<?php
require_once "initialize.php";
echo"Your session has expired. Please login again.";
session_destroy();
die;

?>