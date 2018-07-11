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
    $('#SFM-kpiForm').on('submit', function(e){
        var self = $(this);
        var KPIcodeID = self.find('input[name="KPIcodeID"]').val();
        var KPIDesc = self.find('input[name="KPIdescription"]').val();
        var KPIstartDate = self.find('input[name="KPIstartDate"]').val();
        var KPIendDate = self.find('input[name="KPIendDate"]').val();
        var KPIaction = self.find('input[name="KPIaction"]').val();
        var KPIpost = {}, loaderTxt = '';
        
        KPIpost = { 'code':KPIcodeID, 'desc':KPIDesc, 'sDate':KPIstartDate, 'eDate':KPIendDate, 'action':KPIaction };
        
        $('#frmbtnKPI').hide();
        
        if(KPIaction == 'insert') loaderTxt = '<b>Creating new KPI standards...</b>';
        if(KPIaction == 'update') loaderTxt = '<b>Updating KPI standards...</b>';
        
        ShowHideLoader(true,loaderTxt);
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/kpi_standards_ajax.php',
            type: 'POST',
            data: KPIpost,
            success: function(response){ 
                frmResponse( response,self );
                
                ShowHideLoader(false,' ');
                $('#frmbtnKPI').show();
            }
        });
        
        e.preventDefault();
    });
    
    $('.tbl-listing-div table').on('click','a.rcode', function(e){
        var self = $(this);
        var codeID = self.data('code');
        
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/kpi_standards_ajax.php',
            type: 'POST',
            data: {'action':'edit', 'codeID':codeID},
            success: function(response){ setInputsKPIDetails(response); }
        });
        
        e.preventDefault();
    });
    
    pageListerMethod = function kpiGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/sfm/param_ajax_calls/kpi_standards_ajax.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ kpiLists(response); }
                                });
                            }
    
    function kpiLists(response){
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
        
        $('#SFM-kpiForm').find('input[name="KPIcodeID"]').val(res.current_codeID);
        clearFields($('#SFM-kpiForm'));
    }
    
    ajaxDeletePath = 'pages/sfm/param_ajax_calls/kpi_standards_ajax.php';
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    initCheckBoxSelectSaveIds();
    initActionDeleteSaveIds();
    
    function frmResponse(response, obj){
        var res = $.parseJSON(response);
        
        if(res.status == 'success'){
            dialogMessage(res.message);
            
            if(res.from_action == 'insert'){
                obj.find('input[name="KPIcodeID"]').val(res.nxt_code);
            }
            pageListerMethod(0,list_limit);
            
        }else{
            dialogMessage(res.message);
        }
    }
    
    function setInputsKPIDetails(response){
        var res = $.parseJSON(response);
        var form = $('#SFM-kpiForm');
        
        form.find('input[name="KPIcodeID"]').val(res.data.codeID);
        form.find('input[name="KPIdescription"]').val(res.data.description);
        form.find('input[name="KPIstartDate"]').val(res.data.start_date);
        form.find('input[name="KPIendDate"]').val(res.data.end_date);
        form.find('input[name="KPIaction"]').val('update');
        form.find('input[name="btnNewKPI"]').val('Update KPI');
    }
    
    function clearFields(obj){
        obj.find('input[name="KPIdescription"]').val('');
        obj.find('input[name="KPIstartDate"]').val('');
        obj.find('input[name="KPIendDate"]').val('');
        obj.find('input[name="KPIaction"]').val('insert');
        obj.find('input[name="btnNewKPI"]').val('Create New KPI');
    }
    
    doDatePickerLoad("#KPIstartDate");
    doDatePickerLoad("#KPIendDate");
});