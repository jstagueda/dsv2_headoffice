<?php
/*
 * @author: jdymosco
 * @update: Improved the page code, added pagination, updated search functionality.
 * @date: Feb 06, 2013
 */
	global $database;
	$custID = 0;
	$code = "";
	$name = "";
	$address = "";
	$txnDate = "";
	$ibmcode = "";
	$dealersearch = "";	
	$pastDue = "";
        $penalty = 0.00;
        $writeoff = 0.00;
        $start = 0;
        $limit = 20;
        $rs_customer_total = 0;
        $otherPagingConcat = '';
        $show_update = false;
        
        $page = isset($_GET['paging']) ? $_GET['paging'] : 1;
        $start = (($page - 1) * $limit);
	
	if(isset($_GET['msg']))
	{
		$errmsg = $_GET['msg'];
	}
	else
	{
		$errmsg = "";
	}
		
	if(isset($_POST['btnSearch']) || (isset($_GET['IBMCodeSearch']) || isset($_GET['fldsearch'])))
	{
		$ibmcode = (!isset($_GET['IBMCodeSearch'])) ? addslashes($_POST['txtfldIBMCodeSearch']) : addslashes($_GET['IBMCodeSearch']);
		$dealersearch = (!isset($_GET['fldsearch'])) ? addslashes($_POST['txtfldsearch']) : addslashes($_GET['fldsearch']);
		$rs_customer = $sp->spSelectInactiveCustomer($database, -1, $ibmcode, $dealersearch, $start, $limit);
                $rs_customer_total = $sp->spSelectGetTotalInActiveCustomer($database,$ibmcode, $dealersearch);
                $otherPagingConcat = '&IBMCodeSearch='.$ibmcode.'&fldsearch='.$dealersearch;
	}	
	elseif(isset($_GET['txtfldsearch']))
	{
		$custSearchTxt = addslashes($_GET['txtfldsearch']);	
		$rs_customer = $sp->spSelectInactiveCustomer($database, -1, $ibmcode, $dealersearch, $start, $limit);
                $rs_customer_total = $sp->spSelectGetTotalInActiveCustomer($database,$ibmcode, $dealersearch);
	}
	else
	{
		$rs_customer = $sp->spSelectInactiveCustomer($database, 0, "", "", $start, $limit);
                $rs_customer_total = $sp->spSelectGetTotalInActiveCustomer($database,$ibmcode, $dealersearch);
	}
        
        //Used for pagination...
        if($rs_customer_total->num_rows > 0) $rs_customer_total = $rs_customer_total->num_rows;
        else $rs_customer_total = 0;
        
        $total_pages = ceil($rs_customer_total / $limit); //Total pages to be used in pagination..
        
	if(isset($_GET['custid']))
	{
		$custID = $_GET['custid'];
                $show_update = true;
		$rs_customerdetails =  $sp->spSelectInactiveCustomer($database, $custID, "", "", $start, $limit);		
		
		if($rs_customerdetails->num_rows)
		{
			while($row = $rs_customerdetails->fetch_object())
			{
				$code=$row->Code;
				$name=$row->Name;
				$lname = $row->LastName;
				$fname = $row->FirstName;
				$mname = $row->MiddleName;
				$date_terminated = $row->DateTerminated;
                                $penalty = $row->CustomerPenalty;
                                $writeoff = $row->CustomerWriteOff;
			}
			$rs_customerdetails->close();
		}
		
		$rsPastDue = $sp->spSelectPastDuebyCustID($database,$custID);
		if($rsPastDue->num_rows)
		{
			while($rowpastDue = $rsPastDue->fetch_object())
			{
				$pastDue =$rowpastDue->OutstandingAmount;
			}
			$rsPastDue->close();
		}
		
		//get latest IBM
		$rs_ibm = $sp->spSelectCustomerLatestIBM($database,$_GET['custid']);
		if($rs_ibm->num_rows)
		{
			while($row = $rs_ibm->fetch_object())
			{
				$old_ibmcode = $row->IBMCode;				
				$old_ibmname = $row->IBMName;
			}
			$rs_ibm->close();
		}
	}	
?>