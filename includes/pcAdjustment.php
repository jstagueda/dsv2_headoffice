<?php

	require_once("../initialize.php");
	global $database;

	isset($_POST['txtDocNo']) ? $dno = $_POST['txtDocNo'] : $dno = '';
	isset($_POST['cboMovementType']) ? $mtypeid = $_POST['cboMovementType'] : $mtypeid = 0;
	isset($_POST['cboWarehouse']) ? $wid = $_POST['cboWarehouse'] : $wid = 0;
	isset($_POST['startDate']) ? $tdate = $_POST['startDate'] : $tdate = date("m/d/Y");
	isset($_POST['txtRemarks']) ? $rem = $_POST['txtRemarks'] : $rem = '';	
	isset($_POST['cboProdLine']) ? $pid = $_POST['cboProdLine'] : $pid = 0;
	isset($_POST['txtSearch']) ? $vSearch = $_POST['txtSearch'] : $vSearch = '';
	
	if(isset($_POST['btnClear']))
	{
	    redirect_to("../index.php?pageid=2&dno=$dno&mtypeid=$mtypeid&wid=$wid&tdate=$tdate&rem=$rem&pid=$pid&search=$vSearch");
	}

	if(isset($_POST['btnSearch']))
	{
		$rs_product = $sp->spSelectProductListInventory2($database, $vSearch, $pid, $wid); 	
	}  

	if(isset($_POST['btnRemoveInv']))
	{
		$tmp_prodlist = $_SESSION['prod_list'];
		//print_r($_SESSION['prod_list']);
		//die();
		
		$n =sizeof($tmp_prodlist);
		
		if(isset($_POST['chkIID'])){
			foreach($_POST['chkIID'] as $key => $value){			
				$prodid = $value;
				
				$wid = $_GET['wid'];

				$rs_proddet = $sp->spSelectProductListInv($database, $prodid, $wid);
				if ($rs_proddet->num_rows)
				{
					while ($row = $rs_proddet->fetch_object()){
						$pid = $row->ProductID;
					}
					
					for ($i = 0; $i < $n; $i++){
						if (isset($tmp_prodlist[$i]))
						{
							if ($pid == $tmp_prodlist[$i]) 
							{
								unset($tmp_prodlist[$i]);
								break;
							}
						}
					}
				}
			    //unset($_SESSION['prod_list']);

				$hasvalue = 0;
				for ($i = 0; $i < $n; $i++)
				{
					if (isset($tmp_prodlist[$i]))
					{
						$_SESSION['prod_list'][] = $tmp_prodlist[$i];
				
						if($hasvalue != ""){
							$hasvalue .= ','.$tmp_prodlist[$i];
						}else{
							$hasvalue .= $tmp_prodlist[$i];
						}
					}
				}
			}
		}
		
		$mid = $_POST['cboMovementType'];
		$docno = $_POST['txtDocNo'];
		redirect_to("../index.php?pageid=2&dno=$docno&mtypeid=$mid&wid=$wid&rem=$remarks&prodlist=$hasvalue");
	}
	
	if(isset($_POST['btnSaveInv']))	
	{
		
		try
		{
			$database->beginTransaction();
			$docno = $_POST['txtDocNo'];
		    $mid = $_POST['cboMovementType'];
		    
		    //if (isset($_POST['cboWarehouse']))
		    //{
		    //	$wid = $_POST['cboWarehouse'];		    	
		    //}
		    //else
		    //{
		    //	$wid = 2;
		    //}
			
		    $wid = $_GET['wid'];
			$transactiondate = date("Y-m-d");
			//$transactiondate = date("Y-m-d", strtotime($_GET['tdate']));		
			$remarks = $_POST['txtRemarks'];
			$createdby = $_SESSION['emp_id'];
			$tdate = date("Y-m-d");
			
			//check status of inventory
			$rs_freeze = $sp->spCheckInventoryStatus($database);
			if ($rs_freeze->num_rows)
			{
				while ($row = $rs_freeze->fetch_object())
				{
					$statusid_inv = $row->StatusID;			
				}		
			}
			else
			{
				$statusid_inv = 20;
			}
			
			if ($statusid_inv == 21)
			{
				$message = "Cannot save transaction, Inventory Count is in progress.";
				redirect_to("../index.php?pageid=2&dno=$docno&mtypeid=$mid&wid=$wid&rem=$remarks&pid=$pid&search=$vSearch&message=$message");				
			}
			else
			{
				//check if document number already exists
				$rs_checkdocno = $sp->spCheckDocumentNumber($database, $docno);
				if($rs_checkdocno->num_rows)
				{
					while($row = $rs_checkdocno->fetch_object())
					{
						$message = "Document number already exist.";
					    redirect_to("../index.php?pageid=2&message=$message&dno=$docno&mtypeid=$mid&wid=$wid&rem=$remarks&pid=$pid&search=$vSearch");
					}
				}
			
				$rs_refNo = $sp->spGetMaxID($database, 7, "inventoryadjustment");
				if($rs_refNo->num_rows)
				{
					while($row = $rs_refNo->fetch_object())
					{
						$trno = $row->txnno;
					}
					
					if ($trno == '')
					{
						$trno = "AD00000001";
					}
				
					$rs_refNo->close();
				}
				
				$rs_AID = $sp->spInsertAdjustment($database, $trno, $mid, $docno, $wid, $transactiondate, $remarks, $createdby);
				if (!$rs_AID)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				if($rs_AID->num_rows)
				{  
					while($row = $rs_AID->fetch_object())
					{
						$AdjID = $row->ID;
					}
				}			

		       	$sort = 1;                      	
		       	for ($i = 0, $n = sizeof($_SESSION['prod_list']); $i < $n; $i++ ) 
		       	{
		       		$rs_proddet = $sp->spSelectProductListInv($database, $_SESSION['prod_list'][$i], $_GET['wid']);
		       		if ($rs_proddet->num_rows)
		       		{
		       			while ($row = $rs_proddet->fetch_object())
		       			{	
		       				$cnt++;
		       				$prodID = $_POST['hdnProductID'.$cnt];
		       				$reasonID = $_POST['cboReasons'.$cnt];
		       				$soh = $row->SOH;
		       				$uomid = 1;
		       				$qty = $_POST['txtQuantity'.$cnt];
		       				
		       				$affected_rows = $sp->spInsertAdjustmentDetails($database, $AdjID, $prodID, $uomid, $soh, $qty, $reasonID);
		       				if (!$affected_rows)
		       				{
		       					throw new Exception("An error occurred, please contact your system administrator.");
	       					}
	       					$sort = $sort + 1;
       					}
	       			}
	       		}
                 
				$database->commitTransaction();	
				$message = "Successfully created Inventory Adjustment.";
				
				if($mid == 10)
				{
					redirect_to("../index.php?pageid=119&msg=$message");					
				}
				else
				{
					redirect_to("../index.php?pageid=3&msg=$message");
				}
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=2&dno=$docno&mtypeid=$mid&wid=$wid&rem=$remarks&pid=$pid&search=$vSearch&message=$message");
		}
	}
	
  	$prod = "";
  	if (isset($_GET['prodlist']) && $_GET['prodlist'] != "")
  	{
  		$prodlist_url = split(',', $_GET['prodlist']);
  		$_SESSION['prod_list'] = $prodlist_url;
      	
		for ($i = 0, $n = sizeof($_SESSION['prod_list']); $i < $n; $i++ ) 
      	{
  			if($prod != "")
      		{
      			$prod .= ','.$_SESSION['prod_list'][$i];
  			}
  			else
  			{
  				$prod .= $_SESSION['prod_list'][$i];
			}
		}
  	}
	redirect_to("../index.php?pageid=2&dno=$dno&mtypeid=$mtypeid&wid=$wid&rem=$rem&pid=$pid&prodlist=$prod&search=$vSearch");
?>