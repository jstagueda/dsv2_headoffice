<?php
	//set variables
	ini_set('error_reporting', E_ERROR);
	if(!isset($_GET['custID']))
	{	
		$custID = 0 ;
	}else {$custID = $_GET['custID'];}
	
	//list of customers
	if(isset($_POST['btnSearch']))
	{
		$custSearchTxt = Trim(addslashes($_POST['txtSearch']));	
		$rs_customer = $sp->spSelectCustomer(-1,$custSearchTxt);
	}
	else
	{
		$rs_customer = $sp->spSelectCustomer(0,"");	
	}
	
	//get customer details
	if(isset($_GET['custID']) && $custID != $tmpCustID )
	{
		
		$custID = $_GET['custID'];
		$rs_customerdet = $sp->spSelectCustomer($custID,"");
		
		if($rs_customerdet->num_rows)
		{
			while($row = $rs_customerdet->fetch_object())
			{
				$custname = $row->Name;
				$custcode = $row->Code;
			}
			$rs_customerdet->close();
		}
	}
	
	//get next orid
	$rs_orno = $sp->spGetMaxID(1, "salesorder");
	if($rs_orno->num_rows)
	{
		while($row = $rs_orno->fetch_object())
		{
			$orno = $row->txnno;
		}
		$rs_orno->close();
	}
?>
