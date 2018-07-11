<?php 
    include('../initialize.php');
    include('SFEarningsRecompute.php');
    include('../class/HO.php');
	$SFEarningsRecompute = new SFEarningRecompute();
	$stop = false;
	
	$GetAllBranches = $database->execute("SELECT ID BranchID FROM branch WHERE ID > 3");
		if($GetAllBranches->num_rows == 0){
			
			$result['type']='error';
			$result['message']='Please check then branch table..';
			$stop = true;
			
		}
	
		if(!$stop){
			try
			{
				
				//Start Transaction here...
					$database->beginTransaction();
					while($br = $GetAllBranches->fetch_object()){
						$BranchID = $br->BranchID;
						$GetIBMs = _GETIBMByBranch($database,$BranchID);
						if($GetIBMs->num_rows > 0){
							while($IBM=$GetIBMs->fetch_object()){
								$IBMID = $IBM->IBMID;
								$GetCampaignCodeByIBMQ = GetCampaignCodeByIBMAndBranch($database,$BranchID,$IBMID);
								
								if($GetCampaignCodeByIBMQ > 0){
									while($campaign = $GetCampaignCodeByIBMQ->fetch_object()){
										
										$CampaignCode	= $campaign->CampaignCode;
										$CampaignMonth 	= $campaign->CampaignMonth;
										$CampaignYear	= $campaign->CampaignYear;
									
										// $doSFEarningsRecompute = $SFEarningsRecompute->doSFEarningsRecompute(2,'MAY13',5,13,40);
										$doSFEarningsRecompute = $SFEarningsRecompute->doSFEarningsRecompute($IBMID,$CampaignCode,$CampaignMonth,$CampaignYear,$BranchID);
										if($doSFEarningsRecompute == "success"){
											$result['type']='success';
											$result['message']='SF Earnings Consolidation Successful..';
											//Commit Transaction here..
											//$database->commitTransaction();
										}else{
											$result['type']='failed';
											$result['message']= $doSFEarningsRecompute;
											//$database->rollbackTransaction();
										}
									}
								}
							}
						}
						$result['sf'] = 'success';
						$result['message'] = "service fee computation successful";
						// do rf earnings recomputation..
						$doRFEarningsByIBM = $SFEarningsRecompute->DoRFEarningsRecompute($BranchID);
						$result['rf'] = 'success';
						$result['message'] = "Referral computation successful";
						
						
						$doRFEarningsByIBM = $SFEarningsRecompute->DoCandidacyEarningsRecompute($BranchID);
						$result['rf'] = 'success';
						$result['message'] = "Candidacy computation successful";
					}
					//Commit Transaction here..
					$database->commitTransaction();
			}
			catch(Exception $e)
			{
				print_r($e);
				$result['type'] = 'error';
				$result['message'] = $e;
				$database->rollbackTransaction();
			}
		}
		
		//isa routine..
			$xdate = date("Y-m-d");
			$xyear = date("Y");
			//$ho->consolidateISATransactionByDate($xdate);	
			//$ho->consolidateISATransactionByCampaignYear($xyear, $xyear);
		//end isa routine here...
		
		
		
		die(json_encode($result));
		
		
function _GETIBMByBranch($database,$BranchID)
{
	
	$result = $database->execute("SELECT  SPLIT_STR(HOGeneralID,'-',2) BranchID, ID IBMID FROM customer WHERE CustomerTypeID <> 1 AND SPLIT_STR(HOGeneralID,'-',2) = ".$BranchID."
								  and CustomerTypeID = ( SELECT ID from customertype where Code = 'IBMF' ) GROUP BY ID");							  
	
	return $result;
}
	
function  GetCampaignCodeByIBMAndBranch($database,$BranchID,$IBMID)
{
	$result = $database->execute("SELECT CampaignCode,CampaignMonth,CampaignYear FROM tpi_sfasummary WHERE SPLIT_STR(HOGeneralID,'-',2)=".$BranchID." 
											AND IBMID = ".$IBMID." AND CustomerID = ".$IBMID." AND LevelID = (SELECT ID from customertype where Code = 'IBMF')");
											

	return $result;
}


?>