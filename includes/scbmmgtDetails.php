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
									    c.Name,
									    c.Birthdate,
									    c.BranchID,
										c.firstname firstname,
										c.lastname lastname,
									    net2.manager_code,
									    c2.Name mbmname,
										c.StatusID StatusID,
									    IFNULL(( SELECT DATE(sfm_appthist.appointment_date) FROM sfm_appthist WHERE sfm_appthist.CustomerID = c.CustomerID AND sfm_appthist.sfm_level = 2 ORDER BY id DESC  LIMIT 1 ),'') bmcdate ,
									    s.Name stat, 
									    IF(sfm.PayoutOrOffset=1,'PAYOUT','OFFSET') PayoutOrOffset, 
										sfm.PayoutOrOffset PayoutOrOffset2,
									    IF(sfm.withOR=1,'WITH OR','WITHOUT OR') withOR,  
										sfm.withOR withOR2,
									    sfm.credit_limit,
									    ct.Duration,
										IF(sfm.Vatable=1,'VAT','NVAT') Vatable,
										sfm.Vatable Vatable2, 
										c.EnrollmentDate enrollment_date, 
										sfm.credit_term_id credit_term_id
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
			$HOGeneralID  = $row->HOGeneralID;
			$mlevel       = $row->mlevel;		
			$code         = $row->code;
			$Name         = $row->Name;
			$Birthdate    = $row->Birthdate;
			$manager_code = $row->manager_code;
			$mbmname      = $row->mbmname;
			$bmcdate      = $row->bmcdate;
			$stat         = $row->stat;
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
			$cstatusid       = $row->StatusID;
			$credit_term_id  = $row->credit_term_id;
			
		}
	}
	
	if(isset($_POST['btnCancel'])) 
	{
		redirect_to("index.php?pageid=180.1");	
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{			
	       
			$database->beginTransaction();
			$ctr = 0;
			$ctr_soh = 0;
			$cnt_soh = 0;
			$txtStartDate = $_POST['txtStartDate'];
			$HOGeneralID  = $_POST['txtnatid'];
			$branchid     = $_POST['txtbranchid']; 
			$branch       = $_POST['txtbranch']; 
            $dsv1_igscode = $_POST['txtbmcodedsv1']; 			
			$bmcode       = $_POST['txtbmcode']; 
             
		/*	$tmptxndate = strtotime($_POST['startDate']);
			$txndate = date("Y-m-d", $tmptxndate);*/
			$date = date("");
			$datetoday = date("m/d/Y");	
			$appdate =  date('Y-m-d',strtotime($txtStartDate));	
			
			if ($txtStartDate == '')
			{
				$message = "Please input appointment date.";
				#redirect_to("index.php?pageid=180.1&errmsg=$message");				
			}
			else
			{
				//check soh availability	
				$message = "BM was successfully appointed <br>"."BM: ".$code.'-'.$Name.'<br>'.'BMF Appointment Date:'.$appdate ;	
				#change status from 2 to 3 - customer , sfm_manager , change appointment_date of sfm_manager table
				$database->execute("update sfm_manager
									inner join customer c on c.customerid = sfm_manager.mid 
									set sfm_manager.mlevel = 3, 
										sfm_manager.appointment_date = '$appdate',
										c.customertypeid = 3
								    where c.customerid = $txnid
                                   ");	
                								   			   
				#create sfm_appthist 
				$database->execute(" 
					                    INSERT INTO sfm_appthist
												SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
													sfm_appthist.CustomerID        = '$txnid',
													sfm_appthist.BranchID          = '$branchid',
													sfm_appthist.sfm_level         = 3,
													sfm_appthist.appointment_date  = '$appdate',
													sfm_appthist.status            = 7,
													sfm_appthist.created_date      = '$appdate', 
													sfm_appthist.created_by        = $userid,
													sfm_appthist.updated_date      = now(), 
													sfm_appthist.updated_by        = 1
					              ");
									  
				#create tmpcustfornatid - Type = "AP"
			  
				$database->execute("INSERT INTO tmpcustfornatid
										SET tmpcustfornatid.Branch     = ".'"'.$bcode.'"'." ,
											tmpcustfornatid.nationalid = ".'"'.$HOGeneralID.'"'." ,
											tmpcustfornatid.dsv2code = ".'"'.$code.'"'." ,
											tmpcustfornatid.dsv1code = ".'"'.$dsv1_igscode.'"'." ,
											tmpcustfornatid.networkcode = ".'"'.$manager_code.'"'." ,
											tmpcustfornatid.mLevel = ".'"3"'." ,
											tmpcustfornatid.igsname = ".'"'.$Name.'"'." ,
											tmpcustfornatid.FirstName = ".'"'.$firstname.'"'." ,
											tmpcustfornatid.LastName = ".'"'.$lastname.'"'." ,
											tmpcustfornatid.MiddleName = ".'""'." ,
											tmpcustfornatid.Birthdate = ".'"'.$Birthdate.'"'." ,
											tmpcustfornatid.CustomerClassID = ".'"2"'." ,
											tmpcustfornatid.StatusID  = ".'"'.$cstatusid.'"'." ,
											tmpcustfornatid.cenrolldate = ".'"'.$bmcdate.'"'." ,
											tmpcustfornatid.IsEmployee = ".'"0"'." ,
											tmpcustfornatid.IsHomeGrownIBM = ".'"1"'." ,
											tmpcustfornatid.MBranchID  = ".'""'." ,
											tmpcustfornatid.EmployeeCode = ".'""'." ,
											tmpcustfornatid.bmcdate = ".'"'.$bmcdate.'"'." ,
											tmpcustfornatid.ffdate1 = ".'"'.$appdate.'"'." ,
											tmpcustfornatid.aldate = ".'" "'." ,
											tmpcustfornatid.tmdate = ".'" "'." ,
											tmpcustfornatid.PayoutOrOffset = ".'"'.$PayoutOrOffset2.'"'." ,
											tmpcustfornatid.Vatable = ".'"'.$Vatable2.'"'." ,
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
											tmpcustfornatid.CreditTermID = ".'"'.$credit_term_id.'"'." ,
											tmpcustfornatid.AvailableCL = ".'"'.$credit_limit.'"'." ,
											tmpcustfornatid.withOR = ".'"'.$withOR2.'"'." ,
											tmpcustfornatid.TIN = ".'" "'." ,
											tmpcustfornatid.Accounttype = 'H',
											tmpcustfornatid.TranType = 'APPT'

										   ");
				
				
				 #throw new Exception("An error occurred, please contact your system administrator.");
				echo '<script language="javascript">';
				echo 'alert("Transaction was Successfuly Posted")';
				echo '</script>';	
				
				
				$database->commitTransaction();
		  	  	redirect_to("index.php?pageid=180.1&msg=$message&branch=$BID");		

				
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=180.1&errmsg=$errmsg");	
		}
	}
?>