/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 09, 2013
 */
var ac_usedID = false;
var lvl_cp = true; //variable identifier if level can purchase...
var ac_lower_level = true;

$(function($){
    documentWriteSFMParamCustoms();
    initAutoCompleterAccountsByBranch('accountFrom-Display');
    initAutoCompleterAccountsByBranch('accountTo-Display');
    ac_branch = $('input[name="branch"]').val();
    ac_level = $('input[name="SFL"]').val();
    
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

    //Form even handler for doing process request...
    $('#SFPM-ABIForm').on('submit',function(){
        var BranchID = $('input[name="branch"]').val();
        var SFL = $('select[name="SFL"]').val();
        var accountFrom = $('input[name="accountFromDisplay"]').val();
        var accountTo = $('input[name="accountToDisplay"]').val();

        if(BranchID == 0 || SFL == ''  || accountFrom == ''  || accountTo == '' || $('input[name="branchList"]').val() == ""){
            dialogMessage('Please fill out all fields first before generating report.');
            return false;
        }

        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/sfpm/ajax_requests/AccountBalanceInquiry.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });

        return false;
    });

    //Event handler when sales force level select option changes...
    $('select[name="SFL"]').on('change',function(){
        $('input[name="accountFromDisplay"]').val('');
        $('input[name="accountToDisplay"]').val('');
        var selfVal = $(this).val();
        selfVal = selfVal.split('_');// get the value of can_purchase field for SF levels...

        if(selfVal[1] != undefined){
            if(selfVal[1] == 1){ lvl_cp = true; $('#account-can-purchase').show(); $('#account-can-purchase').removeClass('hide'); $('#account-higher').hide(); }
            if(selfVal[1] == 0){ lvl_cp = false; $('#account-higher').show(); $('#account-higher').removeClass('hide'); $('#account-can-purchase').hide(); }
        }
        $('.tbl-td-rows').remove();

        $('#btn-for-print').hide();
        $('#btn-for-print').addClass('hide');

        ac_lower_level = lvl_cp;
        ac_level = $(this).find('option:selected').data('sflid');
    });

    //Event handler for getting networks of higher level accounts...
    $('.tbl-listing-div table').on('click','a',function(){
        var self = $(this);
        var ibmid = self.data('ibmid');

        dialogMessage('Getting network details, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/sfpm/ajax_requests/AccountBalanceInquiry.php',
            data: { 'action':'get_networks','IBMID':ibmid,'branch':ac_branch},
            success: responseGenerateNetworks
        });

        return false;
    });

    $('#btn-for-print').on('click','a',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        var BranchID = $('input[name="branch"]').val();
        var SFL = $('select[name="SFL"]').val();
        var accountFromDisplay = $('input[name="accountFromDisplay"]').val();
        var accountToDisplay = $('input[name="accountToDisplay"]').val();
        var accountFrom = $('input[name="accountFrom"]').val();
        var accountTo = $('input[name="accountTo"]').val();
        var CLC = $('input[name="CLC"]').val();

        window.location.href = path + '/exceldownloader.php?function=HOReportAccountBalanceInquiryQuery&docname=AccountBalanceInquiryReport&BranchID='+BranchID+'&SFL='+SFL+'&AcctFrom='+accountFrom+'&AcctFromDisplay='+accountFromDisplay+'&AcctTo='+accountTo+'&AcctToDisplay='+accountToDisplay+'&CLC='+CLC;

        return false;
    });

    //Reponse function generator used for displaying list of results from form.
    function responseGenerateReport(response){
        var res = $.parseJSON(response);
        var html = '';

        $('#dialog-message').dialog('destroy');

        if(res.status == 'success'){
            if(res.lists.length > 0){
                var x = 0;

                if(lvl_cp){
                    for(x = 0; x < res.lists.length; x++){
                        html+= '<tr class="tbl-td-rows" style="width: 761px;">';
                        html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Code +'</td>';
                        html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Name +'</td>';
                        html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].desc_code +'</td>';
                        html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalOutstandingBalance +'</td>';
                        html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalOverDueBalance +'</td>';
                        html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalPenaltyAmount +'</td>';
                        html+= '</tr>';
                    }
                }else{
                    for(x = 0; x < res.lists.length; x++){
                        html+= '<tr class="tbl-td-rows" style="width: 761px;">';
                        html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Code +'</td>';
                        html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Name +'</td>';
                        html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].desc_code +'</td>';
                        html+= '<td class="tbl-td-center td-bottom-border"><a data-ibmid="'+ res.lists[x].ID +'" href="javascript:void(0);">View Networks</a></td>';
                        html+= '</tr>';
                    }
                }

                $('.tbl-td-rows').remove();
                $('.tbl-listing-div table').append(html);

                $('#btn-for-print').show();
                $('#btn-for-print').removeClass('hide');
            }
        }else{
            $('.tbl-td-rows').remove();
            dialogMessage(res.message);

            $('#btn-for-print').hide();
            $('#btn-for-print').addClass('hide');
        }
    }

    //Response function generator used in displaying popup / dialog of networks under a select higher level account...
    function responseGenerateNetworks(response){
        var res = $.parseJSON(response);
        var html = '', html_holder = '';

        //Prepare HTML display for popup networks lists...
        html_holder = '<div id="Dialog-ABI-Networks" style="margin-left: auto;margin-right: auto;padding:10px 0;">';
        html_holder+= '<div class="tbl-holder-autoscroller">';
        html_holder+= '     <div class="tbl-content-autoscroller">';
        html_holder+= '<table border="1" cellspacing="3" cellpadding="3" style="width: 760px;border-collapse: collapse;border-color: #959F63;">';
        html_holder+= '    <tr>';
        html_holder+= '        <th width="10%" class="td-bottom-border">Account No.</th>';
        html_holder+= '        <th width="20%" class="td-bottom-border">Account Name</th>';
        html_holder+= '        <th width="8%" class="td-bottom-border">Account Level</th>';
        html_holder+= '        <th width="12%" class="td-bottom-border">Total Outstanding Balance</th>';
        html_holder+= '        <th width="12%" class="td-bottom-border">Total Overdue Balance</th>';
        html_holder+= '        <th width="12%" class="td-bottom-border">Total Penalty Amount</th>';
        html_holder+= '    </tr>';
        html_holder+= '    <tr class="tbl-td-rows">';
        html_holder+= '        <td class="tbl-td-center td-bottom-border" colspan="6">Fetching networks...</td>';
        html_holder+= '    </tr>';
        html_holder+= '</table>';
        html_holder+= '</div></div>';
        html_holder+= '</div>';

        //Show the dialog...
        dialogFormListsEtcDisplay('Account Networks',html_holder,{ width: '800',position: 'center top' });

        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                html+= '<tr class="tbl-td-rows" style="width: 761px;">';
                html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Code +'</td>';
                html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Name +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].desc_code +'</td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalOutstandingBalance +'</td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalOverDueBalance +'</td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalPenaltyAmount +'</td>';
                html+= '</tr>';
            }

            $('#Dialog-ABI-Networks .tbl-td-rows').remove();
            $('#Dialog-ABI-Networks table').append(html);
        }else{
            html = '<tr class="tbl-td-rows">';
            html+= '<td class="tbl-td-center td-bottom-border" colspan="6">'+ res.message +'</td>';
            html+= '</tr>';

            $('#Dialog-ABI-Networks .tbl-td-rows').remove();
            $('#Dialog-ABI-Networks table').append(html);
        }

        $('#dialog-message').dialog('destroy');
    }

});


