<?php
require_once($CFG->dirroot."/config.php");
require_once($CFG->dirroot.'/local/rcommon/WebServices/lib.php');
require_once($CFG->dirroot.'/local/rcommon/locallib.php');

function get_all_books_structure($publisherid = false, $isbn = false) {
    global $DB;

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
        $ret = false;
        rcommon_ws_error('get_all_books_structure', $fault->getMessage());
    }

    return $ret;
}


function get_books_structure_publisher($publisher, $isbn = false) {
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
	                    //$cod_isbn = $li["isbn"]["value"];
	                    $cod_isbn = rcommond_findarrayvalue($li, array('isbn', 'value'));

	                    //si se ha especificado un isbn guarda el libro
	                    if (!$isbn || $cod_isbn == $isbn) {

	                        echo '<li>ISBN: '.$cod_isbn.' -- ';

	                        //obtiene los datos del indice del libro
                            try {
                                $instance = new StdClass();
                                $instance->isbn = $cod_isbn;
                                $instance->name = rcommond_findarrayvalue($li, array("titulo", "value"));
                                $instance->summary = rcommond_findarrayvalue($li, array("titulo", "value"));
                                $instance->format = str_replace("'", "''", rcommond_findarrayvalue($li, array("formato", "value")));
                                $instance->levelid = rcommond_findarrayvalue($li, array("nivel", "value"));
                                $instance->publisherid = $publisher->id;
                                $bookid = rcommon_book::add_update($instance);

                                get_book_structure($publisher, $cod_isbn);


                            } catch( Exception $e){
                                echo "KO! -- <span style='color: red;'>".$e->getMessage()."</span>";
                            }
                            echo '</li>';
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
                $message  = 'Código: '.$ret->ObtenerTodosResult->Codigo.' - '.$ret->ObtenerTodosResult->Descripcion;
                if (isset($response->ObtenerTodosResult->URL)) {
                    $message .= ', URL: '.test_ws_url($response->ObtenerTodosResult->URL);
                }
                $message = rcommon_ws_error('get_books_structure_publisher', $message);

                echo "<br>".$message."<br>";
                return false;
            }
        }
    } catch(Exception $fault) {
        rcommon_ws_error('get_books_structure_publisher', $fault->getMessage());
        print_error($fault->getMessage());
        return false;
    }
}



/**
 * Web Service to access digital content SM
 * @param none
 * @return obj -> web service response
 */
function get_books($publisher) {
    global $OUTPUT;
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
        $message = rcommon_ws_error('get_books', $fault->getMessage());
        echo $OUTPUT->notification("Error get_books: <br/>". $message);
        log_to_file("wsGetBooksStructure get_books() response error: ". $fault->getMessage(),'rcommon_tracer');
        return false;
    }

}


/**
 * Web Service to access digital content SM
 * @param none
 * @return obj -> web service response
 */
function get_book_structure($publisher, $isbn) {
    global $DB;
    //echo "<br>Indice Libro: ".$wsurl_contenido."<br>";

    $book = $DB->get_record('rcommon_books',array('isbn'=>$isbn));
    if(!$book){
        throw new Exception('Book not found');
    }

    try {
        $client = get_marsupial_ws_client($publisher);

        $params = new stdClass();
        $params->ISBN = @new SoapVar($isbn, XSD_STRING, "string", "http://www.w3.org/2001/XMLSchema");
        $response = $client->__soapCall("ObtenerEstructura", array($params));

        //log_to_file("wsget_books_structure Request: ".$client->__getLastRequest(),'rcommon_tracer');
        //log_to_file("wsget_books_structure Response: ".$client->__getLastResponse(),'rcommon_tracer');
    } catch(Exception $fault) {
        log_to_file("wsBookStructure: get_book_structure - Exception = ".$fault->getMessage(),'rcommon_tracer');
        rcommon_ws_error('get_book_structure', $fault->getMessage());
        throw new Exception($fault->getMessage());
    }

    if ($response && (!isset($response->ObtenerEstructuraResult->Codigo) || $response->ObtenerEstructuraResult->Codigo > 0)) {
        save_book_structure($client->__getLastResponse(), $book);
    } else {
        $message  = 'Código: '.$response->ObtenerEstructuraResult->Codigo.' - '.$response->ObtenerEstructuraResult->Descripcion;
        if (isset($response->ObtenerEstructuraResult->URL)) {
            $message .= ', URL: '.test_ws_url($response->ObtenerEstructuraResult->URL);
        }
        rcommon_ws_error('get_book_structure', $message);
        throw new Exception($message);
    }
}

function save_book_structure($response, $book){
    //transforma xmlresponse a array
    $resp_indice = rcommon_xml2array($response);

    //selecciona el array de unidades
    $keys = array("Envelope", "Body", "ObtenerEstructuraResponse", "ObtenerEstructuraResult", "Libros", "libro", "unidades", "unidad");
    $unidades_xml = rcommond_findarrayvalue($resp_indice, $keys);

    if ($unidades_xml && !array_key_exists('0', $unidades_xml)) {
        $unidades_xml = array($unidades_xml);
    }

    // Guarda los datos del libro
    $book->structureforaccess = (count($unidades_xml) > 0)? 1 : 0;
    $bookid = rcommon_book::add_update($book);

    //if exists units
    if ($unidades_xml != false) {
        //recorre las unidades
        foreach($unidades_xml as $un) {
            $unit_instance = new stdClass();
            $unit_instance->bookid = $bookid;

            $unit_instance->code = rcommond_findarrayvalue($un, array("id", "value"));
            // Check if $unit_instance->code is empty and search directly in the array
            if (empty($unit_instance->code)) {
                $unit_instance->code = (isset($un["id"]["value"]))? $un["id"]["value"]: '';
            }

            $unit_instance->name = rcommond_findarrayvalue($un, array("titulo", "value"));
            $unit_instance->summary = rcommond_findarrayvalue($un, array("titulo", "value"));
            $unit_instance->sortorder = rcommond_findarrayvalue($un, array("orden", "value"));

            //echo "<li>Unit: {$unit_instance->name}";
            $unitid = rcommon_unit::add_update($unit_instance);

            $actividades = rcommond_findarrayvalue($un, array("actividades", "actividad"));
            if ($actividades && !array_key_exists('0', $actividades)){
                $actividades = array($actividades);
            }

            //if exists activities
            if ($actividades != false) {
                //guarda las actividades de la unidad
                foreach($actividades as $act) {

                    $activity_instance = new stdClass();
                    $activity_instance->bookid = $bookid;
                    $activity_instance->unitid = $unitid;
                    $activity_instance->code = rcommond_findarrayvalue($act, array("id", "value"));
                    $activity_instance->name = rcommond_findarrayvalue($act, array("titulo", "value"));
                    $activity_instance->summary = rcommond_findarrayvalue($act, array("titulo", "value"));
                    $activity_instance->sortorder = rcommond_findarrayvalue($act, array("orden", "value"));
                    $activid = rcommon_activity::add_update($activity_instance);
                }
            }
        }
    }
}
