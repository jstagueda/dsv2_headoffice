<?php
/*
Author: @Gino C. Leabres
BackEnd Validation;
*/
	require_once "../initialize.php";
   	global $database;
	$sessionID= session_id();
	
if(isset($_POST['btnNext']))
		{
			$rsClearTmpTable = $sp->spDeleteTmpProdExchangebySessionID($database, $sessionID);
			$cnt = $_POST['hcnt'];	
			$txnID = $_POST['hSIID'];
			//echo $cnt;
			for($i = 1 ; $i <= $cnt ; $i ++)
			{
				if(isset($_POST['chkID'.$i]))
				{
					$original_qty = $_POST['qty'.$i];
					$qty = $_POST['txtQty'.$i];
					$reason = $_POST['cboReason'.$i];
					$productID = $_POST['hProductID'.$i];
					if($qty <= $original_qty) {
						for($j=1 ; $j<=$qty ; $j++) {
								$rsInsertTmpProdExchange = $sp->spInsertTmpProdExchange($database,$sessionID , $txnID , 1 , $reason , $productID);
						}
					} else{
						//BackEnd Alert;
						echo"<script type='text/javascript'>";	
						echo"alert('Quantity Should Not Exceed');";	
						echo"</script>";	
						redirect_to("../index.php?pageid=159");	
					}
				}
			}
			redirect_to("../index.php?pageid=159.1&txnID=$txnID");	
			
			//redirect_to("index.php?pageid=159.1&txnID=$txnID");		
		}
		
//cancel
   	if(isset($_POST['btnCancel']))
	{
		//$errmsg= "Transaction Cancelled"
		$rsClearTmpTable = $sp->spDeleteTmpProdExchangebySessionID($database, $sessionID);
		
		redirect_to("../index.php?pageid=160&msg=$errmsg");	
	}
	
?>