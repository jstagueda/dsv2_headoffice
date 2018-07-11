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
						$salesID = array($_POST['salesID'][$x]);
						$explode = explode(',',$_POST['chkInclude'][$x]);
						$ids = $explode[1];
						$idsx .=  $ids.",";
						//echo $ids;
				}
					
				}
					$idsx = substr_replace($idsx ,"",-1);
					
				// and  d.ID in (".$idsx.")
				for($z = 0; $y > $z; $z++){
				//$chekerID = 0;
				//$salesID = $_POST['salesID'][$z];
				$chkInclude = $_POST['chkInclude'][$z];
				$past_due	= $_POST['past_due'][$z];
				$chkIncludex = explode(',',$chkInclude);
				if($chkInclude != $chekerID ){	
				$chkIncludexx = $chkIncludex[0];
				$as_of_date = $_POST['as_of_date'];

						$q = $database->execute("SELECT d.ID, CONCAT(e.Code,'-',a.Code) InvoiceDMNo, e.Name BranchName,f.StreetAdd Address,  a.Code AccountNumber, g.Name Area, f.ZipCode, a.Code, a.Name, b.DaysDue, DATE_FORMAT(d.TxnDate, '%m/%d/%Y') xDate, DATE_FORMAT(d.EffectivityDate,'%m/%d/%Y') DueDate, 
												 CAST(sum(b.OutstandingAmount) AS CHAR) AmountOpen, d.Netamount AmountDue, d.Netamount - b.OutstandingAmount AppliedAmount 
												 FROM customer a
												 INNER JOIN customeraccountsreceivable b ON a.ID = b.CustomerID
												 INNER JOIN tpi_rcustomerpda c ON a.ID = c.CustomerID
												 INNER JOIN salesinvoice d ON b.SalesInvoiceID = d.ID
												 INNER JOIN branch e ON d.BranchID = e.ID
												 LEFT JOIN tpi_customerdetails f ON a.ID = f.CustomerID
												 LEFT JOIN area g ON f.AreaID = c.ID
												 INNER JOIN creditterm h ON h.ID=d.CreditTermID 
												 WHERE a.ID = ".$chkIncludexx." and (DATE_ADD(d.TxnDate, INTERVAL h.Duration DAY) >= '".$as_of_date."')
												 group by a.ID
												 order by e.Name
												 ");			
						
						if($q->num_rows){
							while($r = $q->fetch_object()){
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
												BRANCH: ".$r->BranchName."
											</td>
											<td>
												&nbsp;
											</td>
										</tr>
										<tr>
											
											<td>
												DATE: ".date("d F Y")."
											</td>
										
										<td>
												&nbsp;
										</td>
										</tr>
										<tr>
											<td>
												".$r->Area."&nbsp;
											</td>
										</tr>
										<tr>
											<td>
												".$r->Code."&nbsp;
											</td>
										</tr>
										<tr>
											<td>
												".$r->Name."&nbsp;
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
											<td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Our  records  show that  we  have  not  received  the  amount
												due on  your Tupperware Brands Philippines, Inc. Account amounting  to ".
											$ConverToNumber."".$point." (P ".number_format($past_due,2).")         which had already become past-due. since ".date('d F Y',strtotime($r->xDate)).".
											</p></td>
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please be  informed that per Company policy, a  mandatory  5%
														penalty up  to a maximum of 20%  penalty  will  be  added  to
														your past-due account.  We urge you to immediately respond to
														this demand letter within ten (10) days from invoice due date
														so as  to  minimize  your penalties. If within the prescribed
														period no settlement is made we will be constrained to  refer
														your account to our Lawyer and/or Collection Agency.  In such
														cases, you will be charged collection and legal charges based
														on the total amount due.</p></td>
											&nbsp;
										</tr>
										<tr><td>&nbsp;</td></tr>
										<tr>
											<td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Should  you  have inquiries  regarding your account.   Please
													address them directly to the undersigned.</p></td>
											&nbsp;
										</tr>
										</table>
										<br /><br /><br />
										<table>
										<tr><td>Very truly yours,</td></tr>
										<tr><td>________________</td></tr>
										<tr><td>Branch Executive</td></tr>	
										</table>
										
										
										";
									$pdf->writeHTML($html, true, false, true, false, "");
									$pdf->AddPage();
							}
							
						}
						
							
							
								
						$chekerID = $chkInclude;
					}
					}
					
				
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	ob_start();
	$pdf->Output("spurringletter.pdf", "I");

