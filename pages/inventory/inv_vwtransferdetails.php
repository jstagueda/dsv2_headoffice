<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script type="text/javascript">
function checkAll(bin) 
{
	var elms = document.frmVwTransferDetails.elements;
  
	for(var i=0;i<elms.length;i++)
	{
		if(elms[i].name=='chkID[]') 
  		{
  			elms[i].checked = bin;		  
  		}		
	}
}

function CheckInclude()
{
	var ci = document.frmVwTransferDetails.elements["chkID[]"];

	for(i=0; i< ci.length; i++)
	{
		if(ci[i].checked == false)
		{
			document.frmVwTransferDetails.chkAll.checked = false;
		}
	}
		
	if (document.frmVwTransferDetails.elements["chkID[]"].value > 1)
  	{
  		if(ci.checked == false)
  		{
  			document.frmVwTransferDetails.chkAll.checked = false;
  		}
  	}
}
  
function CheckItem()
{
	count = 0;
	str = '';
 	obj = document.frmVwTransferDetails.elements["chkID[]"];

 	if (obj.length > 1 )
 	{
 		for(x=0; x < obj.length; x++)
	 	{
			if(obj[x].checked==true)
			{
				str += obj[x].value + ',';
			  	count++;
			}
 		}
 	}
 	else
 	{
		if(obj.checked==true)
		{
	  		count++;
		}
 	}

 	if(count == 0)
 	{
  		alert("There are no product(s) to be removed.");
	  	return false;
 	}
	
	document.frmVwTransferDetails.submit();
	return true;
}

function confirmDelete()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
   	{
        return false;
   	}    
}

function confirmCancel()
{
	if (confirm('Changed made will not be saved. Proceed?') == false)
   	{
        return false;
   	}    
}

function confirmSave()
{
	var ml = document.frmVwTransferDetails;
	var len = ml.elements.length;
	var index = 0;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
		if (e.name == "chkID[]") 
		{
			var b = eval('document.frmVwTransferDetails.txtQuantity' + e.value);	
			var a = eval('document.frmVwTransferDetails.cboReasons' + e.value);
			var c = eval('document.frmVwTransferDetails.hdnSOH' + e.value);
		    				
			if(b.value == "" || (!isNumeric(b.value)) || eval(b.value) < 0)
			{
				alert("Invalid numeric format for Quantity.");
				b.focus();
				b.select();
				return false;			
			}
			
			if(eval(b.value) == 0)
			{
				alert("Please input a Quantity.");
				b.focus();
				b.select();
				return false;
			}
			
			if (eval(c.value) < eval(b.value))
			{
				alert("Transferred quantity should be less than or equal SOH.");
				b.focus();
				b.select();
				return false;				
			}
				 
			if(eval(a.value) == 0)
			{
				alert("Please select a Reason.");
				a.focus();
				return false;
			}					
		}
	}
	
	if (confirm('Are you sure you want to confirm this transaction?') == false)
	{
    	return false;
	}
}  

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(obj) 
{
	var objWin;
	//var wc=prompt("Warehouse Clerk : ","");
	//var ws=prompt("Warehouse Supervisor : ","");
	
	popuppage = "pages/inventory/inv_vwtransferdetailsprint.php?tid="+obj.value;		
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','500','yes');
		}
		
		return false;  		
}

function checker()
{
	var ml = document.frmVwTransferDetails;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkID[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}

function confirmSaveDraft()
{
	var ml = document.frmVwTransferDetails;
	var len = ml.elements.length;
	var index = 0;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
		if (e.name == "chkID[]") 
		{
			var b = eval('document.frmVwTransferDetails.txtQuantity' + e.value);	
			var a = eval('document.frmVwTransferDetails.cboReasons' + e.value);
			var c = eval('document.frmVwTransferDetails.hdnSOH' + e.value);
			    				
			if(b.value == "" || (!isNumeric(b.value)) || b.value < 0)
			{
				alert("Invalid numeric format for Quantity.");
				b.focus();
				b.select();
				return false;			
			}
			
			if(b.value == 0)
			{
				alert("Please input a Quantity.");
				b.focus();
				b.select();
				return false;
			}
			
			if (eval(c.value) < eval(b.value))
			{
				alert("Transferred quantity should be less than or equal SOH.");
				b.focus();
				b.select();
				return false;				
			}
				 
			if(a.value == 0)
			{
				alert("Please select a Reason.");
				a.focus();
				return false;
			}				
		}
	}
	
	if (confirm('Are you sure you want to save transaction?') == false)
	{
		return false;
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
</script>

<?PHP 
	include IN_PATH.DS."scViewTransferDetails.php";
?>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<form name="frmVwTransferDetails" method="post" action="includes/pcVwTransferDetails.php?tid=<?php echo $_GET['tid']; ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
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
    		<td class="txtgreenbold13">View Inventory Transfer </td>
    		<td align="right">&nbsp;</td>
		</tr>
		</table>
      	<?php
			if (isset($_GET['msg']))
	  		{ 
	  	?>
	  		<br>
	  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  		<tr>
	  			<td class="txtblueboldlink"><b><?php echo $_GET['msg']; ?></b></td>
	    	</tr>
			</table>
	  	<?php
	  		}
		?>
		<br />
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
              		<td width="40%" valign="top">  
			  			<table width="98%"  border="0" cellspacing="1" cellpadding="0">                 
                   		<!--<tr>
                    		<td height="20" class="txt10">Branch</td>
                    		<td height="20" class="txt10"><?php echo $branchid; ?><input name="hdnBranchID" type="hidden" value="<?php echo $branchid; ?>"></td>
                		</tr>-->			  
                  		<tr>
                      		<td width="30%" height="20" class="txt10">Movement Type :</td>
                      		<td width="70%" height="20" class="txt10"><?php echo $movement;?><input name="hdnMTypeID" type="hidden" value="<?php echo $movementID; ?>"></td>
                  		</tr>                   
                  		<tr>
                    		<td height="20" class="txt10">Source Warehouse :</td>
                			<td height="20" class="txt10"><?php echo $warehouse;?><input name="hdnSWhouseID" type="hidden" value="<?php echo $swhouseid; ?>"></td>
            			</tr>
				 		<tr>
	                		<td height="20" class="txt10">Destination Warehouse :</td>
	                		<td height="20" class="txt10"><?php echo $DestinationWarehouse;?><input name="hdnDWhouseID" type="hidden" value="<?php echo $dwhouseid; ?>"></td>
                		</tr>
                		<tr>
                      		<td height="20" class="txt10">Document No. :</td>
                      		<td height="20" class="txt10"><?php echo $docno;?><input name="hdnDocNo" type="hidden" value="<?php echo $docno; ?>"></td>
                  		</tr>
              			</table>
		  			</td>
		  			<td width="58%" valign="top">
			  			<table width="98%"  border="0" cellspacing="1" cellpadding="0">
			  			<tr>
                    		<td width="30%" height="20" class="txt10">Reference No. :</td>
                    		<td width="70%" height="20" class="txt10"><?php echo $txnno; ?><input name="hdnTxnID" type="hidden" value="<?php echo $id; ?>"></td>
                		</tr>				
						<tr>
                    		<td height="20" class="txt10">Transaction Date :</td>
                    		<td height="20" class="txt10"><?php echo $txndate;?><input name="hdnTxnDate" type="hidden" value="<?php echo $txndate; ?>"></td>
                		</tr>
                		<tr>
                          	<td height="20" class="txt10">Branch Name:</td>
                          	<td height="20"><input name="txtBranch" type="text" class="txtfieldLabel" id="txtBranch" size="20" maxlength="20" readonly="yes" value="<?php echo $branchid; ?>"></td>
                        </tr>
                		<tr>
                    		<td height="20" class="txt10">Created By :</td>
                    		<td height="20" class="txt10"><?php echo $createdby;?></td>
                		</tr>
                		<?php
                			if ($statid == 7)
                			{
                		?>
                		<tr>
                    		<td height="20" class="txt10">Confirmed By :</td>
                    		<td height="20" class="txt10"><?php echo $confirmedby;?></td>
                		</tr>
                		<tr>
                    		<td height="20" class="txt10">Date Confirmed :</td>
                    		<td height="20" class="txt10"><?php echo $datemodified;?></td>
                		</tr>
                		<?php
                			}
                		?>
                		<?php
                			if ($statid == 8)
                			{
                		?>
                		<tr>
                    		<td height="20" class="txt10">Cancelled By :</td>
                    		<td height="20" class="txt10"><?php echo $confirmedby;?></td>
                		</tr>
                		<tr>
                    		<td height="20" class="txt10">Date Cancelled :</td>
                    		<td height="20" class="txt10"><?php echo $datemodified;?></td>
                		</tr>
                		<?php
                			}
                		?>
                		<tr>
                    		<td height="20" class="txt10">Status :</td>
                    		<td height="20" class="txt10"><?php echo $status;?></td>
                		</tr>
						<tr>
                  			<td height="20" valign="top" class="txt10">Remarks :</td>
                  			<td height="20"><textarea name="txtRemarks" cols="30" rows="3" class="txtfieldnh" <?php if ($statid != 6) echo 'readonly=\"yes\"'; ?> ><?php echo $remarks;?></textarea></td>
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
  			<br />
  			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        	<tr>
          		<td class="tabmin">&nbsp;</td>
          		<td class="tabmin2 txtredbold">Transaction Details</td>
          		<td class="tabmin3">&nbsp;</td>
      		</tr>
   			</table>
     		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
        	<tr>
        		<td valign="top" class="tab">
          			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10" height="25">			
					<tr align="center">
					<?php 
					if ($statid == 6) 
					{
						echo "<td width=\"5%\" class=\"bdiv_r\"><input name='chkAll' type='checkbox' id='chkAll' value='1' onclick='checkAll(this.checked);' /></td>";
					} 
			  		?>	
			        	<td width="5%"  class="bdiv_r" height="20">Line No.</td>
			        	<td width="15%" class="bdiv_r" height="20"><div align="left" class="padl5">Item Code</div></td>
						<td width="25%" class="bdiv_r" height="20"><div align="left" class="padl5">Item Description</div></td>					
						<td width="10%" class="bdiv_r" height="20">SOH</td>
						<td width="10%" class="bdiv_r" height="20">UOM</td>
						<td width="10%" class="bdiv_r" height="20">Qty</td>					
						<td width="20%"  height="20">Reason</td>
					</tr>
					</table>
				</td>
    		</tr>
        	<tr>
        		<td valign="top" class="bgF9F8F7"><div class="scroll_300">
		    		<table width="100%" border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">	
		    		<tr>
                   	<?php
						if ($rs_detailsall->num_rows)
						{		     		
							$ctr = 0;
							$rowalt = 0;
							while ($row = $rs_detailsall->fetch_object())
							{
								$rtid1 = $row->rid;
									                       
								$ctr += 1;
								$rowalt += 1;
								($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
								echo"<tr align='center' class='$class' >";
								
	                    		if ($statid == 6) 
								{
									echo "<td height='20' width='5%' class='borderBR'>
										<input name=\"chkID[]\" id=\"chkID\" type=\"checkbox\" class=\"inputOptChk\"  onclick='CheckInclude()'  value=\"$row->ProductID\" /></td>";
									echo "
										<input name=\"chkID2[]\" id=\"chkID\"  class=\"inputOptChk\" type=\"hidden\" checked=\"checked\"  value=\"$row->ProductID\" />";
								} 
			   		?>
                    	<td width="5%" height="20" class="borderBR"><?php echo $ctr; ?>
                    		<input name="hdnProdID" type="hidden" value="<?php echo $row->ProductID; ?>">
                    		<input name="hdnSOH<?php echo $row->ProductID;?>" type="hidden" value="<?php echo $row->SOH; ?>">
                    		<input name="hdnInvID" type="hidden" value="<?php echo $row->InventoryID; ?>">
                    		<input name="hdnProdIDdrft<?php echo $ctr;?>" type="hidden" value="<?php echo $row->ProductID; ?>">
                    		<input name="hdnInvIDdrft<?php echo $ctr;?>" type="hidden" value="<?php echo $row->InventoryID; ?>">
                    	</td>
                    	<?php if ($statid == 6) 
                    	{?>
                    	<td width="15%" height="20" align="left" class="borderBR padl5"><?php echo $row->ProductCode; ?></td>
                    	<td width="25%" height="20" align="left" class="borderBR padl5"><?php echo $row->ProductName; ?></td>                    
                    	<td width="10%" height="20" class="borderBR"><?php echo  $row->SOH; ?></td>
                    	<td width="10%" height="20" class="borderBR"><?php echo  $row->uom; ?>
                    	<input name="hdnUOMID" type="hidden" value="<?php echo $row->UOMID; ?>"></td>                                            
  						<?php } else {?>
  						<td width="15%" height="20" align="left" class="borderBR padl5"><?php echo $row->ProductCode; ?></td>
                    	<td width="25%" height="20" align="left" class="borderBR padl5"><?php echo $row->ProductName; ?></td>                    
                    	<td width="10%" height="20" class="borderBR"><?php echo  $row->PrevBalance; ?></td>
                    	<td width="10%" height="20" class="borderBR"><?php echo  $row->uom; ?>
                    	<input name="hdnUOMID" type="hidden" value="<?php echo $row->UOMID; ?>"></td>  
  						<?php } ?>
                    	<?php if ($statid == 6) 
                    	{?>
                    			<td width='10%' height='20' class='borderBR'>
                    			<input name="txtQuantity<?php echo $row->ProductID;?>" type="text" class="txtfield" style="width:60px; text-align:right" onKeyPress="return numbersonly(this, event);" value="<?php echo $row->Quantity; ?>"></td>
                    			<td width='20%' height='20' class='borderBR'> <select name="cboReasons<?php echo $row->ProductID;?>" style="width:150px"  class="txtfield">
									                        <?PHP
									                  
									                        $reasonlist = $rs_reasons.$cnt;
									                       	$reasonlist = $sp->spSelectReason($database,5,'');
															echo "<option value=\"0\" >[SELECT HERE]</option>";
									                        if ($reasonlist->num_rows)
									                        {
									                            while ($rowreas =  $reasonlist->fetch_object())
									                            {
									                            	
									                             $rtid = $rtid1;
									                            
									                            ($rtid == $rowreas->ID) ? $sel = "selected" : $sel = "";
									                            echo "<option value='$rowreas->ID' $sel>$rowreas->Name</option>";
									                            }
									                        }
									                        ?>
									                        </select></td>
                    	<?php }else 
                    	{?>
							<td width='10%' height='20' class='borderBR'><?php echo $row->Quantity;?></td>	
							<td width='20%' height='20' class='borderBR'><?php echo $row->reasons; ?></td>
							
						<?php }?>
                    	<input name="hdnQty" type="hidden" value="<?php echo $row->Quantity; ?>">
                </tr>
                <?php 
						}                    
						$rs->close();					
               		}
					else 
					{
						echo "<tr align='center'>
							  <td width='100%' height='20' class='borderBR' colspan='8'>No record(s) to display. </span></td>
							</tr>";                                     
					}
			?>
            </table>
            </div></td>
        </tr>
        </table>
      	<br>
      	<?PHP if ($statid == 6) { ?>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
      	<tr>
      		<td align="left"></td>
      		<td align="right"><input name="btnRemove" type="submit" class="btn" value="Remove" onClick="return CheckItem();"></td>
  		</tr>
  		</table>
        <?php } ?>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
        	<td align="center">
            <?php
				if ($statid == 6) 
				{
					echo "<input name='btnDraft' type='submit' class='btn' value='Save' onClick='return confirmSaveDraft();'>&nbsp;";
					echo "<input name='btnConfirm' type='submit' class='btn' value='Confirm' onclick='return confirmSave();' />&nbsp;";
					echo "<input name='btnDelete' type='submit' class='btn' value='Cancel' onclick='return confirmDelete();' />";	
		    ?>					
			<?php } ?>
				<input name="hdnTXNID" type="hidden" value="<?php echo $_GET['tid']; ?>">
			<?php if ($statid != 6) { echo " <input name='input' type='button' class='btn' value='Print' onclick='openPopUp(hdnTXNID)'/>&nbsp;"; } ?>
            	<input name="btnCancel" type="submit" class="btn" value="Back to List" onclick="return confirmCancel();" />
        	</td>
    	</tr>
    	</table>
    	</td>
	</tr>
	</table>
<br />	
</form>