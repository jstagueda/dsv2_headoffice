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
    <td class="txtgreenbold13">View Delivery Receipt</td>
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
              	<td>&nbsp;</td>
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
				<td height="25" class="txt10">Dr No.</td>
				<td><input name="txtTxnNo" type="text" class="txtfield" value="" size="30" readonly="yes"></td>
			  </tr>
			  <tr>
                <td width="30%" height="25" class="txt10">Document No.</td>
                <td width="70%"><input name="txtDocNo" type="text" class="txtfield" size="30" maxlength="20"></td>
              </tr>
              <tr>
                <td width="30%" height="25" class="txt10">Dr Date</td>
                <td width="70%"><input name="txtDRDate" type="text" class="txtfield" value="" size="30" readonly="yes"></td>
              </tr>
			  <tr>
                <td width="30%" height="25" class="txt10">Reference No.</td>
                <td width="70%"><input name="txtRefNo" type="text" class="txtfield" value="" size="30" readonly="yes"></td>
              </tr>
			   <tr>
                <td width="30%" height="25" class="txt10">Customer Code</td>
                <td width="70%"><input name="txtCustCode" type="text" class="txtfield" value="" size="30" readonly="yes"></td>
              </tr>
			   <tr>
                <td width="30%" height="25" class="txt10">Customer Name</td>
                <td width="70%"><input name="txtCustName" type="text" class="txtfield" value="" size="50" readonly="yes">
                </td>
              </tr>
          </table>
			  </td><td valign="top">
			  <table width="100%"  border="0" cellspacing="1" cellpadding="0">
              <tr>
                  <td height="25" class="txt10">Warehouse</td>
                  <td><input name="txtWarehouse" type="text" class="txtfield" size="30" readonly="yes"></td>
              </tr>
              <tr>
                <td width="500" height="25" class="txt10">Delivery Date</td>
                <td width="500"><input name="txtDelDate" type="text" class="txtfield" size="30" readonly="yes"></td>
              </tr>
			   <tr>
			     <td height="25" class="txt10">Status</td>
			     <td>
			       <input name="txtStatus" type="text" class="txtfield" size="30" readonly="yes">
		          </td>
		        </tr>
			   <tr>
                <td height="25" valign="top" class="txt10">Remarks</td>
                <td><textarea name="txtRemarks" cols="30" rows="3" class="txtfieldnh" id="txtRemarks"></textarea></td>
              </tr>
          </table>
			 </td>
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
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">Delivery Receipt Details</td>
              </tr>
          </table></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
  </table>
     
     
     <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
        <tr>
          <td valign="top" class="tab" width="1020">
          	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
              <tr align="center">
                <td width="7%">Line No.</td>
                <td width="22%">Product</td>
                <td width="8%">Ticket No.</td>
                <td width="8%">Booklet No.</td>
                <td width="7%">Unit</td>
                <td width="9%">Ordered Qty </td>
                <td width="7%">Multiplier</td>
                <td width="11%">Ordered Qty (PCS)</td>
				<td width="10%">Available SOH</td>
                <td width="11%">Delivered Qty</td>
              </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td valign="top" class="bgF9F8F7">
		  <div class="scroll_300">
		    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">	
                <tr align="center">
                    <td width="7%" height="20">RPR00032</td>
                    <td width="22%" height="20">1 PBC 2KG X 8 W PLSBRY CRISPE</td>
                    <td width="8%" height="20">2</td>
                    <td width="8%" height="20">52323</td>
                    <td width="7%">&nbsp;</td>
                    <td width="9%">&nbsp;</td>
                    <td width="7%">&nbsp;</td>
               	  <td width="11%">5</td>
                    <td width="10%">63.45</td>
                    <td width="11%"><input name="txtSQty#ctr2#" type="text" class="txtfield3" size="10"></td>				 
                </tr>
            </table>
		  </div>
          </td>
        </tr>
	</table>
      <br>
  	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td align="center"><input name="input" type="button" class="btn" value="View GL Entry" />            <input name="" type="button" class="btn" value="Print" >
		  </td>
	    </tr>
  </table></td>
  </tr>
</table>