<?php
require_once "../../../initialize.php";
include "../../../includes/scAgingReport.php";


$age= array();
if(isset($_POST['ageRange'])){
    foreach($_POST['ageRange'] as $val){
        $age[] = $val;
    }
}else{
    $age[0] = 0;
}

$overdueaging = overdueaging($database, $agingasof, $ibmfrom, $ibmto, $page, 10, $agerange, $age, false, $branch);
$countoverdueaging = overdueaging($database, $agingasof, $ibmfrom, $ibmto, $page, 10, $agerange, $age, true, $branch);

?>


<?php if(isset($_POST['action'])){?>
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="bordergreen">
    <tr class="tableheader">
        <td align="center">Customer</td>
        <td align="center">Total</td>
        <td align="center">Not Yet Due</td>
        <td align="center">(1-30) Days</td>
        <td align="center">(31-60) Days</td>
        <td align="center">(61-90) Days</td>
        <td align="center">(91-120) Days</td>
        <td align="center">(121-150) Days</td>
        <td align="center">(151-180) Days</td>
        <td align="center">(181-over) Days</td>
    </tr>
    <?php 
        $maintotal = 0;
        if($countoverdueaging > 0){
            
            for($x = 0; $x < count($overdueaging['ID']); $x++){
                                
                $maintotal += $overdueaging['xtotal'][$x];
                $age0 += $overdueaging['Amount0'][$x];
                $age1 += $overdueaging['Amount1_30'][$x];
                $age2 += $overdueaging['Amount31_60'][$x];
                $age3 += $overdueaging['Amount61_90'][$x];
                $age4 += $overdueaging['Amount91_120'][$x];
                $age5 += $overdueaging['Amount121_150'][$x];
                $age6 += $overdueaging['Amount151_180'][$x];
                $age7 += $overdueaging['Amount181'][$x];
    ?>
    <tr align="center">
        <td align="left" width="19%">
            <a href="javascript:void(0);" onclick="openSOA('<?=date("Y-m-d", strtotime($agingasof))?>', '<?=$overdueaging['ID'][$x]?>');" class="txtnavgreenlink"><?=$overdueaging['Customer'][$x]?></a>
        </td>
        <td align="right"><?=number_format($overdueaging['xtotal'][$x], 2);?></td>
        <td align="right"><?=number_format($overdueaging['Amount0'][$x], 2)?></td>
        <td align="right"><?=number_format($overdueaging['Amount1_30'][$x], 2)?></td>
        <td align="right"><?=number_format($overdueaging['Amount31_60'][$x], 2)?></td>
        <td align="right"><?=number_format($overdueaging['Amount61_90'][$x], 2)?></td>
        <td align="right"><?=number_format($overdueaging['Amount91_120'][$x], 2)?></td>
        <td align="right"><?=number_format($overdueaging['Amount121_150'][$x], 2)?></td>
        <td align="right"><?=number_format($overdueaging['Amount151_180'][$x], 2)?></td>
        <td align="right"><?=number_format($overdueaging['Amount181'][$x], 2)?></td>
    </tr>
    <?php }?>
    <tr align="center" style="background:#FFDEF0;">
        <td align="right"><strong>TOTAL :</strong></td>
        <td align="right"><?=number_format($maintotal, 2)?></td>
        <td align="right"><?=number_format($age0, 2)?></td>
        <td align="right"><?=number_format($age1, 2)?></td>
        <td align="right"><?=number_format($age2, 2)?></td>
        <td align="right"><?=number_format($age3, 2)?></td>
        <td align="right"><?=number_format($age4, 2)?></td>
        <td align="right"><?=number_format($age5, 2)?></td>
        <td align="right"><?=number_format($age6, 2)?></td>
        <td align="right"><?=number_format($age7, 2)?></td>
    </tr>
    <?php }else{?>
    <tr align="center">
        <td colspan="10" align="center" style="color:red;">No record(s) to display.</td>
    </tr>
    <?php }?>
</table>

<div class="pagination" style="margin-top:20px;">
    <?php echo AddPagination(10, $countoverdueaging, $_POST['page']);?>
</div>
<?php 
}else{
    
    if(isset($_POST['IBMto'])){
		$result = array();
        if($rsIBMFrom->num_rows > 0){
            while($row = $rsIBMFrom->fetch_object()){
                $result[] = array('IBM' => $row->IBMCode, 'Code' => $row->Code, 'ID' => $row->ID);
            }
        }else{
            $result[] = array('IBM' => 'No IBM found.', 'Code' => '', 'ID' => '');
        }
    
		die(tpi_JSONencode($result));
	}
	
	if(isset($_POST['searchParam'])){
		$result = array();
        if($rsIBMFrom->num_rows > 0){
            while($row = $rsIBMFrom->fetch_object()){
                $result[] = array('IBM' => $row->IBMCode, 'Code' => $row->Code, 'ID' => $row->ID);
            }
        }else{
            $result[] = array('IBM' => 'No IBM found.', 'Code' => '', 'ID' => '');
        }
    
		die(tpi_JSONencode($result));
	}
    
	if(isset($_POST['searchbranch'])){
		$result = array();
		$branchquery = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3)
										AND `Code` LIKE '".$_POST['searchbranch']."%' OR `Name` LIKE '".$_POST['searchbranch']."%'
										ORDER BY `Name` LIMIT 10");
										
		if($branchquery->num_rows){
			while($res = $branchquery->fetch_object()){
				$result[] = array("Label" => TRIM($res->Code).' - '.$res->Name,
								"Value" => TRIM($res->Code).' - '.$res->Name,
								"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => 'No result found',
								"Value" => '',
								"ID" => 0);
		}
		
		die(json_encode($result));
	}
	
}
?>
