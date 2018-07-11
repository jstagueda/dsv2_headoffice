<style>
    @page{margin: 0.5in 0; size:landscape;}
    body{font-family: arial;}
    .pageset{margin-bottom:20px;}
    .pageset table{font-size:12px; border-collapse: collapse;}
    .pageset table td{padding:3px;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>
<?php
	include "../../../initialize.php";
	include IN_PATH.DS."pagination.php";
	global $database;
	
	$PMGType = ($_GET['PMG'] == 0)?" ":" and pmg.ID = ".$_GET['PMG'];
	$Search = ($_GET['Search'] == "")?"":" and p.Code like '%".$_GET['Search']."%'";
	$q = $database->execute("SELECT p.Code,p.Name, pmg.Code PMG,pp.UnitPrice,pp.EnrollmentDate FROM product p
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN tpi_pmg pmg ON pp.PMGID = pmg.ID
						WHERE pp.UnitPrice = 0 ".$PMGType." ".$Search);
	

	$branchname = $database->execute("select b.Code from branchparameter param inner join branch b on b.ID = param.BranchID")->fetch_object()->Code;
	echo "<div style='font-size:16px; position:absolute; top:10px; width:100%; text-align:center;'>
				<b>Zero CSP Report</b>
			</div>";
			
	echo "<table border = 0 width='100%' style='font-size:12px;'>
			<tr>
				<td>
					<b><div style='width:80px; float:left;'>Branch</div> : </b> ". $branchname ."
				</td>				
				<td width='25%'>
					<b><div style='width:80px; float:left;'>Run Date</div> : </b> ".date('m/d/Y G:i')."
				</td>
			</tr>
			<tr>
				<td>
					<b><div style='width:80px; float:left;'>Run By</div> : </b> ".$database->execute("SELECT UserName FROM `user` WHERE ID =".$_SESSION['user_id'])->fetch_object()->UserName."
				</td>
			</tr>";
		echo "</table>";
		
	echo "<br />";
	

	
	$html.='<div class="pageset"><table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">';
	$html.='<tr class="trheader">';
	$html.='	<th>Product Code</th>';
	$html.='	<th>Product Name</th>';
	$html.='	<th>PMG</th>';						
	$html.='	<th>Unit Price</th>';
	$html.='	<th>Date Enrolled</th>';
	$html.='</tr>';					
	
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
	$html.='';
	echo $html."</table></div>";
?>