<script type="text/javascript" src="js/ReservationReport.js"></script>
<script type="text/javascript" src="js/popinbox.js"></script>

<style>
	.fieldlabel{
		text-align : right;
		width : 20%;
		font-weight : bold;
	}
	
	.separator{
		text-align : center;
		width : 5%;
		font-weight : bold;
	}
	
	.trheader td{
		padding : 5px;
		text-align : center;
		font-weight : bold;
		background-color : #FFDEF0;
		border-right : 1px solid #FFA3E0;
	}
	
	.trlist td{
		padding : 5px;
		border-top : 1px solid #FFA3E0;
		border-right : 1px solid #FFA3E0;
	}
</style>

<div style="min-height:610px;">

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td width="70%" align="right">&nbsp;
							
							<a href="javascript:void(0);" onclick="return leavepage(1);" class="txtblueboldlink">Leave Page</a>
							|
							<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Main</a></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
	<div style="width:98%; margin:auto;">
	
		<br />
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="txtgreenbold13">Reservation Report</td>
			</tr>
		</table>
		
		<br />
		
		<div style="width:40%;">
			
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="tabmin"></td>
					<td class="tabmin2">Action</td>
					<td class="tabmin3"></td>
				</tr>
			</table>
			
			<form action="" method="post" name="ReservationReport">
			<div class="bordersolo" style="border-top:none;">
				
				<div style="clear:both;">&nbsp;</div>
			
				<table cellspacing="1" cellpadding="1" width="100%" align="left">
					<tr>
						<td class="fieldlabel">Branch</td>
						<td class="separator">:</td>
						<td>
							<input type="text" name="branch" value="" class="txtfield">
							<input type="hidden" name="branchID" value="0">
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Date</td>
						<td class="separator">:</td>
						<td>
							<input type="text" name="datefrom" value="<?=date("Y/m/d")?>" class="txtfield" readonly="yes">
							-
							<input type="text" name="dateto" value="<?=date("Y/m/d")?>" class="txtfield" readonly="yes">
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Search</td>
						<td class="separator">:</td>
						<td>
							<input type="text" name="Search" value="" class="txtfield">
							<input type="hidden" name="page" value="1">
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<br />
							<input type="button" class="btn" name="btnSearch" value="Search">
						</td>
					</tr>
				</table>
								
				<div style="clear:both;">&nbsp;</div>
				
			</div>
			
			</form>
		</div>
		
		<div class="loader" style="padding:10px; font-weight:bold; text-align:center;">&nbsp;</div>
		
		<div>
			
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="tabmin"></td>
					<td class="tabmin2">Result(s)</td>
					<td class="tabmin3"></td>
				</tr>
			</table>
			
			<div class="pageLoad">
				<table cellpadding="0" cellspacing="0" width="100%" class="bordersolo" style="border-top:none;">
					
					<tr class="trheader">
						<td width="10%">Reservation No.</td>
						<td width="10%">Item Code</td>
						<td>Item Description</td>
						<td width="10%">Commitment Date</td>
						<td width="10%">Approved Quantity</td>
						<td width="10%">Delivered Quantity</td>
						<td width="10%">DR No</td>
						<td width="10%">DR Date</td>
					</tr>
					<tr class="trlist">
						<td align="center" colspan="9">No result found.</td>
					</tr>
					
				</table>
				
			</div>
			
			<div style="text-align:center; margin:15px 0;">
				<input type="button" value="Print" name="btnPrint" class="btn">
				<input type="button" value="Back" name="btnBack" class="btn" onclick="location.href='?pageid=1'; return false;">
			</div>
			
		</div>
		
	</div>
</div>

<div class="dialogmessage"></div>
<div id="dialog-message" style='display:none;'><p></p></div>