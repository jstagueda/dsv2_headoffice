<?php 

include "../../../initialize.php";

if(isset($_POST['action'])){
	
//====================================================Reason Code=======================================================================
	//display reason code form
	if($_POST['action'] == "ReasonCodeForm"){
		
		$reasoncode = '';
		$reasonname = '';
		$nature = '';
		$modetype = '';
		$reasonid = 0;
		$transactiontype = 0;
		$customertype = 0;
		
		if(isset($_POST['ReasonID'])){
			
			$query = $database->execute("SELECT * FROM ordmcmreasons WHERE ID = '".$_POST['ReasonID']."'");
			$res = $query->fetch_object();
			
			$reasonid = $_POST['ReasonID'];
			$reasoncode = $res->ReasonCode;
			$reasonname = stripslashes($res->ReasonName);
			$nature = stripslashes($res->Nature);
			$modetype = $res->IsNotORorDMCM;
			$transactiontype = $res->TransactionType;
			$customertype = $res->CustomerType;
			
		}
		
		echo "<div style='width:500px;'>
				<form action='' method='post' name='reasoncodeform'>
					<input type='hidden' value='".$reasonid."' name='ReasonID'>
					<input type='hidden' value='".$reasoncode."' name='OriginalReasonCode'>
					<table width='100%'>
						<tr>
							<td class='fieldlabel'>Reason Code</td>
							<td class='separator'>:</td>
							<td><input class='txtfield' name='ReasonCode' value='".$reasoncode."' onkeyup='return ValidateInput(this);' onkeydown='return ValidateInput(this);'></td>
						</tr>
						<tr>
							<td class='fieldlabel'>Reason Name</td>
							<td class='separator'>:</td>
							<td><input class='txtfield' name='ReasonName' value='".$reasonname."'></td>
						</tr>					
						<tr>
							<td class='fieldlabel' valign='top'>Nature</td>
							<td class='separator' valign='top'>:</td>
							<td>
								<textarea class='txtfield' name='Nature' style='height:90px; width:160px; resize:none;'>".$nature."</textarea>
							</td>
						</tr>
						<tr>
							<td class='fieldlabel'>Transaction Type</td>
							<td class='separator'>:</td>
							<td>
								<select name='TransactionType' class='txtfield'>
									<option value='0' ".(($transactiontype == 0) ? 'selected="selected"' : '').">Select</option>
									<option value='1' ".(($transactiontype == 1) ? 'selected="selected"' : '').">Debit</option>
									<option value='2' ".(($transactiontype == 2) ? 'selected="selected"' : '').">Credit</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class='fieldlabel'>Required Customer Type</td>
							<td class='separator'>:</td>
							<td>
								<select name='RequiredCustomerType' class='txtfield'>
									<option value='0' ".(($customertype == 0) ? 'selected="selected"' : '').">Select</option>
									<option value='1' ".(($customertype == 1) ? 'selected="selected"' : '').">IGS</option>
									<option value='3' ".(($customertype == 3) ? 'selected="selected"' : '').">IBM</option>
								</select>
							</td>
						</tr>						
						<tr>
							<td class='fieldlabel'>Mode Type</td>
							<td class='separator'>:</td>
							<td>
								<select name='ModeType' class='txtfield'>
									<option value='0' ".(($modetype == 0) ? 'selected="selected"' : '').">Collection Receipt</option>
									<option value='1' ".(($modetype == 1) ? 'selected="selected"' : '').">DMCM</option>
								</select>
							</td>
						</tr>
					</table>
				</form>
			</div>";
		
		die();
		
	}
	
	//display reason code table
	if($_POST['action'] == "ReasonCodeTable"){
		
		$query = $database->execute("SELECT * FROM ordmcmreasons ORDER BY ID Desc");
		
		echo '<table width="100%" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
				<tr class="trheader">
					<td width="10%">Reason Code</td>
					<td>Reason Name</td>
					<td>Nature</td>
					<td width="10%">CR or DMCM</td>
					<td width="10%">Transaction Type</td>
					<td width="10%">Customer Type</td>
					<td width="5%">Action</td>
				</tr>';
		
		if($query->num_rows){
			
			while($res = $query->fetch_object()){
				echo '<tr class="trlist">
					<td align="center">'.$res->ReasonCode.'</td>
					<td>'.stripslashes($res->ReasonName).'</td>
					<td>'.stripslashes($res->Nature).'</td>
					<td align="center">'.(($res->IsNotORorDMCM == 0) ? "CR" : "DMCM").'</td>
					<td align="center">'.(($res->TransactionType == 0) ? (($res->IsNotORorDMCM == 1) ? "Debit / Credit" : "") : (($res->TransactionType == 1) ? "Debit" : "Credit")).'</td>
					<td align="center">'.(($res->CustomerType == 0) ? (($res->IsNotORorDMCM == 1) ? "IGS / IBM" : "") : (($res->CustomerType == 1) ? "IGS" : "IBM")).'</td>
					<td align="center">
						<input type="button" class="btn" value="View" name="btnViewReasonCode" onclick="return ViewReasonCode('.$res->ID.')">
					</td>
				</tr>';
			}
			
		}else{
			echo '<tr class="trlist">
					<td colspan="5" align="center">No result found.</td>
				</tr>';
		}
		
		echo '</table>';
		
		die();
	}
	
	//saving reason code
	if($_POST['action'] == "SaveReasonCode"){
		
		try{
			$database->beginTransaction();
			
			$transactiontype = (isset($_POST['TransactionType'])) ? $_POST['TransactionType'] : 0;
			$customertype = (isset($_POST['RequiredCustomerType'])) ? $_POST['RequiredCustomerType'] : 0;
			
			if($_POST['ReasonID'] == 0){
				
				$query = $database->execute("SELECT ID FROM ordmcmreasons WHERE ReasonCode = '".addslashes($_POST['ReasonCode'])."'");
				if($query->num_rows){
					throw new Exception("Reason Code already exist.");
				}
				
				$database->execute("INSERT INTO ordmcmreasons SET
								`ReasonCode` = '".addslashes($_POST['ReasonCode'])."',
								`ReasonName` = '".addslashes($_POST['ReasonName'])."',
								`Nature` = '".addslashes($_POST['Nature'])."',
								`TransactionType` = ".$transactiontype.",
								`CustomerType` = ".$customertype.",
								`IsNotORorDMCM` = ".$_POST['ModeType']."");
				
			}else{
				
				$database->execute("UPDATE ordmcmreasons SET
								`ReasonCode` = '".addslashes($_POST['ReasonCode'])."',
								`ReasonName` = '".addslashes($_POST['ReasonName'])."',
								`Nature` = '".addslashes($_POST['Nature'])."',
								`IsNotORorDMCM` = ".$_POST['ModeType'].",
								`TransactionType` = ".$transactiontype.",
								`CustomerType` = ".$customertype."
								WHERE ID = '".$_POST['ReasonID']."'");
								
				$database->execute("UPDATE ordmcmreasonsdetails SET
								ORDMCMReasonsCode = '".addslashes($_POST['ReasonCode'])."'
								WHERE ORDMCMReasonsCode = '".addslashes($_POST['OriginalReasonCode'])."'");
				
			}
			$database->commitTransaction();
			$result['ErrorMessage'] = "<p>Reason code has been successfully saved.</p>";
			
		}catch(Exception $e){
			$database->rollbackTransaction();
			$result['ErrorMessage'] = "<p>".$e->getMessage()."</p>";
		}
		
		die(json_encode($result));
	}
	
	
	//delete reason code
	if($_POST['action'] == "DeleteReasonCode"){
		
		try{
		
			$database->beginTransaction();
			$query = $database->execute("SELECT ReasonCode FROM ordmcmreasons WHERE ID = ".$_POST['ReasonID']."");
			$res = $query->fetch_object();
			$ReasonCode = stripslashes($res->ReasonCode);
			
			$database->execute("DELETE FROM ordmcmreasonsdetails WHERE ORDMCMReasonsCode = '".addslashes($ReasonCode)."'");
			$database->execute("DELETE FROM ordmcmreasons WHERE ID = ".$_POST['ReasonID']."");
			$database->commitTransaction();
			
			$result['ErrorMessage'] = "<p>Reason Code has been deleted.</p>";
			
		}catch(Exception $e){
		
			$database->rollbackTransaction();
			$result['ErrorMessage'] = "<p>".$e->getMessage()."</p>";
			
		}
		
		die(json_encode($result));
	}

//====================================================GL Account=======================================================================

	//gl account form
	if($_POST['action'] == "GLAccountForm"){
	
		$glaccountcode = '';
		$glaccountdescription = '';
		$glaccountid = 0;
		
		if(isset($_POST['GLAccountID'])){
			
			$query = $database->execute("SELECT * FROM glaccounts WHERE ID = '".$_POST['GLAccountID']."'");
			$res = $query->fetch_object();
			
			$glaccountid = $_POST['GLAccountID'];
			$glaccountcode = $res->Code;
			$glaccountdescription = stripslashes($res->Description);
			
		}
		
		echo "<div style='width:500px;'>
				<form action='' method='post' name='glaccountform'>
					<input type='hidden' value='".$glaccountid."' name='GLAccountID'>
					<input type='hidden' value='".$glaccountcode."' name='OriginalGLAccount'>
					<table width='100%'>
						<tr>
							<td class='fieldlabel'>GL Account Code</td>
							<td class='separator'>:</td>
							<td><input class='txtfield' name='GLAccountCode' value='".$glaccountcode."'></td>
						</tr>				
						<tr>
							<td class='fieldlabel' valign='top'>GL Account Description</td>
							<td class='separator' valign='top'>:</td>
							<td>
								<textarea class='txtfield' name='GLAccountDescription' style='height:90px; width:160px; resize:none;'>".$glaccountdescription."</textarea>
							</td>
						</tr>
					</table>
				</form>
			</div>";
		
		die();
	}
	
	//gl account table
	if($_POST['action'] == "GLAccountTable"){
		$query = $database->execute("SELECT * FROM glaccounts ORDER BY ID Desc");
		
		echo '<table width="100%" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
				<tr class="trheader">
					<td width="20%">GL Account Code</td>
					<td>GL Account Description</td>
					<td width="10%">Action</td>
				</tr>';
		
		if($query->num_rows){
			
			while($res = $query->fetch_object()){
				echo '<tr class="trlist">
					<td align="center">'.$res->Code.'</td>
					<td>'.stripslashes($res->Description).'</td>
					<td align="center">
						<input type="button" class="btn" value="View" name="btnViewGLAccount" onclick="return ViewGLAccount('.$res->ID.')">
					</td>
				</tr>';
			}
			
		}else{
			echo '<tr class="trlist">
					<td colspan="3" align="center">No result found.</td>
				</tr>';
		}
		
		echo '</table>';
		
		die();
	}
	
	//saving GL Account
	if($_POST['action'] == "SaveGLAccount"){
		try{
			$database->beginTransaction();
			if($_POST['GLAccountID'] == 0){
				
				$query = $database->execute("SELECT ID FROM glaccounts WHERE `Code` = '".$_POST['GLAccountCode']."'");
				if($query->num_rows){
					throw new Exception("Reason Code already exist.");
				}
				
				$database->execute("INSERT INTO glaccounts SET
									`Code` = '".$_POST['GLAccountCode']."',
									`Description` = '".addslashes($_POST['GLAccountDescription'])."'");
				
			}else{
				
				$database->execute("UPDATE glaccounts SET
									`Code` = '".$_POST['GLAccountCode']."',
									`Description` = '".addslashes($_POST['GLAccountDescription'])."'
									WHERE ID = ".$_POST['GLAccountID']."");
								
				$database->execute("UPDATE ordmcmreasonsdetails SET
								GLAccountsCode = '".$_POST['GLAccountCode']."'
								WHERE GLAccountsCode = '".$_POST['OriginalGLAccount']."'");
				
			}
			$database->commitTransaction();
			$result['ErrorMessage'] = "<p>GL Account has been successfully saved.</p>";
			
		}catch(Exception $e){
			$database->rollbackTransaction();
			$result['ErrorMessage'] = "<p>".$e->getMessage()."</p>";
		}
		
		die(json_encode($result));
	}
	
	//delete GL Account
	if($_POST['action'] == 'DeleteGLAccouont'){
		
		try{
		
			$database->beginTransaction();
			$query = $database->execute("SELECT `Code` FROM glaccounts WHERE ID = ".$_POST['GLAccountID']."");
			$res = $query->fetch_object();
			$GLAccountCode = $res->Code;
			
			$database->execute("DELETE FROM ordmcmreasonsdetails WHERE GLAccountsCode = '".$GLAccountCode."'");
			$database->execute("DELETE FROM glaccounts WHERE ID = ".$_POST['GLAccountID']."");
			$database->commitTransaction();
			
			$result['ErrorMessage'] = "<p>GL Account has been deleted.</p>";
			
		}catch(Exception $e){
		
			$database->rollbackTransaction();
			$result['ErrorMessage'] = "<p>".$e->getMessage()."</p>";
			
		}
		
		die(json_encode($result));
		
	}
	
	
//====================================================Reason Code and GL Account Taggings=======================================================================

	if($_POST['action'] == "ReasonCodeAndGLAccountTaggingForm"){
		
		$reasoncode = '';
		$glaccountcode = '';
		$description = '';
		$costcenter = '';
		$subaccount = '';
		$projectcode = '';
		$taggingid = 0;
		$modetype = '';
		$reasons = "";
		$glaccount = "";
		$categories = 0;
		$paymenttype = 0;
		
		if(isset($_POST['TaggingID'])){
			
			$query = $database->execute("SELECT * FROM ordmcmreasonsdetails WHERE ID = '".$_POST['TaggingID']."'
										");
			$res = $query->fetch_object();
			
			$reasoncode = stripslashes($res->ORDMCMReasonsCode);
			$glaccountcode = $res->GLAccountsCode;
			$description = stripslashes($res->Description);
			$modetype = $res->DebitorCredit;
			$costcenter = $res->CostCenter;
			$subaccount = $res->SubAccount;
			$projectcode = $res->ProjCode;
			$taggingid = $_POST['TaggingID'];
			$paymenttype = $res->PaymentType;
			
		}
		
		$reasonquery = $database->execute("SELECT * FROM ordmcmreasons 
										WHERE IsNotORorDMCM = IFNULL((SELECT IsNotORorDMCM FROM ordmcmreasons 
											WHERE ReasonCode = '".$reasoncode."'), 0) 
										ORDER BY ID");
		if($reasonquery->num_rows){
			while($res = $reasonquery->fetch_object()){
				$sel = (stripslashes($res->ReasonCode) == $reasoncode) ? "selected='selected'" : "";
				if(stripslashes($res->ReasonCode) == $reasoncode){
					$categories = $res->IsNotORorDMCM;
				}
				$reasons .= "<option $sel value='".stripslashes($res->ReasonCode)."'>".stripslashes($res->ReasonCode)." - ".stripslashes($res->ReasonName)."</option>";
			}
		}
		
		$glaccountquery = $database->execute("SELECT * FROM glaccounts ORDER BY ID");
		if($glaccountquery->num_rows){
			while($res = $glaccountquery->fetch_object()){
				$sel = (stripslashes($res->Code) == $glaccountcode) ? "selected='selected'" : "";
				$glaccount .= "<option $sel value='".$res->Code."'>".$res->Code."</option>";
			}
		}
		
		$modeofpaymentarray = array(0 => "Select", 1 => "Cash, Check, or Deposit Slip", 2 => "SF / CB");
		$modeofpayment = "";
		foreach($modeofpaymentarray as $key => $val){
			$sel = ($paymenttype == $key) ? "selected='selected'" : "";
			$modeofpayment .= "<option value='$key' $sel>$val</option>";
		}
		
		echo "<div style='width:500px;'>
				<form action='' method='post' name='reasoncodeandglaccounttaggingform'>
					<input type='hidden' value='".$taggingid."' name='TaggingID'>
					
					<table width='100%'>
						<tr>
							<td class='fieldlabel'>Categories</td>
							<td class='separator'>:</td>
							<td>
								<select class='txtfield' name='categories' onchange='return GetReasonCode(this.value)'>
									<option value='0' ".(($categories == 0) ? "selected='selected'" : "").">Collection Receipt</option>
									<option value='1'".(($categories == 1) ? "selected='selected'" : "").">DMCM</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class='fieldlabel'>Reason Code</td>
							<td class='separator'>:</td>
							<td>
								<select class='txtfield' name='ReasonCode'>
									<option value=''>Select</option>
									$reasons
								</select>
							</td>
						</tr>
						<tr>
							<td class='fieldlabel'>GL Account Code</td>
							<td class='separator'>:</td>
							<td>
								<select class='txtfield' name='GLAccountCode'>
									<option value=''>Select</option>
									$glaccount
								</select>
							</td>
						</tr>					
						<tr>
							<td class='fieldlabel' valign='top'>Description</td>
							<td class='separator' valign='top'>:</td>
							<td>
								<textarea class='txtfield' name='Description' style='height:90px; width:160px; resize:none;'>".$description."</textarea>
							</td>
						</tr>						
						<tr>
							<td class='fieldlabel'>Mode Type</td>
							<td class='separator'>:</td>
							<td>
								<select name='ModeType' class='txtfield'>
									<option value='DEBIT' ".(($modetype == 'DEBIT') ? 'selected="selected"' : '').">DEBIT</option>
									<option value='CREDIT' ".(($modetype == 'CREDIT') ? 'selected="selected"' : '').">CREDIT</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class='fieldlabel'>Cost Center</td>
							<td class='separator'>:</td>
							<td><input class='txtfield' name='CostCenter' value='".$costcenter."'></td>
						</tr>
						<tr>
							<td class='fieldlabel'>Sub Account</td>
							<td class='separator'>:</td>
							<td><input class='txtfield' name='SubAccount' value='".$subaccount."'></td>
						</tr>
						<tr>
							<td class='fieldlabel'>Project Code</td>
							<td class='separator'>:</td>
							<td><input class='txtfield' name='ProjectCode' value='".$projectcode."'></td>
						</tr>
						<tr>
							<td class='fieldlabel'>Payment Type</td>
							<td class='separator'>:</td>
							<td>
								<select name='PaymentType' class='txtfield'>
									$modeofpayment
								</select>
							</td>
						</tr>
						
					</table>
				</form>
			</div>";
		
		die();
		
	}
	
	//view tagging
	if($_POST['action'] == "TaggingTable"){
		$query = $database->execute("SELECT 
									orr.ReasonName,
									orrd.GLAccountsCode,
									orrd.Description,
									orrd.DebitorCredit,
									orrd.CostCenter,
									orrd.SubAccount,
									orrd.ProjCode,
									orrd.ID,
									orr.IsNotORorDMCM
									FROM ordmcmreasonsdetails orrd 
									INNER JOIN ordmcmreasons orr ON orrd.ORDMCMReasonsCode = orr.ReasonCode
									ORDER BY orrd.ID Desc");
		
		echo '<table width="100%" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
				<tr class="trheader">
					<td>Categories</td>
					<td>Reason</td>
					<td>GL Account</td>
					<td>Description</td>
					<td width="10%">Debit or Credit</td>
					<td width="10%">Cost Center</td>
					<td width="10%">Sub Account</td>
					<td width="10%">Project Code</td>
					<td width="5%">Action</td>
				</tr>';
			
		if($query->num_rows){
			
			while($res = $query->fetch_object()){
				echo '<tr class="trlist">
					<td align="center">'.(($res->IsNotORorDMCM == 1) ? "DMCM" : "Collection Receipt").'</td>
					<td>'.stripslashes($res->ReasonName).'</td>
					<td align="center">'.$res->GLAccountsCode.'</td>
					<td>'.stripslashes($res->Description).'</td>
					<td align="center">'.$res->DebitorCredit.'</td>
					<td align="center">'.$res->CostCenter.'</td>
					<td align="center">'.$res->SubAccount.'</td>
					<td align="center">'.$res->ProjCode.'</td>
					<td align="center">
						<input type="button" class="btn" value="View" name="btnViewTagging" onclick="return ViewTagging('.$res->ID.')">
					</td>
				</tr>';
			}
			
		}else{
			echo '<tr class="trlist">
					<td colspan="9" align="center">No result found.</td>
				</tr>';
		}
		
		echo '</table>';
		
		die();
	}
	
	
	//saving tagging details
	if($_POST['action'] == "SaveReasonCodeAndGLAccountTagging"){
		
		try{
			$database->beginTransaction();
			if($_POST['TaggingID'] == 0){
				
				$database->execute("INSERT INTO ordmcmreasonsdetails SET
								ORDMCMReasonsCode = '".addslashes($_POST['ReasonCode'])."',
								GLAccountsCode = '".$_POST['GLAccountCode']."',
								Description = '".addslashes($_POST['Description'])."',
								DebitorCredit = '".$_POST['ModeType']."',
								CostCenter = '".$_POST['CostCenter']."',
								SubAccount = '".$_POST['SubAccount']."',
								ProjCode = '".$_POST['ProjectCode']."',
								PaymentType = ".$_POST['PaymentType']."");
				
			}else{
				
				$database->execute("UPDATE ordmcmreasonsdetails SET
								ORDMCMReasonsCode = '".addslashes($_POST['ReasonCode'])."',
								GLAccountsCode = '".$_POST['GLAccountCode']."',
								Description = '".addslashes($_POST['Description'])."',
								DebitorCredit = '".$_POST['ModeType']."',
								CostCenter = '".$_POST['CostCenter']."',
								SubAccount = '".$_POST['SubAccount']."',
								ProjCode = '".$_POST['ProjectCode']."',
								PaymentType = ".$_POST['PaymentType']."
								WHERE ID = '".$_POST['TaggingID']."'");
				
			}
			$database->commitTransaction();
			$result['ErrorMessage'] = "<p>Reason Code and Gl Account tagging has been successfully saved.</p>";
			
		}catch(Exception $e){
			$database->rollbackTransaction();
			$result['ErrorMessage'] = "<p>".$e->getMessage()."</p>";
		}
		
		die(json_encode($result));
		
	}
	
	//delete tagging
	if($_POST['action'] == 'DeleteTagging'){
		
		try{
		
			$database->beginTransaction();
			
			$database->execute("DELETE FROM ordmcmreasonsdetails WHERE ID = ".$_POST['TaggingID']."");
			
			$database->commitTransaction();
			
			$result['ErrorMessage'] = "<p>Reason Code and GL Account tagging has been deleted.</p>";
			
		}catch(Exception $e){
		
			$database->rollbackTransaction();
			$result['ErrorMessage'] = "<p>".$e->getMessage()."</p>";
			
		}
		
		die(json_encode($result));
		
	}
	
	//get reason codes
	if($_POST['action'] == "GetReasonCode"){
		
		$reasonquery = $database->execute("SELECT * FROM ordmcmreasons 
										WHERE IsNotORorDMCM = ".$_POST['CategoryID']." 
										ORDER BY ID");
		
		echo "<option value=''>Select</option>";
		
		if($reasonquery->num_rows){
			while($res = $reasonquery->fetch_object()){
				echo "<option value='".stripslashes($res->ReasonCode)."'>".stripslashes($res->ReasonCode)." - ".stripslashes($res->ReasonName)."</option>";
			}
		}
		
		die();
		
	}
	
}

?>