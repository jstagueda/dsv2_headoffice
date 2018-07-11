$(function(){

    $('[name=submitForm]').click(function(){

        $.ajax({
            type    :   "post",
            data    :   $('[name=formSyncLogs]').serialize()+"&request=Get Logs",
            url     :   "pages/datamanagement/system_param_call_ajax/ajaxSyscLogs.php",
            success :   doAjaxResponse,
			beforeSend: ajaxLoader

        });
        return false;

    });

    $('[name=branch]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term},
                url     :   "pages/datamanagement/system_param_call_ajax/ajaxSyscLogs.php",
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Label,
                            value   :   item.Value,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=branchHidden]').val(ui.item.ID);
        }
    });

    $('[name=RunningDate]').datepicker();
});

function ajaxLoader(){
    $('.loader').html('Loading... Please wait...');
}


function doAjaxResponse(data)
{
	$('.loader').html("&nbsp;");
    $('.syncLogs').html(data);
}