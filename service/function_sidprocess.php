
<?php
	include "../initialize.php";
	
	global $database;   
	
	function createAPGL($docco,$doctype,$glctr,$filedate,$Account,$amount,$AddressNo_BM,$subledgertype,$subledger,$Remarks,$location,$DOC_NO_ORI,$recctr,$cmp)
	{
		global $database;
		$query = $database->execute("
									INSERT INTO interface_gl
									SET interface_gl.EDI_USERID         = '' , /*User Interface*/
									    interface_gl.EDI_TXN_NO         = '1', 
										interface_gl.EDI_LINENO         = $glctr,
										interface_gl.EDI_INDICATOR      = 'B',
										interface_gl.EDI_PROCESSED_STAT = '0',
										interface_gl.EDI_TXN_ACTION     = 'A',
										interface_gl.EDI_TXN_TYPE       = 'J',
										interface_gl.EDI_BATCH_NO       = '$DOC_NO_ORI', 
										interface_gl.DOC_TYPE           = 'JE',
										interface_gl.DOC_NO             = '',           /* JDE assigned */
										interface_gl.GL_DATE            = '$filedate',
										interface_gl.BATCH_TYPE         = 'G',
										interface_gl.DOC_CO             = '$docco',
									    interface_gl.ACCOUNT            = '$Account',
										interface_gl.ACCOUNT_MODE       = '2',
										interface_gl.SUBLEDGER          = '$subledger',
										interface_gl.SUBLEDGER_TYPE     = '$subledgertype',
										interface_gl.LEDGER_TYPES       = 'AA',
										interface_gl.CENTURY            = '20',
										interface_gl.CURRENCY           = 'PHP',
										interface_gl.AMOUNT             = $amount,
										interface_gl.EXPLANATION        = '$Remarks',
										interface_gl.REF2               = '$location',
										interface_gl.REF1               = '$cmp',
										interface_gl.ADDRESS_NO         = $AddressNo_BM,
										interface_gl.CURRENCY_MODE      = 'D',
										interface_gl.STATUS             = 'A',
										interface_gl.MODIFY_DATE        = now(),
										interface_gl.CREATE_DATE        = now(),
										interface_gl.RESERVED_CHAR01    = '',
										interface_gl.RESERVED_CHAR02    = '',
										interface_gl.RESERVED_DATE01    = now(),  
										interface_gl.RESERVED_DEC01     = 0.00,
										interface_gl.RESERVED_INT01     = 0
								");
		global $ms_conn;							
		$sql = " INSERT INTO [dbo].[ap_gl_interface] 
                 VALUES ('','1',$glctr,'B','0','A','J','$DOC_NO_ORI','JE',$recctr,'$filedate','G','$docco','$Account','2','','','','','$subledger','$subledgertype','AA','20',
				 'PHP',$amount,$AddressNo_BM,'',null,null,'D','$Remarks','$Remarks','$location','$cmp','DSV2',GETDATE(),GETDATE(),'','',CAST(GETDATE() AS DATE),0.00,0,'A') ";							
		#echo '<br>'.$sql;
		$gl = $ms_conn->prepare($sql);
	    $gl->execute(); 
        		
		return $query;									
								
	}

	function createSIDINV($dtype,$dbranch,$dfilename,$filedate,$linectr,$dmovementcode,$drefno,
						  $dreftxnid,$ditemcode,$ditemcodeold,$dquantity,$dwhordg,$dexplanation,$dtobranch,$dtobranchcode,$userid,$dGLClass)
	{
		global $database;
		$query = $database->execute(" 
									INSERT INTO sid_inv
											SET sid_inv.Type            = '$dtype',
												sid_inv.Branch          = '$dbranch',
												sid_inv.Filename        = '$dfilename',
												sid_inv.Txndate         = '$filedate',
												sid_inv.Lineno          = '$linectr',
												sid_inv.Movementcode    = '$dmovementcode',  
												sid_inv.RefNo           = '$drefno',
												sid_inv.RefTxnID        = '$dreftxnid',
												sid_inv.ProductCode     = '$ditemcode',   
												sid_inv.ProductCode_old = '$ditemcodeold',
												sid_inv.quantity        = '$dquantity',
												sid_inv.DGorWH          = '$dwhordg',
												sid_inv.Explanation     = '$dexplanation',
												sid_inv.tobranchId      = '$dtobranch',
												sid_inv.tobranchCode    = '$dtobranchcode',
												sid_inv.GLClass         = '$dGLClass',
												sid_inv.Created_By      = $userid,
												sid_inv.Created_date    = now(),
												sid_inv.Updated_by      = $userid,
												sid_inv.Updated_date    = now()
		                            ");
		  return $query;
		
	}
	
	function createSIDDMCM($dtype,$dbranch,$dfilename,$dtxndate,$detailtype,$reasoncode,$amount,$accouncode,$costcenter,
	                       $bmcode,$bmname,$campaign,$nationalid,$paymenttype,$userid,$linerow)
	{
		global $database;
		$query = $database->execute(" 
		                    INSERT INTO sid_dmcm
									SET sid_dmcm.type         = '$dtype',
										sid_dmcm.branch       = '$dbranch',
										sid_dmcm.filename     = '$dfilename',
										sid_dmcm.txndate      = '$dtxndate',
										sid_dmcm.detailtype   = '$detailtype',
										sid_dmcm.reasoncode   = '$reasoncode',
										sid_dmcm.amount       = '$amount',
										sid_dmcm.accouncode   = '$accouncode',
										sid_dmcm.costcenter   = '$costcenter',
										sid_dmcm.bmcode       = '$bmcode',
										sid_dmcm.bmname       = '$bmname',
										sid_dmcm.campaign     = '$campaign',
										sid_dmcm.nationalid   = '$nationalid',
										sid_dmcm.paymenttype  = '$paymenttype',
										sid_dmcm.created_by   = $userid,
										sid_dmcm.created_date = now(),
										sid_dmcm.updated_by   = $userid,
										sid_dmcm.updated_date = now(),
										sid_dmcm.linecounter  = $linerow
		                            ");
	}
	
	function createINVdetail($DOC_NO_ORI,$doctype,$filedate,$EXPLANATION,$BRANCH_PLANT,$linectr,$LOCATION,$itemNo,$UOM,$QTY,$REASON_CODE,
	                         $ACCOUNT,$HOLD_CODE,$Movementcode,$PL,$PLX)
	{
		global $database;
		
		$x = " INSERT INTO interface_inv_detail
											SET interface_inv_detail.DOC_COMP       = '00035',
												interface_inv_detail.DOC_NO         = '', 
												interface_inv_detail.BATCH_NO       = '$DOC_NO_ORI',   
												interface_inv_detail.DOC_TYPE       = '$doctype',
												interface_inv_detail.EDI_DOC_TYPE   = '$doctype', 
												interface_inv_detail.TXN_DATE       = '$filedate',
												interface_inv_detail.GL_DATE        = '$filedate',
												interface_inv_detail.LINE_NO        = $linectr,
												interface_inv_detail.BRANCH_PLANT   = '$BRANCH_PLANT',
												interface_inv_detail.LOCATION       = '$LOCATION',
												interface_inv_detail.ITEM_NO        = '$itemNo',
												interface_inv_detail.UOM            = '$UOM', 
												interface_inv_detail.QTY            = $QTY,
												interface_inv_detail.REASON_CODE    = '$REASON_CODE',
												interface_inv_detail.FROM_TO        = '',
												interface_inv_detail.TRANS_REF      = '$Movementcode',
												interface_inv_detail.ACCOUNT        = '$ACCOUNT',
												interface_inv_detail.SUBLEDGER      = '$PL',
												interface_inv_detail.SUBLEDGER_TYPE = '$PLX',
												interface_inv_detail.HOLD_CODE      = '$HOLD_CODE' ";
											
		$query = $database->execute("INSERT INTO interface_inv_detail
											SET interface_inv_detail.DOC_COMP       = '00035',
												interface_inv_detail.DOC_NO         = '', 
												interface_inv_detail.BATCH_NO       = '$DOC_NO_ORI',   
												interface_inv_detail.DOC_TYPE       = '$doctype',
												interface_inv_detail.EDI_DOC_TYPE   = '$doctype', 
												interface_inv_detail.TXN_DATE       = '$filedate',
												interface_inv_detail.GL_DATE        = '$filedate',
												interface_inv_detail.LINE_NO        = $linectr,
												interface_inv_detail.BRANCH_PLANT   = '$BRANCH_PLANT',
												interface_inv_detail.LOCATION       = '$LOCATION',
												interface_inv_detail.ITEM_NO        = '$itemNo',
												interface_inv_detail.UOM            = '$UOM', 
												interface_inv_detail.QTY            = $QTY,
												interface_inv_detail.REASON_CODE    = '$REASON_CODE',
												interface_inv_detail.FROM_TO        = '',
												interface_inv_detail.TRANS_REF      = '$Movementcode',
												interface_inv_detail.ACCOUNT        = '$ACCOUNT',
												interface_inv_detail.SUBLEDGER      = '$PL',
												interface_inv_detail.SUBLEDGER_TYPE = '$PLX',
												interface_inv_detail.HOLD_CODE      = '$HOLD_CODE'
									");
		global $ms_conn;							
		$sql = " INSERT INTO [dbo].[inv_interface_detail]
                 VALUES ('00035','','$DOC_NO_ORI','$doctype','$doctype','$filedate','$filedate',$linectr,'$BRANCH_PLANT'
			     ,'$LOCATION','$itemNo','$UOM','$QTY','$REASON_CODE','','$Movementcode','$ACCOUNT','$PL','$PLX','$HOLD_CODE',0.00,0.00,'','',CAST(GETDATE() AS DATE),0.00,0.00) ";							
		//echo '<br>'.$sql;
		$inv = $ms_conn->prepare($sql);
	    $inv->execute();
        		
		return $query;							
	}
	
	function createINVheader($DOC_NO_ORI,$doctype,$filedate,$EXPLANATION,$BRANCH_PLANT,$TOTLINE_CTR,$Movementcode)
	{
		global $database;
		$query = $database->execute("
									INSERT INTO interface_inv_header
											SET interface_inv_header.DOC_COMP     = '00035',
												interface_inv_header.DOC_NO       = '',
												interface_inv_header.BATCH_NO     = '$DOC_NO_ORI',
												interface_inv_header.DOC_TYPE     = '$doctype',
												interface_inv_header.EDI_DOC_TYPE = '$doctype',
												interface_inv_header.TXN_DATE     = '$filedate',
												interface_inv_header.GL_DATE      = '$filedate',
												interface_inv_header.EXPLANATION  = '$EXPLANATION',
												interface_inv_header.BRANCH_PLANT = '$BRANCH_PLANT',
												interface_inv_header.STATUS       = 'A',
												interface_inv_header.CREATE_DATE  = now(),
												interface_inv_header.MODIFY_DATE  = now(),
												interface_inv_header.TOTLINE_CTR  = $TOTLINE_CTR
		                           ");
								   
	    global $ms_conn;
	    $sql = "INSERT INTO [dbo].[inv_interface_header] VALUES ('00035','','$DOC_NO_ORI','$doctype','$doctype','$filedate','$filedate','$EXPLANATION','$BRANCH_PLANT' ,'A',GETDATE(),GETDATE(),0,'','',CAST(GETDATE() AS DATE),0.00,0.00) ";
    
	    $col = $ms_conn->prepare($sql);
	    $col->execute();
        		
		return $query;						   
								   
	}
	
	function createINVheader_zeroqty($DOC_NO_ORI,$doctype,$filedate,$EXPLANATION,$BRANCH_PLANT,$TOTLINE_CTR,$Movementcode)
	{
		global $database;
		$query = $database->execute("
									INSERT INTO interface_inv_header
											SET interface_inv_header.DOC_COMP     = '00035',
												interface_inv_header.DOC_NO       = '',
												interface_inv_header.BATCH_NO     = '$DOC_NO_ORI',
												interface_inv_header.DOC_TYPE     = '$doctype',
												interface_inv_header.EDI_DOC_TYPE = '$doctype',
												interface_inv_header.TXN_DATE     = '$filedate',
												interface_inv_header.GL_DATE      = '$filedate',
												interface_inv_header.EXPLANATION  = '$EXPLANATION',
												interface_inv_header.BRANCH_PLANT = '$BRANCH_PLANT',
												interface_inv_header.STATUS       = 'S',
												interface_inv_header.CREATE_DATE  = now(),
												interface_inv_header.MODIFY_DATE  = now(),
												interface_inv_header.TOTLINE_CTR  = $TOTLINE_CTR
		                           ");
								   
	    global $ms_conn;
	    $sql = "INSERT INTO [dbo].[inv_interface_header] VALUES ('00035','','$DOC_NO_ORI','$doctype','$doctype','$filedate','$filedate','$EXPLANATION','$BRANCH_PLANT' ,'S',GETDATE(),GETDATE(),0,'','',CAST(GETDATE() AS DATE),0.00,0.00) ";
    
	    $col = $ms_conn->prepare($sql);
	    $col->execute();
        		
		return $query;						   
								   
	}
	
    function createCollection($addressno,$txndate,$amount,$account,$DOC_NO_ORI)
	{
		global $database;
		$query = $database->execute("
		        INSERT INTO interface_collection
						SET interface_collection.EDI_USERID     = '',
							interface_collection.EDI_BATCHNO    = '',
							interface_collection.EDI_TXNNO      = '',
							interface_collection.EDI_LINENO     = '',
							interface_collection.EDI_DOCNO      = '$DOC_NO_ORI',
							interface_collection.DOC_COMP       = '00035',
							interface_collection.RECEIPT_NO     = '',
							interface_collection.BATCH_NO       = '',
							interface_collection.CUS_NO         = $addressno,
							interface_collection.TXN_DATE       = '$txndate',
							interface_collection.BATCH_TYPE     = '9B',
							interface_collection.BATCH_CTRY     = '2',
							interface_collection.GL_DATE        = '$txndate',
							interface_collection.GL_DATE_CTRY   = '0',
							interface_collection.AMOUNT         = $amount,
							interface_collection.REMARKS        = 'TRADE PAYMENT' ,
							interface_collection.ACCOUNT_GL     = '$account',
							interface_collection.ACCOUNT_MODE   = '1',
							interface_collection.CURRENCY       = 'PHP',
							interface_collection.CURRENCY_MODE  = 'D',
							interface_collection.STATUS         = 'A',
							interface_collection.CREATE_DATE    = NOW()
		                   ");
		
		global $ms_conn;
	    $sql = "INSERT INTO [dbo].[col_interface] VALUES ('','','','','".$DOC_NO_ORI."','00035','',null,".$addressno.",'".$txndate."','9B','2','".$txndate."','0',".$amount.",'TRADE PAYMENT','".$account."','1','PHP','D','A',GETDATE(),GETDATE(),'','',CAST(GETDATE() AS DATE),0.00,0)";
	    
		$col = $ms_conn->prepare($sql);
	    $col->execute();
        		
		return $query;
	}	
	
	function createsid_are($dtype,$dbranch,$dfilename,$dtxndate,$dpaymenttype,$dreasoncode,$damount,
						   $dDBaccount,$dDBcc,$dCRaccount,$dCRcc,$dRunningAmount,$linerow,$userid)
	{
		global $database;	
		$branchdailyARE= $database->execute("
		                                      INSERT INTO sid_are
													  SET sid_are.Type                = '$dtype',
														  sid_are.Branch              = '$dbranch',
														  sid_are.FileName            = '$dfilename',
														  sid_are.TxnDate             = '$dtxndate',
														  sid_are.PaymentType         = '$dpaymenttype',
														  sid_are.ReasonCode          = '$dreasoncode',
														  sid_are.Amount              = '$damount',
														  sid_are.DebitAccountCode    = '$dDBaccount',
														  sid_are.DebitCostCenter     = '$dDBcc',
														  sid_are.CreditAccountCode   = '$dCRaccount',
														  sid_are.CreditCostCenter    = '$dCRcc',
														  sid_are.RunningAmount       = '$dRunningAmount',
														  sid_are.LineCounter         = '$linerow',
														  sid_are.Created_By          = $userid,
														  sid_are.created_date        = now(),
														  sid_are.Updated_date        = now(),
                                                          sid_are.Updated_By		  = $userid											  
		                                   ");					   
	}					   
	
	function createsid_bds($dtype,$dbranch,$dfilename,$dtxndate,$productcode,$dqty,$ddregularpricensv,$dcspnsv,
						   $ddealerdiscountnsv,$dadditionaldiscnsv,$dregularprice,$dcsp,$ddealerdiscount,$dadditionaldisc,
						   $dadditionaldiscprev,$dinvoiceamount,$dnsv,$dvat,$dproductline,$dcustcode,$dcusttype,
						   $drowctr,$producttypeid,$jdeproductcode )
	{     
        global $database;	
		/*$branchdailysales = $database->execute(" 
		                    INSERT INTO sid_bds 
									SET sid_bds.type                 = '$dtype',
										sid_bds.branch               = '$dbranch',  
										sid_bds.filename             = '$dfilename',
										sid_bds.txndate              = '$dtxndate',
										sid_bds.productcode          = '$productcode', 
										sid_bds.qty                  = '$dqty',
										sid_bds.regularpricensv      = '$ddregularpricensv',
										sid_bds.cspnsv               = '$dcspnsv',
										sid_bds.dealerdiscountnsv    = '$ddealerdiscountnsv',
										sid_bds.additionaldisnsv     = '$dadditionaldiscnsv',
										sid_bds.regularprice         = '$dregularprice',
										sid_bds.csp                  = '$dcsp',
										sid_bds.dealerdiscount       = '$ddealerdiscount',
										sid_bds.additionaldisc       = '$dadditionaldisc',
										sid_bds.additionaldiscprev   = '$dadditionaldiscprev',  
										sid_bds.invoiceamount        = '$dinvoiceamount',
										sid_bds.nsv                  = '$dnsv',
										sid_bds.vat                  = '$dvat',
										sid_bds.productline          = '$dproductline',
										sid_bds.JDE_ItemNumber       = '$jdeproductcode',
										sid_bds.custcode             = '$dcustcode',
										sid_bds.custtype             = '$dcusttype',
										sid_bds.rowctr               = '$drowctr',
										sid_bds.producttypeid        = '$producttypeid'
		                   "); */
	} 
	
	function createSOHDR($DOC_COMP,$DOC_TYPE,$BUSINESS_UNIT,$DOC_NO_ORI,$ADDR_NO,$SHIP_TO,$ORDER_DATE,$TOTLINE_CTR)
	{
		global $database;
		$query = $database->execute(" 
		                    INSERT INTO interface_so_header 
									SET interface_so_header.DOC_COMP      = '$DOC_COMP',
										interface_so_header.DOC_NO        = '',
										interface_so_header.DOC_TYPE      = '$DOC_TYPE',
										interface_so_header.BATCH_NO      = '',
										interface_so_header.BUSINESS_UNIT = '$BUSINESS_UNIT',
										interface_so_header.DOC_COMP_ORI  = '',
										interface_so_header.DOC_NO_ORI    = '$DOC_NO_ORI',
										interface_so_header.DOC_TYPE_ORI  = '',
										interface_so_header.ADDR_NO       = $ADDR_NO,
										interface_so_header.SHIP_TO       = $SHIP_TO,  
										interface_so_header.ORDER_DATE    = '$ORDER_DATE',
										interface_so_header.`STATUS`      = 'A'  ,
										interface_so_header.CREATED_DATE  = NOW(),
										interface_so_header.MODIFY_DATE   = NOW(), 
										interface_so_header.TOTLINE_CTR   = $TOTLINE_CTR
		                  ");
	    
		global $ms_conn;
	    $sql = "INSERT INTO [dbo].[so_interface_header]
            VALUES ('".$DOC_COMP."','','".$DOC_TYPE."','','".$BUSINESS_UNIT."','','".$DOC_NO_ORI."','',".$ADDR_NO.",".$SHIP_TO.",'".$ORDER_DATE."','A',GETDATE(),GETDATE(),".$TOTLINE_CTR.",'','',CAST(GETDATE() AS DATE),0.00,0)";
        #echo $sql.'<br>';
	    $sohdr = $ms_conn->prepare($sql);
	    $sohdr->execute();
		
		#echo $sql.'222<br>';
		
		return $query;
	}
	
	function createSODTL($DOC_COMP,$DOC_TYPE,$BUSINESS_UNIT,$DOC_NO_ORI,$ADDR_NO,$SHIP_TO,$ORDER_DATE,$LINE_NO,
	                     $LINE_NO_ORI,$ITEM_NO,$UOM,$QTY,$EXTENDED_PRICE,$LINE_TYPE,$LOCATION,$PRD_CATEGORY,$nsv=0)
	{
		global $database;
		
		$query = $database->execute(" 
		                    INSERT INTO  interface_so_details   
									SET  interface_so_details.DOC_COMP          = '$DOC_COMP',  
										 interface_so_details.DOC_NO            = '',
										 interface_so_details.DOC_TYPE          = '$DOC_TYPE',
										 interface_so_details.BATCH_NO          = '',
										 interface_so_details.BUSINESS_UNIT     = '$BUSINESS_UNIT',
										 interface_so_details.DOC_COMP_ORI      = '',
										 interface_so_details.DOC_NO_ORI        = '$DOC_NO_ORI',
										 interface_so_details.DOC_TYPE_ORI      = '',
										 interface_so_details.ADDR_NO           = $ADDR_NO,
										 interface_so_details.SHIP_TO           = $SHIP_TO, 
										 interface_so_details.ORDER_DATE        = '$ORDER_DATE',
										 interface_so_details.LINE_NO           = $LINE_NO,
										 interface_so_details.LINE_NO_ORI       = $LINE_NO_ORI,
										 interface_so_details.ITEM_NO           = '$ITEM_NO',
										 interface_so_details.UOM               = '$UOM',
										 interface_so_details.QTY               = $QTY,
										 interface_so_details.UNIT_PRICE        = 0,
										 interface_so_details.EXTENDED_PRICE    = $EXTENDED_PRICE,
										 interface_so_details.UNIT_LIST_PRICE   = 0,
										 interface_so_details.UNIT_COST         = 0,
										 interface_so_details.LINE_TYPE         = '$LINE_TYPE',
										 interface_so_details.BRANCH_PLANT      = '$BUSINESS_UNIT',
										 interface_so_details.LOCATION          = '$LOCATION',
										 interface_so_details.GL_OFFSET         = '',
										 interface_so_details.PRD_CATEGORY      = '$PRD_CATEGORY',
										 interface_so_details.DMS_NSVAMOUNT     = $nsv
		                  ");
						  
		#tpi_error_log('1'.'|'.'AAA'.'|SID|BDS|'.'AAA'.'|'.'0'.'|Invalid Address No/Branch ID|0|'.'1'."\r\n",'../../../logs/sid_bds.log');				  
		global $ms_conn; 
		
	    $set = " INSERT INTO [dbo].[so_interface_detail]
	            VALUES ('".$DOC_COMP."','','".$DOC_TYPE."','','".$BUSINESS_UNIT."','','".$DOC_NO_ORI."','',".$ADDR_NO.",".$SHIP_TO.",'".$ORDER_DATE."',".$LINE_NO.",".$LINE_NO_ORI.",'".$ITEM_NO."','".$UOM."',".$QTY.",0,".$EXTENDED_PRICE.",0,0,".$nsv.",'".$LINE_TYPE."','".$BUSINESS_UNIT."','".$LOCATION."','','".$PRD_CATEGORY."','','',CAST(GETDATE() AS DATE),0.00,0)";
  	    #echo $set;
		$sodtl = $ms_conn->prepare($set);
	    $sodtl->execute(); 
		
        return  $query; 
	}
	
	function insertLOGs($Code,$SubCode,$Filename,$LineNo,$Remarks,$StatusID,$userid,$branch,$filedate)
	{
		global $database; 
	    $database->execute("INSERT INTO sid_logs
									SET sid_logs.Code            = '$Code',
									    sid_logs.Branch          = '$branch',   
										sid_logs.SubCode         = '$SubCode',
										sid_logs.Filename        = '$Filename',
										sid_logs.RecordLineNo    = '$LineNo',
										sid_logs.Remarks         = '$Remarks',
										sid_logs.StatusID        = '$StatusID',
										sid_logs.CreatedBy       = $userid ,
										sid_logs.CreatedDate     = now(),
										sid_logs.Filedate        = '$filedate'
						   ");
	}
	
	function getChargeCode($pwscode)
	{
		global $database; 
		return $database->execute("SELECT IFNULL(ia.ChargedToDepartment,'') chargecode FROM tpi_document ia  WHERE ia.CE_Code ='".TRIM($pwscode)."'")->fetch_object()->chargecode;
	}
	
	function createlogs($syncProcessLog)
	{
		global $database; 
	    $filecontent = fopen($syncProcessLog, 'r');  //open file
		while(($f = fgets($filecontent)) !== false)	
		{
			$fields  = explode('|',$f);
			insertLOGs($fields[2],$fields[3],$fields[4],$fields[5],$fields[6],$fields[7],$fields[8],$fields[1],$fields[0]);
			#echo $fields[0].'---'.$fields[1].'---'.$fields[2].'---'.$fields[3].'---'.$fields[4].'---'.$fields[5].'---'.$fields[6].'-'.$fields[7].'<br>';
		}
	}
	
	function createSIDFiles($filetype,$filebranch,$filedate,$userid,$filename,$ctrrec)
	{
		global $database; 
		$SID_Filesq = $database->execute(" SELECT * 
										   from  SID_Files 
										   where SID_Files.`Type`    = '$filetype' 
										     and SID_Files.Branch    = '$filebranch' 
										     and SID_Files.FileDate  = '$filedate'
										");
		if($SID_Filesq->num_rows > 0 )
		{
			 $database->execute("
									update SID_Files
									   SET SID_Files.`NoofRecords`    = '$ctrrec',
										   SID_Files.`Updated_Date`   = now(),
										   SID_Files.`Updated_By`     = $userid
									 where SID_Files.Branch    = '$filebranch'
									   and SID_Files.FileDate  = '$filedate'
									   and SID_Files.`Type`    = '$filetype'	
										");
		}
		else
		{
		    $database->execute("
									INSERT INTO SID_Files
									        SET SID_Files.`Type`           = '$filetype', 
												SID_Files.`Branch`         = '$filebranch', 
												SID_Files.`FileDate`       = '$filedate', 
												SID_Files.`Filename`       = '$filename',
												SID_Files.`NoofRecords`    = '$ctrrec', 
												SID_Files.`Uploaded_Date`  = now(),
												SID_Files.`Uploaded_By`    = $userid, 
												SID_Files.`Updated_Date`   = now(),
												SID_Files.`Updated_By`     = $userid
					  
							 ");
		}
	}
	
	function headervalidation($Code,$Filename,$filetype,$filebranch,$filedate,$addressno,$branchID,$location)
	{
		global $database;
		$valid = 1;
		#JDE Go Live Date Validation 
		$validate_golive = $database->execute("SELECT DATE(settingvalue) , DATE(settingvalue) <= '$filedate'  golive
                                             FROM `settings` WHERE settings.settingcode  = 'JDEGOLIVE' 
                                            ");
		if(!$validate_golive->num_rows > 0 )
		{
			$valid = 0;	
		}
		else
		{
			while($golive = $validate_golive->fetch_object() )
			{
				$valid = $golive->golive;
			}
		}
		
		$validate_file = $database->execute(" SELECT * 
														from  SID_Files 
														where SID_Files.`Type`          = '$filetype' 
														  and SID_Files.Branch          = '$filebranch'
														  and date(SID_Files.FileDate)  = '$filedate' ");
		if(!$validate_file->num_rows > 0 )
		{
			//$valid = 1;
		}
		else
		{
			$valid = 0;	  
	    }
		
		if($addressno == '' || $branchID == '' || $location == '') #validate if branch exist / if with branch plant location 
		{
			   $valid = 0;	
		}
		
		return $valid;		  
	}
	
	function GetBranchID($branchcode)
	{
		global $database;
	    return $database->execute("SELECT b.ID ID FROM branch b WHERE b.code  ='".TRIM($branchcode)."'")->fetch_object()->ID;
    }

	function GetAddressNo($branchcode)
	{
		global $database;
		return $database->execute("SELECT b.AddressNo AddressNo FROM branch b WHERE b.code  ='".TRIM($branchcode)."'")->fetch_object()->AddressNo;
	}
	
	function GetSubsidiaryBank($branchcode)
	{
		global $database;
		return $database->execute("SELECT b.SubsidiaryBank SubsidiaryBank FROM branch b WHERE b.code  ='".TRIM($branchcode)."'")->fetch_object()->SubsidiaryBank;
	}
	
	function GetBusinessUnit($branchcode)
	{
		global $database;
		return $database->execute("SELECT b.BusinessUnit BusinessUnit FROM branch b WHERE b.code  ='".TRIM($branchcode)."'")->fetch_object()->BusinessUnit;
	}
	
	
	function getMnemonicCode($MnemonicCode,$CodeValue)
	{
		global $database;
		return $database->execute(" SELECT codemaster.Value `ValueCode`
									FROM codemaster 
									WHERE codemaster.MnemonicCode = '".TRIM($MnemonicCode)."' 
									AND codemaster.CodeValue = '".TRIM($CodeValue)."'
								  ")->fetch_object()->ValueCode;
	}
	
	function GetAddressNo_Vendor($code)
	{
		global $database;
		return $database->execute("SELECT AddressNo from mapping_addressno where mapping_addressno.AddressNo_LongID  ='".TRIM($code)."'")->fetch_object()->AddressNo;
	}

	function GetRecordCountSO($ordertype,$addressno,$filedate)
	{
		global $database;
		return $database->execute(" SELECT COUNT(interface_so_header.doc_type) ctr
									FROM interface_so_header 
									WHERE interface_so_header.DOC_TYPE = '$ordertype'
									AND interface_so_header.ADDR_NO = $addressno
									AND interface_so_header.order_date = '$filedate' 
								 ")->fetch_object()->ctr;
	}
	
	function GetRecordCountARE($addressno,$filedate)
	{
		global $database;
		return $database->execute(" SELECT COUNT(interface_collection.cus_no) ctr
									FROM interface_collection 
									WHERE interface_collection.cus_no = $addressno
									AND date(interface_collection.txn_date) = '$filedate' 
								 ")->fetch_object()->ctr;
	}
	
	function GetINVCount($filedate,$doctype)
	{
		global $database;
		return $database->execute(" SELECT COUNT(interface_inv_header.BATCH_NO)  ctr
									FROM interface_inv_header 
									WHERE DATE(interface_inv_header.TXN_DATE) = '$filedate'
									AND interface_inv_header.DOC_TYPE = '$doctype'    
								 ")->fetch_object()->ctr;
	}
	
	function cohesion($amount1,$amount2)
	{
		$diff = number_format($amount1, 2, '.', '') - number_format($amount2, 2, '.', '');
		$diff = number_format($diff, 2, '.', '');
		$diff = $diff < 0 ? $diff * -1 : $diff;
		$witherror = $diff > 1 ? 1 : 0;
		
		return $witherror;
		
	}
	
	function startsWith($haystack, $needle)
	{
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}
	
	function getBranchPlantLocation($branchplant,$branchID,$LocationType)
	{
		global $database;
		return $database->execute(" SELECT bp.Location location
									FROM branchplant_details bp
									WHERE bp.BranchPlant  = '$branchplant'
									  AND bp.BranchID     =  $branchID   
									  AND bp.LocationType = '$LocationType'
								 ")->fetch_object()->location;
		
	}
	function getBranchPlantLocationIntransit($branchplant,$branchID,$LocationType,$type)
	{
		global $database;
		return $database->execute(" SELECT bp.Location location
									FROM branchplant_details bp
									WHERE bp.BranchPlant  = '$branchplant'
									  AND bp.BranchID     =  $branchID   
									  AND bp.LocationType = '$LocationType'
									  and bp.Type         = '$type'
								 ")->fetch_object()->location;
		
	}
	
	function getHoldCode($branchplant,$branchID,$LocationType)
	{
		global $database;
		return $database->execute(" SELECT bp.HoldCode HoldCode
									FROM branchplant_details bp
									WHERE bp.BranchPlant  = '$branchplant'
									  AND bp.BranchID     =  $branchID   
									  AND bp.LocationType = '$LocationType'
								 ")->fetch_object()->HoldCode;
		
	}
	
	function getProductLine($productcode)
	{
		global $database;
		return $database->execute(" SELECT p2.code PL 
									FROM product p
									INNER JOIN product p2 ON p2.id = p.ParentID
									WHERE p.code = '$productcode'
								 ")->fetch_object()->PL;
	}
	 
?>