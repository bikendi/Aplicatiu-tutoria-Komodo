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
echo "\n";
echo '<link rel="stylesheet" type="text/css" href="css/comu.css">'. "\n";
echo '<link rel="stylesheet" type="text/css" href="css/elmeucompte.css">'. "\n";

?>
<script language='JavaScript'>
function validantelfmobil()
{
	var valtelfmobil=(document.forms.introdmobil.telfmobil.value).replace(/^ +| +$/g,'');
	var restelfmobil=true;
	if ( valtelfmobil != valtelfmobil.match(/[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]/g)) restelfmobil=false;
	if(valtelfmobil=='') restelfmobil=true;
	if (restelfmobil) var noError=true;
	else var noError=false;
	if (!noError) alert( ((restelfmobil)?'':'El número de tèlf. no es vàlid!\nHa d´estar format per 9 números sense espais o buit.') +                   
                     '\nTens Errors, verifica\'ls abans d\'enviar.' );
	return noError;
}

function validapasswd()
{
	var valPasswdAct=(document.forms.introd1.passwdact.value).replace(/^ +| +$/g,'');
	var resPasswdAct=true;
	if (valPasswdAct =='') resPasswdAct=false;
	var valPasswd1=(document.forms.introd1.passwd1.value).replace(/^ +| +$/g,'');
	var valPasswd2=(document.forms.introd1.passwd2.value).replace(/^ +| +$/g,'');
	var resPasswd=true;
	if ( (valPasswd1 != valPasswd2) || ( valPasswd1 != valPasswd1.match(/[a-z][a-z][0-9][0-9][0-9][0-9]/g))) resPasswd=false;
	if (resPasswdAct && resPasswd) var noError=true;
	else var noError=false;
	if (!noError) alert( ((resPasswdAct)?'':'La contrasenya actual no pot estar en blanc!\n') +
                     ((resPasswd)?'':'Les dos contrassenyes noves no son iguals o no compleixen l\'estructura indicada!\n') +                   
                     '\nTens Errors, verifica\'ls abans d\'enviar.' );
	return noError;
}

function validanomreal() {
	var valNom=(document.forms.introdnomreal.nomreal.value).replace(/^ +| +$/g,'');
	var resNom=true;
	if (valNom =='') resNom=false;
	if (resNom) var noError=true;
	else var noError=false;
	if (!noError) alert( ((resNom)?'':'El nom real no pot estar en blanc!\n') +
                     '\nTens Errors, verifica\'ls abans d\'enviar.' );

	return noError;	
}

// TODO: no va
function validaemail()
{
	var valemail=(document.forms.introdmobil.email.value).replace(/^ +| +$/g,'');
	var valemail2=(document.forms.introdmobil.email2.value).replace(/^ +| +$/g,'');
	var noError=true;
	var cadenaemail = '';
	if ( valemail != valemail.match(/[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/g) && valemail != '' ) {
	  cadenaemail = cadenaemail + 'El primer e-mail no es vàlid! \n';
	  noError = noError && false;
	}
	if( valemail2 != valemail2.match(/[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/g) && valemail2 != '' ) {
	  cadenaemail = cadenaemail + 'El segon e-mail no es vàlid!\n';
	  noError = noError && false;
	}
	if (!noError) alert( cadenaemail +                   
                     '\nTens errors, verifica\'ls abans d\'enviar.' );
	return noError;
}

</script>
</head>
<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
$esPare=preg_match("/Pare_/", $sess_privilegis);

print("
<div align='right'>
<table border='0'>
<tr><td><font size='6'>El meu compte&nbsp; &nbsp; </font><br><font size='4' color='#0000ff'>$sess_user - ".(($esPare)?"Pares de: ":"")."$sess_nomreal</font></td>
</tr></table></div><hr>");

if($esPare) {
	$nalumn=split("_",$sess_privilegis);
	$nalumne=$nalumn[1];
    print("<table border='0' width='100%'
    <tr>
    <td valign='top' align='left' width='85%'>");
    $consulta="SELECT adreca, nom_munici, codi_posta, primer_tel, cognom1_pa, cognom2_pa, nom_pare, cognom1_ma, cognom2_ma, nom_mare FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$nalumne' LIMIT 1";
    $conjunt_resultant=mysql_query($consulta, $connect);
    $fila=mysql_fetch_row($conjunt_resultant);
    print("<font color='#0000ff' size='-2'>$fila[6] $fila[4] $fila[5]<br>
           $fila[9] $fila[7] $fila[8]<br>
	   $fila[0]<br>
	   $fila[2] $fila[1]<br>
	   Telf: $fila[3]</font>");
    print("</td>
    <td valign='top' align='right' width='15%'>");
     if(file_exists("$dirfotos/$nalumne.jpg")) $foto = "./foto.php?idsess=$idsess&foto=$nalumne";
     else $foto = "./imatges/fot0.jpg";
     print("<img src='$foto' width='50' height='68' border='0'>
    </td>
    </tr></table>");
}

if(isset($opcpasswd)&& $opcpasswd==1) {
 if($esPare) $consulta="SELECT passwd FROM $bdtutoria.$tbl_prefix"."pares WHERE identificador='$sess_user' limit 1";
 else $consulta="SELECT passwd_crypt FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$sess_user' limit 1";
 $conjunt_resultant=mysql_query($consulta, $connect);
   if(1==mysql_num_rows($conjunt_resultant)) {
	if($esPare) $passwdac=$passwdact; else $passwdac=md5($passwdact);
    if($passwdac==mysql_result($conjunt_resultant, 0,0)) {
      mysql_free_result($conjunt_resultant);
      if($esPare) $consulta="UPDATE $bdtutoria.$tbl_prefix"."pares SET passwd='$passwd1' WHERE identificador='$sess_user' LIMIT 1";
      else {
	      if($pass_crypt_profes_si_no) $p=""; else $p=$passwd1;
	   	  $p_crypt=md5($passwd1);
	      $consulta="UPDATE $bdusuaris.$tbl_prefix"."usu_profes SET passwd='$p', passwd_crypt='$p_crypt' WHERE usuari='$sess_user' LIMIT 1";
	      
      }
      mysql_query($consulta, $connect);
      print("<center>La nova contrasenya ha estat actualitzada, recorda utilitzar-la quan inici&iuml;s una nova sessi&oacute;.</center>");
      print("<hr></body></html>");
      exit;
    }
   }
  print("<center>ERROR: La nova contrasenya no s'ha pogut actualitzar.</center>");
  print("<hr></body></html>");
  exit;
}

if(isset($opctelfmbl)&& $opctelfmbl==1) {
  if($esPare) $consulta="UPDATE $bdtutoria.$tbl_prefix"."pares SET telfSMS='$telfmobil' WHERE identificador='$sess_user' LIMIT 1";
  else $consulta="UPDATE $bdusuaris.$tbl_prefix"."usu_profes SET telfSMS='$telfmobil' WHERE usuari='$sess_user' LIMIT 1";
  mysql_query($consulta, $connect);
}

if(isset($opcemail)&& $opcemail==1) {
  if($esPare)
    $consulta="UPDATE $bdtutoria.$tbl_prefix"."pares SET email='$email', email2='$email2' WHERE identificador='$sess_user' LIMIT 1";
  else 
    $consulta="UPDATE $bdusuaris.$tbl_prefix"."usu_profes SET email='$email' WHERE usuari='$sess_user' LIMIT 1";
  mysql_query($consulta, $connect);
}

if(isset($opcnomreal)&& $opcnomreal==1 && !$esPare) {
  $consulta="UPDATE $bdusuaris.$tbl_prefix"."usu_profes SET nomreal='$nomreal' WHERE usuari='$sess_user' LIMIT 1";
  mysql_query($consulta, $connect);
}

if ($esPare) {
  $consulta="SELECT telfSMS, email, email2 FROM $bdtutoria.$tbl_prefix"."pares WHERE identificador='$sess_user' limit 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $telfSMS=mysql_result($conjunt_resultant, 0,0);
  $email = mysql_result($conjunt_resultant, 0,1);
  $email2 = mysql_result($conjunt_resultant, 0,2);
}else {
  $consulta="SELECT telfSMS, email FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$sess_user' limit 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $telfSMS=mysql_result($conjunt_resultant, 0,0);
  $email = mysql_result($conjunt_resultant, 0,1);
}
mysql_free_result($conjunt_resultant);

if (!$esPare) {
$consulta="SELECT nomreal FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$sess_user' limit 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$nomreal=mysql_result($conjunt_resultant, 0,0);
mysql_free_result($conjunt_resultant);
}
?>

<table border='0' width='100%'><tr><td width='50%' valign='top'>
<fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'>
  <center><b>Introduir el telf. mòbil per a SMS</b></center><p>
  <form name='introdmobil' method='post' action='<?="$PHP_SELF?idsess=$idsess&opctelfmbl=1"?>' onSubmit='return validantelfmobil();'>
  <table border='0' width='100%'>
  <tr><td width='50%' align='right' valign='top'>T&egrave;lf. M&ograve;bil per a rebre missatges SMS de comunicaci&oacute;:<br>(Deixar-ho en blanc per no rebre'n cap)</td><td width='50%' align='left'>&nbsp;<input type='text' name='telfmobil' value='<?=$telfSMS?>' size='9' maxlength='9'><p>&nbsp;</p></td></tr>
  <tr><td colspan='2' align='center'><p>&nbsp</p><input type='submit' value='Acceptar'>&nbsp; &nbsp; &nbsp; <input type='reset' value='Cancelar'></td></tr>
  </table>
  </form>
</fieldset>

<?php
  echo "<fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'> \n";
  echo "  <center><b>Introduir l'e-mail</b></center><p> \n";
  echo "  <form name='introdemail' method='post' action='$PHP_SELF?idsess=$idsess&opcemail=1' onSubmit='return validaemail();'> \n";
  echo "  <table border='0' width='100%'> \n";
  echo "  <tr><td width='50%' align='right' valign='top'>E-mail</td><td width='50%' align='left'>&nbsp;<input type='text' name='email' value='$email'></td></tr> \n";
  if( $esPare )
    echo "  <tr><td width='50%' align='right' valign='top'>E-mail 2</td><td width='50%' align='left'>&nbsp;<input type='text' name='email2' value='$email2'> </td></tr> \n";
//   echo "  <tr><td colspan='2' align='center'><p>&nbsp</p> \n";
  echo "  <tr><td colspan='2' align='center'><input type='submit' value='Acceptar'>&nbsp; &nbsp; &nbsp; <input type='reset' value='Cancelar'></td></tr> \n";
  echo "  </table> \n";
  echo "  </form> \n";
  echo "</fieldset> \n";
?>

<?
if(!$esPare) {
  print("<br>
  <fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'>
    <center><b>Canviar Nom real</b></center><p>
    <form name='introdnomreal' method='post' action='$PHP_SELF?idsess=$idsess&opcnomreal=1' onSubmit='return validanomreal();'>
    <table border='0' width='100%'>
    <tr><td width='50%' align='right' valign='top'>Nom real:</td><td width='50%' align='left' valign='top'><input type='text' name='nomreal' value='$nomreal' size='45' maxlength='50'><p>&nbsp;</p></td></tr>
    <tr><td colspan='2' align='center'><p>&nbsp</p><input type='submit' value='Canviar'>&nbsp; &nbsp; &nbsp; <input type='reset' value='Cancelar'></td></tr>
    </table>
    </form>
  </fieldset>
");
}
?>

</td><td width='50%' valign='top'>   
<fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'>
  <center><b>Canvi de contrasenya</b></center><p>
  <form name='introd1' method='post' action='<?="$PHP_SELF?idsess=$idsess&opcpasswd=1"?>' onSubmit='return validapasswd();'>
  <table border='0' width='100%'>
  <tr><td width='50%' align='right' valign='top'>Contrasenya actual:</td><td width='50%' align='left'><font color='#ff0000'>*</font><input type='password' name='passwdact' value=''><p>&nbsp;</p></td></tr>
  <tr><td align='right' valign='top'>Contrasenya nova:</td><td><font color='#ff0000'>*</font><input type='password' name='passwd1' value=''> <font color='#77a0bb' size=-1>Format pel seg&uuml;ent ordre: 2 lletres i 4 n&uacute;meros, les lletres en min&uacute;scules.</font> <font size=-1>(exemple: <script language='JavaScript'>document.write(passwdAleat());</script>)</font></td></tr>
  <tr><td align='right'>Repeteix la contrasenya nova:</td><td><font color='#ff0000'>*</font><input type='password' name='passwd2' value=''></td></tr>
  <tr><td align='right'>&nbsp;</td><td><font color='#ff0000'>*</font> Camps obligatoris.</td></tr>
  <tr><td colspan='2' align='center'><p>&nbsp</p><input type='submit' value='Canviar'>&nbsp; &nbsp; &nbsp; <input type='reset' value='Esborrar'></td></tr>
  </table>
  </form>
</fieldset>
</td></tr></table>
<hr>
</body>
</html>
