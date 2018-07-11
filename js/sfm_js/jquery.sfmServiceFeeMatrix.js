/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 05, 2013
 */
var list_limit = 15;
var pageListerMethod;

$(function($){
    pageListerMethod = function SFMServiceFeeyMatrixGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/sfm/param_ajax_calls/servicefee_matrix.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ SFMServiceFeeMatrixLists(response); }
                                });
                            }
                            
    documentWriteSFMParamCustoms();
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    
    
    $('#SFM-ServiceMatrixForm').on('submit', function(e){
        var self = $(this), ppost = {},loaderTxt = '';
        var MatrixID = self.find('input[name="matrixID"]').val();
        var Code = self.find('input[name="matrixCode"]').val();
        var PMG = self.find('select[name="matrixPMG"]').val();
        var Min = self.find('input[name="matrixMin"]').val();
        var Max = self.find('input[name="matrixMax"]').val();
        var Rate = self.find('input[name="matrixRate"]').val();
        var action = self.find('input[name="action"]').val();
        
        ppost = {'MatrixID':MatrixID,'Code':Code,'PMG':PMG,'Min':Min,'Max':Max,'Rate':Rate,'action' : action};
        
        //We process and validate all customers that will pass on level criterias here...
        if(action == 'insert') loaderTxt = '<b>Creating service fee matrix...</b>';
        if(action == 'update') loaderTxt = '<b>Updating service fee matrix...</b>';
        
        $('#frmbtnMatrix').hide();
        ShowHideLoader(true,loaderTxt);
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/servicefee_matrix.php',
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
            url: 'pages/sfm/param_ajax_calls/servicefee_matrix.php',
            type: 'POST',
            data: {'action':'edit', 'matrixID':matrixID},
            success: function(response){ setInputsMatrixDetails(response); }
        });
        
        e.preventDefault();
    });
    
    function SFMServiceFeeMatrixLists(response){
        var res = $.parseJSON(response);
        var html = '',PMsHTML = '';
        
        if(res.PMGsOpt.length > 0){
            for(var y = 0; y < res.PMGsOpt.length; y++){
                PMsHTML+= '<option class="matrixPMG" value="'+ res.PMGsOpt[y].ID +'">'+ res.PMGsOpt[y].Code +'</option>';
            }
            $('.matrixPMG').remove();
            $('#SFM-ServiceMatrixForm select[name="matrixPMG"]').append(PMsHTML);
        }
        
        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                html+= '<tr class="tbl-td-rows">';
                html+= '<td class="tbl-td-center td-bottom-border"><a href="javascript:void(0);" class="matrixID" data-id="'+ res.lists[x].ID +'">'+ res.lists[x].Code +'</a></td>';
                html+= '<td class="tbl-td-center td-bottom-border"> <b>'+ res.lists[x].PMGCode + '</b></td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].Minimum +'</td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].Maximum +'</td>';
                html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].Discount +'</td>';
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
        var elem = $('#SFM-ServiceMatrixForm');
        var res = $.parseJSON(response);
        
        elem.find('input[name="matrixID"]').val(res.data.ID);
        elem.find('select[name="matrixPMG"]').val(res.data.PMGID);
        elem.find('input[name="matrixCode"]').val(res.data.Code);
        elem.find('input[name="matrixMin"]').val(res.data.Minimum);
        elem.find('input[name="matrixMax"]').val(res.data.Maximum);
        elem.find('input[name="matrixRate"]').val(res.data.Discount);
        elem.find('input[name="action"]').val('update');
        elem.find('input[name="btnNewMatrix"]').val('Update Service Fee Matrix');
    }
    
    function processFormSubmitResponse(response){
        var res = $.parseJSON(response);
        
        if(res.status == 'success'){
            if(res.from_action == 'insert') _clearFields($('#SFM-ServiceMatrixForm'));
            
            pageListerMethod(0,list_limit);
        }
        
        dialogMessage(res.message);
        $('#frmbtnMatrix').show();
        ShowHideLoader(false,' ');
    }
    
    function _clearFields(Element){
        var elem = Element;
        
        elem.find('select[name="matrixPMG"]').get(0).selectedIndex = 0;
        elem.find('input[name="matrixCode"]').val('');
        elem.find('input[name="matrixMin"]').val('');
        elem.find('input[name="matrixMax"]').val('');
        elem.find('input[name="matrixRate"]').val('');
    }
});


