<?php
/*
AUTHOR BY: GINO LEABRES
DATE 11/31/2012
New Submodule Loyalty Uploader
*/
	
	global $database;
	$array_truncate =  array("uploadloyalbrtmp", "uploadloyalenttmp");
	foreach ($array_truncate as $tmptable){
			$truncate = "truncate table ".$tmptable;
			$database->execute($truncate);
	}
	/*message and error parameter*/
	$errormessage 	= "";
	$errmsg			= "";
	$message		= "";
	$msglog2		= "";
	/* parameter loyalty buyin requirement */
	$start_date 				=	"";
	$end_date 				    =	"";
	$PromoCode 				    =	"";
	$PromoDescription		    =	"";
	$PurchaseRequirementtype    =	"";
	$Criteria  				    =	"";
	$min_val   				    =	"";
	$points					    =	"";
	$brand					    =	"";
	$form					    =	"";
	$style					    =	"";
	$pmg					    =	"";
	$pline						=	"";
	$sku						=	"";
	/*parameter Loyalty Entilement*/
	$lPromoCode 	=	"";
	$lSKU 			=   "";
	$lPOINTS 		=   "";
	$lstart_date 	=   "";
	$lend_date 		=   "";
	/*Process for Uploading Data to TMP table*/
	if(isset($_POST['ReasonType'])) 
	{
		$path_loyalent = $_FILES['loyalent']['name'];
		$uploadErr = "";
		if($path_loyalent == "")
		{
	 		$errormessage = "Please select a file to upload.";
			echo '<script language="javascript">';
			echo 'alert("'.$errormessage.'")';
			echo '</script>';	
	 	}
		
		
		if (is_uploaded_file($file = $_FILES['loyalent']['tmp_name']))
		{
			$fileData = trim(file_get_contents($file));
			$isrho = true;
			$rows = explode("\n", $fileData);
		 	$first_row = true;	
			try 
			{
				$counter = 1;
				
				#validation
				foreach ($rows as $row)
				{
					$file = explode('|',trim($row));
					$ProductCode 	= $file[0];
					
					if($ProductCode != '')
					{
						$prodq = $database->execute("select * from product p where p.code = '$ProductCode' ");
						if(!$prodq ->num_rows)
						{ #validate if product exist in masterfile
							$errmsg .= 'Invalid Product Code : '.$ProductCode.'<br>';
						}
						
					}	
				}
				
				echo '<script language="javascript">';
			    echo 'alert("'.$errmsg.'")';
			    echo '</script>';	
				
				
				foreach ($rows as $row)
				{
					$file = explode('|',trim($row));
					$ProductCode 	= $file[0];
					$lstart_date 	= date("Y-m-d",strtotime($file[1]));
					$lend_date 		= date("Y-m-d",strtotime($file[2]));		
								
					if($counter != 1)
					{
						if($lPromoCode != "")
						{
										$query_insert = " 
														INSERT INTO uploadloyalenttmp (PromoCode, SKU, Points, start_date, end_date)
														VALUES ('$lPromoCode', $lSKU, $lPOINTS, '$lstart_date', '$lend_date')
														";
										//$database->execute($query_insert);
						}
					}                    
					$counter++;
				}
			}
			catch (Exception $e) 
			{
				$database->rollbackTransaction();
				$errmsg = $e->getMessage()."\n";
				redirect_to("../index.php?pageid=171.1&errmsg=$errmsg");
			}
		}
		
		$message = "Loyalty Promo File Successful";
		$queryLogs = "SELECT PromoCode, CONCAT(REPEAT('0', (7-LENGTH(SKU))), SKU) AS ProductCode FROM uploadloyalenttmp
						  WHERE CONCAT(REPEAT('0', (7-LENGTH(SKU))), SKU) NOT IN (SELECT CODE FROM product)";
		//$logs_result = $database->execute($queryLogs);
			
	
  }

  function SelectReason()
  {
	  global $database; 
	  $query =  $database->execute("SELECT ID, Code, Description
										FROM loyalty_program
								   ");
	  return $query;   
  }  
									
?>