<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<?PHP 
	include IN_PATH.DS."scCreateIBMAffiliation.php";
	include IN_PATH.DS."pcCreateIBMAffiliation.php";

	//display error message
	$errMessage = '';
	if (isset($_GET['msg']))
    {
       $message = strtolower($_GET['msg']);
       $success = strpos("$message","success"); 
       $errMessage= "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
    }
    else if (isset($_GET['errmsg']))
    {
		$errormessage = strtolower($_GET['errmsg']);
		$error = strpos("$errormessage","error"); 
		$errMessage = "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";                        	
    }
	
	
	  //branch dropdown
     $drpBranch = '<option value=\'0\' >[SELECT HERE]</option>';
     if ($rs_cboBranch->num_rows)
      {
     while ($row = $rs_cboBranch->fetch_object())
      {  
      	$sel = '';
        $drpBranch .= "<option value='$row->ID'' $sel>$row->Name</option>";
       }
    }
?>
<script type="text/javascript">
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}
function form_validation()
{
	lang = 0;
	def = 0;
	count = 0;
	msg = '';
	str = '';
	obj = document.frmDealer.elements;
	
	// TEXT BOXES
	if (trim(obj["txtCodeEmployee"].value) == '') msg += '   * Code \n';

	if (msg != '')
	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else return true;
}	
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav_dealer.php");
		?>
      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Data Management </span></td>
        </tr>
    </table>
    <br />
  
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Create IBM Affiliation</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
	if ($errmsg != "")
	{
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
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
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
    <form name="frmSearchDealer" method="post" action="index.php?pageid=79">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  <tr>
		    <td><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
		        <tr>
		          <td width="20%">&nbsp;</td>
		          <td width="10%" align="right">&nbsp;</td>
		          <td width="70%" align="right">&nbsp;</td>
		        </tr>
		         <tr>
		          <td>
					 IBM Code :          
				  </td>
				   <td></td>
				   <td>         
					 <input name="txtfldsearchIBMCode" type="text" class="txtfield" id="txtfldsearchIBMCode" value="<?php echo $ibmcode; ?>" size="20">
				  </td>
		        </tr>
		        <tr></tr>
		        <tr></tr>
		        <tr>
		          <td>
					 Search :
				  </td>
				   <td></td>
				   <td>    
					  <input name="txtfldsearch" type="text" class="txtfield" id="txtfldsearch" size="20" value="<?php echo $search; ?>">
					  &nbsp;
					  <input name="btnSearch" type="submit" class="btn" value="Search">
				  </td>
		        </tr>
		        <tr>
		          <td>&nbsp;</td>
		          <td align="right">&nbsp;</td>
		          <td align="right">&nbsp;</td>
		        </tr>
		    </table></td>
		  </tr>
		</table>
    </form>
		      <br>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td class="tabmin">&nbsp;</td>
		          <td class="tabmin2"><span class="txtredbold">Dealer List</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        <tr>
		          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              <tr>
		                <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		                    <tr align="center">
		                      <td width="40%" class="bdiv_r"><div align="center">&nbsp;<span class="txtredbold">Code</span></div></td>
		                      <td width="60%"><div align="center"><span class="txtredbold">Name</span></div></td></tr>
		                </table></td>
		              </tr>
		              <tr>
		                <td class="bordergreen_B"><div class="scroll_300">
                        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
						<?PHP
						if ($rsIBMList->num_rows)
						{
							$rowalt = 0;
							while ($row = $rsIBMList->fetch_object())
							{
								$rowalt += 1;
			 					($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
							   	echo "<tr align='center' class='$class'>
								  <td width='41%' height='20' class='borderBR' align='left'>&nbsp;<span class='txt10'>$row->IBMCode</span></td>
								  <td width='59%' class='borderBR' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=79&custid=$row->customerID' class='txtnavgreenlink'>$row->IBMName</a></span></td>
								</tr>";
							}
							$rsIBMList->close();
						}
						else
						{
							echo "<tr align='center'>
								  <td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td>
							    </tr>";
						}
						?>
		                  </table>
                          </div>
	                    </td>
		              </tr>
		          </table></td>
		        </tr>
		      </table>
	</td>
	<td width="2%">&nbsp;</td>
	<td width="60%" valign="top">
     <form name="frmDealer" method="post" action="" onsubmit="return form_validation();">
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Dealer Details</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         <tr>
           <td class="bgF9F8F7"><?php echo $errMessage; ?></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="30" align="right" class="txt10" width="35%">Code :</td>
                <td height="30" width="5%">&nbsp;</td>
                <td height="30" width="60%"><?PHP echo $code;?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10"> Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?PHP echo $name;?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Enrollment Date :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?PHP 
                $tmpenrolldate = strtotime($enrolldate);
                $date = $tmpenrolldate != '' ? date("m/d/Y", $tmpenrolldate) : '';
                echo $date;?></td>
            </tr>
             <tr>
              <td height="20" align="right" class="txt10"></td>
              <td height="20">&nbsp;</td>
              <td height="20">
              </td>
            </tr>
            </table>		
        </td>
         </tr>
       </table>
       <br>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Performance Report</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         <tr>
           <td class="bgF9F8F7"></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="30" align="left" class="txt10" width="30%">
				<table width="98%" border="0" align="center" cellpadding="0" cellspacing="1"  id="tbl2">
					<tr>
						<td valign="top" class="bgF9F8F7">
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
							<tr>

								<td class=" ">
								<table width="100%" border="0" cellpadding="0"	cellspacing="1" >
									<tr align="center">
										<td width="25%">
										<div align="center">&nbsp;</div>
										</td>										
									</tr>
									<tr>
										<td class="">
										<div style="height: 100px;">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr height="20">
													<td align="right">&nbsp;</td>
												</tr>
												<tr height="20">
													<td align="right" class="txt10" >Discounted Gross Sales: </td>
												</tr>
												<tr height="20">
													<td align="right" class="txt10" >Average of Recruit: </td>
												</tr>
												<tr height="20">
													<td align="right" class="txt10" >BCR: </td>
												</tr>
												<tr height="20">
													<td align="right" class="txt10" >Sales: </td>
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
					</tr>
				</table>
				</td>
                <td height="30" align="left">
                	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        <tr>
		     
		          <td valign="top" class="bgF9F8F7">
		          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              <tr>
		              	
		                <td class="tab bordergreen_T">
		                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		                    <tr align="center">
		                      <td width="25%"><div align="center">&nbsp;<span class="txtredbold">Month 1</span></div></td>
		                      <td width="25%"><div align="center"><span class="txtredbold">Month 2</span></div></td>
		                      <td width="25%"><div align="center">&nbsp;<span class="txtredbold">Month 3</span></div></td>
		                      <td width="25%"><div align="center"><span class="txtredbold">Average</span></div></td>
		                      </tr>
		                       <tr>
		                			<td colspan="4"><div style="height:100px;">
		                			<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr height="20">
											<td align="right">&nbsp;</td>
										</tr>
										<tr height="20">
											<?php 
											if(isset($customerID))
											{
												//GrossSales
												$totalDiscounted = 0;
												 for($i = 0 ; $i<=2 ;$i++)
												 {
												 	echo "<td align='center' width='25%'>";
												 	$txnDate = date("Y-m-d",strtotime("-".$i." Months"));
												 	
												 	$rsDiscountedSales = $sp->spSelectBCRbyIBMID($database,$customerID,$txnDate);
												 	if($rsDiscountedSales->num_rows)
												 	{
												 		while($row = $rsDiscountedSales->fetch_object())
												 		{
												 			$discountedAmount = $row->NetAmount; 												 			
												 			$totalDiscounted = $discountedAmount+ $totalDiscounted;
												 			echo number_format($discountedAmount,2,".",",") ;
												 		}
												 	}
												 	else
												 	{
												 			$discountedAmount = "0.00";
												 			echo   number_format($discountedAmount,2,".",",") ;
												 	}
												 	echo "</td>";
												 	
												 }
												 $tmpaverage = $totalDiscounted/3 ; 
												 $average = number_format($tmpaverage,2,".",",");
												echo" <td align='center' width='25%'>$average</td>";
											}	 
											?>
											
										</tr>
										<tr height="20">
											<?php 
											//Average Recruit
											if(isset($customerID))
											{
												$totalDiscounted = 0;
												 for($i = 0 ; $i<=2 ;$i++)
												 {
												 	echo "<td align='center' width='25%'>";
												 	$txnDate = date("Y-m-d",strtotime("-".$i." Months"));
												 	
												 	$rsDiscountedSales = $sp->spSelectCountRecruit($database,$customerID,$txnDate);
												 	if($rsDiscountedSales->num_rows)
												 	{
												 		while($row = $rsDiscountedSales->fetch_object())
												 		{
												 			$discountedAmount = $row->Recruited; 												 			
												 			$totalDiscounted = $discountedAmount+ $totalDiscounted;
												 			echo number_format($discountedAmount,0,".",",") ;
												 		}
												 	}
												 	else
												 	{
												 			$discountedAmount = "0.00";
												 			echo   number_format($discountedAmount,0,".",",") ;
												 	}
												 	echo "</td>";
												 	
												 }
												 $tmpaverage = $totalDiscounted/3 ; 
												 $average = number_format($tmpaverage,2,".",",");
												echo" <td align='center' width='25%'>$average</td>";
											}	 
											?>
											
										</tr>
										<tr height="20">
											<?php 
											//BCR
											if(isset($customerID))
											{
												$totalDiscounted = 0;
												 for($i = 0 ; $i<=2 ;$i++)
												 {
												 	echo "<td align='center' width='25%'>";
												 	$txnDate = date("Y-m-d",strtotime("-".$i." Months"));
												 	
												 	$rsDiscountedSales = $sp->spSelectBCRbyIBMID($database,$customerID,$txnDate);
												 	if($rsDiscountedSales->num_rows)
												 	{
												 		while($row = $rsDiscountedSales->fetch_object())
												 		{
												 			$discountedAmount = $row->NetAmount; 	
											 				$discountedAmount = "0.00";											 			
												 			$totalDiscounted = $discountedAmount+ $totalDiscounted;
												 			echo number_format($discountedAmount,2,".",",") ;
												 		}
												 	}
												 	else
												 	{
												 			$discountedAmount = "0.00";
												 			echo   number_format($discountedAmount,2,".",",") ;
												 	}
												 	echo "</td>";
												 	
												 }
												 $tmpaverage = $totalDiscounted/3 ; 
												 $average = number_format($tmpaverage,2,".",",");
												echo" <td align='center' width='25%'>$average</td>";
											}	 
											?>
											
										</tr>
										<tr height="20">
											<?php 
											if(isset($customerID))
											{
												$totalDiscounted = 0;
												 for($i = 0 ; $i<=2 ;$i++)
												 {
												 	echo "<td align='center' width='25%'>";
												 	$txnDate = date("Y-m-d",strtotime("-".$i." Months"));
												 	
												 	$rsDiscountedSales = $sp->spSelectBCRbyIBMID($database,$customerID,$txnDate);
												 	if($rsDiscountedSales->num_rows)
												 	{
												 		while($row = $rsDiscountedSales->fetch_object())
												 		{
												 			$discountedAmount = $row->GrossAmount; 												 			
												 			$totalDiscounted = $discountedAmount+ $totalDiscounted;
												 			echo number_format($discountedAmount,2,".",",") ;
												 		}
												 	}
												 	else
												 	{
												 			$discountedAmount = "0.00";
												 			echo   number_format($discountedAmount,2,".",",") ;
												 	}
												 	echo "</td>";
												 	
												 }
												 $tmpaverage = $totalDiscounted/3 ; 
												 $average = number_format($tmpaverage,2,".",",");
												echo" <td align='center' width='25%'>$average</td>";
											}	 
											?>
											
										</tr>
									</table>
		                			</div></td>
		                	</tr>
		                </table>
		                </td>
		              </tr>		              
		          </table></td>
		        </tr>
		      </table>
                </td>
            </tr>
            <tr>
            	<td height="20"></td>
            </tr>
            </table>		
        </td>
         </tr>
       </table>
       <br>
       <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Affiliation Details</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         <tr>
           <td class="bgF9F8F7"><?php echo $errMessage; ?></td>
         </tr>
         <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="30" align="right" class="txt10">Affiliated Branches :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?php echo substr($affiliatedbranch, 1, -1); ; ?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Branch Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><select name="cboBranchName" class="txtfield" style="width:180">
					<?php echo $drpBranch; ?>	
					 </select>&nbsp;</td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10" valign="top">Remarks :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><textarea rows="4" cols="30" name="txtRemarks" class="txtfieldnh"><?php echo $brremarks; ?></textarea></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10"></td>
                <td height="30"></td>
                <td height="30">
                </td>
            </tr>
            </table>		
        </td>
         </tr>
       </table>
      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  <br>
	  <tr>
	    <td align="center">
	    	<input name="btnSubmit" type="submit" class="btn" value="Submit">
	    	<input name="btnCancel" type="submit" class="btn" value="Cancel">
	    </td>
	  </tr>
	</table>
	<br>
    </form>
	</td>
  </tr>
</table>
	</td>
  </tr>
</table>
    </td>
  </tr>
</table>

