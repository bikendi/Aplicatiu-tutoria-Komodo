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


   if(isset($opcreg)&&$opcreg='1') {
       $consulta="SELECT usuari FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$ident'";
       $conjunt_resultant=mysql_query($consulta, $connect);
       if(mysql_num_rows($conjunt_resultant)!=0) {
	     mysql_free_result($conjunt_resultant);
	     print("<html><head><title>Tutoria</title></head>
<body  bgcolor='#c0c0c0' text='#000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<table border='0'><tr><td width='8px'>&nbsp;</td><td width='595px' class='normal'>
<p>&nbsp</p><center><h2>Registre d'usuaris-professors</h2>
L'identificador ($ident) d'aquest usuari-professor ja existeix. Tens que utilitzar-ne un altre.<p>
<a href='$PHP_SELF?idsess=$idsess'>Tornar-hi</a></center>
</td></tr></table>
</body></html>");
             exit;
       }
       else {
	   mysql_free_result($conjunt_resultant);
	   if($pass_crypt_profes_si_no) $p=""; else $p=$passwd1;
	   $p_crypt=md5($passwd1);
	   $consulta="INSERT INTO $bdusuaris.$tbl_prefix"."usu_profes SET usuari='$ident', passwd='$p', passwd_crypt='$p_crypt', nomreal='$nom'";
	   mysql_query($consulta, $connect);
	   print("<html><head><title>Tutoria</title></head>
<body  bgcolor='#c0c0c0' text='#000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<table border='0'><tr><td width='8px'>&nbsp;</td><td width='595px'>
<p>&nbsp</p><center><h2>Registre d'usuaris-professors</h2>
OK. Ja tens efectuat el registre amb les dades seg&uuml;ents:<p>
<form><table border='0'>
<tr><td align='right'>Identificador:</td><td><font color='#990000'>$ident</font></td></tr>
<tr><td align='right'>Contrasenya:</td><td><font color='#990000'><select><option selected>&nbsp;</option><option>$passwd1</option></select></font></td></tr>
<tr><td align='right'>Nom complet:</td><td><font color='#990000'>$nom</font></td></tr>
</table>
<center><input type='button' value='Tancar' onClick='opener.location.href=\"horariprofs.php?idsess=$idsess&usuprof=$ident\";window.close()'></center>
</form>
</center>
</td></tr></table>
</body></html>");
           exit;
       }
   }

?>
<script language='JavaScript'>
function valida()
{
var valIdent=(document.forms.introd1.ident.value).replace(/^ +| +$/g,'');
var resIdent=true;
if ((valIdent.length<4)  || (valIdent != valIdent.match(/[a-z0-9.-]+/g))) resIdent=false;
var valPasswd1=(document.forms.introd1.passwd1.value).replace(/^ +| +$/g,'');
var valPasswd2=(document.forms.introd1.passwd2.value).replace(/^ +| +$/g,'');
var resPasswd=true;
if ( (valPasswd1 != valPasswd2) || ( valPasswd1 != valPasswd1.match(/[a-z][a-z][0-9][0-9][0-9][0-9]/g))) resPasswd=false;
var valNom=(document.forms.introd1.nom.value).replace(/^ +| +$/g,'');
var resNom=true;
if (valNom =='') resNom=false;
if (resIdent && resPasswd && resNom) var noError=true;
else var noError=false;
if (!noError) alert( ((resIdent)?'':'L\'identificador no compleix l\'estructura indicada!\n') +
                     ((resPasswd)?'':'Les dos contrassenyes no són iguals o no compleixen l\'estructura indicada!\n') +
                     ((resNom)?'':'El nom complet no pot estar en blanc!\n') +
                     '\nTens Errors, verifica\'ls abans d\'enviar.' );

return noError;
}

</script>
   
   
</head>
<body  bgcolor="#c0c0c0" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?
print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?opcreg=1&idsess=$idsess' onSubmit='return valida();'>
<table border='0'>
<tr><td><font size='6'>Registre d'un nou usuari-professor&nbsp; &nbsp; </font></td>
</tr></table></div><hr>");
   
print("<b>Introdueix les dades seg&uuml;ents:</b></center><p>
<table border='0' width='100%'>
<tr><td width='50%' align='right' valign='top'>Identificador professor:</td><td width='50%' align='left'><font color='#ff0000'>*</font><input type='text' name='ident' value=''> <font color='#77a0bb' size=-1>Solament lletres min&uacute;scules i n&uacute;meros. M&iacute;nim 4 caracters.</font></td></tr>
<tr><td align='right' valign='top'>Contrasenya:</td><td><font color='#ff0000'>*</font><input type='password' name='passwd1' value=''> <font color='#77a0bb' size=-1>Format pel seg&uuml;ent ordre: 2 lletres i 4 n&uacute;meros, les lletres en min&uacute;scules.</font><br><font size=-1>(exemple: <script language='JavaScript'>document.write(passwdAleat());</script>)</font></td></tr>
<tr><td align='right'>Repeteix la contrasenya:</td><td><font color='#ff0000'>*</font><input type='password' name='passwd2' value=''></td></tr>
<tr><td align='right'>Nom complet:</td><td><font color='#ff0000'>*</font><input type='text' name='nom' value=''></td></tr>
<tr><td align='right'>&nbsp;</td><td><font color='#ff0000'>*</font> Camps obligatoris.</td></tr>
<tr><td colspan='2' align='center'><p>&nbsp</p><input type='submit' value='Registrar'>&nbsp; &nbsp; &nbsp; <input type='reset' value='Esborrar'></td></tr>
</table>
</form>");
print("</td></tr></table>");
?>

</body>
</html>
