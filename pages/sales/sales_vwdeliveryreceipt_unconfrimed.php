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
                  <td height="20" class="txt10">Warehouse</td>
                  <td><input name="txtWarehouse" type="text" class="txtfield" size="30" readonly="yes"></td>
              </tr>
              <tr>
                <td width="500" height="20" class="txt10">Delivery Date</td>
                <td width="500"><input name="txtDelDate" type="text" class="txtfield" size="30" readonly="yes"></td>
              </tr>
			   <tr>
			     <td height="20" class="txt10">Status</td>
			     <td>
			       <input name="txtStatus" type="text" class="txtfield" size="30" readonly="yes">
		          </td>
		        </tr>
			   <tr>
                <td height="20" valign="top" class="txt10">Remarks</td>
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
<!--- details table --->
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="tabmin">&nbsp;</td>
    <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="txtredbold">product List </td>
          <td><table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
              <tr>
                <td>&nbsp;</td>
                
              </tr>
          </table></td>
        </tr>
    </table></td>
    <td class="tabmin3">&nbsp;</td>
  </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
  <tr>
    <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
              <tr align="center">
				<td width="4%"><input name="chkAll" type="checkbox" class="btnnone" id="chkAll" value="1"></td>			
                <td width="25%">product</td>
                <td width="8%">Ticket No.</td>
                <td width="8%">Booklet No.</td>
                <td width="8%">Unit</td>
                <td width="8%">Ordered Qty </td>
                <td width="8%">Multiplier</td>
                <td width="8%">Ordered Qty (PCS)</td>
				<td width="8%">Available SOH</td>
                <td width="15%">Delivered Qty</td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td class="bordergreen_B">
		  <div class="scroll_300">
		  <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">										  
				  <tr align="center">
					<td width="4%" height="20" class="borderBR"><input type="checkbox" name="chk" value=""></td>					  
					<td width="25%" height="20" class="borderBR">&nbsp;</td>
					<td width="8%" class="borderBR">&nbsp;</td>
					<td width="8%" class="borderBR">&nbsp;</td>
					<td width="8%" class="borderBR">&nbsp;</td>
					<td width="8%" class="borderBR">&nbsp;</td>
					<td width="8%" class="borderBR">&nbsp;</td>
					<td width="8%" class="borderBR">&nbsp;</td>
					<td width="8%" class="borderBR">&nbsp;</td>
					<td width="15%" class="borderB"><input name="txtDelQty#ctr#" type="text" class="txtfield3" size="10" style="text-align:right "></td>
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
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td align="left">&nbsp;</td>
    <td align="right"><input name="btnAdd" type="submit" class="btn" value=" Add "></td>
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
                <td width="4%"><input name="chkAll2" type="checkbox" class="btnnone" id="chkAll2" value="1" /></td>
                <td width="8%">Line No.</td>
                <td width="20%">Product</td>
                <td width="7%">Ticket No.</td>
                <td width="7%">Booklet No.</td>
                <td width="6%">Unit</td>
                <td width="8%">Ordered Qty </td>
                <td width="6%">Multiplier</td>
                <td width="10%">Ordered Qty (PCS)</td>
				<td width="9%">Available SOH</td>
                <td width="15%">Delivered Qty</td>
              </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td valign="top" class="bgF9F8F7">
		  <div class="scroll_300">
		    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">	
                <tr align="center">
                  <td width="4%"><input name="chkAll3" type="checkbox" class="btnnone" id="chkAll3" value="1" /></td>
                    <td width="8%" height="20">RPR00032</td>
                    <td width="20%" height="20">1 PBC 2KG X 8 W PLSBRY CRISPE</td>
                    <td width="7%" height="20">2</td>
                    <td width="7%" height="20">52323</td>
                    <td width="6%">&nbsp;</td>
                    <td width="8%">&nbsp;</td>
                    <td width="6%">&nbsp;</td>
               	  <td width="10%">5</td>
                    <td width="9%">63.45</td>
                    <td width="15%"><input name="txtSQty#ctr2#" type="text" class="txtfield3" size="10"></td>				 
                </tr>
            </table>
		  </div>
          </td>
        </tr>
	</table>
    <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td align="left">&nbsp;</td>
        <td align="right"><input name="btnRemove" type="submit" class="btn" value=" Remove "></td>
      </tr>
    </table>
      <br>
  	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td align="center">
			<input name="btnConfirm" type="submit" class="btn" value="Confirm">
			<input name="btnDelete" type="submit" class="btn" value="Delete"> 
    		<input name="btnCancel" type="submit" class="btn" value="Cancel">
			<input name="btnPrint" type="submit" class="btn" value="Print">
		  </td>
	    </tr>
     </table></td>
  </tr>
</table>