
<?PHP 
	require_once "../../initialize.php";	
					
	
	$offset = 0;
	$RPP= 0;
	$vDateFrom = '';
	$vDateTo = '';
	
	$date = date("m/d/Y");
	$prevdate = strtotime ( '-1 month' , strtotime ( $date ) ) ;
	$prevdate = date ( 'm/d/Y' , $prevdate );
	
	(isset($_GET['dFrom'])) ? $vDateFrom = $_GET['dFrom'] : $vDateFrom = $prevdate;			
	(isset($_GET['dTo'])) ? $vDateTo = $_GET['dTo'] : $vDateTo = $date;		
    (isset($_GET['sKey'])) ? $vSearch = $_GET['sKey'] : $vSearch = '';		
	
	
	$rs = $sp->spSelectSODetailedReport($vDateFrom, $vDateTo, $vSearch);
		
						
$html = '';
	  
$html = 
"
<html>
<head>

    <style type='text/css'>
        .style2
        {
            width: 366px;
        }
        .style3
        {
            width: 273px;
        }
        .style4
        {
            width: 319px;
        }
        .style5
        {
            width: 458px;
        }
        .style6
        {
            width: 285px;
        }
        .style7
        {
            width: 146px;
        }
		 .style9
        {
            width: 29px;
        }
        .style10
        {
            width: 4%;
        }
        .style11
        {
            width: 22px;
        }
        </style>

</head>
<body>
<link rel='stylesheet' type='text/css' href='../../css/ems.css'>
<table border='0' width='100%'>
	<tr align='center'>
		
	</tr>
</table>
<table border='0' width='100%'>
	<tr align='center'>
		
	</tr>
</table>
<br />
<br />


<table border='0' width='100%'>
	<tr align='center'>
		<td width='18%' align='left'>
			<strong>SO Detailed Report</strong></td>
	</tr>
</table>
<br />



<table width='50%'>
    <tr>
	    <td align='left' width='1%'><strong>From:</strong></td>
		<td align='left' width='1%'>$vDateFrom</td>
		<td align='left' width='0%'></td>
		<td align='left' width='1%'><strong>To:</strong></td>
		<td align='left' width='1%'>$vDateTo</td>
	</tr>
			
</table>


<table border='0' width='100%'>
	<tr>
		<td height='30' align='right'><strong>&nbsp;</strong></td>		
	</tr>		
</table>

<table border='0' width='100%'>
<tr align='center'>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'></div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'></div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'></div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'></div></td>			
						  <td width='10%' height='20' class='borderBR'><div align='left' class='padl5'> </div></td>			
						  <td width='10%' height='20' class='borderBR'><div align='left' class='padl5'> </div></td>
						  <td width='2%' height='20' class='borderBR'><div align='right' class='padr5'></div></td>
						  <td width='2%' height='20' class='borderBR'><div align='right' class='padr5'>Quantity</div></td>
						  <td width='10%' height='20' class='borderBR'><div align='right' class='padr5'></div></td>
						  </tr>
					
					<tr align='center'>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'>SO Number</div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'>Document Number</div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'>SO Date</div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'>Customer</div></td>			
						  <td width='10%' height='20' class='borderBR'><div align='left' class='padl5'>Product Code</div></td>			
						  <td width='10%' height='20' class='borderBR'><div align='left' class='padl5'>Product Name</div></td>
						  <td width='2%' height='20' class='borderBR'><div align='right' class='padr5'>Booklet</div></td>
						  <td width='2%' height='20' class='borderBR'><div align='right' class='padr5'>Ticket</div></td>
						  <td width='10%' height='20' class='borderBR'><div align='right' class='padr5'>Total Price</div></td>
						  </tr>
";

	   $ctr = 0;
	    $nGrandTot = 0;
	    $cs = 0;
	    $pcs = 0;
	    $totNetAmt = 0;
	    $totAmt = 0;
	    $sSOno = '';
	    $sSONum = '';
	    $sDocNum = '';
	    $dteTxn = '';
	    $sCust = '';
	    $nGrandTot2 = 0;
	    
		if($rs->num_rows)
			{
				$rowalt=0;
				
				while($row = $rs->fetch_object())
				{
					 $rowalt++;
					($rowalt%2) ? $class = "" : $class = "bgEFF0EB";

				   if($row->UOMID == 5)	
					{
						$qty = $row->Qty;
						$qty2 = '0';
						$cs	= $cs + $row->Qty;
					}else
					{
															
						$qty = '0';
						$qty2 = $row->Qty;	
						$pcs = $pcs + $qty2;	
					}
					
					
					$totNetAmt = number_format($row->totamt2, 2);
					$totAmt = number_format($row->totamt, 2);	
					$nGrandTot = $nGrandTot + $row->totamt;
					$nGrandTot2 = number_format($nGrandTot, 2);
					
					$query2 = $sp->spSelectSODetailedReport($vDateFrom, $vDateTo,$row->SONo);
				    $num2=$query2->num_rows;
				    
					if($row->SONo == $sSOno)
					{
						$sSONum = "";
						$sDocNum = "";
						$txnDate = "";
						$sCust = "";
					}
					else
					{
						$sSONum = $row->SONo;
						$sDocNum = $row->DocNo;
						$txnDate = date("M d,Y",strtotime($row->TxnDate));
						$sCust = $row->Customer;
					}
					
					
					$html .= "<tr align='center' class='$class'>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'>$sSONum</div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'>$sDocNum</div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'>$txnDate</div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'>$sCust</div></td>			
						  <td width='10%' height='20' class='borderBR'><div align='left' class='padl5'>$row->ProdCode</div></td>			
						  <td width='10%' height='20' class='borderBR'><div align='left' class='padl5'>$row->Product</div></td>
						  <td width='2%' height='20' class='borderBR'><div align='right' class='padr5'>$qty</div></td>
						  <td width='2%' height='20' class='borderBR'><div align='right' class='padr5'>$qty2</div></td>
						  <td width='10%' height='20' class='borderBR'><div align='right' class='padr5'>$totAmt</div></td>						  
						</tr>  ";
					
						
						
					  $sSOno = $row->SONo;
				      $ctr = $ctr + 1;
				     
				      if($ctr == $num2)
						{
							
						 $html .= "
					
							<tr class='bgE6E8D9'>
							
						<td width='5%' height='20' class='borderBR'><div align='left' class='padl5'></div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'></div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'></div></td>
						  <td width='5%' height='20' class='borderBR'><div align='left' class='padl5'></div></td>			
						  <td width='10%' height='20' class='borderBR'><div align='left' class='padl5'> </div></td>			
						  <td width='10%' height='20' class='borderBR'><div align='left' class='padl5'> </div></td>
						  <td width='2%' height='20' class='borderBR'><div align='right' class='padr5'>TOTAL {per SO} :</div></td>
						  <td width='2%' height='20' class='borderBR'><div align='right' class='padr5'>$totNetAmt</div></td>
						  <td width='10%' height='20' class='borderBR'><div align='right' class='padr5'></div></td>
															
							</tr>";	
						  
						   $ctr = 0;							  
						} 
				      	
					}
					
				$rs->close();
											
			}
			else
			{
			echo "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td></tr>";
			}
	
$html .="</table>	
</table>

<table border='0' width='100%'>
	<tr>
		<td width='30%' height='20'>&nbsp;</td>
		<td width='70%' height='20'><br /><br /><br /></td>
	</tr>
	<tr>
		<td width='30%' height='20'>&nbsp;</td>
		<td width='70%' height='20'>&nbsp;&nbsp;&nbsp;</td>
	</tr>		
</table></body></html>
";

require CS_PATH.DS.'html2fpdf.php';
$pdf=new HTML2FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFontSize(3);
$pdf->SetFont('Arial','B',2);
$pdf->WriteHTML($html);
$pdf->Output("sales_vwsodetailed.pdf", "D");	
?>				
 


