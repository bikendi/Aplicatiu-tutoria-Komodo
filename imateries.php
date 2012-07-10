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
?>
<html>
<head>
<title>Tutoria</title>
<?
@include("linkbd.inc.php");
@include("comu.php");
@include("comu.js.php");
panyacces("Administrador");

?>

</head>
<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
print("
<table border='0' width='100%'><tr><td align='center'>");
print("</td>
<td align='right'><font size='6'>Importar matèries des d'un fitxer CSV&nbsp; &nbsp; </font>");
$consulta="SHOW TABLE STATUS from $bdalumnes LIKE '$tbl_prefix"."Estudiants_Materies' ";
$conjunt_resultant=mysql_query($consulta, $connect);
$consulta1="select DATE_FORMAT('".mysql_result($conjunt_resultant,0,11)."', '<br>Darrera actualitzaci&oacute;: %d-%m-%Y a les %H:%i:%s')";
mysql_free_result($conjunt_resultant);
$conjunt_resultant1=mysql_query($consulta1, $connect);
print(mysql_result($conjunt_resultant1,0,0));
mysql_free_result($conjunt_resultant1);
print("</td>
</tr>
</table>
<hr>
");

if(isset($valida)&&$valida==1) {
  
  $consulta="DROP TABLE IF EXISTS `$bdalumnes`.`$tbl_prefix"."Estudiants_Materies`";
  mysql_query($consulta, $connect);
  $consulta="ALTER TABLE `$bdalumnes`.`$tbl_prefix"."Estudiants_Materies_Tmp` RENAME `$bdalumnes`.`$tbl_prefix"."Estudiants_Materies`";
  mysql_query($consulta, $connect);
  
  $trans=Array('á'=>'a','à'=>'a','ä'=>'a','â'=>'a', 'é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
                   'í'=>'i','ì'=>'i','ï'=>'i','î'=>'i', 'ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o',
                   'ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u', 'ç'=>'c','ñ'=>'ny','l·l'=>'l',
                   'Á'=>'a','À'=>'a','Ä'=>'a','Â'=>'a', 'É'=>'e','È'=>'e','Ë'=>'e','Ê'=>'e',
                   'Í'=>'i','Ì'=>'i','Ï'=>'i','Î'=>'i', 'Ó'=>'o','Ò'=>'o','Ö'=>'o','Ô'=>'o',
                   'Ú'=>'u','Ù'=>'u','Ü'=>'u','Û'=>'u', 'Ç'=>'c','Ñ'=>'ny','L·L'=>'l',' '=>'' );

	// Actualitzem llistacrèdits
	$query = "TRUNCATE TABLE `$bdalumnes`.`$tbl_prefix"."llistacredits`";
	if( !mysql_query($query, $connect) ) {
		echo "<p> Query: $query </p> \n";
		print("<br>Error: ". mysql_error() .".<br>");
	}
	$query = " INSERT INTO `$bdalumnes`.`$tbl_prefix"."llistacredits` (codi, pla_estudis, nivell, areaassign, nomcredit) SELECT codi_credit, trim(substring(pla_estudis,1,4)), min(nivell), codi_area, nom_credit FROM `$bdalumnes`.`$tbl_prefix"."Estudiants_Materies` GROUP BY codi_credit, trim(substring(pla_estudis,1,4)), codi_area, nom_credit";
	if( !mysql_query($query, $connect) ) {
		echo "<p> Query: $query </p> \n";
		print("<br>Error: ". mysql_error() .".<br>");
	}
	// actualitzar relacions subgrups
	$query = "SELECT * FROM $bdalumnes.$tbl_prefix"."rel_subgrups ORDER BY ref_subgrup, codi_credit, curs";
	//echo "<p> Query: $consulta </p> \n";
	$resultat = mysql_query( $query );
	//echo "<p> Esborra curriculum: $esborra_curriculum </p> \n";
	$subgrup_anterior = '';
	while( $relacio=mysql_fetch_object($resultat) ) {
		// afegir els alumnes
		if( $subgrup_anterior != $relacio->ref_subgrup && isset($esborra_curriculum) ) {
			$subgrup_anterior = $relacio->ref_subgrup;
			$consulta="UPDATE $bdtutoria.$tbl_prefix"."subgrups SET alumnes='' WHERE ref_subgrup='". $relacio->ref_subgrup ."'";
				$actual_array = Array();
			if( !mysql_query($consulta, $connect) ) {
				echo "<p> Query: $consulta </p> \n";
				print("<br>Error: ". mysql_error() .".<br>");
			}
		} else {
			// alumnes actuals
			$consulta="SELECT alumnes FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='". $relacio->ref_subgrup ."' limit 1";
	 		//echo "<p> Query: $consulta </p> \n";
			$conjunt_resultant=mysql_query($consulta, $connect);
			if( mysql_num_rows($conjunt_resultant) > 0 ) {
				$alssubgrup = mysql_result($conjunt_resultant, 0,0);
				if( !empty($alssubgrup) )
					$actual_array = explode(',', $alssubgrup);
				else
					$actual_array = Array();
			} else 
				$actual_array = Array();
		}
		// alumnes nous
		$nous_array = Array();
		$query = "SELECT E.numero_mat FROM $bdtutoria.$tbl_prefix"."Estudiants_Materies EM, $bdtutoria.$tbl_prefix"."Estudiants E WHERE EM.numero_mat=E.numero_mat AND EM.codi_credit='". $relacio->codi_credit ."' AND concat(E.curs, ' ', E.grup, ' ', E.pla_estudi)='". $relacio->curs ."'";
 		//echo "<p> Query: $query </p> \n";
		$res = mysql_query( $query );
		while( $fila=mysql_fetch_row($res) )
			$nous_array[] = $fila[0];
		mysql_free_result($res);

		// els juntem
		$final_array = array_merge( $actual_array, $nous_array );
		$final_array = array_unique( $final_array );
		$alsfinals = implode(',', $final_array );
		//print_r($final_array);
// 	print("<br>Finals: $alssubgrup <br>");
		$consulta="UPDATE $bdtutoria.$tbl_prefix"."subgrups SET alumnes='$alsfinals' WHERE ref_subgrup='". $relacio->ref_subgrup ."' limit 1";
// 	echo "<p> Query: $consulta </p> \n";
		mysql_query($consulta, $connect);
	} // fi while relacio subgrups

  print("Els canvis han estat registrats.<hr></body></html>");
  exit;
} // if valida

if(isset($valida)&&$valida==0) {

  $consulta="DROP TABLE IF EXISTS `$bdalumnes`.`$tbl_prefix"."Estudiants_Materies_Tmp`";
  mysql_query($consulta, $connect);
  print("Els canvis no han tingut efecte.<hr></body></html>");
  exit;
}

if(isset($fitxeralumnes)&&$fitxeralumnes!='') {

  $consulta="DROP TABLE IF EXISTS `$bdalumnes`.`$tbl_prefix"."Estudiants_Materies_Tmp`";
  echo "<p>Query: $consulta</p>\n";
  mysql_query($consulta, $connect);

  $consulta="CREATE TABLE `$bdalumnes`.`$tbl_prefix"."Estudiants_Materies_Tmp` LIKE `$bdalumnes`.`$tbl_prefix"."Estudiants_Materies`";
  echo "<p>Query: $consulta</p>\n";
  mysql_query($consulta, $connect);

  $inserits=0;
  $lins=file($fitxeralumnes);
  $errors=false;
  $in=1;
  foreach ($lins as $linia_num => $linia) { 
    if($linia!="\r\n" && $linia!="" && $linia!="\r") {
	$li=$linia;

	$linia=str_replace(',',';',$linia); 
	$linia=str_replace('";"','","',$linia);
      	$linia=substr_replace($linia, ",", strpos($linia,';'),1);
      	$values=preg_split('/,/', $linia);

      	$numr=trim($values[0]);

	$codi_credit = trim($values[7]);

      	$cognom1=(($values[1]!="\"-\"")?$values[1]:"\"\"");
      	$cognom2=(($values[2]!="\"-\"")?$values[2]:"\"\"");
      	$nom=(($values[3]!="\"-\"")?$values[3]:"\"\"");

	$pla_estudis = trim($values[4]);
	$nivell = trim($values[5]);
	$codi_area = trim($values[6]);
      	$nom_credit = trim($values[8]);


      	if($numr=="#") {--$in; continue;}

	//$consulta="INSERT INTO $bdalumnes.$tbl_prefix"."Estudiants_Materies_Tmp VALUES ($numr, '$codi_credit', '', '$cognom1', '$cognom2', '$nom', '$pla_estudis', '$nivell', '$codi_area', '$nom_credit')";
	$consulta="INSERT INTO $bdalumnes.$tbl_prefix"."Estudiants_Materies_Tmp VALUES ($numr, $codi_credit, '', $cognom1, $cognom2, $nom, $pla_estudis, $nivell, $codi_area, $nom_credit)";
// 	echo "<p> Query: $consulta </p> \n";
	if(true==mysql_query($consulta, $connect)) ++$inserits;
	else {
        	print("<br><font color='#ff0000'><b>ERROR:</b></font> No s'ha inserit la fila (".($linia_num+$in)."), comprova la seva sintaxi, o que tingui el identificador-refer&egrave;ncia repetit o que el cognom de l'alumne sigui buit:<pre>$li</pre>");
        	$errors=true;
	}
    } // if linia ...
  } // foreach
  
  unlink($fitxeralumnes);
  $consulta="UPDATE $bdalumnes.$tbl_prefix"."Estudiants_Materies_Tmp EM, $bdalumnes.$tbl_prefix"."Estudiants E SET EM.numero_mat=E.numero_mat WHERE COGNOM_ALU=cognom1 AND COGNOM2_AL=cognom2 AND NOM_ALUM=nom";
//   echo "<p> Query: $consulta </p> \n";
  $res = mysql_query($consulta, $connect);
  print("<br>". mysql_affected_rows() ." matched.<br>");
  if(!$errors) {
	print("<b>Pas 3:</b>");
	print("<br>S'ha trobat $inserits alumnes en el fitxer carregat.<hr>");

	// comprovar els que ja no hi són
	$consulta="SELECT id, codi_credit, cognom1, cognom2, nom FROM $bdalumnes.$tbl_prefix"."Estudiants_Materies";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$esborrar='';
	$nohison='';
	while($fila=mysql_fetch_row($conjunt_resultant)) {
		$consulta1="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants_Materies_Tmp WHERE codi_credit='$fila[1]' AND cognom1='$fila[2]' AND cognom2='$fila[3]' AND nom='$fila[4]' ";
		$conjunt_resultant1=mysql_query($consulta1, $connect);
		if(0==mysql_result($conjunt_resultant1,0,0)) {
			$nohison.="<tr><td bgcolor='#ffc0ff'>$fila[0] $fila[1] $fila[2] $fila[3]</td></tr>";
			if($esborrar!='') $esborrar.=',';
				$esborrar.=$fila[0]; 
		}
		mysql_free_result($conjunt_resultant1);
	}
	mysql_free_result($conjunt_resultant);
	if($nohison!='') {
		print("<p><b>Els seg&uuml;ents alumnes actuals, no apareixen en el nou llistat carregat.</b><br>Si valides els canvis s'esborraran.");
		print("<table border='0'>$nohison</table>");
	}

	// comprovar els nous
	$consulta="SELECT id, codi_credit, cognom1, cognom2, nom FROM $bdalumnes . $tbl_prefix" . "Estudiants_Materies_Tmp";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$nous='';
	while($fila=mysql_fetch_row($conjunt_resultant)) {
		$consulta1="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants_Materies WHERE codi_credit='$fila[1]' AND cognom1='$fila[2]' AND cognom2='$fila[3]' AND nom='$fila[4]'";
		$conjunt_resultant1=mysql_query($consulta1, $connect);
		if(0==mysql_result($conjunt_resultant1,0,0)) {
			$nous.="<tr><td bgcolor='#c0ffff'>$fila[1] ($fila[2] $fila[3] $fila[4])</td></tr>";
		}
		mysql_free_result($conjunt_resultant1);
	}
	mysql_free_result($conjunt_resultant);
	if($nous!='') {
		print("<p><b>Els seg&uuml;ents alumnes s&oacute;n nous en el nou llistat carregat i es donaran d'alta:</b>");
		print("<table border='0'>$nous</table>");
	}
   
	print("<br><center><form name='valida' method='post' action='".$PHP_SELF."?idsess=$idsess&valida=1'>");
	if ($esborrar!='') print("<input type='hidden' name='esborrar' value='$esborrar'>");
	print("<p><label>Esborrar matrícula antiga </label><input type='checkbox' name='esborra_curriculum' id='esborra_curriculum' value='1' checked='checked' /></p>");
	print("<input type='button' value='&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Validar canvis &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;' onClick='if(confirm(\"Segur que vols validar aquests canvis?\")) document.valida.submit();'>");
	print("</form></center>"); 

  } // if !errors
  print("<br><center><form name='novalida' method='post' action='".$PHP_SELF."?idsess=$idsess&valida=0'>");
  print("<input type='button' value='Descartar canvis i tornar a la situaci&oacute; inicial' onClick='document.novalida.submit();'>");
  print("</form></center><br>");
}



if(!isset($fitxeralumnes)||$fitxeralumnes=='') {
  print("
  <form name='introd1' method='post' action='$PHP_SELF?idsess=$idsess' enctype='multipart/form-data'>
  <b>Pas 1:</b><br>
  Crea un fitxer *.csv, amb una fila de capçalera i una fila per a cada alumne, amb el següent format (cada camp entre cometes no pot contenir unes altres cometes, el primer camp &eacute;s el n&uacute;mero d'ordre sense cometes):
  <p><font color='#0000ff'>
  <pre>
   #,\"identificador-referencia\",\"cognom1\",\"cognom2\",\"nom\",\"pla_estudis\",\"nivell\",\"codi_area\",\"codi_credit\",\"nom_credit\"
   1, .......
  </pre>
  </font></p>
  <p>

  <b>Pas 2:</b><br>
  A continuaci&oacute;, selecciona el fitxer *.csv, creat en el pas anterior, i carrega'l.<p>
  <input type='hidden' name='MAX_FILE_SIZE' value='500000'>
  &nbsp; &nbsp; <b>Fitxer:</b> <input type='file' name='fitxeralumnes'> <font size='-2'>(m&agrave;x.: <script language='JavaScript'>document.write(document.forms.introd1.MAX_FILE_SIZE.value +\" bytes\");</script>)</font>
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type='submit' value='Carregar'>
  </form>");
}
?>

<hr>
</body>
</html>
