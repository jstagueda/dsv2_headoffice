<?php
	//unlock transaction
	try
	{
		if (isset($_GET['tableid']))
		{
			$database->beginTransaction();
			if ($_GET['tableid'] == 1)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $_GET["txnid"]);			
			}
			else if ($_GET['tableid'] == 2)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'salesinvoice', 0, 0, $_GET["txnid"]);			
			}
			else if ($_GET['tableid'] == 3)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'provisionalreceipt', 0, 0, $_GET["txnid"]);			
			}
			else if ($_GET['tableid'] == 4)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'officialreceipt', 0, 0, $_GET["txnid"]);			
			}
			else if ($_GET['tableid'] == 5)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'dmcm', 0, 0, $_GET["txnid"]);			
			}
			
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();					
		}
	}
	catch (Exception $e)
	{
		$database->rollbackTransaction();	
		$errmsg = $e->getMessage()."\n";
		throw new Exception($errmsg);	
	}
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6"><?PHP
			include("nav_sales.php");
		?>      <br></td>
    <td class="divider">&nbsp;<?php unset($_SESSION['coll_mop']);?><?php unset($_SESSION['id_add_editOR']);?></td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Sales Main</span></td>
        </tr>
    </table>
    <br />
    </td>
  </tr>
</table>
