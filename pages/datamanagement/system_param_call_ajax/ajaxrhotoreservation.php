
<?php


	include "../../../initialize.php";
	global $database;


	
if(!($_FILES['file']['size']==0))
{
		
		$mvtype  = (isset($_POST['movementtype']))?$_POST['movementtype']:'';
	

		
		if($_FILE['file']['error'] == 0)  //checks if theres an error and continues if there is none
			{
			
			if($mvtype==1)
			{
					
						
					$filetmp = $_FILES['file']['tmp_name']; //this will cut the extension from the filename
					$filename = $_FILES['file']['name'];
					$filename_withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
					$source_file = basename($filename);
					$imageFileExt = strtoupper(trim(pathinfo($source_file,PATHINFO_EXTENSION)));
					
					$filecontent = fopen($filetmp, 'r');  //counts number of rows in the file
					$linecount = 0;
					while(!feof($filecontent))
							{
							$line = fgets($filecontent);
							
							if($linecount==0)
								{
								
								$total_lines_txt=explode(" ",$line);
								$total_lines=$total_lines_txt[3];
								$linecount++;
								}
							else{
							
								$linecount++;
								}
							}
					$linecount=$linecount-2;
					fclose($filecontent); //close the file here and open again



					$rsBranchcodeCheck = $database->execute(" select id from branch where code='$imageFileExt' ") ;
						    if (!$rsBranchcodeCheck->num_rows){
								$return['message'] = "Error loading RHO. Invalid File.";
								$return['response'] = "fail.";
								tpi_JSONencode($return);
								return;
						    }					
					
					$filecontent = fopen($filetmp, 'r'); //opens the file again
					$item_picklistno = array();			//declare the arrays that will hold the data.
					$item_lineno=array();
					$item_code = array();
					$item_quantity=array();
					$item_description=array();
					$itemcode_counter=0;
						
					while(($f = fgets($filecontent)) !== false)	
							{			
							$fields=explode(" ",$f);
							$strDescription="";
																
							$wordCount=count($fields);
							//echo 'wordcount:' . $wordCount. '<br>';
							//build up the description string
							for ($sCounter=4; ($sCounter<$wordCount);$sCounter++)
								{
								
								$strDescription .= $fields[$sCounter] . ' '; 
								//$strDescription= substr($strDescription, 0, -2); 
								}
							
							//save the data of the text file into a arrays	
							$item_picklistno[] = trim($fields[0],"\"#*");
							$item_lineno[] = trim($fields[1],"\"#*");
							$item_code[]=trim($fields[2],"\"#*");
							$item_quantity[]=trim($fields[3],"\"#*");
							$item_description[]=$strDescription=  substr((trim($strDescription,"\"#*\n\r")),0,-2);
							//$item_description[]=$strDescription;
							$itemcode_counter++;
							}
	
						if ($filename_withoutExt!==$item_picklistno[0]) //validates the no of lines
						{
									$return['message'] = "Error loading RHO. Invalid filename.";
									$return['response'] = "fail.";
									
									tpi_JSONencode($return);
									return;
						}
						
		$def_dir="/var/www/html/convertedreservation";				
		if(!file_exists($def_dir)){   //creates folder if doesn't exist
			mkdir($def_dir,0777,true);
		
		}
		
		exec("chmod -R 777 ".$def_dir); //sets the permission to write to folder							

		
		//print_r($item_code);
		$branch_resno="\"".$item_lineno[0]."-".$item_picklistno[0]."\" "; //header detail and column no.1 
		$reservation_filename=$item_lineno[0]."-".$item_picklistno[0].'.'.$item_lineno[0]; //filename maker
		
		 $filename = fopen($def_dir.'/'.$reservation_filename, 'w');
		 
		 fwrite($filename, "\"".$item_lineno[0]."\" ");  // header
		 fwrite($filename, $item_quantity[0]." ");
		 fwrite($filename, $branch_resno);
		 fwrite($filename, ($itemcode_counter-1)."\r\n");
		 // fwrite($filename, "\"".$item_description[0]."\"\r\n");
		 
		 
		 for($x=1;$x<$itemcode_counter; $x++) //create details
		 {
		 	fwrite($filename, $branch_resno);
			fwrite($filename, $x." ");
			fwrite($filename, "\"".$item_code[$x]."\" ");
			fwrite($filename, "\"".trim($item_description[$x],"\n\r")." ");
			fwrite($filename, $item_quantity[$x]."\r\n");
		 
		 }
		 fclose($filename);
		 

		$return['message'] = "Successfully converted RHO.";
		$return['response'] = "success.";
		
		tpi_JSONencode($return);
		return;

								
		}
		elseif($mvtype==2)
		{
							
					$filetmp = $_FILES['file']['tmp_name']; //this will cut the extension from the filename
					$filename = $_FILES['file']['name'];
					$filename_withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
					$source_file = basename($filename);
					//$ext = pathinfo($path_Info);
					$imageFileExt = strtoupper(trim(pathinfo($source_file,PATHINFO_EXTENSION)));
					
					$filecontent = fopen($filetmp, 'r');  //counts number of rows in the file
					$linecount = 0;
					while(!feof($filecontent))
							{
							$line = fgets($filecontent);
							
							if($linecount==0)
								{
								
								$total_lines_txt=explode(" ",$line);
								$total_lines=$total_lines_txt[3];
								$linecount++;
								}
							else{
							
								$linecount++;
								}
							}
					$linecount=$linecount-2;
					fclose($filecontent); //close the file here and open again
					
					$rsBranchcodeCheck = $database->execute(" select id from branch where code='$imageFileExt' ") ;
					if (!$rsBranchcodeCheck->num_rows){
						$return['message'] = "Error loading STA. Invalid File.";
						$return['response'] = "fail.";
						tpi_JSONencode($return);
						return;
					}	
					
					$filecontent = fopen($filetmp, 'r'); //opens the file again
					$item_shipmentadviseno = array();			//declare the arrays that will hold the data.
					$item_lineno=array();
					$item_code = array();
					$item_description=array();
					$item_quantity=array();
					$reservationdate=array();
					$itemcode_counter=0;
						
					while(($f = fgets($filecontent)) !== false)	
							{			
							$fields=explode(" ",$f);
							$strDescription="";
																
							$wordCount=count($fields);
							//echo 'wordcount:' . $wordCount. '<br>';
							//build up the description string
							for ($sCounter=3; ($sCounter<$wordCount-1);$sCounter++)
								{
								
								$strDescription .= $fields[$sCounter] . ' '; 
								//$strDescription= substr($strDescription, 0, -2); 
								}
							
							//save the data of the text file into a arrays	
							$item_shipmentadviseno[] = trim($fields[0],"\"#*");
							$item_lineno[] = trim($fields[1],"\"#*");
							$item_code[]=trim($fields[2],"\"#*");
							$reservationdate[]=trim($fields[3],"\"#*");
							$item_description[]=$strDescription=  substr((trim($strDescription,"\"#*\n\r")),0,-2);
							$item_quantity[]=trim($fields[$wordCount-1],"\"#*\n\r");
							$itemcode_counter++;
							}
							
						if ($filename_withoutExt!==$item_lineno[0]) //validates the no of lines
						{
							$return['message'] = "Error loading STA. Invalid filename.";
							$return['response'] = "fail.";
							tpi_JSONencode($return);
							return;
						}
						
						
		$def_dir="/var/www/html/convertedreservation";				
		if(!file_exists($def_dir)){   //creates folder if doesn't exist
			mkdir($def_dir,0777,true);
		
		}
		
		exec("chmod -R 777 ".$def_dir); //sets the permission to write to folder							
		
		//print_r($item_code);
		$branch_resno="\"".$imageFileExt."-".$item_lineno[0]."\" "; //header detail and column no.1 
		
		$reservation_filename=$imageFileExt."-".$item_lineno[0].".".$imageFileExt; //filename maker
	
    	$filename = fopen($def_dir.'/'.$reservation_filename, 'w');
	
    	fwrite($filename, "\"".$imageFileExt."\" ");  //create header
		fwrite($filename, $reservationdate[0]." ");
		fwrite($filename, $branch_resno);
		fwrite($filename, ($itemcode_counter-1)."\r\n");
		 // fwrite($filename, "\"".$item_description[0]."\"\r\n");
		 
		 
		 for($x=1;$x<$itemcode_counter; $x++) //create details
		 {
		 	fwrite($filename, $branch_resno);
			fwrite($filename, $x." ");
			fwrite($filename, "\"".$item_code[$x]."\" ");
			fwrite($filename, "\"".trim($item_description[$x],"\n\r")."\" ");
			fwrite($filename, $item_quantity[$x]."\r\n");
		 
		 }
		 fclose($filename);
		 
		 $return['message'] = "Successfully converted STA.";
		 $return['response'] = "success.";
		
		 tpi_JSONencode($return);
		 return;
					
		}
		
	}		

jump_to_exit:
								
}

?>
