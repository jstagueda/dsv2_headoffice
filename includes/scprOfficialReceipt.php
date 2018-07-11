<?php
/*   
  @modified by John Paul Pineda.
  @date October 23, 2012.
  @email paulpineda19@yahoo.com         
*/

	global $database;
	ini_set('error_reporting', E_ERROR);
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	if (isset($_GET['orid']))
	{
		$orid = $_GET['orid'];
	}
	else 
	{
		$orid = 0;
	}
	
	if (isset($_GET['new']))
	{
		$reprnt = ' ';
	}
	else
	{
		$reprnt = 'REPRINT';
	}
	
	
	if (isset($_GET['prntcnt']))
	{
		$prntcnt = $_GET['prntcnt'];
	}

	
	// Initiate Variables 	 
	$permitno 	 = "";
	$branchname  = "";
	$tinno		 = "";
	$address     = "";
	$serversn    = "";
	$branchcode  = "";
	$ornumber    = 0;
	$txndate	 = "";
	$txntime	 = "";
	$memono      = "";
	$customer    = "";
	$totalamt    = 0;
	$vcusid      = 0;
	$totalwords  = "";
	$remarks     = "";
	$amtcomnt    = "";
	$appliesto   = "";
	$vcreatedby  = "";
	

	 
	$rsBranch = $sp->spSelectBranchOR($database);
	if($rsBranch->num_rows)
	{
		while ($row = $rsBranch->fetch_object())
		{
			$permitno 	= $row->PermitNo;
			$branchname = $row->Name;
			$tinno 		= $row->TIN;
		 	$address    = $row->StreetAdd;
			$serversn   = $row->ServerSN;
			$branchcode = $row->Code;			
		}
	}
	
	$rsOfficialReceipt = $sp->spSelectOfficialReceiptByORID($database, $orid);
	if($rsOfficialReceipt->num_rows)
	{
		while ($row = $rsOfficialReceipt->fetch_object())
		{
			//$ornumber	= $branchcode."8".str_pad($row->OfficialReceiptID, 8, "0", STR_PAD_LEFT);
			$ornumber	= $row->OfficialReceiptID;
			$orNo 		= $row->ORNo;
			$txndate	= $row->TxnDate;
			$txntime	= $row->TxnTime;
			$memono		= $row->DocumentNo;
			$remarks    = $row->Remarks;
			$customer   = strtoupper($row->CustomerName);
			$totalamt	= number_format($row->TotalAmount, 2);
			$printctr   = $row->PrintCounter;
			$number  	= explode(".", str_replace(",", "", number_format($row->TotalAmount, 2)));
			$vcreatedby = $row->vcreatedby;
			$vcusid     = $row->vcusID;
			
			if (convert_number($number[1]) == "zero")
			{
				$number[1] = "Only";
			} 
			else 
			{
				$number[1] = " and ".$number[1]." centavos";
			}
			$totalwords  = strtoupper(convert_number($number[0])." PESO(S) ".$number[1]);
			
			if (isset($row->OfficialReceiptCheckID))
			{
				$amtcomnt = "CHEQUE (".$row->CheckNumber.")";
			}
			
			if (isset($row->OfficialReceiptDepositID))
			{
				if ($amtcomnt == "")
				{
					$amtcomnt = "DEPOSIT (".$row->DepositSlipNo.")";
				} 
				else 
				{
					$amtcomnt .= " / DEPOSIT (".$row->DepositSlipNo.")";
				}
			}
		}
	}
	
	$rsOfficialReceiptDetails = $sp->spSelectOfficialReceiptDetailsByORID($database, $orid);
	if($rsOfficialReceiptDetails->num_rows)
	{
		while ($row = $rsOfficialReceiptDetails->fetch_object())
		{			
			$vsiref     = $row->vsiref;
			if ($appliesto == "")
			{
				//$appliesto 	= $branchcode."8".str_pad($row->RefTxnID, 8, "0", STR_PAD_LEFT);
				//$appliesto 	= "";
				$appliesto      = $vsiref; 
			} 
			else 
			{
				//$appliesto 	.= ", ".$branchcode."8".str_pad($row->RefTxnID, 8, "0", STR_PAD_LEFT);
				//$appliesto 	= "";
				$appliesto 	.= ", ".$vsiref;
			}
		}
	}
	/** Fetch Next DUE DATE and Next Due Amt **/
	
	$rsSINextDue = $sp->spSelectNextSIDue($database, $vcusid);
	if($rsSINextDue->num_rows)
	{
		while ($row = $rsSINextDue->fetch_object())
		{			
			$vnextduedate     = $row->vnextduedate;
			$vnextdueamt      = $row->vnextdueamt;
			
		}
	}
	
	$rs_update = $sp->spUpdateORPrntCntr($database, $printctr + 1, $orid);
	if (!$rs_update)
	{ 
		throw new Exception("An error occurred, please contact your system administrator");
	}
	
?>