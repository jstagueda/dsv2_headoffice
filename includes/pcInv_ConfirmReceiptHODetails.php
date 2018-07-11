<?php 

global $database;
if(isset($_POST['btnSave']))
{	
	
	$rHoDetCnt=htmlentities(addslashes($_POST['cntt']));
	$invid = htmlentities(addslashes($_POST['hdnInventoryID']));
	
	
	for ( $counter = 1; $counter <= $rHoDetCnt; $counter ++) 
	{
		$prevSOH  = 0;
		 $actqty = $_POST['txtActQuantity'.$counter];
		 $descr = $_POST['txtdescr'.$counter];
		 $lqty = $_POST['hdnQuantity'.$counter];
		 $pid = $_POST['hdnPID'.$counter];	
		 $invID = $_POST['hdnInvID'.$counter];
		  echo $invID;
		  
		$rsGetSOH  = $sp->spSelectSOHbyInvID($database, $invID);
		
		if ($rsGetSOH->num_rows)
		{
			while ($row = $rsGetSOH->fetch_object())
			{
				$prevSOH = $row->SOH;
			}
		}		
		
		echo $prevSOH;	
		 
	
	}	 
	 
		 
	 
}
	
		
		
		
		
	


















?>