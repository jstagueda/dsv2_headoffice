<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: August 13, 2013
 * @description: Applied payment report excel code formatter.
 */
    $sheet = $objPHPExcel->getActiveSheet();
    
    $sheet->getColumnDimension("A")->setWidth(20);
    $sheet->getColumnDimension("B")->setWidth(20);
    $sheet->getColumnDimension("C")->setWidth(20);
    $sheet->getColumnDimension("D")->setWidth(20);
    $sheet->getColumnDimension("E")->setWidth(20);
    $sheet->getColumnDimension("F")->setWidth(20);
    
    $sheet->getStyle("A5:F5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A5:F5")->applyFromArray(array("font" => array( "bold" => true)));
    
    $sheet->getStyle("A1")->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle("A2")->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle("A3")->applyFromArray(array("font" => array( "bold" => true)));
    
    //Set the date and branch detail information above the lists of report.
    $sheet->SetCellValue('A1', 'Date:');
    $sheet->SetCellValue('B1', date('F d, Y',strtotime($_GET['Date'])));
    $sheet->SetCellValue('A2', 'Branch:');
    $sheet->SetCellValue('B2', tpi_getBranchName($_GET['BranchID']));
    $sheet->SetCellValue('A3', 'Customer:');
    $sheet->SetCellValue('B3', tpi_getBranchCustomer($_GET['Account'],$_GET['BranchID']));
    
    //Set column names...
    $sheet->SetCellValue('A5', 'OR No.');
    $sheet->SetCellValue('B5', 'Amount');
    $sheet->SetCellValue('C5', 'Invoice No.');
    $sheet->SetCellValue('D5', 'Invoice Date');
    $sheet->SetCellValue('E5', 'Invoice Due Date');
    $sheet->SetCellValue('F5', 'Amount Applied');
    
    //Process query for the report to be generated...
    $q = HOReportAppliedPaymentReportQuery($_GET['BranchID'],$_GET['Account'],$_GET['Date']);
    $ctr = 6;
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
            $sheet->SetCellValue("A$ctr", "$row->ORNumber");
            $sheet->SetCellValue("B$ctr", "$row->Amount");
            $sheet->SetCellValue("C$ctr", "$row->InvoiceNo");
            $sheet->SetCellValue("D$ctr", "$row->InvoiceDate");
            $sheet->SetCellValue("E$ctr", "$row->InvoiceDueDate");
            $sheet->SetCellValue("F$ctr", "$row->AmountApplied");
            $ctr++;
        endwhile;
    endif;
?>
