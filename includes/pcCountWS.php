<?php
	require_once("../initialize.php");
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	if(isset($_POST['btnSave'])) 
	{			
		$rs_checkdocno = $sp->spCheckInvCountDocNo($_GET['docno']);
			
		if($rs_checkdocno->num_rows)
		{
			while($row = $rs_checkdocno->fetch_object())
			{
				$message = "Document number already exist.";
			    redirect_to("../index.php?pageid=2&message=$message");		
			}
		}
		
	
	    $refno = $_POST['txtRefNo'];
	    $docno = $_POST['txtDocNo'];	
		$transactiondate = $_POST['startDate'];
		$remarks = $_POST['txtRemarks'];
		$createdby = $_SESSION['emp_id'];			
		$dmydate = date('Y-m-d',strtotime($_POST['startDate']));
		
		$rs_ICID = $sp->spInsertInvCount($refno, 15, $docno, $dmydate, $remarks, $createdby);
		
			if($rs_ICID->num_rows)
			{  
				while($row = $rs_ICID->fetch_object())
				{
					$InvCID = $row->ID;
				}
			}		
		
			$selected_radio = $_POST['rdAll'];
		
			if($selected_radio == 2)
			{
				$product = $_POST["chkSelect"];
				$sort = 1;
				foreach ($product as $key=>$ID) 
				{
					$prodcode = $_POST["prodcode{$ID}"];
					$prodid = $_POST["prodid{$ID}"];
					
					$rs_WarehouseID = $sp->spSelectWarehouse(0,'');
					
					while ($rowWID = $rs_WarehouseID->fetch_object())
							{
								$rs_LocationID = $sp->spSelectLocationWS($rowWID->ID); 
							
								while ($rowLID = $rs_LocationID->fetch_object())
								{
									$affected_rows = $sp->spInsertInvCountDetails($InvCID, $prodid,1,$rowLID->ID, $rowWID->ID, 0, 0, 1, $sort);
									$sort = $sort + 1;
								}
							}			
								
				}
		
			}
			
			else
			{
				$sort = 1;
				$rs_ProdID = $sp->spSelectProductListWS();
				while ($rowPID = $rs_ProdID->fetch_object())
					{
							$rs_WarehouseID = $sp->spSelectWarehouse(0,'');
					
							while ($rowWID = $rs_WarehouseID->fetch_object())
							{
								$rs_LocationID = $sp->spSelectLocationWS($rowWID->ID); 
								
								while ($rowLID = $rs_LocationID->fetch_object())
								{
									$affected_rows = $sp->spInsertInvCountDetails($InvCID, $rowPID->ProductID,1,$rowLID->ID, $rowWID->ID, 0, 0, 1, $sort);
									$sort = $sort + 1;
								}
							}	
					}
			}
			
		$msg = "Successfully updated Pricing.";
		redirect_to("../index.php?pageid=57&msg=$msg");
	}
	else if(isset($_POST['btnCancel']))
	{
		redirect_to("../index.php?pageid=100");		
	}
?>