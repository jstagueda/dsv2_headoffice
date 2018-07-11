$(document).ready(function(){
	$('[name=dateto], [name=datefrom]').datepicker();
	$("#generate_list").click(function(){
		//here process...
		$("#page").val(1);
		_doAjax();
		return false;
	});

	//print to excel..
	$('#btn-for-print').on('click',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        //var BranchID 	= $('#campaign_from').val();

		var datefrom 	= $('input[name=datefrom]').val();
        var dateto 		= $('input[name=dateto]').val();
        var branchfrom  = $('[name=branchfrom]').val();
        var branchto 	= $('[name=branchto]').val();

        var LinkExtension = '&datefrom='+datefrom+'&dateto='+dateto+'&branchfrom='+branchfrom+'&branchto='+branchto;
		//return false;
		window.location.href = path + '/exceldownloader.php?&docname=PaymentClassificationReport'+LinkExtension;
        return false;
    });


    branchRange('branchfromList', 'branchfrom');
    branchRange('branchtoList', 'branchto');
});

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
	dynamic_tr += "<table width='100%' cellpadding='0' border=0 cellspacing='0' class='bordergreen'>";
	dynamic_tr += "<tr class='trheader'>";
	dynamic_tr += "<td align='center'>Branch</td>";
	dynamic_tr += "<td align='center'>Mode of Payment</td>";
	dynamic_tr += "<td align='center'>Advance</td>";
	dynamic_tr += "<td align='center'>On-Time 38 Days</td>";
	dynamic_tr += "<td align='center'>Beyond 38 Days but Within 52 Days</td>";
	dynamic_tr += "<td align='center'>Beyond 38 Days</td>";
	dynamic_tr += "<td align='center'>On-Time 52 Days</td>";
	dynamic_tr += "<td align='center'> > 52 Days</td>";
	dynamic_tr += "<td align='center'> TOTAL </td>";
	dynamic_tr += "</tr>";
	var xBranchCode = "";
	var xModeOfPayment = "";
	var xTotalAdvance = 0;
	var xTotalOnTime38Ddays = 0;
	var xTotalBeyond38DdaysButWithin52 = 0;
	var xTotalBeyond38days 	= 0;
	var xTotalOnTime52Ddays	= 0;
	var xTotalx52daysOnwards	= 0;
	var xtotal = 0;
	var xoveralltotal = 0;

	$.ajax({
			//ajax process here...
			type:'post',
			dataType: 'json',
			data:$('form[name=frmPaymentClassification]').serialize(),
			url: 'pages/sfpm/ajax_requests/PaymentClassification.php',
			success:function(resp){
				//success here...
				if(resp['response']=='success'){
					for(var i = 0; resp['data_handler'].length>i;i++){


						    xtotal += parseFloat(resp['data_handler'][i].advance);
						    xtotal += parseFloat(resp['data_handler'][i].OnTime38Ddays);
						    xtotal += parseFloat(resp['data_handler'][i].Beyond38DdaysButWithin52);
						    xtotal += parseFloat(resp['data_handler'][i].Beyond38days);
						    xtotal += parseFloat(resp['data_handler'][i].OnTime52Ddays);
						    xtotal += parseFloat(resp['data_handler'][i].x52daysOnwards);

							dynamic_tr += "<tr class='trlist'>";
							dynamic_tr += "<td align='center' >"+(resp['data_handler'][i].BrancCode==xBranchCode?'':resp['data_handler'][i].BrancCode)+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].ModeOfPayment+"</td>";
							dynamic_tr += "<td align='center' >"+GinoNumberWithCommas(resp['data_handler'][i].advance)+"</td>";
							dynamic_tr += "<td align='center' >"+GinoNumberWithCommas(resp['data_handler'][i].OnTime38Ddays)+"</td>";
							dynamic_tr += "<td align='center' >"+GinoNumberWithCommas(resp['data_handler'][i].Beyond38DdaysButWithin52)+"</td>";
							dynamic_tr += "<td align='center' >"+GinoNumberWithCommas(resp['data_handler'][i].Beyond38days)+"</td>";
							dynamic_tr += "<td align='center' >"+GinoNumberWithCommas(resp['data_handler'][i].OnTime52Ddays)+"</td>";
							dynamic_tr += "<td align='center' >"+GinoNumberWithCommas(resp['data_handler'][i].x52daysOnwards);+"</td>";
							dynamic_tr += "<td align='center' >"+GinoNumberWithCommas(xtotal);+"</td>";
							dynamic_tr +="</tr>";

							xoveralltotal					+= parseFloat(xtotal);
							xTotalAdvance 					+= parseFloat(resp['data_handler'][i].advance);
							xTotalOnTime38Ddays 			+= parseFloat(resp['data_handler'][i].OnTime38Ddays);
							xTotalBeyond38DdaysButWithin52 	+= parseFloat(resp['data_handler'][i].Beyond38DdaysButWithin52);
							xTotalBeyond38days 				+= parseFloat(resp['data_handler'][i].Beyond38days);
							xTotalOnTime52Ddays				+= parseFloat(resp['data_handler'][i].OnTime52Ddays);
							xTotalx52daysOnwards			+= parseFloat(resp['data_handler'][i].x52daysOnwards);
							xtotal = 0;

							xBranchCode = resp['data_handler'][i].BrancCode;
							xModeOfPayment = resp['data_handler'][i].ModeOfPayment;


							if(xBranchCode==resp['data_handler'][i].BrancCode && xModeOfPayment == "OFFSET"){
								dynamic_tr += "<tr class='trlist'>";
								dynamic_tr += "<td align='center'></td>";
								dynamic_tr += "<td align='center'>TOTAL  COLLECTIONS & OFFSETTINGS</td>";
								dynamic_tr += "<td align='center'>"+GinoNumberWithCommas(xTotalAdvance)+"</td>";
								dynamic_tr += "<td align='center'>"+GinoNumberWithCommas(xTotalOnTime38Ddays)+"</td>";
								dynamic_tr += "<td align='center'>"+GinoNumberWithCommas(xTotalBeyond38DdaysButWithin52)+"</td>";
								dynamic_tr += "<td align='center'>"+GinoNumberWithCommas(xTotalBeyond38days)+"</td>";
								dynamic_tr += "<td align='center'>"+GinoNumberWithCommas(xTotalOnTime52Ddays)+"</td>";
								dynamic_tr += "<td align='center'>"+GinoNumberWithCommas(xTotalx52daysOnwards)+"</td>";
								dynamic_tr += "<td align='center'>"+GinoNumberWithCommas(xoveralltotal)+"</td>";
								dynamic_tr +="</tr>";

								xTotalAdvance				   = 0;
								xTotalOnTime38Ddays 		   = 0;
								xTotalBeyond38DdaysButWithin52 = 0;
								xTotalBeyond38days			   = 0;
								xTotalOnTime52Ddays			   = 0;
								xTotalx52daysOnwards 		   = 0;
								xoveralltotal				   = 0;

							}

					}
				}else{
							dynamic_tr += '<tr class="trlist">';
							dynamic_tr += '<td colspan="6" align="center" style="color:red;">No result found.</td>';
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

function GinoNumberWithCommas(x) {
	var y=0;
	if(isNaN(x)){
		x = 0;
	}
	y = Number(x).toFixed(2);
    return y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

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