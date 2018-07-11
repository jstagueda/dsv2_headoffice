<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script language="javascript" src="js/jxCreateOtherIns.js"  type="text/javascript"></script>
<?php
	include IN_PATH.'scCreateInventoryIn.php';
?>
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
      		<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
              	<tr>
                    <td class="txtgreenbold13">Create Inventory In </td>
                    <td>&nbsp;</td>
              	</tr>
            </table>
			<br />
 			<form name="frmInventoryInDetails" method="post" action="includes/pcOtherIns.php" >
          	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  	<td class="tabmin">&nbsp;</td>
                  	<td class="tabmin2">
                    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                              	<td class="txtredbold">General Information</td>
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
          						<td width="50%" valign="top">
                                	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
           								<tr>
                                          	<td height="20" class="txt10">Inventory In Type </td>
                                          	<td>
                                                <select name="cboInventoryInType" style="width:165px; height:20px;" class="txtfield">
                                                    <option value="0">[SELECT HERE]</option>
                                                  	<?php 
                                                      	if ($rs_invintype->num_rows)
                                                      	{
                                                          	while ($row = $rs_invintype->fetch_object())	
                                                          	{
                                                              	if ($rs_invintype == $row->ID) 
																	$sel = "selected";
                                                             	 else 
																 	$sel = "";																
                                                              	echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
                                                          	}
                                                          	$rs_invintype->close();
                                                      	}
                                                  	?>
												</select>
              								</td>
          								</tr>
          								<tr>
                                          	<td height="20" class="txt10">Reference No.</td>
                                          	<td>
                                            	<input type="hidden"  name="hdnWarehouseID" value="<?php echo $warehouseid; ?>"/>
                                            	<input name="txtDocNo" type="text" class="txtfield" id="txtDocNo" size="20" maxlength="20" value="II00000001" disabled="true" />
                                          	</td>
                                        </tr>             
                                        <tr>
                                          	<td height="20" class="txt10">Document No.</td>
                                          	<td>
                                            	<input type="hidden"  name="hdnWarehouseID" value="<?php echo $warehouseid; ?>"/>
                                            	<input name="txtDocNo" type="text" class="txtfield" id="txtDocNo" size="20" maxlength="20" value="" />
                                          	</td>
                                        </tr>
                                        <tr>
                                        	<td height="20" class="txt10">Warehouse</td>
                                        	<td>
                                        		<select name="lstWarehouse" style="width:140px " class="txtfield" <?php echo $dis; ?>>
			                                    	<option value="0" selected>SELECT WAREHOUSE</option>
													<?php 
			                                            if ($rs_warehouse->num_rows)
			                                            {
			                                                while ($row = $rs_warehouse->fetch_object())	
			                                                {
			                                                    if ($warehouseid == $row->ID) 
																	$sel = "selected";
			                                                    else 
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
                                          	<td width="500" height="20" class="txt10">&nbsp;</td>
                                          	<td width="500">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          	<td height="20" class="txt10">&nbsp;</td>
                                          	<td>&nbsp;</td>
                                        </tr>
          							</table>
                                </td>
          						<td valign="top">
                                	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
                                        <tr>
                                          	<td width="500" height="20" class="txt10">Transaction Date</td>
                                          	<td width="500">
                                            	<input name="startDate" type="text" class="txtfield" id="startDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>">
                                            	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'startDate', 'yyyy-mm-dd')" title="Show popup calendar">
                                                	<img src="images/btn_Calendar.gif" width="25" height="19" border="0" align="absmiddle" />
                                                </a>
                                           	</td>
                                        </tr>
                                        <tr>
                                          	<td height="20" valign="top" class="txt10">Remarks</td>
                                          	<td><textarea name="txtRemarks" cols="40" rows="3" class="txtfieldnh" id="txtRemarks"></textarea></td>
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
          	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  	<td height="3" class="bgE6E8D9"></td>
                </tr>
          	</table>
      		<br>     
          	<!--<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
          		<tr>
                    <td class="tabmin">&nbsp;</td>
                    <td class="tabmin2">
                    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                              	<td class="txtredbold">Inventory In Details </td>
                              	<td>
                              		<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
                                  		<tr>
                                    		<td>&nbsp;</td>
                                  		</tr>
                              		</table>
                               	</td>
                            </tr>
            			</table>
                 	</td>
            		<td class="tabmin3">&nbsp;</td>
          		</tr>
        	</table>-->
      		<!--<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl3">
      			<tr>
        			<td valign="top" class="bgF9F8F7">       
                      	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                      		<tr>
                        		<td class="tab ">
                                	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
                                        <tr align="center">
                                            <td width="4%" class="bdiv_r"><input name="chkAll" type="checkbox" id="chkAll" value="1"></td>
                                            
                                            <td width="18%" class="bdiv_r">Product Name </td>
                                            <td width="8%" class="bdiv_r">Booklet No.</td>
                                            <td width="10%" class="bdiv_r">SOH</td>
                                            <td width="9%" class="bdiv_r">UOM</td>
                                            <td width="10%" class="bdiv_r">Loaded Qty</td>
                                            <td width="10%" class="bdiv_r">Unit Price</td>
                                            <td width="8%" class="bdiv_r">Amount</td>								
                                            <td width="19%">Reason</td>
                                      	</tr>
                        			</table>
                             	</td>
                      		</tr>
              				<tr>
                				<td valign="top" class="">
                              	<div id="dvInventoryInDetails">
                                    <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">-->                                       
                                        <!--
                                        <?php
                                          	$rowcount = 0;
                                          	$totqty = 0;
                                          	while ($row = $rs_inventoryin->fetch_object())
                                          	{
                                              	$totqty = $totqty + $row->Qty;
                                            	"<tr align=\"center\">
                                                     <td width=\"4%\">
                                                        <input name=\"chkIID[]\" type=\"checkbox\" id=\"chkAll\" value=\"$row->InventoryID\">
                                                     </td>
                                                     <td width=\"18%\">$row->Product&nbsp;</td>
                                                     <!-- <td width=\"8%\">$row->BookletNo&nbsp;</td> -->
                                                     <td width=\"10%\">".number_format($row->SOH, 0)."&nbsp;</td>
                                                     <td width=\"9%\">$row->UOM&nbsp;</td>
                                                     <td width=\"10%\">$row->Qty&nbsp;</td>
                                                     <td width=\"10%\">".number_format($row->UnitCost,2)."&nbsp;</td>
                   									 <td width=\"8%\">".number_format($row->Amount,2)."&nbsp;</td>                   								     
                                                     <td width=\"19%\">$row->Reason</td>
                                                 </tr>";									
                                            	$rowcount++;
                                         	} 
											$rs_inventoryin->close();
               							?>                                        
                                        <input type="hidden" name="hdntotqty" value="<?php echo $totqty; ?>"/>
                                    </table>                              
                              	</div>
                              	</td>
              				</tr>
        				</table>        
        			</td>
      			</tr>
			</table>
      		<br>
          	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
          		<tr>
                    <td align="center">
                        <input name="btnSaveInv" type="submit" class="btn" value=" Save " onclick="return confirmSave();"> 
                        <input name="btnRemoveInv" type="button" class="btn" value="Remove" onclick="getInventoryIn(this.value, 'dvInventoryInDetails');">
                    </td>
          		</tr>
        	</table>-->
            </form>
      		<br />            
          	<!-- START PRODUCT LIST -->
      		<!--<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
        		<tr>
                  	<td height="30" class="bgF9F8F7">
                      	<form name="frmproduct" action="index.php?pageid=26" method="post">
                      	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                          	<tr>
                                <td width="64" class="txt10"></td>
                                <td width="133" align="left" class="txt10"><input name="txtSearch" type="text" class="txtfield" size="30" value="<?php echo $vSearch; ?>" /></td>
                            	<td width="1098" colspan="3" align="left" class="txt10">
                                <div id="dvWarehouse">
                                  	<input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
                                  	<select name="lstWarehouse" style="width:140px " class="txtfield" <?php echo $dis; ?>>
                                    	<option value="0" selected>SELECT WAREHOUSE</option>
										<?php 
                                            if ($rs_warehouse->num_rows)
                                            {
                                                while ($row = $rs_warehouse->fetch_object())	
                                                {
                                                    if ($warehouseid == $row->ID) 
														$sel = "selected";
                                                    else 
														$sel = "";                                                    
                                                    echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
                                                }
                                                $rs_warehouse->close();
                                            }
										?>
              				  		</select>
              				  		&nbsp;
              				  		<input name="btnSearch" type="submit" class="btn" value="Go" />
                            	</div>
                            	</td>
                				<td width="5" align="right" class="txt10"></td>
                      		</tr>
                      	</table>
                      	</form>
          			</td>
        		</tr>
      		</table>-->
          	<form name="frmList" method="post" action="includes/pcOtherIns.php">
          	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  	<td class="tabmin">&nbsp;</td>
                  	<td class="tabmin2">&nbsp;</td>
                  	<td class="tabmin3">&nbsp;</td>
                </tr>
          	</table>
          	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
              	<tr>
                	<td colspan="3" valign="top" class="bgF9F8F7">
                    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                        	<tr>
                          		<td class="tab ">
                                	<table width="100%"  border="0" cellpadding="0" cellspacing="" class="txtdarkgreenbold10 " height="25">
                                    	<tr align="center">
                                          	<td width="5%">Line No.</td>
                                          	<td width="15%">Product Code</td>
                                          	<td width="15%">Product Name</td>
                                          	<!--  <td width="16%">Booklet No.</td> -->
                                          	<td width="10%">SOH</td>
                                          	<td width="10%">UOM</td>
                                          	<td width="10%">Loaded Qty</td>
                                          	<td width="10%">Actual Qty</td>
                                          	<td width="10%">Discrepancy</td>
                                  		</tr>
                        			</table>
                              	</td>
                    		</tr>
                            <tr>
                              	<td class="">
                                <input type="hidden" name="hdnSearch" value="<?php echo $vSearch; ?>" />
                                <input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
                                
                                <div id="dvProductList" class="scroll_300">
                                 	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                                      	<?php
                                      		if ($rs_product->num_rows)
                                            {
                                                $cnt = 0;
                                                while ($row = $rs_product->fetch_object())
                                                {
                                                    $cnt ++;
													($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
													$pname = $row->Product;
													$pid = $row->ProductID;
													$pcode = $row->ProductCode;
													$invid = $row->InventoryID;
													$batch = $row->Batch;
													$soh = number_format($row->SOH,0);
													$unitcost = $row->UnitCost;
													$uomid = $row->UOMID;
													$uom = $row->UOM;
													$multi = $row->Multiplier;

				  						?>			
                                      	<tr class="<?php echo $alt; ?>">
                                            <input name="hdnInventoryID[]" type="hidden" value="<?php echo $invid; ?>">
                                            <td width="5%" align="center" height="20" class="borderBR"><?php echo $cnt; ?></td>
                                            <td width="15%" height="20" class="borderBR"><?php echo $pcode; ?></td>
                                            <td width="15%" height="20" class="borderBR"><?php echo $pname; ?></td>
                                            <!--  <td width="16%" align="center" class="borderBR">&nbsp;</td> -->
                                            <td width="10%" align="center" class="borderBR"><?php echo $soh; ?></td>
                                            <td width="10%" align="center" class="borderBR"><?php echo $uom; ?>
                                            	<!--<span class="txt10">
                                          		<select name="cboUOM[]" class="txtfield" style="width:100px;">
                                            		<?php
                                            			if ($rs_uom->num_rows)
														{
															while ($row_uom = $rs_uom->fetch_object())
															{
																$id = $row_uom->ID;
																$uomname = $row_uom->Name;
																($id == $uomid) ? $sel = 'selected' : $sel = '';
													?>	
                                                    <option <?php echo $sel;?> value="<?php echo $id; ?>"><?php echo $uomname; ?></option>
                                                    <?php
                                                        	}
                                                        	$rs_uom->data_seek(0);
                                                    	}
                                                    ?>
                      							</select>
                    						</span>--></td>
                                            <td width="10%" align="center" class="borderBR">11</td>
                                            <td width="10%" align="center" class="borderBR"><input name="txtquantity[]" type="text" class="txtfield3" size="12" maxlength="20" value="10" style="text-align:right" ></td>
                                            <td width="10%" align="center" class="borderBR">1</td>
                                           <!-- <td width="15%" align="center" class="borderB">
                                              <input name="txtreason[]" type="text" class="txtfield" value="" size="24" maxlength="50" >
                                              
                                           </td>-->
                                   		</tr>
									  	<?php
                                                }
                                                $rs_product->close();
                                                $rs_uom->close();
                                            }
                                      	?>
                					</table>
          						</div>
                                </td>
        					</tr>
    					</table>
                 	</td>
  				</tr>
                <!--<tr class="bgF4F4F6">
                    <td height="20">&nbsp;</td>
                    <td align="right">
                    	<input name="btnAdd" type="button" class="btn" value="Add" onclick="getInventoryIn(this.value, 'ShowThis');"/>
                    </td>
                </tr>-->
                <br>
        		
			</table>
	  		</form>    
	  		<br>
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr class="">
                    <td height="20" align="center">
                        <input name="btnSave" type="submit" class="btn" value=" Save "> 
                        <input name="btnCancel" type="button" class="btn" value="Cancel">
                    </td>
          		</tr>
          	</table>
          	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  	<td align="right">&nbsp;</td>
                </tr>
          	</table>
      		<!-- END PRODUCT LIST -->
      		<div id="ShowThis"></div>
      		<br>
    	</td>
  	</tr>
</table>