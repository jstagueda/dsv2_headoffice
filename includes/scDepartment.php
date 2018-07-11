<?php
	global $database;
	/*if(ini_get('display_errors')){
		ini_set('display_errors',1);
	}*/

	$dID=0;
	$code="";
	$name="";
	$isvan=0;
	$desc="";
	$msg="";
	$dSearchTxt="";	
	
	//print_r($_POST)."<br/>";
	//print_r($_GET)."<br/>";
	
	if(isset($_POST['btnSearch']))
	{		
		$dSearchTxt = addslashes($_POST['searchTxtFld']);					
		$rs_department = $sp->spSelectDepartment($database, -1,$dSearchTxt);
	}	
	
		
	elseif(isset($_GET['searchedTxt']))
	{     
		$dSearchTxt = addslashes($_GET['searchedTxt']);	
		$rs_department = $sp->spSelectDepartment($database, -1,$dSearchTxt);
	}
	
	else
	{  
            //modified by Gino Leabres :)
		$rs_department = $sp->spSelectDepartment($database, -1,"");	
	}
			
	if(isset($_GET['dID'])){
		$dID=$_GET['dID'];
				
		$rs_departmentDetails =  $sp->spSelectDepartment($database, $dID,"");
		
		
		if($rs_departmentDetails->num_rows){
			
			while($row = $rs_departmentDetails->fetch_object())
			{
				$code=$row->Code;
				$name=$row->Name;							
			}
			
			$rs_departmentDetails->close();
		}
	}
	
?>