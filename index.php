
<?php    
/* 
 *  Modified by: marygrace cabardo 
 *  10.08.2012
 *  marygrace.cabardo@gmail.com
 */
	ini_set('display_errors', '1');
        
	header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
	header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	
        require_once "initialize.php";
        require_once(CS_PATH.DS.'pages.php');

        global $database;
        $cpage = new Pages();
        
        tpi_EODUnlinkReloader(); //delete file trigger for EOD...
        //check explanation in functions.php for more info.
        if($session->is_logged_in()) tpi_setGlobalBranchParams();
        
        if(!$session->is_logged_in()) {
            $message="Please try to login first.";
            redirect_to("login.php?msg=".$message);
        }

        define("DATETODAY", date("m/d/Y"));	
        $page="index.php";

        if(isset($_GET["pup"]))
                $pup=$_GET["pup"];
        else $pup=-1;  	

        if(isset($_GET["pageid"])) 
                $pid=$_GET["pageid"];  	
        else {

        $pid=-1;
            /*unset($_SESSION["USERID"]);
            unset($_SESSION["SESSION"]);*/
            }
            /* SESSION CHECKER */
        if($pid!=-1) 
		{
                if (!$session->is_logged_in()) {
                        $message="Please try to login first.";
                        redirect_to("index.php?msg=".$message);
                }
                if(!$session->user_id){
                        $message="Please try to login first.";
                redirect_to("index.php?msg=".$message);
                }
                $userid = $session->user_id;
				/* echo $pid.'ddd'.$userid.'<br>'; */
                $rs_access = $sp->spSelectAccessPage($database, $userid, $pid);				  
                if((!$rs_access->num_rows) && $pid!=0) $pid=0;
				
				/* echo $pid.'ddd'.$userid.'gg'.$rs_access->num_rows; */
				
       }
                        
        if(!isset($_SESSION["prod_add"])) {
                    $_SESSION["prod_add"]      = array();
                    $_SESSION["prod_add_list"] = array();
        }

            if($pid != 34) {   
                    unset($_SESSION["prod_add"]);
                    unset($_SESSION["prod_add_list"]);
            }

        if($pid!=97) unset($_SESSION["CustomerID"]);	 	 
        if($pid!=67) unset($_SESSION["buyin"]);	 	 
        if($pid!=58) {   
                    unset($_SESSION["prodid2"]);
                    unset($_SESSION["prodcode2"]);
                    unset($_SESSION["prodname2"]);
                    unset($_SESSION["soh2"]);
                    unset($_SESSION["qty2"]);
                    unset($_SESSION["resid2"]);
                    unset($_SESSION["uom2"]);
        }

        if($pid != 2) {
                    unset($_SESSION["prodid"]);
                    unset($_SESSION["prodcode"]);
                    unset($_SESSION["prodname"]);
                    unset($_SESSION["soh"]);
                    unset($_SESSION["qty"]);
                    unset($_SESSION["resid"]);
                    unset($_SESSION["uom"]);
        }

        if($pid!=116.1) {     
                    unset($_SESSION["callouts"]);
                    unset($_SESSION["violators"]);
                    unset($_SESSION["heroed"]);
                    unset($_SESSION["itemworn"]);
        }

        if($pid != 135) {   
                    unset($_SESSION["ll_message_log"]);
                    unset($_SESSION["ll_uploader_error"]);
        }
		
		$enableelectronicagreement = GetSettingValue($database, 'ENAELEAGR');
		$maxtime = GetSettingValue($database, 'MAXIDLETIME');
		
		if($enableelectronicagreement=='YES'){
			$par1 = GetSettingValue($database, 'ELECLICAGMTPAR1');
			$par2 = GetSettingValue($database, 'ELECLICAGMTPAR2');
			$par3 = GetSettingValue($database, 'ELECLICAGMTPAR3');
			$parfooter = GetSettingValue($database, 'ELECLICAGMTFTR');
			
			$licenseagrremet_content='';
			
			if(isset($par1)){
				$licenseagrremet_content=$par1;
				if(isset($par2)){
						if(strlen($par2)>0){
							$licenseagrremet_content=$licenseagrremet_content.'<br><br>'.$par2;
							if(isset($par3)){
								if(strlen($par3)>0){
											$licenseagrremet_content=$licenseagrremet_content.'<br><br>'.$par3;
									}
							}	
						}
					}
			}
			if(isset($parfooter)){
				$licenseagrremet_content = $licenseagrremet_content.'<br><br><b>'.$parfooter.'<b><br><br>';
			}
			
			if($licenseagrremet_content=='')
			{
				$licenseagrremet_content='Do you agree?';
			}
			
			if(isset($_SESSION['freshlogin'])){
				$newlylogged = 1;
			}
		
		}
		else{
			unset($_SESSION["freshlogin"]);
			$newlylogged = 0;
		}
		
		$forcepw=0;
		$changep_nextlog = $database->execute("SELECT `changenextlogon`,`usertypeid` FROM user WHERE id=".$session->user_id);
		$changepw_nextlog_on = $changep_nextlog->fetch_object();		
		$changepw=$changepw_nextlog_on->changenextlogon;
		$utypeid=$changepw_nextlog_on->usertypeid;
		if ($changepw == 1){
			if($utypeid!=28)
			{
				$pid=412;
				$forcepw=1;
			}
		}		
		/* print_r($_SESSION); */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Tupperware Brands - <?php echo (isset($_SESSION['Branch']->Name) ? ucfirst(strtolower($_SESSION['Branch']->Name)) : ''); ?> Branch</title>
    <link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
		<link type="text/css" href="css/ems.css" rel="stylesheet" />
    <link type="text/css" href="css/tpi-style.css" rel="stylesheet" />
    
    
    <script language="javascript" src="js/jquery-1.9.1.min.js"></script>
    <script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css"/>
    
    <script language="javascript" src="js/global-user-interfaces.js"></script>
    <!-- script language="javascript" src="js/jquery.UserSessioner.js"></script -->
    
    
    <link rel="stylesheet" type="text/css" href="css/calpopup.css"/>
    <script language="javascript" src="js/string_validation.js"></script>
    <script type="text/javascript" src="js/calpopup.js"></script>
    <script type="text/javascript" src="js/dateparse.js"></script>
    <script type="text/javascript" src="js/initialization-and-common-functions.js"></script>
 	<script type="text/javascript">
		var nlogged = '<?php echo $newlylogged;?>';
		var licenseagreecontent = '<?php echo $licenseagrremet_content;?>';
		var dataname = '';
		if(window.location.href.indexOf("pwx") > -1) 
			{
				<?php require_once('includes/pwnotification.php'); ?>
				var daysbefore_xp= <?php  echo $daysbefore_exp; ?>;
				var uname = '<?php echo $logname;?>';
			}
		var idleTime = 0;
		var tabnotfocused_idletime = 0;
		var ajaxcounter = 0;
		
		var maxtimeoutraw = "<?php echo $maxtime;?>";
		var maxtimeout = "<?php echo $maxtime = ($maxtime=="")?30:$maxtime;?>";
		/* var maxtimeoutxx = 	maxtimeout; */
		    maxtimeout = maxtimeout * 60;
		var pageexcluded="<?php echo $pgexcluded = GetSettingValue($database, 'PGEXCINAUTOUT');?>";
		var pageid = <?php echo $pid ?>;
		var sessid ='<?php echo $_SESSION["user_session_id"] ?>';
		var pageexcluded_final = pageexcluded.split(',');
		var pgexcluded_array = pageexcluded_final.indexOf(pageid);
		var forcepwc= <?php echo $forcepw;?>;
	</script>
	<script language="javascript" src="js/jxautologout.js?rand=<?php echo $sessionUniqueID ?>"></script>   
	
    <style>
        #ui-datepicker-div{display:none;}
    </style>
    
    <?php require_once('includes/pages-javascript-inclusion.php'); ?>    				
	</head>
	<body id="indexbody">
	<?php 		
	require_once('pages/header.php');
	
        if(tpi_BlockEODModulesLockIDs($pid) && false):
           include_once('pages/sync_page_blocker.php');
        else:
            $page = $cpage->get_page($pid);
            require_once($page);
        endif;
           
        require_once('pages/footer.php');
	?>
	</body>
    <script type="text/javascript">
        <?php
            if($_GET['pageid'] != 97 || $_GET['pageid'] != 63){    
                //echo "WindowDotLoad();";
            }
        ?>
        
    </script>
</html>
