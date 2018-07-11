$ = jQuery.noConflict();
var urlpage = "pages/leaderlist/leaderlist_call_ajax/ProductInfo.php";

$(function(){

	$('[name=btnSearch]').click(function(){
		showPage(1);
	});
	
	$('[name=btnCancel]').click(function(){
		$('[name=ProductID]').val(0);
		$('[name=ProductInfoForm]')[0].reset();
	});
	
	$('[name=btnUpdate]').click(function(){
		
		if($('[name=ProductID]').val() == 0){
			return false;
		}
		
		$.ajax({
			type	:	"post",
			dataType:	"json",
			url		:	urlpage,
			data	:	$('[name=ProductInfoForm]').serialize() + "&action=SaveProductInfo",
			success	:	function(data){
				var btnfunction = {};
				
				btnfunction['Close'] = function(){
					$('.dialogmessage').dialog("close");
					location.reload();
				};
				
				popupHTML(data.ErrorMessage, btnfunction);
			}
		});
		
	});

});

function showPage(page){
	
	$('[name=page]').val(page);
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "ProductPagination", page : $('[name=page]').val(), search : $('[name=SearchProduct]').val()},
		success	:	function(data){
			$('.ProductList').html(data);
		}
	});
	
}

function GetProductDetails(ProductID){
	
	$.ajax({
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	{action : "GetProductDetails", ProductID : ProductID},
		success	:	function(data){
			$('[name=ProductID]').val(data.ProductID);
			$('[name=ItemCode]').val(data.ProductCode);
			$('[name=ItemName]').val(data.ProductName);
			$('[name=ShortName]').val(data.ShortName);
			$('[name=ItemClass]').val(data.ItemClass);
			$('[name=ProductType]').val(data.ProductType);
			$('[name=ProductLine]').val(data.ProductLine);
			$('[name=UOM]').val(data.UOM);
			$('[name=UnitCost]').val(data.UnitCost);
			$('[name=RegularPrice]').val(data.UnitPrice);
			$('[name=LaunchDate]').val(data.LaunchDate);
			$('[name=LastPODate]').val(data.LastPODate);
			$('[name=Brand]').val(data.ItemBrand);
			$('[name=SubBrand]').val(data.SubBrand);
			$('[name=ItemForm]').val(data.ItemForm);
			$('[name=SubForm]').val(data.SubForm);	
			$('[name=ItemStyle]').val(data.ItemStyle);
			$('[name=ItemColor]').val(data.ItemColor);
			$('[name=ItemSize]').val(data.ItemSize);
			$('[name=ProductLife]').val(data.ProductLife);
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
		draggable: false,
		title: "DSS Message",
		buttons : btnfunction
	});
	$('.dialogmessage').dialog("open");
	
}