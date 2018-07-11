<?php
	class StockLog
	{
		private $database;
		
		function __construct($database)
		{
			$this->database=$database;
		}
		
		public function AddToStockLog($warehouseid, //Warehouse ID
									  $inventoryid, // Inventory ID
									  $classid,  // Class ID
									  $cbathno, 
									  $pbatchno, 
									  $plotno, 
									  $productid, //Product ID
									  $ucost, 	  // Unit Cost
									  $refid, 	  // Transaction ID
									  $refno, 	  // Reference ID or Document Number
									  $explanation, //Remarks
									  $txntype,     //Transaction Type
									  $invqty, 		// Inventory In or Out
									  $datecreated	// Trnsaction Date
									  ) 
		{
			
			$tmpdate = strtotime($datecreated);
			$datecreate = date("Y-m-d", $tmpdate);
		
			$qtyin = 0;
			$qtyout = 0;
			$totinvqty = 0;
			
			//check class value
			if ($classid == 0)
			{
				$classid = 1;
			}
			
			//validate txn type
			switch ($txntype)
			{
				case 1: //rho
						$qtyin = $invqty;
						$qtyout = 0;
						$totinvqty = $invqty;
						break;
				case 2: //itr
						$qtyin = 0;
						$qtyout = $invqty;
						$totinvqty = $invqty;
						break;
				case 3: //rtr
						$qtyin = $invqty;
						$qtyout = 0;
						$totinvqty = $invqty;
						break;
				case 4: //irv
						$qtyin = 0;
						$qtyout = $invqty;
						$totinvqty = $invqty;
						break;
				case 5: //cyc
						if ($invqty > 0)
						{
							$qtyin = $invqty;
							$qtyout = 0;
							$totinvqty = $invqty;
						}
						else
						{
							$qtyin = 0;
							$qtyout = -($invqty);
							$totinvqty = -($invqty);
						}
						break;
				case 6: //adj
						//$totinvqty = $invqty;
						if ($invqty > 0){
							$qtyin = $invqty;
							$qtyout = 0;
						}
						else{
							$qtyin = 0;
							//$qtyout = -($invqty);
							$qtyout = abs($invqty);
						}
						$totinvqty = abs($invqty);
						/*
							$qtyin = $invqty;
							$qtyout = 0;
							$totinvqty = $invqty;
						*/
						break;
				case 7: //rdg (to)
						$qtyin = 0;
						$qtyout = $invqty;
						$totinvqty = $invqty;
						break;
				case 8: //idg (to)
						$qtyin = 0;
						$qtyout = $invqty;
						$totinvqty = $invqty;
						break;
				case 9: //pws
						$qtyin = $invqty;
						$qtyout = 0;
						$totinvqty = $invqty;
						break;
				case 10: //des
						$qtyin = 0;
						//$qtyin = $invqty;
						//$qtyout = 0;
						$qtyout = $invqty;
						$totinvqty = $invqty;
						break;
				case 11: //adj
						$qtyin = $invqty;
						$qtyout = 0;
						$totinvqty = $invqty;
						break;
				case 12: //des
						$qtyin = 0;
						//$qtyin = $invqty;
						//$qtyout = 0;
						$qtyout = $invqty;
						$totinvqty = $invqty;
						break;
				case 13: //transfer from source warehouse
						$qtyin = 0;
						$qtyout = $invqty;
						$totinvqty = $invqty;
						break;						
            	case 14: 
                  		$qtyin = $invqty;
                  		$qtyout = 0;
                  		$totinvqty = $invqty;
                  		break;
            	case 15: 
                  		$qtyin = $invqty;
						$qtyout = 0;
						$totinvqty = $invqty;
						break;	
				case 16: 
                  		$qtyin = $invqty;
						$qtyout = 0;
						$totinvqty = $invqty;
						break;	
				
				case 18:
						$qtyin = $invqty;
						$qtyout = 0;
						$totinvqty = $invqty;
						break;
				case 19: 
						$qtyin = 0;
						$qtyout = $invqty;
						$totinvqty = $invqty;
						break;
			}
			
			//check movement type
			if ($qtyin == 0 && $qtyout != 0)
			{
				$intxn = 0;
			}
			else if ($qtyin != 0 && $qtyout == 0)
			{
				$intxn = 1;
			}
			else
			{
				$intxn = 0;
			}

			//check if inventory exists
			$sp = "Call spSelectInventory($warehouseid, $inventoryid, $productid)";
			$rs_invcheck = $this->database->execute($sp);

			if ($rs_invcheck->num_rows)
			{
				$invexist = 1;
				while ($row = $rs_invcheck->fetch_object())
				{
				
					$inventoryid = $row->ID; 	
				}
				$rs_invcheck->close();				
			}
			else
			{
				$invexist = 0;
			}
			
			//check if inventory balance exists
			/*	
			$sp = "Call spSelectInventoryBalance($productid, $warehouseid)";
			$rs_invbalcheck = $this->database->execute($sp);

			if ($rs_invbalcheck->num_rows)
			{
				$invbalexist = 1;				
			}
			else
			{
				$invbalexist = 0;
				
			}*/

			//insert to inventory - INS
			if ($invexist == 0)
			{				
				$tmpucost = number_format($ucost, 0);
				$sp = "Call spInsertInventory($warehouseid, $productid, '$cbathno', '$plotno', $totinvqty)";
				$rs_createinv = $this->database->execute($sp);

				if ($rs_createinv->num_rows)
				{
					while ($row = $rs_createinv->fetch_object())
					{
					
						$new_invid = $row->newid; 	
					}
					$rs_createinv->close();
				}
			}
			//insert to inventory - OUT
			else
			{
				if ($intxn == 1) //inventory add
				{
					$sp = "Call spUpdateInventory(1, $warehouseid, $inventoryid, $productid,  $totinvqty)";
					$rs_updateinv = $this->database->execute($sp);
				}
				else //inventory subtract
				{
					$sp = "Call spUpdateInventory(0, $warehouseid, $inventoryid, $productid,  $totinvqty)";
					$rs_updateinv = $this->database->execute($sp);
				}
				$new_invid = $inventoryid;
			}

			//insert to inventorybalance
			/*if ($invbalexist == 0)
			{				
				$tmpucost = number_format($ucost, 0);
				$sp = "Call spInsertInventoryBalance($warehouseid, $classid, $productid, $totinvqty, $tmpucost)";
				$rs_createinvbal = $this->database->execute($sp);
			}
			//update inventorybalance
			else
			{
				if ($intxn == 1) //inventory add
				{
					$tmpucost = number_format($ucost, 0);
					$sp = "Call spUpdateInventoryBalance(1, $warehouseid, $productid,  $totinvqty, $tmpucost)";		
					$rs_updateinv = $this->database->execute($sp);
				}
				else //inventory subtract
				{
					$tmpucost = number_format($ucost, 0);
					$sp = "Call spUpdateInventoryBalance(0, $warehouseid, $productid,  $totinvqty, $tmpucost)";		
					$rs_updateinv = $this->database->execute($sp);
				}
			}*/
			
			//insert to stocklog
			$sp = "Call spInsertInitialStockLog($productid, $new_invid, '$datecreate', $refid, '$refno', $txntype, $qtyin, $qtyout)";
			$rs_createstocklog = $this->database->execute($sp);
		}
	}
	global $database;
	$stocklog = new StockLog($database);
