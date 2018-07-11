<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>


<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script src="js/shortcut.js" type="text/javascript"></script>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>

<script src="js/jxPagingValuationReport.js" language="javascript" type="text/javascript"></script>

<?php
	//setExpires(0);
	include IN_PATH.DS."scValuationReport.php";
?>

<script type="text/javascript">

function MM_jumpMenu(targ,selObj,restore)
{ 		

	var search = document.frmValuationReport.txtProdCode1.value;
	var dteSearch = document.frmValuationReport.txtStartDates.value;

	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"&dteSearch=" + dteSearch+"&search=" + search+  "#MMHead" +"'");
	if (restore) 
		selObj.selectedIndex = 0;
}

function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}
</script>
<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

<style type="text/css">
<!--
.style1 {color: #FF0000}

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
}z

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
    		<td class="txtgreenbold13">Inventory Valuation Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmValuationReport" method="post" action="index.php?pageid=105">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
    		<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td width="10%" height="20" align="right"></td>
          			<td width="2%" height="20"></td>
          			<td width="38%" height="20" align="left"></td>   
				</tr>
				<tr>
          			<td height="20" align="right"><strong>Warehouse :</strong></td>
          			<td height="20" align="right">&nbsp;</td>
          			<td height="20" align="left">
            			<select name="cboWarehouse" class="txtfield">
							<?php												
							if ($rs_warehouse->num_rows)
							{
								while ($row = $rs_warehouse->fetch_object())
								{
						 			($warehouseid == $row->ID) ? $sel = "selected" : $sel = "";
						 			echo "<option value='$row->ID' $sel >".$row->Name."</option>";	
								}
								$rs_warehouse->close();
							}
						?>
            			</select>
          			</td>
        		</tr>
        		<tr>
					<td align="right"><strong>Location</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">	
					<select name="cboLocation" class="txtfield">
						<option value="0" selected>ALL LOCATION</option>
						<?php 											
							if($rs_location->num_rows)
							{
								while($row= $rs_location->fetch_object())
								{									
									if ($tmpLid == $row->ID) 
										$sel = "selected";
									else 
										$sel = "";
								echo "<option value='$row->ID' $sel >".$row->Name."</option>";
								}
							}
						?>						
						</select>			
				</td>
				</tr> 	
				<tr>
					<td align="right"><strong>Product Line</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboProdLine" class="txtfield">
						<option value="0" selected>ALL PRODUCT LINE</option>
						<?php 
											
							if($rs_productline->num_rows)
							{
								while($row= $rs_productline->fetch_object())
								{									
									($tmpPlid == $row->ID)?$sel = "selected" : $sel = "";
								     echo "<option value='$row->ID' $sel >".$row->Code."-".$row->Name."</option>";
								}
							}
						?>
						</select>			
					</td>
				</tr>
				<tr>
          			<td width="10%" height="20" align="right"><strong>Item Code  </strong></td>
          			<td width="2%" height="20">:</td>
          			<td width="80%" height="20" class="txt10">
						<input name="txtProdCode1" type="text" class="txtfield" id="txtProdCode1" value="<?php echo $prodCode;?>" />
						<span id="indicator1" style="display: none">
							<img src="images/ajax-loader.gif" alt="Working..." />
						</span>                                      
						<div id="prod_choices1" class="autocomplete" style="display:none">
						</div>
						<script type="text/javascript">							
						//<![CDATA[
				     	var prod_choices = new Ajax.Autocompleter('txtProdCode1', 'prod_choices1', 'includes/scProductCodeListAjax.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator1'});																			//]]>
						</script>
						<!--<input name="hProdID" type="text" id="hProdID" value="<?php echo $prodID;?>"/>
					--></td>
        		</tr>
        		
					<td align="right"><strong>PMG</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboPMG" class="txtfield">
						<option value="0" selected>ALL PMG</option>
						<?php 
											
							if($rs_pmg->num_rows)
							{
								while($row= $rs_pmg->fetch_object())
								{									
									($tmpPmg == $row->ID)?$sel = "selected" : $sel = "";
								echo "<option value='$row->ID' $sel >".$row->Code."</option>";
								}
							}
						?>
						</select>			
					</td>
				</tr>						
        		<tr>
		  		<td width="20%" align="right"><strong>As Of</strong></td>
				<td width="2%" height="20">:</td>
				<td width="70%" align="left"><input name="txtStartDates" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $vdte ;?>">
		        <input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
				<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
				</td>
		  </tr>
    		<tr>
      			<td height="20" align="right"></td>
      			<td height="20"></td>
      			<td height="20" align="left"><input name="btnSubmit" type="submit" class="btn" value="Submit" /></td>
    		</tr>
    		<tr>
    			<td colspan="3" height="15">&nbsp;</td>
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
	
	<script>
		//Onload start the user off on page one
		window.onload = showPage("1", "<?php echo $prodCode; ?>", "<?php echo $warehouseid; ?>", "<?php echo $tmpLid; ?>",	"<?php echo $tmpPlid; ?>", "<?php echo $tmpPmg; ?>", "<?php echo $vdte;?>");    
	</script>
	
	</form>
	</td>
</tr>
</table>

<table width="95%"  border="0" align="center">
	<tr>
		<td height="20" align="center">
    	<!--<a class="txtnavgreenlink" href="index.php?pageid=1"><input name="Button" type="button" class="btn" value="Back"></a>
    	-->
    	<input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $prodCode; ?>','<?PHP echo $warehouseid; ?>','<?PHP echo $tmpLid; ?>','<?PHP echo $tmpPlid; ?>','<?PHP echo $tmpPmg; ?>','<?php echo $vdte;?>')"/>
    	</td>
	</tr>
</table>

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

<br>