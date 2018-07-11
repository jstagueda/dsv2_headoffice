<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: August 15, 2013
 * @description: Statement of account report excel formatter.
 */
    $sheet = $objPHPExcel->getActiveSheet();
    $txnTypes = array('1' => 'DEBIT','2' => 'CREDIT');
    
    $sheet->getColumnDimension("A")->setWidth(20);
    $sheet->getColumnDimension("B")->setWidth(25);
    $sheet->getColumnDimension("C")->setWidth(20);
    $sheet->getColumnDimension("D")->setWidth(25);
    $sheet->getColumnDimension("E")->setWidth(25);
    
    $sheet->getStyle("A6:E6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A6:E6")->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle("A1:A4")->applyFromArray(array("font" => array( "bold" => true)));
    
    //Set the date and branch detail information above the lists of report.
    $sheet->SetCellValue('A1', 'Date Range:');
    $sheet->SetCellValue('B1', date('F d, Y',strtotime($_GET['dateFrom'])) .' - '. date('F d, Y',strtotime($_GET['dateTo'])));
    $sheet->SetCellValue('A2', 'Branch:');
    $sheet->SetCellValue('B2', tpi_getBranchName($_GET['BranchID']));
    $sheet->SetCellValue('A3', 'Customer:');
    $sheet->SetCellValue('B3', tpi_getBranchCustomer($_GET['AccountNo'],$_GET['BranchID']));
    $sheet->SetCellValue('A4', 'Transaction Type:');
    $sheet->SetCellValue('B4', (($_GET['ttype'] > 0) ? $txnTypes[$_GET['ttype']] : $_GET['ttype']));
    
    //Set column names...
    $sheet->SetCellValue('A6', 'Date');
    $sheet->SetCellValue('B6', 'Transaction Type');
    $sheet->SetCellValue('C6', 'Document No.');
    $sheet->SetCellValue('D6', 'Debit/Credit Amount');
    $sheet->SetCellValue('E6', 'Running Balance');
    
    $q = HOReportStatementOfAccountQuery($_GET['BranchID'],$_GET['dateFrom'],$_GET['dateTo'],$_GET['ttype'],$_GET['AccountNo']);
    $ctr = 7;
    if($q->num_rows > 0)://loop through the results...
        while($row = $q->fetch_object()):
            //set cell values alignment...
            $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            
            //set cell values now...
            $sheet->SetCellValue("A$ctr", "$row->Date");
            $sheet->SetCellValue("B$ctr", "$row->MemoType");
            $sheet->SetCellValue("C$ctr", "$row->DocumentNo");
            $sheet->SetCellValue("D$ctr", "$row->TotalAmount");
            $sheet->SetCellValue("E$ctr", "$row->RunningBalance");
            $ctr++;
        endwhile;
    endif;
?>