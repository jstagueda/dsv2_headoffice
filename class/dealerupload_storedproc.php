<?php
/*
Gino C. Leabres Procedures For Data Migration
*/
/*stored procedures*/
	public function spTruncateTmpCust()
	{
		$sp = "truncate table tmpcust;"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	
	public function spTruncateTmpInv()
	{
		$sp = "truncate table tmpinventorybegbal;"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	public function spUpdateDealerCodes($code,$ID)
	{
		$sp = "update customer set Code ='".$code."' where ID = ".$ID.";"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	public function spCreateNewDealerCode()
	{
		$sp = "select
			  c.ID customerID,
			  cast(concat('0',bp.BranchID,c.Code) as char) newDealerCode,
			  c.Code
			from customer c
			inner join tmpcust tmp on c.Code =  tmp.DealerCode
			inner join branchparameter bp;"; 	
		$rs = $this->database->execute($sp);
		return $rs;
		
	}
	
	public function spLoadInvDataToTempTable()
	{
			$sp = "LOAD DATA LOCAL INFILE '/xampp/htdocs/10.132.54.166/Data/SHA-iv.csv'
			INTO TABLE tmpinventorybegbal
			FIELDS TERMINATED BY ','
			Optionally enclosed by '\"'
			LINES TERMINATED BY '\n'
			Ignore 1 lines;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	public function UpdateInvDataToTempTable()
	{
			$sp = "UPDATE tmpinventorybegbal SET Location = UPPER(Location)"; 	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	public function spInsertIntoInv()
	{

			$sp = "
					Insert Into inventory
					(warehouseID, ProductID, SOH, InTransit, EnrollmentDate, LastmOdifiedDate)
					SELECT w.ID warehouseID, p.ID ProductID, ti.SOH, 0 InTransit, NOW() EnrollmentDate, NOW() LastmOdifiedDate
					FROM tmpinventorybegbal ti
					INNER JOIN product p ON p.Code = ti.ProductCode
					INNER JOIN warehouse w ON w.Code = TRIM(UPPER(ti.Location));	"; 	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	public function spInsertIntoInvBegBal()
	{
			
			$sp = "
			Insert into inventorybeginningbalance
			(WarehouseID, ProductID, BeginningQty, EnrollmentDate, LastModifiedDate)
			select w.ID WarehouseID, p.ID ProductID, ti.SOH, now() EnrollmentDate, now() LastModifiedDate
			from tmpinventorybegbal ti
			inner join product p on p.Code = ti.ProductCode
			inner join warehouse w on w.Code = UPPER(ti.Location);	"; 	
		$rs = $this->database->execute($sp);
		return $rs;	
	}	
	public function spLoadDataToTempTable()
	{
			$sp = "LOAD DATA LOCAL INFILE '/xampp/htdocs/10.132.54.166/Data/SHA-dlr-0912.csv'
			INTO TABLE tmpcust
			FIELDS TERMINATED BY ','
			Optionally enclosed by '\"'
			LINES TERMINATED BY '\n'
			Ignore 1 lines;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	
	}
	public function spUpdateStatus()
	{
		$sp = "Update tmpcust set status =  substr(Status,1,2);"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	public function spInsertCustomer()
	{
		$sp = " Insert into customer
				(Code, Name, LastName, FirstNAme, MiddleName, BirthDate, CustomerTypeID, CustomerClassID,
				StatusID, EnrollmentDate, LastModifiedDate, Changed)
				Select DealerCode, TRIM(CONCAT(TRIM(LastName),', ', TRIM(FirstName))) Name, LastName, FirstName, MiddleName,
				BirthDate, ct.ID CustomerTypeID, cc.ID CustomerClassID, 1, now(), now(), 0
				from tmpcust t
				inner join customertype ct on ct.Code = t.DealerType
				inner join customerclass cc on cc.Code = t.Classification;"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	public function spInsertrCustomerBranch()
	{
		
		$sp= "
				Insert into tpi_rcustomerbranch (CustomerID,BranchID,IsPrimary,CreatedBy,EnrollmentDate,Changed)
				select c.ID,b.BranchID,1 IsPrimary,1 CreatedBy,now(),0 Changed
				from customer c
				inner join branchparameter b ";	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	public function spUpdateGSUTypeNG()
	{
		$sp= "update tmpcust set GSUType = 'NG' where trim(GSUType)  like 'NONG'; ";	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	public function spUpdateGSUTypeIG()
	{
		$sp= " update tmpcust set GSUType = 'IG' where trim(GSUType)  like 'GSUI'; ";	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	public function spUpdateGSUTypeDG()
	{
		$sp= " update tmpcust set GSUType = 'DG' where trim(GSUType)  like 'GSUD'; ";	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	public function spInsertCustomerDetailsIGS()
	{
	

		$sp = " 
		Insert into tpi_customerdetails
		(CustomerID, AreaID, tpi_ZoneID, tpi_GSUTypeID, tpi_IBMCode, tpi_RecruiterID,
		EnrollmentDate, LastModifiedDate, Changed,LastPODate)
		Select c.ID, ifnull(a.ID,1), 1, tg.ID GSUTypeID, null, c2.ID, now() EnrollmentDate, now() LastModifiedDate, 0 Changed,tc.LastPODate
		from customer c
		inner join tmpcust tc on tc.DealerCode = c.Code
   		left join area a on a.Name = tc.Brgy and arealevelID = 5
		left join customer c2 on c2.code = tc.RecruiterNo
		inner join tpi_gsutype tg on tg.Code = tc.GSUType
		where c.CustomerTypeID = 1;"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	public function spInsertCustomerDetailsIBM()
	{

		$sp = "
		 Insert into tpi_customerdetails
		(CustomerID, AreaID, tpi_ZoneID, tpi_GSUTypeID, tpi_IBMCode, tpi_RecruiterID,
		EnrollmentDate, LastModifiedDate, Changed,LastPODate)
		Select c.ID, ifnull(a.ID,1), 1, IFNULL(tg.ID,'') GSUTypeID, tc.IBMNo, c2.ID, now() EnrollmentDate, now() LastModifiedDate, 0 Changed,tc.LastPODate
		from customer c
		inner join tmpcust tc on tc.DealerCode = c.Code
		left join area a on a.Name = tc.Brgy and arealevelID = 5
		left join customer c2 on c2.code = tc.RecruiterNo
		inner join tpi_gsutype tg on tg.Code = tc.GSUType
		where c.CustomerTypeID in (2,3);
		"; 	
		$rs = $this->database->execute($sp);
		
		return $rs;
	}
	public function spUpdateCustomerStatus()
	{

		$sp = "
		Insert into tpi_rcustomerstatus (CustomerID, CustomerStatusID, CreatedBy, EnrollmentDate, CHANGED)
		select c.ID, ifnull(s.ID,5) CustomerStatusID, 1 CreatedBy, now(), 0 CHANGED
		FROM customer c
		inner join tmpcust tc on tc.DealerCode = c.Code
		left join status s on s.Code =  tc.Status;"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	
	public function spSelectIBM()
	{
		$sp="select ID,Code from customer where customertypeid =3;";	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	public function spInsertIBM($CustID,$code)
	{
		/*insert into tpi_rcustomeribm (CustomerID, IBMID,CreatedBy, EnrollmentDate, Changed)*/
		$sp="
			insert into tpi_rcustomeribm (CustomerID, IBMID,CreatedBy, EnrollmentDate, Changed)
			select c.id,$CustID CustID,1 CreatedBy,now() EnrollmentDate,0 Changed from tmpcust  tc
			inner join customer c on c.Code = tc.DealerCode
			where IBMNo ='".$code."' ;";	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	public function spInsertMIBM($CustID,$code)
	{

		$sp="
			insert into tpi_rcustomeribm (CustomerID,IBMID,CreatedBy, EnrollmentDate,Changed)
			select c.id, $CustID IBMID,1 CreatedBy, now(),0 Changed from tmpcust  tc
			inner join customer c on c.Code = tc.DealerCode
			where IBM ='".$code."' ;";	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	
	public function spInsertTPICredit()
	{	

		 $sp="
				insert into tpi_credit (CustomerID,CreditTermID,ApprovedCL)
				select c.ID , ifnull(ct.ID,1), replace(ApprLimit,',','') ApprLimit from tmpcust tc
				left join creditterm ct on ct.Duration = tc.CreditTerms
				inner join customer c on tc.DealerCode = c.Code;";	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	public function spDropTmpLastSI()
	{
		$sp = "DROP TABLE IF EXISTS `tmplastsi`;";
		$rs = $this->database->execute($sp);
		return $rs;
	} 
	public function spCreateTmpLastSI()
	{
		$sp = "Create TABLE tmplastsi
			  (
			  dealer varchar(20) not null,
			OrderDate DateTime,
			SONumber varchar(20),
			InvoiceNumber varchar(20),
			CreditTerms int,
			csp decimal(11,4),
			cpi decimal(11,4),
			dgs decimal(11,4),
			bd decimal(11,4),
			cft decimal(11,4),
			ncft decimal(11,4),
			ad decimal(11,4),
			swv decimal(11,4),
			vp decimal(11,4),
			vs decimal(11,4),
			adpp decimal(11,4),
			nsv decimal(11,4),
			inv decimal(11,4),
			obal decimal(11,4),
			PenaltyAmt decimal(11,4),
			DaysDue int,
			DueDate datetime
			  );";
		$rs = $this->database->execute($sp);
		return $rs;
	}
	
		public function spLoadDataToLastSITempTable()
	{
			$sp = "LOAD DATA LOCAL INFILE '/xampp/htdocs/10.132.54.166/Data/SHA-LastSI.csv'
			INTO TABLE tmplastsi
			FIELDS TERMINATED BY ','
			Optionally enclosed by '\"'
			LINES TERMINATED BY '\n'
			Ignore 2 lines;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;	
	}	
	public function spInsertSILastSI()
	{
		
			$sp = "
					Insert into salesinvoice
					(CustomerID, RefTxnID, DocumentNo, TxnDate, EffectivityDate, 
					CreditTermID, GrossAmount, ToTalCPI,
					BasicDiscount, TotalCFT, TotalNCFT, AddtlDiscount, VatAmount, AddtlDiscountPrev,
					NetAmount, OutstandingBalance, CreatedBy, EnrollmentDate, LastMOdifiedDate, StatusID)
					SELECT c.ID, 0 RefTxnID, InvoiceNumber, OrderDate, DueDate, ct.CreditTermID, csp, cpi,
					bd, cft, ncft, ad, vp, adpp, nsv, obal, 1 CreatedBy, now() EnrollmentDate, now() LastMOdifiedDate, 7 StatusID
					from tmplastsi tl
					inner join customer c on c.Code = tl.dealer
					inner join tpi_credit ct on c.ID = ct.CustomerID;	
					"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	
	}
	public function spInsertARLastSI()
	{
		/*Insert into customeraccountsreceivable
		(CustomerID, SalesInvoiceID, OutstandingAmount, DaysDue, EnrollmentDate, LastModifiedDate, Changed)
					*/
			$sp = "
					Insert into customeraccountsreceivable
					(CustomerID, SalesInvoiceID, OutstandingAmount, DaysDue, EnrollmentDate, LastModifiedDate, Changed)
					Select CustomerID, si.ID, tl.obal, case when tl.DaysDue>0 then 0 else ABS(tl.DaysDue) end , now() EnrollmentDate, now() LastModifiedDate, 0 Changed
					from tmplastsi tl
					inner join salesinvoice si on si.DocumentNo = tl.InvoiceNumber
					inner join customer c on c.ID = si.CustomerID;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	
	}
	public function spInsertPenaltyLastSI()
	{
					
			$sp = "
					Insert into customerpenalty
					(CustomerID, SalesInvoiceID, Amount, OutstandingAmount, EnrollmentDate, LastModifiedDate, Changed)
					Select CustomerID, si.ID,tl.PenaltyAmt, tl.obal , now() EnrollmentDate, now() LastModifiedDate, 0 Changed
					from tmplastsi tl
					inner join salesinvoice si on si.DocumentNo = tl.InvoiceNumber
					inner join customer c on c.ID = si.CustomerID;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	
	}
	public function spDropTmpOpenSI()
	{
		$sp = "DROP TABLE IF EXISTS `tmpopensi`;";
		$rs = $this->database->execute($sp);
		return $rs;
	} 
	
	public function spCreateTmpOpenSI()
	{
		$sp = "Create TABLE tmpopensi
			  (
			  dealer varchar(20) not null,
			OrderDate DateTime,
			SONumber varchar(20),
			InvoiceNumber varchar(20),
			CreditTerms int,
			csp decimal(11,4),
			cpi decimal(11,4),
			dgs decimal(11,4),
			bd decimal(11,4),
			cft decimal(11,4),
			ncft decimal(11,4),
			ad decimal(11,4),
			swv decimal(11,4),
			vp decimal(11,4),
			vs decimal(11,4),
			adpp decimal(11,4),
			nsv decimal(11,4),
			inv decimal(11,4),
			obal decimal(11,4),
			PenaltyAmt decimal(11,4),
			DaysDue int,
			DueDate datetime
			  );";
		$rs = $this->database->execute($sp);
		return $rs;
	} 
	public function spLoadDataToOpenSITempTable()
	{
			$sp = "LOAD DATA LOCAL INFILE '/xampp/htdocs/10.132.54.166/Data/SHA-OpenSI.csv'
			INTO TABLE tmpopensi
			FIELDS TERMINATED BY ','
			Optionally enclosed by '\"'
			LINES TERMINATED BY '\n'
			Ignore 2 lines;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	
	}
	public function spInsertSIOpenSI()
	{
			$sp = "
					Insert into salesinvoice
					(CustomerID, RefTxnID, DocumentNo, TxnDate, EffectivityDate, CreditTermID, GrossAmount, ToTalCPI,
					BasicDiscount, TotalCFT, TotalNCFT, AddtlDiscount, VatAmount, AddtlDiscountPrev,
					NetAmount, OutstandingBalance, CreatedBy, EnrollmentDate, LastMOdifiedDate, StatusID)
					SELECT c.ID, 0 DocumentNo, InvoiceNumber, OrderDate, DueDate, ct.CreditTermID, csp, cpi,
					bd, cft, ncft, ad, vp, adpp, nsv, obal, 1 CreatedBy, now() EnrollmentDate, now() LastMOdifiedDate, 7 StatusID
					from tmpopensi tl
					inner join customer c on c.Code = tl.dealer
					inner join tpi_credit ct on c.ID = ct.CustomerID;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	
	}
	public function spInsertAROpenSI()
	{
		/*
					
		*/
			$sp = "
					Insert into customeraccountsreceivable
					(CustomerID, SalesInvoiceID, OutstandingAmount, DaysDue, EnrollmentDate, LastModifiedDate, Changed)
					Select CustomerID, si.ID, tl.obal, case when tl.DaysDue>0 then 0 else ABS(tl.DaysDue) end , now() EnrollmentDate, now() LastModifiedDate, 0 Changed
					from tmpopensi tl
					inner join salesinvoice si on si.DocumentNo = tl.InvoiceNumber
					inner join customer c on c.ID = si.CustomerID;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	
	}
	public function spInsertPenaltyOpenSI()
	{

			$sp = "
					Insert into customerpenalty
					(CustomerID, SalesInvoiceID, Amount, OutstandingAmount, EnrollmentDate, LastModifiedDate, Changed)
					Select CustomerID, si.ID,tl.PenaltyAmt, tl.obal , now() EnrollmentDate, now() LastModifiedDate, 0 Changed
					from tmpopensi tl
					inner join salesinvoice si on si.DocumentNo = tl.InvoiceNumber
					inner join customer c on c.ID = si.CustomerID;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	
	}
	public function spSelectRecordsFromTmpCustomer()
	{
		$sp = "select count(ID) cntCustomer from tmpcust;	"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	
	/*Gino Leabres Procedure Created*/
	
	public function spInsertTmpProdMaster($prodCode, $prodName, $uom, $prodLine, $csp, $pmgCode, $pmStatus, $itemType, $brand, $form, $lastReceiptDate, $productType, $cost, $lastPODate)
	{
		$sp = "Insert into tpi_tmpprodmaster (ItemCode,
								 Description,
								 UOMCode,
								 ProductLineCode,
								 CSP,
								 PMGCode,
								 InvStatusCode,
								 ItemTypeCode,
								 BrandCode,
								 FormCode,
								 LastReceiptDate,
                                 ProductTypeCode,
                                 ProductCost,
                                 LastPODate)
                        
						VALUES
							(	'$prodCode', 
								'$prodName', 
								'$uom',
								'$prodLine', 
								$csp, 
								'$pmgCode', 
								'$pmStatus', 
								'$itemType', 
								'$brand', 
								'$form', 
								'$lastReceiptDate', 
								'$productType', 
								$cost, 
								'$lastpodate');"; 	
		$rs = $this->database->execute($sp);
		return $rs;
		
	}
	
	public function spTruncateTmpProdMaster()
	{
		$sp = "truncate table tpi_tmpprodmaster;"; 	
		$rs = $this->database->execute($sp);
		return $rs;
	}
	
	public function spUpdateTmpProdMasterStep1()
	{
	
		$rs = "insert into `value` (Code, Name, FieldID, Sequence, StatusID, EnrollmentDate, LastModifiedDate, Changed)
		select pd.BrandCode, pd.BrandCode, 8, 1, 1, now(), now(), 1
		from tpi_tmpprodmaster pd
		left join `value` ut on ut.Code = pd.BrandCode and ut.FieldID = 8
		where ut.ID is NULL;";
			$rs = $this->database->execute($rs);
			return $rs;
	}	
	public function spUpdateTmpProdMasterStep2()
	{
		$rs1 = "insert into `value` (Code, Name, FieldID, Sequence, StatusID, EnrollmentDate, LastModifiedDate, Changed)
		select pd.FormCode, pd.FormCode, 9, 1, 1, now(), now(), 1
		from tpi_tmpprodmaster pd
		left join `value` ut on ut.Code = pd.FormCode and ut.FieldID = 9
		where ut.ID is NULL;";
			$rs1 = $this->database->execute($rs1);
			return $rs1;
	}
	public function spUpdateTmpProdMasterStep3()
	{		
		$rs2 = "update tpi_tmpprodmaster a
		inner join product b on b.Code =a.ItemCode
		set a.ProductID = b.ID;";
			$rs2 = $this->database->execute($rs2);
			return $rs2;
	}
	public function spUpdateTmpProdMasterStep4()
	{	
		$rs3 = "update tpi_tmpprodmaster a
		inner join unittype b on b.Code =a.UOMCode
		set a.UnitTypeID = b.ID;";
			$rs = $this->database->execute($rs3);
			return $rs3;
	}
	public function spUpdateTmpProdMasterStep5()
	{	
		$rs4 = "update tpi_tmpprodmaster a
		inner join status b on b.Code = a.InvStatusCode
		set a.StatusID = b.ID;";
			$rs4 = $this->database->execute($rs4);
			return $rs4;
	}
	public function spUpdateTmpProdMasterStep6()
	{	
		$rs5 = "update tpi_tmpprodmaster a
		inner join value b on b.Code = a.BrandCode and b.FieldID = 8
		set a.BrandValueID = b.ID;";
		$rs = $this->database->execute($rs5);
		return $rs5;
	}
	public function spUpdateTmpProdMasterStep7()
	{		
		$rs6 = "update tpi_tmpprodmaster a
		inner join value b on b.Code = a.FormCode and b.FieldID = 9
		set a.FormValueID = b.ID;";
			$rs6 = $this->database->execute($rs6);
			return $rs6;
	}
	public function spUpdateTmpProdMasterStep8()
	{	
		$rs7 = "update tpi_tmpprodmaster a
		inner join producttype b on b.Code = a.ProductTypeCode
		set a.ProductTypeID = b.ID;";
			$rs7 = $this->database->execute($rs7);
			return $rs7;
	
		//$sp = "truncate table tpi_tmpprodmaster;"; 	
		//$rs = $this->database->execute($sp);
		//return $rs;
	}
	public function spSetForeignKeyChecks()
	{
		$rs = "SET FOREIGN_KEY_CHECKS = 0;";
		$rs = $this->database->execute($rs);
		return $rs;
	}
	public function spInsertFromTmpProdMaster1()
	{
		$rs1 = "insert into unittype (Code, Name, Changed)
		select distinct UOMCode, UOMCode, 1
		from tpi_tmpprodmaster pd
		left join unittype ut on ut.Code = pd.UOMCode
		where ut.ID is NULL;";
		$rs1 = $this->database->execute($rs1);
		return $rs1;
	}
	public function spUpdateProduct()
	{	
		$rs2 = "update product p
		inner join tpi_tmpprodmaster tpm on tpm.ProductID = p.ID
		set
			p.Code = tpm.ItemCode,
			p.Name = tpm.Description,
			p.ShortName = tpm.Description,
			p.Description = tpm.Description,
			p.ProductTypeID = tpm.ProductTypeID,
			p.UnitTypeID = tpm.UnitTypeID,
			p.StatusID = tpm.StatusID,
			p.LastModifiedDate = now(),
			p.Changed = 1,
			p.LaunchDate = tpm.LastReceiptDate,
			p.LastPODate = tpm.LastPODate
		where p.ID = tpm.ProductID;";
			$rs2 = $this->database->execute($rs2);
			return $rs2;
	}
	public function spInertinToProduct()
	{
		$rs3 = "insert into product (Code, Name, ShortName, Description, ProductLevelID, ParentID, ProductTypeID,UnitTypeID, Lt, Rt, StatusID, EnrollmentDate, LastModifiedDate, Changed, LaunchDate, LastPODate)
		select distinct tpm.ProductLineCode ItemCode, tpm.ProductLineCode Name, tpm.ProductLineCode ShortName, tpm.ProductLineCode description, 2, a.ID ParentID, 1, 1, 1, 1, 10, Now(), Now(), 1, Now(), Now()
		from tpi_tmpprodmaster tpm
			left join product p on p.Code = tpm.ProductLineCode
			inner join (select ID from product where productlevelid = 1) a
		where p.ID is null;";
			$rs3 = $this->database->execute($rs3);
			return $rs3;
	}
	
	public function spInertinToProductv1()
	{	
		$rs4 = "insert into product (Code, Name, ShortName, Description, ProductLevelID, ParentID, ProductTypeID, UnitTypeID, Lt, Rt, StatusID, EnrollmentDate, LastModifiedDate, Changed, LaunchDate, LastPODate)
		select tpm.ItemCode, tpm.Description, tpm.Description, tpm.Description, 3, p.ID ParentID, tpm.ProductTypeID, tpm.UnitTypeID, 1, 1, tpm.StatusID, Now(), Now(), 1, tpm.LastReceiptDate, tpm.LastPODate
		from tpi_tmpprodmaster tpm
			inner join product p on p.Code = tpm.ProductLineCode
			inner join tpi_pmg tp on tp.Code = tpm.PMGCode
		where tpm.ProductID is null and tpm.UnitTypeID is not null and tpm.ProductTypeID is not null and tp.ID is not null;";
			$rs4 = $this->database->execute($rs4);
			return $rs4;
	}
	//spInsertFromTmpProdMaster5
	public function spUpdateTpiTmpProdMaster()
	{	
		$rs5 = "update tpi_tmpprodmaster a
				inner join product b on b.Code =a.ItemCode
		set a.ProductID=b.ID
		where a.ProductID is null;";
			$rs5 = $this->database->execute($rs5);
			return $rs5;
	}
	//spInsertFromTmpProdMaster6
	public function spInsertProductPricing()
	{
		$rs6 = "insert into productpricing (ProductID, UnitTypeID, UnitPrice, PMGID, EnrollmentDate, LastModifiedDate, Changed)
		select tpm.ProductID, tpm.UnitTypeID, tpm.CSP, pmg.id ,Now(), Now(), 1
		from tpi_tmpprodmaster tpm
			inner join tpi_pmg pmg on pmg.Code = tpm.PMGCode
		where tpm.ProductID not in (select ProductID from productpricing) and tpm.ProductID is not null;";
			$rs6 = $this->database->execute($rs6);
			return $rs6;
	}
	//spInsertFromTmpProdMaster7
	public function spUpdateProductPricing()
	{	
		$rs7 = "update productpricing pp
				inner join tpi_tmpprodmaster tmp on tmp.ProductID = pp.ProductID
				inner join tpi_pmg tp on tp.Code = tmp.PMGCode
		set
			pp.UnitPrice = tmp.CSP,
			pp.PMGID = tp.ID,
			pp.LastModifiedDate = now(),
			pp.Changed = 1;";
			$rs7 = $this->database->execute($rs7);
			return $rs7;
	}
	//spInsertFromTmpProdMaster8
	public function spInsertProductCost()
	{
		$rs8 = "insert into productcost (ProductID, UnitTypeID, UnitCost, EnrollmentDate, LastModifiedDate, Changed)
		select tpm.ProductID, tpm.UnitTypeID, tpm.ProductCost, Now(), Now(), 1
		from tpi_tmpprodmaster tpm
		where tpm.ProductID not in (select ProductID from productcost) and tpm.ProductID is not null;";
			$rs8 = $this->database->execute($rs8);
			return $rs8;
	}
	//spInsertFromTmpProdMaster9
	public function spUpdateProductCost()
	{	
		$rs9 = "update productcost pp
				inner join tpi_tmpprodmaster tmp on tmp.ProductID = pp.ProductID
		set
			pp.UnitCost = tmp.ProductCost,
			pp.LastModifiedDate = now(),
			pp.Changed = 1
		where tmp.ProductCost != pp.UnitCost;";
			$rs9 = $this->database->execute($rs9);
			return $rs9;
	}
	//spInsertFromTmpProdMaster10
	public function spInsertProductDetails()
	{	
		$rs10 = "insert into productdetails (ProductID, FieldID, ValueID, Details, EnrollmentDate, LastModifiedDate, Changed)
		select tpm.ProductID, 8, tpm.BrandValueID, null, now(), now(), 1
		from tpi_tmpprodmaster tpm
			inner join `value` v on v.ID = tpm.BrandValueID and v.FieldID = 8
		where tpm.ProductID not in (select ProductID from productdetails where FieldID = 8) and tpm.ProductID is not null;";
			$rs10 = $this->database->execute($rs10);
			return $rs10;
	}
	//spInsertFromTmpProdMaster11
	public function UpdateProductDetails()
	{	
		$rs11 = "update productdetails pp
				inner join tpi_tmpprodmaster tmp on tmp.ProductID = pp.ProductID
		set
			pp.ValueID = tmp.BrandValueID,
			pp.LastModifiedDate = now(),
			pp.Changed = 1
		where tmp.BrandValueID = pp.ValueID and pp.FieldID = 8;";
			$rs11 = $this->database->execute($rs11);
			return $rs11;
	}
	//spInsertFromTmpProdMaster12
	public function spInsertProductDetailsv1()
	{	
		$rs12 = "insert into productdetails (ProductID, FieldID, ValueID, Details, EnrollmentDate, LastModifiedDate, Changed)
		select tpm.ProductID, 9, tpm.FormValueID, null, now(), now(), 1
		from tpi_tmpprodmaster tpm
			inner join `value` v on v.ID = tpm.FormValueID and v.FieldID = 9
		where tpm.ProductID not in (select ProductID from productdetails where FieldID = 9) and tpm.ProductID is not null;";
			$rs12 = $this->database->execute($rs12);
			return $rs12;
	}
	//spInsertFromTmpProdMaster13
	public function UpdateProductDetailsv1()
	{
		$rs13 = "update productdetails pp
				inner join tpi_tmpprodmaster tmp on tmp.ProductID = pp.ProductID
		set
			pp.ValueID = tmp.FormValueID,
			pp.LastModifiedDate = now(),
			pp.Changed = 1
		where tmp.FormValueID = pp.ValueID and pp.FieldID = 9;";
			$rs13 = $this->database->execute($rs13);
			return $rs13;
	}
	//spInsertFromTmpProdMaster14
	public function spInsertinventorybeginningbalance()
	{	
		$rs14 =  "insert into inventorybeginningbalance(WarehouseID, ClassID, ProductID, BeginningQty, EnrollmentDate, LastModifiedDate)
		select 1, 1, p.ID, 0, now(), now() from product p
				left join inventorybeginningbalance ibb on ibb.ProductID = p.ID
		where   p.ProductLevelID = 3 and ibb.ProductID is null
		union all
		select 2, 1, p.ID, 0, now(), now() from product p
				left join inventorybeginningbalance ibb on ibb.ProductID = p.ID
		where   p.ProductLevelID = 3 and ibb.ProductID is null;";
			$rs14 = $this->database->execute($rs14);
			return $rs14;
	}
	//spInsertFromTmpProdMaster15
	public function spInsertinventorybalance()
	{	
		$rs15 = "insert into inventorybalance(WarehouseID, ClassID, ProductID, SOH, AllocatedSOH, UnitCost, EnrollmentDate, LastModifiedDate)
		select 1, 1, p.ID, 0, 0, 0, now(), now() from product p
				left join inventorybalance ib on ib.ProductID = p.ID
		where   p.ProductLevelID = 3 and ib.ProductID is null
		union all
		select 2, 1, p.ID, 0, 0, 0, now(), now() from product p
				left join inventorybalance ib on ib.ProductID = p.ID
		where   p.ProductLevelID = 3 and ib.ProductID is null;";
			$rs15 = $this->database->execute($rs15);
			return $rs15;
	}
	//spInsertFromTmpProdMaster16
	public function spInsertIntoInventory()
	{	
		$rs16 = "insert into inventory(WarehouseID, ProductID, BatchNo, LotNo, SOH, InTransit, EnrollmentDate, LastModifiedDate)
		select 1, p.ID, null, null, 0, 0, now(), now() from product p
				left join inventory i on i.ProductID = p.ID
		where p.StatusID = 10 and p.ProductLevelID = 3 and i.ProductID is null
		union all
		select 2, p.ID, null, null, 0, 0, now(), now() from product p
				left join inventory i on i.ProductID = p.ID
		where   p.ProductLevelID = 3 and i.ProductID is null;";
		$rs16 = $this->database->execute($rs16);
		return $rs16;
	}


?>