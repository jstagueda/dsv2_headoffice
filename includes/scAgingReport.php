<?php
/*
 * modified by joebert italia
 * august 30, 2013
 *
 */

if(!ini_get('display_errors')) ini_set('display_errors', 1);

include IN_PATH.DS."pagination.php";
ini_set('max_execution_time', 1000);
global $database;


//get the Aging as of Date
if(isset($_POST['AgingasOf'])){
    $agingasof = $_POST['AgingasOf'];
}else{
    $agingasof = date("m/d/Y");
}

$page = (isset($_POST['page']))?$_POST['page']:1;

//getting the ibm range
if(isset($_POST['cboCustomerFrom'])){
    $ibmfrom = $_POST['cboCustomerFromHidden'];
}else{
    $ibmfrom = 0;
}

if(isset($_POST['cboCustomerTo'])){
    $ibmto = $_POST['cboCustomerToHidden'];
}else{
    $ibmto = 0;
}

$search = (isset($_POST['searchParam']))?$_POST['searchParam']:"";
$branch = (isset($_POST['branch']))?$_POST['branch']:0;

//function for getting the ibm
function spSelectIBMAjax($database, $search, $branch){

    $query = $database->execute("SELECT CONCAT(TRIM(c.Code), '-', c.Name) IBMCode, c.ID, c.Name, TRIM(c.Code) `Code` FROM customer c
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
								INNER JOIN sfm_level sfm ON c.CustomerTypeID = sfm.codeID AND sfm.has_downline = 1
								INNER JOIN sfm_manager sm ON sm.mCode = c.Code
								AND LOCATE(CONCAT('-', b.ID), sm.HOGeneralID) > 0
                                WHERE (c.Name LIKE '$search%' OR c.Code LIKE '$search%')
								AND b.ID = $branch
                                ORDER BY TRIM(c.Code) LIMIT 10");
    return $query;
}

$rsIBMFrom = spSelectIBMAjax($database, $search, $branch);
$rsIBMTo = spSelectIBMAjax($database, $search, $branch);

//set age range in array
$agerange = array(0 => "Not Yet Due", "91 - 120" => "91 - 120", "1 - 30" => "1 - 30", "121 - 150" => "121 - 150", "31 - 60" => "31 - 60", "151 - 180" => "151 - 180", "61 - 90" => "61 - 90", 181 => "181 - over");

//function for fetching the overdue aging
function overdueaging($database, $agingdate, $ibmfrom, $ibmto, $page, $total, $agerange, $age, $isCount, $branch){
    $start = ($page > 1)?(($page-1) * $total):0;
    $result = array();
    $date = date('Y-m-d', strtotime($agingdate));

    if(!$isCount){
        $limit = "LIMIT $start, $total";
    }else{$limit = "";}

    $limits = array();
    $count = 0;
    foreach($agerange as $key => $val){
        if(in_array($key, $age)){
            $limits[$count] = "";
        }else{
            $limits[$count] = "LIMIT 0";
        }
        $count++;
    }

    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND ((ibm.ID BETWEEN $ibmfrom AND $ibmto) OR (ibm.ID BETWEEN $ibmto AND $ibmfrom))";

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
                                    GROUP BY `ID` ORDER BY  `Name` $limit");

    if(!$isCount){

        while($res = $query->fetch_object()){
            $result['Customer'][] = $res->Name;
            $result['Code'][] = $res->Code;
            $result['ID'][] = $res->ID;
            $result['Amount0'][] = $res->Amount0;
            $result['Amount1_30'][] = $res->Amount1_30;
            $result['Amount31_60'][] = $res->Amount31_60;
            $result['Amount61_90'][] = $res->Amount61_90;
            $result['Amount91_120'][] = $res->Amount91_120;
            $result['Amount121_150'][] = $res->Amount121_150;
            $result['Amount151_180'][] = $res->Amount151_180;
            $result['Amount181'][] = $res->Amount181;
            $result['xtotal'][] = $res->xtotal;
        }
        return $result;

    }else{
        return $query->num_rows;
    }
}