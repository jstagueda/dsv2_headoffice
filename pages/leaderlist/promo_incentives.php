<script src="js/jquery-1.9.1.min.js" language="javascript" type="text/javascript"></script>
<script src="js/jquery-ui-1.10.0.custom.min.js" language="javascript" type="text/javascript"></script>
<script src="js/jxViewIncentives.js" language="javascript" type="text/javascript"></script>
<script>
$(document).ready(function(){
	//alert('this');
	
	//DynamicTable
	
	var header = "";
		header += "<tr align='center' class='txtdarkgreenbold10' style='background:#FFDEF0;'>";
		header += "<td width='15%' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>";
		header += "<td class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>";
		header += "</tr>";
		var txtPromoCodeDesc = $("#txtPromoCodeDesc").val();
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {"request":"fetch data","txtPromoCodeDesc":txtPromoCodeDesc},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_promo_incentives.php',
		success: function(resp){
				//var i = 0; resp['fetch_data'].length > i; i++
			if(resp['response'] == 'successs'){
			//alert(resp['fetch_data']);
				for(var i = 0; resp['fetch_data'].length > i; i++){
					//<a href='javascript:void(0)' onclick='return openPopUp($ID)' class='txtnavgreenlink'>$row->Code</a>
					header += "<tr align='center' class='trlist'>";
					header += "<td class=' bdiv_r'><div align= 'center' class=''><a href='javascript:void(0)' onclick='return openPopUp("+resp['fetch_data'][i].ID+")' class=''>"+resp['fetch_data'][i].Code+"</div></td>";
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].Description+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].StartDate+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].EndDate+"</div></td>";
					header += "</tr>";
				}
			}else{
					header += "<tr align='center' class='trlist'>";
					header += "<td colspan='5' align='center' class='borderBR'><span class='txtredsbold'>No record(s) to display.</span></td>";
					header += "</tr>";
			}
			
			$("#DynamicTable").html(header);
			$("#pagination").html(resp['pagination'].page);
			return false;
		}
	});
});


function showPage(page)
{
	var start = $("#from_date_search").val();
	var end = $("#to_date_search").val()
	
	
	var header = "";
		header += "<tr align='center' class='txtdarkgreenbold10' style='background:#FFDEF0;'>";
		header += "<td width='15%' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>";
		header += "<td class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>";
		header += "</tr>";
									
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'fetch data', 'page': page},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_promo_incentives.php',
		success: function(resp){
				//var i = 0; resp['fetch_data'].length > i; i++
			if(resp['response'] == 'successs'){
			//alert(resp['fetch_data']);
				for(var i = 0; resp['fetch_data'].length > i; i++){
					//<a href='javascript:void(0)' onclick='return openPopUp($ID)' class='txtnavgreenlink'>$row->Code</a>
					header += "<tr align='center' class='trlist'>";
					header += "<td class=' bdiv_r'><div align= 'center' class=''><a href='javascript:void(0)' onclick='return openPopUp("+resp['fetch_data'][i].ID+")' class=''>"+resp['fetch_data'][i].Code+"</div></td>";
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].Description+"</div></td>";			
					header += "<td  class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].StartDate+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].EndDate+"</div></td>";
					header += "</tr>";
				}
			}else{
					header += "<tr align='center' class='trlist'>";
					header += "<td colspan='5' align='center' class='borderBR'><span class='txtredsbold'>No record(s) to display.</span></td>";
					header += "</tr>";
			}
			
			$("#DynamicTable").html(header);
			$("#pagination").html(resp['pagination'].page);
			return false;
		}
	});
	//alert(page);
	//return false;
}

function xbtnSearch()
{
	//alert('xx');
	//return false;
	var txtPromoCodeDesc = $("#txtPromoCodeDesc").val();
	var txtProductCode	 = $("#txtProductCode").val();
	var header = "";
		header += "<tr align='center' class='txtdarkgreenbold10' style='background:#FFDEF0;'>";
		header += "<td width='15%' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>";
		header += "<td class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>";
		header += "</tr>";
									
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'fetch data', 'txtPromoCodeDesc':txtPromoCodeDesc,'txtProductCode':txtProductCode},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_promo_incentives.php',
		success: function(resp){
				//var i = 0; resp['fetch_data'].length > i; i++
			if(resp['response'] == 'successs'){
			//alert(resp['fetch_data']);
				for(var i = 0; resp['fetch_data'].length > i; i++){
					//<a href='javascript:void(0)' onclick='return openPopUp($ID)' class='txtnavgreenlink'>$row->Code</a>
					header += "<tr align='center' class='trlist'>";
					header += "<td class=' bdiv_r'><div align= 'center' class=''><a href='javascript:void(0)' onclick='return openPopUp("+resp['fetch_data'][i].ID+")' class=''>"+resp['fetch_data'][i].Code+"</div></td>";
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].Description+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].StartDate+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].EndDate+"</div></td>";
					header += "</tr>";
				}
			}else{
					header += "<tr align='center' class='trlist'>";
					header += "<td colspan='5' align='center' class='borderBR'><span class='txtredsbold'>No record(s) to display.</span></td>";
					header += "</tr>";
			}
			
			$("#DynamicTable").html(header);
			header = "";
			$("#pagination").html(resp['pagination'].page);
			return false;
		}
	});
	return false;
}

</script>
<style type="text/css">
div.autocomplete {
  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;} 
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px solid #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}
.txtdarkgreenbold10 td{border-bottom: 2px solid #FFA3E0; padding:5px;}
.trlist td{border-bottom: 1px solid #FFA3E0; padding:5px;}
</style>

<?php
		
	$vSearch ="";
	$scodedesc = "";
	$sproductcode = "";
	$isinc = 0;		
	(isset($_POST['txtPromoCodeDesc']) ? $scodedesc = $_POST['txtPromoCodeDesc'] : $scodedesc = "");
	(isset($_POST['txtProdCode1']) ? $sproductcode = $_POST['txtProdCode1'] : $sproductcode = "");
	
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		    <td width="70%" class="txtgreenbold13" align="Left"></td>
			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		<td width="70%">&nbsp;<a class="txtgreenbold13">Incentives Promo</a></td>	
		</tr>
		</table>
	</td>
</tr>
</table>
<?php if (isset($_GET['errmsg'])): ?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtreds">&nbsp;<b><?php echo $_GET['errmsg']; ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php endif; ?>
<?php if (isset($_GET['msg'])): ?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $_GET['msg']; ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php endif; ?>
<br>
<form name="frmViewSetPromo" method="post" action="index.php?pageid=66&inc=2&startrow=0" style="min-height:535px;">
	<div style="margin:auto; width:95%;">
		<table width="40%"  border="0" align="left" cellpadding="0" cellspacing="0">
			<tr>
				<td class="tabmin"></td> 
				   <td class="tabmin2"><div align="left" class="txtredbold padl5">Action</div></td>
				<td class="tabmin3">&nbsp;</td>
			</tr>
		</table>
		<div style="clear:both;"></div>
		<table width="40%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
			<tr>
				<td>
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>&nbsp;</td>
						<td width="5%">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>			
					<tr>
						<td align="right">
							<strong>Promo Code / Description</strong>
						</td>
						<td align="center">:</td>
						<td style="padding:3px;">
							<input name="txtPromoCodeDesc" type="text" class="txtfield" id="txtPromoCodeDesc" value="" size="30">
						</td>
					</tr>
					<tr>
						<td align="right">
							<strong>Item Code</strong>
						</td>
						<td align="center">:</td>
						<td style="padding:3px;">
							<input name="txtProductCode" type="text" class="txtfield" id="txtProductCode" value="" size="30" >
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style="padding:3px;">
							<input name="btnSearch" onclick = "return xbtnSearch();"  type="submit" class="btn" value="Search">
						</td>
					</tr>
					<tr>
						<td height="20" colspan="3">&nbsp;</td>
					</tr>	
					</table>
				</td>
			</tr>
		</table>
		<div style="clear:both;"></div>
	</div>
		<br>
		<br>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
       <td class="tabmin2"><div align="left" class="txtredbold padl5">List of Incentive Promos</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
		<tr>
			<td class="bgF9F8F7">
				<div>
					<table width="100%"   border="0" cellpadding="0" cellspacing="0" id = "DynamicTable">
					<tr>
						<td colspan='5' height='30' class='borderBR'><div align='center'><span class='txtredsbold'>Fetching Data Please wait.</span></div></td>
					</tr>
					<!--ajax here -->
					</table>
				
				</div>
			</td>
		</tr>
	</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="3" class="bgE6E8D9"></td>
        </tr>
      </table>
      <br>
    

<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">

<table width="95%"  border="0" align="center" cellpadding="0" >
	<tr><td id = "pagination"></td></tr>
</table>	
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
		<input name='btnCreate' type='submit' class='btn' value='Create New' onclick="location.href='index.php?pageid=67'; return false;" />
	</td>		
</tr>
</table>
	
	</td>			
</tr>
</table>
<br></form>