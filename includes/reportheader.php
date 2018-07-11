<?php 

function reportheader($title, $createdby, $branchname, $branchaddress, $havedaterange, $datefrom = '', $dateto = ''){
	
	echo "<div style='font-size:16px; position:absolute; top:10px; width:100%; text-align:center;'>
				<b>".$title."</b>
			</div>";
			
	echo "<table width='100%' style='font-size:12px;'>
			<tr>
				<td>
					<b><div style='width:80px; float:left;'>Branch</div> : </b> ". $branchname ."
				</td>				
				<td width='25%'>
					<b><div style='width:80px; float:left;'>Run Date</div> : </b> ".date('m/d/Y')."
				</td>
			</tr>
			<tr>
				<td><b><div style='width:80px; float:left;'>Address</div> : </b> ". $branchaddress ."</td>
				<td>
					<b><div style='width:80px; float:left;'>Created By</div> : </b> ".$createdby."
				</td>
			</tr>";
	if($havedaterange){
		echo "<tr>
				<td>
					<b><div style='width:80px; float:left;'>Date From</div> : </b> ". $datefrom ."
				</td>
				<td>
					<b><div style='width:80px; float:left;'>Date To</div> : </b> ". $dateto ."
				</td>
			</tr>";
	}
	echo "</table>";
}


function reportheader2($title, $createdby, $branchname, $branchaddress, $havedaterange, $datefrom = '', $dateto = ''){
	
	echo "<div style='font-size:16px; position:absolute; top:10px; width:100%; text-align:center;'>
				<b>".$title."</b>
			</div>";
			
	echo "<table width='100%' style='font-size:12px;'>
			<tr>
				<td>
					<b><div style='width:80px; float:left;'>Branch</div> : </b> ". $branchname ."
				</td>				
				<td width='25%'>
					<b><div style='width:80px; float:left;'>Run Date</div> : </b> ".date('m/d/Y G:i')."
				</td>
			</tr>
			<tr>
				<td><b><div style='width:80px; float:left;'>Date From</div> : </b> ". $datefrom ."</td>
				<td>
					<b><div style='width:80px; float:left;'>Run By</div> : </b> ".$createdby."
				</td>
			</tr>";
	echo "<tr>
			<td>
				<b><div style='width:80px; float:left;'>Date To</div> : </b> ". $dateto ."
			</td>
			<td></td>
		</tr>";
	echo "</table>";
}

if(isset($_GET['branch'])){
	$branchID = $_GET['branch'];
}else{
	$branchID = 0;
}

$branchquery = $database->execute("SELECT b.Code BranchCode, b.Name BranchName, b.StreetAdd FROM branch b WHERE b.ID = ".$branchID."");
$branch = $branchquery->fetch_object();

?>