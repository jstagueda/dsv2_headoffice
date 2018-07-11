 <?php

 	global $database;
	$search = "";
	$prodLineID = 0;
	
	/*if(isset($_GET['pline']))
	{
		if($_GET['pline'] == "")
		{
			$prodLineID = 0;			
		}
		else
		{
			$prodLineID = $prodLineID = $_GET['pline'];
		}
		$rs_productList = $sp->spSelectProductListInsPopup($database, $prodLineID, $search);
	}*/
	
	if(isset($_POST['btnSearch']))
	{
		/*if($_GET['pline'] == "")
		{
			$prodLineID = 0;			
		}
		else
		{
			$prodLineID = $prodLineID = $_GET['pline'];
		}*/
		$search = htmlentities(addslashes($_POST['txtSearch']));	
		$rs_productList = $sp->spSelectProductListInsPopup($database, $prodLineID, $search);			
	}
	else
	{
		$rs_productList = $sp->spSelectProductListInsPopup($database, -1, $search);
	}
 	
 	$rs_productType = $sp->spSelectProductLine($database,2);
	
 ?>