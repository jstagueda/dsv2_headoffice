$(function(){
    
    //date picker
    $(function(){        
        $("#datestart, #dateend, #collectiondate").datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
    
    autocomplete('[name=codefrom]', '[name=codefromHidden]');
    autocomplete('[name=codeto]', '[name=codetoHidden]');
    
    $('[name=branchx]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sfm/param_ajax_calls/ajaxOnlineTopSellerReport.php",
                data    :   {branch :   request.term},
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
            $('[name=branch]').val(ui.item.ID);
        }
    });
});

function showPage(page){
    
    $('[name=page]').val(page);
	var RPP = 0
    $.ajax({
        type        :   "post",
		dataType	:	"json",
        url         :   "pages/sfm/param_ajax_calls/ajaxOnlineTopSellerReport.php",
        data        :   $('[name=onlinetopsellersreportform]').serialize(),
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('.pgLoad').html(data['data_handler']).hide().fadeIn('slow');
			if(	$("[name=recordtoshow]").val() > 0 ){
				RPP = $("[name=recordtoshow]").val(); // total View show...
			}else{
				RPP = 10;
			}
			$(".pagination").html(Pagination(page,RPP,data['num']));
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...');
        }
    });
    
    return false;
    
}

function autocomplete(obj, objhidden){
    
    $(obj).autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   'post',
                dataType:   'json',
                url     :   'pages/sfm/param_ajax_calls/ajaxOnlineTopSellerReport.php',
                data    :   {
                    searched    :   request.term,
                    sfmlevel    :   $('[name=level]').val(),
					Branch		:	$('[name=branch]').val()
                },
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
            $(objhidden).val(ui.item.ID);
        }
    });
    
    return false;    
}

