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
    include IN_PATH.DS."scSIDDocnoMonitoringReport.php";

    $datefrom = $_GET['datestart'];
    $dateto = $_GET['dateend'];
    $displaytype = $_GET['sidsubtype2'];
	$sidtype     = $_GET['sidtype'];
	$branch = $_GET['branch'];
    
    $errorlogreport = sidlist($database, $datefrom, $dateto, $displaytype,true, $page, $total, $branch,$sidtype);
    
    $row = 20;
    $counter = 0;
	$count = 1;
    $header = "<div class='pageset'><table border=1>";
    $footer = "</table></div>";
    $trheader = "<tr class='trheader'>
                    <td>No.</td>
                    <td>SID Date</td>
                    <td>SID Type</td>
					<td>Branch</td>
					<td>SID Filename</td>
                    <td>Reference No</td>
					<td>Total Invoice Amount</td>
                 </tr>";
	echo "<div class='pageset'><table border=0>";
    echo "<tr >
	         <td colspan = 4 align = left > Tupperware Brands Philippines Inc.</td>
			 <td colspan = 3 align = right > Run Date : ".date('m/d/Y')." </td>
           </tr>";
	echo "<tr ><td colspan = 7 align = left > Direct Selling System 2.0</td>
           </tr>";	    
    echo "</table></div>";		   
		   
    echo "<h2>SID Summary Report</h2>"; 
	
	
	if($errorlogreport->num_rows)
	{
		echo $header;
        echo $trheader;
        while($res = $errorlogreport->fetch_object())
		{
			    $counter++;
                echo "<tr class=\"listtr\">
                        <td align='right'>".$counter."</td>
                        <td>".$res->siddate."</td>
                        <td align='center'>".$sidtype."</td>
                        <td>".$res->brcode."</td>
                        <td align='center'>".$res->Filename."</td>
                        <td align='center'>".$res->DOC_NO_ORI."</td>
						<td align='right'>".$res->totinvamount."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>";
				
        }
		echo $footer;
    }else
	{
		echo $header;
        echo $trheader;
            echo '<tr class="listtr">
                    <td colspan="6" align="center">No result(s) found.</td>
                </tr>';
    }
	
?>
