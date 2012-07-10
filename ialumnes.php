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
if(!((isset($fitxeralumnes)&&$fitxeralumnes!='')||isset($valida))) 
	print("<a href='ialumnesw.php?idsess=$idsess' title='Des del programa Winsec'>Importaci&oacute;<br>Winsec</a>");
else 
	print("&nbsp;");
print("</td>
<td align='right'><font size='6'>Importar Alumnes des d'un fitxer CSV&nbsp; &nbsp; </font>");
$consulta="SHOW TABLE STATUS from $bdalumnes LIKE '$tbl_prefix"."Estudiants' ";
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
// ' (sintaxis Bluefish)
if(isset($valida)&&$valida==1) {
  
  if(isset($esborrar)&&$esborrar!="") {
    $esbor=preg_split('/,/', $esborrar);
    for ($i=0; $i<count($esbor); ++$i) {
	  $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."EstudiantsEsborrats SELECT * FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$esbor[$i]'";
	  mysql_query($consulta, $connect);
  	}
  }
  $consulta="DROP TABLE IF EXISTS `$bdalumnes`.`$tbl_prefix"."Estudiants`";
  mysql_query($consulta, $connect);
  $consulta="ALTER TABLE `$bdalumnes`.`$tbl_prefix"."EstudiantsTmp` RENAME `$bdalumnes`.`$tbl_prefix"."Estudiants`";
  mysql_query($consulta, $connect);
  
  $trans=Array('á'=>'a','à'=>'a','ä'=>'a','â'=>'a', 'é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
                   'í'=>'i','ì'=>'i','ï'=>'i','î'=>'i', 'ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o',
                   'ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u', 'ç'=>'c','ñ'=>'ny','l·l'=>'l',
                   'Á'=>'a','À'=>'a','Ä'=>'a','Â'=>'a', 'É'=>'e','È'=>'e','Ë'=>'e','Ê'=>'e',
                   'Í'=>'i','Ì'=>'i','Ï'=>'i','Î'=>'i', 'Ó'=>'o','Ò'=>'o','Ö'=>'o','Ô'=>'o',
                   'Ú'=>'u','Ù'=>'u','Ü'=>'u','Û'=>'u', 'Ç'=>'c','Ñ'=>'ny','L·L'=>'l',' '=>'','\''=>'' );
  $consulta="SELECT numero_mat, nom_alum, cognom_alu, contactes FROM $bdalumnes.$tbl_prefix"."Estudiants";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
    $consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."pares WHERE refalumne='$fila[0]'";
    $conjunt_resultant1=mysql_query($consulta1, $connect);
    if(0==mysql_result($conjunt_resultant1,0,0)) {
      $fila[1]=strtolower($fila[1]);
      $fila[2]=strtolower($fila[2]);
      $fila[1]=strtr($fila[1],$trans);
      $fila[2]=strtr($fila[2],$trans);
      $nf=2; // TODO: why 2 and not 1?
      do {
      	$identpare=substr($fila[1],0,1).$fila[2].$nf;
      	// TODO: conflict with usu_profes?
      	$consulta2="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."pares WHERE identificador='$identpare'";
      	$conjunt_resultant2=mysql_query($consulta2, $connect);
      	if(0==mysql_result($conjunt_resultant2,0,0)) break;
      	mysql_free_result($conjunt_resultant2);
      	++$nf;
      } while (true);
      $telfSMS = substr($fila[3], 0, 9);
      $consulta3="INSERT INTO $bdtutoria.$tbl_prefix"."pares (identificador, passwd, refalumne, permisos, telfSMS) VALUES ( '$identpare' , lcase(concat(char(97+round(25*rand())),char(97+round(25*rand())),right(concat('000',round(9999*rand())),4))) , '$fila[0]', '0', '$telfSMS' )";
      mysql_query($consulta3, $connect);
    } // fi if consulta1
    mysql_free_result($conjunt_resultant1);
  }  // fi while
  mysql_free_result($conjunt_resultant);
  
  if(isset($esborrar)&&$esborrar!="") {
    $esbor=preg_split('/,/', $esborrar);
    for ($i=0; $i<count($esbor); ++$i) {
      
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."entrevistes WHERE ref_alumne='$esbor[$i]'";
      
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$esbor[$i]'";
      
    
      $consulta="SELECT ref_fitxer FROM $bdtutoria.$tbl_prefix"."fitxers WHERE ref_alum='$esbor[$i]'";
      $conjunt_resultant=mysql_query($consulta, $connect);
      while($fila=mysql_fetch_row($conjunt_resultant)) {
       
      }
      mysql_free_result($conjunt_resultant);
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."fitxers WHERE ref_alum='$esbor[$i]'";
      
    
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."informeincid WHERE ref_alum='$esbor[$i]'";
      
      
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."apercebiments WHERE refalum='$esbor[$i]'";
      
      
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."pares WHERE refalumne='$esbor[$i]'";
      mysql_query($consulta, $connect);
    
      $consulta="SELECT id, alumnes from $bdtutoria.$tbl_prefix"."subgrups where alumnes like '%$esbor[i]%'";
      $conjunt_resultant=mysql_query($consulta, $connect);
      while($fila=mysql_fetch_row($conjunt_resultant)) {
        $lalums=preg_split('/,/', $fila[1]);
        $nlalums='';
        for($j=0; $j<count($lalums); ++$j) {
         if($lalums[$j]!=$esbor[$i]) {
           if($nlalums!='') $nlalums.=',';
           $nlalums.=$lalums[$j];
         }
        }
	if($nlalums!=$fila[1]) {
          $consulta1="UPDATE $bdtutoria.$tbl_prefix"."subgrups SET alumnes='$nlalums' WHERE id='$fila[0]'";
          mysql_query($consulta1, $connect);
	}
      }
      mysql_free_result($conjunt_resultant);
      $consulta="SELECT id, ref_alum from $bdtutoria.$tbl_prefix"."informelliure where ref_alum like '%$esbor[i]%'";
      $conjunt_resultant=mysql_query($consulta, $connect);
      while($fila=mysql_fetch_row($conjunt_resultant)) {
        $lalums=preg_split('/,/', $fila[1]);
        $nlalums='';
        for($j=0; $j<count($lalums); ++$j) {
         if($lalums[$j]!=$esbor[$i]) {
           if($nlalums!='') $nlalums.=',';
           $nlalums.=$lalums[$j];
         }
        }
	if($nlalums!=$fila[1]) {
          $consulta1="UPDATE $bdtutoria.$tbl_prefix"."informelliure SET ref_alum='$nlalums' WHERE id='$fila[0]'";
          mysql_query($consulta1, $connect);
	}
      }
      mysql_free_result($conjunt_resultant);
    }
  }
  
  print("Els canvis han estat registrats.<hr></body></html>");
  exit;
}

if(isset($valida)&&$valida==0) {
  
  $consulta="DROP TABLE IF EXISTS `$bdalumnes`.`$tbl_prefix"."EstudiantsTmp`";
  mysql_query($consulta, $connect);
  print("Els canvis no han tingut efecte.<hr></body></html>");
  exit;
}

if(isset($fitxeralumnes)&&$fitxeralumnes!='') {

  $consulta="DROP TABLE IF EXISTS `$bdalumnes`.`$tbl_prefix"."EstudiantsTmp`";
  mysql_query($consulta, $connect);

  $consulta="CREATE TABLE `$bdalumnes`.`$tbl_prefix"."EstudiantsTmp` (
  numero_mat varchar(50) NOT NULL default '0',
  DNI varchar(15) NOT NULL default '',
  COGNOM_ALU varchar(30) NOT NULL default '',
  COGNOM2_AL varchar(30) default NULL,
  NOM_ALUM varchar(30) default NULL,
  SEXE varchar(4) default NULL,
  PLA_ESTUDI varchar(60) default NULL,
  CODI_ESPEC varchar(4) default NULL,
  CURS char(3) default NULL,
  GRUP char(3) default NULL,
  CODI_ITINE varchar(4) default NULL,
  ADRECA varchar(30) default NULL,
  CODI_MUNIC varchar(5) default NULL,
  NOM_MUNICI varchar(35) default NULL,
  CODI_POSTA varchar(5) default NULL,
  PRIMER_TEL text,
  COGNOM1_PA varchar(30) default NULL,
  COGNOM2_PA varchar(30) default NULL,
  NOM_PARE varchar(30) default NULL,
  COGNOM1_MA varchar(30) default NULL,
  COGNOM2_MA varchar(30) default NULL,
  NOM_MARE varchar(30) default NULL,
  CONTACTES varchar(100) default NULL,
  PRIMARY KEY  (numero_mat),
  UNIQUE KEY numero_mat (numero_mat)
)";
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
      $idref=str_replace(' ','',$values[1]);
      $idref=trim($idref,"\"");
      $idref=ltrim($idref, "0");
      $idref="\"".$idref."\"";
      $cognom_al=(($values[2]!="\"-\"")?$values[2]:"\"\"");
      $cognom2_al=(($values[3]!="\"-\"")?$values[3]:"\"\"");
      $nom_al=(($values[4]!="\"-\"")?$values[4]:"\"\"");
      $genere=(($values[5]=="\"H\"")?"\"HOME\"":(($values[5]=="\"D\"")?"\"DONA\"":"\"?\""));

      if(($values[6]=="\"-\"")||(preg_match("/\*/", $values[6]))) {  
      	continue;
      } elseif( substr($values[6], 1, 10) == 'DIVERSITAT' ) {
/*      	$plaescursgrup=preg_split("/[ ]+/",$values[6]);
      	$plaestudis="\"ESO\"";
      	$curs="\"".$plaescursgrup[1];
      	$grup="\"D\"";*/
	continue;
      }else {
      	$plaescursgrup=preg_split("/[ ]+/",$values[6]);
      	$plaestudis="\"".$plaescursgrup[1]."\"";
      	$curs=$plaescursgrup[0]."\"";
      	$grup="\"".$plaescursgrup[2];
      }
  	  
      $adreca=(($values[7]!="\"-\"")?$values[7]:"\"\"");
      $nom_municipi=(($values[8]!="\"-\"")?$values[8]:"\"\"");
      $codi_postal=(($values[9]!="\"-\"")?$values[9]:"\"\"");
      if(preg_match("/[0-9]{9}/", $values[10], $te)) { $tel=''; for($i=0; $i<count($te); ++$i) $tel.=(($tel=="")?"":", ").$te[$i];}
      $telefon=(($values[10]!="\"-\"")?$tel:"\"\"");
      $cognom1_pa=(($values[11]!="\"-\"")?$values[11]:"\"\"");
      $cognom2_pa=(($values[12]!="\"-\"")?$values[12]:"\"\"");
      $nom_pa=(($values[13]!="\"-\"")?$values[13]:"\"\"");
      $cognom1_ma=(($values[14]!="\"-\"")?$values[14]:"\"\"");
      $cognom2_ma=(($values[15]!="\"-\"")?$values[15]:"\"\"");
      $nom_ma=(($values[16]!="\"-\"")?$values[16]:"\"\"");
      $contactes_array = preg_grep( "/6[0-9]{8}/", preg_split( "/[\s,\-\(\)]+/", $values[10]) );
      $contactes_string = implode( ";", $contactes_array );
      if($numr=="#") {--$in; continue;}
      $vals=$idref.","."\"-\"".",".$cognom_al.",".$cognom2_al.",".$nom_al.",".$genere.",".$plaestudis.","."\"-\"".",".$curs.",".$grup.","."\"-\"".",".$adreca.","."\"-\"".",".$nom_municipi.",".$codi_postal.",".$telefon.",".$cognom1_pa.",".$cognom2_pa.",".$nom_pa.",".$cognom1_ma.",".$cognom2_ma.",".$nom_ma.",\"".$contactes_string."\""; 

      $consulta="INSERT INTO $bdalumnes.$tbl_prefix"."EstudiantsTmp VALUES ($vals)";
      if(true==mysql_query($consulta, $connect)) ++$inserits;
      else {
	//echo "<p>". substr($values[6], 1, 10) ." </p> \n";
	echo "<p>". $values[6] ." </p> \n";
	echo "<p> Query: $consulta </p> \n";

        print("<br><font color='#ff0000'><b>ERROR:</b></font> No s'ha inserit la fila (".($linia_num+$in)."), comprova la seva sintaxi, o que tingui el identificador-refer&egrave;ncia repetit o que el cognom de l'alumne sigui buit:<pre>$li</pre>");
        $errors=true;
      }
    }
  }
  
  unlink($fitxeralumnes);
  $consulta="UPDATE $bdalumnes.$tbl_prefix"."EstudiantsTmp SET grup='?' WHERE grup=''";
  mysql_query($consulta, $connect);
  $consulta="UPDATE $bdalumnes.$tbl_prefix"."EstudiantsTmp SET curs='?' WHERE curs=''";
  mysql_query($consulta, $connect);
  $consulta="UPDATE $bdalumnes.$tbl_prefix"."EstudiantsTmp SET pla_estudi='?' WHERE pla_estudi=''";
  mysql_query($consulta, $connect);
  if(!$errors) {
    print("<b>Pas 3:</b>");
    print("<br>S'ha trobat $inserits alumnes en el fitxer carregat.<hr>");

    
    $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $esborrar='';
    $nohison='';
    while($fila=mysql_fetch_row($conjunt_resultant)) {
     $consulta1="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."EstudiantsTmp WHERE numero_mat='$fila[0]'";
     $conjunt_resultant1=mysql_query($consulta1, $connect);
     if(0==mysql_result($conjunt_resultant1,0,0)) {
      $nohison.="<tr><td bgcolor='#ffc0ff'>$fila[1] ($fila[2] $fila[3] $fila[4])</td></tr>";
      if($esborrar!='') $esborrar.=',';
      $esborrar.=$fila[0]; 
     }
     mysql_free_result($conjunt_resultant1);
    }
    mysql_free_result($conjunt_resultant);
    if($nohison!='') {
     print("<p><b>Els seg&uuml;ents alumnes actuals, no apareixen en el nou llistat carregat.</b><br>Si valides els canvis s'esborraran totes les seves incid&egrave;ncies registrades i les seves fotos, tamb&eacute; es donaran de baixa en els subgrups.");
     print("<table border='0'>$nohison</table>");
    }

    
    $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."EstudiantsTmp";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $nous='';
    while($fila=mysql_fetch_row($conjunt_resultant)) {
     $consulta1="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$fila[0]'";
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
    print("<input type='button' value='&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Validar canvis &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;' onClick='if(confirm(\"Segur que vols validar aquests canvis?\")) document.valida.submit();'>");
    print("</form></center>"); 
    
  }
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
   #,\"identificador-referencia\",\"cognom_alumne\",\"cognom2_alumne\",\"nom_alumne\",\"genere\",\"curs etapa grup\",\"adreca\",\"nom_municipi\",\"codi_postal\",\"telefon\",\"cognom1_pare\",\"cognom2_pare\",\"nom_pare\",\"cognom1_mare\",\"cognom2_mare\",\"nom_mare\"
   1,\"DAGO0920619008\",\"Casas\",\"Gironés\",\"Jordi\",\"H\",\"1 ESO B\",\"C/ Palau dels esports 41-2 \",\"Lleida\",\"25002\",\"T 933333333 ()\",\"Casas\",\"Llobera\",\"Jaume\",\"Gironés\",\"Piñol\",\"Ester\"
   2,\"RSGO3241562113\",\"Pujol\",\"Ferrera\",\"Joan\",\"H\",\"1 ESO A\",\"C/ dels Pins 12-7 \",\"Lleida\",\"25003\",\"T 933333333 (); T 61636536374 (cangur del pare); T 325345443 (oficina mare)\",\"Pujol\",\"Rius\",\"Joan\",\"Ferrera\",\"Font\",\"Carme\"
   3, .......
  </pre>
  &nbsp; - \"identificador-referencia\" - Un identificador únic de l'alumne que no es pot repetir, es el que serveix per a difer&egrave;nciar els diferents alumnes creats i saber si s'han donat d'alta, baixa o continuen en les posteriors importacions.<br>
  &nbsp; - \"cognom_alumne\",\"cognom2_alumne\",\"nom_alumne\" - Dades de l'alumne.<br>
  &nbsp; - \"genere\" - Home o dona (H o D).<br>
  &nbsp; - \"curs etapa grup\" - Indica el curs, etapa i grup, per aquest ordre, separats amb espais en blanc, el curs pot ser 1,2,3.., la etapa pot ser ESO, BATX, CF... i el grup pot ser A, B, C... .<br>
  &nbsp; - \"adreca\" - Adre&ccedil;a de resid&egrave;ncia.<br>
  &nbsp; - \"nom_municipi\" - Municipi de resid&egrave;ncia per a la correspond&egrave;ncia.<br>
  &nbsp; - \"codi_postal\" - Codi postal del municipi.<br>
  &nbsp; - \"telefon\" - Llista de tel&egrave;fons de contacte.<br>
  &nbsp; - \"cognom1_pare\",\"cognom2_pare\",\"nom_pare\" - Dades del pare.<br>
  &nbsp; - \"cognom1_mare\",\"cognom2_mare\",\"nom_mare\" - Dades de la mare.<br> 
  </font></p>
  <p>

  <b>Pas 2:</b><br>
  A continuaci&oacute;, selecciona el fitxer *.csv, creat en el pas anterior, i carrega'l.<p>
  <input type='hidden' name='MAX_FILE_SIZE' value='300000'>
  &nbsp; &nbsp; <b>Fitxer:</b> <input type='file' name='fitxeralumnes'> <font size='-2'>(m&agrave;x.: <script language='JavaScript'>document.write(document.forms.introd1.MAX_FILE_SIZE.value +\" bytes\");</script>)</font>
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type='submit' value='Carregar'>
  </form>");
}
?>

<hr>
</body>
</html>
