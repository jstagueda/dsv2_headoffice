<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery-ui-1.10.0.custom.min.js"></script>
<script>
	var logtype="",xdate="",xdata="";;
	$(document).ready(function(){
		$("input[name=datepicker]").datepicker();
		
		$("input[name=generate]").click(function(){
			xdate = $("input[name=datepicker]").val();
			logtype = $("select[name=logtype]").val();
			xdata = {request:'save', 'xdate':xdate, 'logtype':logtype}
			$.ajax({
				type:'post',
				data:xdata,
				url:'pages/datamanagement/system_param_call_ajax/ajax_systemlogs.php',
				success:doAjaxRequest
			})
			return false;
		})
		
		$("input[name=Print]").click(function(){
			xdate = $("input[name=datepicker]").val();
			logtype = $("select[name=logtype]").val();
			
			openPopUp(xdate,logtype) 
		})
	});
function doAjaxRequest(response)
{
	var resp = $.parseJSON(response);
	var xhtml = "";
	//alert(resp['response']);
	if(resp['response']=='success'){
		//alert(resp['fetch_data'].length);
		for(var i = 0; i < resp['fetch_data'].length; i++){
			xhtml += resp['fetch_data'][i].FileName+"<br>"; 
			xhtml += resp['fetch_data'][i].LogDescription+"<br>"; 
		}
	}else{
		xhtml += "0 Result(s) displayed.";
	}
	$(".uiinterface").html(xhtml);
}



function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(xdate,logtype)
{
	var objWin;
		popuppage = "pages/datamanagement/print_systemlogs.php?logtype=" + logtype + "&Date="+xdate;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1000','500','yes');
		}
		
		return false;  		
}	

</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?PHP include("nav.php");?>
            <br>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management </span></td>
                </tr>
            </table>
            <br />

            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top">
                        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td class="txtgreenbold13">View System Logs</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                        <br />

                        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td width="33%" valign="top">
                                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="tabmin">&nbsp;</td>
                                            <td class="tabmin2"><span class="txtredbold">Action</span></td>
                                            <td class="tabmin3">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table width="100%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
                                        <tr>
                                            <td>
                                                <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
                                                    <tr>
                                                        <td width="50%">&nbsp;</td>
                                                        <td width="5%" align="right">&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                 
                                                    <tr>
                                                        <td align="right"><b>Generate Log file</b></td>
                                                        <td align="center">:</td>
                                                        <td>
															 <input type="text" value="" class = "txtfield" name="datepicker">
                                                            <select class ="txtfield" name="logtype">
																<?php 
																	$q = $database->execute("SELECT trim(LogType) LogType FROM systemlog GROUP BY LogType ORDER BY LogType ASC"); 
																	if($q->num_rows){
																		while($r=$q->fetch_object()){
																		$LogType = $r->LogType;
																		?>
																			<option value = '<?php echo str_replace("'",'',$LogType); ?>' ><?php echo $LogType; ?></option>
																		<?php }
																	}else{
																			echo "<option value = 0> 0 Result(s)</option>";
																	}
																?>
															</select>
                                                            <input type="button" value="Generate" class = "btn" name="generate">
                                                            <input type="button" value="Print" class = "btn" name="Print">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td align="right">&nbsp;</td>
                                                        <td align="right">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2%">&nbsp;</td>
                                <td width="60%" valign="top"></td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <div style="width:60%;">
                                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="tabmin">&nbsp;</td>
                                                <td class="tabmin2"><span class="txtredbold">Result</span></td>
                                                <td class="tabmin3">&nbsp;</td>
                                            </tr>
                                        </table>
                                        <table width="100%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
                                            <tr>
                                                <td>
                                                    <style>
                                                        .uiinterface{
                                                            height: 350px;
                                                            overflow: auto;
                                                            padding: 10px;
                                                        }
                                                        .uiinterface p{padding: 5px 2px; margin:0}
                                                        .uiinterface p.titlefile{background:pink;}
                                                        .uiinterface p.titlefileheader{background:none;}
                                                    </style>
                                                    <div class="uiinterface">
														<!-- AJAX HERE -->
													</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div>&nbsp;</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
