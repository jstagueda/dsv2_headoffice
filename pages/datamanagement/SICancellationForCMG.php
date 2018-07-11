<style>

div.autocomplete {

  position:absolute;
  /*width:300px;*/
  /*border:1px solid #888;
  margin:0px;
  padding:0px;*/
}

div.autocomplete span { position:relative; top:2px; }
 
div.autocomplete ul {
    max-height: 150px;
    overflow-x: hidden;
    overflow-y: auto;
    width: 319px;
    border: 1px solid #FF00A6;
    color: #312E25;
    list-style-type:none;
    margin:0px;
    padding:0px;
    border-radius:6px;
    background-color:white;
    background: #F5F3E5;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
  /*font-size: 10px;*/
}

div.autocomplete ul li.selected{ 
    background-color: #EB0089; 
    border:1px solid #c40370; 
    color:white; 
    font-weight:bold; 
    margin:3px; 
    border-radius:6px;
}

div.autocomplete ul li {
    line-height: 1.5;
    padding: 0.2em 0.4em;
    list-style-type:none;
    display:block;
    /*height:20px;*/
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    cursor:pointer;
}

.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
.ui-widget-overlay{height:130%;}

.fieldlabel{text-align:right; width:25%; font-weight:bold;}
.separator{text-align:center; width:5%; font-weight:bold;}
.trheader td{padding:5px; font-weight:bold; text-align:center; background:#ffdef0; border-right:1px solid #ffa3e0;}
.trlist td{padding:5px; border-top:1px solid #ffa3e0; border-right:1px solid #ffa3e0;}

</style>

<script src="js/SICancellation.js"></script>

<div style="min-height:620px;">
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table cellspacing="1" cellpadding="0" width="98%" border="0" align="center">
					<tr>
						<td width="70%" align="right">
							<a class="txtblueboldlink" onclick="return leavepage();" href="javascript:void(0);">Leave Page</a>
							|
							<a class="txtblueboldlink" href="index.php?pageid=4">Data Management</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
	<br />
	
	<div style="width:98%; margin:auto;">
	
		<table cellspacing="1" cellpadding="0" width="100%" border="0" align="center">
			<tr>
				<td class="txtgreenbold13">SI Cancellation</td>
			</tr>
		</table>
		
		<br />
		
		<div style="width:600px;">
		
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td>
					<td class="tabmin2">Action</td>
					<td class="tabmin3"></td>
				</tr>
			</table>
			<form action="" method="post" name="SICancellationForm">
				<table cellpadding="2" cellspacing="2" width="100%" class="bordersolo" style="border-top:none;">
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td class="fieldlabel">Branch</td>
						<td class="separator">:</td>
						<td>
							<input type="text" name="Branch" value="" class="txtfield">
							<input type="hidden" name="BranchID" value="0">
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Date Range</td>
						<td class="separator">:</td>
						<td>
							<input type="text" value="<?=date("m/d/Y");?>" name="DateFrom" class="txtfield" readonly="yes">
							-
							<input type="text" value="<?=date("m/d/Y");?>" name="DateTo" class="txtfield" readonly="yes">
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Customer Range</td>
						<td class="separator">:</td>
						<td>
							<input type="text" value="" name="CustomerFrom" class="txtfield">
							<input type="hidden" value="0" name="CustomerFromHidden">
							-
							<input type="text" value="" name="CustomerTo" class="txtfield">
							<input type="hidden" value="0" name="CustomerToHidden">
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Search</td>
						<td class="separator">:</td>
						<td>
							<input type="text" class="txtfield" value="" name="Search">
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<input type="button" value="Search" name="btnSearch" class="btn">
							<input type="hidden" value="1" name="page">
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
				</table>
			</form>
		</div>
		
		<div class="loader" style="text-align:center; font-weight:bold; padding:10px;">&nbsp;</div>
			
		<div>
			
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td>
					<td class="tabmin2">Action</td>
					<td class="tabmin3"></td>
				</tr>
			</table>
			
			<div class="pageloader">
				<table cellspacing="0" cellpadding="0" class="bordersolo" style="border-top:none;" width="100%">
					<tr class="trheader">
						<td width='10%'>Transaction No.</td>
						<td width='10%'>Document No.</td>
						<td width='10%'>Customer Code</td>
						<td>Customer Name</td>
						<td width='10%'>Gross Amount</td>
						<td width='10%'>Net Amount</td>
						<td width='10%'>Transaction Date</td>
						<td width='10%'>Status</td>
						<td width='5%'>Action</td>
					</tr>
					<tr class="trlist">
						<td colspan="9" align="center">No result found.</td>
					</tr>
				</table>
			</div>
			
		</div>
		
	</div>	
</div>

<div class="dialogmessage"></div>

<br />