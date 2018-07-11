<?php
   require_once "../initialize.php";
   global $database;

	$custtypeid = $_GET['custtype'];

	if ($custtypeid == 2 || $custtypeid == 3 || $custtypeid == 5)
	{
		$rs_ptype = $sp->spSelectPaymentTypeOR($database);		
	}
	else
	{
		$rs_ptype = $sp->spSelectPaymentType($database);
	}
	
	echo "<input type='hidden' name='hCustTypeID' id='hCustTypeID' value='$custtypeid'>";        
   	echo '<select name="lstType" id="lstType" style="width:160px" class="txtfield" onChange="submitForm();">';
    echo "<option value=\"0\">[SELECT HERE]</option>";
    if ($rs_ptype->num_rows)
    {
        while ($row = $rs_ptype->fetch_object())
        {
        	if(isset($_POST['lstType']))
			{
				if($_POST['lstType'] == $row->ID)
				{
					$sel = "selected";
				}
				else
				{
					$sel = "";
				}																		
			}
			else
			{
				$sel = "";
			}
        	echo "<option value='$row->ID' $sel>$row->Name</option>";
        }
        $rs_ptype->close();
    }
   	echo '</select>';
?>