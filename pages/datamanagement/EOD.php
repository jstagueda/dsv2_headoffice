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
		url="includes/scEOD.php";
		lyrName='divTest';
		document.getElementById(lyrName).innerHTML = "<table width=100% height=100%><tr><td width='100%' align='center'>Processing... Please wait.</td></tr><tr><td width='100%' height=70px align='center' valign='center'><img src='images/loading.gif'></td></tr></table>";
		http.open('get',url );
		http.onreadystatechange = handleResponse;
		http.send(null);
		document.getElementById('divButtons').style.visibility = 'hidden'; 
		
	}

</script>


<?php
	//include IN_PATH.DS."scSync.php";
?>

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
					<td class="txtgreenbold13">End of Day</td>
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
								  <?php
								    /*$query = "SELECT LastSyncTime FROM syncparameters";
									$dbresult = mysql_query($query, $dbconnect);
									$row = mysql_fetch_row($dbresult);
								  
								    echo "<td>Last Sync: ".$row[0]."</td>";*/
								  ?>
								  <td align="center" >
									 <input name="btnSync" type="button" class="btn" value="Start End of Day Transactions" onClick="startEOD()" >                              
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