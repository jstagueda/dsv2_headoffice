<?php
	if(isset($_POST['btnPrint']))
	{
		
		$chkSelect = $_POST["chkSelect"];
		$customertypeid = $_POST['cboDealerType'];
		
        foreach ($chkSelect as $key=>$ID) 
 		{
			$update = $sp->spPromoteDealer($ID, $customertypeid);
		}

		$msg = "Successfully updated record.";
		redirect_to("index.php?pageid=73&msg=$msg");
	}
?>