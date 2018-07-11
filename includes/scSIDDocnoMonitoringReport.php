<?php
    //added by joebert italia
    function sfmlevel($database){
        $result = array();
        $query = $database->execute("SELECT * FROM sfm_level order by codeID ASC");
        while($res = $query->fetch_object()){
            $result['id'][] = $res->codeID;
            $result['code'][] = $res->desc_code." - ".$res->description;
        }
        return $result;
    }  
    function sidlist($database, $datefrom, $dateto, $displaytype,$istotal, $page, $total, $branch,$sidtype)
	{
		$start = ($page > 1)?(($page - 1) * $total):0;
        $limit = (!$istotal)?"LIMIT $start, $total":"";
		
		$datefrom = date("Y-m-d", strtotime($datefrom));
        $dateto = date("Y-m-d", strtotime($dateto));
		$daterange = "AND date(so_header.ORDER_DATE) BETWEEN '$datefrom' AND '$dateto'";
		$branchquery = $branch =='0' ? '' : "and sf.branch = '$branch'";
		
		$query = $database->execute("
		                              SELECT  DATE(so_header.ORDER_DATE) siddate, so_header.doc_type , so_header.ADDR_NO , b.Code brcode,  b.Name , so_header.DOC_NO_ORI, sf.Filename, 
											FORMAT((
											  SELECT IFNULL(SUM(extended_price),0) 
											  FROM  interface_so_details ds
											  WHERE ds.DOC_NO_ORI = so_header.DOC_NO_ORI       
											),2) totinvamount
									 FROM interface_so_header so_header
									 INNER JOIN branch b ON b.AddressNo = so_header.ADDR_NO
									 INNER JOIN SID_files sf ON sf.Branch = b.code AND sf.FileDate = so_header.ORDER_DATE AND sf.Type = '$sidtype'
									 $daterange
									 AND so_header.doc_type = 'S1'
									 $branchquery
									 order by b.id 
		                           ");
		return $query; 						   
	}
	
    function errorlog($database, $datefrom, $dateto, $displaytype,$istotal, $page, $total, $branch,$sidtype)
	{

        $start = ($page > 1)?(($page - 1) * $total):0;
        $limit = (!$istotal)?"LIMIT $start, $total":"";
		
        $datefrom = date("Y-m-d", strtotime($datefrom));
        $dateto = date("Y-m-d", strtotime($dateto));
        $daterange = "AND DATE_FORMAT(si.TxnDate, '%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";
		$displaytypeq = $displaytype == '' ? '' : "AND s.StatusID = $displaytype" ;
		$branchquery = $branch =='0' ? '' : "and s.branch = '$branch'";
		$sidtypequery = $sidtype == '' ? '' : "and s.subcode = '$sidtype'";
		#echo $datefrom.'hhh'.$dateto.'xxxx'.$displaytypeq.'ffff'.$branch.'hhhhh'.$sidtypequery ;
		
        $query = $database->execute("SELECT * 
									 FROM sid_logs s
									 WHERE s.Filedate BETWEEN '$datefrom' AND '$dateto'
									 $displaytypeq
									 $branchquery
									 $sidtypequery 
									 group by Filedate,SubCode,Branch,Filename,Remarks
									 $limit ");
        return $query; 
    }
  
?>