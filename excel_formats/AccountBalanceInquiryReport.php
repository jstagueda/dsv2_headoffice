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
   
    $SFL = $_GET['SFL'];
    $SFLExp = @explode('_',$SFL);
    $CanPurchase = $SFLExp[1];//checker if sales force level selected can purchase..
    $SFL_ID = $SFLExp[0];
    $SFLDetails = tpi_getSFLDetailsByID($SFL_ID);
    $accountFrom = $_GET['AcctFrom'];
    $accountTo = $_GET['AcctTo'];
    $CLC = (($_GET['CLC'] == 'undefined') ? '' : $_GET['CLC']);
    $BranchName = tpi_getBranchName($_GET['BranchID']);
    
    //different display / format of report...
    if($CanPurchase > 0){
        $sheet->getColumnDimension("A")->setWidth(20);
        $sheet->getColumnDimension("B")->setWidth(35);
        $sheet->getColumnDimension("C")->setWidth(12);
        $sheet->getColumnDimension("D")->setWidth(18);
        $sheet->getColumnDimension("E")->setWidth(18);
        $sheet->getColumnDimension("F")->setWidth(18);
        
        $sheet->getStyle("A5:F5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A5:F5")->applyFromArray(array("font" => array( "bold" => true)));
        $sheet->getStyle("A1:A3")->applyFromArray(array("font" => array( "bold" => true)));
        
        //Set the date and branch detail information above the lists of report.
        $sheet->SetCellValue('A1', 'Date:');
        $sheet->SetCellValue('B1', date('F d, Y'));
        $sheet->SetCellValue('A2', 'Branch:');
        $sheet->SetCellValue('B2', $BranchName);
        $sheet->SetCellValue('A3', 'Sales Force Level:');
        $sheet->SetCellValue('B3', $SFLDetails->description);
        
        $sheet->getStyle('C5:F5')->getAlignment()->setWrapText(true);
        
        //Set column names...
        $sheet->SetCellValue('A5', 'Account No.');
        $sheet->SetCellValue('B5', 'Account Name');
        $sheet->SetCellValue('C5', 'Account Level');
        $sheet->SetCellValue('D5', 'Total Outstanding Balance');
        $sheet->SetCellValue('E5', 'Total Overdue Balance');
        $sheet->SetCellValue('F5', 'Total Penalty Amount');
        
        $q = HOReportAccountBalanceInquiryQuery($_GET['BranchID'],$SFL_ID,$CanPurchase,$accountFrom,$accountTo,$CLC);
        $ctr = 6;
        if($q->num_rows > 0)://loop through the results...
            while($row = $q->fetch_object()):
                //set cell values alignment...
                $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                //set cell values now...
                $sheet->SetCellValue("A$ctr", "$row->Code");
                $sheet->SetCellValue("B$ctr", "$row->Name");
                $sheet->SetCellValue("C$ctr", "$row->desc_code");
                $sheet->SetCellValue("D$ctr", "$row->TotalOutstandingBalance");
                $sheet->SetCellValue("E$ctr", "$row->TotalOverDueBalance");
                $sheet->SetCellValue("F$ctr", "$row->TotalPenaltyAmount");
                $ctr++;
            endwhile;
        endif;
    }else{
        //Formatting for higher level accounts having lower level accounts record(s).
        $sheet->getColumnDimension("A")->setWidth(20);
        $sheet->getColumnDimension("B")->setWidth(35);
        $sheet->getColumnDimension("C")->setWidth(12);
        $sheet->getColumnDimension("D")->setWidth(18);
        $sheet->getColumnDimension("E")->setWidth(18);
        $sheet->getColumnDimension("F")->setWidth(18);
        
        $sheet->getStyle("A5:F5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A5:F5")->applyFromArray(array("font" => array( "bold" => true)));
        $sheet->getStyle("A1:A3")->applyFromArray(array("font" => array( "bold" => true)));
        
        //Set the date and branch detail information above the lists of report.
        $sheet->SetCellValue('A1', 'Date:');
        $sheet->SetCellValue('B1', date('F d, Y'));
        $sheet->SetCellValue('A2', 'Branch:');
        $sheet->SetCellValue('B2', $BranchName);
        $sheet->SetCellValue('A3', 'Sales Force Level:');
        $sheet->SetCellValue('B3', $SFLDetails->description);
        
        $sheet->getStyle('C5:F5')->getAlignment()->setWrapText(true);
        
        //Set column names...
        $sheet->SetCellValue('A5', 'Account No.');
        $sheet->SetCellValue('B5', 'Account Name');
        $sheet->SetCellValue('C5', 'Account Level');
        $sheet->SetCellValue('D5', 'Total Outstanding Balance');
        $sheet->SetCellValue('E5', 'Total Overdue Balance');
        $sheet->SetCellValue('F5', 'Total Penalty Amount');
        
        $q = HOReportAccountBalanceInquiryQuery($_GET['BranchID'],$SFL_ID,$CanPurchase,$accountFrom,$accountTo,$CLC);
        $ctr = 6;
        if($q->num_rows > 0)://loop through the results...
            while($row = $q->fetch_object()):
                $HigherAcct = HOReportABIGetNetworksQuery($_GET['BranchID'],$row->ID);
                //set cell values now...
                if($HigherAcct->num_rows > 0)://loop through the results...
                    //set cell values alignment...
                    $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    
                    //Add some borders to make the listing much readable...
                    $sheet->getStyle("A$ctr:F$ctr")->getBorders()->getBottom()->applyFromArray(
                    		array(
                    			'style' => PHPExcel_Style_Border::BORDER_THIN,
                    			'color' => array('rgb' => '000000')
                    		)
                    );
                    //show higher level account on top...
                    $sheet->SetCellValue("A$ctr", "$row->Code");
                    $sheet->SetCellValue("B$ctr", "$row->Name");
                    $sheet->SetCellValue("C$ctr", "$row->desc_code");
                    
                    //Loop through the lower level accounts of higher level account.
                    while($HigherAcctRow = $HigherAcct->fetch_object()){
                        $ctr++;
                        $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("F$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        
                        $sheet->SetCellValue("A$ctr", "$HigherAcctRow->Code");
                        $sheet->SetCellValue("B$ctr", "$HigherAcctRow->Name");
                        $sheet->SetCellValue("C$ctr", "$HigherAcctRow->desc_code");
                        $sheet->SetCellValue("D$ctr", "$HigherAcctRow->TotalOutstandingBalance");
                        $sheet->SetCellValue("E$ctr", "$HigherAcctRow->TotalOverDueBalance");
                        $sheet->SetCellValue("F$ctr", "$HigherAcctRow->TotalPenaltyAmount");                        
                    }
                    
                    //Let's add a blank row for the beauty of excel format :)....
                    $ctr++;
                    $sheet->SetCellValue("A$ctr", " ");
                    $sheet->SetCellValue("B$ctr", " ");
                    $sheet->SetCellValue("C$ctr", " ");
                    $sheet->SetCellValue("D$ctr", " ");
                    $sheet->SetCellValue("E$ctr", " ");
                    $sheet->SetCellValue("F$ctr", " ");
                    
                    $ctr++;
                endif;
            endwhile;
        endif;
    }
?>