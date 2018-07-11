<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script src="js/jxPagingCreateDealer.js" language="javascript" type="text/javascript"></script>
<script src="js/sfm_js/jquery.sfmDealerMgt.js" language="javascript" type="text/javascript"></script>
<?PHP 
	include IN_PATH.DS."scCustomer.php";
	include IN_PATH.DS."pcUpdateDealer.php";
	
	//initialize customer id
	$custid = 0;
	$applicationForm='';
	if(isset($_GET['custid']))
	{
		$custid = $_GET['custid'];
		
		//file download	   
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
	//initialize variables
	$id = isset($_GET['id']) ? $_GET['id'] : 1; 
 
	$chk = ''; $chk2=''; $chk3=''; $chk4='';
	$otherdetails = ''; $directselling = ''; $recruiter = ''; $credit = ''; $remarks = '';  $bankinfo = '';
	
	$otherdetails = "<a onclick=toggleDivTag('div1') href=#>Other Details</a>";
	$directselling = "<a onclick=toggleDivTag('div2') href=#>Direct Selling</a>";
	$recruiter = "<a onclick=toggleDivTag('div3') href=#>Recruiter</a>";
	$credit = "<a onclick=toggleDivTag('div4') href=#>Credit</a>";
	$remarks = "<a onclick=toggleDivTag('div5') href=#>Remarks</a>";
	//$bankinfo = "<a onclick=toggleDivTag('div6') href=#>Bank Info</a>";
	
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
    
//	//display customers
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
//		while ($row = $rs_customer->fetch_object())
//		{
//		   $customerRows .= "<tr align='center'><td width='40%' height='20' class='borderBR' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
//			  <td width='60%' class='borderBR' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=70&custid=$row->ID&action=update&search=$search' class='txtnavgreenlink'>"
//		   	   .$row->FirstName." ".$row->MiddleName." ".$row->LastName." </a></span></td></tr>";
//		}
//		$rs_customer->close();
//	}
//	else
//	{
//		$customerRows = "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtreds'>No record(s) to display. </span></td></tr>";
//	}
    
    //display check/cancel buttons
    $checkButtonDisplay = '';
    $updateButtonDisplay = '';
    $additionaltable= '';
    $additionalFields = '';
	
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
	
	
	$customerRemarks = $customerRemarks == 'null' ? '' : $customerRemarks;
    $nickname = (isset($_POST['txtNickName'])) ? $_POST['txtNickName'] : $nickname;
    $telno = (isset($_POST['txtHomeTelNo'])) ? $_POST['txtHomeTelNo'] : $telno;
    $mobileno = (isset($_POST['txtCPNumber'])) ? $_POST['txtCPNumber'] : $mobileno;
    $address = (isset($_POST['txtStAddress'])) ? $_POST['txtStAddress'] : $address;
    //$zipcode = (isset($_POST['txtZipCode'])) ? $_POST['txtZipCode'] : $zipcode;
    $length = (isset($_POST['txtLStay'])) ? $_POST['txtLStay'] : $length;
    $customerRemarks = (isset($_POST['txtRemarks'])) ? $_POST['txtRemarks'] : $customerRemarks;
    $company = (isset($_POST['txtCompany1'])) ? $_POST['txtCompany1'] : $company;

    $additionalCompany = '';
	if($custid > 0)
	{
		$rsExistCompany = $sp->spSelectDealerCompany($database, $custid);
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
		
	}
     //gsutype dropdown
    $drpGSUType = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboGSUType->num_rows)
    {
     while ($row = $rs_cboGSUType->fetch_object())
      {
      	$gsutypeid = (isset($_POST['cboGSUType'])) ? $_POST['cboGSUType'] : $gsutypeid;
      	($gsutypeid == $row->ID) ? $sel = 'selected' : $sel = '';
        $drpGSUType .= "<option value='$row->ID' $sel>$row->Name</option>";
      }
    }
    
    //class dropdown
    $drpClass = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboClass->num_rows)
    {
     while ($row = $rs_cboClass->fetch_object())
      {
      	$classid = (isset($_POST['cboClass'])) ? $_POST['cboClass'] : $classid;
      	($classid == $row->ID) ? $sel = 'selected' : $sel = '';
        $drpClass .= "<option value='$row->ID'' $sel>$row->Name</option>";
      }
    }
    
    //customer type dropdown
    $drpCustomerType = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboCustomerType->num_rows)
    {
     while ($row = $rs_cboCustomerType->fetch_object())
     {
     	$customertypeid = (isset($_POST['cboCustomerType'])) ? $_POST['cboCustomerType'] : $customertypeid;
      	($customertypeid == $row->codeID) ? $sel = 'selected' : $sel = '';
        $drpCustomerType .= "<option value='$row->codeID'' $sel>$row->desc_code</option>";
     }
    }
    
     //marital status dropdown
    $drpMaritalStatus = '<option value=\'0\' >[SELECT HERE]</option>';
     if ($rs_cboMaritalStatus->num_rows)
    {
     while ($row = $rs_cboMaritalStatus->fetch_object())
      {
      	$fieldid = (isset($_POST['cboMaritalStatus'])) ? $_POST['cboMaritalStatus'] : $fieldid;
      	($fieldid == $row->ID) ? $sel = 'selected' : $sel = '';
        $drpMaritalStatus .= "<option value='$row->ID'' $sel>$row->Name</option>";
       }
    }
    
     //educational attainment dropdown
    $drpEducational = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboEducational ->num_rows)
    {
     while ($row = $rs_cboEducational ->fetch_object())
      {
      	$efieldid = (isset($_POST['cboEducational'])) ? $_POST['cboEducational'] : $efieldid;
      	($efieldid == $row->ID) ? $sel = 'selected' : $sel = '';
        $drpEducational  .= "<option value='$row->ID'' $sel>$row->Name</option>";
      }
    }
    
    //zone dropdown
    $drpZone = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboZone ->num_rows)
    {
    	while ($row = $rs_cboZone ->fetch_object())
      	{
      		$zoneid = (isset($_POST['cboZone'])) ? $_POST['cboZone'] : $zoneid;
      		($zoneid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpZone .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
	    
    //credit term
    $drpCreditTerm = '<option value=\'0\' >[SELECT HERE]</option>';
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
      		($provinceid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpProvince .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
    
    //town/city
    $drpTownCity = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboTownCity->num_rows)
    {
     	while ($row = $rs_cboTownCity->fetch_object())
      	{
      		($townid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpTownCity .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
    
    //barangay
    $drpBarangay = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboBarangay->num_rows)
    {
     	while ($row = $rs_cboBarangay->fetch_object())
      	{
      		($barangayid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpBarangay .= "<option value='$row->ID' $sel>$row->Name</option>";
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
    
     //display ibm fields
    $ibmfields ='';
    
    /*
     * @author: jdymosco
     * @date: Feb. 25, 2013
     * @explanation: We removed the displaying of IBMCode of IBM mothe code.
     */
    if($customertypeid == 2 || $customertypeid == 3 || $customertypeid == 5)
    {
    	$ibmfields = "
    			<tr style=\"display: none;\">
                <td height='30' align='right' class='txt10'>IBM Code :</td>
                <td height='30'></td>
                <td height='30'>
                <input name='txtIBMCode' type='text' class='txtfield' id='txtIBMCode' value='$ibmcode2'/>
	  			</td></tr>";
    	//$bankinfo = "<a onclick=toggleDivTag('div6') href=#>Bank Info</a>";
        $bankinfo = "";
    	
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
    $updateButtonDisplay = "
    	<table width='100%' border='0' align='center' cellpadding='0' cellspacing='1'>
	  <br>
	  <tr>
	    <td align='center'>
			<input name='btnUpdate' type='submit' class='btn' value='Update'>&nbsp;
	    	<input name='btnDelete' type='submit' class='btn' value='Delete'>&nbsp;
	    	<input name='btnClear' type='button' class='btn' value='Cancel' onclick=window.location.href='index.php?pageid=70' >
	    </td>
	  </tr>
	</table>";
    
    //display additional fields onclick='return form_validation2($custid)'
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
              </td>
            </tr>
             <tr>
              <td height='30' align='right' class='txt10'>GSU Type :</td>
              <td height='30'>&nbsp;</td>
              <td height='30'>
                 <select name='cboGSUType' style='width:150px' class='txtfield input-readonly' readonly=\"true\">
                    $drpGSUType
                    </select>
              </td>
            </tr>
            $ibmfields
             <tr>
                <td align='right' class='txt10'>IBM No. :</td>
                <td></td>
                <td><input readonly=\"true\" type='text' name='txtIBMNo' id='txtIBMNo' maxlength='50' size='10' class='txtfield input-readonly' value='$ibmcode'> &nbsp;&nbsp;
                	<span id='indicator1' style='display: none'><img src='images/ajax-loader.gif' alt='Working...' /></span>                                      
				<div id='coa_choices' class='autocomplete' style='display:none'></div>
				<script type='text/javascript'>							
					 //<![CDATA[
                            var coa_choices = new Ajax.Autocompleter('txtIBMNo', 'coa_choices', 'pages/ajax_request/sfmIBMsAutoCompleteAjax.php', {afterUpdateElement : getSelectionCOAID, indicator: 'indicator1'});																			
                     //]]>
						</script>
                                                <input name='hCOA' type='hidden' id='hCOA' value='$ibmid'/>
                IBM Name : &nbsp;<input type='text' name='txtibmname' maxlength='50'  size='10' readonly=\"true\" class='txtfield input-readonly' value='$ibmname'></td>
            </tr>
             <tr style=\"display: none;\">
                <td height='30' align='right' class='txt10'>TIN :</td>
                <td height='30'></td>
                <td height='30'><input type='text' name='txtTIN' maxlength='50' size='10' class='txtfield' value='$tin'></td>
            </tr>
            ";
    
    //display tabs for updating...
    $additionaltable="
	<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0' >
	  <tr>
		<td>
		<div class='TabView' id='TabView'>
		<div class='Tabs' style='width: 700px;'>
			$otherdetails $directselling $recruiter $credit $remarks  $bankinfo
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
	                <td height='30' align='right' class='txt10'>Nickname :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'><input type='text' name='txtNickName' maxlength='50' size='40' class='txtfield' value='$nickname' onkeyup='javascript:RemoveInvalidChars(txtNickName);'></td>
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
	             	<select name='cboProvince' class='txtfield' style='width:180' onchange='frmDealer.submit();'>
						$drpProvince			
	                </select>
	              </td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Town/City :</td>
	              <td height='30'>&nbsp;</td>
	              <td height='30'>	
	             	<select name='cboTown' class='txtfield' style='width:180' onchange='frmDealer.submit();'>
						$drpTownCity			
	                </select>
	              </td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Barangay :</td>
	              <td height='30'>&nbsp;</td>
	             <td height='30'>	
	             	<select name='cboBarangay' class='txtfield' style='width:180' onchange='frmDealer.submit();'>
						$drpBarangay		
	                </select>
	              </td>
	            </tr>
	             <tr>
	              <td height='30' align='right' class='txt10'>Zip Code :</td>
	              <td height='30'>&nbsp;</td>
	             <td height='30'><input type='text' name='txtZipCode' maxlength='50' size='10' class='txtfield' value='$zipcode' onkeyup='javascript:RemoveInvalidChars(txtmnameDealer);' readonly='yes'></td>
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
	                <td height='30'>
	                	<input name='rdYesNo' type='radio' class='btnnone' value='1' $chk />Yes
                        <input name='rdYesNo' type='radio' class='btnnone' value='0' $chk2 />No
	                </td>
	            </tr>
	            <tr>
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
	                	<input readonly=\"true\" type='text' name='txtRecruiteAcctNo' maxlength='50' size='40' class='txtfield input-readonly' value='$accountno' id='txtRecruiteAcctNo'/>
               				<span id='indicator2' style='display: none'><img src='images/ajax-loader.gif' alt='Working...' /></span>                                      
							<div id='coa_choices2' class='autocomplete' style='display:none'></div>
							<script type='text/javascript'>							
								 //<![CDATA[
                                        var coa_choices = new Ajax.Autocompleter('txtRecruiteAcctNo', 'coa_choices2', 'includes/scRecruiterAjax.php', {afterUpdateElement : getSelectionCOAID2, indicator: 'indicator2'});																			
                                        //]]>
							</script>
							<input name='hdnRec' type='hidden' id='hdnRec' />	
							<br>
	                </td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Recruiter Name :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input readonly=\"true\" type='text' name='txtRecruiteName' maxlength='50' size='40' class='txtfield input-readonly' value='$recruitername'/><br>
	                </td>
	            </tr>
	            <tr>
	                <td height='30' align='right' class='txt10'>Cellphone Number :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input type='text' name='txtRecruiterCP' maxlength='50' size='40' class='txtfield' value='$recruitermobileno'/>
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
	                	<select readonly=\"true\" id='cboCreditTerm' name='cboCreditTerm' class='txtfield input-readonly' style='width:180'>
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
	                	<input readonly=\"true\" type='text' id='txtCharScore' name='txtCharScore' maxlength='10' size='20' class='txtfield input-readonly' value='$character' onKeyUp='return checkFactorValues(this);'/>
	                </td>
	            </tr>
	              <tr>
	                <td height='30' align='right' class='txt10'>Capacity / Capability Score :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input readonly=\"true\" type='text' name='txtCapacityScore' maxlength='10' size='20' class='txtfield input-readonly' value='$capacity' onKeyUp='return checkFactorValues(this);'/>
	                </td>
	            </tr>
	              <tr>
	                <td height='30' align='right' class='txt10'>Capital Score :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input readonly=\"true\" type='text' name='txtCapitalScore' maxlength='10' size='20' class='txtfield input-readonly' value='$capital' onKeyUp='return checkFactorValues(this);'/>
	                </td>
	            </tr>
	             <tr>
	                <td height='30' align='right' class='txt10'>Condition Score :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input readonly=\"true\" type='text' name='txtConditionScore' maxlength='10' size='20' class='txtfield input-readonly' value='$condition' onKeyUp='return checkFactorValues(this);'/>
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
	                	<input readonly=\"true\" type='text' name='txtCalculated' maxlength='10' size='20' class='txtfield input-readonly' value='$calculatedcl' readonly='yes'/>
	                </td>
	            </tr>
	              <tr>
	                <td height='30' align='right' class='txt10'>Recommended :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input readonly=\"true\" type='text' name='txtRecommended' maxlength='10' size='20' class='txtfield input-readonly' value='$recommendedcl'/>
	                </td>
	            </tr>
	              <tr>
	                <td height='30' align='right' class='txt10'>Approved :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<input readonly=\"true\" type='text' name='txtApproved' maxlength='10' size='20' class='txtfield input-readonly' value='$approvedcl'/>
	                </td>
	            </tr>
	            <!-- Remove PDA-RAR Code here -->
	            </table>
	            </td>
	            <td style='width: 30%;' class='bgF9F8F7'></td>
	            </tr>
	            </table>		
		  	</div>
		  </div>
		  <div class='Page' id='div5' style='display: none;'>
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
	                <td height='30' align='right' class='txt10' valign='top'>Remarks :</td>
	                <td height='30'>&nbsp;</td>
	                <td height='30'>
	                	<textarea rows='3' cols='30' name='txtRemarks'/>$customerRemarks</textarea>
	                </td>
	            </tr>
	            <tr>
	               <td width='40%' height='30' class='bgF9F8F7' align='right'>&nbsp;<div align='right' class='txt10'>Application Form :</div></td>
	               <td height='30' class='bgF9F8F7'>&nbsp;
	                </td>
	               <td height='30'  class='bgF9F8F7'>
	               	 $applicationForm
	               </td>
	           </tr>
	            </table>
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
	
		</td>
	  </tr>
	</table>";//$drpPDARARCode
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="css/tab-view.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>

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
<script type="text/javascript">
$j = jQuery.noConflict();
var inival=0;
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
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}
function getSelectionCOAID2(text,li) {
	//alert (text);	
	//d = document.form;
	
	tmp = li.id;
	tmp_val = tmp.split("_");

	var Name  		= eval('document.frmDealer.txtRecruiteName');
	var ID  		= eval('document.frmDealer.hdnRec');
	var Code  	= eval('document.frmDealer.txtRecruiteAcctNo');
	var CellPhone 	= eval('document.frmDealer.txtRecruiterCP');
	ID.value = tmp_val[0];
	Code.value = tmp_val[2];
	Name.value = tmp_val[1];
	CellPhone.value = tmp_val[3];
	
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
	}
}

function form_validation()
{
	msg = '';
	str = '';
	obj = document.frmDealer.elements;


	// TEXT BOXES
	if (trim(obj["txtlnameDealer"].value) == '')msg += '   * Last Name \n';
	if (trim(obj["txtfnameDealer"].value) == '')msg += '   * First Name \n';
	if (trim(obj["txtmnameDealer"].value) == '')msg += '   * Middle Name \n';

	if (msg != '')
	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else return true;
}

function form_validation2(id)
{
	msg = '';
	str = '';
	obj = document.frmDealer.elements;

	custid = id;

	if (custid != 0)
	{	
		// TEXT BOXES
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
	
		if (msg != '')
		{ 
		  alert ('Please complete the following: \n\n' + msg);
		  return false;
		}
		else return true;

	}
	else
	{
		alert ('Please select a dealer to update.');
		  return false;
	}
	
}

function isDate(fld) 
{
   var value = fld.value;
   var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
   var matchArray = value.match(datePat); // is the format ok?
   
   if (matchArray == null) 
   {
   		alert("Please enter date as either mm/dd/yyyy or mm-dd-yyyy.");
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
		div1.style.display = 'none'; 
		div5.style.display = 'none'; 
	}
	
}
</script>
<script type="text/javascript" src="tab-view.js"></script>
<script type="text/javascript">
	tabview_initialize('TabView');
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
    <td class="txtgreenbold13">Update Dealer Profile</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
    <form name="frmSearchDealer" method="post" action="index.php?pageid=70">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  <tr>
		    <td><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
		        <tr>
		          <td width="20%">&nbsp;</td>
		          <td width="10%" align="right">&nbsp;</td>
		          <td width="70%" align="right">&nbsp;</td>
		        </tr><?php /* 
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
		      
		      <!--
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
		                <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		                    <tr align="center">
		                      <td width="40%"><div align="center">&nbsp;<span class="txtredbold">Code</span></div></td>
		                      <td width="60%"><div align="center"><span class="txtredbold">Name</span></div></td>
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
	-->
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
			window.onload = showPage("1", "<?php echo $cID; ?>", "<?php echo $vSearch; ?>","2");    
   		 </script>
	</td>
	<td width="2%">&nbsp;</td>
	<td width="60%" valign="top">
     <form name="frmDealer" method="post" action="index.php?pageid=70<?php if(isset($_GET['custid'])) { echo '&custid='.$_GET['custid']; } else { echo '';}; ?><?php if(isset($_GET['action'])) { echo '&action='.$_GET['action']; } else { echo '';}; ?>" onsubmit="return form_validation();" enctype='multipart/form-data'>
     	<input type="hidden" id="hCustID" name="hCustID" value="<?php echo $custid; ?>">
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
             <tr>
                <td height="30" align="right" class="txt10" width="30%">Code :</td>
                <td height="30" width="5%">&nbsp;</td>
                <td height="30" width="65%"><input readonly="true" type="text" name="txtcodeDealer" maxlength="50" size="40" class="txtfield input-readonly" value="<?PHP if(isset($_GET['custid'])) { echo $code; };?>" ></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10" width="30%">Last Name :</td>
                <td height="30" width="5%">&nbsp;</td>
                <td height="30" width="65%"><input type="text" name="txtlnameDealer" maxlength="50" size="40" class="txtfield" value="<?PHP if(isset($_GET['custid'])) { echo $lname; };?>" ></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">First Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><input type="text" name="txtfnameDealer" maxlength="50" size="40" class="txtfield" value="<?PHP if(isset($_GET['custid'])) { echo $fname; };?>" /></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Middle Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><input type="text" name="txtmnameDealer" maxlength="50" size="10" class="txtfield" value="<?PHP if(isset($_GET['custid'])) { echo $mname; };?>" ></td>
            </tr>
            
            <tr>
              <td height="30" align="right" class="txt10">Birthdate :</td>
              <td height="30">&nbsp;</td>
              <td height="30">
              <input name="txtbdaydealer" type="text" class="txtfield" id="txtbdaydealer" size="20" value="<?PHP if(isset($_GET['custid'])) { echo $bday; } else { if(isset($_POST['txtbdaydealer'])){  echo $_POST['txtbdaydealer'];}  }?>" size="30" onblur="isDate(this)">
                <input type="button" class="buttonCalendar" name="anchorTxnDate" id="anchorTxnDate" value=" ">
                <div id="divtxnDate" style="background-color : white; position:absolute; visibility:hidden;"> </div>
              </td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Dealer Status :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?php echo $dealerStatus; ?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Last Appointment Date :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?php echo $lastAppointmentDate; ?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Last Termination Date :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?php echo $lastTerminationDate; ?></td>
            </tr>
                
            	<?php echo $additionalFields;?>
            <tr>
            	<td height="10">
            	</td>
            </tr>
            </table>		
        </td>
         </tr>
       </table>
        <br>
        <?php echo $additionaltable;?>
        <?php echo $updateButtonDisplay; ?>
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
		ifFormat       :    "%Y-%m-%d",      // format of the input field
		displayArea    :	"divtxnDate",
		button         :    "anchorTxnDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
	
</script>


