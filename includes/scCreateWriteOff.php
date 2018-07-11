<?php
	global $database;
	$msg = "";
  	$errmsg = "";
  	$v_Include = 0;
  	$v_Exclude = 0;
  	$strInclude = '';
  	$strExclude = '';  
  	$tmpInclude = '';
  	$tmpExclude = '';
  	
  	if (isset($_GET["errmsg"]))
  	{
  		$errmsg = $_GET["errmsg"];  		
  	}
  	
  	if (isset($_GET["msg"]))
  	{
  		$msg = $_GET["msg"];  		
  	}

   	$rs_reasons = $sp->spSelectReason($database, 3,'');
   	$rs_tpipda = $sp->spSelecttpidPda($database);
   	$rs_gsuType =$sp->spSelecttpiGsuType($database);
   	$rs_dealers = $sp->spSelectDealerForWriteOff($database, 0,0,0);

  	$cnt = 1;
  	$cntE = 1;
  	
	if(isset($_POST['btnGenerate']))
 	{
    	$txtCountInclude = 0;
 		$txtCountInclude = $_POST['txtCountInclude'];
 
		if($txtCountInclude > 0)
		{
	 		for($i = 1; $i <= $txtCountInclude; $i++)
	 		{	
	 			if(isset($_POST["chkIDGSU$i"]))
	 			{ 		
	 				$gsuID = $_POST["chkIDGSU$i"];
	 			}
	 			else
	 			{
	 				$gsuID = 0;
	 			}
	 		
	 			if($gsuID != 0)
	 			{	
	 				if ($cnt == 1)
	 				{
	 					$strInclude = $gsuID;
	 				}
	 				else if ($cnt > 1)
	 				{
	 					$strInclude .= ','.$gsuID;
	 				} 			
	 				$cnt++;
	 				$tmpInclude = $strInclude;	
	 			} 
			}
		}
 	
	 	//for exclude
 		$txtCountExclude = 0;
 		$txtCountExclude = $_POST['txtCountExclude'];
 		
 		if($txtCountExclude > 0)
 		{
	    	for($i = 1; $i <= $txtCountExclude; $i++)
	 		{	
	 			if(isset($_POST["chkID$i"]))
	 			{ 		
	 				$pdaID = $_POST["chkID$i"];
	 			}
	 			else
	 			{
	 				$pdaID = 0;
	 			}
	 		
	 			if($pdaID != 0)
	 			{	
	 				if ($cntE == 1)
	 				{
	 					$strExclude = $pdaID;
	 				}
	 				else if ($cntE > 1)
	 				{
	 					$strExclude .= ','.$pdaID;
	 				}
	 				 			
	 				$cntE++;
	 				$tmpExclude = $strExclude;	
	 			} 
	 		}
 		}
                $AOD = $_POST['asOfDate'];
                
    	redirect_to("index.php?pageid=91&tmpI=$tmpInclude&tmpE=$tmpExclude&AOD=$AOD");
 	}
 
	if(isset($_GET['tmpI']))
	if(isset($_GET['tmpE']))
	$rs_dealers = $sp->spSelectDealerForWriteOff($database, $_GET['tmpI'],$_GET['tmpE'],$_GET['AOD']);
 	 
	if(isset($_POST['btnSubmit']))
	{	
		try
		{
			$database->beginTransaction();
			$createdBy = $session->emp_id;
	      	$approvedBy = null;
	
		   	if(isset($_POST["hdnCountResult"]))
	       	$cntItemOnGrid =  $_POST['hdnCountResult'];
	       	
	       	$chkInclude = $_POST["chkInclude"];
	       	foreach ($chkInclude as $key=>$ID)
	       	{
	       		$hdnPastDue = $_POST["hdnPastDue$ID"];
				$cboReason = $_POST["cboReason$ID"];
	       		
	       		//write-off
	       		$insert = $sp->spInserttpiDealerWriteOff($database, $ID, $hdnPastDue, $cboReason, 23, $createdBy, $approvedBy);
     			if (!$insert)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				//dmcm
 				$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 5);
				while ($row = $rs_birseries->fetch_object())
				{
					$bir_series	= $row->Series;
					$bir_id = $row->NextID;			
				}
				
				$insertDMCM = $sp->spInsertDMCMforWO($database, $ID, $bir_series, $hdnPastDue);
				if($insertDMCM->num_rows)
				{
					while($dmcmdetails = $insertDMCM->fetch_object())
					{
						$dmcmID = $dmcmdetails->DMCMID ;
					}
				}
				
				//sales invoice
				$rsSelectSalesInvoices = $sp->spSelectSalesInvoicesByCustomerID($database, $ID);
				if($rsSelectSalesInvoices->num_rows)
				{	
					while($salesinvoiceList = $rsSelectSalesInvoices->fetch_object())
					{
						$SIID = $salesinvoiceList->ID;
						$OutstandingBalance = $salesinvoiceList->OutstandingBalance;
						$updateSI = $sp->spUpdateSIforWO($database,$SIID);
						
						$rsGetListAR = $sp->spSelectARByInvoiceIDWO($database, $SIID);
						if($rsGetListAR->num_rows)													
						{
							while($ARDetails = $rsGetListAR->fetch_object())
							{
								$ARID = $ARDetails->ID;
								$updateAR = $sp->spUpdateARforWO($database, $ARID);
							}
						}							
						$rsInsertDMCMDetatils = $sp->spInsertDMCMDetailforWO($database, $dmcmID, $SIID, $OutstandingBalance);
					}
				}
	       	}
					 
	     	$database->commitTransaction();
	     	$msg = "Successfully created Write-Off.";
		   	redirect_to("index.php?pageid=91&tmpI=$tmpInclude&tmpE=$tmpExclude&msg=$msg");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=91&tmpI=$tmpInclude&tmpE=$tmpExclude&errmsg=$errmsg");
		}
	}
?>