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
<?php
@include("linkbd.inc.php");
@include("comu.php");
@include("comu.js.php");
panyacces("Administrador");

$gr='';
for($i=0; $i<count($llista_grups); ++$i) {
 if($gr!='') $gr.='|';
 $gr.=$llista_grups[$i];
}
$sbgr='';
for($i=0; $i<count($llista_subgrups); ++$i) {
 if($sbgr!='') $sbgr.='|';
 $sbgr.=$llista_subgrups[$i];
}

$credits='';
$consulta="SELECT concat(codi, ' ', nomcredit,'--->',tipus,'-',pla_estudis) FROM $bdtutoria.$tbl_prefix"."llistacredits order by pla_estudis desc, tipus, codi";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) $credits.=(($credits!="")?"|":"").$fila[0];


if(isset($eliminar) && $eliminar!='') {
  $consulta="DELETE from $bdusuaris.$tbl_prefix"."usu_profes where usuari='$eliminar' LIMIT 1";
  mysql_query($consulta, $connect);
  $consulta="DELETE from $bdtutoria.$tbl_prefix"."horariprofs where idprof='$eliminar'";
  mysql_query($consulta, $connect);
}

//print_r($HTTP_POST_VARS);

if(isset($usuprof)) {
  $quantitatpost=count($HTTP_POST_VARS);
  for($i=0; $i<$quantitatpost; ++$i) {
    $key=key($HTTP_POST_VARS);
    $noms=split('_', $key);
    if($noms[0]=='gr') {
    	if($noms[1]==0) {
      	if(current($HTTP_POST_VARS)!='') { 
	    		if($noms[2]=='tutor') $consulta="insert into $bdtutoria.$tbl_prefix"."horariprofs SET idprof='".$usuprof."', diasem='X', hora='X', grup='tutor_".current($HTTP_POST_VARS)."'";
	    		else if($noms[2]=='admin') $consulta="insert into $bdtutoria.$tbl_prefix"."horariprofs SET idprof='".$usuprof."', diasem='X', hora='X', grup='admin'";
	    		else if($noms[2]=='sms') $consulta="insert into $bdtutoria.$tbl_prefix"."horariprofs SET idprof='".$usuprof."', diasem='X', hora='X', grup='sms'";
	    		else if($noms[2]=='profes') $consulta="insert into $bdtutoria.$tbl_prefix"."horariprofs SET idprof='".$usuprof."', diasem='X', hora='X', grup='Profes'";
	    		else if($noms[2]=='grass') {
	  		  		$consulta="insert into $bdtutoria.$tbl_prefix"."horariprofs SET idprof='".$usuprof."', diasem='-', hora='-', grup='".current($HTTP_POST_VARS)."'"; //, assign='$ass'";
	    		} else $consulta="insert into $bdtutoria.$tbl_prefix"."horariprofs SET idprof='".$usuprof."', diasem='".$noms[2]."', hora='".$noms[3]."', grup='".current($HTTP_POST_VARS)."'";
// 	    		echo "<p> noms: $noms[2]</p>\n";
// 	    		echo "<p> consulta: $consulta </p>\n";
	    		mysql_query($consulta, $connect);
	    	} // current($HTTP_POST_VARS)!=''
      } else { // $noms[1]==0 
	      if ($noms[2]=='tutor') $aux="gr_$noms[1]_tutor_orig";
			else if($noms[2]=='admin') $aux="gr_$noms[1]_admin_orig";
			else if($noms[2]=='sms') $aux="gr_$noms[1]_sms_orig";
			else if($noms[2]=='Profes') $aux="gr_$noms[1]_profes_orig";
			else $aux="gr_$noms[1]_orig";
			eval("\$orig=\$$aux;");
	      if(current($HTTP_POST_VARS)!=$orig) {
		  		if(current($HTTP_POST_VARS)=="") { 
	   	 		$consulta="delete from $bdtutoria.$tbl_prefix"."horariprofs where id='".$noms[1]."' LIMIT 1";
	    			mysql_query($consulta, $connect);
		  		} else { 
		   		$consulta="update $bdtutoria.$tbl_prefix"."horariprofs SET grup='".(($noms[2]=='tutor')?"tutor_":"").current($HTTP_POST_VARS)."' WHERE id='".$noms[1]."' LIMIT 1";
	   	 		mysql_query($consulta, $connect);
	  			} // current($HTTP_POST_VARS)==""
			} // current($HTTP_POST_VARS)!=$orig
      } // $noms[1]==0
    } // $noms[0]=='gr'
    if($noms[0]=='assg' ){ 
		$consulta="update $bdtutoria.$tbl_prefix"."horariprofs SET assign='".current($HTTP_POST_VARS)."' WHERE id='".$noms[1]."' LIMIT 1";
	    mysql_query($consulta, $connect);    
    }
    next($HTTP_POST_VARS);
  }
}

?>
<script language='JavaScript'>

function eliminar(pUsuProf)
{
  location.href="<?=$PHP_SELF?>?eliminar="+pUsuProf+"&idsess=<?=$idsess?>";
}

function nouusuprof()
{
 var finestra;
 window.focus();
 opt = "status=0,resizable=1,scrollbars=0,width=500,height=430,left=15,top=60";
 finestra=window.open("registreprofs.php?idsess=<?=$idsess?>", "finestra", opt);
 finestra.focus();
}


function selgrups(pTitol, pObj, pValsAct)
{
 var finestra;
 window.focus();
 opt = "status=0,resizable=1,scrollbars=0,width=550,height=330,left=15,top=60";
 finestra=window.open("", "finestra", opt);
 with (finestra.document) {
  write("<html><head><title>Tutoria</title>");
  write("<style type='text/css'>");
  write(" FORM {display:inline}");
  write("</style>");
  write("<sc"+"ript language='JavaScript'>");
  write("var ns4=(document.layers)?true:false;");
  write("var ie=(document.all)?true:false;");
  write("var ns6=((document.getElementById)?true:false) && !ie;");
  write("var llistagrups=unescape('<?=$gr?>');");
  write("var llistasubgrups=unescape('<?=$sbgr?>');");
  write("var valsAct='"+pValsAct+"';");
  write("function aplica() {");
  write(" var cad='';");
  write(" for (var i=0; i<document.forms.form1.grups.options.length; ++i) {");
  write("   if(document.forms.form1.grups.options[i].selected) {");
  write("    if (cad!='') cad += '|';");
  write("    cad += escape(document.forms.form1.grups.options[i].text);");
  write("   }");
  write(" } ");
  write(" for (var i=0; i<document.forms.form1.subgrups.options.length; ++i) {");
  write("   if(document.forms.form1.subgrups.options[i].selected) {");
  write("    if (cad!='') cad += '|';");
  write("    cad += escape(document.forms.form1.subgrups.options[i].text);");
  write("   }");
  write(" } ");
  write(" if (ie) {");
  write("  opener.introd1.gr_"+pObj+".value=cad;");
  write("  opener.escriuACapa('ca_"+pObj+"',(unescape(cad)).replace(/[|]/g,\""+((pObj.search('tutor')==-1)?"<br>":", ")+"\"));");
  write(" }");
  write(" if (ns6) {");
  write("  opener.document.forms.introd1.gr_"+pObj+".value=cad;");
  write("  opener.escriuACapa('ca_"+pObj+"',(unescape(cad)).replace(/[|]/g,\""+((pObj.search('tutor')==-1)?"<br>":", ")+"\"));");
  write(" }");
  write(" opener.document.forms.introd1.submit();");
  write(" window.close();");
  write("} ");
  write("</sc"+"ript>");
  write("</head>");
  write("<body bgcolor='#c0c0c0'>");
  write("<center><b><u>Selecci&oacute; Grups / Subgrups</u></b></center>"+pTitol+"<br>");
  write("<form name='form1'>");
  write("<input type='button' value=\"D'acord\" onClick='aplica();'><br>");
  write("<table border='0'><tr><td><b>Grups:</b><br>");
  write("<select name='grups' size='13' multiple><option></option>");
  write("<sc"+"ript language='JavaScript'>");
  write("document.write(\"<option\"+((valsAct.search(escape('Guàrdia'))!=-1)?\" selected\":\"\")+\">Guàrdia</option>\");");
  write("var grups=llistagrups.split('|');");
  write("for(var i=0; i<grups.length; ++i) {");
  write(" document.write(\"<option\"+((valsAct.search(escape(grups[i]))!=-1)?\" selected\":\"\")+\">\"+unescape(grups[i])+\"</option>\");");
  write("} ");
  write("</sc"+"ript>");
  write("</select>");
  write("</td><td>&nbsp;</td><td><b>Subgrups:</b><br>");
  write("<select name='subgrups' size='13' multiple><option></option>");
  write("<sc"+"ript language='JavaScript'>");
  write("var subgrups=llistasubgrups.split('|');");
  write("for(var i=0; i<subgrups.length; ++i) {");
  write(" document.write(\"<option\"+((valsAct.search(escape(subgrups[i]))!=-1)?\" selected\":\"\")+\">\"+unescape(subgrups[i])+\"</option>\");");
  write("} ");
  write("</sc"+"ript>");
  write("</select>");
  write("</td></tr></table>");
  write("</form>");
  write("</body></html>");
  close();
 }
 finestra.focus();
}

function selassign(pTitol, pObj, pValsAct)
{
 var finestra2;
 window.focus();
 opt = "status=0,resizable=1,scrollbars=0,width=400,height=330,left=25,top=80";
 finestra2=window.open("", "finestra2", opt);
 with (finestra2.document) {
  write("<html><head><title>Tutoria</title>");
  write("<style type='text/css'>");
  write(" FORM {display:inline}");
  write("</style>");
  write("<sc"+"ript language='JavaScript'>");
  write("var ns4=(document.layers)?true:false;");
  write("var ie=(document.all)?true:false;");
  write("var ns6=((document.getElementById)?true:false) && !ie;");
  write("var llistacredits=unescape(\"<?=$credits?>\");");
  write("var valsAct='"+pValsAct+"';");
  write("function aplica() {");
  write(" var cad='';");
  write(" for (var i=0; i<document.forms.form1.credits.options.length; ++i) {");
  write("   if(document.forms.form1.credits.options[i].selected) {");
  write("    if (cad!='') cad += '|';");
  write("    cad += escape(document.forms.form1.credits.options[i].text);");
  write("   }");
  write(" } ");
  write(" var aux=unescape(cad).split(' ')[0];");
  write(" if (ie) {");
  write("  opener.introd1.assg_"+pObj+".value=aux;");
  write("  opener.escriuACapa('ca_assg_"+pObj+"',unescape(cad));");
  write(" }");
  write(" if (ns6) {");
  write("  opener.document.forms.introd1.assg_"+pObj+".value=aux;");
  write("  opener.escriuACapa('ca_assg_"+pObj+"',unescape(cad));");
  write(" }");
  write(" opener.document.forms.introd1.submit();");
  write(" window.close();");
  write("} ");
  write("</sc"+"ript>");
  write("</head>");
  write("<body bgcolor='#c0c0c0'>");
  write("<center><b><u>Selecci&oacute; Assignatures</u></b></center>"+pTitol+"<br>");
  write("<form name='form1'>");
  write("<input type='button' value=\"D'acord\" onClick='aplica();'><br>");
  write("<table border='0'><tr><td><b>Assignatures:</b><br>");
  write("<select name='credits' size='13'><option></option>");
  write("<sc"+"ript language='JavaScript'>");
  write("var credits=llistacredits.split('|');");
  write("for(var i=0; i<credits.length; ++i) {");
  write(" document.write(\"<option\"+((valsAct.search(((credits[i]).split(' '))[0])!=-1)?\" selected\":\"\")+\">\"+unescape(credits[i])+\"</option>\");");
  write("} ");
  write("</sc"+"ript>");
  write("</select>");
  write("</td></tr></table>");
  write("</form>");
  write("</body></html>");
  close();
 }
 finestra2.focus();
}

</script>

</head>
<body  bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php

print("
<div align='right'>
<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess'>
<table border='0'>
<tr><td><font size='6'>Horaris-privilegis de professors&nbsp; &nbsp; </font></td>
<td align='right'>
<b>Usuari-professor:</b> <select name='usuprof' onChange='document.introd1.submit();'>
<option></option>");
$consulta="SELECT usuari FROM $bdusuaris.$tbl_prefix"."usu_profes ORDER BY usuari";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) {
  print("<option".(($usuprof==$fila[0])?" selected":"").">$fila[0]</option>");
}
mysql_free_result($conjunt_resultant);
print("</select>&nbsp; ");

$consulta="SELECT id, idprof FROM $bdtutoria.$tbl_prefix"."horariprofs where diasem='X' and hora='X' and grup='admin'";
$conjunt_resultant=mysql_query($consulta, $connect);
$nadmins=mysql_num_rows($conjunt_resultant);
$valadmin='';
$idreg_admin=0;
while($fila=mysql_fetch_row($conjunt_resultant)) {
 	if($fila[1]==$usuprof) {
   	$valadmin='si';
  		$idreg_admin=$fila[0];
  		print("<input type='hidden' name='gr_".$idreg_admin."_admin_orig' value='$valadmin'>");
 	}  
}
mysql_free_result($conjunt_resultant);

$consulta="SELECT id, idprof FROM $bdtutoria.$tbl_prefix"."horariprofs where diasem='X' and hora='X' and grup='sms'";
$conjunt_resultant=mysql_query($consulta, $connect);
$valsms='';
$idreg_sms=0;
while($fila=mysql_fetch_row($conjunt_resultant)) {
	if($fila[1]==$usuprof) {
   	$valsms='si';
  		$idreg_sms=$fila[0];
  		print("<input type='hidden' name='gr_".$idreg_sms."_sms_orig' value='$valsms'>");
	}  
}
mysql_free_result($conjunt_resultant);

$consulta="SELECT id, idprof FROM $bdtutoria.$tbl_prefix"."horariprofs where diasem='X' and hora='X' and grup='Profes'";
$conjunt_resultant=mysql_query($consulta, $connect);
$valprofes='';
$idreg_profes=0;
while($fila=mysql_fetch_row($conjunt_resultant)) {
	if($fila[1]==$usuprof) {
   	$valprofes='Profes';
  		$idreg_profes=$fila[0];
  		print("<input type='hidden' name='gr_".$idreg_profes."_profes_orig' value='$valprofes'>");
	}  
}
mysql_free_result($conjunt_resultant);


print("<a href='' title='Afegeix un nou usuari-professor' onClick='nouusuprof(); return false;'>Nou</a> &nbsp;");
if($usuprof!='' && $usuprof!=$sess_user && (($valadmin=='')||($valadmin=='si'&&$nadmins>1))           ) print("<a href='' title=\"Elimina l'usuari-professor seleccionat\" onClick='if(confirm(\"Segur que vols eliminar aquest usuari-professor?\")) eliminar(document.forms.introd1.usuprof.options[document.forms.introd1.usuprof.selectedIndex].text); return false;'>Eliminar</a> &nbsp;");
if($usuprof!='' && $usuprof!=$sess_user && $valadmin=='si' && $nadmins==1) print("<a href='' title=\"Elimina l'usuari-professor seleccionat\" onClick='alert(\"Avís: No es pot eliminar aquest usuari per que és l´únic administrador del sistema. Crea un altre usuari administrador i ja podras eliminar aquest.\"); return false;'>Eliminar</a> &nbsp;");
print("</td></tr></table></div><hr>");


if(isset($usuprof) && $usuprof!='') {

  print("<table border='0' width='100%'><tr>");
  print("<td width='2%'>&nbsp;</td>");
  print("<td width='22%'><b>Tutor/a de:</b> "); 
  $consulta="SELECT id, grup FROM $bdtutoria.$tbl_prefix"."horariprofs where idprof='$usuprof' and diasem='X' and hora='X' and grup like 'tutor_%'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  if(0==mysql_num_rows($conjunt_resultant)) {
    print("<input type='hidden' name='gr_0_tutor' value=''>");
    print("<div style='display:inline' id='ca_0_tutor'></div>");
    print("&nbsp; <a href='' title='Clica per canviar-ho' onClick='selgrups(\"<b>Usuari-professor:</b> $usuprof, <b>Tutor/a de:</b>\",\"0_tutor\",document.introd1.gr_0_tutor.value); return false;'>Canviar</a>");
  }
  else {
    $fila=mysql_fetch_row($conjunt_resultant);
    print("<input type='hidden' name='gr_$fila[0]_tutor_orig' value='".strtr($fila[1],array("tutor_"=>""))."'>");
    print("<input type='hidden' name='gr_$fila[0]_tutor' value='".strtr($fila[1],array("tutor_"=>""))."'>");
    print("<div style='display:inline' id='ca_$fila[0]_tutor'>".rawurldecode(strtr($fila[1],array("|"=>", ","tutor_"=>"")))."</div>");
    print("&nbsp; <a href='' title='Clica per canviar-ho' onClick='selgrups(\"<b>Usuari-professor:</b> $usuprof, <b>Tutor/a de:</b>\",\"$fila[0]_tutor\",document.introd1.gr_$fila[0]_tutor.value); return false;'>Canviar</a>");
  }
  mysql_free_result($conjunt_resultant);
  print("</td>");
  print("<td width='15%'>");
  print("<b>Administrador/a:</b> <select name='gr_".$idreg_admin."_admin' ".(($valadmin=='si'&&$nadmins==1)?"disabled  ":"")."onChange='document.forms.introd1.submit();'><option".(($valadmin=='')?" selected":"")."></option><option".(($valadmin=='si')?" selected":"").">si</option></select></td>");
  print("<td width='15%'>");
  print("<b>SMS:</b> <select name='gr_".$idreg_sms."_sms' "."onChange='document.forms.introd1.submit();'><option".(($valsms=='')?" selected":"")."></option><option".(($valsms=='si')?" selected":"").">si</option></select></td>");
  print("<td width='15%'>");
  print("<b>Grup:</b> <select name='gr_".$idreg_profes."_profes' "."onChange='document.forms.introd1.submit();'><option".(($valprofes=='')?" selected":"")."></option><option".(($valprofes=='Profes')?" selected":"").">Profes</option></select></td>");
  print("<td width='11%'><a href='' title='Associaci&oacute; (grups alumnes <-> assignatures) addicionals que no han d´estar en cap franja horapria' onClick='ocultaMostraCapa(\"grupassignprof\",\"v\"); document.forms.introd1.assgaddc.value=\"si\"; /*mostraWindowedObjects(false);*/ return false;'>Assigns. addicionals</a></td>");
  
  $consulta="SELECT nomreal FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$usuprof' limit 1";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $fila=mysql_fetch_row($conjunt_resultant);
  print("<td width='20%' align='right'>$fila[0]</td>");
  mysql_free_result($conjunt_resultant);
  print("</tr></table>");

  print("<table border='0' cellspacing='4' width='100%'>");
  print("<tr><td width='5%' bgcolor='#0088cc'>&nbsp;</td>");
  for($j=1; $j<6; ++$j) print("<td align='center' width='19%' bgcolor='#0088cc'><b>$nomDiaSem[$j]</b></td>");
  print("</tr>");
  
  $consulta="SELECT hora, inici, fi FROM $bdtutoria.$tbl_prefix"."frangeshoraries order by inici asc";
  $conjunt_resultant=mysql_query($consulta, $connect);
  while($fila=mysql_fetch_row($conjunt_resultant)) {
	  $llistahores[]=$fila[0];
	  $llistahorestitle[]= date('H',$fila[1]).":".date('i',$fila[1])." - ".date('H',$fila[2]).":".date('i',$fila[2]);
  }
  mysql_free_result($conjunt_resultant);
  
  for($i=0; $i<count($llistahores); ++$i) {
    print("<tr><td align='center' height='50' valign='middle' bgcolor='#0088cc' title='$llistahorestitle[$i]'><b>$llistahores[$i]</b></td>");
    for($j=1; $j<6; ++$j) {
      print("<td valign='top' bgcolor='#aacccc' style='font-size:11; border-style:solid; border-width:1'>");
      $consulta="SELECT id, grup, assign FROM $bdtutoria.$tbl_prefix"."horariprofs where idprof='$usuprof' and diasem='$nomDiaSem[$j]' and hora='$llistahores[$i]' limit 1";
      $conjunt_resultant=mysql_query($consulta, $connect);
      if(0==mysql_num_rows($conjunt_resultant)) {
        	print("<input type='hidden' name='gr_0_$nomDiaSem[$j]_$llistahores[$i]' value=''>");
			print("<a href='' title='Clica per canviar-ho' onClick='selgrups(\"<b>Usuari-professor:</b> $usuprof, <b>Dia:</b> $nomDiaSem[$j], <b>Hora:</b> $llistahores[$i]\",\"0_$nomDiaSem[$j]_$llistahores[$i]\",document.introd1.gr_0_$nomDiaSem[$j]_$llistahores[$i].value); return false;'>Grup:</a> ");
			print("<span id='ca_0_$nomDiaSem[$j]_$llistahores[$i]'></span>");
      }
      else {
        $fila=mysql_fetch_row($conjunt_resultant);
        print("<input type='hidden' name='gr_$fila[0]_orig' value='$fila[1]'>");
	print("<input type='hidden' name='gr_$fila[0]' value='$fila[1]'>");
	print("<a href='' title='Clica per canviar-ho' onClick='selgrups(\"<b>Usuari-professor:</b> $usuprof, <b>Dia:</b> $nomDiaSem[$j], <b>Hora:</b> $llistahores[$i]\",\"$fila[0]\",document.introd1.gr_$fila[0].value); return false;'>Grup:</a> ");
	print("<span id='ca_$fila[0]'>".rawurldecode(strtr($fila[1],array("|"=>"<br>")))."</span>");
    print("<hr>");
    print("<a href='' title='Clica per canviar-ho' onClick='selassign(\"<b>Usuari-professor:</b> $usuprof, <b>Dia:</b> $nomDiaSem[$j], <b>Hora:</b> $llistahores[$i]\",\"$fila[0]\",document.introd1.assg_$fila[0].value); return false;'>Assign.:</a> ");
    if($fila[2]!='') {
	    $consulta1="SELECT nomcredit FROM $bdtutoria.$tbl_prefix"."llistacredits where codi='$fila[2]' limit 1";
	    $conjunt_resultant1=mysql_query($consulta1, $connect);
	    $fila1=mysql_fetch_row($conjunt_resultant1);
	    $nomcredit=$fila1[0];
	    mysql_free_result($conjunt_resultant1);
    }
    else $nomcredit="";
    print("<input type='hidden' name='assg_$fila[0]' value='$fila[2]'>");
    print("<span id='ca_assg_$fila[0]'>$fila[2] ".rawurldecode($nomcredit)."</span>");
      }
      mysql_free_result($conjunt_resultant);
      print("</td>");
    }
    print("</tr>");
  }
  print("</table>");
  print("<div id='grupassignprof' style='position:absolute; top:135; left:10; border-width:4; border-style:ridge; border-color:#000000; background-color:#FFFFCC; visibility:".((isset($assgaddc)&& $assgaddc=='si')?"visible":"hidden")."'>");
    $consulta="SELECT id, grup, assign FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE idprof='$usuprof' and diasem='-' and hora='-' order by grup";
    $conjunt_resultant=mysql_query($consulta, $connect);
    print("<table border='0' width='90%'><tr><td>&nbsp;</td><td align='center'><b>Associació Alumnes-Assignatura addicionals</b></td><td align='right'><a href='' onClick='ocultaMostraCapa(\"grupassignprof\",\"o\");document.forms.introd1.assgaddc.value=\"no\"; mostraWindowedObjects(true); return false;'>X Tancar</a></td></tr></table>");
    print("<input type='hidden' name='assgaddc' value='".((isset($assgaddc)&& $assgaddc=="si")?"si":"no")."'>");
    print("<table border='0' width='90%'>");
    print("<tr bgcolor='#aabbcc'><td>&nbsp;</td><td align='center'><b>Grup-Subgrup d'alumnes</b></td><td align='center'><b>Assignatura</b></td></tr>");
    print("<tr bgcolor='#bbccaa'><td><b>Nou:</b></td>
    <td><a href='' title='Clica per canviar-ho' onClick='selgrups(\"<b>Usuari-professor:</b> $usuprof\",\"0_grass\",document.introd1.gr_0_grass.value); return false;'>Grup:</a> <input type='hidden' name='gr_0_grass'> <span id='ca_0_grass'></span></td>
    <td>&nbsp;</td>
    </tr>");
    while($fila=mysql_fetch_row($conjunt_resultant)) {
		print("<tr bgcolor='#bbccaa'><td><a href='' title='Clica per eliminar aquesta associaci&oacute;' onClick='document.forms.introd1.gr_$fila[0].value=\"\"; document.forms.introd1.submit(); return false;'>Eliminar</a></td>
		<td><a href='' title='Clica per canviar-ho' onClick='selgrups(\"<b>Usuari-professor:</b> $usuprof\",\"$fila[0]\",document.introd1.gr_$fila[0].value); return false;'>Grup:</a> 
		<input type='hidden' name='gr_$fila[0]_orig' value='$fila[1]'>
		<input type='hidden' name='gr_$fila[0]' value='$fila[1]'>
		<span id='ca_$fila[0]'>".rawurldecode(strtr($fila[1],array("|"=>"<br>")))."</span>
		</td>
		<td>
		<a href='' title='Clica per canviar-ho' onClick='selassign(\"<b>Usuari-professor:</b> $usuprof\",\"$fila[0]\",document.introd1.assg_$fila[0].value); return false;'>Assign:</a>");
		if($fila[2]!='') {
	    $consulta1="SELECT nomcredit FROM $bdtutoria.$tbl_prefix"."llistacredits where codi='$fila[2]' limit 1";
	    $conjunt_resultant1=mysql_query($consulta1, $connect);
	    $fila1=mysql_fetch_row($conjunt_resultant1);
	    $nomcredit=$fila1[0];
	    mysql_free_result($conjunt_resultant1);
    	}
    	else $nomcredit="";
    	print("<input type='hidden' name='assg_$fila[0]' value='$fila[2]'>");
    	print("<span id='ca_assg_$fila[0]'>$fila[2] ".rawurldecode($nomcredit)."</span>");	
		print("</td></tr>");
	}
	print("</table>");
  print("</div>");
}
print("</form>");
?>
</body>
</html>
