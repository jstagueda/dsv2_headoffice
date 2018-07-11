<style>

</style>

<?php
require_once("../initialize.php");
include IN_PATH.DS."pagination.php";

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
									ORDER BY $sortby DESC
									$limit");
	}
    return $query;
}

$sfmlevel = (isset($_POST['salesforcelevel']))?$_POST['salesforcelevel']:0;
$fromCustomID = (isset($_POST['salesforcefromHidden']))?$_POST['salesforcefromHidden']:0;
$toCustomerID = (isset($_POST['salesforcetoHidden']))?$_POST['salesforcetoHidden']:0;
$campaignCode = (isset($_POST['campaign']))?$_POST['campaign']:0;
$page = (isset($_POST['page']))?$_POST['page']:1;
$branch = $_POST['branch'];

if(isset($_POST['sortby'])){
    if($_POST['sortby'] == "Customer Code"){
		if($sfmlevel == 1){
			$sortby = "c.Code";
		}else{
			$sortby = "ibm.Code";
		}
    }else if($_POST['sortby'] == "DGS Amount"){
        $sortby = "ss.TotalDGSSales";
    }else if($_POST['sortby'] == "Paid-on Time"){
        $sortby = "ss.TotalOnTimeDGSPayment";
    }else if($_POST['sortby'] == "Collection%"){
        $sortby = "IFNULL(((ss.TotalOnTimeDGSPayment / ss.TotalDGSSales) * 100), 0)";
    }else{
		if($sfmlevel == 1){
			$sortby = "c.Name";
		}else{
			$sortby = "ibm.Name";
		}
    }
}

$topnth = (isset($_POST['topnth']))?(($_POST['topnth'] == 0)?10:$_POST['topnth']):10;

$total = 10;

$customerDGS = customerDGS($database, $fromCustomID, $toCustomerID, $campaignCode, $sfmlevel, false, $page, $total, $sortby, $topnth, $branch);
$CountcustomerDGS = customerDGS($database, $fromCustomID, $toCustomerID, $campaignCode, $sfmlevel, true, $page, $total, $sortby, $topnth, $branch);

?>

<table border="0" width="100%" cellpadding="0" cellspacing="0" class="bordergreen">
    <tr class="trheader">
        <td>Customer Code</td>
        <td>Customer Name</td>
        <td>Campaign</td>
        <td>Campaign-to-Date DGS Amount</td>
        <td>Paid-on-Time DGS Amount</td>
        <td>No. of Defaults</td>
        <td>Collection%</td>
        <td>Year-to-Date DGS Amount</td>
        <td>Credit Term</td>
    </tr>
    <?php 
    if($customerDGS->num_rows > 0){
        while($res = $customerDGS->fetch_object()){
    ?>
    <tr class="trlist">
        <td><?=$res->Code?></td>
        <td><?=$res->Name?></td>
        <td><?=$res->CampaignCode?></td>
        <td align="right"><?=number_format($res->TotalDGSSales, 2)?></td>
        <td align="right"><?=number_format($res->TotalOnTimeDGSPayment, 2)?></td>
        <td>0</td>
        <td align="right"><?=number_format($res->collection, 2)?></td>
        <td align="right"><?=number_format($res->YTDDGSAmount, 2)?></td>
        <td><?=$res->creditterm?></td>
    </tr>
    <?php
        }
    }else{
    ?>
    <tr class="trlist">
        <td colspan="9" align="center">No query found.</td>
    </tr>
    <?php }?>
</table>
<div style="margin-top:20px;">
    <?=AddPagination($topnth, $CountcustomerDGS->num_rows, $page);?>
</div>