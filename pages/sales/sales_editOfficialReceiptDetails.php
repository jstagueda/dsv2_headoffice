<?php
/*   
  @modified by John Paul Pineda.
  @date November 27, 2012.
  @email paulpineda19@yahoo.com         
*/

include(IN_PATH.DS.'scEditOfficialReceiptDetails.php'); 
?>

<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<style>
.style1 {color:#FF0000; }

div.autocomplete {

  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;} 
div.autocomplete ul {

  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {

  st-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px sod #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}
</style>

<style>
<!--
.style1 {color: #FF0000}
}
-->
</style>

<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script type="text/javascript" src="js/jsUtils.js"></script>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="js/jxEditOfficialReceiptDetails.js"></script>
<script type="text/javascript" src="js/jxPagingEditOfficialReceiptDetails.js"></script>
<script type="text/javascript" src="js/shortcut.js"></script>

<script type="text/javascript">
var objWin;
	
function NewWindow(mypage, myname, w, h, scroll) {

	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}  	
</script>

<style>
<!--
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}
-->
</style>

<form action="index.php?pageid=110.1&TxnID=<?php echo $_GET["TxnID"]; ?>" name="frmEditOfficialReceipt" method="post">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="topnav">
              <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td width="70%" align="right"><a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br />
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td class="txtgreenbold13">Apply Payment</td>    		
          </tr>
        </table>
        <?php if(isset($_GET['msg'])): ?>
        <br />
        <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td><span class="txtblueboldlink"><?php echo $_GET["msg"]; ?></span></td>
          </tr>
        </table>
        <?php endif; ?>
        <br />
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tabmin"></td>
            <td class="tabmin2">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td class="txtredbold">General Information </td>		              		
                </tr>
              </table>
            </td>
            <td class="tabmin3"></td>
          </tr>
        </table>      
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
          <tr>
            <td valign="top" class="bgF9F8F7">
              <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td width="50%" valign="top">  
                    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                      <tr>
                        <td height="20" class="txt10">OR No.</td>
                        <td><input name="txtTxnNo" type="text" class="txtfieldLabel" value="<?php echo $orno; ?>" size="40" readonly="yes"></td>
                      </tr><input name="hdnTxnID" id="hdnTxnID" type="hidden" value="<?php echo $id; ?>">
                      <tr>
                        <td height="20" class="txt10">Document No. </td>
                        <td><input name="txtDocNo" type="text"  class="txtfieldLabel" id="txtDocNo" value="<?php echo $docno; ?>" size="40" readonly="yes" /></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">Dealer Code</td>
                        <td><input name="txtDealerCode" type="text" class="txtfieldLabel" value="<?php echo $custcode; ?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">Dealer Name</td>
                        <td><input name="txtDealerName" type="text" class="txtfieldLabel" value="<?php echo $custname; ?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">IBM No / IBM Name</td>
                        <td height="20"><input name="txtIBM" type="text" class="txtfieldLabel" value="<?php echo $ibmname; ?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">Total Applied Amount </td>
                        <td><input name="txtAppAmount" type="text" class="txtfieldLabel" value="<?php echo number_format($totalappamt, 2); ?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">Total Unapplied Amount </td>
                        <td><input name="txtTotalUnapplied" type="text" class="txtfieldLabel" value="<?php echo $totalunappamt; ?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">Total Amount </td>
                        <td><input name="txtCustName" type="text" class="txtfieldLabel" value="<?php echo $totalamt; ?>" size="40" readonly="yes"></td>
                      </tr>
                    </table>
                  </td>
                  <td valign="top">
                    <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                      <tr>
                        <td height="20" class="txt10">OR Date </td>
                        <td><input name="txtORDate" type="text" class="txtfieldLabel" value="<?php echo $txndate; ?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">OR Type </td>
                        <td><input name="txtORType" type="text" class="txtfieldLabel" value="<?php echo $orType;?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">Status </td>
                        <td><input name="txtORType" type="text" class="txtfieldLabel" value="<?php echo $status;?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">Branch </td>
                        <td><input name="txtORType" type="text" class="txtfieldLabel" value="<?php echo $branch;?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr>
                        <td height="20" class="txt10">Created By </td>
                        <td><input name="txtORType" type="text" class="txtfieldLabel" value="<?php echo $employee;?>" size="40" readonly="yes"></td>
                      </tr>
                      <tr valign="top">
                        <td height="20" class="txt10">Remarks</td>
                        <td><textarea name="txtRemarks" cols="30" rows="3" class="txtfieldnh" id="textarea" wrap="off" readonly="yes"><?php echo $remarks; ?></textarea></td>
                      </tr>
                    </table>			
                  </td>
                </tr>
                <tr>
                  <td colspan="2"></td>
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
            <td class="tabmin"></td>
            <td class="tabmin2">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td class="txtredbold">Official Receipt Details </td>
                </tr>
              </table>
            </td>
            <td class="tabmin3"></td>
          </tr>
        </table>
        
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
          <tr class="bgF9F8F7">
            <td colspan="2"></td>
          </tr>
          <tr>
            <td valign="top" align="center" class="bgF9F8F7" width="100%">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr class="tab">
                  <td height="20" width="10%" class="txt10"><div align="left" class="padl5 bdiv_r"><strong>Payment Type</strong></div></td>
                  <td height="20" width="10%" class="txt10"><div align="left" class="padl5 bdiv_r"><strong>Bank</strong></div></td>
                  <td height="20" width="10%" class="txt10"><div align="center" class="bdiv_r"><strong>Check No.</strong></div></td>
                  <td height="20" width="10%" class="txt10"><div align="center" class="bdiv_r"><strong>Check Date </strong></div></td>
                  <td height="20" width="10%" class="txt10"><div align="center" class="bdiv_r"><strong>Deposit Slip No.</strong></div></td>
                  <td height="20" width="10%" class="txt10"><div align="center" class="bdiv_r"><strong>Date Of Deposit</strong></div></td>
                  <td height="20" width="10%" class="txt10"><div align="center" class="bdiv_r"><strong>Type Of Deposit</strong></div></td>	
                  <td height="20" width="10%" class="txt10"><div align="center" class="bdiv_r"><strong>Account-Campaign</strong></div></td>	
                  <td height="20" width="10%" class="txt10"><div align="right" class="padr5"><strong>Amount</strong></div></td>		
                </tr>
                <?php
                $cnt=0;
                if($rs_details->num_rows) {
                				
                  while($row=$rs_details->fetch_object()) {
                  
                    $cnt++;
                    $class=($cnt%2)?'':'bgEFF0EB';
                    ?>
                    <tr class="<?php echo $class; ?>">
                      <td height="20" class="txt10"><div align="left" class="padl5 borderBR"><?php echo $row->ptype; ?></div></td>
                      <td height="20" class="txt10"><div align="left" class="padl5 borderBR"><?php echo $row->BankName; ?></div></td>
                      <td height="20" class="txt10"><div align="center" class="borderBR"><?php echo $row->CheckNo;  ?></div></td>
                      <td height="20" class="txt10">
                        <div align="center" class="borderBR">
                        <?php 
                        if($row->CheckDate!='N/A') {
                        
                          $tmpTxnDate=strtotime($row->CheckDate);
                          $txndate=date("m/d/Y", $tmpTxnDate);	
                          echo $txndate;		  				
                        } else echo 'N/A'; 
                        ?>
                        </div>
                      </td>
                      <td height="20" class="txt10"><div align="center" class="borderBR"><?php  echo $row->DepositSlipNo;  ?></div></td>
                      <td height="20" class="txt10">
                        <div align="center" class="borderBR">
                          <?php 	                      
                          if($row->DepositDate!='N/A') {
                          
                            $tmpTxnDates=strtotime($row->DepositDate);
                            $txndates=date("m/d/Y", $tmpTxnDate);	
                            echo $txndates;	
                          } else echo 'N/A';                      
                          ?>
                        </div>
                      </td>
                      <td height="20" class="txt10"><div align="center" class="borderBR"><?php echo $row->DepositType ;?></div></td>
                      <td height="20" class="txt10"><div align="center" class="borderBR"><?php echo $row->AccountCampaign ;?></td>
                      <td height="20" class="txt10"><div align="right" class="borderBR"><?php echo number_format($row->TotalAmount, 2); ?>&nbsp;&nbsp;</div></td>
                    </tr>
                <?php
                  }
                } else {
                ?>
                  <tr class="bgF9F8F7">
                    <td height="20" colspan="9"><div align="center" class="borderBR"><span class='txt10 style1'>No record(s) to display.</span></div></td>
                  </tr>
                <?php 
                }
                                                 
                if($cnt==0) echo '<tr class="bgF9F8F7"><td height="20" colspan="9">&nbsp;</td></tr>';
                else {
                
                  echo '<tr>
                  <td height="20" colspan="7" class="borderBR">&nbsp;</td>	
                  <td height="20" class="borderBR"><div align="right" class="padr5"><b>Total :</b></div></td>
                  <td height="20" class="borderBR"><div align="right" class="padr5"><b>'.$totalamt.'</b></div></td>
                  </tr>';
                }                
                ?>		
              </table>
            </td>
            <td valign="top" class="bgF9F8F7" width="500">	
            </td>
          </tr>
        </table>
        <br />
      </td>
    </tr>
  </table>
</form>

<!--ADDED SI DETAILS-->
<form name='frmEditOfficialReceipt2' method='post' action="index.php?pageid=110.1&TxnID=<?php echo $_GET["TxnID"];?> ">
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="tabmin">&nbsp;</td>
      <td class="tabmin2">
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td class="txtredbold"></td>
            <td>
              <table width="50%" border="0" align="left" cellpadding="0" cellspacing="1">
                <tr>
                  <td class="txtredbold">&nbsp;Apply Payment to</td>                                 
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
      <td class="tabmin3">&nbsp;</td>
    </tr>
  </table>
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl3">
    <tr>
      <td valign="top" class="bgF9F8F7">           
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td class="tab ">
              <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
                <tr align="center">                                                    
                  <td width="5%" class="bdiv_r">Line No.</td>
                  <td height="20" width="15%" class="txt10 bdiv_r"><div align="center"><strong>Sales Invoice/Penalties No.</strong></div></td>
                  <td height="20" width="25%" class="txt10 bdiv_r"><div align="left" class="padl5"><strong>IGS Code - Name</strong></div></td>
                  <td height="20" width="10%" class="txt10 bdiv_r"><div align="center"><strong>Transaction Date</strong></div></td>
                  <td height="20" width="15%" class="txt10 bdiv_r"><div align="center" class="padr5"><strong>Transaction Amount</strong></div></td>
                  <td height="20" width="15%" class="txt10 bdiv_r"><div align="center" class="padr5"><strong>Outstanding Balance</strong></div></td>
                  <td height="20" width="15%" class="txt10 bdiv_r"><div align="center" class="padr5"><strong>Amount Applied</strong></div></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td valign="top" class="">            
              <div class="scroll_150" id="dvEditORAddDetails">              
                <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF" id="dynamicTableSIP">
                  <input type="hidden" name="hcntdynamic" id="hcntdynamic" value="1">
                  <tr align='center' class='$alt'>
                    <td width='5%' class='borderBR'>1</td>	
                    <td width="15%" height="20" align="left" class="borderBR padl5">                    
                      <input name="txtSINO1" type="text" class="txtfield" id="txtSINO1" value="" />
                      
                      <span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
                      <div id="dealer_choices2" class="autocomplete" style="display:none"></div>
                      <script type="text/javascript">				                      
                      var url="includes/jxListSIApplyPayment.php?index=1"				
                      //<![CDATA[								
                      var dealer_choices = new Ajax.Autocompleter('txtSINO1', 'dealer_choices2', url , {afterUpdateElement : getSIPenalty, indicator: 'indicator2'});																			
                      //]]>
                      </script>
                      <input name="hdnIID1" type="hidden" id="hdnIID1" value="" />
                      <input name="hdnRID1" type="hidden" id="hdnRID1" value="" />
                      <input name="hdnCreditTerm1" type="hidden" id="hdnCreditTerm1" value="" />
                    </td>
                    <td width='25%' align='left'   class='borderBR padl5'><input name="txtIGSName1" type="text" class="txtfieldLabel" id="txtIGSName1"  readonly="yes"/></td>
                    <td width='10%' align='right' class='borderBR'><input name="txttxnDate1" type="text" class="txtfieldLabel" id="txttxnDate1"  readonly="yes"/></td>	
                    <td width='15%' align='right'  class='borderBR'><input name="txttxnAmount1" type="text" class="txtfieldLabel" id="txttxnAmount1"  readonly="yes"/></td>	
                    <td width='15%' align='right'  class='borderBR'><input name="txtOutStandingBalance1" type="text" class="txtfieldLabel" id="txtOutStandingBalance1"  readonly="yes"/></td>	
                    <td width='15%' align='right'  class='borderBR padr5'><input name="txtAppliedAmount1" type="text" class="txtfield" id="txtAppliedAmount1" onkeyup="validateAmountApplied(this.id);" onkeypress="return calculateIGSAmt(this, event,1);" ></td>									                       
                  </tr>									                                                                       
                </table>
                <input type='hidden' name='txtTotalAmount'  style='text-align:right'  class='txtfield' value="">	
                <input name="txtTotalUnapplieds" type="hidden" class="txtfield"  style="text-align:right"  value="<?php echo $totalunappamt;?>" readonly="yes">			                  
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
  
  <br />
  <br />
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
    <tr>					
      <td align="center">
        <!--<input name='btnprint' type='submit' class='btn' value=' Print' onclick="javascript: return validatePrint(<?php echo $_GET['TxnID']; ?>,1);" >						
        <?php if($txnstatusId=='7'): ?>
        <input name='btnCancelOR' id='btnCancelOR' type='button' class='btn' value='Cancel OR' onclick='return validateCancelOR();' />
        <?php endif; ?>
        -->
                
        <input type="submit" name="btnSave" value="Confirm and Print" onclick="return validateSave(this, event,1);" class="btn" />
        <input type="submit" name="btnCancel" value="Back to List" class="btn" />
        <input type="hidden" name="countRows" value="<?php echo $prodCount;?>" />
      </td>
    </tr>
  </table>
  <br>
</form>