<?php
/*   
  @modified by John Paul Pineda.
  @date October 8, 2012.
  @email paulpineda19@yahoo.com         
*/ 
?>
<form name="frmVwInvCountDetails" method="post" action="includes/pcConfirmUpdatedInvCount.php"  >
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">  
    <tr>
  	 <td valign="top">
  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    		<tr>
    			<td class="topnav">
    				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
    		    	<tr>
    		        <td align="right" width="70%">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
    	    		</tr>
    				</table>
    			</td>
    		</tr>
  		</table>
  		<br />
      
  		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
    		<tr>
      		<td class="txtgreenbold13">View Inventory Count</td>
      		<td align="right">
      			<?php 
      			if (isset($_GET['msg'])) {
            
    					$message=strtolower($_GET['msg']);
    					$success=strpos("$message", "success"); 
    					echo "<span class='txtreds'>".$_GET['msg']."</span>";
  					} 
            ?>
          </td>
        </tr>
      </table>
      <br />
    
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">General Information</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
            
  	  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
        <tr>
          <td valign="top" class="bgF9F8F7">
            <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td width="40%" valign="top">  
    		          <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td width="25%" height="20" class="txt10">Reference No. :</td>
                      <td width="75%" height="20">
                      	<input type="text" name="txtTxnNo" value="<?php echo $transno ; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      	<input type="hidden" name="hTxnID" value="<?php echo $txnid; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td height="20" class="txt10">Document No. :</td>
                      <td height="20" >
                        <input type="text" name="txtDocNo" size="30" value="<?php echo $docno ; ?>" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
    			          <tr>
                      <td height="20" class="txt10">Date Created :</td>
                      <td height="20" >
                        <input type="text" name="txtTxnDate" value="<?php echo $transdate ; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <tr>
                      <td height="20" class="txt10">Warehouse :</td>
                      <td height="20">
                        <input type="text" name="txtWarehouse" value="<?php echo $warehouse ; ?>" size="30" readonly="yes" class="txtfieldLabel" /></td>
                    </tr>
                    <?php if($statusid!= 7): ?>
            		    <tr>
                      <td height="20" class="txt10">Transaction Date :</td>
                      <td height="20" >
                        <input type="text" name="txtTxnDate" value="<?php echo $dateconfirmed ; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
            		    <?php endif; ?>
                    <tr>
                      <td height="20" class="txt10">&nbsp;</td>
                      <td height="20" >&nbsp;</td>
                    </tr>
                  </table>
    		        </td>
                <td width="60%" valign="top">
                  <table width="100%"  border="0" cellspacing="1" cellpadding="0">
                    <tr>
                      <td height="20" class="txt10">Created By :</td>
                      <td height="20">
                        <input type="text" name="" value="<?php echo $createdby; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <?php if($statusid==7): ?>
                    <tr>
                      <td height="20" class="txt10">Confirmed By :</td>
                      <td height="20" >
                        <input type="text" name="" value="<?php echo $confirmedby; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <tr>
                      <td height="20" class="txt10">Date Confirmed :</td>
                      <td height="20">
                        <input type="text" name="" value="<?php echo $dateconfirmed; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td width="25%" height="20" class="txt10">Status :</td>
                      <td width="75%" height="20" >
                        <input type="text" name="" value="<?php echo $status; ?>" size="30" readonly="yes" class="txtfieldLabel" />
                      </td>
                    </tr>
                    <tr>
                      <td height="20" valign="top" class="txt10">Remarks :</td>
                      <td height="20" >
                        <textarea name="txtRemarks" cols="30" rows="5" <?php if($statusid!=1) echo 'readonly="yes"'; ?> class="txtfieldnh"><?php echo $remarks ; ?></textarea>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>      
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="3" class="bgE6E8D9"></td>
        </tr>
      </table>
      <br />
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">Transaction Details</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
      
       <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
        <tr align="center" class="tab txtdarkgreenbold10">
          <td valign="top" class="bgF9F8F7">
            <div<?php echo $rs_detailsall->num_rows>6?' style="margin-right: 17px;"':''; ?>>
              <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                <tr>                  
                  <td align="left" width="6%" class="borderBR">Item Code</td>
                  <td align="left" width="24%" class="borderBR">Description</td>
                  <td align="right" width="6%" class="borderBR">Unit Price</td>
                  <td align="right" width="6%" class="borderBR">Qty To Adjust</td>
                  <td align="right" width="8%" class="borderBR">Value To Adjust</td>                                   			
                  <td align="right" width="6%" class="borderBR">Counted Qty</td>
                  <td align="right" width="6%" class="borderBR">Amt<?php echo str_repeat('&nbsp;', 4); ?> Counted</td>
                  <td align="right" width="6%" class="borderBR">Qty Under</td>
                  <td align="right" width="8%" class="borderBR">Value Under</td>
                  <td align="right" width="6%" class="borderBR">Qty Over</td>
                  <td	align="right" width="6%" class="borderBR">Value Over</td>              
                </tr>
              </table>
            </div>
          </td> 
        </tr>        
         <tr>
           <td valign="top" class="bgF9F8F7">
             <div class="scroll_300">
            		<?php 
            		if ($rs_detailsallnull->num_rows) {
                  
        				 	while($rownull=$rs_detailsallnull->fetch_object()) {
                   			
        					 $numnull=$rownull->numrows;					
        					}
        				}
        	  		?>
        		  	<input type="hidden" name="hNumNull" value="<?php echo $numnull; ?>" />
                
                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">                  
                  <?php
                  $ctr=0;                                    
                  $rowalt=0;
                  $untiprice=0;
                  
                  $QtyUnder=0;
                  $ValUnder=0;
                  $QtyOver=0;
                  $ValOver=0;
                                                      
                  $totQTA=0;					
                  $totVTA=0;              
                  $totCQ=0;
                  $totAC=0;
                  
                  $totQU=0;
                  $totVU=0;                                    
                  $totQO=0;
                  $totVO=0;                  									                                                                                                                                             
                  
                  if($rs_detailsall->num_rows) {
                  
                    while($field=$rs_detailsall->fetch_object()) {
                    
                      $ctr+=1;	
                      $rowalt+=1;
                      $class=($rowalt%2)?'':'bgEFF0EB';                                            
                      
                        $amountcnt=($field->up*$field->CreatedQty);
                        $untiprice=number_format($field->up, 2, ".", ",");
                                                                      
                        $valueToAdjust=($field->SOH*$field->up);                                                                        
                        
                        $totQTA+=$field->SOH;
                        $totVTA+=$valueToAdjust;                        
                        $totCQ+=$field->CreatedQty;
                        $totAC+=$amountcnt;
                        
                        $QtyUnder=0;
                        $ValUnder=number_format(0, 2, '.', ',');                                                                
                        $QtyOver=0;
                        $ValOver=number_format(0, 2, '.', ',');                                                                
                        
                        if($field->SOH>$field->CreatedQty) {
                        
                          $QtyUnder=abs($field->CreatedQty-$field->SOH);
                          $ValUnder=number_format(($QtyUnder*$field->up), 2, '.', ',');
                          
                          $totQU+=$QtyUnder;		                                                    
                          $totVU+=($QtyUnder*$field->up);                           							    		
                        } else if($field->SOH<$field->CreatedQty) {
                        
                          $QtyOver=abs($field->SOH-$field->CreatedQty);
                          $ValOver=number_format(($QtyOver*$field->up), 2, '.', ',');
                          
                          $totQO+=$QtyOver;                                                    
                          $totVO+=($QtyOver*$field->up);                          
                        }                                                  							                                                                                                                  
                      ?>            
                      <tr align="center" class="<?php echo $class; ?>">
                        <input name="hProdID" type="hidden" value="<?php echo $field->ProductID; ?>">
                        <input name="hInvID" type="hidden" value="<?php echo $field->invdid; ?>">
                        <input name="hQty" type="hidden" value="<?php echo $field->CreatedQty; ?>">
                        <input name="htxnid" type="hidden" value="<?php echo $_GET['tid']; ?>">
                                                
                        <td align="left" width="6%" height="18" class="borderBR"><?php echo $field->pCode; ?></td>
                        <td align="left" width="24%" height="18" class="borderBR tpi-dss-hint" title="<?php echo 'DG CountTag: '.$field->CountTag; ?>"><?php echo $field->pName; ?></td>
                        <td align="right" width="6%" height="18" class="borderBR"><?php echo $untiprice; ?></td>
                        <td align="right" width="6%" height="18" class="borderBR"><?php echo number_format($field->SOH, 0, '', ','); ?></td>
                        <td align="right" width="8%" height="18" class="borderBR"><?php echo number_format($valueToAdjust, 2, '.', ','); ?></td>
                        <td align="right" width="6%" height="18" class="borderBR"><?php echo $field->CreatedQty; ?></td>                                                
                        <td align="right" width="6%" height="18" class="borderBR"><?php echo number_format($amountcnt, 2, '.', ','); ?></td>		
                        <td align="right" width="6%" height="18" class="borderBR"><?php echo number_format($QtyUnder, 0, '', ','); ?></td>
                        <td align="right" width="8%" height="18" class="borderBR"><?php echo $ValUnder; ?></td>
                        <td align="right" width="6%" height="18" class="borderBR"><?php echo number_format($QtyOver, 0, '', ','); ?></td>
                        <td align="right" width="6%" height="18" class="borderBR"><?php echo $ValOver; ?></td>
                      </tr>
                    <?php 
                    }                    
                    $rs_detailsall->close();					
                  } else {
                  
                    echo "<tr align='center'>
                    <td colspan='14' height='20' class='borderBR'><div align='center'><span class='txtredsbold'>No record(s) to display.</span></div></td></tr>
                    </tr>";                                     
                  }
                  ?>
                </table>
        		  </div>
            </td>
          </tr>
        </table>
        <br />
      
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tabmin">&nbsp;</td>
            <td class="tabmin2 txtredbold">Totals</td>
            <td class="tabmin3">&nbsp;</td>
          </tr>
        </table>
        
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">          
          <tr valign="top" class="tab txtdarkgreenbold10">
            <td align="right" height="20" class="bdiv_r">&nbsp;</td>
            <td align="right" class="bdiv_r">Qty To Adjust</td>
            <td align="right" class="bdiv_r">Value To Adjust</td>
            <td align="right" class="bdiv_r">Counted Qty</td>
            <td align="right" class="bdiv_r">Amounted Count</td>
            <td align="right" class="bdiv_r">Qty Under</td>
            <td align="right" class="bdiv_r">Value Under</td>
            <td align="right" class="bdiv_r">Qty Over</td>
            <td align="right">Value Over</td>                        
          </tr>
          
          <tr align="center">
            <td align="right" height="20"  class="borderBR padr5"><strong>Worksheet Total :</strong></td>		        	
            <td align="right" height="20" class="borderBR"><?php  echo number_format($totQTA, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php  echo number_format($totVTA, 2, '.', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totCQ, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totAC, 2, '.', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totQU, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totVU, 2, '.', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totQO, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo number_format($totVO, 2, '.', ','); ?></td>
          </tr>
          
          <tr align="center">
            <td height="20" align="right" class="borderBR padr5"><strong>Variance :</strong></td>
            <?php
            $totVUVTA=0;
            $totQUVTA=0;
            $totVOVTA=0;
            $totQOVTA=0;
               
            if($totQTA!=0) {
            
              $totQUVTA=($totQU/$totQTA)*100;
              $totVUVTA=($totVU/$totVTA)*100;
              $totQOVTA=($totQO/$totQTA)*100;                      
              $totVOVTA=($totVO/$totVTA)*100;                      
            }                                                               
            ?>                    
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td align="right" height="20" class="borderBR"><?php echo round($totQUVTA, 2); ?>%</td>
            <td align="right" height="20" class="borderBR"><?php echo round($totVUVTA, 2); ?>%</td>
            <td align="right" height="20" class="borderBR"><?php echo round($totQOVTA, 2); ?>%</td>
            <td align="right" height="20" class="borderBR"><?php echo round($totVOVTA, 2); ?>%</td>
          </tr>
          
          <tr align="center">
            <td align="right" height="20" class="borderBR padr5"><strong>Branch Total :</strong></td>
            <td align="right" height="20" class="borderBR"><?php echo number_format($totQTA, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo number_format($totVTA, 2, '.', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totCQ, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totAC, 2, '.', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totQU, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totVU, 2, '.', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo  number_format($totQO, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo number_format($totVO, 2, '.', ','); ?></td>
          </tr>
          
          <tr align="center">
            <td align="right" height="20" class="borderBR padr5"><strong>Variance % :</strong></td>                                                                                   
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" align="right" class="borderBR"><?php echo round($totQUVTA, 2); ?>%</td>
            <td height="20" align="right" class="borderBR"><?php echo round($totVUVTA, 2); ?>%</td>
            <td height="20" align="right" class="borderBR"><?php echo round($totQOVTA, 2); ?>%</td>
            <td height="20" align="right" class="borderBR"><?php echo round($totVOVTA, 2); ?>%</td>
          </tr>
          
          <tr align="center">
            <td align="right" width="12%" height="20" class="borderBR padr5"><strong>Net Variance :</strong></td>
            <?php $totNetVarQty=$totQO-$totQU; $totNetVarVal=$totVO-$totVU; ?>                    
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td align="right" height="20" class="borderBR"><?php echo number_format($totNetVarQty, 0, '', ','); ?></td>
            <td align="right" height="20" class="borderBR"><?php echo number_format($totNetVarVal, 2, '.', ','); ?></td>
          </tr>
          
          <tr align="center">	
            <td width="12%" height="20" align="right" class="borderBR padr5"><strong>Net Variance Value % :</strong></td>
            <?php
            $totNetVarQtyPer=0;
            $totNetVarValPer=0;
              
            if($totQTA!=0) {
            
              $totNetVarQtyPer=($totNetVarQty/$totQTA)*100;
              $totNetVarValPer=($totNetVarVal/$totVTA)*100;
            }                                          
            ?>                    
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td height="20" class="borderBR"></td>
            <td align="right" height="20" class="borderBR"><?php echo round($totNetVarQtyPer, 2); ?>%</td>
            <td align="right" height="20" class="borderBR"><?php echo round($totNetVarValPer, 2); ?>%</td>
          </tr>                                                  
        </table>
        <br />
  
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center">
              <input name="btnPrint" type="button" class="btn" value="Print" onclick="return validatePrint();" >
              <?php if($statusid==6): ?> 
              <input type="submit" name="btnConfirm" value="Confirm" onclick="return validateConfirm();" class="btn" />
              <?php endif; ?>					
              <input type="button" name="btnCancel" value="Cancel" onclick="return confirmCancel();" class="btn" />
            </td>
          </tr>
        </table>
        <br />
      </td>
    </tr>
  </table>
</form>
