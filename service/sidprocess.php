
<?php
	require_once("../initialize.php");
	include "function_sidprocess.php";
	
	$OSTYPE     = GetSettingValue($database, 'OSTYPE'); 
	
	if($OSTYPE == 'WINDOWS')
	{
	   require("../class/config-ms.php");
	   //require("../class/live-config-ms.php");
	}else
	{
	   //require("../class/config-ms.php");
	}
	
	global $database;
	
	$userid = 1; //ITadministrator #$userid = $_SESSION['user_id'];
	$JDELOGFILE = GetSettingValue($database, 'JDELOGFILE'); 
	
	
	
	
	
	echo 'Starting to Process BDS<br>';
	branchdailysales(); #branch daily sales
	echo 'Done Processing BDS<br>';
	echo 'Starting to Process ARE<br>';
	branchcollection(); #branch daily collection
	echo 'Done Processing ARE<br>';
	echo 'Starting to Process DMCM<br>';
	branchdmcm(); #branch daily dmcm
	echo 'Done Processing DMCM<br>';
	echo 'Starting to Process Inventory<br>';
	branchinventory(); #inventory 
	echo 'Done Processing INV<br>';
	
	function branchinventory()
	{
		global $database; global $userid; global $OSTYPE; global $JDELOGFILE;
        $INVpath   =GetSettingValue($database, 'INVSID');
		$INVpathP  =GetSettingValue($database, 'INVSID_P');
		
		$database->execute("  CREATE TEMPORARY TABLE IF NOT EXISTS tmpSID_inv (SELECT * FROM sid_inv LIMIT 0); ");
		
		if (is_dir($INVpath))
		{
			if ($dh = opendir($INVpath))
			{
				while (($file = readdir($dh)) !== false)
				{
					if($file == '.' || $file == '..' )
				    {
					 
				    }
					else
					{
						try
		                {
							
							$database->beginTransaction();
						    #$syncProcessLog = '../logs/SID_INV.log';
							$syncProcessLog = $JDELOGFILE == '' ? '../logs/SID_INV.log': $JDELOGFILE.'SID_INV.log';
						    tpi_file_truncate($syncProcessLog); //Clear / clean sync process log file...
							
							$filedir    = $INVpath.$file;
							$filedate   = substr( $file, 4, 2 ).'/'.substr( $file, 6, 2 ).'/'.substr( $file, 8, 2 );
						    $filedate   = date('Y-m-d',strtotime($filedate));
						    $filedate2  = date('ymd',strtotime($filedate));
						    $filebranch = substr( $file, 11, 4 );
						    $filebranch = str_replace('.','',$filebranch );
						    $witherror  = 0;
						    $filetype   = substr($file,0,3 ) ;         //'INV';
						    $addressno  = GetAddressNo($filebranch);
						    $branchID   = GetBranchID($filebranch);  
						    $location   = getBranchPlantLocation('3505001',$branchID ,'WH');
							$BusinessUnit = GetBusinessUnit($filebranch);
							
							if($filetype != 'INV')
							{
							   tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.'0'.'|Invalid File Name|0|'.$userid."\r\n",$syncProcessLog);
							   throw new Exception("Invalid File Name.");   
							}
							$validate_file = $database->execute(" SELECT * 
														from  SID_Files 
														where SID_Files.`Type`          = '$filetype' 
														  and SID_Files.Branch          = '$filebranch'
														  and date(SID_Files.FileDate)  = '$filedate' ");
						   if($validate_file->num_rows > 0 )
						   {
						      throw new Exception("Transaction were already processed.");   
						   }
							
							#validate if file were already processed using SID_Files table
						    $valid = headervalidation('SID',$file,$filetype,$filebranch,$filedate,$addressno,$branchID,$location);
						    if($valid == 0)
						    { 
							   tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.'0'.'|Invalid Address No/Branch ID/Branch Plant Location|0|'.$userid."\r\n",$syncProcessLog);
							   throw new Exception("An error occurred, please contact your system administrator.");  
						    }
						    if ($valid == '1')
						    {
							   
							   $database->execute("delete from tmpSID_inv");
							   $linerow = 0; $witherror = 0; $headerline = 0; $linectr = 0;
							   $filecontent = fopen($filedir, 'r');  //open file
							  
							   while(($f = fgets($filecontent)) !== false)	
							   {	
								  $fields              = str_getcsv($f, ' ')	;
								  $linerow             = $linerow + 1; 
								  $dtype               = $linerow == 1 ? 'H' : 'D';  							  
                                  $dmovementcode       = str_replace('"','',trim($fields[1]));	
								  $drefno              = str_replace('"','',trim($fields[2]));
								  $dreftxnid           = str_replace('"','',trim($fields[3]));
								  $ditemcode           = str_replace('"','',trim($fields[4]));
								  $ditemcodeold        = str_replace('"','',trim($fields[5]));
								  $dquantity           = str_replace('"','',trim($fields[6]));
								  $dwhordg             = str_replace('"','',trim($fields[7]));
								  $dexplanation        = str_replace('"','',trim($fields[8]));
								  $dtobranch           = str_replace('"','',trim($fields[9]));
								  $dtobranchcode       = str_replace('"','',trim($fields[10]));
								  $dGLClass            = str_replace('"','',trim($fields[11]));
								  $dDRdate             = str_replace('"','',trim($fields[12]));
								  
								  if($linerow == 1)
								  {         
									 $dfilename   = $file;	
									 $dbranch     = str_replace('"','',trim($fields[0]));	
									 $headerline  = str_replace('"','',trim($fields[3]));								  
								  }
								  else
								  {									  
							          $linectr = $linectr + 1; 
									 
									  $createinv = createSIDINV($dtype,$dbranch,$dfilename,$filedate,$linectr,$dmovementcode,$drefno,
									               $dreftxnid,$ditemcode,$ditemcodeold,$dquantity,$dwhordg,$dexplanation,$dtobranch,$dtobranchcode,$userid,$dGLClass );
									  if(!$createinv)
									  {
										  tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.'0'.'|Cant Created SID Details -Line No'.$linectr.'|0|'.$userid."\r\n",$syncProcessLog);
										  $witherror = 1;
									  }	
									  
									  if($ditemcode == '')
									  {
										   $witherror = 1;
										   tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.'0'.'|Invalid Line Details-Product Code does not exist:'.$ditemcodeold.'|0|'.$userid."\r\n",$syncProcessLog); 
									  }
									  
									  $database->execute(" INSERT INTO tmpSID_inv 
												  VALUE('$dtype','$dbranch','$dfilename','$filedate','$linectr','$dmovementcode','$drefno',
												  '$dreftxnid','$ditemcode','$ditemcodeold','$dquantity','$dwhordg','$dexplanation','$dtobranch','$dtobranchcode','$dGLClass',      									  
												  $userid,NOW(),$userid,NOW())");	
                                       											  
								  }							  
							   }
							  # echo $file.'gg'.$witherror."\n";
							   if($witherror ==1)
							   {
								   throw new Exception("An error occurred, please contact your system administrator.");
							   }
							  #  echo $file.'gg2'.$witherror."\n";
							   $getinvlist = $database->execute("
							                                       SELECT * 
																   FROM tmpSID_inv tmp
							                                   ");
							   if($getinvlist->num_rows > 0 )
							   {
								   while($listinv = $getinvlist->fetch_object() )
								   {
									   if($listinv->GLClass == '')
									   {
										  $witherror = 1;
										  tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$listinv->Lineno.'|Invalid Line Details-GL Class should not be blank:'.$listinv->ProductCode.'|0|'.$userid."\r\n",$syncProcessLog); 
									   }
									   
									   $doctype         = getMnemonicCode('JDE_INV_DOCTYPE',$listinv->Movementcode); 
									   $branchplant_to  = getMnemonicCode('JDE_OTH_B/P',$listinv->Movementcode);
									   if($doctype == '')
									   {
										  $witherror = 1;
										  tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.'0'.'|Invalid Line Details-Document Type for:'.$listinv->Movementcode.' does not exist|0|'.$userid."\r\n",$syncProcessLog);
									   }
									   
									   $PL  = getProductLine($listinv->ProductCode);
									   if($PL == '')
									   {
											$witherror = 1;
											tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$listinv->Lineno.'|Invalid Line Details-Product Line does not exist:'.$listinv->ProductCode.'|0|'.$userid."\r\n",$syncProcessLog); 
									   } 
														 
									   $location   = getBranchPlantLocation('3505001',$branchID ,$listinv->DGorWH);	
									   if($location == '')
									   {
											$witherror = 1;
											tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$listinv->Lineno.'|Invalid Line Details-Location Code does not exist:'.$listinv->ProductCode.'|0|'.$userid."\r\n",$syncProcessLog); 
									   }
									   $reasoncode = '';
									   if($listinv->Movementcode == 'IBT' || $listinv->Movementcode == 'IBT-R')
									   { #look for reason code
											$reasoncode = getChargeCode($listinv->RefNo);
											if(strlen($reasoncode) > 3)
											{
													 $witherror = 1;
													 tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$listinv->Lineno.'|Invalid Line Details-Invalid Reason Code:'.$listinv->RefNo.'|0|'.$userid."\r\n",$syncProcessLog);
											}
									   }
									   if($listinv->ProductCode == '')
									   {
										  $witherror = 1;
										  tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$listinv->Lineno.'|Invalid Line Details-Product Code does not exist-g:'.$listinv->ProductCode_old.'|0|'.$userid."\r\n",$syncProcessLog); 
									   }					 			 
								   }
							   }
							   
							 #  echo $file.'gg3'.$witherror."\n";
							   if($witherror ==1)
							   {
								   throw new Exception("An error occurred, please contact your system administrator.");
							   }
							  # echo $file.'gg4'.$witherror."\n";
							   $doctype = ''; $branchplant_to = '';  $reasoncode = ''; 
							   $getinvpertxn = $database->execute("
							                                          SELECT * 
																	  FROM tmpSID_inv tmp
																	  GROUP BY tmp.RefTxnID,tmp.Movementcode
							                                      ");
							   if($getinvpertxn->num_rows > 0 )
							   {
								  while($inv = $getinvpertxn->fetch_object() )
								  {
									 	
									  $doctype         = getMnemonicCode('JDE_INV_DOCTYPE',$inv->Movementcode); 
									  $branchplant_to  = getMnemonicCode('JDE_OTH_B/P',$inv->Movementcode);
									  if($doctype == '')
									  {
										  $witherror = 1;
										  tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.'0'.'|Invalid Line Details-Document Type for:'.$inv->Movementcode.' does not exist|0|'.$userid."\r\n",$syncProcessLog);
									  }
									   
									  $recctr           = GetINVCount($filedate,$doctype)  + 1;
									  $DOC_NO_ORI       = '35-02-'.$doctype.'-'.$filedate2.'-'.sprintf("%05d",$recctr);	
									  $linectr          = 0;
									  $From_BranchPlant = $inv->Movementcode == 'RHO' ? '3502002' : '3505001'; 
									  $bypassDMAAI      = getMnemonicCode('JDE_BYPASS_DMAAI',$doctype); 
									  #echo $file.'<br>';
									  
									  #echo $DOC_NO_ORI ;
									  #get details
									  $rowctr = 0;
									  $getinvdtlpertxn = $database->execute("
																			  SELECT * 
																			  FROM tmpSID_inv tmp
																			  WHERE tmp.RefTxnID = $inv->RefTxnID 
																			    and tmp.Movementcode = '$inv->Movementcode'
																		   ");
									  if($getinvdtlpertxn->num_rows > 0 )
									  {  
								         
								         $rowctr = $getinvdtlpertxn->num_rows;
								         
								         $linectr = 0;
										 $dtlctr  = 0;
										 while($invd = $getinvdtlpertxn->fetch_object() )
										 {   
									         $tobranchcodeITR = $invd->tobranchCode;
											 $tobranchIDITR   = $invd->tobranchId;
											 if($bypassDMAAI == 'YES')
											 {
												  #get GL Class
												  if($invd->GLClass == '')
												  {
													 $witherror = 1;
													 tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$invd->Lineno.'|Invalid Line Details-GL Class should not be blank:'.$invd->ProductCode.'|0|'.$userid."\r\n",$syncProcessLog); 
												  }
												  else
												  {
													  $ACCOUNT  = getMnemonicCode('JDE_INV_ACCT',$doctype.'-'.$invd->GLClass);										  
													  $ACCOUNT = $BusinessUnit.'.'.$ACCOUNT;
													  $PL = '';
													  
													  $requireSUBledger = getMnemonicCode('JDE_INV_REQSUBLEDGER',$doctype); 
													  if($requireSUBledger == 'YES')
													  {
														 $PL  = getProductLine($invd->ProductCode);
														 $PLX = 'X';
														 if($PL == '')
														 {
															 $witherror = 1;
															 tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$invd->Lineno.'|Invalid Line Details-Product Line does not exist:'.$invd->ProductCode.'|0|'.$userid."\r\n",$syncProcessLog); 
														 } 
													  }
													  else
													  {
														  $PL  = '';
														  $PLX = '';
 													  }
												  }
											 }
											 else 
											 { 
										         $ACCOUNT = ''; 
												 $requireSUBledger = getMnemonicCode('JDE_INV_REQSUBLEDGER',$doctype); 
												 {
													if($requireSUBledger == 'YES')
													{
													   $PL  = getProductLine($invd->ProductCode);
													   $PLX = 'X';
													   if($PL == '')
													   {
															 $witherror = 1;
															 tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$invd->Lineno.'|Invalid Line Details-Product Line does not exist:'.$invd->ProductCode.'|0|'.$userid."\r\n",$syncProcessLog); 
													   } 
													}
												    else
												    {
													   $PL  = '';
												       $PLX = '';
 												    } 
												 }
											 }
											 
											 #create BP channel
											 $linectr    = $linectr + 1;
											 $location   = getBranchPlantLocation('3505001',$branchID ,$invd->DGorWH);	
											 if($location == '')
											 {
												$witherror = 1;
												tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$invd->Lineno.'|Invalid Line Details-Location Code does not exist:'.$invd->ProductCode.'|0|'.$userid."\r\n",$syncProcessLog); 
											 }
											 $HOLD_CODE  = getHoldCode('3505001',$branchID,$invd->DGorWH);
											 $reasoncode = '';
											 if($invd->Movementcode == 'IBT' || $invd->Movementcode == 'IBT-R')
											 { #look for reason code
												 $reasoncode = getChargeCode($invd->RefNo);
												 if(strlen($reasoncode) > 3)
												 {
													 $witherror = 1;
													 tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$invd->Lineno.'|Invalid Line Details-Invalid Reason Code:'.$invd->RefNo.'|0|'.$userid."\r\n",$syncProcessLog);
												 }
											 }
											 if($invd->ProductCode == '')
											 {
												 $witherror = 1;
												 tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$invd->Lineno.'|Invalid Line Details-Product Code does not exist-g:'.$invd->ProductCode_old.'|0|'.$userid."\r\n",$syncProcessLog); 
											 }
											
                                             if($witherror ==1)
											 {
												   throw new Exception("An error occurred, please contact your system administrator.");
											 }
											  
          									 if($invd->quantity <> 0)	
											 {
												 $dtlctr = $dtlctr + 1;
												 createINVdetail($DOC_NO_ORI,$doctype,$filedate,$EXPLANATION,'3505001',$linectr,$location,$invd->ProductCode,'',$invd->quantity,$reasoncode,
																 $ACCOUNT,$HOLD_CODE,$invd->Movementcode,$PL,$PLX);
											 }												 			 		 
											 if($branchplant_to != '')
											 {
												 #create BP intransit/Backload
												 $linectr    = $linectr + 1;
												 $location   = getBranchPlantLocation($branchplant_to,$branchID,$invd->DGorWH);
												 if($location == '')
												 {
													 $witherror = 1;
													 tpi_error_log($filedate.'|'.$filebranch.'|SID|INV|'.$file.'|'.$invd->Lineno.'|Invalid Line Details-Location Code does not exist:'.$invd->ProductCode.'|0|'.$userid."\r\n",$syncProcessLog); 
												 }
												 $HOLD_CODE  = getHoldCode($branchplant_to,$branchID,$invd->DGorWH);
												 if($invd->quantity <> 0)
												 {
													 $PL = '';
													 $PLX = '';
													 if($witherror ==1)
													 {
														 throw new Exception("An error occurred, please contact your system administrator.");
													 }
													 
													 if($invd->Movementcode == 'ITR')
													 {
														 $location   = getBranchPlantLocationIntransit($branchplant_to,$tobranchIDITR,$invd->DGorWH,'X'); 
														 $HOLD_CODE  = getHoldCode($branchplant_to,$tobranchIDITR,$invd->DGorWH);
													 }
													 
													 if($invd->Movementcode == 'RTR')
													 {
														 $location   = getBranchPlantLocationIntransit($branchplant_to,$branchID,$invd->DGorWH,'X'); 
														 $HOLD_CODE  = getHoldCode($branchplant_to,$branchID,$invd->DGorWH);
													 }
													 
													 if($invd->Movementcode == 'RHO')
													 {
														 $location   = getBranchPlantLocationIntransit($branchplant_to,$branchID,$invd->DGorWH,'R'); 
														 $HOLD_CODE  = getHoldCode($branchplant_to,$branchID,$invd->DGorWH);
													 }
													 $dtlctr = $dtlctr + 1;
													 createINVdetail($DOC_NO_ORI,$doctype,$filedate,$EXPLANATION,$branchplant_to,$linectr,$location,$invd->ProductCode,'',($invd->quantity * -1),'',
																	 $ACCOUNT,$HOLD_CODE,$invd->Movementcode,$PL,$PLX);
												 }
											 }
										 }
									  }
									  #echo $doctype.'h'.$filedate.'j'.$inv->Explanation.'k'.$From_BranchPlant.'g'.$rowctr.'<br>';
									  if ($dtlctr <> 0)
									  {
										  createINVheader($DOC_NO_ORI,$doctype,$filedate,$inv->Explanation,$From_BranchPlant,$rowctr,$inv->Movementcode);
									  } 
								  }
								  
							   }
							   
							   /* for movement code with 0 quantity */
                               $doctype = ''; $branchplant_to = '';  $reasoncode = ''; 
							   $getinvpertxn_zeroqty = $database->execute("
							                                                SELECT * 
																	        FROM tmpSID_inv tmp
																			where tmp.quantity = 0
																	        GROUP BY tmp.RefTxnID,tmp.Movementcode
							                                             ");
							   if($getinvpertxn_zeroqty->num_rows > 0 )
							   {
								  while($inv_zeroqty = $getinvpertxn_zeroqty->fetch_object() )
								  {
									 	
									  $doctype         = getMnemonicCode('JDE_INV_DOCTYPE',$inv_zeroqty->Movementcode); 
									  $branchplant_to  = getMnemonicCode('JDE_OTH_B/P',$inv_zeroqty->Movementcode);
									   
									  $recctr           = GetINVCount($filedate,$doctype)  + 1;
									  $DOC_NO_ORI       = '35-03-'.$doctype.'-'.$filedate2.'-'.sprintf("%05d",$recctr);	
									  $linectr          = 0;
									  $From_BranchPlant = $inv_zeroqty->Movementcode == 'RHO' ? '3502002' : '3505001'; 
									  $bypassDMAAI      = getMnemonicCode('JDE_BYPASS_DMAAI',$doctype); 
									  
									  $rowctr = 0;
									  $getinvdtlpertxn_zeroqty = $database->execute("
																					  SELECT * 
																					  FROM tmpSID_inv tmp2
																					  WHERE tmp2.RefTxnID     = $inv_zeroqty->RefTxnID 
																						and tmp2.Movementcode = '$inv_zeroqty->Movementcode'
																						and tmp2.quantity = 0
																				   ");
									  if($getinvdtlpertxn_zeroqty->num_rows > 0 )
									  {
                                         /* create details */
										 while($invd_zeroqty = $getinvdtlpertxn_zeroqty->fetch_object() )
								         {
											 $tobranchcodeITR = $invd_zeroqty->tobranchCode;
											 $tobranchIDITR   = $invd_zeroqty->tobranchId;
											 $linectr         = $linectr + 1;
											 $location        = getBranchPlantLocation('3505001',$branchID ,$invd_zeroqty->DGorWH);	
											 $reasoncode      = getChargeCode($invd_zeroqty->RefNo);
											 $rowctr          = $rowctr + 1;
                                             createINVdetail($DOC_NO_ORI,$doctype,$filedate,$inv_zeroqty->Explanation,'3505001',$linectr,$location,
											                 $invd_zeroqty->ProductCode,'',$invd_zeroqty->quantity,$reasoncode,'','',$invd_zeroqty->Movementcode,'','');

											 if($branchplant_to != '') #create BP intransit/Backload
											 {
												 $linectr    = $linectr + 1;
												 $location   = getBranchPlantLocation($branchplant_to,$branchID,$invd_zeroqty->DGorWH);
												 
												 if($invd_zeroqty->Movementcode == 'ITR')
												 {
													$location   = getBranchPlantLocationIntransit($branchplant_to,$tobranchIDITR,$invd_zeroqty->DGorWH,'X'); 
													$HOLD_CODE  = getHoldCode($branchplant_to,$tobranchIDITR,$invd_zeroqty->DGorWH);
												 } 
											     if($invd_zeroqty->Movementcode == 'RTR')
												 {
													$location   = getBranchPlantLocationIntransit($branchplant_to,$branchID,$invd_zeroqty->DGorWH,'X'); 
												    $HOLD_CODE  = getHoldCode($branchplant_to,$branchID,$invd_zeroqty->DGorWH);
												 }
											     if($invd_zeroqty->Movementcode == 'RHO')
												 {
													$location   = getBranchPlantLocationIntransit($branchplant_to,$branchID,$invd_zeroqty->DGorWH,'R'); 
												    $HOLD_CODE  = getHoldCode($branchplant_to,$branchID,$invd_zeroqty->DGorWH);
												 } 
												 $rowctr          = $rowctr + 1;
												 createINVdetail($DOC_NO_ORI,$doctype,$filedate,$inv_zeroqty->Explanation,$branchplant_to,$linectr,$location,$invd_zeroqty->ProductCode,'',0,'',
																	 '','',$invd_zeroqty->Movementcode,'','');
											 }				
										 }
                                         /* create header */	
                                         createINVheader_zeroqty($DOC_NO_ORI,$doctype,$filedate,$inv_zeroqty->Explanation,$From_BranchPlant,$rowctr,$inv_zeroqty->Movementcode);										 
									  }
								  }
							   }	 /* end ----->>>>> for movement code with 0 quantity */							   
							   
							   if($witherror ==1)
							   {
								   throw new Exception("An error occurred, please contact your system administrator.");
							   }
							   else
							   {
								   createSIDFiles($filetype,$filebranch,$filedate,$userid,$file,$linectr);
                                   #echo $filetype.'A'.$filebranch.'B'.$filedate.'C'.$headerline.'D';
                                   /*echo " INSERT INTO SID_logs
									SET SID_logs.Code            = 'SID',
									    SID_logs.Branch          = '$filebranch',   
										SID_logs.SubCode         = '$filetype',
										SID_logs.Filename        = '$file',
										SID_logs.RecordLineNo    = '$headerline',
										SID_logs.Remarks         = 'Successfully processed INV File',
										SID_logs.StatusID        = '1',
										SID_logs.CreatedBy       = $userid ,
										SID_logs.CreatedDate     = now(),
										SID_logs.Filedate        = '$filedate' " ; 		*/
											
								   insertLOGs('SID',$filetype,$file,$headerline,'Successfully processed INV File','1',$userid,$filebranch,$filedate);
							   }
							   		
                               //echo ' Successfully processed:'.$filebranch.'-'.$filedate.'-'.$file.'<br>';
							   if($OSTYPE == 'WINDOWS')
							   {  #if successfully processed move file to $bdspath-P and delete it and create SID files monitoring  
								  $cmd = 'move /Y "'.$filedir.'" "'.$INVpathP.'"';
								  #echo $cmd.'<br>';
								  exec($cmd);
							   }
							   else
							   {
								  exec("mv ".$filedir." ".$INVpathP);
								  exec("rm -rf ".$filedir);
							   }
							   $database->commitTransaction(); 									
							}
						}
						catch(Exception $e)
					    {
							$database->rollbackTransaction();
							$errmsg = $e->getMessage()."\n";
							$database->beginTransaction();
							createlogs($syncProcessLog);
							tpi_file_truncate($syncProcessLog);
							$database->commitTransaction();
					    } 
					}
				}
			}
		}
		
	}
	
	function branchdmcm()
	{
		global $database; global $userid; global $OSTYPE; global $JDELOGFILE;
        $DMCMpath     =GetSettingValue($database, 'DMCMSID');
		$DMCMpathP    =GetSettingValue($database, 'DMCMSID_P');	
		$GLinterface  =GetSettingValue($database, 'GL'); 
        #penalty offsetting - SO interface while trade offsetting to collection interface
		$database->execute("  CREATE TEMPORARY TABLE IF NOT EXISTS tmpSID_dmcm (SELECT * FROM sid_dmcm LIMIT 0); ");
		
        if (is_dir($DMCMpath))
		{
		    if ($dh = opendir($DMCMpath))
			{
				while (($file = readdir($dh)) !== false)
				{
				  if($file == '.' || $file == '..' )
				  {
					 
				  }
				  else
				  {
					  try
		              {
						  
						  $database->beginTransaction();
						  #$syncProcessLog = '../logs/SID_DMCM.log';
						  $syncProcessLog = $JDELOGFILE == '' ? '../logs/SID_DMCM.log': $JDELOGFILE.'SID_DMCM.log';
						  tpi_file_truncate($syncProcessLog); //Clear / clean sync process log file...
						  
						  $filedir    = $DMCMpath.$file;
						  $filedate   = substr( $file, 5, 2 ).'/'.substr( $file, 7, 2 ).'/'.substr( $file, 9, 2 );
						  $filedate   = date('Y-m-d',strtotime($filedate));
						  $filedate2  = date('ymd',strtotime($filedate));
						  $filebranch = substr( $file, 12, 4 );
						  $filebranch = str_replace('.','',$filebranch );
						  $witherror  = 0;
						  $addressno  = GetAddressNo($filebranch);
						  $branchID   = GetBranchID($filebranch);
                          $location   = getBranchPlantLocation('3505001',$branchID ,'WH');						  
						  $filetype   = substr($file,0,4 ) ;         //'DMCM';
						  $dmcmctr    = 0;
							
						  if($filetype != 'DMCM')
						  {
							   tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.'0'.'|Invalid File Name|0|'.$userid."\r\n",$syncProcessLog);
							   throw new Exception("Invalid File Name.");   
						  }
						  #echo $file;
						  #validate if directory is existng;
						  $offsetDIR    = GetSettingValue($database, 'OFFSET_CSV');
						  $offsetDIR    = $offsetDIR.'/'.$filedate;
						  
						  $validate_file = $database->execute(" SELECT * 
														from  SID_Files 
														where SID_Files.`Type`          = '$filetype' 
														  and SID_Files.Branch          = '$filebranch'
														  and date(SID_Files.FileDate)  = '$filedate' ");
						  if($validate_file->num_rows > 0 )
						  {
						      throw new Exception("Transaction were already processed.");   
						  }
						  
						  //if (!file_exists($offsetDIR) && !is_dir($offsetDIR)) 
						  //{
							//mkdir($offsetDIR);         
						 // } 
						  
						  #validate if file were already processed using SID_Files table
						  $valid = headervalidation('SID',$file,$filetype,$filebranch,$filedate,$addressno,$branchID,$location);
						  if($valid == 0)
						  {
							  tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.'0'.'|Invalid Address No/Branch ID/Branch Plant Location|0|'.$userid."\r\n",$syncProcessLog);
							  throw new Exception("An error occurred, please contact your system administrator.");  
						  }
						  #echo 'd';
						  if ($valid == '1')
						  {
							  
							  $database->execute("delete from tmpSID_dmcm"); 
							  $linerow = 0;  $TotalAmount = 0; $witherror = 0; $headerline = 0; $linectr = 0;
							  $filecontent = fopen($filedir, 'r');  //open file
							  
							  while(($f = fgets($filecontent)) !== false)	
							  {	
								  $fields = str_getcsv($f, ' ')	;
								  $linerow             = $linerow + 1; 
								  $dtype               = $linerow == 1 ? 'H' : 'D';  							  
                                  $detailtype          = str_replace('"','',trim($fields[0]));  //jde 	
                                  $dreasoncode         = str_replace('"','',trim($fields[1]));	
                                  $damount             = str_replace('"','',trim($fields[2]));  //jde	
                                  $daccount            = str_replace('"','',trim($fields[3]));  //jde	
                                  $dcc                 = str_replace('"','',trim($fields[4]));  //jde 	
								  $dbmcode             = str_replace('"','',trim($fields[5]));  //jde 	
								  $dbmname             = str_replace('"','',trim($fields[6]));  //jde 	 
								  $dbcmp               = str_replace('"','',trim($fields[7]));  //jde
								  $dnationalid         = str_replace('"','',trim($fields[8]));  //jde 
								  $dpaymenttype        = str_replace('"','',trim($fields[9]));  //jde 
								  
								  if($linerow == 1)
								  {         
									 $dfilename   = $file;	
									 $dbranch     = str_replace('"','',trim($fields[0]));	
									 $headerline  = str_replace('"','',trim($fields[6]));								  
								  }
								  else
								  { 
							          $linectr = $linectr + 1; 
									  createSIDDMCM($dtype,$dbranch,$dfilename,$filedate,$detailtype,$dreasoncode,$damount,$daccount,$dcc,
	                                               $dbmcode,$dbmname,$dbcmp,$dnationalid,$dpaymenttype,$userid,$linectr); 	  
									  $database->execute(" INSERT INTO tmpSID_dmcm 
												  VALUE('$dtype','$dbranch','$dfilename','$filedate','$detailtype','$dreasoncode',$damount,
												  '$daccount','$dcc','$dbmcode','$dbmname','$dbcmp','$dnationalid','$dpaymenttype',$linectr,      									  
												  NOW(),$userid,NOW(),$userid)");												  
								  }  
							  }
							  
							  createSIDDMCM('H',$dbranch,$dfilename,$filedate,'','',0,'','',
	                                               '','','','','',$userid,$headerline); 
                              $database->execute(" INSERT INTO tmpSID_dmcm 
												  VALUE('$dtype','$dbranch','$dfilename','$filedate','','',0,
												  '','','','','','','',$headerline,NOW(),$userid,NOW(),$userid)");	
												  
                              #Validate address no and accoount code
							  $BusinessUnit = GetBusinessUnit($filebranch);
							  
							  if($BusinessUnit == '')
							  {
								  $witherror = 1;
								  tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.''.'|Invalid Line Header - Business Unit does not exist '.$filebranch.' does not exist |0|'.$userid."\r\n",$syncProcessLog);
							  }
							  
							  $checkcsvlines  = $database->execute("
																 SELECT reasoncode , d.amount amount , accouncode, bmcode,nationalid,campaign 
																 FROM tmpSID_dmcm d 
																 WHERE d.reasoncode in ('SF-OFFSET','OOS')
																 AND d.detailtype = 'X'
																 ORDER BY bmcode,paymenttype,campaign
							                                   ");
							  if($checkcsvlines->num_rows > 0 )
							  {
								  #echo 'FF';
								 while($csvlines = $checkcsvlines->fetch_object())
								 { 
							        #echo '1.4';
									$Account = getMnemonicCode('JDE_ACCTCODE',$csvlines->accouncode);
								    $yesBegin = startsWith($Account, '035100');
									if( $yesBegin == false)
									{
									   $Account = $BusinessUnit.'.'.$Account;	
									}
									#echo '1.1';
									$AddressNo_BM = GetAddressNo_Vendor($csvlines->nationalid);
									#echo '3.3';
									if($Account == '')
									{
										#echo 'xxxxxxxxx';
										$witherror = 1;
										tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Account Code for '.$csvlines->accouncode.' does not exist |0|'.$userid."\r\n",$syncProcessLog);
									}
									#echo '1.2';
									if($AddressNo_BM == '')
									{
										$witherror = 1;
										tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Address No of '.$csvlines->nationalid.' does not exist |0|'.$userid."\r\n",$syncProcessLog);
									}
									#echo '1.3';
								 }
							  }
							  #echo $witherror.'j';
							  #echo 'GG';
							  if($witherror == 1)
							  {
								  throw new Exception("An error occurred, please contact your system administrator.");
							  }							    
							  
							  #create SO interface for total PENALTY offsetting transaction (branch) = SF
							  $getOffsettingPayment = $database->execute("
							                                                 SELECT ROUND(SUM(d.amount),2) Amt, d.accouncode accouncode ,d.accouncode, d.paymenttype paymenttype
																			 FROM tmpSID_dmcm d 
																			 WHERE d.reasoncode = 'SF-OFFSET'
																			 AND d.detailtype <> 'X' AND d.paymenttype = 'PEN'
																			 GROUP BY d.accouncode, d.paymenttype
							                                             ");
							  if($getOffsettingPayment->num_rows > 0 )
							  {
								  $recctr      = GetRecordCountSO('SF',$addressno,$filedate)  + 1;
								  $OrderTypeID = getMnemonicCode('SO-ORDERTYPE','SF');
								  $DOC_NO_ORI  = '35-'.$branchID.'-'.$filedate2.'-'.$OrderTypeID.'-'.$recctr;	
								  $linectr     = 0;
								  $createSOHDR = createSOHDR('00035','SF','3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$getOffsettingPayment->num_rows);
								  if(!$createSOHDR)
								  {
									  $witherror = 1;
									  tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.'0'.'|Invalid Line Header|0|'.$userid."\r\n",$syncProcessLog);
										
								  } 
								  while($offset = $getOffsettingPayment->fetch_object() )
								  {
									  $linectr = $linectr + 1;
									  $codevalue    = $offset->accouncode == '23045' ? 'TAXOUT' : $offset->paymenttype;
									  $MnemonicCode = $offset->accouncode == '23045' ? 'JDE_SKU_DISC' : 'JDE_SKU_DMCM';
									  $dummycode    = getMnemonicCode($MnemonicCode,$codevalue);
									  
									  $createSODTL = createSODTL('00035','SF','3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
													 $linectr,$dummycode,'',1,$offset->Amt,'N',$location,'');
								  }
							  }
							   #echo $witherror.'r';
							  #create Collection interface for total trade offsetting payment = paymenttype <> 'PEN'
							  $getOffsettingNonTrade = $database->execute("
							                                                 SELECT ROUND(SUM(d.amount),2) Amt, d.accouncode accouncode ,d.accouncode, d.paymenttype paymenttype
																			 FROM tmpSID_dmcm d 
																			 WHERE d.reasoncode = 'SF-OFFSET'
																			 AND d.detailtype <> 'X' AND d.paymenttype <> 'PEN'
																			 GROUP BY d.accouncode, d.paymenttype
							                                             ");
							  if($getOffsettingNonTrade->num_rows > 0 )
							  {  
								  while($offsetNT = $getOffsettingNonTrade->fetch_object() )
								  {
									  #echo $witherror.'rr';
									    $recctr       = GetRecordCountARE($addressno,$filedate)  + 1;
									    $DOC_NO_ORI   = '35-'.$branchID.'-'.$filedate2.'-'.'99'.'-'.$recctr;
									    $BusinessUnit = GetBusinessUnit($filebranch);
										$Account      = getMnemonicCode('JDE_ACCT_COMMCLR','COMMCLR');
										$AccountID = getMnemonicCode('JDE_ACCT_ID',$Account);
										if($AccountID == '')
										{
											$witherror = 1;
											tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.'0'.'|Invalid Line Details - Account should not be blank |0|'.$userid."\r\n",$syncProcessLog);
										}
										#echo $witherror.'/rrr';
										createCollection($addressno,$filedate,$offsetNT->Amt,$AccountID,$DOC_NO_ORI);
										#echo $witherror.'/rrrr';
								  }
							  }
							  #echo $witherror.'s';
							  #for all non Offsetting transaction - create SO interface S8 = DM and S9 for CM(S8)
							  $getCMTrn = $database->execute("
							                                 SELECT reasoncode , SUM(d.amount) amount , accouncode
															 FROM tmpSID_dmcm d 
															 WHERE d.reasoncode <> 'SF-OFFSET'
															 AND d.detailtype <> 'X' 
															 AND d.accouncode <> '10511'
															 AND d.accouncode <> '1'
															 AND d.detailtype = 'D'
															 GROUP BY reasoncode , accouncode
							                               ");
							  if($getCMTrn->num_rows > 0 )
							  {
								  $recctr      = GetRecordCountSO('S8',$addressno,$filedate)  + 1;
								  $linectr = 0;
								  $OrderTypeID = getMnemonicCode('SO-ORDERTYPE','S8');
								  $DOC_NO_ORI  = '35-'.$branchID.'-'.$filedate2.'-'.$OrderTypeID.'-'.$recctr;
								  
								  while($cm = $getCMTrn->fetch_object() )
								  {
										$linectr      = $linectr + 1;
										$codevalue    = $cm->accouncode == '23045' ? 'TAXOUT' : $cm->reasoncode;
										$MnemonicCode = $cm->accouncode == '23045' ? 'JDE_SKU_DISC' : 'JDE_SKU_DMCM';
										$dummycode    = getMnemonicCode($MnemonicCode,$codevalue);
										if($dummycode == '')
										{
											$witherror = 1;
											tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Dummy Item Code for '.$cm->reasoncode.' does not exist |0|'.$userid."\r\n",$syncProcessLog);
										}
										$createSODTL = createSODTL('00035','S8','3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
													 $linectr,$dummycode,'',-1,$cm->amount * -1,'N',$location,'');
								  }
								  
								  $createSOHDR = createSOHDR('00035','S8','3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$getCMTrn->num_rows);
								  if(!$createSOHDR)
								  {
									 tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.'0'.'|Invalid Line Header for CM Entry|0|'.$userid."\r\n",$syncProcessLog);
									 $witherror = 1;
								  }
							  }
							  #echo $witherror.'t';
							  #for all non Offsetting transaction - create SO interface S7 = DM
							  $getDMTrn = $database->execute("
							                                 SELECT reasoncode , SUM(d.amount) amount , accouncode
															 FROM tmpSID_dmcm d 
															 WHERE d.reasoncode <> 'SF-OFFSET'
															 AND d.detailtype <> 'X' 
															 AND d.accouncode <> '10511'
															 AND d.accouncode <> '1'
															 AND d.detailtype = 'C'
															 GROUP BY reasoncode , accouncode
							                               ");
							  if($getDMTrn->num_rows > 0 )
							  {
								  $recctr      = GetRecordCountSO('S7',$addressno,$filedate)  + 1;
								  $linectr = 0;
								  $OrderTypeID = getMnemonicCode('SO-ORDERTYPE','S7');
								  $DOC_NO_ORI  = '35-'.$branchID.'-'.$filedate2.'-'.$OrderTypeID.'-'.$recctr;
								  
								  while($dm = $getDMTrn->fetch_object() )
								  {
										$linectr      = $linectr + 1;
										$codevalue    = $dm->accouncode == '23045' ? 'TAXOUT' : $dm->reasoncode;
										$MnemonicCode = $dm->accouncode == '23045' ? 'JDE_SKU_DISC' : 'JDE_SKU_DMCM';
										$dummycode    = getMnemonicCode($MnemonicCode,$codevalue);
										
										if($dummycode == 'GETBANK')
										{
											$dummycode      = '';
											$SubsidiaryBank = GetSubsidiaryBank($filebranch); #getbank
											$dummycode      = getMnemonicCode('JDE_SKU_BANK',$SubsidiaryBank);
										}
										
										if($dummycode == '')
										{
											$witherror = 1;
											tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Dummy Item Code for '.$dm->ReasonCode.' does not exist |0|'.$userid."\r\n",$syncProcessLog);
										    throw new Exception("An error occurred, please contact your system administrator.");
										}
										
										$createSODTL = createSODTL('00035','S7','3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
													 $linectr,$dummycode,'',1,$dm->amount,'N',$location,'');
									    if(!$createSODTL)
										{
											tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.'0'.'|Invalid Line Details -'.$dummycode.'|0|'.$userid."\r\n",$syncProcessLog);
											$witherror = 1;
										}
								  }
								  
								  $createSOHDR = createSOHDR('00035','S7','3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$getDMTrn->num_rows);
								  if(!$createSOHDR)
								  {
										tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.'0'.'|Invalid Line Header for DM Entry|0|'.$userid."\r\n",$syncProcessLog);
										$witherror = 1;
								  }
							  }
							  #echo $witherror.'u';
							  #if all record was successfully created create CSV file that will close the clearing account  
							  #echo 'fff';
							  $BusinessUnit = GetBusinessUnit($filebranch);
							  $Remarks      = $location.':Offset Collection:'.$filedate2;
							  $glctr        = 0;
							  $getcsvlines  = $database->execute("
																 SELECT reasoncode , round(d.amount,2) amount , accouncode, bmcode,nationalid,campaign 
																 FROM tmpSID_dmcm d 
																 WHERE d.reasoncode in ('SF-OFFSET','OOS')
																 AND d.detailtype = 'X'
																 ORDER BY bmcode,paymenttype,campaign
							                                   ");
							  if($getcsvlines->num_rows > 0 )
							  {
								   #echo 'sadsfsdfsdf';
								  $GLinterface = GetSettingValue($database, 'GL'); 
								  $recctr      = 1;
								  $gldocseries = GetSettingValue($database, 'GLSeries') + 1;
								  $csvname     = $filebranch.'_'.$filedate2.'.csv';
							      
								  $DOC_NO_ORI  = '35JE'.$location.$filedate2.$recctr;
								  
								  //if($GLinterface == 'ON')
								  //{
								    //$filename    = fopen($offsetDIR.'/'.$csvname, 'w');
								  //}
								  
								  while($csv = $getcsvlines->fetch_object())
								  { 
									$Account = getMnemonicCode('JDE_ACCTCODE',$csv->accouncode);
								    $yesBegin = startsWith($Account, '035100');
									if( $yesBegin == false)
									{
									   $Account = $BusinessUnit.'.'.$Account;	
									}
									
									$AddressNo_BM = GetAddressNo_Vendor($csv->nationalid);
									if($Account == '')
									{
										$witherror = 1;
										tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Account Code for '.$csv->accouncode.' does not exist |0|'.$userid."\r\n",$syncProcessLog);
									}
									if($AddressNo_BM == '')
									{
										$witherror = 1;
										tpi_error_log($filedate.'|'.$filebranch.'|SID|DMCM|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Address No of '.$csv->nationalid.' does not exist |0|'.$userid."\r\n",$syncProcessLog);
									}
									
									$cmp           = ' '.$csv->campaign; 
									$debit         = $csv->amount < 0 ? '' : $csv->amount;
									$credit        = $csv->amount > 0 ? '' : $csv->amount * -1;
									$subledgertype = $Account == '035100.21514.001' ? '' : 'P';
									if($subledgertype == '')
									{
										$subledger = '';
									}
									else
									{
									    $subledger     = $Account == '035100.21514.001' ? '' : $Account == '035100.23031.002' ? $location : $cmp ;
									}
									
									$glctr = $glctr + 1;
									#create GL Table 
									if($GLinterface == 'ON')
									{
									   createAPGL('00035','JE',$glctr,$filedate,$Account,$csv->amount,$AddressNo_BM,$subledgertype,$subledger,$Remarks,$location,$DOC_NO_ORI,$gldocseries,$csv->campaign);
									}
									else
									{
										//fwrite($filename,$Account.",");
										//fwrite($filename,$debit.","); 
										//fwrite($filename,$credit.","); 
										//#fwrite($filename,$csv->accouncode.",");	
										//fwrite($filename,$subledgertype.",");
										//fwrite($filename,$subledger.",");		
										//fwrite($filename,$Remarks.",");	
										//fwrite($filename,$AddressNo_BM.",");	
										//fwrite($filename,$location." ");										
										//fwrite($filename, "\r\n"); 
									}
								}
								if($GLinterface == 'ON')
							    {
									$database->execute(" UPDATE settings SET settings.settingvalue = '$gldocseries' WHERE settingcode = 'GLSeries'  ");
								}
								
							  }
							  
							  #echo 'gggg';
							  if($witherror == 1)
							  {
								  throw new Exception("An error occurred, please contact your system administrator.");
							  }
                              else
							  {
								  createSIDFiles($filetype,$filebranch,$filedate,$userid,$file,$headerline);	  
								  insertLOGs('SID',$filetype,$file,$linectr,'Successfully processed DMCM File','1',$userid,$filebranch,$filedate);
							  }								    
						  }
						  
						  //echo ' Successfully processed:'.$filebranch.'-'.$filedate.'-'.$file.'<br>';
						  if($OSTYPE == 'WINDOWS')
						  {   #if successfully processed move file to $bdspath-P and delete it and create SID files monitoring  
							  #copy($filedir, $bdspathP);
							  $cmd = 'move /Y "'.$filedir.'" "'.$DMCMpathP.'"';
							  #echo $cmd.'<br>';
                              exec($cmd);
						  }
						  else
						  {
							  exec("mv ".$filedir." ".$DMCMpathP);
							  exec("rm -rf ".$filedir);
						  }
						  
						  $database->commitTransaction();
					  }
					  catch(Exception $e)
					  {
						$database->rollbackTransaction();
						$errmsg = $e->getMessage()."\n";
						$database->beginTransaction();
						createlogs($syncProcessLog);
						tpi_file_truncate($syncProcessLog);
						$database->commitTransaction();
					  } 
				  }
				}
			}
		}			
		
	}
	
	function branchcollection()
	{
		global $database; global $userid; global $OSTYPE; global $JDELOGFILE;
		$AREpath   =GetSettingValue($database, 'ARESID');
		$AREpathP  =GetSettingValue($database, 'ARESID_P');
		
		$database->execute("  CREATE TEMPORARY TABLE IF NOT EXISTS tmpSID_are (SELECT * FROM sid_are LIMIT 0); ");
		
		if (is_dir($AREpath))
		{
		    if ($dh = opendir($AREpath))
			{
				while (($file = readdir($dh)) !== false)
				{
				  if($file == '.' || $file == '..' )
				  {
					 
				  }
				  else
				  {
					  try
		              {
						  $database->beginTransaction();
						  #$syncProcessLog = '../logs/SID_ARE.log';
						  $syncProcessLog = $JDELOGFILE == '' ? '../logs/SID_ARE.log': $JDELOGFILE.'SID_ARE.log';
						  tpi_file_truncate($syncProcessLog); //Clear / clean sync process log file...
						  
						  $filedir    = $AREpath.$file;
						  $filedate   = substr( $file, 4, 2 ).'/'.substr( $file, 6, 2 ).'/'.substr( $file, 8, 2 );
						  $filedate   = date('Y-m-d',strtotime($filedate));
						  $filedate2  = date('ymd',strtotime($filedate));
						  $filebranch = substr( $file, 11, 4 );
						  $filebranch = str_replace('.','',$filebranch );
						  $witherror  = 0;
						  //$filetype   = 'ARE';
						  $OrderType  = 'SP';
						  $addressno   = GetAddressNo($filebranch);
						  $branchID    = GetBranchID($filebranch);  
						  $location   = getBranchPlantLocation('3505001',$branchID ,'WH');	

						  $filetype   = substr($file,0,3 ) ;         //'ARE';
							
						  if($filetype != 'ARE')
						  {
							   tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.'0'.'|Invalid File Name|0|'.$userid."\r\n",$syncProcessLog);
							   throw new Exception("Invalid File Name.");   
						  }
						  
						  #validate if file were already processed using SID_Files table
						  $valid = headervalidation('SID',$file,$filetype,$filebranch,$filedate,$addressno,$branchID,$location);
						  
						  $validate_file = $database->execute(" SELECT * 
														from  SID_Files 
														where SID_Files.`Type`          = '$filetype' 
														  and SID_Files.Branch          = '$filebranch'
														  and date(SID_Files.FileDate)  = '$filedate' ");
						  if($validate_file->num_rows > 0 )
						  {
						      throw new Exception("Transaction were already processed.");   
						  }
						  
						  
						  if($valid == 0)
						  {
							  tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.'0'.'|Invalid Address No/Branch ID|0|'.$userid."\r\n",$syncProcessLog);
							  throw new Exception("An error occurred, please contact your system administrator.");  
						  }
						  if ($valid == '1')
						  { 
					          
							  $database->execute("delete from tmpSID_are"); 
							  $linerow = 0;  $TotalAmount = 0; $witherror = 0; $headerline          = 0;
							  $filecontent = fopen($filedir, 'r');  //open file
							  while(($f = fgets($filecontent)) !== false)	
							  {
								  $fields              = explode(' ',$f);
								  $linerow             = $linerow + 1; 
								  $dtype               = $linerow == 1 ? 'H' : 'D';             
								  $dpaymenttype        = str_replace('"','',trim($fields[2]));  //jde 
								  $dreasoncode         = str_replace('"','',trim($fields[3]));  
								  $damount             = str_replace('"','',trim($fields[4]));  //jde
								  $dDBaccount          = str_replace('"','',trim($fields[5]));  
								  $dDBcc               = str_replace('"','',trim($fields[6]));  //jde
								  $dCRaccount          = str_replace('"','',trim($fields[7]));  
								  $dCRcc               = str_replace('"','',trim($fields[8]));  //jde
								  $dRunningAmount      = str_replace('"','',trim($fields[9]));  //jde
								  
								  if($linerow == 1)
								  {         
									  $dfilename   = $file;	
									  $dbranch     = str_replace('"','',trim($fields[0]));	
									  $headerline  = str_replace('"','',trim($fields[6]));								  
								  }
								  else
								  {
									  $amount = $damount * -1;
									  $RunningAmount  = $dRunningAmount * -1 ;
									  $TotalAmount = $TotalAmount + $amount; 
									  if( number_format($RunningAmount, 2, '.', '') <> number_format($TotalAmount, 2, '.', '')  )
									  {
										  throw new Exception("An error occurred, please contact your system administrator."); 
										  tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.$linerow.'|Running Amount not Equal with TotalAmount|0|'.$userid."\r\n",$syncProcessLog);
									  }
									  
									  if($dpaymenttype == '' || $dreasoncode == '' || $amount == '' || $dDBaccount == '' || $dCRaccount == '' )
									  {
										  $witherror = 1;
										  tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.$linerow.'|Invalid Line Details|0|'.$userid."\r\n",$syncProcessLog);
									  }
									  
									  
									  $dpaymenttype   = getMnemonicCode('JDE_PAYMENT_TYPE',$dreasoncode);
									  if($dpaymenttype == '')
									  {
										  $witherror = 1;
										  tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.$linerow.'|'.$dreasoncode.' Does not exist in codemaster (JDE_PAYMENT_TYPE) |0|'.$userid."\r\n",$syncProcessLog);
									  }
									  
									  createSID_are($dtype,$dbranch,$dfilename,$filedate,$dpaymenttype,$dreasoncode,$amount,
													$dDBaccount,$dDBcc,$dCRaccount,$dCRcc,$RunningAmount,$linerow,$userid); 
									  $database->execute(" INSERT INTO tmpSID_are 
												  VALUE('$dtype','$dbranch','$dfilename','$dtxndate','$dpaymenttype','$dreasoncode','$amount',
												  '$dDBaccount','$dDBcc','$dCRaccount','$dCRcc','$RunningAmount','$linerow',      									  
												  NOW(),$userid,NOW(),$userid)");						
								  }    							  
							  }
							  
							  $SubsidiaryBank = GetSubsidiaryBank($filebranch);
							  if($SubsidiaryBank == '')
							  {
								  $witherror = 1;
								  tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.($linectr + 1).'|Invalid Line Details - SubsidiaryBank should not be blank |0|'.$userid."\r\n",$syncProcessLog);
							  }
							  $Account     = getMnemonicCode('JDE_ACCT_BANK','BankAccount');
							  $BankAccount = $Account.'.'.$SubsidiaryBank;
							  $AccountID   = getMnemonicCode('JDE_ACCT_ID',$BankAccount);
										
							  if($AccountID == '')
							  {
								 $witherror = 1;
								 tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Account should not be blank |0|'.$userid."\r\n",$syncProcessLog);
							  }
						  
							  if( ($linerow - 1) <>  $headerline )
							  {
								  $witherror = 1;
								  tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.'0'.'|Line Header not equal with total detail|0|'.$userid."\r\n",$syncProcessLog);
								  
							  }
						      
							  createSID_are('H',$dbranch,$dfilename,$filedate,'','',$TotalAmount,'','','','',$TotalAmount,1,$userid);
							  $database->execute(" INSERT INTO tmpSID_are 
												   VALUE('H','$dbranch','$dfilename','$filedate','','','$TotalAmount','','','','','$TotalAmount','1',   									  
														 NOW(),$userid,NOW(),$userid)");
							  /* if valid - creation of SO interface for all non trade and collection interface if trade collection */
							  if($witherror == 1)
							  { 
								 throw new Exception("An error occurred, please contact your system administrator.");  
							  }
							  if($witherror == 0)
							  {  // Mapping needed - dummy product code for each non trade reason code 	 
								 //create doc original number 
								 $recctr      = GetRecordCountSO('SP',$addressno,$filedate)  + 1;
								 $linectr = 0;
								 $OrderTypeID = getMnemonicCode('SO-ORDERTYPE','SP');
								 $DOC_NO_ORI  = '35-'.$branchID.'-'.$filedate2.'-'.$OrderTypeID.'-'.$recctr;
								 
								 #Non Trade Payments - SO Interface
								 $getaredtlq =  $database->execute(" select PaymentType,ReasonCode,Round(Amount,2) Amount,CreditAccountCode 
																	 from tmpSID_are where PaymentType = 'N' order by LineCounter
                                                                   ");
																	 
								 if($getaredtlq->num_rows > 0 )
								 {
									while($aredtl = $getaredtlq->fetch_object() )
									{
										$linectr      = $linectr + 1;
										$codevalue    = $aredtl->CreditAccountCode == '23045' ? 'TAXOUT' : $aredtl->ReasonCode;
										$MnemonicCode = $aredtl->CreditAccountCode == '23045' ? 'JDE_SKU_DISC' : 'JDE_SKU_NT';
										$dummycode    = getMnemonicCode($MnemonicCode,$codevalue);
										if($dummycode == '')
										{
											$witherror = 1;
											tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Dummy Item Code fordd '.$aredtl->ReasonCode.' does not exist |0|'.$userid."\r\n",$syncProcessLog);
										}
										$createSODTL = createSODTL('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
													 $linectr,$dummycode,'',1,$aredtl->Amount,'N',$location,'');
									}
									
									$createSOHDR = createSOHDR('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$getaredtlq->num_rows);
									if(!$createSOHDR)
									{
										tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.'0'.'|Invalid Line Header|0|'.$userid."\r\n",$syncProcessLog);
										throw new Exception("An error occurred, please contact your system administrator.");
									}
									
								 }
								 
								 #Trade Collection - Collection Interface 
								 $getaredtlTradeq =  $database->execute(" select PaymentType,ReasonCode,ifnull(ROUND(SUM(Amount),4),0) Amount,CreditAccountCode 
																	 from tmpSID_are where PaymentType = 'T'");
								 if($getaredtlTradeq->num_rows > 0 )
								 {
									while($aredtltrade = $getaredtlTradeq->fetch_object() )
									{
										$SubsidiaryBank = GetSubsidiaryBank($filebranch);
										$recctr         = GetRecordCountARE($addressno,$filedate)  + 1;
									    $DOC_NO_ORI     = '35-'.$branchID.'-'.$filedate2.'-'.'88'.'-'.$recctr;
										if($SubsidiaryBank == '')
										{
											$witherror = 1;
											tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.($linectr + 1).'|Invalid Line Details - SubsidiaryBank should not be blank |0|'.$userid."\r\n",$syncProcessLog);
										}
										$Account     = getMnemonicCode('JDE_ACCT_BANK','BankAccount');
										$BankAccount = $Account.'.'.$SubsidiaryBank;
										$AccountID   = getMnemonicCode('JDE_ACCT_ID',$BankAccount);
										
										if($AccountID == '')
										{
											$witherror = 1;
											tpi_error_log($filedate.'|'.$filebranch.'|SID|ARE|'.$file.'|'.($linectr + 1).'|Invalid Line Details - Account should not be blank |0|'.$userid."\r\n",$syncProcessLog);
										}
										
										if($aredtltrade->Amount <> 0)
										{
										   createCollection($addressno,$filedate,$aredtltrade->Amount,$AccountID,$DOC_NO_ORI);
										}
									}
								 }
								 
								 if($witherror == 1)
								 {
									throw new Exception("An error occurred, please contact your system administrator.");
								 }
								 createSIDFiles($filetype,$filebranch,$filedate,$userid,$file,1);	  
								 insertLOGs('SID',$filetype,$file,$linectr,'Successfully processed ARE File','1',$userid,$filebranch,$filedate);
								
								 #transfer Record to StagingDatabase
							  }					  
						  }
						  
						  //echo ' Successfully processed:'.$filebranch.'-'.$filedate.'-'.$file.'<br>';
						  if($OSTYPE == 'WINDOWS')
						  {   #if successfully processed move file to $bdspath-P and delete it and create SID files monitoring  
							  $cmd = 'move /Y "'.$filedir.'" "'.$AREpathP.'"';
							  #echo $cmd.'<br>';
                              exec($cmd);
						  }
						  else
						  {
							  exec("mv ".$filedir." ".$AREpathP);
							  exec("rm -rf ".$filedir);
						  }
						  $database->commitTransaction();
					  }
					  catch(Exception $e)
					  {
						$database->rollbackTransaction();
						$errmsg = $e->getMessage()."\n";
						$database->beginTransaction();
						createlogs($syncProcessLog);
						tpi_file_truncate($syncProcessLog);
						$database->commitTransaction();
					  } 
				  }
			   }
			}
		} 	
	}
	
	function branchdailysales()
	{
		global $database; global $userid; global $OSTYPE; global $JDELOGFILE;
		$bdspath   =GetSettingValue($database, 'BDSSID');
		$bdspathP  =GetSettingValue($database, 'BDSSID_P');
		
		#temporary table creation - branch daily sales 
		$database->execute("  CREATE TEMPORARY TABLE IF NOT EXISTS tmpsid_bds (SELECT * FROM sid_bds LIMIT 0); ");
		#get all files in $bdspath location  // Open a directory, and read its contents
		if (is_dir($bdspath))
		{
			if ($dh = opendir($bdspath))
			{
				while (($file = readdir($dh)) !== false)
				{
				  if($file == '.' || $file == '..' )
				  {
					 
				  }
				  else
				  {
					  try
		              {	
                          $database->beginTransaction();
					      #$syncProcessLog = '../logs/sid_bds.log';
						  $syncProcessLog = $JDELOGFILE == '' ? '../logs/sid_bds.log': $JDELOGFILE.'sid_bds.log';
                          tpi_file_truncate($syncProcessLog); //Clear / clean sync process log file... 
						  $filedir    = $bdspath.$file;
						  $filedate   = substr( $file, 4, 2 ).'/'.substr( $file, 6, 2 ).'/'.substr( $file, 8, 2 );
						  $filedate   = date('Y-m-d',strtotime($filedate));
						  $filedate2  = date('ymd',strtotime($filedate));
						  $filebranch = substr( $file, 11, 4 );
						  $filebranch = str_replace('.','',$filebranch );
						  $witherror  = 0;
						  //$filetype   = 'BDS';
						  
						  
						  $filetype   = substr($file,0,3 ) ;         //'BDS';
							
						  if($filetype != 'BDS')
						  {
							   tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Invalid File Name|0|'.$userid."\r\n",$syncProcessLog);
							   throw new Exception("Invalid File Name.");   
						  }
						  
						  $addressno   = GetAddressNo($filebranch);
						  $branchID    = GetBranchID($filebranch);  
						  $location    = getBranchPlantLocation('3505001',$branchID ,'WH');
						  
						  $validate_file = $database->execute(" SELECT * 
														from  SID_Files 
														where SID_Files.`Type`          = '$filetype' 
														  and SID_Files.Branch          = '$filebranch'
														  and date(SID_Files.FileDate)  = '$filedate' ");
						  if($validate_file->num_rows > 0 )
						  {
							  #echo $file.'<br>';
						      throw new Exception("Transaction were already processed.");   
						  }
						  
						  #validate if file were already processed using SID_Files table
						  $valid = headervalidation('SID',$file,$filetype,$filebranch,$filedate,$addressno,$branchID,$location);
						  
						  if($valid == 0)
						  {  
							  tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Invalid Address No/Branch ID/BP location |0|'.$userid."\r\n",$syncProcessLog);
						      throw new Exception("An error occurred, please contact your system administrator.");  
						  }
						  
						  
						  
						  if ($valid == '1')
						  {
							  
							  $database->execute("delete from tmpsid_bds "); 
							  $linerow = 0; 
							  $filecontent = fopen($filedir, 'r');  //open file
							  
							  while(($f = fgets($filecontent)) !== false)	
							  {
								  $fields              = explode(' ',$f);
								  $linerow             = $linerow + 1;
								  $dtype               = str_replace('"','',trim($fields[0]));  $dbranch             = str_replace('"','',trim($fields[1]));  //jde 
								  $dfilename           = str_replace('"','',trim($fields[2]));  $dtxndate            = str_replace('"','',trim($fields[3]));  //jde
								  $productcode         = str_replace('"','',trim($fields[4]));  $dqty                = str_replace('"','',trim($fields[5]));  //jde
								  $ddregularpricensv   = str_replace('"','',trim($fields[6]));  $dcspnsv             = str_replace('"','',trim($fields[7])); 
								  $ddealerdiscountnsv  = str_replace('"','',trim($fields[8]));  $dadditionaldiscnsv  = str_replace('"','',trim($fields[9])); 	
								  $dregularprice       = str_replace('"','',trim($fields[10])); $dcsp                = str_replace('"','',trim($fields[11])); //jde
								  $ddealerdiscount     = str_replace('"','',trim($fields[12])); $dadditionaldisc     = str_replace('"','',trim($fields[13])); //jde
								  $dadditionaldiscprev = str_replace('"','',trim($fields[14])); $dinvoiceamount      = str_replace('"','',trim($fields[15]));
								  $dnsv                = str_replace('"','',trim($fields[16])); $dvat                = str_replace('"','',trim($fields[17])); //jde
								  $dproductline        = str_replace('"','',trim($fields[18])); $dcustcode           = str_replace('"','',trim($fields[19])); //jde
								  $dcusttype           = str_replace('"','',trim($fields[20])); $drowctr             = str_replace('"','',trim($fields[21]));  
								  $producttypeid       = str_replace('"','',trim($fields[22])); $jdeproductcode      = str_replace('"','',trim($fields[23]));
								  
								  if($dtype == 'H')
								  {
									 $drowhdr =  $drowctr;
								  }
								  #create record to sid_bds table		
								
								  createsid_bds($dtype,$dbranch,$dfilename,$dtxndate,$productcode,$dqty,$ddregularpricensv,$dcspnsv,
												$ddealerdiscountnsv,$dadditionaldiscnsv,$dregularprice,$dcsp,$ddealerdiscount,$dadditionaldisc,
												$dadditionaldiscprev,$dinvoiceamount,$dnsv,$dvat,$dproductline,$dcustcode,$dcusttype,
												$drowctr,$producttypeid,$jdeproductcode);  
									
									
								  $database->execute(" INSERT INTO tmpsid_bds 
								  VALUE('$dtype','$dbranch','$dfilename','$dtxndate','$productcode','$dqty','$ddregularpricensv','$dcspnsv',
												'$ddealerdiscountnsv','$dadditionaldiscnsv','$dregularprice','$dcsp','$ddealerdiscount','$dadditionaldisc',
												'$dadditionaldiscprev','$dinvoiceamount','$dnsv','$dvat','$dproductline','$dcustcode','$dcusttype',
												'$drowctr','$producttypeid','$jdeproductcode', NOW(),1,NOW(),1) ");					  
												
								  #echo $dtype.'-'.$dbranch.'-'.$dfilename.'-'.$dtxndate.'-'.$productcode.'-'.$dqty.'-'.$dregularprice.'-'.$dcsp.'-'.$ddealerdiscount.'-'.$dadditionaldiscprev.'-'.$dvat.'-'.$dproductline.'-'.$custcode.'-'.$custtype.'-'.$producttypeid.'<br>';
								  
								  if($jdeproductcode == '' && $dtype != 'H')
								  {
									  $witherror = 1;
									  tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.$drowctr.'|Invalid Line Details-Product Code does not exist-y:'.$productcode.'|0|'.$userid."\r\n",$syncProcessLog);			  
								  }
								  
								  if(strlen($dproductline) > 3 && $dtype != 'H' )
								  {
									  $witherror = 1;
									  tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.$drowctr.'|Invalid Line Details-Invalid Product Line of ITEM:'.$productcode.'|0|'.$userid."\r\n",$syncProcessLog);
								  }
							  }
							  
							  $getdetailval = $database->execute(" select * 
																   from  tmpsid_bds 
																   where tmpsid_bds.type = 'D'  
																 "); #get all detail record 
							  if(!$getdetailval->num_rows > 0 )
							  { 
						           $witherror = 1;
								   tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.$drowctr.'|No Record To process'.''.'|0|'.$userid."\r\n",$syncProcessLog);
							  }
                              
							  if($witherror == 1)
							  {
								 throw new Exception("An error occurred, please contact your system administrator."); 
							  }
							  
							  #start of validation - if with error create log file 
							  $gettmptablerecord = $database->execute(" select * from tmpsid_bds where type = 'H' "); #get all header record 
							  if($gettmptablerecord->num_rows > 0 )
							  {
								$linerow = $linerow - $gettmptablerecord->num_rows; 
								$totalINVAmount = 0;  $computedinvamount = 0; 
								
								if($drowhdr != $linerow) #validate header counter and detail counter
								{
								   $witherror = 1;
								   tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Line Header Counter not tally with total line details|0|'.$userid."\r\n",$syncProcessLog);
								}
								while($bsd = $gettmptablerecord->fetch_object() )
								{
									$gettmptablerecorddtl = $database->execute(" select * 
																				 from   tmpsid_bds 
																				  where tmpsid_bds.type = 'D' 
																					and tmpsid_bds.custcode = '$bsd->custcode'  
																			  "); #get all detail record 
									if($gettmptablerecorddtl->num_rows > 0 )
									{
										$totalquantity = 0; $totalregularprice = 0; $totalcsp = 0; $totaldealerdiscount = 0; $totalAD = 0; $totalADPP = 0; $totalVAT = 0;
										
										while($bsdetail = $gettmptablerecorddtl->fetch_object() )
										{ #amount validation of branch sales/export sales/b2b sales = header and detail and #quantity validation
											$totalquantity       = $totalquantity + $bsdetail->qty; 
											$totalregularprice   = $totalregularprice + $bsdetail->regularpricensv;
											$totalcsp            = $totalcsp + $bsdetail->cspnsv;
											$totaldealerdiscount = $totaldealerdiscount + $bsdetail->dealerdiscountnsv;
											$totalAD             = $totalAD + $bsdetail->additionaldisnsv;
											$totalADPP           = $totalADPP + $bsdetail->additionaldiscprev;
											$totalVAT            = $totalVAT + $bsdetail->vat;		
											$totalINVAmount      = $totalINVAmount + $bsdetail->invoiceamount;								
										}
										
										if($totalquantity <> $bsd->qty)
										{
											$witherror = 1;
										}
										
										$witherror  = cohesion($bsd->regularpricensv , $totalregularprice);
										if($witherror == 1) 
										{
											tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Error with Regular Price (Excluded vat)|0|'.$userid."\r\n",$syncProcessLog);
										}
										$witherror  = cohesion($bsd->additionaldisnsv, $totalAD);
										if($witherror == 1) 
										{
										   tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Error with Additional Discount (Excluded vat)|0|'.$userid."\r\n",$syncProcessLog);
										}
										$witherror  = cohesion($bsd->cspnsv, $totalcsp);
										if($witherror == 1) 
										{
										   tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Error with CSP (Excluded vat)|0|'.$userid."\r\n",$syncProcessLog);
										}
										$witherror  = cohesion($bsd->dealerdiscountnsv, $totaldealerdiscount);
										if($witherror == 1) 
										{
											tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Error with Dealer Discount (Excluded vat)|0|'.$userid."\r\n",$syncProcessLog);
										}
										$witherror  = cohesion($bsd->additionaldiscprev, $totalADPP);
										if($witherror == 1) 
										{
											tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Error with Additional Discount Prev|0|'.$userid."\r\n",$syncProcessLog);
										}
									}						  
								}	
							  }
							  
							 
							  #end of validation 
							  #echo $witherror.'witherror<br>';
							  
							  if($witherror == 1)
							  {
								 throw new Exception("An error occurred, please contact your system administrator."); 
							  }
							  else
							  {
								  #creation of record staging database - it includes mapping of fields 
								  #regular price per item - include parent kit and kit components
								  #break by product line - summarize by product line
								  $recctr = 0; $curr_custtype = '';
								  $sohdrq = $database->execute(" select * from tmpsid_bds where type = 'H' order by  custtype "); #get all header record 
								  
								  if($sohdrq->num_rows > 0 )
								  {  
							          
									  while($sohdr = $sohdrq->fetch_object() )
									  {
										  $bpchannel      = GetSettingValue($database, 'B/PChannel');
										  $totalINVAmount = $sohdr->invoiceamount;
										  $HeaderVat      = $sohdr->productline;
										  
										  if($sohdr->custcode == 'Branch')
										  { 
											  
											  $OrderType   = 'S1';
											  $OrderTypeID = '01';										  
										  }
										  else
										  {
											  $addressno   = GetAddressNo_Vendor($sohdr->custcode); 
											  if($sohdr->custtype == 3)
											  {
												  $OrderType   = 'SE';
												  $OrderTypeID = '02';
											  }
											  else if ($sohdr->custtype == 4)
											  {
												  $OrderType   = 'SB';
												  $OrderTypeID = '03';
											  }
											  else
											  {
												  tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Invalid Customer Type:'.$sohdr->custtype.'|0|'.$userid."\r\n",$syncProcessLog); 
												  throw new Exception("An error occurred, please contact your system administrator."); 		
											  }
											  
											  $branchID = sprintf("%02d",$sohdr->custtype); 
										  }
										  
										  if(($curr_custtype != $sohdr->custtype))
										  {
												  $recctr     = GetRecordCountSO($OrderType,$addressno,$filedate)  + 1;
										  }
										  else
										  {
												  $recctr     = $recctr + 1;
										  }
										  
										  if($addressno == '')
										  {
											  $witherror = 1;
											  tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|"Invalid Address No|0|'.$userid."\r\n",$syncProcessLog);							  
										  }
										  
										  $DOC_NO_ORI = '35-'.$branchID.'-'.$filedate2.'-'.$OrderTypeID.'-'.$recctr;
										  
										  #create for the details - items 
										  $sodtlq = $database->execute(" select * 
																		 from   tmpsid_bds 
																		  where tmpsid_bds.type = 'D' 
																			and tmpsid_bds.custcode = '$sohdr->custcode'  
																	   "); #get all detail record 
										 
										  #details per PL - discounts
										  $totaldiscounts = 0;
										  $soPLq = $database->execute(" SELECT ROUND(baseprice,2) baseprice, ROUND(csp,2) csp,ROUND((baseprice-csp),2) cd,ROUND(bd,2) bd,ROUND(ad,2) ad,ROUND(adpp,2) adpp,pl
																		FROM
																		(
																			SELECT SUM(tmp.regularpricensv) baseprice, SUM(tmp.cspnsv) csp, SUM(tmp.dealerdiscountnsv) bd, SUM(tmp.additionaldisnsv) ad,
																				   SUM(tmp.additionaldiscprev) adpp , tmp.productline pl
																			FROM tmpsid_bds tmp 
																			WHERE tmp.custcode = '$sohdr->custcode' 
																			AND tmp.type = 'D'
																			GROUP BY tmp.productline
																		) atbl ");
										  if($soPLq->num_rows > 0 )
										  {  
											  while($soPL = $soPLq->fetch_object())
											  {		  
												 $totaldiscounts = $totaldiscounts + $soPL->cd + $soPL->bd + $soPL->ad + $soPL->adpp;			  
											  }
										  }
										  #echo $witherror.'yyyy<br>';
										  $computedinvamount = $totaldiscounts * -1;
										  $computedinvamount = $computedinvamount + $HeaderVat;
										  #echo $computedinvamount.'------------'.$totalINVAmount.'<br>';
										  #create for the details - items 
										  $totalVAT  = 0; $linectr = 0;
										  $detailctr = 0;
										  if($sodtlq->num_rows > 0 ) 
										  {  
											  while($sodtl = $sodtlq->fetch_object())
											  {
												  
												  $Linetype  = $sodtl->producttypeid == 4 ? 'N' : 'S';
												  #$UOM      = $Linetype == 'N' ? 'EA' : 'ST';
												  $UOM       = '';
												  $linectr   = $linectr + 1;
												  $detailctr = $detailctr + 1;
												  $totalVAT  = $totalVAT + $sodtl->vat;
												  $regprice  = $sodtl->regularpricensv;
												  $computedinvamount = $computedinvamount + $regprice;
												  #echo $computedinvamount.'-'.$sodtl->JDE_ItemNumber.'<br>';
												  if($detailctr == $sodtlq->num_rows)
												  { 
													 $regprice = $regprice + ($totalINVAmount - $computedinvamount);
													 $computedinvamount = $computedinvamount + ($totalINVAmount - $computedinvamount);
													  #echo $computedinvamount.'-'.$sodtl->JDE_ItemNumber.'<br>';
													  
												  }

										          if(strlen($sodtl->productline) > 3)
												  {
													   $witherror = 1;
													   tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.$sodtl->rowctr.'|Invalid Line Details-Invalid Product Line of ITEM:'.$sodtl->JDE_ItemNumber.'|0|'.$userid."\r\n",$syncProcessLog);
												  }
												  
												  if(strlen($sodtl->JDE_ItemNumber)  == '' )
												  {
													   $witherror = 1;
													   tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.$sodtl->rowctr.'|Invalid Line Details-Product Code does not exist-c:'.$sodtl->productcode.'|0|'.$userid."\r\n",$syncProcessLog);
												  }
										          #echo $witherror.'ssssss<br>';
												  #echo $computedinvamount.'-'.$sodtl->JDE_ItemNumber.'<br>';
												  $createSODTL = createSODTL('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
															  $linectr,$sodtl->JDE_ItemNumber,$UOM,$sodtl->qty,$regprice,$Linetype,$location,$sodtl->productline,$sodtl->nsv);	
												  #echo $computedinvamount.'-'.$sodtl->JDE_ItemNumber.'<br>';
												  #echo $linectr.'WWWWW<br>';
												  if(!$createSODTL)
												  {
													  $witherror = 1;
													  tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.$linectr.'|"Invalid Line Details|0|'.$userid."\r\n",$syncProcessLog);
												  }																					  
											  }
										  }
										  
										  
										  #details per PL - discounts
										  $soPLq = $database->execute(" SELECT ROUND(baseprice,2) baseprice, ROUND(csp,2) csp,ROUND((baseprice-csp),2) cd,ROUND(bd,2) bd,ROUND(ad,2) ad,ROUND(adpp,2) adpp,pl
																		FROM
																		(
																			SELECT SUM(tmp.regularpricensv) baseprice, SUM(tmp.cspnsv) csp, SUM(tmp.dealerdiscountnsv) bd, SUM(tmp.additionaldisnsv) ad,
																				   SUM(tmp.additionaldiscprev) adpp , tmp.productline pl
																			FROM tmpsid_bds tmp 
																			WHERE tmp.custcode = '$sohdr->custcode' 
																			AND tmp.type = 'D'
																			GROUP BY tmp.productline
																		) atbl ");
										  if($soPLq->num_rows > 0 )
										  {  
											  while($soPL = $soPLq->fetch_object())
											  {
												 
												 $UOM = '';
												 if($OrderType == 'SB')
												 {
													 $linectr = $linectr + 1;
													 createSODTL('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
															  $linectr,'972001001',$UOM,-1,$soPL->cd * -1,'N',$location,$soPL->pl);   #cd
												 }
												 else
												 {
													$linectr = $linectr + 1; 
													createSODTL('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
															  $linectr,'971001002',$UOM,-1,$soPL->cd * -1,'N',$location,$soPL->pl);   #cd										 
												    $linectr = $linectr + 1;
													createSODTL('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
															  $linectr,'971001003',$UOM,-1,$soPL->bd * -1,'N',$location,$soPL->pl);   #bd
												    $linectr = $linectr + 1;
													createSODTL('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
															  $linectr,'971001004',$UOM,-1,$soPL->ad * -1,'N',$location,$soPL->pl);   #ad
												    $linectr = $linectr + 1;
													createSODTL('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
															  $linectr,'971001005',$UOM,-1,$soPL->adpp * -1,'N',$location,$soPL->pl); #adpp 
												 }
											  }
										  }
										  $UOM = '';
										  #Line detail for total vat
										  $linectr = $linectr + 1; 
										  createSODTL('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr,
															  $linectr,'971001001',$UOM,1,$HeaderVat,'N',$location,'');				  
										  $witherror1  = cohesion($totalINVAmount ,$computedinvamount);
										  
										  if($witherror1 == 1)
										  {
											  $witherror = 1;
											  tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Invalid Invoice Amount'.$totalINVAmount.':'.$computedinvamount.'|0|'.$userid."\r\n",$syncProcessLog);
											  throw new Exception("An error occurred, please contact your system administrator."); 
										  }
										  
										  if($witherror == 1)
										  {
											  throw new Exception("An error occurred, please contact your system administrator."); 
										  }
										  
										  #echo 'HEADER';
										  $createSOHDR = createSOHDR('00035',$OrderType,'3505001',$DOC_NO_ORI,$addressno,$addressno,$filedate,$linectr);
										  if(!$createSOHDR)
										  {
											  $witherror = 1;
											  tpi_error_log($filedate.'|'.$filebranch.'|SID|BDS|'.$file.'|'.'0'.'|Invalid Line Header|0|'.$userid."\r\n",$syncProcessLog);
										  }
                                          #echo 'HEADER2';
										  createSIDFiles($filetype,$filebranch,$filedate,$userid,$file,$linectr);	
										  
										  insertLOGs('SID',$filetype,$file,$linectr,'Successfully processed BDS File','1',$userid,$filebranch,$filedate); 

										  $curr_custtype = $sohdr->custtype;									  
									  } 
								  }
							  } 
						  }	
						  //echo ' Successfully processed:'.$filebranch.'-'.$filedate.'-'.$file.'-'.$filedir.'-'.$bdspathP.'<br>';
						  
						  if($OSTYPE == 'WINDOWS')
						  {   #if successfully processed move file to $bdspath-P and delete it and create SID files monitoring  
							  #copy($filedir, $bdspathP);
							  $cmd = 'move /Y "'.$filedir.'" "'.$bdspathP.'"';
							  #echo $cmd.'<br>';
                              exec($cmd);
						  }
						  else
						  {
							  exec("mv ".$filedir." ".$bdspathP);
							  exec("rm -rf ".$filedir);
						  }
						  
						  $database->commitTransaction();
					  }
					  catch(Exception $e)
					  {
						$database->rollbackTransaction();
						$errmsg = $e->getMessage()."\n";
						$database->beginTransaction();
						createlogs($syncProcessLog);
						tpi_file_truncate($syncProcessLog);
						$database->commitTransaction();
					  }
				   }
			    }
			}
		}
	}
	
?>