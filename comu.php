<?php
/*
    Aplicatiu Tutoria Komodo v.0.1
    Aplicaci� web per a la gesti� de la tasca tutorial.
    Copyright (C) 2002-2007  Artur Guillamet Sabat� <aguillam(a)xtec.net>
    Copyright (C) 2012 �ingen Eguzkitza <beguzkit@xtec.cat>

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
  	//echo "<p>Privlegis sessi�: $sess_privilegis</p>";
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
	$text[0]="(Nom Avaluaci�) Indica el nom extens de l�avaluaci�. M�x. 50 caracters.";
	$text[1]="(N� Items) Nombre d�items a avaluar, el darrer item, sempre representa la nota global m�s important. Si modifiques aquest valor quan ja han estat posades notes d�aquesta avaluaci�, les dades introdu�des poden ser inconsistents i es necessari tornar-les a revisar. La modificaci� d�aquest valor reinicia els noms dels items als valors per defecte.";
	$text[2]="(Nom items) Parelles `sigla->nom�, el nombre de parelles ha de coincidir amb n� d�items o ser nul. Les sigles i el seu nom s�utilitzen per a indicar el item avaluable en les columnes d�items d�introducci� de qualificacions i en els butlletins de notes en format web visible pels pares. Clica a sobre de la sigla o nom per a modificar-ho o borrar-ho. Clica sobre Elimina items o Afegeix items per eliminar-los tots o afegir-los amb els valors per defecte.";
	$text[3]="(Modificable) Si o no. Indica si es possible introdu�r i/o modificar qualificacions de les assignatures-cr�dits d�aquesta avaluaci� per part dels professors que hi tinguin privilegis. Si es posa no, les qualificacions introdu�des solament s�n de lectura.";
	$text[4]="(Visible pares) Indica si els pares poden visualitzar les dades introdu�des en aquesta avaluaci� com a butlleti d�avaluaci� des de l�entorn d�acc�s per pares.";
	$text[5]="(Valors) Relaci� de valors num�rics o textuals v�lids per aquesta avaluaci�. M�xim 25 valors. No es pot usar la z o caracters especials com a valors. Els valors: 1, 2, 3, 4 i I, es consideren com a valors suspesos en les estad�stiques i apareixen en vermell en els resums d�avaluaci� i butlletins.";
	$text[6]="(Data avaluaci�) Indica la data en que s�efectua l�avaluaci�. �s la data que apareix en els butlletins d�avaluaci� i en el resum d�avaluaci�.";
	$text[7]="(Cursos) Relaci� de cursos o (Tots) als quals s�aplica aquesta avaluaci�. Utilitza Shift-Clic i/o Cntrl-Clic per fer seleccions m�ltiples.";
	$text[8]="(Grups) Relaci� de grups o (Tots) als quals s�aplica aquesta avaluaci�. Utilitza Shift-Clic i/o Cntrl-Clic per fer seleccions m�ltiples.";
	$text[9]="(Pla Estudis) Relaci� de Plans d�estudi als quals s�aplica aquesta avaluaci�. Utilitza Shift-Clic i/o Cntrl-Clic per fer seleccions m�ltiples.";
	$text[10]="(Observacions) Text d�observacions que apareixer� en lletra petita als butlletins.";
	$text[11]="(Estat) Estat de l�avaluaci�, Oberta o Tancada. Indica si les dades de l�avaluaci�, formaci� del curriculum de l�alumne i permisos d�avaluaci� dels professors, depenen de la configuraci� dels Horaris-Privilegis de professors o no.";
	$text[12]="<center><b>Informaci&oacute; sobre l�avaluaci&oacute;</b></center> L�avaluaci&oacute; serveix per facilitar la introducci� de qualificacions per part dels professors amb els permisos apropiats, la seva organitzaci� en estad�stiques i resums per facilitar l�an�lisi de dades i la creaci� de butlletins individuals, en format paper i format web, per que puguin esser tramesos i visualitzats pels pares dels alumnes.<p/>".
	          "El sistema desenvolupat intenta ser el m�xim configurable possible, facilitant la realitzaci� d�avaluacions convencionals o b�, altres tipus d�avaluacions especials. El sistema no comprova en cap cas, si el curriculum de l�alumnes �s l�adient, o si els items d�avaluaci� i els seus valors estan d�acord amb la normativa o b� si el nombre d�avaluacions �s l�adequat. �s responsabilitat de l�administrador comprovar que aquests s�ajusten a les seves necessitats.<p/>". 
	          "Per aconseguir aix� es fa de la seg�ent manera:<br/>".
	          "En l�apartat \"Avaluaci�/Definir assignatures\" s�ha de tenir introdu�des totes les assignatures, materies i els seus cr�dits corresponents de les etapes educatives del centre. A \"Opcions/Configuraci�/Crea subgrups\" s�ha de tenir definits tots els subgrups d�alumnes. Posteriorment, a \"Opcions/Configuraci�/Horaris-privilegis\", cada professor ha de tenir associat a cada hora lectiva el grup o subgrup d�alumnes (aquest requisit ja �s necessari per configurar els permisos per que pugui introdu�r les Incid�ncies). Per a cada grup o subgrup d�alumnes, ha de tenir associada l�assignatura (cr�dit o mat�ria) adequat, si alg�n grup o subgrup no ha d�estar ubicat a cap hora lectiva, es pot utilitzar l�opci� \"Assigns. addicionals\" per introdu�r parelles \"grups d�alumnes-assignatura\".<p/>".
	          "Noteu que a \"Opcions/Configuraci�/Horaris-privilegis\" es defineix el seg�ent sobre l�avaluaci�: 1- els privilegis de cada professor a avaluar el seu grup-subgrup d�alumnes de l�assignatura corresponent, 2- el conjunt d�assignatures, cr�dits i/o mat�ries que ha de tenir cada alumne individualment en aquell moment d�avaluaci� (el seu curriculum, ja que cada grup o subgrup �s un colectiu, de 0, 1 o m�s alumnes, que tenen associada una determinada assignatura), es pot comprovar si s'ha definit correctament les assignatures que t� associades cada alumne visualitzant el butlleti que li correspon i verificant que hi t� exactament totes les assignatures i/o cr�dits que li corresponen en aquell moment d�avaluaci�. Si algun alumne o alumnes no t� les seves assignatures correctament configurades, vol dir que es necessari revisar les associacions entre grups i/o subgrups i assignatura en els horaris-privilegis dels professors i si es convenient, crear tants subgrups com sigui necessari (un subgrup podria 
constar d�un sol alumne) per poder ajustar el m�xim possible el curriculum de l�alumne.<p/>".
	          "Un cop fetes les configuracions anteriors, es passa a crear l�avaluaci� en l�apartat: \"Avaluaci�/Definir Avaluacions\". Per aix�, es clica a \"Afegir nova avaluaci�\" i s�indica una Refer�ncia d�avaluaci� que ha de ser �nica, es a dir, no pot coincidir amb altres refer�ncies d�avaluaci�, seguidament, configurem els par�metres que definiexen l�avaluaci�: el nom extens d�avaluaci�, el nombre d�items i els seus noms i sigles, les propietats de modificable i visible per pares, els valors possibles per als items d�avaluaci�, la data d�avaluaci�, els cursos, grups i pla d�estudis a que es pot aplicar i finalment, les observacions.<p/>".
	          "Cada avaluaci� pot tenir dos estats possibles: Avaluaci� oberta i Avaluaci� tancada.<p/>".
	          "L�estat Avaluaci� oberta �s el que s�obt� per defecte quan es crea l�avaluaci�. En aquest estat, la creaci� del curriculum d�assignatures per alumne b� definit i �s totalment dependent del que est� indicat en l�apartat \"Opcions/Configuraci�/Horaris-privilegis\" i tamb� en la llista d�alumnes que forma cada grup i els alumnes que estan indicats en els subgrups de \"Opcions/Configuraci�/Crear subgrups\", aix� vol dir que si es modifica alguna cosa d�aquests apartats, pot afectar a la configuraci� del curriculum i permisos de professor de totes les avaluacions que estiguin en l�estat de Avaluaci� oberta. Es important tenir en compte que mentre hi hagi avaluacions obertes, aquestes poden estar afectades per les modificacions en els apartats indicats, s�aconsella no modificar res d�aquests apartats en aquesta situaci�, a no ser que es desitji afectar expressament a la configuraci� de les avaluacions obertes.<p/>".
	          "L�estat Avaluaci� tancada s�aconsegueix clicant a sobre de l'opci� \"**Tanca-la**\", amb aix� s�incorporen a l�avaluaci� aquelles assignatures en que el professor no ha posat cap nota i es permet independitzar les dades d�aquesta avaluaci� del que esta indicat en els apartats \"Opcions/Configuraci�/Horaris-privilegis\" i \"Opcions/Configuraci�/Crear subgrups\". Aix� permet configurar les dades d�una propera avaluaci� (per canvi de trimestre, quatrimestre o cr�dit) en els apartats indicats sense afectar al que est� introdu�t en avaluacions anteriors. Un cop l�avaluaci� est� tancada ja no es pot modificar les seves propietats ni afegir o canviar valors, per tant, abans de tancar una avaluaci�, �s necessari assegurar-se que ja est� tot correctament introdu�t.<p/>".
	          "Podriem resumir la utilitat dels estats d�avaluaci� de la seg�ent forma: suposant que la sessi� d�avaluaci� es fa el dia X, en la setmana pr�via a aquest dia X, es configura l�avaluaci� tal com s�ha indicat previament i es permet als professors que introdueixin les notes, en el dia X, s�obt� els resums d�avaluaci� i les estad�stiques i es fa la sessi� d�avaluaci� propiament dita, un cop passat el dia X, s�imprimeix els butlletins, s�activa la visibilitat per als pares, i, un cop comprovat que tots els butlletins s�n correctes i no hi falta cap dada, es pot fer el tancament de l�avaluaci�, amb el que aquesta quedar� fixada com a \"sol lectura\" i ja es podr� crear, si es desitja, la nova configuraci� per a la seg�ent avaluaci� i/o trimestre.<p/>".
	          "Quan una o m�s avaluacions estan en l�estat oberta i la propietat \"Modificable\" est� a \"si\", els professors poden introdu�r les qualificacions segons els seus privilegis des de l�apartat \"Avaluaci�/Posar notes\", o b�, mitjan�ant una PDA, des de l�apartat \"Qualificacions\". A la vegada, els tutors i administradors poden veure les qualificacions introdu�des des de \"Avaluaci�/Resum avaluaci�\" i \"Avaluaci�/Butlletins alumnes\". Els pares tamb� poden veure les qualificacions introdu�des des del seu entorn d�acc�s per pares si la propietat d'avaluaci� \"Visible pares\" est� a \"si\". En l�estat d�avaluaci� tancada, es possible visualitzar les mateixes dades, per�, en aquest cas, aquestes ja no s�n modificables.";
	$text[13]="<center><b>Informaci&oacute; sobre la definici� de mat�ries-assignatures i cr�dits.</b></center> En aquest apartat es defineixen les mat�ries i els seus cr�dits, el codis corresponents, el seu tipus (cr�dit com�, variable, optatiu o de modalitat) i el pla d�estudis a que correspon. Tamb� podem introdu�r unes observacions sobre el cr�dit que solament s�n usades com a comentari dins aquest apartat.<p/>".
	          "La informaci� que s'introdueix aqui estar� disponible per utilitzar-la per associar assignatures a grups d'alumnes dins de \"Opcions/Configuraci�/Horaris-privilegis\", formant d'aquesta manera, el curriculum d�assignatures que correspon a cada alumne i les assignatures que podr� avaluar cada professor. Aquesta informaci� tamb� serveix per formar els butlletins d�alumnes i el resum d�avaluaci�, permeten la classificaci� segons els tipus de cr�dits.<p/>".
			  "Per introduir una nova mat�ria cliquem a sobre de \"Afegir nova mat�ria\" i introduim un codi de dos o tres lletres i el nom de la mat�ria (el codi no pot estar repetir respecte de una altra materia). Tamb� indiquem el nombre de cr�dits que t� aquesta mat�ria, el seu tipus i el pla d�estudis que li correspon. En clicar sobre \"Desa\", es desar� aquesta mat�ria i es generar� automaticament tots els cr�dits que la formen. Seguidament, clicant sobre \"Editar\" es pot modificar, si fa falta, individualment cadascun dels cr�dits generats autom�ticament per aquesta mat�ria.<p/>".
			  "Cal tenir cura en no modificar aquestes dades si ja han estat usades en les avaluacions. La modificaci� dels par�metres dels cr�dits (codi, nom, tipus, pla estudis), especialment el codi, quan ja han estat ficades qualificacions als alumnes utilitzant aquestes dades, pot provocar que algunes dades de qualificacions siguin inaccessibles (en cas de canviar el codi) o b�, desordenades en els butlletins si es canvia el tipus.";                
	
	if($tipus==1) $cad="<a href='' title='Ajuda contextual' onClick='alert(\"$text[$ref]\"); return false;'><img src='imatges/ajudaContextual.gif' border='0'></a>";
	if($tipus==2) $cad="<a href='' title='Obre Ajuda contextual' onClick='ocultaMostraCapa(\"capaajuda".$ref."\",\"v\"); return false;'><img src='imatges/ajudaContextual.gif' border='0'></a><div id='capaajuda".$ref."' style='position:absolute; top:10px; left:10px; padding-top:10; padding-left:10; padding-right:10; padding-bottom:10; margin-right:10; margin-bottom:10; border-width:4; text-align:justify; border-style:ridge; border-color:#42A5A5; background-color:#FFFFCC; visibility:hidden'><a href='' title='Tanca Ajuda contextual' onClick='ocultaMostraCapa(\"capaajuda".$ref."\",\"o\"); return false;'>|X| Tancar</a><br>$text[$ref]</div>"; 
	return $cad;
 }
 
?>
