<?php
	//print_r($_POST);
	//die();
	//for future..
	include "../../../initialize.php";
	if($_POST['request']=='list of ibm by branch'){
		//parameters..
		$search 	= $_POST['searched'];
		$byBranchID = $_POST['branchid'];

		$IBMfrom = listofibms($database, $search, $byBranchID);
        if($IBMfrom->num_rows > 0){
            while($res = $IBMfrom->fetch_object()){
                $result[] = array("ID" => $res->ID, "Name" => $res->Name);
            }
        }else{
            $result[] = array("ID" => 0, "Name" => "No Result found.");
        }
        die(json_encode($result));
	}

    function listofibms($database, $search, $byBranchID){

        $query = $database->execute("SELECT
                                        c.Code,
                                        c.ID,
                                        c.Name
                                    FROM customer c
                                    INNER JOIN sfm_level sl
                                        ON sl.codeID = c.CustomerTypeID
                                        AND sl.has_downline = 1
                                    WHERE SPLIT_STR(c.HOGeneralID, '-', 2) = $byBranchID
                                    AND (c.Code LIKE '".$search."%' OR c.Name LIKE '".$search."%')
                                    ORDER BY c.Name
                                    LIMIT 10");
        return $query;
        
    }
?>