/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 23, 2013
 * @description: Script that will hold or block transactions as the sync process started or initialized.
 */
var $USS = jQuery.noConflict(); //namespace for new jquery sessioner for users.
var startSession;

$USS(document).ready(function(){
    //initSessionCheck();
    
    //function that will initialize all session locker methods..
    function initSessionCheck(){
        appendSessionLockerElements();
        checkUserSessionIsLock();
        startSession = setInterval(function(){ checkUserSessionIsLock();  }, 10000);
    }
    
    //function that will send ajax request for getting / checking if session is lock.
    function checkUserSessionIsLock(){
        $USS.ajax({
            cache: false,
            url: 'pages/ajax_request/dm_DataSyncAjax.php',
            type: 'POST',
            data: { 'action':'check_session_if_lock' },
            success: _doUserSessionLocker
        });
    }
    
    //function that will create element blocker base on the response of request sent in ajax.
    function _doUserSessionLocker(response){
        var res = $USS.parseJSON(response);
        
        if(res.status == 'success'){
            if(res.is_locked == 1){
                $USS('#session-overlay-blocker').css({'display':'block'});
                $USS('#session-blocker-msg').css({'display':'block'});
                $USS('#session-blocker-msg').find('h3').text('User Session Locked');
                $USS('#session-blocker-msg').find('p').html(res.message);
            }else{
                $USS('#session-overlay-blocker').css({'display':'none'});
                $USS('#session-blocker-msg').css({'display':'none'});
            }
            
            if(res.do_reload == true){
                window.location.href = window.location.href + '&do_reload=unlink';
            }
        }
    }
    
    //function that will append additional elements display when blocking user session.
    function appendSessionLockerElements(){
        var html = '';
        html = '<div id="session-overlay-blocker" class="session-overlay-blocker"></div>';
        html+= '<div class="session-blocker-msg" id="session-blocker-msg"><h3></h3><p></p></div>';
        
        $USS(document.body).append(html);
    }
    
});

