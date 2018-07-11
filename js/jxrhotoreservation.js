

$(document).ready(function(){ //disables the submit button on form load
     $('input[type="submit"]').attr('disabled','disabled');
     
 });


$(function(){ 
	

    $('[name=btnSearch]').click(function(){
        // return showPage(1,'');
        // return false;
    // });
    var filename = $('input[name="file"]').val(); //this will enable the confirmation 
		  
		if (!filename==""){
		
			var conf=confirm("Continue upload of \" " + filename + " \"   ?");
		if (conf == true){
			
        return showPage(1);
        return false;
			}
		}
	});	
	
	
// window.onbeforeunload = function(e) {   //detects unload
  // return 'Dialog text here.';
// };	
	
    
	// $(window).unload(function() {      //detects unload
      // alert('Handler for .unload() called.');
// });

	
	$('input[name="file"]').change(function(){
		$('input:submit').removeAttr("disabled");	
	//	return exequerry();
		return false;
   
	});
	
	// $('input:file').click(function(){
	// $('input:submit').removeAttr("disabled");	
	// });
	
	
	$('input:submit').click(function(){
	$('input:submit').attr("disabled", true);	
	});
	
	
	
	
	// $('input[name=formPrompt]').submit(function() {
  // $(this).find("button[name=btnSearch]").prop('disabled',true);
// });

	
});



function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}
	



function showPage(page){  //ajax function that will display the data from the ajaxSalesReportPerIGS.php
	
	$('[name=page]').val(page);
	var y = $('[name=fileid]').val(1);
	
	
	data = new FormData($('[name=formPrompt]')[0]); //data processing for file using ajax with form uploading.
         
    $.ajax({
        type        :   "post",
        'data'        :   data ,     //+ "action='aaaaa'",
		dataType:	'json',
		processData: false,
		
		contentType: false,
        url         :   "pages/datamanagement/system_param_call_ajax/ajaxrhotoreservation.php",
        success     :   function(response){
		
			// alert(response['response']+'+++'+response['message']);	
		
		
            // $('.loader').html('&nbsp;');
				if(response['response'] == 'fail.'){
				
				//alert(response['message']);
				popinmessage(response['message']);
				}
				else{
				//alert(response['message']);
				popinmessage(response['message']);
				}
				//popinmessage(data['message']);
				

        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...').hide().fadeIn();
        }
    });
    
    return false;
    
}




