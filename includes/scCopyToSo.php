<?php
	//set variables
	global $database;
	ini_set('error_reporting', E_ERROR);
	$isAdvance =  $_GET['adv'];
	if(!isset($_GET['custID']))
	{	
		$custID = 0 ;		
	}
	else 
	{
		$custID = $_GET['custID'];
	}
	if(!isset($_POST['hGSUType']))
	{	
		$gsutypeID = "null";		
	}
	else 
	{
		$gsutypeID = $_POST['hGSUType'];
	}
	
	if($isAdvance == 1)
	{
		$rsCheckifAdvPO = $sp->spCheckifAdvPO($database);
		$row = $rsCheckifAdvPO->fetch_object();
		$ifAdvPO = $row->cnt;
		
	}
	$table = 'salesorder';
	
	if(isset($_GET[TxnID]))
	{
		$txnid = $_GET[TxnID];		
		//$rsServedDetails = $sp->spSelectServedItemsBackOrder($database,$txnid);
		$rsOpenDetails = $sp->spSelectCopyToSODetails($database,$txnid);
		$rs_header = $sp->spSelectCopyToSOHeader($database, $txnid);
		if ($rs_header->num_rows)
		{
			while ($row = $rs_header->fetch_object())
			{			
				$id = $row->SOID;
				$custID = $row->CustomerID;
				//$sono = $row->SONo;
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
				$customerStatus = $row->CustomerStatus;
				$createdby = $row->CreatedBy;
				$confirmedby = $row->ConfirmedBy;
				if($row->CustomerTypeID == 99)
				{
					$isEmployee = 1;
				}
				else
				{
					$isEmployee = 0;
				}
			}
		}
		
	}
 	$rsNextSONo = $sp->spSelectNextSONo($database);
	$row = $rsNextSONo->fetch_object();
	$SODOCNo = $row->SONo;
	$branchID = 1;
	
 	
	if(!isset($_POST["txtSODate"]))
	{
		$date = time();
		$sodate = date("m/d/Y",$date);
	}
	else
	{
		$sodate = $_POST["txtSODate"];
	}
	
	if(!isset($_POST["txtDelDate"]))
	{
		$date = time();
		$deldate = date("m/d/Y",$date);
	}
	else
	{
		$deldate = $_POST["txtDelDate"];
	}


	$rs_branch = $sp->spSelectBranch($database, -2, "");
	if ($rs_branch->num_rows)
	{
		while ($row = $rs_branch->fetch_object())
		{
			$branch = $row->Name ;
			$branchID = $row->ID;
		}
	}
	
	$rsEmployee = $sp->spSelectEmployee($database, $_SESSION['emp_id'], "");
	
	//echo $rsEmployee->num_rows;
	if ($rsEmployee->num_rows)
	{
		while ($row = $rsEmployee->fetch_object())
		{
			$employee = $row->Name ;
			
		}
	}
	
	if(isset($_POST['btnConfirmSO']))
	{
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
            	$v_CustomerID 			= $_POST['hCOA'];
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
				$cnt					= $_POST['hcnt'] - 1;
				$termsID	= 0;
				
						$v_statusID = 7 ;
				$rsGetTerms = $sp->spSelectCredittermsByCustID($database, $v_CustomerID);
				if($rsGetTerms->num_rows) 
				{
					while ($row = $rsGetTerms->fetch_object())
					{
						$termsID = $row->TermsID;           
					}  
				}
				if($v_NetAmount <= 500.00)
				{
					$termsID = 1;
				}				
			
				$sessionID = session_id();
				$rsPromoDate = $sp->spSelectPurchaseDateProductList($database,$sessionID); 	
				if($rsPromoDate->num_rows)
				{
					while($getPromoDate = $rsPromoDate->fetch_object())
					{
						$promoDate = $getPromoDate->PurchaseDate;
					}
				}
				
				try
				{
					$database->beginTransaction();
					
					$soheader = $sp->spInsertSOHeader($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,$termsID,$gsutypeID,$promoDate,$_GET['adv'],$branchID);
					
					if (!$soheader)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					
					// remove applied incentives
					$database->execute("delete unclaimedproducts u from " 
						. "unclaimedproducts u inner join productlist on UnclaimedProductsID=u.ID "
						. "where PHPSession='$sessionID'");
					
					$availSI = 0;
					while ($row = $soheader->fetch_object())
					{				
						$SOID = $row->SOID;				
						$session = session_id();						
						for($i=1 ; $i <= $cnt ; $i++)
						{
							$productID  		= 	$_POST['hProdID'.$i];
							$substituteID		= 	$_POST['hSubsID'.$i];
							if($substituteID != 0)
							{
								$productID = $substituteID;
							}
												
							$promoID			=	$_POST['hPromoID'.$i];					
							$pmgid 				=	$_POST['hPMGID'.$i];
							$qty				=	$_POST['txtOrderedQty'.$i];
							$effectiveprice		= str_replace(",","",$_POST['txtEffectivePrice'.$i]);
							$totalamt			= str_replace(",","",$_POST['txtTotalPrice'.$i]);					
							$producttype		=	$_POST['hProductType'.$i];
							$soh				=	$_POST['hSOH'.$i];	
							$served				=	$_POST['hServed'.$i];
							$forIncentive		= $_POST['hForIncentive'.$i];
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
								$availSI = 1;
								$v_outstandingqty = 0;
							}
							else
							{
								$v_outstandingqty = $qty;
							}
						
						
							$insertSODetails = $sp->spInsertSODetails($database,$SOID,$productID, $promoID, $promotype, $pmgid,$qty,$totalamt,$effectiveprice,$i,$producttype,$v_outstandingqty,$forIncentive);
							
							if (!$insertSODetails)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}	
							
							// insert into unclaimedproducts. used for incentives calculation
							for($j=1 ; $j <= $qty ; $j++)
							{
								
								$sp->spInsertTmpUnclaimedProducts($database,$v_CustomerID,$productID, $effectiveprice, $pmgid, $producttype, $SOID);
							}
							
							$database->commitTransaction();
						
						}
					}				            
				     
				}
				//committ
           		 catch (Exception $e)
				{
					$database->rollbackTransaction();	
					$errmsg = $e->getMessage()."\n";
					redirect_to("index.php?pageid=34&msg=$errmsg");	
				}
            }
		
	}
	
	if(isset($_POST['btnSave']))
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
		
		$v_CustomerID 			= $_POST['hCOA'];
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
		$cnt					= $_POST['hcnt'] - 1;
		$termsID	= 0;
		$rsGetTerms = $sp->spSelectCredittermsByCustID($database, $v_CustomerID);
		if($rsGetTerms->num_rows) 
		{
			while ($row = $rsGetTerms->fetch_object())
			{
				$termsID = $row->TermsID;           
			}  
		}
		if($v_NetAmount <= 500.00)
		{
			$termsID = 1;
		}
		
		for($i=1 ; $i <= $cnt ; $i++)
		{
			$qty				=	$_POST['txtOrderedQty'.$i];
			$soh				=	$_POST['hSOH'.$i];
			$served				=	$_POST['hServed'.$i];
			if($served == 0)
			{
				$v_statusID = 7 ;
				break;
			}
			else
			{
				$v_statusID = 9;
			}
		}
		$sessionID = session_id();
		$rsPromoDate = $sp->spSelectPurchaseDateProductList($database,$sessionID); 	
		if($rsPromoDate->num_rows)
		{
			while($getPromoDate = $rsPromoDate->fetch_object())
			{
				$promoDate = $getPromoDate->PurchaseDate;
			}
		}
		
		try
		{
			$database->beginTransaction();
			
			$soheader = $sp->spInsertSOHeader($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,$termsID,$gsutypeID,$promoDate,$_GET['adv'],$branchID);
			
			if (!$soheader)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			
			// remove applied incentives
			$database->execute("delete unclaimedproducts u from " 
				. "unclaimedproducts u inner join productlist on UnclaimedProductsID=u.ID "
				. "where PHPSession='$sessionID'");
			
			$availSI = 0;
			while ($row = $soheader->fetch_object())
			{				
				$SOID = $row->SOID;				
				$session = session_id();						
				for($i=1 ; $i <= $cnt ; $i++)
				{
					$productID  		= 	$_POST['hProdID'.$i];
					$substituteID		= 	$_POST['hSubsID'.$i];
					if($substituteID != 0)
					{
						$productID = $substituteID;
					}
										
					$promoID			=	$_POST['hPromoID'.$i];					
					$pmgid 				=	$_POST['hPMGID'.$i];
					$qty				=	$_POST['txtOrderedQty'.$i];
					$effectiveprice		= str_replace(",","",$_POST['txtEffectivePrice'.$i]);
					$totalamt			= str_replace(",","",$_POST['txtTotalPrice'.$i]);					
					$producttype		=	$_POST['hProductType'.$i];
					$soh				=	$_POST['hSOH'.$i];	
					$served				=	$_POST['hServed'.$i];
					$forIncentive		= $_POST['hForIncentive'.$i];
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
						$availSI = 1;
						$v_outstandingqty = 0;
					}
					else
					{
						$v_outstandingqty = $qty;
					}
				
				
					$insertSODetails = $sp->spInsertSODetails($database,$SOID,$productID, $promoID, $promotype, $pmgid,$qty,$totalamt,$effectiveprice,$i,$producttype,$v_outstandingqty,$forIncentive);
					
					if (!$insertSODetails)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}	
					
					// insert into unclaimedproducts. used for incentives calculation
					for($j=1 ; $j <= $qty ; $j++)
					{
						
						$sp->spInsertTmpUnclaimedProducts($database,$v_CustomerID,$productID, $effectiveprice, $pmgid, $producttype, $SOID);
					}
					
				
				
				}
			
				//$insertSODetails = $sp->spInsertSODetails($SOID,$session);
				if($availSI > 0)
				{
				$insertDR = $sp->spInsertDeliveryReceipt($database,$SOID);
				if (!$insertDR)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				while ($row2 = $insertDR->fetch_object())
				{
					$DRID = $row2->DRID;
					 $InsertDeliveryReceiptDetails = $sp->spInsertDeliveryReceiptDetails($database,$DRID,$SOID);
				}
				$insertSI = $sp->spInsertSalesInvoice($database,$SOID,$DRID);
				if (!$insertSI)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				while ($row1 = $insertSI->fetch_object())
				{				
					$SIID = $row1->SIID;					
					$insertSIDetails = $sp->spInsertSalesInvoiceDetails($database,$SIID,$SOID);				
					if (!$insertSIDetails)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					
					$insertUnlaimedProducts = $sp->spInsertUnclaimedProducts($database,$SIID,$SOID);				
					if (!$insertUnlaimedProducts)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
				}
				
				}	
			}
		
		$database->commitTransaction();
		if($availSI > 0)
		{
			//redirect_to("index.php?pageid=34&adv=0&msg=Successfully created Sales Order");
			redirect_to("index.php?pageid=40.1&tid=$SIID");
		}
		else
		{
			redirect_to("index.php?pageid=35");
		}
		
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=34&msg=$errmsg");	
		}
		
		
		}

	}
	if(isset($_POST['btnDraft']))
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
		try
		{
			$database->beginTransaction();
			
			$v_CustomerID 			= $_POST['hCOA'];
			$v_DocumentNo			= $_POST['txtRefNo'];
			$v_EmployeeID	 		= 1;
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
			$cnt					= $_POST['hcnt'] -1 ;		
			$v_statusID 			= 6 ;
			
			if($v_GrossAmount == '')
			{
				$v_GrossAmount = 0.00;	
			}
			
			if($v_BasicDiscount == '')
			{
				$v_BasicDiscount = 0.00;	
			}
			if($v_AddtlDiscount == '')
			{
				$v_AddtlDiscount = 0.00;	
			}
			if($v_VatAmount == '')
			{
				$v_VatAmount = 0.00;	
			}
			if($v_AddtlDiscountPrev == '')
			{
				$v_AddtlDiscountPrev = 0.00;	
			}
			if($v_NetAmount == '')
			{
				$v_NetAmount = 0.00;	
			}
			if($v_TotalCPI == '')
			{
				$v_TotalCPI = 0.00;	
			}
			if($v_TotalCFT == '')
			{
				$v_TotalCFT = 0.00;	
			}
			if($v_TotalNCFT == '')
			{
				$v_TotalNCFT = 0.00;	
			}
			if($isAdvance == 1)
			{		
				$sessionID = session_id();
				$rsPromoDate = $sp->spSelectPurchaseDateProductList($database,$sessionID); 	
				if($rsPromoDate->num_rows)
				{
					while($getPromoDate = $rsPromoDate->fetch_object())
					{
						$promoDate = $getPromoDate->PurchaseDate;
					}
				}	
				$soheader = $sp->spInsertSOHeader($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,1,$gsutypeID,$promoDate,$_GET['adv'],$branchID);
			}
			else
			{
				$soheader = $sp->spInsertSOHeaderDraft($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,1,$gsutypeID);
			}	
			if (!$soheader)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			while ($row = $soheader->fetch_object())
			{
				$SOID = $row->SOID;				
				$session = session_id();				
				for($i=1 ; $i <= $cnt ; $i++)
				{
					$productID  		= 	$_POST['hProdID'.$i];	
					$substituteID		= 	$_POST['hSubsID'.$i];
					if($substituteID != 0)
					{
						$productID = $substituteID;
					}									
					$promoID			=	$_POST['hPromoID'.$i];					
					$pmgid 				=	$_POST['hPMGID'.$i];
					$qty				=	$_POST['txtOrderedQty'.$i];
					$forIncentive		= $_POST['hForIncentive'.$i];
					$effectiveprice		= str_replace(",","",$_POST['txtEffectivePrice'.$i]);
					$totalamt			= str_replace(",","",$_POST['txtTotalPrice'.$i]);	
					if($totalamt == '')
					{
						$totalamt = 0.00;	
					}
					$producttype		=	$_POST['hProductType'.$i];
					$soh				=	$_POST['hSOH'.$i];	
					$v_outstandingqty	= $qty;				
					if ($promoID == '' ||$promoID == 0 )
					{
						$promoID = "null";
					}
					$promotype	=	$_POST['hPromoType'.$i];
					if ($promotype == '')
					{
						$promotype = "null";
					}				
					
						$insertSODetails = $sp->spInsertSODetails($database,$SOID,$productID, $promoID, $promotype, $pmgid,$qty,$totalamt,$effectiveprice,$i,$producttype,$v_outstandingqty,$forIncentive);
						if (!$insertSODetails)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					
				
				
				}
			}
			
			$database->commitTransaction();
			
		
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";	
		}
		redirect_to("index.php?pageid=35");
		}
	}	
	
	if(isset($_POST['btnSaveSO']))
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
            try
			{
				$database->beginTransaction();
				$v_CustomerID 			= $_POST['hCOA'];
				$v_DocumentNo			= $_POST['txtRefNo'];
				$v_EmployeeID	 		= 1;
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
				$cnt					= $_POST['hcnt'] -1 ;		
				$v_statusID 			= 7 ;
				if($v_GrossAmount == '')
				{
					$v_GrossAmount = 0.00;	
				}
				
				if($v_BasicDiscount == '')
				{
					$v_BasicDiscount = 0.00;	
				}
				if($v_AddtlDiscount == '')
				{
					$v_AddtlDiscount = 0.00;	
				}
				if($v_VatAmount == '')
				{
					$v_VatAmount = 0.00;	
				}
				if($v_AddtlDiscountPrev == '')
				{
					$v_AddtlDiscountPrev = 0.00;	
				}
				if($v_NetAmount == '')
				{
					$v_NetAmount = 0.00;	
				}
				if($v_TotalCPI == '')
				{
					$v_TotalCPI = 0.00;	
				}
				if($v_TotalCFT == '')
				{
					$v_TotalCFT = 0.00;	
				}
				if($v_TotalNCFT == '')
				{
					$v_TotalNCFT = 0.00;	
				}
				if($isAdvance == 1)
				{		
					$sessionID = session_id();
					$rsPromoDate = $sp->spSelectPurchaseDateProductList($database,$sessionID); 	
					if($rsPromoDate->num_rows)
					{
						while($getPromoDate = $rsPromoDate->fetch_object())
						{
							$promoDate = $getPromoDate->PurchaseDate;
						}
					}	
					$soheader = $sp->spInsertSOHeader($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,1,$gsutypeID,$promoDate,$_GET['adv'],$branchID);
				}
				else
				{
					$soheader = $sp->spInsertSOHeaderDraft($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,1,$gsutypeID);
				}	
				while ($row = $soheader->fetch_object())
				{				
					$SOID = $row->SOID;				
					$session = session_id();						
					for($i=1 ; $i <= $cnt ; $i++)
					{
						$productID  		= 	$_POST['hProdID'.$i];
						$substituteID		= 	$_POST['hSubsID'.$i];
						if($substituteID != 0)
						{
							$productID = $substituteID;
						}
											
						$promoID			=	$_POST['hPromoID'.$i];					
						$pmgid 				=	$_POST['hPMGID'.$i];
						$qty				=	$_POST['txtOrderedQty'.$i];
						$effectiveprice		= str_replace(",","",$_POST['txtEffectivePrice'.$i]);
						$totalamt			= str_replace(",","",$_POST['txtTotalPrice'.$i]);					
						$producttype		=	$_POST['hProductType'.$i];
						$soh				=	$_POST['hSOH'.$i];	
						$served				=	$_POST['hServed'.$i];
						$forIncentive		= $_POST['hForIncentive'.$i];
						if ($promoID == '' ||$promoID == 0 )
						{
							$promoID = "null";
						}
						$promotype	=	$_POST['hPromoType'.$i];
						if ($promotype == '')
						{
							$promotype = "null";
						}
						
						
						
							$v_outstandingqty = $qty;
						
					
					
						$insertSODetails = $sp->spInsertSODetails($database,$SOID,$productID, $promoID, $promotype, $pmgid,$qty,$totalamt,$effectiveprice,$i,$producttype,$v_outstandingqty,$forIncentive);
						
						if (!$insertSODetails)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}	
						
						// insert into unclaimedproducts. used for incentives calculation
						for($j=1 ; $j <= $qty ; $j++)
						{
							
							$sp->spInsertTmpUnclaimedProducts($database,$v_CustomerID,$productID, $effectiveprice, $pmgid, $producttype, $SOID);
						}
						
					
					
					}
				}
				
				$database->commitTransaction();
				
			}
	       catch (Exception $e)
			{
				$database->rollbackTransaction();	
				$errmsg = $e->getMessage()."\n";	
			}
			redirect_to("index.php?pageid=35");
			
			

       }
	}
	if(isset($_POST['btnCreatBO']))
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
			$cnt = $_POST['hcnt'];
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
					$txnid 					= $_POST['hTxnID'];
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
	
	//check if transaction is locked
	
	}
	if (isset($_POST['btnBackToList']))
	{
		//unlock transaction
		
		try
		{
			$database->beginTransaction();
			$txnid 					= $_POST['hTxnID'];
			$updatestatus = $sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}			
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=35.1&msg=$errmsg&txnid=$txnid");	
		}
		
		redirect_to("index.php?pageid=35");
	}
	
	if(isset($_POST['btnCloseBO']))
	{
		//unlock transaction
		/*try
		{
			$database->beginTransaction();*/
			$txnid 					= $_POST['hTxnID'];
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
	

?>