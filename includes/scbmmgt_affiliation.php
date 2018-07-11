<?php
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}

	global $database; 
	
	$txnid  = $_GET['tid'];
	$userid = $_SESSION['user_id'];
	
	
	
	$affbranch   = $_POST['branch']; 
	#$HOGeneralID = $_POST['txtnatid']; 
	#$userid      = $_POST['txtuserid'];
	
	$rs = $database->execute(" 
	                            SELECT  c.customerid CID,
								        b.code bcode,
										b.Id BID,
									    c.HOGeneralID , 
										trim(c.dsv1_igscode)  dsv1_igscode,
									    lev.desc_code mlevel,
									    c.code,
									    c.Name,
									    date(c.Birthdate) Birthdate,
									    c.BranchID,
										c.firstname firstname,
										c.lastname lastname,
										c.middlename middlename,
									    net2.manager_code,
									    c2.Name mbmname,
										c2.customerid mid,
									    IFNULL(( SELECT DATE(sfm_appthist.appointment_date) FROM sfm_appthist WHERE sfm_appthist.CustomerID = c.CustomerID AND sfm_appthist.sfm_level = 2 ORDER BY id DESC  LIMIT 1 ),'') bmcdate ,
										(SELECT DATE(sfm_appthist.appointment_date) 
										 FROM sfm_appthist 
										 WHERE sfm_appthist.CustomerID = c.customerid
										 AND sfm_appthist.sfm_level = 3 ORDER BY id DESC LIMIT 1
										) bmfdate,
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
										cd.Gender,cd.TelNo,cd.MobileNo ,cd.StreetAdd,cd.AreaID,
                                        sfm.tin tin, 
                                        sfm.ApplicableBonusCodes, 
                                        sfm.bank_acct_name ,
                                        sfm.bank_acct_num ,
                                        sfm.bank_name										
								FROM customer c 
								LEFT JOIN branch b ON b.id = c.BranchID
								INNER JOIN sfm_manager sfm ON sfm.mID = c.CustomerID
								inner join tpi_customerdetails cd on cd.CustomerID = c.CustomerID
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
	        $bank_acct_name  = $row->bank_acct_name;
			$bank_acct_num   = $row->bank_acct_num;
			$bank_name       = $row->bank_name;
			$id              = $row->CID;		
			$bcode           = $row->bcode;
			$BID             = $row->BID;
			$HOGeneralID     = $row->HOGeneralID;
			$mlevel          = $row->mlevel;		
			$code            = $row->code;
			$Name            = $row->Name;
			$Birthdate       = $row->Birthdate;
			$manager_code    = $row->manager_code;
			$mbmname         = $row->mbmname;
			$bmcdate         = $row->bmcdate;
			$bmfdate         = $row->bmfdate; 
			$stat            = $row->stat;
			$PayoutOrOffset  = $row->PayoutOrOffset;
			$PayoutOrOffset2 = $row->PayoutOrOffset2;
			$Vatable         = $row->Vatable;
			$Vatable2        = $row->Vatable2;
			$withOR          = $row->withOR;
			$withOR2         = $row->withOR2;
			$credit_limit    = $row->credit_limit;
			$Duration        = $row->Duration;
			$dsv1_igscode    = $row->dsv1_igscode;
			$firstname       = $row->firstname;
			$lastname        = $row->lastname;
			$enrollment_date = $row->enrollment_date;
			$mid             = $row->mid;
			$Gender          = $row->Gender;
			$tin             = $row->tin;
			$middlename      = $row->middlename;
			$TelNo           = $row->TelNo;
			$MobileNo        = $row->MobileNo;
			$StreetAdd       = $row->StreetAdd;
			$AreaID          = $row->AreaID;
			$ApplicableBonusCodes = $row->ApplicableBonusCodes;
			
		}
	}
	
	if(isset($_POST['btnCancel'])) 
	{
		redirect_to("index.php?pageid=180.5&branch=$BID");	
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
			$txtStartDate  = $_POST['txtStartDate'];
             
			$date = date("");
			$datetoday = date("m/d/Y");	
			$appdate  =  date('Y-m-d',strtotime($txtStartDate));	
			
			

			if ($txtStartDate == '')
			{
				$message = "Please input effectivitydate date.";
				#redirect_to("index.php?pageid=180.1&errmsg=$message");				
			}
			else
			{
				#echo 'vvvv';
				$affbrq = $database->execute("select `Code` from branch where id = $affbranch "); 
				if($affbrq->num_rows)
				{
					while($bq = $affbrq->fetch_object())
					{
						$affbranchcode = $bq->Code;
					}
				}
				#echo 'xxxx';
				
				$custquery = $database->execute("SELECT c.customerid CID, c.StatusID, c.code, c.DSV1_IGSCode, c.AccountType, c.customertypeid customertypeid
												 FROM customer c 
												 WHERE c.BranchID       = $affbranch
												   AND c.HOGeneralID    = '$HOGeneralID'
												   and c.customertypeid = 3 
											   ");						   
				if($custquery->num_rows)
				{
					#echo 'fff';	
					while($res = $custquery->fetch_object())
					{
						$AccountType    = $res->AccountType;
						$StatusID       = $res->StatusID;
						$CID            = $res->CID;
						$customertypeid = $res->customertypeid;
						
						if($StatusID == '5' && $customertypeid != 4 )
						{
							//Assign FF in customer account type and change status from terminated to active
							$database->execute("UPDATE customer c
												SET c.StatusID = 1,
													c.AccountType = 'AFF', 
													c.isHomeGrownIBM = 0, 
													c.MBranchID = '$bcode'
												WHERE c.customerid = $CID 
											   ");	
							#echo 'f1';				   
							//tagged sfm_manager as affialiated 
							$database->execute("UPDATE sfm_manager sfm
												SET sfm.isaffiliated = 1
												WHERE sfm.mid = $CID 	
							                  ");
							#echo 'f2';					  
							//create tpi rcustomerstaus
                            createcustomerstatus($affbranch,$appdate,$HOGeneralID,$CID,$StatusID,$userid);	
							#echo 'f3';	
							#sfm_appthist
					        createsfmapphist($HOGeneralID,$CID,$affbranch,3,$appdate,$userid);
							#sfm_movement_history
					        createmovementhist($affbranch,$CID,$HOGeneralID,$userid);
							//create tmpcustfornatid - affiliated code
							
							if($code != $manager_code)
							{
								#get mother details
								$getmotherq = 	$database->execute(" SELECT d.tpi_RecruiterID RecruiterID , d.Gender Gender, s.withOR withOR, s.TIN TIN, c.Name cname, c.FirstName, c.LastName, c.MiddleName,
																			DATE(c.Birthdate) Birthdate, 
																			if(DATE(s.Appointment_Date) = '' or DATE(s.Appointment_Date) is null , $appdate ,DATE(s.Appointment_Date) )  apptdate,
																			b.Code bcode, d.TelNo, d.MobileNo,d.StreetAdd, d.AreaID, 
																			s.PayoutOrOffset, s.Vatable,s.ApplicableBonusCodes, s.bank_acct_name, s.bank_acct_num,s.bank_name, '' affbranchcode, c.HOGeneralID, 
																			c.DSV1_IGSCode,  c.code custcode, n.manager_code
																	FROM customer c
																	INNER JOIN tpi_customerdetails d ON d.CustomerID = c.CustomerID 
																	INNER JOIN sfm_manager s ON s.mID = c.CustomerID
																	INNER JOIN branch b ON b.id = c.BranchID
																	LEFT JOIN sfm_manager_networks	n ON n.manager_network_codecustID = c.CustomerID
																	WHERE c.customerid = $mid
																  ");	
								if($getmotherq->num_rows)
								{
									while($mq = $getmotherq->fetch_object())
									{
										createtmpcustfornatid($mq->RecruiterID,$mq->Gender,$mq->withOR,$mq->TIN,$mq->cname,$mq->FirstName,$mq->LastName,$mq->MiddleName,
															  $mq->Birthdate,$mq->apptdate,$mq->bcode,$mq->TelNo,$mq->MobileNo,$mq->StreetAdd,$mq->AreaID,
															  $mq->PayoutOrOffset,$mq->Vatable,$mq->ApplicableBonusCodes,$mq->bank_acct_name,
															  $mq->bank_acct_num,$mq->bank_name,$affbranchcode,$mq->HOGeneralID,$mq->DSV1_IGSCode,$mq->custcode,$mq->manager_code,'AFF-MIBM','');
									}
								}
							}
							
							createtmpcustfornatid($mid,$Gender,$withOR2,$tin,$Name,$firstname,$lastname,$middlename,$Birthdate,$appdate,$bcode,$TelNo,$MobileNo,$StreetAdd,$AreaID,
							                      $PayoutOrOffset2,$Vatable2,$ApplicableBonusCodes,$bank_acct_name,$bank_acct_num,$bank_name,$affbranchcode,$HOGeneralID,$dsv1_igscode,$code,$manager_code,'AFF','AFF');				  
							#echo 'f4';	
							
							#echo 'f5';	
						    $message = "Transaction was successfully process\n"."BM: ".$code.'-'.$Name.'\n'.'Affiliated Branch:'.$affbranchcode.'\nAffiliation Effectivity Date:'.$appdate ;	
						}
						else
						{
							$message = 'Branch Code: '.$affbranchcode.'\n'.'Already exist.'; 
							#echo '<script language="javascript">';
							#echo 'alert("'.$message.'")';
							#echo '</script>';
							#redirect_to("index.php?pageid=180.6&msg=$message&branch=$BID&tid=$id&HOGeneralID=$HOGeneralID");	
						} 
					}
				}
				else
				{
					#create customer
					#echo 'fff';
					createcustomer($Birthdate,$dsv1_igscode,$affbranch,$appdate,$firstname,$HOGeneralID,$lastname,$bcode,$middlename,$Name,$tin,$code,$userid);
					#echo 'ggg';
					
					#get the generated customerid
					$CustomerID = '';
					$cidq = $database->execute("select max(CustomerID) ID from customer ");
					if($cidq->num_rows > 0 )
					{
					   while($c = $cidq->fetch_object() )
					   {
						   $CustomerID = $c->ID;
					   }	
					}
					
					if($mid=='')
					{
						$mid =0;
					}
		
		            #echo 'gggh';
					#tpi_customerdetails 
					createcustomerdetails($HOGeneralID,$AreaID,$StreetAdd,$affbranch,$manager_code,$appdate,$Gender,$firstname,$MobileNo,$CustomerID,$userid); 
					#echo $CustomerID.'vvv';
					#tpi_credit 
					createcustomercredit($affbranch,$appdate,$HOGeneralID,$CustomerID,$userid); 
					#echo $CustomerID.'hhhh';
					#tpi-rcustomerstatus
				    createcustomerstatus($affbranch,$appdate,$HOGeneralID,$CustomerID,1,$userid);	
                    #echo $CustomerID.'iiii';					
				    #tpi-rcustomeribm
					createcustomeribm($HOGeneralID,$affbranch,$CustomerID,$manager_code,$appdate,$userid,$mid);
					#echo $CustomerID.'ssss';
				    #sfm_movement_history
					createmovementhist($affbranch,$CustomerID,$HOGeneralID,$userid);
					#echo $CustomerID.'jjjj';
					#sfm_appthist
					createsfmapphist($HOGeneralID,$CustomerID,$affbranch,3,$appdate,$userid);
					#echo $CustomerID.'lllll';
					#sfm_manager
					createsfmmanager($PayoutOrOffset2,$Vatable2,$ApplicableBonusCodes,$bank_acct_name,$bank_acct_num,$bank_name,$appdate,3,$tin,$withOR2,$firstname,$HOGeneralID,$lastname,$code,$CustomerID,$middlename,$userid,$Birthdate,$affbranch,3);
					
					#echo $CustomerID.'kkkkk';				 
					#sfm_manager_network
					#echo $manager_code.'|'.$code.'|'.$CustomerID.'|'.$mid;
					createcustomermanagernetwork($manager_code,$code,$CustomerID,$mid);
					#echo $CustomerID.'lllll';
                    //create tmpcustfornatid for mother of affiliated ibm
                    #get mother details
					if($code != $manager_code)
					{
						$getmotherq = 	$database->execute(" SELECT d.tpi_RecruiterID RecruiterID , d.Gender Gender, s.withOR withOR, s.TIN TIN, c.Name cname, c.FirstName, c.LastName, c.MiddleName,
																	DATE(c.Birthdate) Birthdate, if(DATE(s.Appointment_Date) = '' or DATE(s.Appointment_Date) is null , $appdate ,DATE(s.Appointment_Date) )  apptdate,b.Code bcode, d.TelNo, d.MobileNo,d.StreetAdd, d.AreaID, 
																	s.PayoutOrOffset, s.Vatable,s.ApplicableBonusCodes, s.bank_acct_name, s.bank_acct_num,s.bank_name, '' affbranchcode, c.HOGeneralID, 
																	c.DSV1_IGSCode,  c.code custcode, n.manager_code
															FROM customer c
															INNER JOIN tpi_customerdetails d ON d.CustomerID = c.CustomerID 
															INNER JOIN sfm_manager s ON s.mID = c.CustomerID
															INNER JOIN branch b ON b.id = c.BranchID
															LEFT JOIN sfm_manager_networks	n ON n.manager_network_codecustID = c.CustomerID
															WHERE c.customerid = $mid
														  ");	
						if($getmotherq->num_rows)
						{
							while($mq = $getmotherq->fetch_object())
							{
								createtmpcustfornatid($mq->RecruiterID,$mq->Gender,$mq->withOR,$mq->TIN,$mq->cname,$mq->FirstName,$mq->LastName,$mq->MiddleName,
													  $mq->Birthdate,$mq->apptdate,$mq->bcode,$mq->TelNo,$mq->MobileNo,$mq->StreetAdd,$mq->AreaID,
													  $mq->PayoutOrOffset,$mq->Vatable,$mq->ApplicableBonusCodes,$mq->bank_acct_name,
													  $mq->bank_acct_num,$mq->bank_name,$affbranchcode,$mq->HOGeneralID,$mq->DSV1_IGSCode,$mq->custcode,$mq->manager_code,'AFF-MIBM','');
							}
						}
						
					}
					
					createtmpcustfornatid($mid,$Gender,$withOR2,$tin,$Name,$firstname,$lastname,$middlename,$Birthdate,$appdate,$bcode,$TelNo,$MobileNo,$StreetAdd,$AreaID,
							                      $PayoutOrOffset2,$Vatable2,$ApplicableBonusCodes,$bank_acct_name,$bank_acct_num,$bank_name,$affbranchcode,$HOGeneralID,$dsv1_igscode,$code,$manager_code,'AFF','AFF');
					
					#echo $CustomerID.'mmmm';	
					
					$message = "Transaction was successfully process.".'\n'."BM: ".$code.'-'.$Name.'\n'.'Affiliated Branch:'.$affbranchcode.'\nAffiliation Effectivity Date:'.$appdate ;
					#echo $CustomerID.'nnnn';	
				}	
			}
			$database->commitTransaction();
					#redirect_to("index.php?pageid=180.6&msg=$message&tid=$id&HOGeneralID=$HOGeneralID");
			echo '<script language="javascript">';
			echo 'alert("'.$message.'")';
		    echo '</script>';	
		}
		catch(Exception $e) 
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=180.6&tid=$id&msg=$errmsg&userid=$userid");	
		}
	}
	
	function createcustomerstatus($branch,$appdate,$HOGeneralID,$CustomerID,$StatusID,$userid)
	{
		global $database;
		$database->execute("INSERT INTO tpi_rcustomerstatus
							 SET tpi_rcustomerstatus.BranchID          = '$branch',
								 tpi_rcustomerstatus.CustomerID        = '$CustomerID',
								 tpi_rcustomerstatus.Changed           = 1,
								 tpi_rcustomerstatus.CustomerStatusID  = '$StatusID',
								 tpi_rcustomerstatus.EnrollmentDate    = '$appdate',
								 tpi_rcustomerstatus.FromBranch        = '$branch',
								 tpi_rcustomerstatus.FromIBM           = '$CustomerID',
								 tpi_rcustomerstatus.HOGeneralID       = '$HOGeneralID',
								 tpi_rcustomerstatus.IsAddOther        = '0',
								 tpi_rcustomerstatus.ISPRStatus        = '0',
								 tpi_rcustomerstatus.IsReactivated     = '0',
								 tpi_rcustomerstatus.IsRemoveOther     = '0',
								 tpi_rcustomerstatus.ToBranch          = '$branch',
								 tpi_rcustomerstatus.ToIBM             = '$CustomerID',
								 tpi_rcustomerstatus.CreatedBy         =  $userid
							");					
	}
	
	
	function  createtmpcustfornatid($mid,$Gender,$withOR2,$tin,$Name,$firstname,$lastname,$middlename,$Birthdate,$appdate,$bcode,$TelNo,$MobileNo,$StreetAdd,$AreaID,
							         $PayoutOrOffset2,$Vatable2,$ApplicableBonusCodes,$bank_acct_name,$bank_acct_num,$bank_name,$affbranchcode,$HOGeneralID,$dsv1_igscode,$code,$manager_code,$trantype,$custtype)
	{								 
		global $database;

        $v = "
		                  INSERT INTO tmpcustfornatid
							SET tmpcustfornatid.ZipCode = '' ,
								tmpcustfornatid.tpi_ZoneID = '1',
								tmpcustfornatid.tpi_GSUTypeID = '2',
								tmpcustfornatid.recruiter = '$mid',
								tmpcustfornatid.Remarks  = '' ,
								tmpcustfornatid.Gender =  '$Gender',
								tmpcustfornatid.CreditTermID = '3',
								tmpcustfornatid.AvailableCL = '0',
								tmpcustfornatid.withOR = '$withOR2',
								tmpcustfornatid.TIN = '$tin',
 								tmpcustfornatid.mLevel = '3' ,
								tmpcustfornatid.igsname = '$Name',
								tmpcustfornatid.FirstName = '$firstname' ,
								tmpcustfornatid.LastName = '$lastname',
								tmpcustfornatid.MiddleName = '$middlename',
								tmpcustfornatid.Birthdate = '$Birthdate',
								tmpcustfornatid.CustomerClassID = '2',
								tmpcustfornatid.StatusID  = '1',
								tmpcustfornatid.cenrolldate = '$appdate',
								tmpcustfornatid.IsEmployee = '0',
								tmpcustfornatid.IsHomeGrownIBM = '0',
								tmpcustfornatid.MBranchID  = '$bcode',
								tmpcustfornatid.date_added = '$appdate',
								tmpcustfornatid.ExcludedinIBMCount = '0' ,
								tmpcustfornatid.NickName = '$firstname' ,
								tmpcustfornatid.TelNo = '$TelNo',
								tmpcustfornatid.MobileNo = '$MobileNo' ,
								tmpcustfornatid.StreetAdd = '$StreetAdd',
								tmpcustfornatid.AreaID = '$AreaID',
								tmpcustfornatid.EmployeeCode = '' ,
								tmpcustfornatid.bmcdate = '',
								tmpcustfornatid.ffdate1 = '$appdate' ,
								tmpcustfornatid.aldate = '',
								tmpcustfornatid.tmdate = '',
								tmpcustfornatid.PayoutOrOffset = '$PayoutOrOffset2' ,
								tmpcustfornatid.Vatable = '$Vatable2' ,
								tmpcustfornatid.Vatable_Effectivity = '' ,
								tmpcustfornatid.ApplicableBonusCodes = '$ApplicableBonusCodes',
								tmpcustfornatid.bank_acct_name = '$bank_acct_name',
								tmpcustfornatid.bank_acct_num = '$bank_acct_num',
								tmpcustfornatid.bank_name = '$bank_name',
								tmpcustfornatid.Branch     = '$affbranchcode',
								tmpcustfornatid.TranType = '$custtype',
								tmpcustfornatid.nationalid = '$HOGeneralID',
								tmpcustfornatid.HOGeneralID = '$HOGeneralID',
								tmpcustfornatid.dsv2code = '$code',
								tmpcustfornatid.dsv1code = '$dsv1_igscode',
								tmpcustfornatid.networkcode = '$manager_code' ,
								tmpcustfornatid.Last_ModifiedDate = now(),
								tmpcustfornatid.AccountType = '$trantype'
		      ";
		#echo $v; 
		$database->execute("INSERT INTO tmpcustfornatid
							SET tmpcustfornatid.ZipCode = '' ,
								tmpcustfornatid.tpi_ZoneID = '1',
								tmpcustfornatid.tpi_GSUTypeID = '2',
								tmpcustfornatid.recruiter = '$mid',
								tmpcustfornatid.Remarks  = '' ,
								tmpcustfornatid.Gender =  '$Gender',
								tmpcustfornatid.CreditTermID = '3',
								tmpcustfornatid.AvailableCL = '0',
								tmpcustfornatid.withOR = '$withOR2',
								tmpcustfornatid.TIN = '$tin',
 								tmpcustfornatid.mLevel = '3' ,
								tmpcustfornatid.igsname = '$Name',
								tmpcustfornatid.FirstName = '$firstname' ,
								tmpcustfornatid.LastName = '$lastname',
								tmpcustfornatid.MiddleName = '$middlename',
								tmpcustfornatid.Birthdate = '$Birthdate',
								tmpcustfornatid.CustomerClassID = '2',
								tmpcustfornatid.StatusID  = '1',
								tmpcustfornatid.cenrolldate = '$appdate',
								tmpcustfornatid.IsEmployee = '0',
								tmpcustfornatid.IsHomeGrownIBM = '0',
								tmpcustfornatid.MBranchID  = '$bcode',
								tmpcustfornatid.date_added = '$appdate',
								tmpcustfornatid.ExcludedinIBMCount = '0' ,
								tmpcustfornatid.NickName = '$firstname' ,
								tmpcustfornatid.TelNo = '$TelNo',
								tmpcustfornatid.MobileNo = '$MobileNo' ,
								tmpcustfornatid.StreetAdd = '$StreetAdd',
								tmpcustfornatid.AreaID = '$AreaID',
								tmpcustfornatid.EmployeeCode = '' ,
								tmpcustfornatid.bmcdate = '',
								tmpcustfornatid.ffdate1 = '$appdate' ,
								tmpcustfornatid.aldate = '',
								tmpcustfornatid.tmdate = '',
								tmpcustfornatid.PayoutOrOffset = '$PayoutOrOffset2' ,
								tmpcustfornatid.Vatable = '$Vatable2' ,
								tmpcustfornatid.Vatable_Effectivity = '' ,
								tmpcustfornatid.ApplicableBonusCodes = '$ApplicableBonusCodes',
								tmpcustfornatid.bank_acct_name = '$bank_acct_name',
								tmpcustfornatid.bank_acct_num = '$bank_acct_num',
								tmpcustfornatid.bank_name = '$bank_name',
								tmpcustfornatid.Branch     = '$affbranchcode',
								tmpcustfornatid.TranType = '$trantype',
								tmpcustfornatid.nationalid = '$HOGeneralID',
								tmpcustfornatid.HOGeneralID = '$HOGeneralID',
								tmpcustfornatid.dsv2code = '$code',
								tmpcustfornatid.dsv1code = '$dsv1_igscode',
								tmpcustfornatid.networkcode = '$manager_code' ,
								tmpcustfornatid.Last_ModifiedDate = now(),
								tmpcustfornatid.AccountType = '$custtype' ");
	}
	         
	function createcustomer($Birthdate,$dsv1_igscode,$affbranch,$appdate,$firstname,$HOGeneralID,$lastname,$bcode,$middlename,$Name,$tin,$code,$userid)
	{			
		global $database;	
	    $database->execute("INSERT INTO customer
							SET customer.Birthdate            = '$Birthdate',
								customer.BranchID             = '$affbranch',
								customer.Changed              = 1,
								customer.Code                 = '$code',
                                customer.CustomerClassID      = 2, 
								customer.CustomerTypeID       = 3,
								customer.DSV1_IGSCode         = '$dsv1_igscode', 
								customer.EmployeeCode         = '',
								customer.EnrollmentDate       = '$appdate',
								customer.FirstName            = '$firstname',
								customer.FromBranch           = 0,
								customer.HOGeneralID          = '$HOGeneralID',
								customer.IsEmployee           = 0,
								customer.IsHomeGrownIBM       = 0,
								customer.IsIBMPersonalAccount = 0,
								customer.IsReactivated        = 0,
							    customer.IsTransferee         = 0,
								customer.LastModifiedDate     = now(),
								customer.LastName             = '$lastname',
								customer.MBranchID            = '$bcode',
								customer.MiddleName           = '$middlename',
								customer.Name                 = '$Name',
								customer.Profileimage         = '',
								customer.RefID                = 0,
								customer.StatusID             = 1,
								customer.TIN                  = '$tin',
								customer.AccountType          = 'AFF',
								customer.created_by           = $userid
							 ");
	}
	
	function createcustomerdetails($HOGeneralID,$AreaID,$StreetAdd,$affbranch,$manager_code,$appdate,$Gender,$firstname,$MobileNo,$CustomerID,$userid)
	{
		global $database;
		$database->execute(" INSERT INTO tpi_customerdetails
							         SET tpi_customerdetails.HOGeneralID            = '$HOGeneralID',
										 tpi_customerdetails.ApplicationFilePath    = '',
										 tpi_customerdetails.AreaID                 = $AreaID,
										 tpi_customerdetails.BranchID               = $affbranch,
										 tpi_customerdetails.StreetAdd              = '$StreetAdd',
										 tpi_customerdetails.TelNo                  = '$TelNo',
										 tpi_customerdetails.tpi_GSUTypeID          = 2,
										 tpi_customerdetails.tpi_IBMCode            = '$manager_code',
										 tpi_customerdetails.ZipCode                = '',
										 tpi_customerdetails.tpi_ZoneID             = 1,
										 tpi_customerdetails.Changed                = 1,
										 tpi_customerdetails.CustomerID             = '$CustomerID',
										 tpi_customerdetails.EnrollmentDate         = '$appdate',
										 tpi_customerdetails.Gender                 = '$Gender',
										 tpi_customerdetails.IsAnIBMPersonalAccount = '0',
										 tpi_customerdetails.LastModifiedDate       = now(),
										 tpi_customerdetails.LastPODate             = '0000-00-00', 
										 tpi_customerdetails.MobileNo               = '$MobileNo',
										 tpi_customerdetails.NickName               = '$firstname',
										 tpi_customerdetails.Remarks                = 'AFF',
										 tpi_customerdetails.created_by             = $userid
						  ");
	}
	
	function createcustomercredit($affbranch,$appdate,$HOGeneralID,$CustomerID,$userid)
	{
		global $database;
		$database->execute("INSERT INTO tpi_credit
							SET tpi_credit.ApprovedCL       = 0,
								tpi_credit.ARBalance        = 0,
								tpi_credit.AvailableCL      = 0,
								tpi_credit.Branch           = '$affbranch',
								tpi_credit.CalculatedCL     = 0,
								tpi_credit.Capacity         = 0,
								tpi_credit.Capital          = 0,
							    tpi_credit.Changed          = 1,
								tpi_credit.Character        = 0,
								tpi_credit.Condition        = 0, 
								tpi_credit.CustomerID       = '$CustomerID',
								tpi_credit.CreditTermID     = 3,
								tpi_credit.EnrollmentDate   = '$appdate',
								tpi_credit.HOGeneralID      = '$HOGeneralID',
								tpi_credit.LastModifiedDate = now(),
								tpi_credit.RecommendedCL    = '0'  ,
								tpi_credit.created_by       = $userid
                          ");	
	}
             
	function  createcustomeribm($HOGeneralID,$affbranch,$CustomerID,$manager_code,$appdate,$userid,$mid)
	{
		global $database;
		$database->execute("insert into tpi_rcustomeribm
								    SET tpi_rcustomeribm.HOGeneralID    = '$HOGeneralID',
										tpi_rcustomeribm.BranchID       = '$affbranch',
										tpi_rcustomeribm.CustomerID     = '$CustomerID',
										tpi_rcustomeribm.IBMID          = 0,
										tpi_rcustomeribm.IBM_Code       = '$mid',
										tpi_rcustomeribm.IBM_BranchID   = 0,
										tpi_rcustomeribm.CreatedBy      = $userid,
										tpi_rcustomeribm.EnrollmentDate = '$appdate', 
										tpi_rcustomeribm.Changed        = 1
										
				           ");	
	}
	
	function createmovementhist($affbranch,$CustomerID,$HOGeneralID,$userid)
	{
		global $database;
		$database->execute("INSERT INTO sfm_movement_history
								    SET sfm_movement_history.BranchID        = '$affbranch',
										sfm_movement_history.Changed         = 1,
										sfm_movement_history.CustomerID      = '$CustomerID',
										sfm_movement_history.DateModified    = now(),
										sfm_movement_history.FromLevel       = '3',
										sfm_movement_history.HOGeneralID     = '$HOGeneralID',
										sfm_movement_history.MovementDate    = now(),
										sfm_movement_history.MovementStatus  = 7,
										sfm_movement_history.ToLevel         = '3',
										sfm_movement_history.created_by      = $userid
				          ");	
	}
	function createsfmapphist($HOGeneralID,$CustomerID,$affbranch,$sfmlevel,$appdate,$userid)
	{
	   global $database;
	   $database->execute(" 
					        INSERT INTO sfm_appthist
								    SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
										sfm_appthist.CustomerID        = '$CustomerID',
										sfm_appthist.BranchID          = '$affbranch',
										sfm_appthist.sfm_level         = $sfmlevel,
										sfm_appthist.appointment_date  = '$appdate',
										sfm_appthist.status            = 7,
										sfm_appthist.created_date      = '$appdate', 
										sfm_appthist.created_by        = $userid,
										sfm_appthist.updated_date      = now(), 
										sfm_appthist.updated_by        = $userid
					      ");
	}
	
	function createsfmmanager($PayoutOrOffset2,$Vatable2,$ApplicableBonusCodes,$bank_acct_name,$bank_acct_num,$bank_name,$appdate,$credittermid,$tin,$withOR2,$firstname,$HOGeneralID,$lastname,$code,$CustomerID,$middlename,$userid,$Birthdate,$affbranch,$sfmlevel)
	{
		global $database;
		$database->execute(" 
					          INSERT INTO sfm_manager
									  SET sfm_manager.ApplicableBonusCodes  = '$ApplicableBonusCodes', 
										  sfm_manager.bank_acct_name        = '$bank_acct_name', 
										  sfm_manager.bank_acct_num         = '$bank_acct_num', 
										  sfm_manager.bank_name             = '$bank_name', 
										  sfm_manager.birth_date            = '$Birthdate', 
										  sfm_manager.branchID              = '$affbranch',
										  sfm_manager.credit_term_id        =  $credittermid,  
										  sfm_manager.date_added            = '$appdate', 
										  sfm_manager.mLevel                = '$sfmlevel',
										  sfm_manager.PayoutOrOffset        = '$PayoutOrOffset2', 
										  sfm_manager.TIN                   = '$tin', 
										  sfm_manager.Vatable               = '$Vatable2',
										  sfm_manager.credit_limit          = '0',
										  sfm_manager.withOR                = '$withOR2',
										  sfm_manager.Vatable_Effectivity   = '',
										  sfm_manager.Changed               = 1,
										  sfm_manager.date_modified         = now(),
										  sfm_manager.first_name            = '$firstname', 
										  sfm_manager.HOGeneralID           = '$HOGeneralID',
										  sfm_manager.last_name             = '$lastname', 
										  sfm_manager.mCode                 = '$code',
										  sfm_manager.mID                   = '$CustomerID',
										  sfm_manager.middle_name           = '$middlename', 
										  sfm_manager.isaffiliated          = 1, 
                                          sfm_manager.Created_By            = $userid, 
                                          sfm_manager.appointment_date      = '$appdate'										  
					      ");
	}
	
	function createcustomermanagernetwork($manager_code,$code,$CustomerID,$mid)
	{
         global $database;    
         $v = "
		         INSERT INTO sfm_manager_networks
									  SET sfm_manager_networks.manager_code               = '$manager_code',
										  sfm_manager_networks.manager_network_code       = '$code',
										  sfm_manager_networks.manager_network_codecustID = '$CustomerID',
										  sfm_manager_networks.manager_code_custID        =  $mid
		      ";
		 #echo $v;	  
		 $database->execute(" 
				              INSERT INTO sfm_manager_networks
									  SET sfm_manager_networks.manager_code               = '$manager_code',
										  sfm_manager_networks.manager_network_code       = '$code',
										  sfm_manager_networks.manager_network_codecustID = '$CustomerID',
										  sfm_manager_networks.manager_code_custID        =  $mid
				            ");
	}
?>