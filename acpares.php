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
  @include("comu.php");
  panyacces("Administrador");
  $quantitatpost=count($HTTP_POST_VARS);
  for($i=0; $i<$quantitatpost; ++$i) {
    $key=key($HTTP_POST_VARS);
    $noms=split('_', $key);
    if($noms[0]=='per') {
      $id=$noms[1];
      $valorvell=$noms[2];
      if(current($HTTP_POST_VARS)!=$valorvell) {
        $consulta="UPDATE $bdtutoria.$tbl_prefix"."pares SET permisos='".current($HTTP_POST_VARS)."' WHERE id='$id' LIMIT 1";
	mysql_query($consulta, $connect);
      }
    }
    next($HTTP_POST_VARS);
   }
?>
<html>
<head>
<title>Tutoria</title>
<?
@include("comu.js.php");
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

function obrefinestra(nal)
{
 window.focus();
 opt = "resizable=1,scrollbars=0,width=600,height=400,left=5,top=60";
 finestra=window.open("acpares_pdf.php?idsess=<?=$idsess?>&nal="+nal, "finestra", opt);
}
function cartes_tots(grup)
{
 window.focus();
 opt = "resizable=1,scrollbars=0,width=600,height=400,left=5,top=60";
 finestra=window.open("acpares_pdf.php?idsess=<?=$idsess?>&nal=tots&grup="+grup, "finestra", opt);
}
</script>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">

<?
if(isset($identpares)&&$identpares==1) {
  print("
  <div align='right'>
  <form name='introd1' method='post' action='$PHP_SELF?idsess=$idsess&identpares=1'>
  <table border='0'>
  <tr><td>&nbsp; &nbsp; <a href='$PHP_SELF?idsess=$idsess&identpares=0'>Veure logs</a>&nbsp; &nbsp; </td>
     <td><font size='6'>Acc&eacute;s pares - Identificadors&nbsp; &nbsp; </font></td>");

  print("<td align='right'>");
  print("<b>Grups:</b> <select name='grup' onChange='document.introd1.submit();'>
   <option></option>");
  do {print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");} while(next($llista_grups));
  print("</select></td></tr></table>");
  print("</div><hr>");
}
else {
  print("
  <div align='right'>
  <form name='introd1' method='post' action='$PHP_SELF?idsess=$idsess'>
  <table border='0'>
  <tr><td>&nbsp; &nbsp; <a href='$PHP_SELF?idsess=$idsess&identpares=1'>Veure identificadors</a>&nbsp; &nbsp; </td>
     <td><font size='6'>Acc&eacute;s pares - Logs&nbsp; &nbsp; </font></td>");
  if(!isset($dataI)) $dataI=$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp);
  if(!isset($dataF)) $dataF=$dataI;
  print("<td><b>Des de:</b> <input type='text' name='dataI' size='13' value='$dataI' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,0);'></td>
     <td><b>Fins:</b> <input type='text' name='dataF' size='13' value='$dataF' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,1);'></td>
   </tr></table></div><hr>");
}

if(isset($identpares)&&$identpares==1&&$grup!='') {
      $gru=split(' ', $grup);
      $consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE (curs='$gru[0]' and grup='$gru[1]' and pla_estudi='$gru[2]') ORDER  BY cognom_alu, cognom2_al, nom_alum ASC";
      $conjunt_resultant=mysql_query($consulta, $connect);
      $capcal="<tr bgcolor='#0088cc'><td colspan='2'><center>&nbsp;<b>Pares de:</b>&nbsp;</center></td>";
      $capcal .= "<td><center>&nbsp;<b>Identificador</b>&nbsp;</center></td>";
      $capcal .= "<td><center>&nbsp;<b>Contrasenya</b>&nbsp;</center></td>";
      $capcal .= "<td><center>&nbsp;<b>Permisos</b>&nbsp;</center></td>";
      $capcal .= "<td><b>Telèfon</b></td>";
      $capcal .= "<td><b>E-mail</b></td>";
      $capcal .= "<td><b>E-mail 2</b></td>";
      $capcal .= "<td>&nbsp;</td></tr>";
      print("<input type='submit' value='Gravar canvis'>");
      print("<input type='button' value='Imprimir tots' onClick='cartes_tots(\"$grup\"); return false;'> \n");
      print("<table border='0'><tr><td width='15'>&nbsp;</td><td>");
      print("<table border='0'>");
      
      while ($fila=mysql_fetch_row($conjunt_resultant)) {
         if($compt_capcal%5==0) print($capcal);
         ++$compt_capcal;
         if(file_exists("$dirfotos/$fila[0].jpg")) $foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
         else $foto = "./imatges/fot0.jpg";
         $linkfil="<a href='' onClick='obreFoto(\"$foto\", \"$fila[1]\"); return false;'><img src='$foto' width='25' height='34' border='0'></a>";
         print("<tr bgcolor='#aacccc'><td>".$linkfil."</td><td>".$fila[1]."</td>");
         $consulta1="SELECT id, identificador, passwd, permisos, telfSMS, email, email2 FROM $bdtutoria.$tbl_prefix"."pares WHERE refalumne='$fila[0]' LIMIT 1";
         $conjunt_resultant1=mysql_query($consulta1, $connect);
	 $fila1=mysql_fetch_row($conjunt_resultant1);
	 print("<td>$fila1[1]</td>
		<td>$fila1[2]</td>
		<td>
	     <a href='' onClick='document.introd1.p_$fila1[0]_1.click(); document.introd1.p_$fila1[0]_2.click(); document.introd1.p_$fila1[0]_3.click(); document.introd1.p_$fila1[0]_4.click(); return false;' title='Activa o desactiva tots els permisos alhora.'>Tots</a>
		 <input type='hidden' name='per_$fila1[0]_$fila1[3]' value='$fila1[3]'>
		 <input type='checkbox' name='p_$fila1[0]_1'".((($fila1[3]>>0)&1==1)?" checked":"")." onClick='if(document.introd1.p_$fila1[0]_1.checked) document.introd1.per_$fila1[0]_$fila1[3].value =parseInt(document.introd1.per_$fila1[0]_$fila1[3].value)+1; else document.introd1.per_$fila1[0]_$fila1[3].value-=1' title='Permet veure les incid&egrave;ncies.'>Incid.
		 <input type='checkbox' name='p_$fila1[0]_2'".((($fila1[3]>>1)&1==1)?" checked":"")." onClick='if(document.introd1.p_$fila1[0]_2.checked) document.introd1.per_$fila1[0]_$fila1[3].value =parseInt(document.introd1.per_$fila1[0]_$fila1[3].value)+2; else document.introd1.per_$fila1[0]_$fila1[3].value-=2' title='Permet canviar el password i introduir telf.m&ograve;bil per als SMS.'>Canvi passwd.<br>
		 <input type='checkbox' name='p_$fila1[0]_3'".((($fila1[3]>>2)&1==1)?" checked":"")." onClick='if(document.introd1.p_$fila1[0]_3.checked) document.introd1.per_$fila1[0]_$fila1[3].value =parseInt(document.introd1.per_$fila1[0]_$fila1[3].value)+4; else document.introd1.per_$fila1[0]_$fila1[3].value-=4' title='Permet veure les qualificacions.'>Avals.
		 <input type='checkbox' name='p_$fila1[0]_4'".((($fila1[3]>>3)&1==1)?" checked":"")." onClick='if(document.introd1.p_$fila1[0]_4.checked) document.introd1.per_$fila1[0]_$fila1[3].value =parseInt(document.introd1.per_$fila1[0]_$fila1[3].value)+8; else document.introd1.per_$fila1[0]_$fila1[3].value-=8' title='Permet accedir a l´entorn de comunicaci&oacute;.'>Comunic.
		 </td>
		<td>$fila1[4]</td>
		<td>$fila1[5]</td>
		<td>$fila1[6]</td>
		<td>&nbsp;<a href='' onClick='obrefinestra(\"$fila[0]\"); return false;'>Impr&eacute;s</a>&nbsp;</td></tr>");
	 mysql_free_result($conjunt_resultant1);
	 
      }
      mysql_free_result($conjunt_resultant);
      print("</table></td></tr></table><input type='submit' value='Gravar canvis'>
      <input type='button' value='Imprimir tots' onClick='cartes_tots(\"$grup\"); return false;'>
      </form><hr>");
}
if(    (!isset($identpares)||$identpares==0)&&isset($dataI)&&$dataI!=''&&isset($dataF)&&$dataF!=''     ) {
  $datI=split(' ', $dataI);
  $daI=split('-', $datI[1]);
  $datatimestampI=mktime(0,0,0,$daI[1],$daI[0],$daI[2],-1);
  $datF=split(' ', $dataF);
  $daF=split('-', $datF[1]);
  $datatimestampF=mktime(23,59,59,$daF[1],$daF[0],$daF[2],-1);
  $filtredata= "datahora>='$datatimestampI' and datahora<='$datatimestampF' ";
  $consulta  = "select id, usuari, datahora, ipremota, text ";
  $consulta .= "from $bdtutoria.$tbl_prefix"."pareslogs ";
  $consulta .= "where $filtredata ";
  $consulta .= "order by datahora desc";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $nfiles=mysql_num_rows($conjunt_resultant);
  if($nfiles==0) {
    print("No hi ha cap acci&oacute; registrada.");
  }
  else {
    print("<table border='0' width='100%'><tr bgcolor='#0088cc'>
    <td align='center' width='40'><b>Id</b></td><td align='center' width='80'><b>Ident Pare</b></td><td align='center' width='110'><b>Data</b></td><td align='center' width='60'><b>Hora</b></td><td align='center' width='110'><b>Origen</b></td><td width='110'><b>Text</b></td>
    </tr>");
    while($fila=mysql_fetch_row($conjunt_resultant)) {
      print("<tr bgcolor='#aacccc'><td>$fila[0]</td><td>$fila[1]</td><td align='center'>".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."</td><td align='center'>".date('H:i:s',$fila[2])."</td><td align='center'>$fila[3]</td><td>$fila[4]</td></tr>");
    }
    print("</table>");
  }
  mysql_free_result($conjunt_resultant);
  print("</td></tr></table><hr>");
}


?>
</body>
</html>
