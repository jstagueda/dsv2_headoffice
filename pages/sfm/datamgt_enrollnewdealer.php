<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script src="js/jxPagingCreateDealer.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmDealerMgt.js"></script>
<?PHP 
	ini_set('display_errors',1);	
	include IN_PATH.DS."scCustomer.php";
	include IN_PATH.DS."pcDealer.php";
	global $database;
	//initialize customer id
	$custid = 0;
	if(isset($_GET['custid']))
	{
		$custid = $_GET['custid'];
	}
	
	//initialize variables
	$chk = ''; $chk2=''; $chk3=''; $chk4='';
	$otherdetails = ''; $directselling = ''; $recruiter = ''; $credit = ''; $remarks = ''; $bankinfo = '';
	
	$otherdetails = "<a onclick=toggleDivTag('div1') href=#>Other Details</a>";
	$directselling = "<a onclick=toggleDivTag('div2') href=#>Direct Selling</a>";
	$recruiter = "<a onclick=toggleDivTag('div3') href=#>Recruiter</a>";
	$credit = "<a onclick=toggleDivTag('div4') href=#>Credit</a>";
	$remarks = "<a onclick=toggleDivTag('div5') href=#>Remarks</a>";
	$bankinfo = "<a onclick=toggleDivTag('div6') href=#>Bank Info</a>";
  
	//display error message
	$errMessage = '';
	if (isset($_GET['msg']))
    {
       $message = strtolower($_GET['msg']);
       $success = strpos("$message","success"); 
       $errMessage= "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
    }
    else if (isset($_GET['errmsg']))
    {
		$errormessage = strtolower($_GET['errmsg']);
		$error = strpos("$errormessage","error"); 
		$errMessage = "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";                        	
    }
	
	//display customers
//	$customerRows = '';
//	if ($rs_customer->num_rows)
//	{
//		$search = '';
//		if (isset($_POST['txtfldsearch']))
//		{
//			$search = $_POST['txtfldsearch'];
//		}
//		else
//		{
//			if (isset($_GET['search']))
//			{
//				$search = $_GET['search'];
//			}
//		}
//
//		while ($row = $rs_customer->fetch_object())
//		{
//		   $customerRows .= "<tr align='center'><td width='40%' height='20' class='borderBR' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
//			  <td width='60%' class='borderBR' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=69&custid=$row->ID&search=$search' class='txtnavgreenlink'>"
//		   	   .$row->FirstName." ".$row->MiddleName." ".$row->LastName." </a></span></td></tr>";
//		}
//		$rs_customer->close();
//	}
//	else
//	{
//		$customerRows = "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td></tr>";
//	}
	
    //display existing records
    $existingRecords = ''; $existCustomerRows = '';
    $strChecker = array();
    if(isset($_POST['btnCheck']))
    {
    	if ($rs_existingcustomer->num_rows)
		{
			while ($rowCust = $rs_existingcustomer->fetch_object())
			{
                                //comparison for confirmation message of proceed button...
                                if($_POST['txtlnameDealer'] == trim($rowCust->LastName) && $_POST['txtbdaydealer'] == trim($rowCust->Birthdate)) $strChecker[] = 'yes';
                                else $strChecker[] = 'no';
                            //echo $_POST['txtlnameDealer'].' '.$rowCust->LastName.' '.$_POST['txtbdaydealer'].' '.$rowCust->Birthdate;
				$balance = number_format($rowCust->Balance, 2, '.', '');
                                $writeOffAmt = number_format($rowCust->DWOPastDue, 2, '.', '');
                                $penaltyAmt = number_format(tpi_get_dealer_penalty($rowCust->AmountDue,$rowCust->PastDueInDays), 2, '.', '');
                                $currentBal = number_format($rowCust->Balance, 2, '.', '');
                                $PDACode = empty($rowCust->PDACode) ? 'No PDA' : $rowCust->PDACode;
                                
			   	$existCustomerRows .= "<tr align='center'><td width='2%' height='20' class='borderBR padl5' align='left'><span class='txt10'>$rowCust->Code</span></td>
				 <td width='3%' class='borderBR padl5' align='left'><span class='txt10'><a href='javascript:void(0)' onclick='return openPopUp($rowCust->ID);'class='txtnavgreenlink'>$rowCust->Name</a></span></td>
				 <td width='2%' height='20' class='borderBR padl5' align='left'><span class='txt10'>$rowCust->IBMCode</span></td>
				 <td width='2%' height='20' class='borderBR' align='center'><span class='txt10'>$rowCust->Status</span></td>
				 <td width='2%' height='20' class='borderBR' align='center'><span class='txt10'>$rowCust->DateTerminated</span></td>
				 <td width='2%' height='20' class='borderBR padr5' align='right'><span class='txt10'>$balance</span></td>
                                 <td width='2%' height='20' class='borderBR padr5' align='right'><span class='txt10'>$writeOffAmt</span></td>
                                 <td width='2%' height='20' class='borderBR padr5' align='right'><span class='txt10'>$penaltyAmt</span></td>
                                 <td width='2%' height='20' class='borderBR padr5' align='right'><span class='txt10'>$currentBal</span></td>
                                 <td width='2%' height='20' class='borderBR padr5' align='right'><span class='txt10'>$PDACode</span></td></tr>";
			}
                        
                        if(in_array('yes',$strChecker)) $sameVal = '1';
                        else $sameVal = '0';
                        
                        $existCustomerRows.="<input type=\"hidden\" name=\"same-details\" id=\"same-details\" value=\"$sameVal\" />";
			$rs_existingcustomer->close();
		}
		else
		{
			$existCustomerRows = "<tr align='center'><td height='20' colspan='6' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</span></b></td></tr>";
                        $existCustomerRows.="<input type=\"hidden\" name=\"same-details\" id=\"same-details\" value=\"0\" />";
		}
		
    	$existingRecords = "<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
		        <tr>
		          <td class='tabmin'>&nbsp;</td>
		          <td class='tabmin2'><span class='txtredbold'>Existing Records</span></td>
		          <td class='tabmin3'>&nbsp;</td>
		        </tr>
		      </table>
		      <table width='100%'  border='0' align='center' cellpadding='0' cellspacing='1' class='bordergreen' id='tbl2'>
		        <tr>
		          <td valign='top' class='bgF9F8F7'><table width='100%'  border='0' align='center' cellpadding='0' cellspacing='1'>
		              <tr>
		                <td><table width='100%'  border='0' cellpadding='0' cellspacing='1' class='tab txtdarkgreenbold10'>
		                    <tr align='center'>
		                      <td width='10%'><div align='left' class='bdiv_r padl5'>IGS Code</div></td>
		                      <td width='25%'><div align='left' class='bdiv_r padl5'>IGS Name</div></td>
		                      <td width='20%'><div align='left' class='bdiv_r padl5'>IBM Code - Name</div></td>
		                      <td width='15%'><div align='center' class='bdiv_r padr5'>Status</div></td>
		                      <td width='15%'><div align='center' class='bdiv_r padr5'>Date Terminated</div></td>
		                      <td width='15%'><div align='right' class='bdiv_r padr5'>Past Due</div></td>
                                      <td width='15%'><div align='right' class='bdiv_r padr5'>Write-off Amount</div></td>
                                      <td width='15%'><div align='right' class='bdiv_r padr5'>Penalty Amount</div></td>
                                      <td width='15%'><div align='right' class='bdiv_r padr5'>Current Balance</div></td>
                                      <td width='15%'><div align='right' class='padr5'>PDA-RAR Code</div></td>
		                </table></td>
		              </tr>
		              <tr>
		                <td class='bordergreen_B'><div class='scroll_300'>
                        <table width='100%'  border='0' cellpadding='0' cellspacing='1' class='bgFFFFFF'>
						 	$existCustomerRows
	                   </table>
                        </div>
	                    </td>
		              </tr>
		          </table>
					</td>
		        </tr>
		      </table><br>
          			<table width='100%'  border='0' cellpadding='0' cellspacing='0'>
			   		<tr>
						<td align='center'><input type='submit' class='btn' value='Proceed' name='btnProceed'></td>
					</tr>
					</table>";
    } 
   
    
   
	
    //display check/cancel buttons
    $checkButtonDisplay = '';
    $updateButtonDisplay = '';
	$saveButtonDisplay = '';
    $additionaltable= '';
    $additionalFields = '';
    if(!isset($_GET['action']) || ($_GET['action'] <> 'update' && $_GET['action'] <> 'new'))
    {
    	$checkButtonDisplay = "<input name='btnCheck' type='submit' class='btn' value='Check'/>
                <input name='btnCancel' type='button' class='btn' value='Cancel' onclick=window.location.href='index.php?pageid=69' />";
    
    }
    else
    {
	    //file download
	    $additionalCompany = '';
	    $applicationForm='';
	    if(isset($_GET['custid']) && $_GET['custid'] != '')
	    {
			if(!$rsFile->num_rows)
			{
				$applicationForm = "<input type='hidden' name='MAX_FILE_SIZE' value='2097152' />
			                <input type='file' name='file' class='btn' id='file'>
			                 <input name='btnUpload' type='submit' class='btn' value='Upload'>";
			}
			else
			{
				while ($row = $rsFile->fetch_object())
				{
					$customerid = $row->CustomerID;
					$applicationfilepath = $row->ApplicationFilePath;
					list($str1, $str2, $str3) = explode("\\", $applicationfilepath, 3);
					$applicationForm = "<br><a href='testfile.php?filepath=$applicationfilepath'>$str3</a>";
				}
				$rsFile->close();
			}
	    }
	    else
	    {
	    	$applicationForm = "<input type='hidden' name='MAX_FILE_SIZE' value='2097152' />
			                <input type='file' name='file' class='btn' id='file'>
			                 <input name='btnUpload' type='submit' class='btn' value='Upload'>";
	    }
    	
    	$customerRemarks = $customerRemarks == 'null' ? '' : $customerRemarks;
    	$company = $company == 'null' ? '' : $company;
		if($_GET['action'] == 'update')
		{
	    	$nickname = (isset($_POST['txtNickName'])) ? $_POST['txtNickName'] : $nickname;
	    	$telno = (isset($_POST['txtHomeTelNo'])) ? $_POST['txtHomeTelNo'] : $telno;
	    	$mobileno = (isset($_POST['txtCPNumber'])) ? $_POST['txtCPNumber'] : $mobileno;
	    	$address = (isset($_POST['txtStAddress'])) ? $_POST['txtStAddress'] : $address;
	    	//$zipcode = (isset($_POST['txtZipCode'])) ? $_POST['txtZipCode'] : $zipcode;
	    	$length = (isset($_POST['txtLStay'])) ? $_POST['txtLStay'] : $length;
	    	$customerRemarks = (isset($_POST['txtRemarks'])) ? $_POST['txtRemarks'] : $customerRemarks;
	    	$company = (isset($_POST['txtCompany1'])) ? $_POST['txtCompany1'] : $company;
	    	$tin = (isset($_POST['txtTIN'])) ? $_POST['txtTIN'] : $tin;
	    	$ibmname = (isset($_POST['txtibmname'])) ? $_POST['txtibmname'] : $ibmname;
	    	$ibmcode = (isset($_POST['txtIBMNo'])) ? $_POST['txtIBMNo'] : $ibmcode;
	    		 
			if ($rsExistCompany->num_rows)
			{
				while ($row = $rsExistCompany->fetch_object())
				{
				   $additionalCompany .= "<input type='text' name='textbx[]' id='textbx' class='txtfield' value='$row->Details'/><br>";
				}
				$rsExistCompany->close();
			}
			else
			{
				$additionalCompany = "<input type='text' name='textbx[]' id='textbx' class='txtfield'/>";
			}
			
			if((isset($_POST['cboCustomerType']) && ($_POST['cboCustomerType']== 1 || $_POST['cboCustomerType'] == 4))
			|| $customertypeid == 1 || $customertypeid == 4)
	    	{
	    		$bankinfo = '';
	    	}
	    	else
	    	{
	    		$bankinfo = "<a onclick=toggleDivTag('div6') href=#>Bank Info</a>";
	    	}
				
		}
		else
		{
			$nickname = (isset($_POST['txtNickName'])) ? $_POST['txtNickName'] : '';
	    	$telno = (isset($_POST['txtHomeTelNo'])) ? $_POST['txtHomeTelNo'] : '';
	    	$mobileno = (isset($_POST['txtCPNumber'])) ? $_POST['txtCPNumber'] : '';
	    	$address = (isset($_POST['txtStAddress'])) ? $_POST['txtStAddress'] : '';
	    	//$zipcode = (isset($_POST['txtZipCode'])) ? $_POST['txtZipCode'] : '';
	    	$length = (isset($_POST['txtLStay'])) ? $_POST['txtLStay'] : '';
	    	$customerRemarks = (isset($_POST['txtRemarks'])) ? $_POST['txtRemarks'] : '';
	    	$company = (isset($_POST['txtCompany1'])) ? $_POST['txtCompany1'] : '';
	    	$tin = (isset($_POST['txtTIN'])) ? $_POST['txtTIN'] : '';
	    	$ibmname = (isset($_POST['txtibmname'])) ? $_POST['txtibmname'] : '';
	    	$ibmcode = (isset($_POST['txtIBMNo'])) ? $_POST['txtIBMNo'] : '';
                
                /*
                 * Added line of code for getting IBM No. from manage page redirect.
                 * @author: jdymosco
                 * @date: Feb. 01, 2013
                 */
                if(isset($_GET['IBMCode']) && !empty($_GET['IBMCode'])){ 
                    $ibmcode =  $_GET['IBMCode']; 
                    $autoCompActivate = "coa_choices.activate();";
                }else{
                    $autoCompActivate = '';
                }
                //End of added
	    	
			if((isset($_POST['cboCustomerType']) && ($_POST['cboCustomerType']== 1 || $_POST['cboCustomerType'] == 4))
			|| $customertypeid == 1 || $customertypeid == 4)
	    	{
	    		$bankinfo = '';
	    	}
	    	else
	    	{
	    		$bankinfo = "<a onclick=toggleDivTag('div6') href=#>Bank Info</a>";
	    	}
		}
		
    	
    	//initialize radio button value	
		if($yesno==1)
		{
			$chk = "checked";
			$chk2 = "";
		}
		else
		{
			$chk = "";
			$chk2 = "checked";
		}	
		
		if($eyesno==1)
		{
			$chk3 = "checked";
			$chk4 = "";
		}
		else
		{
			$chk3 = "";
			$chk4 = "checked";
		}	
    	
	     //gsutype dropdown
	    $drpGSUType = '<option value=\'0\' >[SELECT HERE]</option>';
	    if ($rs_cboGSUType->num_rows)
	    {
	     while ($row = $rs_cboGSUType->fetch_object())
	      {
	      	if($_GET['action'] == 'new')
	      	{
		      	$gsutypeid = (isset($_POST['cboGSUType'])) ? $_POST['cboGSUType'] : $gsutypeid;
		      	($gsutypeid == $row->ID) ? $sel = 'selected' : $sel = '';
		        $drpGSUType .= "<option value='$row->ID' $sel>$row->Name</option>";
	      	}
	      	else
	      	{
	      		$gsutypeid = (isset($_POST['cboGSUType'])) ? $_POST['cboGSUType'] : $gsutypeid;
		      	($gsutypeid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
		        $drpGSUType .= "<option value='$row->ID' $sel>$row->Name</option>";
	      	}
	       }
	    }
	    
	    //class dropdown
	    $drpClass = '<option value=\'0\' >[SELECT HERE]</option>';
	     if ($rs_cboClass->num_rows)
	    {
	     while ($row = $rs_cboClass->fetch_object())
	      {
	      	if($_GET['action'] == 'new')
	      	{
	      		$classid = (isset($_POST['cboClass'])) ? $_POST['cboClass'] : $classid;
	      		($classid == $row->ID) ? $sel = 'selected' : $sel = '';
		        $drpClass .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	      	else
	      	{
	      		$classid = (isset($_POST['cboClass'])) ? $_POST['cboClass'] : $classid;
		      	($classid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
		        $drpClass .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	       }
	    }
	    
	    //customer type dropdown
	    $drpCustomerType = '<option value=\'0\' >[SELECT HERE]</option>';
	     if ($rs_cboCustomerType->num_rows)
	    {
	     while ($row = $rs_cboCustomerType->fetch_object())
	      {
	      	if($_GET['action'] == 'new')
	      	{
		      	($customertypeid == $row->codeID) ? $sel = 'selected' : $sel = '';
		        $drpCustomerType .= "<option value='$row->codeID'' $sel>$row->desc_code</option>";
	      	}
	      	else
	      	{
	      		($customertypeid == $row->codeID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
		        $drpCustomerType .= "<option value='$row->codeID'' $sel>$row->desc_code</option>";
	      	}
	       }
	    }
	    
     	//marital status dropdown
	    $drpMaritalStatus = '<option value=\'0\' >[SELECT HERE]</option>';
	     if ($rs_cboMaritalStatus->num_rows)
	    {
	     while ($row = $rs_cboMaritalStatus->fetch_object())
	      {
	      	if($_GET['action'] == 'new')
	      	{
	      		$fieldid = (isset($_POST['cboMaritalStatus'])) ? $_POST['cboMaritalStatus'] : $fieldid;
	      		($fieldid == $row->ID) ? $sel = 'selected' : $sel = '';
		        $drpMaritalStatus .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	      	else
	      	{
	      		$fieldid = (isset($_POST['cboMaritalStatus'])) ? $_POST['cboMaritalStatus'] : $fieldid;
		      	($fieldid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
		        $drpMaritalStatus .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	       }
	    }
	    
     	//educational attainment dropdown
	    $drpEducational = '<option value=\'0\' >[SELECT HERE]</option>';
	     if ($rs_cboEducational ->num_rows)
	    {
	     while ($row = $rs_cboEducational ->fetch_object())
	      {
	      	if($_GET['action'] == 'new')
	      	{
	      		$efieldid = (isset($_POST['cboEducational'])) ? $_POST['cboEducational'] : $efieldid;
	      		($efieldid == $row->ID) ? $sel = 'selected' : $sel = '';
	        	$drpEducational  .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	      	else
	      	{
	      		$efieldid = (isset($_POST['cboEducational'])) ? $_POST['cboEducational'] : $efieldid;
	      		($efieldid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
	        	$drpEducational  .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	       }
	    }
	    
   	 	//zone dropdown
	    $drpZone = '<option value=\'0\' >[SELECT HERE]</option>';
	     if ($rs_cboZone ->num_rows)
	    {
	     while ($row = $rs_cboZone ->fetch_object())
	      {
	      	if($_GET['action'] == 'new')
	      	{
	      		$zoneid = (isset($_POST['cboZone'])) ? $_POST['cboZone'] : $zoneid;
	      		($zoneid == $row->ID) ? $sel = 'selected' : $sel = '';
	        	$drpZone .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	      	else
	      	{
	      		$zoneid = (isset($_POST['cboZone'])) ? $_POST['cboZone'] : $zoneid;
	      		($zoneid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
	        	$drpZone .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	       }
	    }
	    
     	//credit term
	    $drpCreditTerm = '<option value=\'0\' >[SELECT HERE]</option>';
            $credittermid = (($credittermid <= 0 || empty($credittermid)) ? 2 : $credittermid);
	    if ($rs_cboCreditTerm->num_rows)
	    {
	     while ($row = $rs_cboCreditTerm->fetch_object())
	      {
	      	($credittermid == $row->ID) ? $sel = 'selected' : $sel = '';
	        $drpCreditTerm .= "<option value='$row->ID'' $sel>$row->Name</option>";
	       }
	    }
	    
    	//province
	    $drpProvince = '<option value=\'0\' >[SELECT HERE]</option>';
	    if ($rs_cboProvince->num_rows)
	    {
	     while ($row = $rs_cboProvince->fetch_object())
	      {
	      	if($_GET['action'] == 'new')
	      	{
		      	($provinceid == $row->ID) ? $sel = 'selected' : $sel = '';
		        $drpProvince .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	      	else
	      	{
	      		($provinceid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
		        $drpProvince .= "<option value='$row->ID'' $sel>$row->Name</option>";
	      	}
	       }
	    }
	    
    	//town/city
	    if( !isset($_GET['Param']) )
   		{
		    $drpTownCity = '<option value=\'0\' >[SELECT HERE]</option>';
		    if ($rs_cboTownCity->num_rows)
		    {
		     while ($row = $rs_cboTownCity->fetch_object())
		      {
		      	if($_GET['action'] == 'new')
		      	{
			      	($townid == $row->ID) ? $sel = 'selected' : $sel = '';
			        $drpTownCity .= "<option value='$row->ID'' $sel>$row->Name</option>";
		      	}
		      	else
		      	{
		      		($townid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
			        $drpTownCity .= "<option value='$row->ID'' $sel>$row->Name</option>";
		      	}
		       }
		    }
   		}
	    
    	//barangay
   		if( !isset($_GET['Param']) )
   		{
		    $drpBarangay = '<option value=\'0\' >[SELECT HERE]</option>';
		    if ($rs_cboBarangay->num_rows)
		    {
		     while ($row = $rs_cboBarangay->fetch_object())
		      {
		      	($barangayid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
		        $drpBarangay .= "<option value='$row->ID'' $sel>$row->Name</option>";
		       }
		    }
   		}
	    
	    $drpPDARARCode = '';
	    $drpPDACode='';
   		 if((isset($_POST['cboCustomerType']) && ($_POST['cboCustomerType']== 1 || $_POST['cboCustomerType'] == 4))
		|| $customertypeid == 1 || $customertypeid == 4)
    	{
	    	$drpPDACode = '<option value=\'0\' >[SELECT HERE]</option>';
		    if ($rs_cboPDACode->num_rows)
		    {
		     while ($row = $rs_cboPDACode->fetch_object())
		      {
		      	if($_GET['action'] == 'new')
		      	{
			      	$pdaid = (isset($_POST['cboPDACode'])) ? $_POST['cboPDACode'] : $pdaid;
			      	($pdaid == $row->ID) ? $sel = 'selected' : $sel = '';
			        $drpPDACode .= "<option value='$row->ID' $sel>$row->Name</option>";
			        $drpPDARARCode = "<tr>
	                <td height='30' align='right' class='txt10'>PDA-RAR Code :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'><select name='cboPDACode' class='txtfield' style='width:180'>$drpPDACode</select>
	                </td></tr>";
		      	}
		      	else
		      	{
		      		$pdaid = (isset($_POST['cboPDACode'])) ? $_POST['cboPDACode'] : $pdaid;
			      	($pdaid == $row->ID && $_GET['action'] == 'update') ? $sel = 'selected' : $sel = '';
			        $drpPDACode .= "<option value='$row->ID' $sel>$row->Name</option>";
			        $drpPDARARCode = "<tr>
	                <td height='30' align='right' class='txt10'>PDA-RAR Code :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'><select name='cboPDACode' class='txtfield' style='width:180'>$drpPDACode</select>
	                </td></tr>";
		      	}
		       }
		    }
    	}
	    
    	//bank info
    	$drpBIRCOR = ''; $drpBIROR=''; $drpVAT=''; $drpCommission='';
    	if($bircorid == 0)
    	{
    		$drpBIRCOR = "<select name='cboBIRCOR' class='txtfield2' >
							<option value='0' selected>[SELECT HERE]</option>	
							<option value='1' >Yes</option>	
							<option value='2' >No</option>			
	                	</select>";
    	}
    	else
    	{
    		if ($bircorid == 1)
    		{
    			$drpBIRCOR =  "<select name='cboBIRCOR' class='txtfield2' >
							<option value='0' >[SELECT HERE]</option>	
							<option value='1' selected>Yes</option>	
							<option value='2' >No</option>			
	                	</select>";
    		}
    		else
    		{
    			$drpBIRCOR =  "<select name='cboBIRCOR' class='txtfield2' >
							<option value='0' >[SELECT HERE]</option>	
							<option value='1' >Yes</option>	
							<option value='2' selected>No</option>			
	                	</select>";
    		}
    	}
    	
    	if($birorid == 0)
    	{
    		$drpBIROR = "<select name='cboBIROR' class='txtfield2' >
							<option value='0' selected>[SELECT HERE]</option>	
							<option value='1' >Yes</option>	
							<option value='2' >No</option>			
	                	</select>";
    	}
    	else
    	{
    		if ($birorid == 1)
    		{
    			$drpBIROR =  "<select name='cboBIROR' class='txtfield2' >
							<option value='0' >[SELECT HERE]</option>	
							<option value='1' selected>Yes</option>	
							<option value='2' >No</option>			
	                	</select>";
    		}
    		else
    		{
    			$drpBIROR =  "<select name='cboBIROR' class='txtfield2' >
							<option value='0' >[SELECT HERE]</option>	
							<option value='1' >Yes</option>	
							<option value='2' selected>No</option>			
	                	</select>";
    		}
    	}
    	
    	if($vatid == 0)
    	{
    		$drpVAT = "<select name='cboVAT' class='txtfield2' >
							<option value='0' selected>[SELECT HERE]</option>	
							<option value='1' >Yes</option>	
							<option value='2' >No</option>			
	                	</select>";
    	}
    	else
    	{
    		if ($vatid == 1)
    		{
    			$drpVAT =  "<select name='cboVAT' class='txtfield2' >
							<option value='0' >[SELECT HERE]</option>	
							<option value='1' selected>Yes</option>	
							<option value='2' >No</option>			
	                	</select>";
    		}
    		else
    		{
    			$drpVAT =  "<select name='cboVAT' class='txtfield2' >
							<option value='0' >[SELECT HERE]</option>	
							<option value='1' >Yes</option>	
							<option value='2' selected>No</option>			
	                	</select>";
    		}
    	}
		
    	if($comid == 0)
    	{
    		$drpCommission = "<select name='cboCommission' class='txtfield2' >
							<option value='0' selected>[SELECT HERE]</option>	
							<option value='1' >Yes</option>	
							<option value='2' >No</option>			
	                	</select>";
    	}
    	else
    	{
    		if ($comid == 1)
    		{
    			$drpCommission =  "<select name='cboCommission' class='txtfield2' >
							<option value='0' >[SELECT HERE]</option>	
							<option value='1' selected>Yes</option>	
							<option value='2' >No</option>			
	                	</select>";
    		}
    		else
    		{
    			$drpCommission =  "<select name='cboCommission' class='txtfield2' >
							<option value='0' >[SELECT HERE]</option>	
							<option value='1' >Yes</option>	
							<option value='2' selected>No</option>			
	                	</select>";
    		}
    	}
    	
	    //display ibm fields
	    $ibmfields ='';
	  
    	if($customertypeid == 2 || $customertypeid == 3 || $customertypeid == 5)
    	{
    		$ibmfields = "
    				<tr>
                <td height='30' align='right' class='txt10'>IBM Code :</td>
                <td height='30'></td>
                <td height='30'>
                <input name='txtIBMCode' type='text' class='txtfield' id='txtIBMCode' value='$ibmcode2'/>
		  			</td></tr>";
    		
    	}

	    
    	$updateButtonDisplay = "<br>
		<table width='100%' border='0' align='center' cellpadding='0' cellspacing='1'>
		<tr>
			<td align='center'>
				<input name='btnUpdate' type='submit' class='btn' value='Update' onclick='return form_validation2();'>
		    	<input name='btnDelete' type='submit' class='btn' value='Delete'>
		    	<input name='btnClear' type='button' class='btn' value='Cancel' onclick=window.location.href='index.php?pageid=69' >
		    </td>
		</tr>
		</table>";
    	$saveButtonDisplay = "<br>
		<table width='100%' border='0' align='center' cellpadding='0' cellspacing='1'>  
		<tr>
			<td align='center'>
				<input name='btnUpdate' type='submit' class='btn' value='Save'>
		    	<input name='btnDelete' type='submit' class='btn' value='Delete'>
		    	<input name='btnClear' type='button' class='btn' value='Cancel' onclick=window.location.href='index.php?pageid=69' >
		    </td>
	  	</tr>
		</table>";
    	//display additional fields onclick='return form_validation2();'
    	$additionalFields = "
    	<tr>
              <td height='30' align='right' class='txt10'>Classification :</td>
              <td height='30'>&nbsp;</td>
              <td height='30'>
               	  <select name='cboClass' style='width:150px' class='txtfield'>
                        $drpClass
                    </select>
              </td>
            </tr>
            <tr>
              <td height='30' align='right' class='txt10'>Sales Force Level :</td>
              <td height='30'>&nbsp;</td>
              <td height='30'>
               	  <select name='cboCustomerType' style='width:150px' class='txtfield' onchange='frmDealer.submit();'>
                        $drpCustomerType
                    </select>
                    <input name='hCtype' type='hidden' id='hCtype' />	
              </td>
            </tr>
             <tr>
              <td height='30' align='right' class='txt10'>GSU Type :</td>
              <td height='30'>&nbsp;</td>
              <td height='30'>
               	  <select name='cboGSUType' style='width:150px' class='txtfield' >
	                    $drpGSUType
                    </select>
              </td>
            </tr>
            $ibmfields
            <tr>
                <td height='30' align='right' class='txt10'>IBM No. :</td>
                <td height='30'></td>
                <td height='30'>
                <input name='txtIBMNo' type='text' class='txtfield' id='txtIBMNo' value='$ibmcode'/>							
							<span id='indicator1' style='display: none'><img src='images/ajax-loader.gif' alt='Working...' /></span>                                      
							<div id='coa_choices' class='autocomplete' style='display:none'></div>
							<script type='text/javascript'>							
								 //<![CDATA[
                                        var coa_choices = new Ajax.Autocompleter('txtIBMNo', 'coa_choices', 'pages/ajax_request/sfmIBMsAutoCompleteAjax.php', {afterUpdateElement : getSelectionCOAID, indicator: 'indicator1'});																			
                                        $autoCompActivate
                                        //]]>
							</script>
							<input name='hCOA' type='hidden' id='hCOA'/>	
			  			</td></tr> 
			  	<tr>
                <td height='30' align='right' class='txt10'>IBM Name :</td>
                <td height='30'></td>
                <td height='30'><input type='text' name='txtibmname' maxlength='50' size='10' readonly class='txtfield' value='$ibmname'></td>
            </tr>
            <tr style=\"display: none;\">
                <td height='30' align='right' class='txt10'>TIN :</td>
                <td height='30'></td>
                <td height='30'><input type='text' name='txtTIN' maxlength='50' size='10' class='txtfield' value='$tin'></td>
            </tr>";
	                   
	//display tabs for updating...
    $additionaltable="
	<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0' >
	  <tr>
		<td>
		
		
		<div class='TabView' id='TabView'>
		<div class='Tabs' style='width: 5000;'>
			$otherdetails $directselling $recruiter $credit $remarks $bankinfo
		</div>
		<div class='Pages' style='border-left:1px solid #959F63;border-bottom:1px solid #959F63;border-right:1px solid #959F63;border-top:1px solid #959F63; width: auto;'>
		  <div class='Page' id='div1' style='display: block;'>
		  	<div class='Pad'>
		  	<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0' id='tbl2'>
	         <tr>
	           <td class='bgF9F8F7'></td><td class='bgF9F8F7'></td>
	         </tr>
	         <tr>
	           <td class='bgF9F8F7'>&nbsp;</td><td class='bgF9F8F7'></td>
	         </tr>
	        <tr>
	           <td class='bgF9F8F7' align='center'>
	            <table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
	            <tr>
	                <td width='40%' height='30' align='right' class='txt10'>Nickname :</td>
	                <td width='2%' height='30'>&nbsp;</td>
	                <td width='58%' height='30'><input type='text' name='txtNickName' maxlength='50' size='40' class='txtfield' value='$nickname'></td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Home Telephone Number :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'><input type='text' name='txtHomeTelNo' maxlength='50' size='40' class='txtfield' value='$telno' onkeyup='javascript:RemoveInvalidChars(txtHomeTelNo);'/></td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Cellphone Number :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'><input type='text' name='txtCPNumber' maxlength='50' size='10' class='txtfield' value='$mobileno' onkeyup='javascript:RemoveInvalidChars(txtCPNumber);'></td>
	            </tr>
	            <tr>
	              <td height='30' align='right' class='txt10'>Street Address :</td>
	              <td height='30'>&nbsp;</td>
	             <td height='30'><input type='text' name='txtStAddress' maxlength='50' size='10' class='txtfield' value='$address' onkeyup='javascript:RemoveInvalidChars(txtStAddress);'></td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>State/Province :</td>
	              <td height='30'>&nbsp;</td>
	               <td height='30'>	
	             	<select name='cboProvince' class='txtfield' style='width:180' onchange='ajaxFunctionTown(cboTown, this.value)'>
						$drpProvince			
	                </select>
	              </td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Town/City :</td>
	              <td height='30'>&nbsp;</td>
	              <td height='30'>	
	             	<select name='cboTown' id='cboTown' class='txtfield' style='width:180'  onchange='ajaxFunctionBarangay(cboBarangay, this.value)'>
						$drpTownCity			
	                </select>
	              </td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Barangay :</td>
	              <td height='30'>&nbsp;</td>
	             <td height='30'>	
	             	<select name='cboBarangay' id='cboBarangay' class='txtfield' style='width:180' onchange='ajaxFunctionZipCode(cboBarangay, this.value)'>
						$drpBarangay		
	                </select>
	              </td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Zip Code :</td>
	              <td height='30'>&nbsp;</td>
	             <td height='30'><input type='text' id='txtZipCode' name='txtZipCode' maxlength='50' size='10' class='txtfield' value='$zipcode' onkeyup='javascript:RemoveInvalidChars(txtmnameDealer);' readonly='yes'></td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Zone :</td>
	              <td height='30'>&nbsp;</td>
	              <td height='30'>	
	             	<select name='cboZone' class='txtfield' style='width:180'>
						$drpZone			
	                </select>
	              </td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Length of Stay :</td>
	              <td height='30'>&nbsp;</td>
	             <td height='30'><input type='text' name='txtLStay' maxlength='50' size='10' class='txtfield' value='$length' onkeyup='javascript:RemoveInvalidChars(txtmnameDealer);'></td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Marital Status :</td>
	              <td height='30'>&nbsp;</td>
	              <td height='30'>	
	             	<select name='cboMaritalStatus' class='txtfield' style='width:180'>
						$drpMaritalStatus			
	                </select>
	              </td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Educational Attainment :</td>
	              <td height='30'>&nbsp;</td>
	              <td height='30'>	
	             	<select name='cboEducational' class='txtfield' style='width:180'>
						$drpEducational			
	                </select>
	              </td>
	            </tr>
	            </table>
	            </td>
	            <td style='width: 30%;' class='bgF9F8F7'></td>
	            </tr>				
	            </table>						
		  	</div>
		  </div>		  
		  <div class='Page' id='div2' style='display: none;'>
		  	<div class='Pad'>
		  	<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0' id='tbl2'>
	         <tr>
	           <td class='bgF9F8F7'></td>
	         </tr>
	         <tr>
	           <td class='bgF9F8F7'>&nbsp;</td><td class='bgF9F8F7'></td>
	         </tr>
	        <tr>
	           <td class='bgF9F8F7' align='center'>
	            <table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
	            <tr>
	                <td height='30' align='right' class='txt10'>Do you have direct selling experience ? :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30' width=\"20%\">
	                	<input name='rdYesNo' type='radio' class='btnnone' value='1' $chk />Yes
                        <input name='rdYesNo' type='radio' class='btnnone' value='0' $chk2 />No
	                </td>
	            </tr>
	            <tr id=\"in-what-company\">
	                <td height='30' align='right' class='txt10'>In what companies ? :</td>
	                <td height='30'>&nbsp;</td>
	                <td width='15%' height='30'>
	                	
	                	$additionalCompany 
	                	<div id='area'>
	                		
	                	</div>
	                </td>
	                <td height='30'>&nbsp;<a onclick='addTextBox()' href='#' class='btn'>Add</a><br></td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Are you employed ? :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input name='rdEYesNo' type='radio' class='btnnone' value='1' $chk3 />Yes
                        <input name='rdEYesNo' type='radio' class='btnnone' value='0' $chk4 />No
	                </td>
	            </tr>
	            </table>
	            </td>
	            <td style='width: 30%;' class='bgF9F8F7'></td>
	            </tr>
	            </table>		
		  	</div>
		  </div>
		  <div class='Page' id='div3' style='display: none;'>
		  	<div class='Pad'>
				<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0' id='tbl2'>
	         <tr>
	           <td class='bgF9F8F7'></td>
	         </tr>
	         <tr>
	           <td class='bgF9F8F7'>&nbsp;</td><td class='bgF9F8F7'></td>
	         </tr>
	        <tr>
	           <td class='bgF9F8F7' align='center'>
	            <table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
	            <tr>
	                <td height='30' align='right' class='txt10'>Recruiter Account Number :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtRecruiteAcctNo' maxlength='50' size='40' class='txtfield' value='$accountno' id='txtRecruiteAcctNo'/>
	                		<span id='indicator2' style='display: none'><img src='images/ajax-loader.gif' alt='Working...' /></span>                                      
							<div id='coa_choices2' class='autocomplete' style='display:none'></div>
							<script type='text/javascript'>							
								 //<![CDATA[
                                        var coa_choices = new Ajax.Autocompleter('txtRecruiteAcctNo', 'coa_choices2', 'includes/scRecruiterAjax.php', {afterUpdateElement : getSelectionCOAID2, indicator: 'indicator2'});																			
                                        //]]>
							</script>
							<br>
                                <input type='hidden' value='' name='txtRecruiteCustID' id='txtRecruiteCustID' />
	                </td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Recruiter Name :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtRecruiteName' maxlength='50' size='40' class='txtfield' value='$recruitername' id='txtRecruiteName'/><br>
	                </td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Cellphone Number :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtRecruiterCP' maxlength='50' size='40' class='txtfield' value='$recruitermobileno' id='txtRecruiterCP'/>
	                </td>
	            </tr>
	            </table>
	            </td>
	            <td style='width: 30%;' class='bgF9F8F7'></td>
	            </tr>
	            </table>		
			</div>
		  </div>
		  <div class='Page' id='div4' style='display: none;'>
		  	<div class='Pad'>
		  			<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0' id='tbl2'>
	         <tr>
	           <td class='bgF9F8F7'></td>
	         </tr>
	         <tr>
	           <td class='bgF9F8F7'>&nbsp;</td><td class='bgF9F8F7'></td>
	         </tr>
	        <tr>
	           <td class='bgF9F8F7' align='center'>
	            <table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
	            <tr>
	                <td height='30' align='right' class='txt10'>Credit Term :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<select name='cboCreditTerm' class='txtfield' style='width:180'>
							$drpCreditTerm			
	                	</select>
	                </td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'><strong>Credit Interview Score</strong></td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'></td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Character Score :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtCharScore' maxlength='10' size='20' class='txtfield' value='$character' onKeyUp='return checkFactorValues(this);'/>
	                </td>
	            </tr>
	              <tr>
	                <td height='30' align='right' class='txt10'>Capacity / Capability Score :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtCapacityScore' maxlength='10' size='20' class='txtfield' value='$capacity' onKeyUp='return checkFactorValues(this);'/>
	                </td>
	            </tr>
	              <tr>
	                <td height='30' align='right' class='txt10'>Capital Score :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtCapitalScore' maxlength='10' size='20' class='txtfield' value='$capital' onKeyUp='return checkFactorValues(this);'/>
	                </td>
	            </tr>
	             <tr>
	                <td height='30' align='right' class='txt10'>Condition Score :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtConditionScore' maxlength='10' size='20' class='txtfield' value='$condition' onKeyUp='return checkFactorValues(this);'/>
	                </td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'><strong>Credit Limit Amount</strong></td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'></td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Calculated :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtCalculated' maxlength='10' size='20' class='txtfield' value='$calculatedcl' readonly='yes'/>
	                </td>
	            </tr>
	              <tr>
	                <td height='30' align='right' class='txt10'>Recommended :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtRecommended' maxlength='10' size='20' class='txtfield' value='$recommendedcl'/>
	                </td>
	            </tr>
	              <tr>
	                <td height='30' align='right' class='txt10'>Approved :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtApproved' maxlength='10' size='20' class='txtfield' value='$approvedcl'/>
	                </td>
	            </tr>	            
				<!--Remove PDA-RAR-Code here -->		
	            </table>
	            </td>
	            <td style='width: 30%;' class='bgF9F8F7'></td>
	            </tr>
	            </table>		
		  	</div>
		  </div>
		  <div class='Page'  id='div5' style='display: none;'>
		  	<div class='Pad'>
		  			<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0' id='tbl2'>
	         <tr>
	           <td class='bgF9F8F7'></td>
	         </tr>
	         <tr>
	           <td class='bgF9F8F7'>&nbsp;</td><td class='bgF9F8F7'></td>
	         </tr>
	        <tr>
	           <td class='bgF9F8F7' align='center'>
	           <form method='post' enctype='multipart/form-data'>
	            <table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
	            <tr>
	                <td height='30' align='right' class='txt10' valign='top'>Remarks :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<textarea rows='3' cols='30' name='txtRemarks'/>$customerRemarks</textarea>
	                </td>
	            </tr>
	            <tr>
	               <td height='30' class='bgF9F8F7' align='right'>&nbsp;<div align='right' class='txt10'>Application Form :</div></td>
	               <td height='30' class='bgF9F8F7'>&nbsp;
	                </td>
	               <td height='30'  class='bgF9F8F7'>
	               		$applicationForm
	               </td>
	           </tr>
	            </table>
	            </form>
	            </td>
	            <td style='width: 30%;' class='bgF9F8F7'></td>
	            </tr>
	            </table>	
		  	</div>
		  </div>
		   <div class='Page'  id='div6' style='display: none;'>
		  	<div class='Pad'>
		  			<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0' id='tbl2'>
	         <tr>
	           <td class='bgF9F8F7'></td>
	         </tr>
	         <tr>
	           <td class='bgF9F8F7'>&nbsp;</td><td class='bgF9F8F7'></td>
	         </tr>
	        <tr>
	           <td class='bgF9F8F7' align='center'>
	            <table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
	            <tr>
	                <td width='50%' height='30' align='right' class='txt10' >BIR COR :</td>
	                <td width='2%' height='30'>&nbsp;</td>
	                <td width='48%' height='30'>
	                	$drpBIRCOR
	                </td>
	            </tr>	
				<tr>
	                <td height='30' align='right' class='txt10' >BIR OR :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	$drpBIROR
	                </td>
	            </tr> 
				<tr>
	                <td height='30' align='right' class='txt10' >Vatable :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	$drpVAT
	                </td>
	            </tr>      
				<tr>
	                <td height='30' align='right' class='txt10' >Registered TIN No. of IBM :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'><input type='text' class='txtfield2' maxlength='9'></td>
	            </tr>  
				<tr>
	                <td height='30' align='right' class='txt10' >Bank Name :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'><input type='text' class='txtfield2' ></td>
	            </tr>  
				<tr>
	                <td height='30' align='right' class='txt10' >Bank Account Number to Credit Commissions :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'><input type='text' class='txtfield2' ></td>
	            </tr>  
				<tr>
	                <td height='30' align='right' class='txt10' >Remaining Commission :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
					 $drpCommission
	            </tr>  
				<tr>
	                <td height='30' align='right' class='txt10' colspan='3' >&nbsp;</td>
	                
	            </tr>  
 
 
	            </table>
	            </td>
	            <td style='width: 30%;' class='bgF9F8F7'></td>
	            </tr>
	            </table>	
		  	</div>
		  </div>
		</div>
		
		</div>
	
		</td>
	  </tr>
	</table>";
    }//$drpPDARARCode
	   
?>

<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

<style type="text/css">
<!--
.style1 {color: #FF0000}

div.autocomplete {
  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;} 
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px solid #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}z

-->
</style>
<link rel="stylesheet" type="text/css" href="css/tab-view.css">


<script type="text/javascript">
$j = jQuery.noConflict();
function ajaxFunctionTown(ID, Param)
{
   var loaderphp = "includes/scDropTownAjax.php";
   var xmlHttp;
   try
    {
      // Firefox, Opera 8.0+, Safari
      xmlHttp=new XMLHttpRequest();
    }catch(e){
      // Internet Explorer
      try
      {
         xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }catch(e){
         try
         {
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
         }catch(e){
            alert("Your browser does not support AJAX!");
            return false;
         }
      }
   }
   xmlHttp.onreadystatechange=function()
   {
      if(xmlHttp.readyState==4)
        {
         document.getElementById('cboTown').innerHTML = xmlHttp.responseText;
        }
   }
    xmlHttp.open("GET", loaderphp+"?Param="+Param,true);
    xmlHttp.send(null);
}

function getSelectionCOAID(text,li) {
	//alert (text);	
	//d = document.form;
	
	tmp = li.id;;
	tmp_val = tmp.split("_");
	//alert(tmp_val[1]);
	var hCOA  		= eval('document.frmDealer.hCOA');
	var IBMName  	= eval('document.frmDealer.txtibmname');
	var IBMCode 	= eval('document.frmDealer.txtIBMNo');
	//alert(hCOA);
	hCOA.value = tmp_val[0];
	IBMName.value = tmp_val[1];
	IBMCode.value = tmp_val[2];
	
	

	
	
}
/*
 *  @author: jdymosco
 *  @date: May 18, 2013
 *  @update_explain: Added new field that will handle recruiters true internal customer ID for insertion in customer details
 *                   table so we can fixed the error in foreing key constraint.
 */
function getSelectionCOAID2(text,li) {
	//alert (text);	
	//d = document.form;
	
	tmp = li.id;
	tmp_val = tmp.split("_");
        
        var CustID  		= eval('document.frmDealer.txtRecruiteCustID');
	var Name  		= eval('document.frmDealer.txtRecruiteName');
	var Code  	= eval('document.frmDealer.txtRecruiteAcctNo');
	var CellPhone 	= eval('document.frmDealer.txtRecruiterCP');
        
        CustID.value = tmp_val[0];
	Code.value = tmp_val[2];
	Name.value = tmp_val[1];
	CellPhone.value = tmp_val[3];
	
}

function ajaxFunctionBarangay(ID, Param)
{
   var loaderphp = "includes/scDropBarangayAjax.php";
   var xmlHttp;
   try
    {
      // Firefox, Opera 8.0+, Safari
      xmlHttp=new XMLHttpRequest();
    }catch(e){
      // Internet Explorer
      try
      {
         xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }catch(e){
         try
         {
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
         }catch(e){
            alert("Your browser does not support AJAX!");
            return false;
         }
      }
   }
   xmlHttp.onreadystatechange=function()
   {
      if(xmlHttp.readyState==4)
        {
         document.getElementById('cboBarangay').innerHTML = xmlHttp.responseText;
        }
   }
    xmlHttp.open("GET", loaderphp+"?Param="+Param,true);
    xmlHttp.send(null);
}

function ajaxFunctionZipCode(ID, Param)
{
   var loaderphp = "includes/scDropZipCodeAjax.php";
   var xmlHttp;
   try
    {
      // Firefox, Opera 8.0+, Safari
      xmlHttp=new XMLHttpRequest();
    }
    catch(e)
    {
      // Internet Explorer
      try
      {
         xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }
      catch(e)
      {
         try
         {
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
         }
         catch(e)
         {
            alert("Your browser does not support AJAX!");
            return false;
         }
      }
   }
   xmlHttp.onreadystatechange=function()
   {
      if(xmlHttp.readyState==4)
        {
         document.getElementById('txtZipCode').value = xmlHttp.responseText;
        }
   }
    xmlHttp.open("GET", loaderphp+"?Param="+Param,true);
    xmlHttp.send(null);
}

var inival=0;
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}

function checkFactorValues(elem)
{
	var obj = document.frmDealer.elements;
	var field = eval(obj[elem.name]);
	
	var char_factor = document.frmDealer.hCharacter.value;
	var capa_factor = document.frmDealer.hCapacity.value;
	var capi_factor = document.frmDealer.hCapital.value;
	var cond_factor = document.frmDealer.hCondition.value;
	var dcrl_factor = document.frmDealer.hDefCreditLimit.value;
	
	var char_val = document.frmDealer.txtCharScore.value;
	var capa_val = document.frmDealer.txtCapacityScore.value;
	var capi_val = document.frmDealer.txtCapitalScore.value;
	var cond_val = document.frmDealer.txtConditionScore.value;
	
	var tot_factor = 0;
	var tot_score = 0;
	var calc_climit = 0;
	var txtCalculated = document.frmDealer.txtCalculated;
	var txtRecommended = document.frmDealer.txtRecommended;
	var txtApproved = document.frmDealer.txtApproved;
	
	if (!isNumeric(trim(field.value)))
	{
		alert('Invalid numeric format for Score value.');
		field.focus();
		field.select();
		txtCalculated.value = "";
		return false;					
	}
	else if (trim(field.value) < 1 || trim(field.value) > 4)
	{
		alert('Score value must be between 1 to 4 only.');
		field.focus();
		field.select();
		txtCalculated.value = "";
		return false;		
	}
	else
	{
		if (char_val == "")
		{
			char_comp = 0;
		}
		else
		{
			char_comp = eval(char_val * (char_factor / 100));			
		}
		
		if (capa_val == "")
		{
			capa_comp = 0;
		}
		else
		{
			capa_comp = eval(capa_val * (capa_factor / 100));			
		}
		
		if (capi_val == "")
		{
			capi_comp = 0;
		}
		else
		{
			capi_comp = eval(capi_val * (capi_factor / 100));			
		}
		
		if (cond_val == "")
		{
			cond_comp = 0;
		}
		else
		{
			cond_comp = eval(cond_val * (cond_factor / 100));			
		}
		
		tot_factor = char_comp + capa_comp + capi_comp + cond_comp;
		tot_score = (tot_factor / 4) * 100;
		calc_climit = (tot_score / 100) * dcrl_factor;  
		
		txtCalculated.value = Math.round((eval(calc_climit))*100)/100;
		txtRecommended.value = Math.round((eval(calc_climit))*100)/100;
		txtApproved.value = Math.round((eval(calc_climit))*100)/100;
	}
}

function form_validation()
{
	msg = '';
	str = '';
	obj = document.frmDealer.elements;

	// TEXT BOXES
	if (trim(obj["txtlnameDealer"].value) == '') msg += '   * Last Name \n';
        if (trim(obj["txtfnameDealer"].value) == '') msg += '   * First Name \n';
        //if (trim(obj["txtmnameDealer"].value) == '') msg += '   * Middle Name \n';
        //if (trim(obj["txtbdaydealer"].value) == '') msg += '   * Birthdate \n';

	if (msg != '')
	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else return true;
}

function form_validation2()
{
	msg = '';
	str = '';
	obj = document.frmDealer.elements;

	// TEXT BOXES
	if (trim(obj["txtlnameDealer"].value) == '')msg += '   * Last Name \n';
	if (trim(obj["txtmnameDealer"].value) == '')msg += '   * Middle Name \n';
	if (trim(obj["txtfnameDealer"].value) == '')msg += '   * First Name \n';
	if (!isDate(obj["txtbdaydealer"]))msg += '   * Input Birthdate as either mm/dd/yyyy or mm-dd-yyyy \n';
	
	if (trim(obj["txtNickName"].value) == '' || trim(obj["txtNickName"].value) == 'null')msg += '   * Nick Name \n';
	if (trim(obj["txtHomeTelNo"].value) == '' || trim(obj["txtHomeTelNo"].value) == 'null')msg += '   * Tel No \n';
	if (trim(obj["txtCPNumber"].value) == '' || trim(obj["txtCPNumber"].value) == 'null')msg += '   * CP Number \n';
	if (trim(obj["txtStAddress"].value) == '' || trim(obj["txtStAddress"].value) == 'null')msg += '   * Address \n';
	if (trim(obj["cboProvince"].value) == 0)msg += '   * Province \n';
	if (trim(obj["cboTown"].value) == 0)msg += '   * Town \n';
	if (trim(obj["cboBarangay"].value) == 0)msg += '   * Barangay \n';
	//if (trim(obj["txtZipCode"].value) == '' || trim(obj["txtZipCode"].value) == 'null')msg += '   * Zip Code \n';
	if (trim(obj["cboZone"].value) == 0)msg += '   * Zone \n';
	if (trim(obj["txtLStay"].value) == '' || trim(obj["txtLStay"].value) == '0')msg += '   * Length of Stay \n';
	if (trim(obj["cboMaritalStatus"].value) == 0)msg += '   * Marital Status \n';
	if (trim(obj["cboEducational"].value) == 0)msg += '   * Educational Attainment \n';
	if (trim(obj["txtIBMNo"].value) == '')msg += '   * IBM No. \n';	

	if (msg != '')
	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else
	{
		return true;		
	} 
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(objID) 
{
	var objWin;
		popuppage = "pages/sfm/datamgt_dealerinfopopup.php?custid=" + objID;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','700','yes');
		}
		
		return false;  			
}	

function isDate(fld) 
{
	var value = fld.value;
	var datePat = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
	var matchArray = value.match(datePat); // is the format ok?
	
	if (matchArray == null) 
	{
		return false;
   	}
   	return true;
}

function addTextBox()
{
	var newArea = add_New_Element();
	var htcontents = "<tr><td></td></tr><input type='text' name='textbx[]' id='textbx' class='txtfield'/>";
	document.getElementById(newArea).innerHTML = htcontents; // You can any other elements in place of 'htcontents' 
}

function add_New_Element() {
	obj = document.frmDealer.elements;
	inival=inival+1; // Increment element number by 1
	var ni = document.getElementById('area');
	var newdiv = document.createElement('div'); // Create dynamic element
	var divIdName = 'my'+inival+'Div';
	newdiv.setAttribute('id',divIdName);

	ni.appendChild(newdiv);
	return divIdName;
}

function toggleDivTag(id) 
{        
	var divID = document.getElementById(id);        
	
	var div1 = document.getElementById('div1');        
	var div2 = document.getElementById('div2');        
	var div3 = document.getElementById('div3');        
	var div4 = document.getElementById('div4');        
	var div5 = document.getElementById('div5');        
	var div6 = document.getElementById('div6');       
	 
	if(id == 'div1')
	{
		divID.style.display = 'block'; 
		div2.style.display = 'none'; 
		div3.style.display = 'none'; 
		div4.style.display = 'none'; 
		div5.style.display = 'none'; 
		div6.style.display = 'none'; 
	}

	if(id == 'div2')
	{
		divID.style.display = 'block'; 
		div1.style.display = 'none'; 
		div3.style.display = 'none'; 
		div4.style.display = 'none'; 
		div5.style.display = 'none'; 
		div6.style.display = 'none'; 
	}

	if(id == 'div3')
	{
		divID.style.display = 'block'; 
		div2.style.display = 'none'; 
		div1.style.display = 'none'; 
		div4.style.display = 'none'; 
		div5.style.display = 'none'; 
		div6.style.display = 'none'; 
	}

	if(id == 'div4')
	{
		divID.style.display = 'block'; 
		div2.style.display = 'none'; 
		div3.style.display = 'none'; 
		div1.style.display = 'none'; 
		div5.style.display = 'none'; 
		div6.style.display = 'none'; 
	}

	if(id == 'div5')
	{
		divID.style.display = 'block'; 
		div2.style.display = 'none'; 
		div3.style.display = 'none'; 
		div4.style.display = 'none'; 
		div1.style.display = 'none'; 
		div6.style.display = 'none'; 
	}

	if(id == 'div6')
	{
		divID.style.display = 'block'; 
		div2.style.display = 'none'; 
		div3.style.display = 'none'; 
		div4.style.display = 'none'; 
		div5.style.display = 'none'; 
		div1.style.display = 'none'; 
	}
	
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav_dealer.php");
		?>

      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Data Management </span></td>
        </tr>
    </table>
    <br />
  
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Dealer</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
    <form name="frmSearchDealer" method="post" action="index.php?pageid=69">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  <tr>
		    <td><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
		        <tr>
		          <td width="20%">&nbsp;</td>
		          <td width="10%" align="right">&nbsp;</td>
		          <td width="70%" align="right">&nbsp;</td>
		        </tr>
                        <?php /* 
                         * @author: jdymosco
                         * @date: April 23, 2013
                         * @description: We removed IBM code field since dealer profile is now more on IGS listing only..
                         * ?>
		         <tr>
		          <td>
					 IBM Code :          
				  </td>
				   <td></td>
				   <td>         
					 <input name="txtfldIBMCodeSearch" type="text" class="txtfield" id="txtIBMCodeSearch" size="20">
				  </td>
		        </tr>
		        <tr></tr>
		        <tr></tr><?php */?>
		        <tr>
		          <td>
					 Search :
				  </td>
				   <td></td>
				   <td>    
					  <input name="txtfldsearch" type="text" class="txtfield" id="txtfldsearch" size="20" value="<?php if(isset($_POST['txtfldsearch'])) { echo $_POST['txtfldsearch'] ;  } else {  if(isset($_GET['search'])){ echo $_GET['search']; } else { echo ''; } } ?>">&nbsp;
					  <input name="btnSearch" type="submit" class="btn" value="Search">
				  </td>
		        </tr>
		        <tr>
		          <td>&nbsp;</td>
		          <td align="right">&nbsp;</td>
		          <td align="right">&nbsp;</td>
		        </tr>
		    </table></td>
		  </tr>
		</table>
    </form>
    <br>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td class="tabmin">&nbsp;</td>
		          <td class="tabmin2"><span class="txtredbold">Dealer List</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        <tr>
		          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              <tr>
		                <td valign="top" class="" height="242">
		                  <div id="pgContent">
		                    <b>Loading Content...</b><img border="0" src="images/ajax-loader.gif">&nbsp;
		                    </div>
		                  </td>
		              </tr>
		          </table></td>
		        </tr>
		      </table>
		      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td height="3" class="bgE6E8D9"></td>
		        </tr>
		      </table>
		      <br>
		      <table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
		        <tr>
		            <td height="20" class="txtblueboldlink" width="50%">
		            <div id="pgNavigation"><b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif"></div>
		            
		            </td>
		            <td height="20" class="txtblueboldlink" width="48%">
		            <div id="pgRecord" align="right"><b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif"></div>
		            </td>
		        </tr>
		    </table>
		      <br>
		       <?php 
      
		      $vSearch = '';
		      if(isset($_POST['txtfldsearch']))
		      {
		      	 $vSearch =  $_POST['txtfldsearch']; 
		      }
		      
		        $cID = 0;
		     if(isset($_POST['btnSearch']))
			 {
					/*if($_POST['txtfldIBMCodeSearch'])
					{
						$vSearch = addslashes($_POST['txtfldIBMCodeSearch']);
					}
					else
					{*/
						$vSearch = addslashes($_POST['txtfldsearch']);
					//}	
					$cID =  -1;
					
			 }
				
		      ?>     
		<script>
			//Onload start the user off on page one		
			window.onload = showPage("1", "<?php echo $cID; ?>", "<?php echo $vSearch; ?>","1");    
   		 </script>
    
    <!--
		      <br>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td class="tabmin">&nbsp;</td>
		          <td class="tabmin2"><span class="txtredbold">Dealer List</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        <tr>
		          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              <tr>
		                <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="tab txtdarkgreenbold10">
		                    <tr align="center">
		                      <td width="40%"><div align="left" class="bdiv_r padl5">Code</div></td>
		                      <td width="60%"><div align="left" class="padl5">Name</div></td>
		                </table></td>
		              </tr>
		              <tr>
		                <td class="bordergreen_B"><div class="scroll_300">
                        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
							<?php echo $customerRows;?>
		                  </table>
                          </div>
	                    </td>
		              </tr>
		          </table></td>
		        </tr>
		      </table>
		      <br>
	--></td>
	<td width="2%">&nbsp;</td>
	<td width="60%" valign="top">
     <form name="frmDealer" method="post" action="index.php?pageid=69<?php if(isset($_GET['custid'])) { echo '&custid='.$_GET['custid']; } else { echo '';}; ?><?php if(isset($_GET['action'])) { echo '&action='.$_GET['action']; } else { echo '';}; ?>" onsubmit="return form_validation();" enctype='multipart/form-data'>
     	<input type="hidden" id="hCharacter" name="hCharacter" value="<?php echo $character_factor; ?>">
     	<input type="hidden" id="hCapacity" name="hCapacity" value="<?php echo $capacity_factor; ?>">
     	<input type="hidden" id="hCapital" name="hCapital" value="<?php echo $capital_factor; ?>">
     	<input type="hidden" id="hCondition" name="hCondition" value="<?php echo $condition_factor; ?>">
     	<input type="hidden" id="hDefCreditLimit" name="hDefCreditLimit" value="<?php echo $dclimit_factor; ?>">
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Dealer Details</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         <tr>
           <td class="bgF9F8F7"><?php echo $errMessage; ?></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
             <?php if (isset($_GET['action']))
	             { 
	             
	             	$defaultcode = '';
					/*
                                         * @author: jdymosco
                                         * @date: Feb. 19, 2013
                                         * @explanation: We get rid of this old generation of code since we already have
                                         * new specification for creating dealer details.
                                         *                                         
                                        $rsDefaultCode = $sp->spSelectDefaultCustomerCode($database);
					if ($rsDefaultCode->num_rows)
					{
						while ($row = $rsDefaultCode->fetch_object())
						{			
							$defaultcode= $row->Code;
						}
					}*/
                        
                        $defaultcode = tpi_generateDealerCode();
					//echo $defaultcode;
	             	$code = (isset($_GET['custid']) && $_GET['custid'] != '') ? $code : $defaultcode;
	             	$igscode = (isset($_POST['txtcodeDealer'])) ? $_POST['txtcodeDealer'] : $code;
	             	echo "
	             <tr>
	                <td height='30' align='right' class='txt10' width='30%'>Code :</td>
	                <td height='30'width='5%'>&nbsp;</td>
	                <td height='30' width='65%'><input type='text' name='txtcodeDealer' maxlength='50' size='40' readonly class='txtfield' value='$code'></td>
	            </tr>";
	             }
            ?>
            <tr>
                <td height="30" align="right" class="txt10" width="30%">Last Name :</td>
                <td height="30" width="5%">&nbsp;</td>
                <td height="30" width="65%"><input type="text" id="txtlnameDealer" name="txtlnameDealer" maxlength="50" size="40" class="txtfield" value="<?php echo $lname; ?>" ></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">First Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><input type="text" id="txtfnameDealer" name="txtfnameDealer" maxlength="50" size="40" class="txtfield" value="<?PHP echo $fname;?>" /></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Middle Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><input type="text" id="txtmnameDealer" name="txtmnameDealer" maxlength="50" size="10" class="txtfield" value="<?PHP echo $mname;?>" ></td>
            </tr>
            
            <tr>
              <td height="30" align="right" class="txt10">Birthdate :</td>
              <td height="30">&nbsp;</td>
              <td height="30">
              	<input name="txtbdaydealer" type="text" class="txtfield" id="txtbdaydealer" size="20" value="<?PHP echo $bday; ?>" size="30">
                <input type="button" class="buttonCalendar" name="anchorTxnDate" id="anchorTxnDate" value=" ">
                <div id="divtxnDate" style="background-color : white; position:absolute; visibility:hidden;"> </div>
              </td>
            </tr>
        	<?php echo $additionalFields;?>
            <tr>
            	<td height="20" colspan="3"></td>
            </tr>
            </table>		
        </td>
         </tr>
       </table>
       <br>
       <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
       	<td align="center"><?php echo $checkButtonDisplay; ?></td>
       </tr>
       </table>
        <br>  
        <?php echo $additionaltable;?>
		
        <?php 
			if(isset($_GET['action']))
			{
				if($_GET['action'] == "update")
				{
					echo $updateButtonDisplay; 
				}
				else
				{
					echo $saveButtonDisplay ;
				}
			}
		?>
        <?php echo $existingRecords; ?>
      <br>
      <br>
    </form>
	</td>
  </tr>
</table>
	</td>
  </tr>
</table>
    </td>
  </tr>
</table>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtbdaydealer",     // id of the input field
		ifFormat       :    "%m-%d-%Y",      // format of the input field
		displayArea    :	"divtxnDate",
		button         :    "anchorTxnDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
	
</script>

