/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var list_limit = 5;
var pageListerMethod;

$(function($){
    documentWriteSFMParamCustoms();
    $('#SFM-criteriaForm').on('submit', function(e){
        var self = $(this);
        var lcSFL = self.find('select[name="lcSFL"]').val();
        var lcKPI = self.find('select[name="lcKPI"]').val();
        var lccodeID = self.find('input[name="lccodeID"]').val();
        var lctype = self.find('select[name="lctype"]').val();
        var lcDesc = self.find('input[name="lcdescription"]').val();
        var lcmin_value = self.find('input[name="lcmin_value"]').val();
        var lcmax_value = self.find('input[name="lcmax_value"]').val();
        var lcnum_months = self.find('input[name="lcnum_months"]').val();
        var lcavg_total = self.find('select[name="lcavg_total"]').val();
        var lcreq_units = self.find('input[name="lcreq_units"]').val();
        var lcstartDate = self.find('input[name="lcstartDate"]').val();
        var lcendDate = self.find('input[name="lcendDate"]').val();
        var lcaction = self.find('input[name="lcaction"]').val();
        var lcpost = {}, loaderTxt = '';
        
        lcpost = { 'code':lccodeID, 'SFL':lcSFL, 'KPI':lcKPI, 'desc':lcDesc, 'ctype':lctype, 'min_val':lcmin_value, 'max_val':lcmax_value,
                   'num_months':lcnum_months, 'avg_total':lcavg_total, 'req_units':lcreq_units, 'sDate':lcstartDate, 'eDate':lcendDate, 'action':lcaction
                 };
        
        $('#frmbtnCriteria').hide();
        
        if(lcaction == 'insert') loaderTxt = '<b>Creating new level criteria...</b>';
        if(lcaction == 'update') loaderTxt = '<b>Updating level criteria...</b>';
        
        ShowHideLoader(true,loaderTxt);
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/level_criteria_ajax.php',
            type: 'POST',
            data: lcpost,
            success: function(response){ 
                frmResponse( response,self );
                
                ShowHideLoader(false,' ');
                $('#frmbtnCriteria').show();
            }
        });
        
        e.preventDefault();
    });
    
    $('.tbl-listing-div table').on('click','a.lccode', function(e){
        var self = $(this);
        var codeID = self.data('code');
        
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/level_criteria_ajax.php',
            type: 'POST',
            data: {'action':'edit', 'codeID':codeID},
            success: function(response){ setInputsLevelDetails(response); }
        });
        
        e.preventDefault();
    });
    
    $('#SFM-criteriaForm').on('change','select[name="lctype"]',function(){
        var self = $(this);
        var mother_parent = self.parent().parent();
        
        if(self.val() == 'DEMOTION' || self.val() == 'TERMINATION'){
            mother_parent.find('.level-criteria-units').removeClass('hide');
        }else{
            mother_parent.find('.level-criteria-units').addClass('hide');
            mother_parent.find('input[name="lcreq_units"]').val('');
        }
    });
    
    pageListerMethod = function levelGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/sfm/param_ajax_calls/level_criteria_ajax.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ levelLists(response); }
                                });
                            }
                            
    function levelLists(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                html+= '<tr class="tbl-td-rows">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].codeID +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].desc_code +'</td>';
                html+= '<td class="td-bottom-border">'+ res.lists[x].kpi_codeID +' - '+ res.lists[x].kpi_desc +'</td>';
                html+= '<td class="td-bottom-border"><a class="lccode" data-code="'+ res.lists[x].codeID +'" href="#">'+ res.lists[x].description +'</a></td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].criteria_type +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].start_date +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].end_date +'</td>';
                html+= '</tr>';
            }
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
        }else{
            $('.tbl-td-rows td').text(res.message);
        }
        
        if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
        
        $('#SFM-criteriaForm').find('input[name="lccodeID"]').val(res.current_codeID);
    }
    
    
    getLevelsForSelect();
    getKPIsForSelect();
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    initNumericOnlyAllowed('#lcmin_value');
    initNumericOnlyAllowed('#lcmax_value');
    
    function frmResponse(response, obj){
        var res = $.parseJSON(response);
        
        if(res.status == 'success'){
            dialogMessage(res.message);
            
            if(res.from_action == 'insert'){
                obj.find('input[name="lccodeID"]').val(res.nxt_code);
            }
            
            clearFields(obj);
            pageListerMethod(0,list_limit);
            
        }else{
            dialogMessage(res.message);
        }
    }

    function setInputsLevelDetails(response){
        var res = $.parseJSON(response);
        var form = $('#SFM-criteriaForm');
        
        if(res.data.criteria_type == 'DEMOTION' || res.data.criteria_type == 'TERMINATION') form.find('.level-criteria-units').removeClass('hide');
        if(res.data.criteria_type == 'PROMOTION') form.find('.level-criteria-units').addClass('hide');
        
        form.find('select[name="lcSFL"]').val(res.data.level_codeID);
        form.find('select[name="lcKPI"]').val(res.data.kpi_codeID);
        form.find('input[name="lccodeID"]').val(res.data.codeID);
        form.find('select[name="lctype"]').val(res.data.criteria_type);
        form.find('input[name="lcdescription"]').val(res.data.description);
        form.find('input[name="lcmin_value"]').val(res.data.min_value);
        form.find('input[name="lcmax_value"]').val(res.data.max_value);
        form.find('input[name="lcnum_months"]').val(res.data.no_of_months);
        form.find('select[name="lcavg_total"]').val(res.data.avg_total.toLowerCase());
        form.find('input[name="lcreq_units"]').val(res.data.no_of_units);
        form.find('input[name="lcstartDate"]').val(res.data.start_date);
        form.find('input[name="lcendDate"]').val(res.data.end_date);
 
        form.find('input[name="lcaction"]').val('update');
        form.find('input[name="btnNewCriteria"]').val('Update Criteria');
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
                html+= '<option class="level-criteria-SFL" value="'+ res.levels[x].codeID +'">'+ res.levels[x].codeID + ' - ' + res.levels[x].description +'</option>';
            }
            
            $('.level-criteria-SFL').remove();
            $('#SFM-criteriaForm select[name="lcSFL"]').append(html);
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
                html+= '<option class="level-criteria-KPI" value="'+ res.KPIs[x].codeID +'">'+ res.KPIs[x].codeID + ' - ' + res.KPIs[x].description +'</option>';
            }
            
            $('.level-criteria-KPI').remove();
            $('#SFM-criteriaForm select[name="lcKPI"]').append(html);
        }
    }
    
    function clearFields(obj){
        obj.find('select[name="lcSFL"]').get(0).selectedIndex = 0;
        obj.find('select[name="lcKPI"]').get(0).selectedIndex = 0;
        obj.find('select[name="lctype"]').get(0).selectedIndex = 0;
        obj.find('input[name="lcdescription"]').val('');
        obj.find('input[name="lcmin_value"]').val('');
        obj.find('input[name="lcmax_value"]').val('');
        obj.find('input[name="lcnum_months"]').val('');
        obj.find('select[name="lcavg_total"]').get(0).selectedIndex = 0;
        obj.find('input[name="lcreq_units"]').val('');
        obj.find('input[name="lcstartDate"]').val('');
        obj.find('input[name="lcendDate"]').val('');
    }
    
    doDatePickerLoad("#lcstartDate");
    doDatePickerLoad("#lcendDate");
    
});


