<?php	

	
	require_once "../../initialize.php";	
   	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
	global $database;
	//print_r($_POST);
	//die('xxxx');
	
	// create new PDF document
	$pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);
    
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
	$pdf->SetFont("courier", "", 10);
    
	// add a page
	$pdf->AddPage();
			//$row = $query->fetch_object();
							
				$total_numrows = $_POST['total_numrows'];
				$y = 0;
				$page = 0;
				$yy = 0;
				for($x = 0; $total_numrows > $x; $x++){
					$level 		= $_POST['level'];
					if(isset($_POST['chkInclude'][$x])){
						$y++;	
						//$salesID = array($_POST['salesID'][$x]);
						$salesID = array($_POST['salesID'][$x]);
						$explode = explode(',',$_POST['chkInclude'][$x]);
						$ids = $explode[1];
						$idsx .=  $ids.",";
					}
					
					//$joinx = join("",$salesID);
					//$ids .= $joinx.",";
					
				}
					$idsx = substr_replace($idsx ,"",-1);
	
				for($z = 0; $y > $z; $z++){
				//$chekerID = 0;
				$chkInclude = $_POST['chkInclude'][$z];
				$past_due	= $_POST['past_due'][$z];
				$chkIncludex = explode(',',$chkInclude);
				$chkIncludexx = $chkIncludex[0];
				$as_of_date = $_POST['as_of_date'];
				if($chkIncludexx != $chekerID ){
				// and  d.ID in (".$idsx.")
						$q = $database->execute("SELECT d.ID, CONCAT(e.Code,'-',a.Code) InvoiceDMNo, f.StreetAdd Address,  a.Code AccountNumber, g.Name Area, f.ZipCode, a.Code, a.Name, b.DaysDue, DATE_FORMAT(d.TxnDate, '%m/%d/%Y') xDate, DATE_FORMAT(d.EffectivityDate,'%m/%d/%Y') DueDate, 
												 CAST(sum(b.OutstandingAmount) AS CHAR) AmountOpen, d.Netamount AmountDue, d.Netamount - b.OutstandingAmount AppliedAmount 
												 FROM customer a
												 INNER JOIN customeraccountsreceivable b ON a.ID = b.CustomerID
												 INNER JOIN tpi_rcustomerpda c ON a.ID = c.CustomerID
												 LEFT JOIN salesinvoice d ON b.SalesInvoiceID = d.ID
												 INNER JOIN branch e ON d.BranchID = e.ID
												 LEFT JOIN tpi_customerdetails f ON a.ID = f.CustomerID
												 LEFT JOIN area g ON f.AreaID = c.ID
												 INNER JOIN creditterm h ON h.ID=d.CreditTermID and (DATE_ADD(d.TxnDate, INTERVAL h.Duration DAY) >= '".$as_of_date."')
												 WHERE a.ID = ".$chkIncludexx."
												 group by a.ID
												 ");
											
						
						if($q->num_rows){
							while($r = $q->fetch_object()){
							//$amount_open = explode(".", number_format($r->AmountOpen,2,'.', ''));
							$amount_open = explode(".", number_format($past_due,2,'.', ''));
							
							$ConverToNumber = convert_number($amount_open[0]);
							if($amount_open[1] == ""){
								$point = "";
							}else{
								$point = " and ".$amount_open[1]."/100";
							}
								$html = 
										"
										<table>
										<tr>
											<td>
												&nbsp;
											</td>
											<td>
												&nbsp;
											</td>
											<td>
												&nbsp;
											</td>
										</tr>
										<tr>
											<td>
												".$r->Name."
											</td>
											<td>
												&nbsp;
											</td>
											<td>
												".date("M-d-Y")."
											</td>
										</tr>
										<tr>
											<td>
												".$r->Address."
											</td>
										
										<td>
												&nbsp;
										</td>
										
											<td>
											Ref.No.".$r->AccountNumber."&nbsp;
											</td>
										</tr>
										<tr>
											<td>
												".$r->Area."&nbsp;
											</td>
										</tr>
										<tr>
											<td>
												".$r->ZipCode."&nbsp;
											</td>
										</tr>
										
										</table>  
										
										<table>
										<tr><td>&nbsp;</td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td>Dear Sir/Madam</td>
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Our Client TUPPERWARE BRANDS PHIL., INC. has referred to us for immediate action your long overdue account
											including penalty in the ammount of Philippine peso (PhP) ".
											$ConverToNumber."".$point." (P ".number_format($past_due,2).").
											</p></td>
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We understand that despite repeated oral and written demands made by our client, not to mention the leniency extended
											to you. you ahve failed and refused to settle said account.</p></td>
											&nbsp;
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In view of the above, final demand is hereby served  on you
													to pay our client the full amount of ".number_format($r->AmountOpen,2)."
													within  five  (5)  days  from  your  receipt   of  this  letter.
													Should you fail to pay your outstanding obligation within the
													given  period, we shall be constrained to institute the
													necessary legal action against you without further notice, to
													enforce our client's rights and interests through  the  recovery
													of actual & exemplary  damages, including interests,  collection
													charges and court costs.</p></td>
											&nbsp;
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Should you have any question regarding the above or will be
												making payment, please visit  or  communicate  immediately  with
												TWP - Branch Operations Manager  at  its offices written below;
												otherwise, we will promptly file the case without further notice.</p></td>
											&nbsp;
										</tr>
										<tr><td>&nbsp;</td></tr>
										</table>
										<br /><br /><br />
										<table>
										<tr><td>Very truly yours,</td></tr>
										<tr><td>&nbsp;</td></tr>
										<tr><td>_____________________</td></tr>
										<tr><td>ATTY. ALAN D. MIJARES</td></tr>
										<tr><td>Legal Counsel for</td></tr>	
										</table>
										<table>
										<tr><td>Tupperware Brands Phil., Inc.</td></tr>
										</table>
										<br /><br /><br /><br />
										<table>
											<tr>
												<td>ADDRESS YOUR INQUIRIES TO :
												</td>
											</tr>
											<tr>
												<td>
													&nbsp;
												</td>
											</tr>
											<tr>
												<td>TUPPERWARE - #432 2nd Flr SVDC Bldg
														
												</td>
											</tr>
											<tr>
												<td>Quirino Highway Talipapa</td>
											</tr>
											<tr>
												<td>Tel. No.453-0351</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
											</tr><tr>
												<td><i>NOTE: THIS IS A COMPUTER GENERATED FORM, NO NEED FOR BOM/BSOM/FSOM SIGNATURE.</i></td>
											</tr>
										</table>
										
										";
										$pdf->writeHTML($html, true, false, true, false, "");
										$pdf->AddPage();
							}
						}
						
						$chekerID = $chkIncludexx;
					}
					}
					
				
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	ob_start();
	$pdf->Output("demmandletter.pdf", "I");

