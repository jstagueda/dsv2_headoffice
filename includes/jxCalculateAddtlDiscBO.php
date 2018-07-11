<?php
require_once "../initialize.php";
global $database;
$totalCFT  = str_replace(",","",$_POST['totCFT']); 
$totalNCFT = str_replace(",","",$_POST['totNCFT']); 
$custID = $_POST['hcustID'];
$discountCFT = 0;
$discountNCFT = 0;
$addtldisc = 0;
$tmpaddtldisc = 0;
$disc  =0;
$month = date("m"); 
$year  = date("Y");
$MTDCFT = 0;
$MTDNCFT = 0;
$MTDCFTDisc = 0;
$MTDNCFTDisc = 0;
$tmptotalCFT = 0;
$tmptotalNCFT = 0;
$ADPPNCFT	=	0;
$ADPPCFT 	=	0;	
$amountToNextDiscCFT = 0;
$amountToNextDiscNCFT =0;
	//Additional Discount
	$rsDiscountCFT = $sp->spSelectDiscountBracket($database);
	$rsMTDCFT = $sp->spSelectMTDbyCustomerID($database,$custID,$month,$year,1);
				
	if($rsMTDCFT->num_rows)
	{
		while($tmpMTDCFT = $rsMTDCFT->fetch_object())
		{
			$MTDCFT		 = $tmpMTDCFT->Amount;
			$MTDCFTDisc	 = $tmpMTDCFT->DiscountID;
			//echo $MTDCFT;
		}
	}
	
	$rsMTDNCFT = $sp->spSelectMTDbyCustomerID($database,$custID,$month,$year,2);				
	if($rsMTDNCFT->num_rows)
	{
		while($tmpMTDNCFT = $rsMTDNCFT->fetch_object())
		{
			$MTDNCFT 	= $tmpMTDNCFT->Amount;
			$MTDNCTDisc = $tmpMTDNCFT->DiscountID;
			//echo $MTDNCFT;
		}
	}
	if($rsDiscountCFT->num_rows)
	{
		while($CFTbracket = $rsDiscountCFT->fetch_object())
		{
			$minimum = $CFTbracket->Minimum ;
			$maximum = $CFTbracket->Maximum ;	
			$PMGID	 = 	$CFTbracket->PMGID ;		
			if($CFTbracket->PMGID == 1)
			{
				$tmptotalCFT  = $totalCFT + $MTDCFT ;
				
				if($tmptotalCFT >= $minimum && $tmptotalCFT <= $maximum  && $PMGID == 1 )
				{
					
					//echo $tmptotalCFT ;
					$disc = $CFTbracket->Discount;	
					$discountCFT = $totalCFT * ($disc/100);
					
				}								
			}
			else
			{
			
				$tmptotalNCFT = $totalNCFT + $MTDNCFT ;
				
				if($tmptotalNCFT >= $minimum && $tmptotalNCFT <=  $maximum  && $PMGID == 2 )
				{
					
					$disc = $CFTbracket->Discount;	
					$discountNCFT =  $totalNCFT * ($disc/100);
					
				}
			}
		}		
		$tmpaddtldisc = $discountCFT + $discountNCFT;
		$addtldisc  =  number_format($tmpaddtldisc,2,'.','');	
		
		
	}
	
	//ADPP	
	$rsDiscountADPP = $sp->spSelectDiscountBracket($database);
	if($rsDiscountADPP->num_rows)
	{
		while($ADPPDiscountBracket = $rsDiscountADPP->fetch_object())
		{
			$minimum = $ADPPDiscountBracket->Minimum ;
			$maximum = $ADPPDiscountBracket->Maximum ;	
			$PMGID	 = 	$ADPPDiscountBracket->PMGID ;	
			if($ADPPDiscountBracket->PMGID == 1)
			{
				$tmptotalCFT  = $totalCFT + $MTDCFT ;
				//echo $tmptotalCFT ;
				if($tmptotalCFT >= $minimum && $tmptotalCFT <=  $maximum && $PMGID == 1 )
				{
					//echo $tmptotalCFT ;
					$discID = $ADPPDiscountBracket->ID;	
	 				if($MTDCFTDisc != $discID)
					{
						$disc = $ADPPDiscountBracket->Discount;	
						$rsDiscountMTD =  $sp->spSelectDiscountBracketbyID($database,$MTDCFTDisc);
						if($rsDiscountMTD->num_rows)
						{
							while($MTDDisc = $rsDiscountMTD->fetch_object())
							{
								$tmpDiscMTD = 	$MTDDisc->Discount;	
								$DiscMTD 	= 	$disc  - $tmpDiscMTD ;
								$ADPPCFT 	=   ($MTDCFT/1.12) * ($DiscMTD/100);
								//echo $ADPPCFT;
							}
						}
						
					}
					
				}								
				
			}
			else
			{
				$tmptotalNCFT = $totalNCFT + $MTDNCFT ;				
				if($tmptotalNCFT >= $minimum && $tmptotalNCFT <=  $maximum && $PMGID == 2  )
				{
					
					$discID = $ADPPDiscountBracket->ID;	
					if($MTDNCFTDisc != $discID)
					{
						$disc = $ADPPDiscountBracket->Discount;	
						$rsDiscountMTD =  $sp->spSelectDiscountBracketbyID($database,$MTDNCFTDisc);
						if($rsDiscountMTD->num_rows)
						{
							while($MTDDisc = $rsDiscountMTD->fetch_object())
							{
								$tmpDiscMTD = $MTDDisc->Discount;	
								$DiscMTD 	=  $disc - $tmpDiscMTD ;
								$ADPPNCFT 	=   ($MTDNCFT/1.12) * ($DiscMTD/100);
							}
						}
						
					}
					
				}
			}	
			
		}
		$tmpADPPDisc = $ADPPCFT  + $ADPPNCFT;
		$ADPP  =  number_format($tmpADPPDisc,2,'.','');	
		
		
		//amount to next discount
		$rsGetDiscounts = $sp->spSelectDiscountBracket($database);		
		if($rsGetDiscounts->num_rows)
		{
			while($DiscountBrackets = $rsGetDiscounts->fetch_object())
			{
				$minimum = $DiscountBrackets->Minimum ;
				$maximum = $DiscountBrackets->Maximum ;	
				$PMGID	 = 	$DiscountBrackets->PMGID ;
				if($DiscountBrackets->PMGID == 1)
				{
					$tmptotalCFT  = $totalCFT + $MTDCFT ;
					
					if($tmptotalCFT >= $minimum && $tmptotalCFT <=  $maximum  && $PMGID == 1 )
					{
						$discID = $DiscountBrackets->Discount;	
						//echo $discID;
						$rsNextDiscount	= $sp->spSelectNextDiscBracket($database,$discID,1);
						if($rsNextDiscount->num_rows)
						{
							while($nextDisc = $rsNextDiscount->fetch_object())
							{
								$tmpNextMinimum = $nextDisc->Minimum ;
								$amountToNextDiscCFT  = $tmpNextMinimum  - $tmptotalCFT;
								
							}
						}
						
					}
				
				}
				else
				{
					$tmptotalNCFT = $totalNCFT + $MTDNCFT ;				
					if($tmptotalNCFT >= $minimum && $tmptotalNCFT <=  $maximum  && $PMGID == 2 )
					{
						$discID = $DiscountBrackets->Discount;	
						$rsNextDiscount	= $sp->spSelectNextDiscBracket($database,$discID,2);
						if($rsNextDiscount->num_rows)
						{
							while($nextDisc = $rsNextDiscount->fetch_object())
							{
								$tmpNextMinimum = $nextDisc->Minimum ;
								$amountToNextDiscNCFT  = $tmpNextMinimum  - $tmptotalNCFT;
								
							}
						}
						
					}
				}				
			}
		}
		
		echo $addtldisc.'_'.$ADPP.'_'.$amountToNextDiscCFT.'_'.$amountToNextDiscNCFT   ;	
			
		
	}
	

?>
