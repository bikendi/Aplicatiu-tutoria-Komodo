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
    global $grup, $avaluacio, $cursacademic, $avaluacio, $dataaval;
    $nomMesE = array ("de Gener", "de Febrer", "de Març", "d'Abril", "de Maig", "de Juny", "de Juliol", "d'Agost", "de Setembre", "d'Octubre", "de Novembre", "de Desembre");

    $this->Image($logocentre,20,16,25);
    $this->SetFont('Times','',8);
    $this->Text(50, 22, $nomcentre);
    $this->Text(50, 25, $adrecacentre);
    $this->Text(50, 28, "$CPcentre $poblaciocentre");
    $this->Text(50, 31, 'Telf. '.$telfcentre);

    $this->SetFont('Arial','',11);
    $this->SetXY(120,20);
    $this->Cell(0,5,"$poblaciocentre, ".date('j',$dataaval)." ".$nomMesE[(date('n',$dataaval)-1)]." de ".date('Y',$dataaval),"",0,'R',0);
    $this->SetXY(120,25);
    $this->Cell(0,5,"Curs: $cursacademic","",0,'R',0);

    $this->SetXY(90,20);
    $aux=split(" ", $grup);
    $this->Cell(12,5,"Curs:",0,0,'L',0);
    $this->SetFont('','b',0);
    $this->Cell(15,5,"$aux[0]",0,0,'L',0);
    $this->SetFont('','',0);
    $this->Cell(12,5,"Grup:",0,0,'L',0);
    $this->SetFont('','b',0);
    $this->Cell(15,5,"$aux[1]",0,0,'L',0);
    $this->SetFont('','',0);
    $this->Cell(25,5,"Pla Estudis:",0,0,'L',0);
    $this->SetFont('','b',0);
    $this->Cell(20,5,"$aux[2]",0,0,'L',0);
    $this->SetFont('','',0);
    $this->SetXY(90,27);
    $this->Cell(20,5,"Avaluació:",0,0,'L',0);
    $this->SetFont('','b',0);
    $this->Cell(25,5,"$avaluacio",0,0,'L',0);
    $this->SetFont('','',0);
    $this->SetY(42);
  }


  function Footer()
  {
    $this->SetY(-15);
    $this->SetFont('Arial','I',6);
    $this->Cell(0,5,"Pàgina ".$this->PageNo()."/{nb}                               Data impressió: ".date('j')."-".date('n')."-".date('Y').", ".date('H').":".date('i'),"T",0,'R',0);
  }
}

$consulta="SELECT data, observacions, estat FROM $bdtutoria.$tbl_prefix"."avaluacions WHERE refaval='$avaluacio' limit 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
$dataaval=$fila[0];
$observaval=rawurldecode($fila[1]);
$estataval=$fila[2];
mysql_free_result($conjunt_resultant);
    
$pdf=new PDF("L","mm","A4");
$pdf->AliasNbPages();
$pdf->SetMargins(20, 20, 20);
$pdf->SetFillColor(180);

$gr=split(' ', $grup);
$consulta="SELECT numero_mat FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='$gr[0]' and grup='$gr[1]' and pla_estudi='$gr[2]' order by cognom_alu, cognom2_al, nom_alum";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) $llistaalumnes[]=$fila[0];
mysql_free_result($conjunt_resultant);

$pdf->AddPage();
$pdf->SetFont('Arial','',11);
$aux=split(" ", $grup);

$pdf->SetY(35);
$pdf->Cell(50,5,"","B",0,'',0);
$pdf->SetFont('','',6);
$pdf->Cell(160,5,"$observaval","B",0,'L',0);
$pdf->SetFont('','b',12); 
$pdf->Cell(0,5,"Resum d'avaluació","B",0,'R',0);
$pdf->SetFont('','',10);
$pdf->Ln(7);

if($estataval!="tancada") {
	$consulta="SELECT DISTINCT assign ";
	$consulta.="FROM $bdtutoria.$tbl_prefix"."horariprofs ";
	$consulta.="WHERE grup like '%".rawurlencode($grup)."%'";
	$conjunt_resultant=mysql_query($consulta, $connect);
	while($fila=mysql_fetch_row($conjunt_resultant)) $codis[]=$fila[0];
	foreach($llistaalumnes as $alum) {
	  $consulta="SELECT DISTINCT h.assign ";
	  $consulta.="FROM $bdtutoria.$tbl_prefix"."horariprofs h, $bdtutoria.$tbl_prefix"."subgrups s ";
	  $consulta.="WHERE s.alumnes like '%$alum%' and h.grup like concat('%', s.ref_subgrup, '%20', s.nom,'%')";
	  $conjunt_resultant=mysql_query($consulta, $connect);
	  while($fila=mysql_fetch_row($conjunt_resultant)) $codis[]=$fila[0];
	}
}
else {
	foreach($llistaalumnes as $alum) {
	  $consulta="SELECT DISTINCT ref_credit ";
	  $consulta.="FROM $bdtutoria.$tbl_prefix"."notes ";
	  $consulta.="WHERE ref_alum='$alum' and ref_aval='$avaluacio'";
	  $conjunt_resultant=mysql_query($consulta, $connect);
	  while($fila=mysql_fetch_row($conjunt_resultant)) $codis[]=$fila[0];
	}	
}

$aux='';
$i=0;
$consulta="SELECT DISTINCT codi, nomcredit, areaassign, tipus FROM $bdtutoria.$tbl_prefix"."llistacredits ";
for($a=0; $a<count($codis); ++$a) $aux.=(($aux!="")?" or ":"")."codi='$codis[$a]'";
$consulta.="WHERE $aux ";
$consulta.="ORDER BY tipus, nomcredit";
if ($aux!='') {
	$conjunt_resultant=mysql_query($consulta, $connect);
	while($fila=mysql_fetch_row($conjunt_resultant)) {
	 $credit[$i][0]=$fila[0];
	 $credit[$i][1]=rawurldecode($fila[1]);
	 $credit[$i][2]=$fila[2];
	 $credit[$i][3]=$fila[3];
	 $credit[$i][4]=0;
	 $credit[$i][5]=0;
	 ++$i;	  
	}
}
$pdf->SetFontSize(8);

$amplecol=9;
$totalcredits=$i;
$pdf->Cell(55,4,"Nom alumne", 1,0,'L',1);
for($i=0; $i<$totalcredits; ++$i) {
  $pdf->Cell($amplecol,4,"{$credit[$i][0]}", 1,0,'C',1);
}
$pdf->Cell(2,4,"", 0,0,'C',0);
$pdf->Cell($amplecol,4,"Su/Av", 1,0,'C',1);
$pdf->Ln();

$sumnotesgrup=0;
$numnotesgrup=0;
foreach($llistaalumnes as $alum) {
	$consulta="SELECT concat(cognom_alu, ' ', cognom2_al, ', ', nom_alum) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$alum' limit 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	$pdf->Cell(55,4,"$fila[0]", 1,0,'L',0);
	$estadisticanom[]=$fila[0];
	$nom=$fila[0];
	$suspal=0;
	$avalual=0;
	$creditssuspes="";
	$pdf->SetFontSize(7);
	for($i=0; $i<$totalcredits; ++$i) {
		$consulta="SELECT valor, memo FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='$avaluacio' and ref_alum='$alum' and ref_credit='{$credit[$i][0]}' limit 1";
  		$conjunt_resultant=mysql_query($consulta, $connect);
  		$fila=mysql_fetch_row($conjunt_resultant);
  		if($fila[1]!='') {
	  		$comentarisavaluacionom[]=$nom;
	  		$comentarisavaluaciocred[]=$credit[$i][0];
	  		$comentarisavaluaciomemo[]=$fila[1];
  		}
  		$aux=explode('z',$fila[0]);
  		$hihaglobal=true;
  		if($aux[count($aux)-1]=='') {$hihaglobal=false; $aux="";}
		if($aux[count($aux)-1]=='I'||$aux[count($aux)-1]=='1'||$aux[count($aux)-1]=='2'||$aux[count($aux)-1]=='3'||$aux[count($aux)-1]=='4') {
			$creditssuspes.=(($creditssuspes!="")?", ":"").$credit[$i][0];
			++$suspal;
			++$credit[$i][4];
	  	}	
		for($k=0; $k<count($aux); ++$k) {
			if($aux[$k]=='I'||$aux[$k]=='1'||$aux[$k]=='2'||$aux[$k]=='3'||$aux[$k]=='4') $pdf->SetTextColor(255,0,0);
			if($k!=(count($aux)-1)) {
				$pdf->SetFontSize(4);
				$pdf->Cell($amplecol/count($aux),4,"$aux[$k]",(($k==0)?"LTB":(($k==(count($aux)-1))?"RTB":"TB")),0,'C',0);
				$pdf->SetFontSize(7);
			} else {
				$pdf->Cell($amplecol/count($aux),4,"$aux[$k]",(($k==0)?"LTB":(($k==(count($aux)-1))?"RTB":"TB")),0,'C',0);
				if(preg_match ("/^[-]?[0-9]+([\.][0-9]+)?$/", $aux[$k]) && $sumnotesgrup!=-1 && $aux[$k]!='') {$sumnotesgrup=$sumnotesgrup+$aux[$k]; ++$numnotesgrup;}
				else if ($aux[$k]!='') $sumnotesgrup=-1;
			}
			if($aux[$k]=='I'||$aux[$k]=='1'||$aux[$k]=='2'||$aux[$k]=='3'||$aux[$k]=='4') $pdf->SetTextColor(0,0,0);
		}
		if($fila[0]!='' && $hihaglobal) {
			++$avalual;
			++$credit[$i][5];
		}
	}
	$pdf->SetFontSize(8);
	$estadisticacreditssuspes[]=$creditssuspes;
	$estadisticansuspeses[]=$suspal;
	$pdf->Cell(2,4,"", "L",0,'C',0);
	$pdf->Cell($amplecol,4,"$suspal/$avalual", 1,0,'C',0);
	$pdf->Ln();
}
$pdf->Ln(2);
$pdf->Cell(55,4,"Suspesos/Avaluats (Su/Av): ", 1,0,'R',1);
for($i=0; $i<$totalcredits; ++$i) $pdf->Cell($amplecol,4,"{$credit[$i][4]}/{$credit[$i][5]} ", 1,0,'C',0);
$pdf->Ln();
$pdf->Cell(55,4);
for($i=0; $i<$totalcredits; ++$i) $pdf->Cell($amplecol,4,"".(($credit[$i][5]!=0)?(round(($credit[$i][4]/$credit[$i][5])*100,0)):0)."%", 1,0,'C',0);
$pdf->SetX(20);
$pdf->SetFont('','b',0);
$pdf->Write(4,"Llegenda:");
$pdf->SetFont('','',0);
$pdf->Ln();
for($i=0; $i<$totalcredits; ++$i) {
	$pdf->SetFont('','b',0);
	$pdf->Write(4,"{$credit[$i][0]}");
	$pdf->SetFont('','',0);
	$pdf->Write(4," - {$credit[$i][3]} {$credit[$i][1]}.  ");	
}
$pdf->AddPage();
$pdf->SetFont('Arial','',11);
$aux=split(" ", $grup);

$pdf->SetY(35);
$pdf->SetFont('','b',12); 
$pdf->Cell(0,5,"Estadística d'avaluació","B",0,'R',0);
$pdf->SetFont('','',10);
$pdf->Ln(7);

$numalumnes=count($estadisticanom);
$totaprovat=0; $suspesa1=0; $suspeses2=0; $suspeses3=0; $suspeses4=0; $suspeses5omes=0;
for($i=0; $i<count($estadisticanom); ++$i) {
	if($estadisticansuspeses[$i]==0) ++$totaprovat;
	if($estadisticansuspeses[$i]==1) ++$suspesa1;
	if($estadisticansuspeses[$i]==2) ++$suspeses2;
	if($estadisticansuspeses[$i]==3) ++$suspeses3;
	if($estadisticansuspeses[$i]==4) ++$suspeses4;
	if($estadisticansuspeses[$i]>=5) ++$suspeses5omes;

}
$pdf->Write(4,"Alumnes avaluats: ".$numalumnes.(($sumnotesgrup!=-1&&$numnotesgrup!=0)?"  -  Nota mitjana de tot el grup: ".round($sumnotesgrup/$numnotesgrup,3):""));
$pdf->Ln(7);
$ample=45;
$pdf->Cell($ample,5,"Tot aprovat, $totaprovat - ".round(100*$totaprovat/$numalumnes, 1)."%",1,0,'C',1);
$pdf->Cell($ample,5,"1 suspesa, $suspesa1 - ".round(100*$suspesa1/$numalumnes, 1)."%",1,0,'C',1);
$pdf->Cell($ample,5,"2 suspeses, $suspeses2 - ".round(100*$suspeses2/$numalumnes, 1)."%",1,0,'C',1);
$pdf->Cell($ample,5,"3 suspeses, $suspeses3 - ".round(100*$suspeses3/$numalumnes, 1)."%",1,0,'C',1);
$pdf->Cell($ample,5,"4 suspeses, $suspeses4 - ".round(100*$suspeses4/$numalumnes, 1)."%",1,0,'C',1);
$pdf->Cell($ample,5,"5 o més suspeses, $suspeses5omes - ".round(100*$suspeses5omes/$numalumnes, 1)."%",1,0,'C',1);
$pdf->Ln();
$pdf->SetFontSize(6);
$valX=$pdf->GetX();
$valY=$pdf->GetY();
$maxY=0;
for($col=0; $col<6; ++$col) {
	for($i=0; $i<count($estadisticanom); ++$i) {
		if($estadisticansuspeses[$i]==$col||($estadisticansuspeses[$i]>=5 && $col==5)) {
			$pdf->SetX(($col*$ample)+$valX);
			$pdf->Cell($ample,3,$estadisticanom[$i],"TLR",0,'L',0);
			$pdf->Ln();
			if($pdf->GetY()>$maxY) $maxY=$pdf->GetY();
			if($estadisticansuspeses[$i]!=0) {
				$pdf->SetX(($col*$ample)+$valX);
				$pdf->Cell($ample,3,"  ".$estadisticacreditssuspes[$i],"BLR",0,'L',0);
				$pdf->Ln();
				if($pdf->GetY()>$maxY) $maxY=$pdf->GetY();
			}
		}
	}
	if($estadisticansuspeses[$i]==0 && $col==0) {$pdf->Cell($ample,3,"  ","T",0,'L',0); $pdf->Ln(); if($pdf->GetY()>$maxY) $maxY=$pdf->GetY();}
	$pdf->SetXY(($col*$ample)+$valX, $valY);
}
$pdf->SetY($maxY);
$pdf->Ln(3);
$pdf->SetFont('','b',12); 
$pdf->Cell(0,5,"Comentaris d'avaluació","B",0,'R',0);
$pdf->SetFont('','',10);
$pdf->Ln(7);
$pdf->SetFont('','',7);
$nomactual="";
for($i=0; $i<count($comentarisavaluaciomemo); ++$i) {
	if($nomactual!=$comentarisavaluacionom[$i]) {
		$pdf->Ln(2);
		$pdf->SetFont('','bu',7);
		$pdf->Cell(80,3,$comentarisavaluacionom[$i],0);
		$pdf->SetFont('','',7);
		$nomactual=$comentarisavaluacionom[$i];
		$pdf->Ln(3);
	}
	$pdf->SetFont('','b',7);
	$pdf->Cell(12,3,$comentarisavaluaciocred[$i],0);
	$pdf->SetFont('','',7);
	$pdf->MultiCell(0,3,urldecode($comentarisavaluaciomemo[$i]),0);
}
$pdf->SetFont('','',10);

$pdf->Output();
?>
