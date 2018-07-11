<?php
	require "../../initialize.php";	
	global $database;
	$islocked = 0;
	$lockedby = null;
	$table = 'provisionalreceipt';
	$txnid = $_POST['chkInclude'];
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	$rs_reason = $sp->spSelectReason($database, 10, '');

	if(isset($_POST['btnCancel']))
	{
		//unlock transaction
		/*try
		{
			$database->beginTransaction();*/
			$updatestatus = $sp->spUpdateLockStatus($database, $table, 0, 0, $txnid);
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			/*$database->commitTransaction();			
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=51.1&msg=$errmsg&Txnid=$txnid");	
		}*/
		
		redirect_to("index.php?pageid=51");
	}
	
	$totalwords="";
	$amtcomnt    = "";
	
	$rsBranch = $sp->spSelectBranchOR($database);
	if($rsBranch->num_rows)
	{
		while ($row = $rsBranch->fetch_object())
		{
			$permitno 	= $row->PermitNo;
			$branchname = $row->Name;
			$tinno 		= $row->TIN;
		 	$address    = $row->StreetAdd;
			$serversn   = $row->ServerSN;
			$branchcode = $row->Code;			
		}
	}
	//retrieve or header
	$rs_header = $sp->spViewPRHeaderByID($database, $txnid);
	
	if ($rs_header->num_rows)
	{
		while ($row = $rs_header->fetch_object())
		{			
			$id = $row->PRID;
			$orno = $row->PRNo;
			$docno = $row->DocumentNo;
			$status = $row->TxnStatus;
			$custcode = $row->CustCode;
			$custname = $row->CustName;
			$tmpTxnDate = strtotime($row->ORDate);
			$txndate = date("m/d/Y", $tmpTxnDate);		
			$remarks = $row->Remarks;			
			$totalamt = $row->TotalAmount;
			$txnstatusId = $row->TxnStatusID;
			$ibmName = $row->IBMName;
			$time =$row->TxnTime;
			$number  	= explode(".", str_replace(",", "", number_format($row->TotalAmount, 2)));
			
			if (convert_number($number[1]) == "zero")
			{
				$number[1] = "Only";
			} 
			else 
			{
				$number[1] = " and ".$number[1]." centavos";
			}
			$totalwords  = strtoupper(convert_number($number[0])." PESO(S) ".$number[1]);
			
		}
		$rs_header->close();
	}
	
	
	//retrieve or details
	$rs_details = $sp->spSelectPRDetailsByID($database, $txnid); 
	$rs_detailsForPDF = $sp->spSelectPRDetailsByID($database, $txnid); 
	
	if ($rs_detailsForPDF->num_rows)
	{
		while($row = $rs_detailsForPDF->fetch_object())
		{			
			$amtcomnt = "CHEQUE (".$row->CheckNumber.")";
		}
	}
	
	$rs_selectBranch = $sp->spSelectBranchTransfer($database);
	if($rs_selectBranch->num_rows)
	{
		while($row = $rs_selectBranch->fetch_object())
		{
			$branchName = $row->BranchName;	
			$branchID = $row->ID;			
		}
	}
	
	$rs_CreatedBy = $sp->spSelectEmployee($database,$session->emp_id,'');
	if($rs_CreatedBy->num_rows)
	{
		while($row = $rs_CreatedBy->fetch_object())
		{
			$userName = $row->Name;	
			$userID = $row->ID;			
		}
	}
	
	
	
		/*if($printctr > 1)
 	{
 		$footer = "<table width='50%' border='0' align='left' cellpadding='0' cellspacing='0'>
				<tr>
   					<td><strong>**Duplicate Copy -        copies printed.</strong></td>
				</tr>
				</table>";
 	}
 	else
 	{
 		$footer = "";
 	}*/
 		
	$totalamt = number_format($totalamt,2);

	$html = '<table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20" width="30%" align="right">&nbsp;</td>
				<td height="20" width="70%" align="right">'.$orno.'   </td>
			</tr>
			<tr>
				<td height="20" align="right">&nbsp;</td>
				<td height="20" align="right">'.$txndate.'</td>
			</tr>
			<tr>
				<td height="20" align="right">&nbsp;</td>
				<td height="20" align="right">'.$time.'</td>
			</tr>
			<tr>
				<td height="20" align="right">&nbsp;</td>
				<td height="20" align="right">'.$docno.'</td>
			</tr>
			</table>
			<br />
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  	<tr>
  				<td colspan="3" align="center" height="20"><strong>Provisional Receipt</strong></td>
  			</tr>	
		   	<tr>
   				<td width="30%" height="20" align="center">&nbsp;</td>
   				<td width="2%" height="20" align="center">&nbsp;</td>
   				<td width="68%" height="20" align="left">&nbsp;</td>
   			</tr>	
		  	<tr>
		    	<td height="20" align="left"><br />'.$branchname.'<br />'.$address.'</td>
		    	<td height="20">&nbsp;</td>
		    	<td height="20" align="left">TIN :  '.$tinno.'<br />P.N. :  '.$permitno.'<br />SN  :  '.$serversn.' </td>
		  </tr>
		  <tr>
		    <td colspan="3" height="20">&nbsp;</td>
		  </tr>
		  <tr>
	    	<td height="20" align="right">IBM/IGS :</td>
	    	<td height="20">&nbsp;</td>
		    <td height="20" align="left">'.$ibmName.'</td>
		  </tr>
		  <tr>
		    <td height="20" align="right">Amount Paid :</td>
		    <td height="20">&nbsp;</td>
		    <td height="20" align="left">'.$totalamt.' '.$amtcomnt.'</td>
		  </tr>
		  <tr>
		    <td height="20" align="right">In Words :</td>
		    <td height="20">&nbsp;</td>
		    <td height="20" align="left">'.$totalwords.'</td>
		  </tr>
		  <tr>
		    <td height="20" align="right">Remarks :</td>
		    <td height="20">&nbsp;</td>
		    <td height="20" align="left">'.$remarks.'</td>
		  </tr>
		  <tr>
		    <td height="20" align="right">Applies to :</td>
		    <td height="20">&nbsp;</td>
		    <td height="20" align="left">&nbsp;</td>
		  </tr>
		  <tr>
		    <td height="20" align="left">&nbsp;</td>
		    <td height="20" colspan="2" align="left">Tupperware Brands : _______________________________</td>
		  </tr>
		  <tr>
		    <td height="20" align="left">&nbsp;</td>
		    <td height="20" colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(CASHIER)</td>
		  </tr>
		  <tr>
		    <td colspan="3" height="20">&nbsp;</td>
		  </tr>
		  <tr>
		    <td height="20" colspan="3" align="left">Note: Cheque payments are subject to bank clearance before finalization.</td>
		  </tr>
		</table>';

	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
	// create new PDF document
	$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	
	$pdf->setPrintHeader(false);

	// set font
	$pdf->SetFont("courier", "", 9);

	// add a page
	$pdf->AddPage();
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	$pdf->Output("SalesProvisionalReceipt.pdf", "I");
	
	/*require CS_PATH.DS."html2fpdf.php";		
	$pdf = new HTML2FPDF();
	$pdf->AddPage();
	$pdf->SetDisplayMode(real,"default"); 
	$pdf->WriteHTML($html);
	$pdf->Output('SalesProvisionalReceipt.pdf', 'D');*/	
?>
