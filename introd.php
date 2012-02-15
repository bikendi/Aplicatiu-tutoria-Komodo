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
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" type="text/css" href="css/introd.css" />
<title>Tutoria</title>
<?
@include("linkbd.inc.php");
@include("comu.php");
@include("comu.js.php");
panyacces("Privilegis");
$maxpaginador=7;
if(isset($tots)) $maxpaginador=10000;
if(isset($data) and (isset($grup) or isset($subgrup))) {
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
/*	  echo "<p> key: $key </p>\n";
	  echo "<p> aux: $aux </p>\n";
	  echo "<p> t...: $t_0_22179486142_3_0 </p>\n";
	  echo "<p> mq_gpc: ". get_magic_quotes_gpc()."</p>\n";
	  echo "<p> memo: ".$memo."</p>\n";
	  echo "<p> memo strip: ".stripslashes($memo)."</p>\n";*/
	  $dia=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);
	  //get_magic_quotes_gpc()?stripslashes($memo):$memo
	  $consulta="insert into $bdtutoria.$tbl_prefix"."faltes SET refalumne='".$noms[2]."', data='".$dia."', hora='".$noms[3]."', incidencia='".current($HTTP_POST_VARS)."', usuari='$sess_user', memo='".mysql_real_escape_string(stripslashes($memo))."'";
// 	  echo "<p> Consulta: $consulta </p> \n";
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
	// get_magic_quotes_gpc()?stripslashes($memo):$memo
        $consulta="update $bdtutoria.$tbl_prefix"."faltes SET memo='".mysql_real_escape_string(stripslashes($memo))."' WHERE id='".$noms[1]."' LIMIT 1";
// 	echo "<p> Consulta: $consulta </p> \n";
	mysql_query($consulta, $connect);
      }
    }
    next($HTTP_POST_VARS);
  }
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
}

if(isset($inforincid)&&$inforincid!='') {
  print("<script language='JavaScript'>location.href='nou_informe_incid.php?nalumne=$inforincid&idsess=$idsess';</script>");
  exit;
}
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
}

</script>

<link type="text/css" href="jQuery-UI/css/ui-lightness/jquery-ui.custom.css" rel="Stylesheet" />	
<script type="text/javascript" src="jQuery-UI/js/jquery.min.js"></script>
<script type="text/javascript" src="jQuery-UI/js/jquery-ui.custom.min.js"></script>
<script type='text/javascript'>
$().ready(function() {
	$(".anotacions").autocomplete({
		source: "anotacions.php"
	});
})
</script>


</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">

<?
print("
<div align='right'>
<table border='0'>
<tr><td rowspan='2'><font size='6'>Registre d'incid&egrave;ncies&nbsp; &nbsp; </font></td>
<td><b>Data:</a></td><td><b>");
if ( (isset($grup)&&$grup=='Subgrups') || (isset($subgrup)&&$subgrup!='Grups')) {
 print("Subgrup:");
}
else {
print("Grup:");
}
print("</b></td><td>&nbsp;</td></tr>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>
<tr><td><input type='text' name='data' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."' onClick='blur(); obreCalendari(0,0,0);'> <input type='hidden' name='datan' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."' onChange='document.introd1.submit();'></td>
<td>");
if ( (isset($grup)&&$grup=='Subgrups') || (isset($subgrup)&&$subgrup!='Grups')) {
   print("<select name='subgrup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
   <option></option><option>Grups</option>");
   do {
     $permis=privilegis('X', 'X',current($llista_subgrups));
     if($permis) print("<option".((stripslashes($subgrup)==rawurldecode(current($llista_subgrups)))?" selected":"").">".rawurldecode(current($llista_subgrups))."</option>");
   } while(next($llista_subgrups));
   if($grup=='Subgrups') $grup='';
}
else {
   print("<select name='grup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
   <option></option><option>Subgrups</option>");
   do {print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");} while(next($llista_grups));
   if($subgrup=='Grups') $subgrup='';
}
print("</select></td>");
print("<td>&nbsp; &nbsp; &nbsp; </td></tr>
</table>
</div><hr>
");
if ($grup!="" || $subgrup!="") {

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
  $paginador.= "&nbsp; &nbsp; Alumnes ".(($nregs!=0)?($paginadoractual+1):0)." - ".((($paginadoractual+$maxpaginador)<=$nregs)?($paginadoractual+$maxpaginador):$nregs)."&nbsp; &nbsp; ";
  $paginador.= ($paginadorendavant)?"<a href='' onClick='document.introd1.paginadoranterior.value=\"-1\"; document.introd1.paginadorseguent.value=\"".($paginadoractual+$maxpaginador)."\"; document.introd1.submit(); return false;'>":"";
  $paginador.= ">";
  $paginador.= ($paginadorendavant)?"</a>":"";
  $paginador.= "&nbsp; ";
  $paginadorsup=(($maxpaginador*(floor($nregs/$maxpaginador))));
  if($paginadorsup==$nregs) $paginadorsup=$nregs-1;
  if(($nregs%$maxpaginador)==0) $paginadorsup=$nregs-$maxpaginador;
  $paginador.= ($paginadorendavant)?"<a href='' onClick='document.introd1.paginadoranterior.value=\"".$paginadorsup."\"; document.introd1.submit(); return false;'>":"";
  $paginador.= ">>";
  $paginador.= ($paginadorendavant)?"</a>":"";
  $paginador.= "&nbsp; de $nregs";
  if($nregs!=0) print("<input type='submit' value='Gravar'>&nbsp; $paginador (<input type='checkbox' name='tots'".((isset($tots))?" checked":"")." onClick='document.introd1.submit();'> Tots)");
  else print("Aquest subgrup no t&eacute; alumnes.");
  if($grup!='') {
    $gru=split(' ',$grup);
    $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' ORDER BY cognom_alu, cognom2_al ASC LIMIT $paginadoractual,$maxpaginador";
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
  print("<input type='hidden' name='inforincid' value=''>");
  print("<table border='0'>");
  $consulta1="SELECT hora, inici, fi FROM $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
  $conjunt_resultant1=mysql_query($consulta1, $connect);
  $nhores=mysql_num_rows($conjunt_resultant1);
  $capcal="<tr bgcolor='#0088cc'><td>&nbsp;</td><td align='right'><b>Hora:</b></td>";
  while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    $capcal .= "<td align='center' width='38' title='".date('H',$fila1[1]).":".date('i',$fila1[1])." - ".date('H',$fila1[2]).":".date('i',$fila1[2])."'><b>".$fila1[0]."</b></td>";
    $hores[]=$fila1[0];
  }
  $capcal .="</tr>";
  mysql_free_result($conjunt_resultant1);
  $incid=split(',', $ref_incidencia);
  $compt_capcal=0;
  while ($fila=mysql_fetch_row($conjunt_resultant)) {
    if($compt_capcal%5==0) print($capcal);
    ++$compt_capcal;
    if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
    else $foto = "./imatges/fot0.jpg";
    $linkfil="<a href='' onClick='obreFoto(\"$foto\", \"$fila[1]\"); return false;'><img src='$foto' width='25' height='34' border='0'></a>";
    print("<tr bgcolor='#aacccc'><td>".$linkfil."</td><td><a href='' title=\"Afegir informe d'incid&egrave;ncia\" onClick='document.forms.introd1.inforincid.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'> &nbsp;<b>I</b>&nbsp;</a> ".$fila[1]);
    if($subgrup!='') {
     print(" <font size='-2'>($fila[2] $fila[3] $fila[4])</font>");
    }
    print("</td>");
    for($i=0; $i<$nhores; ++$i) {
      $idreg=0;
      $incidencia=-1;
      $memo="";
      $dia=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);
      $consulta1="SELECT id, incidencia, memo FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='".$fila[0]."' AND data='".$dia."' AND hora='".$hores[$i]."'";
      $conjunt_resultant1=mysql_query($consulta1, $connect);
      $nfiles=mysql_num_rows($conjunt_resultant1);
      if ($nfiles==1) {
       $fila1=mysql_fetch_row($conjunt_resultant1);
       $idreg=$fila1[0];
       $incidencia=$fila1[1];
       $memo=$fila1[2];
      }
      mysql_free_result($conjunt_resultant1);
      print("<td align='center'>\n");
      
      
       $marchorari=false;      
        $consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."marcshoraris WHERE curs='$fila[2]' and (grup='$fila[3]' or grup='*') and etapa='$fila[4]'";
        $conjunt_resultant1=mysql_query($consulta1, $connect);
        if(0!=mysql_result($conjunt_resultant1, 0,0)) {
          mysql_free_result($conjunt_resultant1); 
          $consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."marcshoraris WHERE curs='$fila[2]' and (grup='$fila[3]' or grup='*') and etapa='$fila[4]' and diasem='".$nomDiaSem[date('w',$datatimestamp)]."' and hora='$hores[$i]' LIMIT 1";
          $conjunt_resultant1=mysql_query($consulta1, $connect);
	  if(mysql_result($conjunt_resultant1, 0,0)!=0) $marchorari=true;
        }
        else $marchorari=true;
        mysql_free_result($conjunt_resultant1);
      
      
      $permis=privilegis($nomDiaSem[date('w',$datatimestamp)], $hores[$i],(($grup!='')?$grup:$subgrup));
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

      $sum=0;
      for($j=0;$j<strlen($memo);++$j) {
        $sum+=(ord(substr($memo,$j,1))*($j+1));
      }

      if ($permis && $marchorari) {
        print("<select name='inc_".$idreg."_".$fila[0]."_".$hores[$i]."_".$incidencia."' onChange='if( this.value ) getElementById(\"t_".$idreg."_".$fila[0]."_".$hores[$i]."_".$sum."\").style.display=\"inline\"; else getElementById(\"t_".$idreg."_".$fila[0]."_".$hores[$i]."_".$sum."\").style.display=\"none\";'> \n");
        print("<option></option>\n");
        for($j=0; $j<count($incid); ++$j) {
          print("<option".(($incidencia==$incid[$j])?" selected":"").">".$incid[$j]."</option>\n");
        }
        print("</select>\n");
      }
      else {
         if($incidencia==-1) $incidencia='';
         if ($marchorari) print("$incidencia");
	 else print("&nbsp;");
      }
      
      print("<br>");
      if( $marchorari ) {
	if( !$permis ) {
	  $disabled = "disabled='disabled'";
	} else {
	  $disabled = "";
	}
	if( (!$memo && (!$incidencia || $incidencia==-1)) || (!$memo && !$permis) ) {
	    $hidden ="style='display: none'";
	} else {
/*	    echo "<p> inc: $incidencia </p>\n";
	    echo "<p> memo: $memo </p>\n";*/
	    $hidden ="";
	}
	
	print("<input type='text' class='anotacions' $disabled $hidden name='t_".$idreg."_".$fila[0]."_".$hores[$i]."_".$sum."' id='t_".$idreg."_".$fila[0]."_".$hores[$i]."_".$sum."' title=\"".htmlspecialchars($memo)."\" value=\"".htmlspecialchars($memo)."\" >");
      } else
	print("&nbsp;");
	
/*      print("<input type='hidden' name='t_".$idreg."_".$fila[0]."_".$hores[$i]."_".$sum."' value='".$memo."'>");
      if($permis && $marchorari) print("<a href='' title='Text explicatiu' onClick='javascript:var pr=prompt(\"Introdueix el text explicatiu:\",unescape(document.introd1.t_".$idreg."_".$fila[0]."_".$hores[$i]."_".$sum.".value)); if(pr!=null) document.introd1.t_".$idreg."_".$fila[0]."_".$hores[$i]."_".$sum.".value=escape(pr); return false;'>".(($memo!="")?"<b>":"")."T".(($memo!="")?"</b>":"")."</a>");
      else if($marchorari) print("<a href='' title='Text explicatiu' onClick='alert(unescape(document.introd1.t_".$idreg."_".$fila[0]."_".$hores[$i]."_".$sum.".value)); return false;'>".(($memo!="")?"<b>":"")."T".(($memo!="")?"</b>":"")."</a>");
      else print("&nbsp;");*/
      print("</td>");
      
    }
    print("</tr>");
  }
  mysql_free_result($conjunt_resultant);
  print("</table>");
  if($nregs!=0) print("<input type='submit' value='Gravar'>&nbsp; ".$paginador);
  print("</form><hr>");
}
?>
</body>
</html>
