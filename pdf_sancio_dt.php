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

    $this->Ln(25);
  }

  
	function Footer()
	{
		global $tipus, $quantitat, $etapa;
    
		$this->Line(30,280,182,280);
		$this->SetFont('Arial','',8);

		$peu = "Avís treball comunitari";

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
  //$consulta="SELECT P.nomreal FROM $bdtutoria.$tbl_prefix"."horariprofs H, $bdtutoria.$tbl_prefix"."usu_profes P WHERE P.usuari=H.idprof AND H.diasem='X' AND H.hora='X' AND H.grup='tutor_$curs%20$grup%20$etapa'";
  //echo "<p>Query: $consulta </p>";
  //$conjunt_resultant=mysql_query($consulta, $connect);
  //$tutor = mysql_result( $conjunt_resultant, 0, 0);
mysql_free_result($conjunt_resultant);

$nomMesE = array ("de Gener", "de Febrer", "de Març", "d'Abril", "de Maig", "de Juny", "de Juliol", "d'Agost", "de Setembre", "d'Octubre", "de Novembre", "de Desembre");

	$height = 6;
	$height_2 = 8;
	$salt = 3;
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',14);
  $pdf->Cell(0, 8, "ATENCIÓ PERSONALITZADA A L'ALUMNE/A", 0, 1, 'C');
  $pdf->Ln();
  $pdf->SetFont('Arial','',10);

	$pdf->Cell(40, $height_2, "El Professor/a", 1, 0, 'C');
	$pdf->Cell(0, $height_2, "   ". $sess_nomreal, 1, 1);
	$pdf->Cell(0, $height_2, "   Amb data d'avui, dia ". date('j')." ".$nomMesE[(date('n')-1)]." de ".date('Y'), 1, 1);
	$pdf->Cell(0, (2*$height_2), "   Sanciona a l'alumne $nom_alumne $cognom1_al ", 1,1);
	$pdf->Cell(70, $height_2, "A romandre al centre el DIMECRES", 1, 0, 'C');
	$pdf->Cell(44, $height_2, stripslashes($data), 1, 0, 'C');
	$pdf->Cell(0, $height_2, stripslashes($hora), 1, 1, 'C');
	$pdf->MultiCell(0, $height_2, "   A causa de: ". stripslashes($motiu), 1);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, (2*$height_2), "PER FER TREBALL COMUNITARI", 1, 'C');
	$pdf->SetFont('Arial','',10);

	$pdf->Ln(4);
	$pdf->SetX(70+$pdf->GetX());
	$pdf->Cell(0, $height, "Signat, el professor/a", 0, 1);

	$pdf->Ln(20);
	$pdf->MultiCell(0, $height, "El seu fill/a, a causa del seu comportament incorrecte ha estat sancionat i haurà de romandre al centre el dimecres a la tarda sota la vigilància d'un professor. Durant aquesta estona haurà de realitzar les activitats de treball comunitari que se li encomanin.");

	$pdf->Ln($salt);
	$pdf->MultiCell(0, $height, "S'informa que l'assistència de l'alumne és obligatòria, per tant es necessari comunicar prèviament i justificar la no assistència, en cas contrari l'alumne podria incórrer en una conducta contraria a les normes de convivència i se'n podria derivar l'obertura d'un expedient.");

	$pdf->Ln($salt);
	$pdf->SetFont('Arial','B',10);
	$pdf->MultiCell(0, $height, "Properament es reunirà la comissió de convivència per determinar la sanció definitiva.");
	$pdf->SetFont('Arial','',10);

  $pdf->Ln(10);

  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"He rebut l'original", 0, 1);
  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"Data:", 0, 1);
  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"D.N.I.", 0, 1);
  $pdf->SetX(100+$pdf->GetX());
  $pdf->Cell(0,$height,"Signat: Pares/representants legals", 0, 1);

$nom_aux = preg_replace("/[^A-Za-z0-9]/", "-", $nom_alumne);
$cognom_aux = preg_replace("/[^A-Za-z0-9]/", "-", $cognom1_al);
$nom_fitxer = "avis_tc_" . date('Y') . date('m'). date('d')."-". date('H'). date('i'). date('s') ."_${etapa}_${curs}${grup}_${nom_aux}_${cognom_aux}.pdf";
$fitxer_descripcio = "Sanció dimecres tarda";
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
