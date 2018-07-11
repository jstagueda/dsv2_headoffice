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
						//checkbox
						$checkbox = $explode[0];
						$checkboxx .= $checkbox.",";
				}
					
				}
					$idsx = substr_replace($idsx ,"",-1);
					$chkIncludexx = substr_replace($checkboxx ,"",-1);
					
					

				//$chkInclude = $_POST['chkInclude'][$z];

						$q = $database->execute("SELECT c.ID CustomerPDAID, d.ID, CONCAT(e.Code,'-',a.Code) InvoiceDMNo, e.Name BranchName,f.StreetAdd Address,  a.Code AccountNumber, g.Name AREA, f.ZipCode, a.Code, a.Name, b.DaysDue, DATE_FORMAT(d.TxnDate, '%m/%d/%Y') xDate, DATE_FORMAT(d.EffectivityDate,'%m/%d/%Y') DueDate, 
												 sum(b.OutstandingAmount) Write_Off_Amount, d.Netamount AmountDue, d.Netamount - b.OutstandingAmount AppliedAmount,h.Code StatusCode
												 FROM customer a
												 INNER JOIN customeraccountsreceivable b ON a.ID = b.CustomerID
												 INNER JOIN tpi_rcustomerpda c ON a.ID = c.CustomerID
												 INNER JOIN salesinvoice d ON b.SalesInvoiceID = d.ID
												 INNER JOIN branch e ON d.BranchID = e.ID
												 LEFT JOIN tpi_customerdetails f ON a.ID = f.CustomerID
												 LEFT JOIN AREA g ON f.AreaID = c.ID
												 INNER JOIN status h on h.ID = a.StatusID
												 WHERE a.ID in (".$chkIncludexx.") and  d.ID in (".$idsx.")
												 group by a.ID
												 order by e.Name");		

						$qu = $database->execute("SELECT c.ID CustomerPDAID FROM customer a
												 INNER JOIN customeraccountsreceivable b ON a.ID = b.CustomerID
												 INNER JOIN tpi_rcustomerpda c ON a.ID = c.CustomerID
												 INNER JOIN salesinvoice d ON b.SalesInvoiceID = d.ID
												 INNER JOIN branch e ON d.BranchID = e.ID
												 LEFT JOIN tpi_customerdetails f ON a.ID = f.CustomerID
												 LEFT JOIN AREA g ON f.AreaID = c.ID
												 INNER JOIN status h on h.ID = a.StatusID
												 WHERE a.ID in (".$chkIncludexx.") and  d.ID in (".$idsx.")
												 order by e.Name");		
												 
							//HEADER					 
							$qb = $database->execute("select b.Name from branchparameter a inner join branch b on a.BranchID = b.ID");
							$html = "<table>";
							$html .= "<tr>";
							$html .= "<td>BRANCH: ".$qb->fetch_object()->Name."</td>";
							$html .= "</tr>";
							$html .= "<tr>";
							$html .= "<td>AMOUNT FROM: &nbsp;".$_POST['w_o_from']."&nbsp; TO: &nbsp;".$_POST['w_o_to']."</td>";
							$html .= "</tr>";
							$html .= "<tr>";
							$html .= "<td>DAYS OVERDUE FROM: ".$_POST['days_overdue_from']."&nbsp;&nbsp; TO: ".$_POST['days_overdue_to']."</td>";
							$html .= "</tr>";
							$html .= "<tr>";
							$html .= "<td>AS OF DATE: ".$_POST['as_of_date']."</td>";
							$html .= "</tr>";
							$html .= "<tr>";
							$html .= "<td>&nbsp;</td>";
							$html .= "</tr>";
							$html .= "</table>";
							
							$html .= "<table border = ''>";
							$html .= "<tr>
										<th>Code</th><th>Name</th><th>Status</th><th>Proposed Write - Off</th>
									  </tr>
									  <tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>
									  </tr>";
							
						if($q->num_rows){
							while($r = $q->fetch_object()){
									$xtotal = 0;
									$xtotal += $r->Write_Off_Amount;
									$html .= "<tr>
												<th>".$r->AccountNumber."</th>
												<th>".$r->Name."</th>
												<th>".$r->StatusCode."</th>
												<th>".number_format($r->Write_Off_Amount,2)."</th>
											  </tr>";
									//$r->CustomerPDAID;
									//$database->execute("UPDATE tpi_rcustomerpda SET tpi_PDA_ID = 7 WHERE ID = ".$r->CustomerPDAID);
							}
							$html .= "<tr>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
												<th>___________</th>
									 </tr>
									 <tr>
												<th>&nbsp;</th>
												<th># of dealers ".$q->num_rows."</th>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
									 </tr>
									 <tr>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
												<th></th>
									 </tr>
									 <tr>
												<th>&nbsp;</th>
												<th>TOTAL AMOUNT:</th>
												<th>&nbsp;</th>
												<th>".number_format($xtotal,2)."</th>
									 </tr>
									 <tr>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
												<th></th>
									 </tr>
									 ";
							$html .= "</table>";
							$html .= "<table>";
							$html .= "
									<tr>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
												<th></th>
									 </tr>
									 <tr>
												<th>&nbsp;</th>
												<th><b>***NOTHING FOLLOWS***</b></th>
												<th>&nbsp;</th>
												<th></th>
									 </tr>";
							$html .= "</table>";
						$pdf->writeHTML($html, true, false, true, false, "");
						$pdf->AddPage();
						}
						
						if($qu->num_rows){
							while($r = $qu->fetch_object()){
								
								$database->execute("UPDATE tpi_rcustomerpda SET tpi_PDA_ID = 7 WHERE ID = ".$r->CustomerPDAID);
							}
						}
						
					
					
					
				
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	ob_start();
	$pdf->Output("acct_endorsmnt_coll_agncy_lwyr_print.pdf", "I");

