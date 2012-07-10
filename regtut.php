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
if(isset($_GET['obrefitxer'])&& $_GET['obrefitxer']!='') {
 $consulta="SELECT ref_fitxer, nom_fitxer, tipus_mime, tamany FROM $bdtutoria.$tbl_prefix"."fitxers WHERE id='".$_GET['obrefitxer']."' LIMIT 1";
 $conjunt_resultant=mysql_query($consulta, $connect);
 $ref_fitxer=mysql_result($conjunt_resultant, 0,0);
 $nom_fitxer=mysql_result($conjunt_resultant, 0,1);
 $tipus_mime=mysql_result($conjunt_resultant, 0,2);
 $tamany=mysql_result($conjunt_resultant, 0,3);
 mysql_free_result($conjunt_resultant);
 $fp=fopen("$dirfitxers/".$ref_fitxer,"r");
 if( $fp ) {
	header("Content-type: $tipus_mime\n");
 	header("Content-disposition: attachment; filename=\"$nom_fitxer\"\n\n");
 	while(!feof($fp)) {
   	print(fgetc($fp));
 	}
 } else {
 	echo "<p> Error obrint fitxer</p>";
 }
 exit;
}
?>
<html>
<head>
<title>Tutoria</title>

<?php
@include("comu.php");
@include("comu.js.php");
panyacces("Privilegis|Pare");
echo '<link rel="stylesheet" type="text/css" href="css/comu.css" />';

$priv=preg_split("/_/", $sess_privilegis); // per saber si és pare
if( $priv[0] == "Pare" )
  $esPare = true;
else
  $esPare = false;
?>

<script language='JavaScript'>
function calendariEscriuDia(di, i, mes, any) {
 var cad;
 if(di=='Avui') {
   var avui= new Date(<?php print(1000*mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1));?>);
   di="<?php print($nomDiaSem[date('w')]);?>";
   cad="<a href='' onClick='document.forms.introd1.<?=(((!isset($opcio)) ||($opcio=='entrev'))?'entrvafgdata':'informeafgdata')?>.value=\""+di+", "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Avui</a>";
   return cad;
 }
 if(di=='ICurs') {
   cad="<a href='' onClick='document.forms.introd1.<?=(((!isset($opcio)) ||($opcio=='entrev'))?'entrvafgdata':'informeafgdata')?>.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Inici Curs</a>";
   return cad;
 }
 cad="<a href='' onClick='document.forms.introd1.<?=(((!isset($opcio)) ||($opcio=='entrev'))?'entrvafgdata':'informeafgdata')?>.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>" + i + "</a>";
 return cad;
}

function calendariEscriuDia1(di, i, mes, any) {
 var cad;
 if(di=='Avui') {
   var avui= new Date(<?php print(1000*mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1));?>);
   di="<?php print($nomDiaSem[date('w')]);?>";
   cad="<a href='' onClick='document.forms.introd1.<?=(((!isset($opcio)) ||($opcio=='entrev'))?'entrvupdtdata':'informeupdtdata')?>.value=\""+di+", "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Avui</a>";
   return cad;
 }
 if(di=='ICurs') {
   cad="<a href='' onClick='document.forms.introd1.<?=(((!isset($opcio)) ||($opcio=='entrev'))?'entrvupdtdata':'informeupdtdata')?>.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Inici Curs</a>";
   return cad;
 }
 cad="<a href='' onClick='document.forms.introd1.<?=(((!isset($opcio)) ||($opcio=='entrev'))?'entrvupdtdata':'informeupdtdata')?>.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>" + i + "</a>";
 return cad;
}


function selalumn() {
  
  if("-- De tot el grup --"==document.introd1.alumne.options[document.introd1.alumne.selectedIndex].text) {
    document.introd1.nalumne.value='-1';
    return;
  }
  if("-- Comu a tot <?=$grup?> --"==document.introd1.alumne.options[document.introd1.alumne.selectedIndex].text) {
    document.introd1.nalumne.value='-2';
    return;
  }  
  var llistaalumne = llistaalumnes.split('|');
  document.introd1.nalumne.value='';
  for (var i=0; i<llistaalumne.length;++i) {
    var llistaalum=llistaalumne[i].split('&');
    if(llistaalum[1]==document.introd1.alumne.options[document.introd1.alumne.selectedIndex].text) {
      document.introd1.nalumne.value=llistaalum[0];
      break;
    }
  }
}

function obrefinestra(id)
{
 window.focus();
 opt = "resizable=1,scrollbars=0,width=600,height=400,left=5,top=60";
 finestra=window.open("informesincid_pdf.php?idsess=<?=$idsess?>&id="+id, "finestra", opt);
}

</script>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra()">

<?php 
if($public_chk) $public = 1;
else $public = 0;

// Entrevistes - accions //
if(isset($entrvafg)&&$entrvafg!='') {
 $entrvafgdat=preg_split('/ /', $entrvafgdata);
 $entrvafgda=preg_split('/-/', $entrvafgdat[1]);
 $entrvafgdatatimestamp=mktime(0,0,0,$entrvafgda[1],$entrvafgda[0],$entrvafgda[2],-1);
 $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."entrevistes SET ref_alum='$entrvafg', data='$entrvafgdatatimestamp', titol='".addslashes($entrvafgtitol)."', reunits='".addslashes($entrvafgreunits)."', descripcio='".addslashes($entrvafgdescripcio)."', public = $public"; 
 mysql_query($consulta, $connect);
}
if(isset($entrvesborrar)&&$entrvesborrar!='' && ! $esPare) {
  $consulta="DELETE from $bdtutoria.$tbl_prefix"."entrevistes where id='$entrvesborrar' LIMIT 1";
  mysql_query($consulta, $connect);
}
if(isset($entrvupdt)&&$entrvupdt!='' && ! $esPare) {
 $entrvupdtdat=preg_split('/ /', $entrvupdtdata);
 $entrvupdtda=preg_split('/-/', $entrvupdtdat[1]);
 $entrvupdtdatatimestamp=mktime(0,0,0,$entrvupdtda[1],$entrvupdtda[0],$entrvupdtda[2],-1);
 $consulta="UPDATE $bdtutoria.$tbl_prefix"."entrevistes SET data='$entrvupdtdatatimestamp', titol='".addslashes($entrvupdttitol)."', reunits='".addslashes($entrvupdtreunits)."', descripcio='".addslashes($entrvupdtdescripcio)."', public = $public WHERE id='$entrvupdt' LIMIT 1"; 
//  echo "<p> consulta: $consulta </p>\n";
 mysql_query($consulta, $connect);
}

// Informes - accions //
if(isset($informeafg)&&$informeafg!='' && ! $esPare) {
 $informeafgdat=preg_split('/ /', $informeafgdata);
 $informeafgda=preg_split('/-/', $informeafgdat[1]);
 $informeafgdatatimestamp=mktime(0,0,0,$informeafgda[1],$informeafgda[0],$informeafgda[2],-1);
 $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."informeincid SET ref_alum='$informeafg', data='$informeafgdatatimestamp', id_prof='$informeafgprofessor', hora='$informeafghora', text='".addslashes($informeafgtext)."', public = $public"; 
//  echo "<p> consulta: $consulta </p>\n";
 mysql_query($consulta, $connect);
}
if(isset($informeesborrar)&&$informeesborrar!='' && ! $esPare) {
  $consulta="DELETE from $bdtutoria.$tbl_prefix"."informeincid where id='$informeesborrar' LIMIT 1";
  mysql_query($consulta, $connect);
}
if(isset($informeupdt)&&$informeupdt!='' && ! $esPare) {
 $informeupdtdat=preg_split('/ /', $informeupdtdata);
 $informeupdtda=preg_split('/-/', $informeupdtdat[1]);
 $informeupdtdatatimestamp=mktime(0,0,0,$informeupdtda[1],$informeupdtda[0],$informeupdtda[2],-1);
 $consulta="UPDATE $bdtutoria.$tbl_prefix"."informeincid SET data='$informeupdtdatatimestamp', id_prof='$informeupdtprofessor', hora='$informeupdthora', text='".addslashes($informeupdttext)."', public = $public WHERE id='$informeupdt' LIMIT 1"; 
 mysql_query($consulta, $connect);
}

// Fitxers - accions //
if(isset($fitxerdesar)&&$fitxerdesar!='' && ! $esPare) {
 if($MAX_FILE_SIZE>=$fitxerdesarfitxer_size && file_exists($fitxerdesarfitxer)) {
  $fitxerdesardat=preg_split('/ /', $fitxerdesardata);
  $fitxerdesarda=preg_split('/-/', $fitxerdesardat[1]);
  $fitxerdesardatatimestamp=mktime(0,0,0,$fitxerdesarda[1],$fitxerdesarda[0],$fitxerdesarda[2],-1);
  $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."fitxers SET data='$fitxerdesardatatimestamp', ref_alum='$fitxerdesar', nom_fitxer='".addslashes($fitxerdesarfitxer_name)."', descripcio='".addslashes($fitxerdesardescripcio)."', tipus_mime='$fitxerdesarfitxer_type', tamany='$fitxerdesarfitxer_size', public = $public";
  mysql_query($consulta, $connect);
  $consulta="SELECT last_insert_id() FROM $bdtutoria.$tbl_prefix"."fitxers";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $id=mysql_result($conjunt_resultant,0,0);
  mysql_free_result($conjunt_resultant);
  $nomfitxer="f".$fitxerdesar."_".$id;
  $consulta="UPDATE $bdtutoria.$tbl_prefix"."fitxers SET ref_fitxer='$nomfitxer', public = $public WHERE id='$id'";
  mysql_query($consulta, $connect);
//  echo "<p>1: $fitxerdesar 2: $fitxerdesarfitxer_name </p>";
//  print_r( $fitxerdesardat );
//  print_r( $fitxerdesarda );
  copy($fitxerdesarfitxer, "$dirfitxers/$nomfitxer");
 }
 else print("<script language='JavaScript'>alert('Aquest fitxer es massa gran! (max. $MAX_FILE_SIZE bytes)');</script>");
 unlink($fitxerdesarfitxer);
} 

if(isset($fitxeresborrar)&&$fitxeresborrar!='' && ! $esPare) {
 $consulta="SELECT ref_fitxer FROM $bdtutoria.$tbl_prefix"."fitxers WHERE id='$fitxeresborrar' LIMIT 1";
 $conjunt_resultant=mysql_query($consulta, $connect);
 $ref_fitxer=mysql_result($conjunt_resultant, 0,0);
 mysql_free_result($conjunt_resultant);
 unlink("$dirfitxers/".$ref_fitxer);
 $consulta="DELETE from $bdtutoria.$tbl_prefix"."fitxers WHERE id='$fitxeresborrar' LIMIT 1";
 mysql_query($consulta, $connect);
}

print("<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess' enctype='multipart/form-data'>");

if(! $esPare) { // és profe
  // selects grup-alumne
  print("<div align='right'>
  <table border='0'>
  <tr>
    <td><font size='6'>Registres de Tutoria&nbsp; &nbsp; </font></td>
    <td align='right'><b>Grup:</b> <select name='grup' onChange='document.introd1.nalumne.value=\"\"; document.introd1.submit();'>
    <option></option>");
  do {
      $permis=privilegis('-', '-',current($llista_grups));
      if($permis) print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");
  } while(next($llista_grups));
  print("</select></td>");
  print("<td><b>Alumne:</b> ");
  print("<input type='hidden' name='nalumne' value='".((isset($nalumne)&&$nalumne!='')?$nalumne:"")."'>");
  print("<select name='alumne' onChange='selalumn(); document.introd1.submit();'>
  <option>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </option>");
  if(isset($grup)&&($grup!="")) {
    print("<option".(($nalumne=='-1')?" selected":"").">-- De tot el grup --</option>
	  <option".(($nalumne=='-2')?" selected":"").">-- Comu a tot $grup --</option>");
    $gru=preg_split('/ /', $grup);
    $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum)  FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE (curs='$gru[0]' and grup='$gru[1]' and pla_estudi='$gru[2]')ORDER  BY cognom_alu, cognom2_al, nom_alum";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $llistaalumnes='';
    while($fila=mysql_fetch_row($conjunt_resultant)) {
      if($llistaalumnes!='') $llistaalumnes.='|';
      $llistaalumnes.= "$fila[0]&$fila[1]";
      print("<option".(($nalumne==$fila[0])?" selected":"").">$fila[1]</option>");
    }
    mysql_free_result($conjunt_resultant);
  }
  print("</select>");
  print("<script language='JavaScript'>var llistaalumnes='$llistaalumnes';</script>");
  print("</td></tr>
  </table>
  </div>
  <hr>
  ");
} else { // és pare
  $nalumne=$priv[1];
  echo "<div class='titol'>Registres de Tutoria</div>\n";
  echo "<hr>\n";
}

if(isset($nalumne)&&$nalumne!='') {
  if(!isset($opcio)) $opcio='informe';
  print("<table border='0' width='100%'>
    <tr><td valign='top' width='50%'>
    <u>Tipus de registre:</u><br>
    <input type='radio' name='opcio' value='entrev' ".(($opcio=='entrev')?"checked ":"")."onClick='document.forms.introd1.submit();'>Entrevistes
    <input type='radio' name='opcio' value='fitxers' ".(($opcio=='fitxers')?"checked ":"")."onClick='document.forms.introd1.submit();'>Fitxers
    <input type='radio' name='opcio' value='informe' ".(($opcio=='informe')?"checked ":"")."onClick='document.forms.introd1.submit();'>Informes d'incid&egrave;ncies
    </td>
    <td align='right' width='50%'>");
    if($nalumne>=0) {
    print("<table border='0' width='100%'
    <tr>
    <td valign='top' align='left' width='85%'>");
    $consulta="SELECT adreca, nom_munici, codi_posta, primer_tel, cognom1_pa, cognom2_pa, nom_pare, cognom1_ma, cognom2_ma, nom_mare FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$nalumne' LIMIT 1";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $fila=mysql_fetch_row($conjunt_resultant);
    print("<font color='#0000ff' size='-2'>$fila[6] $fila[4] $fila[5]<br>
           $fila[9] $fila[7] $fila[8]<br>
	   $fila[0]<br>
	   $fila[2] $fila[1]<br>
	   Telf: $fila[3]</font>");
    print("</td>
    <td valign='top' align='right' width='15%'>");
     if(file_exists("$dirfotos/$nalumne.jpg")) $foto = "./foto.php?idsess=$idsess&foto=$nalumne";
     else $foto = "./imatges/fot0.jpg";
     print("<img src='$foto' width='50' height='68' border='0'>
    </td>
    </tr></table>");
    }
    else print("&nbsp;");
    print("</td></tr></table><hr>");
    
    $selwhere="";
    if($nalumne==-1) {
      $selwhere.="ref_alum='$grup' ";
      $llistaalumne=explode('|', $llistaalumnes);
      for($i=0; $i<count($llistaalumne); ++$i) {
       $llistaalumn=explode('&', $llistaalumne[$i]);
       $selwhere.="or ref_alum='$llistaalumn[0]' ";
      }
    }
    else if($nalumne==-2) {
      $selwhere="ref_alum='$grup' ";
    }
    else $selwhere="ref_alum='$nalumne' ";
    
    if( $esPare ) // és pare, només veu les públiques
      $selwhere .= " AND public ";

///////// ENTREVISTES ////////////////
  if($opcio=='entrev') {
    print("<input type='hidden' name='novaentrev' value=''>");
    if($nalumne!=-1 && ! $esPare) print("<a href='' onClick='document.forms.introd1.novaentrev.value=\"$nalumne\"; document.forms.introd1.submit(); return false;'>Nova entrevista</a><br><br>");
    if(isset($novaentrev)&&$novaentrev!='' && ! $esPare) {
       print("<table border='0'  bgcolor='#aacccc' width='100%'>
              <tr><td width='32%'><b>Nou.-</b>&nbsp; <input type='hidden' name='entrvafg' value=''><a href='' onClick='document.forms.introd1.entrvafg.value=\"".(($nalumne!=-2)?$nalumne:$grup)."\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a>
	              &nbsp; <b>Data:</b> <input type='text' name='entrvafgdata' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."' onClick=' blur(); obreCalendari(0,0,0);'></td>
	  <td><label for='public_chk'>Pública</label><input type='checkbox' name='public_chk' ></td>
	        <td width='58%'><b>T&iacute;tol:</b> <input type='text' name='entrvafgtitol' size='60' value=''></td></tr>
              <tr><td colspan='2'><b>Reunits:</b> <input type='text' name='entrvafgreunits' size='110' value=''></td></tr>
	      <tr><td colspan='2'><b>Descripci&oacute;:</b><br><textarea name='entrvafgdescripcio' rows='5' cols='90' wrap='hard'></textarea></td></tr>
              </table><hr>");
    }
    $consulta="SELECT id, data, titol, reunits, descripcio, ref_alum, public FROM $bdtutoria.$tbl_prefix"."entrevistes WHERE $selwhere ORDER BY data desc, id DESC";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $nregs=mysql_num_rows($conjunt_resultant);
    if(0==$nregs) {
      if($nalumne==-1) print("<br>Els alumnes d'aquest grup no tenen cap entrevista anotada.");
      else if($nalumne==-2) print("<br>No hi ha anotada cap entrevista comuna a aquest grup.");
      else print("<br>Aquest alumne no t&eacute; cap entrevista anotada.");
    }
    else {
      print("<input type='hidden' name='entrvesborrar' value=''>");
      print("<input type='hidden' name='entrvmodificar' value=''>");
      while($fila=mysql_fetch_row($conjunt_resultant)) {
       $nal='';
       if($nalumne==-1) {
	    $regs=str_replace(' ','',$fila[5]);
	  if($regs==$fila[5]) {
	    $consulta1="SELECT concat(cognom_alu,' ',cognom2_al,', ',nom_alum)  FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$fila[5]' LIMIT 1";
	    $conjunt_resultant1=mysql_query($consulta1, $connect);
	    $nal="<tr><td colspan='2'><b>Nom alumne:</b> ".mysql_result($conjunt_resultant1, 0,0)."</td></tr>";
            mysql_free_result($conjunt_resultant1);
	  }
	  else $nal="<tr><td colspan='2'><b>Com&uacute; a tot:</b> $fila[5]</td></tr>";
       }
       print("<table border='0' bgcolor='#aacccc' width='100%'>");
       if(isset($entrvmodificar)&&$entrvmodificar==$fila[0]) {
	 print("<tr><td width='33%'><b>$nregs.-</b>&nbsp; <input type='hidden' name='entrvupdt' value=''><a href='' onClick='document.forms.introd1.entrvupdt.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a>
	                &nbsp; &nbsp; <b>Data:</b> <input type='text' name='entrvupdtdata' size='13' value='".$nomDiaSem[date('w',$fila[1])].", ".date('j-n-Y',$fila[1])."' onClick=' blur(); obreCalendari(0,0,1);'></td>
	  <td><label for='public_chk'>Pública</label><input type='checkbox' name='public_chk' ".(($fila[6]=="1")?" checked='checked'":"")." ></td>
		    <td width='57%'><b>T&iacute;tol:</b> <input type='text' name='entrvupdttitol' size='60' value=''></td><script language='JavaScript'>document.forms.introd1.entrvupdttitol.value='".addslashes($fila[2])."';</script></tr>");
              print($nal);
	      print("<tr><td colspan='2'><b>Reunits:</b> <input type='text' name='entrvupdtreunits' size='110' value=''><script language='JavaScript'>document.forms.introd1.entrvupdtreunits.value='".addslashes($fila[3])."';</script></td></tr>
	      <tr><td colspan='2'><b>Descripci&oacute;:</b><br><textarea name='entrvupdtdescripcio' rows='5' cols='90' wrap='hard'>$fila[4]</textarea></td></tr>");
       }
       else {
         print("<tr>
         <td width='33%'><b>$nregs.-</b>&nbsp; <a href='' onClick='if(confirm(\"Segur que vols esborrar aquesta entrevista?\")) {document.forms.introd1.entrvesborrar.value=\"$fila[0]\"; document.forms.introd1.submit();} return false;'>Esborrar</a>&nbsp; 
         <a href='' onClick='document.forms.introd1.entrvmodificar.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Modificar</a>
	                &nbsp; &nbsp; <b>Data:</b> ".$nomDiaSem[date('w',$fila[1])].", ".date('j-n-Y',$fila[1])."</td>");
	  echo "<td>\n";
	  if( ! $esPare )
	    echo "<label for='public_chk'>Pública</label><input type='checkbox' name='public_chk' ".(($fila[6]=="1")?" checked='checked'":"")." disabled='disabled' >\n";
	  echo "</td>\n";
	  print( "<td width='57%'><b>T&iacute;tol:</b> $fila[2]</td></tr>");
              print($nal);
              print("<tr><td colspan='2'><b>Reunits:</b> $fila[3]</td></tr>
	      <tr><td colspan='2'><b>Descripci&oacute;:</b> <xmp>$fila[4]</xmp></td></tr>");
       }
       print("</table><hr>");
       --$nregs;
      }
      mysql_free_result($conjunt_resultant);   
    }
  } // fi entrevistes


///////// INFORMES d'INCIDÈNCIA ////////////////
  if($opcio=='informe') {
    print("<input type='hidden' name='nouinforme' value=''>");
    if($nalumne!=-1 && ! $esPare) print("<a href='' onClick='document.forms.introd1.nouinforme.value=\"$nalumne\"; document.forms.introd1.submit(); return false;'>Nou informe</a><br><br>");
    if(isset($nouinforme)&&$nouinforme!='' && ! $esPare) {
       print("<table border='0'  bgcolor='#aacccc' width='100%'>
              <tr><td width='42%'><b>Nou.-</b>&nbsp; <input type='hidden' name='informeafg' value=''><a href='' onClick='document.forms.introd1.informeafg.value=\"".(($nalumne!=-2)?$nalumne:$grup)."\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a>
	              &nbsp; <b>Data:</b> <input type='text' name='informeafgdata' size='13' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."' onClick=' blur(); obreCalendari(0,0,0);'>
		      <b>Hora:</b><select name='informeafghora'>
                            <option></option>");
                            $consulta1="SELECT hora FROM $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
                            $conjunt_resultant1=mysql_query($consulta1, $connect);
                            while($fila1=mysql_fetch_row($conjunt_resultant1)) {
                              print("<option>$fila1[0]</option>");
                            }
                            mysql_free_result($conjunt_resultant1);
                        print("</select>
                   </td>
	  <td><label for='public_chk'>Públic</label><input type='checkbox' name='public_chk' ></td>
	        <td width='48%'><b>Professor:</b> <input type='hidden' name='informeafgprofessor' size='40' value=''><script language='JavaScript'>document.forms.introd1.informeafgprofessor.value='".addslashes($sess_user)."';</script>$sess_user - $sess_nomreal</td></tr>
              <tr><td colspan='2'><b>Text:</b><br><textarea name='informeafgtext' rows='15' cols='90' wrap='hard'></textarea></td></tr>
              </table><hr>");
    }
    $consulta="SELECT id, id_prof, data, hora, text, ref_alum, public FROM $bdtutoria.$tbl_prefix"."informeincid WHERE $selwhere ORDER BY data desc, id DESC";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $nregs=mysql_num_rows($conjunt_resultant);
    if(0==$nregs) {
      if($nalumne==-1) print("<br>Els alumnes d'aquest grup no tenen cap informe creat.");
      else if($nalumne==-2) print("<br>No hi ha cap informe com&uacute; per aquest grup.");
      else print("<br>Aquest alumne no t&eacute; cap informe creat.");
    
    }
    else {                                                                                  
      print("<input type='hidden' name='informeesborrar' value=''>");
      print("<input type='hidden' name='informemodificar' value=''>");
      while($fila=mysql_fetch_row($conjunt_resultant)) {
       $nal='';
       if($nalumne==-1) {
	  $regs=str_replace(' ','',$fila[5]);
	  if($regs==$fila[5]) {
	    $consulta1="SELECT concat(cognom_alu,' ',cognom2_al,', ',nom_alum)  FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$fila[5]' LIMIT 1";
	    $conjunt_resultant1=mysql_query($consulta1, $connect);
	    $nal="<tr><td colspan='2'><b>Nom alumne:</b> ".mysql_result($conjunt_resultant1, 0,0)."</td></tr>";
            mysql_free_result($conjunt_resultant1);
	  }
	  else $nal="<tr><td colspan='2'><b>Com&uacute; a tot:</b> $fila[5]</td></tr>";
       }
       $consulta="SELECT nomreal FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$fila[1]' LIMIT 1";
       $conjunt_resultant1=mysql_query($consulta, $connect);
       if(0!=mysql_num_rows($conjunt_resultant)) $nomrealprof=mysql_result($conjunt_resultant1, 0,0);
       else $nomrealprof="???";
       mysql_free_result($conjunt_resultant1);
       print("<table border='0' bgcolor='#aacccc' width='100%'>");
       if(isset($informemodificar)&&$informemodificar==$fila[0]) {
	 print("<tr><td width='42%'><b>$nregs.-</b>&nbsp; <input type='hidden' name='informeupdt' value=''><a href='' onClick='document.forms.introd1.informeupdt.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a>
	                &nbsp; &nbsp; <b>Data:</b> <input type='text' name='informeupdtdata' size='13' value='".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."' onClick=' blur(); obreCalendari(0,0,1);'>
			<b>Hora:</b><select name='informeupdthora'>
                            <option></option>");
                            $consulta1="SELECT hora FROM $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
                            $conjunt_resultant1=mysql_query($consulta1, $connect);
                            while($fila1=mysql_fetch_row($conjunt_resultant1)) {
                              print("<option".(($fila[3]==$fila1[0])?" selected":"").">$fila1[0]</option>");
                            }
                            mysql_free_result($conjunt_resultant1);
                        print("</select>
			</td>
	  <td><label for='public_chk'>Públic</label><input type='checkbox' name='public_chk' ".(($fila[6]=="1")?" checked='checked'":"")." ></td>
		    <td width='48%'><b>Professor:</b> <input type='hidden' name='informeupdtprofessor' size='40' value=''><script language='JavaScript'>document.forms.introd1.informeupdtprofessor.value='".addslashes($fila[1])."';</script>$fila[1] - $nomrealprof</td></tr>
              $nal
	      <tr><td colspan='2'><b>Text:</b><br><textarea name='informeupdttext' rows='15' cols='90' wrap='hard'>$fila[4]</textarea></td></tr>");
       }
       else {
         print("<tr><td width='39%'><b>$nregs.-</b>
	             &nbsp; ");
	 if( ! $esPare ) {
	    print("
	             <a href='' onClick='if(confirm(\"Segur que vols esborrar aquest informe?\")) {document.forms.introd1.informeesborrar.value=\"$fila[0]\"; document.forms.introd1.submit();} return false;'>Esborrar</a>
		     &nbsp; <a href='' onClick='document.forms.introd1.informemodificar.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Modificar</a>
		     &nbsp; ");
	 }
	 print(" 
		     <a href='' onClick='obrefinestra(\"$fila[0]\"); return false;'>Imprimir</a>
	             &nbsp; <b>Data:</b> ".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."&nbsp; &nbsp;<b>Hora:</b> $fila[3]</td>");
	  echo "<td>\n";
	  if( ! $esPare )
	    echo "<label for='public_chk'>Públic</label><input type='checkbox' name='public_chk'".(($fila[6]=="1")?" checked='checked'":"")." disabled='disabled' \n";
	  echo "</td>\n";
	  print( "<td width='41%'><b>Professor:</b> $fila[1] - $nomrealprof</td></tr>
               $nal
	       <tr><td colspan='2'><b>Text:</b> <xmp>$fila[4]</xmp></td></tr>");
       }
       print("</table><hr>");
       --$nregs;
      }
      mysql_free_result($conjunt_resultant);   
    }
  } // fi informes

///////// FITXERS ////////////////  
  if($opcio=='fitxers') {
    print("<input type='hidden' name='noufitxer' value=''>");
    if($nalumne!=-1 && ! $esPare) print("<a href='' onClick='document.forms.introd1.noufitxer.value=\"$nalumne\"; document.forms.introd1.submit(); return false;'>Nou fitxer</a><br><br>");
    if(isset($noufitxer)&&$noufitxer!='' && ! $esPare) {
       print("<table border='0' bgcolor='#aacccc' width='100%'>");
       print("<tr><td width='35%'><b>Nou.-</b>&nbsp; <input type='hidden' name='fitxerdesar' value=''>
               <input type='hidden' name='MAX_FILE_SIZE' value='300000'>
	       <a href='' onClick='document.forms.introd1.fitxerdesar.value=\"".(($nalumne!=-2)?$nalumne:$grup)."\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a>
	       &nbsp; &nbsp; <b>Data:</b> <input type='hidden' name='fitxerdesardata' value='".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."'>".$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)."</td>");
	echo "<td>\n";
	if( ! $esPare )
	  echo "<label for='public_chk'>Públic</label><input type='checkbox' name='public_chk' >\n";
	echo "</td>\n";
	print( "<td width='55%'><b>Fitxer:</b> <input type='file' name='fitxerdesarfitxer'> <font size='-2'>(m&agrave;x.: <script language='JavaScript'>document.write(document.forms.introd1.MAX_FILE_SIZE.value +\" bytes\");</script>)</font></td></tr>
               <tr><td colspan='2'><b>Descripcio:</b> <input type='text' name='fitxerdesardescripcio' size='80' value=''></td></tr>");
       print("</table><hr>");
    }
    $consulta="SELECT id, data, nom_fitxer, descripcio, ref_alum, public FROM $bdtutoria.$tbl_prefix"."fitxers WHERE $selwhere ORDER BY data desc, id DESC";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $nregs=mysql_num_rows($conjunt_resultant);
    if(0==$nregs) {
      if($nalumne==-1) print("<br>Els alumnes d'aquest grup no tenen cap fitxer adjuntat.");
      else if($nalumne==-2) print("<br>No hi ha adjuntat cap fitxer com&uacute; a aquest grup.");
      else print("<br>Aquest alumne no t&eacute; cap fitxer adjuntat.");
    }
    else {                                                                                  
      print("<input type='hidden' name='fitxeresborrar' value=''>");
      while($fila=mysql_fetch_row($conjunt_resultant)) {
       $nal='';
       if($nalumne==-1) {
	  $regs=str_replace(' ','',$fila[4]);
	  if($regs==$fila[4]) {
	    $consulta1="SELECT concat(cognom_alu,' ',cognom2_al,', ',nom_alum)  FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$fila[4]' LIMIT 1";
	    $conjunt_resultant1=mysql_query($consulta1, $connect);
	    $nal="<tr><td colspan='2'><b>Nom alumne:</b> ".mysql_result($conjunt_resultant1, 0,0)."</td></tr>";
            mysql_free_result($conjunt_resultant1);
	  }
	  else $nal="<tr><td colspan='2'><b>Com&uacute; a tot:</b> $fila[4]</td></tr>";
       }
       print("<table border='0' bgcolor='#aacccc' width='100%'>");
       print("<tr><td width='30%'><b>$nregs.-</b>
               &nbsp; ");
	 if( ! $esPare ) {
	    print("
	             <a href='' onClick='if(confirm(\"Segur que vols esborrar aquest fitxer?\")) {document.forms.introd1.fitxeresborrar.value=\"$fila[0]\"; document.forms.introd1.submit();} return false;'>Esborrar</a>
	             ");
	 }
	 print("
	       &nbsp; &nbsp; <b>Data:</b> ".$nomDiaSem[date('w',$fila[1])].", ".date('j-n-Y',$fila[1])."</td>
	  <td>");
	  if( ! $esPare )
	    echo "<label for='public_chk'>Públic</label><input type='checkbox' name='public_chk' ".(($fila[5]=="1")?" checked='checked'":"")." disabled='disabled' ></td>\n";
	  echo "</td>\n";
	  print( "<td width='60%'><b>Nom fitxer:</b> <a href='$PHP_SELF?obrefitxer=$fila[0]'><img src='".iconafitxer($fila[2])."' border='0' width='12' height='14'> $fila[2]</a></td></tr>
               $nal
	       <tr><td colspan='2'><b>Descripcio:</b> $fila[3]</td></tr>");
       print("</table><hr>");
       --$nregs;
      }
      mysql_free_result($conjunt_resultant);   
    }
  }
}
print("</form>");
?>

</body>
</html>
