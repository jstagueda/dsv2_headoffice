
<style>
	body, html, table{font-size:12px; font-family:arial;}
	.bordersolo{border-collapse:collapse;}
	.trheader td{font-weight:bold; text-align:center; padding:5px;}
	.trlist td{padding:5px;}
	.fieldlabelleft{width:10%; font-weight:bold; text-align:left;}
	.fieldlabel{width:25%; font-weight:bold; text-align:left;}
	.fieldlabelright{width:25%; font-weight:bold; text-align:right;padding-right:30px;}
	.separator{width:5%; font-weight:bold; text-align:left;}
	
</style>

<?php 
	
	include "../../initialize.php";
	
	include IN_PATH.DS."scpriceuploading.php";
	global $database;
		
	date_default_timezone_set("Asia/Manila"); 

	$lastupddate = $database->execute("SELECT
										DATE_FORMAT(uh.enrollmentdate,'%M %d,%Y %h:%i %p')lastdateupd,
										u.`LoginName` login,
										u.id
										 FROM uploadhistory uh
										 INNER JOIN `user` u ON uh.userid=u.id	
										WHERE uh.status='SUCCESSFUL'
										ORDER BY DATE(uh.enrollmentdate) DESC LIMIT 1");
	
	if($lastupddate->num_rows)
	{
		
		while($t=$lastupddate->fetch_object())
		{
			$lastupddate_date = $t->lastdateupd;	
			$lastupddate_uname = $t->login;				
		}
	}
	
	
	if(isset($_GET['lstupd']))
	{
		$listofitem = "Previously Updated";
	}
	else
	{
		$listofitem = "ALL";
	}
	
	echo "<div style='padding:10px; font-size:14px; text-align:center; font-weight:bold;'>Base Price Maintenance</div> <br>";
	echo "<table width='80%' cellpadding='1' cellspacing='2' align='center'>

			<tr>
				<td class='fieldlabelleft'>Run By</td>
				<td class='separator'>:</td>
				<td>". $_SESSION['user_session_name'] ."</td>
				<td class='fieldlabelright'>Run Date</td>
				<td class='separator'>:</td>
				<td > ".date('F d, Y h:i a')."</td>
				
			</tr>
			<tr>
				<td class='fieldlabelleft'>Last Uploaded By</td>
				<td class='separator'>:</td>
				<td>". $lastupddate_uname ."</td>
				<td class='fieldlabelright'>Last Upload Date</td>
				<td class='separator'>:</td>
				<td>". $lastupddate_date ."</td>			
				
			</tr>			
			<tr>
				<td class='fieldlabelleft'>Product List</td>
				<td class='separator'>:</td>
				<td>". $listofitem ."</td>
			</tr>				
			
		</table> <br> ";



	$header1 = "<table cellpadding='0' cellspacing='0' width='80%' class='bordersolo' border='1' align='center'>  
					 <tr class='CustomerCode' height='25px' align='center'>
						<td width='1%'><b>Line Number</b></td>
						<td width='8%'><b>Item Code</b></td>
						<td width='20%'><b>Item Description</b></td>
						<td width='3%'><b>Base Price</b></td>
					 </tr>";
	$footer = "</table><br>";
	$counter = 1;
	
	
		$page      = (isset($_GET['page']))?$_GET['page']:1;
		$pagerows=30;  		
		$start = ($page > 1) ? ($page - 1) * $pagerows : 0; 
		$limit = 'limit '.$start.','.$pagerows;		
	
		if(isset($_GET['lstupd']))
		{
			$resdetails = viewregularprice($limit,1,1); 
		}
		else		
		{	
			$resdetails = viewregularprice($limit,1,0); 
		}	
					if($resdetails->num_rows){    
							echo $header1;	
							while($res = $resdetails->fetch_object())
								{
								$results_customer_salesinvoice = '<tr height ="25px" class="listtr">
																		<td align="center">'  . $counter . '</td>
																		<td align="center">'  . $res->code .' </td>
																		<td align="left" style="padding-left:10px;">'  . $res->Description .' </td> 
																		<td align="right" style="padding-right:10px;">'  . number_format($res->UnitPrice,2).' </td> 
																  </tr>';
																
								echo $results_customer_salesinvoice;
								$counter++;
								} 
						}
				else
				{
					echo $header1;
					echo '<tr class="listtr" align="center"> 
							<td colspan="10">No records found</td> 
						  </tr>';
				}
		echo $footer;
	

?>

<script>
	window.print();
</script>