$(document).ready(function(){
    $("#txtStartDate").datepicker();
    $("#txtEndDate").datepicker();

    $('[name=branchesList]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term},
                url     :   "pages/bpm/ajax_requests/AppliedPaymentReport.php",
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
            $('[name=branches]').val(ui.item.ID);
            $('[name=dealer_fromList]').val('');
            $('[name=dealer_from]').val(0);
            $('[name=dealer_toList]').val('');
            $('[name=dealer_to]').val(0);
        }
    });

    DealerList('dealer_fromList', 'dealer_from');
    DealerList('dealer_toList', 'dealer_to');
});

function DealerList(field, hiddenfield){

    $('[name='+field+']').autocomplete({
        source  :   function(request, response){

            if($('[name=branches]').val() == 0 || $('[name=branchesList]').val() == ''){
                alert("Please select Branch.");
                $('[name='+field+']').val('');
                $('[name='+hiddenfield+']').val(0);
                $('[name=branchesList]').focus();
                return false;
            }

            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searchedDealer   :   request.term, branchID    :   $('[name=branches]').val()},
                url     :   "pages/bpm/ajax_requests/AppliedPaymentReport.php",
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
            $('[name='+hiddenfield+']').val(ui.item.ID);
        }
    });
}

function openPopUp()
{
	var branches = $("[name=branches]").val();
	var start = $("#txtStartDate").val();
	var end = $("#txtEndDate").val();
	var dealer_from = $("[name=dealer_from]").val();
	var dealer_to = $("[name=dealer_to]").val();
	var tperdate = $("#tperdate").val();
	var tperbranch = $("#tperbranch").val();
	var err_msg = "", error_count = 0;

	if(branches == 0 || $("[name=branchesList]").val() == ""){
		err_msg += "*Branch Required \n";
		error_count++;
	}

	if(start == ""){
		err_msg += "*Start Date Required \n";
		error_count++;
	}

	if(end == ""){
		err_msg += "*End Date Required \n";
		error_count++;
	}

	if(dealer_from == 0 || $("[name=dealer_fromList]").val() == ""){
		err_msg += "*Dealer From Required \n";
		error_count++;
	}

	if(dealer_to == 0 || $("[name=dealer_toList]").val() == ""){
		err_msg += "*Dealer To Required \n";
		error_count++;
	}
	if(tperdate == 0){
		err_msg += "*With Total per Date Required \n";
		error_count++;
	}
	if(tperbranch == 0){
		err_msg += "*With Total per Branch Required \n";
		error_count++;
	}



	if(error_count == 0){
		var objWin;
		popuppage = "pages/bpm/confirminvoiceshoprint.php?BranchID="+ branches + "&StartDate=" + start + "&EndDate=" + end + "&DealerFrom=" + dealer_from + "&DealerTo=" + dealer_to  + "&tperdate=" + tperdate  + "&tperbranch=" + tperbranch ;

		if (!objWin)
		{
			objWin = NewWindow(popuppage,'printps','800','500','yes');
		}
		return false;
	}else{
		alert(err_msg);
	}
}

function NewWindow(mypage, myname, w, h, scroll)
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}