/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 06, 2013
 */
$(function($){
    documentWriteSFMParamCustoms();
    doDatePickerLoad('#dateFrom');
    doDatePickerLoad('#dateTo');
    initNumericOnlyAllowed('#num_records');

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

    //Event handler for submitting parameters process...
    $('#SFPM-TSRForm').on('submit',function(){
        var BranchID = $('input[name="branch"]').val();
        var dateFrom = $('input[name="dateFrom"]').val();
        var dateTo = $('input[name="dateTo"]').val();
        var rType = $('select[name="report_type"]').val();
        var sType = $('select[name="sales_type"]').val();
        var records = $('input[name="num_records"]').val();

        if(BranchID == 0 || dateFrom == '' || dateTo == '' || rType == '' || records == '' || $('input[name="branchList"]').val() == ""){
            dialogMessage('Please fill out all fields first before generating report.');
            return false;
        }

        if((rType == 'CUSTOMER' || rType == 'NETWORK') && sType == ''){
            dialogMessage('Please select sales type because you\'ve selected '+ rType +' report type.');
            return false;
        }

        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/sfpm/ajax_requests/TopSellingReport.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });

        return false;
    });

    //Event handler when report type changed...
    $('#report_type').on('change',function(){
        var rtype = $(this).val();
        if(rtype == 'NETWORK' || rtype == 'CUSTOMER'){
            $('#sales-type-options').show();
            $('#sales-type-options').removeClass('hide');
        }else{
            $('#sales-type-options').hide();
            $('#sales-type-options').addClass('hide');
            $('select[name="sales_type"]').get(0).selectedIndex = 0;
        }
    });

    //Function for processing table listing display...
    function responseGenerateReport(response){
        var res = $.parseJSON(response);
        var rtype = $('select[name="report_type"]').val();
        var html = '';

        $('#dialog-message').dialog('destroy');

        _hideTRHeaders();//hide all headers again...

        //let's now identify to which header columns would be displayed...
        if(rtype == 'PRODUCT'){ $('#product-tr-head').show(); $('#product-tr-head').removeClass('hide'); }
        if(rtype == 'CUSTOMER' || rtype == 'NETWORK'){ $('#customer-network-tr-head').show(); $('#customer-network-tr-head').removeClass('hide'); }

        //If success let's loop the returned values from callback file...
        if(res.status == 'success'){
            if(res.lists.length > 0){
                for(var x = 0; x < res.lists.length; x++){
                    if(rtype == 'PRODUCT'){//for product display...
                        html+= '<tr class="tbl-td-rows">';
                        html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].Code +'</td>';
                        html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Description +'</td>';
                        html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].ProductLine +'</td>';
                        html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalQtySold +'</td>';
                        html+= '</tr>';
                    }

                    if(rtype == 'CUSTOMER' || rtype == 'NETWORK'){//for product network or customer...
                        html+= '<tr class="tbl-td-rows">';
                        html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Code +'</td>';
                        html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Name +'</td>';
                        html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].AmtLessCPI +'</td>';
                        html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalBilledAmt +'</td>';
                        html+= '</tr>';
                    }
                }

                $('.tbl-td-rows').remove();
                $('.tbl-listing-div table').append(html);
            }
        }
    }

    //Function that will hide all table listing column headers...
    function _hideTRHeaders(){
        $('#product-tr-head').hide();
        $('#product-tr-head').addClass('hide');
        $('#customer-network-tr-head').hide();
        $('#customer-network-tr-head').addClass('hide');
    }
});


