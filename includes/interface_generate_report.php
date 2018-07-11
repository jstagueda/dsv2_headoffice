<?php
ini_set('display_errors', '1');

require_once('../initialize.php');
include CS_PATH.DS."ClassUserInterface.php";
global $database;

function makeDir($path)
{
   return is_dir($path) || mkdir($path);
}

if(isset($_POST['date'])){

    $database->execute("truncate outputinterfacelogs");
    $file_folder = "../download";
    $user_name = "root";
    //execute chmod
    exec("chmod -R 777 ".SITE_ROOT.DS."download");

    $lastrun = date("Y-m-d", strtotime($_POST['date']));
    $date = date("m/d/Y", strtotime($_POST['date']	));
    $today   = date("mdy",strtotime($lastrun));
    $today2  = date("md",strtotime($lastrun));
	
	if($_POST['branch'] > 0){
		$rs_branch = $database->execute("SELECT * FROM branch WHERE ID = ".$_POST['branch']);
	}else{
		$rs_branch = $tpiInterface->spSelectBranch($database);
	}
	
    $ctr = 0;

			if($rs_branch->num_rows){

				while($row = $rs_branch->fetch_object()){
										
					$ctr += 1;
					/* EPA
					$epa = "epa".$ctr;
					$epa = $tpiInterface->spSalesTransactionsInterfaceFile($database, $row->ID, $lastrun);
					if($epa->num_rows){
						$filename = $today.".".strtoupper($row->Code).'epa';
						$fp_epa = fopen(SITE_ROOT.DS.'download'.DS.'epa'.DS.$filename, 'w');

					    $tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 1, $epa->num_rows);

						if ($fp_epa){
							while($row_epa = $epa->fetch_object()){
								fwrite($fp_epa, "'".$row_epa->BranchCode." ");
								fwrite($fp_epa, $row_epa->TxnDate." ");
								fwrite($fp_epa, "'".$row_epa->DocumentNo."' ");
								fwrite($fp_epa, "'".$row_epa->CustCode."' ");
								fwrite($fp_epa, "'".$row_epa->EmpCode."' ");
								fwrite($fp_epa, "'".$row_epa->EmpName."' ");
								fwrite($fp_epa, $row_epa->NetAmount." ");
								fwrite($fp_epa, $row_epa->BasicDisc." ");
								fwrite($fp_epa, $row_epa->AddtlDisc);
								fwrite($fp_epa, "\r\n");
							}
							$epa->close();
						}
					}
					*/
					/* COLLECTION */
					$dumpname = $today2."00".".".strtoupper($row->Code)."col";
					$col_header = "colheader".$ctr;
					$col_details = "coldetails".$ctr;

					$col_header = $tpiInterface->spCollectionHeaderInterfaceFile($database, $row->ID, $lastrun);
					$col_details = $tpiInterface->spCollectionTransactionsInterfaceFile($database, $row->ID, $lastrun);

					$totalAmountCol = 0;
					$runTotal = $tpiInterface->spCollectionTransactionsInterfaceFile($database, $row->ID, $lastrun);
					if($res = $runTotal->num_rows){
						while($res = $runTotal->fetch_object()){
							$totalAmountCol += $res->Amount;
						}
					}

					if($col_header->num_rows){
						
						makeDir(SITE_ROOT.DS.'download'.DS.'collection');
					
						$filename = $today2."00".".".strtoupper($row->Code).'col';
						$fp_col = fopen(SITE_ROOT.DS.'download'.DS.'collection'.DS.$filename, 'w');


						if ($fp_col){
							while($row_colh = $col_header->fetch_object()){
								/*Collection Header*/
								fwrite($fp_col, "\"".$row_colh->BranchCode."\" ");
								fwrite($fp_col, "\"".date("d/m/Y", strtotime($lastrun))."\" ");
								fwrite($fp_col, $row_colh->SentDate." ");
								fwrite($fp_col, "\"".$dumpname."\" ");
								fwrite($fp_col, $totalAmountCol." ");
								fwrite($fp_col, $col_details->num_rows);
								fwrite($fp_col, "\r\n");
							}
							$col_header->close();
						}
					}


					if($col_details->num_rows)
					{
						$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 2, $col_details->num_rows);
						$counter = 1;
						while($row_cold = $col_details->fetch_object())
						{
							/*Collection Data*/
							fwrite($fp_col, '"'.$row_cold->PaymentType.'" ');
							fwrite($fp_col, '"'.$row_cold->AccountCode.'" ');
							fwrite($fp_col, '"'.$row_cold->AccountNo.'" ');
							fwrite($fp_col, $row_cold->LogicalVal." ");
							fwrite($fp_col, $row_cold->Amount." ");
							fwrite($fp_col, "\"".$row_cold->TxnDate."\" ");
							fwrite($fp_col, '"'.$row_cold->Remarks.'" ');
							
							if($col_details->num_rows > $counter){
								fwrite($fp_col, "\r\n");
							}
							$counter++;
						}
						$col_details->close();
					}

					/* AR PAYMENT */
					
					$dumpname = $today2."00".".".strtoupper($row->Code)."are";
					$arp_header = "arpheader".$ctr;
					$arp_details = "arpdetails".$ctr;

					$arp_header = $tpiInterface->spARHeaderInterfaceFile($database, $row->ID, $lastrun);
					$aredetails = $tpiInterface->spARTransactionsInterfaceFile($database, $row->ID, $lastrun);

					if($arp_header->num_rows){
						/* AR PAYMENT HEADER*/
						makeDir(SITE_ROOT.DS.'download'.DS.'arpayment');
						
						$filename = $today2."00".".".strtoupper($row->Code).'are';
						$fp_arp = fopen(SITE_ROOT.DS.'download'.DS.'arpayment'.DS.$filename, 'w');

							if ($fp_arp){
								while($row_arph = $arp_header->fetch_object()){
									fwrite($fp_arp, '"'.$row_arph->BranchCode.'" ');
									fwrite($fp_arp, '"'.$row_arph->TxnDate.'" ');
									fwrite($fp_arp, $row_arph->LogicalValue." ");
									fwrite($fp_arp, '"'.$row_arph->Method.'" ');
									fwrite($fp_arp, '"'.$dumpname.'" ');
									fwrite($fp_arp, '"'.$row_arph->EPassword.'" ');
									fwrite($fp_arp, $aredetails->num_rows);
									fwrite($fp_arp, "\r\n");
								}
								$arp_header->close();
							}
					}

					$arp_details = $tpiInterface->spARTransactionsInterfaceFile($database, $row->ID, $lastrun);
					if($arp_details->num_rows){
                        $runningAmount = 0;
						/*AR PAYMENT DAYA*/
                        $tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 3, $arp_details->num_rows);
						while($row_arpd = $arp_details->fetch_object()){
							//if($row_arpd->Reason != ""){

								if($row_arpd->Amount == "" || $row_arpd->Amount == 0  || $row_arpd->Amount == NULL || strlen($row_arpd->Amount) == 0){
									if($row_arpd->xAmount == "" || $row_arpd->xAmount == 0  || $row_arpd->xAmount == NULL || strlen($row_arpd->xAmount) == 0){
										$Amount = 0;
										$runningAmount += 0;
									}else{
										$Amount = $row_arpd->xAmount;
										$runningAmount += $row_arpd->xAmount;
									}
								}else{
									$Amount = $row_arpd->Amount;
									$runningAmount += $row_arpd->Amount;
								}
								
								
								fwrite($fp_arp, '"'.$row_arpd->TxnDate.'" ');
								fwrite($fp_arp, '"'.$row_arpd->PaymentType.'" ');
								fwrite($fp_arp, '"'.$row_arpd->Classification.'" ');
								fwrite($fp_arp, '"'.$row_arpd->Reason.'" ');
								fwrite($fp_arp, $Amount." ");
								fwrite($fp_arp, '"'.$row_arpd->DebitCode.'" ');
								fwrite($fp_arp, '"'.$row_arpd->DebitCCenter.'" ');
								fwrite($fp_arp, '"'.$row_arpd->CreditCode.'" ');
								fwrite($fp_arp, '"'.$row_arpd->CreditCCenter.'" ');
								fwrite($fp_arp, ''.$runningAmount.' ');
								fwrite($fp_arp, "\r\n");

							//}
						}
						$arp_details->close();
					}

					/*DMCM */
					$dumpname = $today2."00".".".strtoupper($row->Code)."dcm";
					$dcm_header = "dcmheader".$ctr;
					$dcm_details = "dcmdetails".$ctr;

					$dcm_header = $tpiInterface->spDMCMHeaderInterfaceFile($database, $row->ID, $lastrun);
                                        $dcm_details = $tpiInterface->spDMCMTransactionsInterfaceFile($database, $row->ID, $lastrun);

					if($dcm_header->num_rows){
						
						makeDir(SITE_ROOT.DS.'download'.DS.'dmcm');
					
						$filename = $today2."00".".".strtoupper($row->Code).'dcm';
						//$fp_dcm = fopen(AUTO_UPLOAD.'/download/dmcm/'.$filename, 'w');
						$fp_dcm = fopen(SITE_ROOT.DS.'download'.DS.'dmcm'.DS.$filename, 'w');

						if ($fp_dcm){
							while($row_dcmh = $dcm_header->fetch_object()){
								fwrite($fp_dcm, '"'.$row_dcmh->BranchCode.'" ');
								fwrite($fp_dcm, '"'.$row_dcmh->TxnDate.'" ');
								fwrite($fp_dcm, $row_dcmh->LogicalValue.' ');
								fwrite($fp_dcm, '"'.$row_dcmh->Method.'" ');
								fwrite($fp_dcm, '"'.$dumpname.'" ');
								fwrite($fp_dcm, '"'.$row_dcmh->EPassword.'" ');
								fwrite($fp_dcm, $dcm_details->num_rows);
								fwrite($fp_dcm, "\r\n");
							}
							$dcm_header->close();
						}
					}

					//die('xx');
					//echo $dcm_details->num_rows;
					if($dcm_details->num_rows){

						$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 4, $dcm_details->num_rows);
						while($row_dcmd = $dcm_details->fetch_object()){
							if(strlen($row_dcmd->CustCode) > 10){
								$cuscode = substr($row_dcmd->CustCode, 0, 10);
							}else{
								$cuscode = $row_dcmd->CustCode;
							}
							fwrite($fp_dcm, "\"".$row_dcmd->MemoType."\" ");
							fwrite($fp_dcm, "\"".$row_dcmd->ReasonName."\" ");
							fwrite($fp_dcm, $row_dcmd->TotalAmount." ");
							fwrite($fp_dcm, "\"".$row_dcmd->AccountCode."\" ");
							fwrite($fp_dcm, "\"".$row_dcmd->CostCenter."\" ");
							fwrite($fp_dcm, "\"".$cuscode."\" ");
							fwrite($fp_dcm, "\"".$row_dcmd->CustName."\" ");
							fwrite($fp_dcm, "\"".$row_dcmd->Campaign."\"");
							fwrite($fp_dcm, "\r\n");
						}
						$dcm_details->close();
					}



					/* BSD  BRANCHES SALES DETAILS*/
					$dumpname = $today2."00".".".strtoupper($row->Code)."bsd";
					$bsdh = "bsdh".$ctr;
					$bsdd = "bsdd".$ctr;

					$totalQTY = 0.00;
					$totalCampaignPrice = 0.00;
					$totalBasicDiscount = 0.00;
					$totalNetAmount = 0.00;
					$totalConsumerPrice = 0.00;
					$totalVatAmount = 0.00;
					$additionalDiscountPrev = 0.00;
					$additionalDiscount = 0.00;

					$bsdh = $tpiInterface->spSalesInvoiceHeaderInterfaceFile($database, $row->ID, $lastrun);
					$bsdd = $tpiInterface->spSalesInvoiceDetailsInterfaceFile($database, $row->ID, $lastrun);

					if($bsdh->num_rows){
						
						makeDir(SITE_ROOT.DS.'download'.DS.'bsd');
					
						$filename = $today2."00".".".strtoupper($row->Code).'bsd';
						$fp_bsd = fopen(SITE_ROOT.DS.'download'.DS.'bsd'.DS.$filename, 'w');
						if ($fp_bsd){
							while($row_bsdh = $bsdh->fetch_object()){
								fwrite($fp_bsd, '"'."dtdsin_s".'"'." ");
								fwrite($fp_bsd, '"'.$dumpname.'"'." ");
								fwrite($fp_bsd, '"'."bsd".'"'." ");
								fwrite($fp_bsd, $bsdd->num_rows." ");
								fwrite($fp_bsd, number_format($row_bsdh->TotNetAmount, 2, '.', '')." ");
								fwrite($fp_bsd, '"'."ldPccldkqivkohdh".'"'." ");
								fwrite($fp_bsd, '"'."UNIX CP".'"'." ");
								/*fwrite($fp_bsd, date("d/m/Y", strtotime($date))." ");*/
                                fwrite($fp_bsd, date("d/m/Y")." ");
								fwrite($fp_bsd, date("His",time())." ");
								fwrite($fp_bsd, '"'.$_SESSION['emp_id'].'"'." ");
								fwrite($fp_bsd, '"'."SENT".'"'." ");
								fwrite($fp_bsd, '"'.date("d/m/Y", strtotime($date)).'"');
								fwrite($fp_bsd, "\r\n");
							}
							$bsdh->close();
						}
					}

					if($bsdd->num_rows){
						$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 6, $bsdd->num_rows);
						while($row_bsdd = $bsdd->fetch_object()){
								fwrite($fp_bsd, "\"".$row_bsdd->BranchCode."\" ");
							   // fwrite($fp_bsd, '"'.$row_bsdd->TxnDate.'" ');
								fwrite($fp_bsd, '"" ');
								fwrite($fp_bsd, $row_bsdd->Qty." ");
								fwrite($fp_bsd, "\"".$row_bsdd->ProductCode."\" ");

								//invoice
								fwrite($fp_bsd, number_format($row_bsdd->CampaignPrice, 2, '.', '')." "); 	
								fwrite($fp_bsd, number_format($row_bsdd->BasicDisc, 2, '.', '')." "); 		
								// fwrite($fp_bsd, number_format($row_bsdd->NetAmount, 2, '.', '')." ");
								fwrite($fp_bsd, number_format($row_bsdd->AddtlDisc, 2, '.', '')." ");		
								fwrite($fp_bsd, number_format($row_bsdd->ConsumerPrice, 2, '.', '')." ");
								fwrite($fp_bsd, number_format($row_bsdd->VatAmount, 0, '', '')." ");
								fwrite($fp_bsd, $row_bsdd->Tax." ");
								// fwrite($fp_bsd, number_format($row_bsdd->AddtlDisc, 2, '.', '')." ");
								fwrite($fp_bsd, number_format($row_bsdd->AddtlDiscPrev, 2, '.', '')." ");
								fwrite($fp_bsd, "\r\n");

								$totalQTY 				+= $row_bsdd->Qty;
								$totalCampaignPrice 	+= number_format($row_bsdd->CampaignPrice, 2,".","");
								$totalBasicDiscount 	+= number_format($row_bsdd->BasicDisc, 2, ".","");
								$totalNetAmount 		+= number_format($row_bsdd->NetAmount, 2, ".","");
								$totalConsumerPrice 	+= number_format($row_bsdd->ConsumerPrice, 2, ".","");
								$totalVatAmount 		+= $row_bsdd->VatAmount;
								$additionalDiscountPrev += number_format($row_bsdd->AddtlDiscPrev, 2, ".","");
								$additionalDiscount 	+= number_format($row_bsdd->AddtlDisc, 2, ".","");
						}
						$bsdd->close();
						fclose($fp_bsd);
					}


                                        /* BSH BRANCH SALES HEADER*/
					$dealerdisc = 0;
					$addtldisc = 0;
					$vatamount = 0;
					$adpp = 0;

					$dumpname = $today2."00".".".strtoupper($row->Code)."bsh";
					$bsh = "bsh".$ctr;

					$bsh = $tpiInterface->spSalesInvoiceHeaderInterfaceFile($database, $row->ID, $lastrun);
                    $bsh2 = $tpiInterface->spSalesInvoiceBSHDetailsInterfaceFile($database, $row->ID, $lastrun);

					if($bsh->num_rows){
					
						makeDir(SITE_ROOT.DS.'download'.DS.'bsh');
					
						$filename = $today2."00".".".strtoupper($row->Code).'bsh';
						//$fp_bsh = fopen(AUTO_UPLOAD.'/download/bsh/'.$filename, 'w');
						$fp_bsh = fopen(SITE_ROOT.DS.'download'.DS.'bsh'.DS.$filename, 'w');
						if ($fp_bsh){
							while($row_bsh = $bsh->fetch_object()){
								$dealerdisc = number_format($row_bsh->DealerDisc, 2, '.', '');
								$addtldisc = number_format($row_bsh->AddtlDisc, 2, '.', '');  
								$vatamount = number_format($row_bsh->VatAmount, 0, '', '');
								$adpp = number_format($row_bsh->ADPP, 2, '.', '');

								fwrite($fp_bsh, '"'."dtdsin_s".'"'." ");
								fwrite($fp_bsh, '"'.$dumpname.'"'." ");
								fwrite($fp_bsh, '"'."bsh".'"'." ");
								fwrite($fp_bsh, $bsh2->num_rows." ");
								fwrite($fp_bsh, number_format($row_bsh->TotNetAmount, 2, '.', '')." ");
								fwrite($fp_bsh, '"'."ldPccldkqivkohdh".'"'." ");
								fwrite($fp_bsh, '"'."UNIX CP".'"'." ");
								/*fwrite($fp_bsh, date("d/m/Y", strtotime($date))." ");*/
                                fwrite($fp_bsh, date("d/m/Y")." ");
								fwrite($fp_bsh, date("His",time())." ");
								fwrite($fp_bsh, '"'.$_SESSION['emp_id'].'"'." ");
								fwrite($fp_bsh, '"'."SENT".'"'." ");
								fwrite($fp_bsh, '"'.date("d/m/Y", strtotime($date)).'" ');
								fwrite($fp_bsh, number_format($row_bsh->ADPP, 2, '.', '')." ");
								fwrite($fp_bsh, "\r\n");
							}
							$bsh->close();
						}
					}

					if($bsh2->num_rows){
							$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 5, $bsh2->num_rows);
						while($row_bsh2 = $bsh2->fetch_object()){

							/*fwrite($fp_bsh, '"'.strtoupper($row->Code).'"'." ");
							//fwrite($fp_bsh, '"'.$date.'"'." ");
                                                        fwrite($fp_bsh, '"" ');
							// number_format($row_bsdd->CampaignPrice, 2, '.', '')." ")
							fwrite($fp_bsh,  number_format($row_bsh2->TotQty, 2, '.', '')." ");
							//fwrite($fp_bsh, number_format($row_bsh2->TotCSP, 2, '.', '')." ");
                                                        fwrite($fp_bsh, number_format($row_bsh2->CampaignPrice, 2, '.', '')." ");
							fwrite($fp_bsh, $dealerdisc." ");
							fwrite($fp_bsh, $addtldisc." ");
							fwrite($fp_bsh, number_format($row_bsh2->TotConPrice, 2, '.', '')." "); //
							fwrite($fp_bsh, '"no" ');
							fwrite($fp_bsh, $vatamount." ");
							fwrite($fp_bsh, number_format($row_bsh2->TotADPP, 2, '.', '')." ");
							fwrite($fp_bsh, "\r\n");*/
							//fwrite($fp_bsh, '"'.$date.'"'." ");
							// number_format($row_bsdd->CampaignPrice, 2, '.', '')." ")
							//fwrite($fp_bsh, number_format($row_bsh2->TotCSP, 2, '.', '')." ");

							fwrite($fp_bsh, '"'.strtoupper($row->Code).'"'." ");
							fwrite($fp_bsh, '"" ');
							fwrite($fp_bsh,  number_format($totalQTY, 2, '.', '')." ");
							fwrite($fp_bsh, $totalCampaignPrice." ");
							fwrite($fp_bsh, $totalBasicDiscount." ");
							fwrite($fp_bsh, $additionalDiscount." ");
							//fwrite($fp_bsh, $totalNetAmount." ");
							fwrite($fp_bsh, $totalConsumerPrice." "); //
							fwrite($fp_bsh, '"no" ');
							fwrite($fp_bsh, number_format($totalVatAmount, 0, '', '')." ");
							fwrite($fp_bsh, $additionalDiscountPrev." ");
							fwrite($fp_bsh, "\r\n");
						}
						$bsh2->close();
					}

					/* RHO */
					$dumpname = $today2."00".".".strtoupper($row->Code)."rho";
					$rho = "rho".$ctr;

					$rho  = $tpiInterface->spRHOInterfaceFile($database, $row->ID, $lastrun);
					$rho2 = $tpiInterface->spRHOInterfaceFile($database, $row->ID, $lastrun);
					if($rho->num_rows){
					
						makeDir(SITE_ROOT.DS.'download'.DS.'rho');
					
						$filename = $today2."00".".".strtoupper($row->Code).'rho';
						//$fp_rho = fopen(AUTO_UPLOAD.'/download/rho/'.$filename, 'w');
						$fp_rho = fopen(SITE_ROOT.DS.'download'.DS.'rho'.DS.$filename, 'w');
						if ($fp_rho)
						{
							fwrite($fp_rho, '"'.$row->Code.'"'." ");
							fwrite($fp_rho, date("d/m/Y", strtotime($date))." ");
							fwrite($fp_rho, "\r\n");
							$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 7, $rho2->num_rows);
							while($row_rho = $rho2->fetch_object())
							{
								fwrite($fp_rho, "\"".$row_rho->ReceiptNo."\" ");
								fwrite($fp_rho, "\"".$row_rho->ShipmentNo."\" ");
								fwrite($fp_rho, "\"".$row_rho->DocumentNo."\" ");
								fwrite($fp_rho, "\"".$row_rho->ProdCode."\" ");
								fwrite($fp_rho, $row_rho->LoadedQty." ");
								fwrite($fp_rho, $row_rho->ConfirmedQty);
								fwrite($fp_rho, "\r\n");
							}
							$rho->close();
						}
					fclose($fp_rho);
					}

					/* ZQT ZERO QUANTITY INVENTORY*/
					$dumpname = $today2."00".".".strtoupper($row->Code)."zqt";
					$zqt = "zqt".$ctr;

					$zqt  = $tpiInterface->spZQTInterfaceFile($database, $row->ID, $lastrun);
					$zqt2 = $tpiInterface->spZQTInterfaceFile($database, $row->ID, $lastrun);
					if($zqt->num_rows){
					
						makeDir(SITE_ROOT.DS.'download'.DS.'zqt');
					
						$filename = $today2."00".".".strtoupper($row->Code).'zqt';
						$fp_zqt = fopen(SITE_ROOT.DS.'download'.DS.'zqt'.DS.$filename, 'w');
						if ($fp_zqt){
							fwrite($fp_zqt, '"'.$row->Code.'"'." ");
							fwrite($fp_zqt, date("d/m/Y", strtotime($date))." ");
							fwrite($fp_zqt, 'yes'." ");
							fwrite($fp_zqt, '"'.'UNIX CP'.'"'." ");
							fwrite($fp_zqt, '"'.$dumpname.'"'." ");
							fwrite($fp_zqt, '"'.'inhnAivHNllncklb'.'"'." ");
							fwrite($fp_zqt, $zqt->num_rows);
							fwrite($fp_zqt, "\r\n");
								$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 8, $zqt2->num_rows);
							while($row_zqt = $zqt2->fetch_object()){
								fwrite($fp_zqt, '"'.$row->Code.'"'." ");
								fwrite($fp_zqt, date("d/m/Y", strtotime($row_zqt->TxnDate))." ");
								fwrite($fp_zqt, $row_zqt->ProdCode." ");
								fwrite($fp_zqt, $row_zqt->SOH);
								fwrite($fp_zqt, "\r\n");
							}
							$zqt->close();
						}
					}

					/* INV */
					$dumpname = $today2."00".".".strtoupper($row->Code)."inv";
					$inv = "inv".$ctr;

					$tid = 0;
					$run_total = 0;
					$inv = $tpiInterface->spInventoryTransactionsInterfaceFile($database, $row->ID, $lastrun);
					if($inv->num_rows){
						
						makeDir(SITE_ROOT.DS.'download'.DS.'inv');
					
						$filename = $today2."00".".".strtoupper($row->Code).'inv';
						$fp_inv = fopen(SITE_ROOT.DS.'download'.DS.'inv'.DS.$filename, 'w');
						if ($fp_inv){
							fwrite($fp_inv, '"'.$row->Code.'"'." ");
							fwrite($fp_inv, '"'.date("d/m/Y", strtotime($_POST['date'])).'"'." ");
							fwrite($fp_inv, '"'.date("d/m/Y").'"'." ");
							fwrite($fp_inv, 'yes'." ");
							fwrite($fp_inv, '"'.'UNIX CP'.'"'." ");
							fwrite($fp_inv, '"'.$dumpname.'"'." ");
							fwrite($fp_inv, '"'.'ldPccldkqivkohdh'.'"'." ");
							fwrite($fp_inv, $inv->num_rows);
							fwrite($fp_inv, "\r\n");
							$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 9, $inv->num_rows);
							while($row_inv = $inv->fetch_object()){
								fwrite($fp_inv, '"'.$row_inv->MovementOrder.'" ');
								fwrite($fp_inv, '"'.$row_inv->TxnDate.'" ');
								fwrite($fp_inv, '"'.$row_inv->TxnType.'" ');
								fwrite($fp_inv, '"'.$row_inv->SourceLocation.'" ');
								fwrite($fp_inv, '"'.$row_inv->ReceivingLocation.'" ');
								fwrite($fp_inv, '"'.$row_inv->ItemCode.'" ');
								fwrite($fp_inv, $row_inv->Qty." ");
								fwrite($fp_inv, '"'.$row_inv->UOM.'" ');
								fwrite($fp_inv, '"'.$row_inv->DebitAccount.'" ');
								fwrite($fp_inv, '"'.$row_inv->DebitCCenter.'" ');
								fwrite($fp_inv, '"'.$row_inv->CreditAccount.'" ');
								fwrite($fp_inv, '"'.$row_inv->CreditCCenter.'" ');

                                                                $run_total += $row_inv->Total;
								$tid = $row_inv->TxnID;

								fwrite($fp_inv, $run_total." ");
								fwrite($fp_inv, '"'.$row_inv->MovementCode.'" ');
								fwrite($fp_inv, '"'.$row_inv->ReferenceNo.'" ');
								fwrite($fp_inv, '"'.$row_inv->IBTAcct.'" ');
								fwrite($fp_inv, '"'.$row_inv->IBTCCenter.'" ');
								fwrite($fp_inv, '"'.$row_inv->IBTProj.'"');
								fwrite($fp_inv, "\r\n");
							}
							$inv->close();
						}
					}

					/* VDS */
					$dumpname = $today2."00".".".strtoupper($row->Code)."vds";
					$vds = "vds".$ctr;

					$total = 0;
					$vds_total = $tpiInterface->spVDSInterfaceFile($database, $row->ID, $lastrun);
					if($vds_total->num_rows){
						while($row_vds_tot = $vds_total->fetch_object())
						{
							$total += $row_vds_tot->Amount;
						}
						$vds_total->close();
					}

					$vds = $tpiInterface->spVDSInterfaceFile($database, $row->ID, $lastrun);
					if($vds->num_rows){
					
						makeDir(SITE_ROOT.DS.'download'.DS.'vds');
					
						$filename = $today2."00".".".strtoupper($row->Code).'vds';
						$fp_vds = fopen(SITE_ROOT.DS.'download'.DS.'vds'.DS.$filename, 'w');
						if ($fp_vds){
							fwrite($fp_vds, '"'.$row->Code.'"'." ");
							fwrite($fp_vds, '"'.date("d/m/Y", strtotime($date)).'"'." ");
							fwrite($fp_vds, '"'.date("d/m/Y", strtotime($lastrun)).'"'." ");
							fwrite($fp_vds, '"'.$dumpname.'"'." ");
							fwrite($fp_vds, $total." ");
							fwrite($fp_vds, $vds->num_rows);
							fwrite($fp_vds, "\r\n");
								$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 10, $vds->num_rows);
							while($row_vds = $vds->fetch_object()){
								fwrite($fp_vds, "\"".$row_vds->PaymentType."\" ");
								fwrite($fp_vds, $row_vds->Amount." ");
								fwrite($fp_vds, $row_vds->TxnDate." ");
								fwrite($fp_vds, $row_vds->DepositDate." ");
								fwrite($fp_vds, "\"".$row_vds->DepositSlipNo."\" ");
								fwrite($fp_vds, "\"".$row_vds->Bank."\"");
								fwrite($fp_vds, "\r\n");
							}
							$vds->close();
						}
					}

					/* RFD */
					$rfd = $tpiInterface->spRFDInterfaceFile($database, $row->ID, $lastrun);
					if($rfd->num_rows){
						
						makeDir(SITE_ROOT.DS.'download'.DS.'rfd');
					
						$filename = $today2."00".".".strtoupper($row->Code).'rfd';
						$fp_rfd = fopen(SITE_ROOT.DS.'download'.DS.'rfd'.DS.$filename, 'w');
						if ($fp_rfd){
								$tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 11, $rfd->num_rows);
							while($row_rfd = $rfd->fetch_object()){
								fwrite($fp_rfd, date("d/m/Y", strtotime($row_rfd->TxnDate))." ");
								fwrite($fp_rfd, "\"".$row_rfd->BranchCode."\" ");
								fwrite($fp_rfd, "\"".$row_rfd->ProductCode."\" ");
								fwrite($fp_rfd, "\"".$row_rfd->ProductName."\" ");
								fwrite($fp_rfd, $row_rfd->CreatedQty." ");
								fwrite($fp_rfd, "\r\n");
							}
							$rfd->close();
						}
					}
					/* LOS */
					//$los = "los".$ctr;
					$los = $tpiInterface->spLOSInterfaceFile($database, $row->ID, $lastrun);
					if($los->num_rows){
						
						makeDir(SITE_ROOT.DS.'download'.DS.'los');
					
						$filename = $today2."00".".".strtoupper($row->Code).'los';
						$fp_los = fopen(SITE_ROOT.DS.'download'.DS.'los'.DS.$filename, 'w');
						if ($fp_los){
							fwrite($fp_los, '"'.$row->Code.'"'." ");
							fwrite($fp_los, 'yes'." ");
							fwrite($fp_los, '"'.'UNIX CP'.'"'." ");
							fwrite($fp_los, '"'.$filename.'"'." ");
							fwrite($fp_los, '"'.'ldPccldkqivkohdh'.'"'." ");
							fwrite($fp_los, "\r\n");
                                                        $tpiInterface->spInsertOutputInterfaceLogs($database, $row->ID, $lastrun, $filename, 12, $los->num_rows);
							while($row_los = $los->fetch_object()){
								fwrite($fp_los, "\"".$row_los->BranchCode."\" ");
								fwrite($fp_los, "\"".$row_los->Location."\" ");
								fwrite($fp_los, "\"".$row_los->ProductCode."\" ");
								fwrite($fp_los, $row_los->QtyCounted." ");
								fwrite($fp_los, $row_los->TotSOH." ");
								fwrite($fp_los, $row_los->PrevQtyCounted." ");
								fwrite($fp_los, "\r\n");
							}
							$los->close();
						}
					}
				
				}
				$rs_branch->close();

		$logsresult = $database->execute("select a.*, b.Description, c.Name BranchName from outputinterfacelogs a
								  INNER JOIN outputfiletype b on a.FileTypeID = b.ID
								  INNER JOIN branch c on c.ID = a.BranchID where a.TransactionDate = '".$lastrun."'");
								  //TransactionDate = '".$lastrun."'"
		if($logsresult->num_rows){
			while($row = $logsresult->fetch_object()){
                                $branchname 	 = $row->BranchName;
                                $transactiondate = date("m/d/Y",strtotime($row->TransactionDate));
                                $dategenerated 	 = date("m/d/Y",strtotime($row->DateGenerated));
                                $filename 		 = $row->FileName;
                                $description 	 = $row->Description;
                                $totlnorecords   = $row->TotalNoRecords;
				$results[] = array('gino' => 'successful',
						   'branchname' => $branchname,
				                   'transactiondate' => $transactiondate,
				                   'dategenerated' => $dategenerated ,
				                   'filename' => $filename,
				                   'description' => $description,
				                   'totlnorecords' => $totlnorecords);
			}
		die(json_encode($results));
		}else{
                    $results[] = array('gino'=>'failed', 'msg'=>'No record(s) generated.');
                    die(json_encode($results));
		}
		
		}else{
                    $results[] = array('gino'=>'failed', 'msg' => '0 branches. please check branch table.');
                    die(json_encode($results));
		}
}

if(isset($_POST['getBranch'])){
	
	$branchquery = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND
										(Name LIKE '".$_POST['branchName']."%' OR Code LIKE '".$_POST['branchName']."%')");
	
	if($branchquery->num_rows){
		while($res = $branchquery->fetch_object()){
			$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
								"Value" => trim($res->Code).' - '.$res->Name,
								"ID" => $res->ID);
		}		
	}else{
		$result[] = array("Label" => "No result found",
							"Value" => "",
							"ID" => 0);
	}
	
	die(json_encode($result));
	
}
?>