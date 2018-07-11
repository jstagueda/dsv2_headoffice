<?PHP 
	require_once "initialize.php";
	
	// If it's going to need the database, then it's 
	// probably smart to require it before we start.
	require_once(CS_PATH.DS.'dbconnection.php');
	global $database;
	
   
	echo "session_id = " . session_id() . "<br/><br/>";
   
	// echo "select distinct true promomatch<br/>from productlist<br/>where PHPSession='" . session_id() . "'<br/>and " . Promo::CreateBuyinRequirementCheckQuery(2);
	$promo = new Promo();
	$promo->CalculateBestPrice($database);
	
//	global $database;
	
//	try 
//	{
//		$database->beginTransaction();
//	$database->clearStoredResults();
//	
//		$rs = spSelectPromoBuyinByPromoID(104, $database);
//		
//		$rs2 = spPromoSelectPromoEntitlementByPromoBuyinID($row->ID, $database);
//
//		while ($row2 = $rs2->fetch_object())
//		{
//			echo "PromoBuyinID = $row->ID <br/>\n";
//			
//			$database->clearStoredResults();
//			
//			$rs2 = spPromoSelectPromoEntitlementByPromoBuyinID($row->ID, $database);
//	
//			while ($row2 = $rs2->fetch_object())
//			{
//				echo "PromoEntitlementID = $row2->ID <br/>\n";
//			}
//		}
//		$database->commitTransaction();
//	}
//	catch (Exception $e)  
//	{
//		$database->rollbackTransaction();
//	}
//	
//	function spSelectPromoBuyinByPromoID($promoid,$database) 
//	{
//		$sp = "Call spPromoSelectPromoBuyinByPromoID(" . $promoid . ")";
//		$rs = $database->execute($sp);
//		return $rs;	
//	}
//	
//	function spPromoSelectPromoEntitlementByPromoBuyinID($promoBuyinID,$database) 
//	{
//		$sp = "Call spPromoSelectPromoEntitlementByPromoBuyinID(" . $promoBuyinID . ")";
//		$rs = $database->execute($sp);
//		return $rs;	
//	}
?>