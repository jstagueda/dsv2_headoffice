<?php
include "../../../initialize.php";
include IN_PATH.DS."scIBMSalesReport.php";


if(isset($_POST['searchbranch'])){
	
	$branchquery = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (`Name` LIKE '".$_POST['searchbranch']."%' OR `Code` LIKE '".$_POST['searchbranch']."%')");
	if($branchquery->num_rows){
		while($res = $branchquery->fetch_object()){
			$result[] = array("Label" => trim($res->Code)." - ".$res->Name,
							"Value" => trim($res->Code)." - ".$res->Name,
							"ID" => $res->ID);
		}
	}else{
		$result[] = array("Label" => "No result found.",
						"Value" => "",
						"ID" => 0);
	}
	
	die(json_encode($result));
}

if(isset($_POST['searched'])){
    $ibmrange = ibmrange($database, $_POST['searched'], $_POST['branch']);
    if($ibmrange->num_rows){
        while($res = $ibmrange->fetch_object()){
            $result[] = array("ID" => $res->ID, "Name" => TRIM($res->Code)." - ".$res->Name, "Value" => TRIM($res->Code)." - ".$res->Name);
        }
    }else{
        $result[] = array("ID" => 0, "Name" => "No result found.", "Value" => "");
    }
    
    die(tpi_JSONencode($result));
}

if(isset($_POST['summarydetail'])){
    
    $ibmfrom = (isset($_POST['IBMfromHidden']))?$_POST['IBMfromHidden']:0;
    $ibmto = (isset($_POST['IBMtoHidden']))?$_POST['IBMtoHidden']:0;
    $campaign = (isset($_POST['campaign']))?$_POST['campaign']:0;
    $IGSMinimumCSP = (isset($_POST['igsminimumcsp']))?$_POST['igsminimumcsp']:0;
    $pmg = (isset($_POST['pmg']))?$_POST['pmg']:0;
    $page = (isset($_POST['page']))?$_POST['page']:1;
    $pageibm = (isset($_POST['pageibm']))?$_POST['pageibm']:1;
	$branch = $_POST['branch'];
    $total = 10;
    
    if($_POST['summarydetail'] == 'Summary'){
        
        $ibmsalesreportsummary = ibmsalesreportsummary($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, false, $page, $total, $branch);
        $countibmsalesreportsummary = ibmsalesreportsummary($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $page, $total, $branch);
        $ibmsalesreportsummarycount = ibmsalesreportsummary($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, false, $page, $total, $branch);
        
        echo '<table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">';
        echo '<tr class="tablelisttr">
                <td>IGS Code</td>
                <td>IGS Name</td>
                <td>Campaign</td>
                <td>PMG</td>
                <td>CSP</td>
                <td>Invoice Amount</td>
                <td>Invoice Excluding VAT</td>
                <td>DGS</td>
            </tr>';
        
        if($ibmsalesreportsummary->num_rows){
            
            $ibmidarray = array();
            $ibmarray = array();
            $ibmid = array();
            
            while($res = $ibmsalesreportsummarycount->fetch_object()){
                $ibmidarray['IBMID'][$res->IBMID][] = $res->IBMID;
                $ibmidarray['CID'][] = $res->CID;
                $ibmidarray['DGS'][$res->IBMID][] = $res->DGS;
                $ibmidarray['Invoice'][$res->IBMID][] = $res->Invoice;
                $ibmidarray['InvoiceWithoutVAT'][$res->IBMID][] = $res->InvoiceWithoutVAT;
                $ibmidarray['CSP'][$res->IBMID][] = $res->CSP;
            }
            
            while($res = $ibmsalesreportsummary->fetch_object()){
                /*$dgstotal = 0;
                $invoiceTotal = 0;
                $InvoiceWithoutVAT = 0;
                $CPSTotal = 0;
                
                if(!in_array($res->IBMID, $ibmid)){
                    foreach($ibmidarray['IBMID'][$res->IBMID] as $key => $val){
                        $dgstotal += $ibmidarray['DGS'][$res->IBMID][$key];;
                        $invoiceTotal += $ibmidarray['Invoice'][$res->IBMID][$key];
                        $InvoiceWithoutVAT += $ibmidarray['InvoiceWithoutVAT'][$res->IBMID][$key];
                        $CPSTotal += $ibmidarray['CSP'][$res->IBMID][$key];
                    }
                    
                    echo '<tr class="listtr" style="background:#FFEFF0; font-weight:bold;">
                            <td>'.$res->IBMCode.'</td>
                            <td>'.$res->IBMName.'</td>
                            <td align="center">'.$res->Campaign.'</td>
                            <td align="right">ALL</td>
                            <td align="right">'.number_format($CPSTotal, 2).'</td>
                            <td align="right">'.number_format($invoiceTotal, 2).'</td>                                
                            <td align="right">'.number_format($InvoiceWithoutVAT, 2).'</td>                                
                            <td align="right">'.number_format($dgstotal, 2).'</td>
                        </tr>';
                    $ibmid[] = $res->IBMID;
                }*/
                
                $countibm = 0;
                
                if(!in_array($res->CID, $ibmarray)){
                    foreach($ibmidarray['CID'] as $ibm){
                        if($ibm == $res->CID){
                            $countibm++;
                        }
                    }
                    $ibmarray[] = $res->CID;
                }
                
                echo '<tr class="listtr">';
                if($countibm > 0){
                    echo '<td rowspan='.$countibm.'>'.$res->IGSCode.'</td>';
                    echo '<td rowspan='.$countibm.'>'.$res->IGSName.'</td>';
                    echo '<td rowspan='.$countibm.' align="center">'.$res->Campaign.'</td>';
                }
                
                echo '<td align="right">'.$res->PMG.'</td>';
                echo '<td align="right">'.number_format($res->CSP, 2).'</td>';
                echo '<td align="right">'.number_format($res->Invoice, 2).'</td>';
                echo '<td align="right">'.number_format($res->InvoiceWithoutVAT, 2).'</td>';
                echo '<td align="right">'.number_format($res->DGS, 2).'</td>';
                echo '</tr>';
            }
        }else{
            echo '<tr class="listtr">
                <td align="center" colspan="8">No result found.</td>
            </tr>';
        }
        
        echo "</table>";
        echo "<div style='margin-top:10px;' class='igsfield'>".  AddPagination($total, $countibmsalesreportsummary->num_rows, $page, 'IGS')."</div>";
        echo "<br />";
        echo "<br />";
        //for ibm summary;
        $summaryperibm = summaryperibm($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, false, $pageibm, $total, $branch);
        $countsummaryperibm = summaryperibm($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $pageibm, $total, $branch);

        echo '<table class="tablelist" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                <td class="tabmin"> </td>
                <td class="tabmin2">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" align="center">
                <tr>
                    <td class="txtredbold">
                        <b>Summary Per IBM</b>
                    </td>
                    <td> </td>
                </tr>
                </table>
                </td>
                <td class="tabmin3"> </td>
                </tr>
            </table>
            <table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
                <tr class="tablelisttr">
                    <td>IBM Code</td>
                    <td>IBM Name</td>
                    <td>Total DGS</td>
                    <td>Total No. of Actives</td>
                </tr>';

        if($countsummaryperibm->num_rows){
            while($res = $summaryperibm->fetch_object()){
                echo '<tr class="listtr">
                        <td>'.$res->IBMCode.'</td>
                        <td>'.$res->IBMName.'</td>
                        <td align=right>'.number_format($res->DGS, 2).'</td>
                        <td align=center>'.$res->Actives.'</td>
                    </tr>';
            }
        }else{
            echo '<tr class="listtr">
                    <td align="center" colspan="4">No result found.</td>
                </tr>';
        }
        
        echo "</table>";
        echo "<div style='margin-top:10px;' class='ibmfield'>".AddPagination($total, $countsummaryperibm->num_rows, $pageibm, 'IBM')."</div>";
        
    }else{
        
        $ibmsalesreportdetail = ibmsalesreportdetail($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, false, $page, $total, $branch);
        $ibmsalesreportdetailcount = ibmsalesreportdetail($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $page, $total, $branch);
        $countibmsalesreportdetail = ibmsalesreportdetail($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, false, $page, $total, $branch);
        
        echo '<table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
            <tr class="tablelisttr">
                <td>IGS Code</td>
                <td>IGS Name</td>
                <td>PMG</td>
                <td>CSP</td>
                <td>Basic Discount</td>
                <td>DGS</td>
                <td>Additional Discount</td>
                <td>Invoice Amount</td>
                <td>Paidup Sales</td>
                <td>Overdue Payments</td>
            </tr>';
        
        if($ibmsalesreportdetailcount->num_rows){
            
            $ibmidarray = array();
            $ibmarray = array();
            $ibmid = array();
            
            while($res = $countibmsalesreportdetail->fetch_object()){
                $ibmidarray['IBMID'][$res->IBMID][] = $res->IBMID;
                $ibmidarray['CID'][] = $res->CID;
                $ibmidarray['DGS'][$res->IBMID][] = $res->DGS;
                $ibmidarray['Invoice'][$res->IBMID][] = $res->Invoice;
                $ibmidarray['BasicDiscount'][$res->IBMID][] = $res->BasicDiscount;
                $ibmidarray['CSP'][$res->IBMID][] = $res->CSP;
                $ibmidarray['AdditionalDiscount'][$res->IBMID][] = $res->AdditionalDiscount;
            }
            
            while($res = $ibmsalesreportdetail->fetch_object()){
                
                /*$dgstotal = 0;
                $invoiceTotal = 0;
                $BasicDiscount = 0;
                $CPSTotal = 0;
                $AdditionalDiscount = 0;
                
                
                if(!in_array($res->IBMID, $ibmid)){
                    foreach($ibmidarray['IBMID'][$res->IBMID] as $key => $val){
                        $dgstotal += $ibmidarray['DGS'][$res->IBMID][$key];
                        $invoiceTotal += $ibmidarray['Invoice'][$res->IBMID][$key];
                        $BasicDiscount += $ibmidarray['BasicDiscount'][$res->IBMID][$key];
                        $CPSTotal += $ibmidarray['CSP'][$res->IBMID][$key];
                        $AdditionalDiscount += $ibmidarray['AdditionalDiscount'][$res->IBMID][$key];
                    }
                    
                    echo '<tr class="listtr" style="background:#FFEFF0; font-weight:bold;">
                            <td>'.$res->IBMCode.'</td>
                            <td>'.$res->IBMName.'</td>
                            <td align="center">ALL</td>
                            <td align="right">'.number_format($CPSTotal, 2).'</td>                                                           
                            <td align="right">'.number_format($BasicDiscount, 2).'</td>                            
                            <td align="right">'.number_format($dgstotal, 2).'</td>
                            <td align="right">'.number_format($AdditionalDiscount, 2).'</td>
                            <td align="right">'.number_format($invoiceTotal, 2).'</td> 
                            <td align="right">0</td>
                            <td align="right">0</td>
                        </tr>';
                    $ibmid[] = $res->IBMID;
                }*/
                
                $countibm = 0;                
                if(!in_array($res->CID, $ibmarray)){
                    foreach($ibmidarray['CID'] as $ibm){
                        if($ibm == $res->CID){
                            $countibm++;
                        }
                    }
                    $ibmarray[] = $res->CID;
                }
                
                echo '<tr class="listtr">';
                if($countibm > 0){
                    echo '<td rowspan='.$countibm.'>'.$res->IGSCode.'</td>';
                    echo '<td rowspan='.$countibm.'>'.$res->IGSName.'</td>';
                }
                
                echo "<td align='center'>".$res->PMG."</td>
                    <td align='right'>".number_format($res->CSP, 2)."</td>
                    <td align='right'>".number_format($res->BasicDiscount, 2)."</td>
                    <td align='right'>".number_format($res->DGS, 2)."</td>
                    <td align='right'>".number_format($res->AdditionalDiscount, 2)."</td>
                    <td align='right'>".number_format($res->Invoice, 2)."</td>
                    <td align='right'>0</td>
                    <td align='right'>0</td>
                </tr>";
            }
        }else{
            echo '<tr class="listtr">
                    <td align="center" colspan="10">No result found.</td>
                </tr>';
        }
        
        echo "</table>";
        echo "<div style='margin-top:10px;'>".  AddPagination($total, $ibmsalesreportdetailcount->num_rows, $page, 'IGS')."</div>";
        echo "<br>";
        echo "<br>";
        //for ibm summary;
        $summaryperibm = summaryperibm($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, false, $pageibm, $total, $branch);
        $countsummaryperibm = summaryperibm($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $pageibm, $total, $branch);

        echo '<table class="tablelist" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                <td class="tabmin"> </td>
                <td class="tabmin2">
                <table width="100%" cellspacing="1" cellpadding="0" border="0" align="center">
                <tr>
                    <td class="txtredbold">
                        <b>Summary Per IBM</b>
                    </td>
                    <td> </td>
                </tr>
                </table>
                </td>
                <td class="tabmin3"> </td>
                </tr>
            </table>
            <table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
                <tr class="tablelisttr">
                    <td>IBM Code</td>
                    <td>IBM Name</td>
                    <td>Total DGS</td>
                    <td>Total No. of Actives</td>
                </tr>';

        if($countsummaryperibm->num_rows){
            while($res = $summaryperibm->fetch_object()){
                echo '<tr class="listtr">
                        <td>'.$res->IBMCode.'</td>
                        <td>'.$res->IBMName.'</td>
                        <td align=right>'.number_format($res->DGS, 2).'</td>
                        <td align=center>'.$res->Actives.'</td>
                    </tr>';
            }
        }else{
            echo '<tr class="listtr">
                    <td align="center" colspan="4">No result found.</td>
                </tr>';
        }
        
        echo "</table>";
        echo "<div style='margin-top:10px;' class='ibmfield'>".AddPagination($total, $countsummaryperibm->num_rows, $pageibm, 'IBM')."</div>";

    }
}
?>
