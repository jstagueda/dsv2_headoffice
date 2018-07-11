
$(function(){ 
	

    $('[name=btnSearch]').click(function()
	{
		return showPage(1);
	});	
	
	
	$('input:submit').click(function(){
	$('input:submit').attr("disabled", true);	
	});

	
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
		
		processData: false,

		contentType: false,
        url         :   "pages/datamanagement/system_param_call_ajax/ajaxnationaliddumping.php",
        success     :   function(data){
            $('.loader').html('&nbsp;');
			$('.pgLoading').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('On going process... Please wait...').hide().fadeIn();
        }
    });
    
    return false;
    
}

