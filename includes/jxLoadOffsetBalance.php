<?php
   require_once "../initialize.php";
   global $database;

	$custid = $_GET['custid'];
	$acctid = $_GET['acctid'];
	$campid = $_GET['campid'];
	
	$custcommid = 0;
	$balance = 0;
	$rs_balance = $sp->spSelectCustomerBalanceByCommissionCampaignID($database, $custid, $acctid, $campid);
	
	if($rs_balance->num_rows)
	{
		while($row = $rs_balance->fetch_object())
		{
			$balance = number_format($row->OustandingBalance, 2, '.', '');
			$custcommid = $row->ID;
		}
		$rs_balance->close();
	}
	echo "<input name='hdnCustCommID' id='hdnCustCommID' type='hidden' value='$custcommid'>";
	echo "<input name='txtBalance' id='txtBalance' type='text' class='txtfield' readonly='yes' size='25' maxlength='25' style='width:160px' value='$balance'>";
?>