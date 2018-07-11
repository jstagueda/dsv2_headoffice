<?php
/*

Modified By : Benjie Timogan | 09-24-2014
NEWDS-1259 Single Line Promo


*/
class tpiPromoAndPricing
{

	public function UpdatePromoHeaderByID($database, $prmSID, $promodesc, $startdate, $enddate, $isplusplan, $PageNum,$IsNewRecruit=0)
	{
		
				$rows = $database->execute("SELECT COUNT(ID) cnt FROM `brochureproduct` WHERE PromoID =".$prmSID);
				if($rows->num_rows == 0){
					$statid = 6;
				}else{
					$statid = 7;
				}
				
				if($isplusplan != 2){
					$Updateheader = $database->execute("UPDATE `promo` SET Description = '".$promodesc."', StartDate = '".$startdate."', IsForNewRecruit = ".$IsNewRecruit.",EndDate = '".$enddate."', StatusID = ".$statid.",
									    LastModifiedDate = NOW(), Changed = 1, IsPlusPlan = ".$isplusplan.", PageNum = '".$PageNum."' WHERE ID = ".$prmSID);
				}else{
					$Updateheader = $database->execute("UPDATE `promo` SET Description = '".$promodesc."', StartDate = '".$startdate."', EndDate = '".$enddate."',
										   StatusID = ".$statid.", LastModifiedDate = NOW(), Changed = 1, IsForNewRecruit = ".$IsNewRecruit.", PageNum = '".$PageNum."' WHERE ID = ".$prmSID);
				}
				
				return $Updateheader;
	}
	public function spSelectSinglePromoDetById($database, $prmSID, $promoTypeID)
	{
					$query = $database->execute("SELECT * FROM (
																SELECT ped.pmgid epmgid,prm.ID prmID, pb.ProductID pbProdID, pb.MinQty pbMinQ, pb.MinAmt pbMaxQ, ped.Quantity pedQ, ped.EffectivePrice pedEF,
																prod1.ID prodid, prod1.Code prodCode, prod1.Name prodName, prod2.ID prodid2, prod2.Code prodCode2, prod2.Name prodName2, pe.ID entID,
																pe.ID PromoEntitlementID, ped.ID PromoEntitlementDetailsID,pp.UnitPrice unitprice, pmg.Code pmgcode, pmg.ID pmgid, pb.ID PromoBuyinID,pe.Type TYPE
																FROM promo prm
																INNER JOIN promobuyin pb ON pb.PromoID = prm.ID
																INNER JOIN promoentitlement pe ON pe.PromoBuyinID = pb.ID
																INNER JOIN promoentitlementdetails ped ON ped.PromoEntitlementID = pe.ID
																INNER JOIN product prod1 ON prod1.ID = pb.ProductID
																INNER JOIN product prod2 ON prod2.ID = ped.ProductID
																LEFT JOIN productpricing pp ON pp.ProductID = prod1.ID
																LEFT JOIN tpi_pmg pmg ON pmg.ID = pp.pmgID
																WHERE prm.promotypeid = ".$promoTypeID." AND prm.ID = ".$prmSID."
												ORDER BY prodName ASC ) a GROUP BY pbProdID");
												
										
					return $query;
	}
	
	public function spSelectSinglePrmDetbyIDEnt($database, $prmSID)
	{
	
	
		$query = $database->execute("SELECT * FROM (select
										prm.ID prmID,
										ped.Quantity pedQ,
										ped.EffectivePrice pedEF,
										ped.ID PromoEntitlementDetailsID,
										prod1.ID prodid1,
										prod1.Code prodCode1,
										prod1.Name prodName1,
										prod2.ID prodid2,
										prod2.Code prodCode2,
										prod2.Name prodName2,
										ped.pmgid pmgid,
										pp.UnitPrice unitprice,
										pmg.Code pmgcode,
										pe.ID PromoEntitlementID, 
										pe.Type
										from promo prm
										inner join promobuyin pb on pb.PromoID = prm.ID
										inner join promoentitlement pe on pe.PromoBuyinID = pb.ID
										inner join promoentitlementdetails ped on ped.PromoEntitlementID = pe.ID
										inner join product prod1 on prod1.ID = pb.ProductID
										inner join product prod2 on prod2.ID = ped.ProductID
										left join productpricing pp on pp.ProductID = prod2.ID
										left join tpi_pmg pmg on pmg.ID = ped.PMGID
									 where prm.promotypeid = 1 and prm.ID = ".$prmSID." ORDER BY prodName1 ASC) a GROUP BY prodid2");
									 
									
		return $query;
	}
	
	public function spSelectSinglePromoDet($database, $prmSID)
	{
		$query = $database->execute("SELECT ID prmID, CODE prmCode, Description prmDesc,
									 DATE_FORMAT(StartDate, '%m/%d/%Y') prmSDate,DATE_FORMAT(EndDate, '%m/%d/%Y') prmEDate,
									 IsPlusPlan prmPPlan, PageNum, IsForNewRecruit
									 FROM promo WHERE ID = ".$prmSID." AND promotypeid = 1");
		return $query;
	}
	
	public function spUpdatePromoEntitlementDetails($database, $promoEntitleID, $prodid, $minimum1, $savings, $pmg1, $promoEntitleIDDetails, $savings, $criteria1, $produp1)
	{
		//echo "spUpdatePromoEntitlementDetails($promoEntitleID, $prodid1, $minimum1, $savings, $pmg1, $promoEntitleIDDetails, $savings, $criteria1, $produp1)";
		if($criteria1 != 1){
			//$promoBuyinID	
			$savings = $produp1 - $minimum1;
			$query = $database->execute("update `promoentitlementdetails`
								set
									PromoEntitlementID =".$promoEntitleID.",
									ProductID = ".$prodid.",
									Quantity = 1,
									EffectivePrice = ".$minimum1.",
									Savings = ".$savings.",
									PMGID = ".$pmg1.",
									Changed = 1
								where ID = ".$promoEntitleIDDetails);
								
				/*echo "update `promoentitlementdetails`
								set
									PromoEntitlementID =".$promoEntitleID.",
									ProductID = ".$prodid.",
									Quantity = 1,
									EffectivePrice = ".$minimum1.",
									Savings = ".$savings.",
									PMGID = ".$pmg1.",
									Changed = 1
								where ID = ".$promoEntitleIDDetails ;*/
								
		}else{
		$query =  $database->execute("update `promoentitlementdetails`
								set
									PromoEntitlementID = ".$promoEntitleID.",
									ProductID = ".$prodid1.",
									Quantity = ".$minimum1.",
									EffectivePrice = 0,
									Savings = ".$produp1.",
									PMGID = ".$pmg1.",
									Changed = 1
								where ID = ".$promoEntitleIDDetails);
								
								/*echo "update `promoentitlementdetails`
								set
									PromoEntitlementID = ".$promoEntitleID.",
									ProductID = ".$prodid1.",
									Quantity = ".$minimum1.",
									EffectivePrice = 0,
									Savings = ".$produp1.",
									PMGID = ".$pmg1.",
									Changed = 1
								where ID = ".$promoEntitleIDDetails;*/

		}
		return $query;
	}
	
	public function Updatepromoentitlement($database,$criteria1, $promoBuyinID)
	{
		$query = $database->execute('update promoentitlement set type ='.$criteria1.' where PromoBuyinID ='.$promoBuyinID);
		//echo "//UPDATE PROMO ENTITLEMENT: update promoentitlement set type =".$criteria1." where PromoBuyinID =".$promoBuyinID;
		return $query;
	}
	
	public function spDeletePromo($database, $prmID)
	{
		//Promo Entitlement Details
		$query = array("SET FOREIGN_KEY_CHECKS = 0",
					   "DELETE FROM promoentitlementdetails WHERE PromoEntitlementID = (SELECT ID FROM promoentitlement WHERE PromoBuyinID = (
						SELECT ID FROM promobuyin WHERE PromoID = (SELECT ID FROM promo WHERE ID = ".$prmID.") AND LevelType = 0))",
					   "DELETE FROM promoentitlement WHERE PromoBuyinID in (
						SELECT ID FROM promobuyin WHERE PromoID = (SELECT ID FROM promo WHERE ID = ".$prmID."))",
					   "DELETE FROM promobuyin WHERE PromoID = (SELECT ID FROM promo WHERE ID = ".$prmID.")",
					   "DELETE FROM promo WHERE ID = ".$prmID);
		foreach ($query as $execute_query):
			$database->execute($execute_query);
		endforeach;
		
		return 'true'; 
		
		
	}
}
$PromoAndPricing = new tpiPromoAndPricing();

?>