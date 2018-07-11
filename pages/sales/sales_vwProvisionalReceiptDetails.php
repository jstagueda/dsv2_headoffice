<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxVwProvisionalReceiptDetails.js"  type="text/javascript"></script>
<script language="javascript" src="js/sessionexpire.js"  type="text/javascript"></script>

<?PHP 
	include IN_PATH.DS."scViewProvisionalReceiptDetails.php";
?>

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
<body onLoad="set_interval()" onmousemove="reset_interval()" onclick="reset_interval()" onkeypress="reset_interval()" onUnload="unlock(<?php echo $_GET["TxnID"];?>,3);">
<form name="frmViewProvisionalReceipt" method="post" action="index.php?pageid=51.1&TxnID=<?php echo $_GET["TxnID"];?> ">
<input type="hidden" name="hdncnt" id="hdncnt" value="0" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
  					<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18&tableid=3&txnid=<?php echo $_GET["TxnID"];?>">Sales Main</a></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
			<td class="txtgreenbold13">View Provisional Receipt</td>
    		<td>&nbsp;</td>
		</tr>
		</table>
		<br />
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
          	<td class="tabmin2">
          		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
            	<tr>
              		<td class="txtredbold">General Information </td>
              		<td>&nbsp;</td>
            	</tr>
          		</table>
      		</td>
          	<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>      
	  	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
        <tr>
			<td valign="top" class="bgF9F8F7">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
            	<tr>
              		<td colspan="2">&nbsp;</td>
            	</tr>
            	<tr>
              		<td width="50%" valign="top">  
			  			<table width="100%"  border="0" cellspacing="1" cellpadding="0">
				
					<td height="20" width="15%" class="txt10">Customer Code</td>
					<td height="20">:</td>
					<td><input name="txtCustCode" id="txtCustCode" type="text" class="txtfield" value="<?php echo $custcode; ?>" size="40" readonly="yes"></td>
				</tr>
				<tr>
					<td height="20" class="txt10">Customer Name</td>
					<td height="20">:</td>
					<td><input name="txtCustName" type="text" class="txtfield" value="<?php echo $custname; ?>" size="40" readonly="yes"></td>
				</tr>	
				<tr>
					<td height="20" class="txt10">IBM Code-Name</td>
					<td height="20">:</td>
					<td>
					<input name="txtHIbmCode" type="text" class="txtfield" value="<?php echo $ibmName;?>" size="40" readonly="yes">
					</td>
				</tr>
				<tr>
					<td height="20" class="txt10">Total Amount </td>
					<td height="20">:</td>
					<td><input name="txtCustName" type="text" class="txtfield" value="<?php echo $totalamt; ?>" size="40" readonly="yes"></td>
				</tr>
				</table>
  			</td>
  			<td valign="top">
			  	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
			  	<tr>
					<td height="20" width="13%" class="txt10">Provisional Receipt No.</td>
					<td height="20">:</td>
					<td height="20">
						<input name="txtTxnNo" type="text" class="txtfield" value="<?php echo $orno; ?>" size="40" readonly="yes">
						<input name="hdnTxnID" id="hdnTxnID" type="hidden" value="<?php echo $id; ?>">
					</td>
				</tr>
				<tr>
				    <td height="20" class="txt10">Document No.</td>
				    <td height="20">:</td>
				    <td height="20"><input name="txtDocNo" type="text" class="txtfield" id="txtDocNo" value="<?php echo $docno; ?>" size="40" readonly="yes" /></td>
			    </tr>
				<tr>
					<td height="20" class="txt10">Provisional Receipt Date </td>
					<td height="20">:</td>
					<td height="20"><input name="txtORDate" type="text" class="txtfield" value="<?php echo $txndate; ?>" size="40" readonly="yes"></td>
				</tr>
				<tr>
					<td height="20"  class="txt10">Branch Name</td>	
					<td height="20">:</td>
					<td height="20" class="txt10"><?php echo $branchName;?></td>
				</tr>
				<tr>
					<td height="20" class="txt10">Created By</td>	
					<td height="20">:</td>
					<td height="20" class="txt10"><?php echo $createdby;?></td>				
				</tr>
				<tr>
					<td height="20"  class="txt10">Confirmed By</td>
					<td height="20">:</td>	
					<td height="20" class="txt10"><?php echo $confirmedby;?></td>					
				</tr>
				<tr valign="top">
					<td height="20" class="txt10">Remarks</td>
					<td height="20">:</td>
					<td><textarea name="txtRemarks" cols="30" rows="3" class="txtfieldnh" id="textarea" wrap="off" readonly="yes"><?php echo $remarks; ?></textarea></td>
				</tr>
				</table>			
  			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
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
	<td class="tabmin">&nbsp;</td>
	<td class="tabmin2">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtredbold">Provisional Receipt Details </td>
		</tr>
		</table>
	</td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
<tr class="bgF9F8F7">
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td valign="top" align="center" class="bgF9F8F7" width="85%">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr class="bgEFF0EB">
			<td height="20" width="10%" class="txt10"><div align="right"><strong>Payment Type</strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Bank</strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Check No.</strong></div></td>
			<td height="20" width="10%" class="txt10"><div align="center"><strong>Check Date </strong></div></td>
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
		  	<td height="20" class="txt10"><div align="center">
		  		<?php
		  			if ($row->BankName != '')
		  			{
		  				echo $row->BankName;		  				
		  			}
		  			else
		  			{
		  				echo "N/A";
		  			}
	  			?>
	  		</div>
	  		</td>
	  		<td height="20" class="txt10"><div align="center">
		  		<?php
		  			if ($row->CheckNumber != '')
		  			{
		  				echo $row->CheckNumber;		  				
		  			}
		  			else
		  			{
		  				echo "N/A";
		  			}
	  			?>
	  		</div>
	  		</td>
	  		<td height="20" class="txt10"><div align="center">
		  		<?php
		  			if ($row->CheckDate != '')
		  			{
		  				$tmpTxnDate = strtotime($row->CheckDate);
						$txndate = date("m/d/Y", $tmpTxnDate);	
		  				echo $txndate;		  				
		  			}
		  			else
		  			{
		  				echo "N/A";
		  			}
	  			?>
	  		</div>
	  		</td>
	  		
	  		
	  		
	  		<td height="20" class="txt10"><div align="right"><?php echo number_format($row->TotalAmount, 2); ?></div></td>
		</tr>
		<?php
				}
			}
			else
			{
		?>
			<tr class="bgF9F8F7">
				<td height="20" colspan="5"><div align="center"><span class='txt10 style1'>No record(s) to display.</span></div></td>
			</tr>
		<?php 
			}
		?>
		
		<?php 
			if ($cnt==0)
			{
				echo "<tr class='bgF9F8F7'>
				<td height='20' colspan='5'>&nbsp;</td>	
				</tr>";
				
			}
			else
			{
				echo "<tr class='bgEFF0EB'>
				<td height='20' colspan='3'>&nbsp;</td>	
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
<!--<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">&nbsp;</td>
	<td class="tabmin2">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtredbold">List of Sales Invoice(s) Paid </td>
		</tr>
		</table>
	</td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
<tr class="bgF9F8F7">
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td valign="top" align="center" class="bgF9F8F7" width="85%">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			
			<tr class="bgEFF0EB">
				<td height="20" width="10%" class="txt10"><div align="right"><strong></strong></div></td>
			    <td height="20" width="10%" class="txt10"><div align="center"><strong>Sales Invoice No.</strong></div></td>
				<td height="20" width="10%" class="txt10"><div align="center"><strong>Sales Invoice Date</strong></div></td>
				<td height="20" width="10%" class="txt10"><div align="center"><strong>Sales Invoice Amount</strong></div></td>
				<td height="20" width="10%" class="txt10"><div align="center"><strong>Outstanding Balance</strong></div></td>
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
						$outstandingBalance=$row->siOutstandingBal;
						$amountApplied = $row->AmountApplied ;
						
						$sitotalamt+=$amountApplied;
			
			
			echo "<tr class='$class'>
			  <td height='20' class='txt10'><div align='center'></div></td>
			  <td height='20' class='txt10'><div align='center'>$siNo</div></td>
			  <td height='20' class='txt10'><div align='center'> $sidate</div></td>
			  <td height='20' class='txt10'><div align='center'>$totalAmount</div></td>
			  <td height='20' class='txt10'><div align='center'> $outstandingBalance</div></td>
			  <td height='20' colspan='2' class='txt10'><div align='center'></div></td>			
			  <td height='20' class='txt10'><div align='right'> $amountApplied</div></td>
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
	<td valign="top" class="bgF9F8F7" width="500">
			
		
	</td>
	</tr>
	</table>
	
	--><table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
	
	</table><br>
				<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>					
					<td align="center">					
						<?php if ($txnstatusId == '7'): ?>
						<input name='btnprint' type='submit' class='btn' value=' Print' onclick="javascript: return validatePrint(<?php echo $_GET['TxnID']; ?>,1);" >
						<input name='btnCancelPR' id='btnCancelPR' type='button' class='btn' value='Cancel PR'>
						<?php endif; ?>
						<input name='btnCancel' type='submit' class='btn' value=' Cancel'>
						
					</td>
				</tr>
			  	</table>
<br>
</form>	

<div id="cancelPR" title="Cancel Provisional Receipt">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
              <tr>
                <td height="20" align="right" class="txt10">Reason Code&nbsp; :</td>
                <td height="20">&nbsp;&nbsp;<select name="pProdCls" id="lstReasonCode" class="txtfield" style="width:200px">
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
                <td height="20" align="right" class="txt10" valign="top">Remarks&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;<textarea name="txtCancelRemarks" id="txtCancelRemarks" style="width:200px" cols="30" rows="6" class="txtfieldnh"></textarea></td>
              </tr>              
</table>
</div>		
</td>
</body>