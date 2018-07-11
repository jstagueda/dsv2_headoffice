<script src="js/popinbox.js"></script>
<script language="javascript"  type="text/javascript">
$ = jQuery.noConflict();
$(function(){
	
	$("[name=txtStartDate]" ).datepicker({  
					maxDate: new Date(),
					changeMonth :   true,
					changeYear  :   true					
	});
	$("[name=txtEndDate]").datepicker({ 
			minDate: 0,
			changeMonth :   true,
			changeYear  :   true					
	});
			
	 $('input[name="Type"]').click(function(){
			
			if ($(this).is(':checked')){
				html = '';
				if($(this).val() == 'BulkUpload'){
						// $("#dynamicTable").hide();
						html+='<tr>';
						html+='<td colspan = "5" align = "center"> <input type="file" name="fileToUpload" id="fileToUpload"></td>';
						html+='</tr>';
				}else{
							html+='<tr align="center" class="trheader" style="border-bottom:1px solid #FFA3E0; background:#FFDEF0;">';
							html+='<td width="4%" class="borderBR"><strong>Action</strong></td>';
							html+='<td width="6%" class="borderBR"><strong>Line No.</strong></td>';
							html+='<td width="10%" class="borderBR"><strong>Item Code</strong></td>';
							html+='<td width="22%" class="borderBR"><strong>Item Description</strong></td>';			
							html+='<td width="13%" class="borderBR"><strong>PMG</strong></td>';
							html+='</tr>';
							html+='<tr align="center" class="trlist">';
							html+='<td class="borderBR">';
							html+='<input name="btnRemove1" type="button" class="btn" value="Remove">';
							html+='</td>';
							html+='<td class="borderBR"><div align="center">1</div></td>';
							html+='<td class="borderBR">';
							html+='<div align="center">';
							html+='<input name="txtProdCode1" type="text" class="txtfieldg" id="txtProdCode1" style="width: 70px;" value="" onkeypress="return selectItemCode(this.id);" />';
							html+='<input name="hProdID1" type="hidden" id="hProdID1" value="" />';
							html+='</div>';
							html+='</td>';
							html+='<td class="borderBR">';
							html+='<div align="center">';
							html+='<input name="txtProdDesc1" type="text" class="txtfield" id="txtProdDesc1" style="width: 95%;" readonly="yes" />';
							html+='</div>';
							html+='</td>';
							html+='<td class="borderBR">';
							html+='<div align="center">';
							html+='<select name="txtbPmg1" id = "txtbPmg1" class = "txtfield" style="width: 80%">';
							html+='<option value="1">CFT</option>';
							html+='<option value="2">NCFT</option>';
							html+='<option value="3">CPI</option>';
							html+='</select>';
							html+='</div>';
							html+='</td>';			
							html+='</tr>';
				
				}
				$("#dynamicTable").html(html);
			}
		});
	
	
	
	
    //$('[name=txtStartDate], [name=txtEndDate]').datepicker({
    //    changeMonth :   true,
    //    changeYear  :   true
    //});

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

function addRow(){

    var index = 0;

    //if(e.keyCode == 13 || e.keyCode == 9){

        //removing all onkeypress to all row input for quantity
        index = eval($('#dynamicTable tr:last td:nth-child(2) div').html());
        $('[name=txtEQty'+ index +']').removeAttr("onkeypress");

        index += 1;

        $.ajax({

            type    :   "post",
            data    :   {index  :   index},
            url     :   "pages/datamanagement/tpi_document/ajax/process.php",
            success :   function(data){
                $('#dynamicTable').append(data);
                $('[name=txtProdCode'+ index +']').focus();

                if(index > 1){
                    $('[name=btnRemove1]').attr("onclick", "deleteRow(this.parentNode.parentNode.rowIndex)");
                }
            }

        });

    //}

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
    $('#dynamicTable .trlist').each(function(i){
		
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
       
        $(this).find('td:nth-child(9) select').attr({
            'name'  :   'txtbPmg' + (i+1),
            'id'    :   'txtbPmg' + (i+1)
        });

        count = i;
    });


    //removing the remove row functionality when only 1 row remaining
	// alert(count);
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
			dataType:	"json",
            data    :   {checkCode : $('[name=txtCode]').val()},
            url     :   "pages/datamanagement/tpi_document/ajax/process.php",
            success :   function(data){
               if(data['num_rows'] == 0){
                    $('[name=txtDescription]').removeAttr('disabled');
                    $('[name=promotype]').removeAttr('disabled');
                    $('[name=txtStartDate]').removeAttr('disabled');
                    $('[name=txtEndDate]').removeAttr('disabled');
					
                    $('[name=txtProdCode1]').removeAttr('disabled');
                    $('[name=txtProdDesc1]').removeAttr('disabled');
                    $('[name=txtbPmg1]').removeAttr('disabled');
                    $('[name=txtDescription]').focus();
                    $('[name=btnSaves]').removeAttr('disabled');
					$('[name=txtCode]').attr("readonly","readonly");
                }else{
                    // popinmessage("Promo Code already exist.");
					if(confirm("CE Code already exist do you want to add new items for this CE Code?")==true){
						$('[name=txtDescription]').val(data['Desc']);
						$('[name=txtStartDate]').val(data['Start']);
						$('[name=txtEndDate]').val(data['End']);
						
						
						
						$('[name=txtDescription]').removeAttr('disabled');
						$('[name=promotype]').removeAttr('disabled');
						$('[name=txtStartDate]').removeAttr('disabled');
						$('[name=txtEndDate]').removeAttr('disabled');
						
					
					
						$('[name=txtDescription]').attr("readonly","readonly");
						$('[name=promotype]').attr("readonly","readonly");
						$('[name=txtStartDate]').attr("readonly","readonly");
						$('[name=txtEndDate]').attr("readonly","readonly");
						
						$('[name=txtProdCode1]').removeAttr('disabled');
						$('[name=txtProdDesc1]').removeAttr('disabled');
						$('[name=txtbPmg1]').removeAttr('disabled');
						$('[name=txtDescription]').focus();
						$('[name=btnSaves]').removeAttr('disabled');
						$('[name=txtCode]').attr("readonly","readonly");
					}
                }
            }
        });
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
	if($("input[name=Type]:checked").val() == 'Details' ){
		$.ajax({
			type        :   "post",
			data        :   $('[name=formCreateSpecialPromo]').serialize()+"&action=save",
			url         :   "pages/datamanagement/tpi_document/ajax/process.php",
			success     :   function(data){
				$( "#dialog-message p" ).html("CE CODES and SKU SUCCESFULY CREATED..");
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
	}else{
		// bulk upload..
		$("form").submit();
	}
    return false;
}


function selectItemCode(item){

    var index = item.replace("txtProdCode", "");
	
	var cnt = $(".trlist").length;
	var xarray = new Array()
	for(var i = 1; cnt >= i; i++){
		if(index != i){
			xarray.push($("[name=txtProdCode"+i+"]").val());
		}
	}
	
    $('#'+ item).autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                url     :   "includes/jxCreateSpecialPromo.php",
                dataType:   "json",
                data    :   {promocode  :   request.term,arrayprodid:xarray},
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
			addRow();
        }
    });
}

function confirmCancel(){
    confirmationpopinwithredirection("Do you want to cancel?", "index.php");
    return false;
}
</script>

<style type="text/css">
    
div.autocomplete {
  position:absolute;
  width:300px;
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;} 
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px solid #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}

.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
.ui-datepicker{display:none;}
.trheader td{padding:5px;}
.trlist td{padding:2px;}
</style>

<body>
<form name="formCreateSpecialPromo"  enctype="multipart/form-data" method="post" action="pages/datamanagement/tpi_document/fileupload.php" style="min-height: 610px;">
    
    <input name="counter" value="1" type="hidden">
    
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="topnav">
                <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                        <td width="70%" class="txtgreenbold13" align="left"></td>
                        <td width="70%" align="right">&nbsp;
                            <a class="txtblueboldlink" href="index.php?pageid=80">Data Management</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td>
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                        <td width="70%">&nbsp;<a class="txtgreenbold13">Create CE Codes Maintenance Maintenance</a></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="tabmin"></td> 
            <td class="tabmin2"><div align="left" class="padl5 txtredbold"><b>CE HEADER</b></div></td>
            <td class="tabmin3">&nbsp;</td>
        </tr>
    </table>
    <!-- FORM HEADER -->
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        <tr>
            <td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
			<td>
                            <table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" style="margin-left:4%">
								<tr>
                                    <td width="20%">&nbsp;</td>
									<td width="5%"></td>
                                    <td>&nbsp;</td>
								</tr>			
								<tr>
									<td height="20" align="left"><strong>Code</strong></td>
									<td align="left">:</td>
									<td height="20">
                                        <input type="text" class="txtfieldg" value="" name="txtCode" onkeydown="return CheckPromo(event);" id="txtCode" autocomplete="off">
                                    </td>
                                </tr>
								<tr>
									<td height="20" align="left"><strong>Description</strong></td>
									<td align="left">:</td>
									<td height="20">
                                        <input type="text" class="txtfieldg" value="" name="txtDescription" id="txtDescription" autocomplete="off">
                                    </td>
                                </tr>
								<tr>
                                    <td height="20" align="left"><strong>Start Date</strong></td>
									<td align="left">:</td>
                                    <td width="20%" height="20">
                                        <input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" size="20" readonly="yes" disabled = "true" value="<?php echo $today; ?>">
                                        <i>(e.g. MM/DD/YYYY)</i>
                                    </td>
								</tr>
								<tr>
                                    <td height="20" align="left"><strong>End Date</strong></td>
									<td align="left">:</td>
                                    <td width="20%" height="20">
                                        <input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" size="20" readonly="yes" value="<?php echo $end; ?>" disabled = "true">
                                        <i>(e.g. MM/DD/YYYY)</i>
                                    </td>
								</tr>
								<tr>
                                    <td height="20" align="left"><strong>Type</strong></td>
									<td align="left">:</td>
                                    <td width="20%" height="20">
										<input type="radio" checked name="Type" value="Details">Details<br>
										<input type="radio" name="Type" value="BulkUpload">Bulk Upload
                                    </td>
								</tr>
								
								<tr>
                                    <td colspan="3" height="20">&nbsp;</td>
								</tr>
                            </table>
						</td>
						
                    </tr>
				</table>
            </td>
        </tr>
    </table>
    <br />
    <!-- END FORM HEADER -->

    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <!-- DYNAMIC BUYIN REQUIREMENT TABLE START HERE -->
			<td width= "100%">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td class="tabmin"></td> 
						<td class="tabmin2"><div align="left" class="padl5 txtredbold"><b>Details</b></div></td>
						<td class="tabmin3">&nbsp;</td>
					</tr>
				</table>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">                    
							<tr>
								<td>
									<div style="max-height:220px; overflow:auto;">
										<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicTable" class="bgFFFFFF">
											<tr align="center" class="trheader" style="border-bottom:1px solid #FFA3E0; background:#FFDEF0;">
												<td width="4%" class="borderBR"><strong>Action</strong></td>
												<td width="6%" class="borderBR"><strong>Line No.</strong></td>
												<td width="10%" class="borderBR"><strong>Item Code</strong></td>
												<td width="22%" class="borderBR"><strong>Item Description</strong></td>			
												<td width="13%" class="borderBR"><strong>PMG</strong></td>
											</tr>
											<tr align="center" class="trlist">
												<td class="borderBR">
													<input name="btnRemove1" type="button" class="btn" value="Remove">
												</td>
												<td class="borderBR"><div align="center">1</div></td>
												<td class="borderBR">
													<div align="center">
														<input name="txtProdCode1" type="text" class="txtfieldg" id="txtProdCode1" style="width: 70px;" value="" onkeypress="return selectItemCode(this.id);" disabled = "true" />
														<input name="hProdID1" type="hidden" id="hProdID1" value="" />
													</div>
												</td>
												<td class="borderBR">
													<div align="center">
														<input name="txtProdDesc1" type="text" disabled = "true" class="txtfield" id="txtProdDesc1" style="width: 95%;" readonly="yes" />
													</div>
												</td>			
														
												<td class="borderBR">
													<div align="center">
														<select name="txtbPmg1" id = "txtbPmg1" disabled = "true" class = "txtfield" style="width: 80%">
															<option value="1">CFT</option>
															<option value="2">NCFT</option>
															<option value="3">CPI</option>
														</select>
													</div>
												</td>			
											</tr>
										</table>
									</div>
								</td>
							</tr>
				</table>
			</td>
		<!-- DYNAMIC BUYIN REQUIREMENT TABLE END HERE -->
		<td width= "1%">&nbsp;</td>
		<!-- DYNAMIC ENTITLEMENT TABLE START HERE -->
		
		<!-- DYNAMIC ENTITLEMENT TABLE END HERE -->
        </tr>
    </table>
    <br>
    <table width="98%" align="left"  border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <input name='btnSaves' type='button' class='btn' value='Save' id = 'savebtn' disabled = "true"  onclick='return confirmSave();'>
                <input name='btnCancel' type='button' class='btn' value='Cancel' onclick='return confirmCancel();'>
            </td>			
        </tr>
    </table>
    <br><br>
</form>
    
<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>