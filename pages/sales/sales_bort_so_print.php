<?php
/*   
  @modified by John Paul Pineda.
  @date December 10, 2012.
  @email paulpineda19@yahoo.com         
*/

require_once('../../initialize.php');
require_once('../../tcpdf/config/lang/eng.php');
require_once('../../tcpdf/tcpdf.php');

ini_set('max_execution_time', 500);

global $database;

$branchID=$_GET['branchID'];
$filterID=$_GET['filterID'];
$campaignID=$_GET['campaignID'];
$fromDate=$_GET['fromdate'];
$toDate=$_GET['toDate'];
$vProdLine=$_GET['vProdLine'];
$vCustCode=$_GET['vCustCode'];

$isDetailed=isset($_GET['isDetailed'])?$_GET['isDetailed']:1;

// create new PDF document
$pdf=new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

$pdf->setPrintHeader(false);

// set font
$pdf->SetFont('courier', '', 10);

// add a page
$pdf->AddPage();

$html='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" height="10"><strong>Tupperware Brands Philippines</strong></td>
        </tr>
        <tr>
          <td align="center" height="10"><strong>Shaw Branch</strong></td>
        </tr>
        <tr>
          <td align="center" height="10"><strong>Back Order Report - By Sales Order</strong></td>
        </tr>
        <tr>
          <td align="center" height="10"><strong>'.date('Y-m-d G:i:s').'</strong></td>
        </tr>
        <tr>
          <td align="left" height="10"><strong><hr /></strong></td>
        </tr>
        
        <tr>
          <td align="left" height="10">'.str_repeat('&nbsp;', 12).'Date From: '.date('m/d/Y', strtotime($_GET['fromdate'])).'</td>          
        </tr>
        <tr>
          <td align="left" height="10">'.str_repeat('&nbsp;', 12).'To: '.date('m/d/Y', strtotime($_GET['toDate'])).'</td>          
        </tr>
        <tr>
          <td align="left" height="10">'.str_repeat('&nbsp;', 12).'Product Line: '.$_GET['vProdLine'].'</td>          
        </tr>
        <tr>
          <td align="left" height="10">'.str_repeat('&nbsp;', 12).'SO Number: </td>          
        </tr>
        <tr>
          <td align="left" height="10"><strong><hr /></strong></td>
        </tr>        
       </table><br />';       

// Print text using writeHTML()
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetFont('courier', '', 6);

$query=$tpi->selectBORptBySO($database, $isDetailed, $filterID, $branchID, $fromDate, $toDate, $vProdLine, $vCustCode, $campaignID);

$num=$query->num_rows;	

if($query->num_rows!=0) {

  $j=0;
   
  // Estimated string length of product description when the TCPDF engine will wrap the text,
  // therfore consuming and extra row
  $productLenThreshold=30;
  
  // Threshold to determine whether the number of rows per page should be decremented
  // to accomodate product whose length is greater than $productLenThreshold
  $rowDeletionThreshold=2; 
  
  // Counter of current row deletion threshold
  $deletionThreshold=0;
  
  // Estimated number of rows per page
  $numRowsPerPage=12;
  
  $numRows=$numRowsPerPage;
  
  $has_header_been_displayed=false;
  
  $strTable='';
  $total='';  
  $cnt=0;
  $tmpSOID='';
  $tmpServed=0;
  $tmpOrdered=0;
  $tmpBackOrdered=0;
  
  while($row=$query->fetch_object()) {	    
  
    $soID=$row->soID;
    
    if($j<$numRows) {
    
      if($isDetailed=='1') {
      
        if($tmpSOID!=$soID) {
        
          if($cnt!=0) {
          
            $strTable.='<tr align="center" class="tab">          	
                        	<td height="20" align="right"  colspan="8"><b>Total :  </b></td>
                        	<td height="20"  align="center" width="7%">'.$tmpOrdered.'</td>
                        	<td height="20"  align="center" width="7%">'.$tmpServed.'</td>
                        	<td height="20"  align="center" width="7%">'.$tmpBackOrdered.'</td>
                    	  </tr>';
          }
          
          $tmpServed=0;
          $tmpOrdered=0;
          $tmpBackOrdered=0;
          
          $strTable.='<tr align="center">
                        <td height="20"   align="center" width="5%">'.$row->SONo.'</td>
                      	<td height="20"   align="center" width="7%">'.$row->TxnDate.'</td>
                    	  <td height="20"  align="center" width="5%">'.$row->prodCode.'</td>
                      	<td height="20" align="center" width="20%">'.$row->prodName.'</td>
                      	<td height="20"  align="center" width="12%">'.$row->prodLine.'</td>
                      	<td height="20"   align="center" width="5%">'.$row->branchName.'</td>
                      	<td height="20"   align="center" width="8%">'.$row->custCode.'</td>
                      	<td height="20"   align="center" width="15%">'.$row->custName.'</td>					          	
                      	<td height="20" class="borderBR" align="center" width="7%">'.$row->orderedQty.'</td>
                      	<td height="20" class="borderBR" align="center" width="7%">'.$row->servedQty.'</td>
                      	<td height="20" class="borderBR" align="center" width="7%">'.$row->boQty.'</td>
                      </tr>';
          
          $tmpServed=$row->servedQty;
          $tmpOrdered=$row->orderedQty;
          $tmpBackOrdered=$row->boQty;    
        } else {
        
          $strTable.='<tr align="center">
                        <td height="20" align="center" width="5%"> </td>
                        <td height="20" align="center" width="7%"> </td>
                      	<td height="20"  align="center" width="5%">'.$row->prodCode.'</td>
                      	<td height="20"  align="center" width="20%">'.$row->prodName.'</td>
                      	<td height="20"  align="center" width="12%">'.$row->prodLine.'</td>
                      	<td height="20" align="center" width="5%">'.$row->branchName.'</td>
                      	<td height="20" align="center" width="8%">'.$row->custCode.'</td>
                      	<td height="20" align="center" width="15%">'.$row->custName.'</td>                						          	
                      	<td height="20"  align="center" width="7%">'.$row->orderedQty.'</td>
                      	<td height="20"  align="center" width="7%">'.$row->servedQty.'</td>
                      	<td height="20"   align="center" width="7%">'.$row->boQty.'</td>
                      </tr>';
            
          $tmpServed=$tmpServed+$row->servedQty;
          $tmpOrdered=$tmpOrdered+$row->orderedQty;
          $tmpBackOrdered=$tmpBackOrdered+$row->boQty;
        }            
      } else {
        
        $strTable.='<tr>                        
                    	<td height="20" align="center" >'.$row->SONo.'</td>
                    	<td height="20" align="center" >'.$row->TxnDate.'</td>
                    	<td height="20" align="center" >'.$row->totalOrderedQty.'</td>
                    	<td height="20" align="center" >'.$row->totalServedQty.'</td>
                    	<td height="20" align="center" >'.$row->totalBOQty.'</td>                      	
                    </tr>';
      }
      
      // Determine if product string length is greater than threshold
      // If it is, subtract the number of rows per page if necessary
      if(strlen($field->pName)>$productLenThreshold) {
      
        // Subtract the number of rows only if we reached threshold of the number
        // of rows whose string length is greater than product length threshold
        if($deletionThreshold!=$rowDeletionThreshold) {
        
          $numRows--;
          $deletionThreshold++;
        } else {
        
          // Reset the current count.
          $deletionThreshold=0;
        }
      }
      $j++;    
    } else {        	            
    
      if($isDetailed=='1') {
      
        $html='<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
                <tr>    
                  <td height="20" align="center" width="5%">SO Number</td>
                  <td height="20"  align="center" width="7%">SO Date</td>
                  <td height="20"   align="center" width="5%">Product Code</td>
                  <td height="20"   align="center" width="20%">Product Description</td>
                  <td height="20"   align="center" width="12%">Product Line</td>
                  <td height="20"   align="center" width="5%">Branch ID</td>
                  <td height="20"  align="center" width="8%">Customer Code</td>
                  <td height="20"  align="center" width="15%">Customer Name</td>          
                  <td height="20"   align="center" width="7%">Ordered Qty</td>
                  <td height="20"   align="center" width="7%">Served Qty</td>
                  <td height="20"   align="center" width="7%">Back Order Qty</td>              
                </tr>'.$strTable.'</table>';
      } else {
        
        $html='<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
                <tr>    
                  <td height="20" align="center" width="8%">SO Number</td>
                  <td height="20"  align="center" width="7%">SO Date</td>                                                                                                                      
                  <td height="20"   align="center" width="7%">Total Ordered Qty</td>
                  <td height="20"   align="center" width="7%">Total Served Qty</td>
                  <td height="20"   align="center" width="7%">Total Back Order Qty</td>              
                </tr>'.$strTable.'</table>';
      }
    
      // We only print the page to PDF if we have enough rows to print.
      $pdf->writeHTML($html, true, false, true, false, '');  
      $pdf->AddPage();
      
      if(!$has_header_been_displayed) {
        
       $has_header_been_displayed=true;
       $numRowsPerPage=16; 
      }
    
      // Reset the row counter and the details. 
      $strTable='';
      $total='';
      $j=0;
      $numRows=$numRowsPerPage;
      
      $query->data_seek($cnt); $cnt--;
    }
    
    $tmpSOID=$soID;
    $cnt++;    		   
  }     
    
  if($cnt==$query->num_rows && $isDetailed=='1') {
    
    $total='<tr align="center" class="tab">      
              <td height="20" align="right" colspan="8"><b>Total :  </b></td>
              <td height="20" align="center" width="7%">'.$tmpOrdered.'</td>
              <td height="20" align="center" width="7%">'.$tmpServed.'</td>
              <td height="20" align="center" width="7%">'.$tmpBackOrdered.'</td>
            </tr>';
  } $query->close();      
    
  // If we have gone through all the items and there are unprinted items left, print them one last time.  
  if($strTable!='') {
    
    if($isDetailed=='1') {
    
      $html='<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
                <tr>    
                  <td height="20" align="center" width="5%">SO Number</td>
                  <td height="20"  align="center" width="7%">SO Date</td>
                  <td height="20"   align="center" width="5%">Product Code</td>
                  <td height="20"   align="center" width="20%">Product Description</td>
                  <td height="20"   align="center" width="12%">Product Line</td>
                  <td height="20"   align="center" width="5%">Branch ID</td>
                  <td height="20"  align="center" width="8%">Customer Code</td>
                  <td height="20"  align="center" width="15%">Customer Name</td>          
                  <td height="20"   align="center" width="7%">Ordered Qty</td>
                  <td height="20"   align="center" width="7%">Served Qty</td>
                  <td height="20"   align="center" width="7%">Back Order Qty</td>              
                </tr>'.$strTable.$total.'</table>';
    } else {
      
      $html='<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
              <tr>    
                <td height="20" align="center" >SO Number</td>
                <td height="20" align="center" >SO Date</td>                          
                <td height="20" align="center" >Total Ordered Qty</td>
                <td height="20" align="center" >Total Served Qty</td>
                <td height="20" align="center" >Total Back Order Qty</td>              
              </tr>'.$strTable.'</table>';
    }
  }      
} else {

  $strTable='<tr align="center"><td height="20" colspan="8" align="center"><strong>No record(s) to display.</strong></td></tr>';
  
  $html='<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
          <tr>
            <td height="20" align="center" width="10%"><strong>Date</strong></td>
            <td height="20" align="center" width="10%"><strong>Dealer</strong></td>
            <td height="20" align="center" width="25%"><strong>Name</strong></td>
            <td height="20" align="center" width="15%"><strong>Reason Code</strong></td>
            <td height="20" align="center" width="10%"><strong>Reference #</strong></td>
            <td height="20" align="center" width="10%"><strong>Debit</strong></td>
            <td height="20" align="center" width="10%"><strong>Credit</strong></td>
            <td height="20" align="center" width="10%"><strong>Remarks</strong></td>
          </tr>'.$strTable.'</table>';    		
}

$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// Close and output PDF document
$pdf->Output('BackOrderReportSO.pdf', 'I');
