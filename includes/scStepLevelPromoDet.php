<?php
	global $database;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$prmSID = 0;
	$prmBuyINID = 0;
	$entType = 0;
	$entQty = 0;
	$prmSID = $_GET['prmsid'];
		
	$rs_promoSingleDetails = $sp->spSelectSinglePromoDet($database, $prmSID, 4);
	$database->clearStoredResults();
	$rs_promoAvailment = $sp->spSelectPromoAvailByPromoID($database, $prmSID);
	$database->clearStoredResults();
	$rs_gsutype = $sp->spSelectGSUType($database);
		
	if ($rs_promoSingleDetails->num_rows)
	{
		while ($row = $rs_promoSingleDetails->fetch_object())
		{	
			$prmid = $row->prmID;		
			$prmcode = $row->prmCode;
			$prmdesc = $row->prmDesc;
			$prmsdate = $row->prmSDate;
			$prmedate = $row->prmEDate;
			$prmPplan = $row->prmPPlan;			
		}
	}
	
	if(isset($_GET['prmsid']))
	{
		//get promo header
		$rspromodet = $sp->spSelectPromoByID($database, $_GET['prmsid']);
		if($rspromodet->num_rows)
		{
			while($row = $rspromodet->fetch_object())
			{
				$promoID = $row->ID;
				$promocode = $row->Code;
				$promodesc = $row->Description;
				$tmpsdate = strtotime($row->StartDate);
				$today = date("m/d/Y",$tmpsdate);
				$tmpedate = strtotime($row->EndDate);
				$end = date("m/d/Y",$tmpedate);
			}
			$rspromodet->close();
		}
		
		//get promobuyin
		$rspromobuyin_count = $sp->spSelectPromoBuyInCountByPromoID($database, $_GET['prmsid']);
		if($rspromobuyin_count->num_rows)
		{
			while($row = $rspromobuyin_count->fetch_object())
			{
				$tmpcnt = $row->Cnt;
				$prtype = $row->Type;	
				$prmBuyINID = $row->ID;		
			}
			$rspromobuyin_count->close();
		}
		
		$rspromentitlementType = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $prmBuyINID);
		if($rspromentitlementType->num_rows)
		{
			while($row = $rspromentitlementType->fetch_object())
			{
				$entType = $row->Type;
				$entQty = $row->Qty;	
					
			}
			$rspromentitlementType->close();
		}
		
		$rspromobuyin = $sp->spSelectPromoBuyInByPromoID($database, $_GET['prmsid']);
		$rspromobuyin_ent = $sp->spSelectPromoBuyInByPromoID($database, $_GET['prmsid']);
	}
	if (isset($_POST['btnDelete']))
	{
		try
		{
			$prmID = $_POST['hID'];	
			$linked = $sp->spSelectLinkedBrochureProductByPromoID($database, $prmSID);
			
			if($linked->num_rows)
			{
				echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=64&errmsg=Promo cannot be deleted because it is already linked to a Brochure or it has started.';
						window.close();
					</script>";					
			}
			else
			{
				$database->beginTransaction();
				$affected_rows = $sp->spDeletePromo($database, $prmID);
				
				if (!$affected_rows)
				{	
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$database->commitTransaction();
				echo"<script language=JavaScript>
							opener.location.href = '../../index.php?pageid=64&msg=Successfully deleted promo.';
							window.close();
						</script>";
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			
		}
	}
?>