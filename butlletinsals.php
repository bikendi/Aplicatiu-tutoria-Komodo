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
panyacces("Privilegis");
?>
<script language='JavaScript'>

function selalumn() {
  var llistaalumne = llistaalumnes.split('|');
  document.introd1.nalumne.value='';
  for (var i=0; i<llistaalumne.length;++i) {
    var llistaalum=llistaalumne[i].split('&');
    if(llistaalum[1]==document.introd1.alumne.options[document.introd1.alumne.selectedIndex].text) {
      document.introd1.nalumne.value=llistaalum[0];
      break;
    }
    if('Tots'==document.introd1.alumne.options[document.introd1.alumne.selectedIndex].text) {
	  document.introd1.nalumne.value=0;
	  break;
    }
  }
}
function selparams() {
  var aux ='&grup=';
  aux += document.introd1.grup.options[document.introd1.grup.selectedIndex].text;
  aux += '&avaluacio=';
  aux += document.introd1.avaluacio.options[document.introd1.avaluacio.selectedIndex].text;
  aux += '&nalumne=';
  aux += document.introd1.nalumne.value;
  if ((''!=document.introd1.grup.options[document.introd1.grup.selectedIndex].text)&&
      (document.introd1.avaluacio.options[document.introd1.avaluacio.selectedIndex].text!='')&&
      (document.introd1.nalumne.value!=''))  document.introd1.params.value = aux;
  else document.introd1.params.value = '';
}

</script>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?



print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>
<table border='0'>
<tr><td><font size='6'>Butlletins alumnes&nbsp; &nbsp; </font></td>
<td><b>Avaluacio:</b><br><select name='avaluacio' onChange='if(ie) document.informe.window.location.href=\"buit.php?idsess=$idsess\"; if(ns6) document.getElementById(\"informe\").src=\"buit.php?idsess=$idsess\";'>
<option></option>");
$consulta="SELECT refaval FROM $bdtutoria.$tbl_prefix"."avaluacions";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) {
	print("<option".(($avaluacio==$fila[0])?" selected":"").">$fila[0]</option>");	
}
print("</select></td>
</tr></table>");

print("<table border='0'><tr>
<td><input type='hidden' name='params' value=''><input type='submit' value='Crear Butlletins' onClick='selparams();'></td>
<td align='right'><b>Grup:</b> <select name='grup' onChange='document.introd1.nalumne.value=\"\"; document.introd1.submit();'>
<option></option>");
do {
     $permis=privilegis('-', '-',current($llista_grups));
     if($permis) print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");
   } while(next($llista_grups));
print("</select></td>");
print("<td><b>Alumne:</b> ");
print("<input type='hidden' name='nalumne' value='".((isset($nalumne)&&$nalumne!='')?$nalumne:"")."'>");
print("<select name='alumne' onChange='selalumn(); if(ie) document.informe.window.location.href=\"buit.php?idsess=$idsess\"; if(ns6) document.getElementById(\"informe\").src=\"buit.php?idsess=$idsess\";'>
<option>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </option>");
if(isset($grup)&&($grup!="")) {
  print("<option".(($nalumne=='0')?" selected":"").">Tots</option>");
  $gru=split(' ', $grup);
  $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum)  FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE (curs='$gru[0]' and grup='$gru[1]' and pla_estudi='$gru[2]')ORDER  BY cognom_alu, cognom2_al, nom_alum";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $llistaalumnes='';
  while($fila=mysql_fetch_row($conjunt_resultant)) {
    if($llistaalumnes!='') $llistaalumnes.='|';
    $llistaalumnes.= "$fila[0]&$fila[1]";
    print("<option".(($nalumne==$fila[0])?" selected":"").">$fila[1]</option>");
  }
  mysql_free_result($conjunt_resultant);
}
print("</select>");
print("<script language='JavaScript'>var llistaalumnes='$llistaalumnes';</script>");
print("</td></tr>
</table></form>
</div>
<hr>
");
$pagina="buit.php?idsess=$idsess";
if ($params!='') $pagina="butlletinsals_pdf.php?idsess=$idsess".$params;
?>

<script language='JavaScript'>
document.write("<iframe src='<?print($pagina)?>' id='informe' name='informe' height='100%' width='100%'>Aquest navegador no soporta frames!</iframe>");

function redimensiona() {
  if (ie) {
    var ampleBody = document.body.clientWidth;
    var altBody = document.body.clientHeight;
  }
  if (ns4 || ns6) {
    var ampleBody = window.innerWidth;
    var altBody = window.innerHeight;
  }
  if (ie) document.all.informe.style.height=altBody-document.all.informe.offsetTop-17; //17 es l'alt de la capa menu.
  if(ns6) document.getElementById('informe').style.height=altBody-document.getElementById('informe').offsetTop-38;
}
//if (ns6 || ns4) document.captureEvents(Event.RESIZE);
redimensiona();
window.onresize=redimensiona;
</script>

<hr>

</body>
</html>


















