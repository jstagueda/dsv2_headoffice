<?PHP 

include IN_PATH.DS."scMain.php";
global $database;
?>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="middle" class="topnav">&nbsp;<span class="txtgreen">Welcome, <span class="txtgreenbold"><?PHP echo $loginname; ?></span></span></td>
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
				$rs_mc = $sp->spSelectModuleControl($database,$userid, $submodid);
				?>
             <div style="cursor:pointer" onClick="SwitchMenu('sub<?php echo $submodid; ?>')">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                          <td width='3' class='nav1'>&nbsp;</td>
                          <td class='nav'><span class='txtnavgreenbold'><?php echo $row->SubModule; ?></span></td>
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
                  <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=<?PHP echo $arow->PageID; ?>" class="txtnavgreenlink"><?php echo $arow->ModuleControl; ?></a></td>
                </tr>
              </table>
				 <?php } $rs_mc->close();
                        }
                 ?>
              </span>
              </div>
      		<?php	}$rs_navsub->close();
		}
       ?>

        </td>
    </tr>
    <tr>
    	<td valign="bottom"><Br /> <table width="100%"  border="0" cellpadding="0" cellspacing="1" >
                
                <tr>
                  <td height="20" align="center"><!--<a href="index.php?pageid=0" class="txtblueboldlink"><u>Back to Home</u></a>--></td>
                </tr>
              </table></td>
    </tr>
  </table>
  
  </div>
  
   