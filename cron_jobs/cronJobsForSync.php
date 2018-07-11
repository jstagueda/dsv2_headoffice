<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 28, 2013
 * @description: Different cron jobs process query statement for HO server.
 */
    include('includes.php');
    
    $query_jobs = array();
    
    //Variables used for specific branch CRON update files when sync was triggred...
    $action = $_GET['action'];
    $BranchCode = $_GET['BranchCode'];
    $BranchID = $_GET['BranchID'];
    
    $query_jobs['customer'] = "SELECT * FROM customer WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
    $query_jobs['customerdetails'] = "SELECT * FROM customerdetails WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
    $query_jobs['customeraccountsreceivable'] = "SELECT * FROM customeraccountsreceivable WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
    $query_jobs['customerpenalty'] = "SELECT * FROM customerpenalty WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
    $query_jobs['tpi_customerdetails'] = "SELECT * FROM tpi_customerdetails WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
    $query_jobs['tpi_rcustomerbranch'] = "SELECT tcb.* FROM tpi_rcustomerbranch tcb INNER JOIN customer cu ON cu.ID = tcb.CustomerID WHERE tcb.Changed = 1  AND LOCATE('-[BRANCH_ID]',tcb.`HOGeneralID`) > 0";
    $query_jobs['customercommission'] = "SELECT * FROM customercommission WHERE `Changed` = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
    
	$query_jobs['stocklog'] = "SELECT * FROM `stocklog` WHERE `Changed` = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1";
    $query_jobs['inventoryinout'] = "SELECT * FROM `inventoryinout` WHERE  LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1";
    $query_jobs['inventoryinoutdetails'] = "SELECT * FROM inventoryinoutdetails WHERE  LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1";
    $query_jobs['inventoryadjustment'] = "SELECT * FROM `inventoryadjustment` WHERE `Changed` = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1";
    $query_jobs['inventoryadjustmentdetails'] = "SELECT * FROM inventoryadjustmentdetails WHERE `Changed` = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1";
    
    $query_jobs['product'] = "SELECT `ID`,`Code`,REPLACE(`Name`,\"'\",'') `Name`,REPLACE(`ShortName`,\"'\",'') `ShortName`,
								REPLACE(`Description`,\"'\",'') `Description`,`ProductLevelID`,`ParentID`,`ProductTypeID`,`UnitTypeID`,
								`Lt`,`Rt`,`StatusID`,`EnrollmentDate`,`LastModifiedDate`,`Changed`,`LaunchDate`,`FileName`,`LastPODate` FROM `product` WHERE `Changed` = 1";
	
    $query_jobs['productcost'] = "SELECT * FROM `productcost` WHERE `Changed` = 1";
    $query_jobs['productdetails'] = "SELECT * FROM `productdetails` WHERE `Changed` = 1";
    $query_jobs['productpricing'] = "SELECT * FROM `productpricing` WHERE `Changed` = 1";
	
    $query_jobs['productsubstitute'] = "SELECT c.* FROM productsubslinkbranches a
										INNER JOIN `productsubstitute` b ON b.ID = a.ProductSubstituteID
										INNER JOIN productsubstitute c ON b.ProductID=c.ProductID	
										WHERE a.`Changed` = 1 and a.BranchID = [BRANCH_ID]";
	
	
    $query_jobs['productkit'] = "SELECT * FROM `productkit` WHERE `Changed` = 1";
    
    // $query_jobs['user'] = "SELECT * FROM `user` WHERE `Changed` = 1";
    $query_jobs['user'] = "SELECT * FROM `user` WHERE EmployeeID IN (
								SELECT a.ID FROM `employee` a 
								INNER JOIN `remployeebranch` b ON a.`ID` = b.`EmployeeID`
								WHERE a.`Changed` = 1 AND LOCATE('-[BRANCH_ID]',b.`BranchID`) > 0
						   )";
						   
    $query_jobs['user_permissions'] = "SELECT * FROM `user_permissions` WHERE `Changed` = 1";
    $query_jobs['area'] = "SELECT * FROM `area` WHERE `Changed` = 1";
    $query_jobs['holiday'] = "SELECT * FROM `holiday` WHERE `Changed` = 1";
    
    $query_jobs['loyaltypromo'] = "SELECT * FROM `loyaltypromo` WHERE `Changed` = 1";
    $query_jobs['loyaltypromoavailment'] = "SELECT * FROM `loyaltypromoavailment` WHERE `Changed` = 1";
    $query_jobs['loyaltypromobuyin'] = "SELECT * FROM `loyaltypromobuyin` WHERE `Changed` = 1";
    $query_jobs['loyaltypromoentitlement'] = "SELECT * FROM `loyaltypromoentitlement` WHERE `Changed` = 1";
	
    $query_jobs['promo'] = "SELECT * FROM `promo` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW())";
    $query_jobs['promoavailment'] = "SELECT * FROM `promoavailment` WHERE `Changed` = 1 AND PromoID in (SELECT ID FROM `promo` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW()))";
    $query_jobs['promobuyin'] = "SELECT * FROM `promobuyin` WHERE `Changed` = 1 and PromoID in (SELECT ID FROM `promo` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW()))";
	$query_jobs['promoentitlement'] = "SELECT * FROM `promoentitlement` WHERE `Changed` = 1 and PromoBuyinID in (SELECT ID FROM `promobuyin` WHERE `Changed` = 1 and PromoID in (SELECT ID FROM `promo` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW())))";
    $query_jobs['promoentitlementdetails'] = "SELECT * FROM `promoentitlementdetails` WHERE `Changed` = 1 and 
	PromoEntitlementID in (SELECT ID FROM `promoentitlement` WHERE `Changed` = 1 and PromoBuyinID in (SELECT ID FROM `promobuyin` WHERE `Changed` = 1 and PromoID in (SELECT ID FROM `promo` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW()))))";
	
    $query_jobs['promoincentives'] = "SELECT * FROM `promoincentives` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW())";
    $query_jobs['incentivespromobuyin'] = "
											SELECT * FROM `incentivespromobuyin` WHERE `Changed` = 1 AND PromoIncentiveID IN
											(SELECT ID FROM `promoincentives` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW()))
	";
    $query_jobs['incentivespromoentitlement'] = "SELECT * FROM `incentivespromoentitlement` WHERE `Changed` = 1
												AND IncentivesPromoBuyinID IN (
													SELECT ID FROM `incentivespromobuyin` WHERE `Changed` = 1 AND PromoIncentiveID IN
													(SELECT ID FROM `promoincentives` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW()))
												)";
    $query_jobs['incentivespromoavailemnt'] = "SELECT * FROM `incentivespromoavailemnt` WHERE `Changed` = 1 AND  PromoIncentiveID IN (
		SELECT ID FROM `promoincentives` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW())
	)";
    $query_jobs['incentivesspecialcriteria'] = "SELECT * FROM `incentivesspecialcriteria` WHERE `Changed` = 1
													AND IBuyinID IN (
													SELECT ID FROM `incentivespromobuyin` WHERE `Changed` = 1 AND PromoIncentiveID IN
													(SELECT ID FROM `promoincentives` WHERE `Changed` = 1 AND DATE(EndDate) >= DATE(NOW()))
												)";
	
	$query_jobs['specialpromo'] = "SELECT * FROM specialpromo WHERE `Changed` = 1";
	$query_jobs['specialpromobuyinandentitlement'] = "SELECT * FROM  specialpromobuyinandentitlement WHERE `Changed` = 1";
	
	
	//Branch Default Bank
    $query_jobs['bank'] = "SELECT * FROM `bank` WHERE `Changed` = 1";
	$query_jobs ['branchbank'] = "SELECT * FROM branchbank WHERE BranchID = [BRANCH_ID]";
	
    //Employee
	$query_jobs['employee'] = "	SELECT a.* FROM `employee` a 
								INNER JOIN `remployeebranch` b ON a.`ID` = b.`EmployeeID`
								WHERE a.`Changed` = 1 and LOCATE('-[BRANCH_ID]',b.`BranchID`) > 0";
    
	$query_jobs['remployeebranch'] = "SELECT * FROM `remployeebranch` WHERE `Changed` = 1 AND LOCATE('-[BRANCH_ID]',`BranchID`) > 0";
    
	$query_jobs['remployeeposition'] = "SELECT a.* FROM `remployeeposition` a
										INNER JOIN `remployeebranch` b ON a.`EmployeeID` = b.`EmployeeID`
										WHERE a.`Changed` = 1";
	
//    $query_jobs['rusertypemodulecontrol'] = "SELECT a.* FROM `rusertypemodulecontrol` a
//											 INNER JOIN `remployeebranch` b ON a.`EmployeeID` = b.`EmployeeID`
//											 WHERE a.`Changed` = 1";

	$query_jobs['rusertypemodulecontrol'] = "SELECT a.* FROM `rusertypemodulecontrol` a
											 INNER JOIN `usertype` b ON a.`UserTypeID` = b.`ID`
											 INNER JOIN `position` c ON c.`Code`=b.Code
											 INNER JOIN `remployeeposition` d ON c.ID=d.PositionID
											 INNER JOIN `remployeebranch` e ON e.ID=d.EmployeeID 
											 WHERE a.`Changed` = 1 AND LOCATE('-[BRANCH_ID]',e.`BranchID`) > 0";

    $query_jobs['tpi_document'] = "SELECT * FROM `tpi_document`";
	$query_jobs['tpi_documentdetails'] = "SELECT * FROM `tpi_documentdetails`";										 
	//Position
	$query_jobs['position'] = "SELECT * FROM `position` WHERE `Changed` = 1";										 
	$query_jobs['incentivetype'] = "SELECT * FROM `incentivetype` ";										 
    
	$query_jobs['discount'] = "SELECT * FROM `discount` WHERE `Changed` = 1";										 
    $query_jobs['tpi_fco'] = "SELECT * FROM `tpi_fco` WHERE `Changed` = 1";
    $query_jobs['campaign'] = "SELECT * FROM `campaign` WHERE `Changed` = 1";
    $query_jobs['campaignmonth'] = "SELECT * FROM `campaignmonth` WHERE `Changed` = 1";    
    $query_jobs['reasontype'] = "SELECT * FROM `reasontype` WHERE `Changed` = 1";
    $query_jobs['reason'] = "SELECT * FROM `reason` WHERE `Changed` = 1";
    $query_jobs['tpi_document'] = "SELECT * FROM `tpi_document` WHERE `Changed` = 1";
    $query_jobs['inventorymovementtype_reasons'] = "SELECT * FROM `inventorymovementtype_reasons` WHERE `Changed` = 1";
    
    $query_jobs['branch'] = "SELECT * FROM `branch` WHERE `Changed` = 1";
    $query_jobs['branchspecialactivity'] = "SELECT * FROM `branchspecialactivity` WHERE `Changed` = 1 AND `BranchID` = [BRANCH_ID]";
    $query_jobs['branchbank'] = "SELECT * FROM `branchbank` WHERE `Changed` = 1  AND `BranchID` = [BRANCH_ID]";
    $query_jobs['branchdepartment'] = "SELECT * FROM `branchdepartment` WHERE `Changed` = 1 AND `BranchID` = [BRANCH_ID]";
    $query_jobs['branchdetails'] = "SELECT * FROM `branchdetails` WHERE `Changed` = 1 AND `BranchID` = [BRANCH_ID]";
    
    $query_jobs['sfm_adhoc_cl'] = "SELECT * FROM `sfm_adhoc_cl` WHERE `Changed` = 1";
    $query_jobs['sfm_adhoc_cl_criterias'] = "SELECT * FROM `sfm_adhoc_cl_criterias` WHERE `Changed` = 1";
    $query_jobs['sfm_candidacy_matrix'] = "SELECT * FROM `sfm_candidacy_matrix` WHERE `Changed` = 1";
    $query_jobs['sfm_kpi_standards'] = "SELECT * FROM `sfm_kpi_standards` WHERE `Changed` = 1";
    $query_jobs['sfm_level'] = "SELECT * FROM `sfm_level` WHERE `Changed` = 1";
    $query_jobs['sfm_rank'] = "SELECT * FROM `sfm_rank` WHERE `Changed` = 1";
    
    $query_jobs['tpi_credit'] = "SELECT * FROM tpi_credit WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
    $query_jobs['tpi_creditlimitdetails'] = "SELECT * FROM tpi_creditlimitdetails WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
    $query_jobs['tpi_dealerwriteoff'] = "SELECT * FROM tpi_dealerwriteoff WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
	
    $query_jobs['affliatedibm'] = " SELECT CustomerID, CustomerCode, MBranchID, AFBranchID, MCustCode, CustomerTableInformation,
											SFMManagarTable,TPICredit	
									FROM `affliatedibm`  WHERE `Changed` = 1 and AFBranchID = [BRANCH_ID]";
	//customercommission here...
	$query_jobs['customercommission'] = "SELECT * FROM customercommission where Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";	
	
	// DAL..
	$query_jobs['dal'] = "SELECT * FROM `dal`";
	
	$query_jobs['igsreservation'] = "SELECT * FROM `igsreservation` WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
	$query_jobs['igsreservationdetails'] = "SELECT * FROM `igsreservationdetails` WHERE Changed = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0";
		
	//update query here...
	$update_query_jobs = array( "UPDATE `affliatedibm` SET `Changed` = 0 WHERE AFBranchID = [BRANCH_ID] AND Changed=1",
								"UPDATE `productsubslinkbranches` SET `Changed` = 0 WHERE BranchID = [BRANCH_ID] AND Changed=1",
								"UPDATE `customercommission` SET `Changed` = 0 WHERE BranchID = [BRANCH_ID] AND Changed=1",
								"UPDATE`inventoryinout` SET `Changed` = 0  WHERE  LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1",
								"UPDATE inventoryinoutdetails SET `Changed` = 0 WHERE  LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1",
								"UPDATE `inventoryadjustment` SET `Changed` = 0  WHERE `Changed` = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1",
								"UPDATE inventoryadjustmentdetails SET `Changed` = 0 WHERE `Changed` = 1 AND LOCATE(BINARY '-[BRANCH_ID]',CONCAT(' ',`HOGeneralID`,' ')) > 0 AND `Changed`=1");
	
	//Check action for specific CRON branch data files to update so no need to update all dir and files of all branches...
    if($action == 'SYNC_CRON_UPDATE'){
        if(!empty($BranchCode) && !empty($BranchID)){ //Make sure that variables are not empty to avoid error...
            cron_doQueryJobs($query_jobs,$BranchCode,$BranchID);
            //Move old files per branch...
            cronMoveCronDataFilesToArchive('',$BranchCode);
			//update tables here..
			SyncUpdateTable($update_query_jobs,$BranchID);
        }
    }else{
        //Get branches for creating DIR before writing data file...
        $branches = cronGetBranchesForDIRCreation(); //Get branches...

        //Process for creating cron data files...
        if($branches){
            foreach($branches as $bId => $bCode):
                cron_doQueryJobs($query_jobs,$bCode,$bId);
                //Move old files per branch...
                cronMoveCronDataFilesToArchive('',$bCode);
            endforeach;
        }
    }
?>