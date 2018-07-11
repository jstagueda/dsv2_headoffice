<?php

	if(isset($_POST['btnUpdate']))
	{
            
		$id = 0;
                if($_POST['hCOA'] != ''){
                    $cd_IBMID = $_POST['hCOA'];
                }else{
                    $cd_IBMID = 0;
                }
                
		if($_POST['txtcodeDealer'] != '')
		{
			$cd_igscode=htmlentities(addslashes($_POST['txtcodeDealer']));
		}
		else
		{
			$cd_igscode='null';
		}	
		if($_POST['txtlnameDealer'] != '')
		{
			$cd_lname=htmlentities(addslashes($_POST['txtlnameDealer']));
		}
		else
		{
			$cd_lname='null';
		}
		if($_POST['txtfnameDealer'] != '')
		{
			$cd_fname=htmlentities(addslashes($_POST['txtfnameDealer']));
		}
		else
		{
			$cd_fname = 'null';
		}
		if($_POST['txtmnameDealer'] != '')
		{
			$cd_mname=htmlentities(addslashes($_POST['txtmnameDealer']));
		}
		else
		{
			$cd_mname = 'null';
		}
		
		if($_POST['txtNickName'] != '')
		{
			$cd_nickname=htmlentities(addslashes($_POST['txtNickName']));
		}
		else
		{
			$cd_nickname='null';
		}
		
		if($_POST['txtbdaydealer'] != '')
		{
			$tmpTxnDate = strtotime($_POST['txtbdaydealer']);
			$cd_bday=date("Y/m/d", $tmpTxnDate);	
			$cd_bday=htmlentities(addslashes($cd_bday));
			
		}
		else
		{
			$cd_bday = 'null';
		}
		if($_POST['txtHomeTelNo'] != '')
		{
			$cd_telno=htmlentities(addslashes($_POST['txtHomeTelNo']));
		}
		else
		{
			$cd_telno = 'null';
		}
		
		if($_POST['txtCPNumber'] != '')
		{
			$cd_mobileno=htmlentities(addslashes($_POST['txtCPNumber']));
		}
		else
		{
			$cd_mobileno = 'null';
		}
		if($_POST['txtStAddress'] != '')
		{
			$cd_address=htmlentities(addslashes($_POST['txtStAddress']));
		}
		else
		{
			$cd_address = 'null';
		}	
		if($_POST['txtZipCode'] != '')
		{
			$cd_zipcode=htmlentities(addslashes($_POST['txtZipCode']));
		}
		else
		{
			$cd_zipcode = 'null';
		}
		if(isset($_POST['cboClass']))
		{
			$cd_class=htmlentities(addslashes($_POST['cboClass']));
		}
		else
		{
			$cd_class = 1;
		}		
		if(isset($_POST['cboCustomerType']))
		{
			$cd_dealertype=htmlentities(addslashes($_POST['cboCustomerType']));
		}
		else
		{
			$cd_dealertype = 1;
		}
		if(isset($_POST['cboGSUType']))
		{
			$cd_gsutype=htmlentities(addslashes($_POST['cboGSUType']));
		}
		else
		{
			$cd_gsutype = 1;
		}
		
		
		if($_POST['txtIBMNo'] != '')
		{
			$cd_ibmno=htmlentities(addslashes($_POST['txtIBMNo']));
		}
		else
		{
			$cd_ibmno =  'null';
		}
		if($_POST['txtibmname'] != '')
		{
			$cd_ibmname=htmlentities(addslashes($_POST['txtibmname']));
		}
		else
		{
			$cd_ibmname =  'null';
		}								
		if(isset($_POST['cboZone']))
		{
			$cd_zone=htmlentities(addslashes($_POST['cboZone']));
		}
		else
		{
			$cd_zone = 1;
		}								
		if($_POST['txtLStay'] != '')
		{
			$cd_lstay=htmlentities(addslashes($_POST['txtLStay']));
		}
		else
		{
			$cd_lstay =  1;
		}
		if(isset($_POST['cboMaritalStatus']))
		{
			$cd_marital=htmlentities(addslashes($_POST['cboMaritalStatus']));
		}
		else
		{
			$cd_marital =  1;
		}
		if(isset($_POST['cboEducational']))
		{
			$cd_educational=htmlentities(addslashes($_POST['cboEducational']));
		}
		else
		{
			$cd_educational =  1;
		}
		if($_POST['txtCompany1'] != '' AND isset($_POST['txtCompany1']))
		{
			$cd_directsellcomp=htmlentities(addslashes($_POST['txtCompany1']));
		}
		else
		{
			$cd_directsellcomp =  'null';
		}
		if($_POST['txtRemarks'] != '')
		{
			$cd_remarks=htmlentities(addslashes($_POST['txtRemarks']));
		}
		else
		{
			$cd_remarks =  'null';
		}
		if($_POST['cboCreditTerm'] != 0)
		{
			$cd_credittermid=htmlentities(addslashes($_POST['cboCreditTerm']));
		}
		else
		{
			$cd_credittermid =  1;
		}

		if($_POST['txtCharScore'] != '')
		{
				$cd_character=htmlentities(addslashes($_POST['txtCharScore']));
		}
		else
		{
			$cd_character =  1;
		}
		if($_POST['txtCapacityScore'] != '')
		{
			$cd_capacity=htmlentities(addslashes($_POST['txtCapacityScore']));		
		}
		else
		{
			$cd_capacity =  1;
		}
		if($_POST['txtCapitalScore'] != '')
		{
			$cd_capital=htmlentities(addslashes($_POST['txtCapitalScore']));		
		}
		else
		{
			$cd_capital =  1;
		}
		if($_POST['txtConditionScore'] != '')
		{
			$cd_condition=htmlentities(addslashes($_POST['txtConditionScore']));		
		}
		else
		{
			$cd_condition =  1;
		}
		if($_POST['txtCalculated'] != '')
		{
			$cd_calculatedcl=htmlentities(addslashes($_POST['txtCalculated']));		
		}
		else
		{
			$cd_calculatedcl =  1;
		}
		if($_POST['txtRecommended'] != '')
		{
			$cd_recommendedcl=htmlentities(addslashes($_POST['txtRecommended']));	
		}
		else
		{
			$cd_recommendedcl =  1;
		}
		if($_POST['txtApproved'] != '')
		{
			$cd_approvedcl=htmlentities(addslashes($_POST['txtApproved']));	
				
		}
		else
		{
			$cd_approvedcl =  1;
		}
		if($_POST['txtRecruiteAcctNo'] != '' )
		{
			$cd_recruiter=htmlentities(addslashes($_POST['txtRecruiteAcctNo']));	
		}
		else
		{
			$cd_recruiter =  'null';
		}
		
		if($_POST['txtTIN'] != '' )
		{
			$cd_TIN=htmlentities(addslashes($_POST['txtTIN']));	
		}
		else
		{
			$cd_TIN =  'null';
		}
		
		if($_POST['txtIBMCode'] != '' AND isset($_POST['txtIBMCode']))
		{
			$cd_ibmcode=htmlentities(addslashes($_POST['txtIBMCode']));	
		}
		else
		{
			$cd_ibmcode =  'null';
		}
		
		if($_POST['cboBarangay'] != '' )
		{
			$cd_areaid=htmlentities(addslashes($_POST['cboBarangay']));	
		}
		else
		{
			$cd_areaid =  'null';
		}
		
		if($_POST['cboPDACode'] != '' )
		{
			$cd_pdaid= htmlentities(addslashes($_POST['cboPDACode']));	
		}
		else
		{
			$cd_pdaid = 0;
		}
		
		
		$cd_directsellexp=($_POST['rdYesNo'] == 1) ? 9 : 10;
		$cd_empstatus=($_POST['rdEYesNo'] == 1) ? 11 : 12;
		
		if(isset($_GET['custid']))
		{
			$id = $_GET['custid'];			
		}		
		
		
		if($id > 0)
		{
			try
			{
				$database->beginTransaction();
				
				$affected_rows = $sp->spImprovedUpdateDealer($database, $id, $cd_lname, $cd_fname, $cd_mname, $cd_nickname, $cd_telno, $cd_mobileno, $cd_address, 
				$cd_zipcode, $cd_class, $cd_dealertype, $cd_gsutype, $cd_IBMID,$cd_ibmno, $cd_ibmname, $cd_zone, $cd_lstay, $cd_marital, $cd_educational,
				$cd_directsellexp, $cd_empstatus, $cd_directsellcomp, $cd_remarks, $cd_credittermid, $cd_character, $cd_capacity, $cd_capital,
				$cd_condition, $cd_calculatedcl, $cd_recommendedcl, $cd_approvedcl, $cd_recruiter, $cd_TIN, $cd_ibmcode, $cd_areaid, $cd_igscode, $cd_bday,
				$cd_pdaid);
					
				$txtbx = $_POST["textbx"];
				foreach ($txtbx as $key=>$ID) 
				{
					$insert = $sp->spInsertCompany($database, $id, $ID);
				}
				
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$database->commitTransaction();
				
				$message = "Successfully updated record.";		
				redirect_to("index.php?pageid=70&msg=$message");		
			}
			catch(Exception $e)
			{
				$database->rollbackTransaction();
				$errmsg = $e->getMessage()."\n";	
				redirect_to("index.php?pageid=70&msg=$errmsg");
			}
		}
	}
?>