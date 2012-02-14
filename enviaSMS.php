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

function saldoSMS() {
	global $connect, $bdtutoria, $tbl_prefix;
	$consulta="SELECT proveidorSMS FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	mysql_free_result($conjunt_resultant);

	if($fila[0]=="LleidaNet") return saldoSMSLleidaNet();
	else if($fila[0]=="DinaHosting") return saldoSMSDinaHosting();
	else return "Error connexio1";
}

function enviaSMS($pRemitent, $pLlistaTelfsDesti, $pText) {
	global $connect, $bdtutoria, $tbl_prefix;
	$consulta="SELECT proveidorSMS FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
	//echo "<p> Query: $consulta </p> \n";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	mysql_free_result($conjunt_resultant);

	if($fila[0]=="LleidaNet") return enviaSMSLleidaNet($pRemitent, $pLlistaTelfsDesti, $pText);
	else if($fila[0]=="DinaHosting") return enviaSMSDinaHosting($pRemitent, $pLlistaTelfsDesti, $pText);
	else return "Error connexio";
}

function saldoSMSDinaHosting() {
	global $connect, $bdtutoria, $tbl_prefix;
	$consulta="SELECT identificSMSDinahosting, passwdSMSDinahosting FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	mysql_free_result($conjunt_resultant);
	
	if($fila[0]=="" && $fila[1]=="") return "No configurat";
	
	$url="https://apisms.gestiondecuenta.com/php/comun/ejecutarComando.php";
	$ch=curl_init();
    curl_setopt ($ch, CURLOPT_URL,$url."?command=sms_getCredit&cuenta=".$fila[0]."&password=".$fila[1]);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($ch, CURLOPT_HEADER, FALSE);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                                                              
    $resposta=curl_exec($ch);
    $_parser=new smsSenderXmlResponseParser($resposta);
    $_parser->parse();
    if ($_parser->done && $_parser->code=="700") {
        if (is_array($_parser->errores) && count($_parser->errores)) {
            $errores=$_parser->errores;
            return "Error";
        }
        else {
            $credito=$_parser->credit;
            return $credito;
        }
    }
    else {
        $errores=$_parser->errores;
        print_r( $errores);
        return "Error de connexio";
    }	
}

function enviaSMSDinaHosting($pRemitent, $pLlistaTelfsDesti, $pText) {
	global $connect, $bdtutoria, $tbl_prefix;
	$consulta="SELECT identificSMSDinahosting, passwdSMSDinahosting, remitentSMS FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	mysql_free_result($conjunt_resultant);

	if($fila[0]=="" && $fila[1]=="") return "Error connexio";
	
	$url="https://apisms.gestiondecuenta.com/php/comun/ejecutarComando.php";

	$parametres=array();
	$parametres["command"]="sms_sendBulk";
	$parametres["cuenta"]=$fila[0];
	$parametres["password"]=$fila[1];
	$parametres["remite"]=(($fila[2]=="")?$pRemitent:$fila[2]);
	$parametres["sms"]=$pText;
	$llistaTelfsDesti=explode(";", $pLlistaTelfsDesti);
	$parametres["sms_n_msgs"]=count($llistaTelfsDesti);
	for($i=0; $i<count($llistaTelfsDesti); ++$i) $parametres["sms_to[".($i+1)."]"]= $llistaTelfsDesti[$i];
		
	$paramsTmp=array();
    foreach ($parametres as $key=>$value)
    {
        /*if(mb_get_info("http_output") !== "ISO-8859-1")
        {*/
            if(FALSE!=iconv("ISO-8859-1", "UTF-8", $value)) $value=iconv("ISO-8859-1", "UTF-8", $value);
        /*}*/
        $paramsTmp[]=$key."=".$value;
    }
    $paramStr=implode("&",$paramsTmp);
    
	$ch=curl_init();
    curl_setopt ($ch, CURLOPT_URL,$url);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($ch, CURLOPT_HEADER, FALSE);
    curl_setopt ($ch, CURLOPT_POST, TRUE);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt ($ch, CURLOPT_POSTFIELDS,$paramStr);
                                                                              
    $resposta=curl_exec ($ch);    
    
    $_parser=new smsSenderXmlResponseParser($resposta);
    $_parser->parse();
    if ($_parser->done && $_parser->code=="700") {
        if (is_array($_parser->errores) && count($_parser->errores)) {
            return "NOOK ".$resposta;
        }
        else {
            return $resposta;
        }
    }
    else {
        return "Error connexio";
    }		
}

function enviaSMSLleidaNet($pRemitent, $pLlistaTelfsDesti, $pText) {
	global $connect, $bdtutoria, $tbl_prefix;
	$consulta="SELECT remitentSMS, identificSMSLlNet, passwdSMSLlNet FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	mysql_free_result($conjunt_resultant);
	$remitent=$fila[0];
	$proveidorSMS="sms.lleida.net";
	$portSMS="2048";
	$usuariSMS=$fila[1];
	$passwdSMS=$fila[2];

	if($usuariSMS=='' || $passwdSMS=='') $res="Error: No configurat";
	else {
		$pText=ereg_replace("\n", " ", $pText);
		if($remitent!='') $pRemitent=$remitent;
		$pRemitent=substr(ereg_replace(" ", "", $pRemitent),0,11);	
		$res='';
		$conexio = fsockopen($proveidorSMS,$portSMS);
		if($conexio) {
			fputs($conexio,"1 login $usuariSMS $passwdSMS\r\n");
			$res.=fgets($conexio,256);
			fputs($conexio,"2 trans iniciar\r\n");
			$res.=fgets($conexio,256);
			$llistaTelfsDesti=explode(";", $pLlistaTelfsDesti);
			for($i=0; $i<count($llistaTelfsDesti); ++$i) {
				if ($llistaTelfsDesti[$i]!='') {
					$n=$i+3;
					fputs($conexio,"$n fsubmit $pRemitent +34$llistaTelfsDesti[$i] $pText\r\n");
					$res.=fgets($conexio,256);
				}	
			}
			fputs($conexio, ++$n." trans fin\r\n");
			$res.=fgets($conexio,256);
			fclose($conexio);
		} else $res='Error connexio';
	}
	return $res;
}

function saldoSMSLleidaNet() {
	global $connect, $bdtutoria, $tbl_prefix;
	$consulta="SELECT identificSMSLlNet, passwdSMSLlNet FROM $bdtutoria.$tbl_prefix"."parametres LIMIT 1";
	$conjunt_resultant=mysql_query($consulta, $connect);
	$fila=mysql_fetch_row($conjunt_resultant);
	mysql_free_result($conjunt_resultant);
	$proveidorSMS="sms.lleida.net";
	$portSMS="2048";
	$usuariSMS=$fila[0];
	$passwdSMS=$fila[1];
	
	if($usuariSMS=='' || $passwdSMS=='') $res="No configurat";
	else {
		$conexio = fsockopen($proveidorSMS,$portSMS);
		if($conexio) {
			fputs($conexio,"1 login $usuariSMS $passwdSMS\r\n");		
			$login = fgets($conexio,256);
			ereg("([0-9] )(.*)",$login,$res);
			$resul=rtrim($res[2]);
			settype($resul,"string");
			if ($resul=="NOOK") {
			$res="Error: login incorrecte";
			} else {
				$saldo=split(" ",$resul);
				$res="$saldo[1],$saldo[2] crèdits";
			}
			fclose($conexio);		
		} else $res='Error de connexio';
	}
	return $res;
}



/**
 * Parser para las respuestas de la api de dinahosting
 */
class smsSenderXmlResponseParser {
    /**
     * El xml a parsear.
     * @var string
     */
    var $_xml;
    /**
     * El parser usado.
     * @var Resource
     */
    var $_parser;
    /**
     * Dentro del parseo la tag actual.
     * @var string
     */
    var $_current;
    /**
     * El codigo devuelto.
     * @var int
     */
    var $code;
    /**
     * Los errores Devueltos
     * @var array
     */
    var $errores;
    /**
     * El credito Disponible.
     * @var int
     */
    var $credit;
    /**
     * Estado de exito o fracaso de la operaci\ufffd.
     * @var boolean
     */
    var $done;

    /**
     * Constructor
     *
     * @param string $xml El xml a parsear.
     */
    function smsSenderXmlResponseParser($xml)
    {
        $this->_xml=$xml;
    }

    /**
    * Realiza el parse.
    * @access public
    */
    function parse()
    {
        $this->errores=array();
        $this->_parser=xml_parser_create();
        xml_set_object($this->_parser,$this);
        xml_set_element_handler($this->_parser,"startElementHandler","endElementHandler");
        xml_set_character_data_handler($this->_parser,"cdataHandler");
        xml_parser_set_option($this->_parser,XML_OPTION_CASE_FOLDING,0);
        xml_parse($this->_parser,$this->_xml);
    }

    function startElementHandler($parser, $name, $attribs)
    {
        $this->_current=$name;
    }

    function endElementHandler($parser, $name)
    {
    }

    function cdataHandler($parser,$data)
    {
        switch ($this->_current)
        {
            case "smsCredit":   $this->credit=(int)$data;       break;
            case "Code":        $this->code=$data;              break;
            case "Done":        $this->done=($data=="true");    break;

            default:
                if (preg_match("/^Err[0-9]+/",$this->_current))
                {
                    $this->errores[]=$data;
                }
                break;
        }
    }
}

?>
