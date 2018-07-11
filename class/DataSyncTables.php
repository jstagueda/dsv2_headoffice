<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 25, 2013
 */
//**** NOTE ****
//If you add new table to in SOD or EOD data syncs, you need to add query in /cron_jobs/cronJobsForSync.php
//This should be done on both system files of HO and Branch.
    $FileToReadInHOForBranch = array();
    $HODBTblToReadInBranch = array();
    $MidDaySyncHOTablesForBranch = array();
    
	
    /*
    * Array of database tables to be read in HO server and sync in branch database tables.
    */
	#tpi_rcustomerdetails
    $FileToReadInHOForBranch = array('customer','customerdetails','customeraccountsreceivable','customerpenalty','tpi_customerdetails',
        'tpi_rcustomeribm','tpi_rcustomerbranch','customercommission','inventoryadjustment','inventoryadjustmentdetails','tpi_credit','tpi_creditlimitdetails',
        'user','productsubstitute','productkit','productcost','productdetails','productpricing','product','inventoryinout','inventoryinoutdetails',
        'area','holiday','incentivespromobuyin','incentivespromoentitlement','incentivespromoavailemnt','loyaltypromo','loyaltypromoavailment',
        'promo','promoavailment','promobuyin','promoentitlement','promoincentives','incentivesspecialcriteria','bank','loyaltypromobuyin',
        'loyaltypromoentitlement','promoentitlementdetails','employee','remployeebranch','remployeeposition',
        'tpi_dealerwriteoff','rusertypemodulecontrol','discount','tpi_fco','campaign','campaignmonth',
        'reasontype','tpi_document','inventorymovementtype_reasons','department','departmentlevel',
        'branch','branchbank','branchdepartment','branchdetails','branchspecialactivity',
        'sfm_adhoc_cl','sfm_adhoc_cl_criterias','sfm_kpi_standards','sfm_level','sfm_rank','affliatedibm','bankdepositslip','productexchange','productexchangedetails','dal',
		'igsreservation', 'igsreservationdetails');
    
    /*
    * Array of database tables to be read in Branch server and sync in HO database tables.
    */
	
   $HODBTblToReadInBranch = array('customer','customerdetails','customeraccountsreceivable','customerpenalty','cumulativesales','tpi_customerdetails',
            'tpi_rcustomeribm','tpi_rcustomerstatus','tpi_rcustomerbranch','tpi_rcustomerpda','tpi_customerstats','birseries','stocklog','inventory',
            'inventoryadjustment','inventorycount','inventoryinout','inventorytransfer','inventoryadjustmentdetails','inventorycountdetails',
            'inventoryinoutdetails','inventorytransferdetails','salesorder','salesinvoice','salesorderdetails','salesinvoicedetails',
            'deliveryreceipt','deliveryreceiptdetails','dmcm','dmcmdetails','tpi_credit','tpi_creditlimitdetails','tpi_dealerwriteoff',
            'tpi_dealerpromotion','tpi_dealertransfer','tpi_branchcollectionrating','officialreceipt','officialreceiptcash','officialreceiptcheck',
            'officialreceiptcommission','officialreceiptdeposit','officialreceiptdetails','customercommission',
            'applied_single_line_promo_entitlement_details','applied_multi_line_promo_entitlement_details','applied_overlay_promo_entitlement_details',
            'applied_incentive_entitlement_details','availed_applicable_promos','availed_applicable_incentives','sfm_manager','sfm_manager_networks',
            'tpi_sfasummary','tpi_sfasummary_pmg','affliatedibm','productexchange','productexchangedetails','customercommission_summary','bankdepositslip','staffcount',
			'advancepo','advancepodetails','igs_transactionjournal','birbackend', 'igsreservation', 'igsreservationdetails'
			);
	
	// HO NEED TO UPDATE..
	$HOTblNeedToUpdate = array('customer','customerdetails','customeraccountsreceivable','customerpenalty','cumulativesales','tpi_customerdetails',
            'tpi_rcustomeribm','tpi_rcustomerstatus','tpi_rcustomerbranch','tpi_rcustomerpda','tpi_customerstats','birseries','stocklog','inventory',
            'inventoryadjustment','inventorycount','inventoryinout','inventorytransfer','inventoryadjustmentdetails','inventorycountdetails',
            'inventoryinoutdetails','inventorytransferdetails','salesorder','salesinvoice','salesorderdetails','salesinvoicedetails',
            'deliveryreceipt','deliveryreceiptdetails','dmcm','dmcmdetails','tpi_credit','tpi_creditlimitdetails','tpi_dealerwriteoff',
            'tpi_dealerpromotion','tpi_dealertransfer','tpi_branchcollectionrating','officialreceipt','officialreceiptcash','officialreceiptcheck',
            'officialreceiptcommission','officialreceiptdeposit','officialreceiptdetails','customercommission',
            'applied_single_line_promo_entitlement_details','applied_multi_line_promo_entitlement_details','applied_overlay_promo_entitlement_details',
            'applied_incentive_entitlement_details','availed_applicable_promos','availed_applicable_incentives','sfm_manager','sfm_manager_networks',
            'tpi_sfasummary','tpi_sfasummary_pmg','bankdepositslip','productexchange','productexchangedetails', 'igsreservation', 'igsreservationdetails','tpi_documentdetails'
			//'staffcount'
			);
   
   /* Mid Day sync tables grouped */
   $MidDaySyncHOTablesForBranch['Accounts_and_Credentials'] = array('customer','customerdetails','customeraccountsreceivable',
                                                    'customerpenalty','tpi_customerdetails','tpi_rcustomerbranch','customercommission');
   $MidDaySyncHOTablesForBranch['Credits'] = array('tpi_credit','tpi_creditlimitdetails');
   $MidDaySyncHOTablesForBranch['Inventory'] = array('tpi_document','inventoryadjustment','inventoryadjustmentdetails','inventoryinout','inventoryinoutdetails');
   $MidDaySyncHOTablesForBranch['Products'] = array('productsubstitute','productkit','productcost','productdetails','productpricing','product');
   $MidDaySyncHOTablesForBranch['Incentives'] = array('incentivespromobuyin','incentivespromoentitlement','incentivespromoavailemnt','incentivesspecialcriteria');
   $MidDaySyncHOTablesForBranch['Loyalty_and_Promos'] = array('loyaltypromo','loyaltypromoavailment','promoentitlement','promo','promoavailment','promobuyin','promoincentives','loyaltypromobuyin',
                                                  'loyaltypromoentitlement','promoentitlementdetails');
   $MidDaySyncHOTablesForBranch['Sales_Force'] = array('sfm_adhoc_cl','sfm_adhoc_cl_criterias','sfm_kpi_standards','sfm_level','sfm_rank');
   $MidDaySyncHOTablesForBranch['Holiday_&_Calendar'] = array('tpi_fco','holiday');
   $MidDaySyncHOTablesForBranch['Campaign'] = array('campaign','campaignmonth');
   $MidDaySyncHOTablesForBranch['Employees'] = array('employee','remployeebranch','remployeeposition');
   $MidDaySyncHOTablesForBranch['Department'] = array('department','departmentlevel');
   $MidDaySyncHOTablesForBranch['Branch_Miscellaneous'] = array('branch','branchbank','branchdepartment','branchdetails','branchspecialactivity');
   $MidDaySyncHOTablesForBranch['Others'] = array('area','user','bank','tpi_dealerwriteoff','rusertypemodulecontrol','discount',
                                                  'reasontype','tpi_document','inventorymovementtype_reasons','affliatedibm');
?>
