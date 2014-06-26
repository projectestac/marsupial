<?php

require_once('../../../config.php');
require_once('wsSeguimiento.lib.php');

function ResultadoDetalleExtendido($Resultado) {
    log_to_file("wsSeguimiento request: " . serialize($Resultado));
    $ret = get_ResultadoDetalleExtendido($Resultado->ResultadoExtendido);
    log_to_file("wsSeguimiento response: " . serialize($ret));
    return $ret;
}

function generate_wsdl() {
    global $CFG;

    if (!is_file("$CFG->dataroot/1/WebServices/WsSeguimiento/wsSeguimiento.wsdl") ||
        filemtime("$CFG->dataroot/1/WebServices/WsSeguimiento/wsSeguimiento.wsdl") < 1379462400) { // It's necessary to update WSDL after of 2013/09/18 to add ForzarGuardar parameter
        log_to_file("wsSeguimiento: WSDL no exists");

        if (!is_dir("$CFG->dataroot/1")) {
            mkdir("$CFG->dataroot/1");
        }
        if (!is_dir("$CFG->dataroot/1/WebServices")) {
            mkdir("$CFG->dataroot/1/WebServices");
        }
        if (!is_dir("$CFG->dataroot/1/WebServices/WsSeguimiento")) {
            mkdir("$CFG->dataroot/1/WebServices/WsSeguimiento");
        }
        $f = fopen("$CFG->dataroot/1/WebServices/WsSeguimiento/wsSeguimiento.wsdl", "w");
        $strwsdl = file_get_contents("$CFG->wwwroot/mod/rcontent/WebServices/wsSeguimiento_wsdl.php");
        fwrite($f, $strwsdl);
        fclose($f);
        log_to_file("wsSeguimiento: WSDL created");
    }
}

/* test is isset the wsdl file if not create it */
generate_wsdl();

if (isset($_REQUEST['wsdl']) || isset($_REQUEST['WSDL'])) {
    header('Content-type: text/xml');
    //$wsdl = file_get_contents("$CFG->wwwroot/mod/rcontent/WebServices/wsSeguimiento_wsdl.php");
    $wsdl = file_get_contents("$CFG->dataroot/1/WebServices/WsSeguimiento/wsSeguimiento.wsdl");
    print($wsdl);
} else {
    ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache

    $classmap = array('SeguimientoExtendido' => 'SeguimientoExtendido',
                        'Resultado' => 'Resultado',
                        'DetalleResultado' => 'DetalleResultado',
                        'RespuestaResultadoExtendido' => 'RespuestaResultadoExtendido',
                        'ResultadoDetalleExtendidoResponse' => 'ResultadoDetalleExtendidoResponse');
    //$server = new SoapServer("$CFG->wwwroot/mod/rcontent/WebServices/wsSeguimiento_wsdl.php");
    $server = new SoapServer("$CFG->dataroot/1/WebServices/WsSeguimiento/wsSeguimiento.wsdl");

    $server->addFunction("ResultadoDetalleExtendido");

    $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

    $server->handle();
}
