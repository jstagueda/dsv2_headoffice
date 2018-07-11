
<?php
include "../../../initialize.php";
include IN_PATH.DS."scgrossmarginreport.php";

// product auto-completer

if(isset($_POST['searchitem'])){
    $productRange = productRange($_POST['searchitem']);
    if($productRange->num_rows){
        while($res = $productRange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code));
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}

if(isset($_POST['searchbrand'])){
    $brandrange = brandrange($_POST['searchbrand']);
    if($brandrange->num_rows){
        while($res = $brandrange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code) );
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}


if(isset($_POST['searchsubbrand'])){
    $subbrandrange = subbrandrange($_POST['searchsubbrand']);
    if($subbrandrange->num_rows){
        while($res = $subbrandrange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code) );
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}

if(isset($_POST['searchform'])){
    $formrange = formrange($_POST['searchform']);
    if($formrange->num_rows){
        while($res = $formrange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code) );
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}


if(isset($_POST['searchsubform'])){
    $subformrange = subformrange($_POST['searchsubform']);
    if($subformrange->num_rows){
        while($res = $subformrange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code) );
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}


if(isset($_POST['searchsize'])){
    $sizerange = sizerange($_POST['searchsize']);
    if($sizerange->num_rows){
        while($res = $sizerange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code) );
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}

if(isset($_POST['searchpl'])){
    $plrange = plrange($_POST['searchpl']);
    if($plrange->num_rows){
        while($res = $plrange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code) );
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}


if(isset($_POST['searchcategory'])){
    $catrange = catrange($_POST['searchcategory']);
    if($catrange->num_rows){
        while($res = $catrange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code) );
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}


if(isset($_POST['searchbranch'])){
    $branchrange = branchrange($_POST['searchbranch']);
    if($branchrange->num_rows){
        while($res = $branchrange->fetch_object()){
            $result[] = array("ID" => $res->id, 
							  "Namecode" => TRIM($res->code)." - ".$res->name,
							  "Nameonly" => $res->name,
							  "Code" => TRIM($res->code) );
        }
    }else{
        $result[] = array("ID" => 0, "Namecode" => "No result found.");
    }
    tpi_JSONencode($result);
}




if(isset($_POST['FromDate']) and isset($_POST['ToDate'])){

	$reporttype        = $_POST['reporttype'];
	$summary           = $_POST['summary'];
	$itemid            = ($_POST['item']=="")?'':$_POST['itemid'];
	$itemname          = $_POST['itemname'];
	$brandid           = ($_POST['brand']=="")?'':$_POST['brandcode'];
	$brandname         = $_POST['brandname'];
	$subbrandid        = ($_POST['subbrand']=="")?'':$_POST['subbrandcode'];
	$subbrandname      = $_POST['subbrandname'];
	$formid            = ($_POST['form']=="")?'':$_POST['formcode'];
	$formname          = $_POST['formname'];
	$subformid         = ($_POST['subform']=="")?'':$_POST['subformcode'];
	$subformname       = $_POST['subformname'];
	$sizeid            = ($_POST['size']=="")?'':$_POST['sizecode'];
	$sizename          = $_POST['sizename'];
	$productlineid     = ($_POST['productline']=="")?'':$_POST['productlineid'];
	$productcategoryid = ($_POST['productcategory']=="")?'':$_POST['productcategoryid'];
	$pmgtype		   = $_POST['pmgtype'];
	$branchid_main     = ($_POST['branchname']=="")?'':$_POST['branchid'];
	$branchid_main     = ($branchid_main=='')?0:$_POST['branchid'];
	$plgrouping        = $_POST['plgrouping'];
	#$branchid_main=0;
	$sortby        = $_POST['sortby'];
	$page       = (isset($_POST['page']))?$_POST['page']:1;
	$FromDate   = date('Y-m-d',strtotime($_POST['FromDate']));
	$ToDate     = date('Y-m-d',strtotime($_POST['ToDate']));
	$OpenOnly   = (boolean)$_POST['OpenOnly'];
	$page       = ($_POST['page'])?$_POST['page']:0;
    $pagerows   = 30;
	$plbuffer   = array();
	$catbuffer  = array();
	$brbuffer   = array();
	$datebuffer = array();
	//$linecounter= 0;
		
/* echo $reporttype.'<br>';
echo $summary.'<br>';		
echo $itemid.'<br>';
echo $itemname.'<br>';
echo $brandid.'<br>';
echo $brandname.'<BR>';
echo $subbrandid.'<br>';
echo $subbrandname.'<br>';
echo $formid.'<br>';
echo $formname.'<br>';
echo $subformid.'<br>';
echo $subformname.'<br>';
echo $sizeid.'<br>';
echo $sizename.'<br>';
echo $unitid.'<br>';
echo $unitname.'<br>';
echo $nsv.'<br>';
echo $margin.'<br>';
echo $sortby.'<br>'; */
		
	
	$database->execute("
						UPDATE interface_so_details s
						INNER JOIN product p ON p.code = s.ITEM_NO
						LEFT JOIN productkit pk ON pk.ComponentID = p.id AND s.ORDER_DATE BETWEEN pk.StartDate AND pk.EndDate 
						SET reserved_char01='C'
						WHERE 1=1 
						AND DATE(s.ORDER_DATE) BETWEEN '$FromDate' AND '$ToDate'
						AND pk.ComponentID IS NOT NULL");


	$database->execute("UPDATE interface_so_details
							SET reserved_char01='P'
							WHERE line_type='N'
							AND DATE(ORDER_DATE) BETWEEN '$FromDate' AND '$ToDate'
							AND item_no NOT LIKE '97%'");

	$grossmargin = grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 0, 0, $productlineid,$productcategoryid,$branchid_main,$pmgtype,$plgrouping);
	
	
	$grossmargin_total = grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid,$productcategoryid,$branchid_main,$pmgtype,$plgrouping);
	
	echo '<table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">';

	if($grossmargin->num_rows)
	{
		$linecounter = 0;
		
		############################################
		###########  Per Product Summary ###########
		############################################
		
		if(($reporttype==0) and ($summary==1))
		{
		
		echo '<tr class="tablelisttr">
				<td>#</td>
				<td>Item Number</td>
				<td>Description</td>
				<td>Brand</td>
				<td>Sub Brand</td>
				<td>Form</td>
				<td>Sub Form</td>
				<td>Size</td>
				<td>Units</td>
				<td width = 8%>NSV</td>
				<td>Gross Profit</td>
				<td width = 8%>%Margin</td>	
				
			 </tr>';		
		
		
		$ii = ($page - 1) * $pagerows;
		
		while($res = $grossmargin->fetch_object())
			{     
				
				if(($linecounter!=0) and (end($plbuffer)!=$res->plid )  )
				{
					$FromDate_q = $FromDate ;
					$ToDate_q   = $ToDate ;
					$productlineid_q = end($plbuffer) ;
					$productcategoryid_q = 0 ;
					$branchid_q = $branchid_main ;
					
					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);
					
					if($grossmargin_subtot->num_rows)
					{
						while($res2 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_plcode = $res2->plcode;
							$sub_plname = $res2->plname;
							$sub_units  = $res2->QTY;
							$sub_NSV    = $res2->NSV;
							$sub_Margin = $res2->margin;
							$sub_percentmargin=$res2->percentmargin;
						}
					}	
						
					echo "<tr height='18' class='trlist' style='background:pink; font-weight:bold;'>
			
							  <td colspan='8' align='right' style='padding-right:5px;'>Total For Product Line (".$sub_plcode.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						</tr>	
						 "; 
				}
				
				if( !in_array(($res->plid), $plbuffer ))
				{
					
					echo "<tr>
							<td colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
								<b>Product Line: ".$res->plcode."-".$res->plname." </b> 
							</td>
						  </tr>	
						 "; 
		
					$plbuffer[] = $res->plid;
				} 
	
				$ii++;
		
				echo '<tr class="listtr">';
					echo "<td>".$ii."</td>
						  <td>".$res->productcode."</td>
						  <td>".$res->productname."</td>
						  <td>".$res->brand."</td>
						  <td>".$res->subbrand."</td>
						  <td>".$res->form."</td>
						  <td align='right'>".$res->subform."</td>
						  <td align='right'>".$res->size."</td>
						  <td align='right'>".number_format($res->QTY)."</td>
						  <td align='right'>".number_format($res->NSV,2)."</td>
						  <td align='right'>".number_format($res->margin,2)."</td>
						  <td align='right'>".number_format($res->percentmargin,2)."</td>
						 "; 
				echo '</tr>';
				
				$lastprodid = $res->productid;
				$lastplid   = $res->plid;
				$lastplcode = $res->plcode;
				$lastplname = $res->plname;
				$linecounter++;
			}
	
			$grossmargin_footertotal = grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,0,1,$lastplid,$productcategoryid,$branchid_main,$pmgtype,$plgrouping);	

			if($grossmargin_footertotal->num_rows)
			{
				while($res0 = $grossmargin_footertotal->fetch_object())
					{
						$lastrecord_prd = $res0->productid; 
						$lastrecord_prdcode = $res0->productcode;
						$lastrecord_pl  = $res0->plid;
						
					}
			}
				
			if(($lastrecord_prd==$lastprodid)  )   
			{
				
	
					$FromDate_q = $FromDate ;
					$ToDate_q   = $ToDate ;
					$productlineid_q = $lastrecord_pl ;
					$productcategoryid_q = 0 ;
					$branchid_q = $branchid_main ;						
				
					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);	

				
					
					if($grossmargin_subtot->num_rows)
					{
						while($res3 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_plcode = $res3->plcode;
							$sub_plname = $res3->plname;
							$sub_units  = $res3->QTY;
							$sub_NSV    = $res3->NSV;
							$sub_Margin = $res3->margin;
							$sub_percentmargin=$res3->percentmargin;
						}
					}
						
					echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
							  <td colspan='8' align='right' style='padding-right:5px;'>Total For Product Line (".$lastplcode.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						 </tr>	
						  "; 					 
			 
			}


			$FromDate_q          = $FromDate ;
			$ToDate_q            = $ToDate ;
			$productlineid_q     = $productlineid ;
			$productcategoryid_q = $productcategoryid ;
			$branchid_q          = $branchid_main ;

			$grossmargin_grandtotal = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 1,$productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);

			
			if($grossmargin_grandtotal->num_rows)
			{
				
				while($res3 = $grossmargin_grandtotal->fetch_object())
				{ 			
					$sub_plcode = $res3->plcode;
					$sub_plname = $res3->plname;
					$sub_units  = $res3->QTY;
					$sub_NSV    = $res3->NSV;
					$sub_Margin = $res3->margin;
					$sub_percentmargin=$res3->percentmargin;
				}
			}	
				
			echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
					  <td colspan='8' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($sub_units)."</td>
					  <td align='right'>".number_format($sub_NSV,2)."</td>
					  <td align='right'>".number_format($sub_Margin,2)."</td>
					  <td align='right'>".number_format($sub_percentmargin,2)."</td>
				 </tr>	
				  "; 	

	
		}
		
		############################################
		###########  Per Product Detail ############
	    ############################################
		
		else if(($reporttype==0) and ($summary==0))
		{
		
			echo '<tr class="tablelisttr">
					<td>#</td>
					<td>Item Number</td>
					<td>Description</td>
					<td>Branch Plant/Location Code</td>
					<td>Branch Plant/Location Name</td>
					<td>Units</td>
					<td>NSV</td>
					<td>Margin</td>
					<td>%Margin</td>
				 </tr>';		
		
			$ii = ($page - 1) * $pagerows;
		
			while($res = $grossmargin->fetch_object())
			{
				
				if(($linecounter!=0) and (end($plbuffer)!=$res->plid )  )
				{
					
					
					$FromDate_q = $FromDate ;
					$ToDate_q   = $ToDate ;
					$productlineid_q = end($plbuffer) ;
					$productcategoryid_q = 0 ;
					$branchid_q = $branchid_main ;					
					
					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);					
					if($grossmargin_subtot->num_rows)
					{
						while($res2 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_plcode = $res2->plcode;
							$sub_plname = $res2->plname;
							$sub_units  = $res2->QTY;
							$sub_NSV    = $res2->NSV;
							$sub_Margin = $res2->margin;
							$sub_percentmargin=$res2->percentmargin;
						}
					}	
						
					echo "<tr height='18' class='trlist' style='background:pink; font-weight:bold;'>
			
							  <td colspan='5' align='right' style='padding-right:5px;'>Total For Product Line (".$sub_plcode.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						</tr>	
						 "; 
				}				
				
				if( !in_array(($res->plid), $plbuffer ))
				{
					
					echo "<tr>
							<td colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
								<b>Product Line: ".$res->plcode."-".$res->plname." </b> 
							</td>
						  </tr>	
						 "; 
		
					$plbuffer[] = $res->plid;
				}					
				
			$ii++;
 	
			echo '<tr class="listtr">';
				echo "<td>".$ii."</td>
				      <td>".$res->productcode."</td>
					  <td>".$res->productname."</td>
					  <td>".$res->branchcode."</td>
					  <td>".$res->branchname."</td>
					  <td align='right'>".number_format($res->QTY)."</td>
					  <td align='right'>".number_format($res->NSV,2)."</td>
					  <td align='right'>".number_format($res->margin,2)."</td>
					  <td align='right'>".number_format($res->percentmargin,2)."</td>
					 "; 
			echo '</tr>';
			
			
			$lastprodid = $res->productid;
			$lastplcode = $res->plcode;
			$lastplname = $res->plname;
			$lastplid   = $res->plid;
			$lastbrid   = $res->branchid;
			$linecounter++;
	
			}

			$grossmargin_footertotal = grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,0,1,$lastplid,$productcategoryid,$branchid_main,$pmgtype,$plgrouping);		

			


			if($grossmargin_footertotal->num_rows)
			{
				while($res0 = $grossmargin_footertotal->fetch_object())
					{
						$lastrecord_prd = $res0->productid; 
						$lastrecord_prdcode = $res0->productcode;
						$lastrecord_pl  = $res0->plid;
						$lastrecord_brid  = $res0->branchid;
						
					}
			}	
			

			
			if(($lastrecord_prd==$lastprodid) and ($lastbrid == $lastrecord_brid) )   
			{

					$FromDate_q = $FromDate ;
					$ToDate_q   = $ToDate ;
					$productlineid_q = $lastrecord_pl ;
					$productcategoryid_q = 0 ;
					$branchid_q = $branchid_main ;	

					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);	


					
					if($grossmargin_subtot->num_rows)
					{
						while($res3 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_plcode = $res3->plcode;
							$sub_plname = $res3->plname;
							$sub_units  = $res3->QTY;
							$sub_NSV    = $res3->NSV;
							$sub_Margin = $res3->margin;
							$sub_percentmargin=$res3->percentmargin;
						}
					}	
						
					echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
							  <td colspan='5' align='right' style='padding-right:5px;'>Total For Product Line (".$lastplcode.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						 </tr>	
						  "; 					 
			 
			}

			$FromDate_q          = $FromDate ;
			$ToDate_q            = $ToDate ;
			$productlineid_q     = $productlineid ;
			$productcategoryid_q = $productcategoryid ;
			$branchid_q          = $branchid_main ;			


			$grossmargin_grandtotal = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 1,$productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);
			
			
			
			if($grossmargin_grandtotal->num_rows)
			{
				
				while($res3 = $grossmargin_grandtotal->fetch_object())
				{ 			
					$sub_plcode = $res3->plcode;
					$sub_plname = $res3->plname;
					$sub_units  = $res3->QTY;
					$sub_NSV    = $res3->NSV;
					$sub_Margin = $res3->margin;
					$sub_percentmargin=$res3->percentmargin;
				}
			}	
				
			echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
					  <td colspan='5' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($sub_units)."</td>
					  <td align='right'>".number_format($sub_NSV,2)."</td>
					  <td align='right'>".number_format($sub_Margin,2)."</td>
					  <td align='right'>".number_format($sub_percentmargin,2)."</td>
				 </tr>	
				  "; 	

			
		}
		
		############################################
		###########  Per PL Summary     ############
	    ############################################
	
		else if(($reporttype==1) and ($summary==1))
		{
		
			echo '<tr class="tablelisttr">
					<td>#</td>
					
					<td>Product Line</td>
					<td>Description</td>
					<td>Units</td>
					<td>NSV</td>
					<td>Margin</td>
					<td>%Margin</td>
				 </tr>';		
		
		
			$ii = ($page - 1) * $pagerows;
		
			while($res = $grossmargin->fetch_object())
			{
				
				if(($linecounter!=0) and (end($catbuffer)!=$res->catid )  )
				{
					
					$FromDate_q = $FromDate ;
					$ToDate_q   = $ToDate ;
					$productlineid_q = 0 ;
					$productcategoryid_q = end($catbuffer) ;
					$branchid_q = $branchid_main ;					
					

					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);

					
					if($grossmargin_subtot->num_rows)
					{
						while($res2 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_catcode = $res2->catcode;
							$sub_catname = $res2->catname;
							$sub_units  = $res2->QTY;
							$sub_NSV    = $res2->NSV;
							$sub_Margin = $res2->margin;
							$sub_percentmargin=$res2->percentmargin;
						}
					}	
						
					echo "<tr height='18' class='trlist' style='background:pink; font-weight:bold;'>
			
							  <td colspan='3' align='right' style='padding-right:5px;'>Total For Product Category (".$sub_catcode.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						</tr>	
						 ";
						
				}
				

				if( !in_array(($res->catid), $catbuffer ))
				{
					$catbuffer[] = $res->catid;
				}				
		
		
				$ii++;
 	
				echo '<tr class="listtr">';
					echo "<td>".$ii."</td>
						  <td>".$res->plcode."</td>
						  <td>".$res->plname."</td>
						  <td align='right'>".number_format($res->QTY)."</td>
						  <td align='right'>".number_format($res->NSV,2)."</td>
						  <td align='right'>".number_format($res->margin,2)."</td>
						  <td align='right'>".number_format($res->percentmargin,2)."</td>
						 "; 
				echo '</tr>';
				
				$linecounter++;
				$lastprodid = $res->productid;
				$lastplcode = $res->plcode;
				$lastplname = $res->plname;
				$lastcatid  = $res->catid;
				$lastcatcode = $res->catcode;	
				
			}

			
			$grossmargin_footertotal = grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,0,1,$productlineid,$lastcatid,$branchid_main,$pmgtype,$plgrouping);				
			

			if($grossmargin_footertotal->num_rows)
			{
				while($res0 = $grossmargin_footertotal->fetch_object())
					{

						$lastrecord_catid   = $res0->catid;
					}
			}	
				
			if(($lastrecord_catid==$lastcatid)  )   
			{
				
					$FromDate_q = $FromDate ;
					$ToDate_q   = $ToDate ;
					$productlineid_q = $productlineid ;
					$productcategoryid_q = $lastrecord_catid ;
					$branchid_q = $branchid_main ;				

					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);	

		
					
					if($grossmargin_subtot->num_rows)
					{
						while($res3 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_plcode = $res3->plcode;
							$sub_plname = $res3->plname;
							$sub_units  = $res3->QTY;
							$sub_NSV    = $res3->NSV;
							$sub_Margin = $res3->margin;
							$sub_percentmargin=$res3->percentmargin;
						}
					}	
						
					echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
							  <td colspan='3' align='right' style='padding-right:5px;'>Total For Product Category (".$lastcatcode.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						 </tr>	
						  "; 					 
			 
			}


			$FromDate_q          = $FromDate ;
			$ToDate_q            = $ToDate ;
			$productlineid_q     = $productlineid ;
			$productcategoryid_q = $productcategoryid ;
			$branchid_q          = $branchid_main ;			
			
			$grossmargin_grandtotal = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 1,$productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);

			
			if($grossmargin_grandtotal->num_rows)
			{
				
				while($res3 = $grossmargin_grandtotal->fetch_object())
				{ 			
					$sub_plcode = $res3->plcode;
					$sub_plname = $res3->plname;
					$sub_units  = $res3->QTY;
					$sub_NSV    = $res3->NSV;
					$sub_Margin = $res3->margin;
					$sub_percentmargin=$res3->percentmargin;
				}
			}	
				
			echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
					  <td colspan='3' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($sub_units)."</td>
					  <td align='right'>".number_format($sub_NSV,2)."</td>
					  <td align='right'>".number_format($sub_Margin,2)."</td>
					  <td align='right'>".number_format($sub_percentmargin,2)."</td>
				 </tr>	
				  "; 

			
		}
		
		############################################
		###########  Per PL Detail #    ############
	    ############################################
		
		else if(($reporttype==1) and ($summary==0))
		{

	
			echo '<tr class="tablelisttr">
					<td>#</td>
					<td>Branch</td>
					<td>Product Line</td>
					<td>Description</td>
					<td>Units</td>
					<td>NSV</td>
					<td>Margin</td>
					<td>%Margin</td>
				 </tr>';		

				 
			$ii = ($page - 1) * $pagerows;
		
			while($res = $grossmargin->fetch_object())
			{
				
				
				if(($linecounter!=0) and (end($brbuffer)!=$res->branchid ) )
				{

					$FromDate_q = $FromDate ;
					$ToDate_q   = $ToDate ;
					$productlineid_q = 0 ;
					$productcategoryid_q = $productcategoryid ;
					$branchid_q = end($brbuffer) ;

					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping) ;

					
					
					if($grossmargin_subtot->num_rows)
					{
						while($res2 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_brcode = $res2->branchcode;
							$sub_brname = $res2->branchname;
							$sub_units  = $res2->QTY;
							$sub_NSV    = $res2->NSV;
							$sub_Margin = $res2->margin;
							$sub_percentmargin=$res2->percentmargin;
						}
					
					}	
						
					echo "<tr height='18' class='trlist' style='background:pink; font-weight:bold;'>
			
							  <td colspan='4' align='right' style='padding-right:5px;'>Total For Branch (".$sub_brcode.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						</tr>	
						 "; 
				}	

		
				
				if( !in_array(($res->branchid), $brbuffer ))
				{
					
					echo "<tr>
							<td colspan='8' height='18' class='trlist' style='background:pink; font-weight:bold;'>
								<b> Branch: ".$res->branchcode."-".$res->branchname." </b> 
							</td>
						  </tr>	
						 "; 
		
					$brbuffer[] = $res->branchid;
				}					
				
			
				$ii++;
		
				echo '<tr class="listtr">';
					echo "<td>".$ii."</td>
						  <td>".$res->branchcode."</td>
						  <td>".$res->plcode."</td>
						  <td>".$res->plname."</td>
						  <td align='right'>".number_format($res->QTY)."</td>
						  <td align='right'>".number_format($res->NSV,2)."</td>
						  <td align='right'>".number_format($res->margin,2)."</td>
						  <td align='right'>".number_format($res->percentmargin,2)."</td>
				
						 "; 
				echo '</tr>';
				
				$lastprodid   = $res->productid;
				$lastbranchid = $res->branchid;
				$lastbranchcode = $res->branchcode;
				$lastplid     = $res->plid;
				$lastcatid    = $res->catid;
				$lastcatcode  = $res->catcode;				
				$linecounter++;					
			
			}


			
			$grossmargin_footertotal = grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,0,1,$productlineid,$productcategoryid,$lastbranchid,$pmgtype,$plgrouping);				
			


			if($grossmargin_footertotal->num_rows)
			{
				while($res0 = $grossmargin_footertotal->fetch_object())
					{
						$lastrecord_prd = $res0->productid; 
						$lastrecord_prdcode = $res0->productcode;
						$lastrecord_pl  = $res0->plid; 
						$lastrecord_branchid   = $res0->branchid;
						$lastrecord_branchcode = $res0->branchcode;
						
						$lastrecord_catid   = $res0->catid;
						
					}
			}	
				
			if(($lastbranchid==$lastrecord_branchid) and ($lastplid == $lastrecord_pl)  )   
			{

					$FromDate_q = $FromDate ;
					$ToDate_q   = $ToDate ;
					$productlineid_q = $productlineid ;
					$productcategoryid_q = $productcategoryid ;
					$branchid_q = $lastrecord_branchid    ;

					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);			


					
					if($grossmargin_subtot->num_rows)
					{
						while($res3 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_plcode = $res3->plcode;
							$sub_plname = $res3->plname;
							$sub_units  = $res3->QTY;
							$sub_NSV    = $res3->NSV;
							$sub_Margin = $res3->margin;
							$sub_percentmargin=$res3->percentmargin;
						}
					}	
						
					echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
							  <td colspan='4' align='right' style='padding-right:5px;'>Total For Branch (".$lastbranchcode.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						 </tr>	
						  "; 					 
			 
			}

			$FromDate_q          = $FromDate ;
			$ToDate_q            = $ToDate ;
			$productlineid_q     = $productlineid ;
			$productcategoryid_q = $productcategoryid ;
			$branchid_q          = $branchid_main ;				

			$grossmargin_grandtotal = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 1,$productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);


			
			if($grossmargin_grandtotal->num_rows)
			{
				while($res3 = $grossmargin_grandtotal->fetch_object())
				{ 			
					$sub_plcode = $res3->plcode;
					$sub_plname = $res3->plname;
					$sub_units  = $res3->QTY;
					$sub_NSV    = $res3->NSV;
					$sub_Margin = $res3->margin;
					$sub_percentmargin=$res3->percentmargin;
				}
			}	
				
			echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
					  <td colspan='4' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($sub_units)."</td>
					  <td align='right'>".number_format($sub_NSV,2)."</td>
					  <td align='right'>".number_format($sub_Margin,2)."</td>
					  <td align='right'>".number_format($sub_percentmargin,2)."</td>
				 </tr>	
				  "; 
			
		}		

		############################################
		###########  Per Day Summary    ############
	    ############################################
		
		else if(($reporttype==2) and ($summary==1))
		{
		
			echo '<tr class="tablelisttr">
					<td>#</td>
					<td>Date</td>
					
					<td>Units</td>
					<td>NSV</td>
					<td>Margin</td>
					<td>%Margin</td>
				 </tr>';		
			
			
			$ii = ($page - 1) * $pagerows;
			
			while($res = $grossmargin->fetch_object())
				{
				
				$ii++;
		
				echo '<tr class="listtr">';
					echo "<td>".$ii."</td>
						  <td>".$res->txndate."</td>
						
						  <td align='right'>".number_format($res->QTY)."</td>
						  <td align='right'>".number_format($res->NSV,2)."</td>
						  <td align='right'>".number_format($res->margin,2)."</td>
						  <td align='right'>".number_format($res->percentmargin,2)."</td>
						 "; 
				echo '</tr>';
				}


			$FromDate_q          = $FromDate ;
			$ToDate_q            = $ToDate ;
			$productlineid_q     = $productlineid ;
			$productcategoryid_q = $productcategoryid ;
			$branchid_q          = $branchid_main ;			
			
			$grossmargin_grandtotal = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 1,$productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);
			
			if($grossmargin_grandtotal->num_rows)
			{
				
				while($res3 = $grossmargin_grandtotal->fetch_object())
				{ 			
					$sub_plcode = $res3->plcode;
					$sub_plname = $res3->plname;
					$sub_units  = $res3->QTY;
					$sub_NSV    = $res3->NSV;
					$sub_Margin = $res3->margin;
					$sub_percentmargin=$res3->percentmargin;
				}
			}	
				
			echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
					  <td colspan='2' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($sub_units)."</td>
					  <td align='right'>".number_format($sub_NSV,2)."</td>
					  <td align='right'>".number_format($sub_Margin,2)."</td>
					  <td align='right'>".number_format($sub_percentmargin,2)."</td>
				 </tr>	
				  "; 				
				
	
		}


		############################################
		###########  Per Day Details    ############
	    ############################################
 
		else if(($reporttype==2) and ($summary==0))
		{
		
			echo '<tr class="tablelisttr">
					<td>#</td>
					<td>Date</td>
					<td>Product Line</td>
					<td>Units</td>
					<td>NSV</td>
					<td>Margin</td>
					<td>%Margin</td>
				 </tr>';		
			
			
			$ii = ($page - 1) * $pagerows;
			
			while($res = $grossmargin->fetch_object())
				{

			
			
				if(($linecounter!=0) and (end($datebuffer)!=$res->txndate ) )
				{
					
					$FromDate_q          = end($datebuffer) ;
					$ToDate_q            = end($datebuffer) ;
					$productlineid_q     = $productlineid ;
					$productcategoryid_q = $productcategoryid ;
					$branchid_q          = $branchid_main ;					
					
					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);
					
					

					
					if($grossmargin_subtot->num_rows)
					{
						while($res2 = $grossmargin_subtot->fetch_object())
						{ 	
							$sub_txn    = $res2->txndate;
							$sub_txn_formatted    = $res2->txndate_formatted;
							$sub_brcode = $res2->branchcode;
							$sub_brname = $res2->branchname;
							$sub_units  = $res2->QTY;
							$sub_NSV    = $res2->NSV;
							$sub_Margin = $res2->margin;
							$sub_percentmargin=$res2->percentmargin;
						}
					
					}	
						
					echo "<tr height='18' class='trlist' style='background:pink; font-weight:bold;'>
			
							  <td colspan='3' align='right' style='padding-right:5px;'>Total For Date (".$sub_txn_formatted.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						</tr>	
						 "; 
				}

			
					
				if( !in_array(($res->txndate), $datebuffer ))
				{
					
					echo "<tr>
							<td colspan='7' height='18' class='trlist' style='background:pink; font-weight:bold;'>
								<b>".$res->txndate_formatted." </b> 
							</td>
						  </tr>	
						 "; 
		
					$datebuffer[] = $res->txndate;
				}						
					
					
					
					
				$ii++;
		
					echo '<tr class="listtr">';
						echo "<td>".$ii."</td>
							  <td>".$res->txndate."</td>
							  <td>".$res->plcode."</td>
							  <td align='right'>".number_format($res->QTY)."</td>
							  <td align='right'>".number_format($res->NSV,2)."</td>
							  <td align='right'>".number_format($res->margin,2)."</td>
							  <td align='right'>".number_format($res->percentmargin,2)."</td>
							 "; 
					echo '</tr>';
					
				$lastprodid   = $res->productid;
				$lastbranchid = $res->branchid;
				$lastplid     = $res->plid;
				$lastdate     = $res->txndate;
				$lastdate_formatted = $res->txndate_formatted;
				
				$linecounter++;							
					
				}


			$grossmargin_footertotal = grossmargin($reporttype,$summary,$lastdate,$lastdate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows,0,1,$productlineid,$productcategoryid,$branchid_main,$pmgtype,$plgrouping);	

	


			if($grossmargin_footertotal->num_rows)
			{
				while($res0 = $grossmargin_footertotal->fetch_object())
					{
						$lastrecord_prd = $res0->productid; 
						$lastrecord_prdcode = $res0->productcode;
						$lastrecord_pl  = $res0->plid; 
						$lastbranchid   = $res0->branchid;
						$lastbranchcode = $res0->branchcode;
						$lastrecord_date       = $res0->txndate;
						
					}
			}	
				
			if( ($lastrecord_date==$lastdate) and ($lastplid==$lastrecord_pl) )   
			{


					$FromDate_q          = $lastrecord_date ;
					$ToDate_q            = $lastrecord_date ;
					$productlineid_q     = $productlineid ;
					$productcategoryid_q = $productcategoryid ;
					$branchid_q          = $branchid_main ;					
					
					$grossmargin_subtot = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);

					
					if($grossmargin_subtot->num_rows)
					{
						while($res3 = $grossmargin_subtot->fetch_object())
						{ 			
							$sub_plcode = $res3->plcode;
							$sub_plname = $res3->plname;
							$sub_units  = $res3->QTY;
							$sub_NSV    = $res3->NSV;
							$sub_Margin = $res3->margin;
							$sub_percentmargin=$res3->percentmargin;
						}
					}	
						
					echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
							  <td colspan='3' align='right' style='padding-right:5px;'>Total For Date (".$lastdate_formatted.")</td>
							  <td align='right'>".number_format($sub_units)."</td>
							  <td align='right'>".number_format($sub_NSV,2)."</td>
							  <td align='right'>".number_format($sub_Margin,2)."</td>
							  <td align='right'>".number_format($sub_percentmargin,2)."</td>
						 </tr>	
						  "; 					 
			 
			}

			$FromDate_q          = $FromDate ;
			$ToDate_q            = $ToDate ;
			$productlineid_q     = $productlineid ;
			$productcategoryid_q = $productcategoryid ;
			$branchid_q          = $branchid_main ;

			$grossmargin_grandtotal = grossmargin_subtotal($reporttype,$summary,$FromDate_q,$ToDate_q,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 1,$productlineid_q, $productcategoryid_q, $branchid_q,$pmgtype,$plgrouping);			
			
	  
		
			if($grossmargin_grandtotal->num_rows)
			{
				
				while($res3 = $grossmargin_grandtotal->fetch_object())
				{ 			
					$sub_plcode = $res3->plcode;
					$sub_plname = $res3->plname;
					$sub_units  = $res3->QTY;
					$sub_NSV    = $res3->NSV;
					$sub_Margin = $res3->margin;
					$sub_percentmargin=$res3->percentmargin;
				}
			}	
				
			echo "<tr colspan='12' height='18' class='trlist' style='background:pink; font-weight:bold;'>
					  <td colspan='3' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($sub_units)."</td>
					  <td align='right'>".number_format($sub_NSV,2)."</td>
					  <td align='right'>".number_format($sub_Margin,2)."</td>
					  <td align='right'>".number_format($sub_percentmargin,2)."</td>
				 </tr>	
				  "; 	

				  
		}	 

	} else {
		echo '<tr class="listtr">
			<td align="center" colspan="13">No result found.</td>
		</tr>';
	}
	
	echo "</table><br>";
	
	echo "<div style='margin-top:10px;' class='igsfield'>".  AddPagination( $pagerows , $grossmargin_total->num_rows , $page)."</div>";
	
	
	if($reporttype==2)
	{	

	echo "<div style='margin-top:10px;' class='igsfield'><b>Note that the following Product Lines Are Included in This Report:</b></div>";
	
	$grossmargin_total_bypl = grossmargin(3,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid,$productcategoryid,$branchid_main,$pmgtype,$plgrouping);		

			if($grossmargin_total_bypl->num_rows)
			{
				
			echo "<table>";	
		
				while($res4 = $grossmargin_total_bypl->fetch_object())
				{ 			
					$footer_plcode = $res4->plcode;
					$footer_plname = $res4->plname;
					
					echo "<tr height='18' class='trlist' >
							   <td align='right'>".$footer_plcode."-</td>
							    <td align='left'>".$footer_plname."</td>
						 </tr>	
							"; 					
				
				}
			echo "</table><br>";		
			}	
	
	}
	
}
?>
