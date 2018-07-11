<?php

 	global $database;
 	$bid = 0;
 	$bcode = "";

        if(isset($_POST['searched'])){
            include "../initialize.php";

            $query = $database->execute("SELECT `Name`, TRIM(`Code`) `Code`, ID FROM branch
                                        WHERE ID NOT IN(1,2,3)
                                        AND ((`Name` LIKE '".$_POST['searched']."%')
                                            OR (`Code` LIKE '".$_POST['searched']."%'))
                                        ORDER BY `Name`");

            if($query->num_rows){
                while($res = $query->fetch_object()){
                    $result[] = array("Label" => $res->Code." - ".$res->Name, "Value" => $res->Name, "Code" => $res->Code, "ID" => $res->ID);
                }
            }else{
                $result[] = array("Label" => "No result found.", "Value" => "", "Code" => "", "ID" => 0);
            }

            die(json_encode($result));

        }

 	if (isset($_POST["btnGenerate"]))
 	{
 		$bid = $_POST["lstBranch"];
 		$bcode = $_POST["txtBatchCode"];
 	}

	$rs_reasons = $sp->spSelectReason($database, 3,'');
	$rs_branch = $sp->spSelectBranch($database,-1,'');
	$rs_dealers = $sp->spSelectDealerForWriteOffApprove($database, $bid, $bcode);

	if(isset($_GET['errmsg']))
	{
		$errmsg = $_GET['errmsg'];
	}
	else
	{
		$errmsg = "";
	}

	if(isset($_GET['msg']))
	{
		$msg = $_GET['msg'];
	}
	else
	{
		$msg = "";
	}

	if (isset($_POST["btnApprove"]))
	{
		if(isset($_POST['chkInclude']))
		{
			try
			{
				$database->beginTransaction();
				$index = 0;
				foreach($_POST['chkInclude'] as $key => $value)
				{
					$index ++;
					//update request write off status
					$affected_rows = $sp->spApproveWriteOffStatus($database, $_POST['hdnWriteOffID'.$index], $session->emp_id, 24);
					if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}

					$database->commitTransaction();
					$message = "Successfully approved Write Off(s)";
					redirect_to("index.php?pageid=93&msg=$message");
				}
			}
			catch (Exception $e)
			{
				$database->rollbackTransaction();
				$message = $e->getMessage();
				redirect_to("index.php?pageid=93&errmsg=$message");
			}
		}
	}

	if (isset($_POST["btnDisapprove"]))
	{
		if(isset($_POST['chkInclude']))
		{
			try
			{
				$database->beginTransaction();
				$index = 0;
				foreach($_POST['chkInclude'] as $key => $value)
				{
					$index ++;
					//update request write off status
					$affected_rows = $sp->spApproveWriteOffStatus($database, $_POST['hdnWriteOffID'.$index], $session->emp_id, 28);
					if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}

					//update pda-rar code
					$affected_rows = $sp->spInsertPDARARCodeDisApprovedWriteOff($database, $value, $session->emp_id);
					if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}

					$database->commitTransaction();
					$message = "Successfully disapproved Write Off(s)";
					redirect_to("index.php?pageid=93&msg=$message");
				}
			}
			catch (Exception $e)
			{
				$database->rollbackTransaction();
				$message = $e->getMessage();
				redirect_to("index.php?pageid=93&errmsg=$message");
			}
		}
	}
?>