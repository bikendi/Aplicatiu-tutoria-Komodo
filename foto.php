<?php
/*
    Aplicatiu Tutoria Komodo v.0.1
    Aplicaci� web per a la gesti� de la tasca tutorial.
    Copyright (C) 2002-2007  Artur Guillamet Sabat� <aguillam(a)xtec.net>
    Copyright (C) 2012 �ingen Eguzkitza <beguzkit@xtec.cat>

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
@include("comu.php");

header("Content-type: image/jpeg");

if (file_exists("$dirfotos/".$_GET["foto"].".jpg")) $fp=fopen("$dirfotos/".$_GET["foto"].".jpg","r");
else $fp=fopen("./imatges/fot0.jpg","r");
while(!feof($fp)) {
  print(fgetc($fp));
}
?>
