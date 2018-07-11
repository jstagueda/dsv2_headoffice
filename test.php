<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(CS_PATH.DS.'dbconnection.php');

class Test  
{
    public function test3($id,$level,$sessionid)
	{
        global $database;
        $query = "select ProductID,MinQty,MinAmt,Type from buyin where id=" . $id;
		$result_set = $database->query($query);
		
		$row = $database->fetch_array($result_set); 	
		$productid = $row['ProductID'];
        $multiplicity = $row['MinQty'];
        $worthprice = $row['MinAmt'];
        $type = $row['Type'];
        
        if (empty($productid)) 
        {
            $hasFirstItem = false;
            // get all children
            $query = "select ID from buyin where ParentBuyinID=" . $id;
            $childResultSet = $database->query($query);
            while ($childRow = $database->fetch_array($childResultSet)) 
            {
                if (!empty($childRow['ID'])) {
                    if (!$hasFirstItem) 
                    {
                        $childQuery = self::test3($childRow['ID'],$level+1,$sessionid);
                        $hasFirstItem = true;
                    }
                    else 
                    {
                        $childQuery = $childQuery . ' AND ' . self::test3($childRow['ID'],$level+1,$sessionid);
                    }
                }
            }
            return "(" . $childQuery . ")";
        }
        else 
        {
            // type 1 (X items of A)
            if ($type==1)
            {
                return "exists (select true from productlist where ProductID = '" . $productid . "' and Qty>=" . $multiplicity . " and session='" . $sessionid . "')<br/>";
            }
            // type 1 (X worth of A)
            elseif ($type==2)
            {
                return "exists (select true from productlist where ProductID = '" . $productid . "' and Price*Qty>=" . $worthprice . " and session='" . $sessionid . "')<br/>";
            }
        }
	}
}
?>