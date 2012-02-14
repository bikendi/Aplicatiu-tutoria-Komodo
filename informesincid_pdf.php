<?php
/*
    Aplicatiu Tutoria Komodo v.0.1
    Aplicació web per a la gestió de la tasca tutorial.
    Copyright (C) 2002-2007  Artur Guillamet Sabaté <aguillam(a)xtec.net>
    Copyright (C) 2012 ßingen Eguzkitza <beguzkit@xtec.cat>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

@include("linkbd.inc.php");
@include("comu.php");
panyacces("Privilegis");

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdf.php');
class PDF extends FPDF
{
  function Header()
  {
    global $logocentre, $nomcentre, $adrecacentre, $CPcentre, $poblaciocentre, $telfcentre;
    $this->Image($logocentre,20,16,25);
    $this->SetFont('Times','',8);
    $this->Text(50, 22, $nomcentre);
    $this->Text(50, 25, $adrecacentre);
    $this->Text(50, 28, "$CPcentre $poblaciocentre");
    $this->Text(50, 31, 'Telf. '.$telfcentre);
    $this->Ln(20);
  }

  function Footer()
  {
    $this->Line(20,280,182,280);
  }
}

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(20, 20, 20);

$consulta="SELECT ref_alum, id_prof, data, hora, text FROM $bdtutoria.$tbl_prefix"."informeincid WHERE id='$id' LIMIT 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
$ref_alum=$fila[0];
$id_prof=$fila[1];
$data=$fila[2];
$hora=$fila[3];
$text=$fila[4];
mysql_free_result($conjunt_resultant);

$regs=str_replace(' ','',$ref_alum);
if($regs==$ref_alum) {
  $consulta="SELECT nom_alum, cognom_alu, cognom2_al, curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$ref_alum' LIMIT 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $fila=mysql_fetch_row($conjunt_resultant);
  $nom_alumne="$fila[0] $fila[1] $fila[2] ($fila[3] $fila[4] $fila[5])";
  mysql_free_result($conjunt_resultant);
}
else $nom_alumne="Comú a tot: $ref_alum";

$consulta="SELECT nomreal FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$id_prof' LIMIT 1";
$conjunt_resultant=mysql_query($consulta, $connect);
if(0==mysql_num_rows($conjunt_resultant)) $nom_professor='???';
else {
  $fila=mysql_fetch_row($conjunt_resultant);
  $nom_professor="$id_prof - $fila[0]";
}
mysql_free_result($conjunt_resultant);

$linia=6;
$paragraf=7;
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(50);
  $pdf->Cell(60,10,"INFORME D'INCIDÈNCIA",1,0,'C');
  $pdf->SetY(15+$pdf->GetY());
  $pdf->Write($linia, "Data: ");
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia, date('j-n-Y',$data)); 
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "Hora: ");
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia, $hora."    ");
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "Lloc: ");
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia, "--");
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "Professor: ");
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia, "$nom_professor");
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "Alumne: ");
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia, "$nom_alumne");
  $pdf->SetY(12+$pdf->GetY());
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "Descripció: ");
  $pdf->SetFont('Arial','',12);
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->Write($linia, "$text");
  
$pdf->Output();

?>
