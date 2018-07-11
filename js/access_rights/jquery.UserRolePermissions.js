/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: June 27, 2013
 */
var list_limit = 15;
var pageListerMethod;

$(function($){
    documentWriteSFMParamCustoms();
    initUserSearchAutoComplete('#user');
    
    pageListerMethod = function userPermissionsGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/ajax_request/ajaxUsers.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: userPermissionsLists
                                });
                            }
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    ajaxDeletePath = 'pages/ajax_request/ajaxUsers.php';
    initCheckBoxSelectSaveIds();
    initActionDeleteSaveIds();
    
    //Form process for submitting new user permissions
    $('#URP-Form').on('submit',function(){
        var action = $('#action').val();
        
        if(action == 'insert') dialogMessage('Adding permission to user, please wait...');
        if(action == 'update') dialogMessage('Updating user permission, please wait...');
        
        $.ajax({
                cache: false,
                url: 'pages/ajax_request/ajaxUsers.php',
                type:'POST',
                data: $(this).serialize(),
                success: frmSubmitResponse
            });
            
        return false;
    });
    
    $('.tbl-listing-div table').on('click','a.a_user', function(e){
        var self = $(this);
        var userID = self.data('userid');
        
        dialogMessage('Preparing edit details, please wait...');
        $.ajax({
            cache: false,
            url: 'pages/ajax_request/ajaxUsers.php',
            type: 'POST',
            data: {'action':'edit', 'userID':userID},
            success: setInputsUserPermissionDetails
        });
        
        e.preventDefault();
    });
    
    //Response of the triggered add method of user permissions form..
    function frmSubmitResponse(response){
        var res = $.parseJSON(response);
        if(res.status == 'success'){
            pageListerMethod(0,list_limit);
            clearFields();
        }
        
        dialogMessage(res.message);
    }
    
    function setInputsUserPermissionDetails(response){
        var res = $.parseJSON(response);
        if(res.status == 'success'){
            $('input[name="role_perm[]"]').attr('checked',false);
            $('#UserID').val(res.data.ID);
            $('#user-name').text(res.data.Name);
            $('#user-username').text(res.data.UserName);
            $('#user-type').text(res.data.TypeName);
            $('#user-department').text(res.data.Department);
            
            if(res.data.Permissions){
                var perms = res.data.Permissions;
                for(var x = 0; x < perms.length; x++){
                    $('#role_perm_' + perms[x]).attr('checked',true);
                }
            }
            
            $('#frmbtnPerm').val('Update Permission');
            $('#action').val('update');
            $('#dialog-message').dialog("destroy");
            $('#tbl-URP-edits').slideDown();
        }else{
            dialogMessage(res.message);
        }
    }
    
    function userPermissionsLists(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.status == 'success'){
            if(res.lists.length > 0){
                for(var x = 0; x < res.lists.length; x++){
                    html+= '<tr class="tbl-td-rows" id="td-row-'+ res.lists[x].UserID +'">';
                    html+= '<td class="tbl-td-left td-bottom-border"><a class="a_user" href="javascript:void(0);" data-userid="'+ res.lists[x].UserID +'">'+ res.lists[x].UserName +'</a></td>';
                    html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].Name +'</td>';
                    html+= '<td class="tbl-td-left td-bottom-border">'+ res.lists[x].TypeName +'</td>';
                    html+= '<td class="td-bottom-border">'+ res.lists[x].Permissions +'</td>';
                    html+= '<td class="tbl-td-center td-bottom-border"><input type="checkbox" value="'+ res.lists[x].UserID +'" name="action_chk[]" class="action_chk" /></td>';
                    html+= '</tr>';
                }

                $('.tbl-td-rows').remove();
                $('.tbl-listing-div table').append(html);
                $('#btn-for-checkbox').removeClass('hide');
            }else{
                $('.tbl-td-rows td').text(res.message);
            }
        }else{
            $('.tbl-td-rows td').text(res.message);
        }
        
        if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
        else $('#tblPageNavigation').html('');
    }
    
    function initUserSearchAutoComplete(DOMelement){
        $(DOMelement).on('keydown',function(){
            $('#tbl-URP-edits').slideUp();
        });
        
        //Do the process of jquery ui autocomplete of customer options...
        $(DOMelement).autocomplete({
            source: function(request, response){
                //get source remotely from database...
                $.ajax({
                        cache: false,
                        url: 'pages/ajax_request/ajaxUsers.php',
                        type:'POST',
                        data: {'action':'get_users','input_term':request.term},
                        success: function(data){ //set up details for display....
                            response( $.map( $.parseJSON(data).lists, function( item ) {
                                return {
                                    label: '[' + item.TypeName + '] - ' + item.Name ,
                                    value: { 'ID':item.ID,'Name':item.Name, 'TypeName':item.TypeName,'UserName':item.UserName,'Department':item.Department } 
                                }
                            }));
                            
                            $('#ajax-loader-small').hide();
                            $('#ajax-loader-small').addClass('hide');
                        }
                    });
            },
            search: function(){ $('#ajax-loader-small').show(); $('#ajax-loader-small').removeClass('hide'); }, //do some notifications that source is still in process....
            select: function( event, ui ) { //select function...
                var User = ui.item.value;
                
                $(DOMelement).val('');
                $('#UserID').val(User.ID);
                $('#user-name').text(User.Name);
                $('#user-username').text(User.UserName);
                $('#user-type').text(User.TypeName);
                $('#user-department').text(User.Department);
                $('#tbl-URP-edits').slideDown();
                
                return false;
            }
        });
    }
    
    //Clear fields function....
    function clearFields(){
        $('#user').focus();
        $('#UserID').val('');
        $('#user-name').text('');
        $('#user-username').text('');
        $('#user-type').text('');
        $('#user-department').text('');
        $('input[name="role_perm[]"]').attr('checked',false);
        $('#frmbtnPerm').val('Add Permission to User');
        $('#action').val('insert');
        $('#tbl-URP-edits').slideUp();
    }
    
});