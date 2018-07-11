<?php

include "../../../initialize.php";
include IN_PATH.DS."scSalesForceMasterList.php";
include IN_PATH.DS."pagination.php";


if(isset($_POST['page'])){

    $branch = $_POST['branch'];
    $sfmlevel = $_POST['level'];
    $codefrom = $_POST['codefromHidden'];
    $codeto = $_POST['codetoHidden'];
    $page = $_POST['page'];
    $total = 10;

    $salesforcemasterlist = salesforcemasterlist($database, $branch, $sfmlevel, $codefrom, $codeto, false, $page, $total);
    $salesforcemasterlistcount = salesforcemasterlist($database, $branch, $sfmlevel, $codefrom, $codeto, true, $page, $total);

    echo '<table class="tablelisttable" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FF00A6; border-top:none;">
            <tr class="tablelisttr">
                <td>Branch</td>
                <td>Account Code</td>
                <td>Account Name</td>
                <td>SF Level</td>
                <td>Appointment Date</td>
                <td>Mother Network Code</td>
                <td>Mother Network Name</td>
                <td>TIN</td>
                <td>Bank Account No.</td>
                <td>Status</td>
                <td>Termination Date</td>
                <td>Offsetting / Payout</td>
                <td>Branch Affiliation</td>
            </tr>';

    if($salesforcemasterlistcount->num_rows){

        while($res = $salesforcemasterlist->fetch_object()){

            echo '<tr class="listtr">
                    <td>'.$res->BranchName.'</td>
                    <td>'.$res->AccountCode.'</td>
                    <td>'.$res->AccountName.'</td>
                    <td align=center>'.$res->SFMLevel.'</td>
                    <td align=center>'.$res->AppoinmentDate.'</td>
                    <td>'.$res->NetworkCode.'</td>
                    <td>'.$res->NetworkName.'</td>
                    <td>'.$res->TIN.'</td>
                    <td>'.$res->BankAccount.'</td>
                    <td align=center>'.$res->StatusName.'</td>
                    <td>'.$res->TerminationDate.'</td>
                    <td align=center>'.$res->PayoutOrOffset.'</td>
                    <td></td>
                </tr>';

        }

    }else{

        echo '<tr class="listtr">
                <td align="center" colspan="14">No result found.</td>
            </tr>';

    }

    echo "</table>";
    echo "<div style='margin-top:10px;'>".  AddPagination($total, $salesforcemasterlistcount->num_rows, $page)."</div>";
}

if(isset($_POST['searched'])){

    $searched = $_POST['searched'];
    $sfmlevel = $_POST['sfmlevel'];
    $branchid = $_POST['branch'];

    $customer = $database->execute("SELECT c.ID, c.Name, TRIM(c.Code) Code FROM customer c
                                    INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
                                        AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', $branchid), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', $branchid), ribm.HOGeneralID) > 0
                                    WHERE c.CustomerTypeID = $sfmlevel
                                    AND LOCATE(CONCAT('-', $branchid), c.HOGeneralID) > 0
                                    AND ((c.Code LIKE '$searched%') OR (c.Name LIKE '$searched%'))
                                    LIMIT 10");
    if($customer->num_rows){
        while($res = $customer->fetch_object()){
            $result[] = array("ID" => $res->ID, "Value" => $res->Code." - ".$res->Name, "Label" => $res->Code." - ".$res->Name);
        }
    }else{
        $result[] = array("ID" => 0, "Value" => "", "Label" => "No item found.");
    }

    die(tpi_JSONencode($result));
}


if(isset($_POST['searchedbranch'])){

    $query = $database->execute("SELECT TRIM(`Code`) `Code`, `Name`, ID FROM branch
                                WHERE ID NOT IN (1,2,3)
                                AND ((`Code` LIKE '".$_POST['searchedbranch']."%')
                                    OR (`Name` LIKE '".$_POST['searchedbranch']."%'))
                                ORDER BY `Name` ASC");
    if($query->num_rows){
        while($res = $query->fetch_object()){
            $result[] = array("Label" => $res->Code." - ".$res->Name, "Value" => $res->Code." - ".$res->Name, "ID" => $res->ID);
        }
    }else{
        $result[] = array("Label" => "No result found.", "Value" => "", "ID" => 0);
    }
    die(tpi_JSONencode($result));
}

?>
