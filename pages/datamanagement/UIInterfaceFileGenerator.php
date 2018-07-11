<?php

include "../../initialize.php";
include CS_PATH.DS."UIInterfaceFileGeneratorClass.php";

$runningDate = date("Y-m-d", strtotime($_POST['RunningDate']));

if(isset($_POST['EOD'])){
    //UI Interface for SID

    echo "<p class='titlefileheader'><b>EOD - SID File Generation</b></p>";
    //path for saving generated files from query
    $path = "../../SIDDays";

    //query for branch SID
    $branch = $uiInterface->branch();
    if($branch->num_rows){
        while($row = $branch->fetch_object()){

            //create dir if not exist
            if(!is_dir($path.DS.$row->Code)){
                mkdir($path.DS.$row->Code, 0777);
                //echo "New folder ".$row->Code." created.<br />";
            }
            $dir = $path.DS.$row->Code;
        }
    }
        
    //query for branch SID
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating D1 files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;
			
            //call a function to load query for D1
            $d1 = $uiInterface->callD1($row->ID, $runningDate);
            if($d1->num_rows){
                $counter = 1;

                //check if file was existed if not then create new file and save under the branch named folder
                //if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."D1.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."D1.".$row->Code, 'w');
					$totalDGS = 0;
					$totalInvoice = 0;
					$totalQuantity = 0;
					
                    while($res = $d1->fetch_object()){
                        fwrite($filegenerated, "\"".$res->PMGCode."\" ");
                        fwrite($filegenerated, number_format($res->TotalDGS, 2, '.', '')." ");
                        fwrite($filegenerated, number_format($res->TotalInvfaceoice, 2, '.', '')." ");
                        fwrite($filegenerated, $res->Quantity."\r\n");
						
						/*if($res->PMGCode != 'CPI'){
							$totalDGS += $res->TotalDGS;
							$totalInvoice += $res->TotalInvfaceoice;
							$totalQuantity += $res->Quantity;
						}*/

                        if($counter == $d1->num_rows){
                            fwrite($filegenerated, "\"All\" ");							
                            fwrite($filegenerated, $res->ProcessPO."\r\n");
                        }
						
                        $counter++;
                    }
                    fclose($filegenerated);
                //}
                echo "<p>".date("mdy", strtotime($runningDate))."D1.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."D1.".$row->Code." file.</p>";
            }//end of query for D1
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }

    //query for branch SID
	$counterx = 0;
    $branch = $uiInterface->branch();
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating D3 files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for PFL
            $d3 = $uiInterface->callD3($row->ID, $runningDate);
            if($d3->num_rows){
                //if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."D3.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."D3.".$row->Code, 'w');
                    while($res = $d3->fetch_object()){
                        fwrite($filegenerated, "\"".$res->BranchCode."\" ");
						fwrite($filegenerated, $res->TxnDate." ");
                        fwrite($filegenerated, number_format($res->OrderAmount, 2, '.', '')." ");
                        fwrite($filegenerated, number_format($res->InvoiceAmount, 2, '.', '')." ");
                        fwrite($filegenerated, $res->SalesUnit." ");
                        fwrite($filegenerated, $res->ProcessedPO." ");
                        fwrite($filegenerated, $res->IBMCFFNo." ");
                        fwrite($filegenerated, $res->ActiveIBMCFFNo." ");
                        fwrite($filegenerated, $res->TerminatedIBMCFFNo." ");
                        fwrite($filegenerated, $res->IBMCNo." ");
                        fwrite($filegenerated, $res->ActiveIBMCNo." ");
                        fwrite($filegenerated, $res->TerminatedIBMNo." ");
                        fwrite($filegenerated, $res->BeginningCounts." ");
                        fwrite($filegenerated, $res->NewRecruits." ");
                        fwrite($filegenerated, $res->AddOther." ");
						fwrite($filegenerated, $res->TerminatedAccounts." ");
                        fwrite($filegenerated, $res->RemovedOthers." ");
                        fwrite($filegenerated, number_format($res->OrderAmount2, 2, '.', '')." ");
                        fwrite($filegenerated, $res->BeginningCounts2." ");
                        fwrite($filegenerated, $res->NewRecruitsOther." ");
                        fwrite($filegenerated, $res->AddOther2." ");
                        fwrite($filegenerated, $res->TerminatedAccounts2." ");
                        fwrite($filegenerated, $res->RemovedOthers2." ");
                        fwrite($filegenerated, $res->OrderAmountBCCustomerType." ");
                        fwrite($filegenerated, $res->DLStartDateNo." ");
                        fwrite($filegenerated, $res->DLCampDateNo." ");
                        fwrite($filegenerated, $res->BCStartDateNo." ");
                        fwrite($filegenerated, $res->BCCampDateNo." ");
                        fwrite($filegenerated, $res->ActivesNo." ");
                        fwrite($filegenerated, $res->CBCActives." ");
                        fwrite($filegenerated, $res->Reactivated." ");
                        fwrite($filegenerated, $res->CBCReactivated." ");
                        fwrite($filegenerated, $res->igsndgs." ");
                        fwrite($filegenerated, $res->bfcndgs." ");
                        fwrite($filegenerated, $res->ActiveDealers." ");
                        fwrite($filegenerated, $res->ActiveBC."\r\n");
                    }
                    fclose($filegenerated);
                //}
                echo "<p>".date("mdy", strtotime($runningDate))."D3.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."D3.".$row->Code." file.</p>";
            }                
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    
    //query for branch SID
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating PFL files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for PFL
            $pfl = $uiInterface->callPFL($row->ID, $runningDate);
            if($pfl->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."PFL")){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."PFL", 'w');
                    while($res = $pfl->fetch_object()){
                        fwrite($filegenerated, "\"".$res->branchCode."\" ");
                        fwrite($filegenerated, "\"".$res->DealerCode."\" ");
                        fwrite($filegenerated, "\"".$res->DealerLName."\" ");
                        fwrite($filegenerated, "\"".$res->DealerFName."\" ");
                        fwrite($filegenerated, "\"".$res->Address1."\" ");
                        fwrite($filegenerated, "\"".$res->Address2."\" ");
                        fwrite($filegenerated, "\"".$res->Address3."\" ");
                        fwrite($filegenerated, "\"".$res->Address4."\" ");
                        fwrite($filegenerated, "\"".$res->Birthdate."\" ");
                        fwrite($filegenerated, "\"".$res->MobileNo."\" ");
                        fwrite($filegenerated, "\"".$res->TelNo."\" ");
                        fwrite($filegenerated, "\"".$res->DealerName."\" ");
                        fwrite($filegenerated, "\"".$res->Gender."\" ");
                        fwrite($filegenerated, "\"".$res->MaritalStatus."\" ");
                        fwrite($filegenerated, "\"".$res->GSUType."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerType."\" ");
                        fwrite($filegenerated, "\"".$res->IBMCode."\" ");
                        fwrite($filegenerated, "\"".$res->RepCode."\" ");
                        fwrite($filegenerated, "\"".$res->ZipCode."\"\r\n");
                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate)).".".$row->Code."PFL"." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate)).".".$row->Code."PFL"." file.</p>";
            }                
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    
    //query for branch SID
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating IST files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for IST
            $ist = $uiInterface->callIST($row->ID, $runningDate);
            if($ist->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."IST")){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."IST", 'w');
                    while($res = $ist->fetch_object()){
                        fwrite($filegenerated, "'".$res->BranchName."' ");
                        fwrite($filegenerated, "'".$res->SystemBranchName."' ");
                        fwrite($filegenerated, "'".$res->UnderSystemMaintenanceTable."' ");
                        fwrite($filegenerated, "'".$res->BackCC."' ");
                        fwrite($filegenerated, $res->OrderDate." ");
                        fwrite($filegenerated, "'".$res->Campaign."' ");
                        fwrite($filegenerated, "'".$res->CreditNote."' ");
                        fwrite($filegenerated, "'".$res->InvoiceNo."' ");
                        fwrite($filegenerated, $res->Stage." ");
                        fwrite($filegenerated, "'".$res->SOType."' ");
                        fwrite($filegenerated, "'".$res->DealerCode."' ");
                        fwrite($filegenerated, "'".$res->DealerName."' ");
                        fwrite($filegenerated, "'".$res->CreditTerm."' ");
                        fwrite($filegenerated, "'".$res->CreditTermDesc."' ");
                        fwrite($filegenerated, "'".$res->SalesRepCode."' ");
                        fwrite($filegenerated, "'".$res->SalesRepName."' ");
                        fwrite($filegenerated, "'".$res->RecruiterCode."' ");
                        fwrite($filegenerated, "'".$res->RecruiterName."' ");
                        fwrite($filegenerated, "'".$res->ProductCode."' ");
                        fwrite($filegenerated, "'".$res->ProductDesc."' ");
                        fwrite($filegenerated, "'".$res->ProductLine."' ");
                        fwrite($filegenerated, "'".$res->ProductLineDesc."' ");
                        fwrite($filegenerated, "'".$res->ProductStatus."' ");
                        fwrite($filegenerated, "'".$res->ProductStatusCode."' ");
                        fwrite($filegenerated, "'".$res->PMGCode."' ");
                        fwrite($filegenerated, $res->LineCode." ");
                        fwrite($filegenerated, $res->ProductPrice." ");
                        fwrite($filegenerated, $res->QuantityNo." ");
                        fwrite($filegenerated, $res->OfferDiscount." ");
                        fwrite($filegenerated, $res->TotalCSP." ");
                        fwrite($filegenerated, $res->CSPLessCPI." ");
                        fwrite($filegenerated, $res->DGSAmount." ");
                        fwrite($filegenerated, $res->BasicDiscount." ");
                        fwrite($filegenerated, $res->AdditionalDiscount." ");
                        fwrite($filegenerated, $res->TotalAdjustment."\r\n");
                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate)).".".$row->Code."IST"." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate)).".".$row->Code."IST"." file.</p>";
            }                
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    
    //query for branch SID
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating CST files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for CST
            $pfl = $uiInterface->callCST($row->ID, $runningDate);
            if($pfl->num_rows){
                //if(!file_exists($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."CST")){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."CST", 'w');
                    while($res = $pfl->fetch_object()){
                        fwrite($filegenerated, "\"".$res->BranchCode."\" ");
                        fwrite($filegenerated, "\"".$res->SystemBranchName."\" ");
                        fwrite($filegenerated, $res->StatusDate." ");
                        fwrite($filegenerated, "\"".$res->CustomerCode."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerType."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerTypeDescription."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerLastName."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerFirstName."\" ");
                        fwrite($filegenerated, "\"".$res->Status."\" ");
                        fwrite($filegenerated, "\"".$res->StatusDescription."\" ");
                        fwrite($filegenerated, "\"".$res->RecruitedBy."\" ");
                        fwrite($filegenerated, "\"".$res->IBMName."\" ");						
                        fwrite($filegenerated, "\"".$res->SalesRep."\" ");
                        fwrite($filegenerated, "\"".$res->RecruiterName."\" ");
                        fwrite($filegenerated, $res->ZipCode." ");
                        fwrite($filegenerated, $res->Birthdate." ");
                        fwrite($filegenerated, "\"".$res->CreditTerms."\" ");
                        fwrite($filegenerated, "\"".$res->CreditTermDescription."\" ");
                        fwrite($filegenerated, $res->MaxCreditLimit." ");
                        fwrite($filegenerated, $res->StatusDate." ");
                        fwrite($filegenerated, "\"".$res->TransactionNo."\" ");
                        fwrite($filegenerated, "\"".$res->DescriptionOfPADRAR."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerBalance."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerOvedueBalance."\" ");
                        fwrite($filegenerated, $res->GSU." ");
                        fwrite($filegenerated, $res->dswkfl."\r\n");
                    }
                    fclose($filegenerated);
                //}
                echo "<p>".date("mdy", strtotime($runningDate)).".".$row->Code."CST"." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate)).".".$row->Code."CST"." file.</p>";
            }                
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    
    //query for branch SID
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating DST files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for DST
            $pfl = $uiInterface->callDST($row->ID, $runningDate);
            if($pfl->num_rows){
                //if(!file_exists($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."DST")){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."DST", 'w');
                    while($res = $pfl->fetch_object()){
                        fwrite($filegenerated, "\"".$res->BranchCode."\" ");
                        fwrite($filegenerated, "\"\" ");
                        fwrite($filegenerated, "\"".$res->vmin."\" ");
                        fwrite($filegenerated, $res->SalesOrderDate." ");
                        fwrite($filegenerated, "\"".$res->Campaign."\" ");
                        fwrite($filegenerated, "\"".$res->BankCC."\" ");
                        fwrite($filegenerated, "\"".$res->CreditNote."\" ");
						fwrite($filegenerated, "\"".$res->SINumber."\" ");
                        fwrite($filegenerated, $res->Stage." ");
                        fwrite($filegenerated, "\"".$res->SalesOrderType."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerCode."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerName."\" ");
                        fwrite($filegenerated, "\"".$res->CreditTermCode."\" ");
                        fwrite($filegenerated, "\"".$res->CreditTermName."\" ");
						fwrite($filegenerated, "\"".$res->IBMCode."\" ");
						fwrite($filegenerated, "\"".$res->IBMName."\" ");
						fwrite($filegenerated, "\"".$res->RecruiterCode."\" ");
						fwrite($filegenerated, "\"".$res->RecruiterName."\" ");
                        fwrite($filegenerated, $res->TotalCSP." ");
                        fwrite($filegenerated, $res->TotalCSPLessCPI." ");
                        fwrite($filegenerated, $res->DiscountedGrossAmount." ");
                        fwrite($filegenerated, $res->BasicDiscount." ");
                        fwrite($filegenerated, $res->AdditionalDiscount." ");
                        fwrite($filegenerated, $res->SalesWithVat." ");
                        fwrite($filegenerated, $res->VatPercentage." ");
                        fwrite($filegenerated, $res->VatableSales." ");
                        fwrite($filegenerated, $res->TotalAdjustment." ");
                        fwrite($filegenerated, $res->TotalNSV." ");
                        fwrite($filegenerated, $res->TotalSalesInvoice." ");
                        fwrite($filegenerated, "\"".$res->CreditForInvoice."\" ");
                        fwrite($filegenerated, $res->SalesOrderDate2."\r\n");
                    }
                    fclose($filegenerated);
                //}
                echo "<p>".date("mdy", strtotime($runningDate)).".".$row->Code."DST"." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate)).".".$row->Code."DST"." file.</p>";
            }                
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    
    //query for branch SID
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating OPT files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for OPT
            $opt = $uiInterface->callOPT($row->ID, $runningDate);
            if($opt->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."OPT")){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate)).".".$row->Code."OPT", 'w');
                    while($res = $opt->fetch_object()){
                        fwrite($filegenerated, "\"".$res->BranchCode."\" ");
                        fwrite($filegenerated, "\"".$res->SystemBranchName."\" ");
                        fwrite($filegenerated, $res->EffectiveDate." ");
                        fwrite($filegenerated, "\"".$res->PaymentType."\" ");
                        fwrite($filegenerated, "\"".$res->MemoNo."\" ");
                        fwrite($filegenerated, "\"".$res->AREntryNo."\" ");
                        fwrite($filegenerated, "\"".$res->CollectionReceipt."\" ");
                        fwrite($filegenerated, "\"".$res->DealerCode."\" ");
                        fwrite($filegenerated, "\"".$res->DealerName."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerType."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerTypeDesc."\" ");
                        fwrite($filegenerated, "\"".$res->CustomerStatus."\" ");
                        fwrite($filegenerated, "\"".$res->StatusDescription."\" ");
                        fwrite($filegenerated, "\"".$res->ReasonCode."\" ");
                        fwrite($filegenerated, "\"".$res->ReasonName."\" ");
                        fwrite($filegenerated, "\"".$res->TradeNonTrade."\" ");
                        fwrite($filegenerated, $res->InvoiceAmount." ");
                        fwrite($filegenerated, $res->AppliedAmount." ");
                        fwrite($filegenerated, "\"".$res->Remarks1."\" ");
						fwrite($filegenerated, "\"".$res->Remarks2."\" ");
                        fwrite($filegenerated, "\"".$res->Voptcmp."\" ");
                        fwrite($filegenerated, $res->voptsfearned." ");
                        fwrite($filegenerated, $res->voptsfclaimed."\r\n");
                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate)).".".$row->Code."OPT"." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate)).".".$row->Code."OPT"." file.</p>";
            }                
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
}//end of query for branch

//==========================================================================================================

//UI Interface for EOM
if(isset($_POST['EOM'])){
    
    echo "<p class='titlefileheader'><b>EOM - SID File Generation</b></p>";
    
    //path for saving generated files from query
    $path = "../../EOMMonth";

    //query for branch EOM
    $branch = $uiInterface->branch();
    if($branch->num_rows){
        while($row = $branch->fetch_object()){

            //create dir if not exist
            if(!is_dir($path.DS.$row->Code)){
                mkdir($path.DS.$row->Code, 0777);
                //echo "New folder ".$row->Code." created.<br />";
            }
            $dir = $path.DS.$row->Code;
        }
    }
    
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating M1 files...</b></p>";
        while($row = $branch->fetch_object()){
            //call a function to load query for M1
            $m1 = $uiInterface->callM1($row->ID, $runningDate);
            if($m1->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."M1.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."M1.".$row->Code, 'w');
                    $holder = array();
                    while($res = $m1->fetch_object()){

                        if(!in_array($res->Campaign, $holder)){
                            fwrite($filegenerated, "'".$res->Campaign."' ");
                            fwrite($filegenerated, $res->TransactionDate."\r\n");
                            $holder[] = $res->Campaign;
                        }

                        fwrite($filegenerated, "'".$res->PMGType."' ");
                        fwrite($filegenerated, "'".$res->DL."' ");
                        fwrite($filegenerated, $res->Columna." ");
                        fwrite($filegenerated, $res->Columnb." ");
                        fwrite($filegenerated, $res->Columnc." ");
                        fwrite($filegenerated, $res->Columnd." ");
                        fwrite($filegenerated, $res->Columne." ");
                        fwrite($filegenerated, $res->Columnf." ");
                        fwrite($filegenerated, $res->Columng." ");
                        fwrite($filegenerated, $res->Columnh."\r\n");
                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate))."M1.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."M1.".$row->Code." file.</p>";
            }
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }


    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating M2 files...</b></p>";
        while($row = $branch->fetch_object()){       
            //call function to load query for m2
            $m2 = $uiInterface->callM2($row->ID, $runningDate);
            if($m5->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."M2.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."M2.".$row->Code, 'w');
                    while($res = $m2->fetch_object()){

                        if(!in_array($res->Campaign, $holder)){
                            fwrite($filegenerated, "'".$res->Campaign."' ");
                            fwrite($filegenerated, date('m/d/Y')."\r\n");
                            $holder[] = $res->Campaign;
                        }

                        fwrite($filegenerated, "'".$res->DealerCode."' ");
                        fwrite($filegenerated, "'".$res->DealerFullName."' ");
                        fwrite($filegenerated, date('m/d/Y',strtotime($res->TransactionDate))." ");
                        fwrite($filegenerated, "'".$res->MotherIBM."' ");
                        fwrite($filegenerated, $res->recruit." ");
                        fwrite($filegenerated, $res->Paid."\r\n");

                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate))."M2.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."M1.".$row->Code." file.</p>";
            }

        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
        
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating M5 files...</b></p>";
        while($row = $branch->fetch_object()){
            //call a function to load query for M5
            $m5 = $uiInterface->callM5($row->ID, $runningDate);
            if($m5->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."M5.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."M5.".$row->Code, 'w');
                    $holder = array();
                    while($res = $m5->fetch_object()){

                        if(!in_array($res->Campaign, $holder)){
                            fwrite($filegenerated, "'".$res->Campaign."' ");
                            fwrite($filegenerated, date('m/d/Y')."\r\n");
                            $holder[] = $res->Campaign;
                        }

                        fwrite($filegenerated, "'".$res->IBMCode."' ");
                        fwrite($filegenerated, "'".$res->PMGCode."' ");
                        fwrite($filegenerated, $res->DiscountedDGS."\r\n");

                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate))."M5.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."M5.".$row->Code." file.</p>";
            }
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating M6 files...</b></p>";
        while($row = $branch->fetch_object()){
            //call a function to load query for M6
            $m6 = $uiInterface->callM6($row->ID, $runningDate);
            if($m6->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."M6.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."M6.".$row->Code, 'w');
                    $holder = array();
                    while($res = $m6->fetch_object()){

                        if(!in_array($res->BranchCode, $holder)){
                            fwrite($filegenerated, "'".$res->BranchCode."' ");
                            fwrite($filegenerated, "'".$res->CampaignCode."' ");
                            fwrite($filegenerated, date('m/d/Y')."\r\n");
                            $holder[] = $res->BranchCode;
                        }

                        fwrite($filegenerated, "'".$res->IBMCode."' ");
                        fwrite($filegenerated, "'".$res->BranchCode."' ");
                        fwrite($filegenerated, $res->TotalDGSSales." ");
                        fwrite($filegenerated, $res->newRecruits." ");
                        fwrite($filegenerated, $res->Actives."\r\n");

                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate))."M6.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."M6.".$row->Code." file.</p>";
            }
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating M7 files...</b></p>";
        while($row = $branch->fetch_object()){
            //call a function to load query for M6
            $m6 = $uiInterface->callM7($row->ID, $runningDate);
            if($m6->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."M7.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."M7.".$row->Code, 'w');
                    $holder = array();
                    while($res = $m6->fetch_object()){

                        if(!in_array($res->BranchID, $holder)){
                            fwrite($filegenerated, "'".$res->CampaignCode."' ");
                            fwrite($filegenerated, $res->RunningDate."\r\n");
                            $holder[] = $res->BranchID;
                        }

                        fwrite($filegenerated, $res->TotalDGS." ");
                        fwrite($filegenerated, $res->TotalInvoiceAmount." ");
                        fwrite($filegenerated, $res->UnitSolds." ");
                        fwrite($filegenerated, $res->TotalNumberOfPO." ");
                        fwrite($filegenerated, $res->BeginningCount." ");
                        fwrite($filegenerated, $res->NewRecuits." ");
                        fwrite($filegenerated, $res->Reactivated." ");
                        fwrite($filegenerated, $res->AddOther." ");
                        fwrite($filegenerated, $res->Termination." ");
                        fwrite($filegenerated, $res->RemoveOther." ");
                        fwrite($filegenerated, $res->TotalSales." ");
                        fwrite($filegenerated, $res->TotalActive." ");
                        fwrite($filegenerated, $res->BeginningCount2." ");
                        fwrite($filegenerated, $res->NewRecruit2." ");
                        fwrite($filegenerated, $res->Reactivated2." ");
                        fwrite($filegenerated, $res->AddOther2." ");
                        fwrite($filegenerated, $res->Termination2." ");
                        fwrite($filegenerated, $res->RemoveOther2." ");
                        fwrite($filegenerated, $res->Totals." ");
                        fwrite($filegenerated, $res->Active2." ");
                        fwrite($filegenerated, $res->TotalSales."\r\n");

                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate))."M7.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."M7.".$row->Code." file.</p>";
            }
        }
		
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating M8 files...</b></p>";
        while($row = $branch->fetch_object()){
            //call a function to load query for M8
            $m8 = $uiInterface->callM8($row->ID, $runningDate);
            if($m8->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."M8.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."M8.".$row->Code, 'w');
                    while($res = $m8->fetch_object()){

                        fwrite($filegenerated, "'".$res->BranchCode."' ");
                        fwrite($filegenerated, "'".$res->Campaign."' ");
                        fwrite($filegenerated, "'".$res->PMGType."' ");
                        fwrite($filegenerated, $res->Minimum." ");
                        fwrite($filegenerated, $res->Maximum." ");
                        fwrite($filegenerated, $res->DealerCount." ");
                        fwrite($filegenerated, $res->DiscountedGrossSales." ");
                        fwrite($filegenerated, $res->DiscountTotal."\r\n");

                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate))."M8.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."M8.".$row->Code." file.</p>";
            }
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }


    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating M9 files...</b></p>";
        while($row = $branch->fetch_object()){
            //call a function to load query for M9
            $m9 = $uiInterface->callM9($row->ID, $runningDate);
            if($m9->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."M9.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."M9.".$row->Code, 'w');
                    while($res = $m9->fetch_object()){

                        fwrite($filegenerated, "'".$res->BranchCode."' ");
                        fwrite($filegenerated, $res->Minimum." ");
                        fwrite($filegenerated, $res->Maximum." ");
                        fwrite($filegenerated, $res->DealerCount." ");
                        fwrite($filegenerated, $res->DiscountedGrossSales."\r\n");

                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate))."M9.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."M9.".$row->Code." file.</p>";
            }
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }
    
    $branch = $uiInterface->branch();
	$counterx = 0;
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating M10 files...</b></p>";
        while($row = $branch->fetch_object()){
            //call function to load query for M10
            $m10 = $uiInterface->callM10($row->ID, $runningDate);
            if($m5->num_rows){
                if(!file_exists($dir.DS.date("mdy", strtotime($runningDate))."M10.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdy", strtotime($runningDate))."M10.".$row->Code, 'w');
                    $holder = array();
                    while($res = $m10->fetch_object()){

                        fwrite($filegenerated, "'".$res->Code."' ");
                        fwrite($filegenerated, "'".$res->Campaign."' ");
                        fwrite($filegenerated, $res->TerminatedWithin4Months." ");
                        fwrite($filegenerated, $res->TerminatedWithin8Months." ");
                        fwrite($filegenerated, $res->TerminatedWithin12Months." ");
                        fwrite($filegenerated, $res->TerminatedWithin13To24Months." ");
                        fwrite($filegenerated, $res->TerminatedWithin13To24MonthsNonGSU."\r\n");

                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdy", strtotime($runningDate))."M10.".$row->Code." file created.</p>";
				$counterx++;
            }else{
                //echo "<p>No details to create for ".date("mdy", strtotime($runningDate))."M10.".$row->Code." file.</p>";
            }
        }
		if($counterx == 0){
			echo "<p>No result found.</p>";
		}
    }    
    
}
?>
