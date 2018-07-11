<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxViewSalesOrder.js"  type="text/javascript"></script>
<script language="javascript" src="js/sessionexpire.js"  type="text/javascript"></script>

<?php 
	include IN_PATH.DS."scViewDMCMDetails.php";
?>

<script type="text/javascript">
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
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<style type="text/css">
<!--
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}
-->
</style>
<body onLoad="set_interval()" onmousemove="reset_interval()" onclick="reset_interval()" onkeypress="reset_interval()" onUnload="unlock_trans(<?php echo $_GET["TxnID"];?>,5);">
<form name="frmViewDMCM" method="post" action="index.php?pageid=49.1&TxnID=<?php echo $_GET["TxnID"];?> ">
<input type="hidden" name="hdncnt" id="hdncnt" value="0" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
   <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
         <td class="topnav">
            <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
               <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18&tableid=5&txnid=<?php echo $_GET["TxnID"];?>">Sales Main</a></td>
            </tr>
            </table>
         </td>
      </tr>
      </table>
         <br>
         <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
         <td class="txtgreenbold13">View Debit / Credit Memo</td>
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
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	                  	<tr>
	                     	<td width="25%" height="20" align="right" class="txt10"><strong>Customer Code : </strong></td>
	                     	<td width="5%" height="20" class="txt10">&nbsp;</td>
	                     	<td width="70%" height="20"><?php echo $custcode; ?></td>
	                  	</tr>
	                  	<tr>
	                     	<td height="20" align="right" class="txt10"><strong>Customer Name : </strong></td>
	                     	<td height="20" class="txt10">&nbsp;</td>
	                     	<td height="20"><?php echo $custname; ?></td>
	                  	</tr>
	                  	<tr>
	                     	<td height="20" align="right" class="txt10"><strong>IBM No / Name : </strong></td>
	                     	<td height="20" class="txt10">&nbsp;</td>
	                     	<td height="20"><?php echo $ibm; ?></td>
	                  	</tr>
	                  	<tr>
	                    	<td height="20" align="right" class="txt10"><strong>Memo Type  : </strong></td>
	                    	<td height="20" class="txt10">&nbsp;</td>
	                    	<td height="20"><?php echo $memotype; ?></td>
	                 	</tr>
	                 	<tr>
                        	<td height="20" align="right" class="txt10"><strong>Reason Code : </strong></td>
                        	<td height="20" class="txt10">&nbsp;</td>
                        	<td height="20"><?php echo $reason; ?></td>
                     	</tr>
                   		<tr>
                    		<td height="20" align="right" class="txt10"><strong>Total Amount  : </strong></td>
	                        <td height="20" class="txt10">&nbsp;</td>
	                        <td height="20"><?php echo $totalamt; ?></td>
	                   	</tr>
	                  	</table>
              		</td>
                  	<td valign="top">
                 		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                     	<tr>
                     		<td width="25%" height="20" align="right" class="txt10"><strong>DMCM No. : </strong></td>
                     		<td width="5%" height="20" class="txt10">&nbsp;</td>
                     		<td width="70%" height="20"><?php echo $dmcmno; ?></td>
                  		</tr>
                  		<tr>
                     		<td height="20" align="right" class="txt10"><strong>Document No. : </strong></td>
                     		<td height="20">&nbsp;</td>
                     		<td height="20"><?php echo $docno; ?></td>
                  		</tr>
                  		<tr>
	                     	<td height="20" align="right" class="txt10"><strong>DMCM Date : </strong></td>
	                     	<td height="20" class="txt10">&nbsp;</td>
	                     	<td height="20"><?php echo $txndate; ?></td>
	                  	</tr>
	                  	<tr>
	                     	<td height="20" align="right" class="txt10"><strong>Branch Name : </strong></td>
	                     	<td height="20" class="txt10">&nbsp;</td>
	                     	<td height="20"><?php echo $branch; ?></td>
	                  	</tr>
                     	<tr>
                        	<td height="20" align="right" class="txt10"><strong>Particulars  : </strong></td>
                        	<td height="20" class="txt10">&nbsp;</td>
                        	<td height="20"><?php echo $particularss; ?></td>
                     	</tr>
                     	<tr>
                        	<td height="20" align="right" class="txt10"><strong>Remarks : </strong></td>
                        	<td height="20" class="txt10">&nbsp;</td>
                        	<td height="20" rowspan="3" valign="top"><?php echo $remarks; ?></td>
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
                        <td class="txtredbold">Select Sales Invoice(s) / Penalties  </td>
                     </tr>
                     </table>
                  </td>
                  <td class="tabmin3">&nbsp;</td>
               </tr>
               </table>    
     
               <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
               <tr>
                     <td valign="top" class="tab" width="1020">
                        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10" height="25">
                     <tr align="center">
                        <td width="15%" valign="middle" align="center">Sales Invoice/Penalty No.</td>  
                        <td width="15%" valign="middle" align="center" class="padl5">IGS Code - Name</td>
                        <td width="15%" valign="middle" align="center" class="padl5">Transaction Date</td>
                        <td width="15%" align="right" class="padr5" valign="middle">Transaction Amount</td>
                        <td width="20%" align="right" class="padr5" valign="middle">Outstanding Balance</td>
                        <td width="20%" align="right" class="padr5" valign="middle" class="padr5">Amount</td>                      
                     </tr>
                     </table>
                     </td>
               </tr>
               <tr>
                     <td valign="top" class="bgF9F8F7">
                  <div class="scroll_300">
                  <table width="100%" border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                  <?php
                     $cnt = 0;
                     $totamt = 0;      
                     if ($rs_details->num_rows)
                     {           
                        while ($row = $rs_details->fetch_object())
                        {
                           $cnt +=1;
                           ($cnt % 2) ? $class = "" : $class = "bgEFF0EB";
                  ?>       
                              <tr align="center" class="<?php echo $class; ?>">
                                 <td width="15%" height="20" align="center" class="padl5"><?php echo $row->SIPNo; ?></td>
                                 <td width="15%" height="20" align="center" class="padl5"></td>                                                                  
                                 <td width="15%" height="20" align="center" class="padr5"><?php echo $row->TxnDate; ?></td>                                 
                                 <td width="15%" height="20" align="right" class="padr5" class="padr5"><?php echo number_format($row->TotalNetAmt, 2); ?></td>
                                 <td width="20%" height="20" align="right" class="padr5" class="padr5"><?php echo number_format($row->OutstandingBalance, 2); ?></td>
                                 <td width="20%" height="20" align="right" class="padr5" class="padr5"><?php echo number_format($row->TotalAmt, 2); ?></td>              
                              </tr>
                     <?php
                        }
                     }
                     else
                     {
                        echo "<tr align='center'><td width='100%' height='20' class='borderBR' colspan='10'><span class='txt10 style1'><strong>No record(s) to display. </span></td></tr>";
                     } 
                     ?>
                  </table>
                  </div>
                     </td>
               </tr>
            </table>
            <br>
            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
               <td align="center">
                  <input name="btnCancel" type="submit" class="btn" value="Cancel">
               </td>
            </tr>
            </table>
            <br>
            </td>
         </tr>
         </table>
</form>   
</body>     