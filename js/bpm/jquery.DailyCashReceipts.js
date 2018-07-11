/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 10, 2013
 */
$(function($){
    documentWriteSFMParamCustoms();
    doDatePickerLoad('#date');

    //Form even handler for doing process request...
    $('#BPM-DCRForm').on('submit',function(){
        var BranchID = $('input[name="branch"]').val();
        var Date = $('input[name="date"]').val();

        if(BranchID == '' || Date == ''){
            dialogMessage('Please fill out all fields first before generating report.');
            return false;
        }

        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/bpm/ajax_requests/DailyCashReceipts.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });

        return false;
    });

    $('#btn-for-print').on('click','a',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        var BranchID = $('input[name="branch"]').val();
        var Date = $('input[name="date"]').val();

        window.location.href = path + '/exceldownloader.php?function=HOReportDailyCashReceipts&docname=DailyCashReceiptsReport&BranchID='+BranchID+'&Date='+Date;

        return false;
    });

    function responseGenerateReport(response){
        var res = $.parseJSON(response);
        var html = '';

        $('#dialog-message').dialog('destroy');

        if(res.status == 'success'){
            $('#branch-text').text($('#branch option:selected').text());
            $('#date-text').text($('input[name="date"]').val());
            $('#total-cash').text(res.ORCashTotal);
            $('#total-checks').text(res.ORChequeTotal);
            $('#total-deposit').text(res.ORDepositTotal);
            $('#total-cancelled-cash').text(res.ORCashCancelledTotal);
            $('#total-cancelled-checks').text(res.ORChequeCancelledTotal);

            $('#btn-for-print').show();
            $('#btn-for-print').removeClass('hide');
        }else{
            dialogMessage(res.message);

            $('#btn-for-print').hide();
            $('#btn-for-print').addClass('hide');
        }
    }


    $('[name=branchList]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term},
                url     :   "pages/bpm/ajax_requests/DailyCashReceipts.php",
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
});

