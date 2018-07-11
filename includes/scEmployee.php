<?php
    global $database;

    if(isset($_POST['searched'])){
        include "../initialize.php";

        $query = $database->execute("SELECT * FROM branch
                                        WHERE Name LIKE '".$_POST['searched']."%'
                                        OR Code LIKE '".$_POST['searched']."%'
                                        ORDER BY Name
                                        LIMIT 10");
        if($query->num_rows){
            while($res = $query->fetch_object()){
                $result[] = array("Label" => TRIM($res->Code)."-".$res->Name,
                                  "Value" => $res->Name,
                                  "ID" => $res->ID);
            }
        }else{
            $result[] = array("Label" => "No result found.",
                            "Value" => "",
                            "ID" => 0);
        }

        die(json_encode($result));
    }

	$errmsg = "";
	$empid = 0;
	$dID = 0;
	$wid = 0;
	$search = "";
	$code = "";
	$lname = "";
	$fname = "";
	$mname = "";
 	$bday = "";
	$dhired = "";
	$issales = 0;
	$search = "";
	$param = 0;
	$posid = 0;
	$brid = 0;

	if(isset($_POST['btnSearch']))
	{
		$param = -1;
		$search = addslashes($_POST['txtfldsearch']);
	}
	elseif(isset($_GET['svalue']))
	{
		$param = 0;
		$search = addslashes($_GET['svalue']);
	}

	$rs_cboEmployeeType = $sp->spSelectEmployeeType($database);
 	$rs_cboDepartment = $sp->spSelectDepartment($database, $dID, $search);
 	$rs_cboWarehouse = $sp->spSelectWarehouseCBO($database,$wid, $search);
 	$rs_cboPosition = $sp->spSelectPosition($database, $posid, $search);
 	$rs_cboBranch = $sp->spSelectBranch($database, $posid, $search);
	$rs_employeeall = $sp->spSelectEmployee($database,$param,$search);

 	if (isset($_GET['empid']))
 	{
		$empid = $_GET['empid'];
	 	$rs_employee = $sp->spSelectEmployee($database,$empid,$search);

	 	if ($rs_employee->num_rows)
	 	{
			while ($row = $rs_employee->fetch_object())
			{


				 $code = $row->Code;
				 $lname = $row->LastName;
				 $fname = $row->FirstName;
				 $mname = $row->MiddleName;
				 $bday = $row->BirthDate;
				 $dhired = $row->DateHired;
				 $etid = $row->EmployeeTypeID;
				 $deptid = $row->DepartmentID;
				 $postid = $row->PositionID;
				 $brid = $row->BranchID;
			}
			$rs_employee->close();
		 }
	 }
?>