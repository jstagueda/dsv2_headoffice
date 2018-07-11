<?php
include("../initialize.php");
global $database;
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
			'igsreservation', 'igsreservationdetails');
			
	
	foreach($HODBTblToReadInBranch as $table):
		$database->execute("SET FOREIGN_KEY_CHECKS=0");
		$database->execute("truncate   `".$table."`");
	endforeach;
?>