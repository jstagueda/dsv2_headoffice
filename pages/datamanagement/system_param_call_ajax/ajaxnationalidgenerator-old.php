
<?php
	include "../../../initialize.php";
	include "../../../customercreation.php";
	include IN_PATH.DS."scproductlistuploader.php";
	global $database;
	
	$dir  = "/var/www/html/sid/sid_files/disk1/db/ds.dt/BMList";
	$dir2 = "/var/www/html/sid/sid_files/disk1/db/ds.dt/BMList-P";
	
	$database->execute("DELETE FROM tmpcustfornatid WHERE trantype IS NULL OR trantype  not in ('APPT','AFF','HOMODIFICATION','BMC-E','AFF-MIBM')  ");
	$userid = $_SESSION['user_id'];
	
	// Open a directory, and read its contents
	if (is_dir($dir))
	{
	  if ($dh = opendir($dir))
	  {
		while (($file = readdir($dh)) !== false)
		{
		  if($file == '.' || $file == '..' )
		  {
			 
		  }
		  else
		  {
			 $filedir = $dir.'/'.$file;
			 
			 $filecontent = fopen($filedir, 'r');  //open file
			 $recctr2 = 0;
			 while(($f = fgets($filecontent)) !== false)	
			 {			
				$fields=explode('|',$f);
				$recctr2           = $recctr2 + 1;
				$bcode            = trim($fields[0]);
				$filebranch       = trim($fields[0]);
				$natid            = trim($fields[1]);
				$natid2           = trim($fields[1]);
				$custcode         = trim($fields[2]);
				$networkcode      = trim($fields[4]);
				$mLevel           = trim($fields[5]);
				$name             = trim($fields[6]);
				$lastname         = trim($fields[7]);
				$firstname        = trim($fields[8]);
				$middlename       = trim($fields[9]);
				$Birthdate        = trim($fields[10]);
				$StatusIDB        = trim($fields[12]);
				$IsEmployee       = trim($fields[14]); 
				$IsHomeGrownIBM   = trim($fields[15]); 
				$MBranchID        = trim($fields[16]);
			
				$tmdate               = trim($fields[21]);
				$PayoutOrOffset       = trim($fields[22]);
				$Vatable              = trim($fields[23]);
				$Vatable_Effectivity  = trim($fields[24]);
				$ApplicableBonusCodes = trim($fields[25]);
				$bank_acct_name       = trim($fields[26]);
				$bank_acct_num        = trim($fields[27]);
				$bank_name            = trim($fields[28]);
				$ExcludedinIBMCount   = trim($fields[30]);    
				$NickName         = trim($fields[31]);  
				$TelNo            = trim($fields[32]);  
                $MobileNo         = trim($fields[33]); 
				$StreetAdd        = trim($fields[34]);
				$AreaID           = trim($fields[35]);
				$ZipCode          = trim($fields[36]);
                $tpi_ZoneID       = trim($fields[37]); 
				$tpi_GSUTypeID    = trim($fields[38]); 
				$recruiter        = trim($fields[39]); 
				$Gender           = trim($fields[41]); 
				$CreditTermID     = trim($fields[42]); 
				$AvailableCL      = trim($fields[43]);
				$withOR           = trim($fields[44]);
				
				
				$TIN        = trim($fields[45]); 
				$rem        = trim($fields[46]);
				$rem2       = trim($fields[47]);
				$BID        = 0;
				
				$recctr   = $recctr + 1;
				
				if($rem == 'AFF' || $rem == 'APPT' || $rem2 == 'HOMODIFICATION' )
				{
					$database->execute("DELETE FROM tmpcustfornatid WHERE  branch = '$bcode' and dsv2code = '$custcode' and trantype  in ('APPT','AFF','HOMODIFICATION') ");
				}
				
				#get branchid 
				$branchq = $database->execute("SELECT b.id ID , b.code `CODE`  
												FROM branch b
												WHERE b.code = '$bcode'
											  ");
				 if($branchq->num_rows > 0 )
				 {
					 while($b = $branchq->fetch_object() )
					 {
						 $BID = $b->ID;
					 }	 
				 }	
				#echo 'ggg'.$BID.'ggg'.$custcode.'<br>';
				#validate if record exist 
				$custq = $database->execute("SELECT * 
											 FROM customer c
											 WHERE c.BranchID = $BID
											 AND c.Code = '$custcode' 
											 #and c.hogeneralid = '$natid'
										  ");  		 
				if(!$custq->num_rows)
				{ 
                   $custtype = 'N';		
                   #$natid = '';				   
				}
				else
				{
					while($cq = $custq->fetch_object() )
				    {
						$natid          = $cq->HOGeneralID;
                        $custtype       = 'O';
						$CustomerID     = $cq->CustomerID;
						$StatusID       = $cq->StatusID;
						$CustomerTypeID = $cq->CustomerTypeID;
						#echo 'aa<br>';
						#update customer 
						$database->execute("
											UPDATE customer c
											SET c.name           = '$name',
												c.lastname       = '$lastname',
												c.firstname      = '$firstname',
												c.middlename     = '$middlename',
												c.birthdate      = '$Birthdate',
												c.customertypeid = '$mLevel',
												c.tin            = '$TIN',
												c.statusid       = $StatusIDB,
												c.isemployee     = $IsEmployee,
												c.isHomeGrownIBM = $IsHomeGrownIBM, 
												c.mBranchID      = '$MBranchID'
											where c.customerid   = $CustomerID
										   ");  	
						#echo 'bb<br>';
                        #tpi_customerdetails 						
					    $database->execute("
											UPDATE tpi_customerdetails d
											SET d.nickname        = '$NickName',
												d.telno           = '$TelNo',
												d.mobileno        = '$MobileNo',
												d.streetadd       = '$StreetAdd',
												d.areaid          = '$AreaID',
												d.zipcode         = '$ZipCode',
												d.tpi_zoneid      = if('$tpi_ZoneID' = '','1','$tpi_ZoneID'),
												d.tpi_gsutypeid   = '$tpi_GSUTypeID',
												d.tpi_ibmcode     = '$networkcode',
												d.gender          = '$Gender'
										    where d.customerid = $CustomerID
										   ");  
					    #echo 'cc<br>';
					    #sfm_manager
						$database->execute("
											UPDATE sfm_manager s
											SET s.mLevel              = '$mLevel',
												s.first_name          = '$firstname',
												s.last_name           = '$lastname',
												s.middle_name         = '$middlename',
												s.birth_date          = '$Birthdate',
												s.tin                 = '$TIN',
												s.credit_term_id      = $CreditTermID,
												s.credit_limit        = $AvailableCL,
												s.payoutoroffset      = $PayoutOrOffset,
												s.vatable             = $Vatable,
												s.vatable_effectivity = '$Vatable_Effectivity',
												s.applicablebonuscodes = '$ApplicableBonusCodes',
												s.bank_acct_num       = '$bank_acct_num',
												s.bank_acct_name      = '$bank_acct_name' ,
												s.bank_name           = '$bank_name' ,
												/*s.appointment_date    = */
												s.termination_date    = IF('$tmdate' ='',NULL ,'$tmdate'),
												s.excludedinIBMCount  = $ExcludedinIBMCount,
												s.withOR              = $withOR
											where s.mid = $CustomerID
										  ");
						 #echo 'dd<br>';	
						 #tpi_credit
						 $database->execute("
											 UPDATE tpi_credit
												SET tpi_credit.CreditTermID    = $CreditTermID,
													tpi_credit.CalculatedCL    = $AvailableCL,
													tpi_credit.RecommendedCL   = $AvailableCL,
													tpi_credit.ApprovedCL      = $AvailableCL,
													tpi_credit.AvailableCL     = $AvailableCL,
													tpi_credit.ARBalance	   = 0
											 where tpi_credit.customerid =  $CustomerID
										   ");
						#echo 'ee<br>';
						#sfm_manager_network - check first if manager code value is not the same with current value 
						 $manager_codeq = $database->execute(" select sfm.manager_code manager_code 
																 from sfm_manager_networks sfm
																 where sfm.manager_network_codecustID = $CustomerID
															 ");
						 if($manager_codeq->num_rows > 0 )
						 {
							 while($m = $manager_codeq->fetch_object() )
							 {
								 $oldmanager_code = $m->manager_code;
							 }	 
						 }
						 
                         if($oldmanager_code == '')
                         {
							 
						 }
                         else
						 {
							 if($oldmanager_code != $networkcode)
							 {
								 #echo 'bb.2<br>';
								 $database->execute("SET FOREIGN_KEY_CHECKS = 0");
								 $database->execute(" 
													 UPDATE sfm_manager_networks
													 SET sfm_manager_networks.manager_code_custID = ifnull((SELECT customerid FROM customer  WHERE customer.code = '$networkcode' and customer.branchid = $BID order by statusid limit 1 ),0),
														 sfm_manager_networks.manager_code        = '$networkcode'
													 WHERE  sfm_manager_networks.manager_network_codecustID = $CustomerID
												    ");
								 #echo 'bb.1<br>';
								 #tpi_customerdetails 						
								 $database->execute("
													UPDATE tpi_customerdetails d
													SET d.tpi_recruiterid = ifnull((SELECT customerid FROM customer  WHERE customer.code = '$custcode' and customer.branchid = $BID order by statusid limit 1 ),0)
													where d.customerid = $CustomerID
												   ");  					
							 }
						 }							 	
						#echo 'ff<br>';
						#sfmapphist
						if($CustomerTypeID != $mLevel )
						{
							$database->execute(" 
													INSERT INTO sfm_appthist
													SET sfm_appthist.HOGeneralID       = '$natid',
														sfm_appthist.CustomerID        = '$CustomerID',
														sfm_appthist.BranchID          = '$BID',
														sfm_appthist.sfm_level         =  $mLevel,
														sfm_appthist.appointment_date  =  now(),
														sfm_appthist.status            = 7,
														sfm_appthist.created_date      =  now(), 
														sfm_appthist.created_by        = $userid,
														sfm_appthist.updated_date      = now(), 
														sfm_appthist.updated_by        = $userid
											   ");	
						}
						#echo 'gg<br>';
						#tpi_rcustomerstatus
						#get old tmdate 
						 $oldtmdateq = $database->execute(" SELECT DATE(enrollmentdate) oldtmdate
															FROM tpi_rcustomerstatus 
															WHERE customerid = $CustomerID
															AND CustomerStatusID = 5
															ORDER BY id DESC LIMIT 1
														 ");
						 if($oldtmdateq->num_rows > 0 )
						 {
							 while($m = $oldtmdateq->fetch_object() )
							 {
								 $oldtmdate = $m->oldtmdate;
							 }	 
						 }
						 
						 if($oldtmdate != $tmdate && $StatusIDB == 5 && $tmdate != '')
						 {
							 $database->execute(" 
												INSERT INTO tpi_rcustomerstatus
												 SET tpi_rcustomerstatus.BranchID          = '$BID',
													 tpi_rcustomerstatus.CustomerID        = '$CustomerID',
													 tpi_rcustomerstatus.Changed           = 1,
													 tpi_rcustomerstatus.CustomerStatusID  = '$StatusIDB',
													 tpi_rcustomerstatus.EnrollmentDate    = '$tmdate',
													 tpi_rcustomerstatus.FromBranch        = '$BID',
													 tpi_rcustomerstatus.FromIBM           = '$CustomerID',
													 tpi_rcustomerstatus.HOGeneralID       = '$natid',
													 tpi_rcustomerstatus.IsAddOther        = '0',
													 tpi_rcustomerstatus.ISPRStatus        = '0',
													 tpi_rcustomerstatus.IsReactivated     = '0',
													 tpi_rcustomerstatus.IsRemoveOther     = '0',
													 tpi_rcustomerstatus.ToBranch          = '$BID',
													 tpi_rcustomerstatus.ToIBM             = '$CustomerID',
													 tpi_rcustomerstatus.CreatedBy         =  $userid
											  ");
						 }
						

						if($StatusID != $StatusIDB)
						{
							$database->execute(" 
												INSERT INTO tpi_rcustomerstatus
												 SET tpi_rcustomerstatus.BranchID          = '$BID',
													 tpi_rcustomerstatus.CustomerID        = '$CustomerID',
													 tpi_rcustomerstatus.Changed           = 1,
													 tpi_rcustomerstatus.CustomerStatusID  = '$StatusIDB',
													 tpi_rcustomerstatus.EnrollmentDate    = now(),
													 tpi_rcustomerstatus.FromBranch        = '$BID',
													 tpi_rcustomerstatus.FromIBM           = '$CustomerID',
													 tpi_rcustomerstatus.HOGeneralID       = '$natid',
													 tpi_rcustomerstatus.IsAddOther        = '0',
													 tpi_rcustomerstatus.ISPRStatus        = '0',
													 tpi_rcustomerstatus.IsReactivated     = '0',
													 tpi_rcustomerstatus.IsRemoveOther     = '0',
													 tpi_rcustomerstatus.ToBranch          = '$BID',
													 tpi_rcustomerstatus.ToIBM             = '$CustomerID',
													 tpi_rcustomerstatus.CreatedBy         =  $userid
											  ");
						}
						#echo 'hh<br>';
					}
				}
				
				if( /*($rem != 'AFF' && $rem != 'APPT' ) ||*/ $natid2 == '' )
				{
					#echo 'ggg'.$BID.'ggg'.$custcode.'<br>';
					$database->execute("INSERT INTO tmpcustfornatid
										SET tmpcustfornatid.ZipCode = ".'"'.trim($fields[36]).'"'." ,
											tmpcustfornatid.tpi_ZoneID = ".'"'.trim($fields[37]).'"'." ,
											tmpcustfornatid.tpi_GSUTypeID = ".'"'.trim($fields[38]).'"'." ,
											tmpcustfornatid.recruiter = ".'"'.trim($fields[39]).'"'." ,
											tmpcustfornatid.Remarks  = ".'"'.trim($fields[40]).'"'." ,
											tmpcustfornatid.Gender =  ".'"'.trim($fields[41]).'"'." ,
											tmpcustfornatid.CreditTermID = ".'"'.trim($fields[42]).'"'." ,
											tmpcustfornatid.AvailableCL = ".'"'.trim($fields[43]).'"'." ,
											tmpcustfornatid.withOR = ".'"'.trim($fields[44]).'"'." ,
											tmpcustfornatid.TIN = ".'"'.trim($fields[45]).'"'." ,

 										    tmpcustfornatid.mLevel = ".'"'.trim($fields[5]).'"'." ,
											tmpcustfornatid.igsname = ".'"'.trim($fields[6]).'"'." ,
											tmpcustfornatid.FirstName = ".'"'.trim($fields[7]).'"'." ,
											tmpcustfornatid.LastName = ".'"'.trim($fields[8]).'"'." ,
											tmpcustfornatid.MiddleName = ".'"'.trim($fields[9]).'"'." ,
											tmpcustfornatid.Birthdate = ".'"'.trim($fields[10]).'"'." ,
											tmpcustfornatid.CustomerClassID = ".'"'.trim($fields[11]).'"'." ,
											tmpcustfornatid.StatusID  = ".'"'.trim($fields[12]).'"'." ,
											tmpcustfornatid.cenrolldate = ".'"'.trim($fields[13]).'"'." ,
											tmpcustfornatid.IsEmployee = ".'"'.trim($fields[14]).'"'." ,
											tmpcustfornatid.IsHomeGrownIBM = ".'"'.trim($fields[15]).'"'." ,
											tmpcustfornatid.MBranchID  = ".'"'.trim($fields[16]).'"'." ,
											tmpcustfornatid.date_added = ".'"'.trim($fields[29]).'"'." ,
											tmpcustfornatid.ExcludedinIBMCount = ".'"'.trim($fields[30]).'"'." ,
											tmpcustfornatid.NickName = ".'"'.trim($fields[31]).'"'." ,
											tmpcustfornatid.TelNo = ".'"'.trim($fields[32]).'"'." ,
											tmpcustfornatid.MobileNo = ".'"'.trim($fields[33]).'"'." ,
											tmpcustfornatid.StreetAdd = ".'"'.trim($fields[34]).'"'." ,
											
											tmpcustfornatid.AreaID = ".'"'.trim($fields[35]).'"'." ,
										    tmpcustfornatid.EmployeeCode = ".'"'.trim($fields[17]).'"'." ,
											tmpcustfornatid.bmcdate = ".'"'.trim($fields[18]).'"'." ,
											tmpcustfornatid.ffdate1 = ".'"'.trim($fields[19]).'"'." ,
											tmpcustfornatid.aldate = ".'"'.trim($fields[20]).'"'." ,
											tmpcustfornatid.tmdate = ".'"'.trim($fields[21]).'"'." ,
											tmpcustfornatid.PayoutOrOffset = ".'"'.trim($fields[22]).'"'." ,
											tmpcustfornatid.Vatable = ".'"'.trim($fields[23]).'"'." ,
											tmpcustfornatid.Vatable_Effectivity = ".'"'.trim($fields[24]).'"'." ,
											tmpcustfornatid.ApplicableBonusCodes = ".'"'.trim($fields[25]).'"'." ,
											tmpcustfornatid.bank_acct_name = ".'"'.trim($fields[26]).'"'." ,
											tmpcustfornatid.bank_acct_num = ".'"'.trim($fields[27]).'"'." ,
											tmpcustfornatid.bank_name = ".'"'.trim($fields[28]).'"'." ,
										    tmpcustfornatid.Branch     = ".'"'.trim($fields[0]).'"'." ,
										    tmpcustfornatid.TranType = ".'"'.trim($fields[46]).'"'." ,
											tmpcustfornatid.nationalid = ".'"'.$natid.'"'." ,
											tmpcustfornatid.dsv2code = ".'"'.trim($fields[2]).'"'." ,
											tmpcustfornatid.dsv1code = ".'"'.trim($fields[3]).'"'." ,
											tmpcustfornatid.networkcode = ".'"'.trim($fields[4]).'"'." ,
											
											
											tmpcustfornatid.Accounttype = ".'"'.$custtype.'"'." 

										   ");
				}
				
			 }
			 
			
			 $filedate   = substr( $file, 0, 2 ).'/'.substr( $file, 2, 2 ).'/'.substr( $file, 4, 2 );
			 $filedate   = date('Y-m-d',strtotime($filedate));
			 
			 $database->execute("delete from tmpcustfornatid where tmpcustfornatid.branch = ''");
			 
			 if($filebranch != '')
			 {
				 $SID_Filesq = $database->execute(" SELECT * 
													from  SID_Files 
													where SID_Files.Branch    = '$filebranch'
													  and SID_Files.FileDate  = '$filedate'
													  and SID_Files.`Type`    = 'BMLIST'
												 ");
				 if($SID_Filesq->num_rows > 0 )
				 {
					 $database->execute("
										update SID_Files
										SET `NoofRecords`    = '$recctr2',
											`Updated_Date`   = now(),
											`Updated_By`     = $userid
										where SID_Files.Branch    = '$filebranch'
										  and SID_Files.FileDate  = '$filedate'
										  and SID_Files.`Type`    = 'BMLIST'	
									");
				 }
				 else
				 {
					 $database->execute("
										INSERT INTO SID_Files
										SET `Type`           = 'BMLIST', 
											`Branch`         = '$filebranch', 
											`FileDate`       = '$filedate', 
											`NoofRecords`    = '$recctr2', 
											`Uploaded_Date`  = now(),
											`Uploaded_By`    = $userid, 
											`Updated_Date`   = now(),
											`Updated_By`     = $userid
				  
									");
				 }
			 }
			 
			 #move file 
			 exec("mv ".$filedir." ".$dir2);
			 exec("rm -rf ".$filedir);
			 
			 #exec("mysqldump ".$DB_USER_BACKUP." ".$DB_PASS_BACKUP." ".$DB_NAME." tmpcustfornatid | gzip -9 > /var/www/html/dumpfiles/".$backupname);
		  }
		}
		closedir($dh);
	  }
	  
	  #get last nationalid value
	  $lastid = '';
	  $lastnatid = $database->execute(" SELECT s.id, s.settingcode, s.settingname, s.settingvalue 
										FROM settings s
										WHERE s.settingcode = 'NATIONALIDCTR' 
									  ");
	  if($lastnatid->num_rows > 0 )
	  {
		 while($nat = $lastnatid->fetch_object() )
		 {
			 $lastid = $nat->settingvalue;
		 }	 
	  }	  
	  
	  $custid = $lastid;
	  $ebmid  = '';
	  $tmpcustq = $database->execute("Select * 
						 from tmpcustfornatid where tmpcustfornatid.Accounttype = 'N' and  nationalid = '' ");
						 
	  if($tmpcustq->num_rows > 0 )
	  {
		 while($q = $tmpcustq->fetch_object() )
		 {
			 $value = '';  
			 $custid = $custid + 1;
			 $value = sprintf( '%08d', $custid );
			 $value = '1'.$value;
			 $ctr  = 0;
			 $nat_a = '';
			 $strlen = strlen($value );
			 $brcode = $q->Branch;
			 $custcode = $q->dsv1code;
			 
			 for( $i = 1; $i <= $strlen; $i++ ) 
			 {
				if($ctr == 2)
				{
					$ctr = 1;
				}
				else
				{
					$ctr = $ctr  + 1;
				}
				if($ctr == 1)
				{
					$intval = (substr($value,($strlen - $i ),1 ))  * 2;
					$nat_a = $nat_a.$intval; 
				}
				else
				{
					$intval = (substr($value,($strlen - $i ),1 ))  * 1;
					$nat_a = $nat_a.$intval;
				}
			 }
			   
			$strlen2 = strlen($nat_a);
			$sum = 0;
			for( $ii = 0; $ii <= $strlen2; $ii++ ) 
			{
				$num = substr($nat_a,$ii,1 );
				$sum = $sum + $num;
			}
			$checkdigit = 10- ($sum  %  10);
			if ($checkdigit == 10)
			{
				$checkdigit = 0;
			}
			
			$value = $value.$checkdigit;
			#echo $value.'xxxx'.$brcode.'vvv'.$custcode.'bbb'.$checkdigit.'<br>';
			
			$database->execute("  UPDATE tmpcustfornatid
									 SET tmpcustfornatid.nationalid = '$value'
								   WHERE tmpcustfornatid.Branch   = '$brcode'
									  AND tmpcustfornatid.dsv1code = '$custcode'
							  ");
		 }
		 $database->execute(" UPDATE settings 
							   SET settings.SettingValue = '$custid'
							   WHERE settingcode = 'NATIONALIDCTR' 
							"); 			
	   }

	  
	} 
	
	#customer record generator 
	createcustomer();
	
	$database->execute("
	                      UPDATE tmpcustfornatid
                             SET tmpcustfornatid.HashKey = MD5(CONCAT(tmpcustfornatid.Branch,tmpcustfornatid.nationalid,tmpcustfornatid.dsv2code,tmpcustfornatid.dsv1code,tmpcustfornatid.networkcode,tmpcustfornatid.mLevel,tmpcustfornatid.StatusID,tmpcustfornatid.bmcdate,tmpcustfornatid.ffdate1,tmpcustfornatid.PayoutOrOffset,tmpcustfornatid.Vatable,IFNULL(tmpcustfornatid.TranType,'')))
					 ");
	
	$backupname = 'nationalid-'.date("Y-m-d").'_'.date("Hi").'.sql.gz';
	
    $paramq = $database->execute(" SELECT s.settingvalue userbackup,
											   IFNULL((SELECT settingvalue FROM settings  WHERE settingcode = 'DB_PASS_BACKUP' ),'') pass,
											   IFNULL((SELECT settingvalue FROM settings  WHERE settingcode = 'DB_NAME' ),'') dbname
								   FROM settings s
								   WHERE s.settingcode = 'DB_USER_BACKUP'  
							   "); 
	if($paramq->num_rows > 0 )
	{
	   while($p = $paramq->fetch_object() )
	   {
		   $DB_USER_BACKUP = $p->userbackup;
		   $DB_PASS_BACKUP = $p->pass;
		   $DB_NAME        = $p->dbname;
		   
	   }	 
    }	   
	
	#exec("mysqldump -u".DB_USER_BACKUP." -p".DB_PASS_BACKUP." ".DB_NAME." --routines | gzip -9 > ".SITE_ROOT."/backupdatabase/".$TimeStamp."_".$BranchName.".sql.gz",$output,$result2); //working as of 9-15-2015
	#exec("mysqldump -uroot -ptbpi2014 --verbose dsv2_ho tmpcustfornatid | gzip -9 > /var/www/html/dumpfiles/".$backupname.");
	exec("mysqldump ".$DB_USER_BACKUP." ".$DB_PASS_BACKUP." ".$DB_NAME." tmpcustfornatid | gzip -9 > /var/www/html/dumpfiles/nationalid/".$backupname);

	
    echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " >  
					    <tr class="trheader" >
							<td width="50%">Total Records: </td>
							<td width="50%">'.$recctr.'</td>
						</tr>
						<tr class="trheader" >
							<td width="50%">Dump File Location:</td>
							<td width="50%">/var/www/html/dumpfiles/nationalid/'.$backupname.'</td>
						</tr>
		 </table>';
   

