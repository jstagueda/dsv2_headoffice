<?php
	/*if(ini_get('display_errors'))
 	{
		ini_set('display_errors',1);
	}*/
	
	global $database;
	
	$custid=0;
	$code="";
	$name="";
	$address="";
	$txnDate="";
	$bday="";
	
	$classid=2;
	$customertypeid=1;
	$fieldid=0;
	$efieldid=0;
	$length=0;
	$zoneid=0;
	$customerRemarks='';
	$yesno=0;
	$eyesno=0;
	$credittermid=0;
	$company='';
	$character = 0;
    $capacity = 0;
    $capital = 0;
    $condition = 0;
    $calculatedcl = 0;
    $recommendedcl = 0;
    $approvedcl = 0;
    $accountno='';
    $recruitername='';
	$recruitermobileno='';
	$provinceid =0;
	$townid =0;
	$barangayid=0;
	$fname='';
	$mname='';
	$lname='';
	$enrolldate='';
	$branchname = '';
	$branchid=0;
	$brremarks= '';
	$tin='';
	$gsutypeid=1;
	$ibmcode='';
	$ibmname='';
	
	$nickname = '';
    $telno = '';
    $mobileno = '';
    $address = '';
    $zipcode='';
    $ecode = '';
    $ibmid = '';
    $ibmcode2='';
	$custSearchTxt="";
	$drpBranch="";	
	$pdaid = 0;
	$bircorid = 0;
	$birorid = 0;
	$vatid = 0;
	$comid = 0;
        
        $dealerStatus = '';
        $lastTerminationDate = '';
        $lastAppointmentDate = '';
	
	if(isset($_GET['custid']))
	{
		$custid=$_GET['custid'];
		
		if($custid != "")
		{
				$rs_customerdetails =  $sp->spSelectCustomer($database,$custid,"");		
				$rsFile = $sp->spSelectApplicationFile($database, $custid);
				
				if($rs_customerdetails->num_rows)
				{	
					while($row = $rs_customerdetails->fetch_object())
					{
						$code=$row->Code;
						$name=$row->Name;
						$lname = $row->LastName;
						$fname = $row->FirstName;
						$mname = $row->MiddleName;
						$tmpbday = strtotime($row->BirthDate);
						$bday = $tmpbday != '' ? date("m-d-Y", $tmpbday) : '';
						$classid = $row->CustomerClassID;
						$customertypeid = $row->CustomerTypeID;	
						$gsutypeid = $row->GSUTypeID;
						$tin = $row->TIN;
						$ibmcode = $row->IBMCode;
						$ibmname = $row->IBMName;
						$refid = $row->RefID;
						$zoneid = $row->ZoneID;
						$customerRemarks = $row->Remarks;
						$enrolldate = $row->EnrollmentDate;
						$ibmid = $row->IBMID;
						$ibmcode2 = $row->IBMCode2;						
				
					}
					$rs_customerdetails->close();
				}
		}
		
		else
		{
			if(!isset($_GET['lname']))
			{
				$lname = isset($_POST['txtlnameDealer']) ? $_POST['txtlnameDealer'] : $lname;
				$fname = isset($_POST['txtfnameDealer']) ? $_POST['txtfnameDealer'] : $fname;
				$mname = isset($_POST['txtmnameDealer']) ? $_POST['txtmnameDealer'] : $mname;
				$bday = isset($_POST['txtbdaydealer']) ? $_POST['txtbdaydealer'] : $bday;
			}
			else
			{
				$lname = $_GET['lname'];
				$fname = $_GET['fname'];
				$mname = $_GET['mname'];
				$bday = $_GET['bday'];
			}			
		}
			
		if(isset($_GET['action']) && $_GET['action'] == 'update')
		{
			$rsExist = $sp->spSelectExistingCustomer($database, $custid, '', '', '', '');
		
			if ($rsExist->num_rows)
			{
				while ($row = $rsExist->fetch_object())
				{			
					$ecode= $row->Code;
					$nickname=$row->NickName;
					$telno = $row->TelNo;	
					$mobileno = $row->MobileNo;
					$address = $row->StreetAdd;
					//$zipcode = $row->ZipCode;
					$credittermid = $row->CreditTermID;
					$character = $row->Character;
				    $capacity = $row->Capacity; 
				    $capital = $row->Capital;
				    $condition = $row->Condition;
				    $calculatedcl = $row->CalculatedCL;
				    $recommendedcl = $row->RecommendedCL;
				    $approvedcl = $row->ApprovedCL;
				    $accountno= $row->RecAcctNo;
					$recruitername= $row->RecName;
					$recruitermobileno= $row->RecMobileNo;	
					$pdaid = $row->PDAID;
                                        $dealerStatus = $row->STATUS;
                                        $lastTerminationDate = (empty($row->LastTerminationDate) ? 'NOT YET TERMINATED':$row->LastTerminationDate);
                                        $lastAppointmentDate = (empty($row->LastAppointmentDate) ? 'NOT YET APPOINTED':$row->LastAppointmentDate);
				}
			}
			
			$rsExistMarital = $sp->spSelectMaritalStatus($database, $custid);
			if ($rsExistMarital->num_rows)
			{
				while ($row = $rsExistMarital->fetch_object())
				{			
					$fieldid= $row->ID;
				}
			}
			
			$rsExistEducational = $sp->spSelectEducationalAttainment($database, $custid);
			if ($rsExistEducational->num_rows)
			{
				while ($row = $rsExistEducational->fetch_object())
				{			
					$efieldid= $row->ID;
				}
			}
			
			$rsExistLength = $sp->spSelectLengthStay($database, $custid);
			if ($rsExistLength->num_rows)
			{
				while ($row = $rsExistLength->fetch_object())
				{			
					$length= $row->details;
				}
			}
			
			$rsExistDirectSellExp = $sp->spSelectDirectSellExp($database, $custid);
			if ($rsExistDirectSellExp->num_rows)
			{
				while ($row = $rsExistDirectSellExp->fetch_object())
				{			
					$yesno= $row->YesNo;
				}
			}
			
			$rsExistEmploymentStatus = $sp->spSelectEmploymentStatus($database, $custid);
			if ($rsExistEmploymentStatus->num_rows)
			{
				while ($row = $rsExistEmploymentStatus->fetch_object())
				{			
					$eyesno= $row->YesNo;
				}
			}
			
			$rsExistDirectSellComp = $sp->spSelectDirectSellComp($database, $custid);
			if ($rsExistDirectSellComp->num_rows)
			{
				while ($row = $rsExistDirectSellComp->fetch_object())
				{			
					$company= $row->Details;
				}
			}
			
			$rsExistCompany = $sp->spSelectDealerCompany($database, $custid);
			
			if(!isset($_POST['cboProvince']))
			{
				if($provinceid == 0)
				{
					$rsExistProvince = $sp->spSelectProvince($database, $custid);
					if ($rsExistProvince->num_rows)
					{
						while ($row = $rsExistProvince->fetch_object())
						{			
							$provinceid= $row->ID;
						}
					}
				}
				
				if($townid == 0)
				{
					$rsExistTownCity = $sp->spSelectTownCity($database, $custid, -1);
					if ($rsExistTownCity->num_rows)
					{
						while ($row = $rsExistTownCity->fetch_object())
						{			
							$townid= $row->ID;
						}
					}
				}
				
				if($barangayid == 0)
				{
					$rsExistBarangay = $sp->spSelectBarangay($database, $custid, -1);
					if ($rsExistBarangay->num_rows)
					{
						while ($row = $rsExistBarangay->fetch_object())
						{			
							$barangayid= $row->ID;
						}
					}				
				}				
			}
			else
			{
				$townid = 0;
				$barangayid = 0;				
			}
			
			$rsExistBankInfo = $sp->spSelectBankInfo($database, $custid);
			if ($rsExistBankInfo->num_rows)
			{
				while ($row = $rsExistBankInfo->fetch_object())
				{			
					$bircorid = $row->BIRCOR;
					$birorid = $row->BIROR;
					$vatid = $row->Vatable;
					$comid = $row->Commission;
				}
			}
		}		
	}
	else
	{
		if(!isset($_GET['lname']))
		{
			$lname = isset($_POST['txtlnameDealer']) ? $_POST['txtlnameDealer'] : $lname;
			$fname = isset($_POST['txtfnameDealer']) ? $_POST['txtfnameDealer'] : $fname;
			$mname = isset($_POST['txtmnameDealer']) ? $_POST['txtmnameDealer'] : $mname;
			$bday = isset($_POST['txtbdaydealer']) ? $_POST['txtbdaydealer'] : $bday;
		}
		else
		{
			$lname = $_GET['lname'];
			$fname = $_GET['fname'];
			$mname = $_GET['mname'];
			$bday = $_GET['bday'];
		}			
	}
	
	/* credit limit factors 
         * 
         * @author: jdymosco
         * @date: Feb 20, 2013
         * @update: We changed the way of getting credit limit factors, instead of querying it we used the global
         *          setting of branch parameter session at start when logged in. In this case we already minimized the 
         *          loading of page.
         
	$rs_climit_factors = $sp->spGetCreditLimitFactors($database);
	if($rs_climit_factors->num_rows)
	{
		while($row = $rs_climit_factors->fetch_object())
		{
			$character_factor = $row->CharacterFactor;
			$capacity_factor = $row->CapacityFactor;
			$capital_factor = $row->CapitalFactor;
			$condition_factor = $row->ConditionFactor;
			$dclimit_factor = $row->DefaultCreditLimit;
		}
		$rs_climit_factors->close();
	}*/
        $character_factor = $_SESSION['Branch']->CharacterFactor;
        $capacity_factor = $_SESSION['Branch']->CapacityFactor;
        $capital_factor = $_SESSION['Branch']->CapitalFactor;
        $condition_factor = $_SESSION['Branch']->ConditionFactor;
        $dclimit_factor = $_SESSION['Branch']->DefaultCreditLimit;
        //EOL of update....
	
	/*DROP DOWN BOX*/
	 //$rs_cboCustomerType = $sp->spSelectCustomerType($database);
         $rs_cboCustomerType = tpi_getSalesForceLevel(1);
	 $rs_cboGSUType = $sp->spSelectGSUType($database);
	 $rs_cboClass = $sp->spSelectClass($database);
	 $rs_cboZone = $sp->spSelectZone($database);
	 $rs_cboCreditTerm = $sp->spSelectCreditTerm($database);
	 $rs_cboBranch = $sp->spSelectBranch($database, -1, '');
	 $rs_cboEducational = $sp->spSelectEducationalAttainment($database, -1);
	 $rs_cboMaritalStatus = $sp->spSelectMaritalStatus($database, -1);
	 $rs_cboProvince = $sp->spSelectProvince($database, -1);
	 $rs_cboPDACode = $sp->spSelectPDARARCode($database);
	 
	 if(isset($_POST['cboProvince']))
	 {
	 	$prov = $_POST['cboProvince'];
	 	$provinceid = $prov;
	 	$townid = 0;
	 	$barangayid = 0;
	 	$rs_cboTownCity = $sp->spSelectTownCity($database, -1, $prov);
	 }
	 else
	 {
	 	if ($provinceid == 0)
	 	{
	 		$townid = 0;
	 		$barangayid = 0;
	 		$rs_cboTownCity = $sp->spSelectTownCity($database, -1, -1);	 		
	 	}
	 	else
	 	{
	 		$rs_cboTownCity = $sp->spSelectTownCity($database, -1, $provinceid);
	 	}
	 }
	 
	 if(isset($_POST['cboTown']))
	 {
	 	$town = $_POST['cboTown'];
	 	$townid = $town;
	 	$barangayid = 0;
	 	$rs_cboBarangay = $sp->spSelectBarangay($database, -1, $town);
	 }
	 else
	 {
	 	if ($townid == 0)
	 	{
	 		$barangayid = 0;
	 		$rs_cboBarangay = $sp->spSelectBarangay($database, -1, -1);	 		
	 	}
	 	else
	 	{
	 		$rs_cboBarangay = $sp->spSelectBarangay($database, -1, $townid);
	 	}
	 }
	 
	 if(isset($_POST['cboBarangay']))
	 {
	 	$barangay = $_POST['cboBarangay'];
	 	$barangayid = $barangay;
	 	$rs_zipcode = $sp->spSelectBarangay($database, 3, $barangay);
	 	
	 	/* retrieve zip code */
		if($rs_zipcode->num_rows)
		{
			while($row_zip = $rs_zipcode->fetch_object())
			{
				$zipcode = $row_zip->Name;
			}
			$rs_zipcode->close();
		} 
	 }
	 else
	 {
	 	if ($barangayid == 0)
	 	{
	 		$rs_zipcode = $sp->spSelectBarangay($database, 3, 0);	 		
	 	}
	 	else
	 	{
	 		$rs_zipcode = $sp->spSelectBarangay($database, 3, $barangayid);
	 		
	 		/* retrieve zip code */
			if($rs_zipcode->num_rows)
			{
				while($row_zip = $rs_zipcode->fetch_object())
				{
					$zipcode = $row_zip->Name;
				}
				$rs_zipcode->close();
			} 	 		
	 	}
	 }
	/*END DROP DOWN BOX*/
	
	if(isset($_POST['btnSearch']))
	{
		//$custSearchTxt = addslashes($_POST['txtfldsearch']);	
		//$rs_customer = $sp->spSelectCustomer($database, -1,$custSearchTxt);
		//echo "condition 1!";
	}	
	
	if(isset($_POST['btnProceed']))
	{
		$lname = isset($_POST['txtlnameDealer']) ? $_POST['txtlnameDealer'] : $lname;
		$fname = isset($_POST['txtfnameDealer']) ? $_POST['txtfnameDealer'] : $fname;
		$mname = isset($_POST['txtmnameDealer']) ? $_POST['txtmnameDealer'] : $mname;
		$bday = isset($_POST['txtbdaydealer']) ? $_POST['txtbdaydealer'] : $bday;	
		
		redirect_to("index.php?pageid=69&action=new&lname=$lname&fname=$fname&mname=$mname&bday=$bday");
	}	
	elseif(isset($_POST['btnSearch']))
	{
		//$custSearchTxt = addslashes($_POST['txtfldsearch']);	
		//$rs_customer = $sp->spSelectCustomer($database,-1,$custSearchTxt);
		//echo "condition 1!";
	}	
	else
	{
		//if(isset($_GET['search']))
		//{
		//$search = $_GET['search'];
		//$rs_customer = $sp->spSelectCustomer($database,-1,$search);
		//}
		//else
		//{
		//$rs_customer = $sp->spSelectCustomer($database,0,"");
		//}
		//echo "condition 3!";
	}
	
	if(isset($_POST['btnCheck']))
	{		
		if(isset($_GET['custid']))
		{
			$custid=$_GET['custid'];
			$rs_existingcustomer = $sp->spSelectExistingCustomer($database, $custid, '', '', '', '');
		}
		else
		{
			$cd_lname=htmlentities(addslashes($_POST['txtlnameDealer']));
			$cd_fname=htmlentities(addslashes($_POST['txtfnameDealer']));
			$cd_mname=htmlentities(addslashes($_POST['txtmnameDealer']));
			$cd_bday=htmlentities(addslashes($_POST['txtbdaydealer']));
			if($cd_bday != '')
			{
				$bday = date("m-d-Y", strtotime($cd_bday));
				$cd_bday = date("Y-m-d", strtotime($bday));
			}
			
			$rs_existingcustomer = $sp->spSelectExistingCustomer($database, -1, $cd_fname, $cd_lname, $cd_mname, $cd_bday);
		}
	}
	
	if(isset($_POST['cboCustomerType']) && $_POST['cboCustomerType'] != 0)
	{
		$customertypeid = $_POST['cboCustomerType'];
	}
	
	if(isset($_POST['btnUpload']))
	{
		$myFile = $_FILES['file']['name'];
		$tmpName  = $_FILES['file']['tmp_name'];
		$fh = fopen($myFile, 'w') or die("can't open file");
		$content = fread($fh, filesize($tmpName));
		$stringData = $content;
		fwrite($fh, $stringData);
		fclose($fh);
		
		
		$target_path =  HO_UPLOADPATH.DS;

		$target_path = $target_path . basename( $_FILES['file']['name']); 
		
		if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		 
		    $_GET['errmsg'] = "The file ".  basename( $_FILES['file']['name']). 
		    " has been uploaded";
		} else{
		    $_GET['errmsg'] = "There was an error uploading the file, please try again!";
		}
		
		
		$applicationPath = HO_UPLOADPATH.DS.DS.$myFile;
			
		$query = "UPDATE tpi_customerdetails SET  ApplicationFilePath = " . "'$applicationPath'". " WHERE CustomerID = ". $_GET['custid'];
		
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		$rs = $mysqli->query($query);
		$mysqli->close();
		
	} 
		
?>