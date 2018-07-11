<?php 

include "../../../initialize.php";
include IN_PATH.DS."pagination.php";

if(isset($_POST['page'])){

    $header = "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bordergreen'>
                <tr class='trheader'>
                    <td>Collection Receipt Date</td>
					<td>Collection Receipt No.</td>
                    <td>Transaction No.</td>          
                    <td>Cash</td>
                    <td>Check</td>
                    <td>Deposit Slip</td>
                    <td>Net Total Amount</td>
                    <td>Total Applied Amount</td>
                </tr>";

    $footer = "</table>";

    $datefrom = $_POST['datefrom'];
    $dateto = $_POST['dateto'];
    $page = $_POST['page'];
    $total = 10;
    $sfmlevel = $_POST['sfmlevel'];
    $sfaccountFrom = $_POST['sfaccountfromHidden'];
    $sfaccountTo = $_POST['sfaccounttoHidden'];

    $officialrecieptregister = officialrecieptregister($database, $datefrom, $dateto, false, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo);
    $officialrecieptregistercount = officialrecieptregister($database, $datefrom, $dateto, true, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo);
	
	$officialreceipttotal = officialreceipttotal($datefrom, $dateto, $sfmlevel, $sfaccountFrom, $sfaccountTo);

    if($officialrecieptregistercount->num_rows){
		$Cancelled  = 0;
		$PenaltyAmt	= 0;
        echo $header;
        while($res = $officialrecieptregister->fetch_object()){
            echo "<tr class='trlist'>
                    <td>".$res->ORDate."</td>                    
					<td>".$res->DocumentNo."</td>
					<td>".str_replace('OR', '', $res->ORno)."</td>
                    <td align=right>".number_format($res->cash, 2)."</td>
                    <td align=right>".number_format($res->check, 2)."</td>
                    <td align=right>".number_format($res->DepositSlip, 2)."</td>
                    <td align=right>".number_format($res->NetAmount, 2)."</td>
                    <td align=right>".number_format($res->TotalAppliedAmount, 2)."</td>
                </tr>";
        }
		
		$officialrecieptregister1 = officialrecieptregister($database, $datefrom, $dateto, true, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo);
		$Cancelled  = 0;
		$PenaltyAmt	= 0;
		while($res1= $officialreceipttotal->fetch_object()){
				$Cancelled  				+= $res1->CancelledAmount;
				$PenaltyAmt	 				+= $res1->PenaltyAmount;
				$cash	 					+= $res1->Cash;
				$check	 					+= $res1->Check;
				$DepositSlip	 			+= $res1->Deposit;
				$NetAmount	 				+= $res1->NetTotalAmount;
				$offset	 					+= $res1->Offsetting;
				$TotalAppliedAmount	 		+= $res1->TotalAppliedAmount;
				$TotalUaAppliedAmount		+= $res1->TotalUnappliedAmt;
        }
		
		echo "<tr class='trlist' style='font-weight:bold;'>
				<td colspan='3' align='right'>Total :</td>
				<td align=right>".number_format($cash, 2)."</td>
				<td align=right>".number_format($check, 2)."</td>
				<td align=right>".number_format($DepositSlip, 2)."</td>
				<td align=right>".number_format($NetAmount, 2)."</td>
				<td align=right>".number_format($TotalAppliedAmount, 2)."</td>
			</tr>";
			
        echo $footer;

    }else{
        echo $header;
        echo "<tr class='trlist'>
                <td colspan='13' align='center'><span class='txtredsbold'>No record(s) to display.</span></td>
            </tr>";
        echo $footer;
    }

    echo "<div style='margin-top:20px;'>".AddPagination($total, $officialrecieptregistercount->num_rows, $page)."</div>";

    die();
}

if(isset($_POST['searched'])){

    $sfm = ($_POST['sfmlevel'] == 0)?"":"AND CustomerTypeID = ".$_POST['sfmlevel'];

    $query = $database->execute("SELECT TRIM(Code) Code, Name, ID FROM customer WHERE (Name LIKE '".$_POST['searched']."%' OR Code LIKE '".$_POST['searched']."%') $sfm LIMIT 10");
    if($query->num_rows){
        while($res = $query->fetch_object()){
            $result[] = array("Label" => $res->Code." - ".$res->Name, "Value" => $res->Code." - ".$res->Name, "ID" => $res->ID);
        }
    }else{
        $result[] = array("Label" => "No result found.", "Value" => "", "ID" => 0);
    }
    die(json_encode($result));
}




function officialrecieptregister($database, $datefrom, $dateto, $istotal, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo){

    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $datefrom = date("Y-m-d", strtotime($datefrom));
    $dateto = date("Y-m-d", strtotime($dateto));

    $sfmlevel = ($sfmlevel == 0)?"":"AND c.CustomerTypeID = $sfmlevel";
    $sfaccountrange = ($sfaccountFrom == 0 && $sfaccountTo == 0)?"":"AND ((c.ID BETWEEN $sfaccountFrom AND $sfaccountTo)
                                                                    OR (c.ID BETWEEN $sfaccountTo AND $sfaccountFrom))";
	
	
										
    $query = $database->execute("SELECT a.*, IFNULL(cp.OutstandingAmount,0.00) PenaltyAmt FROM (
										SELECT
										DATE_FORMAT(or.TxnDate, '%m/%d/%Y') ORDate,
										CONCAT('OR',LPAD(or.ID,8,0)) ORno,
										or.DocumentNo,
										TRIM(c.ID) CustomerID,
										TRIM(c.Code) CustomerCode,
										c.Name CustomerName,
										IFNULL(orch.TotalAmount, 0)    cash,
										IFNULL(orck.TotalAmount, 0)    `check`,
										IFNULL(orcom.TotalAmount,0) `offset`,
										s.Name StatusName,
										s.ID StatusID, 
										ord.TotalAmount DepositSlip,
										or.TotalAmount NetAmount,
										or.TotalAppliedAmt TotalAppliedAmount,
										or.TotalUnappliedAmt TotalUaAppliedAmount,
										IFNULL(ords.RefTxnID,0) SalesInvoiceID,
										if(s.ID = 8, or.TotalUnappliedAmt, 0.00) Cancelled
										FROM officialreceipt `or`
										LEFT JOIN officialreceiptcash orch ON orch.OfficialReceiptID = or.ID
										LEFT JOIN officialreceiptcheck orck ON orck.OfficialReceiptID = or.ID
										INNER JOIN customer c ON c.ID = or.CustomerID
										INNER JOIN `status` s ON s.ID = or.StatusID
										LEFT JOIN officialreceiptdeposit `ord` ON ord.OfficialReceiptID = or.ID
										LEFT JOIN officialreceiptcommission orcom ON orcom.OfficialReceiptID = or.ID
										LEFT JOIN officialreceiptdetails `ords` ON `ords`.OfficialReceiptID=or.ID 
										AND ords.ORReferenceType = 1 and or.IsORorMisc = 0
										WHERE DATE(or.TxnDate) BETWEEN '$datefrom' AND '$dateto'
										".$sfmlevel." ".$sfaccountrange."
										GROUP BY or.ID, or.DocumentNo ".$limit."
								) a 
								LEFT JOIN customerpenalty cp ON cp.SalesInvoiceID = a.SalesInvoiceID AND a.CustomerID = cp.CustomerID								
								ORDER BY ORDate, DocumentNo");

    return $query;
}

function officialreceipttotal($datefrom, $dateto, $sfmlevel, $sfaccountFrom, $sfaccountTo){
	global $database;
	
	$datefrom = date("Y-m-d", strtotime($datefrom));
    $dateto = date("Y-m-d", strtotime($dateto));

    $sfmlevel = ($sfmlevel == 0)?"":"AND c.CustomerTypeID = $sfmlevel";
    $sfaccountrange = ($sfaccountFrom == 0 && $sfaccountTo == 0)?"":"AND ((c.ID BETWEEN $sfaccountFrom AND $sfaccountTo)  OR (c.ID BETWEEN $sfaccountTo AND $sfaccountFrom))";
																	
	$ListORIDS=array();																
	$ORIds = "";
	$dynamic_where = "";
	$q = $database->execute("SELECT or.ID from officialreceipt `or`
						inner join customer c on c.ID = or.CustomerID 
						WHERE or.IsORorMisc = 0 and DATE(or.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."'  ".$sfmlevel." ".$sfaccountrange);
	if($q->num_rows > 0 ){
		while($r = $q->fetch_object()){
			$ListORIDS[] = $r->ID;
		}
	}
	$ORIds = implode(',',$ListORIDS);
	if( count($ListORIDS) > 0 ){
		$dynamic_where = " AND a.ID in (".$ORIds.")";
	}
	$query = $database->execute("SELECT 
								( SELECT IFNULL(SUM(b.TotalAmount),0.00)  FROM officialreceipt a INNER JOIN officialreceiptcash b ON a.ID = b.OfficialReceiptID WHERE a.IsORorMisc = 0 and DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' AND a.StatusID = 7  ".$dynamic_where.") Cash,
								( SELECT IFNULL(SUM(b.TotalAmount),0.00)  FROM officialreceipt a INNER JOIN officialreceiptdeposit b ON a.ID = b.OfficialReceiptID WHERE a.IsORorMisc = 0 and DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' AND a.StatusID = 7 ".$dynamic_where.") Deposit,
								( SELECT IFNULL(SUM(b.TotalAmount),0.00)  FROM officialreceipt a INNER JOIN officialreceiptcheck b ON a.ID = b.OfficialReceiptID WHERE a.IsORorMisc = 0 and  DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' AND a.StatusID = 7 ".$dynamic_where.") `Check`,
								(SELECT SUM(TotalAmount) FROM officialreceipt a WHERE StatusID = 8 and a.IsORorMisc = 0 and  DATE(TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' ".$dynamic_where.") CancelledAmount,
								(SELECT SUM(OutstandingAmount) from(SELECT IFNULL(c.OutstandingAmount,0.00) OutstandingAmount 
								FROM officialreceipt a
								INNER JOIN officialreceiptdetails b ON a.ID = b.OfficialReceiptID
								LEFT JOIN customerpenalty c ON b.RefTxnID = c.SalesInvoiceID AND b.ORReferenceType = 1
								WHERE a.IsORorMisc = 0 and  DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' ".$dynamic_where." GROUP BY a.ID) tbl) PenaltyAmount,
								(SELECT IFNULL( SUM(TotalAmount),0.00) FROM officialreceipt a WHERE a.IsORorMisc = 0 and StatusID = 7 AND DATE(TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' ".$dynamic_where.") NetTotalAmount,
								( SELECT IFNULL(SUM(b.TotalAmount),0.00)  FROM officialreceipt a INNER JOIN officialreceiptcommission b ON a.ID = b.OfficialReceiptID WHERE a.IsORorMisc = 0 and DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' AND a.StatusID = 7 ".$dynamic_where.") Offsetting,
								(SELECT IFNULL( SUM(TotalAppliedAmt),0.00) FROM officialreceipt a WHERE a.IsORorMisc = 0 and StatusID = 7 AND DATE(TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' ".$dynamic_where.") TotalAppliedAmount,
								(SELECT IFNULL( SUM(TotalUnappliedAmt),0.00) FROM officialreceipt a WHERE a.IsORorMisc = 0 and StatusID = 7 AND DATE(TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' ".$dynamic_where.") TotalUnappliedAmt");
	
	return $query;
}

?>