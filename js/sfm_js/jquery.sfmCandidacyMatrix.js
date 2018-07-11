/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var list_limit = 5;
var pageListerMethod;

$(function($){
    pageListerMethod = function SFMCandidacyMatrixGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/sfm/param_ajax_calls/candidacy_matrix.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ SFMCandidacyMatrixLists(response); }
                                });
                            }
                            
    documentWriteSFMParamCustoms();
    getLevelsForSelect();
    //getCampaignsForSelect();
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    
    
    $('#SFM-candiMatrixForm').on('submit', function(e){
        var self = $(this), ppost = {},loaderTxt = '';
        var MatrixID = self.find('input[name="matrixID"]').val();
        var SFLevel = self.find('select[name="matrixSFL"]').val();
        var MMonth = self.find('select[name="matrixMonth"]').val();
        //var MCampaign = self.find('select[name="matrixCampaign"]').val();
        var PUDGS = self.find('input[name="PUDGS"]').val();
        var recruits = self.find('input[name="recruits"]').val();
        var BCR = self.find('input[name="BCR"]').val();
        var CBR = self.find('select[name="CBR"]').val();
        var action = self.find('input[name="action"]').val();
        //'MCampaign':MCampaign,
        ppost = {'MatrixID':MatrixID,'SFLevel':SFLevel,'MMonth':MMonth,'PUDGS':PUDGS,'recruits':recruits,'BCR':BCR,'CBR':CBR,'action' : action};
        
        //We process and validate all customers that will pass on level criterias here...
        if(action == 'insert') loaderTxt = '<b>Creating candidacy matrix...</b>';
        if(action == 'update') loaderTxt = '<b>Updating candidacy matrix...</b>';
        
        $('#frmbtnMatrix').hide();
        ShowHideLoader(true,loaderTxt);
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/candidacy_matrix.php',
            type: 'POST',
            data: ppost,
            success: processFormSubmitResponse
        });
        
        e.preventDefault();
        return false;
    });
    
    $('.tbl-listing-div table').on('click','a.matrixID', function(e){
        var self = $(this);
        var matrixID = self.data('id');
        
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/candidacy_matrix.php',
            type: 'POST',
            data: {'action':'edit', 'matrixID':matrixID},
            success: function(response){ setInputsMatrixDetails(response); }
        });
        
        e.preventDefault();
    });
    
    function SFMCandidacyMatrixLists(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                html+= '<tr class="tbl-td-rows">';
                html+= '<td class="tbl-td-center td-bottom-border"><a href="javascript:void(0);" class="matrixID" data-id="'+ res.lists[x].MatrixID +'">'+ res.lists[x].desc_code +'</a></td>';
                html+= '<td class="tbl-td-center td-bottom-border"> <b>'+ res.lists[x].Month + '</b></td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalPaidUpDGS +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].TotalRecruits +'</td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].TotalBCR +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].CandidacyBonusRate +'</td>';
                html+= '</tr>';
            }
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
        }else{
            $('.tbl-td-rows td').text(res.message);
        }
        
        if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
        
    }
    
    function setInputsMatrixDetails(response){
        var elem = $('#SFM-candiMatrixForm');
        var res = $.parseJSON(response);
        
        elem.find('input[name="matrixID"]').val(res.data.MatrixID);
        elem.find('select[name="matrixSFL"]').val(res.data.LevelID);
        elem.find('select[name="matrixMonth"]').val(res.data.Month);
        //elem.find('select[name="matrixCampaign"]').val(res.data.CampaignCode);
        elem.find('input[name="PUDGS"]').val(res.data.TotalPaidUpDGS);
        elem.find('input[name="recruits"]').val(res.data.TotalRecruits);
        elem.find('input[name="BCR"]').val(res.data.TotalBCR);
        elem.find('select[name="CBR"]').val(res.data.CandidacyBonusRate);
        elem.find('input[name="action"]').val('update');
        elem.find('input[name="btnNewMatrix"]').val('Update Candidacy Matrix');
    }
    
    function processFormSubmitResponse(response){
        var res = $.parseJSON(response);
        
        if(res.status == 'success'){
            if(res.from_action == 'insert') _clearFields($('#SFM-candiMatrixForm'));
            
            pageListerMethod(0,list_limit);
        }
        
        dialogMessage(res.message);
        $('#frmbtnMatrix').show();
        ShowHideLoader(false,' ');
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
                html+= '<option class="matrixSFL" value="'+ res.levels[x].codeID +'">'+ res.levels[x].codeID + ' - ' + res.levels[x].description +'</option>';
            }
            
            $('.matrixSFL').remove();
            $('#SFM-candiMatrixForm select[name="matrixSFL"]').append(html);
        }
    }
    
    function getCampaignsForSelect(){
        $.ajax({
            url: 'pages/sfm/param_ajax_calls/candidacy_matrix.php',
            type:'POST',
            data: { 'action':'get_campaigns' },
            success: function(response){
                var res = $.parseJSON(response);
                var html = '';

                if(res.campaigns.length > 0){
                    for(var x = 0; x < res.campaigns.length; x++){
                        html+= '<option class="matrix-Campaign" value="'+ res.campaigns[x].Code +'">'+ res.campaigns[x].Code + '</option>';
                    }

                    $('.matrix-Campaign').remove();
                    $('#SFM-candiMatrixForm select[name="matrixCampaign"]').append(html);
                }
            }
        });
    }
    
    function _clearFields(Element){
        var elem = Element;
        
        elem.find('select[name="matrixSFL"]').get(0).selectedIndex = 0;
        elem.find('select[name="matrixMonth"]').get(0).selectedIndex = 0;
        //elem.find('select[name="matrixCampaign"]').get(0).selectedIndex = 0;
        elem.find('input[name="PUDGS"]').val('');
        elem.find('input[name="recruits"]').val('');
        elem.find('input[name="BCR"]').val('');
        elem.find('select[name="CBR"]').get(0).selectedIndex = 0;
    }
});

