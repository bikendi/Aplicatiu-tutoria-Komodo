<?
/*
    Aplicatiu Tutoria Komodo v.0.1
    Aplicaci� web per a la gesti� de la tasca tutorial.
    Copyright (C) 2002-2007  Artur Guillamet Sabat� <aguillam(a)xtec.net>
    Copyright (C) 2012 �ingen Eguzkitza <beguzkit@xtec.cat>

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
$priv=preg_split("/_/", $sess_privilegis);
$nalumne=$priv[1];

echo '<link rel="stylesheet" type="text/css" href="css/comu.css" />';
echo '<link rel="stylesheet" type="text/css" href="css/cons_alum.css" />';
?>
<script language='JavaScript'>
function calendariEscriuDia(di, i, mes, any) {
 var cad;
 if(di=='Avui') {
   var avui= new Date(actual);
   cad="<a href='' onClick='var tmp=document.introd1.dataI.value; document.introd1.dataI.value=\"<?print($nomDiaSem[date('w',$datatimestamp)]);?>, "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; if(validadates()) document.introd1.submit(); else document.introd1.dataI.value=tmp; return false;'>Avui</a>";
   return cad;
 }
 if(di=='ICurs') {
   cad="<a href='' onClick='var tmp=document.introd1.dataI.value; document.introd1.dataI.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; if(validadates()) document.introd1.submit(); else document.introd1.dataI.value=tmp; return false;'>Inici Curs</a>";
   return cad;
 }
 cad="<a href='' onClick='var tmp=document.introd1.dataI.value; document.introd1.dataI.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; if(validadates()) document.introd1.submit(); else document.introd1.dataI.value=tmp; return false;'>" + i + "</a>";
 return cad;
}
function calendariEscriuDia1(di, i, mes, any) {
 var cad;
 if(di=='Avui') {
   var avui= new Date(actual);
   cad="<a href='' onClick='var tmp=document.introd1.dataF.value; document.introd1.dataF.value=\"<?print($nomDiaSem[date('w',$datatimestamp)]);?>, "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; if(validadates()) document.introd1.submit(); else document.introd1.dataF.value=tmp; return false;'>Avui</a>";
   return cad;
 }
 if(di=='ICurs') {
   cad="<a href='' onClick='var tmp=document.introd1.dataF.value; document.introd1.dataF.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; if(validadates()) document.introd1.submit(); else document.introd1.dataF.value=tmp; return false;'>Inici Curs</a>";
   return cad;
 }
 cad="<a href='' onClick='var tmp=document.introd1.dataF.value; document.introd1.dataF.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; if(validadates()) document.introd1.submit(); else document.introd1.dataF.value=tmp; return false;'>" + i + "</a>";
 return cad;
}

function validadates() {
 var VdataI=document.introd1.dataI.value.split(' ')[1].split('-');
 var VdataF=document.introd1.dataF.value.split(' ')[1].split('-');
 var mktDataI=new Date(VdataI[2],VdataI[1]-1,VdataI[0],0,0,0);
 var mktDataF=new Date(VdataF[2],VdataF[1]-1,VdataF[0],0,0,0);
 if ((mktDataF-mktDataI)<0) {
   alert("Ep! La data final no pot ser anterior a la data inicial.");
   return false;
 }
 else return true;
}
function fdies() {
 var cad='';
 for (var i=0; i<document.introd1.di.options.length; ++i) {
   if(document.introd1.di.options[i].selected) {
     if (cad!='') cad += ';'
     cad += document.introd1.di.options[i].text;
   }
 }
 document.introd1.dies.value=cad;
}
function fhores() {
 var cad='';
 for (var i=0; i<document.introd1.ho.options.length; ++i) {
   if(document.introd1.ho.options[i].selected) {
     if (cad!='') cad += ';'
     cad += document.introd1.ho.options[i].text;
   }
 }
 document.introd1.hores.value=cad;
}
function fincidencia() {
 var cad='';
 for (var i=0; i<document.introd1.incid.options.length; ++i) {
   if(document.introd1.incid.options[i].selected) {
     if (cad!='') cad += ';'
     cad += document.introd1.incid.options[i].text;
   }
 }
 document.introd1.incidencia.value=cad;
}
</script>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">

<?
if(!isset($dataI)) $dataI=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs);
if(!isset($dataF)) $dataF=$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp);

print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>
<table border='0'>
<tr><td><font size='6'>Registre d'Incid&egrave;ncies&nbsp; &nbsp; </font></td><td><b>Des de:</b><br><input type='text' name='dataI' size='13' value='$dataI' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,0);'></td><td><b>Fins:</b><br><input type='text' name='dataF' size='13' value='$dataF' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,1);'></td></tr></table>
");

print("<table border='0'>
<tr><td>&nbsp; &nbsp; &nbsp; </td>
<td align='right'><b>Alumne: <font color='#0000ff'>$sess_nomreal</font></b></td></tr>
</table>
</div><hr>
");

if (isset($dataI)&&$dataI!=''&&isset($dataF)&&$dataF!=''&&isset($nalumne)&&$nalumne!='') {
  $datI=preg_split('/ /', $dataI);
  $daI=preg_split('/-/', $datI[1]);
  $datatimestampI=mktime(0,0,0,$daI[1],$daI[0],$daI[2],-1);
  $datF=preg_split('/ /', $dataF);
  $daF=preg_split('/-/', $datF[1]);
  $datatimestampF=mktime(0,0,0,$daF[1],$daF[0],$daF[2],-1);
  $filtredata= "and data>='$datatimestampI' and data<='$datatimestampF' ";
  $filtredies='';
  if($dies!="") {
    $filtredies .= "and (";
    $dis=preg_split('/;/',$dies);
    for($i=0; $i<count($dis); ++$i) {
      if($i!=0) $filtredies .= " or ";
      $numSem=-1;
      for($j=0; $j<=count($nomDiaSem);++$j) if($nomDiaSem[$j]==$dis[$i]) $numSem=$j;
      $filtredies .= "from_unixtime(data,'%w')='$numSem'";
    }
    $filtredies .= ") ";
  }
  $filtrehores='';
  if($hores!="") {
    $filtrehores .= "and (";
    $hor=preg_split('/;/',$hores);
    for($i=0; $i<count($hor); ++$i) {
      if($i!=0) $filtrehores .= " or ";
      $filtrehores .= "hora='$hor[$i]'";
    }
    $filtrehores .= ") ";
  }
  $filtreincidencia='';
  if($incidencia!="") {
    $filtreincidencia .= "and (";
    $inciden=preg_split('/;/',$incidencia);
    for($i=0; $i<count($inciden); ++$i) {
      if($i!=0) $filtreincidencia .= " or ";
      $filtreincidencia .= "incidencia='$inciden[$i]'";
    }
    $filtreincidencia .= ") ";
  }
  $filtreprofe = '';
  if( $profe != "" )
  {
    $filtreprofe = " AND F.usuari = '$profe' ";
  }
  $filtreextra = '';
  if( ! $extra )
    $filtreextra = " AND hora IN (SELECT hora from $bdtutoria.$tbl_prefix"."frangeshoraries WHERE NOT extraescolar OR extraescolar IS NULL) ";
/*  else // no cal, podem filtrar per hores per seleccionar nom�s les extraescolars
    $filtreextra = " AND hora IN (SELECT hora from $bdtutoria.$tbl_prefix"."frangeshoraries WHERE extraescolar) ";*/
  
  $filtres = $filtredata . $filtredies . $filtrehores . $filtreprofe . $filtreextra;

// informes d'incid�ncia
    $consulta="SELECT id, id_prof, data, hora, text, ref_alum FROM $bdtutoria.$tbl_prefix"."informeincid WHERE ref_alum='$nalumne' AND public ORDER BY data desc, id DESC";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $nregs=mysql_num_rows($conjunt_resultant);
    print("<br>Aquest alumne t&eacute; $nregs <a href='regtut.php?idsess=$idsess&nalumne=$nalumne'>informes</a> creats. \n");
// fi informes

  print("<table border='0' width='100%' id='taulacos'>");
  print("<tr><td rowspan='2' align='center'>");
  if(file_exists("$dirfotos/$nalumne.jpg")) print("<img src='./foto.php?idsess=$idsess&foto=$nalumne' width='93' height='125'>");
  else print("<img src='./imatges/fot0.jpg'>");
  print("</td>");
  
  // filtre profes
  print("<td colspan='3' align='center' valign='bottom'>");
  if($filtreprofe=='') print("<label for='profe'><b>Profe: </b></label>");
  else print("<label for='profe' class='filtre_actiu'><b>Profe: </b></label>");
  print("<select name='profe' id='profe' onChange='document.introd1.submit();'>");
  print("<option value='' ".(($profe=='')?" selected":"")."> </option> \n");
  $consulta1="SELECT usuari, nomreal FROM $bdtutoria.$tbl_prefix"."usu_profes order by usuari asc";
  $conjunt_resultant1=mysql_query($consulta1, $connect);
  while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    print("<option value='$fila1[0]' ".(($profe==$fila1[0])?" selected":"").">$fila1[0] - $fila1[1]</option> \n");
  }
  mysql_free_result($conjunt_resultant1);
  print("</select>\n");
  print("</td>\n");

  $ref_incid=preg_split('/,/',$ref_incidenciaj);
  $ref_incidencia_tex=preg_split('/,/', $ref_incidencia_textj);
  // +2 pels positius i negatius
  print("<td colspan='".(count($ref_incid)+2)."' bgcolor='#0088cc'> <b>Resum:</b> </td> </tr>");
  
//  print("<tr bgcolor='#aacccc'>");
  print("<tr >");
  // filtre dies
  print("<td align='center' valign='bottom'>");
  if($filtredies=='') print("<label for='dies'><b>Filtre<br>Dies:</b><br></label>");
  else print("<label for='dies' class='filtre_actiu'><b>Filtre<br>Dies:</b><br></label>");
  print("<input type='hidden' name='dies' value='$dies'>
  <select name='di' size='4' multiple onChange='fdies(); document.introd1.submit();'title=''>");
  for($i=1;$i<count($nomDiaSem)-1;++$i) print("<option".((preg_match('/'. $nomDiaSem[$i] .'/',$dies))?" selected":"").">$nomDiaSem[$i]</option>");
  print("</select>");
  print("</td>");
  // filtre hores
  print("<td align='center' valign='bottom'>");
  if($filtrehores=='') print("<label for='hores'><b>Filtre<br>Hores:</b><br></label>");
  else print("<label for='hores' class='filtre_actiu'><b>Filtre<br>Hores:</b><br></label>");
  print("<input type='hidden' name='hores' value='$hores'>
  <select name='ho' size='4' multiple onChange='fhores(); document.introd1.submit();'>");
  $consulta1="SELECT hora FROM $bdtutoria.$tbl_prefix"."frangeshoraries";
  if( ! $extra ) $consulta1 .= " WHERE NOT extraescolar OR extraescolar IS NULL";
  $consulta1 .= " order by inici asc";
  $conjunt_resultant1=mysql_query($consulta1, $connect);
  $p=preg_split('/;/',$hores);
  while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    $sel=false;
    for($j=0; $j<count($p);++$j) if($p[$j]==$fila1[0]) $sel=true;
    print("<option".(($sel)?" selected":"").">$fila1[0]</option>");
  }
  mysql_free_result($conjunt_resultant1);
  print("</select>");
  print("</td>");

  // filtre extraescolar
  print("<td align='center' valign='bottom'>");
  if(!$extra) print("<label for='extra' class='filtre_actiu'><b>Extra-<br>escolar:</b><br></label>");
  else print("<label for='extra'> <b>Extra<br>escolar:</b> <br></label>");
  echo "<input type='checkbox' name='extra' id='extra' ".((isset($extra))?" checked='checked'":"")." onChange='document.introd1.submit();'>\n";
  print("</td>");

  // Resum
  for($i=0; $i<count($ref_incid); ++$i) {
    $incid=$ref_incid[$i];
    $consulta  = "select count(*) ";
    $consulta .= "from $bdtutoria.$tbl_prefix"."faltes ";
    $consulta .= "where refalumne='$nalumne' ";
    $consulta .= "$filtres ";
    $consulta .= "and incidencia='$incid'";
    $conjunt_resultant=mysql_query($consulta, $connect);
    //TODO: no va
    print("<td class='td_resum'>");
    print("<center><b>$ref_incidencia_tex[$i]</b><br>($incid)<br><br><font size='5'>".mysql_result($conjunt_resultant, 0,0)."</font></center>");
    print("</td>");
    mysql_free_result($conjunt_resultant);
  }
  // positius i negatius
    $consulta  = "SELECT COUNT(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$nalumne' $filtres AND incidencia='A' AND memo LIKE '+%'";
    $conjunt_resultant=mysql_query($consulta, $connect);
    //TODO: no va
    print("<td class='td_resum'>");
    print("<center><b>Positius</b><br>(+)<br><br><font size='5'>".mysql_result($conjunt_resultant, 0,0)."</font></center>");
    print("</td>");
    $consulta  = "SELECT COUNT(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$nalumne' $filtres AND incidencia='A' AND memo LIKE '-%'";
    $conjunt_resultant=mysql_query($consulta, $connect);
    //TODO: no va
    print("<td class='td_resum'>");
    print("<center><b>Negatius</b><br>(-)<br><br><font size='5'>".mysql_result($conjunt_resultant, 0,0)."</font></center>");
    print("</td>");
    mysql_free_result($conjunt_resultant);

  print("</tr></table>");

  print("<table border='0' width='100%'><tr><td>&nbsp;</td><td bgcolor='#0088cc'><b>Detalls:</b></td></tr><tr><td align='center' valign='top' width='60'>");
  $incid=preg_split('/,/', $ref_incidenciaj);
  if($filtreincidencia=='') print("<b>Filtre<br>Incid&egrave;ncia:</b><br>");
  else print("<font color='#ff0000'><b>Filtre<br>Incid&egrave;ncia:</b></font><br>");
  print("<input type='hidden' name='incidencia' value='$incidencia'>
  <select name='incid' size='6' multiple onChange='fincidencia(); document.introd1.submit();'>");
  $p=preg_split('/;/',$incidencia);
  for($i=0; $i<count($incid);++$i) {
   $sel=false;
   for($j=0; $j<count($p);++$j) if($p[$j]==$incid[$i]) $sel=true;
   print("<option".(($sel)?" selected":"").">$incid[$i]</option>");
  }
  print("</select></td>");

  print("<td valign='top'>");

  //$consulta  = "select id, refalumne, data, hora, incidencia, memo ";
  $consulta  = "select id, refalumne, data, hora, incidencia, memo, P.nomreal ";
  //$consulta .= "from $bdtutoria.$tbl_prefix"."faltes ";
  $consulta .= "from $bdtutoria.$tbl_prefix"."faltes F, $bdtutoria.$tbl_prefix"."usu_profes P ";
  //$consulta .= "where refalumne='$nalumne' $filtredata ";
  $consulta .= "where F.usuari = P.usuari "; 
  $consulta .= "AND refalumne='$nalumne' ";
  $consulta .= "$filtres ";
  $consulta .= "order by data";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $nfiles=mysql_num_rows($conjunt_resultant);
  if($nfiles==0) {
    print("<br>No hi ha cap incid&egrave;ncia registrada.");
  }
  else {
    print("<table border='0' width='100%' id='taulacos'>
      <tr bgcolor='#0088cc'>
	<td align='center'><b>Data</b></td>
	<td align='center'><b>Hora</b></td>
	<td align='center'><b>Incid&egrave;ncia</b></td>
	<td><b>Text</b></td>
	<td><b>Professor</b></td>
      </tr>");
    while($fila=mysql_fetch_row($conjunt_resultant)) {
      print("<tr bgcolor='#aacccc'>
	<td width='110'>".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."</td>
	<td width='35' align='center'>".$fila[3]."</td>
	<td width='70' align='center'>".$fila[4]."</td>
	<td>".(($fila[5]!="")?"<script language='JavaScript'>document.write(unescape('". mysql_real_escape_string($fila[5]) ."'));</script>":"&nbsp;")."</td>
      	<td width='110'>".$fila[6]."</td>
      </tr>");
    }
    print("</table>");
  }
  mysql_free_result($conjunt_resultant);
  print("</td></tr></table><hr>");

}

print("</form>");


?>
</body>
</html>
