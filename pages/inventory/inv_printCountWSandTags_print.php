<?php 
	require_once "../../initialize.php";
	include IN_PATH.'scPrintCountWSandTags_print.php';
	global $database;
	ini_set('max_execution_time', 300);

	if($prodall == 2)
	{
		if ($rs_warehouse->num_rows)
                  			{
                  				$whi = 0;
                  				$cnt = 0;
                  				while ($row_wh = $rs_warehouse->fetch_object())
                  				{
                  					$wid = $row_wh->ID;
                  					$whi += 1;
                  					$rs_location = "rs_location".$whi;
                  					$rs_location = $sp->spSelectLocationWS($database,$row_wh->ID);
                  					
                  					if ($rs_location->num_rows)
                  					{
                  						$loci = 0;
                  						while ($row_loc = $rs_location->fetch_object())
                  						{
                  							$loci += 1;
                  							$rs_product = "rs_prod".$loci;               		                		

				
                  							$rs_product = $sp->spSelectProductListWS($database, 0, $datecreate, 0);
                  							if ($rs_product->num_rows)
											{
												$cntrow = 1;
												
												while ($row = $rs_product->fetch_object())
												{  
													
													$cnt ++;
													($cnt % 2) ? $alt = '' : $alt = 

'bgEFF0EB';
													$pname = $row->Product;
													$pid = $row->ProductID;
													$pcode = $row->ProductCode;	
													
												    
				  					$strTable .= "<tr align='center'>
													<td width='20%' height='10' align='center'>$row_wh->Name</td>
													<td width='20%' height='10' align='center'>$row_loc->Name</td>
													<td width='15%'height='10' align='center'> $cnt</td>
													<td width='15%' height='10'align='center'> $pcode </td>	
													<td width='20%' height='10' align='left'> $pname  </td> 						  
													</tr>"  ;
													}
												$rs_product->close();
											}  
										               							
                  						}              
                  						$rs_location->close();    						
                  					}                  					
                  				}
                  				$rs_warehouse->close();
                  			}
}
else if($prodall == 1)
{
	
if ($rs_warehouse->num_rows)
                  			{
                  				$whi = 0;
                  				$cnt = 0;
                  				while ($row_wh = $rs_warehouse->fetch_object())
                  				{
                  					$wid = $row_wh->ID;
                  					$whi += 1;
                  					$rs_location = "rs_location".$whi;
                  					$rs_location = $sp->spSelectLocationWS($database,$row_wh->ID);
                  					
                  					if ($rs_location->num_rows)
                  					{
                  						$loci = 0;
                  						while ($row_loc = $rs_location->fetch_object())
                  						{
                  							$loci += 1;
                  							$rs_product = "rs_prod".$loci;               		                		

				
                  							$rs_product = $sp->spSelectProductListWS($database, 0, '%%', 0);
                  							if ($rs_product->num_rows)
											{
												$cntrow = 1;
												
												while ($row = $rs_product->fetch_object())
												{  
													
													$cnt ++;
													($cnt % 2) ? $alt = '' : $alt = 

'bgEFF0EB';
													$pname = $row->Product;
													$pid = $row->ProductID;
													$pcode = $row->ProductCode;	
													
												    
				  					$strTable .= "<tr align='center'>
													<td width='20%' height='10' align='center'>$row_wh->Name</td>
													<td width='20%' height='10' align='center'>$row_loc->Name</td>
													<td width='15%'height='10' align='center'> $cnt</td>
													<td width='15%' height='10'align='center'> $pcode </td>	
													<td width='30%' height='20' align='left'> $pname  </td> 						  
													</tr>"  ;
													}
												$rs_product->close();
											}  
										               							
                  						}              
                  						$rs_location->close();    						
                  					}                  					
                  				}
                  				$rs_warehouse->close();
                  			}
}
else
{

	
	$rs_branch = $sp->spGetBranchParameter($database);
	if ($rs_branch->num_rows)
		{
			while ($row_branch = $rs_branch->fetch_object())
		  {
				
				  $branch = $row_branch->name;							
				
				
		  }
		
		}
if (isset($_GET['cntlist']) && $_GET['cntlist'] != "")
	{   
		$tagnumber = 0;
		$cnt = 0;
		$ctr = 0;
		
		ini_set('display_errors',0);
		require CS_PATH.DS.'html2fpdf.php';
		$pdf = new HTML2FPDF();
		$pdf->SetMargins(10,5,0);
		
		
		$cntlist_url = split(',', $_GET['cntlist']);
        $_SESSION['cnt_list'] = $cntlist_url;
                    		
        for ($i = 0, $n = sizeof($_SESSION['cnt_list']); $i < $n; $i++ )
        {   
        	
		     $tagnumber = $_SESSION['cnt_list'][$i];
		     
		   
		     	 $ctr++;
  					
						$loclist_url = split(',', $_GET['loclist']);
				        $_SESSION['loc_list'] = $loclist_url;
			     
			     		$rs_location = $sp->spSelectLocationByID($database, $_SESSION['loc_list'][$i]);
			     		
			     		if ($rs_location->num_rows)
	                  		{
	                  		 while ($row_loc = $rs_location->fetch_object())
	                  		 {
	                  		   $location = $row_loc->Name;
	                  		 }
	                  		 	 					
							 $prodlist_url = split(',', $_GET['prodlist']);
					       	 $_SESSION['prod_list'] = $prodlist_url; 
					       	 
					  
					       	 $rs_product = $sp->spSelectProductbyID($database, $_SESSION['prod_list'][$i],$whid);
					       	 
								 while ($row_prod = $rs_product->fetch_object())
		                  		 {
		                  		   
		                  		   $pname = $row_prod->Name;
		                  		   $pcode = $row_prod->Code;
		                  		   
		                  		  
		                  		     $strTable .= "<html>
													<body>   
													<br>								
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
											
															<table width='100%'>
															<tr align='center'>
																<td height='20' width='20%'><strong>Branch:</strong>&nbsp;&nbsp; $branch</td>																
																<td height='20' width='20%'><strong>Worksheet No:</strong>&nbsp;&nbsp; $txnno</td>
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
																<td height='20' width='20%'><strong>Tag Number:</strong>&nbsp;&nbsp; $tagnumber</td>																	
																<td height='20' width='80%' ><strong>Product Description:</strong>&nbsp;&nbsp; $pname</td>
															
																	
															</tr>
															
															
															</table>
														
															
															<table  width='100%' border='0'>
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
																<td height='20' width='15%'></td>
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
																<td height='20' width='15%'></td>
																<td height='20' align ='left' width='20%'><strong>Date Recounted:</strong></td>
																<td height='20' width='22%'>____________________</td>
															</tr>	
															<tr align='center'>	
																<td height='20' align ='right' width='20%'><strong>Checked By:</strong></td>
																<td height='20' width='25%'>____________________</td>
																<td height='20' width='15%'></td>
																<td height='20' width='20%'></td>
																<td height='20' width='22%'></td>	
															</tr>	
															<tr align='center'>	
																<td height='20' align ='right' width='20%'><strong>Remarks:</strong></td>
																<td height='20' width='25%'>____________________</td>
																<td height='20' width='15%'></td>
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

if($prodall == 3)
{
	$pdf->AddPage();
	$pdf->WriteHTML($strTable); 
	$pdf->SetDisplayMode(real,'default');
	$pdf->Output("CountTagsandWorksheet.pdf", "D");
}
else
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
									<!-- <td height='20' width='10%'><strong>Branch:</strong></td>
								     <td height='20' width='10%' ></td>
								     <td height='20'></td>
									 <td height='20' ></td> -->
								</tr>
							</table>
						</td>
						<td width='100%'>
							<table border='0' width='50%'>				
							 	<tr align='center'>
							 		 <td height='20' width='20%'><strong>Worksheet:</strong></td>
								     <td height='20' width='20%' >$txnno  </td>
								   <!--  <td height='20'><strong>Print Date:</strong></td>
								     <td height='20' ></td>		
								     <td width='20'></td> -->
								</tr>
							</table>
						</td>
					</tr>	
				</table>
						<div >
							<table width='100%'  border='1'  cellpadding='0' cellspacing='1' >				

			
							 <tr align='center'>
							 <td width='20%' align='center'><strong>Per Warehouse</strong></td>
							 <td width='20%' align='center'><strong>Per Location</strong></td>
							 <td width='15%' align='center'><strong>Count Tag No</strong></td>
							 <td width='15%' align='center'><strong>Item Code</strong></td>
							 <td width='30%' align='center'><strong>Item Description</strong></td>
							 </tr>		     				
						
						$strTable
						
					</table>
					</div>
			</body>	
		</html>
			";
						
	ini_set('display_errors',0);
	require CS_PATH.DS.'html2fpdf.php';

	
	$pdf = new HTML2FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Courier','B',12);
	$pdf->SetDisplayMode(real,'default'); 
	$pdf->WriteHTML($html);
	
	$pdf->Output("CountTagsandWorksheet.pdf", "D");	
						
}					


?>