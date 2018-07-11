<style>
    .pageset{margin-bottom: 20px;}
    .pageset table{border-collapse: collapse; width: 100%; font-family: arial; font-size: 11px;}
    h2{font-family: arial; font-size: 16px; text-align: center;}
    .pageset .tablelisttr td{padding: 5px; font-weight: bold; text-align: center;}
    .pageset .listtr td{padding: 5px;}
    @page{margin: 0.5in 0; size: landscape;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>
<?php

include "../../initialize.php";
include IN_PATH.DS."scSalesForceMasterList.php";

if(isset($_GET['page'])){
    
    $branch = $_GET['branch'];
    $sfmlevel = $_GET['level'];
    $codefrom = $_GET['codefromHidden'];
    $codeto = $_GET['codetoHidden'];
    $page = $_GET['page'];
    $total = 10;
    
    $salesforcemasterlistcount = salesforcemasterlist($database, $branch, $sfmlevel, $codefrom, $codeto, true, $page, $total);
    
    $header = '<div class="pageset"><table border=1>';
    $trheader = '<tr class="tablelisttr">
                    <td width="5%">Branch</td>
                    <td width="5%">Account Code</td>
                    <td width="15%">Account Name</td>
                    <td width="5%">SF Level</td>
                    <td width="10%">Appointment Date</td>
                    <td width="5%">Mother Network Code</td>
                    <td width="15%">Mother Network Name</td>
                    <td width="3%">TIN</td>
                    <td width="5%">Bank Account No.</td>
                    <td width="5%">Status</td>
                    <td width="10%">Termination Date</td>
                    <td width="5%">Offsetting / Payout</td>
                    <td width="10%">Branch Affiliation</td>
                </tr>';
    $footer = '</table></div>';
    
    $counter = 1;
    $row = 15;
    
    echo "<h2>Sales Force Master List</h2>";
    
    if($salesforcemasterlistcount->num_rows){
        
        while($res = $salesforcemasterlistcount->fetch_object()){
            
            if($counter == 1){
                echo $header;
                echo $trheader;
            }
            
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
            
            if($counter == $row){
               echo $footer;
               $counter = 0;
            }else{
                if($counter == $salesforcemasterlistcount->num_rows){
                    echo $footer;
                }
            }
            
            $counter++;
        }
        
    }else{
        
        echo $header;
        echo $trheader;
        echo '<tr class="listtr">
                    <td align="center" colspan="14">No result found.</td>
                </tr>';
        echo $footer;
        
    }
    
}

?>
