<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>
<script type="text/javascript" src="../../js/calpopup.js"></script>
<script type="text/javascript" src="../../js/dateparse.js"></script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="../main.cfm">Sales Main</a></td>
                    </tr>
                </table>
                </td>
            </tr>
        </table>
      <br>
      <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td class="txtgreenbold13">Create Customer Return</td>
            <td>&nbsp;</td>
          </tr>
        </table>
<br />
 		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
	<td valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="3">
      <tr>
        <td width="27%" valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="tabmin">&nbsp;</td>
              <td class="tabmin2"><span class="txtredbold">List of Customers </span></td>
              <td class="tabmin3">&nbsp;</td>
            </tr>
          </table>
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
              <tr>
                <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 ">
                          <tr class="bordergreen_B">
                            <td align="left">Search&nbsp;&nbsp;</td>
                            <td><div align="left">
                                <input name="" type="text" class="txtfield" size="20" />
                              &nbsp;
                              <input name="" type="submit" class="btn" value="Go" />
                            </div></td>
                          </tr>
                           <tr>
                            <td align="left">Salesman&nbsp;&nbsp;</td>
                            <td align="left"><select name="lstReason" style="width:150px" class="txtfield">
							<option>[SELECT ONE]</option>
								<option value="">#Name#</option>
						</select></td>
                          </tr>
                        </table>
                          <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bordergreen_T bordergreen_B">
                            <tr align="center">
                              <td height="15" width="30%"><div align="center"><span class="txtredbold">Code</span></div></td>
                              <td height="15" width="70%"><div align="center"><span class="txtredbold">Name</span></div></td>
                            </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td class="bordergreen_B"><div class="scroll_150">
                          <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                                <tr align="center">
                                  <td height="20" class="borderBR" width="30%" align="left">&nbsp;1010 MI</td>
                                  <td height="20" class="borderBR" width="70%" align="left">&nbsp;<span class="txt10"><a href="#" class="txtnavgreenlink" tabindex="3">1010 MINI MART STORE (TAYTAY)</a></span></td>
                                </tr>
                             <!-- <tr align="center">
                                <td height="20" class="borderBR" colspan="2"><span class="txt10 style1"><strong>No record(s) to display. </strong></span></td>
                              </tr>-->
                          </table>
                      </div>
                          <!---<table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
							<td height="20" class="txtblueboldlink" width="100%"><div align="left">&nbsp;<cfoutput><cfif listing.number_of_rows>Page #listing.display_links(10, getAllUrlVars(Url, "page"))#<br></cfif></cfoutput></div></td>
						</tr>
					</table>--->
                      </td>
                    </tr>
                </table></td>
              </tr>
            </table>
          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td height="3" class="bgE6E8D9"></td>
              </tr>
            </table>
         </td>
        <td width="73%" valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="tabmin">&nbsp;</td>
              <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                  <tr>
                    <td class="txtredbold">General Information </td>
                    <td>&nbsp;
                        <!--- <table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
              <tr>
                <td>&nbsp;</td>
                <td width="15"><a href="#" onclick='unhideit("tbl1"); return false;' ><img src="../../../images/max.gif" width="11" height="11" class="btnnone"></a></td>
                <td width="12"><a href="#" onclick='hideit("tbl1"); return false;' ><img src="../../../images/min.gif" width="11" height="11" class="btnnone"></a></td>
              </tr>
          </table> ---></td>
                  </tr>
              </table></td>
              <td class="tabmin3">&nbsp;</td>
            </tr>
          </table>
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
              <tr>
                <td valign="top" class="bgF9F8F7"><div class="scroll_350">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr>
                        <td width="50%" valign="top"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr valign="top">
          <td width="30%" height="25" class="txt10">customer Name </td>
          <td width="70%">
		  	<cfif url.CustomerID EQ 0>
		  	  <input type="text" name="txtCustName" size="40" class="txtfield" readonly="yes">
			</cfif>		  </td>
        </tr>
        <tr>
            <td height="25" class="txt10">inventory In No.</td>
            <td>
			<cfif Isdefined('rscrno.recordcount')>
				
				<cfif rsCRNo.CRNo EQ ''>
					<cfset iino = 'CR00000001'>
					<cfset iino = rsCRNo.CRNo>
				</cfif>
				<cfset IINO = "">
			</cfif>
				<input name="txtIINo" type="text" class="txtfield" id="txtORNo" size="20" maxlength="30" readonly="yes"/></td>
        </tr>
        <tr>
          <td width="30%" height="25" class="txt10">Document No.</td>
          <td width="70%"><input name="txtDocNo" type="text" class="txtfield" size="20" maxlength="20"></td>
        </tr>
	    <tr>
            <td height="25" class="txt10">Return Type</td>
            <td><select name="lstRetType" style="width:180px" class="txtfield">
              <option value="0">[SELECT ONE]</option>
                <option value="">Name</option>
            </select>
        </tr>
		<tr>
            <td height="25" class="txt10">reason</td>
            <td>
				<select name="lstReasons" style="width:180px" class="txtfield">
				<option value="0">[SELECT ONE]</option>
					<option value="">Name</option>
			  </select>        </tr>		
	    <tr>
            <td height="25" class="txt10">warehouse</td>
            <td>
				<select name="lstWarehouse" style="width:180px" class="txtfield">
				<option value="0">[SELECT ONE]</option>
					<option value="">Name</option>
				</select>			</td>
        </tr>
    </table></td>
                        <td valign="top" width="50%"><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td width="30%" height="25" class="txt10">Transaction Date </td>
        <td width="70%">
			<input name="txtIIDate" type="text" class="txtfield" id="txtIIdate" size="24" maxlength="30" readonly="yes">
			<input type="button" class="buttonCalendar" name="anchorTxnDate" id="anchorTxnDate" value=" ">
			<div id="divTxnDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
		</td>
      </tr>
      <tr>
        <td width="30%" height="20" class="txt10" valign="top">Remarks</td>
        <td width="70%"><textarea name="txtRemarks" cols="20" rows="6" class="txtfieldnh" id="textarea" wrap="off"></textarea></td>
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
                    </table>
                </div></td>
              </tr>
            </table>
          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td height="3" class="bgE6E8D9"></td>
              </tr>
            </table>
          <br /></td>
      </tr>
    </table>
    <br />
    <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="7" height="20"><div align="center">
					<input name="btnContinue" type="submit" class="btn" value="Continue" onclick="return ConfirmSave();" >
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