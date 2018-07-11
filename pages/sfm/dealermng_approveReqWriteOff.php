<?PHP
	include IN_PATH.DS."scCreateWriteOffApprove.php";
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
	var elms = document.frmWriteOffApprove.elements;

	for (var i = 0; i < elms.length; i++)
	{
		if (elms[i].name == 'chkInclude[]')
	  	{
	  		elms[i].checked = bin;
	  	}
	}
}

function CheckInclude()
{
	var elms = document.frmWriteOffApprove.elements;

	for (var i = 0; i < elms.length; i++)
	{
		if (elms[i].name == 'chkInclude[]')
	  	{
	  		if (elms[i].checked == false)
	  		{
	  			document.frmWriteOffApprove.chkAll.checked = false;
    			break;
	  		}
	  	}
	}
}

function NewWindow(mypage, myname, w, h, scroll)
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp()
{
	var objWin;
	popuppage = "pages/datamanagement/datamgt_dealerinfopopup.php?empid=5";

	if (!objWin)
	{
		objWin = NewWindow(popuppage,'printps','800','700','yes');
	}

	return false;
}

function checker()
{
	var ml = document.frmWriteOffApprove;
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

function validateApprove()
{
	if (!checker())
	{
		alert('Please select Write Off(s) to be approved.');
		return false;
	}
	else
	{
		if (confirm('Are you sure you want to approve Write Off(s)?') == false)
		{
			return false;
		}
	}
}

function validateDisapprove()
{
	if (!checker())
	{
		alert('Please select Write Off(s) to be disapproved.');
		return false;
	}
	else
	{
		if (confirm('Are you sure you want to disapprove Write Off(s)?') == false)
		{
			return false;
		}
	}
}

$(function(){

        $('[name=lstBranchlist]').autocomplete({
            source  :   function(request, response){
                $.ajax({
                    type    :   "post",
                    dataType:   "json",
                    data    :   {searched   :   request.term},
                    url     :   "includes/scCreateWriteOffApprove.php",
                    success :   function(data){
                        response($.map(data, function(item){
                            return{
                                label   :   item.Label,
                                value   :   item.Value,
                                Code    :   item.Code,
                                ID      :   item.ID
                            }
                        }));
                    }
                });
            },
            select  :   function(event, ui){
                $('[name=lstBranch]').val(ui.item.ID);
                $('[name=txtBatchCode]').val(ui.item.Code);
            }
        });

});
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?PHP
			include("nav_dealer.php");
		?>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Management </span></td>
        </tr>
    	</table>
    	<br />
		<form name="frmWriteOffApprove" method="post" action="index.php?pageid=93">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
    		<td valign="top">
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
    				<td class="txtgreenbold13">Approve Request for Write-Off</td>
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
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="70%" class="txtredsbold">&nbsp;<b><?php echo $errmsg; ?></b></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php
				}
				?>
				<?php
				if ($msg != "")
				{
				?>
				<br>
				<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td>
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $msg; ?></b></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php
				}
				?>
				<br />
				<table width="95%" border="0" align="left" cellpadding="0" cellspacing="1">
				<tr>
					<td width="50%" valign="top">

						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        				<tr>
        					<td valign="top">
                                                    <table width="80%"  border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                        <td class="tabmin">&nbsp;</td>
                                                        <td class="tabmin2"><span class="txtredbold">Action</span></td>
                                                        <td class="tabmin3">&nbsp;</td>
                                                </tr>
                                            </table>
            					<table style="border-top:none;" width="80%" border="0" align="left" cellspacing="1" cellpadding="0" class="bordersolo">
            					<tr>
            						<td height="10" colspan="2">&nbsp;</td>
            					</tr>
           						<tr>
           							<td width="10%" height="20" align="right" class="padr5 txt10"><strong>Branch :</strong></td>
									<td width="70%" height="20" class="txt10">
                              			<input type="hidden" name="hdnBranchCode" value="<?php echo $_GET['swid']; ?>" />
                                                <input name="lstBranchlist" style="width:160px " class="txtfield" value="<?=$_POST['lstBranchlist']?>">
                                  		<input name="lstBranch" style="width:160px " class="txtfield" type="hidden" value="<?=(isset($_POST['lstBranch']))?$_POST['lstBranch']:0;?>">
                          			</td>
								</tr>
           						<tr>
           							<td height='20' align='right' class='padr5 txt10'><strong>Branch Code :</strong></td>
           							<td height='20' class='txt10'>
                                                                    <input type="text" class="txtfield" name="txtBatchCode" value="<?php echo $bcode; ?>" ></td>
           						</tr>
           						<tr>
           							<td height="20">&nbsp;</td>
           							<td height="20"><input name="btnGenerate" type="submit" class="btn" value="Generate List"></td>
           						</tr>
           						<tr>
            						<td height="10" colspan="2">&nbsp;</td>
            					</tr>
          						</table>
    						</td>
						</tr>
						</table>
						<br>
					</td>
				</tr>
				</table>
				<br>
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td valign="top">
      					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        				<tr>
          					<td class="tabmin">&nbsp;</td>
          					<td class="tabmin2"><span class="txtredbold">Dealer List</span></td>
          					<td class="tabmin3">&nbsp;</td>
        				</tr>
      					</table>
      					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
        				<tr>
          					<td valign="top" class="bgF9F8F7">
          						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              					<tr>
                					<td class="tab">
                						<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
                    					<tr align="center">
											<td width="5%" class="bdiv_r"><div align="center"><input name="chkAll" type="checkbox" id="chkAll"  onclick="checkAll(this.checked);" /></div></td>
											<td width="10%" class="bdiv_r"><div align="center"><span class="txtredbold">Code</span></div></td>
											<td width="20%" class="bdiv_r"><div align="center"><span class="txtredbold">IGS Name</span></div></td>
											<td width="20%" class="bdiv_r"><div align="center"><span class="txtredbold">IBM Name</span></div></td>
											<td width="15%" class="bdiv_r"><div align="center"><span class="txtredbold">Days Overdue</span></div></td>
											<td width="15%" class="bdiv_r"><div align="center"><span class="txtredbold">Overdue Amount</span></div></td>
											<td width="15%"><div align="center"><span class="txtredbold">Reason</span></div></td>
                  						</tr>
                						</table>
        							</td>
      							</tr>
              					<tr>
                					<td class="bordergreen_B"><div class="scroll_300">
                   						<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
											<?php
												if ($rs_dealers->num_rows)
							                 	{
							                     	$n = $rs_dealers->num_rows;
						                    		$cnt=0;
						                    		echo  "<input name='hdnCountResult' type='hidden' value='$n'/> ";
						                     		while($row = $rs_dealers->fetch_object())
						                     	    {
														$cnt ++;
							                            ($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
							                     		$id = $row->ID;
							                     		$code = $row->IGSCode;
							                     		$igsName =$row->IGSName;
							                     		$ibmName= $row->IBMID;
							                     		$daysdue = $row->DaysDue;
							                     		$amountDue = $row->OverdueAmount;
							                     		$reason = $row->Reason;
							                     		$woid = $row->WriteOffID;

														  echo "<tr class='$alt'>
														  			<td width='5%' height='20' class='borderBR' align='center'>
														  				<input name='chkInclude[]' type='checkbox' id='chkInclude[]' onclick='return CheckInclude();' value='$id'>
														  				<input name='hdnIID$cnt' type='hidden'  value='$cnt'>
														  				<input name='hdnWriteOffID$cnt' type='hidden' value='$woid'>
														  			</td>
														  			<td width='10%' height='20' class='padl5 borderBR' align='left'><span class='txt10'>$code</span></td>
														  			<td width='20%' height='20' class='padl5 borderBR' align='left'>$igsName</td>
														  			<td width='20%' height='20' class='padl5 borderBR' align='left'><span class='txt10'>";
																	   $rs_ibmName = $sp->spSelectCustomer($database, $ibmName,'');
																	   if($rs_ibmName->num_rows)
																	   {
																	   		while($row_ibmNames = $rs_ibmName->fetch_object())
																	   		{
																	   			$ibmNames= $row_ibmNames->Name;
																	   		}
																	   		$rs_ibmName->close();
																	   }
														  				echo "   $ibmNames</span></td>
														  			<td width='15%' height='20' class='padr5 borderBR' align='right'><span class='txt10'>$daysdue</span></td>
														  			<td width='15%' height='20' class='padr5 borderBR' align='right'><span class='txt10'>$amountDue  <input name='hdnPastDue$cnt' type='hidden' value='$amountDue'/></span></td>
														  			<td width='15%' height='20' class='borderBR' align='center' valign='middle'>$reason</td>
																</tr>" ;
						                     				}
						             					}
						                    			else
						                    			{
						                    				echo "<tr align='center'><td width='100%' height='20' class='borderBR bgFFFFFF' colspan='7'><span class='txtredsbold'>No record(s) to display.</span></td></tr>";
						                   	 			}
													?>
          										</table>
                  							</div></td>
  										</tr>
          								</table>
  							</td>
						</tr>
      					</table>
      					<br>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  					<tr>
	    					<td align="center">
    							<input name="btnApprove" type="submit" class="btn" value="Approve" onClick="return validateApprove();">
	    						<input name="btnDisapprove" type="submit" class="btn" value="Disapprove"  onClick="return validateDisapprove();">
	    						<input name="btnCancel" type="submit" class="btn" value="Cancel">
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
	</form>
	</td>
</tr>
</table>