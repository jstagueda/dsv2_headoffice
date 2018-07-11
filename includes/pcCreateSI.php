<?php 

	require_once("../initialize.php");
	
	/*if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}*/
global $database;	 
if(isset($_POST['btnSave'])) 
{				
		$find = '%';
		$sidiscount = addslashes($_POST['txtSIDisc']);
		$txtocharges = addslashes($_POST['txtOCharges']);		
								
		if(strstr($sidiscount,$find))
		{
			$ispercentdisc = 1;
			$discount = str_replace("%","",$sidiscount);
		} 
		else
		{
			$ispercentdisc = 0;
			if ($sidiscount > 0)
			{
				$discount = $sidiscount;	
			}
			else
			{
				$discount = 0;
			}
		}
		
		if ($txtocharges > 0)
		{
			$ocharges = $txtocharges;			
		}
		else
		{
			$ocharges = 0;
		}
		
		$tmpTxnDate = strtotime($_POST['txtTxnDate']);
		$txndate = date("Y-m-d", $tmpTxnDate);
		$tmpEDate = strtotime($_POST['txtEffectivityDate']);
		$edate = date("Y-m-d", $tmpEDate);

		$DocumentNo = htmlentities(addslashes($_POST['txtDocumentNo']));		 
		$CustomerID = $_POST["hCustomerID"];
		$RefTxnID = $_POST["hTxnID"];
		$TermsID = $_POST['cboPaymentTerms'];
		$SalesmanID = $_POST['cboSalesman'];
		$TotalQty = $_POST['txtTotDelQty'];
		$TotalGrossAmt = str_replace(",","",$_POST['txtGrossAmt']);
		$TotalDiscountAmt = $discount;
		$IsPercentDiscount = $ispercentdisc;
		$VatAmt = $_POST['hVatPercent'];
		$SIDiscount = $discount;
		$IsPercentSI = $ispercentdisc;
		$OtherCharges = $ocharges;
		$TotalNetAmt = str_replace(",","",$_POST['txtNetAmt']);
		$Remarks = htmlentities(addslashes($_POST['txtRemarks']));
		$userid = $session->emp_id;
		$TxnDate = $txndate;
		$EffectivityDate = $edate;
		
		/*echo "'$DocumentNo', $CustomerID, $RefTxnID, $TermsID, $SalesmanID, $TotalQty, $TotalGrossAmt, 
		$TotalDiscountAmt, $IsPercentDiscount, $VatAmt, $SIDiscount, $IsPercentSI, $OtherCharges, $TotalNetAmt, '$Remarks', $userid, '$TxnDate', '$EffectivityDate'";
		exit;*/

		$affected_rows = $sp->spInsertSI($database, $DocumentNo, $CustomerID, $RefTxnID, $TermsID, $SalesmanID, $TotalQty, $TotalGrossAmt, 
		$TotalDiscountAmt, $IsPercentDiscount, $VatAmt, $SIDiscount, $IsPercentSI, $OtherCharges, $TotalNetAmt, $Remarks, $userid, $TxnDate, $EffectivityDate);
		
		$message = "Successfully created Sales Invoice.";
		redirect_to("../index.php?pageid=44&msg=$message");
}
	
	
?>