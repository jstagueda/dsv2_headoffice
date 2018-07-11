<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>
<?php
	require_once "../../initialize.php";
	// include IN_PATH.DS."scChangePromoPopUpSO.php";
	
	global $database;
	$sessionID = session_id();
	
	$pid = (empty($_GET['pid'])?0:$_GET['pid']);
	$prid = (empty($_GET['prid'])?0:$_GET['prid']);
	$newpromoid = (empty($_GET['newpromoid'])?null:$_GET['newpromoid']);
	$elemid = (empty($_GET['elemid'])?0:$_GET['elemid']);
	$pedid = (empty($_GET["pedid_$newpromoid"])?0:$_GET["pedid_$newpromoid"]);
	
	
	$rsGetPromoList = $sp->spSelectChangePromoPopup($database, $pid, $prid, $sessionID);
	if (empty($newpromoid))
	{
		// $rsGetPromoList = $sp->spSelectChangePromoPopup($database, $pid, $prid, $sessionID);
	}
	else 
	{
		$sp->spUpdatetmpSODetailsPromo($database,$pid,$prid,$newpromoid,$pedid,$sessionID);
		
		$rs = $database->execute("SELECT Code FROM promo WHERE ID=$newpromoid");
		$row = $rs->fetch_object();
		
		$code = $row->Code;
		?>
		<script language="javascript">
		if (window.opener && !window.opener.closed)
		{
			
			elem = "<?php echo $elemid; ?>";
			link = window.opener.document.getElementById(elem);
			link.innerHTML = "<?php echo $code; ?>";
			link.setAttribute("onclick", "changepromo(<?php echo "'$elemid', $pid, $newpromoid"; ?>)");

			window.opener.computebestprice(0);
		}
		window.close();
		</script>
		<?php
	}
?>
<script language="javascript">

function save()
{
	if (confirm('Are you sure you want to save this transaction?')) 
	{
		document.forms['myForm'].submit();
	}
}
</script>
<br>
<form id="myForm" method="get" action="sales_changepromopopup.php">
<input type="hidden" name="pid" value="<?php echo $pid; ?>" />
<input type="hidden" name="prid" value="<?php echo $prid; ?>" />
<input type="hidden" name="elemid" value="<?php echo $elemid; ?>" />
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
	<tr>		
	<td  valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="txtredbold">Applicable Promos</td>
				<td>&nbsp;</td>
			</tr>
			</table>
			</td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
	</table>
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
	<tr>
	<td valign="top" class="bgF9F8F7">
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">	
		<tr>
		<td><div class="scroll_300">
				<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 " border="0">
				<tr height="22">
					<td width="5" align="center" class="borderBR" >&nbsp;</td>
					<td width="15%" align="center" class="borderBR" >Promo Code</td>
					<td width="10%" align="center" class="borderBR">Start Date</td>
					<td width="10%" align="center" class="borderBR">End Date</td>
					<td width="20%" align="center" class="borderBR">Criteria</td>
					<td width="30%" align="center" class="borderBR">Description</td>
					<td width="10%" align="center" class="borderBR">Amount/Unit</td>
					<td width="5%" align="center" class="borderBR">%/UOM</td>
				</tr>
				<?php
					if($rsGetPromoList->num_rows)
					{
						$tmpID = 0;
						while($row = $rsGetPromoList->fetch_object())
						{
							
							$promoid = $row->PromoID;
							$code = $row->Code;
							$startdate = $row->StartDate;
							$enddate = $row->EndDate;
							$name = $row->Name;
							$description = $row->Description;
							$amountunit = $row->AmountUnit;
							$uom = $row->UOM;
							$pedid = $row->PromoEntitlementDetailID;
							
							$checked = $prid==$promoid?"checked":"";
							echo "
							<tr height=\"22\">
								<td width=\"5\" align=\"center\" class=\"borderBR\">
									<input type=\"radio\" name=\"newpromoid\" value=\"$promoid\" $checked />
									<input type=\"hidden\" name=\"pedid_$promoid\" value=\"$pedid\" />
								<td width=\"15%\" align=\"left\" class=\"borderBR\" >$code</td>
								<td width=\"10%\" align=\"center\" class=\"borderBR\">$startdate</td>
								<td width=\"10%\" align=\"center\" class=\"borderBR\">$enddate</td>
								<td width=\"20%\" align=\"center\" class=\"borderBR\">$name</td>
								<td width=\"30%\" align=\"center\" class=\"borderBR\">$description</td>
								<td width=\"10%\" align=\"center\" class=\"borderBR\">$amountunit</td>
								<td width=\"5%\" align=\"center\" class=\"borderBR\">$uom</td>
							</tr> ";
						}
					}
				?>
				</table>
			</div></td></tr></table>
	</td>
	</tr>
	</table>
	
	</td>
	</tr>
</table>
<br/>
<center>
<input name="btnSave" type="button"  class="btn" value="Save" onclick="save()"/>
<input name="btnCancel" type="button"  class="btn" value="Cancel" onclick="window.close()"/>
</center>
</form>