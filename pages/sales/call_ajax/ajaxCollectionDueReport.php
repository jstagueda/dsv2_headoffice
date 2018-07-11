<?php

include "../../../initialize.php";
include IN_PATH.DS."pagination.php";
include IN_PATH.DS."scCollectionDueReport.php";

if(isset($_POST['searched'])){
    
    $customerrange = customerrange($database, $_POST['searched'], $_POST['sfmlevel'], $_POST['branch']);
    if($customerrange->num_rows){
        while($res = $customerrange->fetch_object()){
            $result[] = array("ID" => $res->ID, "Value" => $res->Code." - ".$res->Name, "Label" => $res->Code." - ".$res->Name);
        }
    }else{
        $result[] = array("ID" => 0, "Value" => "", "Label" => "No item found.");
    }
    
    die(tpi_JSONencode($result));
}

if(isset($_POST['page'])){
    
    $datefrom = $_POST['datefrom'];
    $dateto = $_POST['dateto'];
    $sfmlevel = $_POST['sfmlevel'];
    $sfafrom = $_POST['customerfromHidden'];
    $sfato = $_POST['customertoHidden'];
    $accounttype = $_POST['accounttype'];
    $page = $_POST['page'];
	$branch = $_POST['branchID'];
    $total = 10;
    
    $collectiondue = collectiondue($database, $datefrom, $dateto, $sfmlevel, $sfafrom, $sfato, $accounttype, false, $page, $total, $branch);
    $collectionduecount = collectiondue($database, $datefrom, $dateto, $sfmlevel, $sfafrom, $sfato, $accounttype, true, $page, $total, $branch);
	$collectionduecountTotal = collectiondueTotal($database, $datefrom, $dateto, $sfmlevel, $sfafrom, $sfato, $accounttype, true, $page, $total, $branch);
	$collectionduetotal = $collectionduecountTotal->fetch_object();
    
    $header = '<table class="tablelisttable" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FF00A6; border-top:none;">
                <tr class="tablelisttr">
                    <td>Account No.</td>
                    <td>Account Name</td>
                    <td>Credit Account Type</td>
                    <td>Credit Term</td>
                    <td>Invoice / Debit Memo</td>
                    <td>Ref. No. / Document No.</td>
                    <td>Total Amount Due</td>
					<td>Due Date</td>
                    <td>Contact No.</td>
                </tr>';
    $footer = "</table>";
    
    echo $header;
    if($collectiondue->num_rows){
        
        while($res = $collectiondue->fetch_object()){
            
            echo '<tr class="listtr">
                    <td>'.$res->AccountCode.'</td>
                    <td>'.$res->AccountName.'</td>
                    <td align="center">'.$res->CreditAccountType.'</td>
                    <td align="center">'.$res->CreditTerm.'</td>
                    <td align="right">'.$res->Code.'</td>
                    <td>'.$res->DocumentNo.'</td>
                    <td align="right">'.number_format($res->TotalAmount, 2).'</td>
					<td align="center">'.$res->DueDate.'</td>
                    <td align="center">'.$res->ContactNo.'</td>
                </tr>';
        }
		
		echo '<tr class="listtr">				
				<td colspan="6" align="right"><b>Total : </b></td>
				<td align="right"><b>'.number_format($collectionduetotal->TotalAmount, 2).'</b></td>
				<td colspan="2"></td>
			</tr>';
        
    }else{
    
        echo '<tr class="listtr">
                <td align="center" colspan="8">No result found.</td>
            </tr>';
    
    }
    
    echo $footer;
    
    echo "<div style='margin-top:10px;'>".  AddPagination($total, $collectionduecount->num_rows, $page)."</div>";
    
}


if(isset($_POST['action'])){
	
	if($_POST['action'] == "GetBranch"){
		
		$query = $database->execute("SELECT * FROM branch WHERE (`Code` LIKE '".$_POST['branch']."%' OR `Name` LIKE '".$_POST['branch']."%') LIMIT 10");
		if($query->num_rows){
			while($res = $query->fetch_object()){
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
