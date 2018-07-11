<?php
/*   
  @modified by John Paul Pineda.
  @date March 25, 2013.
  @email paulpineda19@yahoo.com         
*/
 
require_once(IN_PATH.DS.'scViewSalesOrder.php'); 
?>

<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<script type="text/javascript" src="js/jxPagingListSO.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">
            <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td align="right" width="70%">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <br />
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <?php if(isset($_GET['msg'])): ?>	
        <tr>
          <td>
            <?php 
            $message=strtolower($_GET['msg']);
            $success=strpos($message, 'success');              
            ?>
            <div align="left" style="padding: 5px 0 0 5px;" class="txtblueboldlink"><strong><?php echo $_GET['msg']; ?></strong></div> 
          </td>
        </tr>
        <?php elseif(isset($_GET['errmsg'])): ?>
        <tr>
          <td>
            <?php
            $errormessage=strtolower($_GET['errmsg']);
            $error=strpos($errormessage, 'error');             
            ?>
            <div align="left" style="padding:5px 0 0 5px;" class="txtredbold"><?php echo $_GET['errmsg']; ?></div>
          </td>
        </tr>
        <?php	endif; ?>
        <tr>
          <td class="txtgreenbold13">List of Sales Order</td>
        </tr>
      </table>
      <form name="frmListSO" action="index.php?pageid=35" method="post">
        <br />
        <table align="center" width="95%" border="0" cellpadding="0" cellspacing="1" class="bordersolo">
          <tr>
            <td>
              <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td width="10%">&nbsp;</td>
                  <td width="89%" align="right">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20">From Date :</td>
                  <td height="20">
                    <input type="text" id="txtStartDate" name="txtStartDate" size="20" readonly="yes" value="<?php echo $fromdate; ?>" class="txtfield" />
                    <input type="button" id="anchorStartDate" name="anchorStartDate" value=" " class="buttonCalendar" />
                    <div id="divStartDate" style="background-color:white; position:absolute; visibility:hidden;"> </div>
                  </td>				 
                </tr>
                <tr>
                  <td height="20">To Date :</td>
                  <td height="20">
                    <input type="text" id="txtEndDate" name="txtEndDate" size="20" readonly="yes" value="<?php echo $todate; ?>" class="txtfield" />	        			
                    <input type="button" id="anchorEndDate" name="anchorEndDate" value=" " class="buttonCalendar" />
                    <div id="divEndDate" style="background-color:white; position:absolute; visibility:hidden;"> </div>	
                  </td>
                </tr>
                <tr>
                  <td height="20" align="left">Search :</td>            
                  <td height="20" align="left">
                    <input type="text" id="txtSearch" name="txtSearch" size="30" value="<?php echo $vSearch; ?>" class="txtfield" />
                    <input type="submit" name="btnSearch" value="Search" class="btn" />						 
                  </td>
                </tr>
                <tr>
                  <td colspan="2" height="20">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        </table><br /><br />
        
        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tabmin">&nbsp;</td>
            <td class="tabmin2">&nbsp;</td>
            <td class="tabmin3">&nbsp;</td>
          </tr>
        </table>
        
        <table id="tbl2" align="center" border="0" width="95%" cellpadding="0" cellspacing="1" class="bordergreen">
          <tr>
            <td valign="top" class="bgF9F8F7">
              <table align="center" border="0" width="100%" cellpadding="0" cellspacing="1">
                <tr>
                  <td valign="top" height="242" class="">
                    <div id="pgContent">
                      <b>Loading Content...</b><img src="images/ajax-loader.gif" border="0" />&nbsp;
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
          <tr>
            <td height="3" class="bgE6E8D9"></td>
          </tr>
        </table><br />
        
        <table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td width="50%" height="20" class="txtblueboldlink">
              <div id="pgNavigation">
                <b>Loading Navigation...</b><img src="images/ajax-loader.gif" border="0" />
              </div>            
            </td>
            <td width="48%" height="20" class="txtblueboldlink">
              <div id="pgRecord" align="right">
                <b>Loading Navigation...</b><img src="images/ajax-loader.gif" border="0" />
              </div>
            </td>
          </tr>
        </table><br /><br /><br />
        <script type="text/javascript">
        // Onload start the user off on page one.
        window.onload=showPage("1", "<?php echo $vSearch; ?>", "<?php echo $fromdate; ?>", "<?php echo $todate; ?>");    
        </script>
      </form>
    </td>
  </tr>
</table>

<script type="text/javascript">
Calendar.setup({
  inputField     :    "txtStartDate",     // id of the input field
  ifFormat       :    "%m/%d/%Y",      // format of the input field
  displayArea    :	"divStartDate",
  button         :    "anchorStartDate",  // trigger for the calendar (button ID)
  align          :    "Bl",           // alignment (defaults to "Bl")
  singleClick    :    true
});

Calendar.setup({
  inputField     :    "txtEndDate",     // id of the input field
  ifFormat       :    "%m/%d/%Y",      // format of the input field
  displayArea    :	"divEndDate",
  button         :    "anchorEndDate",  // trigger for the calendar (button ID)
  align          :    "Bl",           // alignment (defaults to "Bl")
  singleClick    :    true
});
</script>