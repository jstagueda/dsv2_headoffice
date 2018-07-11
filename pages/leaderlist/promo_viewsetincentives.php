<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script src="js/jxViewSetPromo.js" language="javascript" type="text/javascript"></script>

<style type="text/css">

div.autocomplete {
  position:absolute;
  /*width:300px;*/
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

.txtdarkgreenbold10 td{border-bottom: 2px solid #FFA3E0;}
.trlist td{border-bottom: 2px solid #FFA3E0;}
</style>

<?php
		
	$vSearch ="";
	$scodedesc = "";
	$sproductcode = "";
	$isinc = 0;	
	
	if ($_GET['inc'] == 1)
	{
		$isinc = 0;
	}
	else
	{
		$isinc = 1;
	}

	
	(isset($_POST['txtPromoCodeDesc']) ? $scodedesc = $_POST['txtPromoCodeDesc'] : $scodedesc = "");
	(isset($_POST['txtProdCode1']) ? $sproductcode = $_POST['txtProdCode1'] : $sproductcode = "");
	
	if(isset($_POST["btnCreate"])) 
	{
		if ($isinc == 0)
		{
			header("Location:index.php?pageid=67");
		}
		else 
		{
			header("Location:index.php?pageid=67.2");
		}
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
		<?php if($isinc == 0)
		{
		
		?>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Overlay Promo</a></td>
		<?php 
		}
		else
		{
			
		?>
		<td width="70%">&nbsp;<a class="txtgreenbold13">Incentives Promo</a></td>
		<?php 
		}
		?>	
			
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
<form name="frmViewSetPromo" style="min-height:570px;" method="post" action="index.php?pageid=66&inc=<?php if($isinc == 0) echo "1"; else echo"2";?>">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">&nbsp;</td> 
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Action</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td width="10%">&nbsp;</td>
			<td width="90%">&nbsp;</td>
		</tr>			
		<tr>
		    <td width="13%" height="20"><div align="right" class="txtpallete"><strong>Promo Code / Description : </strong></div></td>
		    <td width="13%" height="20"><div align="left">&nbsp;&nbsp;<input name="txtPromoCodeDesc" type="text" class="txtfield" id="txtPromoCodeDesc" value="<?php echo $scodedesc; ?>" size="30"></div></td>
	    </tr>
		<tr>
		  	<td width="13%" height="20"><div align="right" class="txtpallete"><strong>Item Code: </strong></div></td>
		  	<td width="13%" height="20"><div align="left">&nbsp; 
				<input name="txtProdCode1" type="text" class="txtfield" id="txtProdCode1" value="<?php echo $sproductcode;?>" size="30" >
				<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
				<div id="prod_choices1" class="autocomplete" style="display:none"></div>
				<script type="text/javascript">							
					 //<![CDATA[
                    	var prod_choices = new Ajax.Autocompleter('txtProdCode1', 'prod_choices1', 'includes/scProductListAjax.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator1'});																			
                    //]]>
				</script>
				<input name="hProdID1" type="hidden" id="hProdID1" value="" /> 
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
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
        
       <?php if($isinc == 0)
        {

        ?>
                <td class="tabmin2"><div align="left" class="txtredbold padl5">List of Overlay Promos</div></td>
        <?php 
        }
        else
        {

        ?>
       <td class="tabmin2"><div align="left" class="txtredbold padl5">List of Incentive Promos</div></td>
        <?php 
        }
        ?>	
       
        
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
<tr>
  <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
	window.onload = showPage("1", "<?php echo $scodedesc; ?>", "<?php echo $sproductcode; ?>", <?php echo $isinc?>);    
    </script>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
 		<!--<input name='btnPrint' type='submit' class='btn' value='Print'> List of Overlay Promos -->
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