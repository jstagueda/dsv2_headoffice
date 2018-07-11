<?php
	global $database;
	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	
	$ptid = 0;
	$code = "";
	$name = "";
	$markup = "";
	$discount1 = "";
	$discount2 = "";
	$discount3 = "";
	$ref = 0;
	$msg = "";
	$search = "";
	$param = 0;

	if(isset($_POST['btnSearch']))
	{
		$param = -1;
		$search = addslashes($_POST['txtfldSearch']);
	}	
	elseif(isset($_GET['svalue']))
	{
		$param = -1;
		$search = addslashes($_GET['svalue']);
	}
	
	$rs_pricetempall = $sp->spSelectPriceList($database, $param,$search);
	$rs_cboRef =  $sp->spSelectPriceList($database, $ptid,$search);
	
	 if (isset($_GET['ptid']))
	 {
	 	$ptid = $_GET['ptid'];
	 	$rs_pricetemplate = $sp->spSelectPriceList($database,$ptid,$search);
	 	
	 	if ($rs_pricetemplate->num_rows)
	 	{
	 		while ($row = $rs_pricetemplate->fetch_object())
			{
				 $code   = $row->Code;
				 $name = $row->Name;
				 $markup = $row->MarkUp;
				 $discount1 = $row->Discount1;
				 $discount2 = $row->Discount2;
				 $discount3 = $row->Discount3;
				 $ref = $row->ReferenceID;
			} 
			$rs_pricetemplate->close();
		}
 	}	
?>