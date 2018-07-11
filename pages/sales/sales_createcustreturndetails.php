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
    <td class="txtgreenbold13">Create Customer Return </td>
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
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
  <tr>
    <td valign="top" class="bgF9F8F7"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td width="50%" valign="top">
            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
							<tr valign="top">
							  	<td width="30%" height="20" class="txt10">customer Name </td>
							 	<td width="70%">
									<input type="text" name="txtCustName" size="40" class="txtfield" readonly="yes">
							  	</td>
							</tr>
        					<tr>
								<td height="20" class="txt10">inventory In No.</td>
							  <td>
								  <input name="txtIINo" type="text" class="txtfield" id="txtORNo" size="20" maxlength="30" readonly="yes"/>
								</td>
        					</tr>
							<tr>
							  	<td width="30%" height="20" class="txt10">Document No.</td>
							  	<td width="70%"><input name="txtDocNo" type="text" class="txtfield" size="20" maxlength="20"></td>
							</tr>
							<tr>
								<td height="20" class="txt10">Return Type</td>
								<td>
									<select name="lstRetType" style="width:180px" class="txtfield">
										<option value="" disabled="disabled" >Name</option>
									</select>	
								</td>    
							</tr>
							<tr>
								<td height="20" class="txt10">reason</td>
								<td>
									<select name="lstReason" style="width:180px" class="txtfield">
										<option value="" disabled="disabled">Name</option>
								  	</select>
								</td>          
							</tr>		
							<tr>
								<td height="20" class="txt10">warehouse</td>
								<td>
									<select name="lstWarehouse" style="width:180px" class="txtfield">
										<option value="ID" disabled="disabled">Name</option>
									</select>			
								</td>
        					</tr>
    					</table></td>
         				 <td valign="top"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
							<tr>
								<td width="30%" height="20" class="txt10">Transaction Date </td>
								<td width="70%">
									<input name="txtIIDate" type="text" class="txtfield" id="txtIIdate" size="24" maxlength="30" readonly="yes">
								</td>
							</tr>
							<tr>
								<td width="30%" height="20" class="txt10" valign="top">Remarks</td>
								<td width="70%"><textarea name="txtRemarks" cols="20" rows="6" class="txtfieldnh" id="textarea" wrap="off" readonly="yes"></textarea></td>
							</tr>
							<tr>
								<td height="20" class="txt10">&nbsp;</td>
								<td>&nbsp;</td>
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
										  	<td class="txtredbold">List of Sales Invoice(s) </td>
									  	</tr>
									</table>
								</td>
								<td class="tabmin3">&nbsp;</td>
						  	</tr>
						</table>
						<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bordergreen" id="tbl4">
							<tr class="bgF9F8F7">
								<td>
									<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
										<tr class="bgE6E8D9">
											<td width="15%" height="20" align="center" class="txtredbold">SI No.</td>
									    <td width="15%" height="20" align="center" class="txtredbold">SI Date</td>
									    <td width="15%" height="20" align="left" class="txtredbold padl5">Document No.</td>
											<td width="15%" height="20" align="center" class="txtredbold">Terms</td>
											<td width="20%" height="20" align="left" class="txtredbold">Salesman</td>
											<td width="20%" height="20" align="right" class="txtredbold padr5">Total Net Amount</td>
										</tr>
										<tr>
											<td colspan="6"><div class="scroll_300">
												<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                    <tr >
                                                        <td width="15%" height="20" align="center"><a href="#" class="txtnavgreenlink">si00000161 </a></td>
                                                        <td width="15%" height="20" align="center">01/27/2010</td>
                                                        <td width="15%" height="20" align="left" class="padl5">4773</td>
                                                        <td width="15%" height="20" align="center">COD</td>
                                                        <td width="20%" height="20" align="left">MARTINEZ, JOHN CARLO</td>
                                                        <td width="20%" height="20" align="right" class="padr5">3,682.55</td>
                                                    </tr>
                                                    <tr class="bgEFF0EB">
                                                      <td height="20" align="center">&nbsp;</td>
                                                      <td height="20" align="center">&nbsp;</td>
                                                      <td height="20" align="left" class="padl5">&nbsp;</td>
                                                      <td height="20" align="center">&nbsp;</td>
                                                      <td height="20" align="left">&nbsp;</td>
                                                      <td height="20" align="right" class="padr5">&nbsp;</td>
													</tr>
																			
												</table>
										  </div></td>
										</tr>
										<tr>
											<td colspan="8" height="20">&nbsp;</td>
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
	<a name="ADetails"></a>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
	<td width="95%">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="95%" valign="top">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			  			<tr>
							<td class="tabmin">&nbsp;</td>
							<td class="tabmin2">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
									<tr>
										<td class="txtredbold">List of product(s)</td>
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
					<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bordergreen" id="tbl4">
						<tr class="bgF9F8F7">
							<td>
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
									<tr class="bgE6E8D9">
									<td width="23%" height="20" align="left" class="txtredbold padl5">Product Description</td>
								    <td width="10%" height="20" align="center" class="txtredbold">Delivered Qty</td>
								    <td width="7%" height="20" align="center" class="txtredbold">UOM</td>
								    <td width="9%" height="20" align="center" class="txtredbold">Returned Qty</td>
								    <td width="9%" height="20" align="right" class="txtredbold padr5">UnitPrice</td>
										<td width="9%" height="20" align="right" class="txtredbold padr5">Qty to be Returned</td>
										<td width="9%" height="20" align="center" class="txtredbold">UOM</td>
									  	<td width="11%" height="20" align="center" class="txtredbold padr5">Amount</td>
										<td width="13%" height="20" align="center" class="txtredbold">Reason</td>
									</tr>
									<tr>
										<td colspan="9"><div class="scroll_300">
										  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
										    <tr>
										      <td width="23%" height="20" align="left" class="padl5">NRT00034 - AMIHAN WHT VIN 1LX12</td>
										      <td width="10%" height="20" align="center">65</td>
										      <td width="7%" height="20" align="center">PIECE(S)</td>
										      <td width="9%"height="20" align="center">0</td>
										      <td width="9%" height="20" align="right" class="padr5"><input name="input" type="text" value="" class="txtfield5" readonly="yes" style="text-align:right" /></td>
										      <td width="9%" height="20" align="right" class="padr5"><input name="input" type="text" value="" size="10" maxlength="10" class="txtfield5" style="text-align:right" /></td>
										      <td width="9%" height="20" align="center"><select name="l" style="width:100px" class="txtfield" >
										        <option value="">Name</option>
										        </select></td>
										      <td width="11%" height="20" align="center"><input name="input" value="" class="txtfield2" style="text-align:right" readonly="yes" /></td>
										      <td width="13%" height="20" align="center"><input name="input" type="text" value="" size="10" maxlength="49" class="txtfield" /></td>
									        </tr>
										    
									      </table>
									  </div></td>
								</tr>
									<tr>
										<td colspan="8" height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">GROSS AMOUNT :</span></div></td>
										<td height="20" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtGrossAmt" value="0.00" class="txtfield2" style="text-align:right" readonly="yes" /></div></td>
									

									</tr>
									<tr>
										<td colspan="8" height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">Discount 1 :</span></div></td>
										<td height="20" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtDisc1" class="txtfield2" style="text-align:right" readonly="yes" width="10px" />%</div></td>
										
									</tr>
									<tr>
										<td colspan="8" height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">Discount 2 :</span></div></td>
										<td height="20" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtDisc2" class="txtfield2" style="text-align:right" readonly="yes" />%</div></td>
										
									</tr>
									<tr>
										<td colspan="8" height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">Discount 3 :</span></div></td>
										<td height="20" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtDisc3" class="txtfield2" style="text-align:right" readonly="yes" />%</div></td>
									
									</tr>
								<tr>
									<td colspan="8" height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">VAT AMOUNT :</span></div></td>
									<td height="20" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtVATAmt" value="0.00" class="txtfield2" style="text-align:right" readonly="yes" /></div></td>
									
								</tr>
								<tr>
									<td colspan="8" height="20" class="bgEFF0EB borderBR"><div align="right" class="padr5"><span class="txtbold">NET AMOUNT :</span></div></td>
									<td height="20" class="bgEFF0EB borderBR"><div align="left" class="padl5"><input name="txtNetAmt" value="0.00" class="txtfield2" style="text-align:right" readonly="yes" /></div></td>
							
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