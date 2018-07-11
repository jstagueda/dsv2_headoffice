/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 11, 2013
 */
$(function($){
    documentWriteSFMParamCustoms();
    doDatePickerLoad('#AsOfDate');
    
    //Form even handler for doing process request...
    $('#IPM-VRForm').on('submit',function(){
        var BranchID = $('select[name="branch"]').val();
        var AsOfDate = $('input[name="AsOfDate"]').val();
        var keyword = $('input[name="keyword"]').val();
        
        if(BranchID == '' || AsOfDate == '' || keyword == ''){
            dialogMessage('Please fill out "branch", "keyword" and "as of date" fields first before generating report.');
            return false;
        }
        
        dialogMessage('Generating report, please wait...');
        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pages/ipm/ajax_requests/ValuationReport.php',
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
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].ProdLineCode +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].ItemCode +'</td>';
                    html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].ItemDescription +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].ProductPrice +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].SOH +'</td>';
                    html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalValue +'</td>';
                    html+= '</tr>';
                }
                
                $('.tbl-td-rows').remove();
                $('.tbl-listing-div table').append(html);
            }
        }else{//If not display message for no records found...
            $('.tbl-td-rows').remove();
            dialogMessage(res.message);
        }
    }
});


