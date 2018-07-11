<?php
	//include here...
	include '../../../initialize.php';
	include IN_PATH.DS.'pagination.php';
	//parameterss...
	//print_r($_POST); 
	$campaign_from 	 = $_POST['campaign_from']; 
	$branchfrom  = trim($_POST['branchfrom']); 
	$branchto  = trim($_POST['branchto']); 
  	$pageNum = 1;
	$offset  = 0;
	$RPP 	 = 8;
	if(isset($_POST['page'])){
		$pageNum    = $_POST['page'];
		$offset 	= $_POST['page'];
	}
	
	$offset = ($pageNum - 1) * $RPP;
	$q = cfagbr_query($campaign_from,$branchfrom,$branchto,0,0);
	$num = $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	$cfagbr_query = cfagbr_query($campaign_from,$branchfrom,$branchto,$offset,$RPP);
	
	if($cfagbr_query->num_rows){
		while($r = $cfagbr_query->fetch_object()){
			$IBDCode 				 = $r->IBDCode; 
			$IBDName				 = $r->IBDName;
			$Campaign				 = $r->Campaign;
			$DGS					 = $r->DGS;
			$CPI 					 = $r->CPI; 
			$xtotal					 = $r->xtotal;
			$CfCreate 				 = $r->CfCreate; 
			$CFAmount 				 = $r->CFAmount; 
			$DGSLY					 = $r->DGSLY;
			$IncreasedDecreasedinDGS = $r->IncreasedDecreasedinDGS;  
			$GBRate					 = $r->GBRate;
			$GrowthBonus 			 = $r->GrowthBonus; 
			$TotalEarnings 			 = $r->TotalEarnings; 
			$VAT					 = $r->VAT;
			$WithTax				 = $r->WithTax;
			$EarningsAfterTax		 = $r->EarningsAfterTax;
			$result['data_handler'][] = array('IBDCode'=>$IBDCode,'IBDName'=>$IBDName,'Campaign'=>$Campaign,'DGS'=>$DGS,'CPI'=>$CPI,'xtotal'=>$xtotal,
											  'CfCreate'=>$CfCreate,'CFAmount'=>$CFAmount,'DGSLY'=>$DGSLY,					 
											  'IncreasedDecreasedinDGS' =>$IncreasedDecreasedinDGS,'GBRate'=>$GBRate,'GrowthBonus'=>$GrowthBonus,
											  'TotalEarnings'=>$TotalEarnings,'VAT'=>$VAT,					 
											  'WithTax'=>$WithTax,				 
											  'EarningsAfterTax'=>$EarningsAfterTax);
		}
		$result['response']='success';
	}else{
		$result['response']='failed';
	}
	die(json_encode($result));
?>