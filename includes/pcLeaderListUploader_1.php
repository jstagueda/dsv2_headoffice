<?php 
/* 
 *  Modified by: marygrace cabardo 
 *  10.10.2012
 *  marygrace.cabardo@gmail.com
 */
	require_once("../initialize.php");
	global $database;

	ini_set('display_errors', 0);
	ini_set("max-execution-time" , 600);
	
	if(isset($_POST['btnUpload']))
	{	
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
			//file_get_contents
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
			
			if (is_uploaded_file($_FILES['file']['tmp_name']))
			{
				$fileData = trim(file_get_contents($_FILES['file']['tmp_name']));                                
                              	$rows = explode("\n", $fileData);
                                //$rows = mysql_real_escape_string (explode("\n", $fileData));
				
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
				
				try
				{
					//$database->beginTransaction();
					foreach ($rows as $row)				 	{
                                                
				 		if ($uType == 5 || $uType == 6 || $uType == 7 || $uType == 8 || $uType == 9)
						{
							$rdata = csvstring_to_array(trim($row), ',', '"', '\r\n');                                                        
						}
						
					  	if ($uType == 5) //single line promo
						{
							$promocode = str_replace("'", "\'",$rdata[0]);
                                                        $promocode = str_replace("|", ";",$rdata[0]);
                                                        $promocode = str_replace(array("\r","\n"),"|",$rdata[0]);
                                                        $promocode = str_replace(array("||||","|||","||"),"|",$rdata[0]);
                                                        
                                                        $promodesc = mysql_escape_string($row[1]);
							$promodesc = str_replace("'", "\'",$rdata[1]);
                                                        $promodesc = str_replace("|", ";",$rdata[1]);
                                                        $promodesc = str_replace(array("\r","\n"),"|",$rdata[1]);
                                                        $promodesc = str_replace(array("||||","|||","||"),"|",$rdata[1]);                                                        
                                                        
                                                        $promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
                                                        $promosdate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[2])));
                                                        $promosdate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[2])));
                                                        $promosdate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[2])));
                                                        
                                                        $promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
                                                        $promoedate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[3])));
                                                        $promoedate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[3])));
                                                        $promoedate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[3])));
							
							$buyintype = str_replace("'", "\'",$rdata[4]);
                                                        $buyintype = str_replace("|", ";",$rdata[4]);
                                                        $buyintype = str_replace(array("\r","\n"),"|",$rdata[4]);
                                                        $buyintype = str_replace(array("||||","|||","||"),"|",$rdata[4]);
                                                        
                                                        $buyinminqty = str_replace("'", "\'",$rdata[5]);
                                                        $buyinminqty = str_replace("|", ";",$rdata[5]);
                                                        $buyinminqty = str_replace(array("\r","\n"),"|",$rdata[5]);
                                                        $buyinminqty = str_replace(array("||||","|||","||"),"|",$rdata[5]);
							
							$buyinminamt = str_replace("'", "\'",$rdata[6]);
                                                        $buyinminamt = str_replace("|", ";",$rdata[6]);
                                                        $buyinminamt = str_replace(array("\r","\n"),"|",$rdata[6]);
                                                        $buyinminamt = str_replace(array("||||","|||","||"),"|",$rdata[6]);
                                                        
                                                        $buyinplevel = str_replace("'", "\'",$rdata[7]);
                                                        $buyinplevel = str_replace("|", ";",$rdata[7]);
                                                        $buyinplevel = str_replace(array("\r","\n"),"|",$rdata[7]);
                                                        $buyinplevel = str_replace(array("||||","|||","||"),"|",$rdata[7]);
							
							$buyinpcode = str_replace("'", "\'",$rdata[8]);
                                                        $buyinpcode = str_replace("|", ";",$rdata[8]);
                                                        $buyinpcode = str_replace(array("\r","\n"),"|",$rdata[8]);
                                                        $buyinpcode = str_replace(array("||||","|||","||"),"|",$rdata[8]);
                                                        
                                                        $buyinpcode = str_replace("'", "\'",$rdata[8]);
                                                        $buyinpcode = str_replace("|", ";",$rdata[8]);
                                                        $buyinpcode = str_replace(array("\r","\n"),"|",$rdata[8]);
                                                        $buyinpcode = str_replace(array("||||","|||","||"),"|",$rdata[8]);
                                                        
							$buyinpdesc = mysql_escape_string($row[9]);							
							$buyinpdesc = str_replace("'", "\'",$rdata[9]);
                                                        $buyinpdesc = str_replace("|", ";",$rdata[9]);
                                                        $buyinpdesc = str_replace(array("\r","\n"),"|",$rdata[9]);
                                                        $buyinpdesc = str_replace(array("||||","|||","||"),"|",$rdata[9]);
                                                        
							$buyinreqtype = str_replace("'", "\'",$rdata[10]);
                                                        $buyinreqtype = str_replace("|", ";",$rdata[10]);
                                                        $buyinreqtype = str_replace(array("\r","\n"),"|",$rdata[10]);
                                                        $buyinreqtype = str_replace(array("||||","|||","||"),"|",$rdata[10]);
                                                        
							$buyinleveltype = str_replace("'", "\'",$rdata[11]);
                                                        $buyinleveltype = str_replace("|", ";",$rdata[11]);
                                                        $buyinleveltype = str_replace(array("\r","\n"),"|",$rdata[11]);
                                                        $buyinleveltype = str_replace(array("||||","|||","||"),"|",$rdata[11]);    
                                                        
                                                        $enttype = str_replace("'", "\'",$rdata[12]);
                                                        $enttype = str_replace("|", ";",$rdata[12]);
                                                        $enttype = str_replace(array("\r","\n"),"|",$rdata[12]);
                                                        $enttype = str_replace(array("||||","|||","||"),"|",$rdata[12]);
                                                        
							//$enttype = 1;
							$entqty = str_replace("'", "\'",$rdata[13]);
                                                        $entqty = str_replace("|", ";",$rdata[13]);
                                                        $entqty = str_replace(array("\r","\n"),"|",$rdata[13]);
                                                        $entqty = str_replace(array("||||","|||","||"),"|",$rdata[13]);
                                                        
							$entprodcode = str_replace("'", "\'",$rdata[14]);
                                                        $entprodcode = str_replace("|", ";",$rdata[14]);
                                                        $entprodcode = str_replace(array("\r","\n"),"|",$rdata[14]);
                                                        $entprodcode = str_replace(array("||||","|||","||"),"|",$rdata[14]);
                                                        
                                                        $entproddesc = mysql_escape_string($row[15]);
							$entproddesc = str_replace("'", "\'",$rdata[15]);
                                                        $entproddesc = str_replace("|", ";",$rdata[15]);
                                                        $entproddesc = str_replace(array("\r","\n"),"|",$rdata[15]);
                                                        $entproddesc = str_replace(array("||||","|||","||"),"|",$rdata[15]);
                                                        
							$entdetqty = str_replace("'", "\'",$rdata[16]);
                                                        $entdetqty = str_replace("|", ";",$rdata[16]);
                                                        $entdetqty = str_replace(array("\r","\n"),"|",$rdata[16]);
                                                        $entdetqty = str_replace(array("||||","|||","||"),"|",$rdata[16]);
                                                        
                                                        $entdetprice = str_replace("'", "\'",$rdata[17]);
                                                        $entdetprice = str_replace("|", ";",$rdata[17]);
                                                        $entdetprice = str_replace(array("\r","\n"),"|",$rdata[17]);                                                        
							$entdetprice = str_replace(array("||||","|||","||"),"|",$rdata[17]);
                                                        
							$entdetsavings = str_replace("'", "\'",$rdata[18]);
                                                        $entdetsavings = str_replace("|", ";",$rdata[18]);
                                                        $entdetsavings = str_replace(array("\r","\n"),"|",$rdata[18]);
                                                        $entdetsavings = str_replace(array("||||","|||","||"),"|",$rdata[18]);                                                        
                                                        
							$entdetpmg = str_replace("'", "\'",$rdata[19]);
                                                        $entdetpmg = str_replace("|", ";",$rdata[19]);
                                                        $entdetpmg = str_replace(array("\r","\n"),"|",$rdata[19]);
                                                        $entdetpmg = str_replace(array("||||","|||","||"),"|",$rdata[19]);
                                                        
							$sl_isplusplan = str_replace("'", "\'",$rdata[20]);
                                                        $sl_isplusplan = str_replace("|", ";",$rdata[20]);
                                                        $sl_isplusplan = str_replace(array("\r","\n"),"|",$rdata[20]);
                                                        $sl_isplusplan = str_replace(array("||||","|||","||"),"|",$rdata[20]);
							
							if ($entdetpmg == "")
							{
								$entdetpmg = 0;								
							}
                                                        if ($entdetpmg == "")
							{
								$entdetpmg = 0;								
							}
                                                        
							$Uploader_rows = $sp->spInsertTmpSingleLinePromo($database, 
                                                                                $promocode,     $promodesc,    $promosdate, 
                                                                                $promoedate,    $buyintype,    $buyinminqty, 
                                                                                $buyinminamt,   $buyinplevel,  $buyinpcode, 
                                                                                $buyinpdesc,    $buyinreqtype, $buyinleveltype, 
                                                                                $enttype,       $entqty,       $entprodcode, 
                                                                                $entproddesc,   $entdetqty,    $entdetprice, 
                                                                                $entdetsavings, $entdetpmg,    $sl_isplusplan);							 																		                                                      
						}
						else if ($uType == 6) //multiline promo - buyin
						{
							$mb_promocode = str_replace("'", "\'",$rdata[0]);
                                                        $mb_promocode = str_replace("|", ";",$rdata[0]);
                                                        $mb_promocode = str_replace(array("\r","\n"),"|",$rdata[0]);
                                                        $mb_promocode = str_replace(array("||||","|||","||"),"|",$rdata[0]);                                                                                                             
                                                        
							$mb_promodesc = str_replace("'", "\'",$rdata[1]);
                                                        $mb_promodesc = str_replace("|", ";",$rdata[1]);
                                                        $mb_promodesc = str_replace(array("\r","\n"),"|",$rdata[1]);
                                                        $mb_promodesc = str_replace(array("||||","|||","||"),"|",$rdata[1]);                                                         
                                                        
							$mb_promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
		                                        $mb_promosdate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[2])));
                                                        $mb_promosdate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[2])));
                                                        $mb_promosdate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[2])));                                                                                                                                                     
                                                        
							$mb_promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
		                                        $mb_promoedate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[3])));
                                                        $mb_promoedate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[3])));
                                                        $mb_promoedate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[3])));                                                                                                                                                                  
                                                        
							$mb_buyintype = str_replace("'", "\'",$rdata[4]);
                                                        $mb_buyintype = str_replace("|", ";",$rdata[4]);
                                                        $mb_buyintype = str_replace(array("\r","\n"),"|",$rdata[4]);
                                                        $mb_buyintype = str_replace(array("||||","|||","||"),"|",$rdata[4]);                                                         
                                                        
							$mb_buyinminqty = str_replace("'", "\'",$rdata[5]);
                                                        $mb_buyinminqty = str_replace("|", ";",$rdata[5]);
                                                        $mb_buyinminqty = str_replace(array("\r","\n"),"|",$rdata[5]);
                                                        $mb_buyinminqty = str_replace(array("||||","|||","||"),"|",$rdata[5]);
                                                                                                                
							$mb_buyinminamt = str_replace("'", "\'",$rdata[6]);
                                                        $mb_buyinminamt = str_replace("|", ";",$rdata[6]);
                                                        $mb_buyinminamt = str_replace(array("\r","\n"),"|",$rdata[6]);
                                                        $mb_buyinminamt = str_replace(array("||||","|||","||"),"|",$rdata[6]);  
                                                        
							$mb_buyinplevel = str_replace("'", "\'",$rdata[7]);
                                                        $mb_buyinplevel = str_replace("|", ";",$rdata[7]);
                                                        $mb_buyinplevel = str_replace(array("\r","\n"),"|",$rdata[7]);
                                                        $mb_buyinplevel = str_replace(array("||||","|||","||"),"|",$rdata[7]);  
                                                                    
							$mb_buyinpcode = str_replace("'", "\'",$rdata[8]);
                                                        $mb_buyinpcode = str_replace("|", ";",$rdata[8]);
                                                        $mb_buyinpcode = str_replace(array("\r","\n"),"|",$rdata[8]);
                                                        $mb_buyinpcode = str_replace(array("||||","|||","||"),"|",$rdata[8]);                                                        
                                                        
							$mb_buyinpdesc = str_replace("'", "\'",$rdata[9]);
                                                        $mb_buyinpdesc = str_replace("|", ";",$rdata[9]);
                                                        $mb_buyinpdesc = str_replace(array("\r","\n"),"|",$rdata[9]);
                                                        $mb_buyinpdesc = str_replace(array("||||","|||","||"),"|",$rdata[9]);                                                        
                                                        
							$mb_buyinreqtype = str_replace("'", "\'",$rdata[10]);
                                                        $mb_buyinreqtype = str_replace("|", ";",$rdata[10]);
                                                        $mb_buyinreqtype = str_replace(array("\r","\n"),"|",$rdata[10]);
                                                        $mb_buyinreqtype = str_replace(array("||||","|||","||"),"|",$rdata[10]);                                                        
                                                        
							$mb_buyinleveltype = str_replace("'", "\'",$rdata[11]);
                                                        $mb_buyinleveltype = str_replace("|", ";",$rdata[11]);
                                                        $mb_buyinleveltype = str_replace(array("\r","\n"),"|",$rdata[11]);
                                                        $mb_buyinleveltype = str_replace(array("||||","|||","||"),"|",$rdata[11]);                                                       
                                                        
							$ml_isplusplan = str_replace("'", "\'",$rdata[12]);
                                                        $ml_isplusplan = str_replace("|", ";",$rdata[12]);
                                                        $ml_isplusplan = str_replace(array("\r","\n"),"|",$rdata[12]);
                                                        $ml_isplusplan = str_replace(array("||||","|||","||"),"|",$rdata[12]);                                                       
                                                        
							
							$Uploader_rows = $sp->spInsertTmpMultiLineBuyinPromo($database, 
                                                                $mb_promocode,    $mb_promodesc, 
                                                                $mb_promosdate,   $mb_promoedate, 
                                                                $mb_buyintype,    $mb_buyinminqty, 
                                                                $mb_buyinminamt,  $mb_buyinplevel, 
                                                                $mb_buyinpcode,   $mb_buyinpdesc, 
                                                                $mb_buyinreqtype, $mb_buyinleveltype, 
                                                                $ml_isplusplan);
						}
						else if ($uType == 7) //multiline promo - entitlement
						{
							$mb_promocode = str_replace("'", "\'",$rdata[0]);
                                                        $mb_promocode = str_replace("|", ";",$rdata[0]);
                                                        $mb_promocode = str_replace(array("\r","\n"),"|",$rdata[0]);
                                                        $mb_promocode = str_replace(array("||||","|||","||"),"|",$rdata[0]);                                                 
                                                          
							$mb_promodesc = str_replace("'", "\'",$rdata[1]);
                                                        $mb_promodesc = str_replace("|", ";",$rdata[1]);
                                                        $mb_promodesc = str_replace(array("\r","\n"),"|",$rdata[1]);
                                                        $mb_promodesc = str_replace(array("||||","|||","||"),"|",$rdata[1]);  
                                                        
							$mb_promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
		                                        $mb_promosdate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[2])));
                                                        $mb_promosdate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[2])));
                                                        $mb_promosdate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[2])));                                                                                                                                                     
                                                        
							$mb_promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
		                                        $mb_promoedate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[3])));
                                                        $mb_promoedate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[3])));
                                                        $mb_promoedate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[3])));                                                                                                                                                     
                                                          
							$mb_enttype = str_replace("'", "\'",$rdata[4]);
                                                        $mb_enttype = str_replace("|", ";",$rdata[4]);
                                                        $mb_enttype = str_replace(array("\r","\n"),"|",$rdata[4]);
                                                        $mb_enttype = str_replace(array("||||","|||","||"),"|",$rdata[4]);                                                       
                                                                                                            
							$mb_entqty = str_replace("'", "\'",$rdata[5]);
                                                        $mb_entqty = str_replace("|", ";",$rdata[5]);
                                                        $mb_entqty = str_replace(array("\r","\n"),"|",$rdata[5]);
                                                        $mb_entqty = str_replace(array("||||","|||","||"),"|",$rdata[5]);                                                       
                                                        
							$mb_entprodcode = str_replace("'", "\'",$rdata[6]);
                                                        $mb_entprodcode = str_replace("|", ";",$rdata[6]);
                                                        $mb_entprodcode = str_replace(array("\r","\n"),"|",$rdata[6]);
                                                        $mb_entprodcode = str_replace(array("||||","|||","||"),"|",$rdata[6]);                                                       
                                                        
							$mb_entproddesc = str_replace("'", "\'",$rdata[7]);
                                                        $mb_entproddesc = str_replace("|", ";",$rdata[7]);
                                                        $mb_entproddesc = str_replace(array("\r","\n"),"|",$rdata[7]);
                                                        $mb_entproddesc = str_replace(array("||||","|||","||"),"|",$rdata[7]);                                                        
                                                        
							$mb_entdetqty = str_replace("'", "\'",$rdata[8]);
                                                        $mb_entdetqty = str_replace("|", ";",$rdata[8]);
                                                        $mb_entdetqty = str_replace(array("\r","\n"),"|",$rdata[8]);
                                                        $mb_entdetqty = str_replace(array("||||","|||","||"),"|",$rdata[8]);                                                         
                                                        
							$mb_entdetprice = str_replace("'", "\'",$rdata[9]);
                                                        $mb_entdetprice = str_replace("|", ";",$rdata[9]);
                                                        $mb_entdetprice = str_replace(array("\r","\n"),"|",$rdata[9]);
                                                        $mb_entdetprice = str_replace(array("||||","|||","||"),"|",$rdata[9]);                                                          
                                                        
							$mb_entdetsavings = str_replace("'", "\'",$rdata[10]);
                                                        $mb_entdetsavings = str_replace("|", ";",$rdata[10]);
                                                        $mb_entdetsavings = str_replace(array("\r","\n"),"|",$rdata[10]);
                                                        $mb_entdetsavings = str_replace(array("||||","|||","||"),"|",$rdata[10]);                                                         
                                                        
							$mb_entdetpmg = str_replace("'", "\'",$rdata[11]);
                                                        $mb_entdetpmg = str_replace("|", ";",$rdata[11]);
                                                        $mb_entdetpmg = str_replace(array("\r","\n"),"|",$rdata[11]);
                                                        $mb_entdetpmg = str_replace(array("||||","|||","||"),"|",$rdata[11]);                                                          
							
							if ($mb_entdetpmg == "")
							{
								$mb_entdetpmg = 0;
							}
							
							$Uploader_rows = $sp->spInsertTmpMultiLineEntitlementPromo($database, $mb_promocode, $mb_promodesc, $mb_promosdate, $mb_promoedate, $mb_enttype, $mb_entqty, $mb_entprodcode, $mb_entproddesc, $mb_entdetqty, $mb_entdetprice, $mb_entdetsavings, $mb_entdetpmg);
						}
						else if ($uType == 8) //overlay promo - buyin
						{
							$ob_promocode = str_replace("'", "\'",$rdata[0]);
							$ob_promocode = str_replace("|", ";",$rdata[0]);
							$ob_promocode = str_replace(array("\r","\n"),"|",$rdata[0]);
							$ob_promocode = str_replace(array("||||","|||","||"),"|",$rdata[0]);       
                                                        
							$ob_promodesc = str_replace("'", "\'",$rdata[1]);							
							$ob_promodesc = str_replace("|", ";",$rdata[1]);
							$ob_promodesc = str_replace(array("\r","\n"),"|",$rdata[1]);
							$ob_promodesc = str_replace(array("||||","|||","||"),"|",$rdata[1]);   
							
							$ob_promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
							$ob_promosdate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[2])));
							$ob_promosdate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[2])));
							$ob_promosdate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[2])));     
							
							
							$ob_promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
							$ob_promoedate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[3])));
							$ob_promoedate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[3])));
							$ob_promoedate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[3])));     
							

							$ob_nongsu = str_replace("'", "\'",$rdata[4]);
							$ob_nongsu = str_replace("|", ";",$rdata[4]);
							$ob_nongsu = str_replace(array("\r","\n"),"|",$rdata[4]);
							$ob_nongsu = str_replace(array("||||","|||","||"),"|",$rdata[4]);    
							

							$ob_dirgsu = str_replace("'", "\'",$rdata[5]);
							$ob_dirgsu = str_replace("|", ";",$rdata[5]);
							$ob_dirgsu = str_replace(array("\r","\n"),"|",$rdata[5]);
							$ob_dirgsu = str_replace(array("||||","|||","||"),"|",$rdata[5]);   
							
							$ob_idirgsu = str_replace("'", "\'",$rdata[6]);
							$ob_idirgsu = str_replace("|", ";",$rdata[6]);
							$ob_idirgsu = str_replace(array("\r","\n"),"|",$rdata[6]);
							$ob_idirgsu = str_replace(array("||||","|||","||"),"|",$rdata[6]);   							
							
							$ob_buyinreqtype = str_replace("'", "\'",$rdata[7]);
							$ob_buyinreqtype = str_replace("|", ";",$rdata[7]);
							$ob_buyinreqtype = str_replace(array("\r","\n"),"|",$rdata[7]);
							$ob_buyinreqtype = str_replace(array("||||","|||","||"),"|",$rdata[7]);    
							

							$ob_buyintype = str_replace("'", "\'",$rdata[8]);
							$ob_buyintype = str_replace("|", ";",$rdata[8]);
							$ob_buyintype = str_replace(array("\r","\n"),"|",$rdata[8]);
							$ob_buyintype = str_replace(array("||||","|||","||"),"|",$rdata[8]);  
							
							
							//$ob_buyinplevel = str_replace("'", "\'",$rdata[9]);
							$ob_buyinplevel = "3";
							
							$ob_buyinpcode = str_replace("'", "\'",$rdata[10]);
							$ob_buyinpcode = str_replace("|", ";",$rdata[10]);
							$ob_buyinpcode = str_replace(array("\r","\n"),"|",$rdata[10]);
							$ob_buyinpcode = str_replace(array("||||","|||","||"),"|",$rdata[10]);  
							
							$ob_buyinpdesc = str_replace("'", "\'",$rdata[11]);
							$ob_buyinpdesc = str_replace("|", ";",$rdata[11]);
							$ob_buyinpdesc = str_replace(array("\r","\n"),"|",$rdata[11]);
							$ob_buyinpdesc = str_replace(array("||||","|||","||"),"|",$rdata[11]);  
							
							
							$ob_buyincriteria = str_replace("'", "\'",$rdata[12]);
							$ob_buyincriteria = str_replace("|", ";",$rdata[12]);
							$ob_buyincriteria = str_replace(array("\r","\n"),"|",$rdata[12]);
							$ob_buyincriteria = str_replace(array("||||","|||","||"),"|",$rdata[12]);  
							
							
							$ob_buyinminval = str_replace("'", "\'",$rdata[13]);
							$ob_buyinminval = str_replace("|", ";",$rdata[13]);
							$ob_buyinminval = str_replace(array("\r","\n"),"|",$rdata[13]);
							$ob_buyinminval = str_replace(array("||||","|||","||"),"|",$rdata[13]);  
							
							
							$ob_buyinsdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[14])));
							$ob_buyinsdate = str_replace("|", ";",$rdata[14]);
							$ob_buyinsdate = str_replace(array("\r","\n"),"|",$rdata[14]);
							$ob_buyinsdate = str_replace(array("||||","|||","||"),"|",$rdata[14]);  
							
							
							$ob_buyinedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[15])));
							$ob_buyinedate = str_replace("|", ";",$rdata[15]);
							$ob_buyinedate = str_replace(array("\r","\n"),"|",$rdata[15]);
							$ob_buyinedate = str_replace(array("||||","|||","||"),"|",$rdata[15]);  
							
							
							$ob_buyinleveltype = str_replace("'", "\'",$rdata[16]);
							$ob_buyinleveltype = str_replace("|", ";",$rdata[16]);
							$ob_buyinleveltype = str_replace(array("\r","\n"),"|",$rdata[16]);
							$ob_buyinleveltype = str_replace(array("||||","|||","||"),"|",$rdata[16]);  
							
							
							$ob_isincentive = str_replace("'", "\'",$rdata[17]);
							$ob_isincentive = str_replace("|", ";",$rdata[17]);
							$ob_isincentive = str_replace(array("\r","\n"),"|",$rdata[17]);
							$ob_isincentive = str_replace(array("||||","|||","||"),"|",$rdata[17]);  
							
							
							$ol_isplusplan = str_replace("'", "\'",$rdata[18]);
							$ol_isplusplan = str_replace("|", ";",$rdata[18]);
							$ol_isplusplan = str_replace(array("\r","\n"),"|",$rdata[18]);
							$ol_isplusplan = str_replace(array("||||","|||","||"),"|",$rdata[18]);
							
								if($ob_nongsu == '')
								{
									$ob_nongsu = 0;
								}
								if($ob_dirgsu == '')
								{
									$ob_dirgsu = 0;
								}
								if($ob_idirgsu == '')
								{
									$ob_idirgsu = 0;
								}
							$Uploader_rows = $sp->spInsertTmpOverlayBuyinPromo($database, $ob_promocode, $ob_promodesc, $ob_promosdate, $ob_promoedate, $ob_nongsu, $ob_dirgsu, $ob_idirgsu, $ob_buyinreqtype, $ob_buyintype, $ob_buyinplevel, $ob_buyinpcode, $ob_buyinpdesc, $ob_buyincriteria, $ob_buyinminval, $ob_buyinsdate, $ob_buyinedate, $ob_buyinleveltype, $ob_isincentive, $ol_isplusplan);
						}
						else if ($uType == 9) //overlay promo - entitlement
						{

							$oe_promocode = str_replace("'", "\'",$rdata[0]);
							$oe_promocode = str_replace("|", ";",$rdata[0]);
							$oe_promocode = str_replace(array("\r","\n"),"|",$rdata[0]);
							$oe_promocode = str_replace(array("||||","|||","||"),"|",$rdata[0]);       
                                                        
							$oe_promodesc = str_replace("'", "\'",$rdata[1]);							
							$oe_promodesc = str_replace("|", ";",$rdata[1]);
							$oe_promodesc = str_replace(array("\r","\n"),"|",$rdata[1]);
							$oe_promodesc = str_replace(array("||||","|||","||"),"|",$rdata[1]);   
							
							$oe_promosdate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[2])));
							$oe_promosdate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[2])));
							$oe_promosdate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[2])));
							$oe_promosdate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[2])));    
							
							$oe_promoedate = date('Y-m-d',strtotime(str_replace("'", "\'",$rdata[3])));
							$oe_promoedate = date('Y-m-d',strtotime(str_replace("|", ";",$rdata[3])));
							$oe_promoedate = date('Y-m-d',strtotime(str_replace(array("\r","\n"),"|",$rdata[3])));
							$oe_promoedate = date('Y-m-d',strtotime(str_replace(array("||||","|||","||"),"|",$rdata[3]))); 

							$oe_enttype = str_replace("'", "\'",$rdata[4]);
							$oe_enttype = str_replace("|", ";",$rdata[4]);
							$oe_enttype = str_replace(array("\r","\n"),"|",$rdata[4]);
							$oe_enttype = str_replace(array("||||","|||","||"),"|",$rdata[4]); 

							$oe_entqty = str_replace("'", "\'",$rdata[5]);
							$oe_entqty = str_replace("|", ";",$rdata[5]);
							$oe_entqty = str_replace(array("\r","\n"),"|",$rdata[5]);
							$oe_entqty = str_replace(array("||||","|||","||"),"|",$rdata[5]);   
							
							$oe_entprodcode = str_replace("'", "\'",$rdata[6]);
							$oe_entprodcode = str_replace("|", ";",$rdata[6]);
							$oe_entprodcode = str_replace(array("\r","\n"),"|",$rdata[6]);
							$oe_entprodcode = str_replace(array("||||","|||","||"),"|",$rdata[6]); 	

							$oe_entproddesc = str_replace("'", "\'",$rdata[7]);
							$oe_entproddesc = str_replace("|", ";",$rdata[7]);
							$oe_entproddesc = str_replace(array("\r","\n"),"|",$rdata[7]);
							$oe_entproddesc = str_replace(array("||||","|||","||"),"|",$rdata[7]);  														
							
							$oe_entdetqty = str_replace("'", "\'",$rdata[8]);
							$oe_entdetqty = str_replace("|", ";",$rdata[8]);
							$oe_entdetqty = str_replace(array("\r","\n"),"|",$rdata[8]);
							$oe_entdetqty = str_replace(array("||||","|||","||"),"|",$rdata[8]);  
							
							$oe_entdetprice = str_replace("'", "\'",$rdata[9]);
							$oe_entdetprice = str_replace("|", ";",$rdata[9]);
							$oe_entdetprice = str_replace(array("\r","\n"),"|",$rdata[9]);
							$oe_entdetprice = str_replace(array("||||","|||","||"),"|",$rdata[9]);  							
							
							$oe_entdetsavings = str_replace("'", "\'",$rdata[10]);
							$oe_entdetsavings = str_replace("|", ";",$rdata[10]);
							$oe_entdetsavings = str_replace(array("\r","\n"),"|",$rdata[10]);
							$oe_entdetsavings = str_replace(array("||||","|||","||"),"|",$rdata[10]);  							
							
							$oe_entdetpmg = str_replace("'", "\'",$rdata[11]);
							$oe_entdetpmg = str_replace("|", ";",$rdata[11]);
							$oe_entdetpmg = str_replace(array("\r","\n"),"|",$rdata[11]);
							$oe_entdetpmg = str_replace(array("||||","|||","||"),"|",$rdata[11]);  
							
							
							if ($oe_entdetpmg == "")
							{
								$oe_entdetpmg = 0;
							}
							
							$Uploader_rows = $sp->spInsertTmpOverlayEntitlementPromo($database, $oe_promocode, $oe_promodesc, $oe_promosdate, $oe_promoedate, $oe_enttype, $oe_entqty, $oe_entprodcode, $oe_entproddesc, $oe_entdetqty, $oe_entdetprice, $oe_entdetsavings, $oe_entdetpmg);
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
								$promo_code = str_replace("|", ";",$row_det->PromoCode);
								$promo_code = str_replace(array("\r","\n"),"|",$row_det->PromoCode);
								$promo_code = str_replace(array("||||","|||","||"),"|",$row_det->PromoCode);
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
						$singleline = $sp->spSelectTmp_PromoSingleLine($database);
						$ud_cnt = $singleline->num_rows;
						 
						if ($singleline->num_rows)
						{
							while ($row = $singleline->fetch_object())
							{
								$ctr += 1;	
								$promo_code = $row->BuyinMinQty;								
								$promo_code = str_replace("'", "\'",$row_det->PromoCode);
								$promo_code = str_replace("|", ";",$row_det->PromoCode);
								$promo_code = str_replace(array("\r","\n"),"|",$row_det->PromoCode);
								$promo_code = str_replace(array("||||","|||","||"),"|",$row_det->PromoCode);                                                                                                
 
 								$promo_desc = $row->PromoDescription;
								$promo_desc = mysql_real_escape_string ($row->PromoDescription); 
								$promo_desc = str_replace("'", "\'",$row->PromoDescription);
								$promo_desc = str_replace("|", ";",$row->PromoDescription);
								$promo_desc = str_replace(array("\r","\n"),"|",$row->PromoDescription);
								$promo_desc = str_replace(array("||||","|||","||"),"|",$row->PromoDescription);
                                                                
								$promo_sdate = str_replace("'", "\'",$row->PromoStartDate);
								$promo_sdate = str_replace("|", ";",$row_det->PromoStartDate);
								$promo_sdate = str_replace(array("\r","\n"),"|",$row->PromoStartDate);
								$promo_sdate = str_replace(array("||||","|||","||"),"|",$row->PromoStartDate);                                                                
                                                                
								$promo_edate = str_replace("'", "\'",$row->PromoEndDate);
								$promo_edate = str_replace("|", ";",$row_det->PromoEndDate);
								$promo_edate = str_replace(array("\r","\n"),"|",$row->PromoEndDate);
								$promo_edate = str_replace(array("\r","\n"),"|",$row->PromoEndDate);
                                                                
								$buyin_type = $row->BuyinTypeID;
								$buyin_type = str_replace("'", "\'",$row_det->BuyinTypeID);
								$buyin_type = str_replace("|", ";",$row_det->BuyinTypeID);
								$buyin_type = str_replace(array("\r","\n"),"|",$row_det->BuyinTypeID);
								$buyin_type = str_replace(array("||||","|||","||"),"|",$row_det->BuyinTypeID);   
								
								$buyin_minqty = $row->BuyinMinQty;
								$buyin_minqty = str_replace("'", "\'",$row_det->BuyinMinQty);
								$buyin_minqty = str_replace("|", ";",$row_det->BuyinMinQty);
								$buyin_minqty = str_replace(array("\r","\n"),"|",$row_det->BuyinMinQty);
								$buyin_minqty = str_replace(array("||||","|||","||"),"|",$row_det->BuyinMinQty);    								
								
								$buyin_minamt = $row->BuyinMinAmt;
								$buyin_minamt = str_replace("'", "\'",$row_det->BuyinMinAmt);
								$buyin_minamt = str_replace("|", ";",$row_det->BuyinMinAmt);
								$buyin_minamt = str_replace(array("\r","\n"),"|",$row_det->BuyinMinAmt);
								$buyin_minamt = str_replace(array("||||","|||","||"),"|",$row_det->BuyinMinAmt);    								
								
								$buyin_plevel = $row->BuyinProductLevelID;
								$buyin_plevel = str_replace("'", "\'",$row_det->BuyinProductLevelID);
								$buyin_plevel = str_replace("|", ";",$row_det->BuyinProductLevelID);
								$buyin_plevel = str_replace(array("\r","\n"),"|",$row_det->BuyinProductLevelID);
								$buyin_plevel = str_replace(array("||||","|||","||"),"|",$row_det->BuyinProductLevelID);    								
								
								$buyin_pcode = $row->BuyinProductCode;
								$buyin_pcode = str_replace("'", "\'",$row_det->BuyinProductCode);
								$buyin_pcode = str_replace("|", ";",$row_det->BuyinProductCode);
								$buyin_pcode = str_replace(array("\r","\n"),"|",$row_det->BuyinProductCode);
								$buyin_pcode = str_replace(array("||||","|||","||"),"|",$row_det->BuyinProductCode);    								
								
								$buyin_pdesc = $row->BuyinProductDescription;
								$buyin_pdesc = mysql_real_escape_string ($row->BuyinProductDescription); 
								$buyin_pdesc = str_replace("'", "\'",$row_det->BuyinProductDescription);
								$buyin_pdesc = str_replace("|", ";",$row_det->BuyinProductDescription);
								$buyin_pdesc = str_replace(array("\r","\n"),"|",$row_det->BuyinProductDescription);
								$buyin_pdesc = str_replace(array("||||","|||","||"),"|",$row_det->BuyinProductDescription);    								
								
								$buyin_reqtype = $row->BuyinPurchaseReqTypeID;
								$buyin_reqtype = str_replace("'", "\'",$row->BuyinPurchaseReqTypeID);
								$buyin_reqtype = str_replace("|", ";",$row->BuyinPurchaseReqTypeID);
								$buyin_reqtype = str_replace(array("\r","\n"),"|",$row->BuyinPurchaseReqTypeID);
								$buyin_reqtype = str_replace(array("||||","|||","||"),"|",$row->BuyinPurchaseReqTypeID);    								
								
								$buyin_leveltype = $row->BuyinLevelTypeID;
								$buyin_leveltype = str_replace("'", "\'",$row_det->BuyinLevelTypeID);
								$buyin_leveltype = str_replace("|", ";",$row_det->BuyinLevelTypeID);
								$buyin_leveltype = str_replace(array("\r","\n"),"|",$row_det->BuyinLevelTypeID);
								$buyin_leveltype = str_replace(array("||||","|||","||"),"|",$row_det->BuyinLevelTypeID);    								
								
								$ent_type = $row->EntitlementTypeID;
								$ent_type = str_replace("'", "\'",$row_det->EntitlementTypeID);
								$ent_type = str_replace("|", ";",$row_det->EntitlementTypeID);
								$ent_type = str_replace(array("\r","\n"),"|",$row_det->EntitlementTypeID);
								$ent_type = str_replace(array("||||","|||","||"),"|",$row_det->EntitlementTypeID);    								
								
								$ent_qty = $row->EntitlementQty;
								$ent_qty = str_replace("'", "\'",$row_det->EntitlementQty);
								$ent_qty = str_replace("|", ";",$row_det->EntitlementQty);
								$ent_qty = str_replace(array("\r","\n"),"|",$row_det->EntitlementQty);
								$ent_qty = str_replace(array("||||","|||","||"),"|",$row_det->EntitlementQty);    								
								
								$ent_prodcode = $row->EntitlementProductCode;
								$ent_prodcode = str_replace("'", "\'",$row_det->EntitlementProductCode);
								$ent_prodcode = str_replace("|", ";",$row_det->EntitlementProductCode);
								$ent_prodcode = str_replace(array("\r","\n"),"|",$row_det->EntitlementProductCode);
								$ent_prodcode = str_replace(array("||||","|||","||"),"|",$row_det->EntitlementProductCode);    								
								
								$ent_proddesc = $row->EntitlementProductDescription;
								$ent_proddesc = mysql_real_escape_string ($row->EntitlementProductDescription); 
								$ent_proddesc = str_replace("'", "\'",$row_det->EntitlementProductDescription);
								$ent_proddesc = str_replace("|", ";",$row_det->EntitlementProductDescription);
								$ent_proddesc = str_replace(array("\r","\n"),"|",$row_det->EntitlementProductDescription);
								$ent_proddesc = str_replace(array("||||","|||","||"),"|",$row_det->EntitlementProductDescription);    
								
								$entdet_qty = $row->EntitlementDetQty;
								$entdet_qty = str_replace("'", "\'",$row_det->EntitlementDetQty);
								$entdet_qty = str_replace("|", ";",$row_det->EntitlementDetQty);
								$entdet_qty = str_replace(array("\r","\n"),"|",$row_det->EntitlementDetQty);
								$entdet_qty = str_replace(array("||||","|||","||"),"|",$row_det->EntitlementDetQty);    								
								
								$entdet_price = $row->EntitlementDetEffPrice;
								$entdet_price = str_replace("'", "\'",$row_det->EntitlementDetEffPrice);
								$entdet_price = str_replace("|", ";",$row_det->EntitlementDetEffPrice);
								$entdet_price = str_replace(array("\r","\n"),"|",$row_det->EntitlementDetEffPrice);
								$entdet_price = str_replace(array("||||","|||","||"),"|",$row_det->EntitlementDetEffPrice);    								
								
								$entdet_savings = $row->EntitlementDetSavings;
								$entdet_savings = str_replace("'", "\'",$row_det->EntitlementDetSavings);
								$entdet_savings = str_replace("|", ";",$row_det->EntitlementDetSavings);
								$entdet_savings = str_replace(array("\r","\n"),"|",$row_det->EntitlementDetSavings);
								$entdet_savings = str_replace(array("||||","|||","||"),"|",$row_det->EntitlementDetSavings);    								
								
								$entdet_pmg = $row->EntitlementDetPMG;
								$entdet_pmg = str_replace("'", "\'",$row_det->EntitlementDetPMG);
								$entdet_pmg = str_replace("|", ";",$row_det->EntitlementDetPMG);
								$entdet_pmg = str_replace(array("\r","\n"),"|",$row_det->EntitlementDetPMG);
								$entdet_pmg = str_replace(array("||||","|||","||"),"|",$row_det->EntitlementDetPMG);    								
								
								$promo_isplusplan = $row->IsPlusPlan;
								$promo_isplusplan = str_replace("'", "\'",$row_det->IsPlusPlan);
								$promo_isplusplan = str_replace("|", ";",$row_det->IsPlusPlan);
								$promo_isplusplan = str_replace(array("\r","\n"),"|",$row_det->IsPlusPlan);
								$promo_isplusplan = str_replace(array("||||","|||","||"),"|",$row_det->IsPlusPlan);    								
								
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
												$rs_promoid = $sp->spInsertPromoHeader($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 1, $session->emp_id, $promo_isplusplan);
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
													$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan);
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
												$promo_entitlement_exist = $sp->spCheckIfExistPromoEntitlement($database, $buyinparentID, 1);
												if (!$promo_entitlement_exist->num_rows)
												{
													//insert entitlement
													$rs_promoentid = $sp->spInsertPromoEntitlement($database, $buyinparentID, 1, 1);
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
													$sp->spUpdatePromoEntitlementByID($database, $entitlementID, $buyinparentID, 1, 1);											
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
						$msgLog .= "<br><br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Not Uploaded: ". $not_uploaded."<br><br>";
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
								$promo_isplusplan = $row->IsPlusPlan;
								
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
										$rs_promoid = $sp->spInsertPromoHeader($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 2, $session->emp_id, $promo_isplusplan);
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
											$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan);
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
						$msgLog .= "<br><br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Not Uploaded: ". $not_uploaded."<br><br>";
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
									
									//check if pmg exists
									$pmg_exist = $sp->spSelectPMGByID($database, $entdet_pmg);
									
									if (!$pmg_exist->num_rows)
									{
										//pmg does not exist
										$errmsg .= "PMG of Entitlement Product Code - ".$ent_prodcode." for Promo Code ".$promo_code. " does not exist.<br>";										
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
						$msgLog .= "<br><br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Not Uploaded: ". $not_uploaded."<br><br>";
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
								$promo_isplusplan = $row->IsPlusPlan;
								
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
										$rs_promoid = $sp->spInsertPromoHeaderOverlay($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, 3, $buyin_type, $min_qty, $min_amt, $session->emp_id, $isincentive, $promo_isplusplan);
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
											$sp->spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan);
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
						$msgLog .= "<br><br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Not Uploaded: ". $not_uploaded."<br><br>";
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
						$msgLog .= "<br><br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows In File: ". $ud_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Uploaded: ". $cd_cnt."<br>";
						$msgLog .= "&nbsp;&nbsp;Total Rows Not Uploaded: ". $not_uploaded."<br><br>";
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
    	redirect_to("../index.php?pageid=135&err=" . urlencode("Errors were encountered during uploading"));
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