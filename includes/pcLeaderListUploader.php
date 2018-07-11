<?php //Buyin Product Code
	require_once("../initialize.php");
	require_once (CS_PATH.DS."ClassPromoAndPricingUploader.php");
	global $database;

	//ini_set('display_errors', 0);
	//ini_set("max-execution-time" , 600);
	
	if(isset($_POST['btnUpload']))
	{	
		
		$database->execute("SET FOREIGN_KEY_CHECKS=0");
	
		$path_Info = $_FILES['file']['name'];
		$ext = pathinfo($path_Info);
		$file_Size = $_FILES['file']['size'];	 
		$uType = addslashes($_POST['cboUploadType']);
		if(isset($_POST['cboCampaign']))
		{
			$campaignID = addslashes($_POST['cboCampaign']);
		}
		else
		{
			$campaignID = 0;
		}
		$uploadErr = "";
		
		if($path_Info == "")
	 	{
	 		$errormessage = "Please select a file to upload.";
			redirect_to("../index.php?pageid=135&err=" . urlencode($errormessage));  
	 	}
		
		if ($uType > 4)
		{
			$unuploadedLog = "";		
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
				redirect_to("../index.php?pageid=135&err=$errmsg");
			}
			
			if (is_uploaded_file($_FILES['file']['tmp_name']))
			{
				$fileData = trim(file_get_contents($_FILES['file']['tmp_name']));
				$rows = explode("\n", $fileData);
				
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
				$sl_isplusplan = 0;
				$slPageNum	   = "0-0";
				
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
				$ml_isplusplan = 0;
				$ml_pagenum	= "0-0";
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
				$ol_isplusplan = 0;
				$ol_PageNum = "0-0";
				$OverlayBuyinBrohureFrom = "";
				$OverlayBuyinBrohureTo   = "";
                                $OverlayApplyAsDiscount = 0;
				try
				{
                                    
				//leader list special promo uploader
				if($uType == 10){
					
					include "pcLeaderListSpecialPromo.php";
					
				}else if ($uType == 11 || $uType == 12){
					include "pcLeaderListPromoIncentives.php";
				}else{
                                    
                                    
					//$database->beginTransaction();
					foreach ($rows as $row)
				 	{
				 		if ($uType == 5 || $uType == 6 || $uType == 7 || $uType == 8 || $uType == 9)
						{
							$rdata = csvstring_to_array(trim($row), ',', '"', '\r\n');
						}
						
					  	if ($uType == 5) //single line promo
						{
							$promocode = tpi_csv_clean_value($rdata[0],'STRING','NULL');
							$promodesc = tpi_csv_clean_value($rdata[1],'STRING','NULL');
							$promosdate = tpi_csv_clean_value($rdata[2]);
							$promoedate = tpi_csv_clean_value($rdata[3]);
							$buyintype = tpi_csv_clean_value($rdata[4],'STRING','NULL');
							$buyinminqty = tpi_csv_clean_value($rdata[5],'STRING','NULL');
							$buyinminamt = tpi_csv_clean_value($rdata[6],'STRING','NULL');
							$buyinplevel = tpi_csv_clean_value($rdata[7],'STRING','NULL');
							$buyinpcode = tpi_csv_clean_value($rdata[8],'STRING','NULL');
							$buyinpdesc = tpi_csv_clean_value($rdata[9],'STRING','NULL');
							$buyinreqtype = tpi_csv_clean_value($rdata[10],'STRING','NULL');
							$buyinleveltype = tpi_csv_clean_value($rdata[11],'STRING','NULL');
							$enttype = tpi_csv_clean_value($rdata[12],'STRING','NULL');
							//$enttype = 1;
							$entqty = tpi_csv_clean_value($rdata[13],'STRING','NULL');
							$entprodcode = tpi_csv_clean_value($rdata[14],'STRING','NULL');
							$entproddesc = tpi_csv_clean_value($rdata[15],'STRING','NULL');
							$entdetqty = tpi_csv_clean_value($rdata[16],'STRING','NULL');
							$entdetprice = tpi_format_number(tpi_csv_clean_value($rdata[17],'STRING','NULL'));
							$entdetsavings = tpi_format_number(tpi_csv_clean_value($rdata[18],'STRING','NULL'));
							$entdetpmg = tpi_csv_clean_value($rdata[19],'STRING','NULL');
							$sl_isplusplan = tpi_csv_clean_value($rdata[20],'STRING','NULL');
							$slPageNum = tpi_csv_clean_value($rdata[21],'STRING','0-0');
							
							//ASL130901
							//if($promocode == 'ASL130901'){
							//	echo "<pre>";
							//	echo $mysqli->real_escape_string($entdetprice);
							//	print_r($rdata);
							//	echo "</pre>";
							//}
							//die();
							if($promocode!=NULL ||  $promocode!=""){							
								$Uploader_rows = $spUploader->spInsertTmpSingleLinePromo($database, 
								$promocode, 
								$mysqli->real_escape_string($promodesc), 
								date("Y-m-d",strtotime($promosdate)), 
								date("Y-m-d",strtotime($promoedate)), 
								$buyintype, 
								$buyinminqty, 
								$buyinminamt, 
								$buyinplevel, 
								$buyinpcode, 
								$mysqli->real_escape_string($buyinpdesc),
								$buyinreqtype, 
								$buyinleveltype, 
								$enttype, 
								$entqty, 
								$entprodcode, 
								$mysqli->real_escape_string($entproddesc), 
								$entdetqty, 
								$mysqli->real_escape_string($entdetprice),
								str_replace(',', '', $entdetsavings), 
								$entdetpmg, 
								$sl_isplusplan,
								$slPageNum);		
							}
						//die();							
						}
						else if ($uType == 6) //multiline promo - buyin
						{
							$mb_promocode = tpi_csv_clean_value($rdata[0],'STRING','NULL');
							$mb_promodesc = tpi_csv_clean_value($rdata[1],'STRING','NULL');
							$mb_promosdate = tpi_csv_clean_value($rdata[2],'DATE','NULL');
							$mb_promoedate = tpi_csv_clean_value($rdata[3],'DATE','NULL');
							$mb_buyintype = tpi_csv_clean_value($rdata[4],'STRING','NULL');
							$mb_buyinminqty = tpi_csv_clean_value($rdata[5],'STRING','NULL');                               
							$mb_buyinminamt = tpi_format_number(tpi_csv_clean_value($rdata[6],'STRING','NULL'));
							$mb_buyinplevel = tpi_csv_clean_value($rdata[7],'STRING','NULL');
							$mb_buyinpcode = tpi_csv_clean_value($rdata[8],'STRING','NULL');
							$mb_buyinpdesc = tpi_csv_clean_value($rdata[9],'STRING','NULL');
							$mb_buyinreqtype = tpi_csv_clean_value($rdata[10],'STRING','NULL');
							$mb_buyinleveltype = tpi_csv_clean_value($rdata[11],'STRING','NULL');
							$ml_isplusplan = tpi_csv_clean_value($rdata[12],'STRING','NULL');                                                       
							$ml_pagenum = tpi_csv_clean_value($rdata[13],'STRING','0-0');                                                       
                            
							if($mb_promocode!=NULL){	
                                                            $Uploader_rows = $spUploader->spInsertTmpMultiLineBuyinPromo($database, 
                                                                                $mb_promocode, 
                                                                                $mysqli->real_escape_string($mb_promodesc), 
                                                                                date("Y-m-d",strtotime($mb_promosdate)), 
                                                                                date("Y-m-d",strtotime($mb_promoedate)), 
                                                                                $mb_buyintype, 
                                                                                $mb_buyinminqty, 
                                                                                $mb_buyinminamt, 
                                                                                $mb_buyinplevel, 
                                                                                $mb_buyinpcode, 
                                                                                $mysqli->real_escape_string($mb_buyinpdesc), 
                                                                                $mb_buyinreqtype, 
                                                                                $mb_buyinleveltype, 
                                                                                $ml_isplusplan,
																				$ml_pagenum);
                                                        }
						}													
						else if ($uType == 7) //multiline promo - entitlement
						{
							$mb_promocode = tpi_csv_clean_value($rdata[0],'STRING','NULL');
							$mb_promodesc = tpi_csv_clean_value($rdata[1],'STRING','NULL');
							$mb_promosdate = tpi_csv_clean_value($rdata[2],'DATE','NULL');
							$mb_promoedate = tpi_csv_clean_value($rdata[3],'DATE','NULL');
							$mb_enttype = tpi_csv_clean_value($rdata[4],'STRING','NULL');
							$mb_entqty = tpi_csv_clean_value($rdata[5],'STRING','NULL');
							$mb_entprodcode = tpi_csv_clean_value($rdata[6],'STRING','NULL');
							$mb_entproddesc = tpi_csv_clean_value($rdata[7],'STRING','NULL');
							$mb_entdetqty = tpi_csv_clean_value($rdata[8],'STRING','NULL');
                                                        
							$mb_entdetprice = tpi_format_number(tpi_csv_clean_value($rdata[9],'STRING','NULL'));                                                                                                                
							$mb_entdetsavings = tpi_format_number(tpi_csv_clean_value($rdata[10],'STRING','NULL'));
							$mb_entdetpmg = tpi_csv_clean_value($rdata[11],'STRING','NULL');
							
							if($mb_promocode!=NULL){	
								$Uploader_rows = $sp->spInsertTmpMultiLineEntitlementPromo( 
																							$database, $mb_promocode, 
																							$mysqli->real_escape_string($mb_promodesc), 
																							date("Y-m-d",strtotime($mb_promosdate)), 
																							date("Y-m-d",strtotime($mb_promoedate)), 
																							$mb_enttype, 
																							$mb_entqty, $mb_entprodcode, 
																							$mysqli->real_escape_string($mb_entproddesc), 
																							$mb_entdetqty, $mb_entdetprice, 
																							$mysqli->real_escape_string($mb_entdetsavings), 
																							$mb_entdetpmg
																						  );
							}
						}
						else if ($uType == 8) //overlay promo - buyin
						{
							$ob_promocode = tpi_csv_clean_value($rdata[0],'STRING','NULL');
							$ob_promodesc = tpi_csv_clean_value($rdata[1],'STRING','NULL');
							$ob_promosdate = tpi_csv_clean_value($rdata[2],'DATE','NULL');
							$ob_promoedate = tpi_csv_clean_value($rdata[3],'DATE','NULL');
							$ob_nongsu = tpi_csv_clean_value($rdata[4],'STRING','NULL');
							$ob_dirgsu = tpi_csv_clean_value($rdata[5],'STRING','NULL');
							$ob_idirgsu = tpi_csv_clean_value($rdata[6],'STRING','NULL');
							$ob_buyinreqtype = tpi_csv_clean_value($rdata[7],'STRING','NULL');
							$ob_buyintype = tpi_csv_clean_value($rdata[8],'STRING','NULL');
							//$ob_buyinplevel = str_replace("'", "\'",$rdata[9]);
							$ob_buyinplevel = "3";
							$ob_buyinpcode = tpi_csv_clean_value($rdata[10],'STRING','NULL');
							$ob_buyinpdesc = tpi_csv_clean_value($rdata[11],'STRING','NULL');
							$ob_buyincriteria = tpi_csv_clean_value($rdata[12],'STRING','NULL');
							$ob_buyinminval = tpi_csv_clean_value($rdata[13],'STRING','NULL');
							$ob_buyinsdate = tpi_csv_clean_value($rdata[14],'DATE','NULL');
							$ob_buyinedate = tpi_csv_clean_value($rdata[15],'DATE','NULL');
							$ob_buyinleveltype = tpi_csv_clean_value($rdata[16],'STRING','NULL');
							$ob_isincentive = tpi_csv_clean_value($rdata[17],'STRING','NULL');
							$ol_isplusplan = tpi_csv_clean_value($rdata[18],'STRING','NULL');							
							$ol_PageNum = tpi_csv_clean_value($rdata[19],'STRING','0-0');							
							$OverlaySelectionQty = tpi_csv_clean_value($rdata[20],'STRING','0');							
							$OverlayApplyAsDiscount = tpi_csv_clean_value($rdata[22],'STRING','0');
							//here..
							if($ob_promocode!=NULL){	
								$Uploader_rows = $spUploader->spInsertTmpOverlayBuyinPromo( 
																					$database, $ob_promocode, 
																					$mysqli->real_escape_string($ob_promodesc), 
																					date("Y-m-d",strtotime($ob_promosdate)), 
																					date("Y-m-d",strtotime($ob_promoedate)), 
																					$ob_nongsu, 
																					$ob_dirgsu, $ob_idirgsu, $ob_buyinreqtype, 
																					$ob_buyintype, $ob_buyinplevel, $ob_buyinpcode, 
																					$mysqli->real_escape_string($ob_buyinpdesc), 
																					$ob_buyincriteria, $ob_buyinminval, $ob_buyinsdate, 
																					$ob_buyinedate, $ob_buyinleveltype, $ob_isincentive, 
																					$ol_isplusplan, $ol_PageNum,$OverlaySelectionQty,
                                                                                                                                                                        $OverlayApplyAsDiscount);
							}
						}
						else if ($uType == 9) //overlay promo - entitlement
						{
							$oe_promocode = tpi_csv_clean_value($rdata[0],'STRING','NULL');
							$oe_promodesc = tpi_csv_clean_value($rdata[1],'STRING','NULL');
							$oe_promosdate = tpi_csv_clean_value($rdata[2],'DATE','NULL');
							$oe_promoedate = tpi_csv_clean_value($rdata[3],'DATE','NULL');
							$oe_enttype = tpi_csv_clean_value($rdata[4],'STRING','NULL');
							$oe_entqty = tpi_csv_clean_value($rdata[5],'STRING','NULL');
							$oe_entprodcode = tpi_csv_clean_value($rdata[6],'STRING','NULL');
							$oe_entproddesc = tpi_csv_clean_value($rdata[7],'STRING','NULL');
							$oe_entdetqty = tpi_csv_clean_value($rdata[8],'STRING','NULL');
                                                        
							$oe_entdetprice = tpi_format_number(tpi_csv_clean_value($rdata[9],'STRING','NULL'));                                                                                                               
							$oe_entdetsavings = tpi_format_number(tpi_csv_clean_value($rdata[10],'STRING','NULL'));                                                        
                                                        
							$oe_entdetpmg = tpi_csv_clean_value($rdata[11],'STRING','NULL');
							
							if($oe_promocode!=NULL){	
								$Uploader_rows = $sp->spInsertTmpOverlayEntitlementPromo(
																						 $database, 
																						 $oe_promocode, 
																						 $mysqli->real_escape_string($oe_promodesc), 
																						 date("Y-m-d",strtotime($oe_promosdate)), 
																						 date("Y-m-d",strtotime($oe_promoedate)), 
																						 $oe_enttype, 
																						 $oe_entqty, 
																						 $oe_entprodcode, 
																						 $mysqli->real_escape_string($oe_entproddesc), 
																						 $oe_entdetqty, 
																						 $oe_entdetprice, 
																						 $oe_entdetsavings, 
																						 $oe_entdetpmg
																						);
							}
						}
				 	}
					$ctr2 = 0;
					$chckEmpty = 0;
					$val = 0;
				
					if ($uType == 5)
					{
						//delete existing promo details
						$details = $sp->spSelectTmp_PromoSingleLine($database);
						if ($details->num_rows)
						{
							while ($row_det = $details->fetch_object())
							{
								$promo_code = str_replace("'", "\'",$row_det->PromoCode);
								$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
								if ($promo_exist->num_rows)
								{
									$rs_promoid = $sp->spSelectPromoByCode2($database, $promo_code);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
										
										//delete existing buyin requirements
										//$sp->spDeletePromoDetailsByPromoID($database, $promoID);
									}						
								}
							}
							$details->close();
						}
						
						//retrieve in temp table (single line)
						$errmsg = "";
						$ctr = 0;
						$cd_cnt = 0;
						$singleline = $spUploader->spSelectTmp_PromoSingleLine($database);
						$ud_cnt = $singleline->num_rows;
						 
						if ($singleline->num_rows)
						{
							while ($row = $singleline->fetch_object())
							{
								$ctr += 1;
								$promo_code = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoCode),'STRING',0);	
								$promo_desc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoDescription),'STRING','NULL');
								$promo_sdate = $row->PromoStartDate;
								$promo_edate = $row->PromoEndDate;
								$buyin_type = tpi_cleanFromDBToDBVal($row->BuyinTypeID,'STRING',0);
								$buyin_minqty = tpi_cleanFromDBToDBVal($row->BuyinMinQty,'STRING',0);
								$buyin_minamt = tpi_cleanFromDBToDBVal($row->BuyinMinAmt,'STRING',0);
								$buyin_plevel = tpi_cleanFromDBToDBVal($row->BuyinProductLevelID,'STRING',0);
								$buyin_pcode = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->BuyinProductCode),'STRING',0);
								$buyin_pdesc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->BuyinProductDescription),'STRING','NULL');
								$buyin_reqtype = tpi_cleanFromDBToDBVal($row->BuyinPurchaseReqTypeID,'STRING',0);
								$buyin_leveltype = tpi_cleanFromDBToDBVal($row->BuyinLevelTypeID,'STRING',0);
								$ent_type = tpi_cleanFromDBToDBVal($row->EntitlementTypeID,'STRING',0);
								$ent_qty = tpi_cleanFromDBToDBVal($row->EntitlementQty,'STRING',0);
								$ent_prodcode = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->EntitlementProductCode),'STRING',0);
								$ent_proddesc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->EntitlementProductDescription),'STRING','NULL');
								$entdet_qty = tpi_cleanFromDBToDBVal($row->EntitlementDetQty,'STRING',0);
								$entdet_price = tpi_cleanFromDBToDBVal($row->EntitlementDetEffPrice,'STRING',0);
								$entdet_savings = tpi_cleanFromDBToDBVal($row->EntitlementDetSavings,'STRING',0);
								$entdet_pmg = tpi_cleanFromDBToDBVal($row->EntitlementDetPMG,'STRING',0);
								$promo_isplusplan = tpi_cleanFromDBToDBVal($row->IsPlusPlan,'STRING',0);
								$promo_pagenum = tpi_cleanFromDBToDBVal($row->PageNum,'STRING','0-0');
								
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
										
										//check if pmg exists
										$pmg_exist = $sp->spSelectPMGByID($database, $entdet_pmg);
										if (!$pmg_exist->num_rows)
										{
											//pmg does not exist
											$errmsg .= "PMG of Entitlement Product Code - ".$ent_prodcode." for Promo Code ".$promo_code. " does not exist. <br>";										
										}
										else
										{
											//check if promo code exists						
											$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
											if (!$promo_exist->num_rows)
											{
												//insert promo header
												$rs_promoid = $spUploader->spInsertPromoHeader($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 1, $session->emp_id, $promo_isplusplan, $promo_pagenum);
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
													
													//update promo header
													$spUploader->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan, $promo_pagenum);
													
												}
												else
												{
													//promo code already exist in diff promo type
													$promoID = 0;
													$errmsg .= "Promo Code ".$promo_code. " already exist in other Promo Type. <br>";											
												}
											}
											
											if ($promoID != 0)
											{
												//check if promo buyin exists
												
												$promo_buyin_exist = $sp->spCheckIfExistPromoBuyIn($database, $promoID, $buyin_type, $buyin_prodid);
												if (!$promo_buyin_exist->num_rows)
												{
													if ($buyin_type == 1)
													{
														$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, 'null', $buyin_type, $buyin_minqty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', date("Y-m-d",strtotime($promo_sdate)), date("Y-m-d",strtotime($promo_edate)), 1);
													}
													else
													{
														$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, 'null', $buyin_type, 'null', $buyin_minamt, 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', date("Y-m-d",strtotime($promo_sdate)), date("Y-m-d",strtotime($promo_edate)), 1);								
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
														$sp->spUpdatePromoBuyInByID($database, $buyinparentID, $promoID, $buyin_type, $buyin_minqty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', date("Y-m-d",strtotime($promo_sdate)), date("Y-m-d",strtotime($promo_edate)), 1);
													}
													else
													{
														$sp->spUpdatePromoBuyInByID($database, $buyinparentID, $promoID, $buyin_type, 'null', $buyin_minamt, 'null', 'null', $buyin_plevel, $buyin_prodid, 'null', date("Y-m-d",strtotime($promo_sdate)), date("Y-m-d",strtotime($promo_edate)), 1);
													}
												}
												
												//check if promo entitlement exists
												$promo_entitlement_exist = $sp->spCheckIfExistPromoEntitlement($database, $buyinparentID, 1);
												if (!$promo_entitlement_exist->num_rows)
												{
													//insert entitlement
													$rs_promoentid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $ent_type, 1);
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
													$sp->spUpdatePromoEntitlementByID($database, $entitlementID, $buyinparentID, $ent_type, 1);											
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
												$cd_cnt += 1;
											}										
										}
									}
									else
									{
										//product does not exist
										$errmsg .= "Entitlement Product Code - ".$ent_prodcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
									}								
								}
								else
								{
									//product does not exist
									$errmsg .= "Buyin Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
								}
							}
							$singleline->close();						
						}
						
						$database->commitTransaction();
						if ($errmsg == "")
						{
							$msgLog = "Successfully Uploaded Single Line Promos";						
						}
						else
						{
							if ($cd_cnt > 0)
							{
								$msgLog = "Successfully Uploaded Single Line Promos";							
							}						
						}
						
						$not_uploaded = $ud_cnt - $cd_cnt;
						$msgLog .= "<br><br><br>";
						$msgLog .= "Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "Total Rows Not Uploaded: ". $not_uploaded."<br><br><br>";
						
						$LogType   		= "Single Line Promos";
						$Description    = $msgLog;
						$xDate			= "NOW()";
						$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
						
						
					}
					else if ($uType == 6) //multiline - buyin
					{
						
						//delete existing promo details
						$details = $sp->spSelectTmp_PromoMultiline_Buyin($database);
						if ($details->num_rows)
						{
							while ($row_det = $details->fetch_object())
							{
								$promo_code = str_replace("'", "\'",$row_det->PromoCode);
								$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
								if ($promo_exist->num_rows)
								{
									$rs_promoid = $sp->spSelectPromoByCode($database, $promo_code);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
										
										//delete existing buyin requirements
										//$sp->spDeletePromoDetailsByPromoID($database, $promoID);
									}						
								}
							}
							$details->close();
						}
						
						//retrieve in temp table
						$buyinparentID_new = 0;
						$new_promo = 0;
						$errmsg = "";
						$ctr = 0;
						$cd_cnt = 0;
						$multiline = $sp->spSelectTmp_PromoMultiline_Buyin($database);
						$ud_cnt = $multiline->num_rows;
						
						if ($multiline->num_rows)
						{
							while ($row = $multiline->fetch_object())
							{
								$ctr += 1;
								$promo_code = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoCode),'STRING',0);	
								$promo_desc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoDescription),'STRING','NULL');
								$promo_sdate = $row->PromoStartDate;
								$promo_edate = $row->PromoEndDate;
								$buyin_type = tpi_cleanFromDBToDBVal($row->BuyinTypeID,'STRING',0);
								$buyin_minqty = tpi_cleanFromDBToDBVal($row->BuyinMinQty,'STRING',0);
								$buyin_minamt = tpi_cleanFromDBToDBVal($row->BuyinMinAmt,'STRING',0);
								$buyin_plevel = tpi_cleanFromDBToDBVal($row->BuyinProductLevelID,'STRING',0);
								$buyin_pcode = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->BuyinProductCode),'STRING',0);
								$buyin_pdesc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->BuyinProductDescription),'STRING','NULL');
								$buyin_reqtype = tpi_cleanFromDBToDBVal($row->BuyinPurchaseReqTypeID,'STRING',0);
								$buyin_leveltype = tpi_cleanFromDBToDBVal($row->BuyinLevelTypeID,'STRING',0);
								$promo_isplusplan = tpi_cleanFromDBToDBVal($row->IsPlusPlan,'STRING',0);
								$promo_pagenum = tpi_cleanFromDBToDBVal($row->PageNum,'STRING','0-0');
								
								//echo $promo_PageNum."-".$row->PageNum;
								//die();
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
										//$rs_promoid = $sp->spInsertPromoHeader($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 2, $session->emp_id, $promo_isplusplan);
										$rs_promoid = $spUploader->spInsertPromoHeader($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 2, $session->emp_id, $promo_isplusplan,$promo_pagenum);
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
											
											//update promo header
											//$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan);
											$spUploader->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan, $promo_pagenum);

										}
										else
										{
											//promo code already exist in diff promo type
											$promoID = 0;
											$errmsg .= "Promo Code ".$promo_code. " already exist in other Promo Type. <br>";										
										}
									}
									
									if ($promoID != 0)
									{
										//retrieve promobuyin - parent
										$rs_promobuyin_parent = $sp->spSelectParentPromoBuyIn($database, $promoID);
										if($rs_promobuyin_parent->num_rows)
										{
											while($row = $rs_promobuyin_parent->fetch_object())
											{
												$buyinparentID = $row->ID;
											}
										}
										else
										{
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
										$cd_cnt += 1;
									}
								}
								else
								{
									//product does not exist
									$errmsg .= "Buyin Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
								}							
							}
							$multiline->close();
						}
						
						$database->commitTransaction();
						if ($errmsg == "")
						{
							$msgLog = "Successfully Uploaded Multiline Buyin Promos";
						}
						else
						{
							if ($cd_cnt > 0)
							{
								$msgLog = "Successfully Uploaded Multiline Buyin Promos";							
							}						
						}
						
						$not_uploaded = $ud_cnt - $cd_cnt;
						$msgLog .= "<br><br><br>";
						$msgLog .= "Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "Total Rows Not Uploaded: ". $not_uploaded."<br><br><br>";
						
						$LogType   		= "Multiline Buyin Promos";
						$Description    = $msgLog;
						$xDate			= "NOW()";
						// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")");
						$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
						
					}
					else if ($uType == 7) //multiline - entitlement
					{
						//retrieve in temp table
						$entparentID_new = 0;
						$errmsg = "";
						$ctr = 0;
						$cd_cnt = 0;
						$multiline = $sp->spSelectTmp_PromoMultiline_Entitlement($database);
						$ud_cnt = $multiline->num_rows;
						
						if ($multiline->num_rows)
						{
							while ($row = $multiline->fetch_object())
							{
								$ctr += 1;
								$promo_code = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoCode),'STRING',0);	
								$promo_desc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoDescription),'STRING','NULL');
								$promo_sdate = $row->PromoStartDate;
								$promo_edate = $row->PromoEndDate;
								$ent_type = tpi_cleanFromDBToDBVal($row->EntitlementTypeID,'STRING',0);
								$ent_qty = tpi_cleanFromDBToDBVal($row->EntitlementQty,'STRING',0);
								$ent_prodcode = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->EntitlementProductCode),'STRING',0);
								$ent_proddesc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->EntitlementProductDescription),'STRING','NULL');
								$entdet_qty = tpi_cleanFromDBToDBVal($row->EntitlementDetQty,'STRING',0);
								$entdet_price = tpi_cleanFromDBToDBVal($row->EntitlementDetEffPrice,'STRING',0);
								$entdet_savings = tpi_cleanFromDBToDBVal($row->EntitlementDetSavings,'STRING',0);
								$entdet_pmg = tpi_cleanFromDBToDBVal($row->EntitlementDetPMG,'STRING',0);
								
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
									
									//check if pmg exists
									$pmg_exist = $sp->spSelectPMGByID($database, $entdet_pmg);
									if (!$pmg_exist->num_rows)
									{
										//pmg does not exist
										$errmsg .= "PMG of Entitlement Product Code - ".$ent_prodcode." for Promo Code ".$promo_code. " does not exist. <br>";										
									}
									else
									{
										//check if promo code exists						
										$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
										if (!$promo_exist->num_rows)
										{
											$errmsg .= "Promo Code ".$promo_code. " does not exist. Upload Promo Buyin first. <br>";						
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
												$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, 2);
											}
											else
											{
												//promo code already exist in diff promo type
												$promoID = 0;
												$errmsg .= "Promo Code ".$promo_code. " already exist in other Promo Type. <br>";
											}
										}
										
										if ($promoID != 0)
										{
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
											$cd_cnt += 1;
										}									
									}
								}
								else
								{
									//product does not exist
									$errmsg .= "Entitlement Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
								}							
							}
							$multiline->close();
						}
						
						$database->commitTransaction();
						if ($errmsg == "")
						{
							$msgLog = "Successfully Uploaded Multiline Entitlement Promos";
						}
						else
						{
							if ($cd_cnt > 0)
							{
								$msgLog = "Successfully Uploaded Multiline Entitlement Promos";							
							}						
						}
						
						$not_uploaded = $ud_cnt - $cd_cnt;
						$msgLog .= "<br><br><br>";
						$msgLog .= "Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "Total Rows Not Uploaded: ". $not_uploaded."<br><br><br>";
						
						$LogType   		= "Multiline Entitlement Promos";
						$Description    = $msgLog;
						$xDate			= "NOW()";
						// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")");
						$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
						
					}
					else if ($uType == 8) //overlay - buyin
					{
						//delete existing promo details
						$details = $sp->spSelectTmp_PromoOverlay_Buyin($database);
						if ($details->num_rows)
						{
							while ($row_det = $details->fetch_object())
							{
								$promo_code = str_replace("'", "\'",$row_det->PromoCode);
								$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
								if ($promo_exist->num_rows)
								{
									$rs_promoid = $sp->spSelectPromoByCode3($database, $promo_code);
									if($rs_promoid->num_rows)
									{
										while($row = $rs_promoid->fetch_object())
										{
											$promoID = $row->ID;
										}
										$rs_promoid->close();
										
										//delete existing buyin requirements
										//$sp->spDeletePromoDetailsByPromoID($database, $promoID);
									}						
								}
							}
							$details->close();
						}
						
						//retrieve in temp table
						$buyinparentID_new = 0;
						$new_promo = 0;
						$errmsg = "";
						$ctr = 0;
						$cd_cnt = 0;
						$overlay = $sp->spSelectTmp_PromoOverlay_Buyin($database);
						$ud_cnt = $overlay->num_rows;
						
						if ($overlay->num_rows)
						{
							while ($row = $overlay->fetch_object())
							{
								$ctr += 1;
								$promo_code = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoCode),'STRING',0);	
								$promo_desc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoDescription),'STRING','NULL');
								$promo_sdate = $row->PromoStartDate;
								$promo_edate = $row->PromoEndDate;
								$avail_nongsu = tpi_cleanFromDBToDBVal($row->MaxAvailNonGSU,'STRING',0);
								$avail_directgsu = tpi_cleanFromDBToDBVal($row->MaxAvailDirectGSU,'STRING',0);
								$avail_indirectgsu = tpi_cleanFromDBToDBVal($row->MaxAvailIndirectGSU,'STRING',0);
								$buyin_reqtype = tpi_cleanFromDBToDBVal($row->BuyinPurchaseReqTypeID,'STRING',0);
								$buyin_type = tpi_cleanFromDBToDBVal($row->BuyinTypeID,'STRING',0);
								$buyin_plevel = tpi_cleanFromDBToDBVal($row->BuyinProductLevelID,'STRING',0);
								$buyin_pcode = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->BuyinProductCode),'STRING','NULL');
								$buyin_pdesc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->BuyinProductDescription),'STRING','NULL');
								$buyin_criteria = tpi_cleanFromDBToDBVal($row->Criteria,'STRING',0);
								$buyin_minval = tpi_cleanFromDBToDBVal($row->MinimumValue,'STRING',0);
								$buyin_sdate = $row->BuyinStartDate;
								$buyin_edate = $row->BuyinEndDate;
								$buyin_leveltype = tpi_cleanFromDBToDBVal($row->BuyinLevelTypeID,'STRING',0);
								$isincentive = tpi_cleanFromDBToDBVal($row->IsIncentive,'STRING',0);
								$promo_isplusplan = tpi_cleanFromDBToDBVal($row->IsPlusPlan,'STRING',0);
								$promo_pagenum = tpi_cleanFromDBToDBVal($row->PageNum,'STRING',0);
								$OverlaySelectionQty = tpi_cleanFromDBToDBVal($row->OverlaySelectionQty,'STRING',0);
								$OverlayApplyAsDiscount = $row->OverlayApplyAsDiscount; 
                                                                
                                                                
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
								
								if ($buyin_type == 1)
								{
									$buyin_sdate_f = $buyin_sdate;
									$buyin_edate_f = $buyin_edate;
								}
								else
								{
									$buyin_sdate_f = $promo_sdate;
									$buyin_edate_f = $promo_edate;
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
										//$rs_promoid = $sp->spInsertPromoHeaderOverlay($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 3, $buyin_type, $min_qty, $min_amt, $session->emp_id, $isincentive, $promo_isplusplan);
										$rs_promoid = $spUploader->spInsertPromoHeaderOverlay($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 3, $buyin_type, $min_qty, $min_amt, $session->emp_id, $isincentive, $promo_isplusplan, $promo_pagenum, $OverlaySelectionQty,$OverlayApplyAsDiscount);
										
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
											$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', 'null', $promo_sdate, $promo_edate, 0);
										}
										else
										{
											$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', 'null', 'null', 'null', 0);
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
											
											//update promo header
											//$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan);
											$spUploader->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan, $promo_pagenum);

										}
										else
										{
											//promo code already exist in diff promo type
											$promoID = 0;
											$errmsg .= "Promo Code ".$promo_code. " already exist in other Promo Type. <br>";										
										}
									}
									
									if ($promoID != 0)
									{
										//retrieve promobuyin - parent
										$rs_promobuyin_parent = $sp->spSelectParentPromoBuyIn($database, $promoID);
										if($rs_promobuyin_parent->num_rows)
										{
											while($row = $rs_promobuyin_parent->fetch_object())
											{
												$buyinparentID = $row->ID;
											}
										}
										else
										{
											//insert to promobuyin - parent
											if ($buyin_type == 1)
											{
												$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', 'null', $promo_sdate, $promo_edate, 0);
											}
											else
											{
												$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', 'null', 'null', 'null', 0);
											}
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
										
										//get product csp
										$csp = 0;
										$prod_csp = $sp->spSelectProductDM($database, $buyin_prodid, "");
										if($prod_csp->num_rows)
										{
											while($row_csp = $prod_csp->fetch_object())
											{
												$csp = $row_csp->UnitPrice;
											}
											$prod_csp->close();
										}
										
										//check if promo buyin exists
										$promo_buyin_exist = $sp->spCheckIfExistPromoBuyIn($database, $promoID, $buyin_criteria, $buyin_prodid);
										if (!$promo_buyin_exist->num_rows)
										{								
											//insert to promobuyin - child
											if ($buyin_criteria == 1)
											{
												if ($buyin_type  == 2)
												{
													$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_criteria, 1, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate_f, $buyin_edate_f, 1);												
												}
												else
												{
													$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_criteria, $min_qty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate_f, $buyin_edate_f, 1);												
												}
											}
											else
											{
												if ($buyin_type  == 2)
												{
													$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_criteria, 'null', $csp, 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate_f, $buyin_edate_f, 1);												
												}
												else
												{
													$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $buyin_criteria, 'null', $min_amt, 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate_f, $buyin_edate_f, 1);												
												}
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
												if ($buyin_type  == 2)
												{
													$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $promoID, $buyin_criteria, 1, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate_f, $buyin_edate_f, 1);												
												}
												else
												{
													$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $promoID, $buyin_criteria, $min_qty, 'null', 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate_f, $buyin_edate_f, 1);												
												}
											}
											else
											{
												if ($buyin_type  == 2)
												{
													$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $promoID, $buyin_criteria, 'null', $csp, 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate_f, $buyin_edate_f, 1);												
												}
												else
												{
													$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $promoID, $buyin_criteria, 'null', $min_amt, 'null', 'null', $buyin_plevel, $buyin_prodid, $buyin_reqtype, $buyin_sdate_f, $buyin_edate_f, 1);												
												}
											}
										}
										$cd_cnt += 1;	
									}
								}
								else
								{
									//product does not exist
									$errmsg .= "Buyin Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
								}
							}
							$overlay->close();						
						}
						
						$database->commitTransaction();
						if ($errmsg == "")
						{
							$msgLog = "Successfully Uploaded Overlay Buyin Promos";
						}
						else
						{
							if ($cd_cnt > 0)
							{
								$msgLog = "Successfully Uploaded Overlay Buyin Promos";							
							}						
						}
						
						$not_uploaded = $ud_cnt - $cd_cnt;
						$msgLog .= "<br><br><br>";
						$msgLog .= "Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "Total Rows Not Uploaded: ". $not_uploaded."<br><br><br>";
						
						$LogType   		= "Overlay Buyin Promos";
						$Description    = $msgLog;
						$xDate			= "NOW()";
						// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")");
						$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
						
					}
					else if ($uType == 9) //overlay - entitlement
					{
						//retrieve in temp table
						$entparentID_new = 0;
						$errmsg = "";
						$ctr = 0;
						$cd_cnt = 0;
						$overlay = $sp->spSelectTmp_PromoOverlay_Entitlement($database);
						$ud_cnt = $overlay->num_rows;
						
						if ($overlay->num_rows)
						{
							while ($row = $overlay->fetch_object())
							{
								$ctr += 1;
								$promo_code = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoCode),'STRING',0);	
								$promo_desc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->PromoDescription),'STRING',0);
								$promo_sdate = $row->PromoStartDate;
								$promo_edate = $row->PromoEndDate;
								$ent_type = tpi_cleanFromDBToDBVal($row->EntitlementTypeID,'STRING',0);
								$ent_qty = tpi_cleanFromDBToDBVal($row->EntitlementQty,'STRING',0);
								$ent_prodcode = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->EntitlementProductCode),'STRING',0);
								$ent_proddesc = tpi_cleanFromDBToDBVal(str_replace("'", "\'",$row->EntitlementProductDescription),'STRING','NULL');
								$entdet_qty = tpi_cleanFromDBToDBVal($row->EntitlementDetQty,'STRING',0);
								$entdet_price = tpi_cleanFromDBToDBVal($row->EntitlementDetEffPrice,'STRING',0);
								$entdet_savings = tpi_cleanFromDBToDBVal($row->EntitlementDetSavings,'STRING',0);
								$entdet_pmg = tpi_cleanFromDBToDBVal($row->EntitlementDetPMG,'STRING',0);
								
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
									
									//check if pmg exists
									$pmg_exist = $sp->spSelectPMGByID($database, $entdet_pmg);
									if (!$pmg_exist->num_rows)
									{
										//pmg does not exist
										$errmsg .= "PMG of Entitlement Product Code - ".$ent_prodcode." for Promo Code ".$promo_code. " does not exist. <br>";										
									}
									else
									{
										//check if promo code exists						
										$promo_exist = $sp->spCheckPromoIfExists($database, $promo_code);
										if (!$promo_exist->num_rows)
										{
											$errmsg .= "Promo Code ".$promo_code. " does not exist. Upload Promo Buyin first. <br>";						
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
												$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, 2);
											}
											else
											{
												//promo code already exist in diff promo type
												$promoID = 0;
												$errmsg .= "Promo Code ".$promo_code. " already exist in other Promo Type. <br>";		
											}
										}
										
										if ($promoID != 0)
										{
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
												//if ($ent_type == 1)
												//{
													//$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);					
												//}
												//else
												//{
													$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $parentEntitlementID, $ent_prodid, $entdet_qty, $entdet_price, $entdet_savings, $entdet_pmg);
												//}									
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
											$cd_cnt += 1;
										}									
									}
								}
								else
								{
									//product does not exist
									$errmsg .= "Entitlement Product Code - ".$buyin_pcode." for Promo Code ".$promo_code. " does not exist in Product master. <br>";
								}							
							}
							$overlay->close();						
						}
						
						$database->commitTransaction();
						if ($errmsg == "")
						{
							$msgLog = "Successfully Uploaded Overlay Entitlement Promos";
						}
						else
						{
							if ($cd_cnt > 0)
							{
								$msgLog = "Successfully Uploaded Overlay Entitlement Promos";							
							}						
						}
						
						$not_uploaded = $ud_cnt - $cd_cnt;
						$msgLog .= "<br><br><br>";
						$msgLog .= "Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "Total Rows Not Uploaded: ". $not_uploaded."<br><br><br>";
						
						$LogType   		= "Overlay Entitlement Promos";
						$Description    = $msgLog;
						$xDate			= "NOW()";
						// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")");
						$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
					}
                                    }
				} 
				catch (Exception $e) 
				{
					$database->rollbackTransaction();
					$errmsg = $e->getMessage()."\n";
					
					//$errmsg = "Invalid Interface file.";
					redirect_to("../index.php?pageid=135&errmsg=$errmsg");
				}
			}
			
			$ctr = 0;
		}
		else
		{
		 	// table/column definitions
			$aopColumns = array("ProdLineCode","ProdLineDescription","Campaign","Gmargin","Gprofit","AOPNet","AOPUnits");
			$aopColumnTypes = array("varchar","varchar","varchar","decimal","decimal","decimal","integer"); // not yet used
			$rfColumns = array("Promo Code","Promo Description","Item Code","Item Description","PMG","Type","Units","Cost","Price","Eff Price","NSV","Gross Profit","Gross Margin");
			$rfColumnTypes = array("varchar","varchar","varchar","varchar","varchar","varchar","integer","decimal","decimal","decimal","decimal","decimal"); // not yet used
			$crfColumns = array("Item Code","DESCRIPTION");
			$crfColumnTypes = array("varchar","varchar"); // not yet used
			$crfUnits = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$crfCampaigns = array("","","","","","","","","","","","","","","","","","","","","","","","");
			$dcColumns = array("ItemCode","Item Description","DummyCost","StartDate","EndDate");
			$dcColumnTypes = array("varchar","varchar","decimal","datetime","datetime"); // not yet used
			$errmsg = "";
			$msgLog = "";
			$unuploadedLog = "";
			
			if (is_uploaded_file($_FILES['file']['tmp_name']))
			{
				try 
				{
					// cleanup our temp tables
					$sp->spLLDeleteTempTables($database);
					$row = 0;
					if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
						$num = 0;
						if ($errmsg==""){
					    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					    	// If we are processing row 1,
					    	// verify if the column names are what we expect
					    	if ($row == 0) {
								  if ($uType == 1) {
										$num = count($aopColumns);
									}
									else if ($uType == 2) {
										$num = count($rfColumns);
									}
									else if ($uType == 3) {
										$num = count($crfColumns);
									}
									else if ($uType == 4) {
										$num = count($dcColumns);
									}
									if ($uType != 3) {
											// for templates except for consolidated rolling forecasts,
											// column count is static.
											// if column count in source file does not match
								    	// expected number of columns, raise an error
								    	if (count($data)!=$num){
								    		$errmsg .=  "Number of columns in file is " . count($data) . ", expected $num.<br/>";
								    		break;
								    	} 
									}
									else {
											// for consolidated rolling forecasts,
											// template column count is dynamic and 
											// must contain at least one campaign 
											// and can contain up to 24 campaigns.
											// i.e (at least 3 cols (2 static + 1) 
											// up to 26 cols (2 static +24)
											if (count($data)<$num+1 || count($data)>$num+24 ){
								    		$errmsg .=  "Number of columns in file is " . count($data) . ", expected at least 3 up to 27 columns.<br/>";
								    		break;
								    	}
									}
						    	for ($c=0; $c < $num; $c++) {
						    		// echo "$data[$c] <br/>";
						    		if ($uType == 1) {
						        	if (trim($data[$c])!=$aopColumns[$c]){
						        		$errmsg .=  "Column \"" . $aopColumns[$c] . "\" is not present in column " . ($c + 1) . "<br/>";
						        		break;
						        	}
						    		}
						    		else if ($uType == 2) {
						        	if (trim($data[$c])!=$rfColumns[$c]){
						        		$errmsg .=  "Column \"" . $rfColumns[$c] . "\" is not present in column " . ($c + 1) . "<br/>";
						        		break;
						        	}
						    		}
						    		else if ($uType == 3) {
						        	if (trim($data[$c])!=$crfColumns[$c]){
						        		$errmsg .=  "Column \"" . $crfColumns[$c] . "\" is not present in column " . ($c + 1) . "<br/>";
						        		break;
						        	}
						    		}
						    		else if ($uType == 4) {
						        	if (trim($data[$c])!=$dcColumns[$c]){
						        		$errmsg .=  "Column \"" . $dcColumns[$c] . "\" is not present in column " . ($c + 1) . "<br/>";
						        		break;
						        	}
						    		}
					        }
					        // for consolidated rolling forecast
					        // check if dynamic column names are 
					        // valid campaigns. $c will not be reset
					        if ($uType == 3) {
					        	for (; $c < count($data); $c++) {
					        		$campaign = trim($data[$c]);
					        		$crfCampaigns[$c-$num] = $campaign; 
					        		if (!$sp->spLLCheckCampaignIfExisting($database,$campaign)){
					        			$errmsg .=  "Campaign \"" . $campaign . "\" is not present in the database.<br/>";
					        			break;
					        		}
					        	}
					        }
					        if ($errmsg!=""){
					        	break;
					        }
					        $row++;
					    	}
					    	else {
					    		
					    		// Continue processing the rest of the rows
					    		
					    		// echo "Processing row $row<br/>";
					    		if ($uType == 3) {
					    			$num = count($data);
					    		}
					        for ($c=0; $c < $num; $c++) {
					        	if ($uType == 1) {
						        	switch($c){
						        		case 0:
						        			$prodLineCode = str_replace("'", "''", $data[$c]);
						        			break;
						        		case 1:
						        			$prodLineDescription = str_replace("'", "''", $data[$c]);
						        			break;
						        		case 2:
						        			$campaign = $data[$c];
						        			break;
						        		case 3:
						        			$gmargin = str_replace('%', '', $data[$c]) / 100;
						        			break;
						        		case 4:
						        			$gprofit = str_replace(',', '', $data[$c]);
						        			break;
						        		case 5:
						        			$aopnet = str_replace(',', '', $data[$c]);
						        			break;
						        		case 6:
						        			$aopunits = str_replace(',', '', $data[$c]);
						        			break;
						        	}
					        	}
					        	else if ($uType == 2) {
						        	switch($c){
						        		case 0:
						        			$promoCode = $data[$c];
						        			break;
						        		case 1:
						        			// ignore column
						        			break;
						        		case 2:
						        			$prodLineCode = str_replace("'", "''", $data[$c]);
						        			break;
						        		case 3:
						        			$prodLineDescription = str_replace("'", "''", $data[$c]);
						        			break;
						        		case 4:
						        			// ignore column
						        			break;
						        		case 5:
						        			$type = str_replace("'", "''", $data[$c]);
						        			switch ($type){
						        				case 'B':
						        					$type="1";
						        					break;
						        				case 'E':
						        					$type="2";
						        					break;
						        				case 'B/E':
						        					$type="3";
						        					break;	
						        				default:
						        					$type="0";	
						        			}
						        			break;
					        			case 6:
						        			$rfunits = str_replace(',', '', $data[$c]);
						        			break;
						        	}
					        	}
					        	else if ($uType == 3) {
						        	switch($c){
						        		case 0:
						        			$prodLineCode = str_replace("'", "''", $data[$c]);
						        			$crfInsertQuery = "INSERT INTO tpi_tempconsolidatedrollingforecast(ProdLineCode";
						        			$crfInsertValues = "VALUES ('$prodLineCode'";
						        			break;
						        		case 1:
						        			$prodLineDescription = str_replace("'", "''", $data[$c]);
						        			$crfInsertQuery .= ", ProdLineDescription";
						        			$crfInsertValues .= ",'$prodLineDescription'";
						        			break;
						        		default:
						        			$rfunits = str_replace(',', '', $data[$c]);
						        			if (!is_numeric($rfunits)) {
						        				$rfunits = "0";
						        			} 
						        			$crfUnits[$c-count($crfColumns)] = $rfunits;
						        			$crfInsertQuery .= ", RFUnits" . ($c-count($crfColumns)+1);
						        			$crfInsertValues .= ", " . $crfUnits[$c-count($crfColumns)];
						        			break;
						        	}
					        	}
					        	else if ($uType == 4) {
						        	switch($c){
						        		case 0:
						        			$prodLineCode = str_replace("'", "''", $data[$c]);
						        			break;
						        		case 1:
						        			$prodLineDescription = str_replace("'", "''", $data[$c]);
						        			break;
						        		case 2:
						        			$dummyCost = str_replace(',', '', $data[$c]);
						        			break;
						        		case 3:
						        			$startDate = date("Y-m-d H:i:s",strtotime($data[$c]));
						        			break;
						        		case 4:
						        			if (!empty($data[$c])){
						        				$endDate = date("Y-m-d H:i:s",strtotime($data[$c]));
						        			}
						        			else {
						        				$endDate = "";
						        			}
						        			break;
						        	}
					        	}
				        	}
					        try {
					        	// Insert the row data into our temp table
					        	if ($uType == 1) {
					        		$sp->spLLInsertTempAOP($database,$prodLineCode,$prodLineDescription,$campaign,$gmargin,$gprofit,$aopnet,$aopunits);
					        	}
					        	else if ($uType == 2) {
					        		$sp->spLLInsertTempRollingForecast($database,$prodLineCode,$prodLineDescription,$campaignID,$promoCode,$rfunits,$type);
					        	}
					          else if ($uType == 3) {
					        		//$sp->spLLInsertTempConsolidatedRollingForecast($database,$prodLineCode,$prodLineDescription,$campaign,$rfunits);
					        		$crfInsertQuery .= ") $crfInsertValues)";
					        		$database->execute($crfInsertQuery);
					        	}
					        	else if ($uType == 4) {
					        		$sp->spLLInsertTempDummyCost($database,$prodLineCode,$prodLineDescription,$dummyCost,$startDate,$endDate);
					        	}
					        }
					        catch (Exception $e){
					        	$errmsg .= "Unable to process row " . ($row+1) . " Please check the source data file for errors.<br/>";
					        }
					        $row++;	
					    	}
					    }
						}
				    // redirect_to("../index.php?pageid=135&msg=" . urlencode("Successfully uploaded file"));
				    // if there are no error messages,
				    // continue processing 
				    if ($errmsg=="")
				    {
				    	// do some processing
				    	// and transfer rows from temp to main table
				    	
				    	// $database->beginTransaction();
				    	
				    	$rs = null;
				    	if ($uType == 1) {
					    	$sp->spLLUploadAOP($database);
					    	
					    	// get uploaded stats
					    	$rs = $sp->spLLGetUploadedAOPCount($database);
				    	}
				    	else if ($uType == 2) {
					    	$sp->spLLValidateRollingForecast($database);
					    	
				    	  $rs = $sp->spLLCheckDuplicateTempRollingForecast($database);
					    	while ($row = $rs->fetch_object())
					    	{
					    		$errmsg .= "Product Code $row->ProdLineCode and Promo Code $row->PromoCode and Type $row->Type has $row->a entries int the source file.<br/>";
					    	}
					    	
					    	if ($errmsg!="")
					    	{
					    		throw new Exception($errmsg);
					    	}
					    	
					    	// upload the data
					    	$rs = $sp->spLLUploadRollingForecast($database);
					    	
					    	// get uploaded stats
					    	$rs = $sp->spLLGetUploadedRollingForecastCount($database);
				    	}
				    	else if ($uType == 3) {
					    	// $sp->spLLUploadConsolidatedRollingForecast($database);
					    	$sp->spLLValidateTempConsolidatedRollingForecast($database);
					    	
					    	// check for duplicate entries
					    	$rs = $sp->spLLCheckDuplicateTempConsolidatedRollingForecast($database);
					    	while ($row = $rs->fetch_object())
					    	{
					    		$errmsg .= "Product Code $row->ProdLineCode has $row->a entries int the source file.<br/>";
					    	}
					    	
					    	if ($errmsg!="")
					    	{
					    		throw new Exception($errmsg);
					    	}
					    	for ($c=0; $c < count($crfCampaigns); $c++) {
					    		$sp->spLLValidateAndUploadTempConsolidatedRollingForecast2($database,$crfCampaigns[$c],"RFUnits" . ($c+1));
					    	}
					    	$sp->spLLMarkUploadedConsolidatedRollingForecastAsUploaded($database);
					    	// get uploaded stats
					    	$rs = $sp->spLLGetUploadedConsolidatedRollingForecastCount($database);
				    	}
				    	else if ($uType == 4) {
					    	$sp->spLLUploadDummyCost($database);
					    	
					    	// get uploaded stats
					    	$rs = $sp->spLLGetUploadedDummyCostCount($database);
				    	}
				    	$row = $rs->fetch_object();
				    	
				    	$total = $row->Total;
				    	$unuploaded = $row->Unuploaded;
				    	$uploaded = $row->Uploaded;
				    	
				    	$rs->close();
				    	$msgLog = "Total row(s): $total<br/>Total uploaded row(s): $uploaded<br/>Total unuploaded row(s): $unuploaded<br/>";
					    	
				    	if ($unuploaded!=0)
				    	{
				    		if ($uType == 1) {
				    			$rs = $sp->spLLGetUnuploadedAOPRows($database);
				    		}
				    		else if ($uType == 2) {
				    			$rs = $sp->spLLGetUnuploadedRollingForecastRows($database);
				    		}
				    	  else if ($uType == 3) {
				    			$rs = $sp->spLLGetUnuploadedConsolidatedRollingForecastRows($database);
				    		}
				    		else if ($uType == 4) {
				    			$rs = $sp->spLLGetUnuploadedDummyCostRows($database);
				    		}
				    		while ($row = $rs->fetch_object()) {
				    			$unuploadedLog .= "Row $row->ID: $row->Remarks<br/>";
				    		}
				    	}
				    } 
				    fclose($handle);
					}
					else {
						$errmsg .= "Cannot open uploaded file. Please try again.<br/>";
					}
					$database->commitTransaction();
				} 
				catch (Exception $e) 
				{
					$database->rollbackTransaction();
					$errmsg = $e->getMessage()."\n";
					
					//$errmsg = "Invalid Interface file.";
					//redirect_to("../index.php?pageid=135&errmsg=$errmsg");
				}
			}			
		}
	}
	else 
	{
		$errmsg .= "Cannot open uploaded file. Please try again.<br/>";
	}
	
	// If we encountered any errors,
	// this is the place to show/return them
	if (isset($_POST['cboUploadType']))
	{
		$uType = addslashes($_POST['cboUploadType']);		
	}
	else
	{
		$uType = 0;
	}
	
	if ($errmsg != "")
	{
    	//echo $errmsg;
    	if ($uType > 4)
    	{
    		$_SESSION['ll_message_log'] = $msgLog;    		
    	}
    	
    	$_SESSION['ll_uploader_error'] = $errmsg;
    	redirect_to("../index.php?pageid=135&err="); // . urlencode("Errors were encountered during uploading"));
  	}
  	else 
  	{
  		// echo $msgLog;
	  	if ($unuploadedLog != "")
	  	{
	  		// echo $unuploadedLog;
	  		$msg = "Successfully uploaded file but warnings were generated. Please see the log for details";
	  		$_SESSION['ll_message_log'] = $msgLog . $unuploadedLog;
	  	}	
	  	else 
	  	{
	  		if ($uType <= 4)
	  		{
	  			$msg = "Successfully uploaded file";
	  		}
	  		$_SESSION['ll_message_log'] = $msgLog;
	  	}
	  	redirect_to("../index.php?pageid=135&msg=" . urlencode($msg));
  	}
?>