/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco    
 * @date: January 24, 2013
 */
var list_limit = 10;
var pageListerMethod;
var pageListerWhere;

$(function($){
    pageListerMethod = function getWorkStandardsLists(start,limit){
                            var extended_obj = { 'start':start, 'end':limit };
                            
                            extended_obj = $.extend(pageListerWhere, extended_obj);
        
                            $.ajax({
                                cache: false,
                                url: 'pages/sfm/param_ajax_calls/work_standards_ajax.php',
                                type: 'POST',
                                data: extended_obj,
                                success: function(response){ wsLists(response); }
                            });
                       }
    
    documentWriteSFMParamCustoms();
    $('#SFM-wsForm').on('submit', function(e){
        var self = $(this);
        var wsSFL = self.find('select[name="wsSFL"]').val();
        var wsKPI = self.find('select[name="wsKPI"]').val();
        var wsCType = self.find('select[name="wsCType"]').val();
        var wsAction = self.find('input[name="wsaction"]').val();
        var wspost = {};
        
        wspost = {'SFL':wsSFL, 'KPI':wsKPI, 'CType':wsCType, 'action':wsAction};
        pageListerWhere = wspost;
        
        pageListerMethod(0,list_limit);
        
        $('#frmbtnWS').hide();
        ShowHideLoader(true,'<b>Getting lists...</b>');
        
        e.preventDefault();
        return false;
    });
    
    //DOM for viewing work standard details when description clicked.
    $('.tbl-listing-div table').on('click','a',function(){
        var self = $(this);
        var wsCode = self.data('wscode');
        var vpost = {'action':'view','code': wsCode};
        var html_holder = '',button = {};
        
        dialogMessage('Getting work standard details, please wait...');
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/work_standards_ajax.php',
            type: 'POST',
            data: vpost,
            success: function(response){
                var res = $.parseJSON(response);
                                
                if(res.status == 'success'){
                    var detail = res.details;
                    
                    button = {
                        'Close' : function(){
                            $(this).dialog('close');
                        }
                    };
                    
                    //Prepare viewing details HTML...
                    html_holder = '<div class="tbl-float-left">';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">Criteria Type:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.criteria_type +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">Level:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.lvlDesc +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">KPI Standard:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.kpiDesc +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">Description:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.description +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">Minimun Value:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.min_value +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">Max Value:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.max_value +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">Avg. Total:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.avg_total +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">No. Of Months:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.no_of_months +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    
                    if(detail.criteria_type == 'MAINTENANCE'){
                        html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">No. Of Units:</div>';
                        html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.no_of_units +'</div>';
                        html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    }
                    
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">Start Date:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.start_date +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold">End Date:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit text-align-right">'+ detail.end_date +'</div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '</div>';
                    
                    dialogFormListsEtcDisplay('Work Standard Details',html_holder,{width:'380', position:'center', buttons:button});
                    $('#dialog-message').dialog('destroy');
                }else{
                    dialogMessage(res.message);
                }
            }
        });
        
        return false;
    });
    
    getLevelsForSelect();
    getKPIsForSelect();
    paginationActions(list_limit);
    
    function wsLists(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                html+= '<tr class="tbl-td-rows">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].level_desc +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].kpi_desc +'</td>';
                html+= '<td class="td-bottom-border"><a class="wscode" data-wscode="'+ res.lists[x].codeID +'" href="javascript:void(0);">'+ res.lists[x].description +'</a></td>';
                html+= '</tr>';
            }
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
        }else{
            html = '<tr class="tbl-td-rows">';
            html+= '<td class="tbl-td-center td-bottom-border" colspan="3">'+ res.message +'</td>';
            html+= '</tr>';
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
        }
        
        ShowHideLoader(false,' ');
        $('#frmbtnWS').show();
        
        if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
        else $('#tblPageNavigation').html('');
    }
    
    function getLevelsForSelect(){
        $.ajax({
            url: 'pages/sfm/param_ajax_calls/level_ajax.php',
            type:'POST',
            data: { 'action':'get_levels' },
            success: frmListsLevelsSelect
        });
    }
    
    function frmListsLevelsSelect(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.levels.length > 0){
            for(var x = 0; x < res.levels.length; x++){
                html+= '<option class="ws-SFL" value="'+ res.levels[x].codeID +'">'+ res.levels[x].codeID + ' - ' + res.levels[x].description +'</option>';
            }
            
            $('.ws-SFL').remove();
            $('#SFM-wsForm select[name="wsSFL"]').append(html);
        }
    }
    
    function getKPIsForSelect(){
        $.ajax({
            url: 'pages/sfm/param_ajax_calls/kpi_standards_ajax.php',
            type:'POST',
            data: { 'action':'get_KPIs' },
            success: frmListsKPIsSelect
        });
    }
    
    function frmListsKPIsSelect(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.KPIs.length > 0){
            for(var x = 0; x < res.KPIs.length; x++){
                html+= '<option class="ws-KPI" value="'+ res.KPIs[x].codeID +'">'+ res.KPIs[x].codeID + ' - ' + res.KPIs[x].description +'</option>';
            }
            
            $('.ws-KPI').remove();
            $('#SFM-wsForm select[name="wsKPI"]').append(html);
        }
    }
});

