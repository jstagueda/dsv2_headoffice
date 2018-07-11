<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: August 14, 2013
 * @description: Account balance inquiry report excel formatter.
 */
    $sheet = $objPHPExcel->getActiveSheet();
		$ibmid		 = $_GET['ibmid'];
		$campaign 	 = $_GET['campaign'];
		$branch	 	 = $_GET['branch'];

		$q = $mysqli->query("SELECT br.Code BranchCodes, b.ID IBMID, a.mCode IBMCOde,
							CONCAT(' ',a.last_name,', ',a.first_name,' ',CONCAT(LEFT(a.middle_name,1),'.')) IBMName, '' vendor, '' Tax,
							IF(a.PayoutOrOffset = 1,'Payout','Offset') `Type`, 
							'' GLEffectivityDate
							FROM sfm_manager a 
							INNER JOIN customer b ON SPLIT_STR(a.HOGeneralID,'-',2) = SPLIT_STR(b.HOGeneralID,'-',2)
							INNER JOIN branch br ON br.ID = SPLIT_STR(b.HOGeneralID,'-',2) AND a.mCode = b.Code
							WHERE a.mLevel = 3 AND SPLIT_STR(b.HOGeneralID,'-',1) = ".$ibmid."  AND SPLIT_STR(b.HOGeneralID,'-',2) = ".$branch."");
							
		if($q->num_rows){
			while($r=$q->fetch_object()){
				$HOGeneralID = $r->HoGeneralID;
				$BranchID = $r->BranchCodes;
				//$ibmid = $r->IBMID;
				$Name = $r->IBMName;
				$vendor = $r->vendor;
				$Tax = $r->Tax;
				$Type = $r->Type;
				$GLEffectivityDate = $r->GLEffectivityDate;
			}
		}
		$sheet->getStyle("A8:L8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		//set bold fonts..
		$aa = array('A1','A3','B3','D3','A4','B4','D4','A5','A8:O8','A3:B3','A10','A11',"A14:J14","E4","G4");
		foreach ($aa as $value){
			  $sheet->getStyle($value)->applyFromArray(array("font" => array( "bold" => true)));
		}
		
		//different display / format of report...
        $sheet->getColumnDimension("A")->setWidth(25);
        $sheet->getColumnDimension("B")->setWidth(25);
        $sheet->getColumnDimension("C")->setWidth(15);
        $sheet->getColumnDimension("D")->setWidth(15);
        $sheet->getColumnDimension("E")->setWidth(15);
        $sheet->getColumnDimension("F")->setWidth(15);
        $sheet->getColumnDimension("G")->setWidth(15);
        $sheet->getColumnDimension("H")->setWidth(15);
        $sheet->getColumnDimension("I")->setWidth(15);
        $sheet->getColumnDimension("J")->setWidth(15);

		
		//header here
		$sheet->SetCellValue('A1', 'SERVICE FEE & REFERRAL FEE REPORT');
		//date..
		$sheet->SetCellValue('G1', 'Date:');
		$sheet->SetCellValue('G2', 'Time:');
		$sheet->SetCellValue('H1', date("m/d/Y"));
		$sheet->SetCellValue('H2', date("h:i:s"));
		// end date here
		$sheet->SetCellValue('A4', $_GET['campaign']." IBM - ".$Name);
		$sheet->SetCellValue('B4', "Vendor:");
		$sheet->SetCellValue('B4', $vendor);
		$sheet->SetCellValue('E4', "Tax:");
		$sheet->SetCellValue('F4', $Tax);
		$sheet->SetCellValue('G4', "Type:");
		$sheet->SetCellValue('H4', $Type);		
		
		//service fee
		$sheet->SetCellValue('A10', 'SERVICE FEE');
		$sheet->SetCellValue('A11', '-----------');
		
		//Set column names...
		
		$sheet->SetCellValue("A14","");
		$sheet->SetCellValue("B14","APPOINTMENT DATE");
		$sheet->SetCellValue("C14","TOTAL DGS");
		$sheet->SetCellValue("D14","CFT");
		$sheet->SetCellValue("E14","NCFT");
		$sheet->SetCellValue("F14","CPI");
		$sheet->SetCellValue("G14","TOTAL PAID-UP");	
		$sheet->SetCellValue("H14","BCR");
		
		$sheet->SetCellValue("A15","");
		$sheet->SetCellValue("B15","-------");
		$sheet->SetCellValue("C15","-------");
		$sheet->SetCellValue("D15","-------");
		$sheet->SetCellValue("E15","-------");
		$sheet->SetCellValue("F15","-------");
		$sheet->SetCellValue("G15","-------");	
		$sheet->SetCellValue("H15","-------");
		
		//$q = dccr_query($datefrom,$dateto,$branchfrom,$branchto,$issummary,0,0);

				
		$q = sfandrfreport($ibmid,$campaign,$branch);
		$ctr = 16;         	 
		$SFTotalDGS	= 0;
		$SFCFT      = 0;
		$SFNCFT     = 0;
		$SFCPI      = 0;
		$SFPaidUp   = 0;
        if($q->num_rows > 0)://loop through the results...
            while($row = $q->fetch_object()):

                //set cell values now...
				$AppointedDate = date("d/m/Y",strtotime($row->AppointedDate));
				$sheet->SetCellValue("A$ctr", "$row->Name"); 
                $sheet->SetCellValue("B$ctr", $AppointedDate); 
				
				$xxTotalDGSAmount = $row->CFT + $row->NCFT + $row->CPI;
                $sheet->SetCellValue("C$ctr", $row->xTotalDGSAmount); 
				
				$cftt = (($row->CFT / $row->xTotalDGSAmount) * $row->PaidUP);
                $sheet->SetCellValue("D$ctr", "$cftt"); 
				
				$ncftt = (($row->NCFT / $row->xTotalDGSAmount) * $row->PaidUP);
                $sheet->SetCellValue("E$ctr", "$ncftt"); 
				
				$ncpii = (($row->CPI / $row->xTotalDGSAmount) * $row->PaidUP);
                $sheet->SetCellValue("F$ctr", "$ncpii"); 
				
                $sheet->SetCellValue("G$ctr", "$row->PaidUP"); 
				$BCR = number_format(($row->PaidUP/$row->xTotalDGSAmount)*100,2);
                $sheet->SetCellValue("H$ctr", $BCR."%"); 
				
				$SFTotalDGS += $row->xTotalDGSAmount;
				$SFCFT      += $cftt;
				$SFNCFT     += $ncftt;
				$SFCPI      += $ncpii;
				$SFPaidUp   += $row->PaidUP;
                
				$ctr++;
            endwhile;
			    $ctr += 1;
				
				
				$totalBCR = number_format((($SFPaidUp/$SFTotalDGS)*100),2)."%";
				$sheet->SetCellValue("A$ctr", "Total"); 
                $sheet->SetCellValue("B$ctr", ""); 
                $sheet->SetCellValue("C$ctr", "$SFTotalDGS"); 
                $sheet->SetCellValue("D$ctr", "$SFCFT"); 
                $sheet->SetCellValue("E$ctr", "$SFNCFT"); 
                $sheet->SetCellValue("F$ctr", "$SFCPI"); 
                $sheet->SetCellValue("G$ctr", "$SFPaidUp"); 
                $sheet->SetCellValue("H$ctr", "$totalBCR"); 
				
				$ctr += 1;
				//get rate here...
				
				$cftq  = $mysqli->query("SELECT Discount, Minimum, Maximum,PMGID FROM servicefeecommission WHERE Minimum <= ".$SFCFT."
						 AND maximum >= ".$SFCFT." AND PMGID = 1");
				if($cftq->num_rows){
					while($rr = $cftq->fetch_object()){
						$dcft = $rr->Discount;
					}
				}
				$ncftq = $mysqli->query("SELECT Discount, Minimum, Maximum,PMGID FROM servicefeecommission WHERE Minimum <= ".$SFNCFT."
						 AND maximum >= ".$SFNCFT." AND PMGID = 2");
				if($ncftq->num_rows){
					while($rrr = $ncftq->fetch_object()){
						$dncft = $rrr->Discount;
					}
				}
				$dcratecft = number_format($dcft,0,'','')."%";
				$dcratencft = number_format($dncft,0,'','')."%";
				
				$sheet->SetCellValue("A$ctr", "SF RATE"); 
                $sheet->SetCellValue("D$ctr", "$dcratecft"); 
                $sheet->SetCellValue("E$ctr", "$dcratencft"); 

				
				$ctr += 1;

				
                $sheet->SetCellValue("D$ctr", "----------"); 
                $sheet->SetCellValue("E$ctr", "----------"); 

				$TotalDGSPaymentCFT 	= $SFCFT * ($dcft/100);
				$TotalDGSPaymentNCFT	= $SFNCFT * ($dncft/100);
				
				//$subTotal =  $SubTotalSFCFT + $SubTotalSFNCFT;
				$sheet->SetCellValue("A$ctr", "SUB-TOTAL SF"); 
                $sheet->SetCellValue("D$ctr", "$TotalDGSPaymentCFT"); 
                $sheet->SetCellValue("E$ctr", "$TotalDGSPaymentNCFT");
                //$sheet->SetCellValue("I$ctr", "$EarnedCommission"); 
                
				$ctr += 1;
				
				$sheet->SetCellValue("A$ctr", "SUB-TOTAL SF"); 
                $sheet->SetCellValue("D$ctr", "----------"); 
                $sheet->SetCellValue("E$ctr", "----------"); 

				
				$ctr += 1;
				$SFTotal = $TotalDGSPaymentCFT + $TotalDGSPaymentNCFT;
				$sheet->SetCellValue("A$ctr", "TOTAL SF"); 
				$sheet->SetCellValue("I$ctr", $SFTotal); 
				
				
				//earnings here for affiliated branch..
				$ctr += 2;
			
				$sheet->SetCellValue("A$ctr", "EARNINGS FROM AFFILIATE BRANCHES  - BELOW MAINTENANCE LEVEL"); 
                $sheet->SetCellValue("B$ctr", ""); 
                $sheet->SetCellValue("C$ctr", ""); 
                $sheet->SetCellValue("D$ctr", ""); 
                $sheet->SetCellValue("E$ctr", ""); 
                $sheet->SetCellValue("F$ctr", ""); 
                $sheet->SetCellValue("G$ctr", ""); 
                $sheet->SetCellValue("H$ctr", ""); 
				
				$ctr += 2;
				$sheet->SetCellValue("A$ctr", "TOTAL SERVICE FEE EARNED"); 
                $sheet->SetCellValue("I$ctr", ""); 
				
				
				$ctr += 2;
				$sheet->getStyle("A$ctr")->applyFromArray(array("font" => array( "bold" => true)));
				$sheet->SetCellValue("A$ctr", "REFERAL FEES"); 
				
				$ctr += 1;
				$sheet->getStyle("A$ctr")->applyFromArray(array("font" => array( "bold" => true)));
				$sheet->SetCellValue("A$ctr", "------------------------------");
				$ctr += 1;
				
				//HEADER REFFERAL FEE...
				$sheet->getStyle("A$ctr:i$ctr")->applyFromArray(array("font" => array( "bold" => true)));
				$sheet->SetCellValue("A$ctr","DAUGHTER IBMs");	
				$sheet->SetCellValue("B$ctr","APPOINTMENT DATE");	
				$sheet->SetCellValue("C$ctr","DGS");	
				$sheet->SetCellValue("D$ctr","PAID-UP"); 
				$sheet->SetCellValue("E$ctr","1%");	
				$sheet->SetCellValue("F$ctr","2%");	
				$sheet->SetCellValue("G$ctr","3%");	
				$sheet->SetCellValue("H$ctr","6%");
			
				
				$qrf = $mysqli->query("	SELECT sfa.LevelID, c.Name, DATE(c.EnrollmentDate) AppointedDate, sfa.TotalDGSSales DGS, sfa.TotalDGSPayment PaidUpDGS FROM sfm_manager_networks sfm_n
										LEFT JOIN customer c ON sfm_n.manager_network_code = c.Code AND SPLIT_STR(sfm_n.HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2)
										LEFT JOIN customer cc ON sfm_n.manager_code = cc.Code
										INNER JOIN tpi_sfasummary sfa ON sfa.CustomerID=c.ID AND sfa.IBMID = c.ID AND SPLIT_STR(sfa.HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2)
										WHERE cc.ID = ".$ibmid." AND SPLIT_STR(sfm_n.HOGeneralID,'-',2) = ".$branch." AND SPLIT_STR(cc.HOGeneralID,'-',2) = ".$branch."
										AND sfm_n.manager_code <> sfm_n.manager_network_code  AND sfa.CampaignCode = '".$campaign."' AND c.ID IS NOT NULL
										GROUP BY sfm_n.manager_code, sfm_n.manager_network_code");

				if($qrf->num_rows):
				$ctr += 1;
				
					while($rrrr = $qrf->fetch_object()):

						$sheet->SetCellValue("A$ctr","$rrrr->Name");
						// $sheet->SetCellValue("A$ctr","$ctr");
						$sheet->SetCellValue("B$ctr","$rrrr->AppointedDate");
						$sheet->SetCellValue("C$ctr","$rrrr->DGS");
						$sheet->SetCellValue("D$ctr","$rrrr->PaidUpDGS");
						$Totrfrate1 = 0;
						$Totrfrate2 = 0;
						$Totrfrate3 = 0;
						$Totrfrate4 = 0;
						$rfrate = $mysqli->query("SELECT Discount, Minimum, Maximum FROM rfcommissionfee WHERE Minimum <= ".$rrrr->DGS."
																AND maximum >= ".$rrrr->DGS." AND SFLevelID = ".$rrrr->LevelID);
						if($rfrate->num_rows){
							while($rfr = $rfrate->fetch_object()){
								$discount = $rfr->Discount * 100;
								if($rrrr->LevelID == 3){
									if($discount == 1){
										$rfrate1 = $rrrr->PaidUpDGS * $rfr->Discount;
										$sheet->SetCellValue("E$ctr","$rfrate1");
										$Totrfrate1 += $rfrate1;
									}else{
										$rfrate2 = ($rrrr->PaidUpDGS * $rfr->Discount);
										$sheet->SetCellValue("F$ctr","$rfrate2");
										$Totrfrate2 += $rfrate2;
									}
									$sheet->SetCellValue("G$ctr","");
									$sheet->SetCellValue("H$ctr","");									
								}else{
									$sheet->SetCellValue("E$ctr","");
									$sheet->SetCellValue("F$ctr","");
									if($discount == 3){
										$rfrate3 = $rrrr->PaidUpDGS * $rfr->Discount;
										$sheet->SetCellValue("G$ctr","$rfrate3");
										$Totrfrate4 += $rfrate3;
									}else{
										$rfrate4 = $rrrr->PaidUpDGS * $rfr->Discount;
										$sheet->SetCellValue("H$ctr","$rfrate4");
										$Totrfrate4 += $rfrate4;
									}
								}
							}
							
						}else{
							$sheet->SetCellValue("E$ctr","");
							$sheet->SetCellValue("F$ctr","");
							$sheet->SetCellValue("G$ctr","");
							$sheet->SetCellValue("H$ctr","");				
						}
						//total for rf fee..
						$ctr += 1;
							$sheet->getStyle("A$ctr")->applyFromArray(array("font" => array( "bold" => true)));
							$sheet->SetCellValue("A$ctr","TOTAL REFERRAL FEE EARNED");	
							$sheet->SetCellValue("B$ctr","");	
							$sheet->SetCellValue("C$ctr","");	
							$sheet->SetCellValue("D$ctr",""); 
							$sheet->SetCellValue("E$ctr","$Totrfrate1");	
							$sheet->SetCellValue("F$ctr","$Totrfrate2");	
							$sheet->SetCellValue("G$ctr","$Totrfrate3");	
							$sheet->SetCellValue("H$ctr","$Totrfrate4");
							//OVER ALL TOTAL
							$OverAllTotalRF = $Totrfrate1 + $Totrfrate2 + $Totrfrate3 + $Totrfrate4;
							$sheet->SetCellValue("I$ctr","$OverAllTotalRF");
					endwhile;
				endif;
        endif;
		
		//Over all Computation
		$ctr += 3;
		$TotalSFANDRF = $SFTotal + $OverAllTotalRF;
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","Total SF/RF");	
		$sheet->SetCellValue("H$ctr","$TotalSFANDRF");
		
		$ctr += 1;
		$INCENTIVES = ($SFTotal * .14);
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","OTHER INCENTIVES");	
		$sheet->SetCellValue("H$ctr","$INCENTIVES");
		
		$ctr += 1;
		$GROSSEARNINGS = $TotalSFANDRF + $INCENTIVES;
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","GROSS EARNINGS");	
		$sheet->SetCellValue("H$ctr","$GROSSEARNINGS");
		
		$ctr += 1;
		$VAT = "";
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","VAT");	
		$sheet->SetCellValue("H$ctr","$VAT");
	
		$ctr += 1;
		$LESS10WTAX = ($TotalSFANDRF * .10);
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","LESS: 10% WTAX");	
		$sheet->SetCellValue("H$ctr","$LESS10WTAX");
		
		$ctr += 1;
		$EARNINGAFTERTAX = $GROSSEARNINGS + $VAT - $LESS10WTAX;
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","EARNING AFTER TAX");	
		$sheet->SetCellValue("H$ctr","$EARNINGAFTERTAX");
		
		$ctr += 1;
		$LESS = "";
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","LESS");	
		$sheet->SetCellValue("H$ctr","$LESS");
		
		$ctr += 1;
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","OFFSET");	
		$sheet->SetCellValue("H$ctr","");
		
		$ctr += 1;
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","OTHER DEDUCTION");	
		$sheet->SetCellValue("H$ctr","");
	
		$ctr += 1;
		$NETAMOUNTDUE = $LESS + $EARNINGAFTERTAX;
		$sheet->getStyle("G$ctr")->applyFromArray(array("font" => array( "bold" => true)));
		$sheet->SetCellValue("G$ctr","NET AMOUNT DUE");	
		$sheet->SetCellValue("H$ctr","$NETAMOUNTDUE");
		
		
		
	// Alignment Here..
    for($i = 1; $ctr >= $i; $i++){
			$sheet->getStyle("A$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
               $sheet->getStyle("B$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("C$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("D$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("E$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("F$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("G$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("H$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("I$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("J$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("K$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               $sheet->getStyle("L$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	}
    
?>