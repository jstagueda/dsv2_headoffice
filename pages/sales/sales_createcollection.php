<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>

<?php
	include IN_PATH.DS."scCreateCollection.php";
?>

<form name="frmCreateCollection" method="post" action="index.php?pageid=46">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
				</tr>
                </table>
			</td>
		</tr>
        </table>
		<br>
      	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtgreenbold13">Create Provisional Receipt</td>
            <td>&nbsp;</td>
		</tr>
		</table>
		<br />
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
                			<td valign="top" class="bgF9F8F7">
                				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    			<tr>
                      				<td>
                      					<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 ">
                          				<!--<tr>
                            				<td align="left">Quick Add &nbsp;&nbsp;</td>
                            				<td><div align="left">                              
							  					<input name="txtQAddCust" type="text" class="txtfield" id="txtQAddCust"  style="width:80px;" value="" size="15" tabindex="1" />
                              					&nbsp;
                              					<input name="btnQAddCust" type="submit" class="btn" value="Go" tabindex="2" />
                            				</div></td>
                          				</tr>-->
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
														<a href='index.php?pageid=47&custID=$row->ID' class='txtnavgreenlink'>$row->Name</a></td></tr>";
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
      				</td>
        			<td width="73%" valign="top">
        				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        				<tr>
              				<td class="tabmin">&nbsp;</td>
              				<td class="tabmin2">
              					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                  				<tr>
                    				<td class="txtredbold">General Information </td>
                    				<td>
                        				<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
              							<tr>
                							<td>&nbsp;</td>
                							<td width="15"><a href="#" onclick='unhideit("tbl1"); return false;' ><img src="../../images/max.gif" width="11" height="11" class="btnnone"></a></td>
                							<td width="12"><a href="#" onclick='hideit("tbl1"); return false;' ><img src="../../images/min.gif" width="11" height="11" class="btnnone"></a></td>
              							</tr>
          								</table>
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
                        				<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        								<tr>
											<td width="30%" height="20" class="txt10">Customer  Code</td>
										  	<td width="70%" height="20"><input type="text" name="txtCustCode" size="50" class="txtfield" disabled="yes" value="<?php echo $custcode; ?>"></td>
										</tr>
										<tr>
										  	<td height="20" class="txt10">Customer Name </td>
										  	<td height="20"><input type="text" name="txtCustName" size="50" class="txtfield" disabled="yes" value="<?php echo $custname; ?>"></td>
										</tr>
										<tr>
										  	<td height="20" class="txt10">PR No.</td>
										  	<td height="20"><input name="txtORNo" type="text" class="txtfield" size="50" maxlength="30" disabled="yes" value="OR00000001"></td><!--<?php echo $orno; ?>-->
										</tr>
										<tr>
										  	<td height="20" class="txt10">Document No.</td>
										  	<td height="20"><input name="txtDocNo" type="text" class="txtfield" size="50" maxlength="30"></td>
										</tr>
										<!--<tr>
										  	<td height="20" class="txt10">Currency</td>
										  	<td height="20">
												<select name="cboCurrency" style="width:200px" class="txtfield">
													<option>Name</option>
												</select>
										  	</td>
										</tr>
										<tr>
										  	<td height="20" class="txt10">Currency Rate</td>
										  	<td height="20"><input type="text" name="txtRate" class="txtfield" value="1.00" size="10"></td>
										</tr>-->
										</table>
									</td>
                        			<td valign="top" width="50%">
                        				<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
									  	<tr>
											<td width="30%" height="20" class="txt10">PR Date </td>
											<td width="70%" height="20">
												<input name="txtTxnDate" type="text" class="txtfield" id="txtTxnDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>">
	               								<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'txtTxnDate', 'mm/dd/yyyy')" title="Show popup calendar"><img src="images/btn_Calendar.gif" width="25" height="19" border="0" align="absmiddle" /></a>
											</td>
										</tr>
										<tr>
											<td height="20" class="txt10" valign="top">Remarks</td>
											<td height="20"><textarea name="txtRemarks" cols="30" rows="5" class="txtfieldnh" id="textarea" wrap="off"></textarea></td>
										</tr>
										<tr>
											<td height="20" colspan="2">&nbsp;</td>
										</tr>
    									</table>
									</td>
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
          				<br />
						<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="38%" valign="top">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td class="tabmin">&nbsp;</td>
									<td class="tabmin2">
										<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
										<tr>
						  					<td class="txtredbold">Mode of Payment</td>
						  				</tr>
										</table>
									</td>
									<td class="tabmin3">&nbsp;</td>
					  			</tr>
								</table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bordergreen" id="tbl2">
            					<tr class="bgF9F8F7">
									<td>
										<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" height="100">
										<tr class="bgF9F8F7">
											<td width="500">&nbsp;</td>
											<td width="500">&nbsp;</td>
										</tr>
										<tr class="bgF9F8F7">
											<td height="20" class="txt10">Type </td>
						 					<td height="20" class="txt10">
												<select name="lstType" style="width:150px " class="txtfield">
													<option value="">Cash</option>
													<option value="">Check</option>
												</select>
											</td>
										</tr>
									  	<tr class="bgF9F8F7">
											<td height="20" class="txt10">Bank</td>
											<td><input name="txtBank" type="text" class="txtfield" id="txtBank" size="25" maxlength="25"></td>
										</tr>
										<tr class="bgF9F8F7">
											<td height="20" class="txt10">Branch</td>
											<td><input name="txtBranch" type="text" class="txtfield" id="txtBank" size="25" maxlength="25"></td>
										</tr>
										<tr class="bgF9F8F7">
											<td height="20" class="txt10">Check No.</td>
											<td><input name="txtCheckNo" type="text" class="txtfield" id="txtCheckNo" size="25" maxlength="25"></td>
										</tr>
										<tr class="bgF9F8F7">
											<td height="20" class="txt10">Check Date</td>
											<td>
												<input name="txtCheckDate" type="text" class="txtfield" id="txtCheckDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>">
	               								<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'txtCheckDate', 'mm/dd/yyyy')" title="Show popup calendar"><img src="images/btn_Calendar.gif" width="25" height="19" border="0" align="absmiddle" /></a>
											</td>
										</tr>
										<tr class="bgF9F8F7">
											<td height="20" class="txt10">Amount</td>
											<td><input name="txtAmount" type="text" class="txtfield" id="txtAmount" size="25" maxlength="25"></td>
										</tr>
										<tr class="bgF9F8F7">
											<td colspan="2"><div align="right"><input name="btnAdd" type="submit" class="btn" value=" Add "></div></td>
										</tr>
										<tr class="bgF9F8F7">
										  	<td>&nbsp;</td>
										  	<td>&nbsp;</td>
										</tr>
									  	</table>
									</td>
								</tr>
								</table>
							</td>
							<td width="2%" valign="top">&nbsp;</td>
							<td width="60%" valign="top">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							  	<tr>
									<td class="tabmin">&nbsp;</td>
									<td class="tabmin2">
										<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
										<tr>
							  				<td class="txtredbold">Selected Mode of Payment</td>
							  			</tr>
										</table>
									</td>
									<td class="tabmin3">&nbsp;</td>
						  		</tr>
								</table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bordergreen" id="tbl3">
								<tr class="bgF9F8F7">
									<td>
									<br>
										<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0" height="100">
										<tr>
								  			<td>
												<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
								  				<tr class="bgE6E8D9">
													<td width="38%" height="20" class="txtredbold">Payment Type</td>
													<td height="20" width="30%" class="txtredbold">Payment Details</td>
													<td height="20" width="30%" class="txtredbold"><div align="right">Amount to be Applied&nbsp;</div></td>
												</tr>
								  				<tr>
													<td height="20"><a href="" class="txtnavgreenlink">Cash</a></td>
													<td height="20">&nbsp;</td>
													<td height="20" align="right"><input type="text" name="Unapplied" value="250.00" style="text-align:right" class="txtfield" readonly="yes">&nbsp;</td>
												</tr>								  
								  				<tr>
													<td height="20"><a href="#" class="txtnavgreenlink">Check</a></td>
													<td height="20">CN00122AE</td>
													<td height="20" align="right"><input type="text" name="Unapplied2" value="500.00" style="text-align:right" class="txtfield" readonly="yes" />&nbsp;</td>
												</tr>
								  				</table>
											</td>
							  			</tr>
										<tr>
					  						<td height="20">&nbsp;</td>
					  					</tr>
										<tr>
											<td>
												<table width="97%"  border="0" align="center" cellpadding="0" cellspacing="0">
												<tr>
													<td width="38%" height="20" class="txtredbold">&nbsp;</td>
													<td width="62%" height="20" width="30%"><div align="right">
														<strong>Total Amount to be Applied</strong>&nbsp;
						    							<input name="txtTotAmt" type="text" class="txtfield" style="text-align:right" value="750.00" size="20" maxlength="20" readonly="yes">
													</div></td>
												</tr>
												</table>
											</td>
					  					</tr>
										<tr class="bgF9F8F7">
											<td height="20">&nbsp;</td>
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
  				<br>
				<!-- <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="95%" valign="top">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				  		<tr>
							<td class="tabmin">&nbsp;</td>
							<td class="tabmin2">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
								<tr>
				  					<td class="txtredbold">List of Sales Invoice(s)</td>
				  					<td>
				  						<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
				  						<tr>
											<td>&nbsp;</td>
											<td width="15"><a href="#" onclick='unhideit("tbl4"); return false;' ><img src="../../images/max.gif" width="11" height="11" class="btnnone"></a></td>
											<td width="12"><a href="#" onclick='hideit("tbl4"); return false;' ><img src="../../images/min.gif" width="11" height="11" class="btnnone"></a></td>
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
									<td width="10%" height="20"><div align="left" class="padl5"><input name="chkAll" type="checkbox" id="chkAll" value="1"></div></td>
									<td width="10%" height="20" class="txtredbold">SI Date</td>
									<td width="10%" height="20" class="txtredbold">SI No.</td>
									<td width="15%" height="20" class="txtredbold">Terms</td>
									<td width="15%" height="20" class="txtredbold" align="right">Outstanding Balance&nbsp;</td>
									<td width="20%" height="20" align="center" class="txtredbold">Mode of Payment</td>
									<td width="20%" height="20" align="right" class="txtredbold">Applied Amount&nbsp;</td>
								</tr>
								
								<tr>
								  <td height="20" align="left" class="padl5"><input type="checkbox" name="chkInclude2" value="" /></td>
								  <td height="20">04/15/2010</td>
								  <td height="20">00000015</td>
								  <td height="20">CASH</td>
								  <td height="20" align="right"><input name="AppliedAmt3" type="text" class="txtfield" style="text-align:right" value="325.50" readonly="yes"/></td>
								  <td height="20" align="center">CASH</td>
								  <td height="20" align="right"><input type="text" name="AppliedAmt" class="txtfield" style="text-align:right" value="250.00">&nbsp;</td>
						 		</tr>
								<tr class="bgEFF0EB">
									<td height="20" align="left" class="padl5"><input type="checkbox" name="chkInclude" value=""></td>
									<td height="20">07/23/2010</td>
									<td height="20">00000047</td>
									<td height="20">7 DAYS</td>
									<td height="20" align="right"><input name="AppliedAmt2" type="text" class="txtfield" style="text-align:right" value="1250.00" readonly="yes" /></td>
									<td height="20" align="center">CHECK</td>
									<td height="20" align="right"><input type="text" name="AppliedAmt" class="txtfield" style="text-align:right" value="500.00">&nbsp;</td>
								</tr>
								<tr>
						  			<td colspan="7" height="20">&nbsp;</td>
								</tr>
								<tr>
						  			<td colspan="7" height="20" align="right"><strong>Total Applied Amount</strong>&nbsp;&nbsp;<input type="text" name="TotalAppliedAmt" class="txtfield" style="text-align:right" readonly="yes" value="750.00">&nbsp;</td>
								</tr>
								<tr>
						  			<td colspan="7" height="20" align="right"><strong>Advance Payment</strong>&nbsp;&nbsp;<input name="AdvancePayment" type="text" class="txtfield" style="text-align:right" value="0.00" readonly="yes">&nbsp;</td>
								</tr>
														
								
								</table>
							</td>
						</tr>
						</table>
          				<br />
         				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            			<tr>
                			<td colspan="7" height="20" align="right"><input name="btnAddSI" type="submit" class="btn" value=" Add ">&nbsp;&nbsp;</td>
            			</tr>
            			</table>
         				<br />
         				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="95%" valign="top">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
	  							<tr>
									<td class="tabmin">&nbsp;</td>
									<td class="tabmin2">
										<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
										<tr>
			  								<td class="txtredbold">List of Selected Sales Invoice(s) Paid </td>
			  								<td>
			  									<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
              									<tr>
													<td>&nbsp;</td>
													<td width="15"><a href="#" onclick='unhideit("tbl5"); return false;' ><img src="../../images/max.gif" width="11" height="11" class="btnnone"></a></td>
													<td width="12"><a href="#" onclick='hideit("tbl5"); return false;' ><img src="../../images/min.gif" width="11" height="11" class="btnnone"></a></td>
											  	</tr>
											  	</table>
										  	</td>
										</tr>
										</table>
									</td>
									<td class="tabmin3">&nbsp;</td>
  								</tr>
								</table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bordergreen" id="tbl5">
								<tr class="bgF9F8F7">
									<td>
										<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
										<tr class="bgE6E8D9">
											<td width="15%" height="20" class="txtredbold"><div align="center">SI Date</div></td>
											<td width="15%" height="20" class="txtredbold"><div align="center">SI No.</div></td>
											<td width="15%" height="20" class="txtredbold"><div align="center">Terms</div></td>
											<td width="20%" height="20" align="right" class="txtredbold">Outstanding Balance&nbsp;</td>
											<td width="20%" height="20" align="center" class="txtredbold">Mode of Payment</td>
											<td width="15%" height="20" align="right" class="txtredbold padr5">Applied Amount&nbsp;</td>
										</tr>
										<tr>
											<td colspan="6"><div class="scroll_300">
												<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
												<tr>
													<td height="20"><div align="center">04/15/2010</div></td>
												  	<td height="20"><div align="center">00000015</div></td>
												  	<td height="20"><div align="center">CASH</div></td>
												  	<td height="20" align="right">325.50</td>
												  	<td height="20" align="center">CASH</td>
												  	<td height="20" align="right" class="padr5">250.00</td>
												</tr>
												<tr class="bgEFF0EB">
													<td width="15%" height="20"><div align="center">07/23/2010</div></td>
													<td width="15%" height="20"><div align="center">00000047</div></td>
													<td width="15%" height="20"><div align="center">7 DAYS</div></td>
													<td width="20%" height="20" align="right">1250.00</td>
													<td width="20%" height="20" align="center">CHECK</td>
													<td width="15%" height="20" align="right" class="padr5">500.00</td>
												</tr>
												</table>
											</div></td>
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
				<br /> -->
				<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td height="20" colspan="7" align="center">
						<input name="btnSave" type="submit" class="btn" value=" Save ">
        				<input name="btnSave" type="submit" class="btn" value=" Cancel ">
					</td>
				</tr>
				</table>
				<br>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>