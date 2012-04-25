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

function meil_usuari( $usuari ) {

  global $bdalumnes, $tbl_prefix, $domini;
  
  $query1 = "SELECT email FROM $bdalumnes.$tbl_prefix"."usu_profes WHERE usuari = '$usuari'";
//   echo "<p> query1: $query1 </p> \n";
  $res1 = mysql_query($query1) OR die("Error:". mysql_error());
  if( mysql_num_rows($res1) > 0 ) {
    $meil[0] = mysql_result($res1,0,0);
    if( empty($meil[0]) )
	$meil[0] = $usuari . $domini;
//     echo "<p> meil: $meil[0] </p> \n";
//     echo "<p> si no: $usuari $domini </p> \n";
  } else { // és pare, no profe
    $query2 = "SELECT email, email2 FROM $bdalumnes.$tbl_prefix"."pares WHERE identificador = '$usuari'";
    $res2 = mysql_query($query2) OR die("Error:". mysql_error());
    if( mysql_num_rows($res2) > 0 ) {
      $meil[0] = mysql_result($res2,0,0);
      $meil[1] = mysql_result($res2,0,1);
    } else { // provem per refalumne enlloc d'identificador
      $query3 = "SELECT email, email2 FROM $bdalumnes.$tbl_prefix"."pares WHERE refalumne = '$usuari'";
      $res3 = mysql_query($query3) OR die("Error:". mysql_error());
      if( mysql_num_rows($res3) > 0 ) {
	$meil[0] = mysql_result($res3,0,0);
	$meil[1] = mysql_result($res3,0,1);
      } // fi 3
    }// fi 2
  } // fi 1
  
  return $meil;
  
} // meil_usuari


function enviar_mail_phpmailer_5( $from, $to, $subject, $message, $from_name='', $to_name='', $html=false ) {

  global $default_email, $default_email_name, $mail_SMTPAuth, $mail_SMTPSecure, $mail_Host, $mail_Port, $mail_Username, $mail_Password, $bdtutoria, $tbl_prefix, $sess_user, $sess_nomreal, $datatimestamp;
  
  require_once("PHPMailer_v5.1/class.phpmailer.php");
//   require_once("phpmailer/smtp.inc.php");

  $mail = new PHPMailer();
  
  if( empty( $from ) )
    $from = $default_email;
  if( empty( $from_name ) )
    $from_name = $default_email_name;

//   $headers = 'Content-Type: text/plain; charset="utf-8"'."\n" .
// 	'From: '. $from ."\n".
// 	'Reply-To: '. $from ."\n".
// 	'X-Mailer: '. $_SERVER['SERVER_NAME'] .'/PHP/' . phpversion(). "\n";
//   $headers .= 'MIME-Version: 1.0' . "\n";

      $mail->IsSMTP();
      $mail->SMTPAuth = $mail_SMTPAuth;
      $mail->SMTPSecure = $mail_SMTPSecure;
      $mail->Host = $mail_Host;
      $mail->Port = $mail_Port;
      $mail->Username = $mail_Username;
      $mail->Password = $mail_Password;
//       $mail->SMTPDebug = true;

      $mail->WordWrap   = 70;
      $mail->CharSet = 'utf8';
      if( $html )
	$mail->IsHTML(true);
      else
	$mail->IsHTML(false);
      $mail->From = $from;
      $mail->FromName = $from_name;
      $mail->SetFrom( $from, $from_name );
      $mail->Subject = $subject;
      $mail->AddAddress($to, $to_name);
      $mail->Body = $message;

  if( $mail->Send() ) {
    echo "<p>E-mail enviat de: $from_name < $from > a: $to_name < $to ></p>\n";
    $consulta="INSERT INTO $bdtutoria.$tbl_prefix"."comunicacio SET sub=0, de='$sess_user|"."$sess_nomreal', per_a='|". ($to_name!=''? "$to_name ($to)" : $to ) ."', datahora='$datatimestamp', assumpte='Enviat e-mail: ".addslashes($subject)."', contingut='".addslashes($message)."', adjunts='', vist='EnviatE-mail_$sess_user/$datatimestamp'";
//      echo "<p>Consulta e-mail: $consulta</p>\n";
    mysql_query($consulta);
    return true;
  } else {
    echo "<p> Resultat e-mail: Error " . $mail->ErrorInfo ." </p> \n";
    return false;
  }

}
    
?>