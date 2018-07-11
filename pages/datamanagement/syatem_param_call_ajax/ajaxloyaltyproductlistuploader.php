
<?php

	include "../../../initialize.php";
	
	include IN_PATH.DS."scproductlistuploader.php";
	
	global $database;
	
	require_once '/var/www/html/branch/Classes/PHPExcel.php';
								
delete_table(); //delete the temp tables

if(!($_FILES['file']['size']==0))
{
		$page      = (isset($_POST['page']))?$_POST['page']:1;
	
		$pagerows=30;  		//number of rows that would be displayed per page that is divided by pagination
		$start = ($page > 1) ? ($page - 1) * $pagerows : 0; //start of the data to be shown
		$limit = 'limit '.$start.','.$pagerows;			 //this will set the limit for the querry
		
		if($_FILE['file']['error'] == 0)  //checks if theres an error and continues if there is none
		{
			
			$filetmp = $_FILES['file']['tmp_name']; //this will cut the extension from the filename
			$filename = $_FILES['file']['name'];
			$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$withoutExt_a = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			$withoutExt = $withoutExt.uniqid();
			$csv = '/var/www/html/TPI-DATA/data/'.$withoutExt.'.csv';
			
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			$reasontye = $_POST['ReasonType'];
			$userid = $_SESSION['user_id'];
			
			$reasontyeid = $database->execute("SELECT ID FROM loyalty_program WHERE `code` = '$reasontye'")->fetch_object()->ID;
			
			$valid = 'yes';
			
			#file extension validation
			if($ext != 'xls' && $ext != 'xlsx')
			{
			   $valid = 'no';	
			   $errorlist = 'Invalid File';
			}
									  
			if($valid != 'no')
			{
				convertXLStoCSV($filetmp,$csv);
				
				$fileData = trim(file_get_contents($csv));
			    $rows = explode("\n", $fileData);
		 	    $first_row = true;	
				
				$counter = 1;
				
				$database->execute(" 
									 LOAD DATA LOCAL INFILE '$csv'
									 INTO TABLE tmployalty_program_productlist
									 FIELDS TERMINATED BY ','
									 OPTIONALLY ENCLOSED BY '\"'
									 LINES TERMINATED BY '\n'
								  ");	
								  
				$database->execute("delete from tmployalty_program_productlist where productcode = 'PRODUCT CODE' ");
				$database->execute("delete from tmployalty_program_productlist where productcode = '' ");
				
				$database->execute("UPDATE tmployalty_program_productlist SET start_date = DATE_ADD('1899-12-30' , INTERVAL start_date DAY) ");
				$database->execute("UPDATE tmployalty_program_productlist SET end_date = DATE_ADD('1899-12-30' , INTERVAL end_date DAY) ");
				
				#filecontent validation
				
				#product validation
				$productq = $database->execute("SELECT * 
												FROM tmployalty_program_productlist tmp
												LEFT JOIN product p ON p.code = tmp.ProductCode
												WHERE p.code IS NULL
											  "); 
				if($productq->num_rows)
				{ #validate if product exist in masterfile
			         $errorlist = 'Invalid Product Code\n';   	
			         while($prd = $productq->fetch_object()) 
					 {
						  $errorlist = $errorlist.'Product Code :'.$prd->ProductCode.'\n';	
					 }
				}
				
				#duplicate product code
				$productq1 = $database->execute("SELECT *
												 FROM
												 (
													 SELECT COUNT(productcode) ctr, ProductCode
													 FROM tmployalty_program_productlist tmp
													 GROUP BY productcode
												 ) atbl
												 WHERE ctr > 1
											     "); 
				if($productq1->num_rows)
				{ #validate if product exist in masterfile
			         $errorlist = $errorlist.'Duplicate Product Code\n';   	
			         while($prd2 = $productq1->fetch_object()) 
					 {
						  $errorlist = $errorlist.'Product Code :'.$prd2->ProductCode.'\n';	
					 }
				}
				
				#2.Date Validation
				$val3q = $database->execute(" SELECT cs.ProductCode, ifnull(Start_date,'') Start_Date, ifnull(End_Date,'') End_Date
												FROM tmployalty_program_productlist cs
												WHERE DATE(start_date) IS NULL
												OR cs.Start_date IS NULL OR cs.End_Date IS NULL
												OR DATE(End_date) IS NULL
											");
				if($val3q->num_rows)
				{	
                    $errorlist = $errorlist.'Invalid Date Value\n';   			
					while($v3 = $val3q->fetch_object())
					{	
						$errorlist = $errorlist.'Product Code :'.$v3->ProductCode.'    Start Date  :'.$v3->Start_Date.'    End Date  :'.$v3->End_Date.'\n';	
					}
				} 
			}
				 	 
			if($errorlist != '')
			{			 
				echo '<script language="javascript">';
				echo 'alert("'.$errorlist.'")';
				echo '</script>';	
                echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " >  
					    <tr class="trheader" >
							<td width="50%">Total Uploaded Products </td>
							<td width="50%">0</td>
						</tr>
					 </table>';
				delete_table();		
	        }	
			else
			{
				#delete all previous record
				$database->execute("delete from loyalty_program_productlist where loyalty_programid = $reasontyeid");
				
                #create table record 
				$database->execute("  insert into loyalty_program_productlist(Loyalty_Programid,productid,start_Date,end_date,created_by,created_date) 
				                      SELECT (SELECT ID FROM loyalty_program WHERE `code` = '$reasontye' ) loyaltyid,
													p.id , tmp.Start_Date, tmp.End_Date,'$userid',NOW()
									  FROM tmployalty_program_productlist tmp
									  INNER JOIN product p ON p.code = tmp.ProductCode"); 
									  
				#create history 
				$database->execute("  insert into loyalty_program_productlisthist(Loyalty_Programid,productid,start_Date,end_date,created_by,created_date,filename) 
				                      SELECT (SELECT ID FROM loyalty_program WHERE `code` = '$reasontye' ) loyaltyid,
													p.id , tmp.Start_Date, tmp.End_Date,'$userid',NOW(),'$withoutExt'
									  FROM tmployalty_program_productlist tmp
									  INNER JOIN product p ON p.code = tmp.ProductCode"); 
									  
				#total Row Uploaded	
                $uploadedrow = $database->execute("select * from loyalty_program_productlist where loyalty_programid = $reasontyeid");
				$uploadedrowctr = $uploadedrow->num_rows;
				
				echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " >  
					    <tr class="trheader" >
							<td width="50%">Total Uploaded Products </td>
							<td width="50%">'.$uploadedrowctr.'</td>
						</tr>
					 </table>';
					 
                delete_table();					  
			}				
    }
}		
else
{
				
echo '<table width="100%" cellpadding="8px" cellspacing="0" style="border: solid 1px #FF00CC;border-top:none ;  " >  
					    <tr class="trheader" >
							<td width="50%">Total Uploaded Products </td>
							<td width="50%">0</td>
						</tr>
					 </table>';

}

function convertXLStoCSV($infile,$outfile)
{
    $fileType = PHPExcel_IOFactory::identify($infile);
    $objReader = PHPExcel_IOFactory::createReader($fileType);
 
    $objReader->setReadDataOnly(true);   
    $objPHPExcel = $objReader->load($infile);    
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save($outfile);
}
 


