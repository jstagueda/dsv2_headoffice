<?php 
include '../../../initialize.php';
include CS_PATH.DS."ClassInventory.php";
global $database;
	//ajax_printcounttagsdetails
	if(isset($_GET['term'])){
		//print_r($_GET);
		//die();
		$txnid = $_GET['tid'];
		$search = $_GET['term'];
		$q = $tpiInventory->spAjaxSelectInvCntDetByID($database, $txnid, 1,$search);
		if($q->num_rows){
			while($row = $q->fetch_object()){
				$ProductID	= $row->ProductID;
				$CountTag 	= $row->CountTag; 
				$pCode 	 	= $row->pCode; 	
				$pName 	 	= $row->pName; 	
				$Location 	= $row->Location; 
				$result[] = array('pCode'=>$pCode, 'pName'=>$pName);
			}
			//$ctr = $q->num_rows;
			//$result['count'] = array('count' => $ctr);
		}
			echo json_encode($result);
	}
	if($_POST['request'] == 'search_item'){
		//print_r($_POST);
		//die();
				$txnid = $_POST['tid'];
				$search = $_POST['ProductCode'];
		$q = $tpiInventory->spAjaxSelectInvCntDetByID($database, $txnid, 1,$search);
		if($q->num_rows){
			while($row = $q->fetch_object()){
				$ProductID	= $row->ProductID;
				$CountTag 	= $row->CountTag; 
				$pCode 	 	= $row->pCode; 	
				$pName 	 	= $row->pName; 	
				$Location 	= $row->Location; 
				$result['fetch_data'][] = array('ProductID' => $ProductID, 'CountTag' => $CountTag, 'pCode' => $pCode, 'pName' => $pName, 'Location' => $Location);
			}
			$ctr = $q->num_rows;
			$result['request'] = array('response'=>'success');
			die(json_encode($result));
		}
			
	}
?>   