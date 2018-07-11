<style>
    .tablelisttable{border-collapse: collapse; font-family: arial; font-size:11px; width:100%;}
    .tablelisttr td{font-weight: bold; text-align: center; padding: 5px; font-size:10px;}
    .listtr td{padding: 5px;}
    .pageset{margin-bottom:20px;}
    h2{font-family: arial; font-size:16px; text-align:center;}
    @page{margin: 0.5in 0; size:landscape;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>
<?php

include "../../initialize.php";
include IN_PATH.DS."scIBMSalesPerformance.php";

$page = $_GET['page'];
$total = 10;

$ibmfrom = $_GET['ibmfromHidden'];
$ibmto = $_GET['ibmtoHidden'];
$prodcat = $_GET['productcategory'];
$campaign = $_GET['campaign'];
$branch = $_GET['branch'];

$ibmsalesperformancecount = ibmsalesperformancereport($database, $ibmfrom, $ibmto, $prodcat, $campaign, true, $page, $total, $branch);

$header = '<div class="pageset"><table class="tablelisttable" border="1" cellspacing="0" cellpadding="0">
            <tr class="tablelisttr">
                <td rowspan="3">IBM Code</td>
                <td rowspan="3">IBM Name</td>
                <td colspan="6">Discount Gross Sales</td>
                <td colspan="7">Staff Count</td>
                <td rowspan="3">Sales Amount</td>
                <td rowspan="3">Active Account</td>
                <td rowspan="3">% Active (Active / End Count)</td>
                <td rowspan="3">Avg. Order</td>
            </tr>
            <tr class="tablelisttr">
                <td rowspan="2">This Month</td>
                <td rowspan="2">YTD</td>
                <td rowspan="2">Previous Month</td>
                <td rowspan="2">Inc/Dec vs. Prev. Month</td>
                <td rowspan="2">Same Month Previous Year</td>
                <td rowspan="2">Growth Vs. Same Month Previous Year</td>
                <td rowspan="2">Beginning Count</td>
                <td colspan="3">ADD</td>
                <td colspan="2">Remove</td>
                <td rowspan="2">End Count</td>
            </tr>
            <tr class="tablelisttr">
                <td>New Recruit</td>
                <td>Reactivated</td>
                <td>Others</td>
                <td>Terminated</td>
                <td>Others</td>
            </tr>';
$footer = "</table></div>";

$row = 10;
$counter = 1;

echo "<h2>IBM Sales Performance Report (ISPR)</h2>";

if($ibmsalesperformancecount->num_rows){

    while($res = $ibmsalesperformancecount->fetch_object()){

        $thisMonthquery = $database->execute("SELECT SUM(TotalDGSAmount) TotalDGSAmount FROM tpi_sfasummary_pmg
                                            WHERE CampaignCode = DATE_FORMAT(NOW(), '%b%y')
                                            AND IBMID = $res->IBMID AND PMGType = '$res->PMGCode'
											AND LOCATE('-$branch', HOGeneralID) > 0");
        $thisMonth = $thisMonthquery->fetch_object();

        $YTDQuery = $database->execute("SELECT SUM(TotalDGSAmount) TotalDGSAmount FROM tpi_sfasummary_pmg
                                            WHERE ((CampaignMonth < DATE_FORMAT(NOW(), '%m'))
                                                OR (CampaignMonth > DATE_FORMAT(NOW(), '%m')))
                                            AND CampaignYear BETWEEN(DATE_FORMAT(NOW(), '%y') - 1)
                                            AND DATE_FORMAT(NOW(), '%y')
                                            AND IBMID = $res->IBMID
                                            AND PMGType = '$res->PMGCode'
											AND LOCATE('-$branch', HOGeneralID) > 0");
        $ytd = $YTDQuery->fetch_object();

        $previousMonthQuery = $database->execute("SELECT SUM(TotalDGSAmount) TotalDGSAmount FROM tpi_sfasummary_pmg
                                            WHERE CampaignCode = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%b%y')
                                            AND IBMID = $res->IBMID
                                            AND PMGType = '$res->PMGCode'
											AND LOCATE('-$branch', HOGeneralID) > 0");
        $previousMonth = $previousMonthQuery->fetch_object();

        $previousYearQuery = $database->execute("SELECT SUM(TotalDGSAmount) TotalDGSAmount FROM tpi_sfasummary_pmg
                                            WHERE CampaignCode = DATE_FORMAT(NOW() - INTERVAL 1 YEAR, '%b%y')
                                            AND IBMID = $res->IBMID
                                            AND PMGType = '$res->PMGCode'
											AND LOCATE('-$branch', HOGeneralID) > 0");
        $previousYear = $previousYearQuery->fetch_object();

        $activeAccountQuery = $database->execute("SELECT
                                            COUNT(c.ID) Active
                                            FROM customer c
                                            INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
												AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE('-$branch', HOGeneralID) > 0)
                                            WHERE c.StatusID = 1 AND ribm.IBMID = $res->IBMID
											AND LOCATE('-$branch', c.HOGeneralID) > 0");
        $activeAccount = $activeAccountQuery->fetch_object();

        $IncDecVSPreviousMonth = ($previousMonth->TotalDGSAmount == 0)?$thisMonth->TotalDGSAmount:($previousMonth->TotalDGSAmount - $thisMonth->TotalDGSAmount);
        $Growth = ($previousYear->TotalDGSAmount == 0)?$thisMonth->TotalDGSAmount:($previousYear->TotalDGSAmount - $thisMonth->TotalDGSAmount);
        $Active = ($activeAccount->Active / $res->EndCount);

        if($counter == 1){
            echo $header;
        }

        echo '<tr class="listtr">
            <td>'.trim($res->Code).'</td>
            <td>'.$res->Name.'</td>
            <td align="right">'.number_format($thisMonth->TotalDGSAmount, 2).'</td>
            <td align="right">'.number_format($ytd->TotalDGSAmount, 2).'</td>
            <td align="right">'.number_format($previousMonth->TotalDGSAmount, 2).'</td>
            <td align="right">'.number_format($IncDecVSPreviousMonth, 2).'</td>
            <td align="right">'.number_format($previousYear->TotalDGSAmount, 2).'</td>
            <td align="right">'.number_format($Growth, 2).'</td>
            <td align="right">'.$res->BeginningCount.'</td>
            <td align="right">'.$res->NewRecruits.'</td>
            <td align="right">'.$res->Reactivated.'</td>
            <td align="right">'.$res->AddOthers.'</td>
            <td align="right">'.$res->Terminated.'</td>
            <td align="right">'.$res->RemoveOthers.'</td>
            <td align="right">'.$res->EndCount.'</td>
            <td align="right">'.number_format($res->SalesAmount, 2).'</td>
            <td align="right">'.$activeAccount->Active.'</td>
            <td align="right">'.number_format($Active, 2).'</td>
            <td align="right">'.$res->AverageOrder.'</td>
        </tr>';

        if($counter == $row){
            echo $footer;
            $counter = 0;
        }else{
            if($counter == $ibmsalesperformancecount->num_rows){
                echo $footer;
            }
        }

        $counter++;
    }

}else{
    echo $header;
    echo '<tr class="listtr">
            <td colspan="19" align="center">No result found.</td>
        </tr>';
    echo $footer;
}

?>
