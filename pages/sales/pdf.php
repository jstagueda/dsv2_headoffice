<?php

require_once( 'fpdf.php' );
	
class PDF extends FPDF
{
 
// Page header (2)
function Header()
{
    //Logo
    $this->Image('images/php.gif',10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(60);
    // Print the Title
    $this->SetFillColor(220);
    $this->Cell(110,10,'Creating PDF files with PHP—part two',1,0,'C',true);
    // Line break
    $this->Ln(20);
}
 
// Page footer (3)
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Set font
    $this->SetFont('Arial','I',6);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C');
}
}

?>