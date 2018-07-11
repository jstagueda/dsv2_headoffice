<?php
	/*
		@author:Gino C. leabres...
		@date: 9/13/2013...
		@explanation: generate DCCR report...
	*/
	//include here...
	include '../../../initialize.php';
	include IN_PATH.DS.'pagination.php';
	
	//parameterss...
	$datefrom 	 = date("m/d/Y",strtotime($_POST['datefrom'])); //%Y-%m-%d'
    $dateto 	 = date("m/d/Y",strtotime($_POST['dateto'])); // %Y-%m-%d'
    $branchfrom  = trim($_POST['branchfrom']); 
    $branchto 	 = trim($_POST['branchto']);
    $issummary	 = $_POST['issummary']; // 1=yes 0 = no...
	$pageNum = 1;
	$offset  = 0;
	$RPP 	 = 8;
	if(isset($_POST['page'])){
		$pageNum    = $_POST['page'];
		$offset 	= $_POST['page'];
	}
	
	$offset = ($pageNum - 1) * $RPP;
	$q = dccr_query($datefrom,$dateto,$branchfrom,$branchto,$issummary,0,0);
	$num = $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	
	$dccr_query = dccr_query($datefrom,$dateto,$branchfrom,$branchto,$issummary,$offset,$RPP);
	//echo $dccr_query;
	if($dccr_query->num_rows){
		while($r=$dccr_query->fetch_object()){
		
			$rq = $database->execute("select * from reason where ID = ".$r->ReasonID);
			if($rq->num_rows){
				while($rr = $rq->fetch_object()){
					$ReasonCode = $rr->Code;
				}
			}
			$result['data_handler'][]=array('xDate'=>$r->xDate,
											'BranchName'=>$r->BranchName,
											'BranchCode'=>$r->BranchCode,
											'BankName'=>$r->BankName,
											'Reference'=>$r->Reference, 
											'ReasonID'=>($ReasonCode==null?"":$ReasonCode),
											'xCash'=>number_format($r->xCash,2),
											'xCheck'=>number_format($r->xCheck,2),
											'xOffsite'=>number_format($r->xOffsite),
											'Canceled'=>number_format($r->Canceled),
											'ReasonName'=>$r->ReasonName, 
											'StatusID'=>$r->StatusID,
											'xoffseting'=>number_format($r->xoffseting,2),
											'PaymentThruOffseting'=>number_format($r->PaymentThruOffseting,2),
											'TotalCollection'=>number_format($r->TotalCollection,2));
		}
		$result['response']='success';
	}else{
		$result['response']='failed';
	}
	die(json_encode($result));
?>