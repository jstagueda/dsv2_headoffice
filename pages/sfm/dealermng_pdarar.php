<link rel= "stylesheet" type= "text/css" href= "../../css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "../../css/calpopup.css" />
<?PHP 
include IN_PATH.DS."scUpdatePDARARCodes.php";
global $database;
?>

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
				<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer
				Management </span></td>
			</tr>
		</table>
		<br>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				<table width="95%" border="0" align="center" cellpadding="0"
					cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Update PDA-RAR Code of Multiple Dealers</td>
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
		<form  action="index.php?pageid=120" name="frmUpdateRARCodes" method="post">
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr><td>
		<!--Begin Search Tab-->
			<table width="70%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
			<tr>
				<td>
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
			  			<td colspan="4" height="10">&nbsp;</td>
					</tr>
					<tr>
			  			
			  			<td width="15%" height="20" align="right" class="padr5"><strong>IBM Code From:</strong></td>
			  			<td width="30%" height="20" align="left"><input type="text" name="txtIBMfrm" class="txtfield" value="<?php echo $custFrom;?>"> </td>
			  			<td width="25%" height="20" align="left" class="padl5"><strong>To:</strong>&nbsp;&nbsp;<input type="text" name="txtIBMto" class="txtfield" value= <?php echo $custTo;?>></td>
			  			<td width="30%" height="20">&nbsp;</td>
					</tr>
					<tr>
			  			
			  			<td height="20" align="right" class="padr5"><strong>PDA-RAR Code:</strong></td>
			  			<td height="20" align="left">
			  				<select name="cboPDACodes" class="txtfield">
			  					<option value="0">[ALL]</option>
			  					<?php
			  						if($rs_cboPDACode->num_rows)
			  						{
			  							  							
			  							while($row = $rs_cboPDACode->fetch_object())
			  							{
			  								
				  							if($pdaID == $row->ID)
				  							{
				  								$selected = "selected" ;
				  								
				  							}		
				  							else 
				  							{
				  								$selected = "" ;
				  							}	
			  								echo "<option value='$row->ID' $selected >$row->Name $selected $pdaID</option>" ;
			  							}
			  						} 
			  					?>
			  				</select>			  				
			  			</td>
			  			<td height="20" align="left">&nbsp;</td>
			  			<td height="20">&nbsp;</td>
					</tr>
					<tr>
			  			
			  			<td height="20" align="right">&nbsp;</td>
			  			<td height="20" align="left"><input type="submit" class="btn" value="Generate List" name="btnSearch"></td>
			  			<td height="20" align="right">&nbsp;</td>
			  			<td height="20">&nbsp;</td>
					</tr>
					<tr>
			  			<td colspan="4" height="10">&nbsp;</td>
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
					<td width="5%" align="center" class="bdiv_r"><input type="checkbox" name="chkAll"></td>
					<td width="10%" class="padl5 bdiv_r" align="left">IGS Code</td>
					<td width="15%" class="padl5 bdiv_r" align="left">IGS Name</td>
					<td width="30%"  class="padl5 bdiv_r"align="left">IBM Code - Name</td>
					<td width="10%" class="padr5 bdiv_r" align="right">Overdue Amount</td>
					<td width="10%" class="padr5 bdiv_r" align="right">Days Due</td>
					<td width="20%" class="padl5" align="left">Current PDA-RAR Code</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7"><div class="scroll_400" id="dynamic">				
				<!--Dealer Details-->
				<table width="100%" cellpadding="0" cellspacing="1"  border="0">
				
  				<?php 
				if($rsDealerDetails->num_rows)
				{
					while($dealerDetails = $rsDealerDetails->fetch_object())
					{
						$amount = number_format($dealerDetails->Amount,2,".",",");
						echo "
						<tr>
							<td width='5%' class='borderBR' align='center'>
								<input type='checkbox' name='chkInclude[]' value='$dealerDetails->CustomerPDAID'>
								<input type='hidden' name='hCustomerID$dealerDetails->CustomerPDAID' value='$dealerDetails->CustomerID'>
							</td>							
							<td width='10%' class='borderBR padl5' align='left'>$dealerDetails->CustomerCode</td>
							<td width='15%' class='borderBR padl5' align='left'>$dealerDetails->CustomerName</td>
							<td width='30%' class='borderBR padl5' align='left'>$dealerDetails->IBM</td>
							<td width='10%' class='borderBR padr5' align='right'>$amount</td>
							<td width='10%' class='borderBR padr5' align='right'>$dealerDetails->DaysDue</td>
							<td width='20%' class='borderBR padl5' align='left'>$dealerDetails->pdaName</td>
						</tr>";
					}	
				}
				else
				{
					echo "
						<tr>
							<td colspan='7' height='20' class='borderBR txtredsbold' align='center'>No record(s) to display.</td>
						</tr>";
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
			  			<td colspan="4" align="center"><strong>New PDA-RAR Code:</strong>
			  			<select name="cboNewRARCode" class="txtfield">
			  				<option value="0">[SELECT HERE]</option>
			  				<?php
		  						if($rs_cboNewPDACode->num_rows)
		  						{
		  							while($row = $rs_cboNewPDACode->fetch_object())
		  							{
		  								echo "<option value='$row->ID'>$row->Name</option>" ;
		  							}
		  						} 
		  					?>
			  			</select>
			  			</td>			  			
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
				<input type="submit" class="btn" value="Save" name="btnSave">
				<input type="submit" class="btn" value="Cancel" name="btnCancel">\
			</td>
		</tr>
		</table>
		<br>		
		</form>
		</td>
	</tr>
</table>


