<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 09, 2013
 */
    include('../../initialize.php');
    $post = $_POST;
    $returns = array();

    if($post['action'] == 'get_accounts'){
        $branchID = $post['branch'];
        $SFL = $post['SFL'];
        $term = $post['input_term'];
        $lower_level = $post['lower_level'];
        $inner_join = '';

        if($SFL) $where_and = "AND c.`CustomerTypeID` = $SFL";
        if($lower_level != 'NULL'){
            if($lower_level === true || $lower_level == 'true') $cp = 1; //can purchase..
            else $cp = 0; //can't purchase...

            $inner_join = "INNER JOIN (SELECT `codeID` FROM `sfm_level` WHERE `can_purchase` = $cp) lvl ON lvl.`codeID` = c.`CustomerTypeID`";
        }

        $q = $mysqli->query("SELECT c.`ID`,TRIM(c.`Code`) AS `Code`,TRIM(c.`Name`) AS `Name`
                                FROM `customer` c
                                $inner_join
                                WHERE LOCATE(BINARY '-$branchID',CONCAT(' ',c.`HOGeneralID`,' '))
                                AND (c.`Code` LIKE '%$term%' || c.`Name` LIKE '%$term%' || c.`FirstName` LIKE '%$term%' ||
                                     c.`LastName` LIKE '%$term%' || c.`MiddleName` LIKE '%$term%')
                                $where_and
                                LIMIT 10
                           ");

        if($q->num_rows > 0){
            while($row = $q->fetch_object()):
                $lists[] = $row;
            endwhile;

            $returns['status'] = 'success';
            $returns['lists'] = $lists;
        }else{
            $returns['status'] = 'error';
            $returns['lists'] = $lists;
            $returns['message'] = 'Sorry, no records found for this branch.';
        }

        tpi_JSONencode($returns);
    }
?>
