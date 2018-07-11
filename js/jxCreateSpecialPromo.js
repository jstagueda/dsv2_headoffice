$(function(){

    $('[name=txtStartDate], [name=txtEndDate]').datepicker({
        changeMonth :   true,
        changeYear  :   true
    });

    checkValue('bpage');
    checkValue('epage');

    $('input#MaxAvail').each(function(index){
        checkValue($(this).attr('name'));
    });
	
	$('[name=txtCode]').keyup(function(){
			
		var validation = /^[A-z 0-9]*$/i;
		var val = $(this).val();
		var string = '';
		
		if(!validation.test($(this).val())){
			
			for(var x = 0; x < $(this).val().length; x++){
			
				if(!validation.test(val.charAt(x))){
					string += '';
				}else{
					string += val.charAt(x);
				}
				
			}
			
			$(this).val(string);
		}
		
	});
	
	
	$('[name=txtDescription]').keyup(function(){
		
		var validation = /^[A-z 0-9]*$/i;
		var val = $(this).val();
		var string = '';
		
		if(!validation.test($(this).val())){
			
			for(var x = 0; x < $(this).val().length; x++){
			
				if(!validation.test(val.charAt(x))){
					string += '';
				}else{
					string += val.charAt(x);
				}
				
			}
			
			$(this).val(string);
		}
		
	});

});

function addRow(e){

    var index = 0;

    if(e.keyCode == 13 || e.keyCode == 9){

        //removing all onkeypress to all row input for quantity
        index = eval($('#dynamicTable tr:last td:nth-child(2) div').html());
        $('[name=txtEQty'+ index +']').removeAttr("onkeypress");

        index += 1;

        $.ajax({

            type    :   "post",
            data    :   {index  :   index},
            url     :   "includes/jxCreateSpecialPromo.php",
            success :   function(data){
                $('#dynamicTable').append(data);
                $('[name=txtProdCode'+ index +']').focus();

                if(index > 1){
                    $('[name=btnRemove1]').attr("onclick", "deleteRow(this.parentNode.parentNode.rowIndex)");
                }
            }

        });

    }

}

function deleteRow(index){

    var count = 0;

    //check the rows if the index is equal to clicked button's index then remove the row
    $('#dynamicTable tr').each(function(i){
        if(i == index){
            $(this).remove();
        }
    });

    //updating the rows numbering
    $('#dynamicTable tr').each(function(i){
        $(this).find('td:first input').attr('name', 'btnRemove' + (i+1)); //remove button
        $(this).find('td:nth-child(2) div').html(i+1); //line no.
        $(this).find('td:nth-child(3) input:first').attr({ //item code textbox
            'name'  :   'txtProdCode' + (i+1),
            'id'    :   'txtProdCode' + (i+1)
        });
        $(this).find('td:nth-child(3) input:last').attr({ //item code hidden
            'name'  :   'hProdID' + (i+1),
            'id'    :   'hProdID' + (i+1)
        });
        $(this).find('td:nth-child(4) input').attr({ //item description
            'name'  :   'txtProdDesc' + (i+1),
            'id'    :   'txtProdDesc' + (i+1)
        });
        $(this).find('td:nth-child(5) select').attr({ //
            'name'  :   'cboCriteria' + (i+1),
            'id'    :   'cboCriteria' + (i+1)
        });
        $(this).find('td:nth-child(6) input').attr({
            'name'  :   'txtQty' + (i+1),
            'id'    :   'txtQty' + (i+1)
        });
        $(this).find('td:nth-child(7) select').attr({
            'name'  :   'cboECriteria' + (i+1),
            'id'    :   'cboECriteria' + (i+1)
        });
        $(this).find('td:nth-child(8) input').attr({
            'name'  :   'txtEQty' + (i+1),
            'id'    :   'txtEQty' + (i+1)
        });
        $(this).find('td:nth-child(9) select').attr({
            'name'  :   'txtbPmg' + (i+1),
            'id'    :   'txtbPmg' + (i+1)
        });

        count = i;
    });

    //add onkeypress to input to the last row
    $('[name=txtEQty'+ (count + 1) +']').attr("onkeypress", "return addRow(event);");

    //removing the remove row functionality when only 1 row remaining
    if(count == 0){
        $('[name=btnRemove'+ (count + 1) +']').removeAttr('onclick');
    }

    return false;
}

function checkValue(name){
    $('[name='+name+']').blur(function(){
        if($('[name='+name+']').val() == ""){
            $('[name='+name+']').val(0);
        }
    });
}

function CheckPromo(e){
    if(e.which == 13 || e.which == 9){
        //check if the code inserted is already exist.
        $.ajax({
            type    :   "post",
            data    :   {checkCode : $('[name=txtCode]').val()},
            url     :   "includes/jxCreateSpecialPromo.php",
            success :   function(data){
                if(data == 0){
                    $('[name=txtDescription]').removeAttr('disabled');
                    $('[name=promotype]').removeAttr('disabled');
                    $('[name=txtStartDate]').removeAttr('disabled');
                    $('[name=txtEndDate]').removeAttr('disabled');
                    $('[name=bpage]').removeAttr('disabled');
                    $('[name=epage]').removeAttr('disabled');
                    $('[name=txtProdCode1]').removeAttr('disabled');
                    $('[name=txtProdDesc1]').removeAttr('disabled');
                    $('[name=cboCriteria1]').removeAttr('disabled');
                    $('[name=txtQty1]').removeAttr('disabled');
                    $('[name=cboECriteria1]').removeAttr('disabled');
                    $('[name=txtEQty1]').removeAttr('disabled');
                    $('[name=txtbPmg1]').removeAttr('disabled');
                    $('[name=txtDescription]').focus();
                    $('[name=btnSaves]').removeAttr('disabled');
                }else{
                    popinmessage("Promo Code already exist.");
                }
            }
        });
    }
}

function Nextfield(){
    if($('[name=txtDescription]').val() == ""){
        alert("Please insert Promo Description.");//change to popinmessage
        $('[name=promotype]').val(0);
        $('[name=txtDescription]').focus();
        return false;
    }else{
        $('[name=btnSaves]').removeAttr('disabled');
        $('[name=txtStartDate]').focus();
        return false;
    }
}

function RemoveInvalidChars(me){
    if(me.value != ""){
        var checker = /^[0-9]+$/;
        if(!checker.test(me.value)){
            me.value = 0;
        }
    }
    return false;
}

function confirmSave(){

    if($('[name=txtCode]').val() == ""){
        popinmessage("Please insert Promo Code.");
        $('[name=txtCode]').focus();
        return false;
    }

    if($('[name=txtDescription]').val() == ""){
        popinmessage("Please insert Promo Description.");
        $('[name=txtDescription]').focus();
        return false;
    }

    if($('[name=promotype]').val() == 0){
        popinmessage("Please select Promo Type.");
        $('[name=promotype]').focus();
        return false;
    }

    if($('[name=txtStartDate]').val() == 0){
        popinmessage("Please insert Start Date.");
        $('[name=txtStartDate]').focus();
        return false;
    }

    if($('[name=txtEndDate]').val() == 0){
        popinmessage("Please insert End Date.");
        $('[name=txtEndDate]').focus();
        return false;
    }

    var err = 0;
    var counter = 0;
    $('#dynamicTable tr').each(function(i){
        if($('[name=hProdID'+ (i+1) +']').val() == ""){
            err++;
        }
        counter++;
    });

    $('[name=counter]').val(counter);

    if(err > 0){
        popinmessage("Please select Item Code.");
        return false;
    }

    $.ajax({
        type        :   "post",
        data        :   $('[name=formCreateSpecialPromo]').serialize()+"&action=save",
        url         :   "includes/jxCreateSpecialPromo.php",
        success     :   function(data){
            $( "#dialog-message p" ).html("New Special Promo successfully created.");
            $( "#dialog-message" ).dialog({
                autoOpen: false,
                modal: true,
                position: 'center',
                height: 'auto',
                width: 'auto',
                resizable: false,
                title: 'DSS Message',
                buttons:{
                    "Ok"   : function(){
                        $(this).dialog("close");
                        window.location.href = "";
                    }
                }
            });
            $("#dialog-message").dialog( "open" );
        },
        beforeSend  :   function(){
            $( "#dialog-message p" ).html("Saving Special Promo...");
            $( "#dialog-message" ).dialog({
                autoOpen: false,
                modal: true,
                position: 'center',
                height: 'auto',
                width: 'auto',
                resizable: false,
                title: 'DSS Message'
            });
            $("#dialog-message").dialog("open");
        }
    });

    return false;
}


function selectItemCode(item){

    var index = item.replace("txtProdCode", "");

    $('#'+ item).autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                url     :   "includes/jxCreateSpecialPromo.php",
                dataType:   "json",
                data    :   {promocode  :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            value   :   item.Value,
                            label   :   item.Label,
                            Name    :   item.Name,
                            ID      :   item.ID,
                            Code    :   item.Code,
                            PMGID   :   item.PMGID,
                            PMGCode :   item.PMGCode,
                            Price   :   item.UnitPrice
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=hProdID'+ index +']').val(ui.item.ID);
            $('[name=txtProdDesc'+ index +']').val(ui.item.Name);
            if(ui.item.PMGID == 1){
                htmltext = '<option value="1">CFT</option>';
                htmltext += '<option value="3">CPI</option>';
            }else if(ui.item.PMGID == 2){
                htmltext = '<option value="2">NCFT</option>';
                htmltext += '<option value="3">CPI</option>';
            }else{
                htmltext = '<option value="3">CPI</option>';
            }
            $('[name=txtbPmg'+ index +']').html(htmltext);
        }
    });
}

function confirmCancel(){
    confirmationpopinwithredirection("Do you want to cancel?", "index.php?pageid=302");
    return false;
}