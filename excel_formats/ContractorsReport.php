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
   

		$campaign_from	 = $_GET['campaign_from'];
		$branchfrom 	 = $_GET['branchfrom'];
		$branchto	 	 = $_GET['branchto'];
    
		$sheet->getStyle("A8:L8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		//set bold fonts..
		$aa = array('A1','A3','B3','D3','A4','B4','D4','A5','A8:O8','A3:B3');
		foreach ($aa as $value){
			  $sheet->getStyle($value)->applyFromArray(array("font" => array( "bold" => true)));
		}
		
		//different display / format of report...
        $sheet->getColumnDimension("A")->setWidth(25);
        $sheet->getColumnDimension("B")->setWidth(25);
        $sheet->getColumnDimension("C")->setWidth(30);
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

		//header here
		$sheet->SetCellValue('A1', 'Contractors fee and Growth Bonus');
		
		$sheet->SetCellValue('A3', 'Campaign:');
		$sheet->SetCellValue('B3', $_GET['campaign_from']);
		
		$sheet->SetCellValue('A4', 'IBD');
        $sheet->SetCellValue('B4', 'From:');
        $sheet->SetCellValue('C4', $_GET['branchfrom']);
        $sheet->SetCellValue('D4', 'To:');
        $sheet->SetCellValue('E4', $_GET['branchto']);
		
		
		//Set column names...
        
        $sheet->SetCellValue('A8', 'IBD CODE');
        $sheet->SetCellValue('B8', 'IBD NAME');
        $sheet->SetCellValue('C8', 'CAMPAIGN');
        $sheet->SetCellValue('D8', 'DGS (CFT & NCFT)');
        $sheet->SetCellValue('E8', 'CPI');
        $sheet->SetCellValue('F8', 'TOTAL');
        $sheet->SetCellValue('G8', 'CF RATE');
        $sheet->SetCellValue('H8', 'DGS (LY)');
        $sheet->SetCellValue('I8', 'INCREASED/ (DECREASED) IN DGS');
        $sheet->SetCellValue('J8', 'GB RATE');
        $sheet->SetCellValue('K8', 'GROWTH BONUS');
        $sheet->SetCellValue('L8', 'TOTAL EARNINGS');
        $sheet->SetCellValue('M8', 'VAT');
        $sheet->SetCellValue('N8', 'W/TAX');
        $sheet->SetCellValue('O8', 'EARNINGS AFTER TAX');
      
        
		$q = cfagbr_query($campaign_from,$branchfrom,$branchto,0,0);
        $ctr = 9;

		$TotalDGS					  = 0;
		$TotalCPI                     = 0;
		$Totalxtotal                  = 0;
		$TotalCfCreate                = 0;
		$TotalDGSLY                   = 0;
		$TotalIncreasedDecreasedinDGS = 0;
		$TotalGBRate                  = 0;
		$TotalGrowthBonus             = 0;
		$TotalTotalEarnings           = 0;
		$TotalVAT                     = 0;
		$TotalWithTax                 = 0;
		$TotalEarningsAfterTax        = 0;

        if($q->num_rows > 0)://loop through the results...
            while($row = $q->fetch_object()):
                
				
                $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("G$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("H$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("I$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("J$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("K$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("L$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //set cell values now...
				
				$sheet->SetCellValue("A$ctr", "$row->IBDCode"); 
                $sheet->SetCellValue("B$ctr", "$row->IBDName"); 
                $sheet->SetCellValue("C$ctr", "$row->Campaign"); 
                $sheet->SetCellValue("D$ctr", "$row->DGS"); 
                $sheet->SetCellValue("E$ctr", "$row->CPI"); 
                $sheet->SetCellValue("F$ctr", "$row->xtotal"); 
                $sheet->SetCellValue("G$ctr", "$row->CfCreate"); 
                $sheet->SetCellValue("H$ctr", "$row->DGSLY"); 
                $sheet->SetCellValue("I$ctr", "$row->IncreasedDecreasedinDGS"); 
                $sheet->SetCellValue("J$ctr", "$row->GBRate");
                $sheet->SetCellValue("K$ctr", "$row->GrowthBonus"); 
                $sheet->SetCellValue("L$ctr", "$row->TotalEarnings"); 
                $sheet->SetCellValue("M$ctr", "$row->VAT"); 
                $sheet->SetCellValue("N$ctr", "$row->WithTax"); 
                $sheet->SetCellValue("O$ctr", "$row->EarningsAfterTax"); 
				//Total..
				$TotalDGS					  += $row->DGS;
				$TotalCPI                     += $row->CPI;
				$Totalxtotal                  += $row->xtotal;
				$TotalCfCreate                += $row->CfCreate;
				$TotalDGSLY                   += $row->DGSLY;
				$TotalIncreasedDecreasedinDGS += $row->IncreasedDecreasedinDGS;
				$TotalGBRate                  += $row->GBRate;
				$TotalGrowthBonus             += $row->GrowthBonus;
				$TotalTotalEarnings           += $row->TotalEarnings;
				$TotalVAT                     += $row->VAT;
				$TotalWithTax                 += $row->WithTax;
				$TotalEarningsAfterTax        += $row->EarningsAfterTax;
				$ctr++;
            endwhile;
				$sheet->getStyle("C$ctr")->applyFromArray(array("font" => array( "bold" => true)));
				$sheet->SetCellValue("A$ctr", "");
                $sheet->SetCellValue("B$ctr", "");
                $sheet->SetCellValue("C$ctr", "TOTAL:");
                $sheet->SetCellValue("D$ctr", "$TotalDGS");
                $sheet->SetCellValue("E$ctr", "$TotalCPI");
                $sheet->SetCellValue("F$ctr", "$Totalxtotal");
                $sheet->SetCellValue("G$ctr", "$TotalCfCreate");
                $sheet->SetCellValue("H$ctr", "$TotalDGSLY");
                $sheet->SetCellValue("I$ctr", "$TotalIncreasedDecreasedinDGS");
                $sheet->SetCellValue("J$ctr", "$TotalGBRate");
                $sheet->SetCellValue("K$ctr", "$TotalGrowthBonus");
                $sheet->SetCellValue("L$ctr", "$TotalTotalEarnings");
                $sheet->SetCellValue("M$ctr", "$TotalVAT");
                $sheet->SetCellValue("N$ctr", "$TotalWithTax");
                $sheet->SetCellValue("O$ctr", "$TotalEarningsAfterTax");
        endif;
		
?>