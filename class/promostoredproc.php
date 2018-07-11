<?php

class PromoStoredProcedures {

	public function spSelectApplicablePromos() 
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
		$sp = "Call spPromoSelectApplicablePromos('" . session_id() . "')";
		//echo $sp; exit;	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	
	public function spSelectBuyinByPromoID($promoid) 
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
		$sp = "Call spPromoSelectBuyinByPromoID(" . $promoid . ")";
		//echo $sp; exit;	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}	
	
	public function spSelectPromoBuyinByPromoID($promoid) 
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
		$sp = "Call spPromoSelectPromoBuyinByPromoID(" . $promoid . ")";
		//echo $sp; exit;	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	
	public function spPromoSelectPromoEntitlementByPromoBuyinID($promoBuyinID) 
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		
		$sp = "Call spPromoSelectPromoEntitlementByPromoBuyinID(" . $promoBuyinID . ")";
		//echo $sp; exit;	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}
	
	public function spPromoUpdateProductList($productID, $numAvailments, $effectivePrice, $promoID, $availmentType, $pmgID) 
	{
		$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		if (empty($pmgID))
		{
			$pmgID = "NULL";
		}
		
		$sp = "Call spPromoUpdateProductList($productID,'" . session_id() . "',$numAvailments,$effectivePrice,$promoID,$availmentType,$pmgID)";
		//echo $sp; exit;	
		$rs = $mysqli->query($sp);
		$mysqli->close();
		return $rs;	
	}	
}


?>