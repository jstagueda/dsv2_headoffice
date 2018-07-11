<?php
include "../../../initialize.php";
$post=$_POST;
$get=$_GET;


if(isset($post['searched'])){

    $query = $database->execute("SELECT trim(Code) Code, Name, ID from branch
                                where ID not in (1,2,3)
                                AND ((Code LIKE '".$post['searched']."%')
                                    OR (Name LIKE '".$post['searched']."%'))
                                LIMIT 10");
    if($query->num_rows){
        while($res = $query->fetch_object()){
            $result[] = array("Label" => $res->Code." - ".$res->Name, "Value" => $res->Code." - ".$res->Name, "ID" => $res->Code);
        }
    }else{
        $result[] = array("Label" => "No result", "Value" => "", "ID" => 0);
    }
    die(json_encode($result));
}


if(isset($post['request'])){
	// GET LOGS STARTS HERE...
	if($post['request']=='Get Logs'):
		echo '<table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
					<tr class="tablelisttr">
						<td>Branch Code</td>
						<td>File Name</td>
						<td>Filesize</td>
					</tr>';
					$RunningDate = date("Y-m-d",strtotime($post['RunningDate']));
					$BranchCode=$post['branchHidden'];
					$TotalFileSize=0;
					$query = doGetSyncFileLogs($database,$RunningDate,$BranchCode);
					if($query->num_rows > 0){
						while($row = $query->fetch_object()){
							$BranchCode 	 = $row->BranchCode;
							$FileName 		 = $row->FileName;
							$ActualSync  	 = $row->ActualSync;
							$Filesize	 	 = $row->Filesize;
							$TotalFileSize 	 += $row->Filesize;
							
							echo '<tr class="listtr">
									<td>'.$BranchCode.'</td>
									<td>'.$FileName.'</td>
									<td align="right">'.number_format($Filesize,2).'</td>
								</tr>';
						}
							echo '<tr class="listtr">
									<td> &nbsp; </td>
									<td>Total:</td>
									<td align="right">'.number_format($TotalFileSize,2).'</td>
								</tr>';
						
					}else{
						echo '<tr class="listtr">
									<td colspan="4" align="center">No Result(s) Display.</td>
							</tr>';
					}
		echo '</table>';
	endif;
	//GET LOGS ENDS HERE...
}
function doGetSyncFileLogs($database,$RunningDate,$BranchCode){
	$q = $database->execute("SELECT * FROM syncfilelogsendofday WHERE DATE(EnrolmentDate)= DATE('".$RunningDate."') 
							 AND BranchCode='".$BranchCode."' and Filesize > 0");//Logs Query..
	return $q;
}

?>