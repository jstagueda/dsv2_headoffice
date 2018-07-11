<?php 
include "../../../initialize.php";

function customer($database, $searched, $sfmlevel, $branch){
    $query = $database->execute("SELECT * FROM customer WHERE CustomerTypeID = $sfmlevel
								AND LOCATE('-$branch', HOGeneralID) > 0
                                AND (Name LIKE '$searched%' OR Code LIKE '$searched%') LIMIT 10");
    return $query;
}



//output

if(isset($_POST['sfmfrom'])){
    $customer = customer($database, $_POST['searched'], $_POST['sfmlevel'], $_POST['branch']);
    if($customer->num_rows > 0){
        while($res = $customer->fetch_object()){
            $result[] = array("Name" => $res->Code." - ".$res->Name, "ID" => $res->ID);
        }
    }else{
        $result[] = array("Name" => "No item found.", "ID" => '');
    }
    tpi_JSONencode($result);
}

if(isset($_POST['sfmto'])){
    $customer = customer($database, $_POST['searched'], $_POST['sfmlevel'], $_POST['branch']);
    if($customer->num_rows > 0){
        while($res = $customer->fetch_object()){
            $result[] = array("Name" => $res->Code." - ".$res->Name, "ID" => $res->ID);
        }
    }else{
        $result[] = array("Name" => "No item found.", "ID" => '');
    }
    tpi_JSONencode($result);
}


if(isset($_POST['branchName'])){
	
	$query = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (Name LIKE '".$_POST['branchName']."%' OR Code LIKE '".$_POST['branchName']."%') LIMIT 10");
	if($query->num_rows){
		while($res = $query->fetch_object()){
			$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
							"Value" => trim($res->Code).' - '.$res->Name,
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