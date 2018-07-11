<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>

<script src="js/jxRHODiscrepancyReport.js" language="javascript" type="text/javascript"></script>

<?php
	include IN_PATH.DS."scRHODiscrepancyReport.php";
?>

<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
.separated{font-weight: bold; width: 5%; text-align: center;}
.fieldlabel{text-align: right; font-weight: bold;}
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" style="min-height: 610px; display: block;">
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
    		<td class="txtgreenbold13">RHO/STA Discrepancy Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmCSOS" method="post">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
                    <table width="50%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="tabmin">&nbsp;</td>
		<td class="tabmin2">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="txtredbold">Action</td>
               	<td>&nbsp;</td>
			</tr>
          	</table>
      	</td>
      	<td class="tabmin3">&nbsp;</td>
	</tr>
  	</table>
    			<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
				<tr>
                                    <td height="20" align="right"></td>
                                    <td height="20">&nbsp;</td>
                                    <td height="20" align="left"></td>   
				</tr>
				<tr>
                                    <td height="20" align="right"><strong>From Date</strong></td>
                                    <td class="separated">:</td>
                                    <td height="20" align="left">
            			<input id="txtDate" name="txtDate" type="text" class="txtfield" readonly="yes" value="<?=date("m/d/Y", strtotime($sdate))?>">
                                <i>(e.g. MM/DD/YYYY)</i>
          			</td>
        		</tr>
				<tr>
          			<td height="20" align="right"><strong>To Date</strong></td>
                                <td class="separated">:</td>
          			<td height="20" align="left">
            			<input id="txtDate1" name="txtDate1" type="text" class="txtfield" readonly="yes" value="<?=date("m/d/Y", strtotime($ssdate))?>">
                                <i>(e.g. MM/DD/YYYY)</i>
          			</td>
        		</tr>
		    	<tr>
		      		<td height="20" align="right"><strong>Branch Code</strong></td>
                                <td class="separated">:</td>
                                <td height="20" align="left">
                                    <input id="cboBCodex" name="cboBCodex" class="txtfield" value="<?=$_POST['cboBCodex']?>">
                                    <input id="cboBCode" name="cboBCode" value="0" value="<?=$_POST['cboBCode']?>" class="txtfield" type="hidden">
        			</td>
	      		</tr>
	      		<tr>
		      		<td height="20" align="right"><strong>Movement Type</strong></td>
                                <td class="separated">:</td>
    		      	<td height="20" align="left">
    		      		<select id="cboMType" name="cboMType" class="txtfield">
    		        		<option value="0" selected="selected">[SELECT ALL]</option>
							<?php																						
							if ($rs_mtype->num_rows)
							{
								while ($row = $rs_mtype->fetch_object())
								{
						 			if ($row->ID == $mtype)
									{
										$sel = "selected";										
									}
						 			else
						 			{
						 				$sel = "";
						 			}
						 			echo "<option value='".$row->ID."' $sel >".$row->Code."</option>";	
								}
								$rs_mtype->close();
							}
							?>
	        			</select>
        			</td>
	      		</tr>
		    	<tr>
	  				<td height="20" align="right"></td>
    		  		<td height="20">&nbsp;</td>
    		  		<td height="20" align="left"><input id="btnSubmit" name="btnSubmit" type="submit" class="btn" value="Submit"/></td>
  				</tr>
    			<tr>
    				<td colspan="3" height="20">&nbsp;</td>
    			</tr>
    			</table>
			</td>
		</tr>
		</table>
		<br />
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td class="tabmin">&nbsp;</td>
		<td class="tabmin2">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="txtredbold">Result(s)</td>
               	<td>&nbsp;</td>
			</tr>
          	</table>
      	</td>
      	<td class="tabmin3">&nbsp;</td>
	</tr>
  	</table>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
	<tr>
  		<td valign="top" class="bgF9F8F7">
	  		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
	  		<tr>
				<td valign="top" class="" height="242">
	  				<div id="pgContent"><b>Loading Content...</b><img border="0" src="images/ajax-loader.gif">&nbsp;</div>
	  			</td>
	  		</tr>
	  		</table>
		</td>
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
	<br>
   	<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $sdate;?>', '<?php echo $bcode;?>', <?php echo $mtype;?>)"/></td>
	</tr>
	</table>

	<script>
		//Onload start the user off on page one
		window.onload = showPage("1", "<?php echo $sdate;?>", "<?php echo $ssdate; ?>","<?php echo $bcode;?>", "<?php echo $mtype;?>");    
	</script>
	
	</form>
	</td>
</tr>
</table>