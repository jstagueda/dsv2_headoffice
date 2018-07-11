<?PHP 

   	include "../../initialize.php";
   	//include IN_PATH.DS."scViewSalesInvoiceDetails2.php";
   	global $database;
   	global $printctr;
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
				function Footer()
				{
					global $printctr;
					
							
					$this->SetY(-19);
				//  Arial italic 8
					$this->SetFont('Arial','B',8);
				//  Page number
				
					if($_GET['reprint'] == 1)
					{
						
					$this->Cell(0,0,"**Duplicate Copy -  {$printctr} copies printed.",0,0,'L');
					}
				
				}
			
				
			}

	$errmsg = "";
	$month = date("m"); 
	$year  = date("Y");
	$MTDCFT			 	= 0;
	$MTDCFTDisc  	 	= 0;
	$YTDCFT	 		 	= 0;
	$MTDNCFT 		 	= 0;
	$MTDNCTDisc 	 	= 0; 
	$YTDNCFT 		 	= 0;
 	$customeraddress = "";
 	$customercode = "";
	$customername ="";
	$customeribmcode = "";
	$customertin     ="";
	$creditLimit = 0;
	
 	$ytdCustSelPrice=0;
	$ytdBasicDisc = 0;
	$ytdAddlDisc = 0;
	$ytdAddlDpp = 0;	
	$ytdiscGrSales =0;	
	
	
	$qMTDCFT = 0;
	$qMTDNCFT = 0;
   	$qYTDCFT = 0;
    $qYTDNCFT =0;
    $qNextLevelCFT = 0;
	$qNextLevelNCFT = 0;
	
	$rs_reason = $sp->spSelectReason($database,4, '');
	$x_ctr = 0;
	//retrieve latest bir series
	$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 7);
	if ($rs_birseries->num_rows)
	{
		while ($row = $rs_birseries->fetch_object())
		{
			$bir_series	= $row->Series;
			$bir_id = $row->NextID;			
		}		
	}
	else
	{
		$rs_branch = $sp->spSelectBranch($database, -2, "");
		if ($rs_branch->num_rows)
		{
			while ($row = $rs_branch->fetch_object())
			{
				$bir_series	= $row->Code."700000001";
				$bir_id = 1;				
			}
		}
	}
	
	
	
			$txnid = $_POST['chkInclude'];
			$rs_detailsall = $sp->spSelectSalesInvoiceDetailsByID($database,$txnid);
			$rs=$tpi->selectSalesInvoiceByID($database, $txnid);
			
			if ($rs->num_rows)
			{
			while ($row = $rs->fetch_object())
			{
				$sino = $row->SINo;
				$id = $row->TxnID;
				$docno = $row->DocumentNo;
				$txndate = $row->SIDate;
				$txnDateFormat = date("m/d/Y h:i A", strtotime($row->TxnDate));
				//$txnDateFormat = $row->TxnDate;
				$tmpDate = strtotime($txnDateFormat);
				$year_si = date("Y",$tmpDate);
				$month_si = date("m",$tmpDate);
				$effectivitydate = $row->EffectivityDate;
				$status = $row->TxnStatus;
				$statid = $row->TxnStatusID;
				$remarks = $row->Remarks;
				$rsAppliedPromoEntitlements=$tpi->getAppliedPromoEntitlements($row->SOID);
				$refno = $row->RefNo;
					$termsduration = $row->TermsDuration;
				$drdate = $row->DRDate;
				$custId = $row->CustomerID;
				$custcode = $row->CustomerCode;
				$custname = $row->CustomerName;
				$terms = $row->Terms;
				$outstandingBalance = $row->OutstandingBalance;
				$totgrossamt = $row->GrossAmount;
				$basicdiscount = $row->BasicDiscount;
				$additionaldiscount = $row->AddtlDiscount;
				$prevadditionaldiscount = $row->AddtlDiscountPrev;
				$vatamt = $row->VatAmount;
				$totalnetamt = $row->NetAmount;
				$ibm = $row->IBM;
				$totalCFT = $row->TotalCFT;
				$totalNCFT = $row->TotalNCFT;
				$totalCPI = $row->TotalCPI;
				$ibmCode = $row->IBMCode;
				$customerType = $row->CustomerTypeID;
				$statusId = $row->StatusID;
				$qMTDCFT =  $row->MTDCFT;
				$qMTDNCFT = $row->MTDNCFT;
				$qYTDCFT = $row->YTDCFT;
				$qYTDNCFT = $row-> YTDNCFT;
				$qNextLevelCFT = $row->NextLevelCFT;
				$qNextLevelNCFT = $row->NextLevelNCFT;
				
				
				//for printing
				$printCnt = $row->PrintCounter;
				$reftxnid 		= false?$row->RefTxnID:$row->SOID;
				$reftxnid 		= "SO".str_pad($reftxnid, 8, "0", STR_PAD_LEFT);
				$salesinvoice   = "".str_pad( $docno, 8, "0", STR_PAD_LEFT);
			}
			}
			//echo $termsduration;
		
			$rsYTDCFT = $sp->spSelectYTDbyCustomerID($database, $custId, $year_si, 1);
			if($rsYTDCFT->num_rows)
			{
				while($tmpYTDCFT = $rsYTDCFT->fetch_object())
				{
					$YTDCFT = $tmpYTDCFT->Amount;
				}
			}
		
			$rsMTDCFT = $sp->spSelectMTDbyCustomerID($database,$custId,$month,$year,1);
						
			if($rsMTDCFT->num_rows)
			{
				while($tmpMTDCFT = $rsMTDCFT->fetch_object())
				{
					$MTDCFT		 = $tmpMTDCFT->Amount;
					$MTDCFTDisc	 = $tmpMTDCFT->DiscountID;
					//echo $MTDCFT;
				}
			}
			$rsMTDNCFT = $sp->spSelectMTDbyCustomerID($database,$custId,$month,$year,2);
						
			if($rsMTDNCFT->num_rows)
			{
				while($tmpMTDNCFT = $rsMTDNCFT->fetch_object())
				{
					$MTDNCFT 	= $tmpMTDNCFT->Amount;
					$MTDNCTDisc = $tmpMTDNCFT->DiscountID;
					//echo $MTDNCFT;
				}
			}
			$rsYTDNCFT = $sp->spSelectYTDbyCustomerID($database, $custId, $year_si, 2);
			if($rsYTDNCFT->num_rows)
			{
				while($tmpYTDNCFT = $rsYTDNCFT->fetch_object())
				{
					$YTDNCFT = $tmpYTDNCFT->Amount;
				}
			}
			
			//FOR Printing #########################################################################
			$rsUpcomingDues= $sp->spSelectSIUpcomingDues($database,$custId,$txnid); 
			$rsBranch = $sp->spSelectBranch($database, -2,''); 
			if($rsBranch->num_rows){
				while ($row = $rsBranch->fetch_object()) {
					$permitno 	= $row->PermitNo;
					$branchname = $row->Name;
					$tinno 		= $row->TIN;
					$address    = $row->StreetAdd;
					$serversn   = $row->ServerSN;
					$branchcode = $row->Code;			
				}
			}
			
			$rsCustomer = $sp->spSelectCustomerDetails($database,$custId); 
			if($rsCustomer->num_rows){
				while ($row = $rsCustomer->fetch_object()) 
				{
					$customercode = $row->Code;
					$customername = $row->Name;
					$customeribmcode = $row->IBMNo;
					$customertin     = $row->TIN;
					//echo "<pre>";
					//print_r($row);
					//echo "</pre>";
				}
			}
			if ($customercode=='')
			{
				$customercode = "N/A";
			}
			if ($customername=='')
			{
				$customername = "N/A";
			}
			if ($customeribmcode=='')
			{
				$customeribmcode = "N/A";	
				
			}
			
			
			if ($customertin=='')
			{
					$customertin = "N/A";
			}
			if ($customeraddress=='')
			{
				$customeraddress ="N/A";
			}
			
			$rsProdSI= $sp->spSelectProductSalesInvoice($database,$txnid,''); 
			
			$rsCreditLimit = $sp->spSelectCreditLimitByCustomerID($database,$custId);
			if($rsCreditLimit->num_rows)
			{
				while ($row = $rsCreditLimit->fetch_object()) 
				{
					$creditLimit =$row->ApprovedCL;
				}
			}
			
			$rsARBalance = $sp->spSelectARBalanceByCustomerID($database,$custId);
			if($rsARBalance->num_rows)
			{
				while($ARBalance = $rsARBalance->fetch_object())
				{
					$arOutstandingbalance 	=$ARBalance->OutstandingAmount;
					
				}
			}	
			
			$availableCL  = $creditLimit - $arOutstandingbalance;	
			
			if($availableCL < 0)
			{
				$availableCL=0.00;	
			}
			
			
			$rsMTDSI= $sp->spSelectMTDSI($database,$custId,$month_si,$year_si); 
			if($rsMTDSI->num_rows)
			{
				while ($row = $rsMTDSI->fetch_object()) 
				{
					//$mtdCustSelPrice=$row->TotalCFT +$row->TotalNCFT + $row->TotalCPI;
					$mtdCustSelPrice=$row->GrossAmount;
					$mtdBasicDisc = $row->BasicDiscount;
					$mtdAddlDisc = $row->AddtlDiscount;
					$mtdAddlDpp = $row->AddtlDiscountPrev;	
					$mtdiscGrSales =$mtdCustSelPrice-$mtdBasicDisc;
					
				}
			}
			
			$rsYTDSI= $sp->spSelectYTDSI($database,$custId,$year_si); 
			if($rsYTDSI->num_rows)
			{
				while ($row = $rsYTDSI->fetch_object()) 
				{
					//$ytdCustSelPrice=$row->TotalCFT +$row->TotalNCFT + $row->TotalCPI;
					$ytdCustSelPrice=$row->GrossAmount;
					$ytdBasicDisc = $row->BasicDiscount;
					$ytdAddlDisc = $row->AddtlDiscount;
					$ytdAddlDpp = $row->AddtlDiscountPrev;	
					//$ytdiscGrSales =$ytdCustSelPrice-$ytdBasicDisc;
			
				}
			}
			
			
			if ($statusId ==6 )
			{
				$v_mtdcft = $MTDCFT + $totalCFT ;
			} 
			else
			{
				$v_mtdcft = $qMTDCFT ; 
			}
			
			if($statusId ==6)
			{
				$v_mtdncft = $MTDNCFT +  $totalNCFT;
			}
			else
			{
				$v_mtdncft = $qMTDNCFT;
			}
			
		
			//FOR Printing #########################################################################
			
			
			
			$saleswithvat = $totgrossamt - $basicdiscount - $additionaldiscount;
			$vat12 =number_format($saleswithvat- $vatamt,2);
			$saleswithvat =  number_format($saleswithvat,2);
			$discGroupSales =  $totgrossamt - $basicdiscount;
			$discGroupSales  = number_format($discGroupSales ,2);  
			$totalLessCPI = number_format($totgrossamt - $totalCPI ,2);
			$basicdiscount =  number_format($basicdiscount ,2); 
			$additionaldiscount = number_format($additionaldiscount ,2);
		
			$prevadditionaldiscount = number_format($prevadditionaldiscount ,2); 
			$totgrossamt = number_format($totgrossamt ,2);
		
			$vatamt = number_format($vatamt,2);
			$totalnetamt = number_format($totalnetamt ,2);
			$creditLimit = number_format($creditLimit,2);
			$totalCPI = number_format($totalCPI,2);
			$totalCFT = number_format($totalCFT,2);
			$totalNCFT = number_format($totalNCFT,2);
			$v_mtdcft = number_format($v_mtdcft ,2);
			$v_mtdncft = number_format($v_mtdncft ,2);
			$availableCL = number_format($availableCL,2);
			$arOutstandingbalance = number_format($arOutstandingbalance,2);
			$mtdCustSelPrice = number_format($mtdCustSelPrice,2);
			$mtdBasicDisc = number_format($mtdBasicDisc,2);
			$mtdAddlDisc = number_format($mtdAddlDisc,2);
			$mtdAddlDpp = number_format($mtdAddlDpp,2);
			$mtdiscGrSales = number_format($mtdiscGrSales,2);
			$ytdiscGrSales =number_format($ytdCustSelPrice-$ytdBasicDisc,2);
			$ytdCustSelPrice=number_format($ytdCustSelPrice,2);
			$ytdBasicDisc = number_format($ytdBasicDisc,2);
			$ytdAddlDisc = number_format($ytdAddlDisc,2);
			$ytdAddlDpp = number_format($ytdAddlDpp,2);
			$space = ' ';
			$printctr = $printCnt + 1;
		
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
			//	if($printctr > 1)
			//	 {
			//		$con= " <table width='95%' align='center'>
			//				<tr>
			//					<td><strong>**Duplicate Copy -  $printctr copies printed.</strong></td>
			//				</tr>
			//				</table>";
			//     }	
			
			$header = "<table width='50%' border='0' align='left' cellpadding='0' cellspacing='2'>	
					<tr>
						<td align='left' height='20px' width='55%'></td>
						<td align='left' height='20px' width='15%'>&nbsp; </td>
						<td align='left' height='20px' width='30%' valign='top' >$salesinvoice</td>
					</tr>				
					<tr>
						<td align='left' height='20px' width='55%'>$branchname</td>
						<td align='left' height='20px' width='15%'>&nbsp; </td>
						<td align='left' height='20px' width='30%' valign='top' >$txnDateFormat</td>
					</tr>	
					<tr>
						<td align='left' height='20px' valign='middle'>VAT REG: $tinno </td>
						<td align='left' height='20px'>&nbsp; </td>
						<td align='left'  height='20px' valign='top' >$reftxnid </td>
					</tr>							
					<tr>
						<td align='left' height='20px' valign='middle'>Permit No. $permitno</td>
						<td align='left' height='20px'>&nbsp; </td>
						<td align='left' height='20px'  valign='top' >$serversn</td>
					</tr>
					<tr>
						<td align='left' height='20px'  valign='middle' rowspan='2'>$address</td>
						<td align='left' height='20px'>&nbsp; </td>
						<td align='left' height='20px' valign='top' >%PAGENO%</td>
					</tr>
					<tr>
						<td align='left' height='20px'>&nbsp; </td>
						<td align='left'  height='20px' valign='top' ></td>
					</tr>
				</table>
				<table width='60%' border='0' align='center' cellpadding='0' cellspacing='2'>
					<tr>
						<td align='left'  width='2%'>&nbsp;</td>
						<td width='45%' align='left' >$custcode&nbsp;</td>
						<td align='left' >&nbsp;</td>
					</tr>
					<tr>
						<td align='left'  >&nbsp;</td>
						<td align='left' >$custname&nbsp;</td>
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
						<td align='left' >$customeribmcode</td>
					</tr>
					</table>
					<br>
					<br>
					<br>";
		
			$footer = "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0' >
					<tr>
						<td width='5%' align='left' valign='middle' >Total</td>
						<td width='25%' align='left' valign='middle' >CSP Less (CPI)</td>
						<td width='1%' align='left' valign='middle' >&nbsp;</td>
						<td width='15%' align='right' valign='middle' >&nbsp;$totalCPI</td>
						<td width='15%' align='right' valign='middle'>$totalLessCPI</td>
					</tr>
					<tr>
						<td align='left' valign='middle' >Less :</td>
						<td align='left' valign='middle' >Basic Discount</td>
						<td align='left' valign='middle' >&nbsp;</td>
						<td align='left' valign='middle'>&nbsp;</td>
						<td align='right' valign='middle' >$basicdiscount</td>
					</tr>
					<tr >
						<td align='left' valign='middle'>&nbsp;</td>
						<td align='left' valign='middle' >Additional Discount</td>
						<td align='left' valign='middle' >&nbsp;</td>
						<td align='left' valign='middle' >&nbsp;</td>
						<td align='right' valign='middle' >$additionaldiscount</td>
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
						<td align='left' valign='middle'> </td>
						<td align='right' valign='middle' >$vatamount</td>
						<td align='right' valign='middle' >$vatamt</td>
					</tr>
					<tr >
						<td align='left' valign='middle' >&nbsp;</td>
						<td align='left' valign='middle' >Vatable Sales</td>
						<td align='left' valign='middle' >&nbsp;</td>
						<td align='right' valign='middle' ></td>
						<td align='right' valign='middle' >$vat12</td>
					</tr>  
						<tr >
						<td align='left' valign='middle'>Less :</td>
						<td align='left' valign='middle' >Additional Discount on Previous Purchase</td>
						<td align='left' valign='middle' >&nbsp;</td>
						<td align='right' valign='middle' >&nbsp;</td>
						<td align='right' valign='middle'>$prevadditionaldiscount</td>
					</tr>	     
					<tr >
						<td colspan='2' align='left' valign='middle'>Total Invoice Amount Due</td>        
						<td align='left' valign='middle' >&nbsp;</td>
						<td align='right' valign='middle' >&nbsp;</td>
						<td align='right' valign='middle'>$totalnetamt</td>
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
						<td align='right'>$v_mtdcft</td>
						<td width='5%'>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Total NCFT :</td>
						<td align='right'>$totalNCFT</td>
						<td>&nbsp;</td>
						<td>MTD NCFT :</td>
						<td align='right'>$v_mtdncft</td>
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
		
			$footer3="<table width='90%' align='left' cellpadding='0' cellspacing='0'   border='1'>
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
					<tr >	
						<td align='right' colspan='4' valign='top'>$space</td>
								
					</tr>
					<tr >	
						<td align='right' colspan='4' valign='top'>$space</td>
									
					</tr>
					<tr >	
						<td align='right' colspan='4' valign='top'>$space</td>					
					</tr>
			</table>";
			
			$footer4="	<table width='50%'  cellpadding='0' cellspacing='0' border='0 '>
				<tr>
				<td   width='90%' align='left' >
				*THANK YOU FOR PATRONIZING OUR
					PRODUCTS* DELAYED PAYMENTS SUBJECT
					TO PENALTY* AVAIL OF BIG PROMO OFFERS
					AND DISCOUNTS* <br>Released by: ______________________<br>Received the above items in good order and condition. 
					I also agree to the terms stated at theback of this invoice.
				________________________<br>
				Signature over Printed Name/Date
				</td>
				</tr>
				</table>
			$con";
		
			$footer2 ="<table>
				<tr>
					<td>LESS PURCHASE:</td>
					<td>&nbsp;</td>
					<td>Cust Sel Price</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>";
		
							
			$pdf=new PDF();
			$pdf->header = $header;
			$pdf->SetMargins(10,13,8);
			//$pdf->SetTopMargin(25);
			$pdf->AddPage();	
			//$pdf->SetFont('Arial','',9);	
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
						$totamt = number_format($row->TotalAmount, 2);
						$body .= "<tr >
										<td width='1%' align='left'  valign='top'></td>
										<td width='10%' align='left'  valign='top'>$row->ProductCode</td>
										<td width='35%' align='left'  valign='top' >$row->Product</td>
										<td width='10%' align='left'  valign='top' >$row->PMGName</td>
										<td width='13%' align='right' valign='top' >$row->Qty</td>
										<td width='13%' align='right' valign='top' >$row->UnitPrice</td>
										<td width='13%' align='right' valign='top' >$totamt</td>
									</tr>";
								$totalqty += $row->Qty;
								$grandtotal += $row->TotalAmount;
						$ctr++;
					}
					else 
					{
						$body .= "</table>";
						$pdf->WriteHTML($body);	
						$pdf->SetMargins(10,13,8);	
						$pdf->AddPage();	 			
						$body = "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
						$ctr=0;	
					}
				}
				if ($ctr>0){
					$grandtotal = number_format($grandtotal,2);
					$body .= "</table>
					<table width='90%' border='0' align='center' cellpadding='0' cellspacing='0'>		
					<tr >
						<td width='10%' align='left' valign='middle'>&nbsp;</td>
						<td width='35%' align='left'  valign='middle' >Total with CPI :</td>
						<td width='15%' align='left' valign='middle' >&nbsp;</td>
						<td width='13%' align='right' valign='middle'>$totalqty</td>
						<td width='13%' align='right' valign='middle'>&nbsp;</td>
						<td width='13%' align='right' valign='middle' > $grandtotal</td>
					</tr>			 
					</table>";
					
					$pdf->WriteHTML($body);	 
		
						$pdf->SetMargins(10,13,8);
					$pdf->AddPage();	 			
					$body = "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
					$ctr=0;	
				}
			
				$pdf->WriteHTML($footer);
				// $pdf->AddPage();	
				$pdf->WriteHTML($footer3);
				$pdf->SetFontSize(10);
				$pdf->WriteHTML($footer4);
				
			}
			else
			{
				$body = "<tr align='center'><td height='20' >No record(s) to display. </td></tr>";
				$pdf->WriteHTML($body);
				$pdf->SetMargins(10,13,8);
				$pdf->AddPage();
			}
				
				if (preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"])){
					header("Content-type: application/PDF");
				} else {
					header("Content-type: application/PDF");
					header("Content-Type: application/pdf");
				}
				$pdf->Output("SalesInvoice.pdf", "I");
		
?>