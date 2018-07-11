<?php

	require_once "../initialize.php";
	
	
	$vSearch = $_POST['txtSearchProdCode'];

	$rsGetProductList = $sp->spSelectProductListLocation($database,$vSearch);
	
	if($rsGetProductList->num_rows)
	{
		echo "<ul>";
		$cnt = 0;
		while($row = $rsGetProductList->fetch_object())
		{
			$cnt ++ ;
		
				if($cnt != 21)
				{
				echo "<li id='$row->prodCode'><div align='left'><strong>$row->prodCode - $row->prodName</strong></div></li> ";
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
