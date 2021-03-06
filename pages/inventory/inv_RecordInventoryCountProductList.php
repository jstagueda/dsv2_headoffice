<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<?php
	require_once "../../initialize.php";
	include IN_PATH.'scConfirmReceiptfromHOProductList.php';
	global $database;
?>

<script language="javascript" type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{ 		
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) 
		selObj.selectedIndex = 0;
}

function checkAll(bin) 
{
	var elms = document.frmProductList.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkInclude[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function checker()
{
	var ml = document.frmProductList;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkInclude[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}

function confirmAdd(linkid)
{
	var prodlist = new Array();
	var url_prod = document.frmProductList.hProdListID.value;
	var url_loc = document.frmProductList.hLocID.value;

	if (url_loc == 0)
	{
		alert ('Select location.');
		return false;
	}
		
	if (!checker())
	{
		alert('Please select product(s) to be added.');
		return false;
	}
	else
	{
		var ml = document.frmProductList;
		var len = ml.elements.length;
		var index = 0;
		
		for (var i = 0; i < len; i++) 
		{
			var e = ml.elements[i];
		    if (e.name == "chkInclude[]" && e.checked == true) 
		    {
				prodlist[index] = e.value;
				index = index + 1;
		    }
		}
		
		if (url_prod != "")
		{
			prodlist = url_prod + ',' + prodlist;			
		}

		linkid = linkid + '&prodlist=' + prodlist;
		if (confirm('Are you sure you want to add product(s)?') == false)
			return false;
		else
			opener.location.href = linkid;
			window.close();
	}		
}
</script>
<br>
<form name="frmProductList" method="post" action="inv_RecordInventoryCountProductList.php?pline=0&tid=<?php echo $_GET['tid'];?>&whid=<?php echo $_GET['whid'];?>&locid=<?php echo $_GET['locid'];?>&prodlist=<?php echo $_GET['prodlist'];?>" >
<input type="hidden" name="hProdListID" value="<?php echo $_GET['prodlist'];?>">
<input type="hidden" name="hLocID" value="<?php echo $_GET['locid'];?>">
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">&nbsp;</td>
	<td class="tabmin2">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="txtredbold">Product List </td>
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
				<td>
					<div id="dvProductList" class="scroll_300">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3">
							<table width="100%" align="center" cellpadding="0" cellspacing="0">
							<!--<tr>
								<td width="25%" height="20"><b>Select Product Line: </b></td>
								<td width="75%" height="20">
									<select name="cboProductLine" style="width:180px" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">							
									<?php
										echo "<option value='inv_RecordInventoryCountProductList.php?pline=0&tid=".$_GET['tid']."&prodlist=".$_GET['prodlist']."&whid=".$_GET['whid']."&locid=".$_GET['locid']."'>[SELECT HERE]</option>";	
										if ($rs_productType->num_rows)
										{
											while ($row = $rs_productType->fetch_object())	
											{
												if ($_GET['pline'] == $row->ID) 
													$sel = "selected";
												else 
													$sel = "";
														
												echo "<option value='inv_RecordInventoryCountProductList.php?pline=".$row->ID."&tid=".$_GET['tid']."&prodlist=".$_GET['prodlist']."&whid=".$_GET['whid']."&locid=".$_GET['locid']."' $sel >".$row->Name."</option>";
											}
											$rs_productType->close();
										} 
									?>
									</select>
								</td>
							</tr>-->
							<tr>
								<td width="20%" height="20"><b>Select Location: </b></td>
								<td width="80%" height="20">
									<select name="cboLocation" style="width:180px" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
									<?php
										echo "<option value='inv_RecordInventoryCountProductList.php?pline=0&tid=".$_GET['tid']."&prodlist=".$_GET['prodlist']."&whid=".$_GET['whid']."&locid=0'>[SELECT HERE]</option>";
										$rs_location = $sp->spSelectLocationWS($database, $_GET['whid']);
										if ($rs_location->num_rows)
										{
											while ($row = $rs_location->fetch_object())
											{
												if ($_GET['locid'] == $row->ID) 
													$sel = "selected";
												else 
													$sel = "";
													
												echo "<option value='inv_RecordInventoryCountProductList.php?pline=0&tid=".$_GET['tid']."&prodlist=".$_GET['prodlist']."&whid=".$_GET['whid']."&locid=".$row->ID."' $sel >".$row->Name."</option>";												
											}
											$rs_location->close();											
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td height="20"><b>Search: </b></td>
								<td height="20">
									<input name="txtSearch" type="text" class="txtfield" size="30" value="<?php echo $search; ?>"/>
									<input name="btnSearch" type="submit" class="btn" value="Go" />
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" height="20">&nbsp;</td>
					</tr>
					<?php if($rs_productList->num_rows) 
					{
					?>
					<tr class='tab'>
						<td width='10%' height='20' align='center'><input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);"></td>
						<td width='20%' height='20' class='padl5' align='left'><strong>Product Code</strong></td>
						<td width='70%' height='20' class='padl5' align='left'><strong>Description</strong></td>
					</tr>
						<?php
							$cnt = 0;
							while ($row = $rs_productList->fetch_object()) 
							{
								$cnt ++;
                                ($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
								echo "<tr class='$alt'>
									<td height='20' class='borderBR' align='center'><input name='chkInclude[]' type='checkbox' id='chkInclude' value='$row->ProductID'></td>
									<td height='20' class='borderBR padl5' align='left'>$row->ProductCode</td>
									<td height='20' class='borderBR padl5' align='left'>$row->Product</td>
								</tr>";
							 }
						 ?>
				  	<tr>
						<td colspan="3">&nbsp;</td>						
					</tr>
					<?php  } else{ ?>
					<tr>
						<td colspan="3" align="center"><font color="#FF0000"><b>No record(s) to display.<b></font></td>
					</tr>
					<?php } ?>
					</table>
					</div>
				</td>
			</tr>
		</table>
   </td>
</tr>
</table>
<br>
<table width="95%" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td height="20"><div align="right"><input name="btnAdd" type="submit" class="btn" value="Add" onclick="javascript: return confirmAdd('../../index.php?pageid=100.1&tid=' + <?php echo $_GET['tid']; ?> + '&whid=' + <?php echo $_GET['whid']; ?> + '&locid=' + <?php echo $_GET['locid']; ?> + '&add=1');"></div></td>
</tr>
</table>
</form>