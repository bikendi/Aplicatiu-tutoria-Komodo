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
// @include("linkbd.inc.php");
// @include("comu.php");
require_once 'mail.php';

function notificar( $profe, $alumne=NULL, $curs=NULL, $subject, $message ) {
  $num_tutors = 1;
  
  switch ($profe) {
    case 'tutor': 
	  if (!$alumne) {
	    if(!$curs)
	      return FALSE;
      
	    $query = "SELECT idprof, grup FROM $bdalumnes.$tbl_prefix"."horariprofs WHERE grup = 'tutor_". rawurlencode($curs) ."'";
	  } else {
	    $query = "SELECT idprof, grup FROM $bdalumnes.$tbl_prefix"."horariprofs WHERE grup = CONCAT('tutor_'";
	  }
	  //echo "<p> Query: $query </p>\n";
	  $res_t = mysql_query($query) OR die("Error:". mysql_error());
	  $num_tutors = mysql_num_rows($res_t);
	  break;
    case 'coord. btx': 
	  break;
    case 'capdes': 
	  break;
    case 'director': 
	  break;
    default:
	  return FALSE;
	  break;
  } //fi switch
  
  for( $i = 0; $i < $num_tutors; $i++ ) {
    $tutor = mysql_result($res_t,$i,0);
    if( !empty($tutor) ) {
      $to = meil_usuari( $tutor );
    }
    enviar_mail_phpmailer( '', $to[0], $subject, $message );
    //enviar_mail_phpmailer( '', 'bingen@iesmediterrania.cat', $subject.' - '.$to, $message );
  }
  
} // fi notificar

    //////////////////// Avisos Coordinador de batxillerat ///////////////////////
  $min_faltes = 15;
  $min_faltes_total = 30;
  $query_2 = " AND F.num_faltes >= $min_faltes_total ) S, 
  $bdalumnes.$tbl_prefix"."Estudiants E 
  WHERE  S.refalumne = E.numero_mat AND PLA_ESTUDI = 'BATX'";
  $query = $query_1 . $min_faltes . $query_2;
  //echo "<p> Query: $query </p>\n";
  $res = mysql_query($query) OR die("Error:". mysql_error());
  $msg_alumnes = '';
  while( $alumnes_object = mysql_fetch_object($res) ) {
    $msg_alumnes .= "- $alumnes_object->NOM_ALUM $alumnes_object->COGNOM_ALU $alumnes_object->COGNOM2_AL -> $alumnes_object->diferencia faltes ($alumnes_object->num_faltes en total)\n";
  }

  $subject = "[Tutoria] Resum de faltes coordinació de Batxillerat";
  $message = $message_1 . $msg_alumnes . $message_2;

  $to = meil_usuari( $coordbtx );
    
  enviar_mail_phpmailer( '', $to[0], $subject, $message );
  enviar_mail_phpmailer( '', 'bingen@iesmediterrania.cat', $subject.' - '.$to, $message );

	    /////////// retards ////////////////////
  $query_retards = "SELECT E.numero_mat, E.NOM_ALUM, E.COGNOM_ALU, E.COGNOM2_AL, S.diferencia, S.num_faltes, E.PLA_ESTUDI 
  FROM (SELECT F.refalumne, F.num_faltes, F.num_faltes - IFNULL(A.quantitat,0) AS diferencia 
	FROM (SELECT refalumne, count(*) AS num_faltes 
	      FROM $bdalumnes.$tbl_prefix"."faltes WHERE incidencia='R' group by refalumne) 
	F LEFT JOIN (SELECT refalum, MAX(quantitat) AS quantitat 
	      FROM $bdalumnes.$tbl_prefix"."apercebiments WHERE incidencia='R' GROUP BY refalum) A 
	ON F.refalumne=A.refalum WHERE F.num_faltes - IFNULL(A.quantitat,0) >= 5) S, 
  $bdalumnes.$tbl_prefix"."Estudiants E 
  WHERE  S.refalumne = E.numero_mat AND PLA_ESTUDI = ";
  $query = $query_retards . "'BATX'";
  //echo "<p> Query: $query </p>\n";
  $res = mysql_query($query) OR die("Error:". mysql_error());
  $msg_alumnes = '';
  while( $alumnes_object = mysql_fetch_object($res) ) {
    $msg_alumnes .= "- $alumnes_object->NOM_ALUM $alumnes_object->COGNOM_ALU $alumnes_object->COGNOM2_AL -> $alumnes_object->diferencia retards ($alumnes_object->num_faltes en total)\n";
  }

  $subject = "[Tutoria] Resum de retards de Batxillerat";
  $message_r_1 = "Hola, \n\n";
  $message_r_1 .= "els següents alumnes tenen un nombre elevat de retards sense notificar.\n\n";
  $message_r_2 .= "\n";
  $message_r_2 .= "S'hauria d'enviar SMS a casa i si és necessari fer un apercebiment per escrit.\n\n";
  $message_2 .= "Missatge generat automàticament pel programa tutoria.\n";
  $message = $message_r_1 . $msg_alumnes . $message_r_2;

  enviar_mail_phpmailer( '', $to, $subject, $message );
  enviar_mail_phpmailer( '', 'bingen@iesmediterrania.cat', $subject.' - '.$to, $message );

    //////////////////// Avisos Cap d'estudis /////////////////////////////////
  $min_faltes_total = 30;
  $query_2 = " AND F.num_faltes >= $min_faltes_total ) S, 
  $bdalumnes.$tbl_prefix"."Estudiants E 
  WHERE  S.refalumne = E.numero_mat";
  $query = $query_1 . $min_faltes . $query_2;
  //echo "<p> Query: $query </p>\n";
  $res = mysql_query($query) OR die("Error:". mysql_error());
  $msg_alumnes = '';
  while( $alumnes_object = mysql_fetch_object($res) ) {
    if( $alumnes_object->PLA_ESTUDI = 'ESO' || $alumnes_object->num_faltes >= 45 )
      $msg_alumnes .= "- $alumnes_object->NOM_ALUM $alumnes_object->COGNOM_ALU $alumnes_object->COGNOM2_AL -> $alumnes_object->diferencia faltes ($alumnes_object->num_faltes en total)\n";
  }

  $subject = "[Tutoria] Resum de faltes cap d'estudis";
  $message = $message_1 . $msg_alumnes . $message_2;

  $to = meil_usuari( $capdes );
    
  enviar_mail_phpmailer( '', $to[0], $subject, $message );
  //enviar_mail_phpmailer( '', 'bingen@iesmediterrania.cat', $subject.' - '.$to, $message );

	    /////////// retards ////////////////////
  $query = $query_retards . "'ESO'";
  //echo "<p> Query: $query </p>\n";
  $res = mysql_query($query) OR die("Error:". mysql_error());
  $msg_alumnes = '';
  while( $alumnes_object = mysql_fetch_object($res) ) {
    $msg_alumnes .= "- $alumnes_object->NOM_ALUM $alumnes_object->COGNOM_ALU $alumnes_object->COGNOM2_AL -> $alumnes_object->diferencia retards ($alumnes_object->num_faltes en total)\n";
  }

  $subject = "[Tutoria] Resum de retards ESO";
  $message = $message_r_1 . $msg_alumnes . $message_r_2;

  enviar_mail_phpmailer( '', $to, $subject, $message );
//   enviar_mail_phpmailer( '', 'bingen@iesmediterrania.cat', $subject.' - '.$to, $message );

    /////////////////// Avisos director //////////////////////////////
  $min_faltes_total = 60;
  $query = $query_1 . $min_faltes . $query_2;
  //echo "<p> Query: $query </p>\n";
  $res = mysql_query($query) OR die("Error:". mysql_error());
  $msg_alumnes = '';
  while( $alumnes_object = mysql_fetch_object($res) ) {
      $msg_alumnes .= "- $alumnes_object->NOM_ALUM $alumnes_object->COGNOM_ALU $alumnes_object->COGNOM2_AL -> $alumnes_object->diferencia faltes ($alumnes_object->num_faltes en total)\n";
  }

  $subject = "[Tutoria] Resum de faltes direcció";
  $message = $message_1 . $msg_alumnes . $message_2;

  $to = meil_usuari( $director );
    
  enviar_mail_phpmailer( '', $to[0], $subject, $message );
  //enviar_mail_phpmailer( '', 'bingen@iesmediterrania.cat', $subject.' - '.$to, $message );

?>