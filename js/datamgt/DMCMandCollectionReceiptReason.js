$ = jQuery.noConflict();

var urlpage = "pages/datamanagement/system_param_call_ajax/DMCMandCollectionReceiptReason.php";

$(function(){
	//load reason code at the first loading of page
	ReasonCode();
	
	//view categories
	$('[name=categories]').change(function(){		
		if($(this).val() == 1){ //reason code
			ReasonCode();
		}else if($(this).val() == 2){
			GLAccount();
		}else if($(this).val() == 3){
			ReasonAndGLAccountTagging();
		}		
	});
	
	//reason code
	$('[name=btnAddReasonCode]').click(function(){
		AddReasonCode();
	});
	
	
	//gl account
	$('[name=btnAddGLAccount]').click(function(){
		AddGLAccount();
	});
		
	//reason code and gl account tagging
	$('[name=btnTagReasonAndGLAccount]').click(function(){
		AddReasonAndGLAccountTagging();
	});
	
});

function ValidateInput(field){
	
	regex = /^[a-zA-Z0-9\-]+$/;
	
	if(!regex.test($(field).val())){
		$(field).val( $(field).val().substring(0, $(field).val().length - 1) );
	}
	
}

//function for dynamic dialog box
function PopupHTML(message, btnfunction){
	$('.dialogmessage').html(message);
	$('.dialogmessage').dialog({
		autoOpen: false,
		modal: true,
		position: 'center',
		height: 'auto',
		width: 'auto',
		resizable: false,
		draggable: false,
		title : 'DMCM and Collection Receipt Reason',
		buttons : btnfunction
	});
	$('.dialogmessage').dialog("open");
}

function GetReasonCode(value){
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "GetReasonCode", CategoryID : value},
		success	:	function(data){
			$('[name=reasoncodeandglaccounttaggingform]').find('[name=ReasonCode]').html(data);
		}
	});
	
}

//==============================================================================
//reason code
function ReasonCode(){
	$('[name=btnAddReasonCode]').css('display', 'block');
	$('[name=btnAddGLAccount]').css('display', 'none');
	$('[name=btnTagReasonAndGLAccount]').css('display', 'none');
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "ReasonCodeTable"},
		success	:	function(data){
			$('.reasoncodetable').html(data);
		}
	});
	
	$('.reasoncode').css('display', 'block');
	$('.glaccount').css('display', 'none');
	$('.reasonandglaccounttagging').css('display', 'none');
	
}

function GLAccount(){
	
	$('[name=btnAddReasonCode]').css('display', 'none');
	$('[name=btnAddGLAccount]').css('display', 'block');
	$('[name=btnTagReasonAndGLAccount]').css('display', 'none');
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "GLAccountTable"},
		success	:	function(data){
			$('.glaccounttable').html(data);
		}
	});
	
	$('.reasoncode').css('display', 'none');
	$('.glaccount').css('display', 'block');
	$('.reasonandglaccounttagging').css('display', 'none');
	
}

function ReasonAndGLAccountTagging(){
	
	$('[name=btnAddReasonCode]').css('display', 'none');
	$('[name=btnAddGLAccount]').css('display', 'none');
	$('[name=btnTagReasonAndGLAccount]').css('display', 'block');
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "TaggingTable"},
		success	:	function(data){
			$('.reasonandglaccounttaggingtable').html(data);
		}
	});
	
	$('.reasoncode').css('display', 'none');
	$('.glaccount').css('display', 'none');
	$('.reasonandglaccounttagging').css('display', 'block');
	
}

function AddReasonCode(){
	
	var btnfunction = {};	
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "ReasonCodeForm"},
		success	:	function(data){
			
			btnfunction['Save'] = function(){
				SaveReasonCode();
			}
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
		
			PopupHTML(data, btnfunction);
		}
	});
	
}

function ViewReasonCode(ReasonID){
	
	var btnfunction = {};	
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "ReasonCodeForm", ReasonID : ReasonID},
		success	:	function(data){
			
			btnfunction['Save'] = function(){
				SaveReasonCode();
			}
			
			btnfunction['Remove'] = function(){
				return function(){
					DeleteReasonCode(ReasonID);
				}
			}(ReasonID);
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
		
			PopupHTML(data, btnfunction);
		}
	});
	
}

function SaveReasonCode(){
	
	$.ajax({
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	$('[name=reasoncodeform]').serialize() + "&action=SaveReasonCode",
		success	:	function(data){
			var btnfunction = {};
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
			
			PopupHTML(data.ErrorMessage, btnfunction);
			ReasonCode();
		}
	});
	
}

function DeleteReasonCode(ReasonID){
	
	$.ajax({
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	{action : "DeleteReasonCode", ReasonID : ReasonID},
		success	:	function(data){
			var btnfunction = {};
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
			
			PopupHTML(data.ErrorMessage, btnfunction);
			ReasonCode();
			
		}
	});
	
}


//======================================================
//gl account

function AddGLAccount(){
	
	var btnfunction = {};	
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "GLAccountForm"},
		success	:	function(data){
			
			btnfunction['Save'] = function(){
				SaveGLAccount();
			}
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
		
			PopupHTML(data, btnfunction);
		}
	});
	
}

function ViewGLAccount(GLAccountID){
	
	var btnfunction = {};	
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "GLAccountForm", GLAccountID : GLAccountID},
		success	:	function(data){
			
			btnfunction['Save'] = function(){
				SaveGLAccount();
			}
			
			btnfunction['Remove'] = function(){
				return function(){
					DeleteGLAccount(GLAccountID);
				}
			}(GLAccountID);
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
		
			PopupHTML(data, btnfunction);
		}
	});
	
}

function SaveGLAccount(){
	
	$.ajax({
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	$('[name=glaccountform]').serialize() + "&action=SaveGLAccount",
		success	:	function(data){
			var btnfunction = {};
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
			
			PopupHTML(data.ErrorMessage, btnfunction);
			GLAccount();
		}
	});
	
}

function DeleteGLAccount(GLAccountID){
	
	$.ajax({
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	{action : "DeleteGLAccouont", GLAccountID : GLAccountID},
		success	:	function(data){
			var btnfunction = {};
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
			
			PopupHTML(data.ErrorMessage, btnfunction);
			ReasonCode();
			
		}
	});
	
}

//======================================================
//reason code and gl account tagging

function AddReasonAndGLAccountTagging(){
	
	var btnfunction = {};	
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "ReasonCodeAndGLAccountTaggingForm"},
		success	:	function(data){
			
			btnfunction['Save'] = function(){
				SaveReasonCodeAndGLAccountTagging();
			}
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
		
			PopupHTML(data, btnfunction);
		}
	});
	
}

function ViewTagging(TaggingID){
	
	var btnfunction = {};	
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "ReasonCodeAndGLAccountTaggingForm", TaggingID : TaggingID},
		success	:	function(data){
			
			btnfunction['Save'] = function(){
				SaveReasonCodeAndGLAccountTagging();
			}
			
			btnfunction['Remove'] = function(){
				return function(){
					DeleteTagging(TaggingID);
				}
			}(TaggingID);
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
		
			PopupHTML(data, btnfunction);
		}
	});
	
}

function SaveReasonCodeAndGLAccountTagging(){
	
	$.ajax({
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	$('[name=reasoncodeandglaccounttaggingform]').serialize() + "&action=SaveReasonCodeAndGLAccountTagging",
		success	:	function(data){
			var btnfunction = {};
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
			
			PopupHTML(data.ErrorMessage, btnfunction);
			ReasonAndGLAccountTagging();
		}
	});
	
}

function DeleteTagging(TaggingID){
	
	$.ajax({
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	{action : "DeleteTagging", TaggingID : TaggingID},
		success	:	function(data){
			var btnfunction = {};
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
			
			PopupHTML(data.ErrorMessage, btnfunction);
			ReasonAndGLAccountTagging();
			
		}
	});
	
}
