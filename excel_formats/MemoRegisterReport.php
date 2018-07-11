<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: August 14, 2013
 * @description: Memo register report excel formatting.
 */
    $sheet = $objPHPExcel->getActiveSheet();
    
    $sheet->getColumnDimension("A")->setWidth(30);
    $sheet->getColumnDimension("B")->setWidth(30);
    $sheet->getColumnDimension("C")->setWidth(30);
    $sheet->getColumnDimension("D")->setWidth(30);
    $sheet->getColumnDimension("E")->setWidth(30);
    $sheet->getColumnDimension("F")->setWidth(30);
    
    $sheet->getStyle("A4:F4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A4:F4")->applyFromArray(array("font" => array( "bold" => true)));
    
    $sheet->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
    
    //Set the date and branch detail information above the lists of report.
    $sheet->SetCellValue('A1', 'Date:');
    $sheet->SetCellValue('B1', date('F d, Y',strtotime($_GET['Date'])));
    $sheet->SetCellValue('A2', 'Branch:');
    $sheet->SetCellValue('B2', tpi_getBranchName($_GET['BranchID']));
    
    //Set column names...
    $sheet->SetCellValue('A4', 'Customer');
    $sheet->SetCellValue('B4', 'Document No.');
    $sheet->SetCellValue('C4', 'Reason');
    $sheet->SetCellValue('D4', 'Memo Type');
    $sheet->SetCellValue('E4', 'Amount');
    $sheet->SetCellValue('F4', 'Remarks');
    
    //Process query for the report to be generated...
    $q = HOReportMemoRegisterQuery($_GET['BranchID'],$_GET['Date'],$_GET['ReasonID']);
    $ctr = 5;
    if($q->num_rows > 0)://loop through the results...
        while($row = $q->fetch_object()):
            //set cell values alignment...
            $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("F$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("F$ctr".$sheet->getHighestRow())->getAlignment()->setWrapText(true); 
            
            //set cell values now...
            $sheet->SetCellValue("A$ctr", "$row->Name");
            $sheet->SetCellValue("B$ctr", "$row->DocumentNo");
            $sheet->SetCellValue("C$ctr", "$row->Reason");
            $sheet->SetCellValue("D$ctr", "$row->DMCMType");
            $sheet->SetCellValue("E$ctr", "$row->DCMCTotalAmount");
            $sheet->SetCellValue("F$ctr", "$row->Remarks");
            $ctr++;
        endwhile;
    endif;
?>