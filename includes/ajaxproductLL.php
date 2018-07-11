<?php
	    include('../initialize.php');
		include('../class/config.php');
		global $mysqli;

			$result = $mysqli->query("SELECT a.Code, a.Name FROM product a
									  INNER JOIN productpricing b ON a.ID = b.ProductID
									  WHERE a.ProductLevelID = 3 and a.Code LIKE '%".$_GET['term']."%' or a.Name like '%".$_GET['term']."%'
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
	 