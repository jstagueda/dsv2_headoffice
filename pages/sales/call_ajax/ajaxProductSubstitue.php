<?php

include "../../../initialize.php";
include IN_PATH.DS."scProductSubstitute.php";
include IN_PATH.DS."pagination.php";

if(isset($_POST['searched'])){
    $product = product($database, $_POST['searched']);
    if($product->num_rows){
        while($res = $product->fetch_object()){
            $result[] = array("ID" => $res->ID, "Value" => TRIM($res->Code)." - ".$res->Name, "Label" => TRIM($res->Code)." - ".$res->Name);
        }
    }else{
        $result[] = array("ID" => 0, "Value" => "", "Label" => "No result found.");
    }
    tpi_JSONencode($result);
}

if(isset($_POST['page'])){
    
    $branch = $_POST['branch'];
    $datefrom = $_POST['datefrom'];
    $dateto = $_POST['dateto'];
    $productlinefrom = $_POST['productlevelfrom'];
    $productlineto = $_POST['productlevelto'];
    $productfrom = $_POST['productfromHidden'];
    $productto = $_POST['producttoHidden'];
    $page = $_POST['page'];
    $total = 10;
        
    $productsubstitute = productsubstitute($database, $branch, $datefrom, $dateto, $productlinefrom, $productlineto, 
                                            $productfrom, $productto, false, $page, $total);
    $countproductsubstitute = productsubstitute($database, $branch, $datefrom, $dateto, $productlinefrom, $productlineto, 
                                            $productfrom, $productto, true, $page, $total);
    $branchquery = branchselect($database, $branch);
        $branch = $branchquery->fetch_object();
    
    $header = '<table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
                <tr class="tablelisttr">
                    <td rowspan="2">Branch</td>
                    <td rowspan="2">Date</td>
                    <td colspan="3" style="border-bottom:2px solid #FFA3E0;">Original Items</td>
                    <td colspan="3" style="border-bottom:2px solid #FFA3E0;">Substitute Items</td>
                </tr>
                <tr class="tablelisttr">
                    <td>Product Line Description</td>
                    <td>Product Code</td>
                    <td>Product Description</td>
                    <td>Product Line Description</td>
                    <td>Product Code</td>
                    <td>Product Description</td>
                </tr>';
    $footer = '</table>';
        
    if($countproductsubstitute->num_rows){
        echo $header;
        while($res = $productsubstitute->fetch_object()){
            echo '<tr class="listtr">
                    <td align="center">'.trim($branch->Code).' - '.$branch->Name.'</td>
                    <td>'.$res->ProdDate.'</td>
                    <td align="center">'.$res->OrigProdLine.'</td>
                    <td>'.$res->OrigCode.'</td>
                    <td>'.$res->OrigDesc.'</td>
                    <td align="center">'.$res->SubProdLine.'</td>
                    <td>'.$res->SubCode.'</td>
                    <td>'.$res->SubDesc.'</td>
                </tr>';
        }
        echo $footer;
    }else{
        echo $header;
        echo '<tr class="listtr">
                <td align="center" colspan="8">No result found.</td>
            </tr>';
        echo $footer;
    }
    
    echo "<div style='margin-top:10px;'>".AddPagination($total, $countproductsubstitute->num_rows, $page)."</div>";
    
}

?>
