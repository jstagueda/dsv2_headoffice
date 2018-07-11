<script type="text/javascript">
  
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

	 if (obj.length > 1 ){
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
  

</script>

<?PHP 
	include IN_PATH.DS."scViewAdjustmentDetails.php";
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<form name="frmVwAdjDetails" method="post" action="includes/pcVwAdjustmentDetails.php"  >
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
    <td class="txtgreenbold13">View  Adjustment </td>
    <td>&nbsp;</td>
  </tr>
</table>
<br /> 
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
              <td colspan="2">
				  <?php 
					  if (isset($_GET['msg']))
					  {
						  $message = strtolower($_GET['msg']);
						  $success = strpos("$message","success"); 
						  echo "<div align='left' style='padding:5px 0 0 5px;' class='txtreds'>".$_GET['msg']."</div>";
					  } 
                  ?> 
              </td>
            </tr>
            <tr>
              <td width="50%" valign="top"> 
             
			  <table width="98%"  border="0" cellspacing="1" cellpadding="0">
                  <tr>
                    <td height="20" class="txt10" width="500">Transaction No.</td>
                    <td width="500"><input name="txtTxnNo" type="text" class="txtfield" value="<?php echo $txnno; ?>" size="30" readonly="yes"></td>
                  </tr><input name="hTxnID" type="hidden" value="<?php echo $id; ?>">
				  <tr>
                    <td height="20" class="txt10">Transaction Date</td>
                    <td><input name="txtTxnDate" type="text" class="txtfield" value="<?php echo $txndate; ?>" size="30" readonly="yes"></td>
                  </tr>
				  <tr>
                    <td height="20" class="txt10">Document No.</td>
                    <td><input name="txtDocNo" type="text" class="txtfield" value="<?php echo $docno; ?>" size="30" readonly="yes"></td>
                  </tr>
                  <tr>
                    <td height="20" class="txt10">Status</td>
                    <td><input name="txtStatus" type="text" class="txtfield" value="<?php echo $status; ?>" size="30" readonly="yes"></td>
                  </tr>
                  <tr>
                    <td height="20" class="txt10">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" class="txt10">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
              </table>
			  </td><td valign="top">
			  <table width="98%"  border="0" cellspacing="1" cellpadding="0">
			  	<tr>
                    <td height="20" class="txt10">Location</td>
                    <td><input name="txtWarehouse" type="text" class="txtfield" value="<?php echo $warehouse; ?>" size="30" readonly="yes"></td>
                </tr><input name="hWID" type="hidden" value="<?php echo $wid; ?>">
				<!--<tr>
                    <td height="20" class="txt10">Item Type</td>
                    <td><input name="txtItemType" type="text" class="txtfield" value="<?php echo $itemtype; ?>" size="30" readonly="yes"></td>
                </tr>-->
                <tr>
                    <td height="20" class="txt10">Movement Type</td>
                    <td><input name="txtItemType" type="text" class="txtfield" value="" size="30" readonly="yes"></td>
                </tr>
				<tr>
                  <td height="20" valign="top" class="txt10">Remarks</td>
                  <td><textarea name="txtRemarks" cols="30" rows="5" class="txtfieldnh" <?php if ($statid != 1) echo 'readonly=\"yes\"'; ?>><?php echo $remarks; ?></textarea></td>
                </tr>
              </table>
			 </td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
  </table>      
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="3" class="bgE6E8D9"></td>
                </tr>
              </table>

<br />
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">Transaction Details</td>
               	<td>&nbsp;</td>
              </tr>
          </table></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
     
     
     <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
        <tr>
          <td valign="top" class="tab" width="1020">
          	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
            <tr align="center">			
			  	<?php 
					if ($statid == 1) 
					{
						echo "<td width=\"4%\" class=\"bdiv_r\"><input name='chkAll' type='checkbox' id='chkAll' value='1' onclick='checkAll(this.checked);' /></td>";
					} 
			  	?>			
              	<td width="4%" class="bdiv_r">Count</td>
              	<td width="12%" class="bdiv_r">Product Code</td>			  
              	<td width="22%" class="bdiv_r">Product Name</td>
              	<td width="6%" class="bdiv_r">SOH</td>
			  	<td width="7%" class="bdiv_r">UOM</td>
              	<td width="8%" class="bdiv_r">Quantity</td>
              	<!--<td width="10%" class="bdiv_r">Unit Price</td>
              	<td width="9%" class="bdiv_r">Amount</td>-->			  
              	<td width="18%" >Reason</td>
              	
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td valign="top" class="bgF9F8F7">
		  <div class="scroll_300">
          
		  <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
				<?PHP										
					$ctr = 0;
					$totamt = 0;
					$totqty = 0;
					$rowalt = 0;
					if ($rs_detailsall->num_rows)
					{						
						while ($row = $rs_detailsall->fetch_object())
						{
							$ctr += 1;	
							$totamt = $totamt + $row->Amt;
							$totqty = $totqty + $row->Qty;	
							$rowalt += 1;
							($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
							echo"<tr align='center' >";
		
							if ($statid == 1) 
							{
								echo "<td height='20' width='4%' class='borderBR'>
									<input name=\"chkID[]\" type=\"checkbox\" class=\"inputOptChk\" value=\"$row->ProductID\" /></td>";
							} 
			   ?>
              
			  <td width="4%" height="20" class="borderBR"><?php echo $ctr; ?></td>
			  <td width="12%" height="20" class="borderBR">
			  	<?php echo $row->ProductCode; ?>
                <input name="hProdID" type="hidden" value="<?php echo $row->ProductID; ?>">
                <input name="hInvID<?php echo $ctr; ?>" type="hidden" value="<?php echo $row->InventoryID; ?>">
              </td>
			  <td width="22%" height="20" class="borderBR"><?php echo $row->Product; ?></td>
			  <td width="6%" height="20" class="borderBR"><?php echo number_format($row->SOH); ?></td>
			  <td width="7%" height="20" class="borderBR">
			  	<?php echo $row->UOM; ?>
                <input name="hUOMID" type="hidden" value="<?php echo $row->UOMID; ?>">
              </td>
			  <td width="8%" height="20" class="borderBR">
			  	<?php echo $row->Qty; ?>
                <input name="hQty<?php echo $ctr; ?>" type="hidden" value="<?php echo $row->Qty; ?>">
              </td>
			  <!--<td width="10%" height="20" class="borderBR"><?php echo number_format($row->UnitPrice,2); ?></td>
			  <td width="9%" height="20" class="borderBR"><?php echo number_format($row->Amt,2); ?></td>-->
			  <td width="18%" height="20" class="borderBR"><?php echo $row->Reason; ?></td>
			</tr>
			<?php 
			 		
						}                    
						$rs->close();					
               		}
					else 
					{
						echo "<tr align='center'>
							  <td width='100%' height='20' class='borderBR' colspan=10>No record(s) to display. </span></td>
							</tr>";                                     
					}
			?>
			<tr class="bgE6E8D9">
				<?php 
					if ($statid == 1) 
					{
						echo "<td height='20' class=''>&nbsp;</td>";
					}
				?>
              	<td height="20" align="left"><span style="font:Arial, Helvetica, sans-serif; font-size:9px">&nbsp;</span></td>
			 	<td height="20" align="left"><span style="font:Arial, Helvetica, sans-serif; font-size:9px"><strong>&nbsp;TOTAL {per class} :</strong></span></td>
			  	<td height="20"><span style="font:Arial, Helvetica, sans-serif; font-size:9px">&nbsp;</span></td>			  	
			  	<td height="20" align="center"><strong><?php echo $totqty; ?></strong></td>
			  	<td height="20" align="center"><strong></strong></td> 
				<td  height="20">&nbsp;</td>
			  	<!--<td  height="20" align="center">&nbsp;</td>								
			  	<td  height="20" align="center"><strong><?php echo number_format($totamt,2); ?></strong></td>-->								
			  	<td  height="20"><strong>&nbsp;</strong></td>
			</tr>
          </table>
          </div>
		<tr class="bgE6E8D9">
            <td height="20">
            <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                <tr class="bgE6E8D9">
                    <?php 
						if ($statid == 1) 
						{
							echo "<td width='4%' height='20' class=''>&nbsp;</td>";
						}
					?>
                    <td width="4%" height="20" align="left"><span style="font:Arial, Helvetica, sans-serif; font-size:9px">&nbsp;</span></td>
                    <td width="12%" height="20" align="left"><span style="font:Arial, Helvetica, sans-serif; font-size:9px"><strong>&nbsp;GRAND TOTAL :</strong></span></td>
                    <td width="22%" height="20"><span style="font:Arial, Helvetica, sans-serif; font-size:9px">&nbsp;</span></td>
                    <td width="6%" height="20" align="center"><span style="font:Arial, Helvetica, sans-serif; font-size:9px"><strong><?php echo $totqty; ?></strong></span></td>
                    <td width="7%" height="20" align="center"><span style="font:Arial, Helvetica, sans-serif; font-size:9px"><strong>&nbsp;</strong></span></td> 
                    <td width="8%" height="20"><span style="font:Arial, Helvetica, sans-serif; font-size:9px">&nbsp;</span></td>
                    <!--<td width="10%" height="20" align="center"><strong><span style="font:Arial, Helvetica, sans-serif; font-size:9px"></span></strong></td>								
                    <td width="9%" height="20" align="center">
                    	<span style="font:Arial, Helvetica, sans-serif; font-size:9px"><strong>&nbsp;<?php echo number_format($totamt,2); ?></strong></span>
                    </td>-->
                    <td width="18%" height="20"><span style="font:Arial, Helvetica, sans-serif; font-size:9px">&nbsp;</span></td>
                </tr>
              </table> 
		  </td>
        </tr>
        </table>
      <br>
      
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td align="left">&nbsp;</td>
    <td align="right">
    		<?PHP
				if ($statid == 1) 
				{
    				echo "<input name='btnRemove' type='button' class='btn' value=' Remove ' onClick='return CheckItem();' >"; 
				}
			?>
					
					
					</td>
  </tr>
</table>
 	   
		<br>
  	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td align="center">
          	<?PHP
				if ($statid == 1) 
				{
					echo "<input name='btnConfirm' type='submit' class='btn' value='Confirm'  />&nbsp;";
					echo "<input name='btnDelete' type='submit' class='btn' value='Delete' />";?>					
			<?php
            	}				
			?>
            <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=3'" >
            <!--<input name="btnPrint" type="submit" class="btn" value="Print" >-->
		  </td>
	    </tr>
  </table><br></td>
  </tr>
</table>
</form>