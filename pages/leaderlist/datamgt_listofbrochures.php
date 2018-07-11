
<?php
	include IN_PATH.DS."scBrochures.php";
	
	$brochureid = '';
	$drpPageType = '';
	if(isset($_GET['ID']))
	{
		$brochureid = "&ID=".$_GET['ID'];
	}
?>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">

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
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Leader List</span></td>
        </tr>
    </table>
    <br />
   
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Brochures </td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td height="10"> </td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  <tr>
		    <td>
            	<form name="frmSearchProdType" method="post" action="index.php?pageid=114">            	
                    <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td width="50%">&nbsp;</td>
                      <td width="29%" align="right">&nbsp;</td>
                      <td width="21%" align="right">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3" class="txtpallete">&nbsp;&nbsp;
                         Search :            
                              <input name="searchTxtFld" type="text" class="txtfield" id="txtSearch" size="20" value="<?php echo $brochureSearch; ?>">
                              <input name="btnSearch" type="submit" class="btn" value="Search" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td align="right">&nbsp;</td>
                      <td align="right">&nbsp;</td>
                    </tr>
                </table>            	
                </form>
            </td>
		  </tr>
		</table>
		      <br>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td class="tabmin">&nbsp;</td>
		          <td class="tabmin2"><span class="txtredbold">List of Brochures</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl0">
		        <tr>
		          <td valign="top" class="bgF9F8F7" height="200px">
                      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                          <tr>
                            <td class="tab bordergreen_T">
                                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                                    <tr align="center">
                                      <td width="28%" height='25' class="bdiv_r"><div align="center">&nbsp;<span class="txtpallete">Code</span></div></td>
                                      <td width="55%" height='25' class="bdiv_r"><div align="center"><span class="txtpallete">Name</span></div></td>
                                      <td width="20%" height='25'><div align="center"><span class="txtpallete">Status</span></div></td>
                                </table>
                            </td>
                          </tr>
                          <tr>
                            <td class="bordergreen_B"><div class="scroll_300">                        
                                    <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                                        <tr>
                                               <?php 
                                                    if($rs_brochure->num_rows)
                                                    {
                                                        $rowalt=0;
                                                        while($row = $rs_brochure->fetch_object())
                                                        {
                                                            ($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
                                                            echo "<tr align='center' class='$class'>
                                                                <td height='25' class='borderBR' width='28%' align='left'>&nbsp;
																<span class='txt10'>\t\t\t\t\t\t$row->Code</span></td>
                                                                <td class='borderBR' width='55%' align='left'>&nbsp;<span class='txt10'>
                                                                <a href='index.php?pageid=114.1&ID=$row->ID' class='txtnavgreenlink'>
                                                                \t\t\t\t\t\t$row->Name</a></td>
                                                                <td class='borderBR' width='20%' align='center'><span class='txt10'>$row->Status</span></td>
                                                                </tr>";
                                                                
                                                            $rowalt+=1;
                                                        }
                                                        $rs_brochure->close();
                                                        
                                                    }
                                                    else
                                                    {
                                                        echo "<tr align='center'><td height='20' class='borderBR' colspan='3'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
                                                    }
                                                ?>
                                      </tr>                        
                                    </table></div>
                            </td>
                          </tr>
                      </table>
                  </td>
		        </tr>
		      </table>
	</td>
	<td width="60%">&nbsp;</td>
     
  </tr>
</table>
	</td>
   </tr>
   <tr><td height="20"></td></tr>
 </table>
 
    </td>
  </tr>
</table>

