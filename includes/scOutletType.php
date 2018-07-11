<?php
	/*if(ini_get('display_errors')){
		ini_set('display_errors',1);
	}*/
	global $database;
	$otID=0;
	$code="";
	$name="";	
	$msg="";
	$otSearchTxt="";	
	
	//print_r($_POST)."<br/>";
	//print_r($_GET)."<br/>";

	
	if(isset($_POST['btnSearch']))
	{
		
		$otSearchTxt = addslashes($_POST['searchTxtFld']);	
		$rs_outlettype = $sp->spSelectOutletType($database,-1,$otSearchTxt);
	}	
	elseif(isset($_GET['searchedTxt']))
	{
		$otSearchTxt = addslashes($_GET['searchedTxt']);	
		$rs_outlettype = $sp->spSelectOutletType($database,-1,$otSearchTxt);
	}
	else
	{
		$rs_outlettype = $sp->spSelectOutletType($database,0,"");
	}

	if(isset($_GET['otID'])){
		$otID=$_GET['otID'];		
		
		$rs_outlettypedetails =  $sp->spSelectOutletType($database,$otID,"");		
		
		if($rs_outlettypedetails->num_rows){
			
			while($row = $rs_outlettypedetails->fetch_object())
			{
				$code=$row->Code;
				$name=$row->Name;									
			}
			
			$rs_outlettypedetails->close();
		}
	}
	
?>