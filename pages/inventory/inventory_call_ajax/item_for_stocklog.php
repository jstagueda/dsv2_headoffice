<?php
include '../../../initialize.php';
global $database;

	$q = $database->execute("select distinct p.Code, p.Name Product
							  from product p
							  INNER JOIN (SELECT ID FROM product WHERE ProductLevelID = 3) pl ON p.ID = pl.ID
							  inner join producttype ptt on ptt.ID = 3
							  left join inventory i on i.ProductID = p.ID
							  left join productdetails pd on pd.ProductID = p.ID
							  left join value v on v.ID = pd.ValueID
							  left join productpricing pp on pp.ProductID = p.ID
							  left join unittype u on u.ID = p.UnitTypeID
							  left join warehouse w on w.ID = i.WarehouseID and w.StatusID = 1
							  left join status s on s.`ID` = p.`StatusID`
							  left join tpi_pmg tpmg on tpmg.`ID`= pp.`PMGID`
							  left join salesinvoicedetails siDetails on siDetails.`ProductID` = p.ID
							  left join salesinvoice si on si.`ID` = siDetails.`SalesInvoiceID`
							  left join brochureproduct bp on bp.ProductID = p.ID
							  where p.Code like '%".$_GET['term']."%' or p.Name like '%".$_GET['term']."%' and w.ID = ".$_GET['wid']." LIMIT 10");

	if($q->num_rows){
			while($row =  $q->fetch_array(MYSQLI_ASSOC))
			{	//Code, Name
				$results[] = array('ProductCode' => $row['Code'], 'ProductName' => $row['Product']);
			}
			echo json_encode($results);
		}else{
			echo json_encode(array("ProductCode" => "No Record(s) Displayed.", 'ProductName' => ''));
	}
?>