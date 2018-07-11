<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<style>
  .ui-autocomplete {max-height: 150px;overflow-y: auto;/* prevent horizontal scrollbar */overflow-x: hidden; }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete { height: 100px; }
</style>
<?PHP 
	include IN_PATH.DS."scDealerTransfer.php";
	include IN_PATH.DS."pcTransferDealer.php";
	
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
    
    //display dealer list
    $customerRows = '';
    $cnt = 0;
    if(isset($_POST['btnGenerateTransfer']))
    {
    	if ($rs_customerall->num_rows)
		{
			$cnt=1;
			while ($row = $rs_customerall->fetch_object())
			{
			   $customerRows .= "<tr align='center'> <td width='5%' height='20' class='borderBR' align='center'>&nbsp;<input type='checkbox' name='chkSelect[]' onclick='UnCheck();' id='chkSelect[]' value='$row->ID'></td>
						  <td width='10%' height='20' class='borderBR padl5' align='left'><span class='txt10'>$row->Code</span></td>
						  <td width='20%' height='20' class='borderBR padl5' align='left'><a href='#' class='txtnavgreenlink'>$row->Name</a></td>
						  <td width='20%' height='20' class='borderBR padl5' align='left'>$row->GSU</td>
						  <td width='25%' height='20' class='borderBR padl5' align='left'><span class='txt10'>$row->IBMCode</span></td>
						  <td width='10%' height='20' class='borderBR padr5' align='right'><span class='txt10'>$row->PastDueAmount</span></td>
						  <td width='10%' height='20' class='borderBR padr5' align='right'><span class='txt10'>$row->Penalty</span></td></tr>";
			}
			$rs_customerall->close();
		}
		else
		{
			$customerRows = "<tr align='center'><td height='20' colspan='7' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td></tr>";
		}    	
    }
	else
	{
		$customerRows = "<tr align='center'><td height='20' colspan='7' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td></tr>";
	}
	
	//ibm network dropdown list
	$ibmid = 0;
	$drpIBMNetwork = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboIBMNetwork->num_rows)
    {
     	while ($row = $rs_cboIBMNetwork->fetch_object())
      	{
      		$cust = $row->Code."-".$row->Name;
      		($ibmid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpIBMNetwork .= "<option value='$row->ID'' $sel>$cust</option>";
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

function checkAll(bin) 
{
	var elms = document.frmTransferDealer.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkSelect[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function UnCheck()
{

    var chkAll = document.frmTransferDealer.chkAll;
    chkAll.checked = false;
}

function checker()
{
	var ml = document.frmTransferDealer;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkSelect[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}

function confirmTransfer()
{
	var ml = document.frmTransferDealer;
	var newibm = ml.cboIBMNetwork;
	
	if (!checker())
	{
		alert ('Select atleast one Dealer to transfer.');
		return false;
	}
	if (newibm.value == 0)
	{
		alert ('Select New IBM Network.');
		newibm.focus();
		return false;		
	}
	if (confirm('Are you sure you want to transfer dealer(s)?') == false)
		return false;
	else
		return true;	
}

function confirmCancel()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
		return false;
	else
		return true;
}
function chkSearch()
{
	var form = document.frmGenerateIBM;
	var ibmFrom = 	form.txtFromIBM;
	var ibmTo	=	form.txtToIBM;
	
	if(eval(ibmFrom.value) != "" && eval(ibmTo.value) == "")
	{
		alert("IBM No. Required.");
		ibmTo.focus();
		ibmTo.select();
		return false;
	}

	if(eval(ibmFrom.value) > eval(ibmTo.value))
	{
		alert("Range is not valid.");
		ibmFrom.focus();
		ibmFrom.select();
		return false;
	}
	if(eval(ibmFrom.value) == "" && eval(ibmTo.value) != "" )
	{
		alert("IBM No. Required.");
		ibmFrom.focus();
		ibmFrom.select();
		return false;
	}
	
}

/*
 *  @author: jdymosco
 *  @date: April 29, 2013
 *  @description: New function that will get lists of IBM networks option.
 */
$(function($){
    function sfmIBMAutoComplete(DOMelement){
        $(DOMelement).autocomplete({
            source: function(request, response){
                $.ajax({
                        url: 'pages/sfm/param_ajax_calls/enrollment_manager_ajax.php',
                        type:'POST',
                        data: { 'action':'get_uplines','manager_term':request.term },
                        success: function(data){
                            response( $.map( $.parseJSON(data).uplines, function( item ) {
                                return {
                                    label: '(' + item.mCode + ') -- ' + item.uplineName,
                                    value: item.mCode + '_' + item.ID
                                }
                            }));
                            
                            $("#cbo-lists-span").hide();
                        }
                    });
            },
            search: function(){ $("#cbo-lists-span").show(); },
            select: function( event, ui ) {
                var code_ID = ui.item.value;
                var splits = code_ID.split('_');
                
                $(DOMelement).val(splits[0]);
                $("#cboIBMNetwork").val(splits[1]);
                return false;
            }
        });
    }
    
    sfmIBMAutoComplete("#cboIBMNetwork-list");
    $("#cbo-lists-span").hide();
});
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?PHP
			include("nav_dealer.php");
		?>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Movement</span></td>
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
   		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  		<tr>
    		<td valign="top">
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
   	 				<td class="txtgreenbold13">Dealer Transfer </td>
    				<td>&nbsp;</td>
  				</tr>
				</table>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td valign="top">
    					<form name="frmGenerateIBM" method="post" action="index.php?pageid=72">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  				<tr>
		    				<td>
		    					<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
		        				<tr>
		        					<td width="10%">&nbsp;</td>
  									<td width="30%" align="right">&nbsp;</td>
  									<td width="10%" align="right">&nbsp;</td>
  									<td width="50%" align="right">&nbsp;</td>
		        				</tr>
                                                        <?php /*?>
		         				<tr>
		          					<td align="right" class="padr5"><strong>From IBM No. :</strong></td>
				   					<td><input name="txtFromIBM" type="text" class="txtfield" id="txtFromIBM" size="20" value="<?php if(isset($_POST['txtFromIBM'])) { echo $_POST['txtFromIBM']; }?>"></td>
				   					<td align="right" class="padr5"><strong>To IBM No. :</strong></td>
				   					<td><input name="txtToIBM" type="text" class="txtfield" id="txtToIBM" size="20" value="<?php if(isset($_POST['txtToIBM'])) { echo $_POST['txtToIBM']; }?>"></td>
		        				</tr><?php */?>
		        				<tr>
									<td width="30%" align="right" class="padr5"><strong>Search IBM (Name / Code) :</strong></td>
				  					<td><input name="txtIBMName" type="text" class="txtfield" id="txtIBMName" size="20" value="<?php if(isset($_POST['txtIBMName'])) { echo $_POST['txtIBMName']; }?>"></td>
				  					<td>&nbsp;</td>
				  					<td>&nbsp;</td>
		        				</tr>
		        				<tr>
		          					<td colspan="4">&nbsp;</td>
		        				</tr>
		    					</table>
	    					</td>
	  					</tr>
						</table>
						<br>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		  				<tr>
		    				<td align="center"><input name="btnGenerateTransfer" type="submit" class="btn" value="Generate List" onclick="return chkSearch();"></td>
		  				</tr>
						</table>
    					</form>
		      			<br>
		      			<form name="frmTransferDealer" method="post" action="index.php?pageid=72">
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        		<tr>
		          			<td class="tabmin">&nbsp;</td>
		          			<td class="tabmin2"><span class="txtredbold">Dealer List</span></td>
		          			<td class="tabmin3">&nbsp;</td>
		        		</tr>
		      			</table>
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		      	 		<tr>
	           				<td class="bgF9F8F7"><?php echo $errMessage; ?></td>
	         			</tr>
		        		<tr>
		          			<td valign="top" class="bgF9F8F7">
		          				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              			<tr>
		                			<td>
		                			
		                				<table style="width: 98.40%;"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
		                    			<tr align="center">
		                    		<?php 
                    					if ($cnt==1)
                    					{
                    							$tmpDisabled="";
                    					}
                    					else
                    					{
                    		 					$tmpDisabled = 'disabled="disabled"';
                    					}
                    					?>
		                      				<td height="20" width="5%"><div align="center" class="bdiv_r"><input name="chkAll" type="checkbox" id="chkAll" <?php echo $tmpDisabled;?>value="1" onclick="checkAll(this.checked);" /></div></td>	
		                      				<td height="20" width="10%"><div align="left" class="padl5 bdiv_r">IGS Code</div></td>
		                      				<td height="20" width="20%"><div align="left" class="padl5 bdiv_r">IGS Name</div></td>
		                      				<td height="20" width="20%"><div align="left" class="padl5 bdiv_r">GSU-Type Code</div></td>
		                      				<td height="20" width="25%"><div align="left" class="padl5 bdiv_r">IBM Code-Name</div></td>
		                      				<td height="20" width="10%"><div align="right" class="padr5 bdiv_r">Past Due Amount</div></td>
		                      				<td height="20" width="10%"><div align="right" class="padr5">Penalty</div></td>
		                      			</tr>
		                				</table>
	                				</td>
		              			</tr>
		              			<tr>
		                			<td class="bordergreen_B"><div class="scroll_300" style="overflow-y: scroll;">
		                   				<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
		                   				<tr>
		                   					<td><?php echo $customerRows; ?></td>
		                   				</tr>
		                  				</table>
                          			</div></td>
		              			</tr>
		          				</table>
          					</td>
		        		</tr>
		      			</table>
		      			<br><br>
		      			<table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2" class="bordersolo">
			  			<tr>
		  					<td align="center" height="40">
		  						<b>New IBM Network :</b> 
		  						&nbsp;&nbsp;&nbsp;<?php /*?>
								<select name="cboIBMNetwork" class="txtfield" style="height: 20px; width:300px;">
									<?php echo $drpIBMNetwork; ?>			
            					</select><?php */?>
                                                                <input type="text" class="txtfield" name="cboIBMNetwork-list" id="cboIBMNetwork-list" style="height: 20px; width:300px;"/>
                                                                <input type="hidden" value="" name="cboIBMNetwork" id="cboIBMNetwork" />
                                                                <br /><br /><span id="cbo-lists-span">Searching, please wait...</span>
            				</td>
            			</tr>
            			</table>
						<br>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			  			<tr>
			    			<td align="center">
			    				<input name="btnUpdate" type="submit" class="btn" value="Update" onClick="return confirmTransfer();">
			    				<input name="btnCancel" type="submit" class="btn" value="Cancel" onClick="return confirmCancel();">
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