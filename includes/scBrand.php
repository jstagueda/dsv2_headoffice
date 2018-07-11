<?php
	/*if(ini_get('display_errors')){
		ini_set('display_errors',1);
	}*/
	
	$bID=0;
	$code="";
	$name="";
	$isvan=0;
	$desc="";
	$msg="";
	$bSearchTxt="";	
	
	//print_r($_POST)."<br/>";
	//print_r($_GET)."<br/>";

	
if(isset($_POST['btnSearch']))
	{
		
		$bSearchTxt = addslashes($_POST['searchTxtFld']);	
		$rs_brand = $sp->spSelectBrand(-1,$bSearchTxt);
	}	
	
		
	elseif(isset($_GET['searchedTxt']))
	{
		$bSearchTxt = addslashes($_GET['searchedTxt']);	
		$rs_brand = $sp->spSelectBrand(-1,$bSearchTxt);
	}
	
	else
	{
		$rs_brand = $sp->spSelectBrand(0,"");
	}

	if(isset($_GET['bID'])){
		$bID=$_GET['bID'];		
		
		$rs_branddetails =  $sp->spSelectBrand($bID,"");		
		
		if($rs_branddetails->num_rows){
			
			while($row = $rs_branddetails->fetch_object())
			{
				$id=$row->ID;
				$code=$row->Code;
				$name=$row->Name;
				$desc=$row->Description;						
			}
			
			$rs_branddetails->close();
		}
	}
	
?>