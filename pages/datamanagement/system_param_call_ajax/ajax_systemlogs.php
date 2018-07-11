<?php 
	include('../../../initialize.php');
	global $database;
	
	$post = $_POST;
	$request = $post['request'];
	if($request=="save"):
		$date 		= date("Y-m-d",strtotime($post['xdate']));
		$logtype 	= $post['logtype'];
		$q = $database->execute("SELECT * FROM systemlog WHERE DATE(xDate)='".$date."' AND LogType='".$logtype."'"); 
		
		if($q->num_rows){
			while($r=$q->fetch_object()){
				$result['fetch_data'][]=array("LogDescription"=>str_replace("<br><br><br>","<br>",$r->Description),"FileName"=>$r->FileName);
			}
			$result['response']='success';
		}else{
			$result['response']='failed';
		}
		die(json_encode($result));
	endif;
	
?>