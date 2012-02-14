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
panyacces("Administrador");

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

    $this->Ln(35);
  }

  
  function Footer()
  {
    
    $this->Line(20,280,182,280);
    
  }
}




$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(20, 20, 20);

// Dades alumne
$consulta="SELECT nom_alum, cognom_alu, cognom2_al, curs, grup, pla_estudi, adreca, nom_munici, codi_posta, primer_tel, cognom1_pa, cognom2_pa, nom_pare, cognom1_ma, cognom2_ma, nom_mare, sexe FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$nal' LIMIT 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
$nom_alumne=$fila[0];
$curs=$fila[3];
$grup=$fila[4];
$etapa=$fila[5];
$cognom1_al=$fila[1];
$cognom2_al=$fila[2];
$adreca=$fila[6];
$nom_municipi=$fila[7];
$codi_posta=$fila[8];
$telefon=$fila[9];
$pare= "$fila[12] $fila[10] $fila[11]";
$mare="$fila[15] $fila[13] $fila[14]";
$sexe=$fila[16];

mysql_free_result($conjunt_resultant);

$datI=split(' ', $dataI);
$daI=split('-', $datI[1]);
$datatimestampI=mktime(0,0,0,$daI[1],$daI[0],$daI[2],-1);
$datF=split(' ', $dataF);
$daF=split('-', $datF[1]);
$datatimestampF=mktime(0,0,0,$daF[1],$daF[0],$daF[2],-1);

// recompte faltes
$consulta="select count(*) from $bdtutoria.$tbl_prefix"."faltes where refalumne='$nal' and incidencia='F' and data>='$datatimestampI' and data<='$datatimestampF'";
$conjunt_resultant=mysql_query($consulta, $connect);
$faltes=mysql_result($conjunt_resultant, 0,0);
mysql_free_result($conjunt_resultant);

// recompte retards
$consulta="select count(*) from $bdtutoria.$tbl_prefix"."faltes where refalumne='$nal' and incidencia='R' and data>='$datatimestampI' and data<='$datatimestampF'";
$conjunt_resultant=mysql_query($consulta, $connect);
$retards=mysql_result($conjunt_resultant, 0,0);
mysql_free_result($conjunt_resultant);

$nomMesE = array ("de Gener", "de Febrer", "de Març", "d'Abril", "de Maig", "de Juny", "de Juliol", "d'Agost", "de Setembre", "d'Octubre", "de Novembre", "de Desembre");


$linia=6;
$paragraf=20;
  $pdf->AddPage();
  $pdf->SetFont('Arial','',9);
  $pdf->Write($linia,"                                                                                                                $pare");
  $pdf->SetY(5+$pdf->GetY());
  $pdf->Write($linia,"                                                                                                                $mare");
  $pdf->SetY(5+$pdf->GetY());
  $pdf->Write($linia,"                                                                                                                $adreca");
  $pdf->SetY(5+$pdf->GetY());
  $pdf->Write($linia,"                                                                                                                $codi_posta $nom_municipi");
  $pdf->SetY(-8+$pdf->GetY());

  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia," ".$poblaciocentre.", ".date('j')." ".$nomMesE[(date('n')-1)]." de ".date('Y'));
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->Write($linia, "Senyors ");
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "$cognom1_al $cognom2_al.");
  $pdf->SetFont('Arial','',12);
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->Write($linia, "Benvolguda Família.");
  $pdf->SetY($paragraf+$pdf->GetY());
  if($sexe=="HOME") $pdf->Write($linia, "El seu fill ");
  else $pdf->Write($linia, "La seva filla ");
  $pdf->SetFont('Arial','B',12); 
  $pdf->Write($linia, "$nom_alumne");
  $pdf->SetFont('Arial','',12);
  if($sexe=="HOME") $pdf->Write($linia, ", alumne del grup ");
  else $pdf->Write($linia, ", alumna del grup ");
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "$curs $grup $etapa ");
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia, "ha acumulat, des del $dataI fins el $dataF, ");
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "$faltes ");
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia, "faltes i ");
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "$retards ");
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia, "retards no justificats, la qual cosa suposa, d'acord amb el que estableix la normativa interna del nostre Consell Escolar una ");
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "FALTA GREU.");
  $pdf->SetFont('Arial','',12);
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->Write($linia, "Recordem que l'acumulació de 15 faltes sense justificació és una falta greu i que tres faltes greus suposa una falta molt greu que es sanciona amb l'expulsió temporal del centre.");
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->Write($linia, "Deixant de banda les sancions reglamentàries els manifestem l'interés dels professors del curs per ajudar ");
  if($sexe=="HOME") $pdf->Write($linia, "el seu fill,");
  else $pdf->Write($linia, "la seva filla,");
  $pdf->Write($linia, " a recuperar aquestes actituds negatives i els aconsellem que parlin amb ");
  if($sexe=="HOME") $pdf->Write($linia, "ell");
  else $pdf->Write($linia, "ella");
  $pdf->Write($linia, " del problema que pugui tenir i els oferim la col·laboració del/de la tutor/a i de l'equip de direcció si ho consideren oportú.");
  $pdf->SetY($paragraf+$paragraf+10+$pdf->GetY());
  $pdf->Write($linia, "        $nomdirector");
  $pdf->SetY(7+$pdf->GetY());
  $pdf->Write($linia, (($sexdirector=='H')?"              Director":"              Directora"));
  
  
$pdf->Output();

?>
