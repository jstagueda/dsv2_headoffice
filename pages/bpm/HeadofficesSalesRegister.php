<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script>
$(document).ready(function(){
    $("#txtStartDate").datepicker();
    $("#txtEndDate").datepicker();

    $('[name=branchesList]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term},
                url     :   "pages/bpm/ajax_requests/AppliedPaymentReport.php",
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Label,
                            value   :   item.Value,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=branches]').val(ui.item.ID);
        }
    });
});

function openPopUp()
{
	var branches = $("[name=branches]").val();
	var start = $("#txtStartDate").val();
	var end = $("#txtEndDate").val();
	var err_msg = "", error_count = 0;

	if(branches == 0 || $("[name=branchesList]").val() == ''){
		err_msg += "*Branch Required \n";
		error_count++;
	}

	if(start == ""){
		err_msg += "*Start Date Required \n";
		error_count++;
	}

	if(end == ""){
		err_msg += "*End Date Required \n";
		error_count++;
	}

	if(error_count == 0){
		var objWin;
		popuppage = "pages/bpm/HeadOfficeSalesRegisterPrint.php?BranchID="+ branches + "&StartDate=" + start + "&EndDate=" + end + "&isCancelled=" + $('[name=isCancelled]').val();

		if (!objWin)
		{
			objWin = NewWindow(popuppage,'printps','1000','600','yes');
		}
		return false;
	}else{
		alert(err_msg);
	}
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

<?php 
	$cancelled = ($_GET['pageid'] == 206) ? 0 : 1;
?>

<form name="frmORRegister" method="post" action="index.php?pageid=99">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php
                include("bpm_left_nav.php");
            ?><br>
        </td>
        <td class="divider">&nbsp;</td>
	<td valign="top" style="min-height: 610px; display: block;">
  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
	    		<tr>
	      			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=203">Branch Performance Monitoring</a></td>
	    		</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">
				<?php 
					if($cancelled){
						echo "Cancelled Sales Invoice";
					}else{
						echo "Sales Invoice Register";
					}
				?>
			</td>
    		<td>&nbsp;</td>
		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
	    	<td>
                    <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                    <td class="tabmin">&nbsp;</td>
                                    <td class="tabmin2"><span class="txtredbold">Action</span></td>
                                    <td class="tabmin3">&nbsp;</td>
                            </tr>
                    </table>
			  	<table width="99%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		        <tr>
		          	<td width="8%">&nbsp;</td>
		          	<td width="91%" align="right">&nbsp;</td>
		        </tr>
				<tr>
		          	<td height="20" class="padr5" align="right">Branches:</td>
					<td height="20">

							<input id = 'branches' name = 'branchesList' class = "txtfield">
                            <input type="hidden" name = 'branches' class = "txtfield">
							<input type="hidden" name="isCancelled" value="<?=$cancelled;?>">
					</td>
				</tr>
	    		<tr>
		          	<td height="20" class="padr5" align="right">From Date :</td>
		          	<td height="20">
		          		<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="">
	    			</td>
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right">To Date :</td>
		          	<td height="20">
		          		<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="">
						<input name="input" type="button" class="btn" value="Generate Report" onclick="openPopUp()"/>
					</td>
				</tr>
				<tr>
		          	<td colspan="2" height="20">&nbsp;</td>
		        </tr>
	    		</table>
			</td>
  		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center">
		<tr>
			<td height="20" align="center">
				<a class="txtnavgreenlink" href="index.php?pageid=18"><input name="Button" type="button" class="btn" value="Back"></a>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
</form>
