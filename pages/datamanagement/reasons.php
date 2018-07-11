<link rel= "stylesheet" type= "text/css" href= "css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "css/calpopup.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/reason.js"  type="text/javascript"></script>
<form method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
		<?PHP include("nav.php"); ?> <br>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top" style="min-height: 610px; display: block;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Parameter</span></td>
		</tr>
		</table>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				<table width="95%" border="0" align="center" cellpadding="0"
					cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Reasons</td>
						<td>&nbsp;</td>
					</tr>
                                        <tr align ="left">
                            <td>
                                  Choose Categories: 
                                  <select id ="choose_cathegory" name ="choose_cathegory">
                                       <option value ="1">Reason tag maintenance</option>
                                       <option value ="2">Reason maintenance</option>
                                       <option value ="3">Reason Type maintenance</option>
                                  </select>
                            </td>
                        </tr>
				</table>	
		</table>
		<br>
		<!--Begin form-->
                <?php include "reason_form.php"; ?>
		<!-- End form -->		
</table>

</form>

