<?php

	$file = $_GET['filepath'];
	
	header("Cache-Control: public");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Type: application/image");
    
     // Read the file from disk
     readfile($file);

?>