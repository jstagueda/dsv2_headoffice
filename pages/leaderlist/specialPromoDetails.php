<link rel="stylesheet" type="text/css" href="../../css/ems.css"/>
<link rel="stylesheet" type="text/css" href="../../css/jquery-ui-1.8.5.custom.css"/>
<script language="javascript" src="../../js/jquery-1.9.1.min.js"></script>
<script language="javascript" src="../../js/jquery-ui-1.10.0.custom.min.js"></script>
<script language="javascript" src="../../js/popinbox.js"  type="text/javascript"></script>

<?php
include "../../initialize.php";
$specialpromoquery = $database->execute("SELECT Code, Description, PromoType,
                                        DATE_FORMAT(StartDate, '%m/%d/%Y') StartDate,
                                        DATE_FORMAT(EndDate, '%m/%d/%Y') EndDate,
                                        BrochurePage, NonGSU, DirectGSU, InDirectGSU, IsPlusPlan
                                        FROM specialpromo WHERE ID = ".$_GET['spid']);
$specialpromo = $specialpromoquery->fetch_object();
$promocode = "";
$promodesc = "";
$promotype = 0;
$startdate = "";
$enddate = "";
$nonGSU = 0;
$directGSU = 0;
$indirectGSU = 0;
$brochure1 = 0;
$brochure2 = 0;
$plusplan = 0;

if($specialpromoquery->num_rows){
    $promocode = $specialpromo->Code;
    $promodesc = $specialpromo->Description;
    $promotype = $specialpromo->PromoType;
    $startdate = $specialpromo->StartDate;
    $enddate = $specialpromo->EndDate;
    $brochure = explode('-', $specialpromo->BrochurePage);
    $brochure1 = $brochure[0];
    $brochure2 = $brochure[1];
    $nonGSU = $specialpromo->NonGSU;
    $directGSU = $specialpromo->DirectGSU;
    $indirectGSU = $specialpromo->InDirectGSU;
    $plusplan = $specialpromo->IsPlusPlan;
}

$promodetailsquery = $database->execute("SELECT p.Code, p.Name, spe.*
                                FROM specialpromobuyinandentitlement spe
                                INNER JOIN product p ON p.ID = spe.ProductID
                                INNER JOIN specialpromo sp ON sp.ID = spe.SpecialPromoID
                                INNER JOIN productpricing pp ON pp.ProductID = p.ID
                                WHERE spe.SpecialPromoID = ".$_GET['spid']);
?>

<script>

function confirmDelete(){
	var message = "Do you want to delete this promo?";
	var DeleteFunction = function(PromoID){
		return function(){
		
			$.ajax({
				url		:	'../../includes/jxCreateSpecialPromo.php',
				type	:	'post',
				dataType:	'json',
				data	:	{DeleteSpecialPromo : 1, PromoID : PromoID},
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
					popinmessage("Deleting Special Promo... Please wait...");
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

function RemoveEntitlement(field, EntitlementID, PromoID){
	
	var message = "Do you want to remove this item?";

	var removeentitlementfunction = function(field, EntitlementID, PromoID){
		return function(){
			$.ajax({
				type	:	'post',
				url		:	'../../includes/jxCreateSpecialPromo.php',
				dataType:	'json',
				data	:	{RemoveEntitlementItem : 1, EntitlementID : EntitlementID, PromoID : PromoID},
				success	:	function(data){
					$( "#dialog-message" ).dialog("close");
					if(data.Success == 1){
						$('#dynamicTable').find('tr').each(function(index){
							$(this).find('td:nth-child(2)').html((index+1));
						});
						$(field).closest('tr').remove();
					}else{
						popinmessage(data.ErrorMessage);
					}
				},
				beforeSend:	function(){
					popinmessage("Removing Entitlement... Please wait...");
				}
			});
		}
	}(field, EntitlementID, PromoID);
	
	ConfirmationPopin(message, removeentitlementfunction);
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
        data        :   $('[name=formSpecialPromoDetails]').serialize()+"&action=update",
        url         :   "../../includes/jxCreateSpecialPromo.php",
        success     :   function(data){
            $( "#dialog-message p" ).html("Special Promo successfully updated.");
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

function RemoveInvalidChars(me){
    if(me.value != ""){
        var checker = /^[0-9]+$/;
        if(!checker.test(me.value)){
            me.value = 0;
        }
    }
    return false;
}

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

});

function checkValue(name){
    $('[name='+name+']').blur(function(){
        if($('[name='+name+']').val() == ""){
            $('[name='+name+']').val(0);
        }
    });
}

function selectItemCode(item){

    var index = item.replace("txtProdCode", "");

    $('#'+ item).autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                url     :   "../../includes/jxCreateSpecialPromo.php",
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
            if(ui.item.Name == 1){
                htmltext = '<option value="1">CFT</option>';
                htmltext += '<option value="3">CPI</option>';
            }else if(ui.item.Name == 2){
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
    $( "#dialog-message p" ).html('Do you want to cancel?');
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSS Message',
        buttons:{
            "Yes"   : function(){
                $(this).dialog("close");
                window.close();
            },
            "No"    : function(){
                $(this).dialog("close");
            }
        }
    });
    $( "#dialog-message" ).dialog( "open" );
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

</style>

<body>
<form name="formSpecialPromoDetails" method="post" action="">
    <input type="hidden" name="spid" value="<?=$_GET['spid']?>">
    <br>
    <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td>
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                        <td width="70%">&nbsp;<a class="txtgreenbold13">Special Promo Details</a></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="tabmin"></td>
            <td class="tabmin2"><div align="left" class="padl5 txtredbold"><b>Promo Header</b></div></td>
            <td class="tabmin3">&nbsp;</td>
        </tr>
    </table>
    <!-- FORM HEADER -->
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        <tr>
            <td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
			<td>
                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
                                    <td width="20%">&nbsp;</td>
                                    <td width="90%" align="right">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" align="right">
                                        <div align="right" class="txtpallete padl5"><strong>Promo Code :</strong></div>
                                    </td>
				    <td height="20">&nbsp;&nbsp;
                                        <input type="text" class="txtfieldg" value="<?=$promocode?>" name="txtCode" id="txtCode" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
				    <td height="20" align="right">
                                        <div align="right" class="txtpallete padl5"><strong>Promo Description :</strong></div>
                                    </td>
				    <td width="20%" height="20">&nbsp;&nbsp;
                                        <input name="txtDescription" type="text" class="txtfieldg" id="txtDescription" value="<?=$promodesc?>" size="30" style="width: 362px;" maxlength="60">
                                    </td>
                                </tr>
                                <tr>
				    <td height="20" align="right">
                                        <div align="right" class="txtpallete padl5"><strong>Promo Type :</strong></div>
                                    </td>
				    <td height="20">&nbsp;&nbsp;
                                        <select name="promotype" class="txtfieldg">
                                            <?php
                                                $promotypearray = array("SELECT", "Coupon", "New Dealer Incentives");
                                                foreach($promotypearray as $key => $val){
                                                    $sel = ($promotype == $key)?"selected='selected'":"";
                                            ?>
                                            <option <?=$sel?> value="<?=$key?>"><?=$val?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                </tr>
				<tr>
                                    <td height="20" align="right">
                                        <div align="right" class="txtpallete padl5"><strong>Start Date: </strong></div>
                                    </td>
                                    <td width="20%" height="20">&nbsp;&nbsp;
                                        <input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" size="20" readonly="yes" value="<?=$startdate?>">
                                        <i>(e.g. MM/DD/YYYY)</i>
                                    </td>
				</tr>
				<tr>
                                    <td height="20" align="right">
                                        <div align="right" class="txtpallete padl5"><strong>End Date: </strong></div>
                                    </td>
                                    <td width="20%" height="20">&nbsp;&nbsp;
                                        <input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" size="20" readonly="yes" value="<?=$enddate?>">
                                        <i>(e.g. MM/DD/YYYY)</i>
                                    </td>
				</tr>
				<tr>
                                    <td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>Brochure Page: </strong></div></td>
                                    <td width="10%" height="20">&nbsp;&nbsp;
                                        <input name="bpage" type="text" onkeyup="return RemoveInvalidChars(this);" value = "<?=$brochure1?>" class="txtfieldg" id="bpage" size="10" value="" style = "width: 5%;">&nbsp; - &nbsp;
                                        <input name="epage" type="text" onkeyup="return RemoveInvalidChars(this);" value = "<?=$brochure2?>" class="txtfieldg" id="epage" size="10" value="" style = "width: 5%;">
                                    </td>
				</tr>
				<tr>
                                    <td colspan="2" height="20">&nbsp;</td>
				</tr>
                            </table>
			</td>
			<td width="50%" valign="top">
                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
                                    <td width="15%">&nbsp;</td>
                                    <td width="85%">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" valign="top" colspan="2">
                                        <strong align="right" class="txtpallete">Max Availment :</strong>
                                        <br />
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                                            <tr>
                                                <td width='15%' align='right' height='20'><strong  class='txtpallete'>NonGSU :</strong></div></td>
                                                <td width='75%' height='20'>&nbsp;<input type='text' id='MaxAvail' value="<?=$nonGSU?>" onkeyup='return RemoveInvalidChars(this);'  class='txtfieldg' name='txtMaxAvail1'></td>
                                            </tr><tr>
                                                <td width='15%' align='right' height='20'><strong  class='txtpallete'>Direct GSU :</strong></div></td>
                                                <td width='75%' height='20'>&nbsp;<input type='text' id='MaxAvail' value="<?=$directGSU?>" onkeyup='return RemoveInvalidChars(this);'  class='txtfieldg' name='txtMaxAvail2'></td>
                                            </tr><tr>
                                                <td width='15%' align='right' height='20'><strong  class='txtpallete'>Indirect GSU :</strong></div></td>
                                                <td width='75%' height='20'>&nbsp;<input type='text' id='MaxAvail' value="<?=$indirectGSU?>" onkeyup='return RemoveInvalidChars(this);'  class='txtfieldg' name='txtMaxAvail3'></td>
                                            </tr>                                            <tr>
                                                <td height="20">
                                                    <div align="right" class="txtpallete">
                                                        <label for="chkplus">
                                                            <strong>Is Plus Plan :</strong>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td height="20">
                                                    <?php $checked = ($plusplan == 1)?"checked='checked'":"";?>
                                                    <input type="checkbox" <?=$checked?> id="chkplus" name="chkPlusPlan" value="1">
                                                </td>
                                            </tr>
				    	</table>
				    </td>
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

    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <!-- DYNAMIC BUYIN REQUIREMENT TABLE START HERE -->
            <td width= "100%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
			<td class="tabmin"></td>
			<td class="tabmin2"><div align="left" class="padl5 txtredbold"><b>Buy-in Requirement and Entitlement</b></div></td>
			<td class="tabmin3">&nbsp;</td>
                    </tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
                    <tr>
			<td valign="top" class="bgF9F8F7">
                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			 	<tr>
                                    <td>
                                        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
                                            <tr align="center">
												<td width="3%"  height="20" class="txtpallete"></td>
                                                <td width="9%"  height="20" class="txtpallete"><div align="center">Line No.</div></td>
                                                <td width="13%" height="20" class="txtpallete"><div align="center">Item Code</div></td>
                                                <td width="22%" height="20" class="txtpallete"><div align="center">&nbsp;Item Description</div></td>
                                                <td width="7%" height="20" class="txtpallete"><div align="center">Buyin Criteria</div></td>
                                                <td width="12%" height="20" class="txtpallete"><div align="center">Buyin Minimum</div></td>
                                                <td width="10%" height="20" class="txtpallete"><div align="left">Entitlement Criteria</div></td>
                                                <td width="12%" height="20" class="txtpallete"><div align="left">Entitlement Minimum</div></td>
                                                <td width="13%" height="20" class="txtpallete"><div align="left">PMG</div></td>
                                            </tr>
                                        </table>
                                    </td>
				</tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
			<td>
                            <div class="scroll_150" style="height:220px;">
                                <input type="hidden" value="<?=$promodetailsquery->num_rows?>" name="counter">
                                <table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicTable" class="bgFFFFFF">
                                    <?php
                                        if($promodetailsquery->num_rows){
                                            $counter = 1;
                                            while($res = $promodetailsquery->fetch_object()){
                                    ?>
                                    <input type="hidden" name="spid<?=$res->ID?>" value="<?=$res->ID?>">
                                    <tr align="center">
										<td class="borderBR" height="20">
											<input type="button" name="btnDeleteRow" class="btn" value="Remove" onclick="return RemoveEntitlement(this, <?=$res->ID?>, <?=$_GET['spid']?>);">
										</td>
                                        <td width="9%" height="20" class="borderBR"><div align="center"><?=$counter;?></div></td>
                                        <td width="13%" height="20" class="borderBR">
                                            <div align="left"  class="padl5">
                                                <input name="ID<?=$counter?>" value="<?=$res->ID?>" type="hidden" type="text" class="txtfieldg"/>
                                                <input name="txtProdCode<?=$counter?>" value="<?=$res->Code?>" type="text" class="txtfieldg" id="txtProdCode<?=$counter?>" style="width: 70px;" onkeypress="return selectItemCode(this.id);" />
                                                <input name="hProdID<?=$counter?>" type="hidden" id="hProdID<?=$counter?>" value="<?=$res->ProductID?>" />
                                            </div>
                                        </td>
                                        <td width="22%" height="20" class="borderBR">
                                            <div align="left" class="padl5">
                                                <input name="txtProdDesc<?=$counter?>" value="<?=$res->Name?>" type="text" class="txtfield" id="txtProdDesc<?=$counter?>" style="width: 95%;" readonly="yes" />
                                            </div>
                                        </td>
                                        <td width="7%" height="20" class="borderBR">
                                            <div align="center">
                                                <select name="cboCriteria<?=$counter?>" class="txtfield" id="cboCriteria<?=$counter?>" style="width: 90%;" >
                                                    <?php
                                                        $criteriaarray = array("Quantity", "Amount");
                                                        foreach($criteriaarray as $key => $val){
                                                            $sel = (($key+1) == $res->BuyinCriteria)?"selected='selected'":"";
                                                    ?>
                                                    <option <?=$sel?> value="<?=($key+1)?>"><?=$val?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="12%" height="20" class="borderBR">
                                            <div align="center">
                                                <?php
                                                    $buyinMinimum = ($res->BuyinCriteria == 1)?$res->BuyinMinimumQty:$res->BuyinMinimumAmnt;
                                                ?>
                                                <input name="txtQty<?=$counter?>" type="text" class="txtfield" id="txtQty<?=$counter?>"  value="<?=$buyinMinimum?>" style="width: 90%; text-align:right" />
                                            </div>
                                        </td>
                                        <td width="10%" height="20" class="borderBR">
                                            <div align="center">
                                                <select name="cboECriteria<?=$counter?>" class="txtfield" id="cboECriteria<?=$counter?>" style="width: 90%;">
                                                    <option value="2" selected="selected">Price</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="12%" height="20" class="borderBR">
                                            <div align="center">
                                                <input name="txtEQty<?=$counter?>" type="text" class="txtfield" id="txtEQty<?=$counter?>" value="<?=$res->EntitlementAmnt?>" style="width: 90%; text-align:right" />
                                            </div>
                                        </td>
                                        <td width="13%" height="20" class="borderBR">
                                            <div align="center">
                                                <select name="txtbPmg<?=$counter?>" id = "txtbPmg<?=$counter?>" class = "txtfield" style="width: 80%">
                                                    <?php
                                                        /*if($res->PMGID == 3){
                                                            $where = "WHERE ID IN(3)";
                                                        }else if($res->PMGID == 1){
                                                            $where = "WHERE ID IN(1,3)";
                                                        }else if($res->PMGID == 2){
                                                            $where = "WHERE ID IN(2,3)";
                                                        }else{
                                                            $where = "WHERE ID IN(1,2,3)";
                                                        }*/
                                                        $where = "WHERE ID IN(1,2,3)";
                                                        $pmgquery = $database->execute("SELECT * FROM tpi_pmg $where");
                                                        while($pmg = $pmgquery->fetch_object()){
                                                            $sel = ($pmg->ID == $res->EntitlementPMGID)?"selected='selected'":"";
                                                            echo "<option ".$sel." value='".$pmg->ID."'>".$pmg->Code."</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                                $counter++;
                                            }
                                        }else{
                                    ?>
                                    <tr align="center">
                                        <td align="center" colspan="8">No result found.</td>
                                    </tr>
                                    <?php }?>
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
    <table width="90%" align="center"  border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <input name='btnUpdate' type='button' class='btn' value='Update' id = 'savebtn' onclick='return confirmSave();'>
				<input name='btnDelete' type='button' class='btn' value='Delete' id = 'deletebtn' onclick='return confirmDelete();'>
                <input name='btnCancel' type='button' class='btn' value='Back' onclick='return confirmCancel();'>
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