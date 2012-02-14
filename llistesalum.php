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
panyacces("Privilegis");
?>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">


<?
print("
<div align='right'>
<form name='introd1' method='post' action='$PHP_SELF?idsess=$idsess' enctype='multipart/form-data'>
<table border='0'>
<tr><td><font size='6'>Llistes alumnes&nbsp; &nbsp; </font></td>");

print("<td align='right'>");
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

if($grup!=''||$subgrup!='') {
  $subgrup=stripslashes($subgrup);
  $subgrup=rawurlencode($subgrup);
  print("
     <iframe src='llistesalum_pdf.php?idsess=$idsess&grup=$grup&subgrup=$subgrup' id='informe' name='informe' height='100%' width='100%'>Aquest navegador no soporta frames!</iframe>
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
