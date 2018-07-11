<?php
	/*if(ini_get('display_errors')){
		ini_set('display_errors',1);
	}*/
	global $database;
	$ptID=0;
	$code="";
	$name="";
	$isvan=0;
	$desc="";
	$msg="";
	$ptSearchTxt="";	
	
	//print_r($_POST)."<br/>";
	//print_r($_GET)."<br/>";

	
if(isset($_POST['btnSearch']))
	{
		
		$ptSearchTxt = addslashes($_POST['txtfldsearch']);	
		$rs_productType = $sp->spSelectProdType($database, -1,$ptSearchTxt);
	}	
	
		
	elseif(isset($_GET['svalue']))
	{
		$ptSearchTxt = addslashes($_GET['svalue']);	
		$rs_productType = $sp->spSelectProdType($database,-1,$ptSearchTxt);
	}
	
	else
	{
		$rs_productType = $sp->spSelectProdType($database,0,"");
	}

	if(isset($_GET['ptID'])){
		$ptID=$_GET['ptID'];		
		
		$rs_ptDetails =  $sp->spSelectProdType($database,$ptID,"");		
		
		if($rs_ptDetails->num_rows){
			
			while($row = $rs_ptDetails->fetch_object())
			{
				$code=$row->Code;
				$name=$row->Name;
				/*$desc=$row->Description;*/						
			}
			
			$rs_ptDetails->close();
		}
	}
	
?>