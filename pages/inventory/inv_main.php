<?PHP 
	include IN_PATH.DS."scMain.php";
	global $database;
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<script language="javascript" type="text/javascript">
function checkInvStatus(statid)
{
	if(statid == 21){
		alert("Cannot access this module, Inventory Count is in progress.");
		//alert("While the count is on-going inquiry menus should be available even after freezing the inventory.");
		return false;		
	}
	else{
		return true;
	}
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="middle" class="topnav">&nbsp;<span class="txtgreen">Welcome, <span class="txtgreenbold"><?PHP echo $loginname; ?></span></span></td>
      </tr>
    </table> 
    <?php $session->unset_trans_prod();?>
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
			if ($rs_navsub->num_rows){
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
                if ($rs_mc->num_rows){
                  while ($arow = $rs_mc->fetch_object()) 
                 {
          ?>
			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
				<tr>
				<td height="24" class=" borderdashed_b txtpad_L">
				<a href="index.php?pageid=<?PHP echo $arow->PageID; ?>" class="txtnavgreenlink" <?php if($arow->PageID != 31 &&  $arow->PageID != 100 && $arow->PageID != 33 && $arow->PageID != 32 && $arow->PageID != 57 && $arow->PageID != 126 && $arow->PageID != 133 && $arow->PageID != 134) {?>onclick="return checkInvStatus(<?php echo $statusid_inv; ?>);" <?php } ?>><?php echo $arow->ModuleControl; ?></a></td>
				</tr>
			</table>
             <?php } $rs_mc->close();
                        }
                 ?>
          </span>
		  </div>
           <?PHP
				}
				$rs_navsub->close();
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
      
      
      
      
      
      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;<span class="txtgreenbold13">Inventory</span></td>
        </tr>
    </table></td>
  </tr>
</table>