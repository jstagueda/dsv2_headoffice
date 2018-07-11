
<?php
$branchquery = $database->execute("SELECT
                                    b.Name,
                                    b.Code,
                                    b.StreetAdd,
                                    b.TelNo1,
                                    b.TelNo2,
                                    b.TelNo3,
                                    b.PermitNo,
                                    b.TIN,
                                    b.ZipCode,
                                    b.FaxNo,
                                    bp.WelcomeNoteLine1, 
                                    bp.WelcomeNoteLine2,
                                    bp.WelcomeNoteLine3
                                  FROM branch b
                                  INNER JOIN branchparameter bp
                                  ON b.ID = bp.BranchID;");
                                  
$branch = $branchquery->fetch_object();

$loginname = "";
$userid =  $session->user_id;

$rs_user = $sp->spSelectUserById($database, $userid);	
if ($rs_user->num_rows) {
	while ($row = $rs_user->fetch_object()) {
		$loginname = $row->UserName;
	}
}

$telholder = array($branch->TelNo1, $branch->TelNo2, $branch->TelNo3);
$tel = array();

foreach($telholder as $val) if($val != "") $tel[] = $val;  
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>    
    <?php 
    $result = $database->execute("SELECT Name BranchName FROM branch WHERE ID = (SELECT BranchID FROM branchparameter LIMIT 1);"); 
    if($result->num_rows):
    ?>
    <td align="left" style="background-color:#eb0089;">
      <h1 style="font-style:normal; font-family: 'Times New Roman', 'Georgia,Serif'">
        <font color="white" face="times new roman">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $result->fetch_object()->BranchName; ?>&nbsp;</font>
      </h1>
    </td>
    <?php else: ?>
    <td align="left" style="background-color:#eb0089;">
      <h1 style="font-style:normal; font-family: 'Times New Roman', 'Georgia,Serif'">
        <font color="white" face="times new roman">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO BRANCH SELECTED</font>
      </h1>
    </td>
    <?php endif; ?>
    <td width="300" style="background-color:#eb0089;" align="right">
        <img src="images/tupperwarebrands_logo.png" width="300" height="80">
    </td>
  </tr>	
</table>

<div id="tooltip_about" class="tooltip">
  <div id="branch_information_header">Branch Information</div>
  
  <div class="labelparam">Branch Code</div>
  <div class="fieldparam"><?=(!empty($branch->Code))?$branch->Code:"N/A";?></div>
            
  <div class="labelparam">Street Address</div>
  <div class="fieldparam"><?=(!empty($branch->StreetAdd))?$branch->StreetAdd:"N/A";?></div>
        
  <div class="labelparam">Zip Code</div>
  <div class="fieldparam"><?=(!empty($branch->ZipCode))?$branch->ZipCode:"N/A";?></div>
  
  <div class="labelparam">Telephone Numbers</div>
  <div class="fieldparam"><?=implode(", ", $tel);?></div>
  
  <div class="labelparam">Fax Number</div>
  <div class="fieldparam"><?=(!empty($branch->FaxNo))?$branch->FaxNo:"N/A";?></div>
  
  <div class="labelparam">TIN Number</div>
  <div class="fieldparam"><?=(!empty($branch->TIN))?$branch->TIN:"N/A";?></div>
        
  <div class="labelparam">Permit Number</div>
  <div class="fieldparam"><?=(!empty($branch->PermitNo))?$branch->PermitNo:"N/A";?></div>                
</div>


<div id="tooltip_logout" class="tooltip">
  <div class="labelparam" style="padding-top:10px;" id="div_changepassword"><a href="#" class="txtblueboldlink" name="logout" onclick="window.location.href='index.php?pageid=412';">Change Password</a></div>
  <!--<div class="fieldparam">&nbsp;&nbsp;</div>-->
  <div class="labelparam" style="padding-top:10px;"> <a href="#" class="txtblueboldlink" name="logout" onclick="window.location.href='login.php?logout';">Log-out</a></div>
  <!--<div class="fieldparam">&nbsp;&nbsp;</div> -->              
</div>


<div id="tooltip_changepassword" class="tooltip">
  <div>CHANGE PASSWORD</div>
  <div class="labelparam" style="padding-top:10px;">Change Password</div>
  <!--<div class="fieldparam">&nbsp;&nbsp;</div>-->
  <div class="labelparam" style="padding-top:10px;"> <a href="#" class="txtblueboldlink" name="logout" onclick="window.location.href='login.php?logout';">Log-out</a></div>
  <!--<div class="fieldparam">&nbsp;&nbsp;</div> -->              
</div>


<div id="tooltip_help" class="tooltip">
  <ul>
    <li class="tpi-module">Sales Cycle</li>
    <li>          
      <ul>
        <li>&nbsp;&nbsp;- Dealer Account Balance Inquiry(<em>F7</em>)</li>
      </ul>
    </li>
  </ul>        
</div>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="height:2px;background-color: #a6a2a6"></td>
  </tr>
  <tr>
    <td align="right" style="height:23px; background-color:#f041a7;">
      <a href="index.php?pageid=0" class="txtblueboldlink">Home</a> |
      <a id="tpi_about" href="javascript: void(0);" class="txtblueboldlink">About</a> |
	   <a id="test_about2" href="javascript: void(0);" class="txtblueboldlink"><?php echo $loginname;?></a>&nbsp;&nbsp;&nbsp;
     <!-- <a id="tpi_help" href="javascript: void(0);" class="txtblueboldlink">Help</a> | 
      <a href="#" class="txtblueboldlink" name="logout" onclick="window.location.href='login.php?logout';">Log-out</a> &nbsp;&nbsp; -->
    </td>
  </tr>
  <tr>
    <td style="height:2px; background-color:#a6a2a6"></td>
  </tr>
</table>