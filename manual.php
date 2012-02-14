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
@include("comu.js.php");
?>

<html>
<head>
<title>Tutoria</title>
<style type="text/css">
<!--
.est1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18pt;
	font-style: normal;
	font-weight: 600;
	font-variant: small-caps;
	color: #990033;
}
.est2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18pt;
	font-style: normal;
	font-weight: 600;
	font-variant: small-caps;
	color: #000000;
}
.est3 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12pt;
	font-style: normal;
	font-weight: 600;
	font-variant: small-caps;
	color: #000000;
}
-->
</style>
</head>
<body  bgcolor="#ccdd88" background="./imatges/fons.gif" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?
if(ereg("Pare_", $sess_privilegis)) $esPare=true; else $esPare=false;
if(ereg("Administrador", $sess_privilegis)) $esAdministrador=true; else $esAdministrador=false;
if(!$esPare && !$esAdministrador) $esProfessoroTutor=true; else $esProfessoroTutor=false;

if ($esAdministrador) print("
<table cellSpacing=4 cellPadding=2 width=614 border=0>
  <tr>
    <td colspan='2' align='center'>$cop<br>Manual d'usuari-administrador</td>
  </tr>
  <tr>
    <td width=82 valign='top' class=\"est2\">INDEX:</td>
    <td width=512 valign='top'>
    	1.- Funcionalitat bàsica.<br>
		2.- Instal·lació i configuració.<br>
		3.- Accés mitjançant PDA i configuració de SMS's.<br>
		4.- Usuaris. Creació i tipus.<br>
		5.- Càrrega d'alumnes.<br>
		6.- Menú Arxiu.<br>
		7.- Menú Tutoria.<br>
		8.- Menú Incidències.<br>
		9.- Men&uacute; Opcions.<br>
		10.- Men&uacute; Ajuda.<br>
    </td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">1.</div></td>
    <td valign='top' class=\"est2\">Funcionalitat bàsica.</td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>
    	<div align=\"justify\">L'Aplicatiu Tutoria esta disenyat per a ser una eina d'ajuda als Tutors en la realització de les seves funcions.
    	Per això inclou un conjunt d'utilitats que es poden classificar en tres blocs principals: Registres de tutoria per al tutor. Gesti&oacute; de les incid&egrave;ncies i Comunicaci&oacute; entre el colectiu d'usuaris del sistema.<br>
    	<br>
    	Per facilitar aquestes tasques als Tutors, es necessaria la participaci&oacute; de la resta de professors i els pares, per aix&ograve;, tamb&eacute; formen part de l'aplicatiu els usuaris professors que no son tutors i els usuaris pares.<br> 
    	</div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr> 
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">2.</div></td>
    <td valign='top' class=\"est2\">Instal&middot;laci&oacute; i configuraci&oacute;. </td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">&middot; Instal&middot;laci&oacute;.<br>
      ......................<br>
      <br>
&middot;Proc&eacute;s de configuraci&oacute; inicial.<br>
  Els passos per a la configuraci&oacute; inicial al comen&ccedil;ament del curs s&oacute;n els seg&uuml;ents:<br>
      <br>
      - Crear un usuari que sigui administrador (Opcions/Configuraci&oacute;/Horaris-privilegis). No fa falta definir el seu horari de moment, ja que aquest usuari servir&agrave; per poder crear la resta d'opcions inicials.<br>
      - Definir totes les franges hor&agrave;ries que s&oacute;n utilitzades en el centre (Opcions/Configuraci&oacute;/Franges hor&agrave;ries).<br>
      - Definir els par&agrave;metres de personalitzaci&oacute; del centre (Opcions/Configuraci&oacute;/Par&agrave;metres).<br>
      - Inserir els alumnes (Opcions/Configuraci&oacute;/Inserir Alumnes).<br>
      - Crear subgrups (Opcions/Configuraci&oacute;/Crear subgrups). Es creen els subgrups per poder-los assignar posteriorment en el horari-privilegis dels professors. No es obligatori, de moment, afegir alumnes als subgrups, si aix&ograve; no es fa ara, es podr&agrave; fer posteriorment.<br>
      - Crear els usuaris professors (Opcions/Configuraci&oacute;/Horaris-privilegis), indicant si s&oacute;n tutors, administradors i la distribuci&oacute; d'assignaci&oacute; de grups i subgrups en el seu horari setmanal per definir quins s&oacute;n els seus privilegis.<br>
      -Definir els Horaris marc per a cada grup classe (Opcions/Configuraci&oacute;/Horaris-marc).<br>
      -Introduir les fotos dels alumnes (Opcions/Configuraci&oacute;/Posar fotos).<br>
      -Assignar els alumnes corresponents als subgrups (Opcions/Configuraci&oacute;/Crear subgrups).<br>
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">3.</div></td>
    <td valign='top' class=\"est2\">Acc&eacute;s mitjan&ccedil;ant PDA i configuraci&oacute; de SMS's </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">&middot; Acc&eacute;s mitjan&ccedil;ant PDA.<br>
        Si el centre disposa d'una xarxa inalambrica Wi-Fi que permeti accedir al servidor que cont&eacute; l'aplicaci&oacute; Tutoria, i els professors disposen d'una PDA amb navegador web i acc&eacute;s Wi-Fi configurat per accedir a la xarxa Wi-Fi del centre, podran accedir a l'aplicaci&oacute; Tutoria mitjan&ccedil;ant l'adre&ccedil;a web: &quot;&lt;url acc&eacute;s a l'aplicaci&oacute;&gt;/index_pda.php&quot;, aquesta adre&ccedil;a web &eacute;s una p&agrave;gina html adaptada als navegadors PDA que els facilita la identificaci&oacute; de l'usuari professor i l'acc&eacute;s a la introducci&oacute; d' Incid&egrave;ncies. Aix&ograve; facilita al professor la tasca de passar llista i anotar incid&egrave;ncies al grup d'alumnes corresponent des de la mateixa aula en temps real, a la vegada, un cop s'ha passat llista, es pot veure des del mateix Aplicatiu Tutoria les incid&egrave;ncies que s'han introdu&iuml;t en aquella mateixa hora de classe. <br>
        <br>
&middot; Configuraci&oacute; de SMS's<br>
      Els usuaris autoritzats poden enviar missatges SMS tal com s'explica en l'apartat 7.3. Per&ograve;, per que aquests enviaments siguin efectius, es necessari que estigui configurat correctament els par&agrave;metres de configuraci&oacute; SMS a l'apartat &quot;Opcions/Configuraci&oacute;/Par&agrave;metres&quot; tal com s'explica en l'apartat 9.2.1. Aquests par&agrave;metres s'han d'obtenir de les dades que ens subministra el proveidor d'enviament de missatges SMS amb qui s'hagi contractat aquest servei. Si aquests par&agrave;metres no s'han configurat o b&eacute; s&oacute;n incorrectes, tots els enviaments SMS que es facin ser&agrave;n erronis. <br>
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">4.</div></td>
    <td valign='top' class=\"est2\">Usuaris. Creaci&oacute; i tipus. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En l'aplicatiu es distingeixen dos tipus d'usuaris: els Professors i els Pares dels alumnes. A la vegada, dins del tipus Professors, en troben tres subgrups m&eacute;s: Els professors Administradors, els professors Tutors i la resta de professors.<br>
        <br>
        La creaci&oacute; d'usuaris Pares &eacute;s fa de forma autom&agrave;tica quan es carreguen els alumnes. &Eacute;s a dir, si existeix un alumne tamb&eacute; existir&agrave; el corresponent usuari Pare associat a aquest alumne i se li assignar&agrave; per defecte, un identificador i contrasenya. Quan l'alumne desapareix de l'aplicatiu, tamb&eacute; s'elimina automaticament el compte d'usuari Pare que t&eacute; associat. Per saber els usuaris Pares que s'han creat en l'aplicatiu s'ha d'accedir a l'opci&oacute; de Men&uacute;: &quot;Opcions/Configuraci&oacute;/Acc&eacute;s Pares&quot; seleccionant el link &quot;Veure Identificadors&quot;. <br>
        <br>
        Els usuaris Professors s'han de crear de forma manual dins de l'apartat &quot;Opcions/Configuraci&oacute;/Horaris-privilegis&quot; seleccionant el link &quot;Nou&quot;. Conjuntament amb la creaci&oacute; de l'usuari Professor es defineix tamb&eacute;, si aquest ser&agrave; Administrador, i/o ser&agrave; Tutor i el seu grup, i les hores i grups sobre els quals tindr&agrave; drets a introduir dades d'Incid&egrave;ncies i/o modificar-les. <br>
        <br>
    Cada usuari, segons el tipus que sigui tindr&agrave; uns drets o &quot;privilegis&quot;, es a dir, possibilitat de consultar i/o modificar dades de alumnes i configuraci&oacute; de l'aplicatiu. Es possible saber quins &quot;privilegis&quot; t&eacute; assignats l'usuari que ha iniciat la sessi&oacute; posicionant el ratol&iacute; a sobre del seu nom i identificador que es troba escrit en la part superior dreta de la finestra. Els usuaris Pares s&oacute;n els que tenen m&eacute;s restriccions i els usuaris administradors els que no en tenen cap. </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">5.</div></td>
    <td valign='top' class=\"est2\">C&agrave;rrega d'alumnes. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">Els alumnes no es poden inserir de forma manual a l'aplicatiu, tampoc es pot modificar les seves dades des de l'Aplicatiu Tutoria. La inserci&oacute; d'alumnes s'ha de fer a partir de la exportaci&oacute; de dades d' alumnes des del programa de gesti&oacute; de centres segons s'indica en l'apartat 9.2.2.<br>
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">6.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Arxiu. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el menu Arxiu hi tenim dos opcions:<br>
        <br>
&middot; Tancar sessi&oacute;.<br>
Aquesta opci&oacute; tanca la sessi&oacute; actual i permet introdu&iuml;r una nova sessi&oacute; amb un altre identificador i contrasenya.<br>      
<br>
&middot; Sortir<br>
Aquesta opci&oacute; tanca l'aplicaci&oacute; Tutoria. <br>
    </div></td>
  </tr>
    <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">7.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Tutoria. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el men&uacute; Tutoria hi tenim les opcions corresponents a les tasques del tutor. Els subapartats s&oacute;n:<br>
        <br>
        <span class=\"est3\">&middot; 7.1.  Registres de Tutoria.</span><br>
        Nom&eacute;s accessibles pels Administradors i Tutors. Aqui disposem d'un espai on podem seleccionar el grup d'alumnes i per cada alumne hi trobem les seves dades personals (adre&ccedil;a, poblaci&oacute;, nom pares, etc.). Tamb&eacute; disposem de tres subapartats: &quot;Entrevistes&quot;, &quot;Fitxers&quot; i &quot;Informes d'incid&egrave;ncia&quot;.<br>
    L'apartat &quot;Entrevistes&quot; serveix per a registrar, a modus d'agenda, les entrevistes fetes amb els pares i els continguts d'aquestes, associada a cada alumne del grup.<br>
    L'apartat &quot;Fitxers&quot; serveix per desar-hi fitxers creats o que disposi el tutor i estiguin associats a un determinat alumne o b&eacute; a tot el grup-classe.<br>
    L'apartat &quot;Informes d'incid&egrave;ncia&quot; &eacute;s on es troba tots els Informes d'Incid&egrave;ncia corresponent a un determinat alumne que hagin estat creats pels professors des de l'apartat &quot;Incid&egrave;ncies/Informes d'Incid&egrave;ncia&quot;, aquest apartat &eacute;s un espai de comunicaci&oacute; on els professors poden crear els informes d'incid&egrave;ncia que considerin apropiats per a un determinat alumne i a la vegada el tutor el pot veure des de Registres de Tutoria/Informes d'Incid&egrave;ncia.<br>
    <br>
    Les Entrevistes, Fitxers i Informes d'incid&egrave;ncia poden ser visualitzats alumne per alumne, o de tots els alumnes del grup o b&eacute; els comuns a tot el grup des de la llista seleccionable &quot;Alumne&quot;.<br>
    <br>
    <span class=\"est3\">&middot; 7.2.  Informe lliure.</span><br>
    Aquest apartat &eacute;s accessible pels Administradors i Tutors. Serveix per a crear cartes circulars personalitzades dirigides a un alumne o grup d'alumnes. En la creaci&oacute; del text &eacute;s pot posar marques corresponents a noms i cognoms dels pares, de l'alumne, adre&ccedil;a, poblaci&oacute;, data, tel&egrave;fon, curs, grup, etapa, etc. Les dades s'agafen directament de la base de dades a mesura que es van creant les cartes circulars. A l'apartat &quot;Ajuda&quot;, dins de l'edici&oacute; d'un informe lliure es t&eacute; una llista dels diferents camps que es pot incloure dins l'informe lliure que es desitja realitzar.<br>
    <br>
    La selecci&oacute; d'alumnes es fa des de l'apartat &quot;Alumnes&quot;. Aqui ens apareix una finestra que t&eacute; dos columnes, a la columna de la dreta tenim la llista d'alumnes seleccionables, aquests es poden seleccionar clicant a sobre del nom. Un cop seleccionats, aquests apareixen a la columna de l'esquerra. Quan ja tenim la selecci&oacute; d'alumnes feta, cliquem sobre &quot;Tancar&quot;. Posteriorment, si cliquem sobre &quot;Imprimir&quot; ens apareixer&agrave; un document per imprimir, repetit tantes vegades com alumnes s'hagin seleccionat amb les dades personalitzades de cadasc&uacute;n d'ells.<br>
    <br>
    <span class=\"est3\">&middot; 7.3. Comunicaci&oacute;.</span><br>
    Aquest apartat serveix com a element de comunicaci&oacute; interna de l'Aplicatiu Tutoria. No es un gestor de correu electr&ograve;nic, per&ograve; t&eacute; un funcionament similar, encara que els &uacute;nics usuaris que es poden comunicar entre ells s&oacute;n els registrats en l'aplicaci&oacute;: Pares i Professors. Per tant, aquest apartat &eacute;s una missatgeria interna. <br>
    Tamb&eacute; es permet l'enviament de missatges SMS als usuaris que han introdu&iuml;t el n&uacute;mero de tel&egrave;fon m&ograve;bil dins l'apartat: &quot;Opcions/El meu compte&quot;.<br>
    <br>
    La missatgeria interna la poden utilitzar tant els professors com els pares, ara b&eacute;, segons el tipus d'usuari, solament es podran comunicar amb alguns altres tipus d'usuari.<br>
    Per exemple:<br>
    - 
    Els pares solament poden enviar missatges nous al tutor del seu fill i poden respondre a qualsevol missatge que l'hi hagi enviat qualsevol usuari. Els pares no poden enviar missatges SMSs a cap usuari.<br>
    <br>
    -Els professors que no son administradors ni tutors, poden enviar missatges interns o SMS's als altres professors.<br>
    <br>
    -Els tutors poden enviar missatges interns i SMS's als altres professors i pares del seu grup de tutoria.<br>
    <br>
    -Els administradors poden enviar missatges interns i SMS's a qualsevol professor i qualsevol pare.<br>
    <br>
    Per poder enviar missatges interns a un determinat pare, &eacute;s necessari que, pr&egrave;viament, se li hagi donat el seu identificador i contrasenya i s'hagin activat els permisos per accedir a l'Aplicatiu Tutoria des de l'apartat &quot;Opcions/Configuraci&oacute;/Acc&eacute;s Pares&quot; i &quot;Veure identificadors&quot;.<br>
    <br>
    Per poder enviar missatges SMS a un determinat usuari, pare o professor, es necessari que aquest usuari hagi indicat el seu n&uacute;mero de tel&egrave;fon m&ograve;bil a l'apartat &quot;Opcions/El meu compte&quot;.<br>
    <br>
    Un cop s'ha accedit dins l'apartat Comunicaci&oacute;, ens apareix per defecte la missatgeria interna, si desitgem elegir la missatgeria SMS ho farem clicant al recuadre de la part superior esquerra.<br>
    <br>
    Tots dos entorns (missatgeria interna i missatgeria SMS) s&oacute;n molt similars, amb la difer&egrave;ncia que en la missatgeria SMS nomes hi veurem missatges enviats per nosaltres i cap missatge rebut, ja que l'aplicatiu no esta configurat per a rebre missatges SMS des de l'exterior.<br>
    <br>
    En tots dos casos disposem d'una part superior i una part inferior, en la superior hi veurem la llista de missatges enviats i rebuts i clicant sobre l'Assumpte, ens mostrar&agrave; el seu contingut en la part inferior. Els missatges amb una icona d'un clip al seu costat vol dir que s&oacute;n missatges amb fitxers adjunts, els missatges amb una banderola vermella, vol dir que s&oacute;n nous i no han estat vistos, i els missatges amb una banderola verda vol dir que han estat marcats com a pendents de resposta, aquesta darrera opci&oacute; &eacute;s un recordatori per a l'usuari. <br>
    <br>
    En principi disposem de dos carpetes de missatges: General i Paperera. A la carpeta General &eacute;s on hi trobem els missatges enviats i rebuts per defecte i a la Paperera, aquells missatges que han estat esborrats. Podem crear tantes noves carpetes personalitzades com vulguem per classificar els missatges clicant a sobre de la icona carpeta que hi ha a la part superior de la finestra, la nova carpeta personalitzada ser&agrave; accessible des del desplegable &quot;Carpetes&quot;. Quan visualitzem un missatge a la part inferior, tindrem l'opci&oacute; de moure'l a la carpeta personalitzada que desitgem per classificar-lo.<br>
    <br>
    Per crear un nou missatge ho fem clicant a sobre de l'enlla&ccedil; superior &quot;Nou missatge&quot;. En la part inferior ens apareix els camps de formulari per introduir l'Assumpte i el Contingut. Tamb&eacute; podem clicar a sobre de &quot;Afegir adjunt&quot; per adjuntar-hi altres fitxers.<br>
    <br>
    La selecci&oacute; dels destinat&agrave;ris del missatge es fa clicant a sobre de &quot;Destinataris&quot;, seguidament ens apareix una nova finestra on a la columna de la dreta i tenim els possibles destinataris a elegir. Elegirem un destinatari clicant a sobre d'ell, aix&ograve; fa que aquest s'afegeixi a la columna de &quot;Seleccionats&quot; a la part esquerra de la finestra. Finalment, clicant a sobre de &quot;Actualitzar&quot; introduirem la llista de destinataris al missatge que estem creant.<br>
    <br>
    En aquest apartat tamb&eacute; podem crear grups de destinataris personalitzats, per exemple: el grup de professors tutors, o b&eacute; el grup de professors del departament de Llengua Catalana,  o b&eacute; el grup de pares d'una determinada activitat, etc. Per fer-ho, s'ha de seleccionar el conjunt de destinataris i clicar sobre l'opci&oacute; &quot;Crear grup&quot;, seguidament li donem un nom adequat i a partir d'ara ja disposarem d'aquest grup de destinataris personalitzat des del desplegable superior de la columna de la dreta.<br>
    <br>
    Cal recordar que segons quin tipus d'usuari es sigui, la llista de possibles destinataris de la columna de l'esquerra pot quedar molt redu&iuml;da, per exemple, si es un pare, solament podr&agrave; elegir com a destinatari el tutor corresponent, per tant, ja no disposar&agrave; de l'opci&oacute; de &quot;Crear grup&quot;. Tampoc es veuran tots el pares si aquests no tenen activat l'acc&eacute;s a l'Aplicatiu Tutoria.<br>
    <br>
    Finalment, un cop hem clicat sobre &quot;Actualitzar&quot;, els destinataris quedaran afegits al nou missatge i aquest es podr&agrave enviar clicant a sobre de la icona &quot;Enviar&quot; o &quot;Enviar SMS&quot;. En el cas de tractar-se d'un SMS, se'ns informa de si la transmissi&oacute; ha estat correcta o no i en cas de tractar-se d'un usuari Administrador, tamb&eacute; ens indica el saldo de cr&egrave;dits disponible des del prove&iuml;dor de SMS. <br>
    <br>
    El missatges que s'estiguin visualitzan (que no hagin estat escrits per nosaltres) es poden respondre al seu remitent clicant sobre la icona &quot;Respon&quot;, aquest cas es similar a la creaci&oacute; d'un nou missatge, per&ograve;, ja apareix automaticament el nom del destinatari, l'assumpte i el text original.<br>
    <br>
    Quan visualitzem el contingut d'un missatge, se'ns mostra la icona &quot;Pendent&quot;, &quot;Mou a carpeta&quot;, &quot;Esborra&quot;, &quot;Historial&quot; (solament en cas de ser nosaltres els remitents) i &quot;Respon&quot; (solament en el cas que nosaltres no siguem els remitents).<br>
    La icona &quot;Pendent&quot; serveix per a marcar un missatge com a pendent de resposta, posant una banderola verda al seu costat, es a dir, com a un recordatori, la propera vegada que visualitzem aquest missatge, la banderola verda es torna a desactivar.<br>
    La icona &quot;Mou a carpeta&quot; ens permet moure el missatge a altres carpetes personalitazades creades per nosaltres per classificar els missatges.<br>
    La icona &quot;Esborra&quot; mou el missatge a la Paperera. Des de la carpeta &quot;Paperera&quot; podem tornar a accedir al missatge per recuperar-lo o per esborrar-lo definitivament.<br>
    La icona &quot;Historial&quot; apareix si nosaltres som els remitents del missatge i ens mostra una finestra on ens diu quins destinataris han vist el missatge i la seva data i hora.<br>
    <br>
    <span class=\"est3\">&middot; 7.4. Llistes alumnes.</span><br>
    En aquest apartat es possible obtenir llistes d'alumnes dels diferents grups i subgrups. Els alumnes que formen part d'un grup ve determinat per la importaci&oacute; d'alumnes des del programa de gesti&oacute; acad&egrave;mica del centre, en canvi, els alumnes que formen part d'un subgrup es defineixen dins l'apartat &quot;Opcions/Configuraci&oacute;/Crear subgrups&quot;. Les llistes s&oacute;n en format pdf, preparades per a ser impreses, per&ograve;, es possible exportar el seu contingut a fulls de c&agrave;lcul o bases de dades si es fa una selecci&oacute; mitjan&ccedil;ant l'eina &quot;Seleccionar columna&quot; del visualitzador de pdf's i posteriorment es fa &quot;Copiar i Enganxar&quot;. <br>
    <br>
    <span class=\"est3\">&middot; 7.5. Fotos alumnes.</span><br>
    En aquest apartat podem veure les fotografies dels alumnes en format web i en format pdf per a imprimir. Les fotografies tenen que haver estat incloses previament des de l'apartat &quot;Opcions/Configuraci&oacute;/Posar fotos&quot;.<br> 
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">8.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Incid&egrave;ncies. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En aquest men&uacute; hi tenin els apartats corresponents a la gesti&oacute; d'incid&egrave;ncies dels alumnes del centre. Els seus apartats es poden classificar en tres tipus: Introducci&oacute; i registre de les incid&egrave;ncies (Registre d'incid&egrave;ncies, Justificar incid&egrave;ncies i Informes d'incid&egrave;ncia), resums individual i col&middot;lectiu de les incid&egrave;ncies registrades (Incid&egrave;ncies per alumne i Incid&egrave;ncies per grup) i extracci&oacute; de documents referents a les incid&egrave;ncies (Informe peri&ograve;dic i Apercebiments).<br>
        <br>
        <span class=\"est3\">&middot; 8.1. Registre d'incid&egrave;ncies.</span><br>
        Aquest apartat &eacute;s accessible per tots els professors i poden visualitzar les incid&egrave;ncies hor&agrave;ries que hi estan registrades pels diferents grups i alumnes. Si el professor t&eacute; assignat el grup o subgrup (dins l'apartat &quot;Opcions/Configuraci&oacute;/Horaris-privilegis&quot;) que esta visualizant, tamb&eacute; podr&agrave; introduir incid&egrave;ncies en aquelles hores del seu horari, es a dir, les que consten en els seus privilegis.<br>
        <br>
        Els tipus d'incid&egrave;ncia que es pot anotar s&oacute;n les seg&uuml;ents: F - Falta d'assist&egrave;ncia, R - Retard, E - Expulsi&oacute; i A - Anotaci&oacute;. Per cada incid&egrave;ncia anotada, clicant sobre la T es pot introduir un text curt explicatiu d'aquella incid&egrave;ncia. Si no s'ha introduit cap incid&egrave;ncia el text explicatiu no es desar&agrave;. La utilitzaci&oacute; del text explicatiu &eacute;s necess&agrave;ria per al cas d'introduir una anotaci&oacute; (A), ja que aquesta no defineix per si mateix el tipus d'incid&egrave;ncia i es necessari introduir el text explicatiu per definir-la. Pel cas de retards, aquest text explicatiu pot servir per indicar el temps de retard.<br>    
        <br>
        Es pot donar el cas que un professor que tingui privilegis adequats sobre una determinada hora i grup o subgrup no pugui introduir la incid&egrave;ncia en una determinada data per que el tutor del grup hagi bloquejat aquesta opci&oacute; per a tots els professors des de l'apartat &quot;Incid&egrave;ncies/Justificar incid&egrave;ncies&quot; &quot;Data bloqueig&quot;. En aquest cas es necessari avisar al tutor per que modifiqui la &quot;Data de bloqueig&quot;. Tamb&eacute; pot passar que no sigui possible introduir incid&egrave;ncies per a unes hores d'un grup pel fet que tingui l'horari marc mal configurat des de l'opci&oacute; &quot;Opcions/Configuraci&oacute;/Horaris-marc&quot;.<br>    
        <br>
        Si un professor t&eacute; assignada una &quot;Gu&agrave;rdia&quot; en el seu horari-privilegis per a una determinada hora de la setmana, aquest podr&agrave; introduir incid&egrave;ncies a qualsevol grup i subgrup del centre ja que es considera que al tenir &quot;Gu&agrave;rdia&quot; es t&eacute; assignat tots els grups del centre. Si no es desitja que els professors puguin introduir incid&egrave;ncies a qualsevol grup en les hores que tenen gu&agrave;rdia, solament fa falta treure'ls-hi aquest privilegi des de &quot;Opcions/Configuraci&oacute;/Horaris-privilegis&quot;.<br>
        <br>
        Els drets a introduir incid&egrave;ncies a un determinat alumne solament s&oacute;n efectius si es t&eacute; seleccionat el grup o subgrup corresponent que tingui assignat el professor en els seus privilegis. Es a dir, si un professor t&eacute; assignat en una determinada hora del seu Horari-privilegis, un subgrup que es un subconjunt d'alumnes d'un grup classe, no podr&agrave; introduir les incidencies als alumnes si els esta visualitzant com a grup, en canvi, si que ho podr&agrave; fer si els visualitza com al subgrup que consta en el seu Horari-privilegis.<br>
        <br>
        El professor Tutor tamb&eacute; podr&agrave; introduir incid&egrave;ncies a qualsevol hora pels alumnes de la seva tutoria, a part dels que ja t&eacute; indicats dins el seu horari de classe. El professor Administrador pot introduir incid&egrave;ncies a tothom sense restriccions.<br>
        <br>
        Qualsevol professor tamb&eacute; pot crear un Informe d'incid&egrave;ncia amb text explicatiu molt extens clicant a sobre de &quot;I&quot;, que es troba al costat de la foto de l'alumne. Aquest informe d'incid&egrave;ncia extens, quedar&agrave; desat dins l'apartat &quot;Incid&egrave;ncies/Informe d'incid&egrave;ncies&quot; pr&ograve;pi de l'usuari professor i tamb&eacute; ser&agrave; accessible pel tutor del grup dins l'apartat &quot;Tutoria/Registres de tutoria&quot; i com a tipus de registre: &quot;Informes d'incid&egrave;ncia&quot;.<br>
        <br>
        Mitjan&ccedil;ant una PDA, tal com s'explica a l'apartat 3 d'aquest manual, es pot accedir a una p&agrave;gina web adaptada als navegadors de la PDA, que tamb&eacute; permet introdu&iuml;r les incid&egrave;ncies de forma similar a com es descriu aqui, per&ograve; des de la mateixa aula on el professor fa classe i en temps real sense la necessitat de tenir disponible un ordinador.<br>
        <br>
        <span class=\"est3\">&middot; 8.2. Justificar Incid&egrave;ncies.</span><br>
        Aquest apartat solament &eacute;s accessible pels Administrador i els Tutors, en el cas dels tutors, solament podran visualitzar el grup o subgrup del qual siguin tutors.<br>
        <br>
        La seva funcionalitat es gaireb&eacute; id&egrave;ntica a la de l'apartat &quot;Registre d'Incid&egrave;ncies&quot;. Per&ograve;, en tractar-se d'un apartat d'&uacute;s exclusiu pels tutors, tamb&eacute; afegeix l'opci&oacute; de modificar les incid&egrave;ncies registrades i justificar els retards (RJ) i les faltes d'assist&egrave;ncia (FJ).<br>
        <br>
        Tamb&eacute; s'inclou en aquest apartat, l'opci&oacute; &quot;Data bloqueig&quot;. Aquesta serveix per que el tutor del grup indiqui una data fins la qual, els professors ja no podran introduir o modificar incid&egrave;ncies dels alumnes d'aquell grup, encara que en tinguin els privilegis adequats. Aquesta funcionalitat es per que, quan el tutor hagi fet les cartes dels informes peri&ograve;dics d'incid&egrave;ncia fins una determinada data (des de l'apartat &quot;Incid&egrave;ncies/Informe peri&ograve;dic&quot;) i les hagi enviat als pares, indicant dates i hores de cada incid&egrave;ncia, cap altre professor pugui modificar l'estat d'incid&egrave;ncies que s'ha comunicat als pares. Si un professor ha de modificar i/o afegir alguna incid&egrave;ncia en dates pr&egrave;vies a la data de bloqueig, seria necessari comunicar-ho al tutor corresponent.<br>
        <br>
        <span class=\"est3\">&middot; 8.3. Incid&egrave;ncies per alumne.</span><br>
        Aquest apartat de consulta &eacute;s accessible per tots els professors i ens permet mostrar totes les incid&egrave;ncies i els texts explicatius registrats per a un determinat alumne entre dos dates inicial i final.<br>
        <br>
        Tamb&eacute; ens permet fer un filtratge de hores, tipus d'incid&egrave;ncia i dia de la setmana per poder analitzar les incid&egrave;ncies registrades segons l'assignatura, o l'hora, etc.<br>
        <br>
        S'ha de tenir en compte que el contingut d'aquests apartat també es visible pels usuaris Pares que accedeixen a l'aplicatiu, per&ograve; solament pel que fa refer&egrave;ncia al seu fill.<br>
        <br>
        <span class=\"est3\">&middot; 8.4 Incid&egrave;ncies per grup.</span><br>
        Aquest altre apartat de consulta tamb&eacute; &eacute;s accessible per tots els professors i ens mostra per a cada grup o subgrup el total d'incid&egrave;ncies registrades de cada tipus registrades per cada alumne entre dos dates inicial i final.<br>
        <br>
        Tamb&eacute; ens permet fer un filtratge de les hores a comptar segons el dia de la setmana i l'hora de classe.<br>
        <br>
        <span class=\"est3\">&middot; 8.5. Informes d'incid&egrave;ncia.</span><br>
        Aquest apartat &eacute;s exclusiu per a cada professor i el seu contingut solament &eacute;s visible pel professor que ha iniciat la sessi&oacute; i pel tutor de l'alumne a qui correspongui l'informe d'incid&egrave;ncia corresponent.<br>
        <br>
        En ell s'hi troba la relaci&oacute; dels Informes d'incid&egrave;ncia creats pel professor (creats des d'aquest mateix apartat o des de l'apartat &quot;Incid&egrave;ncies/Registre d'Incid&egrave;ncies&quot; &quot;I&quot;) referents a un determinat alumne.<br>
        <br>
        El professor pot crear nous informes, modificar-los, esborrar-los o imprimir-los. Val a dir que un determinat informe, associat a un alumne, tamb&eacute; ser&agrave; visible al tutor de l'alumne des de l'apartat &quot;Tutoria/Registre d'Incid&egrave;ncies/Informes d'incid&egrave;ncia&quot;.<br>
        <br>
        <span class=\"est3\">&middot; 8.6. Informe peri&ograve;dic.</span><br> 
    Aquest apartat solament &eacute;s accessible pels Administradors i pels tutors en els seus grups de tutoria.<br>
    <br>
    De forma peri&ograve;dica, els tutors poden generar cartes personalitzades per a cada alumne on s'indica el nombre i tipus d'incid&egrave;ncia registrats entre dos dates determinades, les cartes ja surten preparades per ser enviades als pares dels alumnes.<br>
    <br>
    Si algun alumne no t&eacute; faltes d'assist&egrave;ncia ni retards en aquell peri&ograve;de de temps, no es genera la carta. <br>
    <br>
    <span class=\"est3\">&middot; 8.7. Apercebiments.</span><br>
    L'apartat Apercebiments solament &eacute;s accessible pels professors Administradors. En aquest apartat es poden generar els informes d'apercebiment per als alumnes que han realitzat un nombre elevat de faltes d'assist&egrave;ncia, retards o expulsions.<br>
    <br>
    Inicialment, es mostra per cada alumne el nombre de incid&egrave;ncies de cada tipus realitzat entre dues dates, i es permet fer un filtratge dels alumnes que han realitzat una quantitat superior a un nombre determinat de faltes, retards o expulsions.<br>
    <br>
    A la columna de la dreta ens informa del nombre d'apercebiments enviats pr&egrave;viament i tenim l'opci&oacute; de generar un impr&eacute;s on s'informa de l'apercebiment corresponent, si es decideix informar a la familia d'aquest apercebiment, es pot incrementar el nombre d'apercebiments enviats clicant a sobre de &quot;+1&quot;, si es decideix decrementar el nombre d'apercebiments es fa clicant a sobre de &quot;-1&quot;. <br> 
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">9.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Opcions. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el men&uacute; Opcions tenim dos apartats: Configuraci&oacute; i El meu compte.<br>
        <br>
        L'apartat Configuraci&oacute; ens porta a un altre submen&uacute; on tenim totes les opcions de configuraci&oacute; de l'aplicatiu i l'apartat El meu compte ens permet modificar algunes dades pr&ograve;pies de l'usuari identificat a l'aplicatiu.<br>
        <br>
        <span class=\"est3\">&middot; 9.1. El meu compte.</span><br>
        El contingut d'aquest apartat es mostra de forma diferent si l'usuari es un Pare o un Professor. Encara que algunes parts s&oacute;n comunes a tots dos tipus d'usuaris.<br>
        <br>
        Com a parts comunes, tenim l'opci&oacute; de canviar la contrasenya d'acc&eacute;s a l'aplicatiu i l'opci&oacute; de introduir un n&uacute;mero de tel&egrave;fon m&ograve;bil on podem rebre missatges SMS enviats des de l'apartat &quot;Tutoria/Comunicaci&oacute;/SMS&quot;. Si no indiquem cap tel&egrave;fon m&ograve;bil no rebrem cap missatge SMS.<br>
        <br>
        Per al cas d'esser un usuari Pare, tamb&eacute; veure'm les dades generals corresponents a l'alumne, entre elles la seva foto i les dades d'enviament de correu ordinari. Si un pare observa que les dades de correu s&oacute;n incorrectes, ho pot comunicar al seu tutor des de l'apartat de Comunicaci&oacute;.<br>
        <br>
        Per al cas d'esser un usuari Professor, tamb&eacute; tenim la possibilitat de canviar el nom real que apareix en els diferents documents de l'Aplicatiu Tutoria.<br>
        <br>
        <span class=\"est3\">&middot; 9.2. Configuraci&oacute;</span><br>
        Els diferents elements del submen&uacute; d'aquest apartat solament s&oacute;n accessibles pels administradors i corresponen a tasques de configuraci&oacute; de l'aplicatiu. Aquests elements s&oacute;n: Par&agrave;metres, Inserir Alumnes, Acc&eacute;s Pares, Posar fotos, Crear subgrups, Horaris-privilegis, Horaris-marc, Franges hor&agrave;ries, Veure logs.<br>
        <br>
        <span class=\"est3\">&middot; 9.2.1. Par&agrave;metres.</span><br>
        Aqui hi tenim dos parts: la part superior correspon a la configuraci&oacute; de les dades generals de l'aplicatiu i la part inferior correspon a la configuraci&oacute; del servei d'enviament de missatges SMS.<br>
        <br>
        En la part superior introdu&iuml;m les dades principals que personalitzen el funcionament de l'aplicatiu, entre elles, les dades del centre, el curs acad&egrave;mic, el logotip, la data d'inici de curs, etc. Aquestes dades s'utilitzen per generar informes i altres representacions en altres apartats de l'Aplicatiu Tutoria.<br>
        <br>
        En la part inferior es personalitza les dades de connexi&oacute; al prove&iuml;dor d'enviament de missatges SMS. Es necessari haver efectuat la contractaci&oacute; d'aquest servei abans de poder-lo configurar, ja que la majoria de dades que s'han de posar ens les ha d'indicar el prove&iuml;dor d'aquest servei. <br>
        <br>
        El nom de remitent ser&agrave; el nom que apareixer&agrave; com a remitent en qualsevol dels missatges enviats des de l'aplicatiu, ha d'estar format per lletres normals, sense espais i com a m&agrave;xim 11 caracters. Si es deixa en blanc, al remitent del missatge enviat hi apareixera el nom real del professor que ho envia, retallat a 11 caracters i sense espais.<br>
        <br>
        Els altres quatre par&agrave;metres ens els subministra el prove&iuml;dor:<br>
        Url  prove&iuml;dor SMS es text del tipus: \"xxx.proveidor.com\"<br>
        N&uacute;mero de port: &eacute;s el port d'acc&eacute;s al servei<br>
        Usuari i contrasenya: s&oacute;n els corresponents al compte que tinguem contractat.<br>
        <br>
        Si les dades de configuraci&oacute; s&oacute;n correctes, a la part inferior ens mostrar&agrave; el saldo en cr&egrave;dits disponibles a la nostra compta. En canvi, si no estan indicats els par&agrave;metres de configuraci&oacute; o aquests s&oacute;n incorrectes, a la part inferior, el saldo ens dir&agrave; error.<br> 
        <br>
        <br>
        <span class=\"est3\">&middot; 9.2.2. Inserir alumnes.</span><br>
        No existeix la possibilitat de inserir alumnes, eliminar-los, modificar-los o canviar-los de grup, de forma manual, dins de l'aplicatiu, solament es pot fer important-los des del programa de gesti&oacute; de centres docents Winsec. Aquesta limitaci&oacute; existeix per a garantitzar la integritat de les dades que existeix al programa de gesti&oacute; de centres i l'Aplicatiu Tutoria. Mai es podran tenir dades de l'aplicatiu m&eacute;s actualitzades que les que hi ha en el programa de gesti&oacute; de centres. <br>
        <br>
        Per tant, cada vegada que es desitgi actualitzar les dades d'un alumne, o b&eacute;, canviar-lo de grup-classe, sera necessari fer-ho, primer que tot, en el programa de gesti&oacute; del centre, i posteriorment, s'haura de fer la importaci&oacute; d'aquestes dades tal com es descriu a continuaci&oacute;. Amb aix&ograve;, s'ha d'entendre que la tasca de inserci&oacute; d'alumnes s'ha de fer de forma peri&ograve;dica al llarg del curs si es desitja que les dades que consten a l'aplicatiu estiguin actualitzades amb les que consten al programa de gesti&oacute; del centre.<br>
        <br>
        La importaci&oacute; es realitza en tres passos:<br>
        El primer pas consisteix en aplicar la consulta indicada en el programa de gesti&oacute; de centres Winsec, amb aix&ograve; s'obt&eacute; un fitxer CSV de nom alumnes.txt.<br>
        <br>
    El segon pas consisteix en carregar a l'aplicatiu el fitxer creat en el pas anterior. Seguidament, l'aplicatiu analitza el contingut d'aquest fitxer carregat i ens mostra les novetats que es troba en el nou llistat d'alumnes respecte del que ja tenia carregat, i ens informa de quins alumnes seran donats d'alta per que no existien pr&egrave;viament i de quins alumnes seran donats de baixa per que ja no existeixen en el fitxer carregat.<br>
    <br>
    Encara que no s'indiqui, els alumnes que ja existien i continuen existint en el nou fitxer carregat per&ograve; s'ha modificat alguna dada com el curs, grup, adre&ccedil;a, tel&egrave;fon, etc. aquestes modificacions quedaran registrades en el nou conjunt d'alumnes carregats.<br>
    <br>
    Finalment, en el tercer pas, l'aplicatiu ens demana que confirmem els canvis que s'efectuaran, un cop validat, els alumnes ja estaran disponibles amb les seves dades actualitzades. Cal tenir en compte que si un alumne es donat de baixa de l'Aplicatiu Tutoria, tamb&eacute; s'eliminen totes les dades que consten sobre aquest alumne, entre elles, la compta d'acc&eacute;s dels pares, totes les seves incid&egrave;ncies registrades, la foto, etc.<br>
    <br>
    <span class=\"est3\">&middot; 9.2.3. Acc&eacute;s Pares.</span><br>
    En aquest apartat hi veiem una relaci&oacute; dels accessos, entre dos dates, fets pels pares a l'Aplicatiu Tutoria, indicant l'identificador de l'usuari pare que ha accedit, la data i hora d'acc&eacute;s, la IP de l'ordinador i un text explicatiu on s'indica el nom de l'alumne.<br>
    <br>
    Des de l'enlla&ccedil; &quot;Veure identificadors &quot; accedim a un apartat que ens permet veure les dades d'acc&eacute;s dels usuaris pares. Podem tornar a l'apartat de logs clicant a sobre l'enlla&ccedil; &quot;Veure logs&quot;.<br>    
    <br>
    En aquest altre apartat hi veiem tots els identificadors i contrassenyes dels usuaris pares que formen part de l'aplicatiu i tamb&eacute; tenim l'opci&oacute; d'activar els permisos d'acc&eacute;s a l'aplicatiu i generar un informe on s'indica als pares, la forma i les dades d'acc&eacute;s.<br>
    <br>
    La generaci&oacute; dels usuaris pares es fa de forma autom&agrave;tica en el moment en que es introduit l'alumne corresponent des de l'apartat &quot;Opcions/Configuraci&oacute;/Inserir alumnes&quot;. Encara que uns mateixos pares tinguin m&eacute;s d'un fill en el centre, aquests disposaran de tants identificadors diferents com fills hi tinguin al centre. L'usuari pare es donat de baixa autom&agrave;ticament quan, en fer una nova inserci&oacute; d'alumnes, l'alumne corresponent a aquest usuari pare sigui donat de baixa de l'Aplicatiu Tutoria.<br>
    <br>
    Pel fet que existeixi creat l'usuari pare, aix&ograve; no vol dir que ja hi puguin accedir, es necessari que, pr&egrave;viament, un professor administrador hagi activat els permissos d'acc&eacute;s i li hagi donat l'impr&eacute;s on s'indica les seves dades d'acc&eacute;s a l'aplicatiu.<br>
    <br>    
    <span class=\"est3\">&middot; 9.2.4. Posar fotos.</span><br>
    En aquest apartat podem introduir una foto tamany carnet de cada alumne. Per aix&ograve; es necessari disposar d'un fitxer digital en format .jpg corresponent a la foto d'uns 93x125 px i un m&agrave;xim de 10kby i carregar-la a l'aplicatiu, amb aix&ograve; la foto quedar&agrave; associada a l'alumne corresponent i es veura des de altres apartats de l'aplicatiu tutoria.<br>
    Si un alumne no se li adjunta cap foto, es veur&agrave; la foto per defecte que li assigna l'aplicatiu.<br>
    <br>
    <span class=\"est3\">&middot; 9.2.5. Crear subgrups.</span><br>
    Per defecte, l'Aplicatiu Tutoria organitza els alumnes per grups-classe, tal com s'ha importat des del programa de gesti&oacute; del centre i no es possible canviar l'alumne de grup o curs des d'aquest aplicatiu a no ser que sigui fent una nova importaci&oacute;.<br>
    <br>
    Per&ograve;, &eacute;s possible crear subgrups formats a partir dels alumnes que es troben en els diferents grups. La creaci&oacute; de subgrups es v&agrave;lida per a formar els subgrups corresponents a cr&egrave;dits variables o cr&egrave;dits optatius o de modalitat, que posteriorment es poden assignar en els horaris-privilegis dels professors per que en puguin tenir acc&eacute;s des de l'apartat d'incid&egrave;ncies. Tamb&eacute; pot ser &uacute;til la creaci&oacute; de subgrups quan es desitja fer llistes d'alumnes o de fotos que corresponen a agrupacions d'alumnes formats des de diferents grups classe.<br>
    <br>
    Per la creaci&oacute; dels subgrups, tenim una finestra on hi ha dos columnes, a l'esquerra hi tenim una columna on hi ha l'opci&oacute; &quot;Nou subgrup&quot;, indicant la refer&egrave;ncia i el nom del nou subgrup, aquest ja ser&agrave; accessible des de la llista desplegable de subgrups en la columna de l'esquerra. Un cop seleccionat el subgrup, tamb&eacute; tenim disponible l'opci&oacute; d'esborrar el subgrup amb &quot;Esborrar subgrup&quot;.<br>
    <br>
    Un cop hem seleccionat un subgrup, li podem afegir alumnes, per aix&ograve;, 
    a la de la dreta hi apareixen els diferents grups des d'on podem seleccionar els alumnes que han de formar part del subgrup, clicant a sobre del nom, aquest s'afegir&agrave; al subgrup, si cliquem a sobre del nom d'un alumne de la columna de l'esquerra, aquest s'elimina del subgrup.<br>
    <span class=\"est3\"><br>
&middot; 9.2.6. Horaris - privilegis.</span><br>
En aquest apartat &eacute;s on es donen d'alta els usuaris professors, indicant si es o no tutor, si es o no administrador i la relaci&oacute; de grups i subgrups que t&eacute; associat per a cada dia de la setmana i hora lectiva.<br>
    <br>
    Per crear un nou usuari professor, cliquem a sobre de &quot;Nou&quot;, ens apareix un formulari on es demana les dades b&agrave;siques d'aquest usuari professor. Seguidament, el podrem seleccionar des de la llista desplegable d'usuaris-professors i introduir la resta de dades.<br>
    <br>
    Si es desitja eliminar un usuari-professor, primer el seleccionem i posteriorment cliquem a sobre de &quot;Eliminar&quot;.<br>
    <br>
    Per a cada usuari-professor, podem decidir si ser&agrave; administrador o no. Poden existir m&uacute;ltiples usuaris administradors en l'aplicatiu.<br>
    <br>
    Tamb&eacute; podem indicar si es tutor, per aix&ograve; ho fem clicant a &quot;Canviar&quot; de &quot;Tutor de:&quot; i li assignem un grup d'alumnes. Pel fet de tenir un grup d'alumnes assignat com a tutor, aquest usuari ja es considera com a usuari-tutor amb els permisos corresponents a un tutor sobre la informaci&oacute; d'aquest grup.<br>
    <br>
    En la graella de la part de sota indiquem, per a cada hora lectiva i dia de la setmana, els grups i/o subgrups d'alumnes que tingui assignats aquest usuari professor. Cal tenir en compte que aqui no es detalla l'assignatura que fa el professor, simplement s'indica el grup d'alumnes que t&eacute; assignat, independentment de l'assignatura, ja que la informaci&oacute; de la assignatura no es necess&agrave;ria pel funcionament de l'aplicatiu Tutoria. Per als usuaris Administradors no fa falta indicar res en aquesta graella, ja que per defecte tenen acc&eacute;s a totes les funcionalitats de l'aplicatiu sense cap restricci&oacute;, encara que si ho tenen definit no &eacute;s cap inconvenient.<br>
    <br>
    L'assignaci&oacute; de les dades en aquest apartat 
    serveixen per configurar els permisos que tindr&agrave; cada usuari-professor i s&oacute;n els que posteriorment serviran per decidir si un determinat professor pot veure, o no, alguna informaci&oacute; de les que estan disponibles en l'aplicatiu, o pot introduir, o no, incid&egrave;ncies a un determinat grup o subgrup d'alumnes en un dia i hora de la setmana, etc. <br>
    <br>
    El cas particular de que en una hora s'assigni una &quot;Gu&agrave;rdia&quot; a un usuari-professor fa que en aquesta hora, el professor corresponent pugui posar incid&egrave;ncies a qualsevol grup i subgrup d'alumnes del centre. Si no es desitja que aix&ograve; sigui aix&iacute;, cal treure dels horaris-privilegis les hores amb la assignaci&oacute; &quot;Gu&agrave;rdia&quot;.<br>    
    <br>
    <span class=\"est3\">&middot; 9.2.7. Horaris - marc.</span><br>
    En aquest apartat indiquem quines hores s&oacute;n lectives per a cada grup d'alumnes, dins de la totalitat de franges horaries que t&eacute; definides el centre des de l'apartat &quot;Opcions/Configuraci&oacute;/Franges hor&agrave;ries&quot;. Si d'un grup d'alumnes no definim un marc horari, es considera que s&oacute;n lectives totes les hores indicades en les franges hor&agrave;ries, aquesta situaci&oacute; no es aconsellable, ja que generalment, les franges hor&agrave;ries definides solen ser m&eacute;s extenses que les que un sol grup utilitza com a lectives.<br>
    <br>
    La definici&oacute; dels horaris marc serveix per a evitar que en el Registre d'incidencies es pugui tenir l'opci&oacute; d'introduir una incid&egrave;ncia en una hora que potser no es lectiva per a un determinat grup, per&ograve;, aquesta hora existeix per que ha estat definida en les franges hor&agrave;ries i, es utilitzada, possiblement, com a lectiva per algun altre grup. <br>
    <br>
    Per definir un horari marc d'un grup ho fem seleccionant &quot;Nou&quot;, aix&ograve; ens dona l'opci&oacute; de triar el grup indicant curs, grup i etapa. Si a grup indiquem *, aquest horari marc ser&agrave; v&agrave;lid per a tots els grups que tinguin el mateix curs i etapa. Un cop indicades aquestes dades, ens apareix una graella, amb els dies de la setmana i franges hor&agrave;ries, clicarem a sobre de cada element de la graella per a fer que aquella hora sigui lectiva per aquest grup i per tant, seleccionable des de l'apartat &quot;Registre d'Incid&egrave;ncies.&quot;<br>
    <br>
    <span class=\"est3\">&middot; 9.2.8. Franges hor&agrave;ries.</span><br>
    En aquest apartat definim totes les franges hor&agrave;ries del centre indicant el seu nom, l'hora d'inici i l'hora de finalitzaci&oacute;.<br>
    <br>
    Aquesta llista de franges hor&agrave;ries s&oacute;n les que es mostraran en les diferents graelles que es poden veure en els altres apartats de l'aplicaci&oacute;, com ara, els Marcs hor&agrave;ris, els Horaris-privilegis, el Registre d'incid&egrave;ncies, etc.<br>
    <br>
    L'hora d'inici i finalitzaci&oacute; de cada franja hor&agrave;ria serveixen per ordenar aquestes franges hor&agrave;ries per ordre ascendent i es podran veure aquestes hores d'inici i finalitzaci&oacute; situant el ratoli a sobre del nom corresponent de la franja hor&agrave;ria en forma de tooltip.<br>
    <br>
    <span class=\"est3\">&middot; 9.2.9. Veure logs.</span><br>
    En aquest apartat es t&eacute; una relaci&oacute; dels accessos efectuats pels usuaris professors, indicant l'identificador de l'usuari, la data i hora d'acc&eacute;s, la IP de l'ordinador utilitzat i el nom complet de l'usuari. </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">10.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Ajuda. </td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el men&uacute; Ajuda hi tenim les opcions de visualitzaci&oacute; d'aquest Manual de l'aplicaci&oacute; i els Cr&egrave;dits.<br>
        <br>
        El Manual de l'aplicaci&oacute; es mostra de forma diferent segons l'usuari sigui Administrador, professor tutor, professor o pare.<br> 
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>
</table>
");

else if ($esProfessoroTutor) print("
<table cellSpacing=4 cellPadding=2 width=614 border=0>
  <tr>
    <td colspan='2' align='center'>$cop<br>Manual d'usuari-professor</td>
  </tr>
  <tr>
    <td width=82 valign='top' class=\"est2\">INDEX:</td>
    <td width=512 valign='top'>
    	1.- Funcionalitat bàsica.<br>		
		2.- Accés mitjançant PDA.<br>		
		3.- Menú Arxiu.<br>
		4.- Menú Tutoria.<br>
		5.- Menú Incidències.<br>
		6.- Men&uacute; Opcions.<br>
		7.- Men&uacute; Ajuda.<br>
    </td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">1.</div></td>
    <td valign='top' class=\"est2\">Funcionalitat bàsica.</td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>
    	<div align=\"justify\">L'Aplicatiu Tutoria esta disenyat per a ser una eina d'ajuda als Tutors en la realització de les seves funcions.
    	Per això inclou un conjunt d'utilitats que es poden classificar en tres blocs principals: Registres de tutoria per al tutor. Gesti&oacute; de les incid&egrave;ncies i Comunicaci&oacute; entre el colectiu d'usuaris del sistema.<br>
    	<br>
    	Per facilitar aquestes tasques als Tutors, es necessaria la participaci&oacute; de la resta de professors i els pares, per aix&ograve;, tamb&eacute; formen part de l'aplicatiu els usuaris professors que no son tutors i els usuaris pares.<br>
    	<br>
    	Per tant, la totalitat d'usuaris del sistema es pot classificar en Professors-Administradors, Professors-Tutors, Professors i Pares. <br> 
    	</div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">2.</div></td>
    <td valign='top' class=\"est2\">Acc&eacute;s mitjan&ccedil;ant PDA</td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">&middot; Acc&eacute;s mitjan&ccedil;ant PDA.<br>
        Si el centre disposa d'una xarxa inalambrica Wi-Fi que permeti accedir al servidor que cont&eacute; l'aplicaci&oacute; Tutoria, i els professors disposen d'una PDA amb navegador web i acc&eacute;s Wi-Fi configurat per accedir a la xarxa Wi-Fi del centre, podran accedir a l'aplicaci&oacute; Tutoria mitjan&ccedil;ant l'adre&ccedil;a web: &quot;&lt;url acc&eacute;s a l'aplicaci&oacute;&gt;/index_pda.php&quot;, aquesta adre&ccedil;a web &eacute;s una p&agrave;gina html adaptada als navegadors PDA que els facilita la identificaci&oacute; de l'usuari professor i l'acc&eacute;s a la introducci&oacute; d' Incid&egrave;ncies. Aix&ograve; facilita al professor la tasca de passar llista i anotar incid&egrave;ncies al grup d'alumnes corresponent des de la mateixa aula en temps real, a la vegada, un cop s'ha passat llista, es pot veure des del mateix Aplicatiu Tutoria les incid&egrave;ncies que s'han introdu&iuml;t en aquella mateixa hora de classe. <br>
        <br>
</div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">3.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Arxiu. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el menu Arxiu hi tenim dos opcions:<br>
        <br>
&middot; Tancar sessi&oacute;.<br>
Aquesta opci&oacute; tanca la sessi&oacute; actual i permet introdu&iuml;r una nova sessi&oacute; amb un altre identificador i contrasenya.<br>      
<br>
&middot; Sortir<br>
Aquesta opci&oacute; tanca l'aplicaci&oacute; Tutoria. <br>
    </div></td>
  </tr>
    <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">4.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Tutoria. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el men&uacute; Tutoria hi tenim les opcions corresponents a les tasques del tutor. Els subapartats s&oacute;n:<br>
        <br>
        <span class=\"est3\">&middot; 4.1.  Registres de Tutoria.</span><br>
        Nom&eacute;s accessibles pels Administradors i Tutors. Aqui disposem d'un espai on podem seleccionar el grup d'alumnes i per cada alumne hi trobem les seves dades personals (adre&ccedil;a, poblaci&oacute;, nom pares, etc.). Tamb&eacute; disposem de tres subapartats: &quot;Entrevistes&quot;, &quot;Fitxers&quot; i &quot;Informes d'incid&egrave;ncia&quot;.<br>
    L'apartat &quot;Entrevistes&quot; serveix per a registrar, a modus d'agenda, les entrevistes fetes amb els pares i els continguts d'aquestes, associada a cada alumne del grup.<br>
    L'apartat &quot;Fitxers&quot; serveix per desar-hi fitxers creats o que disposi el tutor i estiguin associats a un determinat alumne o b&eacute; a tot el grup-classe.<br>
    L'apartat &quot;Informes d'incid&egrave;ncia&quot; &eacute;s on es troba tots els Informes d'Incid&egrave;ncia corresponents a un determinat alumne que hagin estat creats pels professors des de l'apartat &quot;Incid&egrave;ncies/Informes d'Incid&egrave;ncia&quot;, aquest apartat &eacute;s un espai de comunicaci&oacute; on els professors poden crear els informes d'incid&egrave;ncia que considerin apropiats per a un determinat alumne i a la vegada el tutor el pot veure des de Registres de Tutoria/Informes d'Incid&egrave;ncia.<br>
    <br>
    Les Entrevistes, Fitxers i Informes d'incid&egrave;ncia poden ser visualitzats alumne per alumne, o de tots els alumnes del grup o b&eacute; els comuns a tot el grup des de la llista seleccionable &quot;Alumne&quot;.<br>
    <br>
    <span class=\"est3\">&middot; 4.2.  Informe lliure.</span><br>
    Aquest apartat &eacute;s accessible pels Administradors i Tutors. Serveix per a crear cartes circulars personalitzades dirigides a un alumne o grup d'alumnes. En la creaci&oacute; del text &eacute;s pot posar marques corresponents a noms i cognoms dels pares, de l'alumne, adre&ccedil;a, poblaci&oacute;, data, tel&egrave;fon, curs, grup, etapa, etc. Les dades s'agafen directament de la base de dades a mesura que es van creant les cartes circulars. A l'apartat &quot;Ajuda&quot;, dins de l'edici&oacute; d'un informe lliure es t&eacute; una llista dels diferents camps que es pot incloure dins l'informe lliure que es desitja realitzar.<br>
    <br>
    La selecci&oacute; d'alumnes es fa des de l'apartat &quot;Alumnes&quot;. Aqui ens apareix una finestra que t&eacute; dos columnes, a la columna de la dreta tenim la llista d'alumnes seleccionables, aquests es poden seleccionar clicant a sobre del nom. Un cop seleccionats, aquests apareixen a la columna de l'esquerra. Quan ja tenim la selecci&oacute; d'alumnes feta, cliquem sobre &quot;Tancar&quot;. Posteriorment, si cliquem sobre &quot;Imprimir&quot; ens apareixer&agrave; un document per imprimir, repetit tantes vegades com alumnes s'hagin seleccionat amb les dades personalitzades de cadasc&uacute;n d'ells.<br>
    <br>
    <span class=\"est3\">&middot; 4.3. Comunicaci&oacute;.</span><br>
    Aquest apartat serveix com a element de comunicaci&oacute; interna de l'Aplicatiu Tutoria. No es un gestor de correu electr&ograve;nic, per&ograve; t&eacute; un funcionament similar, encara que els &uacute;nics usuaris que es poden comunicar entre ells s&oacute;n els registrats en l'aplicaci&oacute;: Pares i Professors. Per tant, aquest apartat &eacute;s una missatgeria interna. <br>
    Tamb&eacute; es permet l'enviament de missatges SMS als usuaris que han introdu&iuml;t el n&uacute;mero de tel&egrave;fon m&ograve;bil dins l'apartat: &quot;Opcions/El meu compte&quot;.<br>
    <br>
    La missatgeria interna la poden utilitzar tant els professors com els pares, ara b&eacute;, segons el tipus d'usuari, solament es podran comunicar amb alguns altres tipus d'usuari.<br>
    Per exemple:<br>
    - 
    Els pares solament poden enviar missatges nous al tutor del seu fill i poden respondre a qualsevol missatge que l'hi hagi enviat qualsevol usuari. Els pares no poden enviar missatges SMS a cap usuari.<br>
    <br>
    -Els professors que no son administradors ni tutors, poden enviar missatges interns o SMS's als altres professors.<br>
    <br>
    -Els tutors poden enviar missatges interns i SMS's als altres professors i pares del seu grup de tutoria.<br>
    <br>
    -Els administradors poden enviar missatges interns i SMS's a qualsevol professor i qualsevol pare.<br>
    <br>
    Per poder enviar missatges interns a un determinat pare, &eacute;s necessari que, pr&egrave;viament, se li hagi donat el seu identificador i contrasenya i s'hagin activat els permisos per accedir a l'Aplicatiu Tutoria des de l'apartat &quot;Opcions/Configuraci&oacute;/Acc&eacute;s Pares&quot; i &quot;Veure identificadors&quot;.<br>
    <br>
    Per poder enviar missatges SMS a un determinat usuari, pare o professor, es necessari que aquest usuari hagi indicat el seu n&uacute;mero de tel&egrave;fon m&ograve;bil a l'apartat &quot;Opcions/El meu compte&quot;.<br>
    <br>
    Un cop s'ha accedit dins l'apartat Comunicaci&oacute;, ens apareix per defecte la missatgeria interna, si desitgem elegir la missatgeria SMS ho farem clicant al recuadre de la part superior esquerra.<br>
    <br>
    Tots dos entorns (missatgeria interna i missatgeria SMS) s&oacute;n molt similars, amb la difer&egrave;ncia que en la missatgeria SMS nomes hi veurem missatges enviats per nosaltres i cap missatge rebut, ja que l'aplicatiu no esta configurat per a rebre missatges SMS des de l'exterior.<br>
    <br>
    En tots dos casos disposem d'una part superior i una part inferior, en la superior hi veurem la llista de missatges enviats i rebuts i clicant sobre l'assumpte, ens mostrar&agrave; el seu contingut en la part inferior. Els missatges amb una icona d'un clip al seu costat vol dir que s&oacute;n missatges amb fitxers adjunts, els missatges amb una banderola vermella, vol dir que s&oacute;n nous i no han estat vistos, i els missatges amb una banderola verda vol dir que han estat marcats com a pendents de resposta, aquesta darrera opci&oacute; &eacute;s un recordatori per a l'usuari. <br>
    <br>
    En principi disposem de dos carpetes de missatges: General i Paperera. A la carpeta General &eacute;s on hi trobem els missatges enviats i rebuts per defecte i a la Paperera, aquells missatges que han estat esborrats. Podem crear tantes noves carpetes personalitzades com vulguem per classificar els missatges clicant a sobre de la icona carpeta que hi ha a la part superior de la finestra, la nova carpeta personalitzada ser&agrave; accessible des del desplegable &quot;Carpetes&quot;. Quan visualitzem un missatge a la part inferior, tindrem l'opci&oacute; de moure'l a la carpeta personalitzada que desitgem per classificar-lo.<br>
    <br>
    Per crear un nou missatge ho fem clicant a sobre de l'enlla&ccedil; superior &quot;Nou missatge&quot;. En la part inferior ens apareix els camps de formulari per introduir l'Assumpte i el Contingut. Tamb&eacute; podem clicar a sobre de &quot;Afegir adjunt&quot; per adjuntar-hi altres fitxers.<br>
    <br>
    La selecci&oacute; dels destinat&agrave;ris del missatge es fa clicant a sobre de &quot;Destinataris&quot;, seguidament ens apareix una nova finestra on a la columna de la dreta i tenim els possibles destinataris a elegir. Elegirem un destinatari clicant a sobre d'ell, aix&ograve; fa que aquest s'afegeixi a la columna de &quot;Seleccionats&quot; a la part esquerra de la finestra. Finalment, clicant a sobre de &quot;Actualitzar&quot; introduirem la llista de destinataris al missatge que estem creant.<br>
    <br>
    En aquest apartat tamb&eacute; podem crear grups de destinataris personalitzats, per exemple: el grup de professors tutors, o b&eacute; el grup de professors del Departament de Llengua Catalana,  o b&eacute; el grup de pares d'una determinada activitat, etc. Per fer-ho, s'ha de seleccionar el conjunt de destinataris i clicar sobre l'opci&oacute; &quot;Crear grup&quot;, seguidament li donem un nom adequat i a partir d'ara ja disposarem d'aquest grup de destinataris personalitzat des del desplegable superior de la columna de la dreta.<br>
    <br>
    Cal recordar que segons quin tipus d'usuari es sigui, la llista de possibles destinataris de la columna de l'esquerra pot quedar molt redu&iuml;da, per exemple, si es un pare, solament podr&agrave; elegir com a destinatari el tutor corresponent, per tant, ja no disposar&agrave; de l'opci&oacute; de &quot;Crear grup&quot;. Tampoc es veuran tots el pares si aquests no tenen activat l'acc&eacute;s a l'Aplicatiu Tutoria.<br>
    <br>
    Finalment, un cop hem clicat sobre &quot;Actualitzar&quot;, els destinataris quedaran afegits al nou missatge i aquest es podr&agrave; enviar clicant a sobre de la icona &quot;Enviar&quot; o &quot;Enviar SMS&quot;. En el cas de tractar-se d'un SMS, se'ns informa de si la transmissi&oacute; ha estat correcta o no i en cas de tractar-se d'un usuari Administrador, tamb&eacute; ens indica el saldo de cr&egrave;dits disponible des del prove&iuml;dor de SMS. <br>
    <br>
    El missatges que s'estiguin visualitzan (que no hagin estat escrits per nosaltres) es poden respondre al seu remitent clicant sobre la icona &quot;Respon&quot;, aquest cas es similar a la creaci&oacute; d'un nou missatge, per&ograve;, ja apareix automaticament el nom del destinatari, l'assumpte i el text original.<br>
    <br>
    Quan visualitzem el contingut d'un missatge, se'ns mostra la icona &quot;Pendent&quot;, &quot;Mou a carpeta&quot;, &quot;Esborra&quot;, &quot;Historial&quot; (solament en cas de ser nosaltres els remitents) i &quot;Respon&quot; (solament en el cas que nosaltres no siguem els remitents).<br>
    La icona &quot;Pendent&quot; serveix per a marcar un missatge com a pendent de resposta, posant una banderola verda al seu costat, es a dir, com a un recordatori, la propera vegada que visualitzem aquest missatge, la banderola verda es torna a desactivar.<br>
    La icona &quot;Mou a carpeta&quot; ens permet moure el missatge a altres carpetes personalitazades creades per nosaltres per classificar els missatges.<br>
    La icona &quot;Esborra&quot; mou el missatge a la Paperera. Des de la carpeta &quot;Paperera&quot; podem tornar a accedir al missatge per recuperar-lo o per eliminar-lo definitivament.<br>
    La icona &quot;Historial&quot; apareix si nosaltres som els remitents del missatge i ens mostra una finestra on ens diu quins destinataris han vist el missatge i la seva data i hora.<br>
    <br>
    <span class=\"est3\">&middot; 4.4. Llistes alumnes.</span><br>
    En aquest apartat es possible obtenir llistes d'alumnes dels diferents grups i subgrups.  Les llistes s&oacute;n en format pdf, preparades per a ser impreses, per&ograve;, es possible exportar el seu contingut a fulls de c&agrave;lcul o bases de dades si es fa una selecci&oacute; mitjan&ccedil;ant l'eina &quot;Seleccionar columna&quot; del visualitzador de pdf's i posteriorment es fa &quot;Copiar i Enganxar&quot;. <br>
    <br>
    <span class=\"est3\">&middot; 4.5. Fotos alumnes.</span><br>
    En aquest apartat podem veure les fotografies dels alumnes en format web i en format pdf per a imprimir. Si un alumne no t&eacute; fotograf&iacute;a, s'utilitza una imatge per defecte.<br> 
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">5.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Incid&egrave;ncies. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En aquest men&uacute; hi tenin els apartats corresponents a la gesti&oacute; d'incid&egrave;ncies dels alumnes del centre. Els seus apartats es poden classificar en tres tipus: Introducci&oacute; i registre de les incid&egrave;ncies (Registre d'incid&egrave;ncies, Justificar incid&egrave;ncies i Informes d'incid&egrave;ncia), resums individual i col&middot;lectiu de les incid&egrave;ncies registrades (Incid&egrave;ncies per alumne i Incid&egrave;ncies per grup) i extracci&oacute; de documents referents a les incid&egrave;ncies (Informe peri&ograve;dic i Apercebiments).<br>
        <br>
        <span class=\"est3\">&middot; 5.1. Registre d'incid&egrave;ncies.</span><br>
        Aquest apartat &eacute;s accessible per tots els professors i poden visualitzar les incid&egrave;ncies hor&agrave;ries que hi estan registrades pels diferents grups i alumnes. Si el professor t&eacute; assignat el grup o subgrup  que esta visualizant, tamb&eacute; podr&agrave; introduir incid&egrave;ncies en aquelles hores del seu horari, es a dir, les que consten en els seus privilegis.<br>
        <br>
        Els tipus d'incid&egrave;ncia que es pot anotar s&oacute;n les seg&uuml;ents: F - Falta d'assist&egrave;ncia, R - Retard, E - Expulsi&oacute; i A - Anotaci&oacute;. Per cada incid&egrave;ncia anotada, clicant sobre la T es pot introduir un text curt explicatiu d'aquella incid&egrave;ncia. Si no s'ha introduit cap incid&egrave;ncia el text explicatiu no es desar&agrave;. La utilitzaci&oacute; del text explicatiu &eacute;s necess&agrave;ria per al cas d'introduir una anotaci&oacute; (A), ja que aquesta no defineix per si mateix el tipus d'incid&egrave;ncia i es necessari introduir el text explicatiu per definir-la. Pel cas de retards, aquest text explicatiu pot servir per indicar el temps de retard.<br>    
        <br>
        Es pot donar el cas que un professor que tingui privilegis adequats sobre una determinada hora i grup o subgrup no pugui introduir la incid&egrave;ncia en una determinada data per que el tutor del grup hagi bloquejat aquesta opci&oacute; per a tots els professors des de l'apartat &quot;Incid&egrave;ncies/Justificar incid&egrave;ncies&quot; &quot;Data bloqueig&quot;. En aquest cas es necessari avisar al tutor per que modifiqui la &quot;Data de bloqueig&quot;. Tamb&eacute; pot passar que no sigui possible introduir incid&egrave;ncies per a unes hores d'un grup pel fet que tingui l'Horari Marc mal configurat.<br>    
        <br>
        Si un professor t&eacute; assignada una &quot;Gu&agrave;rdia&quot; en el seu horari-privilegis per a una determinada hora de la setmana, aquest podr&agrave; introduir incid&egrave;ncies a qualsevol grup i subgrup del centre ja que es considera que al tenir &quot;Gu&agrave;rdia&quot; es t&eacute; assignat tots els grups del centre.<br>
        <br>
        Els drets a introduir incid&egrave;ncies a un determinat alumne solament s&oacute;n efectius si es t&eacute; seleccionat el grup o subgrup corresponent que tingui assignat el professor en els seus privilegis. Es a dir, si un professor t&eacute; assignat en una determinada hora del seu Horari-privilegis, un subgrup que es un subconjunt d'alumnes d'un grup classe, no podr&agrave; introduir les incidencies als alumnes si els esta visualitzant com a grup, en canvi, si que ho podr&agrave; fer si els visualitza com al subgrup que consta en el seu Horari-privilegis.<br>
        <br>
        El professor Tutor tamb&eacute; podr&agrave; introduir incid&egrave;ncies a qualsevol hora pels alumnes de la seva tutoria, a part dels que ja t&eacute; indicats dins el seu horari de classe. El professor Administrador pot introduir incid&egrave;ncies a tothom sense restriccions.<br>
        <br>
        Qualsevol professor tamb&eacute; pot crear un Informe d'incid&egrave;ncia amb text explicatiu molt extens clicant a sobre de &quot;I&quot;, que es troba al costat de la foto de l'alumne. Aquest informe d'incid&egrave;ncia extens, quedar&agrave; desat dins l'apartat &quot;Incid&egrave;ncies/Informe d'incid&egrave;ncies&quot; pr&ograve;pi de l'usuari professor i tamb&eacute; ser&agrave; accessible pel tutor del grup dins l'apartat &quot;Tutoria/Registres de tutoria&quot; i com a tipus de registre: &quot;Informes d'incid&egrave;ncia&quot;.<br>
        <br>
        Mitjan&ccedil;ant una PDA, tal com s'explica a l'apartat 2 d'aquest manual, es pot accedir a una p&agrave;gina web adaptada als navegadors de la PDA, que tamb&eacute; permet introdu&iuml;r les incid&egrave;ncies de forma similar a com es descriu aqui, per&ograve; des de la mateixa aula on el professor fa classe i en temps real sense la necessitat de tenir disponible un ordinador.<br>
        <br>
        <span class=\"est3\">&middot; 5.2. Justificar Incid&egrave;ncies.</span><br>
        Aquest apartat solament &eacute;s accessible pels Administrador i els Tutors, en el cas dels tutors, solament podran visualitzar el grup o subgrup del qual siguin tutors.<br>
        <br>
        La seva funcionalitat es gaireb&eacute; id&egrave;ntica a la de l'apartat &quot;Registre d'Incid&egrave;ncies&quot;. Per&ograve;, en tractar-se d'un apartat d'&uacute;s exclusiu pels tutors, tamb&eacute; afegeix l'opci&oacute; de modificar les incid&egrave;ncies registrades i justificar els retards (RJ) i les faltes d'assist&egrave;ncia (FJ).<br>
        <br>
        Tamb&eacute; s'inclou en aquest apartat, l'opci&oacute; &quot;Data bloqueig&quot;. Aquesta serveix per que el tutor del grup indiqui una data fins la qual, els professors ja no podran introduir o modificar incid&egrave;ncies dels alumnes d'aquell grup, encara que en tinguin els privilegis adequats. Aquesta funcionalitat es per que, quan el tutor hagi fet les cartes dels informes peri&ograve;dics d'incid&egrave;ncia fins una determinada data (des de l'apartat &quot;Incid&egrave;ncies/Informe peri&ograve;dic&quot;) i les hagi enviat als pares, indicant dates i hores de cada incid&egrave;ncia, cap altre professor pugui modificar l'estat d'incid&egrave;ncies que s'ha comunicat als pares. Si un professor ha de modificar i/o afegir alguna incid&egrave;ncia en dates pr&egrave;vies a la data de bloqueig, seria necessari comunicar-ho al tutor corresponent.<br>
        <br>
        <span class=\"est3\">&middot; 5.3. Incid&egrave;ncies per alumne.</span><br>
        Aquest apartat de consulta &eacute;s accessible per tots els professors i ens permet mostrar totes les incid&egrave;ncies i els texts explicatius registrats per a un determinat alumne entre dos dates inicial i final.<br>
        <br>
        Tamb&eacute; ens permet fer un filtratge de hores, tipus d'incid&egrave;ncia i dia de la setmana per poder analitzar les incid&egrave;ncies registrades segons l'assignatura, o l'hora, etc.<br>
        <br>
        <span class=\"est3\">&middot; 5.4 Incid&egrave;ncies per grup.</span><br>
        Aquest altre apartat de consulta tamb&eacute; &eacute;s accessible per tots els professors i ens mostra per a cada grup o subgrup el total d'incid&egrave;ncies registrades de cada tipus registrades per cada alumne entre dos dates inicial i final.<br>
        <br>
        Tamb&eacute; ens permet fer un filtratge de les hores a comptar segons el dia de la setmana i l'hora de classe.<br>
        <br>
        <span class=\"est3\">&middot; 5.5. Informes d'incid&egrave;ncia.</span><br>
        Aquest apartat &eacute;s exclusiu per a cada professor i el seu contingut solament &eacute;s visible pel professor que ha iniciat la sessi&oacute; i pel tutor de l'alumne a qui correspongui l'informe d'incid&egrave;ncia corresponent.<br>
        <br>
        En ell s'hi troba la relaci&oacute; dels Informes d'incid&egrave;ncia creats pel professor (creats des d'aquest mateix apartat o des de l'apartat &quot;Incid&egrave;ncies/Registre d'Incid&egrave;ncies&quot; &quot;I&quot;) referents a un determinat alumne.<br>
        <br>
        El professor pot crear nous informes, modificar-los, esborrar-los o imprimir-los. Val a dir que un determinat informe, associat a un alumne, tamb&eacute; ser&agrave; visible al tutor de l'alumne des de l'apartat &quot;Tutoria/Registre d'Incid&egrave;ncies/Informes d'incid&egrave;ncia&quot;.<br>
        <br>
        <span class=\"est3\">&middot; 5.6. Informe peri&ograve;dic.</span><br> 
    Aquest apartat solament &eacute;s accessible pels Administradors i pels tutors en els seus grups de tutoria.<br>
    <br>
    De forma peri&ograve;dica, els tutors poden generar cartes personalitzades per a cada alumne on s'indica el nombre i tipus d'incid&egrave;ncia registrats entre dos dates determinades, les cartes ja surten preparades per ser enviades als pares dels alumnes.<br>
    <br>
    Si algun alumne no t&eacute; faltes d'assist&egrave;ncia ni retards en aquell peri&ograve;de de temps, no es genera la carta. <br>
    <br>
    <span class=\"est3\">&middot; 5.7. Apercebiments.</span><br>
    L'apartat Apercebiments solament &eacute;s accessible pels professors Administradors. En aquest apartat es poden generar els informes d'apercebiment per als alumnes que han realitzat un nombre elevat de faltes d'assist&egrave;ncia, retards o expulsions.<br>
    <br>
    Inicialment, es mostra per cada alumne el nombre de incid&egrave;ncies de cada tipus realitzat entre dues dates, i es permet fer un filtratge dels alumnes que han realitzat una quantitat superior a un nombre determinat de faltes, retards o expulsions.<br>
    <br>
    A la columna de la dreta ens informa del nombre d'apercebiments enviats pr&egrave;viament i tenim l'opci&oacute; de generar un impr&eacute;s on s'informa de l'apercebiment corresponent, si es decideix informar a la familia d'aquest apercebiment, es pot incrementar el nombre d'apercebiments enviats clicant a sobre de &quot;+1&quot;, si es decideix decrementar el nombre d'apercebiments es fa clicant a sobre de &quot;-1&quot;. <br> 
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">6.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Opcions. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el men&uacute; Opcions tenim dos apartats: Configuraci&oacute; i El meu compte.<br>
        <br>
        L'apartat Configuraci&oacute; ens porta a un altre submen&uacute; on tenim totes les opcions de configuraci&oacute; de l'aplicatiu i solament &eacute;s accessible pels usuaris administradors.<br>
        <br>
        A l'apartat &quot;El meu compte&quot; es permet modificar algunes dades pr&ograve;pies de l'usuari identificat a l'aplicatiu.<br>
        <br>
        <span class=\"est3\">&middot; 6.1. El meu compte.</span><br>

        Tenim l'opci&oacute; de canviar la contrasenya d'acc&eacute;s a l'aplicatiu i l'opci&oacute; de introduir un n&uacute;mero de tel&egrave;fon m&ograve;bil on podem rebre missatges SMS enviats des de l'apartat &quot;Tutoria/Comunicaci&oacute;/SMS&quot;. Si no indiquem cap tel&egrave;fon m&ograve;bil no rebrem cap missatge SMS.<br>
        <br>
        Tamb&eacute; tenim la possibilitat de canviar el nom real que apareix en els diferents documents de l'Aplicatiu Tutoria.<br>
        <br>
        </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">7.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Ajuda. </td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el men&uacute; Ajuda hi tenim les opcions de visualitzaci&oacute; d'aquest Manual de l'aplicaci&oacute; i els Cr&egrave;dits.<br>
        <br>
        El Manual de l'aplicaci&oacute; es mostra de forma diferent segons l'usuari sigui Administrador, professor tutor, professor o pare.<br> 
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>
</table>
");

else if ($esPare) print("
<table cellSpacing=4 cellPadding=2 width=614 border=0>
  <tr>
    <td colspan='2' align='center'>$cop<br>Manual d'usuari-pares</td>
  </tr>
  <tr>
    <td width=82 valign='top' class=\"est2\">INDEX:</td>
    <td width=512 valign='top'>
    	1.- Funcionalitat bàsica.<br>		
		2.- Menú Arxiu.<br>
		3.- Menú Tutoria. Comunicaci&oacute;.<br>
		4.- Menú Incidències. Registre d'incid&egrave;ncies.<br>
		5.- Men&uacute; Opcions. El meu compte.<br>
		6.- Men&uacute; Ajuda.<br>
    </td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">1.</div></td>
    <td valign='top' class=\"est2\">Funcionalitat bàsica.</td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>
    	<div align=\"justify\">L'Aplicatiu Tutoria es un programa Web per facilitar la interacci&oacute; entre el centre educatiu, el tutor corresponent i els pares dels alumnes, subministrant un cam&iacute; de comunicaci&oacute; i informaci&oacute; personalitzada referent a l'alumne.<br>
    	<br>
    	Per aix&ograve;, es disposa d'un entorn de comunicaci&oacute; directa amb el tutor i informaci&oacute; sobre el registre d'incid&egrave;ncies.<br> 
   	  </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">2.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Arxiu. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el menu Arxiu hi tenim dos opcions:<br>
        <br>
&middot; Tancar sessi&oacute;.<br>
Aquesta opci&oacute; tanca la sessi&oacute; actual i permet introdu&iuml;r una nova sessi&oacute; amb un altre identificador i contrasenya.<br>      
<br>
&middot; Sortir<br>
Aquesta opci&oacute; tanca l'Aplicatiu Tutoria. <br>
    </div></td>
  </tr>
    <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">3.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Tutoria. comunicaci&oacute; </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En aquest men&uacute; hi tenim l'opci&oacute; de comunicaci&oacute; amb el tutor. <br>
        <br>
    Aquest apartat serveix com a element de comunicaci&oacute; interna de l'Aplicatiu Tutoria. No es un gestor de correu electr&ograve;nic, per&ograve; t&eacute; un funcionament similar, solament &eacute;s permet enviar missatges que ser&agrave;n rebuts pel tutor i respondre al remitent de qualsevol altre missatge rebut. Per tant, aquest apartat &eacute;s una missatgeria interna. <br>
    <br>
    Un cop hem entrat en l'apartat Comunicaci&oacute;, disposem d'una part superior i una part inferior, en la superior hi veurem la llista de missatges enviats i rebuts i clicant sobre l'Assumpte, ens mostrar&agrave; el seu contingut en la part inferior. Els missatges amb una icona d'un clip al seu costat vol dir que s&oacute;n missatges amb fitxers adjunts, els missatges amb una banderola vermella, vol dir que s&oacute;n nous i no han estat vistos, de moment, i els missatges amb una banderola verda vol dir que han estat marcats com a pendents de resposta, aquesta darrera opci&oacute; &eacute;s un recordatori per a l'usuari. <br>
    <br>
    En principi disposem de dos carpetes de missatges: General i Paperera. A la carpeta General &eacute;s on hi trobem els missatges enviats i rebuts per defecte i a la Paperera, aquells missatges que han estat esborrats. Podem crear tantes noves carpetes personalitzades com vulguem per classificar els missatges clicant a sobre de la icona &quot;carpeta&quot; que hi ha a la part superior de la finestra, la nova carpeta personalitzada ser&agrave; accessible des del desplegable &quot;Carpetes&quot;. Quan visualitzem un missatge a la part inferior, tindrem l'opci&oacute; de moure'l a la carpeta personalitzada que desitgem per classificar-lo.<br>
    <br>
    Per crear un nou missatge ho fem clicant a sobre de l'enlla&ccedil; superior &quot;Nou missatge&quot;. En la part inferior ens apareix els camps de formulari per introduir l'Assumpte i el Contingut. Tamb&eacute; podem clicar a sobre de &quot;Afegir adjunt&quot; per adjuntar-hi altres fitxers.<br>
    <br>
    La selecci&oacute; dels destinat&agrave;ris del missatge es fa clicant a sobre de &quot;Destinataris&quot;, seguidament ens apareix una nova finestra on a la columna de la dreta hi tindrem com a &uacute;nic destinat&agrave;ri, el tutor. Elegirem el destinatari clicant a sobre d'ell, aix&ograve; fa que aquest s'afegeixi a la columna de &quot;Seleccionats&quot; a la part esquerra de la finestra. Finalment, clicant a sobre de &quot;Actualitzar&quot; introduirem el destinat&agrave;ri al missatge que estem creant i ja podrem clicar sobre la icona &quot;Enviar&quot; per fer l'enviament efectiu.<br>
    <br>
    El missatges que s'estiguin visualitzan (que no hagin estat escrits per nosaltres) es poden respondre al seu remitent clicant sobre la icona &quot;Respon&quot;, aquest cas es similar a la creaci&oacute; d'un nou missatge, per&ograve;, ja apareix automaticament el nom del destinatari, l'assumpte i el text original.<br>
    <br>
    Quan visualitzem el contingut d'un missatge, se'ns mostra la icona &quot;Pendent&quot;, &quot;Mou a carpeta&quot;, &quot;Esborra&quot;, &quot;Historial&quot; (solament en cas de ser nosaltres els remitents) i &quot;Respon&quot; (solament en el cas que nosaltres no siguem els remitents).<br>
    <br>
    La icona &quot;Pendent&quot; serveix per a marcar un missatge com a pendent de resposta, posant una banderola verda al seu costat, es a dir, com a un recordatori, la propera vegada que visualitzem aquest missatge, la banderola verda es torna a desactivar.<br>
    La icona &quot;Mou a carpeta&quot; ens permet moure el missatge a altres carpetes personalitazades creades per nosaltres per classificar els missatges.<br>
    La icona &quot;Esborra&quot; mou el missatge a la Paperera. Des de la carpeta &quot;Paperera&quot; podem tornar a accedir al missatge per recuperar-lo o per eliminar-lo definitivament.<br>
    La icona &quot;Historial&quot; apareix si nosaltres som els remitents del missatge i ens mostra una finestra on ens diu si el destinat&agrave;ri ha vist el missatge i la seva data i hora.<br>
    <br>
</div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">4.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Incid&egrave;ncies. Registre d'incid&egrave;ncies. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En aquest apartat del men&uacute; accedirem a la visualitzaci&oacute; de les incid&egrave;ncies que s'han registrat sobre un determinat alumne. Per defecte, es mostren totes les incid&egrave;ncies registrades des de l'inici de curs fins la data actual. Si es desitja un altre rang de dates, es pot fer clicant a sobre de la data i seleccionant la data en el calendari que ens apareix.<br>
      <br>
      Per cada incid&egrave;ncia ens mostra la data i hora, el tipus d'incid&egrave;ncia (falta d'assist&egrave;ncia F, retard R, expulsi&oacute; E, o anotaci&oacute; A, i si s'escau, tamb&eacute; es mostra un text explicatiu introduit pel corresponent professor.<br>
      <br>
      Tamb&eacute; hi podem veure altres sigles com RJ i FJ, la primera indica que es tracta d'un retard, per&ograve;, aquest ha estat justificat i la segona indica el mateix referent a una falta d'assist&egrave;ncia.<br>
      <br>
      A la cap&ccedil;alera de la p&agrave;gina hi tenim un resum de les incid&egrave;ncies registrades per cadascun dels tipus indicats pr&egrave;viament.<br>
      <br>
      Podem efectuar filtratges segons l'hora, dia de la setmana i tipus d'incid&egrave;ncia. <br>
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">5.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Opcions. el meu compte. </td>
  </tr>  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En aquest apartat es troba un resum d'algunes dades administratives sobre l'alumne, com ara el nom dels pares, l'adre&ccedil;a i el tel&egrave;fon. Tamb&eacute; hi tenim la fotografia de l'alumne que s'utilitza en la documentaci&oacute; interna del centre. Si alguna dada &eacute;s incorrecta, ho pot fer saber al tutor mitjan&ccedil;ant l'apartat de comunicaci&oacute;.<br>
        <br>
        Podem canviar, si ho desitgem la contrasenya d'acc&eacute;s a l'aplicatiu. Per aix&ograve;, hem de introdu&iuml;r la contrasenya vella i la nova contrasenya dos vegades, tenint en compte l'estructura que ha de tenir la nova contrasenya segons ens indica el programa. Un cop fet aix&ograve;, hem de clicar sobre &quot;Canviar&quot;.<br>
        <br>
        L'opci&oacute; de introduir un tel&egrave;fon m&ograve;bil per a rebre SMS serveix per si es desitja rebre aquest tipus de comunicaci&oacute;, que li podr&agrave; enviar el seu tutor quan es tracti d'informar-lo de forma r&agrave;pida sobre alguna eventualitat. Si no desitja rebre missatges SMS ha de deixar en blanc aquest apartat. <br>
        <br>
        <br>
        </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>  
  <tr>
    <td valign='top' class=\"est1\"><div align=\"right\">6.</div></td>
    <td valign='top' class=\"est2\">Men&uacute; Ajuda. </td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'><div align=\"justify\">En el men&uacute; Ajuda hi tenim les opcions de visualitzaci&oacute; d'aquest Manual de l'aplicaci&oacute; i els Cr&egrave;dits.<br>
        <br> 
    </div></td>
  </tr>
  <tr>
    <td valign='top'>&nbsp;</td>
    <td valign='top'>&nbsp;</td>
  </tr>
</table>
");

?>

</body>
</html>