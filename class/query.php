<?php

class Queries{

	public function SelectProductList ($offset, $perpage)
	{
		$qry = "select distinct p.ID PID, p.Code PCode, p.Name PName, i.ID InvID, i.CompanyBatchNo Batch,
						sum(i.SOH) SOH, i.UnitCost, u.ID UOMID, u.Name uom, p.Multiplier
				from product p
				inner join inventory i on i.ProductID = p.ID
				inner join uom u on u.ID = p.UOMID
				group by PID
				order by p.Code, p.Name, Batch
				Limit $offset, $perpage;";
		return $qry;
	}
	
	public function SelectProductListCount ()
	{
		$qry = "select count(distinct p.id) AS numrows
				from product p
				inner join inventory i on i.ProductID = p.ID
				inner join uom u on u.ID = p.UOMID;";
		return $qry;
	}
	
	public function SelectViewAdjustment ($offset, $perpage, $vsearch)
	{
		if ($vsearch == '')
		{
		$qry = "select cast(concat('AD', repeat('0', (8- length(t.ID))), t.ID) as char) TxnNo, t.ID TxnID,
						t.DocumentNo, t.TxnDate, w.Name warehouse, i.Name itemtype, c.Name classname, tx.Name Status
				  from adjustment t
				  inner join warehouse w on w.ID = t.WarehouseID
				  inner join class c on c.ID = t.ClassID
				  inner join itemtype i on i.ID = t.ItemTypeID
				  inner join txnstatus tx on tx.ID = t.TxnStatusID
				  group by t.ID order by t.ID desc
				  Limit $offset, $perpage;";
		}
		else
		{
		$qry = "select cast(concat('AD', repeat('0', (8- length(t.ID))), t.ID) as char) TxnNo, t.ID TxnID,
						t.DocumentNo, t.TxnDate, w.Name warehouse, i.Name itemtype, c.Name classname, tx.Name Status
				  from adjustment t
				  inner join warehouse w on w.ID = t.WarehouseID
				  inner join class c on c.ID = t.ClassID
				  inner join itemtype i on i.ID = t.ItemTypeID
				  inner join txnstatus tx on tx.ID = t.TxnStatusID
				  where  (concat('AD', repeat('0', (8- length(t.ID))), t.ID) like upper('%$vsearch%')
				  or w.Name like upper('%$vsearch%')
				  or t.DocumentNo like upper('%$vsearch%')
				  or i.Name like upper('%$vsearch%')
				  or c.Name like upper('%$vsearch%')
				  or tx.Name like upper('%$vsearch%'))
				  group by t.ID order by t.ID desc
				   Limit $offset, $perpage;";
		}
		return $qry;
	}
	
	public function SelectViewAdjustmentCount ($vsearch)
	{
		if ($vsearch == '')
		{
		$qry = "select count(distinct t.ID) AS numrows
			  from adjustment t
			  inner join warehouse w on w.ID = t.WarehouseID
			  inner join class c on c.ID = t.ClassID
			  inner join itemtype i on i.ID = t.ItemTypeID
			  inner join txnstatus tx on tx.ID = t.TxnStatusID;";
		}
		else
		{
		$qry = "select count(distinct t.ID) AS numrows
			  from adjustment t
			  inner join warehouse w on w.ID = t.WarehouseID
			  inner join class c on c.ID = t.ClassID
			  inner join itemtype i on i.ID = t.ItemTypeID
			  inner join txnstatus tx on tx.ID = t.TxnStatusID
			  where  (concat('AD', repeat('0', (8- length(t.ID))), t.ID) like upper('%$vsearch%')
				  or w.Name like upper('%$vsearch%')
				  or t.DocumentNo like upper('%$vsearch%')
				  or i.Name like upper('%$vsearch%')
				  or c.Name like upper('%$vsearch%')
				  or tx.Name like upper('%$vsearch%'));";	  
		}
		return $qry;
	}
	
	public function SelectOfficialReceiptDetails($orid){

		$str = "select ID, OfficialReceiptID, ORReferenceType, RefTxnID, OutstandingBalance, TotalAmount
				from officialreceiptdetails
				where OfficialReceiptID = $orid;";
		
		return $str;
	}
	
	public function SelectOfficialReceipt($orid){

		$str = "select  distinct orp.ID OfficialReceiptID,
						orc.ID OfficialReceiptCashID,
						orch.ID OfficialReceiptCheckID, orch.CheckNumber,
						ord.ID OfficialReceiptDepositID, ord.DepositSlipNo,
						orp.CustomerID, c.Code CustomerCode, c.Name CustomerName, c.LastName, c.FirstName, c.MiddleName,
						orp.DocumentNo,
						orp.TotalAmount, orp.TotalAppliedAmt, orp.TotalUnappliedAmt,
						orp.Remarks,
						orp.PrintCounter,
						Date_Format(orp.TxnDate, '%m/%d/%Y') TxnDate, Date_Format(orp.EnrollmentDate, '%h:%i %p') TxnTime
				from officialreceipt orp
				inner join customer c on c.ID = orp.CustomerID
				left join officialreceiptcash orc on orc.OfficialReceiptID = orp.ID
				left join officialreceiptcheck orch on orch.OfficialReceiptID = orp.ID
				left join officialreceiptdeposit ord on ord.OfficialReceiptID = orp.ID
				where orp.ID = $orid;";
		
		return $str;
	}
	
		public function SelectBranch(){
			
		$str = "Select b.ID, b.Code, b.Name, b.StreetAdd,
					   bp.TIN, bp.PermitNo, bp.ServerSN
				from branch b
				inner join branchparameter bp on bp.BranchID = b.ID
				limit 1;";	
				  
		return $str;
	}
	
	

   public function SelectSalesInvoice($siid){
         
      $str = "select si.ID SalesInvoiceID, si.CustomerID, si.RefTxnID, si.DocumentNo,
                   Date_Format(si.TxnDate, '%m/%d/%Y') TxnDate, si.EffectivityDate, si.GrossAmount, si.BasicDiscount, si.VatAmount, si.TotalCPI,
                   si.AddtlDiscount, si.AddtlDiscountPrev, si.NetAmount, si.OutstandingBalance, si.CreatedBy, si.ConfirmedBy,si.PrintCounter,
                   si.EnrollmentDate
              from salesinvoice si
              inner join customer c on c.ID = si.CustomerID
              where si.ID = $siid;";   
      
      return $str;
      
   }  
   
   public function SelectCustomerDetails($cid){
      
      $str = "select c.ID, c.RefID, c.Code, c.Name, c.LastName, c.FirstName, c.MiddleName,
                  trc.IBMID, c2.Code IBMCode, c.TIN
            from customer c
            inner join tpi_rcustomeribm trc on trc.CustomerID = c.ID
            inner join customer c2 on c2.ID = trc.IBMID
            where c.ID = $cid;"; 
      
      return $str;
      
   }

   public function SelectProductSalesInvoice($siid){
      
      $str = "select distinct sid.SalesInvoiceID,
                  sid.ProductID, p.Code ProductCode, p.Name Product, p.ShortName,
                  sid.UnitTypeID, sid.PromoID, sid.PromoType, sid.PMGID,
                  sid.UnitPrice, sid.Qty, sid.TotalAmount, sid.LineNo
            from salesinvoice si
            inner join salesinvoicedetails sid on sid.SalesInvoiceID = si.ID
            inner join product p on p.ID = sid.ProductID
            where si.ID = $siid;";  
      
      return $str;
      
   }	
   
   public function UpdateSalesInvoiceOfficialReceiptPrintCounter($txnId, $txnTable) {

      $str = "update $txnTable set PrintCounter = PrintCounter + 1 where ID = $txnId";

      return $str;
   }   
	
}

$qrystr = new Queries();

?>