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


if(isset($eliminallistacredits)&& $eliminallistacredits!='') {
 $consulta="DELETE FROM $bdtutoria.$tbl_prefix"."llistacredits where id='$eliminallistacredits' limit 1";
 mysql_query($consulta, $connect);	
}

$insertfail=false;
if (isset($desaafegirllistacredits) && $desaafegirllistacredits=='si') {
 $consulta="insert into $bdtutoria.$tbl_prefix"."llistacredits set codi='$codi', nomcredit='".rawurlencode(stripslashes($nomcredit))."', areaassign='materia', tipus='$tipus', pla_estudis='$pla_estudis', observacions='".rawurlencode(stripslashes($observacions))."'";
 $res=mysql_query($consulta, $connect);
 if($res==1) {
 	for($i=0; $i<$ncredits;++$i) {
		$consulta="insert into $bdtutoria.$tbl_prefix"."llistacredits set codi='".$codi.($i+1)."', nomcredit='".rawurlencode(stripslashes($nomcredit))." ".($i+1)."', areaassign='$codi', tipus='$tipus', pla_estudis='$pla_estudis', observacions='".rawurlencode(stripslashes($observacions))."'";
 	   mysql_query($consulta, $connect);	 
 	}
 } else $insertfail=true;	
}

$updatefail=false;
if (isset($actualitzacredit) && $actualitzacredit!='') {
 $consulta="update $bdtutoria.$tbl_prefix"."llistacredits set codi='$editacreditcodi', nomcredit='".rawurlencode(stripslashes($editacreditnomcredit))."', areaassign='$editacreditareaassign', tipus='$editacredittipus', pla_estudis='$editacreditplaestudis', observacions='".rawurlencode(stripslashes($editacreditobservacions))."' WHERE id='$actualitzacredit' limit 1";
 $res=mysql_query($consulta, $connect);
 if($res!=1) $updatefail=true;	
}
?>


</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
if($insertfail) print("<script language='JavaScript'>alert('Error a l´inserir la materia, codi repetit');</script>");
if($updatefail) print("<script language='JavaScript'>alert('Error al actualitzar la materia, codi repetit');</script>");
print("
<div align='right'>
<table border='0'>
<tr><td><font size='6'>Definir Mat&egrave;ries-&Agrave;rees i Cr&egrave;dits&nbsp; &nbsp; </font></td>
</tr></table></div><hr>");
print("<table width='100%' border='0'><tr>
<td valign='top' >");

print("&nbsp;</td>
<td valign='top' >");
    $consulta="SELECT id, codi, nomcredit, areaassign, tipus, pla_estudis, observacions FROM $bdtutoria.$tbl_prefix"."llistacredits ORDER BY pla_estudis desc, tipus, codi";
    $conjunt_resultant=mysql_query($consulta, $connect);
    print("<table style='border-style:solid; border-width:1'>");
    if(!(isset($editacredit) || isset($afegirllistacredits))) print("<tr><td colspan='3'><a href='$PHP_SELF?idsess=$idsess&afegirllistacredits=si' title='Afegir mat&egrave;ria'>Afegir nova mat&egrave;ria.</a></td><td colspan='4' align='right'>Informaci&oacute; ".ajudaContextual(13,2)."</td></tr>");
    if(isset($afegirllistacredits)&& $afegirllistacredits=='si') {
		print("<tr align='center'><td>&nbsp;</td><td><b>Codi</b></td><td><b>Nom mat&egrave;ria</b></td><td><b>Nº cr&egrave;dits</b></td><td><b>Tipus</b></td><td><b>Pla_estudis</b></td><td><b>Observacions</b></td></tr>
		<form name='allc' method='post' action='$PHP_SELF?idsess=$idsess&desaafegirllistacredits=si'><tr align='center'><td><a href='' title='Desa la nova mat&egrave;ria' onClick='if(document.forms.allc.codi.value==\"\"||document.forms.allc.nomcredit.value==\"\") alert(\"Error, no es pot deixar buit els camps codi i/o matèria.\"); else document.forms.allc.submit(); return false;'>Desar</a> <a href='$PHP_SELF?idsess=$idsess' title='Cancel&middot;la l´acci&oacute;'>Cancelar</a></td>
		<td><input type='text' name='codi' size='5' maxlength='5' value=''></td>
		<td><input type='text' name='nomcredit' size='20' maxlength='50' value=''></td>
		<td><select name='ncredits'><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option></select></td>
		<td><select name='tipus'><option>CC</option><option>CV</option><option>CM</option><option>CO</option></select></td>
		<td><select name='pla_estudis'><option></option>");
		$consulta1="SELECT DISTINCT pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER BY pla_estudi desc";
		$conjunt_resultant1=mysql_query($consulta1, $connect);
		while($fila1=mysql_fetch_row($conjunt_resultant1)) {
			print("<option>$fila1[0]</option>");
		}	
		print("</select></td>
		<td><input type='text' name='observacions' size='30' value=''></td>
		</tr><form>");        
    }
    else {
	    print("<tr><td>&nbsp;</td><td align='center'><b>Codi</b></td><td align='center'><b>Nom cr&egrave;dit</b></td><td align='center'><b>&Agrave;rea-Assign.</b></td><td align='center'><b>Tipus</b></td><td align='center'><b>Pla_estudis</b></td><td><b>Observacions</b></td></tr>");
    	while($fila=mysql_fetch_row($conjunt_resultant)) {
		    if(isset($editacredit)&& $editacredit==$fila[0]) {
			    print("<form name='ellc' method='post' action='$PHP_SELF?idsess=$idsess&actualitzacredit=$fila[0]'>
			    <tr align='center'><td><a href='' title='Desa els canvis' onClick='if(document.forms.ellc.editacreditcodi.value==\"\"||document.forms.ellc.editacreditnomcredit.value==\"\") alert(\"Error, no es pot deixar buit els camps codi i/o matèria.\"); else document.forms.ellc.submit(); return false;'>Desa</a> <a href='$PHP_SELF?idsess=$idsess' title='Cancel&middot;la els canvis'>Cancela</a></td>
			    <td><input type='text' name='editacreditcodi' size='5' maxlength='5' value='$fila[1]'></td>
			    <td><input type='text' name='editacreditnomcredit'  size='20' maxlength='50'value=''><script language='JavaScript'>document.forms.ellc.editacreditnomcredit.value='".addslashes(rawurldecode($fila[2]))."';</script></td>
			    <td><input type='text' name='editacreditareaassign' size='7' maxlength='7' value='$fila[3]'></td>
			    <td><select name='editacredittipus'><option".(($fila[4]=="CC")?" selected":"").">CC</option><option".(($fila[4]=="CV")?" selected":"").">CV</option><option".(($fila[4]=="CM")?" selected":"").">CM</option><option".(($fila[4]=="CO")?" selected":"").">CO</option></select></td>
			    <td><select name='editacreditplaestudis'><option></option>");
				$consulta1="SELECT DISTINCT pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER BY pla_estudi desc";
				$conjunt_resultant1=mysql_query($consulta1, $connect);
				while($fila1=mysql_fetch_row($conjunt_resultant1)) {
					print("<option".(($fila[5]==$fila1[0])?" selected":"").">$fila1[0]</option>");
				}	
				print("</select></td>
			    <td><input type='text' name='editacreditobservacions' size='30' value='$fila[6]'><script language='JavaScript'>document.forms.ellc.editacreditobservacions.value='".addslashes(rawurldecode($fila[6]))."';</script></td></tr>
			    </form>");
		    } else if(!isset($editacredit)) {
			    if($fila[3]=='materia') print("<tr><td colspan='7'>&nbsp;</td></tr>");
			    print("<tr".(($fila[3]=="materia")?" bgcolor='#00dddd'":"")."><td><a href='$PHP_SELF?idsess=$idsess&editacredit=$fila[0]' title='Modifica'>Editar</a> <a href='$PHP_SELF?idsess=$idsess&eliminallistacredits=$fila[0]' title='Elimina' onClick='return confirm(\"Segur que vols eliminar aquesta descripció de cr&egrave;dit?\");'>Eliminar</a></td><td>$fila[1]</td><td>".rawurldecode($fila[2])."</td><td>$fila[3]</td><td>$fila[4]</td><td>$fila[5]</td><td>".(($fila[6]!="")?rawurldecode($fila[6]):"&nbsp;")."</td></tr>");
		    }   
    	}
	}
    mysql_free_result($conjunt_resultant);
	print("</table>");
print("</td></tr></table>");

?>
<hr>
</body>
</html>