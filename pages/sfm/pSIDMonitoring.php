<style>
	
table{font-size:12px; font-family: arial; border-collapse:collapse;}
td{padding:5px;}
.tablelisttr td{font-weight:bold; text-align:center;}
@page{
	margin : 0.5in 0;
}

.mainheader td{padding:1px;}
.fieldlabel{width:15%; font-weight:bold; text-align:right;}
.separator{width:5%; font-weight:bold; text-align:center;}
</style>

<?php 
	
	include "../../initialize.php";
	include IN_PATH."scSIDMonitoring.php";
	
    $status  = (isset($_GET['status']))?$_GET['status']:0;
    $page    = (isset($_GET['page']))?$_GET['page']:1;
    $total   = 10;
	$branch  = $_GET['branch'];
	$branchn = $_GET['branchName'];
	$cmp     = $_GET['Campaign'];
	
	$userquery = $database->execute("SELECT * FROM employee WHERE ID = ".$_SESSION['emp_id']."");
	$user = $userquery->fetch_object();
	$affiliation = $_GET['summarydetail'];
	
	$totalamount = 0;
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainheader">
			<tr>
				<td class="fieldlabel">Report</td>
				<td class="separator">:</td>
				<td><b>SID MONITORING REPORT</b></td>
				<td class="fieldlabel">Run Date</td>
				<td class="separator">:</td>
				<td>'.date("F d, Y h:i").'</td>
			</tr>
			<tr>
				<td class="fieldlabel">SID Type</td>
				<td class="separator">:</td>
				<td><b>'.$status.'</b></td>
				<td class="fieldlabel">Run By</td>
				<td class="separator">:</td>
				<td>'.$user->LastName.', '.$user->FirstName.'</td>
			</tr>
			<tr>
				<td class="fieldlabel">Campaign</td>
				<td class="separator">:</td>
				<td><b>'.$cmp.'</b></td>
				<td class="fieldlabel"></td>
				<td class="separator">:</td>
				<td></td>
			</tr>
		</table><br />';
		
		$getbranchlist = getbranchlist($database, $status, false, $page, $total, $branchn,$branch,$cmp);
        
        echo '<table width="100%" border="1" cellspacing="0" cellpadding="0" align = "center">';
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
                <td align="center" colspan="8">No result found.</td>
            </tr>';
        }
	
	echo "</table>";
	
?>