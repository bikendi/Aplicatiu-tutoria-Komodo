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
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">


<?

print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess' enctype='multipart/form-data'>
<table border='0'>
<tr><td><font size='6'>Fotos alumnes&nbsp; &nbsp; </font></td>");
if(!isset($webprint)) $webprint="web";
print("<td><fieldset style='border-width:1; border-style:ridge; border-color:#42A5A5'><legend>Format de visualitzaci&oacute;: </legend><input type='radio' name='webprint' value='web'".(($webprint=="web")?" checked ":" ")."onClick='document.introd1.submit();'>Web. <input type='radio' name='webprint' value='impr'".(($webprint=="impr")?" checked ":" ")."onClick='document.introd1.submit();'>Impressora.</fieldset></td>
<td align='right'>");
if ( (isset($grup)&&$grup=='Subgrups') || (isset($subgrup)&&$subgrup!='Grups')) {
 print("<b>Subgrups:</b> <select name='subgrup' onChange='document.introd1.submit();'>
 <option></option><option>Grups</option>");
 do {print("<option".((stripslashes($subgrup)==rawurldecode(current($llista_subgrups)))?" selected":"").">".rawurldecode(current($llista_subgrups))."</option>");} while(next($llista_subgrups));
 if($grup=='Subgrups') $grup='';
}
else {
 print("<b>Grups:</b> <select name='grup' onChange='document.introd1.submit();'>
 <option></option><option>Subgrups</option>");
 do {print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");} while(next($llista_grups));
 if($subgrup=='Grups') $subgrup='';
}
print("</select></td></tr></table>");
print("<input type='hidden' name='esborrar' value=''>");
print("</form></div><hr>");


if(($grup!=''||$subgrup!='') && $webprint=='web') {

  if($grup!='') {
  $gru=preg_split('/ /', $grup);
  $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' ORDER BY cognom_alu, cognom2_al ASC";
  }
  else {
   $subgru=preg_split('/ /',$subgrup);
   $consulta="SELECT alumnes FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='$subgru[0]' limit 1";
   $conjunt_resultant=mysql_query($consulta, $connect);
   $alssubgrup=preg_split('/,/',mysql_result($conjunt_resultant, 0,0));
   mysql_free_result($conjunt_resultant);
   $consulta ="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi ";
   $consulta.="FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE ";
   $cons='';
   foreach($alssubgrup as $nal) {
     if ($cons!='') $cons.='or ';
     $cons.="numero_mat='$nal' ";
   }
   $consulta.= $cons;
   $consulta.="ORDER BY cognom_alu, cognom2_al ASC";
  }
  $conjunt_resultant=mysql_query($consulta, $connect);
  if(0==mysql_num_rows($conjunt_resultant)) print("Aquest subgrup no t&eacute; alumnes.");
  
  print("<table border='0' width='100%'>");
  $maxcols=7;
  $contcols=0;

  while ($fila=mysql_fetch_row($conjunt_resultant)) {
    if($contcols==0) print("<tr>");
    print("<td valign='top'>");
    if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
    else $foto = "./imatges/fot0.jpg";
    print("<font size='2'><img src='$foto' width='93' height='125'><br>");
    print("$fila[1]</font>");
    if($subgrup!='') print("<br><font size='1'>($fila[2] $fila[3] $fila[4])</font>"); 
    print("</td>");
    ++$contcols;
    if ($contcols==$maxcols) {
      print("</tr>");
      $contcols=0;
    }

  }
  if ($contcols<7&&$contcols!=0) {
    for ($i=$contcols; $i<$maxcols; ++$i) print("<td>&nbsp;</td>");
    print("</tr>");
  }
  print("</table><hr>");

  mysql_free_result($conjunt_resultant);

}

if(($grup!=''||$subgrup!='') && $webprint=='impr') {
  $subgrup=stripslashes($subgrup);
  $subgrup=rawurlencode($subgrup);
  print("
     <iframe src='fotosalum_pdf.php?grup=$grup&subgrup=$subgrup&idsess=$idsess' id='informe' name='informe' height='100%' width='100%'>Aquest navegador no soporta frames!</iframe>
     <script language='JavaScript'>
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

        redimensiona();
        window.onresize=redimensiona;
     </script>
     <hr>
   ");
}

?>

</body>
</html>
