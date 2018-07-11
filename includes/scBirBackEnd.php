<?php
global $database;
if (!ini_get('display_errors')){
    ini_set('display_errors', 1);
} 
	
$offset = 0;
$RPP = 0;

$date = time();
$today = date("m/d/Y",$date);
$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)) . " -1 month");
$end = date("m/d/Y",$tmpdate);
$start = date("m/d/Y",$tmpdate);

$bname = "";
$btin = "";
$bsn = "";
	
$queryBranch = $sp->spSelectBranchbyBranchParameter($database);
if ($queryBranch->num_rows){
    while ($rowB =  $queryBranch->fetch_object()){
        $bname = $rowB->Name;
        $btin = $rowB->TIN;
        $bsn = $rowB->ServerSN;			    
    }
}		
        
function birbackend($database, $datefrom, $dateto, $istotal, $page, $total, $branch){
    
    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    // vatsales
	// AND LOCATE('-$branch', si.HOGeneralID) > 0

	
	$query= $database->execute("select `Date` xDate,`BeginningInvoice`, `EndingInvoice`,`BeginningBalance`,`EndingBalance`,`TotalSales`,`VATSales`,
								`VATAmount`,`Non-VATSales` NonVATSales,`ZeroRated`,`DiscountPrevPurchase`,`Returns`,`Void`,`OverunOverflow` 
								from birbackend where LOCATE('-".$branch."', HOGeneralID) > 0 and date(`Date`) between '".$datefrom."' AND '".$dateto."' ".$limit);
	
	
    //$query = $database->execute("SELECT DISTINCT MIN(si.DocumentNo)dn1, MAX(si.DocumentNo)dn2,
    //                                DATE_FORMAT(si.txnDate, '%Y/%m/%d') txndate,
    //                                SUM(si.OutstandingBalance) OB,
    //                                SUM(si.NetAmount) NA, SUM(si.GrossAmount) GA,
    //                                -- SUM(si.GrossAmount - si.VatAmount) vatsales,
	//								SUM(si.NetAmount + AddtlDiscountPrev) vatsales,
    //                                0 VatSales, SUM(si.VatAmount) VA,
    //                                0 nonvat, 0 ZeroRated, SUM(si.AddtlDiscountPrev) ADP,
    //                                0 returnss, -- sisi.DN 
	//								(SELECT COUNT(*) from salesinvoice where DATE(TxnDate) = DATE(si.TxnDate) and statusid = 8 AND LOCATE('-$branch', HOGeneralID) > 0) Voids, 0 Overrun
    //                                FROM salesinvoice si
    //                                -- LEFT JOIN
    //                                -- (SELECT ID,  COUNT(si2.DocumentNo) DN, DATE_FORMAT(si2.txnDate, '%Y-%m-%d') txndate2 
    //                                --     FROM salesinvoice si2
    //                                --     WHERE (DATE_FORMAT(si2.txndate,'%Y-%m-%d') 
    //                                --         BETWEEN '$datefrom' AND '$dateto' ) 
    //                                --         AND si2.statusid IN (8)
    //                                --     GROUP BY DATE_FORMAT(si2.txnDate, '%Y-%m-%d')
    //                                --     ORDER BY DATE_FORMAT(si2.txnDate, '%Y-%m-%d'), si2.documentno ASC) 
    //                                --     sisi ON sisi.txndate2 = DATE_FORMAT(si.txnDate, '%Y-%m-%d')
    //                                WHERE
    //                                (DATE_FORMAT(si.txndate,'%Y-%m-%d') 
    //                                    BETWEEN '$datefrom' AND '$dateto') 
    //                                    AND si.statusid IN (7)
	//									AND LOCATE('-$branch', si.HOGeneralID) > 0
    //                                GROUP BY DATE_FORMAT(si.txnDate, '%Y-%m-%d')
    //                                ORDER BY DATE_FORMAT(si.txnDate, '%Y-%m-%d'), si.documentno ASC
    //                                $limit");
    return $query;
}

?>