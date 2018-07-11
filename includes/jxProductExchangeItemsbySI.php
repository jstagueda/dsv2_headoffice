<?php
	require_once "../initialize.php";
	global $database;
	$sessionID= session_id();	
	$txnID = $_GET['SIID'];
	
	$getSIDetails = $sp->spSelectSalesInvoiceDetailsByID($database,$txnID);
	if($getSIDetails->num_rows)
	{
		
		$i =1;
		/**********************************
		**  Modified by: Gino C. Leabres***
		**  10.09.2012*********************
		**  ginophp@yahoo.com**************
		***********************************/
		/*##############################################################################################################################################*/	
		echo '
			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bordergreen">
					<tr align="center" class = "tab">
						<th width="5%" height="20" class="bdiv_r">&nbsp; &nbsp;<input type="checkbox" name="chkAll" id="chkAll" onclick="return enableAll();"></th>
						<th width="10%" height="20" class="bdiv_r">Product Code</th>   
						<th width="20%" height="20" class="bdiv_r">Product Name</th>
						<th width="5%" height="20" class="bdiv_r">UOM</th>
						<th width="7%" height="20" class="bdiv_r">PMG</th>
						<th width="10%" height="20" class="bdiv_r">Promo</td>
						<th width="5%" height="20" class="bdiv_r">Invoice Qty</th>  			
						<th width="5%" height="20" class="bdiv_r">Price</th>
						<th width="8%" height="20" class="bdiv_r">Net Amount</th>
						<th width="5%" height="20" class="bdiv_r">Qty</thd>
						<th width="13%" height="20" >Reason</th>
					</tr>';
		/*##############################################################################################################################################*/
		
		echo'<input type="hidden" name="hcnt" id="hcnt" value="'.$getSIDetails->num_rows.'">';
		
		while($row = $getSIDetails->fetch_object()):
		
				//echo '<table width="100%"  cellpadding="0" cellspacing="1" class="bgFFFFFF" id="dynamicTable" border="0">';
				echo'
					<tr class="bgFFFFFF">
						<td width="5%"height="20" class="borderBR padl5" align="center">
							<input type="checkbox" name="chkID'.$i.'" value="'.$row->ProductID.'" id = "chkID'.$i.'" onclick="return enablefields('.$i.');">
							<input type="hidden" name="hProductID'.$i.'" id="hProductID'.$i.'" value="'.$row->ProductID.'"
						</td>
						<td width="10%" height="20" class="borderBR padl5">'.$row->ProductCode.'</td>
						<td width="20%" height="20" class="borderBR padl5">&nbsp; &nbsp; &nbsp;'.$row->ProductName.'</td>
						<td width="5%" height="20" class="borderBR padl5">'.$row->UOM.'</td>
						<td width="7%" height="20" class="borderBR padl5">'.$row->PMG.'</td>					
						<td width="10%" height="20" class="borderBR padl5">'.$row->Promo.'</td>
						<td width="5%" height="20" class="borderBR padl5">'.$row->ServedQty.'</td>
						<td width="5%" height="20" class="borderBR padl5">'.number_format($row->UnitPrice,2).'</td>
						<td width="8%" height="20" class="borderBR padl5">'.number_format($row->TotalAmount,2).'</td>
						<td width="5%" height="20" class="borderBR padl5">
							<input type = "text" name="txtQty'.$i.'" style="width:160px" id="txtQty'.$i.'" class="txtfield" onkeyup = "qtyvalidation('.$i.');"  disabled>
							<input type = "hidden" value = '.$row->ServedQty.' id = "Qty'.$i.'" name = "qty'.$i.'">
						</td>
						<td width="13%" height="20" class="borderB padl5" >
								<select name="cboReason'.$i.'" id="cboReason'.$i.'" style="width:160px" class="txtfield" disabled>             		
									<option value="0" id = "cboReason'.$i.'">[SELECT HERE]</option>';
										$rscboReason = $sp->spSelectReasonforProductExchange($database,1);
										if ($rscboReason->num_rows){
											while ($row = $rscboReason->fetch_object())
											{									
												echo "<option value='$row->ID' $sel>$row->Name</option>";
											}
										}
							echo '</select>	  					
						</td>
					</tr>				
				';
				//</table>
				$i +=1;
				
		endwhile;
	echo '</table>';
	}
?>