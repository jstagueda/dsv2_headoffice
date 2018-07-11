<?php
/*
@MODIFIED BY: GINO C. LEABRES
@DATE: 01/03/2012 
*/
	require_once("../initialize.php");

	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	global $database;
	if(isset($_POST['btnUpload']))
	{	
		$uType = addslashes($_POST['cboUploadType']);
		
		//Inventory Transaction
		if ($uType == 1)
		{
			$rs_Inventory = $sp->spSelectInterfaceInventory($database, '2010-10-20');
			$details = "";
			$ctr = 0;
			while($row = $rs_Inventory->fetch_object())
		   	{										 						 			
			   $details .= "\"".str_pad($row->MovementOrder,8)."\" ";	
			   $details .= trim(date('m/d/y',strtotime($row->TxnDate)))." ";
			   $details .= "\"".str_pad($row->TransactionType,8)."\" ";
			   $details .= "\"".str_pad($row->SourceLocation,8)."\" ";	
			   $details .= "\"".str_pad($row->ReceivingLocation,8)."\" ";	
			   $details .= "\"".str_pad($row->Code,18)."\" ";
			   $details .= str_pad($row->Quantity,10)." ";	
			   $details .= "\"".$row->Unit."\" ";
			   $details .= "\"".str_pad($row->DebitAccount,8)."\" ";
			   $details .= "\"".str_pad($row->DebitCostCenter,4)."\" ";
			   $details .= "\"".str_pad($row->CreditAccount,8)."\" ";
			   $details .= "\"".str_pad($row->CreditCostCenter,4)."\" ";
			   $details .= str_pad($row->RunningTotal,10)." ";
			   $details .= "\"".str_pad($row->MovementCode,8)."\" ";
			   $details .= "\"".str_pad($row->RefNo,18)."\" ";
			   $details .= "\"".str_pad($row->IBTAccount,8)."\" ";	
			   $details .= "\"".str_pad($row->IBTCostCenter,4)."\" ";	
			   $details .= "\"".str_pad($row->IBTProject,8)."\" ";	
			   $details .= "\r\n";	
			   $ctr++;			 	
		   	}
			
			$header = "\"".str_pad("CEN",8)."\" "; 		//Branch
			$header .= "10/20/10 ";			//Transaction Date
			$header .= "10/20/10 ";			//Sent Date
			$header .= "Y ";
			$header .= "UNIX CP  ";
			$header .= str_pad("102010CEN.inv",15)." ";
			$header .= str_pad(md5("aag"),30)." ";
			$header .= str_pad("$ctr",4)."\r\n";
			
			$f = fopen("interfaces/102010CEN.inv", "w");
			fwrite($f, $header.$details);
			fclose($f); 
		}
		//ZERO QUANTITY TRANSACTION
		else if ($uType == 2)
		{
			/*
				~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					@Author By: @ Gino C. Leabres;
					@Date:		@ 1.04.2013;
				~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			*/
			$date = date("m/d/Y");
			$query = "SELECT
					    ( SELECT b.Code
						  FROM branchparameter bp
						  INNER JOIN branch b ON b.ID = bp.BranchID) AS Branch,
						  DATE_FORMAT(i.LastModifiedDate, '%m/%d/%Y'),
						  p.Code, 0
					  FROM inventory i
					  INNER JOIN product p ON p.ID = i.ProductID
					  WHERE DATE_FORMAT(i.LastModifiedDate, '%m/%d/%Y') = '$date';";
			$dbresult = $database->execute($query);
			//$dbresult->num_rows;
			$ctr 	 = 0;
			$details = "";
			$branch	 = "";
			$header	 = "";
			while($row = $dbresult->fetch_object()){
			   $branch = $row->Branch;
			   $details .= "\"".str_pad($row->Branch,8)."\" ";	
			   $details .= trim(date('m/d/y',strtotime($row->LastModifiedDate)))." ";
			   $details .= "\"".str_pad($row->Code,8)."\" ";
			   $details .= "\r\n";
			   $ctr++;
			}
			$filename = date("mdY").".".$branch."zqt";
			$header = "\"".str_pad("$branch",8)."\" "; 		//Branch
			$header .= "$date"." ";							//Transaction Date
			//$header .= $date." ";							//Sent Date
			$header .= "yes ";
			$header .= "\"".str_pad("UNIX CP",15)."\" ";
			//$header .= "UNIX CP  ";
			$header .= "\"".str_pad("$filename",15)."\" ";
			$header .= "\"".str_pad("aag",30)."\" ";
			$header .= "\"".str_pad("$ctr",4)."\" "."\r\n";
			
			$location = "../download/interface/zqt/".$filename."";
			$f = fopen($location, "w");
			fwrite($f, $header.$details);
			fclose($f); 
		}
		//RHO
		else if ($uType == 3)
		{
			$dbresult = $sp->spSelectInterfaceInventory($database, '2010-10-20');
			$details = "";
			$ctr = 0;
			while($row = $rs_Inventory->fetch_object())
		   	{
		   		
		   		$ctr++;
		   	}
		   	
		   	$header = "\"".str_pad("CEN",8)."\" "; 		//Branch
			$header .= "10/20/10 ";			//Transaction Date
			$header .= "\r\n";
			
			$f = fopen("interfaces/102010CEN.inv", "w");
			fwrite($f, $header.$details);
			fclose($f); 
		}
		//AR PAYMENT
		else if ($uType == 4)
		{
		
		}
		//DCM
		else if ($uType == 5)
		{
		
		}
		//COLLECTION
		else if ($uType == 6)
		{
		
		}
		//COLLECTION
		else if ($uType == 6)
		{
		
		}
		//EPA
		else if ($uType == 7)
		{
		
		}
		//BSH
		else if ($uType == 8)
		{
		
		}
		
		//BSD
		else if ($uType == 9)
		{
		
		}
	}				
?>