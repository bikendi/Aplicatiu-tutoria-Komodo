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

  }
}

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(20, 20, 20);
$pdf->SetFillColor(180);



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

$nomMesE = array ("de Gener", "de Febrer", "de Març", "d'Abril", "de Maig", "de Juny", "de Juliol", "d'Agost", "de Setembre", "d'Octubre", "de Novembre", "de Desembre");
$consulta="SELECT data, observacions, estat FROM $bdtutoria.$tbl_prefix"."avaluacions WHERE refaval='$avaluacio' limit 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
$datavaluacio=$fila[0];
$observacions=$fila[1];
$estataval=$fila[2];

foreach($llistaalumnes as $alum) {
  $pdf->AddPage();
  $pdf->SetFont('Arial','',11);
  $pdf->SetXY(120,20);
  $pdf->Cell(0,5,"$poblaciocentre, ".date('j',$datavaluacio)." ".$nomMesE[(date('n',$datavaluacio)-1)]." de ".date('Y',$datavaluacio),"",0,'R',0);
  $pdf->SetXY(120,25);
  $pdf->Cell(0,5,"Curs: $cursacademic","",0,'R',0);
  $pdf->SetY(45);
    
  $consulta="SELECT concat(nom_alum, ' ', cognom_alu, ' ', cognom2_al), curs, grup, pla_estudi, sexe FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$alum' limit 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $fila=mysql_fetch_row($conjunt_resultant);
  if($fila[4]=="HOME") $textalum="Alumne";
  else $textalum="Alumna";
  $pdf->Cell(17,5,"$textalum: ","B",0,'L',0);
  $pdf->SetFont('','b',14);
  $pdf->Cell(100,5,"$fila[0]","B",0,'L',0);
  $pdf->SetFont('','',11);  
  $pdf->Cell(0,5,"Informe d'avaluació","B",0,'R',0);
  $pdf->Ln();
  $pdf->Cell(12,5,"Curs:","B",0,'L',0);
  $pdf->SetFont('','b',0);
  $pdf->Cell(15,5,"$fila[1]","B",0,'L',0);
  $pdf->SetFont('','',0);
  $pdf->Cell(12,5,"Grup:","B",0,'L',0);
  $pdf->SetFont('','b',0);
  $pdf->Cell(15,5,"$fila[2]","B",0,'L',0);
  $pdf->SetFont('','',0);
  $pdf->Cell(25,5,"Pla Estudis:","B",0,'L',0);
  $pdf->SetFont('','b',0);
  $pdf->Cell(20,5,"$fila[3]","B",0,'L',0);
  $pdf->SetFont('','',0);
  $pdf->Cell(25,5,"Avaluació:","B",0,'R',0);
  $pdf->SetFont('','b',0);
  $pdf->Cell(0,5,"$avaluacio","B",0,'L',0);
  $pdf->SetFont('','',0);
  $pdf->Ln(8);
  $nomcursgrup="$fila[0] ($fila[1] $fila[2] $fila[3])";
  $contcomentari=0;
  unset($comentariavaluacioref);
  unset($comentariavaluaciomemo);
  if($estataval!="tancada") {
  	$consulta="SELECT DISTINCT h.assign, l.nomcredit, l.areaassign, l.tipus ";
  	$consulta.="FROM $bdtutoria.$tbl_prefix"."horariprofs h, $bdtutoria.$tbl_prefix"."subgrups s, $bdtutoria.$tbl_prefix"."llistacredits l ";
  	$consulta.="WHERE ((s.alumnes like '%$alum%' and h.grup like concat('%', s.ref_subgrup, '%20', s.nom, '%'))  or  h.grup like '%".rawurlencode($grup)."%') and l.codi=h.assign ";
  	$consulta.="ORDER BY l.nomcredit";
  }
  else {
  	$consulta="SELECT DISTINCT n.ref_credit, l.nomcredit, l.areaassign, l.tipus ";
  	$consulta.="FROM $bdtutoria.$tbl_prefix"."notes n, $bdtutoria.$tbl_prefix"."llistacredits l ";
  	$consulta.="WHERE n.ref_alum='$alum' and n.ref_aval='$avaluacio' and l.codi=n.ref_credit ";
  	$consulta.="ORDER BY l.nomcredit";	  
  }
/*  $pdf->SetFont('','',8);
  $a = substr($consulta, 0, 100);
  $pdf->Cell(0,5,$a,0,1,'L',0);
  $b= substr($consulta, 101);
  $pdf->Cell(0,5,$b,0,1,'L',0);
  $c= substr($consulta, 201);
  $pdf->Cell(0,5,$c,0,1,'L',0);*/
  $conjunt_resultant=mysql_query($consulta, $connect);
  $i=0;
  while($fila=mysql_fetch_row($conjunt_resultant)) {
	  $codis[$i][0]=$fila[0];
	  $codis[$i][1]=$fila[1];
	  $codis[$i][2]=$fila[2];
	  $codis[$i][3]=$fila[3];
	  ++$i;
  }
  
  $cont=0;
  for ($a=0; $a<$i; ++$a) if($codis[$a][3]=='CC') ++$cont;
  $pdf->SetFont('','b',0);
  $pdf->Cell(30,5,"Qualificacions", 0,0,'L',0);
  $pdf->SetFont('','',8);
  $pdf->Cell(0,5,rawurldecode($observacions), 0,0,'R',0);
  $pdf->SetFont('','',11);
  $pdf->Ln();
  if ($cont!=0) {
	$pdf->Cell(145,5,"Crèdits de matèries comunes", 1,0,'L',1);
	$pdf->Cell(0,5,"Qualificació","LRTB",0,'C',1);
	$pdf->Ln();
	for ($a=0; $a<$i; ++$a) {
		if($codis[$a][3]=='CC') {
			$consulta="SELECT valor, memo, usuari FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='$avaluacio' and ref_alum='$alum' and ref_credit='{$codis[$a][0]}' limit 1";
			$conjunt_resultant=mysql_query($consulta, $connect);
			$fila=mysql_fetch_row($conjunt_resultant);
			
			$pdf->Cell(15,5,"{$codis[$a][0]}","LR",0,'L',0);
			$pdf->Cell(125,5,rawurldecode($codis[$a][1]),"L",0,'L',0);
			if($fila[1]!="") {
				++$contcomentari;
				$pdf->SetFont('','',5);
				$pdf->Cell(5,5,"(".$contcomentari.")","",0,'C',0);
				$pdf->SetFont('','',11);
				$comentariavaluacioref[]="(".$contcomentari.")";
				$comentariavaluaciomemo[]=$fila[1];	
			}
			else $pdf->Cell(5,5,"","",0,'C',0);
			$aux=explode('z',$fila[0]);
			if($aux[count($aux)-1]=='') $aux="";
			for($j=0; $j<count($aux); ++$j) {
				if($j!=(count($aux)-1)) $pdf->SetFont('','',5);
				$pdf->Cell(25/count($aux),5,"$aux[$j]",(($j==0)?"L":(($j==(count($aux)-1))?"R":"")),0,'C',0);
				if($j!=(count($aux)-1)) $pdf->SetFont('','',11);	
			}
			if($fila[0]==0 || count($aux)==1) $pdf->Cell(25,5,"","LR",0,'C',0);
			$pdf->Ln();
		}
	}
	$pdf->Cell(145,5,"","T",0,'L',0);
	$pdf->Cell(0,5,"","T",0,'C',0);
	$pdf->Ln();
  }
  $cont=0;
  for ($a=0; $a<$i; ++$a) if($codis[$a][3]=='CM') ++$cont;
  if ($cont!=0) {
	$pdf->Cell(145,5,"Crèdits de matèries de modalitat", 1,0,'L',1);
	$pdf->Cell(0,5,"Qualificació","LRTB",0,'C',1);
	$pdf->Ln();
	for ($a=0; $a<$i; ++$a) {
		if($codis[$a][3]=='CM') {
			$consulta="SELECT valor, memo, usuari FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='$avaluacio' and ref_alum='$alum' and ref_credit='{$codis[$a][0]}' limit 1";
			$conjunt_resultant=mysql_query($consulta, $connect);
			$fila=mysql_fetch_row($conjunt_resultant);
			
			$pdf->Cell(15,5,"{$codis[$a][0]}","LR",0,'L',0);
			$pdf->Cell(125,5,rawurldecode($codis[$a][1]),"L",0,'L',0);
			if($fila[1]!="") {
				++$contcomentari;
				$pdf->SetFont('','',5);
				$pdf->Cell(5,5,"(".$contcomentari.")","",0,'C',0);
				$pdf->SetFont('','',11);
				$comentariavaluacioref[]="(".$contcomentari.")";
				$comentariavaluaciomemo[]=$fila[1];	
			}
			else $pdf->Cell(5,5,"","",0,'C',0);
			$aux=explode('z',$fila[0]);
			if($aux[count($aux)-1]=='') $aux="";
			for($j=0; $j<count($aux); ++$j) {
				if($j!=(count($aux)-1)) $pdf->SetFont('','',5);
				$pdf->Cell(25/count($aux),5,"$aux[$j]",(($j==0)?"L":(($j==(count($aux)-1))?"R":"")),0,'C',0);
				if($j!=(count($aux)-1)) $pdf->SetFont('','',11);	
			}
			if($fila[0]==0 || count($aux)==1) $pdf->Cell(25,5,"","LR",0,'C',0);
			$pdf->Ln();
		}
	}
	$pdf->Cell(145,5,"","T",0,'L',0);
	$pdf->Cell(0,5,"","T",0,'C',0);
	$pdf->Ln();	  
  }
  $cont=0;
  for ($a=0; $a<$i; ++$a) if($codis[$a][3]=='CO') ++$cont;
  if ($cont!=0) {
	$pdf->Cell(145,5,"Crèdits de matèries optatives", 1,0,'L',1);
	$pdf->Cell(0,5,"Qualificació","LRTB",0,'C',1);
	$pdf->Ln();
	for ($a=0; $a<$i; ++$a) {
		if($codis[$a][3]=='CO') {
			$consulta="SELECT valor, memo, usuari FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='$avaluacio' and ref_alum='$alum' and ref_credit='{$codis[$a][0]}' limit 1";
			$conjunt_resultant=mysql_query($consulta, $connect);
			$fila=mysql_fetch_row($conjunt_resultant);
			
			$pdf->Cell(15,5,"{$codis[$a][0]}","LR",0,'L',0);
			$pdf->Cell(125,5,rawurldecode($codis[$a][1]),"L",0,'L',0);
			if($fila[1]!="") {
				++$contcomentari;
				$pdf->SetFont('','',5);
				$pdf->Cell(5,5,"(".$contcomentari.")","",0,'C',0);
				$pdf->SetFont('','',11);
				$comentariavaluacioref[]="(".$contcomentari.")";
				$comentariavaluaciomemo[]=$fila[1];	
			}
			else $pdf->Cell(5,5,"","",0,'C',0);
			$aux=explode('z',$fila[0]);
			if($aux[count($aux)-1]=='') $aux="";
			for($j=0; $j<count($aux); ++$j) {
				if($j!=(count($aux)-1)) $pdf->SetFont('','',5);
				$pdf->Cell(25/count($aux),5,"$aux[$j]",(($j==0)?"L":(($j==(count($aux)-1))?"R":"")),0,'C',0);
				if($j!=(count($aux)-1)) $pdf->SetFont('','',11);	
			}
			if($fila[0]==0 || count($aux)==1) $pdf->Cell(25,5,"","LR",0,'C',0);
			$pdf->Ln();
		}
	}
	$pdf->Cell(145,5,"","T",0,'L',0);
	$pdf->Cell(0,5,"","T",0,'C',0);
	$pdf->Ln();	  
  }  
  $cont=0;
  for ($a=0; $a<$i; ++$a) if($codis[$a][3]=='CV') ++$cont;
  if ($cont!=0) {
	$pdf->Cell(145,5,"Crèdits variables", 1,0,'L',1);
	$pdf->Cell(0,5,"Qualificació","LRTB",0,'C',1);
	$pdf->Ln();
	for ($a=0; $a<$i; ++$a) {
		if($codis[$a][3]=='CV') {
			$consulta="SELECT valor, memo, usuari FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='$avaluacio' and ref_alum='$alum' and ref_credit='{$codis[$a][0]}' limit 1";
			$conjunt_resultant=mysql_query($consulta, $connect);
			$fila=mysql_fetch_row($conjunt_resultant);
			
			$pdf->Cell(15,5,"{$codis[$a][0]}","LR",0,'L',0);
			$pdf->Cell(125,5,rawurldecode($codis[$a][1]),"L",0,'L',0);
			if($fila[1]!="") {
				++$contcomentari;
				$pdf->SetFont('','',5);
				$pdf->Cell(5,5,"(".$contcomentari.")","",0,'C',0);
				$pdf->SetFont('','',11);
				$comentariavaluacioref[]="(".$contcomentari.")";
				$comentariavaluaciomemo[]=$fila[1];	
			}
			else $pdf->Cell(5,5,"","",0,'C',0);
			$aux=explode('z',$fila[0]);
  		    if($aux[count($aux)-1]=='') $aux="";
			for($j=0; $j<count($aux); ++$j) {
				if($j!=(count($aux)-1)) $pdf->SetFont('','',5);
				$pdf->Cell(25/count($aux),5,"$aux[$j]",(($j==0)?"L":(($j==(count($aux)-1))?"R":"")),0,'C',0);
				if($j!=(count($aux)-1)) $pdf->SetFont('','',11);	
			}
			if($fila[0]==0 || count($aux)==1) $pdf->Cell(25,5,"","LR",0,'C',0);
			$pdf->Ln();
		}
	}
	$pdf->Cell(145,5,"","T",0,'L',0);
	$pdf->Cell(0,5,"","T",0,'C',0);
	$pdf->Ln();	  
  }  
  $pdf->Cell(0,5,"Comentaris de l'avaluació", 1,0,'L',1);
  $pdf->Ln(7);
  for($i=0; $i<count($comentariavaluaciomemo); ++$i) {
	$pdf->SetFont('','',5);
  	$pdf->Cell(4,3,$comentariavaluacioref[$i],0,0,'C',0);
  	$pdf->SetFont('','',7);
  	$pdf->MultiCell(0,3,urldecode($comentariavaluaciomemo[$i]),0,'L',0);
  	$pdf->SetFont('','',11);
  	  
  }

	
	$pdf->SetY(230);
	$pdf->Image("imatges/tisores.jpg",18,232,3);
	$pdf->Cell(0,5,"-----------------------------------------------------------------------------------------------------------------------------------");
	$pdf->Ln();
	$pdf->Cell(0,5,"Cal retornar aquesta part al tutor/a, degudament signada per un dels pares o representant legal.","",0,'L',0);
	$pdf->Ln();
	$pdf->Cell(25,5,"Signatures","B",0,'L',0);
	$pdf->SetFont('','',8);
	$pdf->Cell(0,5,"Avaluació: $avaluacio                   $textalum: $nomcursgrup","B",0,'R',0);
	$pdf->SetFont('','',11);
	$pdf->Ln();
	$pdf->Cell(47,5,"El/La tutor/a","",0,'L',0);
	$pdf->Cell(57,5,"Segell del centre","",0,'C',0);
	$pdf->Cell(0,5,"La mare, el pare o representant legal","",0,'R',0);
  
	$pdf->SetY(267);
       $pdf->Cell(0,5,cercaTutor($grup),"",0,'L',0);
	$pdf->Ln();
	$pdf->SetFont('','I',6);
       $pdf->Cell(0,3,"Data impressió: ".date('j')."-".date('n')."-".date('Y').", ".date('H').":".date('i'),"",0,'L',0);

  }


$pdf->Output();


?>
