<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: August 14, 2013
 * @description: Daily cash receipts excel file formatter.
 */
    $sheet = $objPHPExcel->getActiveSheet();
    
    $sheet->getColumnDimension("A")->setWidth(40);
    $sheet->getColumnDimension("B")->setWidth(40);
  
    $sheet->getStyle("A1:A8")->applyFromArray(array("font" => array( "bold" => true)));
    
    $sheet->SetCellValue('A1', 'Date:');
    $sheet->SetCellValue('B1', date('F d, Y',strtotime($_GET['Date'])));
    
    $sheet->SetCellValue('A2', 'Branch:');
    $sheet->SetCellValue('B2', tpi_getBranchName($_GET['BranchID']));
    
    $DCR = HOReportDailyCashReceiptsQuery($_GET['BranchID'],$_GET['Date']);
    
    $sheet->getStyle("B4:B8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->SetCellValue('A4', 'Total Cash:');
    $sheet->SetCellValue('B4', $DCR->ORCashTotal);
    $sheet->SetCellValue('A5', 'Total Checks:');
    $sheet->SetCellValue('B5', $DCR->ORChequeTotal);
    $sheet->SetCellValue('A6', 'Total Desposit Slip:');
    $sheet->SetCellValue('B6', $DCR->ORDepositTotal);
    $sheet->SetCellValue('A7', 'Total Cancelled Cash:');
    $sheet->SetCellValue('B7', $DCR->ORCashCancelledTotal);
    $sheet->SetCellValue('A8', 'Total Cancelled Checks:');
    $sheet->SetCellValue('B8', $DCR->ORChequeCancelledTotal);
?>