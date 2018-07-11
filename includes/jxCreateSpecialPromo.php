<?php

require_once("../initialize.php");
global $database;

if(isset($_POST['checkCode'])){

    //may change the stored proc if there is a new table for this
    $rs_code_exist = $database->execute("SELECT * FROM specialpromo where `Code`='".$_POST['checkCode']."'");
    echo $rs_code_exist->num_rows;

}

if(isset($_POST['index'])){

    $index = $_POST['index'];
    echo '<tr align="center" class="trlist">
            <td class="borderBR">
                <input name="btnRemove'.$index.'" type="button" class="btn" value="Remove" onclick="deleteRow(this.parentNode.parentNode.rowIndex)">
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
                    <select name="cboCriteria'.$index.'" class="txtfield" id="cboCriteria'.$index.'" style="width: 90%;" >
                        <option value="2">Amount</option>
                        <option value="1" selected="selected">Quantity</option>
                    </select>
                </div>
            </td>
            <td class="borderBR">
                <div align="center">
                    <input name="txtQty'.$index.'" type="text" class="txtfield" id="txtQty'.$index.'"  value="1" style="width: 90%; text-align:right" />
                </div>
            </td>
            <td class="borderBR">
                <div align="center">
                    <select name="cboECriteria'.$index.'" class="txtfield" id="cboECriteria'.$index.'" style="width: 90%;">
                        <option value="2" selected="selected">Price</option>
                    </select>
                </div>
            </td>
            <td class="borderBR">
                <div align="center">
                    <input name="txtEQty'.$index.'" type="text" class="txtfield" id="txtEQty'.$index.'"  onkeypress="return addRow(event);" value="1" style="width: 90%; text-align:right" />
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
	$xx = "";
	$and = "";
	if(isset($_POST['arrayprodid'])){
		$xx = implode("','",$_POST['arrayprodid']);
		
		$and = " p.Code not in ('".$xx."') and";
	}
	
    $query = "SELECT p.ID, p.Code, p.Name, pp.PMGID, ifnull(pp.UnitPrice, 0.00) UnitPrice, pmg.code pmgode
                from product p
                left join productpricing pp on pp.ProductID = p.ID
                left join tpi_pmg pmg on pmg.ID = pp.pmgid
                where ".$and."
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

//		Header..
		$IsPlusPlan             =	0;
		$Code			=	$_POST['txtCode'];
		$Description            =	$_POST['txtDescription'];
		$PromoType		=	$_POST['promotype'];
		$StartDate		=	date("Y-m-d h:i:s", strtotime($_POST['txtStartDate']));
		$EndDate		=	date("Y-m-d h:i:s", strtotime($_POST['txtEndDate']));
		$bpage			=	$_POST['bpage'];
		$epage			=	$_POST['epage'];
		$BrochurePage           = 	$bpage."-".$epage;
		$NonGSU 		=	$_POST['txtMaxAvail1'];
		$DirectGSU		=	$_POST['txtMaxAvail2'];
		$IndirectGSU            =	$_POST['txtMaxAvail3'];
		$stop = false;

		if(isset($_POST[chkPlusPlan])){
			$IsPlusPlan=1;
		}

                if($_POST['action'] == 'save'){
                    $database->execute(
                        "INSERT INTO specialpromo (`Code`,`Description`,`PromoType`,`StartDate`,`EndDate`,`BrochurePage`,`NonGSU`,`DirectGSU`,`InDirectGSU`,`IsPlusPlan`)
                        VALUES					  ('".$Code."','".$Description."','".$PromoType."','".$StartDate."','".$EndDate."','".$BrochurePage."',".$NonGSU.",".$DirectGSU.",
                                                                            ".$IndirectGSU.",".$IsPlusPlan.")");
                }else{
                    $database->execute("UPDATE specialpromo SET
                            `Code` = '$Code',
                            `Description` = '$Description',
                            `PromoType` = $PromoType,
                            `StartDate` = '$StartDate',
                            `EndDate` = '$EndDate',
                            `BrochurePage` = '$BrochurePage',
                            `NonGSU` = $NonGSU,
                            `DirectGSU` = $DirectGSU,
                            `InDirectGSU` = $IndirectGSU,
                            `IsPlusPlan` = $IsPlusPlan
                        WHERE ID = ".$_POST['spid']."");
                }

                if($_POST['action'] == 'save'){
    //		Buyin and Entitlement..
                    $qid = $database->execute("SELECT LAST_INSERT_ID() ID");
                    if($qid->num_rows > 0){
                            $SpecialPromoID = $qid->fetch_object()->ID;
                    }else{
                            $stop = true;
                    }
                }

		if(!$stop){
			$counter = $_POST['counter'];
			for($i=1; $counter >= $i; $i++){

				$ProductID  = $_POST["hProdID{$i}"];
				$BCriteria  = $_POST["cboCriteria{$i}"];
				$BQty       = $_POST["txtQty{$i}"];
				$ECriteria  = $_POST["cboECriteria{$i}"];
				$EQty       = $_POST["txtEQty{$i}"];
				$PMG        = $_POST["txtbPmg{$i}"];
                                $SPID       = $_POST['spid'];

				if($BCriteria == 1){ // Buyin Quantity
                                    if($_POST['action'] == 'save'){
					$database->execute("INSERT INTO specialpromobuyinandentitlement (`SpecialPromoID`, `ProductID` , `BuyinCriteria`, `BuyinMinimumQty` , `BuyinMinimumAmnt`, `EntitlementCriteria`, `EntitlementQty` , `EntitlementAmnt`, `EntitlementPMGID`)
					VALUES (".$SpecialPromoID.", ".$ProductID.", ".$BCriteria.", ".$BQty.", 0 , ".$ECriteria.", 0, ".$EQty.", ".$PMG.")");
                                    }else{
                                        $database->execute("UPDATE specialpromobuyinandentitlement SET
                                                        `ProductID` = $ProductID,
                                                        `BuyinCriteria` = $BCriteria,
                                                        `BuyinMinimumQty` = $BQty,
                                                        `BuyinMinimumAmnt` = 0,
                                                        `EntitlementCriteria` = $ECriteria,
                                                        `EntitlementQty` = 0,
                                                        `EntitlementAmnt` = $EQty,
                                                        `EntitlementPMGID` = $PMG
                                                        WHERE SpecialPromoID = $SPID
                                                        AND ID = ".$_POST["ID{$i}"]);
                                    }
				}else{ // Buyin Ammount
                                    if($_POST['action'] == 'save'){
					$database->execute("INSERT INTO specialpromobuyinandentitlement (`SpecialPromoID`, `ProductID` , `BuyinCriteria`, `BuyinMinimumQty` , `BuyinMinimumAmnt`, `EntitlementCriteria`, `EntitlementQty` , `EntitlementAmnt`, `EntitlementPMGID`)
					VALUES (".$SpecialPromoID.", ".$ProductID.", ".$BCriteria.", 0, ".$BQty." , ".$ECriteria.",0, ".$EQty.", ".$PMG.")");
                                    }else{
                                        $database->execute("UPDATE specialpromobuyinandentitlement SET
                                                        `ProductID` = $ProductID,
                                                        `BuyinCriteria` = $BCriteria,
                                                        `BuyinMinimumQty` = 0,
                                                        `BuyinMinimumAmnt` = $BQty,
                                                        `EntitlementCriteria` = $ECriteria,
                                                        `EntitlementQty` = 0,
                                                        `EntitlementAmnt` = $EQty,
                                                        `EntitlementPMGID` = $PMG
                                                        WHERE SpecialPromoID = $SPID
                                                        AND ID = ".$_POST["ID{$i}"]);
                                    }
				}
			}

			$result['message']="success";
		}else{
			$result['message']="failed";
		}
		die(json_encode($result));
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
		
		$database->execute("DELETE FROM specialpromobuyinandentitlement WHERE SpecialPromoID = ".$_POST['PromoID']."");
		$database->execute("DELETE FROM specialpromo WHERE ID = ".$_POST['PromoID']."");
		
		$database->commitTransaction();
		$result['Success'] = 1;
		
	}catch(Exception $e){
	
		$database->rollbackTransaction();
		$result['ErrorMessage'] = $e->getMessage();
		$result['Success'] = 0;
		
	}
	
	die(json_encode($result));
}
?>
