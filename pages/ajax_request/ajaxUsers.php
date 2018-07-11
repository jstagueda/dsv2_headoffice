<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: June 27, 2013
 */

    include('../../initialize.php');
    $post = $_POST;
    $returns = array();

    //Process used in getting auto completer users...
    if($post['action'] == 'get_users'){
        $term = $post['input_term'];
        $lists = array();
        $q = $mysqli->query("SELECT
                                 u.`ID`,u.`UserName`,CONCAT(e.`LastName`,' ',e.`FirstName`,' ',e.`MiddleName`) AS `Name`,
                                 ut.`Name` AS `TypeName`, IFNULL(d.`Name`,'No Department Assigned') AS `Department`
                               FROM `user` u
                               INNER JOIN `employee` e ON e.`ID` = u.`EmployeeID`
                               LEFT JOIN `department` d ON d.ID = e.`DepartmentID`
                               LEFT JOIN `usertype` ut ON ut.`ID` = u.`UserTypeID`
                               WHERE
                                 (u.`ID` LIKE '%$term%' OR
                                 u.`UserName` LIKE '%$term%' OR
                                 u.`LoginName` LIKE '%$term%') AND u.`ID` NOT IN(SELECT `UserID` FROM `user_permissions`)
                               ORDER BY u.`UserName` ASC
                               LIMIT 10");
        if($q->num_rows > 0){
            while($row = $q->fetch_object()):
                $lists[] = $row;
            endwhile;

            $returns['status'] = 'success';
            $returns['lists'] = $lists;
        }else{
            $returns['status'] = 'error';
            $returns['lists'] = $lists;
        }

        tpi_JSONencode($returns);
    }

    //Process for inserting / adding new user permissions...
    if($post['action'] == 'insert'){
        $userID = $post['UserID'];
        $role_perm = $post['role_perm'];
        $q = $mysqli->prepare("INSERT INTO `user_permissions` (`UserID`,`Permissions`) VALUES (?,?)");

        //Make sure that insertion has selected at least one permission to be assigned to use...
        if($role_perm && $userID){
            $role_perm = json_encode($role_perm);

            $q->bind_param('is',$userID,$role_perm);
            $q->execute();

            $returns['status'] = 'success';
            $returns['message'] = 'New user permission added.';
        }else{
            $returns['status'] = 'error';
            $returns['message'] = 'User permission fails to be added.';
        }

        tpi_JSONencode($returns);
    }

    //Process for getting all user with permission settings...
    if($post['action'] == 'lists'){
        $start = $post['start'];
        $end = $post['end'];
        $lists = array();

        $q = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS
                                 up.`UserID`,u.`UserName`,CONCAT(e.`LastName`,' ',e.`FirstName`,' ',e.`MiddleName`) AS `Name`,
                                 ut.`Name` AS `TypeName`, up.`Permissions`
                               FROM `user_permissions` up
                               INNER JOIN `user` u ON u.`ID` = up.`UserID`
                               INNER JOIN `employee` e ON e.`ID` = u.`EmployeeID`
                               LEFT JOIN `department` d ON d.ID = e.`DepartmentID`
                               LEFT JOIN `usertype` ut ON ut.`ID` = u.`UserTypeID`
                               ORDER BY u.`UserName` ASC
                               LIMIT $start,$end");
        $found_rows = $mysqli->query("SELECT IFNULL(FOUND_ROWS(),0) AS `Total`;");

        if($q->num_rows > 0){
            while($row = $q->fetch_object()):
                $row->Permissions = implode(',',json_decode($row->Permissions));
                $lists[] = $row;
            endwhile;

            $returns['status'] = 'success';
            $returns['lists'] = $lists;
            $returns['total'] = $found_rows->fetch_object()->Total;
        }else{
            $returns['status'] = 'error';
            $returns['lists'] = $lists;
            $returns['total'] = 0;
            $returns['message'] = 'No lists';
        }

        tpi_JSONencode($returns);
    }

    //Process for getting user permission details...
    if($post['action'] == 'edit'){
        $userID = $post['userID'];
        $newData = array();
        $q = "SELECT
                u.`ID`,u.`UserName`,CONCAT(e.`LastName`,' ',e.`FirstName`,' ',e.`MiddleName`) AS `Name`,
                ut.`Name` AS `TypeName`, up.`Permissions`, IFNULL(d.`Name`,'No Department Assigned') AS `Department`
            FROM `user_permissions` up
            INNER JOIN `user` u ON u.`ID` = up.`UserID`
            INNER JOIN `employee` e ON e.`ID` = u.`EmployeeID`
            LEFT JOIN `department` d ON d.ID = e.`DepartmentID`
            LEFT JOIN `usertype` ut ON ut.`ID` = u.`UserTypeID`
            WHERE up.`UserID` = ?";

        $d = $mysqli->query(str_replace('?',$userID,$q));
        if($d->num_rows > 0){
            $user = $d->fetch_object();

            $newData['ID'] = $user->ID;
            $newData['UserName'] = $user->UserName;
            $newData['Name'] = $user->Name;
            $newData['TypeName'] = $user->TypeName;
            $newData['Permissions'] = json_decode($user->Permissions);
            $newData['Department'] = $user->Department;

            $returns['status'] = 'success';
            $returns['data'] = (object) $newData;
        }else{
            $returns['status'] = 'error';
            $returns['data'] = array();
            $returns['message'] = 'Error on fetching user details.';
        }

        tpi_JSONencode($returns);
    }

    //Process for updating user permission...
    if($post['action'] == 'update'){
        $userID = $post['UserID'];
        $role_perm = $post['role_perm'];
        $q = $mysqli->prepare("UPDATE `user_permissions` SET `Permissions` = ?, `Changed` = 1 WHERE `UserID` = ?");

        //Make sure that insertion has selected at least one permission to be assigned to use...
        if($role_perm && $userID){
            $role_perm = json_encode($role_perm);

            $q->bind_param('si',$role_perm,$userID);
            $q->execute();

            $returns['status'] = 'success';
            $returns['message'] = 'User permissions updated.';
        }else{
            $returns['status'] = 'error';
            $returns['message'] = 'User permission fails to be updated.';
        }

        tpi_JSONencode($returns);
    }

    //Process for deleting user permissions...
    if($post['action'] == 'delete'){
        $IdsForDelete = $post['IdsForDelete'];
        $returns = array();

        try{
            foreach($IdsForDelete as $id):
                $mysqli->query("DELETE FROM `user_permissions` WHERE `UserID` = $id");
            endforeach;

            $returns['status'] = 'success';
            $returns['message'] = 'Items for deletion was successfully deleted.';

        }catch(Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = 'Failure in deleting selected items.';
        }

        tpi_JSONencode($returns);
    }
?>
