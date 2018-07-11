<?php
	require_once("../initialize.php");
	global $database;
	ini_set('display_errors', 0);
	ini_set('max_execution_time', 300);
	
	$currentfolder = "";
	$currentfolder2 = "";
	
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
	
	function process($sp, $database, $currentfolder, $currentfolder2, $folder) 
	{
		if ($handle = opendir($folder))
		{
			while (false !== ($file = readdir($handle))) 
			{
				if ($file != "." && $file != ".." && strtolower($file) != "archived") 
				{
					// Check if folder. Recurse if it is
					if(is_dir($folder.$file))
					{
						$currentfolder = $folder;
						$currentfolder2 = $file;
						process($sp, $database, $currentfolder, $currentfolder2, $folder.$file."/");
					}
					else if(is_file($folder.$file))
					{
						$hasError = 0;
						//$extension = substr($file, strrpos($file, '.'));
						//if($extension == ".txt")
						//{
							$rs_DeleteTmpUpload = $sp->spDeleteTmpUpload($database);
							$fh = fopen($folder.$file, 'r');
							$filedata = fread($fh, filesize($folder.$file));
							//echo $filedata."<br><br><br>";
							$rows = explode("\n", $filedata);
							fclose($fh); 
							
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
											
							//for sta
							$txntype = "";
							$stano = "";
							$branchcode = "";
							$totallinecounter = "";
							
							foreach ($rows as $row)
							{
								$rdata = csvstring_to_array(trim($row), ' ', '"', '\r\n');
								//header
								if ($first_row)
								{
									$h1 = trim($rdata[0]);
									$h2 = trim($rdata[1]);
									$h3 = strtoupper(trim($rdata[2]));
									$h4 = trim(date('Y-m-d',strtotime($rdata[3])));
									$h5 = strtoupper(trim($rdata[4]));
									$h6 = trim($rdata[5]);
									//echo "H1 = ".$h1.", H2 = ".$h2.", H3 = ".$h3.", H4 = ".$h4.", H5 = ".$h6."<br>";
									
									$rsBranchCheck = $sp->spCheckBranchCode($database, $h3);
									if (!$rsBranchCheck->num_rows)
									{
										$hasError = 1;
										break;
									}
									
									$rsBranchCheck = $sp->spCheckBranchCode($database, $h5);
									if (!$rsBranchCheck->num_rows)
									{
										$hasError = 1;
										break;
									}
									
									$rsSTACheck = $sp->spCheckDocInventoryinout($database, $h2);
									if ($rsSTACheck->num_rows)
									{
										$hasError = 1;
										break;
									}
									
									if ($h1 == "ITR" || $h1 == "IRV" || $h1 == "RTR")
									{
										$rs_insertTmpSTA = $sp->spInsertTmpSTA($database,$h1,$h2,$h3,$h6,$h4,$h5);
									}
									else 
									{
										$hasError = 1;
										break;
									}

									if($rs_insertTmpSTA->num_rows)
									{
									   while($row = $rs_insertTmpSTA->fetch_object())
									   {										 						 			
										   $staid = $row->ID;						   
									   }
									}
								}
								else //details
								{
									$tpi_tmpstaid = 0;
									$stano = $rdata[0];
									$lcounter = $rdata[1];
									$prodcode = $rdata[2];					 
									$desc = $rdata[3];
									$qty = $rdata[4];
									//echo "STANo = ".$stano.", LineCounter = ".$lcounter.", prodCode = ".$prodcode.", Desc = ".$desc.", Qty = ".$qty."<br>";
									//add_stadetails($staid, $stano, $lcounter, $prodcode, $desc, $qty);
									$data []= array
									(
										'tpi_tmpstaID' => $tpi_tmpstaid,
										'STANo' => $stano,
										'LineCounter' => $lcounter,
										'ProductCode' => $prodcode,				
										'Description' => $desc,
										'Quantity' => $qty
									);
								}
								$first_row = false;
							}
							
							if ($hasError == 0)
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
										$Uploader_rows = $sp->spInsertTmpSTADetails($database, trim($row['tpi_tmpstaID']), trim($row['STANo']), trim($row['LineCounter']), trim($row['ProductCode']), trim(str_replace("'","\'",$row['Description'])), trim($row['Quantity']));
									}
												
									$val = 0;
									$chckEmpty = 0;		 	 
								}
								$sp->spUpdateTmpSTADetails($database);
								$sp->spInsertFromTmpSTA($database);
								
								if(!is_dir($currentfolder.'Archived/'.$currentfolder2))
								{
									mkdir($currentfolder.'Archived/'.$currentfolder2, 0777);
								}

								copy($folder.$file, $currentfolder.'Archived/'.$currentfolder2.'/'.$file);
								unlink($folder.$file);						
							}
						//}
					}
				}
			}
			closedir($handle);
		}
	}
	
	process($sp, $database, $currentfolder, $currentfolder2, AUTO_UPLOAD .'/STA/');
?>
