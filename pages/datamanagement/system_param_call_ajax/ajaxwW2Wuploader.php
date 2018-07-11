
<?php

	include "../../../initialize.php";
	
	include IN_PATH.DS."scproductlistuploader.php";
	
	global $database;
	global $session;
    $userid = $session->user_id;
	

if(!($_FILES['file']['size']==0))
{	
	 try 
	 {
		$database->beginTransaction(); 
		
		
		$database->execute(" CREATE TEMPORARY TABLE IF NOT EXISTS tmpinvcount
							 (
							     `RefID`           INT(10) NULL,
								 `branchid`        INT(10) NULL,
								 `DocumentNo`      VARCHAR(50) NULL ,
								 `TransactionDate` DATE,
								 `ProductID`       INT(10) NULL,
								 `UnitTypeID`      INT(10) NULL,
								 `LocationID`      INT(10) NULL,
								 `WarehouseID`     INT(10) NULL,
								 `CHfreezeQty`     INT(10) NULL,
								 `CHCreatedQty`    INT(10) NULL,
								 `HOfreezeQty`     INT(10) NULL,
								 `productCode`     VARCHAR(50) NULL ,
								 `RegularPrice`    DECIMAL(14,4) NOT NULL DEFAULT 0.00,
								 `Description`     VARCHAR(50) NULL ,
								 `HOLocation`      VARCHAR(50) NULL 
							  )
					      ");	
						  
		$MainBRCode = strtoupper($_POST['branchName']);
		$date       = $_POST['txtStartDate'];
		$HeaderDate = date("Y-m-d", strtotime($date));
		
		$branchmainq = $database->execute(" SELECT *
											FROM branch
											WHERE branch.code = '$MainBRCode'  
										  ");  #validate branch code
		if(!$branchmainq->num_rows > 0 )
		{   
		   $valid = 'no';
		   $errorlist = 'Invalid Branh Code';
		}
		else
		{
			while($br=$branchmainq->fetch_object())
			{
				$brid = $br->ID;
			}
		}

			
		if($_FILE['file2']['error'] == 0)  //checks if theres an error and continues if there is none
	    {
			$filetmp      = $_FILES['file2']['tmp_name']; //this will cut the extension from the filename
			$filename     = $_FILES['file2']['name'];

			$withoutExt   = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$withoutExt_b = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$withoutExt   = $withoutExt.uniqid();
			$ext          = pathinfo($filename, PATHINFO_EXTENSION);
			$userid       = $_SESSION['user_id'];
			
			#file extension validation
			if($ext != 'csv')
			{
			   $valid = 'no';	
			   $errorlist = 'Invalid File';
			}
									  
			if($valid != 'no')
			{
			  
			   $fileData     = trim(file_get_contents($_FILES['file2']['tmp_name']));
			   $rows         = explode("\n", $fileData);
		 	   $first_row    = true;
			   $totalRowctr  = 0;
			   $totalqty     = 0;
               
               foreach ($rows as $row)  #file validation 
			   {
				  
				   $xdata   = csvstring_to_array(trim($row), ',', '"', '\r\n');
				   $totalRowctr = $totalRowctr + 1;
				   
				   $field1  = str_replace('"','',trim($xdata[0]));   $field9  = str_replace('"','',trim($xdata[8]));
				   $field2  = str_replace('"','',trim($xdata[1]));   $field10 = str_replace('"','',trim($xdata[9]));
				   $field3  = str_replace('"','',trim($xdata[2]));   $field11 = str_replace('"','',trim($xdata[10]));
				   $field4  = str_replace('"','',trim($xdata[3]));   $field12 = str_replace('"','',trim($xdata[11]));
				   $field5  = str_replace('"','',trim($xdata[4]));   $field13 = str_replace('"','',trim($xdata[12]));
				   $field6  = str_replace('"','',trim($xdata[5]));   $field14 = str_replace('"','',trim($xdata[13]));
				   $field7  = str_replace('"','',trim($xdata[6]));   $field15 = str_replace('"','',trim($xdata[14]));
				   $field8  = str_replace('"','',trim($xdata[7]));   $field16 = str_replace('"','',trim($xdata[15]));
				   $field17 = str_replace('"','',trim($xdata[16]));
                    
				   if($totalRowctr == 2)
				   {
					  $freezedate = date("Y-m-d", strtotime($field16)) ; // date 
					  if($freezedate !=$HeaderDate )
					  {
						   $errorlist = $errorlist.'Invalid Date:'.$freezedate.':'.$HeaderDate ;
					  }
					  else
					  {  //validate if date is within calendar period 
				         $withsetup = 0;
				         $calendarq = $database->execute("  SELECT * 
															FROM calendar c
															WHERE c.CalendarTypeID IN (1,2)
															AND '$freezedate' BETWEEN c.StartDate AND c.EndDate
															#AND  c.Branch IN ($brid)												
													    ");
					     if(!$calendarq->num_rows > 0)
					     {
							 $errorlist = $errorlist.'Invalid Date, please check calendar setup:'.$freezedate.':'.'\n' ;
						 }
                         else
						 {
							 while($cq=$calendarq->fetch_object())
							 {
								 $branchcode = '('.$cq->Branch.')';
								 $calendarq2 = $database->execute("  SELECT * 
																	FROM calendar c
																	WHERE c.CalendarTypeID IN (1,2)
																	AND '$freezedate' BETWEEN c.StartDate AND c.EndDate
																	AND $brid in $branchcode										
																");
								 if($calendarq2->num_rows > 0)
								 {
									 $withsetup = 1;
								 }
							 }
						 }

                         if( $withsetup != 1)
						 {
							 $errorlist = $errorlist.'Invalid Date, please check calendar setup:'.$freezedate.':'.'\n' ;
						 }							 
					  }
				   }
				  
				   if($totalRowctr == 5)
				   {
					  $cyclecountno = $field3; 
					  if($cyclecountno == '')
					  {
						  $errorlist = $errorlist.'Invalid Cycle Count Number:'.$cyclecountno.'\n';
					  }
				   }
				   if($totalRowctr == 6 )
				   {				  
					  $description = $field3; 
				   }
				    
				   if($totalRowctr == 7)
				   { // create header 
			           $invcycq = $database->execute(" SELECT *
													   FROM  inventorycountch
													   WHERE inventorycountch.BranchID = $brid
														 and inventorycountch.RefID    = $cyclecountno 
                                                       	 and statusid = 7													
													 ");
					   if($invcycq->num_rows > 0)
					   {
						   $errorlist = $errorlist.'Transaction Already Loaded and Posted with Cycle Count Number:'.$cyclecountno.'\n' ;
					   }
					   
					   $invcycq2 = $database->execute(" SELECT *
													    FROM  inventorycountch
													    WHERE inventorycountch.BranchID = $brid
													      and inventorycountch.RefID    <> $cyclecountno 
                                                        	 and statusid = 6													
													 ");
					   if($invcycq2->num_rows > 0)
					   {
						   $errorlist = $errorlist.'Branch has pending transaction :'.'\n' ;
					   }
					   
					   //check if with previously confirmed inventory count ch for the current wall to wall period
                       $invcycq3 = $database->execute(" SELECT *
														FROM  inventorycountch
														WHERE inventorycountch.BranchID = 10
														AND   '$freezedate'             = DATE(inventorycountch.TransactionDate) 
														AND   inventorycountch.StatusID = 7
                                                        and   inventorycountch.RefID    <> $cyclecountno 														
													 ");
					   if($invcycq3->num_rows > 0)
					   {
						   $errorlist = $errorlist.'Invalid Transaction. Meron nang confirmed transaction for current wall to wall :'.$cyclecountno.'\n' ;
					   }					   
				   }					    
				   
				    
				   
				   if($totalRowctr > 7 )
				   {
						   $validline = 'yes'; 
						   $productid = 0; 
						   $productq = $database->execute(" SELECT p.id PID, p.UnitTypeID UnitTypeID, pp.UnitPrice UnitPrice, p.description, 
					                                               p.GLClass GLClass, p2.code productline , p.code pcode , p.2nd_itemnumber itemnumberjde
															FROM product p
															INNER JOIN product p2 ON p2.ID = p.ParentID
															INNER JOIN productpricing pp ON pp.ProductID = p.id 
															WHERE p.code = '$field1' 
														 ");   #productcode validation
						   if(!$productq->num_rows > 0 )
						   {
							  $validline = 'no';  
							  $errorlist = $errorlist.'Invalid Product Code:'.$field1.': Line Counter:'.$totalRowctr.':'.$filename.'\n';
						   }
						   else
						   {
							   while($p=$productq->fetch_object())
							   {
							   	  $productid  = $p->PID;
								  $UnitTypeID = $p->UnitTypeID;
								  $UnitPrice  = $p->UnitPrice;
								  $prddesc    = $p->description;
								  
								   if($p->GLClass == '')
								   {
									  $validline = 'no';
									  $errorlist = $errorlist.'Invalid GL Class:'.$p->pcode.': Line Counter:'.$totalRowctr.'\n'; 
								   }
								   
								   $PL = $p->productline;
								   
								   if(strlen($PL) > 3)
								   {
									  $validline = 'no';
									  $errorlist = $errorlist.'Invalid Product Line:'.$p->pcode.': Line Counter:'.$totalRowctr.'\n';  
								   }
								   if($PL == '')
								   {
									  $validline = 'no';
									  $errorlist = $errorlist.'Invalid Product Line:'.$p->pcode.': Line Counter:'.$totalRowctr.'\n'; 
								   }
								   
								   if($p->GLClass == '')
								   {
									  $validline = 'no';
									  $errorlist = $errorlist.'Invalid GL Class:'.$p->pcode.': Line Counter:'.$totalRowctr.'\n'; 
								   }
								   
								   if($p->itemnumberjde == '')
								   {
									  $validline = 'no';
									  $errorlist = $errorlist.'Product Code does not exist:'.$p->pcode.': Line Counter:'.$totalRowctr.'\n'; 
								   }
								   
								   
							   
							   }
						   }
						  
						   if($field9 != '3505001') // branch plant validation
						   {
							  $validline = 'no';  
							  $errorlist = $errorlist.'Invalid Branch Plant:'.$field9.': Line Counter:'.$totalRowctr.'\n'; 
						   }
                            
						   $locationq = $database->execute(" SELECT w.id WID , bpd.ID LOCID
															 FROM branchplant_details bpd
															 INNER JOIN warehouse w ON w.code = bpd.locationtype
															 WHERE bpd.branchplant = '$field9'
															 AND branchid = $brid
															 AND bpd.location = '$field10'  
														  ");
						   if(!$locationq->num_rows > 0 )
						   {
							  $validline = 'no';
						      $errorlist = $errorlist.'Invalid Location:'.$field10.': Line Counter:'.$totalRowctr.'\n'; 
						   }
						   else
						   {
						      while($loc=$locationq->fetch_object())
							  {
							     $WID    = $loc->WID; 
							     $LOCID  = $loc->LOCID;   
							  }
						   }
						   
						   if($field16 == '')
						   {
							   $field16 = 0;
						   }
						   
						   if($validline == 'yes')
						   {
							   $totalqty = $totalqty + $field16;
							   createtemptable('1',$cyclecountno,$brid,$productid,$WID,$txndate,$description,$UnitTypeID,$LOCID,0,0,$field16,$field1,$UnitPrice,$prddesc,$field13);
						   }
						   
						   //echo $field1.':'.$field2.':'.$field3.':'.$field4.':'.$field5.':'.$field6.':'.$field7.':'.$field8.':'.$field9.':'.$field10.':'.$field11.':'.$field12.':'.$field13.':'.$field14.':'.$field15.':'.$field16.'<br>';
					 
				   }
				   
			   }
			}
				 			
        }
	    
		
		
		if($_FILE['file']['error'] == 0)  //checks if theres an error and continues if there is none
		{
			$userid = $_SESSION['user_id'];
			
			$filetmp    = $_FILES['file']['tmp_name']; //this will cut the extension from the filename
			$filename   = $_FILES['file']['name'];
			
			$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$withoutExt_a = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$withoutExt = $withoutExt.uniqid();
			$csv = '/var/www/html/TPI-DATA/data/'.$withoutExt.'.csv';
			
			$ext     = pathinfo($filename, PATHINFO_EXTENSION);
			$to      = strlen($withoutExt_a) - 10;
			$brcode  = strtoupper(substr($withoutExt_a,7,$to));
            $filextn = $brcode.'los';  
			 
			#validate branch code
			$branchq = $database->execute(" SELECT *
										   FROM branch
										   WHERE branch.code = '$brcode'  
										 ");
			if(!$branchq->num_rows > 0 )
			{
				$valid = 'no';
			    $errorlist = 'Invalid Branh Code';
			}
			
			if($brcode != $MainBRCode)
			{
				$valid = 'no';
			    $errorlist = 'Invalid Branh Code';
			}
			
			#validate Freeze Date
			
			#file extension validation
			if($ext != $filextn)
			{
			   $valid = 'no';	
			   $errorlist = $errorlist.','.'Invalid File';
			}
									  
			if($valid != 'no')
			{
			   $fileData  = trim(file_get_contents($_FILES['file']['tmp_name']));
			   $rows      = explode("\n", $fileData);
		 	   $first_row = true;
			   $totalqty2 = 0;
			   $counter   = 0;
              
               #file validation 
               foreach ($rows as $row)
			   {
				   $counter    = $counter + 1;
				   $xdata      = explode(' ',trim($row));
				   
				   $field1 = str_replace('"','',trim($xdata[0]));
				   $field2 = str_replace('"','',trim($xdata[1]));
				   $field3 = str_replace('"','',trim($xdata[2]));
				   $field4 = str_replace('"','',trim($xdata[3]));
				   $field5 = str_replace('"','',trim($xdata[4]));
				   $field6 = str_replace('"','',trim($xdata[5]));
				   
				   //echo $field1.':'.$field2.':'.$field3.':'.$field4.':'.$field5.':'.$field6.'<br>';
				   if($counter == 1)
				   {
					   if($field1 != $brcode )
					   {
			               $errorlist = $errorlist.'Invalid Branh Code:'.$field1;
					   }
					   if($field6 != $withoutExt_a )
					   {
			               $errorlist = $errorlist.'Invalid File Name:'.$field6;
					   }
				   }
				   else
				   {
					   $validline = 'yes';
					   if($field1 != $brcode )
					   {
			               $errorlist = $errorlist.'Invalid Branh Code:'.$field1;
					   }
					   
					   $productq2 = $database->execute(" SELECT p.id PID, p.UnitTypeID UnitTypeID, pp.UnitPrice UnitPrice, p.description, 
					                                            p.GLClass GLClass, p2.code productline , p.code pcode , p.2nd_itemnumber itemnumberjde
													     FROM product p
														 INNER JOIN product p2 ON p2.ID = p.ParentID
														 INNER JOIN productpricing pp ON pp.ProductID = p.id 
														 WHERE p.code = '$field3' 															
													  ");   #productcode validation
					   if(!$productq2->num_rows > 0 )
					   {
						  if($field6 != 0 || $field4 != 0)
						  {
							  $errorlist = $errorlist.'Invalid Product Code:'.$field3.': Line Counter:'.$counter.'\n';
						  }
					   }
					   else
					   {
						   while($p2=$productq2->fetch_object())
						   {
							  $productid  = $p2->PID;
							  $UnitTypeID = $p2->UnitTypeID;
							  $UnitPrice  = $p2->UnitPrice;
							  $prddesc    = $p2->description;
							  
							   if($p2->GLClass == '')
							   {
								  if($field6 != 0 || $field4 != 0)
								  {
								     $validline = 'no';
						             $errorlist = $errorlist.'Invalid GL Class:'.$p2->pcode.': Line Counter:'.$counter.'\n'; 
								  }
							   }
							   $PL = $p2->productline;
							   
							   if(strlen($PL) > 3)
							   {
								  if($field6 != 0 || $field4 != 0)
								  {
								     $validline = 'no';
								     $errorlist = $errorlist.'Invalid Product Line:'.$p2->pcode.': Line Counter:'.$counter.'\n';  
								  }
							   }
							   if($PL == '')
							   {
								  if($field6 != 0 || $field4 != 0)
								  {
								      $validline = 'no';
								      $errorlist = $errorlist.'Invalid Product Line:'.$p2->pcode.': Line Counter:'.$counter.'\n'; 
								  }
							   }
							   
							   if($p2->itemnumberjde == '')
							   {
								  if($field6 != 0 || $field4 != 0)
								  {
								      $validline = 'no';
								      $errorlist = $errorlist.'Product Code does not exist:'.$p2->pcode.': Line Counter:'.$counter.'\n'; 
								  }
							   }
						   }
					   }
					   
					   #1BGO,1BLY,1BLA,1ZMB,1OLG
					   $field1 = $field1 == 'BGO' ? '1BGO' : $field1;
					   $field1 = $field1 == 'BLY' ? '1BLY' : $field1;
					   $field1 = $field1 == 'OLG' ? '1OLG' : $field1;
					   $field1 = $field1 == 'ZMB' ? '1ZMB' : $field1;
					   $field1 = $field1 == 'BLA' ? '1BLA' : $field1;
					    
					   $loc = $field2 == 'WH' ? $field1 : 'DG-'.$field1 ;
					   
					   
					   
					   $locationq2 = $database->execute("  SELECT w.id WID , bpd.ID LOCID
														   FROM branchplant_details bpd
														   INNER JOIN warehouse w ON w.code = bpd.locationtype
														   WHERE bpd.branchplant = '3505001'
														   AND branchid = $brid
														   AND bpd.location = '$loc'   
														   
														  ");
					   if(!$locationq2->num_rows > 0 )
					   {
						   $validline = 'no';
						   $errorlist = $errorlist.'Invalid Location:'.$field2.': Line Counter:'.$counter.'\n'; 
					   }
					   else
					   {
						   while($loc2=$locationq2->fetch_object())
						   {
							  $WID    = $loc2->WID; 
							  $LOCID  = $loc2->LOCID;   
						   }
					   }
					   
                       if($field4 =='' || $field5 == '' || $field6 == '')
					   {
						   $validline = 'no';
						   $errorlist = $errorlist.'Invalid Numeric Value:';
					   }
					   
					   if($validline == 'yes')
					   {
						   $totalqty2 = $totalqty2 + $field6;
						   createtemptable('2',$cyclecountno,$brid,$productid,$WID,$txndate,$description,$UnitTypeID,$LOCID,$field6,$field4,0,$field3 ,$UnitPrice,$prddesc,'');
					   }
				   }
			   }
			}		
       }
	    

	   if($errorlist == '')
	   {
		   /* create header */
		   
		  $headerq = $database->execute(" SELECT ID MID
										  FROM  inventorycountch
										  WHERE inventorycountch.BranchID = $brid
										    and inventorycountch.RefID = $cyclecountno 												
									");
		  if($headerq->num_rows > 0)
		  {
			 while($h=$headerq->fetch_object())
			 {
			     $MID  = $h->MID;	 
			 }		  
	      }
          else
		  {
			$database->execute(" 
		                        INSERT INTO inventorycountch        
										SET inventorycountch.BranchID           = $brid,
											inventorycountch.Changed            = 1,
											inventorycountch.CreatedBy          = $userid,
											inventorycountch.ConfirmedBy        = $userid,
											inventorycountch.DocumentNo         = '$description', 
											inventorycountch.EnrollmentDate     = now(),
											inventorycountch.LastModifiedDate   = now(),
											inventorycountch.MovementTypeID     = 6 ,
											inventorycountch.RefID              = $cyclecountno,
											inventorycountch.Remarks            = '$description:$cyclecountno',
											inventorycountch.StatusID           = 6,
											inventorycountch.TransactionDate    = '$HeaderDate', 
											inventorycountch.WarehouseID        = 1
		                      "); 
							  
			$MID = $database->execute("SELECT MAX(ID) MID FROM inventorycountch ")->fetch_object()->MID;				  
		  }			 			  
		   /* create details */
		   
		   $createtemptableq = $database->execute(" SELECT c.productCode,c.Description,c.WarehouseID, c.CHfreezeQty , c.CHCreatedQty, c.HOfreezeQty, 
                                                           c.hoLocation  hoLocation , c.locationid locationid , c.productid productid , c.RegularPrice RegularPrice,
                                                           c.UnitTypeID UnitTypeID	, c.WarehouseID WarehouseID													   
												    FROM tmpinvcount c 
											 ");
		   if($createtemptableq->num_rows > 0 )
		   {
			   
			  while($lq=$createtemptableq->fetch_object())
			  {
	             $ADJ = $lq->CHCreatedQty - $lq->HOfreezeQty ;
				 
                 $detailq = $database->execute(" SELECT dtl.ID DTLID
												  FROM  inventorycountchdetails dtl
												  WHERE dtl.InventoryCountID  = $MID   
												    and dtl.ProductID         = $lq->productid 
                                                    and dtl.WarehouseID       = $lq->WarehouseID													
									           ");
			     if($detailq->num_rows > 0)
			     {
               			$database->execute(" update inventorycountchdetails 
						                     set inventorycountchdetails.CHCreatedQty    = $lq->CHCreatedQty,
												 inventorycountchdetails.CHfreezeQty     = $lq->CHfreezeQty,
												 inventorycountchdetails.HOFreezeQty     = $lq->HOfreezeQty,
												 inventorycountchdetails.RegularPrice    = $lq->RegularPrice,
												 inventorycountchdetails.AdjustmentQTY   = $ADJ 
											 WHERE inventorycountchdetails.InventoryCountID  = $MID   
											   and inventorycountchdetails.ProductID         = $lq->productid 
                                               and inventorycountchdetails.WarehouseID       = $lq->WarehouseID													
									     ");					  
			    }
				else
				{
					$details = $database->execute("	INSERT INTO inventorycountchdetails
													SET inventorycountchdetails.Changed         = 1,
														inventorycountchdetails.CHCreatedQty    = $lq->CHCreatedQty,
														inventorycountchdetails.CHfreezeQty     = $lq->CHfreezeQty,
														inventorycountchdetails.EnrollmentDate  = now(),
														inventorycountchdetails.HOFreezeQty     = $lq->HOfreezeQty,
														inventorycountchdetails.HOLocation      = '$lq->hoLocation',
														inventorycountchdetails.InventoryCountID= $MID,
														inventorycountchdetails.LastModifiedDate= now(),
														inventorycountchdetails.AdjustmentQTY   = $ADJ ,
														inventorycountchdetails.LocationID      = $lq->WarehouseID,
														inventorycountchdetails.ProductID       = $lq->productid,
														inventorycountchdetails.RegularPrice    = $lq->RegularPrice,
														inventorycountchdetails.UnitTypeID      = $lq->UnitTypeID,
														inventorycountchdetails.WarehouseID     = $lq->WarehouseID
											     ");
				}
				
				
				 //echo $lq->productCode.':'.$lq->Description.':'.$lq->WarehouseID.':'.$lq->CHfreezeQty.':'.$lq->CHfreezeQty.':'.$lq->HOfreezeQty.'<br>';
			  }
		   } 
		   
		   echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " > 
		               <tr class="trheader" >
							<td width="50%">Cycle Count Number</td>
							<td width="50%">'.$cyclecountno.'</td>
						</tr>
		                <tr class="trheader" >
							<td width="50%">Description</td>
							<td width="50%">'.$description.'</td>
						</tr>
                        <tr class="trheader" >
							<td width="50%">JDE File</td>
							<td width="50%" align = left >'.$withoutExt_b.'</td>
						</tr>
					    <tr class="trheader" >
							<td width="50%">Total Line Detail/s</td>
							<td width="50%" align = left >'.($totalRowctr - 7).'</td>
						</tr>
						<tr class="trheader" >
							<td width="50%">Total Quantity</td>
							<td width="50%" align = left >'.$totalqty.'</td>
						</tr>		   
				        <tr class="trheader" >
							<td width="50%">LOS File</td>
							<td width="50%" align = left >'.$withoutExt_a.'</td>
						</tr>
					    <tr class="trheader" >
							<td width="50%">Total Line Detail/s</td>
							<td width="50%" align = left >'.($counter - 1).'</td>
						</tr>
						<tr class="trheader" >
							<td width="50%">Total Quantity</td>
							<td width="50%" align = left >'.$totalqty2.'</td>
						</tr>
					 </table>';			
		   
		   $database->commitTransaction();
		   $result['Success'] = 1;
		   
		   echo '<script language="javascript">';
		   echo 'alert("Done Processing.")';
		   echo '</script>';	
	   }
	   else
	   {
		   echo '<script language="javascript">';
		   echo 'alert("'.$errorlist.'")';
		   echo '</script>';	
           echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " >  
				     <tr class="trheader" >
						<td width="50%">LOS File</td>
						<td width="50%">'.$withoutExt_b.'</td>
					 </tr>
					 <tr class="trheader" >
							<td width="50%">Total Line Detail/s</td>
							<td width="50%">0</td>
				     </tr>
					 <tr class="trheader" >
							<td width="50%">Total Quantity</td>
							<td width="50%">0</td>
				     </tr>
					 <tr class="trheader" >
						<td width="50%">LOS File</td>
						<td width="50%">'.$withoutExt_a.'</td>
					 </tr>
					 <tr class="trheader" >
							<td width="50%">Total Line Detail/s</td>
							<td width="50%">0</td>
				     </tr>
					 <tr class="trheader" >
							<td width="50%">Total Quantity</td>
							<td width="50%">0</td>
				     </tr>
			  </table>';	
	   }
	   
	   /* end of creation of record */
	   
	} 
    catch (Exception $e) 
    {
		$database->rollbackTransaction();
		$errmsg = $e->getMessage()."\n";
		
		echo '<script language="javascript">';
	    echo 'alert("'.$errmsg.'")';
		echo '</script>';	
		
		//redirect_to("../index.php?pageid=740&errmsg=$errmsg");
	}	
}		

function createtemptable($from, $cyclecountno,$brid,$productid,$WID,$txndate,$description,$UnitTypeID,$LOCID,$CHfreezeQty,$CHCreatedQty,$HOfreezeQty,$prdcode,$UnitPrice,$prddesc,$holocation)
{                        
	global $database;
	
	$prddesc = str_replace("'",'',trim($prddesc));
	
    $tmpinvcountq = $database->execute(" SELECT *
										 FROM  tmpinvcount
										 where tmpinvcount.RefID       = $cyclecountno 
										   and tmpinvcount.branchid    = $brid
										   and tmpinvcount.ProductID   = $productid
										   and tmpinvcount.WarehouseID = $WID 																   
									   ");
	if($tmpinvcountq->num_rows > 0 )
    {
		if($from == 2)
		{
		   $database->execute(" update tmpinvcount
								set tmpinvcount.HOLocation      = '$holocation',
									tmpinvcount.UnitTypeID      = $UnitTypeID,
									tmpinvcount.LocationID      = $LOCID,
									tmpinvcount.CHfreezeQty     = $CHfreezeQty,
									tmpinvcount.CHCreatedQty    = $CHCreatedQty,
									tmpinvcount.productCode     = '$prdcode',
									tmpinvcount.RegularPrice    = $UnitPrice,
									tmpinvcount.Description     = '$prddesc'
							  where tmpinvcount.RefID      = $cyclecountno 
							    and tmpinvcount.branchid    = $brid
							    and tmpinvcount.ProductID   = $productid
							    and tmpinvcount.WarehouseID = $WID  
				          ");	
		}

        if($from == 1)
		{
		   $database->execute(" update tmpinvcount
								set tmpinvcount.HOLocation      = '$holocation',
									tmpinvcount.UnitTypeID      = $UnitTypeID,
									tmpinvcount.LocationID      = $LOCID,
									tmpinvcount.HOfreezeQty     = $HOfreezeQty,
									tmpinvcount.productCode     = '$prdcode',
									tmpinvcount.RegularPrice    = $UnitPrice,
									tmpinvcount.Description     = '$prddesc'
							  where tmpinvcount.RefID      = $cyclecountno 
							    and tmpinvcount.branchid    = $brid
							    and tmpinvcount.ProductID   = $productid
							    and tmpinvcount.WarehouseID = $WID  
				          ");	
		}		
	}
	else
	{
		$database->execute(" 
							insert into tmpinvcount
						    set tmpinvcount.RefID            = $cyclecountno,  
								tmpinvcount.branchid         = $brid,
							    tmpinvcount.DocumentNo       = '$description',
								#tmpinvcount.TransactionDate = $txndate,
								tmpinvcount.ProductID        = $productid,
							    tmpinvcount.UnitTypeID       = $UnitTypeID,
								tmpinvcount.LocationID       = $LOCID,
								tmpinvcount.WarehouseID      = $WID ,
								tmpinvcount.CHfreezeQty      = $CHfreezeQty,
								tmpinvcount.CHCreatedQty     = $CHCreatedQty,
								tmpinvcount.HOfreezeQty      = $HOfreezeQty,
								tmpinvcount.productCode      = '$prdcode',
								tmpinvcount.RegularPrice     = $UnitPrice,
								tmpinvcount.Description      = '$prddesc',
								tmpinvcount.HOLocation       = '$holocation'	
						   ");  
   }
  
}
 

?>