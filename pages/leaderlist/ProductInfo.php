<style>
	.mainwrapper{
		width : 98%;
		margin : auto;
		margin-top : 10px;
		min-width : 850px;
	}
	
	.leftside{
		min-width : 350px;
		margin-right : 10px;
		float : left;
		width : 44%;
	}
	
	.rightside{
		min-width : 450px;
		width : 53%;
		float : left;
	}
	
	.fieldlabel{
		width : 30%;
		text-align : right;
		font-weight : bold;
	}
	
	.separator{
		width : 5%;
		text-align : center;
		font-weight : bold;
	}
	
	.trheader td{
		text-align : center;
		font-weight : bold;
		padding : 5px;
		border-right : 1px solid #ffa3e0;
		background : #ffdef0;
	}
	
	.trlist td{
		padding : 5px;
		border-right : 1px solid #ffa3e0;
		border-top : 1px solid #ffa3e0;
	}
	
	#masterdiv{width:200px;}
</style>

<?php 
	include IN_PATH."ProductInfo.php";
	include IN_PATH."pagination.php";
?>

<script src="js/ProductInfo.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
			<?php include("nav.php");?>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top" style="min-height: 610px; display: block;">
			
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Promo and Pricing Management System (PPMS)</span></td>
				</tr>
			</table>
			
			<div class="mainwrapper">
				
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Update Product Info</td>
					</tr>
				</table>
				
				<br />
				
				<div class="leftside">
				
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Action</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td class="fieldlabel">Search</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" class="txtfield" name="SearchProduct">
								<input type="button" class="btn" value="Search" name="btnSearch">
								<input type="hidden" value="1" name="page">
							</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
					</table>
					
					<br />
					
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Product List</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<div class="ProductList">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bordersolo" style="border-top:none;">
							<tr class="trheader">
								<td>Item Code</td>
								<td>item Name</td>
							</tr>
							<?php 
								$productlist = ProductList('', 1, 10, false);
								$productlistTotal = ProductList('', 1, 10, true);
								
								if($productlist->num_rows){
									while($res = $productlist->fetch_object()){
										echo "<tr class='trlist'>
												<td align='center'>
													<a onclick='return GetProductDetails(".$res->ID.")' href='javascript:void(0);' style='color:blue;'>".$res->Code."</a>
												</td>
												<td>".$res->Name."</td>
											</tr>";
									}
								}else{
							?>
							<tr class="trlist">
								<td colspan="3" align="center">No result found.</td>
							</tr>
							<?php }?>
						</table>
						
						<?php 
							if($productlistTotal->num_rows){
								echo "<div style='margin-top:10px;'>".AddPagination(10, $productlistTotal->num_rows, 1)."</div>";
							}
						?>
						
					</div>
				</div>
				
				<div class="rightside">
					<form action="" method="post" name="ProductInfoForm">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="tabmin"></td>
							<td class="tabmin2">Product Information</td>
							<td class="tabmin3"></td>
						</tr>
					</table>
					
					<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="bordersolo" style="border-top:none;">
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td class="fieldlabel">Item Code</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ItemCode" class="txtfield" disabled="disabled">
								<input type="hidden" value="0" name="ProductID">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Item Name</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ItemName" class="txtfield" style="width:250px;" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Short Name</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ShortName" class="txtfield" style="width:250px;">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Class</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ItemClass" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Product Type</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ProductType" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Product Line</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ProductLine" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Brand</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="Brand" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Sub-Brand</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="SubBrand" class="txtfield">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Form</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ItemForm" class="txtfield" disabled="disabled">
							</td>
						</tr>						
						<tr>
							<td class="fieldlabel">Sub-Form</td>
							<td class="separator">:</td>
							<td>
								<select name="SubForm" class="txtfield">
									<option value="0">Select</option>
									<?php 
										if($subformquery->num_rows){
											while($res = $subformquery->fetch_object()){
												echo "<option value=".$res->ID.">".$res->Name."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Style</td>
							<td class="separator">:</td>
							<td>
								<select name="ItemStyle" class="txtfield">
									<option value='0'>Select</option>
									<?php 
										if($stylequery->num_rows){
											while($res = $stylequery->fetch_object()){
												echo "<option value=".$res->ID.">".$res->Name."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Color</td>
							<td class="separator">:</td>
							<td>
								<select name="ItemColor" class="txtfield">
									<option value="0">Select</option>
									<?php 
										if($colorquery->num_rows){
											while($res = $colorquery->fetch_object()){
												echo "<option value=".$res->ID.">".$res->Name."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Size</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ItemSize" class="txtfield">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Product Life</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="ProductLife" class="txtfield">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">UOM</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="UOM" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Regular Price</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="RegularPrice" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Unit Cost</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="UnitCost" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Launch Date</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="LaunchDate" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Last PO Date</td>
							<td class="separator">:</td>
							<td>
								<input type="text" value="" name="LastPODate" class="txtfield" disabled="disabled">
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">Product Image</td>
							<td class="separator">:</td>
							<td>
								<input type="file" value="" name="ProductImage" class="txtfield" style="height:20px;">
							</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
					</table>
					
					<div style="margin-top:10px; text-align:center;">
						<input type="button" value="Update" class="btn" name="btnUpdate">
						<input type="button" value="Cancel" class="btn" name="btnCancel">
					</div>
					</form>
				</div>
				
			</div>
			
		</td>
	</tr>
</table>
<br />

<div class="dialogmessage"></div>
