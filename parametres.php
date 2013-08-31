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
<?php
@include("linkbd.inc.php");
if($_POST['esborrarlogo']!='' && $_POST['esborrarlogo']=='si') {
  unlink("$dirfitxers/logocentre.jpg");
}
@include("comu.php");

echo '<link rel="stylesheet" type="text/css" href="css/comu.css" />';
if(isset($fitxer)&&$fitxer!='') {
  if ($fitxer_size<$MAX_FILE_SIZE && ($fitxer_type=="image/pjpeg" || $fitxer_type=="image/jpeg")) {
    copy($fitxer, "$dirfitxers/logocentre.jpg");
    print("<script language='JavaScript'>");
    print("opener.document.params.submit();");
    print("window.close();");
    print("</script>");
  }
  else {
    print("<script language='JavaScript'>");
    print("opener.document.params.submit();");
    print("alert('No es pot canviar. Foto massa gran (>20K) o format incorrecte (no es jpg).');");
    print("window.close();");
    print("</script>");
  }
  unlink($fitxer);
  exit;
} else if(isset($MAX_FILE_SIZE)) {
        print("<script language='JavaScript'>");
        print("opener.document.params.submit();");
        print("alert('No es pot canviar. Imatge massa gran (>$MAX_FILE_SIZE bytes).');");
        print("window.close();");
        print("</script>");
	exit;
}

@include("comu.js.php");
panyacces("Administrador");

$consulta="SELECT id FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
if (isset($gravargeneral)&& $gravargeneral==$fila[0]) {
    $datinicurs=preg_split('/ /', $datainicicurs);
    $dic=preg_split('/-/', $datinicurs[1]);
    $dictimestamp=mktime(0,0,0,$dic[1],$dic[0],$dic[2],-1);
    $datini2T=preg_split('/ /', $datainici2T);
    $di2T=preg_split('/-/', $datini2T[1]);
    $di2Ttimestamp=mktime(0,0,0,$di2T[1],$di2T[0],$di2T[2],-1);
    $datini3T=preg_split('/ /', $datainici3T);
    $di3T=preg_split('/-/', $datini3T[1]);
    $di3Ttimestamp=mktime(0,0,0,$di3T[1],$di3T[0],$di3T[2],-1);
    if($_REQUEST['reset_ESO']) $reset_ESO = 1; else $reset_ESO = 0;
    if($_REQUEST['reset_BTX']) $reset_BTX = 1; else $reset_BTX = 0;
  
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."parametres SET nomcentre='".addslashes($nomcentr)."', adrecacentre='".addslashes($adrecacentr)."', cpcentre='".addslashes($cpcentr)."', poblaciocentre='".addslashes($poblaciocentr)."', telfcentre='".addslashes($telfcentr)."', 
	director='".addslashes($_REQUEST['director'])."',
	nomdirector='".addslashes($nomdirector)."', sexdirector='".addslashes($sexdirector)."',
	capdes='".addslashes($_REQUEST['capdes'])."',
	nomcapdes='".addslashes($nomcapdes)."', 
	sexcapdes='".addslashes($sexcapdes)."',
	coordbtx='".addslashes($_REQUEST['coordbtx'])."',
	nomcoordbtx='".addslashes($nomcoordbtx)."', sexcoordbtx='".addslashes($sexcoordbtx)."', nom_cc_alumne='".addslashes($nomccalumne)."', sex_cc_alumne='".addslashes($sexccalumne)."', nom_cc_profe='".addslashes($nomccprofe)."', sex_cc_profe='".addslashes($sexccprofe)."', nom_cc_pare='".addslashes($nomccpare)."', sex_cc_pare='".addslashes($sexccpare)."', cursacademic='".addslashes($cursacademi)."', datainicicurs='$dictimestamp',
	datainici2T='$di2Ttimestamp', datainici3T='$di3Ttimestamp',
	webcentre='".addslashes($webcentr)."', emailcentre='".addslashes($emailcentr)."', 
	retards_ESO='". $_REQUEST['retards_ESO'] ."',
	reset_ESO='$reset_ESO',
	retards_BTX='". $_REQUEST['retards_BTX'] ."',
	reset_BTX='$reset_BTX',
	max_file_size='".$_REQUEST['max_file_size']."',
	max_photo_size='".$_REQUEST['max_photo_size']."'
	where id='$gravargeneral'";
// 	echo "<p>Query: $consulta</p>\n";
	mysql_query($consulta, $connect);
}
if (isset($gravarsms)&& $gravarsms==$fila[0]) {
	if($_REQUEST['sms_auto']) $sms_auto = 1; else $sms_auto = 0;
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."parametres SET remitentSMS='".addslashes($nomremSMS)."', proveidorSMS='".addslashes($actiuSMS)."', identificSMSLlNet='".addslashes($identificSMSLlNet)."', passwdSMSLlNet='".addslashes($passwdSMSLlNet)."' , identificSMSDinahosting='".addslashes($identificSMSDinahosting)."', passwdSMSDinahosting='".addslashes($passwdSMSDinahosting)."', sms_auto='$sms_auto' where id='$gravarsms'";
// 	echo "<p> Query: $consulta </p> \n";
	mysql_query($consulta, $connect);
}
mysql_free_result($conjunt_resultant);
@include("enviaSMS.php");
?>
<script language='JavaScript' src='<?php echo $js?>comu.js'></script>
<script language='JavaScript' src='<?php echo $js?>funcions.js'></script>
<script language='JavaScript'>
// function calendariEscriuDia(di, i, mes, any) {
//  var cad;
//  if(di=='Avui') {
//    var avui= new Date(<?print(1000*mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1));?>);
//    di="<?print($nomDiaSem[date('w')]);?>";
//    cad="<a href='' onClick='document.params."+camp+".value=\""+di+", "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); return false;'>Avui</a>";
//    return cad;
//  }
//  if(di=='ICurs') {
//    di="<?=$nomDiaSem[date('w',$datatimestampIniciCurs)]?>";
//    cad="<a href='' onClick='document.params."+camp+".value=\"<?=$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs)?>\"; ocultaMostraCapa(\"menuContextual\",\"o\"); return false;'>Inici Curs</a>";
//    return cad;
//  }
//  cad="<a href='' onClick='document.params."+camp+".value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); return false;'>" + i + "</a>";
//  return cad;
// }

function carregaLogoCentre()
{
 var finestra;
 window.focus();
 opt = "resizable=0,scrollbars=0,width=300,height=165,left=5,top=60";
 finestra=window.open("", "finestra", opt);
 with (finestra.document) {
  write("<html><head><title>Tutoria</title></head>");
  write("<body bgcolor='#c0c0c0'>");
  write("<form action='<?print("$PHP_SELF?idsess=$idsess");?>' method='post' enctype='multipart/form-data'>");
  write("<b>Carrega logotip de centre.</b><br>");
  write("<input type='hidden' name='MAX_FILE_SIZE' value='<?print($max_photo_size);?>'>");
  write("<font size=-2>La imatge ha d'esser format .jpg, de 129x115px aprox. i tamany m&agrave;xim de 20Kby</font><br>");
  write("Fitxer de la imatge: <input type='file' name='fitxer'><br>");
  write("<center><input type='submit' value='Canviar'></center>");
  write("</form>");
  write("</body></html>");
  close();
 }
 finestra.focus();
}

</script>

<link type="text/css" href="jQuery-UI/css/ui-lightness/jquery-ui.custom.css" rel="Stylesheet" />	
<script type="text/javascript" src="jQuery-UI/js/jquery.min.js"></script>
<script type="text/javascript" src="jQuery-UI/js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="jQuery-UI/development-bundle/ui/i18n/jquery.ui.datepicker-<?php echo $localitzacio ?>.js"></script>
<script>
	$(function() {
		$( ".datepicker" ).datepicker({ 
		      dateFormat: "D, d-m-yy",
		      }, $.datepicker.regional[ "<?php echo $localitzacio ?>" ]);
	});
</script>

</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">
<?php

print("
<div align='right'>
<table border='0'><tr>
<td><font size='6'>Par&agrave;metres de configuraci&oacute;  </font>
</tr></table>
</div>
<hr>");
$consulta="SELECT id, nomcentre, adrecacentre, cpcentre, poblaciocentre, telfcentre, director, nomdirector, sexdirector, cursacademic, datainicicurs, webcentre, emailcentre, remitentSMS, proveidorSMS, identificSMSLlNet, passwdSMSLlNet, identificSMSDinahosting, passwdSMSDinahosting, capdes, nomcapdes, sexcapdes, coordbtx, nomcoordbtx, sexcoordbtx, nom_cc_alumne, sex_cc_alumne, nom_cc_profe, sex_cc_profe, nom_cc_pare, sex_cc_pare, sms_auto, datainici2T, datainici3T, retards_ESO, reset_ESO, retards_BTX, reset_BTX, max_file_size, max_photo_size FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
// echo "<p> consulta: $consulta </p>\n";
$conjunt_resultant=mysql_query($consulta, $connect);// OR die(mysql_error());
$fila=mysql_fetch_object($conjunt_resultant);
mysql_free_result($conjunt_resultant);
print("<table border='0' width='100%'><tr><td width='5%'>&nbsp;</td><td width='90%'>");
print("<fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'>");
print("<table border='0' width='100%'><tr><td valign='top'>");
print("<form name='params' method='post' action='$PHP_SELF?idsess=$idsess' onSubmit='document.forms.params.gravargeneral.value=\"$fila->id\";'>");
print("<table border='0'>");
print("<tr><td width='20%'><input type='hidden' name='gravargeneral' value=''><input type='submit' value='Gravar'></td><td width='80%'><font size='+1'>Paràmetres configuració generals</font></td></tr>");
print("<tr><td align='right'>Nom centre: </td><td><input type='text' name='nomcentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.nomcentr.value='".addslashes($fila->nomcentre)."';</script></td></tr>");
print("<tr><td align='right'>Adreça centre: </td><td><input type='text' name='adrecacentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.adrecacentr.value='".addslashes($fila->adrecacentre)."';</script></td></tr>");
print("<tr><td align='right'>Codi Postal centre: </td><td><input type='text' name='cpcentr' size='7' maxlength='7' value=''><script language='JavaScript'>document.forms.params.cpcentr.value='".addslashes($fila->cpcentre)."';</script></td></tr>");
print("<tr><td align='right'>Població centre: </td><td><input type='text' name='poblaciocentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.poblaciocentr.value='".addslashes($fila->poblaciocentre)."';</script></td></tr>");
print("<tr><td align='right'>Tel&egrave;fon centre: </td><td><input type='text' name='telfcentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.telfcentr.value='".addslashes($fila->telfcentre)."';</script></td></tr>");
// director
print("<tr>");
  print("<td align='right'>");
  print("<label for='director_list'>Director: </label>");
  print("</td><td>\n");
  print("<select name='director_list' id='director_list' onChange='sel_professor(\"director_list\", \"director\", \"nomdirector\");'>");
  print("<option value='' ".(($director=='')?" selected":"")."> </option> \n");
  $consulta1="SELECT usuari, nomreal FROM $bdtutoria.$tbl_prefix"."usu_profes order by usuari asc";
  $conjunt_resultant1=mysql_query($consulta1, $connect);
  while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    print("<option value='$fila1[0]|$fila1[1]' ".(($fila->director==$fila1[0])?" selected":"").">$fila1[0]</option> \n");
  }
  mysql_free_result($conjunt_resultant1);
  print("</select>\n");
  echo "<input type='hidden' name='director' id='director' value='$fila->director'>\n";
// print("<td align='right'>Nom director/a: </td>");
print("<input type='text' name='nomdirector' id='nomdirector' maxlength='50' value=''><script language='JavaScript'>document.forms.params.nomdirector.value='".addslashes($fila->nomdirector)."';</script>");
print("&nbsp; <select name='sexdirector'><option ".(($fila->sexdirector == 'H')?'selected':'').">H</option><option ".(($fila->sexdirector == 'D')?'selected':'').">D</option></select></td></tr>");
// cap d'estudis
print("
	<tr>");
  print("<td align='right'>");
  print("<label for='capdes_list'>Cap d'estudis: </label>");
  print("</td><td>\n");
  print("<select name='capdes_list' id='capdes_list' onChange='sel_professor(\"capdes_list\", \"capdes\", \"nomcapdes\");'>");
  print("<option value='' ".(($capdes=='')?" selected":"")."> </option> \n");
  $consulta1="SELECT usuari, nomreal FROM $bdtutoria.$tbl_prefix"."usu_profes order by usuari asc";
  $conjunt_resultant1=mysql_query($consulta1, $connect);
  while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    print("<option value='$fila1[0]|$fila1[1]' ".(($fila->capdes==$fila1[0])?" selected":"").">$fila1[0]</option> \n");
  }
  mysql_free_result($conjunt_resultant1);
  print("</select>\n");
  echo "<input type='hidden' name='capdes' id='capdes' value='$fila->capdes'>\n";
// 		<td align='right'>Nom cap d'estudis: </td>
// 		<td>
print("			<input type='text' name='nomcapdes' id='nomcapdes' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomcapdes.value='".addslashes($fila->nomcapdes)."';
				</script>
");
print("
			&nbsp; 
			<select name='sexcapdestudis'>
				<option ".(($fila->sexcapdes == 'H')?'selected':'').">H</option>
				<option ".(($fila->sexcapdes == 'D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
// Coordinador batxillerat
print("
	<tr>");
  print("<td align='right'>");
  print("<label for='capdes_list'>Coordinador de batxillerat: </label>");
  print("</td><td>\n");
  print("<select name='coordbtx_list' id='coordbtx_list' onChange='sel_professor(\"coordbtx_list\", \"coordbtx\", \"nomcoordbtx\");'>");
  print("<option value='' ".(($coordbtx=='')?" selected":"")."> </option> \n");
  $consulta1="SELECT usuari, nomreal FROM $bdtutoria.$tbl_prefix"."usu_profes order by usuari asc";
  $conjunt_resultant1=mysql_query($consulta1, $connect);
  while($fila1=mysql_fetch_row($conjunt_resultant1)) {
    print("<option value='$fila1[0]|$fila1[1]' ".(($fila->coordbtx==$fila1[0])?" selected":"").">$fila1[0]</option> \n");
  }
  mysql_free_result($conjunt_resultant1);
  print("</select>\n");
  echo "<input type='hidden' name='coordbtx' id='coordbtx' value='$fila->coordbtx'>\n";
// 		<td align='right'>Nom coordinador BTX: </td>
// 		<td>
print("			<input type='text' name='nomcoordbtx' id='nomcoordbtx' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomcoordbtx.value='".addslashes($fila->nomcoordbtx)."';
				</script>
");
print("
			&nbsp; 
			<select name='sexcoordbtx'>
				<option ".(($fila->sexcoordbtx == 'H')?'selected':'').">H</option>
				<option ".(($fila->sexcoordbtx == 'D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
// cc alumne
print("
	<tr>
		<td align='right'>Nom alumne c.c.: </td>
		<td>
			<input type='text' name='nomccalumne' size='50' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomccalumne.value='".addslashes($fila->nom_cc_alumne)."';
				</script>
");
print("
			&nbsp; 
			<select name='sexccalumne'>
				<option ".(($fila->sex_cc_alumne == 'H')?'selected':'').">H</option>
				<option ".(($fila->sex_cc_alumne == 'D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
// cc profe
print("
	<tr>
		<td align='right'>Nom profe c.c.: </td>
		<td>
			<input type='text' name='nomccprofe' size='50' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomccprofe.value='".addslashes($fila->nom_cc_profe)."';
				</script>
");
print("
			&nbsp; 
			<select name='sexccprofe'>
				<option ".(($fila->sex_cc_profe == 'H')?'selected':'').">H</option>
				<option ".(($fila->sex_cc_profe == 'D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
// cc pare
print("
	<tr>
		<td align='right'>Nom pare c.c.: </td>
		<td>
			<input type='text' name='nomccpare' size='50' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomccpare.value='".addslashes($fila->nom_cc_pare)."';
				</script>
");
print("
			&nbsp; 
			<select name='sexccpare'>
				<option ".(($fila->sex_cc_pare == 'H')?'selected':'').">H</option>
				<option ".(($fila->sex_cc_pare == 'D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
print("<tr><td align='right'>Curs acad&egrave;mic: </td><td><input type='text' name='cursacademi' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.cursacademi.value='".addslashes($fila->cursacademic)."';</script></td></tr>\n");
// print("<tr><td align='right'>Data inici curs: </td><td><input type='text' name='datainicicurs' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila[9])].", ".date('j-n-Y',$fila[9])."' onClick='camp=event.target || event.srcElement; alert(this.form.name + \".\" + camp.name); blur(); obreCalendari(0,0, this.form.name + \".\" + camp.name);'></td></tr>\n");
// print("<tr><td align='right'>Data inici 2T: </td><td><input type='text' name='datainici2T' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila[29])].", ".date('j-n-Y',$fila[29])."' onClick='camp=event.target || event.srcElement; alert(this.form.name + \".\" + camp.name); blur(); alert(this.form.name + \".\" + camp.name); obreCalendari(0,0, this.form.name + \".\" + camp.name);'></td></tr>\n");
// print("<tr><td align='right'>Data inici 3T: </td><td><input type='text' name='datainici3T' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila[30])].", ".date('j-n-Y',$fila[30])."' onClick='camp=event.target || event.srcElement; blur(); obreCalendari(0,0, this.form.name + \".\" + camp.name);'></td></tr>\n");
print("<tr><td align='right'>Data inici curs:</td><td><input type='text' name='datainicicurs' class='datepicker' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila->datainicicurs)].", ".date('j-n-Y',$fila->datainicicurs)."'></td></tr>\n");
print("<tr><td align='right'>Data inici 2T:</td><td><input type='text' name='datainici2T' class='datepicker' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila->datainici2T)].", ".date('j-n-Y',$fila->datainici2T)."'></td></tr>\n");
print("<tr><td align='right'>Data inici 3T:</td><td><input type='text' name='datainici3T' class='datepicker' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila->datainici3T)].", ".date('j-n-Y',$fila->datainici3T)."'></td></tr>\n");
print("<tr><td align='right'>Web centre:</td><td><input type='text' name='webcentr' size='50' maxlength='50' value=''></td></tr>
<script language='JavaScript'>document.forms.params.webcentr.value='".addslashes($fila->webcentre)."';</script>\n");
print("<tr><td align='right'>Email centre:</td><td><input type='text' name='emailcentr' size='50' maxlength='50' value=''></td></tr>
<script language='JavaScript'>document.forms.params.emailcentr.value='".addslashes($fila->emailcentre)."';</script>");
print("<tr><td align='right'>Retards ESO:</td><td><input type='text' name='retards_ESO' size='10' maxlength='10' value='$fila->retards_ESO'> Reset trimestral <input type='checkbox' name='reset_ESO' value='1'".(($fila->reset_ESO=="1")?" checked":"")."> </td></tr>");
print("<tr><td align='right'>Retards BTX:</td><td><input type='text' name='retards_BTX' size='10' maxlength='10' value='$fila->retards_BTX'> Reset trimestral <input type='checkbox' name='reset_BTX' value='1'".(($fila->reset_BTX=="1")?" checked":"")."> </td></tr>");
print("<tr><td align='right'>Tamany màxim import:</td><td><input type='text' name='max_file_size' size='10' maxlength='10' value='$fila->max_file_size'> </td></tr>");
print("<tr><td align='right'>Tamany màxim fotos:</td><td><input type='text' name='max_photo_size' size='10' maxlength='10' value='$fila->max_photo_size'> </td></tr>");
print("<input type='hidden' name='esborrarlogo' value=''>");
print("</table></form>");
print("</td><td valign='top'>");
print("<br><br>Logo centre:<br><br><img src='$logocentre'><br><br>");
print("<a href='' title='Canviar el logo' onClick='carregaLogoCentre(); return false;'>Canviar</a> ");
if(file_exists("$dirfitxers/logocentre.jpg")) print("<a href='' title='Esborra el logo' onClick='document.params.esborrarlogo.value=\"si\"; document.params.submit(); return false;'> Eliminar<a>");
print("</td></tr></table>");
print("</fieldset><br>");

// /////// SMS ////////
print("<form name='paramssms' method='post' action='$PHP_SELF?idsess=$idsess' onSubmit='document.forms.paramssms.gravarsms.value=\"$fila->id\";'>");
print("<fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'>");
print("<table width='100%' border='0'>");
print("<tr><td width='20%'><input type='hidden' name='gravarsms' value=''><input type='submit' value='Gravar'></td><td width='80%'><font size='+1'>Paràmetres configuració SMS</font></td></tr>");
print("<tr><td align='right' valign='top'>Nom remitent*: </td><td><input type='text' name='nomremSMS' size='11' maxlength='11' value=''><script language='JavaScript'>document.forms.paramssms.nomremSMS.value='".addslashes($fila->remitentSMS)."';</script><span style='font-size:10'>*(màx. 11 cars. sense espais. Si es deixa buit, el remitent sera el nom de l'usuari que envia l'SMS)</span></td></tr>");
print("</table>");
print("<table width='100%' border='0'>");
print("<tr><td width='30%'><b>Prove&iuml;dor</b></td><td width='10%'><b>Actiu?</b></td><td width='20%'><b>Identificador</b></td><td width='20%'><b>Contrasenya</b></td><td width='20%'><b>Saldo</b></td></tr>");
print("<tr><td>Lleida Net (<span style='font-size:10; color:blue' title='http://www.lleida.net' onClick='alert(\"Info: http://www.lleida.net\");'>Info</span>)</td><td><input type='radio' name='actiuSMS' value='LleidaNet'".(($fila->proveidorSMS=="LleidaNet")?" checked":"")."></td><td><input type='text' name='identificSMSLlNet' size='20' maxlength='50' value=''><script language='JavaScript'>document.forms.paramssms.identificSMSLlNet.value='".addslashes($fila->identificSMSLlNet)."';</script></td><td><input type='text' name='passwdSMSLlNet' size='20' maxlength='50' value=''><script language='JavaScript'>document.forms.paramssms.passwdSMSLlNet.value='".addslashes($fila->passwdSMSLlNet)."';</script></td><td>".saldoSMSLleidaNet()."</td></tr>");
print("<tr><td>DinaHosting (<span style='font-size:10; color:blue' title='http://www.dinahosting.com/es/tienda/software/sms' onClick='alert(\"Info: http://www.dinahosting.com/es/tienda/software/sms\");'>Info</span>)</td><td><input type='radio' name='actiuSMS' value='DinaHosting'".(($fila->proveidorSMS=="DinaHosting")?" checked":"")."></td><td><input type='text' name='identificSMSDinahosting' size='20' maxlength='50' value=''><script language='JavaScript'>document.forms.paramssms.identificSMSDinahosting.value='".addslashes($fila->identificSMSDinahosting)."';</script></td><td><input type='text' name='passwdSMSDinahosting' size='20' maxlength='50' value=''><script language='JavaScript'>document.forms.paramssms.passwdSMSDinahosting.value='".addslashes($fila->passwdSMSDinahosting)."';</script></td><td>".saldoSMSDinaHosting()." cr&egrave;dits</td></tr>");
print("<tr><td>&nbsp;</td><td><input type='radio' name='actiuSMS' value='cap'".(($fila->proveidorSMS=="cap")?" checked":"")."> Cap</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>");
print("<tr><td>SMS automàtics apercebiments</td><td><input type='checkbox' name='sms_auto' value='1'".(($fila->sms_auto=="1")?" checked":"")."></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>");


print("</table>");
print("</form>");
print("</fieldset>");

print("</td><td width='5%'>&nbsp;</td></tr></table>");

?>
<hr>
</body>
</html>
