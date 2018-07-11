<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 26, 2013
 */
    include('../../../initialize.php');
    $post = $_POST;
    $returns = array();

    if($post['action'] == 'insert'){
        $returns = array();
        $i = $mysqli->prepare("INSERT INTO `system_access_keys` (`DecryptedAccessKey`,`EncryptedAccessKey`,`BranchID`,`ExpirationDate`) VALUES (?,?,?,?)");
        $post = tpi_cleanRequest($post);
        $insert = true;

        if($post['branch'] != '' && $post['access_key'] != '' && $post['expdate'] != ''){
            $branchID = $post['branch'];
            $DCKey = $post['access_key'];
            $ECKey = md5($post['access_key']);
            $ExpDate = date('Y-m-d H:i:s',strtotime($post['expdate']));

            //Let's check first if access key already exists for the branch selected.
            if(thisCheckIfKeyExists($post['access_key'],$post['branch'])){
                $returns['message'] = 'Sorry, access key already used in this branch.';
                $returns['status'] = 'error';
                $insert = false;
            }

            if($insert){
                 $i->bind_param('ssss',$DCKey,$ECKey,$branchID,$ExpDate);
                $i->execute();

                $returns['message'] = 'New access key successfully added.';
                $returns['status'] = 'success';
                $returns['from_action'] = $post['action'];
            }
        }else{
            $returns['message'] = 'Fields with * are required fields.';
            $returns['status'] = 'error';
        }

        tpi_JSONencode($returns);
    }

    //Process for updating edited details of levels...
    if($post['action'] == 'update'){
        $returns = array();
        $u = $mysqli->prepare("UPDATE `system_access_keys` SET `DecryptedAccessKey` = ?,`EncryptedAccessKey` = ?,`BranchID` = ?,`ExpirationDate` = ? WHERE `ID` = ?");
        $post = tpi_cleanRequest($post);

        if($post['branch'] != '' && $post['access_key'] != '' && $post['expdate'] != ''){
            $branchID = $post['branch'];
            $DCKey = $post['access_key'];
            $ECKey = md5($post['access_key']);
            $ExpDate = date('Y-m-d H:i:s',strtotime($post['expdate']));
            $ID = $post['ID'];

            $u->bind_param('sssss',$DCKey,$ECKey,$branchID,$ExpDate,$ID);
            $u->execute();

            $returns['message'] = 'Access key successfully updated.';
            $returns['status'] = 'success';
            $returns['from_action'] = $post['action'];
        }else{
            $returns['message'] = 'Access key is not updated, fields with * are required.';
            $returns['status'] = 'error';
        }

        tpi_JSONencode($returns);
    }

    //Process for fetching and editing one levels...
    if($post['action'] == 'edit'){
        $ID = $post['ID'];
        $returns = array();
        $q = "SELECT sak.`ID`,sak.`BranchID`, CONCAT(TRIM(b.Code), ' - ', b.Name) Branch,sak.`EncryptedAccessKey`,sak.`DecryptedAccessKey`,sak.`ExpirationDate`
               FROM `system_access_keys` sak
               INNER JOIN branch b ON b.ID = sak.BranchID
               WHERE sak.`ID` = ?";
        $d = $mysqli->query(str_replace('?',$ID,$q));

        if($d->num_rows > 0){
            $returns['status'] = 'success';
            $returns['data'] = $d->fetch_object();
        }else{
            $returns['status'] = 'error';
            $returns['data'] = array();
        }

        tpi_JSONencode($returns);
    }

    //Process for getting the lists of levels....
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];

        $d = $mysqli->query("SELECT sak.`ID`,b.`Code`,sak.`EncryptedAccessKey`,sak.`DecryptedAccessKey`,sak.`ExpirationDate`
                             FROM `system_access_keys` sak
                             INNER JOIN `branch` b ON b.`ID` = sak.`BranchID`
                             ORDER BY b.`Code` ASC
                             LIMIT $start, $end");
        $t = $mysqli->query("SELECT COUNT(*) AS total FROM `system_access_keys`");

        $returns = array();

        if($d->num_rows > 0){
            while($r = $d->fetch_object()){
                $returns['lists'][] = $r;
            }
            $returns['status'] = 'success';
        }else{
            $returns['lists'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'No access keys in database.';
        }

        $total = $t->fetch_object()->total;
        $returns['total'] = $total;

        tpi_JSONencode($returns);
    }

    if($post['action'] == 'delete'){
        $IdsForDelete = $post['IdsForDelete'];
        $returns = array();

        try{
            foreach($IdsForDelete as $id):
                $mysqli->query("DELETE FROM `system_access_keys` WHERE `ID` = $id");
            endforeach;

            $returns['status'] = 'success';
            $returns['message'] = 'Items for deletion was successfully deleted.';


        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = 'Failure in deleting selected items.';
        }

        tpi_JSONencode($returns);
    }

    /*
     * Function that will check if access key was already created for an specific branch.
     */
    function thisCheckIfKeyExists($access_key,$branch){
        global $mysqli;

        if(!$access_key && !$branch) return false;

        try{
            $q = $mysqli->query("SELECT COUNT(*) AS `Check` FROM `system_access_keys`
                                 WHERE `DecryptedAccessKey` = '$access_key' AND `BranchID` = $branch");
            if($q->fetch_object()->Check > 0){
                return true;
            }else{
                return false;
            }
        }catch(Exception $e){
            return false;
        }

    }
?>
