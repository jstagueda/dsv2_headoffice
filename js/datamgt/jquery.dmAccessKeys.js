/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 26, 2013
 */
var list_limit = 5;
var pageListerMethod;

$(function($){

    $('[name=branchList]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term},
                url     :   "pages/bpm/ajax_requests/AppliedPaymentReport.php",
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Label,
                            value   :   item.Value,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=branch]').val(ui.item.ID);
        }
    });

    documentWriteSFMParamCustoms();
    $('#DM-AKForm').on('submit', function(e){
        var self = $(this);

        dialogMessage('Processing Access Keys...');
        $.ajax({
            cache: false,
            url: 'pages/datamanagement/system_param_call_ajax/AccessKeys.php',
            type: 'POST',
            data: self.serialize(),
            success: function(response){
                frmResponse( response,self );

                $('#frmbtn').show();
            }
        });

        e.preventDefault();
    });

    //For listing, when item name was clicked
    $('.tbl-listing-div table').on('click','a.lcode', function(e){
        var self = $(this);
        var ID = self.data('id');

        dialogMessage('Preparing edit details, please wait...');
        $.ajax({
            cache: false,
            url: 'pages/datamanagement/system_param_call_ajax/AccessKeys.php',
            type: 'POST',
            data: { 'action':'edit', 'ID':ID },
            success: function(response){ $('#dialog-message').dialog("destroy"); setInputsAccessKeyDetails(response); }
        });

        e.preventDefault();
    });


    pageListerMethod = function accessKeysGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/datamanagement/system_param_call_ajax/AccessKeys.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ accessKeysLists(response); }
                                });
                            }

    function accessKeysLists(response){
        var res = $.parseJSON(response);
        var html = '', checker = '';

        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                if(checkIfInIdsforDelete(res.lists[x].ID)){
                    checker = ' checked="checked" ';
                }else{
                    checker = ' ';
                }

                html+= '<tr class="tbl-td-rows" id="td-row-'+ res.lists[x].ID +'">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].Code +'</td>';
                html+= '<td class="td-bottom-border"><a class="lcode" data-id="'+ res.lists[x].ID +'" href="#">'+ res.lists[x].DecryptedAccessKey +'</a></td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].ExpirationDate +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border"><input type="checkbox"'+ checker +'value="'+ res.lists[x].ID +'" name="action_chk[]" class="action_chk" /></td>';
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

        clearFields($('#DM-AKForm'));
    }

    ajaxDeletePath = 'pages/datamanagement/system_param_call_ajax/AccessKeys.php';
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    initCheckBoxSelectSaveIds();
    initActionDeleteSaveIds();

    function frmResponse(response, obj){
        var res = $.parseJSON(response);

        if(res.status == 'success'){
            dialogMessage(res.message);

            pageListerMethod(0,list_limit);
        }else{
            dialogMessage(res.message);
        }
    }

    function setInputsAccessKeyDetails(response){
        var res = $.parseJSON(response);
        var form = $('#DM-AKForm');

        form.find('input[name="access_key"]').val(res.data.DecryptedAccessKey);
        form.find('input[name="expdate"]').val(res.data.ExpirationDate);
        form.find('[name="branch"]').val(res.data.BranchID);
        form.find('[name="branchList"]').val(res.data.Branch);
        form.find('input[name="ID"]').val(res.data.ID);
        form.find('input[name="access_key"]').attr('readonly',true);

        form.find('input[name="action"]').val('update');
        form.find('input[name="frmbtn"]').val('Update Access Key');
    }

    function clearFields(obj){
        obj.find('input[name="access_key"]').val('');
        obj.find('input[name="expdate"]').val('');
        obj.find('input[name="ID"]').val('');
        obj.find('[name="branch"]').val(0);
        obj.find('[name="branchList"]').val('');
        obj.find('input[name="access_key"]').attr('readonly',false);

        obj.find('input[name="action"]').val('insert');
        obj.find('input[name="frmbtn"]').val('Create New Access Key');
    }

    $('#expdate').datetimepicker({
	timeFormat: "hh:mm tt"
    });
});


