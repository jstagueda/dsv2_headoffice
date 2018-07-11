<?php
global $database;

//$code = $_GET['search'];
$bcode="";
$bname="";
$rname="";
$frmdate = $_GET['frmdte'];
$frmdate2 = date("Y-m-d", strtotime( $_GET['frmdte']));
$todate = $_GET['todte'];
$todate2 = date("Y-m-d", strtotime( $_GET['todte']));
$branch = $_GET['branch'];
	
// $queryBranch = $sp->spSelectBranchbyBranchParameter($database);
$queryBranch = $database->execute("SELECT * FROM branch WHERE ID = ".$branch);
	  if ($queryBranch->num_rows)
		 {
			while ($rowB =  $queryBranch->fetch_object())
					{
						$bname = $rowB->Name;
						$btin = $rowB->TIN;
						$bsn = $rowB->ServerSN;			    
					}
		  }		
?>