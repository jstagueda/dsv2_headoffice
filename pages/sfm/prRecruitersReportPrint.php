<style>
    h2{font-size:16px; font-family: arial; text-align: center;}
    .pageset table{font-size:12px; font-family: arial; border-collapse: collapse; width:100%;}
    .pageset td{padding:5px;}
    .pageset .trheader{font-weight:bold; text-align: center;}
    .pageset{margin-bottom: 20px;}
    @page{size:portrait; margin:0.5in 0;}
    @media print{
        .pageset{page-break-after: always; margin: 0;}
    }
</style>

<?php
    require_once("../../initialize.php");
    include IN_PATH.DS."scRecruitersReport.php";

    $datefrom = $_GET['datestart'];
    $dateto = $_GET['dateend'];
    $sfmlevel = $_GET['saleforcelevel'];
    $dealerfrom = $_GET['sfaccountfromHidden'];
    $dealerto = $_GET['sfaccounttoHidden'];
	$branch = $_GET['branch'];
    
    $countrecruitersreport = recruitersreport($database, $datefrom, $dateto, $sfmlevel, $dealerfrom, $dealerto, true, 0, 0, $branch);
    
    $row = 20;
    $counter = 1;
	$count = 1;
    $header = "<div class='pageset'><table border=1>";
    $footer = "</table></div>";
    $trheader = "<tr class='trheader'>
                    <td>Recruiter's Code</td>
                    <td>Recruiter's Name</td>
                    <td>Recruit's Code</td>
                    <td>Recruit's Name</td>
                    <td>Date Registered</td>
                    <td>First PO(CSP less CPI)</td>
                    <td>Kit Availed</td>
                 </tr>";
    
    echo "<h2>Recruiter's Report</h2>";
    
    $datetmp = '';
	
    if($countrecruitersreport->num_rows){
        while($res = $countrecruitersreport->fetch_object()){
            
            if($counter == 1){
                
				echo $header;
                echo $trheader;
				
            }else{
			
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
                        <td width='10%' align='center'>".$res->recruiterCode."</td>
                        <td width='25%'>".$res->recruiterName."</td>
                        <td width='10%' align='center'>".$res->recruitCode."</td>
                        <td width='25%'>".$res->recruitName."</td>
                        <td width='10%' align='center'>".$res->DateRegister."</td>
                        <td width='10%' align='right'>".number_format($res->FirstPOtpi_dealerkitpurchase, 2)."</td>
                        <td width='10%' align='center'>".$res->kitAvailed."</td>
                    </tr>";
            
            if($counter == $row){
                echo $footer;
                $counter = 0;
            }else{
                if($count == $countrecruitersreport->num_rows){
					
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
						
                    echo $footer;
                }
            }
            
			$datetmp = $res->DateRegister;
			
            $count++;
            $counter++;
        }
    }else{
        echo $header;
        echo $trheader;
        echo "<tr class=\"listtr\"><td align='center'>No result found.</td></tr>";
    }
?>
