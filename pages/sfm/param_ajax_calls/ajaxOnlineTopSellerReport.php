<?php

include "../../../initialize.php";
include IN_PATH.DS."pagination.php";


if(isset($_POST['searched'])){
    
    $searched = $_POST['searched'];
    $sfmlevel = $_POST['sfmlevel'];
    $Branch = $_POST['Branch'];
    
    $customer = $database->execute("SELECT c.ID, c.Name, TRIM(c.Code) Code FROM customer c 
                                    INNER JOIN (SELECT MAX(ID) ID, CustomerID from tpi_rcustomeribm GROUP BY CustomerID)
                                    tribm ON tribm.CustomerID = c.ID
                                    INNER JOIN tpi_rcustomeribm ribm on ribm.ID = tribm.ID
                                    AND SPLIT_STR(c.HoGeneralID,'-',1) = ribm.CustomerID
                                    WHERE c.CustomerTypeID = $sfmlevel
                                    AND ((c.Code LIKE '$searched%') OR (c.Name LIKE '$searched%')) and SPLIT_STR(c.HoGeneralID,'-',2)=".$Branch."
                                    LIMIT 10");
    if($customer->num_rows){
        while($res = $customer->fetch_object()){
            $result[] = array("ID" => $res->ID, "Value" => $res->Code." - ".$res->Name, "Label" => $res->Code." - ".$res->Name);
        }
    }else{
        $result[] = array("ID" => 0, "Value" => "", "Label" => "No item found.");
    }
    
    die(tpi_JSONencode($result));
}


if(isset($_POST['page'])){
    
	/*	
		$_POST[level] => 0
		$_POST[codefrom] => 
		$_POST[codefromHidden] => 
		$_POST[codeto] => 
		$_POST[codetoHidden] => 
		$_POST[datestart] => 04/07/2014
		$_POST[dateend] => 04/07/2014
		$_POST[productmarketgroup] => 0
		$_POST[invoiceDGS] => 0
		$_POST[collectiondate] => 04/07/2014
		$_POST[sortby] => 
		$_POST[recordtoshow] => 0
		$_POST[page] => 1
	*/
	
	// echo $_POST['branch'];
	// die();
	
	//Num_rows..
	$num_rows = get_records($_POST['level'], $_POST['codefromHidden'], $_POST['codetoHidden'], 
							date("Y-m-d",strtotime($_POST['datestart'])), date("Y-m-d",strtotime($_POST['dateend'])),
							$_POST['recordtoshow'],$_POST['page'],$_POST['branch'],true)->num_rows; // Numrows
	
	//Records...
	$q = get_records($_POST['level'], $_POST['codefromHidden'], $_POST['codetoHidden'], date("Y-m-d",strtotime($_POST['datestart'])), date("Y-m-d",strtotime($_POST['dateend'])),
							$_POST['recordtoshow'],$_POST['page'],$_POST['branch'],false);
	
	
	
    $header = '<table class="tablelisttable" border="0" cellspacing="0" cellpadding="0">';
	$header .='<tr class="tablelisttr">';
	$header .='<td>Sequence</td>';
	$header .='<td>Account No.</td>';
	$header .='<td>Customer</td>';
	$header .='<td>Amount Less CPI</td>';
	$header .='<td>Total Billed Amount</td>';
	$header .='<td>Online Payments</td>';
	$header .='<td>Overdue Payments</td>';
	$header .='<td>On-Time-Or-Not Payments</td>';
	$header .='<td>On-Time BCR</td>';
	$header .='<td>On-Time or/not BCR</td>';
	$header .='</tr>';
	$footer = "</table>";
    $details = "";
    
    // echo $header;
    
		if( $num_rows == 0 ){
			$details.= '<tr class="listtr">
				<td align="center" colspan="10">No result found.</td>
			</tr>';
		}else{
			//Do Process here..
			
			$count = 1;
			while($r = $q->fetch_object()){
			
				$TotalOnTimeAmountPercentagePaid 	 	= 0.00;	
				$TotalOnTimeORNotAmountPercentagePaid	= 0.00;
				
				if(((float)$r->NetAmount) > 0){
					$TotalOnTimeAmountPercentagePaid 	  = ((((float)$r->PaidCFT) + ((float)$r->PaidNCFT) + ((float)$r->PaidCPI)) / ((float)$r->NetAmount));
					$TotalOnTimeORNotAmountPercentagePaid = ((((float)$r->NetAmount) - ((float)$r->OutstandingAmount)) / ((float)$r->NetAmount));
				
				}
				$TotalDGSSales		   = (((float)$r->GrossAmount) - ((float)$r->TotalCPICSPAmount) - ((float)$r->BasicDiscount));
				$TotaLDGSSalesWithCPI  = $total_dgs_sales + ((float)$r->TotalCPICSPAmount);
				
				$TotalOnTimeDGSPayment 			= ($TotalOnTimeAmountPercentagePaid * $TotaLDGSSalesWithCPI);
				$TotalOnTimeOrNotDGSPayemnt 	= ($TotalOnTimeORNotAmountPercentagePaid * $TotaLDGSSalesWithCPI);
				
				if($TotaLDGSSalesWithCPI > 0) {
              
						$OnTimeBCR = (($TotalOnTimeDGSPayment / $TotaLDGSSalesWithCPI) * 100);
						$OnTimeOrNotBCR = (($TotalOnTimeOrNotDGSPayemnt / $TotaLDGSSalesWithCPI) * 100);
				}
				
				
				
				$details.='<tr class="listtr">';
				$details .='<td>'.$count.'</td>';
				$details .='<td>'.$r->AccountNo.'</td>';
				$details .='<td>'.$r->Customer.'</td>';
				$details .='<td>'.number_format(($r->TotalCFTCSPAmount + $r->TotalNCFTCSPAmount),2).'</td>';
				$details .='<td>'.number_format(((float)$r->NetAmount),2).'</td>';
				$details .='<td>'.number_format(((float)$r->NetAmount),2).'</td>';
				$details .='<td>'.number_format(((float)$r->OutstandingAmount),2).'</td>';
				$details .='<td>'.number_format($TotalOnTimeOrNotDGSPayemnt,2).'</td>';
				$details .='<td>'.number_format($OnTimeBCR,2).'</td>';
				$details .='<td>'.number_format($OnTimeOrNotBCR,2).'</td>';
				$details .='</tr>';
				
				$count++;
			}
		}
	// echo $footer;
	$result['data_handler'] = $header.$details.$footer;
	$result['num'] = $num_rows;
	die(json_encode($result));
 
}
        
        
if(isset($_POST['branch'])){
    $query = $database->execute("SELECT * FROM branch 
                                WHERE ID NOT IN (1,2,3) 
                                AND (Name LIKE '".$_POST['branch']."%' OR Code LIKE '".$_POST['branch']."%')
                                ORDER BY Name
                                LIMIT 10");
    if($query->num_rows){
        while($res = $query->fetch_object()){
            $result[] = array("Label" => trim($res->Code)." - ".$res->Name, "Value" => trim($res->Code)." - ".$res->Name, "ID" => $res->ID);
        }
    }else{
        $result[] = array("Label" => "No result found.", "Value" => "", "ID" => 0);
    }
    
    die(json_encode($result));
}

function get_records($level,$codefromHidden,$codetoHidden,$datestart,$dateend,$recordtoshow,$page,$branch,$validation)
{

	global $database;
	
	$Limit = "";
	
	if($validation == false){
		
		if($page > 1){
			$xLimit = $page * 10;
		}else{
			$xLimit = $page;
		}
		
		if( $recordtoshow < $page){
			$Limit = " LIMIT ".$xLimit.", 10";
		}else{
			if($page > 1){
				$xLimit = $recordtoshow  * $page;
			}else{
				$xLimit = $page;
			}
			$Limit = " LIMIT ".$xLimit." ,".$recordtoshow."";
		}
	}
	$q =  "	SELECT * FROM ( SELECT c.CustomerTypeID CustomerLevelID, c.ID customerID, c.Code AccountNo, c.Name Customer, 
								SUM(si.GrossAmount) GrossAmount, SUM(si.BasicDiscount) BasicDiscount, 
								SUM(si.NetAmount) NetAmount, SUM(si.OutstandingBalance) OutstandingAmount, 
								IFNULL(SUM(si.PaidwithinCreditTerms), 0) PaidWithinCreditTerms, 
								IFNULL(SUM(si.PaidwithinCreditTermsCFT), 0) PaidCFT, IFNULL(SUM(si.PaidwithinCreditTermsNCFT), 0) PaidNCFT, 
								IFNULL(SUM(si.PaidwithinCreditTermsCPI), 0) PaidCPI, 
								SUM(si.TotalCFT) DGSCFT, SUM(si.TotalNCFT) DGSNCFT, 
								SUM(si.TotalCPI) DGSCPI, 
								SUM(tpiGetSalesInvoiceTotalCSPAmountByPMGID(si.ID, 3)) TotalCPICSPAmount
								FROM salesinvoice si 
								INNER JOIN customer c ON c.ID=si.CustomerID AND SPLIT_STR(si.HOGeneralID,'-',2) = ".$branch." AND SPLIT_STR(c.HOGeneralID,'-',2) = ".$branch."
								WHERE si.StatusID = 7 and si.OutstandingBalance = 0
								AND DATE(si.TxnDate) BETWEEN DATE('".$datestart."') AND '".$dateend."' 
								AND c.ID BETWEEN ".$codefromHidden." AND ".$codetoHidden." 
								GROUP BY c.ID 
								".$Limit.") tbl ORDER BY NetAmount DESC";
	
	
	return $database->execute($q);
}
?>
