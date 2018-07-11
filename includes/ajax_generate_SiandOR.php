<?php 
	include "../initialize.php";
	global $database;
	if($_GET['request']=='autocompleter'){
		$Code = $_GET['term'];
		$query = $database->execute("SELECT * FROM customer where Code like '%".$Code."%' LIMIT 10");
		if($query->num_rows){
			while($r = $query->fetch_object()){
				$Code = trim($r->Code);
				$Dealer = trim($r->Name);
				$result[] = array('Code'=>$Code, 'Dealer'=>$Dealer);
			}
				die(json_encode($result));
		}else{
				$result[] =array('Code'=>'No record(s) result', 'Dealer'=>'No record(s) result');
				die(json_encode($result));
		}
	}
	
	if($_GET['request']=='generatelist'){

		$type 		 = $_POST['type'];
		$date_from   = date("Y-m-d",strtotime($_POST['date_from']));
		$date_to 	 = date("Y-m-d",strtotime($_POST['date_to']));
		
		$dealer_from = $_POST['dealer_code_from'];
		$dealer_to	 = $_POST['dealer_code_to'];
		if ($dealer_from != "" && $dealer_to != ""){
				$where_dealer = " AND c.Code BETWEEN '".$dealer_from."' And '".$dealer_to."'";
		}else if ($dealer_from != "" && $dealer_to == ""){
				$where_dealer = " AND c.Code LIKE '%".$dealer_from."%'";
			
		}else{
				$where_dealer = "";
		}

		if($type == 1){
			if($_POST['advance_po'] != ""){
				$advance_po = 1;
			}else{
				$advance_po = 0;
			}
			
			$si_from = $_POST['si_from']; 
			$si_to 	 = $_POST['si_to'];
			if ($si_from != "" && $si_to != ""){
				$where_si_from_to = " AND CAST(CONCAT('SI', REPEAT('0', (8- LENGTH(si.ID))), si.ID) AS CHAR) BETWEEN '".$si_from."' And '".$si_to."'";
			}else if ($si_from != "" && $si_to == ""){
				$where_si_from_to = " AND CAST(CONCAT('SI', REPEAT('0', (8- LENGTH(si.ID))), si.ID) AS CHAR) LIKE '%".$si_from."%'";
			}else{
				$where_si_from_to = "";
			}
			
			$q = "SELECT
					 CAST(CONCAT('SI', REPEAT('0', (8- LENGTH(si.ID))), si.ID) AS CHAR) TxnNo, si.ID TxnID,si.DocumentNo,DATE_FORMAT(si.TxnDate, '%M %d, %Y') TxnDate,
					 c.Name CustomerName, ts.ID   TxnStatusID,ts.Name TxnStatus FROM salesinvoice si
					 LEFT JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
					 LEFT JOIN salesorder so ON so.ID = dr.RefTxnID
					 INNER JOIN customer c ON c.ID = si.CustomerID
					 INNER JOIN status ts ON ts.ID = si.StatusID
				  WHERE si.StatusID <> 6 and IsAdvanced = ".$advance_po." AND si.TxnDate BETWEEN '".$date_from."' AND '".$date_to."'   ".$where_dealer." ".$where_si_from_to."
				  GROUP BY si.ID ORDER BY si.ID DESC";

		}else{
			$so_from = $_POST['or_from']; 
			$so_to 	 = $_POST['or_to'];
	
			if ($so_from != "" && $so_to != ""){
				$where_so_from_to = "and CAST(CONCAT('PR', REPEAT('0', (8- LENGTH(pr.ID))), pr.ID) AS CHAR) as char BETWEEN '".$so_from."' And '".$so_to."'";
			}else if ($so_from != "" && $so_to == ""){
				$where_so_from_to = "and CAST(CONCAT('PR', REPEAT('0', (8- LENGTH(pr.ID))), pr.ID) AS CHAR) LIKE '%".$so_from."%'";
			}else{
				$where_so_from_to = "";
			}
			$q = "SELECT pr.ID TxnID,
					CAST(CONCAT('PR', REPEAT('0', (8- LENGTH(pr.ID))), pr.ID) AS CHAR) TxnNo,
					pr.DocumentNo,
					c.Name CustomerName,
					DATE_FORMAT(pr.TxnDate,'%M %d,%Y') TxnDate,
					t.Name TxnStatus,
					t.ID   TxnStatusID
				  FROM `provisionalreceipt` pr
				  INNER JOIN customer c ON c.ID = pr.`CustomerID`
				  INNER JOIN `status` t ON pr.`StatusID` = t.ID
				  WHERE pr.StatusID <> 6 and pr.TxnDate BETWEEN '".$date_from."' AND '".$date_to."' ".$where_dealer." ".$where_so_from_to."
				  ORDER BY pr.ID desc";
			//echo $q;
			//die();
		}

		$db_result = $database->execute($q);
		if($db_result->num_rows){
			while($r = $db_result->fetch_object()){
				$TxnNo		  = $r->TxnNo;
				$TxnID		  = $r->TxnID;
				$DocumentNo	  = $r->DocumentNo;
				$CustomerName = $r->CustomerName;
				$TxnDate	  = $r->TxnDate;
				$TxnStatusID  = $r->TxnStatusID;
				$TxnStatus	  = $r->TxnStatus;
				$result['fetch_data'][] = array('TxnNo'=>$TxnNo,'TxnID'=>$TxnID,'DocumentNo'=>$DocumentNo,	  
												'CustomerName'=>$CustomerName,'TxnDate'=>$TxnDate,	  
												'TxnStatusID'=>$TxnStatusID,'TxnStatus'=>$TxnStatus);}
			$result['response'] = array('result'=>'success');
		}else{
			$result['response'] = array('result'=>'failed');
		}
			die(json_encode($result));
	}
?>