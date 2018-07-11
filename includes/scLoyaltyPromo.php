<?php
	require_once("../initialize.php");
	/*loyalty View Page*/
	$txtPromoCodeDesc = "";
	if(isset($_POST['btnSearch'])){
		$txtPromoCodeDesc = $_POST['txtPromoCodeDesc'];
		
	}
	

	/*Insert Buy In Requirement to Tmp Tble*/
	if(isset($_GET['btnAdd'])){
			/**/
			$PromoCode 		 = $_POST['txtPromoCode'];
			$sCriteria		 = $_POST['sCriteria'];
			$txtPoints		 = $_POST['txtPointsBuyin'];
			$txtMinimum		 = $_POST['txtMinimum'];
			$txtSetStartDate = date("Y-m-d",strtotime($_POST['txtBuyinReqSetStartDate']));
			$txtSetEndDate 	 = date("Y-m-d",strtotime($_POST['txtBuyinReqSetEndDate']));
			$sSection		 = $_POST['sSection'];
			
			if(isset($_POST['sProdLine'])){
				
				$sProdLine		 = $_POST['sProdLine'];
				if($sCriteria == 1){
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ProductID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sProdLine, $sCriteria, $txtPoints,  $txtMinimum, 0, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";
				}else{
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ProductID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sProdLine, $sCriteria, $txtPoints, 0, $txtMinimum, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";	
				}
				$database->execute($BuyInRequirementQRY);
				
				$buyintable = "SELECT tpl.*, c.Name CriteriaName, c.ID cID, p.Description FROM tpiloyaltybuyinrequirement tpl
							   INNER JOIN criteria c on tpl.CriteriaID = c.ID
							   inner join product p on tpl.ProductID = p.ID";
				$result = $database->execute($buyintable);
				if($result->num_rows){
					while($row = $result->fetch_object()){
						$ProductID =$row->ProductID;
						$CriteriaName =$row->cID;
						$Points =$row->Points;
						$MinQty =$row->MinQty;
						$MinAmt =number_format($row->MinAmt,2);
						$StartDate =$row->StartDate;
						$EndDate =$row->EndDate;
						$ID	= $row->ID;
						$Description = $row->Description;
						$results[] = array( 
											'ID' => $ID,
											'Description' => $Description,
											'Criteria' => $CriteriaName,
											'Points' => $Points,
											'MinQty' => $MinQty,
											'MinAmt' => $MinAmt,
											'StartDate' => date("m/d/Y", strtotime($StartDate)),
											'EndDate' => date("m/d/y", strtotime($EndDate)),
											'SelectionTyp' => 'ProductLine'
										  );
					}
				die(tpi_JSONencode($results));
				}else{
					die(tpi_JSONencode(array('result'=>0)));
				}
				
				
			}else if(isset($_POST['sBrand'])){
				
				$sBrand = $_POST['sBrand'];
				if($sCriteria == 1){
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sBrand, $sCriteria, $txtPoints, $txtMinimum, 0, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";
				}else{
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sBrand, $sCriteria, $txtPoints, 0, $txtMinimum, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";	
				}
				$database->execute($BuyInRequirementQRY);
				
				$buyintable = "SELECT tpl.*, c.Name CriteriaName, c.ID cID, v.Name vName FROM tpiloyaltybuyinrequirement tpl
							   INNER JOIN criteria c on tpl.CriteriaID = c.ID
							   inner join value v on tpl.ValueID = v.ID";
				$result = $database->execute($buyintable);
				if($result->num_rows){
					while($row = $result->fetch_object()){
						$ProductID =$row->ProductID;
						$CriteriaName =$row->cID;
						$Points =$row->Points;
						$MinQty =$row->MinQty;
						$MinAmt =number_format($row->MinAmt,2);
						$StartDate =$row->StartDate;
						$EndDate =$row->EndDate;
						$ID	= $row->ID;
						$Description = $row->vName;
						$results[] = array( 
											'ID' => $ID,
											'Description' => $Description,
											'Criteria' => $CriteriaName,
											'Points' => $Points,
											'MinQty' => $MinQty,
											'MinAmt' => $MinAmt,
											'StartDate' => date("m/d/Y", strtotime($StartDate)),
											'EndDate' => date("m/d/y", strtotime($EndDate)),
											'SelectionTyp' => 'Brand'
										  );
					}
				die(tpi_JSONencode($results));
				}else{
					die(tpi_JSONencode(array('result'=>0)));
				}
				
				
			}else if(isset($_POST['sForm'])){
				
				$sForm = $_POST['sForm'];
				if($sCriteria == 1){
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sForm, $sCriteria, $txtPoints, $txtMinimum, 0, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";
				}else{
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sForm, $sCriteria, $txtPoints, 0, $txtMinimum, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";	
				}
				$database->execute($BuyInRequirementQRY);
				
				$buyintable = "SELECT tpl.*, c.Name CriteriaName, c.ID cID, v.Name vName FROM tpiloyaltybuyinrequirement tpl
							   INNER JOIN criteria c on tpl.CriteriaID = c.ID
							   inner join value v on tpl.ValueID = v.ID";
				$result = $database->execute($buyintable);
				if($result->num_rows){
					while($row = $result->fetch_object()){
						$ProductID =$row->ProductID;
						$CriteriaName =$row->cID;
						$Points =$row->Points;
						$MinQty =$row->MinQty;
						$MinAmt =number_format($row->MinAmt,2);
						$StartDate =$row->StartDate;
						$EndDate =$row->EndDate;
						$ID	= $row->ID;
						$Description = $row->vName;
						$results[] = array( 
											'ID' => $ID,
											'Description' => $Description,
											'Criteria' => $CriteriaName,
											'Points' => $Points,
											'MinQty' => $MinQty,
											'MinAmt' => $MinAmt,
											'StartDate' => date("m/d/Y", strtotime($StartDate)),
											'EndDate' => date("m/d/y", strtotime($EndDate)),
											'SelectionTyp' => 'Form'
										  );
					}
				die(tpi_JSONencode($results));
					
				}else{
					die(tpi_JSONencode(array('result'=>0)));
				}
				
			
			}else if(isset($_POST['sStyle'])){
			
				$sStyle = $_POST['sStyle'];
				if($sCriteria == 1){
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sStyle, $sCriteria, $txtPoints, $txtMinimum, 0, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";
				}else{
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sStyle, $sCriteria, $txtPoints, 0, $txtMinimum, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";	
				}
				$database->execute($BuyInRequirementQRY);
				
				$buyintable = "SELECT tpl.*, c.Name CriteriaName, c.ID cID, v.Name vName FROM tpiloyaltybuyinrequirement tpl
							   INNER JOIN criteria c on tpl.CriteriaID = c.ID
							   inner join value v on tpl.ValueID = v.ID";
				$result = $database->execute($buyintable);
				if($result->num_rows){
					while($row = $result->fetch_object()){
						$ProductID =$row->ProductID;
						$CriteriaName =$row->cID;
						$Points =$row->Points;
						$MinQty =$row->MinQty;
						$MinAmt =number_format($row->MinAmt,2);
						$StartDate =$row->StartDate;
						$EndDate =$row->EndDate;
						$ID	= $row->ID;
						$Description = $row->vName;
						$results[] = array( 
											'ID' => $ID,
											'Description' => $Description,
											'Criteria' => $CriteriaName,
											'Points' => $Points,
											'MinQty' => $MinQty,
											'MinAmt' => $MinAmt,
											'StartDate' => date("m/d/Y", strtotime($StartDate)),
											'EndDate' => date("m/d/y", strtotime($EndDate)),
											'SelectionTyp' => 'Form'
										  );
					}
				die(tpi_JSONencode($results));
					
				}else{
					die(tpi_JSONencode(array('result'=>0)));
				}
				
				
			}else if(isset($_POST['sPMG'])){
				//meeting
				$sPMG = $_POST['sPMG'];
				if($sCriteria == 1){
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sPMG, $sCriteria, $txtPoints, 0, $txtMinimum, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";
				}else{
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ProductID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $sPMG, $sCriteria, $txtPoints, 0, $txtMinimum, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";	
				}
				$database->execute($BuyInRequirementQRY);
				
			}else if ($_POST['txtProdID']){
				$txtProdID = $_POST['txtProdID'];
				if($sCriteria == 1){
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ProductID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $txtProdID, $sCriteria, $txtPoints, $txtMinimum, 0, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";
				}else{
					$BuyInRequirementQRY = "INSERT INTO tpiloyaltybuyinrequirement(SelectionID, ProductID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, session)
											VALUES ($sSection, $txtProdID, $sCriteria, $txtPoints, 0, $txtMinimum, '$txtSetStartDate','$txtSetEndDate', $session->emp_id)";	
				}
				$database->execute($BuyInRequirementQRY);
				
				$buyintable = "SELECT tpl.*, c.Name CriteriaName, c.ID cID, p.Description FROM tpiloyaltybuyinrequirement tpl
							   INNER JOIN criteria c on tpl.CriteriaID = c.ID
							   inner join product p on tpl.ProductID = p.ID";
				$result = $database->execute($buyintable);
				if($result->num_rows){
					while($row = $result->fetch_object()){
						$ProductID =$row->ProductID;
						$CriteriaName =$row->cID;
						$Points =$row->Points;
						$MinQty =$row->MinQty;
						$MinAmt =number_format($row->MinAmt,2);
						$StartDate =$row->StartDate;
						$EndDate =$row->EndDate;
						$ID	= $row->ID;
						$Description = $row->Description;
						$results[] = array( 
											'ID' => $ID,
											'Description' => $Description,
											'Criteria' => $CriteriaName,
											'Points' => $Points,
											'MinQty' => $MinQty,
											'MinAmt' => $MinAmt,
											'StartDate' => date("m/d/Y", strtotime($StartDate)),
											'EndDate' => date("m/d/y", strtotime($EndDate)),
											'SelectionTyp' => 'ProductID'
										  );
					}
				die(tpi_JSONencode($results));
					
				}else{
					die(tpi_JSONencode(array('result'=>0)));
				}

			}
			
				
	}
	
	if(isset($_GET['btndelete'])){
		$ID = $_POST['xIDx'];
		$Selection = $_POST['xSelectionType'];
		
		$database->execute("DELETE FROM tpiloyaltybuyinrequirement WHERE ID = $ID");
		if($Selection != 'ProductID'){
			$buyintable = "SELECT tpl.*, c.Name CriteriaName, c.ID cID, v.Name vName FROM tpiloyaltybuyinrequirement tpl
						   INNER JOIN criteria c on tpl.CriteriaID = c.ID
						   INNER JOIN value v on tpl.ValueID = v.ID";
				$result = $database->execute($buyintable);
				if($result->num_rows){
					while($row = $result->fetch_object()){
						$ProductID =$row->ProductID;
						$CriteriaName =$row->cID;
						$Points =$row->Points;
						$MinQty =$row->MinQty;
						$MinAmt =number_format($row->MinAmt,2);
						$StartDate =$row->StartDate;
						$EndDate =$row->EndDate;
						$ID	= $row->ID;
						$Description = $row->vName;
						$results[] = array( 
											'ID' => $ID,
											'Description' => $Description,
											'Criteria' => $CriteriaName,
											'Points' => $Points,
											'MinQty' => $MinQty,
											'MinAmt' => $MinAmt,
											'StartDate' => date("m/d/Y", strtotime($StartDate)),
											'EndDate' => date("m/d/y", strtotime($EndDate)),
											'SelectionTyp' => 'Brand'
										  );
					}
				die(tpi_JSONencode($results));
				}else{
					die(tpi_JSONencode(array('result'=>0)));
				}
		}else{
		$buyintable = "SELECT tpl.*, c.Name CriteriaName, c.ID cID, p.Description FROM tpiloyaltybuyinrequirement tpl
							   INNER JOIN criteria c on tpl.CriteriaID = c.ID
							   inner join product p on tpl.ProductID = p.ID";
				$result = $database->execute($buyintable);
				if($result->num_rows){
					while($row = $result->fetch_object()){
						$ProductID =$row->ProductID;
						$CriteriaName =$row->cID;
						$Points =$row->Points;
						$MinQty =$row->MinQty;
						$MinAmt =number_format($row->MinAmt,2);
						$StartDate =$row->StartDate;
						$EndDate =$row->EndDate;
						$ID	= $row->ID;
						$Description = $row->Description;
						$results[] = array( 
											'ID' => $ID,
											'Description' => $Description,
											'Criteria' => $CriteriaName,
											'Points' => $Points,
											'MinQty' => $MinQty,
											'MinAmt' => $MinAmt,
											'StartDate' => date("m/d/Y", strtotime($StartDate)),
											'EndDate' => date("m/d/y", strtotime($EndDate)),
											'SelectionTyp' => 'ProductID'
										  );
					}
				die(tpi_JSONencode($results));
					
				}else{
					die(tpi_JSONencode(array('result'=>0)));
				}
		}
	}
	
	if(isset($_GET['btnSave'])){
			try
			{
				$database->beginTransaction();	
				$PromoCode 		= $_POST['txtPromoCode'];
				$PromoTitle 	= $_POST['txtPromoTitle'];
				$etlmtcount 	= $_POST['entitlementcnt'];		
				$PReqtType 		= $_POST['PReqtType'];		
				$sSection		= $_POST['sSection'];
				$start_date		= date("Y-m-d",strtotime($_POST['txtEntitlemntSetStartDate']));
				$end_date		= date("Y-m-d",strtotime($_POST['txtSetentitlementEndDate']));
				$buyin_sDate	= date("Y-m-d",strtotime($_POST['txtBuyinReqSetStartDate']));
				$buyin_eDate	= date("Y-m-d",strtotime($_POST['txtBuyinReqSetEndDate']));
				$NonGSU			= $_POST['txtNonGSU'];
				if($NonGSU == ""){
					$NonGSU = 0;
				}
				$DirectGSU		= $_POST['txtDirectGSU'];
				if($NonGSU == ""){
					$DirectGSU = 0;
				}
				$IndirectGSU	= $_POST['txtIndirectGSU'];
				if($IndirectGSU == ""){
					$IndirectGSU = 0;
				}
				$database->execute("INSERT into loyaltypromo (PromoCode, PromoTitle, prt, StartDate, EndDate, EnrollmentDate, LastModifiedDate)
									values('$PromoCode','$PromoTitle','$PReqtType','$buyin_sDate','$buyin_eDate',now(),now())");
				$result = $database->execute("SELECT LAST_INSERT_ID() insert_id");
				while($row = $result->fetch_object()){
					$insert_id = $row->insert_id;
				}
				$database->execute("INSERT INTO loyaltypromoavailment (LoyaltyPromoID, NonGSU, DirectGSU, IndirectGSU) VALUES (".$insert_id.", ".$NonGSU.", ".$DirectGSU.", ".$IndirectGSU.")");
	
				
				$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate, LevelType)
									VALUES ($insert_id, null, null, null, null, null, null, null, null, '$buyin_sDate','$buyin_eDate', 0)");
				$result1 = $database->execute("SELECT LAST_INSERT_ID() insert_id");
				if($result1->num_rows){
					while ($row = $result1->fetch_object()){
						$insert_id1 = $row->insert_id;
					}
				}
				$buyinrequirments = $database->execute("SELECT * FROM tpiloyaltybuyinrequirement where session = $session->emp_id");
				
				if($buyinrequirments->num_rows){
					while($row = $buyinrequirments->fetch_object()){
						$header 		= $insert_id;
						$parentid 		= $insert_id1;
						$SelectionID	= $row->SelectionID; 
						$ProductID		= $row->ProductID;
						$ValueID		= $row->ValueID;
						$CriteriaID		= $row->CriteriaID; 
						$Points			= $row->Points;
						$MinQty			= $row->MinQty;
						$MinAmt			= $row->MinAmt; 
						$StartDate		= $row->StartDate; 
						$EndDate		= $row->EndDate;
						$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID, ValueID, CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
						VALUES ($header, $parentid, $SelectionID, $ProductID, $ValueID, $CriteriaID, $Points, $MinQty, $MinAmt, '$StartDate', '$EndDate')");
	
					}
				}
				
				
				for($i=1; $i <= $etlmtcount; $i++){
					$hEProdID 	= $_POST['hEProdID'][$i];
					$EPoints 	= $_POST["txtPoints{$i}"];
					$parentid 	= $insert_id1;
					
							$query = "INSERT INTO loyaltypromoentitlement (LoyaltyBuyinID, ProductID, Points, StartDate, EndDate)
									VALUES($parentid, $hEProdID, $EPoints,'$start_date','$end_date')";
					if(isset($hEProdID)){
								$database->execute($query);
					}
				}
			$database->commitTransaction();
			die(tpi_JSONencode(array('Result'=>'Done')));
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
		}
	}
	
	if(isset($_POST['btnCancel'])){
		$truncate = "Truncate Table tpiloyaltybuyinrequirementtmp";
		$database->execute($truncate);
		redirect_to('index.php?pageid=170');
	}
	

	/*delete*/
	
	if (isset($_POST['btnDelete']))
	{
		try
		{
			
				$database->beginTransaction();
				//$affected_rows = $sp->spDeletePromo($database, $_GET['prmsid']);
				$PromoCode = $_POST['PromoCode'];
				
				$querybuyin = " DELETE FROM tpiloyaltybuyinrequirement 
								where LoyaltyHeaderCode ='".$PromoCode."'";
				$database->execute($querybuyin);
				
				$Query = "DELETE FROM tpiloyaltyheader where PromoCode ='".$PromoCode."'";
				$database->execute($Query);
			
				$entitledate = "delete from tpiloyaltyentitlement where LoyaltyHeaderCode = '".$PromoCode."'";
				$database->execute($entitledate);
				
				$database->commitTransaction();
				echo"<script language=JavaScript>
							opener.location.href = '../../index.php?pageid=170&msg=Successfully deleted promo.';
							window.close();
						</script>";	
								
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";	
		}
	}
	
	
	if(isset($_GET['EntDeleteID'])){
		$EntID = $_POST['EntID'];
		$query = "DELETE FROM loyaltypromoentitlement where ID = ".$EntID;
		$database->execute($query);
		die(json_encode(array('Success'=>'Success')));
	}
	
	if(isset($_GET['DeleteBuyinID'])){
		$BuyinID = $_POST['BuyinID'];
		$query = "DELETE FROM loyaltypromobuyin where ID = ".$BuyinID;
		$database->execute($query);
		die(json_encode(array('Success'=>'Success')));
	}
?>