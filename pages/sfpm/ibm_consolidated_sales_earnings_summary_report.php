<!--@Author: Gino C. Leabres..
	@Date: 	 9/10/2013..-->
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script>
$(document).ready(function(){
	//this code generate xls file
	$('#btn-for-print').on('click',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        //var BranchID 	= $('#campaign_from').val();

		var IBMFrom 	= $('#cboSIBMRHidden').val();
        var IBMTo 		= $('#cboEIBMRHidden').val();
        var branch_from = $('#branch_from').val();
        var branch_to 	= $('#branch_to').val();
		var Campaign_from = $("#campaign_from").val();
		var Campaign_to = $("#campaign_to").val();
        var LinkExtension = '&IBMFrom='+IBMFrom+'&IBMTo='+IBMTo+'&Branch_From='+branch_from+'&Branch_To='+branch_to+'&CampaignFrom='+Campaign_from+'&CampaignTo='+Campaign_to;
		//return false;
		window.location.href = path + '/exceldownloader.php?&docname=ibm_consolidated_sales_earning'+LinkExtension;
        return false;
    });

    //autocompleter for IBM range from
    $('input[name=cboSIBMR]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type        :   "post",
                url         :   "pages/inventory/inventory_call_ajax/ajaxIBMCumPerReport.php",
                dataType    :   "json",
                data        :   {
                    ibmfromx:   "ibmfrom",
                    searched:   request.term
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
            $('input[name=cboSIBMRHidden]').val(ui.item.ID);
        }
    });

	//autocomplete for IBM range to
    $('input[name=cboEIBMR]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type        :   "post",
                url         :   "pages/inventory/inventory_call_ajax/ajaxIBMCumPerReport.php",
                dataType    :   "json",
                data        :   {
                    ibmtox  :   "ibmto",
                    searched:   request.term,
                    ibmfrom :   $('input[name=cboSIBMRHidden]').val()
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
            $('input[name=cboEIBMRHidden]').val(ui.item.ID);
        }
    });

    branchRange('branch_fromList', 'branch_from');
    branchRange('branch_toList', 'branch_to');

    function branchRange(field, hiddenfield){
        $('[name='+field+']').autocomplete({
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
                $('[name='+hiddenfield+']').val(ui.item.ID);
            }
        });
    }
});
</script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<table border = "0" width = "100%" cellspacing = "0" cellpadding="0">
<tr>
	<td rowspan = "5" width="200" valign="top" class="bgF4F4F6">
	<?php  include("bcb_left_nav.php"); ?>
	</td>
	<td class="divider" rowspan = "5">&nbsp;</td>
	<td colspan = "3" class = "topnav" valign="top" align="left">
		<table>
		<tr>
			<td>&nbsp;</td>
			<td>
				<span class="txtgreenbold13">IBM Consolidated Sales & Earnings summary report</span>
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
	<td align = "left" valign="top" style="min-height: 550px; display: block;">
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
					<td align = "right" style="padding:3px;">Campaign:</td>
					<td>
                                            <input id = "campaign_from" name = "campaign_from" class="txtfield">
                                            <input type = "hidden" value = "0" name = "campaign_to" id = "campaign_to">
					</td>
					<td><!--select id = "campaign_to" name = "campaign_to" class="txtfield">
						<option value="0">[SELECT HERE]</option>
							<?php /*$q = $database->execute("SELECT CampaignCode  FROM tpi_sfasummary GROUP BY CampaignCode ORDER BY concat(CampaignMonth,'',CampaignYear) asc");
								if($q->num_rows){
									while($r=$q->fetch_object()){
										echo "<option value = '".$r->CampaignCode."'>".$r->CampaignCode."</option>";
									}
								}*/
							?>
						</select -->
					</td>
				</tr>
				<tr>
					<td align = "right" style="padding:3px;">IBM:</td>
					<td><input type = "text" value = "" id = "cboSIBMR" name = "cboSIBMR" class = "txtfield">
					<input type = "hidden" value = "" id = "cboSIBMRHidden" name = "cboSIBMRHidden" class = "txtfield"></td>
					<td><input type = "text" value = "" id = "cboEIBMR" name = "cboEIBMR" class = "txtfield">
					<input type = "hidden" value = "" id = "cboEIBMRHidden" name = "cboEIBMRHidden" class = "txtfield"></td>
				</tr>
				<tr>
					<td align = "right" style="padding:3px;">Branch:</td>
					<td>
                                            <input id = "branch_from" name = "branch_fromList" class="txtfield">
                                            <input id = "branch_from" name = "branch_from" value="0" class="txtfield" type="hidden">
                                        </td>
					<td>
                                            <input id = "branch_to" name = "branch_toList" class="txtfield">
                                            <input id = "branch_to" name = "branch_to" value="0" class="txtfield" type="hidden">
                                        </td>
				</tr>
				<tr>
					<td align = "right" style="padding:3px;">&nbsp;</td>
					<td><input type = "submit" value = "Print as Excel" id = "btn-for-print" name = "btn-for-print" class = "btn"></td>
					<td>&nbsp;</td>
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
<tr>
<td colspan = "3">&nbsp;</td>
</tr>
</table>


