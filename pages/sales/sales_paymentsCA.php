<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script src="js/jxPaymentsCA.js" language="javascript" type="text/javascript"></script>
<?php
	//setExpires(0);
	include IN_PATH.DS."scPaymentsCA.php";
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
    		<td class="txtgreenbold13">Payments Applied to Customer Account Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmValuationReport" method="post" >
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
    		<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td width="20%" height="20" align="right"></td>
          			<td width="2%" height="20"></td>
          			<td width="80%" height="20" align="left"></td>   
				</tr>
				<tr>
          			<td height="20" align="right"><strong>Branches :</strong></td>
          			<td height="20" align="right">&nbsp;</td>
          			<td height="20" align="left">
            			<select name="cboBranch" class="txtfield" >
            				<option value="0" selected>SELECT BRANCH</option>
							<?php							
																						
							if ($rs_branches->num_rows)
							{
								while ($row = $rs_branches->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";	
								}
								$rs_branches->close();
							}
						?>
            			</select>
          			</td>
        		</tr>
        		<tr>
					<td align="right"><strong>Customer :</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">	
					<select name="cboCustomer" class="txtfield" >
						<option value="0" selected>SELECT CUSTOMER</option>
						<?php 											
							if($rs_customer->num_rows)
							{
								while($row= $rs_customer->fetch_object())
								{									
								
										$sel = "";
								echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
								}
							}
						?>						
						</select>			
				</td>
				</tr> 	
				<tr>
					<td align="right"><strong>Campaign :</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboCampaign" class="txtfield" >
						<option value="0" selected>SELECT CAMPAIGN</option>
						<?php 
											
							if($rs_campaign->num_rows)
							{
								while($row= $rs_campaign->fetch_object())
								{									
									 $sel = "";
								     echo "<option value='".$row->ID."' $sel >".$row->Code."</option>";
								}
							}
						?>
						</select>			
					</td>
				</tr>
    		<tr>
    		  <td height="20" align="right"></td>
    		  <td height="20"></td>
    		  <td height="20" align="left"><input name="btnSubmit" type="button" class="btn" value="Submit" onclick="return showPage(0, <?php echo $branchid; ?>, '<?PHP echo $dealerid; ?>', '<?PHP echo $campgnid; ?>');" /></td>
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
		window.onload = showPage(0, <?php echo $branchid; ?>, '<?PHP echo $dealerid; ?>', '<?PHP echo $campgnid; ?>');    
	</script>
	
	</form>
	</td>
</tr>
</table>



<br>