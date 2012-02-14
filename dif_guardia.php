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
<?php
@include("linkbd.inc.php");
@include("comu.php");
@include("comu.js.php");
require_once('mail.php');
panyacces("Privilegis");

echo "\n";
echo '<link rel="stylesheet" type="text/css" href="css/comu.css">'. "\n";
echo '<link rel="stylesheet" type="text/css" href="css/guardia.css">'. "\n";

///////////////////
$dia=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);
// echo "<p> dia: $dia / datatimestamp: $datatimestamp </p>\n";
// echo "<p> id_esborrar: $id_esborrar </p>\n";

  if(isset($datan) && $datan!='') {
   if($datan=="Avui") {
     $datatimestamp=mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1);
   }
   else {
     $dat=split(' ', $datan);
     $da=split('-', $dat[1]);
     $datatimestamp=mktime(date('H'),date('i'),date('s'),$da[1],$da[0],$da[2],-1);
   }
  }
$dia=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);

?>

<script language='JavaScript'>
function calendariEscriuDia(di, i, mes, any) {
 var cad;
 if(di=='Avui') {
   var avui= new Date(<?print(1000*mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1));?>);
   di="<?print($nomDiaSem[date('w')]);?>";
   if(di!='Ds' && di!='Dg') cad="<a href='' onClick='document.introd1.datan.value=\""+di+", "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; document.introd1.submit(); return false;'>Avui</a>";
   else cad='Avui';
   return cad;
 }
 if(di=='ICurs') {
   di="<?=$nomDiaSem[date('w',$datatimestampIniciCurs)]?>";
   if(di!='Ds' && di!='Dg') cad="<a href='' onClick='document.introd1.datan.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; document.introd1.submit(); return false;'>Inici Curs</a>";
   else cad='Inici Curs';
   return cad;
 }
 if(di!='Ds' && di!='Dg') cad="<a href='' onClick='document.introd1.datan.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; document.introd1.submit(); return false;'>" + i + "</a>";
 else cad=i;
 return cad;
} // calendariEscriuDia

function selalumn() {
  
  var llistaalumne = llistaalumnes.split('|');
  document.introd1.nalumne.value='';
  for (var i=0; i<llistaalumne.length; ++i) {
    var llistaalum=llistaalumne[i].split('&');
//     if(llistaalum[1]==document.introd1.alumne.options[document.introd1.alumne.selectedIndex].text) {
    if(llistaalum[0]==document.introd1.alumne.options[document.introd1.alumne.selectedIndex].value) {
      document.introd1.nalumne.value=llistaalum[0];
      break;
    }
  }
} // selalumn

</script>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">

<?
// Títol i data

/* TODO: Des de fins a
print("
<div align='right'>
<font size='6'>Resum butlleta de guàrdia i diferències </font>");
echo "<label for='data'><b>Data:</b></label>";
echo "<input type='text' name='data' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."' onClick='blur(); obreCalendari(0,0,0);'>\n"; 
echo "<input type='hidden' name='datan' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."' onChange='document.introd1.submit();'> \n";
print("
</div><hr>
");
*/

// echo "<p> dia: $dia </p>\n";
// echo "<p> datatimestamp: $datatimestamp </p>\n";
// echo "<p> data: $data </p>\n";
// echo "<p> datan: $datan </p>\n";


echo "<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess' enctype='multipart/form-data'>\n";

echo "<input type='submit' value='Enviar e-mail' name='enviar_meil' >\n";

// Llista expulsions
echo "<div id='expulsions-taula' class='taula'>\n";
// TODO: Filtrar per profe
// TODO: Paginació
$query = "SELECT CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup, G.refalumne, FROM_UNIXTIME(G.data,'%d-%m-%Y') AS data, G.hora, G.profe, G.memo, G.usuari FROM $bdtutoria.$tbl_prefix"."guardia G LEFT JOIN $bdtutoria.$tbl_prefix"."faltes F USING (refalumne, data, hora), $bdtutoria.$tbl_prefix"."Estudiants E WHERE G.incidencia='E' AND E.numero_mat=G.refalumne AND F.refalumne IS NULL ORDER BY G.profe, G.data, G.hora";
$res = mysql_query($query, $connect) OR die("Error:". mysql_error());
$n = mysql_num_rows($res);
// echo "<p> Consulta: $query </p>\n";
echo "<h3> Expulsions no registrades pel profe que expulsa ($n)</h3>\n";
$exp_nr_table_head = "<table>\n";
$exp_nr_table_head .= "  <tr>\n";
$exp_nr_table_head .= "    <th>Data</th>\n";
$exp_nr_table_head .= "    <th>Hora</th>\n";
$exp_nr_table_head .= "    <th>Profe</th>\n";
$exp_nr_table_head .= "    <th>Grup</th>\n";
$exp_nr_table_head .= "    <th>Alumne</th>\n";
$exp_nr_table_head .= "    <th>Motiu</th>\n";
$exp_nr_table_head .= "    <th>Usuari</th>\n";
$exp_nr_table_head .= "  </tr>\n";
echo $exp_nr_table_head;
while( $fila = mysql_fetch_object($res) ) {
  $aux = "  <tr>\n";
  $aux .= "    <td>$fila->data</td>\n";
  $aux .= "    <td>$fila->hora</td>\n";
  $aux .= "    <td>$fila->profe</td>\n";
  $aux .= "    <td>$fila->grup</td>\n";
  $aux .= "    <td>$fila->alumne</td>\n";
  $aux .= "    <td>$fila->memo</td>\n";
  $aux .= "    <td>$fila->usuari</td>\n";
  $aux .= "  </tr>\n";
  echo $aux;
  $expulsions[$fila->profe] .= $aux;
}
echo "</table>\n";
echo "</div>\n"; //expulsions-taula

if( isset($_POST['enviar_meil']) ) {
  $message_intro = "<p>Hola, </p>\n<p>en la següent taula apareixen expulsions teves registrades a la butlleta de guàrdia, que en canvi no figuren en el registre d'incidències. </p>\n ";
  $message_foot = "<p>Podries revisar-les i si s'escau introduir-les? A la columna de la dreta apareix l'usuari que les ha registrades a la butlleta de guàrdia, per si necessites algun aclariment.</p>\n<p> Gràcies.</p>\n<hr><p>Missatge generat automàticament per l'Aplicatiu Tutoria.</p>\n";
  foreach( $expulsions as $profe => $taula ) {
    $message = "<html><head></head><body>\n". $message_intro . $exp_nr_table_head . $taula . "</table>\n". $message_foot . " </body></html>\n";
    echo "<p> $profe </p> \n";
//   	echo "m: ". $message ."\n";
//   	echo "t: ". $taula ."\n";
    $to = meil_usuari( $profe );
    enviar_mail_phpmailer( '', $to[0], '[Tutoria] expulsions', $message, '', TRUE );
    enviar_mail_phpmailer( '', 'bingen@iesmediterrania.cat', '[Tutoria] expulsions - ' . $profe, $message, '', TRUE );
  } // fi foreach
} // fi if enviar_meil

echo "<div id='expulsions2-taula' class='taula'>\n";
// TODO: Filtrar per profe
$query = "SELECT CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup, F.refalumne, FROM_UNIXTIME(F.data,'%d-%m-%Y') AS data, F.hora, F.usuari, F.memo FROM $bdtutoria.$tbl_prefix"."faltes F LEFT JOIN $bdtutoria.$tbl_prefix"."guardia G USING (refalumne, data, hora), $bdtutoria.$tbl_prefix"."Estudiants E WHERE F.incidencia='E' AND E.numero_mat=F.refalumne AND G.refalumne IS NULL ORDER BY F.data, F.hora, F.usuari";
$res = mysql_query($query, $connect) OR die("Error:". mysql_error());
$n = mysql_num_rows($res);
// echo "<p> Consulta: $query </p>\n";
echo "<h3> Expulsions no registrades a la butlleta de guàrdia ($n)</h3>\n";
echo "<table>\n";
echo "  <tr>\n";
echo "    <th>Data</th>\n";
echo "    <th>Hora</th>\n";
echo "    <th>Profe</th>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
echo "    <th>Motiu</th>\n";
echo "  </tr>\n";
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td>$fila->data</td>\n";
  echo "    <td>$fila->hora</td>\n";
  echo "    <td>$fila->usuari</td>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
  echo "    <td>". urldecode($fila->memo) ."</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //expulsions2-taula

// Llista retards
echo "<div id='retards-taula' class='taula'>\n";
// TODO: Filtrar per profe
$query = "SELECT CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup, G.refalumne, FROM_UNIXTIME(G.data,'%d-%m-%Y') AS data, G.hora, G.profe, G.memo, G.usuari, F.incidencia, F.usuari AS profe2 FROM $bdtutoria.$tbl_prefix"."guardia G LEFT JOIN $bdtutoria.$tbl_prefix"."faltes F USING (refalumne, data, hora), $bdtutoria.$tbl_prefix"."Estudiants E WHERE G.incidencia='R' AND E.numero_mat=G.refalumne AND F.incidencia != 'R' ORDER BY G.profe, G.data, G.hora";
$res = mysql_query($query, $connect) OR die("Error:". mysql_error());
$n = mysql_num_rows($res);
// echo "<p> Consulta: $query </p>\n";
echo "<h3> Retards - discrepàncies ($n) </h3>\n";
echo "<table>\n";
echo "  <tr>\n";
echo "    <th>Data</th>\n";
echo "    <th>Hora</th>\n";
// echo "    <th>Profe</th>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
echo "    <th>Observacions</th>\n";
echo "    <th>Usuari</th>\n";
echo "    <th>Indcid. orig.</th>\n";
echo "    <th>Usuari orig.</th>\n";
echo "  </tr>\n";
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td>$fila->data</td>\n";
  echo "    <td>$fila->hora</td>\n";
//   echo "    <td>$fila->profe</td>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
  echo "    <td>$fila->memo</td>\n";
  echo "    <td>$fila->usuari</td>\n";
  echo "    <td>$fila->incidencia</td>\n";
  echo "    <td>$fila->profe2</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //retards-taula

echo "<div id='retards-taula' class='taula'>\n";
// TODO: Filtrar per profe
$query = "SELECT CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup, G.refalumne, FROM_UNIXTIME(G.data,'%d-%m-%Y') AS data, G.hora, G.profe, G.memo, G.usuari, F.incidencia, F.usuari AS profe2 FROM $bdtutoria.$tbl_prefix"."guardia G LEFT JOIN $bdtutoria.$tbl_prefix"."faltes F USING (refalumne, data, hora), $bdtutoria.$tbl_prefix"."Estudiants E WHERE G.incidencia='R' AND E.numero_mat=G.refalumne AND F.refalumne IS NULL ORDER BY G.profe, G.data, G.hora";
$res = mysql_query($query, $connect) OR die("Error:". mysql_error());
$n = mysql_num_rows($res);
// echo "<p> Consulta: $query </p>\n";
echo "<h3> Retards que falten ($n) </h3>\n";
echo "<table>\n";
echo "  <tr>\n";
echo "    <th>Data</th>\n";
echo "    <th>Hora</th>\n";
// echo "    <th>Profe</th>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
echo "    <th>Observacions</th>\n";
echo "    <th>Usuari</th>\n";
echo "    <th>Indcid. orig.</th>\n";
echo "    <th>Usuari orig.</th>\n";
echo "  </tr>\n";
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td>$fila->data</td>\n";
  echo "    <td>$fila->hora</td>\n";
//   echo "    <td>$fila->profe</td>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
  echo "    <td>$fila->memo</td>\n";
  echo "    <td>$fila->usuari</td>\n";
  echo "    <td>$fila->incidencia</td>\n";
  echo "    <td>$fila->profe2</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //retards-taula

echo "<div style='clear:both;'></div>\n";
// echo "<hr>\n";

echo "</form>\n";

?>
</body>
</html>
