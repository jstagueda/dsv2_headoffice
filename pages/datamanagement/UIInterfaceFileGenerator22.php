<?php

include "../../initialize.php";
include "UIInterfaceFileGeneratorClass22.php";


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
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating D1 files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for D1
            $d1 = $uiInterface->callD1($row->ID);
            if($d1->num_rows){
                $counter = 1;

                //check if file was existed if not then create new file and save under the branch named folder
                if(!file_exists($dir.DS.date("mdY")."D1.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdY")."D1.".$row->Code, 'w');
                    while($res = $d1->fetch_object()){
                        fwrite($filegenerated, "'".$res->PMGCode."' ");
                        fwrite($filegenerated, $res->TotalDGS." ");
                        fwrite($filegenerated, $res->TotalInvoice." ");
                        fwrite($filegenerated, $res->Quantity."\r\n");

                        if($counter == $d1->num_rows){
                            fwrite($filegenerated, "'All' ");
                            fwrite($filegenerated, $res->ProcessPO);
                        }
                        $counter++;
                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdY")."D1.".$row->Code." file created.</p>";
            }else{
                echo "<p>No details to create for ".date("mdY")."D1.".$row->Code." file.</p>";
            }//end of query for D1
        }
    }

    //query for branch SID
    $branch = $uiInterface->branch();
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating D3 files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for PFL
            $d3 = $uiInterface->callD3($row->ID);
            if($d3->num_rows){
                if(!file_exists($dir.DS.date("mdY")."D3.".$row->Code)){
                    $filegenerated = fopen($dir.DS.date("mdY")."D3.".$row->Code, 'w');
                    while($res = $d3->fetch_object()){
                        fwrite($filegenerated, "'".$res->BranchCode."' ");
                        fwrite($filegenerated, $res->OrderAmount);
                        fwrite($filegenerated, $res->InvoiceAmount);
                        fwrite($filegenerated, $res->SalesUnit);
                        fwrite($filegenerated, $res->ProcessedPO);
                        fwrite($filegenerated, $res->IBMCFFNo);
                        fwrite($filegenerated, $res->ActiveIBMCFFNo);
                        fwrite($filegenerated, $res->TerminatedIBMCFFNo);
                        fwrite($filegenerated, $res->IBMCNo);
                        fwrite($filegenerated, $res->ActiveIBMCNo);
                        fwrite($filegenerated, $res->TerminatedIBMNo);
                        fwrite($filegenerated, $res->BeginningCounts);
                        fwrite($filegenerated, $res->NewRecruits);
                        fwrite($filegenerated, $res->AddOther);
                        fwrite($filegenerated, $res->RemovedOthers);
                        fwrite($filegenerated, $res->OrderAmount2);
                        fwrite($filegenerated, $res->BeginningCounts2);
                        fwrite($filegenerated, $res->NewRecruitsOther);
                        fwrite($filegenerated, $res->AddOther2);
                        fwrite($filegenerated, $res->TerminatedAccounts2);
                        fwrite($filegenerated, $res->RemovedOthers2);
                        fwrite($filegenerated, $res->OrderAmountBCCustomerType);
                        fwrite($filegenerated, $res->DLStartDateNo);
                        fwrite($filegenerated, $res->DLCampDateNo);
                        fwrite($filegenerated, $res->BCStartDateNo);
                        fwrite($filegenerated, $res->BCCampDateNo);
                        fwrite($filegenerated, $res->ActivesNo);
                        fwrite($filegenerated, $res->CBCActives);
                        fwrite($filegenerated, $res->Reactivated);
                        fwrite($filegenerated, $res->CBCReactivated);
                        fwrite($filegenerated, $res->igsndgs);
                        fwrite($filegenerated, $res->bfcndgs);
                        fwrite($filegenerated, $res->ActiveDealers);
                        fwrite($filegenerated, $res->ActiveBC."\r\n");
                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdY")."D3.".$row->Code." file created.</p>";
            }else{
                echo "<p>No details to create for ".date("mdY")."D3.".$row->Code." file.</p>";
            }                
        }
    }
    
    
    //query for branch SID
    $branch = $uiInterface->branch();
    if($branch->num_rows){
        echo "<p class='titlefile'><b>Generating PFL files...</b></p>";
        while($row = $branch->fetch_object()){
            $dir = $path.DS.$row->Code;

            //call a function to load query for PFL
            $pfl = $uiInterface->callPFL($row->ID);
            if($pfl->num_rows){
                if(!file_exists($dir.DS.date("mdY").".".$row->Code."PFL")){
                    $filegenerated = fopen($dir.DS.date("mdY").".".$row->Code."PFL", 'w');
                    while($res = $pfl->fetch_object()){
                        fwrite($filegenerated, "'".$res->branchCode."' ");
                        fwrite($filegenerated, "'".$res->DealerCode."' ");
                        fwrite($filegenerated, "'".$res->DealerLName."' ");
                        fwrite($filegenerated, "'".$res->DealerFName."' ");
                        fwrite($filegenerated, "'".$res->Address1."' ");
                        fwrite($filegenerated, "'".$res->Address2."' ");
                        fwrite($filegenerated, "'".$res->Address3."' ");
                        fwrite($filegenerated, "'".$res->Address4."' ");
                        fwrite($filegenerated, "'".$res->Birthdate."' ");
                        fwrite($filegenerated, "'".$res->MobileNo."' ");
                        fwrite($filegenerated, "'".$res->TelNo."' ");
                        fwrite($filegenerated, "'".$res->DealerName."' ");
                        fwrite($filegenerated, "'".$res->Gender."' ");
                        fwrite($filegenerated, "'".$res->MaritalStatus."' ");
                        fwrite($filegenerated, "'".$res->GSUType."' ");
                        fwrite($filegenerated, "'".$res->CustomerType."' ");
                        fwrite($filegenerated, "'".$res->IBMCode."' ");
                        fwrite($filegenerated, "'".$res->RepCode."' ");
                        fwrite($filegenerated, "'".$res->ZipCode."'\r\n");
                    }
                    fclose($filegenerated);
                }
                echo "<p>".date("mdY").".".$row->Code."PFL"." file created.</p>";
            }else{
                echo "<p>No details to create for ".date("mdY").".".$row->Code."PFL"." file.</p>";
            }                
        }
    }
//==========================================================================================================
?>
