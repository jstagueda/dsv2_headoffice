/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 04, 2013
 */
var list_limit = 15;
var pageListerMethod;
var pageListerWhere;
var IDsMovement = new Array();
var TempRecordTermination = new Array();
var TerminationElementIDs = new Array();

//process for submitting all approved customers for promotion...
function initActionMovementProceed(ProceedType){
    $('#btn-for-checkbox').on('click','a',function(){
        var button = {};
        var procText = '';

        if(ProceedType == 'PROMOTION') procText = 'Promotion';
        if(ProceedType == 'DEMOTION') procText = 'Demotion';
        if(ProceedType == 'TERMINATION') procText = 'Termination';

        if(IDsMovement.length > 0){
            button = {
                'Yes' : function(){ 
                            if(ProceedType == 'TERMINATION'){ _doSFMMovementTermination(ProceedType); }
                            else{ _doSFMMovement(ProceedType); } 
                        },
                'Cancel' : function(){ $(this).dialog('close'); }
            };

            dialogFormListsEtcDisplay('DSS Movement','<p>Are you sure in this '+ procText +'?</p>',{ height : '120', buttons : button });
        }else{
            dialogMessage('Please select who to approved first that need\'s '+ procText +'.');
        }

        return false;
    });
}

//checkbox event that will store all IBMs ID into array which can be used on approved button process...
function initActionMovementCheckbox(){
    $('.tbl-listing-div table').on('click','input[type="checkbox"]',function(){
        var self = $(this);
        var index;
        
        //Used for termination array removal by keys...
        if(self.attr('id')){
            if(this.checked){ TerminationElementIDs.push(self.attr('id')); }
            else{
                index = TerminationElementIDs.indexOf(self.attr('id'));
                if(index != -1){
                    TerminationElementIDs.splice(index, 1);
                }
            }
        }

        if(this.checked){
            IDsMovement.push(self.val());
        }else{
            index = IDsMovement.indexOf(self.val());
            if(index != -1){
                IDsMovement.splice(index, 1);
            }
        }
    });
}

/*
 *  @author: jdymosco
 *  @date: February 28, 2013
 *  @explanation: Function that will lists and display data in table after validation.
 */
function SFMForMovementLists(response){
    var res = $.parseJSON(response);
    var html = '',checker = '';

    if(res.lists.length > 0){
        for(var x = 0; x < res.lists.length; x++){

            //checker if ID is already selected for pagination...
            if(_doCheckIfInIDsMovement(res.lists[x].SFMmID)){
                checker = ' checked="checked" ';
            }else{
                checker = ' ';
            }

            html+= '<tr class="tbl-td-rows" id="td-row-'+ res.lists[x].SFMmID +'">';
            html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].SFMmCode +'</td>';
            html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].SFMmCustomerName +'</td>';
            html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].SFMmDGSTotalSales +'</td>';
            html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].SFMmTotalRecruits +'</td>';
            html+= '<td class="tbl-td-center td-bottom-border"></td>';
            html+= '<td class="tbl-td-right td-bottom-border">'+ res.lists[x].SFMmTotalBCR +'%</td>';
            html+= '<td class="tbl-td-center td-bottom-border"><input type="checkbox"'+ checker +'value="'+ res.lists[x].SFMmID +'" name="action_chk[]" class="action_chk" /></td>';
            html+= '</tr>';
        }

        $('.tbl-td-rows').remove();
        $('.tbl-listing-div table').append(html);
        $('#btn-for-checkbox').removeClass('hide');
    }else{
        html = '<tr class="tbl-td-rows">';
        html+= '<td class="tbl-td-center td-bottom-border" colspan="7">'+ res.message +'</td>';
        html+= '</tr>';

        $('.tbl-td-rows').remove();
        $('.tbl-listing-div table').append(html);
        $('#btn-for-checkbox').addClass('hide');
    }
    $( "#dialog-message" ).dialog( "close" );

    if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
    else $('#tblPageNavigation').html('');
}

function SFMForMovementTerminationLists(response){
    var res = $.parseJSON(response);
    var html = '',checker = '';

    if(res.status == 'success'){
        if(res.lists.length > 0){
            for(var y = 0; y < res.lists.length; y++){
                TempRecordTermination.push(res.lists[y]);
            }
            
            pageListerMethod(0,list_limit);
            $('#btn-for-checkbox').removeClass('hide');
        }
    }else{
        html = '<tr class="tbl-td-rows">';
        html+= '<td class="tbl-td-center td-bottom-border" colspan="5">'+ res.message +'</td>';
        html+= '</tr>';

        $('.tbl-td-rows').remove();
        $('.tbl-listing-div table').append(html);
        $('#btn-for-checkbox').addClass('hide');
    }
    $( "#dialog-message" ).dialog( "close" );
    
}

function _doSFMMovementTermination(Mtype){
    var LevelID = $('select[name="terminationSFL"]').val();
    
    $('#dialog-message-etc').dialog('destroy');
    dialogMessage('Processing movement, please wait...');
    $.ajax({
        cache: false,
        url: 'pages/sfm/param_ajax_calls/movement_termination_ajax.php',
        type: 'POST',
        data: { 'IDsForMovement':IDsMovement, 'oldLevelID': LevelID,'newLevelID':LevelID,'MType':Mtype,'action':'do_movement' },
        success: function(response){
            var res = $.parseJSON(response);

            if(res.status == 'success'){
                dialogMessage(res.message);
                _doRemoveInTableLists(res.ids_updated);
                _doRemoveInArrayTempRecordTermination(); //remove now in temporary array of lists
                IDsMovement.length = 0; //reset values on old selection of lists
                
                current_page = 0;
                pageListerMethod(0,list_limit);
                //Show the print window..
                _doOpenWindow('pages/sfm/sfm_movement_termination_print.php', 'printps2', '850', '1100', 'yes');
            }else{
                dialogMessage(res.message);
            }
        }
    });
}

/*
 *  @author: jdymosco
 *  @date: March 01, 2013
 *  @explanation: Function that will do event process for confirmation of SFM movement.
 */
function _doSFMMovement(Mtype){
    var newLevelID = $('select[name="eSFLto"]').val();
    var oldLevelID = $('select[name="eSFLfrom"]').val();
    var html = '';

    $('#dialog-message-etc').dialog('destroy');
    dialogMessage('Processing movement, please wait...');
    $.ajax({
        cache: false,
        url: 'pages/sfm/param_ajax_calls/movement_promotion_reversion_ajax.php',
        type: 'POST',
        data: { 'IDsForMovement':IDsMovement, 'oldLevelID': oldLevelID,'newLevelID':newLevelID,'MType':Mtype,'action':'do_movement' },
        success: function(response){
            var res = $.parseJSON(response);

            if(res.status == 'success'){
                dialogMessage(res.message);
                _doRemoveInTableLists(res.ids_updated);
                IDsMovement.length = 0; //reset values on old selection of lists
            }else{
                dialogMessage(res.message);
            }
        }
    });
}

function getLevelsForSelect(elementFrom,elementTo){
    $.ajax({
        cache: false,
        url: 'pages/sfm/param_ajax_calls/level_ajax.php',
        type:'POST',
        data: {'action':'get_levels'},
        success: function(response){
            var res = $.parseJSON(response);
        
            frmLevelsSelectFromTo(res,elementFrom,'eSFLfrom');
            frmLevelsSelectFromTo(res,elementTo,'eSFLto');
        }
    });
}

function frmLevelsSelectFromTo(res,id_class,select_name){
    var html = '';

    if(res.levels.length > 0){
        for(var x = 0; x < res.levels.length; x++){
            html+= '<option class="'+ id_class +'" value="'+ res.levels[x].codeID +'">'+ res.levels[x].desc_code + ' - ' + res.levels[x].description +'</option>';
        }

        $('.'+ id_class).remove();
        $('select[name="'+ select_name +'"]').append(html);
    }
}

/*
 * @author: jdymosco
 * @date: February 28, 2013
 * @description: Function that will remove data in table listing display.
 */
function _doRemoveInTableLists(IDs){
    if(IDs.length > 0){
        for(var x = 0;x < IDs.length; x++){
            $('#td-row-'+IDs[x]).remove();
        } 
    }
}

/*
 *  @author: jdymosco
 *  @date: February 28, 2013
 *  @explanation: Function that will check if item is exists in a array.
 */
function _doCheckIfInIDsMovement(ID){
    var index = IDsMovement.indexOf(ID);
    if(index != -1){
        return true;
    }else{
        return false;
    }
}

/*
 *  @author: jdymosco
 *  @date: February 28, 2013
 *  @explanation: Function that will remove item in an array of termination records.
 */
function _doRemoveInArrayTempRecordTermination(){
    for(var x = 0;x < TerminationElementIDs.length; x++){
       TempRecordTermination.splice(TerminationElementIDs[x], 1); 
    } 
}

function _doOpenWindow(mypage, myname, w, h, scroll) {
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
  win = window.open(mypage, myname, winprops);
  if(parseInt(navigator.appVersion)>=4) { win.window.focus(); }
}
