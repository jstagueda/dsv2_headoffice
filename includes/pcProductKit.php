<?php
	require_once "../initialize.php";
	global $database;
	
	if(isset($_POST['btnSave']))
	{
		try 
		{
			$database->beginTransaction();
			
			$rowCount = $_POST['hcnt'];
			$kitID = $_POST['kitID'];
			$rsDeleteComponent =  $sp->spDeleteProductKitByID($database,$kitID);
			
			for($i = 1 ; $i <= $rowCount ; $i++)
			{
				if (isset($_POST['txtComponentQty'.$i]))
				{
					$componentqty =	$_POST['txtComponentQty'.$i];
					$componentID = $_POST['hProdID'.$i];
					$startdate = date("Y-m-d", strtotime($_POST['startDate'.$i]));
					$enddate = date("Y-m-d", strtotime($_POST['endDate'.$i]));
					$rsInsertComponent =  $sp->spInsertProductKit($database, $kitID, $componentID, $componentqty, $startdate, $enddate);					
				}
			}
		
			$msg = "Successfully linked component(s) to product kit.";
			$database->commitTransaction();
			redirect_to("../index.php?pageid=118&msg=$msg");
		}
		catch (Exception $e) 
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=118&errmsg2=$errmsg");
		}
	}
	
	if(isset($_POST['btnCancel']))
	{
		$errmsg = "Transaction Cancelled.";
		redirect_to("../index.php?pageid=118&errmsg2=$errmsg");
	}
?>