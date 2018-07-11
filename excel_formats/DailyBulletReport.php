<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: August 14, 2013
 * @description: Daily bullet report excel formatter.
 */
    $sheet = $objPHPExcel->getActiveSheet();
    
    $sheet->getColumnDimension("A")->setWidth(20);
    $sheet->getColumnDimension("B")->setWidth(20);
    $sheet->getColumnDimension("C")->setWidth(20);
    $sheet->getColumnDimension("D")->setWidth(20);
    $sheet->getColumnDimension("E")->setWidth(20);
    $sheet->getColumnDimension("F")->setWidth(20);
    
    $sheet->getStyle("A4:F4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A4:F4")->applyFromArray(array("font" => array( "bold" => true)));
    
    $sheet->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle("A3")->applyFromArray(array("font" => array( "bold" => true)));
    
    //Set the date and branch detail information above the lists of report.
    $sheet->SetCellValue('A1', 'Period:');
    $sheet->SetCellValue('B1', date('F Y',strtotime($_GET['Date'])));
    $sheet->SetCellValue('A2', 'Branch:');
    $sheet->SetCellValue('B2', tpi_getBranchName($_GET['BranchID']));
    
    $sheet->getStyle('D4')->getAlignment()->setWrapText(true);
    $sheet->getStyle('E4')->getAlignment()->setWrapText(true);
    
    //Set column names...
    $sheet->SetCellValue('A4', 'PMG Group');
    $sheet->SetCellValue('B4', 'Total DGS Amount');
    $sheet->SetCellValue('C4', 'Total CSP Amount');
    $sheet->SetCellValue('D4', 'Total CSP less CPI Amount');
    $sheet->SetCellValue('E4', 'Total Net Invoice Amount');
    $sheet->SetCellValue('F4', 'Total Unit Sold');
    
    //Process query for the report to be generated...
    $Month = date('n',strtotime($_GET['Date']));
    $Year = date('y',strtotime($_GET['Date']));
    $q = HOReportDailyBulletReportQuery($_GET['BranchID'],$Month,$Year);
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
            
            //set cell values now...
            $sheet->SetCellValue("A$ctr", "$row->PMGType");
            $sheet->SetCellValue("B$ctr", "$row->TotalDGSAmount");
            $sheet->SetCellValue("C$ctr", "$row->TotalCSPAmount");
            $sheet->SetCellValue("D$ctr", "$row->TotalCSPLessCPIAmt");
            $sheet->SetCellValue("E$ctr", "$row->TotalInvoiceAmount");
            $sheet->SetCellValue("F$ctr", "$row->TotalUnitsSold");
            $ctr++;
        endwhile;
    endif;
?>