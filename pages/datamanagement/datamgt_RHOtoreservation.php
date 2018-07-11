<?php
include ("initialize.php"); //start of database connections
global $database;
	
?>

<style>
	// table,th,tr,td { border : 1px solid black ; border-collapse : collapse }
	 .trheader td{font-weight:bold; text-align:center; border-right:1px solid #ffa3e0; background:#ffdef0; padding:3px; border-bottom : 1px solid #FF99FF;}
	 .trcontent td{ text-align:center; background:#ffdef0; padding:3px; background:#FFFFFF}
	 .tr_res_description { font-weight:bold ; color : #ffffff ; text-align:left; border-right:1px solid #ffa3e0; background : #F7B9DA; }
	 .tr_res_description td { color : #383838;}
	 
	 .trcontent td {text-align:center; border:1px solid #ffa3e0; padding:4px;}
	 
     //.tbhead {background:#F66FF;}
	 //.tdleft td {text-align:left; border:1px solid #ffa3e0; padding:3px;}
	 
	 .scroll_350 {
					width:100%;
					height:350px;
					overflow:auto; 
					color:scrollbar-base-color:#eeeeee; 
					scrollbar-face-color:#dddddd; 
					scrollbar-arrow-color:#333333; 
					scrollbar-3dlight-color:#eeeeee; 
					scrollbar-darkshadow-color:#aaaaaa; 
					scrollbar-highlight-color:#ffffff; 
					scrollbar-shadow-color:#ffffff;
					}

</style>


<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery-ui-1.10.0.custom.min.js"></script>

<script type="text/javascript" src="js/popinbox.js"></script>
<script type="text/javascript" src="js/jxrhotoreservation.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0" >
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">  <!--this will dispaly na vaigation menu on the left-->
			
				<?PHP
					include("nav.php");
				?>
				<br>
		</td>
	
		<td class="divider">&nbsp;</td> <!--divider for the left menu and the main window-->
		
		
		<td valign="top" style="min-height: 610px; display: block">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management </span></td>
					</tr>
				</table>

				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td class="mid_top">&nbsp;</td>
					</tr>
					<tr>
					  <td class="mid2_top">
					  <table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">
						  <tr>
							<td><span class="txtgreenbold13">RHO/STA to Reservation Converter</span></td>
							<td>&nbsp;</td>
						  </tr>
					   </table>
					  </td>
					</tr>
				 </table>
		
		
		
				<table width="95%"  border="0" cellspacing="0" cellpadding="0" >
				  <tr>
					<td valign="top">
						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
					 
						</table>

						
					 <div style="float:left; width:540px;padding-left:10px">
						<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" >
							 
						
							  <tr>
								
								<td width="100%" valign="top">
								
										 <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="1">
												<tr>
												  <td align="center" id="progressbar">&nbsp;</td>
												</tr>
										 </table>
										 
										 
										
										 
										 
										 <table width="60%"  border="0" align="left" cellpadding="0" cellspacing="0" >
												<tr>
													  <td class="cornerUL"></td>
													  <td class="corsidesU"></td>
													  <td class="cornerUR"></td>
												</tr>
											 
											
												<table width="100%"  border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td class="tabmin">&nbsp;</td>
														<td class="tabmin2"><span class="txtredbold">Select File </span>&nbsp;</td>
														<td class="tabmin3">&nbsp;</td>
													</tr>
												</table>
												 
												
			<!-- start of form ------------------------------------------------------------>															
																			
												<form name="formPrompt" method="post" enctype="multipart/form-data" >
												
												<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bordergreen" >

													
													<tr>
													  <td width="20%" height="20" class="bgF9F8F7">&nbsp;<span class="txtwhitebold">&nbsp;</span></td>
													  <td width="80%" height="20" class="bgF9F8F7">&nbsp;</td>
													</tr>
												
													<tr>
													
													
													</tr>
													
													<tr>
														<td class="fieldlabel" align="left">&nbsp;Movement Type&nbsp;:</td>
														<td>
															<select name="movementtype" class="txtfield">
																<option value="1" Selected>RHO</option>;
																<option value="2">STA</option>;
															</select>
														</td>
												
													</tr>
													
													<tr>
														<td>
														&nbsp;
														</td>
													</tr>
													
													<tr>
													
													  <td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10">File:</div></td>
													  <td height="20" class="bgF9F8F7">&nbsp;
														<?php  ?><input type="hidden" name="MAX_FILE_SIZE" value="1073741824" /><?php ?>
														<input type="file" name="file" class="btn"></td>

													</tr>
												  
													
													<tr>
													  <td height="20" class="bgF9F8F7">&nbsp;</td>
													  <td height="20" class="bgF9F8F7">&nbsp;
														<input type="hidden" name="page" value="1">
														<input type="hidden" name="fileid" value="1" id="xxx">
														<input class="btn" type="submit" name="btnSearch" value="Submit" >
														<!--<input class="btn" type="submit" name="btnSearch" value="Submit">-->
														
														<!-- <input type="checkbox" name="sortbydue" value="duedate">Sort by Due Date</input> -->
														
														
														<!--<input name="btnUpload" type="submit" class="btn" value="Upload" ></td> /////////////atoy--> 
													</tr>	
													<tr>
														<td colspan="3" class="bgF9F8F7" height="20">&nbsp;</td>
													</tr>						
												  </table>
												  </form>


        <!------------------------end of form---------------------------------------------------->


							</tr>			
													
					</table>
												  
					</table	>
					</div>
						
					<br>	
					
					<br>
					
					<!-- <div class="scroll_300" id="tabledel">
					</div>	-->
					
	<!-- this is the result div				
					<div style="float:left; width:1100px;padding-left:20px; align:left">
						<table width="100%" border="0" align="left" cellpadding="0" cellspacing="1" >
					
								 <tr>
									<td>
							 				<table width="100%" align="left" border="0" cellpadding="0" cellspacing="0" >
													<tr>
														<td class="tabmin">&nbsp;</td>
														<td class="tabmin2"><span class="txtredbold">Result(s)</span>&nbsp;</td>
														<td class="tabmin3">&nbsp;</td>
													</tr>
											</table>
									</td>			
								 </tr>
									
								
								<tr> 
									
									<td>
										<div class="scroll_350" id="tabledel">
									
											<div class="pgLoading">	
								
								
												<table   width="100%"  cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ; " >  
													<tr class="tr_res_description" >   
														<td width="170" > 
															Reservation No :        
														</td> 
														<td width="100">
															Branch :            
														</td> 
														<td width="300"> 
															Date : 		   
														</td> 
													</tr> 
												</table>
												
												<table 	width="100%" cellpadding="0" cellspacing="0" class="bordersolo" style="border: solid 1px #FF00CC;border-top:none; " >
													
													<tr class="trheader" height="20px" >
															<td width="3%">Line Number</td>
															<td width="3%">Reservation Number</td>
															<td width="3%">Item Code</td>
															<td width="20%">Description</td>
															<td width="1%">Quantity</td>
													</tr> 
													
													<tr height="20px">
															<td colspan="5" align ="center">No Record(s) Found</td>
													</tr> 
													
												</table>
															
									</div>	
									</div>		
								 </td>	
													 
								</tr>	

					</table	>
					
					</div>
	
-->	
					<br>  <!--separator between     -->
					<br>


<!--
					
					<div <div  style="text-align:center;float:left; width:1000px;padding-left:20px;">
					<table  align="center">
						<tr>
							<td>
								<input name="btnPrint" type="button" class="btn" value="Print" onclick="return printreport();">
							</td>
						</tr>

					</table>	
					
					</div>
					
-->

							
									<div style="clear:left; width:1000px;padding-left:20px; align:left">			
									
									<tr>
									  <td class="cornerBL"></td>
									  <td class="corsidesB"></td>
									  <td class="cornerBR"></td>
									</tr>
									</div>
					
		 <div style="clear:both;"></div>
		
		</td>
	   </tr>
		<div id="dialog-message" style='display:none;'>
			<p></p>
	   </div>	   

</table>  
 
 

 
 
 
 


