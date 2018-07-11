<?php 

	require_once("../initialize.php");
	
	/*if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}*/ 
	global $database;
	$errmsg="";
	if(isset($_POST['btnDelete'])) 
	{		
		 try
		 {
		  $database->beginTransaction();	
		  $txnid = 0;
		  $txnid = $_POST['hTxnID'];
		  	  
		  $affected_rows = $sp->spDeleteInventoryIn($database, $txnid);
		  if (!$affected_rows)
		  {
		   throw new Exception("An error occurred, please contact your system administrator.");
		  }
		  
		  $database->commitTransaction();
		  $message = "Data Successfully Deleted.";
		  redirect_to("../index.php?pageid=30&msg=$message");
		 }
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
		}		
	}	
	else if(isset($_POST['btnConfirm'])) 
	{		
		
		  try
		  {
		  	
			  $database->beginTransaction();
			  $txnid = 0;
			  $txnid = $_POST['hTxnID'];
			  
			  $rs_detailsall = $sp->spSelectInvInDetailsByID($database,$txnid);
			  
			  if ($rs_detailsall->num_rows)
			  {						
				  while ($row = $rs_detailsall->fetch_object())
				  {
			  		$invid = 0;
			  		$qty = 0;
					$ctr = 0;
					$cnt = 0;
			  
			  		$invid = $_POST['hInvID'];
				  	$qty = $_POST['hQty'];
			  
			  		$rs = $sp->spCheckSOH($database,$invid);			  		
			  		$soh = $row->SOH;
					
					if ($soh < $qty)
					{
						$cnt =+ 1;
					}
				  }
			  }
			
			  $rs_detailsall = $sp->spSelectInvInDetailsByID($database,$txnid);
				
			  if ($cnt < 1)
			  {  
				  if ($rs_detailsall->num_rows)
				  {						
					  while ($row = $rs_detailsall->fetch_object())
					  {
						$invid = 0;
						$qty = 0;					
						$invid = $_POST['hInvID'];
						$qty = $_POST['hQty'];
				 		
						$affected_rows = $sp->spUpdateInventorySOH($database,$invid, $qty);
					    if (!$affected_rows)
						{
						throw new Exception("An error occurred, please contact your system administrator.");
						}
					  }
				  }
				  		  
			  	  $remarks = htmlentities(addslashes($_POST['txtRemarks'])); 
				  $cby = $session->emp_id;
				  
				  $affected_rows = $sp->spUpdateInventoryInConfirm($database,$txnid, $remarks, $cby);
			  	  if (!$affected_rows)
					{
					throw new Exception("An error occurred, please contact your system administrator.");
					}
					
			      $stocklogtypeid = 3;
				  $affected = $sp->spInsertStockLog($database,$txnid, $remarks, $stocklogtypeid);
			 	 if (!$affected)
					{
					throw new Exception("An error occurred, please contact your system administrator.");
					}
					
				  $database->commitTransaction();
				  $message = "Data Successfully Confirmed.";
			  	  redirect_to("../index.php?pageid=30&msg=$message");		
			  }
			  else
			  {
			  	  $message = "Quantity is greater than SOH. Cannot confirm transaction.";
			  	  redirect_to("../index.php?pageid=30&msg=$message");
			  }	
		  }
		  catch(Exception $e)
			{
				$database->rollbackTransaction();
				$errmsg = $e->getMessage()."\n";	
			}	 
	}	
	else 
	{		
		  try
		  {
		  	  $database->beginTransaction();
			  $txnid = 0;
			  $txnid = $_POST['hTxnID'];			  
			  
			  foreach ($_POST['chkID'] as $key=>$value) 
			  {
				  $txnid = $_POST['hTxnID'];	
				  $prodid = $value;
		  
				  $affected_rows = $sp->spDeleteInventoryInDetails($database,$txnid, $prodid);
				  if (!$affected_rows)
					{
					throw new Exception("An error occurred, please contact your system administrator.");
					}
				  
			  }		
			  		
			  $database->commitTransaction();
			  $message = "Data Successfully Removed.";
			  redirect_to("../index.php?pageid=30.1&tid=$txnid&msg=$message");
			  }
			catch(Exception $e)
			{
				$database->rollbackTransaction();
				$errmsg = $e->getMessage()."\n";	
			}	
	}	
	
?>