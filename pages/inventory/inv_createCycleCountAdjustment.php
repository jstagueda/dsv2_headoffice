<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script language="javascript" src="js/jxCreateAdjustment.js"  type="text/javascript"></script>
<script type="text/javascript">
	window.history.forward(1);
	</script>
<script language="javascript" type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{ 		
    var docNo = document.frmList.txtDocNo;
    var cboWarehouse = document.frmList.cboWarehouse;
    var cboWarehousevalue = cboWarehouse.options[cboWarehouse.selectedIndex].value;
    //var txtTDate = document.frmList.startDate;
    var txtRemarks = document.frmList.txtRemarks;
    var txtSearch = document.frmList.txtSearch;
	
	eval(targ+".location='index.php?pageid=58&pid="+selObj.options[selObj.selectedIndex].value +
		"&dno="+docNo.value+"&wid="+cboWarehousevalue +
		"&remarks="+txtRemarks.value+"&search="+txtSearch.value+"'");
	
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
	var elms = document.frmList.elements;
	var btnRemove = document.frmList.btnRemoveInv;
    var hasCheckedItems = false;
    var chkAll = document.frmList.chkAll;



	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkIID[]') 
	  {
		  elms[i].checked = bin;
		  hasCheckedItems = true;		  
	  }		

    
    if(chkAll.checked)
	{
		btnRemove.disabled = false;
	}
	else
	{
		btnRemove.disabled = true;
	}

	
//	var elms = document.frmList.elements;
//	var btnRemove = document.frmList.btnRemoveInv;
//    var hasCheckedItems = false;
//    var chkAll = document.frmList.chkAll;
//    
//	for (var i = 0; i < elms.length; i++)
//	{
//		for(var a = 0; a < chkcnt; a++)
//		{ 
//			if (elms[i].name == "chkIID" + a)
//			{  
//				elms[i].checked = bin;
//				hasCheckedItems = true;
//			}
//		}
//	}
//    
//	if(chkAll.checked)
//	{
//		btnRemove.disabled = false;
//	}
//	else
//	{
//		btnRemove.disabled = true;
//	}
}

function validateRemove(bin) 
{

	var elms = document.frmList.elements;
    var hasCheckedItems = false;
	var btnRemove = document.frmList.btnRemoveInv;
    var chkAll = document.frmList.chkAll;




	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkIID[]') 
	  {

		  if (elms[i].checked)
		  {
		 		btnRemove.disabled = false;
			 	hasCheckedItems = true;
			 	break;
		  }
		  else
		  {
			  btnRemove.disabled = true;
			  hasCheckedItems = false;
		  }

			if(hasCheckedItems)
			{
				break;
			}	
		 	  
	  }		
//	var elms = document.frmList.elements;
//    var hasCheckedItems = false;
//	var btnRemove = document.frmList.btnRemoveInv;
//    var chkAll = document.frmList.chkAll;
//    var hasRecord = 0;
//    
//	for (var i = 0; i < elms.length; i++)
//	{
//		for(var a = 0; a < chkcnt; a++)
//		{
//			if (elms[i].name == "chkIID" + a) 
//	      	{
//	      		if(elms[i].checked)
//			 	{
//			 		hasRecord = 1;
//			 		break;
//		 		}
//	 		}
//		}	
//	}
//
//	if(hasRecord != 0)
//	{
//		btnRemove.disabled = false;
//		return true;
//	}
//	else
//	{
//		btnRemove.disabled = true;
//		return false;
//	}
//	
	chkAll.checked = false;	
}

function validateSave()
{ 
    var txtdocno = document.frmList.txtDocNo;
    var txtremarks = document.frmList.txtRemarks;
    var cboWarehouse = document.frmList.cboWarehouse;
    var cboWarehousevalue = cboWarehouse.options[cboWarehouse.selectedIndex].value;    
    var cnt = document.frmList.hProdCount.value;
    var msg = "";

    if(txtdocno.value == "")
    { 
    	msg += "* Document Number \n";     
    }

    if(cboWarehouse.selectedIndex == 0)
    {
    	msg += "* Warehouse \n";       
    }

    if(cnt == 0)
	{
    	
    	msg += "* There are no product/s added;";

	}
       
    if(msg != "")
    {
        alert ('Please complete the following: \n\n' + msg);
        return false;
    }
    else
    {
    	ml = document.frmList;
    	
    	if (ml.hdncnt.length > 1)
    	{
        	
    		for(i = 0; i < ml.hdncnt.length; i++)
    		{
    			var ctr = i + 1;
    			var desc = eval('document.frmList.txtQuantity' + ctr);
    			var reason = eval('document.frmList.cboReasons' + ctr);
    			var soh= eval('document.frmList.hdnSOH' + ctr);


    			if(isNaN(desc.value))
    			{
    				alert("Invalid Numeric format.");
    				desc.select();
    				desc.focus();
    				return false;
    			}

    			if(soh.value > desc.value)
    			{
    	
    				var b = soh.value;
    				var c = desc.value;
    				var a = eval(b) + eval(c);
    
    				if(a < 0)
    				{			
    					alert('Adjusted quantity should be less than or equal SOH.');
    					desc.select();
    					desc.focus();
    					return false;
    				}	
    			}	
    			

    			if (desc.value !="" && desc.value !="-")
    			{
    				if (reason.value == 0)
    				{
    					alert('Reason Code required.');
    					reason.focus();
    					return false;
    				}				
    			}
    			else
    			{
    				alert('Quantity is required.');
    				desc.focus();
					return false;
    			}
    		}		
    	}
    	else
    	{
        	
    		var desc = eval('document.frmList.txtQuantity1');
    		var reason = eval('document.frmList.cboReasons1');

  			if(isNaN(desc.value))
			{
				alert("Invalid Numeric format.");
				desc.select();
				desc.focus();
				return false;
			}
    		
  			if (desc.value !="" && desc.value !="-")
			{
				if (reason.value == 0)
				{
					alert('Reason Code required.');
					reason.focus();
					return false;
				}				
			}
			else
			{
				alert('Quantity is required.');
				desc.focus();
				return false;
			}		
    	}
        
        if(confirm('Are you sure you want to save this transaction?') == true)
        {
    	   window.location.href = "includes/pcCreateCycleCountAdjustment.php?&dno="+txtdocno.value+"&wid="+cboWarehousevalue+"&remarks="+txtremarks.value;
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
	var txtQuantity = document.getElementsByName('txtQuantity[]');
	var cboReason = document.getElementsByName('cboReasons[]');
	var cboReasonLength = cboReason.length;
	var isNegative = false;
	var cnthaveNegSign = false;

	if(myfield.value.indexOf("-") != -1)
	{
		isNegative = true;
	}

	if (window.event)
	   key = window.event.keyCode;
	else if (e)
	   key = e.which;
	else
	   return true;
	   
	keychar = String.fromCharCode(key);

	//control keys
	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
   		return true;
	// numbers
	else if ((("0123456789").indexOf(keychar) > -1))
	{  
   		cnthaveNegSign = true
   		return true;
	}
	else if(("-").indexOf(keychar) != -1 && ("0123456789").indexOf(myfield.value) != -1)
	{  
   		return true;
	}
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
	var txtQuantity = document.getElementsByName('txtQuantity[]');
	var txtReason = document.getElementsByName('cboReasons[]');
	var txtSOH = document.getElementsByName('hdnSOH[]');
	var length = txtQuantity.length;
	var emptyslots = 0;
	
	for(var i = 0; i < length; i++)
	{
		if(txtQuantity[i].value != '' && txtReason[i].selectedIndex == 0)
		{
			alert("Reason code required.");
			txtReason[i].focus();
			return false;
		} 
		
		if(txtQuantity[i].value == '' && txtReason[i].selectedIndex > 0)
		{
			alert("Quantity required.");
			txtQuantity[i].focus();
			return false;
		} 
		
		if(txtQuantity[i].value == '' && txtReason[i].selectedIndex == 0)
		{
			emptyslots++;
		} 

		if(eval(txtSOH[i].value) > eval(txtQuantity[i].value))
		{

			var b = eval(txtSOH[i].value);
			var c = eval(txtQuantity[i].value);
			var a = b + c;

			if(a < 0)
			{			
				alert('Adjusted quantity should be less than or equal SOH.');
				txtQuantity[i].select();
				txtQuantity[i].focus();
				return false;
			}			
		}
	}
	
	if(emptyslots == cnt)
	{
		alert("There are no products to be added.");
		return false;
	}	
}

function winSelectProduct() 
{
	 var cboWarehouse = document.frmList.cboWarehouse;
	 var wid = cboWarehouse.options[cboWarehouse.selectedIndex].value;
	 var docNo = document.frmList.txtDocNo.value;
	 var mtypeid = document.frmList.hdnMTypeID.value;
	 var rem = document.frmList.txtRemarks.value;
	 var plist = document.frmList.hProdListID.value;
     if (wid == 0)
     {
         alert("Warehouse is required.");
         cboWarehouse.focus();
         return false;
     }
	var pge = 2;
	subWin = window.open('pages/inventory/inv_ProductPopUp.php?wid=' + wid + '&dno=' + docNo + '&mtypeid=' + mtypeid + '&remarks=' + rem + '&pge=' + pge + '&desid=' + '&prodlist=' + plist, 'newWin','width=600,height=600,scrollbars=yes');
	subWin.focus();
	return false;
}
</script>

<?php
	include IN_PATH.'scCreateCycleCountAdjustment.php'; 
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" align="right"><a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
	<td class="txtgreenbold13">Create Cycle Count Adjustment</td>
	<td>&nbsp;</td>
</tr>
 <?php
 	if (isset($_GET['message']))
	{
  		echo "<tr>
 			<td> <div align='left' style='padding:5px 0 0 5px;' class='txtreds'> ". $_GET['message']." </div> </td>
  		</tr>";
	}
 ?>
 </table>
 <br />
 <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
 	<td class="tabmin">&nbsp;</td>
 	<td class="tabmin2 txtredbold">General Information</td>
  	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<form name="frmList" method="post" action="includes/pcCreateCycleCountAdjustment.php?prodlist=<?php echo $_GET['prodlist'];?>&wid=<?php echo $_GET['wid'];?>">
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		<input type="hidden" name="hProdListID" value="<?php echo $_GET['prodlist'];?>">
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="50%" valign="top">
	          	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
	            <tr>
	          		<td height="20" class="txt10">Movement Type :</td>
	              	<td height="20"><?php echo $movement; ?><input type="hidden" name="hdnMTypeID" id="hdnMTypeID" value="<?php echo $movementtypeid; ?>"></td>
	            </tr>
	            <tr>
	        		<td width="25%" height="20" class="txt10">Warehouse</td>
	        		<td width="75%" height="20">
		               <?php
		                    $wid = 0;
		                    isset($_GET['wid']) ? $wid = $_GET['wid'] : $wid = 0;
		                  	echo "<select name=\"cboWarehouse\" style=\"width:160px\" class=\"txtfield\">";
		                  	echo "<option value=\"0\" >[SELECT HERE]</option>";
		              		if ($rs_warehouse->num_rows)
							{
								$selectedw = '';
								while ($row = $rs_warehouse->fetch_object())	
								{   															
									$row->ID == $wid ? $selectedw = 'selected' : $selectedw = '';
									echo "<option $selectedw value='$row->ID'>".$row->Name."</option>";
								}
								$rs_warehouse->close();
							}
		                  	echo "</select>";                                                      
		          		?>
	          		</td>
	      		</tr>              
	            <tr>
	            	<td height="20" class="txt10">Document No. :</td>
	            	<td height="20"><input name="txtDocNo" type="text" class="txtfield" id="txtDocNo" value="<?php if(isset($_GET['dno'])) {echo $_GET['dno'];} else  {echo '';} ?>" size="20" maxlength="20" value="" /></td>
	        	</tr>
	            <tr>
	            	<td height="20">&nbsp;</td>
	            	<td height="20">&nbsp;</td>
	            </tr>
	            </table>
        	</td>
        	<td width="45%" valign="top">
        		<table width="100%"  border="0" cellspacing="1" cellpadding="0">
        		<tr>
	          		<td height="20" class="txt10">Reference No. :</td>
	              	<td height="20"><?php echo $trno; ?></td>
	            </tr>
            	<tr>
            		<td width="25%" height="20" class="txt10">Transaction Date :</td>
            		<td width="75%" height="20"><?php if(isset($_GET['tdate'])) {echo $_GET['tdate'];} else  {echo $datetoday;} ?></td>
        		</tr>
        		<tr>
                  	<td height="20" class="txt10">Branch :</td>
                  	<td height="20">
		              <input type="text" id="txtBranch" name="txtBranch" class="txtfieldLabel" size="20" maxlength="20" value="<?php echo $branchName;?>"/>
                  	  <input type="hidden" id="txtBranchID" name="txtBranchID" class="txtfield" size="20" maxlength="20" value="<?php echo $branchID;?>" readonly="readonly" />
                  	</td>
				</tr>
				<tr>
                  	<td height="20" class="txt10">Created By :</td>
                  	<td height="20"><input type="text" id="txtCreatedBy" name="txtCreatedBy" class="txtfieldLabel" style="width: 200px;" value="<?php echo $username;?>"/></td>
				</tr> 
            	<tr>
              		<td height="20" valign="top" class="txt10">Remarks :</td>
              		<td height="20"><textarea name="txtRemarks" cols="40" rows="3" class="txtfieldnh" id="txtRemarks"><?php if(isset($_GET['remarks'])) {echo $_GET['remarks'];} else {echo '';} ?></textarea></td>
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
</table>
	<br>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td class="tabmin">&nbsp;</td>
		<td class="tabmin2 txtredbold">Adjustment Details</td>
		<td class="tabmin3">&nbsp;</td>
	</tr>
	</table>
	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
	<tr>
		<td height="30" class="bgF9F8F7">
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td width="64" class="txt10"><input name="btnSearch" type="submit" class="btn" value="Search" onclick="javascript:winSelectProduct();" /></td>
	<!--			<td width="133" align="left" class="txt10"><input name="txtSearch" type="text" class="txtfield" size="30" value="<?php if(isset($_GET['search'])) {echo $_GET['search'];} else  {echo '';} ?>" /></td>-->
				<td width="1098" align="left" class="txt10"><div id="dvWarehouse"><input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" /></div></td>
				<td width="5" align="right" class="txt10"></td>
			</tr>
			</table>
		</td>
	</tr>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl3">
	<tr>
		<td valign="top" class="bgF9F8F7">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="tab">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
                  	<tr align="center">
                    	<td width="5%" height="20" class="bdiv_r"><input name="chkAll" type="checkbox" id="chkAll" value="1"  onclick="checkAll(this.checked, <?php  echo sizeof($_GET['prodlist']); ?>);"></td>
                    	<td width="20%" height="20" class="bdiv_r padl5" align="left">Product Code</td>
                    	<td width="25%" height="20" class="bdiv_r padl5" align="left">Product Name</td>
                    	<td width="10%" height="20" class="bdiv_r">SOH</td>
                    	<td width="10%" height="20" class="bdiv_r">UOM</td>
                    	<td width="10%" height="20" class="bdiv_r">Quantity</td>							
                    	<td width="20%" height="20">Reason</td>
                	</tr>
                	</table>
            	</td>
        	</tr>
        	<tr>
        		<td valign="top"><div id="dvAdjustmentDetails" class="scroll_150">
        			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        			<?php 
        			$prodCount = 0;
        			 if (isset($_GET['prodlist']) && $_GET['prodlist'] != "")
                             {
                             	$cnt = 0;
                               	$prodlist_url = explode(',', $_GET['prodlist']);
                                $_SESSION['prod_list'] = $prodlist_url;
                                            	
                                for ($i = 0, $n = sizeof($_SESSION['prod_list']); $i < $n; $i++ ) 
                                     {
                                     	$cnt ++;
                                     	$prodCount++;
                                     	$rs_proddet = $sp->spSelectProductListInv($database, $_SESSION['prod_list'][$i], $_GET['wid']);
                                     	if ($rs_proddet->num_rows)
                                         	{
                                            	while ($row = $rs_proddet->fetch_object())
                                            	{	
                                     	
			                                     	
													($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
													$pname = $row->Product;
													$pid = $row->ProductID;
													$pcode = $row->ProductCode;								
													$soh = number_format($row->SOH,0);
												
                                            	}
                                            	?>	
			                                    <tr class="<?php echo $alt; ?>">
			                                    <input name="hdncnt" type="hidden" value="<?php echo $cnt; ?>">
										  		<input name="hdnProductID<?php echo $cnt;?>" id="hdnProductID<?php echo $cnt;?>" type="hidden" value="<?php echo $pid; ?>"/>
						                  		<input name="hdnProductCode<?php echo $cnt;?>" type="hidden" value="<?php echo $pcode; ?>"/>
												<input name="hdnProductName<?php echo $cnt;?>" type="hidden" value="<?php echo $pname; ?>"/>
												<input name="hdnSOH<?php echo $cnt;?>" type="hidden" value="<?php echo $soh; ?>"/>
												<input name="hdnRegPrice<?php echo $cnt;?>" type="hidden" value="<?php echo $regprice; ?>"/>
												<td width='5%' align="center" class='borderBR'>															 	
											 		<input name="chkIID[]" onclick="validateRemove(this.checked)" type="checkbox" value="<?php echo $pid;?>" >
											 	</td>
						                  		<td width="5%" align="center" height="20" class="borderBR"><?php echo $cnt; ?></td>
						                  		<td width="15%" height="20" class="borderBR padl5"><?php echo $pcode; ?></td>
						                    	<td width="25%" height="20" class="borderBR padl5"><?php echo $pname; ?></td>
						                    	<td width="10%" align="center" class="borderBR"><?php echo $soh; ?></td>
						                    	<td width="10%" align="center" class="borderBR"><span class="txt10">
					                      		<select name="cboUOM<?php echo $cnt;?>" id="cboUOM<?php echo $cnt;?>" class="txtfield" style="width:100px;" disabled="disabled" >
					                        	<?php
					                        		if ($rs_uom->num_rows)
													{  
											    		$box = 1;
														while ($row_uom = $rs_uom->fetch_object())
														{
															$id = $row_uom->ID;
															$uomname = $row_uom->Name;
															($id == $uomid) ? $sel = 'selected' : $sel = '';
															//($box == 2) ? $sel = 'selected' : $sel = ''; 
															$box += 1;
												?>	
					                        			<option <?php echo $sel;?> value="<?php echo $id; ?>"><?php echo $uomname; ?></option>
					                        	<?php
														}
														$rs_uom->data_seek(0);
													}
												?>
					                      		</select>
						                		</span></td>
						                    	<td width="10%" align="center" class="borderBR"><input name="txtQuantity<?php echo $cnt;?>"  id="txtQuantity<?php echo $cnt;?>" style = "width: 50px; text-align: right;" onKeyPress="return numbersonly(this, event, <?php echo $cnt;?>);" type="text" class="txtfield3" size="12" maxlength="20" ></td>
						                    	<td width="20%" align="center" class="borderB">                     
					                      		<select name="cboReasons<?php echo $cnt;?>" id="cboReasons<?php echo $cnt;?>" class="txtfield" style="width:150px;"> 
										  		<option value="0" selected>[SELECT HERE]</option>
													<?php
					                        		if ($rs_reasons->num_rows)
													{
														while ($row_reasons = $rs_reasons->fetch_object())
														{
															$id = $row_reasons->ID;
															$uomname = $row_reasons->Name;
															($id == $uomid) ? $sel = 'selected' : $sel = '';
													?>
					                        		<option <?php echo $sel;?> value="<?php echo $id; ?>"><?php echo $uomname; ?></option>
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
			                  		}
			                  		$rs_uom->close();
			                  		
								}else{
    
							  	?>
								<tr>
									<td colspan="3" align="center"><font color="#FF0000"><b>No records to display.<b></font></td>
								</tr>
								<?php } ?>
												
                 		</table>
              		</div></td>
          		</tr>
          		</table>
      		</td>
  		</tr>
  		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
      	<tr>
      		<td align="center">
        		<?php 
        			$disabled = "";   
        			if(isset($_SESSION['prod_list']) && sizeof($_SESSION['prod_list']) > 0)
        			{
        				$disabled = "";
    				}
    				else
    				{
    					$disabled = "disabled = disabled";
					}
				?>
				<input name="hProdCount" type="hidden" value="<?php echo $prodCount; ?>">
        		<input name="btnSaveInv"  type="submit" onclick="return validateSave();" class="btn" value="Save"> 
        		<input name="btnRemoveInv" disabled="disabled" type="submit" class="btn" value="Remove">
    		</td>
		</tr>
		</table>
		</form>
	</td>
</tr>
</table>
<br>
