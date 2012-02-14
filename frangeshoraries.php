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

if((isset($afegirdesar) && $afegirdesar=='1')||(isset($modificardesar) && $modificardesar!='')) {
	if(isset($afegirdesar) && $afegirdesar!='') {
    	$consulta  = "select count(*) from $bdtutoria.$tbl_prefix"."frangeshoraries where hora='$nomhora'";
    	$conjunt_resultant=mysql_query($consulta, $connect);
    	if(0<mysql_result($conjunt_resultant, 0,0)) {
    		mysql_free_result($conjunt_resultant);
			print("<html><script language='JavaScript'>alert('Aquest nom d´hora ja existeix! Tria´n un altre de diferent.'); location.href='$PHP_SELF?idsess=$idsess';</script></html>");
			exit;    		    
    	}
    	mysql_free_result($conjunt_resultant);
		if(eregi("^[a-z0-9]{1,4}$",$nomhora)!=true) {
			print("<html><script language='JavaScript'>alert('Nom d´hora incorrecte, ha d´esser entre 1 i 4 caracters a-z, A-Z, 0-9.'); location.href='$PHP_SELF?idsess=$idsess';</script></html>");
			exit;		
		}
	}
	if(ereg("^([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$",$hinici)!=true || ereg("^([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$",$hfi)!=true) {
		print("<html><script language='JavaScript'>alert('Format d´hora incorrecta, ha d´esser: HH:MM:SS.'); location.href='$PHP_SELF?idsess=$idsess';</script></html>");
		exit;		
	}
	$hinic=split(':',$hinici);
	$hinicitimestamp=mktime($hinic[0],$hinic[1],$hinic[2],1,1,1970,-1);
	$hf=split(':',$hfi);
	$hfitimestamp=mktime($hf[0],$hf[1],$hf[2],1,1,1970,-1);
	if($extra) $extraescolar = 1;
	else $extraescolar = 0;
	if (isset($afegirdesar) && $afegirdesar=='1') {
		$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."frangeshoraries SET hora='$nomhora', inici='$hinicitimestamp', fi='$hfitimestamp', extraescolar='$extraescolar'";
		mysql_query($consulta, $connect);
	}
	if (isset($modificardesar) && $modificardesar!='') {
		$consulta="UPDATE $bdtutoria.$tbl_prefix"."frangeshoraries SET inici='$hinicitimestamp', fi='$hfitimestamp', extraescolar='$extraescolar' WHERE id='$modificardesar' LIMIT 1";	
		mysql_query($consulta, $connect);
	}
}
if (isset($eliminar) && $eliminar!='') {
	if (!isset($eliminarconfirm)||$eliminarconfirm!="si") {
		print("<html><script language='JavaScript'>if (confirm('Segur que vols eliminar aquest nom d´hora? Si l´elimines, totes les incidències i referències que utilitzen aquest nom d´hora quedaran inaccessibles!')) location.href='$PHP_SELF?idsess=$idsess&eliminar=$eliminar&eliminarconfirm=si'; else location.href='$PHP_SELF?idsess=$idsess';</script></html>");
		exit;
	} else {
		$consulta="DELETE FROM $bdtutoria.$tbl_prefix"."frangeshoraries WHERE id='$eliminar' LIMIT 1";
		mysql_query($consulta, $connect);
	}	
}
?>
</head>
<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?

print("
<div align='right'>
<table border='0'>
<tr><td><font size='6'>Franges hor&agrave;ries&nbsp; &nbsp; </font></td></tr>
</table>
</div><hr>
");

print("<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>");
$consulta  = "select id, hora, inici, fi, extraescolar from $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
// echo "<p> Consulta: $consulta </p>\n";
$conjunt_resultant=mysql_query($consulta, $connect);
$nfiles=mysql_num_rows($conjunt_resultant);
print("<table border='0' align='center'><tr><td>");
print("<input type='hidden' name='afegir' value=''>");
print("<a href='' onClick='document.forms.introd1.afegir.value=\"1\"; document.forms.introd1.submit(); return false;'>Afegir franja horaria</a><br>");
if(($nfiles==0 && !isset($afegir))||($nfiles==0 && isset($afegir)&& $afegir!='1'  )  ) {
    print("No hi ha cap franja hor&agrave;ria registrada.");
}
else {
    print("<table border='0' width='100%'>
      <tr bgcolor='#0088cc'>
	<td width='60'>&nbsp;</td>
	<td width='60'>&nbsp;</td>
	<td align='center' width='80' valign='top'><b>Nom Hora</b><br>(d'1 a 4 c&agrave;rs. a-z, A-Z o 0-9)</td>
	<td align='center' width='80' valign='top'><b>H. Inici</b><br>(hh:mm:ss)</td>
	<td align='center' width='80' valign='top'><b>H. Fi</b><br>(hh:mm:ss)</td>
	<td align='center' width='80' valign='top'><b>Extraescolar</b></td>
      </tr>");
    if(isset($afegir)&&$afegir=='1') {
	  print("<input type='hidden' name='afegirdesar' value=''>");
	  print("<tr bgcolor='#aacccc'>
	  <td><a href='' onClick='document.forms.introd1.afegirdesar.value=\"1\"; document.forms.introd1.submit(); return false;'>Desar</a></td>
	  <td><a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a></td>
	  <td align='center'><input type='text' name='nomhora' size='4' maxlength='4'></td>
	  <td align='center'><input type='text' name='hinici' size='8' maxlength='8'></td>
	  <td align='center'><input type='text' name='hfi' size='8' maxlength='8'></td>
	  <td align='center'><input type='checkbox' name='extra'></td>
	  </tr>"); 
    }
    if ($nfiles!=0) {
	  print("<input type='hidden' name='eliminar' value=''>");
	  print("<input type='hidden' name='modificar' value=''>");
      while($fila=mysql_fetch_row($conjunt_resultant)) {
	    if (isset($modificar) && $modificar==$fila[0]) {
	    	print("<input type='hidden' name='modificardesar' value=''>");
		    print("<tr bgcolor='#aacccc'>
		    <td><a href='' onClick='document.forms.introd1.modificardesar.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Desar</a></td>
		    <td><a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a></td><td align='center'>$fila[1]</td>
		    <td align='center'><input type='text' name='hinici' value='".date('H',$fila[2]).":".date('i',$fila[2]).":".date('s',$fila[2])."' size='8' maxlength='8'></td>
		    <td align='center'><input type='text' name='hfi' value='".date('H',$fila[3]).":".date('i',$fila[3]).":".date('s',$fila[3])."' size='8' maxlength='8'></td>
		    <td align='center'><input type='checkbox' name='extra' ".(($fila[4]=="1")?" checked='checked'":"")." ></td>
		    </tr>");   
	    }
	    else print("<tr bgcolor='#aacccc'>
	    <td><a href='' onClick='document.forms.introd1.modificar.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Modificar</a></td>
	    <td><a href='' onClick='document.forms.introd1.eliminar.value=\"$fila[0]\"; document.forms.introd1.submit(); return false;'>Eliminar</a></td>
	    <td align='center'>$fila[1]</td><td align='center'>".date('H',$fila[2]).":".date('i',$fila[2]).":".date('s',$fila[2])."</td>
	    <td align='center'>".date('H',$fila[3]).":".date('i',$fila[3]).":".date('s',$fila[3])."</td>
	    <td align='center'><input type='checkbox' ".(($fila[4]=="1")?" checked='checked'":"")." disabled='disabled'></td>
	    </tr>");
      }
    }
    print("</table>");
}
print("</td></tr></table>");
mysql_free_result($conjunt_resultant);
print("<hr></form>");
?>
</body>
</html>