<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 10, 2013
 *
 * 
 *
 * @modified by: joebert italia
 * @date: May 8, 2014
 *
 */
 
 function getReasonCodes(){
	
	global $database;
	$query = $database->execute("SELECT ID, ReasonName `Name` FROM ordmcmreasons WHERE IsNotORorDMCM = 1 ORDER BY ReasonName");
	return $query;
	
 }
 
?>

<style>
	.fieldlabel{width:35%; text-align:right; font-weight:bold;}
	.separator{width:5%; text-align:center;}
	.trheader td{padding:5px; border-right:1px solid #FFA3E0; border-bottom:1px solid #FFA3E0; background:#FFDEF0; font-weight:bold; text-align:center;}
	.trlist td{padding:5px; border-bottom:1px solid #FFA3E0; border-right:1px solid #FFA3E0;}
</style>

<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css"/>
<script language="javascript" src="js/jquery-1.9.1.min.js"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<script type="text/javascript" src="js/bpm/jquery.MemoRegister.js"></script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("bpm_left_nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top" style="min-height: 610px; display: block;">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Memo Register</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
						<br />
						<div style="width:98%; margin:auto;">
							<div style="width:40%;">
								<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td class="tabmin"> </td>
										<td class="tabmin2">
											Action
										</td>
										<td class="tabmin3"> </td>
									</tr>
								</table>
								<div class="bordersolo" style="border-top:none; padding:10px;">
									<form name="formMemoRegister" method="POST" action="">
										<table width="100%" cellspacing="0" cellpadding="1" border="0">
											<tr>
												<td class="fieldlabel">Branch</td>
												<td class="separator">:</td>
												<td>
													<input class="txtfield" id="branch" name="branchList">
													<input type="hidden" name="branch" value="0">
												</td>
											</tr>
											<tr>
												<td class="fieldlabel">Reason Code</td>
												<td class="separator">:</td>
												<td>
													<select id="reason" name="reason" class="txtfield">
														<option value="0">ALL</option>
														<?php
															$DMCMReasons = getReasonCodes();
															if($DMCMReasons->num_rows){
																while($reason = $DMCMReasons->fetch_object()){
														?>
																<option value="<?php echo $reason->ID; ?>"><?=$reason->Name?></option>
														<?php
																}
															}
														?>
													</select>
												</td>
											</tr>
											<tr>
												<td class="fieldlabel">Date From</td>
												<td class="separator">:</td>
												<td>
													<input class="txtfield" name="datefrom" type="text" id="datefrom" value="<?=date('m/d/Y')?>" />
													<i>(e.g. MM/DD/YYYY)</i>
												</td>
											</tr>
											<tr>
												<td class="fieldlabel">Date To</td>
												<td class="separator">:</td>
												<td>
													<input class="txtfield" name="dateto" type="text" id="dateto" value="<?=date('m/d/Y')?>" />
													<i>(e.g. MM/DD/YYYY)</i>
												</td>
											</tr>
											<tr>
												<td colspan="2"></td>
												<td>
													<input type="button" value="Search" class="btn" name="btnSearch">
													<input type="button" value="Cancel" name="btnCancel" class="btn">
													<input type="hidden" value="1" name="page">
												</td>
											</tr>
										</table>
									</form>
								</div>
							</div>
						</div>
												
						<div class="tbl-clear clear-small"></div>
						
						<div style="width:98%; margin:auto;">
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td class="tabmin"> </td>
									<td class="tabmin2">
										Result(s)
									</td>
									<td class="tabmin3"> </td>
								</tr>
							</table>
							
							<div class="PageLoad">
								<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="bordersolo" style="border-top:none;">
									<tr class="trheader">
										<td>Transaction Date</td>
										<td>DM/CM No.</td>
										<td>Transaction No.</td>
										<td>Customer Code</td>
										<td>Name</td>
										<td>Debit</td>
										<td>Credit</td>
										<td>Remarks</td>
									</tr>
									<tr class="trlist">
										<td colspan="8" align="center">No result found.</td>
									</tr>
								</table>
							</div>
							
							<div style="text-align:center; margin-top:10px;">
								<input type="button" value="Print" name="btnPrint" class="btn">
								<input type="button" value="Back" name="btnBack" class="btn" onclick="location.href = '?pageid=203';">
							</div>
						</div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>