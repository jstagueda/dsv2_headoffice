<?PHP 
	include IN_PATH.DS."scConfirmIssuanceOfSTA.php";
	(isset($_POST['btnSearch'])) ? $vSearch = $_POST['txtSearch'] : $vSearch = '';
	$sessionUniqueID = uniqid();
?>

<script src="js/jxbmmgt_candidacyapprvl.js?rand=<?php echo $sessionUniqueID ?>" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" style="min-height: 610px; display: block;">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    	<tr>
		      		<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=71">Sales Force Movement</a></td>
		    	</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">BM-F Appointment Approval</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		
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
    <form action="" method="post" name="formibmsalesreport">
    <table width="100%">
		  <tr>
                                <td align="right">Branch</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <input name="branchName" value="" class="txtfield" value="<?php echo $_POST['branchName'] ; ?>" >
                                    <input name="branch" value="0" type="hidden" class="txtfield">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">IBM Range</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <input name="IBMfrom" value="" class="txtfield">
                                    <input name="IBMfromHidden" value="0" type="hidden" class="txtfield">
                                    -
                                    <input name="IBMto" value="" class="txtfield">
                                    <input name="IBMtoHidden" value="0" type="hidden" class="txtfield">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">STATUS</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <select name="status" class="txtfield">
                                        <option value="0">SELECT ALL</option>
                                        <option value="1">ACTIVE</option>
										<option value="5">TERMINATED</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <br />
                                    <input type="hidden" name="page" value="1">
                                    <input type="hidden" name="pageibm" value="1">
                                    <input class="btn" type="submit" name="btnSearch" value="Submit">
                                </td>
                            </tr>
                        </table>
						</form>
    
    </td>
  </tr>
</table>

<br />
<br /> 
<form name="frmConfirmIssuanceofSTA" action="index.php?pageid=180.1" method="post">
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
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