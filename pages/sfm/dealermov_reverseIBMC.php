<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<?PHP 
	include IN_PATH.DS."scDealerReverse.php";
	
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
    
    $customerRows = '';
    $cnt = 0;
	if ($rs_customerall->num_rows)
	{
		while ($row = $rs_customerall->fetch_object())
		{
			$cnt ++;
            ($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
			$amount = number_format($row->PastDueAmount, 2, '.', ',');
			$tmptxndate = strtotime($row->LastPODate);
			$txndate = $tmptxndate != '' ? date("m/d/Y", $tmptxndate) : '';
		   	$customerRows .= "<tr align='center' class='$alt'><td width='5%' height='20' class='borderBR' align='center'><input type='checkbox' name='chkSelect[]' onclick='UnCheck($row->ID);' id='chkSelect' value='$row->ID'></td>
					  <td width='15%' height='20' class='borderBR padl5' align='left'>$row->Code</td>
					  <td width='20%' height='20' class='borderBR padl5' align='left'><a href='index.php?pageid=47' class='txtnavgreenlink'>$row->Name</a></td>
					  <td width='10%' height='20' class='borderBR padl5' align='left'>$row->GSUCode</td>
					  <td width='20%' height='20' class='borderBR padl5' align='left'>$row->IBMCode</td>
					  <td width='10%' height='20' class='borderBR padl5' align='left'>$row->ContactNo</td>
					  <td width='10%' height='20' class='borderBR' align='center'>$txndate</td>
					  <td width='10%' height='20' class='borderBR padr5' align='right'>$amount</td></tr>";
		}
		$rs_customerall->close();
	}
	else
	{
		$customerRows = "<tr align='center'><td height='20' colspan='8' class='borderBR ><span class='txt10 txtreds'>No record(s) to display. </span></td></tr>";
	}
?>

<script type="text/javascript">
function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(obj) 
{
	obj.value = obj.value + "0";
	var objWin;
		popuppage = "pages/dealer_movements/dealermov_reverseibmcprint.php?id=" + obj.value;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','500','yes');
		}
		
		return false;  		
}

function checkAll(bin) 
{
	var elms = document.frmTerminateDealer.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkSelect[]') 
	  {
		  elms[i].checked = bin;
		  document.frmTerminateDealer.hdnID.value += elms[i].value + ",";		  
	  }		
}

function UnCheck(frm)
{

    var chkAll = document.frmTerminateDealer.chkAll;
    
    chkAll.checked = false;

    document.frmTerminateDealer.hdnID.value += frm + ",";
}

function checker()
{
	var ml = document.frmTerminateDealer;
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
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav_dealer.php");
		?>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Movement</span></td>
        </tr>
    	</table>
    	<br />
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  		<tr>
    		<td valign="top">
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
    				<td class="txtgreenbold13">Reversion of IBMC Appointment</td>
    				<td>&nbsp;</td>
  				</tr>
				</table>
				<br />
 				<form name="frmTerminateDealer" method="post" action="index.php?pageid=78" >
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td valign="top">
						<table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2" class="bordersolo">
						<tr>
							<td height="10" colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td width="10%" height="20" align="right" class="padr5">IBM No. Range From :</td>
							<td width="20%" height="20" align="left" class="padl5"><input name="txtIBMRangeFrom" type="text" class="txtfield" id="txtIBMRangeFrom" size="20"></td>
							<td width="10%" height="20" align="right" class="padr5">To :</td>
							<td width="60%" height="20" align="left" class="padl5"><input name="txtIBMRangeTo" type="text" class="txtfield" id="txtIBMRangeTo" size="20"></td>
						</tr>
						<tr>
							<td height="20" align="right" class="padr5">No. PO Range From :</td>
							<td height="20" align="left" class="padl5">
								<input name="txtDateFrom" type="text" class="txtfield" id="txtDateFrom" size="20" readonly="yes" value="">
								<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
								<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
							</td>
							<td height="20" align="right" class="padr5">To :</td>
							<td height="20" align="left" class="padl5">
								<input name="txtDateTo" type="text" class="txtfield" id="txtDateTo" size="20" readonly="yes" value="">
								<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
								<div id="" style="background-color : white; position:absolute;visibility:hidden;"></div>
							</td>
						</tr>
						<tr>
							<td height="10" colspan="4">&nbsp;</td>
						</tr>
						<tr>
							<td height="20" colspan="2">&nbsp;</td>
							<td height="20" align="center"><input name="btnGenerate" type="submit" class="btn" value="Generate List"></td>
							<td height="20">&nbsp;</td>
						</tr>
						</table>
						<br>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td align="center"></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<br>
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="100%" valign="top">
      					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        				<tr>
          					<td class="tabmin">&nbsp;</td>
          					<td class="tabmin2"><span class="txtredbold">Dealer List</span></td>
          					<td class="tabmin3">&nbsp;</td>
    					</tr>
      					</table>
      					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        				<tr>
          					<td valign="top" class="bgF9F8F7">
          						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              					<tr>
                					<td class="tab">
                						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                    					<tr align="center">
                      						<td width="5%" height="20" align="center" class="bdiv_r"><input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);" /></td>	
                      						<td width="15%" height="20" align="left" class="bdiv_r padl5"><span class="txtredbold">Code</span></td>
                      						<td width="20%" height="20" align="left" class="bdiv_r padl5"><span class="txtredbold">IGS Name</span></td>
                      						<td width="10%" height="20" align="left" class="bdiv_r padl5"><span class="txtredbold">GSU Code</span></td>
                      						<td width="20%" height="20" align="left" class="bdiv_r padl5"><span class="txtredbold">IBM Name</span></td>
                      						<td width="10%" height="20" align="left" class="bdiv_r padl5"><span class="txtredbold">IGS Contact Number</span></td>
                      						<td width="10%" height="20" align="center" class="bdiv_r"><span class="txtredbold">Last PO Date</span></td>
                      						<td width="10%" height="20" align="right" class="padr5"><span class="txtredbold">Past Due Amount</span></td>
                  						</tr>
                						</table>
            						</td>
          						</tr>
              					<tr>
                					<td class="bordergreen_B"><div class="scroll_300">
                   						<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
                   	  						<input type="hidden" name="hdnID" value="" />
											<?php echo $customerRows; ?>
                  						</table>
                          			</div></td>
          						</tr>
          						</table>
      						</td>
    					</tr>
      					</table>
      					<br>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  					<tr>
	    					<td align="center">
	    						<input name="btnPrint" type="submit" class="btn" value="Print" onclick='openPopUp(hdnID)'>
	    						<input name="btnCancel" type="submit" class="btn" value="Cancel">
	    					</td>
  						</tr>
						</table>
						<br>
					</td>
				</tr>
				</table>
				</form>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtDateFrom",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtDateTo",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divEndDate",
		button         :    "anchorEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>