<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script src="js/jxPagingItemAgingReport.js" language="javascript" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<?php
	include IN_PATH.DS."scItemAgingReport.php";
?>

<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

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
    		<td class="txtgreenbold13">Item Aging Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmItemAgingReport" method="post">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
    			<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td width="35%" height="20" align="right"></td>
          			<td width="2%" height="20">&nbsp;</td>
          			<td width="80%" height="20" align="left"></td>   
				</tr>
				<tr>
          			<td height="20" align="right"><strong>Warehouse :</strong></td>
          			<td height="20" align="right">&nbsp;</td>
          			<td height="20" align="left">
            			<select id="cbowarehouse" name="cbowarehouse" class="txtfield">
							<?php							
																						
							if ($rs_warehouse->num_rows)
							{
								while ($row = $rs_warehouse->fetch_object())
								{
						 			if ($row->ID == $warehouseid)
									{
										$sel = "selected";										
									}
						 			else
						 			{
						 				$sel = "";
						 			}
						 			echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";	
								}
								$rs_warehouse->close();
							}
						?>
            			</select>
          			</td>
        		</tr>
				<tr>
					<td align="right"><strong>Product Line :</strong></td>
					<td width="2%" height="20">&nbsp;</td>
					<td align="left">
						<select id="cboproductline" name="cboproductline" class="txtfield">
						<option value="0" selected>[SELECT ALL]</option>
						<?php 		
							if($rs_productline->num_rows)
							{
								while($row= $rs_productline->fetch_object())
								{									
								 	if ($row->ID == $productlineid)
									{
										$sel = "selected";										
									}
						 			else
						 			{
						 				$sel = "";
						 			}
							     	echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
								}
								$rs_productline->close();
							}
						?>
						</select>			
					</td>
				</tr>
				<tr>
		      		<td height="20" align="right"><strong>Inventory Status :</strong></td>
    		      	<td height="20">&nbsp;</td>
    		      	<td height="20" align="left">
    		      		<select id="cboStatus" name="cboStatus" class="txtfield">
    		        		<option value="0" selected="selected">[SELECT ALL]</option>
							<?php																		
							if ($rs_status->num_rows)
							{
								while ($row = $rs_status->fetch_object())
								{
									if ($row->ID == $invstatusid)
									{
										$sel = "selected";										
									}
						 			else
						 			{
						 				$sel = "";
						 			}
						 			echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";	
								}
								$rs_status->close();
							}
							?>
        				</select>
    				</td>
  				</tr>
				<tr>
		      		<td height="20" align="right"><strong>Item Code :</strong></td>
    		      	<td height="20">&nbsp;</td>
    		      	<td height="20" align="left"><input name="txtSearch" type="text" class="txtfield" id="txtSearch" value="<?php echo $search;?>" /></td>
  				</tr>
  				<tr>
		      		<td height="20" align="right"><strong>Age :</strong></td>
    		      	<td height="20">&nbsp;</td>
    		      	<td height="20" align="left"><input name="txtAge" type="text" class="txtfield" id="txtAge" value="<?php echo $age;?>" /></td>
  				</tr>
  				<tr>
		  			<?php /*<td height="20" align="right"><strong>As Of Date</strong></td>
					<td height="20">&nbsp;</td>
					<td height="20" align="left">
						<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="">
		        		<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
					</td> */ ?>
		  		</tr>
		  		<tr>
      				<td height="20">&nbsp;</td>
      				<td height="20">&nbsp;</td>
      				<td height="20" align="left"><input name="btnSubmit" type="submit" class="btn" value="Submit" /></td>
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
				<td class="txtredbold">&nbsp;</td>
               	<td>&nbsp;</td>
			</tr>
          	</table>
      	</td>
      	<td class="tabmin3">&nbsp;</td>
	</tr>
  	</table>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
	<tr>
  		<td valign="top" class="bgF9F8F7">
	  		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
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
		<td align="center"><input name="input" type="button" class="btn" value="Print" onclick="openPopUp(<?php echo $warehouseid;?>,<?php echo $productlineid;?>,<?php echo $invstatusid;?>, '<?php echo $search; ?>', '<?php echo $age; ?>', '<?php echo $today; ?>')"/></td>
	</tr>
	</table>
	
	<script>
		//Onload start the user off on page one
		window.onload = showPage("1", <?php echo $warehouseid; ?>, <?php echo $productlineid; ?>, '<?php echo $search; ?>', <?php echo $invstatusid; ?>, '<?php echo $age; ?>', '<?php echo "";//$today; ?>');    
	</script>
	
	</form>
	</td>
</tr>
</table>
<br>

<script type="text/javascript">
	/*Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});*/
</script>