<?php
/*   
  @modified by John Paul Pineda.
  @date October 23, 2012.
  @email paulpineda19@yahoo.com         
*/
 
require "../../initialize.php";	
include  IN_PATH."scprOfficialReceipt.php";

if($printctr > 1)
{   /**
	$footer = '<table width="50%" border="0" align="left" cellpadding="0" cellspacing="0">
			<tr>
 					<td heigh="20"><strong>**Duplicate Copy -  '.$printctr.'    copies printed.</strong></td>
			</tr>
			</table>';**/
	$footer = "***** R E P R I N T *****";		
}
else

{
	$footer = '';
}

$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >

		<tr>
			<td height="20" width="30%">&nbsp;</td>
			<td height="20" width="40%">&nbsp;</td>
			<td height="20" width="30%" align="right"><strong>'.$orNo.'</strong></td>
		</tr>
		<tr>
			<td height="20">&nbsp;</td>
			<td height="20">&nbsp;</td>
			<td height="20" align="right">'.$txndate.'</td>
		</tr>
		<tr>
			<td height="20">&nbsp;</td>
			<td height="20" align="center"><Strong>'.$footer.'</strong></td>
			
			<---if($printctr != 1)
			{
			<td height="20" align="left"><Strong>***** R E P R I N T ***** '.$printctr.'</strong></td>
			}----/>
			
			<td height="20" align="right">'.$txntime.'</td>
		</tr>
		<tr>
			<td height="20" align="left">'.$branchname.'</td>
			<td height="20" align="right">TIN : '.$tinno.'</td>				
			<td height="20" align="right">&nbsp;'.$memono.'</td>
		</tr>
	  	<tr>
	  		<td height="20" align="left">'.$address.'</td>
			<td height="20" align="right"><br>P.N. : '.$permitno.'<br>SN  : '.$serversn.'</td>				
			<td height="20" align="left">&nbsp;</td>
	  	</tr>
	  	<tr>
	    	<td height="20">&nbsp;</td>
	    	<td height="20">&nbsp;</td>
	    	<td height="20">&nbsp;</td>
	  	</tr>
	  	<tr>
	  		<td height="20" align="left" valign="top">IBM/IGS : </td>
	  		<td height="20" align="left" >&nbsp;'.$customer.'</td>
			<td height="10" align="left" >&nbsp;<x-small>Next Due Date:'.$vnextduedate.'</x-small></td>
	  	</tr>
	  	<tr>
	  		<td height="20" align="left" valign="top">Amount Paid :   </td>
	  		<td height="20" align="left" /*colspan="2" valign="top"*/>&nbsp;'.$totalamt.' '.$amtcomnt.'</td>		   
			<td height="20" align="left" ><font size="9">&nbsp;Next Due Amount:'.$vnextdueamt.'</font></td>
	  	</tr>
	  	<tr>
	    	<td height="20" align="left" valign="top">In Words :   </td>
	    	<td height="20" align="left" valign="top" colspan="2">&nbsp;'.$totalwords.'</td>
	  	</tr>
	  	<tr>
	    	<td height="20" align="left" valign="top">Remarks :   </td>
	    	<td height="20" align="left" >&nbsp;'.$remarks.'</td>
			<td height="20" align="left" >&nbsp;Available CL:</td>
	  	</tr>
	  	<tr>
	   		<td height="20" align="left" valign="top">Applies to :   </td>
	    	<td height="20" align="left" valign="top" colspan="2">&nbsp;'.$appliesto.'</td>
	  	</tr>
		<---
		<tr>
	    	<td height="20">&nbsp;'.$printctr.'</td>
	    	<td height="20">&nbsp;'.$vnextdueamt.'</td>
	    	<td height="20">&nbsp;'.$vnextduedate.'</td>
	  	</tr>
		---/>
	  	<---
		<tr>
	    	<td height="20" align="right" valign="top"> Issued by :</td>		    
			<td height="20" align="left" valign="top" colspan="2"><u>&nbsp;'.$vcreatedby.'</u></td>		    
		</tr>
		
	  	<tr>
	    	<td>&nbsp;</td>
	    	<td height="20" colspan="2" align="center"><small>(CASHIER)</small></td>
	  	</tr>
		--/>
	  	<tr>
	    	<td colspan="2" align="left"><i><x-small>Note: Cheque payments are subject to bank clearance before finalization. </x-small></i></td>	
			<td height="20" align="left"><i>Issued by:&nbsp;'.$vcreatedby.'</i> </td>		    		
	  	</tr>		   
		</table>';
/*
require_once("../../tcpdf/config/lang/eng.php");
require_once("../../tcpdf/tcpdf.php");

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

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
$pdf->SetFont("times", "", 8);

$pdf->SetMargins(5,1,1);

//$pdf->setPageFormat("GIC_OR", "P");

// add a page
$resolution= array(252, 612);
$pdf->AddPage('L', $reslution);

// Print text using writeHTML()
$pdf->writeHTML($html, true, false, true, false, "");

// reset pointer to the last page
$pdf->lastPage();

// Close and output PDF document
$pdf->Output("SalesOfficialReceipt.pdf", "I");
*/	
ini_set("display_errors",0);
require CS_PATH.DS."html2fpdf.php";

$pdf = new HTML2FPDF("P","mm","or");
$pdf->SetMargins(10,1,1);
$pdf->SetFont("times", "", 8);
$pdf->AddPage();
$pdf->SetDisplayMode(real,"default"); 
$pdf->WriteHTML($html);
$pdf->Output('SalesOfficialReceipt.pdf', 'D');