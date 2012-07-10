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
</script>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">
<?
//Sortida per defecte:
print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>
<table border='0'>
<tr><td><font size='6'>Veure logs&nbsp; &nbsp; </font></td>
<td><b>Des de:</b> <input type='text' name='dataI' size='13' value='".((isset($dataI))?$dataI:$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp))."' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,0);'></td>
<td><b>Fins:</b> <input type='text' name='dataF' size='13' value='".((isset($dataF))?$dataF:$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp))."' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,1);'></td>
<td align='right'>&nbsp; <b>Usuari-professor:</b>
<select name='usuprof' onChange='document.introd1.submit();'>
<option></option><option".(($usuprof=='-- Tots --')?" selected":"").">-- Tots --</option>");
$consulta="SELECT usuari FROM $bdusuaris.$tbl_prefix"."usu_profes ORDER BY usuari";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) {
  print("<option".(($usuprof==$fila[0])?" selected":"").">$fila[0]</option>");
}
mysql_free_result($conjunt_resultant);
print("</select>
</td></tr>
</table>
</div><hr>
");

if (isset($dataI)&&$dataI!=''&&isset($dataF)&&$dataF!=''&&isset($usuprof)&&$usuprof!='') {
  $datI=preg_split('/ /', $dataI);
  $daI=preg_split('/-/', $datI[1]);
  $datatimestampI=mktime(0,0,0,$daI[1],$daI[0],$daI[2],-1);
  $datF=preg_split('/ /', $dataF);
  $daF=preg_split('/-/', $datF[1]);
  $datatimestampF=mktime(23,59,59,$daF[1],$daF[0],$daF[2],-1);
  $filtredata= "datahora>='$datatimestampI' and datahora<='$datatimestampF' ";
  $consulta  = "select id, usuari, datahora, ipremota, text ";
  $consulta .= "from $bdtutoria.$tbl_prefix"."logs ";
  if($usuprof=='-- Tots --') $usu='';
  else $usu="usuari='$usuprof' and";
  $consulta .= "where $usu $filtredata ";
  $consulta .= "order by datahora desc";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $nfiles=mysql_num_rows($conjunt_resultant);
  if($nfiles==0) {
    print("No hi ha cap acci&oacute; registrada.");
  }
  else {
    print("<table border='0' width='100%'><tr bgcolor='#0088cc'>
    <td align='center' width='40'><b>Id</b></td><td align='center' width='80'><b>UsuProf</b></td><td align='center' width='110'><b>Data</b></td><td align='center' width='60'><b>Hora</b></td><td align='center' width='110'><b>Origen</b></td><td width='110'><b>Text</b></td>
    </tr>");
    while($fila=mysql_fetch_row($conjunt_resultant)) {
      print("<tr bgcolor='#aacccc'><td>$fila[0]</td><td>$fila[1]</td><td align='center'>".$nomDiaSem[date('w',$fila[2])].", ".date('j-n-Y',$fila[2])."</td><td align='center'>".date('H:i:s',$fila[2])."</td><td align='center'>$fila[3]</td><td>$fila[4]</td></tr>");
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
