<?php    
/* 
 *  Modified by: marygrace cabardo 
 *  10.08.2012
 *  marygrace.cabardo@gmail.com
 */
	#ini_set('display_errors', '1');        
	header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
	header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>ERP Branch</title>
		<link href="css/ems.css" rel="stylesheet" type="text/css">

		<script type="text/javascript">
		if (document.getElementById) { 
		  document.write('<style type="text/css">\n');
		  document.write('.submenu{display: none;}\n');
		  document.write('.submenu2{display: ;}\n');
		  document.write('</style>\n');
		}

		function SwitchMenu(obj) {

			if(document.getElementById) {  
			var el = document.getElementById(obj);
			var ar = document.getElementById("masterdiv").getElementsByTagName("span"); 
				if(el.style.display != "block") {    
					for (var i=0; i<ar.length; i++) {      
						if (ar[i].className=="submenu") 
						ar[i].style.display = "none";
					}
					el.style.display = "block";
				} else {    
					el.style.display = "none";
				}
			}
		}
		</script>
		<script language="javascript" src="js/string_validation.js"></script>
		<script>
		<!--
		function hideit(elem) {
		  document.getElementById(elem).style.display='none';
		}

		function unhideit(elem) {
		  document.getElementById(elem).style.display = 'block';
		}
		//-->
		</script>

		<link rel="stylesheet" type="text/css" href="css/calpopup.css"/>
		<script type="text/javascript" src="js/calpopup.js"></script>
		<script type="text/javascript" src="js/dateparse.js"></script>
		<!-- TPI Developer 1's source codes added by JP start here... -->
		<script type="text/javascript" src="js/ajax-connection.js"></script>
		<script type="text/javascript" src="js/tpi-dss-functions.js"></script>
		<!-- TPI Developer 1's source codes added by JP start here... -->
	</head>

	<body>
	<?php 		
	   require_once "initialize.php";
	   global $database;   
	   if(!$session->is_logged_in()) {
		$message="Please try to login first.";
		redirect_to("login.php?msg=".$message);
	   }
			 
	   define("DATETODAY", date("m/d/Y"));	
	   $page="index.php";
			  
	   if(isset($_GET["pup"]))
		  $pup=$_GET["pup"];
	   else $pup=-1;  	
	  
	   if(isset($_GET["pageid"])) 
		  $pid=$_GET["pageid"];  	
	   else {
	   
	   $pid=-1;
		/*unset($_SESSION["USERID"]);
		unset($_SESSION["SESSION"]);*/
		}
		
		/* SESSION CHECKER */
	   if($pid!=-1) {
	   if (!$session->is_logged_in()) {
		   $message="Please try to login first.";
		   redirect_to("index.php?msg=".$message);
	   }
		   $userid = $session->user_id;
		   $rs_access = $sp->spSelectAccessPage($database, $userid, $pid);				  
		   if((!$rs_access->num_rows) && $pid!=0) $pid=0;			  		  		   
	   }
		  
	   if(!isset($_SESSION["prod_add"])) {
			$_SESSION["prod_add"]      = array();
			$_SESSION["prod_add_list"] = array();
	   }
		
		if($pid != 34) {   
			unset($_SESSION["prod_add"]);
			unset($_SESSION["prod_add_list"]);
		}
		 
	   if($pid!=97) unset($_SESSION["CustomerID"]);	 	 
	   if($pid!=67) unset($_SESSION["buyin"]);	 	 
	   if($pid!=58) {   
			unset($_SESSION["prodid2"]);
			unset($_SESSION["prodcode2"]);
			unset($_SESSION["prodname2"]);
			unset($_SESSION["soh2"]);
			unset($_SESSION["qty2"]);
			unset($_SESSION["resid2"]);
			unset($_SESSION["uom2"]);
	   }
		 
	   if($pid != 2) {   
			unset($_SESSION["prodid"]);
			unset($_SESSION["prodcode"]);
			unset($_SESSION["prodname"]);
			unset($_SESSION["soh"]);
			unset($_SESSION["qty"]);
			unset($_SESSION["resid"]);
			unset($_SESSION["uom"]);
	   }
		 
	   if($pid!=116.1) {     
			unset($_SESSION["callouts"]);
			unset($_SESSION["violators"]);
			unset($_SESSION["heroed"]);
			unset($_SESSION["itemworn"]);
	   }
		 
	   if($pid != 135) {   
			unset($_SESSION["ll_message_log"]);
			unset($_SESSION["ll_uploader_error"]);
	   }

	   if($pid!=-1) include("pages/header.php");	
		  switch ($pid) {    
				case 0    : $page = "pages/main.php"; break;
				case 1    : $page = "pages/inventory/inv_main.php"; break;
				case 2    : $page = "pages/inventory/inv_createMiscellaneousTransactions.php"; break;
				case 3    : $page = "pages/inventory/inv_vwMiscellaneousTransactions.php"; break; 
				case 3.1  : $page = "pages/inventory/inv_vwMiscellaneousTransactionDetails.php"; break; 
				case 4    : $page = "pages/datamanagement/datamgt_main.php"; break;
				case 5    : $page = "pages/datamanagement/datamgt_branch.php"; break;
				case 6    : $page = "pages/datamanagement/datamgt_prodclass.php"; break;
				case 7    : $page = "pages/datamanagement/datamgt_brand.php"; break;
				case 8    : $page = "pages/datamanagement/datamgt_category.php"; break;
				case 9    : $page = "pages/datamanagement/datamgt_pricetemp.php"; break;
				case 10   : $page = "pages/access_rights/access_main.php"; break;
				case 11   : $page = "pages/access_rights/access_usertype.php"; break;
				case 12   : $page = "pages/datamanagement/datamgt_employee.php"; break;
				case 13   : $page = "pages/access_rights/access_module.php"; break;
				case 14   : $page = "pages/datamanagement/datamgt_dept.php"; break;
				case 15   : $page = "pages/access_rights/access_vwuser.php"; break;
				case 16   : $page = "pages/access_rights/access_rights.php"; break;
				case 17   : $page = "pages/access_rights/access_module.php"; break;
				case 18   : $page = "pages/sales/sales_main.php"; break;
				case 19   : $page = "pages/datamanagement/datamgt_paymentterms.php"; break;
				case 20   : $page = "pages/leaderlist/datamgt_product.php"; break;
				case 21   : $page = "pages/access_rights/access_module.php"; break;
				case 22   : $page = "pages/datamanagement/datamgt_producttype.php"; break;
				case 23   : $page = "pages/datamanagement/datamgt_customer.php"; break;
				case 24   : $page = "pages/datamanagement/datamgt_outlettype.php"; break;
				case 25   : $page = "pages/inventory/inv_createtransfer.php"; break;			
				case 26   : $page = "pages/inventory/inv_ConfirmReceiptfromHO.php"; break;
				case 26.1 : $page = "pages/inventory/inv_ConfirmReceiptfromHODetails.php"; break;
				case 27   : $page = "pages/inventory/inv_confirmIssuanceofSTA.php"; break;
				case 27.1 : $page = "pages/inventory/inv_confirmIssuanceofSTADetails.php"; break;
				case 28   : $page = "pages/inventory/inv_createcount.php"; break;
				case 29   : $page = "pages/inventory/inv_vwtransfer.php"; break;
				case 29.1 : $page = "pages/inventory/inv_vwtransferdetails.php"; break;
				case 30   : $page = "pages/inventory/inv_ConfirmReceiptofSTA.php"; break;
				case 30.1 : $page = "pages/inventory/inv_ConfirmReceiptofSTADetails.php"; break;
				case 31   : $page = "pages/inventory/inv_ConfirmReturnsToHO.php"; break;
				case 31.1 : $page = "pages/inventory/inv_ConfirmReturnstoHODetails.php"; break;
				case 32   : $page = "pages/inventory/inv_vwcount.php"; break;
				case 32.1 : $page = "pages/inventory/inv_vwcountdetails.php"; break;
				case 33   : $page = "pages/inventory/inv_vwstockdetails.php"; break;
				case 33.1 : $page = "pages/inventory/inv_vwstocklogdetails.php"; break;
				case 34   : $page = "pages/sales/sales_createsalesorder.php"; break;
				case 34.1 : $page = "pages/sales/sales_copytoso.php"; break;
				case 35   : $page = "pages/sales/sales_vwsalesorder.php"; break;
				case 35.1 : $page = "pages/sales/sales_vwsalesorderdetails.php"; break;
				case 35.2 : $page = "pages/sales/sales_createsalesorder.php"; break;
				case 35.3 : $page = "pages/sales/sales_unconfirmedSO.php"; break;
				case 40   : $page = "pages/sales/sales_vwsalesinvoice.php"; break;
				case 40.1 : $page = "pages/sales/sales_vwsalesinvoicedetails.php"; break;				
				case 42   : $page = "pages/datamanagement/datamgt_pricing_custlinking.php"; break;
				case 43   : $page = "pages/datamanagement/datamgt_pricing.php"; break;
				case 44   : $page = "pages/sfm/dealermng_createwriteoff.php"; break;
				case 45   : $page = "pages/sales/sales_listdr_si.php"; break;
				case 45.1 : $page = "pages/sales/sales_createsi.php"; break;
				case 46   : $page = "pages/sales/sales_createdmcm.php"; break;
				case 47   : $page = "pages/sales/sales_aging_ar.php"; break;
				case 47.1 : $page = "pages/sales/aging_ar_si_list.php"; break;
				case 48   : $page = "pages/sales/sales_createdmcm.php"; break;
				case 49   : $page = "pages/sales/sales_listdmcm.php"; break;
				case 49.1 : $page = "pages/sales/sales_vwdmcmdetails.php"; break; 
				case 50   : $page = "pages/sales/sales_createProvisionalReceipt.php"; break;
				case 51   : $page = "pages/sales/sales_vwProvisionalReceipt.php"; break;	
				case 51.1 : $page = "pages/sales/sales_vwProvisionalReceiptDetails.php"; break;								
				case 56   : $page = "pages/datamanagement/datamgt_packinglistupload.php"; break;
				case 57   : $page = "pages/inventory/inv_printCountWorksheetAndTags.php"; break;
				case 58   : $page = "pages/inventory/inv_createCycleCountAdjustment.php"; break;
				case 59   : $page = "pages/inventory/inv_vwCycleCountAdjustment.php"; break;
				case 59.1 : $page = "pages/inventory/inv_vwCycleCountAdjustmentDetails.php"; break;
				case 80   : $page = "pages/leaderlist/leaderlist_main.php"; break;
				case 60   : $page = "pages/leaderlist/promo_viewsingleline.php"; break;
				case 61   : $page = "pages/leaderlist/promo_createsingleline.php"; break;
                                case 61.1 : $page = "pages/leaderlist/promo_createsingleline_dis.php"; break;
				case 62   : $page = "pages/leaderlist/promo_viewmultiline.php"; break;
				case 62.1 : $page = "pages/leaderlist/promo_popup.php"; break;
				case 63   : $page = "pages/leaderlist/promo_createmultiline.php"; break;
                                case 63.1 : $page = "pages/leaderlist/promo_createmultiline_dis.php"; break;
				case 64   : $page = "pages/leaderlist/promo_viewsteplevel.php"; break;
				case 65   : $page = "pages/leaderlist/promo_createsteplevel.php"; break;
                                case 65.1 : $page = "pages/leaderlist/promo_createsteplevel_dis.php"; break;
				case 66   : $page = "pages/leaderlist/promo_viewset.php"; break;
                                case 66.1 : $page = "pages/leaderlist/promo_viewsetincentives.php"; break;
				//case 67   : $page = "pages/leaderlist/promo_createset.php"; break;
				case 67   : $page = "pages/leaderlist/promo_createoverlay.php"; break;
                                //case 67.1 : $page = "pages/leaderlist/promo_createset_dis.php"; break;
                                 case 67.1 : $page = "pages/leaderlist/promo_createoverlay_dis.php"; break;
								 case 67.2 : $page = "pages/leaderlist/promo_createset.php"; break;
								 case 67.3 : $page = "pages/leaderlist/promo_createset_dis.php"; break;
				case 68   : $page = "pages/leaderlist/promo_performpromovalidation.php"; break;	
				case 69   : $page = "pages/sfm/datamgt_enrollnewdealer.php"; break;
				case 70   : $page = "pages/sfm/datamgt_updatedealerprofile.php"; break;
				case 71   : $page = "pages/sfm/sfm_main.php"; break;
				case 72   : $page = "pages/sfm/dealermov_transferdealer.php"; break;
				case 73   : $page = "pages/sfm/dealermov_promotedealer.php"; break;
				case 74   : $page = "pages/sfm/dealermov_terminatedealer.php"; break;
				case 75   : $page = "pages/sfm/dealermov_reactivatedealer.php"; break;
				case 78   : $page = "pages/sfm/dealermov_reverseIBMC.php"; break;
				case 79   : $page = "pages/sfm/dealermov_createibm.php"; break;
				case 84   : $page = "pages/sfm/dealermov_createibm.php"; break;	        
				//case 90 : $page = "pages/leaderlist/leaderlist_main.php"; break;
				case 91   : $page = "pages/sfm/dealermng_createwriteoff.php"; break;
				case 92   : $page = "pages/sfm/dealermng_createreqwriteoff.php"; break;
				case 93   : $page = "pages/sfm/dealermng_approveReqWriteOff.php"; break;
				case 94   : $page = "pages/inventory/inv_txnregister.php"; break;
				case 95   : $page = "pages/sales/or_txnregister.php"; break;
				case 96   : $page = "pages/sales/sales_vwOfficialReceipt.php"; break;
				case 96.1 : $page = "pages/sales/sales_vwOfficialReceiptDetails.php"; break;
				case 97   : $page = "pages/sales/sales_createOfficialReceipt.php"; break;
				case 98   : $page = "pages/sales/sales_dmcmregister.php"; break;
				case 99   : $page = "pages/sales/sales_siregister.php"; break;
				case 100  : $page = "pages/inventory/inv_vwRecordInvCount.php"; break;
				case 100.1: $page = "pages/inventory/inv_RecordInvCountDetails.php"; break;
				case 102  : $page = "pages/datamanagement/datamgt_interfaces.php"; break;
				case 101  : $page = "pages/datamanagement/sync.php"; break;
				case 103  : $page = "pages/inventory/inv_ItemAgingReport.php";  break;
				case 104  : $page = "pages/sales/sales_createsalesorder.php";  break;			
				case 105  : $page = "pages/inventory/inv_valuationReport.php";  break;
				case 106  : $page = "pages/inventory/inv_productStatus.php"; break;
				case 107  : $page = "pages/sales/sales_birbackend.php"; break;
				case 108  : $page = "pages/sales/sales_agingreport.php"; break;
				case 109  : $page = "pages/datamanagement/EOD.php"; break;
				case 110  : $page = "pages/sales/sales_editOfficialReceipt.php"; break;
				case 110.1: $page = "pages/sales/sales_editOfficialReceiptDetails.php"; break;
				case 111  : $page = "pages/datamanagement/datamgt_productsubstitute.php"; break;
				case 112  : $page = "pages/leaderlist/datamgt_campaign.php"; break;
				case 113  : $page = "pages/leaderlist/datamgt_brochures.php"; break;
				case 116  : $page = "pages/leaderlist/datamgt_brochuresaddpage.php"; break;
				case 116.1: $page = "pages/leaderlist/datamgt_addpagedetails.php"; break;
				case 114  : $page = "pages/leaderlist/datamgt_listofbrochures.php"; break;
				case 114.1: $page = "pages/leaderlist/datamgt_linkpromosandprods.php"; break;
				case 115  : $page = "includes/pcUnlockTransactions.php"; break;
				case 117  : $page = "pages/datamanagement/datamgt_barangay.php"; break;
				case 77   : $page = "pages/datamanagement/datamgt_area.php"; break;
				case 118  : $page = "pages/datamanagement/datamgt_productkit.php"; break;
				case 119  : $page = "pages/inventory/inv_approveMiscellaneousTransactions.php"; break;
				case 119.1: $page = "pages/inventory/inv_approveMiscellaneousTransactionsDetails.php"; break;
				case 120  : $page = "pages/sfm/dealermng_pdarar.php"; break;
				case 121  : $page = "pages/sfm/dealermng_creditlimit.php"; break;
				case 122  : $page = "pages/sfm/dealermng_listcreditlimit.php"; break;
				case 123  : $page = "pages/leaderlist/datamgt_keyspread.php"; break;
				case 124  : $page = "pages/leaderlist/datamgt_netfactor.php"; break;
				case 125  : $page = "pages/leaderlist/datamgt_staffcount.php"; break;
				case 126  : $page = "pages/inventory/inv_unfreezeinventory.php"; break;
				case 127  : $page = "pages/leaderlist/datamgt_linkpromobranch.php"; break;
				case 128  : $page = "pages/parameters/parameter_main.php"; break;			
				case 129  : $page = "pages/parameters/parameter_creditlimit.php"; break;
				case 130  : $page = "pages/datamanagement/EOM.php"; break;
				case 132  : $page = "pages/sfm/dealermng_viewcreditlimit.php"; break;
				case 133  : $page = "pages/inventory/inv_EditWorksheet.php"; break;
				case 133.1: $page = "pages/inventory/inv_EditInvCountDetails.php"; break;
				case 134  : $page = "pages/inventory/inv_ListPrintCountTags.php"; break;
				case 134.1: $page = "pages/inventory/inv_PrintCountTagsDetails.php"; break;
				case 135  : $page = "pages/leaderlist/datamgt_lluploader.php"; break;
				case 136  : $page = "pages/inventory/inv_criticalStockOutofStockReport.php"; break;
				case 137  : $page = "pages/inventory/inv_topSellingProducts.php"; break;
				case 138  : $page = "pages/inventory/inv_walltowallWorksheet.php"; break;
				case 139  : $page = "pages/inventory/inv_walltowallFreezeHistory.php"; break;
				case 140  : $page = "pages/inventory/inv_walltowallAuditTrail.php"; break;
				case 141  : $page = "pages/sales/sales_ibmCumPerReport.php";  break;
				case 142  : $page = "pages/sales/sales_paymentsCA.php";  break;
				case 143  : $page = "pages/sales/sales_customerDGSReport.php";  break;
				case 144  : $page = "pages/sales/sales_bceProfileReport.php";  break;
				case 145  : $page = "pages/sales/sales_paymentclassification.php";  break;
				case 146  : $page = "pages/sales/sales_IBMonlineandActiveReport.php"; break;
				case 147  : $page = "pages/sales/sales_cycleReports.php"; break;
				case 148  : $page = "pages/sales/sales_backOrderReport.php"; break;
				case 149  : $page = "pages/sales/sales_IBDBadDebts.php"; break;
				case 150  : $page = "pages/sfm/sfm_recruitersReport.php";  break;	
				case 151  : $page = "pages/datamanagement/datamgt_llreports.php";  break;	
				case 152  : $page = "pages/datamanagement/datamgt_bank.php";  break;
				case 153  : $page = "pages/datamanagement/datamgt_position.php";  break;
				case 154  : $page = "pages/sales/sales_depositslip.php";  break;
				case 155  : $page = "pages/sales/sales_backorderreport_prod.php";  break;
				case 156  : $page = "pages/inventory/inv_RHODiscrepancyReport.php";  break;
				case 157  : $page = "pages/sales/sales_backorderreport_so.php";  break;
				case 158  : $page = "pages/sales/sales_backorderreport_customer.php";  break;
				case 159  : $page = "pages/sales/sales_recordprodexchange.php";  break;
				case 159.1: $page = "pages/sales/sales_recordprodexchangedetails.php";  break;
				case 160  : $page = "pages/sales/sales_vwproductExchange.php";  break;	
				case 160.1: $page = "pages/sales/sales_vwproductExchangeDetails.php";  break;
                                case 162  : $page = "pages/leaderlist/promo_createstplvl.php";  break;
                                case 161  : $page = "pages/leaderlist/promo_stplvl.php";  break;
				default   : $page = "pages/main.php";
			  }		  
			include("$page");		
			if($pid!=-1) include("pages/footer.php");		
	?>
	</body>
</html>

	header( "Pragma: no-cache" );
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>ERP Branch</title>
		<link href="css/ems.css" rel="stylesheet" type="text/css">

		<script type="text/javascript">
		if (document.getElementById) { 
		  document.write('<style type="text/css">\n');
		  document.write('.submenu{display: none;}\n');
		  document.write('.submenu2{display: ;}\n');
		  document.write('</style>\n');
		}

		function SwitchMenu(obj) {

			if(document.getElementById) {  
			var el = document.getElementById(obj);
			var ar = document.getElementById("masterdiv").getElementsByTagName("span"); 
				if(el.style.display != "block") {    
					for (var i=0; i<ar.length; i++) {      
						if (ar[i].className=="submenu") 
						ar[i].style.display = "none";
					}
					el.style.display = "block";
				} else {    
					el.style.display = "none";
				}
			}
		}
		</script>
		<script language="javascript" src="js/string_validation.js"></script>
		<script>
		<!--
		function hideit(elem) {
		  document.getElementById(elem).style.display='none';
		}

		function unhideit(elem) {
		  document.getElementById(elem).style.display = 'block';
		}
		//-->
		</script>

		<link rel="stylesheet" type="text/css" href="css/calpopup.css"/>
		<script type="text/javascript" src="js/calpopup.js"></script>
		<script type="text/javascript" src="js/dateparse.js"></script>
		<!-- TPI Developer 1's source codes added by JP start here... -->
		<script type="text/javascript" src="js/ajax-connection.js"></script>
		<script type="text/javascript" src="js/tpi-dss-functions.js"></script>
		<!-- TPI Developer 1's source codes added by JP start here... -->
	</head>

	<body>
	<?php 		
	   require_once "initialize.php";
	   global $database;   
	   if(!$session->is_logged_in()) {
		$message="Please try to login first.";
		redirect_to("login.php?msg=".$message);
	   }
			 
	   define("DATETODAY", date("m/d/Y"));	
	   $page="index.php";
			  
	   if(isset($_GET["pup"]))
		  $pup=$_GET["pup"];
	   else $pup=-1;  	
	  
	   if(isset($_GET["pageid"])) 
		  $pid=$_GET["pageid"];  	
	   else {
	   
	   $pid=-1;
		/*unset($_SESSION["USERID"]);
		unset($_SESSION["SESSION"]);*/
		}
		
		/* SESSION CHECKER */
	   if($pid!=-1) {
	   if (!$session->is_logged_in()) {
		   $message="Please try to login first.";
		   redirect_to("index.php?msg=".$message);
	   }
		   $userid = $session->user_id;
		   $rs_access = $sp->spSelectAccessPage($database, $userid, $pid);				  
		   if((!$rs_access->num_rows) && $pid!=0) $pid=0;			  		  		   
	   }
		  
	   if(!isset($_SESSION["prod_add"])) {
			$_SESSION["prod_add"]      = array();
			$_SESSION["prod_add_list"] = array();
	   }
		
		if($pid != 34) {   
			unset($_SESSION["prod_add"]);
			unset($_SESSION["prod_add_list"]);
		}
		 
	   if($pid!=97) unset($_SESSION["CustomerID"]);	 	 
	   if($pid!=67) unset($_SESSION["buyin"]);	 	 
	   if($pid!=58) {   
			unset($_SESSION["prodid2"]);
			unset($_SESSION["prodcode2"]);
			unset($_SESSION["prodname2"]);
			unset($_SESSION["soh2"]);
			unset($_SESSION["qty2"]);
			unset($_SESSION["resid2"]);
			unset($_SESSION["uom2"]);
	   }
		 
	   if($pid != 2) {   
			unset($_SESSION["prodid"]);
			unset($_SESSION["prodcode"]);
			unset($_SESSION["prodname"]);
			unset($_SESSION["soh"]);
			unset($_SESSION["qty"]);
			unset($_SESSION["resid"]);
			unset($_SESSION["uom"]);
	   }
		 
	   if($pid!=116.1) {     
			unset($_SESSION["callouts"]);
			unset($_SESSION["violators"]);
			unset($_SESSION["heroed"]);
			unset($_SESSION["itemworn"]);
	   }
		 
	   if($pid != 135) {   
			unset($_SESSION["ll_message_log"]);
			unset($_SESSION["ll_uploader_error"]);
	   }

	   if($pid!=-1) include("pages/header.php");	
		  switch ($pid) {    
				case 0    : $page = "pages/main.php"; break;
				case 1    : $page = "pages/inventory/inv_main.php"; break;
				case 2    : $page = "pages/inventory/inv_createMiscellaneousTransactions.php"; break;
				case 3    : $page = "pages/inventory/inv_vwMiscellaneousTransactions.php"; break; 
				case 3.1  : $page = "pages/inventory/inv_vwMiscellaneousTransactionDetails.php"; break; 
				case 4    : $page = "pages/datamanagement/datamgt_main.php"; break;
				case 5    : $page = "pages/datamanagement/datamgt_branch.php"; break;
				case 6    : $page = "pages/datamanagement/datamgt_prodclass.php"; break;
				case 7    : $page = "pages/datamanagement/datamgt_brand.php"; break;
				case 8    : $page = "pages/datamanagement/datamgt_category.php"; break;
				case 9    : $page = "pages/datamanagement/datamgt_pricetemp.php"; break;
				case 10   : $page = "pages/access_rights/access_main.php"; break;
				case 11   : $page = "pages/access_rights/access_usertype.php"; break;
				case 12   : $page = "pages/datamanagement/datamgt_employee.php"; break;
				case 13   : $page = "pages/access_rights/access_module.php"; break;
				case 14   : $page = "pages/datamanagement/datamgt_dept.php"; break;
				case 15   : $page = "pages/access_rights/access_vwuser.php"; break;
				case 16   : $page = "pages/access_rights/access_rights.php"; break;
				case 17   : $page = "pages/access_rights/access_module.php"; break;
				case 18   : $page = "pages/sales/sales_main.php"; break;
				case 19   : $page = "pages/datamanagement/datamgt_paymentterms.php"; break;
				case 20   : $page = "pages/leaderlist/datamgt_product.php"; break;
				case 21   : $page = "pages/access_rights/access_module.php"; break;
				case 22   : $page = "pages/datamanagement/datamgt_producttype.php"; break;
				case 23   : $page = "pages/datamanagement/datamgt_customer.php"; break;
				case 24   : $page = "pages/datamanagement/datamgt_outlettype.php"; break;
				case 25   : $page = "pages/inventory/inv_createtransfer.php"; break;			
				case 26   : $page = "pages/inventory/inv_ConfirmReceiptfromHO.php"; break;
				case 26.1 : $page = "pages/inventory/inv_ConfirmReceiptfromHODetails.php"; break;
				case 27   : $page = "pages/inventory/inv_confirmIssuanceofSTA.php"; break;
				case 27.1 : $page = "pages/inventory/inv_confirmIssuanceofSTADetails.php"; break;
				case 28   : $page = "pages/inventory/inv_createcount.php"; break;
				case 29   : $page = "pages/inventory/inv_vwtransfer.php"; break;
				case 29.1 : $page = "pages/inventory/inv_vwtransferdetails.php"; break;
				case 30   : $page = "pages/inventory/inv_ConfirmReceiptofSTA.php"; break;
				case 30.1 : $page = "pages/inventory/inv_ConfirmReceiptofSTADetails.php"; break;
				case 31   : $page = "pages/inventory/inv_ConfirmReturnsToHO.php"; break;
				case 31.1 : $page = "pages/inventory/inv_ConfirmReturnstoHODetails.php"; break;
				case 32   : $page = "pages/inventory/inv_vwcount.php"; break;
				case 32.1 : $page = "pages/inventory/inv_vwcountdetails.php"; break;
				case 33   : $page = "pages/inventory/inv_vwstockdetails.php"; break;
				case 33.1 : $page = "pages/inventory/inv_vwstocklogdetails.php"; break;
				case 34   : $page = "pages/sales/sales_createsalesorder.php"; break;
				case 34.1 : $page = "pages/sales/sales_copytoso.php"; break;
				case 35   : $page = "pages/sales/sales_vwsalesorder.php"; break;
				case 35.1 : $page = "pages/sales/sales_vwsalesorderdetails.php"; break;
				case 35.2 : $page = "pages/sales/sales_createsalesorder.php"; break;
				case 35.3 : $page = "pages/sales/sales_unconfirmedSO.php"; break;
				case 40   : $page = "pages/sales/sales_vwsalesinvoice.php"; break;
				case 40.1 : $page = "pages/sales/sales_vwsalesinvoicedetails.php"; break;				
				case 42   : $page = "pages/datamanagement/datamgt_pricing_custlinking.php"; break;
				case 43   : $page = "pages/datamanagement/datamgt_pricing.php"; break;
				case 44   : $page = "pages/sfm/dealermng_createwriteoff.php"; break;
				case 45   : $page = "pages/sales/sales_listdr_si.php"; break;
				case 45.1 : $page = "pages/sales/sales_createsi.php"; break;
				case 46   : $page = "pages/sales/sales_createdmcm.php"; break;
				case 47   : $page = "pages/sales/sales_aging_ar.php"; break;
				case 47.1 : $page = "pages/sales/aging_ar_si_list.php"; break;
				case 48   : $page = "pages/sales/sales_createdmcm.php"; break;
				case 49   : $page = "pages/sales/sales_listdmcm.php"; break;
				case 49.1 : $page = "pages/sales/sales_vwdmcmdetails.php"; break; 
				case 50   : $page = "pages/sales/sales_createProvisionalReceipt.php"; break;
				case 51   : $page = "pages/sales/sales_vwProvisionalReceipt.php"; break;	
				case 51.1 : $page = "pages/sales/sales_vwProvisionalReceiptDetails.php"; break;								
				case 56   : $page = "pages/datamanagement/datamgt_packinglistupload.php"; break;
				case 57   : $page = "pages/inventory/inv_printCountWorksheetAndTags.php"; break;
				case 58   : $page = "pages/inventory/inv_createCycleCountAdjustment.php"; break;
				case 59   : $page = "pages/inventory/inv_vwCycleCountAdjustment.php"; break;
				case 59.1 : $page = "pages/inventory/inv_vwCycleCountAdjustmentDetails.php"; break;
				case 80   : $page = "pages/leaderlist/leaderlist_main.php"; break;
				case 60   : $page = "pages/leaderlist/promo_viewsingleline.php"; break;
				case 61   : $page = "pages/leaderlist/promo_createsingleline.php"; break;
                                case 61.1 : $page = "pages/leaderlist/promo_createsingleline_dis.php"; break;
				case 62   : $page = "pages/leaderlist/promo_viewmultiline.php"; break;
				case 62.1 : $page = "pages/leaderlist/promo_popup.php"; break;
				case 63   : $page = "pages/leaderlist/promo_createmultiline.php"; break;
                                case 63.1 : $page = "pages/leaderlist/promo_createmultiline_dis.php"; break;
				case 64   : $page = "pages/leaderlist/promo_viewsteplevel.php"; break;
				case 65   : $page = "pages/leaderlist/promo_createsteplevel.php"; break;
                                case 65.1 : $page = "pages/leaderlist/promo_createsteplevel_dis.php"; break;
				case 66   : $page = "pages/leaderlist/promo_viewset.php"; break;
                                case 66.1 : $page = "pages/leaderlist/promo_viewsetincentives.php"; break;
				//case 67   : $page = "pages/leaderlist/promo_createset.php"; break;
				case 67   : $page = "pages/leaderlist/promo_createoverlay.php"; break;
                                //case 67.1 : $page = "pages/leaderlist/promo_createset_dis.php"; break;
                                 case 67.1 : $page = "pages/leaderlist/promo_createoverlay_dis.php"; break;
								 case 67.2 : $page = "pages/leaderlist/promo_createset.php"; break;
								 case 67.3 : $page = "pages/leaderlist/promo_createset_dis.php"; break;
				case 68   : $page = "pages/leaderlist/promo_performpromovalidation.php"; break;	
				case 69   : $page = "pages/sfm/datamgt_enrollnewdealer.php"; break;
				case 70   : $page = "pages/sfm/datamgt_updatedealerprofile.php"; break;
				case 71   : $page = "pages/sfm/sfm_main.php"; break;
				case 72   : $page = "pages/sfm/dealermov_transferdealer.php"; break;
				case 73   : $page = "pages/sfm/dealermov_promotedealer.php"; break;
				case 74   : $page = "pages/sfm/dealermov_terminatedealer.php"; break;
				case 75   : $page = "pages/sfm/dealermov_reactivatedealer.php"; break;
				case 78   : $page = "pages/sfm/dealermov_reverseIBMC.php"; break;
				case 79   : $page = "pages/sfm/dealermov_createibm.php"; break;
				case 84   : $page = "pages/sfm/dealermov_createibm.php"; break;	        
				//case 90 : $page = "pages/leaderlist/leaderlist_main.php"; break;
				case 91   : $page = "pages/sfm/dealermng_createwriteoff.php"; break;
				case 92   : $page = "pages/sfm/dealermng_createreqwriteoff.php"; break;
				case 93   : $page = "pages/sfm/dealermng_approveReqWriteOff.php"; break;
				case 94   : $page = "pages/inventory/inv_txnregister.php"; break;
				case 95   : $page = "pages/sales/or_txnregister.php"; break;
				case 96   : $page = "pages/sales/sales_vwOfficialReceipt.php"; break;
				case 96.1 : $page = "pages/sales/sales_vwOfficialReceiptDetails.php"; break;
				case 97   : $page = "pages/sales/sales_createOfficialReceipt.php"; break;
				case 98   : $page = "pages/sales/sales_dmcmregister.php"; break;
				case 99   : $page = "pages/sales/sales_siregister.php"; break;
				case 100  : $page = "pages/inventory/inv_vwRecordInvCount.php"; break;
				case 100.1: $page = "pages/inventory/inv_RecordInvCountDetails.php"; break;
				case 102  : $page = "pages/datamanagement/datamgt_interfaces.php"; break;
				case 101  : $page = "pages/datamanagement/sync.php"; break;
				case 103  : $page = "pages/inventory/inv_ItemAgingReport.php";  break;
				case 104  : $page = "pages/sales/sales_createsalesorder.php";  break;			
				case 105  : $page = "pages/inventory/inv_valuationReport.php";  break;
				case 106  : $page = "pages/inventory/inv_productStatus.php"; break;
				case 107  : $page = "pages/sales/sales_birbackend.php"; break;
				case 108  : $page = "pages/sales/sales_agingreport.php"; break;
				case 109  : $page = "pages/datamanagement/EOD.php"; break;
				case 110  : $page = "pages/sales/sales_editOfficialReceipt.php"; break;
				case 110.1: $page = "pages/sales/sales_editOfficialReceiptDetails.php"; break;
				case 111  : $page = "pages/datamanagement/datamgt_productsubstitute.php"; break;
				case 112  : $page = "pages/leaderlist/datamgt_campaign.php"; break;
				case 113  : $page = "pages/leaderlist/datamgt_brochures.php"; break;
				case 116  : $page = "pages/leaderlist/datamgt_brochuresaddpage.php"; break;
				case 116.1: $page = "pages/leaderlist/datamgt_addpagedetails.php"; break;
				case 114  : $page = "pages/leaderlist/datamgt_listofbrochures.php"; break;
				case 114.1: $page = "pages/leaderlist/datamgt_linkpromosandprods.php"; break;
				case 115  : $page = "includes/pcUnlockTransactions.php"; break;
				case 117  : $page = "pages/datamanagement/datamgt_barangay.php"; break;
				case 77   : $page = "pages/datamanagement/datamgt_area.php"; break;
				case 118  : $page = "pages/datamanagement/datamgt_productkit.php"; break;
				case 119  : $page = "pages/inventory/inv_approveMiscellaneousTransactions.php"; break;
				case 119.1: $page = "pages/inventory/inv_approveMiscellaneousTransactionsDetails.php"; break;
				case 120  : $page = "pages/sfm/dealermng_pdarar.php"; break;
				case 121  : $page = "pages/sfm/dealermng_creditlimit.php"; break;
				case 122  : $page = "pages/sfm/dealermng_listcreditlimit.php"; break;
				case 123  : $page = "pages/leaderlist/datamgt_keyspread.php"; break;
				case 124  : $page = "pages/leaderlist/datamgt_netfactor.php"; break;
				case 125  : $page = "pages/leaderlist/datamgt_staffcount.php"; break;
				case 126  : $page = "pages/inventory/inv_unfreezeinventory.php"; break;
				case 127  : $page = "pages/leaderlist/datamgt_linkpromobranch.php"; break;
				case 128  : $page = "pages/parameters/parameter_main.php"; break;			
				case 129  : $page = "pages/parameters/parameter_creditlimit.php"; break;
				case 130  : $page = "pages/datamanagement/EOM.php"; break;
				case 132  : $page = "pages/sfm/dealermng_viewcreditlimit.php"; break;
				case 133  : $page = "pages/inventory/inv_EditWorksheet.php"; break;
				case 133.1: $page = "pages/inventory/inv_EditInvCountDetails.php"; break;
				case 134  : $page = "pages/inventory/inv_ListPrintCountTags.php"; break;
				case 134.1: $page = "pages/inventory/inv_PrintCountTagsDetails.php"; break;
				case 135  : $page = "pages/leaderlist/datamgt_lluploader.php"; break;
				case 136  : $page = "pages/inventory/inv_criticalStockOutofStockReport.php"; break;
				case 137  : $page = "pages/inventory/inv_topSellingProducts.php"; break;
				case 138  : $page = "pages/inventory/inv_walltowallWorksheet.php"; break;
				case 139  : $page = "pages/inventory/inv_walltowallFreezeHistory.php"; break;
				case 140  : $page = "pages/inventory/inv_walltowallAuditTrail.php"; break;
				case 141  : $page = "pages/sales/sales_ibmCumPerReport.php";  break;
				case 142  : $page = "pages/sales/sales_paymentsCA.php";  break;
				case 143  : $page = "pages/sales/sales_customerDGSReport.php";  break;
				case 144  : $page = "pages/sales/sales_bceProfileReport.php";  break;
				case 145  : $page = "pages/sales/sales_paymentclassification.php";  break;
				case 146  : $page = "pages/sales/sales_IBMonlineandActiveReport.php"; break;
				case 147  : $page = "pages/sales/sales_cycleReports.php"; break;
				case 148  : $page = "pages/sales/sales_backOrderReport.php"; break;
				case 149  : $page = "pages/sales/sales_IBDBadDebts.php"; break;
				case 150  : $page = "pages/sfm/sfm_recruitersReport.php";  break;	
				case 151  : $page = "pages/datamanagement/datamgt_llreports.php";  break;	
				case 152  : $page = "pages/datamanagement/datamgt_bank.php";  break;
				case 153  : $page = "pages/datamanagement/datamgt_position.php";  break;
				case 154  : $page = "pages/sales/sales_depositslip.php";  break;
				case 155  : $page = "pages/sales/sales_backorderreport_prod.php";  break;
				case 156  : $page = "pages/inventory/inv_RHODiscrepancyReport.php";  break;
				case 157  : $page = "pages/sales/sales_backorderreport_so.php";  break;
				case 158  : $page = "pages/sales/sales_backorderreport_customer.php";  break;
				case 159  : $page = "pages/sales/sales_recordprodexchange.php";  break;
				case 159.1: $page = "pages/sales/sales_recordprodexchangedetails.php";  break;
				case 160  : $page = "pages/sales/sales_vwproductExchange.php";  break;	
				case 160.1: $page = "pages/sales/sales_vwproductExchangeDetails.php";  break;
                                case 162  : $page = "pages/leaderlist/promo_createstplvl.php";  break;
                                case 161  : $page = "pages/leaderlist/promo_stplvl.php";  break;
				default   : $page = "pages/main.php";
			  }		  
			include("$page");		
			if($pid!=-1) include("pages/footer.php");		
	?>
	</body>
</html>
