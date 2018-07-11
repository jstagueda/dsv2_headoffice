<?php

include "../../../initialize.php";
include IN_PATH.DS."pagination.php";
include IN_PATH.DS."scIBMServiceFeeReport.php";

if(isset($_POST['searched'])){
	
    $ibmquery = $database->execute("SELECT c.* FROM customer c
                                    INNER JOIN sfm_level sl on sl.codeID = c.CustomerTypeID
                                    AND sl.has_downline = 1
                                    AND (c.Name LIKE '".$_POST['searched']."%' OR c.Code LIKE '".$_POST['searched']."%')
									AND LOCATE('-".$_POST['branch']."', c.HOGeneralID) > 0
                                    LIMIT 10");
    if($ibmquery->num_rows){
        while($res = $ibmquery->fetch_object()){
            $result[] = array("Label" => trim($res->Code)." - ".trim($res->Name),
                                "Value" => trim($res->Code)." - ".trim($res->Name),
                                "ID" => $res->ID);
        }
    }else{
        $result[] = array("Label" => "No result found.",
                            "Value" => "",
                            "ID" => $res->ID);
    }

    die(json_encode($result));
}

//==============================================================================

if(isset($_POST['searchbranch'])){
	
	$query = $database->execute("SELECT * FROM branch WHERE ID NOT IN(1,2,3)
								AND (Name LIKE '".$_POST['searchbranch']."%'
									OR Code LIKE '".$_POST['searchbranch']."%')
								LIMIT 10");
	
	if($query->num_rows){
		while($res = $query->fetch_object()){
			$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
								"Value" => trim($res->Code).' - '.$res->Name,
								"ID" => $res->ID);
		}
	}else{
		$result[] = array("Label" => "No result found.",
							"Value" => "",
							"ID" => 0);
	}
	
	die(json_encode($result));
}

//==============================================================================

if($_GET['action'] == "pagination"){

    $ibmfrom = $_POST['ibmfromHidden'];
    $ibmto = $_POST['ibmtoHidden'];
    $campaign = $_POST['campaign'];
    $page = $_POST['page'];
	$branch = $_POST['branch'];
    $total = 10;

    $ibmservicefee = IBMServiceFee($ibmfrom, $ibmto, $campaign, false, $page, $total, $branch);
    $ibmservicefeeCount = IBMServiceFee($ibmfrom, $ibmto, $campaign, true, $page, $total, $branch);

    $header = '<table class="tablelisttable" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FF00A6; border-top:none;">
                <tr class="tablelisttr">
                    <td>IBM/IGS Code</td>
                    <td>Name</td>
                    <td>Credit Term</td>
                    <td>PMG</td>
                    <td>Invoice Amount</td>
					<td>DGS Amount</td>
                    <td>Payments</td>
                    <td>Paid DGS</td>
                    <td>Service Fee%</td>
                    <td>Service Fee Amount</td>
                </tr>';
    $footer = "</table>";

    if($ibmservicefeeCount->num_rows){

        echo $header;
        while($res = $ibmservicefee->fetch_object()){
			
			echo "<tr class='listtr'>
                    <td>".trim($res->IBMCode)."</td>
                    <td>".$res->IBMName."</td>
                    <td align='center'>".$res->CreditTerm."</td>
                    <td align='center'>".$res->PMGCode."</td>
                    <td align='right'>".number_format($res->NetInvoice, 2)."</td>
					<td align='right'>".number_format($res->DiscountedGrossAmount, 2)."</td>
                    <td align='right'>".number_format($res->Payment, 2)."</td>
                    <td align='right'>".number_format($res->PaidDGS, 2)."</td>
					<td align='right'>".round($res->ServiceFeePercentage, 2)."%</td>
					<td align='right'>".number_format($res->ServiceFeeAmount, 2)."</td>
                </tr>";
				
        }
        echo $footer;

    }else{

        echo $header;
        echo '<tr class="listtr">
                    <td align="center" colspan="10">No result found.</td>
                </tr>';
        echo $footer;

    }

    echo "<div style='margin-top:10px;'>".AddPagination($total, $ibmservicefeeCount->num_rows, $page)."</div>";
}

?>