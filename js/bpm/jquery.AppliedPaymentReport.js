/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 11, 2013
 */
var ac_level = '';
var ac_branch = 0;
var ac_usedID = true;

$(function($){

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
            ac_branch = $('input[name="branch"]').val();
        }
    });

    documentWriteSFMParamCustoms();
    doDatePickerLoad('#date');
    initAutoCompleterAccountsByBranch('accountNo-Display');

    //Form even handler for doing process request...
    $('#BPM-APRForm').on('submit',function(){
        var BranchID = $('input[name="branch"]').val();
        var Date = $('input[name="date"]').val();
        var AccountNo = $('input[name="accountNoDisplay"]').val();

        if(BranchID == '' || Date == '' || AccountNo == ''){
            dialogMessage('Please fill out all fields first before generating report.');
            return false;
        }

        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/bpm/ajax_requests/AppliedPaymentReport.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });

        return false;
    });

    $('input[name="accountNoDisplay"]').on('change',function(){
        if(ac_branch <= 0 || ac_branch == ''){
            $('#branch').focus();
            $(this).val('');
        }
    });

    $('#btn-for-print').on('click','a',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        var BranchID = $('input[name="branch"]').val();
        var Date = $('input[name="date"]').val();
        var AccountNo = $('input[name="accountNo"]').val();

        window.location.href = path + '/exceldownloader.php?function=HOReportAppliedPaymentReportQuery&docname=AppliedPaymentReport&BranchID='+BranchID+'&Account='+AccountNo+'&Date='+Date;

        return false;
    });

    function responseGenerateReport(response){
        var res = $.parseJSON(response);
        var html = '';

        $('#dialog-message').dialog('destroy');

        //If success let's loop the returned values from callback file...
        if(res.status == 'success'){
            if(res.lists.length > 0){
                for(var x = 0; x < res.lists.length; x++){
                    html+= '<tr class="tbl-td-rows">';
                    html+= '<td class="tbl-td-center tbl-td-vertical-top td-bottom-border">'+ $('[name=branchList]').val() +'</td>';
                    html+= '<td class="tbl-td-center tbl-td-vertical-top td-bottom-border">'+ res.lists[x].PaymentDate +'</td>';
                    html+= '<td class="td-bottom-border" colspan="6">';
                    html+= '<div class="tbl-float-left tbl-memo-details">';
                    html+= '    <div class="tbl-float-inherit bold">OR No.:</div>';
                    html+= '    <div class="tbl-float-right">'+ res.lists[x].ORNumber +'</div>';
                    html+= '    <div class="tbl-clear clear-xsmall"></div>';
                    html+= '    <div class="tbl-float-inherit bold">Amount:</div>';
                    html+= '    <div class="tbl-float-right">'+ res.lists[x].Amount +'</div>';
                    html+= '    <div class="tbl-clear clear-xsmall"></div>';
                    html+= '    <div class="tbl-float-inherit bold">Invoice No.:</div>';
                    html+= '    <div class="tbl-float-right">'+ res.lists[x].InvoiceNo +'</div>';
                    html+= '    <div class="tbl-clear clear-xsmall"></div>';
                    html+= '    <div class="tbl-float-inherit bold">Invoice Date:</div>';
                    html+= '    <div class="tbl-float-right">'+ res.lists[x].InvoiceDate +'</div>';
                    html+= '    <div class="tbl-clear clear-xsmall"></div>';
                    html+= '    <div class="tbl-float-inherit bold">Invoice Due Date:</div>';
                    html+= '    <div class="tbl-float-right">'+ res.lists[x].InvoiceDueDate +'</div>';
                    html+= '    <div class="tbl-clear clear-xsmall"></div>';
                    html+= '    <div class="tbl-float-inherit bold">Amount Applied:</div>';
                    html+= '    <div class="tbl-float-right">'+ res.lists[x].AmountApplied +'</div>';
                    html+= '    <div class="tbl-clear clear-xsmall"></div>';
                    html+= '</div>';
                    html+= '</td>';
                    html+= '</tr>';
                }

                $('.tbl-td-rows').remove();
                $('.tbl-listing-div table').append(html);

                $('#btn-for-print').show();
                $('#btn-for-print').removeClass('hide');
            }
        }else{//If not display message for no records found...
            $('.tbl-td-rows').remove();
            dialogMessage(res.message);

            $('#btn-for-print').hide();
            $('#btn-for-print').addClass('hide');
        }
    }


});


