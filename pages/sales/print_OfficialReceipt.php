<?PHP 
	require "../../initialize.php";	
	include  IN_PATH."scprOfficialReceipt.php";
	
	$html = "
	<table width='100%' border='0' cellpadding='0' cellspacing='0'>
	<tr align='center'>
		<td width='39%' valign='bottom'>&nbsp;</td>
		<td width='61%'>
			<table width='100%' border='0' cellpadding='0' cellspacing='0'>
			<tr>
				<td width='80%' height='14' align='center'></td>
				<td width='20%'>$ornumber</td>
			</tr>
			<tr>
				<td align='center' height='14'></td>
				<td>$txndate</td>
			</tr>
			<tr>
				<td align='center'  height='14'></td>
				<td>$txntime</td>
			</tr>
			<tr>
				<td align='center' height='14'></td>
				<td>$memono</td>
			</tr>
			</table>
		</td>
	</tr>	
	</table>
	<br />
	<br />
	<table width='100%' border='0' cellpadding='0' cellspacing='0'>
  	<tr>
    	<td>
    		<br />
    		$branchname
    		<br />
    		$address
		</td>
    	<td>
    		TIN $tinno
    		<br />
      		P.N. $permitno<br />
      		SN    $serversn</td>
	</tr>
  	<tr>
    	<td>&nbsp;</td>
    	<td>&nbsp;</td>
  	</tr>
  	<tr>
    	<td>IBM/IGS:</td>
    	<td>$customer</td>
  	</tr>
  	<tr>
    	<td>Amount Paid :</td>
    	<td>'Php '.$totalamt.' '.$amtcomnt</td>
  	</tr>
  	<tr>
    	<td>In Words : </td>
    	<td>$totalwords</td>
  	</tr>
  	<tr>
    	<td>&nbsp;</td>
    	<td>&nbsp;</td>
  	</tr>
  	<tr>
    	<td>Remarks : </td>
    	<td>$remarks</td>
  	</tr>
  	<tr>
    	<td>&nbsp;</td>
    	<td>&nbsp;</td>
  	</tr>
  	<tr>
    	<td>Applies to :</td>
    	<td>$appliesto</td>
  	</tr>
  	<tr>
    	<td>&nbsp;</td>
    	<td>Tupperware Brands : _______________________________</td>
  	</tr>
  	<tr>
    	<td>&nbsp;</td>
    	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(CASHIER)</td>
	</tr>
  	<tr>
    	<td>&nbsp;</td>
    	<td>&nbsp;</td>
	</tr>
  	<tr>
    	<td colspan='2'>Note: Cheque payments are subject to bank clearance before finalization.</td>
	</tr>
	</table>";
	if($printctr > 1) 
	{
		$html .= "<table width='95%' align='center'>
		<tr>
   			<td><strong>**Duplicate Copy - $printctr;?> copies printed.</strong></td>
		</tr>
		</table>";
	}
	
	ini_set('display_errors',0);
	require CS_PATH.DS.'html2fpdf.php';
	$pdf = new HTML2FPDF('P','mm','A4');
	$pdf->AddPage();
	$pdf->WriteHTML($html);
	$pdf->SetFont('Arial','B',10);
	$pdf->Output("OfficialReceipt.pdf", "D");		
?>