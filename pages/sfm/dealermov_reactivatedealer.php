<?PHP 
    include IN_PATH.DS."scInactiveCustomer.php";
    include IN_PATH.DS."pcReactivateDealer.php";
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmReactivateDealer.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

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
    <td class="txtgreenbold13">Dealer Reactivation</td>
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
    	<form name="frmSearchDealer" method="post" action="index.php?pageid=75">
    		<input type="hidden" name="hCustID" value="<?php echo $custID; ?>">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  	<tr>
		    	<td>
		    		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		        	<tr>
		          		<td width="20%" height="10">&nbsp;</td>
		          		<td width="80%" height="10">&nbsp;</td>
		        	</tr>
		         	<tr>
		          		<td height="20" align="right" class="padr5">IBM Code :</td>
				   		<td height="20" align="left" class="padl5"><input name="txtfldIBMCodeSearch" type="text" class="txtfield" id="txtIBMCodeSearch" size="20" value="<?php echo $ibmcode; ?>"></td>
	        		</tr>
		        	<tr>
		          		<td height="20" align="right" class="padr5">Search :</td>
				   		<td height="20" align="left" class="padl5">    
					  		<input name="txtfldsearch" type="text" class="txtfield" id="txtSearch" size="20" value="<?php echo $dealersearch; ?>">
					  		&nbsp;
					  		<input name="btnSearch" type="submit" class="btn" value="Search">
			  			</td>
	        		</tr>
		        	<tr>
		          		<td height="10">&nbsp;</td>
		          		<td height="10">&nbsp;</td>
		        	</tr>
		    		</table>
	    		</td>
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
		                <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="tab txtdarkgreenbold10">
		                    <tr align="center">
		                      <td width="40%"><div align="left" class="bdiv_r padl5">Code</div></td>
		                      <td width="60%"><div align="left" class="padl5">Name</div></td>
		                </table></td>
		              </tr>
		              <tr>
		                <td class="bordergreen_B"><div class="scroll_300">
                        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
						<?PHP
						if ($rs_customer->num_rows)
						{
							while ($row = $rs_customer->fetch_object())
							{
							   echo "<tr align='center'>
								  <td width='40%' height='20' class='borderBR' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
								  <td width='60%' class='borderBR' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=75&custid=$row->ID' class='txtnavgreenlink'>".$row->FirstName." ".$row->MiddleName." ".$row->LastName." </a></span></td>
								</tr>";
							}
							$rs_customer->close();
						}
						else
						{
							echo "<tr align='center'>
								  <td height='20' colspan='2' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td>
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
                      <div id="tblPageNavigationInline">
                          <?php echo tpi_simplePaginationArrowed($total_pages, $page, $otherPagingConcat); ?>
                      </div>
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
           <td class="bgF9F8F7"><?php 
           				/*if (isset($_GET['msg']))
           				{
                            $message = strtolower($_GET['msg']);
                            $success = strpos("$message","success"); 
                            echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
                        }
                        else if (isset($_GET['errmsg']))
                        {
							$errormessage = strtolower($_GET['errmsg']);
							$error = strpos("$errormessage","error"); 
							echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";                        	
                        }*/
                         ?></td>
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
                <td height="30" width="60%"><?PHP if(isset($_GET['custid'])) { echo $code; }; ?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10"> Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?PHP if(isset($_GET['custid'])) { echo $fname.' '.$mname.' '.$lname; };?></td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Date Terminated:</td>
                <td height="30">&nbsp;</td>
                <td height="30"><?PHP if(isset($_GET['custid'])) { echo $date_terminated; }?></td>
                <input type="hidden" value="<?php echo date('Y-m-d',strtotime($date_terminated)); ?>" name="date_terminated" />
            </tr>
            
            <tr>
              <td height="30" align="right" class="txt10">Past Due Amount :</td>
              <td height="30">&nbsp;</td>
              <td height="30"><?PHP if(isset($_GET['custid'])) { echo number_format($pastDue,'2','.',''); }?> </td>
            </tr>
            <tr>
              <td height="30" align="right" class="txt10">Penalty :</td>
              <td height="30">&nbsp;</td>
              <td height="30"><?PHP if(isset($_GET['custid'])) { echo number_format($penalty,'2','.',''); }?> </td>
            </tr>
            <tr>
              <td height="30" align="right" class="txt10">Writeoff :</td>
              <td height="30">&nbsp;</td>
              <td height="30"><?PHP if(isset($_GET['custid'])) { echo number_format($writeoff,'2','.',''); }?> </td>
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
           <td class="tabmin2"><span class="txtredbold">Reactivation Details</span>&nbsp;</td>
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
                <td height="30" align="right" class="txt10">Old IBM No. :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><input type="text" name="txtOldIBMNo" maxlength="50" size="40" class="txtfield" style="width:250px;" readonly="yes" value="<?PHP if(isset($_GET['custid'])) { echo $old_ibmcode; };?>" />
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Old IBM Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><input type="text" name="txtOldIBMName" maxlength="50" size="40" class="txtfield" style="width:250px;" readonly="yes" value="<?PHP if(isset($_GET['custid'])) { echo $old_ibmname; };?>"/>
            </tr>
            <?php 
                //Show this option when there's selected dealer to reactivate...
                if($show_update){ 
            ?>
            <tr id="selected-IBMName"></tr>
            <tr>
                <td height="30" align="right" class="txt10">New IBM No. :</td>
                <td height="30">&nbsp;</td>
                <td height="30">
                        <input type="hidden" name="cboIBMNetwork" id="cboIBMNetwork" value="" />
                        <a href="javascript:void(0);" id="reactivate-select-network">Click to view lists for new IBM selection</a>
                        <?php /*?>
                	<select name="cboIBMNetwork" class="txtfield" style="width:250px;">
                		<option value="0">[SELECT HERE]</option>
                		<?php
                			if ($rs_cboIBMNetwork->num_rows)
						    {
						     	while ($row = $rs_cboIBMNetwork->fetch_object())
						      	{
						      		$cust = $row->Code."-".$row->Name;
						      		echo "<option value='$row->ID'>$cust</option>";
						       	}
						    }
                		?>
                	</select><?php */?>
                </td>
            </tr>
            <?php } ?>
            <!--<tr>
              <td height="30" align="right" class="txt10">New IBM Name</td>
              <td height="30">&nbsp;</td>
              <td height="30">
                <input name="txtNewIBMName" id="txtNewIBMName" type="text" class="txtfield" size="30">
              </td>
            </tr>-->
            <tr>
                <td height="30" align="right" class="txt10"></td>
                <td height="30"></td>
                <td height="30"></td>
            </tr>
            </table>		
        </td>
         </tr>
       </table>
       <?php 
            //Show this option when there's selected dealer to reactivate...
            if($show_update){ 
       ?>
       <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  <br>
	  <tr>
	    <td align="center">
	    	<input name="btnUpdate" type="submit" class="btn" value="Update" onclick="return confirmUpdate();">
	    	<input name="btnCancel" type="submit" class="btn" value="Cancel" onclick="return confirmCancel();" >
	    </td>
	  </tr>
	</table>
        <?php } ?>
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

