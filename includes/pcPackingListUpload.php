<?php
/*
MODIFIED BY: GINO LEABRES
DATE 1/16/2013
OPTIMIZED CODE
*/
	ini_set('display_errors', '1');
	require_once("../initialize.php");
	global $database;
	
	function add_rhodetails($tpi_tmprhoid, $plistrefno, $lcounter, $pcode, $qty, $desc){
		global $data;							  
		$data []= array( 'tpi_tmprhoID' => $tpi_tmprhoid,
						 'PickListRefNo' => $plistrefno,
						 'LineCounter' => $lcounter,
						 'ProductCode' => $pcode,
						 'Quantity' => $qty,
						 'Description' => $desc
					  );
	}
	
	function add_stadetails($tpi_tmpstaid, $stano, $lcounter, $pcode, $desc, $qty){
		global $data;				  
		$data []= array( 'tpi_tmpstaID' => $tpi_tmpstaid,
						  'STANo' => $stano,
						  'LineCounter' => $lcounter,
						  'ProductCode' => $pcode,				
						  'Description' => $desc,
						  'Quantity' => $qty
					   );
	}
			
	
	
	
	if(isset($_POST['btnUpload'])){
		
		$path_Info = $_FILES['file']['name'];
		$ext = pathinfo($path_Info);
		$file_Size = $_FILES['file']['size'];	 
		$uType = addslashes($_POST['cboUploadType']);
		$uploadErr = "";
		
		if($path_Info == ""){
	 		$errormessage = "Please select a file to upload.";
			redirect_to("../index.php?pageid=56&errmsg=$errormessage"); 
	 	}


		$data = array();
		try{
			$database->beginTransaction();
			$rs_DeleteTmpUpload = $sp->spDeleteTmpUpload($database);
			$database->commitTransaction();	
		}
		catch(Exception $e){
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=56&errmsg=$errmsg");
		}
		 
	
		
		if (is_uploaded_file($_FILES['file']['tmp_name'])){
			$fileData = trim(file_get_contents($_FILES['file']['tmp_name']));
			
			//echo $fileData;
			$isrho = true;
			$rows = explode("\n", $fileData);

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
			$productType = "";
			$cost = "";
			$lastPODate = "";
			
			//for misc document number.
			
			$CE_Code		= "";
			$CE_Description = "";
			$ce_counter = 0;
			try 
			{
				//here code for inventory document number;
				if($uType == 4){
					foreach ($rows as $row){
							$xdata = explode(',',trim($row));
							$CE_Code = trim($xdata[0]);
							$CE_Description = trim($xdata[1]);
							$datastart = date("Y-m-d", strtotime($xdata[2]));
							$dataend = date("Y-m-d", strtotime($xdata[3]));
							$checker = $database->execute("select * from tpi_document where CE_Code=TRIM('".$CE_Code."')");
							if($checker->num_rows){ //if exist
														$database->execute("update tpi_document set CE_Description='".$CE_Description."',Effectivity_Date_Start = '".$datastart."', 
										Effectivity_Date_End = '".$dataend."', Changed = 1 where CE_Code='".$CE_Code."'");
							}else{ // not exist
														$database->execute("INSERT INTO tpi_document (CE_Code, CE_Description, Effectivity_Date_Start, Effectivity_Date_End)
																	Values ('".$CE_Code."', '".$CE_Description."', '".$datastart."', '".$dataend."')");
							}
							$ce_counter++;
					}
                                        
							$message = "Successfully Uploaded CE-Charge Account File";
							$msg_log .= "<br><br><br>";
							$msg_log .= "Total Rows In File: ". $ce_counter."<br>";
							$msg_log .= "Total Rows Uploaded: ". $ce_counter."<br>";
							$msg_log .= "Total Rows Not Uploaded: 0<br><br><br>";
							$_SESSION['msg_log'] = $msg_log; 	
							$msglog2 = "1";
							//Logs here..
							$LogType        = "CE-Charge Account";
							$Description    = $msg_log;
							$xDate			= "NOW()";
							
							// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")"); 
							$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
							$database->commitTransaction();
							redirect_to("../index.php?pageid=56&msg=$message&msglog=$msglog2");
                            die();
				}
				
				
				foreach ($rows as $row){
					if ($uType != 3){
						$rdata = csvstring_to_array(trim($row), ' ', '"', '\r\n');	
					}
					else
					{
						$rdata = csvstring_to_array(trim($row), ',', '"', '\r\n');							
					}
				
				  	if($first_row && ($uType != 3)){
					//header
	                    if($uType == 1){
					//if RHO file						
							$h1 = trim($rdata[0]);
							$h2 = strtoupper(trim($rdata[1]));
							$h3 = trim(date('Y-m-d',strtotime($rdata[2])));
							$h4 = trim(date('Y-m-d',strtotime($rdata[3])));
							$h5 = trim($rdata[4]);
							$h6 = trim($rdata[5]);
							
							$rsBranchCheck = $sp->spCheckBranchCode($database, $h2);
						    if (!$rsBranchCheck->num_rows){
						    	$uploadErr = "Invalid Branch Code $h2";
						    	throw new Exception("$uploadErr");
						    }
						    
							//$rsRHOCheck = $sp->spCheckDocInventoryinout($database, $h1);
							$rsRHOCheck = $sp->spCheckDocInventoryinout($database,$h1,$h2);
						    if ($rsRHOCheck->num_rows){
						    	$uploadErr = "RHO Document Number $h1 already exists";
						    	throw new Exception("$uploadErr");
						    }
	
						    $isrho = true; 
							$rs_insertTmpRHO = $sp->spInsertTmpRHO($database,$h1,$h2,$h3,$h4,$h5,$h6,0);
							//echo 'Successfully inserted to tmpRho ,';
							if($rs_insertTmpRHO->num_rows){
						      	while($row = $rs_insertTmpRHO->fetch_object()){										 						 			
						 	       $rhoid = $row->ID;							 	
						      	}
					        }
						}else if ($uType == 2)
						{
								//if STA file						
								$h1 = trim($rdata[0]);
								$h2 = trim($rdata[1]);
								$h3 = strtoupper(trim($rdata[2]));
								$h4 = trim(date('Y-m-d',strtotime($rdata[3])));
								$h5 = strtoupper(trim($rdata[4]));
								$h6 = trim($rdata[5]);
								$isrho = false;
								
								$rsBranchCheck = $sp->spCheckBranchCode($database, $h3);
								if (!$rsBranchCheck->num_rows){
									$uploadErr = "Invalid Branch Code $h5";
									throw new Exception("$uploadErr");
								}
								
								$rsBranchCheck = $sp->spCheckBranchCode($database, $h5);
								if (!$rsBranchCheck->num_rows){
									$uploadErr = "Invalid Branch Code $h5";
									throw new Exception("$uploadErr");
								}
								
								$rsSTACheck = $sp->spCheckDocInventoryinout_STA($database, $h2,$h1,$h3,$h5);
								if ($rsSTACheck->num_rows){
									$uploadErr = "STA Document Number $h2 already exists";
									throw new Exception("$uploadErr");
								}
								
								// $rsSTACheck = $sp->spCheckDocInventoryinout($database, $h2,$h1);
								// if ($rsSTACheck->num_rows){
									// $uploadErr = "STA Document Number $h2 already exists";
									// throw new Exception("$uploadErr");
								// }
								
								
								
								if ($h1 == "ITR" || $h1 == "IRV" || $h1 == "RTR"){
									$rs_insertTmpSTA = $sp->spInsertTmpSTA($database,$h1,$h2,$h3,$h6,$h4,$h5);
								}
								else{
									throw new Exception("Invalid STA File");
								}
								if($rs_insertTmpSTA->num_rows){
									while($row = $rs_insertTmpSTA->fetch_object()){										 						 			
										$staid = $row->ID;						   
									}
								}   
						}
					}	 				
	               	else{
						//details					
						if ($uType == 3){
							// Product Master
							/*
							*********************************
							**  Modified by: Gino C. Leabres***
							**  11.26.2012*********************
							**  ginophp@yahoo.com**************
							***********************************
							*/
							
							
							$prodCode = trim($rdata[0]);
							$prodName = trim(str_replace("'", "\'",($rdata[1])));
							$uom = trim(($rdata[2]));
							$prodLine = trim($rdata[3]);
							$csp = trim(str_replace(",", "", ($rdata[4])));
							$pmgCode = trim($rdata[5]);
							$pmStatus = trim($rdata[6]);
							$itemType = trim($rdata[7]);
							$brandold = trim($rdata[8]);
							$formold = trim($rdata[9]);
							$lastReceiptDate = trim($rdata[10]);
							$lastReceiptDate = trim(date('Y-m-d',$lastReceiptDate));
							$productType = trim($rdata[11]);
							$cost = trim(str_replace(",", "", $rdata[12]));
							$lastPODate = trim(date('Y-m-d',strtotime(trim($rdata[13]))));
							$prodCode_2nd = trim($rdata[14]);
							$prod_category = trim($rdata[15]);
							#$stockingtype = trim($rdata[16]);
							$linetype = trim($rdata[16]);
							$brand = trim($rdata[18]);
							$subbrand = trim($rdata[19]);
							$form = trim($rdata[20]); 
							$subform = trim($rdata[21]);
							$size = trim($rdata[22]);
							#$mt = trim($rdata[18]);							
	
	
							//$Uploader_rows = $sp->spInsertTmpProdMaster($database, trim($prodCode), $prodName, $uom, $prodLine, $csp, $pmgCode, $pmStatus, $itemType, $brand, $form, $lastReceiptDate, $productType, $cost, $lastPODate);
							
							$Uploader_rows = $sp->spInsertTmpProdMaster($database, trim($prodCode), $prodName, $uom, $prodLine, $csp, $pmgCode, $pmStatus, $itemType, $brandold, $formold, $lastReceiptDate, $productType, $cost, $lastPODate, $prodCode_2nd, $prod_category, $stockingtype, $linetype, $mt, $brand, $subbrand, $form, $subform, $size );
							
							
						}
						else if ($uType == 5)
						{
						  $prodCode = $rdata[0];
						  $csp = $rdata[6];
						  $pmg = $rdata[9];
						  $ud_cnt = $ud_cnt + 1;

						  if ($csp != 0  && $csp != '?' )
						  {
						     $cd_cnt = $cd_cnt + 1;
 						     $prdpricingupdate = $database->execute("UPDATE productpricing pp 
							                                         INNER JOIN product p ON  pp.ProductID  = p.id 
																	 set pp.UnitPrice = ".$csp." 
																	 where p.code = '".$prodCode."' 
																	 and pp.unitprice !=  '".$csp."'
				                                                    ");
							// if($prdpricingupdate->num_rows != 0){
							//	$cd_cnt2 = $cd_cnt2 + 1;	
							// }
                            								 
                          }

						  $checkpmg = $database->execute(" select * from tpi_pmg where `code` = '$pmg' ");	

						  if($checkpmg->num_rows)
						  {
								while($pmgc = $checkpmg->fetch_object())
								{
									$pmgid = $pmgc->ID;			
								    $database->execute("UPDATE productpricing pp 
														INNER JOIN product p ON  pp.ProductID  = p.id 
														 set pp.pmgid = ".$pmgid." 
														 where p.code = '".$prodCode."' 
														 #and pp.pmgid !=  '".$pmgid."'
												       ");
								}
						   }						  
						}
							
				    	else if ($isrho){
							//is RHO						
						 	$tpi_tmprhoid = 0;
							$plistrefno = $rdata[0];
							$lcounter = $rdata[1];
							$prodcode = $rdata[2];
							$qty = $rdata[3];
							$desc = $rdata[4];
							
							$existingproduct = $database->execute("SELECT ID FROM product WHERE TRIM(`Code`) = '".TRIM($prodcode)."'");
							if($existingproduct->num_rows == 0){
								throw new Exception("Product ".TRIM($prodcode)." doesn't exist in Product Masterlist.");
							}
							
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
							
							$existingproduct = $database->execute("SELECT ID FROM product WHERE TRIM(`Code`) = '".TRIM($prodcode)."'");
							if($existingproduct->num_rows == 0){
								throw new Exception("Product ".TRIM($prodcode)." doesn't exist in Product Masterlist.");
							}
							
							add_stadetails($staid, $stano, $lcounter, $prodcode, $desc, $qty);				     
					  	}
				   	}
				 	$first_row = false;
			 	}
			
				$ctr2 = 0;
				$chckEmpty = 0;
				$val = 0;
			
				if ($uType == 3){
					/*
						Modified by: Gino C. Leabres
						Date: 1/16/2013
					*/
					$sp->spUpdateInsertTmpProdMasterv1($database);
					$database->commitTransaction();
					$up_prodmaster = $sp->spTotalCountUploadedTmpProdMaster($database);
					$cr_prodmaster = $sp->spTotalCountCreatedTmpProdMaster($database);
					$cd_cnt = $cr_prodmaster->num_rows;
					$ud_cnt = $up_prodmaster->num_rows;
					$not_uploaded = $ud_cnt - $cd_cnt;
					/*LOGS DATA NOT UPLOAD*/
					if ($not_uploaded > 0){
						$nup_prodmaster = $sp->spTotalCountNotCreatedTmpProdMaster($database);
						if($nup_prodmaster->num_rows){
							while($nup_row = $nup_prodmaster->fetch_object()){
								$msg_log .= "Product Code - ".$nup_row->ItemCode." was not created."."<br />"; 							 							
							}
							$nup_prodmaster->close();
						
						 }
					}
					
					if ($cd_cnt > 0){
						$message = "Successfully Uploaded Product Master.";						
					}
					$msg_log .= "<br /><br /><br />";
					$msg_log .= "Total Rows In File: ". $ud_cnt."<br>";
					$msg_log .= "Total Rows Uploaded: ". $cd_cnt."<br>";
					$msg_log .= "Total Rows Not Uploaded: ". $not_uploaded."<br /><br /><br />";
					
					
					//Logs here..
					$LogType        = "Product Master";
					$Description    = $msg_log;
					$xDate			= "NOW()";
					
					// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")");
					$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
					
				}elseif($uType == 5){
				
					 if ($cd_cnt == 0){
						$message = "There are no products to be uploaded.";						
					  }else{
					 	$message = "Successfully Updated Product Pricing";						
					}
					
					$msg_log .= "<br><br><br>";
					$msg_log .= "Total Rows In File: ". $ud_cnt."<br>";
					$msg_log .= "Total Rows Uploaded: ". $ud_cnt."<br>";
					
					
				}elseif($isrho){
				//For RHO
					foreach( $data as $row ){
						$ctr2 += 1;
						if(trim($row['tpi_tmprhoID'])== ""){
							$chckEmpty = 1;
						}else if(trim($row['PickListRefNo'])== ""){
							$chckEmpty = 1;
						}else if(trim($row['LineCounter'])== ""){
							$chckEmpty = 1;
						}else if(trim($row['ProductCode'])== ""){
							$chckEmpty = 1;
						}else if(trim($row['Quantity'])== ""){
							$chckEmpty = 1;
						}else if(trim($row['Description'])== ""){
							$chckEmpty = 1;
						}
						if($chckEmpty == 0){
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
					
					$uploaded_details_rows = $sp->spTotalCountUploadedTmpRHODetails($database);
					if($uploaded_details_rows->num_rows){
						while($ud_row = $uploaded_details_rows->fetch_object()){
							$ud_cnt = $ud_row->cnt; 							
						}
						$uploaded_details_rows->close();
					}
					
					$created_details_rows = $sp->spTotalCountCreatedTmpRHODetails($database);
					if($created_details_rows->num_rows){
						while($cd_row = $created_details_rows->fetch_object()){
							$cd_cnt = $cd_row->cnt; 							
						}
						$created_details_rows->close();
					}
					
					$not_uploaded = $ud_cnt - $cd_cnt;
					
					$notcreated_details_rows = $sp->spRecordsNotCreatedTmpRHODetails($database);
					if($notcreated_details_rows->num_rows){
						while($ncd_row = $notcreated_details_rows->fetch_object()){
							$msg_log .= "Product Code - ".$ncd_row->ProductCode." does not exist in Product master. <br>"; 							
						}
						$notcreated_details_rows->close();
					}
					
					if ($cd_cnt == 0){
						$message = "There are no products to be uploaded.";						
					}else{
						$sp->spInsertFromTmpRHO($database);
						$database->commitTransaction();
						$message = "Successfully Uploaded RHO File";						
					}
					
					$msg_log .= "<br><br><br>";
					$msg_log .= "Total Rows In File: ". $ud_cnt."<br>";
					$msg_log .= "Total Rows Uploaded: ". $cd_cnt."<br>";
					$msg_log .= "Total Rows Not Uploaded: ". $not_uploaded."<br><br><br>";
					
					//Logs here..
					$LogType        = "RHO";
					$Description    = $msg_log;
					$xDate			= "NOW()";
					
					// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")");
					$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
					
				
				}else{
				//for STA
					foreach( $data as $row ){
						$ctr2 += 1;
						if(trim($row['tpi_tmpstaID'])== ""){
							$chckEmpty = 1;
						}
						else if(trim($row['STANo'])== ""){
							$chckEmpty = 1;
						}
						else if(trim($row['LineCounter'])== ""){
							$chckEmpty = 1;
						}
						else if(trim($row['ProductCode'])== ""){
							$chckEmpty = 1;
						}
						else if(trim($row['Description'])== ""){
							$chckEmpty = 1;
						}else if(trim($row['Quantity'])== ""){
							$chckEmpty = 1;
						}		 

						if($chckEmpty == 0){
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
					$uploaded_details_rows = $sp->spTotalCountUploadedTmpSTADetails($database);
					if($uploaded_details_rows->num_rows){
						while($ud_row = $uploaded_details_rows->fetch_object()){
							$ud_cnt = $ud_row->cnt; 							
						}
						$uploaded_details_rows->close();
					}
					
					$created_details_rows = $sp->spTotalCountCreatedTmpSTADetails($database);
					if($created_details_rows->num_rows){
						while($cd_row = $created_details_rows->fetch_object()){
							$cd_cnt = $cd_row->cnt; 							
						}
						$created_details_rows->close();
					}
					
					$not_uploaded = $ud_cnt - $cd_cnt;
					
					$notcreated_details_rows = $sp->spRecordsNotCreatedTmpSTADetails($database);
					if($notcreated_details_rows->num_rows){
						while($ncd_row = $notcreated_details_rows->fetch_object()){
							$msg_log .= "Product Code - ".$ncd_row->ProductCode." does not exist in Product master. <br>"; 							
						}
						$notcreated_details_rows->close();
					}
					
					if ($cd_cnt == 0){
						$message = "There are no products to be uploaded.";						
					}else{
						$sp->spInsertFromTmpSTA($database);
						$database->commitTransaction();
						$message = "Successfully Uploaded STA File";
					}
					
					$msg_log .= "<br><br><br>";
					$msg_log .= "Total Rows In File: ". $ud_cnt."<br>";
					$msg_log .= "Total Rows Uploaded: ". $cd_cnt."<br>";
					$msg_log .= "Total Rows Not Uploadeed: ". $not_uploaded."<br><br><br>";
					
					$LogType   		= "STA";
					$Description    = $msg_log;
					$xDate			= "NOW()";
					
					// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")");
					$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
					
					
				}
			} 
			catch (Exception $e) 
			{
				$database->rollbackTransaction();
				$errmsg = $e->getMessage()."\n";
				
				redirect_to("../index.php?pageid=56&errmsg=$errmsg");
			}
		}
		
		$ctr = 0;
		$_SESSION['msg_log'] = $msg_log; 	
		$msglog2 = "1";
		redirect_to("../index.php?pageid=56&msg=$message&msglog=$msglog2");
	}
?>