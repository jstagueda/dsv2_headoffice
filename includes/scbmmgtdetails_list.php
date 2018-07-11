<?php
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}

	global $database; 
	
	if(!isset($_GET['prodlist']))
	{
		$_GET['prodlist'] = "";				
	}	
	
	$txnid  = $_GET['tid'];
	$userid = $session->user_id;

	
	$rs = $database->execute(" 
	                            SELECT  c.customerid CID,
								        b.code bcode,
										b.Id BID,
									    c.HOGeneralID , 
										trim(c.dsv1_igscode)  dsv1_igscode,
									    lev.desc_code mlevel,
									    c.code,
										c.customerTypeid ,
									    c.Name,
									    c.Birthdate,
									    c.BranchID,
										c.firstname firstname,
										c.lastname lastname,
									    net2.manager_code,
									    c2.Name mbmname,
									    IFNULL(( SELECT DATE(sfm_appthist.appointment_date) FROM sfm_appthist WHERE sfm_appthist.CustomerID = c.CustomerID AND sfm_appthist.sfm_level = 2 ORDER BY id DESC  LIMIT 1 ),'') bmcdate ,
										(SELECT DATE(sfm_appthist.appointment_date) 
										 FROM sfm_appthist 
										 WHERE sfm_appthist.CustomerID = c.customerid
										 AND sfm_appthist.sfm_level = if(c.customerTypeid = 4,4,3) ORDER BY id DESC LIMIT 1
										) bmfdate,
										s.Name stat, 
										c.statusid sid,
									    IF(sfm.PayoutOrOffset=1,'PAYOUT','OFFSET') PayoutOrOffset, 
										sfm.PayoutOrOffset PayoutOrOffset2,
									    IF(sfm.withOR=1,'WITH OR','WITHOUT OR') withOR,  
										sfm.withOR withOR2,
									    sfm.credit_limit,
									    ct.Duration,
										IF(sfm.Vatable=1,'VAT','NVAT') Vatable,
										sfm.Vatable Vatable2, 
										c.EnrollmentDate enrollment_date,
										ifnull(( select date(EnrollmentDate) from tpi_rcustomerstatus 
									         where tpi_rcustomerstatus.customerid = c.customerid and tpi_rcustomerstatus.customerstatusid = 5 order by id desc limit 1),'') tmdate
								FROM customer c 
								LEFT JOIN branch b ON b.id = c.BranchID
								INNER JOIN sfm_manager sfm ON sfm.mID = c.CustomerID
								LEFT JOIN sfm_manager_networks net2 ON net2.manager_network_codecustID = c.CustomerID
								LEFT JOIN customer c2 ON c2.customerid = net2.manager_code_custID
								INNER JOIN creditterm ct ON ct.ID =  sfm.credit_term_id
								INNER JOIN `status` s ON s.id = c.StatusID
								INNER JOIN sfm_level lev ON lev.codeID = c.CustomerTypeID
								WHERE c.CustomerID = $txnid
	                         ");
	
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{	
			$id           = $row->CID;		
			$bcode        = $row->bcode;
			$BID          = $row->BID;
			$tmdate       = $row->tmdate;
			$customerTypeid = $row->customerTypeid;
			$HOGeneralID  = $row->HOGeneralID;
			$mlevel       = $row->mlevel;		
			$code         = $row->code;
			$Name         = $row->Name;
			$Birthdate    = $row->Birthdate;
			$manager_code = $row->manager_code;
			$mbmname      = $row->mbmname;
			$bmcdate      = $row->bmcdate;
			$bmfdate      = $row->bmfdate; 
			$stat         = $row->stat;
			$sid          = $row->sid;
			$PayoutOrOffset  = $row->PayoutOrOffset;
			$PayoutOrOffset2 = $row->PayoutOrOffset2;
			$Vatable        = $row->Vatable;
			$Vatable2       = $row->Vatable2;
			$withOR         = $row->withOR;
			$withOR2        = $row->withOR2;
			$credit_limit   = $row->credit_limit;
			$Duration       = $row->Duration;
			$dsv1_igscode   = $row->dsv1_igscode;
			$firstname      = $row->firstname;
			$lastname       = $row->lastname;
			$enrollment_date = $row->enrollment_date;
			
		}
	}
	
	if(isset($_POST['btnCancel'])) 
	{
		redirect_to("index.php?pageid=180.3");	
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{			
	       
			$database->beginTransaction();
			$ctr = 0;
			$ctr_soh = 0;
			$cnt_soh = 0;
			$txtStartDate  = $_POST['txtStartDate'];
			$tmdate        = $_POST['txtTmDate'];
			$bmcdate       = $_POST['txtbmcDate'];
			
			$HOGeneralID   = $_POST['txtnatid'];
			$branchid      = $_POST['txtbranchid']; 
			$branch        = $_POST['txtbranch']; 
            $dsv1_igscode  = $_POST['txtbmcodedsv1']; 			
			$bmcode        = $_POST['txtbmcode']; 
			
			#echo $bmcdate.'ffff<br>';
             
		/*	$tmptxndate = strtotime($_POST['startDate']);
			$txndate = date("Y-m-d", $tmptxndate);*/
			$date = date("");
			$datetoday = date("m/d/Y");	
			
			if($tmdate != '' )
			{
				$tmdate   =  date('Y-m-d',strtotime($tmdate));
			}
			
			if($bmcdate != '')
			{
				$bmcdate  =  date('Y-m-d',strtotime($bmcdate));
			}
			
			if($txtStartDate != '')
			{
				$appdate =  date('Y-m-d',strtotime($txtStartDate));
			}
			
			$txtPayoutOrOffset = $_POST['txtPayoutOrOffset'];
			$txtVatable        = $_POST['txtVatable'];
			$txtWithOR         = $_POST['txtWithOR']; 
			$txtstatus         = $_POST['txtstatus'];  
			$txtlevel          = $_POST['txtlevel']; 
			
			
			#echo $bmcdate.'gggg<br>';
			
			if ($txtstatus == '')
			{
				$message = "Please choose BM status.";
				#redirect_to("index.php?pageid=180.1&errmsg=$message");				
			}
			else
			{
				$message = "BM details was successfully edited <br>"."BM: ".$code.'-'.$Name.'<br>'.'BMF Appointment Date:'.$appdate ;	
									
				#change status from 2 to 3 - customer , sfm_manager , change appointment_date of sfm_manager table
				$database->execute("update sfm_manager
									inner join customer c on c.customerid = sfm_manager.mid 
									set sfm_manager.mlevel           = $txtlevel, 
										sfm_manager.appointment_date = '$appdate',
										c.customertypeid             = $txtlevel,
										c.statusid                   = $txtstatus,
										sfm_manager.vatable          = $txtVatable,
										sfm_manager.withOR           = $txtWithOR,
										sfm_manager.PayoutOrOffset   = $txtPayoutOrOffset,
										sfm_manager.updated_by       = $userid, 
										c.updated_by                  = $userid,
										c.lastmodifieddate           = now()
								    where c.customerid = $txnid
                                   ");	
								   
				#allow changing of level from BMC to BMF , or BMF to BMC , or BMC to AL or BMF to AL 				   
								   
                #echo 'd<br>';							   			   
				#create sfm_appthist 
				$sfm_appthistbmcq = $database->execute(" select date(appointment_date) bmcdate from sfm_appthist where sfm_appthist.CustomerID = $txnid and sfm_appthist.sfm_level = 2 order by id desc limit 1");
				if ($sfm_appthistbmcq->num_rows)
				{
					#echo 'e<br>';	
					while ($bmcq = $sfm_appthistbmcq->fetch_object())
					{	
				        $oldbmcdate = $bmcq->bmcdate ; 
						if($oldbmcdate != $bmcdate)
						{
							$database->execute(" 
													INSERT INTO sfm_appthist
															SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
																sfm_appthist.CustomerID        = '$txnid',
																sfm_appthist.BranchID          = '$branchid',
																sfm_appthist.sfm_level         = 2,
																sfm_appthist.appointment_date  = '$bmcdate',
																sfm_appthist.status            = 7,
																sfm_appthist.created_date      = now(), 
																sfm_appthist.created_by        = $userid,
																sfm_appthist.updated_date      = now(), 
																sfm_appthist.updated_by        = 1
											  ");
						}
					}
				}
				else
				{
					#echo 'f<br>';	
					if($bmcdate != '')
					{
						#echo 'g<br>';	
						$database->execute(" 
														INSERT INTO sfm_appthist
																SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
																	sfm_appthist.CustomerID        = '$txnid',
																	sfm_appthist.BranchID          = '$branchid',
																	sfm_appthist.sfm_level         = 2,
																	sfm_appthist.appointment_date  = '$bmcdate',
																	sfm_appthist.status            = 7,
																	sfm_appthist.created_date      = now(), 
																	sfm_appthist.created_by        = $userid,
																	sfm_appthist.updated_date      = now(), 
																	sfm_appthist.updated_by        = 1
												  ");
					}
				}
				
				
				
	            if($customerTypeid == 4)
				{
					$type = 4;
				}
				else
				{
					$type = 3;
				}
				#echo 'h<br>';	
				$sfm_appthistbmfq = $database->execute(" select date(appointment_date) bmfdate from sfm_appthist 
				              where sfm_appthist.CustomerID = $txnid and sfm_appthist.sfm_level = $type order by id desc limit 1");
				if ($sfm_appthistbmfq->num_rows)
				{
					#echo 'i<br>';	
					while ($bmfq = $sfm_appthistbmfq->fetch_object())
					{	
				        $oldbmfdate = $bmfq->bmfdate ; 
						
					#echo $oldbmfdate.'<br>';	
						#echo $appdate.'xxxx'.'<br>';	
						if($oldbmfdate != $appdate )
						{
							#echo 'sdafsdfsdfsdf<br>';
							
							$database->execute(" 
													INSERT INTO sfm_appthist
															SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
																sfm_appthist.CustomerID        = '$txnid',
																sfm_appthist.BranchID          = '$branchid',
																sfm_appthist.sfm_level         = $type,
																sfm_appthist.appointment_date  = '$appdate',
																sfm_appthist.status            = 7,
																sfm_appthist.created_date      = now(), 
																sfm_appthist.created_by        = $userid,
																sfm_appthist.updated_date      = now(), 
																sfm_appthist.updated_by        = 1
											  ");
						}
					}
				}
				else
				{
					 #echo 'j<br>';	
					if($appdate != '')
					{
						 #echo 'k<br>';	
						$database->execute(" 
														INSERT INTO sfm_appthist
																SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
																	sfm_appthist.CustomerID        = '$txnid',
																	sfm_appthist.BranchID          = '$branchid',
																	sfm_appthist.sfm_level         = $type,
																	sfm_appthist.appointment_date  = '$appdate',
																	sfm_appthist.status            = 7,
																	sfm_appthist.created_date      = now(), 
																	sfm_appthist.created_by        = $userid,
																	sfm_appthist.updated_date      = now(), 
																	sfm_appthist.updated_by        = 1
						 ");
				   }
				}
				 #echo 'l<br>';	
				$bmstatusq = $database->execute(" SELECT s.CustomerStatusID StatusID, DATE(s.EnrollmentDate) EnrollmentDate
												FROM tpi_rcustomerstatus s
												INNER JOIN customer c ON c.CustomerID = s.CustomerID
												WHERE c.CustomerID = $txnid
												ORDER BY s.id DESC LIMIT 1
											 ");
				if ($bmstatusq->num_rows)
				{
					while ($bstat = $bmstatusq->fetch_object())
					{	
				        $oldstatus         = $bstat->StatusID;
						$oldEnrollmentDate = $bstat->EnrollmentDate;
					}
				}
                #echo 'm<br>';	
				#echo $oldstatus.$txtstatus.$tmdate.$oldEnrollmentDate;
				
				if($oldstatus != $txtstatus || ($tmdate != $oldEnrollmentDate && $tmdate != '' )) 
				{
					if($txtstatus == 5)
					{
						$datestat = $tmdate;
					}
					else
					{ 
						$datestat = '';	;
					}
					
					if($tmdate != $oldEnrollmentDate || $txtstatus == 5)
					{
						$database->execute("INSERT INTO tpi_rcustomerstatus
										 SET tpi_rcustomerstatus.BranchID          = '$branchid',
											 tpi_rcustomerstatus.CustomerID        = '$txnid',
											 tpi_rcustomerstatus.Changed           = 1,
											 tpi_rcustomerstatus.CustomerStatusID  = '5',
											 tpi_rcustomerstatus.EnrollmentDate    = '$tmdate',
											 tpi_rcustomerstatus.FromBranch        = '$branchid',
											 tpi_rcustomerstatus.FromIBM           = '$txnid',
											 tpi_rcustomerstatus.HOGeneralID       = '$HOGeneralID',
											 tpi_rcustomerstatus.IsAddOther        = '0',
											 tpi_rcustomerstatus.ISPRStatus        = '0',
											 tpi_rcustomerstatus.IsReactivated     = '0',
											 tpi_rcustomerstatus.IsRemoveOther     = '0',
											 tpi_rcustomerstatus.ToBranch          = '$branchid',
											 tpi_rcustomerstatus.ToIBM             = '$txnid',
											 tpi_rcustomerstatus.CreatedBy         =  $userid
										");
					}
					 
					
					if($txtstatus != 5)
					{
						$database->execute("INSERT INTO tpi_rcustomerstatus
											 SET tpi_rcustomerstatus.BranchID          = '$branchid',
												 tpi_rcustomerstatus.CustomerID        = '$txnid',
												 tpi_rcustomerstatus.Changed           = 1,
												 tpi_rcustomerstatus.CustomerStatusID  = '1',
												 tpi_rcustomerstatus.EnrollmentDate    = now(),
												 tpi_rcustomerstatus.FromBranch        = '$branchid',
												 tpi_rcustomerstatus.FromIBM           = '$txnid',
												 tpi_rcustomerstatus.HOGeneralID       = '$HOGeneralID',
												 tpi_rcustomerstatus.IsAddOther        = '0',
												 tpi_rcustomerstatus.ISPRStatus        = '0',
												 tpi_rcustomerstatus.IsReactivated     = '0',
												 tpi_rcustomerstatus.IsRemoveOther     = '0',
												 tpi_rcustomerstatus.ToBranch          = '$branchid',
												 tpi_rcustomerstatus.ToIBM             = '$txnid',
												 tpi_rcustomerstatus.CreatedBy         =  $userid
											");
					}						
                     										
				} 			  
				#echo 'n<br>';
				$tmpcustfornatidq = $database->execute(" SELECT *
														 FROM tmpcustfornatid
														WHERE tmpcustfornatid.Branch = '$bcode'
														  and tmpcustfornatid.nationalid = '$HOGeneralID'
														  and tmpcustfornatid.dsv2code  = '$code'
														 ");
				if (!$tmpcustfornatidq->num_rows)
				{
												
					$database->execute("INSERT INTO tmpcustfornatid
											SET tmpcustfornatid.Branch = '$bcode',
											    tmpcustfornatid.nationalid = '$HOGeneralID',
												tmpcustfornatid.dsv2code  = '$code',
											    tmpcustfornatid.networkcode = ".'"'.$manager_code.'"'." ,
												tmpcustfornatid.dsv1code = ".'"'.$dsv1_igscode.'"'." ,
												tmpcustfornatid.mLevel = ".'"'.$txtlevel.'"'." ,
												tmpcustfornatid.igsname = ".'"'.$Name.'"'." ,
												tmpcustfornatid.FirstName = ".'"'.$firstname.'"'." ,
												tmpcustfornatid.LastName = ".'"'.$lastname.'"'." ,
												tmpcustfornatid.MiddleName = ".'""'." ,
												tmpcustfornatid.Birthdate = ".'"'.$Birthdate.'"'." ,
												tmpcustfornatid.CustomerClassID = ".'"2"'." ,
												tmpcustfornatid.StatusID  = ".'"'.$txtstatus.'"'." ,
												tmpcustfornatid.cenrolldate = ".'"'.$bmcdate.'"'." ,
												tmpcustfornatid.IsEmployee = ".'"0"'." ,
												tmpcustfornatid.IsHomeGrownIBM = ".'"1"'." ,
												tmpcustfornatid.MBranchID  = ".'""'." ,
												tmpcustfornatid.EmployeeCode = ".'""'." ,
												tmpcustfornatid.bmcdate = ".'"'.$bmcdate.'"'." ,
												tmpcustfornatid.ffdate1 = ".'"'.$appdate.'"'." ,
												tmpcustfornatid.aldate = ".'" "'." ,
												tmpcustfornatid.tmdate = ".'"'.$tmdate.'"'." ,
												tmpcustfornatid.PayoutOrOffset = ".'"'.$txtPayoutOrOffset.'"'." ,
												tmpcustfornatid.Vatable = ".'"'.$txtVatable.'"'." ,
												tmpcustfornatid.Vatable_Effectivity = ".'""'." ,
												tmpcustfornatid.ApplicableBonusCodes = ".'""'." ,
												tmpcustfornatid.date_added = ".'"'.$enrollment_date.'"'." ,
												tmpcustfornatid.ExcludedinIBMCount = ".'"0"'." ,
												tmpcustfornatid.NickName = ".'"'.$firstname.'"'." ,
												tmpcustfornatid.TelNo = ".'""'." ,
												tmpcustfornatid.MobileNo = ".'""'." ,
												tmpcustfornatid.StreetAdd = ".'""'." ,
												tmpcustfornatid.AreaID = ".'" "'." ,
												tmpcustfornatid.ZipCode = ".'""'." ,
												tmpcustfornatid.tpi_ZoneID = ".'""'." ,
												tmpcustfornatid.tpi_GSUTypeID = ".'"1"'." ,
												tmpcustfornatid.recruiter = ".'""'." ,
												tmpcustfornatid.Remarks  = ".'"APPT"'." ,
												tmpcustfornatid.Gender =  ".'""'." ,
												tmpcustfornatid.CreditTermID = ".'"'.$Duration.'"'." ,
												tmpcustfornatid.AvailableCL = ".'"'.$credit_limit.'"'." ,
												tmpcustfornatid.withOR = ".'"'.$txtWithOR.'"'." ,
												tmpcustfornatid.TIN = ".'" "'." ,
												tmpcustfornatid.Accounttype = 'H',
												tmpcustfornatid.TranType = 'HOMODIFICATION' 

											   ");
				}
				else
				{
					
					$database->execute("update tmpcustfornatid
											SET tmpcustfornatid.Branch     = ".'"'.$bcode.'"'." ,
												tmpcustfornatid.nationalid = ".'"'.$HOGeneralID.'"'." ,
												tmpcustfornatid.dsv2code = ".'"'.$code.'"'." ,
												tmpcustfornatid.dsv1code = ".'"'.$dsv1_igscode.'"'." ,
												tmpcustfornatid.networkcode = ".'"'.$manager_code.'"'." ,
												tmpcustfornatid.mLevel = ".'"'.$txtlevel.'"'." ,
												tmpcustfornatid.igsname = ".'"'.$Name.'"'." ,
												tmpcustfornatid.FirstName = ".'"'.$firstname.'"'." ,
												tmpcustfornatid.LastName = ".'"'.$lastname.'"'." ,
												tmpcustfornatid.MiddleName = ".'""'." ,
												tmpcustfornatid.Birthdate = ".'"'.$Birthdate.'"'." ,
												tmpcustfornatid.CustomerClassID = ".'"2"'." ,
												tmpcustfornatid.StatusID  = ".'"'.$txtstatus.'"'." ,
												tmpcustfornatid.cenrolldate = ".'"'.$bmcdate.'"'." ,
												tmpcustfornatid.IsEmployee = ".'"0"'." ,
												tmpcustfornatid.IsHomeGrownIBM = ".'"1"'." ,
												tmpcustfornatid.MBranchID  = ".'""'." ,
												tmpcustfornatid.EmployeeCode = ".'""'." ,
												tmpcustfornatid.bmcdate = ".'"'.$bmcdate.'"'." ,
												tmpcustfornatid.ffdate1 = ".'"'.$appdate.'"'." ,
												tmpcustfornatid.aldate = ".'" "'." ,
												tmpcustfornatid.tmdate = ".'"'.$tmdate.'"'." ,
												tmpcustfornatid.PayoutOrOffset = ".'"'.$txtPayoutOrOffset.'"'." ,
												tmpcustfornatid.Vatable = ".'"'.$txtVatable.'"'." ,
												tmpcustfornatid.Vatable_Effectivity = ".'""'." ,
												tmpcustfornatid.ApplicableBonusCodes = ".'""'." ,
												tmpcustfornatid.date_added = ".'"'.$enrollment_date.'"'." ,
												tmpcustfornatid.ExcludedinIBMCount = ".'"0"'." ,
												tmpcustfornatid.NickName = ".'"'.$firstname.'"'." ,
												tmpcustfornatid.TelNo = ".'""'." ,
												tmpcustfornatid.MobileNo = ".'""'." ,
												tmpcustfornatid.StreetAdd = ".'""'." ,
												tmpcustfornatid.AreaID = ".'" "'." ,
												tmpcustfornatid.ZipCode = ".'""'." ,
												tmpcustfornatid.tpi_ZoneID = ".'""'." ,
												tmpcustfornatid.tpi_GSUTypeID = ".'"1"'." ,
												tmpcustfornatid.recruiter = ".'""'." ,
												tmpcustfornatid.Remarks  = ".'"APPT"'." ,
												tmpcustfornatid.Gender =  ".'""'." ,
												tmpcustfornatid.CreditTermID = ".'"'.$Duration.'"'." ,
												tmpcustfornatid.AvailableCL = ".'"'.$credit_limit.'"'." ,
												tmpcustfornatid.withOR = ".'"'.$txtWithOR.'"'." ,
												tmpcustfornatid.TIN = ".'" "'." ,
												tmpcustfornatid.Accounttype = 'H',
												tmpcustfornatid.TranType = 'HOMODIFICATION'
										  WHERE tmpcustfornatid.Branch = '$bcode'
											and tmpcustfornatid.nationalid = '$HOGeneralID'
										    and tmpcustfornatid.dsv2code  = '$code'
									");
				}
					
				
				 #throw new Exception("An error occurred, please contact your system administrator.");
				echo '<script language="javascript">';
				echo 'alert("Transaction was Successfuly Posted")';
				echo '</script>';	
				
				
				$database->commitTransaction();
		  	  	redirect_to("index.php?pageid=180.3&msg=$message&branch=$BID");		

				
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=180.3&errmsg=$errmsg");	
		}
	}
?>