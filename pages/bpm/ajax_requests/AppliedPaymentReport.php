<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 11, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();

    if($post['action'] == 'generate'){
        $BranchID = $post['branch'];
        $Date = date('Y-m-d',strtotime($post['date']));
        $CustomerID = $post['accountNo'];
        $lists = array();

        try{
            $q = HOReportAppliedPaymentReportQuery($BranchID,$CustomerID,$Date);

            if($q->num_rows > 0):
                while($row = $q->fetch_object()):
                    $lists[] = $row;
                endwhile;

                $returns['status'] = 'success';
                $returns['lists'] = $lists;
            else:
                $returns['status'] = 'error';
                $returns['lists'] = $lists;
                $returns['message'] = 'No record(s) found.';
            endif;
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = $e->getMessage();
        }

        tpi_JSONencode($returns);
    }


    if(isset($_POST['searched'])){
        $query = $database->execute("SELECT TRIM(Code) Code, Name, ID FROM branch
                                        WHERE ID NOT IN(1,2,3)
                                        AND (Name LIKE '".$_POST['searched']."%' OR Code LIKE '".$_POST['searched']."%')");
        if($query->num_rows){
            while($res = $query->fetch_object()){
                $result[] = array("Label" => $res->Code." - ".$res->Name, "Value" => $res->Code." - ".$res->Name, "ID" => $res->ID);
            }
        }else{
            $result[] = array("Label" => "No result found.", "Value" => "", "ID" => 0);
        }

        die(json_encode($result));
    }

    if(isset($_POST['searchedDealer'])){

        $query = $database->execute("SELECT c.ID, c.Name, TRIM(c.Code) Code FROM customer c
                                    INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
                                    WHERE (c.Name LIKE '".$_POST['searchedDealer']."%'
                                        OR c.Code LIKE '".$_POST['searchedDealer']."%')
                                        AND b.ID = ".$_POST['branchID']."
                                    ORDER BY c.Name
                                    LIMIT 10");
        if($query->num_rows){
            while($res = $query->fetch_object()){
                $result[] = array("Label" => $res->Code." - ".$res->Name, "Value" => $res->Code." - ".$res->Name, "ID" => $res->ID);
            }
        }else{
            $result[] = array("Label" => "No result found.", "Value" => "", "ID" => 0);
        }

        die(json_encode($result));

    }
?>
