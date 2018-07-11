<?php
	include IN_PATH.DS."scBrochures.php";

	$brochureid = '';
	$drpPageType = '';

	if(isset($_GET['ID']))
	{
		$brochureid = "&ID=".$_GET['ID'];
	}
?>

<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>

<style type="text/css">
	.trheader td{padding: 5px; background:#FFDEF0;}
	.trlist td{padding: 5px;}
</style>

<script type="text/javascript">
function DatePicker(field){
    $(field).datepicker({
        changeMonth :   true,
        changeYear  :   true
    });
}

$(document).ready(function() {

    DatePicker('[name=txtStartDate]');
    DatePicker('[name=txtEndDate]');

    $('#addCampaign')
    .dialog({
    autoOpen: false,
    height: 205,
    width: 380,
    resizable: false,
    modal: true,
    buttons: {
       'Add': function()
       {
       		var cnt = 0;
       		var elms = document.frmAddCampaign.elements;

			for(var i = 0;i < elms.length;i++)
			{
				if(elms[i].name=='chkInclude[]' && elms[i].checked == true)
				{
					cnt++;
				}
			}

			if (cnt == 0)
			{
				alert("There are no campaign(s) to be addedd.");
				return false;
			}
			else
			{
				if (confirm('Are you sure you want to add campaign(s)?') == true)
	       		{
	       			var list = '';
	       			var collid = eval('document.frmAddCampaign.hCollID');
	       			var elms = document.frmAddCampaign.elements;

					for(var i = 0;i < elms.length;i++)
					{
						if(elms[i].name=='chkInclude[]' && elms[i].checked == true)
					  	{
					  		list = list + elms[i].value + ',';
					  	}
					}

					var cnt = eval('document.frmBrochure.hCampList');
					cnt.value = list;

	    			$.ajax({
		            	type: 'POST',
		            	url: 'includes/jxAddCampaign.php?campaignID=' + list,
		            	success: function(innerText)
		            	{
		            		var camp = innerText.split("_");
		            		var cnt = eval('document.frmAddCampaign.hCampCount');

		          	  		$('#divCampaign').html(camp[0]);
		          	  		cnt.value = camp[1];
		            	},
		            	dataType: 'text'
		          	});
		          	$(this).dialog('close');
	    		}
	    		else
	    		{
	    			$(this).dialog('close');
	    		}
			}
    	}
    },
    open: function()
    {
    }
 });
});

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

function form_validation()
{
	msg = '';
	str = '';
	obj = document.frmBrochure.elements;

	// TEXT BOXES
	if (trim(obj["txtCode"].value) == '')msg += '   * Code \n';
	if (trim(obj["txtName"].value) == '')msg += '   * Name \n';
	if (trim(obj["txtNoPage"].value) == '')msg += '   * No. of Pages \n';
	if (trim(obj["txtHeight"].value) == '')msg += '   * Height \n';
	if (trim(obj["txtWidth"].value) == '')msg += '   * Width \n';
	if (trim(obj["txtStartDate"].value) == '')msg += '   * Start Date \n';
	if (trim(obj["txtEndDate"].value) == '')msg += '   * End Date \n';

	//CAMPAIGN
	cnt = eval('document.frmAddCampaign.hCampCount');
	//if (cnt.value == 0)msg += '   * Campaign(s) \n';

	if(getDateObject(obj["txtStartDate"].value, "/") > getDateObject(obj["txtEndDate"].value, "/"))
	{
		msg += '   * Start Date must be less than End Date \n';
	}

	if (msg != '')
	{
	  alert ('Please complete the following: \n\n' + msg);
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

function isDate(fld)
{
	   var value = fld.value;
	   var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
	   var matchArray = value.match(datePat); // is the format ok?
	   if (matchArray == null) {
	             alert("Please enter date as either mm/dd/yyyy or mm-dd-yyyy.");
	             return false;
	   }
	   return true;
}

function toggleDivTag(id)
{
	var divID = document.getElementById(id);
	var div1 = document.getElementById('div1');
	var div2 = document.getElementById('div2');
	var div3 = document.getElementById('div3');

	if(id == 'div1')
	{
		divID.style.display = 'block';
		div2.style.display = 'none';
		div3.style.display = 'none';
	}

	if(id == 'div2')
	{
		divID.style.display = 'block';
		div1.style.display = 'none';
		div3.style.display = 'none';
	}

	if(id == 'div3')
	{
		divID.style.display = 'block';
		div1.style.display = 'none';
		div2.style.display = 'none';
	}

	if(id != 'div3')
	{
		var ml = document.frmBrochure;
		var table = document.getElementById('dynamicEntTable');
		var nopage = document.frmBrochure.txtNoPage.value;
		var tcnt = document.frmBrochure.hTableCount.value;
		var rowCount = table.rows.length;

		document.getElementById('hdnCntr').value = 1;
		var cnt = tcnt;

		//dynamic combo box
		for (var x = cnt; x < nopage; x++)
		{
			var cid = eval(x) + 1;
			var row = table.insertRow(x);
			//item description
			var cell0 = row.insertCell(0);
			cell0.setAttribute("align", "center");
			cell0.setAttribute("class", "borderBR padl5");
			cell0.setAttribute("width", "30%");
			var element0 = document.createElement("input");
			element0.type = "text";
			element0.setAttribute("class", "txtfield");
			element0.setAttribute("style", "width: auto;");
			element0.setAttribute("readonly", "yes");
			element0.setAttribute("id", "txtPageNum" + cid);
			element0.setAttribute("name", "txtPageNum" + cid);
			element0.setAttribute("value", "Page " + cid);
			cell0.appendChild(element0);

			cnt++;

			var cell1 = row.insertCell(1);
			cell1.setAttribute("align", "center");
			cell1.setAttribute("class", "borderBR");
			cell1.setAttribute("width", "40%");

			var element1 = document.createElement("select");
			var pmgcode1 = ml.hdnLayoutName.value;
			var pmgid1 = ml.hdnLayoutType.value;
			var criteria1 = '[' + pmgcode1 + ']';
			var critid1 = '[' + pmgid1 + ']';
			var tmp_criteria1 = new Array();
			var tmp_critid1 = new Array();
			tmp_criteria1 = criteria1.split(',');
			tmp_critid1 = critid1.split(',');

			//dynamic combo box
			for (var y = 0; y < tmp_criteria1.length; y++)
			{
				var val1 = eval("tmp_criteria1[" + y + "]").replace('[', '').replace(']', '').replace(/'/g, '');
				var id1 = eval("tmp_critid1[" + y + "]").replace('[', '').replace(']', '').replace(/'/g, '');
				eval("var option" + y + " = document.createElement('option');");
				eval("var text" + y + " = document.createTextNode('" + val1 + "');");
				eval("option" + y).appendChild(eval("text" + y));
				eval("option" + y).value = eval(id1);
				element1.appendChild(eval("option" + y));
			}

			element1.setAttribute("class", "txtfield");
			element1.setAttribute("id", "pPageLayout" + cid);
			element1.setAttribute("name", "pPageLayout" + cid);
			element1.setAttribute("style", "width: 120px");
			element1.setAttribute("onchange", "checkSelection(" + cid + ");");
			cell1.appendChild(element1);

			var cell = row.insertCell(2);
			cell.setAttribute("align", "center");
			cell.setAttribute("class", "borderBR");
			cell.setAttribute("width", "40%");

			var element = document.createElement("select");
			var pmgcode = ml.hdnPageName.value;
			var pmgid = ml.hdnPageType.value;
			var criteria = '[' + pmgcode + ']';
			var critid = '[' + pmgid + ']';
			var tmp_criteria = new Array();
			var tmp_critid = new Array();
			tmp_criteria = criteria.split(',');
			tmp_critid = critid.split(',');

			//dynamic combo box
			for (var i = 0; i < tmp_criteria.length; i++)
			{
				var val = eval("tmp_criteria[" + i + "]").replace('[', '').replace(']', '').replace(/'/g, '');
				var id = eval("tmp_critid[" + i + "]").replace('[', '').replace(']', '').replace(/'/g, '');
				eval("var option" + i + " = document.createElement('option');");
				eval("var text" + i + " = document.createTextNode('" + val + "');");
				eval("option" + i).appendChild(eval("text" + i));
				eval("option" + i).value = eval(id);
				element.appendChild(eval("option" + i));
			}

			element.setAttribute("class", "txtfield");
			element.setAttribute("id", "pPageType" + cid);
			element.setAttribute("name", "pPageType" + cid);
			element.setAttribute("style", "width: 120px");
			cell.appendChild(element);
		}
	}
}

function MM_jumpMenu(targ,selObj,restore)
{
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+  "#MMHead" +"'");

	if (restore)
		selObj.selectedIndex = 0;
}

function validateAddCamp()
{
	$('#addCampaign').dialog('open');
}

function checkAll(bin)
{
	var elms = document.frmAddCampaign.elements;

	for(var i = 0;i < elms.length;i++)
	{
		if(elms[i].name=='chkInclude[]')
	  	{
	  		elms[i].checked = bin;
	  	}
	}
}

function validateCopy()
{
	if (confirm('Are you sure you want to create a version of this collateral?') == false)
	{
		return false;
	}
	else
	{
		code = prompt('Input new Code: ','');
		if (code == null)
		{
			alert('Version code required.')
			return false;
		}
		else
		{
			hcode = eval('document.frmBrochure.hNewCode');
			hcode.value = code;
			return true;
		}
	}
}

function getDateObject(dateString,dateSeperator)
{
	//This function return a date object after accepting
	//a date string ans dateseparator as arguments
	var curValue=dateString;
	var sepChar=dateSeperator;
	var curPos=0;
	var cDate,cMonth,cYear;

	//extract day portion
	curPos=dateString.indexOf(sepChar);
	cMonth=dateString.substring(0,curPos);

	//extract month portion
	endPos=dateString.indexOf(sepChar,curPos+1);
	cDate=dateString.substring(curPos+1,endPos);

	//extract year portion
	curPos=endPos;
	endPos=curPos+5;
	cYear=curValue.substring(curPos+1,endPos);

	//Create Date Object
	dtObject=new Date(cYear,cMonth,cDate);
	return dtObject;
}

function checkSelection(index)
{
	var nindex = eval(index) + 1;
	var pplayout = eval('document.frmBrochure.pPageLayout' + index);
	var nplayout = eval('document.frmBrochure.pPageLayout' + nindex);

	if (pplayout.value == 1)
	{
		nplayout.disabled = false;
	}
	else
	{
		nplayout.disabled = true;
	}
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?php
			include("nav.php");
		?>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Promo and Pricing Management System (PPMS)</span></td>
        </tr>
    	</table>
    	<br />
   		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  		<tr>
    		<td valign="top">
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
    				<td class="txtgreenbold13">Brochure </td>
    				<td>&nbsp;</td>
  				</tr>
   				<tr>
    				<td height="10"> </td>
    				<td>&nbsp;</td>
  				</tr>
				</table>
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="33%" valign="top">
                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin">&nbsp;</td>
								<td class="tabmin2"><span class="txtredbold">Action</span></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
		      			</table>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
		  				<tr>
		    				<td>
            					<form name="frmSearchProdType" method="post" action="index.php?pageid=113">
                    				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    				<tr>
                      					<td width="20%"></td>
                      					<td width="5%">&nbsp;</td>
                      					<td>&nbsp;</td>
                    				</tr>
                    				<tr>
                      					<td align="right"><b>Search</b></td>
										<td align="center">:</td>
										<td>
                              				<input name="searchTxtFld" type="text" class="txtfield" id="txtSearch" size="20">
                              				<input name="btnSearch" type="submit" class="btn" value="Search" />
                          				</td>
                					</tr>
                    				<tr>
                      					<td>&nbsp;</td>
                      					<td align="right">&nbsp;</td>
                      					<td align="right">&nbsp;</td>
                    				</tr>
                					</table>
                				</form>
            				</td>
		  				</tr>
						</table>
		      			<br>
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        		<tr>
		          			<td class="tabmin">&nbsp;</td>
		          			<td class="tabmin2"><span class="txtredbold">List of Brochures</span></td>
		          			<td class="tabmin3">&nbsp;</td>
		        		</tr>
		      			</table>
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl0">
		        		<tr>
		          			<td valign="top" class="bgF9F8F7">
                      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                          		<tr>
                            		<td class="bordergreen_T" style="border-bottom: 2px solid #FFA3E0;">
                                		<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
                                    	<tr align="center" class="trheader">
                                      		<td width="29%"><div align="left">&nbsp;<span class="padl5 txtpallete">Session Code</span></div></td>
                                      		<td width="50%"><div align="left"><span class="txtpallete">&nbsp;&nbsp;Name</span></div></td>
                                      		<td width="20%"><div align="center"><span class="txtpallete">Status</span></div></td>
                                      	</tr>
                                		</table>
                        			</td>
                          		</tr>
                          		<tr>
                            		<td class="bordergreen_B" style="border-bottom:none;">
										<div style="overflow:auto; max-height:250px;">
                                    	<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
                                        
                                        	<?php
	                                            if($rs_brochure->num_rows)
	                                            {
	                                                $rowalt=0;
	                                                while($row = $rs_brochure->fetch_object())
	                                                {
	                                                    ($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
	                                                    echo "<tr align='center' class='$class trlist'>
	                                                        	<td class='borderBR' width='29%' align='left'>&nbsp;<span class='txt10'>\t\t\t\t\t\t$row->Code</span></td>
	                                                        	<td class='borderBR' width='50%' align='left'>&nbsp;&nbsp;<span class='txt10'><a href='index.php?pageid=113&ID=$row->ID&searchedTxt=$brochureSearch' class='txtnavgreenlink'>\t\t\t\t\t\t$row->Name</a></td>
	                                                        	<td class='borderBR' width='20%' align='left'>&nbsp;<span class='txt10'>\t\t\t\t\t\t$row->Status</span></td></tr>";
	                                                       $rowalt += 1;
	                                                }
	                                                $rs_brochure->close();

	                                            }
	                                            else
	                                            {
	                                                echo "<tr align='center' class='trlist'><td colspan='3' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
	                                            }
                                            ?>
                                    	</table>
                                	</div></td>
                      			</tr>
                      			</table>
              				</td>
	        			</tr>
		      			</table>
					</td>
					<td width="2%">&nbsp;</td>
      				<td width="60%" valign="top">
       					<form name="frmBrochure" method="post" action="index.php?pageid=113<?php if(isset($_GET['ID'])) { echo '&ID='.$_GET['ID']; } else { echo '';}; ?>" >
       					<input type="hidden" name="hNewCode" value="">
       					<input type="hidden" name="hCampList" value="">
       					<input type="hidden" name="hTableCount" value="<?php echo $tablecount; ?>">
        				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         				<tr>
           					<td class="tabmin">&nbsp;</td>
           					<td class="tabmin2"><span class="txtredbold">Brochure Details</span>&nbsp;</td>
           					<td class="tabmin3">&nbsp;</td>
         				</tr>
       					</table>
       					<div class="" id="div1" style="display: block;">
       					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl1">
        				<tr>
           					<td class="bgF9F8F7">
           						<?php
									if (isset($_GET['msg']))
									{
										$message = strtolower($_GET['msg']);
										$success = strpos("$message","success");
										echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
						    		}
						    		else if(isset($_GET['errmsg']))
						    		{
						    			$errormessage = strtolower($_GET['errmsg']);
										$error = strpos("$errormessage","error");
										echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
						    		}
								?>
            					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
              					<tr>
                					<td width="30%" height="20">&nbsp;</td>
									<td width="5%" height="20">&nbsp;</td>
                					<td width="70%" height="20"></td>
              					</tr>
              					<tr>
                					<td height="20" align="right" class="txtpallete">Session Code</td>
									<td align="center">:</td>
                					<td height="20">&nbsp;&nbsp;<input name="txtCode" type="text" class="txtfield" size="50" maxlength="50" value="<?php echo $bcode; ?>"/></td>
              					</tr>
              					<tr>
                					<td height="20" align="right" class="txtpallete">Name</td>
									<td align="center">:</td>
                					<td height="20">&nbsp;&nbsp;<input name="txtName" type="text" class="txtfield" size="50" maxlength="100" value="<?php echo $bname; ?>"/></td>
              					</tr>
              					<tr>
                					<td height="20" align="right" class="txtpallete">Brochure Type</td>
									<td align="center">:</td>
                					<td height="20">&nbsp;
                						<select name="pColType" class="txtfield" style="width:160px">
											<?php
								 				echo "<option value=\"0\" >[SELECT HERE]</option>";
                            					if ($rs_collateral->num_rows)
                            					{
                                					while ($row = $rs_collateral->fetch_object())
                                					{
                                						($collateralid == $row->ID) ? $sel = "selected" : $sel = "";
                                						echo "<option value='$row->ID' $sel>$row->Name</option>";
                                					}
                                					$rs_collateral->close();
                            					}
                        					?>
                						</select>
             						</td>
          						</tr>
             					<tr>
              						<td height="20" align="right" class="txtpallete">Start Date</td>
									<td align="center">:</td>
              						<td height="20">&nbsp;
              							<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" value="<?php echo $startdate; ?>" size="30" readonly="yes">
                                                                <i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
            					</tr>
             					<tr>
              						<td height="20" align="right" class="txtpallete">End Date</td>
									<td align="center">:</td>
              						<td height="20">&nbsp;
              							<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" value="<?php echo $enddate; ?>" size="30" readonly="yes">
                                                                <i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
            					</tr>
             					<tr>
                					<td height="20" align="right" class="txtpallete">No. of Pages</td>
									<td align="center">:</td>
                					<td height="20">&nbsp;&nbsp;<input name="txtNoPage" type="text" class="txtfield" size="50" maxlength="50" value="<?php echo $nopage; ?>"/></td>
               					</tr>
               					<tr>
                					<td height="20" align="right" class="txtpallete">Height</td>
									<td align="center">:</td>
                					<td height="20">&nbsp;&nbsp;<input name="txtHeight" type="text" class="txtfield" size="50" maxlength="50" value="<?php echo $height; ?>"/></td>
                				</tr>
                 				<tr>
                					<td height="20" align="right" class="txtpallete">Width</td>
									<td align="center">:</td>
                					<td height="20">&nbsp;&nbsp;<input name="txtWidth" type="text" class="txtfield" size="50" maxlength="50" value="<?php echo $width; ?>"/></td>
                				</tr>
                				<?php if(isset($_GET['ID']))
                				{
            					?>
               					<tr>
                					<td height="20" align="right" class="txtpallete">Status</td>
									<td align="center">:</td>
                					<td height="20">&nbsp;
                						<select id="cboStatus" name="cboStatus" class="txtfield" style="width:160px">
                							<?php
                								$sel = "";
					                			if($rs_collstatus->num_rows)
												{
													while($row = $rs_collstatus->fetch_object())
													{
														if ($statid == $row->ID)
														{
															$sel = "selected";
														}
														else
														{
															$sel = "";
														}
														echo "<option value='$row->ID' $sel>$row->Name</option>";
													}
													$rs_collstatus->close();
												}
											?>
                						</select>
         							</td>
          						</tr>
          						<?php
                				}
                				?>
              					<tr>
              						<td height="20" align="right" class="txtpallete">Campaign</td>
									<td align="center">:</td>
                					<td height="20">&nbsp;&nbsp;<input name='btnAddCamp' id='btnAddCamp' type='button' class='btn' value='Add' onclick='return validateAddCamp();'></td>
          						</tr>
          						<?php
          							if ($campaigncount != 0)
          							{
								?>
								<tr>
              						<td height="20" align="right" class="txtpallete">&nbsp;</td>
									<td align="center">:</td>
                					<td height="20">
            							<table border="0" width="100%" cellspacing="0" cellpadding="0">
                						<tr>
                							<td align="left" height="20" class="padl5"><strong>List of Linked Campaign(s)</strong></td>
            							</tr>
                						</table>
                						<table border="0" width="100%" cellspacing="0" cellpadding="0">
                						<?php
                							if($rs_campaignlist->num_rows)
											{
												while($row = $rs_campaignlist->fetch_object())
												{
		                							echo"
		                								<tr>
		                									<td align='left' height='20' class='padl5'>" . $row->CampaignCode . " - " . $row->Campaign  . "</td>
		            									</tr>
		            								";
	            								}
												$rs_campaignlist->close();
											}
            							?>
                						</table>
            						</td>
          						</tr>
								<?php } ?>
          						<tr>
              						<td height="20" align="right" class="txt10">&nbsp;</td>
									<td align="center"></td>
                					<td height="20">
                						<div id="divCampaign">
                							<table border="0" width="100%" cellspacing="0" cellpadding="0">
	                						<tr>
	                							<td align="left" height="20" class="txtpallete padl5"><strong>List of Added Campaign(s)</strong></td>
	            							</tr>
	                						</table>
	                						<table border="0" width="100%" cellspacing="0" cellpadding="0">
	                						<tr>
	                							<td align="left" height="20" class="txtredsbold padl5">[Add Campaign]</td>
	            							</tr>
	                						</table>
	                					</div>
            						</td>
          						</tr>
            					</table>
    						</td>
     					</tr>
        				<tr>
           					<td class="bgF9F8F7">&nbsp;</td>
         				</tr>
         				<tr>
            				<td align="right" class="bgF9F8F7">
            					<input type="hidden" id="hdnPageType" name="hdnPageType" value="<?php echo $pagetypeid; ?>">
			            	   	<input type="hidden" id="hdnPageName" name="hdnPageName" value="<?php echo $pagetypename; ?>">
			            	   	<input type="hidden" id="hdnLayoutType" name="hdnLayoutType" value="<?php echo $layouttypeid; ?>">
			            	   	<input type="hidden" id="hdnLayoutName" name="hdnLayoutName" value="<?php echo $layouttypename; ?>">
			            	   	<input type="hidden" id="hdnKeySpread" name="hdnKeySpread" value="<?php echo $keyspreadid; ?>">
			            	   	<input type="hidden" id="hdnKeyName" name="hdnKeyName" value="<?php echo $keyspread; ?>">
        	    				<input type="hidden" id="hdnCntr" name="hdnCntr" value="0">
                   				<a onclick="toggleDivTag('div2')" href=# class="txtblueboldlink">Next</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          					</td>
      					</tr>
          				<tr>
            				<td align="center" class="bgF9F8F7" height="25"></td>
          				</tr>
       					</table>
       					</div>
        				<div class="" id="div2" style="display: none;">
        				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        		<tr>
		          			<td valign="top" class="bgF9F8F7">
                      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                          		<tr>
                            		<td class="tab bordergreen_T">
                                		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                                    	<tr align="center">
                                      		<td width="30%" class="bdiv_r"><div align="center"><span class="txtpallete">Page Number</span></div></td>
                                      		<td width="40%" class="bdiv_r"><div align="center"><span class="txtpallete">Page Layout</span></div></td>
                                      		<td width="40%" class=""><div align="center"><span class="txtpallete">Key Spread</span></div></td>
                                      	</tr>
                                      	</table>
                                  	</td>
                              	</tr>
                          		<tr>
                            		<td class="tab bordergreen_T">
                                    	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF" id="dynamicEntTable">
                                    	<?php
                                    		if(isset($_GET['ID']))
                                    		{
	                                    		if($rs_brochurepagedetails->num_rows)
												{
													$cnt = 0;
													$ppid = 0;
													while($row = $rs_brochurepagedetails->fetch_object())
													{
														$dis = "";
														$cnt += 1;
														$pagetype_update = $sp->spSelectPageType($database, "", 0);
														$layouttype_update = $sp->spSelectLayoutType($database);
														$pagetypeID = $row->PageTypeID;
														$layouttypeID = $row->LayoutTypeID;
														if ($cnt == 1)
														{
															$ppid = $layouttypeID;
														}
										?>
										<tr>
											<td width="30%" class="borderBR padl5"><div align="center"><input type="text" class="txtfield" style="width:auto;" id="txtPageNum<?php echo $cnt; ?>" name="txtPageNum<?php echo $cnt; ?>" value="Page <?php echo $cnt; ?>"></div></td>
											<td width="40%" class="borderBR"><div align="center">
												<?php
													if ($cnt != 1)
													{
														if ($ppid == 2)
														{
															$dis = 'disabled=true';
														}
														else
														{
															$dis = '';
														}
													}
													else
													{
														$dis = '';
													}
												?>
												<select class="txtfield" id="pPageLayout<?php echo $cnt; ?>" name="pPageLayout<?php echo $cnt; ?>" style="width: 120px;" onchange="checkSelection(<?php echo $cnt; ?>);" <?php echo $dis; ?>>
													<?php
			                                    		if($layouttype_update->num_rows)
														{
															while($row = $layouttype_update->fetch_object())
															{
																if ($layouttypeID == $row->ID)
																{
																	$sel1 = "selected";
																}
																else
																{
																	$sel1 = "";
																}

																echo "<option value='$row->ID' $sel1>$row->Name</option>'";
															}
															$layouttype_update->close();
														}
													?>
												</select>
											</div></td>
											<td width="40%" class="borderBR"><div align="center">
												<select class="txtfield" id="pPageType<?php echo $cnt; ?>" name="pPageType<?php echo $cnt; ?>" style="width: 120px;">
													<?php
			                                    		if($pagetype_update->num_rows)
														{
															while($row = $pagetype_update->fetch_object())
															{
																if ($pagetypeID == $row->ID)
																{
																	$sel = "selected";
																}
																else
																{
																	$sel = "";
																}
																echo "<option value='$row->ID' $sel>$row->Name</option>'";
															}
															$pagetype_update->close();
														}
													?>
												</select>
											</div></td>
										</tr>
										<?php
													$ppid = $layouttypeID;
													}
													$rs_brochurepagedetails->close();
												}
	                                		}
                                    	?>
                                    	</table>
                            		</td>
                      			</tr>
                          		<tr>
					            	<td height="10">&nbsp;</td>
					          	</tr>
					          	<!--
                             	<tr>
					            	<td align="right" class="bgF9F8F7"><a onclick="toggleDivTag('div3')" href=# class="txtblueboldlink">Next</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					          	</tr>
					           	<tr>
					            	<td height="10">&nbsp;</td>
					          	</tr>
					          	-->
                      			</table>
                  			</td>
              			</tr>
              			</table>
        				</div>
        				<div class="" id="div3" style="display: none;">
        				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" id="tbl3">
						<tr>
    						<td valign="top">
								<table width="100%" border="0" align="right" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
								<tr>
									<td valign="top" class="bgF9F8F7">
										<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
										<tr>
											<td>
												<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
												<tr>
					           	 					<td height="5"></td>
					          					</tr>
												<tr>
								   	 				<td height="20" valign="top"><div align="left" class="padl5"><strong>Call-outs : </strong></div></td>
									    			<td height="20"><textarea name="txtCallouts" id="txtCallouts" cols="50" rows="5" class="txtfieldnh"><?php echo $callouts; ?></textarea></td>
								    			</tr>
								    			<tr>
								   	 				<td height="20" valign="top"><div align="left" class="padl5"><strong>Offer Violators :</strong></div></td>
									    			<td height="20"><textarea name="txtOfferViol" id="txtOfferViol" cols="50" rows="5" class="txtfieldnh"><?php echo $offviol; ?></textarea></td>
								    			</tr>
								   				<tr>
								   	 				<td height="20" ><div align="left" class="padl5"><strong>Heroed Shade/Colors :</strong></div></td>
									    			<td height="20">
									    				<select name="cboHS" class="txtfield" id="cboHS" style="width: 230px;">
															<option value="1">[Select Here]</option>
														</select>
														&nbsp;&nbsp;<input name="btnAdd1" id="btnAdd1" type="submit" class="btn" value="Add">
													</td>
							    				</tr>
								    			<tr>
								   	 				<td height="20" ><div align="left" class="padl5"><strong>Item worn by model :</strong></div></td>
									    			<td height="20">
									    				<select name="cboItemWorn" class="txtfield" id="cboItemWorn" style="width: 230px;">
															<option value="1">[Select Here]</option>
														</select>
														&nbsp;&nbsp;<input name="btnAdd2" id="btnAdd2" type="submit" class="btn" value="Add">
									    			</td>
								    			</tr>
								    			<tr>
					           	 					<td height="15"></td>
					          					</tr>
								    			</table>
							    			</td>
										</tr>
										</table>
									</td>
								</tr>
								</table>
							</td>
						</tr>
						</table>
 						</div>
         				<br>
        				<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
          				<tr>
            				<td align="center">
            					<?php
            					if ($_SESSION['ismain'] == 1)
            					{
	                    			if(isset($_GET['ID']) && $refid == 0)
	                    			{
	                    				echo "<input name='btnCopy' type='submit' class='btn' value='Copy' onClick='return validateCopy();' />";
	                    			}
	                    			if ((isset($_GET['ID']) && $statid == 25) || !isset($_GET['ID']))
	                    			{
                    			?>
            						<input name="btnSave" type="submit" class="btn" value="<?php if(isset($_GET['ID'])){ echo "Update"; } else { echo "Save"; }?>" onclick="return form_validation();" />
            					<?php
            						}
        						}
        						?>
								<input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=113'" />
							</td>
  						</tr>
        				</table>
       					</form>
   					</td>
				</tr>
				</table>
			</td>
		</tr>
   		<tr><td height="20"></td></tr>
 		</table>
	</td>
</tr>
</table>

<div id="addCampaign" title="Add Campaign">
<form name="frmAddCampaign" method="post" action="index.php?pageid=113">
<input type="hidden" name="hCollID" value="<?php echo $bID; ?>">
<input type="hidden" name="hCampCount" value="<?php echo $campCount; ?>">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tab bordergreen_T">
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
		<tr align="center">
			<td width="10%" class='bdiv_r'><div align="center"><input name="chkAll" type="checkbox" id="chkAll" value="1" class="inputOptChk" onclick="checkAll(this.checked);" /></div></td>
			<td width="40%" class='bdiv_r'><div align="left" class="padl5"><span class="txtredbold">Code</span></div></td>
			<td width="50%"><div align="left" class="padl5"><span class="txtredbold">Name</span></div></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="bordergreen_B"><div class="scroll_300">
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
			<?php
    			if($rs_campaign->num_rows)
    			{
        			$rowalt = 0;
	        		while($row = $rs_campaign->fetch_object())
	        		{
	            		($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
						echo "<tr align='center' class='$class'>
								<td height='20' class='borderBR' width='10%' align='center'><input type='checkbox' name='chkInclude[]' class='inputOptChk' value='$row->ID'></td>
								<td height='20' class='borderBR padl5' width='40%' align='left'><span class='txt10'>$row->Code</span></td>
								<td height='20' class='borderBR padl5' width='50%' align='left'><span class='txt10'>$row->Name</span>
							 </td></tr>";

						$rowalt += 1;
	    			}
	    			$rs_campaign->close();
				}
				else
				{
    				echo "<tr align='center'><td height='20' colspan='3' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
    			}
			?>
		</table>
	</div></td>
</tr>
</table>
</form>
</div>