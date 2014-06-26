<?php
require_once($CFG->dirroot."/config.php");
require_once($CFG->dirroot.'/local/rcommon/WebServices/lib.php');
require_once($CFG->dirroot.'/local/rcommon/locallib.php');

function get_all_books_structure($publisherid = false, $isbn = false) {
    global $CFG, $DB, $USER;

    set_time_limit(0);

    $ret = true;

    try  {
        $params = array();
        if ($publisherid) {
            $params['id'] = $publisherid;
        }

        if ($publishers = $DB->get_records('rcommon_publisher',$params)) {
            echo '<ul>';
            foreach($publishers as $pub) {
                echo '<li>'.$pub->name;

                if (!empty($pub->urlwsbookstructure)) {
                    $ret = $ret && get_books_structure_publisher($pub, $isbn);
                }
                echo '</li>';
            }
            echo '</ul>';
        } else {
            $ret = false;
        }

    } catch(Exception $fault) {
        //echo "Error: ". $fault->getMessage();
        $ret = false;

        //test if isset the url
        //save error on bd
        $tmp = new stdClass();
        $tmp->time      =  time();
        $tmp->userid    =  $USER->id;
        $tmp->ip        =  $_SERVER['REMOTE_ADDR'];
        //$tmp->course    =  $data->course;
        //$tmp->module    =  $data->module;
        //$tmp->cmid      =  $data->cmid;
        $tmp->action    =  'get_all_books_structure_error';
        $tmp->url       =  $_SERVER['REQUEST_URI'];

        $tmp->info      =  'Error get_all_books_structure: '.$fault->getMessage();

        $DB->insert_record('rcommon_errors_log', $tmp);
    }

    return $ret;
}


function get_books_structure_publisher($publisher, $isbn = false) {
    global $CFG, $DB;
    set_time_limit(0);

    try {
        if ($ret = get_books($publisher)) {
            if (isset($ret->ObtenerTodosResult->Codigo) == false) {
            	$resp_libros = rcommon_xml2array($ret);
                $keys = array('Envelope', 'Body', 'ObtenerTodosResponse', 'ObtenerTodosResult', 'Catalogo', 'libros', 'libro');
                $arraylibros = rcommond_findarrayvalue($resp_libros, $keys);

                if(!empty($arraylibros)){
					// MARSUPIAL ************ AFEGIT -> Fix bug, when there is just one received book
					// 2011.09.29 @mmartinez
					if (!isset($arraylibros[0])) {
						$arraylibros = array(0 => $arraylibros);
					}
					// *********** FI
                    echo '<ol>';
	                //foreach($resp_libros["soap:Envelope"]["soap:Body"]["ObtenerTodosResponse"]["ObtenerTodosResult"]["Catalogo"]["libros"]["libro"] as $li)
	                foreach($arraylibros as $li) {
	                	$instance = new StdClass();
	                    //$cod_isbn = $li["isbn"]["value"];
	                    $cod_isbn = rcommond_findarrayvalue($li, array('isbn', 'value'));

	                    //si se ha especificado un isbn guarda el libro
	                    if (!$isbn || $cod_isbn == $isbn) {

	                        echo '<li>ISBN: '.$cod_isbn.' -- ';

	                        //obtiene los datos del indice del libro
	                        $ret = get_book_structure($publisher, $cod_isbn);

	                        if (isset($ret->ObtenerEstructuraResult->Codigo) == false) {
	                            //transforma xmlresponse a array
	                            $resp_indice = rcommon_xml2array($ret);

	                            //selecciona el array de unidades
                                $keys = array("Envelope", "Body", "ObtenerEstructuraResponse", "ObtenerEstructuraResult", "Libros", "libro", "unidades", "unidad");
	                            $unidades_xml = rcommond_findarrayvalue($resp_indice, $keys);

	                            if ($unidades_xml && !array_key_exists('0', $unidades_xml)) {
                                	$unidades_xml = array($unidades_xml);
                                }

	                            // guarda los datos del libro

	                            $instance->isbn = $cod_isbn;
	                            $instance->name = rcommond_findarrayvalue($li, array("titulo", "value"));
	                            $instance->summary = rcommond_findarrayvalue($li, array("titulo", "value"));
	                            $instance->format = str_replace("'", "''", rcommond_findarrayvalue($li, array("formato", "value")));
	                            $instance->levelid = rcommond_findarrayvalue($li, array("nivel", "value"));

	                            $instance->publisherid = $publisher->id;
	                            $instance->structureforaccess = (count($unidades_xml) > 0)? 1 : 0;

                                try {
                                    $bookid = rcommon_book::add_update($instance);
                                } catch( Exception $e){
                                    echo "KO! -- <span style='color: red;'>".$e->getMessage()."</span>";
                                    continue;
                                }
                                echo "OK";

	                            //if exists units
	                            if ($unidades_xml != false) {
                                    //echo '<ul>';
	                                //recorre las unidades
	                                foreach($unidades_xml as $un) {
	                                    $instance = new stdClass();
	                                    $instance->bookid = $bookid;

	                                    $instance->code = rcommond_findarrayvalue($un, array("id", "value"));
                                        // Check if $instance->code is empty and search directly in the array
                                        if (empty($instance->code)) {
                                        	$instance->code = (isset($un["id"]["value"]))? $un["id"]["value"]: '';
                                        }

	                                    $instance->name = rcommond_findarrayvalue($un, array("titulo", "value"));
	                                    $instance->summary = rcommond_findarrayvalue($un, array("titulo", "value"));
	                                    $instance->sortorder = rcommond_findarrayvalue($un, array("orden", "value"));

                                        //echo "<li>Unit: {$instance->name}";
                                        $unitid = rcommon_unit::add_update($instance);

                                        $actividades = rcommond_findarrayvalue($un, array("actividades", "actividad"));
                                        if ($actividades && !array_key_exists('0', $actividades)){
                                	        $actividades = array($actividades);
                                        }

	                                    //if exists activities
	                                    if ($actividades != false) {
                                            //echo '<ul>';
	                                        //guarda las actividades de la unidad
	                                        foreach($actividades as $act) {

	                                            $instance = new stdClass();
	                                            $instance->bookid = $bookid;
	                                            $instance->unitid = $unitid;
	                                            $instance->code = rcommond_findarrayvalue($act, array("id", "value"));
	                                            $instance->name = rcommond_findarrayvalue($act, array("titulo", "value"));
	                                            $instance->summary = rcommond_findarrayvalue($act, array("titulo", "value"));
	                                            $instance->sortorder = rcommond_findarrayvalue($act, array("orden", "value"));

                                                //echo "<li>Activity: {$instance->name}</li>";
                                                $activid = rcommon_activity::add_update($instance);
	                                        }
                                            //echo '</ul>';
	                                    }
                                        //echo '</li>';
	                                }
                                    //echo '</ul>';
	                            }
                                echo '</li>';
                            } else {
                            $urlok='';
                            if (isset($ret->ObtenerEstructuraResult->URL)) {
                                $curl=curl_init();
                                curl_setopt($curl, CURLOPT_URL, $ret->ObtenerEstructuraResult->URL);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_HEADER, false);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
// MARSUPIAL ************ AFEGIT -> Added proxy option
// 2012.08.30 @mmartinez
							        if ($CFG->proxytype == 'HTTP' && !empty($CFG->proxyhost)) {
							        	curl_setopt($curl, CURLOPT_PROXY, $CFG->proxyhost);
							        	if (!empty($CFG->proxyport)) {
							        		curl_setopt($curl, CURLOPT_PROXYPORT, $CFG->proxyport);
							        	}
							        	if (!empty($CFG->proxyuser)) {
							        		curl_setopt($curl, CURLOPT_PROXYUSERPWD, $CFG->proxyuser . ':' . $CFG->proxypassword);
							        	}
							        }
// ************** FI
							        $urlok = curl_exec($curl);
							        curl_close($curl);
	                        	}

	                        	global $USER;
	                            //save error on bd
	                            $tmp = new stdClass();
	                            $tmp->time      =  time();
	                            $tmp->userid    =  $USER->id;
	                            $tmp->ip        =  $_SERVER['REMOTE_ADDR'];
	                            //$tmp->course    =  $data->course;
	                            //$tmp->module    =  $data->module;
	                            //$tmp->cmid      =  $data->cmid;
	                            $tmp->action    =  "get_books_structure_publishererror";
	                            $tmp->url       =  $_SERVER['REQUEST_URI'];
	                            $tmp->info      =  'Error get_books_structure: C&oacute;digo: '.$ret->ObtenerEstructuraResult->Codigo.' - '.$ret->ObtenerEstructuraResult->Descripcion;
	                            if ($urlok) {
	                                $tmp->info      =  $tmp->info.", URL: ".$response->ObtenerEstructuraResult->URL;
                                }

	                            $DB->insert_record("rcommon_errors_log", $tmp);

	                            echo "<br>".$tmp->info."<br>";

	                            continue;
	                            //print_error('Error get_book_structure: C&oacute;digo: '.$ret->ObtenerEstructuraResult->Codigo.' - '.$ret->ObtenerEstructuraResult->Descripcion);
	                        }
	                    }
	                }
                    echo '</ol>';
	                return true;
	              } else {
	              	 echo "<br><br>".get_string('nobooks','local_rcommon');
	              	 return true;
	              }
            } else {
                //test if isset the url
                $urlok='';
                if(isset($ret->ObtenerTodosResult->URL)) {
				        $curl=curl_init();
				        curl_setopt($curl, CURLOPT_URL, $ret->ObtenerTodosResult->URL);
				        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				        curl_setopt($curl, CURLOPT_HEADER, false);
				        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
// MARSUPIAL ************ AFEGIT -> Added proxy option
// 2012.08.30 @mmartinez
				        if ($CFG->proxytype == 'HTTP' && !empty($CFG->proxyhost)) {
				        	curl_setopt($curl, CURLOPT_PROXY, $CFG->proxyhost);
				        	if (!empty($CFG->proxyport)) {
				        		curl_setopt($curl, CURLOPT_PROXYPORT, $CFG->proxyport);
				        	}
				        	if (!empty($CFG->proxyuser)) {
				        		curl_setopt($curl, CURLOPT_PROXYUSERPWD, $CFG->proxyuser . ':' . $CFG->proxypassword);
				        	}
				        }
// ************** FI
				        $urlok = curl_exec($curl);
				        curl_close($curl);
                }

                global $USER;
                //save error on bd
                $tmp = new stdClass();
                $tmp->time      =  time();
                $tmp->userid    =  $USER->id;
                $tmp->ip        =  $_SERVER['REMOTE_ADDR'];
                //$tmp->course    =  $data->course;
                //$tmp->module    =  $data->module;
                //$tmp->cmid      =  $data->cmid;
                $tmp->action    =  "get_books_structure_publishererror";
                $tmp->url       =  $_SERVER['REQUEST_URI'];

                $tmp->info      =  'Error get_books: Código: '.$ret->ObtenerTodosResult->Codigo.' - '.$ret->ObtenerTodosResult->Descripcion;
                if ($urlok)
                    $tmp->info      =  $tmp->info.", URL: ".$ret->ObtenerTodosResult->URL;

                $DB->insert_record("rcommon_errors_log", $tmp);

                echo "<br>".$tmp->info."<br>";

                return false;
                //print_error('Error get_books: C&oacute;digo: '.$ret->ObtenerTodosResult->Codigo.' - '.$ret->ObtenerTodosResult->Descripcion);
            }
        }
    } catch(Exception $fault) {
        print_error('Error: '.$fault->getMessage());
        return false;
    }
}



/**
 * Web Service to access digital content SM
 * @param none
 * @return obj -> web service response
 */
function get_books($publisher) {
    global $CFG, $DB, $OUTPUT;
    //echo "<br>Contenido Digital";

    try {
        $center = get_marsupial_center();

        $client = get_marsupial_ws_client($publisher);

        $params = new stdClass();
        $params->IdCentro = @new SoapVar($center, XSD_STRING, "string", "http://www.w3.org/2001/XMLSchema");

        $response = $client->__soapCall("ObtenerTodos", array($params));

        log_to_file("get_books Request: ".$client->__getLastRequest(),'rcommon_tracer');
        log_to_file("get_books Response: ".$client->__getLastResponse(),'rcommon_tracer');

        //check if there are any response error
        if ($response->ObtenerTodosResult->Codigo <= 0) {
            return $response;
            //print_error('Error get_books: C&oacute;digo: '.$response->ObtenerTodosResult->Codigo.' - '.$response->ObtenerTodosResult->Descripcion);
        } else {
            //return $response;
            return $client->__getLastResponse();
        }

    } catch(Exception $fault) {

        global $USER;

        //save error on bd
        $tmp = new stdClass();
        $tmp->time      =  time();
        $tmp->userid    =  $USER->id;
        $tmp->ip        =  $_SERVER['REMOTE_ADDR'];
        //$tmp->course    =  $data->course;
        $tmp->module    =  'rcommon';
        //$tmp->cmid      =  $data->cmid;
        $tmp->action    =  "get_books_error";
        $tmp->url       =  $_SERVER['REQUEST_URI'];

        $tmp->info      =  'Error get_books: '.str_replace($fault->getMessage(), '\"', '');

        $DB->insert_record("rcommon_errors_log", $tmp);

        //error('Error get_books: '.$fault->getMessage());

        echo $OUTPUT->notification("Error get_books: <br/>". $fault->getMessage());

        log_to_file("wsGetBooksStructure get_books() response error: ". $fault->getMessage(),'rcommon_tracer');

        //echo("<br><br>REQUEST: " . $client->__getLastRequest() . "\n<br><br>RESPONSE: ".$client->__getLastResponse()."<br><br>");
        return false;
    }

}


/**
 * Web Service to access digital content SM
 * @param none
 * @return obj -> web service response
 */
function get_book_structure($publisher, $isbn) {
    global $CFG, $USER, $DB;
    //echo "<br>Indice Libro: ".$wsurl_contenido."<br>";

    try {
        $client = get_marsupial_ws_client($publisher);

        $params = new stdClass();
        $params->ISBN = @new SoapVar($isbn, XSD_STRING, "string", "http://www.w3.org/2001/XMLSchema");
        $response = $client->__soapCall("ObtenerEstructura", array($params));

        log_to_file("wsget_books_structure Request: ".$client->__getLastRequest(),'rcommon_tracer');
        log_to_file("wsget_books_structure Response: ".$client->__getLastResponse(),'rcommon_tracer');

        //check if there are any response error
        if ($response->ObtenerEstructuraResult->Codigo <= 0) {
            //print_error('Error get_book_structure: C&oacute;digo: '.$response->ObtenerEstructuraResult->Codigo.' - '.$response->ObtenerEstructuraResult->Descripcion);
            return $response;
        } else {
            //return $response;
            return $client->__getLastResponse();
        }

    } catch(Exception $fault) {
        //echo "Error: ". $fault->getMessage();
        log_to_file("wsBookStructure: get_book_structure - Exception = ".$fault->getMessage(),'rcommon_tracer');
        echo "KO!";

        global $USER;
        //save error on bd
        $tmp = new stdClass();
        $tmp->time      =  time();
        $tmp->userid    =  $USER->id;
        $tmp->ip        =  $_SERVER['REMOTE_ADDR'];
        //$tmp->course    =  $data->course;
        $tmp->module    =  'rcommon';
        //$tmp->cmid      =  $data->cmid;
        $tmp->action    =  "get_book_structure_error";
        $tmp->url       =  $_SERVER['REQUEST_URI'];

        $tmp->info      =  'Error get_book_structure: '.$fault->getMessage();

        echo $tmp->info;

        $DB->insert_record("rcommon_errors_log", $tmp);

        //print_error('Error get_book_structure: '.$fault->getMessage());
    }
}
