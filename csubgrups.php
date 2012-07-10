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
panyacces("Administrador");
$maxpaginador=8;
if(isset($tots)) $maxpaginador=10000;
$maxpaginadorsubgr=8;
if(isset($totssubgr)) $maxpaginadorsubgr=10000;

if(isset($subgrup)&&($subgrup!='')&&isset($esborrar)&&($esborrar!='')) {
  $subgru=preg_split('/ /',$subgrup);
  $consulta="SELECT alumnes FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='$subgru[0]' limit 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $alssubgrup=preg_split('/,/',mysql_result($conjunt_resultant, 0,0));
  mysql_free_result($conjunt_resultant);
  $nouconjunt='';
  foreach($alssubgrup as $p) {
    if (($nouconjunt!='')&&($p!=$esborrar)) $nouconjunt .=',';
    if ($p!=$esborrar) $nouconjunt .= $p; 
  }
  $consulta="UPDATE $bdtutoria.$tbl_prefix"."subgrups SET alumnes='$nouconjunt' WHERE ref_subgrup='$subgru[0]' limit 1";
  mysql_query($consulta, $connect);
}

if(isset($subgrup)&&($subgrup!='')&&isset($afegir)&&($afegir!='')) {
  $subgru=preg_split('/ /',$subgrup);
  $consulta="SELECT alumnes FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='$subgru[0]' limit 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $alssubgrup=preg_split('/,/',mysql_result($conjunt_resultant, 0,0));
  mysql_free_result($conjunt_resultant);
  $nouconjunt='';
  foreach($alssubgrup as $p) {
    if ($nouconjunt!='') $nouconjunt .=',';
    $nouconjunt .= $p;
  }
  if ($nouconjunt!='') $nouconjunt .=',';
  $nouconjunt.=$afegir;
  $consulta="UPDATE $bdtutoria.$tbl_prefix"."subgrups SET alumnes='$nouconjunt' WHERE ref_subgrup='$subgru[0]' limit 1";
  mysql_query($consulta, $connect);
}

if(isset($subgrup)&&($subgrup!='')&&isset($esborrarsubgrup)&&($esborrarsubgrup!='')) {
  $subgru=rawurlencode(stripslashes($subgrup));
  $consulta="SELECT id, grup FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE grup like '%$subgru%'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
      $tu=false;
      if('tutor_'==substr($fila[1],0,6)) {
        $tu=true;
	$cad=substr($fila[1],6);
      }
      else $cad=$fila[1];
      if($cad==$subgru) {
        $consulta1="DELETE FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE id='$fila[0]' LIMIT 1";
        mysql_query($consulta1, $connect);
      }
      else {
       $ca=explode('|',$cad);
       $noucad='';
       for($i=0; $i<count($ca); ++$i) {
         if($ca[$i]!=$subgru) {
           if($noucad!='') $noucad.='|';
           $noucad.=$ca[$i];
	 }
       }
       if($tu) $noucad = 'tutor_'.$noucad;
       $consulta1="UPDATE $bdtutoria.$tbl_prefix"."horariprofs SET grup='$noucad' WHERE id='$fila[0]' LIMIT 1";
       mysql_query($consulta1, $connect);
      }
  }
  mysql_free_result($conjunt_resultant);
    
  $subgru=preg_split('/ /',$subgrup);
  $consulta="DELETE FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='$subgru[0]' limit 1";
  mysql_query($consulta, $connect);
  $subgrup='';


  unset($llista_subgrups);
  $consulta="SELECT  ref_subgrup, nom FROM $bdtutoria.$tbl_prefix"."subgrups ORDER  BY nom, ref_subgrup";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
    $llista_subgrups[]="$fila[0] $fila[1]";
  }
  mysql_free_result($conjunt_resultant);

	//borrem les relacions
	$query = "DELETE FROM $bdtutoria.$tbl_prefix"."rel_subgrups WHERE ref_subgrup='$subgru[0]'";
	if( !mysql_query($query, $connect) ) {
		echo "<p> Query: $query </p> \n";
		print("<br>Error: ". mysql_error() .".<br>");
	}
}
if(isset($esborratotsals) && $esborratotsals=='si') {
	$subgru=preg_split('/ /',$subgrup);
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."subgrups SET alumnes='' WHERE ref_subgrup='$subgru[0]' limit 1";
  	mysql_query($consulta, $connect);	
}

if(isset($refnousubgrup)&&($refnousubgrup!='')&&isset($nomnousubgrup)&&($nomnousubgrup!='')) {

   $consulta="SELECT right(ref_subgrup,length(ref_subgrup)-locate('-',ref_subgrup)) as idx FROM $bdtutoria.$tbl_prefix"."subgrups WHERE left(ref_subgrup,locate('-',ref_subgrup)-1)='$refnousubgrup' ORDER BY idx";
   $conjunt_resultant=mysql_query($consulta, $connect);
   $i=1;
   while($fila=mysql_fetch_row($conjunt_resultant)) {
     if($i==$fila[0]) {
       ++$i;
       continue;
     }
     else break;
   }
   mysql_free_result($conjunt_resultant);
   $refnousubgrup .= "-$i";

   
   $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."subgrups SET ref_subgrup='$refnousubgrup', nom='$nomnousubgrup'";
   mysql_query($consulta, $connect);
   print("<script language='JavaScript'>");
   print("opener.location.href='$PHP_SELF?idsess=$idsess&subgrup=$refnousubgrup+$nomnousubgrup';");
   print("window.close();");
   print("</script>");

}

?>

<script language='JavaScript'>
function nouSubgrup()
{
 var finestra;
 window.focus();
 opt = "resizable=0,scrollbars=0,width=300,height=165,left=5,top=60";
 finestra=window.open("", "finestra", opt);
 with (finestra.document) {
  write("<html><head><title>Tutoria</title>");
  write("<sc"+"ript language='JavaScript'>");
  write("function valida() {");
  write(" document.nou.refnousubgrup.value=(document.nou.refnousubgrup.value).replace(/^ +| +$/g,'');");  
  write(" if(document.nou.refnousubgrup.value!= (document.nou.refnousubgrup.value).match(/[A-Za-z0-9]+/gi)) {");
  write("  alert('La referencia solament pot tenir lletres i numeros (A-Z a-z i 0-9), i no pot estar buit');");
  write("  return false;");
  write(" }");
  write(" document.nou.nomnousubgrup.value=(document.nou.nomnousubgrup.value).replace(/^ +| +$/g,'');");
  write(" if(document.nou.nomnousubgrup.value== '') {");
  write("  alert('El nom del subgrup no pot estar buit');");
  write("  return false;");
  write(" }");
  write(" document.nou.nomnousubgrup.value= escape(document.nou.nomnousubgrup.value);");
  write(" return true;");  
  write("} ");
  write("</sc"+"ript>");
  write("</head>");
  write("<body bgcolor='#c0c0c0'>");
  write("<form name='nou' action='<?print("$PHP_SELF?idsess=$idsess");?>' method='post' onSubmit='return valida();'>");
  write("Refer&egrave;ncia del nou subgrup:<br>");
  write("<input type='text' name='refnousubgrup' size='5' maxlength='5' value=''><br>");
  write("Nom del nou subgrup:<br>");
  write("<input type='text' name='nomnousubgrup' value=''><br>");
  write("<center><input type='submit' value='Crear subgrup'></center>");
  write("</form>");
  write("</body></html>");
  close();
 }
 finestra.focus();
}
</script>

</head>
<body bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table border='0' width='100%'>
<tr><td align='right'>
<font size='6'>Gesti&oacute; de subgrups&nbsp; &nbsp; </font>
<hr></td></tr>
</table>

<form name='introd1' method='post' action='<?print("$PHP_SELF?idsess=$idsess");?>'>  
<table border='0' width='100%'>
<tr>
<td width='50%' valign='top'>
<b>Subgrups:</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<a href='' title='Crea un nou subgrup.' onClick='nouSubgrup(); return false;'>Nou subgrup</a>
&nbsp; &nbsp;
<?
if ($subgrup!='') {
$subgru=preg_split('/ /',$subgrup);
print(" 
 <a href='' title='Elimina el subgrup seleccionat.' onClick='if(!confirm(\"Segur que vols eliminar tot aquest subgrup?\\nSi l esborres, tambe s esborrara en els horaris de professor!\")) return false; document.introd1.esborrarsubgrup.value=\"$subgru[0]\"; document.introd1.submit(); return false;'>Esborrar subgrup</a>
");
}
else print("Esborrar subgrup");
?>
<br>
<select name='subgrup' onChange='if(document.introd1.paginadoranteriorsubgr) document.introd1.paginadoranteriorsubgr.value="-1"; if(document.introd1.paginadorseguentsubgr) document.introd1.paginadorseguentsubgr.value="-1"; document.introd1.submit();'>
<option></option>
<?do {print("<option".((stripslashes($subgrup)==rawurldecode(current($llista_subgrups)))?" selected":"").">".rawurldecode(current($llista_subgrups))."</option>");} while(next($llista_subgrups));?>
</select><br> 

<?
if ($subgrup!='') {
  print("<input type='hidden' name='esborrarsubgrup' value=''>");
  print("<input type='hidden' name='afegirsubgrup' value=''>");
  print("<input type='hidden' name='esborrar' value=''>");
  print("<input type='hidden' name='afegir' value=''>");
  $subgru=preg_split('/ /',$subgrup);

  $paginadoractualsubgr=0;
  if(!isset($paginadoranteriorsubgr)) $paginadoranteriorsubgr=-1;
  if(!isset($paginadorseguentsubgr)) $paginadorseguentsubgr=-1;
  print("<input type='hidden' name='paginadorseguentsubgr' value='$paginadorseguentsubgr'>");
  print("<input type='hidden' name='paginadoranteriorsubgr' value='$paginadoranteriorsubgr'>");
  $consulta="SELECT alumnes FROM $bdtutoria.$tbl_prefix"."subgrups WHERE ref_subgrup='$subgru[0]' limit 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $alssubgrup=preg_split('/,/',mysql_result($conjunt_resultant, 0,0));
  if(''==mysql_result($conjunt_resultant, 0,0)) $nalumnessubgr=0; 
  else $nalumnessubgr=count($alssubgrup);
  mysql_free_result($conjunt_resultant);
  if($nalumnessubgr>$maxpaginadorsubgr) {
   if($paginadorseguentsubgr!=-1) {
     $paginadoractualsubgr=$paginadorseguentsubgr;
   }
   if($paginadoranteriorsubgr!=-1) {
     $paginadoractualsubgr=$paginadoranteriorsubgr;
   }
  }
  if(($paginadoractualsubgr-$maxpaginadorsubgr) >= 0) $paginadorenreresubgr=true; else $paginadorenreresubgr=false;
  if(($paginadoractualsubgr+$maxpaginadorsubgr) < $nalumnessubgr) $paginadorendavantsubgr=true; else $paginadorendavantsubgr=false;
  $paginadorsubgr = ($paginadorenreresubgr)?"<a href='' onClick='document.introd1.paginadorseguentsubgr.value=\"-1\"; document.introd1.paginadoranteriorsubgr.value=\"0\"; document.introd1.submit(); return false;'>":"";
  $paginadorsubgr.= "<<";
  $paginadorsubgr.= ($paginadorenreresubgr)?"</a>":"";
  $paginadorsubgr.= "&nbsp; ";
  $paginadorsubgr.= ($paginadorenreresubgr)?"<a href='' onClick='document.introd1.paginadorseguentsubgr.value=\"-1\"; document.introd1.paginadoranteriorsubgr.value=\"".($paginadoractualsubgr-$maxpaginadorsubgr)."\"; document.introd1.submit(); return false;'>":"";
  $paginadorsubgr.= "<";
  $paginadorsubgr.= ($paginadorenreresubgr)?"</a>":"";
  $paginadorsubgr.= "&nbsp; &nbsp; Alumnes ".(($nalumnessubgr!=0)?($paginadoractualsubgr+1):0)." - ".((($paginadoractualsubgr+$maxpaginadorsubgr)<=$nalumnessubgr)?($paginadoractualsubgr+$maxpaginadorsubgr):$nalumnessubgr)."&nbsp; &nbsp; ";
  $paginadorsubgr.= ($paginadorendavantsubgr)?"<a href='' onClick='document.introd1.paginadoranteriorsubgr.value=\"-1\"; document.introd1.paginadorseguentsubgr.value=\"".($paginadoractualsubgr+$maxpaginadorsubgr)."\"; document.introd1.submit(); return false;'>":"";
  $paginadorsubgr.= ">";
  $paginadorsubgr.= ($paginadorendavantsubgr)?"</a>":"";
  $paginadorsubgr.= "&nbsp; ";
  $paginadorsupsubgr=(($maxpaginadorsubgr*(floor($nalumnessubgr/$maxpaginadorsubgr))));
  if($paginadorsupsubgr==$nalumnessubgr) $paginadorsupsubgr=$nalumnessubgr-1;
  if(($nalumnessubgr%$maxpaginadorsubgr)==0) $paginadorsupsubgr=$nalumnessubgr-$maxpaginadorsubgr;
  $paginadorsubgr.= ($paginadorendavantsubgr)?"<a href='' onClick='document.introd1.paginadoranteriorsubgr.value=\"-1\"; document.introd1.paginadorseguentsubgr.value=\"".$paginadorsupsubgr."\"; document.introd1.submit(); return false;'>":"";
  $paginadorsubgr.= ">>";
  $paginadorsubgr.= ($paginadorendavantsubgr)?"</a>":"";
  $paginadorsubgr.= "&nbsp; de $nalumnessubgr";

  print("$paginadorsubgr (<input type='checkbox' name='totssubgr'".((isset($totssubgr))?" checked":"")." onClick='document.introd1.submit();'> Tots)<hr>");
  if($nalumnessubgr==0) print("Aquest subgrup no t&eacute; alumnes.");
  else {  
    $consulta ="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi ";
    $consulta.="FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE ";
    $cons='';
    foreach($alssubgrup as $nal) {
      if ($cons!='') $cons.='or ';
      $cons.="numero_mat='$nal' ";
    }
    $consulta.= $cons;
    $consulta.="ORDER BY cognom_alu, cognom2_al ASC LIMIT $paginadoractualsubgr,$maxpaginadorsubgr";
    $conjunt_resultant=mysql_query($consulta, $connect);
    print("<table border='0'>");
      while ($fila=mysql_fetch_row($conjunt_resultant)) {
        if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
        else $foto = "./imatges/fot0.jpg";
        $linkfil="<a href='' onClick='obreFoto(\"$foto\", \"$fila[1]\"); return false;'><img src='$foto' width='25' height='34' border='0'></a>";
        print("<tr bgcolor='#aacccc'><td>$linkfil</td><td><a href='' title='Clica per esborrar-lo del subgrup.' onClick='document.introd1.esborrar.value=\"$fila[0]\"; document.introd1.submit(); return false;'>$fila[1]</a></td><td><font size=-2>($fila[2] $fila[3] $fila[4])</font></td></tr>");
      }
    mysql_free_result($conjunt_resultant);
    print("</table>");
  }
  print("<hr>$paginadorsubgr");
}
?>

</td>
<td width='1%' valign='top' bgcolor='#cccccc'>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; 
<a href='' title='Clica per afegir-los tots.' onClick='return false;'><<</a><!--No implementat-->
&nbsp; &nbsp;
<a href='' title='Clica per eliminar-los tots.' onClick='document.forms.introd1.esborratotsals.value="si"; document.forms.introd1.submit(); return false;'>>></a>
&nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<input type='hidden' name='esborratotsals' value=''>
<input type='hidden' name='afegirtotsals' value=''> 
</td>
<td width='49%' valign='top'>
<b>Llistes de grups:</b><br>
<select name='grup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value="-1"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value="-1"; document.introd1.submit();'>
<option></option>
<?do {print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");} while(next($llista_grups));?>
</select><br>
<?
if ($grup!='') {
  $grup=preg_split('/ /',$grup);

  $paginadoractual=0;
  if(!isset($paginadoranterior)) $paginadoranterior=-1;
  if(!isset($paginadorseguent)) $paginadorseguent=-1;
  print("<input type='hidden' name='paginadorseguent' value='$paginadorseguent'>");
  print("<input type='hidden' name='paginadoranterior' value='$paginadoranterior'>");
  $consulta="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$grup[0]."' AND grup='".$grup[1]."' AND pla_estudi='".$grup[2]."'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $nregs=mysql_result($conjunt_resultant, 0,0);
  mysql_free_result($conjunt_resultant);
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
  $paginador.= "&nbsp; &nbsp; Alumnes ".($paginadoractual+1)." - ".((($paginadoractual+$maxpaginador)<=$nregs)?($paginadoractual+$maxpaginador):$nregs)."&nbsp; &nbsp; ";
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
  print("$paginador (<input type='checkbox' name='tots'".((isset($tots))?" checked":"")." onClick='document.introd1.submit();'> Tots)");

  $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$grup[0]."' AND grup='".$grup[1]."' AND pla_estudi='".$grup[2]."' ORDER BY cognom_alu, cognom2_al ASC LIMIT $paginadoractual,$maxpaginador";
  $conjunt_resultant=mysql_query($consulta, $connect);
  print("<hr><table border='0'>");
  
  while ($fila=mysql_fetch_row($conjunt_resultant)) {
    if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
    else $foto = "./imatges/fot0.jpg";
    $linkfil="<a href='' onClick='obreFoto(\"$foto\", \"$fila[1]\"); return false;'><img src='$foto' width='25' height='34' border='0'></a>";
    $afg1='';
    $afg2='';
    if (isset($subgrup)&&$subgrup!='') {
      $nohies=true;
      foreach($alssubgrup as $nal) if ($nal==$fila[0]) $nohies=false;
      if($nohies) {
          $afg1="<a href='' title='Clica per afegir-lo al subgrup.' onClick='document.introd1.afegir.value=\"".$fila[0]."\"; document.introd1.submit(); return false;'>";
          $afg2="</a>";
      }
    }
    print("<tr bgcolor='#aacccc'><td>".$linkfil."</td><td>".$afg1.$fila[1].$afg2."</td></tr>");
  }
  mysql_free_result($conjunt_resultant);
  print("</table><hr>");
  print($paginador);
}
?>
</td>
</tr>
</table>
</form>


</body>
</html>
