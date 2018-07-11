<?php


function product($database, $searched){
    
    $query = $database->execute("SELECT Code, Name, ID FROM product 
                                    WHERE ((Name LIKE '$searched%') OR (Code LIKE '$searched%'))
                                    ORDER BY Name LIMIT 10");
    return $query;
    
}

function productsubstitute($database, $branch, $datefrom, $dateto, $productlinefrom, $productlineto, $productfrom, 
                            $productto, $istotal, $page, $total){
    
    $productline = "AND ((pl.ID BETWEEN $productlinefrom AND $productlineto) 
                    OR (pl.ID BETWEEN $productlineto AND $productlinefrom))";
    
    $productrange = ($productfrom == 0 AND $productto == 0)?"":"AND ((p.ID BETWEEN $productfrom AND $productto) 
                                                            OR (p.ID BETWEEN $productto AND $productfrom))";
    $datefrom = date("Y-m-d", strtotime($datefrom));
    $dateto = date("Y-m-d", strtotime($dateto));
    
    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    
    $query = $database->execute("SELECT
                                    TRIM(p.Code) OrigCode, CONCAT(pl.Code, ' - ', pl.Name) OrigProdLine, p.Name OrigDesc,
                                    TRIM(sub.Code) SubCode, CONCAT(subpl.Code, ' - ', subpl.Name) SubProdLine, sub.Description SubDesc,
                                    DATE_FORMAT(ps.EnrollmentDate, '%Y/%m/%d') ProdDate
                                    FROM productsubstitute ps
                                    INNER JOIN product p ON p.ID = ps.ProductID
                                    INNER JOIN product sub ON ps.SubstituteID = sub.ID
                                    INNER JOIN productlevel pl ON pl.ID = p.ProductLevelID
                                    INNER JOIN productlevel subpl ON subpl.ID = sub.ProductLevelID
                                    WHERE DATE_FORMAT(ps.EnrollmentDate, '%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'
                                    $productline
                                    $productrange
                                    ORDER BY ps.EnrollmentDate DESC
                                    $limit
                                ");
    return $query;
}

function branchselect($database, $branchid){
    
    $query = $database->execute("SELECT * FROM branch WHERE ID = $branchid");
    return $query;
    
}

?>
