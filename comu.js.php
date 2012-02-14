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
<link rel="stylesheet" href="estils.css" media="screen" type="text/css">
<style type="text/css" media="print">
    #taulacos td {border-width:1px; border-style:solid; font-size:10px} 
    body, td, select, input, submit, button, textarea {font-size:10px}
</style>
<script language="JavaScript">
  var actual= <?print($datatimestamp*1000);?>;
</script>
<script language='JavaScript' src='comu.js'></script>