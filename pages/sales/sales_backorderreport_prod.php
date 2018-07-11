<?php
/*   
  @modified by John Paul Pineda.
  @date December 13, 2012.
  @email paulpineda19@yahoo.com         
*/ 
?>

<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jxPagingORTxnRegister.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<?php include(IN_PATH.DS.'scBackOrderRptByProd.php'); ?>

<script type="text/javascript">
function MM_jumpMenu(targ, selObj, restore) { 		

  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"#MMHead"+"'");
  if(restore) selObj.selectedIndex=0;
}

function openPopUp(branchID, filterID, campaignID, fromdate, toDate, vProdLine, vCustCode) {

	var objWin;
  
	popuppage="pages/sales/sales_borpt_prod_print.php?branchID="+branchID+"&filterID="+filterID+"&campaignID="+campaignID+"&fromdate="+fromdate+"&toDate="+toDate+"&vProdLine="+vProdLine+"&vCustCode="+vCustCode+"&isDetailed="+isDetailed;
		
	if(!objWin) objWin=NewWindow(popuppage, 'printps', '800', '500', 'yes');	
	
	return false;  		
}

function NewWindow(mypage, myname, w, h, scroll) {

	var winl=(screen.width-w)/2;
	var wint=(screen.height-h)/2;
	winprops="height="+h+", width="+w+", top="+wint+", left="+winl+", scrollbars="+scroll+", resizable, menubar=yes, toolbar=no";
  
	win=window.open(mypage, myname, winprops);
  
	if(parseInt(navigator.appVersion)>=4) { win.window.focus(); }
}

var $tpi=jQuery.noConflict(), isDetailed=1;

$tpi(document).ready(function() {
  
 $tpi(".tpi-link-button").click(function() {
  
  if($tpi(this).attr('id')=='detailed_backorder_report') isDetailed=1;
  else isDetailed=2;
  
  $tpi(".tpi-link-button").removeClass('tpi-active-link-button');
  $tpi(this).addClass('tpi-active-link-button');
  $tpi(".backorder-report").fadeOut('slow');  
  $tpi("."+$tpi(this).attr('id').replace(/_/g, "-")).fadeIn('slow');
 }); 
});
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <br />
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="txtgreenbold13">Back Order Report - By Product</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <br />
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td>
            <form name="frmORRegister" method="post" action="index.php?pageid=155&branchID=<?php echo $branchID; ?>">
              <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
                <tr>
                  <td width="8%">&nbsp;</td>
                  <td width="91%" align="right">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20" class="padr5" align="right">Branch :</td>
                  <td height="20">
                    <select name="cboBranch" style="width:160px;" onChange="MM_jumpMenu('parent', this, 0);" class="txtfield">          				
                      <option value="index.php?pageid=155&branchID=0">[Select Here]</option> 
                      <?php $rsBranch=$sp->spSelectBranchByName($database); ?>
                      <?php if($rsBranch->num_rows): while($row=$rsBranch->fetch_object()): ?>
                      <option value="index.php?pageid=155&branchID=<?php echo $row->ID; ?>"<?php echo $_GET['branchID']==$row->ID?' selected="selected"':''; ?>><?php echo $row->Name; ?></option>                                                                                                           
                      <?php endwhile; endif; ?>                                                                      
                    </select>
                  </td>				 
                </tr>
                <tr>
                  <td height="20" class="padr5" align="right">
                    <input type="radio" name="rdFilter" value="1"<?php echo $filter==1?' checked="checked"':''; ?>>From Date :
                  </td>
                  <td height="20">
                    <input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $fromdate; ?>" />
                    <input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" " />
                    <div id="divStartDate" style="background-color:white; position:absolute; visibility:hidden;"> </div>
                  </td>				 
                </tr>
                <tr>
                  <td height="20" class="padr5" align="right"">To Date :</td>
                  <td height="20">
                    <input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $todate; ?>" />	        			
                    <input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" " />
                    <div id="divEndDate" style="background-color:white; position:absolute; visibility:hidden;"> </div>	
                  </td>
                </tr>
                <tr>
                  <td height="20" class="padr5" align="right">
                    <input type="radio" name="rdFilter" value="2"<?php echo $filter==2?' checked="checked"':''; ?> />Campaign :
                  </td>
                  <td height="20">
                    <select name="cboCampaign" class="txtfield" style="width:160px;">
                      <?php $rsCampaign=$sp->spSelectCampaign($database); ?>
                      <?php if($rsCampaign->num_rows): while($row=$rsCampaign->fetch_object()): ?>
                      <option value="<?php echo $row->ID; ?>"<?php echo $campaignID==$row->ID?' selected="selected"':''; ?>><?php echo $row->Name; ?></option>
                      <?php endwhile; endif; ?>                
                    </select>
                  </td>				 
                </tr>
                <tr>
                  <td height="20"  class="padr5" align="right">Product Line :</td>            
                  <td height="20" align="left">
                    <input name="txtSearchProdLine" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearchProdLine; ?>" />              
                  </td>
                </tr>
                <tr>
                  <td height="20"  class="padr5" align="right">Product Code :</td>            
                  <td height="20" align="left">
                    <input name="txtSearchProdCode" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearchProdCode; ?>" />
                    <input name="btnSearch" type="submit" class="btn" value="Search" />						 
                  </td>
                </tr>
                <tr>
                  <td colspan="2" height="20">&nbsp;</td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
      <br />
      
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td align="center"><span id="detailed_backorder_report" class="tpi-link-button tpi-active-link-button">Detailed</span> | <span id="summarized_backorder_report" class="tpi-link-button">Summarized</span></td>
          <td>&nbsp;</td>
        </tr>
      </table>
      
      <!-- Detailed Back Order Report - By Product starts here... -->
      <div class="backorder-report detailed-backorder-report">
        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tabmin">&nbsp;</td>
            <td class="tabmin2 txtredbold">Detailed Back Order Report - By Product</td>
            <td class="tabmin3">&nbsp;</td>
          </tr>
        </table>
        <table width="95%" align="center" border="0" cellpadding="0" cellspacing="1" class="bordergreen">        
          <tr>
            <td valign="top">
              <div class="scroll_500">
                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="1">
                  <tr align="center" class="tab txtdarkgreenbold10">
                    <td height="20" class="bdiv_r" align="center" width="5%">Product Code</td>
                    <td height="20" class="bdiv_r" align="center" width="20%">Product Description</td>
                    <td height="20" class="bdiv_r" align="center" width="12%">Product Line</td>
                    <td height="20" class="bdiv_r padl5" align="center" width="5%">Branch ID</td>
                    <td height="20" class="bdiv_r padr5" align="center" width="8%">Customer Code</td>
                    <td height="20" class="bdiv_r padr5" align="center" width="15%">Customer Name</td>
                    <td height="20" class="bdiv_r padr5" align="center" width="5%">SO Number</td>
                    <td height="20" class="bdiv_r padr5" align="center" width="7%">SO Date</td>
                    <td height="20" class="bdiv_r" align="center" width="7%">Ordered Qty</td>
                    <td height="20" class="bdiv_r" align="center" width="7%">Served Qty</td>
                    <td height="20" class="bdiv_r" align="center" width="7%">Back Order Qty</td>
                  </tr>
                  <?php if($rsDetailedDetails->num_rows): ?>
                  <?php
                  $tmpProdID='';
                  $tmpServed=0;
                  $tmpOrdered=0;
                  $tmpBackOrdered=0;
                  $cnt=0;
                  ?>
                  <?php while($row=$rsDetailedDetails->fetch_object()): $prodID=$row->prodID; ?>
                  <?php	if($tmpProdID!=$prodID): ?>          
                  <?php if($cnt!=0): ?>          
                  <tr align="center" class="tab">          
                    <td height="20" class="borderBR" align="right" colspan="8"><b>Total :  </b></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $tmpOrdered; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $tmpServed; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $tmpBackOrdered; ?></td>
                  </tr>
                  <?php endif; ?>
                  <?php           
                  $tmpServed=0;
                  $tmpOrdered=0;
                  $tmpBackOrdered=0;
                  ?>
                  <tr align="center" class="tab">
                    <td height="20" class="borderBR" align="center" width="5%"><?php echo $row->prodCode; ?></td>
                    <td height="20" class="borderBR" align="center" width="20%"><?php echo $row->prodName; ?></td>
                    <td height="20" class="borderBR" align="center" width="12%"><?php echo $row->prodLine; ?></td>
                    <td height="20" class="borderBR" align="center" width="5%"><?php echo $row->branchName; ?></td>
                    <td height="20" class="borderBR" align="center" width="8%"><?php echo $row->custCode; ?></td>
                    <td height="20" class="borderBR" align="center" width="15%"><?php echo $row->custName; ?></td>
                    <td height="20" class="borderBR" align="center" width="5%"><?php echo $row->SONo; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->TxnDate; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->orderedQty; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->servedQty; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->boQty; ?></td>
                  </tr>
                  <?php 
                  $tmpServed=$row->servedQty;
                  $tmpOrdered=$row->orderedQty;
                  $tmpBackOrdered=$row->boQty;
                  ?>
                  
                  <?php else: ?>          
                  <tr align="center" class="tab">
                    <td height="20" class="borderBR" align="left" width="5%"></td>
                    <td height="20" class="borderBR" align="left" width="20%"></td>
                    <td height="20" class="borderBR" align="left" width="12%"></td>
                    <td height="20" class="borderBR" align="left" width="5%"><?php echo $row->branchName; ?></td>
                    <td height="20" class="borderBR" align="left" width="8%"><?php echo $row->custCode; ?></td>
                    <td height="20" class="borderBR" align="left" width="15%"><?php echo $row->custName; ?></td>
                    <td height="20" class="borderBR" align="left" width="5%"><?php echo $row->SONo; ?></td>
                    <td height="20" class="borderBR" align="left" width="7%"><?php echo $row->TxnDate; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->orderedQty; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->servedQty; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->boQty; ?></td>
                  </tr>            
                  <?php 
                  $tmpServed=$tmpServed+$row->servedQty;
                  $tmpOrdered=$tmpOrdered+$row->orderedQty;
                  $tmpBackOrdered=$tmpBackOrdered+$row->boQty;
                  ?>
                  
                  <?php endif; ?>
                  <?php
                  $tmpProdID=$prodID;
                  $cnt=$cnt+1;
                  ?>
                  <?php endwhile; ?>
                                                  
                  <?php if($cnt==$rsDetailedDetails->num_rows): ?>            
                  <tr align="center" class="tab">          
                    <td height="20" class="borderBR " align="right" colspan="8"><b>Total :  </b></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $tmpOrdered; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $tmpServed; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $tmpBackOrdered; ?></td>
                  </tr>
                  <?php endif; ?>			
                  <?php endif; ?>          
                </table>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <!-- Detailed Back Order Report - By Product ends here... -->
      
      <!-- Summarized Back Order Report - By Product starts here... -->
      <div class="backorder-report summarized-backorder-report">
        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tabmin">&nbsp;</td>
            <td class="tabmin2 txtredbold">Summarized Back Order Report - By Product</td>
            <td class="tabmin3">&nbsp;</td>
          </tr>
        </table>
        <table width="95%" align="center" border="0" cellpadding="0" cellspacing="1" class="bordergreen">        
          <tr>
            <td valign="top">
              <div class="scroll_500">
                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="1">
                  <tr align="center" class="tab txtdarkgreenbold10">
                    <td height="20" class="bdiv_r" align="center" width="5%">Product Code</td>
                    <td height="20" class="bdiv_r" align="center" width="20%">Product Description</td>
                    <td height="20" class="bdiv_r" align="center" width="12%">Product Line</td>                                                                                                    
                    <td height="20" class="bdiv_r" align="center" width="7%">Total Ordered Qty</td>
                    <td height="20" class="bdiv_r" align="center" width="7%">Total Served Qty</td>
                    <td height="20" class="bdiv_r" align="center" width="7%">Total Back Order Qty</td>
                  </tr>
                  <?php if($rsSummarizedDetails->num_rows): ?>                  
                  <?php while($row=$rsSummarizedDetails->fetch_object()): $prodID=$row->prodID; ?>                                                             
                  <tr align="center" class="tab">
                    <td height="20" class="borderBR" align="center" width="5%"><?php echo $row->prodCode; ?></td>
                    <td height="20" class="borderBR" align="center" width="20%"><?php echo $row->prodName; ?></td>
                    <td height="20" class="borderBR" align="center" width="12%"><?php echo $row->prodLine; ?></td>                                                                                                    
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->totalOrderedQty; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->totalServedQty; ?></td>
                    <td height="20" class="borderBR" align="center" width="7%"><?php echo $row->totalBOQty; ?></td>
                  </tr>                                                             
                  <?php endwhile; ?>                                                                    			
                  <?php endif; ?>          
                </table>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <!-- Summarized Back Order Report - By Product ends here... -->
      
      <br />
      <table width="95%"  border="0" align="center">
        <tr>
          <td height="20" align="center">
            <input type="button" id="print_report" name="print_report" value="Print" onclick="return openPopUp(<?php echo $branchID;?>, <?php echo $filter;?>, <?php echo $campaignID;?>, '<?php echo $fromdate;?>', '<?php echo $todate;?>', '<?php echo $vSearchProdLine;?>', '<?php echo $vSearchProdCode;?>');" class="btn" />
            <a class="txtnavgreenlink" href="index.php?pageid=18">
              <input name="Button" type="button" class="btn" value="Back" />
            </a>        
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%Y-%m-%d",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEndDate",     // id of the input field
		ifFormat       :   "%Y-%m-%d",       // format of the input field
		displayArea    :	"divEndDate",
		button         :    "anchorEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>