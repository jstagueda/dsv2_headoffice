<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery-ui-1.10.0.custom.min.js"></script>
<script src="js/popinbox.js"></script>

<style>
    .ui-datepicker{display:none;}
    .ui-dialog .ui-dialog-titlebar-close span{
        margin: -9px;
    }
</style>

<script>
$(document).ready(function(){
    $( "#datepicker" ).datepicker();
	
	$('[name=branchName]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	'post',
				dataType:	'json',
				url		:	'includes/interface_generate_report.php',
				data	:	{getBranch : 'getBranch', branchName : request.term},
				success	:	function(data){
					response($.map(data, function(item){
						return{
							label	:	item.Label,
							value	:	item.Value,
							ID		:	item.ID
						}
					}));
				}
			});
		},
		select	:	function(event, ui){
			$('[name=branch]').val(ui.item.ID);
		}
	});
	
});

function generate_report()
{
if($("#datepicker").val() == ""){
	popinmessage("Date required.");
	//$("#datepicker").focus();
	return false;
}else{
	$.ajax({
		type: 'post',
		data: $('form').serialize(),
		dataType: 'json',
		url: 'includes/interface_generate_report.php',
                beforeSend: function(){
                    $('#logsheader').hide();
                    $('.scroll_300h').removeClass('bordersolo');
                    $('.scroll_300h').show();
                    $('.scroll_300h').html('<center><b>Loading... Please wait...</b></center>');
                },
		success: function(resp){
			var html1 = "";
			if(resp[0].gino == "successful"){
				popinmessage("Generation report successful");
				for (var i=0; i<resp.length; i++){
					html1 += "<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='bgFFFFFF'>";
					html1 += "<tr>";
					html1 += "<td height='20' colspan = '2' class='bgFFFFFF' align='right'>";
					html1 += "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>Branch : "+resp[i].branchname+"</div></td>";
					html1 += "</tr>";
					html1 += "<tr>";
					html1 += "<td height='20' colspan = '2' class='bgFFFFFF' align='right'>";
					html1 += "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>Transaction Date: "+resp[i].transactiondate+"";
					html1 += "</div></td>";
					html1 += "</tr>";
					html1 += "<tr>";
					html1 += "<td height='20' colspan = '2' class='bgFFFFFF' align='right'><div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>Date Generated: "+resp[i].dategenerated+"</div></td>";
					html1 += "</tr>";
					html1 += "<tr>";
					html1 += "<td height='20' colspan = '2' class='bgFFFFFF' align='right'><div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>File Type: "+resp[i].description+"</div></td>";
					html1 += "</tr>";
					html1 += "<tr>";
					html1 += "<td height='20' colspan = '2' class='bgFFFFFF' align='right'><div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>Total No. Records: "+resp[i].totlnorecords+"</div></td>";
					html1 += "</tr>";
					html1 += "<tr>";
					html1 += "<td height='20' colspan = '2' class='bgFFFFFF' align='right'><div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>&nbsp;</div></td>";
					html1 += "</tr>";
					html1 += "</table>";
				}
					$('#logsheader').show();
					$('.scroll_300h').show();
					$(".scroll_300h").html(html1);
                                        $('.scroll_300h').addClass('bordersolo');
					html1 = "";

			}else{
					popinmessage(resp[0].msg);
				
					html1 += "<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='bgFFFFFF'>";
					html1 += "<tr>";
					html1 += "<td height='20' colspan = '2' class='bgFFFFFF' align='right'>";
					html1 += "<div align='center' style='padding:5px 0 0 5px;' class='txtredsbold'>No Record(s) displayed.</div></td>";
					html1 += "</tr>";
					html1 += "</table>";
					$('#logsheader').show();
					$('.scroll_300h').show();
                                        $('.scroll_300h').addClass('bordersolo');
					
					$(".scroll_300h").html(html1);
			}
		}
	})
	return false;
}
	return false;
}

function print_logs() 
{

	var objWin;
	var datePicked = document.getElementById('datepicker').value;
		popuppage = "pages/datamanagement/viewoutputinterfacelog.php?date=" + datePicked;
	
		if (!objWin && datePicked != '') 
		{			
			objWin = NewWindow(popuppage,'printps','800','500','yes');
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
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6"><?PHP include("nav.php"); ?></td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;">
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
					<td class="txtgreenbold13">Output Interface File Generator:</td>
					<td>&nbsp;</td>
				 </tr>
				 <tr>
					<td><div id="divTest" width="90%"></div></td>
				 </tr>
			  </table>
			  <br />
				<div id="divButtons">
					<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td width="33%" valign="top">
							<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
							<tr>          	
								<form method="post">
									<td>
										<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
											<tr>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td align="center" >
												
													<div style="width:300px; margin:auto;">
														<table width="100%">
															<tr>
																<td><b>Branch</b></td>
																<td>:</td>
																<td>
																	<input type="text" class="txtfield" id="branchName" name = "branchName" />
																	<input type="hidden" value="0" id="branch" name = "branch"  />
																</td>
															</tr>
															<tr>
																<td><b>Date</b></td>
																<td>:</td>
																<td>
																	<input type="text" class="txtfield" id="datepicker" name = "date"  />
																</td>
															</tr>
														</table>
													</div>
													
												</td>
											</tr>
											<tr>
												<td align="center" style="padding-top:10px;">
                                                                                                    <input name="generate" onclick = "return generate_report();" type="button" class="btn" value="Generate" />
                                                                                                    <input name="generate" onclick = "return print_logs();" type="button" class="btn" value="Print Logs" />
                                                                                                </td>
											</tr>
											<tr>
												<td align="right">&nbsp;</td>
											</tr>
											<tr>
												<td align="right">&nbsp;</td>
											</tr>
                                                                                        <tr>  
                                                                                            <td>
												<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0' id = "logsheader"style = "display:none;" ><tr>
													<td class='tabmin'>&nbsp;</td><td class='tabmin2'><span class='txtredbold'>Logs: </span>&nbsp;</td><td class='tabmin3'>&nbsp;</td></tr>
												</table>
												<div class='scroll_300h' style = "display:none;">
												/*ajax*/
												</div>
                                                                                                <br />
                                                                                            </td>
                                                                                        </tr>
										</table>
									</td>           
								</form>
							</tr>
							</table>
						</td>
					</tr>
					</table>
				</div>
	       </td>
        </tr>
      </table>
      </br></br>
    </td>
  </tr>
		
<!--Added by joebert-->
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<!--end-->