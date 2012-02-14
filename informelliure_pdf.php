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

  }
}

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(20, 20, 20);

$consulta="SELECT ref_alum, contingut FROM $bdtutoria.$tbl_prefix"."informelliure WHERE id='$id'";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
$ref_alum=$fila[0];
$text=$fila[1];
$t["\""]="''";
$t["$"]="\\$";
$text=strtr($text,$t); 
mysql_free_result($conjunt_resultant);

$llistaalumnes=explode(",",$ref_alum);

foreach($llistaalumnes as $alum) {
  $textinflliure=$text;
  $pdf->AddPage();
  $consulta="SELECT cognom_alu, cognom2_al, nom_alum, sexe, pla_estudi, curs, grup, adreca, nom_munici, codi_posta, primer_tel, cognom1_pa, cognom2_pa, nom_pare, cognom1_ma, cognom2_ma, nom_mare FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$alum'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $fila=mysql_fetch_row($conjunt_resultant);
  $trans["@PARENOM"]= "$fila[13]";
  $trans["@PARECOGNOM1"]= "$fila[11]";
  $trans["@PARECOGNOM2"]= "$fila[12]";
  $trans["@MARENOM"]= "$fila[16]";
  $trans["@MARECOGNOM1"]= "$fila[14]";
  $trans["@MARECOGNOM2"]= "$fila[15]";
  $trans["@ADRECA"]= "$fila[7]";
  $trans["@TELF"]="$fila[10]";
  $trans["@CODIPOSTAL"]= "$fila[9]";
  $trans["@POBLACIO"]= "$fila[8]";
  $trans["@ALUMNENOM"]= "$fila[2]";
  $trans["@ALUMNECOGNOM1"]= "$fila[0]";
  $trans["@ALUMNECOGNOM2"]= "$fila[1]";
  $trans["@CURS"]= "$fila[5]";
  $trans["@GRUP"]= "$fila[6]";
  $trans["@ETAPA"]= "$fila[4]";
  $trans["@NOMDIRECTOR"]=$nomdirector;
    

  $tok="@GENEREALUM(";
  $straux=strstr($textinflliure, $tok);
  while($straux!=false) {
    $naux2=1+strpos($straux,')');
    $straux3=substr($straux, 0, $naux2);
    $straux5 = $straux3;
    $naux3=strpos($straux3, ':');
    if($fila[3]=='HOME') {
      $straux4=substr($straux3, strlen($tok), $naux3-strlen($tok));
    }
    else {
      $straux4=substr($straux3, $naux3+1, -1);
    }
    $textinflliure=str_replace($straux5, $straux4, $textinflliure);
    $straux=strstr($textinflliure, $tok);
  }
  mysql_free_result($conjunt_resultant);
  $trans["@DATAAVUI"]= date('j-n-Y',$datatimestamp);
  
  if($alum!='') $trans["@TUTORGRUP"]=cercaTutor("$fila[5] $fila[6] $fila[4]");
  else $trans["@TUTORGRUP"]="";
  

  $tok="@GENEREDIR(";
  $straux=strstr($textinflliure, $tok);
  while($straux!=false) {
    $naux2=1+strpos($straux,')');
    $straux3=substr($straux, 0, $naux2);
    $straux5 = $straux3;
    $naux3=strpos($straux3, ':');
    if($sexdirector=='H') {
      $straux4=substr($straux3, strlen($tok), $naux3-strlen($tok));
    }
    else {
      $straux4=substr($straux3, $naux3+1, -1);
    }
    $textinflliure=str_replace($straux5, $straux4, $textinflliure);
    $straux=strstr($textinflliure, $tok);
  }
 

  $pdf->SetFont('Arial','',12);
  
  $textinflliure=strtr($textinflliure,$trans);
  
  $modf["@b@"]="\"); \$pdf->SetFont('','b',0); \$pdf->Write(6,\"";
  $modf["@/b@"]="\"); \$pdf->SetFont('','',0); \$pdf->Write(6,\"";
  $modf["@i@"]="\"); \$pdf->SetFont('','i',0); \$pdf->Write(6,\"";
  $modf["@/i@"]="\"); \$pdf->SetFont('','',0); \$pdf->Write(6,\"";
  $modf["@u@"]="\"); \$pdf->SetFont('','u',0); \$pdf->Write(6,\"";
  $modf["@/u@"]="\"); \$pdf->SetFont('','',0); \$pdf->Write(6,\"";

  $textinflliure="\$pdf->Write(6,\"".$textinflliure."\");"; 
  $textinflliure=strtr($textinflliure, $modf);
  eval(    $textinflliure   );

  }

$pdf->Output();

?>
