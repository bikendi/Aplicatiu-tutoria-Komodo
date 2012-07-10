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
panyacces("Privilegis");

echo "\n";
echo '<link rel="stylesheet" type="text/css" href="css/comu.css">'. "\n";
echo '<link rel="stylesheet" type="text/css" href="css/guardia.css">'. "\n";

///////////////////
$dia=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);
// echo "<p> dia: $dia / datatimestamp: $datatimestamp </p>\n";
// echo "<p> id_esborrar: $id_esborrar </p>\n";
if( !empty($id_esborrar) ) {
  $query = "DELETE FROM $bdtutoria.$tbl_prefix"."guardia WHERE id=$id_esborrar";
  if( !$res=mysql_query($query) ) {
      echo "<p> Error esborrant de la BDD. ". mysql_error() ."</p>\n";
      echo "<p> Query: $query </p>\n";
  }
} // !empty id_esborrar
if( !empty($incidencia) ) {
      $errors = 0;
      if( empty($hora) ) {
	  echo "<p> Has d'informar l'horari de l'expulsió</p>\n";
	  $errors++;
      }
      if( empty($grup) || empty($nalumne) ) {
	  echo "<p> Has d'informar el grup i l'alumne</p>\n";
	  echo "<p> Grup: $grup Alumne: $nalumne </p>\n";
	  $errors++;
      }
      switch( $incidencia ) {
	case 'E':
	  if( empty($profe) ) {
	      echo "<p> Has d'informar el professor que expulsa</p>\n";
	      //echo "<p> professor: $profe</p>\n";
	      $errors++;
	      break;
	  }
	  $text = $e_motiu;
	  break;
	case 'R':
	  $text = $r_observacions;
	  break;
	case 'AC':
	  $text = $ac_motiu;
	  break;
	case 'AL':
	  $text = $al_motiu;
	  break;
	default:
	  break;
      } // switch
      $text = addslashes($text);
      if( $errors == 0 ) {
// 	  $dia=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);
	  $query = "INSERT INTO $bdtutoria.$tbl_prefix"."guardia (incidencia, refalumne, data, hora, profe, memo, usuari) VALUES ('$incidencia', $nalumne, $dia, '$hora', '$profe', '$text', '$sess_user')";
	  if( !$res = mysql_query($query) ){
	      echo "<p> Error insertant a la BDD. ". mysql_error() ." </p>\n";
	      echo "<p> Query: $query </p>\n";
	  }
// 	  echo "<p> Query: $query </p>\n";
      } // no errors

} // if !empty incidencia

  if(isset($datan) && $datan!='') {
   if($datan=="Avui") {
     $datatimestamp=mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1);
   }
   else {
     $dat=preg_split('/ /', $datan);
     $da=preg_split('/-/', $dat[1]);
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

echo "<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>\n";

print("
<div align='right'>
<font size='6'>Butlleta de guàrdia&nbsp; &nbsp; </font>");
echo "<label for='data'><b>Data:</b></label>";
echo "<input type='text' name='data' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."' onClick='blur(); obreCalendari(0,0,0);'>\n"; 
echo "<input type='hidden' name='datan' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."' onChange='document.introd1.submit();'> \n";
print("
</div><hr>
");

// echo "<p> dia: $dia </p>\n";
// echo "<p> datatimestamp: $datatimestamp </p>\n";
// echo "<p> data: $data </p>\n";
// echo "<p> datan: $datan </p>\n";

echo "<a href='https://docs.google.com/a/iesmediterrania.cat/leaf?id=0B45Kqe2NxZ46ZDk5NGEwMDMtY2M5Ni00NTlkLTgyNmUtMThkNzNmMzdjNDAw&hl=ca' title='Prefectura d\'estudis' target='_blank'> Carpeta compartida de prefectura d'estudis </a>\n";
echo "<hr>\n";

// Hora - Grup - Alumne (comú a E, R i Abandonament)

// Hora
echo "<label for='hora'><b>Hora:</b></label>\n";
echo "<select name='hora'>\n";
echo "<option></option>";
$consulta1="SELECT hora FROM $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
$conjunt_resultant1=mysql_query($consulta1, $connect);
while($fila1=mysql_fetch_row($conjunt_resultant1)) {
  print("<option".(($hora==$fila1[0])?" selected":"").">$fila1[0]</option>");
}
mysql_free_result($conjunt_resultant1);
echo "</select>\n";
// Grup
  print( "<b>Grup:</b> <select name='grup' onChange='document.introd1.nalumne.value=\"\"; document.introd1.submit();'>
       <option></option>");
do {
   print("<option".(($grup==current($llista_grups))? " selected": ""). ">". current($llista_grups). "</option> \n");
} while(next($llista_grups));
print("</select>");
// Alumne
print("&nbsp; &nbsp; &nbsp; &nbsp; <b>Alumne:</b> ");
print("<input type='hidden' name='nalumne' value='".((isset($nalumne)&&$nalumne!='')?$nalumne:"")."'>");
print("<select name='alumne' onChange='selalumn(); document.forms.introd1.nouinforme.value=\"-1\"; document.introd1.submit();'>
       <option>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </option>");
if(isset($grup)&&($grup!="")) {
         $gru=preg_split('/ /', $grup);
         $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum)  FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE (curs='$gru[0]' and grup='$gru[1]' and pla_estudi='$gru[2]')ORDER  BY cognom_alu, cognom2_al, nom_alum";
         $conjunt_resultant=mysql_query($consulta, $connect);
         $llistaalumnes='';
         while($fila=mysql_fetch_row($conjunt_resultant)) {
          if($llistaalumnes!='') $llistaalumnes.='|';
          $llistaalumnes.= "$fila[0]&$fila[1]";
          print("<option value='$fila[0]'".(($nalumne==$fila[0])?" selected":"").">$fila[1]</option> \n");
         }
         mysql_free_result($conjunt_resultant);
}
print("</select>");
//           echo "<p> Consulta: $consulta </p>\n";
//           echo "<p> llistaalumnes: $llistaalumnes </p>\n";
print("<script language='JavaScript'>var llistaalumnes='$llistaalumnes';</script>");	echo " \n";

// Expulsions
echo "<div id='expulsions' class='intro'>\n";
echo "<h3> Expulsions </h3>\n";
echo "<input type='hidden' id='incidencia' name='incidencia' value='' >\n";
// Profe
echo "<label for='profe'><b>Professor:</b></label>\n";
echo "<select name='profe' id='profe'>\n";
echo "<option></option>";
// $consulta1="SELECT nomreal, usuari FROM $bdtutoria.$tbl_prefix"."usu_profes WHERE usuari IN (SELECT idprof FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE diasem='X' AND hora='X' AND grup='Profes') order by usuari asc";
$consulta1="SELECT nomreal, usuari FROM $bdtutoria.$tbl_prefix"."usu_profes WHERE usuari IN (SELECT idprof FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE grup='Profes') order by usuari asc";
$conjunt_resultant1=mysql_query($consulta1, $connect);
while($fila1=mysql_fetch_row($conjunt_resultant1)) {
  print("<option".(($profe==$fila1[1])?" selected":"")." value='$fila1[1]'>$fila1[1] - $fila1[0] </option>\n");
}
mysql_free_result($conjunt_resultant1);
echo "</select>\n";
// echo "<p> Consulta: $consulta1 </p>\n";
// Motiu
echo "<label for='e_motiu'><b>Motiu</b></label>\n";
echo "<input type='text' name='e_motiu' id='e_motiu'>\n";
echo "<input type='button' value='Gravar' onClick=' document.getElementById(\"incidencia\").value=\"E\"; document.introd1.submit();'> \n";
echo "</div>\n"; // expulsions

// Retards
echo "<div id='retards' class='intro'>\n";
echo "<h3> Retards </h3>\n";
// Motiu
echo "<label for='r_observacions'><b>Observacions</b></label>\n";
echo "<input type='text' name='r_observacions' id='r_observacions'>\n";
echo "<input type='button' value='Gravar' onClick=' document.getElementById(\"incidencia\").value=\"R\"; document.introd1.submit();'> \n";
echo "</div>\n"; // retards

// Abandonaments del centre
echo "<div id='abandonaments' class='intro'>\n";
echo "<h3> Abandonamets del centre </h3>\n";
// Motiu
echo "<label for='ac_motiu'><b>Motiu</b></label>\n";
echo "<input type='text' name='ac_motiu' id='ac_motiu'>\n";
echo "<input type='button' value='Gravar' onClick=' document.getElementById(\"incidencia\").value=\"AC\"; document.introd1.submit();'> \n";
echo "</div>\n"; //abandonaments

// Altres
echo "<div id='altres' class='intro'>\n";
echo "<h3> Altres incidències </h3>\n";
// Motiu
echo "<label for='al_motiu'><b>Incidència</b></label>\n";
echo "<input type='text' name='al_motiu' id='al_motiu'>\n";
echo "<input type='button' value='Gravar' onClick=' document.getElementById(\"incidencia\").value=\"AL\"; document.introd1.submit();'> \n";
echo "</div>\n"; //altres

// if($nregs!=0) print("<input type='submit' value='Gravar'>&nbsp; ".$paginador);
echo "<input type='hidden' id='id_esborrar' name='id_esborrar' value='' >\n";
print("</form><hr>\n");

// Llista expulsions
echo "<div id='expulsions-taula' class='taula'>\n";
echo "<h3> Expulsions </h3>\n";
$query = "SELECT G.id, G.refalumne, G.hora, G.profe, G.memo, CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup FROM $bdtutoria.$tbl_prefix"."guardia G, $bdtutoria.$tbl_prefix"."Estudiants E WHERE G.refalumne = E.numero_mat AND G.incidencia = 'E' AND G.data = $dia";
$res = mysql_query($query);
// echo "<p> Consulta: $query </p>\n";
echo "<table>\n";
echo "  <tr>\n";
echo "    <th></th>\n"; // esborrar
echo "    <th>Hora</th>\n";
echo "    <th>Profe</th>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
echo "    <th>Motiu</th>\n";
echo "  </tr>\n";
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td><a href='#' title='Esborrar' onClick='document.getElementById(\"id_esborrar\").value=\"$fila->id\";  document.introd1.submit(); '><img src='./imatges/paperera.gif' alt='Esborrar' border='0'></a></td>\n"; // esborrar
  echo "    <td>$fila->hora</td>\n";
  echo "    <td>$fila->profe</td>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
  echo "    <td>$fila->memo</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //expulsions-taula

// Llista retards
echo "<div id='retards-taula' class='taula'>\n";
echo "<h3> Retards </h3>\n";
$query = "SELECT G.id, G.refalumne, G.hora, G.memo, CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup FROM $bdtutoria.$tbl_prefix"."guardia G, $bdtutoria.$tbl_prefix"."Estudiants E WHERE G.refalumne = E.numero_mat AND G.incidencia = 'R' AND G.data = $dia";
$res = mysql_query($query);
echo "<table>\n";
echo "  <tr>\n";
echo "    <th></th>\n"; // esborrar
echo "    <th>Hora</th>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
echo "    <th>Observacions</th>\n";
echo "  </tr>\n";
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td><a href='#' title='Esborrar' onClick='document.getElementById(\"id_esborrar\").value=$fila->id; document.introd1.submit(); '><img src='./imatges/paperera.gif' alt='Esborrar' border='0'></a></td>\n"; // esborrar
  echo "    <td>$fila->hora</td>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
  echo "    <td>$fila->memo</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //retards-taula

// Llista abandonaments
echo "<div id='abandonaments-taula' class='taula'>\n";
echo "<h3> Abandonaments del centre </h3>\n";
$query = "SELECT G.id, G.refalumne, G.hora, G.memo, CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup FROM $bdtutoria.$tbl_prefix"."guardia G, $bdtutoria.$tbl_prefix"."Estudiants E WHERE G.refalumne = E.numero_mat AND G.incidencia = 'AC' AND G.data = $dia";
$res = mysql_query($query);
echo "<table>\n";
echo "  <tr>\n";
echo "    <th></th>\n"; // esborrar
echo "    <th>Hora</th>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
echo "    <th>Motiu</th>\n";
echo "  </tr>\n";
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td><a href='#' title='Esborrar' onClick='document.getElementById(\"id_esborrar\").value=$fila->id; document.introd1.submit(); '><img src='./imatges/paperera.gif' alt='Esborrar' border='0'></a></td>\n"; // esborrar
  echo "    <td>$fila->hora</td>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
  echo "    <td>$fila->memo</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //abandonaments-taula

// Llista altres
echo "<div id='altres-taula' class='taula'>\n";
echo "<h3> Altres incidències </h3>\n";
$query = "SELECT G.id, G.refalumne, G.hora, G.memo, CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup FROM $bdtutoria.$tbl_prefix"."guardia G, $bdtutoria.$tbl_prefix"."Estudiants E WHERE G.refalumne = E.numero_mat AND G.incidencia = 'AL' AND G.data = $dia";
$res = mysql_query($query);
echo "<table>\n";
echo "  <tr>\n";
echo "    <th></th>\n"; // esborrar
echo "    <th>Hora</th>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
echo "    <th>Incidència</th>\n";
echo "  </tr>\n";
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td><a href='#' title='Esborrar' onClick='document.getElementById(\"id_esborrar\").value=$fila->id; document.introd1.submit(); '><img src='./imatges/paperera.gif' alt='Esborrar' border='0'></a></td>\n"; // esborrar
  echo "    <td>$fila->hora</td>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
  echo "    <td>$fila->memo</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //altres-taula

echo "<div style='clear:both;'></div>\n";
echo "<hr>\n";

// Alumnes 15 retards
echo "<div id='retards15-eso-taula' class='taula'>\n";
echo "<h3> Alumnes amb ". $retards_ESO ." o més retards d'ESO </h3>\n";
echo "<table>\n";
echo "  <tr>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
// echo "    <th>Retards</th>\n";
echo "  </tr>\n";
// TODO: Variable number of periods (more than 3)
$ara = time();
$query_reset = '';
if( $reset_ESO ) {
  if($datatimestampIniciCurs <= $ara && $ara < $datatimestampInici2T) {
    $query_reset = " AND ($datatimestampIniciCurs <= data AND data < $datatimestampInici2T)"; 
  } elseif ($datatimestampInici2T <= $ara && $ara < $datatimestampInici3T) {
    $query_reset = " AND ($datatimestampInici2T <= data AND data < $datatimestampInici3T)"; 
  } elseif ($datatimestampInici3T <= $ara) {
    $query_reset = " AND $datatimestampInici3T <= data"; 
  } else {
      $query_reset = " AND FALSE ";
  }
} // fi reset_ESO
$query = "SELECT CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup, count(*) retards FROM $bdtutoria.$tbl_prefix"."faltes F, $bdtutoria.$tbl_prefix"."Estudiants E WHERE F.refalumne = E.numero_mat AND F.incidencia = 'R' AND E.pla_estudi = 'ESO' $query_reset GROUP BY F.refalumne HAVING COUNT(retards) >= ". $retards_ESO ." ORDER BY grup, alumne";
// echo "<p> Query: $query </p>\n";
$res = mysql_query($query);
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
//   echo "    <td>$fila->retards</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //retards15-eso-taula

echo "<div id='retards15-btx-taula' class='taula'>\n";
echo "<h3> Alumnes amb ". $retards_BTX ." o més retards de BTX </h3>\n";
echo "<table>\n";
echo "  <tr>\n";
echo "    <th>Grup</th>\n";
echo "    <th>Alumne</th>\n";
// echo "    <th>Retards</th>\n";
echo "  </tr>\n";
$query_reset = '';
if( $reset_BTX ) {
  if($datatimestampIniciCurs <= $ara && $ara < $datatimestampInici2T) {
    $query_reset = " AND ($datatimestampIniciCurs <= data AND data < $datatimestampInici2T)"; 
  } elseif ($datatimestampInici2T <= $ara && $ara < $datatimestampInici3T) {
    $query_reset = " AND ($datatimestampInici2T <= data AND data < $datatimestampInici3T)"; 
  } elseif ($datatimestampInici3T <= $ara) {
    $query_reset = " AND $datatimestampInici3T <= data"; 
  } else {
      $query_reset = " AND FALSE ";
  }
} // fi reset_BTX
$query = "SELECT CONCAT(E.cognom_alu, ' ',  E.cognom2_al, ', ', E.nom_alum) AS alumne, CONCAT(E.curs, ' ', E.pla_estudi, ' ', E.grup) AS grup, count(*) retards FROM $bdtutoria.$tbl_prefix"."faltes F, $bdtutoria.$tbl_prefix"."Estudiants E WHERE F.refalumne = E.numero_mat AND F.incidencia = 'R' AND E.pla_estudi = 'BATX' $query_reset GROUP BY F.refalumne HAVING COUNT(retards) >= ". $retards_BTX ." ORDER BY grup, alumne";
// echo "<p> Query: $query </p>\n";
$res = mysql_query($query);
while( $fila = mysql_fetch_object($res) ) {
  echo "  <tr>\n";
  echo "    <td>$fila->grup</td>\n";
  echo "    <td>$fila->alumne</td>\n";
//   echo "    <td>$fila->retards</td>\n";
  echo "  </tr>\n";
}
echo "</table>\n";
echo "</div>\n"; //retards15-btx-taula

?>
</body>
</html>
