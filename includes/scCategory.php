<?php
	/*if(ini_get('display_errors')){
		ini_set('display_errors',1);
	}*/
	
	$cid=0;
	$code="";
	$name="";
	$isvan=0;
	$desc="";
	$msg="";
	$cSearchTxt="";	
	
	//print_r($_POST)."<br/>";
	//print_r($_GET)."<br/>";

	global $database;
if(isset($_POST['btnSearch']))
	{
		
		$cSearchTxt = addslashes($_POST['searchTxtFld']);			
		
		$rs_category = $sp->spSelectCategory($database, -1,$cSearchTxt);
	}	
	
		
	elseif(isset($_GET['searchedTxt']))
	{
		$cSearchTxt = addslashes($_GET['searchedTxt']);	
		$rs_category = $sp->spSelectCategory($database,-1,$cSearchTxt);
	}
	
	else
	{
		$rs_category = $sp->spSelectCategory($database,0,"");
	}


			
			
	if(isset($_GET['cid'])){
		$cid=$_GET['cid'];
		
		
		$rs_categorydetails =  $sp->spSelectCategory($database,$cid,"");
		
		
		if($rs_categorydetails->num_rows){
			
			while($row = $rs_categorydetails->fetch_object())
			{
				$code=$row->Code;
				$name=$row->Name;
				$desc=$row->Description;						
			}
			
			$rs_categorydetails->close();
		}
		
	}
			
?>