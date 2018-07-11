<?php include IN_PATH.'scPrintInvCountDetails.php'; ?>
<link rel="stylesheet" type="text/css" media="all" href="css/jquery-ui-1.8.5.custom.css" title="win2k-cold-1" />
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$('#ItemCode').autocomplete({
		source:'pages/inventory/inventory_call_ajax/ajax_printcounttagsdetails.php?tid=<?php echo $_GET["tid"]; ?>',
			select: function( event, ui ) {
				$( "#ItemCode").val(ui.item.pCode);
				return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		 return $( "<li style = 'list-style-type:circle;'></li>" ).data( "item.autocomplete", item ).append( "<a><strong>" + item.pCode + "</strong> - " + item.pName + "</a>" )
		 .appendTo( ul );
	}
});
function checkAll(bin) 
{
	var elms = document.frmPrintCountTagDetails.elements;

	for (var i = 0; i < elms.length; i++)
	{
		if (elms[i].name == 'chkSelect[]') 
	  	{
			elms[i].checked = bin;		  
	  	}			
	}
}

function UnCheck()
{
    var chkAll = document.frmPrintCountTagDetails.chkAll;
    chkAll.checked = false;
}

function checker()
{
	var ml = document.frmPrintCountTagDetails;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkSelect[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}

function validatePrint()
{ 
	if (!checker())
	{
		alert('Please select product(s) to be printed.');
		return false;		
	}
	else
	{
		return true;
	} 
}

function search_query(){
var dynamic_table = "";
var ctr = 1;
var alt = "";
	if($("#ItemCode").val() == ""){
		return true;
	}else{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'request':'search_item','ProductCode': $("#ItemCode").val(), 'tid':'<?php echo $_GET["tid"]; ?>'},
			url: 'pages/inventory/inventory_call_ajax/ajax_printcounttagsdetails.php',
			success: function(resp){
				if(resp['request'].response == "success"){
					
					for(var i = 0; resp['fetch_data'].length > i; i++){
						(ctr % 2) ? alt = '' : alt = 'bgEFF0EB';
						dynamic_table += '<tr align="center" class="'+alt+'">'
						dynamic_table += '<input type="hidden" name="hProdID'+ctr+'" id="hProdID'+ctr+'" value="'+resp["fetch_data"][i].ProductID+'">';
						dynamic_table += '<td width="5%" height="20" class="borderBR" align="center"><input type="checkbox" name="chkSelect[]" id="chkSelect" onclick="UnCheck();" value="'+ctr+'"></td>';
						dynamic_table += '<td width="10%" height="20" class="borderBR" align="center">'+resp["fetch_data"][i].CountTag+'</td>';
						dynamic_table += '<td width="30%" height="20" class="borderBR padl5" align="left">'+resp["fetch_data"][i].pCode+'</td>';
						dynamic_table += '<td width="30%" height="20" class="borderBR padl5" align="left">'+resp["fetch_data"][i].pName+'</td>';
						dynamic_table += '<td width="25%" height="20" class="borderBR padl5" align="left">'+resp["fetch_data"][i].Location+'</td>';
						dynamic_table += '</tr>';
						ctr++;
					}
					$("#dynamic_table").html(dynamic_table);
				}else{
					alert('failed');
				}
			}
		});
	}
	return false;
}
</script>

<form name="frmPrintCountTagDetails" method="post" action="index.php?pageid=134.1&tid=<?php echo $_GET['tid'];?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    	<tr>
		    		<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
		    	</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
    		<td class="txtgreenbold13">Print Count Tags</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
        	<td>
			  	<?php 
			  		if (isset($_GET['msg']))
				  	{
				  		$message = strtolower($_GET['msg']);
					  	$success = strpos("$message","success"); 
					  	echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'><b>".$_GET['msg']."</b></div><br>";
				  	}
				  	if (isset($_GET['errmsg']))
				  	{ 
					  	echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'><b>".$_GET['errmsg']."</b></div><br>";
				  	} 
			  	?> 
		  	</td>
	  	</tr>
	  	</table>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
      		<td class="tabmin">&nbsp;</td>
      		<td class="tabmin2 txtredbold">General Information</td>
      		<td class="tabmin3">&nbsp;</td>
  		</tr>
  		</table>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
    			<tr>
      				<td colspan="2">&nbsp;</td>
    			</tr>
    			<tr>
      				<td width="50%" valign="top">
      					<table width="100%"  border="0" cellspacing="1" cellpadding="0">
        				<tr>
          					<td width="25%" height="20" class="txt10">Worksheet No. :</td>
          					<td width="75%" height="20"><input name="txtRefNo" type="text" readonly="readonly" class="txtfield" id="txtRefNo" size="20" maxlength="20" value="<?php  echo $transno;?>" /></td>
        				</tr>
        				<tr>
          					<td height="20" class="txt10">Document No. :</td>
          					<td height="20"><input name="txtDocNo" type="text"  readonly="readonly" class="txtfield" id="txtDocNo" size="20" maxlength="20" value="<?php  echo $docno;?>" /></td>
    					</tr>
        				<tr>
          					<td height="20" class="txt10">Warehouse :</td>
          					<td height="20"><input name="txtWarehouse" type="text"  readonly="readonly" class="txtfield" id="txtWarehouse" size="20" maxlength="20" value="<?php  echo $warehouse;?>" /></td>
        				</tr>
      					</table>
  					</td>
      				<td valign="top">
      					<table width="100%"  border="0" cellspacing="1" cellpadding="0">
      					<tr>
          					<td height="20" class="txt10">Created By :</td>
          					<td height="20"><input name="txtCreatedBy" type="text"  readonly="readonly" class="txtfield" id="txtCreatedBy" size="20" maxlength="20" value="<?php  echo $createdby;?>" /></td>
    					</tr>
    					<tr>
          					<td height="20" class="txt10">Date Created :</td>
          					<td height="20"><input name="startDate" type="text" class="txtfield" id="startDate" size="20" readonly="yes" value="<?php echo $transdate; ?>" />
      					</tr>
      					<tr>
          					<td height="20" class="txt10">Date Modified :</td>
          					<td height="20"><input name="startDate" type="text" class="txtfield" id="startDate" size="20" readonly="yes" value="<?php echo $lastmodified; ?>" />
      					</tr>
        				<tr>
          					<td width="20%" height="20" valign="top" class="txt10">Remarks :</td>
          					<td width="80%" height="20"><textarea name="txtRemarks" cols="40" rows="2" class="txtfieldnh" id="txtRemarks" readonly="yes"><?php  echo $remarks; ?></textarea></td>
        				</tr>
      					</table>
  					</td>
				</tr>
				<tr>
      				<td colspan="2">&nbsp;</td>
    			</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
      		<td class="tabmin">&nbsp;</td>
      		<td class="tabmin2 txtredbold">Search Item Code</td>
      		<td class="tabmin3">&nbsp;</td>
  		</tr>
  		</table>
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
    			<tr>
      				<td colspan="2">&nbsp;</td>
    			</tr>
    			<tr>
      				<td width="50%" valign="top">
      					<table width="29%"  border="0" cellspacing="1" cellpadding="0">
          					<td height="20" class="txt10">Item Code :</td>
          					<td height="20">
								<input name="ItemCode" type="text"   class="txtfield"  id="ItemCode" size="20" maxlength="20"  />
								<input type = "submit" value = "Search" class = "btn "onclick = "return search_query();">
							</td>
        				</tr>
      					</table>
  					</td>		
			</tr>
				<tr>
      				<td colspan="2">&nbsp;</td>
    			</tr>
				</table>
			</td>
		</tr>
		</table>
  		<br>
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  		<tr>
  			<td class="tabmin">&nbsp;</td>
      		<td class="tabmin2 txtredbold">Worksheet Details</td>
      		<td class="tabmin3">&nbsp;</td>
    	</tr>
  		</table>
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
    			<tr>
    				<td>
    					<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="tab txtdarkgreenbold10">
        				<tr align="center">
        					<td width="5%" height="20" class="bdiv_r" align="center"><input type="checkbox" id="chkAll" name="chkAll" onclick="checkAll(this.checked);"></td>
              				<td width="10%" height="20" class="bdiv_r" align="center">Count Tag</td>
              				<td width="30%" height="20" class="bdiv_r padl5" align="left">Item Code</td>
              				<td width="30%" height="20" class="bdiv_r padl5" align="left">Item Description</td>
              				<td width="25%" height="20" class="padl5" align="left">Location</td>
      					</tr>
        				</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_300">
				<table width="100%"  border="0" cellpadding="0" cellspacing="0" id = "dynamic_table">
				<?php
				if ($rs_productsforprinting->num_rows)
				{
					$cnt = 0;
					while ($row = $rs_productsforprinting->fetch_object())
					{
						$cnt ++;
						($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';	
				?>
				<tr align="center" class="<?php echo $alt; ?>">
					<input type="hidden" name="hProdID<?php echo $cnt; ?>" id="hProdID<?php echo $cnt; ?>" value="<?php echo $row->ProductID; ?>">
					<td width="5%" height="20" class="borderBR" align="center"><input type="checkbox" name="chkSelect[]" id="chkSelect" onclick="UnCheck();" value="<?php echo $cnt; ?>"></td>
      				<td width="10%" height="20" class="borderBR" align="center"><?php echo $row->CountTag; ?></td>
      				<td width="30%" height="20" class="borderBR padl5" align="left"><?php echo $row->pCode; ?></td>
      				<td width="30%" height="20" class="borderBR padl5" align="left"><?php echo $row->pName; ?></td>
      				<td width="25%" height="20" class="borderBR padl5" align="left"><?php echo $row->Location; ?></td>
				</tr>
				<?php
					}
					$rs_productsforprinting->close();
				}
				?>
				</table>
				</div>
			</td>
		</tr>
		</table>
		<br>
		<table width="95%" align="center" border="0" cellpadding="0" cellspacing="">
		<tr>
			<td align="center">
				<input name="btnPrint" type="submit" class="btn" value="Print" onclick="return validatePrint();"/>
				<input name="btnCancel" type="submit" class="btn" value="Cancel"/>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>	
<br />
<br />