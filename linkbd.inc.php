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

$host="";
$usuari="";
$password="";

$connect=mysql_connect($host, $usuari, $password);

$tbl_prefix="at_11_12_";
$bdtutoria="";
$bdusuaris="";
$bdalumnes="";

$temps_max_sessio=12000; //en segons
$pass_crypt_profes_si_no=true; //si true no es podra veure a la taula, el password en clar dels professors.
$dirfotos="./arxiufot";
$dirfitxers="./arxiudoc";
//$sms_auto = false;

$mail_SMTPAuth = true;
$mail_SMTPSecure = "ssl";
$mail_Host = "smtp.gmail.com";
$mail_Port = 465;
$mail_Username = "@.";
$mail_Password = "";

$domini = "@.";
$default_email = "tutoria@.";
$default_email_name = "Aplicatiu Tutoria Komodo";

?>
