/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 05, 2013
 */

$(function($){
    documentWriteSFMParamCustoms();
    doDatePickerLoad('#date');

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
        }
    });

    //Form even handler for doing process request...
    $('#IPM-DBRForm').on('submit',function(){
        var BranchID = $('input[name="branch"]').val();
        var Date = $('input[name="date"]').val();

        if(BranchID == 0 || Date == '' || $('input[name="branchList"]').val() == ''){
            dialogMessage('Please fill out all fields first before generating report.');
            return false;
        }

        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/bpm/ajax_requests/OnlineSalesInquiry.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });

        return false;
    });

    $('#btn-for-print').on('click','a',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        var BranchID = $('input[name="branch"]').val();
        var Date = $('input[name="date"]').val();

        window.location.href = path + '/exceldownloader.php?function=HOReportOnlineSalesInquiryQuery&docname=OnlineSalesInquiryReport&BranchID='+BranchID+'&Date='+Date;

        return false;
    });

    function responseGenerateReport(response){
        var res = $.parseJSON(response);
        var html = '';

        $('#dialog-message').dialog('destroy');

        //If success let's loop the returned values from callback file...
        if(res.status == 'success'){
            $('#CDDGS-less').text(res.CampaignToDateDGSLessCPI);
            $('#CDDGS').text(res.CampaignToDateDGS);
            $('#IA-less').text(res.InvoiceAmountLessCPI);
            $('#IA').text(res.InvoiceAmount);

            $('#TDGS-less').text(res.TodayTotalDGSLessCPI);
            $('#TDGS').text(res.TodayTotalDGS);
            $('#TIA-less').text(res.TodayTotalInvoiceAmountLessCPI);
            $('#TIA').text(res.TodayTotaInvoiceAmount);

            $('#btn-for-print').show();
            $('#btn-for-print').removeClass('hide');
        }else{
            $('#btn-for-print').hide();
            $('#btn-for-print').addClass('hide');
        }
    }

});


