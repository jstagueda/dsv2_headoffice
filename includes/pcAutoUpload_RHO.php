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
						//echo "Folder: ".$file."<br>";
						$currentfolder = $folder;
						$currentfolder2 = $file;
						process($sp, $database, $currentfolder, $currentfolder2, $folder.$file."/");
					}
					else if(is_file($folder.$file))
					{
						//echo "File: ".$folder.$file."<br>";
						$hasError = 0;
						//$extension = substr($file, strrpos($file, '.'));
						
						//if($extension == ".txt")
						//{
							$rs_DeleteTmpUpload = $sp->spDeleteTmpUpload($database);
							$fh = fopen($folder.$file, 'r');
							//echo "fh: ".$fh."<br><br><br>";
							$filedata = fread($fh, filesize($folder.$file));
							//echo "fd: ".$filedata."<br><br><br>";
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
							
							//for rho
							$drno = "";
							$rbranch = "";
							$drdate = "";
							$shipmentdate = "";
							$totlinecounter = "";
							$shipmentvia = "";
							
							foreach ($rows as $row)
							{
								$rdata = csvstring_to_array(trim($row), ' ', '"', '\r\n');
								
								if ($first_row)//header
								{
									$h1 = trim($rdata[0]);
									$h2 = strtoupper(trim($rdata[1]));
									$h3 = trim(date('Y-m-d',strtotime($rdata[2])));
									$h4 = trim(date('Y-m-d',strtotime($rdata[3])));
									$h5 = trim($rdata[4]);
									$h6 = trim($rdata[5]);
									//echo $h1.", ".$h2.", ".$h3.", ".$h4.", ".$h5.", ".$h6."<br>";
									
									$rsBranchCheck = $sp->spCheckBranchCode($database, $h2);
									if (!$rsBranchCheck->num_rows)
									{
										$hasError = 1;
										break;
									}
									
									$rsRHOCheck = $sp->spCheckDocInventoryinout($database, $h1);
									if ($rsRHOCheck->num_rows)
									{
										$hasError = 1;
										break;
									}
			 
									$rs_insertTmpRHO = $sp->spInsertTmpRHO($database,$h1,$h2,$h3,$h4,$h5,$h6,0);
			
									if($rs_insertTmpRHO->num_rows)
									{
										while($row = $rs_insertTmpRHO->fetch_object())
										{										 						 			
										   $rhoid = $row->ID;							 	
										}
									}
								}
								else //details
								{
									$tpi_tmprhoid = 0;
									$plistrefno = $rdata[0];
									$lcounter = $rdata[1];
									$prodcode = $rdata[2];
									$qty = $rdata[3];
									$desc = $rdata[4];
									//echo $plistrefno."---".$lcounter."---".$prodcode."---".$qty."---".$desc."<br>";
									//$data = add_rhodetails(0, $plistrefno, $lcounter, $prodcode, $qty, $desc);							
									$data []= array
										(
											'tpi_tmprhoID' => $tpi_tmprhoid,
											'PickListRefNo' => $plistrefno,
											'LineCounter' => $lcounter,
											'ProductCode' => $prodcode,
											'Quantity' => $qty,
											'Description' => $desc
										);
								}
								$first_row = false;
							}
							
							if ($hasError == 0)
							{
								//echo "Data: ".var_dump($data)."<br>";
								foreach( $data as $row ) 
								{
									//echo "Row: ".var_dump($row)."<br>";
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
									
									//echo "isEmpty: ".$chckEmpty."<br>";	
									if($chckEmpty == 0)
									{
										$Uploader_rows = $sp->spInsertTmpRHODetails($database, trim($row['tpi_tmprhoID']), trim($row['PickListRefNo']), trim($row['LineCounter']), trim($row['ProductCode']), trim($row['Quantity']), trim(str_replace("'","\'",$row['Description']))); 					
									}
															
									$val = 0;
									$chckEmpty = 0;		 	 
								}
								
								$sp->spUpdateTmpRHODetails($database);
								$sp->spInsertFromTmpRHO($database);
								
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
	
	process($sp, $database, $currentfolder, $currentfolder2, AUTO_UPLOAD .'/RHO/');
?>