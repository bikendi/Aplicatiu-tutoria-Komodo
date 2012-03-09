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

if(isset($tancarsess)) {
  $consulta="DELETE from $bdtutoria.$tbl_prefix"."sessions WHERE idsess='$idsess'";
  mysql_query($consulta, $connect);
}

if(isset($idc)) {
   $tra= array("'" => "", "\"" => "");
   $ident=strtr($ident, $tra);
   $pass=strtr($pass, $tra);
   $consulta="SELECT passwd_crypt, nomreal FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari LIKE BINARY '$ident' limit 1";
   $conjunt_resultant=mysql_query($consulta, $connect);
   if(1==mysql_num_rows($conjunt_resultant)) {
    if(md5($pass)==mysql_result($conjunt_resultant, 0,0)) {
      $nomreal=mysql_result($conjunt_resultant, 0,1);
      mysql_free_result($conjunt_resultant);
      $horainici=time();
      $idsess=md5($horainici.$ident);
      $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."logs SET usuari='$ident', datahora='$horainici', ipremota='$_SERVER[REMOTE_ADDR]', text='Ha iniciat sessio l\'usuari/a $nomreal'";
      mysql_query($consulta, $connect);
      $consulta="SELECT diasem, hora, grup FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE idprof='$ident' and diasem<>'-' and hora<>'-' ORDER BY diasem, hora";
      $conjunt_resultant=mysql_query($consulta, $connect);
      $privilegis='Privilegis:';
      while($fila=mysql_fetch_row($conjunt_resultant)) {
//         	if($privilegis!='') $privilegis.='\n'; // $privilegis='Priviegis' => !=''!!!
			if(strstr($fila[2],'tutor_')!=false) {
	  			$fila[2]=str_replace('tutor_', '', $fila[2]);
	  			$tut=true;
			} else $tut=false;
			$multigrups=explode("|",$fila[2]);
			if(count($multigrups)>1) {
	 			for($i=0; $i<count($multigrups); ++$i) {
	   			if($i!=0) $privilegis.='\n';
	   			if ($tut) $privilegis.="Tutor $multigrups[$i]";
	   			else $privilegis.="$fila[0] $fila[1] $multigrups[$i]";
	 			}
			} else {
	  			if ($tut) $privilegis.="Tutor $fila[2]";
	  			else {
	  				if( $fila[2] == 'sms' )
	  					$privilegis.="$fila[2]";
	  				else
	  					$privilegis.="$fila[0] $fila[1] $fila[2]";
	  			}
			} // multigrups
      } // while
      mysql_free_result($conjunt_resultant);
      if(strstr($privilegis,'admin')) $privilegis="Privilegis:\nAdministrador";
      $consulta="insert into $bdtutoria.$tbl_prefix"."sessions set ref_usuari='$ident', ipremota='$_SERVER[REMOTE_ADDR]', horainici='$horainici', idsess='$idsess', nomreal='$nomreal', privilegis='$privilegis'"; 
      mysql_query($consulta, $connect);
      print("<script language='JavaScript'>location.href='menu.php?idsess=$idsess';</script>");
      exit;
    } // fi if pwd ok
   } else { // no és profe => és pare?
   mysql_free_result($conjunt_resultant);
   $consulta="SELECT passwd, refalumne, permisos FROM $bdtutoria.$tbl_prefix"."pares WHERE identificador LIKE BINARY '$ident' limit 1";
//    echo "<p> Consulta: $consulta </p>";
   $conjunt_resultant=mysql_query($consulta, $connect);
   if(1==mysql_num_rows($conjunt_resultant)) {
      $permisos=mysql_result($conjunt_resultant, 0,2);
      if($pass==mysql_result($conjunt_resultant, 0,0)&&$permisos!=0) {
        $refalum=mysql_result($conjunt_resultant, 0,1);
	$consulta1="SELECT concat(cognom_alu,' ',cognom2_al,', ',nom_alum), concat(curs, ' ', grup, ' ', pla_estudi) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$refalum' LIMIT 1";
// 	echo "<p> Consulta1: $consulta1 </p>";
        $conjunt_resultant1=mysql_query($consulta1, $connect);
	$nomalum=mysql_result($conjunt_resultant1, 0,0);
	$cursalum=mysql_result($conjunt_resultant1, 0,1);
	mysql_free_result($conjunt_resultant1);
	mysql_free_result($conjunt_resultant);
        $horainici=time();
        $idsess=md5($horainici.$ident);
	$privilegis="Pare_$refalum"."_".$permisos;
        $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."pareslogs SET usuari='$ident', datahora='$horainici', ipremota='$_SERVER[REMOTE_ADDR]', text='Han iniciat sessi&oacute; els Pares de $nomalum $cursalum'";
        mysql_query($consulta, $connect);
        $consulta="insert into $bdtutoria.$tbl_prefix"."sessions set ref_usuari='$ident', ipremota='$_SERVER[REMOTE_ADDR]', horainici='$horainici', idsess='$idsess', nomreal='$nomalum - $cursalum', privilegis='$privilegis'"; 
        mysql_query($consulta, $connect);
        print("<script language='JavaScript'>location.href='menupares.php?idsess=$idsess';</script>");
        exit;
     }
   }
   mysql_free_result($conjunt_resultant);
   }
}
print("
<html>
<head>
<title>Aplicatiu Tutoria Komodo</title>");
include("comu.js.php");
?>

</head>
<body bgcolor="#ffffff" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?

  print("
   <table border='0' width='100%'>
   <tr><td width='33%'><br><br><br><br><br><br><br><br></td><td width='33%'>&nbsp;</td><td width='33%'>&nbsp;</td></tr>
   <tr><td>&nbsp;</td>
   <td bgcolor='#37a6d1' align='right'>
   <form name='identificacio' method='post' action='$PHP_SELF?nologo=&idc='>
   
   <fieldset style='border-width:3; border-style:ridge; border-color:#42A5A5'><legend>Aplicatiu Tutoria</legend>");
   if(isset($idc)) print("<br><center><font size='-1' color='#ff0000'>Identificaci&oacute; incorrecta. Torna-ho a provar.</font></center>");
   if(isset($sesscad)) print("<br><center><font size='-1' color='#ff0000'>La teva sessi&oacute; ha caducat. Torna't a identificar.</font></center>"); 
   print("<br>
   Identificador: <input type='text' name='ident' size='15' value='".((isset($i)&&isset($p))?"$i":"")."'> &nbsp;
   <br><br>
   Contrasenya: <input type='password' name='pass' size='15' value='".((isset($i)&&isset($p))?"$p":"")."'> &nbsp;
   <br><br>
   <center>
   <input type='submit' value='Acceptar'>&nbsp; &nbsp; &nbsp; 
   <input type='button' value='   Sortir   ' onClick='window.close();'>
   </center>
   <br>
   </fieldset>
   
   </form>
   <script language='JavaScript'>".((isset($i)&&isset($p))?"document.forms.identificacio.submit();":"document.forms.identificacio.ident.focus();")."</script>
   </td>
   <td>&nbsp;</td></tr>
   </table>
  ");

?>

</body>
</html>
