<?php
/*
 * @author: jdymosco
 * @date: Feb. 20, 2013
 * @description: All new functions created are transferred in a different file.
 */
include('tpi_functions.php');

function convert_number($number) 
{ 
    if (($number < 0) || ($number > 999999999)) 
    { 
    throw new Exception("Number is out of range");
    } 

    $Gn = floor($number / 1000000);  /* Millions (giga) */ 
    $number -= $Gn * 1000000; 
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;               /* Ones */ 

    $res = ""; 

    if ($Gn) 
    { 
        $res .= convert_number($Gn) . " Million"; 
    } 

    if ($kn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 

function strip_zeros_from_date( $marked_string="" ) {
  // first remove the marked zeros
  $no_zeros = str_replace('*0', '', $marked_string);
  // then remove any remaining marks
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}

function redirect_to($location=null) {
  
  if(!headers_sent()) {
  
    if($location!=null) {
    
      header('Location: '.$location); 
      exit;
    }
  } else {
    
    if($location==null) echo '<script type="text/javascript">window.location="http://"+document.domain+"/dss_svn_branch";</script>'; 
    else echo '<script type="text/javascript">window.location="'.$location.'";</script>';
  } 
}

function output_message($message="") {
  if (!empty($message)) { 
    return "<p class=\"message\">{$message}</p>";
  } else {
    return "";
  }
}

function __autoload($class_name) 
{  
	$class_name = strtolower($class_name);
  	//$path = DOM_PATH."{$class_name}.php";
  	$path = 'D:\apachewwwroot\tpi\class\fpdf.php';
  	
  	if(file_exists($path)) 
  	{
    	require_once($path);
  	} 
  	else 
  	{ 
		die("The file {$class_name}.php could not be found.");
	}
}

function include_layout_template($template="") {
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function log_action($action, $message="") {
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
  if($handle = fopen($logfile, 'a')) { // append
    $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
    fwrite($handle, $content);
    fclose($handle);
    if($new) { chmod($logfile, 0755); }
  } else {
    echo "Could not open log file for writing.";
  }
}

function getwidth($counter){
	$width = "";
	switch ($counter)
	{
	 case 0: $width = "4%"; break;
	 case 1: $width = "18%"; break;
	 case 2: $width = "8%"; break;
	 case 3: $width = "10%"; break;
	 case 4: $width = "9%"; break;
	 case 5: $width = "10%"; break;
	 case 6: $width = "10%"; break;
	 case 7: $width = "8%"; break;
	 case 8: $width = "19%"; break;
	 default: $width = "";
	}
 
	return $width;
}

function setExpires($expires) {  
 header(  
   'Expires: '.gmdate('D, d M Y H:i:s', time()+$expires).'PHT');
   header('Pragma: no-cache');  
}

function csvstring_to_array(&$string, $CSV_SEPARATOR = ';', $CSV_ENCLOSURE = '"', $CSV_LINEBREAK = "\n"){
    $o = array();
    $o[0] = "";
    $cnt = strlen($string);
    $esc = false;
    $num = 0;
    $i = 0;
    while ($i < $cnt) 
    {
            $s = $string[$i];
            //echo $s."<br>";

            if ($s == $CSV_SEPARATOR) 
            {
                    if ($esc) 
                    {
                            $o[$num] .= $s;
                    } 
                    else 
                    {
                            $num++;
                            $esc = false;
                    }
            } 
            elseif ($s == $CSV_ENCLOSURE) 
            {
                    if ($esc) 
                    {
                            $esc = false;
                    } 
                    else 
                    {
                            $esc = true;
                    }
            } 
            else 
            {
                    $o[$num] .= $s;
            }

            $i++;
    }
    return $o;
}
?>