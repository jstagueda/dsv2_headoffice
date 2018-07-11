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
<?PHP 
	include IN_PATH.DS."scInvProductStatus.php";
?>

<script src="js/jxPagingReportStatus.js" language="javascript" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{ 		
	//var docno = document.frmStocks.txtDocNo.value;
	//var remarks = document.frmStocks.txtRemarks.value;
	//var search = document.frmStocks.txtSearch.value;

	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+ "#MMHead" +"'");
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
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
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
    <td class="txtgreenbold13">Product Status Report</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td>
    	<form name="frmProductStatSearch" method="post" action="index.php?pageid=106&wid=<?php if (isset($_GET['wid'])){ echo $_GET['wid'];}else{echo '0';}?>&lid=<?php if (isset($_GET['lid'])){ echo $_GET['lid'];}else{echo '0';}?>&plid=<?php if (isset($_GET['plid'])){ echo $_GET['plid'];}else{echo '0';}?>&cid=<?php if (isset($_GET['cid'])){ echo $_GET['cid'];}else{echo '0';}?>&bid=<?php if (isset($_GET['bid'])){ echo $_GET['bid'];}else{echo '0';}?>&is=<?php if (isset($_GET['is'])){ echo $_GET['is'];}else{echo '0';}?>&pmgid=<?php if (isset($_GET['pmgid'])){ echo $_GET['pmgid'];}else{echo '0';}?>&ref=<?php if (isset($_Post['rdRefNo'])){ echo $_Post['rdRefNo'];}else{echo '0';}?>">
		<table width="27%" border="0" align="left" cellpadding="3" cellspacing="3"  class="bordersolo">
		  <tr>
		     		<td align="right" ><strong>Warehouse</strong></td>
		     		<td width="1%" align="center">:</td>
             		<td height="20" align="left">
            			<select name="cboWarehouse" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
<!--            			<option value="index.php?pageid=106&wid=0&plid=0&lid=<?php echo $tmpLid;?>&cid=&bid=&is=&pmgid=0" selected>ALL WAREHOUSE</option>-->
							<?php							
																						
							if ($rs_warehouse->num_rows)
							{
								while ($row = $rs_warehouse->fetch_object())
								{
						 			($_GET['wid']== $row->ID) ? $sel = "selected" : $sel = "";
								//echo "<option value='$row->ID' $sel>$row->Name</option>";
						 			echo "<option value='index.php?pageid=106&wid=".$row->ID."&plid=$tmpPlid&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&ref=$ref' $sel >".$row->Name."</option>";
																
								}
								$rs_warehouse->close();
							}
						?>
            			</select>
          			</td>
		  </tr>
		  <tr>
					<td align="right"><strong>Location</strong></td>
					<td width="1%" align="center">:</td>					
					<td align="left">	
					<select name="cboLocation" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
						<option value="index.php?pageid=106&wid=<?php echo $tmpWid;?>&plid=<?php echo $tmpPlid;?>&lid=0&cid=<?php echo $tmpCid;?>&bid=<?php echo $tmpBid;?>&is=<?php echo $tmpIs;?>&pmgid=<?php echo $tmpPmg;?>&ref=<?php echo $ref;?>" selected>ALL</option>
						<?php 											
							if($rs_location->num_rows)
							{
								while($row= $rs_location->fetch_object())
								{									
									if ($_GET['lid'] == $row->ID) 
										$sel = "selected";
									else 
										$sel = "";
								echo "<option value='index.php?pageid=106&wid=$tmpWid&plid=$tmpPlid&lid=".$row->ID."&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&ref=$ref' $sel >".$row->Name."</option>";
								}
							}
						?>						
						
						</select>			
					</td>
			</tr>
			<tr>
					<td align="right"><strong>Product Line</strong></td>
					<td width="1%" align="center">:</td>
					<td align="left">
						<select name="cboProdLine" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
						<option value="index.php?pageid=106&wid=<?php echo $tmpWid;?>&plid=0&lid=<?php echo $tmpLid;?>&cid=<?php echo $tmpCid;?>&bid=<?php echo $tmpBid;?>&is=<?php echo $tmpIs;?>&pmgid=<?php echo $tmpPmg;?>&ref=<?php echo $ref;?>" selected>ALL</option>
						<?php 
											
							if($rs_productline->num_rows)
							{
								while($row= $rs_productline->fetch_object())
								{									
									($_GET['plid'] == $row->ID)?$sel = "selected" : $sel = "";
								     echo "<option value='index.php?pageid=106&wid=$tmpWid&plid=".$row->ID."&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&ref=$ref' $sel >".$row->Name."</option>";
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
						<input name="txtProdCode1" type="text" class="txtfield" id="txtProdCode1" value="<?php echo $vSearch2;?>" />
						<span id="indicator1" style="display: none">
							<img src="images/ajax-loader.gif" alt="Working..." />
						</span>                                      
						<div id="prod_choices1" class="autocomplete" style="display:none">
						</div>
						<script type="text/javascript">							
						//<![CDATA[
				     	var prod_choices = new Ajax.Autocompleter('txtProdCode1', 'prod_choices1', 'includes/scProductStatusList_ajax.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator1'});																			//]]>
						</script>
						<input name="hdnProd" type="hidden" id="hdnProd" value=""/>
					</td>
        		</tr>
			<tr>
					<td align="right"><strong>PMG</strong></td>
					<td width="1%" align="center">:</td>
					<td align="left">
						<select name="cboPMG" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
						<option value="index.php?pageid=106&wid=<?php echo $tmpWid;?>
															&plid=<?php echo $tmpPlid;?>
															&lid=<?php echo $tmpLid;?>
															&cid=<?php echo $tmpCid;?>
															&bid=<?php echo $tmpBid;?>
															&is=<?php echo $tmpIs;?>
															&ref=<?php echo $ref;?>
															&pmgid= 0" selected>ALL</option>
						<?php 
											
							if($rs_pmg->num_rows)
							{
								while($row= $rs_pmg->fetch_object())
								{									
									($_GET['pmgid'] == $row->ID)?$sel = "selected" : $sel = "";
								echo "<option value='index.php?pageid=106&wid=$tmpWid&plid=$tmpPlid&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&ref=$ref&is=$tmpIs&pmgid=".$row->ID."' $sel >".$row->Name."</option>";
								}
							}
						?>
						</select>			
					</td>
			</tr>
			 <tr>
		  		<td width="20%" align="right"><strong>As of</strong></td>
				<td width="1%" align="center">:</td>
				<td width="70%" align="left"><input name="txtStartDates" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php  echo $fromDate; ?>">
		        <input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
				<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
				</td>
		  </tr>	
		  <tr>
		  	  <td width="20%" align="right"></td>
			  <td width="1%" align="center"></td>
			  <td align ="left">
              <input name="rdRefNo" type="radio" size="20" maxlength="20" <?php if (isset($_POST['rdRefNo'])) { if ($_POST['rdRefNo'] == 1) { echo "checked"; } } else { echo "checked"; }; ?> value="1" />All
              </td>             
          </tr>
          <tr>
         
              <td width="20%" align="right"></td>
			  <td width="1%" align="center"></td>
			  <td align ="left"><input name="rdRefNo" type="radio" size="20" maxlength="20" <?php if (isset($_POST['rdRefNo'])) { if ($_POST['rdRefNo'] == 2) { echo "checked"; } }; ?> value="2" />Negative Inventory Only</td>	
          </tr>
		  <tr>
		  		<td align="right"></td>
			  	<td align="center"></td>
				<td align="left"><input type="submit" name="btnSearch" class="btn" id="btnSearch" value="Submit" /></td>
          </tr>
		  </table>
          </form>
     </td>
  </tr>
</table>
<br />
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">&nbsp;</td>
               	<td>&nbsp;</td>
              </tr>
          </table></td>
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
		window.onload = showPage("1", "<?php echo $vSearch; ?>",
									  "<?PHP echo $tmpWid; ?>", 
									  "<?PHP echo $tmpLid; ?>",
									  "<?PHP echo $tmpBid; ?>",
									  "<?PHP echo $tmpCid; ?>",
									  0,
									  0,
									  "<?PHP echo $tmpPlid; ?>",
									  "<?PHP echo $tmpPmg; ?>",
									  "<?PHP echo $tmpIs; ?>",
									  "<?PHP echo $ref; ?>",
									 "<?PHP echo $fromDate; ?>");    
	</script>
<table width="95%"  border="0" align="center">
	<tr>
		<td height="20" align="center">
    	<!--<a class="txtnavgreenlink" href="index.php?pageid=1"><input name="Button" type="button" class="btn" value="Back"></a>
    	-->
    	<input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $vSearch;?>','<?php echo $tmpWid;?>','<?php echo $tmpLid;?>','<?php echo $tmpPmg; ?>','<?php echo $tmpIs;?>','<?php echo $tmpPlid; ?>','<?php echo $ref; ?>','<?php echo $fromDate; ?>');"/>
    	</td>
	</tr>
</table>
	 
	  </td>
  </tr>
</table>
<br>
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

