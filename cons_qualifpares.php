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
$priv=preg_split("/_/", $sess_privilegis);
$nalumne=$priv[1];
$gr=preg_split("/ - /", $sess_nomreal);
$grup=$gr[1];
$nomMesE = array ("de Gener", "de Febrer", "de Març", "d'Abril", "de Maig", "de Juny", "de Juliol", "d'Agost", "de Setembre", "d'Octubre", "de Novembre", "de Desembre");

?>
</head>

<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?

print("
<div align='right'>
<table border='0'>
<tr><td><font size='6'>Registre de Qualificacions&nbsp; &nbsp; </font></td></tr></table>
");
print("<table border='0'>
<tr>
<td align='right'><b>Alumne: <font color='#0000ff'>$sess_nomreal</font></b></td></tr>
</table>
</div><hr>
");

$consulta="SELECT DISTINCT n.ref_aval, a.nomaval, a.data, a.observacions, a.nitems, a.nomitems, a.estat from $bdtutoria.$tbl_prefix"."notes n, $bdtutoria.$tbl_prefix"."avaluacions a WHERE n.ref_aval=a.refaval and n.ref_alum='$nalumne' and a.visiblepares='si' ORDER BY a.data desc";
$conjunt_resultant=mysql_query($consulta, $connect);
$nrefavals=0;
while($fila=mysql_fetch_row($conjunt_resultant)) {
	$refavals[$nrefavals][0]=$fila[0];
	$refavals[$nrefavals][1]=$fila[1];
	$refavals[$nrefavals][2]=$fila[2];
	$refavals[$nrefavals][3]=$fila[3];
	$refavals[$nrefavals][4]=$fila[4];
	$refavals[$nrefavals][5]=$fila[5];
	$refavals[$nrefavals][6]=$fila[6];
	++$nrefavals;	
}
mysql_free_result($conjunt_resultant);
if($nrefavals==0) {
	print("No hi ha cap avaluaci&oacute; disponible per ser visualitzada.<hr>");
}
else {
	print("<b>Nota:</b> Les qualificacions s&oacute;n v&agrave;lides excepte si hi ha difer&egrave;ncies amb les que consten en el registre de la secretaria del centre.");
	for($av=0; $av<$nrefavals; ++$av) {
		if($refavals[$av][6]!="tancada") {
			$consulta="SELECT DISTINCT h.assign, l.nomcredit, l.areaassign, l.tipus ";
			$consulta.="FROM $bdtutoria.$tbl_prefix"."horariprofs h, $bdtutoria.$tbl_prefix"."subgrups s, $bdtutoria.$tbl_prefix"."llistacredits l ";
			$consulta.="WHERE ((s.alumnes like '%$nalumne%' and h.grup like concat('%', s.ref_subgrup, '%20', s.nom, '%'))  or  h.grup like '%".rawurlencode($grup)."%') and l.codi=h.assign ";
			$consulta.="ORDER BY l.nomcredit";
		}
		else {
	  		$consulta="SELECT DISTINCT n.ref_credit, l.nomcredit, l.areaassign, l.tipus ";
  			$consulta.="FROM $bdtutoria.$tbl_prefix"."notes n, $bdtutoria.$tbl_prefix"."llistacredits l ";
  			$consulta.="WHERE n.ref_alum='$nalumne' and n.ref_aval='{$refavals[$av][0]}' and l.codi=n.ref_credit ";
  			$consulta.="ORDER BY l.nomcredit";		
		}
		$conjunt_resultant=mysql_query($consulta, $connect);
		$i=0;
		while($fila=mysql_fetch_row($conjunt_resultant)) {
	 		$codis[$i][0]=$fila[0];
	 		$codis[$i][1]=$fila[1];
	 		$codis[$i][2]=$fila[2];
	 		$codis[$i][3]=$fila[3];
	 		++$i;
		}

  		$contcomentari=0;
		unset($comentariavaluacio);

  		print("<table border='0' width='750'><tr><td bgcolor='#0088cc' colspan='2'><font size='+1' color='#ffffff'><b>Avaluació:</b> {$refavals[$av][0]} - ".urldecode($refavals[$av][1])." - ".date('j',$refavals[$av][2])." ".$nomMesE[(date('n',$refavals[$av][2])-1)]." de ".date('Y',$refavals[$av][2])."</font></td></tr>");
  		print("<tr><td colspan='2' align='right'>".(($refavals[$av][3]!="")?urldecode($refavals[$av][3]):"&nbsp;")."</td></tr>");
  		print("<tr><td width='15%'>&nbsp;</td><td bgcolor='#ffffff'>");
		$cont=0;
  		for ($a=0; $a<$i; ++$a) if($codis[$a][3]=='CC') ++$cont;
  		if ($cont!=0) {
	  		print("<table border='0' width='100%'>");
			print("<tr bgcolor='#808080'><td colspan='3' align='left' width='70%'><b>Cr&egrave;dits de mat&egrave;ries comunes:</b></td><td align='center' width='30%'><b>Qualificaci&oacute;</b>");
			if($refavals[$av][5]!='') {
				$aux=explode("|", $refavals[$av][5]);
				print("<table border='0' width='100%'>");
				for($f=0; $f<$refavals[$av][4]; ++$f) {
					$au=explode("->", $aux[$f]);
					print("<td align='center' title='$au[1]'>$au[0]</td>");
				}
				print("</table>");	
			}			
			print("</td></tr>");			
			for ($a=0; $a<$i;++$a) {
				if($codis[$a][3] =='CC') {
					$consulta="SELECT valor, memo, usuari FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='{$refavals[$av][0]}' and ref_alum='$nalumne' and ref_credit='{$codis[$a][0]}' limit 1";
					$conjunt_resultant=mysql_query($consulta, $connect);
					$fila=mysql_fetch_row($conjunt_resultant);
					print("<tr><td width='8%'>{$codis[$a][0]}</td><td width='60%'>".rawurldecode($codis[$a][1])."</td>");
					if($fila[1]!='') {
						++$contcomentari;
						print("<td align='center' width='2%'><font size='-6'>($contcomentari)</font></td>");
						$comentariavaluacio[]="($contcomentari) ".$fila[1];
					}
					else print("<td width='2%'>&nbsp</td>");
					
					$aux=explode('z',$fila[0]);
					if($aux[count($aux)-1]=='') $aux="";
					print("<td width='30%'><table border='0' width='100%'><tr>");
					for($j=0; $j<count($aux); ++$j) {
						if($aux[$j]!='') {
							$vermell=false;
							if($aux[$j]=='I'||$aux[$j]=='1'||$aux[$j]=='2'||$aux[$j]=='3'||$aux[$j]=='4') $vermell=true;
							$bold=false;
							if($j==(count($aux)-1)) $bold=true;
							print("<td align='center' width='".(100/count($aux))."%'>".(($vermell)?"<font color='#ff0000'>":"").(($bold)?"<b>":"<font size='-5'>").$aux[$j].(($bold)?"</b>":"</font>").(($vermell)?"</font>":"")."</td>");
						}
						else print("<td>&nbsp;</td>");
					}
					print("</tr></table></td>");
				}
			}
			print("</table>");	
  		}
		$cont=0;
  		for ($a=0; $a<$i; ++$a) if($codis[$a][3]=='CM') ++$cont;
  		if ($cont!=0) {
	  		print("<table border='0' width='100%'>");
			print("<tr bgcolor='#808080'><td colspan='3' align='left' width='70%'><b>Cr&egrave;dits de mat&egrave;ries de modalitat:</b></td><td align='center' width='30%'><b>Qualificaci&oacute;</b>");
			if($refavals[$av][5]!='') {
				$aux=explode("|", $refavals[$av][5]);
				print("<table border='0' width='100%'>");
				for($f=0; $f<$refavals[$av][4]; ++$f) {
					$au=explode("->", $aux[$f]);
					print("<td align='center' title='$au[1]'>$au[0]</td>");
				}
				print("</table>");	
			}			
			print("</td></tr>");			
			for ($a=0; $a<$i;++$a) {
				if($codis[$a][3] =='CM') {
					$consulta="SELECT valor, memo, usuari FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='{$refavals[$av][0]}' and ref_alum='$nalumne' and ref_credit='{$codis[$a][0]}' limit 1";
					$conjunt_resultant=mysql_query($consulta, $connect);
					$fila=mysql_fetch_row($conjunt_resultant);
					print("<tr><td width='8%'>{$codis[$a][0]}</td><td width='60%'>".rawurldecode($codis[$a][1])."</td>");
					if($fila[1]!='') {
						++$contcomentari;
						print("<td align='center' width='2%'><font size='-6'>($contcomentari)</font></td>");
						$comentariavaluacio[]="($contcomentari) ".$fila[1];
					}
					else print("<td width='2%'>&nbsp</td>");
					
					$aux=explode('z',$fila[0]);
					if($aux[count($aux)-1]=='') $aux="";
					print("<td width='30%'><table border='0' width='100%'><tr>");
					for($j=0; $j<count($aux); ++$j) {
						if($aux[$j]!='') {
							$vermell=false;
							if($aux[$j]=='I'||$aux[$j]=='1'||$aux[$j]=='2'||$aux[$j]=='3'||$aux[$j]=='4') $vermell=true;
							$bold=false;
							if($j==(count($aux)-1)) $bold=true;
							print("<td align='center' width='".(100/count($aux))."%'>".(($vermell)?"<font color='#ff0000'>":"").(($bold)?"<b>":"<font size='-5'>").$aux[$j].(($bold)?"</b>":"</font>").(($vermell)?"</font>":"")."</td>");
						}
						else print("<td>&nbsp;</td>");
					}
					print("</tr></table></td>");
				}
			}
			print("</table>");	
  		}
		$cont=0;
  		for ($a=0; $a<$i; ++$a) if($codis[$a][3]=='CO') ++$cont;
  		if ($cont!=0) {
	  		print("<table border='0' width='100%'>");
			print("<tr bgcolor='#808080'><td colspan='3' align='left' width='70%'><b>Cr&egrave;dits de mat&egrave;ries optatives:</b></td><td align='center' width='30%'><b>Qualificaci&oacute;</b>");
			if($refavals[$av][5]!='') {
				$aux=explode("|", $refavals[$av][5]);
				print("<table border='0' width='100%'>");
				for($f=0; $f<$refavals[$av][4]; ++$f) {
					$au=explode("->", $aux[$f]);
					print("<td align='center' title='$au[1]'>$au[0]</td>");
				}
				print("</table>");	
			}			
			print("</td></tr>");			
			for ($a=0; $a<$i;++$a) {
				if($codis[$a][3] =='CO') {
					$consulta="SELECT valor, memo, usuari FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='{$refavals[$av][0]}' and ref_alum='$nalumne' and ref_credit='{$codis[$a][0]}' limit 1";
					$conjunt_resultant=mysql_query($consulta, $connect);
					$fila=mysql_fetch_row($conjunt_resultant);
					print("<tr><td width='8%'>{$codis[$a][0]}</td><td width='60%'>".rawurldecode($codis[$a][1])."</td>");
					if($fila[1]!='') {
						++$contcomentari;
						print("<td align='center' width='2%'><font size='-6'>($contcomentari)</font></td>");
						$comentariavaluacio[]="($contcomentari) ".$fila[1];
					}
					else print("<td width='2%'>&nbsp</td>");
					
					$aux=explode('z',$fila[0]);
					if($aux[count($aux)-1]=='') $aux="";
					print("<td width='30%'><table border='0' width='100%'><tr>");
					for($j=0; $j<count($aux); ++$j) {
						if($aux[$j]!='') {
							$vermell=false;
							if($aux[$j]=='I'||$aux[$j]=='1'||$aux[$j]=='2'||$aux[$j]=='3'||$aux[$j]=='4') $vermell=true;
							$bold=false;
							if($j==(count($aux)-1)) $bold=true;
							print("<td align='center' width='".(100/count($aux))."%'>".(($vermell)?"<font color='#ff0000'>":"").(($bold)?"<b>":"<font size='-5'>").$aux[$j].(($bold)?"</b>":"</font>").(($vermell)?"</font>":"")."</td>");
						}
						else print("<td>&nbsp;</td>");
					}
					print("</tr></table></td>");
				}
			}
			print("</table>");	
  		}
		$cont=0;
  		for ($a=0; $a<$i; ++$a) if($codis[$a][3]=='CV') ++$cont;
  		if ($cont!=0) {
	  		print("<table border='0' width='100%'>");
			print("<tr bgcolor='#808080'><td colspan='3' align='left' width='70%'><b>Cr&egrave;dits variables:</b></td><td align='center' width='30%'><b>Qualificaci&oacute;</b>");
			if($refavals[$av][5]!='') {
				$aux=explode("|", $refavals[$av][5]);
				print("<table border='0' width='100%'>");
				for($f=0; $f<$refavals[$av][4]; ++$f) {
					$au=explode("->", $aux[$f]);
					print("<td align='center' title='$au[1]'>$au[0]</td>");
				}
				print("</table>");	
			}			
			print("</td></tr>");			
			for ($a=0; $a<$i;++$a) {
				if($codis[$a][3] =='CV') {
					$consulta="SELECT valor, memo, usuari FROM $bdtutoria.$tbl_prefix"."notes WHERE ref_aval='{$refavals[$av][0]}' and ref_alum='$nalumne' and ref_credit='{$codis[$a][0]}' limit 1";
					$conjunt_resultant=mysql_query($consulta, $connect);
					$fila=mysql_fetch_row($conjunt_resultant);
					print("<tr><td width='8%'>{$codis[$a][0]}</td><td width='60%'>".rawurldecode($codis[$a][1])."</td>");
					if($fila[1]!='') {
						++$contcomentari;
						print("<td align='center' width='2%'><font size='-6'>($contcomentari)</font></td>");
						$comentariavaluacio[]="($contcomentari) ".$fila[1];
					}
					else print("<td width='2%'>&nbsp</td>");
					
					$aux=explode('z',$fila[0]);
					if($aux[count($aux)-1]=='') $aux="";
					print("<td width='30%'><table border='0' width='100%'><tr>");
					for($j=0; $j<count($aux); ++$j) {
						if($aux[$j]!='') {
							$vermell=false;
							if($aux[$j]=='I'||$aux[$j]=='1'||$aux[$j]=='2'||$aux[$j]=='3'||$aux[$j]=='4') $vermell=true;
							$bold=false;
							if($j==(count($aux)-1)) $bold=true;
							print("<td align='center' width='".(100/count($aux))."%'>".(($vermell)?"<font color='#ff0000'>":"").(($bold)?"<b>":"<font size='-5'>").$aux[$j].(($bold)?"</b>":"</font>").(($vermell)?"</font>":"")."</td>");
						}
						else print("<td>&nbsp;</td>");
					}
					print("</tr></table></td>");
				}
			}
			print("</table>");	
  		}
 		

	  	print("<br><table border='0' width='100%'>");
		print("<tr bgcolor='#808080' align='left'><td><b>Comentaris d'avaluaci&oacute;:</b></td></tr>");
		if($contcomentari!=0) {
			for ($a=0; $a<$contcomentari; ++$a) {
				print("<tr><td><font size='-4'>&nbsp; &nbsp; &nbsp; ".urldecode($comentariavaluacio[$a])."</font></td></tr>");	
			}
		}
		else print("<tr><td>&nbsp;</td></tr>");

		print("</table>");	

		  		
  		print("</td></tr></table><hr>");	
  	}	
}

?>
</body>
</html>