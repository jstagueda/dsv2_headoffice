<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<script type="text/javascript" src="js/jsUtils.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="js/jxRecordDepositSlip.js"></script>

<?php require(IN_PATH.DS.'scRecordDepositSlip.php'); ?>
	
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<br />

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="txtgreenbold13">Branch Collection Deposits</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <br />
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td width="40%" valign="top">
            <form name="frmSearchDepositSlip" method="post" action="index.php?pageid=154">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
                <tr>
                  <td>
                    <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr>
                        <td width="50%">&nbsp;</td>
                        <td width="29%" align="right">&nbsp;</td>
                        <td width="21%" align="right">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3">
                        Date :
                        &nbsp;            
                        <input name="txtSearchDate" type="text" class="txtfield" id="txtSearchDate" size="20" readonly="yes" value="<?php echo $sdate; ?>" style="width: 80px;">
                        <input type="button" class="buttonCalendar" name="anchorSearchDate" id="anchorSearchDate" value="">
                        <div id="divSearchDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
                        &nbsp;
                        <input name="btnSearch" type="submit" class="btn" value="Search" value="">
                        </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td align="right">&nbsp;</td>
                        <td align="right">&nbsp;</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </form>
            <br />
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td class="tabmin">&nbsp;</td>
                <td class="tabmin2"><span class="txtredbold">List of Deposits</span></td>
                <td class="tabmin3">&nbsp;</td>
              </tr>
            </table>
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
              <tr>
                <td valign="top" class="bgF9F8F7">
                  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td class="tab bordergreen_T">
                        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                          <tr align="center">
                            <td width="20%"><div align="center" class="bdiv_r"><span class="txtredbold">Date</span></div></td>
                            <td width="30%"><div align="left" class="padl5 bdiv_r"><span class="txtredbold">Bank</span></div></td>
                            <td width="25%"><div align="left" class="padl5 bdiv_r"><span class="txtredbold">Validation No.</span></div></td>
                            <td width="25%"><div align="right" class="padr5"><span class="txtredbold">Total Amount</span></div></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="bordergreen_B">
                        <div class="scroll_300">
                          <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">								
                            <?php if($rs_depsliplist->num_rows): $rowalt=0; ?>                                                        
                            <?php while($row=$rs_depsliplist->fetch_object()): $rowalt++; ?>
                            <?php                             
                            $class=($rowalt%2)?'':'bgEFF0EB';
                            $colldate=date('m/d/Y', strtotime($row->CollectionDate));
                            $totamt=number_format($row->TotalAmount, 2);
                            ?>
                            <tr align="center" class="<?php echo $class; ?>">
                              <td width="20%" height="20" class="borderBR"  align="center"><span class="txt10"><?php echo $colldate; ?></span></td>
                              <td width="30%" height="20" class="borderBR padl5" align="left"><span class="txt10"><?php echo $row->Bank; ?></span></td>
                              <td width="25%" height="20" class="borderBR" align="left">&nbsp;<span class="txt10"><?php echo $row->DepositValidationNo; ?></span></td>
                              <td width="25%" height="20" class="borderBR padr5" align="right">&nbsp;<span class="txt10"><?php echo $totamt; ?></span></td>
                            </tr>
                            <?php endwhile; $rs_depsliplist->close(); ?>
                                                        
                            <?php else: ?>
                            <tr align="center">
                              <td height="20" colspan="4" class="borderBR">
                                <span class="txt10 txtredsbold">No record(s) to display. </span>
                              </td>
                            </tr>
                            
                            <?php endif; ?>
                          </table>
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
          <td width="2%">&nbsp;</td>
          <td width="40%" valign="top">
            <form name="frmRecordDepositSlip" method="post" action="includes/pcRecordDepositSlip.php">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="tabmin">&nbsp;</td>
                  <td class="tabmin2"><span class="txtredbold">Deposit Details</span></td>
                  <td class="tabmin3">&nbsp;</td>
                </tr>
              </table>
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
                <tr>
                  <td class="bgF9F8F7">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <?php 
                      if(isset($_GET['msg'])) {
                      
                        $message=strtolower($_GET['msg']);
                        $success=strpos($message, 'success'); 
                        echo '<div align="left" style="padding:5px 0 0 5px;" class="txtblueboldlink">'.$_GET['msg'].'</div>';
                      } else if(isset($_GET['errmsg'])) {
                      
                        $errormessage=strtolower($_GET['errmsg']);
                        $error=strpos($errormessage, 'error'); 
                        echo '<div align="left" style="padding:5px 0 0 5px;" class="txtredsbold">'.$_GET['errmsg'].'</div>';
                      }
                      ?>
                      <tr>
                        <td colspan="3" height="15">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="20" width="25%" align="right" class="txt10">Collection Date:</td>
                        <td height="20" width="5%">&nbsp;</td>
                        <td height="20" width="70%">
                          <input name="txtCollectionDate" type="text" class="txtfield" id="txtCollectionDate" size="20" readonly="yes" style="width: 60px;" value="<?php echo $today; ?>" onchange="loadTotalAmounts();" />
                          <input type="button" class="buttonCalendar" name="anchorCollectionDate" id="anchorCollectionDate" value="" />
                          <div id="divCollectionDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
                          <div id="CollTotAmts">
                            <input name="hdnTotCashAmt" id="hdnTotCashAmt" type="hidden" value="<?php echo $cash; ?>" />
                            <input name="hdnTotCheckAmt" id="hdnTotCheckAmt" type="hidden" value="<?php echo $check; ?>" />
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td height="20" align="right" class="txt10">Bank:</td>
                        <td height="20">&nbsp;</td>
                        <td height="20">
                          <select name="cboBank" id="cboBank" class="txtfield" style="width: 200px;">
                            <option value="0">[SELECT HERE]</option>
                            <?php if($rs_banklist->num_rows): ?>                          
                            <?php while($row=$rs_banklist->fetch_object()): ?>                                                                                                                                                                                  
                            <option value="<?php echo $row->ID; ?>"<?php echo $row->IsPrimary?' selected="selected"':''; ?>><?php echo $row->Name; ?></option>
                            <?php endwhile; $rs_banklist->close(); ?>                          
                            <?php endif; ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td height="20" align="right" class="txt10">Branch Code:</td>
                        <td height="20">&nbsp;</td>
                        <td height="20">
                          <input name="txtBranchCode" type="text" class="txtfield" id="txtBranchCode" size="20" value="" style="width: 200px;">
                        </td>
                      </tr>
                      <tr>
                        <td height="20" align="right" class="txt10">Deposit Date:</td>
                        <td height="20">&nbsp;</td>
                        <td height="20">
                          <input name="txtDepositDate" type="text" class="txtfield" id="txtDepositDate" size="20" readonly="yes" style="width: 60px;" value="<?php echo $today; ?>">
                          <input type="button" class="buttonCalendar" name="anchorDepositDate" id="anchorDepositDate" value="" />
                          <div id="divDepositDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
                        </td>
                      </tr>                      
                      <tr>
                        <td height="20" align="right" class="txt10">Deposit Validation No.:</td>
                        <td height="20">&nbsp;</td>
                        <td height="20">
                          <input name="txtValidationNo" type="text" class="txtfield" id="txtValidationNo" size="20" value="" style="width: 200px;">
                        </td>
                      </tr>
                      <tr>
                        <td height="20" align="right" class="txt10">Total Cash Amount:</td>
                        <td height="20">&nbsp;</td>
                        <td height="20">
                          <input name="txtTotCash" type="text" class="txtfield" id="txtTotCash" size="20" value="" style="width: 200px;" />
                        </td>
                      </tr>
                      <tr>
                        <td height="20" align="right" class="txt10">Total Check Amount:</td>
                        <td height="20">&nbsp;</td>
                        <td height="20">
                          <input name="txtTotCheck" type="text" class="txtfield" id="txtTotCheck" size="20" value="" style="width: 200px;" />
                        </td>
                      </tr>
                      <tr>
                        <td height="20" align="right" class="txt10" valign="top">Check Numbers:</td>
                        <td height="20">&nbsp;</td>
                        <td height="20"><textarea name="txtCheckNos" cols="30" rows="2" class="txtfieldnh" id="txtCheckNos"></textarea></td>
                      </tr>
                      <tr>
                        <td colspan="3" height="20">&nbsp;</td>
                      </tr>
                    </table>		
                  </td>
                </tr>
              </table>
              <br />
              <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td align="center">
                    <input name="btnSave" type="submit" class="btn" value="Save" onclick="return confirmSave();" />
                    <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location='index.php?pageid=154';" />
                  </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<br />

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtSearchDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSearchDate",
		button         :    "anchorSearchDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
  
	Calendar.setup({
		inputField     :    "txtDepositDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divDepositDate",
		button         :    "anchorDepositDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
  
	Calendar.setup({
		inputField     :    "txtCollectionDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divCollectionDate",
		button         :    "anchorCollectionDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>