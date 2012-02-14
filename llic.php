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
?>
</head>
<body bgcolor='#ffffff'>
<br><br><br><br><br>

<div align='center'>
<a href='' onClick='clearTimeout(tm); return false;'>Llegir</a>
<br><br>
<a href='' onClick='location.href="<?print("buit.php?idsess=$idsess");?>"; return false;'>Avança</a>
<br><br>
<?echo $cop?>
<br><br><br>
L'Aplicatiu Tutoria va SENSE CAP MENA DE GARANTIA. Això és programari lliure sota llicència GPL,<br>
i se us convida a utilitzar-lo i redistribuir-lo d'acord amb certes condicions.<br>
Piqueu '<a href="credits.php?idsess=<?=$idsess?>">aqui</a>' per saber-ne els detalls.
<br><br><img src='im.php'>
<br>Aquest missatge solament es mostra als usuaris administradors.
</div>

<script language='JavaScript'>
var tm=setTimeout("location.href='<?print("buit.php?idsess=$idsess");?>';", 6000);
</script>

</body>
</html>