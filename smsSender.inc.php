<?php
/*
 * Copyright (c) 2006, Dinahosting S.L.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright notice, this
 *       list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *     * Neither the name of the Dinahosting S.L. nor the names of its contributors
 *       may be used to endorse or promote products derived from this software
 *       without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
 * OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 */

if (defined("_DINAHOSTING_SMSSENDER_INCLUDED"))
{
    return;
}

/**
 * Para evitar inclusi� multiple.
 */
define("_DINAHOSTING_SMSSENDER_INCLUDED",true);
/**
 * url a la que se envian las peticiones.
 */
define("_DINAHOSTING_URL_ENVIO","https://apisms.gestiondecuenta.com/php/comun/ejecutarComando.php");

/**
 * Para ayudar al depurado.
 */
define("_DINAHOSTING_SMS_DEBUG",false);


/**
 * Se encarga del envio de SMS usando la API de dinahosting.com
 * @version 1.0
 */
class smsSender {
    /**
     * Nombre de Cuenta
     * @var string
     */
    var $_cuenta;
    /**
     * Contrase� de la cuenta
     * @var string
     */
    var $_password;

    /**
     * Remite Usado en lugar del por defecto.
     * @var string
     */
    var $_remite;
    /**
     * Remite Usado en lugar del por defecto.
     * @var string
     */
    var $_programmed;
    /**
     * Parser usado en la ultima petici�.
     * @var smsSenderXmlResponseParser
     */
    var $_parser;
    /**
     * Url de envio de parametros.
     * @var string
     */
    var $_url;
    /**
     * Errores que se producen en el envio.
     * @var array
     */
    var $errores;
    /**
     * Credito restante, se actualiza en cada petici� correcta.
     * @var int
     */
    var $credito;

    /**
     * Cortamos el mensaje a enviar para enviar solamente los 160 caracteres del estándar GSM.
     * @var string
     */
    var $_cortarMsg;

    /**
     * Contructor
     *
     * @param string $cuenta Nombre de cuenta
     * @param strinf $password Password de la cuenta.
     */
    function smsSender($cuenta,$password,$remite="")
    {
        $this->_cuenta=$cuenta;
        $this->_password=$password;
        $this->_remite=(strlen($remite))?$remite:null;
    }

    /**
     * Establece el remite de los mensajes
     *
     * @param string $remite El numero remite en los mensajes que ser� enviados, NULL usa el remite por defecto.
     */
    function setRemite($remite)
    {
        $this->_remite=$remite;
    }

    /**
     * Establece la fecha de programación si es un envío programado
     *
     * @param string $programmed Fecha en formato YYYY-MM-DD hh:mm.
     */
    function setProgrammed($programmed)
    {
        $this->_programmed=$programmed;
    }

    /**
     * Establece si se fuerza el envío del mensaje aunque sobre pase los 160 caracteres
     *
     * @param boolean $cortar Por defecto populado a false.
     */
    function setCortarMsg($cortar=false)
    {
        $this->_cortarMsg=$cortar;
    }


    /**
     * Envia un mensaje a un numero
     *
     * @param string $numero El numero en formato ISO
     * @param string $mensaje El mensaje
     * @return boolean Indicando el �ito o fracaso del envio.
     */
    function send($numero,$mensaje)
    {
        $parametros=$this->_getParams("sms_send");
        $parametros['sms']=$mensaje;
        $parametros['sms_to']=$numero;
        if($this->_cortarMsg)
        {
            $parametros['cortar_mensaje']=true;
        }

        return $this->_parse($this->_envia($parametros));
    }

    /**
     * Envia el mismo mensaje a varios numeros.
     *
     * @param array $numeros Array que contiene los numeros a los que se enviar�el mensaje.
     * @param string $mensaje El mensaje.
     * @return boolean Indicando el �ito o fracaso del envio.
     */
    function sendBulk($numeros,$mensaje)
    {
        $parametros=$this->_getParams("sms_sendBulk");
        $parametros['sms']=$mensaje;
        $parametros['sms_n_msgs']=count($numeros);
        if($this->_cortarMsg)
        {
            $parametros['cortar_mensaje']=true;
        }
        for ($i=1;$i<=count($numeros);$i++)
        {
            $parametros['sms_to['.$i.']']=$numeros[$i-1];
        }
        return $this->_parse($this->_envia($parametros));
    }

    /**
     * Devuelve el credito disponible.
     *
     * @return int El credito.
     */
    function getCredito()
    {
        if ($this->_parse($this->_envia($this->_getParams("sms_getCredit"))))
        {
            return $this->credito;
        }
        else
        {
            return false;
        }
    }

    /**
     * Parsea el xml devuelto.
     * @param string $xml
     */
    function _parse($xml)
    {
        $this->errores=array();
        if ($xml)
        {
            $this->_parser=new smsSenderXmlResponseParser($xml);
            $this->_parser->parse();
            if ($this->_parser->done && $this->_parser->code=="700")
            {
                if (is_array($this->_parser->errores) && count($this->_parser->errores))
                {
                    $this->errores=$this->_parser->errores;
                    return false;
                }
                else
                {
                    $this->credito=$this->_parser->credit;
                    return true;
                }
            }
            else
            {
                $this->errores=$this->_parser->errores;
                return false;
            }
        }
        else
        {
            $this->_parser=null;
            $this->errores=array("CANT_CONNECT_API");
            return false;
        }
    }


    /**
     * Devuelve un array con los parametros b�icos.
     *
     */
    function _getParams($command)
    {
        $parametros=array("command" =>$command,
                          "cuenta"  =>$this->_cuenta,
                          "password"=>$this->_password);
        if ($this->_remite)
        {
            $parametros['remite']=$this->_remite;
        }
        if ($this->_programmed)
        {
            $parametros['programar']=$this->_programmed;
        }
        return $parametros;
    }

    /**
     * Realiza la petici� remota.
     *
     * @param array $parametros Array asociativo con los nombres de los parametros y sus valores.
     * @return string Col el resultado de la petici�.
     */
    function _envia($parametros)
    {
        $paramsTmp=array();
        foreach ($parametros as $key=>$value)
        {
            if(mb_get_info("http_output") !== "ISO-8859-1")
            {
                $value=iconv("ISO-8859-1", "UTF-8", $value);
            }
            if($key=="sms")
            {
            //$value=str_replace("&", "&amp;", $value);
                $value=urlencode($value);
            }
            $paramsTmp[]=$key."=".$value;
        }
        $paramStr=implode("&",$paramsTmp);

        if (_DINAHOSTING_SMS_DEBUG)
        {
            echo "POST "._DINAHOSTING_URL_ENVIO."\n";
            echo "Post data: '".$paramStr."'\n";
        }
        $ch=curl_init();
        if (_DINAHOSTING_SMS_DEBUG)
        {
            var_dump($ch);echo '\n';
        }
        curl_setopt ($ch, CURLOPT_URL,_DINAHOSTING_URL_ENVIO);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_HEADER, FALSE);
        curl_setopt ($ch, CURLOPT_POST, TRUE);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,$paramStr);

        if (_DINAHOSTING_SMS_DEBUG)
        {
            var_dump($ch);echo '\n';
        }

        $respuesta=curl_exec ($ch);
        if (_DINAHOSTING_SMS_DEBUG)
        {
            echo "Respuesta:\n$respuesta\n";
        }
        return $respuesta;
    }
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
     * Estado de exito o fracaso de la operaci�.
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
