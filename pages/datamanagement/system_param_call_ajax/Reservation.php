<?php 

include "../../../initialize.php";
include IN_PATH."pagination.php";
include IN_PATH."Reservation.php";

if(isset($_POST['action'])){
	
	if($_POST['action'] == "GetBranch"){
		
		$Search = $_POST['Search'];
		
		$branch = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (TRIM(Code) LIKE '%$Search%' OR TRIM(Name) LIKE '%$Search%') LIMIT 10");
		
		if($branch->num_rows){
			while($res = $branch->fetch_object()){
				$result[] = array("Label" => TRIM($res->Code).' - '.$res->Name,
								"Value" => TRIM($res->Code).' - '.$res->Name,
								"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found.",
								"Value" => "",
								"ID" => 0);
		}
		
		die(json_encode($result));
		
	}
	
	if($_POST['action'] == "Reservation"){
		
		$header = '<table width="100%" cellpadding="0" cellspacing="0" style="border-top:none;" class="bordersolo">
						<tr class="trheader">
							<td>Branch</td>
							<td>Reservation No</td>
							<td>Start Date</td>
							<td>End Date</td>
							<td>Transaction Date</td>
							<!--<td>Status</td>-->
						</tr>';
		$footer = '</table>';
		
		$BranchID = $_POST['BranchID'];
		$DateStart = date('Y-m-d', strtotime($_POST['DateStart']));
		$DateEnd = date('Y-m-d', strtotime($_POST['DateEnd']));
		$Search = $_POST['Search'];
		$page = $_POST['page'];
		$total = 10;
		
		$Reservation = Reservation($BranchID, $DateStart, $DateEnd, $Search, $page, $total, false);
		$ReservationTotal = Reservation($BranchID, $DateStart, $DateEnd, $Search, $page, $total, true);
		
		if($Reservation->num_rows){
			echo $header;
			while($res = $Reservation->fetch_object()){
				echo '<tr class="trlist">
						<td align="center">'.$res->BranchCode.'</td>
						<td align="center"><a href="javascript:UpdateReservation(\''.$res->ReservationNo.'\');" style="color:blue;">'.$res->ReservationNo.'</a></td>
						<td align="center">'.$res->StartDate.'</td>
						<td align="center">'.$res->EndDate.'</td>
						<td align="center">'.$res->TransactionDate.'</td>
						<!--<td align="center">'.$res->ReservationStatus.'</td>-->
					</tr>';
			}
			echo $footer;
			echo '<div style="margin-top:10px;">'.AddPagination($total, $ReservationTotal->num_rows, $page).'</div>';
		}else{
			echo $header;
			echo '<tr class="trlist">
					<td align="center" colspan="6">No result found.</td>
				</tr>';
			echo $footer;
		}
		
		die();
		
	}
	
	if($_POST['action'] == "GetProduct"){
		
		$Search = $_POST['Search'];
		$productquery = $database->execute("SELECT * FROM product WHERE ProductLevelID = 3 AND (TRIM(Code) LIKE '%$Search%' OR Name LIKE '%$Search%') LIMIT 5");
		if($productquery->num_rows){
			while($res = $productquery->fetch_object()){
				$result[] = array("Label" => TRIM($res->Code).' - '.$res->Name,
								"Value" => TRIM($res->Code),
								"ProductName" => $res->Name);
			}
		}else{
			$result[] = array("Label" => 'No result found.',
								"Value" => '',
								"ProductName" => '');
		}
		die(json_encode($result));
		
	}
	
	if($_POST['action'] == "CreateReservation"){
		
		echo '<div style="padding:10px; height:400px; overflow:auto;">
				<form action="" method="post" name="CreateReservationForm">
					
					<div style="width:70%;">
						<table width="100%" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="tabmin"></td>
								<td class="tabmin2">Action(s)</td>
								<td class="tabmin3"></td>
							</tr>
						</table>
						<div class="bordersolo" style="border-top:none; padding:10px;">
							<table cellpadding="1" cellspacing="1" width="100%">
								<tr>
									<td class="fieldlabel">Branch</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="Branch" class="txtfield" value="">
										<input type="hidden" name="BranchID" value="0">
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Reservation No</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="ReservationNo" class="txtfield" value="">
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Date From</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="DateStart" value="'.date("m/d/Y").'" class="txtfield">
										&nbsp;&nbsp;
										<b>To&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</b> 
										<input type="text" name="DateEnd" value="'.date("m/d/Y").'" class="txtfield">
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<br />
					
					<div>
						<table width="100%" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="tabmin"></td>
								<td class="tabmin2">Action(s)</td>
								<td class="tabmin3"></td>
							</tr>
						</table>
					
						<table width="100%" cellpadding="0" cellspacing="0" class="bordersolo productfield" style="border-top:none;">
							<tr class="trheader">
								<td width="15%">Product Code</td>
								<td>Product Name</td>
								<td width="15%">Requested Quantity</td>
								<td width="10%">Action</td>
							</tr>
							<tr class="trlist">
								<td align="center">
									<input type="text" name="ProductCode1" value="" class="txtfield" style="width:95%;" onkeydown="return SearchProduct(this);"> 
								</td>
								<td></td>
								<td align="center">
									<input type="text" name="Quantity1" value="0" class="txtfield" style="width:95%; text-align:right;" onkeydown="return AddNewRow(event, this);" disabled="disabled">
								</td>
								<td align="center">
									<input type="button" name="btnRemove1" value="Remove" class="btn" style="width:100%;" onclick="return RemoveReservation(this);">
								</td>
							</tr>
						</table>
					</div>
					
					<br />
					
				</form>
			</div>';
		
		echo '<script>
				GetBranch("form[name=CreateReservationForm] input[name=Branch]", "form[name=CreateReservationForm] input[name=BranchID]");
				$("[name=DateStart], [name=DateEnd]").datepicker({
					changeMonth	:	true,
					changeYear	:	true
				});
			</script>';
		
		die();
		
	}
	
	if($_POST['action'] == "SaveReservation"){
		
		try{
			
			$BranchSplit = explode('-', $_POST['Branch']);
			$Branch = $BranchSplit[0];
			$ReservationNo = $_POST['ReservationNo'];
			$DateStart = date('Y-m-d', strtotime($_POST['DateStart']));
			$DateEnd = date('Y-m-d', strtotime($_POST['DateEnd']));
			$TotalRow = $_POST['TotalRow'];
			
			$reservationquery = $database->execute("SELECT * FROM reservation WHERE TRIM(ReservationNo) = '".TRIM($ReservationNo)."'");
			if($reservationquery->num_rows){
				throw new Exception('Reservation No. already exist.');
			}
			
			$database->execute("INSERT INTO reservation SET
								BranchCode = '$Branch',
								ReservationNo = '$ReservationNo',
								StartDate = '$DateStart',
								EndDate = '$DateEnd',
								CreateDate = NOW(),
								LastUpdateDate = NOW(),
								CreateDateDMS = NOW(),
								Changed = 1");
								
			for($x = 1; $x <= $TotalRow; $x++){
				
				if(isset($_POST['Quantity'.$x])){
					
					$database->execute("INSERT INTO reservationdetails SET
								ReservationNo = '$ReservationNo',
								ProductCode = '".TRIM($_POST['ProductCode'.$x])."',
								RequestedQuantity = '".TRIM($_POST['Quantity'.$x])."',
								ApprovedQuantity = '".TRIM($_POST['Quantity'.$x])."',
								RemainingQuantity = '".TRIM($_POST['Quantity'.$x])."',
								CreateDate = NOW(),
								LastUpdateDate = NOW(),
								Changed = 1");
					
				}
				
			}
			
			$database->execute("UPDATE headofficesetting SET SettingValue = '".DATE("Y-m-d h:i:s")."' WHERE TRIM(SettingCode) = 'RESDATE'");
			
			$result['Success'] = 1;
			$result['ErrorMessage'] = "Reservation successfully added.";
			
		}catch(Exception $e){
			$result['Success'] = 0;
			$result['ErrorMessage'] = $e->getMessage();
		}
		
		die(json_encode($result));
		
	}
	
	if($_POST['action'] == "GetReservation"){
		
		$ReservationNo = TRIM($_POST['ReservationNo']);
		$reservationquery = $database->execute("SELECT * FROM reservation WHERE TRIM(ReservationNo) = '$ReservationNo'");
		$reservation = $reservationquery->fetch_object();
		
		$branch = $database->execute("SELECT * FROM branch WHERE TRIM(Code) = '".TRIM($reservation->BranchCode)."'")->fetch_object();
		
		echo '<div style="padding:10px; height:400px; overflow:auto;">
				<form action="" method="post" name="CreateReservationForm">
					
					<div style="width:70%;">
						<table width="100%" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="tabmin"></td>
								<td class="tabmin2">Action(s)</td>
								<td class="tabmin3"></td>
							</tr>
						</table>
						<div class="bordersolo" style="border-top:none; padding:10px;">
							<table cellpadding="1" cellspacing="1" width="100%">
								<tr>
									<td class="fieldlabel">Branch</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="Branch" class="txtfield" value="'.TRIM($branch->Code).' - '.$branch->Name.'">
										<input type="hidden" name="BranchID" value="'.$branch->ID.'">
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Reservation No</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="ReservationNo" class="txtfield" value="'.TRIM($ReservationNo).'">
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Date From</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="DateStart" value="'.date("m/d/Y", strtotime($reservation->StartDate)).'" class="txtfield">
										&nbsp;&nbsp;
										<b>To&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</b> 
										<input type="text" name="DateEnd" value="'.date("m/d/Y", strtotime($reservation->EndDate)).'" class="txtfield">
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<br />
					
					<div>
						<table width="100%" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="tabmin"></td>
								<td class="tabmin2">Action(s)</td>
								<td class="tabmin3"></td>
							</tr>
						</table>
					
						<table width="100%" cellpadding="0" cellspacing="0" class="bordersolo productfield" style="border-top:none;">
							<tr class="trheader">
								<td width="15%">Product Code</td>
								<td>Product Name</td>
								<td width="15%">Requested Quantity</td>
								<td width="10%">Action</td>
							</tr>';
						
						$reservationdetails = $database->execute("SELECT * FROM reservationdetails rd INNER JOIN product p ON TRIM(p.Code) = TRIM(rd.ProductCode) WHERE TRIM(rd.ReservationNo) = '$ReservationNo'");
						$counter = 1;
						if($reservationdetails->num_rows){
							while($res = $reservationdetails->fetch_object()){
								echo '<tr class="trlist">
									<td align="center">
										<input type="text" name="ProductCode'.$counter.'" value="'.TRIM($res->ProductCode).'" class="txtfield" style="width:95%;" onkeydown="return SearchProduct(this);" readonly="readonly">
									</td>
									<td>'.$res->Name.'</td>
									<td align="center">
										<input type="text" name="Quantity'.$counter.'" value="'.$res->ApprovedQuantity.'" class="txtfield" style="width:95%; text-align:right;" onkeydown="return AddNewRow(event, this);">
									</td>
									<td align="center">
										<input type="button" name="btnRemove'.$counter.'" value="Remove" class="btn" style="width:100%;" onclick="return RemoveReservation(this);">
									</td>
								</tr>';
								$counter++;
							}
						}else{
							echo '<tr class="trlist">
								<td align="center">
									<input type="text" name="ProductCode1" value="" class="txtfield" style="width:95%;" onkeydown="return SearchProduct(this);">
								</td>
								<td></td>
								<td align="center">
									<input type="text" name="Quantity1" value="0" class="txtfield" style="width:95%; text-align:right;" onkeydown="return AddNewRow(event, this);" disabled="disabled">
								</td>
								<td align="center">
									<input type="button" name="btnRemove1" value="Remove" class="btn" style="width:100%;" onclick="return RemoveReservation(this);">
								</td>
							</tr>';
						}
						echo '</table>
					</div>
					
					<br />
					
				</form>
			</div>';
		
		echo '<script>
				GetBranch("form[name=CreateReservationForm] input[name=Branch]", "form[name=CreateReservationForm] input[name=BranchID]");
				$("[name=DateStart], [name=DateEnd]").datepicker({
					changeMonth	:	true,
					changeYear	:	true
				});
			</script>';
		
		die();
	}
	
	if($_POST['action'] == "UpdateReservation"){
		
		try{
			
			$BranchSplit = explode('-', $_POST['Branch']);
			$Branch = $BranchSplit[0];
			$ReservationNo = $_POST['ReservationNo'];
			$DateStart = date('Y-m-d', strtotime($_POST['DateStart']));
			$DateEnd = date('Y-m-d', strtotime($_POST['DateEnd']));
			$TotalRow = $_POST['TotalRow'];
			
			$database->execute("UPDATE reservation SET
								BranchCode = '$Branch',
								ReservationNo = '$ReservationNo',
								StartDate = '$DateStart',
								EndDate = '$DateEnd',
								LastUpdateDate = NOW(),
								Changed = 1
								WHERE TRIM(ReservationNo) = '".TRIM($ReservationNo)."'");
								
			$ProductArray = array();
			for($x = 1; $x <= $TotalRow; $x++){
				
				if(isset($_POST['Quantity'.$x])){
					
					$reservationdetails = $database->execute("SELECT * FROM reservationdetails WHERE TRIM(ProductCode) = '".TRIM($_POST['ProductCode'.$x])."' AND TRIM(ReservationNo) = '".TRIM($ReservationNo)."'");
					
					if($reservationdetails->num_rows){
						$database->execute("UPDATE reservationdetails SET
								RequestedQuantity = '".TRIM($_POST['Quantity'.$x])."',
								ApprovedQuantity = '".TRIM($_POST['Quantity'.$x])."',
								RemainingQuantity = '".TRIM($_POST['Quantity'.$x])."',
								LastUpdateDate = NOW(),
								Changed = 1
								WHERE TRIM(ProductCode) = '".TRIM($_POST['ProductCode'.$x])."'
								AND TRIM(ReservationNo) = '".TRIM($ReservationNo)."'");
					}else{
						$database->execute("INSERT INTO reservationdetails SET
								ReservationNo = '$ReservationNo',
								ProductCode = '".TRIM($_POST['ProductCode'.$x])."',
								RequestedQuantity = '".TRIM($_POST['Quantity'.$x])."',
								ApprovedQuantity = '".TRIM($_POST['Quantity'.$x])."',
								RemainingQuantity = '".TRIM($_POST['Quantity'.$x])."',
								CreateDate = NOW(),
								LastUpdateDate = NOW(),
								Changed = 1");
					}
					
					$ProductArray[] = TRIM($_POST['ProductCode'.$x]);
					
				}
				
			}
			
			$database->execute("DELETE FROM reservationdetails WHERE TRIM(ReservationNo) = '".TRIM($ReservationNo)."' AND TRIM(ProductCode) NOT IN ('".implode("','", $ProductArray)."')");
			$database->execute("UPDATE headofficesetting SET SettingValue = '".DATE("Y-m-d h:i:s")."' WHERE TRIM(SettingCode) = 'RESDATE'");
			
			$result['Success'] = 1;
			$result['ErrorMessage'] = "Reservation successfully updated.";
			
		}catch(Exception $e){
			$result['Success'] = 0;
			$result['ErrorMessage'] = $e->getMessage();
		}
		
		die(json_encode($result));
		
	}
	
}

?>