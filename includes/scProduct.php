<?php
	global $database;
	//ini_set('max_execution_time', 300);
	
	$pID = 0;
	$pStatus = 1;
	$pParent = "NONE";
	$pCode = "";
	$pName = "";
	$pShrtName = "";
	$productImage ="";	
	$pProdCls = "";	
	$pProdTypeID = 0;
	$pProdClsID = 0;
	$pCatID = 0;
	$pBrandID = 0;
	$pUOMID = 0;
	$pUCost = 0;
	$pColorID = 0;
	$pStyleID = 0;
	$pSubFormID = 0;
	$pSize = '';
	$pLife = '';
	$prodSearchTxt = "";
	$launchdate = "";
	$lastpodate = "";
	
	/*DROP DOWN BOX*/
	$rs_cboProdType = $sp->spSelectProdSelProdType($database);	
	$rs_cboProdLine =  $sp->spSelectProductLine($database,2);
	$rs_cboProdCls = $sp->spSelectPMG($database);	
	$rs_cboForm = $sp->spSelectForm($database);	
	$rs_cboBrand = $sp->spSelectBrand($database);
	$rs_cboUOM = $sp->spSelectProdUOM($database);	
	$rs_cboStyle = $sp->spSelectPStyle($database);	
	$rs_cboSubForm = $sp->spSelectPSubForm($database);	
	$rs_cboColor = $sp->spSelectPColor($database);	
	/*END DROP DOWN BOX*/ 
	
	if(isset($_POST['btnSearch']))
	{
		$prodSearchTxt = addslashes($_POST['searchtxtfld']);	
		$rs_product = $sp->spSelectProductDMList($database, -1, $prodSearchTxt, 0, 0);

	}
	elseif(isset($_GET['searchedTxt']))
	{
		$prodSearchTxt = addslashes($_GET['searchedTxt']);	
		$rs_product = $sp->spSelectProductDMList($database, 0, $prodSearchTxt, 0, 0);
	}
	else
	{
		if ($prodSearchTxt != "")
		{
			$rs_product = $sp->spSelectProductDMList($database, 0, "", 0, 0);
		}
	}

	if(isset($_GET['pID']))
	{
		$pID = $_GET['pID'];
		$rs_productdetails =  $sp->spSelectProductDM($database, $pID, "");		
		if($rs_productdetails->num_rows)
		{
			while($row = $rs_productdetails->fetch_object())
			{								
				$pStatus = $row->Status;
				$pCode = $row->ProductCode;
				$pName = $row->ProductName;
				$pShrtName = $row->ShortName;	
				$pProdTypeID = $row->ProductTypeID;
				$pProdClsID = $row->PMGID;
				$pUOMID = $row->UOMID;
				$pProdLineID = $row->ProductLineID;
				$pUCost = $row->UnitCost;
				$productImage = $row->FileName;
				$launchdate = date("Y-m-d", strtotime($row->LaunchDate));
				$lastpodate = date("Y-m-d", strtotime($row->LastPODate));
				$UnitPrice = $row->UnitPrice;
			}
			$rs_productdetails->close();
		}
		
		$rs_productdetbrand =  $sp->spSelectProductDMDynamicField($database, $pID, 8);
		if($rs_productdetbrand->num_rows)
		{
			while($row = $rs_productdetbrand->fetch_object())
			{
				$pBrandID = $row->ValueID;
			}
			$rs_productdetbrand->close();
		}
		
		$rs_productdetform =  $sp->spSelectProductDMDynamicField($database, $pID, 9);
		if($rs_productdetform->num_rows)
		{
			while($row = $rs_productdetform->fetch_object())
			{
				$pFormID = $row->ValueID;
			}
			$rs_productdetform->close();
		}
		
		$rs_productdetstyle =  $sp->spSelectProductDMDynamicField($database, $pID, 14);
		if($rs_productdetstyle->num_rows)
		{
			while($row = $rs_productdetstyle->fetch_object())
			{
				$pStyleID = $row->ValueID;
			}
			$rs_productdetstyle->close();
		}
		
		$rs_productdetsubform =  $sp->spSelectProductDMDynamicField($database, $pID, 15);
		if($rs_productdetsubform->num_rows)
		{
			while($row = $rs_productdetsubform->fetch_object())
			{
				$pSubFormID = $row->ValueID;
			}
			$rs_productdetsubform->close();
		}
		
		$rs_productdetcolor =  $sp->spSelectProductDMDynamicField($database, $pID, 16);
		if($rs_productdetcolor->num_rows)
		{
			while($row = $rs_productdetcolor->fetch_object())
			{
				$pColorID = $row->ValueID;
			}
			$rs_productdetcolor->close();
		}
		
		$rs_productdetsize =  $sp->spSelectProductDMDynamicField($database, $pID, 17);
		if($rs_productdetsize->num_rows)
		{
			while($row = $rs_productdetsize->fetch_object())
			{
				$pSize = $row->Details;
			}
			$rs_productdetsize->close();
		}
		
		$rs_productdetlife =  $sp->spSelectProductDMDynamicField($database, $pID, 18);
		if($rs_productdetlife->num_rows)
		{
			while($row = $rs_productdetlife->fetch_object())
			{
				$pLife = $row->Details;
			}
			$rs_productdetlife->close();
		}
	}
?>