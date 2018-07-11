

<style>
	body, html, table{font-size:12px; font-family:arial;}
	.bordersolo{border-collapse:collapse;}
	.trheader td{font-weight:bold; text-align:center; padding:5px;}
	.trlist td{padding:5px;}
	.fieldlabel{width:25%; font-weight:bold; text-align:right;}
	.separator{width:5%; font-weight:bold; text-align:left;}
	.fieldlabelleft{font-weight:bold; text-align:left;}
	
</style>

<?php 
	

	include "../../../initialize.php";
	
	global $database;

	$ceid=$_GET['ID']; 
	$cecode=$_GET['code'];
	$cename= $_GET['desc'];  
	$datefr= $_GET['start'];  
	$dateto=$_GET['end'];
	$chargedto  = $_GET['charge'];
	$pagerows = 0;   //total rows that will be displayed
	$page = 0;
	

	date_default_timezone_set("Asia/Manila"); //set the time zone to correct the output time that will be displayed

	
	//--- report header ---
	
	echo "<div style='padding:10px; font-size:14px; text-align:center; font-weight:bold;'>View Ce Codes</div>";
	echo "<table width='100%' cellpadding='1' cellspacing='2'>
			<tr>
				<td class='fieldlabelleft' width='150px'>Code</td>
				<td class='separator'>:</td>
				<td>". $cecode ."</td>
				
				<td class='fieldlabelleft'>Run Date</td>
				<td class='separator'>:</td>
				<td>".date('F d, Y h:i a')."</td>				
			
				
			</tr>
			<tr>
				<td class='fieldlabelleft'>Ce Description</td>
				<td class='separator'>:</td>
				<td width='600px'>".$cename."</td>
				
				<td class='fieldlabelleft'>Run By</td>
				<td class='separator'>:</td>
				<td>".$_SESSION['user_session_name']."</td>
			</tr>
			
			<tr>
				<td class='fieldlabelleft'>Charged To:</td>
				<td class='separator'>:</td>
				<td width='600px'>".$chargedto."</td>
				

			</tr>

			<tr>
				<td class='fieldlabelleft'>Start Date</td>
				<td class='separator'>:</td>
				<td>".$datefr."</td>	

			</tr>

			<tr>
				<td class='fieldlabelleft'>End Date</td>
				<td class='separator'>:</td>
				<td>".$dateto."</td>
				

			</tr>			
			
			
		</table> <br> <br>";
		
	
	
	 //---- header for the client info  ----
	$header1 = "<table cellpadding='0' cellspacing='0' width='100%' class='bordersolo' border='1'>  
				 <tr height='25px' class='Customer Code' align=center>
						<td width='4%'>Line No.</td>
						<td width='10%'><b>Item Code</b></td>
						<td width='25%'><b>Item Description</b></td>
						<td width='5%' ><b>PMG</b></td>
				 </tr>";
				 
	
	$footer = "</table><br>";
	$row_number = 0;
	
	 
/* 	 $datefrom = (!empty($datefrom)) ? DATE("Y-m-d", strtotime($datefrom)) : ""; //converts the date format
	 $dateto = (!empty($dateto)) ? DATE("Y-m-d", strtotime($dateto)) : ""; */
	
	echo $header1 ;
	
	 $rs_cecode=$database->execute("SELECT 
										t.tpi_document_ID,
										t.ProductID,
										t.PMGID,
										pmg.Name pmgname,
										pmg.code pmgcode,
										p.Code ProductCode,
										p.Name ProductName 
										FROM tpi_documentdetails t 
										INNER JOIN product p ON p.ID = t.ProductID 
										INNER JOIN tpi_pmg pmg ON t.PMGID=pmg.ID
										WHERE t.tpi_document_ID =".$ceid);
	 

		if ($rs_cecode->num_rows)
		{

			while($ce_code = $rs_cecode->fetch_object())
			{
			
			$row_number ++;
			
					//----------------------------------------------------------------------
					//--------display data in table if the above querry is true-------------
					//----------------------------------------------------------------------

					echo  '<tr height="25px" class="listtr">
									<td align="center">' . $row_number . ' </td>
									<td align="center">' . $ce_code->ProductCode . ' </td>
									<td align="left"  style="padding-left:15px; ">' . $ce_code->ProductName .'</td>
									<td align="center" >' . $ce_code->pmgcode .'</td>
								</tr> ';
							
			}
					  
		}
		else
		{
			 echo '<tr class="listtr" align="center">
					<td colspan="10">No record(s) found</td>
				 </tr>';
		}
			
		echo $footer; //echo the table footer
	
?>

<script>
	window.print();  //print the page
</script>