<?php


	
	global $database;
	$ptid = 0;


	$code = "";
	$name = "";
	$desc = "";
	$duration = "";	
	$search = "";
	$param = 0;

if(isset($_POST['btnSearch']))
	{
		$param = -1;
		$search = addslashes($_POST['txtfldsearch']);	
	}	
elseif(isset($_GET['svalue']))
	{
		$param = -1;
		$search = addslashes($_GET['svalue']);	
	}
	
		$rs_paymenttermsview = $sp->spSelectPaymentTerms($database,$param,$search);

	/*DROP DOWN BOX*/
	 $rs_cboTermsType = $sp->spSelectTermsType($database);
	/*END DROP DOWN BOX*/ 
	 
	 
	
	
	 if (isset($_GET['ptid'])){
		$ptid = $_GET['ptid'];
		
		 $rs_paymentterms = $sp->spSelectPaymentTerms($database,$ptid,$search);
		 if ($rs_paymentterms->num_rows){
			while ($row = $rs_paymentterms->fetch_object())
			{
				 $code   = $row->Code;
				 $name = $row->Name;
				 $desc = $row->Description;
				 $duration = $row->Duration;
				 $ttid = $row->TermsTypeID;

			} 
			$rs_paymentterms->close();
		 }
	 }
	 
	
	
		
?>