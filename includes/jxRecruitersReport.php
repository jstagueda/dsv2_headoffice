<?php
    require_once("../initialize.php");
    include IN_PATH.DS."scRecruitersReport.php";
    include IN_PATH.DS."pagination.php";
    
    if(isset($_POST['searched'])){
        $sfaccount = sfaccount($database, $_POST['sfmlevel'], $_POST['searched'], $_POST['branch']);
        if($sfaccount->num_rows){
            while($res = $sfaccount->fetch_object()){
                $result[] = array("ID" => $res->ID, "Name" => $res->Code." - ".$res->Name);
            }
        }else{
            $result[] = array("ID" => 0, "Name" => "No query found.");
        }
        tpi_JSONencode($result);
    }
    
    //list of recruiter's report details
    if(isset($_POST['page'])){
        $page = (isset($_POST['page']))?$_POST['page']:1;
        $total = 10;
        $datefrom = $_POST['datestart'];
        $dateto = $_POST['dateend'];
        $sfmlevel = $_POST['saleforcelevel'];
        $dealerfrom = $_POST['sfaccountfromHidden'];
        $dealerto = $_POST['sfaccounttoHidden'];
		$branch = $_POST['branch'];
		$counter = 1;
        
        $recruitersreport = recruitersreport($database, $datefrom, $dateto, $sfmlevel, $dealerfrom, $dealerto, false, $page, $total, $branch);
        $countrecruitersreport = recruitersreport($database, $datefrom, $dateto, $sfmlevel, $dealerfrom, $dealerto, true, $page, $total, $branch);
            
        echo "<table class=\"tablelisttable\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"border:1px solid #FF00A6; border-top:none;\"><tr class=\"tablelisttr\">
                    <td>Recruiter's Code</td>
                    <td>Recruiter's Name</td>
                    <td>Recruit's Code</td>
                    <td>Recruit's Name</td>
                    <td>Date Registered</td>
                    <td>First PO(CSP less CPI)</td>
                    <td>Kit Availed</td>
                </tr>";
        
		$datetmp = '';
		
        if($countrecruitersreport->num_rows){
            while($res = $recruitersreport->fetch_object()){
			
				if($counter > 1){
					if($datetmp != $res->DateRegister){
						$recruitersreportTotalPerDay = recruitersreportTotalPerDay($datetmp, $sfmlevel, $dealerfrom, $dealerto, $branch);
						$rcTotalPerDay = $recruitersreportTotalPerDay->fetch_object();
						
						echo "<tr class=\"listtr\">
								<td align='right' colspan='4'><b>TOTAL FOR ".$datetmp." : </b></td>
								<td align='right'><b>".number_format($rcTotalPerDay->TotalCustomer)." Recruit(s)</b></td>
								<td align='right'><b>".number_format($rcTotalPerDay->TotalPurchaseKit, 2)."</b></td>
								<td align='right'><b>".number_format($rcTotalPerDay->TotalKit)." Kit(s)</b></td>
							</tr>";
						
					}
				}
			
                echo "<tr class=\"listtr\">
                        <td align='center'>".$res->recruiterCode."</td>
                        <td>".$res->recruiterName."</td>
                        <td align='center'>".$res->recruitCode."</td>
                        <td>".$res->recruitName."</td>
                        <td align='center'>".$res->DateRegister."</td>
                        <td align='right'>".number_format($res->FirstPOtpi_dealerkitpurchase, 2)."</td>
                        <td align='center'>".$res->kitAvailed."</td>
                    </tr>";
					
				if($recruitersreport->num_rows == $counter){
					
					$recruitersreportTotalPerDay = recruitersreportTotalPerDay($res->DateRegister, $sfmlevel, $dealerfrom, $dealerto, $branch);
					$rcTotalPerDay = $recruitersreportTotalPerDay->fetch_object();
						
					echo "<tr class=\"listtr\">
							<td align='right' colspan='4'><b>TOTAL FOR ".$res->DateRegister." : </b></td>
							<td align='right'><b>".number_format($rcTotalPerDay->TotalCustomer)." Recruit(s)</b></td>
							<td align='right'><b>".number_format($rcTotalPerDay->TotalPurchaseKit, 2)."</b></td>
							<td align='right'><b>".number_format($rcTotalPerDay->TotalKit)." Kit(s)</b></td>
						</tr>";
				
					$recruitersreportTotal = recruitersreportTotal($datefrom, $dateto, $sfmlevel, $dealerfrom, $dealerto, $branch);
					$rcTotal = $recruitersreportTotal->fetch_object();
					
					echo "<tr class=\"listtr\">
							<td align='right' colspan='4'><b>TOTAL : </b></td>
							<td align='right'><b>".number_format($rcTotal->TotalCustomer)." Recruit(s)</b></td>
							<td align='right'><b>".number_format($rcTotal->TotalPurchaseKit, 2)."</b></td>
							<td align='right'><b>".number_format($rcTotal->TotalKit)." Kit(s)</b></td>
						</tr>";
				}
				
				$datetmp = $res->DateRegister;
				$counter++;
            }
        }else{
            echo '<tr class="listtr">
                    <td colspan="7" align="center">No result(s) found.</td>
                </tr>';
        }
        echo "</table>";
        echo "<div style='margin-top:10px;'>".  AddPagination($total, $countrecruitersreport->num_rows, $page)."</div>";
    }
	
	
	if(isset($_POST['searchBranch'])){
		
		$query = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (Name LIKE '".$_POST['searchBranch']."%' OR Code LIKE '".$_POST['searchBranch']."%') LIMIT 10");
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
								"Value" => trim($res->Code).' - '.$res->Name,
								"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found.",
							"Value" => "",
							"ID" => 0);
		}
		
		die(json_encode($result));
		
	}
?>