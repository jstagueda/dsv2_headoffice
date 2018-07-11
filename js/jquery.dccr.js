/*
		@author:Gino C. leabres...
		@date: 9/13/2013...
		@explanation: generate DCCR report...
*/

$(document).ready(function(){
	$('[name=dateto], [name=datefrom]').datepicker();
	//generate here...
	$("#generate_list").click(function(){
		//here process...
		$("#page").val(1);
		_doAjax();
		return false;
	})
	 var issummary 	= 1;
	 $('input[name=issummary]').change(function(){
			issummary = $(this).val();
			$("input[name=xissummary]").val(issummary);
	})

	$('#btn-for-print').on('click',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        //var BranchID 	= $('#campaign_from').val();

		var datefrom 	= $('input[name=datefrom]').val();
        var dateto 		= $('input[name=dateto]').val();
        var branchfrom  = $('[name=branchfrom]').val();
        var branchto 	= $('[name=branchto]').val();
        var xissummary  = $("input[name=xissummary]").val();



        var LinkExtension = '&datefrom='+datefrom+'&dateto='+dateto+'&branchfrom='+branchfrom+'&branchto='+branchto+'&issummary='+issummary;
		//return false;
		window.location.href = path + '/exceldownloader.php?&docname=DCCRReport'+LinkExtension;
        return false;
    });


   branchRange('branchtoList', 'branchto');
   branchRange('branchfromList', 'branchfrom');

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
	dynamic_tr += "<td align='center'>Date</td>";
	dynamic_tr += "<td align='center'>Branch</td>";
	dynamic_tr += "<td align='center'>Bank</td>";
	dynamic_tr += "<td align='center'>Reference</td>";
	dynamic_tr += "<td align='center'>Reason Code</td>";
	dynamic_tr += "<td align='center'>Cash</td>";
	dynamic_tr += "<td align='center'>Check</td>";
	dynamic_tr += "<td align='center'>Offsite</td>";
	dynamic_tr += "<td align='center'>Cancelled</td>";
	dynamic_tr += "<td align='center'>Total Collection for Deposit</td>";
	dynamic_tr += "<td align='center'>Payment Thru Offsettings</td>";
	dynamic_tr += "<td align='center'>Total Collection</td>";
	dynamic_tr += "</tr>";

	$.ajax({
			//ajax process here...
			type:'post',
			dataType: 'json',
			data:$('form[name=frmdccr]').serialize(),
			url: 'pages/sfpm/ajax_requests/DCCRreport.php',
			success:function(resp){
				//success here...
				if(resp['response']=='success'){
					for(var i = 0; resp['data_handler'].length>i;i++){
							dynamic_tr += "<tr class='trlist'>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].xDate+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].BranchCode+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].BankName+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].Reference+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].ReasonID+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].xCash+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].xCheck+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].xOffsite+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].Canceled+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].PaymentThruOffseting+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].xoffseting+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].TotalCollection+"</td>";
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