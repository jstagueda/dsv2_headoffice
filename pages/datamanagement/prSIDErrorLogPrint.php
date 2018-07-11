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
    include IN_PATH.DS."scSIDErrorLogReport.php";

    $datefrom = $_GET['datestart'];
    $dateto = $_GET['dateend'];
    $displaytype = $_GET['displaytype'];
	$sidtype     = $_GET['sidtype'];
	$branch = $_GET['branch'];
    
    $errorlogreport = errorlog($database, $datefrom, $dateto, $displaytype,true, $page, $total, $branch,$sidtype);
    
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
                    <td>Remarks</td> 
                 </tr>";
    
    echo "<h2>SID Loading Error Log</h2>"; 
	
	if($errorlogreport->num_rows)
	{
		echo $header;
        echo $trheader;
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
