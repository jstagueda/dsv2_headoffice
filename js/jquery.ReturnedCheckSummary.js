/*
		@author:Gino C. leabres...
		@date: 9/13/2013...
		@explanation: generate DCCR report...
*/

$(document).ready(function(){

    branchRange('branchfromList', 'branchfrom');
    branchRange('branchtoList', 'branchto');

    $('[name=dateto], [name=datefrom]').datepicker();
    //generate here...
    $("#generate_list").click(function(){
            //here process...
            $("#page").val(1);
            _doAjax();
            return false;
    })

    $('#btn-for-print').on('click',function(){
    var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
    //var BranchID 	= $('#campaign_from').val();

            var datefrom 	= $('input[name=datefrom]').val();
    var dateto 		= $('input[name=dateto]').val();
    var branchfrom  = $('[name=branchfrom]').val();
    var branchto 	= $('[name=branchto]').val();
    var sortby   	= $('select[name=sortby]').val();

    var LinkExtension = '&datefrom='+datefrom+'&dateto='+dateto+'&branchfrom='+branchfrom+'&branchto='+branchto+'&sortby='+sortby;
            //return false;
            window.location.href = path + '/exceldownloader.php?&docname=RCSReport'+LinkExtension;
    return false;
});

});

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


function showPage(page)
{
	$("#page").val(page);
	_doAjax();
}

function _doAjax()
{
	$("#pgContent").hide();
	$("#indicator").fadeIn();
	$('#pagination').html('');
	$("#btn-for-print").hide();
	var dynamic_tr="";

	dynamic_tr += "<table width='100%' cellpadding='0' border='0' cellspacing='0' class='bordergreen' id = 'ajax_process'>";
	dynamic_tr += "<tr class='trheader'>";
	dynamic_tr += "<td align='center'>Branch Code</td>";
	dynamic_tr += "<td align='center'>Branch Name</td>";
	dynamic_tr += "<td align='center'>DM Reference No.</td>";
	dynamic_tr += "<td align='center'>DM Date</td>";
	dynamic_tr += "<td align='center'>OR Number</td>";
	dynamic_tr += "<td align='center'>OR Date</td>";
	dynamic_tr += "<td align='center'>Check No.</td>";
	dynamic_tr += "<td align='center'>IGS Code</td>";
	dynamic_tr += "<td align='center'>IGS Name</td>";
	dynamic_tr += "<td align='center'>IBM Code</td>";
	dynamic_tr += "<td align='center'>Reason Code</td>";
	dynamic_tr += "<td align='center'>Amount</td>";
	dynamic_tr += "</tr>";

	$.ajax({
			//ajax process here...
			type:'post',
			dataType: 'json',
			data:$('form[name=frmPaymentClassification]').serialize(),
			url: 'pages/sfpm/ajax_requests/RCSReport.php',
			success:function(resp){
				//success here...
				if(resp['response']=='success'){
					for(var i = 0; resp['data_handler'].length>i;i++){
							dynamic_tr += "<tr class='trlist'>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].BranchCode+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].BranchName+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].DMCMReferenceNo+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].DMDATE+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].ORNUMBER+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].ORDate+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].CheckNo+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].IGSCODE+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].IGSName+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].IBMCODE+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].ReasonCode+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].Amount+"</td>";
							dynamic_tr +="</tr>";
					}
				}else{
					dynamic_tr += '<tr class="trlist">';
                    dynamic_tr += '<td colspan="12" align="center" style="color:red;">No result found.</td>';
					dynamic_tr += '</tr>';
				}

				$("#pgContent").html(dynamic_tr+"</table>");
				$("#indicator").hide();
				$("#pgContent").fadeIn();
				$("#btn-for-print").fadeIn();
				$("#generate_list").removeAttr("disabled","disabled");
				$("#pagination").html(resp['pagination'].page);

				return false;
			}

		});
}