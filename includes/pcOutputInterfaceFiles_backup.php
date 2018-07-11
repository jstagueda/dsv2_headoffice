<?php
	require_once("../initialize.php");
	global $database;
	ini_set('display_errors', 0);
	ini_set('max_execution_time', 300);
	
	/* parameters */
	$date = time();
	$today = date("mdy",$date); 
	$today2 = date("md",$date); 

	$rs_branch = $sp->spSelectBranch($database, -1, '');
	$ctr = 0;
	if($rs_branch->num_rows)
	{
		while($row = $rs_branch->fetch_object())
		{
			$ctr += 1;
			
			/* EPA */
			$epa = "epa".$ctr;
			$epa = $sp->spSalesTransactionsInterfaceFile($database, $row->ID);
			if($epa->num_rows)
			{
				$filename = $today.".".strtoupper($row->Code).'epa';
				$fp_epa = fopen(AUTO_UPLOAD.'/download/epa/'.$filename, 'w');
				while($row_epa = $epa->fetch_object())
				{
					fwrite($fp_epa, $row_epa->BranchCode." ");
					fwrite($fp_epa, $row_epa->TxnDate." ");
					fwrite($fp_epa, $row_epa->DocumentNo." ");
					fwrite($fp_epa, $row_epa->CustCode." ");
					fwrite($fp_epa, $row_epa->EmpCode." ");
					fwrite($fp_epa, $row_epa->EmpName." ");
					fwrite($fp_epa, $row_epa->NetAmount." ");
					fwrite($fp_epa, $row_epa->BasicDisc." ");
					fwrite($fp_epa, $row_epa->AddtlDisc);
					fwrite($fp_epa, "\r\n");
				}
				$epa->close();
				fclose($fp_epa);
			}
			
			/* COLLECTION */
			$dumpname = $today2."00".".".strtoupper($row->Code)."col";
			$col_header = "colheader".$ctr;
			$col_details = "coldetails".$ctr;
			
			$col_header = $sp->spCollectionHeaderInterfaceFile($database, $row->ID);
			if($col_header->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'col';
				$fp_col = fopen(AUTO_UPLOAD.'/download/collection/'.$filename, 'w');
				while($row_colh = $col_header->fetch_object())
				{
					fwrite($fp_col, $row_colh->BranchCode." ");
					fwrite($fp_col, $row_colh->TxnDate." ");
					fwrite($fp_col, $row_colh->SentDate." ");
					fwrite($fp_col, '"'.$dumpname.'"'." ");
					fwrite($fp_col, $row_colh->TotCollection." ");
					fwrite($fp_col, $row_colh->NoOfRecords);
					fwrite($fp_col, "\r\n");
				}
				$col_header->close();
			}
			
			$col_details = $sp->spCollectionTransactionsInterfaceFile($database, $row->ID);
			if($col_details->num_rows)
			{
				while($row_cold = $col_details->fetch_object())
				{
					fwrite($fp_col, $row_cold->PaymentType." ");
					fwrite($fp_col, $row_cold->AccountCode." ");
					fwrite($fp_col, $row_cold->AccountNo." ");
					fwrite($fp_col, $row_cold->LogicalVal." ");
					fwrite($fp_col, $row_cold->Amount." ");
					fwrite($fp_col, $row_cold->TxnDate." ");
					fwrite($fp_col, $row_cold->Remarks);
					fwrite($fp_col, "\r\n");
				}
				$col_details->close();
			}
			fclose($fp_col);
			
			/* AR PAYMENT */
			$dumpname = $today2."00".".".strtoupper($row->Code)."are";
			$arp_header = "arpheader".$ctr;
			$arp_details = "arpdetails".$ctr;
			
			$arp_header = $sp->spARHeaderInterfaceFile($database, $row->ID);
			if($arp_header->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'are';
				$fp_arp = fopen(AUTO_UPLOAD.'/download/arpayment/'.$filename, 'w');
				while($row_arph = $arp_header->fetch_object())
				{
					fwrite($fp_arp, $row_arph->BranchCode." ");
					fwrite($fp_arp, $row_arph->TxnDate." ");
					fwrite($fp_arp, $row_arph->LogicalValue." ");
					fwrite($fp_arp, $row_arph->Method." ");
					fwrite($fp_arp, '"'.$dumpname.'"'." ");
					fwrite($fp_arp, $row_arph->EPassword." ");
					fwrite($fp_arp, $row_arph->NoOfRecords);
					fwrite($fp_arp, "\r\n");
				}
				$arp_header->close();
			}
			
			$arp_details = $sp->spARTransactionsInterfaceFile($database, $row->ID);
			if($arp_details->num_rows)
			{
				while($row_arpd = $arp_details->fetch_object())
				{
					fwrite($fp_arp, $row_arpd->TxnDate." ");
					fwrite($fp_arp, $row_arpd->PaymentType." ");
					fwrite($fp_arp, $row_arpd->Classification." ");
					fwrite($fp_arp, $row_arpd->Reason." ");
					fwrite($fp_arp, $row_arpd->Amount." ");
					fwrite($fp_arp, $row_arpd->DebitCode." ");
					fwrite($fp_arp, $row_arpd->DebitCCenter." ");
					fwrite($fp_arp, $row_arpd->CreditCode." ");
					fwrite($fp_arp, $row_arpd->CreditCCenter);
					fwrite($fp_arp, "\r\n");
				}
				$arp_details->close();
			}
			fclose($fp_arp);
			
			/*DMCM */
			$dumpname = $today2."00".".".strtoupper($row->Code)."dcm";
			$dcm_header = "dcmheader".$ctr;
			$dcm_details = "dcmdetails".$ctr;
			
			$dcm_header = $sp->spDMCMHeaderInterfaceFile($database, $row->ID);
			if($dcm_header->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'dcm';
				$fp_dcm = fopen(AUTO_UPLOAD.'/download/dmcm/'.$filename, 'w');
				while($row_dcmh = $dcm_header->fetch_object())
				{
					fwrite($fp_dcm, $row_dcmh->BranchCode." ");
					fwrite($fp_dcm, $row_dcmh->TxnDate." ");
					fwrite($fp_dcm, $row_dcmh->LogicalValue." ");
					fwrite($fp_dcm, $row_dcmh->Method." ");
					fwrite($fp_dcm, '"'.$dumpname.'"'." ");
					fwrite($fp_dcm, $row_dcmh->EPassword." ");
					fwrite($fp_dcm, $row_dcmh->NoOfRecords);
					fwrite($fp_dcm, "\r\n");
				}
				$dcm_header->close();
			}
			
			$dcm_details = $sp->spDMCMTransactionsInterfaceFile($database, $row->ID);
			if($dcm_details->num_rows)
			{
				while($row_dcmd = $dcm_details->fetch_object())
				{
					fwrite($fp_dcm, $row_dcmd->MemoType." ");
					fwrite($fp_dcm, $row_dcmd->ReasonName." ");
					fwrite($fp_dcm, $row_dcmd->TotalAmount." ");
					fwrite($fp_dcm, $row_dcmd->AccountCode." ");
					fwrite($fp_dcm, $row_dcmd->CostCenter." ");
					fwrite($fp_dcm, $row_dcmd->CustCode." ");
					fwrite($fp_dcm, $row_dcmd->CustName." ");
					fwrite($fp_dcm, $row_dcmd->Campaign);
					fwrite($fp_dcm, "\r\n");
				}
				$dcm_details->close();
			}
			fclose($fp_dcm);
			
			/* BSH */
			$dealerdisc = 0;
			$addtldisc = 0;
			$vatamount = 0;
			$adpp = 0;
			
			$dumpname = $today2."00".".".strtoupper($row->Code)."bsh";
			$bsh = "bsh".$ctr;
			
			$bsh = $sp->spSalesInvoiceHeaderInterfaceFile($database, $row->ID);
			if($bsh->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'bsh';
				$fp_bsh = fopen(AUTO_UPLOAD.'/download/bsh/'.$filename, 'w');
				while($row_bsh = $bsh->fetch_object())
				{
					$dealerdisc = $row_bsh->DealerDisc;
					$addtldisc = $row_bsh->AddtlDisc;
					$vatamount = $row_bsh->VatAmount;
					$adpp = $row_bsh->ADPP;
					
					fwrite($fp_bsh, '"'."dtdsin_s".'"'." ");
					fwrite($fp_bsh, '"'.$dumpname.'"'." ");
					fwrite($fp_bsh, '"'."bsh".'"'." ");
					fwrite($fp_bsh, $row_bsh->NoOfRecords." ");
					fwrite($fp_bsh, $row_bsh->TotNetAmount." ");
					fwrite($fp_bsh, '"'."ldPccldkqivkohdh".'"'." ");
					fwrite($fp_bsh, '"'."UNIX CP".'"'." ");
					fwrite($fp_bsh, date("d/m/y",$date)." ");
					fwrite($fp_bsh, date("His",$date)." ");
					fwrite($fp_bsh, '"'.$session->emp_id.'"'." ");
					fwrite($fp_bsh, '"'."SENT".'"'." ");
					fwrite($fp_bsh, '"'.date("d/m/Y",$date).'"');
					fwrite($fp_bsh, "\r\n");
				}
				$bsh->close();
			}
			
			$bsh2 = $sp->spSalesInvoiceBSHDetailsInterfaceFile($database, $row->ID);
			if($bsh2->num_rows)
			{
				while($row_bsh2 = $bsh2->fetch_object())
				{
					fwrite($fp_bsh, '"'.strtoupper($row->Code).'"'." ");
					fwrite($fp_bsh, '""'." ");
					fwrite($fp_bsh, $row_bsh2->TotQty." ");
					fwrite($fp_bsh, $row_bsh2->TotCSP." ");
					fwrite($fp_bsh, $row_bsh2->TotQty." ");
					fwrite($fp_bsh, $dealerdisc." ");
					fwrite($fp_bsh, $addtldisc." ");
					fwrite($fp_bsh, $row_bsh2->TotConPrice." ");
					fwrite($fp_bsh, "no"." ");
					fwrite($fp_bsh, $vatamount." ");
					fwrite($fp_bsh, $adpp);
					fwrite($fp_bsh, "\r\n");
				}
				$bsh2->close();
			}
			fclose($fp_bsh);
			
			/* BSD */
			$dumpname = $today2."00".".".strtoupper($row->Code)."bsd";
			$bsdh = "bsdh".$ctr;
			$bsdd = "bsdd".$ctr;
			
			$bsdh = $sp->spSalesInvoiceHeaderInterfaceFile($database, $row->ID);
			$bsdd = $sp->spSalesInvoiceDetailsInterfaceFile($database, $row->ID);
			
			if($bsdh->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'bsd';
				$fp_bsd = fopen(AUTO_UPLOAD.'/download/bsd/'.$filename, 'w');
				while($row_bsdh = $bsdh->fetch_object())
				{
					fwrite($fp_bsd, '"'."dtdsin_s".'"'." ");
					fwrite($fp_bsd, '"'.$dumpname.'"'." ");
					fwrite($fp_bsd, '"'."bsd".'"'." ");
					fwrite($fp_bsd, $bsdd->num_rows." ");
					fwrite($fp_bsd, $row_bsdh->TotNetAmount." ");
					fwrite($fp_bsd, '"'."ldPccldkqivkohdh".'"'." ");
					fwrite($fp_bsd, '"'."UNIX CP".'"'." ");
					fwrite($fp_bsd, date("d/m/y",$date)." ");
					fwrite($fp_bsd, date("His",$date)." ");
					fwrite($fp_bsd, '"'.$session->emp_id.'"'." ");
					fwrite($fp_bsd, '"'."SENT".'"'." ");
					fwrite($fp_bsd, '"'.date("d/m/Y",$date).'"');
					fwrite($fp_bsd, "\r\n");
				}
				$bsdh->close();
			}
			
			if($bsdd->num_rows)
			{
				while($row_bsdd = $bsdd->fetch_object())
				{
					fwrite($fp_bsd, $row_bsdd->BranchCode." ");
					fwrite($fp_bsd, $row_bsdd->TxnDate." ");
					fwrite($fp_bsd, $row_bsdd->Qty." ");
					fwrite($fp_bsd, $row_bsdd->ProductCode." ");
					fwrite($fp_bsd, $row_bsdd->CampaignPrice." ");
					fwrite($fp_bsd, $row_bsdd->BasicDisc." ");
					fwrite($fp_bsd, $row_bsdd->NetAmount." ");
					fwrite($fp_bsd, $row_bsdd->ConsumerPrice." ");
					fwrite($fp_bsd, $row_bsdd->Tax." ");
					fwrite($fp_bsd, $row_bsdd->VatAmount." ");
					fwrite($fp_bsd, $row_bsdd->AddtlDisc." ");
					fwrite($fp_bsd, "\r\n");
				}
				$bsdd->close();
			}
			fclose($fp_bsd);
			
			/* RHO */
			$dumpname = $today2."00".".".strtoupper($row->Code)."rho";
			$rho = "rho".$ctr;
			
			$rho = $sp->spRHOInterfaceFile($database, $row->ID);
			if($rho->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'rho';
				$fp_rho = fopen(AUTO_UPLOAD.'/download/rho/'.$filename, 'w');
				fwrite($fp_rho, '"'.$row->Code.'"'." ");
				fwrite($fp_rho, date("d/m/y",$date)." ");
				fwrite($fp_rho, "\r\n");
				while($row_rho = $rho->fetch_object())
				{
					fwrite($fp_rho, $row_rho->ReceiptNo." ");
					fwrite($fp_rho, $row_rho->ShipmentNo." ");
					fwrite($fp_rho, $row_rho->DocumentNo." ");
					fwrite($fp_rho, $row_rho->ProdCode." ");
					fwrite($fp_rho, $row_rho->LoadedQty." ");
					fwrite($fp_rho, $row_rho->ConfirmedQty);
					fwrite($fp_rho, "\r\n");
				}
				$rho->close();
			}
			fclose($fp_rho);
			
			/* ZQT */
			$dumpname = $today2."00".".".strtoupper($row->Code)."zqt";
			$zqt = "zqt".$ctr;
			
			$zqt = $sp->spZQTInterfaceFile($database, $row->ID);
			if($zqt->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'zqt';
				$fp_zqt = fopen(AUTO_UPLOAD.'/download/zqt/'.$filename, 'w');
				fwrite($fp_zqt, '"'.$row->Code.'"'." ");
				fwrite($fp_zqt, date("d/m/y",$date)." ");
				fwrite($fp_zqt, 'yes'." ");
				fwrite($fp_zqt, '"'.'UNIX CP'.'"'." ");
				fwrite($fp_zqt, '"'.$dumpname.'"'." ");
				fwrite($fp_zqt, '"'.'inhnAivHNllncklb'.'"'." ");
				fwrite($fp_zqt, $zqt->num_rows);
				fwrite($fp_zqt, "\r\n");
				while($row_zqt = $zqt->fetch_object())
				{
					fwrite($fp_zqt, '"'.$row->Code.'"'." ");
					fwrite($fp_zqt, $row_zqt->TxnDate." ");
					fwrite($fp_zqt, $row_zqt->ProdCode." ");
					fwrite($fp_zqt, $row_zqt->SOH);
					fwrite($fp_zqt, "\r\n");
				}
				$zqt->close();
			}
			fclose($fp_zqt);
			
			/* INV */
			$dumpname = $today2."00".".".strtoupper($row->Code)."inv";
			$inv = "inv".$ctr;
			
			$inv = $sp->spInventoryTransactionsInterfaceFile($database, $row->ID);
			if($inv->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'inv';
				$fp_inv = fopen(AUTO_UPLOAD.'/download/inv/'.$filename, 'w');
				fwrite($fp_inv, '"'.$row->Code.'"'." ");
				fwrite($fp_inv, '"'.date("d/m/y",$date).'"'." ");
				fwrite($fp_inv, '"'.date("d/m/y",$date).'"'." ");
				fwrite($fp_inv, 'yes'." ");
				fwrite($fp_inv, '"'.'UNIX CP'.'"'." ");
				fwrite($fp_inv, '"'.$dumpname.'"'." ");
				fwrite($fp_inv, '"'.'ldPccldkqivkohdh'.'"'." ");
				fwrite($fp_inv, $inv->num_rows);
				fwrite($fp_inv, "\r\n");
				while($row_inv = $inv->fetch_object())
				{
					fwrite($fp_inv, $row_inv->MovementOrder." ");
					fwrite($fp_inv, $row_inv->TxnDate." ");
					fwrite($fp_inv, $row_inv->TxnType." ");
					fwrite($fp_inv, $row_inv->SourceLocation." ");
					fwrite($fp_inv, $row_inv->ReceivingLocation." ");
					fwrite($fp_inv, $row_inv->ItemCode." ");
					fwrite($fp_inv, $row_inv->Qty." ");
					fwrite($fp_inv, $row_inv->UOM." ");
					fwrite($fp_inv, $row_inv->DebitAccount." ");
					fwrite($fp_inv, $row_inv->DebitCCenter." ");
					fwrite($fp_inv, $row_inv->CreditAccount." ");
					fwrite($fp_inv, $row_inv->CreditCCenter." ");
					fwrite($fp_inv, $row_inv->Total." ");
					fwrite($fp_inv, $row_inv->MovementCode." ");
					fwrite($fp_inv, $row_inv->ReferenceNo." ");
					fwrite($fp_inv, $row_inv->IBTAcct." ");
					fwrite($fp_inv, $row_inv->IBTCCenter." ");
					fwrite($fp_inv, $row_inv->IBTProj);
					fwrite($fp_inv, "\r\n");
				}
				$inv->close();
			}
			fclose($fp_zqt);
			
			/* VDS */
			$dumpname = $today2."00".".".strtoupper($row->Code)."vds";
			$vds = "vds".$ctr;
			
			$total = 0;
			$vds_total = $sp->spVDSInterfaceFile($database, $row->ID);
			if($vds_total->num_rows)
			{
				while($row_vds_tot = $vds_total->fetch_object())
				{
					$total += $row_vds_tot->Amount;
				}
				$vds_total->close();
			}
			
			$vds = $sp->spVDSInterfaceFile($database, $row->ID);
			if($vds->num_rows)
			{
				$filename = $today2."00".".".strtoupper($row->Code).'vds';
				$fp_vds = fopen(AUTO_UPLOAD.'/download/vds/'.$filename, 'w');
				fwrite($fp_vds, '"'.$row->Code.'"'." ");
				fwrite($fp_vds, '"'.date("d/m/y",$date).'"'." ");
				fwrite($fp_vds, '"'.date("d/m/y",$date).'"'." ");
				fwrite($fp_vds, '"'.$dumpname.'"'." ");
				fwrite($fp_vds, $total." ");
				fwrite($fp_vds, $vds->num_rows);
				fwrite($fp_vds, "\r\n");
				while($row_vds = $vds->fetch_object())
				{
					fwrite($fp_vds, $row_vds->PaymentType." ");
					fwrite($fp_vds, $row_vds->Amount." ");
					fwrite($fp_vds, $row_vds->TxnDate." ");
					fwrite($fp_vds, $row_vds->DepositDate." ");
					fwrite($fp_vds, $row_vds->DepositSlipNo." ");
					fwrite($fp_vds, $row_vds->Bank);
					fwrite($fp_vds, "\r\n");
				}
				$vds->close();
			}
			fclose($fp_vds);
		}
		$rs_branch->close();
   } 	
?>