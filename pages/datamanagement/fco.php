<link rel= "stylesheet" type= "text/css" href= "css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "css/calpopup.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language = "javascript" src = "js/fco.js"></script>
<form>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6"><?php include("nav.php"); ?> <br /> </td>
		<td class="divider">&nbsp;</td>
		<td valign="top" style="min-height: 610px; display: block;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Parameters</span></td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				<table width="95%" border="0" align="center" cellpadding="0"
					cellspacing="1">
					<tr>
						<td class="txtgreenbold13">FCO</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		<table width="50%" border="0" align="left" cellspacing="1" cellpadding="0">
			<tr>
				<td>&nbsp;</td><td>&nbsp;</td>	<td>&nbsp;</td>	
				<td valign = "top">
				<!--Begin form-->
						<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin"></td> 
								<td class="tabmin2">
									<div align="left" class="padl5 txtredbold">FCO Details
									</div></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
						<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
						<tr>
						<td>
							<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1">
								<tr>
									
									<td width="" align="center">&nbsp;</td>
									<td width="" align="center">&nbsp;</td>
									<td width="" align="center">&nbsp;</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Year:</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<input type = "hidden" value = "" id = "FCO_ID" name = "FCO_ID">
										<select id = "Year" class = "txtfield" name = "Year">
											<?php
												
												for($i=date('Y'); $i < date('Y',strtotime(date("Y-m-d", mktime()) . " + 1825 day")); $i++) {
													echo '<option value="'.$i.'">'.$i.'</option>'."\n";
												}
												?>
										</select>
									</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Period</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<select id = "period" class = "txtfield" name = "period">
											<?php for($x = 1; 12 >= $x; $x++):?>
												<option value = "<?php echo $x; ?>"><?php echo $x; ?></option>
											<?php endfor;?>
										</select>
									</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>From Date</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
																<input type = "text" class = "txtfield" id = "from_date" name = "from_date">
																<i>(e.g. MM/DD/YYYY)</i>
															</td>
						
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>To Date</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
																<input type = "text" class = "txtfield" id = "to_date" name = "to_date">
																<i>(e.g. MM/DD/YYYY)</i>
															</td>
									<td width=""></td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Freeze for Compensation / Bonus Offsetting</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<input type = "checkbox" id = "isfreeze" name = "isfreeze" value = "1" />
										<select class = "txtfield" id = "campaign_month" name = 'campaign_month' style = 'width:20%;' disabled = "disabled">
										<?php for($x = 1; 12 >= $x; $x++):?>
											<option value = "<?php echo $x; ?>"><?php echo $x; ?></option>
										<?php endfor;?>
										</select>
										<input type = "text" class = "txtfield" id = "end_date" name = "end_date" style = 'width:43%' disabled = "disabled">
									</td>
									<td width=""></td>
								</tr>
								<tr>
									<td width="" align="center">&nbsp;</td>
									<td width="" align="center" class="padr5">&nbsp;</td>
									<td width="" align="left"><input type = "submit" id = "Save" value = "Save" class ="btn" onclick = "return SaveClick(1);">
									<input type = "submit" id = "Update" value = "Save" class ="btn" onclick = "return SaveClick(2);">
									<input type = "submit" id = "Cancel" value = "Cancel" class ="btn" onclick = "return SaveClick(3);"></td>
								</tr>
								<tr>
									<td width="" align="center" class="padr5">&nbsp;</td>
								</tr>
							</table>
							</td>
						</tr>
						</table>
					<tr>
						<td>&nbsp;</td>
					</tr>
				<!--End form-->
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td><td>&nbsp;</td>	<td>&nbsp;</td>	
				<td>
					
						<table width="100%%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
						<tr>
							<td>
								<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1">
				
								<tr>
									<td width="" align="center" class="padr5"><strong>Date From:</strong></td>
									<td width="" align="center"><input type = "text" class = "txtfield" id = "from_date_search"></td>
									<td width="" align="center" class="padr5"><strong>Date To:</strong></td>
									<td width="" align="center"><input type = "text" class = "txtfield" id = "to_date_search"></td>
									<td width="" align="center" class="padr5"><input id= "Search" type = "submit" value = "Search" class ="btn" onclick = "return Search_Item();"></td>
								</tr>
								</table>
							</td>
						</tr>
						</table>
				</td>
			</tr>
		</table>
		<table width="95%" border="0" align="left" cellspacing="1" cellpadding="0">
			<tr>
				<td></td>
				<td>
					<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td> 
							<td class="tabmin2">
								<div align="left" class="padl5 txtredbold">FCO List
								</div></td>
							<td class="tabmin3">&nbsp;</td>
						</tr>
					</table>
					<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
					<tr>
					<td>
						<tr>
							<td valign="top" class="bgF9F8F7">
							</td>
						</tr>
						<tr>
							<td class="bgF9F8F7">
								<div class="scroll_300">
									<table width="100%" id = "call_ajax"  border="0" cellpadding="0" cellspacing="0">
											<tr align="center" id = "">
														<td height="20" width = "100%" class="txtpallete borderBR" id ="fetching_data_please_wait"><div align="center" class="padl5">Fetching Data Please Wait</div></td>
											</tr>
									</table>
								</div>
							</td>
							</tr>
					</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				<table>
					<tr>
						<td>
							<div id = "pagination">
								<!--Pagination Here-->
							</div>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<div id = "validation">
</div>
</form>

