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

@include("linkbd.inc.php");
$quantitatget=count($_GET);
for ($a=0; $a<$quantitatget; ++$a) {
	$clauget=key($_GET);
	$valorget=((!get_magic_quotes_gpc())?addslashes(current($_GET)):current($_GET));
	next($_GET);
	if(!isset($$clauget)) eval("\$$clauget='$valorget';");
}
$quantitatpost=count($_POST);
for ($a=0; $a<$quantitatpost; ++$a) {
	$claupost=key($_POST);
	$valorpost=((!get_magic_quotes_gpc())?addslashes(current($_POST)):current($_POST));
	next($_POST);
	if(!isset($$claupost)) eval("\$$claupost='$valorpost';");	
}
$PHP_SELF=$_SERVER['PHP_SELF'];
//Per al php v5:
$HTTP_POST_VARS=$_POST;
$HTTP_SERVER_VARS=$_SERVER;

$authip=false; 
if($authip && $_SERVER[REMOTE_ADDR]=='192.168.0.150') $ident='user1';
if($authip && $_SERVER[REMOTE_ADDR]=='192.168.0.151') $ident='user2';

if(isset($tancarsess)) {
  $consulta="DELETE from $bdtutoria.$tbl_prefix"."sessions WHERE idsess='$idsess'";
  mysql_query($consulta, $connect);
}

if(isset($idc) || $authip) {
   $tra= array("'" => "", "\"" => "");
   $ident=strtr($ident, $tra);
   $pass=strtr($pass, $tra);
   $consulta="SELECT passwd_crypt, nomreal FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$ident' limit 1";
   $conjunt_resultant=mysql_query($consulta, $connect);
   if(1==mysql_num_rows($conjunt_resultant)) {
    if((md5($pass)==mysql_result($conjunt_resultant, 0,0))||$authip) {
      $nomreal=mysql_result($conjunt_resultant, 0,1);
      mysql_free_result($conjunt_resultant);
      $horainici=time();
      $idsess=md5($horainici.$ident);
      $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."logs SET usuari='$ident', datahora='$horainici', ipremota='$_SERVER[REMOTE_ADDR]', text='Ha iniciat sessio (pda) l\'usuari/a $nomreal'";
      mysql_query($consulta, $connect);
      $consulta="SELECT diasem, hora, grup FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE idprof='$ident' and diasem<>'-' and hora<>'-'";
      $conjunt_resultant=mysql_query($consulta, $connect);
      $privilegis='Privilegis:';
      while($fila=mysql_fetch_row($conjunt_resultant)) {
        if($privilegis!='') $privilegis.='\n';
	if(strstr($fila[2],'tutor_')!=false) {
	  $fila[2]=str_replace('tutor_', '', $fila[2]);
	  $tut=true;
	}  
	else $tut=false;
	$multigrups=explode("|",$fila[2]);
	if(count($multigrups)>1) {
	 for($i=0; $i<count($multigrups); ++$i) {
	   if($i!=0) $privilegis.='\n';
	   if ($tut) $privilegis.="Tutor $multigrups[$i]";
	   else $privilegis.="$fila[0] $fila[1] $multigrups[$i]";
	 }
	}
	else {
	  if ($tut) $privilegis.="Tutor $fila[2]";
	  else $privilegis.="$fila[0] $fila[1] $fila[2]";
	}
      }
      if(strstr($privilegis,'admin')) $privilegis="Privilegis:\nAdministrador";
      $consulta="insert into $bdtutoria.$tbl_prefix"."sessions set ref_usuari='$ident', ipremota='$_SERVER[REMOTE_ADDR]', horainici='$horainici', idsess='$idsess', nomreal='$nomreal', privilegis='$privilegis'"; 
      mysql_query($consulta, $connect);
      print("<script language='JavaScript'>location.href='menu_pda.php?idsess=$idsess';</script>");
      exit;
    }
   }
   mysql_free_result($conjunt_resultant);
}

 print("
   <html>
   <head>
   <title>Tutoria</title>
   </head>
   <body bgcolor='#ccdd88' text='#000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

   <form name='identificacio' method='post' action='$PHP_SELF?idc='>
   <fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'><br>");
   if(isset($idc)) print("<center><font size='-1' color='#ff0000'>Identificaci&oacute; incorrecta. Torna-ho a provar.</font></center>");
   if(isset($sesscad)) print("<center><font size='-1' color='#ff0000'>La teva sessi&oacute; ha caducat. Torna't a identificar.</font></center>"); 
   print("
   &nbsp; Identificador:<br>
   <center><input type='text' name='ident'></center>
   &nbsp; Contrasenya:<br>
   <center><input type='password' name='pass'>
   <script language='JavaScript'>document.forms.identificacio.ident.focus();</script>
   <br><br>
   <input type='submit' value='Acceptar'>&nbsp; &nbsp; &nbsp; 
   </center>
   <br>
   </fieldset>
   </form>
   
   </body>
   </html>
 ");
?>
