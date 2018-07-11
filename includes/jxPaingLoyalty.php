<?php
/*
	Author: @Gino C. Leabres
	Date: 1/25/2013 8:15am
*/


/*Buy In Requirement Table*/
	$create = " SELECT PromoCode, PromoTitle, StartDate, EndDate, DateRange  FROM tpiloyaltyheader ";
	$buyingcreate = $database->execute($create);	
							if($buyingcreate->num_rows){

								echo "				
									<tr align='center' class='txtdarkgreenbold10 tab'>
										<td width='4%' height='20' class='txtpallete bdiv_r'><div align='left' class='padl5'>Promo Code</div></td>
										<td width='15%' height='20' class='txtpallete bdiv_r'><div align='left' class='padl5'>Promo Title</div></td>			
										<td width='10%' height='20' class='txtpallete bdiv_r'><div align='center'>Start Date</div></td>
										<td width='10%' height='20' class='txtpallete bdiv_r'><div align='center'>End Date</div></td>
										<td width='10%' height='20'><div align='center' class='txtpallete padr5'>Date Range</div></td>
									</tr>";
								
								while($row = $buyingcreate->fetch_object()){
									echo '								
									
									<tr align="center">
											<td width="5%"  height="25" class="borderBR"><div align="center">'.$row->PromoCode.'</div></td>
											<td width="32%" height="20" class=" borderBR"><div align="left" class="padl5">'.$row->PromoTitle.'</div></td>
											<td width="10%" height="20" class=" borderBR"><div align="center">'.date("m/d/Y",strtotime($row->StartDate)).'</div></td>
											<td width="10%" height="20" class=" borderBR"><div align="center" class="padr5">'.date("m/d/Y",strtotime($row->EndDate)).'</div></td>
											<td width="12%" height="20" class=" borderBR"><div align="center">'.date("m/d/Y",strtotime($row->DateRange)).'</div></td>
									 </tr>';
								}	
							}
?>