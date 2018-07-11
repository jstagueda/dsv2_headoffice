<?php
	include IN_PATH.DS."scProduct.php";
	include IN_PATH.DS."pcProduct.php";
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script src="js/jxPagingListProduct.js" language="javascript" type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<link type="text/css" href="css/jquery-ui-1.8.5.custom.css" rel="stylesheet"/>
<style type="text/css">
    .jsAction {
        cursor: pointer;
        color: #00f;
        text-decoration: underline;
    }
    </style>
<!-- Codes by Quackit.com -->
<script type="text/javascript">
// Popup window code

function newPopup(name)
{
  	pagetoprint = "pages/datamanagement/datamgt_imagepopup.php?fname=" +name;
	NewWindow(pagetoprint,'printps2','500','650','yes');

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
</script>

<SCRIPT language=Javascript>
function trim(s)
{
	  var l=0; var r=s.length -1;
	  while(l < s.length && s[l] == ' ')
	  {	l++; }
	  while(r > l && s[r] == ' ')
	  {	r-=1;	}
	  return s.substring(l, r+1);
}

function form_validation()
{

	msg = '';
	str = '';
	obj = document.frmProduct.elements;
	// TEXT BOXES
	if (trim(obj["txtCode"].value) == '')msg += '   * Product Code \n';
	if (trim(obj["txtName"].value) == '')msg += '   * Product Name \n';
	if (trim(obj["txtShrtName"].value) == '')msg += '   * Short Name\n';
	if (trim(obj["pProdCls"].value) == 0)msg += '   * Product Class \n';
	/*
	//document.getElementsByName("acc")[0].value
	//if (trim(document.getElementsbyName("pProdType")[0].value) == '')msg += '   * Product Type \n';
	//if (trim(obj["pProdLine"].value) == 0)msg += '   * Product Line \n';
	//if (trim(obj["pBrand"].value) == 0)msg += '   * Brand \n';
	//if (trim(obj["pForm"].value) == 0)msg += '   * Form \n';
	*/
	if (trim(obj["pStyle"].value) == 0)msg += '   * Style \n';
	if (trim(obj["pSubForm"].value) == 0)msg += '   * Sub-form \n';
	if (trim(obj["pColor"].value) == 0)msg += '   * Color \n';
	if (trim(obj["txtPSize"].value) == '')msg += '   * Size \n';
	if (trim(obj["txtPLife"].value) == '')msg += '   * Product Life \n';




	if (msg != '')
	{
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else return true;
}

function xautocomplete(){

	jQuery( "#txtSearch" ).autocomplete({
	 source:'includes/ajaxproductLL.php',
		select: function( event, ui ) {
			jQuery( "#txtSearch" ).val( ui.item.ProductCode);

			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return jQuery( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.ProductCode + " - " + item.ProductName+ "</strong></a>" )
			.appendTo( ul );
	};
}

</SCRIPT>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
		<td width="200" valign="top" class="bgF4F4F6">
			<?PHP
				include("nav.php");
			?>
		<br>
		</td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Leader List</span></td>
        </tr>
    </table>
    <br />
	
	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtgreenbold13">Product </td>
		</tr>
	</table>
	<br />
	<table width="98%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top">
			
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="49%" valign="top">
	<form name="frmSearchProdType" method="post" action="index.php?pageid=20">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><span class="txtredbold">Action</span></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
		  <tr>
		    <td>
			<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
				  <td width="25%">&nbsp;</td>
				  <td width="5%">&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
				<tr>
					<td align="right"><strong>Search</strong></td>
					<td align="center">:</td>
					<td style="padding:3px;">
						<input name="searchtxtfld" type="text" class="txtfield" id="txtSearch" autocomplete="off" onkeypress = "xautocomplete();" size="20" value="<?php echo $prodSearchTxt; ?>">
						<input name="btnSearch" type="submit" class="btn" value="Search" />
					</td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				  <td align="right">&nbsp;</td>
				  <td align="right">&nbsp;</td>
				</tr>
			</table>
            </td>
		  </tr>
		</table>
		<!--paging-->
		      <br>
		        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><span class="txtredbold">Product</span></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
        <tr>
          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top" class="">
					<div id="pgContent" class="bordergreen">
						<div style='text-align:center; padding:10px;'>Loading... Please wait..</div>
                    </div>
                  </td>
              </tr>
          </table></td>
        </tr>
      </table>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="3" class="bgE6E8D9"></td>
        </tr>
      </table>
      <br>
      <table width="98%"  border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td height="20" class="txtblueboldlink" width="50%" valign="top">
				<div id="pgNavigation">
					<!--<b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif">-->
				</div>
            </td>
            <!--<td height="20" class="txtblueboldlink" width="50%" valign="top">
				<div id="pgRecord" align="right"><b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif"></div>
            </td>-->
        </tr>
    </table>
      <br />
      <?php

      $vSearch = '';
      if(isset($_POST['searchTxtFld']))
      {
      	 $vSearch =  $_POST['searchTxtFld'];
      }

        $pID = -1;
      if(isset($_GET['pID']))
      {
      	 $pID =  $_GET['pID'];
      }


      ?>
    <script>
		//Onload start the user off on page one

		window.onload = showPage("1", "<?php echo $pID; ?>", "<?php echo $prodSearchTxt; ?>");
    </script>

</form>
    </td>
  </tr>
</table>
 <!--paging-->
		      </td>
	<!--<td width="1%">&nbsp;</td>-->
    <!--FORM2-->
	<td width="50%" valign="top">
        <form name="frmProduct" method="post" action="index.php?pageid=20"  enctype="multipart/form-data" >
            
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Product Information</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
       <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
        <tr>
           <td class="bgF9F8F7">&nbsp;&nbsp;
           	<?php
				if (isset($_GET['msg']))
				{
					$message = strtolower($_GET['msg']);
					$success = strpos("$message","success");
					echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
	    		}
	    		else if(isset($_GET['errmsg']))
	    		{
	    			$errormessage = strtolower($_GET['errmsg']);
					$error = strpos("$errormessage","error");
					echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
	    		}
			?>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
              <tr>
                <td width="40%">&nbsp;</td>
                <td width="60%">&nbsp;&nbsp;
                	<?php
						if ($pID > 0)
						{
						  echo "<input type=\"hidden\" name=\"hdnProductID\" value=\"$pID\" />";
						  echo "<input type=\"hidden\" name=\"hdnParent\" value=\"$pParent\" />";
						}
					 ?>
                </td>
			 </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Item Code&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
					<input name="txtCode" type="text" class="txtfieldg" size="50" maxlength="50" style = 'background-color:#e3e3e3;' value="<?php echo $pCode; ?>" readonly="yes" /></td>
                </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Item Name&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
					<input name="txtName" type="text" class="txtfieldg" size="50" maxlength="100" style = 'background-color:#e3e3e3;' value="<?php echo $pName; ?>" readonly="yes"/></td>
                </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Short Name &nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
					<input name="txtShrtName" type="text" class="txtfieldg" size="15" maxlength="50" value="<?php echo $pShrtName; ?>" /></td>
              </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Class&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
						<?PHP
							if($pProdClsID != ""){
								$PMG = $database->execute("SELECT * FROM tpi_pmg where ID = ".$pProdClsID);
							}else{
								$PMG = $database->execute("SELECT * FROM tpi_pmg where ID = 0");
							}
							if($PMG->num_rows){
									while($fetch_object = $PMG->fetch_object()){
										$PMG_NAME = $fetch_object->Name;
									}
							}else{
								$PMG_NAME = "";
							}
							echo "<input name = 'pProdCls' type = 'text' value = '$PMG_NAME' readonly = 'readonly' style = 'background-color:#e3e3e3;' class = 'txtfieldg'>";

                        ?>
                 </td>
              </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Product Type&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;

						<?PHP
								if($pProdTypeID != "" ){
									$Product_Type_Result = $database->execute("SELECT ID, Name FROM producttype where ID = ".$pProdTypeID);
								}else{
									$Product_Type_Result = $database->execute("SELECT ID, Name FROM producttype where ID = 0");
								}
								if($Product_Type_Result->num_rows){
									while($row = $Product_Type_Result->fetch_object()){
										$PT_NAME = $row->Name;
									}
								}else{
										$PT_NAME = "";
								}
								echo "<input name='pProdType' type = 'text' value = '$PT_NAME' readonly = 'readonly' style = 'background-color:#e3e3e3;' class = 'txtfieldg'>";
                        ?>
                    </td>
              </tr>
               <tr>
                <td height="20" align="right" class="txtpallete">Product Line&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
						<?PHP
						if($pProdLineID != ""){
								$q	= $database->execute("SELECT ID, Code, Name FROM product WHERE ProductLevelID =  2 and ID = ".$pProdLineID);
							}else{
								$q	= $database->execute("SELECT ID, Code, Name FROM product WHERE ProductLevelID =  2 and ID = 0");
							}
							if($q->num_rows){

								while($row = $q->fetch_object()){
									$Prod_Name = $row->Name;
								}
							}else{
									$Prod_Name = "";
							}
							echo "<input type = 'text' name='pProdLine' value = '$Prod_Name' readonly = 'readonly' style = 'background-color:#e3e3e3;' class = 'txtfieldg'>";
                        ?>
                    </td>
              </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Brand&nbsp; :</td>
                <td height="20">&nbsp;&nbsp;
                   		<?PHP
						if($pBrandID != ""){
							$q = $database->execute("SELECT * FROM value WHERE FieldID = 8 and ID = ".$pBrandID);
						}else{
							$q = $database->execute("SELECT * FROM value WHERE FieldID = 8 and ID = 0");
						}
						if($q->num_rows){
							while($row  = $q->fetch_object()){
								$Brand_name = $row->Name;
							}
						}else{
							$Brand_name = "";
						}
						echo "<input type = 'text' value = '$Brand_name' name='pBrand' readonly = 'readonly' style = 'background-color:#e3e3e3;' class = 'txtfieldg'>";
                        ?>
                 </td>
              </tr>
               <tr>
                <td height="20" align="right" class="txtpallete">Form&nbsp; :</td>
                <td height="20">&nbsp;&nbsp;
                   		<?PHP
							if($pFormID != ""){
								$q = $database->execute("SELECT * FROM value WHERE FieldID = 9 and ID = ".$pFormID);
							}else{
								$q = $database->execute("SELECT * FROM value WHERE FieldID = 9 and ID = 0");
							}

							if($q->num_rows){
								while($row = $q->fetch_object()){
									$FORM_NAME = $row->Name;
								}
							}else{
									$FORM_NAME = "";
							}
							 echo "<input type = 'text' name='pForm' value = '$FORM_NAME' readonly = 'readonly' style = 'background-color:#e3e3e3;' class = 'txtfieldg'>";


                        ?>
                    </td>
             	 </tr>
             	<tr>
                <td height="20" align="right" class="txtpallete">Style&nbsp; :</td>
                <td height="20">&nbsp;&nbsp;
                	<select name="pStyle" class="txtfield" style="width:200px">
                   		<?PHP
						 echo "<option value=\"0\" >[N/A]</option>";
                            if ($rs_cboStyle->num_rows)
                            {
                                while ($row = $rs_cboStyle->fetch_object())
                                {
                                ($pStyleID == $row->ID) ? $sel = "selected" : $sel = "";
                                echo "<option value='$row->ID' $sel>$row->Name</option>";
                                }
                            }
                        ?>
                    </select>
                    </td>
             	 </tr>
             	<tr>
                <td height="20" align="right" class="txtpallete">Sub-form&nbsp; :</td>
                <td height="20">&nbsp;&nbsp;
                	<select name="pSubForm" class="txtfield" style="width:200px">
                   		<?PHP
						 echo "<option value=\"0\" >[N/A]</option>";
                            if ($rs_cboSubForm->num_rows)
                            {
                                while ($row = $rs_cboSubForm->fetch_object())
                                {
                                ($pSubFormID == $row->ID) ? $sel = "selected" : $sel = "";
                                echo "<option value='$row->ID' $sel>$row->Name</option>";
                                }
                            }
                        ?>
                    </select>
                    </td>
             	 </tr>
             	 <tr>
                <td height="20" align="right" class="txtpallete">Color&nbsp; :</td>
                <td height="20">&nbsp;&nbsp;
                	<select name="pColor" class="txtfield" style="width:200px">
                   		<?PHP
						 echo "<option value=\"0\" >[N/A]</option>";
                            if ($rs_cboColor->num_rows)
                            {
                                while ($row = $rs_cboColor->fetch_object())
                                {
                                ($pColorID == $row->ID) ? $sel = "selected" : $sel = "";
                                echo "<option value='$row->ID' $sel>$row->Name</option>";
                                }
                            }
                        ?>
                    </select>
                    </td>
             	 </tr>
             	 <tr>
                <td height="20" align="right" class="txtpallete">Size&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
                <input name="txtPSize" type="text" class="txtfieldg" size="10" maxlength="30" value="<?php echo $pSize; ?>" /></td>
              </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Product Life&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
                <input name="txtPLife" type="text" class="txtfieldg" size="10" maxlength="30" value="<?php echo $pLife; ?>" /></td>
              </tr>
				<tr>
                <td height="20" align="right" class="txtpallete">UOM&nbsp; :</td>
                <td height="20">&nbsp;&nbsp;
					<input name="txtPLife" name="pUOM" type="text" class="txtfieldg" size="10" maxlength="30" value="PIECE" style = "background-color:#e3e3e3;"readonly = "readonly" />
                </td>
              </tr>
			  <tr>
                <td height="20" align="right" class="txtpallete">Regular Price&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
                <input name="txtUnitPrice" type="text" class="txtfieldg" size="10" readonly="yes" maxlength="30" style = 'background-color:#e3e3e3;' value="<?php echo number_format($UnitPrice,2,".",""); ?>" onkeyup="return RemoveInvalidLetters3(txtUCost)" /></td>
              </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Unit Cost&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
                <input name="txtUCost" type="text" class="txtfieldg" size="10" readonly="yes" maxlength="30" style = 'background-color:#e3e3e3;' value="<?php echo number_format($pUCost,4,".",""); ?>" onkeyup="return RemoveInvalidLetters3(txtUCost)" /></td>
              </tr>

              <tr>
	                <td height="20" align="right" class="txtpallete">Launch Date&nbsp; : </td>
	                <td height="20">&nbsp;&nbsp;
	                <input name="txtLaunchDate" type="text" class="txtfieldg" size="10" readonly="yes" style = 'background-color:#e3e3e3;' maxlength="30" value="<?php echo $launchdate; ?>" /></td>
          	  </tr>
          	  <tr>
	                <td height="20" align="right" class="txtpallete">Last PO Date&nbsp; : </td>
	                <td height="20">&nbsp;&nbsp;
	                <input name="txtLastPODate" type="text" class="txtfieldg" size="10" readonly="yes" style = 'background-color:#e3e3e3;' maxlength="30" value="<?php echo $lastpodate; ?>" /></td>
          	  </tr>
              <tr>
                <td height="20" align="right" class="txtpallete">Product Image&nbsp; : </td>
                <td height="20">&nbsp;&nbsp;
				<?php if($productImage != "") { ?>
					<span class="jsAction" onclick="newPopup('<?php echo $productImage; ?>');"><?php echo $productImage; ?></span> <?php  }?>
					<input type="file" size="20" name="imgProduct" id="imgProduct" style="width:203px;"/></td>
              </tr>
            </table>
        </td>
         </tr>
        <tr>
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
       </table>
       <br>
       <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
       <tr>
       	<td align="center">
       		<?php
       			if ($_SESSION['ismain'] == 1)
       			{
       				if ($pID > 0)
       				{
			?>
						<input name="btnUpdate"  type="submit" class="btn" value="Update" onclick="return form_validation()"/> &nbsp;
						<input name="btnCancel2" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=20'" /></td>
			<?php
					}
       			}
			?>
		</tr>
		</table>
        <br>
	</form>
        </td>

  </tr>
</table>
	</td>
   </tr>
 </table>

    </td>
  </tr>
</table>