<?php
	global $database;

	$sessionID= session_id();
	$custID = "";
	$custCode = "";
	$custName = "";
	$ibm = "";
	$sino = "";
	$docno = "";
	$refno = "";
	$branch = "";
	$invoiceDate = "";
	$createdby = "";
	$status = "";
	$confirmedby = "";
	$dateconfirmed = "";
	$remarks = "";	
	
	$txnID = $_GET['TxnID'];
	$rsHeader = $sp->spSelectProductExchangeHeader($database,$txnID);
	if($rsHeader->num_rows)
	{
		while($row = $rsHeader->fetch_object())
		{
			$custID = $row->custID;
			$custCode = $row->custCode;
			$custName = $row->custName;
			$ibm = $row->ibm;
			$sino = $row->SINo;
			$siid = $row->siid;
			$docno = $row->DocumentNo;
			$refno = $row->RefNo;
			$branch = $row->BranchName;
			$invoiceDate = $row->EffectivityDate;
			$createdby = $row->CreatedBy;
			$status = $row->TxnStatus;
			$confirmedby = $row->ConfirmedBy;
			$dateconfirmed = $row->DateConfirmed;
			$remarks = $row->Remarks;	
					
		}
	}
	$rsDetails = $sp->spSelectProductExchangeDetails($database,$txnID);
	
?>