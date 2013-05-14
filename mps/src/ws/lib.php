<?php

/**
 *  Key looks for a path into an array and returns the value
 *  
 *  @param array $arraywsdl -> indexed XMLarray
 *  @param array $arraykeys -> array of keys to search
 *  @return string -> value of element array or false if not found
 */

function rcommond_findarrayvalue($arraywsdl, $arraykeys)
{
    foreach($arraykeys as $key)
    {
        
        if ($realkey = rcommond_findkey($arraywsdl, $key))
           $arraywsdl = $arraywsdl[$realkey];
        else
            return false;
    }
    
    return $arraywsdl;
}


/**
 *  Looking for a key that contains the specified value
 *  
 *  @param array $arraywsdl -> indexed XMLarray
 *  @param string $valor -> value to search
 *  @return string -> original key or false if not found
 */
 
function rcommond_findkey($arraywsdl, $valor)
{
    //foreach to look in the array for key=definitios 
    foreach($arraywsdl as $key=>$value){
        $pos=strpos(strtolower($key), strtolower($valor));
        if ($pos !== false)
            return $key;
    }
    return false;
}


/**
 * Parse WSDL to get namespaces
 * @param string $urlwdsl
 * @return string -> wdsl namespaces
 */
function rcommond_wdsl_parser($urlwdsl){
	$contents='';
	
    $curl=curl_init();        
    curl_setopt($curl, CURLOPT_URL, $urlwdsl);     
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);    
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);  
    $contents = curl_exec($curl);
    curl_close($curl);
	
    $result = rcommon_xml2array($contents);
    //foreach to look in the array for key=definitios 
    foreach($result as $key=>$value){
    	$pos=strpos($key,'definitions');
    	if ($pos!==false){
    		break;
    	}
    }
    return $result[$key]['attr']['targetNamespace'];
}

/**
 * xml2array() will convert the given XML text to an array in the XML structure.
 * Link: http://www.bin-co.com/php/scripts/xml2array/
 */
function rcommon_xml2array($contents, $get_attributes=1) {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        return array();
    }
    $parser = xml_parser_create();
    xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
    xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
    xml_parse_into_struct( $parser, $contents, $xml_values );
    xml_parser_free( $parser );

    if(!$xml_values) return;
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();
    $current = &$xml_array;

    foreach($xml_values as $data) {
        unset($attributes,$value);
        extract($data);
        $result = '';
        if($get_attributes) {
            $result = array();
            if(isset($value)) $result['value'] = $value;

            if(isset($attributes)) {
                foreach($attributes as $attr => $val) {
                    if($get_attributes == 1) $result['attr'][$attr] = $val; 
                }
            }
        } elseif(isset($value)) {
            $result = $value;
        }
        if($type == "open") {
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) {
                $current[$tag] = $result;
                $current = &$current[$tag];

            } else { 
                if(isset($current[$tag][0])) {
                    array_push($current[$tag], $result);
                } else {
                    $current[$tag] = array($current[$tag],$result);
                }
                $last = count($current[$tag]) - 1;
                $current = &$current[$tag][$last];
            }
        } elseif($type == "complete") { 
            if(!isset($current[$tag])) { 
                $current[$tag] = $result;
            } else { 
                if((is_array($current[$tag]) and $get_attributes == 0)
                        or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
                    array_push($current[$tag],$result); 
                } else { 
                    $current[$tag] = array($current[$tag],$result);
                }
            }
        } elseif($type == 'close') {
            $current = &$parent[$level-1];
        }
    }
    return($xml_array);
} 



function log_to_file($info, $tracer='rcontent_tracer')
{
    global $CFG;

     $directorio_log = $CFG->dataroot."/1/log";

    //Escribimos en un fichero de textos los mensajes de errores
    if(!is_dir($directorio_log))
       mkdir($directorio_log);
    
     if ($handle = @fopen($directorio_log."/Log.log", "a")) 
     {
         $content = "\r\n".time()." - Data: ".$info;
         
         @fwrite($handle,$content);
         @fclose($handle);
     }
     
}

?>
