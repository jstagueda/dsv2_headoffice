<script src="js/jxViewSingleLinePromo.js" language="javascript" type="text/javascript"></script>

<style>
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
}

.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
.ui-widget-overlay{height:130%;}

.txtdarkgreenbold10 td{
	border-bottom: 2px solid #FFA3E0;
	 padding:5px;
}
.trlist td{border-bottom: 1px solid #FFA3E0; padding:5px;}

.fieldlabel{
	font-weight:bold;
	text-align:right;
	width:30%;
}

.separator{
	font-weight:bold;
	text-align:center;
	width:5%;
}
</style>

<?php
	
	(isset($_POST['txtPromoCodeDesc']) ? $scodedesc = $_POST['txtPromoCodeDesc'] : $scodedesc = "");
	(isset($_POST['txtProductCode']) ? $sproductcode = $_POST['txtProductCode'] : $sproductcode = "");

?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		    <td width="70%" class="txtgreenbold13" align="Left"></td>
			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Single Line Promo</a></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<?php
if (isset($_GET['errmsg'])) 
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
?>
<?php if (isset($_GET['msg'])): ?>
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
<?php endif; ?>
<br>
<form name="frmViewSingleLine" method="post" action="index.php?pageid=60" style="min-height:505px">
	<div style="width:95%; margin:auto;">
		<table width="40%"  border="0" align="left" cellpadding="0" cellspacing="0">
			<tr>
				<td class="tabmin"></td> 
				<td class="tabmin2"><div align="left" class="txtredbold padl5">Action</div></td>
				<td class="tabmin3">&nbsp;</td>
			</tr>
		</table>
		<div style="clear:both;"></div>
		<table width="40%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
			<tr>
				<td>
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>&nbsp;</td>
						<td width="5%">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>			
					<tr>
						<td height="20" align="right">
							<strong>Promo Code / Description</strong>
						</td>
						<td align="center">:</td>
						<td height="20" style="padding-top:3px;">
							<input name="txtPromoCodeDesc" type="text" class="txtfield" id="txtPromoCodeDesc" value="" size="30">
						</td>
					</tr>	 
					<tr>
						<td height="20" align="right">
							<strong>Item Code</strong>
						</td>
						<td align="center">:</td>
						<td height="20" style="padding-top:3px;">
							<input name="txtProductCode" type="text" class="txtfield" id="txtProductCode" value="" size="30" >
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td height="20" style="padding-top:3px;">
							<input name="btnSearch" onclick = "return xbtnSearch();"  type="submit" class="btn" value="Search">
						</td>
					</tr>
					<tr>
						<td height="20" colspan="3">&nbsp;</td>
					</tr>	
					</table>
				</td>
			</tr>
		</table>
		<div style="clear:both;"></div>
	</div>
	
	<br />
	<br />
	
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
	<td class="tabmin2"><div align="left" class="txtredbold padl5">List of Single Line Promos</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<div>
					<table width="100%"   border="0" cellpadding="0" cellspacing="0" id = "DynamicTable">
						<tr align='center' class='txtdarkgreenbold10' style='background:#FFDEF0;'>
							<td width='4%' height='20' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>			
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>			
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>			
						</tr>
						<tr>
							<td colspan='5' height='30' class='borderBR'><div align='center'><span class='txtredsbold'>Fetching Data Please wait.</span></div></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>

<table width="95%"  border="0" align="center" cellpadding="0" style="margin-top:10px;">
	<tr><td id = "pagination"></td></tr>
</table>	
</form>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
		<input name='btnCreate' type='submit' class='btn' value='Create New' onclick="location.href='index.php?pageid=61'; return false;">
	</td>			
</tr>
</table>
<br>

<div id="dialogmessage"></div>
