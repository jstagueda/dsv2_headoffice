<?php 

include "../../../initialize.php";
if(isset($_POST['request'])){
	if($_POST['request']=='GetListedPromo'){
	//die('here');
	$result = getAllPromo();
	die(json_encode($result));
	}
	
	if($_POST['request']=="save"){
		//print_r($_POST);
		//die();
		
		
		try
		{
			$database->beginTransaction();
			$PromoID 	= $_POST['PromoID'];
			$BranchID 	= $_POST['BranchID'];
			//delete existing linking
			foreach ($PromoID as $pid){
				$sp->spDeleteBranchLinkingByPromoID($database, $pid);
				foreach($BranchID as $value){
					$sp->spInsertPromoBranchLinking($database, $pid, $value);
				}
			}
			$database->commitTransaction();	
			$result = getAllPromo();
			die(json_encode($result));
			
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage();
			$result['response']=$errmsg;
		}
	}
	
	if($_POST['request']=="SearchPromo"){
		$PromoID=($_POST['PromoID']==""?0:$_POST['PromoID']);
		$promoType = $_POST['cboPromoType'];
		$result = SearchPromo($PromoID, $promoType);
		die(json_encode($result));
	}
}

if(isset($_GET['request'])){
	if($_GET['request']=='search'){	
		
		if($_GET['PromoType']==0){
			$PromoType = "";
		}else{
			$PromoType = $_GET['PromoType'];
		}
		$PromoCode = $_GET['term'];
		$result = (getAllPromo($PromoCode,$PromoType,1));
		die(json_encode($result));
	}
}



function getAllPromo($search="",$promoTypeid="",$validation=0)
{
		
	
	global $database;
	$sp = new StoredProcedures();
	$limit  = ($validation==1 ? "limit 20":"");
	
	if($promoTypeid==0 || $promoTypeid==""){
			$PromoType = "";
	}else{
		$PromoType = "and PromoTypeID =".$promoTypeid;
	}
	
	$q = $database->execute("select * from promo where Code like '%".$search."%' ".$PromoType." ".$limit);

		if($q->num_rows){
				while($r=$q->fetch_object()){
					$rs_branch_list = $sp->spSelectBranchByPromoID($database, $r->ID);
					$count = $rs_branch_list->num_rows;	
					$result['data_handler'][]=array("ID"=>$r->ID,'Code'=>$r->Code,"Description"=>$r->Description,"TotaLinkBranches"=>$count);
					$searchresult[]=array("ID"=>$r->ID,'Code'=>$r->Code,"Description"=>$r->Description,"TotaLinkBranches"=>$count);
				}
					$result['resp']="success";
					$result['message']="success";
			}else{
				
				$result['response']="failed";
				$result['message']="No record(s) display.";
				
				$searchresult[]=array("ID"=>'No Record(s) Display',
									  'Code'=>'No Record(s) Display',
									  "Description"=>'No Record(s) Display',
									  "TotaLinkBranches"=>'No Record(s) Display');
			}
			
			if($validation == 0){
				return $result;
			}else{
				return $searchresult;
			}

}

function SearchPromo($PromoID, $promoType)
{
		global $database;
		$sp = new StoredProcedures();
		if($PromoID==0){
			$wherePromoID = " ";
			$wherePromoType =($promoType==0?"":"where PromoTypeID=".$promoType);
		}else{
			$wherePromoID = "where ID=".$PromoID;
			$wherePromoType =($promoType==0?"":" and PromoTypeID=".$promoType);
		}
		$q = $database->execute("select * from promo ".$wherePromoID." ".$wherePromoType);
		
		
		if($q->num_rows){
				while($r=$q->fetch_object()){
					$rs_branch_list = $sp->spSelectBranchByPromoID($database, $r->ID);
					$count = $rs_branch_list->num_rows;	
					$result['data_handler'][]=array("ID"=>$r->ID,'Code'=>$r->Code,"Description"=>$r->Description,"TotaLinkBranches"=>$count);
				}
					$result['resp']="success";
					$result['message']="success";
		}else{
			
			$result['response']="failed";
			$result['message']="No record(s) display.";
		}
		
		return $result;
}
?>