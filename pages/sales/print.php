<?PHP 
	//include "../../initialize.php";
	require_once "../../initialize.php";	
	include  IN_PATH.DS."scPrint.php";
	
$siDetails="";
$totalqty = 0;
$grandtotal=0;
	
	 if($rsProdSI->num_rows)
	 {
	 	while ($row = $rsProdSI->fetch_object()) 
	 	{
			  	$siDetails .= "<tr >
						  		<td width='10%' align='left' valign='middle'>$row->ProductCode</td>
						  		<td align='left' valign='middle' >$row->Product</td>
						  		<td width='5%' align='left' valign='middle' >$row->Qty</td>
						  		<td width='10%' align='right' valign='middle' >$row->UnitPrice</td>
						  		<td width='10%' align='right' valign='middle' >$row->TotalAmount</td>
						  	  </tr>";
						 $totalqty += $row->Qty;
						$grandtotal += $row->TotalAmount;
						 
		}
		
	}
	else
	{
		$siDetails = "<tr align='center'><td height='20' >No record(s) to display. </td></tr>";
	}
	 	 
	 
$htmls = "
	<html>
	<head>
	<title></title>
	</head>
	<link rel='stylesheet' type='text/css' href='../../css/ems.css'>
	<body>
	<br>
<table border='0' width='100%'>
	<tr align='center'>
		<td width='39%' height='90' valign='bottom'>&nbsp;
<table width='80%' border='0' align='right' cellpadding='0' cellspacing='0'>				
				<tr>
					<td align='left' valign='middle' >$branchname</td>
				</tr>
				<tr>
					<td align='left' valign='middle'>VAT REG:$tinno</td>
				</tr>
				<tr>
				  <td align='left' valign='middle' >Permit No.  $permitno; </td>
			  </tr>
				<tr>
					<td align='left' valign='middle'>$address</td>
			  </tr>
			</table>
        </td>
		<td width='61%'>
			<table width='100%' border='0' cellpadding='0' cellspacing='0'>
				<tr>
				  <td height='24' align='center'>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr>
					<td width='60%' align='center'>&nbsp;</td>
					<td > $salesinvoice</td>
				</tr>
				<tr>
					<td align='center'>&nbsp;</td>
					<td  >$txndate</td>
				</tr>
				<tr>
					<td align='center'>&nbsp;</td>
					<td  > $reftxnid</td>
				</tr>
				<tr>
					<td align='center'>&nbsp;</td>
					<td  > $serverno</td>
				</tr>
				<tr>
					<td align='center'>&nbsp;</td>
					<td >$pageno</td>
				</tr>
			</table>
		</td>
	</tr>	
</table>
<br />
<table border='0' width='100%'>
	<tr align='center'>
	  <td width='40%' align='center' style='padding-left:265px;'><table width='100%' border='0' align='left' cellpadding='0' cellspacing='2'>
	    <tr>
	      <td height='6'  colspan='2' align='left' nowrap='nowrap'></td>
        </tr>
	    <tr>
	      <td width='68%' align='left' nowrap='nowrap' >$customercode&nbsp;</td>
	      <td align='left' nowrap='nowrap' >&nbsp;</td>
        </tr>
	    <tr>
	      <td align='left' nowrap='nowrap' >$customername&nbsp;</td>
	      <td align='left' nowrap='nowrap' >&nbsp;</td>
        </tr>
	    <tr>
	      <td align='left' >$customeraddress&nbsp;</td>
	      <td align='left' nowrap='nowrap' >&nbsp;</td>
        </tr>
	    <tr>
	      <td align='left' nowrap='nowrap' >$customertin</td>
	      <td align='left' >$customeribmcode</td>
        </tr>
      </table></td>
  </tr>	
</table>
<br />
<br />
<br />
<br />
<br />
<br />
<table width='100%' border='1'>  	
	 <tr >
	   <td valign='bottom' style='padding-left:80px;'>&nbsp;</td>
  	 </tr>
	 <tr >
	  <td valign='bottom' style='padding-left:80px;'>
	   <table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>
	   $siDetails 	</table>
      <table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>
		
      <tr >
		<td width='10%' align='left' valign='middle'>&nbsp;</td>
		<td align='left' valign='middle'>Total with CPI :</td>
		<td width='5%' align='left' valign='middle'>$totalqty</td>
		<td width='10%' align='left' valign='middle'>&nbsp;</td>
		<td width='10%' align='right' valign='middle' > $grandtotal</td>
	 </tr>
	
						 
		</table>
      </td>
  </tr>	
</table>
</body>
</html>";
					 
ini_set('display_errors',0);
	require CS_PATH.DS.'html2fpdf.php';
	
	$pdf = new HTML2FPDF();
	
	$pdf->AddPage();
	
	$pdf->SetDisplayMode(real,'default'); 
	$pdf->WriteHTML($htmls);
	
	$pdf->Output("SalesInvoiceDetails1.pdf", "D");
?>