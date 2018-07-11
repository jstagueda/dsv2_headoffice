<?php

  require_once "../initialize.php";
  
 $modid = $_GET["modid"];
 
 $rs_submod = $sp->spSelectSubModuleByModuleID($modid);
  
  echo "<select name=\"cboSubModule\" style=\"width:160px\" class=\"txtfield\" onchange=\"showModule(this.value);\">";
  echo "<option value=\"0\" >[SELECT HERE]</option>";
  
  if ($rs_submod->num_rows){
	 while ($row = $rs_submod->fetch_object())  {
		echo "<option value='$row->ID'>$row->Name</option>";
	 }
  }
  
  echo "</select>";
  
  
  ?>
