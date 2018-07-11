<?php
include "../../../initialize.php";
global $mysqli;


if($_POST['request'] == 'Generate List'){
	
$writeoff_from 	= $_POST['writeoff_from'];
$writeoff_to 	= $_POST['writeoff_to'];
$days_overdue_from 	= $_POST['days_overdue_from'];
$days_overdue_to 	= $_POST['days_overdue_to'];
$as_of_date		= $_POST['as_of_date'];
	

	
		$q = "SELECT a.ID, d.ID salesID,CONCAT(e.Code,'',a.Code) InvoiceDMNo, a.Code, a.Name, b.DaysDue, DATE_FORMAT(d.TxnDate, '%m/%d/%Y') xDate, DATE_FORMAT(d.EffectivityDate,'%m/%d/%Y') DueDate, 
				b.OutstandingAmount AmountOpen, d.Netamount AmountDue, d.Netamount - b.OutstandingAmount AppliedAmount, g.Code StatusCode,
				f.tpi_GSUTypeID GSUTYPE										
				FROM customer a
				INNER JOIN customeraccountsreceivable b ON a.ID = b.CustomerID
				INNER JOIN tpi_rcustomerpda c ON a.ID = c.CustomerID
				INNER JOIN salesinvoice d ON b.SalesInvoiceID = d.ID
				INNER JOIN branch e ON d.BranchID = e.ID
				INNER JOIN tpi_customerdetails f ON a.ID = f.CustomerID
				INNER JOIN status g on g.ID = a.StatusID
				WHERE DATE_FORMAT(d.TxnDate, '%m/%d/%Y') >= '".$as_of_date."' AND
				(c.tpi_PDA_ID = 2 OR c.tpi_PDA_ID = 3)
				AND a.StatusID = 4
				AND (b.OutstandingAmount BETWEEN ".$writeoff_from." AND ".$writeoff_to.")
				AND (b.DaysDue BETWEEN ".$days_overdue_from." AND ".$days_overdue_to.")";
	
	$qq = $mysqli->query($q);
	
	if($qq->num_rows){
		$num_rows = $qq->num_rows;
		while($r = $qq->fetch_object()){

			$results['fetch_data'][] = array( 
									'xID'=>trim($r->ID), 
									'InvoiceDMNo'=>trim($r->InvoiceDMNo), 
									'Code'=>trim($r->Code), 
									'DealerName'=>trim($r->Name),
									'DaysDue'=>trim($r->DaysDue),
									'Date'=>trim($r->xDate),
									'DueDate'=>trim($r->DueDate),
									'AmountOpen'=>trim(number_format($r->AmountOpen,2)),
									'AmountDue'=>trim($r->AmountDue),
									'AppliedAmount'=>trim($r->AppliedAmount),
									'salesID'=>trim($r->salesID),
									'GSUTYPE'=>trim($r->GSUTYPE),
									'StatusCode'=>trim($r->StatusCode)
									);
			
			
			
			
		}
		$results['results'] = array('fetching_data'=>'Success');
		die(json_encode($results));
	}else{
		$results['results'] = array('fetching_data'=>'failed');
		die(json_encode($results));
	}
	
}
?>