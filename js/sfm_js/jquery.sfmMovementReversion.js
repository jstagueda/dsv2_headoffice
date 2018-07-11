/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: Feb. 04, 2013
 */

$(function($){
    pageListerMethod = function getSFMForMovementLists(start,limit){
                            var extended_obj = {'start':start, 'end':limit};
                            
                            extended_obj = $.extend(pageListerWhere, extended_obj);
        
                            $.ajax({
                                cache: false,
                                url: 'pages/sfm/param_ajax_calls/movement_promotion_reversion_ajax.php',
                                type: 'POST',
                                data: extended_obj,
                                success: function(response){SFMForMovementLists(response);}
                            });
                       }
    
    documentWriteSFMParamCustoms(); //load default elements for dialog messages...
    $('#SFM-reversionForm').on('submit', function(e){
        var self = $(this), ppost = {}, ppost_gen = {};
        var select_from = self.find('select[name="eSFLfrom"]').val();
        var select_to = self.find('select[name="eSFLto"]').val();
        var action = self.find('input[name="SFEraction"]').val();
        
        if(select_from == '' || select_to == ''){
            dialogMessage('Select on the options first.');
            return false;
        }
                
        ppost_gen = {'SFLfrom':select_from, 'SFLto':select_to, 'moveType':'DEMOTION', 'action':'generate'};
        ppost = {'action' : action};
        pageListerWhere = ppost;
        
        //We process and validate all customers that will pass on level criterias here...
        dialogMessage('Processing and validating criterias, please wait...');
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/movement_promotion_reversion_ajax.php',
            type: 'POST',
            data: ppost_gen,
            success: function(response){
                var res = $.parseJSON(response), html = '';
                
                if(res.status == 'success'){ //If temporary table has inserted pass customers in level citerias we display lists...
                    dialogMessage('Preparing lists, please be patient...');
                    pageListerMethod(0,list_limit);
                }else{
                    dialogMessage(res.message);
                    
                    html = '<tr class="tbl-td-rows">';
                    html+= '<td class="tbl-td-center td-bottom-border" colspan="7">Select on the options to proceed...</td>';
                    html+= '</tr>';
                    
                    $('.tbl-td-rows').remove();
                    $('.tbl-listing-div table').append(html);
                    $('#tblPageNavigation').html('');
                    $('#btn-for-checkbox').addClass('hide');
                }
                
                IDsMovement.length = 0; //reset values on old selection of lists
            }
        });
        
        e.preventDefault();
        return false;
    });
    
    initActionMovementProceed('DEMOTION');
    initActionMovementCheckbox();
    getLevelsForSelect('reversion-SFL-from','reversion-SFL-to');
    paginationActions(list_limit);
       
});
