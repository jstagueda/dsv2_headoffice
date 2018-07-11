<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<script src="js/jxViewStepLevelPromo.js" language="javascript" type="text/javascript"></script>

<?php
	$vSearch ="";
	$scodedesc = "";
	$sproductcode = "";
	
	(isset($_POST['txtPromoCodeDesc']) ? $scodedesc = $_POST['txtPromoCodeDesc'] : $scodedesc = "");
	(isset($_POST['txtProductCode']) ? $sproductcode = $_POST['txtProductCode'] : $sproductcode = "");
	
	if(isset($_POST["btnCreate"])) 
	{
		
		header("Location:index.php?pageid=65");
	}
	if(isset($_POST["btnSearch"])) 
	{
		
	
	}
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		    <td width="70%" class="txtgreenbold13" align="Left"></td>
			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Step Level Promo</a></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<?php
if (isset($_GET['errmsg'])) 
{
?>
<br>
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtreds">&nbsp;<b><?php echo $_GET['errmsg']; ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
}
?>
<?php
if (isset($_GET['msg'])) 
{
?>
<br>
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $_GET['msg']; ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
}
?>
<br>
<form name="frmViewStepLevel" method="post" action="index.php?pageid=64">
<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td width="10%">&nbsp;</td>
			<td width="90%">&nbsp;</td>
		</tr>			
		<tr>
		    <td width="13%" height="20"><div align="left" class="padl5"><strong>Promo Code/Description : </strong></div></td>
		    <td width="13%"  height="20"><div align="left"><input name="txtPromoCodeDesc" type="text" class="txtfield" id="txtPromoCodeDesc" value="<?php echo $scodedesc;?>" size="30"></div></td>
	    </tr>
		<tr>
		  	<td width="13%"  height="20"><div align="left" class="padl5"><strong>Product Code: </strong></div></td>
		  	<td width="13%"  height="20"><div align="left">
				<input name="txtProductCode" type="text" class="txtfield" id="txtSearch" value="<?php echo $sproductcode;?>" size="30" > 
				<input name="btnSearch" type="submit" class="btn" value="Search">
		  	</div></td>
		</tr>
		<tr>
			<td height="20" colspan="2">&nbsp;</td>
		</tr>	
		</table>
	</td>
</tr>
</table>
<br>
<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
	<td class="tabmin2"><div align="left" class="txtredbold padl5">List of Step Level Promos</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
  <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td class="" height="242" valign="top">
        <div id="pgContent">
        <b>Loading Content...</b><img border="0" src="images/ajax-loader.gif">&nbsp;
        </div>
        
        
        </td>
      </tr>
  </table></td>
</tr>
</table>
<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
	window.onload = showPage("1",  "<?php echo $scodedesc; ?>", "<?php echo $sproductcode; ?>");    
    </script>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
 		<!--<input name='btnPrint' type='submit' class='btn' value='Print'>-->
 		<?php
 			if ($_SESSION['ismain'] == 1)
 			{
  				echo "<input name='btnCreate' type='submit' class='btn' value='Create New'>";
 			}
		?>  
	</td>			
</tr>
</table>
</form>
<br>