/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 02, 2013
 */
$(function($){
    documentWriteSFMParamCustoms();
    doDatePickerMonthYearLoad('#Period');

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
        var Period = $('input[name="Period"]').val();

        if(BranchID == 0 || Period == '' || $('input[name="branchList"]').val() == ''){
            dialogMessage('Please fill out all fields first before generating report.');
            return false;
        }

        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/bpm/ajax_requests/DailyBulletReport.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });

        return false;
    });

    $('#btn-for-print').on('click','a',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        var BranchID = $('input[name="branch"]').val();
        var Date = $('input[name="Period"]').val();

        window.location.href = path + '/exceldownloader.php?function=HOReportDailyBulletReportQuery&docname=DailyBulletReport&BranchID='+BranchID+'&Date='+Date;

        return false;
    });

    //Generation of records display process...
    function responseGenerateReport(response){
        var res = $.parseJSON(response);
        var html = '';

        $('#dialog-message').dialog('destroy');

        //If success let's loop the returned values from callback file...
        if(res.status == 'success'){
            if(res.lists.length > 0){
                for(var x = 0; x < res.lists.length; x++){
                    html+= '<tr class="tbl-td-rows" id="td-row-'+ res.lists[x].PMGType +'">';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].PMGType +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalDGSAmount +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalCSPAmount +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalCSPLessCPIAmt +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalInvoiceAmount +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].TotalUnitsSold +'</td>';
                    html+= '</tr>';
                }

                $('.tbl-td-rows').remove();
                $('.tbl-listing-div table').append(html);

                $('#btn-for-print').show();
                $('#btn-for-print').removeClass('hide');
            }else{
                $('#btn-for-print').hide();
                $('#btn-for-print').addClass('hide');
            }
        }else{//If not display message for no records found...
            $('.tbl-td-rows td').text(res.message);
            $('#btn-for-print').hide();
            $('#btn-for-print').addClass('hide');
        }
    }

});


