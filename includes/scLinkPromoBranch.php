<?php
	global $database;
	$code = "";
	$desc = "";
	$sdate = "";
	$edate = "";
	$ptypeid = 0;
	
	if (isset($_GET['pid']))
	{
		$pid = $_GET['pid'];
	}
	else
	{
		$pid = 0;                    		
	}
	
	if (isset($_POST['cboPromoType']))
	{
		$ptypeid = $_POST['cboPromoType'];		
	}
                    	
	$rs_branch = $sp->spSelectBranchToLink($database, 0, "");
	$rs_promotype = $sp->spSelectPromoType($database);
	
	if(isset($_GET['search']))
	{
		$search = $_GET['search'];		
	}
	else
	{
		$search = "";		
	}
	
	if(isset($_POST['btnSearch']))
	{
		$search = $_POST['txtSearch'];
		$rs_promolist = $sp->spSelectPromoLinking($database, $search, $ptypeid,'');
		$rs_branchlist = $sp->spSelectBranchByPromoID($database, 0);		
	}
	else
	{
		$rs_promolist = $sp->spSelectPromoLinking($database, $search, $ptypeid,'');
		$rs_branchlist = $sp->spSelectBranchByPromoID($database, 0);
	}
	
	if(isset($_GET['pid']))
	{		
		$rs_promodet = $sp->spSelectPromoByID($database, $_GET['pid']);
		if($rs_promodet->num_rows)
        {
            while($row = $rs_promodet->fetch_object())
            {
            	$code = $row->Code; 
            	$desc = $row->Description;
            	$sdate = date("m/d/Y", strtotime($row->StartDate));
            	$edate = date("m/d/Y", strtotime($row->EndDate));
            }
            $rs_promodet->close();
        }
	}
	
	if(isset($_POST['btnSave']))
	{
		try
		{
			$database->beginTransaction();
			
			//delete existing linking
			$sp->spDeleteBranchLinkingByPromoID($database, $_GET['pid']);
			
			//check if there are selected branches
			if (isset($_POST['chkInclude']))
			{
				//insert linking
				foreach ($_POST['chkInclude'] as $key=>$value)
				{
					$sp->spInsertPromoBranchLinking($database, $_GET['pid'], $value);				
				}			
			}

			$database->commitTransaction();	
			$message = "Successfully saved transaction.";
			redirect_to("index.php?pageid=127&msg=$message");			
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=127&errmsg=$errmsg");
		}		
	}
	
	if (isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=127");
	}
?>
