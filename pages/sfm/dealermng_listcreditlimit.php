<link rel= "stylesheet" type= "text/css" href= "../../css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "../../css/calpopup.css" />
<?PHP 
	include IN_PATH.DS."scViewCreditLimit.php";
	global $database;
?>

<script language="javascript" type="text/javascript">
function checkAll(bin) 
{
	var elms = document.frmUpdateCreditLimit.elements;

	for (var i = 0; i < elms.length; i++)
	{
		if (elms[i].name == 'chkInclude[]') 
	  	{
	  		elms[i].checked = bin;		  
	  	}		
	}
}

function CheckInclude()
{
	var elms = document.frmUpdateCreditLimit.elements;

	for (var i = 0; i < elms.length; i++)
	{
		if (elms[i].name == 'chkInclude[]') 
	  	{
	  		if (elms[i].checked == false)
	  		{
	  			document.frmUpdateCreditLimit.chkAll.checked = false;
    			break;	  			
	  		}		  
	  	}		
	}	
}

function checker()
{
	var ml = document.frmUpdateCreditLimit;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkInclude[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}

function validateApprove()
{ 
	if (!checker())
	{
		alert('Please select dealer(s) to be approved.');
		return false;		
	}
	else
	{
		if (confirm('Are you sure you want to approve credit limit  of dealer(s)?') == false)
		{
			return false;
		}  
	} 
}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?PHP
			include("nav_dealer.php");
		?>
		<br>
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
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td class="txtgreenbold13">Approve Credit Limit of Multiple Dealers</td>
					<td>&nbsp;</td>
				</tr>
				</table>
				<?php
				if (isset($_GET['errmsg']) && $_GET['errmsg'] != "")
				{
				?> 
				<br>
				<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td>
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="70%" class="txtreds">&nbsp;<b><?php echo $_GET['errmsg']; ?></b></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php		
				}
				?>
				<?php
				if (isset($_GET['message']) && $_GET['message'] != "")
				{
				?> 
				<br>
				<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td>
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $_GET['message']; ?></b></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php		
				}
				?>
			</td>
		</tr>		
		</table>
		<br />
		<form  action="index.php?pageid=122" name="frmUpdateCreditLimit" method="post">
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
			  				<td colspan="4" height="10">&nbsp;</td>
						</tr>
						<tr>
			  				<td width="20%" height="20" align="right" class="padr5 txt10"><strong>Branch :</strong></td>
			  				<td width="30%" height="20" align="left">
			  					<select id="cboBranch" name="cboBranch" class="txtfield">
			  						<option value="0">[Select Here]</option>
			  						<?php
			  					 		if ($rs_branch->num_rows)
										{
											while ($row = $rs_branch->fetch_object())	
											{
												if ($branchID == $row->ID) 
													$sel = "selected";
												else 
													$sel = "";
													
												echo "<option value='$row->ID' $sel>".$row->Name."</option>";										
											}
											$rs_branch->close();
										} 
		  							?>
		  						</select>
		  			 		</td>
			  				<td width="20%" height="20">&nbsp;</td>
			  				<td width="30%" height="20">&nbsp;</td>
						</tr>
						<tr>
			  				<td height="20" align="right" class="txt10 padr5"><strong>Branch Code :</strong></td>
			  				<td height="20" align="left"><input type="text" name="txtSearch" class="txtfield" value="<?php echo $search; ?>"></td>
			  				<td height="20" align="left"></td>
			  				<td height="20">&nbsp;</td>
						</tr>
						<tr>
			  				<td height="20" align="right">&nbsp;</td>
			  				<td height="20" align="left"><input type="submit" class="btn" value="Generate List" name="btnSearch"></td>
			  				<td height="20" align="left">&nbsp;</td>
			  				<td height="20">&nbsp;</td>
						</tr>
						<tr>
			  				<td colspan="4" height="10">&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin">&nbsp;</td>
					<td class="tabmin2">
						<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td class="txtredbold">Dealer List</td>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
				</table>
				<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0" class="borderfullgreen">
				<tr>
					<td class="tab">
						<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10" border="0">
						<tr>				
							<td width="5%" class="bdiv_r" align="center"><input type="checkbox" id="chkAll" name="chkAll" onclick="checkAll(this.checked);"></td>
							<td width="15%" class="bdiv_r padl5" align="left">IGS Code</td>
							<td width="25%" class="bdiv_r padl5" align="left">IGS Name</td>
							<td width="25%"  class="bdiv_r padl5"align="left">IBM Code - Name</td>
							<td width="15%" class="bdiv_r padr5" align="right">Old Credit Limit</td>	
							<td width="15%" class="padr5" align="right">New Credit Limit</td>	
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="bgF9F8F7"><div class="scroll_400" id="dynamic">				
						<table width="100%" cellpadding="0" cellspacing="1"  border="0">
						<?php 
							if($rsCustomerList->num_rows)
							{
								while($row = $rsCustomerList->fetch_object())
								{
									$oldamount = number_format($row->OldCreditLimit,2,".",",");
									$newamount = number_format($row->NewCreditLimit,2,".",",");
									echo "<tr>				
											<td width='5%' class='borderBR' align='center'>
												<input type='checkbox'name='chkInclude[]' id='chkInclude[]' onclick='return CheckInclude();' value='$row->CreditLimitDetailsID'>	
												<input type='hidden'name='hCustomerID$row->CreditLimitDetailsID' value='$row->customerID'>
												<input type='hidden'name='hBranchID$row->CreditLimitDetailsID' value='$row->BranchID'>
												<input type='hidden'name='hNewCreditLimit$row->CreditLimitDetailsID' value='$row->NewCreditLimit'>
											</td>
											<td width='15%' class='borderBR padl5' align='left'>$row->CustomerCode</td>
											<td width='25%' class='borderBR padl5' align='left'>$row->CustomerName</td>
											<td width='25%' class='borderBR padl5' align='left'>$row->IBM</td>
											<td width='15%' class='borderBR padr5' align='right'>$oldamount</td>	
											<td width='15%' class='borderBR padr5' align='right'>$newamount</td>			
									</tr>";
								}
							}
							else
							{
								echo "<tr><td colspan='6' height='20' class='borderBR txtredsbold' align='center'>No record(s) to display.</td></tr>";
							}
						?>
						</table>				
					</div></td>
				</tr>	
				</table>
			</td>
		</tr>
		</table>
		<br>	
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center">
				<input type="submit" class="btn" value="Approve" name="btnSave" onclick="return validateApprove();">
				<input type="submit" class="btn" value="Cancel" name="btnCancel">
			</td>
		</tr>
		</table>
		<br>		
		</form>
		</td>
	</tr>
</table>