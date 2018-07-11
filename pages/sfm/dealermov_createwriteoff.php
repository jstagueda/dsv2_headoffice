<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<?PHP 
include IN_PATH.DS."scEmployee.php";
?>
<script type="text/javascript">
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}
function form_validation()
{
	lang = 0;
	def = 0;
	count = 0;
	msg = '';
	str = '';
	obj = document.frmDealer.elements;
	
	// TEXT BOXES
	if (trim(obj["txtCodeEmployee"].value) == '') msg += '   * Code \n';
	if (trim(obj["txtlnameEmployee"].value) == '')msg += '   * Last Name \n';
	if (trim(obj["txtfnameEmployee"].value) == '')msg += '   * First Name \n';
	if (trim(obj["txtmnameEmployee"].value) == '')msg += '   * Middle Name \n';
	if (trim(obj["txtBdayEmployee"].value) == '')msg += '   * Birthday \n';
	if (obj["cboEmployeeType"].selectedIndex == 0) msg += '   * Employee Type \n';
	if (obj["cboDepartment"].selectedIndex == 0) msg += '   * Department \n';
	// if (obj["cboWarehouse"].selectedIndex == 0) msg += '   * Warehouse \n';

	if (msg != '')
	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else return true;
}
function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
function openPopUp() 
{
	var objWin;
		popuppage = "pages/datamanagement/datamgt_dealerinfopopup.php?empid=5";
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','700','yes');
		}
		
		return false;  		
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
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Management </span></td>
        </tr>
    </table>
    <br />

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Write-Off Request</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
	<td width="50%" valign="top">
		<table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2" class="bordersolo">
			<tr>
				<td width="55%">
					IBM No. Range From : &nbsp;&nbsp;&nbsp;
					<input name="txtIBMRangeFrom" type="text" class="txtfield" id="txtIBMRangeFrom" size="20">
				</td>
				<td width="45%">
					To : &nbsp;&nbsp;&nbsp;
					<input name="txtIBMRangeTo" type="text" class="txtfield" id="txtIBMRangeTo" size="20">
				</td>
			</tr>
			<tr>
				<td width="55%">
					No PO Range From : &nbsp;&nbsp;&nbsp;
					<select name="pPORangeFrom" class="txtfield" style="width:180">
					<option value="0" >[SELECT HERE]</option>			
					 </select>
				</td>
				<td width="45%">
					To : &nbsp;&nbsp;&nbsp;
					<select name="pPORangeTo" class="txtfield" style="width:180">
					<option value="0" >[SELECT HERE]</option>			
					 </select>&nbsp;
					 <input name="btnGenerate" type="submit" class="btn" value="Generate List">&nbsp;
				</td>
			</tr>
		</table>
 	</td>
 	<td width="55%" valign="top"></td>
 </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="60%" valign="top">
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
                <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                    <tr align="center">
                      <td width="1%"><div align="center"> <input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);" /></div></td>	
                      <td width="15%"><div align="center">&nbsp;<span class="txtredbold">IGS Code</span></div></td>
                      <td width="20%"><div align="center"><span class="txtredbold">IGS Name</span></div></td>
                      <td width="20%"><div align="center"><span class="txtredbold">IBM Name</span></div></td>
                      <td width="15%"><div align="center"><span class="txtredbold">IGS Contact Number</span></div></td>
                      <td width="15%"><div align="center"><span class="txtredbold">Last PO Date</span></div></td>
                      <td width="15%"><div align="center"><span class="txtredbold">Past Due Amount</span></div></td>
                      </tr>
                </table></td>
              </tr>
              <tr>
                <td class="bordergreen_B"><div class="scroll_300">
                   <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
					<tr align='center'>
					  <td width='1%'  class='borderBR' align='left'>&nbsp;<input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);"></td>
					  <td width='15%' class='borderBR' align='left'>&nbsp;<span class='txt10'>923812</span></td>
					  <td width='20%' class='borderBR' align='left'>&nbsp;<a href='index.php?pageid=60&empid=$row->ID&svalue=$search' class='txtnavgreenlink'>Alexander Williams</a></td>
					  <td width='20%' class='borderBR' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=48&empid=$row->ID&svalue=$search' class='txtnavgreenlink'>Joseph Velasco</a></span></td>
					  <td width='15%' class='borderBR' align='left'>&nbsp;<span class='txt10'>0917-2702990</span></td>
					  <td width='15%' class='borderBR' align='left'>&nbsp;<span class='txt10'></span></td>
					  <td width='15%' class='borderBR' align='left'>&nbsp;<span class='txt10'></span></td>
					</tr>
                  </table>
                          </div>
                    </td>
              </tr>
          </table></td>
        </tr>
      </table>
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  <br>
	  <tr>
	    <td align="center">
	    	<input name="btnSubmit" type="submit" class="btn" value="Submit">&nbsp;
	    	<input name="btnCancel" type="submit" class="btn" value="Cancel">
	    </td>
	  </tr>
	</table>
	</td>
	<td width="20%">&nbsp;</td>
	<td width="20%" valign="top">
	</td>
  </tr>
</table>
	</td>
  </tr>
</table>
    </td>
  </tr>
</table>
<br><br>
<!--  -->