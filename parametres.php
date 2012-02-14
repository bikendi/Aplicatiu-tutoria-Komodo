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
}
else if(isset($MAX_FILE_SIZE)) {
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
    $datinicurs=split(' ', $datainicicurs);
    $dic=split('-', $datinicurs[1]);
    $dictimestamp=mktime(0,0,0,$dic[1],$dic[0],$dic[2],-1);
    $datini2T=split(' ', $datainici2T);
    $di2T=split('-', $datini2T[1]);
    $di2Ttimestamp=mktime(0,0,0,$di2T[1],$di2T[0],$di2T[2],-1);
    $datini3T=split(' ', $datainici3T);
    $di3T=split('-', $datini3T[1]);
    $di3Ttimestamp=mktime(0,0,0,$di3T[1],$di3T[0],$di3T[2],-1);
    
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."parametres SET nomcentre='".addslashes($nomcentr)."', adrecacentre='".addslashes($adrecacentr)."', cpcentre='".addslashes($cpcentr)."', poblaciocentre='".addslashes($poblaciocentr)."', telfcentre='".addslashes($telfcentr)."', nomdirector='".addslashes($nomdirecto)."', sexdirector='".addslashes($sexdirecto)."', nomcapdes='".addslashes($nomcapdestudis)."', sexcapdes='".addslashes($sexcapdestudis)."', nomcoordbtx='".addslashes($nomcoordbatx)."', sexcoordbtx='".addslashes($sexcoordbatx)."', nom_cc_alumne='".addslashes($nomccalumne)."', sex_cc_alumne='".addslashes($sexccalumne)."', nom_cc_profe='".addslashes($nomccprofe)."', sex_cc_profe='".addslashes($sexccprofe)."', nom_cc_pare='".addslashes($nomccpare)."', sex_cc_pare='".addslashes($sexccpare)."', cursacademic='".addslashes($cursacademi)."', datainicicurs='$dictimestamp',
	datainici2T='$di2Ttimestamp', datainici3T='$di3Ttimestamp',
	webcentre='".addslashes($webcentr)."', emailcentre='".addslashes($emailcentr)."', 
	retards_ESO='$retard_ESO',
	reset_ESO='$rst_ESO',
	retards_BTX='$retard_BTX',
	reset_BTX='$rst_BTX'
	where id='$gravargeneral'";
//	echo "<p>Query: $consulta</p>\n";
	mysql_query($consulta, $connect);
}
if (isset($gravarsms)&& $gravarsms==$fila[0]) {
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."parametres SET remitentSMS='".addslashes($nomremSMS)."', proveidorSMS='".addslashes($actiuSMS)."', identificSMSLlNet='".addslashes($identificSMSLlNet)."', passwdSMSLlNet='".addslashes($passwdSMSLlNet)."' , identificSMSDinahosting='".addslashes($identificSMSDinHost)."', passwdSMSDinahosting='".addslashes($passwdSMSDinHost)."', sms_auto='$smsauto' where id='$gravarsms'";
//	echo "<p> Query: $consulta </p> \n";
	mysql_query($consulta, $connect);
}
mysql_free_result($conjunt_resultant);
@include("enviaSMS.php");
?>
<script language='JavaScript' src='comu.js'></script>
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
  write("<input type='hidden' name='MAX_FILE_SIZE' value='25000'>");
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
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">
<?php
print("
<div align='right'>
<table border='0'><tr>
<td><font size='6'>Par&agrave;metres de configuraci&oacute;&nbsp; &nbsp; </font>
</tr></table>
</div>
<hr>");
$consulta="SELECT id, nomcentre, adrecacentre, cpcentre, poblaciocentre, telfcentre, nomdirector, sexdirector, cursacademic, datainicicurs, webcentre, emailcentre, remitentSMS, proveidorSMS, identificSMSLlNet, passwdSMSLlNet, identificSMSDinahosting, passwdSMSDinahosting, nomcapdes, sexcapdes, nomcoordbtx, sexcoordbtx, nom_cc_alumne, sex_cc_alumne, nom_cc_profe, sex_cc_profe, nom_cc_pare, sex_cc_pare, sms_auto, datainici2T, datainici3T, retards_ESO, reset_ESO, retards_BTX, reset_BTX FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
mysql_free_result($conjunt_resultant);
print("<table border='0' width='100%'><tr><td width='5%'>&nbsp;</td><td width='90%'>");
print("<fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'>");
print("<table border='0' width='100%'><tr><td valign='top'>");
print("<form name='params' method='post' action='$PHP_SELF?idsess=$idsess' onSubmit='document.forms.params.gravargeneral.value=\"$fila[0]\";'>");
print("<table border='0'>");
print("<tr><td width='20%'><input type='hidden' name='gravargeneral' value=''><input type='submit' value='Gravar'></td><td width='80%'><font size='+1'>Paràmetres configuració generals</font></td></tr>");
print("<tr><td align='right'>Nom centre: &nbsp;</td><td><input type='text' name='nomcentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.nomcentr.value='".addslashes($fila[1])."';</script></td></tr>");
print("<tr><td align='right'>Adreça centre: &nbsp;</td><td><input type='text' name='adrecacentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.adrecacentr.value='".addslashes($fila[2])."';</script></td></tr>");
print("<tr><td align='right'>Codi Postal centre: &nbsp;</td><td><input type='text' name='cpcentr' size='7' maxlength='7' value=''><script language='JavaScript'>document.forms.params.cpcentr.value='".addslashes($fila[3])."';</script></td></tr>");
print("<tr><td align='right'>Població centre: &nbsp;</td><td><input type='text' name='poblaciocentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.poblaciocentr.value='".addslashes($fila[4])."';</script></td></tr>");
print("<tr><td align='right'>Tel&egrave;fon centre: &nbsp;</td><td><input type='text' name='telfcentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.telfcentr.value='".addslashes($fila[5])."';</script></td></tr>");
// director
print("<tr><td align='right'>Nom director/a: &nbsp;</td><td><input type='text' name='nomdirecto' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.nomdirecto.value='".addslashes($fila[6])."';</script>");
print("&nbsp; <select name='sexdirecto'><option ".(($fila[7]=='H')?'selected':'').">H</option><option ".(($fila[7]=='D')?'selected':'').">D</option></select></td></tr>");
// cap d'estudis
print("
	<tr>
		<td align='right'>Nom cap d'estudis: &nbsp;</td>
		<td>
			<input type='text' name='nomcapdestudis' size='50' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomcapdestudis.value='".addslashes($fila[18])."';
				</script>
");
print("
			&nbsp; 
			<select name='sexcapdestudis'>
				<option ".(($fila[19]=='H')?'selected':'').">H</option>
				<option ".(($fila[19]=='D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
// Coordinador batxillerat
print("
	<tr>
		<td align='right'>Nom coordinador BTX: &nbsp;</td>
		<td>
			<input type='text' name='nomcoordbatx' size='50' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomcoordbatx.value='".addslashes($fila[20])."';
				</script>
");
print("
			&nbsp; 
			<select name='sexcoordbatx'>
				<option ".(($fila[21]=='H')?'selected':'').">H</option>
				<option ".(($fila[21]=='D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
// cc alumne
print("
	<tr>
		<td align='right'>Nom alumne c.c.: &nbsp;</td>
		<td>
			<input type='text' name='nomccalumne' size='50' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomccalumne.value='".addslashes($fila[22])."';
				</script>
");
print("
			&nbsp; 
			<select name='sexccalumne'>
				<option ".(($fila[23]=='H')?'selected':'').">H</option>
				<option ".(($fila[23]=='D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
// cc profe
print("
	<tr>
		<td align='right'>Nom profe c.c.: &nbsp;</td>
		<td>
			<input type='text' name='nomccprofe' size='50' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomccprofe.value='".addslashes($fila[24])."';
				</script>
");
print("
			&nbsp; 
			<select name='sexccprofe'>
				<option ".(($fila[25]=='H')?'selected':'').">H</option>
				<option ".(($fila[25]=='D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
// cc pare
print("
	<tr>
		<td align='right'>Nom pare c.c.: &nbsp;</td>
		<td>
			<input type='text' name='nomccpare' size='50' maxlength='50' value=''>
				<script language='JavaScript'>document.forms.params.nomccpare.value='".addslashes($fila[26])."';
				</script>
");
print("
			&nbsp; 
			<select name='sexccpare'>
				<option ".(($fila[27]=='H')?'selected':'').">H</option>
				<option ".(($fila[27]=='D')?'selected':'').">D</option>
			</select>
		</td>
	</tr>");
print("<tr><td align='right'>Curs acad&egrave;mic: &nbsp;</td><td><input type='text' name='cursacademi' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.cursacademi.value='".addslashes($fila[8])."';</script></td></tr>\n");
print("<tr><td align='right'>Data inici curs: &nbsp;</td><td><input type='text' name='datainicicurs' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila[9])].", ".date('j-n-Y',$fila[9])."' onClick='camp=event.target || event.srcElement; alert(this.form.name + \".\" + camp.name); blur(); obreCalendari(0,0, this.form.name + \".\" + camp.name);'></td></tr>\n");
print("<tr><td align='right'>Data inici 2T: &nbsp;</td><td><input type='text' name='datainici2T' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila[29])].", ".date('j-n-Y',$fila[29])."' onClick='camp=event.target || event.srcElement; alert(this.form.name + \".\" + camp.name); blur(); alert(this.form.name + \".\" + camp.name); obreCalendari(0,0, this.form.name + \".\" + camp.name);'></td></tr>\n");
print("<tr><td align='right'>Data inici 3T: &nbsp;</td><td><input type='text' name='datainici3T' size='13' maxlength='15' value='".$nomDiaSem[date('w',$fila[30])].", ".date('j-n-Y',$fila[30])."' onClick='camp=event.target || event.srcElement; blur(); obreCalendari(0,0, this.form.name + \".\" + camp.name);'></td></tr>\n");
print("<tr><td align='right'>Web centre: &nbsp;</td><td><input type='text' name='webcentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.webcentr.value='".addslashes($fila[10])."';</script>\n");
print("<tr><td align='right'>Email centre: &nbsp;</td><td><input type='text' name='emailcentr' size='50' maxlength='50' value=''><script language='JavaScript'>document.forms.params.emailcentr.value='".addslashes($fila[11])."';</script>");
print("<tr><td align='right'>Retards ESO</td><td><input type='text' name='retard_ESO' size='10' maxlength='10' value='$fila[31]'> Reset trimestral <input type='checkbox' name='rst_ESO' value='1'".(($fila[32]=="1")?" checked":"").">");
print("<tr><td align='right'>Retards BTX</td><td><input type='text' name='retard_BTX' size='10' maxlength='10' value='$fila[33]'> Reset trimestral <input type='checkbox' name='rst_BTX' value='1'".(($fila[34]=="1")?" checked":"").">");
print("<input type='hidden' name='esborrarlogo' value=''>");
print("</table></form>");
print("</td><td valign='top'>");
print("<br><br>Logo centre:<br><br><img src='$logocentre'><br><br>");
print("<a href='' title='Canviar el logo' onClick='carregaLogoCentre(); return false;'>Canviar</a> ");
if(file_exists("$dirfitxers/logocentre.jpg")) print("<a href='' title='Esborra el logo' onClick='document.params.esborrarlogo.value=\"si\"; document.params.submit(); return false;'> Eliminar<a>");
print("</td></tr></table>");
print("</fieldset><br>");

// /////// SMS ////////
print("<form name='paramssms' method='post' action='$PHP_SELF?idsess=$idsess' onSubmit='document.forms.paramssms.gravarsms.value=\"$fila[0]\";'>");
print("<fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'>");
print("<table width='100%' border='0'>");
print("<tr><td width='20%'><input type='hidden' name='gravarsms' value=''><input type='submit' value='Gravar'></td><td width='80%'><font size='+1'>Paràmetres configuració SMS</font></td></tr>");
print("<tr><td align='right' valign='top'>Nom remitent*: &nbsp;</td><td><input type='text' name='nomremSMS' size='11' maxlength='11' value=''><script language='JavaScript'>document.forms.paramssms.nomremSMS.value='".addslashes($fila[12])."';</script><span style='font-size:10'>*(màx. 11 cars. sense espais. Si es deixa buit, el remitent sera el nom de l'usuari que envia l'SMS)</span></td></tr>");
print("</table>");
print("<table width='100%' border='0'>");
print("<tr><td width='30%'><b>Prove&iuml;dor</b></td><td width='10%'><b>Actiu?</b></td><td width='20%'><b>Identificador</b></td><td width='20%'><b>Contrasenya</b></td><td width='20%'><b>Saldo</b></td></tr>");
print("<tr><td>Lleida Net (<span style='font-size:10; color:blue' title='http://www.lleida.net' onClick='alert(\"Info: http://www.lleida.net\");'>Info</span>)</td><td><input type='radio' name='actiuSMS' value='LleidaNet'".(($fila[13]=="LleidaNet")?" checked":"")."></td><td><input type='text' name='identificSMSLlNet' size='20' maxlength='50' value=''><script language='JavaScript'>document.forms.paramssms.identificSMSLlNet.value='".addslashes($fila[14])."';</script></td><td><input type='text' name='passwdSMSLlNet' size='20' maxlength='50' value=''><script language='JavaScript'>document.forms.paramssms.passwdSMSLlNet.value='".addslashes($fila[15])."';</script></td><td>".saldoSMSLleidaNet()."</td></tr>");
print("<tr><td>DinaHosting (<span style='font-size:10; color:blue' title='http://www.dinahosting.com/es/tienda/software/sms' onClick='alert(\"Info: http://www.dinahosting.com/es/tienda/software/sms\");'>Info</span>)</td><td><input type='radio' name='actiuSMS' value='DinaHosting'".(($fila[13]=="DinaHosting")?" checked":"")."></td><td><input type='text' name='identificSMSDinHost' size='20' maxlength='50' value=''><script language='JavaScript'>document.forms.paramssms.identificSMSDinHost.value='".addslashes($fila[16])."';</script></td><td><input type='text' name='passwdSMSDinHost' size='20' maxlength='50' value=''><script language='JavaScript'>document.forms.paramssms.passwdSMSDinHost.value='".addslashes($fila[17])."';</script></td><td>".saldoSMSDinaHosting()." cr&egrave;dits</td></tr>");
print("<tr><td>&nbsp;</td><td><input type='radio' name='actiuSMS' value='cap'".(($fila[13]=="cap")?" checked":"")."> Cap</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>");
print("<tr><td>SMS automàtics apercebiments</td><td><input type='checkbox' name='smsauto' value='1'".(($fila[28]=="1")?" checked":"")."></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>");


print("</table>");
print("</form>");
print("</fieldset>");

print("</td><td width='5%'>&nbsp;</td></tr></table>");
?>
<hr>
</body>
</html>