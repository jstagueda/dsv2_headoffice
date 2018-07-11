<?php
    require_once("../initialize.php");
    include IN_PATH.DS."scSIDErrorLogReport.php";
    include IN_PATH.DS."pagination.php";
    
    //list of recruiter's report details
    if(isset($_POST['page'])){
        $page        = (isset($_POST['page']))?$_POST['page']:1;
        $total       = 30;
        $datefrom    = $_POST['datestart'];
        $dateto      = $_POST['dateend'];
        $displaytype = $_POST['displaytype'];
		$sidtype     = $_POST['sidtype'];
		$branch      = $_POST['branch'];
		$counter     = 0;
        
        $errorlogreport = errorlog($database, $datefrom, $dateto, $displaytype,false, $page, $total, $branch,$sidtype);
        $counterrorlogreport = errorlog($database, $datefrom, $dateto, $displaytype, true, $page, $total, $branch,$sidtype);
            
        echo "<table class=\"tablelisttable\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"border:1px solid #FF00A6; border-top:none;\"><tr class=\"tablelisttr\">
                    <td>No.</td>
					<td>SID Date</td>
                    <td>SID Type</td>
					<td>Branch</td>
					<td>SID Filename</td>
                    <td>Remarks</td> 
                </tr>";
         
		$datetmp = '';
		
        if($errorlogreport->num_rows)
		{
            while($res = $errorlogreport->fetch_object())
			{
			    $counter++;
                echo "<tr class=\"listtr\">
                        <td align='right'>".$counter."</td>
                        <td>".$res->Filedate."</td>
                        <td align='center'>".$res->SubCode."</td>
                        <td>".$res->Branch."</td>
                        <td align='center'>".$res->Filename."</td>
                        <td align='center'>".$res->Remarks."</td>
                    </tr>";
				
            }
        }else{
            echo '<tr class="listtr">
                    <td colspan="6" align="center">No result(s) found.</td>
                </tr>';
        }
        echo "</table>";
        echo "<div style='margin-top:10px;'>".  AddPagination($total, $counterrorlogreport->num_rows, $page)."</div>";
    }
	
	
	if(isset($_POST['searchBranch'])){
		
		$query = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (Name LIKE '".$_POST['searchBranch']."%' OR Code LIKE '".$_POST['searchBranch']."%') LIMIT 10");
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
								"Value" => trim($res->Code).' - '.$res->Name,
								"ID" => $res->Code);
			}
		}else{
			$result[] = array("Label" => "No result found.",
							"Value" => "",
							"ID" => 0);
		}
		
		die(json_encode($result));
		
	}
?>