<?php

function customerrange($database, $searched, $branchid){
    $query = $database->execute("SELECT c.Name, c.Code, c.ID
							FROM customer c
							WHERE c.CustomerTypeID = 1
							AND ((c.Name LIKE '$searched%') OR (c.Code LIKE '$searched%'))
							AND LOCATE('-$branchid', c.HOGeneralID) > 0
							ORDER BY c.Name
							LIMIT 10");
    return $query;
}

function loyaltycoverage($database, $searched)
{
    $query = $database->execute("SELECT lyt.id ID, lyt.code Code, lyt.description Name 
								 FROM loyalty_program lyt
								 WHERE ((lyt.code LIKE '$searched%') OR (lyt.description LIKE '$searched%'))
								 ORDER BY lyt.code
								 LIMIT 10
							   ");
    return $query;
}

function loyaltyprdlist($database, $datefrom, $dateto, $loyaltyID,$istotal, $page, $total)
{
    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
	
    $datefrom = date("Y-m-d", strtotime($datefrom));
    $dateto = date("Y-m-d", strtotime($dateto));
	
    $query = $database->execute("SELECT p.id, p.code `code`, p.name `desc`, date(lyt.start_date) Start_date, date(lyt.End_date) End_date  
								 FROM loyalty_program_productlist lyt
								 INNER JOIN product p ON p.id = lyt.productid
								 WHERE DATE(lyt.start_date) >= '$datefrom'
								 AND DATE(lyt.End_date)   <= '$dateto'
								 and lyt.loyalty_programid = $loyaltyID
								 ORDER BY p.code
							     $limit");
    return $query;
}

function countdate($date){
    global $database, $customerfrom, $customerto, $page, $total, $branchID;
    $advancepo = advancepo($database, $date, $date, $customerfrom, $customerto, false, $page, $total, $branchID);        
    return $advancepo->num_rows;
}
?>
