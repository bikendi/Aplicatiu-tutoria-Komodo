<?
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
<?
@include("linkbd.inc.php");
@include("comu.php");

$priv=split("_", $sess_privilegis);
$permisos=$priv[2];
?>
<title>Tutoria - <?print("$nomcentre - $poblaciocentre");?></title>
<style type="text/css">
#barraMenu 
{position:absolute; top:0px; left:0px; width:100%; height:17px; background-color: #336699; text-align:right; cursor:default; font-family:serif; font-weight:normal; font-style:normal; font-size:11}
A {text-decoration: none; color: black}

#arxiu, #tutoria, #incidencies, #avaluacio, #opcions, #ajuda
{position:absolute; background-color: #336699; cursor:default; font-family:serif; font-weight:normal; font-style:normal; font-size:14; color:#ffffff; text-align:center; vertical-align:middle}

#colArxiu, #colTutoria, #colIncidencies, #colAvaluacio, #colOpcions, #colAjuda
{position:absolute; background-color: #aabbcc; cursor:pointer; visibility:hidden; font-family:serif; font-weight:normal; font-style:normal; font-size:14; color:#000000; text-align:left; vertical-align:middle; z-index:2}
#colArxiuOmbra, #colTutoriaOmbra, #colIncidenciesOmbra, #colAvaluacioOmbra, #colOpcionsOmbra, #colAjudaOmbra
{position:absolute; background-color: #000000; cursor:auto; visibility:hidden; z-index:1}

#arxiu
{top:0px; height:17px; left:2px; width:40px}
#tutoria
{top:0px; height:17px; left:50px; width:54px}
#incidencies
{top:0px; height:17px; left:110px; width:82px}
#avaluacio
{top:0px; height:17px; left:196px; width:72px}
#opcions
{top:0px; height:17px; left:270px; width:70px}
#ajuda
{top:0px; height:17px; left:350px; width:50px}


#colArxiu       
{top:17px; left:0px; width:120px; height:42px}
#colArxiuOmbra       
{top:23px; left:6px; width:120px; height:42px}

#colTutoria     
{top:17px; left:50px; width:155px; height:63px}
#colTutoriaOmbra     
{top:23px; left:56px; width:155px; height:63px}

#colIncidencies 
{top:17px; left:110px; width:185px; height:21px}
#colIncidenciesOmbra 
{top:23px; left:116px; width:185px; height:21px}

#colAvaluacio 
{top:17px; left:196px; width:210px; height:21px}
#colAvaluacioOmbra 
{top:23px; left:202px; width:210px; height:21px}

#colOpcions 
{top:17px; left:270px; width:130px; height:21px}
#colOpcionsOmbra 
{top:23px; left:276px; width:130px; height:21px}

#colAjuda 
{top:17px; left:350px; width:110px; height:42px}
#colAjudaOmbra 
{top:23px; left:356px; width:110px; height:42px}

.elementActiu
{background-color: transparent; padding: 2px 2px 2px 2px; border-style:solid; border-color:white; border-width: 0px 1px 1px 1px}
.elementNoActiu
{background-color: #c0c0c0; padding: 2px 2px 2px 2px; border-style:solid; border-color:white; border-width: 0px 1px 1px 1px; color:#000000; cursor:default}

</style>

</head>

<?
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
&nbsp;<?print("<font color='#ffffff'>Curs: $cursacademic</font> ".(($entorngrups)?"<a id='banderolaetglink' href='$etg?idsess=$idsess' target='cos'><img id='banderolaetg' border='0' title='Tens novetats en l´entorn de grups de treball' src='./imatges/banderolag1.gif'></a>":"<a id='banderolaetglink' href='javascript:void(0)' target='cos'><img id='banderolaetg' title='' src='./imatges/pixelblank.gif' border='0'></a>")."&nbsp;".(($tensmissatges==true)?"<a id='banderolalink' href='$adr?idsess=$idsess' target='cos'><img id='banderola' title='Tens nous missatges' src='./imatges/banderola1.gif' border='0'></a>":"<a id='banderolalink' href='javascript:void(0)' target='cos'><img id='banderola' src='./imatges/pixelblank.gif' border='0'></a>")."&nbsp; &nbsp; <font color='#ffffff' title=\"".rawurldecode($sess_privilegis)."\"><b>$sess_user - $sess_nomreal</b>&nbsp; &nbsp;</font>");?>
</div>

<menu>
	<span id="arxiu" onClick="ocultaMostraCol('colArxiu');" onMouseOver="passarhi('colArxiu'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Arxiu</span>
	<span id="tutoria" onClick="ocultaMostraCol('colTutoria');" onMouseOver="passarhi('colTutoria'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Tutoria</span>
	<span id="incidencies" onClick="ocultaMostraCol('colIncidencies');" onMouseOver="passarhi('colIncidencies'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Incid&egrave;ncies</span>
	<span id="avaluacio" onClick="ocultaMostraCol('colAvaluacio');" onMouseOver="passarhi('colAvaluacio'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Avaluaci&oacute;</span>
	<span id="opcions" onClick="ocultaMostraCol('colOpcions');" onMouseOver="passarhi('colOpcions'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Opcions</span>
	<span id="ajuda" onClick="ocultaMostraCol('colAjuda');" onMouseOver="passarhi('colAjuda'); this.style.backgroundColor='#aabbcc';" onMouseOut="this.style.backgroundColor='#336699';">Ajuda</span>
	
	<div id="colArxiuOmbra"></div>
	<div id="colArxiu">
	  <div class="elementActiu" id="elem12" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="index2.php?nologo=&tancarsess=&idsess=<?=$idsess?>">&nbsp; Tancar sessi&oacute;</a></div>
	  <div class="elementActiu" id="elem13" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="javascript:window.close()">&nbsp; Sortir</a></div>
	</div>
	
	<div id="colTutoriaOmbra"></div>
	<div id="colTutoria">
	  <?
	  if((($permisos>>3)&1==1)) {
		  print("<div class='elementActiu' id='elem21' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='comunicacio.php?idsess=$idsess' target='cos'>&nbsp; Comunicaci&oacute;</a></div>");
	  	  print("<div class='elementActiu' id='elem23' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='entorngrups.php?idsess=$idsess' target='cos'>&nbsp; Entorn grups</a></div>");
	  	  print("<div class='elementActiu' id='elem23' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='calendari.php?idsess=$idsess' target='cos'>&nbsp; Calendari - Agenda</a></div>");
	  }
	  else {
		  print("<div class='elementNoActiu' id='elem21'>&nbsp; Comunicaci&oacute;</div>");
		  print("<div class='elementNoActiu' id='elem23'>&nbsp; Entorn grups</div>");
		  print("<div class='elementNoActiu' id='elem23'>&nbsp; Calendari - Agenda</div>");  
	  }
	  ?>
	</div>
	
	<div id="colIncidenciesOmbra"></div>
	<div id="colIncidencies">
	  <?
	  if((($permisos>>0)&1==1)) print("<div class='elementActiu' id='elem31' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='cons_alumpares.php?idsess=$idsess' target='cos'>&nbsp; Registre d'Incid&egrave;ncies</a></div>");
	  else print("<div class='elementNoActiu' id='elem31'>&nbsp; Registre d'Incid&egrave;ncies</div>");
	  ?>
	</div>
	
	<div id="colAvaluacioOmbra"></div>
	<div id="colAvaluacio">
	  <?
	  if((($permisos>>2)&1==1)) print("<div class='elementActiu' id='elem42' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='cons_qualifpares.php?idsess=$idsess' target='cos'>&nbsp; Registre de Qualificacions</a></div>");
	  else print("<div class='elementNoActiu' id='elem42'>&nbsp; Registre de Qualificacions</div>");	
      ?>
	</div>
	
	<div id="colOpcionsOmbra"></div>
	<div id="colOpcions">
	  <?
	  if((($permisos>>1)&1==1)) print("<div class='elementActiu' id='elem41' onMouseOver='this.style.backgroundColor=\"#336699\";' onMouseOut='this.style.backgroundColor=\"transparent\";'><a href='elmeucompte.php?idsess=$idsess' target='cos'>&nbsp; El meu compte</a></div>");
	  else print("<div class='elementNoActiu' id='elem41'>&nbsp; El meu compte</div>");	
      ?>
	</div>
	
	<div id="colAjudaOmbra"></div>
	<div id="colAjuda">
	  <div class="elementActiu" id="elem61" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="manual.php?idsess=<?=$idsess?>" target="cos">&nbsp; Manual</a></div>
	  <div class="elementActiu" id="elem62" onMouseOver="this.style.backgroundColor='#336699';" onMouseOut="this.style.backgroundColor='transparent';"><a href="credits.php?idsess=<?=$idsess?>" target="cos">&nbsp; Cr&egrave;dits</a></div>
	</div>
</menu>

<?
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
 clip:rect(0px 100% 13px 0px); background-color: #bbbbbb; 
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

document.write("<iframe style='position: absolute; top:<?=(($recordatoris!="")?"30":"17")?>px; border-width:0px; border-style:none' src='buit.php?idsess=<?=$idsess?>' id='cos' name='cos' height='100%' width='100%'>Aquest navegador no soporta frames!</iframe>");
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
