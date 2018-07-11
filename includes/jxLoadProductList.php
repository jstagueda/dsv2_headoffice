<?php
	require_once "../initialize.php";
   	global $database;

	$cnt = 0;
	$whid = $_GET['wid'];
	$rs_location = $sp->spSelectLocationWS($database, $whid);
	
	echo "<table width='100%'  border='0' cellpadding='0' cellspacing='0'>";
	if ($rs_location->num_rows)
	{
		$index = 0;
		while ($row_loc = $rs_location->fetch_object())
		{
			$index += 1;
			$rs_product = "rs_prod".$index;
			
			$rs_product = $sp->spSelectProductListForWorksheet($database, $whid);
			if ($rs_product->num_rows)
			{
				while ($row = $rs_product->fetch_object())
				{
					$cnt ++;
					($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
					$pname = $row->Product;
					$pid = $row->ProductID;
					$pcode = $row->ProductCode;
					
					echo "<tr align='center' class='$alt'>
								<td width='10%' align='center' height='20' class='borderBR'>$cnt</td>
								<td width='30%' height='20' align='left' class='borderBR padl5'>$pcode</td>
								<td width='30%' height='20' align='left' class='borderBR padl5'>$pname</td>
								<td width='30%' height='20' align='center' class='borderBR'>$row_loc->Name</td>
						 </tr>";						
				}
				$rs_product->close();				
			}
		}
		$rs_location->close();		
	}
	if ($cnt == 0)
	{
		echo "<tr><td height='20' colspan='4' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";
	}
	echo "</table>";
?>
