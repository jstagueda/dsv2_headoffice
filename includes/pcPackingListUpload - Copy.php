<?php
	require_once("../initialize.php");
	global $database;

	ini_set('display_errors', 0);
	ini_set('max_execution_time', 300);
	
	if(isset($_POST['btnUpload']))
	{	
		$path_Info = $_FILES['file']['name'];
		$ext = pathinfo($path_Info);
		$file_Size = $_FILES['file']['size'];	 
		$uType = addslashes($_POST['cboUploadType']);
		$uploadErr = "";
		
		if($path_Info == "")
	 	{
	 		$errormessage = "Please select a file to upload.";
			redirect_to("../index.php?pageid=56&errmsg=$errormessage"); 
	 	}

	 	//if (strtolower($ext['extension']) != 'xml' )
	 	//{	
		//	$errormessage = "Invalid filename extension.";
	    //	redirect_to("../index.php?pageid=56&errmsg=$errormessage"); 		 	
	 	//}
	
		$data = array();
		try
		{
			$database->beginTransaction();
			$rs_DeleteTmpUpload = $sp->spDeleteTmpUpload($database);
			$database->commitTransaction();
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=56&errmsg=$errmsg");
		}
		
		function csvstring_to_array(&$string, $CSV_SEPARATOR = ';', $CSV_ENCLOSURE = '"', $CSV_LINEBREAK = "\n")
		{
			$o = array();
			$o[0] = "";
			$cnt = strlen($string);
			$esc = false;
			$num = 0;
			$i = 0;
			while ($i < $cnt) 
			{
				$s = $string[$i];
				//echo $s."<br>";
				
				if ($s == $CSV_SEPARATOR) 
				{
					if ($esc) 
					{
						$o[$num] .= $s;
					} 
					else 
					{
						$num++;
						$esc = false;
					}
				} 
				elseif ($s == $CSV_ENCLOSURE) 
				{
					if ($esc) 
					{
						$esc = false;
					} 
					else 
					{
						$esc = true;
					}
				} 
				else 
				{
					$o[$num] .= $s;
				}

				$i++;
			}
			return $o;
		} 
			
		function add_rhodetails($tpi_tmprhoid, $plistrefno, $lcounter, $pcode, $qty, $desc)
	 	{
		    global $data;							  
			$data []= array
			(
				'tpi_tmprhoID' => $tpi_tmprhoid,
				'PickListRefNo' => $plistrefno,
				'LineCounter' => $lcounter,
				'ProductCode' => $pcode,
				'Quantity' => $qty,
				'Description' => $desc
				
			);
		}
		
		function add_stadetails($tpi_tmpstaid, $stano, $lcounter, $pcode, $desc, $qty)
	 	{
			global $data;				  
			$data []= array
			(
				'tpi_tmpstaID' => $tpi_tmpstaid,
				'STANo' => $stano,
				'LineCounter' => $lcounter,
				'ProductCode' => $pcode,				
				'Description' => $desc,
				'Quantity' => $qty
				
			);
		}
		
		if (is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$fileData = trim(file_get_contents($_FILES['file']['tmp_name']));
			//echo $fileData;
			
			$isrho = true;

			$rows = explode("\n", $fileData);
			//$dom = DOMDocument::load( $_FILES['file']['tmp_name'] ); 
		 	//$rows = $dom->getElementsByTagName( 'Row' );
		 	$first_row = true;
			
			//for header
			$h1 = "";
			$h2 = "";
			$h3 = "";
			$h4 = "";
			$h5 = "";
			$h6 = "";
			$rhoid = 0;
			$staid = 0;
			
			//for rho
			$drno = "";
			$rbranch = "";
			$drdate = "";
			$shipmentdate = "";
			$totlinecounter = "";
			$shipmentvia = "";
			
			//for sta
			$txntype = "";
			$stano = "";
			$branchcode = "";
			$totallinecounter = "";
			
			//for product master
			$prodCode = "";
			$prodName = "";
			$uom = "";
			$prodLine = "";
			$csp = "";
			$pmgCode = "";
			$pmStatus = "";
			$itemType = "";
			$brand = "";
			$form = "";			
			$lastReceiptDate = "";
			
			//for single line promo
			$promocode = "";
			$promodesc = "";
			$promosdate = "";
			$promoedate = "";
			$buyintype = "";
			$buyinminqty = "";
			$buyinminamt = "";
			$buyinplevel = "";
			$buyinpcode = "";
			$buyinpdesc = "";
			$buyinreqtype = "";
			$buyinleveltype = "";
			$enttype = "";
			$entqty = "";
			$entprodcode = "";
			$entproddesc = "";
			$entdetqty = "";
			$entdetprice = "";
			$entdetsavings = "";
			$entdetpmg = "";
			
			//for multiline promo
			$mb_promocode = "";
			$mb_promodesc = "";
			$mb_promosdate = "";
			$mb_promoedate = "";
			$mb_buyintype = "";
			$mb_buyinminqty = "";
			$mb_buyinminamt = "";
			$mb_buyinplevel = "";
			$mb_buyinpcode = "";
			$mb_buyinpdesc = "";
			$mb_buyinreqtype = "";
			$mb_buyinleveltype = "";
			$me_promocode = "";
			$me_promodesc = "";
			$me_promosdate = "";
			$me_promoedate = "";
			$me_enttype = "";
			$me_entqty = "";
			$me_entprodcode = "";
			$me_entproddesc = "";
			$me_entdetqty = "";
			$me_entdetprice = "";
			$me_entdetsavings = "";
			$me_entdetpmg = "";
			
			//for overlay promo
			$ob_promocode = "";
			$ob_promodesc = "";
			$ob_promosdate = "";
			$ob_promoedate = "";
			$ob_nongsu = "";
			$ob_dirgsu = "";
			$ob_idirgsu = "";
			$ob_buyinreqtype = "";
			$ob_buyintype = "";
			$ob_buyinplevel = "";
			$ob_buyinpcode = "";
			$ob_buyinpdesc = "";
			$ob_buyincriteria = "";
			$ob_buyinminval = "";
			$ob_buyinsdate = "";
			$ob_buyinedate = "";
			$ob_buyinleveltype = "";
			$ob_isincentive = "";
			$oe_promocode = "";
			$oe_promodesc = "";
			$oe_promosdate = "";
			$oe_promoedate = "";
			$oe_enttype = "";
			$oe_entqty = "";
			$oe_entprodcode = "";
			$oe_entproddesc = "";
			$oe_entdetqty = "";
			$oe_entdetprice = "";
			$oe_entdetsavings = "";
			$oe_entdetpmg = "";

			try 
			{
				//$database->beginTransaction();
				foreach ($rows as $row)
			 	{
					if ($uType != 3 && $uType != 4 && $uType != 5 && $uType != 6 && $uType != 7 && $uType != 8)
					{
						$rdata = csvstring_to_array(trim($row), ' ', '"', '\r\n');
						//echo $row."<br>";
					}
					
					if ($uType == 4 || $uType == 5 || $uType == 6 || $uType == 7 || $uType == 8)
					{
						$rdata = csvstring_to_array(trim($row), ',', '"', '\r\n');
					}
					
				  	if($first_row && ($uType != 3 && $uType != 4 && $uType != 5 && $uType != 6 && $uType != 7 && $uType != 8))//header
				   	{
	                    if($uType == 1) //if RHO file
						{ 					    					 	
							$h1 = trim($rdata[0]);
							$h2 = strtoupper(trim($rdata[1]));
							$h3 = trim(date('Y-m-d',strtotime($rdata[2])));
							$h4 = trim(date('Y-m-d',strtotime($rdata[3])));
							$h5 = trim($rdata[4]);
							$h6 = trim($rdata[5]);
							//echo $h1.", ".$h2.", ".$h3.", ".$h4.", ".$h5.", ".$h6;
							
							$rsBranchCheck = $sp->spCheckBranchCode($database, $h2);
						    if (!$rsBranchCheck->num_rows)
						    {
						    	$uploadErr = "Invalid Branch Code '$h2'";
						    	throw new Exception("$uploadErr");
						    }
						    
							$rsRHOCheck = $sp->spCheckDocInventoryinout($database, $h1);
						    if ($rsRHOCheck->num_rows)
						    {
						    	$uploadErr = "RHO Document Number '$h1' already exists";
						    	throw new Exception("$uploadErr");
						    }
	
						    $isrho = true; 
							$rs_insertTmpRHO = $sp->spInsertTmpRHO($database,$h1,$h2,$h3,$h4,$h5,$h6,0);
							//echo 'Successfully inserted to tmpRho ,';
							if($rs_insertTmpRHO->num_rows)
					        {
						      	while($row = $rs_insertTmpRHO->fetch_object())
			 			      	{										 						 			
						 	       $rhoid = $row->ID;							 	
						      	}
					        }
						}
						else //if STA file
						{		
							$h1 = trim($rdata[0]);
							$h2 = trim($rdata[1]);
							$h3 = strtoupper(trim($rdata[2]));
							$h4 = trim(date('Y-m-d',strtotime($rdata[3])));
							$h5 = strtoupper(trim($rdata[4]));
							$h6 = trim($rdata[5]);
						    $isrho = false;
						    //echo $h1.", ".$h2.", ".$h3.", ".$h4.", ".$h5.", ".$h6;
						    
						    $rsBranchCheck = $sp->spCheckBranchCode($database, $h3);
						    if (!$rsBranchCheck->num_rows)
						    {
						    	$uploadErr = "Invalid Branch Code '$h5'";
						    	throw new Exception("$uploadErr");
						    }
						    
							$rsBranchCheck = $sp->spCheckBranchCode($database, $h5);
						    if (!$rsBranchCheck->num_rows)
						    {
						    	$uploadErr = "Invalid Branch Code '$h5'";
						    	throw new Exception("$uploadErr");
						    }
						    
							$rsSTACheck = $sp->spCheckDocInventoryinout($database, $h2);
						    if ($rsSTACheck->num_rows)
						    {
						    	$uploadErr = "STA Document Number '$h2' already exists";
						    	throw new Exception("$uploadErr");
						    }
							
							if ($h1 == "ITR" || $h1 == "IRV" || $h1 == "RTR")
							{
								$rs_insertTmpSTA = $sp->spInsertTmpSTA($database,$h1,$h2,$h3,$h6,$h4,$h5);
							}
							else {
								throw new Exception("Invalid STA File");
							}
							//echo 'Successfully inserted to tmpSTA ,';
							if($rs_insertTmpSTA->num_rows)
					        {
						       while($row = $rs_insertTmpSTA->fetch_object())
			 			       {										 						 			
						 	       $staid = $row->ID;						   
						       }
					        }   
						}
				   	}	 				
	               	else //details
	               	{	
						if ($uType == 3)// Product Master
						{
							$prodCode = trim(substr($row, 0, 19));
							$prodName = trim(str_replace("'", "\'",substr($row, 19, 56)));
							$uom = trim(substr($row, 75, 3));
							$prodLine = trim(substr($row, 78, 7));
							$csp = trim(str_replace(",", "", substr($row, 85, 13)));
							$pmgCode = trim(substr($row, 99, 9));
							$pmStatus = trim(substr($row, 108, 9));
							$itemType = trim(substr($row, 117, 9));
							$brand = trim(substr($row, 126, 11));
							$form = trim(substr($row, 137, 9));
							$lastReceiptDate = trim(substr($row, 146, 8));
							$lastReceiptDate = trim(date('Y-m-d',strtotime($lastReceiptDate)));
							
							$Uploader_rows = $sp->spInsertTmpProdMaster($database, $prodCode, $prodName, $uom, $prodLine, $csp, $pmgCode, $pmStatus, $itemType, $brand, $form, $lastReceiptDate); 																		
						}
						else if ($uType == 4) //single line promo
						{
							$promocode = str_replace("'", "\'",$rdata[0]);
							$promodesc = str_replace("'", "\'",$rdata[1]);
							$promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
							$promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
							$buyintype = str_replace("'", "\'",$rdata[4]);
							$buyinminqty = str_replace("'", "\'",$rdata[5]);
							$buyinminamt = str_replace("'", "\'",$rdata[6]);
							$buyinplevel = str_replace("'", "\'",$rdata[7]);
							$buyinpcode = str_replace("'", "\'",$rdata[8]);
							$buyinpdesc = str_replace("'", "\'",$rdata[9]);
							$buyinreqtype = str_replace("'", "\'",$rdata[10]);
							$buyinleveltype = str_replace("'", "\'",$rdata[11]);
							$enttype = str_replace("'", "\'",$rdata[12]);
							$entqty = str_replace("'", "\'",$rdata[13]);
							$entprodcode = str_replace("'", "\'",$rdata[14]);
							$entproddesc = str_replace("'", "\'",$rdata[15]);
							$entdetqty = str_replace("'", "\'",$rdata[16]);
							$entdetprice = str_replace("'", "\'",$rdata[17]);
							$entdetsavings = str_replace("'", "\'",$rdata[18]);
							$entdetpmg = str_replace("'", "\'",$rdata[19]);

							$Uploader_rows = $sp->spInsertTmpSingleLinePromo($database, $promocode, $promodesc, $promosdate, $promoedate, $buyintype, $buyinminqty, $buyinminamt, $buyinplevel, $buyinpcode, $buyinpdesc, $buyinreqtype, $buyinleveltype, $enttype, $entqty, $entprodcode, $entproddesc, $entdetqty, $entdetprice, $entdetsavings, $entdetpmg);							 																		
						}
						else if ($uType == 5) //multiline promo - buyin
						{
							$mb_promocode = str_replace("'", "\'",$rdata[0]);
							$mb_promodesc = str_replace("'", "\'",$rdata[1]);
							$mb_promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
							$mb_promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
							$mb_buyintype = str_replace("'", "\'",$rdata[4]);
							$mb_buyinminqty = str_replace("'", "\'",$rdata[5]);
							$mb_buyinminamt = str_replace("'", "\'",$rdata[6]);
							$mb_buyinplevel = str_replace("'", "\'",$rdata[7]);
							$mb_buyinpcode = str_replace("'", "\'",$rdata[8]);
							$mb_buyinpdesc = str_replace("'", "\'",$rdata[9]);
							$mb_buyinreqtype = str_replace("'", "\'",$rdata[10]);
							$mb_buyinleveltype = str_replace("'", "\'",$rdata[11]);
							
							$Uploader_rows = $sp->spInsertTmpMultiLineBuyinPromo($database, $mb_promocode, $mb_promodesc, $mb_promosdate, $mb_promoedate, $mb_buyintype, $mb_buyinminqty, $mb_buyinminamt, $mb_buyinplevel, $mb_buyinpcode, $mb_buyinpdesc, $mb_buyinreqtype, $mb_buyinleveltype);
						}
						else if ($uType == 6) //multiline promo - entitlement
						{
							$mb_promocode = str_replace("'", "\'",$rdata[0]);
							$mb_promodesc = str_replace("'", "\'",$rdata[1]);
							$mb_promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
							$mb_promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
							$mb_enttype = str_replace("'", "\'",$rdata[4]);
							$mb_entqty = str_replace("'", "\'",$rdata[5]);
							$mb_entprodcode = str_replace("'", "\'",$rdata[6]);
							$mb_entproddesc = str_replace("'", "\'",$rdata[7]);
							$mb_entdetqty = str_replace("'", "\'",$rdata[8]);
							$mb_entdetprice = str_replace("'", "\'",$rdata[9]);
							$mb_entdetsavings = str_replace("'", "\'",$rdata[10]);
							$mb_entdetpmg = str_replace("'", "\'",$rdata[11]);
							
							$Uploader_rows = $sp->spInsertTmpMultiLineEntitlementPromo($database, $mb_promocode, $mb_promodesc, $mb_promosdate, $mb_promoedate, $mb_enttype, $mb_entqty, $mb_entprodcode, $mb_entproddesc, $mb_entdetqty, $mb_entdetprice, $mb_entdetsavings, $mb_entdetpmg);
						}
						else if ($uType == 7) //overlay promo - buyin
						{
							$ob_promocode = str_replace("'", "\'",$rdata[0]);
							$ob_promodesc = str_replace("'", "\'",$rdata[1]);
							$ob_promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
							$ob_promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
							$ob_nongsu = str_replace("'", "\'",$rdata[4]);
							$ob_dirgsu = str_replace("'", "\'",$rdata[5]);
							$ob_idirgsu = str_replace("'", "\'",$rdata[6]);
							$ob_buyinreqtype = str_replace("'", "\'",$rdata[7]);
							$ob_buyintype = str_replace("'", "\'",$rdata[8]);
							$ob_buyinplevel = str_replace("'", "\'",$rdata[9]);
							$ob_buyinpcode = str_replace("'", "\'",$rdata[10]);
							$ob_buyinpdesc = str_replace("'", "\'",$rdata[11]);
							$ob_buyincriteria = str_replace("'", "\'",$rdata[12]);
							$ob_buyinminval = str_replace("'", "\'",$rdata[13]);
							$ob_buyinsdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[14])));
							$ob_buyinedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[15])));
							$ob_buyinleveltype = str_replace("'", "\'",$rdata[16]);
							$ob_isincentive = str_replace("'", "\'",$rdata[17]);
							
							$Uploader_rows = $sp->spInsertTmpOverlayBuyinPromo($database, $ob_promocode, $ob_promodesc, $ob_promosdate, $ob_promoedate, $ob_nongsu, $ob_dirgsu, $ob_idirgsu, $ob_buyinreqtype, $ob_buyintype, $ob_buyinplevel, $ob_buyinpcode, $ob_buyinpdesc, $ob_buyincriteria, $ob_buyinminval, $ob_buyinsdate, $ob_buyinedate, $ob_buyinleveltype, $ob_isincentive);
						}
						else if ($uType == 8) //overlay promo - entitlement
						{
							$oe_promocode = str_replace("'", "\'",$rdata[0]);
							$oe_promodesc = str_replace("'", "\'",$rdata[1]);
							$oe_promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
							$oe_promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
							$oe_enttype = str_replace("'", "\'",$rdata[4]);
							$oe_entqty = str_replace("'", "\'",$rdata[5]);
							$oe_entprodcode = str_replace("'", "\'",$rdata[6]);
							$oe_entproddesc = str_replace("'", "\'",$rdata[7]);
							$oe_entdetqty = str_replace("'", "\'",$rdata[8]);
							$oe_entdetprice = str_replace("'", "\'",$rdata[9]);
							$oe_entdetsavings = str_replace("'", "\'",$rdata[10]);
							$oe_entdetpmg = str_replace("'", "\'",$rdata[11]);
							
							$Uploader_rows = $sp->spInsertTmpOverlayEntitlementPromo($database, $oe_promocode, $oe_promodesc, $oe_promosdate, $oe_promoedate, $oe_enttype, $oe_entqty, $oe_entprodcode, $oe_entproddesc, $oe_entdetqty, $oe_entdetprice, $oe_entdetsavings, $oe_entdetpmg);
						}
				    	else if ($isrho) //is RHO
					   	{	   													 
						 	$tpi_tmprhoid = 0;
							$plistrefno = $rdata[0];
							$lcounter = $rdata[1];
							$prodcode = $rdata[2];
							$qty = $rdata[3];
							$desc = $rdata[4];
	
					  		add_rhodetails($rhoid, $plistrefno, $lcounter, $prodcode, $qty, $desc);
					  	}
					  	else //is STA
					  	{				  					
							$tpi_tmpstaid = 0;
							$stano = $rdata[0];
							$lcounter = $rdata[1];
							$prodcode = $rdata[2];					 
							$desc = $rdata[3];
							$qty = $rdata[4];	  
	
							//echo "STANo = ".$stano.", LineCounter = ".$lcounter.", prodCode = ".$prodcode.", Desc = ".$desc.", Qty = ".$qty."<br>";
					  		add_stadetails($staid, $stano, $lcounter, $prodcode, $desc, $qty);				     
					  	}
				   	}
				 	$first_row = false;
			 	}
			 	
				$ctr2 = 0;
				$chckEmpty = 0;
				$val = 0;
			
				if ($uType == 3)
				{
					$sp->spUpdateTmpProdMaster($database);
					$sp->spInsertFromTmpProdMaster($database);
					//echo "Product master uploaded";
					$database->commitTransaction();
					$message = "Successfully Uploaded Interface File";
				}
				else if ($uType == 4)
				{
					//retrieve in temp table (single line)
					$msg_log = "";
					$ctr = 0;
					$singleline = $sp->spSelectTmp_PromoSingleLine($database);
					 
					if ($singleline->num_rows)
					{
						while ($row = $singleline->fetch_object())
						{
							$ctr += 1;
							$promo_code = str_replace("'", "\'",$row->PromoCode);	
							$promo_desc = str_replace("'", "\'",$row->PromoDescription);
							$promo_sdate = $row->PromoStartDate;
							$promo_edate = $row->PromoEndDate;
							$buyin_type = $row->BuyinTypeID;
							$buyin_minqty = $row->BuyinMinQty;
							$buyin_minamt = $row->BuyinMinAmt;
							$buyin_plevel = $row->BuyinProductLevelID;
							$buyin_pcode = str_replace("'", "\'",$row->BuyinProductCode);
							$buyin_pdesc = str_replace("'", "\'",$row->BuyinProductDescription);
							$buyin_reqtype = $row->BuyinPurchaseReqTypeID;
							$buyin_leveltype = $row->BuyinLevelTypeID;
							$ent_type = $row->EntitlementTypeID;
							$ent_qty = $row->EntitlementQty;
							$ent_prodcode = str_replace("'", "\'",$row->EntitlementProductCode);
							$ent_proddesc = str_replace("'", "\'",$row->EntitlementProductDescription);
							$entdet_qty = $row->EntitlementDetQty;
							$entdet_price = $row->EntitlementDetEffPrice;
							$entdet_savings = $row->EntitlementDetSavings;
							$entdet_pmg = $row->EntitlementDetPMG;
							
							//get buyin product id
							$buyin_prod = 'buyin_prod'.$ctr;
							$buyin_prod = $sp->spSelectProductToUpload($database, $buyin_pcode);

							if ($buyin_prod->num_rows)
							{
								while($row = $buyin_prod->fetch_object())
								{
									$buyin_prodid = $row->ProductID; 									
								}
								$buyin_prod->close();

								//get entitlement product id
								$entitlement_prod = 'entitlement_prod'.$ctr;
								$entitlement_prod = $sp->spSelectProductToUpload($database, $ent_prodcode);
								if ($entitlement_prod->num_rows)
								{
									while($row = $entitlement_prod->fetch_object())
									{
										$entitlement_prodid = $row->ProductID; 									
									}
									$entitlement_prod->close();
									
									//check if promo code exists						
									$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
									if (!$promo_exist->num_rows)
									{
										//insert promo header
										$rs_promoid = $sp->spInsertPromoHeader($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 1, $session->user_id);
										if($rs_promoid->num_rows)
										{
											while($row = $rs_promoid->fetch_object())
											{
												$promoID = $row->ID;
											}
											$rs_promoid->close();
										}								
									}
									else
									{
										//get promo id
										$rs_promoid = 'rs_promoid'.$ctr;
										$rs_promoid = $sp->spSelectPromoByCode2($database, $promo_code);
										if($rs_promoid->num_rows)
										{
											while($row = $rs_promoid->fetch_object())
											{
												$promoID = $row->ID;
											}
											$rs_promoid->close();
										}
										
										//update promo header
										$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate);
									}
									
									//check if promo buyin exists
									$promo_buyin_exist = $sp->spCheckIfExistPromoBuyIn($database, $promoID, $buyin_type, $buyin_prodid);
									if (!$promo_buyin_exist->num_rows)
									{
										if ($buyin_type == 1)
										{
											$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, 'null', $buyin_type, $buyin_minqty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', $promo_sdate, $promo_edate, 1);
										}
										else
										{
											$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, 'null', $buyin_type, 'null', $buyin_minamt, 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', $promo_sdate, $promo_edate, 1);								
										}
										
										//insert buyin
										if($rs_promobuyin_child->num_rows)
										{
											while($row = $rs_promobuyin_child->fetch_object())
											{
												$buyinparentID = $row->ID;
											}
											$rs_promobuyin_child->close();
										}										
									}
									else
									{
										if($promo_buyin_exist->num_rows)
										{
											while($row = $promo_buyin_exist->fetch_object())
											{
												$buyinparentID = $row->ID;
											}
											$promo_buyin_exist->close();
										}
										
										//update promo buyin
										if ($buyin_type == 1)
										{
											$sp->spUpdatePromoBuyInByID($database, $buyinparentID, $promoID, $buyin_type, $buyin_minqty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', $promo_sdate, $promo_edate, 1);
										}
										else
										{
											$sp->spUpdatePromoBuyInByID($database, $buyinparentID, $promoID, $buyin_type, 'null', $buyin_minamt, 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', $promo_sdate, $promo_edate, 1);
										}
									}
									
									//check if promo entitlement exists
									$promo_entitlement_exist = $sp->spCheckIfExistPromoEntitlement($database, $buyinparentID, $ent_type);
									if (!$promo_entitlement_exist->num_rows)
									{
										//insert entitlement
										$rs_promoentid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $ent_type, $ent_qty);
										if($rs_promoentid->num_rows)
										{
											while($row = $rs_promoentid->fetch_object())
											{
												$entitlementID = $row->ID;
											}
											$rs_promoentid->close();
										}										
									}
									else
									{
										if($promo_entitlement_exist->num_rows)
										{
											while($row = $promo_entitlement_exist->fetch_object())
											{
												$entitlementID = $row->ID;
											}
											$promo_entitlement_exist->close();
										}
										
										//update promo entitlement
										$sp->spUpdatePromoEntitlementByID($database, $entitlementID, $buyinparentID, $ent_type, $ent_qty);											
									}
									
									//check if promo entitlement details exists
									$promo_entitlementdet_exist = $sp->spCheckIfExistPromoEntitlementDetails($database, $entitlementID, $entitlement_prodid);
									if (!$promo_entitlementdet_exist->num_rows)
									{
										//insert entitlementdetails
										if($ent_type != 1)
										{
											$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $entitlement_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);									
										}
										else
										{
											$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $entitlement_prodid, $entdet_qty, 0, $entdet_savings, $entdet_pmg);
										} 										
									}
									else
									{
										if($promo_entitlementdet_exist->num_rows)
										{
											while($row = $promo_entitlementdet_exist->fetch_object())
											{
												$entitlementdetID = $row->ID;
											}
											$promo_entitlementdet_exist->close();
										}
										
										//update promo entitlement details
										if($ent_type != 1)
										{
											$sp->spUpdatePromoEntitlementDetailsByID($database, $entitlementdetID, $entitlementID, $entitlement_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);									
										}
										else
										{
											$sp->spUpdatePromoEntitlementDetailsByID($database, $entitlementdetID, $entitlementID, $entitlement_prodid, $entdet_qty, 0, $entdet_savings, $entdet_pmg);
										}											
									}
								}
								else
								{
									//product does not exist
									$msg_log .= "Entitlement Product Code - ".$ent_prodcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
								}								
							}
							else
							{
								//product does not exist
								$msg_log .= "Buyin Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
							}
						}
						$singleline->close();						
					}
					
					$database->commitTransaction();
					$message = "Successfully Uploaded Single Line Promos";
				}
				else if ($uType == 5) //multiline - buyin
				{
					//retrieve in temp table
					$buyinparentID_new = 0;
					$new_promo = 0;
					$msg_log = "";
					$ctr = 0;
					$multiline = $sp->spSelectTmp_PromoMultiline_Buyin($database);
					
					if ($multiline->num_rows)
					{
						while ($row = $multiline->fetch_object())
						{
							$ctr += 1;
							$promo_code = str_replace("'", "\'",$row->PromoCode);	
							$promo_desc = str_replace("'", "\'",$row->PromoDescription);
							$promo_sdate = $row->PromoStartDate;
							$promo_edate = $row->PromoEndDate;
							$buyin_type = $row->BuyinTypeID;
							$buyin_minqty = $row->BuyinMinQty;
							$buyin_minamt = $row->BuyinMinAmt;
							$buyin_plevel = $row->BuyinProductLevelID;
							$buyin_pcode = str_replace("'", "\'",$row->BuyinProductCode);
							$buyin_pdesc = str_replace("'", "\'",$row->BuyinProductDescription);
							$buyin_reqtype = $row->BuyinPurchaseReqTypeID;
							$buyin_leveltype = $row->BuyinLevelTypeID;
							
							//get buyin product id
							$buyin_prod = 'buyin_prod'.$ctr;
							$buyin_prod = $sp->spSelectProductToUpload($database, $buyin_pcode);
							
							if ($buyin_prod->num_rows)
							{
								while($row = $buyin_prod->fetch_object())
								{
									$buyin_prodid = $row->ProductID; 									
								}
								$buyin_prod->close();
								
								//check if promo code exists						
								$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
								if (!$promo_exist->num_rows)
								{
									//insert promo header
									$rs_promoid = $sp->spInsertPromoHeader($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 2, $session->user_id);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
									}
									
									//insert to promobuyin - parent
									$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 1, 'null', 'null', 'null', 'null', 3, 'null', $buyin_reqtype, $promo_sdate, $promo_edate, 0);
									if($rs_promobuyin_parent->num_rows)
									{
										while($row = $rs_promobuyin_parent->fetch_object())
										{
											$buyinparentID = $row->ID;
										}
									}
								}
								else
								{
									//get promo id
									$rs_promoid = 'rs_promoid'.$ctr;
									$rs_promoid = $sp->spSelectPromoByCode($database, $promo_code);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
									}
									
									//update promo header
									$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate);
									
									//retrieve promobuyin - parent
									$rs_promobuyin_parent = $sp->spSelectParentPromoBuyIn($database, $promoID);
									if($rs_promobuyin_parent->num_rows)
									{
										while($row = $rs_promobuyin_parent->fetch_object())
										{
											$buyinparentID = $row->ID;
										}
									}
								}
								
								//check if promo buyin exists
								$promo_buyin_exist = $sp->spCheckIfExistPromoBuyIn($database, $promoID, $buyin_type, $buyin_prodid);
								if (!$promo_buyin_exist->num_rows)
								{								
									//insert to promobuyin - child
									if ($buyin_type == 1)
									{
										$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_type, $buyin_minqty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', $promo_sdate, $promo_edate, 1);
									}
									else
									{
										$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_type, 'null', $buyin_minamt, 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', $promo_sdate, $promo_edate, 1);								
									}
								}
								else
								{
									if($promo_buyin_exist->num_rows)
									{
										while($row = $promo_buyin_exist->fetch_object())
										{
											$buyinchildID = $row->ID;
										}
										$promo_buyin_exist->close();
									}
									
									//update promobuyin - child
									if ($buyin_type == 1)
									{
										$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $promoID, $buyin_type, $buyin_minqty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', $promo_sdate, $promo_edate, 1);
									}
									else
									{
										$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $promoID, $buyin_type, 'null', $buyin_minamt, 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', $promo_sdate, $promo_edate, 1);
									}
								}
							}
							else
							{
								//product does not exist
								$msg_log .= "Buyin Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
							}							
						}
						$multiline->close();
					}
					
					$database->commitTransaction();
					$message = "Successfully Uploaded Multiline Buyin Promos";
				}
				else if ($uType == 6) //multiline - entitlement
				{
					//retrieve in temp table
					$entparentID_new = 0;
					$msg_log = "";
					$ctr = 0;
					$multiline = $sp->spSelectTmp_PromoMultiline_Entitlement($database);
					
					if ($multiline->num_rows)
					{
						while ($row = $multiline->fetch_object())
						{
							$ctr += 1;
							$promo_code = str_replace("'", "\'",$row->PromoCode);	
							$promo_desc = str_replace("'", "\'",$row->PromoDescription);
							$promo_sdate = $row->PromoStartDate;
							$promo_edate = $row->PromoEndDate;
							$ent_type = $row->EntitlementTypeID;
							$ent_qty = $row->EntitlementQty;
							$ent_prodcode = str_replace("'", "\'",$row->EntitlementProductCode);
							$ent_proddesc = str_replace("'", "\'",$row->EntitlementProductDescription);
							$entdet_qty = $row->EntitlementDetQty;
							$entdet_price = $row->EntitlementDetEffPrice;
							$entdet_savings = $row->EntitlementDetSavings;
							$entdet_pmg = $row->EntitlementDetPMG;
							
							//get entitlement product id
							$ent_prod = 'ent_prod'.$ctr;
							$ent_prod = $sp->spSelectProductToUpload($database, $ent_prodcode);
							
							if ($ent_prod->num_rows)
							{
								while($row = $ent_prod->fetch_object())
								{
									$ent_prodid = $row->ProductID; 									
								}
								$ent_prod->close();
								
								//check if promo code exists						
								$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
								if (!$promo_exist->num_rows)
								{
									$msg_log .= "Promo Code ".$promo_code. " does not exist. Upload Promo Buyin first. <br>";						
								}
								else
								{
									//get promo id
									$rs_promoid = 'rs_promoid'.$ctr;
									$rs_promoid = $sp->spSelectPromoByCode($database, $promo_code);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
									}
									
									//get parent promo buyin
									$buyin_parent = $sp->spSelectParentPromoBuyIn($database, $promoID);
									if($buyin_parent->num_rows)
									{
										while($row = $buyin_parent->fetch_object())
										{
											$buyinparentID = $row->ID;
										}
										$buyin_parent->close();
									}
									
									//update promo header
									$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate);
									
									//check if promo entitlement exists
									$promo_entitlement_exist = $sp->spCheckIfExistPromoEntitlement($database, $buyinparentID, $ent_type);
									if (!$promo_entitlement_exist->num_rows)
									{
										//insert entitlement
										$rs_promoentid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $ent_type, $ent_qty);
										if($rs_promoentid->num_rows)
										{
											while($row = $rs_promoentid->fetch_object())
											{
												$parentEntitlementID = $row->ID;
											}
											$rs_promoentid->close();
										}
									}
									else
									{
										if($promo_entitlement_exist->num_rows)
										{
											while($row = $promo_entitlement_exist->fetch_object())
											{
												$parentEntitlementID = $row->ID;
											}
											$promo_entitlement_exist->close();
										}
										
										//update promo entitlement
										$sp->spUpdatePromoEntitlementByID($database, $parentEntitlementID, $buyinparentID, $ent_type, $ent_qty);											
									}
									
									//check if promo entitlement details exists
									$promo_entitlementdet_exist = $sp->spCheckIfExistPromoEntitlementDetails($database, $parentEntitlementID, $ent_prodid);
									if (!$promo_entitlementdet_exist->num_rows)
									{
										//insert entitlementdetails
										if ($ent_type == 1)
										{
											$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);					
										}
										else
										{
											$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);
										}									
									}
									else
									{
										if($promo_entitlementdet_exist->num_rows)
										{
											while($row = $promo_entitlementdet_exist->fetch_object())
											{
												$entitlementdetID = $row->ID;
											}
											$promo_entitlementdet_exist->close();
										}
										
										//update promo entitlement details
										if($ent_type != 1)
										{
											$sp->spUpdatePromoEntitlementDetailsByID($database, $entitlementdetID, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);									
										}
										else
										{
											$sp->spUpdatePromoEntitlementDetailsByID($database, $entitlementdetID, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);
										}											
									}
								}
							}
							else
							{
								//product does not exist
								$msg_log .= "Entitlement Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
							}							
						}
						$multiline->close();
					}
					
					$database->commitTransaction();
					$message = "Successfully Uploaded Multiline Entitlement Promos";
				}
				else if ($uType == 7) //overlay - buyin
				{
					//retrieve in temp table
					$buyinparentID_new = 0;
					$new_promo = 0;
					$msg_log = "";
					$ctr = 0;
					$overlay = $sp->spSelectTmp_PromoOverlay_Buyin($database);
					
					if ($overlay->num_rows)
					{
						while ($row = $overlay->fetch_object())
						{
							$ctr += 1;
							$promo_code = str_replace("'", "\'",$row->PromoCode);	
							$promo_desc = str_replace("'", "\'",$row->PromoDescription);
							$promo_sdate = $row->PromoStartDate;
							$promo_edate = $row->PromoEndDate;
							$avail_nongsu = $row->MaxAvailNonGSU;
							$avail_directgsu = $row->MaxAvailDirectGSU;
							$avail_indirectgsu = $row->MaxAvailIndirectGSU;
							$buyin_reqtype = $row->BuyinPurchaseReqTypeID;
							$buyin_type = $row->BuyinTypeID;
							$buyin_plevel = $row->BuyinProductLevelID;
							$buyin_pcode = str_replace("'", "\'",$row->BuyinProductCode);
							$buyin_pdesc = str_replace("'", "\'",$row->BuyinProductDescription);
							$buyin_criteria = $row->Criteria;
							$buyin_minval = $row->MinimumValue;
							$buyin_sdate = $row->BuyinStartDate;
							$buyin_edate = $row->BuyinEndDate;
							$buyin_leveltype = $row->BuyinLevelTypeID;
							$isincentive = $row->IsIncentive;
							
							if ($buyin_criteria == 1)
							{
								$min_qty = $buyin_minval;
								$min_amt = 'null';				
							}
							else
							{
								$min_qty = 'null';
								$min_amt = $buyin_minval;
							}
							
							//get buyin product id
							$buyin_prod = 'buyin_prod'.$ctr;
							$buyin_prod = $sp->spSelectProductToUpload($database, $buyin_pcode);
							
							if ($buyin_prod->num_rows)
							{
								while($row = $buyin_prod->fetch_object())
								{
									$buyin_prodid = $row->ProductID; 									
								}
								$buyin_prod->close();
								
								//check if promo code exists						
								$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
								if (!$promo_exist->num_rows)
								{
									//insert promo header
									$rs_promoid = $sp->spInsertPromoHeaderOverlay($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 3, $buyin_type, $min_qty, $min_amt, $session->user_id, $isincentive);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
									}
									
									//insert to promoavailment table
									if ($avail_nongsu != null)
									{
										$rs_promoavail = $sp->spInsertPromoAvailment($database, $promoID, 1, $avail_nongsu);										
									}
									if ($avail_directgsu != null)
									{
										$rs_promoavail = $sp->spInsertPromoAvailment($database, $promoID, 2, $avail_directgsu);										
									}
									if ($avail_indirectgsu != null)
									{
										$rs_promoavail = $sp->spInsertPromoAvailment($database, $promoID, 3, $avail_indirectgsu);										
									}
																		
									//insert to promobuyin - parent
									if ($buyin_type == 1)
									{
										$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', 'null', 'null', 'null', 0);
									}
									else
									{
										$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', 'null', $promo_sdate, $promo_edate, 0);
									}
									if($rs_promobuyin_parent->num_rows)
									{
										while($row = $rs_promobuyin_parent->fetch_object())
										{
											$buyinparentID = $row->ID;
										}
									}
								}
								else
								{
									//get promo id
									$rs_promoid = 'rs_promoid'.$ctr;
									$rs_promoid = $sp->spSelectPromoByCode3($database, $promo_code);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
									}
									
									//update promo header
									$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate);
									
									//retrieve promobuyin - parent
									$rs_promobuyin_parent = $sp->spSelectParentPromoBuyIn($database, $promoID);
									if($rs_promobuyin_parent->num_rows)
									{
										while($row = $rs_promobuyin_parent->fetch_object())
										{
											$buyinparentID = $row->ID;
										}
									}
								}
								
								//check if promo availment exists
								if ($avail_nongsu != null)
								{
									$rs_promo_avail_1 = $sp->spCheckIfExistPromoAvailment($database, $promoID, 1);
									if($rs_promo_avail_1->num_rows)
									{
										$sp->spUpdatePromoAvailment($database, $promoID, 1, $avail_nongsu);
									}
									else
									{
										$sp->spInsertPromoAvailment($database, $promoID, 1, $avail_nongsu);										
									}										
								}
								
								if ($avail_directgsu != null)
								{
									$rs_promo_avail_2 = $sp->spCheckIfExistPromoAvailment($database, $promoID, 2);
									if($rs_promo_avail_2->num_rows)
									{
										$sp->spUpdatePromoAvailment($database, $promoID, 2, $avail_directgsu);
									}
									else
									{
										$sp->spInsertPromoAvailment($database, $promoID, 2, $avail_directgsu);										
									}										
								}
								
								if ($avail_indirectgsu != null)
								{
									$rs_promo_avail_3 = $sp->spCheckIfExistPromoAvailment($database, $promoID, 3);
									if($rs_promo_avail_3->num_rows)
									{
										$sp->spUpdatePromoAvailment($database, $promoID, 3, $avail_indirectgsu);
									}
									else
									{
										$sp->spInsertPromoAvailment($database, $promoID, 3, $avail_indirectgsu);										
									}										
								}
								
								//check if promo buyin exists
								$promo_buyin_exist = $sp->spCheckIfExistPromoBuyIn($database, $promoID, $buyin_criteria, $buyin_prodid);
								if (!$promo_buyin_exist->num_rows)
								{								
									//insert to promobuyin - child
									if ($buyin_criteria == 1)
									{
										$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_criteria, $min_qty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate, $buyin_edate, 1);
									}
									else
									{
										$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_criteria, 'null', $min_amt, 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate, $buyin_edate, 1);								
									}
								}
								else
								{
									if($promo_buyin_exist->num_rows)
									{
										while($row = $promo_buyin_exist->fetch_object())
										{
											$buyinchildID = $row->ID;
										}
										$promo_buyin_exist->close();
									}
									
									//update promobuyin - child
									if ($buyin_criteria == 1)
									{
										$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $promoID, $buyin_criteria, $min_qty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate, $buyin_edate, 1);
									}
									else
									{
										$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $promoID, $buyin_criteria, 'null', $min_amt, 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate, $buyin_edate, 1);
									}
								}								
							}
							else
							{
								//product does not exist
								$msg_log .= "Buyin Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
							}
						}
						$overlay->close();						
					}
					
					$database->commitTransaction();
					$message = "Successfully Uploaded Overlay Buyin Promos";
				}
				else if ($uType == 8) //overlay - entitlement
				{
					//retrieve in temp table
					$entparentID_new = 0;
					$msg_log = "";
					$ctr = 0;
					$overlay = $sp->spSelectTmp_PromoOverlay_Entitlement($database);
					
					if ($overlay->num_rows)
					{
						while ($row = $overlay->fetch_object())
						{
							$ctr += 1;
							$promo_code = str_replace("'", "\'",$row->PromoCode);	
							$promo_desc = str_replace("'", "\'",$row->PromoDescription);
							$promo_sdate = $row->PromoStartDate;
							$promo_edate = $row->PromoEndDate;
							$ent_type = $row->EntitlementTypeID;
							$ent_qty = $row->EntitlementQty;
							$ent_prodcode = str_replace("'", "\'",$row->EntitlementProductCode);
							$ent_proddesc = str_replace("'", "\'",$row->EntitlementProductDescription);
							$entdet_qty = $row->EntitlementDetQty;
							$entdet_price = $row->EntitlementDetEffPrice;
							$entdet_savings = $row->EntitlementDetSavings;
							$entdet_pmg = $row->EntitlementDetPMG;
							
							//get entitlement product id
							$ent_prod = 'ent_prod'.$ctr;
							$ent_prod = $sp->spSelectProductToUpload($database, $ent_prodcode);
							
							if ($ent_prod->num_rows)
							{
								while($row = $ent_prod->fetch_object())
								{
									$ent_prodid = $row->ProductID; 									
								}
								$ent_prod->close();
								
								//check if promo code exists						
								$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
								if (!$promo_exist->num_rows)
								{
									$msg_log .= "Promo Code ".$promo_code. " does not exist. Upload Promo Buyin first. <br>";						
								}
								else
								{
									//get promo id
									$rs_promoid = 'rs_promoid'.$ctr;
									$rs_promoid = $sp->spSelectPromoByCode3($database, $promo_code);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
									}
									
									//get parent promo buyin
									$buyin_parent = $sp->spSelectParentPromoBuyIn($database, $promoID);
									if($buyin_parent->num_rows)
									{
										while($row = $buyin_parent->fetch_object())
										{
											$buyinparentID = $row->ID;
										}
										$buyin_parent->close();
									}
									
									//update promo header
									$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate);
									
									//check if promo entitlement exists
									$promo_entitlement_exist = $sp->spCheckIfExistPromoEntitlement($database, $buyinparentID, $ent_type);
									if (!$promo_entitlement_exist->num_rows)
									{
										//insert entitlement
										$rs_promoentid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $ent_type, $ent_qty);
										if($rs_promoentid->num_rows)
										{
											while($row = $rs_promoentid->fetch_object())
											{
												$parentEntitlementID = $row->ID;
											}
											$rs_promoentid->close();
										}
									}
									else
									{
										if($promo_entitlement_exist->num_rows)
										{
											while($row = $promo_entitlement_exist->fetch_object())
											{
												$parentEntitlementID = $row->ID;
											}
											$promo_entitlement_exist->close();
										}
										
										//update promo entitlement
										$sp->spUpdatePromoEntitlementByID($database, $parentEntitlementID, $buyinparentID, $ent_type, $ent_qty);											
									}
									
									//check if promo entitlement details exists
									$promo_entitlementdet_exist = $sp->spCheckIfExistPromoEntitlementDetails($database, $parentEntitlementID, $ent_prodid);
									if (!$promo_entitlementdet_exist->num_rows)
									{
										//insert entitlementdetails
										if ($ent_type == 1)
										{
											$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);					
										}
										else
										{
											$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);
										}									
									}
									else
									{
										if($promo_entitlementdet_exist->num_rows)
										{
											while($row = $promo_entitlementdet_exist->fetch_object())
											{
												$entitlementdetID = $row->ID;
											}
											$promo_entitlementdet_exist->close();
										}
										
										//update promo entitlement details
										if($ent_type != 1)
										{
											$sp->spUpdatePromoEntitlementDetailsByID($database, $entitlementdetID, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);									
										}
										else
										{
											$sp->spUpdatePromoEntitlementDetailsByID($database, $entitlementdetID, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);
										}											
									}
								}
							}
							else
							{
								//product does not exist
								$msg_log .= "Entitlement Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
							}							
						}
						$overlay->close();						
					}
					
					$database->commitTransaction();
					$message = "Successfully Uploaded Overlay Entitlement Promos";
				}
				elseif($isrho) //For RHO
				{
					foreach( $data as $row ) 
					{
						$ctr2 += 1;
						if(trim($row['tpi_tmprhoID'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['PickListRefNo'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['LineCounter'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['ProductCode'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['Quantity'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['Description'])== "")
						{
							$chckEmpty = 1;
						}		 
							
						if($chckEmpty == 0)
						{
								$Uploader_rows = $sp->spInsertTmpRHODetails($database, trim($row['tpi_tmprhoID']),
																			trim($row['PickListRefNo']), 
																			trim($row['LineCounter']), 
																			trim($row['ProductCode']), 
																			trim($row['Quantity']), 
																			trim(str_replace("'","\'",$row['Description']))); 					
						}
												
						$val = 0;
						$chckEmpty = 0;		 	 
					}
					
					$sp->spUpdateTmpRHODetails($database);
					$sp->spInsertFromTmpRHO($database);
					$database->commitTransaction();
					$message = "Successfully Uploaded RHO File";
				}
				else //for STA
				{
					foreach( $data as $row ) 
					{
						$ctr2 += 1;
						if(trim($row['tpi_tmpstaID'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['STANo'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['LineCounter'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['ProductCode'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['Description'])== "")
						{
							$chckEmpty = 1;
						}
						else if(trim($row['Quantity'])== "")
						{
							$chckEmpty = 1;
						}		 
						if($chckEmpty == 0)
						{
							$Uploader_rows = $sp->spInsertTmpSTADetails($database, trim($row['tpi_tmpstaID']), 
																				   trim($row['STANo']), 
																			 trim($row['LineCounter']), 
																			 trim($row['ProductCode']), 
																			 trim(str_replace("'","\'",$row['Description'])), 
																			 trim($row['Quantity']));
						}            
						$val = 0;
						$chckEmpty = 0;		 	 
					}
					$sp->spUpdateTmpSTADetails($database);
					$sp->spInsertFromTmpSTA($database);
					//$database->commitTransaction();
					$message = "Successfully Uploaded STA File";
				}
			} 
			catch (Exception $e) 
			{
				$database->rollbackTransaction();
				$errmsg = $e->getMessage()."\n";
				
				
				//$errmsg = "Invalid Interface file.";
				redirect_to("../index.php?pageid=56&errmsg=$errmsg");
			}
		}
		$ctr = 0;
		//Validate insert	
		redirect_to("../index.php?pageid=56&msg=$message&msglog=$msg_log");
	}
?>