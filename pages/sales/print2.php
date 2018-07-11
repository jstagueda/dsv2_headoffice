<?PHP 
	include "../../initialize.php";
	include  IN_PATH.DS."scPrint2.php";
	//include  IN_PATH.DS."scPrint.php";


$con="";
$body="";
$totalqty = 0;
$grandtotal=0;
$ctr = 0;	 

$tmpTotalCSP = $totalNCFT + $totalCFT ;

/*$custSelPrice = $totCPI+$tmpTotalCSP;*/
$discGroupSales = $custSelPrice-$basicdisc;
$saleswithvat = $totgrossamt - $totalCPI - $basicdisc - $additionaldiscount;
$vatamt = number_format($saleswithvat / 1.12,2);   
$creditLimit =number_format($creditLimit,2); 
$arOutstandingbalance= number_format($arOutstandingbalance,2);
$discGroupSales = number_format($discGroupSales,2);
$mtdiscGrSales = number_format($mtdiscGrSales,2);
$custSelPrice = number_format($custSelPrice,2);

$tmpTotalCSP = number_format($tmpTotalCSP,2); 
$saleswithvat = number_format($saleswithvat,2); 


$totTWNCFT=0;
$ytdTWNCFT=0;
$totMTDTWNCFT=0;



if($rsUpcomingDues->num_rows)
{	
	$text = "";
	while ($row = $rsUpcomingDues->fetch_object()) 
	{
		 $upcomingDues = $row->OutstandingBalance;
		 $text =  $text.$upcomingDues. '<br>';
	}
}
if ($customeraddress=='')
{
	$customeraddress='--';
}

	if($printctr > 1)
	 {
		$con= " <table width='95%' align='center'>
				<tr>
					<td><strong>**Duplicate Copy -  $printctr copies printed.</strong></td>
				</tr>
				</table>";
     }	
     
$header = "
			<table width='50%' border='0' align='left' cellpadding='0' cellspacing='2'>				
				<tr>
					<td align='left' height='20px' width='55%' valign='middle' >$branchname</td>
					<td align='left' height='20px' width='15%'>&nbsp; </td>
					<td align='left' height='20px' width='30%' valign='top' >$salesinvoice</td>
				</tr>	
				<tr>
					<td align='left' height='20px' valign='middle'>VAT REG: $tinno </td>
					<td align='left' height='20px'>&nbsp; </td>
					<td align='left'  height='20px' valign='top' >$txndate </td>
				</tr>							
				<tr>
					<td align='left' height='20px' valign='middle'>Permit No. $permitno</td>
					<td align='left' height='20px'>&nbsp; </td>
					<td align='left' height='20px'  valign='top' >$reftxnid</td>
				</tr>
				<tr>
					<td align='left' height='20px'  valign='middle' rowspan='2'>$address</td>
					<td align='left' height='20px'>&nbsp; </td>
					<td align='left' height='20px' valign='top' >$serversn </td>
				</tr>
				<tr>
					<td align='left' height='20px'>&nbsp; </td>
					<td align='left'  height='20px' valign='top' >%PAGENO%</td>
				</tr>
			 </table>
			 <table width='50%' border='0' align='center' cellpadding='0' cellspacing='2'>
				<tr>
					<td align='left'  width='2%'>&nbsp;</td>
					<td width='45%' align='left' >$customercode&nbsp;</td>
					<td align='left' >&nbsp;</td>
				</tr>
				<tr>
					<td align='left'  >&nbsp;</td>
					<td align='left' >$customername&nbsp;</td>
					<td align='left'  >&nbsp;</td>
				</tr>
				<tr>
					<td align='left'  >&nbsp;</td>
					<td align='left' >$customeraddress&nbsp;</td>	
					<td align='left'  >&nbsp;</td>				
				</tr>
				<tr>
					<td align='left'  >&nbsp;</td>
					<td align='left' >$customertin</td>
					<td align='left' >$ibm</td>
				</tr>
				</table>
				<br>
				<br>
				<br>
";

//	<table width='87%' border='0' cellspacing='0' cellpadding='0'>
//	  <tr>
//	    <td></td>
//	  </tr>
//	</table>

//$footer = "
//<table width='87%' border='0' cellspacing='0' cellpadding='0'>
//<tr><td>
//
//	<table width='87%' border='0' align='center' cellpadding='0' cellspacing='0' >
//	      <tr >
//	        <td width='5%' align='left' valign='middle' >Total</td>
//	        <td width='25%' align='left' valign='middle' >CSP Less (CPI)</td>
//	        <td width='1%' align='left' valign='middle' >&nbsp;</td>
//	        <td width='10%' align='right' valign='middle' >&nbsp;$totCPI</td>
//	        <td width='10%' align='right' valign='middle'>$tmpTotalCSP</td>
//	      </tr>
//	      <tr >
//	        <td align='left' valign='middle' >Less :</td>
//	        <td align='left' valign='middle' >Basic Discount</td>
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='left' valign='middle'>&nbsp;</td>
//	        <td align='right' valign='middle' > $basicdisc</td>
//	      </tr>
//	      <tr >
//	        <td align='left' valign='middle'>&nbsp;</td>
//	        <td align='left' valign='middle' >Additional Discount</td>
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle' >$addDiscount</td>
//	      </tr>
//	      <tr >
//	        <td colspan='2' align='left' valign='middle'>Sales with VAT</td>
//	        <td align='left' valign='middle'>&nbsp;</td>
//	        <td align='left' valign='middle'>&nbsp;</td>
//	        <td align='right' valign='middle'>$saleswithvat</td>
//	      </tr>
//	      <tr>
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='left' valign='middle' >12% VAT</td>
//	        <td align='left' valign='middle'>&nbsp;</td>
//	        <td align='right' valign='middle' >$vatamount</td>
//	        <td align='right' valign='middle' >&nbsp;</td>
//	      </tr>
//	      <tr >
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='left' valign='middle' >Vatable Sales</td>
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle' >$vatamt</td>
//	        <td align='right' valign='middle' >&nbsp;</td>
//	      </tr>  
//	        <tr >
//	        <td align='left' valign='middle'>Less :</td>
//	        <td align='left' valign='middle' >Additional Discount on Previous Purchase</td>
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle'>$adddiscprev</td>
//	      </tr>
//	      <tr >
//	        <td colspan='2' align='left' valign='middle'>Total Invoice Amount Due</td>        
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle'>$netamount</td>
//	      </tr>        
//	      <tr >
//	        <td align='left' valign='middle'>Less :</td>
//	        <td align='left' valign='middle' >Additional Discount on Previous Purchase</td>
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle'>$adddiscprev</td>
//	      </tr>
//	      <tr >
//	        <td colspan='2' align='left' valign='middle'>Total Invoice Amount Due</td>        
//	        <td align='left' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle' >&nbsp;</td>
//	        <td align='right' valign='middle'>$netamount</td>
//	      </tr>      
//	    </table>	
//</td></tr>
//<tr><td>
// 	<table width='87%' border='0' cellspacing='0' cellpadding='0' align='center'>
//          <tr>
//            <td colspan='7'>This Invoice ( in DGS ) :</td>
//          </tr>
//          <tr>
//            <td width='20%' nowrap='nowrap'>Total CFT :</td>
//            <td align='right'>$totalCFT</td>
//            <td width='2%'>&nbsp;</td>
//            <td width='25%'>MTD CFT : </td>
//            <td align='right'>$basicdisc</td>
//            <td width='5%'>&nbsp;</td>
//            <td>&nbsp;</td>
//          </tr>
//          <tr>
//            <td>Total NCFT :</td>
//            <td align='right'>$totalNCFT</td>
//            <td>&nbsp;</td>
//            <td>MTD NCFT :</td>
//            <td align='right'>$additionaldiscount</td>
//            <td>&nbsp;</td>
//            <td ></td>
//          </tr>
//          <tr>
//            <td nowrap='nowrap'>TOT TW in NCFT :</td>
//            <td align='right'>0.00</td>
//            <td>&nbsp;</td>
//            <td nowrap='nowrap'>TOT MTD TW in NCFT :</td>
//            <td align='right'>0.00</td>
//            <td>&nbsp;</td>
//            <td>&nbsp;</td>
//          </tr>
//          <tr>
//            <td>YTD TW in NCFT :</td>
//            <td align='right'>0.00</td>
//            <td>&nbsp;</td>
//            <td>&nbsp;</td>
//            <td align='right'></td>
//            <td>&nbsp;</td>
//            <td>&nbsp;</td>
//          </tr>
//          <tr>
//            <td>YTD TW in NCFT :</td>
//            <td align='right'>0.00</td>
//            <td>&nbsp;</td>
//            <td>&nbsp;</td>
//            <td align='right'>0.00</td>
//            <td>&nbsp;</td>
//            <td>&nbsp;</td>
//          </tr>
//	</table>
//</td></tr>
//
//</table>";
//	$footer3="<table width='100%' align='left' cellpadding='0' cellspacing='0'   border='0'>
//		<tr>
//			<td width='10%' align='right' valign='top'>CREDIT LINE:</td>
//			<td width='5%' align='right' valign='top'>$creditLimit</td>
//			<td width='10%' align='center' valign='top'>SUMMARY</td>
//			<td width='10%' align='center' valign='top'>This Invoice</td>
//			<td width='10%' align='center' valign='top'>MTD</td>
//			<td width='25%' align='center' rowspan='2'>
//				<font size='2'>*THANK YOU FOR PATRONIZING OUR
//				PRODUCTS* DELAYED PAYMENTS SUBJECT
//				TO PENALTY* AVAIL OF BIG PROMO OFFERS
//				AND DISCOUNTS*</font>
//			</td>
//		</tr>
//		<tr>
//			<td align='right' valign='top'>LESS PURCHASE:</td>
//			<td align='right' valign='top'>$arOutstandingbalance</td>
//			<td align='right' valign='top'>Cust Sel Price:</td>
//			<td align='right' valign='top'>$custSelPrice</td>
//			<td align='right' valign='top'>$mtdCustSelPrice</td>
//		</tr>
//		<tr>
//			<td align='right' valign='top'>AVAILABLE CL:</td>
//			<td align='right' valign='top'>$availableCL</td>
//			<td align='right' valign='top'>Basic Discount:</td>
//			<td align='right' valign='top'>$basicdisc</td>
//			<td align='right' valign='top'>$mtdBasicDisc </td>
//			<td align='left' rowspan='2' valign='top'>
//				Released by: ____________________<br/>
//				Received the above items in good order and
//				condition. I also agree to the terms stated at the
//				back of this invoice.
//			</td>
//		</tr>
//		<tr>
//			<td align='right' nowrap='nowrap' rowspan='3' valign='top'>UPCOMING DUES:</td>
//			<td align='right' rowspan='3' valign='top'>$text
//	
//			</td>
//			<td align='right' valign='top'>Disc Gr Sales:</td>
//			<td align='right' valign='top'>$discGroupSales</td>
//			<td align='right' valign='top'>$mtdiscGrSales</td>
//		</tr>
//		<tr>
//		
//			
//			<td align='right' valign='top'>Add'l Discount:</td>
//			<td align='right' valign='top'>$addDiscount</td>
//			<td align='right' valign='top'>$mtdAddlDisc</td>
//			<td align='center' rowspan='100' valign='top'>
//				________________________<br/>
//				Signature over Printed Name/Date
//			</td>
//		</tr>
//		<tr>
//			<td align='right' valign='top'>Add'l DPP:</td>
//			<td align='right' valign='top'>$prevadditionaldiscount</td>
//			<td align='right' valign='top'>$mtdAddlDpp</td>
//		</tr>
//		<tr>
//			<td align='right' valign='top'></td>
//			<td align='right' valign='top'>&nbsp;</td>
//			<td align='right' valign='top'></td>
//			<td align='right' valign='top'>&nbsp;</td>
//			<td align='right' valign='top'>&nbsp;</td>
//		</tr>	
//	</table>	
//$con
//</body>
//</html>";

$footer = "
	<table width='87%' border='0' align='center' cellpadding='0' cellspacing='0' >
	      <tr >
	        <td width='5%' align='left' valign='middle' >Total</td>
	        <td width='25%' align='left' valign='middle' >CSP Less (CPI)</td>
	        <td width='1%' align='left' valign='middle' >&nbsp;</td>
	        <td width='10%' align='right' valign='middle' >&nbsp;$totCPI</td>
	        <td width='10%' align='right' valign='middle'>$totalLessCPI</td>
	      </tr>
	      <tr >
	        <td align='left' valign='middle' >Less :</td>
	        <td align='left' valign='middle' >Basic Discount</td>
	        <td align='left' valign='middle' >&nbsp;</td>
	        <td align='left' valign='middle'>&nbsp;</td>
	        <td align='right' valign='middle' > $basicdisc</td>
	      </tr>
	      <tr >
	        <td align='left' valign='middle'>&nbsp;</td>
	        <td align='left' valign='middle' >Additional Discount</td>
	        <td align='left' valign='middle' >&nbsp;</td>
	        <td align='left' valign='middle' >&nbsp;</td>
	        <td align='right' valign='middle' >$addDiscount</td>
	      </tr>
	      <tr >
	        <td colspan='2' align='left' valign='middle'>Sales with VAT</td>
	        <td align='left' valign='middle'>&nbsp;</td>
	        <td align='left' valign='middle'>&nbsp;</td>
	        <td align='right' valign='middle'>$saleswithvat</td>
	      </tr>
	      <tr>
	        <td align='left' valign='middle' >&nbsp;</td>
	        <td align='left' valign='middle' >12% VAT</td>
	        <td align='left' valign='middle'>&nbsp;</td>
	        <td align='right' valign='middle' >$vatamount</td>
	        <td align='right' valign='middle' >&nbsp;</td>
	      </tr>
	      <tr >
	        <td align='left' valign='middle' >&nbsp;</td>
	        <td align='left' valign='middle' >Vatable Sales</td>
	        <td align='left' valign='middle' >&nbsp;</td>
	        <td align='right' valign='middle' >$vatamt</td>
	        <td align='right' valign='middle' >&nbsp;</td>
	      </tr>  
	        <tr >
	        <td align='left' valign='middle'>Less :</td>
	        <td align='left' valign='middle' >Additional Discount on Previous Purchase</td>
	        <td align='left' valign='middle' >&nbsp;</td>
	        <td align='right' valign='middle' >&nbsp;</td>
	        <td align='right' valign='middle'>$adddiscprev</td>
	      </tr>	     
	      <tr >
	        <td colspan='2' align='left' valign='middle'>Total Invoice Amount Due</td>        
	        <td align='left' valign='middle' >&nbsp;</td>
	        <td align='right' valign='middle' >&nbsp;</td>
	        <td align='right' valign='middle'>$netamount</td>
	      </tr>      
	    </table>	
 	<table width='87%' border='0' cellspacing='0' cellpadding='0' align='center'>
          <tr>
            <td colspan='7'>This Invoice ( in DGS ) :</td>
          </tr>
          <tr>
            <td width='20%' nowrap='nowrap'>Total CFT :</td>
            <td align='right'>$totalCFT</td>
            <td width='2%'>&nbsp;</td>
            <td width='25%'>MTD CFT : </td>
            <td align='right'>$mtdcft1</td>
            <td width='5%'>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Total NCFT :</td>
            <td align='right'>$totalNCFT</td>
            <td>&nbsp;</td>
            <td>MTD NCFT :</td>
            <td align='right'>$mtdncft1</td>
            <td>&nbsp;</td>
            <td ></td>
          </tr>
          <tr>
            <td nowrap='nowrap'>TOT TW in NCFT :</td>
            <td align='right'>0.00</td>
            <td>&nbsp;</td>
            <td nowrap='nowrap'>TOT MTD TW in NCFT :</td>
            <td align='right'>0.00</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>YTD TW in NCFT :</td>
            <td align='right'>0.00</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align='right'></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>         
	</table>
";


$footer3="<table width='90%' align='left' cellpadding='0' cellspacing='0'   border='0'>
		<tr>
			<td width='10%' align='right' valign='top'>CREDIT LINE:</td>
			<td width='5%' align='right' valign='top'>$creditLimit</td>
			<td width='10%' align='center' valign='top'>SUMMARY</td>
			<td width='10%' align='center' valign='top'>This Invoice</td>
			<td width='10%' align='center' valign='top'>MTD</td>
			<td width='25%' align='center' valign='top' rowspan='7'>*THANK YOU FOR PATRONIZING OUR
		PRODUCTS* DELAYED PAYMENTS SUBJECT
		TO PENALTY* AVAIL OF BIG PROMO OFFERS
		AND DISCOUNTS* Released by: ______________________</td>
		</tr>
		<tr>
			<td align='right' valign='top'>LESS PURCHASE:</td>
			<td align='right' valign='top'>$arOutstandingbalance</td>
			<td align='right' valign='top'>Cust Sel Price:</td>
			<td align='right' valign='top'>$custSelPrice</td>
			<td align='right' valign='top'>$mtdCustSelPrice</td>
		</tr>
		<tr>
			<td align='right' valign='top'>AVAILABLE CL:</td>
			<td align='right' valign='top'>$availableCL</td>
			<td align='right' valign='top'>Basic Discount:</td>
			<td align='right' valign='top'>$basicdisc</td>
			<td align='right' valign='top'>$mtdBasicDisc </td>
		</tr>
		<tr>
			<td align='right' nowrap='nowrap' rowspan='3' valign='top'>UPCOMING DUES:</td>
			<td align='right' rowspan='3' valign='top'>$text
	
			</td>
			<td align='right' valign='top'>Disc Gr Sales:</td>
			<td align='right' valign='top'>$discGroupSales</td>
			<td align='right' valign='top'>$mtdiscGrSales</td>
		</tr>
		<tr>
		
			
			<td align='right' valign='top'>Add'l Discount:</td>
			<td align='right' valign='top'>$addDiscount</td>
			<td align='right' valign='top'>$mtdAddlDisc</td>
		</tr>
		<tr>
			<td align='right' valign='top'>Add'l DPP:</td>
			<td align='right' valign='top'>$prevadditionaldiscount</td>
			<td align='right' valign='top'>$mtdAddlDpp</td>
		</tr>
		<tr>
			<td align='right' valign='top'></td>
			<td align='right' valign='top'>&nbsp;</td>
			<td align='right' valign='top'></td>
			<td align='right' valign='top'>&nbsp;</td>
			<td align='right' valign='top'>&nbsp;</td>
		</tr>	
	</table>
<table cellpadding='0' cellspacing='0'   border='0'>
<tr>
	<td rowspan='2'>Received the above items in good order and condition. I also agree to the terms stated at theback of this invoice.</td>
	<td align='center'>________________________</td>
</tr>
<tr>
	<td align='center'>Signature over Printed Name/Date</td>
</tr>
</table>
$con";



$footer2 ="
	<table>
		<tr>
			<td>LESS PURCHASE:</td>
			<td>&nbsp;</td>
			<td>Cust Sel Price</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
";

 ini_set('display_errors',0);
	require CS_PATH.DS.'html2fpdf.php';
	
	class PDF extends HTML2FPDF
	{
		public $header;
		
		function Header()
		{
			$this->writeHTML(str_replace("%PAGENO%",$this->PageNo(),$this->header));
			//$this->Ln(10);
			
		}
	}
	$pdf=new PDF();
	
	$pdf->header = $header;
	//$pdf->SetMargins(0,16,0);
	$pdf->SetTopMargin(25);
	$pdf->AddPage();	
	$pdf->SetFont('Arial','',9);	
	//$pdf->header = $header;
	
	
	
	if($rsProdSI->num_rows)
	 {
	 	$rows = $rsProdSI->num_rows;
	 	$ctr = 0;
	 	$body = "<table  width='85%' border='0' align='center' cellpadding='0' cellspacing='0'>";
	 	
	 	
	 	while ($row = $rsProdSI->fetch_object()) 
	 	{
	 		if ($ctr<20)
	 		{
	 			$body .= "<tr >
						  		<td width='10%' align='left' valign='middle'>$row->ProductCode</td>
						  		<td width='35%' align='left'  valign='middle' >$row->Product</td>
						  		<td width='10%' align='left' valign='middle' >$row->PMGName</td>
						  		<td width='10%' align='right' valign='middle' >$row->Qty</td>
						  		<td width='15%' align='right' valign='middle' >$row->UnitPrice</td>
						  		<td width='15%' align='right' valign='middle' >$row->TotalAmount</td>
						  	  </tr>";
						$totalqty += $row->Qty;
						$grandtotal += $row->TotalAmount;
				$ctr++;
	 		}
	 		else 
	 		{
	 			$body .= "</table>";
	 			$pdf->WriteHTML($body);	 			
	 			$pdf->AddPage();	 			
	 			$body = "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
	 			$ctr=0;	
	 		}
		}
		if ($ctr>0)
		{
			$grandtotal = number_format($grandtotal,2);
			$body .= "</table>
			<table width='85%' border='0' align='center' cellpadding='0' cellspacing='0'>		
		      <tr >
				<td width='10%' align='left' valign='middle'>&nbsp;</td>
				<td width='35%' align='left'  valign='middle' >Total with CPI :</td>
				<td width='10%' align='left' valign='middle' >&nbsp;</td>
				<td width='10%' align='right' valign='middle'>$totalqty</td>
				<td width='15%' align='right' valign='middle'>&nbsp;</td>
				<td width='15%' align='right' valign='middle' > $grandtotal</td>
			 </tr>			 
			</table>";
			
 			$pdf->WriteHTML($body);	 			
 			$pdf->AddPage();	 			
 			$body = "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
 			$ctr=0;	
		}
		
		$pdf->WriteHTML($footer);
		// $pdf->AddPage();	
		$pdf->WriteHTML($footer3);
	}
	else
	{
		$body = "<tr align='center'><td height='20' >No record(s) to display. </td></tr>";
		$pdf->WriteHTML($body);
		$pdf->AddPage();
	}
	
	$pdf->Output("SalesInvoiceDetails.pdf", "D");


?>

