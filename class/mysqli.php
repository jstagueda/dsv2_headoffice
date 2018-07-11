<?php

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

 //* check connection 
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>