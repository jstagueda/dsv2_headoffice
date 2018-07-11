<script type="text/javascript" src="js/TransferDealerToOtherBranch.js"></script>

<style>
.fieldlabel{
	text-align:right;
	font-weight:bold;
	width:40%;
}

.separator{
	text-align:center;
	font-weight:bold;
	width:30px;
}

.trheader td{
	padding : 5px;
	border-right : 1px solid #FFA3E0;
	background : #FFDEF0;
	text-align : center;
	font-weight : bold;
}

.trlist td{
	padding : 5px;
	border-top : 1px solid #FFA3E0;
	border-right : 1px solid #FFA3E0;
	
}

.dealerlist input{
	margin : 0;
}

</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
			<?PHP
				include("nav.php");
			?>      
			<br>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top" style="min-height: 610px; display: block;">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management</span></td>
				</tr>
			</table>
			<br />
			
			
			<div>
				<form action="" method="post" name="transferdealerform">
				<div style="width:98%; margin:auto;">
				
					<span class="txtgreenbold13">Transfer Dealer to Other Branch</span>
					
					<br />
					<br />
					
					
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2"><b>Transfer Dealer From</b></td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<div class="bordersolo" style="border-top:none;">
						
						<table border="0" cellpadding="1" cellspacing="1" width="100%">
							<tr>
								<td>&nbsp;</td>
							</tr>
							
							<tr>
								<td class="fieldlabel">Transfer from Branch</td>
								<td class="separator">:</td>
								<td>
									<input type="text" value="" name="frombranch" class="txtfield">
									<input type="hidden" value="0" name="frombranchHidden">
								</td>
							</tr>
							
							<tr>
								<td class="fieldlabel">Transfer from IBM</td>
								<td class="separator">:</td>
								<td>
									<input type="text" value="" name="fromIBM" class="txtfield" onkeypress="return IBMAutocomplete('[name=fromIBM]', '[name=fromIBMHidden]', 1);">
									<input type="hidden" value="0" name="fromIBMHidden">
									<input type="button" value="Search" class="btn" name="btnSearch">
								</td>
							</tr>
							
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
						
					</div>
					
					<br />
					
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2"><b>Dealer List</b></td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<div class="bordersolo dealerlist" style="border-top:none; max-height:300px; overflow:auto;">
						
						<table cellspacing="0" cellpadding="0" width="100%">
							
							<tr class="trheader">
								<td width="10px">
									<input type="checkbox" name="checkAll" value="1" style="margin:0;" onchange="return checkedAll(this)">
								</td>
								<td width="15%">Code</td>
								<td>Name</td>
								<td width="15%">Status</td>
								<td width="15%">Date Registered</td>
							</tr>
							
							<tr class="trlist">
								<td colspan="5" align="center">No result found.</td>
							</tr>
							
						</table>
						
					</div>
					
					<br />
					
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2"><b>Transfer Dealer To</b></td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<div class="bordersolo" style="border-top:none;">
						
						<table border="0" cellpadding="1" cellspacing="1" width="100%">
							<tr>
								<td>&nbsp;</td>
							</tr>
							
							<tr>
								<td class="fieldlabel">Transfer to Branch</td>
								<td class="separator">:</td>
								<td>
									<input type="text" value="" name="tobranch" class="txtfield">
									<input type="hidden" value="0" name="tobranchHidden">
								</td>
							</tr>
							
							<tr>
								<td class="fieldlabel">Transfer to IBM</td>
								<td class="separator">:</td>
								<td>
									<input type="text" value="" name="toIBM" class="txtfield" onkeypress="return IBMAutocomplete('[name=toIBM]', '[name=toIBMHidden]', 0);">
									<input type="hidden" value="0" name="toIBMHidden">
								</td>
							</tr>
							
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
						
					</div>
					
					<br />
					
					<div style="text-align:center;">
						<input type="button" value="Save" name="btnSave" class="btn">
						<input type="button" value="Cancel" name="btnCancel" class="btn">
					</div>
					<br />
					<br />
					
				</div>
				</form>
			</div>
			
			
		</td>
	</tr>
</table>