<?php

	require_once "../../initialize.php";
	
	$html .= "<br><br>";
	//$html .= $_GET['elog'];
	$html .= $_SESSION['msg_log'];

	ini_set('display_errors',0);
	require CS_PATH.DS.'html2fpdf.php';
	
	$pdf = new HTML2FPDF();
	$pdf->SetMargins(10,5,0);
	$pdf->AddPage();
	$pdf->SetDisplayMode(real,'default'); 
	$pdf->WriteHTML($html);

	$pdf->Output("ReportLogDetails.pdf", "D");	
?>