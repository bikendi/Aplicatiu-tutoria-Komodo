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
panyacces("Administrador");

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdf.php');
class PDF extends FPDF
{
  
  function Header()
  {
    global $logocentre, $nomcentre, $adrecacentre, $CPcentre, $poblaciocentre, $telfcentre, $webcentre;
    $this->Image($logocentre,20,16,25);
    $this->SetFont('Times','',8);
    $this->Text(50, 22, $nomcentre);
    $this->Text(50, 25, $adrecacentre);
    $this->Text(50, 28, "$CPcentre $poblaciocentre");
    $this->Text(50, 31, 'Telf. '.$telfcentre);
    $this->Text(50, 34, $webcentre);

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

$nomMesE = array ("de Gener", "de Febrer", "de Març", "d'Abril", "de Maig", "de Juny", "de Juliol", "d'Agost", "de Setembre", "d'Octubre", "de Novembre", "de Desembre");

if( $nal != 'tots' )
  $consulta_nal = " WHERE numero_mat='$nal' LIMIT 1";
else {
  $gru=split(' ', $grup);
  $consulta_nal = " WHERE (curs='$gru[0]' and grup='$gru[1]' and pla_estudi='$gru[2]')   ORDER BY cognom_alu, cognom2_al, nom_alum";
}

$consulta="SELECT nom_alum, cognom_alu, cognom2_al, curs, grup, pla_estudi, adreca, nom_munici, codi_posta, primer_tel, cognom1_pa, cognom2_pa, nom_pare, cognom1_ma, cognom2_ma, nom_mare, sexe, numero_mat FROM $bdalumnes.$tbl_prefix"."Estudiants ". $consulta_nal;

$conjunt_resultant=mysql_query($consulta, $connect);
while ( $fila=mysql_fetch_row($conjunt_resultant) )
{
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
  $nal = $fila[17];

// mysql_free_result($conjunt_resultant);





$linia=6;
$paragraf=20;
  $pdf->AddPage();
  
//   $pdf->Write($linia, $consulta);
  $pdf->SetFont('Arial','',12);
  $pdf->Write($linia,"$poblaciocentre, ".date('j')." ".$nomMesE[(date('n')-1)]." de ".date('Y'));
  $pdf->SetY($paragraf+$pdf->GetY());
  $pdf->Write($linia, "Senyors ");
  $pdf->SetFont('Arial','B',12);
  $pdf->Write($linia, "$cognom1_al $cognom2_al.");
  $pdf->SetFont('Arial','',12);
  $pdf->SetY($paragraf+$pdf->GetY());
  
  $consulta_pwd="SELECT identificador, passwd FROM $bdtutoria.$tbl_prefix"."pares WHERE refalumne='$nal' LIMIT 1";
  $conjunt_resultant_pwd=mysql_query($consulta_pwd, $connect);
  $fila_pwd=mysql_fetch_row($conjunt_resultant_pwd);
  
  $pdf->Write($linia, 
  "El nostre Institut disposa d'un servei d'informació personalitzada per als pares dels
nostres alumnes, amb l'objectiu de facilitar un camí de comunicació permanent i de fàcil
accés que els permeti autoinformar-se de les diferents dades específiques que disposem
".(($sexe=="HOME")?"del seu fill":"de la seva filla"));
$pdf->SetFont('Arial','B',12);
$pdf->Write($linia," $nom_alumne.

");
$pdf->SetFont('Arial','',12);
$pdf->Write($linia,
"Aquest accés a la informació es possible fer-lo mitjançant un navegador de pàgines
Web actualitzat i accedint a la pàgina Web de l'Institut. Un cop estiguin visualitzant 
la pàgina Web de l'Institut, han de clicar sobre l'enllaç de l' \"Accés per pares\".


Seguidament s'iniciarà un aplicatiu Web que els sol·licitarà un identificador i
contrasenya, en el seu cas és el següent:

                     Identificador:            $fila_pwd[0]
                     Contrasenya:          $fila_pwd[1]
     
Quan hagin validat la seva identificació, accediran a l'espai que els mostrarà diferents
tipus d'informació sobre ".(($sexe=="HOME")?"el seu fill":"la seva filla")." i un espai de comunicació directa amb el seu tutor/a,
que poden ser del seu interés.

Per a qualsevol dubte sobre les possibilitats que ofereix l'entorn d'informació
específica o qualsevol altre tipus, no dubtin en posar-se en contacte amb nosaltres.


Salutacions cordials.");

  mysql_free_result($conjunt_resultant_pwd);
}

mysql_free_result($conjunt_resultant);
  
$pdf->Output();

?>
