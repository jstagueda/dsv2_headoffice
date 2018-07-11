<?php

	global $database;
	$searchclick = 0;
	
	if(isset($_GET['ID']))
	{
		$bid = $_GET['ID'];
	}
	else
	{
		$bid = 0;
	}
	
	if(isset($_GET['PID']))
	{
		$pgid = $_GET['PID'];
	}
	else
	{
		$pgid = 0;
	}
	
	if (!isset($_POST['txtProductSearch']))
	{
		$prodsearch = "";
	}
	else
	{
		$prodsearch = $_POST['txtProductSearch'];
	}
	
	if (isset($_POST['btnProductSearch']))
	{
		$searchclick = 1;		
	}
	
	if (!isset($_POST['txtPromoSearch']))
	{
		$promosearch = "";
	}
	else
	{
		$promosearch = $_POST['txtPromoSearch'];	
		$rs_prodlist = $sp->spSelectProductListByLevelIDWOKit($database, 3, $prodsearch);		
	}
	
	$rs_promosandprods =  $sp->spSelectPromoCode($database, $promosearch, $bid);
	
	if(isset($_POST['btnAddPromo']))
	{
		try
		{
			$database->beginTransaction();
			foreach ($_POST['chkInclude'] as $key=>$value)
			{
				$arrid = explode("_", $value);
				//echo "PromoID : ".$arrid[0]." ProductID :".$arrid[1]."<br>";
				$sp->spInsertBrochureProduct($database, $_GET['PID'], $arrid[1], $arrid[0]);
				$sp->spUpdatePromoStatusByID($database, $arrid[0], 1);			
			}
							
			$database->commitTransaction();
			$message = "Successfully linked Promos and Products.";
			redirect_to("index.php?pageid=114.1&msg=$message&ID=$bid&PID=$pgid");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			redirect_to("index.php?pageid=114.1&errmsg=$errmsg&ID=$bid&PID=$pgid");
		}
	}

	if(isset($_POST['btnAddProduct']))
	{
		try
		{
			$database->beginTransaction();
			foreach ($_POST['chkInclude2'] as $key=>$value)
			{
				$sp->spInsertBrochureProduct($database, $_GET['PID'], $value, 0);			
			}
				
			$database->commitTransaction();
			$message = "Successfully linked Promos and Products.";		
			redirect_to("index.php?pageid=114.1&msg=$message&ID=$bid&PID=$pgid");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			redirect_to("index.php?pageid=114.1&errmsg=$errmsg&ID=$bid&PID=$pgid");
		}
	}
	
	if(isset($_POST['btnRemove']))
	{
		try
		{
			$database->beginTransaction();
			foreach ($_POST['chkInclude3'] as $key=>$value)
			{
				$arrid = explode("_", $value);
				//delete linking
				$sp->spDeleteBrochureProduct($database, $_GET['PID'], $arrid[1], $arrid[0]);
				//check status of promos
				$sp->spUpdatePromoStatusByID($database, $arrid[0], 0);			
			}
							
			$database->commitTransaction();
			$message = "Successfully deleted Promos and Products.";		
			redirect_to("index.php?pageid=114.1&msg=$message&ID=$bid&PID=$pgid");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			redirect_to("index.php?pageid=114.1&errmsg=$errmsg&ID=$bid&PID=$pgid");
		}
	}
	
	if(isset($_POST['btnSave']))
	{
		$message = "";		
		redirect_to("index.php?pageid=114&msg=$message");		
	}
?>