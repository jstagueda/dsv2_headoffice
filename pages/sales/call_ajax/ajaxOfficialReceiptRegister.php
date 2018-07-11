<?php
include "../../../initialize.php";
include IN_PATH.DS."pagination.php";
include IN_PATH.DS."scOfficialReceiptRegister.php";

if(isset($_POST['page'])){

    $header = "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bordergreen'>
                <tr class='trheader'>
                    <td>Collection Receipt Date</td>
                    <td>Collection Receipt No.</td>
                    <td>Transaction No.</td>
                    <td>Customer Code</td>
                    <td>Customer Name</td>
                    <td>Cash</td>
                    <td>Check</td>
                    <td>Deposit Slip</td>
                    <td>Cancelled Amount</td>
                    <td>Penalty Amount</td>
                    <td>Net Total Amount</td>
                    <td>Offsetting</td>
                    <td>Total Applied Amount</td>
                    <td>Total Unapplied Amount</td>
                </tr>";

    $footer = "</table>";

    $datefrom = $_POST['datefrom'];
    $dateto = $_POST['dateto'];
    $page = $_POST['page'];
    $total = 10;
    $sfmlevel = $_POST['sfmlevel'];
    $sfaccountFrom = $_POST['sfaccountfromHidden'];
    $sfaccountTo = $_POST['sfaccounttoHidden'];

    $officialrecieptregister = officialrecieptregister($database, $datefrom, $dateto, false, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo,$_POST['branch']);
    $officialrecieptregistercount = officialrecieptregister($database, $datefrom, $dateto, true, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo,$_POST['branch']);
	
	$officialreceipttotal = officialreceipttotal($datefrom, $dateto, $sfmlevel, $sfaccountFrom, $sfaccountTo, $_POST['branch']);

    if($officialrecieptregistercount->num_rows){
		$Cancelled  = 0;
		$PenaltyAmt	= 0;
        echo $header;
        while($res = $officialrecieptregister->fetch_object()){
            echo "<tr class='trlist'>
                    <td>".$res->ORDate."</td>                    
                    <td>".$res->DocumentNo."</td>
					<td>".str_replace('OR','',$res->ORno)."</td>
                    <td>".$res->CustomerCode."</td>
                    <td>".$res->CustomerName."</td>
                    <td align=right>".number_format($res->cash, 2)."</td>
                    <td align=right>".number_format($res->check, 2)."</td>
                    <td align=right>".number_format($res->DepositSlip, 2)."</td>
                    <td align='center'>".number_format($res->Cancelled,2)."</td>
                    <td align='center'>".number_format($res->PenaltyAmt,2)."</td>
                    <td align=right>".number_format($res->NetAmount, 2)."</td>
                    <td align=right>".number_format($res->offset, 2)."</td>
                    <td align=right>".number_format($res->TotalAppliedAmount, 2)."</td>
                    <td align=right>".number_format($res->TotalUaAppliedAmount, 2)."</td>
                </tr>";
				
        }
		
		//$officialrecieptregister1 = officialrecieptregister($database, $datefrom, $dateto, true, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo);
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
				<td colspan='5' align='right'>Total :</td>
				<td align=right>".number_format($cash, 2)."</td>
				<td align=right>".number_format($check, 2)."</td>
				<td align=right>".number_format($DepositSlip, 2)."</td>
				<td align='center'>".number_format($Cancelled,2) ."</td>
				<td align='center'>".number_format($PenaltyAmt,2)."</td>
				<td align=right>".number_format($NetAmount, 2)."</td>
				<td align=right>".number_format($offset, 2)."</td>
				<td align=right>".number_format($TotalAppliedAmount, 2)."</td>
				<td align=right>".number_format($TotalUaAppliedAmount, 2)."</td>
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

if(isset($_POST['action'])){
	if($_POST['action'] == 'getBranch'){
		$branch = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (Code LIKE '".$_POST['BranchName']."%' OR Name LIKE '".$_POST['BranchName']."%')");
		if($branch->num_rows){
			while($res = $branch->fetch_object()){
				$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
								"Value" => trim($res->Code).' - '.$res->Name,
								"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found",
								"Value" => "",
								"ID" => 0);
		}
		die(json_encode($result));
	}
}
?>
