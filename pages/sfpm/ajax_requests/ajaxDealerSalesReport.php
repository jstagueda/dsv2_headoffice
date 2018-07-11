<?php

include "../../../initialize.php";
include IN_PATH.DS."scDealerSalesReport.php";
include IN_PATH.DS."pagination.php";

if(isset($_POST['searchedbranch'])){
	$query = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (`Name` LIKE '".$_POST['searchedbranch']."%' OR `Code` LIKE '".$_POST['searchedbranch']."%') LIMIT 10");
	if($query->num_rows){
		while($res = $query->fetch_object()){
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
    
    $customerrange = customerrange($database, $_POST['searched'], $_POST['branch']);
    if($customerrange->num_rows){
        while($res = $customerrange->fetch_object()){
            $result[] = array("Name" => TRIM($res->Code)." - ".$res->Name, "ID" => $res->ID, "Value" => TRIM($res->Code)." - ".$res->Name);
        }
    }else{
        $result[] = array("Name" => "No result found.", "ID" => 0, "Value" => "");
    }
    die(tpi_JSONencode($result));
}

if(isset($_POST['page'])){
    
    $dealerfrom = (isset($_POST['dealerfromHidden']))?$_POST['dealerfromHidden']:0;
    $dealerto = (isset($_POST['dealertoHidden']))?$_POST['dealertoHidden']:0;
    $datefrom = (isset($_POST['datefrom']))?$_POST['datefrom']:0;
    $dateto = (isset($_POST['dateto']))?$_POST['dateto']:0;
    $po = (isset($_POST['accumulativePO']))?(($_POST['accumulativePO'] == "")?0:$_POST['accumulativePO']):0;
    $amount = (isset($_POST['amount']))?(($_POST['amount'] == "")?0:$_POST['amount']):0;
    $page = (isset($_POST['page']))?$_POST['page']:1;
	$branch = $_POST['branch'];
    $total = 10;
    
    $dealersales = dealersales($database, $dealerfrom, $dealerto, $datefrom, $dateto, $po, $amount, false, $page, $total, $branch);
    $countdealersales = dealersales($database, $dealerfrom, $dealerto, $datefrom, $dateto, $po, $amount, true, $page, $total, $branch);
    
    echo '<table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
            <tr class="tablelisttr">
                <td>Dealer Code</td>
                <td>Dealer Name</td>
                <td>CSP</td>
                <td>CSP Less CPI</td>
                <td>DGS</td>
                <td>DGS Less CPI</td>
                <td>Invoice Amount</td>
                <td>Invoice Amount Less CPI</td>
            </tr>';
    if($countdealersales->num_rows){
        while($res = $dealersales->fetch_object()){
            echo '<tr class="listtr">
                <td>'.$res->Code.'</td>
                <td>'.$res->Name.'</td>
                <td align="right">'.number_format($res->CSP, 2).'</td>
                <td align="right">'.number_format($res->CSPLessCPI, 2).'</td>
                <td align="right">'.number_format($res->DGS, 2).'</td>
                <td align="right">'.number_format($res->DGSLessCPI, 2).'</td>
                <td align="right">'.number_format($res->Invoice, 2).'</td>
                <td align="right">'.number_format($res->InvoiceLessCPI, 2).'</td>
            </tr>';
        }
    }else{
        echo '<tr class="listtr">
            <td align="center" colspan="8">No result found.</td>
        </tr>';
    }
    echo '</table>';
    echo "<div style='margin-top:10px;'>".AddPagination($total, $countdealersales->num_rows, $page)."</div>";
}

?>
