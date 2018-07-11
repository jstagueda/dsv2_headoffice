<style>
	.fieldlabel{
		font-weight:bold;
		text-align:right;
		width:40%;
	}
	
	.separator{
		font-weight:bold;
		text-align:center;
		width:5%;
	}
	
	.trheader td{
		background : #FFDEF0;
		border-right : 1px solid #FFA3E0;
		font-weight : bold;
		text-align : center;
		padding : 5px;
	}
	
	.trlist td{
		border-right : 1px solid #FFA3E0;
		border-top : 1px solid #FFA3E0;
		padding : 5px;
	}
	
</style>

<script type="text/javascript" src="js/datamgt/DMCMandCollectionReceiptReason.js"></script>
<script type="text/javascript" src="js/popinbox.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
			<?php include("nav.php");?>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top">
			
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management</span></td>
				</tr>
			</table>
			
			<br />
			
			<div style="width:98%; margin:auto; min-height:575px;">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td class="txtgreenbold13">DMCM and Collection Receipt Reason</td>					
					</tr>
				</table>
				
				<br />
				
				<div style="width:500px;">
				
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Action</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<div style="padding:10px; border-top:none;" class="bordersolo">
						<table width="100%" cellpadding="2" cellspacing="2">
							<tr>
								<td class="fieldlabel">Categories</td>
								<td class="separator">:</td>
								<td>
									<select name="categories" class="txtfield">
										<option value="1">Reason Code</option>
										<option value="2">GL Accounts</option>
										<option value="3">Reason and GL Account Tagging</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="3" align="center">
									<br />
									<input type="button" class="btn" name="btnAddReasonCode" value="Add Reason Code" style="display:block;">
									<input type="button" class="btn" name="btnAddGLAccount" value="Add GL Account" style="display:none;">
									<input type="button" class="btn" name="btnTagReasonAndGLAccount" value="Tag Reason Code and GL Account" style="display:none;">
								</td>
							</tr>
						</table>
					</div>
				</div>
				
				<br />
					
				<div class="reasoncode" style="display:block;">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Reason Code</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					<div class="reasoncodetable">
						<table width="100%" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
							<tr class="trheader">
								<td>Reason Code</td>
								<td>Reason Name</td>
								<td>Nature</td>
								<td>CR or DMCM</td>
								<td>Action</td>
							</tr>
							<tr class="trlist">
								<td colspan="5" align="center">No result found.</td>
							</tr>
						</table>
					</div>
				</div>
				
				<div class="glaccount" style="display:none;">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">GL Account</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					<div class="glaccounttable">
						<table width="100%" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
							<tr class="trheader">
								<td>GL Account</td>
								<td>Description</td>
								<td>Action</td>
							</tr>
							<tr class="trlist">
								<td colspan="3" align="center">No result found.</td>
							</tr>
						</table>
					</div>
				</div>
				
				<div class="reasonandglaccounttagging" style="display:none;">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Reason and GL Account Tagging</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					<div class="reasonandglaccounttaggingtable">
						<table width="100%" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
							<tr class="trheader">
								<td>Categories</td>
								<td>Reason</td>
								<td>GL Account</td>
								<td>Description</td>
								<td width="10%">Debit or Credit</td>
								<td width="10%">Cost Center</td>
								<td width="10%">Sub Account</td>
								<td width="10%">Project Code</td>
								<td width="5%">Action</td>
							</tr>
							<tr class="trlist">
								<td colspan="9" align="center">No result found.</td>
							</tr>
						</table>
					</div>
				</div>
				
			</div>
			
			<br />
			
		</td>
	</tr>
</table>

<div class="dialogmessage"></div>
<div id="dialog-message" style="display:none;"><p></p></div>
