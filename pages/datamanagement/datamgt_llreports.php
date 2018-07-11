<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script type="text/javascript">
	var http = createRequestObject();

	function createRequestObject()
	{
	// creates XMLHttpRequest object
	if (window.XMLHttpRequest) ro = new XMLHttpRequest();
	else if (window.ActiveXObject) ro = new ActiveXObject("Microsoft.XMLHTTP");
	return ro;
	}
	
	function handleResponse() {
		if(http.readyState == 4){
			var response = http.responseText;
		/*	alert (response)*/
			document.getElementById(lyrName).innerHTML = response; 
			document.getElementById('divButtons').style.visibility = 'visible'; 
		}
	}	

	function startEOD()
	{	

		var test ;
		test = document.getElementById('cboMonth');		
		url="includes/scProcessLLReports.php?month=" +test.value;
		lyrName='divTest';
		document.getElementById(lyrName).innerHTML = "<table width=100% height=100%><tr><td width='100%' align='center'>Processing... Please wait.</td></tr><tr><td width='100%' height=70px align='center' valign='center'><img src='images/loading.gif'></td></tr></table>";
		http.open('get',url );
		http.onreadystatechange = handleResponse;
		http.send(null);
		document.getElementById('divButtons').style.visibility = 'hidden';
		
	}

</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav.php");
		?>

      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management </span></td>
        </tr>
      </table>
      <br />
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
		   <td valign="top">
			  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
				 <tr>
					<td class="txtgreenbold13">Process Leader List Reports</td>
					<td>&nbsp;</td>
				 </tr>
				 <tr>
					<td><div id="divTest" width="90%"></div></td>
				 </tr>
			  </table>
			  <br />
			  <div id="divButtons">
			<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
			  <tr>
				<td width="33%" valign="top">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
					  <tr>          	
						<form name="frmEOD" method="post" action="index.php?pageid=54">
							<td><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
								<tr>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								</tr>
								<tr>					
									
								
								<td align="right"><select name="cboMonth" id="cboMonth" class="txtfield">
									<option value="0">[SELECT MONTH HERE] </option>
									<?php 
										$lastEOMDate = "";
										$rsLastEOM = $sp->spSelectLastLLReportsProcess($database);
										//echo $rsLastEOM->num_rows; echo "test";
										if($rsLastEOM->num_rows)
										{
											while($row = $rsLastEOM->fetch_object())
											{
												$lastEOMDate = $row->LastProcessed;
											}
										}
										 //echo date("y:F:d",strtotime("-1 Months",strtotime($lastEOMDate)));
										$date1 = date("m",strtotime("+1 Months",strtotime($lastEOMDate)));
										$date2  =date("m"); 
										for($i = $date1 ; $i <= $date2 ; $i++)
										{
											switch($i)
											{
												case 1: echo "<option value='01'>January</option>" ;break;
												case 2: echo "<option value='02'>February</option>" ; break;
												case 3: echo "<option value='03'>March</option>" ; break;
												case 4: echo "<option value='04'>April</option>" ; break;
												case 5: echo "<option value='05'>May</option>" ; break;
												case 6: echo "<option value='06'>June</option>" ; break;
												case 7: echo "<option value='07'>July</option>" ; break;
												case 8: echo "<option value='08'>August</option>" ; break;
												case 9: echo "<option value='09'>September</option>" ; break;
												case 10: echo "<option value='10'>October</option>" ; break;
												case 11: echo "<option value='11'>November</option>" ; break;
												case 12: echo "<option value='12'>December</option>" ; break;
												
											}
											//echo $test;
										}
										
									?>
								
								  </select> </td>
								  <?php
								    /*$query = "SELECT LastSyncTime FROM syncparameters";
									$dbresult = mysql_query($query, $dbconnect);
									$row = mysql_fetch_row($dbresult);
								  
								    echo "<td>Last Sync: ".$row[0]."</td>";*/
								  ?>
								  <td align="left" >
									 &nbsp;&nbsp;&nbsp;<input name="btnSync" type="button" class="btn" value="Start Processing Leader List Reports" onClick="startEOD()" >                              
								  </td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td align="right">&nbsp;</td>
								</tr>
							</table></td>           
						</form>
					  </tr>
					</table>
		            <br>
	           </td>
              </tr>
            </table></div>
	       </td>
        </tr>
      </table>
    </td>
  </tr>
</table>