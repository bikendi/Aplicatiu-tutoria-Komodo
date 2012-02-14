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

<?
if(isset($fitxer)&&$fitxer!=''&&$idAlum!='') {
  if ($fitxer_size<20000 && ($fitxer_type=="image/pjpeg" || $fitxer_type=="image/jpeg")) {
    copy($fitxer, "$dirfotos/$idAlum.jpg");
    print("<script language='JavaScript'>");
    print("opener.document.introd1.submit();");
    print("window.close();");
    print("</script>");
  }
  else {
    print("<script language='JavaScript'>");
    print("opener.document.introd1.submit();");
    print("alert('No es pot canviar. Foto massa gran (>10K) o format incorrecte (no es jpg).');");
    print("window.close();");
    print("</script>");
  }
  unlink($fitxer);
  exit;
}
else if(isset($MAX_FILE_SIZE)) {
        print("<script language='JavaScript'>");
        print("opener.document.introd1.submit();");
        print("alert('No es pot canviar. Foto massa gran (>$MAX_FILE_SIZE bytes).');");
        print("window.close();");
        print("</script>");
	exit;
     }

if($esborrar!='') {
  unlink("$dirfotos/$esborrar.jpg");
}
?>

<script language='JavaScript'>
function carregaFoto(pIdAlu, pNom)
{
 var finestra;
 window.focus();
 opt = "resizable=0,scrollbars=0,width=300,height=165,left=5,top=60";
 finestra=window.open("", "finestra", opt);
 with (finestra.document) {
  write("<html><head><title>Tutoria</title></head>");
  write("<body bgcolor='#c0c0c0'>");
  write("<form action='<?print("$PHP_SELF?idsess=$idsess");?>' method='post' enctype='multipart/form-data'>");
  write("<b>Alumne:</b> "+pNom+"<br><br>");
  write("<input type='hidden' name='MAX_FILE_SIZE' value='15000'>");
  write("<font size=-2>La foto ha d'esser format .jpg, de 93x125px aprox. i tamany m&agrave;xim de 10Kby</font><br>");
  write("<input type='hidden' name='idAlum' value='"+pIdAlu+"'>");
  write("Fitxer de la foto: <input type='file' name='fitxer'>");
  write("<center><input type='submit' value='Canviar'></center>");
  write("</form>");
  write("</body></html>");
  close();
 }
 finestra.focus();
}
</script>

</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">


<?
print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess' enctype='multipart/form-data'>
<table border='0'>
<tr><td><font size='6'>Posar fotos&nbsp; &nbsp; </font></td>
<td align='right'>
<b>Grup:</b> <select name='grup' onChange='document.introd1.submit();'>
<option></option>");
do {print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");} while(next($llista_grups));
print("</select></td></tr></table>");
print("<input type='hidden' name='esborrar' value=''>");
print("</form></div><hr>");


if($grup!='') {

  $gru=split(' ', $grup);

  $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' ORDER BY cognom_alu, cognom2_al ASC";
  $conjunt_resultant=mysql_query($consulta, $connect);

  print("<table border='0' width='100%'>");
  $maxcols=7;
  $contcols=0;

  while ($fila=mysql_fetch_row($conjunt_resultant)) {
    if($contcols==0) print("<tr>");
    print("<td valign='top'>");
    if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
    else $foto = "./imatges/fot0.jpg";
    print("<font size='2'><img src='$foto' width='93' height='125'><br>");
    print("<a href='' title='Canvia la foto' onClick='carregaFoto(\"$fila[0]\", \"$fila[1]\"); return false;'>Canviar</a>&nbsp; ");
    if ($foto!="./imatges/fot0.jpg") print("<a href='' title='Esborra la foto' onClick='document.introd1.esborrar.value=\"$fila[0]\"; document.introd1.submit(); return false;'>Esborrar</a>");
    print("<br>");
    print("$fila[1]</font>");
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

?>

</body>
</html>
