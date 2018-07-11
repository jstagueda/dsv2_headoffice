<?php

include "../../../initialize.php";
include IN_PATH.DS."pagination.php";

if($_GET['action'] == "pagination"){
    $txtPromoCodeDesc = $_POST['txtPromoCodeDesc'];
    $txtProductCode = $_POST['txtProductCode'];
    $page = $_POST['page'];
    $total = 10;
    $start = ($page > 1)?(($page - 1) * $total):0;

    $viewpromocode = $database->execute("SELECT sp.Code, sp.Description,
                                    DATE_FORMAT(sp.StartDate, '%m/%d/%Y') StartDate,
                                    DATE_FORMAT(sp.EndDate, '%m/%d/%Y') EndDate,
                                    sp.ID SPID, p.ID PID
                                    FROM specialpromobuyinandentitlement spe
                                    INNER JOIN product p ON p.ID = spe.ProductID
                                    INNER JOIN specialpromo sp ON sp.ID = spe.SpecialPromoID
                                    WHERE (sp.Code LIKE '%$txtPromoCodeDesc%'
                                    OR sp.Description LIKE '%$txtPromoCodeDesc%')
                                    AND p.Code LIKE '%$txtProductCode%'
                                    GROUP BY sp.ID
									ORDER BY sp.ID DESC
                                    LIMIT $start, $total");

    $viewpromocodeCount = $database->execute("SELECT sp.Code, sp.Description,
                                    DATE_FORMAT(sp.StartDate, '%m/%d/%Y') StartDate,
                                    DATE_FORMAT(sp.EndDate, '%m/%d/%Y') EndDate,
                                    sp.ID SPID, p.ID PID
                                    FROM specialpromobuyinandentitlement spe
                                    INNER JOIN product p ON p.ID = spe.ProductID
                                    INNER JOIN specialpromo sp ON sp.ID = spe.SpecialPromoID
                                    WHERE (sp.Code LIKE '%$txtPromoCodeDesc%'
                                    OR sp.Description LIKE '%$txtPromoCodeDesc%')
                                    AND p.Code LIKE '%$txtProductCode%'
                                    GROUP BY sp.ID
									ORDER BY sp.ID DESC");

    $header = '<table width="100%"   border="0" cellpadding="0" cellspacing="0" class="bordergreen">
                    <tr align=\'center\' class="trheader">
                        <td>Promo Code</td>
                        <td>Promo Title</td>
                        <td>Start Date</td>
                        <td>End Date</td>
                    </tr>';

    $footer = '</table>';

    if($viewpromocode->num_rows){
        echo $header;
        while($res = $viewpromocode->fetch_object()){
            echo '<tr class="trlist">
                    <td width="15%" align=center>
						<a href="javascript:void(0);" onclick="return showSpecialPromo('.$res->SPID.', '.$res->PID.')">'.$res->Code.'</a>
					</td>
                    <td align="center">'.$res->Description.'</td>
                    <td width="10%" align=center>'.$res->StartDate.'</td>
                    <td width="10%" align=center>'.$res->EndDate.'</td>
                </tr>';
        }
        echo $footer;
    }else{
        echo $header;
        echo '<tr class="trlist">
                <td colspan=\'4\' align="center">No result found.</td>
            </tr>';
        echo $footer;
    }

    echo "<div style='margin-top:10px;'>".AddPagination($total, $viewpromocodeCount->num_rows, $page)."</div>";
}

?>
