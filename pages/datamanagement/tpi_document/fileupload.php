<?php
	include "../../../initialize.php";
	include IN_PATH.DS."pagination.php";
	global $database;

	$csv = array();

	// check there are no errors
	if($_FILES['fileToUpload']['error'] == 0){
		
		$Code			=	$_POST['txtCode'];
		$Description    =	$_POST['txtDescription'];
		$StartDate		=	date("Y-m-d h:i:s", strtotime($_POST['txtStartDate']));
		$EndDate		=	date("Y-m-d h:i:s", strtotime($_POST['txtEndDate']));
		
		$name = $_FILES['fileToUpload']['name'];
		$ext = strtolower(end(explode('.', $_FILES['fileToUpload']['name'])));
		$type = $_FILES['fileToUpload']['type'];
		$tmpName = $_FILES['fileToUpload']['tmp_name'];
		

		// check the file is a csv
		if($ext === 'csv'){
			$validate =  $database->execute("select ID from tpi_document where CE_Code='".$Code."'");

			if($validate->num_rows == 0){
				$database->execute("insert into tpi_document (`CE_Code`,`CE_Description`,`Effectivity_Date_Start`, `Effectivity_Date_End`, `Changed`) 
									values ('".$Code."','".$Description."','".$StartDate."','".$EndDate."',1)");
				
				$qid = $database->execute("SELECT LAST_INSERT_ID() ID");
				if($qid->num_rows > 0){
			 		 $ID = $qid->fetch_object()->ID;
				}
				
			}else{
				$ID = $validate->fetch_object()->ID;
				$database->execute("UPDATE tpi_document SET `Changed` = 1 where ID = ".$ID);
			}
			
			 
			
			
			if(($handle = fopen($tmpName, 'r')) !== FALSE) {
				// necessary if a large csv file
				set_time_limit(0);
			
				$row = 0;
				while(($data = fgetcsv($handle, 1000, '|')) !== FALSE) {
					// number of fields in the csv
					$num = count($data);
					// print_r($data);
					$ProductID =  $database->execute("SELECT ID from product where Code like '%".trim($data[0])."%'")->fetch_object()->ID;
					$PMG  = $database->execute("SELECT ID from tpi_pmg where Code='".trim($data[1])."'")->fetch_object()->ID;
					if($ProductID != "" || $ProductID != NULL || $ProductID != 0)
					$database->execute("INSERT INTO tpi_documentdetails (`tpi_document_ID`, `ProductID` , `PMGID`,`Changed`) VALUES (".$ID.", ".$ProductID.", ".$PMG.",1)");
					// inc the row
					$row++;
				}
				
				fclose($handle);
			}
		}
	}
	
	redirect_to("../../../index.php?pageid=345");
?>