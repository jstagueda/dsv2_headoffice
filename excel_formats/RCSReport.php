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
		$sortby 	= $_GET['sortby'];
		
    
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
        
        //Set column names...
        $sheet->SetCellValue('A1', 'Branch Code');
        $sheet->SetCellValue('B1', 'Branch Name');
        $sheet->SetCellValue('C1', 'DM Reference No.');
        $sheet->SetCellValue('D1', 'DM Date');
        $sheet->SetCellValue('E1', 'OR Number');
        $sheet->SetCellValue('F1', 'OR Date');
        $sheet->SetCellValue('G1', 'Check No.');
        $sheet->SetCellValue('H1', 'IGS Code');
        $sheet->SetCellValue('I1', 'IGS Name');
        $sheet->SetCellValue('J1', 'IBM Code');
        $sheet->SetCellValue('K1', 'Reason Code');
        $sheet->SetCellValue('L1', 'Amount');

      
        
        $q = ReturnedCheckSummary_Query($datefrom,$dateto,$branchfrom,$branchto,$sortby,0,0);
        $ctr = 2;
        if($q->num_rows > 0)://loop through the results...
            while($row = $q->fetch_object()):
                //set cell values alignment...
                $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("F$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("G$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("H$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("I$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("J$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("K$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("L$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//set cell values now...
                $sheet->SetCellValue("A$ctr", "$row->BranchCode"); 
                $sheet->SetCellValue("B$ctr", "$row->BranchName"); 
                $sheet->SetCellValue("C$ctr", "$row->DMCMReferenceNo"); 
                $sheet->SetCellValue("D$ctr", "$row->DMDATE"); 
                $sheet->SetCellValue("E$ctr", "$row->ORNUMBER"); 
                $sheet->SetCellValue("F$ctr", "$row->ORDate"); 
                $sheet->SetCellValue("G$ctr", "$row->CheckNo"); 
                $sheet->SetCellValue("H$ctr", "$row->IGSCODE"); 
                $sheet->SetCellValue("I$ctr", "$row->IGSName"); 
                $sheet->SetCellValue("J$ctr", "$row->IBMCODE");
                $sheet->SetCellValue("K$ctr", "$row->ReasonCode"); 
                $sheet->SetCellValue("L$ctr", "$row->Amount"); 

                $ctr++;
            endwhile;
        endif;
	
	
?>