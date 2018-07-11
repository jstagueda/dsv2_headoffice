<?php
/*
	Author: @Gino C. Leabres
	Date: 1/25/2013 8:15am
*/
// require_once("../initialize.php");
// global $database;

// /*Buy In Requirement Table*/
	// $buyintable = " SELECT tpl.*, p.*, c.Name CriteriaName, c.ID cID FROM tpiloyaltybuyinrequirementtmp tpl
					// INNER JOIN product p on tpl.ProductID = p.ID
					// INNER JOIN criteria c on tpl.CriteriaID = c.ID";
	// $buyinrequirementtble = $database->execute($buyintable);
					// echo '<tr align="center">
									// <td width="5%" height="25" class="borderBR"><div align="center"><input name="chkAll"  type="checkbox" id="chkAll" value="1"  onclick="checkAll(this.checked);"></div></td>
									// <td width="10%" height="25" class="txtpallete borderBR"><div align="center">Line No.</div></td>									
									// <td width="27%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Description</div></td>
									// <td width="10%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>			
									// <td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
									// <td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Points</div></td>

							// </tr>';
						
							// if($buyinrequirementtble->num_rows){
								// $counter = 1;
								// while($row = $buyinrequirementtble->fetch_object()){
									// $Mininum = "";
									// if($row->cID == 1){
										// $Mininum = '<td width="10%" height="20" class=" borderBR"><div align="right" class="padr5">'.number_format($row->MinVal,0,".", "").'</div></td>';
											// }else{
										// $Mininum = '<td width="10%" height="20" class=" borderBR"><div align="right" class="padr5">'.$row->MinVal.'</div></td>';
											// }
									// echo '
									// <tr align="center">
											// <td width="5%"  height="25" class="borderBR"><div align="center"><input name="chkAll"  type="checkbox" id="chkAll" value="'.$row->ID.'"></div></td>
											// <td width="10%" height="25" class=" borderBR"><div align="center">'.$counter.'</div></td>									
											// <td width="32%" height="20" class=" borderBR"><div align="left" class="padl5">'.$row->Name.'</div></td>
											// <td width="10%" height="20" class=" borderBR"><div align="center">'.$row->CriteriaName.'</div></td>
											// '.$Mininum.'
											// <td width="10%" height="20" class=" borderBR"><div align="right" class="padr5">'.$row->Points.'</div></td>
									 // </tr>';
								// $counter++;
								// }	
							// }
?>