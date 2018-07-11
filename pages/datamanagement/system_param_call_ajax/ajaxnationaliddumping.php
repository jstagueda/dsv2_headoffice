
<?php
	include "../../../initialize.php";
	include "../../../customercreation.php";
	include IN_PATH.DS."scproductlistuploader.php";
	global $database;

	$backupname = 'nationalid-'.date("Y-m-d").'_'.date("Hi").'.sql.gz'; 
	
	
	$database->execute("
	                      UPDATE tmpcustfornatid
                             SET tmpcustfornatid.HashKey = MD5(CONCAT(tmpcustfornatid.Branch,tmpcustfornatid.nationalid,tmpcustfornatid.dsv2code,tmpcustfornatid.dsv1code,tmpcustfornatid.networkcode,tmpcustfornatid.mLevel,tmpcustfornatid.StatusID,tmpcustfornatid.bmcdate,tmpcustfornatid.ffdate1,tmpcustfornatid.PayoutOrOffset,tmpcustfornatid.Vatable,IFNULL(tmpcustfornatid.TranType,'')))
					 ");
					 
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
				<td width="50%">Dump File Location:</td>
				<td width="50%">/var/www/html/dumpfiles/nationalid/'.$backupname.'</td>
			</tr>
		 </table>';


