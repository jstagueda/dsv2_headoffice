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
   
		$datefrom 	 = date("m/d/Y",strtotime($_GET['datefrom']));
		$dateto 	 = date("m/d/Y",strtotime($_GET['dateto']));
		$branchfrom = $_GET['branchfrom'];
		$branchto 	= $_GET['branchto'];
		
    
    //different display / format of report...
        $sheet->getColumnDimension("A")->setWidth(25);
        $sheet->getColumnDimension("B")->setWidth(35);
        $sheet->getColumnDimension("C")->setWidth(30);
        $sheet->getColumnDimension("D")->setWidth(25);
        $sheet->getColumnDimension("E")->setWidth(40);
        $sheet->getColumnDimension("F")->setWidth(25);
        $sheet->getColumnDimension("G")->setWidth(25);
        $sheet->getColumnDimension("H")->setWidth(25);
		
		$aa = array('A1','A3','B3','D3','A4','B4','D4','A5','A8:L8','A3:B3');
		foreach ($aa as $value){
			  $sheet->getStyle($value)->applyFromArray(array("font" => array( "bold" => true)));
		}
		
			$sheet->SetCellValue('A1', 'PAYMENT CLASSIFICATION');
		
		$sheet->SetCellValue('A3', 'DATE:');
		$sheet->SetCellValue('B3', 'From:');
		$sheet->SetCellValue('C3', date('F d, Y'),$_GET['datefrom']);
		$sheet->SetCellValue('D3', 'To:');
        $sheet->SetCellValue('E3', date('F d, Y'),$_GET['dateto']);
        
		$sheet->SetCellValue('A4', 'BRANCH');
        $sheet->SetCellValue('B4', 'From:');
        $sheet->SetCellValue('C4', $_GET['branchfrom']);
        $sheet->SetCellValue('D4', 'To');
        $sheet->SetCellValue('E4', $_GET['branchto']);
		
        
        //Set column names...
        $sheet->SetCellValue('A8', 'Branch');
        $sheet->SetCellValue('B8', 'Mode of Payment');
        $sheet->SetCellValue('C8', 'Advance');
        $sheet->SetCellValue('D8', 'On-Time 38 Days');
        $sheet->SetCellValue('E8', 'Beyond 38 Days but Within 52 Days');
        $sheet->SetCellValue('F8', 'Beyond 38 Days');
        $sheet->SetCellValue('G8', 'On-Time 52 Days');
        $sheet->SetCellValue('H8', '> 52 Days');
        $sheet->SetCellValue('I8', 'TOTAL');

        
        $q = PaymentClassification_query($datefrom,$dateto,$branchfrom,$branchto,$issummary,0,0);
        $ctr = 9;
        if($q->num_rows > 0)://loop through the results...
            while($row = $q->fetch_object()):
                //set cell values alignment...
                $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("G$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("H$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$advance = ($row->a1 + $row->a4);
				$total	 = ($advance + $row->a1 + $row->a2 + $row->a3 + $row->a4+ $row->a5);
				
                //set cell values now...
                $sheet->SetCellValue("A$ctr", ($row->BrancCode==$xBranchCode?"":"$row->BrancCode")); 
                $sheet->SetCellValue("B$ctr", "$row->ModeOfPayment"); 
                $sheet->SetCellValue("C$ctr", "$advance"); 
                $sheet->SetCellValue("D$ctr", "$row->a1"); 
                $sheet->SetCellValue("E$ctr", "$row->a2"); 
                $sheet->SetCellValue("F$ctr", "$row->a3"); 
                $sheet->SetCellValue("G$ctr", "$row->a4"); 
                $sheet->SetCellValue("H$ctr", "$row->a5"); 
                $sheet->SetCellValue("i$ctr", "$total"); 
				
				$xOverAllTotal 					+= $total;
				$xTotalAdvance 					+= $advance;					
				$xTotalOnTime38Ddays 			+= $row->a1;			
				$xTotalBeyond38DdaysButWithin52 += $row->a2; 	
				$xTotalBeyond38days 			+= $row->a3;		
				$xTotalOnTime52Ddays			+= $row->a4;			
				$xTotalx52daysOnwards			+= $row->a5;	
				
				$xBranchCode 	= $row->BrancCode;
				$xModeOfPayment = $row->ModeOfPayment;

				if($xBranchCode == $row->BrancCode && $xModeOfPayment == "OFFSET"){
					$ctr += 1;
					//set cell values now...
					$sheet->SetCellValue("A$ctr", ""); 
					$sheet->SetCellValue("B$ctr", "TOTAL  COLLECTIONS & OFFSETTINGS"); 
					$sheet->SetCellValue("C$ctr", "$xTotalAdvance"); 
					$sheet->SetCellValue("D$ctr", "$xTotalOnTime38Ddays"); 
					$sheet->SetCellValue("E$ctr", "$xTotalBeyond38DdaysButWithin52"); 
					$sheet->SetCellValue("F$ctr", "$xTotalBeyond38days"); 
					$sheet->SetCellValue("G$ctr", "$xTotalOnTime52Ddays"); 
					$sheet->SetCellValue("H$ctr", "$xTotalx52daysOnwards"); 
					$sheet->SetCellValue("I$ctr", "$xOverAllTotal"); 
					
					$xTotalAdvance 					= 0;					
					$xTotalOnTime38Ddays 			= 0;			
					$xTotalBeyond38DdaysButWithin52 = 0; 	
					$xTotalBeyond38days 			= 0;		
					$xTotalOnTime52Ddays			= 0;			
					$xTotalx52daysOnwards			= 0;	
					$xOverAllTotal					= 0;	
				}

                $ctr++;
            endwhile;
        endif;
	
	
?>