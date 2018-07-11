<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/confirminvoicesho.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">


<form name="frmORRegister" method="post" action="index.php?pageid=99">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php
                include("bpm_left_nav.php");
            ?><br>
        </td>
        <td class="divider">&nbsp;</td>
	<td valign="top" style="min-height: 610px; display: block;">
  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
	    		<tr>
	      			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=203">Branch Performance Monitoring</a></td>
	    		</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">Confirmed Invoices</td>
    		<td>&nbsp;</td>
		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
	    	<td>
                    <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                                <td class="tabmin">&nbsp;</td>
                                <td class="tabmin2"><span class="txtredbold">Action</span></td>
                                <td class="tabmin3">&nbsp;</td>
                        </tr>
                    </table>
			  	<table width="99%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		        <tr>
		          	<td width="16%">&nbsp;</td>
		          	<td width="91%" align="right">&nbsp;</td>
		        </tr>
				<tr>
		          	<td height="20" class="padr5" align="right">Branches:</td>
					<td height="20">

                                            <input id = 'branches' name = 'branchesList' class = "txtfield">
                                            <input value="0" name = 'branches' type="hidden" class = "txtfield">

					</td>
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right">Dealer Account Range:</td>
					<td height="20">

							<input name='dealer_fromList' class="txtfield">
                                                        <input id='dealer_from' value="0" name='dealer_from' type="hidden" class="txtfield">
							-
                                                        <input name='dealer_toList' class="txtfield">
                                                        <input id='dealer_to' value="0" name='dealer_to' type="hidden" class="txtfield">

					</td>
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right">With Total per Date :</td>
					<td height="20">

							<select id = 'tperdate' name = 'tperdate' class = "txtfield">
								<option value = 0>SELECT HERE</option>
								<option value = 1>YES</option>
								<option value = 2>NO</option>
							</select>
					</td>
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right">With Total per Branch :</td>
					<td height="20">

							<select id = 'tperbranch' name = 'tperbranch' class = "txtfield">
								<option value = 0>SELECT HERE</option>
								<option value = 1>YES</option>
								<option value = 2>NO</option>
							</select>
					</td>
				</tr>
	    		<tr>
		          	<td height="20" class="padr5" align="right">From Date :</td>
		          	<td height="20">
		          		<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="">
	    			</td>
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right">To Date :</td>
		          	<td height="20">
		          		<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="">
						<input name="input" type="button" class="btn" value="Generate Report" onclick="openPopUp()"/>
					</td>
				</tr>
				<tr>
		          	<td colspan="2" height="20">&nbsp;</td>
		        </tr>
	    		</table>
			</td>
  		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center">
		<tr>
			<td height="20" align="center">
				<a class="txtnavgreenlink" href="index.php?pageid=18"><input name="Button" type="button" class="btn" value="Back"></a>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
</form>
