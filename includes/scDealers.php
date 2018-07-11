<?php
	global $database;
 
	$customertypeid = 0;
	$rs_cboCustomerType = $sp->spSelectCustomerType($database);
	$rs_cboDealerType = $sp->spSelectCustomerType($database);
	$rs_cboIBMNetwork = $sp->spSelectIBMNetwork($database);
	
	$monthFrom = '00';
	$monthTo ='00';
        $yearFrom = '';
        $startyear = '';
        $yearTo = '';
	
	if(isset($_POST['btnGenerate']))
	{
		$customertypeid = $_POST['cboCustomerType'];
		
		if ($_POST['txtSalesFrom'] != '' )
		{
			if($_POST['txtSalesFrom'] != 0)
			{
				$siFrom =  str_replace(",","",$_POST['txtSalesFrom']);
			}
			else
			{
			 	$siFrom = 1 ;
			}
		}		
		else 
		{
			$siFrom = -1 ;
		}
		
		if ($_POST['txtSalesTo'] != '' )
		{
			if($_POST['txtSalesTo'] != 0)
			{
				$siTo =  str_replace(",","",$_POST['txtSalesTo']);
				
			}
			else
			{
			 	$siTo = 1 ;
			}
		}		
		else 
		{
			$siTo = -1 ;
		}
		
		if(isset($_POST['cboMonthFrom']) && !empty($_POST['cboMonthFrom']))
		{
			$monthFrom =  $_POST['cboMonthFrom'];
		}
		else
		{
			$monthFrom = '01';
		}
		
		if (isset($_POST['cboYearFrom']))
		{
			$yearFrom = $_POST['cboYearFrom'];	
		}
		else
		{
			$yearFrom = '1900';
		}
		
		if(isset($_POST['cboMonthTo']) && !empty($_POST['cboMonthFrom']))
		{
			$monthTo =  $_POST['cboMonthTo'];
		}
		else
		{
			$monthTo =  '01';
		}
		
		if (isset($_POST['cboYearTo']))
		{
			$yearTo = $_POST['cboYearTo'];
		}
		else
		{
			$yearTo = '1900';
		}
		
		if (($monthFrom == '01') || ($monthFrom == '03') || ($monthFrom == '05') || ($monthFrom == '07') || ($monthFrom == '08') || ($monthFrom == '10') || ($monthFrom == '12'))
		{
			$daysFrom = '/01/';			
		}
		else if ($monthFrom == '02')
		{
			$daysFrom = '/01/';
		}
		else
		{
			$daysFrom = '/01/';
		}
		
		if (($monthTo == '01') || ($monthTo == '03') || ($monthTo == '05') || ($monthTo == '07') || ($monthTo == '08') || ($monthTo == '10') || ($monthTo == '12'))
		{
			$daysTo = '/31/';			
		}
		else if ($monthTo == '02')
		{
			$daysTo = '/28/';
		}
		else
		{
			$daysTo = '/30/';
		}
			
     	$fromDate = $monthFrom.$daysFrom.$yearFrom;
     	$toDate = $monthTo.$daysTo.$yearTo;
     	
     	/*$txnDatesTo = strtotime($toDate);
		$txnDateTo = date("m/d/Y", $txnDatesTo);
		$txnDatesFrom = strtotime($fromDate);
		$txnDateFrom = date("m/d/Y", $txnDatesFrom);
		*/

		$rs_customerall = $sp->spSelectDealers($database, -1, $customertypeid,$siFrom,$siTo,$fromDate,$toDate);
	}
?>