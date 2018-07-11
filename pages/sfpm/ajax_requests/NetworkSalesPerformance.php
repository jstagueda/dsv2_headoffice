<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 04, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();

    //Generation report process
    if($post['action'] == 'generate'){
        $BranchID = $post['branch'];
        $Month = date('n',strtotime($post['Period']));
        $Year = date('y',strtotime($post['Period']));
        $netFrom = $post['netFrom'];
        $netTo = $post['netTo'];
        $pmg = $post['pmg'];
        $SFL = $post['SFL'];
        $lists = array();

        $new_row = array();

        try{
            $q = HOReportNetworkSalesPerformanceQuery($BranchID,$Month,$Year,$netFrom,$netTo,$pmg,$SFL);

            if($q->num_rows > 0):
                while($row = $q->fetch_object()):
                    if($pmg == 'ALL'){
                        $new_row[$row->IBMID]['Code'] = $row->Code;
                        $new_row[$row->IBMID]['TotalNumberOfRecruits'] = $row->TotalNumberOfRecruits;
                        $new_row[$row->IBMID]['TotalNumberOfActives'] = $row->TotalNumberOfActives;
                        $new_row[$row->IBMID][$row->PMGType] = $row->Sales;
                    }else{
                        $new_row[$row->IBMID]['Code'] = $row->Code;
                        $new_row[$row->IBMID]['TotalNumberOfRecruits'] = $row->TotalNumberOfRecruits;
                        $new_row[$row->IBMID]['TotalNumberOfActives'] = $row->TotalNumberOfActives;
                        $new_row[$row->IBMID]['PMGType'] = $row->PMGType;
                        $new_row[$row->IBMID]['Sales'] = number_format($row->Sales,2,'.',',');
                    }
                endwhile;

                $lists = $new_row;

                $returns['status'] = 'success';
                $returns['lists'] = $lists;
                $returns['display_type'] = $pmg;
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

    //Process for getting networks / IBM record details by branch...
    if($post['action'] == 'get_branch_networks'){
        $branchID = $post['bID'];
        $SFL = $post['SFL'];
        $lists = array();

        if($SFL) $where_and = "AND c.`CustomerTypeID` = $SFL";

        $q = $mysqli->query("SELECT c.`ID`,TRIM(c.`Code`) AS `Code`,TRIM(c.`Name`) AS `Name`
                                FROM `customer` c
                                INNER JOIN (SELECT `codeID` FROM `sfm_level` WHERE `can_purchase` = 0) lvl ON lvl.`codeID` = c.`CustomerTypeID`
                                WHERE LOCATE(BINARY '-$branchID',CONCAT(' ',c.`HOGeneralID`,' '))
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
            $returns['message'] = 'Sorry, no network records found for this branch.';
        }

        tpi_JSONencode($returns);
    }
?>
