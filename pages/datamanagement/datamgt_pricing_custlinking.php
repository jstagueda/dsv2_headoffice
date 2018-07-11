<?PHP
	include IN_PATH.DS."scPricingCustlinking.php";
?>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{ 		
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) 
		selObj.selectedIndex = 0;
}
function checkAll(bin) 
{
	var elms = document.frmCustomerLinking.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkSelect[]') 
	  {
		  elms[i].checked = bin;		  
	  }	
}
function checker()
{
	var ml = document.frmCustomerLinking;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkSelect[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}
function validatSave()
{
	var tempid = document.frmCustomerLinking.hTempID;
	
	if (tempid.value == 0)
	{
		alert('Select Pricing Template.');
		tempid.focus();
		tempid.select();
		return false;
	}
	if (!checker())
	{
		alert('Please select customer(s) to be linked.');
		return false;
	}
	else
	{
		if (confirm('Are you sure you want to save this transaction?') == false)
			return false;
		else
			return true;
	}	
}

function UnCheck()
{

    var chkAll = document.frmCustomerLinking.chkAll;
    
    chkAll.checked = false;
}
</script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<form name="frmCustomerLinking" method="post" action="index.php?pageid=42&tempid=<?php echo $_GET["tempID"];?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav.php");
		?>

      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management </span></td>
        </tr>
    </table>
    <br />
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Link Pricing Template to Customers </td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php if($message != '')
{
?>	
<br>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
	<td><span class="txtblueboldlink"><?php echo $message; ?></span></td>
</tr>
</table>
<?php } ?>
<br />
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td valign="top" width="80%">
        
           <table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
  
            <tr>
                <td width="20%" height="30" align="right" class="padr5"><strong>Select Pricing Template :</strong></td>
                <td width="30%" height="30">
                    <select name="lsrPTemplate" class="txtfield" style="width:150px" onChange="MM_jumpMenu('parent',this,0)">
     				<?PHP
						echo "<option value='index.php?pageid=42&tempID=0' selected>[SELECT HERE]</option>";
						if ($rs_cboRef->num_rows)
						{
							while ($row = $rs_cboRef->fetch_object())
							{
								if ($tempid == $row->ID) 
									$sel = "selected";
								else 
									$sel = "";
								echo "<option value='index.php?pageid=42&tempID=".$row->ID."' $sel >".$row->Code."</option>";											
							}
						}		
					?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="20%" height="30" align="right" class="padr5"><strong>Search :</strong></td>
                <td width="30%" height="30">
                	<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30">
                	<input name="btnSearch" type="submit" class="btn" value="Go">
                </td>
            </tr>
            
            </table>
        
        </td>
      </tr>
      <tr>
      	<td valign="top">
          	<br>
          	<!--<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50%" align="right" class="txtblueboldlink"><div>List Of Customer 1 2 3 4 5 Next</div></td>	
            </tr>
            </table>-->
           <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td class="tabmin">&nbsp;</td>
				<td class="tabmin2">&nbsp;</td>
				<td class="tabmin3">&nbsp;</td>
			</tr>
			</table>


<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen bgF9F8F7 txtdarkgreenbold10">
<tr class="tab">
	<td width="30%" height="20" align="center" class="bdiv_r">LIST OF LINKED CUSTOMER(S)</td>
	<td width="65%" height="20" align="center" class="bdiv_r">LIST OF CUSTOMER(S)</td>
</tr>
</table>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
<tr>
	<td width="30%" class="borderBR">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="tab">
			<td width="25%" height="20" class="bdiv_r padl5" align="left"><strong>Code</strong></td>
			<td width="75%" height="20" class="bdiv_r padl5" align="left"><strong>Name</strong></td>
		</tr>
		<tr>
			<td colspan="2"><div class="scroll_300" id="dvCustomerLinking">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
						<?php 
						if($rs_customerbypricelist->num_rows)
						{
							$rowalt=0;
							while($row = $rs_customerbypricelist->fetch_object())
							{
								$rowalt++;
								($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
								echo "<tr align='center' class='$class'>
									<td height='20' class='borderBR' width='25%' align='left'>&nbsp;<span class='txt10'>$row->CustomerCode</span></td>
									<td class='borderBR' width='75%' align='left'>&nbsp;$row->Customer</td>
									</tr>
									";
							}
							$rs_customerbypricelist->close();			
						}
						else
						{
							echo "<tr align='center'><td height='20' colspan='2' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
						}
						?>
				</tr>
				</table>
			</div></td>
		</tr>
		</table>
	</td>
	<td width="65%" class="borderBR">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="tab">
			<td width="5%" height="20" align="center" class="bdiv_r"><div align="center">
			<input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);"/></div></td>
			<td width="20%" height="20" align="left" class="bdiv_r padl5"><strong>Code</strong></td>
			<td width="50%" height="20" align="left" class="bdiv_r padl5"><strong>Name</strong></td>
			<!--<td width="25%" height="20" align="center"><strong>Transaction Date </strong></td>-->
		</tr>
		<tr>
			<td colspan="4">
			<div class="scroll_300">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
					<?php 
					if($rs_customer->num_rows)
					{
						$rowalt=0;
						while($row = $rs_customer->fetch_object())
						{
							$rowalt++;
							($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
							
							//check if customer in the pricelist
							$exists = $sp->spSelectCustomerByPriceList($row->ID, $tempid);
							if($exists->num_rows)
							{
								$checked = 'checked';
							}
							else
							{
								$checked = '';
							}
							
							echo "<tr align='center' class='$class'>
								<td width='5%' height='20' align='center' class='borderBR'><input type='checkbox' onclick='UnCheck();' name='chkSelect[]' id='chkSelect' value='$row->ID' $checked/></td>
								<td height='20' class='borderBR' width='20%' align='left'>&nbsp;<span class='txt10'>\t\t\t\t\t\t$row->Code</span></td>
								<td class='borderBR' width='50%' align='left'>&nbsp;
								\t\t\t\t\t\t$row->Name</td>
								</tr>
								";
						}
						$rs_customer->close();
						/*<td width='25%' height='20' class='borderBR' align='center'>
								<input name='startDate[]' type='text' class='txtfield' id='startDate[]' size='20' readonly='yes' value=''>
                      			<a href='javascript:void(0);' onclick='g_Calendar.show(event, 'startDate[]', 'yyyy-mm-dd')' title='Show popup calendar'>
                      			<img src='images/btn_Calendar.gif' width='25' height='19' border='0' align='absmiddle' /></a>
								</td>*/			
					}
					else
					{
						echo "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
					}
					?>
			</tr>
			</table>
			</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br />
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
  <td align="center">
  	<input name="hTempID" type="hidden" value="<?php echo $tempid; ?>">
    <input name="btnSave" type="submit" class="btn" value="Save" onclick="return validatSave();"></td>
  </tr>
</table>
<br>            
        </td>
      </tr>
    </table>
	</td>
   </tr>
 </table>
    </td>
  </tr>
</table>
</form>