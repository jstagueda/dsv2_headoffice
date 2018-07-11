<?PHP
	include IN_PATH.DS."scCampaign.php";
?>

<script type="text/javascript">

function dialogmessage(title, message, buttonFunction){
	$( "#dialog-message p" ).html(message);
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
		draggable: false,
        title: title,
        buttons: buttonFunction
    });
    $( "#dialog-message" ).dialog( "open" );
}

$(function(){
	
	$('[name=txtStartDate]').datepicker({
		maxDate	:	new Date($('[name=txtEndDate]').val()),
		onClose	:	function(SelectedDate){
			$('[name=txtEndDate]').datepicker('option', 'minDate', SelectedDate);
			
			var AdvancePoDate = new Date(SelectedDate);
			var NewAdvancePODate = new Date(AdvancePoDate.setDate(AdvancePoDate.getDate() - 1));
			$('[name=txtAdvancePOEndDate]').datepicker('option', 'maxDate', NewAdvancePODate);
			
			//get the number of x days
			var NumberOfDays = $('[name=NumberOfDays]').val();
			
			
			if(NumberOfDays == ""){
				alert("Please configure campaign days in HO Setting.");
				location.href = "index.php?pageid=344";
				return false;
			}
			
			//set new date based on the selected less number of days
			SelectedDate = new Date(SelectedDate);
			var CurrentDate = new Date(SelectedDate);
			var NewDate = new Date(CurrentDate.setDate(CurrentDate.getDate() - NumberOfDays));
			
			//count the number of sundays
			NumberOfSundays = 0;
			
			while(NewDate < SelectedDate){
				
				if(NewDate.getDay() == 0){
					NumberOfSundays++;
				}
				NewDate.setDate(NewDate.getDate() + 1);
				
			}
			
			NumberOfDays = eval(NumberOfDays) + eval(NumberOfSundays);
			
			SelectedDate = new Date(SelectedDate.setDate(SelectedDate.getDate() - NumberOfDays));
			
			if(SelectedDate.getDay() == 0){
				SelectedDate = new Date(SelectedDate.setDate(SelectedDate.getDate() - 1));
			}
			
			var NewPODate = eval(SelectedDate.getMonth() + 1)+'/'+SelectedDate.getDate()+'/'+SelectedDate.getFullYear();
			
			//check if date dropped to sunday the less another 1 days
			$('[name=txtAdvancePOStartDate]').val(NewPODate);			
			$('[name=txtAdvancePOEndDate]').val(NewPODate);
			$('[name=txtAdvancePOEndDate]').datepicker('option', 'minDate', NewPODate);
			
		}
	});
	
	$('[name=txtEndDate]').datepicker({
		minDate	:	new Date($('[name=txtStartDate]').val()),
		onClose	:	function(SelectedDate){
			$('[name=txtStartDate]').datepicker('option', 'maxDate', SelectedDate);
		}
	});
	
	$('[name=txtAdvancePOStartDate]').datepicker({
		maxDate	:	new Date($('[name=txtAdvancePOEndDate]').val()),
		onClose	:	function(SelectedDate){
			$('[name=txtAdvancePOEndDate]').datepicker('option', 'minDate', SelectedDate);
		}
	});
		
	$('[name=txtAdvancePOEndDate]').datepicker({
		minDate	:	new Date($('[name=txtAdvancePOStartDate]').val()),
		onClose	:	function(SelectedDate){
			$('[name=txtAdvancePOStartDate]').datepicker('option', 'maxDate', SelectedDate);
		}
	});

	
});

function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}
function form_validation(x)
{
	lang = 0;
	def = 0;
	count = 0;
	msg = '';
	str = '';
	obj = document.frmCampaignInfo.elements;


	var str1 = document.getElementById("txtStartDate").value;
    var str2 = document.getElementById("txtEndDate").value;
    var str3 = document.getElementById("txtAdvancePOStartDate").value;
    var str4 = document.getElementById("txtAdvancePOEndDate").value;

    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);

    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);

    var dt3  = parseInt(str3.substring(0,2),10);
    var mon3 = parseInt(str3.substring(3,5),10);
    var yr3  = parseInt(str3.substring(6,10),10);

    var dt4  = parseInt(str4.substring(0,2),10);
    var mon4 = parseInt(str4.substring(3,5),10);
    var yr4  = parseInt(str4.substring(6,10),10);

    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);
    var date3 = new Date(yr3, mon3, dt3);
    var date4 = new Date(yr4, mon4, dt4);


	// TEXT BOXES
	if (trim(obj["txtCode"].value) == '') msg += '   * Code<br>';
	if (trim(obj["txtName"].value) == '')msg += '   *  Name<br>';
	if (trim(obj["txtStartDate"].value) == '')msg += '   * Start Date<br>';
	if (trim(obj["txtEndDate"].value) == '')msg += '   * End Date<br>';
	if (obj["txtAdvancePOStartDate"].selectedIndex == '') msg += '   * Advance PO Start Date<br>';
	if (obj["txtAdvancePOEndDate"].selectedIndex == '') msg += '   * Advance PO End Date<br>';
	//if (obj["cboStatus"].selectedIndex == 0) msg += '   * Status \n';
	// if (obj["cboWarehouse"].selectedIndex == 0) msg += '   * Warehouse \n';

	var title = "Campaign";
	var message = "";
	var ButtonFunction = {};
	
	if (msg != '')
	{
		message = 'Please complete the following:<br/><br/>' + msg;	
				
		ButtonFunction['Ok'] = function(){
			$('#dialog-message').dialog('close');
		}
		
		dialogmessage(title, message, ButtonFunction);
		return false;
	}
	else {


				if(date2 < date1)
				{
					message = 'End Date cannot be less than Start Date.';	
				
					ButtonFunction['Ok'] = function(){
						$('#dialog-message').dialog('close');
					}
					
					dialogmessage(title, message, ButtonFunction);
					return false;
				}

	           if(date4 < date3)
               {
					message = "Advance PO Start Date cannot be greater than Advance PO End Date.";	
				
					ButtonFunction['Ok'] = function(){
						$('#dialog-message').dialog('close');
					}
					
					dialogmessage(title, message, ButtonFunction);
					return false;
               }


		if(x==1)
		{
			
			message = 'Are you sure you want to save this transaction?';
				
			ButtonFunction['Yes'] = function(){
				$('[name=frmCampaignInfo]').append("<input type='hidden' value='1' name='btnSave'>");
				$('#dialog-message').dialog('close');
				$('[name=frmCampaignInfo]').submit();
			}
			
			ButtonFunction['No'] = function(){
				$('#dialog-message').dialog('close');
			}
			
			dialogmessage(title, message, ButtonFunction);
			return false;
			
		}
		else if (x == 2)
		{
			message = 'Are you sure you want to update this transaction?';
				
			ButtonFunction['Yes'] = function(){
				$('[name=frmCampaignInfo]').append("<input type='hidden' value='1' name='btnUpdate'>");
				$('#dialog-message').dialog('close');
				$('[name=frmCampaignInfo]').submit();
			}
			
			ButtonFunction['No'] = function(){
				$('#dialog-message').dialog('close');
			}
			
			dialogmessage(title, message, ButtonFunction);
			return false;
		}
	}
}

function form_validation_delete()
{
	message = 'Are you sure you want to delete this record?';
				
	ButtonFunction['Yes'] = function(){
		$('[name=frmCampaignInfo]').append("<input type='hidden' value='1' name='btnDelete'>");
		$('#dialog-message').dialog('close');
		$('[name=frmCampaignInfo]').submit();
	}
	
	ButtonFunction['No'] = function(){
		$('#dialog-message').dialog('close');
	}
	
	dialogmessage(title, message, ButtonFunction);
	return false;

}

function CompareDates()
 {
	var title = "Campaign";
	var message = "";
	var ButtonFunction = {};
	
    if(date2 < date1)
    {
		message = "Birthdate cannot be greater than date hired.";
				
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');
		}
		
		dialogmessage(title, message, ButtonFunction);
		return false;
    }
    else
    {
        return true;
    }
}

</script>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav.php");
		?>

      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Promo and Pricing Management System (PPMS)</span></td>
        </tr>
    </table>
    <br />

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Campaigns</td>
    <td>&nbsp;</td>
  </tr>
</table>
<!-- <?php
	if ((isset($_GET['errmsg2'])))
	{
?>


<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtreds">&nbsp;<b><?php echo $_GET['errmsg2'] ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
	}
?>-->
<br />
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
    <form name="frmCampaign" method="post" action="index.php?pageid=112">
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Action</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
		<table width="100%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  <tr>
		    <td><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
		        <tr>
		          <td width="25%">&nbsp;</td>
		          <td width="5%">&nbsp;</td>
		          <td>&nbsp;</td>
		        </tr>
		        <tr>
					
					<td align="right"><b>Search</b></td>
					<td align="center">:</td>
					<td>
						<input name="txtfldsearch" type="text" class="txtfield" id="txtSearch" size="20" value="<?php echo $search;?>">
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
		          <td class="tabmin2"><span class="txtredbold">&nbsp;List of Campaigns</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
		        <tr>
		          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		              <tr>
		                <td style="border-bottom: 2px solid #FFA3E0; background:#FFDEF0;">
							<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
		                    <tr align="center">
		                      <td width='40%' height='25' class="bdiv_r"><div align="center">&nbsp;<span class="txtpallete">Code</span></div></td>
		                      <td width='60%' height='25'><div align="left"><span class="txtpallete">&nbsp;&nbsp;&nbsp;Name</span></div></td>
								</table>
						</td>
		              </tr>
		              <tr>
		                <td>
							<div style="overflow:auto; max-height: 250px;">
                        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
						<?PHP
						if ($rs_campaign->num_rows)
						{
							while ($row = $rs_campaign->fetch_object())
							{
							   echo "<tr align='center'>
								  		<td width='40%' height='25' class='borderBR' align='center'>&nbsp;
											<span class='txt10'>
												<a href='index.php?pageid=112&campId=$row->ID&svalue=$search' class='txtnavgreenlink' style='color:blue;'>$row->Code</a>
											</span></td>
								  		<td width='60%' height='25' class='padr5 borderBR' align='left'>
											<span class='txt10'>&nbsp;&nbsp;&nbsp;".$row->Name."</span>
										</td>
								</tr>";
							}
							$rs_campaign->close();
						}
						else
						{
							echo "<tr align='center'><td colspan='2' height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
						}
						?>
		                  </table>
                          </div>
	                    </td>
		              </tr>
		          </table></td>
		        </tr>
		      </table>
	</td>
	<td width="1%">&nbsp;</td>
	<td width="60%" valign="top">
     <form name="frmCampaignInfo" method="post" action="includes/pcCampaign.php" >
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Campaign Details</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         <tr>
           <td class="bgF9F8F7"><?php
				if (isset($_GET['msg']))
				{
					$message = strtolower($_GET['msg']);
					$success = strpos("$message","success");
					echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
				}
				else if (isset($_GET['errmsg']))
				{
					$errormessage = strtolower($_GET['errmsg']);
					$error = strpos("$errormessage","error");
					echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
				}
				 ?>
			</td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td align="right" style="padding:5px; font-weight:bold;">Code</td>
                <td width="5%" align="center">:</td>
                <td>
					<input type="text" name="txtCode" maxlength="15" size="30" class="txtfield" value="<?PHP echo $ccode;?>" />
					<input type="hidden" name="txtID" maxlength="15" size="30" class="txtfield" value="<?PHP echo $cid;?>" />
                </td>
            </tr>
            <tr>
                <td align="right" style="padding:5px; font-weight:bold;">Name</td>
                <td width="5%" align="center">:</td>
                <td><input type="text" name="txtName" maxlength="50" size="40" class="txtfield" value="<?PHP echo $cname;?>" ></td>
            </tr>


            <tr>
              <td align="right" style="padding:5px; font-weight:bold;">Start Date</td>
              <td width="5%" align="center">:</td>
              <td>
              	<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $startDate;?>">
                <i>(e.g. MM/DD/YYYY)</i>
				<?php 
					$query = $database->execute("SELECT SettingValue FROM headofficesetting WHERE SettingCode = 'CAMPDAYS'");
					$res = $query->fetch_object();
					$Xdays = $res->SettingValue;
				?>
				<input type='hidden' value="<?=$Xdays?>" name="NumberOfDays">
				
              </td>
            </tr>
			 <tr>
              <td align="right" style="padding:5px; font-weight:bold;">End Date</td>
              <td width="5%" align="center">:</td>
              <td>
              	<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $endDate; ?>">
                <i>(e.g. MM/DD/YYYY)</i>
              </td>
            </tr>
             <tr>
              <td align="right" style="padding:5px; font-weight:bold;">Advance PO Start Date</td>
              <td width="5%" align="center">:</td>
              <td>
              	<input name="txtAdvancePOStartDate" type="text" class="txtfield" id="txtAdvancePOStartDate" size="20" readonly="yes" value="<?php echo $advPOStartDate;?>">
                <i>(e.g. MM/DD/YYYY)</i>
              </td>
            </tr>
             <tr>
              <td align="right" style="padding:5px; font-weight:bold;">Advance PO End Date</td>
              <td width="5%" align="center">:</td>
              <td>
              	<input name="txtAdvancePOEndDate" type="text" class="txtfield" id="txtAdvancePOEndDate" size="20" readonly="yes" value="<?php echo $advPOEndDate;?>">
                <i>(e.g. MM/DD/YYYY)</i>
              </td>
            </tr>
            <!--
            <tr>
              <td height="30" align="right" class="txtpallete">Status :</td>
              <td height="30">&nbsp;</td>
              <td height="30">
                    <select name="cboStatus" style="width:162px" class="txtfield">
                    <option value="0" >[SELECT HERE]</option>
                         <?PHP

                        if ($rs_status->num_rows)
                        {
                            while ($row = $rs_status->fetch_object())
                            {
                            ($statID == $row->ID) ? $sel = "selected" : $sel = "";
                            echo "<option value='$row->ID' $sel>$row->Name</option>";
                            }
                        }

                        ?>


                    </select>
              </td>
          </tr>
          -->
            </table>
        </td>
         </tr>
        <tr>
          <td class="bgF9F8F7">&nbsp;</td>
          </tr>
        <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
       </table>

        <br>
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td align="center">
            <?PHP
            if ($_SESSION['ismain'] == 1)
            {
	            if ($campId > 0)
	            {
					//if (($advPOStartDate != $today) && (($advPOStartDate < $today) && ($endDate < $today)))
					if ((strtotime($advPOStartDate) != strtotime($today)) && (strtotime($endDate) < strtotime($today)))
					{
						echo "<input name='btnUpdate' type='submit' class='btn' value='Update' onClick = 'return form_validation(2);' />";
	                    echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onClick = 'return form_validation_delete();' />";
					}
	            }
	            else
	            {
	                    echo "<input name='btnSave' type='submit' class='btn' value='Save' onClick = 'return form_validation(1);' >";
	            }
            }
            ?>
            <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=112'" />
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
   </form>
    </td>
  </tr>
</table>
<div id="dialog-message" style="display:none;"><p></p></div>