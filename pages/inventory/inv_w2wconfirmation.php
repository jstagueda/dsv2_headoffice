<?php
$sessionUniqueID = uniqid();
?>

<script language="javascript" type="text/javascript">
function confirmUnfreeze()
{
	if(confirm("Are you sure you want to unfreeze Inventory?") == true)
	{
		return true;
	}
	else
	{
		return false;
	}
}
</script>

<script src="js/jxconfirmw2w.js?rand=<?php echo $sessionUniqueID?>" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
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
    <td class="txtgreenbold13">List of Inventory Count</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<form name="frmInventoryCountView" action="index.php?pageid=32" method="post">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
  <tr>
    <td>
    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
        	<td width="5%" height="20">&nbsp;</td>
        	<td width="1%" height="20">&nbsp;</td>
        	<td width="94%" height="20">&nbsp;</td>
        </tr>
        <tr>
        	<td height="20" class="padl5">Search :</td>
        	<td height="20">&nbsp;</td>
        	<td height="20" align="left">            
        		<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearch; ?>" />
        		<input name="btnSearch" type="submit" class="btn" value="Search">
    		</td>
		</tr>	
        <tr>
          <td colspan="3" height="20">&nbsp;</td>
        </tr>
    	</table>
	</td>
  </tr>
</table>
<br />
<br /> <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="">
		  <?php 
			  if (isset($_GET['msg']))
			  {
				  $message = strtolower($_GET['msg']);
				  $success = strpos("$message","success"); 
				  echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div><br />";
			  } 
			  else if (isset($_GET['errmsg']))
			  {
			  	 echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredbold'>".$_GET['errmsg']."</div><br />";
			  }
		  ?> 
         </td>
          
        </tr>
      </table>

      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2">&nbsp;</td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        <tr>
          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td valign="top" class="" height="242">
                	<div id="pgContent">
                    <b>Loading Content...</b><img border="0" src="images/ajax-loader.gif">&nbsp;
                    </div>
                </td>
              </tr>
          </table></td>
        </tr>
      </table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="3" class="bgE6E8D9"></td>
        </tr>
      </table>
      <br>
      <table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td height="20" class="txtblueboldlink" width="50%">
            <div id="pgNavigation"><b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif"></div>
            
            </td>
            <td height="20" class="txtblueboldlink" width="48%">
            <div id="pgRecord" align="right"><b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif"></div>
            </td>
        </tr>
    </table>
    <script>
	//Onload start the user off on page one
	window.onload = showPage("1", "<?php echo $vSearch; ?>");    
    </script>
    </form>
    </td>
  </tr>
</table>
<br />
