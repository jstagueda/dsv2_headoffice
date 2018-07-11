<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<?php
/*   
  @modified by John Paul Pineda.
  @date October 8, 2012.
  @email paulpineda19@yahoo.com         
*/

include IN_PATH.DS.'scw2wDetails.php'; 

?>
<script type="text/javascript">
$(document).ready(function(){
	//search_item
	$("#btn_search").bind("click",function(){
		$(".scroll_300").attr("style","display:none;");
		return false;
		
	});
});

function checkAll(bin) {

  var elms=document.frmVwInvCountDetails.elements;

  for(var i=0;i<elms.length;i++)
  if(elms[i].name=='chkID[]') {
  
	  elms[i].checked=bin;		  
  }		
}
  
function CheckItem() {

 count=0;
 str='';
 obj=document.frmVwInvCountDetails.elements["chkID[]"];

 if(obj.length > 1 ) {
 
   for(x=0; x < obj.length; x++) {
   
  	if(obj[x].checked==true) {
    
  	  str+=obj[x].value+',';
  	  count++;
  	}
   }
 } else {
 
	if(obj.checked==true) {
  
	  count++;
	}
 }
 
 if(count==0) {
 
  alert("You didn\'t choose any checkboxes");
  return false;
 }
 document.frmVwInvCountDetails.submit();
 return true;
}
  
function validateConfirm() {
 
  var txtnullcnt=document.frmVwInvCountDetails.hNumNull;

  var msg="";

  if(msg!="") {
  
    alert(msg);
    return false;
  } else {
  
    if(confirm('Are you sure you want to confirm this transaction?')==true) {
    
	   return true;
    } else {
    
      return false;
    }
  }    
}

function confirmCancel() { 
   
  if(confirm('Are you sure you want to cancel this transaction?')==true) {
  
	  window.location.href="index.php?pageid=32";
  } else {
  
    return false;
  }        
}

function NewWindow(mypage, myname, w, h, scroll) {

	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win=window.open(mypage, myname, winprops)
	if(parseInt(navigator.appVersion)>=4) { win.window.focus(); }
}

function validatePrint() {	
	
  var tid=document.frmVwInvCountDetails.hTxnID.value;

   pagetoprint="pages/inventory/<?php echo $ic_warehouse_id=='1'?'inv_countDetails_print.php':'inv_countDetailsInDG_print.php'; ?>?tid="+tid+"&sort=1";
   objWin=NewWindow(pagetoprint,'print','900','900','yes');
   return false;
}    
</script>
<input type = "hidden" value = "<?php echo $ic_warehouse_id; ?>" id = "warehouse_id">
<input type = "hidden" value = "<?php echo $_GET['tid']; ?>" id = "tid">
<?php if($ic_warehouse_id=='1'): ?>
<form name="frmVwInvCountDetails" method="post" action="includes/pcConfirmw2w.php"  >
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">  
    <tr>
  	 <td valign="top">
  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    		<tr>
    			<td class="topnav">
    				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
    		    	<tr>
    		        <td align="right" width="70%">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
    	    		</tr>
    				</table>
    			</td>
    		</tr>
  		</table>
  		<br />
      
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
    		<tr>
      		<td class="txtgreenbold13">View Inventory Count</td>
      		<td align="right">
      			<?php 
      			if (isset($_GET['msg'])) {
            
    					$message=strtolower($_GET['msg']);
    					$success=strpos("$message", "success"); 
    					echo "<span class='txtreds'>".$_GET['msg']."</span>";
  					} 
            ?>
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
                <td class="txtredbold">General Information</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
            
  	  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1" >
        <tr>
          <td valign="top" class="bgF9F8F7">
            <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td width="40%" valign="top">  
    		          <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td width="25%" height="20" class="txt10">Reference No. :</td>
                      <td width="75%" height="20">
                      	<input type="text" name="txtTxnNo" value="<?php echo $transno ; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      	<input type="hidden" name="hTxnID" value="<?php echo $txnid; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td height="20" class="txt10">Document No. :</td>
                      <td height="20" >
                        <input type="text" name="txtDocNo" size="30" value="<?php echo $docno ; ?>" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
    			          <tr>
                      <td height="20" class="txt10">Date Created :</td>
                      <td height="20" >
                        <input type="text" name="txtTxnDate" value="<?php echo $transdate ; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <tr>
                      <td height="20" class="txt10">Warehouse :</td>
                      <td height="20">
                        <input type="text" name="txtWarehouse" value="<?php echo $warehouse ; ?>" size="30" readonly="yes" class="txtfieldLabel" /></td>
                    </tr>
                    <?php if($statusid!= 7): ?>
            		    <tr>
                      <td height="20" class="txt10">Transaction Date :</td>
                      <td height="20" >
                        <input type="text" name="txtTxnDate" value="<?php echo $dateconfirmed ; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
            		    <?php endif; ?>
					
					<tr>
                      <td height="20" class="txt10">&nbsp;</td>
                      <td height="20" >&nbsp;</td>
                    </tr>
                  </table>
    		        </td>
                <td width="60%" valign="top">
                  <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td height="20" class="txt10">Created By :</td>
                      <td height="20">
                        <input type="text" name="" value="<?php echo $createdby; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <?php if($statusid==7): ?>
                    <tr>
                      <td height="20" class="txt10">Confirmed By :</td>
                      <td height="20" >
                        <input type="text" name="" value="<?php echo $confirmedby; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <tr>
                      <td height="20" class="txt10">Date Confirmed :</td>
                      <td height="20">
                        <input type="text" name="" value="<?php echo $dateconfirmed; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td width="25%" height="20" class="txt10">Status :</td>
                      <td width="75%" height="20" >
                        <input type="text" name="" value="<?php echo $status; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <tr>
                      <td height="20" valign="top" class="txt10">Remarks :</td>
                      <td height="20" >
                        <textarea name="txtRemarks" cols="30" rows="5" <?php if($statusid!=1) echo 'readonly="yes"'; ?> class="txtfieldnh"><?php echo $remarks ; ?></textarea>
                      </td>
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
      <br />
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">Transaction Details</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
      
       <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
         <tr>
           <td valign="top" class="bgF9F8F7">
             <div class="scroll_300">
                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="trheader">  
                  <tr>
					  <td align="left"  width="5%"   class="borderBR"><strong>Counter</strong></td>		
					  <td align="left"  width="5%"   class="borderBR"><strong>Location</strong></td>						  
					  <td align="left"  width="10%"  class="borderBR"><strong>Product Code</strong></td>
					  <td align="left"  width="10%"  class="borderBR"><strong>3rd Item Number</strong></td>
					  <td align="left"  width="28%"  class="borderBR"><strong>Description</strong></td>
					  <td align="right" width="7%"   class="borderBR"><strong>Regular Price</strong></td>
					  <td align="right" width="7%"   class="borderBR" height = '40px'><strong>Channel Freezed Qty</strong></td>
					  <td align="right" width="7%"   class="borderBR"><strong>JDE Freezed Qty</strong></td>
					  <td align="right" width="7%"   class="borderBR"><strong>Channel Counted Qty</strong></td>
					  <td align="right" width="7%"   class="borderBR"><strong>Diff.(Channel - JDE)</strong></td>
					  <td align="right" width="7%"   class="borderBR"><strong>Adjustment(JDE - Counted Qty)</strong></td>         
                  </tr>                
				
				 <?php									
   
                  if($rs_detailsall->num_rows) 
				  {
                  
                    while($field=$rs_detailsall->fetch_object()) 
					{
                    
                      $ctr+=1;	
                      $rowalt+=1;
                      $class=($rowalt%2)?'':'bgEFF0EB';     
                      $diff = $field->CHfreezeQty - $field->HOFreezeQty;	


                      $totalCHfreezeQty  = $totalCHfreezeQty + $field->CHfreezeQty;
					  $totalHOFreezeQty  = $totalHOFreezeQty + $field->HOFreezeQty;
					  $totalCHCreatedQty = $totalCHCreatedQty + $field->CHCreatedQty;
					  $totaldiff         = $totaldiff + $diff;
					  $totalADJ          = $totalADJ + $field->AdjustmentQty;
					
				   	  if($field->wcode == 'WH')
				  	  {
						 $totalCHfreezeQtyWH  = $totalCHfreezeQtyWH + $field->CHfreezeQty;
						 $totalHOFreezeQtyWH  = $totalHOFreezeQtyWH + $field->HOFreezeQty;
						 $totalCHCreatedQtyWH = $totalCHCreatedQtyWH + $field->CHCreatedQty;
						 $totaldiffWH         = $totaldiffWH + 	$diff;
						 $totalADJWH          = $totalADJWH + $field->AdjustmentQty;
					  }
					  else
					  {
						 $totalCHfreezeQtyDG  = $totalCHfreezeQtyDG + $field->CHfreezeQty;
						 $totalHOFreezeQtyDG  = $totalHOFreezeQtyDG + $field->HOFreezeQty;
						 $totalCHCreatedQtyDG = $totalCHCreatedQtyDG + $field->CHCreatedQty;
						 $totaldiffDG         = $totaldiffDG + 	$diff;
						 $totalADJDG          = $totalADJDG + $field->AdjustmentQty;
					  }					  
                                                                                            								                                                                  
                    ?>            
					  
                      <tr align="center" class="<?php echo $class; ?>">
                        <input name="hProdID" type="hidden" value="<?php echo $field->ProductID; ?>">
                        <input name="hInvID"  type="hidden" value="<?php echo $field->invdid; ?>">
                        <input name="hQty"    type="hidden" value="<?php echo $field->CreatedQty; ?>">
                        <input name="htxnid" type="hidden" value="<?php echo $_GET['tid']; ?>">
                                                
                        <td align="left"  width="5%"  height="18" class="borderBR"><?php echo $ctr ?></td>
                        <td align="center" width="5%"  height="18" class="borderBR"><?php echo $field->wcode; ?></td>
                        <td align="left" width="10%" height="18" class="borderBR"><?php echo $field->pCode; ?></td>
                        <td align="left" width="10%" height="18" class="borderBR"><?php echo $field->ItemNumber; ?></td>
                        <td align="left"  width="28%" height="18" class="borderBR"><?php echo $field->pName; ?></td>
                        <td align="right" width="7%"  height="18" class="borderBR"><?php echo $field->RegularPrice; ?></td>
                        <td align="right" width="7%"  height="18" class="borderBR"><?php echo $field->CHfreezeQty; ?></td>
                        <td align="right" width="7%"  height="18" class="borderBR"><?php echo $field->HOFreezeQty; ?></td>
                        <td align="right" width="7%"  height="18" class="borderBR"><?php echo $field->CHCreatedQty; ?></td>		
                        <td align="right" width="7%"  height="18" class="borderBR"><?php echo $diff; ?></td>
                        <td align="right" width="7%"  height="18" class="borderBR"><?php echo $field->AdjustmentQty; ?></td>
                      </tr>
                    <?php 
                    }                    
                    $rs_detailsall->close();					
					echo "</div>";
				  }
					else {
                  
                    echo "<tr align='center'>
                    <td colspan='14' height='20' class='borderBR'><div align='center'><span class='txtredsbold'>No record(s) to display.</span></div></td></tr>
                    </tr>";                                     
                  }
                  ?>
                </table>
            </td>
          </tr>
        </table>
        <br />
      
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" >
          <tr>
            <td class="tabmin">&nbsp;</td>
            <td class="tabmin2 txtredbold">Totals</td>
            <td class="tabmin3">&nbsp;</td>
          </tr>
        </table>
        
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3" >          
              
           

            <tr>
			 <td colspan = 6 width ="48%"   class="borderBR" ></td>		
			 <td align="right" width="7%"   class="borderBR" height = '40px'><strong>Channel Freezed Qty</strong></td>
			 <td align="right" width="7%"   class="borderBR" height = '40px'><strong>JDE Freezed Qty</strong></td>
			 <td align="right" width="7%"   class="borderBR" height = '40px'><strong>Channel Counted Qty</strong></td>
			 <td align="right" width="7%"   class="borderBR" height = '40px'><strong>Diff.(Channel - JDE)</strong></td>
			 <td align="right" width="7%"   class="borderBR" height = '40px'><strong>Adjustment(JDE - Counted Qty)</strong></td>         
           </tr>
		   
		  <tr>
			 <td colspan = 6 width ="48%"   class="borderBR" align="right" >WH Totals</td>		
			 <td align="right" width="7%"   class="borderBR" height = '30px'><?php echo $totalCHfreezeQtyWH ?></td>
			 <td align="right" width="7%"   class="borderBR"><?php echo $totalHOFreezeQtyWH ?></td>
			 <td align="right" width="7%"   class="borderBR"><?php echo $totalCHCreatedQtyWH ?></td>
			 <td align="right" width="7%"   class="borderBR"><?php echo $totaldiffWH ?></td>
			 <td align="right" width="7%"   class="borderBR"><?php echo $totalADJWH ?></td>         
           </tr> 

           <tr>
			 <td colspan = 6 width ="48%"   class="borderBR" align="right" >DG Totals</td>		
			 <td align="right" width="7%"   class="borderBR" height = '30px'><?php echo $totalCHfreezeQtyDG ?></td>
			 <td align="right" width="7%"   class="borderBR"><?php echo $totalHOFreezeQtyDG ?></td>
			 <td align="right" width="7%"   class="borderBR"><?php echo $totalCHCreatedQtyDG ?></td>
			 <td align="right" width="7%"   class="borderBR"><?php echo $totaldiffDG ?></td>
			 <td align="right" width="7%"   class="borderBR"><?php echo $totalADJDG ?></td>          
           </tr>	    		   

            <tr>
			 <td colspan = 6 width ="48%"   class="borderBR" align="right" >GRAND Totals</td>		
			 <td align="right" width="7%"   class="borderBR" height = '30px'><strong><?php echo $totalCHfreezeQty ?></strong></td>
			 <td align="right" width="7%"   class="borderBR"><strong><?php echo $totalHOFreezeQty ?></strong></td>
			 <td align="right" width="7%"   class="borderBR"><strong><?php echo $totalCHCreatedQty ?></strong></td>
			 <td align="right" width="7%"   class="borderBR"><strong><?php echo $totaldiff ?></strong></td>
			 <td align="right" width="7%"   class="borderBR"><strong><?php echo $totalADJ ?></strong></td>         
           </tr> 		   

        </table>
        <br />
  
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center">
              <?php if($statusid==6): ?> 
              <input type="submit" name="btnConfirm" value="Confirm" onclick="return validateConfirm();" class="btn" />
              <?php endif; ?>					
              <input type="button" name="btnCancel" value="Cancel" onclick="return confirmCancel();" class="btn" />
            </td>
          </tr>
        </table>
        <br />
      </td>
    </tr>
  </table>
</form>

<?php else: require_once('pages/inventory/inv_vwcountdetailsInDG.php'); endif; ?>
