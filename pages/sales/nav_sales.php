<?PHP 
	include IN_PATH.DS."scMain.php";
	global $database;
?>

<script language="javascript" type="text/javascript">
function checkInvStatus(statid)
{
	if(statid == 21)
	{
		alert("Cannot access this module, Inventory Count is in progress.");
		return false;		
	}
	else
	{
		return true;
	}
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="middle" class="topnav">&nbsp;<span class="txtgreen">Welcome, <span class="txtgreenbold">Administrator</span></span></td>
</tr>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="tabmain">&nbsp;<span class="txtwhitebold">CORE MODULES</span></td>
</tr>
</table>
<div id="masterdiv">
<table width="100%"  border="0" cellspacing="1" cellpadding="0">
<tr>
      <td>
          <?PHP  
	  		$submodid = 0;
			if ($rs_navsub->num_rows)
			{
				while ($row = $rs_navsub->fetch_object()) 
				{	
					$submodid = $row->SubModuleID;
					$rs_mc = $sp->spSelectModuleControl($database, $userid, $submodid);
		?>
  		<div style="cursor:pointer" onClick="SwitchMenu('sub<?php echo $submodid; ?>')">
      	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td width="3" class="nav1">&nbsp;</td>
          	<td class="nav"><span class="txtnavgreenbold">&nbsp;<?php echo $row->SubModule; ?></span></td>
      	</tr>
      	</table>
      	<span class="submenu" id="sub<?php echo $submodid; ?>">
	  	<?php 
	  		if ($rs_mc->num_rows)
	  		{
	  			while ($arow = $rs_mc->fetch_object()) 
	  			{
		?>
		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
        	<td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=<?PHP echo $arow->PageID; if ($arow->PageID==104) echo '&adv=1';if ($arow->PageID==34) echo '&adv=0';?> " class="txtnavgreenlink" <?php if($arow->PageID != 95 && $arow->PageID != 98 && $arow->PageID != 99) {?>onclick="return checkInvStatus(<?php echo $statusid_inv; ?>);" <?php } ?>><?php echo $arow->ModuleControl; ?></a></td>
        </tr>
        </table>
        <?php 
        		}
        		$rs_mc->close();
    		}
		?>
      	</span>
      	</div>
      	<?php	
      			}
      			$rs_navsub->close();
  			}
		?>
	</td>
</tr>
</table>
  </div>
  <Br /><Br />
  <!--<div id="masterdiv">
  <table width="100%"  border="0" cellspacing="1" cellpadding="0">
    <tr>
      <td>		    <div style="cursor:pointer" onClick="SwitchMenu('sub1')">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="3" class="nav1">&nbsp;</td>
          <td class="nav"><span class="txtnavgreenbold">&nbsp;Sales Order</span></td>
        </tr>
      </table>
      <span class="submenu" id="sub1">
      <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=34" class="txtnavgreenlink">Create Sales Order</a></td>
        </tr>
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=35" class="txtnavgreenlink">View Sales Order</a></td>
        </tr>
      </table></span>
      </div>
      <div style="cursor:pointer" onClick="SwitchMenu('sub2')">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3" class="nav1">&nbsp;</td>
            <td class="nav"><span class="txtnavgreenbold">&nbsp;Delivery Receipt</span></td>
          </tr>
        </table>
        <span class="submenu" id="sub2">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=6" class="txtnavgreenlink">Create Delivery Receipt</a></td>
        </tr>
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=6" class="txtnavgreenlink">View Delivery Receipt</a> </td>
        </tr>
      </table>
        </span>
        </div>
        <div style="cursor:pointer" onClick="SwitchMenu('sub3')">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3" class="nav1">&nbsp;</td>
            <td class="nav"><span class="txtnavgreenbold">&nbsp;Sales Invoice</span></td>
          </tr>
        </table>
        <span class="submenu" id="sub3">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=8" class="txtnavgreenlink">Create Sales Invoice</a></td>
        </tr>
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=8" class="txtnavgreenlink">View Sales Invoice</a> </td>
        </tr>
      </table>
        </span> </div>
        <div style="cursor:pointer" onClick="SwitchMenu('sub4')">
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="3" class="nav1">&nbsp;</td>
              <td class="nav"><span class="txtnavgreenbold">&nbsp;Collection</span></td>
            </tr>
          </table>
          <span class="submenu" id="sub4">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=9" class="txtnavgreenlink">Create Collection</a></td>
        </tr>
        <tr>
          
            <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=9" class="txtnavgreenlink">View Collection</a> </td>
        </tr>
      </table>
        </span>
        </div>
        <div style="cursor:pointer" onClick="SwitchMenu('sub5')">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3" class="nav1">&nbsp;</td>
            <td class="nav"><span class="txtnavgreenbold">&nbsp;Accounts Receivable</span></td>
          </tr>
        </table>
        <span class="submenu" id="sub5">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=7" class="txtnavgreenlink">Aging of A/R</a> </td>
        </tr>
      </table>
        </span>
        </div>
         <div style="cursor:pointer" onClick="SwitchMenu('sub6')">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3" class="nav1">&nbsp;</td>
            <td class="nav"><span class="txtnavgreenbold">&nbsp;Customer Return</span></td>
          </tr>
        </table>
        <span class="submenu" id="sub6">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=12" class="txtnavgreenlink">Create Customer Return</a></td>
        </tr>
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=12" class="txtnavgreenlink">View Customer Return </a> </td>
        </tr>
      </table>
        </span>
        </div></td>
    </tr>
  </table>
  </div>-->