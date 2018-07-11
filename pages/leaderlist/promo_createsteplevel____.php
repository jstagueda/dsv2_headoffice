<!-- <meta http-equiv="refresh" content=""> -->
<?php
	if(isset($_SESSION['buyina']))
	{
		unset($_SESSION["buyina"]);
		echo '<meta http-equiv="refresh" content="5">';
	}

	include IN_PATH.DS."scCreateStepLevelPromo.php";
	/* if ((isset($_POST['btnVerify'])))
	
	{

		$database->beginTransaction();			
		$promocode = htmlentities(addslashes($_POST['txtCode']));
	
		
	//check if promo code already exist
	$rs_code_exist = $sp->spCheckPromoIfExists($database, $promocode);
	if($rs_code_exist->num_rows){
				$errmsg = "Existing Promo code.";
				redirect_to("index.php?pageid=65&error=$errmsg");				
		}
		else{

			if(empty($promocode)){
			
				$errmsg = "Please enter Promo code.";
				redirect_to("index.php?pageid=65&error=$errmsg");				
	
			}
			else{
				//$errmsg = "Promo code does not exists.";				
				redirect_to("index.php?pageid=65.1&code=$promocode");					
		
			}
		}
	} */
?>	
<script type="text/javascript">
function confirmVerify()
{
	alert(jQuery('#txtCode').val());
	return false;

	
}

function validateForm()
{
var x=document.forms["frmCreateStepLevel"]["txtCode"].value;
if (x==null || x=="")
  {
  alert("Enter Promo code.");
  redirect_to("index.php?pageid=65");
  return false;
  }

}

function RemoveInvalidChars(strString)
{
    var iChars = "1234567890.";
	   
   var strtovalidate = strString.value;
   var strlength = strtovalidate.length;
   var strChar;
   var ctr = 0;
   var newStr = '';
   if (strlength == 0)
   {
	return false;
   }

	for (i = 0; i < strlength; i++)
	{
		strChar = strtovalidate.charAt(i);
			if 	(!(iChars.indexOf(strChar) == -1))
			{
				newStr = newStr + strChar;
			}
	}
	strString.value = newStr;
}
</script>


<?php
if(isset($_GET['error'])){
?>
<script>
alert("<?php echo $_GET['error']; ?>");
</script>
<?php
}
?>


<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<!-- product list -->
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxCreateStepLevelPromo.js"  type="text/javascript"></script>

<style type="text/css">
<!--
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
}
-->
</style>

<script language="javascript" type="text/javascript">
  function RemoveInvalidChars(strString)
  {
      var iChars = "1234567890.";
  	   
     var strtovalidate = strString.value;
     var strlength = strtovalidate.length;
     var strChar;
     var ctr = 0;
     var newStr = '';
     if (strlength == 0)
     {
  	return false;
     }

  	for (i = 0; i < strlength; i++)
  	{
  		strChar = strtovalidate.charAt(i);
  			if 	(!(iChars.indexOf(strChar) == -1))
  			{
  				newStr = newStr + strChar;
  			}
  	}
  	strString.value = newStr;
  }
  </script> 
  
<body onload="return enableFields();">
<input type="hidden" id="hBuyInCnt" name="hBuyInCnt" value="<?php echo $bcnt; ?>">
<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="0">
<input type="hidden" id="hRangeID" name="hRangeID" value="<?php echo $levelid; ?>">
<input type="hidden" id="hPMGID" name="hPMGID" value="<?php echo $pmg_id; ?>">
<input type="hidden" id="hPMGCode" name="hPMGCode" value="<?php echo $pmg_code; ?>">
<form name="frmCreateStepLevel" method="post" action="index.php?pageid=65">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		    <td width="70%" class="txtgreenbold13" align="left"></td>
			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
	
		<td width="70%">&nbsp;<a class="txtgreenbold13">Create Step Level Promo</a></td>
	
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
	if ($errmsg != "")
	{
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtreds">&nbsp;<b><?php echo $errmsg; ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php		
	}
?>

<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">
	<input type="hidden" id="hEntIndex" name="hEntIndex" value="">
	<input type="hidden" id="hEntCnt" name="hEntCnt" value="">
	</td> 
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="50%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="25%">&nbsp;</td>
					<td width="75%" align="right">&nbsp;</td>
				</tr>		
				
			   <tr>
				    <td height="20" align="right"><div align="right" class="txtpallete"><strong>Promo Code :</strong></div></td>
				      <td height="20">&nbsp;&nbsp;<input name="txtCode" id="txtCode" type="text" size="15" value="<?php if (isset($_GET['promoID'])) { echo $promocode; } else { if (isset($_POST['txtCode'])) { echo $_POST['txtCode']; } } ?>" size="30" <?php if (isset($_GET['promoID'])) {?>readonly="yes"<?php } ?>>
						&nbsp;&nbsp;&nbsp;Click to verify Promo Code if Exist.&nbsp;<input name="btnVerify" class='btn' type="submit" onclick = 'return confirmVerify();' />			 
					</td>					
			    </tr>
				
			    <tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Promo Description :</strong></div></td>
				    <td height="20">&nbsp;&nbsp;<input name="txtDescription" disabled type="text" class="txtfieldg" id="txtDescription" value="<?php if (isset($_POST['txtDescription'])) { echo $_POST['txtDescription']; } ?>" style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>Start Date: </strong></div></td>
				  	<td height="20">&nbsp;
						<input name="txtStartDate" type="text" disabled class="txtfieldg" id="txtStartDate" size="20" readonly="yes" value="<?php if (isset($_POST['txtStartDate'])) { $tmpsdate = strtotime($_POST['txtStartDate']); $sdate = date("m/d/Y",$tmpsdate); echo $sdate; } else { echo $today; } ?>" onChange="checkEndDate();">
						<input type="button" disabled class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>End Date: </strong></div></td>
				  	<td height="20">&nbsp;
						<input name="txtEndDate" disabled type="text" class="txtfieldg" id="txtEndDate" size="20" readonly="yes" value="<?php if (isset($_POST['txtEndDate'])) { $tmpedate = strtotime($_POST['txtEndDate']); $edate = date("m/d/Y",$tmpedate); echo $edate; } else { echo $today; } ?>">
						<input type="button" disabled class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>	
					<tr>
				  		<td height="20"><div align="right" class="txtpallete"><strong>Purchase Requirement Type: </strong></div></td>
				  	<td height="20">&nbsp;
					<?php if(isset($_SESSION['buyina']))
				  	  {
					  	  if (sizeof($_SESSION['buyina']))
							   {
								$buyin_list = $_SESSION["buyina"];
								$rowalt=0;
													//while($row = $rspromobuyin->fetch_object())
								for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ ) 
									{
										$preqttype2 = $buyin_list[$i]['PReqType'];
									}
									
							   }				  	  	
				  	  ?>
						<select name="cboPReqtType" disabled class="txtfieldg" id="cboPReqtType" <?php if (isset($_GET['promoID'])) {?>disabled<?php } ?>>
							<option value="1" <?php if (isset($_GET['promoID'])) { if ($_GET['promoID'] == 1) { echo "selected"; } } else { if (isset($_POST['cboPReqtType'])) { if ($_POST['cboPReqtType'] == 1) { echo "selected";} } }?>>Single</option>
							<option value="2" <?php if (isset($_GET['promoID'])) { if ($_GET['promoID'] == 2) { echo "selected"; } } else { if (isset($_POST['cboPReqtType'])) { if ($_POST['cboPReqtType'] == 2) { echo "selected";} } }?>>Cumulative</option>
						</select>	
					</td>
				</tr>		
				
					<?php 	
				  	  }else{
				  	 ?>
				  	 
						<select name="cboPReqtType"  disabled class="txtfieldg" id="cboPReqtType">
							<option value="1" <?php if (isset($_POST['cboPReqtType'])) { if ($_POST['cboPReqtType'] == 1) { echo "selected";} } ?>>Single</option>
							<option value="2" <?php if (isset($_POST['cboPReqtType'])) { if ($_POST['cboPReqtType'] == 2){ echo "selected";} } ?>>Cumulative</option>
						</select>
						<?php 
						}
						?>	
						
						
					</td>
				</tr>
				<tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>Buy-in Requirement: </strong></div></td>
				  	<td height="20" class="txtpallete">
				  		<input type="radio" disabled name="rdoBReqt" id="rdoBReqt" value="2" 
                                                Selection	
					</td>
				</tr>	
				<tr>
				  	<td height="20">&nbsp;</td>
				  	<td height="20" align="left" class="txtpallete">
				  		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  		Criteria:
				  		&nbsp;
				  		<select name="cboPHCriteria" disabled class="txtfieldg" id="cboPHCriteria" style="width: 150px;">
							<option disabled value="1" <?php if (isset($_POST['cboPHCriteria'])) { if ($_POST['cboPHCriteria'] == 1) { echo "selected";} } ?>>Quantity</option>
							<option disabled value="2" <?php if (isset($_POST['cboPHCriteria'])) { if ($_POST['cboPHCriteria'] == 2) { echo "selected";} } ?>>Amount</option>
						</select>	
					</td>
				</tr>	
				<tr>
				  	<td height="20">&nbsp;</td>
				  	<td height="20" align="left" class="txtpallete">
				  		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  		Minimum Value:
				  		&nbsp;
				  		<input name=""  type="text" disabled value="" class="txtfieldg" style="width: 150px">
					</td>
				</tr>	
				<tr>
				  	<td height="20">&nbsp;</td>
				  	<td height="20" class="txtpallete">
				  		<input type="radio"   name="rdoBReqt" id="rdoBReqt" value="1" disabled onClick="" >
                                                Multiple Buy-in Requirement	
					</td>
				</tr>		
				<tr>
					<td colspan="2" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="15%">&nbsp;</td>
					<td width="85%">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" valign="top"><div align="right" class="txtpallete"><strong>Max Availment :</strong></div></td>
				    <td height="20">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				    	<?php
				    	if($rs_gsutype->num_rows)
						{
							while($row = $rs_gsutype->fetch_object())
							{
								$txt = 'txtMaxAvail'.$row->ID;
								if (isset($_POST["$txt"]))
								{
									$val = $_POST["$txt"]; 									
								}
								else
								{
									$val = "";									
								}
								echo "<tr>
				    						<td width='15%' height='20'><div align='right' class='txtpallete'><strong>$row->Name :</strong></div></td>
				    						<td width='75%' height='20'>&nbsp;<input type='text' disabled class='txtfieldg' name='$txt' value='$val' onkeyup='javascript:RemoveInvalidChars($txt);'></td>
				    				</tr>";
							}
							$rs_gsutype->close();
						}
				    	?>
				    	</table>
				    </td>
			    </tr>
			    <tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Is Plus Plan :</strong></div></td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1" ></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td valign="top" width="45%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td height="20" width="30%">&nbsp;</td>
							<td height="20" width="70%" align="right">&nbsp;</td>
						</tr>	
                                              
						<tr>
							<td height="20"><div  align="right" class="txtpallete"><strong>Step Level No. :</strong></div></td>
							<td height="20" class="txtredbold">&nbsp;&nbsp;<?php echo $ctr; ?></td>
						</tr>
					
					
					    <tr>
						    <td height="21"><div  align="right" class="txtpallete"><strong>Range :</strong></div></td>
						    <td height="21">&nbsp;
						    	<select name="cboRange" disabled style="width:150px; height:20px;" class="txtfieldg" onChange="">
                                                        <option value="0">[SELECT HERE]</option>
                                </select>                      
							</td>
					    <tr>
						    <td height="22"><div align="right" class="txtpallete"><strong>Selection :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtCriteria" disabled type="text" class="txtfieldg" id="txtCriteria" style="width: 150px;" value="" />
						    	<span id="indicatorCriteria" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>
						    	<div id="prod_choices_criteria" class="autocomplete" style="display:none"></div>						    	
						    	<br>&nbsp;
						    	<input name="txtCDescription"  disabled type="text" class="txtfieldg" id="txtCDescription" style="width: 250px;" value="" readonly="yes" />
						    	<input name="hProdID_criteria" disabled type="hidden" id="hProdID_criteria" value="" />
						    </td>
						</tr>
					    <tr>
						    <td height="22"><div align="right" class="txtpallete"><strong>Criteria :</strong></div></td>
						    <td height="22">&nbsp;
						    	<select name="cboCriteria" disabled class="txtfieldg" id="cboCriteria" style="width: 150px;" disabled="yes">
									<option value="">Quantity</option>
									<option value="">Amount</option>
								</select>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div  align="right" class="txtpallete"><strong>Minimum Value :</strong></div></td>
						    <td height="22">&nbsp;&nbsp;<input name="txtMinimum" disabled type="text" value="" class="txtfieldg" style="width: 150px" disabled="yes"></td>
					    </tr>
					    <tr>
						    <td height="22"><div  align="right" class="txtpallete"><strong>Start Date :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtSetStartDate" disabled type="text" class="txtfieldg" id="txtSetStartDate" size="20" style="width: 150px" readonly="yes" value="<?php echo $today; ?>">
								<input type="button"  disabled class="buttonCalendar" name="anchorSetStartDate" id="anchorSetStartDate" value=" " disabled="yes">
								<div id="divSetStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div  align="right" class="txtpallete"><strong>End Date :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtSetEndDate" disabled type="text" class="txtfieldg" id="txtSetEndDate" size="20" style="width: 150px" readonly="yes" value="<?php echo $end; ?>">
								<input type="button" disabled class="buttonCalendar" name="anchorSetEndDate" id="anchorSetEndDate" value=" " disabled="yes">
								<div id="divSetEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
						    </td>
					    </tr>
						<tr>
							<td colspan="2" height="20">&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right">
				<input name='btnAdd' type='submit' disabled class='btn' value='Add' onclick='return confirmAdd();'>
			</td>			
		</tr>
		</table>
		<br>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td> 
					<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
				</table>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
				<tr>
					<td valign="top" class="bgF9F8F7">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td>
								<table width="100%"  border="0" cellpadding="0" cellspacing="0" class=" tab">
								<tr align="center">
									<td width="5%" height="25" class="borderBR"><div align="center"><input name="chkAll" disabled type="checkbox" id="chkAll" value="1"  onclick="checkAll(this.checked);"></div></td>
                                                                        

									<td width="15%" height="25" class="txtpallete borderBR"><div align="center">Step Level No.</div></td>									
									<td width="27%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td>
									<td width="10%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>			
									<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>									
									<td width="15%" height="20" class="txtpallete borderBR"><div align="center">Start Date</div></td>			
									<td width="15%" height="20" class="txtpallete borderBR"><div align="center">End Date</div></td>

								</tr>
								</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">
						<div class="scroll_150">
						<table width="100%"  border="0" cellpadding="0" cellspacing="1">
						<?php
						$cntr = 0;
							if(isset($_SESSION['buyina']))	
							{
								if (sizeof($_SESSION['buyina']))
								{
									$buyin_list = $_SESSION["buyina"];
									$rowalt=0;
									//while($row = $rspromobuyin->fetch_object())
									for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ ) 
									{
										$cntr++;
										$rowalt++;
										($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
										if($buyin_list[$i]['Criteria'] == 1)
										{
											$criteria = "Quantity";
											$minimum = $buyin_list[$i]['MinQty'];
										}
										else
										{
											$criteria = "Amount";
											$minimum = number_format($buyin_list[$i]['MinAmt'], 2, '.', '');
										}

										if($buyin_list[$i]['StartDate'] == "00/00/0000" || $buyin_list[$i]['StartDate'] == "null")
										{
											$startdate = "&nbsp;";
										}
										else
										{
											$startdate = $buyin_list[$i]['StartDate'];
										}
										if($buyin_list[$i]['EndDate'] == "00/00/0000" || $buyin_list[$i]['EndDate'] == "null")
										{
											$enddate = "&nbsp;";
										}
										else
										{
											$enddate = $buyin_list[$i]['EndDate'];
										}
										$proddesc = $buyin_list[$i]['ProdDesc'];
										$pmg = $buyin_list[$i]['PMGCode'];
										$prodid = $buyin_list[$i]['ProdID'];
										$PReqType = $buyin_list[$i]['PReqType'];
										echo "<tr align='center' class='$class'>
										      <td width='5%' height='20' class='borderBR'><input disabled type='checkbox' name='chkSelect[]' id='chkSelect'  value='$cntr' onclick=''>
										      <input type='hidden' id='hprodIDID$cntr' name='hprodIDID$cntr' value='$prodid'></td>
												<td width='15%' height='20' class='borderBR'><div align='center'>$rowalt</div></td>
												<td width='27%' height='20' class='borderBR'><div align='left' class='padl5'>$proddesc</div></td>
												<td width='10%' height='20' class='borderBR'><div align='center'>$criteria</div></td>			
												<td width='10%' height='20' class='borderBR'><div align='right' class='padr5'>$minimum</div></td>												
												<td width='15%' height='20' class='borderBR'><div align='center'>$startdate</div></td>
												<td width='15%' height='20' class='borderBR'><div align='center'>$enddate</div></td>
												
										</tr>";									
									}
								}
								else
								{
									echo "<tr><td colspan='5' height='22' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
								}						
							}
							else
							{
								echo "<tr><td colspan='5' height='22' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
							}
						?>
						</table>
						</div>
					</td>
				</tr>
					<tr>
					<td>
					&nbsp;&nbsp;&nbsp;<input name="btnRemove" disabled type="submit" class="btn" value="Remove" onClick ="return confirmRemove();"/></td>
					</tr> 
				</table>
			</td>
		</tr>
		</table>
	</td>
	<td width="1%">&nbsp;</td>
	<td width="47%" valign="top">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr align="center">
			<td height="25" class="borderBR"><div align="left" class="padl5 txtpallete">
				<strong>Type :</strong>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="cboType" disabled class="txtfieldg" id="cboType" style="width: 100px;" onChange ="selection();"">>
					<option value="2">Selection</option>
					<option value="1">Set</option>
				</select>
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<input name="txtTypeQty" disabled type="text" class="txtfieldg" id="txtTypeQty" style="width: 60px; text-align: right;">
			</div></td>
		</tr>
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="10%" height="20" class="borderBR"><div align="center" class="txtpallete">&nbsp;</div></td>
							<td width="8%" height="20"  class="borderBR"><div align="center" class="txtpallete">Line No.</div></td>
							<td width="12%" height="20" class="borderBR"><div align="left" class="txtpallete">Item Code</div></td>
							<td width="31%" height="20" class="borderBR"><div align="left" class="txtpallete">Item Description</div></td>			
							<td width="12%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>
							<td width="12%" height="20" class="txtpallete borderBR"><div align="center">Minimum</div></td>
							<td width="20%" height="20" class="txtpallete borderBR"><div align="center">PMG</div></td>			
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_400">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicEntTable">
					<tr align="center">				
					    <td width="10%" height="20" class="borderBR">
						 <input disabled name="btnRemove1" type="button" class="btn" value="Remove" onclick="deleteRow2(this.parentNode.parentNode.rowIndex)">						
						</td>
						<td width="8%"  height="20" class="borderBR"><div align="center" class="txtredbold padl5">1</div></td>
						<td width="12%" height="20" class="borderBR"><div align="left" class="padl5">
							<input name="txtEProdCode1" disabled type="text" class="txtfieldg" id="txtEProdCode1" style="width: 80%;" value=""/>
							<span id="indicatorE1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="prod_choicesE1" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								 //<![CDATA[
                                                                    var prod_choicesE1 = new Ajax.Autocompleter('txtEProdCode1', 'prod_choicesE1', 'includes/scProductListEntitlementAjax.php?index=1', {afterUpdateElement : getSelectionProductList2, indicator: 'indicatorE1'});																			
                                                            //]]>
							</script>
							<input name="hEProdID1" disabled type="hidden" id="hEProdID1" value="" />
							<input name="hEUnitPrice1" disabled type="hidden" id="hEUnitPrice1" value=""/>
<!--							<input name="hEpmgid1" type="hidden" id="hEpmgid1" value=""/>-->
						</div></td>
						<td width="31%" height="20" class="borderBR"><div align="left" class="padl5"><input name="txtEProdDesc1" disabled type="text" class="txtfieldg" id="txtEProdDesc1" style="width: 95%;" readonly="yes" /></div></td>			
						<td width="12%" height="20" class="borderBR"><div align="center">
							<select name="cboECriteria1" disabled class="txtfieldg" id="cboECriteria1" style="width: 80%;">
								<option value="2" selected="selected">Price</option>
								<option value="1">Quantity</option>
							</select>
						</div></td>
						<td width="12%" height="20" class="borderBR"><div align="center"><input name="txtEQty1" disabled type="text" class="txtfieldg" id="txtEQty1"  value="1" style="width: 50%; text-align:right" onKeyPress='return disableEnterKey(this, event, 1)'/></div></td>
						<td width="20%" height="20" class="borderBR"><div align="center">
<!--						<input name="txtPmg1" type="text" id="txtPmg1" class="txtfieldg" readonly="readonly"  style="width: 130px; text-align:left" value=""/>-->
						
							<select name="cboEPMG1" class="txtfieldg" disabled id="cboEPMG1" style="width: 80%;">
								<?php
									if($rs_pmg->num_rows)
									{
										while($row = $rs_pmg->fetch_object())
										{
											echo "<option value='$row->ID'>$row->Code</option>";										
										}
										$rs_pmg->close();
									}
								?>
							</select>
						</div></td>			
					</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
     <td align="right">
		<input name='btnSave' type='submit' class='btn' value='Save' disabled onclick='return confirmSave();'>
     </td>
     </form>
    
      
        <td align="left">
        <form name="frmViewStepLevel" method="post" action="index.php?pageid=64">        
		<input name='btnCancel' type='submit' class='btn' value='Cancel' onclick='return confirmCancel();'>   </form>
	</td>
       	
</tr>
</table>

</body>
<br>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divEndDate",
		button         :    "anchorEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtSetStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSetStartDate",
		button         :    "anchorSetStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtSetEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSetEndDate",
		button         :    "anchorSetEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>