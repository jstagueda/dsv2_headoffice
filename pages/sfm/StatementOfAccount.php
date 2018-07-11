<?php 
	include IN_PATH.DS."pagination.php";	
?>

<script language="javascript" src="js/StatementOfAccount.js"></script>

<style>

.trheader td{
	border-right	: 	1px solid #FFA3E0;
	padding			:	5px;
	font-weight		:	bold;
	text-align		:	center;
	background		:	#FFDEF0;
}

.trlist td{
	border-right	: 	1px solid #FFA3E0;
    border-top		: 	1px solid #FFA3E0;
	padding			:	5px;
}

.fieldlabel{
	text-align		:	right;
	font-weight		:	bold;
	width			:	25%;
}	
	
.separator{	
	text-align		:	center;
	font-weight		:	bold;
	width			:	5%;
}

.statementofaccountparameter{
	width			:	600px;
}

</style>

<table cellspacing="0" border="0" cellpadding="0" width="100%">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
			<?PHP
				include("pages/sfpm/sfpm_left_nav.php");
			?>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top" style="min-height: 610px; display: block;">
			
			<div style="padding:10px; background:#FFDEF0;">
				<span class="txtgreenbold13">Sales Force Management</span>
				<div style="float:right;">
					<a href="#"></a>
				</div>
				<div style="clear:both;"></div>
			</div>
			<br />
			
			<div style="margin:auto; width:98%; min-width:960px;">
			
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Statement of Account</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<br />
				
				<div class="statementofaccountparameter">
					
					<table width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td class="tabmin"> </td>
							<td class="tabmin2"><b>Action</b></td>
							<td class="tabmin3"> </td>
						</tr>
					</table>
					
					<div class="bordersolo" style="border-top:none; padding:10px;">
						<form method="post" name="statementofaccountform" action="">
						<table width="100%" cellspacing="0" cellpadding="1">
							<tr>
								<td class="fieldlabel">Branch</td>
								<td class="separator">:</td>
								<td>
									<input type="text" name="BranchName" value="" class="txtfield">
									<input type="hidden" name="BranchID" value="0">
								</td>
							</tr>
							<tr>
								<td class="fieldlabel">Customer</td>
								<td class="separator">:</td>
								<td>
									<input type="text" name="Customer" value="" class="txtfield">
									<input type="hidden" name="CustomerID" value="0">
								</td>
							</tr>
							<tr>
								<td class="fieldlabel">Date Range</td>
								<td class="separator">:</td>
								<td>
									<input type="text" name="DateFrom" value="<?=date('m/d/Y');?>" class="txtfield" readonly="yes">
									&nbsp;-&nbsp;
									<input type="text" name="DateTo" value="<?=date('m/d/Y');?>" class="txtfield" readonly="yes">
								</td>
							</tr>
							<tr>
								<td class="fieldlabel"></td>
								<td class="separator"></td>
								<td>
									<input type="hidden" value="1" name="page">
									<input type="button" class="btn" name="btnSearch" value="Search">
								</td>
							</tr>
						</table>
						</form>
					</div>
					
				</div>
				
				<div class="loader" style="font-weight:bold; text-align:center; padding:10px;">&nbsp;</div>
				
				<div class="statementofaccount">
					
					<table width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td class="tabmin"> </td>
							<td class="tabmin2"><b>Result(s)</b></td>
							<td class="tabmin3"> </td>
						</tr>
					</table>
					
					<div class="statementofaccountlist bordersolo" style="border-top:none;">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr class="trheader">
								<td>Date</td>
								<td>Reference #</td>
								<td>Transaction Type</td>
								<td>Debit</td>						
								<td>Credit</td>
								<td>Balance</td>
							</tr>
							<tr class="trlist">
								<td colspan="6" align="center">No result found.</td>
							</tr>
						</table>
					</div>
					
				</div>
				<div style="margin-top:10px; text-align:center;">
					<input type="button" name="btnPrint" value="Print" class="btn">
				</div>
				
			</div>
			
		</td>
	</tr>
</table>

<div id="message-dialog" style="display:none;">
	<p></p>
</div>