<?php
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	require_once('../../class/config.php');
	require_once('../../class/dbconnection.php');
	require_once('../../class/mysqli.php');
	//include('../config.php');
	global $database;
	tpi_error_log("Start process truncate<br />",$success_logs);
	setForeignKeyChecks($database,0);
	foreach ($deletetables as $deletequery):
		$database->execute($deletequery);
	endforeach;
	/*tables*/		
	$truncatetables = array 	(
									'product',
									'vwbrochure','vwbandingcompany','vwbanding','vwnewrepeat','vwactualsales',
									'tpi_staffcount','rcategoryproductline','brochuretext',
									'brochureproductinfo','brochureproduct','brochuredetails','bank',
									'brochurecampaign','brochure','campaignmonth','campaign',
									'vwbrochure','vwbandingcompany','vwbanding','vwnewrepeat','vwactualsales',
									'tpi_detailedcustomercommissions','tpi_branchcollectionrating','cumulativesales',
									'customercommission','customerpenalty','tmpsodetails',
									'unclaimedproducts','salesinvoicedetails','salesinvoice','deliveryreceiptdetails',
									'deliveryreceipt','salesorderdetails','salesorder','officialreceiptdetails',
									'officialreceiptcheck','officialreceiptdeposit','officialreceiptcash',
									'officialreceiptcommission','officialreceipt','provisionalreceiptcheck',
									'provisionalreceipt','dmcmdetails','dmcm',
									'productlistforprinting','tpi_rinventorybranch','freeze',
									'stocklog','inventorytransferdetails','inventorytransfer',
									'inventoryinoutdetails','inventoryinout','inventorycountdetails',
									'inventorycount','inventoryadjustmentdetails','inventoryadjustment','inventorybeginningbalance',
									'inventorybalance','inventory','promodeleteddetails','productlistpromosuggestion','productlistpromo','birseries',
									'productlist','rpromobranch','applicableincentives','promocummulativebalance','promoentitlementdetails',
									'promoentitlement','promobuyin','promoavailment','promo','productcost','productdetails','productkit',
									'productpricing','productregkit','productsubstitute','productexchange',
									'productexchangedetails','tpi_creditlimit','tpi_credit','tpi_customerdetails',
									'tpi_customerstats','tpi_dealerkitpurchase','tpi_dealerpromotion',
									'tpi_dealertransfer','tpi_dealerwriteoff','tpi_detailedcustomercommissions',
									'tpi_rcustomerbranch','tpi_rcustomeribm','tpi_rcustomerpda','tpi_rcustomerstatus',
									'customeraccountsreceivable','customercommission','customerdetails','customerpenalty',
									'customer','branch','branchdepartment','branchdetails','remployeebranch',
									'employee','remployeeposition', 'branchparameter',
									'bankdepositslip','branchbank','syncbranch','synclog',
									'tmpcopytosoheader','tmpcopytosodetails','tmpcreditlimit',
									'tmpcust','tmpdgs','tmpexpericence','tmpinventorybegbal',
									'tmplastsi','tmpopensi','tmpprodexchange','tmpsodetails',
									'tmpunclaimedproducts','tpi_aop','tpi_branchcollectionrating',
									'tpi_consolidatedrollingforecast','tpi_netfactor','tpi_rcustomerstatus',
									'tpi_rinventorybranch','tpi_salesinvoice_adjustment','tpi_tempaop',
									'tpi_tempconsolidatedrollingforecast','tpi_tempdummycost','tpi_temprollingforecast',
									'tpi_tmppromomultiline_buyin','tpi_tmppromomultiline_entitlement',
									'tpi_tmppromooverlay_buyin','tpi_tmppromooverlay_entitlement','tpi_tmppromosingleline',
									'tpi_tmprho','tpi_tmprhodetails','tpi_tmpsta','tpi_tmpstadetails','tpi_tmpupload','tpi_sfasummary',
									'tpi_sfasummary_pmg','tpi_branchcollectionrating','department','position'
									'promoincentives','incentivespromobuyin','incentivespromoentitlement','incentivespromoavailemnt','incentivesspecialcriteria',
									'loyaltypromo','loyaltypromoavailment','loyaltypromobuyin','loyaltypromoentitlement','promoentitlementdetails',
									'holiday','branchspecialactivity','sfm_adhoc_cl', 'sfm_adhoc_cl_criterias', 'sfm_candidacy_matrix', 
									'sfm_kpi_standards', 'sfm_level','sfm_rank', 'applied_single_line_promo_entitlement_details',
									'applied_multi_line_promo_entitlement_details', 'applied_overlay_promo_entitlement_details', 
									'applied_incentive_entitlement_details', 'availed_applicable_promos', 'availed_applicable_incentives','sfm_candidacy_matrix',
									'sfm_manager','sfm_manager_networks'
								);
								

	$database->execute("INSERT  INTO `product`(	`ID`,`Code`,`Name`,`ShortName`,`Description`,`ProductLevelID`,
												`ParentID`,`ProductTypeID`,`UnitTypeID`,`Lt`,
												`Rt`,`StatusID`,`EnrollmentDate`,`LastModifiedDate`,
												`Changed`,`LaunchDate`,`FileName`,`LastPODate`) 
										VALUES
										(1,'TBP','Tupperware Brands Philippines','Tupperware Brands Philippines',
										 'Tupperware Brands Philippines',
										 1,NULL,1,1,1,1,10,'2012-09-27 13:46:07','2012-09-27 13:46:07',0,
										 '2012-09-27 13:46:07','','0000-00-00 00:00:00')");
											
	truncate_tables($database,$truncatetables,$success_logs);
	tpi_error_log("Truncate Successful<br />",$success_logs);
	setForeignKeyChecks($database);
?>