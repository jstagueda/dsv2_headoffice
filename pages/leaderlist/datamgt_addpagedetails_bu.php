<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

<script type="text/javascript">
</script>

<?php
	include IN_PATH.DS."scAddPageDetails.php";
?>

<form name="frmAddPageDetails" method="post" >
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		    <td width="70%" class="txtgreenbold13" align="left"></td>
			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=4">Leader List</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Add Page Details</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>

<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">General Information</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="50%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="25%">&nbsp;</td>
					<td width="75%" align="right">&nbsp;</td>
				</tr>			
				<tr>
				    <td height="20"><div align="left" class="padl5"><strong>Brochure Code :</strong></div></td>
				    <td height="20"><?php echo $bcode;?></td>
			    </tr>
			    <tr>
				    <td height="20"><div align="left" class="padl5"><strong>Brochure Name :</strong></div></td>
				    <td height="20"><?php  echo $bname; ?></td>
			    </tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>Campaign Code : </strong></div></td>
				  	<td height="20"></td>
				</tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>Date Created : </strong></div></td>
				  	<td height="20"><?php echo $datecreated;?></td>
				</tr>			
				<tr>
					<td colspan="2" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="15%">&nbsp;</td>
					<td width="85%">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" ><div align="left" class="padl5"><strong>Number of Pages :</strong></div></td>
				    <td height="20"><?php  echo $nopage;?></td>				    
			    </tr>
			    <tr>
			    	<td height="20" ><div align="left" class="padl5"><strong>Size :</strong></div></td>
				    <td height="20"></td>
			    </tr>
			     <tr>
			    	<td height="20" ><div align="left" class="padl5"><strong>Status :</strong></div></td>
				    <td height="20"></td>
			    </tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" id="tbl2">
<tr>
	<td valign="top" ></td>
    <td valign="top" width="57%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Page Details</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="right" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="75%">
									<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
									<tr>
								   	 	<td height="20" ><div align="left" class="padl5"><strong>Call-outs : </strong></div></td>
									    <td height="20"><textarea name="txtCallouts" id="txtCallouts" cols="50" rows="5" class="txtfieldnh"></textarea></td>
								    </tr>  
								    <tr>
								   	 	<td height="20" ><div align="left" class="padl5"><strong>Offer Violators :</strong></div></td>
									    <td height="20"><textarea name="txtOfferViol" id="txtOfferViol" cols="50" rows="5" class="txtfieldnh"></textarea></td>
								    </tr> 
								   <tr>
								   	 	<td height="20" ><div align="left" class="padl5"><strong>Heroed Shade/Colors :</strong></div></td>
									    <td height="20">
									    	<select name="cboHS" class="txtfield" id="cboHS" style="width: 230px;">
											<option value="1">[Select Here]</option>
											</select>	
											&nbsp;&nbsp;<input name="btnAdd1" id="btnAdd1" type="submit" class="btn" value="Add">
										</td>
								    </tr>
								    <tr>
								   	 	<td height="20" ><div align="left" class="padl5"><strong>Item worn by model :</strong></div></td>
									    <td height="20">
									    <select name="cboItemWorn" class="txtfield" id="cboItemWorn" style="width: 230px;">
											<option value="1">[Select Here]</option>
											</select>
											&nbsp;&nbsp;<input name="btnAdd2" id="btnAdd2" type="submit" class="btn" value="Add">
									    </td>
									   
								    </tr>
								    </table>                                        								                                            							
					</td>
				</tr>
				</table>
				</td>
		</tr>
		</table>
</td>
</tr>
</table>

<br>
<table width="85%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td></td>
<td align="center"> 
	<input name="btnSave" type="submit" class="btn" value="Save" >
&nbsp;&nbsp;<input name="btnCancel" type="submit" class="btn" value="Cancel" ></td>
<td></td>
</tr>
</table>
<br>  
</form>