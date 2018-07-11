<?php 
	include "../../../initialize.php";
	
	if(isset($_POST['action'])){
	
		if($_POST['action'] == "SaveNewSetting"){
			
			try{
				
				$database->beginTransaction();
				
				$SettingCode = TRIM($_POST['SettingCode']);
				$SettingName = $_POST['SettingName'];
				$SettingValue = $_POST['SettingValue'];
				
				$SettingExistence = $database->execute("SELECT ID FROM headofficesetting WHERE SettingCode = '$SettingCode'");
				
				if($SettingExistence->num_rows){
					throw new Exception("Setting Code already exist.");
				}
				
				$database->execute("INSERT INTO headofficesetting SET
									SettingCode = '$SettingCode',
									SettingName = '$SettingName',
									SettingValue = '$SettingValue'");
				
				$database->commitTransaction();
				$result['Success'] = 1;
				$result['ErrorMessage'] = "Successfully add new setting.";				
				
			}catch(Exception $e){
			
				$database->rollbackTransaction();
				$result['Success'] = 0;
				$result['ErrorMessage'] = $e->getMessage();
				
			}
			
			die(json_encode($result));
		}
		
		if($_POST['action'] == "EditSettingForm"){
			
			$query = $database->execute("SELECT * FROM headofficesetting WHERE ID = ".$_POST['SettingID']."");
			$res = $query->fetch_object();
			
			$result['SettingCode'] = $res->SettingCode;
			$result['SettingName'] = $res->SettingName;
			$result['SettingValue'] = $res->SettingValue;
			
			die(json_encode($result));
			
		}
		
		if($_POST['action'] == "EditSetting"){
		
			try{
				
				$database->beginTransaction();
				
				$SettingCode = TRIM($_POST['SettingCode']);
				$SettingName = $_POST['SettingName'];
				$SettingValue = $_POST['SettingValue'];
				$SettingID = $_POST['SettingID'];
				
				$database->execute("UPDATE headofficesetting SET
									SettingCode = '$SettingCode',
									SettingName = '$SettingName',
									SettingValue = '$SettingValue'
									WHERE ID = $SettingID");
				
				$database->commitTransaction();
				$result['Success'] = 1;
				$result['ErrorMessage'] = "Successfully edit setting.";				
				
			}catch(Exception $e){
			
				$database->rollbackTransaction();
				$result['Success'] = 0;
				$result['ErrorMessage'] = $e->getMessage();
				
			}
			
			die(json_encode($result));
			
		}
		
		
		if($_POST['action'] == "RemoveSetting"){
			
			try{
				$database->beginTransaction();
				
				$database->execute("DELETE FROM headofficesetting WHERE ID = ".$_POST['SettingID']."");
				
				$database->commitTransaction();
				$result['Success'] = 1;
				$result['ErrorMessage'] = "Setting deleted.";
			
			}catch(Exception $e){
				$database->rollbackTransaction();
				$result['Success'] = 0;
				$result['ErrorMessage'] = $e->getMessage();
			}
			
			die(json_encode($result));
			
		}
		
	}
?>