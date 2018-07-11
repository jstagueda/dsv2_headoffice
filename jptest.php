<?php
// jp test
print 'test!';
function scan_directory_recursively($directory, $filter=FALSE, $search_text)
{
	global $exclude;
	
	// if the path has a slash at the end we remove it here
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}

	// if the path is not valid or is not a directory ...
	if(!file_exists($directory) || !is_dir($directory))
	{
		// ... we return false and exit the function
		return FALSE;

	// ... else if the path is readable
	}elseif(is_readable($directory))
	{
		// we open the directory
		$directory_list = opendir($directory);

		// and scan through the items inside
		while (FALSE !== ($file = readdir($directory_list)))
		{
			// if the filepointer is not the current directory
			// or the parent directory
			if($file != '.' && $file != '..' && !in_array($file, $exclude))
			{
				// we build the new path to scan
				$path = $directory.'/'.$file;

				// if the path is readable
				if(is_readable($path))
				{
					// we split the new path by directories
					$subdirectories = explode('/',$path);

					// if the new path is a directory
					if(is_dir($path))
					{
						// add the directory details to the file list
/*						$directory_tree[] = array(
							'path'    => $path,
							'name'    => end($subdirectories),
							'kind'    => 'directory',

							// we scan the new path by calling this function
							'content' => scan_directory_recursively($path, $filter));*/
						scan_directory_recursively($path, $filter, $search_text);
					// if the new path is a file
					}elseif(is_file($path))
					{
						// get the file extension by taking everything after the last dot
						$extension = end(explode('.',end($subdirectories)));

						// if there is no filter set or the filter is set and matches
						if($filter === FALSE || $filter == $extension)
						{
							// add the file details to the file list
/*							$directory_tree[] = array(
								'path'      => $path,
								'name'      => end($subdirectories),
								'extension' => $extension,
								'size'      => filesize($path),
								'kind'      => 'file');*/
							search_this_file($path, $search_text);
						}
					}
				}
			}
		}
		// close the directory
		closedir($directory_list); 

		// return file list
		return $directory_tree;

	// if the path is not readable ...
	}else{
		// ... we return false
		return FALSE;	
	}
}


function search_this_file($file, $search_text) {
      $show_file = '';
      if (file_exists($file)) {
        $show_file .= "\n" . '<table border="2" width="95%" cellspacing="2" cellpadding="1" align="center"><tr><td class="main">' . "\n";
        $show_file .= '<tr class="infoBoxContent"><td class="dataTableHeadingContent">';
        $show_file .= '<strong>' . $file . '</strong>';
        $show_file .= '</td></tr>';
        $show_file .= '<tr><td class="main">';

        // put file into an array to be scanned
        $lines = file($file);
        $found_line = 'false';
        // loop through the array, show line and line numbers
        foreach ($lines as $line_num => $line) {
          $cnt_lines++;
          if (stristr(strtoupper($line), $search_text)) {
            $found_line= 'true';
            $found = 'true';
            $cnt_found++;
            $show_file .= "<br />Line #<strong>".($line_num + 1)."</strong> : ";
            //prevent db pwd from being displayed, for sake of security
            $show_file .= (substr_count($line,"'DB_SERVER_PASSWORD'")) ? '***HIDDEN***' : htmlspecialchars($line);
            $show_file .= "<br />\n";
          } else {
            if ($cnt_lines >= 5) {
//            $show_file .= ' .';
              $cnt_lines=0;
            }
          }
        }
      }
      $show_file .= '</td></tr></table>' . "\n";
	  
	  if ($found == 'true') print $show_file;
}

?>
<form method="get" action="<?= basename($_SERVER['PHP_SELF']) ?>">
	Find text: <input type="text" name="search_text" value="">
	<input type="submit" value="Find it">
</form>
<?php
$exclude = array('_shrub', '_old', 'images');

if ($_GET['search_text'] && strlen(trim($_GET['search_text'])) > 0) {
	$search_text = stripslashes($_GET['search_text']);
	scan_directory_recursively('.', 'php', $search_text);
}

?>