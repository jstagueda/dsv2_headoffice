<?php
	require_once "../../initialize.php";
	include IN_PATH.DS."scRecordInvCountDetails_print.php";	
	global $database;	
	ini_set('max_execution_time', 300);
    $strTable = "";

    if($addtnl == 0)
    {
	if ($rs_product->num_rows)
					{

											
							$cnt = 0;
							while ($row = $rs_product->fetch_object())
							{
								$cnt ++;
								($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';                 
								$pname = $row->itemdesc;
								$pid = $row->ProductID;
								$pcode = $row->itemcode;	
								$createdqty = $row->cqty;							
								$cnttag = $row->tag;	
								$iid = $row->InventoryID;
								$wid2 = $row->wareID;
								$prodPrice = number_format($row->UnitPrice, 2, '.', '');
								$location = $row->Location;
								
								$amntCntd = $createdqty * $prodPrice;
								$zerVal = ' 0 ';
								
								$rs_chckInvDesc = $sp->spSelectInventory($database, $wid2,$iid , $pid);
								if($iid != 0)
								{
									if ($rs_chckInvDesc->num_rows)
									{
										while ($row1 = $rs_chckInvDesc->fetch_object())
										{
											$soh = $row1->SOH;
											if($createdqty != 0)
											{
											    if ($row1->SOH != $createdqty)
											    {
		
											    	
											    	if($row1->SOH > $createdqty)
											    	{
											    		
											    		$QtyUnder = $createdqty;
											    		$ValUnder = $amntCntd;
											    		
											    		$QtyOver = $zerVal;
											    		$ValOver = $zerVal;
											    		
											    	}
											    	else if ($row1->SOH < $createdqty)
											    	{
											    		
											    		$QtyOver = $createdqty;
											    		$ValOver = $amntCntd;
											    		$QtyUnder = $zerVal;
											    		$ValUnder = $zerVal;
											    	}
											    	//<td height='10' align='center'> $prodPrice </td>
											$strTable .= "<tr align='center'>
													    	<td height='10'> $cnttag</td>
													        <td height='10'> $pcode</td>
													        <td height='10'> $pname</td>
													        <td height='10'> $location</td>	
													        <td height='10'> $row1->SOH  </td>
													        <td height='10'> $zerVal </td>
													        <td height='10'> $createdqty </td>
													        <td height='10'> $amntCntd </td>
													        <td height='10'> $QtyUnder </td>
													        <td height='10'> $ValUnder </td>
													        <td height='10'> $QtyOver </td>
													        <td height='10'> $ValOver </td>	 						  
													</tr>"  ;
											    }
											}
											else
											{
												//<td height='10' align='center'> $prodPrice </td>
												$strTable .= "<tr align='center'>
												    	<td height='10' align = 'center'> $cnttag</td>
												        <td height='10'> $pcode</td>
												        <td height='10' align='left'> $pname</td>
												        <td height='10'> $location</td>	
												        <td height='10' align='center'> $row1->SOH  </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												</tr>"  ;
											}
										}
									}
								}
								else
								{
									   	//<td height='10' align='center'> $prodPrice </td>
										$strTable .= "<tr align='center'>
												    	<td height='10' align = 'center'> $cnttag</td>
												        <td height='10'> $pcode</td>
												        <td height='10' align='left'> $pname</td>
												        <td height='10'> $location</td>	
												        <td height='10' align='center'> $zerVal  </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>
												        <td height='10' align='center'> $zerVal </td>			  
												</tr>"  ;
								}
							}
					}
    }
    else
    {
    	if (isset($_GET['prodlist']) && $_GET['prodlist'] != "")
								{
									$cnt = 0;
									$zerVal = ' 0 ';
									
									ini_set('display_errors',0);
									require CS_PATH.DS.'html2fpdf.php';
									$pdf = new HTML2FPDF();
									$pdf->SetMargins(10,5,0);
									
									$prodlist_url = split(',', $_GET['prodlist']);
		                    		$_SESSION['prod_list'] = $prodlist_url;
		                    		
		                    		$cntlist_url = split(',', $_GET['cntlist']);
		                    		$_SESSION['cnt_list'] = $cntlist_url;
		                    		
		                    		$quantitylist_url = split(',', $_GET['quantitylist']);
		                    		$_SESSION['quantity_list'] = $quantitylist_url;
		                    		
		                    		$locationlist_url = split(',', $_GET['loclist']);
		                    		$_SESSION['loc_list'] = $locationlist_url;
		                    		
		                    	
		                    		for ($i = 0, $n = sizeof($_SESSION['prod_list']); $i < $n; $i++ )
		                    		{
		                    			$rs_proddet = $sp->spSelectProductbyID($database, $_SESSION['loc_list'][$i], 1);
		                    			$rs_location = $sp->spSelectLocationByID($database, $_SESSION['loc_list'][$i]);
										if ($rs_location->num_rows)
				                  		{
				                  		 while ($row_loc = $rs_location->fetch_object())
				                  		 {
				                  		   $location = $row_loc->Name;
				                  		 }
			                  		} 
		                    		
		                        		if ($rs_proddet->num_rows)
		                        		{
		                        			while ($row = $rs_proddet->fetch_object())
		                        			{  
		                        				
		                                    	$cnt ++;
		                                        
		                                        $pcode = $row->Code;
		                                        $pname = $row->Name;
												$pid = $row->ID;
		                                        $desc = $row->Description;
		                                        $utname = $row->UnitType;
												$invID = $row->InvID;
												$soh = $row->SOH;
												$prodPrice = number_format($row->UnitPrice, 2, '.', '');
												$createdqty = $_SESSION['quantity_list'][$i];													
												$lineno = $_SESSION['cnt_list'][$i];
												$location - $row->Location;
												$amntCntd = $createdqty * $prodPrice;
														       
												
											 $strTable .= "<html>
												<body>   
																				
												<table border='1' width='100%'>
												  <tr align='center'>
												   	<td width='21%'>
														<table border='0' width='100%'>				
														<tr align='left' valign='top'>
															<td width='50%'></td>
															<td width='50%' valign='top'></td>
												   		</tr>
														</table>
													</td>
										
														<table width='100%' >
														<tr align='center'>
															<td height='20' width='15%'><strong>Branch:</strong>&nbsp;&nbsp; $branchname</td>																
															<td height='20' width='15%'><strong>Worksheet No:</strong>&nbsp;&nbsp; $transno</td>
															<td height='20' width='10%'></td>	
															<td height='20' width='10%'></td>		
																
														</tr>
														<tr align='center'>
															<td height='20' width='30%'><strong>Location:</strong>&nbsp;&nbsp; $location</td>																	
															<td height='20' width='20%'><strong>Product Code:</strong>&nbsp;&nbsp; $pcode</td>
															<td height='20' width='10%'></td>	
															<td height='20' width='10%'></td>
																
														</tr>
														<tr align='center'>
															<td height='20' width='20%'><strong>Tag Number:</strong>&nbsp;&nbsp; $lineno</td>																	
															<td height='20' width='80%' ><strong>Product Description:</strong>&nbsp;&nbsp; $pname</td>
														
																
														</tr>
														
														
														</table>
													
														
														<table  width='100%'>
														<tr align='center'>
															<td height='20' align ='center' width='20%'><strong>Quantity Counted:</strong></td>
															<td height='20' width='25%'>____________________</td>
															<td height='20' width='10%'></td>
															<td height='20' align ='right' width='20%'><strong>Quantity Recounted:</strong></td>
															<td height='20' width='22%'>____________________</td>
														</tr>
														<tr align='center'>
															<td height='20' align ='right' width='20%'><strong>UM:</strong></td>	
															<td height='20' width='25%'>PC</td>
															<td height='20' width='10%'></td>
															<td height='20' align ='left' width='20%'><strong>UM:</strong></td>	
															<td height='20' width='22%'>PC</td>																		
														</tr>
														<tr align='center'>
															<td height='20' align ='right' width='20%'><strong>Counted By:</strong></td>
															<td height='20' width='25%'>____________________</td>
															<td height='20' width='10%'></td>
															<td height='20' align ='left' width='20%'><strong>Recounted By:</strong></td>
															<td height='20' width='22%'>____________________</td>
														</tr>	
														<tr align='center'>
															<td height='20' align ='right' width='20%'><strong>Date Counted:</strong></td>	
															<td height='20' width='25%'>____________________</td>
															<td height='20' width='10%'></td>
															<td height='20' align ='left' width='20%'><strong>Date Recounted:</strong></td>
															<td height='20' width='22%'>____________________</td>
														</tr>	
														<tr align='center'>	
															<td height='20' align ='right' width='20%'><strong>Checked By:</strong></td>
															<td height='20' width='25%'>____________________</td>
															<td height='20' width='10%'></td>
															<td height='20' width='20%'></td>
															<td height='20' width='22%'></td>	
														</tr>	
														<tr align='center'>	
															<td height='20' align ='right' width='20%'><strong>Remarks:</strong></td>
															<td height='20' width='25%'>____________________</td>
															<td height='20' width='10%'></td>
															<td height='20' width='20%'></td>
															<td height='20' width='22%'></td>
														</tr>
																			
														
														</table>
																											
												  </tr>	
												</table>
												</body>
											</html>";
		                        			}
		                        		}
		                    		}
								}
    }		
					
					
    if($addtnl == 0)
    {	
	$html = " <html>
					<body>   
				
				<table border='1' width='100%'>
					<tr align='center'>
						<td width='21%'>
							<table border='0' width='100%'>				
								<tr align='left' valign='top'>
									<td width='50%'></td>
									<td width='50%' valign='top'></td>
								</tr>
							</table>
						</td>
						<td width='66%'>
							<table border='0' width='100%'>
								<tr align='center'>
									 <td height='20' width='20%'><strong>Branch:</strong></td>
								     <td height='20'  >$branchname</td>
								     <td height='20'></td>
									 <td height='20' ></td>
								</tr>
							</table>
						</td>
						<td width='100%'>
							<table border='0' width='50%'>				
							 	<tr align='center'>
							 		 <td height='20' width='20%'><strong>Worksheet:</strong></td>								 
								      <td height='20' width='20%'>$transno</td>
								     <td height='20' width='20%'><strong>Print Date:</strong></td>
								     <td height='20' width='20%' >$printDate</td>		
								     
								</tr>
							</table>
						</td>
					</tr>	
				</table>
				<table width='100%'  border='1'  cellpadding='0' cellspacing='0'>							
					 <tr align='center'>
						 <td width='8%'><div align='center'><strong>Line #</strong></div></td>
						 <td width='9%'><div align='center'><strong>Code</strong></div></td>
						 <td width='30%'><div align='center'><strong>Item Description</strong></div></td>
				 		 <td width='12%'><div align='center'><strong>Location</strong></div></td>
						 <td width='10%'><div align='center'><strong>Qty To Adjust</strong></div></td>
						 <td width='10%'><div align='center'><strong>Val To Adjust</strong></div></td>
						 <td width='10%'><div align='center'><strong>Counted Qty</strong></div></td>
						 <td width='11%'><div align='center'><strong>Amounted Count</strong></div></td>
						 <td width='10%'><div align='center'><strong>Qty Under</strong></div></td>
						 <td width='10%'><div align='center'><strong>Val Under</strong></div></td>
						 <td width='10%'><div align='center'><strong>Qty Over</strong></div></td>
						 <td width='10%'><div align='center'><strong>Val Over</strong></div></td>
					 </tr>
     				$strTable
				</table>
			</body>
		</html>
			";
    }
    						 //<td width='9%' align= 'center'><strong>Price</strong></td>
    else
    {
    	$html = "$strTable";
    	$pdf->SetAutoPageBreak($auto,$margin=30);
    	$pdf->AddPage();
		$pdf->WriteHTML($html); 
		$pdf->SetDisplayMode(real,'default');
		$pdf->Output("InventoryCount.pdf", "D");
		exit;
    }
    
	ini_set('display_errors',0);	
	require CS_PATH.DS.'html2fpdf.php';
	$pdf = new HTML2FPDF('L', 'mm', 'A4');
	//$pdf->SetMargins(10,5,0);
	$pdf->AddPage();
	$pdf->SetDisplayMode(real,'default'); 
	$pdf->WriteHTML($html);
	$pdf->Output("InventoryCount.pdf", "D");	
	
?>
