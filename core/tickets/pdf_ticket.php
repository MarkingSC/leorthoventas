<?php
require('../../pdf/fpdf.php');
$cliente="marco";
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Image('../../img/lgo_ch.png' , 5 ,5, 30 , 30,'PNG', 'http://www.facebook.com');
$pdf->Ln();
$pdf->SetY(0);
$pdf->SetX(80);
$pdf->Cell(100,20,utf8_decode('LEORTHOPEDIC'));
$pdf->Ln();
$pdf->SetY(15);
$pdf->SetX(50);
$pdf->Cell(200,20,utf8_decode('Av. Toluca No.432, Valle de Bravo, México.'));
$pdf->Ln();
$pdf->Cell(200,10,utf8_decode('____________________________________________________________'));
$pdf->Ln();
$pdf->Cell(200,10,utf8_decode('Reporte de ticket para el cliente '.$cliente.": "));


$pdf->Output();

?>