<?php
require_once('initialize.php');
global $database;

echo '<H3> Please wait.......';

$tmpcustq = $database->execute("select * from tmpcustnatidMAY10 ");

if($tmpcustq->num_rows > 0 )
{
	while($r = $tmpcustq->fetch_object() )
	{
		#look for customer record - if not exist then create customer record and corresponding related tables.
		$HOGeneralID  = $r->HOGeneralID;
		$DSV1_IGSCode = $r->dsv1code;
		$Branch       = $r->Branch;
		$accounttype  = '0';
		
		if($r->AccountType == 'AFF')
		{
			$accounttype = '1';
		}
		#get branch ID
		$brq = $database->execute("select * from branch where branch.code = '$Branch' ");
		if(!$brq->num_rows > 0 )
		{
			$BranchID = 0;
			echo $Branch.'<br>';
		}
		else
		{
			while($b = $brq->fetch_object() )
			{
				$BranchID = $b->ID;
			}
		}
		
		
		$custvalq = $database->execute("SELECT * 
										FROM customer c
										WHERE c.HOGeneralID = '$HOGeneralID'
										AND c.BranchID = '$BranchID'
										AND c.DSV1_IGSCode = '$DSV1_IGSCode'
									  ");
		if(!$custvalq->num_rows > 0 ) 
		{
			#echo $HOGeneralID.'xxxx<br>';
			#create customer record
			$database->execute("
			                    INSERT INTO customer
										SET customer.Birthdate            = '$r->Birthdate',
											customer.BranchID             = '$BranchID',
											customer.Changed              = 1,
											customer.Code                 = '$r->dsv2code',
											customer.CustomerClassID      = '$r->CustomerClassID', 
											customer.CustomerTypeID       = '$r->mLevel',
											customer.DSV1_IGSCode         = '$r->dsv1code', 
											customer.EmployeeCode         = '$r->EmployeeCode',
											customer.EnrollmentDate       = '$r->cenrolldate',
											customer.FirstName            = '$r->FirstName',
											customer.FromBranch           = 0,
											customer.HOGeneralID          = '$HOGeneralID',
											customer.IsEmployee           = '$r->IsEmployee',
											customer.IsHomeGrownIBM       = '$r->IsHomeGrownIBM',
											customer.IsIBMPersonalAccount = '0',
											customer.IsReactivated        = '0',
											customer.IsTransferee         = '0',
											customer.LastModifiedDate     = now(),
											customer.LastName             = '$r->LastName',
											customer.MBranchID            = '$r->MBranchID',
											customer.MiddleName           = '$r->MiddleName',
											customer.Name                 = '$r->igsname',
											customer.Profileimage         = '',
											customer.RefID                = 0,
											customer.StatusID             = '$r->StatusID',
											customer.TIN                  = '$r->TIN',
											customer.AccountType          = '$r->AccountType'
			                  ");
						  
			#get the generated customerid
			$CustomerID = '';
			$cidq = $database->execute("select max(CustomerID) ID from customer ");
			if($cidq->num_rows > 0 )
			{
			   while($c = $cidq->fetch_object() )
			   {
				   $CustomerID = $c->ID;
			   }	
			}
			
			if($CustomerID != '')
			{   #create related table - tpi-customerdetails
				$database->execute(" INSERT INTO tpi_customerdetails
											 SET tpi_customerdetails.HOGeneralID            = '$HOGeneralID',
											 	 tpi_customerdetails.ApplicationFilePath    = '',
											 	 tpi_customerdetails.AreaID                 = '$r->AreaID',
											 	 tpi_customerdetails.BranchID               = '$BranchID',
												 tpi_customerdetails.StreetAdd              = '$r->StreetAdd',
												 tpi_customerdetails.TelNo                  = '$r->TelNo',
												 tpi_customerdetails.tpi_GSUTypeID          = '$r->tpi_GSUTypeID',
												 tpi_customerdetails.tpi_IBMCode            = '$r->networkcode',
												 tpi_customerdetails.ZipCode                = '$r->ZipCode',
												 tpi_customerdetails.tpi_ZoneID             = '$r->tpi_ZoneID',
											 	 tpi_customerdetails.Changed                = '1',
												 tpi_customerdetails.CustomerID             = '$CustomerID',
												 tpi_customerdetails.EnrollmentDate         = '$r->cenrolldate',
												 tpi_customerdetails.Gender                 = '$r->Gender',
												 tpi_customerdetails.IsAnIBMPersonalAccount = '0',
												 tpi_customerdetails.LastModifiedDate       = now(),
												 tpi_customerdetails.LastPODate             = '0000-00-00', 
												 tpi_customerdetails.MobileNo               = '$r->MobileNo',
												 tpi_customerdetails.NickName               = '$r->NickName',
												 tpi_customerdetails.Remarks                = 'MIGRATION'
												 
                                     ");		 
				#create related table - tpi-credit
				$database->execute("INSERT INTO tpi_credit
											SET tpi_credit.ApprovedCL       = '$r->AvailableCL',
												tpi_credit.ARBalance        = '0',
												tpi_credit.AvailableCL      = '$r->AvailableCL',
												tpi_credit.Branch           = '$BranchID',
												tpi_credit.CalculatedCL     = '0',
												tpi_credit.Capacity         = '0',
												tpi_credit.Capital          = '0',
												tpi_credit.Changed          = 1,
												tpi_credit.Character        = '0',
												tpi_credit.Condition        = '0', 
												tpi_credit.CustomerID       = '$CustomerID',
												tpi_credit.CreditTermID     = '$r->CreditTermID',
												tpi_credit.EnrollmentDate   = '$r->cenrolldate',
												tpi_credit.HOGeneralID      = '$HOGeneralID',
												tpi_credit.LastModifiedDate = now(),
												tpi_credit.RecommendedCL    = '0'  
                                    ");	
									
				#create related table - tpi-rcustomerstatus
				$database->execute("INSERT INTO tpi_rcustomerstatus
											SET tpi_rcustomerstatus.BranchID          = '$BranchID',
												tpi_rcustomerstatus.CustomerID        = '$CustomerID',
												tpi_rcustomerstatus.Changed           = 1,
												tpi_rcustomerstatus.CustomerStatusID  = '$r->StatusID',
												tpi_rcustomerstatus.EnrollmentDate    = '$r->cenrolldate',
												tpi_rcustomerstatus.FromBranch        = '$BranchID',
												tpi_rcustomerstatus.FromIBM           = '$CustomerID',
												tpi_rcustomerstatus.HOGeneralID       = '$HOGeneralID',
												tpi_rcustomerstatus.IsAddOther        = '0',
												tpi_rcustomerstatus.ISPRStatus        = '0',
												tpi_rcustomerstatus.IsReactivated     = '0',
												tpi_rcustomerstatus.IsRemoveOther     = '0',
												tpi_rcustomerstatus.ToBranch          = '$BranchID',
												tpi_rcustomerstatus.ToIBM             = '$CustomerID'
				                  ");	
								  
				#create related table - tpi-rcustomeribm
				$database->execute("insert into tpi_rcustomeribm
									SET tpi_rcustomeribm.HOGeneralID    = '$HOGeneralID',
										tpi_rcustomeribm.BranchID       = '$BranchID',
										tpi_rcustomeribm.CustomerID     = '$CustomerID',
										tpi_rcustomeribm.IBMID          = 0,
										tpi_rcustomeribm.IBM_Code       = '$r->networkcode',
										tpi_rcustomeribm.IBM_BranchID   = 0,
										tpi_rcustomeribm.CreatedBy      = 1,
										tpi_rcustomeribm.EnrollmentDate = '$r->cenrolldate', 
										tpi_rcustomeribm.Changed        = 1
				                  ");	
				
				#create related table - sfm_movement_history
				$database->execute("INSERT INTO sfm_movement_history
											SET sfm_movement_history.BranchID        = '$BranchID',
												sfm_movement_history.Changed         = 1,
												sfm_movement_history.CustomerID      = '$CustomerID',
												sfm_movement_history.DateModified    = now(),
												sfm_movement_history.FromLevel       = '$r->mLevel',
												sfm_movement_history.HOGeneralID     = '$HOGeneralID',
												sfm_movement_history.MovementDate    = now(),
												sfm_movement_history.MovementStatus  = 7,
												sfm_movement_history.ToLevel         = '$r->mLevel'
				                  ");	
                  
				#create related table - sfm_apphist
				if($r->bmcdate != '' )
				{
					$database->execute(" 
					                    INSERT INTO sfm_appthist
												SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
													sfm_appthist.CustomerID        = '$CustomerID',
													sfm_appthist.BranchID          = '$BranchID',
													sfm_appthist.sfm_level         = 2,
													sfm_appthist.appointment_date  = '$r->bmcdate',
													sfm_appthist.status            = 7,
													sfm_appthist.created_date      = '$r->bmcdate', 
													sfm_appthist.created_by        = 1,
													sfm_appthist.updated_date      = now(), 
													sfm_appthist.updated_by        = 1
					                  ");
				}
				if($r->ffdate1 != '' )
				{
					$database->execute(" 
					                    INSERT INTO sfm_appthist
												SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
													sfm_appthist.CustomerID        = '$CustomerID',
													sfm_appthist.BranchID          = '$BranchID',
													sfm_appthist.sfm_level         = 3,
													sfm_appthist.appointment_date  = '$r->ffdate1',
													sfm_appthist.status            = 7,
													sfm_appthist.created_date      = '$r->ffdate1', 
													sfm_appthist.created_by        = 1,
													sfm_appthist.updated_date      = now(), 
													sfm_appthist.updated_by        = 1
					                  ");
				}
				if($r->aldate != '' )
				{
					$database->execute(" 
					                    INSERT INTO sfm_appthist
												SET sfm_appthist.HOGeneralID       = '$HOGeneralID',
													sfm_appthist.CustomerID        = '$CustomerID',
													sfm_appthist.BranchID          = '$BranchID',
													sfm_appthist.sfm_level         = 3,
													sfm_appthist.appointment_date  = '$r->aldate',
													sfm_appthist.status            = 7,
													sfm_appthist.created_date      = '$r->aldate', 
													sfm_appthist.created_by        = 1,
													sfm_appthist.updated_date      = now(), 
													sfm_appthist.updated_by        = 1
					                  ");
				}
				
				#create related table - sfm_manager
				if($r->withOR == '')
				{
					$withOR = 0;
				}
				else
				{
					$withOR = $r->withOR;
				}
				
				if($r->ExcludedinIBMCount == '')
				{
					$ExcludedinIBMCount = '0';
				}
				else
				{
					$ExcludedinIBMCount = $r->ExcludedinIBMCount;
				}
				
				
				
				$database->execute(" 
					                    INSERT INTO sfm_manager
												SET sfm_manager.ApplicableBonusCodes  = '$r->ApplicableBonusCodes', 
												    sfm_manager.Vatable_Effectivity   = '$r->Vatable_Effectivity',
													sfm_manager.Changed               = 1,
													sfm_manager.date_modified         = now(),
													sfm_manager.first_name            = '$r->FirstName', 
													sfm_manager.HOGeneralID           = '$HOGeneralID',
													sfm_manager.last_name             = '$r->LastName', 
													sfm_manager.mCode                 = '$r->dsv2code',
													sfm_manager.middle_name           = '$r->MiddleName', 
													sfm_manager.ExcludedinIBMCount    = '$ExcludedinIBMCount',
												    sfm_manager.mID                   = '$CustomerID',
													sfm_manager.isaffiliated          = $accounttype,
													sfm_manager.bank_acct_name        = '$r->bank_acct_name', 
													sfm_manager.bank_acct_num         = '$r->bank_acct_num', 
													sfm_manager.bank_name             = '$r->bank_name', 
													sfm_manager.birth_date            = '$r->Birthdate', 
													sfm_manager.branchID              = '$BranchID',
													sfm_manager.credit_term_id        = '$r->CreditTermID',  
													sfm_manager.date_added            = '$r->cenrolldate', 
													sfm_manager.mLevel                = '$r->mLevel',
													sfm_manager.PayoutOrOffset        = '$r->PayoutOrOffset', 
													sfm_manager.TIN                   = '$r->TIN', 
													sfm_manager.Vatable               = '$r->Vatable',
													sfm_manager.credit_limit          = '$r->AvailableCL',
													sfm_manager.withOR                = '$withOR'
													
													
													
													
					             ");
				#create related table - sfm_manager_network
				$database->execute(" 
				                    INSERT INTO sfm_manager_networks
											SET sfm_manager_networks.manager_code               = '$r->networkcode',
												sfm_manager_networks.manager_network_code       = '$r->dsv2code',
												sfm_manager_networks.manager_network_codecustID = '$CustomerID'
				                   ");
							   
			}
			else
			{
				echo $HOGeneralID.'error<br>';
			}
			
		}	
		#echo $r->HOGeneralID.'fffff'.'<br>';	
	}
}

echo '<H3> Process Done...... You Can now proceed with the Validation ';


#update tpi_customerdetails.tpi_RecruiterID = '0',
#update tpi_rcustomeribm.IBMID              = 0,
#update tpi_rcustomeribm.IBM_BranchID       = 0,
#sfm_manager.Vatable_Effectivity
#SET sfm_manager_networks.manager_codeID        = 0,
#sfm_movement_history.MovementType    = 0,
#sfm_manager.Termination_Date      = '',
#sfm_manager_networks.manager_code_custID        = 0,

?>