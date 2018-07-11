<?php

   require_once "../initialize.php";
   global $database;
   $vSearch = $_POST['txtComponent'];

   $rsGetComponent = $sp->spSelectProductComponentAjax($database,$vSearch);

   if($rsGetComponent->num_rows){
        $cnt = 0 ;
        echo "<ul> ";
        while($row = $rsGetComponent->fetch_object()){
            $cnt ++ ;
            $component =  $row->ID.'_'.$row->Name.'_'.$row->Code;
            if($cnt != 16){
                echo "<li id=\"". str_replace('"','',$component) . "\"><div align='left'><strong>$row->Code - $row->Name </strong></div></li> ";
            }
        }
        echo "</ul>";
   }else{
        echo "<ul>";
        $component =  '0__';
        echo "<li id='$component'><div align='left'><strong>No records found.</strong></div></li> ";
        echo "</ul>";
   }
?>
