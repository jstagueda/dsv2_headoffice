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
	width			:	35%;
}	
	
.separator{	
	text-align		:	center;
	font-weight		:	bold;
	width			:	5%;
}

</style>

<script language="javascript" src="js/DeliquentAccount.js"></script>
<script language="javascript" src="js/popinbox.js"></script>

<div style="min-height:605px;">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td width="70%" align="right">&nbsp;
							<a href="javascript:void(0);" onclick="return leavepage(71);" class="txtblueboldlink">Leave Page</a>
							|
							<a class="txtblueboldlink" href="index.php?pageid=71">Sales Force Management</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br />

	<div style="width:98%; margin:auto;">
		
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="txtgreenbold13">Delinquent Account</td>
			</tr>
		</table>
		
		<br />
		
		<div style="width:100%;">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td>
					<td class="tabmin2">Action</td>
					<td class="tabmin3"></td>
				</tr>
			</table>
			
			<form action="" method="post" name="DeliquentAccountForm">
				<div class="bordersolo CustomerSelection" style="border-top:none;">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr class="trheader">
							<td width="5%;">Branch</td>
							<td width="5%;">Customer</td>
							<td width="25%;">Customer Name</td>
							<td width="10%;">Birth Date</td>
							<td width="5%;">Gender</td>
							<td>Address</td>
							<td width="8%;">Total Outstanding Balance</td>
						</tr>
						<tr class="trlist">
							<td align="center">
								<input type="text" name="Branch" class="txtfield" value="" style="width:100px;">
								<input type="hidden" name="BranchID" value="0">
							</td>
							<td align="center">
								<input type="text" name="CustomerCode" class="txtfield" value="" style="width:100px;">
								<input type="hidden" name="CustomerID" value="0">
							</td>
							<td align="left"></td>
							<td align="center">N/A</td>
							<td align="center">N/A</td>
							<td align="left"></td>
							<td align="right">
								<span class="OutstandingBalace">0.00</span>
								<input type="hidden" value="0" name="OutstandingBalance">
							</td>
						</tr>
					</table>
				</div>
			</form>
			
			<div style="text-align:center; margin:10px;">
				<input type="button" value="Add to Delinquent Account" class="btn" name="btnAddDeliquent">
			</div>
			
			<br />
			
			<div style="margin: 10px 0px;">
				<b>Search : </b>
				<input type="text" value="" class="txtfield" name="SearchBranch" style="width:100px;" placeholder="Branch">
				<input type="hidden" value="0" name="SearchedBranchID">
				<input type="hidden" value="0" name="page">				
				<input type="text" value="" class="txtfield" name="SearchBar" placeholder="Search">
				<input type="button" value="Search" class="btn" name="btnSearch">
				<span class="loader"></span>
			</div>
			
			<div>				
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td class="tabmin"></td>
						<td class="tabmin2">Delinquent Account(s)</td>
						<td class="tabmin3"></td>
					</tr>
				</table>
				
				<div class="DeliquentAccountList">
					<table class="bordersolo" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top:none;">
						<tr class="trheader">
							<td width="10%;">Branch</td>
							<td width="25%;">Customer</td>
							<td width="10%;">Birth Date</td>
							<td width="10%;">Gender</td>
							<td>Address</td>
							<td width="10%;">Total Outstanding Balance</td>
							<td width="10%;">Action</td>
						</tr>
						<tr class="trlist">
							<td colspan="8" align="center">No result found.</td>
						</tr>
					</table>
				</div>
			</div>
			
		</div>
		
	</div>

</div>
<br />

<div id="message-dialog" style="display:none;">
	<p></p>
</div>