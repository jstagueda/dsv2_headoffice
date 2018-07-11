<?php
	require_once("../initialize.php");
	
	$txnid = $_GET['tid'];
	if(isset($_POST['btnCancel'])) 
	{
		redirect_to("../index.php?pageid=27");	
	}
?>
