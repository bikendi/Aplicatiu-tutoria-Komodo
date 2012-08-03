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
//echo '<link rel="stylesheet" type="text/css" href="css/comu.css" />';

// Paths
$lib = "lib/";
$js = "js/";

foreach( $_GET as $clau => $valor) {
//   echo "clau: $clau , valor: $valor \n";
  $valor=((!get_magic_quotes_gpc())?addslashes($valor):strtr($valor,array('\"'=>'"')));
  if(!isset($$clau)) eval("\$$clau='$valor';");
}
foreach( $_POST as $clau => $valor) {
//   echo "clau: $clau , valor: $valor \n";
  $valor=((!get_magic_quotes_gpc())?addslashes($valor):strtr($valor,array('\"'=>'"')));
  if(!isset($$clau)) eval("\$$clau='$valor';");
}
foreach( $_FILES as $clau => $valor) {
	
	$nom_tmp=$_FILES[$clau]['tmp_name'];
	$tipus=$_FILES[$clau]['type'];
	$nom=((!get_magic_quotes_gpc())?addslashes($_FILES[$clau]['name']):strtr($_FILES[$clau]['name'],array('\"'=>'"')));
	$mida=$_FILES[$clau]['size'];
	
	if(!isset($$clau)) eval("\$$clau='$nom_tmp';");
	if(!isset(${$clau."_type"})) eval("\$$clau"."_type='$tipus';");
	if(!isset(${$clau."_name"})) eval("\$$clau"."_name='$nom';");
	if(!isset(${$clau."_size"})) eval("\$$clau"."_size='$mida';");
		
}

// $quantitatget=count($_GET);
// for ($a=0; $a<$quantitatget; ++$a) {
// 	$clauget=key($_GET);
// 	$valorget=((!get_magic_quotes_gpc())?addslashes(current($_GET)):strtr(current($_GET),array('\"'=>'"')));
// 	$valorget=current($_GET);
// 	next($_GET);
// 	if(!isset($$clauget)) eval("\$$clauget='$valorget';");
// }
// $quantitatpost=count($_POST);
// for ($a=0; $a<$quantitatpost; ++$a) {
// 	$claupost=key($_POST);
// 	$valorpost=((!get_magic_quotes_gpc())?addslashes(current($_POST)):strtr(current($_POST),array('\"'=>'"')));
// 	next($_POST);
// 	if(!isset($$claupost)) eval("\$$claupost='$valorpost';");	
// }
// $quantitatfiles=count($_FILES);
// for ($a=0; $a<$quantitatfiles; ++$a) {
// 	$claufiles=key($_FILES);
// 	$valorfiles=current($_FILES);
// 	next($_FILES);
// 	
// 	$nom_tmp=$_FILES[$claufiles]['tmp_name'];
// 	$tipus=$_FILES[$claufiles]['type'];
// 	$nom=((!get_magic_quotes_gpc())?addslashes($_FILES[$claufiles]['name']):strtr($_FILES[$claufiles]['name'],array('\"'=>'"')));
// 	$mida=$_FILES[$claufiles]['size'];
// 	
// 	if(!isset($$claufiles)) eval("\$$claufiles='$nom_tmp';");
// 	if(!isset(${$claufiles."_type"})) eval("\$$claufiles"."_type='$tipus';");
// 	if(!isset(${$claufiles."_name"})) eval("\$$claufiles"."_name='$nom';");
// 	if(!isset(${$claufiles."_size"})) eval("\$$claufiles"."_size='$mida';");
// 		
// }
$PHP_SELF=$_SERVER['PHP_SELF'];
//Per al php v5:
$HTTP_POST_VARS=$_POST;
$HTTP_SERVER_VARS=$_SERVER;

$consulta="DELETE from $bdtutoria.$tbl_prefix"."sessions WHERE horainici<".(time()-$temps_max_sessio);
mysql_query($consulta, $connect);
$acces=false;
if (isset($idsess)) {
  $consulta="SELECT ref_usuari, ipremota, nomreal, privilegis FROM $bdtutoria.$tbl_prefix"."sessions WHERE idsess='$idsess' LIMIT 1";
  //echo "<p>Consulta: $consulta</p>";
  $conjunt_resultant=mysql_query($consulta, $connect);
  if (mysql_num_rows($conjunt_resultant)!=0) {
    $fila=mysql_fetch_row($conjunt_resultant);
    if ($fila[1]==$_SERVER[REMOTE_ADDR]) {
      $sess_user=$fila[0];
      $sess_nomreal=$fila[2];
      $sess_privilegis=$fila[3];
      $consulta="UPDATE $bdtutoria.$tbl_prefix"."sessions SET horainici='".time()."' WHERE idsess='$idsess' LIMIT 1";
      mysql_query($consulta, $connect);
      $acces=true;
    }
  }
  mysql_free_result($conjunt_resultant); 
}
if( !$acces && substr($PHP_SELF,-10) != 'index2.php' && substr($PHP_SELF,-13) != 'index_pda.php' ) {
  if(isset($pda)) print("
    <script language='JavaScript'>
       top.location.href='index_pda.php?sesscad=';
    </script>
  ");
  else print("
    <script language='JavaScript'>
       top.location.href='index2.php?nologo=&sesscad=';
    </script>
  ");
  exit;
}

$localitzacio = "ca";

$ref_incidencia= "F,R,E,A";
$ref_incidenciaj= "F,FJ,R,RJ,E,A";
$ref_incidencia_text= "Faltes, Retards, Expulsions, Anotacions";
$ref_incidencia_textj= "Faltes, Faltes Justificades, Retards, Retards Justificats, Expulsions, Anotacions";

// $cop = "Aplicatiu Tutoria Komodo v0.1<br>&copy; Artur Guillamet (2002-2007)<br>aguillam@xtec.net<br>&copy; Bingen Eguzkitza (2012)<br>beguzkit@xtec.cat";
$cop2 = "Aplicatiu Tutoria Komodo v0.1<br>&copy; Artur Guillamet (2002-2007)<br>aguillam@xtec.net<br>&copy; Bingen Eguzkitza (2012)<br>beguzkit@xtec.cat";

$tipus_apercebiments = Array('Ll', 'F', 'R', 'DT', 'CC', 'REC');

$consulta="SELECT id FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
$conjunt_resultant=mysql_query($consulta, $connect);
if (0==mysql_num_rows($conjunt_resultant)) {
	mysql_free_result($conjunt_resultant);
	$consulta="INSERT INTO $bdtutoria.$tbl_prefix"."parametres SET nomcentre='Actualitza: Opcions/Configuracio/Parametres!'";
	mysql_query($consulta, $connect);	
}
// $consulta="SELECT id, nomcentre, adrecacentre, cpcentre, poblaciocentre, telfcentre, director, nomdirector, sexdirector, cursacademic, datainicicurs, webcentre, emailcentre, capdes, nomcapdes, sexcapdes, coordbtx, nomcoordbtx, sexcoordbtx, nom_cc_alumne, sex_cc_alumne, nom_cc_profe, sex_cc_profe, nom_cc_pare, sex_cc_pare, sms_auto FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";  eval(base64_decode("JGNvcD0nQXBsaWNhdGl1IFR1dG9yaWEgdjEuMDxicj4mY29weTsgQXJ0dXIgR3VpbGxhbWV0ICgyMDAyLTIwMDcpPGJyPmFndWlsbGFtQHh0ZWMubmV0Jzs="));
$consulta="SELECT * FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";  eval(base64_decode("JGNvcD0nQXBsaWNhdGl1IFR1dG9yaWEgdjEuMDxicj4mY29weTsgQXJ0dXIgR3VpbGxhbWV0ICgyMDAyLTIwMDcpPGJyPmFndWlsbGFtQHh0ZWMubmV0Jzs="));
$conjunt_resultant=mysql_query($consulta, $connect);
$fila=mysql_fetch_object($conjunt_resultant);
// print_r($fila);
$nomcentre=$fila->nomcentre;
$adrecacentre=$fila->adrecacentre;
$CPcentre=$fila->cpcentre;
$poblaciocentre=$fila->poblaciocentre;
$telfcentre=$fila->telfcentre;
$director=$fila->director;
$nomdirector=$fila->nomdirector;
$sexdirector=$fila->sexdirector; 
$cursacademic=$fila->cursacademic;
$datatimestampIniciCurs=$fila->datainicicurs;
$datatimestampInici2T=$fila->datainici2T;
$datatimestampInici3T=$fila->datainici3T;
$webcentre=$fila->webcentre;
$emailcentre=$fila->emailcentre;
$capdes=$fila->capdes;
$nomcapdes=$fila->nomcapdes;
$sexcapdes=$fila->sexcapdes; 
$coordbtx=$fila->coordbtx;
$nomcoordbtx=$fila->nomcoordbtx;
$sexcoordbtx=$fila->sexcoordbtx; 
$nom_cc_alumne=$fila->nom_cc_alumne;
$sex_cc_alumne=$fila->sex_cc_alumne; 
$nom_cc_profe=$fila->nom_cc_profe;
$sex_cc_profe=$fila->sex_cc_profe; 
$nom_cc_pare=$fila->nom_cc_pare;
$sex_cc_pare=$fila->sex_cc_pare; 
$sms_auto=$fila->sms_auto;
$retards_ESO = $fila->retards_ESO;
$reset_ESO = $fila->reset_ESO;
$retards_BTX = $fila->retards_BTX;
$reset_BTX = $fila->reset_BTX;
mysql_free_result($conjunt_resultant);
if(file_exists("$dirfitxers/logocentre.jpg")) $logocentre = "$dirfitxers/logocentre.jpg";
else $logocentre = "./imatges/logocentre0.jpg";


/* bingen */
$consulta="SELECT  DISTINCT pla_estudi FROM $bdalumnes.$tbl_prefix"."Estudiants ORDER  BY pla_estudi desc";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) {
  $llista_nivells[]=$fila[0];
}
mysql_free_result($conjunt_resultant);
/* bingen */
if( !empty($nivell) && $nivell != 'Tots' )
	$where_nivell = " AND pla_estudi = '$nivell'";
$consulta="SELECT  DISTINCT concat( curs,  ' ', grup,  ' ', pla_estudi ) FROM $bdalumnes.$tbl_prefix"."Estudiants WHERE 1 $where_nivell ORDER  BY pla_estudi desc, curs, grup";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) {
  $llista_grups[]=$fila[0];
}
mysql_free_result($conjunt_resultant);

if( !empty($grup) && $grup != 'Tots' ) { 
	$gru = preg_split('/ /', $grup);
	$where_grup = " AND (curs='$gru[0]' AND grup='$gru[1]' AND pla_estudi='$gru[2]')";
}


$consulta="SELECT  ref_subgrup, nom FROM $bdtutoria.$tbl_prefix"."subgrups ORDER  BY nom, ref_subgrup";
$conjunt_resultant=mysql_query($consulta, $connect);
while($fila=mysql_fetch_row($conjunt_resultant)) {
  $llista_subgrups[]="$fila[0] $fila[1]";
}
mysql_free_result($conjunt_resultant);

 if(isset($data)) {
   if($data=="Avui") {
     $datatimestamp=mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1);
   }
   else {
     $dat=preg_split('/ /', $data);
     $da=preg_split('/-/', $dat[1]);
     $datatimestamp=mktime(date('H'),date('i'),date('s'),$da[1],$da[0],$da[2],-1);
   }
  } else {
   $datatimestamp=mktime(date('H'),date('i'),date('s'),date('n'),date('j'),date('Y'),-1);
  }
 $nomDiaSem = array ("Dg","Dl","Dm","Dc","Dj","Dv","Ds");
 $nomDiaSemLlarg = array ("Diumenge","Dilluns","Dimarts","Dimecres","Dijous","Divendres","Dissabte");
 $nomMes = array ("Gener", "Febrer", "Mar&ccedil;", "Abril", "Maig", "Juny", "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre");
 
function privilegis($diasem, $hora, $grupsubgrup) {
  	global $sess_privilegis, $bdtutoria, $tbl_prefix, $sess_user, $connect;
  	//echo "<p>Privlegis sessió: $sess_privilegis</p>";
  	$permis=false;
  	$grupsubgrup=rawurlencode(rawurldecode(stripslashes($grupsubgrup)));
  	if(($diasem=='Y')&&($hora=='Y')) {  
		$consulta="SELECT diasem, hora, grup FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE idprof='$sess_user' and diasem='-' and hora='-'";
	  	$conjunt_resultant=mysql_query($consulta, $connect);
	  	$grupsambguions="";
	  	while($fila=mysql_fetch_row($conjunt_resultant)) {
      	$grupsambguions.=(($grupsambguions!="")?"\n":"")."$fila[0] $fila[1] $fila[2]";
     	}
	  	$sess_priv=$sess_privilegis.(($sess_privilegis!="")?"\n":"").$grupsambguions; 
	  	if(strstr($sess_priv,$grupsubgrup)) $permis=true;
  	} // ($diasem=='Y')&&($hora=='Y')
	if(($diasem=='X')&&($hora=='X')) {
   	if(strstr($sess_privilegis,$grupsubgrup)) $permis=true;
  	}
  	if(strstr($sess_privilegis,"$diasem $hora $grupsubgrup")) $permis=true;
  	if($grupsubgrup!='Tutor') $grupsubgrup="Tutor $grupsubgrup";
  	if(strstr($sess_privilegis,"$grupsubgrup")) $permis=true;
  	if(strstr($sess_privilegis,"$diasem $hora Gu%E0rdia")) $permis=true;
  	if(strstr($sess_privilegis,'Administrador')) $permis=true;     
	if(($diasem=='sms')&&($hora=='sms')) {
   	if(strstr($sess_privilegis,'sms')) $permis=true;
  	}
  	return $permis;
}

 function cercaTutor($grup) {
  if ($grup=='') return '';
  global $connect, $bdtutoria, $bdusuaris, $tbl_prefix;
  $consulta1="SELECT idprof FROM $bdtutoria.$tbl_prefix"."horariprofs WHERE grup like 'tutor_%' and grup like '%".rawurlencode(stripslashes($grup))."%' ";
  $conjunt_resultant1=mysql_query($consulta1, $connect);
  $nomtuts='';
  while($fila1=mysql_fetch_row($conjunt_resultant1)) {
      $consulta2="SELECT nomreal FROM $bdusuaris.$tbl_prefix"."usu_profes WHERE usuari='$fila1[0]' ORDER BY nomreal LIMIT 1";
      $conjunt_resultant2=mysql_query($consulta2, $connect);
      $nomreal=mysql_result($conjunt_resultant2, 0,0);
      if($nomtuts!='') $nomtuts.=' / ';
      $nomtuts.= $nomreal;
      mysql_free_result($conjunt_resultant2);    
  }
  mysql_free_result($conjunt_resultant1);
  return $nomtuts;
 }
 
 function iconafitxer($nomfitxer) {
	$nomext=explode(".", $nomfitxer);
	$extensio=$nomext[count($nomext)-1];
	$imatge="./imatges/generic.gif";
	if($extensio=='doc') $imatge="./imatges/doc.gif";
	if($extensio=='bmp'||$extensio=='jpg'||$extensio=='gif'||$extensio=='png') $imatge="./imatges/img.gif";
	if($extensio=='pdf') $imatge="./imatges/pdf.gif";
	if($extensio=='zip') $imatge="./imatges/zip.gif";
	if($extensio=='txt'||$xtensio=='sxw') $imatge="./imatges/txt.gif";
	if($extensio=='htm'||$extensio=='html') $imatge="./imatges/html.gif";
	if($extensio=='xls'||$extensio=='sxc') $imatge="./imatges/calc.gif";
	if($extensio=='ppt'||$extensio=='sxi') $imatge="./imatges/pres.gif";
	if($extensio=='wav'||$extensio=='mp3') $imatge="./imatges/mus.gif";
	if($extensio=='avi'||$extensio=='mpeg') $imatge="./imatges/movie.gif";	
	return $imatge;	 
 } 
 function panyacces($clau) {
   global $sess_privilegis, $pda;
	
	if(preg_match("/Administrador/",$sess_privilegis))
		return true;    
   //echo "<p>privilegis: $sess_privilegis </p> \n";
   //echo "<p>clau: $clau </p> \n";
// 	if(!ereg($clau,$sess_privilegis)) {
	if(!preg_match('/'. $clau .'/',$sess_privilegis)) {
  		if(isset($pda)) print("
    		<script language='JavaScript'>
       		top.location.href='index_pda.php';
    		</script>
  		");
  		else print("
    		<script language='JavaScript'>
       		top.location.href='index2.php?nologo=';
    		</script>
  		");
  		exit;
	}
 }
 function comprovaNoVistRecurs($recurs) {
	global $sess_user, $bdtutoria, $tbl_prefix, $connect;
	$noVist=false;
	$consulta="SELECT tipus FROM $bdtutoria.$tbl_prefix"."grupsrecurs WHERE id='$recurs' LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	$tipus=$fila[0];
	if($tipus=="forum") {
		$consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."grupsforums WHERE idrecurs='$recurs' and creat_per!='$sess_user' and vist_per not like '%$sess_user|%' LIMIT 1";
		$conjunt_resultant=mysql_query($consulta, $connect);
		if(0!=mysql_result($conjunt_resultant,0,0)) $noVist=true;
	}
	else if($tipus=="fitxers") {
		$consulta="SELECT count(*) FROM $bdtutoria.$tbl_prefix"."grupsfitxers WHERE idrecurs='$recurs' and creat_per!='$sess_user'  and vist_per not like '%$sess_user|%' LIMIT 1";
		$conjunt_resultant=mysql_query($consulta, $connect);
		if(mysql_result($conjunt_resultant,0,0)!=0) $noVist=true;		
	}
	return $noVist;
}

function comprovaNoVistUsuari() {
	global $sess_user, $bdtutoria, $tbl_prefix, $connect;
	$noVist=false;
	$consulta="SELECT recursos FROM $bdtutoria.$tbl_prefix"."grupsentorns WHERE usuarigestor='$sess_user' or usuarismembres like '%$sess_user|%'";
	$conjunt_resultant=mysql_query($consulta, $connect);
	while($fila=mysql_fetch_row($conjunt_resultant)) {
		$recursos=explode(";", $fila[0]);
		for($a=0; $a<count($recursos); ++$a) {
			$noVist=comprovaNoVistRecurs($recursos[$a]);
			if($noVist) break;
		}
		if($noVist) break;	
	}
	return $noVist;	
}

 function ajudaContextual($ref, $tipus) {
	$text[0]="(Nom Avaluació) Indica el nom extens de l´avaluació. Màx. 50 caracters.";
	$text[1]="(Nº Items) Nombre d´items a avaluar, el darrer item, sempre representa la nota global més important. Si modifiques aquest valor quan ja han estat posades notes d´aquesta avaluació, les dades introduïdes poden ser inconsistents i es necessari tornar-les a revisar. La modificació d´aquest valor reinicia els noms dels items als valors per defecte.";
	$text[2]="(Nom items) Parelles `sigla->nom´, el nombre de parelles ha de coincidir amb nº d´items o ser nul. Les sigles i el seu nom s´utilitzen per a indicar el item avaluable en les columnes d´items d´introducció de qualificacions i en els butlletins de notes en format web visible pels pares. Clica a sobre de la sigla o nom per a modificar-ho o borrar-ho. Clica sobre Elimina items o Afegeix items per eliminar-los tots o afegir-los amb els valors per defecte.";
	$text[3]="(Modificable) Si o no. Indica si es possible introduïr i/o modificar qualificacions de les assignatures-crèdits d´aquesta avaluació per part dels professors que hi tinguin privilegis. Si es posa no, les qualificacions introduïdes solament són de lectura.";
	$text[4]="(Visible pares) Indica si els pares poden visualitzar les dades introduïdes en aquesta avaluació com a butlleti d´avaluació des de l´entorn d´accés per pares.";
	$text[5]="(Valors) Relació de valors numèrics o textuals vàlids per aquesta avaluació. Màxim 25 valors. No es pot usar la z o caracters especials com a valors. Els valors: 1, 2, 3, 4 i I, es consideren com a valors suspesos en les estadístiques i apareixen en vermell en els resums d´avaluació i butlletins.";
	$text[6]="(Data avaluació) Indica la data en que s´efectua l´avaluació. És la data que apareix en els butlletins d´avaluació i en el resum d´avaluació.";
	$text[7]="(Cursos) Relació de cursos o (Tots) als quals s´aplica aquesta avaluació. Utilitza Shift-Clic i/o Cntrl-Clic per fer seleccions múltiples.";
	$text[8]="(Grups) Relació de grups o (Tots) als quals s´aplica aquesta avaluació. Utilitza Shift-Clic i/o Cntrl-Clic per fer seleccions múltiples.";
	$text[9]="(Pla Estudis) Relació de Plans d´estudi als quals s´aplica aquesta avaluació. Utilitza Shift-Clic i/o Cntrl-Clic per fer seleccions múltiples.";
	$text[10]="(Observacions) Text d´observacions que apareixerà en lletra petita als butlletins.";
	$text[11]="(Estat) Estat de l´avaluació, Oberta o Tancada. Indica si les dades de l´avaluació, formació del curriculum de l´alumne i permisos d´avaluació dels professors, depenen de la configuració dels Horaris-Privilegis de professors o no.";
	$text[12]="<center><b>Informaci&oacute; sobre l´avaluaci&oacute;</b></center> L´avaluaci&oacute; serveix per facilitar la introducció de qualificacions per part dels professors amb els permisos apropiats, la seva organització en estadístiques i resums per facilitar l´anàlisi de dades i la creació de butlletins individuals, en format paper i format web, per que puguin esser tramesos i visualitzats pels pares dels alumnes.<p/>".
	          "El sistema desenvolupat intenta ser el màxim configurable possible, facilitant la realització d´avaluacions convencionals o bé, altres tipus d´avaluacions especials. El sistema no comprova en cap cas, si el curriculum de l´alumnes és l´adient, o si els items d´avaluació i els seus valors estan d´acord amb la normativa o bé si el nombre d´avaluacions és l´adequat. És responsabilitat de l´administrador comprovar que aquests s´ajusten a les seves necessitats.<p/>". 
	          "Per aconseguir això es fa de la següent manera:<br/>".
	          "En l´apartat \"Avaluació/Definir assignatures\" s´ha de tenir introduïdes totes les assignatures, materies i els seus crèdits corresponents de les etapes educatives del centre. A \"Opcions/Configuració/Crea subgrups\" s´ha de tenir definits tots els subgrups d´alumnes. Posteriorment, a \"Opcions/Configuració/Horaris-privilegis\", cada professor ha de tenir associat a cada hora lectiva el grup o subgrup d´alumnes (aquest requisit ja és necessari per configurar els permisos per que pugui introduïr les Incidències). Per a cada grup o subgrup d´alumnes, ha de tenir associada l´assignatura (crèdit o matèria) adequat, si algún grup o subgrup no ha d´estar ubicat a cap hora lectiva, es pot utilitzar l´opció \"Assigns. addicionals\" per introduïr parelles \"grups d´alumnes-assignatura\".<p/>".
	          "Noteu que a \"Opcions/Configuració/Horaris-privilegis\" es defineix el següent sobre l´avaluació: 1- els privilegis de cada professor a avaluar el seu grup-subgrup d´alumnes de l´assignatura corresponent, 2- el conjunt d´assignatures, crèdits i/o matèries que ha de tenir cada alumne individualment en aquell moment d´avaluació (el seu curriculum, ja que cada grup o subgrup és un colectiu, de 0, 1 o més alumnes, que tenen associada una determinada assignatura), es pot comprovar si s'ha definit correctament les assignatures que té associades cada alumne visualitzant el butlleti que li correspon i verificant que hi té exactament totes les assignatures i/o crèdits que li corresponen en aquell moment d´avaluació. Si algun alumne o alumnes no té les seves assignatures correctament configurades, vol dir que es necessari revisar les associacions entre grups i/o subgrups i assignatura en els horaris-privilegis dels professors i si es convenient, crear tants subgrups com sigui necessari (un subgrup podria 
constar d´un sol alumne) per poder ajustar el màxim possible el curriculum de l´alumne.<p/>".
	          "Un cop fetes les configuracions anteriors, es passa a crear l´avaluació en l´apartat: \"Avaluació/Definir Avaluacions\". Per això, es clica a \"Afegir nova avaluació\" i s´indica una Referència d´avaluació que ha de ser única, es a dir, no pot coincidir amb altres referències d´avaluació, seguidament, configurem els paràmetres que definiexen l´avaluació: el nom extens d´avaluació, el nombre d´items i els seus noms i sigles, les propietats de modificable i visible per pares, els valors possibles per als items d´avaluació, la data d´avaluació, els cursos, grups i pla d´estudis a que es pot aplicar i finalment, les observacions.<p/>".
	          "Cada avaluació pot tenir dos estats possibles: Avaluació oberta i Avaluació tancada.<p/>".
	          "L´estat Avaluació oberta és el que s´obté per defecte quan es crea l´avaluació. En aquest estat, la creació del curriculum d´assignatures per alumne bé definit i és totalment dependent del que està indicat en l´apartat \"Opcions/Configuració/Horaris-privilegis\" i també en la llista d´alumnes que forma cada grup i els alumnes que estan indicats en els subgrups de \"Opcions/Configuració/Crear subgrups\", això vol dir que si es modifica alguna cosa d´aquests apartats, pot afectar a la configuració del curriculum i permisos de professor de totes les avaluacions que estiguin en l´estat de Avaluació oberta. Es important tenir en compte que mentre hi hagi avaluacions obertes, aquestes poden estar afectades per les modificacions en els apartats indicats, s´aconsella no modificar res d´aquests apartats en aquesta situació, a no ser que es desitji afectar expressament a la configuració de les avaluacions obertes.<p/>".
	          "L´estat Avaluació tancada s´aconsegueix clicant a sobre de l'opció \"**Tanca-la**\", amb això s´incorporen a l´avaluació aquelles assignatures en que el professor no ha posat cap nota i es permet independitzar les dades d´aquesta avaluació del que esta indicat en els apartats \"Opcions/Configuració/Horaris-privilegis\" i \"Opcions/Configuració/Crear subgrups\". Això permet configurar les dades d´una propera avaluació (per canvi de trimestre, quatrimestre o crèdit) en els apartats indicats sense afectar al que està introduït en avaluacions anteriors. Un cop l´avaluació està tancada ja no es pot modificar les seves propietats ni afegir o canviar valors, per tant, abans de tancar una avaluació, és necessari assegurar-se que ja està tot correctament introduït.<p/>".
	          "Podriem resumir la utilitat dels estats d´avaluació de la següent forma: suposant que la sessió d´avaluació es fa el dia X, en la setmana prèvia a aquest dia X, es configura l´avaluació tal com s´ha indicat previament i es permet als professors que introdueixin les notes, en el dia X, s´obté els resums d´avaluació i les estadístiques i es fa la sessió d´avaluació propiament dita, un cop passat el dia X, s´imprimeix els butlletins, s´activa la visibilitat per als pares, i, un cop comprovat que tots els butlletins són correctes i no hi falta cap dada, es pot fer el tancament de l´avaluació, amb el que aquesta quedarà fixada com a \"sol lectura\" i ja es podrà crear, si es desitja, la nova configuració per a la següent avaluació i/o trimestre.<p/>".
	          "Quan una o més avaluacions estan en l´estat oberta i la propietat \"Modificable\" està a \"si\", els professors poden introduïr les qualificacions segons els seus privilegis des de l´apartat \"Avaluació/Posar notes\", o bé, mitjançant una PDA, des de l´apartat \"Qualificacions\". A la vegada, els tutors i administradors poden veure les qualificacions introduïdes des de \"Avaluació/Resum avaluació\" i \"Avaluació/Butlletins alumnes\". Els pares també poden veure les qualificacions introduïdes des del seu entorn d´accés per pares si la propietat d'avaluació \"Visible pares\" està a \"si\". En l´estat d´avaluació tancada, es possible visualitzar les mateixes dades, però, en aquest cas, aquestes ja no són modificables.";
	$text[13]="<center><b>Informaci&oacute; sobre la definició de matèries-assignatures i crèdits.</b></center> En aquest apartat es defineixen les matèries i els seus crèdits, el codis corresponents, el seu tipus (crèdit comú, variable, optatiu o de modalitat) i el pla d´estudis a que correspon. També podem introduïr unes observacions sobre el crèdit que solament són usades com a comentari dins aquest apartat.<p/>".
	          "La informació que s'introdueix aqui estarà disponible per utilitzar-la per associar assignatures a grups d'alumnes dins de \"Opcions/Configuració/Horaris-privilegis\", formant d'aquesta manera, el curriculum d´assignatures que correspon a cada alumne i les assignatures que podrà avaluar cada professor. Aquesta informació també serveix per formar els butlletins d´alumnes i el resum d´avaluació, permeten la classificació segons els tipus de crèdits.<p/>".
			  "Per introduir una nova matèria cliquem a sobre de \"Afegir nova matèria\" i introduim un codi de dos o tres lletres i el nom de la matèria (el codi no pot estar repetir respecte de una altra materia). També indiquem el nombre de crèdits que té aquesta matèria, el seu tipus i el pla d´estudis que li correspon. En clicar sobre \"Desa\", es desarà aquesta matèria i es generarà automaticament tots els crèdits que la formen. Seguidament, clicant sobre \"Editar\" es pot modificar, si fa falta, individualment cadascun dels crèdits generats automàticament per aquesta matèria.<p/>".
			  "Cal tenir cura en no modificar aquestes dades si ja han estat usades en les avaluacions. La modificació dels paràmetres dels crèdits (codi, nom, tipus, pla estudis), especialment el codi, quan ja han estat ficades qualificacions als alumnes utilitzant aquestes dades, pot provocar que algunes dades de qualificacions siguin inaccessibles (en cas de canviar el codi) o bé, desordenades en els butlletins si es canvia el tipus.";                
	
	if($tipus==1) $cad="<a href='' title='Ajuda contextual' onClick='alert(\"$text[$ref]\"); return false;'><img src='imatges/ajudaContextual.gif' border='0'></a>";
	if($tipus==2) $cad="<a href='' title='Obre Ajuda contextual' onClick='ocultaMostraCapa(\"capaajuda".$ref."\",\"v\"); return false;'><img src='imatges/ajudaContextual.gif' border='0'></a><div id='capaajuda".$ref."' style='position:absolute; top:10px; left:10px; padding-top:10; padding-left:10; padding-right:10; padding-bottom:10; margin-right:10; margin-bottom:10; border-width:4; text-align:justify; border-style:ridge; border-color:#42A5A5; background-color:#FFFFCC; visibility:hidden'><a href='' title='Tanca Ajuda contextual' onClick='ocultaMostraCapa(\"capaajuda".$ref."\",\"o\"); return false;'>|X| Tancar</a><br>$text[$ref]</div>"; 
	return $cad;
 }
 
?>
