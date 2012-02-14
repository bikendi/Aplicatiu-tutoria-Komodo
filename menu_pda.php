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
<?
@include("linkbd.inc.php");
@include("comu.php");
@include("comu.js.php");
panyacces("Privilegis");
?>
<title>Tutoria - <?print("$nomcentre - $poblaciocentre");?></title>
</head>
<body bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?
	$consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."comunicacio WHERE per_a like '%$sess_user|%' and vist not like '%Vist_$sess_user/%' and vist not like '%Enviat_$sess_user/%' and vist not like '%EnviatSMS_%'";
	$conjunt_resultant=mysql_query($consulta, $connect);
	if(mysql_result($conjunt_resultant, 0,0)==0) $tensmissatges=false;
	else $tensmissatges=true;
	mysql_free_result($conjunt_resultant);

	print("<center>".(($tensmissatges==true)?"<a href='' onClick='alert(\"Tens missatges nous a Comunicació\"); return false;'><img src='./imatges/banderola1.gif' border='0'></a> ":"")."Usuari: <font color='#0000ff'>$sess_nomreal</font></center><br>");
	print("<table width='80%' align='center' style='border-width:3; border-style:ridge; border-color:#42A5A5'><tr><td align='center'>");
	print("<a href='introd_pda.php?pda=&idsess=$idsess'>Incid&egrave;ncies</a>");
	print("<br><br>");
	print("<a href='posarnotes_pda.php?pda=&idsess=$idsess'>Qualificacions</a>");
	print("<br><br><br><br>");
	print("<a href='index_pda.php?pda=&tancarsess=&idsess=$idsess'>Tancar sessi&oacute;</a>");
	print("</td></tr></table>");

?>

</body>
</html>
