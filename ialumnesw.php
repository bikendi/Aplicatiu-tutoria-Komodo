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
<?
print("
<table border='0' width='100%'><tr><td align='center'>");
if(!((isset($fitxeralumnes)&&$fitxeralumnes!='')||isset($valida))) print("<a href='ialumnes.php?idsess=$idsess' title='Des d´un fitxer .csv'>Importaci&oacute;<br>des d'un fitxer .csv</a>");
else print("&nbsp;");
print("</td>
<td align='right'><font size='6'>Importar Alumnes des de Winsec&nbsp; &nbsp; </font>");
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

if(isset($valida)&&$valida==1) {
  $consulta="DROP TABLE IF EXISTS `$bdalumnes`.`$tbl_prefix"."Estudiants`";
  mysql_query($consulta, $connect);
  $consulta="ALTER TABLE `$bdalumnes`.`$tbl_prefix"."EstudiantsTmp` RENAME `$bdalumnes`.`$tbl_prefix"."Estudiants`";
  mysql_query($consulta, $connect);
  
  $trans=Array('á'=>'a','à'=>'a','ä'=>'a','â'=>'a', 'é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
                   'í'=>'i','ì'=>'i','ï'=>'i','î'=>'i', 'ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o',
                   'ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u', 'ç'=>'c','ñ'=>'ny','l·l'=>'l',' '=>'' );
  $consulta="SELECT numero_mat, nom_alum, cognom_alu FROM $bdalumnes.$tbl_prefix"."Estudiants";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
    $consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."pares WHERE refalumne='$fila[0]'";
    $conjunt_resultant1=mysql_query($consulta1, $connect);
    if(0==mysql_result($conjunt_resultant1,0,0)) {
      $fila[1]=strtolower($fila[1]);
      $fila[2]=strtolower($fila[2]);
      $fila[1]=strtr($fila[1],$trans);
      $fila[2]=strtr($fila[2],$trans);
      $nf=2;
      do {
      	$identpare=substr($fila[1],0,1).$fila[2].$nf;
      	$consulta2="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."pares WHERE identificador='$identpare'";
      	$conjunt_resultant2=mysql_query($consulta2, $connect);
      	if(0==mysql_result($conjunt_resultant2,0,0)) break;
      	mysql_free_result($conjunt_resultant2);
      	++$nf;
  	  } while (true);
      $consulta3="INSERT INTO $bdtutoria.$tbl_prefix"."pares (identificador, passwd, refalumne, permisos) VALUES ( '$identpare' , lcase(concat(char(97+round(25*rand())),char(97+round(25*rand())),right(concat('000',round(9999*rand())),4))) , '$fila[0]', '0' )";
      mysql_query($consulta3, $connect);
    }
    mysql_free_result($conjunt_resultant1);
  }
  mysql_free_result($conjunt_resultant);
  
  if(isset($esborrar)&&$esborrar!="") {
    $esbor=preg_split('/,/', $esborrar);
    for ($i=0; $i<count($esbor); ++$i) {
      if(file_exists("$dirfotos/$esbor[$i].jpg")) unlink("$dirfotos/$esbor[$i].jpg");
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."entrevistes WHERE ref_alumne='$esbor[$i]'";
      mysql_query($consulta, $connect);
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$esbor[$i]'";
      mysql_query($consulta, $connect);
    
      $consulta="SELECT ref_fitxer FROM $bdtutoria.$tbl_prefix"."fitxers WHERE ref_alum='$esbor[$i]'";
      $conjunt_resultant=mysql_query($consulta, $connect);
      while($fila=mysql_fetch_row($conjunt_resultant)) {
       if(file_exists("$dirfitxers/$fila[0]")) unlink("$dirfitxers/$fila[0]");
      }
      mysql_free_result($conjunt_resultant);
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."fitxers WHERE ref_alum='$esbor[$i]'";
      mysql_query($consulta, $connect);
    
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."informeincid WHERE ref_alum='$esbor[$i]'";
      mysql_query($consulta, $connect);
      
      $consulta="DELETE from $bdtutoria.$tbl_prefix"."apercebiments WHERE refalum='$esbor[$i]'";
      mysql_query($consulta, $connect);
      
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
  numero_mat varchar(20) NOT NULL default '0',
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
  PRIMARY KEY  (numero_mat),
  UNIQUE KEY numero_mat (numero_mat)
)";
  mysql_query($consulta, $connect);

  $inserits=0;
  $linies=file($fitxeralumnes);
  $errors=false;
  for($i=0; $i<count($linies); ++$i) {
    if($linies[$i]!="") {  
      $linies[$i]=str_replace('";"','","',$linies[$i]);
      $consulta="INSERT INTO $bdalumnes.$tbl_prefix"."EstudiantsTmp VALUES ($linies[$i])";
      if(true==mysql_query($consulta, $connect)) ++$inserits;
      else {
        print("<br><font color='#ff0000'><b>ERROR:</b></font> No s'ha inserit la fila (".($i+1)."), comprova la seva sintaxi, o que tingui el dni repetit o que el cognom de l'alumne sigui buit:<pre>$linies[$i]</pre>");
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
  Fer la seg&uuml;ent consulta al Winsec, a Complements/Consultes definibles (no oblidis esborrar els espais al final de la consulta, sin&oacute;, dona error), modifica l'any, si s'escau:
  <p><font size='-1' color='#0000ff'>
   SELECT alumnes.matricula AS Numero_Mat, alumnes.dni AS DNI, alumnes.cognom1 AS Cognom_alu, alumnes.cognom2 AS Cognom2_al, alumnes.nom AS Nom_alum, IIF(alumnes.sexe=1,'HOME','DONA') AS Sexe, alumacad.etapa AS Pla_estudi, alumacad.subetapa AS Codi_espec, alumacad.nivell AS Curs, alumacad.grupclasse AS Grup, alumacad.codidistri AS Codi_itine, alumcurs.adreca AS Adreca, alumcurs.municipi AS Codi_munic, tmunicip.nommuni AS Nom_Munici, alumcurs.codipostal AS Codi_posta, alumcurs.telefon AS Primer_tel, tutors1.cognom1 AS Cognom1_pa, tutors1.cognom2 AS Cognom2_pa, tutors1.nom AS Nom_pare, tutors2.cognom1 AS Cognom1_ma, tutors2.cognom2 AS Cognom2_ma, tutors2.nom AS Nom_mare FROM alumacad INNER JOIN alumcurs ON alumacad.matricula = alumcurs.matricula INNER JOIN alumnes ON alumcurs.matricula = alumnes.matricula INNER JOIN alumexp ON alumcurs.matricula = alumexp.matricula INNER JOIN tmunicip ON alumcurs.municipi = tmunicip.codiine LEFT OUTER JOIN tutors AS tutors1 ON alumnes.idtutor1 = tutors1.idtutor LEFT OUTER JOIN tutors AS tutors2 ON alumnes.idtutor2 = tutors2.idtutor WHERE alumacad.curs_acad ='2003' AND alumcurs.curs_acad ='2003' AND (alumacad.etapa ='ESO' OR alumacad.etapa ='BATX') AND EMPTY(alumacad.databaixa) GROUP BY alumnes.matricula ORDER BY alumnes.cognom1, alumnes.cognom2, alumnes.nom
  </font></p>
  de les dades obtingudes, exportar-les a Tipus: Text(txt), Delimitador (\"), Separador de camps: (,), Destinaci&oacute;: 'alumnes.txt'
  <p>

  <b>Pas 2:</b><br>
  A continuaci&oacute;, selecciona el fitxer 'alumnes.txt', exportat en el pas anterior, i carrega'l.<p>
  <input type='hidden' name='MAX_FILE_SIZE' value='300000'>
  &nbsp; &nbsp; <b>Fitxer:</b> <input type='file' name='fitxeralumnes'> <font size='-2'>(m&agrave;x.: <script language='JavaScript'>document.write(document.forms.introd1.MAX_FILE_SIZE.value +\" bytes\");</script>)</font>
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type='submit' value='Carregar'>
  </form>");
}
?>

<hr>
</body>
</html>
