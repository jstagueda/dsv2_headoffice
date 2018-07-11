
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<script type="text/javascript" src="js/popinbox.js"></script>
<script type="text/javascript" src="js/jxgrossmarginreport.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">



<?php include IN_PATH.DS."scgrossmarginreport.php";?>



<style>
    .formwrapper{border:1px solid #FF00A6; border-top:none; padding:10px; font-weight: bold;}
    .tablelisttr td{padding:5px; text-align:center; font-weight: bold; border-left:1px solid #FFA3E0;}
    .tablelisttr{background: #FFDEF0;}
    .tablelisttable{width:100%;}
    .listtr td{border-top:1px solid #FFA3E0; border-left:1px solid #FFA3E0; padding:5px;}
    .ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
    .ui-widget-overlay{height:130%;}
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
			<!-- toolbar -->
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">
                    <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                        <tr>
                            <td width="70%" align="right">&nbsp;
                                <a href="javascript:void(0);" onclick="return leavepage(18);" class="txtblueboldlink">Leave Page</a>
                                |
                                <a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a>
                            </td>
                        </tr>
                    </table>
                    </td>
                </tr>
            </table>		
            <br />
			
           <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
					<!--- title --->
                    <td class="txtgreenbold13">Gross Margin Report</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <br />
            
            <div style="width:95%; margin:0 auto;">
                <div style="float:left; width:540px;">
                    <div class="tbl-head-content-left tbl-float-inherit"></div>
                    <div class="tbl-head-content-center tbl-float-inherit" style="width: 530px;">
                        <span>Action</span>
                    </div>
                    <div class="tbl-head-content-right tbl-float-inherit"></div>
                    <div class="tbl-clear"></div>
                    <div class="formwrapper">
						
                        <form action="" method="post" name="formPrompt">
                        <table width="100%">
							
							<tr>
								<td class="fieldlabel">Report Type</td>
								<td class="separator">:</td>
								<td>								
									<select class="txtfield" name="reporttype">
										<option value="0">Per Product</option>
										<option value="1">Per Product Line</option>
										<option value="2">Per Day</option>

									</select>
								</td>	
							</tr>							


							<tr>
							<td class="fieldlabel" >Display</td>
								<td class="separator">:</td>
								<td>
	
								
									<!-- <input TYPE="radio" NAME="summaryyes" VALUE="1" id="summaryyes" checked>
										<label FOR="summaryyes">Summary</label>
										&nbsp;
									<input TYPE="radio" NAME="summaryno" VALUE="0" id="summaryno"  
										>
										<label FOR="summaryno">Detailed</label> -->
										<input type="radio" name="summary" value="1" id="summary" checked> Summary</input>
										<input type="radio" name="summary" value="0" id="summary"> Detail</input>
								</td>
	
							</tr>
							
				
							<!--- inclusive dates --->
							<tr>
								<td class="fieldlabel">Date Range</td>
								<td class="separator">:</td>
								<td>
									<input type="text" name="FromDate" value="<?=date('m/01/Y')?>" class="txtfield">
									-
									<input type="text" name="ToDate"   value="<?=date('m/d/Y')?>" class="txtfield">
								</td>
                            </tr>
							<tr>
							  <td colspan='2'></td>
							  <td>(e.g. MM/DD/YYYY)</td>
							</tr>
							
							<tr>
								<td class='fieldlabel'><b>Item Code</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="item" value="" class="txtfield">
									<input name="itemid" type="hidden"  value="" class="txtfield">
									<input name="itemname" type="hidden"  value="" class="txtfield">
									<input name="itemcode" type="hidden"  value="" class="txtfield">
							</tr>

							<tr>
								<td class='fieldlabel'><b>Product Line</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="productline" value="" class="txtfield">
									<input name="productlineid" type="hidden"  value="" class="txtfield">
									<input name="productlinename" type="hidden"  value="" class="txtfield">
									<input name="productlinecode" type="hidden"  value="" class="txtfield">
							</tr>

							<tr>
								<td class='fieldlabel'><b>Product Category</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="productcategory" value="" class="txtfield">
									<input name="productcategoryid" type="hidden"  value="" class="txtfield">
									<input name="productcategoryname" type="hidden"  value="" class="txtfield">
									<input name="productcategorycode" type="hidden"  value="" class="txtfield">
							</tr>							
							

							<tr>
								<td class="fieldlabel">Product Line Grouping</td>
								<td class="separator">:</td>
								<td>						

									<select class="txtfield" name="plgrouping" id="plgrouping">
									<option value="0">NONE</option>
								<?php global $database;
									$pmg = $database->execute("SELECT codevalue,description,value FROM codemaster WHERE MnemonicCode='PLGRPNG'");
									if($pmg->num_rows)
									{
										while($res = $pmg->fetch_object())
											{     
									?>
												<option value="<?php echo $res->value;?>"> <?php echo $res->codevalue.'-'.$res->description; ?> </option>
									<?php			
											}										
									
									}			
								
									  ?>	
																
									
									<!--	<option value="0">Per Product</option>
										<option value="1">Per Product Line</option>
										<option value="2">Per Day</option> -->

									</select>
								</td>	
							</tr>							
							
							<tr>
								<td class="fieldlabel">PMG</td>
								<td class="separator">:</td>
								<td>						

									<select class="txtfield" name="pmgtype" id="pmgtype">
									<option value="0">ALL</option>
								<?php global $database;
									$pmg = $database->execute("SELECT codevalue,description,value FROM codemaster WHERE MnemonicCode='PRDPMG'");
									if($pmg->num_rows)
									{
										while($res = $pmg->fetch_object())
											{     
									?>
												<option value="<?php echo $res->value;?>"> <?php echo $res->codevalue.'-'.$res->description; ?> </option>
									<?php			
											}										
									
									}			
								
									  ?>	
																
									
									<!--	<option value="0">Per Product</option>
										<option value="1">Per Product Line</option>
										<option value="2">Per Day</option> -->

									</select>
								</td>	
							</tr>




						
							<!--- product --->
							<tr>
								<td class='fieldlabel'><b>Brand</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="brand" value="" class="txtfield">
                                    <input name="brandid" value="0" type="hidden" class="txtfield">
									<input name="brandname" value=""  type="hidden" class="txtfield">
									<input name="brandcode" value=""  type="hidden" class="txtfield">
								</td>
							</tr>

							<tr>
								<td class='fieldlabel'><b>SubBrand</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="subbrand"       value=""                class="txtfield">
                                    <input name="subbrandid" value="0" type="hidden" class="txtfield">
									<input name="subbrandname"   value=""  type="hidden" class="txtfield">
									<input name="subbrandcode"   value=""  type="hidden" class="txtfield">
								</td>
							</tr>

							<tr>
								<td class='fieldlabel'><b>Form</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="form" value="" class="txtfield">
                                    <input name="formid" value="0" type="hidden" class="txtfield">
									<input name="formname" value=""  type="hidden" class="txtfield">
									<input name="formcode" value=""  type="hidden" class="txtfield">
								</td>
							</tr>							
							
							<tr>
								<td class='fieldlabel'><b>Sub Form</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="subform" value="" class="txtfield">
                                    <input name="subformid" value="0" type="hidden" class="txtfield">
									<input name="subformname" value="" type="hidden" class="txtfield">
									<input name="subformcode" value="" type="hidden" class="txtfield">
								</td>
							</tr>

							<tr>
								<td class='fieldlabel'><b>Size</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="size" value="" class="txtfield">
                                    <input name="sizeid" value="0" type="hidden" class="txtfield">
									<input name="sizename"   value=""  type="hidden" class="txtfield">
									<input name="sizecode"   value=""  type="hidden" class="txtfield">
								</td>
							</tr>		

							<tr>
								<td class='fieldlabel'><b>Branch</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="branchz" value="" class="txtfield">
                                    <input name="branchid" value="0" type="hidden" class="txtfield">
									<input name="branchname"   value=""  type="hidden" class="txtfield">
									<input name="branchcode"   value=""  type="hidden" class="txtfield">
								</td>
							</tr>	
							
<!--
							<tr>
								<td class='fieldlabel'><b>Sort By</b></td>
								<td class="separator">:</td>
								<td align="left">
									<input name="sortby" value="" class="txtfield">
								</td>
							</tr>

-->		
<!--					
							<tr>
								<td class="fieldlabel">Sort By</td>
								<td class="separator">:</td>
								<td>						

									<select class="txtfield" name="sortby" id="sortby">
									<option value="0">ALL</option>
									<?php global $database;
									$pmg = $database->execute("SELECT codevalue,description,value FROM codemaster WHERE MnemonicCode='PRDPMG'");
									if($pmg->num_rows)
									{
										while($res = $pmg->fetch_object())
											{     
									?>
												<option value="<?php echo $res->value;?>"> <?php echo $res->codevalue.'-'.$res->description; ?> </option>
									<?php			
											}										
									
									}			
								
									  ?>	


									</select>
								</td>	
							</tr>							
							
-->							
							
							
                            <tr>
                                <td colspan="3" align="center">
                                    <br />
                                    <input type="hidden" name="page" value="1">
                                    <input class="btn" type="submit" name="btnSearch" value="Submit">
                                </td>
                            </tr>
                        </table>
                        </form>
						
                    </div>
                </div>
                
                <div style="clear:both;">&nbsp;</div>
                <div class="loader" style="text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
                    Summary
                </div>
                
                <div style="width:100%; min-height:275px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablelist">
                        <tr>
                            <td class="tabmin">&nbsp;</td>
                            <td class="tabmin2">
                                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                                    <tr>
                                        <td class="txtredbold"><b>Result(s)</b></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="tabmin3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="pgLoading">
                                    <table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" 
									       border="0" cellspacing="0" cellpadding="0">
                                        <tr class="tablelisttr">
                                            <td>#</td>
											<td>Item Number</td>
											<td>Description</td>
                                            <td>Brand</td>
                                            <td>Sub Brand</td>
											<td>Form</td>
											<td>Sub Form</td>
											<td>Size</td>
											<td>Units</td>
											<td width = 8%>NSV</td>
											<td>Gross Profit</td>
											<td width = 10% >%Margin</td>	
                                        </tr>
                                        <tr class="listtr">
                                            <td align="center" colspan="13">No result found.</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="text-align:center;">
                    <input class="btn" type="button" value="Back" name="btnBack" onclick="location.href='?pageid=18'">
                    <input class="btn" type="button" value="Print" name="btnPrint">
                </div>
            </div>
            
            <div style="clear:both;"></div>
        </td>
    </tr>
</table>
<br>

<!--Added by joebert-->
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<!--end-->
