<?php
/*   
  @modified by John Paul Pineda.
  @date December 21, 2012.
  @email paulpineda19@yahoo.com         
*/ 
?>

<link rel="stylesheet" type="text/css" href="../../css/calpopup.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script type="text/javascript" src="js/jsUtils.js"></script>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jxCreateDMCM.js"></script>

<style>
<!--
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}


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
-->
</style>

<?php include(IN_PATH.DS.'scCreateDMCM.php'); ?>

<form name="frmCreateDMCM" method="post" action="index.php?pageid=46">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="topnav">
              <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td width="70%" align="right"><a href="index.php?pageid=18" class="txtblueboldlink">Sales Main</a></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br />
        <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td class="txtgreenbold13 padl5">Create Debit / Credit Memo </td>
          </tr>
        </table>
        <br />
        <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td><span class="txtblueboldlink"><?php echo isset($_GET['msg'])?$_GET['msg']:''; ?> <?php echo $message; ?></span></td>
          </tr>        
        </table>
        <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td valign="top">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="3">
                <tr>
                  <td width="73%" valign="top">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td class="tabmin"></td>
                        <td class="tabmin2">
                          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                              <td class="txtredbold">General Information</td>
                            </tr>
                          </table>
                        </td>
                        <td class="tabmin3"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
                      <tr>
                        <td valign="top" class="bgF9F8F7">
                          <div class="scroll_350">
                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                              <tr>
                                <td width="40%" valign="top">
                                  <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                                    <tr>
                                      <td colspan="3" height="10">&nbsp;</td>
                                    </tr>														
                                    <tr>
                                      <td width="20%" height="20" align="left" class="padl5 txt10">Customer Code</td>
                                      <td width="3%" height="20" class="txt10">:</td>
                                      <td width="77%" height="20">
                                        <input name="txtCustomer" type="text" class="txtfield" id="txtCustomer" size="35" style="font:bold; width:160px;" value="<?php echo isset($_GET['dCode'])?$_GET['dCode']:''; ?>" tabindex="2" />                     
                                        		<span id="indicator2" style="display: none">
                                              <img src="images/ajax-loader.gif" alt="Working..." />
                                            </span>                                      
                                        		<div id="cust_choices" class="autocomplete" style="display:none"></div>
                                        		<script type="text/javascript">                    
                                           		//<![CDATA[
                                             		var cust_choices=new Ajax.Autocompleter('txtCustomer', 'cust_choices', 'includes/scCustomerListAjaxDMCM.php?index=1', {afterUpdateElement: getSelectionCustomer, indicator: 'indicator2'});
                                          		//]]>
                                        		</script>
                                        		<input type="hidden" id="hCustomerID" name="hCustomerID" value="<?php echo isset($_GET['custID'])?$_GET['custID']:''; ?>" />
                                      </td>
                                    </tr>
                                    <tr>
                                      <td height="20" align="left" class="padl5 txt10">Customer Name</td>
                                      <td height="20">:</td>
                                      <td heights="20">
                                        <input type="text" id="txtDealerName" name="txtDealerName" readonly="readonly" size="35" maxlength="15" tabindex="6" value="<?php echo isset($_GET['dName'])?$_GET['dName']:''; ?>" class="txtfieldLabel" style="text-align:left" />
                                      </td>
                                    </tr>
                                    <tr>
                                      <td height="20" align="left" class="padl5 txt10">IBM No. /IBM Name</td>
                                      <td height="20">:</td>
                                      <td heights="20">
                                        <input type="text" id="txtIBMCode" name="txtIBMCode" readonly="readonly" size="35" maxlength="35" tabindex="6" class="txtfieldLabel" style="text-align:left;" />
                                      </td>
                                    </tr>
                                    <tr>
                                      <td width="12%" height="20" align="left" class="txt10">Memo Type</td>
                                      <td width="3%" height="20" class="txt10">:</td>
                                      <td width="85%" height="20">
                                        <select name="cboMemoType" id="cboMemoType" class="txtfield"  <?php echo $able; ?> >
                                          <option value="index.php?pageid=46&mtid=0" selected="selected">[SELECT HERE]</option>
                                          <?php $n=$_POST['txtCustomer']; ?>
                                          <?php if($rs_memoType->num_rows): ?>                                          	
                                          <?php while($row=$rs_memoType->fetch_object()): ?>                                          		                                          			                                           			                                          			                                           				
                                          <option value="<?php echo $row->ID; ?>"<?php echo $_GET['mtid']==$row->ID?' selected="selected"':''; ?>><?php echo $row->Name; ?></option>
                                          <?php endwhile; $rs_memoType->close(); ?>                                          		
                                          <?php endif; ?>
                                        </select>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td height="20" align="left" class="txt10">Reason Code</td>
                                      <td height="20" class="txt10">:</td>
                                      <td height="20">
                                        <div id="reasonArea">
                                          <select name="cboReason" class="txtfield" >
                                          <option value="index.php?pageid=46" selected="selected">[SELECT HERE]</option>
                                          <?php if($rs_reason->num_rows): ?>                                          
                                          <?php while($row=$rs_reason->fetch_object()): ?>	                                          
                                          <option value="<?php echo $row->ID; ?>" <?php echo $sel; ?>><?php echo $row->Name; ?></option>
                                          <?php endwhile; $rs_reason->close(); ?>                                          
                                          <?php endif; ?>
                                          </select>
                                        </div>
                                      </td>
                                    </tr>				                         
                                      
                                    <tr>
                                    	<td height="20" align="left" class="padl5 txt10">Total Amount</td>
                                    	<td height="20">:</td>
                                    	<td height="20"><input name="txtMemoAmt" type="text" class="txtfield" id="txtMemoAmt" size="35" maxlength="15" tabindex="6" <?php echo $amount_style; ?> value="<?php if(isset($_GET['amt'])){ echo $_GET['amt']; }; ?>"/></td>
                                    </tr>
                                    <tr>
                                    	<td height="20" align="left" class="txt10">OR No.</td>
                                    	<td height="20" class="txt10">:</td>
                                    	<td height="20"><input name="txtParticulars" type="text" class="txtfield" id="txtParticulars" size="25" maxlength="50" tabindex="7" value="<?php if(isset($_GET['orNo'])){ echo $_GET['orNo']; }; ?>"/></td>
                                    </tr>	
                                    <tr>
                                    <td colspan="3" height="10">&nbsp;</td>
                                    </tr>					
                                  </table>
                                </td>
                                <td valign="top" width="60%">
                                <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                                <tr>
                                <td colspan="3" height="10">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="20" align="left" class="padl5 txt10">DMCM No.</td>
                                 	<td height="20">:</td>
                                 	<td height="20"><?php echo $dmcmNo; ?><input name="txtRefNo" type="hidden" id="txtRefNo" value="<?php echo $dmcmNo; ?>"/></td>
                                </tr>		
                                <tr>
                                	<td height="20" align="left" class="padl5 txt10">Document No.</td>
                                 	<td height="20">:</td>
                                 	<td height="20">
                                 		<input name="txtDocNo" type="text" class="txtfield" id="txtDocNo" readonly="yes" value="<?php echo $bir_series; ?>"/>
                                 		<input name="txtBIRSeriesID" type="hidden" id="txtBIRSeriesID" value="<?php echo $bir_id; ?>"/>
                                	</td>
                                </tr>
                                <tr>
                                <td height="20" align="left" class="padl5 txt10">Branch Name:</td>
                                 	<td height="20">:</td>
                                 	<td height="20"><?php echo $branch; ?></td>
                                </tr>
                                <tr>
                                <td height="20" align="left" class="padl5 txt10">Created By:</td>
                                 	<td height="20">:</td>
                                 	<td height="20"><?php echo $employee; ?></td>
                                </tr>
                                <tr>
                                <td height="20" align="left" class="padl5 txt10">DMCM Date</td>
                                <td height="20">:</td>
                                <td height="20"><?php echo $datetoday; ?></td>
                                </tr>
                                
                                <tr>
                                <td height="20" align="left" valign="top" class="txt10">Remarks</td>
                                <td height="20" valign="top" class="txt10">:</td>
                                <td height="20"><textarea name="txtRemarks" cols="42" rows="3" class="txtfieldnh" id="txtRemarks" wrap="off" tabindex="8" ><?php if(isset($_GET['remarks'])){ echo $_GET['remarks']; }; ?></textarea></td>
                                </tr>
                                	</table>
                                </td>
                              </tr>
                              <tr>
                              <td colspan="3" height="10">&nbsp;</td>
                              </tr>
                            </table>
                          </div>
                        </td>
                      </tr>
                    </table>
                    <br />
                  </td>
                </tr>
              </table>
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>   	
              <td valign="top">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
              <td class="tabmin"></td>
              <td class="tabmin2 txtredbold">Select Sales Invoice(s) / Penalties</td>
              <td class="tabmin3"></td>
              </tr>
              </table> 
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">	
              <tr>
              <td class="tab">
              <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
              <tr align="center">
              <td width="5%" height="20" class="bdiv_r"><input tabindex="9" name="chkAll2" type="checkbox" id="chkAll2" value="1" onclick="checkAll2(this.checked);" ></td>
              <td width="15%" height="20" class="bdiv_r">Invoice /Penalties No. </td>
              <td width="15%" height="20" class="bdiv_r">IGS Code - Name</td>
              <td width="15%" height="20" class="bdiv_r">Transaction Date</td>
              <td width="15%" height="20" class="bdiv_r">Transaction Amount</td>
              <td width="15%" height="20" class="bdiv_r">Outstanding Balance</td>
              <td width="15%" height="20">Amount</td>
              </tr>
              </table>
              </td>
              </tr>
              <tr>
              <td width="100%"><div class="scroll_300" id="siDetails">
              <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
              <tr align="center">
              <td width="100%" height="20" class="borderBR style1">Select customer first.</td>
              </tr>
              </table>
              </div></td>
              </tr>	
              <tr>
              <td class="bgF9F8F7">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
              <td width="85%" height="25" align="right" class="borderBR txt10 padr5"><div align="right"><strong>Total Payment :</strong></div></td>
              <td width="15%" height="25" align="left" class="borderBR padl5"><div align="center"><input name="txtTotPayment" type="text" value="" class="txtfield3" style="text-align:right" readonly="readonly"></div></td>
              </tr>
              </table>
              </td>
              </tr>
              </table>
              <br />
              <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
              <td align="center">
              <input tabindex="11" name="btnSave" id="btnSave" type="submit" class="btn" value="Save" onClick="return ConfirmSave();">
              <input tabindex="12" name="btnCancel" id="btnCancel" type="submit" class="btn" value="Cancel" onclick="return cancelTxn();">
              </td>
              </tr>
              </table>
              <br>
              </td>
              </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>