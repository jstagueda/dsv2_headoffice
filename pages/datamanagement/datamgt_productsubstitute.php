<script language="javascript" src="js/tab-view.js"  type="text/javascript"></script>
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<!--script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script-->
<script language="javascript" src="js/jquery-1.9.1.min.js"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<link type="text/css" href="css/jquery-ui-1.8.5.custom.css" rel="stylesheet"/>
<script src="js/jxPagingListProductSubstitute.js" language="javascript" type="text/javascript"></script>
<?PHP include IN_PATH.DS."scProdSubstitute.php"; ?>
<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery(document).keypress(function(e) {
        if(e.which == 13) {
            jQuery("#txtSearch").focus();
        }

            if(e.which == 9) {
            jQuery("#txtSearch").focus();
        }
    });

    jQuery('[name=txtSDate]').datepicker({
        changeMonth :   true,
        changeYear :   true
    });

    jQuery('[name=txtEDate]').datepicker({
        changeMonth :   true,
        changeYear :   true
    });

});
var $j = jQuery.noConflict();
function getSelectionProductList(text, li)
{

	var txt = text.id;
	var ctr = txt.substr(11, txt.length);

	tmp = li.id;
	tmp_val = tmp.split("_");
	a = eval('document.frmSubs.hdnProd1');
	b = eval('document.frmSubs.txtSubstitute');
	a.value =  tmp_val[0];
	b.value = tmp_val[2];
}

function trim(s)
{

	  var l=0; var r=s.length -1;
	  while(l < s.length && s[l] == ' ')
	  {	l++; }
	  while(r > l && s[r] == ' ')
	  {	r-=1;	}
	  return s.substring(l, r+1);
}

function CheckInclude()
{
	var ci = document.frmSubs.elements["chkInclude[]"];

	for(i=0; i< ci.length; i++)
	{
		if(ci[i].checked == false)
		{
			document.frmSubs.chkAll.checked = false;
		}
	}

	if (document.frmSubs.elements["chkInclude[]"].value > 1)
	{
		if(ci.checked == false)
		{
			document.frmSubs.chkAll.checked = false;
		}
	}
}

function checkAll(bin)
{
	var elms = document.frmSubs.elements;

	for (var i = 0; i < elms.length; i++)
	{
		if (elms[i].name == 'chkInclude[]')
	  	{
			elms[i].checked = bin;
	  	}
	}
}

function confirmAdd()
{
	var title = 'Product Substitute';
	var message = '';
	var ButtonFunction = {};
	
	var code = document.frmSubs.txtCode;
	var desc = document.frmSubs.txtDesc;
	var subs = document.frmSubs.txtSubstitute;
	var hdnprod = document.frmSubs.hdnProd1;
	var start = document.frmSubs.txtSDate;
	var end = document.frmSubs.txtEDate;
	var sdate = new Date(Date.parse(start.value));
	var edate = new Date(Date.parse(end.value));
	
	if((code.value == '') || (desc.value == ''))
	{
		var message = 'Please select a product.';
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');
		}
		popinmessage(title, message, ButtonFunction);
		return false;
	}

	if(subs.value == '')
	{
		message = 'Please input a substitute product.';		
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');			
			subs.focus;
		}
		popinmessage(title, message, ButtonFunction);
		return false;
	}

	if(sdate > edate){
		message = 'End date should be the same or later than Start date.';		
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');
		}
		popinmessage(title, message, ButtonFunction);
		return false;
	}


	if(getDateObject(start.value, "/") > getDateObject(end.value, "/"))
	{
		message = 'End date should be the same or later than Start date.';		
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');
			start.select();
			start.focus();
		}
		popinmessage(title, message, ButtonFunction);
		return false;
	}

	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;

	if(getDateObject(start.value, "/") < getDateObject(now_date, "/"))
	{
		message = 'Start date should be current or future date.';		
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');
			start.select();
			start.focus();
		}
		popinmessage(title, message, ButtonFunction);
		return false;
	}


	if(hdnprod.value == '')
	{
		message = 'Please select a valid substitute.';		
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');
			subs.focus;
		}
		popinmessage(title, message, ButtonFunction);
		return false;
	}

	//window.location.href='index.php?pageid=111&pid='+page;
}

function confirmSave(cnt)
{

	var title = 'Product Substitute';
	var message = '';
	var ButtonFunction = {};
	
	if(cnt == 0){
	
		message = 'Please add a product/s.';		
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');
		}
		popinmessage(title, message, ButtonFunction);
		return false;
		
	}else{
	
		message = 'Are you sure you want to save this record?';		
		
		ButtonFunction['Yes'] = function(){
			jQuery('[name=frmSubs]').append('<input type="hidden" name="btnSave" value="1">');
			jQuery('#dialog-message').dialog('close');
			jQuery('[name=frmSubs]').submit();
		}
		
		ButtonFunction['No'] = function(){
			jQuery('#dialog-message').dialog('close');
		}
		
		popinmessage(title, message, ButtonFunction);
		return false;
		
	}
}

function getDateObject(dateString,dateSeperator)
{
	//This function return a date object after accepting
	//a date string ans dateseparator as arguments
	var curValue=dateString;
	var sepChar=dateSeperator;
	var curPos=0;
	var cDate,cMonth,cYear;

	//extract day portion
	curPos=dateString.indexOf(sepChar);
	cMonth=dateString.substring(0,curPos);

	//extract month portion
	endPos=dateString.indexOf(sepChar,curPos+1);
	cDate=dateString.substring(curPos+1,endPos);

	//extract year portion
	curPos=endPos;
	endPos=curPos+5;
	cYear=curValue.substring(curPos+1,endPos);

	//Create Date Object
	dtObject=new Date(cYear,cMonth,cDate);
	return dtObject;
}

function checker()
{
	var ml = document.frmSubs;
	var len = ml.elements.length;

	for (var i = 0; i < len; i++)
	{
		var e = ml.elements[i];
	    if (e.name == "chkInclude[]" && e.checked == true)
	    {
			return true;
	    }
	}
	return false;
}

function confirmRemove()
{	
	var title = 'Product Substitute';
	var message = '';
	var ButtonFunction = {};
	
	if (!checker())
	{
		message = 'Please select product(s) to be removed.';		
		ButtonFunction['Ok'] = function(){
			jQuery('#dialog-message').dialog('close');
		}
		popinmessage(title, message, ButtonFunction);
		return false;
	}
	else
	{
		message = 'Are you sure you want to remove substitute(s)?';		
		
		ButtonFunction['Yes'] = function(){
			jQuery('[name=frmSubs]').append('<input type="hidden" name="btnRemove" value="1">');
			jQuery('#dialog-message').dialog('close');
			jQuery('[name=frmSubs]').submit();
		}
		
		ButtonFunction['No'] = function(){
			jQuery('#dialog-message').dialog('close');
		}
		
		popinmessage(title, message, ButtonFunction);
		return false;
	}
}

function xautocomplete(){

	jQuery( "#txtSearch" ).autocomplete({
	 source:'includes/ajaxproductLL.php',
		select: function( event, ui ) {
			jQuery( "#txtSearch" ).val( ui.item.ProductCode);

			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return jQuery( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.ProductCode + " - " + item.ProductName+ "</strong></a>" )
			.appendTo( ul );
	};
}

function popinmessage(title, message, buttonFunction){
	jQuery( "#dialog-message p" ).html(message);
    jQuery( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
		draggable: false,
        title: title,
        buttons: buttonFunction
    });
    jQuery( "#dialog-message" ).dialog( "open" );
}

</script>

<style type="text/css">
div.autocomplete {

  position:absolute;
  /*width:300px;*/
  /*border:1px solid #888;
  margin:0px;
  padding:0px;*/

}

div.autocomplete span { position:relative; top:2px; }

div.autocomplete ul {
    max-height: 150px;
    overflow-x: hidden;
    overflow-y: auto;
    width: 319px;
    border: 1px solid #FF00A6;
    color: #312E25;
    list-style-type:none;
    margin:0px;
    padding:0px;
    border-radius:6px;
    background-color:white;
    background: #F5F3E5;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
  /*font-size: 10px;*/
}

div.autocomplete ul li.selected{
    background-color: #EB0089;
    border:1px solid #c40370;
    color:white;
    font-weight:bold;
    margin:3px;
    border-radius:6px;
}

div.autocomplete ul li {
    line-height: 1.5;
    padding: 0.2em 0.4em;
    list-style-type:none;
    display:block;
    /*height:20px;*/
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    cursor:pointer;
}

.ui-dialog .ui-dialog-titlebar-close span{margin : -10px 0 0 -10px;}
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?PHP
			include("nav.php");
		?>
	<br></td>
	<td class="divider">&nbsp;</td>
	<td valign="top" style="min-height: 610px; display: block;">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Promo and Pricing Management System (PPMS)</span></td>
		</tr>
		</table>
		<br />
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td class="txtgreenbold13">Product Substitute</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<?php
			if (isset($_GET['msg']) != "")
			{
			?>
			<br>
			<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td>
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $_GET['msg']; ?></b></td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
			<?php
				}
			?>
		<br />
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		
 		<tr>
			<td valign="top">
			  	
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  				<tr>
					<td width="39%" valign="top">
					<form name="frmSearchProdSubs" method="post" action="index.php?pageid=111">
                                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
				  		<td class="tabmin">&nbsp;</td>
				  		<td class="tabmin2"><span class="txtredbold">Action</span></td>
				  		<td class="tabmin3">&nbsp;</td>
					</tr>
				  	</table>
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
					<tr>
						<td>
							<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
							<tr>
					  			<td></td>
					  			<td width="5%">&nbsp;</td>
					  			<td>&nbsp;</td>
							</tr>
							<tr>
					  			<td align="right"><b>Code / Name</b></td>
								<td align="center">:</td>
								<td style="padding:3px;">
						  			<input name="txtfldsearch" type="text" class="txtfield" id="txtSearch" autocomplete="off" onkeypress = "xautocomplete();" size="20" value="<?php echo $search; ?>">
					  				<input name="btnSearch" type="submit" class="btn" value="Search">
					  			</td>
							</tr>
							<tr>
					  			<td>&nbsp;</td>
					  			<td align="right">&nbsp;</td>
					  			<td align="right">&nbsp;</td>
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
				  		<td class="tabmin2"><span class="txtredbold">Product Substitute List </span></td>
				  		<td class="tabmin3">&nbsp;</td>
					</tr>
				  	</table>
				  	 <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
				        <tr>
				          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				              <tr>
				                <td valign="top" class="">
									<div id="pgContent">
										<div style="padding:10px; text-align:center;">Loading... Please wait...</div>
				                    </div>
				                  </td>
				              </tr>
				          </table></td>
				        </tr>
				      </table>
				      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				        <tr>
				          <td height="3" class="bgE6E8D9"></td>
				        </tr>
				      </table>
				      <br>
				      <table width="98%"  border="0" cellspacing="0" cellpadding="0" align="center">
				        <tr>
				            <td height="20" class="txtblueboldlink" width="50%">
								<div id="pgNavigation"></div>
				            </td>
				            <!--<td height="20" class="txtblueboldlink" width="48%">
								<div id="pgRecord" align="right"><b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif"></div>
				            </td>-->
				        </tr>
				    </table>
				      <br />
				      <br />
				      <br />
				    <script>
						//Onload start the user off on page one
						window.onload = showPage("1", "<?php echo $search;?>");
				    </script>
	          			</td>
		        	</tr>
		      		</table>
					</td>
					<td width="60%" valign="top">

		<form name="frmSubs" method="post" action="index.php?pageid=111" >
			<table width="98%"  border="0" align="left" cellpadding="0" cellspacing="0">
				<tr>
				<td class="tabmin">&nbsp;</td>
				<td class="tabmin2"><span class="txtredbold">Substitute Details</span>&nbsp;</td>
				<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="98%"  border="0" align="left" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
				<tr>
					<td class="bgF9F8F7">
				</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">&nbsp;</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">
						<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
							<tr>
								<td></td>
								<td width="5%"></td>
								<td></td>
							</tr>
							<tr>
								<td align="right" class="txt10" style="padding:3px;"><b>Code</b></td>
								<td align="center">:</td>
								<td style="padding:3px;">
									<?PHP echo $code; ?>
									<input type="hidden" name="txtCode" id="txtCode" maxlength="50" size="40" class="txtfield" value="<?PHP echo $code; ?>" />
								</td>
							</tr>
							<tr>
								<td align="right" class="txt10" style="padding:3px;"><b>Description</b></td>
								<td align="center">:</td>
								<td style="padding:3px;">
									<?PHP echo $name; ?>
									<input type="hidden" name="txtDesc" id="txtDesc" maxlength="50" size="40" class="txtfield" value="<?PHP echo $name; ?>" />
								</td>
							</tr>
							<tr>
								<td align="right" class="txt10"><b>Substitute</b></td>
								<td align="center">:</td>
								<td style="padding:3px;">
									<input type="text" name="txtSubstitute" id="txtSubstitute" maxlength="50" size="40" class="txtfield" value="<?PHP echo $substitute; ?>" />
									<input name="hProdid2" type="hidden" id="hProdid2" value="<?php echo $prodid;?>"/>
									<span id="indicator1" style="display: none">
										<img src="images/ajax-loader.gif" alt="Working..." />
									</span>
									<div id="prod_choices1" class="autocomplete" style="display:none"></div>
									<script type="text/javascript">
										//<![CDATA[
										var prod_choices = new Ajax.Autocompleter('txtSubstitute', 'prod_choices1', 'includes/scProductSubs_ajax.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator1'});																			//]]>
									</script>
									<input name="hdnProd1" type="hidden" id="hdnProd1" value=""/>
								</td>
							</tr>
							<tr>
								<td align="right" class="txt10"><b>Start Date</b></td>
								<td align="center">:</td>
								<td style="padding:3px;">
									<input name="txtSDate" type="text" class="txtfield" readonly="readonly" id="txtSDate" size="20" value="<?php echo $start;?>" size="30" >
								</td>
							</tr>
							<tr>
								<td align="right" class="txt10"><b>End Date</b></td>
								<td align="center">:</td>
								<td style="padding:3px;">
									<input name="txtEDate" type="text" class="txtfield" id="txtEDate" readonly="readonly" size="20" value="<?php echo $end;?>" size="30" >
								</td>
							</tr>
							<tr>
								<td align="right" class="txt10"><b>Show only if SOH = 0</b></td>
								<td align="center">:</td>
								<td style="padding:3px;">
									<input type="checkbox" name="chckShow" ID="chckShow" />
								</td>
							</tr>
							<?php if ($_SESSION['ismain'] == 1){?>
							<tr>
								<td colspan=2></td>
								<td><input name="btnAdd" type="submit" class="btn" value="Add" onclick="return confirmAdd()">&nbsp;&nbsp;</td>
							</tr>
							<?php }?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">&nbsp;</td>
				</tr>
		   </table>		
			<div style="clear:both;">&nbsp;</div>
        <table width="98%"  border="0" align="left" cellpadding="0" cellspacing="0">
			<tr>
				<td class="tabmin">&nbsp;</td>
				<td class="tabmin2"><span class="txtredbold">List of Substitutes</span>&nbsp;</td>
				<td class="tabmin3">&nbsp;</td>
			</tr>
		</table>
		<table width="98%" border="0" align="left" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
				<tr>
					<td valign="top" class="bgF9F8F7">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td style="border-bottom:2px solid #FFA3E0; font-weight:bold;">
								<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
								<tr align="center">
						 			<td width="5%" class="bdiv_r" style='border-right:none;background:#FFDEF0;' align="center"><input name="chkAll" type="checkbox" id="chkAll" value="1"  onclick="checkAll(this.checked);"></td>
	                                <td width="15%" class="bdiv_r padl5" style='border-right:none;background:#FFDEF0;' align="left">Item Code</td>
	                                <td width="25%" class="bdiv_r padl5" style='border-right:none;background:#FFDEF0;' align="left">Description</td>
	                                <td width="20%" class="bdiv_r" style='border-right:none;background:#FFDEF0;' align="center">Start</td>
	                                <td width="15%" class="bdiv_r" style='border-right:none;background:#FFDEF0;' align="center">End</td>
	                                <td width="15%" align="center" style='border-right:none;background:#FFDEF0;'>SOH = 0</td>
								</tr>
								</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">
						<div style="overflow:auto; max-height:200px;">
						<table width="100%"  border="0" cellpadding="0" cellspacing="0">
						<?php
							if(isset($_GET['prodid']) && isset($_SESSION['subs']))
							{
								if (sizeof($_SESSION['subs']))
								{
									$subs_list = $_SESSION["subs"];
									$rowalt=0;
									//while($row = $rspromobuyin->fetch_object())
									for ($i=0, $n=sizeof($subs_list); $i < $n; $i++ )
									{


										$rowalt++;
										($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
										$prodcode = $subs_list[$i]['ProdCode'];
										$proddesc = $subs_list[$i]['ProdName'];
										$chk = $subs_list[$i]['Check'];
										$sDate = strtotime($subs_list[$i]['SDate']);
										$eDate = strtotime($subs_list[$i]['EDate']);
										$prodid = $subs_list[$i]['ProdID'];
										$prodsubsid = $subs_list[$i]['SubsID'];
										$sDate2 = date("m/d/Y", $sDate);
										$eDate2 = date("m/d/Y", $eDate);
										$show = "";
										if ($chk == 1)
										{
											$show = "Yes";
										}
										else
										{
											$show = "No";
										}
										echo "<tr align='center' class='$class'>
												<td width='5%' class='borderBR'><input name='chkInclude[]' type='checkbox' id='chkInclude[]' value='$i' onclick='return CheckInclude();'></td>
                                            		<input type='hidden' name='hprodid$i' id='hprodid$i' value='$prodid'/>
													<input type='hidden' name='hsubsid$i' id='hsubsid$i' value='$prodsubsid'/>
												<td width='15%' height='20' class='borderBR'><div align='left' class='padl5'>$prodcode</div></td>
												<td width='35%' height='20' class='borderBR'><div align='left' class='padl5'>$proddesc</div></td>
												<td width='15%' height='20' class='borderBR'><div align='center'>$sDate2</div></td>
												<td width='15%' height='20' class='borderBR'><div align='center'>$eDate2</div></td>
												<td width='15%' height='20' class='borderBR'><div align='center'>$show</div></td>
										</tr>";

									}
								}
								else
								{
									echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";
								}
							}
							else
							{
								echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";
							}
						?>
						</table>
						</div>
					</td>
				</tr>
				</table>
					
				<?php
				if ($_SESSION['ismain'] == 1)
				{
				?>
		       	<table width="90%"  border="0" align="left" cellpadding="0" cellspacing="0">
		       	<tr>
       				<td colspan="3" height="20">&nbsp;</td>
       			</tr>
				<tr>
					<td>&nbsp;</td>
				<?php
					if(isset($_SESSION['subs'])){
						$cnt = sizeof($_SESSION['subs']);
					}else{
						$cnt = 0;
					}
				?>
				 	<td align="center">
				 		<input name="btnSave" type="submit" class="btn" value="Save"  onClick ="return confirmSave(<?php echo $cnt;?>)">
						<?php if ($_SESSION['ismain'] == 1){
						if(isset($_SESSION['subs'])){ ?>
							<input name="btnRemove" type="submit" class="btn" value="Remove" onClick="return confirmRemove();">
						<?php }}?>
				 		<input name="btnCancel" type="submit" class="btn" value="Cancel">
			 		</td>
				 	<td>&nbsp;</td>
				</tr>
				<tr>
       				<td colspan="3" height="20">&nbsp;</td>
       			</tr>
				</table>
				<?php
				}
				?>
      </form>
	</td>
  </tr>
</table>
<br>

		</td>
	</tr>
   </table>

    </td>
  </tr>
</table>
<div style="display:none;" id="dialog-message">
	<p></p>
</div>
