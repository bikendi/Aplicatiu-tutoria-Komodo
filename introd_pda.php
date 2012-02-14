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

@include("linkbd.inc.php");
$pda='';
@include("comu.php");
@include("comu.js.php");
panyacces("Privilegis");
$maxpaginador=12;
if(isset($tots)) $maxpaginador=10000;
?>
<html>
<head>
<title>Tutoria</title>
</head>

<body bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
print("<center><a href='menu_pda.php?pda=&idsess=$idsess'>Tornar</a> &nbsp; &nbsp; Usuari: <font color='#0000ff'>$sess_nomreal</font></center>");

if(isset($data) and (isset($grup)||isset($subgrup)) and isset($hora)) {
  $quantitatpost=count($HTTP_POST_VARS);
  for($i=0; $i<$quantitatpost; ++$i) {
    $key=key($HTTP_POST_VARS);
    $noms=split('_', $key);
    if($noms[0]=='inc') {
      if($noms[1]==0) { 
        if(current($HTTP_POST_VARS)!='') { 
	  $memo="";
	  $aux="t_0_".$noms[2]."_".$noms[3]."_0";
	  eval("\$memo=\$$aux;");
	  $dia=mktime(0,0,0,date('n',$datatimest),date('d',$datatimest),date('Y',$datatimest),-1);
	  $consulta="insert into $bdtutoria.$tbl_prefix"."faltes SET refalumne='".$noms[2]."', data='".$dia."', hora='".$noms[3]."', incidencia='".current($HTTP_POST_VARS)."', usuari='$sess_user', memo='".$memo."'";
	  mysql_query($consulta, $connect);
	}
      }
      else { 
        if(current($HTTP_POST_VARS)!=$noms[4]) {
	  if(current($HTTP_POST_VARS)=="") { 
	    $consulta="delete from $bdtutoria.$tbl_prefix"."faltes where id='".$noms[1]."' LIMIT 1";
	    mysql_query($consulta, $connect);
	  }
	  else { 
	    $consulta="update $bdtutoria.$tbl_prefix"."faltes SET incidencia='".current($HTTP_POST_VARS)."' WHERE id='".$noms[1]."' LIMIT 1";
	    mysql_query($consulta, $connect);
	  }
	}
      }
    }
    if($noms[0]=='t' and $noms[1]!='0') {
      $memo=current($HTTP_POST_VARS);
      $sum=0;
      for($j=0;$j<strlen($memo);++$j) {
        $sum+=(ord(substr($memo,$j,1))*($j+1));
      }
      if ($sum!=intval($noms[4])) {
        $consulta="update $bdtutoria.$tbl_prefix"."faltes SET memo='".$memo."' WHERE id='".$noms[1]."' LIMIT 1";
	mysql_query($consulta, $connect);
      }
    }
    next($HTTP_POST_VARS);
  }
}



print("
<table border='0'>
<tr><td colspan='2'><img src='imatges/pixelblank.gif' height='1' width='280'></td></tr>
<form name='introd1' method='post' action='".$PHP_SELF."?pda=&idsess=$idsess' onSubmit='return true; //verifica'>
<tr><td colspan='2'><input type='hidden' name='datatimest' value='$datatimestamp'>
Data: <select name='data' onChange='document.introd1.submit();'>");
$i=12;
while($i>0) {
  if($nomDiaSem[date('w',($datatimestamp-(86400*$i)))]!='Dg' && $nomDiaSem[date('w',($datatimestamp-(86400*$i)))]!='Ds') {
    print("<option>".$nomDiaSem[date('w',($datatimestamp-(86400*$i)))].", ".date('d-n-Y',($datatimestamp-(86400*$i)))."</option>");
  }
  --$i;
}
print("<option selected>".$nomDiaSem[date('w',$datatimestamp)].", ".date('d-n-Y',$datatimestamp)."</option>
<option>Avui</option></select></td></tr>
<tr><td>
Hora:<br><select name='hora' onChange='document.introd1.submit();'>
<option></option>");
$consulta1="SELECT hora FROM $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
$conjunt_resultant1=mysql_query($consulta1, $connect);
while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    print("<option".(($hora==$fila1[0])?" selected":"").">$fila1[0]</option>");
}
mysql_free_result($conjunt_resultant1);
print("</select>
</td>
<td>");
if ( (isset($grup)&&$grup=='Subgrups') || (isset($subgrup)&&$subgrup!='Grups')) {
  print("Subgrup:<br><select name='subgrup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
  <option></option><option>Grups</option>");
  do {
     $permis=privilegis('X', 'X',current($llista_subgrups));
     if($permis) print("<option".((stripslashes($subgrup)==rawurldecode(current($llista_subgrups)))?" selected":"").">".rawurldecode(current($llista_subgrups))."</option>");
  } while(next($llista_subgrups));
  if($grup=='Subgrups') $grup='';
}
else {
  print("Grup:<br><select name='grup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
  <option></option><option>Subgrups</option>");
  do {print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");} while(next($llista_grups));
  if($subgrup=='Grups') $subgrup='';
}
print("</select>
</td>
</tr>
</table><hr>
");
if (($grup!=""||$subgrup!="") && $hora!="") {
  
  $paginadoractual=0;
  if(!isset($paginadoranterior)) $paginadoranterior=-1;
  if(!isset($paginadorseguent)) $paginadorseguent=-1;
  print("<input type='hidden' name='paginadorseguent' value='$paginadorseguent'>");
  print("<input type='hidden' name='paginadoranterior' value='$paginadoranterior'>");
  if($grup!='') {
   $gru=split(' ',$grup);
   $consulta="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."'";
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
  $paginador.= "&nbsp; ".(($nregs!=0)?($paginadoractual+1):0)." - ".((($paginadoractual+$maxpaginador)<=$nregs)?($paginadoractual+$maxpaginador):$nregs)."&nbsp; ";
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
  
  if($nregs!=0) print("&nbsp;<input type='submit' value='Gravar'>&nbsp; &nbsp; ".$paginador." <input type='checkbox' name='tots'".((isset($tots))?" checked":"")." onClick='document.introd1.submit();'> Tots");
  else print("Aquest subgrup no t&eacute; alumnes.");
  if($grup!='') {
    $gru=split(' ',$grup);
    $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' ORDER BY cognom_alu, cognom2_al ASC LIMIT $paginadoractual,$maxpaginador";
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
   $consulta.="ORDER BY cognom_alu, cognom2_al ASC ";
   $consulta.="LIMIT $paginadoractual,$maxpaginador";  
  }  
  $conjunt_resultant=mysql_query($consulta, $connect);
  print("<table border='0'>");
  print("<tr><td colspan='3'><img src='.imatges/pixelblank.gif' height='1' width='280'></td></tr>");
  $capcal = "<tr bgcolor='#0088cc'><td>&nbsp;</td><td align='right'><b>Hora:</b></td><td align='center' width='38'><b>".$hora."</b></td></tr>";
  
  $incid=split(',', $ref_incidencia);
  $compt_capcal=0;
  while ($fila=mysql_fetch_row($conjunt_resultant)) {
    if($compt_capcal%5==0) print($capcal);
    ++$compt_capcal;
    if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
    else $foto = "./imatges/fot0.jpg";
    $linkfil="<a href='$foto'><img src='$foto' width='25' height='34' border='0'></a>";
    print("<tr bgcolor='#aacccc'><td>".$linkfil."</td><td>".$fila[1]." ");
    if($subgrup!='') {
      print("<font size='-2'>($fila[2] $fila[3] $fila[4])</font>");
    }
    print("</td>");

      $idreg=0;
      $incidencia=-1;
      $memo="";
      $dia=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);
      $consulta1="SELECT id, incidencia, memo FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='".$fila[0]."' AND data='".$dia."' AND hora='".$hora."'";
      $conjunt_resultant1=mysql_query($consulta1, $connect);
      $nfiles=mysql_num_rows($conjunt_resultant1);
      if ($nfiles==1) {
       $fila1=mysql_fetch_row($conjunt_resultant1);
       $idreg=$fila1[0];
       $incidencia=$fila1[1];
       $memo=$fila1[2];
      }
      mysql_free_result($conjunt_resultant1);
      print("<td align='center'>");
      $permis=privilegis($nomDiaSem[date('w',$datatimestamp)], $hora,(($grup!='')?$grup:$subgrup));
      if($grup!='') $consulta="SELECT data FROM $bdtutoria.$tbl_prefix"."databloqueig WHERE grup='$grup' LIMIT 1";
      else $consulta="SELECT data FROM $bdtutoria.$tbl_prefix"."databloqueig WHERE grup='$fila[2] $fila[3] $fila[4]' LIMIT 1";
      $conjunt_resultant1=mysql_query($consulta, $connect);
      if(0!=mysql_num_rows($conjunt_resultant1)) {
        $fila1=mysql_fetch_row($conjunt_resultant1);
	$datatimestampbloqueig=$fila1[0];
      }
      else $datatimestampbloqueig=0;
      mysql_free_result($conjunt_resultant1);
      $datatimestampactual=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);
      if(($incidencia=='FJ')||($incidencia=='RJ')||($datatimestampactual<=$datatimestampbloqueig)) $permis=false;
      if($permis) {
         print("<select name='inc_".$idreg."_".$fila[0]."_".$hora."_".$incidencia."'>");
         print("<option></option>");
         for($j=0; $j<count($incid); ++$j) {
           print("<option".(($incidencia==$incid[$j])?" selected":"").">".$incid[$j]."</option>");
         }
         print("</select>");
      }
      else {
         if($incidencia==-1) $incidencia='';
         print("$incidencia");
      }
      print("<br>");
      $sum=0;
      for($j=0;$j<strlen($memo);++$j) {
        $sum+=(ord(substr($memo,$j,1))*($j+1));
      }
      print("<input type='hidden' name='t_".$idreg."_".$fila[0]."_".$hora."_".$sum."' value='".$memo."'>");
      if($permis) print("<a href='' onClick='javascript:var pr=prompt(\"Introdueix el text explicatiu:\",unescape(document.introd1.t_".$idreg."_".$fila[0]."_".$hora."_".$sum.".value)); if(pr!=null) document.introd1.t_".$idreg."_".$fila[0]."_".$hora."_".$sum.".value=escape(pr); return false;'>".(($memo!="")?"<b>":"")."T".(($memo!="")?"</b>":"")."</a>");
      else print("<a href='' onClick='alert(unescape(document.introd1.t_".$idreg."_".$fila[0]."_".$hora."_".$sum.".value)); return false;'>".(($memo!="")?"<b>":"")."T".(($memo!="")?"</b>":"")."</a>");
      print("</td>");
    print("</tr>");
  }
  mysql_free_result($conjunt_resultant);
  print("</table>");
  if($nregs!=0) print("&nbsp;<input type='submit' value='Gravar'>&nbsp; ".$paginador);
  print("</form><hr>");
}
?>
</body>
</html>
