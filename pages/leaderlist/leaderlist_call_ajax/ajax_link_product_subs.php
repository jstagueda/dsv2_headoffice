<?php 

include "../../../initialize.php";
if(isset($_POST['request'])){
	if($_POST['request']=='GetListedProductSubs'){
	//die('here');
		$result = GetListedProductSubs();
		die($result);
	}
	
	if($_POST['request']=="save"){
		
		//print_rprint_r($_POST);
		//die();
		
		
		try
		{
			$database->beginTransaction();
//			$PromoID 	= $_POST['PromoID'];
//			$BranchID 	= $_POST['BranchID'];
			
			$psid 	  = (isset($_POST['psid'])) ? $_POST['psid'] : 0;
			$BranchID = (isset($_POST['BranchID'])) ? $_POST['BranchID'] : 0;
	
			//delete existing linking
			if($psid > 0){
				foreach ($psid as $pid){
					$sp->spDeleteBranchLinkingByPromoID($database, $pid);
					$database->execute("DELETE FROM productsubslinkbranches where ProductSubstituteID=".$pid);
					foreach($BranchID as $value){
						$database->execute("insert into productsubslinkbranches (ProductSubstituteID,BranchID,EnrollmentDate,LastModifiedDate) values (".$pid.",".$value.",NOW(),NOW())");
					}
				}
			}
			$database->commitTransaction();	
			$result = GetListedProductSubs();
			die($result);
			
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage();
			$result['response']=$errmsg;
			die(json_encode($result));
		}
	}
	
	if($_POST['request']=="SearchPromo"){
	//print_r($_POST);
	//die();
		$ProductID=$_POST['ProductID'];
		if($ProductID <> "" && $_POST['txtSearch'] <> ""){
			$result = SearchProductSubs($ProductID);
		}else{
			$result = GetListedProductSubs();
		}
		die($result);
	}
}

if(isset($_GET['request'])){
	if($_GET['request']=='search'){	
		
		$ProductCode = $_GET['term'];
		$result = GetListedProductSubs($ProductCode);
		die($result);
	}
}



function GetListedProductSubs($search="",$promoTypeid="",$validation=0)
{
		
	global $database;
	$q = $database->execute("SELECT * FROM (
							SELECT
								p.ID pid,
								ps.ID psid,
								p.Code Code,
								p.name description ,
								IFNULL(COUNT(ps.ID),0) noSubs,
								ps.EnrollmentDate,
								(select count(*) from productsubslinkbranches where `ProductSubstituteID`=ps.`ID`) TotaLinkBranches
							FROM product p
							LEFT JOIN productsubstitute ps ON ps.productid = p.id
							WHERE p.productlevelid = 3
							GROUP BY p.id
							ORDER BY p.ID
						) a WHERE noSubs <> 0 and (Code like '%".$search."%' or description like '%".$search."%')
						ORDER BY EnrollmentDate DESC");
	
	if($q->num_rows){
		while($r=$q->fetch_object()){
			$PSID 		  = $r->psid;
			$Code 		  = $r->Code;
			$Description  = $r->description;
			$NoofSubs 	  = $r->noSubs;
			if($search == ""){
				$result['data_handler'][]=array( 'PSID'=> $r->psid,'Code'=> $r->Code,'Description' => $r->description,'NoofSubs'=> $r->noSubs,'TotaLinkBranches'=>$r->TotaLinkBranches );
			}else{
				if($validation <> 0){
					//used for search button..
					$result['data_handler'][]=array( 'PSID'=> $r->psid,'Code'=> $r->Code,'Description' => $r->description,'NoofSubs'=> $r->noSubs,'TotaLinkBranches'=>$r->TotaLinkBranches );
				}else{
					$result[]=array('ID'=>$r->pid, 'PSID'=> $r->psid,'Code'=> $r->Code,'Description' => $r->description,'NoofSubs'=> $r->noSubs,'TotaLinkBranches'=>$r->TotaLinkBranches );
				}
			}
		}
		$result['resp']="success";
	}else{
		if($search <> ""){
			$result[]=array("ID"=>'No Record(s) Display','Code'=>'No Record(s) Display',"Description"=>'No Record(s) Display');
		}

		$result['resp']="failed";
	}
	
	return json_encode($result);
	
}


function SearchProductSubs($ProductID)
{
	global $database;
	$q = $database->execute("select Code from product where ID=".$ProductID);
	$ProductCode = $q->fetch_object()->Code;
	return GetListedProductSubs($ProductCode,0,1);
}

?>