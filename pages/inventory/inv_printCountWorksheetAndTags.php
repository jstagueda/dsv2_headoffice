<?php include IN_PATH.'scCreatePrintCountWorkSheetandTags.php';?>

<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>

<script type="text/javascript">
var $j = jQuery.noConflict();

function loadProductList()
{
	var wid = document.frmInfo.cboWarehouse.value;
	
	$j.ajax({
        type: 'POST',
        url: 'includes/jxLoadProductList.php?wid=' + wid,
        success: function(innerText) 
        {
        	tmp_val = innerText.split("_");
        	$j('#dvProductList').html(tmp_val[0]);
        },
        dataType: 'text'
      });   
}

function validateSave()
{ 
    var txtdocno = document.frmInfo.txtDocNo;
    var txtremarks = document.frmInfo.txtRemarks;    
    var whouse = document.frmInfo.cboWarehouse;
    var msg = "";
    var prod = 0;
			    
    if(txtdocno.value == '')
    {
        msg += "* Document No. is required. \n";      
    }
	if(whouse.value == 0)
    {
        msg += "* Warehouse is required. \n";      
    }
    
    if(msg != "")
    {
        alert(msg);
        return false;
    }
    else
    {
    	if (confirm('Are you sure you want to save this transaction?') == false)
	   	{
	        return false;
	   	}
	   	else
	   	{
	   		return true;
	   	} 
    }
}

function validateCancel()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
		return false;
	else
		return true;
}
</script>

<body>
<form name="frmInfo" action="index.php?pageid=57" method="post">
<input type="hidden" name="hProdListID" value="">
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
    		<td class="txtgreenbold13">Print Count Tag & Worksheet</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<?php
		if (isset($_GET['errmsg']) != "")
		{
		?>
		<br>
		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%" class="txtreds">&nbsp;<b><?php echo $_GET['errmsg']; ?></b></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<?php		
		}
		else if(isset($_GET['msg']) != "")
		{
		?>
		<br>
		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $_GET['msg']; ?></b></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<?php		
		}
		?>
		<br />
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
      	<tr>
      		<td class="tabmin">&nbsp;</td>
          	<td class="tabmin2">
          		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
            	<tr>
              		<td class="txtredbold">General Information</td>
              		<td>&nbsp;</td>
            	</tr>
          		</table>
          	</td>
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
              				<td height="20" class="txt10">Worksheet No. :</td>
              				<td><input name="txtRefNo" type="text" class="txtfield" id="txtRefNo" size="20" maxlength="20" readonly="yes" value="<?php echo $trno; ?>" /></td>
            			</tr>
            			<tr>
              				<td height="20" class="txt10">Document No. :</td>
              				<td><input name="txtDocNo" type="text" class="txtfield" id="txtDocNo" size="20" maxlength="20" value="" /></td>
            			</tr>  	
            			<tr>
              				<td height="20" class="txt10">Warehouse :</td>
              				<td>
              					<select name="cboWarehouse" style="width:160px " class="txtfield" onchange="loadProductList();">
              					<?php
									echo "<option value=\"0\">[SELECT HERE]</option>";
									if ($rs_whouse->num_rows)
									{
										while ($row = $rs_whouse->fetch_object())
										{
											echo "<option value='$row->ID' $sel>$row->Name</option>";
										}
										$rs_whouse->close();
									}
                         		?>      
              					</select>
              				</td>
            			</tr>  		    
            			<tr>         
              				<td height="20" class="txt10">&nbsp;</td>
              				<td>&nbsp;</td>
            			</tr>
          				</table>
          			</td>
          			<td valign="top">
          				<table width="100%"  border="0" cellspacing="1" cellpadding="0">
          				<tr>
              				<td height="20" class="txt10">Created By :</td>
              				<td height="20"><input name="txtCreatedBy" type="text" class="txtfield" id="txtCreatedBy" size="20" readonly="yes" value="<?php echo $createdby; ?>"></td>
            			</tr>
            			<tr>
              				<td width="500" height="20" class="txt10">Date Created :</td>
              				<td width="500"><input name="txtTxnDate" type="text" class="txtfield" id="txtTxnDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>"></td>
            			</tr>         
            			<tr>
              				<td height="20" valign="top" class="txt10">Remarks :</td>
              				<td height="20"><textarea name="txtRemarks" cols="40" rows="3" class="txtfieldnh" id="txtRemarks" ></textarea></td>
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
  		<br />
  		<!-- START PRODUCT LIST -->
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  		<tr>
  			<td class="tabmin">&nbsp;</td>
        	<td class="tabmin2">
        		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        		<tr>
        			<td class="txtredbold">Worksheet Details</td>
                	<td>&nbsp;</td>
            	</tr>
          		</table>
      		</td>
      		<td class="tabmin3">&nbsp;</td>
    	</tr>
  		</table>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
  		<tr>
  			<td colspan="2" valign="top" class="bgF9F8F7">
  				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        		<tr>
          			<td class="tab">
          				<table width="100%"  border="0" cellpadding="0" cellspacing="" class="txtdarkgreenbold10">
            			<tr align="center">
            				<td width="10%" class="bdiv_r"><div align="center">Count Tag No.</div></td>
	              			<td width="30%" class="bdiv_r"><div align="left" class="padl5">Item Code</div></td>            
	              			<td width="30%" class="bdiv_r"><div align="left" class="padl5">Item Description</div></td>
	              			<td width="30%"><div align="left" class="padl5">Location</div></td>  
              			</tr>
            			</table>
        			</td>
        		</tr>
        		<tr>
          			<td>
	        			<input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
            			<div id="dvProductList" class="scroll_300">
	              			<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	              			<tr>
	              				<td height="20" class="borderBR"><div align="center" class="txtredsbold">No record(s) to display.</td>
	              			</tr>
	                		</table>
          				</div>
      				</td>
    			</tr>
    			</table>
			</td>
  		</tr>
		</table>      
  		<!-- END PRODUCT LIST -->
  		<br>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  	<tr>
	  		<td align="center">	
	  			<input name="btnSavePrint" type="submit" class="btn" value="Save & Print" onClick="return validateSave();">
	  			<input name="btnSave" type="submit" class="btn" value="Save" onClick="return validateSave();"> 
	  			<input name="btnCancel" type="submit" class="btn" value="Cancel" onClick="return validateCancel();">
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>	
</form>
</body>