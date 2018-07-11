<?
/*
	@Author: Gino Leabres
	@Date: 6/24/2013
	@Email: ginophp@gmail.com
	
	
	SALES REPORT, BACK ORDER REPORT
	
	DESCRIPTION
		Summary or Detailed report of all Back Order Transactions.
		Could be generated per Product Category or Dealer Account
	
	PARAMETERS
		Branch, Date Range,Dealer Account Range ,Product Category Range
	
	DISPLAY
		Customer Code ,Customer Name ,Product Code, Product Description, Sales Order No., Sales Invoice No., Transaction Date, Qty Ordered ,Qty Served, BackOrder Qty
*/
?>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/backorderhoreport.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<form name="frmORRegister" method="post" action="index.php?pageid=99">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php
                include("ipm_left_nav.php");
            ?><br>
        </td>
        <td class="divider">&nbsp;</td>
	<td valign="top" style="min-height: 610px; display: block;">
  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>                     
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
	    		<tr>
	      			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=203">Branch Performance Monitoring</a></td>
	    		</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">Back Order Report</td>
    		<td>&nbsp;</td>
		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
	    	<td>
			  	<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		        <tr>
		          	<td width="16%">&nbsp;</td>
		          	<td width="91%" align="right">&nbsp;</td>
		        </tr>
				<tr>
		          	<td height="20" class="padr5" align="right">Branches:</td>
					<td height="20">	
						
							<select id = 'branches' name = 'branches' class = "txtfield">
								<option value = 0>SELECT HERE</option>
								<?php $q = $database->execute("SELECT * FROM branch ORDER BY CODE asc");
								if($q->num_rows):
									while($r = $q->fetch_object()):?>
									<option value = "<?php echo $r->ID; ?>"><?php echo $r->Code." - ".$r->Name; ?></option>
								<?php 
									endwhile;
								endif; ?>
							</select>
						
					</td>				 
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right">Dealer Account Range:</td>
					<td height="20">	
						
							<select id = 'dealer_from' name = 'dealer_from' class = "txtfield">
								<option value = 0>SELECT HERE</option>
								<?php $q = $database->execute("SELECT *,SUBSTRING_INDEX(SUBSTRING_INDEX(HOGeneralID,'-',1),'-',-1) xID FROM customer ORDER BY SUBSTRING_INDEX(SUBSTRING_INDEX(HOGeneralID,'-',1),'-',-1) asc");
								if($q->num_rows):
									while($r = $q->fetch_object()):?>
									<option value = "<?php echo $r->xID; ?>"><?php echo $r->Code." - ".$r->Name; ?></option>
								<?php 
									endwhile;
								endif; ?>
							</select>							
							To:<select id = 'dealer_to' name = 'dealer_to' class = "txtfield">
								<option value = 0>SELECT HERE</option>
								<?php $q = $database->execute("SELECT *,SUBSTRING_INDEX(SUBSTRING_INDEX(HOGeneralID,'-',1),'-',-1) xID FROM customer ORDER BY  SUBSTRING_INDEX(SUBSTRING_INDEX(HOGeneralID,'-',1),'-',-1) asc");
								if($q->num_rows):
									while($r = $q->fetch_object()):?>
									<option value = "<?php echo $r->xID; ?>"><?php echo $r->Code." - ".$r->Name; ?></option>
								<?php 
									endwhile;
								endif; ?>
							</select>
						
					</td>					
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right">Product Range:</td>
					<td height="20">	
						
							<select id = 'product_from' name = 'product_from' class = "txtfield">
								<option value = 0>SELECT HERE</option>
								<?php $q = $database->execute("SELECT * FROM product WHERE ProductLevelID = 3 ORDER BY ID ASC");
								if($q->num_rows):
									while($r = $q->fetch_object()):?>
									<option value = "<?php echo $r->ID; ?>"><?php echo $r->Code." - ".$r->Name; ?></option>
								<?php 
									endwhile;
								endif; ?>
							</select>							
							To:<select id = 'product_to' name = 'product_to' class = "txtfield">
								<option value = 0>SELECT HERE</option>
								<?php $q = $database->execute("SELECT * FROM product WHERE ProductLevelID = 3 ORDER BY ID ASC ");
								if($q->num_rows):
									while($r = $q->fetch_object()):?>
									<option value = "<?php echo $r->ID; ?>"><?php echo $r->Code." - ".$r->Name; ?></option>
								<?php 
									endwhile;
								endif; ?>
							</select>
						
					</td>					
				</tr>
	    		<tr>
		          	<td height="20" class="padr5" align="right">From Date :</td>
		          	<td height="20">
		          		<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="">
	    			</td>				 
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right">To Date :</td>
		          	<td height="20">
		          		<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="">	        			
						<input name="input" type="button" class="btn" value="Generate Report" onclick="openPopUp()"/>	
					</td>
				</tr>
				<tr>
		          	<td colspan="2" height="20">&nbsp;</td>
		        </tr>
	    		</table>
			</td>
  		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center">
		<tr>
			<td height="20" align="center">
				<a class="txtnavgreenlink" href="index.php?pageid=18"><input name="Button" type="button" class="btn" value="Back"></a>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
</form>
