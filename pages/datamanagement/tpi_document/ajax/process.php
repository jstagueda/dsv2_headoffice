<?php
include "../../../../initialize.php";
include IN_PATH.DS."pagination.php";
global $database;
global $session;
$userid = $session->user_id;


$pageid	 =	($_POST['pageid'])?$_POST['pageid']:0 ;
	//getmoduleid
	
$moduleid =  $database->execute("SELECT ID FROM modulecontrol WHERE pagenum=".$pageid);
if($moduleid->num_rows)
{
	while($j=$moduleid->fetch_object())
	{
		$moduleID = $j->ID;
	}
}	

if(ISSET($_POST['checkCode'])){
	$result=array();
	$result['num_rows'] = $database->execute("select * from tpi_document where CE_Code='".$_POST['checkCode']."'")->num_rows;
	$q = $database->execute("select * from tpi_document where CE_Code='".$_POST['checkCode']."'");
	if($result['num_rows'] > 0){
		while($r = $q->fetch_object()){
			
			$result['Code'] = $r->Ce_Code;
			$result['Desc'] = $r->CE_Description;
			$result['Start'] = date("m/d/Y",strtotime($r->Effectivity_Date_Start));
			$result['End'] =   date("m/d/Y",strtotime($r->Effectivity_Date_End));
			
		}
	}
	die(json_encode($result));
}

if(isset($_POST['index'])){

    $index = $_POST['index'];
    echo '<tr align="center" class="trlist">
            <td class="borderBR">
                <input name="btnRemove'.$index.'" type="button" class="btn" value="Remove" onclick="deleteRow(this.parentNode.parentNode.rowIndex,0)">
                <input name="btnRemovex'.$index.'" type="hidden" class="btn" value="Remove">
				
            </td>
            <td class="borderBR"><div align="center">'.$index.'</div></td>
            <td class="borderBR">
                <div align="center">
                    <input name="txtProdCode'.$index.'" type="text" class="txtfieldg" id="txtProdCode'.$index.'" style="width: 70px;" onkeypress="return selectItemCode(this.id);" value=""/>
                    <input name="hProdID'.$index.'" type="hidden" id="hProdID'.$index.'" value="" />
                </div>
            </td>
            <td class="borderBR">
                <div align="center">
                    <input name="txtProdDesc'.$index.'" type="text" class="txtfield" id="txtProdDesc'.$index.'" style="width: 95%;" readonly="yes" />
                </div>
            </td>
            <td class="borderBR">
                <div align="center">
                    <select name="txtbPmg'.$index.'" id = "txtbPmg'.$index.'" class = "txtfield" style="width: 80%">
                        <option value="1">CFT</option>
                        <option value="2">NCFT</option>
                        <option value="3">CPI</option>
                    </select>
                </div>
            </td>
        </tr>';
    die();

}


if(isset($_POST['addr'])){

    $index    = $_POST['addr'];
	$prodid   = $_POST['pid'];
	$prodname = $_POST['pname'];
	$prodcode = $_POST['pcode'];
	$pmgid    = $_POST['ppmgid'];
	$pmgcode  = $_POST['ppmgcode'];
	$doexist  = $_POST['doesexist'];
	$txnid    = $_POST['txnid'];
	
	$rem_del = 'Remove';
	$rem_del_action = 'deleteRow(this.parentNode.parentNode.rowIndex,0)';
	$bgcolor = '';
	
	if($doexist==1)
	{
		$rem_del_action = 'deleteRow(this.parentNode.parentNode.rowIndex,1)';
		$bgcolor = 'style="background-color: #b3b3b3;"';
		
		$getpmgidfrce = $database->execute("SELECT pmgid FROM tpi_documentdetails WHERE tpi_document_ID = $txnid and productid=$prodid");
		if($getpmgidfrce->num_rows){
			while($l = $getpmgidfrce->fetch_object()){
				
				$pmgid = $l->pmgid;
			}
		}		
	}
	
    echo '<tr align="center" class="trlist" '.$bgcolor.'>
            <td class="borderBR">
                <input name="btnRemove'.$index.'" type="button" class="btn" value="'.$rem_del.'" onclick="'.$rem_del_action.'">
                <input name="btnRemovex'.$index.'" type="hidden" class="btn" value="'.$rem_del.'">
				
            </td>
            <td class="borderBR"><div align="center">'.$index.'</div></td>
            <td class="borderBR">
                <div align="center">
                    <input name="txtProdCode'.$index.'" type="text" class="txtfieldg" id="txtProdCode'.$index.'" style="width: 70px;" onkeypress="return selectItemCode(this.id);" value="'.$prodcode.'" readonly="yes"/>
                    <input name="hProdID'.$index.'" type="hidden" id="hProdID'.$index.'" value="'.$prodid.'" />
                </div>
            </td>
            <td class="borderBR">
                <div align="center">
                    <input name="txtProdDesc'.$index.'" type="text" class="txtfield" id="txtProdDesc'.$index.'" style="width: 95%;" readonly="yes" value="'.$prodname.'"/>
                </div>
            </td>
            <td class="borderBR">
                <div align="center">
                    <select name="txtbPmg'.$index.'" id = "txtbPmg'.$index.'" class = "txtfield" style="width: 80%">
						'; 
							
							$q = $database->execute("SELECT id id,`code` `code`,`name` `name` FROM tpi_pmg WHERE id IN (1,2,3,8,9)");
							if($q->num_rows){
								while($w = $q->fetch_object()){
									
									$ceid = $w->id;
									$cecode = $w->code;
									$cename = $w->name;
									$selected= '';
									if ($ceid == $pmgid )
									{
										$selected = 'selected';
									}
							echo '<option value="'.$ceid.'" '.$selected.'>'.$cecode.'</option>';	
									
								}
							}					
					
            echo 	       '
                    </select>
                </div>
				<input name="hProdcode'.$index.'" type="hidden" id="hProdcode'.$index.'" value="'.$prodcode.'" />
            </td>
        </tr>';
    die();

}



if(isset($_POST['promocode'])){

    $query = "SELECT p.ID, p.Code, p.Name, pp.PMGID, ifnull(pp.UnitPrice, 0.00) UnitPrice, pmg.code pmgode
                from product p
                left join productpricing pp on pp.ProductID = p.ID
                left join tpi_pmg pmg on pmg.ID = pp.pmgid
                where
                p.`ProductLevelID` = 3 and p.Code like '%".$_POST['promocode']."%'
                or p.Name like '%".$_POST['promocode']."%'
                order by p.Name desc limit 5";
    $rsGetProductList = $database->execute($query);

    if($rsGetProductList->num_rows){
        while($row = $rsGetProductList->fetch_object()){
            $result[] = array("Label" => trim($row->Code)." - ".$row->Name,
                          "Value" => trim($row->Code),
                          "ID" => $row->ID,
                          "Name" => $row->Name,
                          "Code" => trim($row->Code),
                          "PMGID" => $row->PMGID,
                          "PMGCode" => $row->pmgode,
                          "UnitPrice" => $row->UnitPrice);
        }

    }else{

        $result[] = array("Label" => "No result found.",
                          "Value" => "",
                          "ID" => "",
                          "Name" => "",
                          "Code" => "",
                          "PMGID" => "",
                          "PMGCode" => "",
                          "UnitPrice" => "");

    }

    die(tpi_JSONencode($result));

}


if(isset($_POST['action'])){
		//create function for saving here...

		//Header..
		$Code			=	$_POST['txtCode'];
		$Description    =	$_POST['txtDescription'];
		$StartDate		=	date("Y-m-d h:i:s", strtotime($_POST['txtStartDate']));
		$EndDate		=	date("Y-m-d h:i:s", strtotime($_POST['txtEndDate']));
		$stop = false;
		$chargedto 		= 	$_POST['chargeto'];


                if($_POST['action'] == 'save'){
				
				   
                   $validate =  $database->execute("select ID from tpi_document where CE_Code='".$Code."'");
				   if($validate->num_rows == 0){
							$database->execute("insert into tpi_document (`CE_Code`,`CE_Description`,`Effectivity_Date_Start`,
							`Effectivity_Date_End`,`ChargedToDepartment`,`createdby`, `enrollmentdate`, `Changed`) values ('".$Code."','".$Description."','".$StartDate."','".$EndDate."','$chargedto',$userid,NOW(),1)");
				   }else{
					 $ID = $validate->fetch_object()->ID;
				     $database->execute("UPDATE tpi_document SET `Changed` = 1 where ID = ".$ID);
				   }
				   
				   
				   
                }else{
                    $database->execute("UPDATE tpi_document SET
                            `CE_Code` = '$Code',
                            `CE_Description` = '$Description',
                            `Effectivity_Date_Start` = '$StartDate',
                            `Effectivity_Date_End` = '$EndDate',
							`ChargedToDepartment` = '$chargedto',
							`Changed` = 1
                        WHERE ID = ".$_POST['CEID']."");
                }

                if($_POST['action'] == 'save'){
					//		Buyin and Entitlement..
					 if($validate->num_rows == 0){
							$qid = $database->execute("SELECT LAST_INSERT_ID() ID");
							if($qid->num_rows > 0){
									$ID = $qid->fetch_object()->ID;
							}else{
									$stop = true;
							}
					}
                }
		
		if(!$stop){
			$counter = $_POST['counter'];
			if($counter > 0):
				for($i=1; $counter >= $i; $i++){

				$ProductID  = $_POST["hProdID".$i];
				$PMG        = $_POST["txtbPmg".$i];
				// $SPID       = (isset($_POST['spid']))?$_POST['spid']:0;

				if(isset($_POST['hProdID'.$i]) || isset($_POST['hUpProdID'.$i])):
					if($_POST['action'] == 'save'){
						$database->execute("INSERT INTO tpi_documentdetails (`tpi_document_ID`, `ProductID` , `PMGID`,`EnrollmentDate`,`Changed`)
						VALUES (".$ID.", ".$ProductID.", ".$PMG.",NOW(),1)");
					}else{
						
							$_POST["hUpProdID".$i] = ($_POST["hUpProdID".$i]!="")?$_POST["hUpProdID".$i]:$_POST['hProdID'.$i];
						
							if(isset($_POST['btnRemove'.$i])){
								if($_POST["hUpProdID".$i] != ''){
									$database->execute("INSERT INTO tpi_documentdetails (`tpi_document_ID`, `ProductID` , `PMGID`,`EnrollmentDate`,`Changed`)
									VALUES (".$_POST['CEID'].", ".$_POST['hUpProdID'.$i].", ".$PMG.",NOW(),1)");
								}
							}else{
									$database->execute("UPDATE tpi_documentdetails SET `ProductID` = ".$_POST['hUpProdID'.$i].",
												`PMGID` = ".$PMG.", `Changed` =1  WHERE tpi_document_ID = ".$_POST['CEID']." and ProductID = ".$_POST['hProdID'.$i]."");
								
							}
							
							
					}
				endif;
					
			}
			endif;
			$result['message']="success";
			
		}else{
			$result['message']="failed";
		}
		die(json_encode($result));
}


if(isset($_POST['update'])){

		$Code			=	$_POST['txtCode'];
		$Description    =	$_POST['txtDescription'];
		$StartDate		=	date("Y-m-d h:i:s", strtotime($_POST['txtStartDate']));
		$EndDate		=	date("Y-m-d h:i:s", strtotime($_POST['txtEndDate']));
		$stop 			= 	false;
		$chargedto 		= 	$_POST['chargeto'];
		$counter 		= 	$_POST['counter'];
		
		try
		{
			$database->beginTransaction();
			
			$upd=$database->execute("UPDATE tpi_document SET
									`CE_Code` = '$Code',
									`CE_Description` = '$Description',
									`Effectivity_Date_Start` = '$StartDate',
									`Effectivity_Date_End` = '$EndDate',
									`ChargedToDepartment` = '$chargedto',
									`Changed` = 1
									WHERE ID = ".$_POST['CEID']."");			
			if(!$upd)
			{
				 throw new Exception("Cannot update record. Please contract IT for Support");

			}
			

			if($counter > 0)
			{
				for($i=1; $counter >= $i; $i++)
				{
				
				$ProductID   = $_POST["hProdID".$i];
				$PMG         = $_POST["txtbPmg".$i];
				$productcode = $_POST['hProdcode'.$i];
				
					if(isset($_POST['hProdID'.$i]) || isset($_POST['hUpProdID'.$i]))
					{
					   $gettxnid =  $database->execute("select ID from tpi_document where CE_Code='".$Code."'");
					   if($gettxnid->num_rows)
					   {
							while($r=$gettxnid->fetch_object())
							{
								$txnid = $r->ID;
							}
					   }		
				   
					   $ifprdexist =  $database->execute(" SELECT 
															pmg.code pmgcode,
															tpi.pmgid   pmgid
															FROM tpi_documentdetails tpi
															LEFT JOIN tpi_pmg pmg ON tpi.PMGID=pmg.id
															WHERE tpi.tpi_document_ID=$txnid AND productid=$ProductID");
					   if($ifprdexist->num_rows)
					   {
							//update 
							while($prdex=$ifprdexist->fetch_object())
							{
								$oldpmgid = $prdex->pmgid;
								$oldpmgcode = $prdex->pmgcode;
							}							
							
							
							$tt=$database->execute("UPDATE tpi_documentdetails
													SET `pmgid`= ".$PMG."
													WHERE productid= ".$ProductID."
													AND tpi_document_ID= ".$txnid);
													
							if(!$tt)
							{
								 throw new Exception("Cannot update record. Please contract IT for Support");
							}

							$newpmgcode = $database->execute("SELECT `code` pmgcode FROM tpi_pmg WHERE id = ".$PMG)->fetch_object()->pmgcode;

							$tablename = 'tpi_documentdetails';
							$fieldname = 'pmgid';
							$moduleid = $moduleID;
							$oldvalue = $oldpmgid;
							$newvalue = $PMG;
							$logtype = 'UPDATE';
							$remarks = "Updated PMG of $productcode from $oldpmgcode to $newpmgcode";
							
							$createlog=$sp->spCreateLog($database, $txnid, $tablename, $fieldname, $moduleid, $oldvalue, $newvalue, $logtype, $remarks, $userid );							
							
							if(!$createlog)
							{
								 throw new Exception("Cannot update record. Please contract IT for Support");
							} 							
							
												
					   }						
					   else
					   {
 
						   
							//add 
							$oo=$database->execute("INSERT INTO tpi_documentdetails (`tpi_document_ID`, `ProductID` , `PMGID`,`Changed`)
							VALUES (".$txnid.", ".$ProductID.", ".$PMG.",1)");
							
							if(!$oo)
							{
								 throw new Exception("Cannot update record. Please contract IT for Support");
							}								
							
							
							
							$tablename = 'tpi_documentdetails';
							$fieldname = 'productid';
							$moduleid = $moduleID;
							$oldvalue = 'NULL';
							$newvalue = $ProductID;
							$logtype = 'ADD';
							$remarks = "ADDED NEW PRODUCT: $productcode";
							
							$createlog=$sp->spCreateLog($database, $txnid, $tablename, $fieldname, $moduleid, $oldvalue, $newvalue, $logtype, $remarks, $userid );
																
							if(!$createlog)
							{
								 throw new Exception("Cannot update record. Please contract IT for Support");
							} 							
						
					   }
					}
				}
			}
			
			$database->commitTransaction();
			$result['message']="success";
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$result['ErrorMessage'] = $e->getMessage();
			$result['message']="failed";
		}
		
		die(json_encode($result));
}

if(isset($_POST['rolon']))
{
	$database->execute("delete from tpi_documentdetails where ID =".$_POST['rolon']);
}

//remove item per promo
if(isset($_POST['RemoveEntitlementItem'])){
	
	try{
		
		$database->beginTransaction();
		
		$database->execute("DELETE FROM specialpromobuyinandentitlement WHERE SpecialPromoID = ".$_POST['PromoID']." AND ID = ".$_POST['EntitlementID']."");
		
		$database->commitTransaction();
		$result['Success'] = 1;
		
	}catch(Exception $e){
	
		$database->rollbackTransaction();
		$result['ErrorMessage'] = $e->getMessage();
		$result['Success'] = 0;
	}
	die(json_encode($result));
}

if(isset($_POST['DeleteSpecialPromo'])){
	try{
		
		$database->beginTransaction();
		
		$database->execute("DELETE FROM tpi_document WHERE ID = ".$_POST['PromoID']."");
		$database->execute("DELETE FROM tpi_documentdetails WHERE tpi_document_ID = ".$_POST['PromoID']."");
		
		$database->commitTransaction();
		$result['Success'] = 1;
		
	}catch(Exception $e){
	
		$database->rollbackTransaction();
		$result['ErrorMessage'] = $e->getMessage();
		$result['Success'] = 0;
		
	}
	
	die(json_encode($result));
}

if($_GET['action'] == "pagination"){
    //$txtPromoCodeDesc = $_POST['txtPromoCodeDesc'];
    //$txtProductCode = $_POST['txtProductCode'];
    $page = $_POST['page'];
    $total = 10;
	$Search = TRIM($_POST['Search']);
    $start = ($page > 1)?(($page - 1) * $total):0;

    $viewpromocode = $database->execute("select * from tpi_document
									WHERE CE_Code LIKE '%$Search%'
									OR CE_Description LIKE '%$Search%'
									order by ID desc
                                    LIMIT $start, $total");

    $viewpromocodeCount = $database->execute("select * from tpi_document WHERE CE_Code LIKE '%$Search%'
									OR CE_Description LIKE '%$Search%' order by ID desc ");

    $header = '<table width="100%"   border="0" cellpadding="0" cellspacing="0" class="bordergreen">
                    <tr align=\'center\' class="trheader">
                        <td>CE CODES</td>
                        <td>Description</td>
                        <td>Start</td>
                        <td>End</td>
                    </tr>';

    $footer = '</table>';

    if($viewpromocode->num_rows){
        echo $header;
        while($res = $viewpromocode->fetch_object()){
            echo '<tr class="trlist">
                    <td width="15%" align=center>
						<a href="javascript:void(0);" onclick="return showSpecialPromo('.$res->ID.')">'.$res->CE_Code.'</a>
					</td>
                    <td align="center">'.$res->CE_Description.'</td>
                    <td width="10%" align=center>'.date("m/d/Y",strtotime($res->Effectivity_Date_Start)).'</td>
                    <td width="10%" align=center>'.date("m/d/Y",strtotime($res->Effectivity_Date_End)).'</td>
                </tr>';
        }
        echo $footer;
    }else{
        echo $header;
        echo '<tr class="trlist">
                <td colspan=\'4\' align="center">No result found.</td>
            </tr>';
        echo $footer;
    }

    echo "<div style='margin-top:10px;'>".AddPagination($total, $viewpromocodeCount->num_rows, $page)."</div>";
}



if(isset($_POST['remfrdbase'])){

		$txnid	 =	$_POST['txnno'];
		$prodid  =	$_POST['prodid'];
		$productcode = $_POST['prodcode'];
		
		if($_POST['remfrdbase'] == 'remfrdbase')
		{
			
			
			
			try
				{
					$database->beginTransaction();

					$del=$database->execute("DELETE FROM tpi_documentdetails
												WHERE tpi_document_ID = $txnid
												AND productid = $prodid");					
					
					if(!$del)
					{
						 throw new Exception("Error Message Here");
					}

					$tablename = 'tpi_documentdetails';
					$fieldname = 'productid';
					$moduleid = $moduleID;
					$oldvalue = $prodid;
					$newvalue = 'NULL';
					$logtype = 'DELETE';
					$remarks = "DELETED PRODUCT: $productcode";
					
					$createlog=$sp->spCreateLog($database, $txnid, $tablename, $fieldname, $moduleid, $oldvalue, $newvalue, $logtype, $remarks, $userid );							
					
					if(!$createlog)
					{
						 throw new Exception("Cannot update record. Please contract IT for Support");
					} 	

				
					$database->commitTransaction();


					$result['Success'] = 1;

				}	
				catch (Exception $e)
				{
					$database->rollbackTransaction();
					$result['Success'] = 0;
					$result['ErrorMessage'] = $e->getMessage();
				}

		die(json_encode($result));
		}
		
}


?>
