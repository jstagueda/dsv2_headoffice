/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 21, 2013
 */
var list_limit = 5;
var pageListerMethod;

$(function($){
    documentWriteSFMParamCustoms();
    $('#SFM-rankForm').on('submit', function(e){
        var self = $(this);
        var rcodeID = self.find('input[name="rcodeID"]').val();
        var rDesc = self.find('input[name="rdescription"]').val();
        var rOrder = self.find('input[name="rorder"]').val();
        var rstartDate = self.find('input[name="rstartDate"]').val();
        var rendDate = self.find('input[name="rendDate"]').val();
        var raction = self.find('input[name="raction"]').val();
        var rpost = {}, loaderTxt = '';
        
        rpost = { 'code':rcodeID, 'desc':rDesc,'order':rOrder,'sDate':rstartDate, 'eDate':rendDate, 'action':raction };
        
        $('#frmbtnRank').hide();
        
        if(raction == 'insert') loaderTxt = '<b>Creating new rank...</b>';
        if(raction == 'update') loaderTxt = '<b>Updating rank...</b>';
        
        ShowHideLoader(true,loaderTxt);
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/rank_ajax.php',
            type: 'POST',
            data: rpost,
            success: function(response){ 
                frmResponse( response,self );
                
                ShowHideLoader(false,' ');
                $('#frmbtnRank').show();
            }
        });
        
        e.preventDefault();
    });
    
    $('.tbl-listing-div table').on('click','a.rcode', function(e){
        var self = $(this);
        var codeID = self.data('code');
        
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/rank_ajax.php',
            type: 'POST',
            data: {'action':'edit', 'codeID':codeID},
            success: function(response){ setInputsRankDetails(response); }
        });
        
        e.preventDefault();
    });
    
    pageListerMethod = function ranksGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/sfm/param_ajax_calls/rank_ajax.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ ranksLists(response); }
                                });
                            }
    
    function ranksLists(response){
        var res = $.parseJSON(response);
        var html = '',checker = '';
        
        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                if(checkIfInIdsforDelete(res.lists[x].codeID)){
                    checker = ' checked="checked" ';
                }else{
                    checker = ' ';
                }
                
                html+= '<tr class="tbl-td-rows" id="td-row-'+ res.lists[x].codeID +'">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].codeID +'</td>';
                html+= '<td class="td-bottom-border"><a class="rcode" data-code="'+ res.lists[x].codeID +'" href="#">'+ res.lists[x].description +'</a></td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].rank_order +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].start_date +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].end_date +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border"><input type="checkbox"'+ checker +'value="'+ res.lists[x].codeID +'" name="action_chk[]" class="action_chk" /></td>';
                html+= '</tr>';
            }
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
            $('#btn-for-checkbox').removeClass('hide');
        }else{
            $('.tbl-td-rows td').text(res.message);
        }
        
        if(res.total > list_limit){ generatePaginationLinks(res.total,list_limit); }
        else{ $('#tblPageNavigation').html(''); }
        
        $('#SFM-rankForm').find('input[name="rcodeID"]').val(res.current_codeID);
        clearFields($('#SFM-rankForm'));
    }
    
    ajaxDeletePath = 'pages/sfm/param_ajax_calls/rank_ajax.php';
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    initCheckBoxSelectSaveIds();
    initActionDeleteSaveIds();
    
    function frmResponse(response, obj){
        var res = $.parseJSON(response);
        
        if(res.status == 'success'){
            dialogMessage(res.message);
            
            if(res.from_action == 'insert'){
                obj.find('input[name="rcodeID"]').val(res.nxt_code);              
            }
            pageListerMethod(0,list_limit);
            
        }else{
            dialogMessage(res.message);
        }
    }
    
    function setInputsRankDetails(response){
        var res = $.parseJSON(response);
        var form = $('#SFM-rankForm');
        
        form.find('input[name="rcodeID"]').val(res.data.codeID);
        form.find('input[name="rdescription"]').val(res.data.description);
        form.find('input[name="rorder"]').val(res.data.rank_order);
        form.find('input[name="rstartDate"]').val(res.data.start_date);
        form.find('input[name="rendDate"]').val(res.data.end_date);
        form.find('input[name="raction"]').val('update');
        form.find('input[name="btnNewRank"]').val('Update Rank');
    }
    
    function clearFields(obj){
        obj.find('input[name="rdescription"]').val('');
        obj.find('input[name="rorder"]').val('');
        obj.find('input[name="rstartDate"]').val('');
        obj.find('input[name="rendDate"]').val('');
        obj.find('input[name="raction"]').val('insert');
        obj.find('input[name="btnNewRank"]').val('Create New Rank');
    }
    
    doDatePickerLoad("#rstartDate");
    doDatePickerLoad("#rendDate");
    
});


