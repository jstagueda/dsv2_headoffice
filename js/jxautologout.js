	$ = jQuery.noConflict();
	var i=0;
	$(function(){

	var session_updattimeout = setInterval(updatesessiontimeout, 1000); // 10 secs
	var ajaxischecker = setInterval(ajaxisbzchecker, 1000); // 10 secs
	var checccckkkk = 0 ;
	$("#indexbody").on('mousemove mousescroll mousedown keyup keypress',function (e) {
		idleTime = 0;
		var getcurredate = new Date();
		var getcurredate=getcurrenttime();
		setCookie("op1U2138",sessid, 1);
		setCookie("dtimeonly",getcurredate, 1);

		
	});			
	
	$(window).focus(function()
	{ //check if other tabs are still logged in
		//document.title = 'RESET4';
		idleTime = 0;
		//document.title = 'time lapsed7';	
		getcurredate = new Date();
		getcurredate = getcurrenttime();
		setCookie("op1U2138",sessid, 1);
		setCookie("dtimeonly",getcurredate, 1);		
		
		
				if ($.active < 2)	
					{					
					$.ajax({
						type        :   "post",
						dataType	:	'json',
						data        :   "action=checkloginstatus",
						url         :   "includes/scautologout.php",
						success     :   function(response){
							
									if (response['response']==0)
									{
										alert('You have been logged out due to inactivity. Please log-in again if you want to continue.');
										window.location.replace("login.php?logout");
									}
									else
									{
										//document.title = 'RESET1';
										//document.title = maxtimeout;
										
										idleTime = 0;
										
									}
							},
						beforeSend  :   function(){
							
						}
					});
					return false;
					}
				else{
				
					//document.title = "processing....";	
				}		
	
					
		});		
	
		$(window).blur(function(){ //check if other tabs are still logged in
			//document.title = 'Inactive';		
						
		});		

		$( document ).ajaxStart(function() {
		});

		$( document ).ajaxStop(function() { //################problem in here #################
		   if(ajaxisbzchecker()==0){
		   }
		   
		});
	});
	
	
	function updatesessiontimeout()
	{
		//alert('xxxxxx');	
		if (pgexcluded_array > -1 && pageid != 0 ) {  
			var currdate_latest=getcurrenttime();
			setCookie("dtimeonly",currdate_latest, 1);
			//document.title = 'exempted---'+$.active;
		}
		else{
	
	
			idleTime = idleTime + 1;
			//document.title = idleTime+'----'+$.active+'=='+ajaxisbzchecker()+'i='+i;
			//document.title = idleTime+'----'+$.active+'=='+ajaxisbzchecker();
			/* document.title = idleTime+'--'+$.active+'=='+'@@'+maxtimeoutxx+'||'+maxtimeoutraw; */
			if (ajaxisbzchecker()> 2 || $.active > 1){
						idleTime = 0;
						getcurredate = new Date();
						getcurredate = getcurrenttime();
						setCookie("op1U2138",sessid, 1);
						setCookie("dtimeonly",getcurredate, 1);
					}			
			
			

			//if (idleTime > 10) { 
			if (idleTime > maxtimeout) { 
						//var cookietime=getCookie("dtime");
						var cookie_currtime=getCookie("dtimeonly");
						var cookiesessionid=getCookie("op1U2138");
						var currentsessionid=sessid;
						
						
						// if (i==0){
							// checksqlpending();
						// }
						
						
						if (ajaxisbzchecker()> 2 || $.active > 1){
									idleTime = 0;
									getcurredate = new Date();
									getcurredate = getcurrenttime();
									setCookie("op1U2138",sessid, 1);
									setCookie("dtimeonly",getcurredate, 1);
								}

						else if ( ajaxisbzchecker()<=2 )	
								{
									if (cookiesessionid == currentsessionid){
										if(document.hasFocus()){
											updatesessionstatus();
											//document.title = cookietime+cookiesessionid;
											alert('You have been logged out due to inactivity. Please log-in again if you want to continue.');
											/* updatesessionstatus(); */
											window.location.replace("login.php?logout");
										}
										else{
											
											var timediff=parseTime(getcurrenttime())-parseTime(cookie_currtime);
											if (timediff>maxtimeout)
											//if (timediff>10)
											{
												updatesessionstatus();
												alert('You have been logged out due to inactivity. Please log-in again if you want to continue.');
												/* updatesessionstatus(); */
												window.location.replace("login.php?logout");
											}
										}
									}
								}

					return false;		
				 }
		}
	}	
	
	
function parseTime(s) {
   var c = s.split(':');
   return parseInt(c[0]) * 3600 + parseInt(c[1]) * 60 + parseInt(c[2]);
}

function updatesessionstatus(){



	$.ajax({
		type        :   "post",
		dataType	:	'json',
		data        :   "action=updatesessionstatus",
		url         :   "includes/scautologout.php",
		success     :   function(response){
					//alert('test timeer'+timer);
				}
		,
		beforeSend  :   function(){
			
		}
	});
	return false;
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function getcurrenttime()
{
		var currentdate = new Date();
		var datetime = currentdate.getHours() + ":"  
						+ currentdate.getMinutes() + ":" 
						+ currentdate.getSeconds();
		return datetime;					
}		  

function ajaxisbzchecker()
{
	if($.active>0){
		ajaxcounter++;
	}
	else if(ajaxcounter>1 && $.active==0){
		ajaxcounter=0;
	}
	return ajaxcounter;
}

function checksqlpending(){

//alert(i);
	$.ajax({
		type        :   "post",
		dataType	:	'json',
		data        :   "action=checkpendingsql",
		url         :   "includes/scautologout.php",
		success     :   function(response){
					if(response['response'] = 'success')
					{
					 i=0;
					}
				}
		,
		beforeSend  :   function(){
			
		}
	});
	return false;
}
