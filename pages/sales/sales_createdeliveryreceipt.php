<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    <tr>
		      <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="#">Sales Cycle Main</a></td>
		    </tr>
		</table>
		</td>
	</tr>
</table>
      <br>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Create Delivery Receipt </td>
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
            <table width="100%"  border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td width="25%" height="20" align="right" class="txt10">Reference No. :</td>
                <td width="5%" class="txt10">&nbsp;</td>
                <td width="70%">&nbsp;</td>
              </tr>
              <tr>
                <td width="25%" height="20" align="right" class="txt10">SO Date :</td>
                <td width="5%" class="txt10">&nbsp;</td>
                <td width="70%">&nbsp;</td>
              </tr>
              <tr>
                <td width="25%" height="20" align="right" class="txt10">Customer Code :</td>
                <td width="5%" class="txt10">&nbsp;</td>
                <td width="70%">&nbsp;</td>
              </tr>
              <tr>
                <td width="25%" height="20" align="right" class="txt10">Customer Name :</td>
                <td width="5%" class="txt10">&nbsp;</td>
                <td width="70%">&nbsp;</td>
              </tr>
              <tr>
                <td width="25%" height="20" class="txt10">&nbsp;</td>
                <td width="5%" class="txt10">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
          </table></td>
          <td valign="top"><table width="100%"  border="0" cellspacing="1" cellpadding="0">
              <tr>
                  <td width="25%" height="20" align="right" class="txt10">Warehouse :</td>
                  <td width="5%" class="txt10">&nbsp;</td>
                  <td width="70%"><select name="lstWarehouse" style="width:170px" class="txtfield">
                          
                              <option value="">warehouse</option>
                         
                  </select></td>
              </tr>
              <tr>
                <td width="25%" height="20" align="right" class="txt10">Document No. :</td>
                <td width="5%" class="txt10">&nbsp;</td>
                <td width="70%"><input name="txtDocNo" type="text" class="txtfield" id="txtDocNo" size="30" maxlength="20" ></td>
              </tr>
              <tr>
                  <td width="25%" height="20" align="right" class="txt10">DR Date :</td>
                  <td width="5%" class="txt10">&nbsp;</td>
                  <td width="70%"><input name="txtTxnDate" type="text" class="txtfield" id="txtTxnDate" size="30">
				<input type="button" class="buttonCalendar" name="anchorTxnDate" id="anchorTxnDate" value=" ">
				<div id="divTxnDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>				  </td>
              </tr>
              <tr>
                  <td width="25%" height="20" align="right" class="txt10">Delivery Date :</td>
                  <td width="5%" class="txt10">&nbsp;</td>
                  <td width="70%"><input name="txtDeliveryDate" type="text" class="txtfield" id="txtDeliveryDate" size="30">
				<input type="button" class="buttonCalendar" name="anchorDeliveryDate" id="anchorDeliveryDate" value=" ">
				<div id="divDeliveryDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>				  </td>
              </tr>
              <tr>
                <td width="25%" height="20" align="right" valign="top" class="txt10">Remarks :</td>
                <td width="5%" valign="top" class="txt10">&nbsp;</td>
                <td width="70%"><textarea name="txtRemarks" cols="30" rows="5" class="txtfieldnh" id="txtRemarks"></textarea></td>
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
<!--- details table --->
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="tabmin">&nbsp;</td>
    <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="txtredbold">Product List </td>
        </tr>
    </table></td>
    <td class="tabmin3">&nbsp;</td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
  <tr>
    <td valign="top" width="100%" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
              <tr align="center">
				<td width="5%"><input name="chkAll" type="checkbox" class="btnnone" id="chkAll" value="1" checked></td>			
                <td width="30%">Product</td>
                <td width="10%">Unit</td>
                <td width="10%">Ordered Qty </td>
                <td width="10%">Multiplier</td>
                <td width="10%">Ordered Qty (PCS)</td>
				<td width="10%">Available SOH</td>
                <td width="15%">Delivered Qty</td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td class="bordergreen_B">
		  <div class="scroll_300">
		  <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">	  									  
              <tr align="center">
                <td width="5%" height="20" class="borderBR"><input type="checkbox" name="chk" value="#ctr#" checked></td>					  
                <td width="30%" height="20" class="borderBR">&nbsp;</td>
                <td width="10%" class="borderBR">&nbsp;</td>
                <td width="10%" class="borderBR">&nbsp;</td>
                <td width="10%" class="borderBR">&nbsp;</td>
                <td width="10%" class="borderBR">&nbsp;</td>
                <td width="10%" class="borderBR">&nbsp;</td>
                <td width="15%" class="borderB"><input name="txtDelQty#ctr#" type="text" class="txtfield3" size="10"></td>
              </tr>
			  <tr align="center">
				<td height="20" colspan="8" class="borderBR"><span class="style1">There are no products for this Delivery Receipt.</span></td>
			</tr>	
			</table>
		  </div></td>
        </tr>
    </table></td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="3" class="bgE6E8D9"></td>
  </tr>
</table>
<!--- end details table --->

<!-- add to form button -->
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td align="left">&nbsp;</td>
    <td align="right"><input name="btnAdd" type="submit" class="btn" value=" Add "></td>
  </tr>
</table>
<!-- end add to form button -->
<Br />
<!--- transaction form --->
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="tabmin">&nbsp;</td>
    <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="txtredbold">Delivery Receipt Details </td>
        </tr>
    </table></td>
    <td class="tabmin3">&nbsp;</td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl3">
  <tr>
    <td valign="top" width="100%" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
			  <td class="tab bordergreen_T">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		        	<tr align="center">
						<td width="4%"><input name="chkAllRemove" type="checkbox" id="chkAllRemove" value="1"></td>
		            	<td width="6%">Line No.</td>		        	
		                <td width="30%">product</td>
		                <td width="10%">Unit</td>
		                <td width="10%">Ordered Qty </td>
		                <td width="10%">Multiplier</td>
		                <td width="10%">Ordered Qty (PCS)</td>
		                <td width="10%">Available SOH</td>
		            	<td width="10%">Delivered Qty </td>
		        	</tr>
				</table>				
				</td>
			</tr>
			<tr>
			  <td class="bordergreen_B">
				<div class="scroll_300">
				  <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
						<tr align="center" #rowcolor#>
							<td width="4%" height="20" class="borderBR"><input type="checkbox" name="chkRemove" ></td>
							<td width="6%" class="borderBR">&nbsp;</td>
							<td width="30%" height="20" class="borderBR">&nbsp;</td>
							<td width="10%" class="borderBR">&nbsp;</td>
							<td width="10%" class="borderBR">&nbsp;</td>
							<td width="10%" class="borderBR">&nbsp;</td>
							<td width="10%" class="borderBR">&nbsp;</td>
							<td width="10%" class="borderBR">&nbsp;</td>
							<td width="10%" class="borderB"><input name="" type="text" class="txtfield3" size="10"></td>
						</tr>				
					</table>
				</div>
			  </td>
			</tr>
    </table></td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="3" class="bgE6E8D9"></td>
  </tr>
</table>
<!--- end transaction form --->
      <br>
  	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td align="left">&nbsp;</td>
    <td align="center">
			<input name="Save" type="submit" class="btn" value="Save">
			<input name="Remove" type="submit" class="btn" value="Remove">

        <input name="Submit3" type="submit" class="btn" value="Cancel"></td>
  </tr>
</table>
</td>
  </tr>
</table>