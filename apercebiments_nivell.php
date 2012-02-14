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
@include("comu.php");
@include("comu.js.php");
panyacces("Tutor");

echo '<link rel="stylesheet" type="text/css" href="css/comu.css" />';

print(' 
<script language="JavaScript">
function genera_apercebiment_pdf(nal, tipus, quantitat, motiu)
{
 window.focus();
 opt = "resizable=1,scrollbars=0,width=600,height=400,left=5,top=60";

 finestra=window.open("pdf_apercebiment_faltes.php?idsess='.$idsess .'&nal="+nal+"&tipus="+tipus+"&quantitat="+quantitat+"&motiu="+motiu, "finestra", opt);
}

function genera_acta_cc_pdf(nal, data, motiu, acords)
{
 window.focus();
 opt = "resizable=1,scrollbars=0,width=600,height=400,left=5,top=60";

 finestra=window.open("pdf_acta_cc.php?idsess='.$idsess .'&nal="+nal+"&data="+data+"&motiu="+motiu+"&acords="+acords, "finestra", opt);
}

function genera_sancio_dt_pdf(nal, motiu, data, hora)
{
	// alert( "pdf_sancio_dt.php?idsess='.$idsess .'&nal="+nal+"&motiu="+motiu+"&data="+data+"&hora="+hora );
 window.focus();
 opt = "resizable=1,scrollbars=0,width=600,height=400,left=5,top=60";

 finestra=window.open("pdf_sancio_dt.php?idsess='.$idsess .'&nal="+nal+"&motiu="+motiu+"&data="+data+"&hora="+hora, "finestra", opt);
}

</script>
');

// nou apercebiment ///////////
if(isset($nouapercebiment)&&$nouapercebiment!='') {
  	$dia=mktime(0,0,0,date('n',$datatimestamp),date('d',$datatimestamp),date('Y',$datatimestamp),-1);
	//echo "<p>tipus apercebiment: $tipusapercebiment </p> \n";
  	if( $tipusapercebiment == 'F' || $tipusapercebiment == 'R' || $tipusapercebiment == 'REC' )
  		$query_tipus = ", quantitat=$quantnouapercebiment";
  	$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."apercebiments SET refalum='$nouapercebiment', datahora='$dia', obsv='".rawurlencode(stripslashes($textnouapercebiment))."', incidencia='$tipusapercebiment'". $query_tipus;
 	//echo "<p>Consulta: $consulta</p>\n";
  	mysql_query($consulta, $connect);
	// generem el pdf
	if( $tipusapercebiment == 'CC' )
   		//echo "<script language='JavaScript'> genera_acta_cc_pdf( '$nouapercebiment', '". htmlspecialchars($textnouapercebiment_1, ENT_QUOTES) ."', '". htmlspecialchars($textnouapercebiment_2, ENT_QUOTES) ."' );</script>";
   		echo "<script language='JavaScript'> genera_acta_cc_pdf( '$nouapercebiment', '". addslashes($textnouapercebiment_3) ."', '". addslashes($textnouapercebiment_1) ."', '". addslashes($textnouapercebiment_2) ."' );</script>";
	elseif( $tipusapercebiment == 'DT' || $tipusapercebiment == 'REC' )
   		echo "<script language='JavaScript'> genera_sancio_dt_pdf( '$nouapercebiment', '". addslashes($textnouapercebiment_1) ."', '". addslashes($textnouapercebiment_2) ."', '". addslashes($textnouapercebiment_3) ."' );</script>";
  	elseif( $tipusapercebiment != 'R' || $quantnouapercebiment >= 15 )
		echo "<script language='JavaScript'> genera_apercebiment_pdf( '$nouapercebiment', '$tipusapercebiment', '$quantnouapercebiment', '". addslashes($textnouapercebiment_1) ."' );</script>";

	// cerquem e-mail, sms i nom de l'alumne
	$consulta = "SELECT telfSMS, email, identificador FROM $bdtutoria.$tbl_prefix"."pares WHERE refalumne='$nouapercebiment'";
	$conjunt_resultant = mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	$consulta = "SELECT COGNOM_ALU, COGNOM2_AL, NOM_ALUM, PLA_ESTUDI, CURS, GRUP FROM $bdtutoria.$tbl_prefix"."Estudiants WHERE numero_mat='$nouapercebiment'";
	//echo "<p>Consulta: $consulta</p>\n";
	$conjunt_resultant = mysql_query($consulta, $connect);
	$fila2=mysql_fetch_row($conjunt_resultant);
	$pares = "$fila[2]|Pares de $fila2[0] $fila2[1], $fila2[2] ($fila2[4]$fila2[3]$fila2[5])";
	//print_r($fila);

	// enviem e-mail
	if( !empty($fila[1]) ) {
		$email_pares = $fila[1];
		$from = '"'. $sess_nomreal .'" <'. $sess_user .'@iesmediterrania.cat>';
		$message = "El seu fill/a $fila2[2] ha rebut un apercebiment que hauria de retornar signat.\n\n";
		if( $tipusapercebiment == 'F' ) {
			$message .= "El motiu de l'apercebiment és l'acumulació de ". $quantnouapercebiment ." faltes injustificades.\n\n";
		} elseif( $tipusapercebiment == 'R' ) { // retards
			$message .= "El motiu de l'apercebiment és l'acumulació de ". $quantnouapercebiment ." retards.\n\n";
		} elseif( $tipusapercebiment == 'CC' ) { // comissió de convivència
			$message .= "El motiu de l'apercebiment és la reunió de la comissió de convivència.\n\n";
		} elseif( $tipusapercebiment == 'DT' ) { // sanció dimecres tarda
			$message .= "El seu fill/a haurà de venir ". addslashes($textnouapercebiment_2) . " " . addslashes($textnouapercebiment_3) . " per fer treball comunitari a causa de: ". addslashes($textnouapercebiment_1) .".\n\n";
		} elseif( $tipusapercebiment == 'REC' ) { // sanció dimecres tarda
			$message .= "El motiu de l'apercebiment és una sanció de dimecres tarda per acumulació de ". $quantnouapercebiment ." retards entre classes.\n\n";
//		} elseif( $tipus == 'Ll' ) { // apercebiment lliure
//			$message .= "El motiu de l'apercebiment és ";
		} // fi if tipus
		$message .= "Aquest és un missatge generat automàticament per l'Aplicatiu Tutoria de l'IES Mediterrània. Si desitja més informació posi's en contacte amb el/la tutor/a del seu fill/a.\n";
		$message = wordwrap($message, 70);
		$headers = 'Content-Type: text/plain; charset="utf-8"'."\n" .
						'From: '. $from ."\n".
						'Reply-To: '. $sess_user .'@iesmediterrania.cat' ."\n".
						'X-Mailer: '. $_SERVER['SERVER_NAME'] .'/PHP/' . phpversion(). "\n";
		$headers .= 'MIME-Version: 1.0' . "\n";
		$subject = "IES Mediterrania - Notificació d'apercebiment";
		if( mail($email_pares, $subject, $message, $headers) ) {
    		echo "<p>E-mail enviat a: $email_pares</p>\n";
			$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."comunicacio SET sub=0, de='$sess_user|"."$sess_nomreal', per_a='$pares ($email_pares)', datahora='$datatimestamp', assumpte='Enviat e-mail: ".addslashes($subject)."', contingut='".addslashes($message)."', adjunts='', vist='EnviatE-mail_$sess_user/$datatimestamp'";
    		//echo "<p>Consulta e-mail: $consulta</p>\n";
			mysql_query($consulta, $connect);
		} else
			echo "<p> Resultat e-mail: Error </p> \n";
	} // fi if email no buit
	// enviem SMS
	if( $sms_auto && $enviar_sms ) {
		@include_once("enviaSMS.php");
		if( !empty($fila[0]) ) {
			echo "<p> enviem SMS a: $fila[0] </p>\n";
			$telfSMS = $fila[0];
			switch( $tipusapercebiment ) {
				case 'R':
						$conting = "El seu fill/a $fila2[2] ha acumulat $quantnouapercebiment retards en la seva assistència a classe. Preguem que millori la seva puntualitat.";
						break;
				case 'DT':
						$conting = "El seu fill/a $fila2[2] haurà de venir ". addslashes($textnouapercebiment_2) . " " . addslashes($textnouapercebiment_3) . " per fer treball comunitari";
						break;
				case 'REC':
						$conting = "Per acumulació de $quantnouapercebiment retards entre classes, el seu fill/a $fila2[2] haurà de venir ". addslashes($textnouapercebiment_2) . " " . addslashes($textnouapercebiment_3);
						break;
				default:
						$conting = "El seu fill/a $fila2[2] ha rebut un apercebiment que hauria de retornar signat.";
						break;
			} // fi switch tipus
			//echo "<p> De: $sess_nomreal </p> \n";
			//echo "<p> Contingut: $conting </p> \n";
			//echo "<p> telf: $telfSMS </p> \n";
			$res=enviaSMS($sess_nomreal, $telfSMS, $conting);
			echo "<p> Resultat SMS: $res </p> \n";
			$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."comunicacio SET sub=0, de='$sess_user|"."$sess_nomreal', per_a='$pares ($telfSMS)', datahora='$datatimestamp', assumpte='".((eregi("NOOK", $res)||$res=="Error connexio"||$res=="Error: No configurat")?"Resultat: Enviament Erròni":"Resultat: Enviament OK")."', contingut='".addslashes($conting)."', adjunts='', vist='EnviatSMS_$sess_user/$datatimestamp;$res'";
    		//echo "<p>Consulta sms: $consulta</p>\n";
			mysql_query($consulta, $connect);
		} // fi if telf no buit
	}// fi if sms_auto

} //if(isset($nouapercebiment)&&$nouapercebiment!='')

if(isset($signaapercebiment)&&$signaapercebiment!='') {
  $consulta="UPDATE $bdtutoria.$tbl_prefix"."apercebiments SET signat = TRUE WHERE id='$signaapercebiment'";
  mysql_query($consulta, $connect);
}
if(isset($unsignaapercebiment)&&$unsignaapercebiment!='') {
  $consulta="UPDATE $bdtutoria.$tbl_prefix"."apercebiments SET signat = FALSE WHERE id='$unsignaapercebiment'";
  mysql_query($consulta, $connect);
}

if(isset($eliminaapercebiment)&&$eliminaapercebiment!='') {
  $consulta="DELETE FROM $bdtutoria.$tbl_prefix"."apercebiments WHERE id='$eliminaapercebiment' LIMIT 1";
  mysql_query($consulta, $connect);
}
if(isset($desatextal)&&$desatextal!='') {
	if($desatextal==-1) {
		$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."apercebiments SET refalum='$refal', datahora='-1', obsv='".rawurlencode(stripslashes($textal))."'";
    	mysql_query($consulta, $connect);	
	}
	else {
		$consulta="UPDATE $bdtutoria.$tbl_prefix"."apercebiments SET obsv='".rawurlencode(stripslashes($textal))."' WHERE id='$desatextal'";
		mysql_query($consulta, $connect);
	}
}

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

function obrefinestra(nal, dataI, dataF)
{
 window.focus();
 opt = "resizable=1,scrollbars=0,width=600,height=400,left=5,top=60";
 finestra=window.open("apercebiments_pdf.php?idsess=<?=$idsess?>&nal="+nal+"&dataI="+dataI+"&dataF="+dataF, "finestra", opt);
}

function validacamp() {
   var valor=document.forms.introd1.fil_valor.value.replace(/^ +| +$/g,'');
   if(isNaN(valor)||valor<0) {
      alert("El camp \"filtre\" ha de ser numeric no negatiu!");
      document.forms.introd1.fil_valor.focus();
      return false;
   }
   else return true;
}

function validacamp2() {
   var valor=document.forms.introd1.fil_valor2.value.replace(/^ +| +$/g,'');
   if(isNaN(valor)||valor<0) {
      alert("El camp \"filtre\" ha de ser numeric no negatiu!");
      document.forms.introd1.fil_valor2.focus();
      return false;
   }
   else return true;
}
function nouApercebiment( tipus, nom, refalum, num_incid ) {
	// alert( tipus + " " + refalum + " " + num_incid );
	if ( validacamp() && validacamp2() ) {
		document.forms.introd1.action = document.introd1.action+"#"+refalum;
		document.forms.introd1.nouapercebiment.value = refalum; 
		document.forms.introd1.tipusapercebiment.value = tipus; 
		switch( tipus ) {
			case 'Ll':
				var aux=prompt("Text pel nou apercebiment de "+ nom +":",""); 
				if(aux==null)
					return false;
				document.forms.introd1.textnouapercebiment.value=aux;
				break;
			case 'F':
				var num=prompt("Nombre de faltes");
				if(num == null )
					return false;
				var data=prompt("Data"); 
				if(data == null )
					return false;
				var aux=prompt("Text addicional pel nou apercebiment de "+ nom +":",""); 
				if(aux == null )
					return false;
				if(num=="")
					num = num_incid;
				if(data!="")
					aux="Enviat avís i amonestació per " + num + " faltes el " + data + ". " + aux;
				else
					aux="Enviat avís i amonestació per " + num + " faltes. " + aux;
				document.forms.introd1.quantnouapercebiment.value=num;
				document.forms.introd1.textnouapercebiment.value=aux;
				break;
			case 'R':
				var num=prompt("Nombre de retards"); 
				if(num == null )
					return false;
				var data=prompt("Data"); 
				if(data == null )
					return false;
				var aux=prompt("Text addicional pel nou apercebiment de "+ nom +":",""); 
				if(aux == null )
					return false;
				if(num=="")
					num = num_incid; 
				if(data!="")
					aux="Enviat SMS per " + num + " retards el " + data + ". " + aux;
				else 
					aux="Enviat SMS per " + num + " retards. " + aux; 
				document.forms.introd1.quantnouapercebiment.value=num;
				document.forms.introd1.textnouapercebiment.value=aux; 
				break;
			case 'CC':
				var data=prompt("Data");
				if(data == null )
					return false;
				var aux_1=prompt("Motiu de la comissió de convivència de "+ nom +":",""); 
				if(aux_1 == null )
					return false;
				var aux_2=prompt("Acords de la comissió de convivència de "+ nom +":",""); 
				if(aux_2 == null )
					return false;
				document.forms.introd1.textnouapercebiment.value="Comissió de convivència el "+ data +" per "+ aux_1 + ". Acords: " + aux_2;
				document.forms.introd1.textnouapercebiment_1.value=aux_1;
				document.forms.introd1.textnouapercebiment_2.value=aux_2;
				document.forms.introd1.textnouapercebiment_3.value=data;
				break;
			case 'DT':
				var data=prompt("Data de la sanció");
				if(data == null )
					return false;
				var hora=prompt("Hora de la sanció");
				if(hora == null )
					return false;
				var aux=prompt("Motiu de la sanció de dimecres de "+ nom +":",""); 
				if(aux == null )
					return false;
				document.forms.introd1.textnouapercebiment.value="Avís treball comunitari el "+ data + " " + hora + " per "+ aux;
				document.forms.introd1.textnouapercebiment_1.value=aux;
				document.forms.introd1.textnouapercebiment_2.value=data;
				document.forms.introd1.textnouapercebiment_3.value=hora;
				break;
			case 'REC':
				var num=prompt("Nombre de retards entre classes"); 
				if(num == null )
					return false;
				if(num=="")
					num = num_incid; 
				var data=prompt("Data de la sanció");
				if(data == null )
					return false;
				var hora=prompt("Hora de la sanció");
				if(hora == null )
					return false;
				var aux="acumulació de "+ num +" retards entre classes"; 
				if(aux == null )
					return false;
				document.forms.introd1.textnouapercebiment.value="Avís treball comunitari el "+ data + " " + hora + " per "+ aux;
				document.forms.introd1.quantnouapercebiment.value=num;
				document.forms.introd1.textnouapercebiment_1.value=aux;
				document.forms.introd1.textnouapercebiment_2.value=data;
				document.forms.introd1.textnouapercebiment_3.value=hora;
				break;
			default:
				return false;
				break;
		} // fi switch
		if( confirm ("Enviar SMS a la família?") )
			document.forms.introd1.enviar_sms.value=1;
		else
			document.forms.introd1.enviar_sms.value=0;
		document.forms.introd1.submit(); 
	} // fi valida camps
}
</script>

</head>

<body  text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="horaAra();">

<?php

print("
<div align='right'>
<form name='introd1' method='post' action='$PHP_SELF' onSubmit='return validacamp();'>
<input type='hidden' name='idsess' value='$idsess'>
<input type='hidden' name='nouapercebiment' value=''>
<input type='hidden' name='textnouapercebiment' value=''>
<input type='hidden' name='tipusapercebiment' value=''>
<input type='hidden' name='quantnouapercebiment' value=''>
<input type='hidden' name='textnouapercebiment_1' value=''>
<input type='hidden' name='textnouapercebiment_2' value=''>
<input type='hidden' name='textnouapercebiment_3' value=''>
<input type='hidden' name='signaapercebiment' value=''>
<input type='hidden' name='unsignaapercebiment' value=''>
<input type='hidden' name='eliminaapercebiment' value=''>
<input type='hidden' name='desatextal' value=''>
<input type='hidden' name='refal' value=''>
<input type='hidden' name='textal' value=''>
<input type='hidden' name='enviar_sms' value=0>
<table border='0'>
<tr><td><font size='6'>Apercebiments&nbsp; &nbsp; </font></td>
	<td><input type='button' value='Imprimir' onClick='window.print();'>&nbsp; &nbsp; </td>
	<td valign='top'><b>Des de:</b><br><input type='text' name='dataI' size='13' value='".((isset($dataI))?$dataI:$nomDiaSem[date('w',$datatimestampIniciCurs)].", ".date('j-n-Y',$datatimestampIniciCurs))."' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,0);'></td>
	<td valign='top'><b>Fins:</b><br><input type='text' name='dataF' size='13' value='".((isset($dataF))?$dataF:$nomDiaSem[date('w',$datatimestamp)].", ".date('j-n-Y',$datatimestamp))."' onChange='document.introd1.submit();' onClick='blur(); obreCalendari(0,0,1);'></td>
");


print("
	<td valign='top'>
		<b>Nivell:</b><br>
		<select name='nivell' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'>
  			<option></option><option".(($nivell=='Tots')?" selected":"").">Tots</option>");
// bingen: llista_nivells
do {
	print("
		<option". (($nivell==current($llista_nivells))?" selected":"") .">". current($llista_nivells). "</option>
	");
} while(next($llista_nivells));
print("</select></td>");

print("
	<td valign='top'>
		<b>Grup:</b><br>
		<select name='grup' onChange='if(document.introd1.paginadoranterior) document.introd1.paginadoranterior.value=\"-1\"; if(document.introd1.paginadorseguent) document.introd1.paginadorseguent.value=\"-1\"; document.introd1.submit();'><option></option>
");
// bingen: llista_grups
$permis=privilegis('-', '-', '-');
if( $permis )
	echo "<option".(($grup=='Tots')?" selected":"").">Tots</option> \n";
do {
     $permis=privilegis('-', '-',current($llista_grups));
     if($permis) print("<option".(($grup==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");
   } while(next($llista_grups));

print("</select></td></tr></table></div><hr>");


if (isset($dataI)&&$dataI!=''&&isset($dataF)&&$dataF!=''&&(isset($grup)&&$grup!='')) {
  if(!isset($fil_valor)) $fil_valor=0;
  if(!isset($fil_tincidencia)) $fil_tincidencia='-- res --';
// bingen: rowspan
  print("<table border='0' width='100%'><tr><td rowspan='4' width='7%' align='center'><b>Filtre:</b></td><td width='78%'><input type='checkbox' name='ftipus1'".((isset($ftipus1))?" checked":"").">Selecciona els alumnes que tenen ");
  print("<input type='text' size='1' maxlength='2' name='fil_valor' value='$fil_valor'> ");
  print(" o m&eacute;s "); 
  print("<select name='fil_tincidencia'>");
  print("<option ".(($fil_tincidencia=='-- res --')?'selected':'').">-- res --</option>
          <option ".(($fil_tincidencia=='Faltes injustificades')?'selected':'').">Faltes injustificades</option>
	  <option ".(($fil_tincidencia=='Retards injustificats')?'selected':'').">Retards injustificats</option>
	  <option ".(($fil_tincidencia=='Retards injustificats entre classes')?'selected':'').">Retards injustificats entre classes</option>
	  <option ".(($fil_tincidencia=='Faltes i retards injustificats')?'selected':'').">Faltes i retards injustificats</option>
	  <option ".(($fil_tincidencia=='Expulsions')?'selected':'').">Expulsions</option>");
  print("</select>, o");
  print("</td>");
// bingen: rowspan
  print("<td rowspan='4' width='15%' align='center'>");
  print("<input type='submit' value='Aplica selecci&oacute;'>");
  print("<br>Seleccionats: <span id='nseleccionats'></span>");  
  print("</td></tr>");
  print("<tr><td>");
  print("<input type='checkbox' name='ftipus2'".((isset($ftipus2))?" checked":"").">Selecciona els alumnes amb apercebiments enviats ");
  if(!isset($fil_tapercebiment)) $fil_tapercebiment='-- tots --';
  print("<select name='fil_tapercebiment'>");
  print("<option ".(($fil_tapercebiment=='-- tots --')?'selected':'').">-- tots --</option>");
  foreach( $tipus_apercebiments as $apercebiment )
  		echo "<option ".(($fil_tapercebiment==$apercebiment)?'selected':'').">$apercebiment</option> \n";
  print("</select>");
  if(!isset($fil_signat)) $fil_signat=2;
  print("<select name='fil_signat'>");
  print("<option value=\"2\" ".(($fil_signat==2)?'selected':'').">-- tots --</option>
          <option value=\"1\" ".(($fil_signat==1)?'selected':'').">Signats</option>
	  <option value=\"0\" ".(($fil_signat==0)?'selected':'').">No signats</option>");
  print("</select>");
  print(", o");
  print("</td></tr>");
  print("<tr><td>");
  print("<input type='checkbox' name='ftipus3'".((isset($ftipus3))?" checked":"").">Selecciona els alumnes amb contingut en les anotacions textuals.");
  print("</td></tr>");
// bingen
  if(!isset($fil_valor2)) $fil_valor2=0;
  if(!isset($fil_tincidencia2)) $fil_tincidencia2='-- res --';
  print("<tr><td>");
  print("<input type='checkbox' name='ftipus4'".((isset($ftipus4))?" checked":"").">Selecciona els alumnes amb ");
  print("<input type='text' size='1' maxlength='2' name='fil_valor2' value='$fil_valor2'> ");
  print(" o m&eacute;s "); 
  print("<select name='fil_tincidencia2'>");
  print("
  		<option ".(($fil_tincidencia2=='-- res --')?'selected':'').">-- res --</option>
  		<option ".(($fil_tincidencia2=='Faltes injustificades')?'selected':'').">Faltes injustificades</option>
  		<option ".(($fil_tincidencia2=='Retards injustificats')?'selected':'').">Retards injustificats</option>
  		<option ".(($fil_tincidencia2=='Retards injustificats entre classes')?'selected':'').">Retards injustificats entre classes</option>
  		");
  print("</select> sense notificar");
  print("</td></tr>");
//bingen
  print("</table>");
  print("<hr>");
  
	$consulta = "SELECT numero_mat FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE  1 $where_nivell $where_grup ORDER BY pla_estudi DESC, curs, grup, cognom_alu, cognom2_al, nom_alum"; // bingen

  $conjunt_resultant=mysql_query($consulta, $connect);
  $datI=split(' ', $dataI);
  $daI=split('-', $datI[1]);
  $datatimestampI=mktime(0,0,0,$daI[1],$daI[0],$daI[2],-1);
  $datF=split(' ', $dataF);
  $daF=split('-', $datF[1]);
  $datatimestampF=mktime(0,0,0,$daF[1],$daF[0],$daF[2],-1);
 
  $compt_capcal=0;
  $ref_incid=split(',',$ref_incidenciaj);
  $ref_incidencia_tex=split(',', $ref_incidencia_textj);
  $capcal="<tr bgcolor='#0088cc'><td colspan='2' align='right'><b>Incidencia:</b></td>";
  for($i=0; $i<count($ref_incid); ++$i)
		$capcal .= "<td><center>&nbsp;<b>$ref_incid[$i]</b>&nbsp;</center></td>";
  $capcal .= "<td title='Retards injustificats entre classes'><center>&nbsp;<b>REC</b>&nbsp;</center></td>";
  $capcal .="<td align='center'><b>Apercebiments enviats</b></td></tr>";
   
  print("<table border='0' width='100%'><tr><td align='center' valign='top' width='60'>");
  print("&nbsp;</td>");
  print("<td valign='top'>");
  print("<table border='0' id='taulacos'>");
  $nseleccionats=0;
  while($fila=mysql_fetch_row($conjunt_resultant)) { // recorrem alumnes
		$aux_consulta1=" WHERE refalum='$fila[0]' and datahora!=-1";
      if(isset($ftipus2)) {
      	if( !empty($fil_tapercebiment) && $fil_tapercebiment != "-- tots --" )
      		$aux_consulta1 .= " AND incidencia='$fil_tapercebiment'";
     		if($fil_signat==0)
     			$aux_consulta1 .= " AND NOT signat";
	  		elseif($fil_signat==1)
     			$aux_consulta1 .= " AND signat";
      }
    /*$consulta1="SELECT id, refalum, datahora, obsv, signat FROM $bdtutoria.$tbl_prefix"."apercebiments WHERE refalum='$fila[0]' and datahora!=-1 ORDER BY datahora"; //and datahora>='$datatimestampI' and datahora<='".($datatimestampF)."' ";*/
    	$consulta1="SELECT id, refalum, datahora, obsv, signat FROM $bdtutoria.$tbl_prefix"."apercebiments ".$aux_consulta1." ORDER BY datahora"; //and datahora>='$datatimestampI' and datahora<='".($datatimestampF)."' ";
    	//echo "<p>Consulta1: $consulta1</p>\n";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$napercebiments=mysql_num_rows($conjunt_resultant1);
    	$textcapa="";
    	while($fila1=mysql_fetch_row($conjunt_resultant1)) { // recorrem apercebiments existents
//bingen: "signat" ( Per què es necessita if (validacamp()) ??? )
	   	$textcapa.=(($textcapa!="")?"<br>":"")."<a href=''  title='Elimina apercebiment' onClick='if(confirm(\"Segur que vols eliminar aquest apercebiment?\")){if (validacamp()) {document.forms.introd1.action=document.introd1.action+\"#$fila[0]\"; document.forms.introd1.eliminaapercebiment.value=\"$fila1[0]\"; document.forms.introd1.submit();} } return false;'><img src='imatges/paperera.gif' border='0'></a>" .(($fila1[4]==0)?"&nbsp<a href='' title='Signat' onClick='if (validacamp()) {document.forms.introd1.signaapercebiment.value=\"$fila1[0]\"; document.forms.introd1.submit();} return false;'>S</a>&nbsp" : "&nbsp<a href='' title='No signat' onClick='if (validacamp()) {document.forms.introd1.unsignaapercebiment.value=\"$fila1[0]\"; document.forms.introd1.submit();} return false;'>N</a>&nbsp" )."<b>".$nomDiaSem[date('w',$fila1[2])].", ".date('j-n-Y',$fila1[2])."</b> ".rawurldecode($fila1[3]).(($fila1[4]==0)?"<b> - No signat!</b>" : " - (Signat)" );   
    	} // fi while apercebiments
    	mysql_free_result($conjunt_resultant1);
		// faltes injustificades
    	$consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' and incidencia='F' and data>='$datatimestampI' and data<='$datatimestampF' ";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$nfaltestot=mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
		// faltes justificades
    	$consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' and incidencia='FJ' and data>='$datatimestampI' and data<='$datatimestampF' ";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$nfaltesj=mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
    	// retards injustificats
    	$consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' and incidencia='R' and data>='$datatimestampI' and data<='$datatimestampF' ";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$nretardstot=mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
    	// retards justificats
    	$consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' and incidencia='RJ' and data>='$datatimestampI' and data<='$datatimestampF' ";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$nretardsj=mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
    	// expulsions
    	$consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' and incidencia='E' and data>='$datatimestampI' and data<='$datatimestampF' ";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$nexpul=mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
    	// anotacions
    	$consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' and incidencia='A' and data>='$datatimestampI' and data<='$datatimestampF' ";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$nanotac=mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
// bingen
// OBS!: Què passa si la data inicial no és l'inici de curs? (no xutarà...)
		// faltes injustificades sense notificar
    	$consulta1="SELECT max(quantitat) FROM $bdtutoria.$tbl_prefix"."apercebiments WHERE refalum='$fila[0]' and incidencia='F'";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$n_faltes_ap = mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
    	if( empty($n_faltes_ap) ) $n_faltes_ap = 0;
    	$n_dif_faltes = intval($nfaltestot) - $n_faltes_ap;

		// retards injustificats sense notificar
    	$consulta1="SELECT max(quantitat) FROM $bdtutoria.$tbl_prefix"."apercebiments WHERE refalum='$fila[0]' and incidencia='R'";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$n_retards_ap = mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
    	if( empty($n_retards_ap) ) $n_retards_ap = 0;
    	$n_dif_retards = intval($nretardstot) - $n_retards_ap;

		// retards entre classes
    	$consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."faltes WHERE refalumne='$fila[0]' and incidencia='R' and data>='$datatimestampI' and data<='$datatimestampF' AND hora != '1' AND hora != '7'";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$nrecstot=mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
		
		// retards entre classes sense notificar
    	$consulta1="SELECT max(quantitat) FROM $bdtutoria.$tbl_prefix"."apercebiments WHERE refalum='$fila[0]' and incidencia='REC' ";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$n_recs_ap = mysql_result($conjunt_resultant1, 0,0);
    	mysql_free_result($conjunt_resultant1);
    	if( empty($n_retards_ap) ) $n_retards_ap = 0;
    	$n_dif_recs = intval($nrecstot) - $n_recs_ap;

// bingen

    	$consulta1="SELECT concat(cognom_alu,' ',cognom2_al,', ',nom_alum), curs, grup, pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$fila[0]' LIMIT 1";
    	$conjunt_resultant1=mysql_query($consulta1, $connect);
    	$nom=mysql_result($conjunt_resultant1, 0,0);
    	$curs=mysql_result($conjunt_resultant1, 0,1)." ".mysql_result($conjunt_resultant1, 0,2)." ".mysql_result($conjunt_resultant1, 0,3);
    	mysql_free_result($conjunt_resultant1);
      $consulta1="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."apercebiments WHERE refalum='$fila[0]' and datahora=-1";
      $conjunt_resultant1=mysql_query($consulta1, $connect);
      $nregs=mysql_result($conjunt_resultant1, 0,0);
      if($nregs==1) {
	  		$consulta1="SELECT id, refalum, datahora, obsv FROM $bdtutoria.$tbl_prefix"."apercebiments WHERE refalum='$fila[0]' and datahora=-1 LIMIT 1";
      	$conjunt_resultant1=mysql_query($consulta1, $connect);
      	$fila1=mysql_fetch_row($conjunt_resultant1);
      	$textual=$fila1[3];
  	  		$linktextual="<a href='' title='Anotacions de $nom' onClick='activarCapaText(\"$nom\", \"$fila[0]\",\"$fila1[3]\",\"$fila1[0]\"); return false;'>".(($fila1[3]!="")?"<b>T</b>":"T")."</a>";	  			
      } else {
			$textual="";
			$linktextual="<a href='' title='Anotacions de $nom' onClick='activarCapaText(\"$nom\", \"$fila[0]\",\"\",\"-1\"); return false;'>T</a>";  
      }
	  	mysql_free_result($conjunt_resultant1);
      
    	if( (isset($ftipus2)&&$textcapa!="")||(isset($ftipus3)&&$textual!="")||(isset($ftipus1)&&(($fil_tincidencia=='-- res --')||  (($fil_tincidencia=='Faltes injustificades')&&$nfaltestot>=$fil_valor)||(($fil_tincidencia=='Retards injustificats')&&$nretardstot>=$fil_valor)||($fil_tincidencia=='Retards injustificats entre classes'&&$nrecstot>=$fil_valor) ||(($fil_tincidencia=='Expulsions')&&$nexpul>=$fil_valor)||(($fil_tincidencia=='Faltes i retards injustificats')&&($nfaltestot+$nretardstot)>=$fil_valor))) /* bingen */ || ( isset($ftipus4) && ( ($fil_tincidencia2=='Faltes injustificades' && $n_dif_faltes>=$fil_valor2) || ($fil_tincidencia2=='Retards injustificats' && $n_dif_retards>=$fil_valor2 ) || ($fil_tincidencia2=='Retards injustificats entre classes' && $n_dif_recs>=$fil_valor2 ) ) )      ) { // if l'alumne passa el filtre
      	$curst=$curs;
      	if($curst!=$curstut) {
				print("<tr><td colspan='9'>".(($curstut=='')?"":"<br>")."<b>$curst</b> &nbsp; &nbsp; &nbsp; Tutor/a:&nbsp; &nbsp; <i>".cercaTutor($curst)."</i></td></tr>");
        		$curstut=$curst;
				$compt_capcal=0;
      	}
    
      	if($compt_capcal%5==0) 
      		print($capcal);
      	++$compt_capcal;
    
      	if(file_exists("$dirfotos/$fila[0].jpg")) 
      		$foto = "./foto.php?idsess=$idsess&foto=$fila[0]";
      	else 
      		$foto = "./imatges/fot0.jpg";
      	$linkfil="<a href='' onClick='obreFoto(\"$foto\", \"$nom\"); return false;'><img src='$foto' width='25' height='34' border='0'></a>";
      	print("
      		<tr bgcolor='#aacccc'>
      			<td><a name='$fila[0]'></a>$linkfil</td><td>$nom <font size='-2'>($curs)</font></td>
               <td align='center'>".(($nfaltestot>0)?"<font color='#ff0000'><b>$nfaltestot</b></font>":"$nfaltestot")."</td>
               <td align='center'>$nfaltesj</td>
               <td align='center'>".(($nretardstot>0)?"<font color='#ff0000'><b>$nretardstot</b></font>":"$nretardstot")."</td>
               <td align='center'>$nretardsj</td>
               <td align='center'>".(($nexpul>0)?"<font color='#ff0000'><b>$nexpul</b></font>":"$nexpul")."</td>
               <td align='center'>$nanotac</td>
               <td align='center'>".(($nrecstot>0)?"<font color='#ff0000'><b>$nrecstot</b></font>":"$nrecstot")."</td>
	       		<td align='center'>
	      ");
      	print("<span title='Apercebiments ja enviats. Clica per veure´ls o ocultar-los.'".(($napercebiments!=0)?" onMouseOver='ocultaMostraCapa(\"c1_$fila[0]\",\"t\");'><font color='#ff0000'><b>$napercebiments</b></font>":">".$napercebiments)."</span> ");
      	if($napercebiments!=0) 
      		print("<div id='c1_$fila[0]' style='position:absolute; text-align:left; border-width:2; border-style:ridge; border-color:#000000; background-color:#FFFFCC; visibility:hidden'><u>Apercebiments de $nom:</u><br><br>$textcapa</div>");

			print("
				<select name='nou_apercebiment' id='nou_apercebiment' onChange='var num_incid=0; if(this.value==\"F\") {num_incid=$nfaltestot;} else if(this.value==\"R\") {num_incid=$nretardstot;} else if(this.value==\"REC\") {num_incid=$nrecstot;} nouApercebiment(this.value, \"$nom\", \"$fila[0]\", num_incid)' title='Ll - Lliure\nF - Faltes\nR - Retards\nCC - Comissió de Convivència\nDT - Dc Tarda\nREC - Retards entre classes'>
					<option selected='selected'></option>
			");
			foreach( $tipus_apercebiments as $apercebiment )
				echo "<option>$apercebiment</option>\n";
			print("
				</select>
			");
// bingen
      	print("<a href='' title='Genera impr&eacute;s' onClick='obrefinestra(\"$fila[0]\",\"$dataI\", \"$dataF\"); return false;'>Impr&eacute;s</a> ");
      	print("$linktextual");
 
      	print("</td></tr>");
      	if((isset($ftipus2)&&$textcapa!="")||(isset($ftipus3)&&$textual!="")/*bingen*/||(isset($ftipus4)&&$textcapa!="")) 
      		print("
      			<tr bgcolor='#aacccc'>
      				<td>&nbsp;</td>
      				<td colspan='9'>".(((isset($ftipus2)||isset($ftipus4))&&$textcapa!="")?"<u>Apercebiments enviats:</u><br>".$textcapa."<br>":"").((isset($ftipus3)&&$textual!="")?"<u>Anotacions Textuals:</u><br>".strtr(rawurldecode($textual),array("\n"=>"<br>")):"")."</td>
      			</tr>
      		");
      	++$nseleccionats;
    } // fi if l'alumne passa el filtre
/*    else {
      echo '<p> No passa: '.$curs.' '.$nom.' faltes: '.$n_dif_faltes.'='.$nfaltestot.'-'.$n_faltes_ap .' retards: '.$n_dif_retards.'='.$nretardstot.'-'.$n_retards_ap.'</p>'; //echo bingen
      echo '<p> Me cago en su puta madre</p>'; //echo bingen
      echo '<p> Total: '.$nfaltestot+$nretardstot.'</p>'; //echo bingen
      //echo '<p> var dump: '.var_dump($n_dif_faltes). var_dump($nfaltestot). var_dump($n_faltes_ap).'y: '.var_dump(intval($nfaltestot)). '</p>';
    }*/
  }  // fi while alumnes
  print("</table>");
  print("</td></tr></table>");
  print("<script language='JavaScript'>escriuACapa(\"nseleccionats\", \"$nseleccionats\");</script>");
  mysql_free_result($conjunt_resultant);
  }
print("</form>");
if($nseleccionats>0) print("<hr>");

?>
<div id='c2' style='position:absolute; top:10px; left:10px; text-align:left; border-width:2; border-style:ridge; border-color:#000000; background-color:#FFFFCC; visibility:hidden'></div>
<script language='JavaScript'>
function activarCapaText(pNom, pRef, pText, pId) {
	mostraWindowedObjects(false);
	var des="<a href='' title='Desa els canvis' onClick='if (validacamp()) {document.forms.introd1.textal.value=document.forms.teal.tal.value; document.forms.introd1.desatextal.value=\""+pId+"\"; document.forms.introd1.refal.value=\""+pRef+"\";document.forms.introd1.action=document.introd1.action+\"#"+pRef+"\"; document.forms.introd1.submit();} return false;'>Desa</a>";
	var can="<a href='' title='Cancela els canvis' onClick='escriuACapa(\"c2\",\"\"); ocultaMostraCapa(\"c2\",\"o\"); mostraWindowedObjects(true); location.href=\"#"+pRef+"\"; return false;'>Cancela</a>";
	escriuACapa("c2","<br>&nbsp;&nbsp;Anotacions de <b>"+pNom+"</b>:<br>&nbsp; <form name='teal'><textarea name='tal' cols='40' rows='20'>"+unescape(pText)+"</textarea></form>&nbsp; <br><center>"+des+" "+can+"</center><br>");
	ocultaMostraCapa("c2","v");
	document.forms.teal.tal.focus();
}
</script>
</body>
</html>
