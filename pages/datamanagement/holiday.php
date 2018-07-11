<link rel= "stylesheet" type= "text/css" href= "css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "css/calpopup.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language = "javascript" src = "js/holiday.js" type = "text/javascript"></script>
<form method="post" action="pages/sfm/welcomeleterprint.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
		<?PHP include("nav.php"); ?> <br>
		</td>
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
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Special Holiday</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		<!--Begin form-->
		<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td>&nbsp;</td><td>&nbsp;</td>
			<td>
			<table width = "80%">
			<tr>
			<td>
				<table width="75%" border="0" align="left" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin"></td> 
								<td class="tabmin2">
									<div align="left" class="padl5 txtredbold">Special Holiday Details
									</div></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
				</table>
				<table width="75%%" style="border-top:none;" border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td>&nbsp;</td>
					<td>
						
						<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1">
						<tr>
							
							<td width="" align="center">&nbsp;</td>
							<td width="" align="center">&nbsp;</td>
							<td width="" align="center">&nbsp;</td>
						</tr>
						<tr>
							
							<td width="" align="right" class="padr5"><strong>Holiday</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
							<input type = "text" class = "txtfield" id = "holiday">
							<input type = "hidden" id = "HolidayID">
							</td>
						</tr>
						<tr>
							
							<td width="" align="right" class="padr5"><strong>Date From</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left"><input type = "text" class = "txtfield" id = "from_date"></td>
				
						</tr>
						<tr>
							
							<td width="" align="right" class="padr5"><strong>Date To</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left"><input type = "text" class = "txtfield" id = "to_date"></td>
							<td width=""></td>
						</tr>
						<tr>
							
							<td width="" align="right" class="padr5"><strong>Branch Close</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
							<select id = "branch_close" class = "txtfield" name = "branch_close">
								<option value = "1">YES</option>
								<option value = "2">NO</option>
							</select>
							</td>
						</tr>
						<tr>
							<td width="" align="left" class="padr5">&nbsp;</td>
                                                        <td align="center" width="5%"></td>
							<td width="" align="left" class="padr5">
								<input id= "Save" type = "submit" value = "Save" class ="btn" onclick = "return SaveClick(1);">
								<input id= "Update" type = "submit" value = "Save" class ="btn" onclick = "return SaveClick(2);">
								<input id= "Cancel" type = "submit" value = "Cancel" class ="btn" onclick = "return SaveClick(3);">
							</td>
						</tr>
						<tr>
							<td width="" align="center" class="padr5">&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
			</tr>
			<tr></tr><tr></tr>
			<tr>
			<td>
			
				<table width="75%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
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
				</tr>
				</table>
			</td>
			</td>
			</tr>
			</table>
			<!-- begin search here-->
			
			<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
			<tr><td>
			
			<!-- end search here -->
			<tr>
				<td>&nbsp;</td>
			</tr>
					
			</tr></td>
			<tr><td>
			<table width="95%" border="0" align="left" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td> 
					<td class="tabmin2">
						<div align="left" class="padl5 txtredbold">Special Holiday List
						</div></td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="95%" border="0" align="left" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
				<tr>
					<td valign="top" class="bgF9F8F7">
					</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">
						<div class="scroll_300">
							<table width="100%"   border="0" cellpadding="0" cellspacing="0" id = "call_ajax">
								
									<tr align="center">
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
		</td>
	</tr>
	<tr>
		<table>
			<tr>
				<td>
					<div id = "pagination">
						<!--Pagination Here-->
					</div>
				</td>
			</tr>
		</table>
	</tr>	
		</table>	
	</table>
	
</form>
</table>
