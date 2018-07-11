<?php
	    include('../initialize.php');
		include('../class/config.php');
		global $mysqli;
		
		$result = $mysqli->query("SELECT a.Code, a.Name FROM product a
								  INNER JOIN inventory i ON i.ProductID = a.ID
								  INNER JOIN status b ON a.StatusID = b.ID
								  WHERE b.ID <> 18 AND b.id <> 19 AND i.SOH > 0
								  AND a.Code LIKE '%".$_GET['term']."%' OR a.Name LIKE '%".$_GET['term']."%'
								  GROUP BY a.ID LIMIT 10");
								  

					  
		
		if($result->num_rows){
			while($row =  $result->fetch_array(MYSQLI_ASSOC))
			{
				//Code, Name
				$results[] = array('ProductCode' => $row['Code'], 'ProductName' => $row['Name']);
			}
			echo json_encode($results);
		}else{
			echo json_encode(array("ProductCode" => "No Record(s) Displayed.", 'ProductName' => ''));
		}
	
?>
	 