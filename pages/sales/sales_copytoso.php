<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxCopyToSO.js"  type="text/javascript"></script>
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
}

-->
</style>



<?php
	include IN_PATH.DS."scCopyToSo.php";
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>

<form name="frmCreateSalesOrder" id="frmCreateSalesOrder" method="post" action="index.php?pageid=34&custID=<?php echo $_GET["custID"];?>&adv=<?php echo $_GET["adv"];?> " >
<body  onload="return chkAdvPO();">
<table width="100%"  border="0" cellspacing="0" cellpadding="0" >
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr >
					<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
				</tr>
				</table>
			</td>
          </tr>
        </table>
      	<br>
      	<input type="hidden" name="hCustomerID" id="hCustomerID" value="<?php echo $custID; ?>">
      	<input type="hidden" name="hVatPercent" id="hVatPercent" value="<?php echo $vatpercent; ?>">
		<input type="hidden" name="hisAdvance"	id="hisAdvance" value="<?php echo $_GET['adv']; ?>">
		<input type="hidden" name="hApprvAdvPO"	id="hApprvAdvPO" value="<?php echo $ifAdvPO; ?>">
		<input type="hidden" name="hBackOrder"	id="hBackOrder" value="<?php if($_GET[pageid] == 35.2){ echo "1" ; }else echo "0"; ?>">
		<input type="hidden" name="hTxnID"	id="hTxnID" value="<?php if($_GET[pageid] == 35.2){ echo $_GET['TxnID'] ; }else echo "0"; ?>">
		
      	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtgreenbold13">Create Sales Order</td>
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
		<?php 	if(isset($_GET[TxnID])){?>
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
								<input type="hidden" name="hCOA"  value="<?php echo $custID 	; ?>" />
								<input name="hGSUType" type="hidden" id="hGSUType" value="<?php echo $gsutypeID ; ?>"/>	
			  				<input type="hidden" name="hCustomerStatus" id="hCustomerStatus" value="<?php echo $customerStatus ; ?>">
			  				</td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Customer Name  :</td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtCustomerName" type="text" class="txtfieldLabel" id="txtRefNo" size="25" readonly="yes" value="<?php echo $custname; ?>" /><input type="hidden" name="GSUTypeID" value="<?php echo $gsutypeID; ?>"/></td>
						</tr>
						<tr>
							<td width="25%" height="20" align="right" class="txt10">IBM No / IBM Name : </td>
							<td width="5%" height="20" class="txt10"></td>
							<td width="70%" height="20"><div align="left" class="padr5" id="IBM"><input type="hidden" name="isEmployee" value="<?php echo $isEmployee; ?>" />
								                            
                            </td>
                        </tr>
                        <tr>
				  			<td height="20" align="right" class="txt10">Type : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtDealerType" type="text" class="txtfieldLabel" id="txtDealerType"  readonly="yes"/></td>
						</tr>
						
						<tr>
				  			<td height="20" align="right" class="txt10">Credit Limit  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtCLimit" type="text" class="txtfieldLabel" id="txtCLimit" readonly="yes"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Available Credit  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtAvailableCredit" type="text" class="txtfieldLabel" id="txtAvailableCredit"  readonly="yes"/></td>
						</tr>
						
						<tr>
				  			<td height="20" align="right" class="txt10">Unpaid Invoices  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtARBalance" type="text" class="txtfieldLabel" id="txtARBalance" readonly="yes"/></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Unpaid Penalty  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtPenalty" type="text" class="txtfieldLabel" id="txtPenalty"  readonly="yes"/></td>
						</tr>
                        
                    	</table>
            		</td>
                    <td valign="top" width="50%">
                    	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
                    	<tr>
							<td colspan="3" height="15">&nbsp;</td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Sales Order No.  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtSONo" type="text" class="txtfieldLabel" id="txtSONo" size="25" maxlength="25"   readonly="yes"  value="<?php echo $SODOCNo; ?>" /></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Sales Order Date  : </td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtSODate" type="text" class="txtfieldLabel" id="txtSODate" size="12"   readonly="yes"  value="<?php echo $txndate; ?>" /></td>
						</tr>
						<tr>
				  			<td height="20" align="right" class="txt10">Branch Name : </td>
				  			<td height="20" class="txt10">&nbsp;</td>							
				  			<td height="20"><input name="txtBranch" type="text" class="txtfieldLabel" id="txtBranch" readonly="yes" value="<?php echo $branch ; ?>"/></td>
						</tr>	
					
			        		
							
						<tr>
				  			<td height="20" align="right" class="txt10">Document No.  :</td>
				  			<td height="20" class="txt10">&nbsp;</td>
				  			<td height="20"><input name="txtRefNo" type="text" class="txtfieldLabel" id="txtRefNo" size="25" maxlength="15"  readonly="yes"  value="<?php echo $docno; ?>" /></td>
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
		<?php }?>		
          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td height="3" class="bgE6E8D9"></td>
              </tr>
            </table>
          <br /></td>
      </tr>
    </table>
<a name="AnchorHere"></a>
<div id="tbl22" style="display:block"> 
	
	<br>
		
		
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0" class="borderfullgreen">	
		<tr>
			<td class="tab">
				<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 " border="0">
				<tr>
					<td width="4%" class="padl5" align="left" >Line #</td>
					<td width="8%" class="padl5" align="left">Item Code</td>
					<td width="24%" class="padl5" align="left">Item Name</</td>
					<td width="5%" class="" align="center">UOM</td>
					<td width="8%" class="" align="center">PMG</td>					
					<td width="8%" class="padr5" align="right">Regular Price</td>
					<td width="5%" class="" align="center">Promo</td>
					<td width="5%" class="" align="center">SOH</td>				
					<td width="8%" class="" align="center">Intransit Qty</td>
					<td width="8%" class="" align="center">Ordered Qty</td>
					<td width="8%" class="padr5" align="right">CSP </td>	
					<td width="10%" class="padr5" align="right">Total Price </td>				
				</tr>
				</table>
			</td>
		</tr>
			<?php 	if(isset($_GET[TxnID])){?>
			<tr>
			<td class="bgF9F8F7">
				<div class="scroll_350" id="prodlist">				
				<!--Dynamic Table-->
				<table width="100%"  cellpadding="0" cellspacing="1" class="bgFFFFFF" id="dynamicTable" border="0">
			<?php  
			if ($rsOpenDetails->num_rows)
				{
					
					$i = 1;
					$cnt = $rsOpenDetails->num_rows + 1;
					echo "<input type='hidden' name='hcnt' id='hcnt' value=".$cnt." />";
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
									<input name="hSubsID'.$i.'" type="hidden" id="hSubsID'.$i.'" value="" >
									<input name="hKitComponent'.$i.'" type="hidden" id="hKitComponent'.$i.'" value="" />
								</div></td>
								<td width="17%" align="left" class="borderBR padl5"><input name="txtProdDesc'.$i.'" type="text" class="txtfieldLabel" style="width: 220px" id="txtProdDesc'.$i.'"  readonly="yes" value="'.$productdescription.'" /></td>
								<td width="5%" align="center" class="borderBR padl5">Piece</td>
								<td width="5%" align="center" class="borderBR padl5"><input name="txtPMG'.$i.'" type="text" class="txtfieldLabel" id="txtPMG'.$i.'"  value="'.$pmg.'" /><input type="hidden" name="hPMGID'.$i.'" id="hPMGID'.$i.'"  value="'.$pmgid.'" /> <input type="hidden" name="hProductType'.$i.'" id="hProductType'.$i.'"  value="'.$producttype.'" /></td>					
								<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtUnitPrice'.$i.'" class="txtfieldLabel" id="txtUnitPrice'.$i.'" readonly="yes" value="'.$unitprice.'"></td>
								<td width="10%" align="center" class="borderBR padl5">'.$promoCode.' <input type="hidden" name="hPromoID'.$i.'" id="hPromoID'.$i.'" value="'.$promoID.'" /><input type="hidden" name="hPromoType'.$i.'" id="hPromoType'.$i.'" value="'.$promoType.'" /><input type="hidden" name="hForIncentive'.$i.'" id="hForIncentive'.$i.'" value="0"/></td>		
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
			<?php }else {?>
			<tr>
			<td class="bgF9F8F7">
				<div class="scroll_350" id="prodlist">				
				<!--Dynamic Table-->
				<table width="100%"  cellpadding="0" cellspacing="1" class="bgFFFFFF" id="dynamicTable" border="0">
					<tr height="20">
						<input type="hidden" name="hcnt" id="hcnt" />
						<input type="hidden" name="hHasIncentive" value="0" />
						<td width="4%" align="left" class="borderBR padl5">1</td>
						<td width="8%" height="20" class="borderBR padl5"><div align="left" >
							<input name="txtProdCode1" type="text" class="txtfield" id="txtProdCode1"  onKeyPress="return disableEnterKey(this, event,1)" style="width: 75px"  />
							<span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="prod_choices1" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								 //<![CDATA[
									var prod_choices = new Ajax.Autocompleter('txtProdCode1', 'prod_choices1', 'includes/scProductListAjaxSO.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator2'});																			
								//]]>
							</script>
							<input name="hProdID1" type="hidden" id="hProdID1" value="" />
							<input name="hSubsID1" type="hidden" id="hSubsID1" value="" >
							<input name="hKitComponent1" type="hidden" id="hKitComponent1" value="" />
						</div></td>
						<td width="24%" align="left" class="borderBR padl5"><input name="txtProdDesc1" style="width: 300px" type="text" class="txtfield"  id="txtProdDesc1"  readonly="yes" onKeyPress="return disableEnterKey(this, event,1)"/></td>
						<td width="5%" align="center" class="borderBR padl5">Piece</td>
						<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtPMG1" id="txtPMG1" class="txtfieldItemLabel1" readonly="yes" style="text-align:center"/> <input type="hidden" name="hPMGID1" id="hPMGID1" readonly="yes" /><input type="hidden" name="hProductType1" id="hProductType1" /> </td>					
						<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtUnitPrice1" class="txtfieldItemLabel1" id="txtUnitPrice1" readonly="yes" style="text-align:right"></td>
						<td width="5%" align="center" class="borderBR padl5"><div id="divPromo1" name="divPromo1"></div><input type="hidden" name="hPromoID1" id="hPromoID1" value="0"/><input type="hidden" name="hPromoType1" id="hPromoType1" /><input type="hidden" name="hForIncentive1" id="hForIncentive1" value="0"/>&nbsp;</td>		
						<td width="5%" align="center" class="borderBR padl5"><div id="divSOH1" name="divSOH1">&nbsp;</div><input type="hidden" name="hSOH1" id="hSOH1" ></td>					
						<td width="8%" align="center" class="borderBR padl5"><div id="divTransit1" name="divTransit1">&nbsp;</div><input type="hidden" name="hTransit1" id="hTransit1" ></td>
						<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtOrderedQty1" class="txtfield3" id="txtOrderedQty1" onKeyPress="return disableEnterKey(this, event,1);" style="text-align:right"/><input type="hidden" name="hServed1" /></td>
						<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtEffectivePrice1" class="txtfieldItemLabel1" id="txtEffectivePrice1" readonly="yes" style="text-align:right"> </td>					
						<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtTotalPrice1" class="txtfieldItemLabel1" id="txtTotalPrice1" readonly="yes" style="text-align:right"> </td>					
					</tr>
				</table>			
				</div>	
			</td>
		</tr>	
		<?php }?>
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
				  <td width="30%" height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total Qty CFT :</strong>&nbsp;</div></td>
				  <td width="30%" height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totQtyCFT" id="totQtyCFT" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" class="txtfieldLabel"  style="text-align:right" value="0"/></div></td>
				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
				</tr>
				<tr>
				<tr>				  
				  <td width="30%" height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total Qty NCFT :</strong>&nbsp;</div></td>
				  <td width="30%" height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totQtyNCFT" id="totQtyNCFT" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" class="txtfieldLabel"  style="text-align:right"  value="0"/></div></td>
				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
				</tr>
				<tr>				  
				  <td width="30%" height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total Qty CPI :</strong>&nbsp;</div></td>
				  <td width="30%" height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totQtyCPI" id="totQtyCPI" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" class="txtfieldLabel"  style="text-align:right" value="0"/></div></td>
				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
				</tr>
				<tr>				  
				  <td width="30%" height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total Qty :</strong>&nbsp;</div></td>
				  <td width="30%" height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totQty" id="totQty" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" class="txtfieldLabel"  style="text-align:right" value="0"/></div></td>
				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
				</tr>
				<tr>				  
				  <td width="30%" height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total CPI :</strong>&nbsp;</div></td>
				  <td width="30%" height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totCPI" id="totCPI" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" class="txtfieldLabel"  style="text-align:right" value="0.00"/></div></td>
				  <td width="25%" height="25" align="right" class="borderB">&nbsp;</td>
				  <td width="15%" height="25" class="borderBR">&nbsp;</td>
				</tr>
				<tr>				  
				  <td width="30%" height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total CFT :</strong>&nbsp;</div></td>
				  <td width="30%" height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totCFT" id="totCFT" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" class="txtfieldLabel"  style="text-align:right" value="0.00"/></div></td>
				  <td width="25%" height="25" align="right" class="borderBR txt10"><div align="right"><strong>Gross Amount :</strong>&nbsp;</div></td>
				  <td width="15%" height="25" class="borderBR"><div align="right" class="padr5"><input name="txtGrossAmt" type="text" class="txtfieldLabel" size="20" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" style="text-align:right" value="0.00"  /></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total NCFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"><input type="text" name="totNCFT" id="totNCFT" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" class="txtfieldLabel"  style="text-align:right" value="0.00"/></div></td>
				  <td height="20" align="right" class="borderBR"><div align="right"><strong>Total CSP Less (CPI <b id='boldStuff'></b>) :</strong>&nbsp;</div></td>
				 <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtCSPLessCPI" id="txtCSPLessCPI" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" type="text" class="txtfieldLabel" size="20" style="text-align:right" value="0.00"/></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>MTD CFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="MTDCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR"><div align="right"><strong>Basic Discount :</strong>&nbsp;</div></td>
				 <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtBasicDiscount" id="txtBasicDiscount" type="text" class="txtfieldLabel" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" size="20" style="text-align:right" value="0.00"/></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>MTD NCFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="MTDNCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR"><div align="right"><strong>Additional Discount :</strong>&nbsp;</div></td>
					<td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtAddtlDisc" type="text" class="txtfieldLabel" size="20" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" style="text-align:right" value="0.00" ></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>YTD CFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="YTDCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Sales With Vat :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtSalesWVat"  type="text" class="txtfieldLabel" size="20" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" style="text-align:right" value="0.00"></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>YTD NCFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="YTDNCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Vat Amount :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtVatAmt"  type="text" class="txtfieldLabel" size="20" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" style="text-align:right" value="0.00"></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Amount to next discount level - CFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5" id="nextdiscCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Vatable Amount :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtAmtWOVat"  type="text" class="txtfieldLabel" size="20" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" style="text-align:right" value="0.00"></div></td>
				</tr>
				<tr>
				  <td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Amount to next discount level - NCFT :</strong>&nbsp;</div></td>
				  <td height="20" class="borderBR"><div align="right" class="padr5"  id="nextdiscNCFT">0.00</div></td>
				  <td height="20" align="right" class="borderBR"><div align="right"><strong>Additional Discount on Previous Purchase :</strong>&nbsp;</div></td>
					<td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtADPP"  type="text" class="txtfieldLabel" size="20" style="text-align:right" value="0.00" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)"></div></td>
				</tr>
				<tr>
					<td height="20" align="right">&nbsp;</td>
					<td height="20" align="right">&nbsp;</td>
					<td height="20" align="right" class="borderBR txt10"><div align="right"><strong>Total Invoice Amount Due :</strong>&nbsp;</div></td>
				  	<td height="20" class="borderBR"><div align="right" class="padr5"><input name="txtNetAmt"  type="text" class="txtfieldLabel" id="txtNetAmt" style="text-align:right" size="20" readonly="yes" onKeyPress="return disableEnterKey(this, event,0)" value="0.00"></div></td>
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
				<input name="btnSave" type="submit" class="btn" value="Create SI" onclick="return validateSave();"  />				
         		&nbsp;
         		<input name="btnConfirmSO" type="submit" class="btn" value="Create SO" onclick="return validateSaveSO();"  />
				
         		&nbsp;
            	<input name="btnDraft" type="submit" class="btn" value="Save as Draft" onclick="return validateSave();"  />
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
</body>
</form>
<div id="substituteItem" title="Substitute Item">
	<input type="hidden" name="hProdIDSubstitute" id="hProdIDSubstitute" />
	<div id="substitutetable">
	
	</div>
	
</div>
<div id="incentive" title="Available Incentives">
	
	<div id="incentivetable">
	
	</div>
	
</div>
