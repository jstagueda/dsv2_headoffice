<?php
	//print_r($_POST);
	//die();
	include '../../../initialize.php';
	include IN_PATH.DS.'pagination.php';
//parameterss...
	$datefrom 	 = date("m/d/Y",strtotime($_POST['datefrom'])); //%Y-%m-%d'
    $dateto 	 = date("m/d/Y",strtotime($_POST['dateto'])); // %Y-%m-%d'
    $branchfrom  = trim($_POST['branchfrom']); 
    $branchto 	 = trim($_POST['branchto']);
	$pageNum = 1;
	$offset  = 0;
	$RPP 	 = 8;
	if(isset($_POST['page'])){
		$pageNum    = $_POST['page'];
		$offset 	= $_POST['page'];
	}
	
	$offset = ($pageNum - 1) * $RPP;
	$q = PaymentClassification_query($datefrom,$dateto,$branchfrom,$branchto,0,0);

	$num = $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	
	$PaymentClassification_query = PaymentClassification_query($datefrom,$dateto,$branchfrom,$branchto,$offset,$RPP);
	//echo $dccr_query;
	if($PaymentClassification_query->num_rows){
		$validation = "";
		while($r=$PaymentClassification_query->fetch_object()){			
			$advance = ($r->a1 + $r->a4);
			$result['data_handler'][]=array('BrancCode'=>$r->BrancCode, 
											'ModeOfPayment'=> $r->ModeOfPayment, 
											'OnTime38Ddays'=> number_format($r->a1,2,'.',''), 
											'Beyond38DdaysButWithin52'=> number_format($r->a2,2,'.',''), 
											'Beyond38days'=> number_format($r->a3,2,'.',''), 
											'OnTime52Ddays'=> number_format($r->a4,2,'.',''), 
											'x52daysOnwards'=> number_format($r->a5,2,'.',''),
											'advance'=>number_format($advance,2,'.',''));
		}
		$result['response']='success';
		
	}else{
		$result['response']='failed';
	}
	die(json_encode($result));
?>