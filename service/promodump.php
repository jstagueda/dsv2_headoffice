<?php 


include "../initialize.php";		

$date= $_GET['date'];
$startdate= $_GET['startdate'];
$enddate= $_GET['enddate'];
$campaign= $_GET['campaign'];
$dumppath=$database->execute("SELECT SettingValue FROM settings WHERE SettingCode='PROMODUMPPATH'")->fetch_object()->SettingValue;
$lastdumpdate=$database->execute("SELECT SettingValue FROM settings WHERE SettingCode='PROMODUMPLASTDATE'")->fetch_object()->SettingValue;


if((($startdate!='') and ($enddate!='')) and (($campaign=='')  or ($date=='')))
	{
		$date1   = date("Y-m-d", strtotime($startdate));
		$date2     = date("Y-m-d", strtotime($enddate))  ;
	}	
	
else if (($campaign!='') and ((($startdate=='') or ($enddate=='')) or ($date=='')))
	{
		$montharray = array("JAN" => 1, "FEB" => 2, "MAR" => 3, "APR" => 4, "MAY" => 5, "JUN" => 6, "JUL" => 7, "AUG" => 8, "SEP" => 9, "OCT" => 10, "NOV" => 11, "DEC" => 12);
		$CamMonth = $montharray[strtoupper(substr($campaign, 0, 3))];
		$CamYear = substr($campaign, 3, 2);

		$datecomb=date_create($CamYear."-".$CamMonth."-01");
		$date1=date_format($datecomb,"Y-m-d");
		$date2=date("Y-m-t", strtotime($date1));	
	} 	
else if (($campaign=='') AND (($startdate=='') AND ($enddate=='')) AND ($date==''))
	{
		$date =	date("Y-m-d");
		$date = date("Y-m-d", strtotime($date))     ;
	
		$date1=	date("Y-m-d", strtotime($lastdumpdate));
		$date2= $date;

		if ($date2 < $date1)
			{
				$date2=$date1;
				$date1=$date;
			}
	}
	
$headercampaign=date("My", strtotime($date1));	
$daterange=	" between ('$date1') and ('$date2')";
$datetodatabase  =  date("m/d/Y", strtotime($date2)) ;
$filename        = date("mdy", strtotime($date2))    ;
$path            = $dumppath.$filename.'.HOPRM'      ;


if (file_exists($path)) 
	{
	unlink($path);
	} 

	
				$query = "
								SELECT 
								promocode,
								promodescription,
								Startdate,
								EndDate,
								Campaign,
								IF(prodlevelid=2,CONCAT('PL-',BuyinProductcode),BuyinProductcode) BuyinProductcode

								FROM
								(

								SELECT 
								p.Code promocode,
								p.Description promodescription,
								DATE_FORMAT(p.StartDate,'%m/%d/%Y') Startdate,
								DATE_FORMAT(p.EndDate,'%m/%d/%Y') EndDate,
								DATE_FORMAT(p.StartDate, '%b%y') Campaign,
								prod.Code BuyinProductcode,
								prod.ProductLevelID prodlevelid

								FROM promobuyin pbuy
								INNER JOIN promo p ON p.id = pbuy.PromoID
								INNER JOIN product prod ON pbuy.ProductID=prod.ID

								
								WHERE 
								((DATE(p.startdate) $daterange) AND (DATE(p.enddate) $daterange))

								UNION ALL

								SELECT 
								pi.Code promocode,
								pi.Description promodescription,
								DATE_FORMAT(pi.StartDate,'%m/%d/%Y') Startdate,
								DATE_FORMAT(pi.EndDate,'%m/%d/%Y') EndDate,
								DATE_FORMAT(pi.StartDate, '%b%y') Campaign,
								prod.Code BuyinProductcode,
								prod.ProductLevelID prodlevelid

								FROM promoincentives `pi`
								INNER JOIN incentivespromobuyin pib ON pib.PromoIncentiveID = pi.ID
								INNER JOIN product prod ON prod.ID=pib.ProductID

								WHERE 
								((DATE(pi.startdate) $daterange) AND (DATE(pi.enddate) $daterange))

								UNION ALL
								
								SELECT 
								p.Code promocode,
								p.Description promodescription,
								DATE_FORMAT(p.StartDate,'%m/%d/%Y') Startdate,
								DATE_FORMAT(p.EndDate,'%m/%d/%Y') EndDate,
								DATE_FORMAT(p.StartDate, '%b%y') Campaign,
								prm.Code BuyinProductcode,
								pbuy.ProductLevelID prodlevelid

								FROM promobuyin pbuy
								INNER JOIN promo p ON p.id = pbuy.PromoID
								INNER JOIN promo prm ON prm.ID=pbuy.PromoWithinPromoID

								WHERE 
								((DATE(p.startdate) $daterange) AND (DATE(p.enddate) $daterange))
								AND pbuy.ProductLevelID=7	
								
								UNION ALL							
															
								SELECT 
									p.Code promocode,
									p.Description promodescription,
									DATE_FORMAT(p.StartDate,'%m/%d/%Y') Startdate,
									DATE_FORMAT(p.EndDate,'%m/%d/%Y') EndDate,
									DATE_FORMAT(p.StartDate, '%b%y') Campaign,
									#prm.Code BuyinProductcode,
									CONCAT('BrochurePage ',pbuy.BrochurePageFrom,'-',pbuy.BrohurePageTo) BuyinProductcode,
									pbuy.ProductLevelID prodlevelid

									FROM promobuyin pbuy
									INNER JOIN promo p ON p.id = pbuy.PromoID
									LEFT JOIN promo prm ON prm.ID=pbuy.PromoWithinPromoID

									WHERE 
									((DATE(p.startdate) $daterange) AND (DATE(p.enddate) $daterange))
									AND pbuy.ProductLevelID=6								
						
						
								)atbl
								
								ORDER BY promocode,promodescription,startdate
									
								";


	
$datenow=date("m/d/Y");							
//echo 	$fname;							
//echo 	$path.'<br>';							
//echo $daterange;								//echo $query;
if($x=$database->execute($query))
{

		if($x->num_rows)
		{
			$filename = fopen($path, 'w');
			$filegenerated = $filename;
			fwrite($filegenerated, "\"".$headercampaign."\" ");
			fwrite($filegenerated, "\"".$datenow."\"\r\n" );
			
			while($res = $x->fetch_object())
			{
				fwrite($filegenerated, " \"".$res->promocode."\"");
				fwrite($filegenerated, " \"".$res->promodescription."\"");
				fwrite($filegenerated, " \"".$res->Startdate."\"");
				fwrite($filegenerated, " \"".$res->EndDate."\"");
				fwrite($filegenerated, " \"".$res->Campaign."\"");
				fwrite($filegenerated, " \"".$res->BuyinProductcode."\"\r\n");
			}
			fclose($filegenerated);

			savelastdate('PROMODUMPLASTDATE',$datetodatabase);
		//echo 'End of Promo Dump<br>';
		}
		else
			{
				echo 'No Result Found...<br>';
			}
		
}
else
{
	echo 'Error2...<br>';
}


function savelastdate($settingcode,$value){
	global $database;
			$database->execute("UPDATE settings SET `SettingValue`='$value' WHERE SettingCode='$settingcode'");

}
?>