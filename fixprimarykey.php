<?php 
	require_once("initialize.php");
	global $database;
	
	/*
		alter table `customer` 
		change `HOGeneralID` `HOGeneralID` varchar(150) character set latin1 collate latin1_swedish_ci NOT NULL,
		drop primary key
	
	*/
	
	/*
		alter table `dss_headoffice`.`officialreceiptcash` 
		change `ID` `ID` bigint(20) UNSIGNED NOT NULL,
		drop primary key
	*/
	// bankdepositslip
	$list = array('customer','customerdetails','customeraccountsreceivable',
    'customerpenalty','tpi_customerdetails','tpi_rcustomeribm','tpi_rcustomerstatus',
    'tpi_rcustomerbranch','tpi_rcustomerpda','tpi_customerstats','customercommission',
	'customercommission_summary','inventory','inventoryadjustment','inventorycount',
    'inventoryinout','inventorytransfer','inventoryadjustmentdetails','inventorycountdetails',
    'inventoryinoutdetails','inventorytransferdetails','salesorder','salesinvoice','salesorderdetails',
    'salesinvoicedetails','cumulativesales','birseries','deliveryreceipt','deliveryreceiptdetails',
    'dmcm','dmcmdetails','tpi_credit','tpi_creditlimitdetails','tpi_dealerwriteoff','tpi_dealerpromotion',
    'tpi_dealertransfer','tpi_branchcollectionrating','officialreceipt','officialreceiptcash',
    'officialreceiptcheck','officialreceiptcommission','officialreceiptdeposit','officialreceiptdetails',
    'applied_single_line_promo_entitlement_details','applied_multi_line_promo_entitlement_details', 	
    'applied_overlay_promo_entitlement_details','applied_incentive_entitlement_details','availed_applicable_promos',
    'availed_applicable_incentives','sfm_manager','sfm_manager_networks','productexchange','productexchangedetails',
	'tpi_sfasummary','tpi_sfasummary_pmg','affliatedibm','staffcount');
	
	foreach($list as $table):
		$q = $database->execute('ALTER TABLE `'.$table.'` DROP PRIMARY KEY, ADD PRIMARY KEY(`HOGeneralID`)');
		//echo $table."<br />";
		//while($r = $q->fetch_object()){
		//	
		//	if($r->Key =='PRI'){
		//		if(substr($r->Type,0,7) =='varchar'){
		//			$str = 'varchar(150) character set latin1 collate latin1_swedish_ci NOT NULL, DROP PRIMARY KEY';
		//		}else{
		//			$str = 'bigint(20) UNSIGNED NOT NULL, DROP PRIMARY KEY';
		//		}
		//		$database->execute('alter table `'.$table.'` change `'.$r->Field.'` `'.$r->Field.'` '.$str.', ADD PRIMARY KEY(`HOGeneralID`)');
		//		
		//	}
		//	
		//	echo "<pre>";
		//		print_r($r);
		//	echo "</pre> <br /> <br />";
		//	
		//	
		//}
	endforeach;
?>