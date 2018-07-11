<!--@Author: Gino C. Leabres..
	@Date: 	 9/10/2013..-->
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script>
$(document).ready(function(){

        $('[name=branchList]').autocomplete({
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
                $('[name=branch]').val(ui.item.ID);
                $('input[name=ibm]').val("")
                $('input[name=ibmid]').val(0);
                $('input[name=campaign]').val("");
            }
        });

	 $('input[name=ibm]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type        :   "post",
                url         :   "pages/sfpm/ajax_requests/sfandrfajax.php",
                dataType    :   "json",
                data        :   {
                    request:   "list of ibm by branch",
                    campaign:   $("[name=campaign]").val(),
                    searched:   request.term,
                    branchid:   $("[name=branch]").val()
                },
                success     :   function(data){
                    response( $.map( data, function( item ){
                        return {
                            label   :   item.Name,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('input[name=ibmid]').val(ui.item.ID);
        }
    });

    $('#btn-for-print').on('click',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
	var ibmid = $('input[name=ibmid]').val();
        var campaign = $('input[name=campaign]').val();
        var branch = $("[name=branch]").val();
        var LinkExtension = '&ibmid='+ibmid+'&campaign='+campaign+'&branch='+branch;
	window.location.href = path + '/exceldownloader.php?&docname=sfandrfreport'+LinkExtension;
        return false;
    });
});



</script>
<table border = "0" width = "100%" cellspacing = "0" cellpadding="0">
<tr>
	<td rowspan = "5" width="200" valign="top" class="bgF4F4F6">
	<?php  include("bcb_left_nav.php"); ?>
	</td>
	<td class="divider" rowspan = "5">&nbsp;</td>
	<td colspan = "3" class = "topnav" align="left">
		<table>
		<tr>
			<td>&nbsp;</td>
			<td>
				<span class="txtgreenbold13">Service fee and Referal fee report</span>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan = "3">&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align = "left" valign="top" style="min-height: 560px; display: block;">
		<div style="width:500px;">
			<div style="float:left; width:500px;">
				<div class="tbl-head-content-left tbl-float-inherit"></div>
				<div class="tbl-head-content-center tbl-float-inherit" style="width: 400px;">
					<span>Details</span>
				</div>
				<div class="tbl-head-content-right tbl-float-inherit"></div>
				<div class="tbl-clear"></div>
				<table width = "410px" border = "0" style= "border:1px solid #EB0089; border-top:none;" cellpadding = "1" cellspacing = "0">
                                    <tr>
                                            <td colspan="3">&nbsp;</td>
                                    </tr>
                                    <tr>
                                            <td align = "right" style="padding:2px;">Branch</td>
                                            <td align="center" width="5%">:</td>
                                            <td>
                                                <input name="branchList" class="txtfield">
                                                <input name="branch" type="Hidden" value="0">
                                            </td>
                                    </tr>
                                    <tr>
                                            <td align = "right" style="padding:2px;">Period</td>
                                            <td align="center" width="5%">:</td>
                                            <td>
                                                <input type = "text" id = "campaign" name = "campaign" class="txtfield">
                                            </td>
                                    </tr>
                                    <tr>
                                            <td align = "right" style="padding:2px;">IBM</td>
                                            <td align="center" width="5%">:</td>
                                            <td>
                                                <input type = "text" value = "" id = "ibm" name = "ibm" class = "txtfield">
                                                <input type = "hidden" value = "" id = "ibmid" name = "ibmid" class = "txtfield">
                                            </td>
                                    </tr>
                                    <tr>
                                            <td align = "right" style="padding:2px;">&nbsp;</td>
                                            <td align="center" width="5%"></td>
                                            <td>
                                                <input type = "submit" value = "Print as Excel" id = "btn-for-print" name = "btn-for-print" class = "btn">
                                            </td>
                                    </tr>
                                    <tr>
                                            <td colspan="3">&nbsp;</td>
                                    </tr>
				</table>
			</div>
		</div>
	</td>
	<td>&nbsp;</td>
</tr>
</table>


