<?php
	global $database;
	$username = "";
	
	if (isset($_POST['btnUnfreeze']))
	{		
		try
		{
			$database->beginTransaction();
			
			//get username
			$rs_user = $sp->spSelectUserById($database, $session->user_id);
			if ($rs_user->num_rows)
			{
				while ($row = $rs_user->fetch_object())
				{
					$username = $row->LoginName;			
				}		
				$rs_user->close();
			}
			
			//check password
			$found_user = User::authenticate($username, $_POST['txtPassword']);
			if ($found_user) 
			{
				//check status of inventory
				$rs_freeze = $sp->spCheckInventoryStatus($database);
				if ($rs_freeze->num_rows)
				{
					$cnt_inv = $rs_freeze->num_rows;
					while ($row = $rs_freeze->fetch_object())
					{
						$statusid_inv = $row->StatusID;			
						$InventoryCountID = $row->InventoryCountID;			
					}		
					$rs_freeze->close();
				}
				else
				{
					$cnt_inv = 0;
					$statusid_inv = 20;
				}
				
				//update freeze table
				if ($statusid_inv == 20)
				{
					$new_stat = 21;
				}
				else
				{
					$new_stat = 20;
				}
				
				if ($statusid_inv == 20)
				{
					$msg = "Freeze Inventory first.";
					redirect_to("index.php?pageid=126&msg=$msg");			
				}
				else
				{
					$rs_inventory = $sp->spUpdateInventoryStatus($database, $cnt_inv, $new_stat, $session->emp_id,$InventoryCountID);
					if (!$rs_inventory)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}  
					$database->commitTransaction();
					$msg= "Successful unfreezing of Inventory.";
					redirect_to("index.php?pageid=126&msg=$msg");
				}
			}
			else
			{
				$msg= "Invalid password.";
				redirect_to("index.php?pageid=126&msg=$msg");				
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$msg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=126&msg=$msg");
		}
	}
?>

<script type="text/javascript">
function validateUnfreeze()
{
	var password = document.frmUnfreeze.txtPassword;
	
	if (password.value == "")
	{
		alert ("Please input password.");
		password.focus();
		return false;		
	}
	else
	{
		if (confirm('Are you sure you want to unfreeze Inventory?') == false)
			return false;
	  	else
	  		return true;		
	}
}
</script>
<form name="frmUnfreeze" method="post" action="index.php?pageid=126">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    	<tr>
		      		<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
		    	</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<?php
	if (isset($_GET['msg']))
	{
		$msg = $_GET['msg'];
		echo "
				<br>
				<table width='50%'  border='0' align='center' cellpadding='0' cellspacing='1'>
				<tr>
					<td class='txtblueboldlink'>$msg</td>
				</tr>
				</table>
			";
	}
?>
<br>
<table width="50%"  border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
	<td class="txtgreenbold13">Unfreeze Inventory</td>
    <td>&nbsp;</td>
</tr>
</table>
<br><br>
<table width="50%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
<tr>
	<td height="20" align="left" class="padl5">
		<span class="txt10"><strong>Input Password:</strong></span>
		&nbsp;&nbsp;
		<input name="txtPassword" type="password" class="txtfield" id="txtPassword" size="20" value="">
		&nbsp;&nbsp;
		<input name="btnUnfreeze" type="submit" class="btn" value="Unfreeze Inventory" onClick="return validateUnfreeze();">
	</td>
</tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
</table>
</form>
<br>
<br>
<br>
<br>
<br>
<br>