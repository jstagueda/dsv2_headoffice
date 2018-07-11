<?php

	require_once "../initialize.php";
	
	$index = $_GET['index'];
	$vSearch = $_POST['txtProdCode'.$index];

	$rsGetProductList = $sp->spSelectProductListByLevelIDSO($database,3, $vSearch);
	
	if($rsGetProductList->num_rows)
	{
		echo "<ul>";
		$cnt = 0;
		while($row = $rsGetProductList->fetch_object())
		{
			$cnt ++ ;
		
				if($cnt != 21)
				{
				echo "<li id='$row->Code'><div align='left'><strong>$row->Code - $row->Name</strong></div></li> ";
				}
				else
				{
				echo "<li id='0'><div align='left'><strong>Refine Search</strong></div></li> ";	
				}
		}
		
		if($cnt == 0)
			{
				echo "<li id='0'><div align='left'><strong>No records found.</strong></div></li> ";
			}
		echo "</ul>";
	}
	else
	{
		echo "<ul>";			
		echo "<li id='0'><div align='left'><strong>No records found.</strong></div></li> ";			
		echo "</ul>";
	}
?>
