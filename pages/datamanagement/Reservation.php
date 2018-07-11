<style>
	.fieldlabel{text-align:right; font-weight:bold; width:20%;}
	.separator{font-weight:bold; text-align:center; width:5%;}
	.trheader td{font-weight:bold; text-align:center; border-right: 1px solid #ffa3e0; background:#ffdef0; padding:5px;}
	.trlist td{border-right: 1px solid #ffa3e0; border-top: 1px solid #ffa3e0; padding:5px;}
	.ui-widget-overlay{height:200%;}
</style>

<script>
	$ = jQuery.noConflict();
	$(function(){
		
		showPage(1);
		
		$('[name=DateStart], [name=DateEnd]').datepicker({
			changeMonth	:	true,
			changeYear	:	true
		});
		
		$('[name=btnSearch]').click(function(){
			showPage(1);
		});
		
		GetBranch('form[name=ReservationForm] input[name=Branch]', 'form[name=ReservationForm] input[name=BranchID]');
		
		$('[name=btnCreate]').click(function(){
			
			$.ajax({
				type	:	'post',
				url		:	'pages/datamanagement/system_param_call_ajax/Reservation.php',
				data	:	{action : 'CreateReservation'},
				success	:	function(data){
					
					var btnFunction = {};
					
					btnFunction['Save'] = function(){
						SaveReservation();
					}
					
					btnFunction['Close'] = function(){
						$('.dialogmessage').dialog('close');
					}
					
					CreateReservation(btnFunction, data);
					
				}
			});
			
		});
		
	});
	
	function GetBranch(field, fieldID){
		$(field).autocomplete({
			source	:	function(request, response){
				$.ajax({
					type	:	"post",
					dataType:	"json",
					url		:	"pages/datamanagement/system_param_call_ajax/Reservation.php",
					data	:	{action : "GetBranch", Search : request.term},
					success	:	function(data){
						response($.map(data, function(item){
							return{
								label	:	item.Label,
								value	:	item.Value,
								ID		:	item.ID
							}
						}));
					}
				});
			},
			select	:	function(event, ui){
				$(fieldID).val(ui.item.ID);
			}
		});
	}
	
	function showPage(page){
		$('[name=page]').val(page);
		$.ajax({
			type	:	'post',
			url		:	'pages/datamanagement/system_param_call_ajax/Reservation.php',
			data	:	$('[name=ReservationForm]').serialize() + '&action=Reservation',
			success	:	function(data){
				$('.loader').html('&nbsp;');
				$('.pageloader').html(data);
			},
			beforeSend:	function(){
				$('.loader').html('Loading... Please wait...');
			}
		});
	}
	
	function CreateReservation(btnFunction, htmlmessage){
		
		$('.dialogmessage p').html(htmlmessage);
		$('.dialogmessage').dialog({
			autoOpen: false,
			modal: true,
			position: ['top', 50],
			height: 'auto',
			width: '70%',
			resizable: false,
			draggable: false,
			open: function(event, ui){
				$('body').css('overflow','hidden');
				$('.ui-widget-overlay').css('width','100%');
				$('.ui-dialog .ui-dialog-titlebar-close').css('display', 'none');
			},
			close: function(event, ui){
				$('body').css('overflow','auto');
				$('.ui-dialog .ui-dialog-titlebar-close').css('display', 'block');
			},
			title: 'Create Reservation',
			buttons: btnFunction
		});
		$('.dialogmessage').dialog('open');
		
	}
	
	function SearchProduct(field){
		
		var exist = 0;
		var productlist = new Array();
		
		if($('.productfield').find('.trlist').length > 1){
			$('.productfield').find('.trlist').each(function(index){
				if($(this).find('td:nth-child(1) input').attr('name') != $(field).attr('name')){
					productlist.push($(this).find('td:nth-child(1) input').val().trim());
				}
			});
		}
		
		$(field).autocomplete({
			source	:	function(request, response){
				$.ajax({
					type	:	"post",
					dataType:	"json",
					url		:	"pages/datamanagement/system_param_call_ajax/Reservation.php",
					data	:	{action : "GetProduct", Search : request.term},
					success	:	function(data){
						response($.map(data, function(item){
							return{
								label	:	item.Label,
								value	:	item.Value,
								Name	:	item.ProductName
							}
						}));
					}	
				});
			},
			select	:	function(event, ui){
				
				if(productlist.indexOf(ui.item.value.trim()) > -1){
					exist++;
				}
				
				if(exist > 0){
					alert("Product already added. Please select other product.");
					$(field).val('');
					$(field).focus();
					return false;
				}
				
				$(field).attr('readonly', 'readonly');
				$(field).closest('.trlist').find('td:nth-child(2)').html(ui.item.Name);
				$(field).closest('.trlist').find('td:nth-child(3) input').removeAttr('disabled');
				$(field).closest('.trlist').find('td:nth-child(3) input').focus();
				$(field).closest('.trlist').find('td:nth-child(3) input').select();
				
			}
		});
		
	}
	
	function AddNewRow(e, field){
		
		var index = $('.productfield').find('.trlist').length;
		
		if(e.which == 13 || e.which == 9){
			
			if($(field).val() == 0 || isNaN($(field).val())){
				alert("Please insert Requested Quantity.");
				$(field).val(0);
				$(field).focus();
				$(field).select();
				return false;
			}
			
			if($('[name=ProductCode'+index+']').attr('readonly') != "readonly" || $('[name=Quantity'+index+']').attr('disabled') == 'disabled'){
				$('[name=ProductCode'+index+']').focus();
				return false;
			}
			
			if($('[name=Quantity'+index+']').val() == 0 || isNaN($('[name=Quantity'+index+']').val())){
				$('[name=Quantity'+index+']').val(0);
				$('[name=Quantity'+index+']').focus();
				$('[name=Quantity'+index+']').select();
				return false;
			}
			
			htmldetails = '<tr class="trlist">'+
							'<td align="center">'+
								'<input type="text" name="ProductCode'+(index + 1)+'" value="" class="txtfield" style="width:95%;" onkeydown="return SearchProduct(this);">'+
							'</td>'+
							'<td></td>'+
							'<td align="center">'+
								'<input type="text" name="Quantity'+(index + 1)+'" value="0" class="txtfield" style="width:95%; text-align:right;" onkeydown="return AddNewRow(event, this);" disabled="disabled">'+
							'</td>'+
							'<td align="center">'+
								'<input type="button" name="btnRemove'+(index + 1)+'" value="Remove" class="btn" style="width:100%;" onclick="return RemoveReservation(this);">'+
							'</td>'+
						'</tr>';
						
			$('.productfield').append(htmldetails);
			$('[name=ProductCode'+(index + 1)+']').focus();
		}
		
	}
	
	function RemoveReservation(field){
		
		if($('.productfield').find('.trlist').length > 1){
			$(field).closest('.trlist').remove();
			$('.productfield').find('.trlist').each(function(index){
				var x = (index + 1);
				$(this).find('td:nth-child(1) input').attr('name', 'ProductCode' + x);
				$(this).find('td:nth-child(3) input').attr('name', 'Quantity' + x);
				$(this).find('td:nth-child(4) input').attr('name', 'btnRemove' + x);
			});
				
		}else{
			$(field).closest('.trlist').remove();
			htmldetails = '<tr class="trlist">'+
							'<td align="center">'+
								'<input type="text" name="ProductCode1" value="" class="txtfield" style="width:95%;" onkeydown="return SearchProduct(this);">'+
							'</td>'+
							'<td></td>'+
							'<td align="center">'+
								'<input type="text" name="Quantity1" value="0" class="txtfield" style="width:95%; text-align:right;" onkeydown="return AddNewRow(event, this);" disabled="disabled">'+
							'</td>'+
							'<td align="center">'+
								'<input type="button" name="btnRemove1" value="Remove" class="btn" style="width:100%;" onclick="return RemoveReservation(this);">'+
							'</td>'+
						'</tr>';
			$('.productfield').append(htmldetails);
			$('[name=ProductCode1]').focus();
		}
		
	}
	
	function SaveReservation(){
		
		if($('form[name=CreateReservationForm] input[name=BranchID]').val() == 0){
			alert("Please select from Branch.");
			$('form[name=CreateReservationForm] input[name=BranchID]').focus();
			return false;
		}
		
		if($('form[name=CreateReservationForm] input[name=ReservationNo]').val().trim() == ''){
			alert("Please insert Reservation No.");
			$('form[name=CreateReservationForm] input[name=ReservationNo]').focus();
			return false;
		}
		
		if($('[name=ProductCode1]').attr('readonly') != 'readonly'){
			alert("Please select product.");
			$('[name=ProductCode1]').focus();
			return false;
		}
		
		if($('[name=Quantity1]').val() == 0 || isNaN($('[name=Quantity1]').val())){
			alert("Please insert valid Requested Quantity.");
			$('[name=Quantity1]').focus();
			return false;
		}
		
		$.ajax({
			type	:	"post",
			dataType:	"json",
			url		:	"pages/datamanagement/system_param_call_ajax/Reservation.php",
			data	:	$('form[name=CreateReservationForm]').serialize() + "&action=SaveReservation&TotalRow=" + $('form[name=CreateReservationForm] .trlist').length,
			success	:	function(data){
				
				alert(data.ErrorMessage);
			
				if(data.Success == 1){
					showPage(1);
					$('.dialogmessage').dialog('close');
				}
			}
		});
		
	}
	
	function UpdateReservation(ReservationNo){
		
		$.ajax({
			type	:	"post",
			url		:	"pages/datamanagement/system_param_call_ajax/Reservation.php",
			data	:	{action : "GetReservation", ReservationNo : ReservationNo},
			success	:	function(data){
				
				var btnfunction = {};
				btnfunction['Save'] = function(){
					UpdateExistingReservation();
				};
				
				btnfunction['Close'] = function(){
					$('.dialogmessage').dialog('close');
				}
				
				CreateReservation(btnfunction, data);
			}
		});
		
	}
	
	function UpdateExistingReservation(){
		
		if($('form[name=CreateReservationForm] input[name=BranchID]').val() == 0){
			alert("Please select from Branch.");
			$('form[name=CreateReservationForm] input[name=BranchID]').focus();
			return false;
		}
		
		if($('form[name=CreateReservationForm] input[name=ReservationNo]').val().trim() == ''){
			alert("Please insert Reservation No.");
			$('form[name=CreateReservationForm] input[name=ReservationNo]').focus();
			return false;
		}
		
		if($('[name=ProductCode1]').attr('readonly') != 'readonly'){
			alert("Please select product.");
			$('[name=ProductCode1]').focus();
			return false;
		}
		
		if($('[name=Quantity1]').val() == 0 || isNaN($('[name=Quantity1]').val())){
			alert("Please insert valid Requested Quantity.");
			$('[name=Quantity1]').focus();
			return false;
		}
		
		$.ajax({
			type	:	"post",
			dataType:	"json",
			url		:	"pages/datamanagement/system_param_call_ajax/Reservation.php",
			data	:	$('form[name=CreateReservationForm]').serialize() + "&action=UpdateReservation&TotalRow=" + $('form[name=CreateReservationForm] .trlist').length,
			success	:	function(data){
				
				alert(data.ErrorMessage);
			
				if(data.Success == 1){
					showPage(1);
					$('.dialogmessage').dialog('close');
				}
			}
		});
		
	}
	
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
			<?php
				include("nav.php");
			?>
			<br>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top" style="display:block; min-height:500px;">
			
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management</span></td>
				</tr>
			</table>
			
			<br />
			
			<div style="width:98%; margin:auto;">
				
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Reservation</td>
					</tr>
				</table>
				<br />
				
				<div style="width:50%;">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Action(s)</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<div style="padding:10px; border-top:none;" class="bordersolo">
						<form action="" name="ReservationForm" method="post">
							<table cellpadding="1" cellspacing="1" width="100%">
								<tr>
									<td class="fieldlabel">Branch</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="Branch" value="" class="txtfield">
										<input type="hidden" name="BranchID" value="0">
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Date Range</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="DateStart" value="<?=date("m/d/Y")?>" class="txtfield">
										-
										<input type="text" name="DateEnd" value="<?=date("m/d/Y")?>" class="txtfield">
									</td>
								</tr>
								<tr>
									<td class="fieldlabel">Search</td>
									<td class="separator">:</td>
									<td>
										<input type="text" name="Search" value="" class="txtfield">
									</td>
								</tr>
								<tr>
									<td colspan="3" align="center" style="padding:10px;">
										<input type="button" class="btn" name="btnSearch" value="Search">
										<input type="hidden" name="page" value="1">
									</td>
								</tr>
							</table>
						</form>
					</div>
					
				</div>
				
				<div class="loader" style="padding:10px; font-weight:bold; text-align:center;">&nbsp;</div>
				
				<div>
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Result(s)</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					<div class="pageloader">
						<table width="100%" cellpadding="0" cellspacing="0" style="border-top:none;" class="bordersolo">
							<tr class="trheader">
								<td>Branch</td>
								<td>Reservation No</td>
								<td>Start Date</td>
								<td>End Date</td>
								<td>Transaction Date</td>
								<!--<td>Status</td>-->
							</tr>
							<tr class="trlist">
								<td align="center" colspan="6">No result found.</td>
							</tr>
						</table>
					</div>
				</div>
				
				<div style="text-align:center; padding:10px;">
					<input type="button" class="btn" name="btnCreate" value="Create Reservation">
				</div>
				
			</div>
			
			<br />
			
		</td>
	</tr>
</table>
<div style="display:none;" class="dialogmessage"><p></p></div>