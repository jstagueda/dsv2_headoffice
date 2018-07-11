

<?php
require_once("../../../initialize.php");
global $database;
//$head = $database->execute("select * from tpi_document where ID =".$_GET['ID']);

$head = $database->execute("SELECT
								ce.CE_Code CE_Code,
								ce.CE_Description CE_Description,
								ce.Effectivity_Date_Start Effectivity_Date_Start,
								ce.Effectivity_Date_End Effectivity_Date_End, 
								cm.Description chargetodeptname,
								cm.ID chargetodeptID
								FROM tpi_document ce
								LEFT JOIN codemaster cm ON ce.ChargedToDepartment=cm.CodeValue
								WHERE ce.ID =".$_GET['ID']);
if($head->num_rows){
	while($r = $head->fetch_object()){
		$Code 		 = $r->CE_Code;
		$Description = $r->CE_Description;
		$Start 	     = date("m/d/Y",strtotime($r->Effectivity_Date_Start));
		$End 		 = date("m/d/Y",strtotime($r->Effectivity_Date_End));
		$chargedtoname = $r->chargetodeptname;
		$chargedtoID = $r->chargetodeptID;

	}
}


$exemptedpws = GetSettingValue($database, 'DISPWSADD');

$allexemptedpws = explode(",",$exemptedpws);

	if(in_array("$Code",$allexemptedpws))
	{
		$isexempted=1;
	}
	else
	{
		$isexempted=0;
	}	


$details = $database->execute("select t.*,p.Code ProductCode,p.Name ProductName from tpi_documentdetails t inner join product p on p.ID = t.ProductID where t.tpi_document_ID =".$_GET['ID']);

$pageid = $_GET['pageid'] ;


?>
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="../../../css/ems.css">
<!-- product list -->

<link rel="stylesheet" type="text/css" href="../../../css/jquery-ui-1.8.5.custom.css"/>
<script language="javascript" src="../../../js/jquery-1.9.1.min.js"></script>
<script language="javascript" src="../../../js/jquery-ui-1.10.0.custom.min.js"></script>
<script language="javascript" src="../../../js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="../../../js/popinbox.js"  type="text/javascript"></script>
<script language="javascript" src="../../../js/popinbox.js"  type="text/javascript"></script>
<script language="javascript"  type="text/javascript">
var itemlistarray = [];
var itemlistarray_UI = [];

<?php
$itemarray = array();
$itemlist  = $database->execute("SELECT t.productid productid FROM tpi_documentdetails t INNER JOIN product p ON p.ID = t.ProductID WHERE t.tpi_document_ID =".$_GET['ID']);

if($itemlist->num_rows){
	while($rlist = $itemlist->fetch_object()){
		$itemarray[] = $rlist->productid;
	}
}


?>

	itemlistarray = <?php echo json_encode($itemarray); ?>;


$(function(){
	$("[name=txtStartDate]" ).datepicker({  
					//maxDate: new Date(),
					changeMonth :   true,
					changeYear  :   true					
	});
	
	$("[name=txtEndDate]").datepicker({ 
			//minDate: 0,
			changeMonth :   true,
			changeYear  :   true					
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


function printreport(){
    
    //var param = "?"+$('[name=formCreateSpecialPromo]').serialize();            
	var param = "ID=<?php echo $_GET['ID'];?>&code=<?php echo $Code ;?>&desc=<?php echo $Description;?>&start=<?php echo $Start;?>&end=<?php echo $Start;?>&end=<?php echo $End;?>&charge=<?php echo $chargedtoname;?>";
	
    var objWin;
    popuppage = "view_print.php?" + param;
	//popuppage = "pages/datamanagement/tpi_document/view_print.php?" + param;
    if (!objWin){			
        objWin = NewWindow(popuppage,'printce','1000','500','yes');
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

function confirmDelete(){
	var message = "Do you want to delete this CE Codes?";
	var DeleteFunction = function(){
		return function(){
		
			$.ajax({
				url		:	'../../../pages/datamanagement/tpi_document/ajax/process.php',
				type	:	'post',
				dataType:	'json',
				data	:	{DeleteSpecialPromo : 1, PromoID : '<?=$_GET['ID'];?>'},
				success	:	function(data){
					$( "#dialog-message" ).dialog("close");
					if(data.Success == 1){
						window.onunload = function(){
							window.opener.location.reload();
						};
						window.close();
					}else{
						popinmessage(data.ErrorMessage);
					}
				},
				beforeSend:	function(){
					popinmessage("Deleting CE Codes... Please wait...");
				}
			});
		}
	}($('[name=spid]').val());
	
	ConfirmationPopin(message, DeleteFunction);
	return false;
}

function ConfirmationPopin(message, customfunction){
	$( "#dialog-message p" ).html(message);
	$( "#dialog-message" ).dialog({
		autoOpen: false,
		modal: true,
		position: 'center',
		height: 'auto',
		width: 'auto',
		resizable: false,
		title: 'DSS Message',
		buttons:{
			"Yes"   : 	customfunction,
			"No"	:	function(){
				$(this).dialog("close");
			}
		}
	});
	$("#dialog-message").dialog( "open" );
}




function addRow(){

    var index = 0;

    //if(e.keyCode == 13 || e.keyCode == 9){

        //removing all onkeypress to all row input for quantity
        index = eval($('#dynamicTable tr:last td:nth-child(2) div').html());
        $('[name=txtEQty'+ index +']').removeAttr("onkeypress");

	/* 	alert(index); */
		/* return false; */
        index += 1;

        $.ajax({

            type    :   "post",
            data    :   {index  :   index},
            url     :   "../../../pages/datamanagement/tpi_document/ajax/process.php",
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

function deleteRow1(index,ProductID){
	
	
	$.ajax({

            type    :   "post",
            data    :   {rolon  :   ProductID},
            url     :   "../../../pages/datamanagement/tpi_document/ajax/process.php",
            success :   function(data){
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
					
					$(this).find('td:nth-child(3) input:nth-child(1)').attr({ //item code textbox
						'name'  :   'txtProdCode' + (i+1),
						'id'    :   'txtProdCode' + (i+1)
					});
					
					$(this).find('td:nth-child(3) input:nth-child(2)').attr({ //item code textbox
						'name'  :   'hProdID' + (i+1),
						'id'    :   'hProdID' + (i+1)
					});
					
					$(this).find('td:nth-child(3) input:nth-child(3)').attr({ //item code textbox
						'name'  :   'hUpProdID' + (i+1),
						'id'    :   'hUpProdID' + (i+1)
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

	});
		
    return false;
}



function deleteRow(index,postaction){

    var count = 0;
	var txnid = <?php echo $_GET['ID'] ?>;
    //check the rows if the index is equal to clicked button's index then remove the row
	var pageid=<?php echo $pageid; ?>;
	
    $('#dynamicTable tr').each(function(i){
		
        if(i == index){
			var prdid = ($('#hProdID'+index).val());
			var prdcode = ($('#txtProdCode'+index).val());
			var prddes = ($('#txtProdDesc'+index).val());
			var prdid_arr = itemlistarray_UI.indexOf(prdid);
			if (prdid_arr > -1) {  //check if dealer already in table
				itemlistarray_UI.splice(prdid_arr, 1);
			}
			
			if (postaction==1)
			{
		
		
			var r = confirm("Are you sure you want to PERMANENTLY remove   \""+prdcode+"-"+prddes+"\"   from this CE Code?");
			if (r == true)
				{
					$.ajax({

					type    :   "post",
					data    :   { remfrdbase : 'remfrdbase',txnno : txnid, prodid : prdid, prodcode: prdcode, pageid : pageid },
					url     :   "../../../pages/datamanagement/tpi_document/ajax/process.php",
					success :   function(data)
						{
							
						}

					});	
				}
			else
				{
					return false;
				}		
		
			} 
	
		   	/* $(this).closest('tr').remove();	 */
           $(this).remove();
		$('[name=txtProdCode]').focus();	
        }
    });

    //updating the rows numbering
    $('#dynamicTable .trlist').each(function(i){
		
        $(this).find('td:first input').attr('name', 'btnRemove' + (i+1)); //remove button
        $(this).find('td:nth-child(2) div').html(i+1); //line no.
		
        $(this).find('td:nth-child(3) input:nth-child(1)').attr({ //item code textbox
            'name'  :   'txtProdCode' + (i+1),
            'id'    :   'txtProdCode' + (i+1)
        });
		
		$(this).find('td:nth-child(3) input:nth-child(2)').attr({ //item code textbox
            'name'  :   'hProdID' + (i+1),
            'id'    :   'hProdID' + (i+1)
        });
		
		$(this).find('td:nth-child(3) input:nth-child(3)').attr({ //item code textbox
            'name'  :   'hUpProdID' + (i+1),
            'id'    :   'hUpProdID' + (i+1)
        });
		

        $(this).find('td:nth-child(4) input').attr({ //item description
            'name'  :   'txtProdDesc' + (i+1),
            'id'    :   'txtProdDesc' + (i+1)
        });
       
        $(this).find('td:nth-child(5) select').attr({
            'name'  :   'txtbPmg' + (i+1),
            'id'    :   'txtbPmg' + (i+1)
        });	   
		
		$(this).find('td:nth-child(5)input:nth-child(1)').attr({
            'name'  :   'hProdcode' + (i+1),
            'id'    :   'hProdcode' + (i+1)
        });	   
	   
	   
	   
	   
	   
/*         $(this).find('td:nth-child(9) select').attr({
            'name'  :   'txtbPmg' + (i+1),
            'id'    :   'txtbPmg' + (i+1)
        }); */

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
            data    :   {checkCode : $('[name=txtCode]').val()},
            url     :   "../../../pages/datamanagement/tpi_document/ajax/process.php",
            success :   function(data){
                if(data == 0){
                    $('[name=txtDescription]').removeAttr('disabled');
                    $('[name=promotype]').removeAttr('disabled');
                    $('[name=txtStartDate]').removeAttr('disabled');
                    $('[name=txtEndDate]').removeAttr('disabled');
                    $('[name=txtProdCode1]').removeAttr('disabled');
                    $('[name=txtProdDesc1]').removeAttr('disabled');
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
	
	
	var pageid=<?php echo $pageid; ?>;
	
	
	/* alert(pageid); */

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
	counter--;
	
    $('[name=counter]').val(counter);

    if(err > 0){
        popinmessage("Please select Item Code.");
        return false;
    }

    $.ajax({
        type        :   "post",
		dataType	:	'json',
        data        :   $('[name=formCreateSpecialPromo]').serialize()+"&update=update&pageid="+pageid,
        url         :   "../../../pages/datamanagement/tpi_document/ajax/process.php",
        success     :   function(data){
									  /*  alert(data['message'])	 */
									   var msg='';
									   if(data['message']=='success')
									   {
										   msg="CE and SKU Codes were successfully Updated.";
									   }
									   else
									   {
										   msg=data['ErrorMessage'];
									   }
										   
										$( "#dialog-message p" ).html(msg);
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
														window.onunload = function(){
														window.opener.location.reload();
													};
													window.close();
												}
											}
										});
			
        },
        beforeSend  :   function(){
            $( "#dialog-message p" ).html("Update CE Codes Promo...");
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




function confirmCancel(){
    confirmationpopinwithredirection("Do you want to cancel?", "index.php?pageid=302");
    return false;
}

function CheckPromo(e){
    if(e.which == 13 || e.which == 9){
        //check if the code inserted is already exist.
        $.ajax({
            type    :   "post",
			dataType:	"json",
            data    :   {checkCode : $('[name=txtCode]').val()},
            url     :   "../../../pages/datamanagement/tpi_document/ajax/process.php",
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

function selectItemCode(item){

	var index = eval($('#dynamicTable tr:last td:nth-child(2) div').html());
	var prdexistindb = 0;
	if (isNaN(index))
	{
		index=0;
	}
	var cnt = $(".trlist").length;
	
	var xarray = new Array()
	for(var i = 1; cnt >= i; i++){
		if(index != i){
			xarray.push($("[name=txtProdCode"+i+"]").val());
		}
	}
	
	index += 1;
	
    $('#'+ item).autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                url     :   "../../../includes/jxCreateSpecialPromo.php",
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
			
       $('[name=txtEQty'+ index +']').removeAttr("onkeypress");

		var iid      = ui.item.ID;
        var iname    = ui.item.Name;
		var icode    = ui.item.Code;
		var ipmgid   = ui.item.PMGID;
		var ipmgcode = ui.item.PMGCode;
		var ce_txnid = <?php echo $_GET['ID']; ?>;

		var prdexist = itemlistarray.indexOf(iid);
		if (prdexist > -1) {  
			prdexistindb = 1;
		}
		
		var prdexist_UI = itemlistarray_UI.indexOf(iid);
		if (prdexist_UI > -1) {  
			alert('Item Code is already in the list.');
    		 $('[name=txtProdCodex').val('');
			 $('[name=txtProdCodex').focus();			
			return false;
		}
		
		$.ajax({

            type    :   "post",
            data    :   { addr : index, pid : iid, pcode : icode, pname : iname, ppmgid : ipmgid, ppmgcode : ipmgcode, doesexist : prdexistindb, txnid:ce_txnid },
            url     :   "../../../pages/datamanagement/tpi_document/ajax/process.php",
            success :   function(data){
                $('#dynamicTable').append(data);
				itemlistarray_UI.push(iid);
				 $('[name=txtProdCodex').val('');
				 $('[name=txtProdCodex').focus();

            }

        });			
			
					
			
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
			/* addRow(); */
        }
    });
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
<form name="formCreateSpecialPromo" method="post" action="" style="min-height: 610px;">
    
    <input name="counter" value="<?=$details->num_rows;?>" type="hidden">
    <input type = 'hidden' value = '<?=$_GET['ID'];?>' name = 'CEID'>
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
                        <td width="70%">&nbsp;<a class="txtgreenbold13">Create CE Codes Maintenance</a></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="tabmin"></td> 
            <td class="tabmin2"><div align="left" class="padl5 txtredbold"><b>CE Code HEADER</b></div></td>
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
                                        <input type="text" class="txtfieldg" value="<?=$Code;?>" name="txtCode" onkeydown="return CheckPromo(event);" id="txtCode" autocomplete="off">
                                    </td>
                                </tr>
								<tr>
									<td height="20" align="left"><strong>Description</strong></td>
									<td align="left">:</td>
									<td height="20">
                                        <input type="text" class="txtfieldg" value="<?=$Description;?>" name="txtDescription" id="txtDescription" autocomplete="off">
                                    </td>
                                </tr>
								<tr>
                                    <td height="20" align="left"><strong>Start Date</strong></td>
									<td align="left">:</td>
                                    <td width="20%" height="20">
                                        <input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" size="20" readonly="yes"  value="<?=$Start;?>">
                                        <i>(e.g. MM/DD/YYYY)</i>
                                    </td>
								</tr>
								<tr>
                                    <td height="20" align="left"><strong>End Date</strong></td>
									<td align="left">:</td>
                                    <td width="20%" height="20">
                                        <input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" size="20" readonly="yes" value="<?php echo $End; ?>" >
                                        <i>(e.g. MM/DD/YYYY)</i>
                                    </td>
								</tr>	

										
								<tr>
									<td align="left" height="20"><strong>Charged To</strong></td>
									<td align="left">:</td>
									<td>
										<select name="chargeto" class="txtfield" id="chargeto">
											<?php 
												$codemaster = $database->execute("SELECT * FROM codemaster WHERE mnemoniccode='CEDEPT' AND enabled=1 ORDER BY sequence ");
												while($codem = $codemaster->fetch_object()){
													if($chargedtoID == $codem->ID)
													{
														$selected="selected";
													}	
													else
													{
														$selected="";
													}
														
													echo "<option value='".$codem->Value."' $selected>".$codem->CodeValue.' - '.$codem->Description."</option>";
												}
											?>
										</select>
									</td>
								</tr>

								<?php
								if ($isexempted==0)
								{	
								?>
								<tr>
									<td align="left" height="20"><strong>Product Code</strong></td>
									<td align="left">:</td>								

										<td class="txtfieldg">
											
												<input name="txtProdCodex" type="text" class="txtfieldg" id="txtProdCodex" style="width: 100px;" value="" onkeypress="return selectItemCode(this.id);"  />
												<input name="hProdID<?=$xcount;?>" type="hidden" id="hProdID<?=$xcount;?>" value="" />
												
											<!--	<input name="txtProdCode<?=$xcount;?>" type="text" class="txtfieldg" id="txtProdCode<?=$xcount;?>" style="width: 100px;" value="" onkeypress="return selectItemCode(this.id);"  />
												<input name="hProdID<?=$xcount;?>" type="hidden" id="hProdID<?=$xcount;?>" value="" />												
											-->	
												
											
										</td>
								</tr>	
								<?php
								}
								?>
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
					<td class="tabmin2"><div align="left" class="padl5 txtredbold"><b>CE Code DETAILS</b></div></td>
					<td class="tabmin3">&nbsp;</td>
							</tr>
				</table>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">                    
							<tr>
								<td>
									<div style="max-height:220px; overflow:auto;">
										<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicTable" class="bgFFFFFF">
											<tr align="center" class="trheader" style="border-bottom:1px solid #FFA3E0; background:#FFDEF0;">
												<td width="6%" class="borderBR"><strong>Action</strong></td>
												<td width="6%" class="borderBR"><strong>Line No.</strong></td>
												<td width="10%" class="borderBR"><strong>Item Code</strong></td>
												<td width="22%" class="borderBR"><strong>Item Description</strong></td>			
												<td width="13%" class="borderBR"><strong>PMG</strong></td>
											</tr>
											<?php
/* 												$count=0;
												while($rr = $details->fetch_object()){
												$count++; */
											?>
											<!--
											<tr align="center" class="trlist">
												
												<td class="borderBR"><div align="center">
												</div><input name="btnRemove<?=$count;?>" type="submit" class="btn" value="Remove" onclick = "return deleteRow1(this.parentNode.parentNode.rowIndex,<?=$rr->ID;?>)"></td>
												<td class="borderBR"><div align="center"><?=$count;?></div></td>
												<td class="borderBR">
													<div align="center">
														<input name="txtProdCode<?=$count;?>" type="text" class="txtfieldg" id="txtProdCode<?=$count;?>" style="width: 70px;" value="<?=$rr->ProductCode;?>" onkeypress="return selectItemCode(this.id);"  />
														<input name="hProdID<?=$count;?>" type="hidden" id="hProdID<?=$count;?>" value="<?=$rr->ProductID;?>" />
														<input name="hUpProdID<?=$count;?>" type="hidden" id="hUpProdID<?=$count;?>" value="" />
														
													</div>
												</td>
												<td class="borderBR">
													<div align="center">
														<input name="txtProdDesc<?=$count;?>" type="text"  value = '<?=$rr->ProductName;?>' class="txtfield" id="txtProdDesc<?=$count;?>" style="width: 95%;" readonly="yes" />
													</div>
												</td>			
														
												<td class="borderBR">
													<div align="center">
														<select name="txtbPmg<?=$count;?>" id = "txtbPmg<?=$count;?>"  class = "txtfield" style="width: 80%">
															
															<option <?php echo ($rr->PMGID == 1)? "selected":""; ?> value="1">CFT</option>
															<option <?php echo ($rr->PMGID == 2)? "selected":""; ?> value="2">NCFT</option>
															<option <?php echo ($rr->PMGID == 3)? "selected":""; ?> value="3">CPI</option>
														</select>
													</div>
												</td>			
											</tr> -->
											<?php 
											/* }; 
											
											$xcount  = $count += 1; */
											
											?>
											<!--
											<tr align="center" class="trlist">
												<td class="borderBR">
													<input name="btnRemove<?=$xcount;?>" type="submit" class="btn" value="Remove" onclick = "deleteRow(this.parentNode.parentNode.rowIndex)">
													<input name="btnRemovex<?=$xcount;?>" type="hidden" value="Remove">
												</td>
												<td class="borderBR"><div align="center"><?=$xcount;?></div></td>
												<td class="borderBR">
													<div align="center">
														<input name="txtProdCode<?=$xcount;?>" type="text" class="txtfieldg" id="txtProdCode<?=$xcount;?>" style="width: 70px;" value="" onkeypress="return selectItemCode(this.id);"  />
														<input name="hProdID<?=$xcount;?>" type="hidden" id="hProdID<?=$xcount;?>" value="" />
													</div>
												</td>
												<td class="borderBR">
													<div align="center">
														<input name="txtProdDesc<?=$xcount;?>" type="text"  class="txtfield" id="txtProdDesc<?=$xcount;?>" style="width: 95%;" readonly="yes" />
													</div>
												</td>			
														
												<td class="borderBR">
													<div align="center">
														<select name="txtbPmg<?=$xcount;?>" id = "txtbPmg<?=$xcount;?>"  class = "txtfield" style="width: 80%">
															<option value="1">CFT</option>
															<option value="2">NCFT</option>
															<option value="3">CPI</option>
														</select>
													</div>
												</td>			
											</tr>
											-->
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
			
                <input name='btnSave' type='button' class='btn' value='Update' id = 'savebtn'   onclick='return confirmSave();'>
				<?php
				if ($isexempted==0)
				{	
				?>				
                <input name='btnDelete' type='button' class='btn' value='Delete' onclick='return confirmDelete();'>
				<?php
				}
				?>					
				<input name='btnprint' type='button' class='btn' value='Print All' onclick='return printreport()'>
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