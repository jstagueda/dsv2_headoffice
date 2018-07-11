<?php

function IBMrange($database, $searched){

    $query = $database->execute("SELECT * FROM customer c INNER JOIN sfm_manager sf ON sf.mCode = c.Code
								INNER JOIN sfm_level sl ON sl.codeID = c.CustomerTypeID
                                WHERE (c.Name LIKE '$searched%' OR c.Code LIKE '$searched%')
								AND sl.has_downline = 1
                                LIMIT 10");
    return $query;
}

function ibmsalesperformancereport($database, $ibmfrom, $ibmto, $prodcat, $campaign, $istotal, $page, $total, $branch){

    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":" AND ((ibm.ID BETWEEN $ibmfrom AND $ibmto) OR (ibm.ID BETWEEN $ibmto AND $ibmfrom))";

    $query = $database->execute("SELECT
                                *,
                                0 AverageOrder
                                FROM (SELECT
                                        sfa.CampaignCode,
                                        ibm.Code,
                                        pmg.Code PMGCode,
                                        ibm.ID IBMID,
                                        ibm.Name,
                                        IFNULL(SUM(sc.TotalRecruits), 0)    NewRecruits,
                                        IFNULL(SUM(sc.TotalReactivated), 0)    Reactivated,
                                        IFNULL(SUM(sc.TotalTerminated), 0)    `Terminated`,
                                        -- IFNULL(SUM(sc.LessTotalTransferred), 0)    RemoveOthers,
                                        -- IFNULL(SUM(sc.PlusTotalTransferred), 0)    AddOthers,
										0 RemoveOthers,
                                        0 AddOthers,
                                        IFNULL(sc.TotalBeginningCount, 0)    BeginningCount,
                                        IFNULL(sc.TotalEndCount, 0)          EndCount,
                                        pmg.ID PMGID,
                                        IFNULL(sfa.TotalDGSAmount, 0)         SalesAmount
                                    FROM tpi_sfasummary_pmg sfa
									INNER JOIN branch b ON b.ID = SPLIT_STR(sfa.HOGeneralID, '-', 2)
                                    INNER JOIN customer ibm ON ibm.ID = sfa.IBMID
										AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
                                    LEFT JOIN staffcount sc
                                        ON sc.IBMID = ibm.ID
                                        AND sc.CampaignCode = sfa.CampaignCode
										AND LOCATE(CONCAT('-', b.ID), sc.HOGeneralID) > 0
                                    INNER JOIN tpi_pmg pmg ON pmg.Code = sfa.PMGType
									WHERE sfa.CampaignCode = '$campaign'
									AND pmg.ID = $prodcat
									AND b.ID = $branch
									$ibmrange
                                    GROUP BY ibm.ID, sfa.CampaignCode, sfa.PMGType
                                    ORDER BY sc.TotalBeginningCount) a                                    
                                    $limit");

    return $query;

}

?>
