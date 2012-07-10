<?
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
    $this->SetY(-20);
    $this->Line(20,280,182,280);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
  }
}


$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(20, 20, 20);

if($grup!='') {
  $gru=preg_split('/ /', $grup);
  $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' ORDER BY cognom_alu, cognom2_al ASC";
  }
  else {
   $subgru=preg_split('/ /',$subgrup);
   $consulta="SELECT alumnes FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='$subgru[0]' limit 1";
   $conjunt_resultant=mysql_query($consulta, $connect);
   $alssubgrup=preg_split('/,/',mysql_result($conjunt_resultant, 0,0));
   mysql_free_result($conjunt_resultant);
   $consulta ="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi ";
   $consulta.="FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE ";
   $cons='';
   foreach($alssubgrup as $nal) {
     if ($cons!='') $cons.='or ';
     $cons.="numero_mat='$nal' ";
   }
   $consulta.= $cons;
   $consulta.="ORDER BY cognom_alu, cognom2_al ASC";
  }
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
    $llistaalumnes[]=$fila[0];
    if($grup!='') $llistaalumnesnom[]=$fila[1];
    if($subgrup!='') $llistaalumnesnom[]="$fila[1] ($fila[2] $fila[3] $fila[4])";
  }
  mysql_free_result($conjunt_resultant);
  
  
  $maxalumsxpag=32; 
  for($i=0; $i<count($llistaalumnes); ++$i) {
    if (($i%$maxalumsxpag)==0) {
      $pdf->AddPage();
      $pdf->SetY(15);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(80);
      $pdf->Cell(60,10,$cursacademic,0,0,'C');
      $pdf->SetY(20);
      $pdf->SetFont('Arial','B',12);
      if($grup!='') {
        $pdf->Cell(80);
        $pdf->Cell(60,10,"Grup: $grup",0,0,'C');
      }
      if($subgrup!='') {
        $pdf->Cell(80);
        $pdf->Cell(60,10,"Subgrup: ".stripslashes(rawurldecode($subgrup)),0,0,'C');
      }
      $pdf->SetFont('Arial','',11);
      $pdf->SetY(50);
      $contfila=0;
    }
    
//     $pdf->Write(7, (($i<9)?"  ".($i+1):($i+1)).".- $llistaalumnesnom[$i]");
    $pdf->Cell(80, 7, (($i<9)?"  ".($i+1):($i+1)).".- $llistaalumnesnom[$i]", 1, 0);
    for($j=0; $j<12; $j++ )
      $pdf->Cell(8, 7, '', 1, 0);
//     $pdf->Ln(7.5);
    $pdf->Ln();
    ++$contfila;
  }
$pdf->Output();
?>
