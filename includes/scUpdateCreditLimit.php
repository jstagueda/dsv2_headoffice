<?php
global $database;
$errmsg = "";

if(isset($_POST['btnSearch']))
{
	$creditfrom = str_replace(",","",$_POST['txtIBMfrm']);
	$creditto	= str_replace(",","",$_POST['txtIBMto']);
	$rsCustomerList = $sp->spSelectCustomerCreditLimit($database,2, $creditfrom , $creditto);
}
else
{
	$rsCustomerList = $sp->spSelectCustomerCreditLimit($database,1, 0 , 0);
	
}


if(isset($_POST['btnSave']))
{
	$newCreditLimit = $_POST['txtNewCreditLimit'];
	try
	{
		$database->beginTransaction();			
		
			if(isset($_POST['chkInclude']))
			{
				foreach($_POST['chkInclude'] as $key => $value)
				{		
					$customerID = $value;					
					$oldCreditLimit = $_POST['hAmount'.$customerID];
					$ifExist= 0;
					/*Check if customer exist in creditlimitdetails table
					$rsCheckCreditLimitDetails = $sp->spCheckCreditLimitDetails($database, $customerID);
					
					while($row = $rsCheckCreditLimitDetails->fetch_object())
					{
						$ifExist = $row->cnt;
					}
					if($ifExist == 0)
					{*/						
					$rsInserCreditLimitDetails = $sp->spInsertCreditLimitDetails($database,$oldCreditLimit , $newCreditLimit, $customerID);	
					/*}	
					else
					{
						$rsUpdateCreditLimitDetails = $sp->spUpdateCreditLimitDetails($database,$customerID,$oldCreditLimit,$newCreditLimit);
					}	*/			
					
					
				}
				
		}
		$database->commitTransaction();	
		$message = "Transaction Successful";	
		redirect_to("index.php?pageid=121&message=$message");
	}
	catch (Exception $e)
	{
		$database->rollbackTransaction();
		$message = $e->getMessage();
		redirect_to("index.php?pageid=121&message=$message");
	}
	
}
?>