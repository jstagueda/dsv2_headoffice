<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 08, 2013
 */
    include('../../initialize.php');
    
    $IDsAdhocCLIForPrinting = @implode(',', $_SESSION['IDsAdhocCLIForPrinting']);
    $BasisOfCLIncrease = isset($_SESSION['BasisOfCLIncrease']) ? $_SESSION['BasisOfCLIncrease'] : '';
    $WithAdvancePayment = isset($_SESSION['WithAdvancePayment']) ? $_SESSION['WithAdvancePayment'] : '';
    $PO = isset($_SESSION['PO']) ? $_SESSION['PO'] : '';
    $CrediTermID = isset($_SESSION['CreditTermID']) ? $_SESSION['CreditTermID'] : ''; 
    
    //Prepare what type of CL increase would be applied and displayed in print page...
    if($BasisOfCLIncrease){        
        $BOCLIValue = $BasisOfCLIncrease['Value'];
        //Fixed Amount
        if($BasisOfCLIncrease['Option'] == 'FA'){
            $CLIncrease = $BOCLIValue;
            $q = "SELECT c.`ID`,TRIM(c.`Code`) AS `Code`,c.`Name`,$CLIncrease AS `CLIncrease`
                        FROM `customer` c
                        INNER JOIN `tpi_customerdetails` cd ON cd.`CustomerID` = c.`ID`
                        WHERE c.`ID` IN($IDsAdhocCLIForPrinting)";
            $lists = $mysqli->query($q);
        }
        
        //Percentage of Current CL
        if($BasisOfCLIncrease['Option'] == 'PCCL'){
            $percentage = $BOCLIValue / 100;
            $lists = $mysqli->query("SELECT c.`ID`,TRIM(c.`Code`) AS `Code`, c.`Name`, cr.`ApprovedCL`, 
                                    ROUND((cr.`ApprovedCL` * $percentage),2) AS `CLIncrease`
                                    FROM customer c
                                    INNER JOIN `tpi_credit` cr ON cr.`CustomerID` = c.`ID`
                                    WHERE 
                                    c.`ID` IN ($IDsAdhocCLIForPrinting)"
                                );
        }
        
        //Percentage of Advance Payment
        if($BasisOfCLIncrease['Option'] == 'PAP' && $WithAdvancePayment){
            $percentage = $BOCLIValue / 100;
            $CreditTermID = $WithAdvancePayment['CreditTermID'];
            $MinAmt = $WithAdvancePayment['MinAmt'];
            $PaymentDatePeriodStart = $WithAdvancePayment['PaymentDatePeriodStart'];
            $PaymentDatePeriodEnd = $WithAdvancePayment['PaymentDatePeriodEnd'];
            $NoOfDays = $WithAdvancePayment['NoOfDays'];
            
            $lists = $mysqli->query("SELECT c.`ID`,TRIM(c.`Code`) AS `Code`, c.`Name`, 
                                    ROUND((so.`NetAmount` * $percentage),2) AS `CLIncrease`
                                    FROM customer c
                                    INNER JOIN `salesinvoice` si ON si.`CustomerID` = c.`ID`
                                    INNER JOIN `salesorder` so ON so.`CustomerID` = si.`CustomerID`
                                    WHERE 
                                    c.`ID` IN ($IDsAdhocCLIForPrinting)
                                    AND si.`CreditTermID` = $CreditTermID AND so.`CreditTermID` = $CreditTermID
                                    AND so.`NetAmount` >= $MinAmt
			            AND so.`TxnDate` BETWEEN '$PaymentDatePeriodStart' AND '$PaymentDatePeriodEnd'
				    AND si.`TxnDate` BETWEEN '$PaymentDatePeriodStart' AND '$PaymentDatePeriodEnd'
				    AND TIMESTAMPDIFF(DAY,DATE(si.`LastPaymentDate`),DATE_ADD(DATE(si.`TxnDate`),INTERVAL ct.`Duration` DAY)) >= $NoOfDays
                                    AND si.`StatusID` = 7
                                    GROUP BY si.`CustomerID`"
                                );
        }
        
        //Percentage of PO Based
        if($BasisOfCLIncrease['Option'] == 'PPOB'){
            if($PO){
                $POPeriodStart = $PO['POPeriodStart'];
                $POPeriodEnd = $PO['POPeriodEnd'];
                $POMinAmt = $PO['MinPOAmt'];
                
                //CSP
                if($BasisOfCLIncrease['OptionPPOB'] == 'CSP'){
                    $SelectFormulaCLI = "ROUND((so.`GrossAmount` * $percentage),2) AS `CLIncrease`";
                }

                //CSP Less CPI GA - CSP
                if($BasisOfCLIncrease['OptionPPOB'] == 'CSPLCPI'){
                    $SelectFormulaCLI = "ROUND(((so.`NetAmount` - si.TotalCPI) * $percentage),2) AS `CLIncrease`";
                }

                //Invoice Amount
                if($BasisOfCLIncrease['OptionPPOB'] == 'IA'){
                    $SelectFormulaCLI = "ROUND((si.`NetAmount` * $percentage),2) AS `CLIncrease`";
                }
                
                //Maing query for PO selected basis of CL increase
                $lists = $mysqli->query("SELECT c.`ID`,TRIM(c.`Code`) AS `Code`, c.`Name`, 
                                            $SelectFormulaCLI
                                            FROM customer c 
                                            INNER JOIN `salesinvoice` si ON si.`CustomerID` = c.`ID`
                                            INNER JOIN `salesorder` so ON so.`CustomerID` = si.`CustomerID`
                                            WHERE
                                            c.`ID` IN ($IDsAdhocCLIForPrinting)
                                            AND DATE(so.`TxnDate`) BETWEEN '$POPeriodStart' AND '$POPeriodEnd'
                                            AND so.`CreditTermID` = $CrediTermID
                                            AND so.`NetAmount` >= $POMinAmt
                                            AND so.`StatusID` = 7
                                            GROUP BY so.`CustomerID`");
            }
        }
    }
?>
    <style>
        html{font-family: Verdana,Geneva,Arial,helvetica,sans-serif;font-size: 12px;}
        table{font-size: 12px;border: 1px solid #000;border-collapse: collapse;}
        table td{border: 1px solid #000;}
        table th{border: 1px solid #000;}
        h2{font-size: 16px;}
        .td-center{text-align: center;}
    </style>
    <script> window.onload = function(){ window.print(); } </script>
<?php
    if($lists->num_rows > 0):
?>
    <h2>List for CL Increase</h2>
    <span>Date Printed: <?php echo date('F d, Y h:i A'); ?></span>
    <table width="100%" border="1" cellspacing="3" cellpadding="3">
        <tr>
            <th width="12%" class="td-bottom-border">Code</th>
            <th width="23%" class="td-bottom-border">Name</th>
            <th width="23%" class="td-bottom-border">CL Increase</th>
        </tr>
<?php
        while($row = $lists->fetch_object()):
?>
        <tr class="tbl-td-rows">
            <td class="tbl-td-center td-bottom-border td-center"><?php echo $row->Code; ?></td>
            <td class="tbl-td-center td-bottom-border"><?php echo $row->Name; ?></td>
            <td class="tbl-td-right td-bottom-border"><?php echo number_format($row->CLIncrease); ?></td>
        </tr>
<?php
        endwhile;
?>
    </table>
<?php
        unset($_SESSION['IDsAdhocCLIForPrinting']);        
    endif;
?>