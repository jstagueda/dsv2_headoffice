<?php
/*   
  @modified by John Paul Pineda
  @date June 1, 2013
  @email paulpineda19@yahoo.com         
*/
 
ini_set('display_errors', '1');
require_once(IN_PATH.DS.'scCreateSalesOrder.php'); 
?>

<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="js/jsUtils.js"></script>
<script type="text/javascript" src="js/jxCreateSalesOrder.js"></script>
<script type="text/javascript" src="js/scriptaculous-js-1.9.0/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous-js-1.9.0/scriptaculous.js"></script>

<style>
.style1 {

  color: #FF0000;
  font-weight: bold;
}

div.autocomplete {

  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px; }
 
div.autocomplete ul {

  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}

div.autocomplete ul li.selected { background-color: #ffb; }

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

.padl5 { padding-left:1px; }

.sales-order-heading { font-weight:bold; }

.txtfieldLabel { height:auto; }  
</style>

<script type="text/javascript">
$tpi(document).ready(function() {
  
  $tpi("#tpi_dss_applicable_promos_overlay, #tpi_dss_applicable_incentives_overlay").height($tpi(document).height());  
  
  chkAdvPO();
  
  $tpi("#prodlist").on('mouseenter', "tr", function() { $tpi(this).css({'background':'pink', 'cursor':'pointer'}); }).on('mouseleave', "tr", function() { $tpi(this).css({'background':'#FFF7FE', 'cursor':'default'}); });    
  
  $tpi("#tpi_dss_applicable_promos").on('click', ".close-applicable-promos-window", function() {    
    
    $tpi('#tpi_dss_applicable_promos_overlay').fadeOut('slow');
    // $tpi('html, body').animate({ scrollTop: $tpi('#view_applicable_promos').position().top }, 0);
  }).on('mouseenter', ".promo-entitlement-row", function() { $tpi(this).css({'background':'pink', 'cursor':'pointer'}); }).on('mouseleave', ".promo-entitlement-row", function() { $tpi(this).css({'background':'#FFF7FE', 'cursor':'default'}); });
  
  $tpi("#tpi_dss_applicable_incentives_overlay").on('click', ".close-applicable-incentives-window", function() {    
    
    $tpi('#tpi_dss_applicable_incentives_overlay').fadeOut('slow');
    // $tpi('html, body').animate({ scrollTop: $tpi('#view_applicable_incentives').position().top }, 0);
  });
  
  $tpi("#tpi_dss_applicable_incentives").on('mouseenter', ".incentive-entitlement-row", function() { $tpi(this).css({'background':'pink', 'cursor':'pointer'}); }).on('mouseleave', ".incentive-entitlement-row", function() { $tpi(this).css({'background':'#FFF7FE', 'cursor':'default'}); }).on('click', ".applicable-incentive", function() {
  
    $tpi("#applicable_incentive_entitlement_details"+(($tpi(this).attr('id')).substr(20, ($tpi(this).attr('id')).length))).fadeToggle('slow', 'linear'); 
  });
  
  $tpi("#tpi_dss_applied_promos").on('click', ".applied-promo", function() {
    
    $tpi(this).toggleClass('clicked-promo-code');
    $tpi("."+$tpi(this).attr('id')).toggle('slow');
  }).on('mouseenter', ".applied-promo", function() { $tpi(this).css('background', 'pink'); }).on('mouseleave', ".applied-promo", function() { $tpi(this).css('background', 'white'); });
  
  $tpi("#tpi_dss_applied_incentives").on('click', ".applied-incentive", function() {
    
    $tpi(this).toggleClass('clicked-incentive-code');
    $tpi("."+$tpi(this).attr('id')).toggle('slow');
  }).on('mouseenter', ".applied-incentive", function() { $tpi(this).css('background', 'pink'); }).on('mouseleave', ".applied-incentive", function() { $tpi(this).css('background', 'white'); });        
});
</script>

<form id="frmCreateSalesOrder" name="frmCreateSalesOrder" action="index.php?pageid=34&custID=<?php echo $_GET['custID']; ?>&adv=<?php echo $_GET['adv']; ?>" method="post">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" >
    <tr>
    	<td valign="top">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="topnav">
              <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br />
        <input type="hidden" name="hCustomerID" id="hCustomerID" value="<?php echo $custID; ?>" />
        <input type="hidden" name="hVatPercent" id="hVatPercent" value="<?php echo $vatpercent; ?>" />
        <input type="hidden" name="hisAdvance" id="hisAdvance" value="<?php echo $_GET['adv']; ?>" />
        <input type="hidden" name="hApprvAdvPO"	id="hApprvAdvPO" value="<?php echo $ifAdvPO; ?>" />
        <input type="hidden" name="hBackOrder" id="hBackOrder" value="<?php echo $_GET['pageid']=='35.2'?'1':'0'; ?>" />
        <input type="hidden" name="hTxnID"	id="hTxnID" value="<?php echo $_GET['pageid']=='35.2'?$_GET['TxnID']:'0'; ?>" />
    		
        <?php /* ?><h1 align="center"><font color="red">Programmer's Note: This page is currently being updated.</font></h1><?php */ ?>
        
        <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td class="txtgreenbold13">Create<?php echo $_GET['adv']?' Advance':''; ?> Sales Order</td>
            <td>&nbsp;</td>
          </tr>
        </table>
    		<br />
    		<?php if($errmsg!=''): ?>
    		<br />
    		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
      		<tr>
        		<td>
          		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
            		<tr>
            			<td width="70%" class="txtreds">&nbsp;<b><?php echo $errmsg; ?></b></td>
            		</tr>
          		</table>
        		</td>
      		</tr>
    		</table>
    		<?php endif; ?>
    		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
      		<tr>
      			<td><span class="txtblueboldlink"><?php echo $_GET['msg'] ; ?></span></td>
      		</tr>
    		</table>
        <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>      
            <td  valign="top">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="tabmin">&nbsp;</td>
                  <td class="tabmin2">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr>
                        <td class="txtredbold">General Information</td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </td>
                  <td class="tabmin3">&nbsp;</td>
                </tr>
              </table>
              <?php if(isset($_GET['TxnID'])) { ?>
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
                <tr>
                  <td valign="top" class="bgF9F8F7"><div class="scroll_350">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr>
                        <td width="50%" valign="top">
                          <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                            <tr>
                              <td colspan="3" height="15">&nbsp;</td>
                            </tr>
                            <tr>
                              <td width="35%" height="20" align="right" class="txt10">Customer Code :</td>
                              <td width="5%" height="20" class="txt10">&nbsp;</td>
                              <td width="60%" height="20">
                                <input type="text" id="txtCustomer" name="txtCustomer" readonly="yes" value="<?php echo $custcode; ?>" class="txtfieldLabel" />	
                                <input type="hidden" name="hcustID" value="<?php echo $custID; ?>" />
                                <input type="hidden" name="hCOA" value="<?php echo $custID; ?>" />
                                <input type="hidden" id="hGSUType" name="hGSUType" value="<?php echo $gsutypeID; ?>" />	
                                <input type="hidden" id="hCustomerStatus" name="hCustomerStatus" value="<?php echo $customerStatus; ?>" />
                              </td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Customer Name :</td>
                              <td height="20" class="txt10">&nbsp;</td>
                              <td height="20">
                                <input type="text" id="txtRefNo" name="txtCustomerName" size="25" readonly="yes" value="<?php echo $custname; ?>" class="txtfieldLabel" />
                                <input type="hidden" name="GSUTypeID" value="<?php echo $gsutypeID; ?>" class="txtfieldLabel" />
                              </td>
                            </tr>
                            <tr>
                              <td width="25%" height="20" align="right" class="txt10">IBM No / IBM Name : </td>
                              <td width="5%" height="20" class="txt10"></td>
                              <td width="70%" height="20">
                                <div align="left" class="padr5" id="IBM">
                                  <input type="hidden" name="isEmployee" value="<?php echo $isEmployee; ?>" />
                                  <?php echo $ibm; ?>
                                </div>								                            
                              </td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Type : </td>
                              <td height="20" class="txt10">&nbsp;</td>							
                              <td height="20">
                                <input type="text" id="txtDealerType" name="txtDealerType" readonly="yes" value="<?php echo $gsuType; ?>" class="txtfieldLabel" />
                              </td>
                            </tr>                  
                            <tr>
                              <td height="20" align="right" class="txt10">Credit Limit : </td>
                              <td height="20" class="txt10">&nbsp;</td>							
                              <td height="20">
                                <input type="text" id="txtCLimit" name="txtCLimit" readonly="yes" value="<?php echo number_format($creditLimit, 2); ?>" class="txtfieldLabel" />
                              </td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Available Credit : </td>
                              <td height="20" class="txt10">&nbsp;</td>							
                              <td height="20">
                                <input type="text" id="txtAvailableCredit" name="txtAvailableCredit" readonly="yes" value="<?php echo number_format($availableCredit, 2); ?>" class="txtfieldLabel" />
                              </td>
                            </tr>                  
                            <tr>
                              <td height="20" align="right" class="txt10">Unpaid Invoices : </td>
                              <td height="20" class="txt10">&nbsp;</td>							
                              <td height="20">
                                <input type="text" id="txtARBalance" name="txtARBalance" readonly="yes" value="<?php echo number_format($unpaidInvoice, 2); ?>"  class="txtfieldLabel" />
                              </td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Unpaid Penalty : </td>
                              <td height="20" class="txt10">&nbsp;</td>							
                              <td height="20">
                                <input type="text" id="txtPenalty" name="txtPenalty" readonly="yes" value="<?php echo number_format($outstandingPenalty, 2); ?>" class="txtfieldLabel" />
                              </td>
                            </tr>                
                          </table>
                        </td>
                        <td valign="top" width="50%">
                          <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                            <tr>
                              <td colspan="3" height="15">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Sales Order No.  : </td>
                              <td height="20" class="txt10">&nbsp;</td>
                              <td height="20">
                                <input type="text" id="txtSONo" name="txtSONo" size="25" maxlength="25" readonly="yes" value="<?php echo $sono; ?>" class="txtfieldLabel" />
                              </td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Sales Order Date  : </td>
                              <td height="20" class="txt10">&nbsp;</td>
                              <td height="20">
                                <input type="text" id="txtSODate" name="txtSODate" size="12" readonly="yes" value="<?php echo $txndate; ?>" class="txtfieldLabel" />
                              </td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Branch Name : </td>
                              <td height="20" class="txt10">&nbsp;</td>							
                              <td height="20">
                                <input type="text" id="txtBranch" name="txtBranch" readonly="yes" value="<?php echo $branch ; ?>" class="txtfieldLabel" />
                              </td>
                            </tr>						                                    
                            <tr>
                              <td height="20" align="right" class="txt10">Created By : </td>
                              <td height="20" class="txt10">&nbsp;</td>							
                              <td height="20"><?php echo $employee; ?></td>
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
                              <td height="20" align="right" class="txt10">Date Confirmed </td>
                              <td height="20">:</td>
                              <td height="20"><?php echo $txndate; ?></td>
                            </tr>                                  
                            <tr>
                              <td height="20" align="right" class="txt10">Document No.  :</td>
                              <td height="20" class="txt10">&nbsp;</td>
                              <td height="20">
                                <input type="text" id="txtRefNo" name="txtRefNo" size="25" maxlength="15"  readonly="yes" value="<?php echo $docno; ?>" class="txtfieldLabel" />
                              </td>
                            </tr>				                                    
                            <tr>
                              <td height="20" align="right" valign="top" class="txt10">Remarks : </td>
                              <td height="20" valign="top" class="txt10">&nbsp;</td>
                              <td height="20">
                                <textarea id="txtRemarks" name="txtRemarks" cols="42" rows="3" wrap="hard" readonly="yes" class="txtfieldnh"><?php echo $remarks; ?></textarea>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">&nbsp;</td>
                      </tr>
                    </table>          
                  </td>
                </tr>
              </table>
              <?php } else { ?>
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
                <tr>
                  <td valign="top" class="bgF9F8F7">
                    <div class="scroll_350">
                      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                        <tr>
                          <td width="50%" valign="top">
                            <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                              <tr>
                                <td colspan="3" height="15">&nbsp;</td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" width="35%" height="20" class="txt10">Customer Code: </td>
                                <td width="5%" height="20" class="txt10">&nbsp;</td>
                                <td valign="top" width="60%" height="20">
                                  <input type="text" id="txtCustomer" name="txtCustomer" onkeypress="return disableEnterKey(this, event, 0);" class="txtfield" />
                                  <span id="indicator1" style="display: none">
                                    <img src="images/ajax-loader.gif" alt="Working..." />
                                  </span>                                      
                                  <div id="coa_choices" class="autocomplete" style="display:none"></div>
                                  <script type="text/javascript">							
                                  //<![CDATA[
                                  var coa_choices = new Ajax.Autocompleter('txtCustomer', 'coa_choices', 'includes/scCustomerListAjax.php', {afterUpdateElement : getSelectionCOAID, indicator: 'indicator1'});																			
                                  //]]>
                                  </script>
                                  <input name="hCOA" type="hidden" id="hCOA" />	
                                  <input name="hGSUType" type="hidden" id="hGSUType" />	
                                  <input type="hidden" name="hCustomerStatus" id="hCustomerStatus" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Customer Name  :</td>
                                <td height="20" class="txt10">&nbsp;</td>
                                <td valign="top" height="20">
                                  <input type="text" id="txtRefNo" name="txtCustomerName" size="35" maxlength="15" readonly="yes" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" width="25%" height="20" class="txt10">IBM No / IBM Name : </td>
                                <td width="5%" height="20" class="txt10">&nbsp;</td>
                                <td valign="top" width="70%" height="20">
                                  <div align="left" class="padr5" id="IBM"></div>
                                  <input type="hidden" name="isEmployee" />
                                  <input type="hidden" name="GSUTypeID" value="0" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Type : </td>
                                <td height="20" class="txt10">&nbsp;</td>							
                                <td valign="top" height="20">
                                  <input type="text" id="txtDealerType" name="txtDealerType" readonly="yes" class="txtfieldLabel" />
                                </td>
                              </tr>																							  
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Credit Limit  : </td>
                                <td height="20" class="txt10">&nbsp;</td>							
                                <td valign="top" height="20">
                                  <input type="text" id="txtCLimit" name="txtCLimit" readonly="yes" class="txtfieldLabel" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">AR Balance  : </td>
                                <td height="20" class="txt10">&nbsp;</td>							
                                <td valign="top" height="20">
                                  <input type="text" id="txtARBalance" name="txtARBalance" readonly="yes" class="txtfieldLabel" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Unpaid Penalties  : </td>
                                <td height="20" class="txt10">&nbsp;</td>							
                                <td valign="top" height="20">
                                  <input type="text" id="txtPenalty" name="txtPenalty" readonly="yes" class="txtfieldLabel" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Available Credit  : </td>
                                <td height="20" class="txt10">&nbsp;</td>							
                                <td valign="top" height="20">
                                  <input type="hidden" id="txtAvailableCredit" name="txtAvailableCredit" readonly="yes" class="txtfieldLabel" />
                                  <span id="dealer_available_credit"></span>
                                </td>
                              </tr>																								                        
                            </table>
                          </td>
                          <td valign="top" width="50%">
                            <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                              <tr>
                                <td colspan="3" height="15">&nbsp;</td>
                              </tr>                            
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Sales Order No.  : </td>
                                <td height="20" class="txt10">&nbsp;</td>
                                <td valign="top" height="20">
                                  <input type="text" id="txtSONo" name="txtSONo" size="25" maxlength="25" readonly="yes" value="<?php echo $SODOCNo; ?>" class="txtfieldLabel" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Sales Order Date  : </td>
                                <td height="20" class="txt10">&nbsp;</td>
                                <td valign="top" height="20">
                                  <input type="text" id="txtSODate" name="txtSODate" size="12" readonly="yes" maxlength="12" value="<?php echo $sodate ; ?>" class="txtfieldLabel" />&nbsp;
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Branch Name : </td>
                                <td height="20" class="txt10">&nbsp;</td>							
                                <td valign="top" height="20">
                                  <input type="text" id="txtBranch" name="txtBranch" readonly="yes" value="<?php echo $branch ; ?>" class="txtfieldLabel" />
                                </td>
                              </tr>						                                                        
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Created By : </td>
                                <td height="20" class="txt10">&nbsp;</td>							
                                <td valign="top" height="20"><?php echo $employee; ?></td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Document No.  :</td>
                                <td height="20" class="txt10">&nbsp;</td>
                                <td valign="top" height="20">
                                  <input type="text" id="txtRefNo" name="txtRefNo" size="25" maxlength="15" onkeypress="return disableEnterKey(this, event, 0);" class="txtfield" />
                                </td>
                              </tr>
                              <tr>
                                <td align="right" valign="top" height="20" class="txt10">Remarks : </td>
                                <td height="20" valign="top" class="txt10">&nbsp;</td>
                                <td valign="top" height="20">
                                  <textarea id="txtRemarks" name="txtRemarks" cols="42" rows="3" wrap="hard" class="txtfieldnh"></textarea>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="3">&nbsp;</td>
                        </tr>
                      </table>
                    </div>
                  </td>
                </tr>
              </table>
              <?php } ?>
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="3" class="bgE6E8D9"></td>
                </tr>
              </table>
              <br />
            </td>
          </tr>
          <tr>
            <td>
              <center id="view_applicable_promos">Applicable promos found. Please click <a href="javascript: view_applicable_promos();" class="tpi-blinking-link">here</a> to choose and apply.</center><br />
              <center id="view_applicable_incentives">Applicable incentives found. Please click <a href="javascript: view_applicable_incentives();" class="tpi-blinking-link">here</a> to choose and apply.</center>
            </td>
          </tr>
        </table>
        <a name="AnchorHere"></a>
        <div id="tbl22" style="display:block"> 
          <?php if(isset($_GET['TxnID'])): ?>
          <table width="98%" border="0" align="center" cellpadding="0" cellspacing="3">
            <tr>   	                
              <td valign="top">&nbsp;
                <!---  right table -->
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="tabmin">&nbsp;</td>
                    <td class="tabmin2">
                      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                        <tr>
                          <td class="txtredbold">Order Details </td>
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
                </table>
                <!---  right div-->
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">	                  
                  <tr>
                    <td class="bgF9F8F7">
                      <div class="scroll_350">	
                        <!--Served Table-->
                        <table id="dynamicTable" width="100%" cellpadding="0" cellspacing="1" class="bgFFFFFF" border="0">
                          <tr class="tab txtdarkgreenbold10 ">          
                            <td align="center" width="4%" class="borderBR padl5">Line #</td>
                            <td align="center" width="8%" class="borderBR padl5">Item Code</td>
                            <td align="center" width="24%" class="borderBR padl5">Item Name</</td>
                            <td align="center" width="5%" class="borderBR padl5">UOM</td>
                            <td align="center" width="8%" class="borderBR padl5">PMG</td>			
                            <td align="center" width="5%" class="borderBR padl5">Promo</td>
                            <td align="center" width="5%" class="borderBR padl5">SOH</td>				
                            <td align="center" width="8%" class="borderBR padl5">Intransit Qty</td>
                            <td align="center" width="8%" class="borderBR padl5">Ordered Qty</td>
                            <td align="center" width="8%" class="borderBR padl5">CSP </td>	
                            <td align="center" width="10%" class="borderBR padl5">Total Price</td>				
                          </tr>
                          <?php if($rsServedDetails->num_rows): $cnt=1; ?>
                          <?php while($row=$rsServedDetails->fetch_object()): ?>            
                          <tr height="20">									
                            <td align="center" width="4%" class="borderBR padl5"><?php echo $cnt; ?></td>
                            <td align="center" width="8%" height="20" class="borderBR padl5"><?php echo $row->prodCode; ?></td>
                            <td align="center" width="17%" class="borderBR padl5"><?php echo $row->prodName; ?></td>
                            <td align="center" width="5%" class="borderBR padl5">Piece</td>
                            <td align="center" width="5%" class="borderBR padl5"><?php echo $row->pmgCode; ?></td>									
                            <td align="center" width="10%" class="borderBR padl5"><?php echo $row->promo; ?></td>		
                            <td align="center" width="5%" class="borderBR padl5"><?php echo $row->SOH; ?></td>					
                            <td align="center" width="10%" class="borderBR padl5"><?php echo $row->intransit; ?></td>
                            <td align="center" width="8%" class="borderBR padl5"><?php echo $row->qty; ?></td>
                            <td align="center" width="10%" class="borderBR padl5"><?php echo number_format($row->unitprice, 2, '.', ''); ?></td>					
                            <td width="10%" align="center" class="borderBR padl5"><?php echo number_format($row->unitprice, 2, '.', ''); ?></td>					
                          </tr>
                          <?php $cnt++; endwhile; ?>            
                          <?php endif; ?>
                        </table>			
                      </div>	
                    </td>
                  </tr>	
                  <tr class="bgF9F8F7">
                    <td height="15">&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <?php endif; ?><br />        				
      		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">			
            <?php
            $sales_order_items_heading='
      			<tr class="tab txtdarkgreenbold10">
      				<td align="center" width="4%" class="borderBR padl5">Line #</td>
      				<td align="center" width="8%" class="borderBR padl5">Item Code</td>
      				<td align="center" width="27%" class="borderBR padl5">Item Name</td>
      				<td align="center" width="5%" class="borderBR padl5">UOM</td>
      				<td align="center" width="5%" class="borderBR padl5">PMG</td>					
      				<td align="center" width="8%" class="borderBR padl5">Regular Price</td>
      				<td align="center" width="5%" class="borderBR padl5">Promo</td>
      				<td align="center" width="5%" class="borderBR padl5">SOH</td>				
      				<td align="center" width="8%" class="borderBR padl5">Intransit Qty</td>
      				<td align="center" width="8%" class="borderBR padl5">Ordered Qty</td>
      				<td align="center" width="8%" class="borderBR padl5">CSP</td>	
      				<td align="center" width="10%" class="borderBR padl5">Total Price</td>				
      			</tr>';
            ?>
      				
      			<?php if(isset($_GET['TxnID'])) { ?>                      
      			<tr>
      			 <td class="bgF9F8F7">
      			   <div class="scroll_350" id="prodlist">				
        				<!--Dynamic Table-->
        				<table id="dynamicTable" border="0" cellpadding="0" cellspacing="1" width="100%" class="bgFFFFFF">
            			<?php
                  echo $sales_order_items_heading;
                    
            			if($rsOpenDetails->num_rows) {
            					
            			  $i=1;
          					$cnt=$rsOpenDetails->num_rows+1;
                    
          					echo '<input type="hidden" id="hcnt" name="hcnt" value="'.$cnt.'" />';
                    
          				  while($row=$rsOpenDetails->fetch_object()) {
                     		        				   			   		
        				   		for($k=1;$k<=$row->qty;$k++) {
                      
          							$productID=$row->prodID;						
          							$productcode=$row->prodCode;
          							$productdescription=$row->prodName;
          							$pmg=$row->pmgCode;	
          							$pmgid=$row->pmgid;					
          							$unitprice=number_format($row->unitprice, 2, '.', '');
          							$orderedQTY=1;
          							$effectiveprice=number_format($row->unitprice, 2, '.', '');
          							$tmptotalprice=0;
          							$totalprice=number_format($tmptotalprice, 2, '.', '');
          							$promoCode=$row->promo;
          							$producttype=$row->prodType;
          							$promoID=$row->promoID;
          							$promoType=$row->promotype;
                        
          				      echo '<tr height="20">				
          								      <td align="center" width="4%" class="borderBR padl5">'.$i.'</td>
          								      <td align="center" width="8%" height="20" class="borderBR padl5">
                                  <div align="center">
          									       <input name="txtProdCode'.$i.'" type="text" readonly="yes" class="txtfieldLabel" id="txtProdCode'.$i.'"  value="'.$productcode.'" style="width:52px;" />        									
          									       <input name="hProdID'.$i.'" type="hidden" id="hProdID'.$i.'" value="'.$productID.'" />
          									       <input name="hSubsID'.$i.'" type="hidden" id="hSubsID'.$i.'" value="" />
          									       <input name="hKitComponent'.$i.'" type="hidden" id="hKitComponent'.$i.'" value="" />
          								        </div>
                                </td>
          								      <td width="27%" align="center" class="borderBR">
                                  <input type="text" name="txtProdDesc'.$i.'" id="txtProdDesc'.$i.'"  readonly="yes" value="'.$productdescription.'" class="txtfieldLabel" style="text-align:center; width:100%;" />
                                </td>
          								      <td width="5%" align="center" class="borderBR padl5">Piece</td>
          								      <td width="5%" align="center" class="borderBR padl5">
                                  <input type="text" name="txtPMG'.$i.'" id="txtPMG'.$i.'" value="'.$pmg.'" class="txtfieldLabel" style="text-align:center;" />
                                  <input type="hidden" name="hPMGID'.$i.'" id="hPMGID'.$i.'"  value="'.$pmgid.'" /> 
                                  <input type="hidden" name="hProductType'.$i.'" id="hProductType'.$i.'"  value="'.$producttype.'" />
                                </td>					
          								      <td width="8%" align="center" class="borderBR padl5">
                                  <input type="text" name="txtUnitPrice'.$i.'" id="txtUnitPrice'.$i.'" readonly="yes" value="'.$unitprice.'" class="txtfieldLabel" style="text-align:center;" />
                                </td>
          								      <td width="10%" align="center" class="borderBR padl5">'.$promoCode.' <input type="hidden" name="hPromoID'.$i.'" id="hPromoID'.$i.'" value="'.$promoID.'" /><input type="hidden" name="hPromoType'.$i.'" id="hPromoType'.$i.'" value="'.$promoType.'" /><input type="hidden" name="hForIncentive'.$i.'" id="hForIncentive'.$i.'" value="0"/></td>		
          								      <td width="5%" align="center" class="borderBR padl5">
                                  <div id="divSOH'.$i.'" name="divSOH'.$i.'">'.$row->SOH.'</div>
                                  <input type="hidden" name="hSOH'.$i.'" id="hSOH'.$i.'" value="'.$row->SOH.'" >
                                </td>					
          								      <td width="10%" align="center" class="borderBR padl5">
                                  <div id="divTransit'.$i.'" name="divTransit'.$i.'">'.$row->intransit.'</div>
                                  <input type="hidden" name="hTransit'.$i.'" id="hTransit'.$i.'" value="'.$row->intransit.'"  />
                                </td>
          								      <td width="8%" align="center" class="borderBR padl5">
                                  <input type="text" name="txtOrderedQty'.$i.'" readonly="yes" id="txtOrderedQty'.$i.'" value="'.$orderedQTY.'" class="txtfieldLabel" style="text-align:center;" />
                                  <input type="hidden" name="hServed'.$i.'" />
                                </td>
          								      <td width="10%" align="center" class="borderBR padl5">
                                  <input type="text" name="txtEffectivePrice'.$i.'" id="txtEffectivePrice'.$i.'" readonly="yes" value="'.$effectiveprice.'" class="txtfieldLabel" style="text-align:center;" /> 
                                </td>					
          								      <td width="10%" align="center" class="borderBR padl5">
                                  <input type="text" name="txtTotalPrice'.$i.'" id="txtTotalPrice'.$i.'" readonly="yes" value="'.$totalprice.'" class="txtfieldLabel" style="text-align:center;" /> 
                                </td>					
          							      </tr>';
          							$i++;
        						  }	
        					  }        				  
                    echo'<input type="hidden" name="hcnt" id="hcnt" value="'.$i.'" />';
            			} 
                  ?>
        			   </table>			
      			   </div>	
      			 </td>
      			</tr>
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
                </table><br />
              </td>
            </tr>
            <?php endif; ?>	
      			<?php } else { ?>
            <tr>
              <td class="bgF9F8F7">
                <div class="scroll_350" id="prodlist">				
                  <!--Dynamic Table-->
                  <table width="100%"  cellpadding="1" cellspacing="1" class="bgFFFFFF" id="dynamicTable" border="0">
                    <?php echo str_replace(array('left', 'right', 'padl5', 'class=""', 'padr5'), array('center', 'center', 'borderBR sales-order-heading', 'class="borderBR sales-order-heading"', 'borderBR sales-order-heading'), $sales_order_items_heading); ?>
                    <tr height="20">
                      <input type="hidden" name="hcnt" id="hcnt" />
                      <input type="hidden" name="hHasIncentive" value="0" />
                      <td width="4%" align="center" class="borderBR padl5">1</td>
                      <td width="8%" height="20" class="borderBR padl5">
                        <div align="center">
                          <input name="txtProdCode1" type="text" class="txtfield" id="txtProdCode1"  onKeyPress="return disableEnterKey(this, event,1)" style="width: 75px"  />
                          <span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
                          <div id="prod_choices1" class="autocomplete" style="display:none"></div>
                          <script type="text/javascript">							
                          //<![CDATA[
                          var prod_choices = new Ajax.Autocompleter('txtProdCode1', 'prod_choices1', 'includes/scProductListAjaxSO.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator2'});																			
                          //]]>
                          </script>
                          <input name="hProdID1" type="hidden" id="hProdID1" value="" />
                          <input name="hSubsID1" type="hidden" id="hSubsID1" value="" >
                          <input name="hKitComponent1" type="hidden" id="hKitComponent1" value="" />
                        </div>
                      </td>
                      <td width="24%" align="center" class="borderBR padl5">
                        <input name="txtProdDesc1" style="width: 300px" type="text" class="txtfield"  id="txtProdDesc1"  readonly="yes" onkeypress="return disableEnterKey(this, event, 1);"/>
                      </td>
                      <td width="5%" align="center" class="borderBR padl5">Piece</td>
                      <td width="8%" align="center" class="borderBR padl5">
                        <input type="text" name="txtPMG1" id="txtPMG1" class="txtfieldItemLabel1" readonly="yes" style="text-align:center"/> 
                        <input type="hidden" name="hPMGID1" id="hPMGID1" readonly="yes" />
                        <input type="hidden" name="hProductType1" id="hProductType1" /> 
                      </td>					
                      <td width="8%" align="center" class="borderBR padl5">
                        <input type="text" name="txtUnitPrice1" class="txtfieldItemLabel1" id="txtUnitPrice1" readonly="yes" style="text-align:center;">
                      </td>
                      <td width="5%" align="center" class="borderBR padl5">
                        <div id="divPromo1" name="divPromo1"></div>
                        <input type="hidden" name="hPromoID1" id="hPromoID1" value="0" />
                        <input type="hidden" name="hPromoType1" id="hPromoType1" />
                        <input type="hidden" name="hForIncentive1" id="hForIncentive1" value="0" />&nbsp;
                      </td>		
                      <td width="5%" align="center" class="borderBR padl5">
                        <div id="divSOH1" name="divSOH1">&nbsp;</div>
                        <input type="hidden" name="hSOH1" id="hSOH1" />
                      </td>					
                      <td width="8%" align="center" class="borderBR padl5">
                        <div id="divTransit1" name="divTransit1">&nbsp;</div>
                        <input type="hidden" name="hTransit1" id="hTransit1" />
                      </td>
                      <td width="8%" align="center" class="borderBR padl5">
                        <input type="text" name="txtOrderedQty1" class="txtfield3" id="txtOrderedQty1" onkeyup="" onkeypress="return disableEnterKey(this, event, 1);" style="text-align:center;"/>
                        <input type="hidden" name="hServed1" />
                      </td>
                      <td width="8%" align="center" class="borderBR padl5">
                        <input type="text" name="txtEffectivePrice1" class="txtfieldItemLabel1" id="txtEffectivePrice1" readonly="yes" style="text-align:center;" /> 
                      </td>					
                      <td width="10%" align="center" class="borderBR padl5">
                        <input type="text" name="txtTotalPrice1" class="txtfieldItemLabel1" id="txtTotalPrice1" readonly="yes" style="text-align:center;" /> 
                      </td>					
                    </tr>
                  </table>			
                </div>                
                <div id="tpi_dss_applied_promos"></div>
                <div id="tpi_dss_applicable_promos_overlay">
                  <div id="tpi_dss_applicable_promos"></div>
                </div>
                
                <div id="tpi_dss_applied_incentives"></div>
                <div id="tpi_dss_applicable_incentives_overlay">
                  <div id="applicable_incentives_header">
                    <div><font size="3"><b>Applicable Incentives</b></font><sup class="close-applicable-incentives-window"><b>(Close)</b></sup></div>                    
                  </div>
                  <div id="tpi_dss_applicable_incentives"></div>
                </div>	
              </td>
            </tr>	
      		<?php } ?>
      		<tr class="bgF9F8F7">
      			<td height="15">&nbsp;</td>
      		</tr>    		
      		<tr class="bgF9F8F7">
      			<td height="15">&nbsp;</td>
      		</tr>
      		<tr>
      			<td class="bgF9F8F7 tab">
      				<table width="100%" border="0" cellpadding="0" cellspacing="1">
        				<tr>
        					<td colspan="4" height="20">&nbsp;</td>
        				</tr>
        				<tr>				  
        				  <td width="30%" height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Total Qty CFT :</strong>&nbsp;</div>
                  </td>
        				  <td width="30%" height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="totQtyCFT" id="totQtyCFT" readonly="yes" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right" value="0" />
                    </div>
                  </td>
        				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
        				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
        				</tr>				
        				<tr>				  
        				  <td width="30%" height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Total Qty NCFT :</strong>&nbsp;</div>
                  </td>
        				  <td width="30%" height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="totQtyNCFT" id="totQtyNCFT" readonly="yes" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" value="0" />
                    </div>
                  </td>
        				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
        				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
        				</tr>
        				<tr>				  
        				  <td width="30%" height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Total Qty CPI :</strong>&nbsp;</div>
                  </td>
        				  <td width="30%" height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="totQtyCPI" id="totQtyCPI" readonly="yes" value="0" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" />
                    </div>
                  </td>
        				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
        				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
        				</tr>
        				<tr>				  
        				  <td width="30%" height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Total Qty :</strong>&nbsp;</div>
                  </td>
        				  <td width="30%" height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="totQty" id="totQty" readonly="yes" value="0" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right" />
                    </div>
                  </td>
        				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
        				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
        				</tr>
        				<tr>				  
        				  <td width="30%" height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Total CPI :</strong>&nbsp;</div>
                  </td>
        				  <td width="30%" height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="totCPI" id="totCPI" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" />
                    </div>
                  </td>
        				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
        				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
        				</tr>
        				<tr>      				 
        				  <td width="30%" height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Total CFT :</strong>&nbsp;</div>
                  </td>
        				  <td width="30%" height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="totCFT" id="totCFT" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" /></div>
                  </td>
        				  <td width="25%" height="25" align="right" class="borderBR txt10">
                    <div align="right"><strong>Gross Amount :</strong>&nbsp;</div>
                  </td>
        				  <td width="15%" height="25" class="borderBR">
                    <div align="right" class="padr5">
                      <input name="txtGrossAmt" type="text"  size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" />
                    </div>
                  </td>
        				</tr>
        				<tr>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Total NCFT :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="totNCFT" id="totNCFT" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel"  style="text-align:right" />
                    </div>
                  </td>
        				  <td height="20" align="right" class="borderBR">
                    <div align="right"><strong>Total CSP Less (CPI <b id="boldStuff"></b>) :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input name="txtCSPLessCPI" id="txtCSPLessCPI" size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" type="text" class="txtfieldLabel" style="text-align:right;" />
                    </div>
                  </td>
        				</tr>
        				<tr>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>MTD CFT :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5" id="MTDCFT">0.00</div>
                  </td>
        				  <td height="20" align="right" class="borderBR">
                    <div align="right"><strong>Basic Discount :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="txtBasicDiscount" id="txtBasicDiscount" size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" />
                    </div>
                  </td>
        				</tr>
        				<tr>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>MTD NCFT :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5" id="MTDNCFT">0.00</div>
                  </td>
        				  <td height="20" align="right" class="borderBR">
                    <div align="right"><strong>Additional Discount :</strong>&nbsp;</div>
                  </td>
        					<td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="txtAddtlDisc" size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right" />
                    </div>
                  </td>
        				</tr>
        				<tr>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>YTD CFT :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5" id="YTDCFT">0.00</div>
                  </td>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Sales With Vat :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="txtSalesWVat" size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right" />
                    </div>
                  </td>
        				</tr>
        				<tr>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>YTD NCFT :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5" id="YTDNCFT">0.00</div>
                  </td>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Vat Amount :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="txtVatAmt" size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" />
                    </div>
                  </td>
        				</tr>
        				<tr>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Amount to next discount level - CFT :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR"><div align="right" class="padr5" id="nextdiscCFT">0.00</div></td>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Vatable Amount :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="txtAmtWOVat" size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right" />
                    </div>
                  </td>
        				</tr>
        				<tr>
        				  <td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Amount to next discount level - NCFT :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div id="nextdiscNCFT" align="right" class="padr5">0.00</div>
                  </td>
        				  <td height="20" align="right" class="borderBR">
                    <div align="right"><strong>Additional Discount on Previous Purchase :</strong>&nbsp;</div>
                  </td>
        					<td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" name="txtADPP" size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" />
                    </div>
                  </td>
        				</tr>
        				<tr>
        					<td height="20" align="right">&nbsp;</td>
        					<td height="20" align="right">&nbsp;</td>
        					<td height="20" align="right" class="borderBR txt10">
                    <div align="right"><strong>Total Invoice Amount Due :</strong>&nbsp;</div>
                  </td>
        				  <td height="20" class="borderBR">
                    <div align="right" class="padr5">
                      <input type="text" id="txtNetAmt" name="txtNetAmt" size="20" readonly="yes" value="0.00" onkeypress="return disableEnterKey(this, event, 0);" class="txtfieldLabel" style="text-align:right;" />
                    </div>
                  </td>
        				</tr>				
      				</table>
      			</td>
      		</tr>
      	 </table>
      	 <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="3" class="bgE6E8D9"></td>
        	</tr>
      	 </table>
        </div>
      	<!--- end right div  -->
    		<br />
    		<?php if($_GET['adv']==0 && $_GET['pageid']==34): ?>
    		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
      		<tr>
      			<td height="20">
              <div align="center">
        				<input type="submit" name="btnSave" value="Create SI" onclick="return validateSave();" class="btn" />&nbsp;
                <input type="submit" name="btnConfirmSO" value="Create SO" onclick="return validateSaveSO();" class="btn" />&nbsp;
                <input type="submit" name="btnDraft" value="Save as Draft" onclick="return validateSave();" class="btn" />&nbsp;
        		  	<input type="submit" name="btnCancel" value="Cancel" onclick="return ConfirmCancel();" class="btn" />                                     
      		  	</div>
            </td>
      		</tr>
    		</table>
          
    		<?php else: ?>
    		<?php if($_GET['pageid']=='35.2'): ?>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="20">
              <div align="center">				
                <input type="submit" name="btnCreatBO" value="Create SI" onclick="return validateSave();" class="btn" />&nbsp;
                <input type="submit" name="btnCloseBO" value="Close SO" class="btn" />&nbsp;
                <input type="submit" name="btnBackToList" value="Back to List" onclick="return ConfirmCancel();" class="btn" />
              </div>
            </td>
          </tr>
        </table>
          
    		<?php else: ?>    
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="20">
              <div align="center">				
                <input type="submit" name="btnSave" value="Confirm SO" onclick="return <?php echo false?'validateSaveSO':'validateSave'; ?>();" class="btn" />&nbsp;
                <input type="submit" name="btnDraft" value="Save as Draft" onclick="return validateSave();" class="btn" />&nbsp;
                <input type="submit" name="btnCancel" value="Cancel" onclick="return ConfirmCancel();" class="btn" />
              </div>
            </td>
          </tr>
        </table>
          
    		<?php endif; ?>
         
        <?php endif; ?>
    		<!--- end  right table -->
      </td>	
    </tr>
  </table>  	
</form>

<div id="substituteItem" title="Substitute Item">
	<input type="hidden" id="hProdIDSubstitute" name="hProdIDSubstitute" />
	<div id="substitutetable"></div>	
</div>

<div id="incentive" title="Available Incentives">
	<div id="incentivetable"></div>	
</div>
