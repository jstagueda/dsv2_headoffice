<?php
	require_once("../initialize.php");
	include CS_PATH.DS."ClassInventory.php";
	include "../service/function_sidprocess.php";
	global $database;

	$OSTYPE     = GetSettingValue($database, 'OSTYPE'); 
	
	if($OSTYPE == 'WINDOWS')
	{
	    //require("/class/config-ms.php");
	    //require("../class/live-config-ms.php");
	    //$servername = "10.132.50.245\sqlexpress";
		$servername = "10.132.50.203";
		$port       = "49239"; 
		$username   = "sa";
		$password   = "Jde@2018";
        $dbname     = "prod_jdestaging";

		try
		{		
				//$ms_conn = new PDO( "sqlsrv:server=$servername;database=$dbname",$username,$password);   // please do not delete.used to coonect if terminal is windows operated.
				$ms_conn = new PDO("dblib:host=$servername:$port;dbname=$dbname",$username,$password);
				$ms_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
			die();
		}
	}else
	{
	   //require("../class/config-ms.php");
	}
	
  	if(isset($_POST['btnConfirm'])) 
	{	
		try
		{
			$database->beginTransaction();
			
			$ctr        = 0;
			$txnid      = htmlentities(addslashes($_POST['htxnid']));
			$docno      = htmlentities(addslashes($_POST['txtDocNo']));
			$remarks    = htmlentities(addslashes($_POST['txtRemarks']));
			$tmptxndate = $_POST['txtTxnDate'];
			$txndate    = date('Y-m-d',strtotime($_GET['$tmptxndate']));
			$witherror  = 0;
			
			#data validation
			$datavalq = $database->execute("SELECT ic.BranchID BranchID, invd.ID invdid, p.ID ProductID, p.Code pCode, p.3rd_ItemNumber ItemNumber, p.Name pName, uom.name UOM, ic.`WarehouseID`,invd.RegularPrice,
														 ic.DocumentNo, CAST(CONCAT('IC', REPEAT('0', (8- LENGTH(ic.ID))), ic.ID) AS CHAR) TxnNo,
														 ware.code DGorWH, invd.CHfreezeQty , invd.HOFreezeQty, invd.HOLocation, invd.CHCreatedQty, invd.AdjustmentQty,
														 mt.Code Movementcode , p.GLClass GLClass , ifnull(p.2nd_ItemNumber,'') jdeitem, IFNULL(p2.code,'') productline		 
											FROM inventorycountchdetails invd
											INNER JOIN inventorycountch ic ON ic.`id` = invd.`inventorycountid`
											INNER JOIN product p ON p.`ID`  =  invd.`ProductID`
											LEFT JOIN product p2 ON p2.`ID`  =  p.ParentID
											INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
											INNER JOIN warehouse ware ON ware.id = invd.`WarehouseID`
											INNER JOIN movementtype mt ON mt.ID = ic.MovementTypeID
											WHERE invd.`InventoryCountID` = $txnid
											order by invd.`WarehouseID` , p.Code
										 ");
		    if ($datavalq->num_rows)
			{  
				while ($invq = $datavalq->fetch_object())
				{
				   $ctr = $ctr + 1;
                   $location   = getBranchPlantLocation('3505001',$invq->BranchID ,$invq->DGorWH);	
				   
                   if($ctr == 1)
				   {
					   $doctype         = getMnemonicCode('JDE_INV_DOCTYPE',$invq->Movementcode); 
				       $branchplant_to  = getMnemonicCode('JDE_OTH_B/P',$invq->Movementcode);
					   if($doctype == '')
					   {
							  $witherror = 1;
							  $errmessage = $errmessage.','.'Invalid Document Type: Document Type:'.$doctype;		
					   }
				   }					   
 
				   if($location == '')
				   {
					   $witherrror = 1;
					   $errmessage = $errmessage.','.'Invalid Location: Product Code:'.$invq->pCode; 
				   }
						
			       if($invq->GLClass == '')
				   {
					   $witherrror = 1;
					   $errmessage = $errmessage.','.'Invalid FG Class: Product Code:'.$invq->pCode; 	 
				   }
				   
				   if($invq->jdeitem == '')
				   {
					  if($invq->AdjustmentQty != 0)
					  {
						  $witherrror = 1;
					      $errmessage = $errmessage.','.'Product Code does not exist in JDE: Product Code:'.$invq->pCode;
					  } 
				   }
				   
				   $PL  = $invq->productline;
				   if(strlen($PL) > 3)
				   {
					  $witherrror = 1;
					  $errmessage = $errmessage.','.'Invalid Product Line: Product Code:'.$invq->pCode; 
				   }
				   if($PL == '')
				   {
					  $witherrror = 1;
					  $errmessage = $errmessage.','.'Invalid Product Line: Product Code:'.$invq->pCode;
				   } 	
			    }
			}			
			
			if($witherror == 1)
			{
				echo '<script language="javascript">';
				echo 'alert("'.$errmessage.'")';
				echo '</script>';
			}
			else
			{
				$ctr = 0;
				$rs_detailsall2 =   $database->execute("SELECT ic.BranchID BranchID, invd.ID invdid, p.ID ProductID, p.Code pCode, p.3rd_ItemNumber ItemNumber, p.Name pName, uom.name UOM, ic.`WarehouseID`,invd.RegularPrice,
															 ic.DocumentNo, CAST(CONCAT('IC', REPEAT('0', (8- LENGTH(ic.ID))), ic.ID) AS CHAR) TxnNo,
															 ware.code DGorWH, invd.CHfreezeQty , invd.HOFreezeQty, invd.HOLocation, invd.CHCreatedQty, invd.AdjustmentQty,
															 mt.Code Movementcode , p.GLClass GLClass , p.2nd_ItemNumber jdeitem, IFNULL(p2.code,'') productline, 
															 DATE(NOW()) filedate
														FROM inventorycountchdetails invd
														INNER JOIN inventorycountch ic ON ic.`id` = invd.`inventorycountid`
														INNER JOIN product p ON p.`ID`  =  invd.`ProductID`
														LEFT JOIN product p2 ON p2.`ID`  =  p.ParentID
														INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
														INNER JOIN warehouse ware ON ware.id = invd.`WarehouseID`
														INNER JOIN movementtype mt ON mt.ID = ic.MovementTypeID
														WHERE invd.`InventoryCountID` = $txnid
														  and ic.statusid = 6
														order by invd.`WarehouseID` , p.Code
													  ");
				
				if ($rs_detailsall2->num_rows)
				{
					while ($inv = $rs_detailsall2->fetch_object())
					{
						
						$ctr = $ctr + 1;
						
						if($ctr == 1)
						{ // create header 
							$branchID        = $inv->BranchID;
							$filedate        = $inv->filedate;
							$movement        = $inv->Movementcode;
							$doctype         = getMnemonicCode('JDE_INV_DOCTYPE',$inv->Movementcode); 
							$branchplant_to  = getMnemonicCode('JDE_OTH_B/P',$inv->Movementcode);
							$EXPLANATION     = $inv->Movementcode.'-'.$inv->TxnNo;
							
				            $BusinessUnit  = $database->execute(" SELECT b.BusinessUnit BusinessUnit FROM branch b WHERE b.id = $branchID ")->fetch_object()->BusinessUnit;
				   
							if($doctype == '')
							{
								throw new Exception("Invalid Line Details-Document Type for.".$doctype);
								$witherror = 1;
							}
							
							$recctr           = GetINVCount($filedate,$doctype)  + 1;
							$filedate2        = date('ymd',strtotime($filedate));
							$DOC_NO_ORI       = '35-02-'.$doctype.'-'.$filedate2.'-'.sprintf("%05d",$recctr);	
							$linectr          = 0;
							$From_BranchPlant = '3505001'; 
							$bypassDMAAI      = getMnemonicCode('JDE_BYPASS_DMAAI',$doctype); 
							
							$location    = getBranchPlantLocation($From_BranchPlant,$branchID,$inv->DGorWH);
							if($location == '')
							{
								throw new Exception("Invalid Location "); 
							}
							$location   = '';
							if($branchplant_to != '')
							{
								$location   = getBranchPlantLocation($branchplant_to,$branchID,$inv->DGorWH);
								if($location == '')
								{
									throw new Exception("Invalid Location "); 
								}
							}
						}
						else
						{ //create detail 
							$PL      = '';  $reasoncode = '';
							$PLX     = '';  $ACCOUNT = '';
							if($bypassDMAAI == 'YES')
							{ #get GL Class
								 if($inv->GLClass == '')
								 {
									throw new Exception("Invalid FG Class");
								 }
								 else
								 {
									$ACCOUNT  = getMnemonicCode('JDE_INV_ACCT',$doctype.'-'.$inv->GLClass);										  
									$ACCOUNT  = $BusinessUnit.'.'.$ACCOUNT;
													  
									$requireSUBledger = getMnemonicCode('JDE_INV_REQSUBLEDGER',$doctype); 
									if($requireSUBledger == 'YES')
									{
										$PL  = $inv->productline;
										if(strlen($PL) > 3)
										{
										   throw new Exception("Invalid Product Line");
										}
										$PLX = 'X';
										if($PL == '')
										{
										   throw new Exception("Invalid Product Line");
										} 
									}  
								}
							}
							
							#create BP channel
							$linectr    = $linectr + 1;
							$location   = getBranchPlantLocation('3505001',$branchID ,$inv->DGorWH);	
							if($location == '')
							{
								throw new Exception("Invalid Location");
							}
							
							$HOLD_CODE  = getHoldCode('3505001',$branchID,$inv->DGorWH);
							if($inv->AdjustmentQty <> 0)	
							{
								$dtlctr = $dtlctr + 1;
								createINVdetail($DOC_NO_ORI,$doctype,$filedate,$EXPLANATION,'3505001',$dtlctr,$location,$inv->jdeitem,'',$inv->AdjustmentQty,$reasoncode,
												$ACCOUNT,$HOLD_CODE,$inv->Movementcode,$PL,$PLX);
							}
							
						 }
					}
					
					if ($dtlctr <> 0)
					{
						createINVheader($DOC_NO_ORI,$doctype,$filedate,$EXPLANATION,'3505001',$dtlctr,$movement);
					}
					
					$database->execute(" update inventorycountch 
					                     set inventorycountch.statusid = 7
										 where inventorycountch.id = $txnid
                   					   ");
                    #update status
				}
				$database->commitTransaction();
				$message = "Successfully confirmed Inventory Count.";
				redirect_to("../index.php?pageid=760&msg=$message");  				
			}
			if($witherror == 1)
			{
			  // redirect_to("../index.php?pageid=760.1&tid=$txnid");
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			//redirect_to("../index.php?pageid=760.1&errmsg=$errmsg&tid=21");		
		}
	}	
?>
