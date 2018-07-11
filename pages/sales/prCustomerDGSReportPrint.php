<style>
    .pageset table{border-collapse:collapse; font-family:arial; font-size: 12px; width: 100%;}
    h2{text-align: center; font-family: arial; font-size: 16px;}
    .pageset .trheader{text-align: center; font-weight: bold;}
    .pageset table td{padding:3px;}
    .pageset{margin-bottom: 20px;}
    @page{margin: 0.5in 0; size:landscape;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>
<?php
include "../../initialize.php";

function customerDGS($database, $fromCustomID, $toCustomerID, $campaignCode, $sfmlevel, $istotal, $page, $total, $sortby, $topnth, $branch){
    $start = ($page > 1)?(($page-1) * $topnth):0;
    $limit = (!$istotal)?"LIMIT $start, $topnth":"";
	
	
	if($sfmlevel == 1){
		$customerrange = ($fromCustomID == 0 AND $toCustomerID == 0)?"":"AND (c.ID BETWEEN $fromCustomID
                                        AND $toCustomerID OR c.ID BETWEEN $toCustomerID AND $fromCustomID)";
		$query = $database->execute("SELECT
										c.ID,
										c.Code,
										c.Name,
										ss.CampaignCode,
										ss.TotalOnTimeDGSPayment,
										(ss.TotalDGSSales + IFNULL(ss.TotalCPIAmount, 0)) TotalDGSSales,
										ct.Name creditterm,
										IFNULL(((ss.TotalOnTimeDGSPayment / ss.TotalDGSSales) * 100), 0) collection,
										IFNULL((SELECT (SUM(TotalDGSSales) + IFNULL(SUM(ss.TotalCPIAmount), 0)) FROM tpi_sfasummary 
											WHERE CustomerID = c.ID 
											AND CampaignYear = ss.CampaignYear
											AND LOCATE('-$branch', HOGeneralID) > 0 ), 0) YTDDGSAmount,
										IFNULL((SELECT (SUM(TotalDGSSales) + IFNULL(SUM(ss.TotalCPIAmount), 0)) FROM tpi_sfasummary 
											WHERE CustomerID = c.ID 
											AND CampaignCode = ss.CampaignCode
											AND LOCATE('-$branch', HOGeneralID) > 0 ), 0) MTDDGSAmount
                                    FROM tpi_sfasummary ss
									INNER JOIN branch b ON b.ID = SPLIT_STR(ss.HOGeneralID, '-', 2)
                                    INNER JOIN customer c ON ss.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
                                    INNER JOIN tpi_credit cd ON cd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), cd.HOGeneralID) > 0
                                    INNER JOIN creditterm ct ON ct.ID = cd.CreditTermID
                                    WHERE ss.CampaignCode = '$campaignCode'
                                        AND c.CustomerTypeID = $sfmlevel
										AND b.ID = $branch
                                        $customerrange
                                    GROUP BY c.ID
                                    ORDER BY $sortby DESC
                                    $limit");
	}else{
		$customerrange = ($fromCustomID == 0 AND $toCustomerID == 0)?"":"AND (ibm.ID BETWEEN $fromCustomID
                                        AND $toCustomerID OR ibm.ID BETWEEN $toCustomerID AND $fromCustomID)";
										
		$query = $database->execute("SELECT
										ibm.ID,
										ibm.Code,
										ibm.Name,
										ss.CampaignCode,
										SUM(ss.TotalOnTimeDGSPayment) TotalOnTimeDGSPayment,
										SUM(ss.TotalDGSSales) TotalDGSSales,
										ct.Name creditterm,
										SUM(IFNULL(((ss.TotalOnTimeDGSPayment / ss.TotalDGSSales) * 100), 0)) collection,
										SUM(IFNULL((SELECT (SUM(ss.TotalDGSSales) + IFNULL(SUM(ss.TotalCPIAmount), 0)) FROM tpi_sfasummary 
											WHERE CustomerID = c.ID 
											AND CampaignYear = ss.CampaignYear
											AND LOCATE('-$branch', HOGeneralID) > 0 ), 0)) YTDDGSAmount,
										SUM(IFNULL((SELECT (SUM(ss.TotalDGSSales) + IFNULL(SUM(ss.TotalCPIAmount), 0)) FROM tpi_sfasummary 
											WHERE CustomerID = c.ID 
											AND CampaignCode = ss.CampaignCode
											AND LOCATE('-$branch', HOGeneralID) > 0), 0)) MTDDGSAmount
									FROM tpi_sfasummary ss 
									INNER JOIN branch b ON b.ID = SPLIT_STR(ss.HOGeneralID, '-', 2)
									INNER JOIN customer c ON ss.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE('-$branch', HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
										AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
									INNER JOIN tpi_credit cd ON cd.CustomerID = ibm.ID
										AND LOCATE(CONCAT('-', b.ID), cd.HOGeneralID) > 0
									INNER JOIN creditterm ct ON ct.ID = cd.CreditTermID
									WHERE ss.CampaignCode = '$campaignCode'
									AND ibm.CustomerTypeID = $sfmlevel
									AND b.ID = $branch
									$customerrange
									GROUP BY ibm.ID
									ORDER BY $sortby DESC");
	}
    return $query;
}

$sfmlevel = (isset($_GET['sfmlevel']))?$_GET['sfmlevel']:0;
$fromCustomID = (isset($_GET['customerfrom']))?$_GET['customerfrom']:0;
$toCustomerID = (isset($_GET['customerto']))?$_GET['customerto']:0;
$campaignCode = (isset($_GET['campaign']))?$_GET['campaign']:0;
$branch = $_GET['branch'];
$page = 1;
$total = 10;

if(isset($_GET['sortby'])){
    if($_GET['sortby'] == "Customer Code"){
		if($sfmlevel == 1){
			$sortby = "c.Code";
		}else{
			$sortby = "ibm.Code";
		}
    }else if($_GET['sortby'] == "DGS Amount"){
        $sortby = "ss.TotalDGSSales";
    }else if($_GET['sortby'] == "Paid-on Time"){
        $sortby = "ss.TotalOnTimeDGSPayment";
    }else if($_GET['sortby'] == "Collection%"){
        $sortby = "IFNULL(((ss.TotalOnTimeDGSPayment / ss.TotalDGSSales) * 100), 0)";
    }else{
        if($sfmlevel == 1){
			$sortby = "c.Name";
		}else{
			$sortby = "ibm.Name";
		}
    }
}
$topnth = (isset($_GET['topnth']))?(($_GET['topnth'] == 0)?10:$_GET['topnth']):10;

$CountcustomerDGS = customerDGS($database, $fromCustomID, $toCustomerID, $campaignCode, $sfmlevel, true, $page, $total, $sortby, $topnth, $branch);

echo "<h2>Customer DGS Report</h2>";

$header = "<div class='pageset'>
        <table border=1>
            <tr class='trheader'>
                <td width='8%'>Customer Code</td>
                <td>Customer Name</td>
                <td width='8%'>Campaign</td>
                <td width='8%'>Campaign-to-Date DGS Amount</td>
                <td width='8%'>Paid-on-Time DGS Amount</td>
                <td width='8%'>No. of Defaults</td>
                <td width='8%'>Collection%</td>
                <td width='8%'>Year-to-Date DGS Amount</td>
                <td width='8%'>Credit Term</td>
            </tr>";
$counter = 1;
$row = 20;

$DGSSalesTotal = 0;
$OnTimeDGSTotal = 0;
$YTDDGSTotal = 0;
$totalcount = 1;
		
if($CountcustomerDGS->num_rows > 0){
    while($res = $CountcustomerDGS->fetch_object()){
        if($counter == 1){
            echo $header;
        }
		
		$DGSSalesTotal += $res->TotalDGSSales;
		$OnTimeDGSTotal += $res->TotalOnTimeDGSPayment;
		$YTDDGSTotal += $res->YTDDGSAmount;
        
        echo "<tr>
                <td>".$res->Code."</td>
                <td>".$res->Name."</td>
                <td>".$res->CampaignCode."</td>
                <td align='right'>".number_format($res->TotalDGSSales, 2)."</td>
                <td align='right'>".number_format($res->TotalOnTimeDGSPayment, 2)."</td>
                <td>0</td>
                <td align='right'>".number_format($res->collection, 2)."</td>
                <td align='right'>".number_format($res->YTDDGSAmount, 2)."</td>
                <td>$res->creditterm</td>
            </tr>";
			
		if($CountcustomerDGS->num_rows == $totalcount){
			echo "<tr style='font-weight:bold;'>
					<td colspan='3' align='right'>Total : </td>
					<td align='right'>".number_format($DGSSalesTotal, 2)."</td>
					<td align='right'>".number_format($OnTimeDGSTotal, 2)."</td>
					<td>0</td>
					<td align='right'></td>
					<td align='right'>".number_format($YTDDGSTotal, 2)."</td>
					<td></td>
				</tr>";
			echo "</table></div>";
		}else{
			if($counter == $row){
				echo "</table></div>";
				$counter = 0;				
			}
		}
		
        $counter++;
		$totalcount++;
    }
}else{
    echo $header;
    echo "<tr>
            <td align='center' colspan=9>No result found.</td>
        </tr></table></div>";
    echo "</table></div>";
}
?>

<script>
	window.print();
</script>