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
		$branchfrom  = $_GET['branchfrom'];
		$branchto 	 = $_GET['branchto'];
		$issummary 	 = $_GET['issummary'];
    
		$sheet->getStyle("A8:L8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$aa = array('A1','A3','B3','D3','A4','B4','D4','A5','A8:L8','A3:B3');
		foreach ($aa as $value){
			  $sheet->getStyle($value)->applyFromArray(array("font" => array( "bold" => true)));
		}
		
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

		//header here
		$sheet->SetCellValue('A1', 'COLLECTION REPORT');
		
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
		
        $sheet->SetCellValue('A5', "SUMMARY");
		$sheet->SetCellValue('B5', ($_GET['issummary']==1?"YES":"NO"));
		
		//Set column names...
        
        $sheet->SetCellValue('A8', 'Date');
        $sheet->SetCellValue('B8', 'Branch');
        $sheet->SetCellValue('C8', 'Bank');
        $sheet->SetCellValue('D8', 'Reference');
        $sheet->SetCellValue('E8', 'Reason Code');
        $sheet->SetCellValue('F8', 'Cash');
        $sheet->SetCellValue('G8', 'Check');
        $sheet->SetCellValue('H8', 'Offsite');
        $sheet->SetCellValue('I8', 'Cancelled');
        $sheet->SetCellValue('J8', 'Total Collection for Deposit');
        $sheet->SetCellValue('K8', 'Payment Thru Offsettings');
        $sheet->SetCellValue('L8', 'Total Collection');
      
        
        $q = dccr_query($datefrom,$dateto,$branchfrom,$branchto,$issummary,0,0);
        $ctr = 9;
		$Total_Validation_Per_Branch = "";
		//Sub Total here..
		$subtotalcash = 0;
		$subtotalcheck = 0;
		$subtotaldeposit= 0;
		$subtotalcancel = 0;
		$subtotalpaymentthruoffseting = 0;
		$subtotaloffseting = 0;
		$subTotalCollection = 0;
		
		//Grand Total Here..
		$grandtotalcash = 0;
		$grandtotalcheck = 0;
		$grandtotaldeposit= 0;
		$grandtotalcancel = 0;
		$grandtotalpaymentthruoffseting = 0;
		$offseting = 0;
		$TotalCollection = 0;

        if($q->num_rows > 0)://loop through the results...
            while($row = $q->fetch_object()):
                if($Total_Validation_Per_Branch==""){
					$Total_Validation_Per_Branch = $row->BranchCode;
				}
				
				if($Total_Validation_Per_Branch != $row->BranchCode){
						  $sheet->getStyle("E$ctr")->applyFromArray(array("font" => array( "bold" => true)));
					      $sheet->SetCellValue("A$ctr", ""); 
						  $sheet->SetCellValue("B$ctr", ""); 
						  $sheet->SetCellValue("C$ctr", ""); 
						  $sheet->SetCellValue("D$ctr", ""); 
						  $sheet->SetCellValue("E$ctr", "Subtotal:"); 
						  $sheet->SetCellValue("F$ctr", "$subtotalcash"); 
						  $sheet->SetCellValue("G$ctr", "$subtotalcheck"); 
						  $sheet->SetCellValue("H$ctr", "$subtotaldeposit"); 
						  $sheet->SetCellValue("I$ctr", "$subtotalcancel"); 
						  $sheet->SetCellValue("J$ctr", "$subtotalpaymentthruoffseting");
						  $sheet->SetCellValue("K$ctr", "$subtotaloffseting"); 
						  $sheet->SetCellValue("L$ctr", "$subTotalCollection"); 
						  $ctr+=1;
						  
						  $subtotalcash = 0;
						  $subtotalcheck = 0;
						  $subtotaldeposit= 0;
						  $subtotalcancel = 0;
						  $subtotalpaymentthruoffseting = 0;
						  $subtotaloffseting = 0;
						  $subTotalCollection = 0;
				}
				
                $sheet->getStyle("A$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("B$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("C$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("D$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("E$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("F$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("G$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("H$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("I$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("J$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("K$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("L$ctr")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //set cell values now...
				$y=$row->ReasonID;
				$ReasonCode =  _getReason($y);
				$x = ($ReasonCode==null?"":$ReasonCode);
                $sheet->SetCellValue("A$ctr", "$row->xDate"); 
                $sheet->SetCellValue("B$ctr", "$row->BranchCode"); 
                $sheet->SetCellValue("C$ctr", "$row->BankName"); 
                $sheet->SetCellValue("D$ctr", "$row->Reference"); 
                $sheet->SetCellValue("E$ctr", "$x"); 
                $sheet->SetCellValue("F$ctr", "$row->xCash"); 
                $sheet->SetCellValue("G$ctr", "$row->xCheck"); 
                $sheet->SetCellValue("H$ctr", "$row->xOffsite"); 
                $sheet->SetCellValue("I$ctr", "$row->Canceled"); 
                $sheet->SetCellValue("J$ctr", "$row->PaymentThruOffseting");
                $sheet->SetCellValue("K$ctr", "$row->xoffseting"); 
                $sheet->SetCellValue("L$ctr", "$row->TotalCollection"); 
				
				//subtotal..
				$subtotalcash				  += $row->xCash;
				$subtotalcheck 				  += $row->xCheck;
				$subtotaldeposit			  += $row->xOffsite;
				$subtotalcancel 			  += $row->Canceled;	
				$subtotalpaymentthruoffseting += $row->PaymentThruOffseting;
				$subtotaloffseting 			  += $row->xoffseting;
				$subTotalCollection 		  += $row->TotalCollection;
				
				//grandtotal...
				$grandtotalcash 				+= $row->xCash;
				$grandtotalcheck 				+= $row->xCheck;
				$grandtotaldeposit				+= $row->xOffsite;
				$grandtotalcancel 				+= $row->Canceled;
				$grandtotalpaymentthruoffseting += $row->PaymentThruOffseting;
				$offseting 						+= $row->xoffseting;
				$TotalCollection 				+= $row->TotalCollection;
				
				
				
				
				$Total_Validation_Per_Branch = $row->BranchCode;
                $ctr++;
				
            endwhile;
				$sheet->getStyle("E$ctr")->applyFromArray(array("font" => array( "bold" => true)));
				$sheet->SetCellValue("A$ctr", ""); 
                $sheet->SetCellValue("B$ctr", ""); 
                $sheet->SetCellValue("C$ctr", ""); 
                $sheet->SetCellValue("D$ctr", ""); 
                $sheet->SetCellValue("E$ctr", "Grand Total:"); 
                $sheet->SetCellValue("F$ctr", "$grandtotalcash"); 
                $sheet->SetCellValue("G$ctr", "$grandtotalcheck"); 
                $sheet->SetCellValue("H$ctr", "$grandtotaldeposit"); 
                $sheet->SetCellValue("I$ctr", "$grandtotalcancel"); 
                $sheet->SetCellValue("J$ctr", "$grandtotalpaymentthruoffseting"); 
                $sheet->SetCellValue("K$ctr", "$offseting");
                $sheet->SetCellValue("L$ctr", "$TotalCollection"); 
			
        endif;
		
		
		
?>