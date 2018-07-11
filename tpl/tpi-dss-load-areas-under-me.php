<?php
require_once('../initialize.php');

if($_GET['area_level_id']=='6') {

  foreach(((array)$dev1_cm->load_areas_under_me($_GET['area_id'], $_GET['area_level_id'])) as $area) { echo trim($area->Name); break; };
} else {
  
  foreach($dev1_cm->load_areas_under_me($_GET['area_id'], $_GET['area_level_id']) as $area) 
  echo '<option value="'.$area->ID.'">'.$area->Name.'</option>';          
}

