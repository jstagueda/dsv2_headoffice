<?php
class tpiIncentives 
{
	public function tpiSelectIncentiveType($database)
	{
		$query = "SELECT * FROM incentivetype";
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectMechanicsType($database)
	{
		$query = "SELECT * FROM mechanicstype";
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectProductLevel($database)
	{
		$query = "SELECT * FROM productlevel";
		$result = $database->execute($query);
		return $result;
	}
	
	public function tpiSelectAllProduct($database, $ProductLevelID)
	{
		$query = "SELECT * FROM product where ProductLevelID =".$ProductLevelID;
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectProductLine($database)
	{
		$query = "SELECT * FROM product WHERE ProductLevelID = 2";
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectProductLineSelected($database, $ID)
	{
		$query = "SELECT * FROM product WHERE ID = $ID";
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectTpiCriteria($database)
	{
		$query = "SELECT * FROM criteria";
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectProductID($database, $ProductID)
	{
		$query = "SELECT * FROM product where ID = '$ProductID'";
		$result = $database->execute($query);
		return $result;
	
	}
	public function tpiSelectProductCode($database, $BRItemCode)
	{
		$query = "SELECT ID, Name FROM product where Code = '$BRItemCode'";
		$result = $database->execute($query);
		return $result;

	}

	
	public function tpiInsertIncentivesTmpBuyin($database, $BuyinSelection, $ProductID, $ProductDesc, $buyincriteria, $BRMinVal, $BuyinSetStartDate, $BuyinSetEndDate, $Changed,$session)
	{
		$Sdate = date("Y-m-d",strtotime($BuyinSetStartDate));
		$Edate = date("Y-m-d",strtotime($BuyinSetEndDate));
		
		if($buyincriteria == 1){
			$query = "Insert into tpiincentivesbuyintmp (ProductLevelID, ProductID, ProductDesc, CriteriaID, MinQty, MinAmt, StartDate, EndDate, Changed, sessionID)
						values ($BuyinSelection, $ProductID, '$ProductDesc', $buyincriteria, $BRMinVal, 0, '$Sdate', '$Edate', $Changed, $session)
						
					 ";
		}else{
			$query = "Insert into tpiincentivesbuyintmp (ProductLevelID, ProductID, ProductDesc, CriteriaID, MinQty, MinAmt, StartDate, EndDate, Changed, sessionID)
						values ($BuyinSelection, $ProductID, '$ProductDesc', $buyincriteria, 0, $BRMinVal, '$Sdate', '$Edate', $Changed, $session)
						
					  ";
		
		}
		$result = $database->execute($query);
		return $result;
	}
	public function tpiInsertIncentivesTmpBuyin1($database, $BuyinSelection, $ProductID, $ProductDesc, $buyincriteria, $BRMinVal, $BuyinSetStartDate, $BuyinSetEndDate, $Changed,$session, $activate, $Sselection, $sType)
	{
		$Sdate = date("Y-m-d",strtotime($BuyinSetStartDate));
		$Edate = date("Y-m-d",strtotime($BuyinSetEndDate));
		
		if($buyincriteria == 1){
			$query = "Insert into tpiincentivesbuyintmp (ProductLevelID, ProductID, ProductDesc, CriteriaID, MinQty, MinAmt, StartDate, EndDate, Changed, sessionID, SpecialCriteria, Type, Qty)
						values ($BuyinSelection, $ProductID, '$ProductDesc', $buyincriteria, $BRMinVal, 0, '$Sdate', '$Edate', $Changed, $session, $activate, $Sselection, $sType)
						
					 ";
		}else{
			$query = "Insert into tpiincentivesbuyintmp (ProductLevelID, ProductID, ProductDesc, CriteriaID, MinQty, MinAmt, StartDate, EndDate, Changed, sessionID, SpecialCriteria, Type, Qty)
						values ($BuyinSelection, $ProductID, '$ProductDesc', $buyincriteria, 0, $BRMinVal, '$Sdate', '$Edate', $Changed, $session, $activate,  $Sselection, $sType)
						
					  ";
		
		}
		$result = $database->execute($query);
		return $result;
	}
	public function insert_last_id($database)
	{
		$query = "SELECT LAST_INSERT_ID() insert_id";
		$result = $database->execute($query);
		return $result;
	
	}
	
	public function tpiInsertIncentivesTmpEntitlement($database, $EProductID, $EProductDesc, $EMinVal, $EntitlementCriteria, $Changed,$StartDate, $EndDate, $IBuyinID)
	{

		$Sdate = date("Y-m-d",strtotime($StartDate));
		$Edate = date("Y-m-d",strtotime($EndDate));
		
		if($EntitlementCriteria == 1){
		
		
			$query = "Insert into tpiincentivesentltmp (ProductID, ProductDesc, CriteriaID, MinQty, MinAmt , StartDate, EndDate, Changed, IBuyinID)
						values ($EProductID, '$EProductDesc', $EntitlementCriteria, $EMinVal, 0, '$Sdate', '$Edate', $Changed, $IBuyinID)";
		}else{
			$query = "Insert into tpiincentivesentltmp (ProductID, ProductDesc, CriteriaID, MinQty, MinAmt , StartDate, EndDate, Changed, IBuyinID)
						values ($EProductID, '$EProductDesc', $EntitlementCriteria, 0, $EMinVal, '$Sdate', '$Edate', $Changed, $IBuyinID)";
		
		}
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectTmpTable($database,$SESSION)
	{
			$query = "  SELECT ibt.ID BuyinID, ibt.ProductDesc BuyinProdDesc, ibt.CriteriaID BuyinCriteria, ibt.MinQty BuyinMinQty,
							   ibt.MinAmt BuyinMinAmt, ibt.StartDate BuyinStartDate,ibt.EndDate BuyinEndDate, iet.ProductDesc EntProductDesc,
							   iet.CriteriaID EntCriteria, iet.MinQty EntMinQty, iet.MinAmt EntMinAmt, iet.StartDate EntStartDate,
							   iet.EndDate EntEndDate
						FROM tpiincentivesbuyintmp ibt
						INNER JOIN tpiincentivesentltmp iet
						ON ibt.ID = iet.IBuyinID
						WHERE ibt.sessionID = $SESSION";
		$result = $database->execute($query);
		return $result;
	}
	public function tpiDeleteIncentivesPromoBuyinAndEntitlement($database, $ID)
	{
		$table = array('tpiincentivesbuyintmp'=>'ID','tpiincentivesentltmp'=>'IBuyinID');
		foreach($table as $tble => $xids){
			$queries = "DELETE FROM $tble where $xids = $ID";
			$result = $database->execute($queries);
		}
		return $result;
		
	}
	public function InsertIncentivesHeader($database,$PromoCode,$PromoDesc,$inctype,$mechtype,$startdate,$endDate,$xNonGSU,$IndirectGSU,$chkIsPlus, $session)
	{
		 
		$query = "insert into promoincentives (Code, Description, IncentiveTypeID, MechanicsTypeID, StartDate, EndDate, EnrollmentDate, LastModifiedDate, IsPlusPlan, CreatedBy)
		 values ('$PromoCode', '$PromoDesc', $inctype, $mechtype, '$startdate', '$endDate', NOW(), NOW(), $chkIsPlus, $session)";
		$result = $database->execute($query);
		return $result;
	}
	
	public function selectIncentivesPromoBuyinAndEntitlement($database, $IDHeader, $SESSION)
	{
		$query = "	SELECT a.ProductLevelID BProductLevelID,
						a.Productid BProductID, a.CriteriaID BCriteriaID,
						a.MinQty BMinQty, a.MinAmt BMinAmt, a.StartDate BStartDate,
						a.EndDate BEndDate, 
						b.ProductID EProductID, b.CriteriaID ECriteriaID, b.MinQty EMinQty, 
						b.MinAmt EMinAmt, b.StartDate EStartDate, b.EndDate EEndDate,a.PromoID
					FROM tpiincentivesbuyintmp a 
					INNER JOIN tpiincentivesentltmp b ON a.ID = b.IBuyinID
					WHERE a.sessionID = $SESSION";
		$result = $database->execute($query);
		return $result;
	}
	
	public function selectIncentivesPromoBuyinAndEntitlement1($database, $IDHeader, $SESSION)
	{
		$query = "
					SELECT a.ProductLevelID BProductLevelID,
					a.Productid BProductID, a.CriteriaID BCriteriaID,
					a.MinQty BMinQty, a.MinAmt BMinAmt, a.StartDate BStartDate,
					a.EndDate BEndDate, 
					b.ProductID EProductID, b.CriteriaID ECriteriaID, b.MinQty EMinQty, 
					b.MinAmt EMinAmt, b.StartDate EStartDate, b.EndDate EEndDate,
					c.IBuyinID SBuyinID, c.NoOfwiks sNoOfWiks, c.StartWeek1, 
					c.EndWeek1,c.StartWeek2, c.EndWeek2, c.StartWeek3, c.EndWeek3,c.StartWeek4, c.EndWeek4 
					,c.MinVal csMinVal, c.NoOfWiks,a.PromoID
					FROM tpiincentivesbuyintmp a 
					INNER JOIN tpiincentivesentltmp b ON a.ID = b.IBuyinID
					LEFT JOIN tpispecialcriteriatmp c ON a.ID = c.IBuyinID
					WHERE sessionID = $SESSION";
		$result = $database->execute($query);
		return $result;
	}
	public function IncentivesInsertPromoBuyin($database,$IDHeader,$BProductLevelID,$BProductID,$BCriteriaID,$BMinQty,$BMinAmt,$BStartDate,$BEndDate, $Btype, $BQty, $SpecialCriteria)
	{
			$query = "insert into incentivespromobuyin (PromoIncentiveID,ProductLevelID,ProductID,CriteriaID,MinQty,MinAmt,StartDate,EndDate,EnrollmentDate,LastModifiedDate, Type, Qty, SpecialCriteria)
			 values ($IDHeader,$BProductLevelID,$BProductID,$BCriteriaID,$BMinQty,$BMinAmt,'$BStartDate','$BEndDate',NOW(),NOW(), $Btype, $BQty, $SpecialCriteria)";
			$result = $database->execute($query);
			return $result;
	}
	
	
	public function IncentivesInsertPromoBuyin1($database,$IDHeader,$BProductLevelID,$BProductID,$BCriteriaID,$BMinQty,$BMinAmt,$BStartDate,$BEndDate, $Btype, $BQty)
	{
		
			$query = "insert into incentivespromobuyin (PromoIncentiveID,ProductLevelID,ProductID,CriteriaID,MinQty,MinAmt,StartDate,EndDate,EnrollmentDate,LastModifiedDate, Type, Qty, SpecialCriteria)
			 values ($IDHeader,$BProductLevelID,$BProductID,$BCriteriaID,$BMinQty,$BMinAmt,'$BStartDate','$BEndDate',NOW(),NOW(), $Btype, $BQty, 0)";
			//echo $query;
			$result = $database->execute($query);
			return $result;
	}
	
	public function IncentivesInsertPromoBuyinWithinPromo($database,$validate,$IDHeader,$BProductLevelID,$BPromoID,$BCriteriaID,$BMinQty,$BMinAmt,$BStartDate,$BEndDate, $Btype, $BQty, $SpecialCriteria)
	{
		
		if($validate == 1){
			$query = "insert into incentivespromobuyin (PromoIncentiveID,ProductLevelID,PromoID,CriteriaID,MinQty,MinAmt,StartDate,EndDate,EnrollmentDate,LastModifiedDate, Type, Qty, SpecialCriteria)
			 values ($IDHeader,$BProductLevelID,$BPromoID,$BCriteriaID,$BMinQty,$BMinAmt,'$BStartDate','$BEndDate',NOW(),NOW(), $Btype, $BQty, 0)";
			//echo $query;
			$result = $database->execute($query);
		}
		
		if($validate == 2){
			$query = "insert into incentivespromobuyin (PromoIncentiveID,ProductLevelID,PromoID,CriteriaID,MinQty,MinAmt,StartDate,EndDate,EnrollmentDate,LastModifiedDate, Type, Qty, SpecialCriteria)
			 values ($IDHeader,$BProductLevelID,$BPromoID,$BCriteriaID,$BMinQty,$BMinAmt,'$BStartDate','$BEndDate',NOW(),NOW(), $Btype, $BQty, $SpecialCriteria)";
			//echo $query."<br />";
			$result = $database->execute($query);
			return $result;
			
		}
		return $result;
		
	}
	
	public function IncentivesInsertEntitlement($database, $IncentiveBuyinID, $EProductID, $ECriteriaID, $EMinQty, $EMinAmt, $EStartDate, $EEndDate)
	{
		$query = "insert into incentivespromoentitlement (IncentivesPromoBuyinID, ProductID,CriteriaID,MinQty,MinAmt,StartDate,EndDate,EnrollmentDate,LastModifiedDate)
				  values ($IncentiveBuyinID,$EProductID,$ECriteriaID,$EMinQty,$EMinAmt,'$EStartDate','$EEndDate',NOW(),NOW())";
		//echo $query;
		$result = $database->execute($query);
		return $result;
	}
	public function InsertIncentivesPromoAvailment($database,$IDHeader,$NoCpi,$NonGSU,$IndirectGSU, $directGSU)
	{
		$query 	= "insert into incentivespromoavailemnt (PromoIncentiveID, NonGSU, InDirectGSU, NetOFCPI, DirectGSU) values ($IDHeader, $NonGSU, $IndirectGSU, $NoCpi, $directGSU)";
		//echo $query;
		$result = $database->execute($query);
		return $result;
	}
	
	public function DeleteIncentivesPromoBuyinTmp($database, $session)
	{
		$query = "DELETE FROM tpiincentivesbuyintmp WHERE sessionID = $session";
		$result = $database->execute($query);
		return $result;
	}
    public function DeleteIncentivesEntitlementTmp($database, $session)
	{
		$query = "delete from tpiincentivesentltmp WHERE IBuyinID IN (SELECT ID FROM tpiincentivesbuyintmp WHERE sessionID = $session)";
		$result = $database->execute($query);
		return $result;
	}
	public function DeleteIncentivesSpecialCriteriaTmp($database, $session)
	{
		$query = "delete from tpispecialcriteriatmp WHERE IBuyinID IN (SELECT ID FROM tpiincentivesbuyintmp WHERE sessionID = $session)";
		$result = $database->execute($query);
		return $result;
	}
	
	public function IncentivesValidateIfExistPromoCode($database, $PromoCode)
	{
		$query = "SELECT * FROM promoincentives where Code = '$PromoCode'";
		$result = $database->execute($query);
		return $result;
	
	}
	
	public function tpiInsertSpecialCriteria($database, $Swik1, $Ewik1, $Swik2, $Ewik2, $Swik3, $Ewik3, $Swik4, $Ewik4, $IBuyinID, $SminValue, $noofwiks, $noofwiksto, $session)
	{
			$query = "insert into tpispecialcriteriatmp (IBuyinID, StartWeek1, EndWeek1, StartWeek2, EndWeek2, StartWeek3, EndWeek3, StartWeek4, EndWeek4, LastModifiedDate, EnrollmentDate, MinVal, NoOfWiks,RequiredNoOfWeeksMet, sessionID)
			values ($IBuyinID, '$Swik1', '$Ewik1', '$Swik2', '$Ewik2', '$Swik3', '$Ewik3', '$Swik4', '$Ewik4', now(), now(), $SminValue, $noofwiks, $noofwiksto, $session)";
			$result = $database->execute($query);
			return $result;
	}
	
	public function tpiInsertSpecialCriteria1($database, $StartWeek1,$EndWeek1, $StartWeek2, $EndWeek2, $StartWeek3, $EndWeek3, $StartWeek4, $EndWeek4 , $IncentiveBuyinID, $csMinVal ,$NoOfWiks, $RequiredNoOfWeeksMet)
	{
			$query = "insert into incentivesspecialcriteria (IBuyinID, StartWeek1, EndWeek1, StartWeek2, EndWeek2, StartWeek3, EndWeek3, StartWeek4, EndWeek4, LastModifiedDate, EnrollmentDate, MinVal, NoOfWiks, RequiredNoOfWeeksMet)
			values ($IncentiveBuyinID,  '$StartWeek1', '$EndWeek1', '$StartWeek2', '$EndWeek2', '$StartWeek3', '$EndWeek3', '$StartWeek4', '$EndWeek4', now(), now(), $csMinVal, $NoOfWiks, $RequiredNoOfWeeksMet)";
			$result = $database->execute($query);
			return $result;
	}
	
	public function tpiDeleteIncentivesPromoSpecialCriteria($database, $ID)
	{
			$query = "DELETE FROM tpispecialcriteriatmp where IBuyinID = ".$ID;
			$result = $database->execute($query);
			return $result;
	}
	/*Promo Incentive Promo View*/
	public function spSelectIncentiveCount($database, $RPP,  $scodedesc, $sprodcode)
	{
		if ($scodedesc == '%%' && $sprodcode == '%%'){
				$query = "SELECT COUNT(DISTINCT ID) AS numrows FROM promoincentives 
				ORDER BY StartDate,Code LIMIT 0, $RPP";
		}elseif ($scodedesc <> '%%' && $sprodcode <> '%%') {
				$query =  "SELECT COUNT(DISTINCT ID) AS numrows
				FROM promoincentives
				WHERE  Code LIKE '%$sprodcode%' OR Description LIKE '%$scodedesc%'
				ORDER BY StartDate, Code";
		}elseif ($scodedesc <> '%%' && $sprodcode == '%%'){
				$query =  "select count(DISTINCT ID) as numrows
				FROM promoincentives
				WHERE 
				Code like upper('%$scodedesc%') or 
				prm.Description like upper('%$scodedesc%')
				order by prm.StartDate,prm.Code";		 
		}elseif ($scodedesc == '%%' && $sprodcode <> '%%') {
				$query = "select count(DISTINCT prm.ID) as numrows
				FROM promoincentives
				where 
				Code like upper($sprodcode) or Code like upper($sprodcode)
				order by StartDate,Code";
		}
		$result = $database->execute($query);
		return $result;
	}
	
	public function spSelectIncentives($database, $RPP,  $scodedesc, $sprodcode)
	{
		if ($scodedesc == '%%' && $sprodcode == '%%'){
				$query = "SELECT ID, Code, Description, StartDate, EndDate  FROM promoincentives 
				ORDER BY StartDate,Code LIMIT 0, $RPP";
		}elseif ($scodedesc <> '%%' && $sprodcode <> '%%') {
				$query =  "SELECT ID, Code, Description, StartDate, EndDate
				FROM promoincentives 
				WHERE  Code LIKE '%$sprodcode%' OR Description LIKE '%$scodedesc%'
				ORDER BY StartDate, Code";
		}elseif ($scodedesc <> '%%' && $sprodcode == '%%'){
				$query =  "SELECT ID, Code, Description, StartDate, EndDate 
				FROM promoincentives
				WHERE 
				Code like upper('%$scodedesc%') or 
				prm.Description like upper('%$scodedesc%')
				order by prm.StartDate,prm.Code";		 
		}elseif ($scodedesc == '%%' && $sprodcode <> '%%') {
				$query = "SELECT ID, Description, Code, sStartDate, EndDate
				FROM promoincentives
				where 
				Code like upper($sprodcode) or Code like upper($sprodcode)
				order by StartDate,Code";
		}
		$result = $database->execute($query);
		return $result;	
	}
	
	public function tpiSelectHeaderIncentives($database, $xID)
	{
		$query = "SELECT a.Code, a.Description,a.IncentiveTypeID, a.MechanicsTypeID, b.Name IncentiveType, c.Name MechanicsType, 
				  a.StartDate, a.EndDate, a.IsPlusPlan, d.NonGSU, d.InDirectGSU,d.DirectGSU, d.NetOFCPI
				  FROM promoincentives a
				  INNER JOIN incentivetype b ON a.IncentiveTypeID = b.ID
				  INNER JOIN mechanicstype c ON a.MechanicsTypeID = c.ID
				  LEFT JOIN incentivespromoavailemnt d ON a.ID = d.PromoIncentiveID
				  WHERE a.ID = ".$xID;
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectBuyinIncentives($database, $xID)
	{
		$query ="SELECT a.*,b.*, a.ID IncentivesPromoBuyinID,  c.Code PromoCode, a.ProductLevelID PLEVEL FROM incentivespromobuyin a
				 LEFT JOIN product b ON a.ProductID = b.ID
				 LEFT JOIN promo c ON c.ID =  a.PromoID
				 WHERE a.PromoIncentiveID = ".$xID;
				 
		$result = $database->execute($query);
		return $result;
	}
	public function tpiSelectEntitlementinIncentives($database, $xID)
	{
		$query = "SELECT a.*,b.*, a.ID eID  FROM incentivespromoentitlement a
				  INNER JOIN product b ON a.ProductID = b.ID		
				  WHERE a.IncentivesPromoBuyinID = $xID";
		$result = $database->execute($query);
		return $result;
	}
	
	public function tpiSelectTmpTableGetingPromo($database,$SESSION)
	{
		$query ="
				SELECT ibt.ID BuyinID, ibt.ProductDesc BuyinProdDesc, ibt.CriteriaID BuyinCriteria, ibt.MinQty BuyinMinQty,
					ibt.MinAmt BuyinMinAmt, ibt.StartDate BuyinStartDate,ibt.EndDate BuyinEndDate, iet.ProductDesc EntProductDesc,
					iet.CriteriaID EntCriteria, iet.MinQty EntMinQty, iet.MinAmt EntMinAmt, iet.StartDate EntStartDate,
					iet.EndDate EntEndDate, prm.Code PromoCode, ibt.ProductLevelID
				FROM tpiincentivesbuyintmp ibt
				LEFT JOIN tpiincentivesentltmp iet ON ibt.ID = iet.IBuyinID
				LEFT JOIN promo prm ON ibt.PromoID = prm.ID
				WHERE ibt.sessionID = $SESSION";
			$result = $database->execute($query);
			return $result;
	}
}
$tpiIncentives = new tpiIncentives();

?>