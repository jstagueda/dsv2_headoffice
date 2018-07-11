<?php
include "../../../initialize.php";
global $mysqli;

if ($_POST['request'] == 'fetching_selection'){
	$q = $mysqli->query('SELECT * FROM sfm_level');
		if($q->num_rows){
			while($r = $q->fetch_object()){
				$ID   = $r->codeID;
				$Code = $r->desc_code;
				$results['select'][] = array('LevelID' => $ID, 'Code'=>$Code);
			}
		
		}
		
	$q1 = $mysqli->query("SELECT * FROM tpi_gsutype");		
	if($q1->num_rows){
		while($r = $q1->fetch_object()){
			$ID   = $r->ID;
			$Name = $r->Name;
			$results['select_gsu'][] = array('GSUID' => $ID, 'GSU_NAME'=>$Name);
		}
	}
	die(json_encode($results));
}

if($_POST['request'] == 'Generate List'){
	/*
	$level 	 = $_POST['level'];
	$as_of_date 	 = $_POST['as_of_date'];
	$dealer  = $_POST['dealer'];
	*/
	$level 	 		= $_POST['level'];
	$as_of_date 	= $_POST['as_of_date'];
	$dealer 		= $_POST['dealer'];
	$IBM_Code_From  = $_POST['IBM_Code_From'];
	$IBM_Code_To 	= $_POST['IBM_Code_To'];
	$gsu_type       = $_POST['gsu_type'];
	$past_due_from  = $_POST['past_due_from'];
	$past_due_to    = $_POST['past_due_to'];
	
	if($past_due_from != "" && $past_due_to == ""){
		$DaysDue = " AND DaysDue = ".$past_due_from."";
	}elseif($past_due_from != "" && $past_due_to != ""){
		$DaysDue = " AND DaysDue BETWEEN ".$past_due_from." AND ".$past_due_to."";
	}
	
	
	if($IBM_Code_From != "" && $IBM_Code_To == ""){
			$IBM = "AND f.tpi_IBMCode = '".$IBM_Code_From."'";
			
	}elseif ($IBM_Code_From != "" && $IBM_Code_To != ""){
			$IBM = "AND f.tpi_IBMCode  BETWEEN '".$IBM_Code_From."' AND '".$IBM_Code_To."'";
			
	}else{
			$IBM = "";
	}
	
	if($past_due_from != "" && $past_due_to == ""){
		$where_pastdue = "AND d.OutstandingBalance = ".$past_due_from."";
	}elseif($past_due_from != "" && $past_due_to != ""){
		$where_pastdue = "AND d.OutstandingBalance BETWEEN ".$past_due_from." AND ".$past_due_to."";	
	}else{
		$where_pastdue = "";
	}
	
	
	$q = "SELECT a.ID, d.ID salesID,CONCAT(e.Code,'',a.Code) InvoiceDMNo, a.Code, a.Name, b.DaysDue, DATE_FORMAT(d.TxnDate, '%m/%d/%Y') xDate, 
										DATE_FORMAT(d.EffectivityDate,'%m/%d/%Y') DueDate, b.OutstandingAmount AmountOpen, 
										d.Netamount AmountDue, d.Netamount - b.OutstandingAmount AppliedAmount,
										f.tpi_GSUTypeID GSUTYPE, SUM(d.OutstandingBalance) PastDue, g.Name GSU_Name, CONCAT(TRIM(ibm.Code),'-',ibm.Name) ibm
										FROM customer a
										INNER JOIN customeraccountsreceivable b ON a.ID = b.CustomerID
										INNER JOIN tpi_rcustomerpda c ON a.ID = c.CustomerID
										INNER JOIN salesinvoice d ON b.SalesInvoiceID = d.ID 										
										INNER JOIN branch e ON d.BranchID = e.ID
										INNER JOIN tpi_customerdetails f on a.ID = f.CustomerID
										LEFT JOIN tpi_gsutype g ON f.tpi_GSUTypeID = g.ID 
										INNER JOIN creditterm h ON h.ID=d.CreditTermID 
										INNER JOIN tpi_rcustomeribm i ON a.ID = i.CustomerID
										INNER JOIN customer ibm ON ibm.ID = i.IBMID
										WHERE c.tpi_PDA_ID = 4 AND a.CustomerTypeID = ".$level." AND a.Code LIKE '%".$dealer."%' 
										AND DATE_ADD(d.TxnDate, INTERVAL h.Duration DAY) >= '".$as_of_date."' ".$gsu_where." ".$IBM." ".$where_pastdue." order by a.Name";
									
	//echo $q;
	//die();
	$qq = $mysqli->query($q);
	
	if($qq->num_rows){
	
		$num_rows = $qq->num_rows;
		while($r = $qq->fetch_object()){
		 if($r->ID != NULL || $r->ID != ""){
			$results['fetch_data'][] = array( 
									'xID'=>trim($r->ID), 
									'InvoiceDMNo'=>trim($r->InvoiceDMNo), 
									'Code'=>trim($r->Code), 
									'DealerName'=>trim($r->Name),
									'DaysDue'=>trim($r->DaysDue),
									'Date'=>trim($r->xDate),
									'DueDate'=>trim($r->DueDate),
									'AmountOpen'=>trim($r->AmountOpen),
									'AmountDue'=>trim($r->AmountDue),
									'AppliedAmount'=>trim($r->AppliedAmount),
									'salesID'=>trim($r->salesID),
									'GSUTYPE'=>trim($r->GSUTYPE),
									'GSU_Name'=>trim($r->GSU_Name),
									'ibm'=>trim($r->ibm),
									'PastDue'=>trim($r->PastDue)
									);	
			$results['results'] = array('fetching_data'=>'Success');
		}	else{
			$results['results'] = array("fetching_data" => "failed");
		}
	}
	}else{
			$results['results'] = array("fetching_data" => "failed");
		
	}
	die(json_encode($results));
}

//autocompleter
if(isset($_GET['term'])){
	$DealerCode = $_GET['term'];
		$dbresult =  $mysqli->query(" select a.ID, concat(e.Code,'',a.Code) InvoiceDMNo, a.Code, a.Name, b.DaysDue, d.TxnDate Date, d.EffectivityDate DueDate, 
										  sum(b.OutstandingAmount) AmountOpen, sum(d.Netamount) AmountDue, sum(d.Netamount) - sum(b.OutstandingAmount) AppliedAmount 
									from customer a
									inner join customeraccountsreceivable b on a.ID = b.CustomerID
									inner join tpi_rcustomerpda c on a.ID = c.CustomerID
									inner join salesinvoice d on b.SalesInvoiceID = d.ID
									inner join branch e on d.BranchID = e.ID
									where c.tpi_PDA_ID = 4 and a.Code like '%".$DealerCode."%'
									group by a.ID");
		
		if($dbresult->num_rows){
			while($row = $dbresult->fetch_array(MYSQLI_ASSOC)){
				$results[] = array( 
									'xID'=>trim($row['ID']), 
									'InvoiceDMNo'=>trim($row['InvoiceDMNo']), 
									'Code'=>trim($row['Code']), 
									'DealerName'=>trim($row['Name']),
									'DaysDue'=>trim($row['DaysDue']),
									'Date'=>trim($row['Date']),
									'DueDate'=>trim($row['DueDate']),
									'AmountOpen'=>trim($row['AmountOpen']),
									'AmountDue'=>trim($row['AmountDue']),
									'AppliedAmount'=>trim($row['AppliedAmount'])
									);
			}
			echo tpi_JSONencode($results);
		}else{
			$results[] = array( 
									
									'Code'=>"No record(s) displayed.", 
									'DealerName'=>"No record(s) displayed."
									);
									
				echo tpi_JSONencode($results);
		}
}
?>