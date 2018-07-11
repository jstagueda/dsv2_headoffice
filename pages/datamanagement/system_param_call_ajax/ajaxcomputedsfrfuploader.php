
<?php

	include "../../../initialize.php";
	
	include IN_PATH.DS."scproductlistuploader.php";
	
	global $database;
	

if(!($_FILES['file']['size']==0))
{
		$page      = (isset($_POST['page']))?$_POST['page']:1;
	
		$pagerows=30;  		//number of rows that would be displayed per page that is divided by pagination
		$start = ($page > 1) ? ($page - 1) * $pagerows : 0; //start of the data to be shown
		$limit = 'limit '.$start.','.$pagerows;			 //this will set the limit for the querry
		
		if($_FILE['file']['error'] == 0)  //checks if theres an error and continues if there is none
		{
			
			$filetmp = $_FILES['file']['tmp_name']; //this will cut the extension from the filename
			$filename = $_FILES['file']['name'];
			$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$withoutExt_a = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$withoutExt = $withoutExt.uniqid();
			$csv = '/var/www/html/TPI-DATA/data/'.$withoutExt.'.csv';
			
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			$userid = $_SESSION['user_id'];
			$campaigncode = substr($withoutExt_a,0,5);
			
			#validate if campaign is existing
			$cmpq = $database->execute("
										 SELECT MONTH(campaign.startdate) mnt, YEAR(campaign.startdate) yr
										 FROM campaign WHERE SUBSTRING(`code`,1,5) = '$campaigncode'
									  "); 	
            if($cmpq->num_rows)
			{		
				while($c = $cmpq->fetch_object())
				{	
					$campaignmonth = $c->mnt;
					$campaignyear  = $c->yr;
					$valid = 'yes';
				}
			}
			else
			{
				$valid = 'no';
				echo 'Invalid campaign';
			}
			
			#file extension validation
			if($ext != 'csv')
			{
			   $valid = 'no';	
			   $errorlist = 'Invalid File';
			}
									  
			if($valid != 'no')
			{
				
			   $fileData = trim(file_get_contents($_FILES['file']['tmp_name']));
			   $rows = explode("\n", $fileData);
		 	   $first_row = true;
			   $totalAmount = 0;

               #file validation 
               foreach ($rows as $row)
			   {
				   $xdata      = explode('|',trim($row));
				   $branchcode = trim($xdata[0]);
				   $nationalid = trim($xdata[1]);
				   $bmcode     = trim($xdata[2]);
				   $bmname     = trim($xdata[3]);
				   $amount     = trim($xdata[4]);
				   $date       = trim($xdata[5]);
				   $counter    = $counter + 1;
				   
				   #data validation - check if customer is existing
				   $custq = $database->execute("
													   SELECT * 
														FROM customer c 
														INNER JOIN branch b ON b.id = c.branchid
														WHERE c.DSV1_IGSCode = '$bmcode'
														AND b.code = '$branchcode'
														AND c.HOGeneralID = '$nationalid'
												 ");
					if(!$custq->num_rows > 0)
					{ 	
					   $errorlist = $errorlist.'Invalid Customer:'.$branchcode.':'.$nationalid.':'.$bmcode.':'.$bmname.'\n';	
					}
			   }

			   
			   if($errorlist == '')
			   {				   
				   try 
				   {
					   foreach ($rows as $row)
					   {
						  $xdata      = explode('|',trim($row));
						  $branchcode = trim($xdata[0]);
						  $nationalid = trim($xdata[1]);
						  $bmcode     = trim($xdata[2]);
						  $bmname     = trim($xdata[3]);
						  $amount     = trim($xdata[4]);
						  $date       = trim($xdata[5]);
						  $counter    = $counter + 1;
						  
						  $consoearningsq = $database->execute(" SELECT * 
																 FROM HOconsolidatedearnings c
																 WHERE c.CampaignCode = '$campaigncode' 
																 AND c.HOGeneralID    = '$nationalid'  
																 AND c.CustomerCode   = '$bmcode'
																 AND c.Branch         = '$branchcode'  
															  ");
						  if($consoearningsq->num_rows)
						  { 	
							 while($conso = $consoearningsq->fetch_object())
							 {	
								$consoID = $conso->ID;
								
								$database->execute(" UPDATE HOconsolidatedearnings 
													 SET HOconsolidatedearnings.amount          = $amount,
														 HOconsolidatedearnings.Effective_Date  = '$date',
														 HOconsolidatedearnings.updated_by      = $userid,
														 HOconsolidatedearnings.updated_date    = NOW(),
														 HOconsolidatedearnings.HashField       = MD5(CONCAT(HOconsolidatedearnings.HOGeneralID,HOconsolidatedearnings.Effective_Date,HOconsolidatedearnings.Amount,HOconsolidatedearnings.CampaignCode,HOconsolidatedearnings.CampaignMonth,HOconsolidatedearnings.CampaignYear,HOconsolidatedearnings.CustomerCode))
													 WHERE id = $consoID
												  ");
								
							}
						  }
						  else
						  {
							   $database->execute("
												   INSERT HOconsolidatedearnings 
													  set HOconsolidatedearnings.CampaignCode    = '$campaigncode',
														  HOconsolidatedearnings.Amount          = $amount,
														  HOconsolidatedearnings.Branch          = '$branchcode',
														  HOconsolidatedearnings.CampaignMonth   = $campaignmonth,
														  HOconsolidatedearnings.CampaignYear    = $campaignyear,
														  HOconsolidatedearnings.Created_By      = $userid,
														  HOconsolidatedearnings.CustomerCode    = '$bmcode',
														  HOconsolidatedearnings.EarningType     = 1,
														  HOconsolidatedearnings.Effective_Date  = '$date',
														  HOconsolidatedearnings.Enrollment_Date = now(), 
														  HOconsolidatedearnings.HOGeneralID     = '$nationalid',
														  HOconsolidatedearnings.updated_by      = $userid,
														  HOconsolidatedearnings.updated_date    = now(), 
														  HOconsolidatedearnings.Status          = 7,
														  HOconsolidatedearnings.HashField       = MD5(CONCAT(HOconsolidatedearnings.HOGeneralID,HOconsolidatedearnings.Effective_Date,HOconsolidatedearnings.Amount,HOconsolidatedearnings.CampaignCode,HOconsolidatedearnings.CampaignMonth,HOconsolidatedearnings.CampaignYear,HOconsolidatedearnings.CustomerCode))
												   ");
						  }
						  $totalAmount = $totalAmount + $amount;					  
						  #create consolidatedearning at ho - if record is existing update the record if not create the record 
					  }
				   } 
				   catch (Exception $e) 
				   {
						$database->rollbackTransaction();
						$errmsg = $e->getMessage()."\n";
						
						redirect_to("../index.php?pageid=712.2&errmsg=$errmsg");
				   }
			   }
			}
				 	 
			if($errorlist != '')
			{			 
				echo '<script language="javascript">';
				echo 'alert("'.$errorlist.'")';
				echo '</script>';	
                echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " >  
					    <tr class="trheader" >
							<td width="50%">Total Uploaded BM Accounts </td>
							<td width="50%">0</td>
						</tr>
					 </table>';	
	        }	
			else
			{
                
		        $backupname = 'consosfrf-'.$campaigncode.'-'.date("Y-m-d").'_'.date("Hi").'.sql.gz';
	
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
				
				
	            $condition = 'HOconsolidatedearnings --where='.'"CampaignCode='."'$campaigncode'".'"';
				
				exec('mysqldump '.$DB_USER_BACKUP.' '.$DB_PASS_BACKUP.' '.$DB_NAME.' '.$condition.' | gzip -9 > /var/www/html/dumpfiles/consolidatedsfrf/'.$backupname);
	
				echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " >  
					     <tr class="trheader" >
							<td width="50%">Total Uploaded BM Accounts  </td>
							<td width="50%">'.$counter.'</td>
						 </tr>
						 <tr class="trheader" >
							<td width="50%">Total Amount </td>
							<td width="50%">'.$totalAmount.'</td>
						 </tr>
						  <tr class="trheader" >
							<td width="50%">Dump Files  </td>
							<td width="50%">/var/www/html/dumpfiles/consolidatedsfrf/'.$backupname.'</td>
						 </tr>
					 </table>';			  
			}				
    }
}		
else
{
				
echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " >  
			<tr class="trheader" >
			<td width="50%">Total Uploaded Products </td>
			<td width="50%">0</td>
			</tr>
	  </table>';

}


