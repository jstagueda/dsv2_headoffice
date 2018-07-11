<script language="javascript" src="js/jxCreateInventoryCount.js"  type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{ 		
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) 
		selObj.selectedIndex = 0;
}
function confirmCancel()
{
  if (confirm('Are you sure you want to cancel this transaction?') == false)
   {
        return false;
   }    
}

function checkAll(bin, chkcnt) 
{
	var elms = document.frmDetails.elements;
 
	for (var i = 0; i < elms.length; i++)
	{
		for(var a = 0; a < chkcnt; a++)
		{ 
	      if (elms[i].name == "chkIID" + a) 
	      {  
		     elms[i].checked = bin;		  
	      }	
		}	
	}
}

function validateSave()
{ 
    var txtrefno = document.frmInventoryCountDetails.txtRefNo;
    var txtdocno = document.frmInventoryCountDetails.txtDocNo;
    var txttdate = document.frmInventoryCountDetails.startDate;
    var txtremarks = document.frmInventoryCountDetails.txtRemarks;
    
    var msg = "";

    //alert(txtrefno + "-" + txtdocno +"-"+ cboMoveType);
    //return false;
    
    if(txtrefno.value == "")
    {
        msg += "* Reference Number \n";      
    }
    
    if(txtdocno.value == "")
    { 
    	msg += "* Document Number \n";     
    }
    
    
    
    if(msg != "")
    {
        alert(msg);
        return false;
    }
    else
    {
        if(confirm('Are you sure you want to save this transaction?') == true)
        {
    	   window.location.href = "includes/pcInvCount.php?refno="+txtrefno.value+"&docno="+txtdocno.value+"&tdate="+txttdate.value+"&remarks="+txtremarks.value+"";
        }
        else
        {
            return false;
        }
    }
    
}

function numbersonly(myfield, e, dec)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);

// control keys
if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

// numbers
else if ((("0123456789").indexOf(keychar) > -1))
   return true;

// decimal point jump
else if (dec && (keychar == "."))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;
}


function confirmAdd(cnt)
{
	//cnt = document.frmList.hProd;
	//objwid  = document.frmList.cboReasons;
       objqty  = document.frmList.elements["txtQuantity[]"];
       objres  = document.frmList.elements["cboReasons[]"];
       var isValid = true;
       var emptyslots = 0;
       
		for (var c = 0; c < cnt; c++)
		{
			if(objqty[c].value != "" && objres[c].selectedIndex == 0)
			{
				if(objres[c].selectedIndex == 0)
				{
					 alert("Please select a reason");
					 isValid = false;
				}
			}

			if(objqty[c].value == "" && objres[c].selectedIndex > 0)
			{
			    alert("Quantity required.");
			    isValid = false;
				
			}

			if(objqty[c].value == "" && objres[c].selectedIndex == 0)
			{
				emptyslots++;
			}

		}

		if(emptyslots == cnt)
		{
			alert("There are no products to be added.");
			isValid = false;
		}

	 	if(!isValid)
	 	{
		 	return false;
	 	}
	 	else
	 	{
		 	return true;
	 	}
}

</script>
<?php
		include IN_PATH.'scCreateInvCount.php';
?>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
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
    <td class="txtgreenbold13">Create Inventory Count</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
 <form name="frmInventoryCountDetails" method="post" action="includes/pcInvCount.php">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td class="txtredbold">General Information </td>
              <td>&nbsp;</td>
            </tr>
          </table></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
  <tr>
    <td valign="top" class="bgF9F8F7"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td width="50%" valign="top"><table width="100%"  border="0" cellspacing="1" cellpadding="0">
            <tr>
              <td width="500" height="20" class="txt10">ReferenceNo No.</td>
              <td width="500">
              	<input name="txtRefNo" type="text"  onKeyPress="return numbersonly(this, event);"  class="txtfield" id="txtRefNo" size="20" maxlength="20" value="" /></td>
            </tr>
            <tr>
              <td width="500" height="20" class="txt10">Document No.</td>
              <td width="500"><input type="hidden"  name="hdnWarehouseID" value="<?php echo $warehouseid; ?>"/>
              	<input name="txtDocNo" type="text"  onKeyPress="return numbersonly(this, event);"  class="txtfield" id="txtDocNo" size="20" maxlength="20" value="" /></td>
            </tr>
            <tr>
              <td height="20" class="txt10">Transaction Date </td>
			  
              <td><input name="startDate" type="text" class="txtfield" id="startDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>" />
                <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'startDate', 'yyyy-mm-dd')" title="Show popup calendar"><img src="images/btn_Calendar.gif" width="25" height="19" border="0" align="absmiddle" /></a></td>
            </tr>
            <tr>
              <td height="20" class="txt10"><input type="button" id="btnFreeze" class="btn" name="btnFreeze" value="Freeze Inventory"></td>			  
              <td></td>
            </tr>
          </table></td>
          <td valign="top"><table width="100%"  border="0" cellspacing="1" cellpadding="0">
            <tr>
              <td width="500" height="20" valign="top" class="txt10">Remarks</td>
              <td width="500"><textarea name="txtRemarks" cols="40" rows="3" class="txtfieldnh" id="txtRemarks"></textarea></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
</table>
 </form>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="3" class="bgE6E8D9"></td>
        </tr>
      </table>
      <br>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="tabmin">&nbsp;</td>
    <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="txtredbold">Inventory Count Details </td>
          <td><table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
              <tr>
                <td>&nbsp;</td>
                <td width="15">&nbsp;</td>
                <td width="12">&nbsp;</td>
              </tr>
          </table></td>
        </tr>
    </table></td>
    <td class="tabmin3">&nbsp;</td>
  </tr>
</table>
<form name="frmDetails" method="post" action="includes/pcInvCount.php">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl3">
      <tr>
        <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="tab "><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
              <tr align="center">
                <td width="5%"><input name="chkAll" type="checkbox" id="chkAll" value="1"  onclick="checkAll(this.checked, <?php  echo sizeof($_SESSION['prodid']); ?>);"></td>
                <td width="15%">Product Name </td>
                <td width="10%">SOH</td>
                <td width="10%">UOM</td>
                <td width="10%">Quantity</td>
                <td width="10%">Unit Price</td>
                <td width="10%">Amount</td>
                <td width="15%">Reason</td>
              </tr>
            </table></td>
              </tr>
              <tr>
                <td valign="top" class="">
                  <div id="dvAdjustmentDetails">

                        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                            <?php

								$cnt = 0;			
							    $prodCount = 0;
				                    				 
				                    				if (isset($_SESSION['prodid']) && sizeof($_SESSION['prodid']) > 0) 
													{
														$prodCount = sizeof($_SESSION['prodid']);
														$totqty = 0;														
														for ($i = 0, $n = sizeof($_SESSION['prodid']); $i < $n; $i++ ) 
								    					{
								    						$cnt ++;
															($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';							    							    						
								    						$v_productid = $_SESSION['prodid'][$i];
								    						$v_productcode = $_SESSION['prodcode'][$i];
								    						$v_productname = $_SESSION['prodname'][$i];
								    						$v_soh = $_SESSION['soh'][$i];
															$v_uomid = $_SESSION['uomid'];
								    						$v_uom = $_SESSION['uom'][$i];
								    						$v_qty = $_SESSION['qty'][$i];
															$v_reasonid = $_SESSION['resid'][$i];
															$v_reason = $_SESSION['res'][$i];
															$v_unitprice = $_SESSION['unitprice'][$i];
														    $index = $i + 1;	
															
															echo "<tr align='center' class='$alt'>
															 		<td width='5%' class='bdiv_r'>															 	
															 			<input name='chkIID$i' type='checkbox' >
															 			<input name='hdnIID[]' type='hidden' value='$v_productid'>
															 			<input name='hdnUID[]' type='hidden' value='$v_uomid'>
															 			<input name='hdnRID[]' type='hidden' value='$v_reasonid'>
															 			<input name='hdnUP[]' type='hidden' value='$v_unitprice'>
															 			
															 		</td>
															
									                         		<td width='15%' align='left' class='bdiv_r padl5'>".$v_productname."&nbsp;</td>
									                         		<td width='10%' class='bdiv_r'>$v_soh</td>
									                         		<td width='10%' class='bdiv_r'>";										    					
								    									//$rs_uombyid = $sp->spSelectUOMByID($v_uom);
																		//if ($rs_uombyid->num_rows)
																		//{
																			//while ($row_uom = $rs_uombyid->fetch_object())	
																			//{
																			//	echo $row_uom->Name;
																			//}
																			//$rs_uombyid->close();														
																		//}
																	echo "$v_uom</td>
									                         			<td width='10%'  class='bdiv_r'>".$v_qty."&nbsp;</td>
									                         			<td width='10%'  class='bdiv_r'>".$v_unitprice."&nbsp;</td>
									                         			";
																	$amount = $v_qty * $v_unitprice;
																	echo "
									                         			<td width='10%'  class='bdiv_r'>".$amount."&nbsp;</td>
									                         			";
																	
																		//<td width='10%' class='bdiv_r'><div align='right' class='padr5'>".number_format($v_unitprice,2)."</div></td>
									                   		 			//<td width='10%' class='bdiv_r'><div align='right' class='padr5'>".number_format($v_amount,2)."</div></td>
								                   		 			if ($prodCount > 8)
								                   		 			{
								                   		 				//echo"<td width='10%' align='left' class='padl5' style='max-width: 150px; word-wrap: break-word;'>".$v_qty."</td>";
								                   		 			}
								                   		 			else
								                   		 			{
								                   		 				//echo"<td width='10%' align='left' class='padl5' style='max-width: 150px; word-wrap: break-word;'>".$v_qty."</td>";
								                   		 			}
																	
																	echo"<td width='15%'  class='padl5' style='max-width: 150px; word-wrap: break-word;'>".$v_reason."</td>";
							                     			echo "</tr>";	

							                     			
								    					}
								    					    
													}
												else
												{
													echo "<tr align='center'>
									                         <td width='100%' height='20' class='' colspan='9'><span class='txtredsbold'>No record(s) to display.</span></td>								                         
									                     </tr>";	
												}
							  
							  
                              ?>
							
							
                        </table>
                  
                  </div></td>
              </tr>
        </table></td>
      </tr>
	</table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="3" class="bgE6E8D9"></td>
        </tr>
      </table>
      
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td align="center">
         <?php $disabled = "";   if(sizeof($_SESSION['prodid']) == 0)
                                 {
        	                        $disabled = "disabled = disabled";
                                 }
                                 else
                                 {
                 	                 $disabled = "";
                                 } //echo $disabled; exit();
         ?>
        <input name="btnSaveInv" <?php echo $disabled; ?> type="button" onclick="return validateSave();" class="btn" value=" Save "> 
         <input name="btnRemoveInv" <?php echo $disabled; ?> type="submit" class="btn" value="Remove" ">
        </td>
      </tr>
    </table>
    </form>
      <br>
      <!-- START PRODUCT LIST -->
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">Product List </td>
                <td>&nbsp;</td>
              </tr>
          </table></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
        <tr>
          <td height="30" class="bgF9F8F7">
          <form name="frmproduct" action="index.php?pageid=28" method="post">
          <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td width="100" class="txt10"><input name="txtSearch" type="text" class="txtfield" size="30" value="<?php echo $vSearch; ?>" /></td>
                <td width="150" align="left" class="txt10"><input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
                  <div id="dvWarehouse">
				  <input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
                  <select name="lstWarehouse" style="width:140px " class="txtfield" <?php echo $dis; ?>>
                				<option value="0" selected>[SELECT HERE]</option>
                                <?php 
									if ($rs_warehouse->num_rows)
									{
										while ($row = $rs_warehouse->fetch_object())	
										{
											if ($warehouseid == $row->ID) $sel = "selected";
											else $sel = "";
											
											echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
										}$rs_warehouse->close();
									}
								?>
       				  </select>
     			</div></td>
                <td align="left" class="txt10">
                  <input name="btnSearch" type="submit" class="btn" value="Search" />
                </td>
                </tr>
          </table>
          </form>
          </td>
        </tr>
      </table>
      <form name="frmList" method="post" action="includes/pcInvCount.php">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        <tr>
    <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="tab "><table width="100%"  border="0" cellpadding="0" cellspacing="" class="txtdarkgreenbold10 " height="25">
            <tr align="center">
              <td width="5%">Line No.</td>
              <td width="30%">Product Name</td>
              <td width="12%">SOH</td>
              <td width="9%">UOM</td>
              <td width="12%">Quantity</td>
              <td width="16%">Reason</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td class="">
            <input type="hidden" name="hdnSearch" value="<?php echo $vSearch; ?>" />
	        <input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
            <div id="dvProductList" class="scroll_300">
              <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                  <?php if ($rs_product->num_rows)
						{
							$cnt = 0;
							while ($row = $rs_product->fetch_object())
							{
								$cnt ++;
								($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
                                $invid = $row->invid;
								$pname = $row->Product;
								$pid = $row->ProductID;
								$pcode = $row->ProductCode;								
								$soh = number_format($row->SOH,0);
								$unitprice = $row->up;

				  ?>			
                  <tr class="<?php echo $alt; ?>">
                   <input name='hdnProductID[]' type='hidden' value="<?php echo $row->ProductID; ?>"/>
                  	<input name="hdnProductCode[]" type="hidden" value="<?php echo $pcode; ?>"/>
					<input name="hdnProductName[]" type="hidden" value="<?php echo $pname; ?>"/>
					<input name="hdnSOH[]" type="hidden" value="<?php echo $soh; ?>"/>
                   <input name="hdnInventoryID[]" type="hidden" value="<?php echo $invid; ?>">
                   <input name="hdnUnitPrice[]" type="hidden" value="<?php echo $unitprice; ?>">
                   
                  	<td width="5%" align="center" height="20" class="borderBR"><?php echo $cnt; ?></td>
                    <td width="30%" height="20" class="borderBR"><?php echo $pname; ?></td>
                    <td width="12%" align="center" class="borderBR"><?php echo $soh; ?></td>
                    <td width="9%" align="center" class="borderBR"><span class="txt10">
                      <?php 
						if ($rs_uom->num_rows)
						{
							while ($row_uom = $rs_uom->fetch_object())
							{
								$id = $row_uom->ID;
								$uomname = $row_uom->Name;
							}
							echo $uomname; 
						}
						
						?>
                    </span></td>
                    <td width="12%" align="center" class="borderBR"><input name="txtQuantity[]" type="text" class="txtfield3" size="12" maxlength="20" value="" ></td>
                    <td width="16%" align="center" class="borderB">
                      <select name="cboReasons[]" class="txtfield" style="width:100px;"> 
                       <option value="0" selected>[SELECT HERE]</option>
													<?php
													
							                        if ($rs_reasons->num_rows)
													{
														while ($row_reasons = $rs_reasons->fetch_object())
														{
															$id = $row_reasons->ID;
															$reasonname = $row_reasons->Name;
															($id == $uomid) ? $sel = 'selected' : $sel = '';
													?>	
							                       
							                        <option <?php echo $sel;?> value="<?php echo $id; ?>"><?php echo $reasonname; ?></option>
							                        
							                        <?php
														}
														$rs_reasons->data_seek(0);
													}
													?>
							                      </select>
                      
                   </td>
                  </tr>
                  <?php
							}
							$rs_product->close();
							$rs_uom->close();
							$rs_reasons->close();
						}
				  ?>
                </table>
          </div></td>
        </tr>
    </table></td>
  </tr>
	<tr class="bgF4F4F6">
	  <td height="30" align="right">
      	<div id="showthis"></div>
      	<input name="btnAdd" type="submit" class="btn" onclick="return confirmAdd(<?php echo $cnt; ?>);" value="Add"/> &nbsp;
      	<input name="btnClear" type="submit" class="btn" value="Clear"/></td>
	  </tr>
</table>
    </form>
      <!-- END PRODUCT LIST -->
      <br>
      
    </td>
  </tr>
</table>
<br />
<br />
<br />
<br />
