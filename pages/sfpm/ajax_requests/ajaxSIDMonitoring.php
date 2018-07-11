<?php
include "../../../initialize.php";
include IN_PATH.DS."scSIDMonitoring.php";


if(isset($_POST['searchbranch'])){
	
	$branchquery = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (`Name` LIKE '".$_POST['searchbranch']."%' OR `Code` LIKE '".$_POST['searchbranch']."%')");
	if($branchquery->num_rows){
		while($res = $branchquery->fetch_object()){
			$result[] = array("Label" => trim($res->Code)." - ".$res->Name,
							"Value" => trim($res->Code)." - ".$res->Name,
							"ID" => $res->ID);
		}
	}else{
		$result[] = array("Label" => "No result found.",
						"Value" => "",
						"ID" => 0);
	}
	
	die(json_encode($result));
}

if(isset($_POST['searched'])){
    $ibmrange = ibmrange($database, $_POST['searched'], $_POST['branch']);
    if($ibmrange->num_rows){
        while($res = $ibmrange->fetch_object()){
            $result[] = array("ID" => $res->ID, "Name" => TRIM($res->Code)." - ".$res->Name, "Value" => TRIM($res->Code)." - ".$res->Name);
        }
    }else{
        $result[] = array("ID" => 0, "Name" => "No result found.", "Value" => "");
    }
    
    die(tpi_JSONencode($result));
}

if(isset($_POST['page']))
{
    $status      = (isset($_POST['status']))?$_POST['status']:0;
    $page        = (isset($_POST['page']))?$_POST['page']:1;
	$branch      = $_POST['branch'];
	$branchn      = $_POST['branchName'];
	$cmp         = $_POST['Campaign'];
    $total       = 10;
	
	#echo $branchn.$branch;
	$getbranchlist = getbranchlist($database, $status, false, $page, $total, $branchn,$branch,$cmp);
    
	
	$totalamount = 0;
	
    echo '<table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="1" cellspacing="0" cellpadding="0">';
    echo '<tr class="tablelisttr">
	             <td>CTR</td>    
                 <td>BRANCH</td>
                 <td>1</td>
                 <td>2</td>
                 <td>3</td>
                 <td>4</td>
                 <td>5</td>
                 <td>6</td>
				 <td>7</td>
                 <td>8</td>
				 <td>9</td>
                 <td>10</td>
				 <td>11</td>
                 <td>12</td>
				 <td>13</td>
                 <td>14</td>
				 <td>15</td>
                 <td>16</td>
				 <td>17</td>
                 <td>18</td>
				 <td>19</td>
                 <td>20</td>
				 <td>21</td>
                 <td>22</td>
				 <td>23</td>
                 <td>24</td>
				 <td>25</td>
                 <td>26</td>
			     <td>27</td>
                 <td>28</td>
				 <td>29</td>
                 <td>30</td>
				 <td>31</td>             
            </tr>';	
        $ctr = 0;
        if($getbranchlist->num_rows)
		{
            while($res = $getbranchlist->fetch_object() )
			{
				$ctr = $ctr + 1;
				#echo '<tr class="listtr">';
				echo '<td align="right">'.$ctr.'.</td>';
				echo '<td align="center" >'.$res->bcode.'</td>';
				echo '<td align="center" '.$res->a1.'></td>';
				echo '<td align="center" '.$res->a2.'></td>';
				echo '<td align="center" '.$res->a3.'></td>';
				echo '<td align="center" '.$res->a4.'></td>';
				echo '<td align="center" '.$res->a5.'></td>';
				echo '<td align="center" '.$res->a6.'></td>';
				echo '<td align="center" '.$res->a7.'></td>';
				echo '<td align="center" '.$res->a8.'></td>';
				echo '<td align="center" '.$res->a9.'></td>';
				echo '<td align="center" '.$res->a10.'></td>';
				echo '<td align="center" '.$res->a11.'></td>';
				echo '<td align="center" '.$res->a12.'></td>';
				echo '<td align="center" '.$res->a13.'></td>';
				echo '<td align="center" '.$res->a14.'></td>';
				echo '<td align="center" '.$res->a15.'></td>';
				echo '<td align="center" '.$res->a16.'></td>';
				echo '<td align="center" '.$res->a17.'></td>';
				echo '<td align="center" '.$res->a18.'></td>';
				echo '<td align="center" '.$res->a19.'></td>';
				echo '<td align="center" '.$res->a20.'></td>';
				echo '<td align="center" '.$res->a21.'></td>';
				echo '<td align="center" '.$res->a22.'></td>';
				echo '<td align="center" '.$res->a23.'></td>';
				echo '<td align="center" '.$res->a24.'></td>';
				echo '<td align="center" '.$res->a25.'></td>';
				echo '<td align="center" '.$res->a26.'></td>';
				echo '<td align="center" '.$res->a27.'></td>';
				echo '<td align="center" '.$res->a28.'></td>';
				echo '<td align="center" '.$res->a29.'></td>';
				echo '<td align="center" '.$res->a30.'></td>';
				echo '<td align="center" '.$res->a31.'></td>';
				
				echo '</tr>';
			} 
        }else
		{
            echo '<tr class="listtr">
                <td align="center" colspan="6">No result found.</td>
            </tr>';
        }
		
        echo "</table>";
        
    
}
?>
