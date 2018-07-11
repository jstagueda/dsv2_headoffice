
/* 
  @package TPI DSS Global User Interface(s)
  @author John Paul Pineda
  @email paulpineda19@yahoo.com
  @copyright 2013 John Paul Pineda
  @version 1.0 August 8, 2013
  
  @description ?  
*/
var $tpi = jQuery.noConflict();

var jquery_tools_minified_script = document.createElement('script');
jquery_tools_minified_script.type = "text/javascript";
jquery_tools_minified_script.src = "js/jquery.tools.min.js";



$tpi(document).ready(function() {
   
    $tpi("#tpi_about").click(function(){
        //jQuery("#tooltip_about").toggle('fast');
        $tpi("#tooltip_about").fadeIn('fast');
    });
  
    $tpi("#tpi_about").blur(function(){
        $tpi("#tooltip_about").fadeOut();
    });
 
     //use for about link in menu
	$tpi("#test_about2").click(function() 
	{
	 //$tpi("#tooltip_logout").show();
	 $tpi("#tooltip_logout").toggle('fast');
	
	});
 
     $tpi("#test_about2").blur(function(){
        $tpi("#tooltip_logout").fadeOut();
    });
 
  if(window.location.href.indexOf("pwx") > -1) {
		if (daysbefore_xp==0){
		alert("Hi "+uname+", your password will expire today. Please change your password now. Thank You.");
		}
		else if(daysbefore_xp<0){
		alert("Hi "+uname+", your password is beyond the duration of expiration.Please consider changing you password soon. Thank You.");
		}
		else{
		alert("Hi "+uname+", your password will expire in "+daysbefore_xp+" day(s).");
		}
	}
  
  
  //Electronic License Agreement
  	if (nlogged==1 && forcepwc!=1 )
	{
		var uiPos = [$(window).width() / 2 - 400, 100];	
		$tpi( "#dialogelectronicagreement p" ).html(licenseagreecontent);
		$tpi( "#dialogelectronicagreement" ).dialog({
			autoOpen: false,
			modal: true,
			//position: 'center',
			position: uiPos,
			height: 'auto',
			width: 'auto',
			maxWidth: "800px",
			resizable: false,
			draggable: false,
			title: 'Electronic License Agreement',
			buttons:{
				"Agree"   : function(){
									var x = $(this);
					
									//start of code to create backup
									dataname = 'unloadnewlylog';
									$tpi.ajax({
											type        :   "post",
											dataType	:	'json',
											data        :   "action="+dataname,
											url         :   "includes/scautologout.php",

											});
										//end
									x.dialog("close");	
									//return false;
							
				},
				"Decline"    : function(){$tpi(this).dialog("close");

									dataname = 'returntologin';
									
									$.ajax({
												type        :   "post",
												dataType	:	'json',
												data        :   "action="+dataname,
												url         :   "includes/scautologout.php",
												success     :   function(response){
															if (response['response'] == 0)
															{
																window.location.replace("login.php?logout");
															}
													},

											});

					}
			}
		});
		
		$tpi( "#dialogelectronicagreement" ).dialog( "open" );
		//return false;
		$tpi( ".dialogelectronicagreement" ).dialog({ dialogClass: 'no-close' });

	}
 
});






  





