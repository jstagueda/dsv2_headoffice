<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    <tr>
		      <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="#">Sales Main</a></td>
		    </tr>
		</table>
		</td>
	</tr>
</table>
      <br>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Create Sales Invoice</td>
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
        </tr>
    </table></td>
    <td class="tabmin3">&nbsp;</td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
  <tr>
    <td valign="top" class="bgF9F8F7"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td width="50%" valign="top">
            <table width="98%"  border="0" cellspacing="1" cellpadding="0">

              <tr>
                <td width="40%" height="25" class="txt10">Reference No. </td>
                <td width="58%">&nbsp;</td>
              </tr>
              <tr>
                <td width="40%" height="25" class="txt10">DR Date </td>
                <td width="58%">&nbsp;</td>
              </tr>		  
              <tr>
                <td width="40%" height="25" class="txt10">Customer Code </td>
                <td width="58%">&nbsp;</td>
              </tr>
              <tr>
                <td width="40%" height="25" class="txt10">Customer Name </td>
                <td width="58%">&nbsp;</td>
              </tr>
			  <tr>
                <td width="40%" height="25" class="txt10">Credit Limit </td>
                <td width="58%">&nbsp;</td>
              </tr>
			  <tr>
                <td width="40%" height="25" class="txt10">Credit Status </td>
                <td width="58%">&nbsp;</td>
              </tr>
</table></td>
         				 <td valign="top"><table width="98%"  border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td width="40%" height="25" class="txt10">Document No. </td>
                <td width="58%"><input name="txtDocumentNo" type="text" class="txtfield" size="30" maxlength="20"></td>
              </tr>
              <tr>
                <td width="40%" height="25" class="txt10">si Date </td>
                <td width="58%">
					<input name="txtTxnDate" id="txtTxnDate" type="text" class="txtfield" size="30">
					<input type="button" class="buttonCalendar" name="anchorTxnDate" id="anchorTxnDate" value=" ">
					<div id="divTxnDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>				</td>
              </tr>
              <tr>
                <td width="40%" height="25" class="txt10">Effectivity Date </td>
                <td width="58%"><input name="txtEffectivityDate" id="txtEffectivityDate" type="text" class="txtfield" size="30" />
                  <input type="button" class="buttonCalendar" name="anchorEffectivityDate" id="anchorEffectivityDate" value=" ">
					<div id="divEffectivityDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>				</td>
              </tr>			  
              <tr>
                <td width="40%" height="25" class="txt10">Payment terms </td>
                <td width="58%">
					<select name="cboTerms" style="width:100px" class="txtfield" >
						<option value="">Name</option>
					</select>				</td>
              </tr>
              <tr>
                <td width="40%" height="25" valign="top" class="txt10">Salesman</td>
                <td width="58%">
					<select name="cboSalesman" style="width:200px" class="txtfield">
						<option value="">Name</option>
					</select>
				</td>
              </tr>
              <tr>
                <td width="40%" height="25" valign="top" class="txt10">Remarks </td>
                <td width="58%"><textarea name="txtRemarks" cols="30" rows="3" class="txtfieldnh"></textarea></td>
              </tr>
          </table></td>
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
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="40%">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="95%" valign="top">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						  	<tr>
								<td class="tabmin">&nbsp;</td>
								<td class="tabmin2">
									<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
										<tr>
										  	<td class="txtredbold">Delivered Products </td>
									  	</tr>
									</table>
								</td>
								<td class="tabmin3">&nbsp;</td>
						  	</tr>
						</table>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bordergreen" id="tbl4">
							<tr >
								<td class="tab">
                                	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                                    <tr align="center">
                                      <td width="6%">Line No</td>	  
                                      <td width="8%">product Code</td>	  
                                      <td width="20%">product Name</td>		  
                                      <td width="7%">uom</td>
                                      <td width="8%">Ticket No.</td>
                                      <td width="8%">Booklet No.</td>
                                      <td width="8%">Ordered Qty</td>
                                      <td width="8%">Delivered Qty</td>
                                      <td width="8%">Unit Price</td>
                                      <td width="9%">Discount</td>
                                      <td width="10%">Net Amount</td>
                                    </tr>
                                </table>
                                	
                                </td>
							</tr>
                            <tr>
                            	<td>
                                	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bgF9F8F7">
                                         <tr>
                                            <td width="95%" valign="top">
                                                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td colspan="2"><div class="scroll_300">
                                                          <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                                                            <tr align="center"> 
                                                                <td width="6%" height="20" class="borderBR">&nbsp;</td>
                                                                <td width="8%" height="20" class="borderBR">&nbsp;</td>
                                                                <td width="20%" height="20" class="borderBR">&nbsp;</td>
                                                                <td width="7%" class="borderBR">&nbsp;</td>
                                                                <td width="8%" class="borderBR">&nbsp;</td>
                                                                <td width="8%" height="20" class="borderBR">&nbsp;</td>
                                                                <td width="8%" height="20" class="borderBR"><input name="txtOrdQty" type="text" class="txtfield3" readonly="yes" size="12" maxlength="9"></td>
                                                                <td width="8%" height="20" class="borderBR"><input name="txtDelQty" type="text" class="txtfield3" readonly="yes" size="12" maxlength="9"></td>
                                                                <td width="8%" height="20" class="borderBR"><input name="txtUPrice" type="text" class="txtfield3" readonly="yes" size="12" maxlength="15"></td>
                                                                <td width="9%" height="20" class="borderBR"><input name="txtDiscPerLine" type="text" class="txtfield3" readonly="yes" size="12" maxlength="15"></td>
                                                                <td width="10%" height="20" class="borderBR"><input name="txtTotalNetPrice" type="text" class="txtfield3" readonly="yes" size="15" maxlength="20"></td>
                                                            </tr>   			   
                        </table>
                                                      </div></td>
                                                </tr>
                                                <tr>
                                                    <td width="85%" height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">GROSS AMOUNT :</span></div></td>
                                                    <td width="15%" height="20" colspan="-8" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtGrossAmt" value="0.00" class="txtfield2" style="text-align:right" readonly="yes" /></div></td>
                                                
            
                                                </tr>
                                                <tr>
                                                    <td height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">Discount 1 :</span></div></td>
                                                    <td height="20" colspan="-8" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtDisc1" class="txtfield2" style="text-align:right" readonly="yes" width="10px" />%</div></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">Discount 2 :</span></div></td>
                                                    <td height="20" colspan="-8" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtDisc2" class="txtfield2" style="text-align:right" readonly="yes" />%</div></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">Discount 3 :</span></div></td>
                                                    <td height="20" colspan="-8" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtDisc3" class="txtfield2" style="text-align:right" readonly="yes" />%</div></td>
                                                
                                                </tr>
                                            <tr>
                                                <td height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">AMOUNT W/O VAT:</span></div></td>
                                                <td height="20" colspan="-8" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtVATAmt" value="0.00" class="txtfield2" style="text-align:right" readonly="yes" /></div></td>
                                                
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
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			  	<tr>
					<td height="3" class="bgE6E8D9"></td>
			  	</tr>
			</table>
		</td>
	</tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">

  <tr>
    <td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="85%" height="20" align="right" class="txtbold">si DISCOUNT :&nbsp;</td>
          <td width="15%" height="20" align="left" class="padl5"><input name="txtSIDisc" id="txtSIDisc" type="text" class="txtfield" value="" size="20" maxlength="9" style="text-align:right; "></td>
        </tr>
		<input name="txtWTax" id="txtWTax" type="hidden"  class="txtfield" value="0" size="20" maxlength="9" style="text-align:right; ">
		
		
		
		  <tr>
          <td height="20" align="right" class="txtbold">Vat Amount:&nbsp;</td>
          <td height="20" align="left" class="padl5"><input name="txtVATAmt" id="txtVAmt" type="text"  readonly="yes" class="txtfield" value="" size="20" maxlength="9" style="text-align:right; "></td>
        </tr>
        <tr>
          <td height="20" align="right" class="txtbold">OTHER CHARGES :&nbsp;</td>
          <td height="20" align="left" class="padl5"><input name="txtOCharges" id="txtOCharges" type="text"  class="txtfield" value="" size="20" maxlength="9" style="text-align:right; "></td>
        </tr>
        <tr>
          <td height="20" align="right" class="txtbold">NET AMOUNT :&nbsp;</td>
          <td height="20" align="left" class="padl5"><input name="txtNetAmt" type="text"  class="txtfield txtbold" readonly="yes" value="" size="20" style="text-align:right;"></td>
        </tr>
    </table></td>
  </tr>
</table></td>
    </tr>
    <tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="7" height="20"><div align="center">
					<input name="btnSave" type="submit" class="btn" value="Save" onclick="return ConfirmSave();">
					<input name="btnCancel" type="submit" class="btn" value="Cancel" onclick="return ConfirmCancel();">
				</div></td>
			</tr>
			</table>
	</td>
</tr>
</table>
</td>
  </tr>
</table>