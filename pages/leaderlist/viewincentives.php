<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script src="js/jxViewLoyalty.js" language="javascript" type="text/javascript"></script>
<script src="js/jsloyalty.js" language="javascript" type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav">
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td width="70%" class="txtgreenbold13" align="Left"></td>
				<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<br />
<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%">&nbsp;<a class="txtgreenbold13">Loyalty Promo</a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php if (isset($_GET['errmsg'])):?>
<br />
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%" class="txtreds">&nbsp;<b><?php echo $_GET['errmsg']; ?></b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php endif; ?>
<?php if (isset($_GET['msg'])): ?>
<br />
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $_GET['msg']; ?></b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php endif; ?>
<br />
<form name="frmViewSetPromo" method="post" action="">
	<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td width="10%">&nbsp;</td>
						<td width="90%">&nbsp;</td>
					</tr>			
					<tr>
						<td width="13%" height="20"><div align="right" class="txtpallete"><strong>Promo Code / Promo Title: </strong></div></td>
						<td width="13%" height="20"><div align="left">&nbsp;&nbsp;<input name="txtPromoCodeDesc" id = "txtPromoCodeDesc" type="text" class="txtfield" id="txtPromoCodeDesc" value="" size="30"></div></td>
					</tr>
						<td width="13%" height="20"><div align="right" class="txtpallete"></div></td>
						<td width="13%" height="20">
							<div align="left">&nbsp; 
								<input name="btnSearch" type="submit" class="btn" value="Search">
							</div>
						</td>
					<tr>
						<td height="20" colspan="2">&nbsp;</td>
					</tr>	
				</table>
			</td>
		</tr>
	</table>
<br />
	<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
						<td class="tabmin2"><div align="left" class="txtredbold padl5">List of Loyalty Promos</div></td>		
			<td class="tabmin3">&nbsp;</td>
		</tr>
	</table>
	<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_150">
					<table width="100%"   border="0" cellpadding="0" cellspacing="1" id = "DynamicTable">
						<tr align='center' class='txtdarkgreenbold10 tab'>
							<td width='4%' height='20' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>			
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>			
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>			
						</tr>
						<tr align="center">
								<td width="10%"  height="25" class="borderBR"><div align="center"></div></td>
								<td width="40%" height="20" class=" borderBR"><div align="left" class="padl5"></a></div></td>
								<td width="10%" height="20" class=" borderBR"><div align="left" class="padl5"></a></div></td>
								<td width="10%" height="20" class=" borderBR"><div align="left" class="padl5"></a></div></td>
						</tr>
						  else: ?>	
						<tr>
							<td colspan='5' height='30' class='borderBR'><div align='center'><span class='txtredsbold'>No record(s) to display.</span></div></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
	<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td height="3" class="bgE6E8D9"></td>
			</tr>
	</table>
	<br />
	<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td height="20" class="txtblueboldlink" width="50%"></td>
            <td height="20" class="txtblueboldlink" width="48%"></td>
        </tr>
    </table>
</form>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
			<form name="frmViewSetPromo" method="post" action="index.php?pageid=170.1">
				<input name='btnCreate' type='submit' class='btn' value='Create New' />
			</form>
		</td>			
	</tr>
</table>
<br />