<!DOCTYPE html>
<html>
  <head>
    <style>
    @media print {
    
    …
    }
    
    header nav, footer {
    
      display: none;
    }
    
    .Page {
    
      height: 900px
      width: 595px;
      page-break-after: always; /* Always insert page break after this element */
      page-break-inside: avoid; /* Please don't break my page content up browser */
      font-style:normal;
      font-size:14px;
      font-family:"Arial";
    }
    </style>
    
    <script type = "text/javascript" src = "../../js/jquery-ui-1.10.0.custom.min.js" ></script>
    <script type = "text/javascript" src = "../../js/jquery-1.8.3.min.js"></script>  
  <head>
<body>
<div class="Page">
<?php 
require_once('../../initialize.php');
require_once(IN_PATH.DS.'scViewSalesInvoiceDetails2.php');

global $database, $printctr;

$txnid = $_GET['tid'];

$saleswithvat 			= $totgrossamt - $basicdiscount - $additionaldiscount;
$vat12 					= number_format($saleswithvat- $vatamt,2);
$saleswithvat 			= number_format($saleswithvat,2);
$discGroupSales 		= $totgrossamt - $basicdiscount;
$discGroupSales 		= number_format($discGroupSales ,2);  
$totalLessCPI 			= number_format($totgrossamt - $totalCPI ,2);
$basicdiscount 			= number_format($basicdiscount ,2); 
$additionaldiscount 	= number_format($additionaldiscount ,2);

$prevadditionaldiscount = number_format($prevadditionaldiscount ,2); 
$totgrossamt 			= number_format($totgrossamt ,2);

$vatamt 	 		  	= number_format($vatamt,2);
$totalnetamt 		  	= number_format($totalnetamt ,2);
$creditLimit 		  	= number_format($creditLimit,2);
$totalCPI 	 		  	= number_format($totalCPI,2);
$totalCFT 	 		  	= number_format($totalCFT,2);
$totalNCFT 	 		  	= number_format($totalNCFT,2);
$v_mtdcft 	 		  	= number_format($v_mtdcft ,2);
$v_mtdncft 	 		  	= number_format($v_mtdncft ,2);
$availableCL 		  	= number_format($availableCL,2);
$arOutstandingbalance 	= number_format($arOutstandingbalance,2);
$mtdCustSelPrice 	  	= number_format($mtdCustSelPrice,2);
$mtdBasicDisc 		  	= number_format($mtdBasicDisc,2);
$mtdAddlDisc  		  	= number_format($mtdAddlDisc,2);
$mtdAddlDpp 		  	= number_format($mtdAddlDpp,2);
$mtdiscGrSales 		  	= number_format($mtdiscGrSales,2);
$ytdiscGrSales 		  	= number_format($ytdCustSelPrice-$ytdBasicDisc,2);
$ytdCustSelPrice	  	= number_format($ytdCustSelPrice,2);
$ytdBasicDisc 		  	= number_format($ytdBasicDisc,2);
$ytdAddlDisc 		  	= number_format($ytdAddlDisc,2);
$ytdAddlDpp 		  	= number_format($ytdAddlDpp,2);
$space 				  	= ' ';
$printctr 	   		  	= $printCnt + 1;

$rs_update = $sp->spUpdateSIPrntCntr($database, $printctr, $txnid);
if (!$rs_update)
{ 
throw new Exception("An error occurred, please contact your system administrator");
}
//with computation "sales with vat","vat12"

if($rsUpcomingDues->num_rows)
{	
$text = "";
$dates = "";
while ($row = $rsUpcomingDues->fetch_object()) 
{

$upcomingDues = $row->OutstandingBalance;
$text =  $text.$upcomingDues. '<br>';

$dueDate = strtotime(date("Y-m-d", strtotime($row->EffectivityDate)) . " 0 day");
$dueDate = date("m/d/Y",$dueDate);

$dates=$dates.$dueDate.'<br>';
}
}

/*if ($customeraddress=='')
{
$customeraddress='--';
}
*/
if($printctr > 1){
$con= "<td width='' rowspan = '3' align='left' ><font size='3'><u><b>REPRINTED COPY</b></u></font></td>
<td align='left' >&nbsp;</td>";
}else{
$con = "<td align='left' >&nbsp;</td>";
}	

$header = "<br /><br /><br /><br /><table width='100%' border='0' align='center' cellpadding='0' cellspacing='2'>	

<tr>
<td align='left' height='20px' width='38%'>$branchname</td>
<td align='left' height='20px' width='15%'>&nbsp; </td>
<td align='left' height='20px' width='30%' valign='top' >$salesinvoice</td>

</tr>	
<tr>
<td align='left' height='20px' valign='middle'>VAT REG: $tinno </td>
<td align='left' height='20px'>&nbsp; </td>
<td align='left' height='20px' width='30%' valign='top' >$txnDateFormat</td>

</tr>							
<tr>
<td align='left' height='20px' valign='middle'>Permit No. $permitno</td>
<td align='left' height='20px'>&nbsp; </td>
<td align='left'  height='20px' valign='top' >$reftxnid </td>

</tr>
<tr>
<td align='left' height='20px'  valign='middle' rowspan='2'>$address</td>
<td align='left' height='20px'>&nbsp; </td>
<td align='left' height='20px'  valign='top' >$serversn</td>
</tr>
<tr>
<td align='left' height='20px'>&nbsp; </td>
<td align='left'  height='20px' valign='top' ></td>
</tr>

<table width='70%' border='0' align='center' cellpadding='0' cellspacing='2'>

<tr>
<td align='left'  width='2%'>&nbsp;</td>
<td width='60%' align='left' >$custcode&nbsp;</td>
".$con."
</tr>

<tr>
<td align='left'  >&nbsp;</td>
<td align='left' width='60%' >$custname&nbsp;</td>
<td align='left'  >&nbsp;</td>
</tr>
<tr>
<td align='left'  >&nbsp;</td>
<td align='left'width='60%' >$customeraddress&nbsp;</td>	
<td align='left' >&nbsp;</td>				
</tr>
<tr>
<td align='left'  >&nbsp;</td>
<td align='left' width='60%' >$customertin</td>
<td align='left' >$customeribmcode</td>
</tr>

</table>
<br>
";
echo $header;

$footer = "<table width='70%' border='0' align='left' cellpadding='0' cellspacing='0' >
<tr>
<td width='%' 	align='left' 	valign='middle' >Total</td>
<td width='53%' align='left' 	valign='middle' >CSP Less (CPI)</td>
<td width='%' 	align='left' 	valign='middle' >&nbsp;</td>
<td width='%' 	align='right' 	valign='middle' >$totalCPI&nbsp;&nbsp;&nbsp;</td>
<td width='18%' align='left' 	valign='middle'>$totalLessCPI</td>
</tr>
<tr>
<td width='%' 	align='left'  valign='middle' >Less :</td>
<td width='50%' align='left'  valign='middle' >Basic Discount</td>
<td width='%' 	align='left'  valign='middle' >&nbsp;</td>
<td width='%' 	align='right' valign='middle'>&nbsp;</td>
<td width='24%' align='left'  valign='middle' >$basicdiscount</td>
</tr>
<tr >
<td width='%' 	align='left'  valign='middle'>&nbsp;</td>
<td width='53%' align='left'  valign='middle' >Additional Discount</td>
<td width='%' 	align='left'  valign='middle' >&nbsp;</td>
<td width='%' 	align='right' valign='middle' >&nbsp;</td>
<td width='18%' align='left'  valign='middle' >$additionaldiscount</td>
</tr>
<tr >
<td colspan='2' align='left' valign='middle'>Sales with VAT</td>
<td align='left' valign='middle'>&nbsp;</td>
<td align='left' valign='middle'>&nbsp;</td>
<td align='left' valign='middle'>$saleswithvat</td>
</tr>
<tr>
<td width='%' 	align='left' valign='middle' >&nbsp;</td>
<td width='53%' align='left' valign='middle' >12% VAT</td>
<td width='%' 	align='left' valign='middle'> </td>
<td width='%' 	align='left' valign='middle' >$vatamount</td>
<td width='18%' align='left' valign='middle' >$vatamt</td>
</tr>
<tr >
<td width='%' 	align='left' valign='middle' >&nbsp;</td>
<td width='53%' align='left' valign='middle' >Vatable Sales</td>
<td width='%' 	align='left' valign='middle' >&nbsp;</td>
<td width='%' 	align='left' valign='middle' ></td>
<td width='18%' align='left' valign='middle' >$vat12</td>
</tr>  
<tr >
<td width='%' 	align='left' valign='middle'>Less :</td>
<td width='53%' align='left' valign='middle' >Additional Discount on Previous Purchase</td>
<td width='%' 	align='left' valign='middle' >&nbsp;</td>
<td width='%' 	align='left'  valign='middle' >&nbsp;</td>
<td width='18%' align='left'  valign='middle'>$prevadditionaldiscount</td>
</tr>	     
<tr >
<td colspan='2' align='left' valign='middle'>Total Invoice Amount Due</td>        
<td align='left' valign='middle' >&nbsp;</td>
<td align='left' valign='middle' >&nbsp;</td>
<td align='left' valign='middle'>$totalnetamt</td>
</tr>      
</table><br />	
<table width='73%' border='0' cellspacing='0' cellpadding='0' align='left'>
<tr>
<td colspan='7'>This Invoice ( in DGS ) :</td>
</tr>
<tr>
<td width='15%' nowrap='nowrap'>Total CFT :</td>
<td align='left'>$totalCFT</td>
<td width='2%'>&nbsp;</td>
<td width='25%'>MTD CFT : </td>
<td align='left'>$v_mtdcft</td>
<td width='5%'>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>Total NCFT :</td>
<td align='left'>$totalNCFT</td>
<td>&nbsp;</td>
<td>MTD NCFT :</td>
<td align='left'>$v_mtdncft</td>
<td>&nbsp;</td>
<td ></td>
</tr>
<tr>
<td nowrap='nowrap'>TOT TW in NCFT :</td>
<td align='left'>0.00</td>
<td>&nbsp;</td>
<td nowrap='nowrap'>TOT MTD TW in NCFT :</td>
<td align='left'>0.00</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>YTD TW in NCFT :</td>
<td align='left'>0.00</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align='right'></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>         
</table><br />
";
# echo $footer."<br />";
$footer3="<table width='73%' align='left' cellpadding='0' cellspacing='0'   border='1'>
<tr>
<td width='10%' align='right' valign='top'>CREDIT LINE:</td>
<td width='5%' align='right' valign='top'>$creditLimit</td>
<td width='10%' align='center' valign='top'>SUMMARY</td>
<td width='10%' align='center' valign='top'>This Invoice</td>
<td width='10%' align='center' valign='top'>MTD</td>
<td width='10%' align='center' valign='top'>YTD</td>

</tr>
<tr>
<td width='10%' align='right' valign='top'>CREDIT LINE:</td>
<td width='5%' align='right' valign='top'>$creditLimit</td>
<td width='10%' align='center' valign='top'>SUMMARY</td>
<td width='10%' align='center' valign='top'>This Invoice</td>
<td width='10%' align='center' valign='top'>MTD</td>
<td width='10%' align='center' valign='top'>YTD</td>

</tr>
<tr>
<td align='right' valign='top'>LESS PURCHASE:</td>
<td align='right' valign='top'>$arOutstandingbalance</td>
<td align='right' valign='top'>Cust Sel Price:</td>
<td align='right' valign='top'>$totgrossamt</td>
<td align='right' valign='top'>$mtdCustSelPrice</td>
<td align='right' valign='top'>$ytdCustSelPrice</td>

</tr>
<tr>
<td align='right' valign='top'>AVAILABLE CL:</td>
<td align='right' valign='top'>$availableCL</td>
<td align='right' valign='top' nowrap='nowrap'>Basic Discount:</td>
<td align='right' valign='top'>$basicdiscount</td>
<td align='right' valign='top'>$mtdBasicDisc </td>
<td align='right' valign='top'>$ytdBasicDisc</td>

</tr>
<tr>
<td align='right' rowspan='1' nowrap='nowrap' valign='top'>UPCOMING DUES:</td>			
<td align='right' rowspan='1' valign='top'></td>
<td align='right' valign='top'>Disc Gr Sales:</td>
<td align='right' valign='top'>$discGroupSales</td>
<td align='right' valign='top'>$mtdiscGrSales</td>
<td align='right' valign='top'>$ytdiscGrSales</td>

</tr>
<tr>
<td align='right' rowspan='5' valign='top'>$dates</td>
<td align='right' rowspan='5' valign='top'>$text</td>
<td align='right' valign='top' >Add'l Discount:</td>
<td align='right' valign='top'>$additionaldiscount</td>
<td align='right' valign='top'>$mtdAddlDisc</td>
<td align='right' valign='top'>$ytdAddlDisc</td>			
</tr>
<tr valign='top'>	
<td align='right' valign='top'>Add'l DPP:</td>
<td align='right' valign='top'>$prevadditionaldiscount</td>
<td align='right' valign='top'>$mtdAddlDpp</td>
<td align='right' valign='top'>$ytdAddlDpp</td>			
</tr>			
</table><br />";
# echo $footer3."<br />";
$footer4="	<table width='73%'  cellpadding='0' cellspacing='0' border='1 ' align = 'left'>
<tr>
<td   width='73%' align='left' >
*THANK YOU FOR PATRONIZING OUR
PRODUCTS* DELAYED PAYMENTS SUBJECT
TO PENALTY* <br />*AVAIL OF BIG PROMO OFFERS
AND DISCOUNTS* <br>Released by: ______________________<br />Received the above items in good order and condition. 
I also agree to the terms stated at the back of this invoice.<br />
________________________<br>
Signature over Printed Name/Date
</td>
</tr>
</table>
";
//$con
# echo $footer4 ."<br />";
$footer2 ="<table align = 'center'>
<tr>
<td>LESS PURCHASE:</td>
<td>&nbsp;</td>
<td>Cust Sel Price</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>";
# echo $footer2 ."<br />";




if($rsProdSI->num_rows)
{
$rows = $rsProdSI->num_rows;
$ctr = 1;
$body = "<br /><br /><br /><table  width='90%' border='0' align='left' cellpadding='0' cellspacing='0'>";
$page = 1;
while ($row = $rsProdSI->fetch_object()) 
{
//if ($ctr + 13 < 30)
if ($ctr + 13 < 35)
{
$totamt = number_format($row->TotalAmount, 2);
$body .= "<tr>
<td width='%' align='left'  valign='top'></td>
<td width='10%' align='left'  valign='top'>$row->ProductCode</td>
<td width='66%' align='left'  valign='top' >$row->Product</td>
<td width='11%' align='left' valign='top' >$row->Qty</td>
<td width='6%' align='right' valign='top' >$row->UnitPrice</td>
<td width='%' align='right' valign='top' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$totamt</td>
</tr>";
$totalqty += $row->Qty;
$grandtotal += $row->TotalAmount;
$ctr++;					
}
else 
{
$body .= "</table><br />";
echo $body;
$height  = 620*$page;
$body = "<div style='height:".$height."px;'></div>".$header."<br /><br /><br /><br /><table width='86%' border='0' align='left' cellpadding='0' cellspacing='0'>";
$ctr = 1;

}
}

// Promo Entitlements start here...
if($rsAppliedPromoEntitlements->num_rows) {            

while($field=$rsAppliedPromoEntitlements->fetch_object()) {      

if($ctr+13<35) {

$totamt=number_format($field->NetAmount, 2);
$body.="<tr>
<td width='%' align='left'  valign='top'></td>
<td width='10%' align='left'  valign='top'>$field->ItemCode</td>
<td width='66%' align='left'  valign='top' >$field->ItemName</td>
<td width='11%' align='left' valign='top' >$field->Quantity</td>
<td width='6%' align='right' valign='top' >$field->CSP</td>
<td width='%' align='right' valign='top' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$totamt</td>
</tr>";
$totalqty+=$field->Quantity;
$grandtotal+=$field->NetAmount;
$ctr++;					
} else  {

$body.="</table><br />";
echo $body;
$height=620*$page;
$body="<div style='height:".$height."px;'></div>".$header."<br /><br /><br /><br /><table width='86%' border='0' align='left' cellpadding='0' cellspacing='0'>";
$ctr=1;		
}
}
}
// Promo Entitlements end here...
if ($ctr>0)
{
$grandtotal = number_format($grandtotal,2);
$body .= "</table>
<table width='77%' border='0' align='center' cellpadding='0' cellspacing='0'>		
<tr>
<td align='left'  width='2%'>&nbsp;</td>
<td width='45%' align='left' >&nbsp;</td>
<td align='left' >&nbsp;</td>
</tr>
<tr >
<td width='0%' align='left' valign='middle'>&nbsp;</td>
<td width='30%' align='left'  valign='middle' >Total with CPI :</td>
<td width='0%' align='left' valign='middle' >&nbsp;</td>
<td width='12%' align='left' valign='middle'>$totalqty</td>
<td width='7%' align='right' valign='middle'>&nbsp;</td>
<td width='8%' align='left' valign='middle' > $grandtotal</td>
</tr>			 
</table>";
//$totalpage = $page * 30;
$totalpage = $page * 35;
$minuspage = $page * 5;

if(($ctr + 13) > ($totalpage - $minuspage)){	
//-200
$height  = 240*$page;
echo $body."<div style='height:".$height."px;'></div>$header <br /><br /><br /><br />";
}else{
echo $body;
}			


$body = "<table width='90%' border='0' align='center' cellpadding='0' cellspacing='0'>";

}


}
else
{
$body = "<tr align='center'><td height='20' >No record(s) to display. </td></tr>";
echo $body;
}
echo $footer;
echo $footer3;
echo $footer4;
// echo $footer2 ."<br />";
//$pdf->Output("SalesInvoiceDetails.pdf", "I");
?>
</div>
</body>
</html>