/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 15, 2013
 */
$(function($){
    documentWriteSFMParamCustoms();
    doDatePickerLoad('#dateFrom');
    doDatePickerLoad('#dateTo');
    
    //Form even handler for doing process request...
    $('#IPM-TRForm').on('submit',function(){
        var BranchID = $('select[name="branch"]').val();
        var AsOfDate = $('input[name="AsOfDate"]').val();
        var keyword = $('input[name="keyword"]').val();
        
        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/ipm/ajax_requests/TransactionRegister.php',
            data: $(this).serialize(),
            success: responseGenerateReport
        });
        
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
                    html+= '<tr class="tbl-td-rows">';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].TxnDate +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].MovementType +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].RefTxnNo +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].Code +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].Product +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].IssuingBranch +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].Location1 +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].ReceivingBranch +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].Location2 +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].Qty +'</td>';
                    html+= '</tr>';
                }
                
                $('.tbl-td-rows').remove();
                $('.tbl-listing-div table').append(html);
            }
        }else{//If not display message for no records found...
            $('.tbl-td-rows td').text(res.message);
        }
    }
});

