<?php 
include "../../../../initialize.php";
include IN_PATH.DS."pagination.php";
global $database;

if($_POST['action'] == "pagination"){
	$result = array();
	$page = $_POST['page'];
	$total = 10;
	$start = ($page > 1)?(($page - 1) * $total):0;
	$PMGType = ($_POST['PMGType'] == 0)?" ":" and pmg.ID = ".$_POST['PMGType'];
	$Search = ($_POST['Search'] == "")?"":" and p.Code like '%".$_POST['Search']."%'";
	$html = "";
	
	$q = $database->execute("SELECT p.Code,p.Name, pmg.Code PMG,pp.UnitPrice,pp.EnrollmentDate FROM product p
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN tpi_pmg pmg ON pp.PMGID = pmg.ID
						WHERE pp.UnitPrice = 0 ".$PMGType." ".$Search." order by p.ID desc 
						LIMIT $start, $total");
						
	$num_rows = $database->execute("SELECT p.Code,p.Name, pmg.Code PMG,pp.UnitPrice,pp.EnrollmentDate FROM product p
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN tpi_pmg pmg ON pp.PMGID = pmg.ID
						WHERE pp.UnitPrice = 0 ".$PMGType." ".$Search." order by p.ID desc")->num_rows;
						
	$html .= "<tr class='trheader'>
						<td>Product Code</td>
						<td>Product Name</td>
						<td>PMG</td>						
						<td>Unit Price</td>
						<td>Date Enrolled</td>
		</tr>";
	if($q->num_rows > 0){
		while($r = $q->fetch_object()){
			$Code = $r->Code;
			$Name = $r->Name;
			$PMG = $r->PMG;
			$Price = number_format($r->UnitPrice,2);
			$Enrollment = $r->EnrollmentDate;
			
			$html .= "<tr class='trlist'>
						<td>".$Code."</td>
						<td>".$Name."</td>
						<td>".$PMG."</td>						
						<td>".$Price."</td>
						<td>".$Enrollment."</td>
					</tr>";
		}
	}else{
		$html .= "<tr class='trlist'><td colspan='9' align='center'>No result found.</td></tr>";
	}
	$result['data_handler'] = $html;
	$result['num'] = $num_rows;
	$result['RPP'] = $total;
	
	die(json_encode($result));
}

if(isset($_GET['term'])){
	$Search = ($_GET['term'] == "")?"":" and p.Code like '%".$_GET['term']."%'";
	
	$q = $database->execute("SELECT p.Code,p.Name, pmg.Code PMG,pp.UnitPrice,pp.EnrollmentDate FROM product p
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN tpi_pmg pmg ON pp.PMGID = pmg.ID
						WHERE pp.UnitPrice = 0 ".$Search."  order by p.ID desc LIMIT 10");
	if($q->num_rows){
			while($r = $q->fetch_array(MYSQLI_ASSOC)){
				$results[] = array('Code'=>$r['Code'], 'Description' => $r['Name']);
			}	
	}else{
		$results[] = array('Code'=>'No Result(s) Display', 'Description' => 'No Result(s) Display');
	}
	die(json_encode($results));
}

?>