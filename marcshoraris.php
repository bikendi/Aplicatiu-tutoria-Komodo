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
<style type='text/css'>
</style>
<?
@include("linkbd.inc.php");
@include("comu.php");
@include("comu.js.php");
panyacces("Administrador");

if(isset($eliminar) && $eliminar!='') {
  $elim=explode(" ", $eliminar);
  $consulta="DELETE from $bdtutoria.$tbl_prefix"."marcshoraris where curs='$elim[0]' and grup='$elim[1]' and etapa='$elim[2]'";
  mysql_query($consulta, $connect);
}

if(isset($noumarchorari)&&$noumarchorari!='') {
   $noumarchor=explode(" ", $noumarchorari);
   if($noumarchor[1]!='*') $consulta="select count(*) from $bdtutoria.$tbl_prefix"."marcshoraris where curs='$noumarchor[0]' and (grup='$noumarchor[1]' or grup='*') and etapa='$noumarchor[2]'";
   else $consulta="select count(*) from $bdtutoria.$tbl_prefix"."marcshoraris where curs='$noumarchor[0]' and etapa='$noumarchor[2]'";
   $conjunt_resultant=mysql_query($consulta, $connect);
   if(0==mysql_result($conjunt_resultant, 0, 0)) {
      $consulta="insert into $bdtutoria.$tbl_prefix"."marcshoraris SET curs='$noumarchor[0]', grup='$noumarchor[1]', etapa='$noumarchor[2]', diasem='$nomDiaSem[1]', hora='1'";
      mysql_query($consulta, $connect);   
   }
   mysql_free_result($conjunt_resultant);
}

if(isset($horarimarc)) {
  $horma=explode(" ", $horarimarc);
  $quantitatpost=count($HTTP_POST_VARS);
  for($i=0; $i<$quantitatpost; ++$i) {
    $key=key($HTTP_POST_VARS);
    $noms=preg_split('/_/', $key);
    if($noms[0]=='hd') {
      if($noms[1]==0) { 
        if(current($HTTP_POST_VARS)=='1') { 
	  $consulta="insert into $bdtutoria.$tbl_prefix"."marcshoraris SET curs='$horma[0]', grup='$horma[1]', etapa='$horma[2]', diasem='$noms[3]', hora='$noms[4]'";
	  mysql_query($consulta, $connect);
	}
      }
      else { 
        if(current($HTTP_POST_VARS)==0) { 
	    $consulta="delete from $bdtutoria.$tbl_prefix"."marcshoraris where id='".$noms[1]."' LIMIT 1";
	    mysql_query($consulta, $connect);
	}
      }
    }
    next($HTTP_POST_VARS);
  }
}
?>
<script language='JavaScript'>

function eliminar(phorarimarc)
{
  location.href="<?=$PHP_SELF?>?eliminar="+phorarimarc+"&idsess=<?=$idsess?>";
}

function nouhorarimarc()
{
 var finestra;
 window.focus();
 opt = "status=0,resizable=1,scrollbars=0,width=300,height=100,left=15,top=60";
 finestra=window.open("", "finestra", opt);
 with(finestra.document) {
  write("<html><head><title>Tutoria</title>");
  write("<style type='text/css'>");
  write(" FORM {display:inline}");
  write("</style>");
  write("<scr" + "ipt language='JavaScript'>");
  write("function dacord() { ");
  write("   var vals = document.forms.form1.curs.options[document.forms.form1.curs.selectedIndex].text;");
  write("   vals += \" \" + document.forms.form1.grup.options[document.forms.form1.grup.selectedIndex].text;");
  write("   vals += \" \" + document.forms.form1.etapa.options[document.forms.form1.etapa.selectedIndex].text;");
  write("   opener.document.forms.introd1.noumarchorari.value= vals;");
  write("   opener.document.forms.introd1.submit();");
  write("   window.close();");
  write("} ");  
  write("</scr" + "ipt>");
  write("</head>");
  write("<body bgcolor='#c0c0c0'>");
  write("<form name='form1'><center>");
  <?
  $cursos='';
  $consulta="SELECT  DISTINCT curs FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER  BY curs";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
    $cursos.=($fila[0]!="?")?"<option>".$fila[0]."</option>":"";
  }
  mysql_free_result($conjunt_resultant);
  $grps='<option>*</option>';
  $consulta="SELECT  DISTINCT grup FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER  BY grup";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
    $grps.=($fila[0]!="?")?"<option>".$fila[0]."</option>":"";
  }
  mysql_free_result($conjunt_resultant);
  $pla_estudi='';
  $consulta="SELECT  DISTINCT pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER  BY pla_estudi desc";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
    $plaestudi.=($fila[0]!="?")?"<option>".$fila[0]."</option>":"";
  }
  mysql_free_result($conjunt_resultant);
  ?> 
  write("<select name='curs'><?echo $cursos?></select>&nbsp; &nbsp; ");
  write("<select name='grup'><?echo $grps?></select>&nbsp; &nbsp; ");
  write("<select name='etapa'><?echo $plaestudi?></select><p>");
  write("<input type='button' value=\"D'acord\" onClick='dacord();'>");
  write("</center></form>");
  write("</body>");
  write("</html>");
  close();
 }
 finestra.focus();
}
</script>

</head>
<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?

print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>
<input type='hidden' name='noumarchorari' value=''>
<table border='0'>
<tr><td><font size='6'>Horaris-Marc&nbsp; &nbsp; </font></td>
<td align='right'>
<b>Grup:</b> <select name='horarimarc' onChange='document.introd1.submit();'>
<option></option>");
$consulta="SELECT DISTINCT concat( curs,  ' ', grup,  ' ', etapa ) FROM $bdtutoria.$tbl_prefix"."marcshoraris ORDER BY etapa desc, curs, grup";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) {
  print("<option".(($horarimarc==$fila[0])?" selected":"").">$fila[0] $fila[1] $fila[2]</option>");
}
mysql_free_result($conjunt_resultant);
print("</select>&nbsp; <a href='' title='Afegeix un nou horari-marc' onClick='nouhorarimarc(); return false;'>Nou</a> &nbsp;");
if($horarimarc!='') print("<a href='' title=\"Elimina l'horari-marc seleccionat\" onClick='if(confirm(\"Segur que vols eliminar aquest horari-marc?\")) eliminar(document.forms.introd1.horarimarc.options[document.forms.introd1.horarimarc.selectedIndex].text); return false;'>Eliminar</a> &nbsp;");
print("</td></tr></table></div><hr>");

if(isset($horarimarc) && $horarimarc!='') {
  print("<table border='0' width='100%'><tr>");
  print("<td width='10%'><input type='submit' value='Gravar'></td>");
  print("<td width='35%'>&nbsp;</td>"); 
  print("<td width='25%'>&nbsp;</td>");
  print("<td width='30%' align='right'>&nbsp;</td>");
  print("</tr></table>");

  print("<table border='0' width='100%'>");
  print("<tr><td width='5%'bgcolor='#0088cc'>&nbsp;</td>");
  for($j=1; $j<6; ++$j) print("<td align='center' width='19%' bgcolor='#0088cc'><b>$nomDiaSem[$j]</b></td>");
  print("</tr>");
  
  $consulta="SELECT hora, inici, fi FROM $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
	   $llistahores[]=$fila[0];
	   $llistahorestitle[]=date('H',$fila[1]).":".date('i',$fila[1])." - ".date('H',$fila[2]).":".date('i',$fila[2]);
  }
  mysql_free_result($conjunt_resultant);
  
  for($i=0; $i<count($llistahores); ++$i) {
    print("<tr><td align='center' height='30' valign='middle' bgcolor='#0088cc' title='$llistahorestitle[$i]'><b>$llistahores[$i]</b></td>");
    for($j=1; $j<6; ++$j) {
	$horarima=explode(" ", $horarimarc);
	$consulta="SELECT id FROM $bdtutoria.$tbl_prefix"."marcshoraris WHERE curs='$horarima[0]' and grup='$horarima[1]' and etapa='$horarima[2]' and diasem='$nomDiaSem[$j]' and hora='$llistahores[$i]' LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	if(1==mysql_num_rows($conjunt_resultant)) $id=mysql_result($conjunt_resultant, 0, 0);
	else $id=0;
	mysql_free_result($conjunt_resultant);
        print("<td valign='center' align='center' bgcolor='".(($id!=0)?"#ffff77":"#aacccc")."' style='font-size:11'>");
	print("<input type='hidden' name='hd_".$id."_".(($id!=0)?"1":"0")."_$nomDiaSem[$j]_$llistahores[$i]' value='".(($id!=0)?"1":"0")."'>");
	print("<input type='checkbox' name='ckb_".$id."_".(($id!=0)?"1":"0")."_$nomDiaSem[$j]_$llistahores[$i]'".(($id!=0)?" checked":"")." onClick='if(document.forms.introd1.ckb_".$id."_".(($id!=0)?"1":"0")."_$nomDiaSem[$j]_$llistahores[$i].checked) document.forms.introd1.hd_".$id."_".(($id!=0)?"1":"0")."_$nomDiaSem[$j]_$llistahores[$i].value=1; else document.forms.introd1.hd_".$id."_".(($id!=0)?"1":"0")."_$nomDiaSem[$j]_$llistahores[$i].value=0;'>");
        print("</td>");
    }
    print("</tr>");
  }
  print("</table>");
}
print("</form>");
?>
</body>
</html>
