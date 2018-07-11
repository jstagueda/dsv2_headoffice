<?php
	include IN_PATH.DS."scAddPageDetails.php";
	
	$brochureid = '';
	$drpPageType = '';
	if(isset($_GET['ID']))
	{
		$brochureid = "&ID=".$_GET['ID'];
	}
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

<script type="text/javascript">
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
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Promo and Pricing Management System (PPMS)</span></td>
        </tr>
    </table>
    <br />
   
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Brochure </td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td height="10"> </td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
	<td width="60%" valign="top">
		<table width="80%"  border="0" align="left" cellpadding="0" cellspacing="1" class="">
		<tr>
			<td>
				<form name="frmSearchProdType" method="post" action="index.php?pageid=116">            	
				<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td width="50%">&nbsp;</td>
					<td width="29%" align="right">&nbsp;</td>
					<td width="21%" align="right">&nbsp;</td>
				</tr>
                <tr>
              		<td colspan="3" align="left" class="padl5">
						Search :            
						<input name="searchTxtFld2" type="text" class="txtfield" id="txtSearch2" size="20">
						<input name="btnSearch2" id="btnSearch2" type="submit" class="btn" value="Search" />
					</td>
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
        <tr>
        	<td height="25">&nbsp;</td>
        </tr>
		</table>
      	<table width="80%"  border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
      		<td class="tabmin">&nbsp;</td>
          	<td class="tabmin2"><span class="txtredbold">List of Brochure</span></td>
          	<td class="tabmin3">&nbsp;</td>
        </tr>
      	</table>
      	<table width="80%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl0">
        <tr>
			<td valign="top" class="bgF9F8F7" height="200px">
          		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              	<tr>
                	<td class="">
                    	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                        <tr align="center" class="tab">
                      		<td width="40%" class="bdiv_r" height="20"><div align="center">&nbsp;<span class="txtredbold">Code</span></div></td>
                          	<td width="60%" height="20"><div align="center"><span class="txtredbold">Name</span></div></td>
                        </tr>
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
	                                	$rowalt += 1;
	                                    ($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
	                                    echo "<tr align='center' class='$class'>
	                                        <td height='20' class='borderBR' width='40%' align='left'>&nbsp;<span class='txt10'>\t\t\t\t\t\t$row->Code</span></td>
	                                        <td class='borderBR' width='60%' align='left'>&nbsp;<span class='txt10'>
	                                        <a href='index.php?pageid=116.1&ID=$row->ID&searchedTxt=$brochureSearch' class='txtnavgreenlink'>
	                                        \t\t\t\t\t\t$row->Name</a></td></tr>";
	                                }
	                                $rs_brochure->close();
	                                
	                            }
	                            else
	                            {
	                                echo "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
	                            }
	                        ?>
                          </tr>                        
                          </table>
                  	</div></td>
              	</tr>
              	</table>
              </td>
          </tr>
	      </table>
      </td>
  	</tr>
	</table>
	</td>
	<td width="40%" height="20">&nbsp;</td>
</tr>
<tr><td height="20"></td></tr>
</table>
	</td>
  	</tr>
</table>