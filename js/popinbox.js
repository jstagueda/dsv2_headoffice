//Author: Joebert Italia
//July 16, 2013


//leave page popin
function leavepage(page){
    
    $( "#dialog-message-with-button p" ).html("Do you want to leave this page?");
    $( "#dialog-message-with-button" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSS Message',
        buttons: {
            "Yes"   :   function(){
                window.location.href = "index.php?pageid="+page;
            },
            "No"    :   function(){$(this).dialog("close");}
        }
    });
    $( "#dialog-message-with-button" ).dialog( "open" );
}

//message popin
function popinmessage(message){
    $( "#dialog-message p" ).html(message);
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSS Message',
        buttons:{
            "Ok" : function(){$(this).dialog("close");}
        }
    });
    $( "#dialog-message" ).dialog( "open" );
}

//confirmation popin
//message = popin message
//buttonname = name for subtitute button
function confirmationpopin(message, buttonname, formname){
    
    $( "#dialog-message p" ).html(message);
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSS Message',
        buttons:{
            "Yes"   : function(){
                $(this).dialog("close");
                $("input[name="+buttonname+"]").remove();
                $("form[name="+formname+"]").append("<input type='hidden' value='submitted' name='"+buttonname+"'>");
                $("form[name="+formname+"]").submit();
            },
            "No"    : function(){
                $(this).dialog("close");
            }
        }
    });
    $( "#dialog-message" ).dialog( "open" );
    
}

//with link redirection
function confirmationpopinwithredirection(message, link){
    
    $( "#dialog-message p" ).html(message);
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSS Message',
        buttons:{
            "Yes"   : function(){
                window.location.href = link;
            },
            "No"    : function(){
                $(this).dialog("close");
            }
        }
    });
    $( "#dialog-message" ).dialog( "open" );
    
}

//end =========================================================
