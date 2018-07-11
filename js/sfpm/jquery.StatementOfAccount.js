/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 08, 2013
 */
var AccountData;
var ac_usedID = true;
var ac_level = 0;
var ac_lower_level = true;

$(function($){
    documentWriteSFMParamCustoms();
    doDatePickerLoad('#dateFrom');
    doDatePickerLoad('#dateTo');
    initAutoCompleterAccountsByBranch('accountNo-Display');
    ac_branch = $('input[name="branch"]').val();

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

    $('#SFPM-SOAForm').on('submit',function(){
        var BranchID = $('input[name="branch"]').val();
        var AccountNo = $('input[name="accountNo"]').val();
        var dateFrom = $('input[name="dateFrom"]').val();
        var dateTo = $('input[name="dateTo"]').val();
        var ttype = $('select[name="ttype"]').val();

        if(BranchID == 0 || dateFrom == '' || dateTo == '' || AccountNo == '' || ttype == '' || $('input[name="branchList"]').val() == ""){
            dialogMessage('Please fill out all fields first before generating report.');
            return false;
        }

        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/sfpm/ajax_requests/StatementOfAccount.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });

        return false;
    });

    $('#btn-for-print').on('click','a',function(){
        var path = window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');
        var BranchID = $('input[name="branch"]').val();
        var AccountNo = $('input[name="accountNo"]').val();
        var dateFrom = $('input[name="dateFrom"]').val();
        var dateTo = $('input[name="dateTo"]').val();
        var ttype = $('select[name="ttype"]').val();

        window.location.href = path + '/exceldownloader.php?function=HOReportStatementOfAccountQuery&docname=StatementOfAccountReport&BranchID='+BranchID+'&AccountNo='+AccountNo+'&dateFrom='+dateFrom+'&dateTo='+dateTo+'&ttype='+ttype;

        return false;
    });

    function responseGenerateReport(response){
        var res = $.parseJSON(response);
        var html = '';

        $('#dialog-message').dialog('destroy');

        if(res.status == 'success'){
            if(res.lists.length > 0){
                for(var x = 0; x < res.lists.length; x++){
                    html+= '<tr class="tbl-td-rows">';
                    html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Date +'</td>';
                    html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].MemoType +'</td>';
                    html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].DocumentNo +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalAmount +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].RunningBalance +'</td>';
                    html+= '</tr>';
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

    function responseBranchAccounts(response){
        var res = $.parseJSON(response);
        var network = res.lists;
        var html = '';

        _loaderNetwork('hide');//hide loader...

        if(res.status == 'success'){
            if(network.length > 0){
                //Store data in browser now, so we don't need to make query everytime searching networks...
                AccountData = _prepareAccountsDataForAutoCompleter(network);
                //Let's initialize autocompleter jquery ui in every elements now...
                initFromToInputsAccountAutoComplete('accountNo-Display');

                $('#account-for-branch').show();
            }
        }else{
            dialogMessage(res.message);
            $('#account-for-branch').hide();
        }
    }

    function _loaderNetwork(action){
        if(action == 'show'){
            $('#account-for-branch-loader').removeClass('hide');
            $('#account-for-branch-loader').show();
        }

        if(action == 'hide'){
            $('#account-for-branch-loader').addClass('hide');
            $('#account-for-branch-loader').hide();
            _clearInputFromToFields('accountNo-Display');
        }
    }

    //Function that will prepare JSON items fetch from server, so it can be used in autocompleter jquery UI...
    function _prepareAccountsDataForAutoCompleter(data){
        var newData = $.map( data, function( item ) {
                            return {
                                label: '(' + item.Code + ') -- ' + item.Name,
                                value: { 'Code':item.Code,'Name':item.Name,'ID':item.ID }
                            }
                        });
        return newData;
    }

    //Method that will initialize autocompleter jquery UI for an element assigned.
    function initFromToInputsAccountAutoComplete(DOMelement){
        $('#' + DOMelement).autocomplete({
            source: AccountData,
            search: function(){  }, //do some notifications that source is still in process....
            select: function( event, ui ) { //select function...
                var Account = ui.item.value;
                var inputElem = DOMelement.split('-');

                $('#' + DOMelement).val('[ '+ Account.Code +' ] - ' + Account.Name);
                $('#' + inputElem[0]).val(Account.ID);

                return false;
            }
        });
    }

    function _clearInputFromToFields(DOMelement){
        var inputElem = DOMelement.split('-');

        $('#' + DOMelement).val('');
        $('#' + inputElem[0]).val('');
    }
});

