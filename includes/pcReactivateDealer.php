<?php
global $database;

	if(isset($_POST['btnUpdate']))
	{
		try
		{
			$database->beginTransaction();
			$id=0;
			if(isset($_GET['custid']))
			{
				$id = $_GET['custid'];			
			}		
                        
                        /* 
                         * @author: jdymosco
                         * @date: Feb 08, 2013
                         * Determine first what would be the new status depending on the date four months backwards. 
                         */
                        $DateFourMonthsPast = date("Y-m-d",strtotime("-4 Months"));
                        $date_terminated = $_POST['date_terminated'];
                        
                        if($date_terminated < $DateFourMonthsPast){
                            $newStatus = 4; //Status Appointed...
                        }elseif($date_terminated > $DateFourMonthsPast){
                            $newStatus = 3; //Status Applicant
                        }//end of added
			
			$affected_rows = $sp->spUpdateDealerStatus($database,$id, $newStatus);
                        
                        /*
                         * @author: jdymosco
                         * @date: March 12, 2013
                         * @update: We check first if has new IBM No. before transferring in a new network.
                         */
                        if($_POST['cboIBMNetwork']){
                            $update = $sp->spTransferDealer($database, $id, $_POST['cboIBMNetwork'], $session->emp_id);
                            if (!$update)
                            {
                                    throw new Exception("An error occurred, please contact your system administrator.");
                            } 
                        }
				
			$database->commitTransaction();
			$msg = "Transaction successful.";
			redirect_to("index.php?pageid=75&msg=$msg");
	
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
		}
	}
	if(isset($_POST['btnCancel']))	
	{
		$msg = "Transaction cancelled.";
		redirect_to("index.php?pageid=75&msg=$msg");  
	}
?>