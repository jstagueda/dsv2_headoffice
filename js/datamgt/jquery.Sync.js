/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 18, 2013
 * @description: Javasript file for sync process of Branch...
 */
var on_progress = false;
var interval = 100;
var load_log;

$(function($){
    documentWriteSFMParamCustoms();
    
    //Main function for sending request to start SOD,EOD and HO or Branch sync process...
    function doProcessSOD_EODAndHOSync(action,sync_opt){
        var msgLoader = ''
        
        if(action == 'doSODAndHOSync') msgLoader = 'SOD and HO sync process on progress, please don\'t click anything to avoid data discrepancy.';
        if(action == 'doEODAndBranchSync') msgLoader = 'EOD and Branch sync process on progress, please don\'t click anything to avoid data discrepancy.';
        
        on_progress = true; //restrict to process another sync...
        
        $('#dialog-message-with-button').dialog("destroy"); 
        dialogMessage(msgLoader);
        _showProcessLogs(true);
        $.ajax({
          cache: false,
          type: 'POST',
          url:'pages/ajax_request/dm_DataSyncAjax.php',
          data: { 'action':action, 'sync_opt':sync_opt },
          success: doSyncResponse
        });
        
        _doAnimateDialogFade();
    }
    
    function doSyncResponse(response){
        var res = $.parseJSON(response);
        
        if(!jQuery.isEmptyObject(res)){
            if(res.status == 'success'){            
                setInterval(function(){ 
                    clearInterval(load_log);
                    $('.check-success').removeClass('hide');
                    $('.sync-loader').addClass('hide');
                }, 6000);
            }

            if(res.status == 'error'){
                dialogMessage(res.message);
                clearInterval(load_log);
                $('.sync-loader').addClass('hide');
                $('.sync-error').removeClass('hide');
                $('.tbl-mid-logs').removeClass('hide');
                $('.check-success').addClass('hide');
                $('.check-success').hide();
                startIframeShowingSyncLogs();
            }
        }else{
            _showProcessLogs(false);
            clearInterval(load_log);
            dialogMessage('System callback file error, please contact IT administrator.');
        }
        
        on_progress = false;
            
    }
    
    //function that will show or start displaying sync process logs...
    function _showProcessLogs(status){
        //hide all again...
        $('.check-success').addClass('hide');
        $('.sync-loader').addClass('hide');
        $('.tbl-mid-logs').addClass('hide');
        $('.sync-error').addClass('hide');
        
        if(status){
            $('.check-success').addClass('hide');
            $('.sync-loader').removeClass('hide');
            
            startIframeShowingSyncLogs();
            load_log = setInterval(function(){ startIframeShowingSyncLogs(); $('.tbl-mid-logs').removeClass('hide'); }, 10000);
        }else{
            $('.check-success').addClass('hide');
            $('.sync-loader').addClass('hide');
            $('.tbl-mid-logs').addClass('hide');
        }
    }
    
    function startIframeShowingSyncLogs(){
        $('#syncframe-log-view').attr("src", function(){ var d = new Date(); return $(this).contents().get(0).location.href + '?rf=' + d.getTime(); });
    }
    
    function _doAnimateDialogFade(){
        //put some fade effects...
        $("#dialog-message").dialog({ hide: {effect: "fadeOut", duration: 3000} });
        setTimeout(function(){ $("#dialog-message").dialog('close'); }, 3000);
        //we destroy dialog so we can use the effect fade in again..
        setTimeout(function(){ $("#dialog-message").dialog('destroy'); }, 5000);
    }
    
    $('#DM-syncForm').on('submit',function(){
        var buttons = {};
        var msgConfirm = 'Are you sure in this procedure to be process? <br /><br /> \n\
                          If you\'re sure in this process please proceed and if not<br />\n\
                          you can contact your administrator for this procedure.';
        
        var sync_opt = $('input[name="sync_opt"]:checked').val();
        var action = $('input[name="action"]').val();
        
        buttons = {
            'Yes, process now': function(){ doProcessSOD_EODAndHOSync(action, sync_opt); },
            'No' : function(){ $(this).dialog("close"); }
        };
        
        if(!on_progress){
            dialogMessageWithButton(msgConfirm,buttons);
        }else{
            dialogMessage('Process was already triggered, could not process another one. ');
        }
        
        return false;
    });
});


