<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script src="js/jxCriticalStockOutofStockReport.js" language="javascript" type="text/javascript"></script>
<?php
	//setExpires(0);
	include IN_PATH.DS."scCriticalStockOutofStockReport.php";
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

function openPopUp(obj1,obj2,obj3,obj4,obj5,obj6) 
{

	var objWin;
	
		popuppage = "pages/inventory/inv_itemAgingReportPrint.php?wid="+obj1+"&plid="+obj2+"&sid="+obj3+"&code="+obj4+"&age="+obj5+"&dteAsOf="+obj6;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','500','yes');
		}
		
		return false;  		
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
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
		      		<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory</a></td>
		    	</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">Critical Stock / Out of Stock Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
<form name="frmCSOS" method="post" action = "pages/inventory/inv_CriticalStockPrint.php" target = "_blank">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
    		<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td width="35%" height="20" align="right"></td>
          			<td width="2%" height="20"></td>
          			<td width="80%" height="20" align="left"></td>   
				</tr>
				<tr>
          			<td height="20" align="right"><strong>Warehouse :</strong></td>
          			<td height="20" align="right">&nbsp;</td>
          			<td height="20" align="left">
            			<select name="cbowarehouse" class="txtfield" >
            				<option value="0" selected>SELECT WAREHOUSE</option>
							<?php							
																						
							if ($rs_warehouse->num_rows)
							{
								while ($row = $rs_warehouse->fetch_object())
								{
						 			$sel = "";
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
						<select name="cboproductline" class="txtfield" >
						<option value="0" selected>SELECT PRODUCT LINE</option>
						<?php 		
							if($rs_productline->num_rows)
							{
								while($row= $rs_productline->fetch_object())
								{									
									 $sel = "";
								     echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
								}
								$rs_productline->close();
							}
						?>
						</select>			
					</td>
				</tr>
    		    <tr>
    		      <td height="20" align="right"><strong>Product Market Group :</strong></td>
    		      <td height="20"></td>
    		      <td height="20" align="left"><select name="cbopmg" class="txtfield" >
    		        <option value="0" selected="selected">SELECT PMG</option>
						<?php							
																						
							if ($rs_tpipmg->num_rows)
							{
								while ($row = $rs_tpipmg->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";	
								}
								$rs_tpipmg->close();
							}
						?>
  		        </select></td>
  		      </tr>
    		    <tr>
    		      <td height="20" align="right"><strong>Item Code :</strong></td>
    		      <td height="20"></td>
    		      <td height="20" align="left"><input name="txtitemcode" type="text" class="txtfield5"  value="" /></td>
  		      </tr>
    		    <tr>
    		      <td height="20" align="right"><strong>Inventory Status :</strong></td>
    		      <td height="20"></td>
    		      <td height="20" align="left"><select name="cboStatus" class="txtfield" >
    		        <option value="0" selected="selected">SELECT STATUS</option>
						<?php							
																						
							if ($rs_status->num_rows)
							{
								while ($row = $rs_status->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";	
								}
								$rs_status->close();
							}
						?>
  		        </select></td>
  		      </tr>
    		    <tr>
    		      <td height="20" align="right"><strong>Qty Range From :</strong></td>
    		      <td height="20"></td>
    		      <td height="20" align="left"><input name="txtqtyfrom" type="text" class="txtfield5"  value="0" /></td>
  		      </tr>
    		    <tr>
    		      <td height="20" align="right"><strong>Qty Range To :</strong></td>
    		      <td height="20"></td>
    		      <td height="20" align="left"><input name="txtqtyto" type="text" class="txtfield5"  value="0" /></td>
  		      </tr>
    		    <tr>
    		  <td height="20" align="right"></td>
    		  <td height="20"></td>
    		  <td height="20" align="left"><input name="btnSubmit" type="button" class="btn" value="Submit" onclick="return showPage(1, <?php echo $warehouseid; ?>, <?PHP echo $productid; ?>, <?PHP echo $pmgid; ?>, '<?PHP echo $pcode; ?>', <?PHP echo $invstatus; ?>, <?PHP echo $qtyfrom; ?>, <?PHP echo $qtyto; ?>);" /></td>
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
		window.onload = showPage(1, <?php echo $warehouseid; ?>, <?PHP echo $productid; ?>, <?PHP echo $pmgid; ?>, '<?PHP echo $pcode; ?>', <?PHP echo $invstatus; ?>, <?PHP echo $qtyfrom; ?>, <?PHP echo $qtyto; ?>);    
	</script>
	</td>
</tr>
</table>
<br>

<table width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr>
		<td align="center">
			<input class="btn" type="submit"  value="Print" name="input">
		</td>
	</tr>
</table>
</form>
<br />