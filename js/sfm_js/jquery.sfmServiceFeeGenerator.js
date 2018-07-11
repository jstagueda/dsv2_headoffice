/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: March 27, 2013
 */
$(function($){
    documentWriteSFMParamCustoms();
    getLevelsForSelect();

    $('#SFM-SFForm').on('submit', function(e){
        var self = $(this), ppost = {};
        var SFLevel = self.find('select[name="SFSFL"]').val();
        var CampaignCode = self.find('input[name="SFCM"]').val();
        var action = self.find('input[name="action"]').val();

        ppost = {'SFLevel':SFLevel,'CampaignCode':CampaignCode,'action' : action};
        loaderTxt = '<b>Processing, please wait...</b>';

        if(SFLevel == ''){
            dialogMessage('Select level first to process service fee.');
            return false;
        }

        if(CampaignCode == ''){
            dialogMessage('Select campaign code to process service fee.');
            return false;
        }

        dialogMessage('Processing service fee, please wait...');
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/servicefee_processor.php',
            type: 'POST',
            data: ppost,
            success: processFormSubmitResponse
        });

        e.preventDefault();
        return false;
    });

    function processFormSubmitResponse(response){
        var res = $.parseJSON(response);
        var html = '';

        if(res.status == 'success'){
            dialogMessage('Preparing lists of results, be patient...');
            $.ajax({
                cache: false,
                url: 'pages/sfm/param_ajax_calls/servicefee_processor.php',
                type: 'POST',
                data: {'action':'lists'},
                success: processResponseLists
            });
        }else{
            html+= '<tr class="tbl-td-rows">';
            html+= '<td class="tbl-td-center td-bottom-border" colspan="3">Generate first to see list(s).</td>';
            html+= '</tr>';

            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
            dialogMessage(res.message);
        }
    }

    function processResponseLists(response){
        var res = $.parseJSON(response);
        var html = '';

        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                html+= '<tr class="tbl-td-rows">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].Code +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].Name +'</td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].EarnedBonusAmount +'</td>';
                html+= '</tr>';
            }

            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
        }else{
            $('.tbl-td-rows td').text(res.message);
        }

        $('#dialog-message').dialog('close');
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
                html+= '<option class="SFSFL" value="'+ res.levels[x].codeID +'">'+ res.levels[x].codeID + ' - ' + res.levels[x].description +'</option>';
            }

            $('.SFSFL').remove();
            $('#SFM-SFForm select[name="SFSFL"]').append(html);
        }
    }

});