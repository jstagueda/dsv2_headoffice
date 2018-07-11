


<script language="javascript" type="text/javascript">

$ = jQuery.noConflict();

$(document).ready(function(){
	
	$('[name=btnSearch]').click(function(){
		showPage(1);
	});
	
	showPage(1);
	
})
function showPage(page){
    $('[name=page]').val(page);
    
    $.ajax({
        type        :   "post",
        url         :   "pages/datamanagement/tpi_document/ajax/process.php?action=pagination",
        data        :   $('[name=frmSpecialPromo]').serialize(),
        beforeSend  :   function(){
            //popinmessage("Loading... Please wait...");
        },
        success     :   function(data){
            //$('#dialog-message').dialog("close");
            $('.pgLoad').html(data);
        }
        
    });
    return false;
}

function showSpecialPromo(ID){
    var pageid = <?php echo $_GET['pageid'];?>;
    objLink = "pages/datamanagement/tpi_document/view.php?ID="+ID+"&pageid="+pageid;
    openPopUp(objLink);
    return false;
    
}

function openPopUp(ObjLink) 
{
    var objWin;
    popuppage = ObjLink;

    if (!objWin) 
    {			
            objWin = NewWindow(popuppage,'printps','1500','700','yes');
    }

    return false;  		
}

function NewWindow(mypage, myname, w, h, scroll) 
{
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
</script>
<script src="js/popinbox.js" language="javascript" type="text/javascript"></script>

<style>
    .trheader td{padding:5px; border-left:1px solid #FFA3E0; background:#FFDEF0; font-weight:bold;}
    .trlist td{padding:5px; border-left:1px solid #FFA3E0; border-top:1px solid #FFA3E0;}
    .ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
    .ui-widget-overlay{height:130%;}
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav">
			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td width="70%" class="txtgreenbold13" align="Left"></td>
						
				<td width="70%" align="right">
								<a class="txtblueboldlink" href="index.php?pageid=0" >Leave Page</a>
								|
								<a class="txtblueboldlink" href="index.php?pageid=4">Data Management</a>
							</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">View CE Codes</a></td>
			</tr>
		</table>
	</td>
</tr>
</table>

<br>
<form name="frmSpecialPromo" method="post" action="" style="min-height:495px;">

<input type="hidden" name="page" value="1">
<div style="margin:auto; width:95%;">
	<table width="40%"  border="0" align="left" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="txtredbold padl5"><b>Action</b></div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
	</table>
	<div style="clear:both;"></div>
	
	<table width="40%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td>&nbsp;</td>
					<td width="5%">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>			
				<tr>
					<td align="right">
						<strong>Search</strong>
					</td>
					<td align="center">:</td>
					<td style="padding:3px;">
						<input name="Search" type="text" class="txtfield" id="Search" value="" size="30">
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td style="padding:3px;">
						<input name="btnSearch" type="button" class="btn" value="Search">
					</td>
				</tr>
				<tr>
					<td height="20" colspan="3">&nbsp;</td>
				</tr>	
				</table>
			</td>
		</tr>
	</table>
	
	<div style="clear:both;"></div>
</div>
<br />
<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
	<td class="tabmin2"><div align="left" class="txtredbold padl5"><b>List CE Codes</b></div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
    <tr>
        <td valign="top" class="bgF9F8F7">
            <div class="pgLoad">                
                <table width="100%"   border="0" cellpadding="0" cellspacing="0" class="bordergreen">
                    <tr align='center' class="trheader">
                        <td>Promo Code</td>
                        <td>Promo Title</td>
                        <td>Start Date</td>
                        <td>End Date</td>
                    </tr>
                    <tr class="trlist">
                        <td colspan='4' align="center">No result found.</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</form>
<br />
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
	
    <input name='btnCreate' type='submit' class='btn' value='Create New' onclick="location.href='index.php?pageid=346';">
	</td>			
</tr>
</table>
<br>

<!--Added by joebert-->
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<!--end-->