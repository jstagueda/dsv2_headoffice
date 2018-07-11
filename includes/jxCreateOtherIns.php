<?php

	require_once "../initialize.php";
	global $database;	
	if  (isset($_GET["mod"]) && $_GET["mod"] == "w")
	{	
		$rs_warehouse = $sp->spSelectWarehouse(0,"");
		$rs_inventoryin = $sp->spSelectTmpInventoryInDetails($database, $session->emp_id, $session->createid);
		
		if($rs_inventoryin->num_rows) 
			$dis = "disabled";
		else 
			$dis = "";
			
		while($row = $rs_inventoryin->fetch_object())
		{
			$warehouseid = $row->WarehouseID;	break;
		}
		$rs_inventoryin->close(); 
		
		echo "<input type='hidden' name='hdnlstWarehouse' value='$warehouseid' />";
		echo "<select name='lstWarehouse' style='width:140px' class='txtfield' $dis>";
		echo "<option value='0' selected>ALL WAREHOUSE</option>";
		if ($rs_warehouse->num_rows)
		{
			while ($row = $rs_warehouse->fetch_object())	
			{
				if ($warehouseid == $row->ID) 
					$sel = "selected";
				else 
					$sel = "";				
				echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
			}
			$rs_warehouse->close();
		}
		echo "</select>";                              		
	}
	elseif  (isset($_GET["mod"]) && $_GET["mod"] == "t")
	{		
		$rs_product = $sp->spSelectProductList($_GET["srch"], $_GET["wrid"]);
		$rs_uom = $sp->spSelectUOM();
    
	   	echo "<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='bgFFFFFF'>";
	   
		if ($rs_product->num_rows)
		{
			$cnt = 0;
			while ($row = $rs_product->fetch_object())
			{
				$cnt ++;
				($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
				$pname = $row->Product;
				$pid = $row->ProductID;
				$pcode = $row->ProductCode;
				$invid = $row->InventoryID;
				$batch = $row->Batch;
				$soh = number_format($row->SOH,0);
				$unitcost = $row->UnitCost;
				$uomid = $row->UOMID;
				$uom = $row->UOM;
				$multi = $row->Multiplier;
				
				echo "<tr class='$alt'>".
					 "<td width='5%' align='center' class='borderBR'>$cnt</td>".
					 "<td width='30%' height='20' class='borderBR'>$pname".
					 "<input name='hdnInventoryID[]' type='hidden' value='$invid'></td>".
					 "<td width='16%' align='center' class='borderBR'>&nbsp;</td>".
					 "<td width='12%' align='center' class='borderBR'>$soh</td>".
					 "<td width='9%' align='center' class='borderBR'><span class='txt10'>".
					 "<select name='cboUOM[]' class='txtfield' style='width:100px;'>";
					  if ($rs_uom->num_rows)
					  {
						  while ($row_uom = $rs_uom->fetch_object())
						  {
							  $id = $row_uom->ID;
							  $uomname = $row_uom->Name;
							  ($id == $uomid) ? $sel = 'selected' : $sel = '';
							  echo "<option $sel value='$id'>$uomname</option>";
						  }
						  $rs_uom->data_seek(0);
					  }
				echo "</select>".
					 "</span></td>".
					 "<td width='12%' align='center' class='borderBR'>".
					 "<input name='txtquantity[]' type='text' class='txtfield3' size='12' maxlength='20' value='' ></td>".
					 "<td width='16%' align='center' class='borderB'>".
					 "<input name='txtreason[]' type='text' class='txtfield' value='' size='24' maxlength='50' ></td></tr>";
			}
			$rs_product->close();
			$rs_uom->close();
		} 
		echo "</table>";
	}	
	elseif (isset($_GET["mod"]) && $_GET["mod"] == "p")
	{	
		$rs_ii = $sp->spSelectTmpInventoryInDetails($database,$session->emp_id, $session->createid);
		
		echo "<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='bgFFFFFF'>";
  
	  
		$rowcount = 0;
		while ($row = $rs_ii->fetch_object())
		{
			$totqty = $totqty + $row->Qty;
			echo "<tr align=\"center\">
				   <td width=\"4%\"><input name=\"chkIID[]\" type=\"checkbox\" id=\"chkAll\" value=\"$row->InventoryID\"></td>
				   <td width=\"18%\">$row->Product&nbsp;</td>
				   <!-- <td width=\"8%\">$row->BookletNo&nbsp;</td> -->
				   <td width=\"10%\">".number_format($row->SOH, 0)."&nbsp;</td>
				   <td width=\"9%\">$row->UOM&nbsp;</td>
				   <td width=\"10%\">$row->Qty&nbsp;</td>
				   <td width=\"10%\">".number_format($row->UnitCost,2)."&nbsp;</td>
                   <td width=\"8%\">".number_format($row->Amount,2)."&nbsp;</td>
				   <td width=\"19%\">$row->Reason</td>
			  	</tr>";
		  	$rowcount++;
		}
		$rs_ii->close();
		echo "<input type='hidden' name='hdntotqty' value='$totqty' >";
		echo "</table>";                   

	}	
	elseif (isset($_POST["action"]) && $_POST["action"] == "Add")
	{ 
		$inv = explode(",", $_POST["hdnInventoryID"]);
		$qty = explode(",", $_POST["hdnQuantity"]);
		$rsn = explode(",", $_POST["hdnReason"]);
		$uom = explode(",", $_POST["hdnUOM"]);
		
		foreach ($inv as $key=>$IID)
		{
			if ($IID != "")
			{
				$uid 		 = $session->emp_id;
				$createid 	 = $session->createid;
				$inventoryid = $IID;
				$uomid		 = $uom[$key];
				$quantity 	 = $qty[$key];
				$reason		 = $rsn[$key];
				$sort		 = 1;
				$multiplier	 = 0; 
				$prodid_si	 = 0;
				$uomid_si	 = 0; 
				$unitprice_si = 0;
				$qty_si		 = 0;
				$affected_row = $sp->spInsertTmpInventoryInDetails($database,$uid, $createid, $inventoryid, $uomid, $quantity, $reason, $sort, $multiplier, $prodid_si, $uomid_si, $unitprice_si, $qty_si);
			}
		}
		
		echo "Successfully added Inventory In detail(s).";		
	}
	elseif (isset($_POST["action"]) && $_POST["action"] == "Remove")
	{
		$uid 		= $session->emp_id;
		$createid 	= $session->createid;
		$inv 		= explode(",", $_POST["hdnInventoryID"]);
				
		foreach ($inv as $key=>$IID)
		{
			$affected_row = $sp->spDeleteTmpInventoryInDetails($database, $uid, $createid, $IID);	
		}
			
        echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='bgFFFFFF'>";
		
		$rs_ii = $sp->spSelectTmpInventoryInDetails($database, $session->emp_id, $session->createid);
	  
		$rowcount = 0;
		while ($row = $rs_ii->fetch_object())
		{
            $totqty = $totqty + $row->Qty;
			echo "<tr align=\"center\">
				   <td width=\"4%\"><input name=\"chkIID[]\" type=\"checkbox\" id=\"chkAll\" value=\"$row->InventoryID\"></td>
				   <td width=\"18%\">$row->Product&nbsp;</td>
				   <td width=\"8%\">$row->BookletNo&nbsp;</td>
				   <td width=\"10%\">".number_format($row->SOH, 0)."&nbsp;</td>
				   <td width=\"9%\">$row->UOM&nbsp;</td>
				   <td width=\"10%\">$row->Qty&nbsp;</td>
				   <td width=\"10%\">".number_format($row->UnitCost,2)."&nbsp;</td>
                   <td width=\"8%\">".number_format($row->Amount,2)."&nbsp;</td>
				   <td width=\"19%\">$row->Reason</td>
			   </tr>";
		  	$rowcount++;
		} 
		$rs_ii->close();
            echo "<input type='hidden' name='hdntotqty' value='$totqty' >";
        echo "</table>";
	}
?>