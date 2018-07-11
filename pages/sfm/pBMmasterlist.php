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
	include IN_PATH."scBMmasterlist.php";
	
	$ibmfrom = (isset($_GET['IBMfromHidden']))?$_GET['IBMfromHidden']:0;
    $ibmto = (isset($_GET['IBMtoHidden']))?$_GET['IBMtoHidden']:0;
    $status      = (isset($_GET['status']))?$_GET['status']:0;
    $page = (isset($_GET['page']))?$_GET['page']:1;
    $pageibm = (isset($_GET['pageibm']))?$_GET['pageibm']:1;
    $total = 10;
	$branch = $_GET['branch'];
	
	$userquery = $database->execute("SELECT * FROM employee WHERE ID = ".$_SESSION['emp_id']."");
	$user = $userquery->fetch_object();
	$affiliation = $_GET['summarydetail'];
	
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainheader">
			<tr>
				<td class="fieldlabel">Report</td>
				<td class="separator">:</td>
				<td><b>BM Consolidated Network Report</b></td>
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
		
		$getNetwork = getconsolidatednetwork($database, $ibmfrom, $ibmto, $status, false, $page, $total, $branch);
        
        echo '<table width="100%" border="1" cellspacing="0" cellpadding="0" align = "center">';
        echo '<tr class="tablelisttr">
	            <td>CTR</td>
                <td>BRANCH</td>
                <td>NATIONAL ID</td>
                <td>ACCOUNT CODE</td>
                <td>ACCOUNT NAME</td>
				<td>M-IBM</td>
                <td>LEVEL</td>
				<td>BMC Date</td>
				<td>BMF Date</td>
                <td>STATUS</td>
				<td>LAST TM DATE</td>
				<td>VATABLE</td>
				<td>WITH OR</td>
            </tr>';
			
        $ctr = 0;
        if($getNetwork->num_rows)
		{
            while($res = $getNetwork->fetch_object() )
			{
				$HOGeneralID = $res->HOGeneralID;
				$ctr = $ctr + 1;
				echo '<tr class="listtr">';
				echo '<td align="right">'.$ctr.'.</td>';
				echo '<td align="center">'.$res->bcode.'</td>';
				echo '<td align="center">'.$res->HOGeneralID.'</td>';
				echo '<td align="left">'.$res->ccode.'</td>';
				echo '<td align="left">'.$res->cnamne.'</td>';
				echo '<td align="left">'.$res->networkcode.'</td>';
				echo '<td align="center">'.$res->leveltype.'</td>';
				echo '<td align="center">'.$res->bmcdate.'</td>';
				echo '<td align="center">'.$res->bmfdate.'</td>';
				echo '<td align="left">'.$res->statusid.'</td>';
				echo '<td align="left">'.$res->tmdate.'</td>';
				echo '<td align="left">'.$res->vatable.'</td>';
				echo '<td align="left">'.$res->withOR.'</td>';
				echo '<td align="left">&nbsp;</td>'; 
				echo '</tr>';
				
				if($affiliation == 'yes')
				{
					
					$getNetworklist = getconsolidatednetworklist($database, $HOGeneralID);
					if($getNetworklist->num_rows)
					{
						/*echo '<tr class="tablelisttr">
								<td colspan = 2></td>
								<td>AFF. BRANCH</td>
								<td>AFF. BM CODE</td>
								<td>ACCOUNT NAME</td>
								<td>LEVEL</td>
								<td>STATUS</td>
							</tr>';
					    $ctr2 = 0;*/
						while($r2 = $getNetworklist->fetch_object() )
						{
							$ctr2 = $ctr2 + 1;
							echo '<tr class="listtr">';
							echo '<td align="right"></td>';
							echo '<td align="center">'.$r2->bcode.'</td>';
							echo '<td align="center">'.$r2->HOGeneralID.'</td>';
							echo '<td align="left">'.$r2->ccode.'</td>';
							echo '<td align="left">'.$r2->cnamne.'</td>';
							echo '<td align="left">'.$r2->networkcode.'</td>';
							echo '<td align="center">'.$r2->leveltype.'</td>';
							echo '<td align="center">'.$r2->bmcdate.'</td>';
							echo '<td align="center">'.$r2->bmfdate.'</td>';
							echo '<td align="left">'.$r2->statusid.'</td>';
							echo '<td align="left">'.$r2->tmdate.'</td>';
							echo '<td align="left">'.$r2->vatable.'</td>';
							echo '<td align="left">'.$r2->withOR.'</td>';
							echo '<td align="left">'.$r2->AccountType.'</td>';
							echo '</tr>';
						}
					} 
				}
			}
        }else
		{
            echo '<tr class="listtr">
                <td align="center" colspan="8">No result found.</td>
            </tr>';
        }
	
	echo "</table>";
	
?>