<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="../../css/tab-view.css">
<?PHP 
	ini_set('display_errors',1);	
	require_once "../../initialize.php";	
	include IN_PATH.DS."scCustomerDetails.php";
	
	$custid = $_GET['custid'];
	
	$id = isset($_GET['id']) ? $_GET['id'] : 1;
	
	//display error message
	$errMessage='';
	if (isset($_GET['msg']))
    {
          $message = strtolower($_GET['msg']);
          $success = strpos("$message","success"); 
          $errMessage = "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
    }
    else if (isset($_GET['errmsg']))
    {
		$errormessage = strtolower($_GET['errmsg']);
		$error = strpos("$errormessage","error"); 
		$errMessage = "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";                        	
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
    
    //tabs view
   	$otherdetails = "<a onclick=toggleDivTag('div1') href=#>Other Details</a>";
	$directselling = "<a onclick=toggleDivTag('div2') href=#>Direct Selling</a>";
	$recruiter = "<a onclick=toggleDivTag('div3') href=#>Recruiter</a>";
	$credit = "<a onclick=toggleDivTag('div4') href=#>Credit</a>";
	$remarks = "<a onclick=toggleDivTag('div5') href=#>Remarks</a>";
	$bankinfo = "<a onclick=toggleDivTag('div6') href=#>Bank Info</a>";
	
	 //file download
	    $applicationForm='';
		if(!$rsFile->num_rows)
		{
			$applicationForm = "";
		}
		else
		{
			while ($row = $rsFile->fetch_object())
			{
				$customerid = $row->CustomerID;
				$applicationfilepath = $row->ApplicationFilePath;
				list($str1, $str2, $str3) = explode("\\", $applicationfilepath, 3);
				$applicationForm = "<br><a href='../../testfile.php?filepath=$applicationfilepath'>$str3</a>";
			}
			$rsFile->close();
		}

		//addtional comapnies
		$additionalCompany = '';
		if ($rsExistCompany->num_rows)
		{
			while ($row = $rsExistCompany->fetch_object())
			{
			   $additionalCompany .= "$row->Details <br>";
			}
			$rsExistCompany->close();
		}
?>
<script type="text/javascript">
function redirect(linkid)
{
opener.location.href=linkid;
window.close();
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
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="2%">&nbsp;</td>
	<td width="60%" valign="top">
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Dealer Details</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         <tr>
           <td class="bgF9F8F7"><?php echo $errMessage;?></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
             <tr>
                <td height="30" width="20%" align="right" class="txt10" width="30%">Code :</td>
                <td height="30" width="2%">&nbsp;</td>
                <td height="30" width="78%"><?PHP echo $igscode;?></td>
            </tr>
            <tr>
                <td height="30" width="20%" align="right" class="txt10" width="30%">Last Name :</td>
                <td height="30" width="2%">&nbsp;</td>
                <td height="30" width="78%"><?PHP echo $lname;?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">First Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?PHP echo $fname;?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Middle Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?PHP echo $mname;?></td>
            </tr>
            <tr>
              <td height="30" align="right" class="txt10">Birthdate :</td>
              <td height="30">&nbsp;</td>
              <td height="30"><?PHP echo $bday;?></td>
            </tr>
            <tr>
              <td height='30' align='right' class='txt10'>Classification :</td>
              <td height='30'>&nbsp;</td>
              <td height='30'><?php echo $cclassname; ?></td>
            </tr>
            <tr>
              <td height='30' align='right' class='txt10'>Dealer Type :</td>
              <td height='30'>&nbsp;</td>
              <td height='30'><?php echo $custtypename; ?>
              </td>
            </tr>
             <tr>
              <td height='30' align='right' class='txt10'>GSU Type :</td>
              <td height='30'>&nbsp;</td>
              <td height='30'>  <?php echo $gsuname; ?></td>
            </tr>
             <tr>
                <td height='30'  align='right' class='txt10'>IBM No. :</td>
                <td height='30'>&nbsp;</td>
                <td height='30' ><?php echo $ibmcode; ?></td>
            </tr>
            <tr>
                <td height='30' align='right' class='txt10'>IBM Name :</td>
                <td height='30'>&nbsp;</td>
                <td height='30'><?php echo $ibmname; ?></td>
            </tr>
            <tr>
            	 <td colspan="3" height='20'></td>
            </tr>
            </table>		
        </td>
         </tr>
       </table>
        <br>
	
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" >
  <tr>
	<td>
	<div class="TabView" id="TabView">
	
	<!-- ***** Tabs ************************************************************ -->
	
	<div class="Tabs" style="width: 700px;">
		<?php echo $otherdetails ;?>
	  	<?php echo $directselling ;?>
	  	<?php echo $recruiter ;?>
	  	<?php echo $credit ;?>
	  	<?php echo $remarks ;?>
	</div>
	
	
	<!-- ***** Pages *********************************************************** -->
	
	<div class="Pages" style="border-left:1px solid #959F63;border-bottom:1px solid #959F63;border-right:1px solid #959F63;border-top:1px solid #959F63;">
	  <div class="Page" id='div1' style='display: block;'>
	  	<div class="Pad">
	  	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
         <tr>
           <td class="bgF9F8F7"></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7" align="center">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="35%" height="30" align="right" class="txt10"><div align="left" class="padl5">Nickname :</div></td>
                <td width="2%" height="30">&nbsp;</td>
                <td width="63%" height="30"><?PHP echo $nickname;?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10"><div align="left" class="padl5">Home Telephone Number :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30"><?PHP echo $telno;?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10"><div align="left" class="padl5">Cellphone Number :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30"><?PHP echo $mobileno;?></td>
            </tr>
            <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">Street Address :</div></td>
              <td height="30">&nbsp;</td>
             <td height="30"><?PHP echo $address;?></td>
            </tr>
             <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">State/Province :</div></td>
              <td height="30">&nbsp;</td>
               <td height="30">	
           					<?php echo $provinceid; ?>	
              </td>
              <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">Town/City :</div></td>
              <td height="30">&nbsp;</td>
              <td height="30">	
             	
					<?php echo $townid; ?>			
               
              </td>
            </tr>
            
             <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">Barangay :</div></td>
              <td height="30">&nbsp;</td>
             <td height="30">	
             
					<?php echo $barangayid; ?>			
           
              </td>
            </tr>
            </tr>
             <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">Zip Code :</div></td>
              <td height="30">&nbsp;</td>
             <td height="30"><?PHP echo $zipcode;?></td>
            </tr>
             <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">Zone :</div></td>
              <td height="30">&nbsp;</td>
              <td height="30">	
             	
					<?php echo $zonename; ?>			
               
              </td>
            </tr>
             <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">Length of Stay :</div></td>
              <td height="30">&nbsp;</td>
             <td height="30"><?php $length = $length == 0 ? '' : $length; echo $length; ?></td>
            </tr>
             <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">Marital Status :</div></td>
              <td height="30">&nbsp;</td>
              <td height="30">	
             	
					<?php echo $fieldid; ?>		
                
              </td>
            </tr>
             <tr>
              <td height="30" align="right" class="txt10"><div align="left" class="padl5">Educational Attainment :</div></td>
              <td height="30">&nbsp;</td>
              <td height="30">	
             	
					<?php echo $efieldid; ?>			
                
              </td>
            </tr>
            </table>
            </td>
            <td style="width: 30%;"></td>
            </tr>
            </table>		
	  	</div>
	  </div>
	  <div class="Page" id='div2' style='display: none;'>
	  	<div class="Pad">
	  	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
         <tr>
           <td class="bgF9F8F7"></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7" align="center">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50%" height="30" align="right" class="txt10"><div align="left" class="padl5">Do you have direct selling experience ? :</div></td>
                <td width="50%" height="30">
                 <?php if($chk == "checked")
                {
                	echo 'Yes';
                }
                else
                {
                echo 'No';
                }?>
                </td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10"><div align="left" class="padl5">In what companies ? :</div></td>
                <td height="30"><?PHP $company = $company == 'null' ? '' : $company; echo $company;?><br>
                		<?php echo $additionalCompany; ?>
                </td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10"><div align="left" class="padl5">Are you employed ? :</div></td>
                <td height="30">
                <?php if($chk3 == "checked")
                {
                	echo 'Yes';
                }
                else
                {
                echo 'No';
                }?>
                </td>
            </tr>
            </table>
            </td>
            	<td style="width: 30%;"></td>
            </tr>
            </table>		
	  	</div>
	  </div>
	  <div class="Page" id='div3' style='display: none;'>
	  	<div class="Pad">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
         <tr>
           <td class="bgF9F8F7"></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7" align="center">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="33%" height="30" align="center" class="txt10"><div align="left" class="padl5">Recruiter Account Number :</div></td>
                <td height="30">&nbsp;</td>
                <td  height="30">
                	<?PHP echo $accountno;?><br>
                </td>
            </tr>
            <tr>
                <td width="33%" height="30" align="center" class="txt10"><div align="left" class="padl5">Recruiter Name :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30">
                	<?PHP echo $recruitername;?><br>
                </td>
            </tr>
            <tr>
                <td width="33%" height="30" align="center" class="txt10"><div align="left" class="padl5">Cellphone Number :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30">
                	<?PHP echo $recruitermobileno;?>
                </td>
            </tr>
            </table>
            </td>
            <td style="width: 30%;"></td>
            </tr>
            </table>		
		</div>
	  </div>
	  <div class="Page" id='div4' style='display: none;'>
	  	<div class="Pad">
	  			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
         <tr>
           <td class="bgF9F8F7"></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7" align="center">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="35%" height="30" align="center" class="txt10"><div align="left" class="padl5">Credit Term :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="left">
                	
						<?php echo $crtname; ?>		
                	
                </td>
            </tr>
            <tr>
                <td width="35%"  height="30" align="center" class="txt10"><div align="left" class="padl5"><strong>Credit Interview Score</strong></div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="center"></td>
            </tr>
            <tr>
                <td width="35%"  height="30" align="center" class="txt10"><div align="left" class="padl5">Character Score :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="left">
                	<?PHP echo $character;?>
                </td>
            </tr>
              <tr>
                <td width="35%"  width="33%"  height="30" align="center" class="txt10"><div align="left" class="padl5">Capacity / Capability Score :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="left">
                	<?PHP echo $capacity;?>
                </td>
            </tr>
              <tr>
                <td width="35%"  height="30" align="center" class="txt10"><div align="left" class="padl5">Capital Score :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="left">
                	<?PHP echo $capital;?>
                </td>
            </tr>
             <tr>
                <td width="35%"  height="30" align="center" class="txt10"><div align="left" class="padl5">Condition Score :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="left">
                	<?PHP echo $condition;?>
                </td>
            </tr>
            <tr>
                <td width="35%"  height="30" align="center" class="txt10"><div align="left" class="padl5"><strong>Credit Limit Amount</strong></div></td>
                <td height="30">&nbsp;</td>
                <td height="30" ></td>
            </tr>
            <tr>
                <td width="35%"  height="30" align="center" class="txt10"><div align="left" class="padl5">Calculated :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="left">
                	<?PHP echo $calculatedcl;?>
                </td>
            </tr>
              <tr>
                <td width="35%"  height="30" align="center" class="txt10"><div align="left" class="padl5">Recommended :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="left">
                	<?PHP echo $recommendedcl;?>
                </td>
            </tr>
              <tr>
                <td width="35%"  height="30" align="center" class="txt10"><div align="left" class="padl5">Approved :</div></td>
                <td height="30">&nbsp;</td>
                <td height="30" align="left">
                	<?PHP echo $approvedcl;?>
                </td>
            </tr>
            <?php if ($customertypeid == 1 || $customertypeid ==4)
            {
            	echo "<tr>
                <td width='35%' height='30' align='center' class='txt1'><div align='left' class='padl5'>PDA-RAR Code :</div></td>
                <td height='30'>&nbsp;</td>
                <td height='30' align='left'>
                	$pdacode
                </td>
            </tr>";
            	
            }	
            ?>
           
            </table>
            </td>
            <td style="width: 30%;"></td>
            </tr>
            </table>		
	  	</div>
	  </div>
	  <div class="Page" id='div5' style='display: none;'>
	  	<div class="Pad">
	  			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
         <tr>
           <td class="bgF9F8F7"></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7" align="center">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="30" align="right" class="txt10" valign="top">Remarks :    <?php $customerRemarks = $customerRemarks == 'null' ? '' : $customerRemarks;  echo $customerRemarks; ?></td>
                <td height="30">&nbsp;</td>
            </tr>
            <tr>
               <td width="40%" height="30" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10">Application Form :</div></td>
               <td height="30" class="bgF9F8F7">&nbsp;
                </td>
               <td height="30"  class="bgF9F8F7">
               		<?php echo $applicationForm; ?>
               </td>
           </tr>
            </table>
            </td>
            <td style="width: 30%;"></td>
            </tr>
            </table>	
	  	</div>
	  </div>
	</div>
		
	</div>
		<script type="text/javascript" src="tab-view.js"></script>
		<script type="text/javascript">
			tabview_initialize('TabView');
		</script>
	</td>
  </tr>
</table>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <br>
  <tr>
    <td align="center">
		<input name="btnRUpdate" type="submit" class="btn" value="Update" onclick="javascript: redirect('../../index.php?pageid=69&custid=<?php echo $custid; ?>&action=update');">
    	<input name="btnProceed" type="submit" class="btn" value="Proceed" onclick="javascript: redirect('../../index.php?pageid=69&action=new');">
    	<?php if($dstatusid == 5)
    	{
    	?>
    	<input name="btnReactivate" type="submit" class="btn" value="Reactivate" onclick="javascript: redirect('../../index.php?pageid=75&custid=<?php echo $custid; ?>');">	
    	<?php
    	}
    	?>
    </td>
  </tr>
</table>
</td>
</tr>
</table>
<br>