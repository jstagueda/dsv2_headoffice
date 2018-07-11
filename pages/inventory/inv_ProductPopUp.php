<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link type="text/css" href="../../css/jquery-ui-1.8.5.custom.css" rel="stylesheet"/>
<script type="text/javascript" src="../../js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.0.custom.min.js"></script>
<?php
	require_once "../../initialize.php";
	include IN_PATH.'scSearchProductPopUp.php';
?>
<script language="javascript" type="text/javascript">

$(document).ready(function(){
	//Branch AutoCompleter
	$( "#txtSearch" ).autocomplete({
	 source:'../../includes/ProductsearchInventory.php',
		select: function( event, ui ) {
			$( "#txtSearch" ).val( ui.item.ProductCode);

			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.ProductCode + " - " + item.ProductName+ "</strong></a>" )
			.appendTo( ul );
	};
});

function checkAll(bin) 
{
	var elms = document.frmProductList.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkInclude[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function checker()
{
	var ml = document.frmProductList;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkInclude[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}
function confirmAdd(linkid)
{
	var prodlist = new Array();
	var url_prod = document.frmProductList.hProdListID.value;
	
	if (!checker())
	{
		alert('Please select product(s) to be added.');
		return false;
	}
	else
	{
		var ml = document.frmProductList;
		var len = ml.elements.length;
		var index = 0;
		var rem = ml.hrem.value;
		var mtypeid = ml.hmtypeID.value; 
		var dno = ml.hdno.value;


		for (var i = 0; i < len; i++) 
		{
			var e = ml.elements[i];
		    if (e.name == "chkInclude[]" && e.checked == true) 
		    {

				prodlist[index] = e.value;
				index = index + 1;
		    }
		}

	
		if (url_prod != "")
		{
			prodlist = url_prod + ',' + prodlist;			
		}

		linkid = linkid + '&prodlist=' + prodlist + '&mtypeid=' + mtypeid + '&remarks=' + rem + '&dno=' + dno;
		if (confirm('Are you sure you want to add product(s)?') == false)
			return false;
		else
			opener.location.href = linkid;
			window.close();
	}		
}

function enableCheckAll()
{
	var ml = document.frmProductList;
	var search = ml.txtSearch;
		
	if (search.value == "" || search.value == "   ")
	{
		ml.chkAll.disabled = true;		
	}
	else
	{
		ml.chkAll.disabled = false;
	}	
}
</script>

<body onLoad="enableCheckAll();">
<form name="frmProductList" method="post" action="inv_ProductPopUp.php?prodlist=<?php echo $_GET['prodlist'];?>&wid=<?php echo $_GET['wid'];?>&dno=<?php echo $_GET['dno'];?>&mtypeid=<?php echo $_GET['mtypeid'];?>&remarks=<?php echo $_GET['remarks'];?>&pge=<?php echo $_GET['pge'];?>&desid=<?php echo $_GET['desid'];?>&remarks=<?php echo $_GET['remarks'];?>">
<input type="hidden" name="hProdListID" value="<?php echo $_GET['prodlist'];?>">
<input type="hidden" name="hmtypeID"  id ="hmtypeID" value="<?php echo $_GET['mtypeid'];?>">
<input type="hidden" name="hrem" value="<?php echo $_GET['remarks'];?>">
<input type="hidden" name="hdno" value="<?php echo $_GET['dno'];?>">
<br>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td class="tabmin">&nbsp;</td>
		<td class="tabmin2 txtredbold">Product List</td>
		<td class="tabmin3">&nbsp;</td>
	</tr>
	</table>
	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
	<tr>
		<td height="30" class="bgF9F8F7">
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td width="64" class="txt10"><input name="btnSearch2" type="submit" class="btn" value="Search" onClick="enableCheckAll();"/></td>
                <td width="133" align="left" class="txt10"><input id="txtSearch" name="txtSearch" type="text" class="txtfield" size="30" value="<?php if ($vSearch == "   ") { echo ""; } else { echo $vSearch; } ?>" /></td>
                <td width="1098" align="left" class="txt10"><div id="dvWarehouse"><input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" /></div></td>
                <td width="5" align="right" class="txt10"></td>
            </tr>
            </table>
        </td>
    </tr>
    </table>
    <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
    <tr>
    	<td colspan="2" valign="top" class="bgF9F8F7">
    		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
        	<tr>
          		<td class="tab">
          			<table width="100%"  border="0" cellpadding="0" cellspacing="" class="txtdarkgreenbold10 " height="25">
            		<tr align="center">
            			<td width="5%" height="20" align="center" class="bdiv_r">
            			<input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);">
            			</td>
              			<td width="5%" height="20" class="bdiv_r">Line No.</td>
              			<td width="20%" height="20" class="bdiv_r padl5" align="left">Product Code</td>
              			<td width="25%" height="20" class="bdiv_r padl5" align="left">Product Name</td>
          			</tr>
            		</table>
        		</td>
    		</tr>
    		<tr>
    			<td>
	        		<input type="hidden" name="hdnSearch" value="<?php echo $vSearch; ?>" />
	        		<input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
            		<div id="dvProductList" class="scroll_300">
              		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                  	<?php
                  		if ($rs_product->num_rows && $vSearch != "")
						{
							$cnt = 0;
							while ($row = $rs_product->fetch_object())
							{  

								$cnt ++;
								($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
								$pname = $row->Product;
								$pid = $row->ProductID;
								$pcode = $row->ProductCode;								
								$soh = number_format($row->SOH,0);
	
								
				  	?>
				  	<tr class="<?php echo $alt; ?>">
				  		<input name='hdnProductID[]' type='hidden' value="<?php echo $pid; ?>"/>
                  		<input name="hdnProductCode[]" type="hidden" value="<?php echo $pcode; ?>"/>
						<input name="hdnProductName[]" type="hidden" value="<?php echo $pname; ?>"/>
						<input name="hdnSOH[]" type="hidden" value="<?php echo $soh; ?>"/>						
						
						<td width="5%" align="center" height="20" class="borderBR"><input name="chkInclude[]" type="checkbox" id="chkInclude" value="<?php echo $pid; ?>"></td>
                  		<td width="5%" align="center" height="20" class="borderBR"><?php echo $cnt; ?></td>
                  		<td width="20%" height="20" class="borderBR padl5"><?php echo $pcode; ?></td>
                    	<td width="25%" height="20" class="borderBR padl5"><?php echo $pname; ?></td>

              		</tr>
                  	<?php
                  		}
                  	
					}else{?>
								<tr>
									<td colspan="3" align="center"><font color="#FF0000"><b>No records to display.<b></font></td>
								</tr>
								<?php } ?>
                	</table>
      			</div></td>
    		</tr>
    		</table>
		</td>
	</tr>
	</table>
	<?php if($rs_product->num_rows){ ?>
	<br>	
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="20">&nbsp;</td>	
		<?php  if($_GET['pge'] == 1) {?>	
		<td height="20"><div align="right"><input name="btnAdd" type="submit" class="btn" value="Add" onclick="javascript: confirmAdd('../../index.php?pageid=2&wid=' + <?php echo $_GET['wid']; ?>);"></div></td>
		<?php }else if ($_GET['pge'] == 2){?>
		<td height="20"><div align="right"><input name="btnAdd" type="submit" class="btn" value="Add" onclick="return confirmAdd('../../index.php?pageid=58&wid=' + <?php echo $_GET['wid']; ?>);"></div></td>
		<?php }else if ($_GET['pge'] == 3){?>
		<td height="20"><div align="right"><input name="btnAdd" type="submit" class="btn" value="Add"></div></td>
		<?php }?>
	</tr>
	</table>
	<?php }  $rs_product->close(); ?>
	<br>

</td>

</tr>
</table>
<br>
<table width="95%" align="center" cellpadding="0" cellspacing="0">
<tr>
	
</tr>
</table>
</form>
</body>