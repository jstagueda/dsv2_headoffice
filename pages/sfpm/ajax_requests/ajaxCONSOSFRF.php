<?php
include "../../../initialize.php";
include IN_PATH.DS."scCONSOSFRF.php";


if(isset($_POST['searchbranch'])){
	
	$branchquery = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (`Name` LIKE '".$_POST['searchbranch']."%' OR `Code` LIKE '".$_POST['searchbranch']."%')");
	if($branchquery->num_rows){
		while($res = $branchquery->fetch_object()){
			$result[] = array("Label" => trim($res->Code)." - ".$res->Name,
							"Value" => trim($res->Code)." - ".$res->Name,
							"ID" => $res->ID);
		}
	}else{
		$result[] = array("Label" => "No result found.",
						"Value" => "",
						"ID" => 0);
	}
	
	die(json_encode($result));
}

if(isset($_POST['searched'])){
    $ibmrange = ibmrange($database, $_POST['searched'], $_POST['branch']);
    if($ibmrange->num_rows){
        while($res = $ibmrange->fetch_object()){
            $result[] = array("ID" => $res->ID, "Name" => TRIM($res->Code)." - ".$res->Name, "Value" => TRIM($res->Code)." - ".$res->Name);
        }
    }else{
        $result[] = array("ID" => 0, "Name" => "No result found.", "Value" => "");
    }
    
    die(tpi_JSONencode($result));
}

if(isset($_POST['page']))
{
    
    $ibmfrom     = (isset($_POST['IBMfromHidden']))?$_POST['IBMfromHidden']:0;
    $ibmto       = (isset($_POST['IBMtoHidden']))?$_POST['IBMtoHidden']:0;
    $status      = (isset($_POST['status']))?$_POST['status']:0;
    $page        = (isset($_POST['page']))?$_POST['page']:1;
    $pageibm     = (isset($_POST['pageibm']))?$_POST['pageibm']:1;
	$branch      = $_POST['branch'];
	$cmp         = $_POST['Campaign'];
    $total       = 10;
	$affiliation = $_POST['summarydetail'];
	
	
    $getNetwork = getconsolidatednetwork($database, $ibmfrom, $ibmto, $status, false, $page, $total, $branch,$cmp);
    #$getNetworkTotal = getconsolidatednetwork($database, $ibmfrom, $ibmto, $status, true, $page, $total, $branch);  
	
	$totalamount = 0;
	
    echo '<table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">';
    echo '<tr class="tablelisttr">
	             <td>CTR</td>  
	             <td>BRANCH</td>
                 <td>NATIONAL ID</td>
                 <td>ACCOUNT CODE</td>
                 <td>ACCOUNT NAME</td>
				 <td>M-IBM</td>
                 <td>LEVEL</td>
                 <td>STATUS</td>
				 <td>AMOUNT</td>
            </tr>';
			
        $ctr = 0;
        if($getNetwork->num_rows)
		{
            while($res = $getNetwork->fetch_object() )
			{
				$HOGeneralID = $res->HOGeneralID;
				$ctr = $ctr + 1;
				$totalamount = $totalamount + $res->Amount;
				echo '<tr class="listtr">';
				echo '<td align="right">'.$ctr.'.</td>';
				echo '<td align="center">'.$res->Branch.'</td>';
				echo '<td align="center">'.$res->HOGeneralID.'</td>';
				echo '<td align="left">'.$res->ccode.'</td>';
				echo '<td align="left">'.$res->cname.'</td>';
				echo '<td align="left">'.$res->manager_code.'</td>';
				echo '<td align="center">'.$res->desc_code.'</td>';
				echo '<td align="left">'.$res->statuscode.'</td>';
				echo '<td align="left">'.$res->Amount.'</td>';
				
				echo '</tr>';
			}
			
			    echo '<tr class="listtr">';
				echo '<td align="right" colspan = 8>Total:</td>';
				echo '<td align="left">'.$totalamount.'</td>';
				
				echo '</tr>';
			
        }else
		{
            echo '<tr class="listtr">
                <td align="center" colspan="6">No result found.</td>
            </tr>';
        }
		
        echo "</table>";
        echo "<div style='margin-top:10px;' class='ibmfield'>".AddPagination($total, $getNetworkTotal->num_rows, $pageibm, 'IBM')."</div>";
        
    
}
?>
