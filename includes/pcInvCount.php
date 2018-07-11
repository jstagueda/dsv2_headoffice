<?php

	require_once("../initialize.php");

//$session->unset_sessionarrays(); exit();

//echo sizeof($_SESSION['prodid']); exit();
if(!isset($_SESSION['prodid']))
{
	
	$_SESSION['prodid']=array();
    $_SESSION['prodcode']=array();
    $_SESSION['prodname']=array();
    $_SESSION['soh']=array();
    $_SESSION['uomid']=1;
    $_SESSION['uom']=array();
    $_SESSION['qty']=array();
    $_SESSION['resid']=array();
    $_SESSION['res']=array();
    $_SESSION['unitprice']=array();
}
	
	
if(sizeof($_SESSION['prodid']) == 0)
{
	$_SESSION['prodid']=array();
    $_SESSION['prodcode']=array();
    $_SESSION['prodname']=array();
    $_SESSION['soh']=array();
    $_SESSION['uomid']=1;
    $_SESSION['uom']=array();
    $_SESSION['qty']=array();
    $_SESSION['resid']=array();
    $_SESSION['res']=array();
    $_SESSION['unitprice']=array();
}

if(isset($_POST['btnClear']))
{
	$_SESSION['prodid']=array();
    $_SESSION['prodcode']=array();
    $_SESSION['prodname']=array();
    $_SESSION['soh']=array();
    $_SESSION['uomid']= 1;
    $_SESSION['uom']=array();
    $_SESSION['qty']=array();
    $_SESSION['resid']=array();
    $_SESSION['res']=array();
    $_SESSION['unitprice']=array();
    redirect_to("../index.php?pageid=28");
}

	if(isset($_POST['btnRemoveInv']))
	{
		
	     $arrprodid = $_SESSION['prodid'];
         $arrprodcode = $_SESSION['prodcode'];
         $arrprodname = $_SESSION['prodname'];
         $arrsoh = $_SESSION['soh'];
         //$arruomid = $_SESSION['uomid'];
         $arruom = $_SESSION['uom'];
         $arrqty = $_SESSION['qty'];
         $arrresid = $_SESSION['resid'];
         $arrres = $_SESSION['res'];
		
		$arrQty = sizeof($_POST['hdnIID']);
		//$session->unset_adjustment();
		
		 $_SESSION['prodid']=array();
         $_SESSION['prodcode']=array();
         $_SESSION['prodname']=array();
         $_SESSION['soh']=array();
         $_SESSION['uomid']=1;
         $_SESSION['uom']=array();
         $_SESSION['qty']=array();
         $_SESSION['resid']=array();
         $_SESSION['res']=array();
         
         
         $test = 0;
         		
		for($i = 0; $i < $arrQty; $i++)
		{		
			$ismarked = false;    
		  	if ($_POST['chkIID'.$i] == "on") 
		  	{	   		              		  	  		  		  	   
		  		$ismarked = true; 
		  	}	       
		  		
		  	  
		  	  if(!$ismarked)
		  	   {	
		  	   
                array_push($_SESSION['prodid'],$arrprodid[$i]);
				array_push($_SESSION['prodcode'],$arrprodcode[$i]);
				array_push($_SESSION['prodname'],$arrprodname[$i]);
				array_push($_SESSION['soh'],$arrsoh[$i]);
				//array_push($_SESSION['uomid'],$arruomid[$i]);
				
				array_push($_SESSION['qty'],$arrqty[$i]);
				array_push($_SESSION['resid'],$arrresid[$i]);
				
				array_push($_SESSION['uom'],$arruom[$i]);						     				      
						
				array_push($_SESSION['res'],$arrres[$i]);					       
					
			
		  	  }
				
		  				  
		  } 
		  	
		  	
		
	}

	if(isset($_POST['btnAdd']))
	{
						
		$arrQty = sizeof($_POST['hdnProductID']);
		//$session->unset_adjustment();
			
	
		
		for($i = 0; $i < $arrQty; $i++)
		{		
			$hasprod = false;    
		  	if ($_POST['txtQuantity'][$i] != "") 
		  	{	   		
               
		  		for($a = 0; $a < sizeof($_SESSION['prodid']); $a++)
		  		{
		  		  if($_POST['hdnProductID'][$i] == $_SESSION['prodid'][$a])
		  		  {   		  		  	   
		  		  	   $hasprod = true;  
		  		       $_SESSION['qty'][$a] = (int)$_POST['txtQuantity'][$i] + (int)$_SESSION['qty'][$a];	
		  		      //echo $_SESSION['qty'][$a]; exit();
		  		       
		  		  }
		  		}
		  		
		  	  if(!$hasprod)
		  	   {	
		  	   
                array_push($_SESSION['prodid'],$_POST['hdnProductID'][$i]);
				array_push($_SESSION['prodcode'],$_POST['hdnProductCode'][$i]);
				array_push($_SESSION['prodname'],$_POST['hdnProductName'][$i]);
				array_push($_SESSION['soh'],$_POST['hdnSOH'][$i]);
				//array_push($_SESSION['uomid'],$_POST['cboUOM'][$i]);
				array_push($_SESSION['unitprice'],$_POST['hdnUnitPrice'][$i]);
				array_push($_SESSION['qty'],$_POST['txtQuantity'][$i]);
				array_push($_SESSION['resid'],$_POST['cboReasons'][$i]);
				
		  	   	
				              
				$rs_uom = $sp->spSelectUOMbyID(1);
				if($rs_uom->num_rows)
				{
				      while($row = $rs_uom->fetch_object())
					  {   
					      array_push($_SESSION['uom'],$row->Name);						     				      
					  }
				}
				
                   

				
				$rs_reason = $sp->spSelectReasonbyID($_POST['cboReasons'][$i]);
				if($rs_reason->num_rows)
				{
				      while($row = $rs_reason->fetch_object())
					  {
					      array_push($_SESSION['res'],$row->Name);					       
					  }
				}
				
				
				
		  	  }
						  				  
		  	} 
		  			  	
		}
		
	}
	
	if (isset($_GET['refno']))	
	{

		$rs_checkdocno = $sp->spCheckInvCountDocNo($_GET['docno']);
			
		if($rs_checkdocno->num_rows)
		{
			while($row = $rs_checkdocno->fetch_object())
			{
				$message = "Document number already exist.";
			    redirect_to("../index.php?pageid=2&message=$message");		
			}
		}
		
	
	    $refno = $_GET['refno'];
	    $docno = $_GET['docno'];
	  //  $mid = $_GET['mid'];
		$transactiondate = $_GET['tdate'];
		$remarks = $_GET['remarks'];
		$createdby = $_SESSION['emp_id'];
		$locationid = 1;
	
			$dmydate = date('Y-m-d',strtotime($_GET['tdate']));
		//check if document number already exists
	
	
			$rs_ICID = $sp->spInsertInvCount($refno, 5,$locationid, $docno, $dmydate, $remarks, $createdby);
		  
			if($rs_ICID->num_rows)
			{  
				while($row = $rs_ICID->fetch_object())
				{
					$InvCID = $row->ID;
				}
			}			
					
			
			$sort = 1;
			for ($i = 0, $n = sizeof($_SESSION['prodid']); $i < $n; $i++ ) 
			{			
                $v_prodid = $_SESSION['prodid'][$i];
                $v_uomid = 1;
                $v_prevbal = $_SESSION['soh'][$i];
                $v_createdqty = $_SESSION['qty'][$i];
                $v_resid = $_SESSION['resid'][$i]; 
				$v_tpiCountag = 1;
				 				
				$affected_rows = $sp->spInsertInvCountDetails($InvCID, $v_prodid, $v_uomid, $v_prevbal, $v_createdqty, $v_resid, $v_tpiCountag);
				$sort = $sort + 1;
			}
			
			$_SESSION['prodid']=array();
            $_SESSION['prodcode']=array();
            $_SESSION['prodname']=array();
            $_SESSION['soh']=array();
            $_SESSION['uomid']=1;
            $_SESSION['uom']=array();
            $_SESSION['qty']=array();
            $_SESSION['resid']=array();
            $_SESSION['res']=array();
			
		    
			$message = "Successfully created Inventory Count.";
			redirect_to("../index.php?pageid=28&message=$message");		
		
	}


	
	redirect_to("../index.php?pageid=28");
?>