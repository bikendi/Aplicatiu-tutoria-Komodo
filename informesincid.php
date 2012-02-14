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
?>

<script language='JavaScript'>
function calendariEscriuDia(di, i, mes, any) {
 var cad;
 if(di=='Avui') {
   var avui= new Date(<?print(1000*mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1));?>);
   di="<?print($nomDiaSem[date('w')]);?>";
   cad="<a href='' onClick='document.forms.introd1.informeafgdata.value=\""+di+", "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Avui</a>";
   return cad;
 }
 if(di=='ICurs') {
   cad="<a href='' onClick='document.forms.introd1.informeafgdata.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Inici Curs</a>";
   return cad;
 }
 cad="<a href='' onClick='document.forms.introd1.informeafgdata.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>" + i + "</a>";
 return cad;
}

function calendariEscriuDia1(di, i, mes, any) {
 var cad;
 if(di=='Avui') {
   var avui= new Date(<?print(1000*mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1));?>);
   di="<?print($nomDiaSem[date('w')]);?>";
   cad="<a href='' onClick='document.forms.introd1.informeupdtdata.value=\""+di+", "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Avui</a>";
   return cad;
 }
 if(di=='ICurs') {
   cad="<a href='' onClick='document.forms.introd1.informeupdtdata.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Inici Curs</a>";
   return cad;
 }
 cad="<a href='' onClick='document.forms.introd1.informeupdtdata.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>" + i + "</a>";
 return cad;
}


function selalumn() {
  
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

if(isset($informeafg)&&$informeafg!='') {
 $informeafgdat=split(' ', $informeafgdata);
 $informeafgda=split('-', $informeafgdat[1]);
 $informeafgdatatimestamp=mktime(0,0,0,$informeafgda[1],$informeafgda[0],$informeafgda[2],-1);
 $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."informeincid SET ref_alum='$informeafg', data='$informeafgdatatimestamp', id_prof='$informeafgprofessor', hora='$informeafghora', text='".addslashes($informeafgtext)."', public = $public"; 
 mysql_query($consulta, $connect);
 // TODO: notifiquem a tutor, cap d'estudis, directora i coord. btx si s'escau

 unset($informeafg);
 unset($nouinforme);
 unset($informeafgdata);
 unset($informeafghora);
 unset($informeafgtext);
 unset($grup);
 unset($nalumne);
 print("<script language='JavaScript'>alert(\"L'informe d'incidència s'ha desat correctament. Ja el té disponible el tutor del grup.\");</script>");
 
 
 }
if(isset($informeesborrar)&&$informeesborrar!='') {
  $consulta="DELETE from $bdtutoria.$tbl_prefix"."informeincid where id='$informeesborrar' LIMIT 1";
  mysql_query($consulta, $connect);
}
if(isset($informeupdt)&&$informeupdt!='') {
 $informeupdtdat=split(' ', $informeupdtdata);
 $informeupdtda=split('-', $informeupdtdat[1]);
 $informeupdtdatatimestamp=mktime(0,0,0,$informeupdtda[1],$informeupdtda[0],$informeupdtda[2],-1);
 $consulta="UPDATE $bdtutoria.$tbl_prefix"."informeincid SET data='$informeupdtdatatimestamp', id_prof='$informeupdtprofessor', hora='$informeupdthora', text='".addslashes($informeupdttext)."', public = $public WHERE id='$informeupdt' LIMIT 1"; 
 mysql_query($consulta, $connect);
}

print("<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess' enctype='multipart/form-data'>
<div align='right'>
<table border='0'><tr>
<td><font size='6'>Informes d'incid&egrave;ncia&nbsp; &nbsp; </font></td>
<td align='right'>&nbsp;</td></tr>
</table>
</div>
<hr>
");

    print("<input type='hidden' name='nouinforme' value=''>");
    if(!(isset($nouinforme)&&$nouinforme=='-1')) print("<a href='' onClick='document.forms.introd1.nouinforme.value=\"-1\"; document.forms.introd1.submit(); return false;'>Nou informe</a><br><br>");
    if(isset($nouinforme)&&$nouinforme=='-1') {
       print("<table border='0'  bgcolor='#aacccc' width='100%'>
       <tr><td width='42%'><b>Nou.-</b>&nbsp; <input type='hidden' name='informeafg' value=''><a href='' onClick='if(document.introd1.nalumne.value==\"\") alert(\"Ep! Tens que seleccionar un grup i alumne!\"); else {document.forms.introd1.informeafg.value=\"".(($nalumne!=-2)?$nalumne:$grup)."\"; document.forms.introd1.submit();} return false;'>Desar</a> <a href='' onClick='document.forms.introd1.nouinforme.value=\"\"; document.forms.introd1.informeafgdata.value=\"\"; document.forms.introd1.informeafghora.value=\"\"; document.forms.introd1.informeafgtext.value=\"\"; document.forms.introd1.nalumne.value=\"\"; document.forms.introd1.submit(); return false;'>Cancelar</a>
       &nbsp; <b>Data:</b> <input type='text' name='informeafgdata' size='13' value='".((isset($informeafgdata))?$informeafgdata:(     $nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp)        ))."' onClick=' blur(); obreCalendari(0,0,0);'>
       <b>Hora:</b><select name='informeafghora'>
       <option></option>");
       $consulta1="SELECT hora FROM $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
       $conjunt_resultant1=mysql_query($consulta1, $connect);
       while($fila1=mysql_fetch_row($conjunt_resultant1)) {
         print("<option".(($informeafghora==$fila1[0])?" selected":"").">$fila1[0]</option>");
       }
       mysql_free_result($conjunt_resultant1);
       print("</select></td>
       <td><label for='public_chk'>Públic</label><input type='checkbox' name='public_chk' ></td>
       <td width='48%'><b>Professor:</b> <input type='hidden' name='informeafgprofessor' size='40' value=''><script language='JavaScript'>document.forms.introd1.informeafgprofessor.value='".addslashes($sess_user)."';</script>$sess_user - $sess_nomreal</td></tr>
       <tr><td colspan='2'>
       <b>Grup:</b> <select name='grup' onChange='document.introd1.nalumne.value=\"\"; document.forms.introd1.nouinforme.value=\"-1\"; document.introd1.submit();'>
       <option></option>");
       do {
         print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");
        } while(next($llista_grups));
       print("</select>");
       print("&nbsp; &nbsp; &nbsp; &nbsp; <b>Alumne:</b> ");
       print("<input type='hidden' name='nalumne' value='".((isset($nalumne)&&$nalumne!='')?$nalumne:"")."'>");
       print("<select name='alumne' onChange='selalumn(); document.forms.introd1.nouinforme.value=\"-1\"; document.introd1.submit();'>
       <option>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </option>");
       if(isset($grup)&&($grup!="")) {
         print("<option".(($nalumne=='-2')?" selected":"").">-- Comu a tot $grup --</option>");
         $gru=split(' ', $grup);
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
        <tr><td colspan='2'><b>Text:</b><br><textarea name='informeafgtext' rows='15' cols='90' wrap='hard'>$informeafgtext</textarea></td></tr>
        </table><hr>");
    }
    
    $selwhere="";
    if($nalumne==-2) {
      $selwhere="ref_alum='$grup' ";
    }
    else $selwhere="ref_alum='$nalumne' ";
    
    $consulta="SELECT id, id_prof, data, hora, text, ref_alum, public FROM $bdtutoria.$tbl_prefix"."informeincid WHERE id_prof='$sess_user' ORDER BY data desc, id DESC";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $nregs=mysql_num_rows($conjunt_resultant);
    if(0==$nregs) {
      print("<br>No tens cap informe creat.");
    }
    else {                                                                                  
      print("<input type='hidden' name='informeesborrar' value=''>");
      print("<input type='hidden' name='informemodificar' value=''>");
      while($fila=mysql_fetch_row($conjunt_resultant)) {
          $nal='';
	  $regs=str_replace(' ','',$fila[5]);
      if($regs==$fila[5]) {
	    $consulta1="SELECT concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$fila[5]' LIMIT 1";
	    $conjunt_resultant1=mysql_query($consulta1, $connect);
	    $nal="<tr><td colspan='2'><b>Nom alumne:</b> ".mysql_result($conjunt_resultant1, 0,0)."&nbsp; (".mysql_result($conjunt_resultant1, 0,1)." ".mysql_result($conjunt_resultant1, 0,2)." ".mysql_result($conjunt_resultant1, 0,3).")</td></tr>";
            mysql_free_result($conjunt_resultant1);
	  }
	  else $nal="<tr><td colspan='2'><b>Com&uacute; a tot:</b> $fila[5]</td></tr>";

       $nomrealprof="$sess_nomreal";
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
       } // fi if modify
       else {
         print("<tr><td width='39%'><b>$nregs.-</b>
	 &nbsp; <a href='' onClick='if(confirm(\"Segur que vols esborrar aquest informe?\")) {document.forms.introd1.informeesborrar.value=\"$fila[0]\"; document.forms.introd1.submit();} return false;'>Esborrar</a>
	 &nbsp; <a href='' onClick='document.forms.introd1.informemodificar.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Modificar</a>
	 &nbsp; <a href='' onClick='obrefinestra(\"$fila[0]\"); return false;'>Imprimir</a>
	                &nbsp; <b>Data:</b> ".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."&nbsp; &nbsp;<b>Hora:</b> $fila[3]</td>");
	  echo "<td>\n";
	  echo "<label for='public_chk'>Públic</label><input type='checkbox' name='public_chk'".(($fila[6]=="1")?" checked='checked'":"")." disabled='disabled' \n";
	  echo "</td>\n";
	  print("<td width='41%'><b>Professor:</b> $fila[1] - $nomrealprof</td></tr>
               $nal
	       <tr><td colspan='2'><b>Text:</b> <xmp>$fila[4]</xmp></td></tr>");
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
