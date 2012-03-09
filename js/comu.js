
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

var ns4=(document.layers)?true:false;
var ie=(document.all)?true:false;
var ns6=((document.getElementById)?true:false) && !ie;

  
var barraCalendari= '<a href="#" onMouseDown="mouMenuFlotDreta(); return false;" onClick="return false;"><img src="imatges/capa2_1.jpg" border="0"></a><a href="#" onClick="minMaxMenuFlotDreta(); return false;"><img src="imatges/capa2_2.jpg" border="0" id="imgMinMax" name="imgMinMax"></a><a href="#" onClick="tancaCapaFlotDreta(); return false;"><img src="imatges/capa2_3.jpg" border="0"></a><br>';
document.write("<div id='menuContextual' style='position:absolute; margin-top:95; margin-left:10; border-width:2; border-style:ridge; border-color:#42A5A5; background-color:#FFFFCC; visibility:hidden'></div>");

function tancaCapaFlotDreta() {
	  rel=false;
      ocultaMostraCapa('menuContextual','o');
      mostraWindowedObjects(true);
}

var minMax = true;
function minMaxMenuFlotDreta()
{
 if (minMax == true) {
   escriuACapa('menuContextual', barraCalendari); rel=false;
   if (ie) imgMinMax.src = "imatges/capa2_4.jpg";
   if (ns6) document.getElementById("imgMinMax").src = "imatges/capa2_4.jpg";
   if (ns4) document.menuContextual.document["imgMinMax"].src = "imatges/capa2_4.jpg";
   minMax = false;
   mostraWindowedObjects(true);
 }
 else {
   escriuACapa("menuContextual", barraCalendari+calendari(0,0,0)); rel=true;
   if (ie) imgMinMax.src = "imatges/capa2_2.jpg";
   if (ns6) document.getElementById("imgMinMax").src = "imatges/capa2_2.jpg";
   if (ns4) document.menuContextual.document.images["imgMinMax"].src = "imatges/capa2_2.jpg";
   minMax = true;
   mostraWindowedObjects(false);
 }
}

var despX, despY;
var capEvent = true;

function mouMenuFlotDreta(ev)
{
 if (ie) ev = window.event;
 if(capEvent) {
 	if(!document.addEventListener) {
 		document.onmouseup = mouMenuFlotDreta;
 		document.onmousemove = mouMenuFlotDreta;
	}
	else {
		addEvent(document, 'mouseup', mouMenuFlotDreta);
		addEvent(document, 'mousemove', mouMenuFlotDreta);
	}
 if(ev != null) {
   if (ns6) {
     despX = document.getElementById("menuContextual").offsetLeft - ev.pageX;
     despY = document.getElementById("menuContextual").offsetTop - ev.pageY;
   }
   if (ns4) {
     despX = document.layers["menuContextual"].left - ev.pageX;
     despY = document.layers["menuContextual"].top - ev.pageY;
   }
   if (ie) {
     despX = menuContextual.offsetLeft - ev.clientX;
     despY = menuContextual.offsetTop - ev.clientY;
   }
   capEvent = false;
 }
 }
 else {
   if(ev != null) {
     if (ev.type == 'mousemove') {
       if (ns6) {
         document.getElementById("menuContextual").style.left = ev.pageX + despX -10;
         document.getElementById("menuContextual").style.top = ev.pageY + despY -95;
       }
       if (ns4) {
         document.layers["menuContextual"].top = ev.pageY +despY -95;
         document.layers["menuContextual"].left = ev.pageX + despX -10;
       }
       if (ie) {
         document.all.menuContextual.style.left= ev.clientX + despX -10;
         document.all.menuContextual.style.top = ev.clientY + despY -95;
       }
     }
     if (ev.type == 'mouseup') {
       if(!document.removeEventListener) {
	       document.onmousemove = null;
	       document.onmouseup = null;
       }
       else {
       		rmEvent(document,'mousemove', mouMenuFlotDreta);
       		rmEvent(document,'mouseup', mouMenuFlotDreta);
   	   }
       capEvent = true;
     }
   }
 }
}



function escriuACapa(pNomCapa, text) {
  if (ie) eval( 'document.all.' + pNomCapa + '.innerHTML= text' );
  if (ns4) {
    eval( 'document.' + pNomCapa + '.document.write(text)' );
    eval( 'document.' + pNomCapa + '.document.close()' );
  }
  if (ns6) eval( 'document.getElementById("' + pNomCapa + '").innerHTML=text' );
}

function ocultaMostraCapa(IdCapa,ocultaMostra) {
  
  if (ocultaMostra=='t') {
	  if(ns6||ie) eval('if(document.getElementById("'+IdCapa+'").style.visibility != "hidden") ocultaMostra="o"; else ocultaMostra="v";');
  }
  if (ns4)  eval( 'document.layers["' + IdCapa + '"].visibility = ' + ((ocultaMostra=="v")?'"visible"':'"hidden"') );
  if (ns6||ie)  eval( 'document.getElementById("' + IdCapa + '").style.visibility = ' + ((ocultaMostra=="v")?'"visible"':'"hidden"') );
  if (ie)   eval( 'document.all.' + IdCapa +'.style.visibility=' + ((ocultaMostra=="v")?'"visible"':'"hidden"') );
}

function ocultaMostraCapa2(IdCapa,display) {
  
  if (ns4)  eval( 'document.layers["' + IdCapa + '"].display = "' + display + '"' );
  if (ns6||ie)  eval( 'document.getElementById("' + IdCapa + '").style.display = "' + display + '"' );
  if (ie)   eval( 'document.all.' + IdCapa +'.style.display="' + display + '"' );
}


var rel=false;
function horaAra()
{
 var ara=new Date(actual);
 var hora = (ara.getHours()<10) ? "0"+ara.getHours() : ara.getHours();
 var minuts = (ara.getMinutes()<10)?"0"+ara.getMinutes():ara.getMinutes();
 var segons = (ara.getSeconds()<10)?"0"+ara.getSeconds():ara.getSeconds();
 if (rel) document.forms.rellotge.rellotg.value = hora + ":" + minuts + ":" + segons;
 actual += 1000;
 setTimeout("horaAra()",1000);
}

var posCal=true;
function obreCalendari(pAny, pMes, camp) {
 if (ie && posCal==true) {
	document.all.menuContextual.style.left=window.event.clientX-10-260 + document.body.scrollLeft;;
	document.all.menuContextual.style.top=window.event.clientY-95+10 + document.body.scrollTop;
 } 
 if (ns4 && posCal==true) {
      document.layers["menuContextual"].top = ev.y -95+10;
      document.layers["menuContextual"].left = ev.x -10-260;
 } 
 if (ns6 && posCal==true) {
    document.getElementById("menuContextual").style.top = (((ev.pageY-95+10)<0)?10:ev.pageY-95+10);
    document.getElementById("menuContextual").style.left = (((ev.pageX-10-260)<0)?10:ev.pageX-10-260);
 }

 escriuACapa("menuContextual", barraCalendari+calendari(pAny,pMes, camp));
 rel=true;
 ocultaMostraCapa("menuContextual","v");
 mostraWindowedObjects(false);
}

function calendari(pAny, pMes, camp)
{
 
 var nomDiaSem = new Array("Dl", "Dm", "Dc", "Dj", "Dv", "Ds", "Dg");
 var nomMes = new Array("Gener", "Febrer", "Mar&ccedil;", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
 var diesMes = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
 var ampleCol = 30;
 
 var avui = new Date(actual);
 if (pAny!=0) var any=pAny;
 else var any=avui.getFullYear();
 if (pMes!=0) var mes=pMes-1;
 else var mes=avui.getMonth();
 var dia=avui.getDate();
 var dataCal = new Date(any, mes, dia);
 if ( ((any%4==0) && (any%100 != 0)) || (any%400==0) ) diesMes[1] = 29;
 var numDies = diesMes[mes];
 var primerDia = dataCal; 
 primerDia.setDate(1);
 var diaSem1 = (primerDia.getDay()==0)?7:primerDia.getDay(); 
 var cal="<table border='1'>";
 if ((mes+1)==12) {
  var mesmes1=1;
  nouanymes=any+1;
 }
 else {
  var mesmes1=mes+2;
  nouanymes=any;
 }
 if ((mes-1)==-1) {
  var mesmenys1=12;
  nouanymenys=any-1;
 }
 else {
  var mesmenys1=mes-1+1;
  nouanymenys=any;
 }
 cal += "<tr><th colspan='7' bgcolor='#ffff00'><table border='0' width='100%'><tr align='center' valign='middle'><td width='10%'><a href='' onClick='posCal=false; obreCalendari("+(any-1)+","+(mes+1)+","+camp+"); posCal=true; return false;'><b><<</b></a></td><td width='10%'><a href='' onClick='posCal=false; obreCalendari("+nouanymenys+","+mesmenys1+","+camp+"); posCal=true; return false;'><b><</b></a></td><td width='60%'><b>" + nomMes[mes] + " " + any + "</b></td><td width='10%'><a href='' onClick='posCal=false; obreCalendari("+nouanymes+","+mesmes1+","+camp+"); posCal=true; return false;'><b>></b></a></td><td width='10%'><a href='' onClick='posCal=false; obreCalendari("+(any+1)+","+(mes+1)+","+camp+"); posCal=true; return false;'><b>>></b></a></td></tr></table></th></tr>";
 cal += "<tr>";
 for(var i=0; i<7; i++) cal += "<th width=' " + ampleCol + " ' bgcolor='#ffff00'>" + nomDiaSem[i] + "</th>";
 cal += "</tr>\n";
 var columna = 1;
 cal += "<tr>";
 for(i=1; i<diaSem1; i++) {
   cal += "<td bgcolor='#ffffc0'>&nbsp;</td>";
   ++ columna;
 }
 for(i=1; i<=numDies; i++) {
 cal += "<td bgcolor=";
 if (i==dia && avui.getMonth()==dataCal.getMonth() && avui.getFullYear()==dataCal.getFullYear()) cal += " '#ff8080' ";
 else cal += " '#ffffc0' ";
 cal += "><center>";
 var d=new Date(any,mes,i);
 var di = nomDiaSem[((d.getDay()==0)?7:d.getDay())-1];
 if (camp==0) cal += calendariEscriuDia(di, i, mes, any);
 else if (camp==1) cal += calendariEscriuDia1(di, i, mes, any);
 else cal += calendariEscriuDia_camp(di, i, mes, any, camp);
 
 cal += "</center></td>";
 if(++columna > 7) {
   cal += "</tr>\n";
   if (i < numDies) cal += "<tr>";
   columna = 1;
   }
 }
 if (columna!=1) {
  for(i=0; i <= 7-columna; ++i) cal += "<td bgcolor='#ffffc0'>&nbsp;</td>";
  cal += "</tr>\n";
 }
 cal += "<tr><td colspan='7' bgcolor='#ffffc0' align='center'>";
 if (camp==0) cal += calendariEscriuDia('Avui', 0, 0, 0);
 else if (camp==1) cal += calendariEscriuDia1('Avui', 0, 0, 0);
 else cal += calendariEscriuDia_camp('Avui', 0, 0, 0, camp);
 cal += "&nbsp; &nbsp; &nbsp; <form name='rellotge'><input type='text' name='rellotg' size='8' style='background:transparent; border:none'></form> &nbsp;";
 if (camp==0) cal += calendariEscriuDia('ICurs', 0, 0, 0);
 else if (camp==1) cal += calendariEscriuDia1('ICurs', 0, 0, 0);
 else cal += calendariEscriuDia_camp('ICurs', 0, 0, 0, camp);
 cal += "</td></tr>";
 cal += "</table>";

 return cal;
}

function calendariEscriuDia_camp(di, i, mes, any, camp) {
 var nomDiaSem = new Array("Dl", "Dm", "Dc", "Dj", "Dv", "Ds", "Dg");
 var cad;
 if(di=='Avui') {
//    var avui= Math.round((new Date()).getTime() / 1000);
   var avui= new Date();
   di=nomDiaSem[avui.getDay()];
   cad="<a href='' onClick='document."+camp+".value=\""+di+", "+avui.getDate()+"-"+(avui.getMonth()+1)+"-"+avui.getFullYear()+"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); return false;'>Avui</a>";
   return cad;
 }
 if(di=='ICurs') {
   iniciCurs= new Date();
//   iniciCurs= new Date(datatimestampIniciCurs); // TODO
   di=nomDiaSem[iniciCurs.getDay()];
   cad="<a href='' onClick='document."+camp+".value=\""+ di +", "+ iniciCurs.getDate() +"-"+ (iniciCurs.getMonth()+1) +"-"+ iniciCurs.getFullYear() +"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); return false;'>Inici Curs</a>";
   return cad;
 }
 cad="<a href='' onClick='document."+camp+".value=\"" +di+", " + i +"-"+ (mes+1) +"-"+ any +"\"; ocultaMostraCapa(\"menuContextual\",\"o\"); return false;'>" + i + "</a>";
 return cad;
}

var ev;
function bDret(e){
  ev=e;
  if (ie) if (event.button == 2) return false;
  if (ns4||ns6) if (e.which == 3) return false;
}
function cMenu(){
  if (ie) event.returnValue=false;
  return false;
}

document.onmousedown=bDret;
document.oncontextmenu=cMenu;   


function obreFoto(pFoto, pNom)
{
 var finestra;
 window.focus();
 opt = 'resizable=1,scrollbars=1,width=300,height=165,left=5,top=60';
 finestra=window.open('', 'finestra', opt);
 with (finestra.document) {
  writeln('<html>\n<head>\n<title>'+pNom+'</title>\n</head>');
  writeln('<body bgcolor="#c0c0c0">');
  writeln('<table><tr><td><img src=\''+pFoto+'\' width=\'93\' height=\'125\'></td><td>'+pNom+'</td></tr>');
  writeln('</body>\n</html>');
  close();
 }
 finestra.focus();
}

function mostraWindowedObjects(mostra) {
  if(!ie && !ns6) return;  
  if (ie) var tmpTags = this.document.all.tags("frame");
  else if(ns6) var tmpTags = this.document.getElementsByTagName("frame");
  if(tmpTags.length>0) for (var k=0; k<tmpTags.length; k++) if(tmpTags[k].contentWindow.mostraWindowedObjects) tmpTags[k].contentWindow.mostraWindowedObjects(mostra);
  if(ie) var windowedObjectTags = new Array("select", "iframe", "object", "applet","embed");
  if(ns6) var windowedObjectTags = new Array("iframe", "object", "applet","embed");
  var windowedObjects = new Array();
  var j=0;
  for (var i=0; i<windowedObjectTags.length; i++) {
     if (ie) var tmpTags = this.document.all.tags(windowedObjectTags[i]);
     else if(ns6) var tmpTags = this.document.getElementsByTagName(windowedObjectTags[i]);
     if (tmpTags.length > 0) for(var k=0; k<tmpTags.length; k++) windowedObjects[j++] = tmpTags[k];
  }
  for (var i=0; i<windowedObjects.length; i++) {
     windowedObjects[i].visBackup = "";
     if (!mostra) windowedObjects[i].visBackup = ((windowedObjects[i].style.visibility ==null) ? "visible" : windowedObjects[i].style.visibility);
     windowedObjects[i].style.visibility =((mostra)?windowedObjects[i].visBackup : "hidden");
  }
}


function passwdAleat() {
  var cadena = '';
  cadena += String.fromCharCode( 97 + Math.round(26*Math.random()-0.5) );
  cadena += String.fromCharCode( 97 + Math.round(26*Math.random()-0.5) );
  cadena += Math.round(10*Math.random()-0.5);
  cadena += Math.round(10*Math.random()-0.5);
  cadena += Math.round(10*Math.random()-0.5);
  cadena += Math.round(10*Math.random()-0.5);
  return cadena;
}


function addEvent(obj, tipusEvent, nomFuncio) {
	if (obj.addEventListener){ 
		obj.addEventListener(tipusEvent, nomFuncio, false);
		return true;
	} else if (obj.attachEvent){  
		var r = obj.attachEvent("on"+tipusEvent, nomFuncio);
		return r;
	} else {
		return false;  
	}
}
function rmEvent(obj, tipusEvent, nomFuncio) {
	if (obj.removeEventListener) {
		obj.removeEventListener(tipusEvent, nomFuncio, false );
		return true;
	}
	else if (obj.detachEvent) {
		var r=obj.detachEvent("on"+tipusEvent, nomFuncio);
		return r;
	}
	else return false;	
}

 
 
