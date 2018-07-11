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
	 var issummary 	= 1;
	 $('input[name=issummary]').change(function(){
			issummary = $(this).val();
			$("input[name=xissummary]").val(issummary);
	})

	$('#btn-for-print').on('click',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        //var BranchID 	= $('#campaign_from').val();

		var campaign_from 	= $('[name=campaign_from]').val();
        var branchfrom 		= $('[name=branchfrom]').val();
        var branchto 		= $('[name=branchto]').val();




        var LinkExtension = '&campaign_from='+campaign_from+'&branchfrom='+branchfrom+'&branchto='+branchto;
		//return false;
		window.location.href = path + '/exceldownloader.php?&docname=ContractorsReport'+LinkExtension;
        return false;
    });

});

function branchRange(field, hidden){
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
            $('[name='+hidden+']').val(ui.item.ID);
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
	dynamic_tr+="<td align='center'>IBD CODE</td>";
	dynamic_tr+="<td align='center'>IBD NAME</td>";
	dynamic_tr+="<td align='center'>CAMPAIGN</td>";
	dynamic_tr+="<td align='center'>DGS (CFT & NCFT)</td>";
	dynamic_tr+="<td align='center'>CPI</td>";
	dynamic_tr+="<td align='center'>TOTAL</td>";
	dynamic_tr+="<td align='center'>CF RATE</td>";
	dynamic_tr+="<td align='center'>DGS (LY)</td>";
	dynamic_tr+="<td align='center'>INCREASED/ (DECREASED) IN DGS</td>";
	dynamic_tr+="<td align='center'>GB RATE</td>";
	dynamic_tr+="<td align='center'>GROWTH BONUS</td>";
	dynamic_tr+="<td align='center'>TOTAL EARNINGS</td>";
	dynamic_tr+="<td align='center'>VAT</td>";
	dynamic_tr+="<td align='center'>W/TAX</td>";
	dynamic_tr+="<td align='center'>EARNINGS AFTER TAX</td>";
	dynamic_tr += "</tr>";

	var TotalDGS					 = 0;
	var TotalCPI                     = 0;
	var Totalxtotal                  = 0;
	var TotalCfCreate                = 0;
	var TotalDGSLY                   = 0;
	var TotalIncreasedDecreasedinDGS = 0;
	var TotalGBRate                  = 0;
	var TotalGrowthBonus             = 0;
	var TotalTotalEarnings           = 0;
	var TotalVAT                     = 0;
	var TotalWithTax                 = 0;
	var TotalEarningsAfterTax        = 0;


	$.ajax({
			//ajax process here...
			type:'post',
			dataType: 'json',
			data:$('form[name=frmContractorsfeeandGrowthBonus]').serialize(),
			url: 'pages/sfpm/ajax_requests/ContractorsfeeandGrowthBonusReport.php',
			success:function(resp){
				//success here...
				if(resp['response']=='success'){
					for(var i = 0; resp['data_handler'].length>i;i++){
							dynamic_tr += "<tr class='trlist'>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].IBDCode+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].IBDName+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].Campaign+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].DGS+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].CPI+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].xtotal+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].CfCreate+"</td>";
							//dynamic_tr += "<td align='center' >"+resp['data_handler'][i].CFAmount+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].DGSLY+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].IncreasedDecreasedinDGS+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].GBRate+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].GrowthBonus+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].TotalEarnings+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].VAT+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].WithTax+"</td>";
							dynamic_tr += "<td align='center' >"+resp['data_handler'][i].EarningsAfterTax+"</td>";
							dynamic_tr +="</tr>";

							TotalDGS						+= resp['data_handler'].DGS;
							TotalCPI                        += resp['data_handler'].CPI;
							Totalxtotal                     += resp['data_handler'].xtotal;
							TotalCfCreate                   += resp['data_handler'].CfCreate;
							TotalDGSLY                      += resp['data_handler'].DGSLY;
							TotalIncreasedDecreasedinDGS    += resp['data_handler'].IncreasedDecreasedinDGS;
							TotalGBRate                     += resp['data_handler'].GBRate;
							TotalGrowthBonus                += resp['data_handler'].GrowthBonus;
							TotalTotalEarnings              += resp['data_handler'].TotalEarnings;
							TotalVAT                        += resp['data_handler'].VAT;
							TotalWithTax                    += resp['data_handler'].WithTax;
							TotalEarningsAfterTax           += resp['data_handler'].EarningsAfterTax;

					}

					dynamic_tr += "<tr class='trlist'>";
					dynamic_tr += "<td align='center' ></td>";
					dynamic_tr += "<td align='center' ></td>";
					dynamic_tr += "<td align='center' >TOTAL:</td>";
					dynamic_tr += "<td align='center' >"+TotalDGS+"</td>";
					dynamic_tr += "<td align='center' >"+TotalCPI+"</td>";
					dynamic_tr += "<td align='center' >"+Totalxtotal+"</td>";
					dynamic_tr += "<td align='center' >"+TotalCfCreate+"</td>";
					dynamic_tr += "<td align='center' >"+TotalDGSLY+"</td>";
					dynamic_tr += "<td align='center' >"+TotalIncreasedDecreasedinDGS+"</td>";
					dynamic_tr += "<td align='center' >"+TotalGBRate+"</td>";
					dynamic_tr += "<td align='center' >"+TotalGrowthBonus+"</td>";
					dynamic_tr += "<td align='center' >"+TotalTotalEarnings+"</td>";
					dynamic_tr += "<td align='center' >"+TotalVAT+"</td>";
					dynamic_tr += "<td align='center' >"+TotalWithTax+"</td>";
					dynamic_tr += "<td align='center' >"+TotalEarningsAfterTax+"</td>";
					dynamic_tr += "</tr>";

				}else{
					dynamic_tr += '<tr class="trlist">';
                    dynamic_tr += '<td colspan="15" align="center" style="color:red;">No result found.</td>';
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