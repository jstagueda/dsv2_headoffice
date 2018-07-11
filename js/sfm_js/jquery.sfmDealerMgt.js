/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 *  @author: jdymosco
 *  @date: April 23, 2013
 */
var $jq = jQuery.noConflict();

$jq(document).ready(function(){
    documentWriteParamCustoms();
    var action = getQueryParameter('action');
    
    $jq( "#txtlnameDealer,#txtfnameDealer,#txtmnameDealer" ).keyup(function(){$jq(this).val( $jq(this).val().toUpperCase() );}); 
    $jq( "#txtlnameDealer,#txtfnameDealer,#txtmnameDealer" ).keydown(function(event){
        if( !(event.keyCode == 8                                // backspace
            || event.keyCode == 46                              // delete
            || event.keyCode == 9                               // tab
            || event.keyCode == 32                              // space
            || (event.keyCode >= 35 && event.keyCode <= 40)     // arrow keys/home/end
            || (event.keyCode >= 65 && event.keyCode <= 90 ))   
            ){event.preventDefault();}   // Prevent character input
    });
    $jq( "#txtbdaydealer" ).keydown(function(event){
        if( !(event.keyCode == 8                                // backspace
            || event.keyCode == 9                               // tab
            || event.keyCode == 46                              // delete
            || event.keyCode == 173                             // dash
            || event.keyCode == 109                             // substract
            || (event.keyCode >= 35 && event.keyCode <= 40)     // arrow keys/home/end
            || (event.keyCode >= 48 && event.keyCode <= 57)     // numbers on keyboard
            || (event.keyCode >= 96 && event.keyCode <= 105))   // number on keypad
            ){event.preventDefault();}   // Prevent character input
    });
    
    $jq('form[name="frmDealer"]').on('click','input[name="btnProceed"]',function(e){
        var button = {};
        var check = $jq("#same-details").val();
        var lname = $jq("#txtlnameDealer").val(),fname = $jq("#txtfnameDealer").val(),mname = $jq("#txtmnameDealer").val();
        var bday = $jq("#txtbdaydealer").val();
        
        //Check age by birthdate...
        if(getAge(bday) < 18){
            dialogMessage('Sorry your age is below 18 years old, you cannot proceed.');
            return false;
        }
        
        button = {
            "Yes, I'm sure" : function(){location.href="index.php?pageid=69&action=new&lname="+ lname +"&fname="+ fname +"&mname="+ mname + "&bday="+ bday;$jq(this).dialog("close");},
            "No, check again" : function(){$jq(this).dialog("close");}
        };
        
        if(check == '1'){
            dialogMessageWithButton('Same details of "Lastname" and "Birthdate" already exists in the lists,<br /> do you still want to proceed?',button);
            e.preventDefault();
            return false;
        }else{
            return true;
        }
    });
    
    if(action == 'new'){
        //Validates forms required fields in dealer enrollment...
        $jq('form[name="frmDealer"]').on('click','input[name="btnUpdate"]',function(e){
            var self = $jq(this), msg = '',ctrReds = new Array(),index;

            var items = $jq("form[name=\"frmDealer\"] :input").map(function(index, elm){
                if(elm.type != 'radio') return {name: elm.name, type:elm.type, value: $jq(elm).val()};
                if(elm.type == 'radio') return {name: elm.name, type:elm.type, value: $jq(elm).val(), chk_attr: elm.checked};
            });

            /* 
            * Loop through the form fields and validate all specific fields that should be not null.
            */
            $jq.each(items, function(i, d){
                if(d.type != 'hidden' && d.type != 'button' && d.type != 'file' && d.type != 'submit' && d.name != ''){
                    if(d.type == 'text'){
                        if(d.name != 'txtZipCode' && d.name != 'txtTIN'){ 
                            if((d.value == '' || d.value == '0')){$jq('input[name="'+ d.name +'"]').css({"border":"1px solid #FF0000"}); ctrReds.push(d.name); }
                            else{
                                $jq('input[name="'+ d.name +'"]').css({"border":"1px solid #FFD6FB"});
                                index = ctrReds.indexOf(d.name); if(index != -1){ ctrReds.splice(index, 1); }
                            }
                        }
                    }

                    if(d.type == 'select-one'){
                        if(d.name != "cboBIRCOR" && d.name != "cboBIROR" && d.name != "cboVAT" && d.name != "cboCommission"){
                            if((d.value == '' || d.value == '0')){$jq('select[name="'+ d.name +'"]').css({"border":"1px solid #FF0000"}); ctrReds.push(d.name);}
                            else{ $jq('select[name="'+ d.name +'"]').css({"border":"1px solid #FFD6FB"}); index = ctrReds.indexOf(d.name); if(index != -1){ ctrReds.splice(index, 1); } }
                        }
                    }

                    if(d.type == 'textarea'){
                        if((d.value == '' || d.value == '0')){$jq('textarea[name="'+ d.name +'"]').css({"border":"1px solid #FF0000"}); ctrReds.push(d.name);}
                        else{$jq('textarea[name="'+ d.name +'"]').css({"border":"1px solid #FFD6FB"}); index = ctrReds.indexOf(d.name); if(index != -1){ ctrReds.splice(index, 1); } }
                    }

                    if(d.type == 'radio'){
                        if(d.name == 'rdYesNo' && d.chk_attr == true && d.value == '1' && $jq('#area > *').length <= 0){
                            $jq('#in-what-company td').eq(0).css({"color":"#FF0000","font-weight":"bold"});
                            ctrReds.push(d.name);
                        }else if(d.name == 'rdYesNo' && d.chk_attr == true && d.value == '0' && ($jq('#area > *').length > 0 || $jq('#area > *').length <= 0)){
                            $jq('#in-what-company td').eq(0).css({"color":"#000000","font-weight":"normal"});
                            index = ctrReds.indexOf(d.name); if(index != -1){ ctrReds.splice(index, 1); }
                        }else if(d.name == 'rdYesNo' && d.chk_attr == true && d.value == '1' && $jq('#area > *').length > 0){
                            $jq('#in-what-company td').eq(0).css({"color":"#000000","font-weight":"normal"});
                            index = ctrReds.indexOf(d.name); if(index != -1){ ctrReds.splice(index, 1); }
                        }
                    }
                }
            });
            //console.log(ctrReds.length);
            if(ctrReds.length > 0){
                dialogMessage("All fields in red color and border are required to fill in, kindly double check the tabs.");
                e.preventDefault();
                return false;
            }

            if(getAge($jq("#txtbdaydealer").val()) < 18){
                dialogMessage('Sorry your age is below 18 years old, you cannot proceed.');
                return false;
            }

            return true;
        });
    }else if(action == 'update'){
        $jq('form[name="frmDealer"]').on('click','input[name="btnUpdate"]',function(e){
            var self = $jq(this), msg = '',ctrRedsU = new Array(),index;
            var reqFields = ['txtlnameDealer','txtfnameDealer','txtmnameDealer','txtbdaydealer','cboClass','cboCustomerType',
                             'cboGSUType','txtNickName','txtHomeTelNo','txtCPNumber','txtStAddress','cboProvince',
                             'cboTown','cboBarangay','cboZone','txtLStay','cboMaritalStatus','cboEducational'];

            var items = $jq("form[name=\"frmDealer\"] :input").map(function(index, elm){
                if(elm.type != 'radio') return {name: elm.name, type:elm.type, value: $jq(elm).val()};
                if(elm.type == 'radio') return {name: elm.name, type:elm.type, value: $jq(elm).val(), chk_attr: elm.checked};
            });
            
            $jq.each(items, function(i, d){
                if(d.type != 'hidden' && d.type != 'button' && d.type != 'file' && d.type != 'submit' && d.name != ''){
                    if((d.type == 'text' || d.type == 'select-one') && checkIfInArray(d.name,reqFields)){
                       if(trim(d.value) == '' || d.value.length <= 0 || trim(d.value) == 0){
                           if(d.type == 'text') $jq('input[name="'+ d.name +'"]').css({"border":"1px solid #FF0000"});
                           if(d.type == 'select-one') $jq('select[name="'+ d.name +'"]').css({"border":"1px solid #FF0000"});
                           
                           dialogMessage("All fields in red color and border are required to fill in, kindly double check the tabs also.");
                           ctrRedsU.push(d.name);
                       }else{
                           if(d.type == 'text') $jq('input[name="'+ d.name +'"]').css({"border":"1px solid #FFD6FB"});
                           if(d.type == 'select-one') $jq('select[name="'+ d.name +'"]').css({"border":"1px solid #FFD6FB"});
                           
                           index = ctrRedsU.indexOf(d.name); if(index != -1){ ctrRedsU.splice(index, 1); }
                       }     
                    }
                }
            });
            
            if(ctrRedsU.length > 0){ return false; }
            else{ return true; }
        });
    }
    
    $jq('form[name="frmDealer"]').on('click','input[name="btnCheck"]',function(){
        var input_bday = $jq("#txtbdaydealer");
        
        if(getAge(input_bday.val()) < 18){
            dialogMessage('Sorry your age is below 18 years old, you cannot proceed.');
            return false;
        }

        return true;
    });
    
    //function that will compute age birthdate value.
    //Update: Changed the formula and computation on getting age by bithdate...
    function getAge(dateString){
        var dates = dateString.split("-");
        var d = new Date();
        var usermonth = dates[0];
        var userday = dates[1];
        var useryear = dates[2];
        
        var curday = d.getDate();
        var curmonth = d.getMonth()+1;
        var curyear = d.getFullYear();
        var age = curyear - useryear;

        if((curmonth < usermonth) || ( (curmonth == usermonth) && curday < userday   )){
            age--;
        }
        
        return age;
    }
    
    function checkIfInArray(Needle,Items){
        var index = Items.indexOf(Needle);
        if(index != -1){
            return true;
        }else{
            return false;
        }
    }
    
    //custom functions here, we just copied the functions from SFM because we are having conflict in jquery if we're going
    //to used the $ sign in dealer profile page beacuse of prototype.js
    function documentWriteParamCustoms(){
        var style = '<style>' + 
                    '   .ui-button-icon-only .ui-icon{left: -3%;}' +
                    '   .ui-button-icon-only .ui-icon, .ui-button-text-icon-primary .ui-icon, .ui-button-text-icon-secondary .ui-icon, .ui-button-text-icons .ui-icon, .ui-button-icons-only .ui-icon{top: 1%;}' +
                    '</style>';
        var dialoHolder = '<div style="display:none;" id="dialog-message" title="Basic dialog"><p>Message here</p></div>';
        dialoHolder += '<div style="display:none;" id="dialog-message-with-button" title="Basic dialog"><p>Message here</p></div>';
        dialoHolder += '<div style="display:none;" id="dialog-message-etc" title="Basic dialog"></div>';

        $jq(document.body).append(style);
        $jq(document.body).append(dialoHolder);
    }
    
    function dialogMessageWithButton(message,btnFunction){
        $jq( "#dialog-message-with-button p" ).html(message);
        $jq( "#dialog-message-with-button" ).dialog({
            autoOpen: false, 
            modal: true,
            position: 'center',
            height: 'auto',
            width: 'auto',
            resizable: false,
            title: 'DSS Message',
            buttons: btnFunction
        });
        $jq( "#dialog-message-with-button" ).dialog( "open" );
    }
    
    function dialogMessage(message){
        $jq( "#dialog-message p" ).html(message);
        $jq( "#dialog-message" ).dialog({
            autoOpen: false, 
            modal: true,
            position: 'center',
            resizable: false,
            minHeight: '100',
            minWidth: '350',
            title: 'DSS Message'
        });
        $jq( "#dialog-message" ).dialog( "open" );
    }
    
    function getQueryParameter( parameterName ) {
        var queryString = window.top.location.search.substring(1);
        var parameterName = parameterName + "=";
        
        if ( queryString.length > 0 ) {
            begin = queryString.indexOf ( parameterName );
            if ( begin != -1 ) {
            begin += parameterName.length;
            end = queryString.indexOf ( "&" , begin );
                if ( end == -1 ) {
                end = queryString.length
            }
            return unescape ( queryString.substring ( begin, end ) );
            }
        }
        
        return "null";
    } 
});


