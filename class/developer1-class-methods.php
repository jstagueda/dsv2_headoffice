<?php
/* 
  @package TPI Developer 1's Class Methods.
  @author John Paul Pineda.
  @email paulpineda19@yahoo.com
  @copyright 2012 John Paul Pineda.
  @version 1.0 September 4, 2012.
  
  @description TPI Developer 1's Class Methods. 
*/

class Developer1_Class_Methods {
  
  // function load_areas($area_level_id)
  function load_areas($area_level_id) {
   
    global $database;
    
    $query="SELECT * FROM area WHERE AreaLevelID='".$area_level_id."' AND StatusID='1' ORDER BY Name";
    $rs=$database->execute($query);
   
    if($rs->num_rows) {
   
      $x=0;
      while($field=$rs->fetch_object()) {
        
        $areas[$x]->ID=$field->ID;
        $areas[$x]->Code=$field->Code;
        $areas[$x]->Name=$field->Name;
        $areas[$x]->AreaLevelID=$field->AreaLevelID;
        $areas[$x]->ParentID=$field->ParentID;
        
        $x++;                
      }
    }
    return $areas;
  }
  
  // function load_areas_under_me($area_id, $area_level_id)    
  function load_areas_under_me($area_id, $area_level_id) {
   
    global $database;
    
    $query="SELECT * FROM area WHERE ParentID='".$area_id."' AND AreaLevelID='".$area_level_id."' AND StatusID='1' ORDER BY Name";
    $rs=$database->execute($query);
   
    if($rs->num_rows) {
   
      $x=0;
      while($field=$rs->fetch_object()) {
        
        $areas_under_me[$x]->ID=$field->ID;
        $areas_under_me[$x]->Code=$field->Code;
        $areas_under_me[$x]->Name=$field->Name;
        $areas_under_me[$x]->AreaLevelID=$field->AreaLevelID;
        $areas_under_me[$x]->ParentID=$field->ParentID;
        
        $x++;                
      }
    }
    return $areas_under_me;
  }        
}

$dev1_cm=new Developer1_Class_Methods();


  