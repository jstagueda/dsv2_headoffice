<?php 
	include "../../../initialize.php";
	
	if(isset($_POST['action'])){
		
		if($_POST['action'] == "getBranch"){
			
			$query = $database->execute("SELECT * FROM branch 
											WHERE TRIM(`Code`) LIKE '".$_POST['branch']."%'
											OR `Name` LIKE '".$_POST['branch']."%'
											LIMIT 10");
			
			if($query->num_rows){
				
				while($res = $query->fetch_object()){
					$result[] = array("Label" => TRIM($res->Code)." - ".$res->Name, "Value" => TRIM($res->Code)." - ".$res->Name, "ID" => $res->ID);
				}
				
			}else{
				
				$result[] = array("Label" => "No result found.", "Value" => "", "ID" => 0);
				
			}
			
			die(json_encode($result));
			
		}
		
		//=====================================================
		
		if($_POST['action'] == "getIBM"){
			
			$query = $database->execute("SELECT c.* FROM customer c 
										INNER JOIN sfm_level sf ON sf.codeID = c.CustomerTypeID 
										WHERE sf.has_downline = 1 
										AND (TRIM(c.Code) LIKE '".$_POST['IBM']."%' OR c.Name LIKE '".$_POST['IBM']."%')
										AND SPLIT_STR(c.HOGeneralID, '-', 2) = ".$_POST['branch']."										
										LIMIT 10");
										
			if($query->num_rows){
				
				while($res = $query->fetch_object()){
					$result[] = array("Label" => TRIM($res->Code)." - ".$res->Name, "Value" => TRIM($res->Code)." - ".$res->Name, "ID" => $res->ID);
				}
				
			}else{
				
				$result[] = array("Label" => "No result found.", "Value" => "", "ID" => 0);
				
			}
			
			die(json_encode($result));
		
		}		
		
		//=========================================================
		
		if($_POST['action'] == "getCustomerList"){
			
			$query = $database->execute("SELECT 
									c.ID CustomerID,
									TRIM(c.Code) CustomerCode,
									c.Name CustomerName,
									s.Name CustomerStatus,
									DATE_FORMAT(c.EnrollmentDate, '%m/%d/%Y') RegisteredDate
									FROM customer c 
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm 
											WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
									INNER JOIN tpi_rcustomerstatus rcs ON rcs.CustomerID = c.ID
									AND LOCATE(CONCAT('-', b.ID), rcs.HOGeneralID) > 0
									AND rcs.ID = (SELECT MAX(ID) FROM tpi_rcustomerstatus 
										WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
									INNER JOIN `status` s ON s.ID = rcs.CustomerStatusID
									WHERE b.ID = ".$_POST['branch']."
									AND ribm.IBMID = ".$_POST['IBM']."
									AND s.ID = 4");
			
			echo '<table cellspacing="0" cellpadding="0" width="100%">
					<tr class="trheader">
						<td width="10px">
							<input type="checkbox" name="checkAll" value="1" style="margin:0;" onchange="return checkedAll(this);">
						</td>
						<td width="15%">Code</td>
						<td>Name</td>
						<td width="15%">Status</td>
						<td width="15%">Date Registered</td>
					</tr>';
	
			if($query->num_rows){
				while($res = $query->fetch_object()){
					echo '<tr class="trlist">
							<td align="center">
								<input type="checkbox" value="'.$res->CustomerID.'" name="CustomerID[]" style="margin:0;" class="CustomerIDField">
							</td>
							<td align="center">'.$res->CustomerCode.'</td>
							<td>'.$res->CustomerName.'</td>
							<td align="center">'.$res->CustomerStatus.'</td>
							<td align="center">'.$res->RegisteredDate.'</td>
						</tr>';
				}
			}else{
				echo '<tr class="trlist">
								<td colspan="5" align="center">No result found.</td>
							</tr>';
			}
			
			echo '</table>';
			
			die();
		}
		
		
	}
	
?>