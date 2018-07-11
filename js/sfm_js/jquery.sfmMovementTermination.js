/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 02, 2013
 */
$(function($){
    pageListerMethod = function getSFMForMovementLists(start,limit){
                            var html = '',checker = '';
                            var starter = 0, ender = 0;
                            
                            if(current_page == 0) current_page = 1;
                            if(current_page > 1) starter = start;
                            
                            ender = (limit * current_page);
                            
                            for(var x = starter; x <= ender; x++){
                                if(TempRecordTermination[x] != undefined){
                                    //checker if ID is already selected for pagination...
                                    if(_doCheckIfInIDsMovement(TempRecordTermination[x].ID)){
                                        checker = ' checked="checked" ';
                                    }else{
                                        checker = ' ';
                                    }

                                    html+= '<tr class="tbl-td-rows" id="td-row-'+ TempRecordTermination[x].ID +'">';
                                    html+= '<td class="tbl-td-center td-bottom-border">'+ TempRecordTermination[x].Code +'</td>';
                                    html+= '<td class="tbl-td-center td-bottom-border">'+ TempRecordTermination[x].Name +'</td>';
                                    html+= '<td class="tbl-td-center td-bottom-border">'+ TempRecordTermination[x].NetAmount +'</td>';
                                    html+= '<td class="tbl-td-center td-bottom-border">'+ TempRecordTermination[x].LastPODate +'</td>';
                                    html+= '<td class="tbl-td-center td-bottom-border"><input id="'+ x +'" type="checkbox"'+ checker +' value="'+ TempRecordTermination[x].ID +'" name="action_chk[]" class="action_chk" /></td>';
                                    html+= '</tr>';
                                }
                            }

                            $('.tbl-td-rows').remove();
                            $('.tbl-listing-div table').append(html);
                            
                            if(TempRecordTermination.length > limit) generatePaginationLinks(TempRecordTermination.length,limit);
                       }
    
    documentWriteSFMParamCustoms(); //load default elements for dialog messages...
    $('#SFM-terminationForm').on('submit', function(e){
        var self = $(this), ppost = {}, ppost_gen = {};
        var terminationSFL = self.find('select[name="terminationSFL"]').val();
        var terminationCM = self.find('select[name="terminationCM"]').val(); //Campaign Month Code
        var action = self.find('input[name="action"]').val();
        
        if(terminationSFL == '' || terminationCM == ''){
            dialogMessage('Select on the options first.');
            return false;
        }
        
        ppost_gen = {'SFL':terminationSFL, 'CampaignCode':terminationCM, 'moveType':'TERMINATION', 'action':'generate'};
        current_page = 0;
        
        //We process and validate all customers that will pass on level criterias here...
        dialogMessage('Processing and validating criterias, please wait...');
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/movement_termination_ajax.php',
            type: 'POST',
            data: ppost_gen,
            success: function(response){ SFMForMovementTerminationLists(response); }
        });
        
        IDsMovement.length = 0; //reset values on old selection of lists
        TempRecordTermination.length = 0; //reset lists
        TerminationElementIDs.lenght = 0;
        
        e.preventDefault();
        return false;
    });
    
    
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
                html+= '<option class="termination-SFL" value="'+ res.levels[x].codeID +'">'+ res.levels[x].codeID + ' - ' + res.levels[x].description +'</option>';
            }
            
            $('.termination-SFL').remove();
            $('#SFM-terminationForm select[name="terminationSFL"]').append(html);
        }
    }
    
    initActionMovementProceed('TERMINATION');
    initActionMovementCheckbox();
    getLevelsForSelect();
    paginationActions(list_limit);
});


