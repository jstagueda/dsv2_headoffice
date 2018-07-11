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
	$TxnInvoice = $_GET['TxnInvoice'];
	
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
	//$rsDetails 		  	= $sp->spSelectProductExchangeDetails($database,$txnID);
	$rsDetails 		  		= $database->execute("SELECT ped.ProductID, p.ProductLevelID, p.ID prodID, p.Code prodCode, p.ParentID, p.ParentID prodLineID, p.Name prodName,
														 uom.Name uom, t.Code pmg, ped.qty, sd.UnitPrice, sd.`TotalAmount`, r.Name reason, r.ID reasonID, p2.ID exchangeproductID,
														 p2.Code ExchangeCode, p2.Name ExchangeName, tp.StatusID
												  FROM productexchange tp
												  INNER JOIN salesinvoice si ON si.ID = tp.SalesInvoiceID
												  INNER JOIN productexchangedetails ped ON ped.ProductExchangeID = tp.ID
												  INNER JOIN salesinvoicedetails sd ON sd.SalesInvoiceID = si.ID  AND sd.ProductID = ped.ProductID
												  INNER JOIN product p ON p.ID = ped.ProductID
												  LEFT JOIN unittype uom ON uom.ID = sd.UnitTypeID
												  LEFT JOIN `tpi_pmg` t ON t.ID = sd.`PMGID`
												  INNER JOIN reason r ON r.ID = ped.ReasonID
												  INNER JOIN product p2 ON p2.ID = ped.exchangeproductID
												  WHERE tp.ID = ".$txnID);
	$rsDetailsunconfirm	= $sp->spSelectTmpProductExchangeDetails($database,$TxnInvoice,$sessionID);
?>