<?php 	

require_once("../initialize.php");
global $database;

$search = $_GET['term'];
	$q = $database->execute("SELECT DISTINCT
								p.ID ProductID,
								p.Code ProductCode,
								p.Name Product,
								p.`ProductLevelID`,
								p.`ParentID`
							 FROM product p
								LEFT JOIN productdetails pd ON pd.ProductID = p.ID
								LEFT JOIN VALUE v ON v.ID = pd.ValueID
							 WHERE  (p.Code LIKE '%".$search."%' OR p.Name LIKE '%".$search."%' OR v.Name LIKE '%".$search."%' OR pd.Details LIKE '%".$search."%')
							 AND p.ProductLevelID = 3 AND p.ProductTypeID IN (1,3)
							 ORDER BY p.Name DESC
							 LIMIT 10");
							 
		if($q->num_rows){
			while($r = $q->fetch_array(MYSQLI_ASSOC)){
				$results[] = array('Code'=>$r['ProductCode'], 'Description' => $r['Product']);
			}	
		}else{
			$results[] = array('Code'=>'No Result(s) Display', 'Description' => 'No Result(s) Display');
		}
			 
		die(tpi_JSONencode($results));
?>