<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 10, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();

    if($post['action'] == 'generate'){
        $BranchID = $post['branch'];
        $Date = date('Y-m-d',strtotime($post['date']));

        try{
            $DCR = HOReportDailyCashReceiptsQuery($BranchID,$Date);

            $returns['status'] = 'success';
            $returns['ORCashTotal'] = $DCR->ORCashTotal;
            $returns['ORChequeTotal'] = $DCR->ORChequeTotal;
            $returns['ORDepositTotal'] = $DCR->ORDepositTotal;
            $returns['ORCashCancelledTotal'] = $DCR->ORCashCancelledTotal;
            $returns['ORChequeCancelledTotal'] = $DCR->ORChequeCancelledTotal;
        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = $e->getMessage();
        }

        tpi_JSONencode($returns);
        die();
    }

    if(isset($_POST['searched'])){

        $query = $database->execute("SELECT TRIM(Code) Code, Name, ID FROM branch WHERE ID NOT IN(1,2,3)
                                        AND ((Name LIKE '".$_POST['searched']."%') OR (Code LIKE '".$_POST['searched']."%'))
                                     ORDER BY Name");
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
