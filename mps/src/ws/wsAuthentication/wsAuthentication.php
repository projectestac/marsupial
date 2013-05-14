<?php
// IECISA -> MPS ********** ADDED -> create new web service

    /// load libraries
    require_once('../../../config.php');
    require_once($CFG->dirroot.'/ws/lib.php'); 
	
    if (!isset($_GET['wsdl'])){
    	//save log registering the call to the ws server
	    add_to_log(1,1);
	    if ($CFG->debugmode){
	        /// save log registering the xml received headers
	        add_to_log(1,'1-1',serialize($HTTP_RAW_POST_DATA),true);
	    }
    }
    
	function generate_wsdl()
	{
		global $CFG;
		
		if(!is_file("{$CFG->dataroot}/1/WebServices/wsAuthentication/wsAuthentication.wsdl"))
		{
			//save log registering the wsdl generation
			if ($CFG->debugmode && !isset($_GET['wsdl'])){
			    add_to_log(1,'1-2','',true); //debug mode
			}
			
			$strwsdl='<?xml version="1.0" encoding="UTF-8"?>
			<definitions xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://educacio.gencat.cat/proveedores/autenticacion/" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns="http://schemas.xmlsoap.org/wsdl/" targetNamespace="http://educacio.gencat.cat/proveedores/autenticacion/">
			<types>
			<xsd:schema targetNamespace="http://educacio.gencat.cat/proveedores/autenticacion/">
			 <xsd:import namespace="http://schemas.xmlsoap.org/soap/encoding/" />
			 <xsd:import namespace="http://schemas.xmlsoap.org/wsdl/" />
			 <xsd:element name="WSEAuthenticateHeader" type="tns:WSEAuthenticateHeader" />
			   <xsd:complexType name="WSEAuthenticateHeader">
			   <xsd:sequence>
				 <xsd:element minOccurs="0" maxOccurs="1" name="User" type="xsd:string" />
				 <xsd:element minOccurs="0" maxOccurs="1" name="Password" type="xsd:string" />
			   </xsd:sequence>
			   <xsd:anyAttribute />
			 </xsd:complexType>
			 <xsd:complexType name="AutenticarUsuarioContenido">
			  <xsd:all>
			   <xsd:element name="Credencial" type="xsd:string" minOccurs="1" maxOccurs="1"/>
			   <xsd:element name="ISBN" type="xsd:string" minOccurs="1" maxOccurs="1"/>
			   <xsd:element name="IdUsuario" type="xsd:string" minOccurs="1" maxOccurs="1"/>
			   <xsd:element name="NombreApe" type="xsd:string" minOccurs="0" maxOccurs="1"/>
			   <xsd:element name="IdGrupo" type="xsd:string" minOccurs="0" maxOccurs="1"/>';
//XTEC *********** AFEGIT -> Add parameter Rol to the WSDL definition
//2011.05.17 @mmartinez
			   $strwsdl .= '<xsd:element name="Rol" type="tns:TipoRol" default="ESTUDIANTE" minOccurs="0" maxOccurs="1"/>';
//********** FI
			   $strwsdl .= '<xsd:element name="IdCurso" type="xsd:string" minOccurs="1" maxOccurs="1"/>
			   <xsd:element name="IdCentro" type="xsd:string" minOccurs="1" maxOccurs="1"/>
			   <xsd:element name="URLResultado" type="xsd:string" minOccurs="0" maxOccurs="1"/>
			   <xsd:element name="IdContenidoLMS" type="xsd:string" minOccurs="0" maxOccurs="1"/>
			   <xsd:element name="IdUnidad" type="xsd:string" minOccurs="0" maxOccurs="1"/>
			   <xsd:element name="IdActividad" type="xsd:string" minOccurs="0" maxOccurs="1"/>
			  </xsd:all>
			 </xsd:complexType>';
//XTEC *********** AFEGIT -> Add definition TipoRol to the WSDL
//2011.05.17 @mmartinez
			 $strwsdl .= '<xsd:simpleType name="TipoRol">
                 <xsd:restriction base="xsd:string">
                     <xsd:enumeration value="ESTUDIANTE"/>
                     <xsd:enumeration value="PROFESOR"/>
                 </xsd:restriction>
             </xsd:simpleType>';
//********** FI
			 $strwsdl .= '<xsd:complexType name="Licencia">
			  <xsd:all>
			   <xsd:element name="Codigo" type="xsd:string" minOccurs="0" maxOccurs="1"/>
			   <xsd:element name="Descripcion" type="xsd:string" minOccurs="0" maxOccurs="1"/>
			   <xsd:element name="URL" type="xsd:string" minOccurs="0" maxOccurs="1"/>
			  </xsd:all>
			 </xsd:complexType>
			 <xsd:complexType name="AutenticarUsuarioContenidoResponse">
			  <xsd:all>
			   <xsd:element name="AutenticarUsuarioContenidoResult" type="tns:Licencia"/>
			  </xsd:all>
			 </xsd:complexType>
			</xsd:schema>
			</types>
			<message name="AutenticarUsuarioContenidoRequest">
			  <part name="AutenticarUsuarioContenido" type="tns:AutenticarUsuarioContenido" /></message>
			<message name="AutenticarUsuarioContenidoResponse">
			  <part name="return" type="tns:AutenticarUsuarioContenidoResponse" /></message>
			<wsdl:message name="AutenticarUsuarioContenidoWSEAuthenticateHeader">
			  <part name="WSEAuthenticateHeader" element="tns:WSEAuthenticateHeader" />
			</wsdl:message>
			<portType name="ws_authenticationPortType">
			  <operation name="AutenticarUsuarioContenido">
				<documentation>Retorna una URL de acceso al libro digital a partir de una credencial válida para ese libro.&lt;br /&gt;&lt;br /&gt;
        Parámetros: &lt;br /&gt;&lt;br /&gt;
         - Credencial = Código de credencial del usuario para ese libro. &lt;br /&gt;
         - ISBN = Código ISBN del libro digital al que se solicita acceso. &lt;br /&gt;
         - IdUsuario = Identificador único del usuario dentro del EVA. Longitud máxima de 20 caracteres. &lt;br /&gt;
         - NombreApe = Nombre y apellidos del usuario. Longitud máxima de 50 caracteres. &lt;br /&gt;
         - IdGrupo = Identificador del grupo del EVA del colegio desde donde se está solicitando el contenido. Longitud máxima de 30 caracteres. &lt;br /&gt;
         - IdCurso = Identificador del curso del EVA del colegio desde donde se está solicitando el contenido. Longitud máxima de 30 caracteres. &lt;br /&gt;
         - IdCentro = Identificador único que describe al colegio dentro del EVA. Longitud máxima de 100 caracteres. &lt;br /&gt;
         - URLResultado = Url del servicio al que se retorna el seguimiento de las actividades del libro.&lt;br /&gt;
         - IdContenidoLMS = Identificador del contenido en el EVA.&lt;br /&gt;
         - IdUnidad = Identificador de la unidad, acceso directo a una página del libro digital (donde solo se cargará la unidad seleccionada).&lt;br /&gt;
         - IdActividad = Identificador de una actividad del repositorio de contenido de la editorial. Esta llamada sirve como un acceso directo a esa actividad.&lt;br /&gt;&lt;br /&gt;
        Retorna: &lt;br /&gt;&lt;br /&gt;
         * Código (string) / Descripción (string) / URL (string) &lt;br /&gt;
             - (1): Proceso correcto / URL del libro devuelta correctamente. &lt;br /&gt;
             - (0): Error inesperado / URL de excepciones. &lt;br /&gt;
             - (-1): Error al realizar la URL dinámica / URL de excepciones. &lt;br /&gt;
             - (-2): El código de credencial no es válido / URL de excepciones. &lt;br /&gt;
             - (-3): El ISBN del producto no es válido / URL de excepciones. &lt;br /&gt;
             - (-4): La credencial ha expirado / URL de excepciones. &lt;br /&gt;
             - (-5): El identificador de la unidad no es válido / URL de excepciones. &lt;br /&gt;
             - (-6): El identificador de la actividad no es válido / URL de excepciones. &lt;br /&gt;
             - (-7): Rol incorrecto. El valor del rol es incorrecto / URL de excepciones. &lt;br /&gt;
             - (-101): Autenticación incorrecto. El usuario que solicita acceso a este método del servicio Web no es correcto. &lt;br /&gt;
             - (-102): Autenticación incorrecto. El usuario que solicita acceso a este método del servicio Web no tiene permisos.&lt;br /&gt;&lt;br /&gt;</documentation>
				<input message="tns:AutenticarUsuarioContenidoRequest"/>
				<output message="tns:AutenticarUsuarioContenidoResponse"/>
			  </operation>
			</portType>
			<binding name="ws_authenticationBinding" type="tns:ws_authenticationPortType">
			  <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>
			  <operation name="AutenticarUsuarioContenido">
				<soap:operation soapAction="http://educacio.gencat.cat/proveedores/autenticacion/#AutenticarUsuarioContenido" style="rpc"/>
				<input>
					<soap:body use="literal" namespace="http://educacio.gencat.cat/proveedores/autenticacion/"/>
					<soap:header message="tns:AutenticarUsuarioContenidoWSEAuthenticateHeader" part="WSEAuthenticateHeader" use="literal" />
				</input>
				<output><soap:body use="literal" namespace="http://educacio.gencat.cat/proveedores/autenticacion/"/></output>
			  </operation>
			</binding>
			<service name="ws_authentication">
			  <port name="ws_authenticationPort" binding="tns:ws_authenticationBinding">
				<soap:address location="'.$CFG->wwwroot.'/ws/wsAuthentication/wsAuthentication.php"/>
			  </port>
			</service>
			</definitions>';

			if(!is_dir("{$CFG->dataroot}/1"))
			{
				mkdir("{$CFG->dataroot}/1");
			}
			if(!is_dir("{$CFG->dataroot}/1/WebServices"))
			{
				mkdir("{$CFG->dataroot}/1/WebServices");
			}
			if(!is_dir("{$CFG->dataroot}/1/WebServices/wsAuthentication"))
			{
				mkdir("{$CFG->dataroot}/1/WebServices/wsAuthentication");
			}
			$f=fopen("{$CFG->dataroot}/1/WebServices/wsAuthentication/wsAuthentication.wsdl","w");
			fwrite($f,$strwsdl);
			fclose($f);
		}
	}
    
	class AutenticarUsuarioContenidoResponse
	{
		public $AutenticarUsuarioContenidoResult;
	}

	class Licencia
	{
		public $Codigo;
		public $Descripcion;
		public $URL;
	}

    /// define methods as a php function
	function AutenticarUsuarioContenido($usrcontent) 
    {
    	global $CFG;
    	
    	//save log registering the request data
	    add_to_log(1,10,serialize($usrcontent));

        $result = new AutenticarUsuarioContenidoResponse();
        
        $auth = UserAuthentication($GLOBALS["HTTP_RAW_POST_DATA"]);

        if ($auth->Codigo == '1')
        {
           //if book exists
           if ($book = get_record("books_credentials", 'isbn', $usrcontent->ISBN))
           {
               //if credential of the book exists
               if ($book_credential = get_record("books_credentials", 'isbn', $usrcontent->ISBN, 'credentials', $usrcontent->Credencial)){
                   $result->AutenticarUsuarioContenidoResult->Codigo = $book_credential->code;
                   $result->AutenticarUsuarioContenidoResult->Descripcion = $book_credential->description;
                   $result->AutenticarUsuarioContenidoResult->URL = $book_credential->url;
                   
//XTEC *********** AFEGIT -> Check if isset parameter Rol and if one off the tow allowed values
//2011.05.16  @mmartinez
                   if (isset($usrcontent->Rol)){
                   	   $alloweb_values = array("ESTUDIANTE", "PROFESOR");
                   	   if (!in_array($usrcontent->Rol, $alloweb_values)){
                   	   	   $result->AutenticarUsuarioContenidoResult->Codigo = "-7";
                           $result->AutenticarUsuarioContenidoResult->Descripcion = "Rol incorrecte. El valor del rol &eacute;s incorrecte";
                           $result->AutenticarUsuarioContenidoResult->URL = "http://www.xtec.cat/error.html";
                           return $result;
                   	   }
                   } else {
                   	   $usrcontent->Rol = "ESTUDIANTE";
                   }
//*********** FI
                   
                   if ($book_credential->success == 1){
                   
	                   /// get the absolute book path
	                   $path = $CFG->wwwroot.'/data/books/';                   
	                   if ($usrcontent->IdUnidad == '' && $usrcontent->IdActividad == ''){
	                   	   if (!$bookpath = get_record('books', 'isbn', $usrcontent->ISBN)){
	                   	   	   //save log error becouse the ISBN it's not found in db
	                   	   	   add_to_log(1, '1-200', serialize(array('ISBN' => $usrcontent->ISBN)), true);
	                   	   }
	                   	   else{
	                   	   	   /// manipulate the manifest to set href's absolutes
	                   	   	   if ($bookpath->format == 'scorm'){
		                   	   	   if (!manifest_manipulation ($bookpath->path)){
		                   	   	   	   add_to_log (1, '1-201', serialize(array('ISBN' => $usrcontent->ISBN, 'path' => $bookpath->path)), true);
		                   	   	   }
	                   	       }
	                           
	                   	   	   /// set the absolute path to the manifest
	                   	   	   $result->AutenticarUsuarioContenidoResult->URL = $path.$bookpath->path;
	                   	   }
	                   }
	                   /// get the absolute unit path
	                   else if ($usrcontent->IdUnidad != '' && $usrcontent->IdActividad == ''){
	                       if (!$bookpath = get_record('books', 'isbn', $usrcontent->ISBN)){
	                   	   	   //save log error becouse the ISBN it's not found in db
	                   	   	   add_to_log(1, '1-210', serialize(array('ISBN' => $usrcontent->ISBN)), true);
	                   	   }
	                   	   else{
	                   	   	   if (!$unitpath = get_record('books_units', 'bookid', $bookpath->id, 'code', $usrcontent->IdUnidad)){
	                   	   	       //save log error becouse the Unit code it's not found in db
	                   	   	       add_to_log(1, '1-211', serialize(array('ISBN' => $usrcontent->ISBN, 'unitcode' => $usrcontent->IdUnidad)), true);
	                   	   	       $result->AutenticarUsuarioContenidoResult->Codigo = "-5";
	                               $result->AutenticarUsuarioContenidoResult->Descripcion = "L'identificador de la unitat no &eacute;s v&agrave;lid";
	                               $result->AutenticarUsuarioContenidoResult->URL = "http://www.xtec.cat/error.html";
	                               return $result;
	                   	   	   }
	                   	   	   else {
		                   	   	   /// manipulate the manifest to set href's absolutes
		                   	   	   if ($bookpath->format == 'scorm'){
			                   	   	   if (!manifest_manipulation ($unitpath->path)){
			                   	   	   	   add_to_log (1, '1-212', serialize(array('ISBN' => $usrcontent->ISBN, 'unitcode' => $usrcontent->IdUnidad, 'path' => $unitpath->path)), true);
			                   	   	   }
		                   	       }
		                   	   	   /// set the absolute path to the manifest
		                   	   	   $result->AutenticarUsuarioContenidoResult->URL = $path.$unitpath->path;
	                   	   	   }
	                   	   }                   	
	                   }
	                   /// get the absolute activity path
	                   else if ($usrcontent->IdUnidad != '' && $usrcontent->IdActividad != ''){
	                       if (!$bookpath = get_record('books', 'isbn', $usrcontent->ISBN)){
	                   	   	   //save log error becouse the ISBN it's not found in db
	                   	   	   add_to_log(1, '1-220', serialize(array('ISBN' => $usrcontent->ISBN)), true);
	                   	   }
	                   	   else{
	                   	   	   if (!$unitpath = get_record('books_units', 'bookid', $bookpath->id, 'code', $usrcontent->IdUnidad)){
	                   	   	       //save log error becouse the Unit code it's not found in db
	                   	   	       add_to_log(1, '1-221', serialize(array('ISBN' => $usrcontent->ISBN, 'unitcode' => $usrcontent->IdUnidad)), true);
	                   	   	       $result->AutenticarUsuarioContenidoResult->Codigo = "-5";
	                               $result->AutenticarUsuarioContenidoResult->Descripcion = "L'identificador de la unitat no &eacute;s v&agrave;lid";
	                               $result->AutenticarUsuarioContenidoResult->URL = "http://www.xtec.cat/error.html";
	                               return $result;
	                   	   	   }
	                   	   	   else {
	                   	   	   	   if (!$activitypath = get_record('books_activities', 'bookid', $bookpath->id, 'unitid', $unitpath->id,'code', $usrcontent->IdActividad)){
	                   	   	   	       add_to_log (1, '1-222', serialize(array('ISBN' => $usrcontent->ISBN, 'unitcode' => $usrcontent->IdUnidad, 'activitycode' => $usrcontent->IdActividad, 'path' => $activitypath->path)), true);
	                   	   	   	       $result->AutenticarUsuarioContenidoResult->Codigo = "-6";
		                               $result->AutenticarUsuarioContenidoResult->Descripcion = "L'identificador de la activitat no &eacute;s v&agrave;lid ";
		                               $result->AutenticarUsuarioContenidoResult->URL = "http://www.xtec.cat/error.html";
		                               return $result;
	                   	   	       } else {
			                   	   	   /// manipulate the manifest to set href's absolutes
			                   	   	   if ($bookpath->format == 'scorm'){
				                   	   	   if (!manifest_manipulation ($activitypath->path)){
				                   	   	   	   add_to_log (1, '1-223', serialize(array('ISBN' => $usrcontent->ISBN, 'unitcode' => $usrcontent->IdUnidad, 'path' => $activitypath->path)), true);
				                   	   	   }
			                   	       }
			                   	   	   /// set the absolute path to the manifest
			                   	   	   $result->AutenticarUsuarioContenidoResult->URL = $path.$activitypath->path;
	                   	   	   	   }
	                   	   	   }
	                   	   }
	                   	
	                   }
	                   /// if no path is found send the generic one
	                   else{ 
	                   	   add_to_log(1,'1-204',serialize(array('ISBN' => $book_credencial->ISBN)));
	                       $result->AutenticarUsuarioContenidoResult->URL=$book_credential->url;
	                   }
	                   
	//GAP
	//********** AFEGIT XTEC - if URL generated correctly, generates the token and saves the data in the session table
	
	                   if ($result->AutenticarUsuarioContenidoResult->Codigo == 1)
	                   {
	                       if (isset($bookpath->format) and $bookpath->format == 'webcontent' and !isset($_GET['wsdl']))
	                       {
	                           $session = new stdClass();
	                           $session->token = str_replace('.', '', uniqid('', true));
	                           $session->isbn = $usrcontent->ISBN;
	                           $session->userid = $usrcontent->IdUsuario;
	                           $session->nameape = $usrcontent->NombreApe;
	                           $session->groupid = $usrcontent->IdGrupo;
	                           $session->courseid = $usrcontent->IdCurso;
	                           $session->centerid = $usrcontent->IdCentro;
	                           $session->wsurltracking = $usrcontent->URLResultado;
	                           $session->lmscontentid = $usrcontent->IdContenidoLMS;
	                           $session->unitid = $usrcontent->IdUnidad;
	                           $session->activityid = $usrcontent->IdActividad;
	                           $session->addtime = time();
	                           $session->expiretime = time() + 86400;  //expire in 24 hours
							   $session->urlcontent = $result->AutenticarUsuarioContenidoResult->URL."?token={$session->token}";
	                           
	                           $session = addslashes_object($session);
	
	                           $result->AutenticarUsuarioContenidoResult->URL = $result->AutenticarUsuarioContenidoResult->URL."?token={$session->token}";
	                           
	                           insert_record("sessions", $session);
	                       }
	                   }
                   }
//**********
                   
               }
               else{
                   $result->AutenticarUsuarioContenidoResult->Codigo = '-2';
                   $result->AutenticarUsuarioContenidoResult->Descripcion = 'El codi de llicencia no es vàlid.';
                   $result->AutenticarUsuarioContenidoResult->URL = 'http://www.xtec.cat/error.html';
               }
           }
           else{
               $result->AutenticarUsuarioContenidoResult->Codigo = '-3';
               $result->AutenticarUsuarioContenidoResult->Descripcion = 'El ISBN del producte no es vàlid.';
               $result->AutenticarUsuarioContenidoResult->URL = 'http://www.xtec.cat/error.html';
           }
        }
        else
        {
            $result->AutenticarUsuarioContenidoResult->Codigo = $auth->Codigo;
            $result->AutenticarUsuarioContenidoResult->Descripcion = $auth->Descripcion;      
            $result->AutenticarUsuarioContenidoResult->URL = $auth->url;
			
        }
        /// save log registering method response
        add_to_log(1,20,serialize($result->AutenticarUsuarioContenidoResult));
        
        return $result;
    }
    
    

    function UserAuthentication($post_data)
    {
        global $CFG;
		
        $retAut->Codigo = '-101';
        $retAut->Descripcion = 'Usuari/contrasenya errònies';
        
        $post = rcommon_xml2array($post_data);
        
    	if ($CFG->debugmode){
	    	/// save log registering the xml received headers
	        add_to_log(1,'1-11',serialize(rcommond_findarrayvalue($post, array("Envelope", "Header", "WSEAuthenticateHeader"))),true);
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
		    add_to_log(1,11,serialize($data_to_log));
            
            if ($creden_usr = get_record_sql("select * from {$CFG->prefix}lms_ws_credentials where username = '{$user_pub}' and password = '{$pwr_pub}'"))
            {
                $retAut->Codigo = $creden_usr->code;
                $retAut->Descripcion = $creden_usr->description;
            }
        }
        
        /// save log with headers auth return
		add_to_log(1,21,serialize($retAut));
        
        return $retAut;
    }

    generate_wsdl();

    ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache

    global $CFG;

    $server = new SoapServer("{$CFG->dataroot}/1/WebServices/wsAuthentication/wsAuthentication.wsdl", array('soap_version' => SOAP_1_1));       

    $server->addFunction("AutenticarUsuarioContenido");

    $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

    $resp = $server->handle();
    
    if (!isset($_GET['wsdl'])){
	    add_to_log(1,100);
	    die;
    }
    
    /*
     * function that set to absolute all the href
     * 
     *@param   string  $path -> relative path to the manifest.xml
     *@return  bool    action finish ok or ko
     */ 
    function manifest_manipulation ($path){
    	
    	global $CFG;
    	
    	$dirpath = $CFG->dirroot.'/data/books/'.$path;
    	$path = $CFG->wwwroot.'/data/books/'.$path;
    	
        $topath = '';
	    $separator = '/';
	    $frompath = split($separator,$path);
	    for ($i=0; $i<(count($frompath)-1); $i++){
	    	$topath .= $frompath[$i].$separator;    		
	    }
    	
	    if ($handle = fopen($dirpath, "r")){	    	
    	
	        $buffer = '';
	        while(!feof($handle)){
	        	$buffer .= fgets($handle, 4096);
	        }
	        
	        $buffer = split('href="', $buffer);
	        $return = $buffer[0];
	        for ($i=1; $i<count($buffer); $i++){
	        	/// get relative url
	        	$stpos = strpos($buffer[$i],'"');
	        	$relurl = substr($buffer[$i],0,$stpos);
	        	/// transform relative url to absolute
	        	if (substr($relurl,0,7) == "http://"){
	        	    $relurl = split($separator,$relurl);
	        	    $relurl = $relurl[count($relurl)-1];
	        	}
	        	$relurl = $topath.$relurl;
	        	/// set actuall row to return parameter
	        	$return .= 'href="'.$relurl.substr($buffer[$i],$stpos,strlen($buffer[$i]));
	        }
	        
	        fclose($handle);
	        
	        $handle = fopen($dirpath, "w+");
	        fwrite($handle,$return);
	        fclose($handle);
	        
	        return true;
	    }else{
	    	add_to_log(1, '1-202', serialize($path), true);
	    }
	    add_to_log(1, '1-203', serialize($path), true);
	    return false;
    }
	
// ************ END
?>