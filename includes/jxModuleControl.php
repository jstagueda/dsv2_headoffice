<?php



  require_once "../initialize.php";
  
 $modid = $_GET["modid"];
 $subid = $_GET["subid"];
 $mcid = $_GET["mcid"];
 
 
 $rs_suball = $sp->spSelectModControlDetails($subid,0);

  
 ?>
  <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                           
 <?php 
  if ($rs_suball->num_rows){
	  $b = 0;
	 while ($row = $rs_suball->fetch_object())  {
		$b++;
		($b % 2) ? $class = "" : $class = "bgEFF0EB";
		
		echo "<tr align=\"center\" class=\"$class\">
		         	<td height=\"20\" align=\"left\" class=\"borderBR\">
				  		<a href='index.php?pageid=13&mcid=$row->ID&subid=$subid&modid=$row->ModuleID' class='txtnavgreenlink'>$row->Name
					</td>
        	  </tr>";
	 }
  }
 ?>
    
  </table>
