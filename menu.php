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
<?php
@include("linkbd.inc.php");
@include("comu.php");

panyacces("Privilegis");
?>
<title>Tutoria - <?php print("$nomcentre - $poblaciocentre");?></title>
<link rel="stylesheet" type="text/css" href="css/menu.css" />
<style type="text/css">
<?=(privilegis('-', '-','-')?"#subConfiguracio ":"")?> {
	position:absolute; 
	background-color: #aabbcc; 
	cursor:pointer; 
	visibility:hidden; 
	font-family:serif; 
	font-weight:normal; 
	font-style:normal; 
	font-size:14; 
	color:#000000; 
	text-align:left; 
	vertical-align:middle; 
	z-index:2
}
<?=(privilegis('-', '-','-')?"#subConfiguracioOmbra ":"")?>
{position:absolute; background-color: #000000; cursor:auto; visibility:hidden; z-index:1}

<?php
// bingen: height = 21 * num_rows
if (privilegis('-', '-','-')) {
	print("#subConfiguracio 
	{top:23px; left:421px; width:150px; height:274px}\n");
	print("#subConfiguracioOmbra 
	{top:29px; left:427px; width:150px; height:274px} ");
}
?>

</style>

</head>

<?php
	$consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."comunicacio WHERE per_a like '%$sess_user|%' and vist not like '%Vist_$sess_user/%' and vist not like '%Enviat_$sess_user/%' and vist not like '%EnviatSMS_%'";
	$conjunt_resultant=mysql_query($consulta, $connect);
	if(mysql_result($conjunt_resultant, 0,0)==0) $tensmissatges=false;
	else $tensmissatges=true;
	mysql_free_result($conjunt_resultant);

	$adr=substr($PHP_SELF, 0, strrpos($PHP_SELF, "/"))."/comunicacio.php";
	$etg=substr($PHP_SELF, 0, strrpos($PHP_SELF, "/"))."/entorngrups.php";
	$entorngrups=false;
	if(comprovaNoVistUsuari()) $entorngrups=true;
?>
<body marginwidth="0" marginheight="0" style="margin: 0; font: 10pt/12pt sans-serif; background-color: #FFFFFF">
<div id="barramenu">
&nbsp;
<?php 
print("
	<font color='#ffffff'>Curs: $cursacademic</font> ".
	(($entorngrups)?"<a id='banderolaetglink' href='$etg?idsess=$idsess' target='cos'>
	<img id='banderolaetg' border='0' title='Tens novetats en lï¿½entorn de grups de treball' src='./imatges/banderolag1.gif'>
	</a>":"<a id='banderolaetglink' href='javascript:void(0)' target='cos'>
	<img id='banderolaetg' title='' src='./imatges/pixelblank.gif' border='0'>
	</a>").
	"&nbsp;".
	(($tensmissatges==true)?"<a id='banderolalink' href='$adr?idsess=$idsess' target='cos'>
	<img id='banderola' title='Tens nous missatges a comunicaciï¿½' src='./imatges/banderola1.gif' border='0'>
	</a>":"<a id='banderolalink' href='javascript:void(0)' target='cos'>
	<img id='banderola' src='./imatges/pixelblank.gif' border='0'></a>").
	"&nbsp; 
	<font color='#ffffff' title=\"".rawurldecode($sess_privilegis)."\">
		<b>$sess_user - $sess_nomreal</b>&nbsp; &nbsp;
	</font>
");
?>
</div>
<menu>
	<span id="arxiu" onClick="ocultaMostraCol('colArxiu');" onMouseOver="passarhi('colArxiu'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Arxiu</span>
	<span id="tutoria" onClick="ocultaMostraCol('colTutoria');" onMouseOver="passarhi('colTutoria'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Tutoria</span>
	<span id="incidencies" onClick="ocultaMostraCol('colIncidencies');" onMouseOver="passarhi('colIncidencies'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Incid&egrave;ncies</span>
	<span id="avaluacio" onClick="ocultaMostraCol('colAvaluacio');" onMouseOver="passarhi('colAvaluacio'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Avaluaci&oacute;</span>
	<span id="opcions" onClick="ocultaMostraCol('colOpcions');" onMouseOver="passarhi('colOpcions'); this.style.backgroundColor='#aabbcc'; <?=(privilegis('-', '-','-')?"ocultaMostraSub('subConfiguracio','o');":"")?>" onMouseOut="this.style.backgroundColor='#336699';">Opcions</span>
	<span id="ajuda" onClick="ocultaMostraCol('colAjuda');" onMouseOver="passarhi('colAjuda'); this.style.backgroundColor='#aabbcc'; <?=(privilegis('-', '-','-')?"ocultaMostraSub('subConfiguracio','o');":"")?>" onMouseOut="this.style.backgroundColor='#336699';">Ajuda</span>
	
	<div id="colArxiuOmbra"></div>
	<div id="colArxiu">
	  <div class="elementActiu" id="elem12" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="index2.php?nologo=&tancarsess=&idsess=<?=$idsess?>">&nbsp; Tancar sessi&oacute;</a></div>
	  <div class="elementActiu" id="elem13" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="javascript:window.close()">&nbsp; Sortir</a></div>
	</div>
	
	<div id="colTutoriaOmbra"></div>
	<div id="colTutoria">
	  <?php
	  $permis=privilegis('-', '-','Tutor');
	  if($permis) {
	  	  print("<div class='elementActiu' id='elem21' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='regtut.php?idsess=$idsess' target='cos'>&nbsp; Registres Tutoria</a></div>");
	  	  print("<div class='elementActiu' id='elem23' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='informelliure.php?idsess=$idsess' target='cos'>&nbsp; Informe Lliure</a></div>");
	  }
	  else {
	  	  print("<div class='elementNoActiu' id='elem21'>&nbsp; Registres Tutoria</div>");
	  	  print("<div class='elementNoActiu' id='elem23'>&nbsp; Informe Lliure</div>");
	  }
	  ?>
	  <div class="elementActiu" id="elem23" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="comunicacio.php?idsess=<?=$idsess?>" target="cos">&nbsp; Comunicaci&oacute;</a></div>
	  <div class="elementActiu" id="elem23" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="entorngrups.php?idsess=<?=$idsess?>" target="cos">&nbsp; Entorn grups</a></div>
	  <div class="elementActiu" id="elem23" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="calendari.php?idsess=<?=$idsess?>" target="cos">&nbsp; Calendari - Agenda</a></div>
	  <div class="elementActiu" id="elem23" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="llistesalum.php?idsess=<?=$idsess?>" target="cos">&nbsp; Llistes alumnes</a></div>
	  <div class="elementActiu" id="elem22" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="fotosalum.php?idsess=<?=$idsess?>" target="cos">&nbsp; Fotos alumnes</a></div>
	</div>
	
	<div id="colIncidenciesOmbra"></div>
	<div id="colIncidencies">
	  <div class="elementActiu" id="elem31" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="introd.php?idsess=<?=$idsess?>" target="cos">&nbsp; Registre d' incid&egrave;ncies</a></div>
	  <?php
	  $permis=privilegis('-', '-','Tutor');
	  if($permis) print("<div class='elementActiu' id='elem32' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='introdj.php?idsess=$idsess' target='cos'>&nbsp; Justificar incid&egrave;ncies</a></div>");
	  else print("<div class='elementNoActiu' id='elem32'>&nbsp; Justificar incid&egrave;ncies</div>");
	  ?>
	  <div class="elementActiu" id="elem33" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="cons_alum.php?idsess=<?=$idsess?>" target="cos">&nbsp; Incid&egrave;ncies per alumne</a></div>
	  <div class="elementActiu" id="elem34" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="cons_grup.php?idsess=<?=$idsess?>" target="cos">&nbsp; Incid&egrave;ncies per grup</a></div>
	  <div class="elementActiu" id="elem35" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="informesincid.php?idsess=<?=$idsess?>" target="cos">&nbsp; Informes d'incid&egrave;ncia</a></div>
	  <?php
	  $permis=privilegis('-', '-','Tutor');
	  if($permis) print("<div class='elementActiu' id='elem36' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='informe2.php?idsess=$idsess' target='cos'>&nbsp; Informe peri&ograve;dic</a></div>");
	  else print("<div class='elementNoActiu' id='elem36'>&nbsp; Informe peri&ograve;dic</div>");
//	  $permis=privilegis('-', '-','-');
	  $permis=privilegis('-', '-','Tutor');
	  if($permis) print("<div class='elementActiu' id='elem37' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='apercebiments_nivell.php?idsess=$idsess' target='cos'>&nbsp; Apercebiments</a></div>");
	  else  print("<div class='elementNoActiu' id='elem37'>&nbsp; Apercebiments</div>");
// bingen:
	  $permis=privilegis('-', '-','Tutor');
	  if($permis) print("<div class='elementActiu' id='elem38' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='informe_massives.php?idsess=$idsess' target='cos'>&nbsp; Informe massives</a></div>");
	  else print("<div class='elementNoActiu' id='elem38'>&nbsp; Informe massives</div>");
	  ?>
	  <div class="elementActiu" id="elem39" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="guardia.php?idsess=<?=$idsess?>" target="cos">&nbsp; Butlleta de guàrdia</a></div>
	</div>
	
	<div id="colAvaluacioOmbra"></div>
	<div id="colAvaluacio">
	  <div class="elementActiu" id="elem41" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="posarnotes.php?idsess=<?=$idsess?>" target="cos">&nbsp; Posar notes</a></div>
	  <?php
	  $permis=privilegis('-', '-','Tutor');
	  if($permis) print("
	  	  <div class='elementActiu' id='elem42' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='butlletinsals.php?idsess=$idsess' target='cos'>&nbsp; Butlletins alumnes</a></div>
	  	  <div class='elementActiu' id='elem43' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='resumaval.php?idsess=$idsess' target='cos'>&nbsp; Resum avaluaci&oacute;</a></div>
	  	  ");
	  else {
	  	print("
	  	  <div class='elementNoActiu' id='elem42'>&nbsp; Butlletins alumnes</div>
	  	  <div class='elementNoActiu' id='elem43'>&nbsp; Resum avaluaci&oacute;</div>
	  	  ");	
	  }
	  $permis=privilegis('-', '-','-');
	  if($permis) print("
	  	  <div class='elementActiu' id='elem44' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='dassigns.php?idsess=$idsess' target='cos'>&nbsp; Definir Assignatures</a></div>
	  	  <div class='elementActiu' id='elem45' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='davals.php?idsess=$idsess' target='cos'>&nbsp; Definir Avaluacions</a></div>
	  	  ");
	  else {
	  	print("
	  	  <div class='elementNoActiu' id='elem44'>&nbsp; Definir Assignatures</div>
	  	  <div class='elementNoActiu' id='elem45'>&nbsp; Definir Avaluacions</div>	
	  	");
	  }
	  ?>
	</div>
	
	<div id="colOpcionsOmbra"></div>
	<div id="colOpcions">
	  <?php
	  $permis=privilegis('-', '-','-');
	  if($permis) print("<div class='elementActiu' id='elem51' onMouseOver='this.style.backgroundColor=\"#336699\"; ocultaMostraSub(\"subConfiguracio\",\"v\");' onMouseOut='this.style.backgroundColor=\"transparent\";'>&nbsp; Configuraci&oacute;&nbsp; &nbsp; &nbsp; &nbsp; &diams;</div>");
	  else print("<div class='elementNoActiu' id='elem51'>&nbsp; Configuraci&oacute;</div>");
	  ?>
	  <div class="elementActiu" id="elem52" onMouseOver="this.style.backgroundColor='#336699'; <?=(privilegis('-', '-','-')?"ocultaMostraSub('subConfiguracio','o');":"")?>" onMouseOut="this.style.backgroundColor='transparent';"><a href="elmeucompte.php?idsess=<?=$idsess?>" target="cos">&nbsp; El meu compte</a></div>
	</div>
	
	<div id="colAjudaOmbra"></div>
	<div id="colAjuda">
	  <div class="elementActiu" id="elem61" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="manual.php?idsess=<?=$idsess?>" target="cos">&nbsp; Manual</a></div>
	  <div class="elementActiu" id="elem62" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="credits.php?idsess=<?=$idsess?>" target="cos">&nbsp; Cr&egrave;dits</a></div>
	</div>

<?php
$permis=privilegis('-', '-','-');
if($permis) print("	
	<div id='subConfiguracioOmbra'></div>
	<div id='subConfiguracio'>
	  <div class='elementActiu' id='elem511' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='parametres.php?idsess=$idsess' target='cos'>&nbsp; Par&agrave;metres</a></div>
	  <div class='elementActiu' id='elem512' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='ialumnes.php?idsess=$idsess' target='cos'>&nbsp; Inserir Alumnes</a></div>
	  <div class='elementActiu' id='elem520' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='imateries.php?idsess=$idsess' target='cos'>&nbsp; Inserir Matèries</a></div>
	  <div class='elementActiu' id='elem513' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='acpares.php?idsess=$idsess' target='cos'>&nbsp; Acc&eacute;s pares</a></div>
	  <div class='elementActiu' id='elem514' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='pfotos.php?idsess=$idsess' target='cos'>&nbsp; Posar fotos</a></div>
	  <div class='elementActiu' id='elem515' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='csubgrups.php?idsess=$idsess' target='cos'>&nbsp; Crear subgrups</a></div>
	  <div class='elementActiu' id='elem521' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='rsubgrups.php?idsess=$idsess' target='cos'>&nbsp; Relació subgrups</a></div>
	  <div class='elementActiu' id='elem522' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='dif_subgrups.php?idsess=$idsess' target='cos'>&nbsp; Diferència subgrups</a></div>
	  <div class='elementActiu' id='elem516' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='horariprofs.php?idsess=$idsess' target='cos'>&nbsp; Horaris-privilegis</a></div>
	  <div class='elementActiu' id='elem517' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='marcshoraris.php?idsess=$idsess' target='cos'>&nbsp; Horaris-marc</a></div>
	  <div class='elementActiu' id='elem518' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='frangeshoraries.php?idsess=$idsess' target='cos'>&nbsp; Franges hor&agrave;ries</a></div>
	  <div class='elementActiu' id='elem519' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='veurelogs.php?idsess=$idsess' target='cos'>&nbsp; Veure logs</a></div>
	  <div class='elementActiu' id='elem523' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='dif_guardia.php?idsess=$idsess' target='cos'>&nbsp; Difs. guàrdia</a></div>
	</div>
");
?>
</menu>

<?php
$dataavui=mktime(0,0,0,date('n'),date('j'),date('Y'),-1);
$consulta="SELECT id, data, text, horainici, horafi, periodicitat, link, avisador, colorfons, autor, lectors FROM $bdtutoria.$tbl_prefix"."calendari WHERE data>=$dataavui and avisador!='cap' and (autor='$sess_user' or (lectors like '%$sess_user|%' and lectorsclasf not like '%$sess_user/ocult%')) ORDER BY data asc, horainici";
$conjunt_resultant=mysql_query($consulta, $connect);
$recordatoris="";
while($fila=mysql_fetch_row($conjunt_resultant)) {
	$die=floor(($fila[1]-$dataavui)/(24*60*60));
	if($die<0) $die=0;
	if($die<=4) $recordatoris.=(($recordatoris!="")?"&nbsp; &nbsp;":"")."<a href='calendari.php?idsess=$idsess&vistames=' target='cos' title='Calendari Vista Mes'><font color='#dd3366'>#$die</font></a> ".(($fila[6]!="")?"<a href='$fila[6]' title='$fila[6]' target='_new'>":"")."-".((strlen($fila[2])>22)?substr($fila[2],0,22)."...":$fila[2]).(($fila[6]!="")?"</a>":"");
}

if($recordatoris!="") {
	print("<div align='right' onMouseOver='ocultaTotesColsSub();' style='position: absolute; left: 0px; top: 17px; width: 100%; height: 13px;
 clip:rect(0px 100% 13px 0px);background-color: #bbbbbb; 
 font-size: 9px'>&nbsp; $recordatoris &nbsp;</div>");	
}
?> 

<script language='JavaScript'>
var ns4=(document.layers)?true:false;
var ie=(document.all)?true:false;
var ns6=((document.getElementById)?true:false) && !ie;

var colOpen=0;

function ocultaMostraSub(idCol,opt) {
	ocultaMostraCapa(idCol,opt);
	ocultaMostraCapa(idCol+'Ombra',opt);	
}

function ocultaMostraCol(idCol) {
	var idElem, i, compt;
	for(i=0; i<elements.length; ++i) {
		idElem=elements[i].getAttribute('id');
		if(idElem.match(/^col.+/g)&& !idElem.match(/^col.+Ombra$/g)) {
			if(idElem==idCol) {
				ocultaMostraCapa(idElem,'t');
				ocultaMostraCapa(idElem+'Ombra','t');				
			}
			else {
				ocultaMostraCapa(idElem,'o');
				ocultaMostraCapa(idElem+'Ombra','o');
			}	
		}
	}
	colOpen=0;
	compt=0;
	for(i=0; i<elements.length; ++i) {
		idElem=elements[i].getAttribute('id');
		if(idElem.match(/^col.+/g)&& !idElem.match(/^col.+Ombra$/g)) {
			++compt;
			if(document.getElementById(idElem).style.visibility!="hidden") colOpen=compt;
		}	
	}
	if(colOpen!=0) if(document.getElementById('cos').contentWindow.mostraWindowedObjects) document.getElementById('cos').contentWindow.mostraWindowedObjects(false);	
}

function passarhi(idCol) {
	var i, idElem, compt;
	
	if (colOpen==0) return;
	compt=0;
	for(i=0; i<elements.length; ++i) {
		idElem=elements[i].getAttribute('id');
		if(idElem.match(/^col.+/g)&& !idElem.match(/^col.+Ombra$/g)) {
			++compt;
			if(idCol==idElem && colOpen==compt) return;
		}
	}
	ocultaMostraCol(idCol);	
}

function ocultaTotesColsSub() {
	if(!elements) return;
	for(i=0; i<elements.length; ++i) {
		idElem=elements[i].getAttribute('id');
		if(idElem.match(/^col.+/g)||idElem.match(/^sub.+/g)) ocultaMostraCapa(idElem,'o');		
	}
	if(document.getElementById('cos').contentWindow.mostraWindowedObjects && colOpen!=0) document.getElementById('cos').contentWindow.mostraWindowedObjects(true);	
	colOpen=0;
}

function ocultaMostraCapa(IdCapa,ocultaMostra) {
  if (ocultaMostra=='t') {
	  if(ns6||ie) eval('if(document.getElementById("'+IdCapa+'").style.visibility != "hidden") ocultaMostra="o"; else ocultaMostra="v";');
  }
  if (ocultaMostra=='v' && IdCapa=='menuContextual') rel=true;
  else rel=false;
  if (ns4)  eval( 'document.layers["' + IdCapa + '"].visibility = ' + ((ocultaMostra=="v")?'"visible"':'"hidden"') );
  if (ns6||ie)  eval( 'document.getElementById("' + IdCapa + '").style.visibility = ' + ((ocultaMostra=="v")?'"visible"':'"hidden"') );
  if (ie)   eval( 'document.all.' + IdCapa +'.style.visibility=' + ((ocultaMostra=="v")?'"visible"':'"hidden"') );
}

document.write("<iframe style='position: absolute; top:<?=(($recordatoris!="")?"30":"17")?>px; border-width:0px; border-style:none' src='<?if(ereg("Administrador", $sess_privilegis)) print("llic.php?idsess=$idsess"); else print("buit.php?idsess=$idsess");?>' id='cos' name='cos' height='100%' width='100%'>Aquest navegador no soporta frames!</iframe>");
function redimensiona() {
  if (ie) {
    var ampleBody = document.body.clientWidth;
    var altBody = document.body.clientHeight;
  }
  if (ns4 || ns6) {
    var ampleBody = window.innerWidth;
    var altBody = window.innerHeight;
  }
  if(ie) document.all.cos.style.height=altBody-<?=(($recordatoris!="")?"30":"17")?>; 
  if(ns6) document.getElementById('cos').style.height=(altBody-<?=(($recordatoris!="")?"30":"17")?>);
}

redimensiona();
window.onresize=redimensiona;

document.getElementById("cos").onmouseover=ocultaTotesColsSub;
var menu=document.getElementsByTagName('menu');
var elements=menu[0].getElementsByTagName('div');
for(var i=0; i<elements.length; ++i) {
	elements[i].onclick=ocultaTotesColsSub;
}
</script>
</body>
</html>
