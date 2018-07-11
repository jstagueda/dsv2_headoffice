<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxBackOrder.js"  type="text/javascript"></script>
<script language="javascript" src="js/sessionexpire.js"  type="text/javascript"></script>
<script src="js/shortcut.js" type="text/javascript"></script>
<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

<style type="text/css">
<!--
.style1 {color: #FF0000}

div.autocomplete {
  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;} 
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px solid #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}z

-->
</style>



<?php
	include IN_PATH.DS."scBackOrderSO.php";
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
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>
<!--<body onLoad="set_interval()" onmousemove="reset_interval()" onclick="reset_interval()" onkeypress="reset_interval()">);"-->
<body onUnload="unlock_trans(<?php echo $_GET["TxnID"];?>,1);">
<form name="frmCreateSalesOrder" id="frmCreateSalesOrder" method="post" action="index.php?pageid=35.2&TxnID=<?php echo $_GET["TxnID"];?> ">
<input type="hidden" name="hdncnt" id="hdncnt" value="0" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr >
					<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18&tableid=1&txnid=<?php echo $_GET["TxnID"];?>">Sales Main</a></td>
				</tr>
				</table>
			</td>
          </tr>
        </table>
      	<br>
      	<input type="hidden" name="hCustomerID" id="hCustomerID" value="<?php echo $custID; ?>">
      	<input type="hidden" name="hVatPercent" id="hVatPercent" value="<?php echo $vatpercent; ?>">
		<input type="hidden" name="hSOID" id="hSOID" value="<?php echo $_GET['TxnID']; ?>">
      	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtgreenbold13">Back Order</td>
            <td>&nbsp;</td>
		</tr>
        </table>
		<br />
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
		
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td><span class="txtblueboldlink"><?php echo $_GET['msg'] ; ?></span></td>
		</tr>

		</table>
 		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			
        	<td  valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
		<tr>
			<td valign="top" class="bgF9F8F7"><div class="scroll_350">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="50%" valign="top">
						<table width="100%"  border="0" cellspacing="1" cellpadding="0">
						<tr>
							<td colspan="3" height="15">&nbsp;</td>
						</tr>
						<tr>
				  			<td width="35%" height="20" align="right" class="txt10">Customer Code: </td>
				  			<td width="5%" height="20" class="txt10">&nbsp;</td>
				  			<td width="60%" height="20">
								<input name="txtCustomer" type="text" class="txtfieldLabel" id="txtCustomer" readonly="yes"  value="<?php echo $custcode; ?>" />	
								<input type="hidden" name="hcustID"  value="<?php echo $custID 	; ?>" />
			  				</td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Customer Name  :</td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtCustomerName" type="text" class="txtfieldLabel" id="txtRefNo" size="25" readonly="yes" value="<?php echo $custname; ?>" /><input type="hidden" name="GSUTypeID" value="<?php echo $gsutypeID; ?>"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Document No.  :</td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtRefNo" type="text" class="txtfieldLabel" id="txtRefNo" size="25" maxlength="15"  readonly="yes"  value="<?php echo $docno; ?>" /></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Sales Order No.  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtSONo" type="text" class="txtfieldLabel" id="txtSONo" size="25" maxlength="25"   readonly="yes"  value="<?php echo $sono; ?>" /></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Sales Order Date  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtSODate" type="text" class="txtfieldLabel" id="txtSODate" size="12"   readonly="yes"  value="<?php echo $txndate; ?>" /></td>
						</tr>
						<!--<tr>
				  			<td height="20" align="right" class="txt10">Delivery Date  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtDelDate" type="text" class="txtfield" id="txtDelDate" size="12"   readonly="yes"   value="<?php// echo $txndate; ?>" /></td>
						</tr>-->
						<tr>
				  			<td height="20" align="right" class="txt10">Credit Limit  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtCLimit" type="text" class="txtfieldLabel" id="txtCLimit" readonly="yes"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">AR Balance  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtARBalance" type="text" class="txtfieldLabel" id="txtARBalance" readonly="yes"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Unpaid Penalty  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtPenalty" type="text" class="txtfieldLabel" id="txtPenalty"  readonly="yes"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Available Credit  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtAvailableCredit" type="text" class="txtfieldLabel" id="txtAvailableCredit"  readonly="yes"/></td>
						</tr>
						
                        
                    	</table>
            		</td>
                    <td valign="top" width="50%">
                    	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
                    	<tr>
							<td colspan="3" height="15">&nbsp;</td>
						</tr>
						<tr>
							<td width="25%" height="20" align="right" class="txt10">IBM No / IBM Name : </td>
							<td width="5%" height="20" class="txt10"></td>
							<td width="70%" height="20"><?php echo $ibm ; ?><input type="hidden" name="isEmployee" value="<?php echo $isEmployee; ?>" />
								                            
                            </td>
                        </tr>
						<tr>
							<td width="25%" height="20" align="right" class="txt10">Warehouse : </td>
							<td width="5%" height="20" class="txt10">&nbsp;</td>
							<td width="70%" height="20"> MAIN WAREHOUSE </td>
                        </tr>
                    
						
						<tr>
							<td height="20" align="right" valign="top" class="txt10">Remarks : </td>
							<td height="20" valign="top" class="txt10">&nbsp;</td>
							<td height="20"><textarea name="txtRemarks" cols="42" rows="3" class="txtfieldnh" id="txtRemarks" wrap="off"  readonly="yes"   value="<?php echo $remarks; ?>" ></textarea></td>
						</tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
					<td colspan="3">&nbsp;</td>
				</tr>
                </table>
                </div></td>
              </tr>
            </table>
          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td height="3" class="bgE6E8D9"></td>
              </tr>
            </table>
          <br /></td>
      </tr>
    </table>
<a name="AnchorHere"></a>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td class="tabmin">&nbsp;</td>
		<td class="tabmin2">
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="txtredbold">Served Items</td>
				<td>
					<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
					<tr>
						<td>&nbsp;</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</td>
		<td class="tabmin3">&nbsp;</td>
	</tr>
</table>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">	
	<tr>
	<td class="tab">
		<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 " border="0">
		<tr >
			
			<td width="4%" align="center" >Line #</td>
			<td width="8%" align="left">Item Code</td>
			<td width="24%" align="left">Item Name</</td>
			<td width="5%" align="center">UOM</td>
			<td width="8%" align="center">PMG</td>			
			<td width="5%" align="center">Promo</td>
			<td width="5%" align="center">SOH</td>				
			<td width="8%" align="left">Intransit Qty</td>
			<td width="8%" align="left">Ordered Qty</td>
			<td width="8%" align="center">CSP </td>	
			<td width="10%" align="right">Total Price </td>				
		</tr>
		</table>
	</td>
	</tr>
	<tr>
	<td class="bgF9F8F7">
		<div class="scroll_350" >	
		<!--Served Table-->
		<table width="100%"  cellpadding="0" cellspacing="1" class="bgFFFFFF" id="dynamicTable" border="0">
			<?php
			if($rsServedDetails->num_rows)
			{
				$cnt = 1;
				while ($row = $rsServedDetails->fetch_object())
				{
				echo '<tr height="20">									
					<td width="4%" align="left" class="borderBR padl5">'.$cnt.'</td>
					<td width="8%" height="20" class="borderBR padl5">'.$row->prodCode.'</td>
					<td width="17%" align="left" class="borderBR padl5">'.$row->prodName.'</td>
					<td width="5%" align="center" class="borderBR padl5">Piece</td>
					<td width="5%" align="center" class="borderBR padl5">'.$row->pmgCode.'</td>									
					<td width="10%" align="center" class="borderBR padl5">'.$row->promo.'</td>		
					<td width="5%" align="center" class="borderBR padl5">'.$row->SOH.'</td>					
					<td width="10%" align="center" class="borderBR padl5">'.$row->intransit.'</td>
					<td width="8%" align="center" class="borderBR padl5">'.$row->qty.'</td>
					<td width="10%" align="center" class="borderBR padl5">'.number_format($row->unitprice,2,'.','').' </td>					
					<td width="10%" align="center" class="borderBR padl5">'.number_format($row->unitprice,2,'.','').'</td>					
				</tr>';
				$cnt ++;
				}
			} ?>
		</table>			
		</div>	
	</td>
	</tr>	
	<tr class="bgF9F8F7">
	<td height="15">&nbsp;</td>
	</tr>
</table>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="3">
<tr>   	
	
	<!--- ###################################################################################################  -->
	<td valign="top">&nbsp;
		<!---  right table -->
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td class="txtredbold">Order Details </td>
					<td>
						<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<!---  right div-->
		<div id="tbl22" style="display:block"> 
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">	
		<tr>
			<td class="tab">
				<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 " border="0">
				<tr >
					
					<td width="4%" align="center" >Line #</td>
					<td width="8%" align="left">Item Code</td>
					<td width="24%" align="left">Item Name</</td>
					<td width="5%" align="center">UOM</td>
					<td width="8%" align="center">PMG</td>					
					<td width="8%" align="center">Regular Price</td>
					<td width="5%" align="center">Promo</td>
					<td width="5%" align="center">SOH</td>				
					<td width="8%" align="left">Intransit Qty</td>
					<td width="8%" align="left">Ordered Qty</td>
					<td width="8%" align="center">CSP </td>	
					<td width="10%" align="right">Total Price </td>				
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_350" id="prodlist">
				
				<!--Dynamic Table-->
				<table width="100%"  cellpadding="0" cellspacing="1" class="bgFFFFFF" id="dynamicTable" border="0">
				
				<?php  if ($rsOpenDetails->num_rows)
				{
					
					$i = 1;
					;
				   while ($row = $rsOpenDetails->fetch_object())
				   {		
				   			   		
				   		for($k= 1 ;$k <= $row->qty; $k ++)
						{
							$productID 			=	$row->prodID ;						
							$productcode 		= 	$row->prodCode;
							$productdescription = 	$row->prodName;
							$pmg 				= 	$row->pmgCode;	
							$pmgid				=	$row->pmgid;					
							$unitprice			= 	number_format($row->unitprice,2,'.','');
							$orderedQTY 		= 	1;
							$effectiveprice 	= 	number_format($row->unitprice,2,'.','');
							$tmptotalprice  	= 	0;
							$totalprice			= 	number_format($tmptotalprice,2,'.','');
							$promoCode			=	$row->promo;
							$producttype		= 	$row->prodType;
							$promoID			= 	$row->promoID;
							$promoType			= 	$row->promotype;
						echo'<tr height="20">				
								<td width="4%" align="left" class="borderBR padl5">'.$i.'</td>
								<td width="8%" height="20" class="borderBR padl5"><div align="left" >
									<input name="txtProdCode'.$i.'" type="text" readonly="yes" class="txtfieldLabel" id="txtProdCode'.$i.'" style="width: 75px;" value="'.$productcode.'"/>
									
									<input name="hProdID'.$i.'" type="hidden" id="hProdID'.$i.'" value="'.$productID.'"/>
								</div></td>
								<td width="17%" align="left" class="borderBR padl5"><input name="txtProdDesc'.$i.'" type="text" class="txtfieldLabel" style="width: 220px" id="txtProdDesc'.$i.'"  readonly="yes" value="'.$productdescription.'" /></td>
								<td width="5%" align="center" class="borderBR padl5">Piece</td>
								<td width="5%" align="center" class="borderBR padl5"><input name="txtPMG'.$i.'" type="text" class="txtfieldLabel" id="txtPMG'.$i.'"  value="'.$pmg.'" /><input type="hidden" name="hPMGID'.$i.'" id="hPMGID'.$i.'"  value="'.$pmgid.'" /> <input type="hidden" name="hProductType'.$i.'" id="hProductType'.$i.'"  value="'.$producttype.'" /></td>					
								<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtUnitPrice'.$i.'" class="txtfieldLabel" id="txtUnitPrice'.$i.'" readonly="yes" value="'.$unitprice.'"></td>
								<td width="10%" align="center" class="borderBR padl5">'.$promoCode.' <input type="hidden" name="hPromoID'.$i.'" id="hPromoID'.$i.'" value="'.$promoID.'" /><input type="hidden" name="hPromoType'.$i.'" id="hPromoType'.$i.'" value="'.$promoType.'" /></td>		
								<td width="5%" align="center" class="borderBR padl5"><div id="divSOH'.$i.'" name="divSOH'.$i.'">'.$row->SOH.'</div><input type="hidden" name="hSOH'.$i.'" id="hSOH'.$i.'" value="'.$row->SOH.'" ></td>					
								<td width="10%" align="center" class="borderBR padl5"><div id="divTransit'.$i.'" name="divTransit'.$i.'">'.$row->intransit.'</div><input type="hidden" name="hTransit'.$i.'" id="hTransit'.$i.'" value="'.$row->intransit.'"  ></td>
								<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtOrderedQty'.$i.'" readonly="yes" class="txtfieldLabel" id="txtOrderedQty'.$i.'" value = "'.$orderedQTY.'" /><input type="hidden" name="hServed'.$i.'" /></td>
								<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtEffectivePrice'.$i.'" class="txtfieldLabel" id="txtEffectivePrice'.$i.'" readonly="yes" value="'.$effectiveprice.'"> </td>					
								<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtTotalPrice'.$i.'" class="txtfieldLabel" id="txtTotalPrice'.$i.'" readonly="yes" value="'.$totalprice.'"> </td>					
							</tr>' ;
							$i ++  ;
						}	
					}
					echo'<input type="hidden" name="hcnt" id="hcnt" value="'.$i.'" />';
				} ?>
				</table>			
				</div>	
			</td>
		</tr>	
		<tr class="bgF9F8F7">
			<td height="15">&nbsp;</td>
		</tr>
		<!--<tr class="bgF9F8F7">
			<td height="15" class="padl5"><input name="btnCalculate" type="submit"  class="btn" value="Calculate Best Price"></td>
		</tr>-->
		<tr class="bgF9F8F7">
			<td height="15">&nbsp;</td>
		</tr>
		<tr>
			<td class="bgF9F8F7 tab">
				<table width="100%" border="0" cellpadding="0" cellspacing="1">
				<tr>
					<td colspan="4" height="20">&nbsp;</td>
				</tr>
				<tr>
				<input type="hidden" name="totCPI"  />
				  <td width="30%" height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total CFT :</strong>&nbsp;</div></td>
				  <td width="30%" height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totCFT" id="totCFT" readonly="yes" class="txtfieldLabel"  style="text-align:right" /></div></td>
				  <td width="25%" height="25" align="right" class="borderBR txt10"><div align="right"><strong>Gross Amount :</strong>&nbsp;</div></td>
				  <td width="15%" height="25" class="borderBR"><div align="right" class="padr5"><input name="txtGrossAmt" type="text" class="txtfieldLabel" size="10" readonly="yes" style="text-align:right"   /></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total NCFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totNCFT" id="totNCFT" readonly="yes" class="txtfieldLabel"  style="text-align:right" /></div></td>
				  <td height="20" align="right" class="borderBR"><div align="right"><strong>Total CSP Less (CPI <b id='boldStuff'></b>) :</strong>&nbsp;</div></td>
				 <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtCSPLessCPI" id="txtCSPLessCPI" type="text" class="txtfieldLabel" size="10" style="text-align:right"/></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>MTD CFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="MTDCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR"><div align="right"><strong>Basic Discount :</strong>&nbsp;</div></td>
				 <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtBasicDiscount" id="txtBasicDiscount" type="text" class="txtfieldLabel" size="10" style="text-align:right"/></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>MTD NCFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="MTDNCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR"><div align="right"><strong>Additional Discount :</strong>&nbsp;</div></td>
					<td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtAddtlDisc" type="text" class="txtfieldLabel" size="10" style="text-align:right" value="0.00" ></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>YTD CFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="YTDCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Sales With Vat :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtSalesWVat"  type="text" class="txtfieldLabel" size="10" readonly="yes" style="text-align:right" ></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>YTD NCFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="YTDNCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Vat Amount :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtVatAmt"  type="text" class="txtfieldLabel" size="10" readonly="yes" style="text-align:right" ></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Amount to next discount level - CFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="nextdiscCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Vatable Amount :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtAmtWOVat"  type="text" class="txtfieldLabel" size="10" readonly="yes" style="text-align:right"></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Amount to next discount level - NCFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="nextdiscNCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR"><div align="right"><strong>Additional Discount on Previous Purchase :</strong>&nbsp;</div></td>
					<td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtADPP"  type="text" class="txtfieldLabel" size="10" style="text-align:right" value="0.00"></div></td>
				</tr>
				<tr>
					<td height="20" align="right">&nbsp;</td>
					<td height="20" align="right">&nbsp;</td>
					<td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total Invoice Amount Due :</strong>&nbsp;</div></td>
				  	<td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtNetAmt"  type="text" class="txtfieldLabel" id="txtNetAmt" style="text-align:right" size="10" readonly="yes"></div></td>
				</tr>				
				</table>
			</td>
		</tr>
		</table>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
  		<tr>
    		<td height="3" class="bgE6E8D9"></td>
  		</tr>
		</table>
		</div>
		<!--- end right div  -->
		<br />
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td height="20"><div align="center">
				<input name="btnCreate" type="submit" class="btn" value="Create SI" onclick="return validateSave();"  />
         		&nbsp;
            	<input name="btnClose" type="submit" class="btn" value="Close"  />
         		&nbsp;
		  		<input name="btnCancel" type="submit" class="btn" value="Cancel" onclick="return ConfirmCancel();" >
		  	</div></td>
		</tr>
		</table>
		<!--- end  right table -->
		</td>	
	</tr>
	</table>
	</td>
</tr> 
</table>
</td>
</tr>
</table>
</form>
<div id="substituteItem" title="Substitute Item">
	<input type="hidden" name="hProdIDSubstitute" id="hProdIDSubstitute" />
	<div id="substitutetable">
	
	</div>
	
</div>
</body>