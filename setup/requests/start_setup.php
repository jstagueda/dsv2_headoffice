<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 11, 2013
 * @description: Prcocessing of file includes that have codes for uploading data.
 */
    include('../../initialize.php');
    
    $file_setup = $_POST['file'];
    
    //loop through all file includes processes...
    if($file_setup){
        include_once("$file_setup"); //let's include now file that have upload process..
        echo tpi_JSONencode(array('status' => 'success','setup_file' => str_replace('.php','',$file_setup)));
    }
?>
