<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jQueryLinkProductSubs.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<style>
    tr.trheader td{padding:3px; border:1px solid #FFA3E0; font-weight:bold;background:#FFDEF0;}
	tr.trlist td{padding:3px; border:1px solid #FFA3E0;}
	.ui-dialog .ui-dialog-titlebar-close span{margin : -10px 0 0 -10px;}
</style>

<form name = "form_listing">
<table border = "0" width = "100%" cellspacing = "0" cellpadding="0">
<tr>
	<td rowspan = "5" width="200" valign="top" class="bgF4F4F6"> 
	<?php  include("nav.php"); ?>
	</td>
	<td class="divider" rowspan = "5">&nbsp;</td>
	<td colspan = "3" class = "topnav" valign="middle" align="left">
		<table>
		<tr>
			<td>&nbsp;</td>
			<td>
				<span class="txtgreenbold13">Promo and Pricing Management System (PPMS)</span>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan = "3">&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align = "left" valign="top" style="min-height: 560px; display: block;">
		
		<div class="txtgreenbold13" style="width:98%; margin:auto;">
			Product Substitute
		</div>
		<br />
	
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td width="50%" valign="top">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin">&nbsp;</td>
							<td class="tabmin2">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
									<tr>
										<td class="txtredbold"><b>Action</b></td>
										<td>&nbsp;</td>
									</tr>
								</table>
							</td>
							<td class="tabmin3">&nbsp;</td>
						</tr>
					</table>
					<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0" class = "">
						<!-- Search Engine here.. -->
						<tr>
							<td>
								<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class = "bordersolo" style="border-top:none;">
									<tr>
										<td width="35%">&nbsp;</td>
										<td width="5%">&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td align="right">Product Code/Description</td>
										<td align="center">:</td>
										<td>
											<input name="request" type="hidden" id="request" value="">            
											<input name="ProductID" type="hidden" id="ProductID">
											<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="20" value="" onkeypress="OnkeySearch();">
											<input name="btnSearch" type="submit" class="btn" value="Search" />
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td align="right">&nbsp;</td>
										<td align="right">&nbsp;</td>
									</tr>
									<!-- End Search Engine -->
								</table>
							</td>
						</tr>
						<tr>
							<td valign="top">
								<!-- List here all promos-->
                                <br />
								<table width="100%"  border="0" cellpadding="0" align="left" cellspacing="1">
									<tr align="center">
										<td class="tab" valign="top">
											<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
												<tr>
												<td class="tabmin">&nbsp;</td>
												<td class="tabmin2"><span class="txtredbold"><b>List of Product Substitute</b></span></td>
												<td class="tabmin3">&nbsp;</td>
												</tr>
											</table>
											<div style="max-height: 300px; overflow: auto;" class="bordergreen">
												<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0" id="ListingPromo">
													<!-- ajax here -->
													<tr class="bgFFFFFF">
														<td width="10%" height="220" valign="top" class="borderBR padl5" colspan="4">
														<div align="center"><span class="">Fetching data please wait.</span></div>
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			<td valign="top" width="1%"> &nbsp;</td >
			<td valign="top" width="48%" >
				<!-- List of Branches.. -->
				<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
					<tr>
						<td class="tabmin">&nbsp;</td>
						<td class="tabmin2"><span class="txtredbold"><b>List of Branches</b></span></td>
						<td class="tabmin3">&nbsp;</td>
					</tr>
				</table>
				<div style="max-height: 387px; overflow: auto;" class="bordergreen">
					<table width="100%"  align="center" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr align="center" class="trheader">
							<td width="10%" class="bdiv_r"><div align="center">
								<input name="chkAll" type="checkbox" id="chkAll" value="1" class="inputOptChk" onclick="checkAllbranch();" /></div>
							</td>
							<td width="30%" class="bdiv_r"><div align="center"><span class="txtredbold padl5">Code</span></div></td>
							<td width="60%"><div align="center"><span class="txtredbold padl5">Name</span></div></td>
						</tr>
						<?php $q = $database->execute("select * from branch where ID > 3 order by Code ASC"); 
						if($q->num_rows): 
							while($r=$q->fetch_object()): ?>
							<tr class="trlist">
								<td width="10%" class="bdiv_r"><div align="center">
									<input name="BranchID[]" type="checkbox" id="BranchID[]" value="<?=$r->ID;?>" class="inputOptChk" /></div>
								</td>
								<td width="30%" class="bdiv_r"><div align="center"><span><?= $r->Code;?></span></div></td>
								<td width="60%"><div align="center"><span><?= $r->Name;?></span></div></td>
							</tr>
						<?php 
							endwhile;
						else:
						?>
						<tr class="trlist">
							<td width="10%" height="230" valign="top" class='borderBR padl5' colspan='4'>
								<div align="center"><span class="">No record(s) display.</span></div>
							</td>
						</tr>
						<?php
						endif;
						?>
					</table>
					</div>
			</td>
		</tr>
		<tr>
			<td colspan=3>
				<br /><br />
				<table border = "0" align = "center">
					<tr>
						<!-- Button here -->
						<td valign="top">
							<input type = "submit" onclick = "return ConfirmSave(1);" value = "Save" id = "btnSave" class = "btn">
							<input type = "submit" onclick = "return ConfirmSave(0);" value = "Cancel" id = "btnCancel" class = "btn">
						</td>
					</tr>
				</table>
			</td>		
		</tr>		
		</table>
	</td>
</tr>
</table>
</form>
<div id="dialog-message">
	<p></p>
</div>
