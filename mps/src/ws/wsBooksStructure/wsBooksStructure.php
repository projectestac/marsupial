<?php
// IECISA -> MPS ********** ADDED -> create new web service
  
	/// load libraries
	require_once('../../../config.php');
	require_once($CFG->dirroot.'/ws/lib.php'); 
	
	if (!isset($_GET['wsdl'])){
		//save log registering the call to the ws server
	    add_to_log(2,1);
	    if ($CFG->debugmode){
	        /// save log registering the xml received headers
	        add_to_log(2,'2-1',serialize($HTTP_RAW_POST_DATA),true);
	    }
	}
	
	function generate_wsdl()
	{
	    global $CFG;
	    
	    if(!is_file("$CFG->dataroot/1/WebServices/wsBooksStructure/wsBooksStructure.wsdl"))
	    {
	    	//save log registering the wsdl generation
	    	if ($CFG->debugmode && !isset($_GET['wsdl'])){
	            add_to_log(2,'2-2','',true);
	    	}
	        
			$strwsdl='<?xml version="1.0" encoding="UTF-8"?><!-- Published by JAX-WS RI at http://jax-ws.dev.java.net. RI"s version is JAX-WS RI 2.1.7-b01-. --><wsdl:definitions xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tns="http://educacio.gencat.cat/agora/estructuralibros/" targetNamespace="http://educacio.gencat.cat/agora/estructuralibros/"> 
			  <wsdl:types>
			    <xs:schema elementFormDefault="qualified" targetNamespace="http://educacio.gencat.cat/agora/estructuralibros/">
			      <xs:complexType name="Actividad">
			        <xs:sequence>
			          <xs:element minOccurs="1" maxOccurs="1" name="id" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="titulo" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="orden" type="xs:int" />
			        </xs:sequence>
			      </xs:complexType>
			      <xs:complexType name="Actividades">
			        <xs:sequence>
			          <xs:element minOccurs="1" maxOccurs="unbounded" name="actividad" type="tns:Actividad" />
			        </xs:sequence>
			      </xs:complexType>
			      <xs:complexType name="Unidad">
			        <xs:sequence>
			          <xs:element minOccurs="1" maxOccurs="1" name="id" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="titulo" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="orden" type="xs:int" />
			          <xs:element minOccurs="0" maxOccurs="1" name="actividades" type="tns:Actividades" />
			        </xs:sequence>
			      </xs:complexType>
			      <xs:complexType name="Unidades">
			        <xs:sequence>
			          <xs:element minOccurs="1" maxOccurs="unbounded" name="unidad" type="tns:Unidad" />
			        </xs:sequence>
			      </xs:complexType>
			      <xs:complexType name="libro">
			        <xs:sequence>
			          <xs:element minOccurs="1" maxOccurs="1" name="ISBN" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="titulo" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="nivel" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="formato" type="xs:string" />
			          <xs:element minOccurs="0" maxOccurs="1" name="unidades" type="tns:Unidades" />
			        </xs:sequence>
			      </xs:complexType>
			      <xs:complexType name="libros">
			        <xs:sequence>
			          <xs:element minOccurs="1" maxOccurs="unbounded" name="libro" type="tns:libro" />
			        </xs:sequence>
			      </xs:complexType>
			      <xs:element name="WSEAuthenticateHeader" type="tns:WSEAuthenticateHeader" />
			      <xs:complexType name="WSEAuthenticateHeader">
			        <xs:sequence>
			          <xs:element minOccurs="0" maxOccurs="1" name="User" type="xs:string" />
			          <xs:element minOccurs="0" maxOccurs="1" name="Password" type="xs:string" />
			        </xs:sequence>
			        <xs:anyAttribute />
			      </xs:complexType>
			      <xs:element name="ObtenerEstructura">
			        <xs:complexType>
			          <xs:sequence>
			            <xs:element minOccurs="1" maxOccurs="1" name="ISBN" type="xs:string" />
			          </xs:sequence>
			        </xs:complexType>
			      </xs:element>
			      <xs:element name="ObtenerEstructuraResponse">
			        <xs:complexType>
			          <xs:sequence>
			            <xs:element minOccurs="0" maxOccurs="1" name="ObtenerEstructuraResult" type="tns:EstructuraLibro" />
			          </xs:sequence>
			        </xs:complexType>
			      </xs:element>
			      <xs:complexType name="EstructuraLibro">
			        <xs:sequence>
			          <xs:element minOccurs="0" maxOccurs="1" name="Libros" type="tns:libros" />
			          <xs:element minOccurs="1" maxOccurs="1" name="Codigo" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="Descripcion" type="xs:string" />
			        </xs:sequence>
			      </xs:complexType>
			      <xs:element name="ObtenerTodos">
			        <xs:complexType>
			          <xs:sequence>
			            <xs:element minOccurs="0" maxOccurs="1" name="IdCentro" type="xs:string" />
			          </xs:sequence>
			        </xs:complexType>
			      </xs:element>
			      <xs:element name="ObtenerTodosResponse">
			        <xs:complexType>
			          <xs:sequence>
			            <xs:element minOccurs="0" maxOccurs="1" name="ObtenerTodosResult" type="tns:EstructuraCatalogo" />
			          </xs:sequence>
			        </xs:complexType>
			      </xs:element>
			      <xs:complexType name="EstructuraCatalogo">
			        <xs:sequence>
			          <xs:element minOccurs="0" maxOccurs="1" name="Catalogo">
			            <xs:complexType>
			              <xs:sequence>
			                <xs:element minOccurs="1" maxOccurs="1" name="libros" type="tns:libros" />
			              </xs:sequence>
			            </xs:complexType>
			          </xs:element>
			          <xs:element minOccurs="1" maxOccurs="1" name="Codigo" type="xs:string" />
			          <xs:element minOccurs="1" maxOccurs="1" name="Descripcion" type="xs:string" />
			        </xs:sequence>
			      </xs:complexType>
			      <xs:complexType name="DetallesError">
			        <xs:annotation>
			          <xs:documentation>Detalles del motivo del error producido en el tratamiento del servicio web realizado</xs:documentation>
			        </xs:annotation>
			        <xs:sequence>
			          <xs:element name="Codigo" type="xs:int">
			            <xs:annotation>
			              <xs:documentation>Código del error</xs:documentation>
			            </xs:annotation>
			          </xs:element>
			          <xs:element name="Descripcion" type="xs:string">
			            <xs:annotation>
			              <xs:documentation>Descripción detallada del error</xs:documentation>
			            </xs:annotation>
			          </xs:element>
			          <xs:element name="Observaciones" minOccurs="0">
			            <xs:annotation>
			              <xs:documentation>Observaciones/descripción ampliada del error</xs:documentation>
			            </xs:annotation>
			          </xs:element>
			        </xs:sequence>
			      </xs:complexType>
			    </xs:schema>
			  </wsdl:types>
			  <wsdl:message name="ObtenerEstructuraRequest">
			    <wsdl:part name="parameters" element="tns:ObtenerEstructura" />
			  </wsdl:message>
			  <wsdl:message name="ObtenerEstructuraResponse">
			    <wsdl:part name="parameters" element="tns:ObtenerEstructuraResponse" />
			  </wsdl:message>
			  <wsdl:message name="ObtenerEstructuraWSEAuthenticateHeader">
			    <wsdl:part name="WSEAuthenticateHeader" element="tns:WSEAuthenticateHeader" />
			  </wsdl:message>
			  <wsdl:message name="ObtenerTodosRequest">
			    <wsdl:part name="parameters" element="tns:ObtenerTodos" />
			  </wsdl:message>
			  <wsdl:message name="ObtenerTodosResponse">
			    <wsdl:part name="parameters" element="tns:ObtenerTodosResponse" />
			  </wsdl:message>
			  <wsdl:message name="ObtenerTodosWSEAuthenticateHeader">
			    <wsdl:part name="WSEAuthenticateHeader" element="tns:WSEAuthenticateHeader" />
			  </wsdl:message>
			  <wsdl:portType name="EstructuraLibrosPort">
			    <wsdl:operation name="ObtenerEstructura">
			      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
			        Retorna el indice de contenidos de un libro de nuestro catálogo.&lt;br />&lt;br />
			        Parámetros: &lt;br />
			         - ISBN = Código ISBN del producto digital&lt;br />&lt;br />
			        Retorna: &lt;br />
			         * Código (string) / Descripción (string) / Libros (XML) &lt;br />
			         - (1): Proceso correcto. &lt;br />
			         - (0): Error inesperado. &lt;br />
			         - (-3): El ISBN del producto no es válido. &lt;br />
			         - (-101): Autenticación incorrecta. El usuario que solicita acceso a este método del servicio Web no es correcto. &lt;br />
			         - (-102): Autenticación incorrecta. El usuario que solicita acceso a este método del servicio Web no tiene permisos suficientes.&lt;br />&lt;br /></wsdl:documentation>
			      <wsdl:input message="tns:ObtenerEstructuraRequest" />
			      <wsdl:output message="tns:ObtenerEstructuraResponse" />
			    </wsdl:operation>
			    <wsdl:operation name="ObtenerTodos">
			      <wsdl:documentation xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/">
			        Retorna todos los indices de los libros que se están dados de alta como libros &lt;br />
			        accesibles para el usuario autenticado.&lt;br />&lt;br />
			        Retorna: &lt;br />
			         * Código (string) / Descripción (string) / Libros (XML) &lt;br />
			         - (1): Proceso correcto&lt;br />
			         - (0): Error inesperado&lt;br />
			         - (-101): Autenticación incorrecta. El usuario que solicita acceso a este método del servicio Web no es correcto. &lt;br />
			         - (-102): Autenticación incorrecta. El usuario que solicita acceso a este método del servicio Web no tiene permisos suficientes.&lt;br />&lt;br /></wsdl:documentation>
			      <wsdl:input message="tns:ObtenerTodosRequest" />
			      <wsdl:output message="tns:ObtenerTodosResponse" />
			    </wsdl:operation>
			  </wsdl:portType>
			  <wsdl:binding name="EstructuraLibrosBinding" type="tns:EstructuraLibrosPort">
			    <soap:binding transport="http://schemas.xmlsoap.org/soap/http" />
			    <wsdl:operation name="ObtenerEstructura">
			      <soap:operation soapAction="ObtenerEstructura" style="document" />
			      <wsdl:input>
			        <soap:body use="literal" />
			        <soap:header message="tns:ObtenerEstructuraWSEAuthenticateHeader" part="WSEAuthenticateHeader" use="literal" />
			      </wsdl:input>
			      <wsdl:output>
			        <soap:body use="literal" />
			      </wsdl:output>
			    </wsdl:operation>
			    <wsdl:operation name="ObtenerTodos">
			      <soap:operation soapAction="ObtenerTodos" style="document" />
			      <wsdl:input>
			        <soap:body use="literal" />
			        <soap:header message="tns:ObtenerTodosWSEAuthenticateHeader" part="WSEAuthenticateHeader" use="literal" />
			      </wsdl:input>
			      <wsdl:output>
			        <soap:body use="literal" />
			      </wsdl:output>
			    </wsdl:operation>
			  </wsdl:binding>
			  <wsdl:service name="EstructuraLibrosService">
			    <wsdl:port name="EstructuraLibrosPort" binding="tns:EstructuraLibrosBinding">
			      <soap:address location="'.$CFG->wwwroot.'/ws/wsBooksStructure/wsBooksStructure.php" />
			    </wsdl:port>
			  </wsdl:service>
			</wsdl:definitions>';
		
		    if(!is_dir("$CFG->dataroot/1"))
		    {
		        mkdir("$CFG->dataroot/1");
		    }
		    if(!is_dir("$CFG->dataroot/1/WebServices"))
		    {
		        mkdir("$CFG->dataroot/1/WebServices");
		    }
		    if(!is_dir("$CFG->dataroot/1/WebServices/wsBooksStructure"))
		    {
		        mkdir("$CFG->dataroot/1/WebServices/wsBooksStructure");
		    }
		    $f=fopen("$CFG->dataroot/1/WebServices/wsBooksStructure/wsBooksStructure.wsdl","w");
		    fwrite($f,$strwsdl);
		    fclose($f);
		}
	}
	
	
	class ObtenerEstructuraResponse
	{
	    public $ObtenerEstructuraResult;
	}
	
	class ObtenerTodosResponse
	{
	    public $ObtenerTodosResult;
	}
	
	class Catalogo
	{
	    public $libros;
	}
	
	/*class libros
	{
	    public $libros;
	}
	*/
	class libro
	{
	    public $ISBN;
	    public $titulo;
	    public $nivel;
	    public $formato;
	    public $unidades;
	}
	
	/*class unidades
	{
	    public $unidad;
	}
	*/
	class unidad
	{
	    public $id;
	    public $titulo;
	    public $orden;
	    public $actividades;
	}
	
	/*
	class actividades
	{
	    public $actividad;
	}
	*/
	class actividad
	{
	    public $id;
	    public $titulo;
	    public $orden;
	}
	
	
	function RetornaLibros($books_recordset)
	{
	    global $CFG;
	    
	    $books = $books_recordset;
	
	    $arrLibros = array();
	    foreach($books as $b)
	    {
	        $libro_encontrado = new libro();
	        $libro_encontrado->ISBN = $b['isbn'];
	        $libro_encontrado->titulo = $b['name'];
	        $libro_encontrado->nivel = $b['level'];
	        $libro_encontrado->formato = $b['format'];
	        array_push($arrLibros, $libro_encontrado);
	        
//XTEC ************* DELETED -> Take out becouse we just want to send book info, not the structure of each book
// 2011.03.30 @mmartinez
	        /*$arrUnidades = array();
	        if ($units = get_recordset_sql("select * from {$CFG->prefix}books_units where bookid = '{$b['id']}'"))
	        {
	            foreach($units as $u)
	            {
	                $unidad_encontrada = new unidad();
	                $unidad_encontrada->id = $u['code'];
	                $unidad_encontrada->titulo = $u['name'];
	                $unidad_encontrada->orden = $u['sortorder'];
	                array_push($arrUnidades, $unidad_encontrada);
	
	                $arrActividades = array();
	                if ($activ = get_recordset_sql("select * from {$CFG->prefix}books_activities where bookid = '{$b['id']}' and unitid = '{$u['id']}'"))
	                {
	
	                    foreach($activ as $a)
	                    {
	                        $actividad_encontrada = new actividad();
	                        $actividad_encontrada->id = $a['code'];
	                        $actividad_encontrada->titulo = $a['name'];
	                        $actividad_encontrada->orden = $a['sortorder'];
	                        array_push($arrActividades, $actividad_encontrada);
	                    }
	                }
	                if (count($arrActividades) > 0)
	                    $unidad_encontrada->actividades = $arrActividades;
	            }
	        }
	        if (count($arrUnidades) > 0)
	            $libro_encontrado->unidades = $arrUnidades;*/
//************ END

	    }
	
	    return $arrLibros;    
	}

//XTEC ************* ADDED -> Full response when the method called is get book sctructure
// 2011.05.05 @mmartinez
	function RetornaEstructuraLibros($books_recordset)
	{
	    global $CFG;
	    
	    $books = $books_recordset;
	
	    $arrLibros = array();
	    foreach($books as $b)
	    {
	        $libro_encontrado = new libro();
	        $libro_encontrado->ISBN = $b['isbn'];
	        $libro_encontrado->titulo = $b['name'];
	        $libro_encontrado->nivel = $b['level'];
	        $libro_encontrado->formato = $b['format'];
	        array_push($arrLibros, $libro_encontrado);
	        
	        $arrUnidades = array();
	        if ($units = get_recordset_sql("select * from {$CFG->prefix}books_units where bookid = '{$b['id']}'"))
	        {
	            foreach($units as $u)
	            {
	                $unidad_encontrada = new unidad();
	                $unidad_encontrada->id = $u['code'];
	                $unidad_encontrada->titulo = $u['name'];
	                $unidad_encontrada->orden = $u['sortorder'];
	                array_push($arrUnidades, $unidad_encontrada);
	
	                $arrActividades = array();
	                if ($activ = get_recordset_sql("select * from {$CFG->prefix}books_activities where bookid = '{$b['id']}' and unitid = '{$u['id']}'"))
	                {
	
	                    foreach($activ as $a)
	                    {
	                        $actividad_encontrada = new actividad();
	                        $actividad_encontrada->id = $a['code'];
	                        $actividad_encontrada->titulo = $a['name'];
	                        $actividad_encontrada->orden = $a['sortorder'];
	                        array_push($arrActividades, $actividad_encontrada);
	                    }
	                }
	                if (count($arrActividades) > 0)
	                    $unidad_encontrada->actividades = $arrActividades;
	            }
	        }
	        if (count($arrUnidades) > 0)
	            $libro_encontrado->unidades = $arrUnidades;

	    }
	
	    return $arrLibros;    
	}
//************ END
	
	function ObtenerTodos($param)
	{
	    global $CFG;
	    
	    /// save log registering request parameters
	    $data_to_log->idcentro = $param->IdCentro;
	    add_to_log(2,12,serialize($data_to_log));
	    
	    $ret = new ObtenerTodosResponse();
	    
	    $auth = UserAuthentication($GLOBALS["HTTP_RAW_POST_DATA"]);
	    
	    if ($auth->Codigo == '1')
	    {
	        //return all books
	        if (isset($param->IdCentro) && !empty($param->IdCentro) && $center = get_record('center', 'code', $param->IdCentro)){
	        	if ($books = get_records('center_books', 'centerid', $center->id)){
	        		$where = '';
	        		foreach ($books as $book){
	        		    $where .= $book->bookid.',';	        			
	        		}
	        		$where = substr($where, 0, strlen($where)-1);
	        	    $sSql = "SELECT * FROM {$CFG->prefix}books WHERE id IN ({$where})";
	        	} else {
	        		add_to_log (2, '2-100', serialize(array('ISBN' => $param->IdCentro)), true);
	        	}
	        } else {
	            $sSql = "SELECT * FROM {$CFG->prefix}books";
	        }
	
	        $books = get_recordset_sql($sSql);
	
	        $arrLibros = array();
	        $arrLibros = RetornaLibros($books);
	
	        $ret->ObtenerTodosResult->Codigo = "1";
	        $ret->ObtenerTodosResult->Descripcion ="procés correcte";
	        $ret->ObtenerTodosResult->Catalogo = new Catalogo();
	
	        $ret->ObtenerTodosResult->Catalogo->libros = $arrLibros;
	    }
	    else
	    {
	        $ret->ObtenerTodosResult->Codigo = $auth->Codigo;
	        $ret->ObtenerTodosResult->Descripcion = $auth->Descripcion;      
	    }

	    /// save log registering method response
	    add_to_log(2, 22, serialize($ret->ObtenerTodosResult));
	    
	    return $ret;
	
	}
	
	
	function ObtenerEstructura($param)
	{
	    global $CFG;
	    
	    /// save log registering request parameters
	    $data_to_log->ISBN = $param->ISBN;
	    add_to_log(2,10,serialize($data_to_log));
	
	    $ret = new ObtenerEstructuraResponse();
	    
	    $auth = UserAuthentication($GLOBALS["HTTP_RAW_POST_DATA"]);
	    
	    if ($auth->Codigo == '1')
	    {
	        //default return
	        $ret->ObtenerEstructuraResult->Codigo = "0";
	        $ret->ObtenerEstructuraResult->Descripcion = "no s'ha trobat llibre";      
	        
	        if (isset($param))
	        {
	            $sSql = "Select * from {$CFG->prefix}books where isbn = '{$param->ISBN}'";
	
	            if ($books = get_recordset_sql($sSql))
	            {
	                $arrLibros = array();
	                $arrLibros = RetornaEstructuraLibros($books);
	                if (count($arrLibros) > 0)
	                {
	                    $ret->ObtenerEstructuraResult->Codigo = "1";
	                    $ret->ObtenerEstructuraResult->Descripcion = "procés correcte";      
	                    $lib = new libro();
	                    $lib = $arrLibros[0];
	                    $ret->ObtenerEstructuraResult->Libros->libro = $lib;
	                }
	            }
	        }
	    }
	    else
	    {
	        $ret->ObtenerEstructuraResult->Codigo = $auth->Codigo;
	        $ret->ObtenerEstructuraResult->Descripcion = $auth->Descripcion;      
	    }
	
	    /// save log registering method response
	    add_to_log(2, 20, serialize($ret->ObtenerEstructuraResult));
	    
	    return $ret;    
	}
	
	function UserAuthentication($post_data)
	{
	    global $CFG;
	    
	    $retAut->Codigo = '-101';
	    $retAut->Descripcion = 'Usuari/contrasenya errònies';
	    
	    $post = rcommon_xml2array($post_data);
	    
	    if ($CFG->debugmode){
	    	/// save log registering the xml received headers
	        add_to_log(2,'2-10',serialize(rcommond_findarrayvalue($post, array("Envelope", "Header", "WSEAuthenticateHeader"))),true);
	    }
	
	    $keys = array("Envelope", "Header", "WSEAuthenticateHeader", "User", "value");
	    if ($valor = rcommond_findarrayvalue($post, $keys))
	    {
	        $keys = array("Envelope", "Header", "WSEAuthenticateHeader", "User", "value");
	        $user_pub = rcommond_findarrayvalue($post, $keys);
	
	        $keys = array("Envelope", "Header", "WSEAuthenticateHeader", "Password", "value");
	        $pwr_pub = rcommond_findarrayvalue($post, $keys);
	        
	        /// save log with request headers data
		    $data_to_log->user = $user_pub;
			$data_to_log->pwd = $pwr_pub;
		    add_to_log(2,11,serialize($data_to_log));
	        
	        if ($creden_usr = get_record_sql("select * from {$CFG->prefix}lms_ws_credentials where username = '{$user_pub}' and password = '{$pwr_pub}'"))
	        {
	            $retAut->Codigo = $creden_usr->code;
	            $retAut->Descripcion = $creden_usr->description;
	        }
	    }
	    
	    /// save log with headers auth return
        add_to_log(2,21,serialize($retAut));
        
	    return $retAut;
	}
	
	generate_wsdl();
	
	ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
	
	global $CFG;
	
	$server = new SoapServer($CFG->dataroot.'/1/WebServices/wsBooksStructure/wsBooksStructure.wsdl', array('soap_version' => SOAP_1_1));       
	
	$server->addFunction("ObtenerTodos");
	$server->addFunction("ObtenerEstructura");
	
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

	$server->handle();
    
    if (!isset($_GET['wsdl'])){
	    add_to_log(2,100);
    }
    
?>
