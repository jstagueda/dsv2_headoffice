<?php
	global $database;
	$code = "";
	$name = "";
	$substitute = "";
	$param = 0;
	$startdate = "";
	$enddate = "";
	$msg = "";

	if (isset($_GET['prodid']))
	{
		$pid = $_GET['prodid'];
	}

	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)) . " -1 month");
	$end = date("m/d/Y",$tmpdate);
	$start = date("m/d/Y",$tmpdate);

	if(isset($_POST['btnSearch']))
	{
		$search = addslashes($_POST['txtfldsearch']);		
	}	
	else
	{
		if(isset($_GET['svalue']))
		{
			$search = 	$_GET['svalue'];
		}
		else
		{
			$search = '';	
		}	
	}

	if ($search != "")
	{
		$rs_prodsubsall = $sp->spSelectProdSubstitute($database,0 ,$search, $param);		
	}
	
	if (isset($_GET['prodid']))
	{
		if ($_GET['subs'] == 2)
		{
			$pid = $_GET['prodid'];
			$rs_prod = $sp->spSelectProdSubstitute($database,$pid,$search, 2);
			if ($rs_prod->num_rows)
			{
				while ($row = $rs_prod->fetch_object())
				{
					 $prodid = $row->pid;
					 $code = $row->code;
					 $name = $row->description;
					 $sohShw = $row->noSubs;
			         $startdate = "";
			         $enddate = "";
				} 
		 	}
		} 
		
		if ($_GET['subs'] == 1)
		{
			unset($_SESSION['subs']);
			$pid = $_GET['prodid'];
	 
			$rs_prod = $sp->spSelectProdSubstitute($database,$pid,$search, 2);
			if ($rs_prod->num_rows)
			{
				while ($row = $rs_prod->fetch_object())
				{
					 $prodid = $row->pid;
					 $code = $row->code;
					 $name = $row->description;
					 $sohShw = $row->noSubs;
			         $startdate = "";
			         $enddate = "";
				} 
		 	}
			 
			$rs_prodsubslist = $sp->spSelectProdSubstitute($database,$pid,$search, 3);
		  	if ($rs_prodsubslist->num_rows)
	       	{
          		while ($row = $rs_prodsubslist->fetch_object())
				{
	            	$prodID = $row->prodid;  
		            $prodsubsID = $row->substituteid;                                                         
		            $prodCode = $row->code;   
		            $prodDesc = $row->description;  
		            $prodSDate = $row->StartDate;   
		            $prodEDate = $row->EndDate;    
		            $prodChck = $row->chck;
		            if($prodChck == 1)
		            {
	            		$prodChck == 'Yes';
		            }       
		            else
		            {
		            	$prodChck == 'No';
		            }
		            
		            $_SESSION['subs'][] = array('ProdCode'=>$prodCode, 'ProdName'=>$prodDesc, 'Check'=>$prodChck, 'SDate'=>$prodSDate, 'EDate'=>$prodEDate, 'ProdID'=>$prodID, 'SubsID'=>$prodsubsID);
		          }  
			}
		}
	}

	if(isset($_SESSION['subs']))	
  	{
  		
  	}
  	else
  	{
  		if(isset($_GET['subs']))	
	  	{
	  		if ($_GET['subs'] != 2)
			{
		  		if (isset($_GET['prodid']))
				{
		  	    	$pid = $_GET['prodid'];
				}
		  		
		  		$rs_prodsubslist = $sp->spSelectProdSubstitute($database,$pid,$search, 3);
			  	if ($rs_prodsubslist->num_rows)
		       	{
	          		while ($row = $rs_prodsubslist->fetch_object())
		          	{
		            	$prodID = $row->prodid;  
			            $prodsubsID = $row->substituteid;                                                         
			            $prodCode = $row->code;   
			            $prodDesc = $row->description;  
			            $prodSDate = $row->StartDate;   
			            $prodEDate = $row->EndDate;    
			            $prodChck = $row->chck;
			            if($prodChck == 1)
			            {
			            	$prodChck == 'Yes';
			            }       
			            else
			            {
			            	$prodChck == 'No';
			            }
			            
			            $_SESSION['subs'][] = array('ProdCode'=>$prodCode, 'ProdName'=>$prodDesc, 'Check'=>$prodChck, 'SDate'=>$prodSDate, 'EDate'=>$prodEDate, 'ProdID'=>$prodID, 'SubsID'=>$prodsubsID);
		          	} 
				}
			}
  		}
	}	 
	
	if (isset($_POST['btnAdd']))
	{
		$tmpCode = $_POST['txtCode'];
		$tmpName = $_POST['txtDesc'];
		
		$tmpProdID = $_POST['hProdid2'];
		$tmpSubsID = $_POST['hdnProd1'];		
		$tmpsdate = strtotime($_POST['txtSDate']);
		$startdate = date("Y-m-d", $tmpsdate);
		$tmpedate = strtotime($_POST['txtEDate']);
		$enddate = date("Y-m-d", $tmpedate);
		
		if(isset($_POST['chckShow']))
		{
			$tmpSOHShw = $_POST['chckShow'];
			if($tmpSOHShw == 'on')
			{
				$tmpSOHShw = 1;
			}
		}
		else
		{
			$tmpSOHShw = 0;
		}
		
		if(isset($_SESSION['subs']))	
		{
			if (sizeof($_SESSION['subs']))
			{
				$subs_list = $_SESSION["subs"];
				$rowalt=0;
				//while($row = $rspromobuyin->fetch_object())
				for ($i=0, $n=sizeof($subs_list); $i < $n; $i++ ) 
				{
					if($tmpSubsID == $subs_list[$i]['SubsID'])
					{
						$msg="Substitute is already on the list.";
						redirect_to("./index.php?pageid=111&prodid=$tmpProdID&subs=2&msg=$msg");
					}
				}
			}
		}
       
		$rs_prodsubsadd = $sp->spSelectProdSubstitute($database,$tmpSubsID,$search, 2);
	  	if ($rs_prodsubsadd->num_rows)
       	{
      		while ($row_add = $rs_prodsubsadd->fetch_object())
          	{                                                       
            	$tmpCode = $row_add->code;   
	            $tmpName = $row_add->description;
          	} 
		}		
		
		//add to session
 		$_SESSION['subs'][] = array('ProdCode'=>$tmpCode, 'ProdName'=>$tmpName, 'Check'=>$tmpSOHShw, 'SDate'=>$startdate, 'EDate'=>$enddate, 'ProdID'=>$tmpProdID, 'SubsID'=>$tmpSubsID);
		redirect_to("./index.php?pageid=111&prodid=$tmpProdID&subs=2");
	}
	
	if(isset($_POST['btnRemove']))		
	{	
		$tmpProdID = $_POST['hProdid2'];
		//$subs_list = $_SESSION['subs'];		
		$tmp_subslist = $_SESSION['subs'];
		$n=sizeof($tmp_subslist);
		if(isset($_POST['chkInclude']))
		{
			foreach($_POST['chkInclude'] as $key => $value)
			{			
				$prodid = "hprodid$value";
				$subsid = "hsubsid$value";
					
				for ($i = 0; $i < $n; $i++)
				{					
					if (isset($tmp_subslist[$i]['ProdID']))
					{		
						if (($_POST['hprodid'.$value] == $tmp_subslist[$i]['ProdID']) && ($_POST['hsubsid'.$value] == $tmp_subslist[$i]['SubsID'])) 
						{									
							unset($tmp_subslist[$i]);
							break;
						}
					}
				}
			}
			unset($_SESSION['subs']);
		
			for ($i = 0; $i < $n; $i++)
			{
				if (isset($tmp_subslist[$i]['ProdID']))
				{	 
					$_SESSION['subs'][] = array('ProdCode'=>$tmp_subslist[$i]['ProdCode'], 'ProdName'=>$tmp_subslist[$i]['ProdName'], 'Check'=>$tmp_subslist[$i]['Check'], 'SDate'=>$tmp_subslist[$i]['SDate'], 'EDate'=>$tmp_subslist[$i]['EDate'], 'ProdID'=>$tmp_subslist[$i]['ProdID'], 'SubsID'=>$tmp_subslist[$i]['SubsID']);
				}				
			}
		}
		redirect_to("./index.php?pageid=111&prodid=$tmpProdID&subs=2");
	}
	
	if(isset($_POST['btnCancel']))		
	{
		unset($_SESSION['subs']);
	}
	
	if(isset($_POST['btnSave']))		
	{
		try
		{
			$database->beginTransaction();
			$tmpProdID = $_POST['hProdid2'];
			
			$rs_deleteExist = $sp->spDeleteExistProdSubs($database, $tmpProdID);
			if (!$rs_deleteExist)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			
			if(isset($_SESSION['subs']))	
			{
				if (sizeof($_SESSION['subs']))
				{
					$subs_list = $_SESSION["subs"];
		
					for ($i=0, $n=sizeof($subs_list); $i < $n; $i++ ) 
					{
						$chk = $subs_list[$i]['Check'];
						$sDate = strtotime($subs_list[$i]['SDate']);
						$eDate = strtotime($subs_list[$i]['EDate']);
						$prodid = $subs_list[$i]['ProdID'];
						$prodsubsid = $subs_list[$i]['SubsID'];
						$startdate = date("Y-m-d", $sDate);
						$enddate = date("Y-m-d", $eDate);
	
						//save
						$rs_save = $sp->spInsertProductSubstitute($database, $prodid, $prodsubsid, $chk, $startdate, $enddate);
						if (!$rs_save)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}
				}
				
				unset($_SESSION['subs']);		
				$database->commitTransaction();  						
				$message = "Successfully saved record.";
				redirect_to("./index.php?pageid=111&subs=1&msg=$message");
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
		  	$message = $e->getMessage();
		    redirect_to("./index.php?pageid=111&subs=0&msg=$message");
		}	
	}
?>