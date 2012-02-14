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

/* Cridat per plugin jQuery autocomplete pel despegable de les anotacions */

// $cerca = $_REQUEST['q'];
$cerca = $_REQUEST['term'];
$limit = $_REQUEST['limit'];
if( !$limit) $limit = 10;

@include("linkbd.inc.php");
// @include("comu.php");

$query = "SELECT id, text FROM $bdtutoria.$tbl_prefix"."anotacions WHERE text LIKE '%$cerca%' LIMIT $limit";
// echo $query;
$conjunt_resultant = mysql_query($query, $connect);

$llista = '["';
while( $fila=mysql_fetch_array($conjunt_resultant) ) {
//   $llista .= $fila["text"] . "\n";
  $llista .= $fila["text"] . "\",\"";
}

$llista = substr($llista,0,strlen($llista)-2) . "]";

echo $llista;

return $llista;

// $items[] = array();//creamos un array llamado items
// $i=0; //creo una variable del tipo entero
// while($fila=mysql_fetch_array($conjunt_resultant))
// {
//     $i++; //incremento
//  //insertamos en el array los datos
//   array_push($items,array("id"=>$i,"label"=>$fila["text"],"value"=>$fila["text"]));
// }
// // print_r($items);
// //pasamos el array a formato JSON y lo imprimimos
// echo json_encode($items);

?>
