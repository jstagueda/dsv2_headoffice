
<link rel= "stylesheet" type= "text/css" href= "css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "css/calpopup.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script>
$(document).ready(function(){
/*Date Picker*/
	$("#date_to").datepicker();
	$("#date_from").datepicker();
	
	$("#type").change(function(){
		if($("#type").val() == 1){
			$("#si_from").removeAttr("disabled","disabled");
			$("#si_to").removeAttr("disabled","disabled");
			$("#advance_po").removeAttr("disabled","disabled");
			$("#or_from").attr("disabled","disabled");
			$("#or_to ").attr("disabled","disabled");
		} else if($("#type").val() == 2){
			$("#si_from").attr("disabled","disabled");
			$("#si_to").attr("disabled","disabled");
			$("#advance_po").attr("disabled","disabled");
			$("#or_from").removeAttr("disabled","disabled");
			$("#or_to ").removeAttr("disabled","disabled");
		}else{
			$("#advance_po").attr("disabled","disabled");
			$("#si_from").attr("disabled","disabled");
			$("#si_to").attr("disabled","disabled");
			$("#or_from").attr("disabled","disabled");
			$("#or_to ").attr("disabled","disabled");
		}
	});
	/*auto completer*/
	$('#dealer_code_from').autocomplete({
		source:'includes/ajax_generate_SiandOR.php?request=autocompleter',
			select: function( event, ui ) {
				$( "#dealer_code_from").val(ui.item.Code);
			
			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			 return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.Code + "</strong> - " + item.Dealer + "</a>" )
			.appendTo( ul );
	}
	
	$('#dealer_code_to').autocomplete({
		source:'includes/ajax_generate_SiandOR.php?request=autocompleter',
			select: function( event, ui ) {
				$("#dealer_code_to").val(ui.item.Code);
			
			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			 return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.Code + "</strong> - " + item.Dealer + "</a>" )
			.appendTo( ul );
	}
	/*end autocompleter*/
	
	//printing validation
	
	$("#print").click(function(){
		if($("#chkInclude:checked").length == 1){
			if(confirm("Are you sure want to print this transaction?")==false){
			return false;}else{return true;}
		}else{
			alert('Please select OR/SI report');
			return false;
		}
	});
	
});

function generate_list()
{
	var error_msg = "", error_cnt = 0;
	var xtype 				= $("#type").val();
	var date_from 			= $("#date_from").val();	
	var date_to             = $("#date_to ").val();

	var dealer_code_from    = $("#dealer_code_from").val();
	var dealer_code_to      = $("#dealer_code_to").val();

	
	var s_date 				= Date.parse(date_from);
    var sdate  				= new Date(s_date); 
	
    var e_date 				= Date.parse(date_to);
    var edate  				= new Date(e_date);
	
	var now 				= new Date();
	var now_day   			= now.getDate();
	var now_month 			= now.getMonth() + 1;
	var now_year  			= now.getFullYear();
	var now_date  			= now_month + "/" + now_day + "/" + now_year;
	
	if(xtype == 1){
		if(dealer_code_to != ""){
			if(dealer_code_from == ""){
				error_msg += "*Dealer From required. \n";
				error_cnt++;
			}
		}
	}else if(xtype == 2){	
		if(dealer_code_to != ""){
			if(dealer_code_from == ""){
				error_msg += "*Dealer From required. \n";
				error_cnt++;
			}
		}
	}
	else{
		error_msg += "*Type Required. \n";
		error_cnt++;
	}
	
	if(date_from == ""){
		error_msg += "*Date to required. \n";
		error_cnt++;
	}
	if(date_to == ""){
		error_msg += "*Date from required. \n";
		error_cnt++;
	}
	
	if(date_from != "" && date_to != ""){
		/*
		if(getDateObject(date_from, "/") < getDateObject(now_date, "/")){			
			error_msg += "*Start date should be current or future date. \n";
			error_cnt++;
		}
		*/
			
		if(sdate > edate){			
			error_msg += "*End date should be the same or later than Start date. \n";
			error_cnt++;
		}
	}
		
	if(error_cnt > 0){
		alert(error_msg);
		return false;
	}else{
		//alert('gotcha!');
		//return false;
		var fetching_data = '', dynamic_SoSi_Number = "", dynamic_SoSi_Date = '';
		$.ajax({
		    type: 'post',
			dataType: 'json',
			data: $('form').serialize(),
			url: 'includes/ajax_generate_SiandOR.php?request=generatelist',
			success: function(resp){
				if(resp['response'].result=="success"){
					for(var i = 0; resp['fetch_data'].length > i; i++){
						fetching_data += '<tr ><td width="3%" height="20" class="borderBR">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name = "chkInclude" value = "'+resp["fetch_data"][i].TxnID+'" id ="chkInclude"></td>';
						fetching_data += '<td width="7%"   height="20" class="borderBR">'+resp["fetch_data"][i].TxnNo+'</td>';
						fetching_data += '<td width="10%"  height="20" class="borderBR">'+resp["fetch_data"][i].DocumentNo+'</td>';
						fetching_data += '<td width="10%"  height="20" class="borderBR">'+resp["fetch_data"][i].CustomerName+'</td>';
						fetching_data += '<td width="10%"  height="20" class="borderBR">'+resp["fetch_data"][i].TxnDate+'</td>';	
						fetching_data += '<td width="10%"  height="20" class="borderBR">'+resp["fetch_data"][i].TxnStatus+'</td></tr>';	
					}
					var total_record = resp['fetch_data'].length;
					$("#dynamic_table").html(fetching_data);
					$("#total_numrows").val(total_record);
					$("#print").removeAttr("disabled","disabled");
					
					if(xtype == 1){
						dynamic_SoSi_Number = "SI Number";
						dynamic_SoSi_Date	= "SI Date";
					}else{
						dynamic_SoSi_Number = "OR Number";
						dynamic_SoSi_Date	= "OR Date";
					}
					
					$("#dynamiclabelSiSoNumber").html(dynamic_SoSi_Number);
					$("#dynamiclabel_SiSoDate").html(dynamic_SoSi_Date);
				}else{
					alert('no record(s) result.');
					$("#dynamic_table").html("");
					$("#print").attr("disabled","disabled");
				}	
			}
			
		})
		return false;
	}
}

function getDateObject(dateString,dateSeperator)
{
	//This function return a date object after accepting 
	//a date string ans dateseparator as arguments
	var curValue=dateString;
	var sepChar=dateSeperator;
	var curPos=0;
	var cDate,cMonth,cYear;

	//extract day portion
	curPos=dateString.indexOf(sepChar);
	cMonth=dateString.substring(0,curPos);
	
	//extract month portion				
	endPos=dateString.indexOf(sepChar,curPos+1);			
	cDate=dateString.substring(curPos+1,endPos);

	//extract year portion				
	curPos=endPos;
	endPos=curPos+5;			
	cYear=curValue.substring(curPos+1,endPos);
	
	//Create Date Object
	dtObject=new Date(cYear,cMonth,cDate);	
	return dtObject;
}
</script>
<form method="post" action="pages/sales/SiandOR_Report.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Report</span></td>
			<td class="topnav">
				<table width="98%" cellspacing="1" cellpadding="0" border="0" align="center">
					<tr>
						<td width="70%" align="right">
							<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				<table width="95%" border="0" align="center" cellpadding="0"
					cellspacing="1">
					<tr>
						<td class="txtgreenbold13">OR/SI RE-Print</td>
						<td>&nbsp;</td>
					</tr>
				</table>	
		</table>
		<br>
		<!--Begin form-->
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr><td>
		<!--Begin Search Tab-->
		
		<table width="70%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
			<tr>
				<td>
					<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
			  			
			  			<td width="10%" align="right">&nbsp;</td>
			  			<td width="20%" align="right">&nbsp;</td>
			  			<td width="40%" align="right">&nbsp;</td>
			  			<td width="30%"></td>
					</tr>
					<tr>
			  			
			  			<td width="15%" align="right" class="padr5"><strong>Type:</strong></td>
			  			<td width="20%" align="left">
							<select class="txtfield"  id = "type" name = "type">
								<option value = '0'>[SELECT : HERE]</option>
								<option value = '1'>SI - Sales Invoice</option>
								<option value = '2'>OR - Official Receipt</option>
							</select>
						</td>

						<td width="20%" align="left">&nbsp;&nbsp;&nbsp;<strong>Advance PO:</strong>&nbsp; <input type = "checkbox"  disabled = "disabled" name = "advance_po" id = "advance_po" value = "" />
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
			  			
			  			<td width="15%" align="right" class="padr5"><strong>Date: </strong> </td>
			  			<td width="20%" align="left"><input type = "text"  name = "date_from" id = "date_from" value = "" class="txtfield">
						</td>
						<td width="20%" align="left">&nbsp;&nbsp;&nbsp;<strong>To:</strong>&nbsp;&nbsp; <input type = "text"  name = "date_to" id = "date_to" value = "" class="txtfield">
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
			  			
			  			<td width="15%" align="right" class="padr5"><strong>SI Number:</strong></td>
			  			<td width="20%" align="left"><input type = "text"  id = "si_from"  name = "si_from" value = "" class="txtfield" disabled = "disabled">
						</td>
						<td width="20%" align="left">&nbsp;&nbsp;&nbsp;<strong>To:</strong>&nbsp;&nbsp; <input type = "text"  name = "si_to" id = "si_to"  value = "" class="txtfield" disabled = "disabled">
						</td>
			  			<td width="30%"></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
			  			
			  			<td width="15%" align="right" class="padr5"><strong>OR Number:</strong></td>
			  			<td width="20%" align="left"><input type = "text"  name = "or_from" id = "or_from"  value = "" class="txtfield" disabled = "disabled">
						</td>
						<td width="20%" align="left">&nbsp;&nbsp;&nbsp;<strong>To:</strong>&nbsp;&nbsp; <input type = "text"  name = "or_to" id = "or_to"  value = "" class="txtfield" disabled = "disabled">
						</td>
			  			<td width="30%"></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td width="15%" align="right" class="padr5"><strong>Dealer:</strong></td>
			  			<td width="20%" align="left"><input type = "text"  name = "dealer_code_from" id = "dealer_code_from" value = "" class="txtfield">
						</td>
						<td>&nbsp;&nbsp; <strong>To:</strong>&nbsp;&nbsp; 
						<input type = "text" id = "dealer_code_to" name = "dealer_code_to" value ="" class = "txtfield">
						</td>
						<td width="30%"></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td width="15%" align="right" class="padr5">&nbsp;</td>
						<td width="20%" align="left">&nbsp;<input type = "submit" id = "Generatelist"  onclick = "return generate_list();"value = "Generatelist" class = "btn"></td>
					</tr>
					<tr>
			  			<td width="10%" align="right">&nbsp;</td>
			  			<td width="20%" align="right">&nbsp;</td>
			  			<td width="40%" align="right">&nbsp;</td>
			  			<td width="30%"></td>
					</tr>					
					
					</table>
				</td>
			</tr>
		</table>			
		<!--End Search Tab-->	
		</td></tr>

		<tr><td>
		<!--Start Dealer List-->
		<br>
		<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td class="txtredbold">OR/SI RE-Print</td>
					<td>
						<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0" class="borderfullgreen" >
		<tr>
		<td class="tab">
			<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 " border="0">
			<tr align = 'center'>				
				<td width="3%"   class="padl5 bdiv_r" align="center" ><?php //<input type="checkbox" name="chkAll" id = "chkAll"><input type = "hidden" name = "total_numrows" id = "total_numrows"> ?></td>
				<td width="7%"   class="padl5 bdiv_r" align="left"><div id = "dynamiclabelSiSoNumber">SI Number</div></td>
				<td width="10%"  class="padl5 bdiv_r" align="left">Document Number</td>
				<td width="10%"  class="padl5 bdiv_r"align="left">Customer Name</td>	
				<td width="10%"  class="padl5 bdiv_r"align="left"><div id = "dynamiclabel_SiSoDate">SI Date</div></td>	
				<td width="10%"  class="padl5 bdiv_r"align="left">Status</td>	
			</tr>
			</table>
		</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_400" id="dynamic">				
					<!--Dealer Details-->
					<table width="100%" cellpadding="0" cellspacing="1"  border="0" id = "dynamic_table">
						<?php //show tables ?>		
					</table>
						
				</div>
			</td>
		</tr>	
		</table>
		</td></tr>

		<br>		
		</td>
	</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
<tr>
	<td><input type = "submit"  id = "print" value = "Print" disabled = "disabled" onclick="this.form.target='_blank';return true;"></td>
</tr>
</table>
</form>
<br />


