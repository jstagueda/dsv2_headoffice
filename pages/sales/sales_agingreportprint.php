<style>
    body{font-family:arial;}
    .pageset{margin-bottom: 20px;}
    .pageset table{font-size:12px; border-collapse: collapse;}
    .tableheadermain{font-size:12px;}
    .pageset table td{padding:3px;}
    @page{margin:0.5in 0; size:portrait;}
    @media print{
        .tableheadermain{font-size:10px;}
        .pageset{page-break-after: always; margin:0;}
        .pageset table{font-size:10px;}
        .title{font-size:12px;}
    }
</style>
<?php
require_once('../../initialize.php');
//require_once('../../tcpdf/config/lang/eng.php');
//require_once('../../tcpdf/tcpdf.php');

global $database;

ini_set('max_execution_time', 1000);

//function for branch
function branch($database){
    $query = $database->execute("SELECT CONCAT(b.Code, ' ', b.Name) branchName, b.ID
                                FROM branchparameter bp
                                INNER JOIN branch b ON b.ID = bp.BranchID");
    return $query;
}
$branch = branch($database);

//function for fetching the overdue aging
function overdueaging($database, $agingdate, $ibmfrom, $ibmto, $agerange, $age, $branch){
    $result = array();
    $date = date('Y-m-d', strtotime($agingdate));

    $limits = array();
    $count = 0;
    foreach($agerange as $val){
        if(in_array($val, $age)){
            $limits[$count] = "";
        }else{
            $limits[$count] = "LIMIT 0";
        }
        $count++;
    }

    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND ((ibm.ID BETWEEN $ibmfrom AND $ibmto) AND ((ibm.ID BETWEEN $ibmto AND $ibmfrom)))";

    $unionquery = "FROM customeraccountsreceivable car
					INNER JOIN branch b ON b.ID = SPLIT_STR(car.HOGeneralID, '-', 2)
                    INNER JOIN salesinvoice si ON car.SalesInvoiceID = si.ID
						AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
						AND LOCATE(CONCAT('-', b.ID), car.HOGeneralID) > 0
                    INNER JOIN customer igs ON igs.ID = si.CustomerID
						AND LOCATE(CONCAT('-', b.ID), igs.HOGeneralID) > 0
                    INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = igs.ID
                        AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = igs.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
						AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                    INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
                    INNER JOIN tpi_credit tc ON tc.CreditTermID = si.CreditTermID
						AND tc.CustomerID = si.CustomerID
						AND LOCATE(CONCAT('-', b.ID), tc.HOGeneralID) > 0
                    INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
						AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
                    WHERE si.StatusID = 7 AND car.OutstandingAmount > 0
                    AND DATE(si.TxnDate) <= '$date'
					AND b.ID = $branch
                    $ibmrange";

    $unionqueryx = "";
    $querycounter = 0;
    //for amount = 0
    if($limits[0] == ""){
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, IFNULL(SUM(car.OutstandingAmount),0.000) Amount0,
                        0.0000 Amount1_30, 0.0000 Amount31_60, 0.0000 Amount61_90, 0.0000 Amount91_120,
                        0.0000 Amount121_150, 0.0000 Amount151_180, 0.0000 Amount181,
                        ibm.Name IBMName, ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        $unionquery
                        AND car.DaysDue = 0
                        GROUP BY igs.ID $limits[0]";
        $querycounter++;
    }

    //for amount between 1 to 30
    if($limits[2] == ""){
        if($querycounter > 0){
            $unionqueryx .= " UNION ALL ";
        }
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, 0.0000 Amount0,
                        IFNULL(SUM(car.OutstandingAmount),0.0000) Amount1_30, 0.0000 Amount31_60, 0.0000 Amount61_90,
                        0.0000 Amount91_120, 0.0000 Amount121_150, 0.0000 Amount151_180, 0.0000 Amount181,
                        ibm.Name IBMName, ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        $unionquery
                        AND car.DaysDue BETWEEN 1 AND 30
                        GROUP BY igs.ID $limits[2]";
        $querycounter++;
    }

    //for amount between 31 AND 60
    if($limits[4] == ""){
        if($querycounter > 0){
            $unionqueryx .= " UNION ALL ";
        }
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, 0.0000 Amount0,
                        0.0000  Amount1_30, IFNULL(SUM(car.OutstandingAmount),0.0000) Amount31_60, 0.0000 Amount61_90,
                        0.0000 Amount91_120, 0.0000 Amount121_150, 0.0000 Amount151_180, 0.0000 Amount181,
                        ibm.Name IBMName, ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        $unionquery
                        AND car.DaysDue BETWEEN 31 AND 60
                        GROUP BY igs.ID $limits[4]";
        $querycounter++;
    }

    //for amount between 61 AND 90
    if($limits[6] == ""){
        if($querycounter > 0){
            $unionqueryx .= " UNION ALL ";
        }
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, 0.0000 Amount0,
                        0.0000  Amount1_30, 0.0000 Amount31_60, IFNULL(SUM(car.OutstandingAmount),0.0000)  Amount61_90,
                        0.0000 Amount91_120, 0.0000 Amount121_150, 0.0000 Amount151_180, 0.0000 Amount181,
                        ibm.Name IBMName, ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        $unionquery
                        AND car.DaysDue BETWEEN 61 AND 90
                        GROUP BY igs.ID $limits[6]";
        $querycounter++;
    }

    //for amount between 91 AND 120
    if($limits[1] == ""){
        if($querycounter > 0){
            $unionqueryx .= " UNION ALL ";
        }
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, 0.0000 Amount0,
                        0.0000  Amount1_30, 0.0000 Amount31_60, 0.0000  Amount61_90,
                        IFNULL(SUM(car.OutstandingAmount),0.0000) Amount91_120, 0.0000 Amount121_150,
                        0.0000 Amount151_180, 0.0000 Amount181,
                        ibm.Name IBMName, ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        $unionquery
                        AND car.DaysDue BETWEEN 91 AND 120
                        GROUP BY igs.ID $limits[1]";
        $querycounter++;
    }

    //for amount between 121 AND 150
    if($limits[3] == ""){
        if($querycounter > 0){
            $unionqueryx .= " UNION ALL ";
        }
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, 0.0000 Amount0,
                        0.0000  Amount1_30, 0.0000 Amount31_60, 0.0000  Amount61_90, 0.0000 Amount91_120,
                        IFNULL(SUM(car.OutstandingAmount),0.0000) Amount121_150,
                        0.0000 Amount151_180, 0.0000 Amount181,
                        ibm.Name IBMName, ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        $unionquery
                        AND car.DaysDue BETWEEN 121 AND 150
                        GROUP BY igs.ID $limits[3]";
        $querycounter++;
    }

    //for amount between 151 AND 180
    if($limits[5] == ""){
        if($querycounter > 0){
            $unionqueryx .= " UNION ALL ";
        }
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, 0.0000 Amount0,
                        0.0000  Amount1_30, 0.0000 Amount31_60, 0.0000  Amount61_90,
                        0.0000 Amount91_120, 0.0000 Amount121_150,
                        IFNULL(SUM(car.OutstandingAmount),0.0000) Amount151_180,
                        0.0000 Amount181, ibm.Name IBMName, ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        $unionquery
                        AND car.DaysDue BETWEEN 151 AND 180
                        GROUP BY igs.ID $limits[5]";
        $querycounter++;
    }


    //for amount >= 181
    if($limits[7] == ""){
        if($querycounter > 0){
            $unionqueryx .= " UNION ALL ";
        }
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, 0.0000 Amount0,
                        0.0000  Amount1_30, 0.0000 Amount31_60, 0.0000  Amount61_90,
                        0.0000 Amount91_120, 0.0000 Amount121_150, 0.0000 Amount151_180,
                        IFNULL(SUM(car.OutstandingAmount),0.0000) Amount181, ibm.Name IBMName,
                        ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        $unionquery
                        AND car.DaysDue >= 181
                        GROUP BY igs.ID $limits[7]";
        $querycounter++;
    }


    //check if the age is null
    if($querycounter == 0){
        $unionqueryx .= "SELECT igs.Name, igs.Code, igs.ID, 0.0000 Amount0,
                        0.0000  Amount1_30, 0.0000 Amount31_60, 0.0000  Amount61_90,
                        0.0000 Amount91_120, 0.0000 Amount121_150, 0.0000 Amount151_180,
                        0.0000 Amount181, ibm.Name IBMName, ibm.Code IBMCode, ibm.ID IBMID, SUM(tc.ApprovedCL) ApprovedCL
                        FROM salesinvoice si
						INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                        INNER JOIN customer igs ON igs.ID = si.CustomerID
							AND LOCATE(CONCAT('-', b.ID), igs.HOGeneralID) > 0							
                        INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = igs.ID
                            AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = igs.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
							AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                        INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
							AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
                        WHERE si.StatusID = 7 AND
                        DATE_FORMAT(si.TxnDate, '%Y-%m-%d') <= '$date'
						AND b.ID = $branch
                        $ibmrange
                        GROUP BY igs.ID";
    }
	
    $query = $database->execute("SELECT `Name`, `Code`, `ID`,
                                    Amount0, Amount1_30, Amount31_60, Amount61_90, Amount121_150, Amount151_180, Amount91_120, Amount181, xtotal,
                                    IBMName, IBMCode, IBMID, ApprovedCL, (SELECT ApprovedCL FROM tpi_credit WHERE CustomerID = IBMID AND LOCATE('-$branch', HOGeneralID) > 0) ApproveCLIBM
                                    FROM
                                            (SELECT
                                            `Name`, `Code`, `ID`,
                                            IFNULL(SUM(Amount0),0.0000) Amount0,
                                            IFNULL(SUM(Amount1_30),0.0000) Amount1_30,
                                            IFNULL(SUM(Amount31_60),0.0000) Amount31_60,
                                            IFNULL(SUM(Amount61_90),0.0000) Amount61_90,
                                            IFNULL(SUM(Amount91_120),0.0000) Amount91_120,
                                            IFNULL(SUM(Amount121_150),0.0000) Amount121_150,
                                            IFNULL(SUM(Amount151_180),0.0000) Amount151_180,
                                            IFNULL(SUM(Amount181),0.0000) Amount181,
                                            IFNULL(SUM(Amount181),0.0000) + IFNULL(SUM(Amount0),0.0000) + IFNULL(SUM(Amount1_30),0.0000) +
                                            IFNULL(SUM(Amount31_60),0.0000) + IFNULL(SUM(Amount61_90),0.0000) + IFNULL(SUM(Amount91_120),0.0000) +
                                            IFNULL(SUM(Amount121_150),0.0000)  +  IFNULL(SUM(Amount151_180),0.0000) xtotal,
                                            IBMName, IBMCode, IBMID, ApprovedCL
                                            FROM (
                                                $unionqueryx
                                                    ) `table`
                                            GROUP BY `ID` ORDER BY `Name` ) `xtable`
                                    WHERE xtotal != 0
                                    GROUP BY `ID` ORDER BY  `Name`");
    return $query;
}

$asofdate = date("Y-m-d", strtotime($_GET['agingasof']));
$ibmfrom = $_GET['ibmfrom'];
$ibmto = $_GET['ibmto'];
$ibmfromCode = $_GET['ibmfromCode'];
$ibmtoCode = $_GET['ibmtoCode'];
$agerange = explode("_", $_GET['agerange']);
$age = explode("_", $_GET['age']);
$branchID = $_GET['branch'];

$rs_aging_report = overdueaging($database, $asofdate, $ibmfrom, $ibmto, $agerange, $age, $branchID);
$txnRegister='';

$cnt=0;
$total=0;
$total_0=0;
$total_1=0;
$total_2=0;
$total_3=0;
$total_4=0;
$total_5=0;
$total_6=0;
$total_7=0;

$tmp_tot_total=0;
$tmp_tot_total0=0;
$tmp_tot_total1=0;
$tmp_tot_total2=0;
$tmp_tot_total3=0;
$tmp_tot_total4=0;
$tmp_tot_total5=0;
$tmp_tot_total6=0;
$tmp_tot_total7=0;

$tot_total=0;
$tot_total0=0;
$tot_total1=0;
$tot_total2=0;
$tot_total3=0;
$tot_total4=0;
$tot_total5=0;
$tot_total6=0;
$tot_total7=0;

$ibmrangedisplay = ($ibmfrom == 0 AND $ibmto == 0)?"ALL":trim($ibmfromCode).'&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;'.trim($ibmtoCode);

$header='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="20" align="center"><strong class="title">IBM Overdue Aging Report</strong></td>
            </tr>
        </table>
        <div style="border-top:2px solid; border-bottom:2px solid; margin:10px 0; padding:10px 0;">
        <table width="100%" border="0" class="tableheadermain" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50px" height="20" align="left"><strong>Date</strong></td>
                <td style="width:3%" align="center"><strong>:</strong></td>
                <td height="20" align="left">'.date('m/d/Y').'</td>
            </tr>
        </table>
        </div>
        <div>
        <table width="100%" border="0" class="tableheadermain" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50px" height="20" align="left"><strong>Branch</strong></td>
                <td style="width:3%" align="center"><strong>:</strong></td>
                <td height="20" align="left">'.$branch->fetch_object()->branchName.'</td>
                <td width="70px" height="20" align="left"><strong>Aging as of</strong></td>
                <td style="width:3%" align="center"><strong>:</strong></td>
                <td height="20" align="left">'.$_GET['agingasof'].'</td>
                <td width="80px" height="20" align="left"><strong>IBM From/To</strong></td>
                <td style="width:3%" align="center"><strong>:</strong></td>
                <td height="20" align="left">'.$ibmrangedisplay.'</td>
            </tr>
        </table>
        </div>
        <br />
        <br />
       ';

echo $header;
//$rs_aging_report = $sp->spSelectOverdueAgingReport($database, $fromDate, $toDate, $c, $s);

if($rs_aging_report->num_rows) {

  $j=1;

  // Estimated string length of product description when the TCPDF engine will wrap the text, therefore consuming an extra row.
  $productLenThreshold=30;

  // Threshold to determine whether the number of rows per page should be decremented to accomodate product whose length is greater than $productLenThreshold
  $rowDeletionThreshold=5;

  // Counter of current row deletion threshold.
  $deletionThreshold=0;

  // Estimated number of rows per page.

  $ibmarray = array();
  while($row=$rs_aging_report->fetch_object()) {
	
	$cnt++;
	
	if($cnt <= 20){
		$numRowsPerPage=20;
		$numRows = $numRowsPerPage;
	}else{
		$numRowsPerPage=30;
		$numRows = $numRowsPerPage;
	}

    $totalCL += $row->ApprovedCL;
    $total   = number_format($row->xtotal, 2);
    $total_0 = number_format($row->Amount0, 2);
    $total_1 = number_format($row->Amount1_30, 2);
    $total_2 = number_format($row->Amount31_60, 2);
    $total_3 = number_format($row->Amount61_90, 2);
    $total_4 = number_format($row->Amount91_120, 2);
    $total_5 = number_format($row->Amount121_150, 2);
    $total_6 = number_format($row->Amount151_180, 2);
    $total_7 = number_format($row->Amount181, 2);

    $tmp_tot_total  += $row->xtotal;
    $tmp_tot_total0 += $row->Amount0;
    $tmp_tot_total1 += $row->Amount1_30;
    $tmp_tot_total2 += $row->Amount31_60;
    $tmp_tot_total3 += $row->Amount61_90;
    $tmp_tot_total4 += $row->Amount91_120;
    $tmp_tot_total5 += $row->Amount121_150;
    $tmp_tot_total6 += $row->Amount151_180;
    $tmp_tot_total7 += $row->Amount181;

    if($j < $numRows) {
	
        if(!in_array($row->IBMID, $ibmarray)){
            $txnRegister.='<tr>
                        <td align="left">&nbsp;'.$row->IBMCode.'</td>
                        <td align="center" style="border-left:1px solid white; border-right:1px solid white;">SC</td>
                        <td align="left">'.$row->IBMName.'&nbsp;&nbsp;</td>
                        <td align="right">'.number_format($row->ApproveCLIBM, 2).'&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                        <td align="right">0.00&nbsp;&nbsp;</td>
                    </tr>';
            $ibmarray[] = $row->IBMID;
        }

      $txnRegister.='<tr>
                      <td align="left">&nbsp;'.$row->Code.'</td>
                      <td align="center" style="border-left:1px solid white; border-right:1px solid white;">DL</td>
                      <td align="left">'.$row->Name.'&nbsp;&nbsp;</td>
                      <td align="right">'.number_format($row->ApprovedCL, 2).'&nbsp;&nbsp;</td>
                      <td align="right">'.$total.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_0.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_1.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_2.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_3.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_4.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_5.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_6.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_7.'&nbsp;&nbsp;</td>
                     </tr>';

      // Determine if product string length is greater than threshold
      // If it is, subtract the number of rows per page if necessary
      if(strlen($row->Name)>$productLenThreshold) {

        // Subtract the number of rows only if we reached threshold of the number
        // of rows whose string length is greater than product length threshold
        if($deletionThreshold!=$rowDeletionThreshold) {

          $numRows--;
          $deletionThreshold++;
        } else {

          // Reset the current count.
          $deletionThreshold=0;
        }
      }
      $j++;
	  
    } else {
        if(!in_array($row->IBMID, $ibmarray)){
            $txnRegister.='<tr>
                        <td align="left">&nbsp;'.$row->IBMCode.'</td>
                        <td align="center" style="border-left:1px solid white; border-right:1px solid white;">SC</td>
                        <td align="left">'.$row->IBMName.'&nbsp;&nbsp;</td>
                        <td align="right">'.number_format($row->ApproveCLIBM, 2).'&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                        <td align="right" width="9%">0.00&nbsp;&nbsp;</td>
                    </tr>';
            $ibmarray[] = $row->IBMID;
        }
        $txnRegister .= '<tr>
                      <td align="left">&nbsp;'.$row->Code.'</td>
                      <td align="center" style="border-left:1px solid white; border-right:1px solid white;">DL</td>
                      <td align="left">'.$row->Name.'&nbsp;&nbsp;</td>
                      <td align="right">'.number_format($row->ApprovedCL, 2).'&nbsp;&nbsp;</td>
                      <td align="right">'.$total.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_0.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_1.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_2.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_3.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_4.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_5.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_6.'&nbsp;&nbsp;</td>
                      <td align="right">'.$total_7.'&nbsp;&nbsp;</td>
                     </tr>';

      $html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
              <tr style="font-weight:bold;">
                <td align="center" width="35%" colspan="3">IBM / IGS</td>
                <td align="center">Cr. Limit</td>
                <td align="center">Total Outstanding</td>
                <td align="center">Not Yet Due</td>
                <td align="center">(1-30)</td>
                <td align="center">(31-60)</td>
                <td align="center">(61-90)</td>
                <td align="center">(91-120)</td>
                <td align="center">(121-150)</td>
                <td align="center">(151-180)</td>
                <td align="center">(180-over)</td>
              </tr>'.$txnRegister.'</table>';

      // We only print the page to PDF if we have enough rows to print.
      //$pdf->writeHTML($html, true, false, true, false, '');
      //$pdf->AddPage();

      echo "<div class='pageset'>";
      echo $html;
      echo "</div>";
      // Reset the row counter and the details.
      $txnRegister='';
      $j=1;
      $numRows=$numRowsPerPage;
    }
  } $rs_aging_report->close();

  $totalCL = number_format($totalCL, 2);
  $tot_total=number_format($tmp_tot_total, 2);
  $tot_total0=number_format($tmp_tot_total0, 2);
  $tot_total1=number_format($tmp_tot_total1, 2);
  $tot_total2=number_format($tmp_tot_total2, 2);
  $tot_total3=number_format($tmp_tot_total3, 2);
  $tot_total4=number_format($tmp_tot_total4, 2);
  $tot_total5=number_format($tmp_tot_total5, 2);
  $tot_total6=number_format($tmp_tot_total6, 2);
  $tot_total7=number_format($tmp_tot_total7, 2);

  // If we have gone through all the items and there are unprinted items left, print them one last time.
  if($txnRegister!='') {

    $html='<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
            <tr style="font-weight:bold;">
              <td align="center" width="35%" colspan="3">IBM / IGS</td>
              <td align="center">Total Outstanding</td>
              <td align="center">Cr. Limit</td>
              <td align="center">Not Yet Due</td>
              <td align="center">(1-30)</td>
              <td align="center">(31-60)</td>
              <td align="center">(61-90)</td>
              <td align="center">(91-120)</td>
              <td align="center">(121-150)</td>
              <td align="center">(151-180)</td>
              <td align="center">(180-over)</td>
            </tr>'.$txnRegister;
  } else $html='<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">';

  $html.='<tr><td height="20" colspan="13" style="border-left:1px solid white; border-right:1px solid white;" align="center">&nbsp;</td></tr>
          <tr>
            <td align="right" colspan="3"><strong>TOTAL (BRANCH)</strong></td>
            <td align="right">'.$totalCL.'</td>
            <td align="right">'.$tot_total.'&nbsp;&nbsp;</td>
            <td align="right">'.$tot_total0.'&nbsp;&nbsp;</td>
            <td align="right">'.$tot_total1.'&nbsp;&nbsp;</td>
            <td align="right">'.$tot_total2.'&nbsp;&nbsp;</td>
            <td align="right">'.$tot_total3.'&nbsp;&nbsp;</td>
            <td align="right">'.$tot_total4.'&nbsp;&nbsp;</td>
            <td align="right">'.$tot_total5.'&nbsp;&nbsp;</td>
            <td align="right">'.$tot_total6.'&nbsp;&nbsp;</td>
            <td align="right">'.$tot_total7.'&nbsp;&nbsp;</td>
          </tr></table>';

    //$pdf->writeHTML($html, true, false, true, false, '');
        echo "<div class='pageset'>";
        echo $html;
        echo "</div>";
} else {

  $txnRegister='<tr align="center"><td colspan="13" align="center"><strong>No record(s) to display.</strong></td></tr>';

  $html='<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr style="font-weight:bold;">
            <td align="center" width="35%" colspan="3">IBM / IGS</td>
            <td align="center">Total Outstanding</td>
            <td align="center">Cr. Limit</td>
            <td align="center">Not Yet Due</td>
            <td align="center">(1-30)</td>
            <td align="center">(31-60)</td>
            <td align="center">(61-90)</td>
            <td align="center">(91-120)</td>
            <td align="center">(121-150)</td>
            <td align="center">(151-180)</td>
            <td align="center">(180-over)</td>
          </tr>'.$txnRegister.'</table>';
          //$pdf->writeHTML($html, true, false, true, false, '');
        echo "<div class='pageset'>";
        echo $html;
        echo "</div>";
}

// Reset pointer to the last page.
//$pdf->lastPage();

// Close and output the PDF document.
//$pdf->Output('AgingReport.pdf', 'I');
?>
<script>
    window.print();
</script>