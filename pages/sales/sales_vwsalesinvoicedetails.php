<?php
/*   
  @modified by John Paul Pineda
  @date May 4, 2013
  @email paulpineda19@yahoo.com         
*/

include(IN_PATH.DS.'scViewSalesInvoiceDetails.php'); 
?>

<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="js/jxConfirmSalesInvoice.js?ver=2"></script>
<script type="text/javascript" src="js/sessionexpire.js"></script>

<script type="text/javascript">
document.onkeydown=test;

function setEvents() {

  if(window.event) document.onkeydown=test;  
}

function test(e) {

  var keyId=(window.event)?event.keyCode:e.keyCode;
  //alert(keyId)
  
  if(keyId==116) {
  		    		     
    var rep=String(window.location);	
    var split=rep.split("&"); 
    
    document.getElementById('hdncnt').value=1;
    window.location.href=split[0]+'&'+split[1]+'&locked=1';
    return false;  
  }
}
</script>

<body onload="set_interval();" onmousemove="reset_interval();" onclick="reset_interval();" onkeypress="reset_interval();" onunload="unlock_trans(<?php echo $_GET["tid"]; ?>, 2);"> 	
  <form name="frmViewSI" action="index.php?pageid=40.1&tid=<?php echo $_GET['tid']; ?>" method="post">
    <input type="hidden" id="hdncnt" name="hdncnt" value="0" />
    
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="topnav">
                <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18&tableid=2&txnid=<?php echo $_GET["tid"]; ?>">Sales Main</a></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <br />
          <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td class="txtgreenbold13">View Sales Invoice</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <?php if($errmsg!=''): ?>
          <br />
          <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td>
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td width="70%" class="txtreds">&nbsp;<b><?php echo $errmsg; ?></b></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <?php	endif; ?>
          <br />
          <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
                      <table width="98%"  border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td height="25" class="txt10"><b>Customer Code</b></td>
                          <td>
                          <input type="hidden" id="hCustomerID" name="hCustomerID" value="<?php echo $custId; ?>">
                          <input name="txtCustCode" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $custcode; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>Customer Name</b></td>
                          <td><input name="txtCustName" type="text" class="txtfieldLabel" size="40" readonly="yes" value="<?php echo $custname; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" valign="top" class="txt10"><b>IBM No / IBM Name</b></td>
                          <td><input name="txtSalesman" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $ibm; ?>"/></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>Credit terms</b></td>
                          <td><input name="txtTerms" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $terms; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>SI Due Date</b></td>
                          <td><input name="txtEffDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $effectivitydate; ?>"></td>
                        </tr>                                                                                                                        
                      </table>
                    </td>
                    <td valign="top">
                      <table width="98%"  border="0" cellspacing="1" cellpadding="0">
                        <tr>
                          <td height="25" class="txt10" width="300"><b>SI No.</b></td>
                          <td width="500">
                            <input type="hidden" id="hTxnID" name="hTxnID" value="<?php echo $_GET['tid']; ?>">
                            <input name="txtTxnNo" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $sino; ?>">
                          </td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10" width="300"><b>Document No.</b></td>
                          <td><input name="txtDocNo" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $statid==6?$bir_series:$docno; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>Sales Invoice Date</b></td>
                          <td>
                            <input type="hidden" id="hTxnDate" name="hTxnDate" value="<?php echo $txnDateFormat; ?>">
                            <input name="txtSIDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $txndate; ?>">
                          </td>
                        </tr>  
                        <tr>
                          <td height="25" class="txt10"><b>Reference SO No.</b></td>
                          <td><input name="txtRefNo" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $refno; ?>"></td>
                        </tr>    
                        <tr>
                          <td height="25" class="txt10"><b>SO Date</b></td>
                          <td><input name="txtDRDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $drdate; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>Branch</b></td>
                          <td><input name="txtDRDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $branch; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>Created By</b></td>
                          <td><input name="txtDRDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $employee; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>Status :</b></td>
                          <td><input name="txtDRDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $status; ?>"></td>
                        </tr>
                        
                        <?php if($statid==7 || $statid==8): ?>
                        <tr>
                          <td height="25" class="txt10"><b>Confirmed By:</b></td>
                          <td><input name="txtDRDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $employee; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>Date Confirmed:</b></td>
                          <td><input name="txtDRDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $txndate; ?>"></td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php if($statid==8): ?>
                        <tr>
                          <td height="25" class="txt10"><b>Cancelled By:</b></td>
                          <td><input name="txtDRDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $employee; ?>"></td>
                        </tr>
                        <tr>
                          <td height="25" class="txt10"><b>Date Cancelled:</b></td>
                          <td><input name="txtDRDate" type="text" class="txtfieldLabel" size="30" readonly="yes" value="<?php echo $txndate; ?>"></td>
                        </tr>
                        <?php endif; ?>
                        
                        <tr>
                          <td height="20" valign="top" class="txt10"><b>Remarks</b></td>
                          <td><textarea name="txtRemarks" cols="30" rows="5" wrap="hard" class="txtfieldnh"><?php echo $remarks; ?></textarea></td>
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
              <td class="tabmin2">
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td class="txtredbold">Sales Invoice Details</td>
                  </tr>
                </table>
              </td>
              <td class="tabmin3">&nbsp;</td>
            </tr>
          </table>
          
          <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
            <tr>
              <td class="tab">
                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                  <tr align="center">
                    <td width="5%" height="20" class="bdiv_r">Line No.</td>
                    <td width="10%" height="20" class="bdiv_r">Product Code</td>   
                    <td width="20%" height="20" class="bdiv_r">Product Name</td>
                    <td width="7%" height="20" class="bdiv_r">UOM</td>
                    <td width="10%" height="20" class="bdiv_r">PMG</td>
                    <td width="10%" height="20" class="bdiv_r">Promo</td>
                    <td width="10%" height="20" class="bdiv_r">Ordered Qty</td>
                    <td width="10%" height="20" class="bdiv_r">Served Qty</td>
                    <td width="10%" height="20" class="bdiv_r">CSP</td>
                    <td width="8%" height="20">Net Amount</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td valign="top" class="bgF9F8F7">
                <div class="scroll_300">
                  <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                    <?php                            
                    $ctr=0;
                    $totamt=0;
                    $totorderedqty=0;
                    $totdeliveredqty=0;
                    $rowalt=0;
                    $totalcft=0;
                    $totalnoncft=0;
                                                            
                    if($rs_detailsall->num_rows):
                     
                    while($row=$rs_detailsall->fetch_object()):                                        
                    if($row->PMGID==1) $totalcft+=$row->TotalAmount;                       
                    elseif($row->PMGID==2) $totalnoncft+=$row->TotalAmount;
                                        
                    $ctr+=1;  
                    $totamt=$totamt+$row->TotalAmount;                   
                    $rowalt+=1;
                    $class=($rowalt%2)?'':'bgEFF0EB';                                                            
                    ?>
                    <tr align="center">
                      <td width="5%" height="20" class="borderBR"><?php echo $ctr; ?></td>
                      <td width="10%" height="20" class="borderBR"><?php echo $row->ProductCode; ?></td>
                      <td width="20%" height="20" class="borderBR"><?php echo $row->ProductName; ?></td>
                      <td width="7%" height="20" class="borderBR"><?php echo $row->UOM; ?></td>
                      <td width="10%" height="20" class="borderBR"><?php echo $row->PMG; ?></td>
                      <td width="10%" height="20" class="borderBR"><?php echo $row->Promo . ' - ' . $row->PromoType; ?></td>
                      <td width="10%" class="borderBR"><?php echo $row->OrderedQty; ?></td>
                      <td width="10%" height="20" class="borderBR"><?php echo $row->ServedQty; ?></td>
                      <td width="10%" height="20" class="borderBR"><?php echo number_format($row->UnitPrice,2); ?></td>
                      <td width="8%" height="20" class="borderBR" align="right" style="padding-right: 5px;"><?php echo number_format($row->TotalAmount, 2); ?></td>
                    </tr>             
                    <?php endwhile; $rs->close(); ?>
                    
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
                            <td align="center" valign="middle" class="borderBR"><b>CSP</b></td>
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
                      <td width="100%" height="20" class="borderBR" colspan="10"><span>No record(s) to display.</span></td>
                    </tr>
                                                         
                    <?php endif; ?>                        
                  </table>
                </div>
              </td>
            </tr>
            <tr class="bgF9F8F7">
              <td class="tab">
                <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td colspan="4" height="20">&nbsp;</td>
                  </tr>       
                  <tr align="center">
                    <td width="30%" height="20" align="right" class="borderBR txt10">
                      <div align="right"><strong>Total CFT :</strong>&nbsp;</div>
                    </td>
                    <td width="30%" height="20" class="borderBR">
                      <div align="right" class="padr5">
                        <input type="hidden" name="hTotalCFT" id="hTotalCFT" value="<?php echo $totalCFT; ?>">
                        <strong><?php echo number_format($totalCFT, 2); ?></strong>
                      </div>
                    </td>          
                    <td width="25%" height="20" class="borderBR"><div align="right"><strong>Gross Amount :&nbsp;</strong></div></td>
                    <td width="15%" height="20" class="borderBR"><div align="right"><strong><?php echo number_format($totgrossamt, 2); ?></strong>&nbsp;</div></td>
                  </tr>
                  <tr align="center">
                    <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total NCFT :</strong>&nbsp;</div></td>
                    <td height="20" class="borderBR">
                      <div align="right" class="padr5">
                        <input type="hidden" name="hTotalNCFT" id="hTotalNCFT" value="<?php echo $totalNCFT; ?>">
                        <strong><?php echo number_format($totalNCFT, 2); ?></strong>
                      </div>
                    </td>           
                    <td height="20" class="borderBR"><div align="right"><strong>Total CSP Less (CPI  <?php echo $totalCPI; ?>) :&nbsp;</strong></div></td>
                    <td height="20" class="borderBR">
                      <div align="right"><strong><?php echo number_format($totgrossamt-$totalCPI, 2); ?></strong>&nbsp;</div>
                    </td>
                  </tr>            
                  <tr align="center">
                    <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>MTD CFT :</strong>&nbsp;</div></td>
                    <td height="20" class="borderBR">
                      <div align="right" class="padr5"><strong><?php echo $statid==6?number_format(($MTDCFT+$totalCFT), 2):number_format($qMTDCFT, 2); ?></strong></div>
                    </td>           
                    <input type="hidden" name="hMTDCFT" id="hMTDCFT" value="<?php echo $MTDCFT+$totalCFT; ?>" />
                    <td height="20" class="borderBR"><div align="right"><strong>Basic Discount :&nbsp;</strong></div></td>
                    <td height="20" class="borderBR">
                      <div align="right"><strong><?php echo number_format($basicdiscount, 2); ?></strong>&nbsp;</div>
                    </td>
                  </tr>
                  <tr align="center">
                    <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>MTD NCFT :</strong>&nbsp;</div></td>
                    <td height="20" class="borderBR">
                      <div align="right" class="padr5">
                        <strong><?php echo $statid==6?number_format(($MTDNCFT+$totalNCFT), 2):number_format($qMTDNCFT, 2); ?></strong>
                      </div>
                    </td>           
                    <input type="hidden" name="hMTDNCFT" id="hMTDNCFT"  value="<?php echo $MTDNCFT+$totalNCFT ; ?> " />
                    <td height="20" class="borderBR"><div align="right"><strong>Additional Discount :&nbsp;</strong></div></td>
                    <td height="20" class="borderBR">
                      <div align="right"><strong><?php echo number_format($additionaldiscount, 2); ?></strong>&nbsp;</div>
                    </td>
                  </tr>
                  <?php $saleswithvat=($totgrossamt-$basicdiscount-$additionaldiscount); ?>
                  <tr align="center">
                    <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>YTD CFT : :</strong>&nbsp;</div></td>
                    <td height="20" class="borderBR">
                      <div align="right" class="padr5">
                        <strong><?php echo $statid==6?number_format(($YTDCFT+$totalCFT), 2):number_format($qYTDCFT, 2); ?></strong>
                      </div>
                    </td>           
                    <input type="hidden" name="hYTDCFT" id="hYTDCFT" value="<?php echo $YTDCFT+$totalCFT; ?>" />
                    <td height="20" class="borderBR"><div align="right"><strong>Sales With Vat :&nbsp;</strong></div></td>
                    <td height="20" class="borderBR">
                      <div align="right"><strong><?php echo number_format($saleswithvat, 2); ?></strong>&nbsp;</div>
                    </td>
                  </tr>
                  <tr>
                    <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>YTD NCFT : :</strong>&nbsp;</div></td>
                    <td height="20" class="borderBR">
                      <div align="right" class="padr5">
                        <strong><?php echo $statid==6?number_format(($YTDNCFT+$totalNCFT), 2):number_format($qYTDNCFT, 2); ?></strong>
                      </div>
                    </td>           
                    <input type="hidden" name="hYTDNCFT" id="hYTDNCFT" value="<?php echo $YTDNCFT+$totalNCFT ; ?> " />
                    <td height="20" align="right" class="borderBR txtbold">Vat Amount :&nbsp;</td>
                    <td height="20" align="right" class="borderBR">
                      <?php 
                      
                      $vatamt=($saleswithvat/1.12);                 
                      $amountwovat=$saleswithvat-$vatamt;
                      $amountToNextDiscCFT=0;
                      $amountToNextDiscNCFT=0;
                      
                      $rsGetDiscounts=$sp->spSelectDiscountBracket($database);	
                      
                      if($rsGetDiscounts->num_rows) {                      
                      
                        while($DiscountBrackets=$rsGetDiscounts->fetch_object()) {
                        
                          $minimum=$DiscountBrackets->Minimum;
                          $maximum=$DiscountBrackets->Maximum;
                          $PMGID=$DiscountBrackets->PMGID;
                          
                          if($DiscountBrackets->PMGID==1) {
                          
                            $tmptotalCFT=$MTDCFT;                            
                            if($statid==6) $tmptotalCFT=$totalCFT+$MTDCFT;
                                        
                            if($tmptotalCFT>=$minimum && $tmptotalCFT<=$maximum && $PMGID==1) {
                            
                              $discID=$DiscountBrackets->Discount;	
                              
                              $rsNextDiscount=$sp->spSelectNextDiscBracket($database, $discID, 1);
                              
                              if($rsNextDiscount->num_rows) {
                              
                                while($nextDisc=$rsNextDiscount->fetch_object()) {
                                
                                  $tmpNextMinimum=$nextDisc->Minimum;
                                  $amountToNextDiscCFT=$tmpNextMinimum-$tmptotalCFT;                                                            
                                }
                              }                            
                            }                          
                          } else {
                          
                            $tmptotalNCFT=$MTDNCFT;                            
                            if($statid==6) $tmptotalNCFT=$totalNCFT+$MTDNCFT;	
                            
                            if($tmptotalNCFT>=$minimum && $tmptotalNCFT<=$maximum &&  $PMGID==2) {
                            
                              $discID=$DiscountBrackets->Discount;						
                              $rsNextDiscount=$sp->spSelectNextDiscBracket($database, $discID, 2);
                              
                              if($rsNextDiscount->num_rows) {
                              
                                while($nextDisc=$rsNextDiscount->fetch_object()) {
                                
                                  $tmpNextMinimum=$nextDisc->Minimum;
                                  $amountToNextDiscNCFT=$tmpNextMinimum-$tmptotalNCFT;                                                            
                                }
                              }                            
                            }
                          }				
                        }
                      }                                            
                      ?>
                      <strong><?php echo number_format($amountwovat, 2); ?></strong>&nbsp;
                    </td>
                  </tr>
                  <tr align="center">
                    <td height="20" align="right" class="borderBR txt10">
                      <div align="right"><strong>Amount to next discount level - CFT :</strong>&nbsp;</div>
                    </td>
                    <td height="20" class="borderBR">
                      <div align="right" class="padr5">
                        <strong><?php echo $customerType==99?'0.00':number_format($amountToNextDiscCFT, 2); ?></strong> 
                      </div>
                    </td>           
                    <input type="hidden" name="hamountToNextDiscCFT" id="hamountToNextDiscCFT" value="<?php echo $amountToNextDiscCFT; ?>" /> 
                    <td height="20" class="borderBR"><div align="right"><strong>Vatable Sales :&nbsp;</strong></div></td>
                    <td height="20" class="borderBR">
                      <div align="right"><strong><?php echo number_format($vatamt, 2); ?></strong>&nbsp;</div>
                    </td>
                  </tr>
                  <tr align="center">
                    <td height="20" align="right" class="borderBR txt10">
                      <div align="right"><strong>Amount to next discount level - NCFT :</strong>&nbsp;</div>
                    </td>
                    <td height="20" class="borderBR">
                      <div align="right" class="padr5">
                        <strong><?php echo $customerType==99?'0.00':number_format($amountToNextDiscNCFT, 2); ?></strong> 
                      </div>
                    </td>
                    <input type="hidden" name="hamountToNextDiscNCFT" id="hamountToNextDiscNCFT" value="<?php echo $amountToNextDiscNCFT; ?>" /> 
                    <td height="20" class="borderBR">
                      <div align="right"><strong>Additional Discount on Previous Purchase :&nbsp;</strong></div>
                    </td>
                    <td height="20" class="borderBR">
                      <div align="right"><strong><?php echo number_format($prevadditionaldiscount, 2); ?></strong>&nbsp;</div>
                    </td>
                  </tr>
                  <tr>
                    <td height="20" align="right" class="borderBR txt10"><div align="right"><strong></strong>&nbsp;</div></td>
                    <td height="20" class="borderBR"><div align="right" class="padr5"></div></td>
                    <td height="20" align="right" class="borderBR txtbold"><strong>Total Invoice Amount Due :&nbsp;</strong></td>
                    <td height="20" class="borderBR" align="right"><strong><?php echo number_format($totalnetamt, 2); ?></strong>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" align="right" class="borderBR txt10"><div align="right"><strong></strong>&nbsp;</div></td>
                    <td height="20" class="borderBR"><div align="right" class="padr5"></div></td>
                    <td height="20" align="right" class="borderBR txtbold">&nbsp;</td>
                    <td height="20" class="borderBR" align="right">&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          
          <!--- end right div  -->
          <br />
          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td height="20">
                <div align="center">
                  <?php if($statid==6): ?><input name="btnConfirm" type="submit" class="btn" value="Confirm and Print" onclick="return confirmSave(<?php echo $_GET['tid']; ?>,1);" /><?php endif ?>
                                                          
                  <input name="btnCancel" type="submit" class="btn" value="Back to List" />
                  
                  <?php if($statid==7): ?><input name="btnPrint" type="submit" class="btn" value="Print" onclick="javascript: return validatePrint1(<?php echo $_GET['tid']; ?>, 1, 1);" /><?php endif ?>
                  
                  <?php if($dateDiff==0 && $statid!=8): ?><input name="btnCancelSI" type="button" id="cancelSIButton" class="btn" value="Cancel SI" /><?php endif; ?>
                  
                  <input name="btnCopy" type="submit" class="btn" value="Copy to SO" />
                </div>
              </td>
            </tr>
          </table>
          <br />
        </td>
      </tr>
    </table>
  </form>
  
  <div id="cancelSI" title="Cancel Sales Invoice">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
      <tr>
        <td height="20" align="right" class="txt10">Reason Code&nbsp; :</td>
        <td height="20">
          &nbsp;&nbsp;
          <select name="pProdCls" id="lstReasonCode" class="txtfield" style="width:200px">
            <option value="">[SELECT HERE]</option>
            <?php if($rs_reason->num_rows): ?>            
            <?php while($row=$rs_reason->fetch_object()): ?>                         
            <option value="<?php echo $row->ID; ?>"<?php echo $pProdClsID==$row->ID?' selected="selected"':''; ?>><?php echo $row->Name; ?></option>                               
            <?php endwhile; ?>
            <?php endif; ?>
          </select>
        </td>
      </tr>
      <tr>
        <td height="20" align="right" class="txt10" valign="top">Remarks&nbsp; :</td>
        <td height="20">&nbsp;&nbsp;<textarea name="txtCancelRemarks" id="txtCancelRemarks" style="width:200px" cols="30" rows="6" class="txtfieldnh"></textarea></td>
      </tr>              
    </table>
  </div>
</body>