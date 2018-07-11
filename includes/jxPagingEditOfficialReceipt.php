<script language="javascript" src="js/jxPagingEditOfficialReceiptDetails.js"  type="text/javascript"></script>
<?php
	require_once("../initialize.php");
	global $database;
	#=This is the number of rows to display per page (results per page)
	$RPP = '10';

	if (isset($_GET['svalue']))
	{
		$vSearch  = $_GET['svalue'];
	}
	else 
	{
		$vSearch  = "";
	}
	
	#=Images for the next and back button
	$PrevIc		=	"images/bprv.gif";
	$FirstIc 	=	"images/bfrst.gif";
	$NextIc 	=	"images/bnxt.gif";
	$LastIc 	=	"images/blst.gif";
	
	$dPrevIc	=	"images/dprv.gif";
	$dFirstIc	=	"images/dfrst.gif";
	$dNextIc	=	"images/dnxt.gif";
	$dLastIc	=	"images/dlst.gif";
	
	/*
	This page will go out to a database, and grab a selection of rows.
	Because we dont want to display hundreds of rows at a time we will
	create dynamic paging.
	*/
	
	//############################################
	//First get the page value passed to this page
	$pageNum='1';
	
	if (isset($_GET["p"])) 
	{
		$pageNum=$_GET["p"];
	}
	
	$type='';
	
	if (isset($_GET["t"])) 
	{
		$type=$_GET["t"];
	}
	
	$v_custid = $_GET["custId"];
	//############################################
	//Now import the settings
	//include_once("settings.php");
	
	//############################################
	//Connect to the db
	//mysql_connect($db_host,$MySqlUN,$MySqlPW);
	//@mysql_select_db($database) or die( "Unable to select databases");
	
	//############################################
	//Get a quick count of all the rows
	$query = $sp->spSelectCustomerIGSSI($database,$v_custid);
	$row = $query->fetch_object();
	$num =$query->num_rows;
	//############################################
	//If there are some rows then start the pagination
	if ($num>0) 
	{
		//Determine the maxpage and the offset for the query
		$maxPage = ceil($num/$RPP);
		$offset = ($pageNum - 1) * $RPP;
	
		//Initiate the navigation bar
		$nav  = '';
	
		//get low end
		$page = $pageNum-3;
	
		//get upperbound
		$upper =$pageNum+3;
	
		if ($page <=0) 
		{
			$page=1;
		}
	
		if ($upper >$maxPage) 
		{
			$upper =$maxPage;
		}
	
		//Make sure there are 7 numbers (3 before, 3 after and current
		if ($upper-$page <6)
		{	
			//We know that one of the page has maxed out
			//check which one it is
			//echo "$upper >=$maxPage<br>";
			if ($upper >=$maxPage)
			{
				//the upper end has maxed, put more on the front end
				//echo "to begining<br>";
				$dif = $maxPage-$page;
				//echo "$dif<br>";
					if ($dif==3)
					{
						$page=$page-3;
					}
					elseif ($dif==4)
					{
						$page=$page-2;
					}
					elseif ($dif==5)
					{
						$page=$page-1;
					}
			}
			elseif ($page <=1) 
			{
				//its the low end, add to upper end
				//echo "to upper<br>";
				$dif =$upper-1;
	
				if ($dif==3)
				{
					$upper=$upper+3;
				}
				elseif ($dif==4)
				{
					$upper=$upper+2;
				}
				elseif ($dif==5)
				{
					$upper=$upper+1;
				}
			}
		}
	
		if ($page <=0) 
		{
			$page=1;
		}
	
		if ($upper >$maxPage) 
		{
			$upper =$maxPage;
		}
	
		//These are the numbered links
		for($page; $page <=  $upper; $page++) 
		{
			if ($page == $pageNum)
			{
				//If this is the current page then disable the link
				$nav .= " <font color='red'>$page</font> ";
			}
			else
			{
				//If this is a different page then link to it  , \"".$v_custid."\"
				$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$vSearch."\" , \"".$v_custid."\")'>$page</a> ";
			}
		}
	
		//These are the button links for first/previous enabled/disabled
		if ($pageNum > 1)
		{
			$page  = $pageNum - 1;
			$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$vSearch."\" , \"".$v_custid."\")' style:'cursor:pointer'> ";
			$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$vSearch."\" , \"".$v_custid."\")'  style:'cursor:pointer'> ";
		}
		else
		{
			$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
			$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
		}
	
		//These are the button links for next/last enabled/disabled
		if ($pageNum < $maxPage AND $upper <= $maxPage) 
		{
			$page = $pageNum + 1;
			$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$vSearch."\" , \"".$v_custid."\")'  >";
			$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$vSearch."\" , \"".$v_custid."\")' >";
		} 
		else 
		{
			$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
			$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
		}
	
		if ($maxPage>=1 AND $type=='nav') 
		{
			// print the navigation link
			echo $first . $prev . $nav . $next . $last;
		}
		elseif ($maxPage>=1 AND $type=='con') 
		{
			//Build the header
			echo "
			<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='tab'>
            <tr align='center' class='tab'>
              	<td height='20' width='5%' class='txt10'><div align='center' class='bdiv_r'><input name='chkAlls' type='checkbox' id='chkAlls' onClick='checkAllPaging(this.checked); CheckIncludes();'  ></div></td>
				<td height='20' width='15%' class='txt10'><div align='center' class='bdiv_r'><strong>Sales Invoice/Penalties No.</strong></div></td>
		    	<td height='20' width='25%' class='txt10'><div align='left' class='padl5 bdiv_r'><strong>IGS Code - Name</strong></div></td>
				<td height='20' width='10%' class='txt10'><div align='center' class='bdiv_r'><strong>Transaction Date</strong></div></td>
				<td height='20' width='15%' class='txt10'><div align='right' class='padr5 bdiv_r'><strong>Transaction Amount</strong></div></td>
				<td height='20' width='15%' class='txt10'><div align='right' class='padr5 bdiv_r'><strong>Outstanding Balance</strong></div></td>
				<td height='20' width='15%' class='txt10'><div align='right' class='padr5'><strong>Amount Applied</strong></div></td>
			</tr>";
			
			//Get all the rows
			$query = $sp->spSelectCustomerIGSSIPaging ($database, $offset, $RPP, $vSearch,$v_custid);
			$num = $query->num_rows;
	
			//Echo each row
			$cnts = 0;
			$sitotalamt=0;
			if ($query->num_rows)
			{				
				while ($row = $query->fetch_object())
				{
					($cnts % 2) ? $class = "" : $class = "bgEFF0EB";						
					$siNo = $row->SIPNos ;
					$tmpTxnDate = strtotime($row->TransactionDate);
					$sidate = date("m/d/Y", $tmpTxnDate);							
					$totalAmount = number_format($row->TransactionAmount,2) ;
					//$totalOutAmount = $row->OutStandingAmount ;
					$outstandingBalance=number_format($row->OutStandingBalance,2);
					$outBalance=number_format($row->OutStandingBalance,2, '.', '');
					$siid = $row->SIPNo;
					$igsName=$row->IGSName;
					$totalOutAmount=0;
				    $refType = $row->RefType;
				    $isWithinCreditTerm = $row->IsWithinCreditTerm;
					//$amountApplied = $row->AmountApplied ;
					$amountApplied = 0;//$row->AmountApplied ;
					$sitotalamt+=$outstandingBalance;
			
					echo "<tr class='$class'>
							  <input name='hdncpsiNos[]' type='hidden' value ='$siid'/>                                                       
		                      <input name='hdnreftypes[]' type='hidden' value='$refType' />	
		                      <input name='hdnOutstandingBals[]' type='hidden' value='$outstandingBalance' />
		                      <input name='hdnids[]' type='hidden' value ='$siNo'/> 
		                      <input name='hdnigsCodes[]' type='hidden' value ='$igsName'/> 
		                      <input name='hdnTxnDates[]' type='hidden' value ='$sidate'/> 
		                      <input name='hdnTxnAmounts[]' type='hidden' value ='$totalAmount'/>
		                      <input name='hdnIsWithinCreditTerm[]' type='hidden' value ='$isWithinCreditTerm'/>
		                      <input name='txtAmountApplieds_1$cnts' type='hidden' value='0.00'> 
		                                          	
							  <td height='20' class='txt10 borderBR' align='center'>
								<input name='chkIDs$cnts' type='checkbox' onClick='CheckIncludes();' value='$cnts'>
							   	<input type='hidden'  style='text-align:right' name='txtchks$cnts' class='txtfield' value ='0' >
							  </td>
							  <td height='20' class='txt10 borderBR'><div align='center'>$siNo<input type='hidden' name='txtsiNos$cnts' value='$siid'><input type='hidden' name='txtOrType$cnts' value='$refType'></div></td>
							  <td height='20' class='txt10 borderBR'><div align='left' class='padl5'>$igsName</div></td>
							  <td height='20' class='txt10 borderBR'><div align='center'> $sidate</div></td>
							  <td height='20' class='txt10 borderBR'><div align='right' class='padr5'>$totalAmount</div></td>
							  <td height='20' class='txt10 borderBR'><div align='right' class='padr5'>$outstandingBalance<input type='hidden' name='txtOutstandingBalances$cnts' value='$outBalance'></div></td>
							  <td height='20' class='txt10 borderBR'><div align='right' class='padr5'><input type='text'  style='text-align:right' name='txtAmountApplieds$cnts' class='txtfield' disabled='true' value=''></div></td>			
						 </tr>
					";				
					$cnts++;			
				}
			}
	
			//Close table
			echo "<input id='countRows1' name='countRows1' type='hidden'  value='$cnts'></table>";
		}
		elseif ($maxPage>=1 AND $type=='num') 
		{
			$offsets = $offset + 1;
			$offset2s = $offsets + 9;
			
			echo "Displaying ". $offsets." to  ";
			if ( $offset2s <= $num)
			{
				echo $offset2s;	
			}
			else
			{
				echo $num;
			}
			echo " (of ".$num." records)" ;			
		}
	}
	else if (($num <= 0) && ($type == 'con'))
	{
		echo "<table width='100%'  border='0' cellpadding='0' cellspacing='1'>
			    <tr align='center' class='tab'>
	              	<td height='20' width='5%' class='txt10'><div align='center' class='bdiv_r'><input name='chkAlls' type='checkbox' id='chkAlls' onClick='checkAllPaging(this.checked); CheckIncludes();'  ></div></td>
					<td height='20' width='15%' class='txt10'><div align='center' class='bdiv_r'><strong>Sales Invoice/Penalties No.</strong></div></td>
			    	<td height='20' width='25%' class='txt10'><div align='left' class='padl5 bdiv_r'><strong>IGS Code - Name</strong></div></td>
					<td height='20' width='10%' class='txt10'><div align='center' class='bdiv_r'><strong>Transaction Date</strong></div></td>
					<td height='20' width='15%' class='txt10'><div align='right' class='padr5 bdiv_r'><strong>Transaction Amount</strong></div></td>
					<td height='20' width='15%' class='txt10'><div align='right' class='padr5 bdiv_r'><strong>Outstanding Balance</strong></div></td>
					<td height='20' width='15%' class='txt10'><div align='right' class='padr5'><strong>Amount Applied</strong></div></td>
				</tr>
		    	<tr><td colspan='7' height='20' class='borderBR'><div align='center'><span class='txtredsbold'>No record(s) to display.</span></div></tr>
		    </table>";
	}
?>
