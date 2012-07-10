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

echo '<link rel="stylesheet" type="text/css" href="css/comu.css" />';
echo '<link rel="stylesheet" type="text/css" href="css/cons_alum.css" />';

$maxpaginador=7;
if(isset($tots)) $maxpaginador=10000;
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
   cad="<a href='' onClick='document.introd1.dataF.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; document.introd1.submit(); return false;'>Inici Curs</a>";
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

</script>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">

<?

print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>
<table border='0'>
<tr><td><font size='6'>Incid&egrave;ncies per grup&nbsp; &nbsp; </font></td>
<td><input type='button' value='Imprimir' onClick='window.print();'>&nbsp; &nbsp; </td>
<td valign='top'><b>Des de:</b><br><input type='text' name='dataI' size='13' value='".((isset($dataI))?$dataI:$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp))."' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,0);'></td>
<td valign='top'><b>Fins:</b><br><input type='text' name='dataF' size='13' value='".((isset($dataF))?$dataF:$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp))."' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,1);'></td>
<td valign='top'>");

if ( (isset($grup)&&$grup=='Subgrups') || (isset($subgrup)&&$subgrup!='Grups')) {
  print("<b>Subgrup:</b><br><select name='subgrup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
  <option></option><option>Grups</option>");
  do {print("<option".((stripslashes($subgrup)==rawurldecode(current($llista_subgrups)))?" selected":"").">".rawurldecode(current($llista_subgrups))."</option>");} while(next($llista_subgrups));
  if($grup=='Subgrups') $grup='';
}
else {
  print("<b>Grup:</b><br><select name='grup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
  <option></option><option>Subgrups</option>");
  do {print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");} while(next($llista_grups));
  if($subgrup=='Grups') $subgrup='';
}
print("</select></td></tr></table></div><hr>");


if (isset($dataI)&&$dataI!=''&&isset($dataF)&&$dataF!=''&&((isset($grup)&&$grup!='')||(isset($subgrup)&&$subgrup!=''))) {
  if($grup!='') {
  $gru=split(' ', $grup);
  $consulta="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE (curs='$gru[0]' and grup='$gru[1]' and pla_estudi='$gru[2]')";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $nregs=mysql_result($conjunt_resultant, 0,0);
  mysql_free_result($conjunt_resultant);
  }
  else {
    $subgru=split(' ',$subgrup);
    $consulta="SELECT alumnes FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='$subgru[0]' limit 1";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $alssubgrup=split(',',mysql_result($conjunt_resultant, 0,0));
    if(''==mysql_result($conjunt_resultant, 0,0)) $nregs=0;
    else $nregs=count($alssubgrup); 
    mysql_free_result($conjunt_resultant);
    
  }
  $datI=split(' ', $dataI);
  $daI=split('-', $datI[1]);
  $datatimestampI=mktime(0,0,0,$daI[1],$daI[0],$daI[2],-1);
  $datF=split(' ', $dataF);
  $daF=split('-', $datF[1]);
  $datatimestampF=mktime(0,0,0,$daF[1],$daF[0],$daF[2],-1);
  $filtredata= "and data>='$datatimestampI' and data<='$datatimestampF' ";

  $filtredies='';
  if($dies!="") {
    $filtredies .= "and (";
    $dis=split(';',$dies);
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
    $hor=split(';',$hores);
    for($i=0; $i<count($hor); ++$i) {
      if($i!=0) $filtrehores .= " or ";
      $filtrehores .= "hora='$hor[$i]'";
    }
    $filtrehores .= ") ";
  }
  $filtreprofe = '';
  if( $profe != "" )
  {
    $filtreprofe = " AND usuari = '$profe' ";
  }
  
  $filtreextra = '';
  if( ! $extra )
    $filtreextra = " AND hora IN (SELECT hora from $bdtutoria.$tbl_prefix"."frangeshoraries WHERE NOT extraescolar OR extraescolar IS NULL) ";
/*  else // no cal, podem filtrar per hores per seleccionar només les extraescolars
    $filtreextra = " AND hora IN (SELECT hora from $bdtutoria.$tbl_prefix"."frangeshoraries WHERE extraescolar) ";*/
  
  $filtres = $filtredata . $filtredies . $filtrehores . $filtreprofe . $filtreextra;
//   echo "<p> Filtres: $filtres </p>\n";


  print("<table border='0' width='100%'><tr><td align='center' valign='top' width='60'>");
  if($filtredies=='') print("<br><b>Filtre<br>Dies:</b><br>");
  else print("<font color='#ff0000'><br><b>Filtre<br>Dies:</b></font><br>");
  print("<input type='hidden' name='dies' value='$dies'>
  <select name='di' size='5' multiple onChange='fdies(); document.introd1.submit();'>");
  for($i=1;$i<count($nomDiaSem)-1;++$i) print("<option".((preg_match('/'. $nomDiaSem[$i] .'/',$dies))?" selected":"").">$nomDiaSem[$i]</option>");
  print("</select><br>");

  // filtre hores
  if($filtrehores=='') print("<br><b>Filtre<br>Hores:</b><br>");
  else print("<font color='#ff0000'><br><b>Filtre<br>Hores:</b></font><br>");
  print("<input type='hidden' name='hores' value='$hores'>
  <select name='ho' size='12' multiple onChange='fhores(); document.introd1.submit();'>");
  $consulta1="SELECT hora FROM $bdtutoria.$tbl_prefix"."frangeshoraries";
  if( ! $extra ) $consulta1 .= " WHERE NOT extraescolar OR extraescolar IS NULL";
  $consulta1 .= " order by inici asc";
  $conjunt_resultant1=mysql_query($consulta1, $connect);
  $p=split(';',$hores);
  while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    $sel=false;
    for($j=0; $j<count($p);++$j) if($p[$j]==$fila1[0]) $sel=true;
    print("<option".(($sel)?" selected":"").">$fila1[0]</option>");
  }
  mysql_free_result($conjunt_resultant1);
  print("</select><br>");

  // filtre extraescolar
//   print("<td align='center' valign='bottom'>");
  if(!$extra) print("<label for='extra' class='filtre_actiu'><b>Extra-<br>escolar:</b><br></label>");
  else print("<label for='extra'> <b>Extra<br>escolar:</b> <br></label>");
  echo "<input type='checkbox' name='extra' id='extra' ".((isset($extra))?" checked='checked'":"")." onChange='document.introd1.submit();'>\n";
  echo "</td>\n";
  
  print("<td valign='top'>");
    
    $paginadoractual=0;
    if(!isset($paginadoranterior)) $paginadoranterior=-1;
    if(!isset($paginadorseguent)) $paginadorseguent=-1;
    print("<input type='hidden' name='paginadorseguent' value='$paginadorseguent'>");
    print("<input type='hidden' name='paginadoranterior' value='$paginadoranterior'>");
    if($nregs>$maxpaginador) {
     if($paginadorseguent!=-1) {
       $paginadoractual=$paginadorseguent;
     }
     if($paginadoranterior!=-1) {
       $paginadoractual=$paginadoranterior;
     }
    }
    if(($paginadoractual-$maxpaginador) >= 0) $paginadorenrere=true; else $paginadorenrere=false;
    if(($paginadoractual+$maxpaginador) < $nregs) $paginadorendavant=true; else $paginadorendavant=false;
    $paginador = ($paginadorenrere)?"<a href='' onClick='document.introd1.paginadorseguent.value=\"-1\"; document.introd1.paginadoranterior.value=\"0\"; document.introd1.submit(); return false;'>":"";
    $paginador.= "<<";
    $paginador.= ($paginadorenrere)?"</a>":"";
    $paginador.= "&nbsp; ";
    $paginador.= ($paginadorenrere)?"<a href='' onClick='document.introd1.paginadorseguent.value=\"-1\"; document.introd1.paginadoranterior.value=\"".($paginadoractual-$maxpaginador)."\"; document.introd1.submit(); return false;'>":"";
    $paginador.= "<";
    $paginador.= ($paginadorenrere)?"</a>":"";
    $paginador.= "&nbsp; &nbsp; Alumnes ".(($nregs!=0)?($paginadoractual+1):0)." - ".((($paginadoractual+$maxpaginador)<=$nregs)?($paginadoractual+$maxpaginador):$nregs)."&nbsp; &nbsp; ";
    $paginador.= ($paginadorendavant)?"<a href='' onClick='document.introd1.paginadoranterior.value=\"-1\"; document.introd1.paginadorseguent.value=\"".($paginadoractual+$maxpaginador)."\"; document.introd1.submit(); return false;'>":"";
    $paginador.= ">";
    $paginador.= ($paginadorendavant)?"</a>":"";
    $paginador.= "&nbsp; ";
    $paginadorsup=(($maxpaginador*(floor($nregs/$maxpaginador))));
    if($paginadorsup==$nregs) $paginadorsup=$nregs-1;
    if(($nregs%$maxpaginador)==0) $paginadorsup=$nregs-$maxpaginador;
    $paginador.= ($paginadorendavant)?"<a href='' onClick='document.introd1.paginadoranterior.value=\"-1\"; document.introd1.paginadorseguent.value=\"".$paginadorsup."\"; document.introd1.submit(); return false;'>":"";
    $paginador.= ">>";
    $paginador.= ($paginadorendavant)?"</a>":"";
    $paginador.= "&nbsp; de $nregs";
    
    if($nregs!=0) 
    {
      print("$paginador (<input type='checkbox' name='tots'".((isset($tots))?" checked":"")." onClick='document.introd1.submit();'> Tots)\n");
    // filtre profes
//       print("<td colspan='2' align='center' valign='bottom'>");
      if($filtreprofe=='') print("<label for='profe'><b>Profe: </b></label>");
      else print("<label for='profe' class='filtre_actiu'><b>Profe: </b></label>");
      print("<select name='profe' name='profe' id='profe' onChange='document.introd1.submit();'>");
      print("<option value='' ".(($profe=='')?" selected":"")."> </option> \n");
      $consulta1="SELECT usuari, nomreal FROM $bdtutoria.$tbl_prefix"."usu_profes order by usuari asc";
      $conjunt_resultant1=mysql_query($consulta1, $connect);
      while($fila1=mysql_fetch_row($conjunt_resultant1)) {
	print("<option value='$fila1[0]' ".(($profe==$fila1[0])?" selected":"").">$fila1[0] - $fila1[1]</option> \n");
      }
      mysql_free_result($conjunt_resultant1);
      print("</select>\n");
//       print("</td>\n");
      print("<hr>\n");
    } else print("Aquest subgrup no t&eacute; alumnes.");
    if($grup!='') {
      $gru=split(' ', $grup);
      $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum)  FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE (curs='$gru[0]' and grup='$gru[1]' and pla_estudi='$gru[2]') ORDER  BY cognom_alu, cognom2_al, nom_alum ASC LIMIT $paginadoractual,$maxpaginador";
    }
    else {
      $consulta ="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi ";
      $consulta.="FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE ";
      $cons='';
      foreach($alssubgrup as $nal) {
        if ($cons!='') $cons.='or ';
        $cons.="numero_mat='$nal' ";
      }
      $consulta.= $cons;
      $consulta.="ORDER BY cognom_alu, cognom2_al, nom_alum ASC ";
      $consulta.="LIMIT $paginadoractual,$maxpaginador";
    }
    $conjunt_resultant=mysql_query($consulta, $connect);
    print("<table border='0' id='taulacos'>");

    $compt_capcal=0;
    $ref_incid=split(',',$ref_incidenciaj);
    $ref_incidencia_tex=split(',', $ref_incidencia_textj);
    $capcal="<tr bgcolor='#0088cc'><td colspan='2' align='right'><b>Incidencia:</b></td>";
    for($i=0; $i<count($ref_incid); ++$i) 
      $capcal .= "<td><center>$ref_incid[$i]</center></td>";
    $capcal .="<td><center><b>+</b></center></td>";
    $capcal .="<td><center><b>-</b></center></td>";
    $capcal .="</tr>";
    if($nregs!=0) {
      print("<tr bgcolor='#0088cc'><td colspan='2'>&nbsp</td>");
      for($i=0; $i<count($ref_incid); ++$i) 
	print("<td><center><b>$ref_incidencia_tex[$i]</b></center></td>");
      // positius i negatius
      print("<td><center><b>Positius</b></center></td>");
      print("<td><center><b>Negatius</b></center></td>");
      print("</tr>");
    }
    while ($fila=mysql_fetch_row($conjunt_resultant)) {
      if($compt_capcal%5==0) print($capcal);
      ++$compt_capcal;
      if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
      else $foto = "./imatges/fot0.jpg";
      $linkfil="<a href='' onClick='obreFoto(\"$foto\", \"$fila[1]\"); return false;'><img src='$foto' width='25' height='34' border='0'></a>";
      print("<tr bgcolor='#aacccc'><td>".$linkfil."</td><td>".$fila[1]);
      if($subgrup!='') {
        print(" <font size='-2'>($fila[2] $fila[3] $fila[4])</font>");
      }
      print("</td>");
      for($i=0; $i<count($ref_incid); ++$i) {

          $incid=$ref_incid[$i];
          $consulta  = "select count(*) ";
          $consulta .= "from $bdtutoria.$tbl_prefix"."faltes ";
          $consulta .= "where refalumne='$fila[0]' ";
          $consulta .= "$filtres ";
          $consulta .= "and incidencia='$incid'";
//           echo "<p> Consulta: $consulta </p>\n";
          $conjunt_resultant1=mysql_query($consulta, $connect);
          print("<td>");
          print("<center>".mysql_result($conjunt_resultant1, 0,0)."</center>");
          print("</td>");
          mysql_free_result($conjunt_resultant1);
      }
      // positius i negatius
      $consulta  = "SELECT COUNT(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' $filtres AND incidencia='A' AND memo LIKE '+%'";
      $conjunt_resultant1=mysql_query($consulta, $connect);
      print("<td>");
      print("<center>".mysql_result($conjunt_resultant1, 0,0)."</center>");
      print("</td>");
      mysql_free_result($conjunt_resultant1);
      $consulta  = "SELECT COUNT(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' $filtres AND incidencia='A' AND memo LIKE '-%'";
      $conjunt_resultant1=mysql_query($consulta, $connect);
      print("<td>");
      print("<center>".mysql_result($conjunt_resultant1, 0,0)."</center>");
      print("</td>");
      mysql_free_result($conjunt_resultant1);

      print("</tr>");
    }
    mysql_free_result($conjunt_resultant);
    print("</table><hr>");
    if($nregs!=0) print("$paginador");


  print("</td></tr></table><hr>");
}
print("</form>");


?>
</body>
</html>
