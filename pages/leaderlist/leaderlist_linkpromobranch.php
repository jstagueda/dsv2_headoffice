<!--@Author: Gino C. Leabres..
	@Date: 	 10/03/2013..-->
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jQueryLinkPromoBranch.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<form name = "form_listing">
<table border = "0" width = "100%" cellspacing = "0" cellpadding="0">
<tr>
	<td rowspan = "5" width="200" valign="top" class="bgF4F4F6"> 
	<?php  include("nav.php"); ?>
	</td>
	<td class="divider" rowspan = "5">&nbsp;</td>
	<td colspan = "3" class = "topnav" valign="top" align="left">
		<table>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<span class="txtgreenbold13">Link Promos to Branches</span>
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
	<td align = "left" valign="top" style="min-height: 560px; display:block;">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class = "">
							<!-- Search Engine here.. -->
							<tr>
							<td>
                                                            <table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
					<tr>
						<td class="tabmin">&nbsp;</td>
						<td class="tabmin2"><span class="txtredbold">Action</span></td>
						<td class="tabmin3">&nbsp;</td>
					</tr>
					</table>
							<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class = "bordersolo" style="border-top:none;">
								<tr>
								<td width="%">&nbsp;</td>
								<td width="%" align="right">&nbsp;</td>
								<td width="%" align="right">&nbsp;</td>
								</tr>
								<tr>
								<td align="right">Promo Type</td>
                                                                <td align="center" width="5%">:</td>
                                                                <td>
										<input type="hidden" id = "cboPromoType" value = 0>
										<select name="cboPromoType" style="width:160px" class="txtfield">
											<?php $rs_promotype = $database->execute("select * from promotype where ID in (1,2,3)");						
												echo "<option value=\"0\" >[SELECT HERE]</option>";
												if ($rs_promotype->num_rows):
													while ($row = $rs_promotype->fetch_object()):
														($ptypeid == $row->ID) ? $sel = "selected" : $sel = "";
														echo "<option value='$row->ID' $sel>$row->Name</option>";
													endwhile;
													$rs_promotype->close();
												endif;
											?>
										</select>
									</td>
								</tr>
								<tr>
								<td align="right">
										Promo Code</td>
                                                                <td align="center" width="5%">:</td>
                                                                <td><input name="request" type="hidden" id="request" value="">
										<input name="PromoID" type="hidden" id="PromoID">
										<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="20" value="" onkeypress="OnkeySearch();">
										<input name="btnSearch" type="submit" class="btn" value="Search" /></td>
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
                                                            <br>
								<!-- List here all promos-->
								<table width="100%"  border="0" cellpadding="0" align="left" cellspacing="1" class="">
									<tr align="center">
										<td class="tab" valign="top">
											<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
												<tr>
												<td class="tabmin">&nbsp;</td>
												<td class="tabmin2"><span class="txtredbold">List of Promos</span></td>
												<td class="tabmin3">&nbsp;</td>
												</tr>
											</table>
											<div style="height: 255px; overflow: auto; width: 100%;">
												<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0" class="bordergreen" id="ListingPromo">
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
			<td valign="top" width="2%"> &nbsp;</td >
			<td valign="top" width="48%" >
					<!-- List of Branches.. -->
					<table width="94%"  border="0" align="left" cellpadding="0" cellspacing="0">
					<tr>
						<td class="tabmin">&nbsp;</td>
						<td class="tabmin2"><span class="txtredbold">List of Branches</span></td>
						<td class="tabmin3">&nbsp;</td>
					</tr>
					</table>
					<div style="height: 326px; overflow: auto; width: 94%;">
					<table width="100%"  align="center" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
					<tr align="center" class="tab">
						<td width="10%" class="bdiv_r"><div align="center">
							<input name="chkAll" type="checkbox" id="chkAll" value="1" class="inputOptChk" onclick="checkAllbranch();" /></div>
						</td>
						<td width="30%" class="bdiv_r"><div align="center"><span class="txtredbold padl5">Code</span></div></td>
						<td width="60%"><div align="center"><span class="txtredbold padl5">Name</span></div></td>
					</tr>
					<?php $q = $database->execute("select * from branch where ID > 3 order by Code ASC"); 
					if($q->num_rows): 
						while($r=$q->fetch_object()): ?>
						<tr class="tab">
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
						<tr class="bgFFFFFF">
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
			<td>
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

