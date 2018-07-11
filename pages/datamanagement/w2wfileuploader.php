<?php
#include ("initialize.php"); //start of database connections
#global $database;
$sessionUniqueID = uniqid();
$datetoday = date("m/d/Y");

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


<?php
if (!isset($_GET['msglog']))
{
   unset($_SESSION['msg_log']);		
}
?>
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery-ui-1.10.0.custom.min.js"></script>

<script type="text/javascript" src="js/popinbox.js"></script>
<script type="text/javascript" src="js/jxw2wfilesuploader.js?rand=<?php echo $sessionUniqueID?>"></script>

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
							<td><span class="txtgreenbold13">Inventory Count Data File Loading</span></td>
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
					 <div style="float:left; width:640px;padding-left:10px">
						<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" >
							  <tr>
								<td width="100%" valign="top">
								
										 <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
												<tr>
												  <td align="center" id="progressbar">&nbsp;</td>
												</tr>
										 </table>
										 
										 <table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0" >
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
														 <td height="20" class="bgF9F8F7" align="Right">FREEZE DATE &nbsp;:</td>
														 <td height="20" class="bgF9F8F7" align="Left" colspan = 3>&nbsp;
															 <input name="txtStartDate" type="text" class="trheader" style = "font-weight: bold; font-size: 200%; " id="txtStartDate" size="20" readonly="yes" value="<?php echo $datetoday ?>">
															 <input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
															 <div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
														 </td>				 
													</tr>
														
													<tr>
														<td height="20" class="bgF9F8F7" align="Right" >Branch &nbsp;:</td>
														<td height="20" class="bgF9F8F7">&nbsp;
															<input name="branchName" value="" class="trheader" >
														    <input name="branch" value="0" type="hidden" class="txtfield">
														</td>
													</tr>
														
													<!--<tr>
													     <td height="20" class="bgF9F8F7" align="Right">DATE:</td>
														 <td height="20" class="bgF9F8F7" align="Left" colspan = 3>
															 <input name="txtEndDate" type="text" class="trheader" style = "font-weight: bold; font-size: 200%; " id="txtEndDate" size="20" readonly="yes" value="<?php echo $datetoday ?>">
															 <input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
															 <div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
														 </td>				 
													</tr> -->
													
													<tr>
													  <td width="20%" height="20" class="bgF9F8F7">&nbsp;<span class="txtwhitebold">&nbsp;</span></td>
													  <td width="80%" height="20" class="bgF9F8F7">&nbsp;</td>
													</tr>
													
													<tr>
													  <td width="20%" height="20" class="bgF9F8F7" style = "font-weight: bold; font-size: 160%; " colspan = 3 >&nbsp;&nbsp;&nbsp;Select LOS File &nbsp;<span class="txtwhitebold">&nbsp;</span></td>
													</tr>
													<tr>
													
													  <td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10"></div></td>
													  <td height="20" class="bgF9F8F7" colspan = 2>&nbsp;
														<?php  ?><input type="hidden" name="MAX_FILE_SIZE" value="1073741824" /><?php ?>
													    	<input type="file" name="file" class="btn">
													  </td>
													</tr>
													
													<tr>
													  <td width="20%" height="20" class="bgF9F8F7" style = "font-weight: bold; font-size: 160%; " colspan = 3 >&nbsp;&nbsp;&nbsp;Select JDE File &nbsp;<span class="txtwhitebold">&nbsp;</span></td>
													</tr>
													<tr>
													  <td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10"></div></td>
													  <td height="20" class="bgF9F8F7">&nbsp;
														<?php  ?><input type="hidden" name="MAX_FILE_SIZE" value="1073741825" /><?php ?>
														<input type="file" name="file2" class="btn">
													  </td>
													</tr>
												  
													<tr>
													  <td width="20%" height="20" class="bgF9F8F7">&nbsp;<span class="txtwhitebold">&nbsp;</span></td>
													  <td width="80%" height="20" class="bgF9F8F7">&nbsp;</td>
													</tr>
													
													<tr>
													  <td height="20" class="bgF9F8F7">&nbsp;</td>
													  <td height="20" class="bgF9F8F7">&nbsp;
														<input type="hidden" name="page" value="1">
														<input type="hidden" name="fileid" value="1" id="xxx">
														<input class="btn" type="submit" name="btnSearch" value="Submit" >
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
					
					<div style="float:left; width:1100px;padding-left:20px; align:left">
						<table width="80%" border="0" align="left" cellpadding="0" cellspacing="1" >
					           <div class="loader" style="display:block; text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
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
									</div>	
									</div>		
								 </td>	
													 
								</tr>	

					</table	>
					
					</div>
					
					<br>  <!--separator between     -->
					<br>
					
					<div <div  style="text-align:center;float:left; width:1000px;padding-left:20px;">
				
					<table  align="center">
						<tr>
							<td>
								<!--<input name="btnPrint" type="button" class="btn" value="Print" onclick="return printreport();">-->
							</td>
						</tr>

					</table>	
					
					</div>
							
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
</table>  
 
 <script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID) bmfdate
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>

</script>
  

 
 
 
 


