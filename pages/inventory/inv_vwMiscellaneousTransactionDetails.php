<?PHP 
	include IN_PATH.DS."scViewMiscellaneousTransactionDetails.php";
?>
<script type="text/javascript">
function RemoveInvalidChars(strString, cnt, obj)
{
	var iChars = "1234567890";
   	var strtovalidate = strString.value;
	var strlength = strtovalidate.length;
	var strChar;
	var ctr = 0;
	var newStr = '';
	
	if (strlength == 0)
	{
		return false;
	}

	for (i = 0; i < strlength; i++)
	{	
		strChar = strtovalidate.charAt(i);
		if 	(!(iChars.indexOf(strChar) == -1) || !(("-").indexOf(strChar) == -1))
		{
			newStr = newStr + strChar;
		}
		
	}

	strString.value = newStr; 

	var soh = document.getElementsByName('hdnSOH[]');
	
	for(var i = 0; i < soh.length; i++)
	{
		if(cnt == i + 1)
		{
			var s = parseFloat(soh[i].value);
		
		    if(obj.value < 0 && Math.abs(obj.value) > s)
		    {
		    	alert('Quantity cannot be greater than SOH.');
		    	obj.value = '';			    
		    	return false;
	    	}
	    	break;
	    } 
	}
	
	return true;
}


  function checkAll(bin) 
  {
	  var elms=document.frmVwAdjDetails.elements;
  
	  for(var i=0;i<elms.length;i++)
	  if(elms[i].name=='chkID[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
  }
  
  function CheckItem()
  {
	 count = 0;
	 str = '';
 	obj = document.frmVwAdjDetails.elements["chkID[]"];

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
 
 	if(count==0)
 	{
  		alert("You didn\'t choose any checkboxes");
	  	return false;
  	}
  	
  	document.frmVwAdjDetails.submit();
  	return true;
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function validatePrint(iaID)
{
	
	var objWin;
	pagetoprint = "pages/inventory/inv_vwMiscellaneousTransactionDetails_print.php?iaID="+iaID;
	
	if (!objWin) 
	{
		objWin = NewWindow(pagetoprint,'printps','850','1100','yes');
	}
	
	objWin.focus();
	return false;  		
}	
  
function confirmCancel()
{
	if (confirm('Changed made will not be saved. Proceed?') == false)
   	{
        return false;
   	}    
}

function confirmDelete()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
   	{
   		return false;
	}    
}	

function validateConfirm()
{
	var ml = document.frmVwAdjDetails;
	var mtid = eval(ml.hMTypeID);
	
	if (ml.hdncnt.length > 1)
	{
		for(i = 0; i < ml.hdncnt.length; i++)
		{
			var ctr = i + 1;
			var soh  = eval('document.frmVwAdjDetails.hdnsoh' + ctr);
			var qty  = eval('document.frmVwAdjDetails.txtActQuantity' + ctr);
			var aqty = eval('document.frmVwAdjDetails.hQty' + ctr);
			
			if(isNaN(qty.value))
			{
				alert("Invalid Numeric format.");
				qty.select();
				qty.focus();
				return false;
			}
			
			if(eval(qty.value) == 0)
			{
				alert("Adjusted quantity should be greater than 0.");
				qty.select();
				qty.focus();
				return false;
			}
			
			/*if(eval(soh.value) < eval(qty.value))
			{
				alert('Adjusted quantity should be less than or equal SOH.');
				qty.select();
				qty.focus();
				return false;
			}*/	
			
			if (eval(mtid.value) == 10)
			{
				if(eval(aqty.value) < eval(qty.value))
				{
					alert('Adjusted quantity should be less than or equal Approved quantity.');
					qty.select();
					qty.focus();
					return false;
				}				
			}
		}
	}
	else
	{
		var soh = eval('document.frmVwAdjDetails.hdnsoh1');
		var qty = eval('document.frmVwAdjDetails.txtActQuantity1');
		
		if(isNaN(qty.value))
		{
			alert("Invalid Numeric format.");
			qty.select();
			qty.focus();
			return false;
		}
		
		if(eval(qty.value) == 0)
		{
			alert("Adjusted quantity should be greater than 0.");
			qty.select();
			qty.focus();
			return false;
		}

		/*if(eval(soh.value) < eval(qty.value))
		{
			alert('Adjusted quantity should be less than or equal SOH.');
			qty.select();
			qty.focus();
			return false;
		}*/			
	}
	
	if (confirm('Are you sure you want to confirm this transaction?') == false)
   	{
   		return false;
	}    
}  
</script>



<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<form name="frmVwAdjDetails" method="post" action=""  >
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
	    	<td class="txtgreenbold13">View Miscellaneous Transactions </td>
	    	<td>&nbsp;</td>
	  	</tr>
		</table>
		<?php
		if (isset($_GET['message']))
  		{
  			$message = strtolower($_GET['message']);
	  		$success = strpos("$message","success"); 
  		?>
  		<br>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
  			<td class="txtreds"><b><?php echo $_GET['message']; ?></b></td>
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
              		<td width="40%" valign="top">
			  			<table width="98%"  border="0" cellspacing="1" cellpadding="0">
			  			<tr>
			  				<input name="hTxnID" type="hidden" value="<?php echo $id; ?>">
		                    <td width="25%" height="20" class="txt10">Movement Type :</td>
		                    <td width="75%" height="20" class="txt10"><input type="hidden" name="hMTypeID" value="<?php echo $mid; ?>"><?php echo $mtype?></td>
		                </tr>
		                <tr>
                    		<td height="20" class="txt10">Warehouse :</td>
                    		<td height="20" class="txt10"><?php echo $whouse; ?></td>
                  		</tr>
                  		<tr>
                    		<td height="20" class="txt10">Document No. :</td>
                    		<td height="20" class="txt10"><?php echo $docno; ?></td>
                  		</tr>
                  		</table>
          			</td>
              		<td width="58%" valign="top">
					  	<table width="98%"  border="0" cellspacing="1" cellpadding="0">
					  	<tr>
                    		<td width="25%" height="20" class="txt10">Reference No. :</td>
                    		<td width="75%" height="20" class="txt10"><?php echo $transno; ?></td>
                  		</tr>
					  	<tr>
                    		<td height="20" class="txt10">Transaction Date :</td>
                    		<td height="20" class="txt10"><?php echo $transdate; ?></td>
                  		</tr>
                  		<tr>
                          	<td height="20" class="txt10">Branch Name:</td>
                          	<td height="20"><input name="txtBranch" type="text" class="txtfieldLabel" id="txtBranch" size="20" maxlength="20" readonly="yes" value="<?php echo $branch; ?>"></td>
                        </tr>
                        <tr>
                    		<td height="20" class="txt10">Created By :</td>
                    		<td height="20" class="txt10"><?php echo $createdby; ?></td>
                  		</tr>
                  		<?php
                			if ($statusid == 24)
                			{
                		?>
                		<tr>
                    		<td height="20" class="txt10">Approved By :</td>
                    		<td height="20" class="txt10"><?php echo $approvedby; ?></td>
                  		</tr>
                  		<tr>
                    		<td height="20" class="txt10">Date Approved :</td>
                    		<td height="20" class="txt10"><?php echo $dateconfirmed;?></td>
                		</tr>
                  		<?php
                			}
            			?>
                  		<?php
                			if ($statusid == 7)
                			{
                		?>
                		<tr>
                    		<td height="20" class="txt10">Approved By :</td>
                    		<td height="20" class="txt10"><?php echo $approvedby; ?></td>
                  		</tr>
                  		<tr>
                    		<td height="20" class="txt10">Confirmed By :</td>
                    		<td height="20" class="txt10"><?php echo $confirmedby; ?></td>
                  		</tr>
                  		<tr>
                    		<td height="20" class="txt10">Date Confirmed :</td>
                    		<td height="20" class="txt10"><?php echo $dateconfirmed;?></td>
                		</tr>
                  		<?php
                			}
            			?>
            			<?php
                			if ($statusid == 8)
                			{
                		?>
                  		<tr>
                    		<td height="20" class="txt10">Cancelled By :</td>
                    		<td height="20" class="txt10"><?php echo $confirmedby; ?></td>
                  		</tr>
                  		<tr>
                    		<td height="20" class="txt10">Date Cancelled :</td>
                    		<td height="20" class="txt10"><?php echo $dateconfirmed;?></td>
                		</tr>
                  		<?php
                			}
            			?>
            			<tr>
		                	<td height="20" class="txt10">Status :</td>
			                <td height="20" class="txt10"><?php echo $status; ?></td>
		                </tr>
						<tr>
		                  <td height="20" valign="top" class="txt10">Remarks :</td>
		                  <td height="20" class="txt10"><textarea name="txtRemarks" cols="30" rows="3" class="txtfieldnh" <?php echo 'readonly=\"yes\"'; ?>><?php echo $remarks; ?></textarea></td>
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
			<td class="tab">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
            	<tr align="center">			
            		<td width="5%" height="20" class="bdiv_r">Line No.</td>
              		<td width="20%" height="20" class="bdiv_r">Item Code</td>			  
              		<td width="25%" height="20" class="bdiv_r">Item Description</td>
              		<td width="10%" height="20" class="bdiv_r">SOH</td>
			  		<td width="10%" height="20" class="bdiv_r">UOM</td>
			  		<td width="10%" height="20" class="bdiv_r">Regular Price</td>
              		<td width="10%" height="20" class="bdiv_r">Actual Qty</td>
              		<td width="20%" height="20">Reason</td>
          		</tr>
          		</table>
      		</td>
  		</tr>
  		<tr>
  			<td valign="top" class="bgF9F8F7"><div class="scroll_300">
		  		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
				<?PHP										
					$ctr = 0;
					$totamt = 0;
					$totqty = 0;
					$rowalt = 0;
					if ($rs_invadjdetails->num_rows)
					{						
						while ($row = $rs_invadjdetails->fetch_object())
						{
						
							$ctr += 1;	
							$rowalt += 1;
							($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
							echo"<tr align='center' class='$class'>"; 
			   ?>
			   	<input name="hdncnt" type="hidden" value="<?php echo $cnt; ?>">
			   	<input name="hdnsoh<?php echo $ctr?>" type="hidden" value="<?php echo $row->SOH; ?>">
              	<td width="5%" height="20" class="borderBR"><?php echo $ctr; ?></td>
			  	<td width="20%" height="20" class="borderBR"><?php echo $row->Code; ?><input name="hProdID" type="hidden" value="<?php echo $row->Code; ?>"></td>
			  	<td width="25%" height="20" class="borderBR"><?php echo $row->Name; ?></td>
			  	<?php if($statusid == 6){?>
			  	<td width="10%" height="20" class="borderBR"><?php echo number_format($row->SOH); ?></td>
			  	<?php } else {?>
			  	<td width="10%" height="20" class="borderBR"><?php echo number_format($row->PrevBalance); ?></td>
			  	<?php }?>
			  	<td width="10%" height="20" class="borderBR"><?php echo $row->UOM; ?><input name="hUOMID" type="hidden" value="<?php echo $row->UOM; ?>"></td>
			  	<td width="10%" height="20" class="borderBR"><?php echo number_format($row->regprice,2); ?></td>
			  	<td width="10%" height="20" class="borderBR">
			  	<?php if ($row->stat == 6){?>
			  		<input name="txtActQuantity<?php echo $ctr?>" type="text" class="txtfield" style="width:60px; text-align:right" value="<?php echo $row->CreatedQty; ?>" onkeyup="javascript:RemoveInvalidChars(txtActQuantity<?php echo $ctr?>,<?php echo $ctr?>, this);" />
			  	<?php } else {?>
			  		<?php echo $row->CreatedQty; }?>  			  	
			  	<input name="hQty<?php echo $ctr; ?>" type="hidden" value="<?php echo $row->CreatedQty; ?>"></td>
			  	<input name="hdnSOH[]" type="hidden" value="<?php echo $row->SOH; ?>"/>
			  	<td width="20%" height="20" class="borderBR"> <?php echo $row->Reason; ?></td>
		  	</tr>
			<?php 
						}                    
						$rs_invadjdetails->close();					
               		}
					else 
					{
						echo "<tr align='center'><td width='100%' height='20' class='borderBR' colspan='7'>No record(s) to display. </span></td></tr>";                                     
					}
			?>
			</table>
          </td></div>
      </tr>
      </table>
      <br>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
          <td align="center">
          	<?php if ($statusid == 6 || $statusid == 24): ?>
          		<input name="btnConfirm" type="submit" class="btn" value="Confirm" onclick="return validateConfirm();">
            	<input name="btnDelete" type="submit" class="btn" value="Cancel" onclick="return confirmDelete();">
            <?php endif ?>
            <?php if ($statusid == 7): ?>
            	<input name="btnPrint" type="submit" class="btn" value="Print" onclick='return validatePrint(<?php if(isset($_GET['tid'])) {echo $_GET['tid'];} else{ echo 0;} ?>);' >
            <?php endif ?>
            <input name="btnCancel" type="submit" class="btn" value="Back to List" onclick="return confirmCancel();" >
        </td>
    </tr>
  	</table>
  </td>
</tr>
</table>
<br>
</form>