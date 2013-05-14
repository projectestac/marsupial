<?php #!/bin/sh

// IECISA -> MPS ********** ADDED -> cron to clean log

    /// load libraries
    include_once('../../../config.php');

    /// truncate log table
    if (!delete_records('log')){
    	add_error_log (4, '4-1', serialize(time()), true);
		log_to_file("Action 4-1 KO!");
    } else{
	    log_to_file("Action 4-1 OK!");
	}
	
	/// truncate sessions table
	if (!delete_records('sessions')){
    	add_error_log (4, '4-2', serialize(time()), true);
		log_to_file("Action 4-2 KO!");
    } else{
	    log_to_file("Action 4-2 OK!");
	}
	
	function log_to_file($info)
	{
		global $CFG;

		 $directorio_log = $CFG->dirroot."/application/admin/log";

		//Escribimos en un fichero de textos los mensajes de errores
		if(!is_dir($directorio_log))
		   mkdir($directorio_log);
		
		 if ($handle = @fopen($directorio_log."/cron.log", "a")) 
		 {
			 $content = "\r\n".date("Y-m-d H:i:s")." - Succes: ".$info;
			 
			 @fwrite($handle,$content);
			 @fclose($handle);
		 }
		 
	}
// ********** END