<?php
/*   
  @modified by John Paul Pineda
  @date June 4, 2013
  @email paulpineda19@yahoo.com         
*/

ini_set('display_errors', '1');

require_once('../initialize.php');

ob_clean();

$sales_order_session_id=session_id();

$customer_id=trim($_GET['customer_id']);
$customer_code=isset($_GET['is_detailed'])?trim($_GET['customer_code']):'';

$rsDealerInformation=$tpi->getDealerInformationByIDOrCode($customer_id, $customer_code);
if($rsDealerInformation->num_rows) {    
  
  $customer_id=$rsDealerInformation->fetch_object()->ID;
  $rsDealerInformation->data_seek(0);
  
  $open_sales_invoices_details_html='
  <tr>
    <td align="center" valign="middle" colspan="8">&nbsp;</td>
  </tr>    
  <tr bgcolor="pink">
    <td align="center" valign="middle" colspan="8"><font size="2"><b>Sales Invoice Transactions</b></font></td>
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
  </tr>';
  
  $total_penalty_amount=0.00;
  
  $rsOpenSalesInvoices=$tpi->getOpenSalesInvoices($customer_id);
  if($rsOpenSalesInvoices->num_rows) {        
    
    while($field=$rsOpenSalesInvoices->fetch_object()) {            
      
      $amount_due=((float)$field->AmountDue);
      $days_due=((int)$field->DaysDue);                
      
      $total_penalty_amount+=((float)$field->Penalty);             
      
      $open_sales_invoices_details_html.='
      <tr>
        <td align="center" valign="middle">'.$field->SINumber.'</td>        
        <td align="center" valign="middle">'.$field->Documentno.'</td>
        <td align="center" valign="middle">'.$field->SONumber.'</td>      
        <td align="center" valign="middle">'.date('F j, Y', strtotime($field->TxnDate)).'</td>
        <td align="center" valign="middle">'.number_format($amount_due, 2, '.', ',').'</td>
        <td align="center" valign="middle">'.date('F j, Y', strtotime($field->DueDate)).'</td>
        <td align="center" valign="middle">'.$days_due.'</td>
        <td align="center" valign="middle">'.number_format(((float)$field->Penalty), 2, '.', ',').'</td>          
      </tr>';
    }
  } else $open_sales_invoices_details_html.='<tr><td align="center" valign="middle" colspan="8" class="tpi-blinking-link">No record(s) to display.</td></tr>'; 
  
  $dealer_information_details_html='
  <tr bgcolor="pink">
    <td align="center" valign="middle" colspan="8"><font size="2"><b>Dealer General Information</b></font></td>
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
    
    $dealer_information_details_html.='  
    <tr>
      <td align="center" valign="middle">'.$field->Code.'</td>        
      <td align="center" valign="middle">'.$field->Name.'</td>
      <td align="center" valign="middle">'.$field->IBMCode.' - '.$field->IBMName.'</td>
      <td align="center" valign="middle">'.$field->CustomerTypeCode.'</td>
      <td align="center" valign="middle">'.number_format($field->CreditLimit, 2, '.', ',').'</td>
      <td align="center" valign="middle">'.number_format($field->TotalARBalance, 2, '.', ',').'</td>
      <td align="center" valign="middle">'.number_format($total_penalty_amount, 2, '.', ',').' <input type="hidden" id="total_penalty_amount" name="total_penalty_amount" value="'.$total_penalty_amount.'" /></td>
      <td align="center" valign="middle">'.number_format($field->AvailableCredit, 2, '.', ',').'</td>        
    </tr>';
  }
  
  $official_receipts_details_html='';        
  if(isset($_GET['is_detailed'])) {
  
    $official_receipts_details_html='
    <tr>
      <td align="center" valign="middle" colspan="8">&nbsp;</td>
    </tr>  
    <tr bgcolor="pink">
      <td align="center" valign="middle" colspan="8"><font size="2"><b>Payment Transactions</b></font></td>
    </tr>
    <tr>
      <td align="center" valign="middle"><b>OR Number</b></td>        
      <td align="center" valign="middle"><b>Document Number</b></td>      
      <td align="center" valign="middle"><b>OR Date</b></td>
      <td align="center" valign="middle" colspan="2"><b>Total Amount</b></td>
      <td align="center" valign="middle"><b>Total Un-applied Amount</b></td>
      <td align="center" valign="middle"><b>Total Applied Amount</b></td>
      <td align="center" valign="middle"><b>Status</b></td>              
    </tr>';
    
    $rsOfficialReceiptsByCustomerID=$tpi->getOfficialReceiptsByCustomerID($customer_id);
    if($rsOfficialReceiptsByCustomerID->num_rows) {
      
      while($field=$rsOfficialReceiptsByCustomerID->fetch_object()) {
        
        $official_receipts_details_html.='
        <tr>
          <td align="center" valign="middle">'.$field->ORNumber.'</td>        
          <td align="center" valign="middle">'.$field->DocumentNo.'</td>                              
          <td align="center" valign="middle">'.$field->TxnDate.'</td>
          <td align="center" valign="middle" colspan="2">'.number_format($field->TotalAmount, 2, '.', ',').'</td>
          <td align="center" valign="middle">'.number_format($field->TotalAppliedAmt, 2, '.', ',').'</td>
          <td align="center" valign="middle">'.number_format($field->TotalUnappliedAmt, 2, '.', ',').'</td>
          <td align="center" valign="middle">'.$field->TxnStatus.'</td>                  
        </tr>';
      }
    } else $official_receipts_details_html.='<tr><td align="center" valign="middle" colspan="8" class="tpi-blinking-link">No record(s) to display.</td></tr>';
  }
  
  echo '<table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">';
  echo  $dealer_information_details_html;
  echo  $open_sales_invoices_details_html;
  echo  $official_receipts_details_html;
  echo '</table>';        
}
