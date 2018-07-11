<link rel= "stylesheet" type= "text/css" href= "css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "css/calpopup.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script src="js/jxPagingStocks.js" language="javascript" type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>

<?php
	setExpires(0);
	include IN_PATH.DS."scStocks.php";
?>

<script type="text/javascript">
function Item_Search()
{
	var wid = 1;
	//alert($("#search_dealer").val());
	<?php if(isset($_GET['wid'])): ?>
		wid = <?php echo $_GET['wid']; ?>;
	<?php endif; ?>
	$('#txtSearch').autocomplete({
		source:'pages/inventory/inventory_call_ajax/item_for_stocklog.php?wid='+wid+'',
			select: function( event, ui ) {
				$( "#txtSearch").val(ui.item.ProductCode);
			
			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			 return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.ProductCode + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
	}
}
function MM_jumpMenu(targ,selObj,restore)
{ 		
	//var docno = document.frmStocks.txtDocNo.value;
	//var remarks = document.frmStocks.txtRemarks.value;
	var search = document.frmStocks.txtSearch.value;

	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"&search=" + search+  "#MMHead" +"'");
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
    		<td class="txtgreenbold13">View Stocks</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmStocks" method="post" action="index.php?pageid=33&wid=<?php if (isset($_GET['wid'])){ echo $_GET['wid'];}else{echo $tmpWid;}?>&lid=<?php if (isset($_GET['lid'])){ echo $_GET['lid'];}else{echo '0';}?>&plid=<?php if (isset($_GET['plid'])){ echo $_GET['plid'];}else{echo '0';}?>&cid=<?php if (isset($_GET['cid'])){ echo $_GET['cid'];}else{echo '0';}?>&bid=<?php if (isset($_GET['bid'])){ echo $_GET['bid'];}else{echo '0';}?>&is=<?php if (isset($_GET['is'])){ echo $_GET['is'];}else{echo '0';}?>&pmgid=<?php if (isset($_GET['pmgid'])){ echo $_GET['pmgid'];}else{echo '0';}?>&brid=<?php if (isset($_GET['brid'])){ echo $_GET['brid'];}else{echo '0';}?>&pgid=<?php if (isset($_GET['pgid'])){ echo $_GET['pgid'];}else{echo '0';}?>&brdid=<?php if (isset($_GET['brdid'])){ echo $_GET['brdid'];}else{echo '0';}?>">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
    		<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr><td height="20">
				<tr>
          			<td height="20" align="right"><strong>Location :</strong></td>
          			<td height="20" align="right">&nbsp;</td>
          			<td height="20" align="left">
            			<select name="cboWarehouse" id = "cboWarehouse" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
							<?php
							if ($rs_warehouse->num_rows)
							{
								while ($row = $rs_warehouse->fetch_object())
								{
						 			($_GET['wid']== $row->ID) ? $sel = "selected" : $sel = "";
						 			echo "<option value='index.php?pageid=33&wid=".$row->ID."&plid=$tmpPlid&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&brid=$tmpbrid&pgid=$tmppgid&brdid=$tmpbrdid' $sel >".$row->Name."</option>";
								}
								$rs_warehouse->close();
							}
						?>
            			</select>
          			</td>
        		</tr>
				<input type = "hidden" name = "cboLocation" value = "0" />
				<?php /* <td align="right"><strong>Location</strong></td>
							<tr>
								
								<td width="2%" height="20">:</td>
								<td align="left">	
								<select name="cboLocation" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
									<option value="'index.php?pageid=33&wid=<?php echo $tmpWid;?>&plid=<?php echo $tmpPlid;?>&lid=0&cid=<?php echo $tmpCid;?>&bid=<?php echo $tmpBid;?>&is=<?php echo $tmpIs;?>&pmgid=<?php echo $tmpPmg;?>&brid=<?php echo $tmpbrid;?>&pgid=<?php echo $tmppgid;?>&brdid=<?php echo $tmpbrdid;?>" selected>[SELECT HERE]</option>
									//<?php 											
										if($rs_location->num_rows):
											while($row= $rs_location->fetch_object()):									
												if ($_GET['lid'] == $row->ID) 
													$sel = "selected";
												else 
													$sel = "";
											echo "<option value='index.php?pageid=33&wid=$tmpWid&plid=$tmpPlid&lid=".$row->ID."&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&brid=$tmpbrid&pgid=$tmppgid&brdid=$tmpbrdid' $sel >".$row->Name."</option>";
											endwhile;
										endif;
									//?>
								</select>			
								</td>
							</tr>*/
				?>
        		<tr>
					<td align="right"><strong>Branch</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboBranch" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
						<option value="index.php?pageid=33&wid=<?php echo $tmpWid;?>&plid=<?php echo $tmpPlid;?>&lid=<?php echo $tmpLid;?>&cid=<?php echo $tmpCid;?>&bid=0&is=<?php echo $tmpIs;?>&pmgid=<?php echo $tmpPmg;?>&brid=<?php echo $tmpbrid;?>&pgid=<?php echo $tmppgid;?>&brdid=<?php echo $tmpbrdid;?>" selected>[SELECT HERE]</option>
						<?php 											
							if($rs_branch->num_rows):
								while($row= $rs_branch->fetch_object()):									
									($_GET['bid'] == $row->ID)?$sel = "selected" : $sel = "";
								echo "<option value='index.php?pageid=33&wid=$tmpWid&plid=$tmpPlid&lid=$tmpLid&cid=$tmpCid&bid=".$row->ID."&is=$tmpIs&pmgid=$tmpPmg&brid=$tmpbrid&pgid=$tmppgid&brdid=$tmpbrdid' $sel >".$row->Name."</option>";
								endwhile;
							endif;
						?>
						</select>			
					</td>
				</tr>				
				<tr>
					<td align="right"><strong>Campaign</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboCampaign" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
						<option value="index.php?pageid=33&wid=<?php echo $tmpWid;?>&plid=<?php echo $tmpPlid;?>&lid=<?php echo $tmpLid;?>&cid=0&bid=<?php echo $tmpBid;?>&is=<?php echo $tmpIs;?>&pmgid=<?php echo $tmpPmg;?>&brid=<?php echo $tmpbrid;?>&pgid=<?php echo $tmppgid;?>&brdid=<?php echo $tmpbrdid;?>" selected>[SELECT HERE]</option>
						<?php
						
							if ($rs_campaign->num_rows):
								while ($row = $rs_campaign->fetch_object()):
									($_GET['cid'] == $row->ID)?$sel = "selected" : $sel = "";
									echo "<option value='index.php?pageid=33&wid=$tmpWid&plid=$tmpPlid&lid=$tmpLid&cid=".$row->ID."&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&brid=0&pgid=0&brdid=0' $sel >".$row->Code." - ".$row->Name."</option>";			
								endwhile;
									$rs_campaign->close();
							endif;
						?>
						</select>			
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Brochure</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboBrochure" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
							<option value="index.php?pageid=33&wid=<?php echo $tmpWid;?>&plid=<?php echo $tmpPlid;?>&lid=<?php echo $tmpLid;?>&cid=<?php echo $tmpCid;?>&bid=<?php echo $tmpBid;?>&is=<?php echo $tmpIs;?>&pmgid=<?php echo $tmpPmg;?>&brid=0&pgid=0&brdid=0" selected>[SELECT HERE]</option>
							<?php
							if ($rs_brochure->num_rows):
								while ($row = $rs_brochure->fetch_object()):
						 			($_GET['brid']== $row->ID) ? $sel = "selected" : $sel = "";
						 			echo "<option value='index.php?pageid=33&wid=$tmpWid&plid=$tmpPlid&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&brid=".$row->ID."&pgid=0&brdid=0' $sel >".$row->Code." - ".$row->Name."</option>";
								endwhile;
								$rs_brochure->close();
							endif;
							?>
            			</select>			
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Page</strong></td>
					<td width="2%" height="20">:</td>					
					<td align="left">
						<select name="cboPage" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
							<option value="index.php?pageid=33&wid=<?php echo $tmpWid;?>&plid=<?php echo $tmpPlid;?>&lid=<?php echo $tmpLid;?>&cid=<?php echo $tmpCid;?>&bid=<?php echo $tmpBid;?>&is=<?php echo $tmpIs;?>&pmgid=<?php echo $tmpPmg;?>&brid=<?php echo $tmpbrid;?>&pgid=0&brdid=0" selected>[SELECT HERE]</option>
							<?php
							if ($rs_brochurepage->num_rows):
								while ($row = $rs_brochurepage->fetch_object()):
						 			($_GET['pgid']== $row->PageNum) ? $sel = "selected" : $sel = "";
						 			echo "<option value='index.php?pageid=33&wid=$tmpWid&plid=$tmpPlid&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&brid=$tmpbrid&pgid=".$row->PageNum."&brdid=".$row->ID."' $sel>".$row->PageNum."</option>";
								endwhile;
								$rs_brochurepage->close();
							endif;?>
            			</select>			
					</td>
				</tr>
				<tr>
					<td align="right"><strong>Product Line</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboProdLine" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
						<option value="index.php?pageid=33&wid=<?php echo $tmpWid;?>&plid=0&lid=<?php echo $tmpLid;?>&cid=<?php echo $tmpCid;?>&bid=<?php echo $tmpBid;?>&is=<?php echo $tmpIs;?>&pmgid=<?php echo $tmpPmg;?>&brid=<?php echo $tmpbrid;?>&pgid=<?php echo $tmppgid;?>&brdid=<?php echo $tmpbrdid;?>" selected>[SELECT HERE]</option>
						<?php 
											
							if($rs_productline->num_rows):
								while($row= $rs_productline->fetch_object()):									
									($_GET['plid'] == $row->ID)?$sel = "selected" : $sel = "";
								     echo "<option value='index.php?pageid=33&wid=$tmpWid&plid=".$row->ID."&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&pmgid=$tmpPmg&brid=$tmpbrid&pgid=$tmppgid&brdid=$tmpbrdid' $sel >".$row->Code."-".$row->Name."</option>";
								endwhile;
							endif;
						?>
						</select>			
					</td>
				</tr>	
				<tr>
					<td align="right"><strong>Product Market Group</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboPMG" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
						<option value="index.php?pageid=33&wid=<?php echo $tmpWid;?>
															&plid=<?php echo $tmpPlid;?>
															&lid=<?php echo $tmpLid;?>
															&cid=<?php echo $tmpCid;?>
															&bid=<?php echo $tmpBid;?>
															&is=<?php echo $tmpIs;?>
															&brid=<?php echo $tmpbrid;?>
															&pgid=<?php echo $tmppgid;?>
															&brdid=<?php echo $tmpbrdid;?>
															&pmgid= 0" selected>[SELECT HERE]</option>
						<?php		
							if($rs_pmg->num_rows):
								while($row= $rs_pmg->fetch_object()):									
									($_GET['pmgid'] == $row->ID)?$sel = "selected" : $sel = "";
								echo "<option value='index.php?pageid=33&wid=$tmpWid&plid=$tmpPlid&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&is=$tmpIs&brid=$tmpbrid&pgid=$tmppgid&pmgid=".$row->ID."&brdid=$tmpbrdid' $sel >".$row->Name."</option>";
								endwhile;
							endif;
						?>
						</select>			
					</td>
				</tr>	
				<tr>
					<td align="right"><strong>Inventory Status</strong></td>
					<td width="2%" height="20">:</td>
					<td align="left">
						<select name="cboStatus" class="txtfield" onChange="MM_jumpMenu('parent',this,0)">
						<option value="index.php?pageid=33&wid=<?php echo $tmpWid;?>&plid=<?php echo $tmpPlid;?>&lid=<?php echo $tmpLid;?>&cid=<?php echo $tmpCid;?>&bid=<?php echo $tmpBid;?>&is=0&pmgid=<?php echo $tmpPmg;?>&brid=<?php echo $tmpbrid;?>&pgid=<?php echo $tmppgid;?>&brdid=<?php echo $tmpbrdid;?>" selected>[SELECT HERE]</option>
						<?php 
											
							if($rs_status->num_rows):
								while($row= $rs_status->fetch_object()):									
									($_GET['is'] == $row->ID)?$sel = "selected" : $sel = "";
								echo "<option value='index.php?pageid=33&wid=$tmpWid&plid=$tmpPlid&lid=$tmpLid&cid=$tmpCid&bid=$tmpBid&is=".$row->ID."&pmgid=$tmpPmg&brid=$tmpbrid&pgid=$tmppgid&brdid=$tmpbrdid' $sel >".$row->Code." - ".$row->Name."</option>";
								endwhile;
							endif;
						?>
						</select>			
					</td>
				</tr>				
        		<tr>
          			<td width="10%" height="20" align="right"><strong>Search  </strong></td>
          			<td width="2%" height="20">:</td>
          			<td width="38%" height="20" align="left">
						<input type = "text"  id = "txtSearch" name = "txtsearch" onkeypress = "Item_Search()" value = "<?php echo $vSearch;?>" class="txtfield">
						&nbsp;&nbsp;
						<input name="btnsearch" type="submit" class="btn" value="Search" />
         			</td>
        		</tr>        		
			<td height="20">
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
	<?php 
			if(isset($_GET['lid']))
					$vLocation = $_GET['lid'];		
			else
				$vLocation = 0;	
				
			if(isset($_GET['wid']))
					$warehouseid = $_GET['wid'];		
			else
				$warehouseid = 1;	
	?>
	
	<script>
		//Onload start the user off on page one
		window.onload = showPage("1", "<?php echo $vSearch; ?>","<?PHP echo $warehouseid; ?>", "<?PHP echo $tmpLid; ?>","<?PHP echo $tmpBid; ?>","<?PHP echo $tmpCid; ?>",0,0,"<?PHP echo $tmpPlid; ?>","<?PHP echo $tmpPmg; ?>","<?PHP echo $tmpIs; ?>","<?PHP echo $tmpbrdid; ?>");    
	</script>
	</form>
	</td>
</tr>
</table>

<table width="95%"  border="0" align="center">
	<tr>
		<td height="20" align="center">
			<input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $vSearch;?>','<?php echo $warehouseid;?>','<?php echo $tmpLid;?>','<?php echo $tmpPmg; ?>','<?php echo $tmpIs;?>','<?php echo $tmpPlid; ?>',1)" />
    	</td>
	</tr>
</table>
<br />
