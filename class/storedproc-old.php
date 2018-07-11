<?php
class StoredProcedures {
	
	/************************** DS REPORTS  *********************************/
	public function spSelectPaymentsCA ($database, $offset, $perpage, $branchid, $dealerid, $campgnid, $flag)
	{
		$sp = "Call spSelectPaymentsCA ($offset, $perpage, $branchid, $dealerid, $campgnid, $flag);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCriticalStockOutofStockReport($database, $flag, $offset, $perpage, $warehouseid, $productlineid, $pmgid, $invstatus, $qtyfrom, $qtyto)
	{
		$sp = "Call spSelectCriticalStockOutofStockReport($flag, $offset, $perpage, $warehouseid, $productlineid, $pmgid, $invstatus, $qtyfrom, $qtyto);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectBackOrderReport($database, $flag,  $offset, $perpage, $sdate, $edate, $sbranch, $ebranch, $sdealer, $edealer, $sprod, $eprod, $sid, $eid, $grp)
	{
		$sp = "Call spSelectBackOrderReport($flag,  $offset, $perpage, '$sdate', '$edate', $sbranch, $ebranch, $sdealer, $edealer, $sprod, $eprod, $sid, $eid, $grp);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectProductAll($database)
	{
		$sp = "Call spSelectProduct();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSO($database, $flag)
	{
		$sp = "Call spSelectSO($flag);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPaymentClassification ($database, $offset, $perpage, $branchid, $fromdate, $todate, $flag)
	{
		$sp = "Call spSelectPaymentClassification ( $offset, $perpage, $branchid, '$fromdate', '$todate', $flag);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectRecruitersReport ($database, $offset, $perpage, $branchid, $sdate, $edate, $scode, $ecode, $flag)
	{		
		$sp = "Call spSelectRecruitersReport ( $offset, $perpage, $branchid, '$sdate', '$edate', $scode, $ecode, $flag);";
		$rs = $database->execute($sp);
		return $rs;

	}
	
	
	public function spSelectCustomerCode ($database)
	{
		$sp = "Call spSelectCustomerCode();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectBCEProfileReport ($database, $offset, $perpage, $branchid, $campgnid, $flag)
	{
		$sp = "Call spSelectBCEProfileReport ( $offset, $perpage, $branchid, $campgnid, $flag);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectIBMOnlineSalesAndActiveReport ($database, $offset, $perpage, $branchid, $campaignid,  $ibmcode, $ibmfrom, $ibmto, $flag)
	{
		$sp = "Call spSelectIBMOnlineSalesAndActiveReport ( $offset, $perpage, $branchid, $campaignid, $ibmcode, $ibmfrom, $ibmto,  $flag);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectTPIPMG ($database)
	{
		$sp = "Call spSelectTPIPMG();";
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectCustomerDGSReport ($database, $offset, $perpage, $branchid, $campaignid, $flag)
	{
	 	$sp = "Call spSelectCustomerDGSReport ( $offset, $perpage, $branchid, $campaignid, $flag);";
		$rs = $database->execute($sp);
		return $rs;
	 }
	
	public function spSelectIBMCumPerReport ($database, $offset, $perpage, $branchid, $sibmid, $eibmid, $campaignid, $flag)
	{
		$sp = "Call spSelectIBMCumPerReport2 ( $offset, $perpage, $branchid, $sibmid ,$eibmid ,$campaignid, $flag);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectIBMRange($database)
	{
		$sp = "Call spSelectIBMRange();";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectBranchByName($database)
	{
		$sp = "Call spSelectBranchByName();";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectIBDBadDebts($database, $flag, $offset, $perpage, $branchid, $fromdate, $todate)
	{
		$sp = "Call spSelectIBDBadDebts($flag, $offset, $perpage, $branchid, '$fromdate',  '$todate');";
		$rs = $database->execute($sp);
		return $rs;			
	}
	
	public function spSelectIBMCollectionDue($database, $offset, $perpage, $branchid, $ibmfrom, $ibmto, $fromdate, $todate, $vID)
	{
		$sp = "Call spSelectIBMCollectionDue($offset, $perpage, $branchid, $ibmfrom, $ibmto, '$fromdate',  '$todate', $vID);";
		$rs = $database->execute($sp);
		return $rs;					
	}
	public function spSelectIBMCollectionTotalAmountDue($database)
	{
		$sp = "Call spSelectIBMCollectionTotalAmountDue();";
		$rs = $database->execute($sp);
		return $rs;							
	}
	
	public function spSelectCustomerDetailsNew($database)
	{
		$sp = "Call spSelectCustomerDetailsNew();";
		$rs = $database->execute($sp);
		return $rs;					
	}
	
	/************************** LEADER LIST RELATED  *********************************/

	public function spLLDeleteTempTables($database)
	{
		$sp = "truncate table `tpi_tempaop`;";
	  $database->execute($sp);
	  $sp = "truncate table `tpi_temprollingforecast`;";
	  $database->execute($sp);
	  $sp = "truncate table `tpi_tempconsolidatedrollingforecast`;";
	  $database->execute($sp);
	  $sp = "truncate table `tpi_tempdummycost`;";
	  $database->execute($sp);
	}
	
	public function spLLGetCampaigns($database)
	{
		$sp = "select ID,Code from campaign order by StartDate";
	  return $database->execute($sp);
	}
	
	public function spLLInsertTempAOP($database,$prodLineCode,$prodLineDescription,$campaign,$gmargin,$gprofit,$aopnet,$aopunits)
	{
		$sp = "call spLLInsertTempAOP('$prodLineCode','$prodLineDescription','$campaign',$gmargin,$gprofit,$aopnet,$aopunits)";
	  $database->execute($sp);
	}
	
	public function spLLUploadAOP($database)
	{
		$sp = "call spLLUploadAOP()";
	  $database->execute($sp);
	}
	
	public function spLLGetUploadedAOPCount($database)
	{
		$sp = "call spLLGetUploadedAOPCount()";
	  $rs = $database->execute($sp);
	  return $rs;
	}
	
	public function spLLGetUnuploadedAOPRows($database)
	{
		$sp = "call spLLGetUnuploadedAOPRows()";
	  $rs = $database->execute($sp);
	  return $rs;
	}
	
	public function spLLCheckCampaignIfExisting($database,$campaign)
	{
		$sp = "call spLLCheckCampaignIfExisting('$campaign')";
	  $rs = $database->execute($sp);
	  $row = $rs->fetch_object();
	  if ($row->a==0){
	  	return false;
	  }
	  else {
	  	return true;
	  }
	}
	
	public function spLLInsertTempRollingForecast($database,$prodLineCode,$prodLineDescription,$campaignID,$promoCode,$rfunits,$type)
	{
		$sp = "call spLLInsertTempRollingForecast('$prodLineCode','$prodLineDescription',$campaignID,'$promoCode',$rfunits,$type)";
	  $database->execute($sp);
	}
	
	public function spLLValidateRollingForecast($database)
	{
		$sp = "call spLLValidateRollingForecast()";
	  $database->execute($sp);
	}
	
  public function spLLUploadRollingForecast($database)
	{
		// Upload rows to tpi_rollingforecast
		$query = "insert into `tpi_rollingforecast` " .
							"select t.ProductID,t.CampaignID,t.PromoID,t.RFUnits,t.Type " .
							"from `tpi_temprollingforecast` t " .
							"left join `tpi_rollingforecast` aop on t.ProductID=aop.ProductID and t.CampaignID=aop.CampaignID and t.PromoID=aop.PromoID and t.Type=aop.Type " .
							"where aop.ProductID is null " .
							"and t.Uploaded is null;";
		$database->execute($query);
		
		// and mark them as uploaded
		$query = "update `tpi_temprollingforecast` " .
							"set Uploaded=1 where Uploaded is null;	";
	  $database->execute($query);
	}
	
  public function spLLCheckDuplicateTempRollingForecast($database)
	{
		$query = "select ProdLineCode,PromoCode,Type,count(*) a " .
					 "from tpi_temprollingforecast " .
					 "where ProductID is not null and PromoID is not null " .
					 "group by 1,2,3 " .
					 "having count(*) >1;";
		$rs = $database->execute($query);
		return $rs;
	}
	
	public function spLLGetUploadedRollingForecastCount($database)
	{
		$sp = "call spLLGetUploadedRollingForecastCount()";
	  $rs = $database->execute($sp);
	  return $rs;
	}
	
	public function spLLGetUnuploadedRollingForecastRows($database)
	{
		$sp = "call spLLGetUnuploadedRollingForecastRows()";
	  $rs = $database->execute($sp);
	  return $rs;
	}
	
	public function spLLInsertTempConsolidatedRollingForecast($database,$prodLineCode,$prodLineDescription,$campaign,$rfunits)
	{
		$sp = "call spLLInsertTempConsolidatedRollingForecast('$prodLineCode','$prodLineDescription','$campaign',$rfunits)";
	  $database->execute($sp);
	}
	
	public function spLLUploadConsolidatedRollingForecast($database)
	{
		$sp = "call spLLUploadConsolidatedRollingForecast()";
	  $database->execute($sp);
	}
	
	public function spLLValidateTempConsolidatedRollingForecast($database)
	{
		// Update ProductID column, matching it from existing product rows
		$query = "update `tpi_tempconsolidatedrollingforecast` t " .
					"inner join `product` p on p.Code=t.ProdLineCode " .
					"set t.ProductID=p.ID;";
		$database->execute($query);
		
		// If there are no matching products, mark rows as unuploaded and add remarks
		$query = "update `tpi_tempconsolidatedrollingforecast` t " .
					"set Uploaded=0, Remarks = concat('Product Line Code ''',ProdLineCode,''' did not match any existing product in the database') " .
					"where ProductID is null " .
					"and Remarks is null;";
		$database->execute($query);
	}
	
	public function spLLValidateAndUploadTempConsolidatedRollingForecast2($database,$campaign,$rfColumnName)
	{
		// For each given campaign, check if they are already existing
		// in the database
		$query = "update " .
					"`tpi_tempconsolidatedrollingforecast` t " .
					"inner join `tpi_consolidatedrollingforecast` aop on t.ProductID=aop.ProductID, campaign c " .
					"set t.Uploaded=0, Remarks = concat('Product Code ''', ProdLineCode, ''' and Campaign ''', c.Code, ''' already exists in the database') " .
					"where c.Code='$campaign' and c.ID=aop.CampaignID " .
					"and $rfColumnName>0;";
		$database->execute($query);
		
		$query = "insert into `tpi_consolidatedrollingforecast` " .
					"select distinct t.ProductID, c.ID, t.$rfColumnName from  " .
					"`tpi_tempconsolidatedrollingforecast` t, `campaign` c " .
					"where c.Code='$campaign' " .
					"and t.ProductID is not null " .
					"and t.$rfColumnName>0 " .
					"and t.Uploaded is null;";
		$database->execute($query);
	}
	
	public function spLLCheckDuplicateTempConsolidatedRollingForecast($database)
	{
		$query = "select ProdLineCode,count(*) a " .
					 "from tpi_tempconsolidatedrollingforecast " .
					 "where ProductID is not null " .
					 "group by 1 " .
					 "having count(*) >1;";
		$rs = $database->execute($query);
		return $rs;
	}
	
	public function spLLMarkUploadedConsolidatedRollingForecastAsUploaded($database)
	{
		$query = "update `tpi_tempconsolidatedrollingforecast` " .
					"set Uploaded=1 where Uploaded is null;";	
		$database->execute($query);
	}
	
	public function spLLGetUploadedConsolidatedRollingForecastCount($database)
	{
		$sp = "call spLLGetUploadedConsolidatedRollingForecastCount()";
	  $rs = $database->execute($sp);
	  return $rs;
	}
	
	public function spLLGetUnuploadedConsolidatedRollingForecastRows($database)
	{
		$sp = "call spLLGetUnuploadedConsolidatedRollingForecastRows()";
	  $rs = $database->execute($sp);
	  return $rs;
	}	
	public function spLLInsertTempDummyCost($database,$prodLineCode,$prodLineDescription,$dummyCost,$startDate,$endDate)
	{
		if ($endDate=="")
		{
			$endDate = "null";
		}
		else 
		{
			$endDate = "'$endDate'";
		}
		$sp = "call spLLInsertTempDummyCost('$prodLineCode','$prodLineDescription',$dummyCost,'$startDate',$endDate)";
	  $database->execute($sp);
	}
	
	public function spLLUploadDummyCost($database)
	{
		$sp = "call spLLUploadDummyCost()";
	  $database->execute($sp);
	}
	
	public function spLLGetUploadedDummyCostCount($database)
	{
		$sp = "call spLLGetUploadedDummyCostCount()";
	  $rs = $database->execute($sp);
	  return $rs;
	}
	
	public function spLLGetUnuploadedDummyCostRows($database)
	{
		$sp = "call spLLGetUnuploadedDummyCostRows()";
	  $rs = $database->execute($sp);
	  return $rs;
	}
	
	/************************** DISPLAY STOREDPROC *********************************/
	public function spSelectInventoryInRHODetailsbyID ($database, $txnid)
	{

		$sp = "Call spSelectInventoryInRHODetailsbyID($txnid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
											
	public function spSelectDealerTransfer ($database, $id, $fromibmid, $toibmid, $ibmname)
	{				
		$sp = "Call spSelectDealerTransfer($id, $fromibmid, $toibmid, '$ibmname');";
	    $rs = $database->execute($sp);
	    return $rs;		
	}

	public function spSelectDealerTerminateByID ($database, $id)
	{
		$sp = "Call spSelectDealerTerminateByID('$id');";
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spTransferDealer ($database, $id, $ibimid, $createdid)
	{
					
		$sp = "Call spTransferDealer($id, $ibimid, $createdid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectMaritalStatus ($database, $id)
	{
		$sp = "Call spSelectMaritalStatus($id);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectEducationalAttainment ($database, $id)
	{
		$sp = "Call spSelectEducationalAttainment($id);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectLengthStay ($database, $id)
	{
		$sp = "Call spSelectLengthStay($id);";
		$rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectDirectSellExp ($database, $id)
	{
		$sp = "Call spSelectDirectSellExp($id);";
		$rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectEmploymentStatus ($database, $id)
	{	
		$sp = "Call spSelectEmploymentStatus($id);";
		$rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectDirectSellComp ($database, $id)
	{
		$sp = "Call spSelectDirectSellComp($id);";
		$rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectProvince ($database, $id)
	{				
		$sp = "Call spSelectProvince($id);";
		$rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectTownCity ($database, $id, $areaid)
	{					
		$sp = "Call spSelectTownCity($id, $areaid);";
		$rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectBarangay ($database, $id, $areaid)
	{
		$sp = "Call spSelectBarangay($id, $areaid);";
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectRecruiter ($database, $accountno, $id)
	{	
		$sp = "Call spSelectRecruiter('$accountno', $id);";
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectZone ($database)
	{	
		$sp = "Call spSelectZone();";
		$rs = $database->execute($sp);
	    return $rs;
	}
	public function spSelectIBMNetwork ($database)
	{			
		$sp = "Call spSelectIBMNetwork();";
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectCreditTerm ($database)
	{		
		$sp = "Call spSelectCreditTerm();";
		$rs = $database->execute($sp);
	    return $rs;
	}	
	public function spSelectInActiveCustomer ($database, $custID, $ibm, $dealer, $start, $end)
	{	
		$sp = "Call spSelectInActiveCustomer($custID, '$ibm', '$dealer', $start, $end);";
		$rs = $database->execute($sp);
	    return $rs;
	}
        
        public function spSelectGetTotalInActiveCustomer($database, $ibm, $dealer){
            $sp = "Call spSelectGetTotalInActiveCustomer('$ibm', '$dealer');";
            $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectProduct ($database, $prodID, $search, $pricelistID)
	{	
		$sp = "Call spSelectProduct($prodID,'%$search%', $pricelistID);";
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectProductDM ($database, $prodID, $search)
	{
		$sp = "Call spSelectProductDM($prodID,'%$search%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectProdType ($database, $ptID, $search)
	{					
		$sp = "Call spSelectProdType($ptID,'%$search%');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectOutletType ($database,$otid, $search)
	{
			
		$sp = "Call spSelectOutletType($otid,'%$search%');";			
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	/*public function spSelectCustSelPaymentTerms ()
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spSelectCustSelPaymentTerms();";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}*/
	
	
	
	/*public function spSelectCustSelSalesman ()
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
		/*if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spSelectCustSelSalesman();";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}*/
	
	/*public function spSelectCustSelOutletType ()
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spSelectCustSelOutletType();";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}*/
	
	public function spSelectProdSelProdType ($database)
	{

		$sp = "Call spSelectProdSelProdType();";		
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	/*public function spSelectProdSelProdClass ()
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
		/*if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spSelectProdSelProdClass();";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}*/
	
	/*public function spSelectProdSelCategory ()
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spSelectProdSelCategory();";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	/*public function spSelectProdSelBrand ()
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spSelectProdSelBrand();";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}*/
	
	public function spSelectProdUOM ($database)
	{
		$sp = "Call spSelectProdUOM();";		
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectAdjustmentByID ($database, $v_tid)
	{
		$sp = "Call spSelectAdjustmentByID($v_tid);";				
 		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectUserType ($database, $utid)
	{
		$sp = "Call spSelectUserType($utid);";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectEmployeeType($database)
	{
		$sp = "Call spSelectEmployeeType();";		
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectDepartment($database, $dID, $search)
	{   

		$sp = "Call spSelectDepartment($dID, '%$search%');";
	    $rs = $database->execute($sp);
	    return $rs;
		
	}

	public function spSelectEmployee($database,$empid, $search)
	{
		$sp = "Call spSelectEmployee($empid, '%$search%');";
	    $rs = $database->execute($sp);
	    return $rs;	
		
	}
	
	public function spSelectUOM ($database)
	{
		$sp = "Call spSelectUOM();";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectProductList ($database, $vSearch, $warehouseid)
	{
		$sp = "Call spSelectProductList('%$vSearch%', $warehouseid);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	
	public function spSelectModule($database)
	{
		$sp = "Call spSelectModule();";		
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectSubModuleByModuleID ($database, $modid)
	{
		$sp = "Call spSelectSubModuleByModuleID($modid);";		
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectModControlDetails ($database, $subid, $mcid)
	{
		$sp = "Call spSelectModControlDetails ($subid, $mcid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectUserEmployee ($database, $id)
	{
		$sp = "Call spSelectUserEmployee ($id);";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectEmpUserType($database)
	{
		$sp = "Call spSelectEmpUserType();";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectEmployeeList($database, $empid,$search)
	{
		$sp = "Call spSelectEmployeeList($empid,'%$search%');";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectUserTypeModuleControl ($database, $id)
	{

		$sp = "Call spSelectUserTypeModuleControl ($id);";
		$rs = $database->execute($sp);		
		return $rs;
		
	}
	
	public function spSelectTermsType ($database)
	{			
		$sp = "Call spSelectTermsType();";
		$rs = $database->execute($sp);	
		return $rs;	
	}
	
	public function spSelectPaymentTerms ($database, $id, $search)
	{			
		$sp = "Call spSelectPaymentTerms($id, '%$search%');";
        $rs = $database->execute($sp);	
		return $rs;	
	}
	
	public function spSelectUserModule ($database, $userid)
	{
		$sp = "Call spSelectUserModule($userid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectSubModule ($database, $userid, $pageid)
	{
		$sp = "Call spSelectSubModule($userid, $pageid);";
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spSelectSubModuleModuleControl ($database, $userid, $pageid)
	{
		$sp = "Call spSelectSubModuleModuleControl($userid, $pageid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectModuleControl ($database, $userid, $pageid)
	{
		
		$sp = "Call spSelectModuleControl($userid, $pageid);";
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spSelectAdjDetailsByID ($database, $v_tid)
	{
		$sp = "Call spSelectAdjDetailsByID($v_tid);";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spCheckSOH ($database, $invid)
	{		
		$sp = "Call spCheckSOH($invid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryIn ($database, $v_offset, $v_perpage, $vSearch)
	{
		$sp = "Call spSelectInventoryIn($v_offset, $v_perpage, '%$vSearch%');";	
		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryInCount ($database,$vSearch)
	{
		$sp = "Call spSelectInventoryInCount('%$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectAccessPage ($database, $userid, $pageid)
	{
		$sp = "Call spSelectAccessPage($userid, $pageid);";
		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryInByID ($database, $v_tid)
	{

		$sp = "Call spSelectInventoryInByID($v_tid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInvInDetailsByID ($database, $v_tid)
	{

		$sp = "Call spSelectInvInDetailsByID($v_tid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryOut ($database, $v_offset, $v_perpage, $vSearch)
	{

		$sp = "Call spSelectInventoryOut($v_offset, $v_perpage, '%$vSearch%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryOutCount ($database, $vSearch)
	{
		$sp = "Call spSelectInventoryOutCount('%$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInvOutDetailsByID ($database, $v_tid)
	{
		$sp = "Call spSelectInvOutDetailsByID($v_tid);";
		$rs = $database->execute($sp);		
		return $rs;
	}
	
	public function spSelectInventoryOutByID ($database, $v_tid)
	{
		$sp = "Call spSelectInventoryOutByID($v_tid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectDesWarehouse ($database, $wid)
	{

		$sp = "Call spSelectDesWarehouse ($wid);";	
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectStocks ($database, $offset, $RPP, $vSearch, $warehouseid)
	{
		$sp = "Call spSelectStocks ($offset, $RPP, '$vSearch%', $warehouseid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectStocksCount ($database, $vSearch, $warehouseid)
	{
		$sp = "Call spSelectStocksCount ('$vSearch%', $warehouseid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryInType($database)
	{
		$sp = "Call spSelectInventoryInType();";		
		$rs = $database->execute($sp);
		return $rs;
		
	}
	
	public function spSelectInventoryOutType($database)
	{

		$sp = "Call spSelectInventoryOutType();";		
		$rs = $database->execute($sp);
		return $rs;
		
	}
	
	public function spSelectInventoryDetails($database, $prodid, $wareid)
	{
		$sp = "Call spSelectInventoryDetails($prodid, $wareid);";		
		$rs = $database->execute($sp);
		return $rs;		
	}

	public function spSelectStockLog($database, $prodid, $wareid, $year, $month)
	{
		$sp = "Call spSelectStockLog($prodid, $wareid, '$year', '$month');";
		
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectPriceList($database, $ptid, $search)
	{
		$sp = "Call spSelectPriceList($ptid, '%$search%');";
		$rs = $database->execute($sp);
		return $rs;
		
	}
	
	public function spSelectSalesInvoice ($database, $v_offset, $v_perpage, $vSearch)
	{

		$sp = "Call spSelectSalesInvoice($v_offset, $v_perpage, '%$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCustomerIGSSIPaging ($database ,$vSearch,$custid)
	{

				
		$sp = "Call spSelectCustomerIGSSIPaging('$vSearch%',$custid);";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSalesInvoiceCount ($database,$vSearch)
	{
		$sp = "Call spSelectSalesInvoiceCount('%$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSalesInvoiceByID ($database,$v_tid)
	{		
		$sp = "Call spSelectSalesInvoiceByID($v_tid);";	
		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSalesInvoiceDetailsByID($database,$v_tid)
	{
		$sp = "Call spSelectSalesInvoiceDetailsByID($v_tid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spGetQtyByMultiplier ($database, $v_productid, $v_uomid, $v_qty)
	{
		$sp = "Call spGetQtyByMultiplier($v_productid, $v_uomid, $v_qty)";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spSelectUnitPriceByUOM ($v_productid, $v_uomid, $v_pricelistid)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
   /*		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spSelectUnitPriceByUOM($v_productid, $v_uomid, $v_pricelistid)";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}*/
	
	/*public function spGetMultiplier ($v_productid, $v_uomid)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spGetMultiplier($v_productid, $v_uomid)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	public function spSelectInventory ($database, $v_warehouseid, $v_inventoryid, $v_productid)
	{		
		$sp = "Call spSelectInventory($v_warehouseid, $v_inventoryid, $v_productid)";//echo $sp; exit;			
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	/*public function spSelectInventoryBalance ($v_productid, $v_warehouseid)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME); */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spSelectInventoryBalance($v_productid, $v_warehouseid)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	public function spGetMaxID($database, $txnid,$dbtable)
	{
		$sp = "Call spGetMaxID($txnid,'$dbtable');";				
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectDRCount($database, $v_search)
	{
		$sp = "Call spSelectDRCount('%$v_search%');";			
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectDR ($database, $v_offset, $v_perpage, $vSearch)
	{
		$sp = "Call spSelectDR($v_offset, $v_perpage, '%$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectTerms ($database)
	{
		$sp = "Call spSelectTerms ();";	
		$rs = $database->execute($sp);
		return $rs;
		
	}
	
	public function spSelectSalesman ($database)
	{
		$sp = "Call spSelectSalesman ();";	
		$rs = $database->execute($sp);
		return $rs;
		
	}
		
	public function spSelectDRByID ($database, $v_tid)
	{
		$sp = "Call spSelectDRByID($v_tid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectDRDetailsByID ($database, $v_tid)
	{
		$sp = "Call spSelectDRDetailsByID($v_tid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSOCount($database, $v_search, $v_fromdate, $v_todate)
	{
		$sp = "Call spSelectSOCount('%$v_search%', '$v_fromdate', '$v_todate');";			
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectSalesOrder ($database, $v_offset, $v_perpage, $vSearch, $vfromdate, $vtodate)
	{
		$sp = "Call spSelectSalesOrder($v_offset, $v_perpage, '%$vSearch%', '$vfromdate', '$vtodate')";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	/*public function spSelectCustDiscount ($custid)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spSelectCustDiscount($custid)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	public function spSelectSOHeaderByID ($database,$v_txnid)
	{		
		$sp = "Call spSelectSOHeaderByID($v_txnid)";
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSODetailsByID ($database, $v_txnid,$statid)
	{
		
		$sp = "Call spSelectSODetailsByID($v_txnid,$statid)";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingCustomer ($database, $custid, $fname, $lname, $mname, $bday)
	{	
		$sp = "Call spSelectExistingCustomer($custid, '$fname', '$lname', '$mname', '$bday')";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectValuationReport($database, $offset, $RPP, $vSearch, $warehouseid,$location,$pmgid,$plid,$dteAsOf)
	{	
		$sp = "Call spSelectValuationReport($offset, $RPP,'%$vSearch%', $warehouseid,$location,$pmgid,$plid,'$dteAsOf')";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectValuationReportCount ($database, $vSearch, $warehouseid,$location,$pmgid,$plid,$dteAsOf)
	{	
		$sp = "Call spSelectValuationReportCount('%$vSearch%', $warehouseid,$location,$pmgid,$plid,'$dteAsOf')";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectProductLineByID ($database, $pid)
	{	
		$sp = "Call spSelectProductLineByID($pid)";	

		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectPMGByID ($database, $pmgid)
	{	
		$sp = "Call spSelectPMGByID($pmgid)";	
		$rs = $database->execute($sp);
		return $rs;	
	}

	
	/************************** INSERT STOREDPROC *********************************/

	public function spInsertPriceList($database, $code,$name,$markup,$discount1,$discount2,$discount3,$refid)
	{
		
		$sp = "Call spInsertPriceList('$code','$name',$markup,$discount1,$discount2,$discount3,$refid);";
		$rs = $database->execute($sp);
		return $rs;	
		
	}
	
	public function spInsertStockLog($database,$transactionid, $remarks, $stocklogtypeid)
	{
		
		$sp = "Call spInsertStockLog($transactionid, '$remarks', $stocklogtypeid);";
		$rs = $database->execute($sp);
		return $rs;	
		
	}


	public function spInsertAdjustment($database, $refid, $moveid, $documentno, $warehouseid, $trandate, $remarks, $createdby)
	{
		$sp = "Call spInsertAdjustment('$refid', $moveid, '$documentno', $warehouseid, '$trandate', '$remarks', '$createdby');"; 
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertAdjustmentDetails($database, $adjid, $prodid, $unittypeid, $prevbal, $createdqty, $resid)
	{
		$sp = "Call spInsertAdjustmentDetails($adjid, $prodid, $unittypeid, $prevbal, $createdqty, $resid);"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spInsertInventoryCount($database, $documentno, $txndate, $warehouseid, $classid, $itemtypeid, $totalqty, $remarks, $prepearedby, $userid, $createid)
	{
		$sp = "Call spInsertInventoryCount('$documentno', '$txndate', $warehouseid, $classid, $itemtypeid,  $totalqty, '$remarks', '$prepearedby', $userid, '$createid');";
		$rs = $database->execute($sp);
		return $rs;
		
	}

	public function spInsertTransfer($database, $movementTypeID, $documentNo, $fromWarehouseID, $toWarehouseID, $transactionDate, $remarks, $createdBy, $branchID)
	{	
		$sp = "Call spInsertTransfer($movementTypeID, '$documentNo', $fromWarehouseID, $toWarehouseID, '$transactionDate', '$remarks', $createdBy, $branchID)";		               
		$rs = $database->execute($sp);
		return $rs;
	}
	
	
   public function spInsertTransferDetails($database, $inventoryTransferID, $productId, $unitTypeId, $prevBalance, $createdQty, $reasonID)
   {
   		$sp = "Call spInsertTransferDetails($inventoryTransferID, $productId, $unitTypeId, $prevBalance, $createdQty, $reasonID)";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spInsertInventoryIn($database, $documentno, $txndate, $siid, $invintypeid, $warehouseid, $returntypeid, $reasonid, $totalqty, $remarks, $preparedby, $userid, $createid)
	{

		$sp = "Call spInsertInventoryIn('$documentno', '$txndate', $siid, $invintypeid, $warehouseid, $returntypeid, $reasonid, $totalqty, '$remarks', '$preparedby', $userid, '$createid');";		
		$rs = $database->execute($sp);
		return $rs;	
		
	}
	
	public function spInsertInventoryOut($database, $documentno, $txndate, $invouttypeid, $warehouseid, $totalqty, $remarks, $prepearedby, $userid, $createid)
	{
		$sp = "Call spInsertInventoryOut('$documentno', '$txndate', $invouttypeid, $warehouseid, $totalqty, '$remarks', '$prepearedby', $userid, '$createid');";		
		$rs = $database->execute($sp);
		return $rs;
		
	}

	public function spInsertTmpAdjustmentDetails($database, $userid, $createsessionid, $inventoryid, $uomid, $qty, $reason, $sort)
	{
		$sp = "Call spInsertTmpAdjustmentDetails($userid, '$createsessionid', $inventoryid, $uomid, $qty, '$reason', $sort);";		
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spInsertTmpInventoryCountDetails($database, $userid, $createsessionid, $inventoryid, $uomid, $qty, $reason)
	{
		$sp = "Call spInsertTmpInventoryCountDetails($userid, '$createsessionid', $inventoryid, $uomid, $qty, '$reason');";		
		$rs = $database->execute($sp);
		return $rs;	
		
	}

	public function spInsertTmpTransferDetails($database, $userid, $createsessionid, $inventoryid, $uomid, $qty, $reason, $sort)
	{

		$sp = "Call spInsertTmpTransferDetails($userid, '$createsessionid', $inventoryid, $uomid, $qty, '$reason', $sort);";		
		$rs = $database->execute($sp);
		return $rs;	
		
	}

	public function spInsertTmpInventoryInDetails($database, $userid, $createsessionid, $inventoryid, $uomid, $qty, $reason, $sort, $multiplier, $prodid_si, $uomid_si, $unitprice_si, $qty_si)
	{

		$sp = "Call spInsertTmpInventoryInDetails($userid, '$createsessionid', $inventoryid, $uomid, $qty, '$reason', $sort, $multiplier, $prodid_si, $uomid_si, $unitprice_si, $qty_si);";		
		$rs = $database->execute($sp);
		return $rs;
		
	}
	
	public function spInsertTmpInventoryOutDetails($database,$userid, $createsessionid, $inventoryid, $uomid, $qty, $reason, $sort)
	{

		$sp = "Call spInsertTmpInventoryOutDetails($userid, '$createsessionid', $inventoryid, $uomid, $qty, '$reason', $sort);";		
		$rs = $database->execute($sp);
		return $rs;
		
	}
	
	public function spInsertCustomer($database, $code, $name, $address, $txndate, $empID, $otID, $ptID)
	{

		$sp = "Call spInsertCustomer('$code', '$name', '$address', '$txndate', $empID, $otID, $ptID);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertWarehouse($database,$code, $name)
	{
		$sp = "Call spInsertWarehouse('$code', '$name');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spInsertProduct($pCode, $pName, $pShrtName, $pProdType, $pCat, $pBrand, $pProdCls, $pUOM, $pStatus, $pUCost)
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}


		$sp = "Call spInsertProduct('$pCode', '$pName', '$pShrtName', $pProdType, $pCat, $pBrand, $pProdCls, $pUOM, $pStatus, $pUCost);";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	
	public function spInsertProductClass($database, $code, $name, $description)
	{
		$sp = "Call spInsertProductClass('$code', '$name', '$description');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertUserType($database, $code, $name)
	{
		$sp = "Call spInsertUserType('$code', '$name');";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertEmployee($database, $code, $lname, $fname, $mname, $bday, $dhired, $etid, $deptid, $posid, $brid)
	{
     	$sp = "Call spInsertEmployee('$code', '$lname', '$fname', '$mname', '$bday', '$dhired', $etid, $deptid, $posid, $brid);";
	    $rs = $database->execute($sp);
	    return $rs;
	}	
	
	public function spInsertBrand($database, $code,$name,$desc)
	{
		$sp = "Call spInsertBrand('$code', '$name', '$desc');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spInsertCategory($database, $code,$name,$desc)
	{
		$sp = "Call spInsertCategory('$code', '$name', '$desc');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spInsertProdType($database, $code,$name,$desc)
	{

		$sp = "Call spInsertProdType('$code', '$name', '$desc');";
	    $rs = $database->execute($sp);
	    return $rs;
	}	
	
	public function spInsertDepartment($database,$code,$name,$deplvlid,$parentid,$lt,$rt)
	{

		$sp = "Call spInsertDepartment('$code', '$name', $deplvlid, $parentid, $lt, $rt);";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spInsertOutletType($database, $code,$name)
	{
		$sp = "Call spInsertOutletType('$code', '$name');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spInsertModuleControl($database, $code, $modid, $submodid, $name, $description, $pageno)
	{
		$sp = "Call spInsertModuleControl('$code', $modid, $submodid, '$name', '$description', '$pageno');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertUser($database, $username, $loginname, $password, $employeeid, $usertypeid)
	{
		$sp = "Call spInsertUser('$username', '$loginname', '$password', $employeeid, $usertypeid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertUserTypeModControl($database, $utid, $mcid)
	{
		$sp = "Call spInsertUserTypeModControl($utid, $mcid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertPaymentTerms($database, $code,$name,$desc,$duration,$ttid)
	{
		$sp = "Call spInsertPaymentTerms('$code', '$name', '$desc', '$duration', $ttid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spInsertSalesOrder ($v_DocNo, $v_TxnDate, $v_DelDate, $v_CustomerID, $v_SalesmanID,$v_WarehouseID,$v_TermsID,$v_TotalQty,$v_TotalGrossAmt,$v_TotalNetAmt,$v_VatAmt,$v_Discount1, $v_Discount2, $v_Discount, $v_Remarks)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spInsertSalesOrder('$v_DocNo', '$v_TxnDate', '$v_DelDate', $v_CustomerID, $v_SalesmanID,$v_WarehouseID,$v_TermsID,$v_TotalQty,$v_TotalGrossAmt,$v_TotalNetAmt,$v_VatAmt,$v_Discount1, $v_Discount2, $v_Discount, '$v_Remarks')";					
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	public function spInsertInitialStockLog ($v_productid, $v_inventoryid, $v_txndate, $v_reftxnid, $v_refno, $v_movementtypeid, $v_qtyin, $v_qtyout)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spInsertInitialStockLog($v_productid, $v_inventoryid, $v_txndate, $v_reftxnid, $v_refno, $v_movementtypeid, $v_qtyin, $v_qtyout)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}	
		
	
	
	public function spInsertSI ($database, $v_DocNo, $v_customerid, $v_reftxnid, $v_termsid, $v_salesmanid, $v_totalqty, $v_totalgrossamt, $v_totaldiscount, $v_ispercentdisc, $v_vatamt, $v_sidisc, $v_ispercentsi, $v_ocharges, $v_totalnetamt, $v_remarks, $v_userid, $v_txndate, $v_effectivitydate)
	{
				
		$sp = "Call spInsertSI('$v_DocNo', $v_customerid, $v_reftxnid, $v_termsid, $v_salesmanid, $v_totalqty, $v_totalgrossamt, $v_totaldiscount, $v_ispercentdisc, $v_vatamt, $v_sidisc, $v_ispercentsi, $v_ocharges, $v_totalnetamt, '$v_remarks', $v_userid, '$v_txndate', '$v_effectivitydate')";
		$rs = $database->execute($sp);
		return $rs;
	}	

	public function spInsertInventoryBalance ($v_warehouseid, $v_classid, $v_productid, $v_qty, $v_unitcost)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
				
		$sp = "Call spInsertInventoryBalance($v_warehouseid, $v_classid, $v_productid, $v_qty, $v_unitcost)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}	
	
	public function spInsertInventory ($v_warehouseid, $v_productid, $v_batchno, $v_lotno, $v_SOH)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}	
			
		$sp = "Call spInsertInventory($v_warehouseid, $v_productid, $v_batchno, $v_lotno, $v_SOH)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	
	/************************** UPDATE STOREDPROC *********************************/
	
	public function spUpdatePriceList($database, $id, $code, $name, $markup, $discount1, $discount2, $discount3, $refid)
	{

		$sp = "Call spUpdatePriceList($id, '$code', '$name', $markup, $discount1, $discount2, $discount3, $refid);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateCustomer($database,$id, $code, $name, $address, $enrolldate, $empID, $otID, $termsId)
	{

		$sp = "Call spUpdateCustomer($id, '$code', '$name', '$address', '$enrolldate', $empID, $otID, $termsId);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateDealer($database, $id, $lname, $fname, $mname, $nickname, $telno, $mobileno, $address, 
								$zipcode , $class, $dealertype, $gsutype, $ibmno, $ibmname, $zone, $lstay, 
								$marital, $educ, $directsellexp, $empstatus, $directsellcomp, $remarks,
								$credittermid, $character, $capacity, $capital, $condition, $calculatedcl,
								$recommendedcl, $approvedcl, $recruiteraccount, $tin, $ibmcode, $areaid, $igscode, $bday, $pdaid, $recruiter_mobileno)
	{
		$sp = "Call spUpdateDealer($id, '$lname', '$fname', '$mname', '$nickname', '$telno', '$mobileno', '$address', '$zipcode', 
					$class, $dealertype, $gsutype, '$ibmno', '$ibmname', $zone, '$lstay', $marital, $educ, $directsellexp, $empstatus,
					'$directsellcomp', '$remarks', $credittermid, $character, $capacity, $capital, $condition, $calculatedcl,
					$recommendedcl, $approvedcl, '$recruiteraccount', '$tin', '$ibmcode', $areaid, '$igscode', '$bday', $pdaid, '$recruiter_mobileno');";	
		$rs = $database->execute($sp);
    	return $rs;		
	}
        
        /*
         * @author: jdymosco
         * @date: Feb. 21, 2013
         * @explanation: Instead of using and updating the current store proc method we just created a new one to avoid conflicts.
         */
        public function spImprovedUpdateDealer($database, $id, $lname, $fname, $mname, $nickname, $telno, $mobileno, $address, 
								$zipcode , $class, $dealertype, $gsutype, $IBMID,$ibmno, $ibmname, $zone, $lstay, 
								$marital, $educ, $directsellexp, $empstatus, $directsellcomp, $remarks,
								$credittermid, $character, $capacity, $capital, $condition, $calculatedcl,
								$recommendedcl, $approvedcl, $recruiteraccount, $tin, $ibmcode, $areaid, $igscode, $bday, $pdaid, $recruiter_mobileno)
	{
		$sp = "Call spImprovedUpdateDealer($id, '$lname', '$fname', '$mname', '$nickname', '$telno', '$mobileno', '$address', '$zipcode', 
					$class, $dealertype, $gsutype, $IBMID,'$ibmno', '$ibmname', $zone, '$lstay', $marital, $educ, $directsellexp, $empstatus,
					'$directsellcomp', '$remarks', $credittermid, $character, $capacity, $capital, $condition, $calculatedcl,
					$recommendedcl, $approvedcl, '$recruiteraccount', '$tin', '$ibmcode', $areaid, '$igscode', '$bday', $pdaid, '$recruiter_mobileno');";	
		$rs = $database->execute($sp);
                return $rs;		
	}
	
	public function spPromoteDealer($database, $id, $customertypeid)
	{
		$sp = "Call spPromoteDealer($id, $customertypeid);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateWarehouse($database, $id, $code, $name)
	{
		$sp = "Call spUpdateWarehouse($id, '$code', '$name');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateProduct($database, $id, $pCode, $pName, $pShrtName, $pProdType, $pProdLine, $pPMGID, $pUCost, $pBrand, $pForm, $pSize, $pLife, $pStyle, $pSubForm, $pColor)
	{

		$sp = "Call spUpdateProduct($id, '$pCode', '$pName', '$pShrtName', $pProdType, $pProdLine, $pPMGID, $pUCost, $pBrand, $pForm, '$pSize', '$pLife', $pStyle, $pSubForm, $pColor);";
	    $rs = $database->execute($sp);
	    return $rs;
	}

	public function spUpdateProductClass($database, $id, $code, $name, $description)
	{
		$sp = "Call spUpdateProductClass($id, '$code', '$name', '$description');";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spUpdateUserType($database, $id, $code, $name)
	{
		$sp = "Call spUpdateUserType($id, '$code', '$name');";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateModuleControl($database, $id, $code, $name, $description, $pageno)
	{
		$sp = "Call spUpdateModuleControl($id, '$code', '$name', '$description', '$pageno');";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateBrand($database, $bID, $bCode, $bName, $bDesc)
	{
		$sp = "Call spUpdateBrand($bID, '$bCode', '$bName', '$bDesc');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateCategory($database, $catID, $catCode, $catName, $catDesc)
	{
		$sp = "Call spUpdateCategory($catID, '$catCode', '$catName', '$catDesc');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateProdType($database, $ptID, $ptCode, $ptName, $ptDesc)
	{
		$sp = "Call spUpdateProdType($ptID, '$ptCode', '$ptName', '$ptDesc');";	
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spUpdateOutletType($database, $otID, $otCode, $otName)
	{
		$sp = "Call spUpdateOutletType($otID, '$otCode', '$otName');";		
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spUpdateDepartment($database, $dID, $dCode, $dName, $dDesc, $pid)
	{
		$sp = "Call spUpdateDepartment($dID, '$dCode', '$dName', '$dDesc', $pid);";			
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spUpdateEmployee($database, $id, $code, $lname, $fname, $mname, $bday, $dhired, $etid, $deptid, $posid, $brid)
	{
		$sp = "Call spUpdateEmployee($id, '$code', '$lname', '$fname', '$mname', '$bday', '$dhired', $etid, $deptid, $posid, $brid);"; 	
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spUpdateUser($database, $id, $username, $loginname, $password, $usertypeid)
	{
		$sp = "Call spUpdateUser($id, '$username', '$loginname', '$password', $usertypeid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spUpdatePaymentTerms($database, $id, $code, $name, $desc, $duration, $ttid)
	{
		$sp = "Call spUpdatePaymentTerms($id, '$code', '$name', '$desc', $duration, $ttid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spUpdateInventorySOH($database, $invid, $qty)
	{
		$sp = "Call spUpdateInventorySOH($invid, $qty);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateAdjustmentConfirm($database, $txnid, $remarks, $cby)
	{
		$sp = "Call spUpdateAdjustmentConfirm($txnid, '$remarks', '$cby');";		
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spUpdateTransferConfirm($database, $txnid, $remarks, $cby)
	{
		$sp = "Call spUpdateTransferConfirm($txnid, '$remarks', '$cby');";		
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spUpdateInventoryInConfirm($database,$txnid, $remarks, $cby)
	{
		$sp = "Call spUpdateInventoryInConfirm($txnid, '$remarks', '$cby');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateInventoryOutConfirm($database, $txnid, $remarks, $cby)
	{
		$sp = "Call spUpdateInventoryOutConfirm($txnid, '$remarks', '$cby');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateInventory ($v_intxn, $v_warehouseid, $v_inventoryid, $v_productid, $v_qty)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spUpdateInventory($v_intxn, $v_warehouseid, $v_inventoryid, $v_productid, $v_qty)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}	
	
	public function spUpdateInventoryBalance ($v_intxn, $v_warehouseid, $v_productid, $v_qty, $v_unitcost)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spUpdateInventoryBalance($v_intxn, $v_warehouseid, $v_productid, $v_qty, $v_unitcost)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	
	public function spConfirmSalesOrder ($database, $v_txnid)
	{		
		$sp = "Call spConfirmSalesOrder($v_txnid)";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	
	/************************** DELETE STOREDPROC *********************************/
	
	public function spDeletePriceList($database, $id)
	{
		$sp = "Call spDeletePriceList($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
		
	public function spDeleteTmpAdjustmentDetails($database, $userid, $createid, $inventoryid)
	{
		$sp = "Call spDeleteTmpAdjustmentDetails($userid, '$createid', $inventoryid);";
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spDeleteTmpInventoryCountDetails($database, $userid, $createid, $inventoryid)
	{
		$sp = "Call spDeleteTmpInventoryCountDetails($userid, '$createid', $inventoryid);";
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spDeleteTmpTransferDetails($database,$userid, $createid, $inventoryid)
	{
		$sp = "Call spDeleteTmpTransferDetails($userid, '$createid', $inventoryid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteTmpInventoryInDetails($database, $userid, $createid, $inventoryid)
	{
		$sp = "Call spDeleteTmpInventoryInDetails($userid, '$createid', $inventoryid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteTmpInventoryOutDetails($database,$userid, $createid, $inventoryid)
	{
		$sp = "Call spDeleteTmpInventoryOutDetails($userid, '$createid', $inventoryid);";
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spDeleteCustomer($database, $id)
	{
		$sp = "Call spDeleteCustomer($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteWarehouse($database, $id)
	{
		$sp = "Call spDeleteWarehouse($id);";
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spDeleteInventoryCount($database, $id)
	{
		$sp = "Call spDeleteInventoryCount($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteProductClass($database, $id)
	{
		$sp = "Call spDeleteProductClass($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteUserType($database, $id)
	{
		$sp = "Call spDeleteUserType($id);";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteModuleControl($database, $id)
	{
		$sp = "Call spDeleteModuleControl($id);";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteBrand($database,$id)
	{
		$sp = "Call spDeleteBrand($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteCategory($database, $id)
	{
		$sp = "Call spDeleteCategory($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteProdType($database, $id)
	{
		$sp = "Call spDeleteProdType($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteDepartment($database, $dID)
	{
		$sp = "Call spDeleteDepartment($dID);"; 
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteOutletType($database,$otID)
	{
		$sp = "Call spDeleteOutletType($otID);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteEmployee($database, $id)
	{
		$sp = "Call spDeleteEmployee($id);"; 
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spDeleteUser($database, $id)
	{
		$sp = "Call spDeleteUser($id);";		
		$rs = $database->execute($sp);
		return $rs;;
	}
	
	public function spDeleteUserTypeModControl($database, $utid)
	{
		$sp = "Call spDeleteUserTypeModControl($utid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeletePaymentTerms($database, $id)
	{
		$sp = "Call spDeletePaymentTerms($id);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spDeleteAdjustmentDetails($database, $txnid, $prodid)
	{
		$sp = "Call spDeleteAdjustmentDetails($txnid, $prodid);";
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spDeleteTransferDetails($database, $txnid, $prodid)
	{
		$sp = "Call spDeleteTransferDetails($txnid, $prodid);";		
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spDeleteAdjustment($database, $txnid)
	{
		$sp = "Call spDeleteAdjustment($txnid);";		
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spDeleteTransfer($database, $txnid, $empid)
	{
		$sp = "Call spDeleteTransfer($txnid, $empid);";		
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spDeleteInventoryIn($database, $txnid)
	{
		$sp = "Call spDeleteInventoryIn($txnid);";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spDeleteInventoryInDetails($database, $txnid, $prodid)
	{
		$sp = "Call spDeleteInventoryInDetails($txnid, $prodid);";		
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spDeleteInventoryOut($database, $txnid)
	{
		$sp = "Call spDeleteInventoryOut($txnid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteInventoryOutDetails($database, $txnid, $prodid)
	{
		$sp = "Call spDeleteInventoryOutDetails($txnid, $prodid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spDeleteSalesOrder ($v_txnid)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}		
		$sp = "Call spDeleteSalesOrder($v_txnid)";		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	
	public function spSelectProductListInventory ($database, $vSearch,$parentID)
	{
		$sp = "Call spSelectProductListInventory('%$vSearch%',$parentID);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectProductListInventory2 ($database, $vSearch,$parentID, $wid)
	{
		$sp = "Call spSelectProductListInventory2('$vSearch%',$parentID, $wid);"; //echo $sp; exit;
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectProductListInventoryTransfer ($database, $vSearch,$parentID,$warehouseId)
	{
		$sp = "Call spSelectProductListInventoryTransfer('$vSearch%',$parentID,$warehouseId);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryInRHOCount ($database, $vSearch)
	{
		$sp = "Call spSelectInventoryInRHOCount('%$vSearch%');";
	    $rs = $database->execute($sp);
	    return $rs;			

	}
	
	public function spSelectInventoryInRHO ($database, $v_offset, $v_perpage, $vSearch)
	{	
		$sp = "Call spSelectInventoryInRHO($v_offset, $v_perpage, '%$vSearch%');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	/*public function spSelectInventoryOutIssueSTACount ($vSearch)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spSelectInventoryOutIssueSTACount('%$vSearch%');";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	
	/*public function spSelectInventoryOutIssueSTA ($v_offset, $v_perpage, $vSearch)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spSelectInventoryOutIssueSTA($v_offset, $v_perpage, '%$vSearch%');";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	public function spSelectInventoryOutIssueSTAHeaderbyID ($database,$txnid)
	{
		$sp = "Call spSelectInventoryOutIssueSTAHeaderbyID($txnid);";	
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectInventoryOutIssueSTADetailsbyID ($database,$txnid, $vSearch)
	{
		$sp = "Call spSelectInventoryOutIssueSTADetailsbyID($txnid, '%$vSearch%');";
		$rs = $database->execute($sp);
	    return $rs;

	}
	public function spSelectSOHbyInvID($database, $invID)
	{
		$sp = "Call spSelectSOHbyInvID($invID);";		
		$rs = $database->execute($sp);
	    return $rs;	

	}
	public function spSelectProductChildByLevelID ($database, $v_levelid, $v_prodid)
	{
		$sp = "Call spSelectProductChildByLevelID($v_levelid, $v_prodid);";
		$rs = $database->execute($sp);
	    return $rs;
	}
	public function spInsertPromoAvailment ($database, $v_promoid, $v_gsutid, $v_max)
	{
		$sp = "Call spInsertPromoAvailment($v_promoid, $v_gsutid, $v_max);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectProductLevel ($database)
	{
		$sp = "Call spSelectProductLevel();";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectProductListByLevelID($database, $levelid, $vSearch)
	{
		$sp = "Call spSelectProductListByLevelID($levelid, '$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectInventoryInSTADetailsbyID ($database, $txnid)
	{

		$sp = "Call spSelectInventoryInSTADetailsbyID($txnid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectPricing($database, $search)
	{

		$sp = "Call spSelectPricing( '%$search%');";
	    $rs = $database->execute($sp);
	    return $rs;
		
	}
	public function spSelectUOMbyID ($database,$id)
	{
		$sp = "Call spSelectUOMbyID($id);";  		
		$rs = $database->execute($sp);
		return $rs;		

	}
	
	public function spSelectReasonbyID ($database, $id)
	{
		$sp = "Call spSelectReasonbyID($id);"; // echo $sp;exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInvInRHODetails ($database, $search, $txnid,$wid)
	{
		$sp = "Call spSelectInvInRHODetails('$search',$txnid,$wid);";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectInvInSTADetails ($database, $search, $txnid,$wid)
	{
		 $sp = "Call spSelectInvInSTADetails('$search',$txnid,$wid);";
	
	     $rs = $database->execute($sp);
	     return $rs;	
	}
	
	public function spCheckDocumentNoIfExists ($database, $docNo, $tableName)
	{
		$sp = "Call spCheckDocumentNoIfExists('$docNo','$tableName');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spSelectInvCountByID ($id)
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spSelectInvCountByID($id);";	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;		

	}
	*/
	public function spSelectUnitTypeByID($database, $id)
	{
		$sp = "Call spSelectUnitTypeByID($id);";			
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	/*public function spSelectUnitPriceInProductProfileBuying($prodid, $uomid)
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spSelectUnitPriceInProductProfileBuying($prodid, $uomid);";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
		
	}
	*/
	public function spSelectProductInvTransfer($database, $v_productID,$v_warehouseID,$statusid)
	{		
		$sp = "Call spSelectProductInvTransfer($v_productID,$v_warehouseID,$statusid);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectSalesEditOR($database, $v_ID,$v_refType,$v_custid)
	{		
		$sp = "Call spSelectSalesEditOR($v_ID,$v_refType,$v_custid);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectReason ($database,$rtid, $txtSearch)
	{
		$sp = "Call spSelectReason($rtid,'%$txtSearch%');";			
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectProductLine($database,$plid)
	{		
		$sp = "Call spSelectProductLine($plid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingPriceList2($database, $id, $code)
	{
		$sp = "Call spSelectExistingPriceList2($id,'$code');";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectExistingPosition($database, $id, $code)
	{
		$sp = "Call spSelectExistingPosition($id,'$code');";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectExistingArea($database, $id, $code)
	{
		$sp = "Call spSelectExistingArea($id,'$code');";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectExistingArea2($database,$id, $code)
	{
		$sp = "Call spSelectExistingArea2($id,'$code');";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectExistingPosition2($database, $id, $code)
	{
		$sp = "Call spSelectExistingPosition2($id,'$code');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spCheckProduct($code)
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spCheckProduct('$code');";	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	
	public function spDeleteTmpUpload($database)
	{
		$sp = "Call spDeleteTmpUpload();";	
		$rs = $database->execute($sp);
		return $rs;
	
	}
	
	public function spInsertTmpRHO($database,$drno, $rbranch, $drdate, $shipmentdate, $totlinecounter, $shipmentvia ,$totalvalue)
	{
		$sp = "Call spInsertTmpRHO('$drno', '$rbranch', '$drdate', '$shipmentdate', $totlinecounter, '$shipmentvia' ,$totalvalue);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertTmpProdMaster($database,$prodCode, $prodName, $uom, $prodLine, $csp, $pmgCode, $pmStatus, $itemType, $brand, $form, $lastReceiptDate, $productType, $cost, $lastpodate)
	{
		$sp = "Call spInsertTmpProdMaster('$prodCode', '$prodName', '$uom', '$prodLine', $csp, '$pmgCode', '$pmStatus', '$itemType', '$brand', '$form', '$lastReceiptDate', '$productType', $cost, '$lastpodate');"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertTmpRHODetails($database,$tmprhoid, $plistrefno, $linecounter, $productcode, $quantity, $desc)
	{
		$sp = "Call spInsertTmpRHODetails($tmprhoid, '$plistrefno', $linecounter, '$productcode', $quantity, '$desc');"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateTmpRHODetails($database)
	{
		$sp = "update tpi_tmprhodetails a inner JOIN product b on b.Code=a.ProductCode set a.ProductID=b.ID"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateTmpSTADetails($database)
	{
		$sp = "update tpi_tmpstadetails a inner JOIN product b on b.Code=a.ProductCode set a.ProductID=b.ID"; 	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spInsertFromTmpRHO($database)
	{
		//$sp = "Call spInsertFromTmpRHO()"; 	
		//$rs = $database->execute($sp);
		//return $rs;	
		
		$database->execute("INSERT INTO inventoryinout(HOGeneralID, RefID, MovementTypeID, DocumentNo, BranchID, ToBranchID, WarehouseID, TransactionDate, ShipmentDate, ShipmentVia, EnrollmentDate, StatusID) 
							SELECT TRIM(CONCAT((SELECT IFNULL(MAX(ID)+1,1) FROM inventoryinout),'-',b.ID)) HOGeneralID, 
							NULL, 1, DrNo, 1, b.ID BranchID, 1, DrDate, ShipmentDate, ShipmentVia, DateCreated, rho.StatusID FROM tpi_tmprho rho 
							INNER JOIN branch b ON b.Code = rho.ReceivingBranch");
							
	
		//2nd step insert the inventoryinout details..
		$qdetails = $database->execute("SELECT (SELECT SPLIT_STR(HOGeneralID,'-',2) BranchID FROM inventoryinout WHERE ID = InventoryInOutID) BranchID , InventoryInOutID, 
										LineCounter, PicklistRefNo, ShipmentAdviseNo, ProductID, UnitTypeID, Quantity,ConfirmedQty, ReasonID, EnrollmentDate FROM (
										SELECT   IFNULL((SELECT MAX(ID) FROM inventoryinout),1) InventoryInOutID, 
										LineCounter, PicklistRefNo, NULL ShipmentAdviseNo, ProductID, p.UnitTypeID, Quantity, 0 ConfirmedQty, 0 ReasonID, NOW() EnrollmentDate
										FROM tpi_tmprhodetails rhod
										INNER JOIN product p ON p.id = rhod.ProductID
										) a");
										
										

		if($qdetails->num_rows){
			while($r = $qdetails->fetch_object()){
				$BranchID	 		= $r->BranchID;
				$InventoryInOutID 	= $r->InventoryInOutID;
				$LineCounter 		= $r->LineCounter;
				$PicklistRefNo 		= $r->PicklistRefNo;
				$ShipmentAdviseNo 	= $r->ShipmentAdviseNo;
				$ProductID 			= $r->ProductID;
				$UnitTypeID 		= $r->UnitTypeID;
				$Quantity 			= $r->Quantity;
				$ConfirmedQty	 	= $r->ConfirmedQty;				
				$ReasonID 			= $r->ReasonID;
				$EnrollmentDate 	= $r->EnrollmentDate;
				//get the last id on inventoryinoutdetails table..
				$GetMaxIDQ = $database->execute("select IFNULL(MAX(ID) + 1,1) getMaxID from inventoryinoutdetails");
				if($GetMaxIDQ->num_rows){
					while($GetMaxIDr = $GetMaxIDQ->fetch_object()){
						//last id on inventoryinoutdetails table...
						$LastID = $GetMaxIDr->getMaxID;
					}
				}
				/*format HoGeneralID => ID-BranchID */
				$HOGeneralID = $LastID."-".$BranchID;
				//Insert now on inventoryinoutdetails...
				
				$database->execute("INSERT INTO inventoryinoutdetails(HOGeneralID, InventoryInOutID, LineCounter, PicklistRefNo, ShipmentAdviseNo, 
												  ProductID, UnitTypeID, LoadedQty, ConfirmedQty,ReasonID,  EnrollmentDate)
									VALUES
									('".$HOGeneralID."',".$InventoryInOutID.", ".$LineCounter.", '".$PicklistRefNo."', 
									'".$ShipmentAdviseNo."', ".$ProductID.", ".$UnitTypeID.", ".$Quantity.", ".$ConfirmedQty.", ".$ReasonID.", '".$EnrollmentDate."')");
			}
		}
	}
	
	public function spUpdateTmpProdMaster($database)
	{
		$sp = "Call spUpdateTmpProdMaster()"; 	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spInsertFromTmpProdMaster($database)
	{
		$sp = "Call spInsertFromTmpProdMaster()"; 	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spInsertFromTmpSTA($database)
	{
		//$sp = "Call spInsertFromTmpSTA()"; 	
		//$rs = $database->execute($sp);
		//return $rs;	
		

		/* New Code on STA Uploader */
		
		// 1st step insert the inventoryinout header...
		$database->execute("INSERT INTO inventoryinout(HOGeneralID ,RefID, MovementTypeID, DocumentNo, BranchID, ToBranchID, WarehouseID, TransactionDate, 
							ShipmentDate, ShipmentVia, EnrollmentDate, StatusID) 
							SELECT TRIM(CONCAT((SELECT IFNULL(MAX(ID) + 1,1) FROM inventoryinout),'-',b.ID)) HOGeneralID, NULL RefID, mt.ID MovementTypeID, STANo 
							DocumentNo, b2.ID FromBranchID, b.ID ToBranchID,  1, TxnDate TransactionDate, TxnDate ShipmentDate, NULL ShipmentVia, DateCreated,
							sta.StatusID
							FROM tpi_tmpsta sta
							INNER JOIN branch b ON b.Code = sta.FromBranchCode
							INNER JOIN branch b2 ON b2.Code = sta.ToBranchCode
							INNER JOIN movementtype mt ON mt.Code = sta.TxnType AND mt.Code NOT IN ('IRV', 'ITR')");
							
		$database->execute("INSERT INTO inventoryinout(HOGeneralID ,RefID, MovementTypeID, DocumentNo, BranchID, ToBranchID, WarehouseID, TransactionDate, 
							ShipmentDate, ShipmentVia, EnrollmentDate, StatusID) 
							SELECT TRIM(CONCAT((SELECT IFNULL(MAX(ID) + 1,1) FROM inventoryinout),'-',b.ID)) HOGeneralID, NULL RefID, mt.ID MovementTypeID, STANo 
							DocumentNo, b2.ID FromBranchID, b.ID ToBranchID,  1, TxnDate TransactionDate, TxnDate ShipmentDate, NULL ShipmentVia, DateCreated,
							sta.StatusID
							FROM tpi_tmpsta sta
							INNER JOIN branch b ON b.Code = sta.FromBranchCode
							INNER JOIN branch b2 ON b2.Code = sta.ToBranchCode
							INNER JOIN movementtype mt ON mt.Code = sta.TxnType AND mt.Code IN ('IRV', 'ITR')");
							
	
		//2nd step insert the inventoryinout details..
		$qdetails = $database->execute("SELECT  (SELECT SPLIT_STR(HOGeneralID,'-',2) BranchID FROM inventoryinout WHERE ID = InventoryInOutID) BranchID, 
										InventoryInOutID,LineCounter, PickListRefNo, ShipmentAdviseNo, ProductID, UnitTypeID,Quantity,ConfirmedQty,ReasonID,EnrollmentDate FROM (
										SELECT (SELECT IFNULL(MAX(ID),1) FROM inventoryinout) InventoryInOutID, 
										LineCounter, NULL PicklistRefNo, STANo ShipmentAdviseNo, ProductID, p.UnitTypeID, Quantity, 0 ConfirmedQty, 0 ReasonID, NOW() EnrollmentDate
										FROM tpi_tmpstadetails stad
										INNER JOIN product p ON p.id = stad.ProductID
										) a");
		if($qdetails->num_rows){
			while($r = $qdetails->fetch_object()){
				$BranchID	 		= $r->BranchID;
				$InventoryInOutID 	= $r->InventoryInOutID;
				$LineCounter 		= $r->LineCounter;
				$PicklistRefNo 		= $r->PicklistRefNo;
				$ShipmentAdviseNo 	= $r->ShipmentAdviseNo;
				$ProductID 			= $r->ProductID;
				$UnitTypeID 		= $r->UnitTypeID;
				$Quantity 			= $r->Quantity;
				$ConfirmedQty	 	= $r->ConfirmedQty;				
				$ReasonID 			= $r->ReasonID;
				$EnrollmentDate 	= $r->EnrollmentDate;
				//get the last id on inventoryinoutdetails table..
				$GetMaxIDQ = $database->execute("select MAX(ID) + 1 getMaxID from inventoryinoutdetails");
				if($GetMaxIDQ->num_rows){
					while($GetMaxIDr = $GetMaxIDQ->fetch_object()){
						//last id on inventoryinoutdetails table...
						$LastID = $GetMaxIDr->getMaxID;
					}
				}
				/*format HoGeneralID => ID-BranchID */
				$HOGeneralID = $LastID."-".$BranchID;
				//Insert now on inventoryinoutdetails...
				
				$database->execute("INSERT INTO inventoryinoutdetails(HOGeneralID, InventoryInOutID, LineCounter, PicklistRefNo, ShipmentAdviseNo, 
												  ProductID, UnitTypeID, LoadedQty, ConfirmedQty,ReasonID,  EnrollmentDate)
									VALUES
									('".$HOGeneralID."',".$InventoryInOutID.", ".$LineCounter.", '".$PicklistRefNo."', 
									'".$ShipmentAdviseNo."', ".$ProductID.", ".$UnitTypeID.", ".$Quantity.", ".$ConfirmedQty.", ".$ReasonID.", '".$EnrollmentDate."')");
			}
		}
		/*
		#For Reservation
		
		$database->execute("INSERT INTO reservation(BranchCode, ReservationNo, StartDate, EndDate, EnrollmentDate, CreateDate, LastUpdateDate, CreateDateDMS, `Changed` , `IsClosed`)
						   SELECT sta.FromBranchCode, concat(sta.FromBranchCode,'-',sta.STANo), TxnDate, TxnDate, TxnDate, DateCreated, DateCreated , DateCreated, 1, 0
						   FROM tpi_tmpsta sta
						   WHERE  sta.TxnType IN ('RTR')");
       
        $database->execute("INSERT INTO reservationdetails(ReservationNo, ProductCode, RequestedQuantity, ApprovedQuantity, RemainingQuantity, LoadedQuantity, AvailableSOH, SOH, CreateDate, LastUpdateDate, `Changed`)
						   SELECT concat(sta.FromBranchCode,'-',sta.STANo)  , p.code,  Quantity , Quantity , Quantity , 0 , 0, 0, NOW() , NOW() , 1
						   FROM tpi_tmpstadetails stad
							INNER JOIN tpi_tmpsta sta ON sta.STANo  = stad.STANo
							INNER JOIN product p ON p.id = stad.ProductID
						   WHERE  sta.TxnType IN ('RTR')");

		//create reservation sta file for the meantime.... 		

		$reservationquery = $database->execute(" SELECT sta.FromBranchCode  BranchCode, STANo , DATE_FORMAT(sta.TxnDate,'%m/%d/%y' ) TxnDate FROM tpi_tmpsta sta WHERE  sta.TxnType IN ('RTR') ");
		if($reservationquery->num_rows)
		{
			while($rsv = $reservationquery->fetch_object())
		   {
			    $reservationDetailsquery = $database->execute(" SELECT stad.STANo,stad.LineCounter  LineCounter, 
																	   stad.ProductCode ProductCode ,stad.Description Description, 
																	   stad.Quantity Quantity 
																FROM tpi_tmpstadetails stad
                                                                INNER JOIN tpi_tmpsta sta ON sta.STANo  = stad.STANo
																WHERE  sta.TxnType IN ('RTR')
															");
				
				$dumpname1 = $rsv->BranchCode.'-'.$rsv->STANo;
			    $dumpname  = $rsv->BranchCode.'-'.$rsv->STANo.'.'.$rsv->BranchCode; // Reservation..		
				$fopen = fopen("/var/www/html/headoffice/Reservation/".$dumpname, 'w');

				fwrite($fopen,'"'.$rsv->BranchCode.'" '); 
				fwrite($fopen,''.$rsv->TxnDate.' ');
				fwrite($fopen,'"'.$dumpname1.'" ');
				fwrite($fopen, $reservationDetailsquery->num_rows."\n");
				
				if($reservationDetailsquery->num_rows)
		        {
					while($rsvd = $reservationDetailsquery->fetch_object())
		            {
						fwrite($fopen,'"'.$dumpname1.'" ');
						fwrite($fopen,''.$rsvd->LineCounter.' ');
						fwrite($fopen,'"'.$rsvd->ProductCode.'" ');
						fwrite($fopen,'"'.$rsvd->Description.'" ');
						fwrite($fopen,''.$rsvd->Quantity."\n");
						
					}
				}
		   }
		}
		*/
	}
	
	public function spInsertTmpSTA($database, $txntype, $stano, $frombranchcode, $totlinecounter, $txnDate, $tobranchcode)
	{
		$sp = "Call spInsertTmpSTA('$txntype', '$stano', '$frombranchcode', '$tobranchcode', $totlinecounter, '$txnDate');"; 
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertTmpSTADetails($database, $tmpstaid, $stano, $linecounter, $productcode, $desc, $qty)
	{

		$sp = "Call spInsertTmpSTADetails($tmpstaid, '$stano', $linecounter, '$productcode', '$desc', $qty);"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertSyncLog($database, $SyncTypeID, $branchID)
	{

		$sp = "Call spInsertSyncLog($SyncTypeID, $branchID);"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertSyncLogDetails($database, $SyncID, $TableName, $Uploaded, $Processed)
	{

		$sp = "Call spInsertSyncLogDetails($SyncID, '$TableName', $Uploaded, $Processed);"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateSyncLog($database, $SyncID)
	{

		$sp = "Call spUpdateSyncLog($SyncID);"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spInsertTmpUpload($drno, $rbranch, $drdate, $shipmentdate, $totlinecounter, $shipmentvia ,$totalvalue, $plistrefno, $lcounter, $prodcode, $qty, $desc)
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spInsertTmpUpload($drno, '$rbranch', '$drdate', '$shipmentdate', $totlinecounter, '$shipmentvia' ,$totalvalue, '$plistrefno', $lcounter, '$prodcode', $qty, '$desc');"; 	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	public function spSelectExistingPriceList($database, $id, $code)
	{
		$sp = "Call spSelectExistingPriceList($id,'$code');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingProductClass2($database, $id, $code)
	{
		$sp = "Call spSelectExistingProductClass2($id,'$code');";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectExistingProductClass($database,$id, $code)
	{
		$sp = "Call spSelectExistingProductClass($id,'$code');";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spProductClassProduct($database,$id)
	{

		$sp = "Call spProductClassProduct($id);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spDepartmentEmployee($database,$id)
	{
		$sp = "Call spDepartmentEmployee($id);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingDepartment2($database,$id, $code)
	{

		$sp = "Call spSelectExistingDepartment2($id, '$code');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingDepartment($database,$id, $code)
	{
		$sp = "Call spSelectExistingDepartment($id, '$code');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingEmployee2($database, $id, $code)
	{

		$sp = "Call spSelectExistingEmployee2($id, '$code');";	
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectExistingEmployee ($database, $id, $code)
	{
		$sp = "Call spSelectExistingEmployee($id, '$code');";	
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
/*	public function spCheckEmployeeWareHouse ($id)
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spCheckEmployeeWareHouse($id);";	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	/*public function spCheckEmployeeLinks ($id)
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spCheckEmployeeLinks($id);";	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	//	public function spSelectExistingCustomer ($code)
	//	{
	//		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	//		/* check connection */
	//		if (mysqli_connect_errno()) {
	//			printf("Connect failed: %s\n", mysqli_connect_error());
	//			exit();
	//		}
	//
	//		$sp = "Call spSelectExistingCustomer('$code');";	
	//		$rs = $mysqli->query($sp);
	//		$mysqli->close();
	//		return $rs;	
	//	}
	
	public function spSelectExistingBrand ($database, $code)
	{
		$sp = "Call spSelectExistingBrand('$code');";	
	    $rs = $database->execute($sp);
	    return $rs;	

	}
	
	public function spSelectExistingPaymentTerms ($database,$code)
	{
		$sp = "Call spSelectExistingPaymentTerms('$code');";	
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spCheckBrand ($database, $id)
	{
		$sp = "Call spCheckBrand($id);";			
	    $rs = $database->execute($sp);
	    return $rs;			

	}
	
	public function spCheckProdType ($database, $id)
	{

		$sp = "Call spCheckProdType($id);";			
	    $rs = $database->execute($sp);
	    return $rs;		

	}
	
	public function spCheckPaymentTerms ($database, $id)
	{
		$sp = "Call spCheckPaymentTerms($id);";			
		$rs = $database->execute($sp);
	    return $rs;	

	}
	
	public function spCheckOutletType ($database,$id)
	{
		$sp = "Call spCheckOutletType($id);";		
		$rs = $database->execute($sp);
	    return $rs;	

	}
	
	public function spSelectExistingCategory ($database, $code)
	{
		$sp = "Call spSelectExistingCategory('$code');";		
		$rs = $database->execute($sp);
	    return $rs;	

	}
	
	public function spSelectExistingProduct($database, $id, $code)
	{
		
		$sp = "Call spSelectExistingProduct($id, '$code');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spCheckCategory ($database,$id)
	{

		$sp = "Call spCheckCategory($id);";			
	    $rs = $database->execute($sp);
	    return $rs;

	}
	
	public function spCheckWarehouse ($database, $id)
	{

		$sp = "Call spCheckWarehouse($id);";			
		$rs = $database->execute($sp);
	    return $rs;

	}
	
	public function spSelectExistingOutletType ($database,$code) 
	{
		$sp = "Call spSelectExistingOutletType('$code');";	
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectExistingWarehouse($database,$id,$code) 
	{
		$sp = "Call spSelectExistingWarehouse($id,'$code');";	
		$rs = $database->execute($sp);
	    return $rs;	

	}
	
	public function spSelectExistingWarehouse2($database,$id,$code) 
	{
		$sp = "Call spSelectExistingWarehouse($id,'$code');";	
		$rs = $database->execute($sp);
	    return $rs;	

	}
	
	public function spSelectExistingModuleControl ($database, $modid, $submodid, $code) 
	{
		$sp = "Call spSelectExistingModuleControl($modid, $submodid, '$code');";	
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelectInvCountDetailsByID ($database, $id) 
	{
		$sp = "Call spSelectInvCountDetailsByID($id);";	
		$rs = $database->execute($sp);
		return $rs;

	}
	
	public function spSelectExistingLogin ($database, $loginname, $id) 
	{
		$sp = "Call spSelectExistingLogin('$loginname', $id);";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingUserType ($database, $id, $code) 
	{
		$sp = "Call spSelectExistingUserType($id, '$code');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingProductType ($database, $code) 
	{
		$sp = "Call spSelectExistingProductType('$code');";		
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectTmpAdjustmentDetails ($database, $userid, $createid)
	{
		$sp = "Call spSelectTmpAdjustmentDetails($userid, '$createid');";	
		$rs = $database->execute($sp);
		return $rs;		
	}

	public function spSelectTmpInventoryCountDetails ($database, $userid, $createid)
	{
		$sp = "Call spSelectTmpInventoryCountDetails($userid, '$createid');";	
		$rs = $database->execute($sp);
		return $rs;			

	}

	public function spSelectTmpTransferDetails ($database, $userid, $createid)
	{
		$sp = "Call spSelectTmpTransferDetails($userid, '$createid');";
		$rs = $database->execute($sp);
		return $rs;	

	}
	
	public function spSelectTmpInventoryInDetails ($database,$userid, $createid)
	{
		$sp = "Call spSelectTmpInventoryInDetails($userid, '$createid');";		
		$rs = $database->execute($sp);
		return $rs;		

	}
	
	public function spSelectTmpInventoryOutDetails ($database,$userid, $createid)
	{

		$sp = "Call spSelectTmpInventoryOutDetails($userid, '$createid');";			
		$rs = $database->execute($sp);
		return $rs;

	}

	public function spSelectUserById ($database, $id)
	{
		$sp = "Call spSelectUser($id);";			
		$rs = $database->execute($sp);
		return $rs;		

	}
		
	public function spSelectWarehouse ($database, $wid, $search)
	{
		$sp = "Call spSelectWarehouse($wid , '%$search%');";					
		$rs = $database->execute($sp);
		return $rs;
	}	
	
	public function spSelectAreaLevelCbo ($database)
	{

		$sp = "Call spSelectAreaLevelCbo();";	
		$rs = $database->execute($sp);
		return $rs;
		
	}	
	public function spSelectArea ($database, $id, $search, $mode)
	{
		$sp = "Call spSelectArea($id, '%$search%', $mode);";	
		$rs = $database->execute($sp);
		return $rs;
		
	}	
	
	public function spSelectMovementType ($database, $v_ID, $txtSearch)
	{
		$sp = "Call spSelectMovementType($v_ID,'%$txtSearch%');";	
		$rs = $database->execute($sp);
		return $rs;
	}	
	
	public function spSelectWarehouseCBO ($database, $wid, $search)
	{
		$sp = "Call spSelectWarehouseCBO($wid , '%$search%');";	
	
	    $rs = $database->execute($sp);
	    return $rs;
		
	}
	
	public function spSelectProductClass ($database, $pid, $search)
	{
		$sp = "Call spSelectProductClass($pid, '%$search%');";		
	    $rs = $database->execute($sp);
	    return $rs;
		
	}
	
	public function spSelectAdjustment ($database, $v_offset, $v_perpage, $vSearch)
	{
		$sp = "Call spSelectAdjustment($v_offset, $v_perpage, '%$vSearch%');"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectTransfer ($database, $v_offset, $v_perpage, $vSearch)
	{
		$sp = "Call spSelectTransfer($v_offset, $v_perpage, '%$vSearch%');";    
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelectInventoryCount ($database, $v_offset, $v_perpage, $vSearch)
	{

		$sp = "Call spSelectInventoryCount($v_offset, $v_perpage, '%$vSearch%');";
		$rs = $database->execute($sp);
		return $rs;		

	}

	public function spSelectTransferByID ($database, $id)
	{		
		$sp = "Call spSelectTransferByID($id);";
		$rs = $database->execute($sp);
		return $rs;
	}	

	public function spSelectTransferDetailsByID ($database, $id, $statid)
	{	
		$sp = "Call spSelectTransferDetailsByID($id, $statid);";		
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelectAdjustmentCount ($database, $vSearch)
	{
		$sp = "Call spSelectAdjustmentCount('%$vSearch%');"; 
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spSelectInventoryCountCount ($vSearch)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spSelectInventoryCountCount('%$vSearch%');";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}	
*/
	public function spSelectTransferCount ($database, $vSearch)
	{		
		$sp = "Call spSelectTransferCount('%$vSearch%');";
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelectBrand ($database)
	{		
		$sp = "Call spSelectBrand();";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCategory ($database,$cid, $search)
	{
		
		$sp = "Call spSelectCategory($cid,'%$search%');";
		$rs = $database->execute($sp);
		return $rs;

	}
	
	public function spSelectCustomer ($database, $custID, $search)
	{
		$sp = "Call spSelectCustomer($custID,'%$search%');";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectDealers ($database, $id,
									 $customerType,
									 $siFrom,
									 $siTo,
									 $fromDate,
									 $toDate)
	{
			
		$sp = "Call spSelectDealers($id,
									$customerType,
									$siFrom,
									$siTo,
									'$fromDate',
									'$toDate');";
		//echo $sp;exit;							
		$rs = $database->execute($sp);		
		return $rs;
	}
	
	public function spSelectCustomerType ($database)
	{
			
		$sp = "Call spSelectCustomerType();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectGSUType ($database)
	{
		$sp = "Call spSelectGSUType();";
		$rs = $database->execute($sp);
		return $rs;	
	}

	public function spSelectClass ($database)
	{
			
		$sp = "Call spSelectClass();";
		$rs = $database->execute($sp);
		return $rs;
	}										
	public function spSelectPosition($database, $ID, $search)
	{
		$sp = "Call spSelectPosition($ID, '%$search%');";	
	    $rs = $database->execute($sp);
	    return $rs;
	}				
	public function spInsertArea($database, $code,$name,$arealevelid, $areaprnt)
	{
		
		$sp = "Call spInsertArea('$code','$name',$arealevelid, $areaprnt);";
		$rs = $database->execute($sp);
	    return $rs;

	}
	
	public function spInsertPosition($database, $code,$name)
	{

		$sp = "Call spInsertPosition('$code','$name');";
		$rs = $database->execute($sp);
	    return $rs;
		
	}
	public function spUpdateArea($database,$id, $code, $name, $arealevelid, $areaprnt)
	{

		$sp = "Call spUpdateArea($id, '$code', '$name', $arealevelid, $areaprnt);";	
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spUpdatePosition($database,$id, $code, $name)
	{

		$sp = "Call spUpdatePosition($id, '$code', '$name');";	
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spUpdateProductPricing($database, $id, $uomid, $multi)
	{
		$sp = "Call spUpdateProductPricing($id, $uomid,$multi);";	
	    $rs = $database->execute($sp);
	    return $rs;
	}
	public function spUpdateInventoryInOutConfirm($database, $txnid, $remarks, $cby, $txndate)
	{
		$sp = "Call spUpdateInventoryInOutConfirm($txnid, '$remarks', '$cby', '$txndate');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spUpdateInventoryInOutDetailsConfirm($database, $txnid, $qty, $rid, $pid, $dID)
	{
		$sp = "Call spUpdateInventoryInOutDetailsConfirm($txnid, $qty, $rid, $pid, $dID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spDeleteArea($database, $id)
	{
		$sp = "Call spDeleteArea($id);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spDeletePosition($database,$id)
	{
		$sp = "Call spDeletePosition($id);";
		$rs = $database->execute($sp);
	    return $rs;
	}
		
	public function spSelectInventoryInSTACount ($database, $vSearch)
	{

		$sp = "Call spSelectInventoryInSTACount('%$vSearch%');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectInventoryOutSTACount ($database, $vSearch)
	{

		$sp = "Call spSelectInventoryOutSTACount('%$vSearch%');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectInventoryOutReturnsToHOCount ($database, $vSearch)
	{

		$sp = "Call spSelectInventoryOutReturnsToHOCount('%$vSearch%');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectInventoryInSTA ($database, $v_offset, $v_perpage, $vSearch)
	{

		$sp = "Call spSelectInventoryInSTA($v_offset, $v_perpage, '%$vSearch%');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectInventoryOutSTA ($database, $v_offset, $v_perpage, $vSearch)
	{
		$sp = "Call spSelectInventoryOutSTA($v_offset, $v_perpage, '%$vSearch%');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectInventoryOutReturnsToHO ($database, $v_offset, $v_perpage, $vSearch)
	{

		$sp = "Call spSelectInventoryOutReturnsToHO($v_offset, $v_perpage, '%$vSearch%');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectProductListInsPopup($database, $prodLineID, $vSearch)
	{
		$sp = "Call spSelectProductListInsPopup($prodLineID, '$vSearch%')";	
	    $rs = $database->execute($sp);
	    return $rs;
	}
	public function spSelectProductbyID ($database, $txnid, $wid)
	{
		$sp = "Call spSelectProductbyID($txnid, $wid);";	
		$rs = $database->execute($sp);
		return $rs;		

	}
	public function spInsertInventoryInOutDetails($database, $txnid, $prodid, $utid, $soh, $lqty, $cqty, $rid)
	{
		$sp = "Call spInsertInventoryInOutDetails($txnid, $prodid, $utid, $soh, $lqty, $cqty, $rid);";		
	    $rs = $database->execute($sp);
	    return $rs;
	}
	public function spInsertPromoHeader($database, $code, $desc, $start, $end, $promotype, $empid, $plusplan)
	{
		$sp = "Call spInsertPromoHeader('$code', '$desc', '$start', '$end', $promotype, $empid, $plusplan);";		
		$rs = $database->execute($sp);
		//echo $sp;
		return $rs;

	}

	public function spSelectNextSONo($database)
	{		
		$sp = "Call spSelectNextSONo();";		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectCustomerAjax($database, $vSearch)
	{
		$sp = "Call spSelectCustomerAjax('$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertPromoBuyIn($database, $promoid, $ppbuyinid, $type, $minqty, $minamt, $maxqty, $maxamt, $plevelid, $pid, $preqid, $start, $end, $leveltype)
	{
		$sp = "Call spInsertPromoBuyIn($promoid, $ppbuyinid, $type, $minqty, $minamt, $maxqty, $maxamt, $plevelid, $pid, $preqid, '$start', '$end', $leveltype)";
		//echo $sp; exit;			
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertPromoEntitlement($database, $buyinid, $type, $qty)
	{
		$sp = "Call spInsertPromoEntitlement($buyinid, $type, $qty);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertPromoEntitlementDetails($database, $entitlementid, $prodid, $qty, $effprice, $savings, $pmgid)
	{
		$sp = "Call spInsertPromoEntitlementDetails($entitlementid, $prodid, $qty, $effprice, $savings, $pmgid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoCount ($database, $v_offset, $v_perpage, $vptypeid, $vcodedesc,  $vprodcode, $isInc)
	{
		$sp = "Call spSelectPromoCount($v_offset, $v_perpage, $vptypeid, '%$vcodedesc%', '%$vprodcode%', $isInc);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spSelectPromo ($v_offset, $v_perpage, $vSearch, $vptypeid)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spSelectPromo($v_offset, $v_perpage, '%$vSearch%', $vptypeid);";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	public function spSelectPromo2 ($database, $v_offset, $v_perpage, $vptypeid, $vcodedesc,  $vprodcode, $isInc)
	{
		$sp = "Call spSelectPromo2($v_offset, $v_perpage, $vptypeid, '%$vcodedesc%', '%$vprodcode%', $isInc);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoByID ($database, $v_txnid)
	{		
		$sp = "Call spSelectPromoByID($v_txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoBuyInCountByPromoID ($database, $v_txnid)
	{		
		$sp = "Call spSelectPromoBuyInCountByPromoID($v_txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoBuyInByPromoID ($database, $v_txnid)
	{
		$sp = "Call spSelectPromoBuyInByPromoID($v_txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spSelectPromoEntitlementByPromoBuyInID ($database, $v_txnid)
	{	
		$sp = "Call spSelectPromoEntitlementByPromoBuyInID($v_txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spSelectPromoEntitlementDetailsByPromoEntitlementID ($database, $v_txnid)
	{
		$sp = "Call spSelectPromoEntitlementDetailsByPromoEntitlementID($v_txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	
	public function spSelectSinglePromoDet ($database, $prmsid, $ptypeid)
	{
		$sp = "Call spSelectSinglePromoDet($prmsid, $ptypeid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSinglePromoDetById ($database, $prmsid, $ptypeid)
	{
		$sp = "Call spSelectSinglePromoDetById($prmsid, $ptypeid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSinglePrmDetbyIDEnt ($database, $prmsid, $ptypeid)
	{
		$sp = "Call spSelectSinglePrmDetbyIDEnt($prmsid, $ptypeid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertBuyIn($database, $promoid, $pbuyinid, $type, $minqty, $minamt, $maxqty, $maxamt, $plevelid, $pid, $preqid, $start, $end, $enttype, $entqty, $leveltype)
	{

		$sp = "Call spInsertBuyIn($promoid, $pbuyinid, $type, $minqty, $minamt, $maxqty, $maxamt, $plevelid, $pid, $preqid, $start, $end, $enttype, $entqty, $leveltype)";		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoAvailByPromoID ($database, $prmsid)
	{
		$sp = "Call spSelectPromoAvailByPromoID($prmsid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoByCode($database, $code)
	{
		$sp = "Call spSelectPromoByCode('$code');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectMultiLinePromoDetails($database, $buyinid, $mode)
	{
		$sp = "Call spSelectMultiLinePromoDetails($buyinid, $mode);"; 
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoValidation ($database, $vdatefrom, $vdateto, $vcode)
	{	
		$sp = "Call spSelectPromoValidation('$vdatefrom', '$vdateto', '%$vcode%');";//echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoValidationCount ($database, $vdatefrom, $vdateto, $vcode)
	{		
		$sp = "Call spSelectPromoValidationCount('$vdatefrom', '$vdateto', '%$vcode%');"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoValidationPaging ($database, $v_offset, $v_perpage, $vdatefrom, $vdateto, $vcode)
	{		
		$sp = "Call spSelectPromoValidationPaging($v_offset, $v_perpage, '$vdatefrom', '$vdateto', '%$vcode%');"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectCustomerByCustomerTypeID ($database)
	{		
		$sp = "Call spSelectCustomerByCustomerTypeID();";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectCustomerLatestIBM ($database, $v_id)
	{
		$sp = "Call spSelectCustomerLatestIBM($v_id);";
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelectDealerReverseIBMC ($database, $fromibmid, $toibmid, $fromnopo, $tonopo)
	{
		$sp = "Call spSelectDealerReverseIBMC('$fromibmid', '$toibmid', '$fromnopo', '$tonopo');";
		$rs = $database->execute($sp);
		return $rs;	

	}
	
	public function spSelectDealerReverseIBMCByID ($database, $id)
	{
		$sp = "Call spSelectDealerReverseIBMCByID('$id');";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectDealerTerminate($database, $fromibmid, $toibmid, $fromnopo, $tonopo)
	{				
		$sp = "Call spSelectDealerTerminate('$fromibmid', '$toibmid', '$fromnopo', '$tonopo');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectDealerForWriteOff($database, $v_Include, $v_Exclude, $v_days)
	{
		$sp = "Call spSelectDealerForWriteOff('$v_Include', '$v_Exclude', '$v_days');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectDealerForWriteOffReq($database, $v_Include, $v_Exclude, $days)
	{
		$sp = "Call spSelectDealerForWriteOffReq('$v_Include','$v_Exclude', '$days');";	
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelecttpidPda($database)
	{
		$sp = "Call spSelecttpidPda();";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInserttpiDealerWriteOff($database,  $custID,
											    $pastDue,
											    $reasonId,
											    $statusid,
											    $createdBy,
											    $approvedBy)
	{

		$sp = "Call spInserttpiDealerWriteOff($custID,
											    $pastDue,
											    $reasonId,
											    $statusid,
											    $createdBy,
											    null);";	
											    
		$rs = $database->execute($sp);
		return $rs;									  
	
	}
	
	public function spSelecttpiGsuType($database)
	{
		$sp = "Call spSelecttpiGsuType();";		
		$rs = $database->execute($sp);
		return $rs;
	}

	
	/*public function spSelectAffiliatedBranches($id)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
					
		$sp = "Call spSelectAffiliatedBranches($id);";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	
	public function spSelectBranches($database)
	{			
		$sp = "Call spSelectBranches();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertIBMAffiliation ($database, $custid, $branchid, $userID, $remarks)
	{
		$sp = "Call spInsertIBMAffiliation ($custid, $branchid, $userID, '$remarks');";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
  /* public function spSelectMaxDMCMRefNo ()
   {
      $mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);*/
   /*   if (mysqli_connect_errno()) {
         printf("Connect failed: %s\n", mysqli_connect_errno());
         exit();
      }
               
      $sp = "Call spSelectMaxDMCMRefNo();";
      $rs = $mysqli->query($sp);
      $mysqli->close();
      return $rs; 
   }
   */
   
   public function spInsertDMCM ($database, $v_docno, $v_txndate, $v_custid, $v_memotypeid, $v_totalamt, $v_particulars, $v_remarks, $v_userid, $vReasonId,$vTotalAppliedAmount,$vTotalUnAppliedAmount)
   {
   		$sp = "Call spInsertDMCM('$v_docno', '$v_txndate', $v_custid, $v_memotypeid, $v_totalamt, '$v_particulars', '$v_remarks', $v_userid, $vReasonId,$vTotalAppliedAmount,$vTotalUnAppliedAmount)";
      	$rs = $database->execute($sp);
      	return $rs;
   }
   
   public function spInsertDMCMDetails ($database, $v_dmcmarid, $v_reftxnid, $v_totalmat, $v_referenceType, $v_createdBy)
   {
   		$sp = "Call spInsertDMCMDetails($v_dmcmarid, $v_reftxnid, $v_totalmat, $v_referenceType, $v_createdBy)";
   		$rs = $database->execute($sp);
   		return $rs;
   }	
   
   public function spSelectMemoType ($database)
   {
   		$sp = "Call spSelectMemoType ();";     
      	$rs = $database->execute($sp);
      	return $rs;
   }
   
   public function spSelectDMCM_SI ($database, $v_custid)
   {
      $sp = "Call spSelectDMCM_SI($v_custid);"; 
      $rs = $database->execute($sp);
      return $rs;
   }
   
  public function spSelectCustomerIGSSI($database, $v_custid)
   {
      $sp = "Call spSelectCustomerIGSSI($v_custid);";       
      $rs = $database->execute($sp);
      return $rs;
   }
   public function spSelectDMCMCount($database, $v_search,$fromDate, $toDate)
   {
      $sp = "Call spSelectDMCMCount('%$v_search%','$fromDate', '$toDate');";       
      $rs = $database->execute($sp);
      return $rs; 
   }
   public function spSelectDMCM ($database, $v_offset, $v_perpage, $vSearch,$fromDate, $toDate)
   {
   		$sp = "Call spSelectDMCM($v_offset, $v_perpage, '%$vSearch%','$fromDate', '$toDate')";         
      	$rs = $database->execute($sp);
      	return $rs;
   }
   
   public function spSelectDMCMHeaderByID ($database, $v_txnid)
   {
      	$sp = "Call spSelectDMCMHeaderByID($v_txnid)";     
      	$rs = $database->execute($sp);
      	return $rs;
   }
   
   public function spSelectDMCMDetailsByID ($database, $v_txnid)
   {
   		$sp = "Call spSelectDMCMDetailsByID($v_txnid)";  
		$rs = $database->execute($sp);
      	return $rs;
   }   
	
	public function spSelectInventoryRegister($database, $offset, $perpage,  $dteFrom,$dteTo,$mtid,$search)
	{
		$sp = "Call spSelectInventoryRegister($offset,$perpage, '$dteFrom','$dteTo',$mtid,'%$search%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryRegisterCount($database,$mtid, $dteFrom, $dteTo,$search)
	{
		$sp = "Call spSelectInventoryRegisterCount($mtid,'$dteFrom','$dteTo','%$search%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spORRegisterCount($database, $year, $month)
	{
		$sp = "Call spORRegisterCount('$year', '$month');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spORRegister($database, $offset, $perpage, $year, $month)
	{
		$sp = "Call spORRegister($offset, $perpage, '$year', '$month');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectDealerAjaxProvision($database, $vSearch)
	{

		$sp = "Call spSelectDealerAjaxProvision('$vSearch%');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spViewORHeaderByID($database, $id)
	{
		$sp = "Call spViewORHeaderByID($id);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectProductListByLevelIDSO($database,$levelid, $vSearch)
	{
		$sp = "Call spSelectProductListByLevelIDSO($levelid, '$vSearch%');";				
		$rs = $database->execute($sp);
		return $rs;
	}	

	public function spInsertOfficialReceipt($database, $custId, $docNo, $txnDate, $totalAmount, $totalAppliedAmount, $totalUnappliedAmount, $remarks, $createdBy, $confirmedBy, $ortypeid, $nreasonid, $bankid, $prntcnt,$dmcmno)
	{
		$sp = "Call spInsertOfficialReceipt($custId, '$docNo', '$txnDate', $totalAmount, $totalAppliedAmount, $totalUnappliedAmount, '$remarks', $createdBy, $confirmedBy, $ortypeid, $nreasonid, $bankid, $prntcnt,'$dmcmno');";
				
		$rs = $database->execute($sp);
		return $rs;
	}
											
	 public function spDeleteSOProductList($database,$v_session)
	{		
		$sp = "Call spDeleteSOProductList('$v_session');";				
		$rs = $database->execute($sp);	
		return $rs;
	}

	public function spInsertTmpSODetails($database,$v_productID, $v_regularprice, $v_effectiveprice, $v_session, $v_pmgid, $v_producttypeid , $v_SOH, $v_Intransit,$v_promoID, $v_hasMorePromos,$v_AvailmentType,$v_SubsID, $v_ForIncentive)
	{		
		$sp = "Call  spInsertTmpSODetails($v_productID, $v_regularprice, $v_effectiveprice, '$v_session', $v_pmgid, $v_producttypeid , $v_SOH, $v_Intransit,$v_promoID,$v_hasMorePromos,$v_AvailmentType,$v_SubsID,$v_ForIncentive);";						
		//echo $sp;
		$rs = $database->execute($sp);	
		return $rs;
	}
	
	/*public function spInsertORPaymentMode($docNo,
											$txnDate,
											$totalAmount,
											$totalAppliedAmount,
											$totalUnappliedAmount,
											$remarks,
											$createdBy,
											$confirmedBy)
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME); */
		/* check connection */
	/*	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$sp = "Call spInsertORPaymentMode(($docNo,
											$txnDate,
											$totalAmount,
											$totalAppliedAmount,
											$totalUnappliedAmount,
											$remarks,
											$createdBy,
											$confirmedBy);";	
		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
*/
	
	public function spCheckDocumentNumber($database, $docno)
	{					
		$sp = "Call spCheckDocumentNumber('$docno');"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectInvAdjByID($database, $id)
	{
		$sp = "Call spSelectInvAdjByID($id);"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectInvAdjDetailsByID($database, $id)
	{
		$sp = "Call spSelectInvAdjDetailsByID($id);"; //echo $sp; exit();	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectCycleCountAdjustment ($database, $v_offset, $v_perpage, $vSearch)
	{
		$sp = "Call spSelectCycleCountAdjustment($v_offset, $v_perpage, '%$vSearch%');"; 
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCycleAdjustmentCount ($database, $vSearch)
	{
		$sp = "Call spSelectCycleAdjustmentCount('%$vSearch%');"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spGetInventoryIDWarehouseIDProductID ($database, $vProductID, $vWarehouseID)
	{
		$sp = "Call spGetInventoryIDWarehouseIDProductID($vProductID, $vWarehouseID);"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateCycleCount ($database, $id, $empid)
	{
		$sp = "Call spUpdateCycleCount($id, $empid);"; 		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectBranch($database, $id, $search)
	{				
		$sp = "Call spSelectBranch($id,'$search');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectAreaByAreaLevel($database, $arealevelid)
	{			
		$sp = "Call spSelectAreaByAreaLevel($arealevelid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
    public function spSelectBranchType($database)
	{					
		$sp = "Call spSelectBranchType();";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
 	public function spSelectExistingBranch($database, $id, $code)
	{				
		$sp = "Call spSelectExistingBranch($id, '$code');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spInsertBranch($database, $code, $name, $address, $areaid, $zipcode, $telno1, $telno2, $telno3, $faxno, $branchtype, $contactperson, $tin, $permitno, $serversn, $salesdir, $aodir, $salesdept, $aodept, $branchsize)	
	{
		$sp = "Call spInsertBranch('$code', '$name', '$address', $areaid, '$zipcode', '$telno1', '$telno2', '$telno3', '$faxno', $branchtype, $contactperson, '$tin', '$permitno', '$serversn', $salesdir, $aodir, $salesdept, $aodept, $branchsize);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spUpdateBranch($database, $id, $code, $name, $address, $areaid, $zipcode, $telno1, $telno2, $telno3, $faxno, $branchtype, $contactperson, $tin, $permitno, $serversn, $salesdir, $aodir, $salesdept, $aodept, $branchsize)	
	{					
		$sp = "Call spUpdateBranch($id, '$code', '$name', '$address', $areaid, '$zipcode', '$telno1', '$telno2', '$telno3', '$faxno', $branchtype, $contactperson, '$tin', '$permitno', '$serversn', $salesdir, $aodir, $salesdept, $aodept, $branchsize);";
     	$rs = $database->execute($sp);
     	return $rs;	
	}
	
/*	public function spSelectProdSelProdLevel()
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME); 
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
					
		$sp = "Call spSelectProdSelProdLevel();";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	public function spSelectPMG($database)
	{
		$sp = "Call spSelectPMG();";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectForm($database)
	{			
		$sp = "Call spSelectForm();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectOrCount($database, $vSearch, $fromdate, $todate)
	{
		$sp = "Call spSelectOrCount('%$vSearch%', '$fromdate', '$todate');";				
		$rs = $database->execute($sp);
		return $rs;
	}
	
	
	public function spSelectEditORCount($database, $vSearch, $fromdate, $todate)
	{
		$sp = "Call spSelectEditORCount('%$vSearch%', '$fromdate', '$todate');";	
				
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectORCash($database, $orid,$totAmount,$unAppAmount)
	{
		$sp = "Call spSelectORCash($orid,$totAmount,$unAppAmount);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInserORCheck($database, $orid, $bankName, $branchName, $checkNo, $checkDate, $totAmount, $unAppAmount)
	{
		$sp = "Call spInserORCheck($orid, '$bankName', '$branchName', '$checkNo', '$checkDate', $totAmount, $unAppAmount);";										  
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertORDepositSlip($database, $orid, $depositSlipNo, $depDate, $depType, $totAmount, $unAppAmount, $bankid)
	{
		$sp = "Call spInsertORDepositSlip($orid, '$depositSlipNo', '$depDate', $depType, $totAmount, $unAppAmount, $bankid);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	
	public function spSelectOfficialReceipt($database, $offset, $perpage, $search, $fromdate, $todate)
	{
		$sp = "Call spSelectOfficialReceipt($offset, $perpage, '%$search%', '$fromdate', '$todate');";	
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelectEditOfficialReceipt($database, $offset, $perpage, $search, $fromdate, $todate)
	{
		$sp = "Call spSelectEditOfficialReceipt($offset, $perpage, '%$search%', '$fromdate', '$todate');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectORDetailsByID($database, $id)
	{
		$sp = "Call spSelectORDetailsByID($id);";	
		$rs = $database->execute($sp);
		return $rs;
	}	
	
	public function spSelectOfficialReceiptSIByID($database, $id,$reftype)
	{
		$sp = "Call spSelectOfficialReceiptSIByID($id,$reftype);";											 
		$rs = $database->execute($sp);
		return $rs;
	}	
	
	public function spSelectEditOfficialReceiptSI($database, $id,$reftype)
	{
		$sp = "Call spSelectEditOfficialReceiptSI($id,$reftype);";											 
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectDMCMRegister($database, $offset, $perpage, $dtefrm, $dteto, $rid)
	{
		$sp = "Call spSelectDMCMRegister($offset, $perpage, '$dtefrm', '$dteto', $rid);";
		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectDMCMRegisterCount($database, $dtefrm, $dteto, $rid)					 			  
	{
		$sp = "Call spSelectDMCMRegisterCount('$dtefrm', '$dteto', $rid);";
		
		$rs = $database->execute($sp);
		return $rs;
	}
	
   public function spUpdateSalesInvoiceOutstandingBalance($database, $vSalesInvoiceID, $vAmount, $vIfSI, $isWithinTerm,$vPaymentCFT,$vPaymentNCFT,$vPaymentCPI)
   {
      $sp = "Call spUpdateSalesInvoiceOutstandingBalance($vSalesInvoiceID, $vAmount, $vIfSI, $isWithinTerm,$vPaymentCFT,$vPaymentNCFT,$vPaymentCPI);";
      $rs = $database->execute($sp);
      return $rs; 
   }

   public function spInsertOfficialReceiptDetails($database, $vORID, $vORReferenceType, $vRefTxnID, $vOutstandingBalance,$vTotalAmount, $vCreatedBy)
   {
      $sp = "Call spInsertOfficialReceiptDetails($vORID, $vORReferenceType, $vRefTxnID, '$vOutstandingBalance', '$vTotalAmount', $vCreatedBy);";
      
      $rs = $database->execute($sp);
      return $rs; 
   }
   
 /*  public function spSelectUnappliedAmountORCash($vORID)
   {
      $mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         printf("Connect failed: %s\n", mysqli_connect_errno());
         exit();
      }
               
      $sp = "Call spSelectUnappliedAmountORCash($vORID);";
      $rs = $mysqli->query($sp);
      $mysqli->close();
      return $rs; 
   }
   */
/*   public function spSelectUnappliedAmountORCheck($vORID)
   {
      $mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         printf("Connect failed: %s\n", mysqli_connect_errno());
         exit();
      } 
               
      $sp = "Call spSelectUnappliedAmountORCheck($vORID);";
      $rs = $mysqli->query($sp);
      $mysqli->close();
      return $rs; 
   } 
 */
 /*  public function spSelectUnappliedAmountORDeposit($vORID)
   {
      $mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         printf("Connect failed: %s\n", mysqli_connect_errno());
         exit();
      }
               
      $sp = "Call spSelectUnappliedAmountORDeposit($vORID);";
      $rs = $mysqli->query($sp);
      $mysqli->close();
      return $rs; 
   }    
 */  
   public function spUpdateUnappliedAmountOfficialReceipt($database, $vORID, $vAmount)
   {
      $sp = "Call spUpdateUnappliedAmountOfficialReceipt($vORID, $vAmount);";
      $rs = $database->execute($sp);
      return $rs; 
   }     
   
  /*  public function spUpdateUnappliedAmountORCash($vORCashID, $vAmount)
   {
      $mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         printf("Connect failed: %s\n", mysqli_connect_errno());
         exit();
      }
               
      $sp = "Call spUpdateUnappliedAmountORCash($vORCashID, $vAmount);";
      $rs = $mysqli->query($sp);
      $mysqli->close();
      return $rs; 
   }     
  */ 
   /* public function spUpdateUnappliedAmountORCheck($vORCheckID, $vAmount)
   {
      $mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         printf("Connect failed: %s\n", mysqli_connect_errno());
         exit();
      }
               
      $sp = "Call spUpdateUnappliedAmountORCheck($vORCheckID, $vAmount);";
      $rs = $mysqli->query($sp);
      $mysqli->close();
      return $rs; 
   }   
*/
 /*  public function spUpdateUnappliedAmountORDeposit($vORDepositID, $vAmount)
   {
      $mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         printf("Connect failed: %s\n", mysqli_connect_errno());
         exit();
      }
               
      $sp = "Call spUpdateUnappliedAmountORDeposit($vORDepositID, $vAmount);";
      $rs = $mysqli->query($sp);
      $mysqli->close();
      return $rs; 
   } */ 

   public function spSelectSIRegister($database, $offset, $perpage, $fromdate, $todate, $search, $employee)
   {
   		$sp = "Call spSelectSIRegister($offset, $perpage, '$fromdate', '$todate', '%$search%', $employee);"; 
   		$rs = $database->execute($sp);
      	return $rs;
   }
   
   public function spSelectSIRegisterCount($database, $fromdate, $todate, $search)
   {
   		$sp = "Call spSelectSIRegisterCount('$fromdate', '$todate', '%$search%');";
   		$rs = $database->execute($sp);
     	return $rs;
   }   
	public function spUpdateConfirmSalesInvoice($database,$vTxnId, $vUserId, $docno,$remarks)
   {
      $sp = "Call spUpdateConfirmSalesInvoice($vTxnId, $vUserId, '$docno','$remarks');";
      $rs = $database->execute($sp);
      return $rs; 
   }

   public function spSelectAvailableInventoryForSalesInvoice($database,$vProductId, $vWarehouseId)
   {
   
      $sp = "Call spSelectAvailableInventoryForSalesInvoice($vProductId, $vWarehouseId);";
       $rs = $database->execute($sp);     
      return $rs; 
   }

   public function spSelectOutstandingInvoicesForAutoSettlement($database, $vCustomerId)
   {
      $sp = "Call spSelectOutstandingInvoicesForAutoSettlement($vCustomerId);";
      $rs = $database->execute($sp);
      return $rs; 
   }

   public function spUpdateSalesInvoiceCancel($database,$vSIID, $vReasonID, $vRemarks, $vCancelledBy)
   {          
      $sp = "Call spUpdateSalesInvoiceCancel($vSIID, $vReasonID, '$vRemarks', $vCancelledBy);";
      $rs = $database->execute($sp);
      return $rs; 
   }   
   
   public function spUpdateOfficialReceiptCancel($database, $vORID, $vReasonID, $vRemarks, $vCancelledBy)
   {
      $sp = "Call spUpdateOfficialReceiptCancel($vORID, $vReasonID, '$vRemarks', $vCancelledBy);";  
      $rs = $database->execute($sp);
      return $rs; 
   }
   
   public function spUpdateOfficialReceipt($database, $vOrID,$vAmount)
   {
      $sp = "Call spUpdateOfficialReceipt($vOrID,$vAmount);";  
      $rs = $database->execute($sp);
      return $rs; 
   }
   
   
   public function spSelectMTDbyCustomerID($database,$customerid,$month,$year,$pmgid)
   {        
      $sp = "Call spSelectMTDbyCustomerID($customerid,$month,$year,$pmgid);";		  
      $rs = $database->execute($sp);   
      return $rs; 
   } 
   
	public function spSelectProductListSOAjax($database,$v_session)
	{
		$sp = "Call spSelectProductListSOAjax('$v_session');";								
		$rs = $database->execute($sp);
		return $rs;
	}   
	public function spInsertProductList ($database,$v_productID, $v_regularprice, $v_effectiveprice, $v_session,$pmgid,$productype,$v_purchasedate,$gsutypeid,$v_subsID, $v_forIncentive, $v_promoID)
	{
		$sp = "Call spInsertProductList($v_productID, $v_regularprice, $v_effectiveprice, '$v_session',$pmgid,$productype,'$v_purchasedate',$gsutypeid,$v_subsID,$v_forIncentive, $v_promoID);";
		//echo $sp;
		$rs = $database->execute($sp);		
		return $rs;
	}
	
	
	public function spDeleteProductList ($database, $v_session)
	{		
		$sp = "Call spDeleteProductList('$v_session');";				
		$rs = $database->execute($sp);		
		return $rs;
	}
	
	public function spInsertSOHeader ($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,$v_termsID,$gsutypeID,$promoDate,$v_isAdvanced,$branchID)
	{		
		$sp = "Call spInsertSOHeader($v_CustomerID,'$v_DocumentNo',$v_EmployeeID,$v_WarehouseID,$v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,$v_termsID,$gsutypeID,'$promoDate',$v_isAdvanced,$branchID);";					
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertSalesInvoice($database,$v_SOID,$DRID)
	{	
		$sp = "Call spInsertSalesInvoice($v_SOID,$DRID);";								
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertSalesInvoiceDetails($database,$v_SIID,$v_SOID)
	{
		$sp = "Call spInsertSalesInvoiceDetails($v_SIID,$v_SOID);";								
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectDiscountBracket($database)
	{		
		$sp = "Call spSelectDiscountBracket();";									
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectDiscountBracketbyID($database,$v_ID)
	{		
		$sp = "Call spSelectDiscountBracketbyID($v_ID);";						
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertSODetails($database,$v_SOID,$v_productID, $v_promoID, $v_promotype, $v_pmgid,$v_qty,$v_totalamt,$v_effectiveprice,$v_cnt,$v_producttype,$v_outstandingqty,$v_forIncentive)
	{
		$sp = "Call spInsertSODetails($v_SOID,$v_productID, $v_promoID, $v_promotype, $v_pmgid,$v_qty,$v_totalamt,$v_effectiveprice,$v_cnt,$v_producttype,$v_outstandingqty,$v_forIncentive);";							
	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertTmpUnclaimedProducts($database,$v_customerID,$v_productID, $v_price, $v_pmgid, $v_producttypeid, $v_SOID)
	{
		$sp = "Call spInsertTmpUnclaimedProducts($v_customerID,$v_productID, $v_price, $v_pmgid, $v_producttypeid, $v_SOID);";							
	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertUnclaimedProducts($database,$v_SIID,$v_SOID)
	{
		$sp = "Call spInsertUnclaimedProducts($v_SIID,$v_SOID);";						
		//echo $sp ;exit;
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertProvisionalReceipt($database, $custId, $docNo, $txnDate, $totalAmount, $remarks, $createdBy, $confirmedBy)
	{
		$sp = "Call spInsertProvisionalReceipt($custId, '$docNo', '$txnDate', $totalAmount, '$remarks', $createdBy,$confirmedBy);";
		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInserPRCheck($database, $prid, $bankName, $branchName, $checkNo, $checkDate, $totAmount)
	{
		$sp = "Call spInserPRCheck($prid, '$bankName', '$branchName', '$checkNo', '$checkDate', $totAmount);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPRCount($database, $vSearch, $fromdate, $todate)
	{
		$sp = "Call spSelectPRCount('%$vSearch%', '$fromdate', '$todate');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spViewPRHeaderByID($database, $id)
	{
		$sp = "Call spViewPRHeaderByID($id);";	
	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPRDetailsByID($database, $id)
	{
		$sp = "Call spSelectPRDetailsByID($id);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
 	public function spUpdateProvisionalReceiptCancel($database, $vPRID, $vReasonID, $vRemarks, $vCancelledBy)
    {
      $sp = "Call spUpdateProvisionalReceiptCancel($vPRID, $vReasonID, '$vRemarks', $vCancelledBy);";
      $rs = $database->execute($sp);
      return $rs; 
   }
	public function spSelectProdList($database, $vSearch, $id)
	{

	$sp = "Call spSelectProdList('%$vSearch%', $id);";	
      $rs = $database->execute($sp);
      return $rs; 
	}
   
	public function spInsertDealer($database, $lname, $fname, $mname, $bday, $nickname,  $telno, $mobileno, $address, 
								$zipcode , $class, $dealertype, $gsutype, $ibmno, $ibmname, $zone, $lstay, 
								$marital, $educ, $tin, $igscode, $areaid, $directsellexp, $empstatus, $credittermid, 
								$character, $capacity, $capital, $condition, $calculatedcl, $recommendedcl, $approvedcl,
								$remarks, $pdaid,$ibmID,$IBMCode,$recruiter)
    { 
     
	 	$sp = "Call spInsertDealer('$lname', '$fname', '$mname', '$bday', '$nickname', '$telno', '$mobileno', '$address', 
						'$zipcode' , $class, $dealertype, $gsutype, '$ibmno', '$ibmname', $zone, '$lstay', 
						$marital, $educ, '$tin', '$igscode', $areaid, $directsellexp, $empstatus, $credittermid, 
								$character, $capacity, $capital, $condition, $calculatedcl, $recommendedcl, $approvedcl,
								'$remarks', $pdaid,$ibmID,'$IBMCode',$recruiter);";
	  	$rs = $database->execute($sp);
     	return $rs; 
   }
   
	public function spUpdateSOStatus($database, $id)
	{

		$sp = "Call spUpdateSOStatus($id);";	
	  	$rs = $database->execute($sp);
     	return $rs; 
	}
		 
    public function spSelectPromoPopUp($database, $vSession)
    {            
      $sp = "Call spSelectPromoPopUp('$vSession');";
	  	$rs = $database->execute($sp);
     	return $rs; 
   }
    public function spSelectKitComponents($database,$vproductkitID)
    {
        
      $sp = "Call spSelectKitComponents($vproductkitID);";
	  $rs = $database->execute($sp);
      return $rs; 
   }

	public function spSelectBranchTransfer($database)
	{
		$sp = "Call spSelectBranchTransfer();";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectProvisionalReceipt($database, $offset, $perpage, $search, $fromDate, $toDate)
	{
		$sp = "Call spSelectProvisionalReceipt($offset, $perpage, '%$search%', '$fromDate', '$toDate');";	
	
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelectIBMAjax($database, $search)
	{
		$sp = "Call spSelectIBMAjax('$search');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPastDuebyCustID($database, $v_customerID)
	{
		$sp = "Call spSelectPastDuebyCustID($v_customerID);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertPromoHeaderOverlay($database, $code, $desc, $start, $end, $promotype, $otype, $oqty, $oamt, $empid, $inc, $plusplan)
	{
		$sp = "Call spInsertPromoHeaderOverlay('$code', '$desc', '$start', '$end', $promotype, $otype, $oqty, $oamt, $empid, $inc, $plusplan);";
		//echo $sp; exit;		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoBuyInByPromoIDOverlay ($database, $v_txnid)
	{		
		$sp = "Call spSelectPromoBuyInByPromoIDOverlay($v_txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertInvCount($database,$refid, $moveid, $documentno, $transactiondate, $whouseid, $remarks, $createdby)
	{

		$sp = "Call spInsertInvCount($refid, $moveid, '$documentno', '$transactiondate', $whouseid, '$remarks', '$createdby');"; 
    	$rs = $database->execute($sp);
	    return $rs;
		
	}
	
	public function spInsertInvCountDetails($database, $InvCID, $prodid, $unittypeid, $locationid, $prevbal, $createdqty, $resid, $v_tpiCountag, $mode)
	{		
		$sp = "Call spInsertInvCountDetails($InvCID, $prodid, $unittypeid,$locationid, $prevbal, $createdqty, $resid, $v_tpiCountag, $mode);";
	    $rs = $database->execute($sp);
	    return $rs;	
		
	}
	public function spCheckInvCountDocNo($database, $docno)
	{
		$sp = "Call spCheckInvCountDocNo('$docno');"; //echo $sp; exit();
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	/*public function spSelectInvCnt ($v_offset, $v_perpage, $vSearch)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spSelectInvCnt($v_offset, $v_perpage, '%$vSearch%');"; //echo $sp; exit();
		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	public function spSelectInvCntCount ($database, $vSearch)
	{
		$sp = "Call spSelectInvCntCount('%$vSearch%');"; //echo $sp; exit();
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectInvCntbyID($database, $id)
	{	

		$sp = "Call spSelectInvCntbyID($id);"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInvCntDetByID($database, $id, $mode)
	{
		$sp = "Call spSelectInvCntDetByID($id, $mode);"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	
/*	public function spSelectTPIPMG()
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
					
		$sp = "Call spSelectTPIPMG();";
		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	public function spSelectProductListWS($database, $v_parentid, $v_datecreate, $v_pmgid)
	{
		$sp = "Call spSelectProductListWS($v_parentid, '$v_datecreate', $v_pmgid);";
		//echo $sp; exit;
 		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectLocationWS($database,$wid)
	{				
		$sp = "Call spSelectLocationWS($wid);";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectRecordInvCount ($database, $v_offset, $v_perpage, $vSearch)
	{

		$sp = "Call spSelectRecordInvCount($v_offset, $v_perpage, '%$vSearch%');"; //echo $sp; exit();
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectRecordInvCountDetails($database, $vSearch, $icid, $warehouseid, $locationid)
	{		
		$sp = "Call spSelectRecordInvCountDetails('$vSearch%', $icid,$warehouseid, $locationid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
 /*	public function spSelectLocationCBO()
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
					
		$sp = "Call spSelectLocationCBO();";
		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	*/
	public function spUpdateRecordInvCountDetails($database, $icid, $qty)
	{
		$sp = "Call spUpdateRecordInvCountDetails($icid, $qty);";	
		//echo 	$sp ;exit;
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpadateInventoryCountStatus($database, $txnid)
	{
			
		$sp = "Call spUpadateInventoryCountStatus($txnid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectDealerForWriteOffApprove($database, $bid, $bcode)
	{
		$sp = "Call spSelectDealerForWriteOffApprove($bid, '%$bcode%');";	
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectInventoryCountUnconfirmed($database)
	{
		$sp = "Call spSelectInventoryCountUnconfirmed();";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteInventoryCountDetailsByInventoryCountID($database, $txnid)
	{
		$sp = "Call spDeleteInventoryCountDetailsByInventoryCountID($txnid);";	
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spUpdateInventoryCountHeaderByID($database, $txnid, $refid, $docno, $txndate, $whouseid, $remarks)
	{
		$sp = "Call spUpdateInventoryCountHeaderByID($txnid, $refid, '$docno', '$txndate', $whouseid, '$remarks');";		
  		$rs = $database->execute($sp);
	    return $rs;
	}
	
	/*public function spSelectInvCntCountConfirmed ($vSearch)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spSelectInvCntCountConfirmed('%$vSearch%');"; //echo $sp; exit();
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	/*public function spSelectRecordInvCountConfirmed ($v_offset, $v_perpage, $vSearch)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spSelectRecordInvCountConfirmed($v_offset, $v_perpage, '%$vSearch%');"; //echo $sp; exit();
		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}*/
	
	public function spSelectPaymentType ($database)
	{
		$sp = "Call spSelectPaymentType();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCommissionType ($database)
	{
		$sp = "Call spSelectCommissionType();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCampaign ($database)
	{
		$sp = "Call spSelectCampaign();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInvCountDet_print ($database, $tid, $wid, $lid, $sort)
	{

		$sp = "Call spSelectInvCountDet_print($tid, $wid, $lid, $sort);"; //echo $sp; exit();
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectInvCnt_print ($database, $tid)
	{
		$sp = "Call spSelectInvCnt_print($tid);"; //echo $sp; exit();
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectIGSDealersByIBMID ($database, $tid,$v_search)
	{
		$sp = "Call spSelectIGSDealersByIBMID($tid,'$v_search%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertORCommission($database, $orid, $commid, $totAmount, $unAppAmount)
	{
		$sp = "Call spInsertORCommission($orid, $commid, $totAmount, $unAppAmount);";			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCommissionTypeByID ($database, $tid)
	{
		$sp = "Call spSelectCommissionTypeByID($tid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCampaignByID ($database, $tid)
	{
		$sp = "Call spSelectCampaignByID($tid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCustomerBalanceByCommissionCampaignID ($database, $custid, $commid, $campid)
	{
		$sp = "Call spSelectCustomerBalanceByCommissionCampaignID($custid, $commid, $campid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	/*public function spUpdateCustomerCommissionOutstandingBalance ($tid)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spUpdateCustomerCommissionOutstandingBalance($tid);";
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	*/
	
	public function spUpdateCustomerPenaltyOutstandingBalance ($database,$cpid,$amount)
	{
		$mysqli = new mysqli (DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_errno());
			exit();
		}
		
		$sp = "Call spUpdateCustomerPenaltyOutstandingBalance($cpid,$amount);";
		
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;
	}
	
	public function spCheckSOHSO($database,$v_sessionID)
	{			
		$sp = "Call spCheckSOHSO('$v_sessionID');";
		$rs = $database->execute($sp);	
		return $rs;
	}
	public function spDeleteTmpSODetailsByProductID($database,$v_productID,$v_sessionID)
	{		
		$sp = "Call spDeleteTmpSODetailsByProductID($v_productID,'$v_sessionID');";
		$rs = $database->execute($sp);	
		return $rs;
	}
	
	public function spSelectStockLogBeginningBalance ($database, $prodid, $wareid, $year, $month)
	{
		$sp = "Call spSelectStockLogBeginningBalance($prodid, $wareid, '$year', '$month');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateEffectivePriceSODetails($database,$v_productID, $v_sessionID)
	{		
		$sp = "Call spUpdateEffectivePriceSODetails($v_productID, '$v_sessionID');";
		$rs = $database->execute($sp);		
		return $rs;
	}
	public function  spSelectIDSODetailsLimit($database,$v_productID,$v_sessionID, $v_limit)
	{
		$sp = "Call spSelectIDSODetailsLimit($v_productID,'$v_sessionID', $v_limit);";
		$rs = $database->execute($sp);	
		return $rs;
	}
	public function  spDeleteTmpSODetailsByID($database,$v_ID)
	{
		$sp = "Call spDeleteTmpSODetailsByID($v_ID);";
		$rs = $database->execute($sp);
		return $rs;
	}	

	public function spSelectIGSAjax($database, $search)
	{

		$sp = "Call spSelectIGSAjax($search);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectDefaultCustomerCode($database)
	{

		$sp = "Call spSelectDefaultCustomerCode();";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectCreditLimitByCustomerID($database,$v_customerID)
	{
		$sp = "Call spSelectCreditLimitByCustomerID($v_customerID);";											 
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectARBalanceByCustomerID($database,$v_customerID)
	{
		$sp = "Call spSelectARBalanceByCustomerID($v_customerID);";											 
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPenaltyByCustomerID($database,$v_customerID)
	{
		$sp = "Call spSelectPenaltyByCustomerID($v_customerID);";											 
		$rs =  $database->execute($sp);
		return $rs;
	}
	public function spSelectYTDbyCustomerID($database,$customerid,$year,$pmgid)
   {
       
      $sp = "Call spSelectYTDbyCustomerID($customerid,$year,$pmgid);";
      $rs = $database->execute($sp);
      return $rs; 
   } 
   public function spCheckSOHSOList($database,$v_sessionID)
	{		
		$sp = "Call spCheckSOHSOList('$v_sessionID');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInterfaceInventory($database, $txnDate)
	{
		$sp = "Call spSelectInterfaceInventory('$txnDate');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectZQT($database, $txnDate)
	{
		$sp = "Call spSelectInterfaceZQT('$txnDate');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectRHO($database, $txnDate)
	{
		$sp = "Call spSelectRHO('$txnDate');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInventoryRegisterPrint($database, $year, $month,$wareid)
	{
		$sp = "Call spSelectInventoryRegisterPrint( '$year', '$month',$wareid );";
		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectLocationByID($database, $txnid)
	{
		$sp = "Call spSelectLocationByID($txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteCycleCount ($database, $id, $empid)
	{
		$sp = "Call spDeleteCycleCount($id, $empid);"; 		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateInvAdjDetailsByID ($database, $id, $prodid, $soh, $cqty)
	{
		$sp = "Call spUpdateInvAdjDetailsByID($id, $prodid, $soh, $cqty);"; 		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spCheckExistingICProd($database,$mode, $InvCID, $prodid, $locid)
	{		
		$sp = "Call spCheckExistingICProd($mode, $InvCID, $prodid, $locid);"; 
	
	    $rs = $database->execute($sp);
	    return $rs;	
		
	}
	public function spSelectDiscountBracketbyAmount($database,$v_Amount,$v_pmgID)
	{		
		$sp = "Call spSelectDiscountBracketbyAmount($v_Amount,$v_pmgID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateCumulativeSales($database,$v_amount, $v_discountID , $v_ID)
	{		
		$sp = "Call spUpdateCumulativeSales($v_amount, $v_discountID , $v_ID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertAccountReceivable($database,$v_custID, $v_txnID, $v_amount)
	{		
		$sp = "Call  spInsertAccountReceivable($v_custID, $v_txnID, $v_amount);";
		//echo $sp ; exit;
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spCheckIfSubstitute($database,$v_productID)
	{		
		$sp = "Call spCheckIfSubstitute($v_productID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectProductSubstituteSO($database,$v_productID,$v_param)
	{		
		$sp = "Call spSelectProductSubstituteSO($v_productID,$v_param);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectProductListWS_print($database,  $v_prodlist)
	{
				
		$sp = "Call spSelectProductListWS_print('$v_prodlist');";
		
 		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectBankByBranchID($database)
	{		
		$sp = "Call spSelectBankByBranchID();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectBIRSeriesByTxnTypeID($database, $v_txntypeid)
	{		
		$sp = "Call spSelectBIRSeriesByTxnTypeID($v_txntypeid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateBIRSeriesByBranchID($database, $v_nextid, $v_txntypeid)
	{		
		$sp = "Call spUpdateBIRSeriesByBranchID($v_nextid, $v_txntypeid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertCumulativeSales($database,$v_custID, $v_month, $v_year, $v_discountID, $v_amount, $v_pmgID)
	{		
		$sp = "Call spInsertCumulativeSales($v_custID, $v_month, $v_year, $v_discountID, $v_amount, $v_pmgID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectServedItemsBackOrder($database,$v_txnID)
	{		
		$sp = "Call spSelectServedItemsBackOrder($v_txnID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectOpenItemsBackOrder($database,$v_txnID)
	{		
		$sp = "Call spSelectOpenItemsBackOrder($v_txnID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectChangePromoPopup ($database, $v_productid, $v_promoid, $v_session)
	{
		$sp = "Call spSelectChangePromoPopup($v_productid, $v_promoid, '$v_session');";
		$rs = $database->execute($sp);
		return $rs;	
	
	}
	
	public function spDeleteCustomerARBySalesInvoiceID($database, $v_txnid)
	{		
		$sp = "Call spDeleteCustomerARBySalesInvoiceID($v_txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateCumulativeSalesByCustomerID($database, $v_totcft, $v_totncft, $v_custid, $v_txndate)
	{		
		$sp = "Call spUpdateCumulativeSalesByCustomerID($v_totcft, $v_totncft, $v_custid, '$v_txndate');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSalesInvoiceByIDPDF($database, $siid)
	{

		$sp = "Call spSelectSalesInvoiceByIDPDF($siid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectCustomerDetails($database, $cid)
	{

		$sp = "Call spSelectCustomerDetails($cid);";	
		
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectProductSalesInvoice($database, $siid)
	{

		$sp = "Call spSelectProductSalesInvoice($siid);";
		
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spUpdatetmpSODetailsPromo($database, $v_productid, $v_oldpromoid, $v_newpromoid, $v_promoentitlementdetailid, $v_session)
	{

		$sp = "Call spUpdatetmpSODetailsPromo($v_productid, $v_oldpromoid, $v_newpromoid, $v_promoentitlementdetailid,'$v_session');";
		//echo $sp;
		//exit;
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spInsertSIDetailsBackOrder($database, $v_SIID,$v_productID,$v_promoID,$v_promotype,$v_pmgID,$v_unitprice,$v_qty,$v_totalamt,$v_line,$v_producttype)
	{

		$sp = "Call spInsertSIDetailsBackOrder($v_SIID,$v_productID,$v_promoID,$v_promotype,$v_pmgID,$v_unitprice,$v_qty,$v_totalamt,$v_line,$v_producttype);";
		//echo $sp;
		//exit;
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spInsertSIBackOrder($database,$v_custID, $v_soid , $v_docno, $v_txndate,$v_remarks,$v_grossamt,$v_totalcpi,$v_basicdisc, $v_totalcft, $v_totalncft,$v_addtldisc,$v_vatamt,$v_adpp, $v_netamt)
	{

		$sp = "Call spInsertSIBackOrder($v_custID, $v_soid , '$v_docno', '$v_txndate','$v_remarks',$v_grossamt,$v_totalcpi,$v_basicdisc, $v_totalcft, $v_totalncft,$v_addtldisc,$v_vatamt,$v_adpp, $v_netamt);";
		$rs = $database->execute($sp);
	    return $rs;	
	}
	public function spUpdateSODetailsBackOrder($database,$v_SOID,$v_productID,$qty,$ID)
	{

		$sp = "Call spUpdateSODetailsBackOrder($v_SOID,$v_productID,$qty,$ID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spCheckSOforOutstandingQty($database,$v_SOID)
	{

		$sp = "Call spCheckSOforOutstandingQty($v_SOID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectBranchOR($database)
	{
		$sp = "Call spSelectBranchOR();";	
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spGetBranchParameter($database)
	{
		$sp = "Call spGetBranchParameter();";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectOfficialReceiptByORID($database, $v_txnid)
	{
		$sp = "Call spSelectOfficialReceiptByORID($v_txnid);";	
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectOfficialReceiptDetailsByORID($database, $v_txnid)
	{
		$sp = "Call spSelectOfficialReceiptDetailsByORID($v_txnid);";	
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectInventoryOutSTADetailsbyID ($database, $txnid)
	{
		$sp = "Call spSelectInventoryOutSTADetailsbyID($txnid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spCheckInventoryStatus ($database)
	{
		$sp = "Call spCheckInventoryStatus();";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spUpdateInventoryStatus ($database, $v_cnt, $v_statid, $v_empid, $iid)
	{
		$sp = "Call spUpdateInventoryStatus($v_cnt, $v_statid, $v_empid, $iid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectCustomerIBM($database,$v_custID)
	{
		$sp = "Call spSelectCustomerIBM($v_custID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectInvCntDetAllBranches($database,$v_DocumentNo)
	{
		$sp = "Call spSelectInvCntDetAllBranches('$v_DocumentNo');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectMTDSI($database,$v_custID,$v_month,$year)
	{
		$sp = "Call spSelectMTDSI($v_custID,$v_month,$year);";		

		$rs = $database->execute($sp);
	    return $rs;	
	}
	
    public function spSelectYTDSI($database,$v_custID,$v_year)
	{
		$sp = "Call spSelectYTDSI($v_custID,$v_year);";		
		
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectSIUpcomingDues($database,$v_custID,$siid)
	{
		$sp = "Call spSelectSIUpcomingDues($v_custID,$siid);";
		$rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spUpdateInvTrandferDetDraft($database, $v_txnID, $v_prodid, $v_qty, $v_reason)
	{
		$sp = "Call spUpdateInvTrandferDetDraft($v_txnID, $v_prodid, $v_qty, $v_reason);";				
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectStockz($database, $offset, $RPP, $vSearch, $warehouseid, $location, $pmgid, $isId, $plid, $brdid)
	{
		$sp = "Call spSelectStockz($offset, $RPP, '$vSearch%', $warehouseid, $location, $pmgid, $isId, $plid, $brdid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectStockzCount($database, $vSearch, $warehouseid, $location, $pmgid, $isId, $plid, $brdid)
	{
		$sp = "Call spSelectStockzCount('$vSearch%', $warehouseid, $location, $pmgid, $isId, $plid, $brdid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectStatusStocks($database)
	{
		$sp = "Call spSelectStatusStocks();";	
		$rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectPaymentTypeOR($database)
	{
		$sp = "Call spSelectPaymentTypeOR();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectStockzPrint($database, $vSearch, $warehouseid,$location,$pmgid,$isId,$plid)
	{
		$sp = "Call spSelectStockzPrint('%$vSearch%', $warehouseid,$location,$pmgid,$isId,$plid);";		
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateRecordInvCount($database, $icid, $remarks)
	{
		$sp = "Call spUpdateRecordInvCount($icid, '$remarks');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCredittermsByCustID($database, $v_custID)
	{
		$sp = "Call spSelectCredittermsByCustID($v_custID);";		
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectUnconfirmedInvCntCount($database)
	{
		$sp = "Call spSelectUnconfirmedInvCntCount();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateInvTransferDetailsByID ($database, $id, $prodid, $soh)
	{
		$sp = "Call spUpdateInvTransferDetailsByID($id, $prodid, $soh);"; 		
		$rs = $database->execute($sp);
		return $rs;
	}
	/*
	public function spSelectProductforProdReportStatCnt ($database, $vSearch, $warehouseid, $plid, $pmgid, $location, $ref, $fromdate, $statid)
	{
		$sp = "Call spSelectProductforProdReportStatCnt('%$vSearch%', $warehouseid, $plid, $pmgid, $location, $ref, '$fromdate', $statid);";
		//echo $sp; exit;		
		$rs = $database->execute($sp);
		return $rs;
	}
	*/
	public function spSelectProductforProdReportStatCnt ($database, $vSearch, $warehouseid, $plid, $pmgid, $location, $ref, $fromdate)
	{
		$sp = "Call spSelectProductforProdReportStatCnt('%$vSearch%', $warehouseid, $plid, $pmgid, $location, $ref, '$fromdate');";
		//echo $sp; exit;		
		$rs = $database->execute($sp);
		return $rs;
	}
	/*
	public function spSelectProductforProdReportStat ($database,$offset, $RPP, $vSearch, $warehouseid,$plid,$pmgid,$location,$ref, $fromdate, $statid)
	{
		$sp = "Call spSelectProductforProdReportStat($offset, $RPP,'$vSearch%', $warehouseid,$plid,$pmgid,$location,$ref, '$fromdate', $statid);"; 
		//echo $sp; exit;		
		$rs = $database->execute($sp);
		return $rs;
	}
	*/
	
	public function spSelectProductforProdReportStat ($database,$offset, $RPP, $vSearch, $warehouseid,$plid,$pmgid,$location,$ref, $fromdate)
	{
		$sp = "Call spSelectProductforProdReportStat($offset, $RPP,'$vSearch%', $warehouseid,$plid,$pmgid,$location,$ref, '$fromdate');"; 
		//echo $sp; exit;		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	
	public function spSelectProdlistforProdRepStat ($database,$plid,$parentid, $search)
	{
		$sp = "Call spSelectProdlistforProdRepStat($plid,$parentid, '$search%');"; 
		//echo $sp; exit;		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSIRegisterPrint($database,  $fromdate, $todate, $search, $employee)
   	{
   		$sp = "Call spSelectSIRegisterPrint('$fromdate', '$todate', '%$search%', $employee);";
   		$rs = $database->execute($sp);
      	return $rs;
   	}
   	
   	public function spSelectRecordtInvCntCount($database, $vSearch)
	{
		$sp = "Call spSelectRecordtInvCntCount('%$vSearch%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectRecordUnconfirmedInvCount($database, $offset, $RPP, $vSearch)
	{
		$sp = "Call spSelectRecordUnconfirmedInvCount($offset, $RPP, '%$vSearch%');";
		$rs = $database->execute($sp);
		return $rs;
		
	}
	public function spSelectReasonForDMCM($database, $rid)
   	{
   		$sp = "Call spSelectReasonForDMCM($rid);"; 
   		$rs = $database->execute($sp);
      	return $rs;
   	}
   	
   	public function spSelectCreatedBySalesInvoice($database,  $fromdate, $todate,$search)
   	{
   		$sp = "Call spSelectCreatedBySalesInvoice('$fromdate', '$todate', '%$search%');"; 
   		$rs = $database->execute($sp);
      	return $rs;
   	}
	
	public function spSelectDMCMRegisterCount2($database, $fromdate, $rid)
	{
		$sp = "Call spSelectDMCMRegisterCount2('$fromdate', $rid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectBranchbyBranchParameter($database)
	{
		$sp = "Call spSelectBranchbyBranchParameter();";

		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectBirBackEndreport($database, $fromdate, $todate)
	{
		$sp = "Call spSelectBirBackEndreport('$fromdate', '$todate');";
		//echo $sp; exit;
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelecBirBackEndReportCount($database,$fromdate, $todate, $vmode)
	{
		$sp = "Call spSelecBirBackEndReportCount('$fromdate', '$todate', $vmode);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectOverdueAgingReport($database, $fromdate, $todate,  $custID, $search)
	{
		$sp = "Call spSelectOverdueAgingReport_test('$fromdate', '$todate', $custID, '%$search%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
public function spUpdateInventoryInOutConfirmHeader($database, $txnid, $remarks)
	{
		$sp = "Call spUpdateInventoryInOutConfirmHeader($txnid, '$remarks');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
 	public function spSelectNextDiscBracket($database,$v_ID,$v_pmgID)
   {
   		$sp = "Call spSelectNextDiscBracket($v_ID,$v_pmgID);"; 		
   		$rs = $database->execute($sp);
      	return $rs;
   	}
   	
public function spSelectEODBackOrder ($database)
	{
		$sp = "Call spSelectEODBackOrder();"; 		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectEODUpdateDealerStatus ($database)
	{
		$sp = "Call spSelectEODUpdateDealerStatus();"; 		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateEODDealerStatus($database, $customerID)
	{
		$sp = "Call spUpdateEODDealerStatus($customerID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spUpdateEODrcustomerpdaStatus($database, $customerID)
	{
		$sp = "Call spUpdateEODrcustomerpdaStatus($customerID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectEODProvisionalReceipt($database)
	{
		$sp = "Call spSelectEODProvisionalReceipt();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectEODCustomerPenalty($database)
	{
		$sp = "Call spSelectEODCustomerPenalty();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectEODRAR($database)
	{
		$sp = "Call spSelectEODRAR();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spCheckEODCustomerAccountsReceivable($database, $CustomerID, $SalesInvoiceID)
	{
		$sp = "Call spCheckEODCustomerAccountsReceivable($CustomerID, $SalesInvoiceID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spCheckEODCustomerPenalty($database, $CustomerID, $SalesInvoiceID)
	{
		$sp = "Call spCheckEODCustomerPenalty($CustomerID, $SalesInvoiceID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spInsertEODCustomerPenalty($database, $CustomerID, $SalesInvoiceID, $Amount, $OutstandingAmount)
	{
		$sp = "Call spInsertEODCustomerPenalty($CustomerID, $SalesInvoiceID, $Amount, $OutstandingAmount);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spInsertEODrcustomerpda($database, $CustomerID, $PDAID, $CreatedBy)
	{
		$sp = "Call spInsertEODrcustomerpda($CustomerID, $PDAID, $CreatedBy);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spUpdateEODCustomerPenalty($database, $CustomerID, $SalesInvoiceID, $Amount, $OutstandingAmount)
	{
		$sp = "Call spUpdateEODCustomerPenalty($CustomerID, $SalesInvoiceID, $Amount, $OutstandingAmount);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spInsertEODCustomerAccountsReceivable($database, $CustomerID, $SalesInvoiceID, $OutstandingAmount, $DaysDue)
	{
		$sp = "Call spInsertEODCustomerAccountsReceivable($CustomerID, $SalesInvoiceID, $OutstandingAmount, $DaysDue);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spUpdateEODCustomerAccountsReceivable($database, $CustomerID, $SalesInvoiceID, $OutstandingAmount, $DaysDue)
	{
		$sp = "Call spUpdateEODCustomerAccountsReceivable($CustomerID, $SalesInvoiceID, $OutstandingAmount, $DaysDue);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spDCCRRegister($database, $fromdate, $todate, $search)
	{
		$sp = "Call spDCCRRegister('$fromdate', '$todate', '%$search%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectSOAByCustomerID($database, $fromdate, $todate, $customerid)
	{
		$sp = "Call spSelectSOAByCustomerID('$fromdate', '$todate', $customerid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectAgingReportByCustomerID($database, $fromdate, $todate, $custid)
	{
		$sp = "Call spSelectAgingReportByCustomerID('$fromdate', '$todate', $custid);";
		$rs = $database->execute($sp);
		return $rs;
	} 

	public function spCheckBranchCode($database, $branchCode)
	{
		$sp = "Select ID from branch where Code = '$branchCode';";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	// public function spCheckDocInventoryinout($database, $docNo)
	// {
		// $sp = "SELECT ID 
					// FROM inventoryinout
					// WHERE DocumentNo = '$docNo'
					// AND MovementTypeID =(SELECT id FROM movementtype WHERE CODE='$movement_type');";
		// $rs = $database->execute($sp);
		// return $rs;
	// }
	
	
	public function spCheckDocInventoryinout($database,$docNo,$tobranch)
	{
		$sp = "SELECT ID 
					FROM inventoryinout
					WHERE DocumentNo = '$docNo'
					AND movementtypeid=1
					AND tobranchid=(SELECT id FROM branch WHERE CODE='$tobranch')
					";
		$rs = $database->execute($sp);
		return $rs;
	}
	
		public function spCheckDocInventoryinout_STA($database,$docNo,$movement_type,$frombranch,$tobranch)
	{

		if (($movementtype=='ITR')||($movementtype=='IRV')) {
			$destination_branch=$tobranch;
			$source_branch=$frombranch;
		}
		else{
			$destination_branch=$frombranch;
			$source_branch=$tobranch;
		}

		
		
		
		$sp = "SELECT ID 
					FROM inventoryinout
					WHERE DocumentNo = '$docNo'
					AND MovementTypeID =(SELECT id FROM movementtype WHERE CODE='$movement_type')
					AND branchid=(SELECT id FROM branch WHERE CODE='$source_branch')
					AND tobranchid=(SELECT id FROM branch WHERE CODE='$destination_branch')
					;";
					
		$rs = $database->execute($sp);
		return $rs;
	}
	
	
	
			// $sp = "SELECT ID 
					// FROM inventoryinout
					// WHERE DocumentNo = '$docNo'
					// AND MovementTypeID =(SELECT id FROM movementtype WHERE CODE='$movement_type');";
		// $rs = $database->execute($sp);
	
	// public function spCheckDocInventoryinout($database, $docNo)
	// {
		// $sp = "Select ID from inventoryinout where DocumentNo = '$docNo';";
		// $rs = $database->execute($sp);
		// return $rs;
	// }
	
	
	public function spInsertCompany ($database, $id, $company)
	{
		$sp = "Call spInsertCompany($id, '$company');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}

	public function spSelectDealerCompany ($database, $id)
	{
		$sp = "Call spSelectDealerCompany($id);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	
	public function spSelectApplicationFile ($database, $id)
	{
		$sp = "Call spSelectApplicationFile($id);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}		
	public function spUpdateEffectivityDateSI ($database, $v_txnID,$v_duration )
	{
		$sp = "Call spUpdateEffectivityDateSI($v_txnID,$v_duration);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectSODetailsID($database,$v_productID, $v_SOID)
	{
		$sp = "Call spSelectSODetailsID($v_productID, $v_SOID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spDeletePromo($database, $id)
	{
		$sp = "Call spDeletePromo($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPDARARCode($database)
	{
		$sp = "Call spSelectPDARARCode();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectBankInfo($database, $id)
	{
		$sp = "Call spSelectBankInfo($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertUpdateBankInfo($database, $id, $bircorid, $birorid, $vatid, $comid)
	{
		$sp = "Call spInsertUpdateBankInfo($id, $bircorid, $birorid, $vatid, $comid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spDeletePromo2($database, $prmid, $entid, $mode)
	{
		$sp = "Call spDeletePromo2($prmid, $entid, $mode);";
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertDeliveryReceipt($database,$v_SOID)
	{
		$sp = "Call spInsertDeliveryReceipt($v_SOID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertDeliveryReceiptDetails($database,$v_DRID,$v_SOID)
	{
		$sp = "Call spInsertDeliveryReceiptDetails($v_DRID,$v_SOID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spCheckIfHoliday($database,$v_date)
	{
		$sp = "Call spCheckIfHoliday('$v_date');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spCheckApplicableIncentives($database,$sessionID)
	{
		$sp = "Call spCheckApplicableIncentives('$sessionID');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectIncentivesHeader($database,$sessionID)
	{
		$sp = "Call spSelectIncentivesHeader('$sessionID');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectIncentiveEntitlementDetails($database,$sessionID,$v_promoID)
	{
		$sp = "Call spSelectIncentiveEntitlementDetails('$sessionID',$v_promoID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateORPrntCntr($database,$prntcntr,$orid)
	{
		$sp = "Call spUpdateORPrntCntr($prntcntr,$orid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateSIPrntCntr($database,$prntcntr,$orid)
	{
		$sp = "Call spUpdateSIPrntCntr($prntcntr,$orid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectAdvPOStart($database,$date)
	{
		$sp = "Call spSelectAdvPOStart('$date');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPurchaseDateProductList($database,$session)
	{
		$sp = "Call spSelectPurchaseDateProductList('$session');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoDatebySOID($database,$soid)
	{
		$sp = "Call spSelectPromoDatebySOID($soid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectProdSubstitute($database,$id, $search, $param)
	{
		$sp = "Call spSelectProdSubstitute($id,'$search%',$param);";
		//echo $sp; exit;
		$rs = $database->execute($sp);
		return $rs;
	}
    public function spSelectProductListSubs ($database,$plid,$parentid, $search)
	{
		$sp = "Call spSelectProductListSubs($plid,$parentid, '$search%');"; 
		//echo $sp; exit;		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertProductSubstitute($database,$prodid,$subsid,$chk,$sdate,$edate)
	{
		$sp = "Call spInsertProductSubstitute($prodid,$subsid,$chk,'$sdate','$edate');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertSOHeaderDraft($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,$v_termsID,$gsutypeID)
	{		
		$sp = "Call spInsertSOHeaderDraft($v_CustomerID,'$v_DocumentNo',$v_EmployeeID,$v_WarehouseID,$v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,$v_termsID,$gsutypeID);";					
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectUpdateCampaignInfo($database,$search)
	{
		$sp = "Call spSelectUpdateCampaignInfo('%$search%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectUpdateCampaignInfoByID($database,$id)
	{
		$sp = "Call spSelectUpdateCampaignInfoByID($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectStatusListByStatusTypeID($database,$id)
	{
		$sp = "Call spSelectStatusListByStatusTypeID($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertCampaign($database,$code,$name,$startDate,$toDate,$advPOStartDate,$advPOEndDate,$status)
	{
		$sp = "Call spInsertCampaign('$code','$name','$startDate','$toDate','$advPOStartDate','$advPOEndDate',$status);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateCampaign($database,$id,$code,$name,$startDate,$toDate,$advPOStartDate,$advPOEndDate,$status)
	{
		$sp = "Call spUpdateCampaign($id,'$code','$name','$startDate','$toDate','$advPOStartDate','$advPOEndDate',$status);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectExistingCampaign($database,$code)
	{
		$sp = "Call spSelectExistingCampaign('$code');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectExistingCampaign2($database,$id,$code)
	{
		$sp = "Call spSelectExistingCampaign2($id,'$code');";
			
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteCampaign($database,$id)
	{
		$sp = "Call spDeleteCampaign($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectBrochures($database,$id,$search)
	{
		$sp = "Call spSelectBrochures($id,'$search');";
		//echo $sp; exit;
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertUpdateBrochure($database, $id, $code, $name, $nopage, $collateral, $startdate, $enddate, $height, $width, $statusid, $refid)
	{
		$sp = "Call spInsertUpdateBrochure($id, '$code', '$name', '$nopage', $collateral, '$startdate', '$enddate', $height, $width, $statusid, $refid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertInventoryNewProduct($database)
	{
		$sp = "Call spInsertInventoryNewProduct();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPColor($database)
	{
		$sp = "Call spSelectPColor();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPStyle($database)
	{
		$sp = "Call spSelectPStyle();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPSubForm($database)
	{
		$sp = "Call spSelectPSubForm();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCollateralType($database)
	{
		$sp = "Call spSelectCollateralType();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectBrochureLayout($database, $id)
	{
		$sp = "Call spSelectBrochureLayout($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectLayoutType($database)
	{
		$sp = "Call spSelectLayoutType();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPageType($database, $v_search, $v_id)
	{
		$sp = "Call spSelectPageType('%$v_search%', $v_id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertBrochureDetails($database, $id, $pagenum, $pagetype, $pagelayout)
	{
		$sp = "Call spInsertBrochureDetails($id, $pagenum, $pagetype, $pagelayout);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spDeleteBrochureDetails($database, $id)
	{
		$sp = "Call spDeleteBrochureDetails($id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectPromoCode($database, $vSearch, $brochureid)
	{
		$sp = "Call spSelectPromoCode('$vSearch%', $brochureid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertAddPageDetails($database, $bid, $content, $type)
	{
		$sp = "Call spInsertAddPageDetails($bid, '$content', $type);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spCheckIfTransactionIsLocked ($database, $table, $txnid)
	{
		$sp = "Call spCheckIfTransactionIsLocked('$table', $txnid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spUpdateLockStatus ($database, $table, $locked, $lockedby, $txnid)
	{
		$sp = "Call spUpdateLockStatus('$table', $locked, $lockedby, $txnid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectBrochureLayoutLimit($database, $id, $offset, $perpage)
	{
		$sp = "Call spSelectBrochureLayoutLimit($id, $offset, $perpage);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertMTDBySI($database,$v_MTDCFT, $v_MTDNCFT , $v_YTDCFT, $v_YTDNCFT, $v_NextLevelCFT , $v_NextLevelNCFT , $v_TxnID)
	{		
		$sp = "Call spInsertMTDBySI($v_MTDCFT, $v_MTDNCFT , $v_YTDCFT, $v_YTDNCFT, $v_NextLevelCFT , $v_NextLevelNCFT , $v_TxnID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUnlockLockedTransactions ($database)
	{
		$sp = "Call spUnlockLockedTransactions();";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	
	public function spSelectProductDMPaging($database,$id,$offset, $perpage, $search)
	{
		$sp = "Call spSelectProductDMPaging($id,$offset, $perpage,'%$search%');";
	
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spInsertBrochurePage($database, $bid, $pageno, $promoid, $changed)
	{
		$sp = "Call spInsertBrochurePage($bid, $pageno, $promoid, $changed);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectProductListInv($database, $pid, $wid)
	{
		$sp = "Call spSelectProductListInv($pid, $wid);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectProductListInventoryTransfer2($database, $pid, $wid)
	{
		$sp = "Call spSelectProductListInventoryTransfer2($pid, $wid);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spDeleteExistProdSubs($database, $pid)
	{
		$sp = "Call spDeleteExistProdSubs($pid);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertTmpSingleLinePromo($database, $promocode, $promodesc, $promosdate, $promoedate, $buyintype, $buyinminqty, $buyinminamt, $buyinplevel, $buyinpcode, $buyinpdesc, $buyinreqtype, $buyinleveltype, $enttype, $entqty, $entprodcode, $entproddesc, $entdetqty, $entdetprice, $entdetsavings, $entdetpmg, $isplusplan)
	{
		$sp = 'Call spInsertTmpSingleLinePromo("'.$promocode.'", "'.$promodesc.'", "'.$promosdate.'", "'.$promoedate.'", '.$buyintype.', '.$buyinminqty.', '.$buyinminamt.', '.$buyinplevel.', "'.$buyinpcode.'", "'.$buyinpdesc.'", '.$buyinreqtype.', '.$buyinleveltype.', '.$enttype.', '.$entqty.', "'.$entprodcode.'", "'.$entproddesc.'", '.$entdetqty.', '.number_format($entdetprice,2,'.','').', '.number_format($entdetsavings,2,'.','').', '.$entdetpmg.', '.$isplusplan.');';
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectTmp_PromoSingleLine($database)
	{
		$sp = "Call spSelectTmp_PromoSingleLine();";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spCheckPromoIfExists($database, $promocode)
	{
		$sp = "Call spCheckPromoIfExists('$promocode');";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectPromoByCode2($database, $code)
	{
		$sp = "Call spSelectPromoByCode2('$code');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spCheckIfExistPromoBuyIn($database, $promoid, $type, $prodid)
	{
		$sp = "Call spCheckIfExistPromoBuyIn($promoid, $type, $prodid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spCheckIfExistPromoEntitlement($database, $buyinid, $type)
	{
		$sp = "Call spCheckIfExistPromoEntitlement($buyinid, $type);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spCheckIfExistPromoEntitlementDetails($database, $entid, $prodid)
	{
		$sp = "Call spCheckIfExistPromoEntitlementDetails($entid, $prodid);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdatePromoHeaderByID($database, $promoid, $desc, $start, $end, $isplusplan)
	{
		$sp = "Call spUpdatePromoHeaderByID($promoid, '$desc', '$start', '$end', $isplusplan);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdatePromoBuyInByID($database, $buyinparentID, $promoid, $type, $minqty, $minamt, $maxqty, $maxamt, $plevelid, $pid, $preqid, $start, $end, $leveltype)
	{
		$sp = "Call spUpdatePromoBuyInByID($buyinparentID, $promoid, $type, $minqty, $minamt, $maxqty, $maxamt, $plevelid, $pid, $preqid, '$start', '$end', $leveltype)";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdatePromoEntitlementByID($database, $id, $buyinid, $type, $qty)
	{
		$sp = "Call spUpdatePromoEntitlementByID($id, $buyinid, $type, $qty);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdatePromoEntitlementDetailsByID($database, $id, $entitlementid, $prodid, $qty, $effprice, $savings, $pmgid)
	{
		$sp = "Call spUpdatePromoEntitlementDetailsByID($id, $entitlementid, $prodid, $qty, $effprice, $savings, $pmgid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectValuationReport_Print($database, $vSearch, $warehouseid,$location,$pmgid,$plid,$dteAsOf)
	{	
		$sp = "Call spSelectValuationReport_Print('%$vSearch%', $warehouseid,$location,$pmgid,$plid,'$dteAsOf')";	
		$rs = $database->execute($sp);
		//echo $sp;
		return $rs;	
	}	

	public function spSelectCustomerCount($database, $custID, $search)
	{
		$sp = "Call spSelectCustomerCount($custID,'%$search%');";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectCustomerCreateDealer ($database,$v_offset, $v_perpage, $custID, $search)
	{
		$sp = "Call spSelectCustomerCreateDealer($v_offset, $v_perpage, $custID,'%$search%');";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spInsertTmpMultiLineBuyinPromo($database, $promocode, $promodesc, $promosdate, $promoedate, $buyintype, $buyinminqty, $buyinminamt, $buyinplevel, $buyinpcode, $buyinpdesc, $buyinreqtype, $buyinleveltype, $isplusplan)
	{
		$sp = "Call spInsertTmpMultiLineBuyinPromo('$promocode', '$promodesc', '$promosdate', '$promoedate', $buyintype, $buyinminqty, $buyinminamt, $buyinplevel, '$buyinpcode', '$buyinpdesc', $buyinreqtype, $buyinleveltype, $isplusplan);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertTmpMultiLineEntitlementPromo($database, $promocode, $promodesc, $promosdate, $promoedate, $enttype, $entqty, $entprodcode, $entproddesc, $entdetqty, $entdetprice, $entdetsavings, $entdetpmg)
	{
		$sp = "Call spInsertTmpMultiLineEntitlementPromo('$promocode', '$promodesc', '$promosdate', '$promoedate', $enttype, $entqty, '$entprodcode', '$entproddesc', $entdetqty, $entdetprice, $entdetsavings, $entdetpmg);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectTmp_PromoMultiline_Buyin($database)
	{
		$sp = "Call spSelectTmp_PromoMultiline_Buyin();";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectTmp_PromoMultiline_Entitlement($database)
	{
		$sp = "Call spSelectTmp_PromoMultiline_Entitlement();";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectParentPromoBuyIn($database , $promoid)
	{
		$sp = "Call spSelectParentPromoBuyIn($promoid);";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectProductToUpload($database , $code)
	{
		$sp = "Call spSelectProductToUpload('$code');";
		$rs = $database->execute($sp);
		return $rs;		
	}

	public function spSelectProductKit ($database, $v_search)
	{

		$sp = "Call spSelectProductKit('%$v_search%');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spSelectProductKitByID($database, $v_ID)
	{

		$sp = "Call spSelectProductKitByID($v_ID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spSelectProductComponentAjax($database, $vsearch)
	{

		$sp = "Call spSelectProductComponentAjax('$vsearch%');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spInsertProductKit($database,$v_kitID, $v_componentID, $v_componentQty, $v_startdate, $v_enddate)
	{
		$sp = "Call spInsertProductKit($v_kitID, $v_componentID, $v_componentQty, '$v_startdate', '$v_enddate');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spSelectProductKitComponentDetails($database,$v_kitID)
	{

		$sp = "Call spSelectProductKitComponentDetails($v_kitID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spDeleteProductKitByID($database,$v_kitID)
	{

		$sp = "Call spDeleteProductKitByID($v_kitID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectBankIDByBranch($database)
	{

		$sp = "Call spSelectBankIDByBranch();";		
	    $rs = $database->execute($sp);
	    return $rs;	
	}	


	public function spSelectCustomerPenaltyByCustomerID($database, $fromdate, $todate, $customerid)
	{
		$sp = "Call spSelectCustomerPenaltyByCustomerID('$fromdate', '$todate', $customerid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectApproveAdjustmentCount ($database, $vSearch)
	{
		$sp = "Call spSelectApproveAdjustmentCount('%$vSearch%');"; 
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectApproveAdjustment ($database, $v_offset, $v_perpage, $vSearch)
	{
		$sp = "Call spSelectApproveAdjustment($v_offset, $v_perpage, '%$vSearch%');"; //echo $sp; exit();
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spApproveMiscTransaction ($database, $id, $empid)
	{
		$sp = "Call spApproveMiscTransaction($id, $empid);"; 		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectCampagnMonthID($database)
	{
		$sp = "Call spSelectCampagnMonthID();";
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spSelectTotalNCFTCFTByMonth($database)
	{
		$sp = "Call spSelectTotalNCFTCFTByMonth();";			
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectCustomerCommissionByCustomerID($database,$v_customerID , $v_monthID,$v_commissiontype)
	{
		$sp = "Call spSelectCustomerCommissionByCustomerID($v_customerID , $v_monthID,$v_commissiontype);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectServiceFeeByAmount($database,$v_amount , $v_pmgID)
	{
		$sp = "Call spSelectServiceFeeByAmount($v_amount , $v_pmgID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateProvisionalReceiptStatus($database, $v_ID)
	{
		$sp = "Call spUpdateProvisionalReceiptStatus($v_ID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spCheckCustomerPenalty($database, $v_customerID)
	{
		$sp = "Call spCheckCustomerPenalty($v_customerID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateCustomerCommission($database,$v_amount,$v_ID,$addtlBonus)
	{
		$sp = "Call spUpdateCustomerCommission($v_amount,$v_ID,$addtlBonus);";
		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertCustomerCommission($database, $v_branchID,$v_customerID,$v_campaignmonthID,$v_amount,$v_commissiontype)
	{
		$sp = "Call spInsertCustomerCommission($v_branchID , $v_customerID, $v_campaignmonthID, $v_amount,$v_commissiontype )";
		
		$rs = $database->execute($sp);
		return $rs;
	}

	public function spCheckPromoIfExistsByPromoID($database, $promocode, $promoid)
	{
		$sp = "Call spCheckPromoIfExistsByPromoID('$promocode', $promoid);";
		$rs = $database->execute($sp);
		return $rs;		
	}
	public function spSelectOutstandingInvoicesForAutoSettlementLimit($database, $customerId,$term)
	{
		$sp = "Call spSelectOutstandingInvoicesForAutoSettlementLimit( $customerId,$term);";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spInsertTmpOverlayBuyinPromo($database, $v_PromoCode, $v_PromoDescription, $v_PromoStartDate, $v_PromoEndDate, $v_MaxAvailNonGSU, $v_MaxAvailDirectGSU, $v_MaxAvailIndirectGSU, $v_BuyinPurchaseReqTypeID, $v_BuyinTypeID, $v_BuyinProductLevelID, $v_BuyinProductCode, $v_BuyinProductDescription, $v_Criteria, $v_MinimumValue, $v_BuyinStartDate, $v_BuyinEndDate, $v_BuyinLevelTypeID, $v_IsIncentive, $isplusplan)
	{
		$sp = "Call spInsertTmpOverlayBuyinPromo('$v_PromoCode', '$v_PromoDescription', '$v_PromoStartDate', '$v_PromoEndDate', $v_MaxAvailNonGSU, $v_MaxAvailDirectGSU, $v_MaxAvailIndirectGSU, $v_BuyinPurchaseReqTypeID, $v_BuyinTypeID, $v_BuyinProductLevelID, '$v_BuyinProductCode', '$v_BuyinProductDescription', $v_Criteria, $v_MinimumValue, '$v_BuyinStartDate', '$v_BuyinEndDate', $v_BuyinLevelTypeID, $v_IsIncentive, $isplusplan);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertTmpOverlayEntitlementPromo($database, $promocode, $promodesc, $promosdate, $promoedate, $enttype, $entqty, $entprodcode, $entproddesc, $entdetqty, $entdetprice, $entdetsavings, $entdetpmg)
	{
		$sp = "Call spInsertTmpOverlayEntitlementPromo('$promocode', '$promodesc', '$promosdate', '$promoedate', $enttype, $entqty, '$entprodcode', '$entproddesc', $entdetqty, $entdetprice, $entdetsavings, $entdetpmg);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectTmp_PromoOverlay_Buyin($database)
	{
		$sp = "Call spSelectTmp_PromoOverlay_Buyin();";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectTmp_PromoOverlay_Entitlement($database)
	{
		$sp = "Call spSelectTmp_PromoOverlay_Entitlement();";
		$rs = $database->execute($sp);
		return $rs;		
	}
	
	public function spSelectPromoByCode3($database, $code)
	{
		$sp = "Call spSelectPromoByCode3('$code');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spCheckIfExistPromoAvailment($database, $promoid, $type)
	{
		$sp = "Call spCheckIfExistPromoAvailment($promoid, $type);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdatePromoAvailment($database, $promoid, $type, $maxavail)
	{
		$sp = "Call spUpdatePromoAvailment($promoid, $type, $maxavail);";		
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectRecordInvCountDetailsByCountTag($database, $vSearch, $icid, $warehouseid, $locationid)
	{		
		$sp = "Call spSelectRecordInvCountDetailsByCountTag('$vSearch%', $icid,$warehouseid, $locationid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectRecordInvCountDetailsByItemCode($database, $vSearch)
	{		
		$sp = "Call spSelectRecordInvCountDetailsByItemCode('$vSearch%');";
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectCampaignMonth ($database)
	{
		$sp = "Call spSelectCampaignMonth();";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectInvCntDetByDocumentNo($database,$v_DocumentNo)
	{
		$sp = "Call spSelectInvCntDetByDocumentNo('$v_DocumentNo');";
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
public function spSelectProductListLocation($database, $vSearch)
	{		
		$sp = "Call spSelectProductListLocation('$vSearch%');";
		$rs = $database->execute($sp);
		return $rs;	
	}	
	public function spSelectLocation($database)
	{		
		$sp = "Call spSelectLocation();";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectSalesInvoiceDateDiff($database,$v_ID)
	{		
		$sp = "Call spSelectSalesInvoiceDateDiff($v_ID);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectCustomerPDACode($database)
	{		
		$sp = "Call spSelectCustomerPDACode();";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsertCustomerPDA($database,$v_customerID, $v_pdaID)
	{		
		$sp = "Call spInsertCustomerPDA($v_customerID, $v_pdaID);";
		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectCustomerPDARARCodes($database,$param,$pdaID,$custFrom,$custTo)
	{		
		$sp = "Call spSelectCustomerPDARARCodes($param,$pdaID,'$custFrom','$custTo');";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectProductCount($database, $vSearch)
	{		
		$sp = "Call spSelectProductCount('%$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectListProductSubstitute($database,$v_offset, $v_perpage , $v_search)
	{		
		$sp = "Call spSelectListProductSubstitute($v_offset, $v_perpage , '%$v_search%');";
			
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsertPDARARCodes($database,$v_ID , $v_customerID , $v_userID , $v_pdaID)
	{		
		$sp = "Call spInsertPDARARCodes($v_ID , $v_customerID , $v_userID , $v_pdaID);";			
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectCustomerCreditLimit($database,$v_param, $v_creditfrom , $v_creditto)
	{		
		$sp = "Call spSelectCustomerCreditLimit($v_param, $v_creditfrom , $v_creditto);";					
		$rs = $database->execute($sp);
		return $rs;	
	}	
	public function spCheckProductPricing($database,$v_id)
	{		
		$sp = "Call spCheckProductPricing($v_id);";			
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsertProductPricing($database,$v_productID,$v_pmgID)
	{		
		$sp = "Call spInsertProductPricing($v_productID,$v_pmgID);";			
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spGetCreditLimitFactors($database)
	{
		$sp = "Call spGetCreditLimitFactors();";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
		
	public function spInsertCreditLimitDetails($database, $v_oldcreditlimit , $v_newcreditlimit, $v_customerID)
	{
		$sp = "Call spInsertCreditLimitDetails($v_oldcreditlimit , $v_newcreditlimit, $v_customerID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
		
	public function spCheckCreditLimitDetails($database, $v_customerID)
	{
		$sp = "Call spCheckCreditLimitDetails($v_customerID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spUpdateCreditLimitDetails($database,$v_ID)
	{
		$sp = "Call spUpdateCreditLimitDetails($v_ID);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	
	public function spSelectCreditLimitDetails($database, $v_branchID, $v_brachCode)
	{
		$sp = "Call spSelectCreditLimitDetails($v_branchID, '%$v_brachCode%');";			
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	
	public function spDeletePromoDetailsByPromoID($database, $v_promoid)
	{
		$sp = "Call spDeletePromoDetailsByPromoID($v_promoid);";			
	    $rs = $database->execute($sp);
	    return $rs;	
	}	
	public function spSelectStartDatebyAdvPOEnd($database, $v_date)
	{
		$sp = "Call spSelectStartDatebyAdvPOEnd('$v_date');";							
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectAdvPOList($database, $v_date)
	{
		$sp = "Call spSelectAdvPOList('$v_date');";							
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spUpdateSITxnDateAdvPO($database, $v_date,$v_ID)
	{
		$sp = "Call spUpdateSITxnDateAdvPO('$v_date',$v_ID);";				
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectIBMList($database)
	{
		$sp = "Call spSelectIBMList();";				
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectBCRbyIBMID($database,$v_ID,$v_date)
	{
		$sp = "Call spSelectBCRbyIBMID($v_ID,'$v_date');";				
	    $rs = $database->execute($sp);	    
	    return $rs;	
	}
	public function spSelectCountRecruit($database,$v_ID,$v_date)
	{
		$sp = "Call spSelectCountRecruit($v_ID,'$v_date');";				
	    $rs = $database->execute($sp);	    
	    return $rs;	
	}
	public function spSelectAffiliatedBranch($database,$v_ID)
	{
		$sp = "Call spSelectAffiliatedBranch($v_ID);";				
	    $rs = $database->execute($sp);	    
	    return $rs;	
	}
	
	public function spSelectCampaignByIDList($database, $idlist)
	{
		$sp = "Call spSelectCampaignByIDList(". "'$idlist'". ");";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spInsertBrochureCampaign($database, $brochureid, $campaignid)
	{
		$sp = "Call spInsertBrochureCampaign($brochureid, $campaignid);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spDeleteBrochureCampaign($database, $brochureid, $campaignid)
	{
		$sp = "Call spDeleteBrochureCampaign($brochureid, $campaignid);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectCampaignByBrochureID($database, $brochureid)
	{
		$sp = "Call spSelectCampaignByBrochureID($brochureid);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectLinkedPromosAndProducts($database, $brochuredetailsid)
	{
		$sp = "Call spSelectLinkedPromosAndProducts($brochuredetailsid);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectBrochureDetailsByBrochureID($database, $brochureid)
	{
		$sp = "Call spSelectBrochureDetailsByBrochureID($brochureid);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectBrochureDetailsByPageID($database, $id)
	{
		$sp = "Call spSelectBrochureDetailsByPageID($id);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spInsertBrochureProduct($database, $bdetid, $prodid, $promoid)
	{
		$sp = "Call spInsertBrochureProduct($bdetid, $prodid, $promoid);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spUpdatePromoStatusByID($database, $promoid, $isinsert)
	{
		$sp = "Call spUpdatePromoStatusByID($promoid, $isinsert);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spDeleteBrochureProduct($database, $bdetid, $prodid, $promoid)
	{
		$sp = "Call spDeleteBrochureProduct($bdetid, $prodid, $promoid);";			
	    $rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spInsertPageType($database, $code, $name)
	{
		$sp = "Call spInsertPageType('$code', '$name');";
		$rs = $database->execute($sp);
	    return $rs;
		
	}
	
	public function spUpdatePageType($database, $id, $code, $name)
	{
		$sp = "Call spUpdatePageType($id, '$code', '$name');";	
		$rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectProductColors($database, $id)
	{
		$sp = "Call spSelectProductColors($id);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spInsertBrochureText($database, $bdid, $callout, $violator)
	{
		$sp = "Call spInsertBrochureText($bdid, '$callout', '$violator');";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spInsertBRochureProductInfo($database, $bdid, $pid, $hid, $wbid)
	{
		$sp = "Call spInsertBRochureProductInfo($bdid, $pid, $hid, $wbid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectBrochureText($database, $bdid)
	{
		$sp = "Call spSelectBrochureText($bdid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spDeleteBrochureCallOut($database, $bdid)
	{
		$sp = "Call spDeleteBrochureCallOut($bdid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spDeleteBrochureViolator($database, $bdid)
	{
		$sp = "Call spDeleteBrochureViolator($bdid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectProductInfoHeroed($database, $bdid)
	{
		$sp = "Call spSelectProductInfoHeroed($bdid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectProductInfoWornByModel($database, $bdid)
	{
		$sp = "Call spSelectProductInfoWornByModel($bdid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spDeleteBrochureProdInfoHeroed($database, $bdid)
	{
		$sp = "Call spDeleteBrochureProdInfoHeroed($bdid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spDeleteBrochureProdInfoWornByModel($database, $bdid)
	{
		$sp = "Call spDeleteBrochureProdInfoWornByModel($bdid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spCreateCollateralVersion($database, $id, $code)
	{
		$sp = "Call spCreateCollateralVersion($id, '$code');";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectNetFactor($database, $v_search, $v_id)
	{
		$sp = "Call spSelectNetFactor('%$v_search%', $v_id);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spInsertNetFactor($database, $v_pmgid, $v_nfactor, $v_sdate, $v_edate)
	{
		$sp = "Call spInsertNetFactor($v_pmgid, $v_nfactor, '$v_sdate', '$v_edate');";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spSelectStaffCount($database, $v_search, $txnid)
	{
		$sp = "Call spSelectStaffCount('%$v_search%', $txnid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spInsertStaffCount($database, $v_campaign, $v_staff, $v_active, $txnid)
	{
		$sp = "Call spInsertStaffCount($v_campaign, $v_staff, $v_active, $txnid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectCampaignByCollateral($database, $v_bid)
	{
		$sp = "Call spSelectCampaignByCollateral($v_bid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectPromoLinking($database, $v_search, $ptype, $date)
	{
		$sp = "Call spSelectPromoLinking('%$v_search%', $ptype, '$date');";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectBranchByPromoID($database, $v_pid)
	{
		$sp = "Call spSelectBranchByPromoID($v_pid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectBranchByPromoIDAndBranchID($database, $v_pid, $v_bid)
	{
		$sp = "Call spSelectBranchByPromoIDAndBranchID($v_pid, $v_bid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spInsertPromoBranchLinking($database, $v_pid, $v_bid)
	{
		$sp = "Call spInsertPromoBranchLinking($v_pid, $v_bid);";	
		$rs = $database->execute($sp);
		
	    return $rs;		
	}
	
	public function spDeleteBranchLinkingByPromoID($database, $v_pid)
	{
		$sp = "Call spDeleteBranchLinkingByPromoID($v_pid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectLinkedBrochureProductByPromoID($database, $v_pid)
	{
		$sp = "Call spSelectLinkedBrochureProductByPromoID($v_pid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spSelectCollateralReferenceByID($database, $v_bid, $type)
	{
		$sp = "Call spSelectCollateralReferenceByID($v_bid, $type);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spUpdateBrochureVersionStatus($database, $v_bid)
	{
		$sp = "Call spUpdateBrochureVersionStatus($v_bid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spCheckBrochureCodeIfExist($database, $v_code, $v_bid)
	{
		$sp = "Call spCheckBrochureCodeIfExist('$v_code', $v_bid);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	
	public function spUpdateProductInformation($database, $id, $pUCost, $pSize, $pLife, $pStyle, $pSubForm, $pColor)
	{

		$sp = "Call spUpdateProductInformation($id, $pUCost, '$pSize', '$pLife', $pStyle, $pSubForm, $pColor);";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	
	public function spSelectRequiredProductKit($database, $v_txnDate)
	{
		$sp = "Call spSelectRequiredProductKit('$v_txnDate');";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	public function spSelectListDealerApplicants($database)
	{
		$sp = "Call spSelectListDealerApplicants();";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	public function spCheckProductKitRequirement($database,$v_customerID,$v_productID)
	{
		$sp = "Call spCheckProductKitRequirement($v_customerID,$v_productID);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	public function spSelectAccumalatedSIDealerApplicants($database,$v_customerID)
	{
		$sp = "Call spSelectAccumalatedSIDealerApplicants($v_customerID);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	public function spSelectCreditLimitParameters($database)
	{
		$sp = "Call spSelectCreditLimitParameters();";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	public function spUpdateCreditLimitParameters($database,$v_salesquota,$v_creditincrease,$v_enabled)
	{
		$sp = "Call spUpdateCreditLimitParameters($v_salesquota,$v_creditincrease,$v_enabled);";	
		$rs = $database->execute($sp);
	    return $rs;		
	}
	public function spInsertCreditLimitParameters($database,$v_salesquota,$v_creditincrease,$v_enabled)
	{
		$sp = "Call spInsertCreditLimitParameters($v_salesquota,$v_creditincrease,$v_enabled);";		
		$rs = $database->execute($sp);
	    return $rs;		
	}
	public function spInsertCustomerAppointedStatus($database,$v_customerID,$v_userID)
	{
		$sp = "Call spInsertCustomerAppointedStatus($v_customerID,$v_userID);";		
		$rs = $database->execute($sp);
	    return $rs;		
	}
	public function spSelectListofDealersAutoTeminate($database,$v_txnDate)
	{
		$sp = "Call spSelectListofDealersAutoTeminate('$v_txnDate');";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertAutoTerminateDealer($database,$v_customerID,$v_userID)
	{
		$sp = "Call spInsertAutoTerminateDealer($v_customerID,$v_userID);";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectDGSbyCustomer($database,$v_txnDate)
	{
		$sp = "Call spSelectDGSbyCustomer('$v_txnDate');";	
		//echo $sp;	
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectLastEOM($database)
	{
		$sp = "Call spSelectLastEOM();";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectBCRbyCustomerID($database,$v_customerID,$v_month,$v_year)
	{
		$sp = "Call spSelectBCRbyCustomerID($v_customerID,$v_month,$v_year);";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertBrachCollectionRating($database,$v_customerID, $v_ibmID,$v_Year, $v_Month,$v_rating,$v_paidDGS, $v_dgs,$v_paidcft,$v_paidncft,$v_paidcpi,$v_dgscft,$v_dgsncft,$v_dgscpi)
	{
		$sp = "Call spInsertBrachCollectionRating($v_customerID, $v_ibmID,$v_Year, $v_Month,$v_rating,$v_paidDGS, $v_dgs,$v_paidcft,$v_paidncft,$v_paidcpi,$v_dgscft,$v_dgsncft,$v_dgscpi);";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}	
	public function spUpdateBrachCollectionRating($database,$v_customerID , $v_Year , $v_Month , $v_paidDGS , $v_dgs , $v_rating,$v_paidcft,$v_paidncft,$v_paidcpi,$v_dgscft,$v_dgsncft,$v_dgscpi)
	{
		$sp = "Call spUpdateBrachCollectionRating($v_customerID , $v_Year , $v_Month , $v_paidDGS , $v_dgs , $v_rating,$v_paidcft,$v_paidncft,$v_paidcpi,$v_dgscft,$v_dgsncft,$v_dgscpi);";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectCustomerCreditLimitUpdate($database,$v_txnDate)
	{
		$sp = "Call spSelectCustomerCreditLimitUpdate('$v_txnDate');";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectBRCbyCustomerID($database,$v_customerID,$v_txnMonth,$v_txnYear)
	{
		$sp = "Call spSelectBRCbyCustomerID($v_customerID,$v_txnMonth,$v_txnYear);";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spUpdateEOMParameters($database,$v_txnDate)
	{
		$sp = "Call spUpdateEOMParameters('$v_txnDate');";		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectBranchCustomerID($database,$v_customerID)
	{
		$sp = "Call spSelectBranchCustomerID($v_customerID);";			
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spUpdateCreditLimit($database,$v_customerID,$v_creditLimit)
	{
		$sp = "Call spUpdateCreditLimit($v_customerID,$v_creditLimit);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectApprovedCL($database)
	{
		$sp = "Call spSelectApprovedCL();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSalesTransactionsInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spSalesTransactionsInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spCollectionHeaderInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spCollectionHeaderInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spCollectionTransactionsInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spCollectionTransactionsInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spARHeaderInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spARHeaderInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spARTransactionsInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spARTransactionsInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spDMCMHeaderInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spDMCMHeaderInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spDMCMTransactionsInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spDMCMTransactionsInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSalesInvoiceHeaderInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spSalesInvoiceHeaderInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSalesInvoiceDetailsInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spSalesInvoiceDetailsInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spRHOInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spRHOInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spZQTInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spZQTInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInventoryTransactionsInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spInventoryTransactionsInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spVDSInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spVDSInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectSalesInvoicesByCustomerID($database,$customerID)
	{
		$sp = "Call spSelectSalesInvoicesByCustomerID($customerID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectARByInvoiceIDWO($database,$v_txnID)
	{
		$sp = "Call spSelectARByInvoiceIDWO($v_txnID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spUpdateSIforWO($database,$v_txnID)
	{
		$sp = "Call spUpdateSIforWO($v_txnID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spUpdateARforWO($database,$v_txnID)
	{
		$sp = "Call spUpdateARforWO($v_txnID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertDMCMforWO($database,$v_customerID, $v_DocNo,$v_amount)
	{
		$sp = "Call spInsertDMCMforWO($v_customerID, '$v_DocNo',$v_amount);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertDMCMDetailforWO($database,$v_dmcmid, $v_siid,$v_amount)
	{
		$sp = "Call spInsertDMCMDetailforWO($v_dmcmid, $v_siid,$v_amount);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectPDACodeforWO($database,$v_customerID)
	{
		$sp = "Call spSelectPDACodeforWO($v_customerID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertPDARARCodeforWO($database,$v_type,$v_customerID)
	{
		$sp = "Call spInsertPDARARCodeforWO($v_type,$v_customerID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSalesInvoiceBSHDetailsInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spSalesInvoiceBSHDetailsInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}	
	public function spSelectApprovedWO($database)
	{
		$sp = "Call spSelectApprovedWO();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spUpdateWriteOffStatus($database,$v_txnID)
	{
		$sp = "Call spUpdateWriteOffStatus($v_txnID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectCustomerIBD($database)
	{
		$sp = "Call spSelectCustomerIBD();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spCheckifIBD($database)
	{
		$sp = "Call spCheckifIBD();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectSalesInvoiceCMforIBD($database)
	{
		$sp = "Call spSelectSalesInvoiceCMforIBD();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertDMCMforIBD($database,$v_customerID, $v_DocNo,$v_amount)
	{
		$sp = "Call spInsertDMCMforIBD($v_customerID, '$v_DocNo',$v_amount);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertDealerPromotion($database,$v_customerID,$v_fromCustomerType, $v_toCustomerType)
	{
		$sp = "Call spInsertDealerPromotion($v_customerID,$v_fromCustomerType,$v_toCustomerType);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectMaxCountTag($database,$v_txnID)
	{
		$sp = "Call spSelectMaxCountTag($v_txnID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	
	public function spTotalCountUploadedTmpRHODetails($database)
	{
		$sp = "select count(tpi_tmprhoID) cnt from tpi_tmprhodetails"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spTotalCountCreatedTmpRHODetails($database)
	{
		$sp = "select count(tpi_tmprhoID) cnt from tpi_tmprhodetails where ProductID is not null"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spRecordsNotCreatedTmpRHODetails($database)
	{
		$sp = "select * from tpi_tmprhodetails where ProductID is null"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spTotalCountUploadedTmpSTADetails($database)
	{
		$sp = "select count(tpi_tmpstaID) cnt from tpi_tmpstadetails"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spTotalCountCreatedTmpSTADetails($database)
	{
		$sp = "select count(tpi_tmpstaID) cnt from tpi_tmpstadetails where ProductID is not null"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spRecordsNotCreatedTmpSTADetails($database)
	{
		$sp = "select * from tpi_tmpstadetails where ProductID is null"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spTotalCountUploadedTmpProdMaster($database)
	{
		$sp = "select ID from tpi_tmpprodmaster;"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spTotalCountCreatedTmpProdMaster($database)
	{
		$sp = "select cnt cnt from
				(
  					select tmp.ID cnt from tpi_tmpprodmaster tmp inner join product pr on pr.ID = tmp.ProductID
  					union all
  					select distinct tpm.ID cnt from tpi_tmpprodmaster tpm left join product p on p.Code = tpm.ProductLineCode inner join (select ID from product where productlevelid = 1) a where p.ID is null
  					union all
  					select distinct tpm.ID from tpi_tmpprodmaster tpm inner join product p on p.Code = tpm.ProductLineCode inner join tpi_pmg tp on tp.Code = tpm.PMGCode where tpm.ProductID is null and tpm.UnitTypeID is not null and tpm.ProductTypeID is not null and tp.ID is not null
				) a"; 	
		$rs = $database->execute($sp);
		return $rs;
	}
	

	public function spTotalCountNotCreatedTmpProdMaster($database)
	{
		/* 
		MODIFIED BY: GINO LEABRES
		DATE: 1/17/2013
		OLD QUERY 
		select ItemCode, Description from tpi_tmpprodmaster where ID not in(
		select ID from
			(
				select tmp.ID from tpi_tmpprodmaster tmp inner join product pr on pr.ID = tmp.ProductID
				union all
				select tpm.ID from tpi_tmpprodmaster tpm left join product p on p.Code = tpm.ProductLineCode inner join (select ID from product where productlevelid = 1) a where p.ID is null
				union all
				select tpm.ID from tpi_tmpprodmaster tpm inner join product p on p.Code = tpm.ProductLineCode inner join tpi_pmg tp on tp.Code = tpm.PMGCode where tpm.ProductID is null and tpm.UnitTypeID is not null and tpm.ProductTypeID is not null and tp.ID is not null
			) a
		)*/
		
		$sp = "select ItemCode, Description from tpi_tmpprodmaster where ID not in(
					select tmp.ID from tpi_tmpprodmaster tmp inner join product pr on pr.ID = tmp.ProductID
					union all
					select tpm.ID from tpi_tmpprodmaster tpm left join product p on p.Code = tpm.ProductLineCode inner join (select ID from product where productlevelid = 1) a where p.ID is null
					union all
					select tpm.ID from tpi_tmpprodmaster tpm inner join product p on p.Code = tpm.ProductLineCode inner join tpi_pmg tp on tp.Code = tpm.PMGCode where tpm.ProductID is null and tpm.UnitTypeID is not null and tpm.ProductTypeID is not null and tp.ID is not null
			   )";
		$rs = $database->execute($sp);
		return $rs;		
	}
	public function spSelectBranchEmployee($database, $id)
	{				
		$sp = "Call spSelectBranchEmployee($id);";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	public function spSelectBranchDepartment($database, $id)
	{				
		$sp = "Call spSelectBranchDepartment($id);";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	public function spCheckSOHSOListSubstitute($database,$v_sessionID,$v_productID,$v_substituteID)
	{
		$sp = "Call spCheckSOHSOListSubstitute('$v_sessionID',$v_productID,$v_substituteID);";			 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectBranchSize($database)
	{
		$sp = "Call spSelectBranchSize();";			 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectBranchDetails($database, $bid)
	{
		$sp = "Call spSelectBranchDetails($bid);";			 	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectIBMListSearch($database, $search, $ibmcode)
	{
		$sp = "Call spSelectIBMListSearch('%$search%', '%$ibmcode%');";				
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spCheckIfExistCountTag($database,$v_txnID,$v_productID,$v_locationID)
	{
		$sp = "Call spCheckIfExistCountTag($v_txnID,$v_productID,$v_locationID);";			
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spSelectProductListDG($database)
	{
		$sp = "Call spSelectProductListDG();";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectInvCntDetSortByID($database, $id, $sort, $prodline)
	{
		$sp = "Call spSelectInvCntDetSortByID($id, $sort, '%$prodline%');"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectProductListForWorksheet($database, $whid)
	{
		$sp = "Call spSelectProductListForWorksheet($whid);"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spCheckInvCountDocNoWarehouse($database, $docno, $whid)
	{
		$sp = "Call spCheckInvCountDocNoWarehouse('$docno', $whid);";
	    $rs = $database->execute($sp);
	    return $rs;	
	}
	public function spEditProductListForWorksheet($database, $whid, $icid, $datecreate)
	{
		$sp = "Call spEditProductListForWorksheet($whid, $icid, '$datecreate');"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectInvCntDetAddtlByID($database, $id, $sort)
	{
		$sp = "Call spSelectInvCntDetAddtlByID($id, $sort);"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spDeleteProductListForPrinting($database, $txnid, $sessionid)
	{
		$sp = "Call spDeleteProductListForPrinting($txnid, '$sessionid');"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spPrintCountTags($database, $txnid, $prodid, $sessionid)
	{
		$sp = "Call spPrintCountTags($txnid, $prodid, '$sessionid');"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectInvCntDetByIDForPrinting($database, $txnid, $sessionid)
	{
		$sp = "Call spSelectInvCntDetByIDForPrinting($txnid, '$sessionid');"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectCustomerStatus($database, $customerid)
	{
		$sp = "Call spSelectCustomerStatus($customerid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spDeletePromoDetails($database, $promotype, $promoid, $prodlist, $isbuyin)
	{
		$sp = "Call spDeletePromoDetails($promotype, $promoid, '$prodlist', $isbuyin);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectDeletedPromoDetails($database, $detailtype, $promoid, $prodlist)
	{
		$sp = "Call spSelectDeletedPromoDetails($detailtype, $promoid, '$prodlist');";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsetDeletedPromoDetails($database, $promotypeid, $detailtype, $detailid)
	{
		$sp = "Call spInsetDeletedPromoDetails($promotypeid, $detailtype, $detailid);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spDeletePromoDetailsBranch($database)
	{
		$sp = "Call spDeletePromoDetailsBranch();";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectIBMCandidacy($database,$v_monthFrom,$v_monthTo)
	{
		$sp = "Call spSelectIBMCandidacy('$v_monthFrom','$v_monthTo');";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectDGSBCRByCustomerID($database,$v_customerID,$v_month,$v_year)
	{
		$sp = "Call spSelectDGSBCRByCustomerID($v_customerID,$v_month,$v_year);";	
		//echo $sp."<br>";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectCountRecruits($database,$v_recruiterID,$v_year,$v_month)
	{
		$sp = "Call spSelectCountRecruits($v_recruiterID,$v_year,$v_month);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectBranchName($database)
	{
		$sp = "Call spSelectBranchName();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spUpdateDealerStatus($database,$v_customerID,$v_statusID)
	{
		$sp = "call spUpdateDealerStatus($v_customerID,$v_statusID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectProductDMList($database, $prodID, $search, $offset, $perpage)
	{
		$sp = "Call spSelectProductDMList($prodID, '%$search%', $offset, $perpage);";
		$rs = $database->execute($sp);
		return $rs;
	}
       	public function spSelectProductDMLists($database, $prodID, $search)
	{
		$sp = "Call spSelectProductDMList($prodID, '%$search%');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spApproveWriteOffStatus($database, $v_txnID, $v_userid, $v_statid)
	{
		$sp = "Call spApproveWriteOffStatus($v_txnID, $v_userid, $v_statid);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertPDARARCodeDisApprovedWriteOff($database, $v_customerID, $v_userid)
	{
		$sp = "Call spInsertPDARARCodeDisApprovedWriteOff($v_customerID, $v_userid);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}	
	public function spInsertCandidacyBonusDetails($database, $v_BCRID, $v_CFTRate, $v_NCFTRate , $v_CFTEarnedBonus , $v_NCFTEarnedBonus , $v_TotalEarnedBonus , $v_VAT, $v_Tax , $v_TotalNetOfTax , $v_TotalApplied , $v_ForPayment , $v_Recruits)
	{
		$sp = "Call spInsertPDARARCodeDisApprovedWriteOff($v_customerID, $v_userid);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spCheckUser($database,$v_username, $v_password)
	{
		$sp = "Call spCheckUser('$v_username', '$v_password');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}		
	public function spSelectProductDMDynamicField ($database, $prodID, $fieldid)
	{
		$sp = "Call spSelectProductDMDynamicField($prodID, $fieldid);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spInsertNewRepeat($database, $v_date)
	{
		$sp = "Call spInsertNewRepeat('$v_date');";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spInsertActualSales($database, $v_date)
	{
		$sp = "Call spInsertActualSales('$v_date');";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spInsertBrochureView($database, $v_date)
	{
		$sp = "Call spInsertBrochureView('$v_date');";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spInsertVWBanding($database, $v_date)
	{
		$sp = "Call spInsertVWBanding('$v_date');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectLastLLReportsProcess($database)
	{
		$sp = "Call spSelectLastLLReportsProcess();";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertRollingForcast($database, $v_date)
	{
		$sp = "Call spInsertRollingForcast('$v_date');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertActualSalesPerProductLinePY($database, $v_date)
	{
		$sp = "Call spInsertVWBanding('$v_date');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateProductImage($database, $v_fname, $v_ID)
	{
		$sp = "Call spUpdateProductImage('$v_fname',$v_ID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectPromoType($database)
	{
		$sp = "Call spSelectPromoType();";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spCountRecruits($database,$v_txndate,$v_customerID )
	{
		$sp = "Call spCountRecruits('$v_txndate',$v_customerID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectStatsbyCustomerID($database,$v_customerID,$v_year,$v_month )
	{
		$sp = "Call spSelectStatsbyCustomerID($v_customerID,$v_year,$v_month);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateCustomerStats($database, $v_recruits , $v_actives , $v_customerID , $v_year, $v_month)
	{
		$sp = "Call spUpdateCustomerStats($v_recruits , $v_actives,$v_customerID,$v_year,$v_month);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spInsertCustomerStats($database, $v_customerID , $v_year, $v_month, $v_recruits , $v_actives )
	{
		$sp = "Call spInsertCustomerStats($v_customerID , $v_year, $v_month, $v_recruits , $v_actives);";
		$rs = $database->execute($sp);
		return $rs;
	}		
	public function spSelectCampaignMonthIDbyMonth($database, $v_month , $v_year)
	{
		$sp = "Call spSelectCampaignMonthIDbyMonth( $v_month , $v_year);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spCheckCustomerCommissionDetailed($database, $v_bcrid , $v_commissiontype)
	{
		$sp = "Call spCheckCustomerCommissionDetailed( $v_bcrid , $v_commissiontype);";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spSelectBrochuresAllStatus($database, $id, $search)
	{
		$sp = "Call spSelectBrochuresAllStatus($id, '$search');";
		$rs = $database->execute($sp);
		return $rs;
	}	
	public function spSelectCampaignStaffCount($database, $txnid)
	{
		$sp = "Call spSelectCampaignStaffCount($txnid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spUpdateCustomerCommissionDetailed($database,$v_ID, $v_cftbonus, $v_ncftbonus, $v_earnedbonus, $v_vatamount, $v_witholdingtax , $v_netoftax)
	{
		$sp = "Call spUpdateCustomerCommissionDetailed($v_ID, $v_cftbonus, $v_ncftbonus, $v_earnedbonus, $v_vatamount, $v_witholdingtax , $v_netoftax);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertCustomerCommissionDetailed($database,$v_commissiontype, $v_cftrate, $v_ncftrate , $v_cftearnedbonus, $v_ncftearnedbonus , $v_totalbonus, $v_vatamount , $v_taxamount ,$v_netamount , $v_bcrID)
	{
		$sp = "Call spInsertCustomerCommissionDetailed($v_commissiontype, $v_cftrate, $v_ncftrate , $v_cftearnedbonus, $v_ncftearnedbonus , $v_totalbonus, $v_vatamount , $v_taxamount ,$v_netamount , $v_bcrID);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectIBMFF($database,$v_monthFrom,$v_monthTo)
	{
		$sp = "Call spSelectIBMFF('$v_monthFrom','$v_monthTo');";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectDGSBCRByIBMID($database,$v_customerID,$v_month,$v_year)
	{
		$sp = "Call spSelectDGSBCRByIBMID($v_customerID,$v_month,$v_year);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectMaxIBMCode($database)
	{
		$sp = "Call spSelectMaxIBMCode();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function  spUpdateMaxAvailmentbyPromoID($database,$v_promoID,$v_qty,$v_session)
	{
		$sp = "Call spUpdateMaxAvailmentbyPromoID($v_promoID,$v_qty,'$v_session');";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectProductListByLevelIDWOKit($database, $levelid, $vSearch)
	{
		$sp = "Call spSelectProductListByLevelIDWOKit($levelid, '$vSearch%');";		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function  spSelectBranchIDSeries($database)
	{
		$sp = "Call spSelectBranchIDSeries();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function  spCheckMonthsTermination($database,$customerid)
	{
		$sp = "Call spCheckMonthsTermination($customerid);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function  spUpdateTerminatedDealers($database,$v_customerID , $v_statusID)
	{
		$sp = "Call spUpdateTerminatedDealers($v_customerID , $v_statusID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function  spInsertViewStaff($database)
	{
		$sp = "Call spInsertViewStaff();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function  spSelectBrochuresByCampaignID($database, $txnid)
	{
		$sp = "Call spSelectBrochuresByCampaignID($txnid);";	
		$rs = $database->execute($sp);
		return $rs;	
	}	
	public function  spUpdateCLTerminated($database,$customerID)
	{
		$sp = "Call spUpdateCLTerminated($customerID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectBank($database, $txnid, $search)
	{
		$sp = "Call spSelectBank($txnid, '$search');";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectBankBranch($database, $bankid)
	{
		$sp = "Call spSelectBankBranch($bankid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectExistingBank($database, $id, $code)
	{
		$sp = "Call spSelectExistingBank($id, '$code');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertUpdateBank($database, $code, $name, $account, $id, $branch)
	{
		$sp = "Call spInsertUpdateBank('$code', '$name', '$account', $id, '$branch');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertUpdateBranchBank($database, $brid, $baid, $primary)
	{
		$sp = "Call spInsertUpdateBranchBank($brid, $baid, $primary);";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spInsertCampaignMonth($database, $code, $name, $cdate)
	{
		$sp = "Call spInsertCampaignMonth('$code', '$name', '$cdate');";	
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectBranchToLink($database, $id, $search)
	{				
		$sp = "Call spSelectBranchToLink($id,'$search');";
	    $rs = $database->execute($sp);
	    return $rs;
	}
	public function spSelectBranchBank($database, $branchid)
	{
		$sp = "Call spSelectBranchBank($branchid);";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function  spCheckifAdvPO($database)
	{
		$sp = "Call spCheckifAdvPO();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function  spSelectCustomerTypebyCustomerID($database,$customerID)
	{
		
		$sp = "Call spSelectCustomerTypebyCustomerID($customerID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function  spSelectGSUTypebyCustomerID($database,$customerID)
	{
		$sp = "Call spSelectGSUTypebyCustomerID($customerID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spRFDInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spRFDInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectParentDepartment($database, $id)
	{
		$sp = "Call spSelectParentDepartment($id);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spLOSInterfaceFile($database, $brid, $lastrun)
	{
		$sp = "Call spLOSInterfaceFile($brid, '$lastrun');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectFromDealerType($database)
	{
		$sp = "Call spSelectFromDealerType();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectToDealerType($database, $dealertypeid)
	{
		$sp = "Call spSelectToDealerType($dealertypeid);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectHoliday($database)
	{
		$sp = "Call spSelectHoliday();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectGSUNetwork($database, $ibmid)
	{
		$sp = "Call spSelectGSUNetwork($ibmid);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectInvCntDetPerLocation($database, $txnid, $prodid, $locationid)
	{
		$sp = "Call spSelectInvCntDetPerLocation($txnid, $prodid, $locationid);";
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectTotalORCashCheckPerDate($database, $date)
	{
		$sp = "Call spSelectTotalORCashCheckPerDate('$date');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectBranchCollDeposits($database, $date)
	{
		$sp = "Call spSelectBranchCollDeposits('$date');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertBranchCollectionDeposits($database, $colldate, $bankid, $depdate, $valno, $cash, $check, $checknos)
	{
		$sp = "Call spInsertBranchCollectionDeposits('$colldate', $bankid, '$depdate', '$valno', $cash, $check, '$checknos');";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectRHODiscrepancyReport($database, $offset, $perpage, $mode, $date, $bcode, $mtype)
	{
		$sp = "Call spSelectRHODiscrepancyReport($offset, $perpage, $mode, '$date', $bcode, $mtype);";
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	
	public function spSelectRHODiscrepancyReportv1($database, $offset, $perpage, $mode, $date, $edate, $bcode, $mtype)
	{
		$sp = "Call spSelectRHODiscrepancyReportv1($offset, $perpage, $mode, '$date', '$edate', $bcode, $mtype);";
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	
	
	public function spSelectNetUnpaidSIByCustomerID($database, $customerID)
	{
		$sp = "Call spSelectNetUnpaidSIByCustomerID($customerID);";			
		
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectItemAgingReportCount($database, $warehouseid, $plid, $search, $statid, $age ,$newdate)
	{	
		$sp = "Call spSelectItemAgingReportCount($warehouseid, $plid, '%$search%', $statid, '$age' ,'$newdate')";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectItemAgingReport($database, $offset, $perpage, $warehouseid, $plid, $search, $statid, $age , $newdate, $mode)
	{	
		$sp = "Call spSelectItemAgingReport($offset, $perpage, $warehouseid, $plid, '%$search%', $statid, '$age' , '$newdate', $mode)";	
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectWallToWallFreezeHistory($database, $mode, $offset, $perpage, $from, $to)
	{	
		$sp = "Call spSelectWallToWallFreezeHistory($mode, $offset, $perpage, '$from', '$to')";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectWallToWallAuditTrail($database, $mode, $offset, $perpage, $fdate, $tdate, $ftime, $ttime, $sortby)
	{	
		$sp = "Call spSelectWallToWallAuditTrail($mode, $offset, $perpage, '$fdate', '$tdate', '$ftime', '$ttime', $sortby)";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectInvCntDetByIDAndSearch($database, $id, $from, $to)
	{
		$sp = "Call spSelectInvCntDetByIDAndSearch($id, '$from', '$to');"; 
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectTopSellingProducts($database, $mode, $offset, $perpage, $fdate, $tdate, $ftime, $ttime, $prod)
	{	
		$sp = "Call spSelectTopSellingProducts($mode, $offset, $perpage, '$fdate', '$tdate', '$ftime', '$ttime', '%$prod%')";
		$rs = $database->execute($sp);
		return $rs;	
	}
public function spSelectEODDraftSO($database)
	{
		$sp = "Call spSelectEODDraftSO();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectBORptByProd($database,$filter,$branchID,$fromDate,$toDate,$productLine,$prodCode,$campaignID)
	{
		$sp = "Call spSelectBORptByProd($filter,$branchID,'$fromDate','$toDate','%$productLine%','%$prodCode%',$campaignID);";			
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectBORptBySO($database,$filter,$branchID,$fromDate,$toDate,$productLine,$prodCode,$campaignID)
	{
		$sp = "Call spSelectBORptBySO($filter,$branchID,'$fromDate','$toDate','%$productLine%','%$prodCode%',$campaignID);";	
		//echo $sp;		
		//exit;	
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectBORptByCustomer($database,$filter,$branchID,$fromDate,$toDate,$productLine,$prodCode,$campaignID)
	{
		$sp = "Call spSelectBORptByCustomer($filter,$branchID,'$fromDate','$toDate','%$productLine%','%$prodCode%',$campaignID);";	
		//echo $sp;
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spInsertBandingCompany($database)
	{
		$sp = "Call spInsertBandingCompany();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectDueARBalanceByCustomerID($database,$customerID)
	{
		$sp = "Call spSelectDueARBalanceByCustomerID($customerID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectIBMFFforServiceFee($database)
	{
		$sp = "Call spSelectIBMFFforServiceFee();";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectMotherBranchperIBMID($database,$v_customerID)
	{
		$sp = "Call spSelectMotherBranchperIBMID($v_customerID);";				
		$rs = $database->execute($sp);				
	    return $rs;		
	}
	public function spSelectDGSperIBMIDSFMainBranch($database,$v_customerID,$v_month,$v_year,$v_branchID)
	{
		$sp = "Call spSelectDGSperIBMIDSFMainBranch($v_customerID,$v_month,$v_year,$v_branchID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectListMotherIBM($database)
	{
		$sp = "Call spSelectListMotherIBM();";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectDaughterIBM($database,$v_customerID)
	{
		$sp = "Call spSelectDaughterIBM($v_customerID);";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectIBMNetworkbyIBMID($database, $custid)
	{	
		$sp = "Call spSelectIBMNetworkbyIBMID($custid)";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsertCopyToSOHeader($database,$v_customerID,$v_PHPSessionID)
	{
		$sp = "Call spInsertCopyToSOHeader($v_customerID,'$v_PHPSessionID');";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsertCopyToSODetails($database,$v_tmpSOID,$v_productID,$v_orderedQty)
	{
		$sp = "Call spInsertCopyToSODetails($v_tmpSOID,$v_productID,$v_orderedQty);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectCopyToSODetails($database,$v_tmpSOID)
	{
		$sp = "Call spSelectCopyToSODetails($v_tmpSOID);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectCopyToSOHeader($database,$v_tmpSOID)
	{
		$sp = "Call spSelectCopyToSOHeader($v_tmpSOID);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectSIDetailsApplyPayment($database,$v_txnID)
	{
		$sp = "Call spSelectSIDetailsApplyPayment($v_txnID);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spUpdateCustomerPenaltyApplyPayment($database,$v_txnID,$v_amount)
	{
		$sp = "Call spUpdateCustomerPenaltyApplyPayment($v_txnID,$v_amount);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectConsolidateBCRIBM($database,$v_year,$v_month)
	{
		$sp = "Call spSelectConsolidateBCRIBM($v_year,$v_month);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectOutputInterfaceFilesParam($database, $mode)
	{
		$sp = "Call spSelectOutputInterfaceFilesParam($mode);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spUpdateCustomerChanged($database, $v_customerID)
	{
		$sp = "Call spUpdateCustomerChanged($v_customerID);";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectBCRbyIBMIAffiliation($database,$v_txnDate, $v_customerID)
	{
		$sp = "Call spSelectBCRbyIBMIAffiliation('$v_txnDate',$v_customerID);";
		
		$rs = $database->execute($sp);
		return $rs;	
	}
public function spSelectListInvoiceProdExchange($database,$v_customerID, $v_search)
	{
		$sp = "Call spSelectListInvoiceProdExchange($v_customerID, '$v_search%');";
		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectReasonforProductExchange($database, $v_reasontypeID)
	{
		$sp = "Call spSelectReasonforProductExchange($v_reasontypeID);";
		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spDeleteTmpProdExchangebySessionID($database, $v_session)
	{
		$sp = "Call spDeleteTmpProdExchangebySessionID('$v_session');";
		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsertTmpProdExchange($database,$v_sessionID , $v_siid , $v_qty , $v_reason , $v_productID )
	{
		$sp = "Call spInsertTmpProdExchange('$v_sessionID' , $v_siid , $v_qty , $v_reason , $v_productID);";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectTmpProductExchangeHeader($database,$v_txnID ,$v_sessionID)
	{
		$sp = "Call spSelectTmpProductExchangeHeader($v_txnID,'$v_sessionID');";
		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectTmpProductExchangeDetails($database,$v_txnID , $v_sessionID)
	{
		$sp = "Call spSelectTmpProductExchangeDetails($v_txnID,'$v_sessionID');";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spCheckProdExchangeParameter($database,$v_txnID  )
	{
		$sp = "Call spCheckProdExchangeParameter($v_txnID);";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spCheckIfAvailableProdExchange($database,$v_prodID,$v_warehouseID  )
	{
		$sp = "Call spCheckIfAvailableProdExchange($v_prodID,$v_warehouseID);";		
		
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectListProductsAvailableforExchange($database,$v_search,$v_conditionID ,$v_productID ,$v_colorfield ,$v_colorvalue,$v_subformfield ,$v_subformvalue , $v_stylefield , $v_stylevalue ,$v_formfield,$v_formvalue)
	{
		$sp = "Call spSelectListProductsAvailableforExchange('$v_search%',$v_conditionID ,$v_productID ,$v_colorfield ,$v_colorvalue,$v_subformfield ,$v_subformvalue , $v_stylefield , $v_stylevalue ,$v_formfield,$v_formvalue);";
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectProductDetailsProdExchange($database,$v_productID)
	{
		$sp = "Call spSelectProductDetailsProdExchange($v_productID);";		
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsertProductExchangeHeader($database,$v_txnID,$v_userID, $statusID)
	{
		$sp = "Call spInsertProductExchangeHeader($v_txnID,$v_userID,$statusID);";		
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spInsertProductExchangeDetails($database,$v_txnID, $v_productID, $v_exchangedID,$v_qty , $v_reasonID)
	{
		$sp = "Call  spInsertProductExchangeDetails($v_txnID, $v_productID, $v_exchangedID,$v_qty , $v_reasonID);";		
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectAvailableInventoryForProductExchange($database, $v_productID,  $warehousID)
	{
		$sp = "Call  spSelectAvailableInventoryForProductExchange($v_productID,  $warehousID);";		
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectProductExchangeCount($database, $v_search, $v_fromdate, $v_todate)
	{
		$sp = "Call spSelectProductExchangeCount('%$v_search%', '$v_fromdate', '$v_todate');";		
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectProductExchange ($database, $v_offset, $v_perpage, $vSearch, $vfromdate, $vtodate)
	{
		$sp = "Call spSelectProductExchange($v_offset, $v_perpage, '%$vSearch%', '$vfromdate', '$vtodate')";
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectProductExchangeHeader($database,$v_txnID )
	{
		$sp = "Call spSelectProductExchangeHeader($v_txnID);";
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectProductExchangeDetails($database,$v_txnID )
	{
		$sp = "Call spSelectProductExchangeDetails($v_txnID);";
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	/* 
		**  Modified by: Gino C. Leabres **
		**  9.24.2012					 **
		**  ginophp@yahoo.com			 **
	*/
	/* START ********************************************************************************************************************/
	public function spSelectValuationReportv1($database, $offset, $RPP, $vSearch, $warehouseid,$location,$pmgid,$plid,$dteAsOf)
	{	
		
		$sp = "Call spSelectValuationReportv1($offset, $RPP,'%$vSearch%', $warehouseid,$location,$pmgid,$plid,'$dteAsOf')";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectValuationReportv2($database, $offset, $RPP, $vSearch, $warehouseid,$location,$pmgid,$plid,$dteAsOf)
	{	
		
		$sp = "Call spSelectValuationReportv2($offset, $RPP,'%$vSearch%', $warehouseid,$location,$pmgid,$plid,'$dteAsOf')";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectValuationReportCountv1 ($database, $vSearch, $warehouseid,$location,$pmgid,$plid,$dteAsOf)
	{	
		$sp = "Call spSelectValuationReportCountv1('%$vSearch%', $warehouseid,$location,$pmgid,$plid,'$dteAsOf')";	
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectItemAgingReportv1($database, $offset, $perpage, $warehouseid, $plid, $search, $statid, $age , $mode)
	{	
		$sp = "Call spSelectItemAgingReportv1($offset, $perpage, $warehouseid, $plid, '%$search%', $statid, '$age', $mode)";	
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spSelectProductforProdReportStatv1($database,$offset, $RPP, $vSearch, $warehouseid,$plid,$pmgid,$location,$ref, $statid)
	{
		$sp = "Call spSelectProductforProdReportStatv1($offset, $RPP,'$vSearch%', $warehouseid,$plid,$pmgid,$location,$ref, $statid);"; 
		//echo $sp; exit;		
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spSelectListProductsAvailableforExchangev1($database,$v_search,$v_conditionID ,$v_productID ,$v_colorfield ,$v_colorvalue,$v_subformfield ,$v_subformvalue , $v_stylefield , $v_stylevalue ,$v_formfield,$v_formvalue, $v_ParentID)
	{
		$sp = "Call spSelectListProductsAvailableforExchangev1('$v_search%',$v_conditionID ,$v_productID ,$v_colorfield ,$v_colorvalue,$v_subformfield ,$v_subformvalue , $v_stylefield , $v_stylevalue ,$v_formfield,$v_formvalue, $v_ParentID);";
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spCheckInventoryCount($database)
	{
		$sp = "Call spCheckInventoryCount();";
		//echo $sp;
		$rs = $database->execute($sp);
		return $rs;	
	}
	
	public function spUpdateProductExchangeHeaderv1($database,$txnID,$statusID)
	{
		$sp = "Call spUpdateProductExchangeHeaderv1('$txnID', $statusID);";
		//echo $sp;
		//die();
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spUpdateProductExchangeDetails($database,$prodExID, $prodID, $exchangeID, $qty, $reasonID)
	{
		$sp = "Call spUpdateProductExchangeDetails($prodExID, $prodID, $exchangeID, $qty, $reasonID);";
		//echo $sp;
		//die();
		$rs = $database->execute($sp);
		return $rs;	
	}
	public function spSelectContactEmployeev1($database, $contact_param)
	{
		$sp = "Call spSelectContactEmployeev1($contact_param);";
		$rs = $database->execute($sp);
		return $rs;
	}
	
	public function spUpdateInsertTmpProdMasterv1($database)
	{
		$sp = "Call spUpdateInsertTmpProdMasterv1();";
		$rs = $database->execute($sp);
		return $rs;
	}
	public function spDeleteExistingBank($database, $code)
	{
		$sp = "Call spDeleteExistingBank($code);";
		$rs = $database->execute($sp);
		return $rs;
	}
  
  function spLoadInventoryCountDetailsCache($database, $txn_id, $warehouse_id, $v_sort, $v_product_line) {
    
    $query="CALL spLoadInventoryCountDetailsCache($txn_id, $warehouse_id, $v_sort, $v_product_line);";
    $rs=$database->execute($query);
    return $rs;
  }
  
    public function spSelectNextSIDue($database, $vcusid){
        $sp = "CALL spSelectNextSIDue($vcusid)";
        $rs = $database->execute($sp);
        return $rs;
    }
	/* End ***********************************************************************************************************/
	//end 
}

$sp = new StoredProcedures();
