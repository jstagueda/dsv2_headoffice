

<?php

	include "../../../initialize.php";
	
	include IN_PATH.DS."scpriceuploading.php";
	global $database;
	global $session;
	$userid = $session->user_id;
	
								
$headerdetail =	'<table 	width="100%" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;
								border-left:none ; border-right:none; border-bottom:none ;  " >
								<tr class="trheader" >
									<td width="1%">Line Number</td>
									<td width="8%">Item Code</td>
									<td width="20%" align="left" >Item Description</td>
									<td width="3%">Base Price</td>
								</tr> ';								
								
$footer = '</table>';								

								
if(!($_FILES['file']['size']==0))
{
		if($_FILE['file']['error'] == 0)  
		{

		$filetmp = $_FILES['file']['tmp_name'];
		$filename = $_FILES['file']['name'];
		$filename_withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$filefordb = trim($filename_withoutExt);
		$chechfname = validateDate($filename_withoutExt);
		$errmsg='';
		$goterr=0;
		$dateforupd = date("Y-m-d H:i:s");
		$dateforupd = date("Ymd Hi");
		$filenametodate=str_replace("_"," ",$filename_withoutExt);

		$database->execute(" CREATE TEMPORARY TABLE IF NOT EXISTS tmpbaseprice
										 (
										   `ID` INT(10) NOT NULL AUTO_INCREMENT, 	
										   `Code` VARCHAR(50) NULL ,
										   `Description` VARCHAR(50) NULL ,
										   `Baseprice` DECIMAL(14,4) NOT NULL DEFAULT 0.00,
										   `Datefrom` DATE,
										   `Dateto` DATE,
										   `Datedifference` int(10) NULL,
											PRIMARY KEY (`ID`)  
										 )");		
		

		$database->execute(" CREATE TEMPORARY TABLE IF NOT EXISTS tmpbaseprice1
										 (
										   `ID` INT(10) NOT NULL AUTO_INCREMENT, 	
										   `Code` VARCHAR(50) NULL ,
										   `Description` VARCHAR(50) NULL ,
										   `Baseprice` DECIMAL(14,4) NOT NULL DEFAULT 0.00,
										   `Datefrom` DATE,
										   `Dateto` DATE,
										   `Datedifference` int(10) NULL,
											PRIMARY KEY (`ID`)  
										 )");		
		
			try
			{
				$database->beginTransaction();
	
				$chkfnameindb = $database->execute("SELECT * FROM uploadhistory WHERE filename ='$filefordb' and `status`='SUCCESSFUL' ");
			
				if ($chkfnameindb->num_rows) 
				{

					$rectype = 'H';
					$remarks =  "File already exists.";
					throw new Exception("Cannot continue with transaction. File already exists.");
				
				}

				if(!$chechfname)
				{
					$rectype = 'H';
					$remarks =  "Invalid file format.";
					throw new Exception("Cannot continue with the transaction. Invalid file format.");
				}
			
				if($ext!='rpf')
				{
					$rectype = 'H';
					$remarks =  "Invalid file extension: $ext  .";
					throw new Exception("Cannot continue with the transaction. Invalid file extension: $ext  .");
				}
				
				$lastupddate = $database->execute("SELECT `LastUploadDate` FROM productpricing ORDER BY lastuploaddate DESC LIMIT 1")->fetch_object()->LastUploadDate;
				
				$filenametodate=date("Ymd Hi", strtotime($filenametodate));
				$lastupddate= date('Ymd Hi', strtotime($lastupddate));
				$timea=strtotime($lastupddate); 
				$timeb=strtotime($filenametodate);
				$dateforsaving=date("Y-m-d H:i:s", strtotime($filenametodate));
			
				if($timeb<$timea)
				{
					$rectype = 'H';
					$remarks =  "Date of file is older than the latest file uploaded";
					throw new Exception("Cannot continue with the transaction. Date of file is older than the latest file uploaded.");					
				}
				

				$fileData = trim(file_get_contents($filetmp));
				$rows = explode("\n", $fileData);
				$prdnotindb='';
				$prdnotindbctr=0;
				$reccounter=0;
				$linecounter = 1;
				$linecounter_buffer ='';
				foreach ($rows as $row)
				{
					$rdata = csvstring_to_array(trim($row), ',', '"', '\r\n');	
					$pcode = trim($rdata[0]);
					$pdesc = trim($rdata[1]);
					$uprice = trim($rdata[2]);
					$datef = trim($rdata[3]);
					$datet = trim($rdata[4]);
					$unitprice  = number_format($uprice, 4, '.', '');

					$datef = date("Y-m-d", strtotime($datef));
					$datet = date("Y-m-d", strtotime($datet));
			
					$addrec = $database->execute(" INSERT INTO tmpbaseprice 
													(`code`,`description`,`baseprice`,`datefrom`,`dateto`) 
													values('$pcode','$pdesc',$unitprice,'$datef','$datet')");
														
				}
				
				$checktmpprice = $database->execute("SELECT * FROM tmpbaseprice limit 1");
				
				if($checktmpprice->num_rows)
				{
					$upddatediff = $database->execute("UPDATE tmpbaseprice
														SET Datedifference=(DATEDIFF(dateto,datefrom))");	

					$upddatediff = $database->execute("INSERT INTO tmpbaseprice1 (`id`,`code`,`description`,`baseprice`,`datefrom`,`dateto`,`Datedifference`) 
														SELECT `id`,`code`,`description`,`baseprice`,`datefrom`,`dateto`,`Datedifference` 
														FROM tmpbaseprice");	
				}
				
				$getpricefrtmp = $database->execute("SELECT 
														tbp.id id,
														tbp.`code` pcode,
														tbp.`description` pname,
														tbp.`baseprice` baseprice,
														tbp.`datefrom` datefrom,
														tbp.`dateto` dateto,
														tbp.`Datedifference` Datedifference

														 FROM tmpbaseprice tbp
														 #INNER JOIN tmpbaseprice1 tbp1 ON tbp1.id=tbp.id
														WHERE tbp.id=(SELECT id FROM tmpbaseprice1 WHERE `code`=tbp.code AND DATE_FORMAT(NOW(),'%Y-%m-%d') BETWEEN (datefrom) AND (dateto)
														ORDER BY Datedifference LIMIT 1 )
														GROUP BY tbp.code");
				
				if($getpricefrtmp->num_rows)
				{
														
					while($r=$getpricefrtmp->fetch_object())
					{	
						$tmp_pcode=$r->pcode;
						$tmp_pname=$r->pname;
						$tmp_baseprice=$r->baseprice;
						$tmp_baseprice  = number_format($tmp_baseprice, 4, '.', '');
						$tmp_datefrom=$r->datefrom;
						$tmp_dateto=$r->dateto;
						
			
						$prdid = $database->execute("SELECT id FROM product WHERE `code`='$tmp_pcode'")->fetch_object()->id;
						
						if($prdid)
						{
						
							$checkproduct = $database->execute("SELECT unitprice FROM productpricing WHERE productid=$prdid");
							
							if ($checkproduct->num_rows) 
								{
							
																		
									while($oldpr = $checkproduct->fetch_object())
									{
										$oldprice = $oldpr->unitprice;			
									}
									
									$oldprice = number_format($oldprice, 4, '.', '');
									if( $unitprice <> $oldprice )
									{	
										$updrec = $database->execute(" UPDATE productpricing
																				SET `unitprice` = $tmp_baseprice,
																					`lastuploaddate` ='$dateforsaving'
																				WHERE productid = $prdid");								
										if(!$updrec)
										{
											$linecounter_buffer.=$linecounter;
											$rectype = 'D';
											throw new Exception("Cannot update product. ");
										}
										else
										{
											$reccounter++;
											
										}		
										
									}
			
								}
								else
								{
								
									$addrec = $database->execute(" INSERT INTO productpricing 
																		(`productid`,`unittypeid`,`unitprice`,`pmgid`,`enrollmentdate`,`lastuploaddate`) 
																		SELECT '$prdid',1,'$tmp_baseprice' ,1,NOW(), '$dateforsaving'
																		FROM productpricing WHERE NOT EXISTS
																		(SELECT * FROM productpricing WHERE productid=$prdid)
																		LIMIT 1	 ");

									if(!$addrec)
									{
										$linecounter_buffer.=$linecounter.',';
										$rectype = 'D';
										throw new Exception("Cannot add product.");
									}
									else
									{
										$reccounter++;
										
									}
																				
								}
							
									$addrec3344 = $database->execute("  SELECT * FROM productpricinghistory
																	 WHERE productid=$prdid
																	 AND regularprice='$tmp_baseprice'
																	 AND DATE(EffectivityDateFrom)='$tmp_datefrom'
																	 AND DATE(EffectivityDateTo)='$tmp_dateto' ");

									if(!$addrec3344->num_rows) 
									{
										$database->execute(" INSERT INTO productpricinghistory( `ProductID`,`RegularPrice`,`EffectivityDateFrom`,`EffectivityDateTo`,`EnrollmentDate` )
												VALUES ( $prdid ,'$tmp_baseprice','$tmp_datefrom','$tmp_dateto',NOW())");
									}

						}
						else
						{

							$prdnotindb .= '<b>'.$pcode.'</b>-'.$pdesc.'<br>';
							$prdnotindbctr++;
							$linecounter_buffer.=$linecounter.',';
							
						}
						
					$linecounter++;	


					}
				}				


				$linecounter_buffer = rtrim($linecounter_buffer,',');
				$remarks =  "";
				$recordtype = "";


				if($reccounter!=0)
				{	
					if($prdnotindb !='')
					{
						$prdnotindb = "<b> Successfully uploaded file.</b><br> <br>
										<b>Item(s) listed below are not in the product master list.</b> <br>".$prdnotindb;
						$result['ErrorMessage'] = $prdnotindb;
						$remarks = "Successfully uploaded file but some items are not in the masterlist.";
						$recordtype = 'D';
					}
					else
					{
						$result['ErrorMessage'] = 'Successfully uploaded file';
						$remarks =  "Successfully uploaded file";
						$recordtype = 'H';
					}
				}
				else
				{
					
					if($prdnotindb !='')
					{
						$prdnotindb = "<b> No Record was updated.</b><br> <br>
										<b>Item(s) listed below are not in the product master list.</b> <br>".$prdnotindb;
						$result['ErrorMessage'] = $prdnotindb;
						$remarks =  "No Record was updated.  Some items are not in the masterlist.";
						$rectype = 'D';
						throw new Exception($prdnotindb);
						
					}
					else
					{
						$result['ErrorMessage'] = 'No Record was updated.';
						$remarks =  "No Record was updated.";
						$recordtype = 'H';						
						throw new Exception('No Record was updated.');						
					}				
			
				}


				$addlog = $database->execute(" INSERT INTO uploadhistory (`Filename`,`Code`,`SubCode`,`UserID`,`Remarks`,`RecordType`,`LineNo`,`Status`,`EnrollmentDate`)
											VALUES('$filefordb','UPLOADING','PRICEFILE', $userid, '$remarks','$recordtype','$linecounter_buffer' ,'SUCCESSFUL', NOW())");
				
				
				if(!$addlog)
				{
					throw new Exception("Cannot log transaction.");
				}
				
				
				
				$database->commitTransaction();
				$result['success'] = 1;
				$result['goterr'] = 0;
				

				
		}
			catch (Exception $e)
			{
				$database->rollbackTransaction();
				
				$linecounter_buffer = rtrim($linecounter_buffer,',');
				$result['success'] = 0;
				$result['ErrorMessage'] = $e->getMessage();
				$result['remrks'] = $remarks;
				$emsg = $e->getMessage();
				$result['goterr'] = 1;
				$result['filefordb'] = $filefordb;
				$result['userid'] = $userid;
				$result['linecounter'] = $linecounter_buffer;
				$result['rectype'] = $rectype;
			}				
		
		die(json_encode($result));		
		
		}
		
}

if($_POST['action'])
{
	if($_POST['action']=='showallprice')
	{
		$page      = (isset($_POST['page']))?$_POST['page']:1;
		$pagerows=30;  		
		$start = ($page > 1) ? ($page - 1) * $pagerows : 0; 
		$limit = 'limit '.$start.','.$pagerows;	
		
				echo $headerdetail;
				$viewresereved	= viewregularprice($limit,0,0);
				$viewresereved_total	= viewregularprice($limit,1,0);
																	
					if ($viewresereved->num_rows) 
						{
							$y = ($page - 1) * $pagerows;
							while($res = $viewresereved->fetch_object())
							{
								$y++;
							
								echo  '<tr class="trcontent" >  
										   <td width="3%"> ' .   $y   . '</td>
										   <td width="3%"> ' .   $res->code .'</td> 
										   <td width="20%"  style="text-align:left;padding-left:10px;">'.$res->Description.'</td> 
										   <td width="3%"  style="text-align:right;padding-right:10px;">' .    number_format($res->UnitPrice,2) . '</td>
									   </tr>';
							}
						}
				echo $footer;

	echo "<div style='margin-top:10px;' class='igsfield'>".  AddPagination($pagerows,$viewresereved_total->num_rows,$page)."</div>";		
	}
	
	
	elseif($_POST['action']=='createlog')
	{
		$filname = $_POST['filefordb'];
		$errmsg = $_POST['errmsg'];
		$linecounter = $_POST['linecounter'] ;
		$recordtype  = $_POST['rectype'] ;
		
		$addlog = $database->execute(" INSERT INTO uploadhistory (`Filename`,`Code`,`SubCode`,`UserID`,`Remarks`,`RecordType`,`LineNo`,`Status`,`EnrollmentDate`)
										       VALUES('$filname','UPLOADING','PRICEFILE',$userid, '$errmsg','$recordtype','$linecounter', 'FAILED', NOW())");		
											   
	}	
	
	elseif($_POST['action']=='checkfile')
	{
		$fileexist=0;
		$filename = $_POST['fname'];
		$filename_withoutExt = trim(preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename));
		
				$chkfnameindb = $database->execute("SELECT * FROM uploadhistory WHERE filename ='$filename_withoutExt' and `status`='SUCCESSFUL' ");
			
				if ($chkfnameindb->num_rows) 
				{
					$fileexist = 1;
				}
				
		$return['fileexist'] = $fileexist;
		die(json_encode($return));	

	}		
	
}
	
function validateDate($date)
{
    $d = DateTime::createFromFormat('Ymd_Hi', $date);
    return $d && $d->format('Ymd_Hi') === $date;
}