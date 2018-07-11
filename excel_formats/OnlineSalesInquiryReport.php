<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: August 14, 2013
 * @description: Online sales inquiry excel formatter.
 */
    $sheet = $objPHPExcel->getActiveSheet();
    
    $sheet->getColumnDimension("A")->setWidth(30);
    $sheet->getColumnDimension("B")->setWidth(30);
    $sheet->getColumnDimension("C")->setWidth(30);
  
    $sheet->getStyle("A1:A9")->applyFromArray(array("font" => array( "bold" => true)));
    
    $sheet->SetCellValue('A1', 'Date:');
    $sheet->SetCellValue('B1', date('F d, Y',strtotime($_GET['Date'])));
    
    $sheet->SetCellValue('A2', 'Branch:');
    $sheet->SetCellValue('B2', tpi_getBranchName($_GET['BranchID']));
    
    $sheet->getStyle("B4")->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle("C4")->applyFromArray(array("font" => array( "bold" => true)));
    $sheet->getStyle("B4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("C4")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->SetCellValue('B4', 'TOTAL');
    $sheet->SetCellValue('C4', 'LESS CPI');
    
    $sheet->getStyle("A5:A9")->applyFromArray(array("font" => array( "bold" => true)));
    
    $Month = date('n',strtotime($_GET['Date']));
    $Year = date('y',strtotime($_GET['Date']));
    $CURDATE = date('Y-m-d',strtotime($_GET['Date']));
    
    $q = HOReportOnlineSalesInquiryQuery($_GET['BranchID'],$Month,$Year,$CURDATE);
    $today = $q->Today; 
    $campaign = $q->Campaign;
    
    //For campaing to date return values....
    if($campaign->num_rows){
        $Campaign = $campaign->fetch_object();
        $CampaignToDateDGS = $Campaign->CampaignToDateDGS;
        $InvoiceAmount = $Campaign->InvoiceAmount;
        $CampaignToDateDGSLessCPI = $Campaign->CampaignToDateDGSLessCPI;
        $InvoiceAmountLessCPI = $Campaign->InvoiceAmountLessCPI;
    }else{
        $CampaignToDateDGS = 0.00;
        $InvoiceAmount = 0.00;
        $CampaignToDateDGSLessCPI = 0.00;
        $InvoiceAmountLessCPI = 0.00;
    }
    
    //For today values...
    if($today->num_rows){
        $Today = $today->fetch_object();
        $TodayTotalDGS = $Today->TodayTotalDGS;
        $TodayTotaInvoiceAmount = $Today->TodayTotaInvoiceAmount;
        $TodayTotalDGSLessCPI = $Today->TodayTotalDGSLessCPI;
        $TodayTotalInvoiceAmountLessCPI = $Today->TodayTotalInvoiceAmountLessCPI;
    }else{
        $TodayTotalDGS = 0.00;
        $TodayTotaInvoiceAmount = 0.00;
        $TodayTotalDGSLessCPI = 0.00;
        $TodayTotalInvoiceAmountLessCPI = 0.00;
    }
    
    $sheet->SetCellValue('A5', 'Campaign to Date DGS:');
    $sheet->SetCellValue('A6', 'Invoice Amount:');
    
    $sheet->getStyle("B5:B9")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle("C5:C9")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    
    $sheet->SetCellValue('B5', $CampaignToDateDGS);
    $sheet->SetCellValue('C5', $CampaignToDateDGSLessCPI);
    $sheet->SetCellValue('B6', $InvoiceAmount);
    $sheet->SetCellValue('C6', $InvoiceAmountLessCPI);
    
    $sheet->SetCellValue('A8', 'Today\'s DGS:');
    $sheet->SetCellValue('A9', 'Invoice Amount:');
    
    $sheet->SetCellValue('B8', $TodayTotalDGS);
    $sheet->SetCellValue('C8', $TodayTotalDGSLessCPI);
    $sheet->SetCellValue('B9', $TodayTotaInvoiceAmount);
    $sheet->SetCellValue('C9', $TodayTotalInvoiceAmountLessCPI);
?>