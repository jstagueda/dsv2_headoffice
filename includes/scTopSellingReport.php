<?php
	global $database;	
	
	if (isset($_POST["btnSubmit"]))
	{
		$fromdate = date("m/d/Y", strtotime($_POST["txtFromDate"]));
		$todate = date("m/d/Y", strtotime($_POST["txtToDate"]));
		$sfromdate = date("Y-m-d", strtotime($_POST["txtFromDate"]));
		$stodate = date("Y-m-d", strtotime($_POST["txtToDate"]));
		$fromtime = $_POST["txtFromTime"];
		$totime = $_POST["txtToTime"];
		$product = $_POST["txtProduct"];
	}
	else
	{
		$fromdate = date("m/d/Y");
		$todate = date("m/d/Y");
		$sfromdate = date("Y-m-d");
		$stodate = date("Y-m-d");
		$fromtime = "";
		$totime = "";
		$product = "";
	}
?>