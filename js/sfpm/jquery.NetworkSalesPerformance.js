/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 04, 2013
 */
var NetworkData;

$(function($){
    documentWriteSFMParamCustoms();
    doDatePickerMonthYearLoad('#Period');

    //Form even handler for doing process request...
    $('#SFPM-NSPForm').on('submit',function(){
        var BranchID = $('input[name="branch"]').val();
        var Period = $('input[name="Period"]').val();
        var pmg = $('select[name="pmg"]').val();
        var netFrom = $('input[name="netFromDisplay"]').val();
        var netTo = $('input[name="netToDisplay"]').val();

        if(BranchID == 0 || Period == ''  || pmg == ''  || netFrom == ''  || netTo == '' || $('input[name="branchList"]').val() == ""){
            dialogMessage('Please fill out all fields first before generating report.');
            return false;
        }

        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/sfpm/ajax_requests/NetworkSalesPerformance.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });

        return false;
    });

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
            var BranchID = ui.item.ID;
            var SFL = $('select[name="SFL"]').val(); //sales force level...

            $('#network-for-branch').hide();//hide network select options...
            _loaderNetwork('show');//show loader...

            $.ajax({
                async: false,
                cache: false,
                type: 'POST',
                url: 'pages/sfpm/ajax_requests/NetworkSalesPerformance.php',
                data: { 'action':'get_branch_networks', 'bID': BranchID, 'SFL':SFL },
                success: responseBranchNetworks
            });
        }
    });

    function _loaderNetwork(action){
        if(action == 'show'){
            $('#network-for-branch-loader').removeClass('hide');
            $('#network-for-branch-loader').show();
        }

        if(action == 'hide'){
            $('#network-for-branch-loader').addClass('hide');
            $('#network-for-branch-loader').hide();
            _clearInputFromToFields('netFrom-Display');
            _clearInputFromToFields('netTo-Display');
        }
    }

    //Function that will show / generate displays for table listing of query results report...
    function responseGenerateReport(response){
        var res = $.parseJSON(response);
        var html = '';

        $('#dialog-message').dialog('destroy');
        //If success let's loop the returned values from callback file...
        if(res.status == 'success'){
            for (var key in res.lists) {
                html+= '<tr class="tbl-td-rows" id="td-row-'+ key +'">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[key].Code +'</td>';

                //Prepare items display depending on PMG option selected...
                if(res.display_type == 'ALL'){
                    html+= '<td class="tbl-td-right td-bottom-border">';
                    html+= prepTDDisplay('CPI', NumberFormat(res.lists[key].CPI));
                    html+= prepTDDisplay('NCFT',  NumberFormat(res.lists[key].NCFT));
                    html+= prepTDDisplay('CFT',  NumberFormat(res.lists[key].CFT));
                    html+= '<div class="clear-with-border-bottom"></div>';

                    //Let's compute the total of PMGs...
                    var PMGTotal = parseFloat(res.lists[key].CPI) + parseFloat(res.lists[key].NCFT) + parseFloat(res.lists[key].CFT);

                    html+= prepTDDisplay('TOTAL', NumberFormat(String(PMGTotal)));
                    html+= '</td>';
                }else{
                    html+= '<td class="tbl-td-right td-bottom-border">';
                    html+= prepTDDisplay(res.lists[key].PMGType, res.lists[key].Sales);
                    html+= '</td>';
                }

                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[key].TotalNumberOfRecruits +'</td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[key].TotalNumberOfActives +'</td>';
                html+= '</tr>';
            }

            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
        }else{//If not display message for no records found...
            $('.tbl-td-rows td').text(res.message);
        }
    }

    //Method that will prepare / initialize DOM elements for network selections...
    function responseBranchNetworks(response){
        var res = $.parseJSON(response);
        var network = res.lists;
        var html = '';

        _loaderNetwork('hide');//hide loader...

        if(res.status == 'success'){
            if(network.length > 0){
                //Store data in browser now, so we don't need to make query everytime searching networks...
                NetworkData = _prepareNetworkDataForAutoCompleter(network);

                //Let's initialize autocompleter jquery ui in every elements now...
                initFromToInputsNetworkAutoComplete('netFrom-Display');
                initFromToInputsNetworkAutoComplete('netTo-Display');

                $('#network-for-branch').show();
            }
        }else{
            dialogMessage(res.message);
            $('#network-for-branch').hide();
        }

    }

    //Function that will prepare JSON items fetch from server, so it can be used in autocompleter jquery UI...
    function _prepareNetworkDataForAutoCompleter(data){
        var newData = $.map( data, function( item ) {
                            return {
                                label: '(' + item.Code + ') -- ' + item.Name,
                                value: { 'Code':item.Code,'Name':item.Name,'ID':item.ID }
                            }
                        });
        return newData;
    }

    //Method that will initialize autocompleter jquery UI for an element assigned.
    function initFromToInputsNetworkAutoComplete(DOMelement){
        $('#' + DOMelement).autocomplete({
            source: NetworkData,
            search: function(){  }, //do some notifications that source is still in process....
            select: function( event, ui ) { //select function...
                var Network = ui.item.value;
                var inputElem = DOMelement.split('-');

                $('#' + DOMelement).val('[ '+ Network.Code +' ] - ' + Network.Name);
                $('#' + inputElem[0]).val(Network.Code);

                return false;
            }
        });
    }

    function prepTDDisplay(label,value){
        var html = '';
        html+= '<div class="label-sales">'+ label +':</div><div class="label-sales-value">'+ value +'</div>';
        html+= '<div class="label-clear"></div>';

        return html;
    }

    function _clearInputFromToFields(DOMelement){
        var inputElem = DOMelement.split('-');

        $('#' + DOMelement).val('');
        $('#' + inputElem[0]).val('');
    }
});
