<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<?php
	ini_set('display_errors',1);
	global $database;	
	require_once "../../initialize.php";	
	include IN_PATH.DS."scBrochures.php";
	
	$brochureid = '';
	if(isset($_GET['ID']))
	{
		$brochureid = $_GET['ID'];
	}
	
	$customerRows = '';
	$customerRows1 = '';
	$rowalt = 0;
	$class = "";
	
	if ($rs_brochurelayout->num_rows)
	{
		while ($row = $rs_brochurelayout->fetch_object())
		{
			//($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
			$layout = ''; $layout1= '';
			
			if($row->LayoutTypeID == 1)
		   	{
		   		$layout = $row->LayoutType." (TOP)";
		   		$layout1 = $row->LayoutType." (BOTTOM)";
		   	}
		   	else
		   	{		   		
		   		$layout =  $row->LayoutType." (LEFT)";
		   		$layout1 = $row->LayoutType." (RIGHT)";
		   	}
		   
		   $customerRows .= "<tr align='center' class='$class'> <td width='10%' height='20' class='borderBR' align='center'>		   				
		   				<input type='checkbox' name='chkSelect[]' onclick='UnCheck($row->PageNum);' id='chkSelect' value='$row->PageNum'></td>
					  	<td width='20%' height='20' class='borderBR padl5' align='left'><span class='txt10'>$row->PageNum</span></td>
					  	<td width='35%' height='20' class='borderBR padl5' align='left'>$row->PageType</td>
					  	<td width='35%' height='20' class='borderBR padl5' align='left'>$layout</td></tr>";
			if($row->LayoutTypeID == 1 || $row->LayoutTypeID == 2)
		   	{
		   		$customerRows .= "<tr align='center' class='$class'> <td width='10%' height='20' class='borderBR' align='center'>		   				
		   				<input type='checkbox' name='chkSelect2[]' onclick='UnCheck1($row->PageNum);' id='chkSelect2' value='$row->PageNum'></td>
					  	<td width='20%' height='20' class='borderBR padl5' align='left'><span class='txt10'>$row->PageNum</span></td>
					  	<td width='35%' height='20' class='borderBR padl5' align='left'>$row->PageType</td>
					  	<td width='35%' height='20' class='borderBR padl5' align='left'>$layout1</td></tr><tr><td height='10'></td></tr>";
	  		}
	  		$rowalt +=1;
		}
		$rs_brochurelayout->close();
	}
	else
	{
		$customerRows = "<tr align='center'><td height='20' colspan='4' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td></tr>";
	}
?>

<script type="text/javascript">
function redirect(linkid)
{
	opener.location.href=linkid + "&hdnids=" + document.frmLinkPromo.hdnID.value + "&hdnids2=" + document.frmLinkPromo.hdnID1.value;
	window.close();
}

function checkAll(bin) 
{
	var elms = document.frmLinkPromo.elements;

	for (var i = 0; i < elms.length; i++)
	{
	  if (elms[i].name == 'chkSelect[]') 
	  {
		  elms[i].checked = bin;
		  if(document.frmLinkPromo.hdncnt.value == "")
		  {
			  document.frmLinkPromo.hdnID.value += elms[i].value;
			  document.frmLinkPromo.hdncnt.value = 1;
		  }
		  else
		  {
			  document.frmLinkPromo.hdnID.value += "," + elms[i].value;	
			  
		  }
	  }		

	  if (elms[i].name == 'chkSelect2[]') 
	  {
		  elms[i].checked = bin;
		  if(document.frmLinkPromo.hdncnt1.value == "")
		  {
			  document.frmLinkPromo.hdnID1.value += elms[i].value;
			  document.frmLinkPromo.hdncnt1.value = 1;
		  }
		  else
		  {
			  document.frmLinkPromo.hdnID1.value += "," + elms[i].value;	
			  
		  }
	  }		
	}
}

function UnCheck(frm)
{

    var chkAll = document.frmLinkPromo.chkAll;
    
    chkAll.checked = false;

    var cnt = 1;

    if(document.frmLinkPromo.hdncnt.value != "")
    {
    	document.frmLinkPromo.hdnID.value += "," + frm;
    }
    else
    {
    	document.frmLinkPromo.hdnID.value += frm;
    	document.frmLinkPromo.hdncnt.value = 1;
    }
    
}

function UnCheck1(frm)
{

    var chkAll = document.frmLinkPromo.chkAll;
    
    chkAll.checked = false;

    var cnt = 1;

    if(document.frmLinkPromo.hdncnt1.value != "")
    {
    	document.frmLinkPromo.hdnID1.value += "," + frm;
    }
    else
    {
    	document.frmLinkPromo.hdnID1.value += frm;
    	document.frmLinkPromo.hdncnt1.value = 1;
    }
    
}

function checker()
{
	var ml = document.frmLinkPromo;
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
</script>

<br>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2"><span class="txtredbold padl5">Link Promos</span></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>    
		<form name="frmLinkPromo" method="post" action="">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        <tr>
        	<td valign="top" class="bgF9F8F7">
        		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              	<tr>
                	<td>
                		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                    	<tr align="center" class="tab">
                      		<td width="10%"><div align="center" class="bdiv_r"><input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);" /></div></td>	
                      		<td width="20%"><div align="left" class="bdiv_r padl5">Page Number</div></td>
                      		<td width="35%"><div align="left" class="bdiv_r padl5">Page Type</div></td>
                      		<td width="35%"><div align="left" class="padl5">Page Layout</div></td>
                     	</tr>
                		</table>
                	</td>
              		</tr>
              		<tr>
                		<td class="bordergreen_B">
                			<div class="scroll_300">
	                   			<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
	                   				<input type="hidden" name="hdnID" value="" />
	                   				<input type="hidden" name="hdncnt" value="" />
	                   				<input type="hidden" name="hdnID1" value="" />
	                   				<input type="hidden" name="hdncnt1" value="" />
									<?php echo $customerRows; ?>
	                  			</table>
                          	</div>
                    	</td>
              		</tr>
          			</table>
          		</td>
        	</tr>
      		</table>
      		<br>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  		<tr>
	    		<td align="center"><input name="btnLink" type="submit" class="btn" value="Link" onclick="javascript: redirect('../../index.php?pageid=114.1&link=yes&ID=<?php echo $brochureid; ?>&promoid=<?php echo $_GET['promoid']; ?>');"></td>
	  		</tr>
			</table>
			</form>
		</td>
	</tr>
</table>