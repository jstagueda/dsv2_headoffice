<link rel= "stylesheet" type= "text/css" href= "../../css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "../../css/calpopup.css" />
<?PHP 
	include IN_PATH.DS."scUpdateCreditLimit.php";
	global $database;
?>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script type="text/javascript">
function chkSearch()
{
	var creditFrom = document.frmUpdateCreditLimit.txtIBMfrm;
	var creditTo   = document.frmUpdateCreditLimit.txtIBMto;
	
	if(creditFrom.value == "" && creditTo.value == "" )
	{
		alert("Credit Limit range is required.");
		creditFrom.select();
		return false;
	}
	
	if(creditFrom.value == "" && creditTo.value != "" )
	{
		alert("Credit Limit From is required.");
		creditFrom.select();
		return false;
	}
	
	if(!isNumeric(creditFrom.value))
	{
		alert("Invalid numeric format for Credit Limit From.");
		creditFrom.select();
		creditFrom.focus();
		return false;		
	}
	
	if(creditFrom.value != "" && creditTo.value == "" )
	{
		alert("Credit Limit To is required.");
		creditTo.select();
		return false;
	}
	
	if(!isNumeric(creditTo.value))
	{
		alert("Invalid numeric format for Credit Limit To.");
		creditTo.select();
		creditTo.focus();
		return false;		
	}
	
	if (eval(creditFrom.value) > eval(creditTo.value))
	{
		alert("Credit Limit From should be less than or equal to Credit Limit To.");
		creditFrom.select();
		creditFrom.focus();
		return false;		
	}	
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
		<?PHP
			include("nav_dealer.php");
		?> <br>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Management</span></td>
		</tr>
		</table>
		<br>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				<table width="95%" border="0" align="center" cellpadding="0"
					cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Update Credit Limit of Multiple Dealers</td>
						<td>&nbsp;</td>
					</tr>
				</table>

				<?php
				if ($errmsg != "")
				{
					?> <br>
				<table width="95%" border="0" cellspacing="0" cellpadding="0"
					align="center">
					<tr>
						<td>
						<table width="100%" border="0" align="center" cellpadding="0"
							cellspacing="1">
							<tr>
								<td width="70%" class="txtreds">&nbsp;<b><?php echo $errmsg; ?></b></td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				<?php		
					}
				?>		
		</table>
		<br />
		<!--Begin form-->
		<form  action="index.php?pageid=121" name="frmUpdateCreditLimit" method="post">
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr><td>
		<!--Begin Search Tab-->
			<table width="70%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
			<tr>
				<td>
					<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
			  			
			  			<td width="10%" align="right">&nbsp;</td>
			  			<td width="20%" align="right">&nbsp;</td>
			  			<td width="40%" align="right">&nbsp;</td>
			  			<td width="30%"></td>
					</tr>
					<tr>
			  			
			  			<td width="15%" align="right" class="padr5"><strong>Credit Limit From:</strong></td>
			  			<td width="20%" align="left"><input type="text" name="txtIBMfrm" class="txtfield" value=""></td>
			  			<td width="35%" align="left" class="padl5"><strong>To:</strong>&nbsp;<input type="text" name="txtIBMto" class="txtfield" value=""> &nbsp; &nbsp; <input type="submit" class="btn" value="Generate List" name="btnSearch" onclick="return chkSearch()"></td>
			  			<td width="30%"></td>
					</tr>
					<tr>
			  			
			  			<td width="10%" align="right">&nbsp;</td>
			  			<td width="20%" align="right">&nbsp;</td>
			  			<td width="40%" align="right">&nbsp;</td>
			  			<td width="30%"></td>
					</tr>					
					
					</table>
				</td>
			</tr>
			</table>			
			<!--End Search Tab-->		
		</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>
		<!--Start Dealer List-->
		<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td class="txtredbold">Dealer List</td>
					<td>
						<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		
		<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0" class="borderfullgreen" >
		<tr>
		<td class="tab">
			<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 " border="0">
			<tr>				
				<td width="3%" class="padl5 bdiv_r" align="center" ><input type="checkbox" name="chkAll"></td>
				<td width="12%" class="padl5 bdiv_r" align="left">IGS Code</td>
				<td width="35%" class="padl5 bdiv_r" align="left">IGS Name</td>
				<td width="40%"  class="padl5 bdiv_r"align="left">IBM Code - Name</td>
				<td width="10%" class="padl5" align="left">Credit Limit</td>	
							
			</tr>
			</table>
		</td>
		</tr>
		<tr>
			<td class="bgF9F8F7"><div class="scroll_400" id="dynamic">				
				<!--Dealer Details-->
				<table width="100%" cellpadding="0" cellspacing="1"  border="0">
				<?php 
					if($rsCustomerList->num_rows)
					{
						while($row = $rsCustomerList->fetch_object())
						{
							$amount = number_format($row->CreditLimit,2,".",",");
							echo "<tr>				
								<td width='3%'  class='borderBR padl5''  align='left' >
									<input type='checkbox'name='chkInclude[]' value='$row->customerID'>
									<input type='hidden' name='hAmount$row->customerID' value='$amount'>
								</td>
								<td width='12%'  class='borderBR padl5'  align='left'>$row->CustomerCode</td>
								<td width='35%'  class='borderBR padl5'  align='left'>$row->CustomerName</td>
								<td width='40%'   class='borderBR padl5'  align='left'>$row->IBM</td>
								<td width='10%'  class='borderBR padl5'  align='left'>$amount</td>			
							</tr>";
						}	
						
					}
				
				
				?>
							
				</table>				
			</div></td>
		</tr>	
		</table>
		</td></tr>
	
		</table>		
		<br>
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr><td>
		<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
			<tr>
				<td>
					<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
			  			
			  			<td width="10%" align="right">&nbsp;</td>
			  			<td width="20%" align="right">&nbsp;</td>
			  			<td width="40%" align="right">&nbsp;</td>
			  			<td width="30%"></td>
					</tr>
					<tr>			  			
			  			<td colspan="4" align="center"><strong>New Credit Limit:</strong>&nbsp;&nbsp;&nbsp; <input type="text" name="txtNewCreditLimit" class="txtfield"></td>			  			
					</tr>	
					<tr>
			  			
			  			<td width="10%" align="right">&nbsp;</td>
			  			<td width="20%" align="right">&nbsp;</td>
			  			<td width="40%" align="right">&nbsp;</td>
			  			<td width="30%"></td>
					</tr>				
					</table>
				</td>
			</tr>
		</table>
		</td></tr></table>
		<br>	
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center">
				<input type="submit" class="btn" value="Apply" name="btnSave">
				<input type="submit" class="btn" value="Cancel" name="btnCancel">
			</td>
		</tr>
		</table>
		<br>		
		</form>
		</td>
	</tr>
</table>


