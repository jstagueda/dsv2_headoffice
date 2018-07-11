<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxCreateSalesOrder.js"  type="text/javascript"></script>
<style type="text/css">
<!--
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}
-->
</style>

<?php
	include IN_PATH.DS."scCreateProvisionalReceipt.php";
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>

<form name="frmCreateSalesOrder" method="post" action="index.php?pageid=46&custID=<?php echo $_GET["custID"];?> ">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				    <tr>
				      <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
				    </tr>
				</table>
				</td>
			</tr>
		</table>
		<br>
      	<input type="hidden" name="hCustomerID" id="hCustomerID" value="<?php echo $custID; ?>">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		  <tr>
		    <td class="txtgreenbold13">Create Provisional Receipt </td>
		    <td>&nbsp;</td>
		  </tr>
		</table>
		<br />
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td><span class="txtblueboldlink"><?php echo $_GET['msg'] ; ?></span></td>
		</tr>

		</table>
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		<td valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="3">
      			<tr>
        			<td width="27%" valign="top">
        				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
                      		<td>
                      			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 ">
                          		<tr class="bordergreen_B">
                            		<td align="left">Search&nbsp;&nbsp;</td>
                            		<td><div align="left">
                                		<input name="txtSearch" id="txtSearch" type="text" class="txtfield" size="20" />
                              			&nbsp;
                              			<input name="btnSearch" type="submit" class="btn" value="Go" />
                            		</div></td>
                          		</tr>
                        		</table>
                          		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bordergreen_T bordergreen_B">
                        		<tr align="center">
                              		<td height="15" width="30%"><div align="center"><span class="txtredbold">Code</span></div></td>
                          			<td height="15" width="70%"><div align="center"><span class="txtredbold">Name</span></div></td>
                        		</tr>
                        		</table>
                    		</td>
                		</tr>
                    	<tr>
                      		<td class="bordergreen_B">
                      			<div class="scroll_150">
                          		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
								<?php
									if($rs_customer->num_rows)
									{
										$rowalt=0;
										while($row = $rs_customer->fetch_object())
										{
											$rowalt++;
											($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
											echo "<tr align='center' class='$class'>
							  					<td height='20' class='borderBR' width='30%' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
							  					<td class='borderBR' width='70%' align='left'>&nbsp;<span class='txt10'>
												<a href='index.php?pageid=46&custID=$row->ID' class='txtnavgreenlink'>$row->Name</a></td></tr>";
										}
										$rs_customer->close();
									}
									else
									{
										echo "<tr align='center'>
                        				<td height='20' class='borderBR' colspan='2'><span class='txt10 style1'><strong>No record(s) to display. </strong>" .
                        						"</span></td></tr>";
									} 
								?>
                          		</table>
                      			</div>
                          		<!---<table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>
									<td height="20" class="txtblueboldlink" width="100%"><div align="left">&nbsp;<cfoutput><cfif listing.number_of_rows>Page #listing.display_links(10, getAllUrlVars(Url, "page"))#<br></cfif></cfoutput></div></td>
								</tr>
								</table>-->
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
          		<table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center">
              	<tr>
					<td width="50%" height="20" align="left" class="txtblueboldlink">&nbsp;</td>
              	</tr>
          		</table>
      		</td>
        	<td width="73%" valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="tabmin">&nbsp;</td>
              <td class="tabmin2">
			  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td class="txtredbold">General Information </td>
                    <td>&nbsp;
					<!--- <table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
              			<tr>
                			<td>&nbsp;</td>
                			<td width="15"><a href="#" onclick='unhideit("tbl1"); return false;' ><img src="../../../images/max.gif" width="11" height="11" class="btnnone"></a></td>
                			<td width="12"><a href="#" onclick='hideit("tbl1"); return false;' ><img src="../../../images/min.gif" width="11" height="11" class="btnnone"></a></td>
              			</tr>
          				</table> -->
      				</td>
				</tr>
              	</table>
          	</td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
		<tr>
			<td valign="top" class="bgF9F8F7"><div class="scroll_350">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="50%" valign="top">
						<table width="100%"  border="0" cellspacing="1" cellpadding="0">
						<tr>
							<td colspan="3" height="15">&nbsp;</td>
						</tr>
						<tr>
				  			<td width="35%" height="20" align="right" class="txt10">Customer : </td>
				  			<td width="5%" height="20" class="txt10">&nbsp;</td>
				  			<td width="60%" height="20"><?php if ($custID != 0) { ?>
				  				<input name="txtCustomer" type="text" class="txtfield" id="txtCustomer" size="35" readonly="yes" style="font:bold:" value="<?php echo $custname; ?>" disabled="yes" />
			  				<?php } else { ?>
			  					<strong><em><font color="red">[SELECT CUSTOMER]</font></em></strong>
		  					<?php } ?>
			  				</td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Reference No.  :</td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtRefNo" type="text" class="txtfield" id="txtRefNo" size="25" maxlength="15" tabindex="3" disabled="yes" readonly="yes" value="00000001"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Document No.  :</td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtRefNo" type="text" class="txtfield" id="txtRefNo" size="25" maxlength="15" tabindex="3"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Memo Type  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20">
				  				<select name="cboMemoType" style="width:130px" class="txtfield">
			                      <option value="0">[SELECT ONE]</option>
			                      <option value="1">DEBIT</option>
			                      <option value="2">CREDIT</option>
			                    </select>
				  			</td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Transaction Date  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20">
					  			<input name="txtTxnDate" type="text" class="txtfield" id="txtTxnDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>">
				                <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'txtTxnDate', 'mm/dd/yyyy')" title="Show popup calendar">
				                <img src="images/btn_Calendar.gif" width="25" height="19" border="0" align="absmiddle" /></a>
				  			</td>
						</tr>						
                    	</table>
            		</td>
                    <td valign="top" width="50%">
                    	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
                    	<tr>
							<td colspan="3" height="15">&nbsp;</td>
						</tr>
						<tr>
				  			<td height="20" align="right" valign="top" class="txt10">Memo Account  :</td>
				  			<td height="20" valign="top" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtRefNo" type="text" class="txtfield" id="txtRefNo" size="25" maxlength="15" tabindex="3" style="text-align:right" value="0.00"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" valign="top" class="txt10">Particulars  :</td>
				  			<td height="20" valign="top" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtRefNo" type="text" class="txtfield" id="txtRefNo" size="25" maxlength="15" tabindex="3"/></td>
						</tr>
						<tr>
							<td height="20" align="right" valign="top" class="txt10">Remarks : </td>
							<td height="20" valign="top" class="txt10">&nbsp;</td>
							<td height="20"><textarea name="txtRemarks" cols="42" rows="3" class="txtfieldnh" id="txtRemarks" wrap="off" tabindex="10" ></textarea></td>
						</tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
					<td colspan="3">&nbsp;</td>
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
<a name="AnchorHere"></a>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
<tr>   	
	<!--- ###################################################################################################  -->
	<td width="73%" valign="top">&nbsp;
		<!---  right table -->
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td class="txtredbold">Select Sales Invoice(s) </td>
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
		<!---  right div-->
		<div id="tbl22" style="display:block"> 
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">	
		<tr>
          <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
			  <tr align="center">
				<td width="5%">&nbsp;&nbsp;
						<input name="chkAll" type="checkbox" id="chkAll" value="1">
				</td>
				<td width="15%">Invoice No. </td>
				<td width="15%">Document No.</td>
				<td width="15%">Transaction Date</td>
				<td width="15%">Invoice Amount</td>
				<td width="15%">Amount Due</td>
				<td width="15%">Payment</td>
			  </tr>
		  </table></td>
        </tr>
		<tr>
          <td class="bordergreen_B">
		  <div class="scroll_300">
		  <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
			<tr align="center">
				<td width="5%" height="20">&nbsp;
					<input type="checkbox" name="chkInclude" value="">
				</td>
				<td width="15%" height="20">SI00000011</td>
				<td width="15%" height="20">00004</td>
				<td width="15%" height="20">Jul 14, 2010</td>
				<td width="15%" height="20">4,678.86</td>
				<td width="15%" height="20">4,678.86</td>
				<td width="15%" height="20"><input name="txtPayAmt" type="text" class="txtfield" size="12" maxlength="12" style="text-align:right" value="" ></td>
			</tr>
 			<tr align="center">
				<td width="5%" height="20">&nbsp;
					<input type="checkbox" name="chkInclude" value="">
				</td>
				<td width="15%" height="20">SI00000006</td>
				<td width="15%" height="20">0003</td>
				<td width="15%" height="20">Jul 12, 2010</td>
				<td width="15%" height="20">41.47</td>
				<td width="15%" height="20">41.47</td>
				<td width="15%" height="20"><input name="txtPayAmt" type="text" class="txtfield" size="12" maxlength="12" style="text-align:right" value="" ></td>
			</tr>
          </table>
		  </div></td>
        </tr>	
		<tr class="bgF9F8F7">
			<td height="15">&nbsp;</td>
		</tr>
		<tr>
			<td class="bgF9F8F7 tab">
				<table width="100%" border="0" cellpadding="0" cellspacing="1">
				<tr>
					<td colspan="3" height="20">&nbsp;</td>
				</tr>
				<tr>
			  	    <td width="80%" height="25" align="right" class="borderBR txt10"><div align="right"><strong>Total Payment :</strong>&nbsp;</div></td>
					<td width="15%" height="25" class="borderBR"><div align="center"><input name="txtGrossAmt" type="text" class="txtfield3" size="10" readonly="yes" style="text-align:right" value="300.00" /></div></td>
					<td width="5%" height="25" class="borderBR">&nbsp;</td>
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
		</div>
		<!--- end right div  -->
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		  <tr>
		    <td align="center">
				<input name="btnSave" type="submit" class="btn" value="Save"> 
				<input name="Remove" type="submit" class="btn" id="Remove" value="Remove">
		        <input name="Reset" type="submit" class="btn" id="Reset" value="Reset">
			</td>
		  </tr>
		</table>
		<!--- end  right table -->
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