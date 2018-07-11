<?php
	require_once("../initialize.php");
	global $database;
	
	if(isset($_POST['btnSave']))
	{			
		try
		{

			$database->beginTransaction();
			$ctr = 0;
			//update remarks
			$affected_rows = $sp->spUpdateRecordInvCount($database, $_GET['tid'], $_POST['hdnremarks']);
	  		if (!$affected_rows){
				throw new Exception("An error occurred, please contact your system administrator.");
			}
	  		
	  		//insert added products
			if ($_POST['hrowcnt'] != 0){
				$prodlist_url = explode(',', $_GET['prodlist']);
	          	$_SESSION['prod_list'] = $prodlist_url;
          	 	$n = $_POST['hrowcnt'];
	          	
	          	for ($i = 1; $i <= $n ; $i++ ){
	          		$ctr += 1;
	          		$prodid = $_POST['hdnProductID'.$ctr];			
					$qty = $_POST['txtQuantity'.$ctr];
					//echo $locid ; exit;
					$tag = $_POST['txtCountTag'.$ctr];
					if (isset($_POST['cbolocation'.$ctr]))
					{
						$locid = $_POST['cbolocation'.$ctr];
					}
					else
					{
						$locid = 0;
					}
					if($prodid != ""){
						if($tag == ""){
							$rsCheckifProductExist = $sp->spCheckIfExistCountTag($database, $_GET['tid'], $prodid, $locid);
							if($rsCheckifProductExist->num_rows != 0){
								while($row = $rsCheckifProductExist->fetch_object()){
									$cntTag = $row->tpi_CountTag;	
								}
								$affected_rows = $sp->spUpdateRecordInvCountDetails($database, $cntTag, $qty);
							}else{
								$rsGetMaxCountag = $sp->spSelectMaxCountTag($database, $_GET['tid']);
								if($rsGetMaxCountag->num_rows){
									while($row = $rsGetMaxCountag->fetch_object()){
										$newTag = $row->cnt;
									}
								}
								$rs_details = $sp->spInsertInvCountDetails($database, $_GET['invid'], $prodid, 1, $locid, 'null', $qty, 1, $newTag, 2);	
								if ( !$rs_details ){ throw new Exception("An error occurred, please contact your system administrator."); }
							}
						}else{
							$affected_rows = $sp->spUpdateRecordInvCountDetails($database, $tag, $qty);
							if ( !$affected_rows ){ throw new Exception("An error occurred, please contact your system administrator."); }
						}
					}
	          	}
			}

	  		$database->commitTransaction();
	  		$message = "Successfully recorded Inventory Count.";
  			redirect_to("../index.php?pageid=100&message=$message");
  		}
  		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=100&errmsg=$errmsg");
		}		
	}
	else if(isset($_POST['btnCancel']))
	{
		redirect_to("../index.php?pageid=100");		
	}
	else if (isset($_POST['btnAdd']))
	{
		try
		{
			$database->beginTransaction();
			$arrQty = sizeof($_POST['hdncnttag']);
			$ctr = 0;
			
			//update details
			for($i = 0; $i < $arrQty; $i++)
			{  
				$ctr += 1;
				if ($_POST['txtQuantity'][$i] != "") 
		  		{
		  			$crtdQTY = $_POST['txtQuantity'][$i];
		  			$icdID = $_POST['hdnicdID'][$i];
		  			
		  			$affected_rows = $sp->spUpdateRecordInvCountDetails($database, $icdID, $crtdQTY);
			  		if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
				}
	  		}
	  		$database->commitTransaction();
	  		$txnid = $_GET['tid'];
	  		$prodlist = $_GET['prodlist'];
	  		if (!isset($_GET['locid']))
	  		{
	  			$locid = 0;	  			
	  		}
	  		else
	  		{
	  			$locid = $_GET['locid'];
	  		}
  			redirect_to("../index.php?pageid=100.1&tid=$txnid&prodlist=$prodlist&locid=$locid");
  		}
  		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=100.1&tid=$txnid&prodlist=$prodlist&errmsg=$errmsg");
		}				
	}
?>