<style>
	
table{font-size:12px; font-family: arial; border-collapse:collapse;}
td{padding:5px;}
.tablelisttr td{font-weight:bold; text-align:center;}
@page{
	margin : 0.5in 0;
}

.mainheader td{padding:1px;}
.fieldlabel{width:15%; font-weight:bold; text-align:right;}
.separator{width:5%; font-weight:bold; text-align:center;}
</style>

<?php 
	
	include "../../initialize.php";
	include IN_PATH."scIBMSalesReport.php";
	
	$ibmfrom = (isset($_GET['IBMfromHidden']))?$_GET['IBMfromHidden']:0;
    $ibmto = (isset($_GET['IBMtoHidden']))?$_GET['IBMtoHidden']:0;
    $campaign = (isset($_GET['campaign']))?$_GET['campaign']:0;
    $IGSMinimumCSP = (isset($_GET['igsminimumcsp']))?$_GET['igsminimumcsp']:0;
    $pmg = (isset($_GET['pmg']))?$_GET['pmg']:0;
    $page = (isset($_GET['page']))?$_GET['page']:1;
    $pageibm = (isset($_GET['pageibm']))?$_GET['pageibm']:1;
    $total = 10;
	$branch = $_GET['branch'];
	
	$userquery = $database->execute("SELECT * FROM employee WHERE ID = ".$_SESSION['emp_id']."");
	$user = $userquery->fetch_object();
	
	
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainheader">
			<tr>
				<td class="fieldlabel">Report</td>
				<td class="separator">:</td>
				<td><b>IBM Sales Report</b></td>
				<td class="fieldlabel">Run Date</td>
				<td class="separator">:</td>
				<td>'.date("F d, Y h:i").'</td>
			</tr>
			<tr>
				<td class="fieldlabel">Campaign</td>
				<td class="separator">:</td>
				<td>'.$campaign.'</td>
				<td class="fieldlabel">Created By</td>
				<td class="separator">:</td>
				<td>'.$user->LastName.', '.$user->FirstName.'</td>
			</tr>
		</table><br />';
	
	if($_GET['summarydetail'] == "Summary"){
		
		$ibmsalesreportsummary = ibmsalesreportsummary($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $page, $total, $branch);
		$ibmsalesreportsummarycount = ibmsalesreportsummary($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $page, $total, $branch);
        
        echo '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
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
		
	}else{
		
		$ibmsalesreportdetail = ibmsalesreportdetail($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $page, $total, $branch);
        $countibmsalesreportdetail = ibmsalesreportdetail($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $page, $total, $branch);
        
        echo '<table width="100%" class="tablelisttable" border="1" cellspacing="0" cellpadding="0">
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
        
        if($ibmsalesreportdetail->num_rows){
            
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
		
	}
	
	//for imb total
	$summaryperibm = summaryperibm($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, true, $pageibm, $total, $branch);
	
	echo '<br />
		<div style="font-weight:bold; text-align:center; font-family:arial;">IBM Total</div>
		<table class="tablelisttable" border="1" cellspacing="0" cellpadding="0" width="100%">
			<tr class="tablelisttr">
				<td>IBM Code</td>
				<td>IBM Name</td>
				<td>Total DGS</td>
				<td>Total No. of Actives</td>
			</tr>';

	if($summaryperibm->num_rows){
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
	
?>