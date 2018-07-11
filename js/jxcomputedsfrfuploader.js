

$(document).ready(function(){ //disables the submit button on form load
     $('input[type="submit"]').attr('disabled','disabled');
     
 });
 

$(function(){ 
	

    $('[name=btnSearch]').click(function()
	{
		var filename = $('input[name="file"]').val(); //this will enable the confirmation 
		var loyalty  = $('[name=ReasonType]').val();
	
	    if(loyalty == '')
		{
			 alert('Please select Loyalty Program');
			 return false;
		}
			
		if (!filename=="")
		{
			var conf=confirm("Continue upload of \" " + filename + " \"   ?");
			if (conf == true)
			{
				
			return showPage(1);
			return false;
			}
		}
	});	

	
	$('input[name="file"]').change(function(){
		$('input:submit').removeAttr("disabled");	
		return false;
   
	});
	
	
	$('input:submit').click(function(){
	$('input:submit').attr("disabled", true);	
	});

	
});

	
function printreport(){
    
    var param = "?"+$('[name=formPrompt]').serialize();            
    var objWin;
    popuppage = "pages/datamanagement/datamgt_manualreservationupload_print.php" + param;
    
    if (!objWin){			
        objWin = NewWindow(popuppage,'printps','1000','500','yes');
    }
            
    return false;    
}


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
		
		processData: false,

		contentType: false,
        url         :   "pages/datamanagement/system_param_call_ajax/ajaxcomputedsfrfuploader.php",
        success     :   function(data){
            $('.loader').html('&nbsp;');
			$('.pgLoading').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...').hide().fadeIn();
        }
    });
    
    return false;
    
}

