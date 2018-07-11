/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 23, 2013
 */
var list_limit = 5;
var pageListerMethod;

$(function($){
    documentWriteSFMParamCustoms();
    $('#SFM-levelForm').on('submit', function(e){
        var self = $(this);
        var lrank = self.find('select[name="lrank"]').val();
        var lcodeID = self.find('input[name="lcodeID"]').val();
        var labbrv = self.find('input[name="labbrv"]').val();
        var lDesc = self.find('input[name="ldescription"]').val();
        var lstatus = self.find('input[name="lstatus"]').val();
        var lcan_purchase = self.find('select[name="lcan_purchase"]').val();
        var lhas_downline = self.find('input[name="ldownline"]').val();
        var lpaccount = self.find('input[name="lpaccount"]').val();
        var lclc = self.find('select[name="lCLC"]').val();
        var ltrade = self.find('select[name="ltrade"]').val();
        var lstartDate = self.find('input[name="lstartDate"]').val();
        var lendDate = self.find('input[name="lendDate"]').val();
        var laction = self.find('input[name="laction"]').val();
        var lpost = {}, loaderTxt = '';
        
        lpost = { 'rank':lrank,'code':lcodeID, 'abbrv':labbrv,'desc':lDesc, 'status':lstatus,'can_purchase':lcan_purchase,
                  'has_downline':lhas_downline,'has_paccount':lpaccount,'CLC':lclc,'Trade':ltrade,'sDate':lstartDate, 'eDate':lendDate, 'action':laction };
        
        $('#frmbtnLevel').hide();
        
        if(laction == 'insert') loaderTxt = '<b>Creating new level...</b>';
        if(laction == 'update') loaderTxt = '<b>Updating level...</b>';
        
        ShowHideLoader(true,loaderTxt);
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/level_ajax.php',
            type: 'POST',
            data: lpost,
            success: function(response){ 
                frmResponse( response,self );
                
                ShowHideLoader(false,' ');
                $('#frmbtnLevel').show();
            }
        });
        
        e.preventDefault();
    });
    
    //For listing, when item name was clicked
    $('.tbl-listing-div table').on('click','a.lcode', function(e){
        var self = $(this);
        var codeID = self.data('code');
        
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/level_ajax.php',
            type: 'POST',
            data: {'action':'edit', 'codeID':codeID},
            success: function(response){ setInputsLevelDetails(response); }
        });
        
        e.preventDefault();
    });
    
    //For checkboxes value, when item name was clicked
    $('input[name="ldownline"]').on('click', function(){
        var self = $(this);
        
        if(self.is(':checked')) self.val(1);
        else self.val(0);
    });
    
    $('input[name="lpaccount"]').on('click', function(){
        var self = $(this);
        
        if(self.is(':checked')) self.val(1);
        else self.val(0);
    });
    
    pageListerMethod = function levelGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/sfm/param_ajax_calls/level_ajax.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ levelLists(response); }
                                });
                            }
                            
    function levelLists(response){
        var res = $.parseJSON(response);
        var html = '', txt_cp = '',checker = '';
        
        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                if(checkIfInIdsforDelete(res.lists[x].codeID)){
                    checker = ' checked="checked" ';
                }else{
                    checker = ' ';
                }
                
                html+= '<tr class="tbl-td-rows" id="td-row-'+ res.lists[x].codeID +'">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].rankCodeID +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].codeID +'</td>';
                html+= '<td class="td-bottom-border"><a class="lcode" data-code="'+ res.lists[x].codeID +'" href="#">'+ res.lists[x].description +'</a></td>';
                
                if(res.lists[x].can_purchase == 0) txt_cp = 'No';
                else txt_cp = 'Yes';
                
                html+= '<td class="tbl-td-center td-bottom-border">'+ txt_cp +'</td>';
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
        
        if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
        else $('#tblPageNavigation').html('');
        
        $('#SFM-levelForm').find('input[name="lcodeID"]').val(res.current_codeID);
        clearFields($('#SFM-levelForm'));
    }
    
    ajaxDeletePath = 'pages/sfm/param_ajax_calls/level_ajax.php';
    getRanksForSelect();
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    initCheckBoxSelectSaveIds();
    initActionDeleteSaveIds();
    
    function frmResponse(response, obj){
        var res = $.parseJSON(response);
        
        if(res.status == 'success'){
            dialogMessage(res.message);
            
            if(res.from_action == 'insert'){
                obj.find('input[name="lcodeID"]').val(res.nxt_code);
            }
                        
            pageListerMethod(0,list_limit);
        }else{
            dialogMessage(res.message);
        }
    }

    function setInputsLevelDetails(response){
        var res = $.parseJSON(response);
        var form = $('#SFM-levelForm');
        
        form.find('input[name="lcodeID"]').val(res.data.codeID);
        form.find('input[name="labbrv"]').val(res.data.desc_code);
        form.find('input[name="ldescription"]').val(res.data.description);
        form.find('input[name="lstatus"]').val(res.data.status);
        form.find('input[name="lstartDate"]').val(res.data.start_date);
        form.find('input[name="lendDate"]').val(res.data.end_date);
        form.find('select[name="lrank"]').val(res.data.rankCodeID);
        form.find('select[name="lcan_purchase"]').val(res.data.can_purchase);
        form.find('select[name="lCLC"]').val(res.data.credit_line);
        form.find('select[name="ltrade"]').val(res.data.non_trade);
        form.find('input[name="ldownline"]').val(res.data.has_downline);
        form.find('input[name="lpaccount"]').val(res.data.has_personal_acct);
        
        if(res.data.has_downline == '1') form.find('input[name="ldownline"]').attr('checked',true);
        else form.find('input[name="ldownline"]').attr('checked',false);
        
        if(res.data.has_personal_acct == '1') form.find('input[name="lpaccount"]').attr('checked',true);
        else form.find('input[name="lpaccount"]').attr('checked',false);
        
        form.find('input[name="laction"]').val('update');
        form.find('input[name="btnNewLevel"]').val('Update Level');
    }

    function getRanksForSelect(){
        $.ajax({
            url: 'pages/sfm/param_ajax_calls/level_ajax.php',
            type:'POST',
            data: { 'action':'get_ranks' },
            success: frmListsRanksSelect
        });
    }
    
    function frmListsRanksSelect(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.ranks.length > 0){
            for(var x = 0; x < res.ranks.length; x++){
                html+= '<option class="rank-select" value="'+ res.ranks[x].codeID +'">'+ res.ranks[x].codeID + ' - ' + res.ranks[x].description +'</option>';
            }
            
            $('.rank-select').remove();
            $('#SFM-levelForm select[name="lrank"]').append(html);
        }
    }
    
    function clearFields(obj){
        obj.find('input[name="ldescription"]').val('');
        obj.find('input[name="lstatus"]').val('');
        obj.find('input[name="lstartDate"]').val('');
        obj.find('input[name="lendDate"]').val('');
        obj.find('input[name="labbrv"]').val('');

        obj.find('input[name="lpaccount"]').val('0');
        obj.find('input[name="ldownline"]').val('0');
        obj.find('input[name="ldownline"]').attr('checked',false);
        obj.find('input[name="lpaccount"]').attr('checked',false);

        obj.find('select[name="lrank"]').get(0).selectedIndex = 0;
        obj.find('select[name="lcan_purchase"]').get(0).selectedIndex = 0;
        obj.find('select[name="lCLC"]').get(0).selectedIndex = 0;
        obj.find('select[name="ltrade"]').get(0).selectedIndex = 0;
        
        obj.find('input[name="laction"]').val('insert');
        obj.find('input[name="btnNewLevel"]').val('Create New Level');
    }
    
    doDatePickerLoad("#lstartDate");
    doDatePickerLoad("#lendDate");
   
});


