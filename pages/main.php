<?php require_once(IN_PATH.DS.'scMain.php'); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
       
		<tr>
          <td valign="middle" class = "topnav">&nbsp;
            <span class="txtgreen">Welcome, <span class="txtgreenbold"><?php echo $loginname; ?></span></span>
          </td>
        </tr>
		
      </table>    
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
          <td class = "tabmain">&nbsp;<span class="txtwhitebold">CORE MODULES</span></td>
        </tr>
      </table>
      <div id="masterdiv">
        <table width="100%"  border="0" cellspacing="1" cellpadding="0">
          <?php 
                /* @author: jdymosco
                 * @date: April 12, 2013
                 * New way of displaying / listing main navigations... 
                 */
                foreach($new_rs_navmod as $NId => $new_navs):
          ?>
                <tr>
                    <td>
                        <div onclick="SwitchMenu('sub<?php echo $NId;?>');">
                            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="3" class="nav1">&nbsp;</td>
                                    <td class="nav"><span class="txtnavgreenbold">&nbsp;<?php echo $groupIDLabels[$NId]; ?></span></td>
                                </tr>
                            </table>
                            <span class="submenu" id="sub<?php echo $NId;?>">
                                <?php 
                                    foreach($new_navs as $nav):
                                ?>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                                        <tr>
                                        <td height="24" class="borderdashed_b txtpad_L">
                                            <a href="index.php?pageid=<?php echo $nav->PageID; ?>" class="txtnavgreenlink"><?php echo $nav->Module;?></a>
                                        </td>
                                        </tr>
                                    </table>
                                <?php 
                                    endforeach;
                                ?>
                            </span>
                        </div>
                    </td>
                </tr>
          <?php
                endforeach;
          ?>
        </table>
      </div>
      <br>
    </td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display:block; background:#EC008C;">              
      <table width="100%"  cellspacing="0" cellpadding="0">
        <tr bgcolor="#EC008C">
          <td width="350" rowspan="2" class="wel_d" valign="top" style="padding: 50px; background:#EC008C;">            
            <div class="branchheader">
              <h1><?php echo $branch->WelcomeNoteLine1; ?></h1>
            </div>
            
            <div style="clear:both;"></div>
            
            <div class="branchparam">
              <h1 align="center"><?php echo $branch->WelcomeNoteLine2; ?></h1>
              <h1 align="center"><?php echo $branch->WelcomeNoteLine3; ?></h1>            
            </div>
            <br />
          </td>        
        </tr>
      </table>
    </td>
  </tr>
</table>