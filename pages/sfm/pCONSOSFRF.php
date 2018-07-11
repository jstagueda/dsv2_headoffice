<style>
	
table{font-size:12px; font-family: arial; border-collapse:collapse;}
td{padding:5px;}
.tablelisttr td{font-weight:bold; text-align:center;}
@page{
	margin : 0.5in 0;
}

.mainheader td{padding:1px;}
.fieldlabel{width:15%; font-weight:bold; text-align:right;}
.separator{width:5%; font-weight:bold; text-align:center;}
</style>

<?php 
	
	include "../../initialize.php";
	include IN_PATH."scCONSOSFRF.php";
	
	$ibmfrom = (isset($_GET['IBMfromHidden']))?$_GET['IBMfromHidden']:0;
    $ibmto   = (isset($_GET['IBMtoHidden']))?$_GET['IBMtoHidden']:0;
    $status  = (isset($_GET['status']))?$_GET['status']:0;
    $page    = (isset($_GET['page']))?$_GET['page']:1;
    $pageibm = (isset($_GET['pageibm']))?$_GET['pageibm']:1;
    $total   = 10;
	$branch  = $_GET['branch'];
	$cmp     = $_GET['Campaign'];
	
	$userquery = $database->execute("SELECT * FROM employee WHERE ID = ".$_SESSION['emp_id']."");
	$user = $userquery->fetch_object();
	$affiliation = $_GET['summarydetail'];
	
	$totalamount = 0;
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainheader">
			<tr>
				<td class="fieldlabel">Report</td>
				<td class="separator">:</td>
				<td><b>Loaded Computed SF/RF Report</b></td>
				<td class="fieldlabel">Run Date</td>
				<td class="separator">:</td>
				<td>'.date("F d, Y h:i").'</td>
			</tr>
			<tr>
				<td class="fieldlabel"></td>
				<td class="separator"></td>
				<td></td>
				<td class="fieldlabel">Created By</td>
				<td class="separator">:</td>
				<td>'.$user->LastName.', '.$user->FirstName.'</td>
			</tr>
		</table><br />';
		
		$getNetwork = getconsolidatednetwork($database, $ibmfrom, $ibmto, $status, false, $page, $total, $branch,$cmp);
        
        echo '<table width="100%" border="1" cellspacing="0" cellpadding="0" align = "center">';
        echo '<tr class="tablelisttr">
	            <td>CTR</td>
                <td>BRANCH</td>
                <td>NATIONAL ID</td>
                <td>ACCOUNT CODE</td>
                <td>ACCOUNT NAME</td>
				<td>M-IBM</td>
                <td>LEVEL</td>
                <td>STATUS</td>
				<td>AMOUNT</td>
            </tr>';
			
        $ctr = 0;
        if($getNetwork->num_rows)
		{
            while($res = $getNetwork->fetch_object() )
			{
				$HOGeneralID = $res->HOGeneralID;
				$ctr = $ctr + 1;
				$totalamount = $totalamount + $res->Amount;
				echo '<tr class="listtr">';
				echo '<td align="right">'.$ctr.'.</td>';
				echo '<td align="center">'.$res->Branch.'</td>';
				echo '<td align="center">'.$res->HOGeneralID.'</td>';
				echo '<td align="left">'.$res->ccode.'</td>';
				echo '<td align="left">'.$res->cname.'</td>';
				echo '<td align="left">'.$res->manager_code.'</td>';
				echo '<td align="center">'.$res->desc_code.'</td>';
				echo '<td align="left">'.$res->statuscode.'</td>';
				echo '<td align="left">'.$res->Amount.'</td>';
				echo '</tr>';
			} 
			
			    echo '<tr class="listtr">';
				echo '<td align="right" colspan = 8>Total:</td>';
				echo '<td align="left">'.$totalamount.'</td>';
				
				echo '</tr>';
				
        }else
		{
            echo '<tr class="listtr">
                <td align="center" colspan="8">No result found.</td>
            </tr>';
        }
	
	echo "</table>";
	
?>