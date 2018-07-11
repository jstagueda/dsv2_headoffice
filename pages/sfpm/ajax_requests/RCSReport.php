<?php

	include '../../../initialize.php';
	include IN_PATH.DS.'pagination.php';
	//parameterss...
	$datefrom 	 = date("m/d/Y",strtotime($_POST['datefrom'])); //%Y-%m-%d'
    $dateto 	 = date("m/d/Y",strtotime($_POST['dateto'])); // %Y-%m-%d'
    $branchfrom  = trim($_POST['branchfrom']); 
    $branchto 	 = trim($_POST['branchto']);
	$sortby 	 = $_POST['sortby'];
	$pageNum 	 = 1;
	$offset  	 = 0;
	$RPP 	 	 = 8;
	if(isset($_POST['page'])){
		$pageNum    = $_POST['page'];
		$offset 	= $_POST['page'];
	}
	
	$offset = ($pageNum - 1) * $RPP;
	$q = ReturnedCheckSummary_Query($datefrom,$dateto,$branchfrom,$branchto,$sortby,0,0);

	$num = $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	
	$ReturnedCheckSummary_Query = ReturnedCheckSummary_Query($datefrom,$dateto,$branchfrom,$branchto,$sortby,$offset,$RPP);
	//echo $dccr_query;
	if($ReturnedCheckSummary_Query->num_rows){
		$validation = "";
		while($r=$ReturnedCheckSummary_Query->fetch_object()){			
			
			$result['data_handler'][]=array('BranchCode'	 => $r->BranchCode,
											'BranchName'	 => $r->BranchName, 
											'DMCMReferenceNo'=> $r->DMCMReferenceNo, 
											'DMDATE'	 	 => $r->DMDATE, 
											'ORNUMBER'	 	 => $r->ORNUMBER, 
											'ORDate'	 	 => $r->ORDate, 
											'CheckNo'	 	 => $r->CheckNo, 
											'IGSCODE'	 	 => $r->IGSCODE, 
											'IGSName'	 	 => $r->IGSName, 
											'IBMCODE'	 	 => $r->IBMCODE, 
											'ReasonCode' 	 => $r->ReasonCode,
											'Amount'		 => number_format($r->Amount,2));
		}
		$result['response']='success';
		
	}else{
		$result['response']='failed';
	}
	die(json_encode($result));
?>