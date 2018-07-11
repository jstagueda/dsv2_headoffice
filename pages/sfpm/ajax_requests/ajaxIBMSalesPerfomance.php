<?php

include "../../../initialize.php";
include IN_PATH.DS."pagination.php";
include IN_PATH.DS."scIBMSalesPerformance.php";

if(isset($_POST['searchbranch'])){
	$branch = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (Name LIKE '".$_POST['searchbranch']."%' OR Code LIKE '".$_POST['searchbranch']."%') LIMIT 10");
	if($branch->num_rows){
		while($res = $branch->fetch_object()){
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

if(isset($_POST['searched'])){

    $ibmrange = IBMrange($database, $_POST['searched']);
    if($ibmrange->num_rows){

        while($res = $ibmrange->fetch_object()){
            $result[] = array("ID" => $res->ID, "Label" => trim($res->Code)." - ".$res->Name, "Value" => trim($res->Code)." - ".$res->Name);
        }

    }else{
        $result[] = array("ID" => 0, "Label" => "No result found.", "Value" => "");
    }

    die(json_encode($result));
}

//===============================================================================

if($_POST['action'] == "pagination"){

    $page = $_POST['page'];
    $total = 10;

    $ibmfrom = $_POST['ibmfromHidden'];
    $ibmto = $_POST['ibmtoHidden'];
    $prodcat = $_POST['productcategory'];
    $campaign = $_POST['campaign'];

    $ibmsalesperformance = ibmsalesperformancereport($database, $ibmfrom, $ibmto, $prodcat, $campaign, false, $page, $total, $_POST['branch']);
    $ibmsalesperformancecount = ibmsalesperformancereport($database, $ibmfrom, $ibmto, $prodcat, $campaign, true, $page, $total, $_POST['branch']);

    $header = '<table class="tablelisttable" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FF00A6; border-top:none;">
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
    $footer = "</table>";

    if($ibmsalesperformancecount->num_rows){
        echo $header;
        while($res = $ibmsalesperformance->fetch_object()){

            $thisMonthquery = $database->execute("SELECT SUM(TotalDGSAmount) TotalDGSAmount FROM tpi_sfasummary_pmg
                                                WHERE CampaignCode = DATE_FORMAT(NOW(), '%b%y')
                                                AND IBMID = $res->IBMID AND PMGType = '$res->PMGCode'");
            $thisMonth = $thisMonthquery->fetch_object();

            $YTDQuery = $database->execute("SELECT SUM(TotalDGSAmount) TotalDGSAmount FROM tpi_sfasummary_pmg
                                                WHERE ((CampaignMonth < DATE_FORMAT(NOW(), '%m'))
                                                    OR (CampaignMonth > DATE_FORMAT(NOW(), '%m')))
                                                AND CampaignYear BETWEEN(DATE_FORMAT(NOW(), '%y') - 1)
                                                AND DATE_FORMAT(NOW(), '%y')
                                                AND IBMID = $res->IBMID
                                                AND PMGType = '$res->PMGCode'");
            $ytd = $YTDQuery->fetch_object();

            $previousMonthQuery = $database->execute("SELECT SUM(TotalDGSAmount) TotalDGSAmount FROM tpi_sfasummary_pmg
                                                WHERE CampaignCode = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%b%y')
                                                AND IBMID = $res->IBMID
                                                AND PMGType = '$res->PMGCode'");
            $previousMonth = $previousMonthQuery->fetch_object();

            $previousYearQuery = $database->execute("SELECT SUM(TotalDGSAmount) TotalDGSAmount FROM tpi_sfasummary_pmg
                                                WHERE CampaignCode = DATE_FORMAT(NOW() - INTERVAL 1 YEAR, '%b%y')
                                                AND IBMID = $res->IBMID
                                                AND PMGType = '$res->PMGCode'");
            $previousYear = $previousYearQuery->fetch_object();

            $activeAccountQuery = $database->execute("SELECT
                                                COUNT(c.ID) Active
                                                FROM customer c
                                                INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
													AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID)
                                                WHERE c.StatusID = 1
                                                    AND ribm.IBMID = $res->IBMID");
            $activeAccount = $activeAccountQuery->fetch_object();

            $IncDecVSPreviousMonth = ($previousMonth->TotalDGSAmount == 0)?$thisMonth->TotalDGSAmount:($previousMonth->TotalDGSAmount - $thisMonth->TotalDGSAmount);
            $Growth = ($previousYear->TotalDGSAmount == 0)?$thisMonth->TotalDGSAmount:($previousYear->TotalDGSAmount - $thisMonth->TotalDGSAmount);
            $Active = ($activeAccount->Active / $res->EndCount);

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
        }
        echo $footer;
    }else{
        echo $header;
        echo '<tr class="listtr">
                <td colspan="19" align="center">No result found.</td>
            </tr>';
        echo $footer;
    }

    echo "<div style='margin-top:10px;'>".AddPagination($total, $ibmsalesperformancecount->num_rows, $page)."</div>";
}

?>
