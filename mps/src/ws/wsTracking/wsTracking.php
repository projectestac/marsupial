<?php

//print_r($_POST); die; //debug purpose

require_once('../../../config.php');
require_once($CFG->dirroot.'/ws/lib.php'); 

//*****  CLASSES

class Resultado
{
    public $FechaHoraInicio;
    public $Duracion;
    public $MaxDuracion;
    public $MinCalificacion;
    public $Calificacion;
    public $MaxCalificacion;
    public $Intentos;
    public $MaxIntentos;
    public $Estado;
    public $Observaciones;
    public $URLVerResultados;
}


class DetalleResultado
{
    public $IdDetalle;
    public $IdTipoDetalle;
    public $Descripcion;
    public $FechaHoraInicio;
    public $Duracion;
    public $MaxDuracion;
    public $MinCalificacion;
    public $Calificacion;
    public $MaxCalificacion;
    public $Intentos;
    public $MaxIntentos;
    public $Peso;
    public $URLVerResultados;
}


class SeguimientoExtendido
{
    public $idUsuario;
    public $idContenidoLMS;
    public $idCentro;
    public $idUnidad;
    public $UnidadTitulo;
    public $UnidadOrden;
    public $idActividad;
    public $ActividadTitulo;
    public $ActividadOrden;
    public $Resultado;
    public $Detalles;
    public $SumaPesos;
}


class TipoDetalleError
{
    public $Codigo;
    public $Descripcion;
    public $Observaciones;
}


class DatosSeguimientoExtendido
{
    public $Resultado;
    public $DetalleError;
}


class ResultadoDetalleExtendido
{
    public $ResultadoExtendido;
}


//*****

  $detall = array('grade_1', 'grade_2', 'grade_3', 'grade_4');
  $questionsresults = array('key0b20', 'key1b20', 'key2b20', 'key3b20');
  $total_Calif = 0;
  $token = required_param('token', PARAM_RAW); 

  //save log registering the call to the ws server
  add_to_log(3,1);

  /// save log registering the remote content result
  $log = new stdClass();
  $log->token = $token;
  $log->grade_1 = optional_param('grade_1', 0, PARAM_INT);
  $log->grade_2 = optional_param('grade_2', 0, PARAM_INT);
  $log->grade_3 = optional_param('grade_3', 0, PARAM_INT);
  $log->grade_4 = optional_param('grade_4', 0, PARAM_INT);
  $log->key0b20 = optional_param('key0b20', 0, PARAM_INT);
  $log->key1b20 = optional_param('key1b20', 0, PARAM_INT);
  $log->key2b20 = optional_param('key2b20', 0, PARAM_INT);
  $log->key3b20 = optional_param('key3b20', 0, PARAM_INT);
// MARSUPIAL ********** MODIFICAT -> wsTracking allow empty grade
// 2012.01.12 @mmartinez
  $log->totalgrade = optional_param('totalgrade', '', PARAM_RAW);
// ********** MODIFICAT
  //$log->totalgrade = optional_param('totalgrade', 0, PARAM_INT);
// ********** FI
  add_to_log(3,10,serialize($log));
  
  $paramresults = '';
  
  //getting credential to call ws tracking
  if ($reg_credential = get_record("lms_ws_credentials", "success", "1"))
  {
      add_to_log(3,11,serialize($reg_credential));

      //get data from the session  
      if ($reg_session = get_record("sessions", 'token', $token))
      {
          $result = new SeguimientoExtendido();

          //if there is activity
          if ($reg_session->activityid != null)
          {
            $result->idActividad = $reg_session->activityid;
            //  OPTIONALS FIELDS
            //$result->ActividadOrden = 0;
            //$result->ActividadTitulo = get_string("activity_1", "tracking");
          }
// XTEC ********* AFEGIT -> Send the received activity post value from activities to the traking web service server
// 2011.09.02 @mmartinez
           elseif (isset($_POST['gradeactivity']) && !empty($_POST['gradeactivity'])){
               $result->idActividad = $_POST['gradeactivity'];
           }
// ********** FI
          //if there is unit
          if ($reg_session->unitid != null)
          {
              $result->idUnidad = $reg_session->unitid;
            //  OPTIONALS FIELDS
            // $result->UnidadOrden = 0;
            // $result->UnidadTitulo = get_string("unit_1", "tracking");
          }
// XTEC ********* AFEGIT -> Send the received unit post value from activities to the traking web service server
// 2011.09.02 @mmartinez
           elseif (isset($_POST['gradeunit']) && !empty($_POST['gradeunit'])){
               $result->idUnidad = $_POST['gradeunit'];
           }
// ********** FI
          
          $result->idCentro = $reg_session->centerid;
          $result->idContenidoLMS = $reg_session->lmscontentid;
          $result->idUsuario = $reg_session->userid;
          $result->SumaPesos = 4;   // four questions

          $result->Resultado = new Resultado();
          //  OPTIONALS FIELDS
// XTEC ********** MODIFICAT -> Send the received state post value from activities to the traking web service server
// 2011.09.01 @mmartinez
          if (isset($_POST['gradestate']) && !empty($_POST['gradestate'])){
              $result->Resultado->Estado = $_POST['gradestate'];
          }
// ********** ORIGINAL
          ////$result->Resultado->Estado = 'FINALIZADO';
// ********** FI

          //$result->Resultado->Intentos = 1;
          //$result->Resultado->MaxIntentos = 1;
          //$result->Resultado->MinCalificacion = 0;
          //$result->Resultado->MaxCalificacion = 100;
          $result->Resultado->Duracion = (time() - $reg_session->addtime);
          $result->Resultado->FechaHoraInicio = $reg_session->addtime;
          $result->Resultado->MaxDuracion = 86400;
          //$result->Resultado->Observaciones = get_string('obs_result', "tracking");   //take out becouse we want to receive it empty
          $result->Resultado->Observaciones = '';
          $result->Resultado->Calificacion = $_POST['totalgrade'];

          //result details
          $result->Detalles = array();

          for($i = 0; $i < 4; $i++)
          {
              $det = new DetalleResultado();
              //  OPTIONALS FIELDS
              //$det->Intentos = 1;
              //$det->MaxIntentos = 1;
              //$det->Peso = 1;
              //$det->MinCalificacion = 0;
              //$det->MaxCalificacion = 100;
              $det->Calificacion = optional_param($detall[$i], 0, PARAM_RAW);
              $det->Descripcion = get_string('desc_detail', "tracking", $i+1);
              //$det->Duracion = 1000;
              //$det->MaxDuracion = 20000;
              //$det->FechaHoraInicio = $reg_session->addtime;
              $det->IdDetalle = '000'.$i;
              $det->IdTipoDetalle = get_string('question', "tracking");

              $qr = (isset($_POST[$questionsresults[$i]])) ? $_POST[$questionsresults[$i]] : '';
              $rq = "q".$i."=".$qr;
              //$det->URLVerResultados = $reg_session->urlcontent."&".$rq;

              if ($paramresults != '')
                $paramresults = $paramresults."&";
                
              $paramresults = $paramresults.$rq;
              
              array_push($result->Detalles, $det);
          }
          
          $result->Resultado->URLVerResultados = $reg_session->urlcontent."&".$paramresults;

          /// save log registering the data result for LMS tracking
          $log = new stdClass();
          $log->idActividad = $result->idActividad;
          $log->ActividadOrden = $result->ActividadOrden;
          $log->ActividadTitulo = $result->ActividadTitulo;
          $log->idUnidad = $result->idUnidad;
          $log->UnidadOrden = $result->UnidadOrden;
          $log->UnidadTitulo = $result->UnidadTitulo;
          $log->idCentro = $result->idCentro;
          $log->idContenidoLMS = $result->idContenidoLMS;
          $log->idUsuario = $result->idUsuario;
          $log->SumaPesos = $result->SumaPesos;
          $log->Duracion = $result->Resultado->Duracion;
          $log->FechaHoraInicio = $result->Resultado->FechaHoraInicio;
          $log->MaxDuracion = $result->Resultado->MaxDuracion;
          $log->Observaciones = $result->Resultado->Observaciones;
          $log->Calificacion = $result->Resultado->Calificacion;
          $log->URLVerResultados = $result->Resultado->URLVerResultados;

          add_to_log(3, 12, serialize(addslashes_object($log)));
        
          /// save log registering the number of details
          add_to_log(3, 13, serialize(count($result->Detalles)));
          $params = new ResultadoDetalleExtendido();
          $params->ResultadoExtendido = new SoapVar($result, SOAP_ENC_OBJECT, "SeguimientoExtendido", "http://educacio.gencat.cat/agora/seguimiento/");      

          $client = new soapclient($reg_session->wsurltracking.'?wsdl', array('trace' => 1));

          $auth = array('User' => $reg_credential->username, 'Password' => $reg_credential->password);
            
          $namespace=rcommond_wdsl_parser($reg_session->wsurltracking.'?wsdl');

          $header = new SoapHeader($namespace, "WSEAuthenticateHeader", $auth);
          $client->__setSoapHeaders(array($header)); 

          /// save log registering the call to ws tracking
            add_to_log(3, 14);

            try{
          $response = $client->__soapCall("ResultadoDetalleExtendido", array($params));
            } catch (Exception $e) {
              print_r($e);
            }
          //delete session information
          if ($response->ResultadoDetalleExtendidoResult->Resultado == 'OK'){
// XTEC *********** DELETED -> Take out the session delletion to keep it in hierarchical books
// 2011.09.02 @mmartinez
            //delete_records("sessions", 'token', $token);
// ********** FI
            echo "OK";
          } else {
            $descError = "WS response error: ".$response->ResultadoDetalleExtendidoResult->DetalleError->Codigo." - ".$response->ResultadoDetalleExtendidoResult->DetalleError->Descripcion;  //have to add it to idiomatic files
            echo $descError;
            add_to_log(3, '3-1',serialize($descError), true); // have to add a error id and put serialize($descError) 
          }
      } 
      else
      {
        echo get_string("withoutsession", "tracking");
        add_to_log(3, '3-2', serialize($token), true);
      }
  }
  else
  {
     echo get_string("withoutcredentials", "tracking");
     add_to_log(3, '3-3', serialize("withoutcredentials"), true);
  }

  
  
?>
