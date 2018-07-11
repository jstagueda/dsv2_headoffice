<style>
	
	.fieldlabel{
		text-align : right;
		font-weight : bold;
		width : 30%;
	}
	
	.separator{
		text-align : center;
		font-weight : bold;
		width : 5%;
	}
	
	.trheader td{
		padding : 5px;
		text-align : center;
		font-weight : bold;
		background : #FFDEF0;
		border-right : 1px solid #FFA3E0;
	}
	
	.trlist td{
		padding : 5px;
		border-top : 1px solid #FFA3E0;
		border-left : 1px solid #FFA3E0;
	}
</style>

<script type="text/javascript" src="js/CreditLimitRegister.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
			<?php include "nav_dealer.php";?>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top">
			
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Sales Force Management</span></td>
				</tr>
			</table>
			
			<br />
			
			<div style="width:98%; margin:auto; min-height:565px;">
				
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Credit Limit Register</td>
					</tr>
				</table>
				
				<br />
				
				<div style="width:600px;">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2"><b>Action</b></td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					<div class="bordersolo" style="border-top:none; padding:10px;">
						<form name="creditlimitregisterform" action="" method="post">
							<table cellpadding="2" cellspacing="2" width="100%">
							
								<tr>
									<td class="fieldlabel">Branch</td>
									<td class="separator">:</td>
									<td>
										<input type="text" value="" name="branch" class="txtfield">
										<input type="hidden" value="0" name="branchID">
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Date Range</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="datefrom" value="<?=date("m/d/Y");?>" class="txtfield" readonly="yes">
										-
										<input type="text" name="dateto" value="<?=date("m/d/Y");?>" class="txtfield" readonly="yes">
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Sales Force Level</td>
									<td class="separator">:</td>
									<td>
										<select class="txtfield" name="sfmlevel">
											<option value="0">Select</option>
											<?php 
												$saleforcelevel = $database->execute("SELECT * FROM sfm_level");
												if($saleforcelevel->num_rows){
													while($res = $saleforcelevel->fetch_object()){
														echo "<option value='".$res->codeID."'>".$res->desc_code." - ".$res->description."</option>";
													}
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Sales Force Range</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="sfmfrom" value="" class="txtfield">
										<input type="hidden" name="sfmfromhidden" value="0">
										-
										<input type="text" name="sfmto" value="" class="txtfield">
										<input type="hidden" name="sfmtohidden" value="0">
									</td>
								</tr>
								<tr>
									<td colspan="3" align="center">
										<input type="button" value="Search" name="btnSearch" class="btn">
										<input type="hidden" value="0" name="page">
									</td>
								</tr>
							</table>
						</form>
					</div>
				</div>
				
				<div class="loader" style="text-align:center; font-weight:bold; margin:10px;">&nbsp;</div>
				
				<div>
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2"><b>Result(s)</b></td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					<div class="pageloader">
						<table cellpadding="0" cellspacing="0" width="100%" class="bordersolo" style="border-top:none;">
							<tr class="trheader">
								<td>Sales Force Code</td>
								<td>Sales Force Name</td>
								<td>Credit Limit</td>
								<td>Date Update</td>
							</tr>
							<tr class="trlist">
								<td colspan="4" align="center">No result found.</td>
							</tr>
						</table>
					</div>
				</div>
				
				<br />
				
				<div style="text-align : center;">
					<input type="button" name="btnPrint" value="Print" class="btn">
					<input type="button" name="btnCancel" value="Cancel" class="btn">
				</div>
				
			</div>
			
		</td>
	</tr>
</table>