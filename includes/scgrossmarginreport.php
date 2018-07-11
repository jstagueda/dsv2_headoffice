<?php

function tpiGetTotalParentKit2($type,$FromDate,$ToDate,$productid,$AddressNo,$txndate,$blank)
{
	global $database;
	$qty = 0;
	/*$query = $database->execute("SELECT ifnull(SUM(sod.qty),0) qty FROM interface_so_details sod
							     WHERE reserved_char01='P'
							     AND DATE(sod.order_Date) BETWEEN '$FromDate' AND '$ToDate'
								 AND sod.item_no IN (
												       SELECT p.code FROM productkit pk
													   INNER JOIN product p ON p.id = pk.kitid
													   WHERE componentid =$productid
													    AND '$txndate' BETWEEN pk.StartDate AND pk.EndDate 
													    GROUP BY pk.kitid
											        )
								  AND sod.addr_no=$AddressNo
							  ");
	if($query->num_rows > 0)
	{
        while($ru = $query->fetch_object())
		{
           $qty = $ru->qty;
        }
    }*/
	return $qty;
}

function productRange($search){
    global $database;
	$query = $database->execute("SELECT
									DISTINCT(product.ID) id,
									product.Code code,
									product.Name name
								  FROM product 
								  WHERE((product.Name LIKE '%$search%') OR (product.Code LIKE '%$search%'))
								  ORDER BY product.name
								  LIMIT 10
								");
    return $query;
}

function brandrange($search){
    global $database;
	$query = $database->execute("SELECT `id`,`code`,`name` FROM productbrand  
									WHERE `level`=1
									#and `code` like '%$search%' 
									AND ((`code` LIKE '%$search%') OR (`name` LIKE '%$search%' ))
									ORDER BY `code`
									LIMIT 10
								  
								");
    return $query;
}


function subbrandrange($search){
    global $database;
	$query = $database->execute("SELECT `id`,`code`,`name` FROM productbrand  
									WHERE `level`=2
									AND ((`code` LIKE '%$search%') OR (`name` LIKE '%$search%' ))
									ORDER BY `code`
									LIMIT 10
								  
								");
    return $query;
}


function formrange($search){
    global $database;
	$query = $database->execute("SELECT `id`,`code`,`name` FROM productform
									WHERE `level`=1
									AND ((`code` LIKE '%$search%') OR (`name` LIKE '%$search%' ))
									ORDER BY `code`
									LIMIT 10
								  
								");
    return $query;
}


function subformrange($search){
    global $database;
	$query = $database->execute("SELECT `id`,`code`,`name` FROM productform
									WHERE `level`=2
									AND ((`code` LIKE '%$search%') OR (`name` LIKE '%$search%' ))
									ORDER BY `code`
									LIMIT 10
								  
								");
    return $query;
}



function sizerange($search){
    global $database;
	$query = $database->execute("SELECT `id`,`code`,`name` FROM productsize
									WHERE  ((`code` LIKE '%$search%') OR (`name` LIKE '%$search%' ))
									ORDER BY `code`
									LIMIT 10
								  
								");
    return $query;
}

function searchpl($search)
{
    global $database;
	$query = $database->execute("SELECT
									DISTINCT(product.ID) id,
									product.Code code,
									product.Name name
								  FROM product 
								  WHERE((product.Name LIKE '%$search%') OR (product.Code LIKE '%$search%'))
								  AND ProductLevelID=2
								  ORDER BY product.name
								  LIMIT 10
								  
								");
    return $query;
}


function plrange($search)
{
    global $database;
	$query = $database->execute("SELECT
									DISTINCT(product.ID) id,
									product.Code code,
									product.Name name
								  FROM product 
								  WHERE((product.Name LIKE '%$search%') OR (product.Code LIKE '%$search%'))
								  AND ProductLevelID=2
								  ORDER BY product.name
								  LIMIT 10
								  
								");
    return $query;
}


function catrange($search)
{
    global $database;
	$query = $database->execute("SELECT
									DISTINCT(product.ID) id,
									product.Code code,
									product.Name name
								  FROM product 
								  WHERE((product.Name LIKE '%$search%') OR (product.Code LIKE '%$search%'))
								  AND ProductLevelID=6
								  ORDER BY product.name
								  LIMIT 10
								  
								");
    return $query;
}

function branchrange($search)
{
    global $database;
	$query = $database->execute("
								SELECT 
									b.id `id`,
									b.code `code`,
									b.name `name`
									FROM branch b
									WHERE ((b.name LIKE '%$search%') OR (b.code LIKE '%$search%'))
									ORDER BY b.name
									LIMIT 10
								  
								");
    return $query;
}


//function grossmargin($FromDate,$ToDate,$ProductID,$OpenOnly,$page,$pagerows,$campaign) {
/* function grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,$istotal,$pl,$lastrec,$category,$productlineid,$productcategoryid, $lastbranchid){	 */


function grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,$istotal,$islastrec,$productlineid,$productcategoryid,$branchid,$pmgtype,$plgrouping){	

global $database;
  
  
/*   echo $productcategoryid;
  die(); */
  
if($islastrec==0) 
{	
  $start = ($page > 1) ? ($page - 1) * $pagerows : 0;
  $limit = 'limit '.$start.','.$pagerows; 
  $orderdesc = "";
} 
else
{	
  $start = ($page > 1) ? ($page - 1) * $pagerows : 0;
  $limit = 'limit 1'; 
  $orderdesc = "desc";
}  
  
 /* echo $limit.'<br>'; */
  
  
  $limit = ($istotal)?'':$limit;     
  $productFilter 	= ($ProductID != 0) ? 'and p.ID = '.$ProductID  : '';

  $brandid_que    = ($brandid=='')? "":"AND p.Brand='$brandid' " ;
  $subbrand_que   = ($subbrandid=='')?"":"AND p.subbrand='$subbrandid' ";
  $form_que       = ($formid=='')?"":"AND p.form='$formid' ";
  $subform_que    = ($subformid=='')?"":"AND p.subform='$subformid' ";
  $size_que       = ($sizeid=='')?"":"AND p.size='$sizeid' ";
  $productid_que  = ($itemid=='')?"":"AND p.id='$itemid' ";
  $pl_que         = ($productlineid=='')?"":"AND pl.id=$productlineid";
  #$pl_que         = ($productlineid=='')?"": ($productlineid=='')?"" : "AND pl.id=$productlineid";
  $cat_que        = ($productcategoryid=='')?"":"AND cat.id=$productcategoryid";
  $branchid_que   = ($branchid=='')?"":"AND b.id=$branchid";
  $pmg_que        = ($pmgtype=='0')?"":"AND p.pmg='$pmgtype'";  
  $plgrouping     = str_replace(",","','",$plgrouping);
  $plgrouping_que = ($plgrouping=='0')?"":"AND pl.code IN ('".$plgrouping."')";
  

if($reporttype==0)
{
  $groupby  = ($summary==1)?"GROUP BY plid,productid":"GROUP BY plid, productid, branchcode";
  $groupby2 = ($summary==1)?"GROUP BY plid,productid":"GROUP BY plid, productid, branchcode";
  $orderby  = "ORDER BY plid $orderdesc, productid $orderdesc, branchcode $orderdesc";
  $rtype    = ($summary==1)?1:2;
}
elseif($reporttype==1)
{
  $groupby  = ($summary==1)?"GROUP BY catcode,plcode,productid":"GROUP BY branchcode,plcode,productid";		
  $groupby2 = ($summary==1)?"GROUP BY catcode,plcode":"GROUP BY branchcode,plcode";		
  
  $orderby  = ($summary==1)?"ORDER BY catcode $orderdesc , plcode $orderdesc ":"ORDER BY branchcode $orderdesc,plid $orderdesc";
  $rtype    = ($summary==1)?3:4;
}
  
elseif($reporttype==2)
{
  $groupby  = ($summary==1)?"GROUP BY txndate, productid" :"GROUP BY txndate, plid, productid";		
  $groupby2 = ($summary==1)?"GROUP BY txndate":"GROUP BY txndate, plid";		
  #
  $orderby  = ($summary==1)?"ORDER BY txndate $orderdesc":"ORDER BY txndate $orderdesc,plid $orderdesc";
  $rtype    = ($summary==1)?5:6;
}

elseif($reporttype==3)
{
  $groupby = "GROUP BY plcode";		
  $groupby2 = "GROUP BY plcode";
  $orderby = "ORDER BY plcode";
 $rtype    = ($summary==1)?5:6;
}		

  $query1 = " " ;

#echo $query1.'YYYYYYYYYYYY<br>';									
  $query = $database->execute("SELECT catid, catcode, catname,txndate,DATE_FORMAT(txndate,'%m/%d/%Y') txndate_formatted,
									  plid, plcode, plname, productid, productcode, productname,branchid,branchcode,
									  branchname,AddressNo,brand,subbrand,form,subform,
									  size, QTY, NSV, totalcost totalcost,(NSV-totalcost) margin,
									  IFNULL((((NSV-totalcost)/NSV) * 100),0) percentmargin,invtype
							   FROM
							   ( 
									SELECT catid, catcode, catname, txndate,DATE_FORMAT(txndate,'%m/%d/%Y') txndate_formatted,
									       plid,plcode,plname,productid,productcode,productname,branchid,branchcode,
										   branchname,AddressNo,brand,subbrand,form,subform,
									       size,QTY,NSV,totalcost,invtype
									FROM
									(
									     SELECT txndate,catid,catcode,catname,plid,plcode,plname,productid,
										        productcode,productname,branchid,branchcode,branchname,AddressNo,
										        brand,subbrand,form,subform,size,SUM(QTY) QTY,SUM(NSV) NSV,SUM(IFNULL(totalcost,0)) totalcost,
										        invtype ,StandardPrice
										   FROM
										   (
										       SELECT txndate,catid,catcode,catname, plid,plcode,plname,
											          productid,productcode,productname,branchid,branchcode,
											          branchname,AddressNo,brand,subbrand,form,subform,
											          size,SUM(QTY) QTY,SUM(NSV) NSV,
											          SUM(IFNULL(totalcost,0)) totalcost,
													  invtype , StandardPrice
											    FROM    
												(
                                                    SELECT txndate,catid,catcode,catname, plid,plcode,plname,
														   productid,productcode,productname,branchid,branchcode,
														   branchname,AddressNo,brand,subbrand,form,subform,
														   size,QTY, NSV,
														   QTY * StandardPrice totalcost,
														   invtype , StandardPrice
													FROM    
													(												
															SELECT soh.ORDER_DATE txndate,cat.ID catid,cat.Code catcode,cat.Name catname,                                            
																   85897 plid, 
																   'M04' plcode,
																   (SELECT product.name FROM product WHERE product.code = 'M04' ) plname,
																   p.ID productid,p.Code productcode,
																   p.Name productname,b.id branchid,b.Code branchcode,b.name branchname,
																   b.AddressNo AddressNo,p.Brand brand,p.SubBrand subbrand,
																   p.Form form,p.SubForm subform,p.Size size,
																   IF(sod.reserved_char01 = '' ,sod.QTY , IFNULL(IF(sod.reserved_char01 = 'P' , sod.QTY,
																	   ( SELECT IFNULL(SUM(sod2.qty),0) FROM interface_so_details sod2
																		WHERE sod2.reserved_char01='P'
																		AND DATE(sod2.order_Date) BETWEEN  soh.ORDER_DATE AND soh.ORDER_DATE
																		AND sod2.item_no IN (
																					SELECT p.code FROM productkit pk
																					INNER JOIN product p ON p.id = pk.kitid
																					WHERE componentid = productid
																					AND soh.ORDER_DATE BETWEEN pk.StartDate AND pk.EndDate 
																					GROUP BY pk.kitid
																				   )
																		AND sod2.addr_no = sod.addr_no	 )
																   ),0)) QTY,
																   IF(sod.reserved_char01 = '' , sod.DMS_NSVAMOUNT , IF(sod.reserved_char01 = 'P' , sod.DMS_NSVAMOUNT,0)) NSV,   
																   sod.reserved_char01 invtype,sc.StandardPrice StandardPrice
															 FROM  interface_so_header soh
															 INNER JOIN interface_so_details sod ON soh.DOC_NO_ORI=sod.DOC_NO_ORI
															 INNER JOIN branch b ON b.AddressNo=soh.ADDR_NO
															 INNER JOIN product p ON p.2nd_ItemNumber=sod.ITEM_NO
															 INNER JOIN product pl ON pl.id = p.parentid 
															 LEFT JOIN product cat ON p.catcode=cat.Code 
															 LEFT JOIN standardcost sc ON p.ID=sc.ProductID
																					   
															 WHERE 1=1
															 $brandid_que
															 $subbrand_que
															 $pl_que
															 $form_que 
															 $subform_que 
															 $size_que
															 $productid_que
															 $cat_que
															 $branchid_que
															 $pmg_que
															 $plgrouping_que
															 AND soh.ORDER_DATE BETWEEN ('$FromDate') and ('$ToDate')
															 AND (pl.Code = 'M04' OR sod.reserved_char01 IN ('C','P'))
													 
													 UNION ALL
													 
													 SELECT soh.ORDER_DATE txndate,cat.ID catid,cat.Code catcode,cat.Name catname,                                            
														   pl.ID plid, pl.Code plcode,pl.Name plname,p.ID productid,p.Code productcode,
														   p.Name productname,b.id branchid,b.Code branchcode,b.name branchname,
														   b.AddressNo AddressNo,p.Brand brand,p.SubBrand subbrand,
														   p.Form form,p.SubForm subform,p.Size size,
														   IFNULL(IF(sod.reserved_char01 IN ('C','P'),((sod.QTY -   ( SELECT ifnull(SUM(sod2.qty),0) FROM interface_so_details sod2
																													WHERE sod2.reserved_char01='P'
																													AND DATE(sod2.order_Date) BETWEEN  soh.ORDER_DATE AND soh.ORDER_DATE
																													AND sod2.item_no IN (
																																SELECT p.code FROM productkit pk
																																INNER JOIN product p ON p.id = pk.kitid
																																WHERE componentid = productid
																																AND soh.ORDER_DATE BETWEEN pk.StartDate AND pk.EndDate 
																																GROUP BY pk.kitid
																															   )
																													AND sod2.addr_no = sod.addr_no	 )
															                                           )),sod.QTY),0) QTY,
														   
														   sod.DMS_NSVAMOUNT NSV,
														   sod.reserved_char01 invtype,sc.StandardPrice StandardPrice
                                                     FROM  interface_so_header soh
													 INNER JOIN interface_so_details sod ON soh.DOC_NO_ORI=sod.DOC_NO_ORI
													 INNER JOIN branch b ON b.AddressNo=soh.ADDR_NO
													 INNER JOIN product p ON p.2nd_ItemNumber=sod.ITEM_NO
													 INNER JOIN product pl ON p.ParentID=pl.ID
													 LEFT JOIN product cat ON p.catcode=cat.Code 
													 LEFT JOIN standardcost sc ON p.ID=sc.ProductID
																			   
													 WHERE 1=1
													 $brandid_que
													 $subbrand_que
													 $form_que 
													 $subform_que 
													 $size_que
													 $productid_que
													 $pl_que
													 $cat_que
													 $branchid_que
													 $pmg_que
													 $plgrouping_que
													 AND soh.ORDER_DATE BETWEEN ('$FromDate') and ('$ToDate')
													 AND pl.Code <> 'M04'
												   )addtl 
												)atbl
                                                $groupby
											)atbl1
											$groupby2
									    )atbl2
									) atbl3
									$orderby 
									$limit		
								");													
  return $query;
}

function grossmargin_subtotal($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,$istotal, $isgrandtotal,$productlineid,$productcategoryid,$branchid,$pmgtype,$plgrouping)
{	
/* function grossmargin_subtotal($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,$istotal, $isgrandtotal,$productlineid,$productcategoryid,$branchid,$txndate){	 */
  global $database;
  $productFilter 	= ($ProductID != 0) ? 'and p.ID = '.$ProductID  : '';
  $brandid_que      = ($brandid=='')? "":"AND p.Brand='$brandid' " ;
  $subbrand_que = ($subbrandid=='')?"":"AND p.subbrand='$subbrandid' ";
  $form_que = ($formid=='')?"":"AND p.form='$formid' ";
  $subform_que = ($subformid=='')?"":"AND p.subform='$subformid' ";
  $size_que = ($sizeid=='')?"":"AND p.size='$sizeid' ";
  $productid_que = ($itemid=='')?"":"AND p.id='$itemid' ";
  $pl_que = ($productlineid=='')?"":"AND pl.id=$productlineid";
  $cat_que = ($productcategoryid=='')?"":"AND cat.id=$productcategoryid";
  #$branch_que = ($branchid=='')?"":"AND b.id='$branchid'";
  $branchid_que   = ($branchid=='')?"":"AND b.id=$branchid";
  $pmg_que = ($pmgtype=='0')?"":"AND p.pmg='$pmgtype'";
  $plgrouping = str_replace(",","','",$plgrouping);
  $plgrouping_que = ($plgrouping=='0')?"":"AND pl.code IN ('".$plgrouping."')";

if($reporttype==0)
{
  $groupby  = ($summary==1)?"GROUP BY plid,productid ":" GROUP BY plid, productid, branchcode";
  $groupby2 = ($summary==1)?"GROUP BY plid ":"GROUP BY plid, productid, branchcode";
  $rtype    = ($summary==1)?1:2;
}
elseif($reporttype==1)
{
  $groupby  = ($summary==1)?"GROUP BY catcode,plid,productid":"GROUP BY branchcode,plid,productid";		
  $groupby2 = ($summary==1)?"GROUP BY catcode":"GROUP BY branchcode,plid";		
  $rtype    = ($summary==1)?3:4;
}
  
elseif($reporttype==2)
{
  $groupby  = ($summary==1)?"GROUP BY txndate, productid" :"GROUP BY txndate, plid, productid";		
  $groupby2 = ($summary==1)?"GROUP BY txndate":"GROUP BY txndate, plid";		
  $rtype    = ($summary==1)?5:6;
}

elseif($reporttype==3)
{
  $groupby = "GROUP BY plcode";		
  $groupby2 = "GROUP BY plcode";
  $rtype    = ($summary==1)?5:6;
}


if($isgrandtotal==1)
{
   $groupby2 = "";
}

/* echo '<br>'.$branch_que.'<br>'; */
  $q = " ";
									
  #echo $q.'<br>xx';
  
  $query = $database->execute("

									SELECT catid,catcode,catname,txndate,DATE_FORMAT(txndate,'%m/%d/%Y') txndate_formatted,
									       plid,plcode,plname,productid,productcode,productname,branchid,branchcode,branchname,
									       AddressNo,brand,subbrand,form,subform,size,QTY,NSV,totalcost totalcost,
									       (NSV-totalcost) margin,IFNULL((((NSV-totalcost)/NSV) * 100),0) percentmargin,invtype
									FROM
									( 
										SELECT catid,catcode,catname,txndate,DATE_FORMAT(txndate,'%m/%d/%Y') txndate_formatted,
											   plid,plcode,plname,productid,productcode,productname,branchid,branchcode,branchname,
                                               AddressNo,brand,subbrand,form,subform,size,QTY,NSV,totalcost,invtype
										FROM
										(
											SELECT txndate,catid,catcode,catname,plid,plcode,plname,productid,productcode,productname,branchid,branchcode,
												   branchname,AddressNo,brand,subbrand,form,subform,size,SUM(QTY) QTY,
												   SUM(NSV) NSV,SUM(IFNULL(totalcost,0)) totalcost,invtype ,StandardPrice
											FROM
                                            (
											    SELECT txndate,catid,catcode,catname, plid, plcode,plname, productid,productcode,productname,
													   branchid,branchcode,branchname,AddressNo,brand,subbrand,form,subform,size,
													   SUM(QTY) QTY,
													   SUM(NSV) NSV,
													   SUM(totalcost) totalcost ,
													   invtype ,StandardPrice
												FROM    
												(
                                                    SELECT txndate,catid,catcode,catname, plid,plcode,plname,
														   productid,productcode,productname,branchid,branchcode,
														   branchname,AddressNo,brand,subbrand,form,subform,
														   size,QTY, NSV,
														   QTY * StandardPrice totalcost,
														   invtype , StandardPrice
													FROM 
													(												
															SELECT soh.ORDER_DATE txndate,cat.ID catid,cat.Code catcode,cat.Name catname,                                            
																   85897 plid, 
																   'M04' plcode,
																   (SELECT product.name FROM product WHERE product.code = 'M04' ) plname,
																   p.ID productid,p.Code productcode,
																   p.Name productname,b.id branchid,b.Code branchcode,b.name branchname,
																   b.AddressNo AddressNo,p.Brand brand,p.SubBrand subbrand,p.Form form,p.SubForm subform,
																   p.Size size,
																   IF(sod.reserved_char01 = '' ,sod.QTY , IFNULL(IF(sod.reserved_char01 = 'P' , sod.QTY,
																	   ( SELECT IFNULL(SUM(sod2.qty),0) FROM interface_so_details sod2
																		WHERE sod2.reserved_char01='P'
																		AND DATE(sod2.order_Date) BETWEEN  soh.ORDER_DATE AND soh.ORDER_DATE
																		AND sod2.item_no IN (
																					SELECT p.code FROM productkit pk
																					INNER JOIN product p ON p.id = pk.kitid
																					WHERE componentid = productid
																					AND soh.ORDER_DATE BETWEEN pk.StartDate AND pk.EndDate 
																					GROUP BY pk.kitid
																				   )
																		AND sod2.addr_no = sod.addr_no	 )
																   ),0)) QTY,
																   IF(sod.reserved_char01 = '' , sod.DMS_NSVAMOUNT , IF(sod.reserved_char01 = 'P' , sod.DMS_NSVAMOUNT,0)) NSV,
																   sod.reserved_char01 invtype,sc.StandardPrice StandardPrice
															FROM interface_so_header soh
															INNER JOIN interface_so_details sod ON soh.DOC_NO_ORI=sod.DOC_NO_ORI
															INNER JOIN branch b ON b.AddressNo=soh.ADDR_NO
															INNER JOIN product p ON p.2nd_ItemNumber=sod.ITEM_NO
															INNER JOIN product pl ON pl.id = p.parentid 
															LEFT JOIN product cat ON p.catcode=cat.Code
															LEFT JOIN standardcost sc ON p.ID=sc.ProductID
															WHERE 1=1
															$brandid_que
															$subbrand_que
															$form_que
															$pl_que 
															$subform_que 
															$size_que
															$productid_que
															$cat_que
															$branchid_que
															$pmg_que
															$plgrouping_que																
															AND soh.ORDER_DATE BETWEEN ('$FromDate') and ('$ToDate')
															AND (pl.Code = 'M04' OR sod.reserved_char01 IN ('C','P'))
													
													
															UNION ALL
													 
															 SELECT soh.ORDER_DATE txndate,cat.ID catid,cat.Code catcode,cat.Name catname,                                            
																   pl.ID plid, pl.Code plcode,pl.Name plname,p.ID productid,p.Code productcode,
																   p.Name productname,b.id branchid,b.Code branchcode,b.name branchname,
																   b.AddressNo AddressNo,p.Brand brand,p.SubBrand subbrand,
																   p.Form form,p.SubForm subform,p.Size size,
																   IFNULL(IF(sod.reserved_char01 IN ('C','P'),((sod.QTY -   ( SELECT ifnull(SUM(sod2.qty),0) FROM interface_so_details sod2
																															WHERE sod2.reserved_char01='P'
																															AND DATE(sod2.order_Date) BETWEEN  soh.ORDER_DATE AND soh.ORDER_DATE
																															AND sod2.item_no IN (
																																		SELECT p.code FROM productkit pk
																																		INNER JOIN product p ON p.id = pk.kitid
																																		WHERE componentid = productid
																																		AND soh.ORDER_DATE BETWEEN pk.StartDate AND pk.EndDate 
																																		GROUP BY pk.kitid
																																	   )
																															AND sod2.addr_no = sod.addr_no	 )
																											   )),sod.QTY),0) QTY,
																   sod.DMS_NSVAMOUNT NSV,
																   sod.reserved_char01 invtype,sc.StandardPrice StandardPrice
															 FROM  interface_so_header soh
															 INNER JOIN interface_so_details sod ON soh.DOC_NO_ORI=sod.DOC_NO_ORI
															 INNER JOIN branch b ON b.AddressNo=soh.ADDR_NO
															 INNER JOIN product p ON p.2nd_ItemNumber=sod.ITEM_NO
															 INNER JOIN product pl ON p.ParentID=pl.ID
															 LEFT JOIN product cat ON p.catcode=cat.Code 
															 LEFT JOIN standardcost sc ON p.ID=sc.ProductID
																					   
															 WHERE 1=1
															 $brandid_que
															 $subbrand_que
															 $form_que 
															 $subform_que 
															 $size_que
															 $productid_que
															 $pl_que
															 $cat_que
															 $branchid_que
															 $pmg_que
															 $plgrouping_que
															 AND soh.ORDER_DATE BETWEEN ('$FromDate') and ('$ToDate')
															 and pl.Code <> 'M04'
													)adtnl	
												)atbl
                                                $groupby
											 )atbl1
											 $groupby2
										)atbl2
									) atbl3
								");		
  return $query;
  

}


function AddPagination($RPP, $num, $pageNum){
    $PrevIc=		"images/bprv.gif";
    $FirstIc=		"images/bfrst.gif";
    $NextIc=		"images/bnxt.gif";
    $LastIc=		"images/blst.gif";

    $dPrevIc=		"images/dprv.gif";
    $dFirstIc=		"images/dfrst.gif";
    $dNextIc=		"images/dnxt.gif";
    $dLastIc=		"images/dlst.gif";
	
    if($num > 0) {
        //Determine the maxpage and the offset for the query
        $maxPage = ceil($num/$RPP);
        $offset = ($pageNum - 1) * $RPP;
        //Initiate the navigation bar
        $nav  = '';
        //get low end
        $page = $pageNum - 3;
        //get upperbound
        $upper =$pageNum + 3;
        if($page <= 0){
            $page = 1;
        }
        if($upper > $maxPage){
            $upper = $maxPage;
        }

        //Make sure there are 7 numbers (3 before, 3 after and current
        if($upper-$page < 6){

            //We know that one of the page has maxed out
            //check which one it is
            //echo "$upper >=$maxPage<br>";
            if($upper >= $maxPage){
                //the upper end has maxed, put more on the front end
                //echo "to begining<br>";
                $dif = $maxPage - $page;
                //echo "$dif<br>";
                if($dif == 3){
                   $page = $page - 3;
                }elseif ($dif == 4){
                    $page = $page - 2;
                }elseif ($dif == 5){
                    $page = $page - 1;
                }
                
            }elseif ($page <= 1){
                //its the low end, add to upper end
                //echo "to upper<br>";
                $dif = $upper-1;

                if ($dif == 3){
                    $upper = $upper + 3;
                }elseif ($dif == 4){
                    $upper = $upper + 2;
                }elseif ($dif == 5){
                    $upper = $upper + 1;
                }
            }
        }

        if($page <= 0) {
            $page = 1;
        }

        if($upper > $maxPage) {
            $upper = $maxPage;
        }

        //These are the numbered links
        for($page; $page <=  $upper; $page++) {

            if($page == $pageNum){
                //If this is the current page then disable the link
                $nav .= " <font color='red'>$page</font> ";
            }else{
                //If this is a different page then link to it
                $nav .= " <a style='cursor:pointer;' onclick=\"return showPage(".$page.")\">$page</a> ";
            }
        }


        //These are the button links for first/previous enabled/disabled
        if($pageNum > 1){
            $page  = $pageNum - 1;
            $prev  = "<img border='0' src='$PrevIc'  onclick=\"return showPage(".$page.")\" style='cursor:pointer;'> ";
            $first = "<img border='0' src='$FirstIc' onclick=\"return showPage(1)\"  style='cursor:pointer;'> ";
        }else{
            $prev  = "<img border='0' src='$dPrevIc'  style='cursor:pointer;'> ";
            $first = "<img border='0' src='$dFirstIc'   style='cursor:pointer;'> ";
        }

        //These are the button links for next/last enabled/disabled
        if($pageNum < $maxPage AND $upper <= $maxPage) {
            $page = $pageNum + 1;
            $next = " <img border='0' src='$NextIc' style='cursor:pointer;' onclick=\"return showPage(".$page.")\" >";
            $last = " <img border='0' src='$LastIc' style='cursor:pointer;' onclick=\"return showPage(".$maxPage.")\" >";
        }else{
            $next = " <img border='0' src='$dNextIc' style='cursor:pointer;'>";
            $last = " <img border='0' src='$dLastIc' style='cursor:pointer;'>";
        }

        if($maxPage >= 1){
            // print the navigation link
            return  $first . $prev . $nav . $next . $last;
        }
    }
}
?>
