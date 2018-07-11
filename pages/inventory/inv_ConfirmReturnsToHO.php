<?PHP 
	include IN_PATH.DS."scConfirmReturnsToHO.php";
	(isset($_POST['btnSearch'])) ? $vSearch = $_POST['txtSearch'] : $vSearch = '';
?>

<script src="js/jxConfirmReturnsToHO.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" style="min-height: 610px; display: block;">
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
    		<td class="txtgreenbold13">Returns to Head Office</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
<form name="frmConfirmReturnsToHO" action="index.php?pageid=31" method="post">
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2">Action</td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
  <tr>
    <td>
    
    <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td width="50%">&nbsp;</td>
          <td width="29%" align="right">&nbsp;</td>
          <td width="21%" align="right">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">
			 Search            
				  <input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30" value="" />
				  <input name="btnSearch" type="submit" class="btn" value="Search">
			 
		  </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
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
          <td class="tabmin2">Result(s)</td>
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
	window.onload = showPage("1", "<?php echo $vSearch; ?>");    
    </script>
    <br />
    </form>
    </td>
  </tr>
</table>