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
panyacces("Tutor");

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
		global $tipus, $quantitat, $etapa;
    
		$this->Line(30,280,182,280);
		$this->SetFont('Arial','',8);

		if( $tipus == 'F' ) {
			if( $quantitat >= 15 && $quantitat < 30 ) {
				$peu = "15 FALTES D'ASSISTÈNCIA NO JUSTIFICADES - " . $etapa;
			} elseif( $quantitat >= 30 && (($etapa == 'ESO' && $quantitat < 60) || $quantitat < 45) ) {
				$peu = "30 FALTES D'ASSISTÈNCIA NO JUSTIFICADES - " . $etapa;
			} elseif( $quantitat >= 45 && $quantitat < 60 ) {
				$peu = "45 FALTES D'ASSISTÈNCIA NO JUSTIFICADES - " . $etapa;
			} elseif( $quantitat >= 60 ) {
				$peu = "60 FALTES D'ASSISTÈNCIA NO JUSTIFICADES - " . $etapa;
			}
		} elseif( $tipus == 'R' ) { // retards
			$peu = "15 RETARDS EN L'ASSISTÈNCIA A CLASSE";
//		} elseif( $tipus == 'CC' ) { // comissió de convivència
//			$peu = "ACTA DE LA COMISSIÓ DE CONVIVÈNCIA";
		} elseif( $tipus == 'DT' ) { // sanció dimecres tarda
		} elseif( $tipus == 'Ll' ) { // apercebiment lliure
		}
		$this->SetXY(30, 285);
  		$this->Cell(0, 6, $peu, 0, 1, 'R');
    
	} // fi Footer
}


$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(30, 20, 30);

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

// tutor
  $consulta="SELECT P.nomreal FROM $bdtutoria.$tbl_prefix"."horariprofs H, $bdtutoria.$tbl_prefix"."usu_profes P WHERE P.usuari=H.idprof AND H.diasem='X' AND H.hora='X' AND H.grup='tutor_$curs%20$grup%20$etapa'";
  //echo "<p>Query: $consulta </p>";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $tutor = mysql_result( $conjunt_resultant, 0, 0);
mysql_free_result($conjunt_resultant);

$nomMesE = array ("de Gener", "de Febrer", "de Març", "d'Abril", "de Maig", "de Juny", "de Juliol", "d'Agost", "de Setembre", "d'Octubre", "de Novembre", "de Desembre");

	$height = 6;
	$salt = 3;
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',14);
  $pdf->Cell(0, 8, "AVÍS I AMONESTACIÓ ESCRITA", 0, 1, 'C');
  $pdf->Ln();
  $pdf->SetFont('Arial','',10);
  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"Srs.: $cognom1_al $cognom2_al", 0, 1);
  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"Pares de l'alumne/a: $nom_alumne", 0, 1);
  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"Curs: $curs $etapa $grup");
  $pdf->Ln();
  $pdf->MultiCell(0,$height,"Srs.:");
  $pdf->Ln($salt);
  // faltes
	if( $tipus == 'F' ) {
  		if( $quantitat >= 15 && $quantitat < 30 )
  			$informes = "els informes rebuts dels professors del vostre fill/lla, aquest/a";
  		else
  			$informes = "l'informe rebut del tutor/a Sr./Sra. $tutor, el vostre fill/lla";
  		$pdf->MultiCell(0,$height,"Poso en el seu coneixement que segons $informes ha comès $quantitat faltes d'assistència injustificades en el present curs acadèmic.");
		$pdf->Ln($salt);
  		$pdf->MultiCell(0,$height,"En aplicació del Decret 279/2006, de 4 de juliol, sobre drets i deures dels alumnes de nivell no universitari de Catalunya i de les disposicions complementàries del Consell Escolar, data 13 de març del 2000, us comuniquem que aquest fet està tipificat com una conducta contraria a les normes de convivència, sancionada amb la corresponent amonestació.");
		$pdf->Ln($salt);
  		//$pdf->MultiCell(0,$height,"En aplicació de  la legislació vigent disposeu d'un termini de tres dies per presentar les justificacions que considereu oportunes. En cas contrari, es consideraran com a definitives la tipificació i la sanció abans esmentades. Cal especificar el dia i l'hora de cada falta justificada en el model adjunt.");
  		if( $quantitat >= 45 && $quantitat < 60 ) {
			$pdf->Ln($salt);
			$pdf->MultiCell(0,$height,"En arribar a les 60 faltes injustificades es pot iniciar expedient disciplinari i la reiteració de conductes contraries a les normes de convivència en el centre es considera una falta que pot ser sancionada amb la inhabilitació definitiva per a cursar estudis al centre.");
		}
		$pdf->Ln($salt);
  		$pdf->MultiCell(0,$height,"Aprofitem per recordar-vos que per evitar aquesta situació hauríeu de seguir el procediment ordinari, justificant les absències amb el model que podeu recollir a consergeria en el termini màxim d'una setmana des de la reincorporació a les classes.");
	} elseif( $tipus == 'R' ) { // retards
  		$pdf->MultiCell(0,$height,"Poso en el seu coneixement que segons l'informe rebut del tutor/a Sr./Sra. $tutor, el vostre fill/lla acumula $quantitat retards en  la seva assistència a classe, demostrant que no ha modificat la seva conducta després d'avisar a la família per sms cada vegada que s'acumulaven apoximadament uns 5 retards més.");
		$pdf->Ln();
  		$pdf->MultiCell(0,$height,"Per aquest motiu se l'amonesta per escrit, avisant que properament es reunirà la comissió de convivència del centre per determinar la sanció corresponent.");
		$pdf->Ln();
  		$pdf->MultiCell(0,$height,"En cas de reincidència, se li obrirà expedient disciplinari, ja que aquesta conducta pot ser tipificada com a falta i, en conseqüència, la Sra. Directora del centre determinarà , si s'escau, la sanció que cregui oportuna.");	
//	} elseif( $tipus == 'CC' ) { // comissió de convivència
//	} elseif( $tipus == 'DT' ) { // sanció dimecres tarda
//	} elseif( $tipus == 'Ll' ) { // apercebiment lliure
	}

  $pdf->Ln();
  //$pdf->SetX(15+$pdf->GetX());
  $pdf->Cell(0,$height,$poblaciocentre.", ".date('j')." ".$nomMesE[(date('n')-1)]." de ".date('Y'), 0, 1);
  $pdf->Ln();


if( $tipus == 'F' ) {
	if( $quantitat >= 10 && $quantitat < 30 && $etapa == "ESO" ) {
		$nom_fitxer = "apercebiments_faltes_10_";
		$fitxer_descripcio = "Apercebiment 10 faltes";
		$signatura_1 = "El/la tutor/a";
		$signatura_2 = $tutor;
	} elseif( $quantitat >= 15 && $quantitat < 30 ) {
		$nom_fitxer = "apercebiments_faltes_15_";
		$fitxer_descripcio = "Apercebiment 15 faltes";
		$signatura_1 = "El/la tutor/a";
		$signatura_2 = $tutor;
	} elseif( $quantitat >= 30 && $quantitat < 45 ) {
		$nom_fitxer = "apercebiments_faltes_30_";
		$fitxer_descripcio = "Apercebiment 30 faltes";
		if( $etapa == "BATX" ) {
			if( $sexcoordbtx == "H" )
				$signatura_1 = "El Coordinador de Batxillerat";
			else
				$signatura_1 = "La Coordinadora de Batxillerat";
			$signatura_2 = $nomcoordbtx;
		} else {
			if( $sexcapdes == "H" )
				$signatura_1 = "El Cap d'Estudis";
			else
				$signatura_1 = "La Cap d'Estudis";
			$signatura_2 = $nomcapdes;
		}
	} elseif( $quantitat >= 45 && $quantitat < 60 ) {
		$nom_fitxer = "apercebiments_faltes_45_";
		$fitxer_descripcio = "Apercebiment 45 faltes";
		if( $sexcapdes == "H" )
			$signatura_1 = "El Cap d'Estudis";
		else
			$signatura_1 = "La Cap d'Estudis";
		$signatura_2 = $nomcapdes;
	} elseif( $quantitat >= 60 ) {
		$nom_fitxer = "apercebiments_faltes_60_";
		$fitxer_descripcio = "Apercebiment 60 faltes";
		if( $sexdirector == "H" )
			$signatura_1 = "El Director";
		else
			$signatura_1 = "La Directora";
		$signatura_2 = $nomdirector;
	}
} elseif( $tipus == 'R' ){
	$nom_fitxer = "apercebiments_retards_";
	$fitxer_descripcio = "Apercebiment retards";
	if( $etapa == "BATX" ) {
		if( $sexcoordbtx == "H" )
			$signatura_1 = "El Coordinador de Batxillerat";
		else
			$signatura_1 = "La Coordinadora de Batxillerat";
		$signatura_2 = $nomcoordbtx;
	} else {
		if( $sexcapdes == "H" )
			$signatura_1 = "El Cap d'Estudis";
		else
			$signatura_1 = "La Cap d'Estudis";
		$signatura_2 = $nomcapdes;
	}
}

$nom_aux = preg_replace("/[^A-Za-z0-9]/", "-", $nom_alumne);
$cognom_aux = preg_replace("/[^A-Za-z0-9]/", "-", $cognom1_al);

$nom_fitxer .= date('Y') . date('m'). date('d')."-". date('H'). date('i'). date('s') ."_${etapa}_${curs}${grup}_${nom_aux}_${cognom_aux}.pdf";

  $pdf->Cell(100,$height, $signatura_1, 0, 0);
  //$pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"He rebut l'original", 0, 1);
  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"Data:", 0, 1);
  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"D.N.I.", 0, 1);
  $pdf->Cell(100,$height, $signatura_2, 0, 0);
  //$pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"Signat: Pares/representants legals", 0, 1);

$pdf->Output("$dirfitxers/$nom_fitxer", 'F' );
$pdf->Output("$dirfitxers/$nom_fitxer", 'I' );

// registrar fitxer
//  $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."fitxers SET data=UNIX_TIMESTAMP(now()), ref_alum='$nal', nom_fitxer='".addslashes($nom_fitxer)."', descripcio='".addslashes($fitxer_descripcio)."', tipus_mime='application/pdf', tamany='$fitxerdesarfitxer_size'";
  $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."fitxers SET data=UNIX_TIMESTAMP(now()), ref_alum='$nal', nom_fitxer='".addslashes($nom_fitxer)."', descripcio='".addslashes($fitxer_descripcio)."', tipus_mime='application/pdf', tamany=". filesize("$dirfitxers/$nom_fitxer") .",  ref_fitxer='".addslashes($nom_fitxer)."', public=1";
  mysql_query($consulta, $connect);
  /*$consulta="SELECT last_insert_id() FROM $bdtutoria.$tbl_prefix"."fitxers";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $id=mysql_result($conjunt_resultant,0,0);
  mysql_free_result($conjunt_resultant);
  $ref_fitxer="f".$nal."_".$id;
  $consulta="UPDATE $bdtutoria.$tbl_prefix"."fitxers SET ref_fitxer='$ref_fitxer' WHERE id='$id'";
  mysql_query($consulta, $connect);*/


?>
