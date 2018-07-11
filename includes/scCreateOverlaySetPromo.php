<?php
	ini_set('display_errors',0);
	global $database;
	$errmsg="";
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)) . " +1 month");
	$end = date("m/d/Y",$tmpdate);
	
	if(isset($_SESSION['buyins']))
	{
		$bcnt = $_SESSION['buyins'];
		$ctr = sizeof($_SESSION['buyins']) + 1;		
	}
	else
	{
		$bcnt = 0;
		$ctr = 1;	
	}
	
	//get gsu type
	$rs_gsutype = $sp->spSelectGSUType($database);
	$rs_gsutype_save = $sp->spSelectGSUType($database);
	
	//get product level list
	$rsprodlevel = $sp->spSelectProductLevel($database);
	
	//get pmg
	$rs_pmg = $sp->spSelectPMG($database);
	$rs_pmg2 = $sp->spSelectPMG($database);
	
	//for dynamic combo box
	$index = 0;
	$pmg_id = "";
	$pmg_code = "";
	if($rs_pmg2->num_rows)
	{
		while($row = $rs_pmg2->fetch_object())
		{
			$index += 1;
			if ($index == $rs_pmg2->num_rows)
			{
				$pmg_id = $pmg_id.$row->ID;
				$pmg_code = $pmg_code."'".$row->Code."'";
			}
			else
			{
				$pmg_id = $pmg_id.$row->ID.", ";				
				$pmg_code = $pmg_code."'".$row->Code."'".", ";
			}	
		}
		$rs_pmg2->close();
	}
	
	//get product selection
	if (isset($_POST['cboRange']))
	{
		$levelid = $_POST['cboRange'];
		$inc = $_GET['inc'];
	} 
	else
	{
		$levelid = 0;		
	}
	$rsprodselection = $sp->spSelectProductListByLevelID($database, $levelid, "");

	if (isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=178&inc=1");			
	}
	else if (isset($_POST['btnAdd']))
	{
		$tmpsdate = strtotime($_POST['txtStartDate']);
		$startdate = date("Y-m-d", $tmpsdate);
		$tmpedate = strtotime($_POST['txtEndDate']);
		$enddate = date("Y-m-d", $tmpedate);
		
		//purchase reqt type
		if(!isset($_POST['cboPReqtType']))
		{
			$preqttype = $prtype;			
		}
		else
		{
			$preqttype = $_POST['cboPReqtType'];
		}
		
		if(isset($_SESSION['buyins']))	
		  {
			if (sizeof($_SESSION['buyins']))
			   {
				$buyin_list = $_SESSION["buyins"];
				$rowalt=0;
									//while($row = $rspromobuyin->fetch_object())
				for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ ) 
					{
						$preqttype = $buyin_list[$i]['PReqType'];
					}
					
			   }
		  }
		
		//buy-in requirement
		$range = $_POST["cboRange"];
		$prodid = $_POST["hProdID_criteria"];
		$breqid = $_POST['rdoBReqt'];
		if ($breqid == 2)
		{
			$criteria = $_POST["cboPHCriteria"];
			$minimum = $_POST["txtPHMinimum"];
			// $br_startdate = 'null';			
			// $br_enddate = 'null';
			$br_startdate = $startdate;
			$br_enddate = $enddate;
			if ($criteria == 1)
			{
				//$min_qty = $_POST["txtPHMinimum"];
				$min_qty = 1;
				$min_amt = 'null';				
			}
			else
			{
				$min_qty = 'null';
				$min_amt = $_POST["txtPHMinimum"];
			}
		}
		else
		{
			$criteria = $_POST["cboCriteria"];
			$minimum = $_POST["txtMinimum"];
			$tmpbsdate = strtotime($_POST['txtSetStartDate']);
			$br_startdate = date("m/d/Y", $tmpbsdate);
			$tmpbedate = strtotime($_POST['txtSetEndDate']);
			$br_enddate = date("m/d/Y", $tmpbedate);
			if ($criteria == 1)
			{
				$min_qty = $_POST["txtMinimum"];
				$min_amt = 'null';				
			}
			else
			{
				$min_qty = 'null';
				$min_amt = $_POST["txtMinimum"];
			}				
		}
		
		//get product description
		$rs_prod = $sp->spSelectProductbyID($database, $prodid, 1);
		if($rs_prod->num_rows)
		{
			while($row = $rs_prod->fetch_object())
			{
				$proddesc = $row->Name;
				$pmgCode = $row->pmgCode;
			}
		}
		
		//add to session
		$_SESSION['buyins'][] = array('PReqType'=>$preqttype, 'Range'=>$range, 'ProdID'=>$prodid, 'ProdDesc'=>$proddesc, 'BReqID'=>$breqid, 'Criteria'=>$criteria, 'MinQty'=>$min_qty, 'MinAmt'=>$min_amt, 'StartDate'=>$br_startdate, 'EndDate'=>$br_enddate,'PMGCode'=>$pmgCode);
		$ctr = sizeof($_SESSION['buyins']) + 1;	
	}
	else if (isset($_POST['btnRemove']))
		{
		if(isset($_SESSION["buyins"]))	
		{		
			if(isset($_POST["chkSelect"]))	
			{
				$tmpbuyin = $_SESSION["buyins"];
				$x=sizeof($tmpbuyin);
			    $buyin = $_POST["chkSelect"];
				foreach ($buyin as $key=>$ID) 
				{
					for ($i = 0; $i < $x; $i++)
					{
					 if (isset($tmpbuyin[$i]['ProdID']))
						{		
							if (($_POST['hprodIDID'.$ID] == $tmpbuyin[$i]['ProdID']))
							{									
								unset($tmpbuyin[$i]);
								break;
								
							}
						}
					}
				
				}
				
				unset($_SESSION["buyins"]);
				for ($i = 0; $i < $x; $i++)
				{
					if (isset($tmpbuyin[$i]['ProdID']))
					{	 
	
						$_SESSION['buyins'][] = array('PReqType'=>$tmpbuyin[$i]['PReqType'], 'Range'=>$tmpbuyin[$i]['Range'], 'ProdID'=>$tmpbuyin[$i]['ProdID'], 'ProdDesc'=>$tmpbuyin[$i]['ProdDesc'], 'BReqID'=>$tmpbuyin[$i]['BReqID'], 'Criteria'=>$tmpbuyin[$i]['Criteria'], 'MinQty'=>$tmpbuyin[$i]['MinQty'], 'MinAmt'=>$tmpbuyin[$i]['MinAmt'], 'StartDate'=>$tmpbuyin[$i]['StartDate'], 'EndDate'=>$tmpbuyin[$i]['EndDate'],'PMGCode'=>$tmpbuyin[$i]['PMGCode']);
						
					}	
								
				};
				
				if(isset($_SESSION["buyins"]))	
				{
					$bcnt = $_SESSION['buyins'];
					$ctr = sizeof($_SESSION['buyins']) + 1;	
				}
			}
		}
			
	}
	if (isset($_POST['btnSave']))
		{
		try
		{
			
			$database->beginTransaction();
			//promo header variables			
			$promocode = htmlentities(addslashes($_POST['txtCode']));				
			$promodesc = htmlentities(addslashes($_POST['txtDescription']));
			$tmpsdate = strtotime($_POST['txtStartDate']);
			$startdate = date("Y-m-d", $tmpsdate);
			$tmpedate = strtotime($_POST['txtEndDate']);
			$enddate = date("Y-m-d", $tmpedate);
						
			
			
			if(!isset($_POST['cboPReqtType']))
			{
				$preqttype = $prtype;			
			}
			else
			{
				$preqttype = $_POST['cboPReqtType'];
			}

			if(isset($_SESSION['buyins']))	
			  {
				if (sizeof($_SESSION['buyins']))
				   {
					$buyin_list = $_SESSION["buyins"];
					$rowalt=0;
										//while($row = $rspromobuyin->fetch_object())
					for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ ) 
						{
							$preqttype = $buyin_list[$i]['PReqType'];
						}
						
				   }
			  }
			
			$entprod = $_POST['hEntitlementCnt'];
			$type = $_POST['cboType'];
			if ($type == 2)
			{
				$typeqty = $_POST['txtTypeQty'];
			}
			else
			{
				$typeqty = 0;
			}
			
			//buy-in requirement variables
			$range = $_POST["cboRange"];
			$prodid = $_POST["hProdID_criteria"];
			$breqid = $_POST['rdoBReqt'];
			if ($breqid == 2)
			{
				$criteria = $_POST["cboPHCriteria"];
				$minimum = $_POST["txtPHMinimum"];
				$br_startdate = 'null';			
				$br_enddate = 'null';
				if ($criteria == 1)
				{
					$min_qty = $_POST["txtPHMinimum"];
					$min_amt = 'null';				
				}
				else
				{
					$min_qty = 'null';
					$min_amt = $_POST["txtPHMinimum"];
				}
			}
			else
			{
				$min_qty = 'null';
				$min_amt = 'null';
			}
			
			if(isset($_POST['chkPlusPlan']))
			{
				$isplusplan = $_POST['chkPlusPlan'];				
			}
			else
			{
				$isplusplan = 0;
			}
			
			//check if promo code already exist
			// $rs_code_exist = $sp->spCheckPromoIfExists($database, $promocode);
			// if($rs_code_exist->num_rows)
			// {
				// $errmsg = "Promo code already exists.";
			// }
			// else
			// {
				//insert to promo table
				$rs_promoid = $sp->spInsertPromoHeaderOverlay($database, $promocode, $promodesc, $startdate, $enddate, 3, $breqid, $min_qty, $min_amt, $session->emp_id, 0, $isplusplan);
				if (!$rs_promoid)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				if($rs_promoid->num_rows)
				{
					while($row = $rs_promoid->fetch_object())
					{
						$promoID = $row->ID;
					}
					$rs_promoid->close();
				}
				
				//link promo to branches
				$rs_branch = $sp->spSelectBranch($database, -1, '');
				if($rs_branch->num_rows)
				{
					while($row_branch = $rs_branch->fetch_object())
					{											
						$sp->spInsertPromoBranchLinking($database, $promoID, $row_branch->ID); 
					}
					$rs_branch->close();										
				}
				
				//insert to promoavailment table
				if($rs_gsutype_save->num_rows)
				{
					while($row = $rs_gsutype_save->fetch_object())
					{
						if(isset($_POST["txtMaxAvail{$row->ID}"]))
						{
							if($_POST["txtMaxAvail{$row->ID}"] != "")
							{
								$rs_promoavail = $sp->spInsertPromoAvailment($database, $promoID, $row->ID, $_POST["txtMaxAvail{$row->ID}"]);
								if (!$rs_promoavail)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}							
							}						
						}					
					}
					$rs_gsutype_save->close();
				}
				
				//insert to promobuyin - parent
				// RCB 20101130: set productlevelid = 3. doesn't have to be 3 but 
				// promo.php needs the parent promobuyin productlevelid
				// column to have a value
				$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', $preqttype, $startdate, $enddate, 0);
				if (!$rs_promobuyin_parent)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				if($rs_promobuyin_parent->num_rows)
				{
					while($row = $rs_promobuyin_parent->fetch_object())
					{
						$buyinparentID = $row->ID;
					}
					$rs_promobuyin_parent->close();
				}
				
				//insert to promobuyin - child
				if(isset($_SESSION['buyins']))	
				{
					if (sizeof($_SESSION['buyins']))
					{
						$buyin_list = $_SESSION["buyins"];
						for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ )
						{
							
							if ($buyin_list[$i]['Criteria'] == 1)
							{
								$rs_promobuyin = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_list[$i]['Criteria'], $buyin_list[$i]['MinQty'], 'null', 'null', 'null', $buyin_list[$i]['Range'], $buyin_list[$i]['ProdID'], $buyin_list[$i]['PReqType'], $buyin_list[$i]['StartDate'], $buyin_list[$i]['EndDate'], 1);
								if (!$rs_promobuyin)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}
							}
							else
							{
								$rs_promobuyin = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_list[$i]['Criteria'], 'null', $buyin_list[$i]['MinAmt'], 'null', 'null', $buyin_list[$i]['Range'], $buyin_list[$i]['ProdID'], $buyin_list[$i]['PReqType'], $buyin_list[$i]['StartDate'], $buyin_list[$i]['EndDate'], 1);
								if (!$rs_promobuyin)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}
							}					
						}			
					}			
				}
				
					if($type != 2)
					{
					 	$typeqty = $entprod;
					}
					else
					{
						$typeqty = $_POST['txtTypeQty'];
					}
				
				
				//insert to entitlement/entitlementdetails
	//			if ($entprod != 1 && $_POST["txtEProdCode1"] != "")
	//			{
					//insert to promoentitlement
					$rs_promoid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $type, $typeqty);
					if (!$rs_promoid)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					if($rs_promoid->num_rows)
					{
						while($row = $rs_promoid->fetch_object())
						{
							$entitlementID = $row->ID;
						}
					}
					
					//insert to promoentitlementdetails
				$itemcountent = 0;
			    $j =1;
	
				while ($itemcountent < $entprod - 1)
				{
					do {
					    $prodid = $_POST["hEProdID{$j}"];
						$produp = $_POST["hEUnitPrice{$j}"];
						$prodcode = $_POST["txtEProdCode{$j}"];
						$criteria = $_POST["cboECriteria{$j}"];
						$minimum = $_POST["txtEQty{$j}"];
						//$pmg = $_POST["hEpmgid{$i}"];
						$pmg = $_POST["cboEPMG{$j}"];
					     $j++;	
					    }while (empty($prodcode));
					    
					    if ($prodcode != "")
						{
							if ($criteria == 1)
							{
								$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, $minimum, 0, $produp, $pmg);
								if (!$rs_promoent_details)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}					
							}
							else
							{
								$savings = $produp - $minimum;
								$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, 1, $minimum, $savings, $pmg);
								if (!$rs_promoent_details)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}
							}
						}			
					$itemcountent++;
				}
				
			
	//			}
				
				$database->commitTransaction();
				redirect_to("index.php?pageid=178&inc=1");				
			//}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";			
		}
	}

	
	
?>

