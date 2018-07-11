<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<script src="js/jquery-1.9.1.min.js" language="javascript" type="text/javascript"></script>
<script src="js/jquery-ui-1.10.0.custom.min.js" language="javascript" type="text/javascript"></script>
<script src="js/jxViewSetPromo.js" language="javascript" type="text/javascript"></script>
<!--script src="js/jxViewMultiLinePromo.js" language="javascript" type="text/javascript"></script-->

<style>
	.txtdarkgreenbold10 td{
        border-bottom: 2px solid #FFA3E0;
		 padding:5px;
    }
    .trlist td{border-bottom: 1px solid #FFA3E0; padding:5px;}
</style>

<script type="text/javascript">
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
		var txtProductCode	 = $("#txtProductCode").val();
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {"request":"fetch data", 'txtPromoCodeDesc':txtPromoCodeDesc,'txtProductCode':txtProductCode},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_overlay.php',
		success: function(resp){
				//var i = 0; resp['fetch_data'].length > i; i++
			if(resp['response'] == 'successs'){
			//alert(resp['fetch_data']);
				for(var i = 0; resp['fetch_data'].length > i; i++){
					//<a href='javascript:void(0)' onclick='return openPopUp($ID)' class='txtnavgreenlink'>$row->Code</a>
					header += "<tr align='center' class='trlist'>";
					header += "<td class=' bdiv_r'><div align= 'center' class=''><a href='javascript:void(0)' onclick= 'return openPopUp("+resp['fetch_data'][i].ID+")' class=''>"+resp['fetch_data'][i].Code+"</div></td>";
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
	
	
	var header = "";
		header += "<tr align='center' class='txtdarkgreenbold10' style='background:#FFDEF0;'>";
		header += "<td width='15%' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>";
		header += "<td class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>";
		header += "</tr>";
		
	var txtPromoCodeDesc = $("#txtPromoCodeDesc").val();
	var txtProductCode	 = $("#txtProductCode").val();
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'fetch data', 'page': page, 'txtPromoCodeDesc':txtPromoCodeDesc,'txtProductCode':txtProductCode},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_overlay.php',
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
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_overlay.php',
		success: function(resp){
			if(resp['response'] == 'successs'){
				for(var i = 0; resp['fetch_data'].length > i; i++){
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

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}


function openPopUp(objID, objInc) 
{
	var objWin;
		popuppage = "pages/leaderlist/promo_setpromoDetails.php?prmsid=" + objID + "&inc="+objInc;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false;  		
}


</script>
<?php 
/* 
 *  Modified by: marygrace cabardo 
 *  10.08.2012
 *  marygrace.cabardo@gmail.com
 */
	$vSearch ="";
	$scodedesc = "";
	$sproductcode = "";
	
	(isset($_POST['txtPromoCodeDesc']) ? $scodedesc = $_POST['txtPromoCodeDesc'] : $scodedesc = "");
	(isset($_POST['txtProductCode'])   ? $sproductcode = $_POST['txtProductCode'] : $sproductcode = "");
	
	if(isset($_POST["btnCreate"])) 
	{
		header("Location:index.php?pageid=63");
	}
	

?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
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
			<td>&nbsp;<a class="txtgreenbold13">List of Overlay Promos</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
if (isset($_GET['errmsg'])) 
{
?>
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
<?php
}
?>
<?php
if (isset($_GET['msg'])) 
{
?>
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
<?php
}
?>
<br>
<form name="frmViewMultiLine" method="post" action="index.php?pageid=62" style="min-height:505px;">
	<div style="width:95%; margin:auto;">
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
						<td height="20" align="right">
							<strong>Promo Code / Description</strong>
						</td>
						<td align="center">:</td>
						<td style="padding:3px;">
							<input name="txtPromoCodeDesc" type="text" class="txtfieldg" id="txtPromoCodeDesc" value="" size="30">
						</td>
					</tr>
					<tr>
						<td height="20" align="right">
							<strong>Product Code</strong>
						</td>
						<td align="center">:</td>
						<td style="padding:3px;">
							<input name="txtProductCode" type="text" class="txtfieldg" id="txtProductCode" value="" size="30" >
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td style="padding:3px;">
							<input name="btnSearch" type="submit" class="btn" onclick = "return xbtnSearch();"  value="Search">
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
			<td class="tabmin2"><div align="left" class="txtredbold padl5">List of Multi Line Promos</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
	</table>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
<tr>
  <td valign="top" class="bgF9F8F7">
  <div>
  <table width="100%"   border="0" cellpadding="0" cellspacing="0" id = "DynamicTable">
						<tr align='center' class='txtdarkgreenbold10 tab'>
							<td width='4%' height='20' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>			
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>			
							<td width='15%' height='20' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>			
						</tr>
						<tr><td colspan='5' height='30' class='borderBR'><div align='center'><span class='txtredsbold'>Fetching Data please wait.</span></div></td></tr>
						
	</table>
  </div>
  </td>
</tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" style="margin-top:10px;">
	<tr><td id = "pagination"></td></tr>
</table>	
</form>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
		
<form name="frmViewSetPromo" method="post" action="index.php?pageid=178.1&inc=<?php if($isinc == 0) echo "1"; else echo"2";?>"> 
		<!--<input name='btnPrint' type='submit' class='btn' value='Print'>-->
		<input name='btnCreate' type='submit' class='btn' value='Create New'>	
</form>
	</td>			
</tr>
</table>
<br>
