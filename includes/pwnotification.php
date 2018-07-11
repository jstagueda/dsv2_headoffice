<?php

		$pwdval_period  = GetSettingValue($database, 'MAXPWDVAL');
		$daysbef_pwdexp = GetSettingValue($database, 'DAYSBEFPWDEXPNOT');
		$logname='';
		
		$get_user = $database->execute("SELECT loginname from user where `ID`=".$session->user_id);	
		if ($get_user->num_rows) {
			while ($r = $get_user->fetch_object()) {
				$logname = $r->loginname;
			}
		}
		
		$pwordexp= $database->execute("SELECT `ID` id, `userid` userid, `historydate` historydate, NOW() datenow, DATEDIFF(NOW(),`historydate`) datedifference FROM userpasswordhistory WHERE userid=".$session->user_id." ORDER BY id DESC LIMIT 1");
		
		if($pwordexp->num_rows){
			while($pwxp = $pwordexp->fetch_object())
				{
				$datediff =	$pwxp->datedifference;
				}
		}
		
		$daysbefore_exp=$pwdval_period-$datediff;
	
?>

