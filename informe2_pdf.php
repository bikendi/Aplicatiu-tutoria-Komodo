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
    $this->Line(20,280,182,280);
  }
}

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(20, 20, 20);


$consulta="SELECT text FROM $bdtutoria.$tbl_prefix"."informes WHERE referencia='informe2'";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
$informe=$fila[0];
mysql_free_result($conjunt_resultant);


if($nalumne=='0') { 
  $gr=split(' ', $grup);
  $consulta="SELECT numero_mat FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='$gr[0]' and grup='$gr[1]' and pla_estudi='$gr[2]' order by cognom_alu, cognom2_al, nom_alum";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) $llistaalumnes[]=$fila[0];
  mysql_free_result($conjunt_resultant);
}
else { 
  $llistaalumnes[]=$nalumne;
}
$nomtuts=cercaTutor($grup);
  
$trans["!DATAI"]=$dataI;
$trans["!DATAF"]=$dataF;
$datI=split(' ', $dataI);
$daI=split('-', $datI[1]);
$datatimestampI=mktime(0,0,0,$daI[1],$daI[0],$daI[2],-1);
$datF=split(' ', $dataF);
$daF=split('-', $datF[1]);
$datatimestampF=mktime(0,0,0,$daF[1],$daF[0],$daF[2],-1);

foreach($llistaalumnes as $alum) {

  $consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$alum' and incidencia='F' and data>='$datatimestampI' and data<='$datatimestampF'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $trans["!NFALTESM"]=mysql_result($conjunt_resultant, 0,0);
  mysql_free_result($conjunt_resultant);
  $consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$alum' and incidencia='R' and data>='$datatimestampI' and data<='$datatimestampF'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $trans["!NRETARDSM"]=mysql_result($conjunt_resultant, 0,0);
  mysql_free_result($conjunt_resultant);
  if(($trans["!NFALTESM"]==0)&&($trans["!NRETARDSM"]==0)) continue; //si no te faltes o retards el saltem.
  $pdf->AddPage();

  $consulta="SELECT from_unixtime(data,'%d-%m-%Y'), hora FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$alum' and incidencia='F' and data>='$datatimestampI' and data<='$datatimestampF' order by data asc";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $trans["!DETFALM"]="";
  $datant='';
  unset($llistafaltm);
  while ($fila=mysql_fetch_row($conjunt_resultant)) {
     if($datant==$fila[0]) $llistafaltm["$fila[0]"].= ", ".$fila[1];
     else $llistafaltm["$fila[0]"]=$fila[1];
     $datant=$fila[0];  
  }
  mysql_free_result($conjunt_resultant);
  for($i=0; $i<count($llistafaltm); ++$i) {
    if($trans["!DETFALM"]=="") $trans["!DETFALM"]="                  Relació de faltes:\n";
    $trans["!DETFALM"] .= "                  Dia: ".key($llistafaltm)." - Hora: ".current($llistafaltm)."\n"; 
    next($llistafaltm);
  }
         
  $consulta="SELECT from_unixtime(data,'%d-%m-%Y'), hora FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$alum' and incidencia='R' and data>='$datatimestampI' and data<='$datatimestampF'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $trans["!DETRETM"]="";
  $datant='';
  unset($llistaretm);
  while ($fila=mysql_fetch_row($conjunt_resultant)) {
     if($datant==$fila[0]) $llistaretm["$fila[0]"].= ", ".$fila[1];
     else $llistaretm["$fila[0]"]=$fila[1];
     $datant=$fila[0];
  }
  mysql_free_result($conjunt_resultant);
  for($i=0; $i<count($llistaretm); ++$i) {
    if($trans["!DETRETM"]=="") $trans["!DETRETM"]="                  Relació de retards:\n";
    $trans["!DETRETM"] .= "                  Dia: ".key($llistaretm)." - Hora: ".current($llistaretm)."\n"; 
    next($llistaretm);
  }

  $consulta="SELECT cognom_alu, cognom2_al, nom_alum, sexe, pla_estudi, curs, grup, adreca, nom_munici, codi_posta, primer_tel, cognom1_pa, cognom2_pa, nom_pare, cognom1_ma, cognom2_ma, nom_mare FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$alum'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $fila=mysql_fetch_row($conjunt_resultant);
  $trans["!PARE1"]= "$fila[13] $fila[11] $fila[12]";
  $trans["!PARE2"]= "$fila[16] $fila[14] $fila[15]";
  $trans["!ADRECA"]= "$fila[7]";
  $trans["!TELF"]="$fila[10]";
  $trans["!CODIPOSTAL"]= "$fila[9]";
  $trans["!POBLACIO"]= "$fila[8]";
  $trans["!ALUMNE"]= "$fila[2] $fila[0] $fila[1]";
  $trans["!ESTUDIS"]= "$fila[5] $fila[6] $fila[4]";
  $trans["!MATRICULAT"]= (($fila[3]=='HOME')?'matriculat':'matriculada');
  $trans["!VFILL"]= (($fila[3]=='HOME')?'el vostre fill':'la vostra filla');
  mysql_free_result($conjunt_resultant);
  $trans["!DATA0"]= date('j-n-Y',$datatimestamp);
  
  $trans["!TUTOR"]=$nomtuts;
  $trans["!POBLACIOCENTRE"]=$poblaciocentre;

  $consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$alum' and incidencia='F'  and data>='$datatimestampIniciCurs' and data<='$datatimestampF'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $trans["!NFALTESC"]=mysql_result($conjunt_resultant, 0,0);
  mysql_free_result($conjunt_resultant);

  $consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$alum' and incidencia='FJ' and data>='$datatimestampIniciCurs' and data<='$datatimestampF' ";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $trans["!NFALTESCJ"]=mysql_result($conjunt_resultant, 0,0);
  mysql_free_result($conjunt_resultant);
  
  $consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$alum' and incidencia='R' and data>='$datatimestampIniciCurs' and data<='$datatimestampF'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $trans["!NRETARDSC"]=mysql_result($conjunt_resultant, 0,0);
  mysql_free_result($conjunt_resultant);

  $consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$alum' and incidencia='RJ' and data>='$datatimestampIniciCurs' and data<='$datatimestampF'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $trans["!NRETARDSCJ"]=mysql_result($conjunt_resultant, 0,0);
  mysql_free_result($conjunt_resultant);

  $pdf->SetFont('Arial','',11);
  $pdf->Write(4,strtr($informe,$trans));

  }

$pdf->Output();

?>
