//author: joebert italia
//date: 10/17/2013

$(function(){
    
	$('[name=RunningDate]').datepicker({
		changeMonth	:	true,
		changeYear	:	true
	});
	
    $('[name=eod]').click(function(){
        
        $.ajax({
            type    :   "post",
            url     :   "pages/datamanagement/UIInterfaceFileGenerator.php",
            data    :   {EOD : "Yes", RunningDate : $('[name=RunningDate]').val()},
            success :   function(data){
                $('.uiinterface').html(data);
                if($('.uiinterface').scrollTop() < $('.uiinterface')[0].scrollHeight){
                    $('.uiinterface').scrollTop($('.uiinterface')[0].scrollHeight);
                }
                $('[name=eod]').removeAttr('disabled');
                $('[name=eom]').removeAttr('disabled');
            },
            beforeSend  :   function(){
                $('.uiinterface').html("<p>Loading... Please wait...</p>");                
                $('[name=eod]').attr("disabled", "disabled");
                $('[name=eom]').attr("disabled", "disabled");
            }
        });
        
    });
    
    $('[name=eom]').click(function(){
        
        $.ajax({
            type    :   "post",
            url     :   "pages/datamanagement/UIInterfaceFileGenerator.php",
            data    :   {EOM : "Yes", RunningDate : $('[name=RunningDate]').val()},
            success :   function(data){
                $('.uiinterface').html(data);
                if($('.uiinterface').scrollTop() < $('.uiinterface')[0].scrollHeight){
                    $('.uiinterface').scrollTop($('.uiinterface')[0].scrollHeight);
                }
                $('[name=eod]').removeAttr('disabled');
                $('[name=eom]').removeAttr('disabled');
            },
            beforeSend  :   function(){
                $('.uiinterface').html("<p>Loading... Please wait...</p>");
                $('[name=eod]').attr("disabled", "disabled");
                $('[name=eom]').attr("disabled", "disabled");
            }
        });
        
    });
    
});