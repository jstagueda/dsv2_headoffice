<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxVwOfficialReceiptDetails.js"  type="text/javascript"></script>
<script language="javascript" src="js/sessionexpire.js"  type="text/javascript"></script>

<?php include(IN_PATH.DS.'scViewOfficialReceiptDetails.php'); ?>

<script type="text/javascript">
	function Trim(s)
  	{
		var l=0; var r=s.length -1;
		while(l < s.length && s[l] == ' ')
		{	
			l++; 
		}
		while(r > l && s[r] == ' ')
		{	
			r-=1;	
		}
		return s.substring(l, r+1);
  	}
	
	function NewWindow(mypage, myname, w, h, scroll) 
  	{
  		var winl = (screen.width - w) / 2;
  		var wint = (screen.height - h) / 2;
  		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no'
  		win = window.open(mypage, myname, winprops)
  		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
  	}

  var objWin;
	document.onkeydown = test;
	function setEvents()
		{
		      if (window.event)
		      {		           
		            document.onkeydown = test;
		      }
		     
		}
		
		function test(e)
		{
		    var keyId = (window.event) ? event.keyCode : e.keyCode;
		      //alert(keyId)
			
		    if(keyId == 116)
		    {	
			    var rep = String(window.location);	
			    var split = rep.split("&"); 
			        
		    	document.getElementById('hdncnt').value = 1;
		        window.location.href = split[0] +'&'+ split[1] + '&locked=1';
		      return false;
		    
		    }
		}
</script>



<style type="text/css">
<!--
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}
-->
</style>
<body onLoad="set_interval()" onmousemove="reset_interval()" onclick="reset_interval()" onkeypress="reset_interval()" onUnload="unlock_trans(<?php echo $_GET["TxnID"];?>,4);">
<form name="frmViewOfficialReceipt" method="post" action="index.php?pageid=96.1&TxnID=<?php echo $_GET["TxnID"];?> ">
<input type="hidden" name="hdncnt" id="hdncnt" value="0" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
  					<td width="70%" align="right"><a class="txtblueboldlink" href="index.php?pageid=18&tableid=4&txnid=<?php echo $_GET["TxnID"];?>">Sales Main</a></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
			<td class="txtgreenbold13">View Official Receipt</td>
    		<td></td>
		</tr>
		</table>
		<?php if (isset($_GET['msg'])) { ?>
			<br />
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td><span class="txtblueboldlink"><?php echo $_GET["msg"]; ?></span></td>
			</tr>
			</table>
		<?php } ?>
		<br />
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
          	<td class="tabmin2">
          		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
            	<tr>
              		<td class="txtredbold">General Information </td>
              		<td></td>
            	</tr>
          		</table>
      		</td>
          	<td class="tabmin3"></td>
		</tr>
		</table>      
	  	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
        <tr>
			<td valign="top" class="bgF9F8F7">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
            	<tr>
              		<td colspan="2"></td>
            	</tr>
            	<tr>
              		<td width="50%" valign="top">  
			  			<table width="100%"  border="0" cellspacing="1" cellpadding="0">
			  			<tr>
							<td width="25%" height="20" class="txt10">Customer Code</td>
							<td width="85%" height="20" ><input name="txtCustCode" type="text" class="txtfieldLabel" value="<?php echo $custcode; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr>
							<td height="20" class="txt10">Customer Name</td>
							<td height="20"><input name="txtCustName" type="text" class="txtfieldLabel" value="<?php echo $custname; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr>
							<td height="20" class="txt10">IBM No / IBM Name</td>
							<td height="20"><input name="txtIBM" type="text" class="txtfieldLabel" value="<?php echo $ibmname; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr>
							<td height="20" class="txt10">OR Type </td>
							<td height="20"><input name="txtORType" type="text" class="txtfieldLabel" value="<?php echo $orType;?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr>
							<td height="20" class="txt10">Total Amount </td>
							<td height="20"><input name="txtCustName" type="text" class="txtfieldLabel" value="<?php echo $totalamt; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr>
							<td height="20" class="txt10">Total Applied Amount </td>
							<td height="20"><input name="txtCustName" type="text" class="txtfieldLabel" value="<?php echo $totalappamt; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr>
							<td height="20" class="txt10">Total Unapplied Amount </td>
							<td height="20"><input name="txtCustName" type="text" class="txtfieldLabel" value="<?php echo $totalunappamt; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						</table>
					</td>
  					<td valign="top">
					  	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
					  	<tr>
							<td width="25%" height="20" class="txt10">Official Receipt No.</td>
							<td width="80%" height="20" >
								<input name="txtTxnNo" type="text" class="txtfieldLabel" value="<?php echo $orno; ?>" size="40" style="width:200px;" readonly="yes">
								<input name="hdnTxnID" id="hdnTxnID" type="hidden" value="<?php echo $id; ?>">
							</td>
						</tr>
						<tr>
						    <td height="20" class="txt10">Document No.</td>
						    <td height="20"><input name="txtDocNo" type="text" class="txtfieldLabel" id="txtDocNo" value="<?php echo $docno; ?>" size="40" style="width:200px;" readonly="yes" /></td>
					    </tr>
						<tr>
							<td height="20" class="txt10">Official Receipt Date</td>
							<td height="20"><input name="txtORDate" type="text" class="txtfieldLabel" value="<?php echo $txndate; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr>
							<td height="20" class="txt10">Branch Name</td>
							<td height="20"><input name="txtBranch" type="text" class="txtfieldLabel" value="<?php echo $branch; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr>
							<td height="20" class="txt10">Created By</td>
							<td height="20"><input name="txtCreatedBy" type="text" class="txtfieldLabel" value="<?php echo $createdby; ?>" size="40" style="width:200px;" readonly="yes"></td>
						</tr>
						<tr valign="top">
							<td height="20" class="txt10">Status</td>
							<td height="20"><input name="txtStatus" type="text" class="txtfieldLabel" value="<?php echo $status;?>" size="40" style="width:200px;" readonly="yes"/></td>
						</tr>
						<?php
							if ($txnstatusId == 7)
							{
						?>
								<tr>
									<td height="20" class="txt10">Confirmed By</td>
									<td><input name="txtConfirmedBy" type="text" class="txtfieldLabel" value="<?php echo $confirmedby; ?>" size="40" style="width:200px;" readonly="yes"></td>
								</tr>
								<tr>
									<td height="20" class="txt10">Date Confirmed</td>
									<td><input name="txtDateConfirmed" type="text" class="txtfieldLabel" value="<?php echo $lastmodifieddate; ?>" size="40" style="width:200px;" readonly="yes"></td>
								</tr>
						<?php
							}
						?>
						<?php
							if ($txnstatusId == 8)
							{
						?>
								<tr>
									<td height="20" class="txt10">Cancelled By</td>
									<td><input name="txtConfirmedBy" type="text" class="txtfieldLabel" value="<?php echo $cancelledby; ?>" size="40" style="width:200px;" readonly="yes"></td>
								</tr>
								<tr>
									<td height="20" class="txt10">Date Cancelled</td>
									<td><input name="txtDateConfirmed" type="text" class="txtfieldLabel" value="<?php echo $lastmodifieddate; ?>" size="40" style="width:200px;" readonly="yes"></td>
								</tr>
								<tr>
									<td height="20" class="txt10">Reason for Cancellation</td>
									<td><input name="txtDateConfirmed" type="text" class="txtfieldLabel" value="<?php echo $reason; ?>" size="40" style="width:200px;" readonly="yes"></td>
								</tr>
						<?php
							}
						?>
						
						<tr valign="top">
							<td height="20" class="txt10">Remarks</td>
							<td height="20"><textarea name="txtRemarks" cols="24" rows="3" style="width:200px;" class="txtfieldnh" id="textarea" wrap="off" readonly="yes"><?php echo $remarks; ?></textarea></td>
						</tr>
						</table>			
					</td>
			</tr>
			<tr>
				<td colspan="2"></td>
			</tr>
			</table>
	</td>
</tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td height="3" class="bgE6E8D9"></td>
</tr>
</table>
<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td>
	<td class="tabmin2">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtredbold">Official Receipt Details </td>
		</tr>
		</table>
	</td>
	<td class="tabmin3"></td>
</tr>
</table>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
<tr class="bgF9F8F7">
	<td colspan="2"></td>
</tr>
<tr>
	<td valign="top" align="center" class="bgF9F8F7" width="100%">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr class="bgEFF0EB">
			<td height="20" width="10%" class="txt10"><div align="right"><strong>Payment Type</strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Bank</strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Check No.</strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Check Date </strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Deposit Slip No.</strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Date Of Deposit</strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Type Of Deposit</strong></div></td>	
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Account-Campaign</strong></div></td>	
			<td height="20" width="10%" class="txt10"><div align="right"><strong>Amount</strong></div></td>		
		</tr>
		<?php
			$cnt = 0;
			if ($rs_details->num_rows)
			{				
				while ($row = $rs_details->fetch_object())
				{
					$cnt++;
					($cnt % 2) ? $class = "" : $class = "bgEFF0EB";
		?>
		<tr class="<?php echo $class; ?>">
			<td height="20" class="txt10"><div align="right"><?php echo $row->ptype; ?></div></td>
		  	<td height="20" class="txt10"><div align="center"><?php echo $row->BankName; ?></div></td>
	  		<td height="20" class="txt10"><div align="center"><?php echo $row->CheckNo;  ?></div></td>
	  		<td height="20" class="txt10"><div align="center">
	  		<?php 
	  					if ($row->CheckDate!= 'N/A')
	  					{
	  						$tmpTxnDate = strtotime($row->CheckDate);
							$txndate = date("m/d/Y", $tmpTxnDate);	
		  					echo $txndate;		  				
	  					}
	  					else
	  					{
	  						echo 'N/A';
	  					}
		  	?>
	  		</div>
	  		</td>
	  		<td height="20" class="txt10"><div align="center"><?php  echo $row->DepositSlipNo;  ?></div></td>
	  		<td height="20" class="txt10"><div align="center">
	  		<?php 	
	  					
	  					if ($row->DepositDate!= 'N/A')
	  					{
	  						$tmpTxnDates = strtotime($row->DepositDate);
							$txndates = date("m/d/Y", $tmpTxnDate);	
		  					echo $txndates;	
	  					}
	  					else
	  					{
	  						echo 'N/A';
	  					}
		  	?>
	  		</div>
	  		</td>
	  		<td height="20" class="txt10"><div align="center"><?php echo $row->DepositType ;?></div></td>
	  		<td height="20" class="txt10"><div align="center"><?php echo $row->AccountCampaign ;?></td>
	  		<td height="20" class="txt10"><div align="right"><?php echo number_format($row->TotalAmount, 2); ?></div></td>
		</tr>
		<?php
				}
			}
			else
			{
		?>
			<tr class="bgF9F8F7">
				<td height="20" colspan="9"><div align="center"><span class='txt10 style1'>No record(s) to display.</span></div></td>
			</tr>
		<?php 
			}
		?>
		
		<?php 
			if ($cnt==0)
			{
				echo "<tr class='bgF9F8F7'>
				<td height='20' colspan='8'></td>	
				</tr>";
				
			}
			else
			{
				echo "<tr class='bgEFF0EB'>
				<td height='20' colspan='7'></td>	
				<td height='20' class='txt10'><div align='right'><b>Total :</b></div></td>
				<td height='20' class='txt10'><div align='right'><b>$totalamt</b></div></td>
				</tr>";
			}
		
		?>
		
		</table>
	</td>
	<td valign="top" class="bgF9F8F7" width="500">
			
		
	</td>
	</tr>
	</table>
	<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td>
	<td class="tabmin2">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtredbold"> </td>
		</tr>
		</table>
	</td>
	<td class="tabmin3"></td>
</tr>
</table>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
<tr class="bgF9F8F7">
	<td colspan="2"></td>
</tr>
<tr>
	<td valign="top" align="center" class="bgF9F8F7" width="100%">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			
			<tr class="bgEFF0EB">
				 <td height="20" width="10%" class="txt10"><div align="center"><strong>Sales Invoice/Penalties No.</strong></div></td>
			    <td height="20" width="10%" class="txt10"><div align="center"><strong>IGS Code - Name</strong></div></td>
				<td height="20" width="10%" class="txt10"><div align="center"><strong>Transaction Date</strong></div></td>
				<td height="20" width="10%" class="txt10"><div align="center"><strong>Transaction Amount</strong></div></td>
				<td height="20" width="10%" class="txt10"><div align="right"><strong>Outstanding Balance</strong></div></td>
				<td height="20" width="10%" class="txt10"><div align="right"><strong></strong></div></td>
				<td height="20" width="10%" class="txt10"><div align="right"><strong></strong></div></td>				
				<td height="20" width="10%" class="txt10"><div align="right"><strong>Amount Applied</strong></div></td>
			</tr>
			<?php
				$cnts = 0;
				$sitotalamt=0;
				if ($rs_silist->num_rows)
				{				
					while ($row = $rs_silist->fetch_object())
					{
						$cnts++;
						($cnts % 2) ? $class = "" : $class = "bgEFF0EB";						
						$siNo = $row->siNo ;
						$tmpTxnDate = strtotime($row->siTxnDate);
						$sidate = date("m/d/Y", $tmpTxnDate);							
						$totalAmount = $row-> invoiceAmount ;
						$totalOutAmount = $row-> outstandingAmount ;
						$outstandingBalance = $row->siOutstandingBal;
						$amountApplied = $row->AmountApplied ;
						$sitotalamt += $amountApplied;
			echo "<tr class='$class'>
			  <td height='20' class='txt10'><div align='center'>$siNo</div></td>
			  <td height='20' class='txt10'><div align='center'></div></td>
			  <td height='20' class='txt10'><div align='center'> $sidate</div></td>
			  <td height='20' class='txt10'><div align='center'>$totalAmount</div></td>
			  <td height='20' class='txt10'><div align='center'>$totalOutAmount</div></td>
			  <td height='20' colspan='2' class='txt10'><div align='center'></div></td>			
			  <td height='20' class='txt10'><div align='right'>$amountApplied</div></td>
			</tr>";
					}
				}
				else
				{
				echo "<tr class='bgF9F8F7'>
					<td height='20' colspan='8'><div align='center'><span class='txt10 style1'>No record(s) to display.</span></div></td>
					</tr>";
				}
				$sitotalamt = number_format($sitotalamt,2);

			   if ($cnts >0)
			   {
			   	echo "<tr class='bgEFF0EB'>
					 <td height='20' colspan='6' class='txt10'><div align='right'></div></td>					
			         <td height='20' class='txt10'><div align='right'><b>Total :</b></div></td>
			         <td height='20' class='txt10'><div align='right'><b>$sitotalamt</b></div></td>
				</tr>";
			   }
			?>
				<tr class='bgF9F8F7'>
					<td height='20' colspan='5'><div align='center'></td>
				</tr> 
			</table>
		
		</td>
	</td>
	<td valign="top" class="bgF9F8F7" width="500">&nbsp;</td>
	</tr>
	</table>
	<br>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
	<tr>					
		<td align="center">
			<input name='btnprint' type='submit' class='btn' value=' Print' onclick="javascript: return validatePrint(<?php echo $_GET['TxnID']; ?>,1, <?php echo $prntcnt; ?>);" >						
			<?php if ($txnstatusId == '7'): ?>
			<input name='btnCancelOR' id='btnCancelOR' type='button' class='btn' value=' Cancel OR' onclick='return validateCancelOR();'>
			<?php endif; ?>
			<input name='btnCancel' type='submit' class='btn' value='Back to List'>
			
		</td>
	</tr>
  	</table>
	<br>
</form>	

<div id="cancelOR" title="Cancel Official Receipt">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
<tr>
	<td height="20" align="right" class="txt10">Reason Code :</td>
	<td height="20"><select name="pProdCls" id="lstReasonCode" class="txtfield" style="width:200px">
	  <?PHP
	   echo "<option value=\"\" >[SELECT HERE]</option>";
	        if ($rs_reason->num_rows)
	        {
	            while ($row = $rs_reason->fetch_object())
	            {
	            ($pProdClsID == $row->ID) ? $sel = "selected" : $sel = "";
	            echo "<option value='$row->ID' $sel>$row->Name</option>";                               
	            }
	        }
	        
	    ?>
	</select></td>
</tr>
<tr>
	<td height="20" align="right" class="txt10" valign="top">Remarks : </td>
	<td height="20"><textarea name="txtCancelRemarks" id="txtCancelRemarks" style="width:200px" cols="30" rows="6" class="txtfieldnh"></textarea></td>
</tr>              
</table>
</div>		
</body>