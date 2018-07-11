
<?php
	global $database;
	include IN_PATH.'scRecordInvCountDetails.php';
?>

<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxCreateInventoryCount.js"  type="text/javascript"></script>
<script language="javascript" src = "js/inv_RecordInvCountDetails.js" type="text/javascript"></script>
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
}

-->
</style>



<style type="text/css">
#wrapper, #confirmBox 
{
  	height: 100px;
  	width: 300px;
}

#wrapper 
{
  	bottom: 50%;
  	right: 50%;
  	position:
  	absolute;
}
#confirmBox 
{
  	left: 45%;
  	position: absolute;
  	top: 50%;
	z-index: 1;
	visibility: hidden;
	background: #D4D0C8;
	color: #00000;
	border: 1px;
	text-align: center;
	font-weight: bold;
	border-style: solid;
	border-color: #00000;
	
}
</style>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">

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
    		<td class="txtgreenbold13">Record Inventory Count</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
        	<td>
			  	<?php 
			  		if (isset($_GET['msg']))
				  	{
				  		$message = strtolower($_GET['msg']);
					  	$success = strpos("$message","success"); 
					  	echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'><b>".$_GET['msg']."</b></div><br>";
				  	} 
			  	?> 
		  	</td>
	  	</tr>
	  	</table>
	  	
	 	<form name="frmRecInvCountDetails" method="post" action="index.php?pageid=100.1&invid=<?php echo $invID;?>&tid=<?php echo $_GET['tid'];?>&prodlist=<?php echo $_GET['prodlist'];?>&whid=<?php echo $_GET['whid'];?>&locid=<?php echo $_GET['locid'];?>&add=<?php echo $_GET['add'];?>">
	 		<input type="hidden" name="hTxnID" value="<?php echo $_GET['tid']; ?>" />
	 		<input type="hidden" name="hStatID" value="<?php echo $statusid; ?>" />
	 		<input type="hidden" name="hInvStatID" value="<?php echo $statusid_inv; ?>" />
	 		<input type="hidden" name="hProdListID" value="<?php echo $_GET['prodlist'];  ?> "/>
	 		<input type="hidden" name="hWhouseID" value="<?php echo $warehouseid; ?>" />
	 		<input type="hidden" name="hLocationID" value="<?php echo $locationid; ?>" />
	 		<input type="hidden" name="hUOM" value="<?php echo $uomname; ?>" />
      	  	
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        	<tr>
          		<td class="tabmin">&nbsp;</td>
          		<td class="tabmin2 txtredbold">General Information</td>
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
          				<td width="50%" valign="top">
          					<table width="100%"  border="0" cellspacing="1" cellpadding="0">
            				<tr>
              					<td width="25%" height="20" class="txt10">Reference No. :</td>
              					<td width="75%" height="20"><input name="txtRefNo" type="text" readonly="readonly" class="txtfield" id="txtRefNo" size="20" maxlength="20" value="<?php  echo $transno;?>" /></td>
            				</tr>
            				<tr>
              					<td height="20" class="txt10">Document No. :</td>
              					<td height="20"><input type="hidden"  name="hdnWarehouseID" value="<?php echo $warehouseid; ?>"/>
              					<input name="txtDocNo" type="text"  readonly="readonly" class="txtfield" id="txtDocNo" size="20" maxlength="20" value="<?php  echo $docno;?>" /></td>
        					</tr>
            				<tr>
              					<td height="20" class="txt10">Transaction Date :</td>
              					<td height="20"><input name="startDate" type="text" class="txtfield" id="startDate" size="20" readonly="yes" value="<?php echo $transdate; ?>" />
          					</tr>
            				<tr>
              					<td height="20" class="txt10">Warehouse :</td>
              					<td height="20"><input name="txtWarehouse" type="text"  readonly="readonly" class="txtfield" id="txtWarehouse" size="20" maxlength="20" value="<?php  echo $warehouse;?>" /></td>
            				</tr>
            				<?php
            					//check status of inventory
								$rs_freeze = $sp->spCheckInventoryStatus($database);
								if ($rs_freeze->num_rows)
								{
									$cnt_inv = $rs_freeze->num_rows;
									while ($row = $rs_freeze->fetch_object())
									{
										$statusid_inv = $row->StatusID;			
									}		
								}
								else
								{
									$cnt_inv = 0;
									$statusid_inv = 20;
								}
            				?>
            				<?php if($statusid_inv == 20) { ?>
            				<tr>
              					<td height="30" class="txt10">&nbsp;</td>			  
              					<td height="30"><input name="btnFreeze" type="submit" id="btnFreeze" class="btn" value="Freeze Inventory" onclick="return confirmFreeze(<?php echo $statusid_inv; ?>);"></td>
            				</tr>
            				<?php } ?>
          					</table>
      					</td>
          				<td valign="top">
          					<table width="100%"  border="0" cellspacing="1" cellpadding="0">
            				<tr>
              					<td width="20%" height="20" valign="top" class="txt10">Remarks :</td>
              					<td width="80%" height="20"><textarea name="txtRemarks" cols="40" rows="3" class="txtfieldnh" id="txtRemarks" ><?php  echo $remarks; ?></textarea></td>
            				</tr>
          					</table>
      					</td>
    				</tr>
    				<tr>
          				<td colspan="2">&nbsp;</td>
        			</tr>
    				</table>
				</td>
			</tr>
			</table>
 			</form>
      		<br>
      		<!-- START PRODUCT LIST -->
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
      		<tr>
      			<td class="tabmin">&nbsp;</td>
          		<td class="tabmin2 txtredbold">Product List</td>
          		<td class="tabmin3">&nbsp;</td>
        	</tr>
      		</table>
      		<!--<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
      		<tr>
      				<td height="50" class="bgF9F8F7">
      					<form name="frmproduct" action="index.php?pageid=100.1&tid=<?php echo $_GET['tid'];?>&prodlist=<?php echo $_GET['prodlist'];?>&whid=<?php echo $_GET['whid'];?>&locid=<?php echo $_GET['locid'];?>&add=<?php echo $_GET['add'];?>" method="post">
          				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
          				<tr>
  							<td height="10">&nbsp;</td>
  							<td height="10"></td>
  						</tr>
          				<tr> 
          					<td width="10%" class="txt10 padl5" algin="left"> Search:</td>
          					<input type="hidden" name="hdnicid" value="<?php echo $invID; ?>" />
      						<td width="90%" class="txt10"><input name="txtSearch" type="text" class="txtfield" size="30" style="width:140px " value="<?php echo $vSearch; ?>" /></td>
      					</tr>
      					
			      		<tr>
			      			<td class="txt10 padl5" algin="left">Warehouse:</td>
			      			<td height="20">
			      				<input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
				                <div id="dvWarehouse">
				                	<input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
				                  	<select name="lstWarehouse" id="lstWarehouse" style="width:140px " class="txtfield" onChange="document.frmproduct.submit();">
										<option value="0" selected>[SELECT HERE]</option>
						                <?php 
											if ($rs_warehouse->num_rows)
											{
												while ($row = $rs_warehouse->fetch_object())	
												{
													if (isset($_POST['lstWarehouse']))
			                                  		{
			                                  			if ($_POST['lstWarehouse'] == $row->ID)
			                                  			{
			                                  				$sel = 'selected';		                                  				
			                                  			}		              
			                                  			else
			                                  			{
			                                  				$sel = '';
			                                  			}                    			
			                                  		}
													echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
												}
												$rs_warehouse->close();
											}
										?>
									</select>
								</div>
			      			</td>
			  			</tr>
  						<tr>
      						<td class="txt10 padl5" algin="left">Location:</td>
      						<td height="20"><input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
      							<div id="dvLocation">
      							<select name="lstLocation" style="width:140px " class="txtfield">
									<option value="0" selected>[SELECT HERE]</option>
		                			<?php 
										if ($rs_location->num_rows)
										{
											while ($row = $rs_location->fetch_object())	
											{
												if ($locationid == $row->ID) $sel = "selected";
												else $sel = "";
												echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
											}
											$rs_location->close();
										}
									?>
   				  				</select>
       				  			&nbsp;
	     						<input name="btnSearch" type="submit" class="btn" value="Search" > 
	     						</div>
  							</td>
  							<td align="left" class="txt10">&nbsp;</td>
						</tr>
						<tr>
      						<td class="txt10 padl5" algin="left">Add Product: </td>
      						<td height="20">
							<input name='hdnSearchProductID' type='hidden' value=""/>
							<input type="text" class="txtfield" name="txtSearchProdCode" id="txtSearchProdCode"   size="30" style="width:140px "/>
							<span id="indicator3" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="search_product1" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								//<![CDATA[
									var search_item = new Ajax.Autocompleter('txtSearchProdCode', 'search_product1', 'includes/scAddInventoryCountProduct.php', {afterUpdateElement : getSearchList, indicator: 'indicator3'});																			
								//]]>
							</script>
							
							</td>
  							<td align="left" class="txt10">&nbsp;</td>
						</tr>
					
     				
				<tr></tr>
  				</table>
  				</form>
			</td>
		</tr>
		</table>-->
		<form name="frmList" method="post" action="includes/pcRecordInvCount.php?tid=<?php echo $_GET['tid'];?>&prodlist=<?php echo $_GET['prodlist']; ?>&invid=<?php echo $invID;?>&locid=<?php echo $_GET['locid']; ?>">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
        		<tr>
        			<td class="tab">
        				<table width="100%"  border="0" cellpadding="0" cellspacing="" class="txtdarkgreenbold10">
            			<tr align="center">
              				<td width="10%" height="20" class="bdiv_r" align="center">Count Tag No.</td>
              				<td width="20%" height="20" class="bdiv_r padl5" align="left">Item Code</td>
              				<td width="30%" height="20" class="bdiv_r padl5" align="left">Item Description</td>
              				<td width="20%" height="20" class="bdiv_r padl5" align="left">Location</td>
              				<td width="10%" height="20" class="bdiv_r" align="center">UOM</td>
              				<td width="10%" height="20" align="center">Quantity</td>
          				</tr>
            			</table>
    				</td>
				</tr>
        		<tr>
        			<td>
            			<input type="hidden" name="hdnSearch" value="<?php echo $vSearch; ?>" />
	        			<input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
	        			<input type="hidden" name="hdnProdlist" value="<?php echo $prodlist; ?>" />
            			<div id="dvProductList" class="scroll_300">
            			<input type="hidden" name="hrowcnt" value = '1' id="hrowcnt">
              			<table width="100%" id="dynamicTable"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
              			<tr class="">
	                    	<td width="10%" height="25" align="center" class="borderBR">
	                    		<input name='hdnProductID1' type='hidden' value=""/>
	                    		<input name="hdnicdID1" type="hidden" value=""/> <!--onKeyPress="return disableEnterKey(this, event)"-->
	                    		<input name="txtCountTag1" id="txtCountTag1" onkeypress = "return enter_key(event,1);" type="text" class="txtfield3" size="12" style="text-align:center;" value=""/>
	                    		<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
								<div id="counttag_choices1" class="autocomplete" style="display:none"></div>
								<script type="text/javascript">							
									//<![CDATA[
		                        		var counttag_choices = new Ajax.Autocompleter('txtCountTag1', 'counttag_choices1', 'includes/scCountTagAjax.php?index=1&tid='+<?php echo $icID; ?>+'&whid='+<?php echo $warehouseid; ?>+'&locid='+<?php echo $locationid; ?>, {afterUpdateElement : getSelectionCountTagList, indicator: 'indicator1'});																			
		                        	//]]>
		                        </script>
                    		</td>
	                    	<td width="20%" height="25" align="left"  class="padl5 borderBR"><!--onKeyPress="return disableEnterKey(this, event)"-->
	                    		<input name="txtItemCode1" id="txtItemCode1" type="text" class="txtfield" style="width:220px;" value=""/>
	                    		<span id="indicatori" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
								<div id="itemcode_choices1" class="autocomplete" style="display:none"></div>
								<script type="text/javascript">							
									//<![CDATA[
		                        		var itemcode_choices = new Ajax.Autocompleter('txtItemCode1', 'itemcode_choices1', 'includes/scItemCodeAjax.php?index=1&tid='+<?php echo $icID; ?>+'&whid='+<?php echo $warehouseid; ?>+'&locid='+<?php echo $locationid; ?>, {afterUpdateElement : getSelectionItemCodeList, indicator: 'indicatori'});																			
		                        		//var itemcode_choices = new Ajax.Autocompleter('txtItemCode1', 'itemcode_choices1', 'includes/scCountTagAjax.php?index=1&tid='+<?php echo $icID; ?>+'&whid='+<?php echo $warehouseid; ?>+'&locid='+<?php echo $locationid; ?>, {afterUpdateElement : getSelectionItemCodeList, indicator: 'indicatori'});																			
		                        	//]]>
		                        </script>
	                    	</td>
	                    	<td width="30%" height="25" align="left" class="padl5 borderBR"><input name="txtItemName1" type="text" class="txtfield" style="width:360px;" value="" readonly="yes"/></td>
	                    	<td width="20%" height="25" align="left" class="padl5 borderBR">
	                    		<select name="cbolocation1" id = "cbolocation1" class="txtfield" onchange = "changeselection(this)"> 
	                    		<option value="0" selected>[SELECT HERE]</option>
	                    		<?php 
	                    				$locationID = "" ;
	                    				$locationName = "";
	                    				$locationCnt = 0;
										if ($rscboLocation->num_rows)
										{
											while ($row = $rscboLocation->fetch_object())	
											{
												$locationID = $locationID.$row->ID.'_';
												$locationName = $locationName.$row->Name.'_';
												$locationCnt ++ ;
												//echo $locationid;
												//echo $row->ID;
												if ($locationid == $row->ID) $sel = "selected";
												else $sel = "";
												echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
											}
											$rscboLocation->close();
										}
									?>
	                    		
	                    		</select>
	                    		<input type="hidden" name="hlocationName"  id="hlocationName" value="<?php echo substr($locationName,0,-1);?>" >
	                    		<input type="hidden" name="hlocationID" id="hlocationID" value="<?php echo substr($locationID,0,-1);  ?>" >
                    			<input type="hidden" name="hlocationcnt" id="hlocationcnt" value="<?php echo $locationCnt ;  ?>" >
	                    	</td>
	                    	<td width="10%" height="25" align="center" class="borderBR"><input name="txtUOM1" type="text" class="txtfield" style="width:80px; text-align:center;" value="<?php echo $uomname; ?>" readonly="yes"/></td>
	                    	<td width="10%" height="25" align="center" class="borderBR"><input name="txtQuantity1" id="txtQuantity1" type="text" class="txtfield3" size="12" style="text-align:right" maxlength="20" value="" onKeyPress="return numbersonly(this, event);" <?php if($statusid_inv == 21) { echo "enabled"; } else { echo "disabled"; } ?>></td> <!-- onFocus="return selectField(2);" -->
	                    </tr>
            			</table>
      					</div>
      				</td>
				</tr>
				<div id="confirmBox">
				<BR>				
					<p style="text-align: 'center'; font: '16pt'; courier; color: 'blue'">Continue?</p>					
					<p style="text-align: 'center'; font: '16pt'; courier; color: 'blue'">
						<input type="button" onclick="answer(1)" value="1" class="btn">
						<input type="button" onclick="answer(2)" value="2" class="btn">
						<input type="button" onclick="answer(3)" value="3" class="btn">
					</p>
				</div>
    			</table>
			</td>
		</tr>
		</table>
		<br>
		<table width="95%" align="center" border="0" cellpadding="0" cellspacing="">
		<tr>
			<td align="center">
				<input name="hdnremarks" type="hidden" value=""/>
				<input name="hdnlid" type="hidden" value="<?php echo $locationid;?>"/>
				<input name="hdnwid" type="hidden" value="<?php echo $warehouseid;?>"/>
				<input name="hdntid" type="hidden" value="<?php echo $_GET['tid']; ?>"/>
			
				<input name="btnPrint" type="button" class="btn" value="Variance Report" onclick="tester(this)" >
				<?php if($statusid_inv == 21) {?>
					<input name="btnSave" type="submit" class="btn" onclick="return confirmAdd();" value=" Save "/>
				<?php } ?>
				<input name="btnCancel" type="submit" class="btn" onclick="return confirmCancel();" value=" Cancel "/>
			</td>
		</tr>
		</table>
		</form>
		
		<div id="addItem" title="Add Item">
			<input type="hidden" name="hAddProdID" id="hAddProdID" />
			<div id="addItemtable"></div>
		</div>

	</td>
</tr>
</table>
<br />
<br />

<script type="text/javascript">
function tester(button) 
{
	myConfirm("Sort Item List by :", "Item Code", "Quantity", "Cancel",
		function(answer) 
		{
			var wid = document.frmList.hdnwid.value;
			var lid = document.frmList.hdnlid.value;
			var tid = document.frmList.hdntid.value;
	
	  		if(answer == 1)
      		{
      			pagetoprint = "pages/inventory/inv_countDetails_print.php?tid="+tid+"&sort=1";
	   			objWin = NewWindow(pagetoprint,'print','900','900','yes');
	   			return false; 
      		}
      		else if(answer == 2)
      		{
      			pagetoprint = "pages/inventory/inv_countDetails_print.php?tid="+tid+"&sort=2";
    	  		objWin = NewWindow(pagetoprint,'print','900','900','yes');
    	  		return false;
	  		}
	  		else
	  		{
	  			return false;	  			
	  		}
  		}
  	);
}
</script>