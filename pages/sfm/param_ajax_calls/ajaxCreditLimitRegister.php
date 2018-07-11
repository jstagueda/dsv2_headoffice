<?php 

include "../../../initialize.php";
include IN_PATH."pagination.php";
include IN_PATH."scCreditLimitRegister.php";

if(isset($_POST['action'])){
	
	if($_POST['action'] == "ShowCreditLimit"){
		
		echo '<table cellpadding="0" cellspacing="0" width="100%" class="bordersolo" style="border-top:none;">
				<tr class="trheader">
					<td>Sales Force Code</td>
					<td>Sales Force Name</td>
					<td>Credit Limit</td>
					<td>Date Update</td>
				</tr>';
		
		$page = $_POST['page'];
		$sfmlevel = $_POST['sfmlevel'];
		$sfmfrom = $_POST['sfmfromhidden'];
		$sfmto = $_POST['sfmtohidden'];
		$datefrom = date("Y-m-d", strtotime($_POST['datefrom']));
		$dateto = date("Y-m-d", strtotime($_POST['dateto']));
		$branch = $_POST['branchID'];
		$total = 10;
		
		$creditlimit = CreditLimitRegister($branch, $sfmlevel, $sfmfrom, $sfmto, $datefrom, $dateto, $page, $total, false);
		$creditlimitTotal = CreditLimitRegister($branch, $sfmlevel, $sfmfrom, $sfmto, $datefrom, $dateto, $page, $total, true);
		
		if($creditlimit->num_rows){
		
			while($res = $creditlimit->fetch_object()){
				
				echo '<tr class="trlist">
						<td align="center">'.$res->CustomerCode.'</td>
						<td align="left">'.$res->CustomerName.'</td>
						<td align="right">'.number_format($res->CreditLimit, 2).'</td>
						<td align="center">'.$res->DateUpdate.'</td>
					</tr>';
				
			}
			
		}else{
		
			echo '<tr class="trlist">
					<td colspan="4" align="center">No result found.</td>
				</tr>';

		}
		
		echo "</table>";
		echo "<div style='margin-top:10px;'>".AddPagination($total, $creditlimitTotal->num_rows, $page)."</div>";
		die();
	}
	
	
	if($_POST['action'] == "SearchedCustomer"){
		
		$query = $database->execute("SELECT c.ID, c.Code, c.Name FROM customer c
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
								WHERE (c.Code LIKE '%".$_POST['Customer']."%' OR c.Name LIKE '%".$_POST['Customer']."%')
								AND c.CustomerTypeID = ".$_POST['sfmlevel']."
								AND b.ID = ".$_POST['branchID']."
								ORDER BY c.Name
								LIMIT 10");
		
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => $res->Code." - ".$res->Name,
								"Value" => $res->Code." - ".$res->Name,
								"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found.",
								"Value" => "",
								"ID" => 0);
		}
		
		die(json_encode($result));
	}
	
	
	if($_POST['action'] == "GetBranch"){
		
		$branch = $database->execute("SELECT * FROM branch WHERE (`Code` LIKE '".$_POST['branch']."%' OR `Name` LIKE '".$_POST['branch']."%') LIMIT 10");
		
		if($branch->num_rows){
			
			while($res = $branch->fetch_object()){
				$result[] = array("Label" => TRIM($res->Code)." - ".$res->Name,
								"Value" => TRIM($res->Code)." - ".$res->Name,
								"ID" => $res->ID);
			}
			
		}else{
			
			$result[] = array("Label" => "No result found.",
							"Value" => "",
							"ID" => 0);
			
		}
		
		die(json_encode($result));
		
	}
	
}

?>