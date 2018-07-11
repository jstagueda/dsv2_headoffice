
$(document).ready(function(){ //disables the submit button on form load
     $('input[type="submit"]').attr('disabled','disabled');
     
 });

$(function(){ 
	
	displayall(1);
	
    $('[name=btnSearch]').click(function()
	{
		var filename = $('input[name="file"]').val(); //this will enable the confirmation 
		  
		if (filename) 
		{
			var startIndex = (filename.indexOf('\\') >= 0 ? filename.lastIndexOf('\\') : filename.lastIndexOf('/'));
			var filename = filename.substring(startIndex);
			
			if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) 
			{
				filename = filename.substring(1);
			}		  
		}	  
			  
		if (!filename=="")
		{

			/*	
			$.ajax({
				type        :   "post",
				data        :   {action : "checkfile",fname:filename},
				dataType    :    "json",
				url         :   "pages/datamanagement/system_param_call_ajax/ajaxpriceuploading.php",
				success     :   function(data){
					
							if (data['fileexist']==1)
							{
								var conf = confirm(" File was already loaded. Are you sure you want to continue upload of \" " + filename + " \" ?");
							
							}
							else
							{
			*/					
								var conf=confirm(" Continue upload of \" " + filename + " \" ?");
				//			}
							
							if (conf == true)
							{
								return showPage(1);
								return false;
							}										
/* 							
				},
				beforeSend  :   function(){
				   
				}
			}); */
									
			return false;
		
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
    popuppage = "pages/datamanagement/datamgt_priceuploading_print.php" + param;
    
    if (!objWin){			
        objWin = NewWindow(popuppage,'printps','1000','500','yes');
    }
            
    return false;    
}


function printreportlastupdated(){
    
    var param = "?"+$('[name=formPrompt]').serialize()+'&lstupd=1';            
    var objWin;
    popuppage = "pages/datamanagement/datamgt_priceuploading_print.php" + param;
    
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



function showPage(page){ 
	
	$('[name=page]').val(page);
	var y = $('[name=fileid]').val(1);
	
	
	data = new FormData($('[name=formPrompt]')[0]); 
       
  
    $.ajax({
        type        :   "post",
        'data'        :   data ,     
		dataType      : 'json',
		processData: false,
		contentType: false,
        url         :   "pages/datamanagement/system_param_call_ajax/ajaxpriceuploading.php",
        success     :   function(data){

				var filefordb = data['filefordb'] ;
				var errmsg = data['ErrorMessage'];
				var remrks =  data['remrks'];
				var rectype = data['rectype'];
				var linecounter = data['linecounter'];

				if(data['goterr']==1)
				{
					$.ajax({
						type        :   "post",
						data        :   {action : "createlog", filefordb : filefordb , errmsg : remrks , rectype : rectype, linecounter : linecounter },
						url         :   "pages/datamanagement/system_param_call_ajax/ajaxpriceuploading.php",
						success     :   function(data){
							
						},
						beforeSend  :   function(){

						}
					});					
				}	
			
			displayall(1);
			popinmessage(data['ErrorMessage']);

        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...').hide().fadeIn();
        }
    });
    
    return false;
    
}


function displayall(page){  
	
	$('[name=page]').val(page);
        
    $.ajax({
        type        :   "post",
        data        :   {action : "showallprice",page : page},
        url         :   "pages/datamanagement/system_param_call_ajax/ajaxpriceuploading.php",
        success     :   function(data){
			$('.loader').html('&nbsp;');
			$('.pgLoading').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
           
        }
    });
    
    return false;
    
}




