<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: August 12, 2013
 * @description: Excel file generator and downloader for DS system HO reports.
 */
	
    include_once('PHPExcelClasses'.DIRECTORY_SEPARATOR.'Classes'.DIRECTORY_SEPARATOR.'PHPExcel.php');
    include_once('class'.DIRECTORY_SEPARATOR.'config.php');
    include_once('class'.DIRECTORY_SEPARATOR.'mysqli.php');
    include_once('class'.DIRECTORY_SEPARATOR.'tpi_functions.php');
    include_once('class'.DIRECTORY_SEPARATOR.'HOReportFunctionsQuery.php');
    
    $docname = $_GET['docname'];
    
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Type: application/vnd.ms-excel');
    //header('Content-Disposition: attachment;filename="DSWorksheet-'.$docname.'.xls"');
    header('Content-Disposition: attachment;filename="DSWorksheet-'.$docname.'.xls"');
    header('Cache-Control: max-age=0');
    
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setTitle("$docname");

    //include formatted excel generated report...
    include("excel_formats/$docname.php");
    
    $objPHPExcel->getActiveSheet()->setTitle("$docname");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
?>