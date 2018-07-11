<?php
	/*
		@Author: Gino C. Leabres
		@date: 10/30/2013
		@Description: These functions used to zip and unzip files..
	*/


function create_dirs($path) // Create Directories
{
	if (!is_dir($path)){
		$directory_path = "";
		$directories = explode("/",$path);
		array_pop($directories);
	
		foreach($directories as $directory) {
			$directory_path .= $directory."/";
			if (!is_dir($directory_path)){
				mkdir($directory_path);
				chmod($directory_path, 0777);
			}
		}
	}
}

function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true) //Unzip files..
{
	if ($zip = zip_open($src_file)){
		if ($zip) {
			$splitter = ($create_zip_name_dir === true) ? "." : "/";
			if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
			
			// Create the directories to the destination dir if they don't already exist
			create_dirs($dest_dir);
		
			// For every file in the zip-packet
			while ($zip_entry = zip_read($zip)){
				// Now we're going to create the directories in the destination directories
				// If the file is not in the root dir
				$pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
				if ($pos_last_slash !== false){
					// Create the directory where the zip-entry should be saved (with a "/" at the end)
					create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
				}
		
				// Open the entry
				if (zip_entry_open($zip,$zip_entry,"r")) {
					// The name of the file to save on the disk
					$file_name = $dest_dir.zip_entry_name($zip_entry);
					// Check if the files should be overwritten or not
					if ($overwrite === true || $overwrite === false && !is_file($file_name)){
						// Get the content of the zip entry
						$fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
						file_put_contents($file_name, $fstream );
						// Set the rights
						chmod($file_name, 0777);
						//echo "save: ".$file_name."<br />";
					}
					// Close the entry
					zip_entry_close($zip_entry);
				}       
			}
			// Close the zip-file
			zip_close($zip);
		}
	} else{
		return false;
	}

	return true;
}
	
function create_zip($files = array(),$destination = '',$overwrite = false) //Zip Files..
{
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite):
		return false; 
	endif; 
	
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)){
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		//check to make sure the file exists
		return file_exists($destination);
	}else{
		return false;
	}
}
?>