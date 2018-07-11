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
   
	
		//$ = $_GET['docname']; 
		$IBMFROM 		= $_GET['IBMFrom']; 
		$IBMTO 			= $_GET['IBMTo']; 
		$BranchFrom 	= $_GET['Branch_From']; 
		$BRanchTo 		= $_GET['Branch_To']; 
		$CAMPAIGNFROM 	= $_GET['CampaignFrom']; 
		$CAMPAIGNTO 	= $_GET['CampaignTo']; 
		//fonts here..
		$aa = array('A1','A3','B3','D3','A4','B4','D4','A5','A8:v8','A3:B3');
		foreach ($aa as $value){
			  $sheet->getStyle($value)->applyFromArray(array("font" => array( "bold" => true)));
		}
		
		$sheet->SetCellValue('A1', 'IBM CONSOLIDATED SALES & EARNINGS SUMMARY REPORT');
		
		$sheet->SetCellValue('A3', 'DATE:');
		$sheet->SetCellValue('B3', 'From:');
		$sheet->SetCellValue('C3', date('F d, Y'),$_GET['datefrom']);
		$sheet->SetCellValue('D3', 'To:');
        $sheet->SetCellValue('E3', date('F d, Y'),$_GET['dateto']);
        
		$sheet->SetCellValue('A4', 'BRANCH');
        $sheet->SetCellValue('B4', 'From:');
        $sheet->SetCellValue('C4', $_GET['Branch_From']);
        $sheet->SetCellValue('D4', 'To');
        $sheet->SetCellValue('E4', $_GET['Branch_To']);
		
        $sheet->SetCellValue('A5', "CAMPAIGN");
		$sheet->SetCellValue('B5', ($_GET['CampaignFrom']));
		
    //different display / format of report...
        $sheet->getColumnDimension("A")->setWidth(25);
        $sheet->getColumnDimension("B")->setWidth(25);
        $sheet->getColumnDimension("C")->setWidth(25);
        $sheet->getColumnDimension("D")->setWidth(25);
        $sheet->getColumnDimension("E")->setWidth(25);
        $sheet->getColumnDimension("F")->setWidth(25);
        $sheet->getColumnDimension("G")->setWidth(25);
        $sheet->getColumnDimension("H")->setWidth(25);
        $sheet->getColumnDimension("I")->setWidth(25);
        $sheet->getColumnDimension("J")->setWidth(25);
        $sheet->getColumnDimension("K")->setWidth(25);
        $sheet->getColumnDimension("L")->setWidth(25);
        $sheet->getColumnDimension("M")->setWidth(25);
        $sheet->getColumnDimension("N")->setWidth(25);
        $sheet->getColumnDimension("O")->setWidth(25);
        $sheet->getColumnDimension("P")->setWidth(25);
        $sheet->getColumnDimension("Q")->setWidth(25);
        $sheet->getColumnDimension("R")->setWidth(25);
        $sheet->getColumnDimension("S")->setWidth(25);
        $sheet->getColumnDimension("T")->setWidth(25);
        $sheet->getColumnDimension("U")->setWidth(25);
        $sheet->getColumnDimension("V")->setWidth(25);
        
        //Set column names...
        $sheet->SetCellValue('A8', 'IBM CODE');
        $sheet->SetCellValue('B8', 'IBM NAME');
        $sheet->SetCellValue('C8', 'BRANCH CODE');
        $sheet->SetCellValue('D8', 'CAMPAIGN');
        $sheet->SetCellValue('E8', 'DGS CFT');
        $sheet->SetCellValue('F8', 'DGS NCFT');
        $sheet->SetCellValue('G8', 'DGS CPI');
        $sheet->SetCellValue('H8', 'DGS TOTAL');
        $sheet->SetCellValue('I8', 'PAID-UP DGS CFT');
        $sheet->SetCellValue('J8', 'PAID-UP DGS NCFT');
        $sheet->SetCellValue('K8', 'PAID-UP DGS CPI');
        $sheet->SetCellValue('L8', 'PAID-UP DGS TOTAl');
        $sheet->SetCellValue('M8', 'BCR');
        $sheet->SetCellValue('N8', 'EARNED SERVICE FEE');
        $sheet->SetCellValue('O8', 'EARNED REFERRAL FEE');
        $sheet->SetCellValue('P8', 'TOTAL EARNINGS');
        $sheet->SetCellValue('Q8', 'VAT');
        $sheet->SetCellValue('R8', 'W/TAX');
        $sheet->SetCellValue('S8', 'NET EARNINGS');
        $sheet->SetCellValue('T8', 'OFFSET');
        $sheet->SetCellValue('U8', 'AVAILABLE EARNINGS');
        $sheet->SetCellValue('V8', 'PAY-OUT/ OFFSETTING');
        
		//sub here
		$SUBDGSAmountCFT 		=0;
		$SUBDGSAmountNCFT 		=0;
		$SUBDGSAmountCPI 		=0;
		$SUBPMGTotalDGSAmount 	=0;
		$SUBPaidUpDGSCFT 		=0;
		$SUBPaidUpDGSNCFT 		=0;
		$SUBPaidUpDGCPI 		=0;
		$SUBPMGTotalPaidUpDGS 	=0;
		$SUBEarnedServiceFee 	=0;
		$SUBBalanceServiceFee 	=0;
		$SUBTotalNetOfTax  		=0;
		$SUBWithHoldingTax  	=0;
		$SUBBalanceServiceFee	=0;
		$SUBTotalOffsetAmount	=0;
		$SUBBalanceServiceFee	=0;
		
		//GRAND TOTAL HERE...
		$GRANDDGSAmountCFT 		= 0;
		$GRANDDGSAmountNCFT 	= 0;
		$GRANDDGSAmountCPI 		= 0;
		$GRANDPMGTotalDGSAmount = 0;
		$GRANDPaidUpDGSCFT 		= 0;
		$GRANDPaidUpDGSNCFT 	= 0;
		$GRANDPaidUpDGCPI 		= 0;
		$GRANDPMGTotalPaidUpDGS = 0;
		$GRANDEarnedServiceFee 	= 0;
		$GRANDBalanceServiceFee = 0;
		$GRANDTotalNetOfTax  	= 0;
		$GRANDWithHoldingTax  	= 0;
		$GRANDTotalOffsetAmount	= 0;
		
		
		
        $q = ibm_consolidated_sales_earnings_summary_report($IBMFROM,$IBMTO,$BRanchTo,$BranchFrom,$CAMPAIGNFROM,$CAMPAIGNTO);
        $ctr = 9;
        if($q->num_rows > 0)://loop through the results...
            while($row = $q->fetch_object()):
                //set cell values alignment...
				
                $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("G$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("H$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("I$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("J$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("K$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("L$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("M$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("N$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("O$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("P$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("Q$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("R$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("S$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("T$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("U$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("V$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				if($Total_Validation_Per_Branch==""){
					$Total_Validation_Per_Branch = $row->BranchCode;
				}
				//subtotal here..
				if($Total_Validation_Per_Branch != $row->BranchCode){
						    $sheet->getStyle("D$ctr")->applyFromArray(array("font" => array( "bold" => true)));
					        $sheet->SetCellValue("A$ctr", ""); //'IBM CODE'
							$sheet->SetCellValue("B$ctr", ""); //'IBM NAME'
							$sheet->SetCellValue("C$ctr", ""); //'BRANCH CODE'
							$sheet->SetCellValue("D$ctr", "Subtotal:"); //'CAMPAIGN'
							$sheet->SetCellValue("E$ctr", "$SUBDGSAmountCFT"); //'DGS CFT'
							$sheet->SetCellValue("F$ctr", "$SUBDGSAmountNCFT"); //'DGS NCFT'
							$sheet->SetCellValue("G$ctr", "$SUBDGSAmountCPI"); //'DGS CPI'
							$sheet->SetCellValue("H$ctr", "$SUBPMGTotalDGSAmount"); //'DGS TOTAL'
							$sheet->SetCellValue("I$ctr", "$SUBPaidUpDGSCFT"); //'PAID-UP DGS CFT
							$sheet->SetCellValue("J$ctr", "$SUBPaidUpDGSNCFT"); //'PAID-UP DGS NCFT'
							$sheet->SetCellValue("K$ctr", "$SUBPaidUpDGCPI"); //'PAID-UP DGS CPI'
							$sheet->SetCellValue("L$ctr", "$SUBPMGTotalPaidUpDGS"); //'PAID-UP DGS TOTAl'
							$sheet->SetCellValue("M$ctr", "0"); //'BCR'
							$sheet->SetCellValue("N$ctr", "$SUBEarnedServiceFee");//'EARNED SERVICE FEE'
							$sheet->SetCellValue("P$ctr", "$SUBBalanceServiceFee"); //'TOTAL EARNINGS'  + SERVICE FEE
							$sheet->SetCellValue("Q$ctr", "$SUBTotalNetOfTax"); //'VAT'
							$sheet->SetCellValue("O$ctr", "0"); //'EARNED REFERRAL FEE'
							$sheet->SetCellValue("R$ctr", "$SUBWithHoldingTax"); //'W/TAX'
							$sheet->SetCellValue("S$ctr", "$SUBBalanceServiceFee"); //'NET EARNINGS'
							$sheet->SetCellValue("T$ctr", "$SUBTotalOffsetAmount"); //'OFFSET'
							$sheet->SetCellValue("U$ctr", "$SUBBalanceServiceFee"); //'AVAILABLE EARNINGS'
							$sheet->SetCellValue("V$ctr", ""); //'PAY-OUT/ OFFSETTING'
						  
							$SUBDGSAmountCFT 		= 0;
							$SUBDGSAmountNCFT 		= 0;
							$SUBDGSAmountCPI 		= 0;
							$SUBPMGTotalDGSAmount 	= 0;
							$SUBPaidUpDGSCFT 		= 0;
							$SUBPaidUpDGSNCFT 		= 0;
							$SUBPaidUpDGCPI 		= 0;
							$SUBPMGTotalPaidUpDGS 	= 0;
							$SUBEarnedServiceFee 	= 0;
							$SUBBalanceServiceFee 	= 0;
							$SUBTotalNetOfTax  		= 0;
							$SUBWithHoldingTax  	= 0;
							$SUBTotalOffsetAmount	= 0;
						    $ctr+=1;
				}
				
				if($row->PayoutOrOffset == 0){
					$PayoutOrOffset = "OFFSET";
				}else{
					$PayoutOrOffset = "PAY-OUT";
				}
                //set cell values now...
                $sheet->SetCellValue("A$ctr", "$row->IBMCODE"); //'IBM CODE'
                $sheet->SetCellValue("B$ctr", "$row->Name"); //'IBM NAME'
                $sheet->SetCellValue("C$ctr", "$row->BranchCode"); //'BRANCH CODE'
                $sheet->SetCellValue("D$ctr", "$row->CampaignCode"); //'CAMPAIGN'
                $sheet->SetCellValue("E$ctr", "$row->DGSAmountCFT"); //'DGS CFT'
                $sheet->SetCellValue("F$ctr", "$row->DGSAmountNCFT"); //'DGS NCFT'
                $sheet->SetCellValue("G$ctr", "$row->DGSAmountCPI"); //'DGS CPI'
                $sheet->SetCellValue("H$ctr", "$row->PMGTotalDGSAmount"); //'DGS TOTAL'
                $sheet->SetCellValue("I$ctr", "$row->PaidUpDGSCFT"); //'PAID-UP DGS CFT
                $sheet->SetCellValue("J$ctr", "$row->PaidUpDGSNCFT"); //'PAID-UP DGS NCFT'
                $sheet->SetCellValue("K$ctr", "$row->PaidUpDGCPI"); //'PAID-UP DGS CPI'
                $sheet->SetCellValue("L$ctr", "$row->PMGTotalPaidUpDGS"); //'PAID-UP DGS TOTAl'
                $sheet->SetCellValue("M$ctr", "0"); //'BCR'
                $sheet->SetCellValue("N$ctr", "$row->EarnedServiceFee");//'EARNED SERVICE FEE'
                $sheet->SetCellValue("O$ctr", "0"); //'EARNED REFERRAL FEE'
                $sheet->SetCellValue("P$ctr", "$row->BalanceServiceFee"); //'TOTAL EARNINGS'  + SERVICE FEE
                $sheet->SetCellValue("Q$ctr", "$row->TotalNetOfTax"); //'VAT'
                $sheet->SetCellValue("R$ctr", "$row->WithHoldingTax"); //'W/TAX'
                $sheet->SetCellValue("S$ctr", "$row->BalanceServiceFee"); //'NET EARNINGS'
                $sheet->SetCellValue("T$ctr", "$row->TotalOffsetAmount"); //'OFFSET'
                $sheet->SetCellValue("U$ctr", "$row->BalanceServiceFee"); //'AVAILABLE EARNINGS'
                $sheet->SetCellValue("V$ctr", "$PayoutOrOffset"); //'PAY-OUT/ OFFSETTING'
				
				//subtotal here..
				$SUBDGSAmountCFT 		+= $row->DGSAmountCFT;
				$SUBDGSAmountNCFT 		+= $row->DGSAmountNCFT;
				$SUBDGSAmountCPI 		+= $row->DGSAmountCPI;
				$SUBPMGTotalDGSAmount 	+= $row->PMGTotalDGSAmount;
				$SUBPaidUpDGSCFT 		+= $row->PaidUpDGSCFT;
				$SUBPaidUpDGSNCFT 		+= $row->PaidUpDGSNCFT;
				$SUBPaidUpDGCPI 		+= $row->PaidUpDGCPI;
				$SUBPMGTotalPaidUpDGS 	+= $row->PMGTotalPaidUpDGS;
				$SUBEarnedServiceFee 	+= $row->EarnedServiceFee;
				$SUBBalanceServiceFee 	+= $row->BalanceServiceFee;
				$SUBTotalNetOfTax  		+= $row->TotalNetOfTax; 
				$SUBWithHoldingTax  	+= $row->WithHoldingTax; 
				$SUBTotalOffsetAmount	+= $row->TotalOffsetAmount; 
				
				$GRANDDGSAmountCFT 		+= $row->DGSAmountCFT;
				$GRANDDGSAmountNCFT 	+= $row->DGSAmountNCFT;
				$GRANDDGSAmountCPI 		+= $row->DGSAmountCPI;
				$GRANDPMGTotalDGSAmount += $row->PMGTotalDGSAmount;
				$GRANDPaidUpDGSCFT 		+= $row->PaidUpDGSCFT;
				$GRANDPaidUpDGSNCFT 	+= $row->PaidUpDGSNCFT;
				$GRANDPaidUpDGCPI 		+= $row->PaidUpDGCPI;
				$GRANDPMGTotalPaidUpDGS += $row->PMGTotalPaidUpDGS;
				$GRANDEarnedServiceFee 	+= $row->EarnedServiceFee;
				$GRANDBalanceServiceFee += $row->BalanceServiceFee;
				$GRANDTotalNetOfTax  	+= $row->TotalNetOfTax; 
				$GRANDWithHoldingTax  	+= $row->WithHoldingTax; 
				$GRANDTotalOffsetAmount	+= $row->TotalOffsetAmount; 
				
				
				$Total_Validation_Per_Branch = $row->BranchCode;
                $ctr++;
            endwhile;
			
				$sheet->getStyle("D$ctr")->applyFromArray(array("font" => array( "bold" => true)));
				$sheet->SetCellValue("A$ctr", ""); //'IBM CODE'
				$sheet->SetCellValue("B$ctr", ""); //'IBM NAME'
				$sheet->SetCellValue("C$ctr", ""); //'BRANCH CODE'
				$sheet->SetCellValue("D$ctr", "Grand Total:"); //'CAMPAIGN'
				$sheet->SetCellValue("E$ctr", "$GRANDDGSAmountCFT"); //'DGS CFT'
				$sheet->SetCellValue("F$ctr", "$GRANDDGSAmountNCFT"); //'DGS NCFT'
				$sheet->SetCellValue("G$ctr", "$GRANDDGSAmountCPI"); //'DGS CPI'
				$sheet->SetCellValue("H$ctr", "$GRANDPMGTotalDGSAmount"); //'DGS TOTAL'
				$sheet->SetCellValue("I$ctr", "$GRANDPaidUpDGSCFT"); //'PAID-UP DGS CFT
				$sheet->SetCellValue("J$ctr", "$GRANDPaidUpDGSNCFT"); //'PAID-UP DGS NCFT'
				$sheet->SetCellValue("K$ctr", "$GRANDPaidUpDGCPI"); //'PAID-UP DGS CPI'
				$sheet->SetCellValue("L$ctr", "$GRANDPMGTotalPaidUpDGS"); //'PAID-UP DGS TOTAl'
				$sheet->SetCellValue("M$ctr", "0"); //'BCR'
				$sheet->SetCellValue("N$ctr", "$GRANDEarnedServiceFee");//'EARNED SERVICE FEE'
				$sheet->SetCellValue("P$ctr", "$GRANDBalanceServiceFee"); //'TOTAL EARNINGS'  + SERVICE FEE
				$sheet->SetCellValue("Q$ctr", "$SUBTotalNetOfTax"); //'VAT'
				$sheet->SetCellValue("O$ctr", "0"); //'EARNED REFERRAL FEE'
				$sheet->SetCellValue("R$ctr", "$GRANDWithHoldingTax"); //'W/TAX'
				$sheet->SetCellValue("S$ctr", "$GRANDBalanceServiceFee"); //'NET EARNINGS'
				$sheet->SetCellValue("T$ctr", "$GRANDTotalOffsetAmount"); //'OFFSET'
				$sheet->SetCellValue("U$ctr", "$GRANDBalanceServiceFee"); //'AVAILABLE EARNINGS'
				$sheet->SetCellValue("V$ctr", ""); //'PAY-OUT/ OFFSETTING'
        endif;
	
	
?>