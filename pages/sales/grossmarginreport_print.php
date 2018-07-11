<style>
	body, html, table{font-size:12px; font-family:Arial;}
	.bordersolo{border-collapse:collapse;}
	.trheader td{font-weight:bold; text-align:center; padding:5px; }
	.trlist td{padding:5px;}
	.fieldlabel{width:25%; font-weight:bold; text-align:right;}
	.fieldlabelleft{font-weight:bold; text-align:left;}
	.separator{width:5%; font-weight:bold; text-align:center;}
</style>


<?php 

	ini_set('MAX_EXECUTION_TIME', -1);
	include "../../initialize.php";
	include IN_PATH.DS."scgrossmarginreport.php";

	$reporttype        = $_GET['reporttype'];
	$summary           = $_GET['summary'];
	$itemid            = ($_GET['item']=="")?'':$_GET['itemid'];
	$itemname          = ($_GET['item']=="")?'':$_GET['itemname'];    
	$brandid           = ($_GET['brand']=="")?'':$_GET['brandcode'];
	$brandname         = ($_GET['brand']=="")?'':$_GET['brandname'];    
	$subbrandid        = ($_GET['subbrand']=="")?'':$_GET['subbrandcode'];
	$subbrandname      = ($_GET['subbrand']=="")?'':$_GET['subbrandname'];    
	$formid            = ($_GET['form']=="")?'':$_GET['formcode'];
	$formname          = ($_GET['form']=="")?'':$_GET['formname'];  
	$subformid         = ($_GET['subform']=="")?'':$_GET['subformcode'];
	$subformname       = ($_GET['subform']=="")?'':$_GET['subformname'];   
	$sizeid            = ($_GET['size']=="")?'':$_GET['sizecode'];
	$sizename          = ($_GET['size']=="")?'':$_GET['sizename']; 
	$productlineid     = ($_GET['productline']=="")?'':$_GET['productlineid'];
	$productcategoryid = ($_GET['productcategory']=="")?'':$_GET['productcategoryid'];	
	$branchid_main     = ($_GET['branchname']=="")?'':$_GET['branchid'];
	$branchid_main     = ($branchid_main=='')?0:$_GET['branchid'];	
	$pmgtype		   = $_GET['pmgtype'];
	$plgrouping        = $_GET['plgrouping'];
	$sortby            = $_GET['sortby'];
	$page       	   = (isset($_GET['page']))?$_GET['page']:1;
	$FromDate          = date('Y-m-d',strtotime($_GET['FromDate']));
	$ToDate            = date('Y-m-d',strtotime($_GET['ToDate']));
	//$OpenOnly          = (boolean)$_GET['OpenOnly'];
	$page              = ($_GET['page'])?$_GET['page']:0;
    $pagerows          = 30;
	$plbuffer          = array();
	$catbuffer         = array();
	$brbuffer          = array();
	$datebuffer        = array();

	
/*
echo $reporttype.'<br>';
echo 'rtype: '.$reporttype.'<br>';
echo 'summary: '.$summary.'<br>';	
echo 'FromDate: '.$FromDate.'<br>';	
echo 'ToDate: '.$ToDate.'<br>';
echo 'itemid: '.$itemid.'<br>';
echo 'itemname: '.$itemname.'<br>';
echo 'brandid: '.$brandid.'<br>';
echo 'brandname: '.$brandname.'<BR>';
echo 'subbrandid: '.$subbrandid.'<br>';
echo 'subbrandname: '.$subbrandname.'<br>';
echo 'formid: '.$formid.'<br>';
echo 'formname: '.$formname.'<br>';
echo 'subformid: '.$subformid.'<br>';
echo 'subformname: '.$subformname.'<br>';
echo 'sizeid: '.$sizeid.'<br>';
echo 'sizename: '.$sizename.'<br>';
echo 'productlineid: '.$productlineid.'<br>';
echo 'productcategoryid: '.$productcategoryid.'<br>';
echo 'branchid_main: '.$branchid_main.'<br>';
echo 'pmgtype: '.$pmgtype.'<br>';
echo 'plgrouping: '.$plgrouping.'<br>';
 */

 
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
 
 
	
	if($_GET['reporttype']==0)
	{
		$reportdesc = "Per Product";
	}	
	elseif($_GET['reporttype']==1)
	{
		$reportdesc = "Per Product Line";
	}
	elseif($_GET['reporttype']==2)
	{
		$reportdesc = "Per Day";
	}
	
	$summarytype=($summary)?"Yes":"No";
	
	//--- report header ---
	echo "<div style='padding:10px; font-size:14px; text-align:center; font-weight:bold;'>Gross Margin Report</div>";
	echo "<table width='100%' cellpadding='1' cellspacing='2'>
	        <tr>
				<td class='fieldlabelleft'>Report Type</td>
				<td class='separator'>:</td>
				<td>".$reportdesc."</td>
				<td class='fieldlabel'>Is Summary</td>
				<td class='separator'></td>
				<td>".$summarytype."</td>
			</tr>
			<tr>
				<td class='fieldlabelleft'>Item</td>
				<td class='separator'>:</td>
				<td>".$itemname."</td>
				<td class='fieldlabel'>Product Line</td>
				<td class='separator'>:</td>
				<td>".$_GET['productlinename']."</td>
			</tr>
			<tr>
				<td class='fieldlabelleft'>Date Range</td>
				<td class='separator'>:</td>
				<td>".$_GET['FromDate']." to ".$_GET['ToDate']."</td>
				<td class='fieldlabel'>Product Category</td>
				<td class='separator'>:</td>
				<td>".$_GET['productcategoryname']."</td>
			</tr>
		
			<tr>
				<td class='fieldlabelleft'>Product Line Grouping</td>
				<td class='separator'>:</td>
				<td>".$_GET['plgrp']."</td>
				<td class='fieldlabel'>PMG</td>
				<td class='separator'>:</td>
				<td>".$_GET['pmgtypecode']."</td>
			</tr>
			
			<tr>
				<td class='fieldlabelleft'>Brand</td>
				<td class='separator'>:</td>
				<td>".$_GET['brandname']."</td>
				<td class='fieldlabel'>Sub Brand</td>
				<td class='separator'>:</td>
				<td>".$_GET['subbrandname']."</td>
			</tr>

			<tr>
				<td class='fieldlabelleft'>Form</td>
				<td class='separator'>:</td>
				<td>".$_GET['formname']."</td>
				<td class='fieldlabel'>Sub Form</td>
				<td class='separator'>:</td>
				<td>".$_GET['subformname']."</td>
			</tr>			

			<tr>
				<td class='fieldlabelleft'>Size</td>
				<td class='separator'>:</td>
				<td>".$_GET['sizename']."</td>
				<td class='fieldlabel'>Run Date</td>
				<td class='separator'>:</td>
				<td>".date('F d, Y h:s a')."</td>
			</tr>			

			<tr>
				<td class='fieldlabelleft'>Branch</td>
				<td class='separator'>:</td>
				<td>".$_GET['branchcode']."</td>
				<td class='fieldlabel'>Run By</td>
				<td class='separator'>:</td>
				<td>".$_SESSION['user_session_name']."</td>
			</tr>
		
		  </table><br>";
		  
	  
	$grossmargin = grossmargin($reporttype,$summary,$FromDate,$ToDate,$itemid,$itemname,$brandid,$subbrandid,$formid,$subformid,$sizeid,$unitid,$nsv,$margin,$sortby,$page,$pagerows, 1, 0, $productlineid,$productcategoryid,$branchid_main,$pmgtype,$plgrouping);
	

	$sub_units       = 0; 
	$sub_NSV         = 0;
	$sub_totalcost   = 0;
	$sub_grossmargin = 0;

	
	
	echo '<div class="pageset">
			<table cellpadding="0" cellspacing="0" width="100%" border=1>';

	if($grossmargin->num_rows)
	{
		$linecounter = 0;
		
		############################################
		###########  Per Product Summary ###########
		############################################
		
		if(($reporttype==0) and ($summary==1))
		{
		
			echo '<tr class="trheader">
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

						$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
						$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
						$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;		

						echo "<tr height='18' class='trlist' style=' font-weight:bold;'>
				
								  <td colspan='8' align='right' style='padding-right:5px;'>Total For Product Line (".$lastplcode.")</td>
								  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
								  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
								  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
								  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
							</tr>	";
							
						$sub_units_1       = 0; 
						$sub_NSV_1         = 0;
						$sub_totalcost_1   = 0;
						$sub_grossmargin_1 = 0;
					
						 
					}
					
					$sub_units_1     += $res->QTY;
					$sub_NSV_1       += $res->NSV;
					$sub_totalcost_1 += $res->totalcost;
					
					if( !in_array(($res->plid), $plbuffer ))
					{
						echo "<tr>
								<td colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
									<b>Product Line: ".$res->plcode."-".$res->plname." </b> 
								</td>
							  </tr>	
							 "; 
			
						$plbuffer[] = $res->plid;
					} 
		
					$ii++;
			
					echo '<tr class="listtr">';
						echo "<td align='center'>".$ii."</td>
							  <td align='center'>".$res->productcode."</td>
							  <td align='left' style='padding-left:5px;'>".$res->productname."</td>
							  <td align='center'>".$res->brand."</td>
							  <td align='center'>".$res->subbrand."</td>
							  <td align='center'>".$res->form."</td>
							  <td align='center'>".$res->subform."</td>
							  <td align='center'>".$res->size."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($res->QTY)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($res->NSV,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($res->margin,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($res->percentmargin,2)."</td>
							 "; 
					echo '</tr>';
					
					$lastprodid     = $res->productid;
					$lastplid       = $res->plid;
					$lastplcode     = $res->plcode;
					$lastplname     = $res->plname;
					$linecounter++;
					$grand_units    += $res->QTY; 
					$grand_NSV      += $res->NSV;	
					$grand_Totalcost+= $res->totalcost;
			
				}
		
						
						$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
						$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
						$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;						  
							  
						echo "<tr height='18' class='trlist' style=' font-weight:bold;'>
				
								  <td colspan='8' align='right' style='padding-right:5px;'>Total For Product Line (".$lastplcode.")</td>
								  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
								  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
								  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
								  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
							</tr>	";				
				

					$grand_Margin        =	$grand_NSV -$grand_Totalcost ;				  
					$grand_percentMargin =	(($grand_NSV -$grand_Totalcost)/$grand_NSV)*100;				  
					  
					  
				echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
						  <td colspan='8' align='right' style='padding-right:5px;'>Grand Total : </td>
						  <td align='right'>".number_format($grand_units)."</td>
						  <td align='right'>".number_format($grand_NSV,2)."</td>
						  <td align='right'>".number_format($grand_Margin,2)."</td>
						  <td align='right'>".number_format($grand_percentMargin,2)."</td>
					 </tr>	
					  "; 				  
		  
	
		}
		
		############################################
		###########  Per Product Detail ############
	    ############################################
		
		else if(($reporttype==0) and ($summary==0))
		{
		
			echo '<tr class="trheader">
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
					
						 
				$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
				$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
				$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;							 
					
				echo "<tr height='18' class='trlist' style=' font-weight:bold;'>
		
						  <td colspan='5' align='right' style='padding-right:5px;'>Total For Product Line (".$lastplcode.")</td>
						  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
					</tr>	
					 "; 
				$sub_units_1       = 0; 
				$sub_NSV_1         = 0;
				$sub_totalcost_1   = 0;
				$sub_grossmargin_1 = 0;						 

					
				}	


				$sub_units_1     += $res->QTY;
				$sub_NSV_1       += $res->NSV;
				$sub_totalcost_1 += $res->totalcost;				
				
				if( !in_array(($res->plid), $plbuffer ))
				{
					
					echo "<tr>
							<td colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
								<b>Product Line: ".$res->plcode."-".$res->plname." </b> 
							</td>
						  </tr>	
						 "; 
		
					$plbuffer[] = $res->plid;
				}					
				
				$ii++;
		
				echo '<tr class="listtr">';
					echo "<td align='center'>".$ii."</td>
						  <td align='center'>".$res->productcode."</td>
						  <td align='left' style='padding-left:5px;'>".$res->productname."</td>
						  <td align='center'>".$res->branchcode."</td>
						  <td align='left' style='padding-left:5px;'>".$res->branchname."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->QTY)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->NSV,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->margin,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->percentmargin,2)."</td>
						 "; 
				echo '</tr>';
				
				
				$lastprodid = $res->productid;
				$lastplcode = $res->plcode;
				$lastplname = $res->plname;
				$lastplid   = $res->plid;
				$lastbrid   = $res->branchid;
				$linecounter++;
				
				$grand_units         +=	$res->QTY; 
				$grand_NSV           +=	$res->NSV;	
				$grand_Totalcost     +=	$res->totalcost;			
			
	
			}

			$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
			$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
			$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;				
			
			
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='5' align='right' style='padding-right:5px;'>Total For Product Line (".$lastplcode.")</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
				 </tr>	
				  "; 				

			$grand_Margin        =	$grand_NSV -$grand_Totalcost ;				  
			$grand_percentMargin =	(($grand_NSV -$grand_Totalcost)/$grand_NSV)*100;
					
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='5' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($grand_units)."</td>
					  <td align='right'>".number_format($grand_NSV,2)."</td>
					  <td align='right'>".number_format($grand_Margin,2)."</td>
					  <td align='right'>".number_format($grand_percentMargin,2)."</td>
				 </tr>	
				  ";  
			
		}
		
		############################################
		###########  Per PL Summary     ############
	    ############################################
	
		else if(($reporttype==1) and ($summary==1))
		{
		
			echo '<tr class="trheader">
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

					$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
					$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
					$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;	
						 
					echo "<tr height='18' class='trlist' style=' font-weight:bold;'>
			
							  <td colspan='3' align='right' style='padding-right:5px;'>Total For Product Category (".$lastcatcode.")</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
						</tr>	
						 "; 
						 
					$sub_units_1       = 0; 
					$sub_NSV_1         = 0;
					$sub_totalcost_1   = 0;
					$sub_grossmargin_1 = 0;
						 
						
				}

				$sub_units_1     += $res->QTY;
				$sub_NSV_1       += $res->NSV;
				$sub_totalcost_1 += $res->totalcost;				
				

				if( !in_array(($res->catid), $catbuffer ))
				{
					$catbuffer[] = $res->catid;
				}				
		
		
				$ii++;
 	
				echo '<tr class="listtr">';
					echo "<td align='center'>".$ii."</td>
						  <td align='center'>".$res->plcode."</td>
						  <td align='left' style='padding-left:5px;'>".$res->plname."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->QTY)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->NSV,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->margin,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->percentmargin,2)."</td>
						 "; 
				echo '</tr>';
				
				$linecounter++;
				$lastprodid = $res->productid;
				$lastplcode = $res->plcode;
				$lastplname = $res->plname;
				$lastcatid  = $res->catid;
				$lastcatcode = $res->catcode;	
				
				$grand_units         +=	$res->QTY; 
				$grand_NSV           +=	$res->NSV;	
				$grand_Totalcost     +=	$res->totalcost;									
				
				
			}

			$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
			$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
			$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;
			
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='3' align='right' style='padding-right:5px;'>Total For Product Category (".$lastcatcode.")</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
				 </tr>	
				  "; 				


			$grand_Margin        =	$grand_NSV -$grand_Totalcost ;				  
			$grand_percentMargin =	(($grand_NSV -$grand_Totalcost)/$grand_NSV)*100;					  
				  
				  
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='3' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($grand_units)."</td>
					  <td align='right'>".number_format($grand_NSV,2)."</td>
					  <td align='right'>".number_format($grand_Margin,2)."</td>
					  <td align='right'>".number_format($grand_percentMargin,2)."</td>
				 </tr>	
				  "; 				  
			
		}
		
		############################################
		###########  Per PL Detail #    ############
	    ############################################
		
		else if(($reporttype==1) and ($summary==0))
		{

	
			echo '<tr class="trheader">
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
					
					$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
					$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
					$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;					
						 
					echo "<tr height='18' class='trlist' style=' font-weight:bold;'>
							  <td colspan='4' align='right' style='padding-right:5px;'>Total For Branch (".$lastbranchcode.")</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
						</tr>	
						 "; 
					$sub_units_1       = 0; 
					$sub_NSV_1         = 0;
					$sub_totalcost_1   = 0;
					$sub_grossmargin_1 = 0;						 
						 
				}	

				$sub_units_1     += $res->QTY;
				$sub_NSV_1       += $res->NSV;
				$sub_totalcost_1 += $res->totalcost;		
				
				if( !in_array(($res->branchid), $brbuffer ))
				{
					
					echo "<tr>
							<td colspan='8' height='18' class='trlist' style=' font-weight:bold;'>
								<b> Branch: ".$res->branchcode."-".$res->branchname." </b> 
							</td>
						  </tr>	
						 "; 
		
					$brbuffer[] = $res->branchid;
				}					
				
			
				$ii++;
		
				echo '<tr class="listtr">';
					echo "<td align='center'>".$ii."</td>
						  <td align='center'>".$res->branchcode."</td>
						  <td align='center'>".$res->plcode."</td>
						  <td align='left' style='padding-left:5px;'>".$res->plname."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->QTY)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->NSV,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->margin,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->percentmargin,2)."</td>
				
						 "; 
				echo '</tr>';
				
				$lastprodid   = $res->productid;
				$lastbranchid = $res->branchid;
				$lastbranchcode = $res->branchcode;
				$lastplid     = $res->plid;
				$lastcatid    = $res->catid;
				$lastcatcode  = $res->catcode;				
				$linecounter++;	

				$grand_units         +=	$res->QTY; 
				$grand_NSV           +=	$res->NSV;	
				$grand_Totalcost     +=	$res->totalcost;				
			
			}


			$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
			$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
			$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;
			
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='4' align='right' style='padding-right:5px;'>Total For Branch (".$lastbranchcode.")</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
				 </tr>	
				  "; 			
			

			$grand_Margin        =	$grand_NSV -$grand_Totalcost ;				  
			$grand_percentMargin =	(($grand_NSV -$grand_Totalcost)/$grand_NSV)*100;	
				  
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='4' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($grand_units)."</td>
					  <td align='right'>".number_format($grand_NSV,2)."</td>
					  <td align='right'>".number_format($grand_Margin,2)."</td>
					  <td align='right'>".number_format($grand_percentMargin,2)."</td>
				 </tr>	
				  "; 
	
		}		

		############################################
		###########  Per Day Summary    ############
	    ############################################
		
		else if(($reporttype==2) and ($summary==1))
		{
		
			echo '<tr class="trheader">
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
					echo "<td align='center'>".$ii."</td>
						  <td align='center'>".$res->txndate."</td>
						
						  <td align='right' style='padding-right:5px;'>".number_format($res->QTY)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->NSV,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->margin,2)."</td>
						  <td align='right' style='padding-right:5px;'>".number_format($res->percentmargin,2)."</td>
						 "; 
				echo '</tr>';
				
				$grand_units         +=	$res->QTY; 
				$grand_NSV           +=	$res->NSV;	
				$grand_Totalcost     +=	$res->totalcost;				
				
				
				}

			$grand_Margin        =	$grand_NSV -$grand_Totalcost ;				  
			$grand_percentMargin =	(($grand_NSV -$grand_Totalcost)/$grand_NSV)*100;				  
				  
				  
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='2' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($grand_units)."</td>
					  <td align='right'>".number_format($grand_NSV,2)."</td>
					  <td align='right'>".number_format($grand_Margin,2)."</td>
					  <td align='right'>".number_format($grand_percentMargin,2)."</td>
				 </tr>	
				  "; 
				  
	
		}


		############################################
		###########  Per Day Details    ############
	    ############################################
 
		else if(($reporttype==2) and ($summary==0))
		{
		
			echo '<tr class="trheader">
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
					

					$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
					$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
					$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;						 
						 
					echo "<tr height='18' class='trlist' style=' font-weight:bold;'>
			
							  <td colspan='3' align='right' style='padding-right:5px;'>Total For Date (".$lastdate_formatted.")</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
						</tr>	
						 "; 						 
					$sub_units_1       = 0; 
					$sub_NSV_1         = 0;
					$sub_totalcost_1   = 0;
					$sub_grossmargin_1 = 0;						 
						 
				}

				$sub_units_1     += $res->QTY;
				$sub_NSV_1       += $res->NSV;
				$sub_totalcost_1 += $res->totalcost;			
					
				if( !in_array(($res->txndate), $datebuffer ))
				{
					
					echo "<tr>
							<td colspan='7' height='18' class='trlist' style=' font-weight:bold;'>
								<b>".$res->txndate_formatted." </b> 
							</td>
						  </tr>	
						 "; 
		
					$datebuffer[] = $res->txndate;
				}						
					
					
					
					
				$ii++;
		
					echo '<tr class="listtr">';
						echo "<td align='center'>".$ii."</td>
							  <td align='center'>".$res->txndate."</td>
							  <td align='center'>".$res->plcode."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($res->QTY)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($res->NSV,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($res->margin,2)."</td>
							  <td align='right' style='padding-right:5px;'>".number_format($res->percentmargin,2)."</td>
							 "; 
					echo '</tr>';
					
				$lastprodid   = $res->productid;
				$lastbranchid = $res->branchid;
				$lastplid     = $res->plid;
				$lastdate     = $res->txndate;
				$lastdate_formatted = $res->txndate_formatted;
				
				$linecounter++;							
	
				$grand_units         +=	$res->QTY; 
				$grand_NSV           +=	$res->NSV;	
				$grand_Totalcost     +=	$res->totalcost;
	
	
				}

			$sub_NSV_1	       = ($sub_NSV_1)?$sub_NSV_1:0;
			$sub_grossmargin   = $sub_NSV_1  - $sub_totalcost_1 ;	
			$sub_grossmargin_p = ($sub_NSV_1==0)?0:(($sub_NSV_1  - $sub_totalcost_1)/$sub_NSV_1)*100;				
			
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='3' align='right' style='padding-right:5px;'>Total For Date (".$lastdate_formatted.")</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_units_1)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_NSV_1,2)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin,2)."</td>
					  <td align='right' style='padding-right:5px;'>".number_format($sub_grossmargin_p,2)."</td>
				 </tr>	
				  "; 			


			$grand_Margin        =	$grand_NSV -$grand_Totalcost ;				  
			$grand_percentMargin =	(($grand_NSV -$grand_Totalcost)/$grand_NSV)*100;					  
				  
			echo "<tr colspan='12' height='18' class='trlist' style=' font-weight:bold;'>
					  <td colspan='3' align='right' style='padding-right:5px;'>Grand Total : </td>
					  <td align='right'>".number_format($grand_units)."</td>
					  <td align='right'>".number_format($grand_NSV,2)."</td>
					  <td align='right'>".number_format($grand_Margin,2)."</td>
					  <td align='right'>".number_format($grand_percentMargin,2)."</td>
				 </tr>	
				  "; 					  
				  
		}	 

	} else {
		echo '<tr class="listtr">
			<td align="center" colspan="13">No result found.</td>
		</tr>';
	}
	
	echo "</table></div><br>";
	
	
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
	

?>

<script>
	window.print();
</script>