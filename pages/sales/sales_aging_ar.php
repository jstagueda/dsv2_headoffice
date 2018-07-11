

<link rel="stylesheet" type="text/css" href="../../css/ems.css"/>
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
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="tabmin">&nbsp;</td>
    <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="txtredbold">Aging of Accounts Receivable</td>
          <td><table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
              <tr>
                <td>&nbsp;</td>
                <td width="15">&nbsp;</td>
                <td width="12">&nbsp;</td>
              </tr>
          </table></td>
        </tr>
    </table></td>
    <td class="tabmin3">&nbsp;</td>
  </tr>
</table>


<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl1">
  <tr>
    <td valign="top" class="bgF9F8F7">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" class="bgF9F8F7" width="1500">
	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">
	<tr class="bgEFF0EB">
		<td>
		<table align="center" cellpadding="0" cellspacing="0" >
			<tr>
				<td><strong>Reference Date : &nbsp;</strong></td>
				<td height="20">
				<input readonly="yes" name="txtFromDate" id="txtFromDate" type="text" class="txtfield" size="30">
				<input type="button" class="buttonCalendar" name="anchorFromDate" id="anchorFromDate" value=" ">
				<div id="divFromDate" style="background-color : white; position:absolute;visibility:hidden;"></div>	
				</td>
			<tr>
			<tr>
				<td align="left"><strong>Bucket: &nbsp; </strong></td>
				<td>
				<select name="lstbucket" class="txtfield" style="width:120px">
			
						<option value="">Name</option>
				</select>
				</td>
			</tr>
			<tr>
				<td align="left"><strong>Salesman: &nbsp; </strong> </td>
				<td>
				<select name="lstEmployee" class="txtfield" style="width:120px">
				<option value="0">[ALL]</option>
				
						<option value="">Name</option>
					
				</select>
				 
				</td>
			</tr>
			<tr>
				<td align="left"><strong>Coverage Area: &nbsp; </strong> </td>
				<td>
				<select name="lstTerritory" class="txtfield" style="width:120px">
					<option value="">Name</option>
				</select>
				</td>
			</tr>	
			<tr>
				<td align="left"><strong>Include Zero Balance: &nbsp; </strong> </td>
				<td>
					<input name="rdZeroIncluded" type="radio" class="btnnone" value="0" >
					No &nbsp;&nbsp;
					<input name="rdZeroIncluded" type="radio" class="btnnone" value="1">
					Yes					
					
				</td>		
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="btnGo" class="btn" value="GO" />		&nbsp;</td>
			</tr>

		</table>

	</tr>
	
	<tr class="bgEFF0EB">
		<td height="20" align="center">&nbsp;
		
        </td>
	</tr>
	<tr class="bgEFF0EB">
		<td height="20" align="center">&nbsp;
		</td>
	</tr>
	</table>
	<br>
	<br>
	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
		<tr class="tab">
			<td width="15%" height="20" align="left" class="padl5">customer Name</td>
			<!-- depends on the bucket -->
            <td width="10%" height="20" align="right" class="padr5">bucket</td>
	
		
		</tr>
		</table>
		<div class="scroll_300">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		
				<tr>
					<td width="25%" height="20" align="center" class="txtredsbold">NO RECORD(S) TO DISPLAY.</td>
				</tr>
		</table>
		</div>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr class="bgE6E8D9">
			<td width="15%" height="20" align="right" class="txtbold padr5">Total</td>
		
				<td width="10%" height="20" align="right" class="txtbold padr5">&nbsp;</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
  </tr>
  <tr>
    <td valign="top" class="bgF9F8F7">&nbsp;</td>
  </tr>
</table>
<Br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" class="bgE6E8D9"><input name="btnPrint" type="button" class="btn" value="Print" ></td>
  </tr>
</table>
<br>
<br>
