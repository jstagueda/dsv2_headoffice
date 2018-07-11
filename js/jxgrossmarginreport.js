
$(function(){


	
	$('[name=FromDate],[name=ToDate]').datepicker({
		changeYear	:	true,
		changeMonth	:	true
	});

	
	$('[name=item]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchitem   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code	:  item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=itemid]').val(ui.item.ID);
			$('[name=item]').val(ui.item.label);
			$('[name=itemname]').val(ui.item.label);
			$('[name=itemcode]').val(ui.item.Code);
        }
    });
	
	
	$('[name=brand]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchbrand   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code	:  item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=brandid]').val(ui.item.ID);
			$('[name=brand]').val(ui.item.label);
			$('[name=brandname]').val(ui.item.label);
			$('[name=brandcode]').val(ui.item.Code);
        }
    });
	
	
	$('[name=subbrand]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchsubbrand   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code   :   item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=subbrandid]').val(ui.item.ID);
			$('[name=subbrand]').val(ui.item.label);
			$('[name=subbrandname]').val(ui.item.label);
			$('[name=subbrandcode]').val(ui.item.Code);
        }
    });	
	
	
	$('[name=form]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchform   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code   :   item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=formid]').val(ui.item.ID);
			$('[name=form]').val(ui.item.label);
			$('[name=formname]').val(ui.item.label);
			$('[name=formcode]').val(ui.item.Code);
        }
    });		
	

	$('[name=subform]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchsubform   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code   :   item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=subformid]').val(ui.item.ID);
			$('[name=subform]').val(ui.item.label);
			$('[name=subformname]').val(ui.item.label);
			$('[name=subformcode]').val(ui.item.Code);
        }
    });		
	
	
	
	$('[name=size]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchsize   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code   :   item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=sizeid]').val(ui.item.ID);
			$('[name=size]').val(ui.item.label);
			$('[name=sizename]').val(ui.item.label);
			$('[name=sizecode]').val(ui.item.Code);
        }
    });		

	
	$('[name=productline]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchpl   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code   :   item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
			$('[name=productline]').val(ui.item.label);
            $('[name=productlineid]').val(ui.item.ID);
			$('[name=productlinename]').val(ui.item.label);
			$('[name=productlinecode]').val(ui.item.Code);
        }
    });		
	
	

	
	$('[name=productcategory]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchcategory   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code   :   item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
			$('[name=productcategory]').val(ui.item.label);
            $('[name=productcategoryid]').val(ui.item.ID);
			$('[name=productcategoryname]').val(ui.item.label);
			$('[name=productcategorycode]').val(ui.item.Code);
        }
    });		
	
	
	$('[name=branchz]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchbranch   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code	:  item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
			$('[name=branchz]').val(ui.item.label);
            $('[name=branchid]').val(ui.item.ID);
			$('[name=branchname]').val(ui.item.label);
			$('[name=branchcode]').val(ui.item.Code);
        }
    });	
	
	
	/*
	$('[name=Product]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchProduct   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label  :   item.Name,
							Name   :   item.Name,
                            ID     :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=ProductHidden]').val(ui.item.ID);
			$('[name=ProductName]').val(ui.item.Name);
        }
    }); */
    
	
	/*$('[name=branch]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
                data    :   {searchbranch   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return
						{
                            label  :   item.Namecode,
							Name   :   item.Nameonly,
                            ID     :   item.ID,
							Code   :   item.Code 
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
			$('[name=branch]').val(ui.item.label);
            $('[name=branchid]').val(ui.item.ID);
			$('[name=branchname]').val(ui.item.label);
			$('[name=branchcode]').val(ui.item.Code);
        }
    });			
	
	*/
    $('[name=btnSearch]').click(function(){
        return showPage(1,'');
        return false;
    });
    
    $('[name=btnPrint]').click(function(){
		
		var plgrp   = $("#plgrouping option:selected").text();
		var pmgtypecode = $("#pmgtype option:selected").text();
		
        var param = $('[name=formPrompt]').serialize()+'&plgrp='+plgrp+'&pmgtypecode='+pmgtypecode;
		 //var param = $('[name=formPrompt]').serialize()+'&plgrp='+plgrp+'&pmgtype='+pmgtype;

        var objWin;
        popuppage = "pages/sales/grossmarginreport_print.php?" + param ;

        if (!objWin){
           objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }
    });
    
});

function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}

function showPage(page){
	
	$('[name=page]').val(page);
        
    $.ajax({
        type        :   "post",
        data        :   $('[name=formPrompt]').serialize(),
        url         :   "pages/sales/call_ajax/ajaxgrossmarginreport.php",
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


