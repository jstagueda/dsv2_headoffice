<?php

    $rs_branch = $sp->spSelectBranch($database, -1, "");
    $rs_mtype  = $sp->spSelectMovementType($database, -4, "");
    
    function intransitreport($database, $datefrom, $dateto, $branchID, $movementTypeID, $page, $total, $isTotal){
        
        $movementCond = ($movementTypeID > 0)?"AND mt.ID = $movementTypeID":"";
        $branchCond = ($branchID > 0)?"AND b.ID = $branchID":"";
        $start = ($page > 1)?(($page-1) * $total):0;
        $limit = (!$isTotal)?"LIMIT $start, $total":"";
        $datefrom = date("Y-m-d", strtotime($datefrom));
        $dateto = date("Y-m-d", strtotime($dateto));
        
        $query = $database->execute("SELECT
                                        DATE_FORMAT(invIO.TransactionDate, '%m/%d/%Y') TransactionDate,
                                        invD.PicklistRefNo,
                                        invD.ShipmentAdviseNo,
                                        p.Code,
                                        p.Name,
                                        invD.LoadedQty,
                                        invIO.DocumentNo,
                                        DATE_FORMAT(invD.EnrollmentDate, '%m/%d/%Y') EnrollmentDate,
                                        mt.Code MovementCode
                                        FROM inventoryinout invIO
                                        INNER JOIN movementtype mt ON mt.ID = invIO.MovementTypeID
                                        INNER JOIN inventoryinoutdetails invD ON invD.InventoryInOutID = invIO.ID
                                            AND SPLIT_STR(invD.HOGeneralID, '-', 2) = SPLIT_STR(invIO.HOGeneralID, '-', 2)
                                        INNER JOIN product p ON p.ID = invD.ProductID
                                        INNER JOIN branch b ON b.ID = invIO.BranchID OR b.ID = invIO.ToBranchID
                                        WHERE DATE_FORMAT(invIO.TransactionDate, '%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'
                                        AND SPLIT_STR(invIO.HOGeneralID, '-', 2) = b.ID
                                        $movementCond $branchCond
                                        ORDER BY invIO.TransactionDate DESC
                                        $limit
                                    ");
        return $query;
    }
    
    //getting post value from submitted form
    $datefrom = date("m/d/Y", strtotime((isset($_POST['datestart']))?$_POST['datestart']:date('m/d/Y')));
    $dateto = date("m/d/Y", strtotime((isset($_POST['dateend']))?$_POST['dateend']:date('m/d/Y')));
    $page = (isset($_POST['page']))?$_POST['page']:1;
    $branchID = (isset($_POST['branch']))?$_POST['branch']:0;
    $movementTypeID = (isset($_POST['movementtype']))?$_POST['movementtype']:0;
    
    //getting get value for printing...
    if(isset($_GET['datestart']) AND !empty($_GET['datestart'])){
        $datefrom = $_GET['datestart'];
    }
    
    if(isset($_GET['dateend']) AND !empty($_GET['dateend'])){
        $dateto = $_GET['dateend'];
    }
    
    if(isset($_GET['branch']) AND $_GET['branch'] != ""){
        $branchID = $_GET['branch'];
    }
    
    if(isset($_GET['movementtype']) AND $_GET['movementtype'] != ""){
        $movementTypeID = $_GET['movementtype'];
    }
    
    //saving the query to variables
    $countintransitreport = intransitreport($database, $datefrom, $dateto, $branchID, $movementTypeID, $page, 10, true);
    $intransitreport = intransitreport($database, $datefrom, $dateto, $branchID, $movementTypeID, $page, 10, false);

?>
