<style type="text/css">
<!--
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}
-->
</style>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<?php
	include IN_PATH.DS."scPricing.php";
?>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{ 		
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) 
		selObj.selectedIndex = 0;
}
function CheckAll()    
{
	var ca = document.frmPricing.chkAll;
	if(document.frmPricing.hchk.value > 1)
	{
		for(i=0; i<document.frmPricing.chkInclude.length; i++)
		{
			if(ca.checked == true)
			{
				document.frmPricing.chkInclude[i].checked = true;
			}
			else if(ca.checked == false)
			{
				document.frmPricing.chkInclude[i].checked = false;
			}
		}
	}
	else
	{
		if(ca.checked == true)
		{
			document.frmPricing.chkInclude.checked = true;
		}
		else if(ca.checked == false)
		{
			document.frmPricing.chkInclude.checked = false;
		}
	}
}

function checkAll(bin) 
{
	var elms = document.frmPricing.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkSelect[]') 
	  {
		  elms[i].checked = bin;		  
	  }	
}
function checker()
{
	var ml = document.frmPricing;
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
function ConfirmSave()
{
	if (!checker())
	{
		alert('Select product(s) to be updated.');
		return false;		
	}
	else
	{
		if (confirm('Are you sure you want to save this transaction?') == false)
			return false;
		else
			return true;		
	}
}

function UnCheck()
{

    var chkAll = document.frmPricing.chkAll;
    
    chkAll.checked = false;
}

</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav.php");
		?>

      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management </span></td>
        </tr>
    </table>
    <br />
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Pricing</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
	if ($msg != '')
	{
?>
<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
	<td><span class="txtblueboldlink"><?php echo $msg; ?></span></td>
</tr>
</table>
<?php
	}
	else if ($errmsg != '')
	{
?>
<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
	<td><span class="txtredsbold"><?php echo $errmsg; ?></span></td>
</tr>
</table>
<?php
	}

?>

<br />
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td valign="top">
        <?php 
          if(isset($_GET['ptid']))
			{
				$ptid = $_GET['ptid'];
			
			}
			else
			{
				$ptid = 0;
			}
			?>
         <form name="frmSearchPricing" method="post" action="index.php?pageid=43">
           <table width="40%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
           
            <tr>
          
              <!--  <td width="20%" height="30" align="right" class="padr5"><strong>Select Pricing Template :</strong></td>
                <td width="30%" height="30">
                	
                     <select name="lsrPTemplate" class="txtfield" style="width:150px" onChange="MM_jumpMenu('parent',this,0)">
						<?PHP
							echo "<option value='index.php?pageid=43&ptid=0' selected>[SELECT HERE]</option>";
							if ($rs_cboRef->num_rows)
							{  $name = '';
								while ($row = $rs_cboRef->fetch_object())
								{
								if ($ptid == $row->ID) 
									$sel = "selected";
									else 
									$sel = "";
									echo "<option value='index.php?pageid=43&refid=".$row->ReferenceID. "&ptid=".$row->ID."' $sel >".$row->Code."</option>";	
																		
								}
							}		
						?>
                    </select>  
                </td> --> 
            </tr>
            <tr>
                <td width="20%" height="30" align="right" class="padr5"><strong>Search :		</strong>
                <input type="hidden" name="txtptid" value="<?php echo $ptid?>" />
                <input type="hidden" name="txtrefid" value="<?php echo $refid?>" /></td>
                
                <td width="30%" height="30"><input name="txtSearch" type="text" class="txtfield" id="txtSearch" value="<?php echo $search;?>" size="30">
                  <span class="padr5">
                  <input name="btnSearch2" type="submit" class="btn" value="Search" />
                  </span></td>
            </tr>
          
            </table>
          </form>
        </td>
      </tr>
      </table>
      
 <table width="97%" border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
      	<td valign="top">
          <br>
          <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
			  <tr>
				<td class="tabmin">&nbsp;</td>
				<td align="left" class="tabmin2 padl5"><span class="txtredbold">PRICE LIST TEMPLATE </span></td>
				<td class="tabmin3">&nbsp;</td>
			</tr>
		</table>
  <form name="frmPricing" method="post" action="includes/pcPricing.php">
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0"  class="bgF9F8F7 txtdarkgreenbold10 bordergreen">
		<tr class="tab">
			<td width="5%" height="20" class=""><div align="center">
			<input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);" /></div></td>
			<td width="33%" height="20" align="left" class="padl5">Product Code - Product Name</td>
			<td width="15%" height="20" align="left" class="padl5">UOM</td>
			<td width="15%" height="20" align="center" class="">Last Pricing Date</td>
			<!--  <td width="15%" height="20" align="center" class="padr5">SKU Mark-Up</td>-->
			<td width="15%" height="20" align="center" class="padr5">Selling Price</td>
		</tr>
		</table>


<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
			
<tr>
	<td>
		<div class="scroll_500">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr >
		<?php 
			$code ='';
			$name='';
			$uom='';
			$ldate='';
			$prodId=0;
			$uomID=0;
			
		if($rs_pricing->num_rows)
		{
			$rowalt = 0;
			echo"<input type='hidden' name='hchk'  value='$rs_pricing->num_rows' />";
			while($rs_row = $rs_pricing->fetch_object())
				{
					$rowalt++;
					($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
					$code = $rs_row->code;
					$name = $rs_row->name;
					$uom = $rs_row->uom;
					$ldate = date("M d,Y",strtotime($rs_row->lmd));
					$prodID = $rs_row->id;
					$uomID = $rs_row->uomid;
					$prodiduomid = $prodID."_"."$uomID";
					
					echo"<input type='hidden' name='uomid$prodiduomid' value='$uomID' />";
					echo"<input type='hidden' name='prodid$prodiduomid' value='$prodID' />";
					
					echo "<tr align='center' class='$class' >
					<td width='5%' height='20' ><div align='center'><input type='checkbox' name='chkSelect[]' id='chkSelect' onclick='UnCheck();' value='$prodiduomid' </div></td>
					<td width='33%' height='20' ><div align='left' class='padl5'>$code - $name</div></td>
					<td width='15%' height='20' ><div align='left' class='padl5'>$uom</div></td>
					<td width='15%' height='20' ><div align='center'>$ldate</div></td>
					
					";
					$unitprice= number_format($rs_row->SellingPrice, 2, '.', '');
						 echo" <td width='15%' height='20' ><div align='center' class='padr5'>
						 <input type='text' name='txtSKUMU$prodiduomid' value='$unitprice' style='text-align:right' class='txtfield3'></td></tr>";
				
					
					
					
				}
		}	
		else
		{
			echo "<tr align='center'>
                 <td height='20' class='borderBR' colspan='5'><span class='txt10 style1'><strong>No record(s) to display. </strong>" .
                 "</span></td></tr>";			
		}	
		?>
		</table>
		</div>
	</td>
	
</tr>
</table>

<br>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td align="center">
                <input name="btnSave" type="submit" class="btn" value="Save" onclick="return ConfirmSave();"></td>
              </tr>
            </table>
           
</form>
<br />
            
        </td>
      </tr>
    </table>
	</td>
   </tr>
 </table>
    </td>
  </tr>
</table>