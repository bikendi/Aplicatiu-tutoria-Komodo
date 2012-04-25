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

@include("linkbd.inc.php");
if(isset($_GET['obrefitxer'])&& $_GET['obrefitxer']!='') {
 header("Content-type: ".$_GET['obrefitxermime']."\n");
 header("Content-disposition: attachment; filename=\"".$_GET['obrefitxernom']."\"\n\n");
 $fp=fopen("$dirfitxers/".$_GET['obrefitxer'],"r");
 while(!feof($fp)) {
   print(fgetc($fp));
 }
 exit;
}

@include("comu.php");
@include("enviaSMS.php");
require_once $lib.'mail.php';
$esPare=ereg("Pare_", $sess_privilegis);
?>
<html>
<head>
<title>Tutoria</title>

<?php
if (isset ($mostracontingut) && $mostracontingut!='') {
	$consulta="SELECT id, sub, de, per_a, datahora, assumpte, contingut, adjunts, vist FROM $bdtutoria.$tbl_prefix"."comunicacio where id='$mostracontingut'";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	$remitent=explode("|", $fila[2]);
	$remitentiden=$remitent[0];
	$remitentnomreal=$remitent[1];
	@include("comu.js.php");
	print("<script language='JavaScript'>");
	print("var historial;");
	print("function histori(id)");
	print("{");
	print(" window.focus();");
	print(" opt = 'resizable=1,scrollbars=1,width=500,height=200,left=200,top=120';");
	print(" historial=window.open('$PHP_SELF?idsess=$idsess&historial='+id, 'historial', opt);");
	print(" historial.focus();");
	print("}");
	print("</script>");
	print("</head><body bgcolor='#ffffff' onUnload='if(historial) historial.close();'><form name='mostracontingut' method='post' action='$PHP_SELF?idsess=$idsess'>");
	if((isset($sms)&&$sms=='si')) print("<input type='hidden' name='sms' value='$sms'>");
	print("<div id='capcalera' style='position:relative; border-width:0; border-style:ridge; border-color:#42A5A5; background-color:#FFFF80; visibility:visible'>");
	if($remitentiden!=$sess_user && $carpactual!='Paperera') print("<input type='hidden' name='respon' value=''><a href=''  title='Respon al remitent' onClick='document.forms.mostracontingut.respon.value=\"$fila[0]\"; document.forms.mostracontingut.submit(); return false;'><img src='./imatges/respondre.gif' border='0'> Respon</a>&nbsp; &nbsp;");
	if ($carpactual!='Paperera') print("<a href='' title='Mou el missatge a la paperera' onClick='if(confirm(\"Segur que vols moure el missatge a la paperera?\")) {document.forms.mostracontingut.mouacarpeta.value=\"$fila[0]\"+\"_Paperera\"; document.forms.mostracontingut.submit();} return false;'><img src='./imatges/paperera.gif' border='0'> Esborra</a>&nbsp; &nbsp;");
	else print("<input type='hidden' name='elimina' value=''><a href='' title='Elimina el missatge permanentment' onClick='if(confirm(\"Segur que vols eliminar definitivament aquest missatge?\")) {document.forms.mostracontingut.elimina.value=\"$fila[0]\"; document.forms.mostracontingut.submit();} return false;'><img src='./imatges/esborra.gif' border='0'> Elimina</a>&nbsp; &nbsp;");
	if(!(isset($sms)&&$sms=='si') && $carpactual!='Paperera') { 
	  if($remitentiden==$sess_user) print("<a href='' title='Mostra qui ha vist el missatge' onClick='histori(\"$mostracontingut\"); return false;'><img src='./imatges/historial.gif' border='0'> Historial</a>&nbsp; &nbsp;");
	  print("<a href='' title='Marca el missatge com a pendent' onClick='parent.frames.superior.document.forms.formselmiss.pendent.value=\"$mostracontingut\"; parent.frames.superior.document.forms.formselmiss.submit(); return false;'><img src='./imatges/banderolav.gif' border='0'> Pendent</a>&nbsp; &nbsp;");
	}
	if($carpactual!='Paperera') print("<a href=''  title='Imprimir el missatge' onClick='window.print(); return false;'><img src='./imatges/impr.gif' border='0'> Imprimir</a>&nbsp; &nbsp;");
	
	$hihacarpspers=true;
	$carpetes="<option></option>".(($carpactual!='General')?"<option>General</option>":"");
	$consulta1  = "SELECT carpetes FROM $bdtutoria.$tbl_prefix"."comunicpers WHERE usuari='$sess_user' LIMIT 1";
	$conjunt_resultant1=mysql_query($consulta1, $connect);			
	if(0!=mysql_num_rows($conjunt_resultant1)) {
		$fila1=mysql_fetch_row($conjunt_resultant1);
		mysql_free_result($conjunt_resultant1);
		if($fila1[0]!="") {
			$carps=explode("|", $fila1[0]);
			for($i=0; $i<count($carps); ++$i) {
				$carpetes.=(($carpactual!=$carps[$i])?"<option>$carps[$i]</option>":"");
			}
		}
		else $hihacarpspers=false;
	}
	else {
		mysql_free_result($conjunt_resultant1);
		$hihacarpspers=false;
	}
	print("<input type='hidden' name='mouacarpeta' value=''>");
	if($carpactual!='General' || $hihacarpspers) print("<a href='' title='Moure el missatge' onClick='var dest=document.forms.mostracontingut.mou.options[document.forms.mostracontingut.mou.selectedIndex].text; if(dest!=\"\") {document.forms.mostracontingut.mouacarpeta.value=\"$mostracontingut\"+\"_\"+dest; document.forms.mostracontingut.submit();} return false;'><img src='./imatges/carpetaob.gif' border='0'> Mou a la carpeta:</a> <select name='mou'>$carpetes</select>&nbsp; &nbsp;");
	if((isset($sms)&&$sms=='si')) print("<b>Missatge enviat per SMS</b>");
	if($sess_user==$remitentiden) {
		$nomsp=explode(";", $fila[3]);
		$nomspera="";
		for($i=0; $i<count($nomsp); ++$i) {
			$nomspe=explode("|", $nomsp[$i]);
			$nomspera.=	(($nomspera!="")?"; ":"").$nomspe[1];
		}
	}
	else $nomspera=(($esPare)?"Pares de ":"").$sess_nomreal;

	print("<br><b>$nseleccio.-</b> &nbsp; <b>De:</b> $remitentnomreal &nbsp; &nbsp; &nbsp; <b>Per a:</b> $nomspera<br>");
	print("<b>Data:</b> ".$nomDiaSem[date('w',$fila[4])].", ".date('j-n-Y',$fila[4])."&nbsp; ".date('H:i:s',$fila[4])."&nbsp;");
	if(!(isset($sms)&&$sms=='si')) print("<b>Assumpte:</b> $fila[5]<br>");
	$llistaadjunts='';
	if ($fila[7]!='') {
		$adjunts=split(";",$fila[7]);
		for($i=0; $i<count($adjunts); ++$i) {
			$adj=explode("|",$adjunts[$i]);
			$llistaadjunts.=(($llistaadjunts!="")?", ":"")."<a href='$PHP_SELF?obrefitxer=$adj[0]&obrefitxernom=".addslashes($adj[1])."&obrefitxermime=$adj[2]' title='($adj[3] by.)'><img src='".iconafitxer($adj[1])."' border='0' width='12' height='14'>$adj[1]</a>";	
		}	
	}
	if(!(isset($sms)&&$sms=='si')) print("<img src='./imatges/adj_arxiu.gif'><b>Adjunts:</b> $llistaadjunts");
	print("<br><b>Contingut:</b>");
	print("</div>");
	print(strtr($fila[6],array("\n"=>"<br>")));
	print("</form>");
	mysql_free_result($conjunt_resultant);
	$borrapendent=explode(";",$fila[8]);
// 	$aux = '';
	for ($i=0; $i<count($borrapendent); ++$i) {
		if(ereg("Pendent_$sess_user/", $borrapendent[$i])==false) $aux.=(($aux!='')?";":"").$borrapendent[$i];
	}
	$fila[8]=$aux;
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."comunicacio SET vist='$fila[8]' WHERE id='$fila[0]'";
	mysql_query($consulta, $connect);
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."comunicacio SET vist='".(($fila[8]!='')?"$fila[8];":"")."Vist_$sess_user/$datatimestamp' WHERE id='$fila[0]' and vist not like '%Vist_$sess_user%'";
	mysql_query($consulta, $connect);
	print("<script language='JavaScript'>parent.superior.document.getElementById(\"img$fila[0]\").src='./imatges/pixelblank.gif';</script>");
	$consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."comunicacio WHERE per_a like '%$sess_user|%' and vist not like '%Vist_$sess_user/%' and vist not like '%Enviat_$sess_user/%' and vist not like '%EnviatSMS_%'";
	$conjunt_resultant=mysql_query($consulta, $connect);
	if(mysql_result($conjunt_resultant, 0,0)==0) print("<script language='JavaScript'>parent.window.parent.document.getElementById(\"banderola\").src='./imatges/pixelblank.gif'; parent.window.parent.document.getElementById(\"banderola\").title=''; parent.window.parent.document.getElementById(\"banderolalink\").href='javascript:void(0)';</script>");
	else  print("<script language='JavaScript'>parent.window.parent.document.getElementById(\"banderola\").src='./imatges/banderola1.gif';</script>");
	mysql_free_result($conjunt_resultant);
	print("</body></html>");
	exit;	
}

if(isset($mouacarpeta) && $mouacarpeta!='') {
	$mou=explode("_", $mouacarpeta);
	$idmiss=$mou[0];
	$carpdesti=$mou[1];
	$consulta="SELECT vist FROM $bdtutoria.$tbl_prefix"."comunicacio WHERE id='$idmiss' LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$vist=mysql_result($conjunt_resultant, 0,0);
	mysql_free_result($conjunt_resultant);
	$vist1=eregi_replace("((;Carp_$sess_user/)[a-z0-9]{1,10}$)|((Carp_$sess_user/)[a-z0-9]{1,10};)", "", $vist);
	if($carpdesti!='General') $vist2=$vist1.(($vist1!="")?";":"")."Carp_$sess_user/$carpdesti";
	else $vist2=$vist1;
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."comunicacio SET vist='$vist2' WHERE id='$idmiss'";
	mysql_query($consulta, $connect);
	print("<html><body><script language='JavaScript'>parent.frames.superior.document.forms.formselmiss.submit(); location.href=\"./buit.php?idsess=$idsess\";</script></body></html>");
	exit;	
}

if(isset($fitxer) && $fitxer!='') {
	$nomtemporal="tmp$datatimestamp";
	copy($fitxer, "$dirfitxers/$nomtemporal");
	unlink($fitxer);
	print("</head><body bgcolor='#c0c0c0'>");
	print("El fitxer: $fitxer_name, tipus $fitxer_type i tamany $fitxer_size s'ha adjuntat al missatge.");
	print("<script language='JavaScript'>");
	print("opener.document.forms.noumisstg.adjts.value=opener.document.forms.noumisstg.adjts.value+((opener.document.forms.noumisstg.adjts.value!='')?\";\":\"\")+\"$nomtemporal|$fitxer_name|$fitxer_type|$fitxer_size\"; ");
	print("var llistaadjunts=opener.document.forms.noumisstg.adjts.value; ");
	print("var llistaadjunt=llistaadjunts.split(';'); ");
	print("var llistaadjun=''; ");
	print("for(var i=0; i<llistaadjunt.length; ++i) llistaadjun+=((llistaadjun!='')?', ':'')+llistaadjunt[i].split('|')[1]; ");
    print("if (document.layers) {opener.document.adjunts.document.write(llistaadjun); opener.document.adjunts.document.close();} ");
    print("else if (document.all) opener.document.all.adjunts.innerHTML= llistaadjun; ");  
	print("else if(document.getElementById && !document.all) opener.document.getElementById(\"adjunts\").innerHTML=llistaadjun; ");
	print("window.close(); ");
	print("</script>");
	print("</body></html>");
	exit;
}

if (isset($pendent) && $pendent!='') {
	$consulta="SELECT vist FROM $bdtutoria.$tbl_prefix"."comunicacio where id='$pendent' limit 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila= mysql_fetch_row($conjunt_resultant);
	if(!ereg("Pendent_$sess_user/", $fila[0])) {
		$consulta="UPDATE $bdtutoria.$tbl_prefix"."comunicacio SET vist='$fila[0];Pendent_$sess_user/$datatimestamp' where id='$pendent' limit 1";
		mysql_query($consulta, $connect);
	}
	mysql_free_result($conjunt_resultant);
	
}

if (isset($mostraadreces) && $mostraadreces==1) {
	@include("comu.js.php");
	$llistaseleccionables='';
	
	if(isset($eliminagrup) && $eliminagrup!='') {
		$consulta="SELECT grups FROM $bdtutoria.$tbl_prefix"."comunicpers WHERE usuari='$sess_user' LIMIT 1";
		$conjunt_resultant=mysql_query($consulta, $connect);
		$fila=mysql_fetch_row($conjunt_resultant);
		mysql_free_result($conjunt_resultant);
		$llistagrups=explode("|", $fila[0]);
		$novallistagrups='';
		for ($i=0; $i<count($llistagrups); ++$i) {
			$nom=explode(":", $llistagrups[$i]);
			if($nom[0]!=$eliminagrup) $novallistagrups.=(($novallistagrups!="")?"|":"").$llistagrups[$i];
		}
		$consulta="UPDATE $bdtutoria.$tbl_prefix"."comunicpers SET grups='$novallistagrups' WHERE usuari='$sess_user' LIMIT 1";
		mysql_query($consulta, $connect);	
	}	

	if($esPare && $grups=="") $grups="Tutor";
	$esGrupPersonalitzat=false;
		
	if($grups=="Tutor") {
		$ralum=explode("_", $sess_privilegis);
		$consulta="SELECT concat(curs, ' ', grup, ' ',pla_estudi) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$ralum[1]' LIMIT 1";
		$conjunt_resultant=mysql_query($consulta, $connect);
		$fila=mysql_fetch_row($conjunt_resultant);
		mysql_free_result($conjunt_resultant);
		$consulta="SELECT h.idprof, u.nomreal, u.telfSMS FROM $bdtutoria.$tbl_prefix"."horariprofs h, $bdusuaris.$tbl_prefix"."usu_profes u WHERE h.idprof=u.usuari and h.grup like 'tutor_%' and h.grup like '%".rawurlencode(stripslashes($fila[0]))."%' ";
		$conjunt_resultant=mysql_query($consulta, $connect);
// 		echo "<p> consulta: $consulta </p>\n";
		while($fila=mysql_fetch_row($conjunt_resultant)) {
  			if($fila[0]!=$sess_user) $llistaseleccionables.=(($llistaseleccionables!='')?";":"")."$fila[0]|$fila[1]|$fila[2]";
		}
		mysql_free_result($conjunt_resultant);
	}
	// TODO: equip directiu, coord btx...
	else if($grups=="Altres") {
		$consulta="SELECT h.idprof, u.nomreal, u.telfSMS FROM $bdtutoria.$tbl_prefix"."horariprofs h, $bdusuaris.$tbl_prefix"."usu_profes u WHERE h.idprof=u.usuari and h.grup like 'tutor_%' and h.grup like '%".rawurlencode(stripslashes($fila[0]))."%' ";
		$conjunt_resultant=mysql_query($consulta, $connect);
// 		echo "<p> consulta: $consulta </p>\n";
		while($fila=mysql_fetch_row($conjunt_resultant)) {
  			if($fila[0]!=$sess_user) $llistaseleccionables.=(($llistaseleccionables!='')?";":"")."$fila[0]|$fila[1]|$fila[2]";
		}
		mysql_free_result($conjunt_resultant);
	}	
	else if($grups=="Professors") {
		$consulta="SELECT usuari, nomreal, telfSMS FROM $bdusuaris.$tbl_prefix"."usu_profes order by nomreal";
		$conjunt_resultant=mysql_query($consulta, $connect);
		while($fila=mysql_fetch_row($conjunt_resultant)) {
  			if($fila[0]!=$sess_user) $llistaseleccionables.=(($llistaseleccionables!='')?";":"")."$fila[0]|$fila[1]|$fila[2]";
		}
		mysql_free_result($conjunt_resultant);
		
	}
	else if($grups=="ParesTots" || eregi("[?1-9] [?A-Z] ([.?A-Z0-9])+",$grups)) {
		if($grups=="ParesTots") $consulta="SELECT cognom_alu, cognom2_al, nom_alum, concat(curs, grup, pla_estudi), numero_mat FROM $bdalumnes.$tbl_prefix"."Estudiants order by cognom_alu, cognom2_al";
		else {
    		$gru=split(' ',$grups);
			$consulta="SELECT cognom_alu, cognom2_al, nom_alum, concat(curs, grup, pla_estudi), numero_mat FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' order by cognom_alu, cognom2_al";	
// 			echo "<p> consulta: $consulta </p>\n";
		}
		$conjunt_resultant=mysql_query($consulta, $connect);
		while($fila=mysql_fetch_row($conjunt_resultant)) {
// 			print_r( $fila );
			$consulta1="SELECT identificador, telfSMS, permisos FROM $bdtutoria.$tbl_prefix"."pares where refalumne='$fila[4]'";
// 			echo "<p> consulta: $consulta1 </p>\n";
			$conjunt_resultant1=mysql_query($consulta1, $connect);
			$fila1=mysql_fetch_row($conjunt_resultant1);
  			if($fila1[0]!=$sess_user && ($fila1[2]!=0||(isset($sms)&&$sms=="1"))) $llistaseleccionables.=(($llistaseleccionables!='')?";":"")."$fila1[0]|Pares de $fila[0] $fila[1], $fila[2] ($fila[3])|$fila1[1]";
//   			echo "<p> llistaseleccionables: $llistaseleccionables </p>\n";
  			mysql_free_result($conjunt_resultant1);
		}
		mysql_free_result($conjunt_resultant);		
	}
	else {
		$consulta  = "SELECT grups FROM $bdtutoria.$tbl_prefix"."comunicpers WHERE usuari='$sess_user' LIMIT 1";
// 		echo "<p> consulta: $consulta </p>\n";
		$conjunt_resultant=mysql_query($consulta, $connect);
		if(0!=mysql_num_rows($conjunt_resultant)) {
			$fila=mysql_fetch_row($conjunt_resultant);
			if($fila[0]!="") {
				$grupspersonalitzats=explode("|", $fila[0]);
				for($i=0; $i<count($grupspersonalitzats); ++$i) {
					$grupspersonaltzs=explode(":", $grupspersonalitzats[$i]);
						if($grups==$grupspersonaltzs[0]) {
							$esGrupPersonalitzat=true;
							$elementllista=split(",", $grupspersonaltzs[1]);
							for ($k=0; $k<count($elementllista); ++$k) {
								$consulta1="SELECT usuari, nomreal, telfSMS FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$elementllista[$k]'";
// 								echo "<p> consulta: $consulta1 </p>\n";
								$conjunt_resultant1=mysql_query($consulta1, $connect);
								if(0!=mysql_num_rows($conjunt_resultant1)) {
									$fila1=mysql_fetch_row($conjunt_resultant1);
									mysql_free_result($conjunt_resultant1);
									if($fila1[0]!=$sess_user) $llistaseleccionables.=(($llistaseleccionables!='')?";":"")."$fila1[0]|$fila1[1]|$fila1[2]";	
								}
								else {
									mysql_free_result($conjunt_resultant1);
									$consulta1="SELECT p.identificador, concat('Pares de ', e.cognom_alu, ' ', e.cognom2_al, ', ', e.nom_alum, ' (', e.curs, ' ', e.grup, ' ', e.pla_estudi, ')'), p.telfSMS, p.permisos FROM $bdtutoria.$tbl_prefix"."pares p, $bdalumnes.$tbl_prefix"."Estudiants e WHERE p.refalumne=e.numero_mat and p.identificador='$elementllista[$k]' limit 1";
// 									echo "<p> consulta: $consulta1 </p>\n";

									$conjunt_resultant1=mysql_query($consulta1, $connect);
									if(0!=mysql_num_rows($conjunt_resultant1)) {
										$fila1=mysql_fetch_row($conjunt_resultant1);
										mysql_free_result($conjunt_resultant1);
										if($fila1[0]!=$sess_user && ($fila1[3]!=0||(isset($sms)&&$sms=="1"))) $llistaseleccionables.=(($llistaseleccionables!='')?";":"")."$fila1[0]|$fila1[1]|$fila1[2]";	
									}
								}	
							}					
						} //fi if grups = grupspersonalitzats[0]	
				}
			}
		}
		mysql_free_result($conjunt_resultant);	
	} // fi else
// 	echo "<p> llistaseleccionables: $llistaseleccionables </p>\n";
	
	if(isset($creagrupnom) && $creagrupnom!='') {
		$consulta  = "SELECT grups FROM $bdtutoria.$tbl_prefix"."comunicpers WHERE usuari='$sess_user' LIMIT 1";
		$conjunt_resultant=mysql_query($consulta, $connect);
		if(0!=mysql_num_rows($conjunt_resultant)) {
			$fila=mysql_fetch_row($conjunt_resultant);
			mysql_free_result($conjunt_resultant);
			$fila[0].=(($fila[0]!="")?"|":"")."$creagrupnom:$creagrupllista";
			$consulta="UPDATE $bdtutoria.$tbl_prefix"."comunicpers SET grups='$fila[0]' WHERE usuari='$sess_user' LIMIT 1";
			mysql_query($consulta, $connect);
		}
		else {
			$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."comunicpers SET usuari='$sess_user', grups='$creagrupnom:$creagrupllista'";
			mysql_query($consulta, $connect);
		}	
	}
	$llistaseleccionables_escape = addslashes($llistaseleccionables);
	print("
	<script language='JavaScript'>
	var llistaseleccionats=opener.document.forms.noumisstg.pera.value;
//     var llistaseleccionables='$llistaseleccionables';
    var llistaseleccionables='$llistaseleccionables_escape';
	
	function afegirSeleccionable(iden, nom, telfSMS) {
		llistaseleccionats+=((llistaseleccionats!='')?';':'')+iden+'|'+nom+'|'+telfSMS;
		mostraSeleccionats();
	}
	function afegirTotsSeleccionables() {
        var llselt, trobat, llselts;                                                                                                                                            
		if(llistaseleccionables!='') {                                                                                                                                                 
	    	var llist=llistaseleccionables.split(';');                                                                                                                                    
			for (var i=0; i<llist.length; ++i) {
				llis=llist[i].split('|');
				trobat=false;
				llselts=llistaseleccionats.split(';');
				for(var j=0; j<llselts.length; ++j) {
					llselt=llselts[j].split('|');
					if (llis[0]==llselt[0]) { trobat=true; break; }	
				}
				if(!trobat) llistaseleccionats+=((llistaseleccionats!='')?';':'')+llis[0]+'|'+llis[1]+'|'+llis[2];
			}                                                                                                                                                               
		}                                                                                                                                                                	
		mostraSeleccionats();
		mostraSeleccionables();
	}
	function eliminarSeleccionat(iden) {
		var aux=llistaseleccionats.split(';');
		llistaseleccionats='';
		for(var i=0; i<aux.length; ++i) {
			var llistaslcn=aux[i].split('|');
			if (llistaslcn[0]!=iden) llistaseleccionats+=((llistaseleccionats!='')?';':'')+llistaslcn[0]+'|'+llistaslcn[1]+'|'+llistaslcn[2];
		}
		mostraSeleccionats();
		mostraSeleccionables();
	}
	function eliminarTotsSeleccionats() {
		llistaseleccionats='';
		mostraSeleccionats();
		mostraSeleccionables();
	}
	
	function clauOrdenacio(a,b) {
		var aa=a.split('|');
		var bb=b.split('|');
		var A=aa[0].substring(1);
		var B=bb[0].substring(1);
		return A>B?1:A<B?-1:0;
	}
	function mostraSeleccionats() {
		var aux='';
		if (llistaseleccionats!='') {
			var llistaslcnts=llistaseleccionats.split(';');
			llistaslcnts=llistaslcnts.sort(clauOrdenacio);
			if(llistaslcnts.length!=0) aux+='<table border=\"0\" width=\"100%\">';
			for(var i=0; i<llistaslcnts.length; ++i) {
				var llistaslcn=llistaslcnts[i].split('|');
				aux+='<tr><td bgcolor=\"'+(((i+1)%2==0)?'#c0c0ff':'#c0ffc0')+'\"><a href=\"\" title=\"Clica per eliminar-lo de la selecció.\" onClick=\"eliminarSeleccionat(\''+llistaslcn[0]+'\'); return false;\">'+llistaslcn[1]+'</a>".((isset($sms)&&$sms=="1")?"<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Telf. SMS: '+((llistaslcn[2]!=\"\")?llistaslcn[2]:\"<font color=#ff0000><b>Compte! No en té.</b></font>\")+'":"")."</tr></td>';
			}
			if(llistaslcnts.length!=0) aux+='</table>';
		}
		if(aux!='') aux+='<hr>';
		escriuACapa(\"seleccionats\", aux);	
	}
	function mostraSeleccionables() {
        var vals='';                                                                                                                                            
		if(llistaseleccionables!='') {                                                                                                                                                 
	    	var llist=llistaseleccionables.split(';');
	    	llist=llist.sort(clauOrdenacio);
	    	if(llist.length!=0) vals+='<table border=\"0\" width=\"100%\">';                                                                                                                                    
			for (var i=0; i<llist.length; ++i) {                                                                                                                            
				llis=llist[i].split('|');
				var patro=llis[0]+'|';                                                                                                                               
				if(llistaseleccionats.indexOf(patro)!=-1) vals+='<tr><td bgcolor=\"'+(((i+1)%2==0)?'#c0c0ff':'#c0ffc0')+'\">'+llis[1]+'</tr></td>';                                                                    
				else vals+='<tr><td bgcolor=\"'+(((i+1)%2==0)?'#c0c0ff':'#c0ffc0')+'\"><a href=\"\" title=\"Clica per afegir-lo a la selecció.\" onClick=\"afegirSeleccionable(\''+llis[0]+'\', \''+llis[1]+'\', \''+llis[2]+'\'); mostraSeleccionables(); return false;\">'+llis[1]+'</a></tr></td>';
			}
			if(llist.length!=0) vals+='</table>';                                                                                                                                                               
		}
		if(vals!='') vals+='<hr>';                                                                                                                                                                	
		escriuACapa(\"seleccionables\", vals);
	}
	function actualitzaMissatge() { ");
     	if(isset($sms)&& $sms=="1") {
	     	print("if(llistaseleccionats!='') {
		     		var auxllistaseleccion=llistaseleccionats.split(';');
		     		llistaseleccionats='';
		     		var auxllistaselecc;
		     		for(var i=0; i<auxllistaseleccion.length; ++i) {
			    	 	auxllistaselecc=auxllistaseleccion[i].split('|');
			    	 	if(auxllistaselecc[2]!='') llistaseleccionats+=((llistaseleccionats!='')?';':'')+auxllistaseleccion[i];
		     		}
	     		   } ");
     	}
     	print("
		opener.document.forms.noumisstg.pera.value=llistaseleccionats;
		var llistanoms='';
		if(llistaseleccionats!='') {
			var llistaseleccion=llistaseleccionats.split(';');
			var llistaselecc='';
			for(var i=0; i<llistaseleccion.length; ++i) {
				llistaselecc=llistaseleccion[i].split('|');
				llistanoms+=((llistanoms!='')?'; ':'')+llistaselecc[1];
			}
		}
		opener.escriuACapa('per_a', llistanoms);				
	}
	function creaGrup() {
		if (llistaseleccionats!='') {
			var nomgrup=prompt('Nom del nou grup: (d´1 a 10 cars. a-z, A-Z o 0-9)', '');
			if(nomgrup==null) return;
			nomgrup=nomgrup.replace(/^ +| +$/g,'');
			var validat=true;
			if ((nomgrup=='')||(nomgrup.length>10)||(nomgrup != nomgrup.match(/[a-z0-9]+/gi))) validat=false;
			for(var i=0; i<document.forms.formseldesti.grups.options.length; ++i) if(nomgrup==document.forms.formseldesti.grups.options[i].text) validat=false;	
			if(!validat) {
				alert('Error: Nom incorrecte. Caracters incorrectes o repetit o massa llarg');
				return;
			}
			var elements=llistaseleccionats.split(';');
			var llista='';
			for (var i=0; i<elements.length; ++i) {
				llista+=((llista!='')?',':'')+elements[i].split('|')[0];	
			}
			document.forms.formseldesti.creagrupnom.value=nomgrup;
			document.forms.formseldesti.creagrupllista.value=llista;
			actualitzaMissatge();
			document.forms.formseldesti.submit();
		}
		else alert('Error: Per crear un nou grup personalitzat tens que tenir seleccionats');			
	}
	</script>
	</head>
	<body bgcolor='#ccdd88' text='#000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

	<table border='0' width='100%'>
	<tr><td align='center'>
	<font size='5'>Comunicacio".((isset($sms)&&$sms=="1")?" SMS":"")." - Selecci&oacute; de destinataris&nbsp; &nbsp; </font>
	</td></tr>
	<tr><td align='center'><a href='' title='Actualitza les adreces del missatge' onClick='actualitzaMissatge(); window.close(); return false;'>Actualitzar</a>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='' title='Cancelar les accions fetes' onClick='window.close(); return false;'>Cancelar</a>");
	if(!$esPare) print("&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='' title='Crea un nou grup personalitzat amb els seleccionats' onClick='creaGrup(); return false;'>Crear grup</a>");
	print("</td></tr>
	</table>
	<hr>
  
	<table border='0' width='100%'>
	<tr>
	<td width='50%' valign='top'>
	  <b>Seleccionats:</b><br><br><hr>
	<div id='seleccionats'></div>
	<script language='JavaScript'>mostraSeleccionats();</script>
	</td>
	<td width='1%'  valign='top' bgcolor='#c0c0ff'>
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<b><a href='' title='Seleccionar-los tots' onClick='afegirTotsSeleccionables(); return false;'><<</a>
	&nbsp; &nbsp; &nbsp; &nbsp;
	<a href='' title='Esborrar-los tots' onClick='eliminarTotsSeleccionats(); return false;'>>></a></b>
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	</td>
	<td width='49%' valign='top'>");
	print("
	<form name='formseldesti' method='post' action='$PHP_SELF?idsess=$idsess&mostraadreces=1'>
	<input type='hidden' name='creagrupnom' value=''>
	<input type='hidden' name='creagrupllista' value=''>
	<input type='hidden' name='eliminagrup' value=''>
	<input type='hidden' name='sms' value='$sms'>
	<select name='grups' onChange='actualitzaMissatge(); document.forms.formseldesti.submit();'><option></option>");
	$consulta  = "SELECT grups FROM $bdtutoria.$tbl_prefix"."comunicpers WHERE usuari='$sess_user' LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);			
	if(0!=mysql_num_rows($conjunt_resultant)) {
		$fila=mysql_fetch_row($conjunt_resultant);
		mysql_free_result($conjunt_resultant);
		if($fila[0]!="") {
			$grupspers=explode("|", $fila[0]);
			for($i=0; $i<count($grupspers); ++$i) {
				$grupsper=explode(":", $grupspers[$i]);
				print("<option".(($grups==$grupsper[0])?" selected":"").">".$grupsper[0]."</option>");
			}
		}
	}		
	if($esPare) {
		print("<option".(($grups=="Tutor")?" selected":"").">Tutor</option>");
	} else {
		if(privilegis("sms","sms","-")) 
			print("<option".(($grups=="Professors")?" selected":"").">Professors</option><option".(($grups=="ParesTots")?" selected":"").">ParesTots</option>");
		else 
			print("<option".(($grups=="Professors")?" selected":"").">Professors</option>");
		do {
     		$permis=privilegis('sms', 'sms',current($llista_grups));
     		if($permis) print("<option".(($grups==current($llista_grups))?" selected":"").">".current($llista_grups)."</option>");
   	} while(next($llista_grups));
	} // no es pare
	print("</select>");	
	if($esGrupPersonalitzat) print("&nbsp; &nbsp; <a href='' title='Elimina aquest grup personalitzat' onClick='actualitzaMissatge(); document.forms.formseldesti.eliminagrup.value=\"$grups\"; document.forms.formseldesti.submit(); return false;'>Elimina grup</a>");

	print("<hr>
	<div id='seleccionables'></div>	
	<script language='JavaScript'>mostraSeleccionables();</script>
	</form></td></tr></table>
	</body></html>");
	exit;
}   

if ((isset($nou) && ($nou=='si'||$nou=='siSMS')) || (isset($respon) && $respon!='')) {
	@include("comu.js.php");
	
	print("<script language='JavaScript'>");
	print("var finestra;");
	print("function carregaAdjunt()");
	print("{");
	print(" window.focus();");
	print(" opt = 'resizable=0,scrollbars=0,width=300,height=165,left=15,top=60';");
	print(" finestra=window.open('', 'finestra', opt);");
	print(" with (finestra.document) {");
	print("  write('<html><head><title>Tutoria</title></head>');");
	print("  write('<body bgcolor=\"#c0c0c0\">');");
	print("  write('<form action=\"$PHP_SELF?idsess=$idsess\" method=\"post\" enctype=\"multipart/form-data\">');");
	print("  write('<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\">');");
	print("  write('<br><font size=-1>Selecciona el fitxer a adjuntar i clica sobre <b>Adjuntar</b> (màx. 1M)</font><br>');");
	print("  write('<br>Fitxer a adjuntar: <input type=\"file\" name=\"fitxer\">');");
	print("  write('<br><center><input type=\"submit\" value=\"Adjuntar\"></center>');");
	print("  write('</form>');");
	print("  write('</body></html>');");
	print("  close();");
	print(" }");
	print(" finestra.focus();");
	print("}");
	print("function mostraAdreces()");
	print("{");
	print(" window.focus();");
	print(" opt = 'resizable=1,scrollbars=1,width=630,height=525,left=10,top=60';");
	print(" finestra=window.open('$PHP_SELF?idsess=$idsess&mostraadreces=1".((isset($nou)&&$nou=="siSMS")?"&sms=1":"")."', 'finestra', opt);");
	print(" finestra.focus();");
	print("}");
	print("function enviarmiss() {");
	print("	if(document.forms.noumisstg.pera.value=='') {");
	print("		alert('No es pot enviar, no has indicat cap destinatari.');");
	print("	}");
	print("	else {");			
	if(!(isset($nou)&&$nou=="siSMS")) print("		if(document.forms.noumisstg.assumpte.value==''&& !confirm('No has indicat cap Assumpte. Segur que vols enviar-ho sense Assumpte?')) return false;");
	print("		document.forms.noumisstg.enviar.value='".((!(isset($nou)&&$nou=="siSMS"))?"si":"siSMS")."';");
	print("		document.forms.noumisstg.submit();");
	print("	}");
	print("}");
	print("function contarcars() {");
	print("if ((document.forms.noumisstg.conting.value).length>152) {");
	print("	document.forms.noumisstg.conting.value=(document.forms.noumisstg.conting.value).substring(0,152);");	
	print("}");
	print(" escriuACapa('comptacars', (document.forms.noumisstg.conting.value).length + ' cars., m&agrave;xim 152 caracters');");
	
	print("}");
	print("</script>");
	if(isset($respon) && $respon!='') {
		$consulta="SELECT de, assumpte, contingut, datahora FROM $bdtutoria.$tbl_prefix"."comunicacio where id='$respon'";
		$conjunt_resultant=mysql_query($consulta, $connect);
		$fila=mysql_fetch_row($conjunt_resultant);
		$deorig=$fila[0];
		$deorigi=explode("|", $deorig);
		$deorigid=$deorigi[0];
		$deorignom=$deorigi[1];
		$assumpteorig=$fila[1];
		$contingutorig=$fila[2];
		$datahoraorig=$fila[3];
		mysql_free_result($conjunt_resultant);
		$detelfSMSorig="";
		$consulta="SELECT telfSMS FROM $bdusuaris.$tbl_prefix"."usu_profes where usuari='$deorigid' LIMIT 1";
		$conjunt_resultant=mysql_query($consulta, $connect);
		if(1==mysql_num_rows($conjunt_resultant)) {
			$fila=mysql_fetch_row($conjunt_resultant);
			$detelfSMSorig=$fila[0];
			mysql_free_result($conjunt_resultant);
		}
		else {
			mysql_free_result($conjunt_resultant);
			$consulta="SELECT telfSMS FROM $bdtutoria.$tbl_prefix"."pares where identificador='$deorigid' LIMIT 1";
			$conjunt_resultant=mysql_query($consulta, $connect);
			if(1==mysql_num_rows($conjunt_resultant)) {
				$fila=mysql_fetch_row($conjunt_resultant);
				$detelfSMSorig=$fila[0];
				mysql_free_result($conjunt_resultant);
			}
		}
		$deorig=$deorig."|".$detelfSMSorig;
	}	
	print("</head><body bgcolor='#ffffff' onUnload='if(finestra) finestra.close();'>");
	print("<form name='noumisstg' method='post' action='$PHP_SELF?idsess=$idsess'>");
	print("<div id='capcalera' style='position:relative; border-width:0; border-style:ridge; border-color:#42A5A5; background-color:#FFFF80; visibility:visible'>");
    print("<input type='hidden' name='enviar' value=''>");
    print("<input type='hidden' name='sub' value='".((isset($respon)&&$respon!='')?"$respon":"")."'>");
	print("<a href='' onClick='enviarmiss(); return false;'><img src='./imatges/enviar.gif' border='0'> Enviar".((isset($nou)&&$nou=='siSMS')?" SMS":"")."</a> <a href='' onClick='if(confirm(\"Segur que vols Cancelar l´operació?\")) {document.forms.noumisstg.enviar.value=\"no\"; document.forms.noumisstg.submit();} return false;'><img src='./imatges/tornar.gif' border='0'> Cancelar</a><br>");
	print("<b>De:</b> ".(($esPare)?"Pares de ":"")."$sess_nomreal &nbsp; &nbsp; &nbsp; <b>Per a:</b> <input type='hidden' name='pera' value='".((isset($respon)&&$respon!='')?"$deorig":"")."'><span id='per_a'>".((isset($respon)&&$respon!='')?"$deorignom":"")."</span> <a href='' onClick='mostraAdreces(); return false;'><img src='./imatges/agenda.gif' border='0'>Destinataris</a><br>");
	if((isset($nou)&&$nou!='siSMS')||(isset($respon) && $respon!='')) {
	  print("<b>Assumpte:</b> <input type='text' name='assumpte' value='' size='50' maxlength='50'><script language='JavaScript'>document.forms.noumisstg.assumpte.value='".((isset($respon)&&$respon!='')?addslashes("Re: ".ereg_replace( "^Re: ", "", $assumpteorig)):"")."';</script><br>");
	  print("<img src='./imatges/adj_arxiu.gif'><b>Adjunts:</b> <input type='hidden' name='adjts' value=''><span id='adjunts'></span>&nbsp; &nbsp; <a href='' onClick='carregaAdjunt(); return false;'>Afegir adjunt</a><br>");
	  print("<b>Contingut:</b>");
	}
	else print("<b>Contingut:</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span id='comptacars'>m&agrave;xim 152 caracters</span>");
	print("</div>");
	print("<textarea name='conting' ".((isset($nou)&&$nou=='siSMS')?"onKeyUp='contarcars()' ":"")."cols='124' rows='14' wrap='hard' style=' font-size: 11px; font-family: Verdana, sans-serif; background-color: #C0FFFF'>".((isset($respon)&&$respon!='')?"\n\n\n> --------------Text original--------------\n> El ".$nomDiaSem[date('w',$datahoraorig)].", ".date('j-n-Y',$datahoraorig)."&nbsp; ".date('H:i',$datahoraorig).", $deorignom ha dit:\n".ereg_replace("^", ">", $contingutorig):"")."</textarea>");
	print("</form>");
	print("</body></html>");
	exit;	
}
if (isset($enviar) && $enviar!='') {
//	$conting = traduir_cadena( , $conting );
	if($enviar=='si'||$enviar=='siSMS') {
		$per=explode(";", $pera);
		$p="";
		for($i=0; $i<count($per); ++$i) {
			$pe=explode("|", $per[$i]);
			$p.=(($p!="")?";":"").$pe[0]."|".$pe[1];
			if($enviar=='siSMS' && $pe[2]!='') $llistaTelfsDesti.=(($llistaTelfsDesti!='')?";":"").$pe[2];
		}
		$res='';
		if($enviar=='siSMS') {
			$res=enviaSMS((($esPare)?"Pares de ":"").$sess_nomreal, $llistaTelfsDesti, $conting);
		} else  { // enviem e-mail
		  $subject = $assumpte;
		  $message = $conting;
		  $from = meil_usuari( $sess_user );
// 		  print_r( $from );
// 		  echo "<p> per a: $p </p>\n";
		  $psplit = split( ";", $p );
		  foreach( $psplit as $dest ) {
		    $destsplit = split( "\|", $dest );
// 		    echo "<p> per a: $destsplit[0] espare: $esPare </p>\n";
		    if( !empty($from[0]) ) {
		      $meils = meil_usuari( $destsplit[0] );
		      foreach( $meils as $to ) {
			if( !empty($to) )
			enviar_mail_phpmailer_5( $from[0], $to, $subject, $message, $sess_nomreal );
  // 		      echo "<p> From: $psplit[1] < $from[0] >, To: $to , Sbj: $subject , Msg: $message </p>\n";
		      } // fi foreach $meils
		    } // fi if no empty from
		  } // fi foreach $p
		} // fi else if SMS
		$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."comunicacio SET sub='".((isset($sub)&& $sub!='')?"$sub":"0")."', de='$sess_user|".(($esPare)?"Pares de ":"")."$sess_nomreal', per_a='$p', datahora='$datatimestamp', assumpte='".(($enviar!='siSMS')?addslashes($assumpte):((eregi("NOOK", $res)||$res=="Error connexio"||$res=="Error: No configurat")?"Resultat: Enviament Erròni":"Resultat: Enviament OK"))."', contingut='".addslashes($conting)."', adjunts='', vist='".(($enviar!='siSMS')?"Enviat_$sess_user/$datatimestamp":"EnviatSMS_$sess_user/$datatimestamp;$res")."'";
// 		echo "<p> Consulta: $consulta </p>\n";
		mysql_query($consulta, $connect);
		if(isset($adjts) && $adjts!='') {
			$adjts=addslashes($adjts);
			$consulta="SELECT last_insert_id() FROM $bdtutoria.$tbl_prefix"."comunicacio";
			$conjunt_resultant=mysql_query($consulta, $connect);
			$id=mysql_result($conjunt_resultant,0,0);
			mysql_free_result($conjunt_resultant);
			$llistaadjunts="";
			$adjunts=split(";",$adjts);
			for($i=0; $i<count($adjunts); ++$i) {
				$adj=explode("|",$adjunts[$i]);
				$nomadj="c_$id"."_$i";
				rename("$dirfitxers/$adj[0]", "$dirfitxers/$nomadj");
				$llistaadjunts.=(($llistaadjunts!="")?";":"")."$nomadj|$adj[1]|$adj[2]|$adj[3]"; 	
			}
			$consulta="UPDATE $bdtutoria.$tbl_prefix"."comunicacio SET adjunts='$llistaadjunts' WHERE id='$id'";
			mysql_query($consulta, $connect);
		}		
		if($enviar=='siSMS' && (eregi("NOOK", $res)||$res=="Error connexio"||$res=="Error: No configurat")) print("</head><body><script language='JavaScript'>alert(\"Error: Missatge no enviat\"); parent.superior.document.forms.formselmiss.submit(); location.href=\"buit.php?idsess=$idsess\";</script></body></head></html>");
		else print("</head><body><script language='JavaScript'>alert(\"Missatge enviat correctament.".((privilegis('-', '-','-') && $enviar=='siSMS' && !(eregi("NOOK", $res)||$res=="Error connexio"))?" Saldo SMS: ".saldoSMS():"")."\"); parent.superior.document.forms.formselmiss.submit(); location.href=\"buit.php?idsess=$idsess\";</script></body></head></html>"); 
	}
	else if($enviar=='no') {
		if(isset($adjts) && $adjts!='') {
			$adjunts=split(";",$adjts);
			for($i=0; $i<count($adjunts); ++$i) {
				$adj=explode("|",$adjunts[$i]);
				unlink("$dirfitxers/$adj[0]");
			}	
		}
		print("</head><body><script language='JavaScript'>parent.superior.document.forms.formselmiss.submit(); location.href=\"buit.php?idsess=$idsess\";</script></body></head></html>");	
	}
	exit;	
}
if (isset($elimina) && $elimina!='') {
	$consulta="SELECT vist FROM $bdtutoria.$tbl_prefix"."comunicacio where id='$elimina'";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	$fila[0]=eregi_replace("((;Carp_$sess_user/)[a-z0-9]{1,10}$)|((Carp_$sess_user/)[a-z0-9]{1,10};)", "", $fila[0]);
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."comunicacio SET vist='".(($fila[0]!='')?"$fila[0];":"").((isset($sms)&&$sms=='si')?"EliminaSMS_":"Elimina_")."$sess_user/$datatimestamp' WHERE id='$elimina'";
	mysql_query($consulta, $connect);
	mysql_free_result($conjunt_resultant);
	print("</head><body><script language='JavaScript'>parent.frames.superior.document.forms.formselmiss.submit(); location.href=\"buit.php?idsess=$idsess\";</script></body></html>");
	exit;	
}

if (isset($historial) && $historial!='') {
	$consulta="SELECT vist FROM $bdtutoria.$tbl_prefix"."comunicacio where id='$historial'";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	mysql_free_result($conjunt_resultant);
	$llista=explode(";", $fila[0]);
	@include("comu.js.php");
	print("<head><body bgcolor='#ccdd88' text='000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
	<div align='right'>
	<table border='0'>
	<tr><td><font size='4'>Historial del missatge&nbsp; &nbsp; </font></td></tr>
	</table>
	</div><hr>");	
	for($i=0; $i<count($llista); ++$i) {
		$aux=explode("/", $llista[$i]);
		$aux0=explode("_", $aux[0]);
		$consulta1="SELECT concat('Pares de ', e.cognom_alu, ' ', e.cognom2_al, ', ', e.nom_alum, ' (', e.curs, ' ', e.grup, ' ', e.pla_estudi, ')') FROM $bdtutoria.$tbl_prefix"."pares p, $bdalumnes.$tbl_prefix"."Estudiants e WHERE p.refalumne=e.numero_mat and p.identificador='$aux0[1]' limit 1";
		$conjunt_resultant1=mysql_query($consulta1, $connect);
		if(mysql_num_rows($conjunt_resultant1)==0) {
			mysql_free_result($conjunt_resultant1);
			$consulta1="SELECT nomreal FROM $bdusuaris.$tbl_prefix"."usu_profes where usuari='$aux0[1]' limit 1";
			$conjunt_resultant1=mysql_query($consulta1, $connect);	
		}
		$fila1=mysql_fetch_row($conjunt_resultant1);	
		if($aux0[0]=="Vist"||$aux0[0]=="Enviat") print("$aux0[0] - $fila1[0] - ".$nomDiaSem[date('w',$aux[1])].", ".date('j-n-Y',$aux[1])."&nbsp; ".date('H:i:s',$aux[1])."<hr>");	
	
	}
	
	
	print("</body></html>");
	exit;
}

if(isset($gcarpetes) && $gcarpetes=='1') {
	if(isset($afegircarpetadesar) && $afegircarpetadesar=='1') {
		if (!eregi("^[a-zA-Z0-9]{1,10}$",$nomnovacarpeta)) {
			print("<html><script language='JavaScript'>alert('Error: Nom de carpeta incorrecte o en blanc.'); location.href='$PHP_SELF?idsess=$idsess&gcarpetes=1';</script></html>");
			exit;
		}
		$consulta  = "SELECT carpetes FROM $bdtutoria.$tbl_prefix"."comunicpers WHERE usuari='$sess_user' LIMIT 1";
		$conjunt_resultant=mysql_query($consulta, $connect);
		if(0!=mysql_num_rows($conjunt_resultant)) {
			$fila=mysql_fetch_row($conjunt_resultant);			
			$carps=explode("|", $fila[0]);
			$repetit=false;
	  		for($i=0; $i<count($carps); ++$i) {
				if($carps[$i]==$nomnovacarpeta) $repetit=true;
      		}
      		if(strtolower($nomnovacarpeta)==strtolower('General')||strtolower($nomnovacarpeta)==strtolower('Paperera')) $repetit=true;
      		if ($repetit) {
				print("<html><script language='JavaScript'>alert('Error: Nom de carpeta repetit.'); location.href='$PHP_SELF?idsess=$idsess&gcarpetes=1';</script></html>");
				exit;	      		
      		}
			$consulta  = "UPDATE $bdtutoria.$tbl_prefix"."comunicpers SET carpetes='".(($fila[0]!="")?"$fila[0]|":"")."$nomnovacarpeta' WHERE usuari='$sess_user' LIMIT 1";
			mysql_query($consulta, $connect);
			mysql_free_result($conjunt_resultant);	
		}
		else {
			mysql_free_result($conjunt_resultant);
			$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."comunicpers SET usuari='$sess_user', carpetes='$nomnovacarpeta'";
			mysql_query($consulta, $connect);
		}	
	}
	if (isset($eliminarcarpeta) && $eliminarcarpeta!='') {
		if (!isset($eliminarcarpetaconfirm)||$eliminarcarpetaconfirm!="si") {
			print("<html><script language='JavaScript'>if (confirm('Segur que vols eliminar aquesta carpeta? Si l´elimines, tots els missatges d´aquesta carpeta quedaran inaccessibles!')) location.href='$PHP_SELF?idsess=$idsess&gcarpetes=1&eliminarcarpeta=$eliminarcarpeta&eliminarcarpetaconfirm=si'; else location.href='$PHP_SELF?idsess=$idsess&gcarpetes=1';</script></html>");
			exit;
		} else {
			$consulta  = "SELECT carpetes FROM $bdtutoria.$tbl_prefix"."comunicpers WHERE usuari='$sess_user' LIMIT 1";
			mysql_query($consulta, $connect);
			$conjunt_resultant=mysql_query($consulta, $connect);
			$fila=mysql_fetch_row($conjunt_resultant);
			mysql_free_result($conjunt_resultant);
	  		$carps=explode("|", $fila[0]);
	  		$carpetes='';
	  		for($i=0; $i<count($carps); ++$i) {
				if($carps[$i]!=$eliminarcarpeta) $carpetes.=(($carpetes!="")?"|":"").$carps[$i];
      		}
      		$consulta  = "UPDATE $bdtutoria.$tbl_prefix"."comunicpers SET carpetes='$carpetes' WHERE usuari='$sess_user' LIMIT 1";
			mysql_query($consulta, $connect);
		}	
	}
	@include("comu.js.php");
	print("<head><body bgcolor='#ccdd88' text='000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onUnload='opener.document.forms.formselmiss.submit();'>");	

	print("
	<div align='right'>
	<table border='0'>
	<tr><td><font size='4'>Gesti&oacute; carpetes personalitzades&nbsp; &nbsp; </font></td></tr>
	</table>
	</div><hr>
	");

	print("<form name='introd1' method='post' action='".$PHP_SELF."?idsess=$idsess&gcarpetes=1'>");
	$consulta  = "select carpetes from $bdtutoria.$tbl_prefix"."comunicpers where usuari='$sess_user' limit 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$nfiles=mysql_num_rows($conjunt_resultant);
	print("<table border='0' align='center' width='100%'><tr><td>");
	print("<input type='hidden' name='afegircarpeta' value=''>");
	print("<a href='' onClick='document.forms.introd1.afegircarpeta.value=\"1\"; document.forms.introd1.submit(); return false;'>Afegir carpeta personalitzada</a><br>");
	if($nfiles==0 && !isset($afegircarpeta)) {
	    print("No hi ha cap carpeta personalitzada.");
	}
	else {
		$fila=mysql_fetch_row($conjunt_resultant);
		if($fila[0]=='' && !isset($afegircarpeta)) print("No hi ha cap carpeta personalitzada.");
		else {
	    	print("<table border='0' width='100%'><tr bgcolor='#0088cc'>
	    	<td width='10%'>&nbsp;</td><td align='center' width='90%' valign='top'><b>Nom carpeta</b><br>(d'1 a 10 c&agrave;rs. a-z, A-Z o 0-9)</td>
	    	</tr>");
	    	if(isset($afegircarpeta)&&$afegircarpeta=='1') {
			  print("<input type='hidden' name='afegircarpetadesar' value=''>");
			  print("<tr bgcolor='#aacccc'><td align='center'><a href='' onClick='document.forms.introd1.afegircarpetadesar.value=\"1\"; document.forms.introd1.submit(); return false;'>Desar</a> <a href='' onClick='document.forms.introd1.submit(); return false;'>Cancelar</a></td><td align='center'><input type='text' name='nomnovacarpeta' size='10' maxlength='10'></td></tr>"); 
	    	}
	    	if ($fila[0]!="") {
			  $carpetes=explode("|", $fila[0]);
			  print("<input type='hidden' name='eliminarcarpeta' value=''>");
			  for($i=0; $i<count($carpetes); ++$i) {
			    print("<tr bgcolor='#aacccc'><td><a href='' onClick='document.forms.introd1.eliminarcarpeta.value=\"$carpetes[$i]\"; document.forms.introd1.submit(); return false;'>Eliminar</a></td><td align='center'>$carpetes[$i]</td></tr>");
	    	  }
	    	}
	    	print("</table>");
    	}
	}
	print("</td></tr></table>");
	mysql_free_result($conjunt_resultant);
	print("<hr></form>");
	print("</body></html>");
	exit;	
}

if (isset($superior) && $superior=='si') {
	@include("comu.js.php");
	print("<script language='JavaScript'>");
	print("var gcarpetes;");
	print("function gcarp() {");
	print(" window.focus();");
	print(" opt = 'resizable=1,scrollbars=1,width=330,height=400,left=350,top=120';");
	print(" gcarpetes=window.open('$PHP_SELF?idsess=$idsess&gcarpetes=1', 'gcarpetes', opt);");
	print(" gcarpetes.focus();");
	print("}");
	print("</script>");
	print("</head>
	<body  bgcolor='#ccdd88' text='#000000' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
	<form name='formselmiss' method='post' action='$PHP_SELF?idsess=$idsess&superior=si'>
	<input type='hidden' name='pendent' value=''>
	<table border='0' width='100%'><tr>
	<td valign='bottom'>"); 	
	if(!$esPare) print("<input type='checkbox' name='sms'".((isset($sms))?" checked":"")." onClick='parent.frames.contingut.location.href=\"buit.php?idsess=$idsess\"; document.forms.formselmiss.submit();'> <img src='./imatges/sms.gif' border='0' title='Seleccionar entorn SMS'>");
	else print("&nbsp;");
	print("</td>
	<td align='right'><font size='6'>Comunicaci&oacute;".((isset($sms))?" SMS":"")."&nbsp; &nbsp; </font></td>
	</tr></table>
	<hr>
	");

	if(!isset($carpeta)) $carpeta='General';
	if(isset($sms)) {
		$filtrewhere="where de like '%$sess_user|%' and vist like '%EnviatSMS_$sess_user/%' and vist not like '%EliminaSMS_$sess_user/%'".(($carpeta!='General')?" and vist like '%Carp_$sess_user/$carpeta%'":" and vist not like '%Carp_$sess_user/%'");
		$consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."comunicacio $filtrewhere";
	}
	else {
		$filtrewhere="where (de like '%$sess_user|%' or per_a like '%$sess_user|%') and vist not like '%Elimina_$sess_user/%'".(($carpeta!='General')?" and vist like '%Carp_$sess_user/$carpeta%'":" and vist not like '%Carp_$sess_user/%'")." and vist not like '%EnviatSMS_%'";
		$consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."comunicacio $filtrewhere";
	} 
	$conjunt_resultant=mysql_query($consulta, $connect);
	$nseleccionats=mysql_result($conjunt_resultant, 0,0);
	mysql_free_result($conjunt_resultant);

	
	$maxpaginador=6;
	if(isset($tots)) $maxpaginador=10000;

	$paginadoractual=0;
	if(!isset($paginadoranterior)) $paginadoranterior=-1;
	if(!isset($paginadorseguent)) $paginadorseguent=-1;
	print("<input type='hidden' name='paginadorseguent' value='$paginadorseguent'>");
	print("<input type='hidden' name='paginadoranterior' value='$paginadoranterior'>");

	if($nseleccionats>$maxpaginador) {
	   if($paginadorseguent!=-1) {
	     $paginadoractual=$paginadorseguent;
	   }
	   if($paginadoranterior!=-1) {
	     $paginadoractual=$paginadoranterior;
	   }
	}
	if(($paginadoractual-$maxpaginador) >= 0) $paginadorenrere=true; else $paginadorenrere=false;
	if(($paginadoractual+$maxpaginador) < $nseleccionats) $paginadorendavant=true; else $paginadorendavant=false;
	$paginador = ($paginadorenrere)?"<a href='' onClick='document.forms.formselmiss.paginadorseguent.value=\"-1\"; document.forms.formselmiss.paginadoranterior.value=\"0\"; document.forms.formselmiss.submit(); return false;'>":"";
	$paginador.= "<<";
	$paginador.= ($paginadorenrere)?"</a>":"";
	$paginador.= "&nbsp; ";
	$paginador.= ($paginadorenrere)?"<a href='' onClick='document.forms.formselmiss.paginadorseguent.value=\"-1\"; document.forms.formselmiss.paginadoranterior.value=\"".($paginadoractual-$maxpaginador)."\"; document.forms.formselmiss.submit(); return false;'>":"";
	$paginador.= "<";
	$paginador.= ($paginadorenrere)?"</a>":"";
	$paginador.= "&nbsp; &nbsp; Missatges ".(($nseleccionats!=0)?($nseleccionats-$paginadoractual):0)." - ".((($paginadoractual+$maxpaginador)<=$nseleccionats)?($nseleccionats-($paginadoractual+$maxpaginador)+1):(($nseleccionats!=0)?1:0))."&nbsp; &nbsp; ";
	$paginador.= ($paginadorendavant)?"<a href='' onClick='document.forms.formselmiss.paginadoranterior.value=\"-1\"; document.forms.formselmiss.paginadorseguent.value=\"".($paginadoractual+$maxpaginador)."\"; document.forms.formselmiss.submit(); return false;'>":"";
	$paginador.= ">";
	$paginador.= ($paginadorendavant)?"</a>":"";
	$paginador.= "&nbsp; ";
	$paginadorsup=(($maxpaginador*(floor($nseleccionats/$maxpaginador))));
	if($paginadorsup==$nseleccionats) $paginadorsup=$nseleccionats-1;
	if(($nseleccionats%$maxpaginador)==0) $paginadorsup=$nseleccionats-$maxpaginador;
	$paginador.= ($paginadorendavant)?"<a href='' onClick='document.forms.formselmiss.paginadoranterior.value=\"-1\"; document.forms.formselmiss.paginadorseguent.value=\"".$paginadorsup."\"; document.forms.formselmiss.submit(); return false;'>":"";
	$paginador.= ">>";
	$paginador.= ($paginadorendavant)?"</a>":"";
	$paginador.= "&nbsp; de $nseleccionats";
 	

 	$carpetes="<option".(($carpeta=='General')?" selected":"").">General</option><option".(($carpeta=='Paperera')?" selected":"").">Paperera</option>";
	$consulta  = "SELECT carpetes FROM $bdtutoria.$tbl_prefix"."comunicpers WHERE usuari='$sess_user' LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);			
	if(0!=mysql_num_rows($conjunt_resultant)) {
		$fila=mysql_fetch_row($conjunt_resultant);
		mysql_free_result($conjunt_resultant);
		if($fila[0]!="") {
			$carps=explode("|", $fila[0]);
			for($i=0; $i<count($carps); ++$i) {
				$carpetes.="<option".(($carpeta==$carps[$i])?" selected":"").">".$carps[$i]."</option>";
			}
		}
	}
	else mysql_free_result($conjunt_resultant);
			
	print("<a href='' title='Crea un nou missatge' onClick='parent.frames.contingut.location.href=\"$PHP_SELF?idsess=$idsess&nou=".((!isset($sms))?"si":"siSMS")."\"; return false;'><img src='./imatges/nou.gif' border='0'>".((!$sms)?"Nou missatge":"Nou missatge SMS")."</a> &nbsp; &nbsp; Carpeta: <select name='carpeta' onChange='parent.frames.contingut.location.href=\"buit.php?idsess=$idsess\"; document.forms.formselmiss.submit();'>$carpetes</select> <a href='' title='Gesti&oacute; carpetes' onClick='gcarp(); return false;'><img src='./imatges/carpetaob.gif' border='0'></a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; $paginador &nbsp;<input type='checkbox' name='tots'".((isset($tots))?" checked":"")." onClick='document.forms.formselmiss.submit();'>Tots<br>");

	$consulta="SELECT id, sub, de, per_a, datahora, assumpte, adjunts, vist FROM $bdtutoria.$tbl_prefix"."comunicacio $filtrewhere order by datahora desc LIMIT $paginadoractual,$maxpaginador";
	$conjunt_resultant=mysql_query($consulta, $connect);

	print("</form>
	<div id='llista' style='position:relative; height:108; overflow:auto; border-width:2; border-style:ridge; border-color:#42A5A5; background-color:#FFFFCC; visibility:visible'>
	");
	if($nseleccionats==0) {
		print("No tens cap missatge.");	
	} else {
		print("<table border='0' width='100%'>");
		$nseleccio=$nseleccionats-$paginadoractual;
		while ($fila=mysql_fetch_row($conjunt_resultant)) {
			$remitent=explode("|", $fila[2]);
			$remitentiden=$remitent[0];
			$remitentnomreal=$remitent[1];
			if($sess_user==$remitentiden) {
				$nomsp=explode(";", $fila[3]);
				$nomspera="";
				for($i=0; $i<count($nomsp); ++$i) {
					$nomspe=explode("|", $nomsp[$i]);
					$nomspera.=	(($nomspera!="")?"; ":"").$nomspe[1];
				}
			}
			else $nomspera=(($esPare)?"Pares de ":"").$sess_nomreal;
			print("<tr><td width='5%' valign='top'>$nseleccio.-</td><td width='2%' valign='top'><img id='img$fila[0]' src='".((ereg("Vist_$sess_user/|Enviat_$sess_user/|EnviatSMS_$sess_user/",$fila[7])==false)?"./imatges/banderola.gif' title='Nou, no llegit'":(((ereg("Pendent_$sess_user/",$fila[7])==true))?"./imatges/banderolav.gif' title='Pendent de resposta'":"./imatges/pixelblank.gif'"))."></td><td width='1%' valign='top'>".(($fila[6]!='')?"<img src='./imatges/adj_arxiu.gif'>":"&nbsp;")."</td><td width='29%' valign='top'> De: <span title='Per a: $nomspera'>$remitentnomreal</span></td><td width='43%' valign='top'> <a href='' title='De: $remitentnomreal => Per a: $nomspera' onClick='parent.frames.contingut.location.href=\"$PHP_SELF?idsess=$idsess&mostracontingut=$fila[0]&nseleccio=$nseleccio&carpactual=$carpeta".((isset($sms))?"&sms=si":"")."\"; return false;'>".(($fila[5]!='')?$fila[5]:"N/A")."</a></td><td width='20%' valign='top'> ".$nomDiaSem[date('w',$fila[4])].", ".date('j-n-Y',$fila[4])."&nbsp; ".date('H:i',$fila[4])."</td></tr>");
			--$nseleccio;
		}
		print("</table>");
	}
	mysql_free_result($conjunt_resultant);
	print("
	</div>
	<hr>
	</body>
	</html>
	");
	exit;
} 

function traduir_cadena( $alum, $original ) {

	global $bdalumnes, $tbl_prefix;
	 
  $consulta="SELECT cognom_alu, cognom2_al, nom_alum, sexe, pla_estudi, curs, grup, adreca, nom_munici, codi_posta, primer_tel, cognom1_pa, cognom2_pa, nom_pare, cognom1_ma, cognom2_ma, nom_mare FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE numero_mat='$alum'";
  $conjunt_resultant=mysql_query($consulta, $connect);
  $fila=mysql_fetch_row($conjunt_resultant);
  $trans["@PARENOM"]= "$fila[13]";
  $trans["@PARECOGNOM1"]= "$fila[11]";
  $trans["@PARECOGNOM2"]= "$fila[12]";
  $trans["@MARENOM"]= "$fila[16]";
  $trans["@MARECOGNOM1"]= "$fila[14]";
  $trans["@MARECOGNOM2"]= "$fila[15]";
  $trans["@ADRECA"]= "$fila[7]";
  $trans["@TELF"]="$fila[10]";
  $trans["@CODIPOSTAL"]= "$fila[9]";
  $trans["@POBLACIO"]= "$fila[8]";
  $trans["@ALUMNENOM"]= "$fila[2]";
  $trans["@ALUMNECOGNOM1"]= "$fila[0]";
  $trans["@ALUMNECOGNOM2"]= "$fila[1]";
  $trans["@CURS"]= "$fila[5]";
  $trans["@GRUP"]= "$fila[6]";
  $trans["@ETAPA"]= "$fila[4]";
  $trans["@NOMDIRECTOR"]=$nomdirector;
    

  $tok="@GENEREALUM(";
  $straux=strstr($original, $tok);
  while($straux!=false) {
    $naux2=1+strpos($straux,')');
    $straux3=substr($straux, 0, $naux2);
    $straux5 = $straux3;
    $naux3=strpos($straux3, ':');
    if($fila[3]=='HOME') {
      $straux4=substr($straux3, strlen($tok), $naux3-strlen($tok));
    }
    else {
      $straux4=substr($straux3, $naux3+1, -1);
    }
    $original=str_replace($straux5, $straux4, $original);
    $straux=strstr($original, $tok);
  }
  mysql_free_result($conjunt_resultant);
  $trans["@DATAAVUI"]= date('j-n-Y',$datatimestamp);
  
  if($alum!='') $trans["@TUTORGRUP"]=cercaTutor("$fila[5] $fila[6] $fila[4]");
  else $trans["@TUTORGRUP"]="";
  

  $tok="@GENEREDIR(";
  $straux=strstr($textinflliure, $tok);
  while($straux!=false) {
    $naux2=1+strpos($straux,')');
    $straux3=substr($straux, 0, $naux2);
    $straux5 = $straux3;
    $naux3=strpos($straux3, ':');
    if($sexdirector=='H') {
      $straux4=substr($straux3, strlen($tok), $naux3-strlen($tok));
    }
    else {
      $straux4=substr($straux3, $naux3+1, -1);
    }
    $original=str_replace($straux5, $straux4, $original);
    $straux=strstr($original, $tok);
  }
  $final=strtr($original,$trans);
  
  return $final;
} // traduir_cadena
?>

</head>
<?@include("comu.js.php");?>
<frameset rows="206,*"  border="0">
<frame name="superior" id="superior" scrolling="no" noresize marginwidth="0" marginheight="0" src="<?=$PHP_SELF."?idsess=".$idsess."&superior=si"?>"</frame>
<frame name="contingut" id="contingut" scrolling="auto" noresize marginwidth="5" marginheight="2" src="buit.php?idsess=<?=$idsess?>"</frame>
</frameset>
</html><?/*LS0tIDE5LTQtMjAwOCwgMjM6MzA6NTQgQXBUdXRvcmlhVjEuMC50YXIuZ3ogIDg4LjcuMTE2LjY2IC0tLQ==*/?>