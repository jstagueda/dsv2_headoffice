<?php
include "../../../initialize.php";
include IN_PATH.DS."scLoyaltyProductList.php";
include IN_PATH.DS."pagination.php";

if($_POST['action'] == 'loyalty')
{
   $customerrange = loyaltycoverage($database, $_POST['searched']);
   if($customerrange->num_rows)
   {
		while($res = $customerrange->fetch_object())
		{
			$result[] = array("ID" => $res->ID, "Value" => TRIM($res->Code)." - ".$res->Name, "Label" => TRIM($res->Code)." - ".$res->Name);
		}
	}else
	{
			$result[] = array("ID" => 0, "Value" => "", "Label" => "No result found.");
	}
	tpi_JSONencode($result);
}
else
{	
	if(isset($_POST['searched'])){
		$customerrange = customerrange($database, $_POST['searched'], $_POST['branchId']);
		if($customerrange->num_rows){
			while($res = $customerrange->fetch_object()){
				$result[] = array("ID" => $res->ID, "Value" => TRIM($res->Code)." - ".$res->Name, "Label" => TRIM($res->Code)." - ".$res->Name);
			}
		}else{
			$result[] = array("ID" => 0, "Value" => "", "Label" => "No result found.");
		}
		tpi_JSONencode($result);
	}
}



if(isset($_POST['page']))
{
    $datefrom = $_POST['datefrom'];
    $dateto = $_POST['dateto'];
    $page = $_POST['page'];
    $loyaltyID = $_POST['Loyalty'];
    $total = 10;
    
    $loyaltyprdlist = loyaltyprdlist($database, $datefrom, $dateto,$loyaltyID,false, $page, $total);
    $loyaltyprdlistcount = loyaltyprdlist($database, $datefrom,$dateto,$loyaltyID, $dateto,true, $page, $total);
    
    $header = "<table style='border:1px solid #FF00A6; border-top:none;' class='tablelisttable'border='0' cellspacing='0' cellpadding='0'>
                <tr class='tablelisttr'>
                   <td>Product Code</td>
                   <td>Product Description</td>
                   <td>Start Date</td>
                   <td>End Date</td>
                </tr>";
    $footer = "</table>";
    
    echo $header;
    $counter = 1;
    
    if($loyaltyprdlist->num_rows)
	{
        while($res = $loyaltyprdlist->fetch_object()){
            
            echo '<tr class="listtr">
                    <td>'.$res->code.'</td>
                    <td>'.$res->desc.'</td>
                    <td>'.$res->Start_date.'</td>
                    <td>'.$res->End_date.'</td>
                </tr>';
            $counter++;
        }
    }else{
        echo '<tr class="listtr">
                <td align="center" colspan="14">No result found.</td>
            </tr>';
    }
    echo $footer;
    
    echo "<div style='margin-top:10px;'>".AddPagination($total, $loyaltyprdlistcount->num_rows, $page)."</div>";
    die();
}

?>
