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
?>
<html>
<head>
<title>Tutoria</title>

<?php
@include("comu.php");
@include("comu.js.php");
panyacces("Privilegis");
?>

<?php
if (isset($selalum)&&$selalum!='') {
  $maxpaginadordre=8;
  $maxpaginadoresq=8;
  if(isset($totsdre)) $maxpaginadordre=10000;
  if(isset($totsesq)) $maxpaginadoresq=10000;

   if(isset($esborrartots)&&($esborrartots!='')) {
     $consulta="UPDATE $bdtutoria.$tbl_prefix"."informelliure SET ref_alum='' WHERE id='$selalum' limit 1";
     mysql_query($consulta, $connect);   
   }
   if(isset($afegirtots)&&($afegirtots!='')) {
     $consulta="SELECT ref_alum FROM $bdtutoria.$tbl_prefix"."informelliure WHERE id='$selalum' limit 1";
     $conjunt_resultant=mysql_query($consulta, $connect);
     $llistaals=mysql_result($conjunt_resultant, 0,0);
     mysql_free_result($conjunt_resultant);
     if ($llistaals!='') $llistaals .=',' . $afegirtots;
     else $llistaals=$afegirtots;
     $consulta="UPDATE $bdtutoria.$tbl_prefix"."informelliure SET ref_alum='$llistaals' WHERE id='$selalum' limit 1";
     mysql_query($consulta, $connect);
   }
   if(isset($esborrarselec)&&($esborrarselec!='')) {
     $consulta="SELECT ref_alum FROM $bdtutoria.$tbl_prefix"."informelliure WHERE id='$selalum' limit 1";
     $conjunt_resultant=mysql_query($consulta, $connect);
     $alsselec=split(',',mysql_result($conjunt_resultant, 0,0));
     mysql_free_result($conjunt_resultant);
     $nouconjunt='';
     foreach($alsselec as $p) {
       if (($nouconjunt!='')&&($p!=$esborrarselec)) $nouconjunt .=',';
       if ($p!=$esborrarselec) $nouconjunt .= $p; 
     }
     $consulta="UPDATE $bdtutoria.$tbl_prefix"."informelliure SET ref_alum='$nouconjunt' WHERE id='$selalum' limit 1";
     mysql_query($consulta, $connect);
   }
   if(isset($afegirselec)&&($afegirselec!='')) {
     $consulta="SELECT ref_alum FROM $bdtutoria.$tbl_prefix"."informelliure WHERE id='$selalum' limit 1";
     $conjunt_resultant=mysql_query($consulta, $connect);
     $alsselec=split(',',mysql_result($conjunt_resultant, 0,0));
     mysql_free_result($conjunt_resultant);
     $nouconjunt='';
     foreach($alsselec as $p) {
       if ($nouconjunt!='') $nouconjunt .=',';
       $nouconjunt .= $p;
     }
     if ($nouconjunt!='') $nouconjunt .=',';
     $nouconjunt.=$afegirselec;
     $consulta="UPDATE $bdtutoria.$tbl_prefix"."informelliure SET ref_alum='$nouconjunt' WHERE id='$selalum' limit 1";
     mysql_query($consulta, $connect);
   }


print("
</head>
<body bgcolor='#ccdd88' text='#000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

<table border='0' width='100%'>
<tr>
<td><a href='' onClick='window.close();'>Tancar</a></td>
<td align='right'>
  <font size='5'>Circulars - Selecci&oacute; alumnes&nbsp; &nbsp; </font>
</td></tr>
</table>
<hr>
<form name='formselalum' method='post' action='$PHP_SELF?idsess=$idsess&selalum=$selalum'>  
<input type='hidden' name='esborrarselec' value=''>
<input type='hidden' name='afegirselec' value=''>
<script language='JavaScript'>var llistaafegirselec='';</script>
<input type='hidden' name='esborrartots' value=''>
<input type='hidden' name='afegirtots' value=''>
<table border='0' width='100%'>
<tr>
<td width='50%' valign='top'>
  <b>Seleccionats:</b><br><br>");
  $paginadoractualselec=0;
  if(!isset($paginadoranteriorselec)) $paginadoranteriorselec=-1;
  if(!isset($paginadorseguentselec)) $paginadorseguentselec=-1;
  print("<input type='hidden' name='paginadorseguentselec' value='$paginadorseguentselec'>");
  print("<input type='hidden' name='paginadoranteriorselec' value='$paginadoranteriorselec'>");
  $consulta="SELECT ref_alum FROM $bdtutoria.$tbl_prefix"."informelliure WHERE id='$selalum' limit 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $alsselec=split(',',mysql_result($conjunt_resultant, 0,0));
  if(''==mysql_result($conjunt_resultant, 0,0)) $nalumnesselec=0; 
  else $nalumnesselec=count($alsselec);
  mysql_free_result($conjunt_resultant);
  if($nalumnesselec>$maxpaginadoresq) {
   if($paginadorseguentselec!=-1) {
     $paginadoractualselec=$paginadorseguentselec;
   }
   if($paginadoranteriorselec!=-1) {
     $paginadoractualselec=$paginadoranteriorselec;
   }
  }
  if(($paginadoractualselec-$maxpaginadoresq) >= 0) $paginadorenrereselec=true; else $paginadorenrereselec=false;
  if(($paginadoractualselec+$maxpaginadoresq) < $nalumnesselec) $paginadorendavantselec=true; else $paginadorendavantselec=false;
  $paginadorselec = ($paginadorenrereselec)?"<a href='' onClick='document.formselalum.paginadorseguentselec.value=\"-1\"; document.formselalum.paginadoranteriorselec.value=\"0\"; document.formselalum.submit(); return false;'>":"";
  $paginadorselec.= "<<";
  $paginadorselec.= ($paginadorenrereselec)?"</a>":"";
  $paginadorselec.= "&nbsp; ";
  $paginadorselec.= ($paginadorenrereselec)?"<a href='' onClick='document.formselalum.paginadorseguentselec.value=\"-1\"; document.formselalum.paginadoranteriorselec.value=\"".($paginadoractualselec-$maxpaginadoresq)."\"; document.formselalum.submit(); return false;'>":"";
  $paginadorselec.= "<";
  $paginadorselec.= ($paginadorenrereselec)?"</a>":"";
  $paginadorselec.= "&nbsp; &nbsp; Alumnes ".(($nalumnesselec!=0)?($paginadoractualselec+1):0)." - ".((($paginadoractualselec+$maxpaginadoresq)<=$nalumnesselec)?($paginadoractualselec+$maxpaginadoresq):$nalumnesselec)."&nbsp; &nbsp; ";
  $paginadorselec.= ($paginadorendavantselec)?"<a href='' onClick='document.formselalum.paginadoranteriorselec.value=\"-1\"; document.formselalum.paginadorseguentselec.value=\"".($paginadoractualselec+$maxpaginadoresq)."\"; document.formselalum.submit(); return false;'>":"";
  $paginadorselec.= ">";
  $paginadorselec.= ($paginadorendavantselec)?"</a>":"";
  $paginadorselec.= "&nbsp; ";
  $paginadorsupselec=(($maxpaginadoresq*(floor($nalumnesselec/$maxpaginadoresq))));
  if($paginadorsupselec==$nalumnesselec) $paginadorsupselec=$nalumnesselec-1;
  if(($nalumnesselec%$maxpaginadoresq)==0) $paginadorsupselec=$nalumnesselec-$maxpaginadoresq;
  $paginadorselec.= ($paginadorendavantselec)?"<a href='' onClick='document.formselalum.paginadoranteriorselec.value=\"-1\"; document.formselalum.paginadorseguentselec.value=\"".$paginadorsupselec."\"; document.formselalum.submit(); return false;'>":"";
  $paginadorselec.= ">>";
  $paginadorselec.= ($paginadorendavantselec)?"</a>":"";
  $paginadorselec.= "&nbsp; de $nalumnesselec";
  if($nalumnesselec!=0) {
    if(!isset($ordesq)) $ordesq='ordesqcogn';
    print("$paginadorselec<br><input type='checkbox' name='totsesq'".((isset($totsesq))?" checked":"")." onClick='document.formselalum.submit();'>Tots");
    print(" &nbsp; &nbsp; &nbsp; Ordre:<input type='radio' name='ordesq' value='ordesqcogn' ".(($ordesq=='ordesqcogn')?"checked ":"")."onClick='document.forms.formselalum.submit();'>Cognom");
    print("<input type='radio' name='ordesq' value='ordesqcurs' ".(($ordesq=='ordesqcurs')?"checked ":"")."onClick='document.forms.formselalum.submit();'>Curs");   
  }
  print("<hr>");
  if($nalumnesselec==0) print("No hi ha cap alumne seleccionat.");
  else {  
    $consulta ="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi ";
    $consulta.="FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE ";
    $cons='';
    foreach($alsselec as $nal) {
      if ($cons!='') $cons.='or ';
      $cons.="numero_mat='$nal' ";
    }
    $consulta.= $cons;
    if($ordesq=='ordesqcogn') $consulta.="ORDER BY cognom_alu, cognom2_al ";
    if($ordesq=='ordesqcurs') $consulta.="ORDER BY pla_estudi DESC, curs, grup, cognom_alu, cognom2_al ";
    $consulta.="ASC LIMIT $paginadoractualselec,$maxpaginadoresq";
    $conjunt_resultant=mysql_query($consulta, $connect);
    print("<table border='0'>");
      while ($fila=mysql_fetch_row($conjunt_resultant)) {
        if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
        else $foto = "./imatges/fot0.jpg";
        $linkfil="<a href='' onClick='obreFoto(\"$foto\", \"$fila[1]\"); return false;'><img src='$foto' width='25' height='34' border='0'></a>";
        print("<tr bgcolor='#aacccc'><td>$linkfil</td><td><a href='' title='Clica per esborrar-lo de la selecci&oacute;.' onClick='document.formselalum.esborrarselec.value=\"$fila[0]\"; document.formselalum.submit(); return false;'>$fila[1]</a></td><td><font size=-2> ($fila[2] $fila[3] $fila[4])</font></td></tr>");
      }
    mysql_free_result($conjunt_resultant);
    print("</table>");
  }
  print("<hr>");
  if($nalumnesselec!=0) print("$paginadorselec");
  
print("
</td>

<td width='1%'  valign='top' bgcolor='#c0c0ff'>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<b><a href='' title='Seleccionar-los tots' onClick='document.formselalum.afegirtots.value=llistaafegirselec; document.formselalum.submit(); return false;'><<</a>
&nbsp; &nbsp; &nbsp; &nbsp;
<a href='' title='Esborrar-los tots' onClick='document.formselalum.esborrartots.value=\"1\"; document.formselalum.submit(); return false;'>>></a></b>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
</td>

<td width='49%' valign='top'>");
  if((!isset($subgrup)&&$grup!='Subgrups') || (isset($subgrup)&&$subgrup=='Grups')) {
    print("<b>Llistes de grups:</b><br>
    <select name='grup' onChange='if(document.formselalum.paginadoranterior) document.formselalum.paginadoranterior.value=\"-1\"; if(document.formselalum.paginadorseguent) document.formselalum.paginadorseguent.value=\"-1\"; document.formselalum.submit();'>
    <option></option>");
    $permis=privilegis('-', '-','-');
    if ($permis) {
    print("<option".(($grup=='Tots')?" selected":"").">Tots</option>
            <option>Subgrups</option>");
    }
    do {
      $permis=privilegis('-', '-',current($llista_grups));
      if ($permis) print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");
    } while(next($llista_grups));
    print("</select><br>");
    if($subgrup=='Grups') $subgrup='';
  }
  else {
    print("<b>Llistes de subgrups:</b><br>
    <select name='subgrup' onChange='if(document.formselalum.paginadoranterior) document.formselalum.paginadoranterior.value=\"-1\"; if(document.formselalum.paginadorseguent) document.formselalum.paginadorseguent.value=\"-1\"; document.formselalum.submit();'>
    <option></option>
    <option>Grups</option>");
    do {print("<option".((stripslashes($subgrup)==rawurldecode(current($llista_subgrups)))?" selected":"").">".rawurldecode(current($llista_subgrups))."</option>");} while(next($llista_subgrups));
    print("</select><br>");
    if($grup=='Subgrups') $grup='';
  }
    
  if ($grup!='' || $subgrup!='') {
  $gru=split(' ',$grup);
  $paginadoractual=0;
  if(!isset($paginadoranterior)) $paginadoranterior=-1;
  if(!isset($paginadorseguent)) $paginadorseguent=-1;
  print("<input type='hidden' name='paginadorseguent' value='$paginadorseguent'>");
  print("<input type='hidden' name='paginadoranterior' value='$paginadoranterior'>");
  if($grup!='') {
    if($grup!='Tots') $consulta="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."'";
    else $consulta="SELECT count(*) FROM $bdalumnes.$tbl_prefix"."Estudiants";
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
  if($nregs>$maxpaginadordre) {
   if($paginadorseguent!=-1) {
     $paginadoractual=$paginadorseguent;
   }
   if($paginadoranterior!=-1) {
     $paginadoractual=$paginadoranterior;
   }
  }
  if(($paginadoractual-$maxpaginadordre) >= 0) $paginadorenrere=true; else $paginadorenrere=false;
  if(($paginadoractual+$maxpaginadordre) < $nregs) $paginadorendavant=true; else $paginadorendavant=false;
  $paginador = ($paginadorenrere)?"<a href='' onClick='document.formselalum.paginadorseguent.value=\"-1\"; document.formselalum.paginadoranterior.value=\"0\"; document.formselalum.submit(); return false;'>":"";
  $paginador.= "<<";
  $paginador.= ($paginadorenrere)?"</a>":"";
  $paginador.= "&nbsp; ";
  $paginador.= ($paginadorenrere)?"<a href='' onClick='document.formselalum.paginadorseguent.value=\"-1\"; document.formselalum.paginadoranterior.value=\"".($paginadoractual-$maxpaginadordre)."\"; document.formselalum.submit(); return false;'>":"";
  $paginador.= "<";
  $paginador.= ($paginadorenrere)?"</a>":"";
  $paginador.= "&nbsp; &nbsp; Alumnes ".($paginadoractual+1)." - ".((($paginadoractual+$maxpaginadordre)<=$nregs)?($paginadoractual+$maxpaginadordre):$nregs)."&nbsp; &nbsp; ";
  $paginador.= ($paginadorendavant)?"<a href='' onClick='document.formselalum.paginadoranterior.value=\"-1\"; document.formselalum.paginadorseguent.value=\"".($paginadoractual+$maxpaginadordre)."\"; document.formselalum.submit(); return false;'>":"";
  $paginador.= ">";
  $paginador.= ($paginadorendavant)?"</a>":"";
  $paginador.= "&nbsp; ";
  $paginadorsup=(($maxpaginadordre*(floor($nregs/$maxpaginadordre))));
  if($paginadorsup==$nregs) $paginadorsup=$nregs-1;
  if(($nregs%$maxpaginadordre)==0) $paginadorsup=$nregs-$maxpaginadordre;
  $paginador.= ($paginadorendavant)?"<a href='' onClick='document.formselalum.paginadoranterior.value=\"-1\"; document.formselalum.paginadorseguent.value=\"".$paginadorsup."\"; document.formselalum.submit(); return false;'>":"";
  $paginador.= ">>";
  $paginador.= ($paginadorendavant)?"</a>":"";
  $paginador.= "&nbsp; de $nregs";
  if($nregs!=0) {
    if(!isset($orddre)) $orddre='orddrecogn';
    print($paginador."<br><input type='checkbox' name='totsdre'".((isset($totsdre))?" checked":"")." onClick='document.formselalum.submit();'>Tots");
    if(($grup!='' &&$grup=='Tots')||$subgrup!='') print(" &nbsp; &nbsp; &nbsp; Ordre:<input type='radio' name='orddre' value='orddrecogn' ".(($orddre=='orddrecogn')?"checked ":"")."onClick='document.forms.formselalum.submit();'>Cognom <input type='radio' name='orddre' value='orddrecurs' ".(($orddre=='orddrecurs')?"checked ":"")."onClick='document.forms.formselalum.submit();'>Curs");
    print("<hr>");
  }
  else print("<hr>Aquest subgrup no t&eacute; alumnes.");
  if($orddre=='orddrecurs') $ordrecons="ORDER BY pla_estudi DESC, curs, grup, cognom_alu, cognom2_al ";
  if($orddre=='orddrecogn') $ordrecons="ORDER BY cognom_alu, cognom2_al ASC ";
  if($grup!='') {
    $gru=split(' ',$grup);
    if($grup!='Tots') $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' $ordrecons LIMIT $paginadoractual,$maxpaginadordre";
    else $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants $ordrecons LIMIT $paginadoractual,$maxpaginadordre";
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
   $consulta.=$ordrecons;
   $consulta.="LIMIT $paginadoractual,$maxpaginadordre";
  }
  $conjunt_resultant=mysql_query($consulta, $connect);
  
  
  print("<table border='0'>");
  
  while ($fila=mysql_fetch_row($conjunt_resultant)) {
    if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
    else $foto = "./imatges/fot0.jpg";
    $linkfil="<a href='' onClick='obreFoto(\"$foto\", \"$fila[1]\"); return false;'><img src='$foto' width='25' height='34' border='0'></a>";
    $afg1='';
    $afg2='';

      $nohies=true;
      foreach($alsselec as $nal) if ($nal==$fila[0]) $nohies=false;
      if($nohies) {
          $afg1="<a href='' title='Clica per afegir-lo a la selecci&oacute;.' onClick='document.formselalum.afegirselec.value=\"".$fila[0]."\"; document.formselalum.submit(); return false;'>";
          $afg2="</a>";
	  if($llista=='') $llista=$fila[0];
          else $llista.= ','.$fila[0];
      }

    print("<tr bgcolor='#aacccc'><td>".$linkfil."</td><td>".$afg1.$fila[1].$afg2."</td>");
    if($subgrup!='' || $grup=='Tots') print("<td><font size=-2> ($fila[2] $fila[3] $fila[4])</font></td>");
    print("</tr>");
  }
  mysql_free_result($conjunt_resultant);
  print("</table><script language='JavaScript'>llistaafegirselec='".$llista."';</script><hr>");
  if($nregs!=0) print($paginador);
}

 print(" 
</td>
</tr>
</table>
</form>
</body>
</html>
 ");
exit;
}
?>

<script language='JavaScript'>
function obrefinestrainflliu(id) {
 window.focus();
 var opt = "status=0,resizable=1,scrollbars=1,width=600,height=400,left=5,top=60";
 var finestra=window.open("informelliure_pdf.php?idsess=<?=$idsess?>&id="+id, "finestra", opt);
}

function inflliureajuda() {
 window.focus();
 var finestraAjuda=window.open('','win1', 'width=380,height=450,top=20,left=20,resizable=1,scrollbars=1');
 with(finestraAjuda.document) {
   write('<html><head><title>Informe Lliure Ajuda</title></head>');
   write('<body bgcolor="#ccdd88"><center><b>Ajuda informe lliure</b></center><br>');
   write('- Camps de Dades sobre alumnes:<br>');
   write('<table style=" font-size: 10px; font-family: Verdana, sans-serif; background-color: #cbf0f5">');
   write('<tr><td><span title="Nom del pare"><b>@PARENOM</b></span></td><td><span title="Cognom 1 del pare"><b>@PARECOGNOM1</b></span></td><td><span title="Cognom 2 del pare"><b>@PARECOGNOM2</b></span></td><tr>');
   write('<tr><td><span title="Nom de la mare"><b>@MARENOM</b></span></td><td><span title="Cognom 1 de la mare"><b>@MARECOGNOM1</b></span></td><td><span title="Cognom 2 de la mare"><b>@MARECOGNOM2</b></span></td><tr>');
   write('<tr><td colspan="3"><span title="Adre&ccedil;a, carrer i n&uacute;mero."><b>@ADRECA</b></span></td><tr>');
   write('<tr><td><span title="Codi Postal"><b>@CODIPOSTAL</b></span></td><td colspan="2"><span title="Poblaci&oacute;"><b>@POBLACIO</b></span></td><tr>');
   write('<tr><td colspan="3"><span title="Tel&egrave;fon de l\'alumne."><b>@TELF</b></span></td><tr>');  
   write('<tr><td><span title="Nom de l\'alumne"><b>@ALUMNENOM</b></span></td><td><span title="Cognom 1 de l\'alumne"><b>@ALUMNECOGNOM1</b></span></td><td><span title="Cognom 2 de l\'alumne"><b>@ALUMNECOGNOM2</b></span></td><tr>');
   write('<tr><td><span title="Curs acad&egrave;mic"><b>@CURS</b></span></td><td><span title="Grup classe"><b>@GRUP</b></span></td><td><span title="Etapa educativa"><b>@ETAPA</b></span></td><tr>');
   write('<tr><td colspan="2"><span title="Text en mascul&iacute; o femen&iacute; segons el genere de l\'alumne."><b>@GENEREALUM(</b><i>text masc.</i><b>:</b><i>text fem.</i><b>)</b></span></td><td><span title="Nom del tutor de l\'alumne"><b>@TUTORGRUP</b></span></td><tr>');
   write('</table><br>');
   write('- Altres camps de dades:<br>');
   write('<table style=" font-size: 10px; font-family: Verdana, sans-serif; background-color: #cbf0f5">');
   write('<tr><td><span title="Nom del Director"><b>@NOMDIRECTOR</b></span></td><td><span title="Data d\'avui en format: dd-mm-aaaa"><b>@DATAAVUI</b></span></td><tr>');
   write('<tr><td colspan="2"><span title="Text en mascul&iacute; o femen&iacute; segons el genere del director."><b>@GENEREDIR(</b><i>text masc.</i><b>:</b><i>text fem.</i><b>)</b></span></td><tr>');
   write('</table><br>');
   write('- Modificadors de format:<br>');
   write('<table style=" font-size: 10px; font-family: Verdana, sans-serif; background-color: #cbf0f5">');
   write('<tr><td><span title="Inici de text en negreta"><b>@b@</b></span></td><td><span title="Fi de text en negreta"><b>@/b@</b></span></td><td><span title="Inici de text en cursiva"><b>@i@</b></span></td><td><span title="Fi de text en cursiva"><b>@/i@</b></span></td><tr>');
   write('<tr><td><span title="Inici de text subratllat."><b>@u@</b></span></td><td><span title="Fi de text subratllat"><b>@/u@</b></span></td><td>&nbsp;</td><td>&nbsp;</td><tr>');
   write('</table>');
   write('</body></html>');
   close();
 }
 finestraAjuda.focus();
}

function inflliureSelAlum(id) {
 window.focus();
 var finestraSelAlum=window.open('informelliure.php?idsess=<?=$idsess?>&selalum='+id,'inflliureselalum', 'status=0,width=750,height=525,top=15,left=15,resizable=1,scrollbars=1');
}
</script>
</head>
<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if(isset($inflliureafg)&&$inflliureafg=='1') {
 $inflliureafgdat=split(' ', $inflliureafgdata);
 $inflliureafgda=split('-', $inflliureafgdat[1]);
 $inflliureafgdatatimestamp=mktime(0,0,0,$inflliureafgda[1],$inflliureafgda[0],$inflliureafgda[2],-1);
 $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."informelliure SET id_prof='$sess_user', ref_alum='', data='$inflliureafgdatatimestamp', titol='".addslashes($inflliureafgtitol)."', contingut='".addslashes($inflliureafgtext)."'"; 
 mysql_query($consulta, $connect);
}
if(isset($inflliureesborrar)&&$inflliureesborrar!='') {
  $consulta="DELETE from $bdtutoria.$tbl_prefix"."informelliure where id='$inflliureesborrar' LIMIT 1";
  mysql_query($consulta, $connect);
}
if(isset($inflliureupdt)&&$inflliureupdt!='') {
 $consulta="UPDATE $bdtutoria.$tbl_prefix"."informelliure SET titol='".addslashes($inflliureupdttitol)."', contingut='".addslashes($inflliureupdttext)."' WHERE id='$inflliureupdt' LIMIT 1"; 
 mysql_query($consulta, $connect);
}

print("<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess' enctype='multipart/form-data'>
<div align='right'>
<table border='0'><tr>
<td><font size='6'>Informe lliure - Circulars&nbsp; &nbsp; </font></td>
</tr></table></div><hr>");

    print("<input type='hidden' name='nouinflliure' value=''>");
    if($nalumne!=-1) print("<a href='' onClick='document.forms.introd1.nouinflliure.value=\"1\"; document.forms.introd1.submit(); return false;'>Nou informe lliure.</a><br><br>");
    if(isset($nouinflliure)&&$nouinflliure=='1') {
       print("<table border='0'  bgcolor='#aacccc' width='100%'>
              <tr><td width='52%'><b>Nou.-</b>&nbsp; <input type='hidden' name='inflliureafg' value=''><a href='' onClick='document.forms.introd1.inflliureafg.value=\"1\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a>
	              &nbsp; <a href='' onClick='inflliureajuda(); return false;'>Ajuda</a>
	              &nbsp; <b>Data:</b> <input type='hidden' name='inflliureafgdata' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."'>".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."
		     </td>
	        <td width='48%'><b>Titol:</b> <input type='text' name='inflliureafgtitol' size='40' value=''></td></tr>
              <tr><td colspan='2'><b>Contingut:</b><br><textarea name='inflliureafgtext' rows='30' cols='140' wrap='soft' style=' font-size: 10px; font-family: Verdana, sans-serif; background-color: #cbf0f5'></textarea></td></tr>
              </table><hr>");
    }
    $consulta="SELECT id, ref_alum, data, titol, contingut FROM $bdtutoria.$tbl_prefix"."informelliure WHERE id_prof='$sess_user' ORDER BY data desc, id DESC";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $nregs=mysql_num_rows($conjunt_resultant);
    if(0==$nregs) {
     print("<br>No tens cap informe lliure creat.");
    }
    else {                                                                                  
      print("<input type='hidden' name='inflliureesborrar' value=''>");
      print("<input type='hidden' name='inflliuremodificar' value=''>");
      while($fila=mysql_fetch_row($conjunt_resultant)) {
       
       print("<table border='0' bgcolor='#aacccc' width='100%'>");
       $aux="veure".$nregs;
       eval("\$aux2=\$$aux;");
       print("<input type='hidden' name='$aux' value='$aux2'>");
       if(isset($inflliuremodificar)&&$inflliuremodificar==$fila[0]) {
	 print("<tr><td width='52%'><b>$nregs.-</b>&nbsp; <input type='hidden' name='inflliureupdt' value=''><a href='' onClick='document.forms.introd1.inflliureupdt.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a>
			<a href='' onClick='inflliureajuda(); return false;'>Ajuda</a>
	                &nbsp; &nbsp; <b>Data:</b> ".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."
			</td>
		    <td width='48%'><b>Titol:</b> <input type='text' name='inflliureupdttitol' size='40' value=''><script language='JavaScript'>document.forms.introd1.inflliureupdttitol.value='".addslashes($fila[3])."';</script></td></tr>
	      <tr><td colspan='2'><b>Contingut:</b><br><textarea name='inflliureupdttext' rows='30' cols='140' wrap='soft' style=' font-size: 10px; font-family: Verdana, sans-serif; background-color: #cbf0f5'>$fila[4]</textarea></td></tr>");
       }
       else {
	 eval("\$aux1=(!isset(\$$aux)||\$$aux!='o');");
	 if($aux1) {
	   print("<tr><td width='25'><a href='' title='Veure&acute;l' onClick='document.introd1.$aux.value=\"o\"; document.introd1.submit(); return false;'><b>$nregs.-</b></a></td>
	                 <td width='100'>".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."</td><td width='400'>$fila[3]</td></tr>");
	 }
	 else { 
           print("<tr><td width='60%'><a href='' title='Amaga&acute;l' onClick='document.introd1.$aux.value=\"t\"; document.introd1.submit(); return false;'><b>$nregs.-</b></a>
	             &nbsp; <a href='' onClick='if(confirm(\"Segur que vols esborrar aquest informe?\")) {document.forms.introd1.inflliureesborrar.value=\"$fila[0]\"; document.forms.introd1.submit();} return false;'>Esborrar</a>
		     &nbsp; <a href='' onClick='document.forms.introd1.inflliuremodificar.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Modificar</a>
		     &nbsp; <a href='' onClick='obrefinestrainflliu(\"$fila[0]\"); return false;'>Imprimir</a>
		     &nbsp; <a href='' onClick=' inflliureSelAlum(\"$fila[0]\"); return false;'>Alumnes</a>
	             &nbsp; <b>Data:</b> ".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."</td>
		    <td width='40%'><b>Titol:</b> $fila[3]</tr>
               <tr><td colspan='2'><b>Contingut:</b><span style=' font-size: 10px; font-family: Verdana, sans-serif; background-color: #cbf0f5'><xmp>$fila[4]</xmp></span></td></tr>");
        }
       }
       print("</table><hr>");
       --$nregs;
      }
      mysql_free_result($conjunt_resultant);   
    }


print("</form>");
?>

</body>
</html>
