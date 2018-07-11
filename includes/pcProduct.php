<?php
	global $database;
	$errmsg="";
	
	if(isset($_POST['btnUpdate']))
	{
		try
		{
			
			
			$database->beginTransaction();
			$id = 0;		
			$pUCost=addslashes($_POST['txtUCost']);		
			$pSize=addslashes($_POST['txtPSize']);		
			$pLife=addslashes($_POST['txtPLife']);
			$pStyle=addslashes($_POST['pStyle']);
			$pSubForm=addslashes($_POST['pSubForm']);	
			$pColor=addslashes($_POST['pColor']);				
			
			if(isset($_POST['hdnProductID'])){
				$id = $_POST['hdnProductID'];			
			}
				
			if (($_FILES["imgProduct"]["type"] == "image/gif")|| ($_FILES["imgProduct"]["type"] == "image/jpeg") || ($_FILES["imgProduct"]["type"] == "image/pjpeg")|| ($_FILES["imgProduct"]["type"] == "image/PNG") || ($_FILES["imgProduct"]["type"] == "image/bmp"))
			{
				move_uploaded_file($_FILES["imgProduct"]["tmp_name"],"productimage/" . $_FILES["imgProduct"]["name"]);
				$test = chmod("productimage/" . $_FILES["imgProduct"]["name"] , 0755);
				$rsUpdateImgage = $sp->spUpdateProductImage($database,  $_FILES["imgProduct"]["name"], $id);
			}
			else
			{
			  echo "Invalid file";
			}	
			
			
			$rs_existingProduct = $sp->spSelectExistingProduct($database, $id, trim($pCode));
			if(!$rs_existingProduct ->num_rows)
			{
				$database->execute("SET FOREIGN_KEY_CHECKS = 0");
				$affected_rows = $sp->spUpdateProductInformation($database, $id, $pUCost, $pSize, $pLife, $pStyle, $pSubForm, $pColor);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
			 	//check if product exist in product pricing
				$rsCheckProductPricing = $sp->spCheckProductPricing($database,$id);
				while($row = $rsCheckProductPricing->fetch_object())
				{
					if($row->cnt == 0)
					{
						$rsInsertProductPricing = $sp->spInsertProductPricing($database,$id,$pPMGID);
					}
				}
				$database->execute("SET FOREIGN_KEY_CHECKS = 1");
				$database->commitTransaction();
				$message = "Successfully updated record.";		
				redirect_to("index.php?pageid=20&msg=$message");
			}
			else
			{
				$database->commitTransaction();
				$errorMessage = "Code already exists.";
				redirect_to("index.php?pageid=20&errmsg=$errorMessage");	
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=20&errmsg=$errmsg");
		}		
	}			
?>