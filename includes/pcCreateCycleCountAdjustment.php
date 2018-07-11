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
		$_SESSION['prodid2']=array();
	    $_SESSION['prodcode2']=array();
	    $_SESSION['prodname2']=array();
	    $_SESSION['soh2']=array();
	    $_SESSION['uomid2']= 1;
	    $_SESSION['uom2']=array();
	    $_SESSION['qty2']=array();
	    $_SESSION['resid2']=array();
	    $_SESSION['res2']=array();
	    redirect_to("../index.php?pageid=58&dno=$dno&wid=$wid&tdate=$tdate&remarks=$rem&pid=$pid&search=$vSearch");
	}
     
//	if(isset($_POST['btnSearch']))
//	{
//		$rs_product = $sp->spSelectProductListInventory2($database, $vSearch, $pid, $wid); 	
//	}

//	if(isset($_POST['btnAdd']))
//	{		
//		$arrQty = sizeof($_POST['hdnProductID']);
//	
//		for($i = 0; $i < $arrQty; $i++)
//		{
//			$hasprod = false;    
//		  	if ($_POST['txtQuantity'][$i] != "") 
//		  	{
//		  		//check if product already exists
//		  		for($a = 0; $a < sizeof($_SESSION['prodid2']); $a++)
//		  		{
//		  			if(($_POST['hdnProductID'][$i] == $_SESSION['prodid2'][$a]) && ($_POST['cboReasons'][$i] == $_SESSION['resid2'][$a]))
//		  		  	{
//		  		  		$hasprod = true;  
//		  		       	$_SESSION['qty2'][$a] = (int)$_POST['txtQuantity'][$i] + (int)$_SESSION['qty2'][$a];
//	  		       	}
//	  		  	}
//	  		  	
//	  		  	if(!$hasprod)
//		  	  	{
//					array_push($_SESSION['prodid2'],$_POST['hdnProductID'][$i]);
//					array_push($_SESSION['prodcode2'],$_POST['hdnProductCode'][$i]);
//					array_push($_SESSION['prodname2'],$_POST['hdnProductName'][$i]);
//					array_push($_SESSION['soh2'],$_POST['hdnSOH'][$i]);
//					//array_push($_SESSION['uomid2'],$_POST['cboUOM'][$i]);
//					array_push($_SESSION['qty2'],$_POST['txtQuantity'][$i]);
//					array_push($_SESSION['resid2'],$_POST['cboReasons'][$i]);
//				              
//					$rs_uom = $sp->spSelectUOMbyID($database, 1);
//					if($rs_uom->num_rows)
//					{
//					      while($row = $rs_uom->fetch_object())
//						  {   
//						      array_push($_SESSION['uom2'],$row->Name);						     				      
//						  }
//					}
//				
//					$rs_reason = $sp->spSelectReasonbyID($database, $_POST['cboReasons'][$i]);
//					if($rs_reason->num_rows)
//					{
//					      while($row = $rs_reason->fetch_object())
//						  {
//						      array_push($_SESSION['res2'],$row->Name);					       
//						  }
//					}
//				}
//	  	  	}
//	  	}
//	}
	
	if(isset($_POST['btnRemoveInv']))
	{
		$tmp_prodlist = $_SESSION['prod_list'];
		$n=sizeof($tmp_prodlist);
		if(isset($_POST['chkIID']))
		{
			foreach($_POST['chkIID'] as $key => $value)
			{			
				$prodid = $value;
				
				$rs_proddet = $sp->spSelectProductListInv($database, $prodid, $_GET['wid']);		
				
				if ($rs_proddet->num_rows)
                    {
                      while ($row = $rs_proddet->fetch_object())
                       {	
 
						$pid = $row->ProductID;

                       }
						for ($i = 0; $i < $n; $i++)
						{					
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
			    unset($_SESSION['prod_list']);
//
//				echo $tmp_prodlist[$i];	
//				exit;
			$hasvalue = 0;
			for ($i = 0; $i < $n; $i++)
			{
				if (isset($tmp_prodlist[$i]))
				{	 

				$_SESSION['prod_list'][] = $tmp_prodlist[$i];
				
					if($hasvalue != "")
					{
						$hasvalue .= ','.$tmp_prodlist[$i];
					}
					else
					{
						$hasvalue .= $tmp_prodlist[$i];
					}
				
				}	
							
			};
		
		
			}
	}
		$mid = $_POST['hdnMTypeID'];
		$docno = $_POST['txtDocNo'];
		$wid = $_POST['cboWarehouse'];
	
		redirect_to("../index.php?pageid=58&dno=$docno&mtypeid=$mid&wid=$wid&remarks=$remarks&prodlist=$hasvalue");

	}
	
	if(isset($_POST['btnSaveInv']))	
	{
		try
		{
			$database->beginTransaction();
		    $docno = $_POST['txtDocNo'];
		    $mid = $_POST['hdnMTypeID'];
		    $wid = $_POST['cboWarehouse'];
			$transactiondate = date("Y-m-d");		
			//$transactiondate = date("Y-m-d", strtotime($_GET['tdate']));		
			$remarks = $_POST['txtRemarks'];
			$createdby = $_SESSION['emp_id'];
			$tdate = date("Y-m-d");
				
			//check if document number already exists
			$rs_checkdocno = $sp->spCheckDocumentNumber($database, $docno);
			if($rs_checkdocno->num_rows)
			{
				while($row = $rs_checkdocno->fetch_object())
				{
					$message = "Document number already exist.";
				    redirect_to("../index.php?pageid=58&message=$message&dno=$docno&wid=$wid&tdate=$tdate&remarks=$remarks&pid=$pid&search=$vSearch");
				}
			}
			
			$rs_AID = $sp->spInsertAdjustment($database, 'null', 5, $docno, $wid, $transactiondate, $remarks, $createdby);
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

			
			
		       if (isset($_GET['prodlist']) && $_GET['prodlist'] != "")
                  {
                     $cnt = 0;
                      
                      $prodlist_url = split(',', $_GET['prodlist']);
                      $_SESSION['prod_list'] = $prodlist_url;
                                                
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
                  }	
			
//			$sort = 1;
//			for ($i = 0, $n = sizeof($_SESSION['prodid2']); $i < $n; $i++ ) 
//			{			
//	            $v_prodid = $_SESSION['prodid2'][$i];
//	            $v_uomid = 1;
//	            $v_prevbal = number_format($_SESSION['soh2'][$i], 0, '.', '');
//	            $v_createdqty = $_SESSION['qty2'][$i];
//	            $v_resid = $_SESSION['resid2'][$i]; 
//				 				
//				$affected_rows = $sp->spInsertAdjustmentDetails($database, $AdjID, $v_prodid, $v_uomid, $v_prevbal, $v_createdqty, $v_resid);
//				if (!$affected_rows)
//				{
//					throw new Exception("An error occurred, please contact your system administrator.");
//				}
//				$sort = $sort + 1;
//			}
				
			
			$database->commitTransaction();	
			$message = "Successfully created Inventory Adjustment.";
			redirect_to("../index.php?pageid=59&msg=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=58&message=$message&dno=$docno&wid=$wid&tdate=$tdate&remarks=$remarks&pid=$pid&search=$vSearch");
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
	redirect_to("../index.php?pageid=58&dno=$dno&wid=$wid&tdate=$tdate&remarks=$rem&pid=$pid&prodlist=$prod&search=$vSearch");
?>
