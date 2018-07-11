<?php
global $database;


	$txnid = $_GET['TxnID'];
	$errmsg = "";
	$islocked = 0;
	$lockedby = null;
	$table = 'salesorder';
	
	$rs_header = $sp->spSelectSOHeaderByID($database, $txnid);
	if ($rs_header->num_rows)
	{
		while ($row = $rs_header->fetch_object())
		{			
			$id = $row->SOID;
			$custID = $row->CustomerID;
			$sono = $row->SONo;
			$docno = $row->DocumentNo;
			$status = $row->TxnStatus;			
			$custcode = $row->CustCode;
			$custname = $row->CustomerName;			
			$txndate = $row->TxnDate;	
			$remarks = $row->Remarks;			
			$grossamt = $row->GrossAmount;
			$netamt = number_format($row->NetAmount, 2);
			$paymentTerm = $row->PaymentTerm;
			$vat = $row->VatAmount;
			$vatamt = $grossamt;
			$salesman = $row->Salesman;
			$deliverydate = $txndate;
			$totalCft = $row->TotalCFT;
			$totalCpi = $row->TotalCPI;
			$totalNcft = $row->TotalNCFT;
			$basicDiscount= $row->BasicDiscount;
			$vatAmount= $row->VatAmount;
		    $addtlDiscount = $row->AddtlDiscount;
		    $addtlPrevDiscount = $row->AddtlDiscountPrev;
		    $salesWithVat = $row->SalesWithVat;
		    $totalInvoiceAmountDue = $row->TotalInvoiceAmountDue;
			$ibm = $row->IBM;
			$gsutypeID = $row->GSUTypeID;
			if($row->CustomerTypeID == 99)
			{
				$isEmployee = 1;
			}
			else
			{
				$isEmployee = 0;
			}
		}
		//$rs_header->close();
	}
	$rsServedDetails = $sp->spSelectServedItemsBackOrder($database,$txnid);
	$rsOpenDetails = $sp->spSelectOpenItemsBackOrder($database,$txnid);
	
	if (isset($_POST['btnCancel']))
	{
		//unlock transaction
		/*try
		{
			$database->beginTransaction();*/
			$updatestatus = $sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}			
/*			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=35.2&msg=$errmsg&txnid=$txnid");	
		}*/
		
		redirect_to("index.php?pageid=35");
	}
	if(isset($_POST['btnClose']))
	{
		//unlock transaction
		/*try
		{
			$database->beginTransaction();*/
			$updatestatus = $sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}			
			/*$database->commitTransaction();
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=35.2&msg=$errmsg&txnid=$txnid");	
		}*/
		
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
            redirect_to("index.php?pageid=31&errmsg=$message");               
        }
        else
        {
			$rs_closeSO = $sp->spUpdateSOStatus($database, $txnid);	
			$message = "Successfully closed SO.";
			redirect_to("index.php?pageid=35&msg=$message");
		}
	}
	if(isset($_POST['btnCreate']))
	{
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
            redirect_to("index.php?pageid=31&errmsg=$message");               
        }
        else
        {
			$cnt = $_POST['hcnt'] - 1;
			$valid = 0;
			
			for($i=1 ; $i <= $cnt ; $i++)
			{
				$productID  		= 	$_POST['hProdID'.$i];										
				$promoID			=	$_POST['hPromoID'.$i];					
				$pmgid 				=	$_POST['hPMGID'.$i];
				$qty				=	$_POST['txtOrderedQty'.$i];
				$effectiveprice		= str_replace(",","",$_POST['txtEffectivePrice'.$i]);
				$totalamt			= str_replace(",","",$_POST['txtTotalPrice'.$i]);
				$producttype		=	$_POST['hProductType'.$i];
				$soh				=	$_POST['hSOH'.$i];					
				if ($promoID == '' ||$promoID == 0 )
				{
					$promoID = "null";
				}
				$promotype	=	$_POST['hPromoType'.$i];
				if ($promotype == '')
				{
					$promotype = "null";
				}
				
				$served				=	$_POST['hServed'.$i];
				if($served == 1)
				{
					$valid = 1;
					$break;
				}			
			}
		
			if($valid == 1)
			{
				try
				{
					$database->beginTransaction();		
					$v_CustomerID 			= $_POST['hcustID'];
					$v_DocumentNo			= $_POST['txtRefNo'];
					$v_EmployeeID	 		= $_SESSION['emp_id'];
					$v_WarehouseID			= 1;		
					$v_GrossAmount 			= str_replace(",","",$_POST['txtGrossAmt']);	
					$v_BasicDiscount 		= str_replace(",","",$_POST['txtBasicDiscount']);	
					$v_AddtlDiscount 		= str_replace(",","",$_POST['txtAddtlDisc']);	
					$v_VatAmount 			= str_replace(",","",$_POST['txtVatAmt']);	
					$v_AddtlDiscountPrev	= str_replace(",","",$_POST['txtADPP']);	
					$v_NetAmount			= str_replace(",","",$_POST['txtNetAmt']);
					$v_TotalCPI				= str_replace(",","",$_POST['totCPI']);
					$v_TotalCFT				= str_replace(",","",$_POST['totCFT']);
					$v_TotalNCFT			= str_replace(",","",$_POST['totNCFT']);	
					$v_remarks				= $_POST['txtRemarks'];
					$v_txndate				= date("Y-m-d")	;
					$insertDR = $sp->spInsertDeliveryReceipt($database,$txnid);				
					if (!$insertDR)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					while ($row2 = $insertDR->fetch_object())
					{
						$DRID = $row2->DRID;
					
					}
					$insertSI = $sp->spInsertSIBackOrder($database,$v_CustomerID, $DRID , $v_DocumentNo, $v_txndate,$v_remarks,$v_GrossAmount,$v_TotalCPI,$v_BasicDiscount, $v_TotalCFT, $v_TotalNCFT,$v_AddtlDiscount,$v_VatAmount,$v_AddtlDiscountPrev, $v_NetAmount);
					if (!$insertSI)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					
					while ($row1 = $insertSI->fetch_object())
					{				
						$SIID = $row1->SIID;	
						for($i=1 ; $i <= $cnt ; $i++)
						{
							$productID  		= 	$_POST['hProdID'.$i];										
							$promoID			=	$_POST['hPromoID'.$i];					
							$pmgid 				=	$_POST['hPMGID'.$i];
							$qty				=	$_POST['txtOrderedQty'.$i];
							$effectiveprice		= str_replace(",","",$_POST['txtEffectivePrice'.$i]);
							$totalamt			= str_replace(",","",$_POST['txtTotalPrice'.$i]);						
							$producttype		=	$_POST['hProductType'.$i];
							$soh				=	$_POST['hSOH'.$i];	
							$served				=	$_POST['hServed'.$i];					
							if ($promoID == '' ||$promoID == 0 )
							{
								$promoID = "null";
							}
							$promotype	=	$_POST['hPromoType'.$i];
							if ($promotype == '')
							{
								$promotype = "null";
							}
							if($served == 1)
							{
								//$availSI = 1;
								$outstandingqty = 0;
								$InsertDeliveryReceiptDetails = $sp->spInsertDeliveryReceiptDetails($database,$DRID,$txnid);
	
								$insertSIDetails = $sp->spInsertSIDetailsBackOrder($database, $SIID,$productID,$promoID,$promotype,$pmgid,$effectiveprice,$qty,$totalamt,$i,$producttype)	;	
							}
							else
							{
								$outstandingqty = $qty;
							}
								$rsSelectSODetailsID = $sp->spSelectSODetailsID($database,$productID, $txnid);
								if($rsSelectSODetailsID->num_rows)
								{
									while ($SODID = $rsSelectSODetailsID->fetch_object())
										{
											$sodetais = $SODID->ID;
										}
								}
							
								//echo $sodetais ;
								$updateSODetails = $sp->spUpdateSODetailsBackOrder($database,$txnid,$productID,$outstandingqty,$sodetais);
								if (!$insertSIDetails)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}
										
						}				
						
					}
					
					$rsCheckSO = $sp->spCheckSOforOutstandingQty($database,$txnid);
					if($rsCheckSO->num_rows)
					{
						while ($row = $rsCheckSO->fetch_object())
						{
							$ctr = $row->cnt;
							if($ctr  == 0)
							{
								$rs_closeSO = $sp->spUpdateSOStatus($database, $txnid);		
							}
						}
					}
					$database->commitTransaction();
				}
				catch (Exception $e)
				{
					$database->rollbackTransaction();	
					$errmsg = $e->getMessage()."\n";	
					redirect_to("index.php?pageid=35.2&TxnID=$txnid&msg=$errmsg");	
				}
				redirect_to("index.php?pageid=34&adv=0");
			}
			else
			{
				$errmsg = "Product(s) in list has no SOH.";
				redirect_to("index.php?pageid=35.2&TxnID=$txnid&msg=$errmsg");
			}	
			
			//unlock transaction
			/*try
			{
				$database->beginTransaction();*/
				$updatestatus = $sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
				if (!$updatestatus)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				else
				{
				/*	$database->commitTransaction();
				}
			}
			catch (Exception $e)
			{
				$database->rollbackTransaction();	
				$errmsg = $e->getMessage()."\n";
				redirect_to("index.php?pageid=35.2&msg=$errmsg&txnid=$txnid");	
			}*/
				}
		}
	}
	//check if transaction is locked
	$locked = 0;
	if(isset($_GET['locked']))
	{
		$locked = 1;
	}
	
	if($locked == 0)
	{
	try
	{
		$database->beginTransaction();
		
		$checkiflocked = $sp->spCheckIfTransactionIsLocked($database, 'salesorder', $txnid);
		if (!$checkiflocked)
		{
			$errmsg = "An error occurred, please contact your system administrator.";
			redirect_to("index.php?pageid=35&errmsg=$errmsg");
		}
		else
		{
			if ($checkiflocked->num_rows)
			{
				while ($row = $checkiflocked->fetch_object())
				{
					$islocked = $row->IsLocked;
					$lockedby = $row->FirstName." ".$row->LastName;					
				}				
			}
			
			if ($islocked == 1)
			{		
				$errmsg = "Selected transaction is locked by ".$lockedby;
				redirect_to("index.php?pageid=35&errmsg=$errmsg");
			}
			else
			{
				$updatestatus = $sp->spUpdateLockStatus($database, $table, 1, $session->emp_id, $txnid);
				if (!$updatestatus)
				{
					$errmsg = "An error occurred, please contact your system administrator.";
					redirect_to("index.php?pageid=35&errmsg=$errmsg");
				}				
			}			
		}
		$database->commitTransaction();
	}	
	catch (Exception $e)
	{
		$database->rollbackTransaction();	
		$errmsg = $e->getMessage()."\n";
		redirect_to("index.php?pageid=35&errmsg=$errmsg");	
	}
	}
?>