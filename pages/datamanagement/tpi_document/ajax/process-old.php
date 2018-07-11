<?php

include "../../../../initialize.php";
include IN_PATH.DS."pagination.php";
global $database;

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
                <input name="btnRemove'.$index.'" type="button" class="btn" value="Remove" onclick="deleteRow(this.parentNode.parentNode.rowIndex)">
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


                if($_POST['action'] == 'save'){
				
				   
                   $validate =  $database->execute("select ID from tpi_document where CE_Code='".$Code."'");
				   if($validate->num_rows == 0){
						$database->execute("insert into tpi_document (`CE_Code`,`CE_Description`,`Effectivity_Date_Start`,
							`Effectivity_Date_End`, `Changed`) values ('".$Code."','".$Description."','".$StartDate."','".$EndDate."',1)");
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
						$database->execute("INSERT INTO tpi_documentdetails (`tpi_document_ID`, `ProductID` , `PMGID`,`Changed`)
						VALUES (".$ID.", ".$ProductID.", ".$PMG.",1)");
					}else{
						
							$_POST["hUpProdID".$i] = ($_POST["hUpProdID".$i]!="")?$_POST["hUpProdID".$i]:$_POST['hProdID'.$i];
						
							if(isset($_POST['btnRemove'.$i])){
								if($_POST["hUpProdID".$i] != ''){
									$database->execute("INSERT INTO tpi_documentdetails (`tpi_document_ID`, `ProductID` , `PMGID`,`Changed`)
									VALUES (".$_POST['CEID'].", ".$_POST['hUpProdID'.$i].", ".$PMG.",1)");
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



if(isset($_POST['rolon'])){
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
?>
