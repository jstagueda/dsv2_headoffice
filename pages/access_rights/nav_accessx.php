<?PHP 

include IN_PATH.DS."scMain.php";
?>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="middle" class="topnav">&nbsp;<span class="txtgreen">Welcome, <span class="txtgreenbold">Administrator</span></span></td>
      </tr>
    </table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td class="tabmain">&nbsp;<span class="txtwhitebold">CORE MODULES</span></td>
 </tr>
</table>
   <div id="masterdiv">
  <table width="100%"  border="0" cellspacing="1" cellpadding="0">
    <tr>
      <td>
     <div style="cursor:pointer" onClick="SwitchMenu('sub1')">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="3" class="nav1">&nbsp;</td>
          <td class="nav"><span class="txtnavgreenbold">&nbsp;User</span></td>
        </tr>
      </table>
      <span class="submenu" id="sub1">
      <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=15" class="txtnavgreenlink">Create User</a></td>
        </tr>
      </table></span>
      </div>
      <div style="cursor:pointer" onClick="SwitchMenu('sub2')">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3" class="nav1">&nbsp;</td>
            <td class="nav"><span class="txtnavgreenbold">&nbsp;User Type</span></td>
          </tr>
        </table>
        <span class="submenu" id="sub2">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=11" class="txtnavgreenlink">Create User Type</a> </td>
        </tr>
      </table>
        </span>
        </div>
        <div style="cursor:pointer" onClick="SwitchMenu('sub3')">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="3" class="nav1">&nbsp;</td>
            <td class="nav"><span class="txtnavgreenbold">&nbsp;Access Rights</span></td>
          </tr>
        </table>
        <span class="submenu" id="sub3">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=16" class="txtnavgreenlink">Create Access Rights</a> </td>
        </tr>
      </table>
        </span> </div>
        <div style="cursor:pointer" onClick="SwitchMenu('sub4')">
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="3" class="nav1">&nbsp;</td>
              <td class="nav"><span class="txtnavgreenbold">&nbsp;Module Control</span></td>
            </tr>
          </table>
          <span class="submenu" id="sub4">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
        <tr>
          
            <td height="24" class=" borderdashed_b txtpad_L"><a href="index.php?pageid=13" class="txtnavgreenlink">Create Module Control</a> </td>
        </tr>
      </table>
        </span>
        </div>
        
        
        
        
        </td>
    </tr>
  </table>
  </div>