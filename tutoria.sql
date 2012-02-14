#    Aplicatiu Tutoria Komodo v.0.1
#    Aplicació web per a la gestió de la tasca tutorial.
#    Copyright (C) 2002-2007  Artur Guillamet Sabaté <aguillam(a)xtec.net>
#    Copyright (C) 2012 ßingen Eguzkitza <beguzkit@xtec.cat>
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as
#    published by the Free Software Foundation, either version 3 of the
#    License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# phpMyAdmin SQL Dump
# version 2.5.7-pl1
# http://www.phpmyadmin.net
#
# Servidor: localhost
# Tiempo de generaci�n: 10-03-2007 a las 14:43:24
# Versi�n del servidor: 3.23.54
# Versi�n de PHP: 4.2.2
# 
# Base de datos : `tutoria`
# 

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_Estudiants`
#

CREATE TABLE at_11_12_Estudiants (
  numero_mat varchar(50) NOT NULL default '0',
  DNI varchar(15) NOT NULL default '',
  COGNOM_ALU varchar(30) NOT NULL default '',
  COGNOM2_AL varchar(30) default NULL,
  NOM_ALUM varchar(30) default NULL,
  SEXE varchar(4) default NULL,
  PLA_ESTUDI varchar(60) default NULL,
  CODI_ESPEC varchar(4) default NULL,
  CURS char(3) default NULL,
  GRUP char(3) default NULL,
  CODI_ITINE varchar(4) default NULL,
  ADRECA varchar(30) default NULL,
  CODI_MUNIC varchar(5) default NULL,
  NOM_MUNICI varchar(35) default NULL,
  CODI_POSTA varchar(5) default NULL,
  PRIMER_TEL text,
  COGNOM1_PA varchar(30) default NULL,
  COGNOM2_PA varchar(30) default NULL,
  NOM_PARE varchar(30) default NULL,
  COGNOM1_MA varchar(30) default NULL,
  COGNOM2_MA varchar(30) default NULL,
  NOM_MARE varchar(30) default NULL,
  CONTACTES varchar(100) default NULL,
  PRIMARY KEY  (numero_mat),
  UNIQUE KEY numero_mat (numero_mat)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_EstudiantsEsborrats`
#

CREATE TABLE at_11_12_EstudiantsEsborrats (
  numero_mat varchar(50) NOT NULL default '0',
  DNI varchar(15) NOT NULL default '',
  COGNOM_ALU varchar(30) NOT NULL default '',
  COGNOM2_AL varchar(30) default NULL,
  NOM_ALUM varchar(30) default NULL,
  SEXE varchar(4) default NULL,
  PLA_ESTUDI varchar(60) default NULL,
  CODI_ESPEC varchar(4) default NULL,
  CURS char(3) default NULL,
  GRUP char(3) default NULL,
  CODI_ITINE varchar(4) default NULL,
  ADRECA varchar(30) default NULL,
  CODI_MUNIC varchar(5) default NULL,
  NOM_MUNICI varchar(35) default NULL,
  CODI_POSTA varchar(5) default NULL,
  PRIMER_TEL text,
  COGNOM1_PA varchar(30) default NULL,
  COGNOM2_PA varchar(30) default NULL,
  NOM_PARE varchar(30) default NULL,
  COGNOM1_MA varchar(30) default NULL,
  COGNOM2_MA varchar(30) default NULL,
  NOM_MARE varchar(30) default NULL,
  PRIMARY KEY  (numero_mat),
  UNIQUE KEY numero_mat (numero_mat)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_anotacions`
#

CREATE TABLE at_11_12_anotacions (
  id int(11) NOT NULL auto_increment,
  text varchar(200) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO `at_11_12_anotacions` VALUES (1, '- No treballa a classe');
INSERT INTO `at_11_12_anotacions` VALUES (2, '- No para de parlar');
INSERT INTO `at_11_12_anotacions` VALUES (3, '- No porta els deures');
INSERT INTO `at_11_12_anotacions` VALUES (4, '- No porta el material');
INSERT INTO `at_11_12_anotacions` VALUES (5, '- Est&agrave treballant una altra assignatura');
INSERT INTO `at_11_12_anotacions` VALUES (6, '- Comportament inadequat');
INSERT INTO `at_11_12_anotacions` VALUES (7, '+ Participa');
INSERT INTO `at_11_12_anotacions` VALUES (8, '+ Ajuda als seus companys/es');
INSERT INTO `at_11_12_anotacions` VALUES (9, '+ Surt voluntari/a');
INSERT INTO `at_11_12_anotacions` VALUES (10, '+ Mostra inter&egraves');
INSERT INTO `at_11_12_anotacions` VALUES (11, '+ Pregunta els dubtes');
INSERT INTO `at_11_12_anotacions` VALUES (12, '+ Realitza els exercicis b&eacute');
-- INSERT INTO `at_11_12_anotacions` VALUES (13, '');
-- INSERT INTO `at_11_12_anotacions` VALUES (14, '');
-- INSERT INTO `at_11_12_anotacions` VALUES (15, '');

# --------------------------------------------------------
#
# Estructura de tabla para la tabla `at_11_12_apercebiments`
#

CREATE TABLE at_11_12_apercebiments (
  id int(11) NOT NULL auto_increment,
  refalum varchar(20) NOT NULL default '',
  datahora bigint(20) NOT NULL default '0',
  obsv text NOT NULL,
-- bingen
  incidencia varchar(4) default NULL,
  quantitat smallint default NUlL,
  signat boolean default false,
-- bingen
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- bingen
-- ALTER TABLE  `at_11_12_apercebiments` ADD  `incidencia` VARCHAR( 4 ) NULL, ADD  `quantitat` SMALLINT NULL, ADD signat BOOLEAN DEFAULT FALSE ;
# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_avaluacions`
#

CREATE TABLE at_11_12_avaluacions (
  id int(11) NOT NULL auto_increment,
  refaval varchar(10) NOT NULL default '',
  nomaval varchar(50) NOT NULL default '',
  nitems char(3) NOT NULL default '1',
  nomitems text NOT NULL,
  valors varchar(50) NOT NULL default '1|2|3|4|5|6|7|8|9|10',
  data bigint(20) NOT NULL default '0',
  modificable char(2) NOT NULL default 'no',
  visiblepares char(2) NOT NULL default 'no',
  curs varchar(60) NOT NULL default '',
  grup varchar(60) NOT NULL default '',
  pla_estudi varchar(60) NOT NULL default '',
  observacions text NOT NULL,
  estat varchar(25) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY refaval (refaval)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_calendari`
#

CREATE TABLE at_11_12_calendari (
  id int(11) NOT NULL auto_increment,
  data bigint(20) NOT NULL default '0',
  text text NOT NULL,
  horainici bigint(20) NOT NULL default '0',
  horafi bigint(20) NOT NULL default '0',
  periodicitat varchar(30) NOT NULL default '',
  link varchar(60) NOT NULL default '',
  avisador varchar(30) NOT NULL default '',
  colorfons varchar(8) NOT NULL default '',
  autor varchar(15) NOT NULL default '',
  lectors text NOT NULL,
  lectorsclasf text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_comunicacio`
#

CREATE TABLE at_11_12_comunicacio (
  id bigint(20) NOT NULL auto_increment,
  sub bigint(20) NOT NULL default '0',
  de varchar(100) NOT NULL default '',
  per_a text NOT NULL,
  datahora bigint(20) NOT NULL default '0',
  assumpte varchar(50) NOT NULL default '',
  contingut text NOT NULL,
  adjunts text NOT NULL,
  vist text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_comunicpers`
#

CREATE TABLE at_11_12_comunicpers (
  id int(11) NOT NULL auto_increment,
  usuari varchar(40) NOT NULL default '',
  carpetes text,
  grups text,
  PRIMARY KEY  (id,usuari),
  UNIQUE KEY usuari (usuari)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_databloqueig`
#

CREATE TABLE at_11_12_databloqueig (
  id int(11) NOT NULL auto_increment,
  grup varchar(150) NOT NULL default '',
  data bigint(20) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY grup (grup)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_entrevistes`
#

CREATE TABLE at_11_12_entrevistes (
  id int(11) NOT NULL auto_increment,
  ref_alum varchar(25) NOT NULL default '0',
  data bigint(20) NOT NULL default '0',
  titol varchar(100) NOT NULL default '',
  reunits varchar(150) default NULL,
  descripcio text,
  public boolean NOT NULL default FALSE,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_faltes`
#

CREATE TABLE at_11_12_faltes (
  id bigint(20) NOT NULL auto_increment,
  refalumne varchar(20) NOT NULL default '0',
  data bigint(20) NOT NULL default '0',
  hora varchar(4) NOT NULL default '',
  incidencia varchar(4) NOT NULL default '',
  usuari varchar(50) NOT NULL default '',
  memo text,
  PRIMARY KEY  (id),
  UNIQUE KEY refalumnedatahora (refalumne,data,hora)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_fitxers`
#

CREATE TABLE at_11_12_fitxers (
  id int(11) NOT NULL auto_increment,
  data bigint(20) NOT NULL default '0',
  ref_alum varchar(25) NOT NULL default '0',
  ref_fitxer varchar(100) NOT NULL default '',
  nom_fitxer varchar(100) NOT NULL default '',
  descripcio text NOT NULL,
  tipus_mime varchar(50) NOT NULL default '',
  tamany int(11) NOT NULL default '0',
  public boolean NOT NULL default FALSE,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_frangeshoraries`
#

CREATE TABLE at_11_12_frangeshoraries (
  id int(11) NOT NULL auto_increment,
  hora varchar(4) NOT NULL default '',
  inici int(11) NOT NULL default '0',
  fi int(11) NOT NULL default '0',
  extraescolar boolean default FALSE,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
INSERT INTO `at_11_12_frangeshoraries` VALUES (1, '1', 27900, 31200, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (2, '2', 31201, 34500, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (3, 'p1', 34501, 36300, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (4, '3', 36301, 39600, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (5, '4', 39601, 42900, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (6, 'p2', 42901, 43500, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (7, '5', 43501, 46800, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (8, '6', 46801, 50100, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (9, 'di', 50101, 51000, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (10, '7', 51001, 54300, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (11, '8', 54301, 57600, FALSE);
INSERT INTO `at_11_12_frangeshoraries` VALUES (11, '9', 57601, 60900, TRUE);
# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_guardia`
#

CREATE TABLE at_11_12_guardia (
  id bigint(20) NOT NULL auto_increment,
  incidencia varchar(4) NOT NULL default '',
  refalumne varchar(20) NOT NULL default '0',
  data bigint(20) NOT NULL default '0',
  hora varchar(4) NOT NULL default '',
  profe varchar(50) NOT NULL default '',
  memo text,
  usuari varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
--  UNIQUE KEY refalumnedatahora (refalumne,data,hora)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_grupsentorns`
#

CREATE TABLE at_11_12_grupsentorns (
  id int(11) NOT NULL auto_increment,
  nomcurtgrup varchar(20) NOT NULL default '',
  nomllarggrup text NOT NULL,
  usuarigestor varchar(12) NOT NULL default '',
  usuarismembres text NOT NULL,
  recursos text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_grupsfitxers`
#

CREATE TABLE at_11_12_grupsfitxers (
  id int(11) NOT NULL auto_increment,
  idrecurs int(11) NOT NULL default '0',
  branca int(11) NOT NULL default '0',
  data bigint(20) NOT NULL default '0',
  creat_per varchar(40) NOT NULL default '',
  vist_per text NOT NULL,
  ref_fitxer varchar(30) NOT NULL default '',
  nom_fitxer varchar(100) NOT NULL default '',
  descripcio text NOT NULL,
  tipus_mime varchar(50) NOT NULL default '',
  tamany bigint(20) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_grupsforums`
#

CREATE TABLE at_11_12_grupsforums (
  id int(11) NOT NULL auto_increment,
  idrecurs int(11) NOT NULL default '0',
  fil_de_id int(11) NOT NULL default '0',
  data bigint(20) NOT NULL default '0',
  creat_per varchar(40) NOT NULL default '',
  vist_per text NOT NULL,
  tema varchar(255) NOT NULL default '',
  text text NOT NULL,
  ref_fitxer varchar(30) NOT NULL default '',
  nom_fitxer varchar(100) NOT NULL default '',
  tipus_mime varchar(50) NOT NULL default '',
  tamany bigint(20) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_grupsrecurs`
#

CREATE TABLE at_11_12_grupsrecurs (
  id int(11) NOT NULL auto_increment,
  tipus varchar(40) NOT NULL default '',
  propietat varchar(5) NOT NULL default '',
  nom varchar(40) NOT NULL default '',
  descripcio text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_horariprofs`
#

CREATE TABLE at_11_12_horariprofs (
  id int(11) NOT NULL auto_increment,
  idprof varchar(15) NOT NULL default '',
  diasem char(3) NOT NULL default '',
  hora char(3) NOT NULL default '',
  grup varchar(250) NOT NULL default '',
  assign varchar(250) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
INSERT INTO `at_11_12_horariprofs` VALUES (1, 'admin', 'X', 'X', 'admin', '');

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_informeincid`
#

CREATE TABLE at_11_12_informeincid (
  id int(11) NOT NULL auto_increment,
  ref_alum varchar(25) NOT NULL default '0',
  id_prof varchar(25) NOT NULL default '',
  data bigint(20) NOT NULL default '0',
  hora char(3) default NULL,
  text text,
  public boolean NOT NULL default FALSE,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_informelliure`
#

CREATE TABLE at_11_12_informelliure (
  id int(11) NOT NULL auto_increment,
  id_prof varchar(25) NOT NULL default '',
  ref_alum text,
  data bigint(20) NOT NULL default '0',
  titol varchar(100) default NULL,
  contingut text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_informes`
#

CREATE TABLE at_11_12_informes (
  id int(11) NOT NULL auto_increment,
  referencia varchar(30) NOT NULL default '',
  text text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
INSERT INTO `at_11_12_informes` VALUES (2, 'informe2', '                                                                                         !PARE1\r\n                                                                                         !PARE2\r\n                                                                                         !ADRECA\r\n                                                                                         !CODIPOSTAL !POBLACIO\r\n                                                            \r\n            \r\n            Senyor/a,\r\n            \r\n            Us comuniquem que !VFILL\r\n\r\n                        !ALUMNE\r\n\r\n            que consta !MATRICULAT al curs !ESTUDIS, ha  faltat  a  classe de forma injustificada un total de !NFALTESM hores,  durant el per�ode que va des del !DATAI fins el !DATAF.\r\n            En aquest per�ode tamb� ha fet !NRETARDSM retards injustificats.\r\n\r\n!DETFALM\r\n\r\n!DETRETM\r\n\r\n            Des del comen�ament del curs, ha fet !NFALTESCJ faltes justificades, !NFALTESC faltes injustificades, !NRETARDSCJ retards justificats i !NRETARDSC retards injustificats.\r\n                \r\n            Per  la  qual  cosa  us preguem  posar-vos  en contacte amb nosaltres per tal de concertar una entrevista.\r\n         \r\n            Cordialment,\r\n            EL/LA  TUTOR/A\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n            !TUTOR\r\n\r\n             !POBLACIOCENTRE, a !DATA0 ');
INSERT INTO `at_11_12_informes` VALUES (3, 'informe3', '                                       AV�S I AMONESTACI� ESCRITA\r\n                                                                                 !PARE1\r\n                                                                                 !PARE2\r\n                                                                                 Pares de l''alumne: !ALUMNE\r\n                                                                                 Curs: !ESTUDIS\r\n                                                            \r\n            Srs.:\r\n            \r\n            Poso   en   el  seu   coneixement  que  d''acord   amb  els  informes  rebuts   dels \r\nprofessors  sobre  !VFILL, ha  com�s  fins  aquest  moment  !NFALTESM  faltes  d''assist�ncia \r\ninjustificades en el present curs acad�mic.\r\n            En  aplicaci�  del  Decret  279/2006,  de  4  de juliol,  sobre  drets  i  deures  dels \r\nalumnes  de  nivell  no universitari  de Catalunya  i de les disposicions  complement�ries \r\ndel  Consell Escolar,  data  13  de mar�  de 2000,  us comuniquem  que  aquest fet  est�\r\ntipificat  com  una conducta  contr�ria a  les normes de conviv�ncia, sancionada amb  la \r\ncorresponent amonestaci�\r\n            En apliaci� de la legislaci� vigent, disposeu d''un termini de tres dies per presentar \r\nles  justificacions que considereu  oportunes mitjan�ant el justificant  extraordinari que  us \r\nadjuntem. En cas contrari, es consideraran com a definitives la tipificaci� i la sanci� abans \r\nesmentades.\r\n\r\n            !POBLACIOCENTRE, a !DATA0,\r\n            El/la  tutor/a !TUTOR\r\n                                                                                                He rebut l''original\r\n                                                                                                Data:\r\n                                                                                                D.N.I.\r\n                                                                                                Signat: Pares/representants legals');

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_llistacredits`
#

CREATE TABLE at_11_12_llistacredits (
  id int(11) NOT NULL auto_increment,
  codi varchar(25) NOT NULL default '',
  nomcredit varchar(50) NOT NULL default '',
  areaassign varchar(15) NOT NULL default '',
  tipus varchar(15) NOT NULL default '',
  nivell varchar(15) NOT NULL default '',
  pla_estudis varchar(40) NOT NULL default '',
  observacions text NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY codi (codi)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_Estudiants_Materies`
#

CREATE TABLE at_11_12_Estudiants_Materies (
  id int(11) NOT NULL auto_increment,
  codi_credit varchar(25) NOT NULL default '',
  numero_mat varchar(50) default '0',
  cognom1 varchar(30) NOT NULL default '',
  cognom2 varchar(30) default NULL,
  nom varchar(30) default NULL,
  pla_estudis varchar(40) NOT NULL default '',
  nivell varchar(15) NOT NULL default '',
  codi_area varchar(15) NOT NULL default '',
  nom_credit varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_logs`
#

CREATE TABLE at_11_12_logs (
  id bigint(20) NOT NULL auto_increment,
  usuari varchar(25) NOT NULL default '',
  datahora bigint(20) NOT NULL default '0',
  ipremota varchar(25) NOT NULL default '',
  text text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_marcshoraris`
#

CREATE TABLE at_11_12_marcshoraris (
  id int(11) NOT NULL auto_increment,
  curs varchar(5) NOT NULL default '',
  grup varchar(5) NOT NULL default '',
  etapa varchar(25) NOT NULL default '',
  diasem char(3) NOT NULL default '',
  hora varchar(5) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_notes`
#

CREATE TABLE at_11_12_notes (
  id bigint(20) NOT NULL auto_increment,
  ref_aval varchar(25) NOT NULL default '',
  ref_alum varchar(25) NOT NULL default '',
  ref_credit varchar(25) NOT NULL default '',
  valor varchar(18) NOT NULL default '',
  usuari varchar(30) NOT NULL default '',
  memo text NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY ref_avalref_alumref_credit (ref_aval,ref_alum,ref_credit)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_parametres`
#

DROP TABLE IF EXISTS `at_11_12_parametres`;
CREATE TABLE `at_11_12_parametres` (
  `id` int(11) NOT NULL auto_increment,
  `nomcentre` varchar(50) NOT NULL default '',
  `adrecacentre` varchar(50) NOT NULL default '',
  `cpcentre` varchar(7) NOT NULL default '',
  `poblaciocentre` varchar(50) NOT NULL default '',
  `telfcentre` varchar(50) NOT NULL default '',
  director varchar(15) NOT NULL default '',
  `nomdirector` varchar(50) NOT NULL default '',
  `sexdirector` char(1) NOT NULL default '',
  `cursacademic` varchar(50) NOT NULL default '',
  `datainicicurs` bigint(20) NOT NULL default '0',
  `datainici2T` bigint(20) NOT NULL default '0',
  `datainici3T` bigint(20) NOT NULL default '0',
  `webcentre` varchar(50) NOT NULL default '',
  `emailcentre` varchar(50) NOT NULL default '',
  `remitentSMS` varchar(11) NOT NULL default '',
  `proveidorSMS` varchar(50) NOT NULL default '',
  capdes varchar(15) NOT NULL default '',
  `nomcapdes` varchar(50) NOT NULL default '',
  `sexcapdes` char(1) NOT NULL default '',
  coordbtx varchar(15) NOT NULL default '',
  `nomcoordbtx` varchar(50) NOT NULL default '',
  `sexcoordbtx` char(1) NOT NULL default '',
  `nom_cc_alumne` varchar(50) NOT NULL default '',
  `sex_cc_alumne` char(1) NOT NULL default '',
  `nom_cc_profe` varchar(50) NOT NULL default '',
  `sex_cc_profe` char(1) NOT NULL default '',
  `nom_cc_pare` varchar(50) NOT NULL default '',
  `sex_cc_pare` char(1) NOT NULL default '',
  retards_ESO int(2) NOT NULL default 15,
  reset_ESO tinyint(1) NOT NULL default '0',
  retards_BTX int(2) NOT NULL default 15,
  reset_BTX tinyint(1) NOT NULL default '0',
  `passwdSMSLlNet` varchar(50) NOT NULL default '',
  `identificSMSDinahosting` varchar(50) NOT NULL default '',
  `passwdSMSDinahosting` varchar(50) NOT NULL default '',
  `identificSMSLlNet` varchar(50) NOT NULL default '',
  `sms_auto` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_pares`
#

CREATE TABLE at_11_12_pares (
  id bigint(20) NOT NULL auto_increment,
  identificador varchar(40) NOT NULL default '',
  passwd varchar(10) NOT NULL default '',
  refalumne varchar(20) NOT NULL default '0',
  permisos int(11) default NULL,
  telfSMS varchar(9) NOT NULL default '',
  email varchar(64) NOT NULL default '',
  email2 varchar(64) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY identificador (identificador)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_pareslogs`
#

CREATE TABLE at_11_12_pareslogs (
  id bigint(20) NOT NULL auto_increment,
  usuari varchar(40) NOT NULL default '',
  datahora bigint(20) NOT NULL default '0',
  ipremota varchar(20) NOT NULL default '',
  text text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_sessions`
#

CREATE TABLE at_11_12_sessions (
  id bigint(20) NOT NULL auto_increment,
  ref_usuari varchar(25) NOT NULL default '',
  ipremota varchar(16) NOT NULL default '',
  horainici bigint(20) NOT NULL default '0',
  idsess varchar(40) NOT NULL default '',
  nomreal varchar(50) NOT NULL default '',
  privilegis text NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY idsess (idsess)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_subgrups`
#

CREATE TABLE at_11_12_subgrups (
  id int(11) NOT NULL auto_increment,
  ref_subgrup varchar(15) NOT NULL default '',
  nom text NOT NULL,
  alumnes text,
  PRIMARY KEY  (id),
  UNIQUE KEY ref_subgrup (ref_subgrup)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_rel_subgrups`
#

CREATE TABLE at_11_12_rel_subgrups (
  id int(11) NOT NULL auto_increment,
  ref_subgrup varchar(15) NOT NULL default '',
  codi_credit varchar(15) NOT NULL default '',
  curs varchar(15) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY ref_subgrup (ref_subgrup, codi_credit, curs)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_dif_subgrups`
#

CREATE TABLE at_11_12_dif_subgrups (
  id int(11) NOT NULL auto_increment,
  ref_subgrup varchar(15) NOT NULL default '',
  curs varchar(15) NOT NULL default '',
  subgrup_orig varchar(15) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY ref_subgrup (ref_subgrup, curs, subgrup_orig)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Estructura de tabla para la tabla `at_11_12_usu_profes`
#

CREATE TABLE at_11_12_usu_profes (
  ident varchar(15) NOT NULL default '',
  nomreal varchar(92) default NULL,
  usuari varchar(31) default NULL,
  passwd varchar(7) NOT NULL default '',
  passwd_crypt varchar(40) NOT NULL default '',
  gid int(5) NOT NULL default '2000',
  home varchar(30) NOT NULL default '',
  grup_usuari varchar(6) default NULL,
  shell varchar(10) NOT NULL default '',
  uid int(11) NOT NULL default '2000',
  telfSMS varchar(9) NOT NULL default '',
  email varchar(64) NOT NULL default ''
) TYPE=MyISAM;
/*INSERT INTO at_11_12_usu_profes VALUES ('', 'Administrador', 'admin', 'aa1111', '137a3a3a7809a828afe701ad71827e81', 2000, '', NULL, '', 2000, '');*/

