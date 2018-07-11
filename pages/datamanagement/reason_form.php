<!-- Reason tagging maintenance..  -->
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" id ="reason_tagging_maintenance">
                        <tr>
			<td> &nbsp;
			<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
			<tr>
				<td width="36%" valign="top">
                                    
					<div style = "text-align:center; margin:auto; width:90%;">
                                            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin"></td> 
								<td class="tabmin2">
									<div align="left" class="padl5 txtredbold">Action</div></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
							<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
								<tr>
									<td width="" align="center">&nbsp;</td>
									<td width="" align="center">&nbsp;</td>
									<td width="" align="center">&nbsp;</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Module</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<select id = "selection_module" name = "selection_module" class = "txtfield">
											<option value = "0">[SELECT HERE]</option>
											<?php $qmodule = $database->execute("SELECT * FROM module"); 
												if($qmodule->num_rows){
													while($r1 = $qmodule->fetch_object()){
														$ID = $r1->ID;
														$Name = $r1->Name;
													echo "<option value = '".$ID."'>".$Name."</option>";
													}
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Reason Type</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<select id = "selection_reason_type" name = "selection_reason_type" class = "txtfield">
											<option value = "0">[SELECT HERE]</option>
										</select>
									</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Mode Type</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<select id = "mode_type_reason_tag" name = "mode_type_reason_tag" class = "txtfield" >
											<option value = 0>SELECT HERE</option>
											<option value = "1">Automatic</option>
											<option value = "2">Manual</option>
											<option value = "3">Not Applicable</option>
										</select>
									</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Group Type</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<select id = "selection_group" name = "selection_group" disabled = "disabled" class = "txtfield">
											<option value = "0">[SELECT HERE]</option>
											<option value = "1">Inventory Movement Type</option>
											<option value = "2">Sales Force Level</option>
											<option value = "3">Not applicable</option>
										</select>
									</td>
								</tr>
								<tr>
									<td width="" align="center" class="padr5"></td>
                                                                        <td align="center" width="5%"></td>
									<td width="" align="left">
										<select id = "selection_group2" name = "selection_group2" class = "txtfield" ></select>
									</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Doc Type</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<select id = "doc_type" name = "doc_type" class = "txtfield" >
											<option value = "0">SELECT HERE</option>
											<option value = "1">Trade</option>
											<option value = "2">Non-Trade</option>
											<option value = "3">Not Applicable</option>
										</select>
									</td>
								</tr>
								<tr>
									<td width="" align="right" class="padr5"><strong>Link to DMCM</strong></td>
                                                                        <td align="center" width="5%">:</td>
									<td width="" align="left">
										<input type = "checkbox" value = "1" id = "LinkToDMCM" name = "LinkToDMCM" />
									</td>
								</tr>
								
								<tr>
									<td width="" align="center" class="padr5">&nbsp;</td>
                                                                        <td align="center" width="5%"></td>
									<td width="" align="left">
										<input type = "submit" value ="Save" disabled = "disabled" class = "btn" id = "save" onclick = "return confirmsave();">
									</td>
									<td width="">&nbsp;</td>
								</tr>
								<tr>
									<td width="" align="center" class="padr5">&nbsp;</td>
								</tr>
							</table>
					</div>
				</td>
				<td>
				<!-- reason table starts here -->
				<table width="50%" border="0" align="left" cellpadding="0" cellspacing="0">
				<tr>
				
					<td width="100%" valign="top">
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin"></td> 
								<td class="tabmin2">
									<div align="left" class="padl5 txtredbold">Reasons
									</div></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
						<td>
								<tr>
									<td valign="top" class="bgF9F8F7">
									</td>
								</tr>
								<tr>
									<td class="bgF9F8F7">
										<?PHP $q = $database->execute("SELECT * FROM reason order by Code asc"); ?>
										<div class="scroll_300">
											<input type = "hidden" value = "0" id = "total_records" />
											<table width="100%" id = "dynamic_reason"  border="0" cellpadding="0" cellspacing="1">
                                                                                         <!-- AJAX REASON HERE -->
											</table>
										</div>
									</td>
								</tr>
							</td>	
						</table>
					<!-- reason table ends here -->
					</td>
					</tr>
				</table>
				</td>
				<tr>
					<td>&nbsp;</td>
				</tr>	
			</tr>
			</table>
			</td>
			</tr>
</table>
<!-- Reason maintenance -->
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" id ="reason_maintenance" style ="display:none;">
 <tr>
			<td> &nbsp;
			<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
			<tr>
				<td width="36%" valign="top">
                                    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin"></td> 
								<td class="tabmin2">
									<div align="left" class="padl5 txtredbold">Action</div></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
					<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
						<tr>
							<td width="" align="center">&nbsp;</td>
							<td width="" align="center">&nbsp;</td>
							<td width="" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td width="" align="right" class="padr5"><strong>Reason Code</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
                                                            <input type ="text" value ="" id="reason_code" class ="txtfield" name ="reason_code" />
							</td>
						</tr>
						<tr>
							<td width="" align="right" class="padr5"><strong>Reason</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
                                                            <input type ="hidden" value ="" id="reason_id" name ="reason_id" />
                                                            <input type ="text" value ="" id="reason" class ="txtfield" name ="reason" />
							</td>
						</tr>
						<!--tr>
							<td width="" align="center" class="padr5"><strong>Reason Type:</strong></td>
							<td width="" align="center">
								<select id = "selection_reason_type_r" name = "selection_reason_type_r" class = "txtfield">
									  <option value = '0'>[SELECT HERE]</option>	
									 <?php 
										   $q = $database->execute("select * from reasontype");
										   if($q->num_rows):
												while($r = $q->fetch_object()):
													 echo "<option value = '".$r->ID."'>".$r->Name."</option>";
												endwhile;
										   endif;
										?>
								</select>
							</td>
						</tr-->
	
						<tr>
							<td width="" align="right" class="padr5"><strong>Credit GL Account</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
								<input type = "text" id = "CreditGLAcount" name = "CreditGLAcount" class = "txtfield">
							</td>
						</tr>
						<tr>
							<td width="" align="right" class="padr5"><strong>Credit Cost Center</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
								<input type = "text" id = "CreditCostCenter" name = "CreditCostCenter" class = "txtfield">							
							</td>
						</tr>
						<tr>
							<td width="" align="right" class="padr5"><strong>Debit GL Account</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
								<input type = "text" id = "DebitGLAccount" name = "DebitGLAccount" class = "txtfield">
							</td>
						</tr>
						<tr>
							<td width="" align="right" class="padr5"><strong>Debit Cost Center</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
								<input type = "text" id = "DebitCostCenter" name = "DebitCostCenter" class = "txtfield">
							</td>
						</tr>
						<tr>
							<td width="" align="right" class="padr5">&nbsp;</td>
                                                        <td align="center" width="5%"></td>
							<td width="" align="left">
								<input type = "submit" value ="Save"  class = "btn" id = "rm_save" onclick = "return rm_saveclick(1);">
								<input type = "submit" value ="Cancel"  class = "btn" id = "clear" onclick = "return rm_saveclick(4);">
								<input type = "submit" value ="Cancel" style="display:none;" class = "btn" id = "cancel" onclick = "return rm_saveclick(0);">
							</td>
							<td width="">&nbsp;</td>
						</tr>
						<tr>
							<td width="" align="center" class="padr5">&nbsp;</td>
						</tr>
					</table>
				</td>
				<td>
				<!-- reason table starts here -->
				<table width="50%" border="0" align="left" cellpadding="0" cellspacing="0">
				<tr>
				
					<td width="100%" valign="top">
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin"></td> 
								<td class="tabmin2">
									<div align="left" class="padl5 txtredbold" style="width:350px; margin:auto;">Reasons Search: <input type="text" id = "search" name = "search" class = "txtfield" />
									<input type = "submit" value ="search" class = "btn" id = "go_search" name = "go_search">
									</div></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
						<td>
								<tr>
									<td valign="top" class="bgF9F8F7">
									</td>
								</tr>
								<tr>
									<td class="bgF9F8F7">
										<div class="scroll_300">
											<table width="100%" id = "dynamic_reason2"  border="0" cellpadding="0" cellspacing="1">
													 <tr align="center" id = "">
														   <td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Name</div></td>
														   <td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>
													 </tr>
														 <?php
													 $q = $database->execute("select * from reason");
													 if($q->num_rows){
														  while($r = $q->fetch_object()){
															  echo '<tr>
																		  <td width="" height="20" class=" borderBR">
																			   <div align="center" class="padl5">
																					<a id = "reason_link" href = "" onclick = "return reason_click('.$r->ID.')">'.$r->Name.'</a>
																					<input type = "hidden" value = "'.$r->Name.'" id = "'.$r->ID.'">
																					<input type = "hidden" value = "'.$r->ReasonTypeID.'" id = "'.$r->Name.'">
																			   </div>
																		  </td>
																		  <td width="" height="20" class=" borderBR">
																			   <div align="center" class="padl5">
																					<input type = "submit" class = "btn" onclick = "return delete_record('.$r->ID.');" value = "Delete">
																		
																			   </div>
																		  </td>
																	</tr>';
														  }
													 }
													 ?>
											</table>
										</div>
									</td>
								</tr>
							</td>	
						</table>
					<!-- reason table ends here -->
					</td>
					</tr>
				</table>
				</td>
				<tr>
					<td>&nbsp;</td>
				</tr>	
			</tr>
			</table>
			</td>
			</tr>
</table>
<!-- Reason type maintenance here -->
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" id ="reason_type_maintenance" style ="display:none;">
 <tr>
			<td> &nbsp;
			<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
			<tr>
				<td width="44%" valign="top">
				<form name = "form_reason_type">
                                    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin"></td> 
								<td class="tabmin2">
									<div align="left" class="padl5 txtredbold">Action</div></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
					<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" id = "" style="border-top:none;">
						<tr>
							<td width="" align="center">&nbsp;</td>
							<td width="" align="center">&nbsp;</td>
							<td width="" align="center">&nbsp;</td>
							<input type = "hidden" id = "request" name = "request" value ="">
						</tr>
						<tr>
							<td width="" align="right" class="padr5"><strong>Name</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
                                                            <input type ="text" value ="" id="reason_type_name" class ="txtfield" name ="reason_type_name" />
                                                            <input type ="hidden" value ="" id="reason_type_ID" class ="txtfield" name ="reason_type_ID" />
							</td>
						</tr>
						<tr>
							<td width="" align="right" class="padr5"><strong>Module Type</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
								<select id = "selection_module_type" name = "selection_module_type" class = "txtfield">
									  <option value = '0'>[SELECT HERE]</option>	
									 <?php 
										   $q = $database->execute("select * from module");
										   if($q->num_rows):
												while($r = $q->fetch_object()):
													 echo "<option value = '".$r->ID."'>".$r->Name."</option>";
												endwhile;
										   endif;
										?>
								</select>
							</td>
						</tr>
						<tr>
							<td width="" align="right" class="padr5"><strong>Group</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
								<select name = "reason_type_group" id = "reason_type_group" class="txtfield">
									<option value = 0>SELECT HERE</option>
									<option value = 1>Sales Force Level</option>
									<option value = 2>Movement type</option>
									<option value = 3>Not Applicable</option>
								</select>								
							</td>
						</tr>
						<tr id="TRIsTradeNonTrade" style = "display:none;">
							<td width="" align="right" class="padr5"><strong>Is Trade/Non-Trade</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
								<input type = "checkbox" id = "isTradeNontrade" value = "1" name = "isTradeNontrade" class = "txtfield">
							</td>
						</tr>
						<tr id="TRModeType" style = "display:none;">
							<td width="" align="right" class="padr5"><strong>Is Auto / Manual</strong></td>
                                                        <td align="center" width="5%">:</td>
							<td width="" align="left">
								<input type = "checkbox" id = "ModeType" value = "1" name = "ModeType" class = "txtfield">
							</td>
						</tr>
						<tr>
						<tr>
							<td width="" align="right" class="padr5">&nbsp;</td>
                                                        <td align="center" width="5%"></td>
							<td width="" align="left">
								<input type ="submit" value="Save" class="btn" id = "reason_type_save" onclick = "return click_reason_type_save(1)">
								<input type ="submit" value="Save" class="btn" id = "reason_type_update" style = "display:none;" onclick = "return click_reason_type_save(3)">
								<input type ="submit" value="cancel" class="btn" id="reason_type_cancel" onclick = "return click_reason_type_save(2)">
							</td>
						</tr>
							<td width="" align="center" class="padr5">&nbsp;</td>
						</tr>
					</table>
				</form>
				</td>
				<td>
				<!-- reason table starts here -->
				<table width="64%" border="0" align="left" cellpadding="0" cellspacing="0">
				<tr>
				
					<td width="100%" valign="top">
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin"></td> 
								<td class="tabmin2">
									<div align="left" class="padl5 txtredbold" style="width:350px; margin:auto;">Reasons Search: <input type="text" id = "search" name = "search" class = "txtfield" disabled />
									<input type = "submit" value ="search" class = "btn" id = "go_search" name = "go_search" disabled>
									</div></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
						<td>
								<tr>
									<td valign="top" class="bgF9F8F7">
									</td>
								</tr>
								<tr>
									<td class="bgF9F8F7">
										<div class="scroll_300">
											<table width="100%" id = "dynamic_reason_type"  border="0" cellpadding="0" cellspacing="1">
													 <tr align="center" id = "">
														   <td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Type Name</div></td>
														   <td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>
													 </tr>
											</table>
										</div>
									</td>
								</tr>
							</td>	
						</table>
					<!-- reason table ends here -->
					</td>
					</tr>
				</table>
				</td>
				<tr>
					<td>&nbsp;</td>
				</tr>	
			</tr>
			</table>
			</td>
			</tr>
</table>