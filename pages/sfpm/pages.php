<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 09, 2013
 * @description: Page setter for display includes, since the old developers of DSS
 * made the page include depending on id we just separate it in view page to make it more cleaner. 
 */

class Pages{
    
    /*
     * @return: include file path.
     */
    public function get_page($id = 0){
        return $this->_pages($id);
    }
    
    /*
     * @descritpion: All new file include should be added here.
     * @return: file path for include page.
     */
    private function _pages($pid = 0){
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
            case 66   : $page = "pages/leaderlist/promo_incentives.php"; break;
            case 66.1 : $page = "pages/leaderlist/promo_viewsetincentives.php"; break;
            case 67   : $page = "pages/leaderlist/create_incentives.php"; break;
            //case 67   : $page = "pages/leaderlist/promo_createoverlay.php"; break;
                case 67.1 : $page = "pages/leaderlist/promo_createoverlay_dis.php"; break;
				case 67.2 : $page = "pages/leaderlist/create_incentives.php"; break;
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
            //case 101  : $page = "pages/datamanagement/sync.php"; break; We change UI page...jdymosco 2013-04-17
            case 101  : $page = "pages/datamanagement/dm_dataSyncStartOfDay.php"; break; 
            case 103  : $page = "pages/inventory/inv_ItemAgingReport.php";  break;
            case 104  : $page = "pages/sales/sales_createsalesorder.php";  break;			
            case 105  : $page = "pages/inventory/inv_valuationReport.php";  break;
            case 106  : $page = "pages/inventory/inv_productStatus.php"; break;
            case 107  : $page = "pages/sales/sales_birbackend.php"; break;
            case 108  : $page = "pages/sales/sales_agingreport.php"; break;
            //case 109  : $page = "pages/datamanagement/EOD.php"; break; We change UI page...jdymosco 2013-06-06
            case 109  : $page = "pages/datamanagement/dm_dataSyncEndOfDay.php"; break;
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
            case 163  : $page = "pages/sfm/sfm_rank_param.php";  break;
            case 164  : $page = "pages/sfm/sfm_level_param.php";  break;
            case 165  : $page = "pages/sfm/sfm_kpi_standards_param.php";  break;
            case 166  : $page = "pages/sfm/sfm_level_criteria_param.php";  break;
            case 167  : $page = "pages/sfm/sfm_work_standards_param.php";  break;
            case 168  : $page = "pages/sfm/sfm_enrollment_manager.php";  break;
            case 169  : $page = "pages/sfm/sfm_movement_promotion.php";  break;
            case 170  : $page = "pages/leaderlist/viewloyalty.php";  break;
            case 170.1  : $page = "pages/leaderlist/createloyalty.php";  break;
            case 170.2  : $page = "pages/leaderlist/viewloyaltyrecord.php";  break;
            case 170.3  : $page = "pages/leaderlist/viewLoyaltyLink.php.php";  break;
            case 171  : $page = "pages/sfm/sfm_movement_reversion.php";  break;
            case 172  : $page = "pages/sfm/sfm_candidacy_matrix.php";  break;
            case 173  : $page = "pages/sfm/sfm_candidacy_generator.php";  break;
            case 174  : $page = "pages/sfm/sfm_servicefee_generator.php";  break;
            case 175  : $page = "pages/sfm/sfm_referralfee_generator.php";  break;
            case 176  : $page = "pages/sfm/sfm_servicefee_matrix.php";  break;
            case 177  : $page = "pages/leaderlist/loyaltyuploader.php";  break;
            case 178  : $page = "pages/leaderlist/promo_viewset.php";  break;
            case 178.1 : $page = "pages/leaderlist/promo_createset.php";  break;
            case 179 : $page = "pages/datamanagement/user_interface.php";  break;
            //case 2013 : $page = "pages/sfm/sfm_level_definition_mgt.php";  break;
            case 180  : $page = "pages/sfm/sfm_movement_termination.php";  break;
            case 181  : $page = "pages/sfm/sfm_financial_details_maintenance.php";  break;
            case 182  : $page = "pages/sfm/sfm_adhocCLIncrease_param.php";  break;
            case 183  : $page = "pages/sfm/sfm_adhocCLIncrease_generator.php";  break;
            case 190 : $page = "pages/leaderlist/prodsub_link_to_branches.php";  break;
			case 191 : $page = "pages/sfm/welcomeleter.php"; break;
			case 192 : $page = "pages/sfm/spurringletter.php"; break;
			case 193 : $page = "pages/sfm/demandletter.php"; break;
			
				//reserve for new UI
				case 194 : $page = "pages/datamanagement/holiday.php"; break;
				case 195 : $page = "pages/datamanagement/fco.php"; break;
				case 196 : $page = "pages/datamanagement/branchspecialactivity.php"; break;
			case 197 : $page = "pages/sfm/acct_endorsmnt_coll_agncy_lwyr.php"; break;
			case 200 : $page = "pages/leaderlist/create_bridgingincentives.php"; break;
			case 201 : $page = "pages/leaderlist/viewbridgingincentives.php"; break;
			case 202 : $page = "pages/sales/generate_SIandOR.php"; break;
            case 203 : $page = "pages/bpm/bpm_main.php"; break;
                        case 500 : $page = "pages/bpm/HeadofficesSalesRegister.php"; break;
                        case 501 : $page = "pages/bpm/HeadOfficeSalesRegisterPrint.php"; break;
                        case 502 : $page = "pages/bpm/confirminvoicesho.php"; break;
			case 206 : $page = "pages/bpm/HeadofficesSalesRegister.php"; break;
                        case 505 : $page = "pages/bpm/bpm_DailyBulletReport.php"; break;
                        case 507 : $page = "pages/bpm/bpm_OnlineSalesInquiry.php"; break;
                        case 511 : $page = "pages/bpm/bpm_DailyCashReceipts.php"; break;
                        case 512 : $page = "pages/bpm/bpm_MemoRegister.php"; break;
                        case 513 : $page = "pages/bpm/bpm_AppliedPaymentReport.php"; break;
                        case 516 : $page = "pages/bpm/bpm_CollectionDueReport.php"; break;
                        case 517 : $page = "pages/bpm/bpm_OverdueAgingAccount.php"; break;
            case 204 : $page = "pages/sfpm/sfpm_main.php"; break;
                        case 506 : $page = "pages/sfpm/sfpm_NetworkSalesPerformance.php"; break;
                        case 508 : $page = "pages/sfpm/sfpm_TopSellingReport.php"; break;
                        case 509 : $page = "pages/sfpm/sfpm_StatementOfAccount.php"; break;
                        case 510 : $page = "pages/sfpm/sfpm_AccountBalanceInquiry.php"; break;
                        case 550  : $page = "pages/sfpm/ibm_consolidated_sales_earnings_summary_report.php";  break;
            case 205 : $page = "pages/ipm/ipm_main.php"; break;
                        case 514 : $page = "pages/ipm/ipm_ValuationReport.php"; break;
                        case 515 : $page = "pages/ipm/ipm_TransactionRegister.php"; break;
			case 604 : $page = "pages/sfpm/bcb_main.php"; break;
						case 600 : $page = "pages/sfpm/sfpmDCCR.php"; break;
                        case 601 : $page = "pages/sfpm/sfpmPaymentClassification.php"; break;
                        case 602 : $page = "pages/sfpm/sfpmReturnedCheckSummary.php"; break;
                        case 603 : $page = "pages/sfpm/sfpmContractorsfeeandGrowthBonus.php"; break;
			case 504 : $page = "pages/ipm/backorderhoreport.php"; break;
			case 520 : $page = "pages/datamanagement/reasons.php"; break;

                        case 503 : $page = "pages/access_rights/access_user_role_permissions.php"; break;
                        case 518 : $page = "pages/datamanagement/dm_ParamAccessKeys.php"; break;

            default   : $page = "pages/main.php";                
        }
        
		return $page;
    } 
}
?>
