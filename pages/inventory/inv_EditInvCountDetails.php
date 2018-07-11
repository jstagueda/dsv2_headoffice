<?php
	include IN_PATH.'scEditInvCountDetails.php';
?>

<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function confirmCancel()
{
  if (confirm('Are you sure you want to cancel this transaction?') == false)
   {
        return false;
   }    
}

function checkAll(bin) 
{
	var elms = document.frmEditInvCountDetails.elements;

	for (var i = 0; i < elms.length; i++)
	{
		if (elms[i].name == 'chkSelect[]') 
	  	{
			elms[i].checked = bin;		  
	  	}			
	}
}

function UnCheck()
{

    var chkAll = document.frmEditInvCountDetails.chkAll;
    chkAll.checked = false;
}

function checker()
{
	var ml = document.frmEditInvCountDetails;
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

function validateSave()
{ 
	var cnt = document.frmEditInvCountDetails.hProdCount;
	
	if (eval(cnt.value) == 0)
	{
		alert ("There are no product(s) to be added.");	
		return false;	
	}
	else
	{
		if (!checker())
    	{
    		alert('Please select product(s) to be added.');
    		return false;		
    	}
    	else
    	{
			if(confirm('Are you sure you want to save this transaction?') == true)
			{
			   return true;
		    }
		    else
		    {
		        return false;
		    }
    	} 
    }
}
</script>

<form name="frmEditInvCountDetails" method="post" action="index.php?pageid=133.1&tid=<?php echo $_GET['tid'];?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    	<tr>
		    		<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
		    	</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
    		<td class="txtgreenbold13">Edit Worksheet</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
        	<td>
			  	<?php 
			  		if (isset($_GET['msg']))
				  	{
				  		$message = strtolower($_GET['msg']);
					  	$success = strpos("$message","success"); 
					  	echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'><b>".$_GET['msg']."</b></div><br>";
				  	}
				  	if (isset($_GET['errmsg']))
				  	{ 
					  	echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'><b>".$_GET['errmsg']."</b></div><br>";
				  	} 
			  	?> 
		  	</td>
	  	</tr>
	  	</table>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
      		<td class="tabmin">&nbsp;</td>
      		<td class="tabmin2 txtredbold">General Information</td>
      		<td class="tabmin3">&nbsp;</td>
  		</tr>
  		</table>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
    			<tr>
      				<td colspan="2">&nbsp;</td>
    			</tr>
    			<tr>
      				<td width="50%" valign="top">
      					<table width="100%"  border="0" cellspacing="1" cellpadding="0">
        				<tr>
          					<td width="25%" height="20" class="txt10">Worksheet No. :</td>
          					<td width="75%" height="20"><input name="txtRefNo" type="text" readonly="readonly" class="txtfield" id="txtRefNo" size="20" maxlength="20" value="<?php  echo $transno;?>" /></td>
        				</tr>
        				<tr>
          					<td height="20" class="txt10">Document No. :</td>
          					<td height="20"><input name="txtDocNo" type="text"  readonly="readonly" class="txtfield" id="txtDocNo" size="20" maxlength="20" value="<?php  echo $docno;?>" /></td>
    					</tr>
        				<tr>
          					<td height="20" class="txt10">Warehouse :</td>
          					<td height="20"><input name="txtWarehouse" type="text"  readonly="readonly" class="txtfield" id="txtWarehouse" size="20" maxlength="20" value="<?php  echo $warehouse;?>" /></td>
        				</tr>
      					</table>
  					</td>
      				<td valign="top">
      					<table width="100%"  border="0" cellspacing="1" cellpadding="0">
      					<tr>
          					<td height="20" class="txt10">Created By :</td>
          					<td height="20"><input name="txtCreatedBy" type="text"  readonly="readonly" class="txtfield" id="txtCreatedBy" size="20" maxlength="20" value="<?php  echo $createdby;?>" /></td>
    					</tr>
    					<tr>
          					<td height="20" class="txt10">Date Created :</td>
          					<td height="20"><input name="startDate" type="text" class="txtfield" id="startDate" size="20" readonly="yes" value="<?php echo $transdate; ?>" />
      					</tr>
      					<tr>
          					<td height="20" class="txt10">Date Modified :</td>
          					<td height="20"><input name="startDate" type="text" class="txtfield" id="startDate" size="20" readonly="yes" value="<?php echo $lastmodified; ?>" />
      					</tr>
        				<tr>
          					<td width="20%" height="20" valign="top" class="txt10">Remarks :</td>
          					<td width="80%" height="20"><textarea name="txtRemarks" cols="40" rows="2" class="txtfieldnh" id="txtRemarks" readonly="yes"><?php  echo $remarks; ?></textarea></td>
        				</tr>
      					</table>
  					</td>
				</tr>
				<tr>
      				<td colspan="2">&nbsp;</td>
    			</tr>
				</table>
			</td>
		</tr>
		</table>
  		<br>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  		<tr>
  			<td class="tabmin">&nbsp;</td>
      		<td class="tabmin2 txtredbold">Add Items</td>
      		<td class="tabmin3">&nbsp;</td>
    	</tr>
  		</table>
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr class="bgF9F8F7">
  			<td height="35" align="left" class="borderBR txt10 padl5">
  				<strong>Products created after :</strong>
				&nbsp;&nbsp;&nbsp;
				<input name="txtCreatedDate" type="text" class="txtfield" id="txtCreatedDate" style="width:70px;" readonly="yes" value="<?php if (isset($_POST['txtCreatedDate'])) { $tmpsdate = strtotime($_POST['txtCreatedDate']); $sdate = date("m/d/Y",$tmpsdate); echo $sdate; } else { echo $datetoday; }?>">
				<input type="button" class="buttonCalendar" name="anchorCreatedDate" id="anchorCreatedDate" value=" ">
				<div id="divCreatedDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
				&nbsp;&nbsp;&nbsp;
				<input type="submit" id="btnSearch" name="btnSearch" class="btn"  value="Search" onClick="return validateSearch();">
  			</td>
		</tr>
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
    			<tr>
    				<td>
    					<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="tab txtdarkgreenbold10">
        				<tr align="center">
              				<td width="10%" height="20" class="bdiv_r" align="center"><input type="checkbox" id="chkAll" name="chkAll" onclick="checkAll(this.checked);"></td>
              				<td width="40%" height="20" class="bdiv_r padl5" align="left">Item Code</td>
              				<td width="50%" height="20" class="padl5" align="left">Item Description</td>
      					</tr>
        				</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_300">
				<table width="100%"  border="0" cellpadding="0" cellspacing="0">
				<?php
					$prodcnt = 0;
					if (isset($_POST['btnSearch']))
					{
						if ($rs_product->num_rows)
						{
							$prodcnt = $rs_product->num_rows;
							$cnt = 0;
							while ($row = $rs_product->fetch_object())
							{
								$cnt ++;
								($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';	
				?>
								<input type="hidden" name="hProdID<?php echo $cnt; ?>" value="<?php echo $row->ProductID; ?>"/>
								<input type="hidden" name="hUOMID<?php echo $cnt; ?>" value="<?php echo $row->UnitTypeID; ?>"/>
								<tr align="center" class="<?php echo $alt; ?>">
				      				<td width="10%" height="20" class="borderBR" align="center"><input type="checkbox" name="chkSelect[]" id="chkSelect" onclick="UnCheck();" value="<?php echo $cnt; ?>"></td>
				      				<td width="40%" height="20" class="borderBR padl5" align="left"><?php echo $row->ProductCode; ?></td>
				      				<td width="50%" height="20" class="borderBR padl5" align="left"><?php echo $row->Product; ?></td>
								</tr>
				<?php
							}
							$rs_product->close();
						}
						else
						{
							echo "<tr align='center'><td height='20' class='borderBR txtredsbold' colspan='3' align='center'>No record(s) to display.</td></tr>";							
						}
					}
					else
					{
				?>
				<tr align="center">
					<td height="20" class="borderBR txtredsbold" colspan="3" align="center">No record(s) to display.</td>
				</tr>
				<?php
					}
				?>
				</table>
				</div>
			</td>
		</tr>
		</table>
		<br>
		<table width="95%" align="center" border="0" cellpadding="0" cellspacing="">
		<tr>
			<td align="center">
				<input type="hidden" name="hProdCount" value="<?php echo $prodcnt; ?>">
				<input name="btnAddPrint" type="submit" class="btn" value="Add & Print" onclick="return validateSave();">
				<input name="btnAdd" type="submit" class="btn" value="Add" onclick="return validateSave();"/>
				<input name="btnCancel" type="submit" class="btn" onclick="return confirmCancel();" value=" Cancel "/>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>	
<br />
<br />

<script type="text/javascript">
Calendar.setup({
	inputField     :    "txtCreatedDate",     // id of the input field
	ifFormat       :    "%m/%d/%Y",      // format of the input field
	displayArea    :	"divCreatedDate",
	button         :    "anchorCreatedDate",  // trigger for the calendar (button ID)
	align          :    "Bl",           // alignment (defaults to "Bl")
	singleClick    :    true
});
</script>