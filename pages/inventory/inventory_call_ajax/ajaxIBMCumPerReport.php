<?php 
    include "../../../initialize.php";
    function ibmrange($database, $issecond, $from, $searched, $branch){
        $where = "";
        if($issecond){
            $where = "AND ID >= $from";
        }

        $query = $database->execute("SELECT ID, Name FROM customer
                                    WHERE (Name LIKE '$searched%'
                                    OR Code LIKE '$searched%')
                                    AND CustomerTypeID = 3
									AND LOCATE('-$branch', HOGeneralID) > 0
                                    $where
                                    ORDER BY ID LIMIT 10");
        return $query;
    }    
	
	
    //getting the details of searched sales force account
    if(isset($_POST['ibmfromx'])){
        $IBMfrom = ibmrange($database, false, 0, $_POST['searched'], $_POST['branch']);
        if($IBMfrom->num_rows > 0){
            while($res = $IBMfrom->fetch_object()){
                $result[] = array("ID" => $res->ID, "Name" => $res->Name);
            }
        }else{
            $result[] = array("ID" => 0, "Name" => "No Result found.");
        }
        die(json_encode($result));
    }
    
	//getting the details of searched sales force account
    if(isset($_POST['ibmtox'])){
        $IBMto = ibmrange($database, true, $_POST['ibmfrom'], $_POST['searched'], $_POST['branch']);
        if($IBMto->num_rows > 0){
            while($res = $IBMto->fetch_object()){
                $result[] = array("ID" => $res->ID, "Name" => $res->Name);
            }
        }else{
            $result[] = array("ID" => 0, "Name" => "No Result found.");
        }
        die(json_encode($result));
    }
	
	
	
	//getting the details of searched branch
	if(isset($_POST['searchBranch'])){
		$query = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3)
									AND (Name LIKE '".$_POST['searchBranch']."%' OR 
										Code LIKE '".$_POST['searchBranch']."%')
									LIMIT 10");
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => trim($res->Code).' - '.trim($res->Name),
								"Value" => trim($res->Code).' - '.trim($res->Name),
								"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found",
								"Value" => "",
								"ID" => 0);
		}
		
		die(json_encode($result));
	}
?>