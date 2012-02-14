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
@include("comu.php");
panyacces("Administrador");

class xml2Array {   
   var $arrOutput = array();
   var $resParser;
   var $strXmlData;   
   function parse($strInputXML) {  
           $this->resParser = xml_parser_create ();
           xml_set_object($this->resParser,$this);
           xml_set_element_handler($this->resParser, "tagOpen", "tagClosed");           
           xml_set_character_data_handler($this->resParser, "tagData");       
           $this->strXmlData = xml_parse($this->resParser,$strInputXML );
           if(!$this->strXmlData) {
               die(sprintf("XML error: %s a la linia %d",
           xml_error_string(xml_get_error_code($this->resParser)),
           xml_get_current_line_number($this->resParser)));
           }                          
           xml_parser_free($this->resParser);           
           return $this->arrOutput;
   }
   function tagOpen($parser, $name, $attrs) {
       $tag=array("name"=>$name,"attrs"=>$attrs); 
       array_push($this->arrOutput,$tag);
   }   
   function tagData($parser, $tagData) {
       if(trim($tagData)) {
           if(isset($this->arrOutput[count($this->arrOutput)-1]['tagData'])) {
               $this->arrOutput[count($this->arrOutput)-1]['tagData'] .= $tagData;
           } 
           else {
               $this->arrOutput[count($this->arrOutput)-1]['tagData'] = $tagData;
           }
       }
   }   
   function tagClosed($parser, $name) {
       $this->arrOutput[count($this->arrOutput)-2]['children'][] = $this->arrOutput[count($this->arrOutput)-1];
       array_pop($this->arrOutput);
   }
}


$opcio="";
$opcio=$_GET['opc'];
if($opcio=="") $opcio=$_POST['opc'];
switch($opcio) { 
	case "llistaals": 
	   llistarAlumnes();
	   break;
	case "llistanotes":
	   llistarNotes();
	   break;
	case "esborraNota":
		esborraNota();
		llistarNotes();
		break;
	case "modificaDesa":
		modificaDesa();
		llistarNotes();
		break;
	case "afegirDesa":
		afegirDesa();
		llistarNotes();
		break;
	default: 
	   sortidaDefecte(); 
}

function llistarAlumnes() {
	global $bdalumnes, $tbl_prefix, $connect;
	$gru=split(' ',$_GET["cge"]);
	$consulta="SELECT numero_mat, concat(cognom_alu,' ',cognom2_al,', ',nom_alum) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE curs='".$gru[0]."' AND grup='".$gru[1]."' AND pla_estudi='".$gru[2]."' ORDER BY cognom_alu, cognom2_al ASC";
	$conjunt_resultant=mysql_query($consulta, $connect);
	header('Content-Type: text/xml');
	print("<?xml version='1.0' encoding='ISO-8859-1' ?>\n");
	print("<alumnes cge='".$_GET["cge"]."'>");
	while($fila=mysql_fetch_row($conjunt_resultant)) {
		print("<alumne>");
		print("<numero_mat>$fila[0]</numero_mat>");
		print("<nomcomplet>$fila[1]</nomcomplet>");
		print("</alumne>");
	}
	print("</alumnes>");
	mysql_free_result($conjunt_resultant);
}

function llistarNotes() {
		global $bdtutoria, $tbl_prefix, $connect;
		$alum=$_GET["alum"]; if($alum=="") $alum=$_POST["alum"];
		$aval=$_GET["aval"]; if($aval=="") $aval=$_POST["aval"];
		$consulta="SELECT n.ref_credit, n.valor, n.memo, l.nomcredit, n.id FROM $bdtutoria.$tbl_prefix"."notes n, $bdtutoria.$tbl_prefix"."llistacredits l WHERE n.ref_credit=l.codi and n.ref_alum='".$alum."' and n.ref_aval='".$aval."' order by l.pla_estudis desc, l.tipus, l.codi";
		$conjunt_resultant=mysql_query($consulta, $connect);
		header('Content-Type: text/xml');
		print("<?xml version='1.0' encoding='ISO-8859-1' ?>\n");
		print("<alumne nmatricula='".$alum."' aval='".$aval."'>");
		while($fila=mysql_fetch_row($conjunt_resultant)) {
			print("<assignatura nid='".$fila[4]."' codi='".$fila[0]."' nom='".$fila[3]."' valor='".$fila[1]."'>".$fila[2]."</assignatura>");
		}
		print("</alumne>");
		mysql_free_result($conjunt_resultant);		
}

function esborraNota() {
	global $bdtutoria, $tbl_prefix, $connect;
	$consulta="DELETE FROM $bdtutoria.$tbl_prefix"."notes WHERE id='".$_GET["nid"]."' LIMIT 1";
	mysql_query($consulta, $connect);
}

function modificaDesa() {
	global $bdtutoria, $tbl_prefix, $connect;	
	$objXML = new xml2Array();
 	$arrOutput = $objXML->parse(stripslashes($_POST["xml"]));
 	$nid=$arrOutput[0]["children"][0]["tagData"];
 	$valor=$arrOutput[0]["children"][1]["tagData"];
 	$memo=addslashes($arrOutput[0]["children"][2]["tagData"]);
	$consulta="UPDATE $bdtutoria.$tbl_prefix"."notes SET valor='$valor', memo='$memo' WHERE id='$nid' LIMIT 1";
	mysql_query($consulta, $connect);	
}

function afegirDesa() {
	global $bdtutoria, $tbl_prefix, $connect, $sess_user;	
	$objXML = new xml2Array();
 	$arrOutput = $objXML->parse(stripslashes($_POST["xml"]));
 	$refaval=$_POST["aval"];
 	$refalum=$arrOutput[0]["children"][1]["tagData"];
 	$refcredit=$arrOutput[0]["children"][0]["tagData"];
 	$valor=$arrOutput[0]["children"][2]["tagData"];
 	$memo=addslashes($arrOutput[0]["children"][3]["tagData"]); 	
 	$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."notes SET ref_aval='$refaval', ref_alum='$refalum', ref_credit='$refcredit', valor='$valor', usuari='$sess_user', memo='$memo'";
 	mysql_query($consulta, $connect);		
}

function sortidaDefecte() {
	global $idsess, $PHP_SELF, $bdtutoria, $tbl_prefix, $connect, $llista_grups;
?>
	<html>
	<head>
	<title>Tutoria</title>
	<?
	@include("comu.js.php");
	?>
	<script language='JavaScript'>
var http_request=new Array();
function creaObjAjax() {
	var peticio=http_request.length; 
	http_request[peticio]=false;

	if (window.XMLHttpRequest) { 
		http_request[peticio] = new XMLHttpRequest();
		if (http_request[peticio].overrideMimeType) http_request[peticio].overrideMimeType('text/xml');
    }
    else if (window.ActiveXObject) { 
		try {
			http_request[peticio] = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request[peticio] = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request[peticio]) {
		alert('Falla :( No es posible crear una instancia XMLHTTP');
		return -1;
	}
	else return peticio;	    
}

var accions=new Array();
function fer_peticio(params, accio, getpost) {
	var peticio=creaObjAjax()
	if(peticio==-1) return;
	accions[peticio]=accio;
	ocultaMostraCapa("temps_espera","v");
	if (ie) {
		document.all.temps_espera.style.left=window.event.clientX+5 + document.body.scrollLeft;;
		document.all.temps_espera.style.top=window.event.clientY+5 + document.body.scrollTop;
 	} 
 	if (ns4) {
      document.layers["temps_espera"].top = ev.y;
      document.layers["temps_espera"].left = ev.x;
 	} 
 	if (ns6) {
    	document.getElementById("temps_espera").style.top = (((ev.pageY+5)<0)?10:ev.pageY+5);
    	document.getElementById("temps_espera").style.left = (((ev.pageX+5)<0)?10:ev.pageX+5);
 	}
	http_request[peticio].onreadystatechange = function() { 
		if (http_request[peticio].readyState == 4) {
			if (http_request[peticio].status == 200) {
				eval ( accions[peticio]+"(http_request[peticio]);" );
				accions[peticio]="";
			} else {
				alert('Hi ha problemes amb la petició: '+peticio,+' '+accions[peticio]+' status: '+http_request[peticio].status);
				accions[peticio]="";
			}
			var trobat=false;
			for(var i=0; i<accions.length; ++i) {
				if(accions[peticio]!="") {
					trobat=true;
					break;
				}
			}
			if(!trobat) ocultaMostraCapa("temps_espera","o");
		}
	}
	if(getpost=='get') {       
		http_request[peticio].open('GET', "<?=$PHP_SELF?>"+"?"+params, true);
		http_request[peticio].setRequestHeader( "If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT" );
		http_request[peticio].send(null);
	}
	else {
		try {
			http_request[peticio].open('POST', "<?=$PHP_SELF?>", true);
			http_request[peticio].setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http_request[peticio].send(params);
		} catch (e) {}
	}
}

function fllistaals(hr) {
	var doc=hr.responseXML;
	var cge=doc.getElementsByTagName('alumnes')[0].getAttribute('cge');
	var alumne=doc.getElementsByTagName('alumne');
	html="<table border='0' width='100%'>\n";
	html+="<tr><td width='20'><input type='checkbox' id='chkTots' name='chkTots' title='Selecciona´ls tots' onClick='var els=document.getElementsByTagName(\"input\"); if(document.getElementById(\"chkTots\").checked) {for(var i=0; i<els.length; ++i) {if(els[i].getAttribute(\"name\")==(els[i].getAttribute(\"name\")).match(/^ck_.+/g)) els[i].checked=true;} } else {for(var i=0; i<els.length; ++i) {if(els[i].getAttribute(\"name\")==(els[i].getAttribute(\"name\")).match(/^ck_.+/g)) els[i].checked=false;} }'></td>";
	html+="<td width='27'>&nbsp;</td><td>Alumnes: &nbsp; &nbsp; &nbsp; &nbsp; <a href='' title='Afegeix notes als alumnes seleccionats' onClick='afegir(); return false;'>Afegir Notes</a>";
	html+=" &nbsp; &nbsp; &nbsp; &nbsp; <a href='' title='Mostra les notes dels alumnes seleccionats' onClick='var llistachk= document.getElementsByTagName(\"input\"); for (var i=0; i<llistachk.length; ++i) {if(llistachk[i].getAttribute(\"type\")==\"checkbox\" && llistachk[i].getAttribute(\"name\")!=null && llistachk[i].getAttribute(\"name\")==(llistachk[i].getAttribute(\"name\")).match(/^ck_.+/g) && llistachk[i].checked ) mostranotes(llistachk[i].value);} return false;'>Mostra´ls</a>";
	html+=" &nbsp; &nbsp; &nbsp; &nbsp; <a href='' title='Oculta les notes dels alumnes seleccionats' onClick='var llistachk= document.getElementsByTagName(\"input\"); for (var i=0; i<llistachk.length; ++i) {if(llistachk[i].getAttribute(\"type\")==\"checkbox\" && llistachk[i].getAttribute(\"name\")!=null && llistachk[i].getAttribute(\"name\")==(llistachk[i].getAttribute(\"name\")).match(/^ck_.+/g) && llistachk[i].checked ) ocultanotes(llistachk[i].value);} return false;'>Oculta´ls</a></td></tr>\n";
	html+="<tr><td colspan='3'>&nbsp;</td></tr>";
	for(var i=0; i<alumne.length; ++i) {
		var numero_mat=alumne[i].childNodes[0].childNodes[0].nodeValue;
		var nomcomplet=alumne[i].childNodes[1].childNodes[0].nodeValue;
		html+="<tr><td valign='top'><input type='checkbox' title='Selecciona alumne' name='ck_"+numero_mat+"' value='"+numero_mat+"'></td><td valign='top'><a href='' onClick='obreFoto(\"./foto.php?idsess=<?=$idsess?>&foto="+numero_mat+"\", \""+nomcomplet+"\"); return false;'><img src='./foto.php?idsess=<?=$idsess?>&foto="+numero_mat+"' width='25' height='34' border='0'></a></td>";
		html+="<td valign='top'><b>"+nomcomplet+"</b><br>&nbsp; &nbsp; &nbsp; <a href='' title='Mostra les notes' onClick='mostranotes(\""+numero_mat+"\"); return false;'>Mostra</a>";
		html+="&nbsp; &nbsp; <a href='' title='Oculta les notes' onClick='ocultanotes(\""+numero_mat+"\"); return false;'>Oculta</a><br>";
		html+="<span id='sp_"+numero_mat+"' style='display:none'></span>";
		html+="</td></tr>\n";
	}
	html+="</table>";
	ocultaMostraCapa('capamodifafg','o');
	escriuACapa('llistaals',html);
}

function mostranotes(numero_mat) {
	fer_peticio("idsess=<?=$idsess?>&opc=llistanotes&alum="+numero_mat+"&aval=<?echo $_GET["aval"];?>", "fllistanotes", "get");	
}
function ocultanotes(numero_mat) {
	ocultaMostraCapa("capamodifafg","o");
	ocultaMostraCapa2("sp_"+numero_mat, "none");
}

function fllistanotes(hr) {
	//alert(hr.responseText);
	var doc=hr.responseXML;
	var nmatr=doc.getElementsByTagName('alumne')[0].getAttribute('nmatricula');
	var aval=doc.getElementsByTagName('alumne')[0].getAttribute('aval');
	var html="<table border='0' width='100%'>\n";
	html+="<tr bgcolor='#0088cc'><td width='22'>Codi</td><td width='60'>Nom Assign.</td><td><center>Nota</center>";
	if(document.forms.introd1.nomitems.value!="") {
		html+="<table border='0' width='100%'><tr>";
		var nomite=(document.forms.introd1.nomitems.value).split('|');
		for(var j=0; j<document.forms.introd1.nitems.value; ++j) {
			nomit=nomite[j].split('->');
			html+="<td align='center' title='"+nomit[1]+"'>"+((j==document.forms.introd1.nitems.value-1)?"<b>":"")+nomit[0]+((j==document.forms.introd1.nitems.value-1)?"</b>":"")+"</td>";
		}	
		html+="</tr></table>";
	}
	html+="</td><td>&nbsp;</td><td>Susp./Avals : <span title='Suspeses sobre Avaluades' id='estadistica"+nmatr+"'></span></td></tr>\n";
	var assignatura=doc.getElementsByTagName('assignatura');
	var suspeses=0, avaluades=0, mitjana=0;
	for(var i=0; i<assignatura.length; ++i) {
		var nid=assignatura[i].getAttribute('nid');
		var codi=assignatura[i].getAttribute('codi');
		var nom=assignatura[i].getAttribute('nom');
		var valor=assignatura[i].getAttribute('valor');
		var memo=escape( (assignatura[i].childNodes.length>0)?assignatura[i].childNodes[0].nodeValue:""  );
		html+="<tr><td>"+codi+"</td><td>"+unescape(nom)+"</td><td>";
		if(valor!="") {
			++avaluades;
			var val=valor.split('z');
			html+="<table border='0' width='100%'><tr>";
			for(var j=0; j<document.forms.introd1.nitems.value; ++j) {
				if(val[j]=="I"||val[j]=="1"||val[j]=="2"||val[j]=="3"||val[j]=="4") var vermell=true; else var vermell=false;
				if(j==document.forms.introd1.nitems.value-1) var negreta=true; else var negreta=false;
				if(vermell&&negreta) ++suspeses;
				var menor=false;
				if(document.forms.introd1.nitems.value>1 && j!=document.forms.introd1.nitems.value-1) var menor=true
				if(isNaN(val[j])) mitjana=-1;
				if(mitjana!=-1 && !menor) mitjana+=parseInt(val[j]);
				html+="<td align='center'>"+((menor)?"<font size='-2'>":"")+((negreta)?"<b>":"")+((vermell)?"<font color='red'>":"")+((val[j]!="")?val[j]:"&nbsp;")+((vermell)?"</font>":"")+((negreta)?"</b>":"")+((menor)?"</font>":"")+"</td>";	
			}
			html+="</tr></table>";
		}
		else html+="&nbsp;";
		html+="</td><td align='center'><a href='' title='Mostra l´anotaci&oacute;' onClick='alert(unescape(\""+memo+"\"));return false;'>"+((memo!="")?"<b>":"")+"T"+((memo!="")?"</b>":"")+"</a></td>";
		html+="<td align='center'><a href='' title='Modifica aquesta nota' onClick='modifica(\""+nid+"\", \""+nmatr+"\", \""+aval+"\", \""+codi+"\", \""+nom+"\", \""+valor+"\", \""+memo+"\"); return false;'>Modificar</a>";
		html+=" <a href='' title='Elimina aquesta nota' onClick='ocultaMostraCapa(\"capamodifafg\",\"o\"); if(confirm(\"Segur que vols eliminar aquesta nota?\")) fer_peticio(\"idsess=<?=$idsess?>&opc=esborraNota&alum="+nmatr+"&aval="+aval+"&nid="+nid+"\", \"fllistanotes\", \"get\"); return false;'>Eliminar</a>";
		html+="</td></tr>\n";
	}
	html+="</table><hr>";
	ocultaMostraCapa('capamodifafg','o');
	escriuACapa('sp_'+nmatr,html);
	ocultaMostraCapa2('sp_'+nmatr,'block');
	if(avaluades==0 || mitjana==NaN) mitjana=-1;
	if(mitjana!=-1) mitjana/=avaluades;
	escriuACapa('estadistica'+nmatr, ((suspeses>0)?'<font color=\"#ff0000\">':'')+suspeses+((suspeses>0)?'</font>':'')+'/<b>'+avaluades+'</b>'+((mitjana!=-1 && !isNaN(mitjana))?'<br>Mitjana: '+mitjana.toFixed(3):''));
}

function modifica(nid, nmatr, aval, codi, nom, valor, memo) {
	var html="<form name='f1' method='' action='' onSubmit=''>";
	html+="<table border='0'><tr><td><b>Modificar Nota:</b></td><td>";
	
	if(document.forms.introd1.nomitems.value!="") {
		html+="<table border='0' width='100%'><tr>";
		var nomite=(document.forms.introd1.nomitems.value).split('|');
		for(var j=0; j<document.forms.introd1.nitems.value; ++j) {
			nomit=nomite[j].split('->');
			html+="<td align='center' title='"+nomit[1]+"'>"+((j==document.forms.introd1.nitems.value-1)?"<b>":"")+nomit[0]+((j==document.forms.introd1.nitems.value-1)?"</b>":"")+"</td>";
		}	
		html+="</tr></table>";
	}
	else html+="&nbsp;";
	
	html+= "</td><td>&nbsp;</td></tr>";
	html+= "<tr><td>"+codi + " - " + unescape(nom) + ": </td><td>";
	var valitems=valor.split('z');
	var vals=document.forms.introd1.valors.value.split('|');
	html+="<table border='0' width='100%'><tr>";
	for (var i=0; i<document.forms.introd1.nitems.value; ++i) {
		html+="<td align='center'><select name='s"+i+"'><option"+((valitems[i]=="")?" selected":"")+"></option>";
		for(var j=0; j<vals.length; ++j) {
			html+="<option"+((valitems[i]==vals[j])?" selected":"")+">"+vals[j]+"</option>";	
		}
		html+="</select></td>";	
	}
	html+="</tr></table>";
	html+="</td><td>";
	html+="<input type='hidden' name='memo' value='"+memo+"'>";
	var gruixT=((memo!="")?true:false);
	html+="<a href='' title='Modifica l´anotaci&oacute;' onClick='var a; if((a=prompt(\"Introdueix el text\",unescape(document.forms.f1.memo.value))) != null) document.forms.f1.memo.value=escape(a); return false;'>"+((gruixT)?"<b>":"")+"T"+((gruixT)?"</b>":"")+"</a>";
	html+="</td></tr></table>";
	html+="</form>";
	html+="<center><a href='' onClick='modificadesa(\""+nid+"\",\""+nmatr+"\",\""+aval+"\"); ocultaMostraCapa(\"capamodifafg\",\"o\"); return false;'>Desa</a> <a href='' onClick='ocultaMostraCapa(\"capamodifafg\",\"o\"); return false;'>Cancela</a></center><br>";
	escriuACapa('capamodifafg',html);
	ocultaMostraCapa('capamodifafg','v');
	posicionaCapaModifAfg();
}

function modificadesa(nid, nmatr, aval) {
	var cadenaXML="<"+"?xml version='1.0' encoding='ISO-8859-1' ?"+">\n";
	cadenaXML+='<modificadesa>';
	cadenaXML+='<nid>'+nid+'</nid>';
	var cad="";
	for (var i=0; i<document.forms.introd1.nitems.value; ++i) {
		eval("cad+=((i>0)?'z':'')+document.forms.f1.s"+i+".options[document.forms.f1.s"+i+".selectedIndex].text;");
	} 
	cadenaXML+='<valor>'+cad+'</valor>';
	cadenaXML+='<memo>'+document.forms.f1.memo.value+'</memo>';	
	cadenaXML+='</modificadesa>';
	fer_peticio("idsess=<?=$idsess?>&opc=modificaDesa&alum="+nmatr+"&aval="+aval+"&xml="+cadenaXML, "fllistanotes", "post");
}

function afegir() {
	var html="<form name='f2' method='' action='' onSubmit=''>";
	html+="<table border='0'><tr><td colspan='2'><b>Afegir notes als alumnes seleccionats:</b></td></tr>";
	html+="<tr><td colspan='2'>";	
	html+="<select name='credits'><option></option>";
	html+="<?$consulta="SELECT concat(codi, ' ', nomcredit,'--->',tipus,'-',pla_estudis) FROM $bdtutoria.$tbl_prefix"."llistacredits order by pla_estudis desc, tipus, codi";
		$conjunt_resultant=mysql_query($consulta, $connect);
		while($fila=mysql_fetch_row($conjunt_resultant)) print("<option>".rawurldecode($fila[0])."</option>");
	?>";
	html+="</select>";	
	html+="</td></tr>";
	html+="<tr><td>";
	if(document.forms.introd1.nomitems.value!="") {
		html+="<table border='0' width='100%'><tr>";
		var nomite=(document.forms.introd1.nomitems.value).split('|');
		for(var j=0; j<document.forms.introd1.nitems.value; ++j) {
			nomit=nomite[j].split('->');
			html+="<td align='center' title='"+nomit[1]+"'>"+((j==document.forms.introd1.nitems.value-1)?"<b>":"")+nomit[0]+((j==document.forms.introd1.nitems.value-1)?"</b>":"")+"</td>";
		}	
		html+="</tr></table>";
	}
	else html+="&nbsp;";	
	html+= "</td><td>&nbsp;</td></tr>";
	html+="<tr><td>";	
	var vals=document.forms.introd1.valors.value.split('|');
	html+="<table border='0' width='100%'><tr>";
	for (var i=0; i<document.forms.introd1.nitems.value; ++i) {
		html+="<td align='center'><select name='s"+i+"'><option selected></option>";
		for(var j=0; j<vals.length; ++j) {
			html+="<option>"+vals[j]+"</option>";	
		}
		html+="</select></td>";	
	}
	html+="</tr></table>";
	html+="</td><td>";
	html+="<input type='hidden' name='memo' value=''>";
	html+="<a href='' title='Inserir anotaci&oacute;' onClick='var a; if((a=prompt(\"Introdueix el text\",unescape(document.forms.f2.memo.value))) != null) document.forms.f2.memo.value=escape(a); return false;'>T</a>";
	html+="</td></tr></table>";
	html+="</form>";
	html+="<p><center><a href='' title='Afegeix l´assignatura als alumnes seleccionats' onClick='afegirdesa(\"<?echo $_GET["aval"];?>\"); return false;'>Afegir</a> &nbsp; &nbsp; <a href='' title='Cancela l´operaci&oacute;' onClick='ocultaMostraCapa(\"capamodifafg\",\"o\"); return false;'>Cancela</a></center><br>";
	escriuACapa('capamodifafg',html);
	ocultaMostraCapa('capamodifafg','v');
	posicionaCapaModifAfg();
}

function afegirdesa(aval) {	
	var llistachk= document.getElementsByTagName("input");
	var llistaals="";
	for (var i=0; i<llistachk.length; ++i) {
		if(llistachk[i].getAttribute('type')=='checkbox' && llistachk[i].getAttribute('name')!=null && llistachk[i].getAttribute('name')==(llistachk[i].getAttribute('name')).match(/^ck_.+/g) && llistachk[i].checked )
		   llistaals+=((llistaals!="")?"|":"")+llistachk[i].value;
	}
	if(llistaals=="") {
		alert("Ep!, no has seleccionat cap alumne");
		return;
	}
	if(document.forms.f2.credits.options[document.forms.f2.credits.selectedIndex].text=="") {
		alert("Ep!, no has seleccionat cap crèdit");
		return;	
	}
	var val="";
	for (var i=0; i<document.forms.introd1.nitems.value; ++i) {
		eval("val+=((i>0)?'z':'')+document.forms.f2.s"+i+".options[document.forms.f2.s"+i+".selectedIndex].text;");
	}
	var refcredit=(document.forms.f2.credits.options[document.forms.f2.credits.selectedIndex].text).split(' ')[0];
	llistaal=llistaals.split('|');
	for(i=0; i<llistaal.length; ++i) {
		var cadenaXML="<"+"?xml version='1.0' encoding='ISO-8859-1' ?"+">\n";
		cadenaXML+='<afegirdesa>\n';
		cadenaXML+='<credit>'+refcredit+'</credit>\n';
		cadenaXML+='<alumne>'+llistaal[i]+'</alumne>\n';
		cadenaXML+='<valor>'+val+'</valor>\n';
		cadenaXML+='<memo>'+document.forms.f2.memo.value+'</memo>\n';
		cadenaXML+='</afegirdesa>';
		fer_peticio("idsess=<?=$idsess?>&opc=afegirDesa&alum="+llistaal[i]+"&aval="+aval+"&xml="+cadenaXML, "fllistanotes", "post");
	}
}

function posicionaCapaModifAfg() {
 if (ie) {
	document.all.capamodifafg.style.left=10; //window.event.clientX + document.body.scrollLeft;;
	document.all.capamodifafg.style.top=window.event.clientY-88 + document.body.scrollTop;
 } 
 if (ns4) {
      document.layers["capamodifafg"].top = ev.y;
      document.layers["capamodifafg"].left = 10; //ev.x;
 } 
 if (ns6) {
    document.getElementById("capamodifafg").style.top = (((ev.pageY-88)<0)?10:ev.pageY-88);
    document.getElementById("capamodifafg").style.left = 10; //(((ev.pageX-300)<0)?10:ev.pageX-300);
 }
}

</script>
</head>
<body bgcolor="#ccdd88" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id='capamodifafg' style='position:absolute; margin-top:95; margin-left:10; border-width:2; border-style:ridge; border-color:#42A5A5; background-color:#FFFFCC; visibility:hidden'></div>
<div id="temps_espera" style="position:absolute; margin-top:0; margin-left:0; visibility:hidden;"><a href="" title="Clica per aturar" onClick="for(var i=0; i<http_request.length; ++i) http_request[i].abort(); ocultaMostraCapa('temps_espera','o'); return false;"><img border='0' src="imatges/espera.gif"></a></div>
<table border='0' width='100%'>
<tr><td><font size='6'>&nbsp; Edici&oacute; notes d'avaluaci&oacute;&nbsp; &nbsp; </font><br>&nbsp; &nbsp; &nbsp; &nbsp; <h3 style='display:inline'><?echo $_GET["aval"];?></h3>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='' onClick='window.print(); return false;'>Imprimir</a></td>
<td align='right'>&nbsp;</td>
</tr></table><hr>

<?
$consulta="select curs, grup, pla_estudi, nomaval, nitems, nomitems, valors from $bdtutoria.$tbl_prefix"."avaluacions where refaval=\"".$_GET["aval"]."\" LIMIT 1";
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_row($conjunt_resultant);
?>
<form name="introd1" method="" action="" onSubmit="">
<input type="hidden" name="nomaval" value="<?=$fila[3]?>">
<input type="hidden" name="nitems" value="<?=$fila[4]?>">
<input type="hidden" name="nomitems" value="<?=$fila[5]?>">
<input type="hidden" name="valors" value="<?=$fila[6]?>">
&nbsp; &nbsp; Curs: <select name="cge" onChange="if(document.forms.introd1.cge.options[document.forms.introd1.cge.selectedIndex].text!='') fer_peticio('idsess=<?=$idsess?>&opc=llistaals&cge='+document.forms.introd1.cge.options[document.forms.introd1.cge.selectedIndex].text, 'fllistaals', 'get'); else {ocultaMostraCapa('capamodifafg','o'); escriuACapa('llistaals','');}">
<option></option>
<
<?
for($i=0; $i<count($llista_grups); ++$i) {
	$cge=explode(' ', $llista_grups[$i]);
	if(($fila[0]==$cge[0] || $fila[0]=="Tots") && ($fila[1]==$cge[1] || $fila[1]=="Tots") && ($fila[2]==$cge[2] || $fila[2]=="Tots") ) {
		?>
		<option><?=$llista_grups[$i]?></option>
		<?
	}
}	
mysql_free_result($conjunt_resultant);
?>	   
</select> 	
</form>	    	
<span id='llistaals'></span>
<hr>	    	
</body>
</html>
<?
}
?>




