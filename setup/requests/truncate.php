<?php
    include('../../initialize.php');
    include('../config.php');
	global $mysqli;

/* SET FOREIGN_KEY_CHECK */
$success_logs = LOGS_PATH.'process.log';
tpi_file_truncate($success_logs);



$FOREIGN_KEY_CHECKS = "SET FOREIGN_KEY_CHECKS = 0";
$mysqli->query($FOREIGN_KEY_CHECKS);
/* views */

$array_view = array('vwbrochure','vwbandingcompany','vwbanding','vwnewrepeat','vwactualsales');
		foreach ($array_view as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
		}

/** sales **/
$array_sales = array (
					 	'tpi_detailedcustomercommissions','tpi_branchcollectionrating','cumulativesales',
					 	'customercommission','customerpenalty','tmpsodetails',
					 	'unclaimedproducts','salesinvoicedetails','salesinvoice','deliveryreceiptdetails',
					 	'deliveryreceipt','salesorderdetails','salesorder','officialreceiptdetails',
					 	'officialreceiptcheck','officialreceiptdeposit','officialreceiptcash',
					 	'officialreceiptcommission','officialreceipt','provisionalreceiptcheck',
					 	'provisionalreceipt','dmcmdetails','dmcm'
					 );
				
		foreach ($array_sales as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
		}

/** inventory **/
$array_inventory  = array (	
							'productlistforprinting','tpi_rinventorybranch','freeze',
							'stocklog','inventorytransferdetails','inventorytransfer',
							'inventoryinoutdetails','inventoryinout','inventorycountdetails',
							'inventorycount','inventoryadjustmentdetails','inventoryadjustment',
							'inventorybeginningbalance','inventorybalance','inventory'
						  );
		foreach ($array_inventory as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
		}
/** bir **/
$birseries = "truncate table birseries";
$mysqli->query($birseries);
tpi_error_log($birseries,$success_logs);

/** bank **/
$bank = "truncate table bank";
$mysqli->query($bank);
tpi_error_log($birseries,$success_logs);
/** leader list **/
$array_leaderlist = array ( 
							'tpi_staffcount','rcategoryproductline','brochuretext',
							'brochureproductinfo','brochureproduct','brochuredetails',
							'brochurecampaign','brochure','campaignmonth','campaign' 
						  );
							
		foreach ($array_leaderlist as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
		}

/** promo and pricing **/
$array_promopricing = array( 	'promodeleteddetails','productlistpromosuggestion','productlistpromo',
								'productlist','rpromobranch','applicableincentives',
								'promocummulativebalance','promoentitlementdetails','promoentitlement',
								'promobuyin','promoavailment','promo' 
						   );
		foreach ($array_promopricing as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
		}
			
$promobuyin = "delete from `promobuyin` where ParentPromoBuyInID is not null";
$mysqli->query($promobuyin);
tpi_error_log($truncate."<br />",$success_logs);

/** product **/
$product1 = "delete from product where ParentID is not null and ProductLevelID = 3";
$product2 = "delete from product where ParentID is not null and ProductLevelID = 2";
$mysqli->query($product1);
$mysqli->query($product2);
tpi_error_log($product1."<br />",$success_logs);
tpi_error_log($product2."<br />",$success_logs);

/*'product'*/
$array_product = array( 
						'productcost','productdetails','productkit','productpricing',
						'productregkit','productsubstitute','productexchange',
						'productexchangedetails'
					   );
						
		foreach ($array_product as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
		}

/* - DEALERS - */
$array_dealers = array( 
						'tpi_creditlimit','tpi_credit','tpi_customerdetails',
						'tpi_customerstats','tpi_dealerkitpurchase','tpi_dealerpromotion',
						'tpi_dealertransfer','tpi_dealerwriteoff','tpi_detailedcustomercommissions',
						'tpi_rcustomerbranch','tpi_rcustomeribm','tpi_rcustomerpda','tpi_rcustomerstatus',
						'customeraccountsreceivable','customercommission','customerdetails','customerpenalty',
						'customer'
					   );

		
		foreach ($array_dealers as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
		}
		
//truncate branch tables 
$branch = array(	
				  'branch','branchdepartment','branchdetails','remployeebranch',
				  'employee','remployeeposition', 'branchparameter'
			   );
		foreach ($branch as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
		}
/*tables*/		
$tables = array(
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
					'tpi_tmprho','tpi_tmprhodetails','tpi_tmpsta','tpi_tmpstadetails','tpi_tmpupload'
				);
				
	foreach ($tables as $table){
			$truncate = "Truncate Table ".$table;
			$mysqli->query($truncate);
			tpi_error_log($truncate."<br />",$success_logs);
	}
		tpi_error_log("Truncate Successful<br />",$success_logs);
?>