<?PHP 
	include IN_PATH.DS."scViewSalesInvoice.php";
?>

<script src="js/jxPagingSalesInvoice.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    <tr>
		      <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
		    </tr>
		</table>
		</td>
	</tr>
</table>
      <br>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">List of Sales Invoices</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<form name="frmSalesInvoiceView" action="index.php?pageid=40" method="post">
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
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
							<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearch; ?>" />
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
	<br /> 

	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          	<td class="">
			  	<?php 
				  	if (isset($_GET['msg']))
				  	{
					  	$message = strtolower($_GET['msg']);
					  	$success = strpos("$message","success"); 
					  	echo "<div align='left' class='txtblueboldlink'><strong>".$_GET['msg']."</strong></div><br>"; 
				  	} 
				  	
				  	if (isset($_GET['tid']))
				  	{
				  	
				  		$tid = strtolower($_GET['tid']);
					  	echo"
							<script language='Javascript'>
						    blah = window.open('pages/sales/sales_SalesInvoiceDetailsPrint.php?tid=$tid&new=1');
						    </script>";
					  
					  	
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