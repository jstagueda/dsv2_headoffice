<link rel= "stylesheet" type= "text/css" href= "../../css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "../../css/calpopup.css" />
<?PHP 
include IN_PATH.DS."scViewApprovedCreditLimit.php";
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
						<td class="txtgreenbold13">View Approved Credit Limit of Multiple Dealers</td>
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
		<form  action="index.php?pageid=122" name="frmUpdateCreditLimit" method="post">
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr><td>
		<!--Begin Search Tab
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
			  			
			  			<td width="10%" align="right">IBM Code From: </td>
			  			<td width="20%" align="left"><input type="text" name="txtSearch" class="txtfield" value=""> </td>
			  			<td width="40%" align="left"><input type="submit" class="btn" value="Generate List" name="btnSearch"></td>
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
			</table>-->			
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
			<tr height="20">				
				
				<td width="12%" class="padl5" align="left">IGS Code</td>
				<td width="20%" class="padl5" align="left">IGS Name</td>
				<td width="30%"  class="padl5"align="left">IBM Code - Name</td>
				<td width="10%" class="padl5" align="left">Old Credit Limit</td>	
				<td width="10%" class="padl5" align="left">New Credit Limit</td>
				<td width="15%" class="padl5" align="left">Approved Date</td>	
							
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
							$oldamount = number_format($row->OldCreditLimit,2,".",",");
							$newamount = number_format($row->NewCreditLimit,2,".",",");
							$lastmodifieddate = date("M d, Y",strtotime($row->LastModifiedDate));
							echo "<tr  height='20'>			
								
								<td width='12%'  class='borderBR padl5'  align='left'>$row->CustomerCode</td>
								<td width='20%'  class='borderBR padl5'  align='left'>$row->CustomerName</td>
								<td width='30%'   class='borderBR padl5'  align='left'>$row->IBM</td>
								<td width='10%'  class='borderBR padl5'  align='left'>$oldamount</td>	
								<td width='10%'  class='borderBR padl5'  align='left'>$newamount</td>	
								<td width='15%'  class='borderBR padl5'  align='left'>$lastmodifieddate</td>			
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
		
		<br>		
		</form>
		</td>
	</tr>
</table>


