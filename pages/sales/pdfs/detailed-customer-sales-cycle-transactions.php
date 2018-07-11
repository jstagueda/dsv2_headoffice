<?php
/*   
  @modified by John Paul Pineda
  @date June 5, 2013
  @email paulpineda19@yahoo.com         
*/

ini_set('display_errors', '1');
ini_set('max_execution_time', 1000);

require_once('../../../initialize.php');
require_once('../../../tcpdf/config/lang/eng.php');
require_once('../../../tcpdf/tcpdf.php');

ob_clean();

$sales_order_session_id=session_id();

$customer_id=0;
$customer_code=trim($_GET['customer_code']);

$rsDealerInformation=$tpi->getDealerInformationByIDOrCode($customer_id, $customer_code);
if($rsDealerInformation->num_rows) {

  // PDF settings start here...
  $pdf=new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // Create a new PDF document.

  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); // Set the default monospaced font.
  
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); // Set the auto page breaks.
  
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); // Set the image scale factor.
  
  $pdf->setLanguageArray($l); // Set some language-dependent strings.
  
  $pdf->setPrintHeader(false);
  
  $pdf->SetFont('courier', '', 9); // Set the font.
  
  $pdf->AddPage(); // Add a page.
  
  $x=0;
   	
	$lenThreshold=22; // Estimated string length of specific field when the TCPDF engine will wrap the text, therefore consuming an extra row.
		
	$rowDeletionThreshold=2; // Threshold to determine whether the number of rows per page should be decremented to accomodate customer name whose length is greater than $lenThreshold. 
		
	$deletionThreshold=0; // Counter of the current row deletion threshold.
		
	$numRowsPerPage=28; // Estimated number of rows per page.
	
	$numRows=$numRowsPerPage;
  
  $strTable='';
  // PDF settings end here...               
  
  // "Dealer Information Details" start here...
  $dealer_information_details_html='
  <table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">
    <tr>
      <td align="center" valign="middle" colspan="8"><font size="12"><b>Dealer General Information</b></font></td>
    </tr>  
    <tr>
      <td align="center" valign="middle"><b>Customer Code</b></td>        
      <td align="center" valign="middle"><b>Customer Name</b></td>
      <td align="center" valign="middle"><b>IBM Code / Name</b></td>
      <td align="center" valign="middle"><b>Type</b></td>
      <td align="center" valign="middle"><b>Credit Limit</b></td>
      <td align="center" valign="middle"><b>AR Balance</b></td>
      <td align="center" valign="middle"><b>Unpaid Penalties</b></td>
      <td align="center" valign="middle"><b>Available Credit</b></td>        
    </tr>';
  
  while($field=$rsDealerInformation->fetch_object()) {
  
    $customer_id=$field->ID;
    
    $total_penalty_amount=0.00;
    
    $rsOpenSalesInvoices=$tpi->getOpenSalesInvoices($customer_id, (isset($_GET['is_detailed'])?0:1));
    if($rsOpenSalesInvoices->num_rows) while($open_sales_invoice=$rsOpenSalesInvoices->fetch_object()) $total_penalty_amount+=((float)$open_sales_invoice->Penalty);
    $rsOpenSalesInvoices->data_seek(0); 
    
    $dealer_information_details_html.='  
    <tr>
      <td align="center" valign="middle">'.$field->Code.'</td>        
      <td align="center" valign="middle">'.$field->Name.'</td>
      <td align="center" valign="middle">'.$field->IBMCode.' - '.$field->IBMName.'</td>
      <td align="center" valign="middle">'.$field->CustomerTypeCode.'</td>
      <td align="center" valign="middle">'.number_format($field->CreditLimit, 2, '.', ',').'</td>
      <td align="center" valign="middle">'.number_format($field->TotalARBalance, 2, '.', ',').'</td>
      <td align="center" valign="middle">'.number_format($total_penalty_amount, 2, '.', ',').'</td>
      <td align="center" valign="middle">'.number_format($field->AvailableCredit, 2, '.', ',').'</td>        
    </tr>';
  } $dealer_information_details_html.='</table>';
  
  $pdf->writeHTML($dealer_information_details_html, true, false, true, false, '');
  // "Dealer Information Details" end here...
  
  // "Open Sales Invoices Details" start here...      
  if($rsOpenSalesInvoices->num_rows) {        
    
    while($field=$rsOpenSalesInvoices->fetch_object()) {                
      
      $amount_due=number_format(((float)$field->AmountDue), 2, '.', ',');
      $days_due=((int)$field->DaysDue);                               
      
      $open_sales_invoices_details_html='
      <tr>
        <td align="center" valign="middle">'.$field->SINumber.'</td>        
        <td align="center" valign="middle">'.$field->Documentno.'</td>
        <td align="center" valign="middle">'.$field->SONumber.'</td>      
        <td align="center" valign="middle">'.date('F j, Y', strtotime($field->TxnDate)).'</td>
        <td align="center" valign="middle">'.$amount_due.'</td>
        <td align="center" valign="middle">'.date('F j, Y', strtotime($field->DueDate)).'</td>
        <td align="center" valign="middle">'.$days_due.'</td>
        <td align="center" valign="middle">'.number_format(((float)$field->Penalty), 2, '.', ',').'</td>          
      </tr>';
      
      $strTable.=$open_sales_invoices_details_html;
      
      if($x<$numRows) {                                                     
        
        // Determine if the specific field's string length is greater than the threshold if it is, subtract the number of rows per page if necessary.         
        if(strlen($amount_due)>$lenThreshold) {
        
          // Subtract the number of rows only if we've reached the threshold of the number of rows whose string length is greater than the specific field length threshold.           
          if($deletionThreshold!=$rowDeletionThreshold) {
          
            $numRows--;
            $deletionThreshold++;
          } else {
                      
            $deletionThreshold=0; // Reset the current count.
          }
        }
        
        $x++;				
      } else {                
        
        $html='
        <table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">              
          <tr>
            <td align="center" valign="middle" colspan="8"><font size="12"><b>Sales Invoice Transactions</b></font></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><b>SI Number</b></td>        
            <td align="center" valign="middle"><b>Document Number</b></td>
            <td align="center" valign="middle"><b>SO Number</b></td>  
            <td align="center" valign="middle"><b>SI Date</b></td>
            <td align="center" valign="middle"><b>Amount Due</b></td>
            <td align="center" valign="middle"><b>Due Date</b></td>
            <td align="center" valign="middle"><b>Day(s) Due</b></td>
            <td align="center" valign="middle"><b>Penalties</b></td>          
          </tr>'.$strTable.'</table>';                                                          
                
        $pdf->writeHTML($html, true, false, true, false, ''); // We only write the page to PDF if we have enough rows to print.
        $pdf->AddPage();
        
        // Reset the row counter and the details. 
        $strTable='';
        $x=0;
        $numRows=$numRowsPerPage;					
      }
    }
    
    // If we have gone through all the items and there are unprinted items left, print them one last time.   		 
    if($strTable) {
      
      $html='
      <table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">            
        <tr>
          <td align="center" valign="middle" colspan="8"><font size="12"><b>Sales Invoice Transactions</b></font></td>
        </tr>
        <tr>
          <td align="center" valign="middle"><b>SI Number</b></td>        
          <td align="center" valign="middle"><b>Document Number</b></td>
          <td align="center" valign="middle"><b>SO Number</b></td>  
          <td align="center" valign="middle"><b>SI Date</b></td>
          <td align="center" valign="middle"><b>Amount Due</b></td>
          <td align="center" valign="middle"><b>Due Date</b></td>
          <td align="center" valign="middle"><b>Day(s) Due</b></td>
          <td align="center" valign="middle"><b>Penalties</b></td>          
        </tr>'.$strTable.'</table>';
      
      $pdf->writeHTML($html, true, false, true, false, '');
      # $pdf->AddPage();
      
      // Reset the row counter and the details. 
      $strTable='';
      $x+=7;
      # $numRows=$numRowsPerPage;      
    }
  }    
  // "Open Sales Invoices Details" end here...  
  
  // "Official Receipts Details" start here...                
  $rsOfficialReceiptsByCustomerID=$tpi->getOfficialReceiptsByCustomerID($customer_id);
  if($rsOfficialReceiptsByCustomerID->num_rows) {
    
    while($field=$rsOfficialReceiptsByCustomerID->fetch_object()) {
      
      $total_amount=number_format($field->TotalAmount, 2, '.', ',');
      
      $official_receipts_details_html='
      <tr>
        <td align="center" valign="middle">'.$field->ORNumber.'</td>        
        <td align="center" valign="middle">'.$field->DocumentNo.'</td>                              
        <td align="center" valign="middle">'.$field->TxnDate.'</td>
        <td align="center" valign="middle">'.$total_amount.'</td>
        <td align="center" valign="middle" colspan="2">'.number_format($field->TotalAppliedAmt, 2, '.', ',').'</td>
        <td align="center" valign="middle">'.number_format($field->TotalUnappliedAmt, 2, '.', ',').'</td>
        <td align="center" valign="middle">'.$field->TxnStatus.'</td>                  
      </tr>';
      
      $strTable.=$official_receipts_details_html;
      
      if($x<$numRows) {                                                     
        
        // Determine if the specific field's string length is greater than the threshold if it is, subtract the number of rows per page if necessary.         
        if(strlen($total_amount)>$lenThreshold) {
        
          // Subtract the number of rows only if we've reached the threshold of the number of rows whose string length is greater than the specific field length threshold.           
          if($deletionThreshold!=$rowDeletionThreshold) {
          
            $numRows--;
            $deletionThreshold++;
          } else {
                      
            $deletionThreshold=0; // Reset the current count.
          }
        }
        
        $x++;				
      } else {                
        
        $html='
        <table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">  
          <tr>
            <td align="center" valign="middle" colspan="8"><font size="12"><b>Payment Transactions</b></font></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><b>OR Number</b></td>        
            <td align="center" valign="middle"><b>Document Number</b></td>      
            <td align="center" valign="middle"><b>OR Date</b></td>
            <td align="center" valign="middle"><b>Total Amount</b></td>
            <td align="center" valign="middle" colspan="2"><b>Total Un-applied Amount</b></td>
            <td align="center" valign="middle"><b>Total Applied Amount</b></td>
            <td align="center" valign="middle"><b>Status</b></td>              
          </tr>'.$strTable.'</table>';                                                          
                
        $pdf->writeHTML($html, true, false, true, false, ''); // We only write the page to PDF if we have enough rows to print.
        $pdf->AddPage();
        
        // Reset the row counter and the details. 
        $strTable='';
        $x=0;
        $numRows=$numRowsPerPage;					
      }
    }
    
    // If we have gone through all the items and there are unprinted items left, print them one last time.   		 
    if($strTable) {
      
      $html='
      <table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">  
        <tr>
          <td align="center" valign="middle" colspan="8"><font size="12"><b>Payment Transactions</b></font></td>
        </tr>
        <tr>
          <td align="center" valign="middle"><b>OR Number</b></td>        
          <td align="center" valign="middle"><b>Document Number</b></td>      
          <td align="center" valign="middle"><b>OR Date</b></td>
          <td align="center" valign="middle"><b>Total Amount</b></td>
          <td align="center" valign="middle" colspan="2"><b>Total Un-applied Amount</b></td>
          <td align="center" valign="middle"><b>Total Applied Amount</b></td>
          <td align="center" valign="middle"><b>Status</b></td>              
        </tr>'.$strTable.'</table>';
      
      $pdf->writeHTML($html, true, false, true, false, '');           
    }
  }
  // "Official Receipts Details" end here...                 
  
  // Reset the pointer to the last page.
	$pdf->lastPage();
	
	// Close and output the PDF document.
	$pdf->Output('detailed-customer-sales-cycle-transactions.pdf', 'I');         
}