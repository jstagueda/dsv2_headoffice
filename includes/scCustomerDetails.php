<?php

	$classid=0;
	$customertypeid=0;
	$fieldid='';
	$efieldid='';
	$length=0;
	$zoneid=0;
	$customerRemarks='';
	$yesno=0;
	$eyesno=0;
	$credittermid=1;
	$company='';
	$credittermid = 0;
	$character = 0;
    $capacity = 0;
    $capital = 0;
    $condition = 0;
    $calculatedcl = 0;
    $recommendedcl = 0;
    $approvedcl = 0;
    $accountno='';
    $recruitername='';
	$recruitermobileno='';
	$provinceid ='';
	$townid ='';
	$barangayid='';
	$zonename='';
	$gsuname='';
	$custtypename='';
	$cclassname='';
	$crtname='';
	$pdacode='';
	
	if(isset($_GET['custid']))
	{
		$custid = $_GET['custid'];
	
		$rs = $sp->spSelectCustomer($database,$custid, "");
		$rsFile = $sp->spSelectApplicationFile($database, $custid);
	
		if ($rs->num_rows)
		{
			while ($row = $rs->fetch_object())
			{			
				$igscode=$row->Code;
				$name=$row->Name;
				$lname = $row->LastName;
				$fname = $row->FirstName;
				$mname = $row->MiddleName;
				$tmpbday = strtotime($row->BirthDate);
				$bday = date("m/d/Y", $tmpbday);	
				$classid = $row->CustomerClassID;
				$customertypeid = $row->CustomerTypeID;	
				$gsutypeid = $row->GSUTypeID;
				$ibmcode = $row->IBMCode;
				$ibmname = $row->IBMName;
				$refid = $row->RefID;
				$zoneid = $row->ZoneID;
				$customerRemarks = $row->Remarks;
				$zonename = $row->zonename;
				$gsuname = $row->gsuname;
				$custtypename = $row->custtypename;
				$cclassname = $row->cclassname;
				$cstatusid = $row->CustomerStatusID;
			}
		}
		
		$rsExist = $sp->spSelectExistingCustomer($database, $custid, '', '', '', '');
		
		if ($rsExist->num_rows)
		{
			while ($row = $rsExist->fetch_object())
			{			
				$code= $row->Code;
				$nickname=$row->NickName;
				$telno = $row->TelNo;	
				$mobileno = $row->MobileNo;
				$address = $row->StreetAdd;
				$zipcode = $row->ZipCode;
				$credittermid = $row->CreditTermID;
				$character = $row->Character;
			    $capacity = $row->Capacity;
			    $capital = $row->Capital;
			    $condition = $row->Condition;
			    $calculatedcl = $row->CalculatedCL;
			    $recommendedcl = $row->RecommendedCL;
			    $approvedcl = $row->ApprovedCL;
			    $crtname = $row->crtname;
			    $accountno= $row->RecAcctNo;
				$recruitername= $row->RecName;
				$recruitermobileno= $row->RecMobileNo;	
				$dstatusid = $row->CustomerStatusID;
				$pdacode = $row->PDACode;	
			}
		}
		
		$rsExistMarital = $sp->spSelectMaritalStatus($database,$custid);
		if ($rsExistMarital->num_rows)
		{
			while ($row = $rsExistMarital->fetch_object())
			{			
				$fieldid= $row->Name;
			}
		}
		
		$rsExistEducational = $sp->spSelectEducationalAttainment($database,$custid);
		if ($rsExistEducational->num_rows)
		{
			while ($row = $rsExistEducational->fetch_object())
			{			
				$efieldid= $row->Name;
			}
		}
		
		$rsExistLength = $sp->spSelectLengthStay($database, $custid);
		if ($rsExistEducational->num_rows)
		{
			while ($row = $rsExistLength->fetch_object())
			{			
				$length= $row->details;
			}
		}
		
		$rsExistDirectSellExp = $sp->spSelectDirectSellExp($database, $custid);
		if ($rsExistDirectSellExp->num_rows)
		{
			while ($row = $rsExistDirectSellExp->fetch_object())
			{			
				$yesno= $row->YesNo;
			}
		}
		
		$rsExistEmploymentStatus = $sp->spSelectEmploymentStatus($database, $custid);
		if ($rsExistEmploymentStatus->num_rows)
		{
			while ($row = $rsExistEmploymentStatus->fetch_object())
			{			
				$eyesno= $row->YesNo;
			}
		}
		
		$rsExistDirectSellComp = $sp->spSelectDirectSellComp($database, $custid);
		$rsExistCompany = $sp->spSelectDealerCompany($database, $custid);
		if ($rsExistDirectSellComp->num_rows)
		{
			while ($row = $rsExistDirectSellComp->fetch_object())
			{			
				$company= $row->Details;
			}
		}
		
		$rsExistProvince = $sp->spSelectProvince($database, $custid);
		if ($rsExistProvince->num_rows)
		{
			while ($row = $rsExistProvince->fetch_object())
			{			
				$provinceid= $row->Name;
			}
		}
		
		$rsExistTownCity = $sp->spSelectTownCity($database, $custid, -1);
		if ($rsExistTownCity->num_rows)
		{
			while ($row = $rsExistTownCity->fetch_object())
			{			
				$townid= $row->Name;
			}
		}
		
		$rsExistBarangay = $sp->spSelectBarangay($database, $custid, -1);
		if ($rsExistBarangay->num_rows)
		{
			while ($row = $rsExistBarangay->fetch_object())
			{			
				$barangayid= $row->Name;
			}
		}
			
	}
	
?>