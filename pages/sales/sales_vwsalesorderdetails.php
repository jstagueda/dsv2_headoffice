<?php
/*   
  @modified by John Paul Pineda
  @date May 4, 2013
  @email paulpineda19@yahoo.com         
*/

require_once(IN_PATH.DS.'scViewSalesOrderDetails.php'); 
?>

<link type="text/css" href="css/jquery-ui-1.8.5.custom.css" rel="stylesheet" />

<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="js/jsUtils.js"></script>
<script type="text/javascript" src="js/jxViewSalesOrder.js"></script>
<script type="text/javascript" src="js/sessionexpire.js"></script>

<script type="text/javascript">
document.onkeydown=test;

function setEvents() {

  if(window.event) document.onkeydown=test;  
}

function test(e) {

  var keyId=(window.event)?event.keyCode:e.keyCode;  
  
  if(keyId==116) {
  		    		           
    var rep=String(window.location);	
    var split=rep.split("&"); 
    
    document.getElementById('hdncnt').value=1;
    window.location.href=split[0]+'&'+split[1]+'&locked=1';
    
    return false;  
  }
}
</script>

<style>
<!--
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}
-->
</style>

<body onload="set_interval();" onmousemove="reset_interval();" onclick="reset_interval();" onkeypress="reset_interval();" onunload="unlock_trans(<?php echo $_GET["TxnID"]; ?>, 1);">
  <form name="frmViewSalesOrder" method="post" action="index.php?pageid=35.1&TxnID=<?php echo $_GET["TxnID"];?>">
    <input type="hidden" name="hdncnt" id="hdncnt" value="0" />
  
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="topnav">
                <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td align="right" width="70%">&nbsp;<a href="index.php?pageid=18&tableid=1&txnid=<?php echo $_GET['TxnID']; ?>" class="txtblueboldlink">Sales Main</a></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <br />
          <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td class="txtgreenbold13">View Sales Order</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <br />
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="tabmin">&nbsp;</td>
              <td class="tabmin2">
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td class="txtredbold">General Information </td>
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
                      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                        <tr>
                          <td height="20" align="right" class="txt10">Customer Code  </td>
                          <td height="20" class="txt10">:</td>
                          <td height="20"><?php echo $custcode; ?></td>
                        </tr>
                        <tr>
                          <td height="20" align="right" class="txt10">Customer Name  </td>
                          <td height="20" class="txt10">:</td>
                          <td height="20"><?php echo $custname; ?></td>
                        </tr>
                        <tr>
                          <td height="20" width="25%"align="right" class="txt10">IBM No / IBM Name  </td>
                          <td height="20" width="1%"class="txt10">:</td>
                          <td height="20" width=70%"><?php echo $ibm; ?></td>
                        </tr>
                        <tr>
                          <td height="20" width="25%"align="right" class="txt10">Type  </td>
                          <td height="20" width="1%"class="txt10">:</td>
                          <td height="20" width=70%"><?php echo $gsutype; ?></td>
                        </tr>                                                                        
                        <tr>
                          <td height="20" align="right" class="txt10">Credit Limit  </td>
                          <td height="20">:</td>
                          <td height="20"><?php echo number_format($creditLimit, 2); ?></td>
                        </tr>
                        <tr>
                          <td height="20" align="right" class="txt10">AR Balance  </td>
                          <td height="20">:</td>
                          <td height="20"><?php echo number_format($ARBalance, 2); ?></td>
                        </tr>
                        <tr>
                          <td height="20" align="right" class="txt10">Unpaid Penalty  </td>
                          <td height="20">:</td>
                          <td height="20"><?php echo number_format($outstandingpenalty, 2); ?></td>
                        </tr>
                        <tr>
                          <td height="20" align="right" class="txt10">Available Credit  </td>
                          <td height="20">:</td>
                          <td height="20"><?php echo number_format($availableCredit, 2); ?></td>
                        </tr>
                      </table>
                    </td>
                    <td valign="top">
                      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                        <tr>
                          <td height="20" align="right" class="txt10">Sales Order No. </td>
                          <td height="20" class="txt10">:</td>
                          <td height="20"><?php echo $sono; ?></td>
                        </tr>                                                
                        <tr>
                          <td width="25%" height="20" align="right" class="txt10">Sales Order Date  </td>
                          <td width="1%" height="20" class="txt10">:</td>
                          <td width="70%" height="20"><?php echo $txndate; ?></td>
                        </tr>
                        <tr>
                          <td height="20" align="right" class="txt10">Branch  </td>
                          <td height="20">:</td>
                          <td height="20"><?php echo $branch; ?></td>
                        </tr>
                        <tr>
                          <td height="20" align="right" class="txt10">Created By </td>
                          <td height="20">:</td>
                          <td height="20"><?php echo $createdby; ?></td>
                        </tr>                        
                        <tr>
                          <td height="20" align="right" class="txt10">S0 Status  </td>
                          <td height="20">:</td>
                          <td height="20"><?php echo $status; ?></td>
                        </tr>
                        <tr>
                          <td height="20" align="right" class="txt10">Confirmed By </td>
                          <td height="20">:</td>
                          <td height="20"><?php echo $confirmedby; ?></td>
                        </tr>                        
                        <tr>
                          <td height="20" align="right" class="txt10">Payment Terms   </td>
                          <td height="20" class="txt10">:</td>
                          <td height="20"><?php echo $paymentTerm; ?></td>
                        </tr>                        
                        <tr>
                          <td width="25%" height="20" align="right" class="txt10">Document No.  </td>
                          <td width="1%" height="20" class="txt10">:</td>
                          <td width="70%" height="20"><?php echo $docno; ?></td>
                        </tr>
                        <tr>
                          <td height="20" align="right" class="txt10">Remarks   </td>
                          <td height="20" valign="top" class="txt10"></td>
                          <td height="20"><?php echo $remarks; ?></td>
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
                    <td class="txtredbold">Sales Order Details </td>
                  </tr>
                </table>
              </td>
              <td class="tabmin3">&nbsp;</td>
            </tr>
          </table>               
          <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
            <tr>
              <td valign="top" class="tab" width="1020">
                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10" height="25">
                  <tr align="center">
                    <td width="5%" valign="middle" align="center">Line No.</td>	
                    <td width="7%" valign="middle" align="left" class="padl5">Item Code</td>
                    <td width="20%" valign="middle" align="left" class="padl5">Item Name</td>
                    <td width="5%" align="center" valign="middle">UOM</td>
                    <td width="7%" align="center" valign="middle" class="padr5">PMG</td>
                    <td width="7%" align="center" valign="middle" class="padr5">Regular Price</td>
                    <?php /* ?><td width="7%" valign="middle" align="middle" class="padr5">Promo</td><?php */ ?>
                    <td width="10%" valign="middle" align="middle" class="padr5">SOH</td>				  
                    <td width="7%" valign="middle" align="middle" class="padr5">Intransit Qty</td>
                    <td width="7%" valign="middle" align="middle" class="padr5">Ordered Qty</td>
                    <td width="7%" valign="middle" align="middle" class="padr5">CSP</td>
                    <td width="10%" valign="middle" align="right" class="padr5">Total Price</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td valign="top" class="bgF9F8F7">
                <div class="scroll_250">
                  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                    <?php
                    $cnt=0;
                    $totamt=0;
                    ?>
                    <?php if($rs_details->num_rows): ?>
                    <?php	while($row=$rs_details->fetch_object()): $cnt++; ?>
                    <?php                                                                                              
                    $class=($cnt%2)?'':'bgEFF0EB';                                              
                    $totalPrice=($row->orderedQty*$row->CSP);
                    ?>			
                    <tr align="center" class="<?php echo $class; ?>">
                      <td width="5%" height="20"><?php echo $cnt; ?></td>
                      <td width="7%" height="20" align="left" class="padl5"><?php echo $row->ItemCode; ?></td>
                      <td width="20%" height="20" align="left" class="padl5"><?php echo $row->ItemName; ?></td>
                      <td width="5%" height="20"><?php echo $row->UOM; ?></td>
                      <td width="7%" height="20"><?php echo $row->PMGID; ?></td>
                      <td width="7%" height="20"><?php echo number_format($row->RegularPrice, 2); ?></td>
                      <?php /* ?><td width="7%" height="20" align="right" class="padr5"><?php echo $row->PromoDesc; ?></td><?php */ ?>
                      <td width="10%" height="20" align="center" class="padr5"><?php echo $row->SOH; ?></td>
                      <td width="7%" height="20" align="center" class="padr5"><?php echo $row->intransitQty; ?></td>	
                      <td width="7%" height="20" align="center" class="padr5"><?php echo $row->orderedQty; ?></td>				 
                      <td width="7%" height="20" align="center" class="padr5"><?php echo number_format($row->CSP, 2); ?></td>				 
                      <td width="10%" height="20" align="right" class="padr5"><?php echo number_format($totalPrice, 2); ?></td>				 			 
                    </tr>                                        
                    <?php $totamt+=$row->TotalAmount; endwhile; ?>
                    
                    <?php if($rsAppliedPromoEntitlements->num_rows): ?>
                    <tr>
                      <td align="center" valign="middle" colspan="12">                                                  
                        <?php while($field=$rsAppliedPromoEntitlements->fetch_object()): ?>
                        <?php
                        $promo_code=$field->PromoCode;
                        $promo_type=$field->PromoType;
                        $entitlement_criteria=$field->PromoEntitlementCriteria;                                                
                        
                        $product_quantity_html=($promo_type=='Single Line' && $entitlement_criteria=='Price')?'':('<td align="center" class="borderBR">'.($field->Quantity+$field->ProductOutstandingQuantity).'</td>');
                        
                        $promo_entitlement_netamount=number_format(((float)$field->NetAmount), '2', '.', ',');
                                                                        
                        $applied_promo_entitlements.=<<<Applied_Promo_Entitlements
                        <tr>
                          <td align="center" class="borderBR">{$promo_code}</td>                                                    
                          <td align="center" class="borderBR">{$field->ItemCode}</td>
                          <td align="center" class="borderBR">{$field->ItemName}</td>
                          <td align="center" class="borderBR">{$field->PMGCode}</td>
                          $product_quantity_html
                          <td align="center" class="borderBR">{$field->Quantity}</td>
                          <td align="center" class="borderBR">{$field->ProductOutstandingQuantity}</td>
                          <td align="center" class="borderBR">{$field->CSP}</td>
                          <td align="center" class="borderBR">{$promo_entitlement_netamount}</td>                          
                        </tr>
Applied_Promo_Entitlements;
                        ?>  
                        <?php endwhile; ?>
                        <br />
                        <table align="center" border="0" cellpadding="2" cellspacing="2" width="72%">
                          <tr class="bgEFF0EB">
                            <td align="center" valign="middle" colspan="9" class="borderBR"><b>Applied Promo(s)</b></td>                                                        
                          </tr>                          
                          <tr>
                            <td align="center" valign="middle" class="borderBR"><b>Promo Code</b></td>                                                        
                            <td align="center" valign="middle" class="borderBR"><b>Item Code</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>Item Name</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>PMG</b></td>
                            <?php if($promo_type=='Single Line' && $entitlement_criteria=='Price'): else: ?><td align="center" valign="middle" class="borderBR"><b>Ordered Qty</b></td><?php endif; ?>
                            <td align="center" valign="middle" class="borderBR"><b>Served Qty</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>Back Order</b></td>
                            <td align="center" valign="middle" class="borderBR padl5"><b>CSP</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>Net Amount</b></td>                            
                          </tr>
                          <?php echo $applied_promo_entitlements; ?>  
                        </table>
                        <br />
                      </td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php if($rsAppliedIncentiveEntitlements->num_rows): ?>
                    <tr>
                      <td align="center" valign="middle" colspan="12">                                                  
                        <?php while($field=$rsAppliedIncentiveEntitlements->fetch_object()): ?>
                        <?php
                        $incentive_code=$field->IncentiveCode;
                        $incentive_type=$field->IncentiveType;
                        $entitlement_criteria=$field->IncentiveEntitlementCriteria;                                                
                        
                        $product_quantity_html=($incentive_type=='Single Line' && $entitlement_criteria=='Price')?'':('<td align="center" class="borderBR">'.($field->Quantity+$field->ProductOutstandingQuantity).'</td>');
                        
                        $incentive_entitlement_netamount=number_format(((float)$field->NetAmount), '2', '.', ',');
                                                                        
                        $applied_incentive_entitlements.=<<<Applied_Incentive_Entitlements
                        <tr>
                          <td align="center" class="borderBR">{$incentive_code}</td>                                                    
                          <td align="center" class="borderBR">{$field->ItemCode}</td>
                          <td align="center" class="borderBR">{$field->ItemName}</td>
                          <td align="center" class="borderBR">{$field->PMGCode}</td>
                          $product_quantity_html
                          <td align="center" class="borderBR">{$field->Quantity}</td>
                          <td align="center" class="borderBR">{$field->ProductOutstandingQuantity}</td>
                          <td align="center" class="borderBR">{$field->CSP}</td>
                          <td align="center" class="borderBR">{$incentive_entitlement_netamount}</td>                          
                        </tr>
Applied_Incentive_Entitlements;
                        ?>  
                        <?php endwhile; ?>
                        <br />
                        <table align="center" border="0" cellpadding="2" cellspacing="2" width="72%">
                          <tr class="bgEFF0EB">
                            <td align="center" valign="middle" colspan="9" class="borderBR"><b>Applied Incentive(s)</b></td>                                                        
                          </tr>                          
                          <tr>
                            <td align="center" valign="middle" class="borderBR"><b>Incentive Code</b></td>                                                        
                            <td align="center" valign="middle" class="borderBR"><b>Item Code</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>Item Name</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>PMG</b></td>
                            <?php if($incentive_type=='Single Line' && $entitlement_criteria=='Price'): else: ?><td align="center" valign="middle" class="borderBR"><b>Ordered Qty</b></td><?php endif; ?>
                            <td align="center" valign="middle" class="borderBR"><b>Served Qty</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>Back Order</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>CSP</b></td>
                            <td align="center" valign="middle" class="borderBR"><b>Net Amount</b></td>                            
                          </tr>
                          <?php echo $applied_incentive_entitlements; ?>  
                        </table>
                        <br />
                      </td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php else: ?>                    
                    <tr align="center">
                      <td width="100%" height="20" colspan="10" class="borderBR">
                        <span class="txt10 style1"><strong>No record(s) to display.</strong></span>
                      </td>
                    </tr>
                    
                    <?php endif; ?>
                  </table>
                </div>
              </td>
            </tr>            
          </table>
      		<br />
          <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td align="center">
                <input type="submit" id="btnCancel" name="btnCancel" value="Cancel" class="btn">&nbsp;                 
                <?php if($status=='UNCONFIRMED'): ?>                
                <input type="submit" id="btnConfirm" name="btnConfirm" value="Confirm" onclick="return validateConfirm();" class="btn">&nbsp;
                <input type="submit" id="btnDelete" name="btnDelete" value="Delete" onclick="return validateDelete();" class="btn" />
                <?php endif; ?>
              </td>
            </tr>
          </table>
      		<br />
        </td>
      </tr>
    </table>
  </form>			
</body>