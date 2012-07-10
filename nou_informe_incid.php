<?
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
<?php
@include("linkbd.inc.php");
@include("comu.php");
@include("comu.js.php");
panyacces("Privilegis");

if($public_chk) $public = 1;
else $public = 0;

if(isset($informeafg)&&$informeafg!='') {
 $informeafgdat=preg_split('/ /', $informeafgdata);
 $informeafgda=preg_split('/-/', $informeafgdat[1]);
 $informeafgdatatimestamp=mktime(0,0,0,$informeafgda[1],$informeafgda[0],$informeafgda[2],-1);
 $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."informeincid SET ref_alum='$informeafg', data='$informeafgdatatimestamp', id_prof='$informeafgprofessor', hora='$informeafghora', text='".addslashes($informeafgtext)."', public = $public"; 
//  echo "<p> consulta: $consulta</p>\n";
 mysql_query($consulta, $connect);
 print("<script language='JavaScript'>alert(\"L'informe d'incidència s'ha desat correctament. Ja el té disponible el tutor del grup.\"), location.href='buit.php?idsess=$idsess';</script>");
 exit;
}

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
   cad="<a href='' onClick='document.introd1.informeafgdata.value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>Inici Curs</a>";
   return cad;
 }
 cad="<a href='' onClick='document.forms.introd1.informeafgdata.value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); mostraWindowedObjects(true); return false;'>" + i + "</a>";
 return cad;
}

</script>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra()">

<?php
print("<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>
<div align='right'>
<table border='0'><tr>
<td><font size='6'>Informe d'incid&egrave;ncia&nbsp; &nbsp; </font></td>
</tr>
</table>
</div>
<hr>
");
    
$consulta ="SELECT concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$nalumne' LIMIT 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
print("<input type='hidden' name='nouinforme' value='$nalumne'>");
print("<table border='0' width='100%'><tr><td valign='top' width='80%'><b>Alumne:</b> $fila[0] - $fila[1] $fila[2] $fila[3]</td>
         <td width='20%' align='right'>");
          if(file_exists("$dirfotos/$nalumne.jpg")) $foto = "./foto.php?idsess=$idsess&foto=$nalumne";
          else $foto = "./imatges/fot0.jpg";
          print("<img src='$foto' width='50' height='68' border='0'>
	 </td></tr></table>");
mysql_free_result($conjunt_resultant);
print("<table border='0'  bgcolor='#aacccc' width='100%'>
       <tr><td width='42%'><b>Nou.-</b>&nbsp; <input type='hidden' name='informeafg' value=''><a href='' onClick='document.forms.introd1.informeafg.value=\"$nalumne\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='location.href=\"buit.php?idsess=$idsess\"; return false;'>Cancelar</a>
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
        <tr><td colspan='2'><b>Text:</b><br><textarea name='informeafgtext' rows='18' cols='90' wrap='hard'></textarea></td></tr>
        </table><hr>");
 print("</form>");
?>

</body>
</html>
