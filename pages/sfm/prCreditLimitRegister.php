<style>
	.pageset{
		margin-bottom : 20px;
		font-family : arial;
		font-size : 12px;
	}
	
	table{
		border-collapse : collapse;
		font-family : arial;
		font-size : 12px;
	}
	
	.trheader td{
		padding : 5px;
		font-weight : bold;
		text-align : center;
	}
	
	.trlist td{
		padding : 5px;
	}
	
	@page{margin : 0.5in 0;}
	
	@media print{
		.pageset{
			page-break-after : always;
			margin : 0;
		}
	}
	
	.maintitle{
		font-family: arial;
		font-size: 18px;
		font-weight: bold;
		text-align: center;
	}
	
	.separator{
		font-weight: bold;
		text-align: center;
		width: 5%;
	}
	
	.fieldlabel {
		font-weight: bold;
		text-align: right;
		width: 100px;
	}
	
	.field {
		width: 200px;;
	}
</style>

<?php 
	
	include "../../initialize.php";
	include IN_PATH."scCreditLimitRegister.php";
	
	$employee = $database->execute("SELECT LastName, FirstName FROM employee WHERE ID = ".$_SESSION['emp_id']."");
	$emp = $employee->fetch_object();
	
	$branchdetails = $database->execute("SELECT CONCAT(TRIM(Code), ' - ', Name) BranchName FROM branch WHERE ID = ".$_GET['branchID']."");
	$branch = $branchdetails->fetch_object();
	
	echo "<div class='maintitle'>Credit Limit Register</div>
		<div style='margin: 10px 0;'>
			<table width='100%'>
				<tr>
					<td class='fieldlabel'>Date From</td>
					<td class='separator'>:</td>
					<td class='field'>".$_GET['datefrom']."</td>
					
					<td class='divider'></td>
					
					<td class='fieldlabel'>Run Date</td>
					<td class='separator'>:</td>
					<td class='field'>".date("m/d/Y h:i:s A")."</td>
				</tr>
				<tr>
					<td class='fieldlabel'>Date To</td>
					<td class='separator'>:</td>
					<td class='field'>".$_GET['dateto']."</td>
					
					<td class='divider'></td>
					
					<td class='fieldlabel'>Run By</td>
					<td class='separator'>:</td>
					<td class='field'>".$emp->FirstName." ".$emp->LastName."</td>
				</tr>
				<tr>
					<td class='fieldlabel'>Branch</td>
					<td class='separator'>:</td>
					<td class='field'>".$branch->BranchName."</td>
					
					<td class='divider'></td>
					
					<td class='fieldlabel'></td>
					<td class='separator'></td>
					<td class='field'></td>
				</tr>
			</table>			
		</div>";
	
	$header = '<div class="pageset"><table cellpadding="0" cellspacing="0" width="100%" border="1">
			<tr class="trheader">
				<td>Sales Force Code</td>
				<td>Sales Force Name</td>
				<td>Credit Limit</td>
				<td>Date Update</td>
			</tr>';
	
	$footer = "</table></div>";
	
	$page = $_GET['page'];
	$sfmlevel = $_GET['sfmlevel'];
	$sfmfrom = $_GET['sfmfromhidden'];
	$sfmto = $_GET['sfmtohidden'];
	$datefrom = date("Y-m-d", strtotime($_GET['datefrom']));
	$dateto = date("Y-m-d", strtotime($_GET['dateto']));
	$branch = $_GET['branchID'];
	$total = 10;
	$row = 20;
	$counter = 1;
	$count = 1;
	$pagerecord = 1;
	
	$creditlimitTotal = CreditLimitRegister($branch, $sfmlevel, $sfmfrom, $sfmto, $datefrom, $dateto, $page, $total, true);
	
	if($creditlimitTotal->num_rows){
		
		while($res = $creditlimitTotal->fetch_object()){
			
			if($pagerecord == 1){
				$row = 18;
			}else{
				$row = 20;
			}
			
			if($counter == 1){
				echo $header;				
			}
			
			echo '<tr class="trlist">
					<td align="center" width="20%">'.$res->CustomerCode.'</td>
					<td align="left">'.$res->CustomerName.'</td>
					<td align="right" width="20%">'.number_format($res->CreditLimit, 2).'</td>
					<td align="center" width="20%">'.$res->DateUpdate.'</td>
				</tr>';
			
			if($row == $counter){
				echo $footer;
				$pagerecord++;
				$counter = 0;
			}else{
				if($count == $creditlimitTotal->num_rows){
					echo $footer;
					$counter = 0;
				}
			}
			
			$counter++;
			$count++;
			
		}
		
	}else{
		echo $header;
		echo '<tr class="trlist">
				<td colspan="4" align="center">No result found.</td>
			</tr>';
		echo $footer;
	}
	
?>