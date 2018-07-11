/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 23,2013
 */
var current_page = 0;
var max_page = 0;
var IdsforDelete = new Array();
var ajaxDeletePath;
var IdsForPrinting = new Array();
var ajaxPrintPath;

function ShowHideLoader(show, txt){
    $('#frmLoader').html(txt);

    if(show == true) $('#frmLoader').show();
    else $('#frmLoader').hide();
}

function generatePaginationLinks(total,limit){
    max_page = Math.ceil(total / limit);
    var html = '',html_btn_prev = '', html_btn_nxt = '';
    var html_pagination = '';
    var selected = '';
    
    if(current_page == 0) current_page = 1;
      
    //html = '<img src="images/dfrst.gif">';
    html_btn_prev = '<img src="images/bprv.gif" class="page-prev">';
    html_btn_nxt = '<img src="images/bnxt.gif" class="page-next">';
    
    for(var x = 1;x <= max_page; x++){
        
        if(max_page > 10){
            if(x == current_page) selected = ' selected="selected" ';
            else selected = ' ';
            
            html+= '<option'+ selected +'class="page-num" data-page="'+ x +'" value="'+ x +'">'+ x +'</option>';
        }else{
            if(x == current_page) selected = ' page-selected';
            else selected = '';
            
            html+= '<a class="page-num'+ selected +'" data-page="'+ x +'" href="#">'+ x +'</a>';
        }
        
    }
    
    if(max_page > 10){
        html = '<select name="select-page" class="tbl-float-left">'+ html +'</select>';
    }
    
    html_pagination = html_btn_prev + html + html_btn_nxt;
    //html+= '<img src="images/blst.gif">';
    
    $('#tblPageNavigation').html(html_pagination);
}

function paginationActions(limit){
    $('#tblPageNavigation').on('click','a.page-num',function(e){
        var self = $(this);
        var d_start = self.data('page');
        
        if(current_page == d_start) return false;
        
        current_page = d_start;                
        d_start = ((d_start - 1) * limit);
        
        pageListerMethod(d_start,limit);
        
        e.preventDefault();
        return false;
    });
    
    $('#tblPageNavigation').on('change','select',function(e){
        var self = $(this);
        var d_start = self.find('option:selected').data('page');
        
        if(current_page == d_start) return false;
        
        current_page = d_start;                
        d_start = ((d_start - 1) * limit);
        
        pageListerMethod(d_start,limit);
        
        e.preventDefault();
        return false;
    });
    
    $('#tblPageNavigation').on('click','img',function(){
        var self = $(this);
        var new_start = 0;
        var selected = $('.page-selected').data('page');
        
        if(max_page > 10){
            selected = $('select[name="select-page"] option:selected').data('page');
        }
        
        if(self.hasClass('page-next')){current_page = selected + 1;}
        if(self.hasClass('page-prev')){current_page = selected - 1;}
        
        if(current_page > max_page || current_page <= 0) return false;
        
        new_start = ((current_page - 1) * limit);
        pageListerMethod(new_start, limit);
        
        return false;
    });
}

function dialogMessage(message){
   $( "#dialog-message p" ).html(message);
   $( "#dialog-message" ).dialog({
      autoOpen: false, 
      modal: true,
      position: 'center',
      resizable: false,
      minHeight: '100',
      minWidth: '350',
      title: 'DSS Message'
    });
    $( "#dialog-message" ).dialog( "open" );
}

function dialogMessageWithButton(message,btnFunction){
   $( "#dialog-message-with-button p" ).html(message);
   $( "#dialog-message-with-button" ).dialog({
      autoOpen: false, 
      modal: true,
      position: 'center',
      height: 'auto',
      width: 'auto',
      resizable: false,
      title: 'DSS Message',
      buttons: btnFunction
    });
    $( "#dialog-message-with-button" ).dialog( "open" );
}

function dialogFormListsEtcDisplay(h_title,content,add_options){
   var d_options = {};
   
   d_options = {
      autoOpen: false, 
      modal: true,
      height: 'auto',
      width: 'auto',
      resizable: false,
      title: h_title
    }
    
    if(add_options != '') d_options = $.extend(d_options, add_options); 
    
   $( "#dialog-message-etc" ).html(content);
   $( "#dialog-message-etc" ).dialog(d_options);
   $( "#dialog-message-etc" ).dialog( "open" );
}

function documentWriteSFMParamCustoms(){
    var style = '<style>' + 
                '   .ui-button-icon-only .ui-icon{left: -3%;}' +
                '   .ui-button-icon-only .ui-icon, .ui-button-text-icon-primary .ui-icon, .ui-button-text-icon-secondary .ui-icon, .ui-button-text-icons .ui-icon, .ui-button-icons-only .ui-icon{top: 1%;}' +
                '</style>';
    var dialoHolder = '<div style="display:none;" id="dialog-message" title="Basic dialog"><p>Message here</p></div>';
    dialoHolder += '<div style="display:none;" id="dialog-message-with-button" title="Basic dialog"><p>Message here</p></div>';
    dialoHolder += '<div style="display:none;" id="dialog-message-etc" title="Basic dialog"></div>';
    
    $(document.body).append(style);
    $(document.body).append(dialoHolder);
}

function doDatePickerLoad(element){
    $( element ).datepicker({ dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, yearRange: "-200:+10"});
}

//Function that will initialize date picker and format display with month and year only...
function doDatePickerMonthYearLoad(element){
    $(element).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
}

/*
 *  @author: jdymosco
 *  @date: April 23, 2013
 *  @description: Main action methods and process of checkbox input type in SFM delete item option.
 */
function initCheckBoxSelectSaveIds(){
    $('.tbl-listing-div table').on('click','input[type="checkbox"]',function(){
        var self = $(this);
        var index;
        
        if(this.checked){
            IdsforDelete.push(self.val());
        }else{
            index = IdsforDelete.indexOf(self.val());
            if(index != -1){
                IdsforDelete.splice(index, 1);
            }
        }
    });
}

function initActionDeleteSaveIds(){
   $('#btn-for-checkbox').on('click','a.btn-delete',function(){
       var button = {};
       
       if(IdsforDelete.length > 0){
            button = {
                'Yes' : function(){ doDeleteSaveIds(); $(this).dialog('close'); },
                'Cancel' : function(){ $(this).dialog('close'); }
            };
            dialogFormListsEtcDisplay('DSS Movement','<p>Are you sure want to delete all selected items?</p>',{ height : '120', buttons : button });
        }else{
            dialogMessage('Please select first what should be deleted.');
        }

        return false;
   });
}

function doDeleteSaveIds(){
    if($('#dialog-message').is(':data(dialog)')) $('#dialog-message').dialog("destroy");
    dialogMessage('Deleting items, please wait...');
    $.ajax({
        type:'POST',
        url: ajaxDeletePath,
        data: { 'action':'delete','IdsForDelete':IdsforDelete },
        success: function(response){
            var res = $.parseJSON(response);
            
            if(res.status == 'success'){
                for(var x = 0;x <= IdsforDelete.length;x++){
                    $('#td-row-' + IdsforDelete[x]).remove();
                }
                
                max_page = 0;
                current_page = 0;
                pageListerMethod(0,list_limit);
                IdsforDelete.length = 0;
                
                $('#dialog-message').dialog("destroy");
                dialogMessage('Item(s) deletion done.');
                setTimeout(function(){ $('#dialog-message').dialog("destroy") },4000);
            }else{
                dialogMessage(res.message);
            }
        }
    });
}

/*
 *  @author: jdymosco
 *  @date: April 23, 2013
 *  @description: Main action methods and process of checkbox input type for item option printing.
 */
function initCheckBoxSaveIdsForPrinting(){
    $('.tbl-listing-div table').on('click','input[type="checkbox"]',function(){
        var self = $(this);
        var index;
        
        if(this.checked){
            IdsForPrinting.push(self.val());
        }else{
            index = IdsForPrinting.indexOf(self.val());
            if(index != -1){
                IdsForPrinting.splice(index, 1);
            }
        }
    });
}

function initActionPrintSaveIds(){
   $('#btn-for-checkbox').on('click','a.btn-print',function(){
       var button = {};
       
       if(IdsForPrinting.length > 0){
            button = {
                'Yes' : function(){ doPrintSaveIds(); $(this).dialog('close'); },
                'Cancel' : function(){ $(this).dialog('close'); }
            };
            dialogFormListsEtcDisplay('DSS Print','<p>Ready for printing items?</p>',{ height : '120', buttons : button });
        }else{
            dialogMessage('Please select first what should be printed.');
        }

        return false;
   });
}

function doPrintSaveIds(){
    if($('#dialog-message').is(':data(dialog)')) $('#dialog-message').dialog("destroy");
    dialogMessage('Preparing items to print, please wait...');
    $.ajax({
        type:'POST',
        url: ajaxPrintPath,
        data: { 'action':'prepare_for_printing','IdsForPrinting':IdsForPrinting },
        success: function(response){
            var res = $.parseJSON(response);
            if(res.status == 'success'){
                $('#dialog-message').dialog("destroy");
                _doOpenPopWindow('pages/sfm/sfm_adhocCLIncrease_lists_print.php', 'printps2', '850', '1100', 'yes');
            }else{
                dialogMessage(res.message);
            }
        }
    });
}

function _doOpenPopWindow(mypage, myname, w, h, scroll) {
    var winl = (screen.width-w)/2;
    var wint = (screen.height-h)/2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops);
    if(parseInt(navigator.appVersion)>=4) { win.window.focus(); }
}

/*
 *  @author: jdymosco
 *  @date: February 28, 2013
 *  @explanation: Function that will check if item is exists in a array.
 */
function checkIfInIdsforDelete(ID){
    var index = IdsforDelete.indexOf(ID);
    if(index != -1){
        return true;
    }else{
        return false;
    }
}
//End of main action methods for SFM delete item option...

/*
 *  @author: jdymosco
 *  @date: February 28, 2013
 *  @explanation: Function that will initialized an elemet to allow numeric values only....
 */
function initNumericOnlyAllowed(element){
    $( element ).on('keydown', function(event){
            if( !(event.keyCode == 8                                // backspace
                || event.keyCode == 46                              // delete
                || event.keyCode == 190                              // period
                || event.keyCode == 110                              // decimal point
                || (event.keyCode >= 35 && event.keyCode <= 40)     // arrow keys/home/end
                || (event.keyCode >= 48 && event.keyCode <= 57)     // numbers on keyboard
                || (event.keyCode >= 96 && event.keyCode <= 105))   // number on keypad
              ){  event.preventDefault();  }   // Prevent character input
    });
}

/*
 *  @author: jdymosco
 *  @date: February 28, 2013
 *  @explanation: Function that will initialized an elemet to allow alphabet values only....
 */
function initAlphaOnlyAllowed(element){
    $( element ).on('keydown', function(event){
            if( !(event.keyCode == 8                                // backspace
                || event.keyCode == 46                              // delete
                || (event.keyCode >= 35 && event.keyCode <= 40)     // arrow keys/home/end
                || (event.keyCode >= 65 && event.keyCode <= 90))  
              ){  event.preventDefault();  }   // Prevent character input
    });
}

/*
 *  @author: jdymosco
 *  @date: July 05, 2013
 *  @description: Function that will format number with thousands comma.
 */
function NumberFormat(nStr){
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    
    return x1 + x2;
}

/*
 * @author: jdymosco
 * @date: July 11, 2013
 * @description: Function that will initialize auto completer for customer accounts per branch selection.
 */
function initAutoCompleterAccountsByBranch(DOMelement){
    $('#' + DOMelement).autocomplete({
        source: function(request, response){
            if(ac_branch <= 0 || ac_branch == ''){
                dialogMessage('Please select on branch first.');
                return false;
            }
            
            if(typeof ac_lower_level === 'undefined') ac_lower_level = 'NULL';
            
            //get source remotely from database...
            $.ajax({
                    cache: false,
                    url: 'pages/ajax_request/ajaxCustomers.php',
                    type:'POST',
                    data: {'action':'get_accounts','input_term':request.term, 'SFL':ac_level, 'branch':ac_branch, 'lower_level':ac_lower_level},
                    success: function(data){ //set up details for display....

                        if($.parseJSON(data).status == 'success'){
                                response( $.map( $.parseJSON(data).lists, function( item ) {
                                return {
                                    label: '(' + item.Code + ') -- ' + item.Name,
                                    value: {'Code':item.Code,'Name':item.Name,'ID':item.ID} 
                                }
                            }));
                        }else{
                            dialogMessage($.parseJSON(data).message);
                        }

                        $('#account-for-branch-loader').hide();  
                        $('#account-for-branch-loader').addClass('hide');
                    }
                });
        },
        search: function(){ if(ac_branch > 0){ $('#account-for-branch-loader').show();$('#account-for-branch-loader').removeClass('hide'); } }, //do some notifications that source is still in process....
        select: function( event, ui ) { //select function...
            var Account = ui.item.value;
            var inputElem = DOMelement.split('-');

            $('#' + DOMelement).val('[ '+ Account.Code +' ] - ' + Account.Name);
            
            if(ac_usedID) $('#' + inputElem[0]).val(Account.ID);
            else $('#' + inputElem[0]).val(Account.Code);

            return false;
        }
    });
}

/* Will work on after priority tasks....
window.onerror = function(msg, url, line) {
   // You can view the information in an alert to see things working
   // like so:
   alert("Error: " + msg + "\nurl: " + url + "\nline #: " + line);

   // TODO: Report this error via ajax so you can keep track
   //       of what pages have JS issues
   var suppressErrorAlert = true;
   // If you return true, then error alerts (like in older versions of 
   // Internet Explorer) will be suppressed.
   return suppressErrorAlert;
};*/