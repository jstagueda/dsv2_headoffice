/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 28, 2013
 */

//Global variables used in this script only..
var prop_data = {fname: '', lname: '', mname: '',dob: '',current_cp: '', current_has_pa: '',
                 names_validated: false,check_exists:false};
var list_limit = 15;
var pageListerMethod;        

$(function($){
    // Create delay function for keyup DOM in validation below...
    var delay = (function() {
            var timer = 0;
            return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
            };
    })();

    documentWriteSFMParamCustoms();
    $('#SFM-enrollmentForm').on('submit', function(e){
        var self = $(this);
        var ecodeID = self.find('input[name="ecodeID"]').val();
        var eSFL = self.find('select[name="eSFL"]').val();
        var eSFLcp = self.find('select[name="eSFL"] option:selected').data('lvl').cp; //checker if level can purchase...
        var eSFLhas_pa = self.find('select[name="eSFL"] option:selected').data('lvl').has_pa; //checker if level with personal account...
        var efname = self.find('input[name="efirst_name"]').val();
        var elname = self.find('input[name="elast_name"]').val();
        var emname = self.find('input[name="emiddle_name"]').val();
        var edob = self.find('input[name="edob"]').val();
        var eTIN = self.find('input[name="eTIN"]').val();
        var eULN = self.find('input[name="eULN"]').val();
        var eULN_IBMID = self.find('input[name="eULN-IBMIDused"]').val();
        var ecredit_limit = self.find('input[name="ecredit_limit"]').val();
        var ecredit_term = self.find('select[name="eCT"]').val();
        var ebank_account_num = self.find('input[name="ebank_account_num"]').val();
        var ebank_account_name = self.find('input[name="ebank_account_name"]').val();
        var ebank_name = self.find('input[name="ebank_name"]').val();
        var ePayoutOrOffset = self.find('select[name="ePOorOS"]').val();
        var eaction = self.find('input[name="SFEaction"]').val();
        var epost = {}, loaderTxt = '';
        
        //Another validation for different scenario when creating new sales force manager...
        if(eSFLcp == 1){
            self.attr('action','index.php?pageid=69&action=new&lname='+elname+'&fname='+efname+'&mname='+emname+'&bday='+edob);
            return true;
        }
        
        epost = {'code':ecodeID, 'SFL':eSFL, 'fname':efname, 'lname':elname, 'mname':emname, 'dob':edob, 
                 'TIN':eTIN, 'ULN':eULN, 'IBMID':eULN_IBMID,'credit_term':ecredit_term,'credit_limit':ecredit_limit, 'bank_acct_num':ebank_account_num,
                 'bank_acct_name':ebank_account_name, 'bank_name':ebank_name, 'SFLcp':eSFLcp, 'SFLhas_pa':eSFLhas_pa, 'PayoutOrOffset':ePayoutOrOffset, 'action':eaction
                };
        
        ShowHideLoader(true,'<b>Creating new sales force manager...</b>');
        $('#frmbtnSFE').hide();
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
            type: 'POST',
            data: epost,
            success: function(response){ frmResponse(response, self); }
        });
        
        e.preventDefault();
        return false;
    });
    
    pageListerMethod = function managerGetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ managerLists(response); }
                                });
                            }
                            
    function managerLists(response){
        var res = $.parseJSON(response);
        var html = '', txt_cp = '';
        
        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                html+= '<tr class="tbl-td-rows">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].code +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].abbrv +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border"><a class="manCode" data-code="'+ res.lists[x].code +'" href="javascript:void(0);">'+ res.lists[x].full_name +'</a></td>';
                html+= '</tr>';
            }
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
        }else{
            $('.tbl-td-rows td').text(res.message);
        }
        
        if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
    }
    
    //DOM for validating and checking of manager if already exists.
    $('input[name="efirst_name"]').on({keyup: checkIfExists, /*blur: checkIfExists, */keydown: checkIfLevelSelected});
    $('input[name="elast_name"]').on({keyup: checkIfExists, /*blur: checkIfExists, */keydown: checkIfLevelSelected});
    $('input[name="emiddle_name"]').on({keyup: checkIfExists, /*blur: checkIfExists, */keydown: checkIfLevelSelected});
    $('input[name="edob"]').on({keyup: checkIfExists, /*blur: checkIfExists, */change: checkIfExists, keydown: checkIfLevelSelected});
    $('select[name="eSFL"]').on({change: checkIfCanPurchase});
    
    //DOM for editing and updating manager details when manager name was clicked in the lists.
    $('.tbl-listing-div table').on('click','a.manCode',function(){
        var self = $(this);
        var html_holder = '', button = {}, mCode = self.data('code');
        
        $('#edit-loader').removeClass('hide');
        //Get manager details first here...
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
            type: 'POST',
            data: {'action':'edit', 'mCode':mCode},
            // On success of getting manager details we display a popup for editing details option.
            success: function(response){
                var res = $.parseJSON(response);
                
                if(res.status == 'success'){
                    //Button used for updating manager details process...
                    button = {
                                'Update Details' : function(){
                                    dialogMessage('Updating details, please wait....');
                                    var updateForm = $('#SFM-enrollmentUpdateForm');
                                    var ecodeID = updateForm.find('input[name="dialog-ecodeID"]').val();
                                    var eSFL = updateForm.find('select[name="dialog-eSFL"]').val();
                                    var eSFLcp = updateForm.find('select[name="dialog-eSFL"] option:selected').data('lvl').cp; //checker if level can purchase...
                                    var eSFLhas_pa = updateForm.find('select[name="dialog-eSFL"] option:selected').data('lvl').has_pa; //checker if level with personal account...
                                    var efname = updateForm.find('input[name="dialog-efirst_name"]').val();
                                    var elname = updateForm.find('input[name="dialog-elast_name"]').val();
                                    var emname = updateForm.find('input[name="dialog-emiddle_name"]').val();
                                    var edob = updateForm.find('input[name="dialog-edob"]').val();
                                    var eTIN = updateForm.find('input[name="dialog-eTIN"]').val();
                                    var eULN = updateForm.find('input[name="dialog-eULN"]').val();
                                    var OldeULN = updateForm.find('input[name="dialog-old-eULN"]').val();
                                    var eULN_IBMID = updateForm.find('input[name="dialog-eULN-IBMIDused"]').val();
                                    var ecredit_limit = updateForm.find('input[name="dialog-ecredit-limit"]').val();
                                    var ecredit_term = updateForm.find('select[name="dialog-eCT"]').val();
                                    var ebank_account_num = updateForm.find('input[name="dialog-ebank_account_num"]').val();
                                    var ebank_account_name = updateForm.find('input[name="dialog-ebank_account_name"]').val();
                                    var ebank_name = updateForm.find('input[name="dialog-ebank_name"]').val();
                                    var ePOorOS = updateForm.find('select[name="dialog-ePOorOS"]').val();
                                    var eaction = updateForm.find('input[name="dialog-SFEaction"]').val();
                                    var epost = {};
                                    
                                    epost = {'code':ecodeID, 'SFL':eSFL, 'fname':efname, 'lname':elname, 'mname':emname, 'dob':edob, 
                                            'TIN':eTIN, 'ULN':eULN, 'OldULN':OldeULN, 'IBMID':eULN_IBMID,'credit_term':ecredit_term,'credit_limit':ecredit_limit, 'bank_acct_num':ebank_account_num,
                                            'bank_acct_name':ebank_account_name, 'bank_name':ebank_name, 'SFLcp':eSFLcp, 'SFLhas_pa':eSFLhas_pa, 'PayoutOrOffset':ePOorOS,'action':eaction
                                            };
                                    
                                    $.ajax({
                                        cache: false,
                                        url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
                                        type: 'POST',
                                        data: epost,
                                        success: function(response){
                                            var res = $.parseJSON(response);
                                                                                        
                                            if(res.status == 'success'){
                                                $('#dialog-message').dialog('close');
                                                $('#dialog-message-etc').dialog('close');
                                            }
                                            
                                            dialogMessage(res.message);
                                        }
                                    });
                                }
                            };
                    
                    var data = res.data, IBMcode = '',IBMID = '';
                    var selectsDefaultData = { levelID : data.mLevel, creditTermID : data.credit_term_id ,POorOS : data.PayoutOrOffset };
                    
                    if(data.manager_code != null || data.manager_code != undefined) IBMcode = data.manager_code;
                    else IBMcode = '';
                    
                    if(data.ID != null || data.ID != undefined) IBMID = data.ID;
                    else IBMID = '';
                    
                    //Prepare html display for edit fields of manager.        
                    html_holder+= '    <form class="tbl-float-left" id="SFM-enrollmentUpdateForm" method="POST" action="">';
                    html_holder+= '        <div class="tbl-float-right hide" id="loader-still"><img src="images/ajax-loader.gif" /> loading select options...</div>';
                    html_holder+= '        <div class="tbl-float-inherit"><b>Required Fields <span class="required-asterisk">*</span></b></div>';
                    html_holder+= '        <div class="tbl-clear clear-small"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Code:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input disabled="disabled" style="width: 250px;" type="text" id="dialog-ecodeID" name="dialog-ecodeID" value="'+ data.mCode +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">SF Level:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit">';
                    html_holder+= '            <select style="width: 252px;" id="dialog-eSFL" name="dialog-eSFL">';
                    html_holder+= '                <option value="">Select sales force level here...</option>';
                    html_holder+= '                <option class="dialog-enrollment-SFL hide"></option>';
                    html_holder+= '            </select>';
                    html_holder+= '        </div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Last Name:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="dialog-elast_name" type="text" id="dialog-elast_name" value="'+ data.last_name +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">First Name:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="dialog-efirst_name" type="text" id="dialog-efirst_name" value="'+ data.first_name +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Middle Name:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="dialog-emiddle_name" type="text" id="dialog-emiddle_name" value="'+ data.middle_name +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Birth Date:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" name="dialog-edob" type="text" id="dialog-edob" value="'+ data.birth_date +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Network Code:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit">';
                    html_holder+= '             <input type="text" style="width: 252px;" id="dialog-eULN" name="dialog-eULN" value="'+ IBMcode +'" />';
                    html_holder+= '             <input type="hidden" id="dialog-old-eULN" name="dialog-old-eULN" value="'+ IBMcode +'"/>';
                    html_holder+= '             <input type="hidden" id="dialog-eULN-IBMIDused" name="dialog-eULN-IBMIDused" value="'+ IBMID +'"/>';
                    html_holder+= '        </div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Credit Term:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit">';
                    html_holder+= '             <select style="width: 252px;" id="dialog-eCT" name="dialog-eCT">';
                    html_holder+= '                  <option value="">Select credit term here...</option>';
                    html_holder+= '                  <option class="dialog-enrollment-eCT hide"></option>';
                    html_holder+= '             </select>';
                    html_holder+= '        </div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Payout or Offsetting:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit">';
                    html_holder+= '             <select style="width: 252px;" id="dialog-ePOorOS" name="dialog-ePOorOS">';
                    html_holder+= '                 <option value="">Select here...</option>';
                    html_holder+= '                 <option value="1">PAYOUT</option>';
                    html_holder+= '                 <option value="0">OFFSETTING</option>';
                    html_holder+= '             </select>';
                    html_holder+= '        </div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Credit Limit:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" name="dialog-ecredit-limit" type="text" id="dialog-ecredit-limit" value="'+ data.credit_limit +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-small"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">TIN:<span class="required-asterisk">*</span></div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="dialog-eTIN" type="text" id="dialog-eTIN" value="'+ data.TIN +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit bold" style="width: 250px;">Bank Information:</div>';
                    html_holder+= '        <div class="tbl-clear clear-small"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Acct. No.:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="dialog-ebank_account_num" type="text" id="dialog-ebank_account_num" value="'+ data.bank_acct_num +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Acct. Name:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="dialog-ebank_account_name" type="text" id="dialog-ebank_account_name" value="'+ data.bank_acct_name +'" /></div>';
                    html_holder+= '        <div class="tbl-clear clear-xsmall"></div>';
                    html_holder+= '        <div class="tbl-lbl tbl-float-inherit">Bank Name:</div>';
                    html_holder+= '        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="dialog-ebank_name" type="text" id="dialog-ebank_name" value="'+ data.bank_name +'" /></div>';

                    html_holder+= '        <input type="hidden" name="dialog-SFEaction" id="dialog-SFEaction" value="update" />';
                    html_holder+= '    </form>'; 
                                                            
                    //Display edit fields...
                    $('#edit-loader').addClass('hide');
                    dialogFormListsEtcDisplay('Edit Manager Details',html_holder,{ width: '380',position: 'center', buttons: button,open: function(){ doDatePickerLoad("#dialog-edob"); }});                    
                    prepareDefaultSelectFieldsEditDialog(selectsDefaultData);
                    sfmIBMAutoComplete('#dialog-eULN');
                }
            }
        });
        
    });
    
    //Methods for setting default values on from fields....
    getLevelsForSelect();
    getGeneratedCode();
    sfmIBMAutoComplete('#eULN');
    getCreditTerms();
    pageListerMethod(0,list_limit);
    paginationActions(list_limit);
    
    //Everything about functions used in SFM Manager enrollment process....
    function frmResponse(response, obj){
        var res = $.parseJSON(response);
        var button = {}, fs, msg = '', to_page = '';
        
        if(res.status == 'success'){
            obj.find('input[name="ecodeID"]').val(res.nxt_code);
            
            if(res.SFL_pa_btn == true){
                fs = res.SFLhas_pa; //fields submitted will be used to pass in dealer form page.
                button = {
                    'Create Personal Account' : function(){
                        to_page = 'index.php?pageid=69&action=new&lname='+fs.lname+'&fname='+fs.fname+'&mname='+fs.mname+'&bday='+fs.dob+'&IBMCode='+fs.code;
                        location.href = to_page;
                    }
                };
                msg = 'Manager\'s account was successfully created and sales force level <br />';
                msg += 'selected has a requirement to create personal account in dealer page <br />';
                msg += 'in able to finish process. <br /><br />Click on the button below to proceed.';
                
                dialogMessageWithButton(msg, button);
            }else{
                dialogMessage(res.message);        
            }
            
            pageListerMethod(0,list_limit);
            
            frmFieldsClear(obj);
            $('#frmbtnSFE').hide();
        }else{
            $('#frmbtnSFE').show();
            dialogMessage(res.message);
        }
        
        ShowHideLoader(false,'');
    }
    
    //Will clear all form fields...
    function frmFieldsClear(obj){
        obj.find('select[name="eSFL"]').val(0);
        obj.find('input[name="efirst_name"]').val('');
        obj.find('input[name="elast_name"]').val('');
        obj.find('input[name="emiddle_name"]').val('');
        obj.find('input[name="edob"]').val('');
        obj.find('input[name="eTIN"]').val('');
        obj.find('input[name="eULN"]').val('');
        obj.find('select[name="eCT"]').val(0);
        obj.find('input[name="ecredit_limit"]').val('');
        obj.find('input[name="ebank_account_num"]').val('');
        obj.find('input[name="ebank_account_name"]').val('');
        obj.find('input[name="ebank_name"]').val('');
        
        prop_data.fname = '';
        prop_data.lname = '';
        prop_data.mname = '';
        prop_data.current_cp = 0;
        prop_data.current_has_pa = 0;
        prop_data.names_validated = false;
        
        $('.tbl-enrollment-others').slideUp();
    }
    
    function checkIfLevelSelected(){
        var obj = $('#SFM-enrollmentForm');
        var has_select = obj.find('select[name="eSFL"]').val();
        
        if(!has_select){ 
            dialogMessage('Select on SF Level first before filling in other required fields.');
        }else{
            //do nothing here...
        }
    }
    
    /*
     * Function used pulldown DOM change, to check if the selected SF Level can purchase...
     */
    function checkIfCanPurchase(){
        var self = $(this);
        var cp = self.find('option:selected').data('lvl').cp; //checker if level can purchase...
        var has_pa = self.find('option:selected').data('lvl').has_pa; //checker if with personal account...
        var obj = $('#SFM-enrollmentForm');
        var msg = '';
        
        prop_data.current_cp = cp; //set current global variable by selected level if can purchase...
        prop_data.current_has_pa = has_pa; //set current global variable by selected level if can purchase...
        
        //Validation when page was loaded that fields are not yet filled in.
        if(cp == '1'){
            var button = {
                            'Try again and select other levels': function(){
                                frmFieldsClear(obj);
                                $(this).dialog('close');
                            },
                            'Go to Dealer Form': function(){
                                var to_page = ''
                                
                                //Validate if name fields already have values...
                                if(prop_data.names_validated == true){
                                    to_page = 'index.php?pageid=69&action=new&lname='+prop_data.lname+'&fname='+prop_data.fname+'&mname='+prop_data.mname+'&bday='+prop_data.dob;
                                }else{
                                    to_page = 'index.php?pageid=69';
                                }
                                
                                location.href = to_page; //redirect to page...
                            }
                        }
            msg = 'Selected level is allowed to purchase, please click on the button below<br />for next action to do. <br /><br />\n\
                   To clear all fields and try again click the first button or <br />just close using the [X] on top of this dialog message.';            
            dialogMessageWithButton(msg, button);
        }
        
        if(prop_data.fname != '' && prop_data.lname != '' && prop_data.mname != ''){
            if(cp == 1 || cp == null){
                $('.tbl-enrollment-others').slideUp();
                
                if(cp == 1) $('#frmbtnSFE').show();
                else $('#frmbtnSFE').hide();
                
                $('#frmbtnSFE').val('Next');
            }else{
                $('.tbl-enrollment-others').slideDown();
                $('#frmbtnSFE').val('Save');
                $('#frmbtnSFE').show();
            }
        }
    }
    
    //Function used to check if first name, last name, middle name and birthdate details already exists.
    function checkIfExists(){
        var self = $(this);
        var cdata = {};
        var button = {};
        
        prop_data.fname = $('#efirst_name').val();
        prop_data.lname = $('#elast_name').val();
        prop_data.mname = $('#emiddle_name').val();
        prop_data.dob = $('#edob').val();
        
        delay(function(){
            
            cdata = {'action':'check_if_exists', 'first_name':prop_data.fname, 'last_name':prop_data.lname, 'middle_name':prop_data.mname,'birthdate':prop_data.dob};
            
            //Will do process of checking when middle name was already filled in...prop_data.fname != '' && prop_data.lname != '') && 
            if((prop_data.fname != '' && prop_data.lname != '') && (prop_data.current_cp != null && prop_data.current_cp != '')){
                $('#checker-if-exists').removeClass('hide');
                
                /* We used ajax setup async to make ajax calls more accurate because we are 
                 * getting the correct and last validation result on the process of checking customer
                 * and manager details.
                 * 
                 * Using this is still valid because our jquery version didn't deprecate it
                 * but in the future this should be change.
                 */
                $.ajaxSetup({ async:false });
                //End of ajax setup explanation...
                
                $.ajax({
                    cache: false,
                    url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
                    type: 'POST',
                    data: cdata,
                    success: function(response){
                        var res = $.parseJSON(response);
                        var obj = $('#SFM-enrollmentForm');
                        
                        prop_data.check_exists = res.exists;
                        
                        //Validation if names are already registered...
                        if(prop_data.check_exists == true){
                            button = {
                                        'Okay, Try Again': function(){
                                            frmFieldsClear(obj);
                                            $(this).dialog('close');
                                        },
                                        'Nevermind and Stop': function(){
                                            location.href = 'index.php?pageid=71';
                                        }
                                    }
                            dialogMessageWithButton('Sorry, manager name already exists / created.', button);

                            $('.tbl-enrollment-others').slideUp();
                            $('#frmbtnSFE').hide();
                        }else{
                            if(prop_data.current_cp == '0'){
                                $('.tbl-enrollment-others').slideDown();
                                $('#frmbtnSFE').val('Save');
                                $('#frmbtnSFE').show();
                            }
                        }

                        prop_data.names_validated = true;
                        $('#checker-if-exists').addClass('hide');
                    }
                });
            }
            
        }, 1000);
       
    }
    
    //Function for getting auto generated code when creating new manager details.
    function getGeneratedCode(){
         $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
            type:'POST',
            data: {'action':'generate_code'},
            success: function(response){
                var res = $.parseJSON(response);
                $('input[name="ecodeID"]').val(res.mCode);
            }
        });
    }
    
    /* 
     * @author: jdymosco
     * Stopped using this since Feb. 18, 2013 because it was anticipated that managers can be more than hundreds
     * so using select option is not a correct way but I will just retain the function maybe can be used in future =)
     */
    function getUplinesLists(){
         $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
            type:'POST',
            data: {'action':'get_uplines'},
            success: function(response){
                var res = $.parseJSON(response);
                var html = '';
        
                if(res.uplines.length > 0){
                    for(var x = 0; x < res.uplines.length; x++){
                        html+= '<option class="enrollment-eULN" value="'+ res.uplines[x].mCode +'">'+ res.uplines[x].mCode + ' - ' + res.uplines[x].uplineName +'</option>';
                    }

                    $('.enrollment-eULN').remove();
                    $('#SFM-enrollmentForm select[name="eULN"]').append(html);
                }
            }
        });
    }
    
    function getCreditTerms(){
         $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
            type:'POST',
            data: {'action':'get_credit_terms'},
            success: function(response){
                var res = $.parseJSON(response);
                var html = '';
        
                if(res.credit_terms.length > 0){
                    for(var x = 0; x < res.credit_terms.length; x++){
                        html+= '<option class="enrollment-eCT" value="'+ res.credit_terms[x].ID +'">'+ res.credit_terms[x].Name +'</option>';
                    }

                    $('.enrollment-eCT').remove();
                    $('#SFM-enrollmentForm select[name="eCT"]').append(html);
                }
            }
        });
    }
    
    function getLevelsForSelect(){
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/level_ajax.php',
            type:'POST',
            data: {'action':'get_levels'},
            success: frmListsLevelsSelect
        });
    }
    
    function frmListsLevelsSelect(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.levels.length > 0){
            for(var x = 0; x < res.levels.length; x++){
                html+= '<option class="enrollment-SFL" data-lvl=\'{"cp":"'+ res.levels[x].can_purchase +'","has_pa":"'+ res.levels[x].has_personal_acct +'"}\' value="'+ res.levels[x].codeID +'">'+ res.levels[x].codeID + ' - ' + res.levels[x].description +'</option>';
            }
            
            $('.enrollment-SFL').remove();
            $('#SFM-enrollmentForm select[name="eSFL"]').append(html);
        }
    }
    
    //I'm just lazy that's why I didn't made the code dynamic and reusable, ahahaha.... =D
    function prepareDefaultSelectFieldsEditDialog(defaultData){
        
        $.ajaxSetup({ async:false });
        //Dialog select option for levels...
        $('#loader-still').removeClass('hide');
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/level_ajax.php',
            type:'POST',
            data: {'action':'get_levels'},
            success: function(response){
                var res = $.parseJSON(response);
                var html = '';

                if(res.levels.length > 0){
                    for(var x = 0; x < res.levels.length; x++){
                        html+= '<option class="dialog-enrollment-SFL" data-lvl=\'{"cp":"'+ res.levels[x].can_purchase +'","has_pa":"'+ res.levels[x].has_personal_acct +'"}\' value="'+ res.levels[x].codeID +'">'+ res.levels[x].codeID + ' - ' + res.levels[x].description +'</option>';
                    }

                    $('.dialog-enrollment-SFL').remove();
                    $('#SFM-enrollmentUpdateForm select[name="dialog-eSFL"]').append(html);
                }
            }
        });
        
        //Dialog select option for credit terms....
        $.ajax({
            cache: false,
            url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
            type:'POST',
            data: {'action':'get_credit_terms'},
            success: function(response){
                var res = $.parseJSON(response);
                var html = '';
        
                if(res.credit_terms.length > 0){
                    for(var x = 0; x < res.credit_terms.length; x++){
                        html+= '<option class="dialog-enrollment-eCT" value="'+ res.credit_terms[x].ID +'">'+ res.credit_terms[x].Name +'</option>';
                    }

                    $('.dialog-enrollment-eCT').remove();
                    $('#SFM-enrollmentUpdateForm select[name="dialog-eCT"]').append(html);
                }
            }
        });
        
        $('#SFM-enrollmentUpdateForm select[name="dialog-eSFL"]').val(defaultData.levelID);
        $('#SFM-enrollmentUpdateForm select[name="dialog-eCT"]').val(defaultData.creditTermID);
        $('#SFM-enrollmentUpdateForm select[name="dialog-ePOorOS"]').val(defaultData.POorOS);
        $('#loader-still').addClass('hide');
    }
    
    /*
     * New way of displaying manager downlines.
     */
    function sfmIBMAutoComplete(DOMelement){
        $(DOMelement).autocomplete({
            source: function(request, response){
                $.ajax({
                        url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
                        type:'POST',
                        data: { 'action':'get_uplines','manager_term':request.term },
                        success: function(data){
                            response( $.map( $.parseJSON(data).uplines, function( item ) {
                                return {
                                    label: '(' + item.mCode + ') -- ' + item.uplineName,
                                    value: item.mCode + '_' + item.ID
                                }
                            }));
                        }
                    });
            },
            select: function( event, ui ) {
                var code_ID = ui.item.value;
                var splits = code_ID.split('_');
                
                $(DOMelement).val(splits[0]);
                $(DOMelement + '-IBMIDused').val(splits[1]);
                return false;
            }
        });
    }
    
    doDatePickerLoad("#edob");
});


