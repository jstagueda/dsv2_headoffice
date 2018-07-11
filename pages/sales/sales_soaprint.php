<style>
    
    .pageset, .pageset table{font-family: arial; font-size: 12px;}
    .pageset table{border-collapse: collapse;}
    .pageset td{padding:3px;}
    @page{margin: 0.5in 0; size:landscape;}
    @media print{
        .pageset{page-break-after: always;}
    }
</style>

<?PHP 
	require_once "../../initialize.php";
	global $database;
        
        function spSelectSOAByCustomerID($database, $v_datefrom, $v_customerid, $branch){
            $query = $database->execute("SELECT DISTINCT
                                        cu.ID CustomerID, cu.Name Customer, SUM(si.`NetAmount`) Debit, 0 Credit,
                                        si.TxnDate, si.DocumentNo, 'I' T, '1' posCount, si.`ID` siid
                                    FROM `salesinvoice` si
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                        INNER JOIN `customer` cu ON cu.ID = si.CustomerID
											AND LOCATE(CONCAT('-', b.ID), cu.HOGeneralID) > 0
                                    WHERE si.StatusID = 7 AND si.TxnDate >= '$v_datefrom' AND si.CustomerID = $v_customerid
									AND b.ID = $branch
                                    GROUP BY si.ID
									
                                    UNION ALL
									
                                    SELECT DISTINCT
                                        cu.ID CustomerID, cu.Name Customer, 0 Debit,
                                        SUM(orDetails.`OutstandingBalance`) Credit,
                                        ofr.TxnDate, ofr.DocumentNo, 'OR ' T, '2' posCount, si.`ID` siid
                                    FROM `officialreceipt` ofr
									INNER JOIN branch b ON b.ID = SPLIT_STR(ofr.HOGeneralID, '-', 2)
                                    INNER JOIN `officialreceiptdetails` orDetails ON orDetails.`OfficialReceiptID` = ofr.`ID`
										AND LOCATE(CONCAT('-', b.ID), orDetails.HOGeneralID) > 0
                                        INNER JOIN `salesinvoice` si ON si.`ID` = orDetails.`RefTxnID`
											AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
                                        INNER JOIN `customer` cu ON cu.ID = si.CustomerID
											AND LOCATE(CONCAT('-', b.ID), cu.HOGeneralID) > 0
                                    WHERE ofr.StatusID = 7 AND DATE_FORMAT(ofr.`TxnDate`, '%Y-%m-%d') <= '$v_datefrom' AND si.`CustomerID` = $v_customerid
									AND b.ID = $branch
                                    GROUP BY si.ID, ofr.ID
									
                                    UNION ALL
									
                                    SELECT DISTINCT
                                        cu.ID CustomerID, cu.Name Customer, 
                                        CASE WHEN dm.MemoTypeID = 1 THEN
                                        SUM(dm.`TotalAppliedAmount`)
                                        ELSE 0 END Debit,
                                        CASE WHEN dm.MemoTypeID = 2 THEN
                                        SUM(dm.`TotalAppliedAmount`)
                                        ELSE
                                        0  END Credit, dm.TxnDate, dm.DocumentNo, 'M' T,
                                        '2' posCount, si.`ID` siid
                                    FROM `dmcm` dm
										INNER JOIN branch b ON b.ID = SPLIT_STR(dm.HOGeneralID, '-', 2)
                                        INNER JOIN `customer` cu ON cu.ID = dm.CustomerID
											AND LOCATE(CONCAT('-', b.ID), cu.HOGeneralID) > 0
                                        INNER JOIN `dmcmdetails` dmDetails ON dmDetails.`DMCMID` = dm.`ID`
											AND LOCATE(CONCAT('-', b.ID), dmDetails.HOGeneralID) > 0
                                        INNER JOIN `salesinvoice` si ON si.`ID` = dmDetails.`RefTxnID`
											AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
                                    WHERE dm.StatusID = 7 AND DATE_FORMAT(dm.TxnDate, '%Y-%m-%d') <= '$v_datefrom' AND dm.CustomerID = $v_customerid
									AND b.ID = $branch
                                    GROUP BY dm.ID
                                    ORDER BY siid ASC,posCount ASC, TxnDate ASC, DocumentNo DESC");
            return $query;
        }
        
        
        function spSelectAgingReportByCustomerID($database, $fromDate, $v_custid, $branch){
			
            $query = $database->execute("SELECT
                                            CustomerID, Customer, SUM(CurrentAmount) CurrentAmount,
                                            SUM(1_30_Amount) Amount1, SUM(31_60_Amount) Amount2,
                                            SUM(60_Over_Amount) Amount3
                                        FROM
                                        (SELECT DISTINCT
                                            a.CustomerID, a.Customer, SUM(a.Amount) CurrentAmount,
                                            0 1_30_Amount, 0 31_60_Amount, 0 60_Over_Amount
                                        FROM
                                        (SELECT DISTINCT
                                            cu.ID CustomerID, cu.`Name` Customer, si.`EnrollmentDate`,
                                            si.`DaysDue`, (si.OutstandingAmount) Amount,
                                            DATEDIFF(NOW(), DATE_ADD(sii.TxnDate, INTERVAL (ct.Duration + 1) DAY)) Countdays
                                        FROM `customeraccountsreceivable` si
											INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                            INNER JOIN `customer` cu ON cu.ID = si.CustomerID
												AND LOCATE(CONCAT('-', b.ID), cu.HOGeneralID) > 0
                                            INNER JOIN `salesinvoice` sii ON sii.`ID`=si.`SalesInvoiceID`
												AND LOCATE(CONCAT('-', b.ID), sii.HOGeneralID) > 0
                                            INNER JOIN `creditterm` ct ON ct.`ID`=sii.`CreditTermID`
											WHERE b.ID = $branch) a
                                        WHERE a.Countdays <= 0
                                        GROUP BY a.CustomerID
										
                                        UNION ALL
										
                                        SELECT DISTINCT
                                            a.CustomerID, a.Customer, 0 CurrentAmount, SUM(a.Amount) 1_30_Amount,
                                            0 31_60_Amount, 0 60_Over_Amount
                                        FROM (SELECT DISTINCT
                                            cu.ID CustomerID, cu.`Name` Customer, si.`EnrollmentDate`, si.`DaysDue`,
                                            (si.OutstandingAmount) Amount, DATEDIFF(NOW(), DATE_ADD(sii.TxnDate, INTERVAL (ct.Duration + 1) DAY)) Countdays
                                        FROM `customeraccountsreceivable` si
											INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                            INNER JOIN `customer` cu ON cu.ID = si.CustomerID
												AND LOCATE(CONCAT('-', b.ID), cu.HOGeneralID) > 0
                                            INNER JOIN `salesinvoice` sii ON sii.`ID`=si.`SalesInvoiceID`
												AND LOCATE(CONCAT('-', b.ID), sii.HOGeneralID) > 0
                                            INNER JOIN `creditterm` ct ON ct.`ID`=sii.`CreditTermID`
											WHERE b.ID = $branch) a
                                        WHERE a.Countdays BETWEEN 1 AND 30
                                        GROUP BY a.CustomerID
										
                                        UNION ALL
										
                                        SELECT DISTINCT
                                            a.CustomerID, a.Customer, 0 CurrentAmount,
                                            0 1_30_Amount, SUM(a.Amount) 31_60_Amount, 0 60_Over_Amount
                                        FROM
                                        (SELECT DISTINCT
                                            cu.ID CustomerID, cu.`Name` Customer, si.`EnrollmentDate`,
                                            si.`DaysDue`, (si.OutstandingAmount) Amount, DATEDIFF(NOW(), DATE_ADD(sii.TxnDate, INTERVAL (ct.Duration + 1) DAY)) Countdays
                                        FROM `customeraccountsreceivable` si
											INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                            INNER JOIN `customer` cu ON cu.ID = si.CustomerID
												AND LOCATE(CONCAT('-', b.ID), cu.HOGeneralID) > 0
                                            INNER JOIN `salesinvoice` sii ON sii.`ID`=si.`SalesInvoiceID`
												AND LOCATE(CONCAT('-', b.ID), sii.HOGeneralID) > 0
                                            INNER JOIN `creditterm` ct ON ct.`ID`=sii.`CreditTermID`
											WHERE b.ID = $branch) a
                                        WHERE a.Countdays BETWEEN 31 AND 60
                                        GROUP BY a.CustomerID
										
                                        UNION ALL
										
                                        SELECT DISTINCT
                                            a.CustomerID, a.Customer, 0 CurrentAmount,
                                            0 1_30_Amount, 0 31_60_Amount, SUM(a.Amount) 60_Over_Amount
                                        FROM
                                        (SELECT DISTINCT
                                            cu.ID CustomerID, cu.`Name` Customer, si.`EnrollmentDate`,
                                            si.`DaysDue`, (si.OutstandingAmount) Amount, DATEDIFF(NOW(), DATE_ADD(sii.TxnDate, INTERVAL (ct.Duration + 1) DAY)) Countdays
                                        FROM `customeraccountsreceivable` si
											INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                            INNER JOIN `customer` cu ON cu.ID = si.CustomerID
												AND LOCATE(CONCAT('-', b.ID), cu.HOGeneralID) > 0
                                            INNER JOIN `salesinvoice` sii ON sii.`ID`=si.`SalesInvoiceID`
												AND LOCATE(CONCAT('-', b.ID), sii.HOGeneralID) > 0
                                            INNER JOIN `creditterm` ct ON ct.`ID`=sii.`CreditTermID`
											WHERE b.ID = $branch) a
                                        WHERE a.Countdays >= 61
                                        GROUP BY a.CustomerID
                                        ) X
                                        WHERE CustomerID = $v_custid
                                        GROUP BY CustomerID;");
            
            return $query;
        }
        
        function spSelectCustomerPenaltyByCustomerID($database, $v_datefrom, $v_customerid, $branch){
            $query = $database->execute("SELECT DISTINCT
                                        cu.ID CustomerID,
                                        cu.Name Customer,
                                        SUM(cp.`OutstandingAmount`) Debit,
                                        0 Credit,
                                        cp.`LastModifiedDate` TxnDate,
                                        si.`DocumentNo`,
                                        'P' T,
                                        '2' posCount,
                                        si.`ID` siid
                                    FROM `customerpenalty` cp
										INNER JOIN branch b ON b.ID = SPLIT_STR(cp.HOGeneralID, '-', 2)
                                        INNER JOIN `customer` cu ON cu.ID = cp.CustomerID
											AND LOCATE(CONCAT('-', b.ID), cu.HOGeneralID) > 0
                                        INNER JOIN salesinvoice si ON si.`ID` = cp.`SalesInvoiceID`
											AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
                                    WHERE si.StatusID = 7  AND cp.`Amount` > 0 
                                    AND DATE_FORMAT(si.TxnDate, '%Y-%m-%d') <= '$v_datefrom' 
                                    AND cp.CustomerID = $v_customerid
									AND b.ID = $branch
                                    GROUP BY si.DocumentNo
                                    ORDER BY siid ASC,posCount ASC, TxnDate ASC, DocumentNo DESC");
            return $query;
        }
        
        
	$f = $_GET['f'];
	$c = $_GET['e'];
	
	$tmpftxndate = strtotime($_GET['f']);
	$fromDate = date("Y-m-d", $tmpftxndate);	
	$from_date = date("m/d/Y", $tmpftxndate);
	
	$branch_param = $sp->spSelectBranch($database, -2, "");
	if ($branch_param->num_rows)
	{
		while($row = $branch_param->fetch_object())
		{
			$branch = $row->Name;			
		}		
	}
	
	$cust_det = $sp->spSelectCustomer($database, $c, '');
	if ($cust_det->num_rows)
	{
		while($row = $cust_det->fetch_object())
		{
			$custcode = $row->Code;
			$custname = $row->Name;			
		}		
	}
	
	$tmpbal = 0;
	$txnRegister = "";
	$txnCPRegister = "";
	$agingreport = "";
	$branch = $_GET['branch'];
	$rs_soa_report = spSelectSOAByCustomerID($database, $fromDate, $c, $branch);
	$rs_cuspen_report = spSelectCustomerPenaltyByCustomerID($database, $fromDate, $c, $branch);
	$rs_aging_report = spSelectAgingReportByCustomerID($database, $fromDate, $c, $branch);

	if ($rs_soa_report->num_rows)
	{
		$bal = 0;
		while($row = $rs_soa_report->fetch_object())
		{
			$txndate = date('m/d/Y', strtotime($row->TxnDate));
			$tmpbal += $row->Debit;
			$tmpbal -= $row->Credit;
			$debit = number_format($row->Debit, 2, ".", "");
			$credit = number_format($row->Credit, 2, ".", "");
			$bal = number_format($tmpbal, 2, ".", "");
			$txnRegister .= '<tr>
				        		<td height="20" align="left">&nbsp;'.$txndate.'</td>
					        	<td height="20" align="left">&nbsp;'.$row->DocumentNo.'</td>
					          	<td height="20" align="center">'.$row->T.'</td>
					          	<td height="20" align="right">'.$debit.'&nbsp;&nbsp;</td>
					          	<td height="20" align="right">'.$credit.'&nbsp;&nbsp;</td>
					          	<td height="20" align="right">'.$bal.'&nbsp;&nbsp;</td>
					      	</tr>';
		}
		$rs_soa_report->close();
		
		$txnRegister .= '<tr><td colspan="6" height="20">&nbsp;</td></tr>';
		$txnRegister .= '<tr>
			        		<td height="20" align="left">&nbsp;</td>
				        	<td height="20" align="left">&nbsp;</td>
				          	<td height="20" align="center">&nbsp;</td>
				          	<td height="20" align="right">&nbsp;</td>
				          	<td height="20" align="right"><strong>Total Due :</strong>&nbsp;&nbsp;</td>
				          	<td height="20" align="right"><strong>'.$bal.'</strong>&nbsp;&nbsp;</td>
				      	</tr>';
	}
	else
	{
		$txnRegister .= '<tr><td height="20" colspan="6" align="center"><strong>No record(s) to display.</strong></td></tr>';
	}
	
	if ($rs_cuspen_report->num_rows)
	{
		$bal = 0;
		$tmpbal = 0;
		while($row = $rs_cuspen_report->fetch_object())
		{
			$txndate = date('m/d/Y', strtotime($row->TxnDate));
			$tmpbal += $row->Debit;
			$tmpbal -= $row->Credit;
			$debit = number_format($row->Debit, 2, ".", "");
			$credit = number_format($row->Credit, 2, ".", "");
			$bal = number_format($tmpbal, 2, ".", "");
			$txnCPRegister .= '<tr>
					        		<td height="20" align="left">&nbsp;'.$txndate.'</td>
						        	<td height="20" align="left">&nbsp;'.$row->DocumentNo.'</td>
						          	<td height="20" align="center">'.$row->T.'</td>
						          	<td height="20" align="right">'.$debit.'&nbsp;&nbsp;</td>
						          	<td height="20" align="right">'.$credit.'&nbsp;&nbsp;</td>
						          	<td height="20" align="right">'.$bal.'&nbsp;&nbsp;</td>
						      	</tr>';
		}
		$rs_cuspen_report->close();
		
		$txnCPRegister .= '<tr><td colspan="6" height="20">&nbsp;</td></tr>';
		$txnCPRegister .= '<tr>
		        				<td height="20" align="left">&nbsp;</td>
					        	<td height="20" align="left">&nbsp;</td>
					          	<td height="20" align="center"></td>
					          	<td height="20" align="right">&nbsp;</td>
					          	<td height="20" align="right"><strong>Total Due :   </strong>&nbsp;</td>
					          	<td height="20" align="right"><strong>'.$bal.'</strong>&nbsp;&nbsp;</td>
				      		</tr>';
	}
	else
	{
		$txnCPRegister .= '<tr><td height="20" colspan="6" align="center"><strong>No record(s) to display.</strong></td></tr>';
	}
	
	if ($rs_aging_report->num_rows)
	{
		$agingreport .= '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
						<tr>
		        			<td height="20" align="right" width="25%">Current&nbsp;&nbsp;</td>
				          	<td height="20" align="right" width="25%">30 Days&nbsp;&nbsp;</td>
				          	<td height="20" align="right" width="25%">60 Days&nbsp;&nbsp;</td>
				          	<td height="20" align="right" width="25%">Past 60 Days&nbsp;&nbsp;</td>
				      	</tr>';
				      	
		while($row = $rs_aging_report->fetch_object())
		{
			$current = number_format($row->CurrentAmount, 2, ".", "");
			$amt_1_30 = number_format($row->Amount1, 2, ".", "");
			$amt_31_60 = number_format($row->Amount2, 2, ".", "");
			$amt_Over_60 = number_format($row->Amount3, 2, ".", "");
			
			$agingreport .= '<tr>
					          	<td height="20" align="right">'.$current.'&nbsp;&nbsp;</td>
					          	<td height="20" align="right">'.$amt_1_30.'&nbsp;&nbsp;</td>
					          	<td height="20" align="right">'.$amt_31_60.'&nbsp;&nbsp;</td>
					          	<td height="20" align="right">'.$amt_Over_60.'&nbsp;&nbsp;</td>
					      	</tr>';
		}
		$agingreport .= '</table>';
	}
	
	$html = '<div class="pageset"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
  		<td height="20" align="center"><strong>STATEMENT OF ACCOUNT</strong></td>
    </tr>
  	</table>
  	<br>
  	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">							
	<tr>
		<td height="20" width="20%" align="left">&nbsp;<strong>TUPPERWARE BRANDS</strong></td>
		<td height="20" width="80%" align="left"></td>
	</tr>
	<tr>
		<td height="20" colspan="2">2288 Pasong Tamo Ext., Makati City, Metro Manila</td>
	</tr>
	</table>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="20" width="11%" height="20" align="right"><strong>Dealer :   </strong></td>
		<td height="20" width="89%" height="20" align="left">&nbsp;<strong>'.$custcode.'</strong>&nbsp;&nbsp;&nbsp;'.$custname.'</td>
	</tr>
	<tr>
		<td height="20" align="right"><strong>Branch :   </strong></td>
		<td height="20" align="left">&nbsp;'.$branch.'</td>
	</tr>
	<tr>
		<td height="20" align="right"><strong>Date From :   </strong></td>
		<td height="20" align="left">&nbsp;'.$from_date.'</td>
	</tr>
	</table>
	<br>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="20" align="left">&nbsp;<strong>Summary of Transactions</strong></td>
	</tr>
  	</table>
	<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
	<tr>
		<td height="20" align="center" width="15%">Date</td>
    	<td height="20" align="center" width="20%">Document No.</td>
      	<td height="20" align="center" width="10%">Type</td>
      	<td height="20" align="center" width="20%">Debit</td>
      	<td height="20" align="center" width="20%">Credit</td>
      	<td height="20" align="center" width="15%">Balance</td>
  	</tr>
  	<tr>
		<td height="20" colspan="5" align="right">Beg. Bal&nbsp;&nbsp;</td>
      	<td height="20" align="right">0.00&nbsp;&nbsp;</td>
  	</tr>'.$txnRegister.'</table><br>
	<table width="100%" border="0" width="100%" align="center">
	<tr>
		<td height="20" align="left"><strong>Summary of Penalties</strong></td>
	</tr>
  	</table>
	<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
	<tr>
    	<td height="20" align="center" width="15%">Date</td>
    	<td height="20" align="center" width="20%">Document No.</td>
      	<td height="20" align="center" width="10%">Type</td>
      	<td height="20" align="center" width="20%">Debit</td>
      	<td height="20" align="center" width="20%">Credit</td>
      	<td height="20" align="center" width="15%">Balance</td>
  	</tr>
  	<tr>
    	<td height="20" colspan="5" align="right">Beg. Bal&nbsp;&nbsp;</td>
      	<td height="20" align="right">0.00&nbsp;&nbsp;</td>
  	</tr>'.$txnCPRegister.'</table><br>'.$agingreport.
	'<br><br>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="10%" height="20" align="left">&nbsp;NOTICE:</td>
		<td width="95%" height="20" align="left">&nbsp;All overdue accounts are subject to penalty, plus interest</td>
	</tr>
	<tr>
		<td height="20" align="left">&nbsp;</td>
		<td height="20" align="left">&nbsp;and other collection charges if account is referred to a</td>
	</tr>
	<tr>
		<td height="20" align="left">&nbsp;</td>
		<td height="20" align="left">&nbsp;third party for collection purposes</td>
	</tr>
	</table></div>';
	
        
        echo $html;
        
	/*require_once("../../tcpdf/config/lang/eng.php");
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
	$pdf->Output("SOA.pdf", "I");*/
?>