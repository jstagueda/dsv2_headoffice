<?php
	include('../../initialize.php');
	global $database;
	
	$logtype = $_GET['logtype'];
	$Date = date("Y-m-d",strtotime($_GET['Date']));
	$q = $database->execute("SELECT * FROM systemlog WHERE DATE(xDate)='".$Date."' AND LogType='".$logtype."'");
	echo "<b>".$logtype."</b>";
	
	if($q->num_rows){
		while($r=$q->fetch_object()){
				//print_r($r);
				echo "<br>".$r->FileName;
				echo str_replace("<br><br><br>","<br>",$r->Description);
			}
		
	}else{
			echo "<br /> 0 Result(s) displayed.";
	}
?>