<?php  include IN_PATH . DS . "scCreateSpecialPromo.php"; ?>
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<!-- product list -->

<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css"/>
<script language="javascript" src="js/jquery-1.9.1.min.js"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/popinbox.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxCreateSpecialPromo.js"  type="text/javascript"></script>

<style type="text/css">
    
div.autocomplete {
  position:absolute;
  width:300px;
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

.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
.ui-datepicker{display:none;}
.trheader td{padding:5px;}
.trlist td{padding:2px;}
</style>

<body>
<?php //<form name="frmCreateSingleLine" method="post" action="index.php?pageid=61" onsubmit="return validateForm()" > ?>
<form name="formCreateSpecialPromo" method="post" action="" style="min-height: 610px;">
    
    <input name="counter" value="1" type="hidden">
    
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="topnav">
                <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                        <td width="70%" class="txtgreenbold13" align="left"></td>
                        <td width="70%" align="right">&nbsp;
                            <a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td>
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                        <td width="70%">&nbsp;<a class="txtgreenbold13">Create Special Promo</a></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="tabmin"></td> 
            <td class="tabmin2"><div align="left" class="padl5 txtredbold"><b>Promo Header</b></div></td>
            <td class="tabmin3">&nbsp;</td>
        </tr>
    </table>
    <!-- FORM HEADER -->
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        <tr>
            <td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
			<td>
                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
								<tr>
                                    <td width="20%">&nbsp;</td>
									<td width="5%"></td>
                                    <td>&nbsp;</td>
								</tr>			
								<tr>
									<td height="20" align="right"><strong>Promo Code</strong></td>
									<td align="center">:</td>
									<td height="20">
                                        <input type="text" class="txtfieldg" value="" name="txtCode" onkeydown="return CheckPromo(event);" id="txtCode" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
									<td height="20" align="right"><strong>Promo Description</strong></td>
									<td align="center">:</td>
									<td height="20">
                                        <input name="txtDescription" type="text" class="txtfieldg" disabled = "true" id="txtDescription" value="" size="30" style="width: 362px;" maxlength="60">
                                    </td>
                                </tr>
                                <tr>
								<td height="20" align="right"><strong>Promo Type</strong></td>
								<td align="center">:</td>
								<td height="20">
                                        <select name="promotype" class="txtfieldg" disabled="true" onchange="return Nextfield();">
                                            <option value="0">SELECT</option>
                                            <?php 
                                                $promotype = array("Coupon", "New Dealer Incentives");
                                                foreach($promotype as $key => $val){
                                            ?>            
                                            <option value="<?=$key+1?>"><?=$val?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                </tr>
								<tr>
                                    <td height="20" align="right"><strong>Start Date</strong></td>
									<td align="center">:</td>
                                    <td width="20%" height="20">
                                        <input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" size="20" readonly="yes" disabled = "true" value="<?php echo $today; ?>">
                                        <i>(e.g. MM/DD/YYYY)</i>
                                    </td>
								</tr>
								<tr>
                                    <td height="20" align="right"><strong>End Date</strong></td>
									<td align="center">:</td>
                                    <td width="20%" height="20">
                                        <input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" size="20" readonly="yes" value="<?php echo $end; ?>" disabled = "true">
                                        <i>(e.g. MM/DD/YYYY)</i>
                                    </td>
								</tr>	
								<tr>
                                    <td height="20" align="right"><strong>Brochure Page</strong></td>
									<td align="center">:</td>
                                    <td width="10%" height="20">
                                        <input name="bpage" type="text" onkeyup="return RemoveInvalidChars(this);" value = "0" class="txtfieldg" id="bpage" size="10" value="" style = "width: 5%;" disabled = "true">&nbsp; - &nbsp;
                                        <input name="epage" type="text" onkeyup="return RemoveInvalidChars(this);" value = "0" class="txtfieldg" id="epage" size="10" value="" style = "width: 5%;" disabled = "true">
                                    </td>
								</tr>					
								<tr>
                                    <td colspan="3" height="20">&nbsp;</td>
								</tr>
                            </table>
						</td>
						<td width="50%" valign="top">
                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
								<tr>
                                    <td width="15%">&nbsp;</td>
                                    <td width="85%">&nbsp;</td>
								</tr>
								<tr>
								<td height="20" valign="top" colspan="2">
                                    <strong>Max Availment :</strong>
                                    <br />
									<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                                            <?php
												if ($rs_gsutype->num_rows) {
													while ($row = $rs_gsutype->fetch_object()) {
																					$txt = 'txtMaxAvail' . $row->ID;
																					echo "<tr>
																							<td width='15%' align='right' height='20'><strong>$row->Name</strong></td>
																							<td align='center' width='5%'>:</td>
																							<td width='75%' height='20'><input type='text' id='MaxAvail' value=0 onkeyup='return RemoveInvalidChars(this);'  class='txtfieldg' name='$txt'></td>
																							</tr>";
													}
													$rs_gsutype->close();
												}
												?>
                                            <tr>
                                                <td height="20" align="right">
													<label for="chkplus">
														<strong>Is Plus Plan</strong>
													</label>
                                                </td>
												<td align="center" width='5%'>:</td>
                                                <td height="20"><input type="checkbox" id="chkplus" name="chkPlusPlan" value="1"></td>
                                            </tr>
										</table>
									</td>
                                </tr>
                            </table>
						</td>
                    </tr>
				</table>
            </td>
        </tr>
    </table>
    <br />
    <!-- END FORM HEADER -->

    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <!-- DYNAMIC BUYIN REQUIREMENT TABLE START HERE -->
            <td width= "100%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold"><b>Buy-in Requirement and Entitlement</b></div></td>
			<td class="tabmin3">&nbsp;</td>
                    </tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">                    
                    <tr>
						<td>
                            <div style="max-height:220px; overflow:auto;">
                                <table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicTable" class="bgFFFFFF">
									<tr align="center" class="trheader" style="border-bottom:1px solid #FFA3E0; background:#FFDEF0;">
										<td width="4%" class="borderBR"><strong>Action</strong></td>
										<td width="6%" class="borderBR"><strong>Line No.</strong></td>
										<td width="10%" class="borderBR"><strong>Item Code</strong></td>
										<td width="22%" class="borderBR"><strong>Item Description</strong></td>			
										<td width="7%" class="borderBR"><strong>Buyin Criteria</strong></td>
										<td width="12%" class="borderBR"><strong>Buyin Minimum</strong></td>			
										<td width="10%" class="borderBR"><strong>Entitlement Criteria</strong></td>
										<td width="12%" class="borderBR"><strong>Entitlement Minumum</strong></td>			
										<td width="13%" class="borderBR"><strong>PMG</strong></td>
									</tr>
                                    <tr align="center" class="trlist">
                                        <td class="borderBR">
                                            <input name="btnRemove1" type="button" class="btn" value="Remove">
                                        </td>
                                        <td class="borderBR"><div align="center">1</div></td>
                                        <td class="borderBR">
                                            <div align="center">
                                                <input name="txtProdCode1" type="text" class="txtfieldg" id="txtProdCode1" style="width: 70px;" value="" onkeypress="return selectItemCode(this.id);" disabled = "true" />
                                                <input name="hProdID1" type="hidden" id="hProdID1" value="" />
                                            </div>
                                        </td>
                                        <td class="borderBR">
                                            <div align="center">
                                                <input name="txtProdDesc1" type="text" disabled = "true" class="txtfield" id="txtProdDesc1" style="width: 95%;" readonly="yes" />
                                            </div>
                                        </td>			
                                        <td class="borderBR">
                                            <div align="center">
                                                <select name="cboCriteria1" class="txtfield" id="cboCriteria1" disabled = "true" style="width: 90%;" >
                                                    <option value="2">Amount</option>
                                                    <option value="1" selected="selected">Quantity</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="borderBR">
                                            <div align="center">
                                                <input name="txtQty1" disabled = "true"  type="text" class="txtfield" id="txtQty1"  value="1" style="width: 90%; text-align:right" />
                                            </div>
                                        </td>
                                        <td class="borderBR">
                                            <div align="center">
                                                <select name="cboECriteria1" disabled = "true" class="txtfield" id="cboECriteria1" style="width: 90%;">
                                                    <option value="2" selected="selected">Price</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="borderBR">
                                            <div align="center">
                                                <input name="txtEQty1" disabled = "true" type="text" class="txtfield" id="txtEQty1"  onkeypress='return addRow(event);' value="1" style="width: 90%; text-align:right" />
                                            </div>
                                        </td>			
                                        <td class="borderBR">
                                            <div align="center">
                                                <select name="txtbPmg1" id = "txtbPmg1" disabled = "true" class = "txtfield" style="width: 80%">
                                                    <option value="1">CFT</option>
                                                    <option value="2">NCFT</option>
                                                    <option value="3">CPI</option>
                                                </select>
                                            </div>
                                        </td>			
                                    </tr>
                                </table>
                            </div>
			</td>
                    </tr>
		</table>
            </td>
            <!-- DYNAMIC BUYIN REQUIREMENT TABLE END HERE -->
            <td width= "1%">&nbsp;</td>
            <!-- DYNAMIC ENTITLEMENT TABLE START HERE -->
            
            <!-- DYNAMIC ENTITLEMENT TABLE END HERE -->
        </tr>
    </table>
    <br>
    <table width="98%" align="left"  border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <input name='btnSaves' type='button' class='btn' value='Save' id = 'savebtn' disabled = "true"  onclick='return confirmSave();'>
                <input name='btnCancel' type='button' class='btn' value='Cancel' onclick='return confirmCancel();'>
            </td>			
        </tr>
    </table>
    <br><br>
</form>
    
<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>