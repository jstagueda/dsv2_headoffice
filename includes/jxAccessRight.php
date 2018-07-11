<?php
  
  	require_once "../initialize.php";
	global $database;
	$utid = 0;
	$utid = $_GET['utid'];
	//$utid = $_POST['cboUserType'];
	
	$rs_modulecontrol = $sp->spSelectUserTypeModuleControl($database, $utid);
	$action = $_GET['mod'];
	$mod = "";
	$sub = "";
	if ($action  == "t")
	{
		if ($rs_modulecontrol->num_rows)
		{
			$rowalt = 0;
			while  ($row = $rs_modulecontrol->fetch_object())
			{
				$rowalt += 1;
            	($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
?>

    <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr align="center" <?php echo "class='$class'"; ?>>
            <td width="6%" height="14" align="center">
            		<?php
						($row->UserTypeID != 0) ? $chk = 'checked' : $chk = '';
						
	            		echo "<input name=\"chkID[]\" $chk type=\"checkbox\" class=\"inputOptChk\" value=\"$row->ModuleControlID\" />";
					?>
            </td>
            <td width="14%">
            <strong>
			<?php 
				($mod != $row->Module) ? $mod = $row->Module : $mod = "";
					echo $mod; 
				$mod = $row->Module;
			?>
			</strong>
			</td>
            <td width="17%" height="14">
			<?php 
				($sub != $row->SubModule) ? $sub = $row->SubModule : $sub = "";
					echo $sub; 
				$sub = $row->SubModule;
			?></td>
            <td width="19%" height="14"><?php echo $row->ModuleControl; ?></td>
            <td width="18%" height="14"><?php echo $row->Description; ?></td>
        </tr> 
    </table>
<?php				
		}
		
	}
	}
	else if ($action  == "p")
	{
	 ?>
      
     <?php
	}

	
?>

   