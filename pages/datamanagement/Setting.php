<?php 
	$settingquery = $database->execute("SELECT * FROM headofficesetting");	
?>

<style>
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

.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
.ui-widget-overlay{height:130%;}

.trheader td{text-align:center; font-weight:bold; padding:5px; background:#ffdef0; border-right:1px solid #ffa3e0;}
.trlist td{padding:5px; border-top:1px solid #ffa3e0; border-right:1px solid #ffa3e0}

.fieldlabel{width:35%; font-weight:bold; text-align:right;}
.separator{width:5%; text-align:center; font-weight:bold;}

</style>

<script>
	$ = jQuery.noConflict();
	var urlpage = "pages/datamanagement/system_param_call_ajax/ajaxSetting.php";
	
	$(function(){
		
		$('[name=btnAddNewSetting]').click(function(){
			var btnfunction = {};
			var message = "";
			
			btnfunction['Save'] = function(){
				SaveNewSetting('AddNewSettingForm', 'SaveNewSetting');
			}
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
			
			message += "<div style='width:400px;'>" +
							"<form action='' method='' name='AddNewSettingForm'>" +
								"<table cellpacing='2' cellpadding='2' width='100%'>" +
									"<tr>" +
										"<td class='fieldlabel'>Setting Code</td>" +
										"<td class='separator'>:</td>" +
										"<td>" +
											"<input type='text' class='txtfield' value='' name='SettingCode'>" +
										"</td>" +
									"</tr>" +
									"<tr>" +
										"<td class='fieldlabel'>Setting Name</td>" +
										"<td class='separator'>:</td>" +
										"<td>" +
											"<input type='text' class='txtfield' value='' name='SettingName'>" +
										"</td>" +
									"</tr>" +
									"<tr>" +
										"<td class='fieldlabel'>Setting Value</td>" +
										"<td class='separator'>:</td>" +
										"<td>" +
											"<input type='text' class='txtfield' value='' name='SettingValue'>" +
										"</td>" +
									"</tr>" +
								"</table>" +
							"</form>" +
						"</div>";
			
			popupHTML(message, btnfunction);
		});
		
	});
	
	function RemoveSetting(field, SettingID){
		
		$.ajax({
			type	:	"post",
			dataType:	"json",
			url		:	urlpage,
			data	:	{action : "RemoveSetting", SettingID : SettingID},
			success	:	function(data){
				var btnfunction = {};
				
				btnfunction['Close'] = function(){
					$('.dialogmessage').dialog("close");
				}
				
				if(data.Success == 1){				
					$(field).closest('tr').remove();
					if($('.trlist').length == 0){
						$('.pageload table').append('<tr class="trlist"><td align="center" colspan="4">No result found.</td></tr>');
					}
				}
				
				popupHTML(data.ErrorMessage, btnfunction);
			}
		});
		
	}
	
	function EditSetting(SettingID){
		
		var btnfunction = {};
		var message = "";
		
		$.ajax({
			type	:	"post",
			dataType:	"json",
			url		:	urlpage,
			data	:	{action : "EditSettingForm", SettingID : SettingID},
			success	:	function(data){
				
				message += "<div style='width:400px;'>" +
						"<form action='' method='' name='EditSettingForm'>" +
							"<table cellpacing='2' cellpadding='2' width='100%'>" +
								"<tr>" +
									"<td class='fieldlabel'>Setting Code</td>" +
									"<td class='separator'>:</td>" +
									"<td>" +
										"<input type='text' class='txtfield' value='"+ data.SettingCode +"' name='SettingCode'>" +
										"<input type='hidden' name='SettingID' value='"+ SettingID +"'>" +
									"</td>" +
								"</tr>" +
								"<tr>" +
									"<td class='fieldlabel'>Setting Name</td>" +
									"<td class='separator'>:</td>" +
									"<td>" +
										"<input type='text' class='txtfield' value='"+data.SettingName+"' name='SettingName'>" +
									"</td>" +
								"</tr>" +
								"<tr>" +
									"<td class='fieldlabel'>Setting Value</td>" +
									"<td class='separator'>:</td>" +
									"<td>" +
										"<input type='text' class='txtfield' value='"+data.SettingValue+"' name='SettingValue'>" +
									"</td>" +
								"</tr>" +
							"</table>" +
						"</form>" +
					"</div>";
				
				btnfunction['Save'] = function(){
					SaveNewSetting('EditSettingForm', 'EditSetting');
				}
				
				btnfunction['Close'] = function(){
					$('.dialogmessage').dialog('close');
				}
				
				popupHTML(message, btnfunction);
				
			}
		});
		
	}
	
	function SaveNewSetting(FormName, Action){
		
		if($('[name=SettingCode]').val().trim() == ""){
			$('[name=SettingCode]').focus();
			$('[name=SettingCode]').css("border", "1px solid red");
			return false;
		}else{
			$('[name=SettingCode]').css("border", "1px solid #ffd6fb");
		}
		
		if($('[name=SettingName]').val().trim() == ""){
			$('[name=SettingName]').focus();
			$('[name=SettingName]').css("border", "1px solid red");
			return false;
		}else{
			$('[name=SettingName]').css("border", "1px solid #ffd6fb");
		}
		
		if($('[name=SettingValue]').val().trim() == ""){
			$('[name=SettingValue]').focus();
			$('[name=SettingValue]').css("border", "1px solid red");
			return false;
		}else{
			$('[name=SettingValue]').css("border", "1px solid #ffd6fb");
		}
		
		$.ajax({
			
			type	:	"post",
			dataType:	"json",
			url		:	urlpage,
			data	:	$('[name='+FormName+']').serialize() + "&action=" + Action,
			success	:	function(data){
				
				var btnfunction = {};
				
				if(data.Success == 1){					
					btnfunction['Close'] = function(){
						location.reload();
					}
				}else{
					btnfunction['Close'] = function(){
						$('.dialogmessage').dialog("close");
					}
				}
				
				popupHTML("<p>"+data.ErrorMessage+"</p>", btnfunction);
			}
		});
		
	}
	
	function popupHTML(message, btnfunction){
		$('.dialogmessage').html(message);
		$('.dialogmessage').dialog({
			autoOpen: false,
			modal: true,
			position: 'center',
			height: 'auto',
			width: 'auto',
			resizable: false,
			title: 'DSS Message',
			buttons:btnfunction
		});
		$('.dialogmessage').dialog("open");
	}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
			<?php include("nav.php");?>
			<br>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top" style="min-height: 610px; display: block;">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management</span></td>
				</tr>
			</table>
			<br />
			
			<div style="width:95%; margin:auto;">
			
				<table cellspacing="0" cellpadding="0" width="100%" border="0">
					<tr>
						<td>
							<span class="txtgreenbold13">Head Office Settings</span>
						</td>
					</tr>
				</table>
				
				<br />
				
				<div style="width:80%;">
				
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Action</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<div class="bordersolo pageload" style="border-top:none;">
						
						<table width="100%" cellpadding="0" cellspacing="0">
							
							<tr class="trheader">
								<td width="15%">Setting Code</td>
								<td>Setting Name</td>
								<td width="15%">Setting Value</td>
								<td width="15%">Action</td>
							</tr>
							<?php 
								if($settingquery->num_rows){
									
									while($res = $settingquery->fetch_object()){
										echo "<tr class='trlist'>
												<td align='center'>".$res->SettingCode."</td>
												<td>".$res->SettingName."</td>
												<td align='right'>".$res->SettingValue."</td>
												<td align='center'>
													<input type='button' value='Edit' class='btn' name='btnSetting' onclick='return EditSetting(".$res->ID.")'>
													<input type='button' value='Remove' class='btn' name='btnRemove' onclick='return RemoveSetting(this, ".$res->ID.")'>
												</td>
											</tr>";
									}
									
								}else{
									echo '<tr class="trlist">
											<td align="center" colspan="4">No result found.</td>
										</tr>';
								}
							?>							
							
						</table>
						
					</div>
					
					<br />
					
					<div style="text-align:center;">
						<input type="button" value="Add New Setting" class="btn" name="btnAddNewSetting">
						<input type="button" value="Back" class="btn" name="btnBack">
					</div>
					
				</div>
			
			</div>
			
		</td>
	</tr>
</table>

<div class="dialogmessage"></div>
