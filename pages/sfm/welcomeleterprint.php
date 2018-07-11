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
				for($x = 0; $total_numrows > $x; $x++){
					$level 		= $_POST['level'];
					if(isset($_POST['chkInclude'][$x])){
						$y++;	
					}
				}
				
				for($z = 0; $y > $z; $z++){
				$chkInclude = $_POST['chkInclude'][$z];
						$q = $database->execute("SELECT a.Name FullName,a.Code AccountNumber, b.StreetAdd Address,  c.Name Area, b.ZipCode FROM customer a
												 LEFT JOIN tpi_customerdetails b ON a.ID = b.CustomerID
												 LEFT JOIN area c ON b.AreaID = c.ID
												 WHERE a.ID = ".$chkInclude);
						
						if($q->num_rows){
							while($r = $q->fetch_object()){
								$html = 
										"
										<table>
										<tr>
											<td>
												".date('F d, Y')."
											</td>
										</tr>
										<tr>
											<td>
												".$r->FullName."
											</td>
										</tr>
										<tr>
											<td>
												".$r->Address."
											</td>
										</tr>
										<tr>
											<td>
												".$r->Area."
											</td>
										</tr>
										<tr>
											<td>
												".$r->ZipCode."&nbsp;
											</td>
										</tr>
										<tr>
											<td>
											ACCT.NO.".$r->AccountNumber."&nbsp;
											</td>
										</tr>
										</table>  
										
										<table>
										<tr>
										<td>&nbsp;</td>
										</tr>
										<tr>
										<td>Dear Sir / Madam:</td>
										</tr>
										<tr>
										<td>&nbsp;</td>
										</tr>
										<tr>
										<td><b>Welcome to the family of TUPPERWARE BRANDS PHILIPPINES, INC. !!!</b></td>
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td><p>We take great pleasure in having you as our partner in business. Please present 
											this letter to your Branch/Service Center immediately to validate our record  
											and allow CL increases to your account in the future. 
											Our Branch Executives are eagerly waiting for your visit.</p></td>
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td><p>Also, please fill-up the acknowledgment slip found at the lower part of this letter</p></td>
											&nbsp;
										</tr>
										<tr><td>&nbsp;</td></tr>
										
										<tr>
											<td><p>Thank you.</p></td>
											&nbsp;
										</tr>
										</table>
										<table align = 'right'>
										<tr align = 'right'>
											<td><p>________________</p></td>
										</tr>
										
										<tr align = 'right'>
											<td><p>BRANCH EXECUTIVE</p></td>
										</tr>
										</table>
								  <table>
								  <tr>
								  <td>_________________________________________________________________________________________</td>
								  </tr>
								  
								  </table>
								  <table border = >
									<tr align = 'center'>
										<td>&nbsp;</td><td><b>ACKNOWLEDGMENT RECEIPT</b></td><td>&nbsp;</td>
								    </tr>
									<tr><td>&nbsp;</td></tr>
									<tr><td>&nbsp;</td></tr>
								  </table>
								  <table border = >
									<tr><td>&nbsp;</td></tr>
									
									<tr>
										<td><p>THIS IS TO ACKNOWLEDGE RECEIPT OF MY WELCOME LETTER DATED ".DATE("m/d/Y").". PLEASE CHECK APPLICABLE SPACE:</p></td>
								    </tr>
									<tr><td>&nbsp;</td></tr>
									<tr>
										<td>___________ THIS IS TO CONFIRM THAT MY NAME AND ADDRESS IS ACCURATE AND COMPLETE</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
								    </tr>
									<tr>
										<td>___________ PLEASE FIND BELOW CORRECTION ON MY PERSONAL INFORMATION:</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
								    </tr>
									<tr>
										<td>_________________________________________________________________________________________</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
								    </tr>
									<tr>
										<td>_________________________________________________________________________________________</td>
									</tr>
										<tr>
										<td>&nbsp;</td>
								    </tr>
										<tr><td>&nbsp;</td></tr>
								  </table>	
								  <table>
									<tr><td align = 'center'>___________________________</td></tr>
									<tr><td>SIGNATURE OVER PRINTED NAME</td></tr>
								  </table>";
								
								
							}
						}
						
							$pdf->writeHTML($html, true, false, true, false, "");
							$page += 1;
							if($page < $y){
								$pdf->AddPage();
							}
					}
				
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	ob_start();
	$pdf->Output("welcomeleter.pdf", "I");

