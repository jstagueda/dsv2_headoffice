<?php
	require_once "../initialize.php";
	global $database;
	$list = $_GET['campaignID'];
	$list .= 0;

	$rs_campaign = $sp->spSelectCampaignByIDList($database, $list);
	
	echo "<table border='0' width='100%' cellspacing='0' cellpadding='0'>
			<tr>
				<td align='left' height='30' class='padl5'><strong>List of Campaign(s)</strong></td>
			</tr>
			</table>";
	echo "
		<table border='0' width='100%' cellspacing='0' cellpadding='0'>
	";
	if($rs_campaign->num_rows)
	{
		$cnt = 0;
		while($row = $rs_campaign->fetch_object())
		{
			$cnt += 1;
			$campaign = $row->Code." - ". $row->Name;
			echo"
				<input type='hidden' name='hCampID[]' value='$row->ID'>
				<tr>
					<td align='left' height='20' class='padl5'>$campaign</td>
				</tr>";
		}
		$rs_campaign->close();
	}
	echo "</table>" . "_" . $cnt;
?>
