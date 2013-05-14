<?php 

// IECISA -> MPS ************ ADDED -> 

    include_once('../../../config.php');
	
	//check session for security reasons
	
	// get parameters
	$filter = optional_param('filter', '', PARAM_HOST);
	$lasttime = optional_param('lasttime', 0, PARAM_INT);
	$CFG->limitviewentries = (!isset($CFG->limitviewentries) || empty($CFG->limitviewentries))? 600 : (6*$CFG->limitviewentries);
	
	//add_to_log(1, 1, "filter: $filter, lasttime: $lasttime", true);  //debug mode

	// check for the lastes entries in the log
	$where = "id > '$lasttime'";
	if ($filter != ''){
	    $where .= " AND ip = '$filter'";
	}
	
	if (!$count = count_records_select('log', $where)){
		$return = '{"response":""}';
        //add_to_log(1, 1, $return, true); //debug mode			
	    echo $return;
		die;
	}
	$count = ($count>$CFG->limitviewentries)? ($count-$CFG->limitviewentries): 0;
	
	if (!$entries = get_recordset_select('log', $where, 'time ASC', '*', $count, $CFG->limitviewentries)){
	    $return = '{"response":"KO"}';
        //add_to_log(1, 1, $return, true); //debug mode			
	    echo $return;
		die;
	}
	if (!$entries = recordset_to_array($entries)){
	    $return = '{"response":""}';
        //add_to_log(1, 1, $return, true); //debug mode			
	    echo $return; 
		die;
	}
	
	/// set return entries in json format {"response":[{"ip":"","time":"","smarttime":"","category":"","info":""}]}
	$return = '{"response":[';
	foreach ($entries as $entri){
	    /// search for category names
		if (!$category = get_record('categories', 'id', $entri->categoryid)){
		    $return = '{"response":"KO"}';
            //add_to_log(1, 1, $return, true); //debug mode			
			echo $return;
		    die;
		}
		$entri->category = get_string('categoryname', $category->name);
		/// set the info text
		$entri->infotext = get_string($entri->actionid, $category->name, unserialize($entri->info));
		/// print
		$return .= '{"ip":"'.$entri->ip.'","id":"'.$entri->id.'","smarttime":"'.date('d-m-Y H:i:s',$entri->time).'","category":"'.$entri->category.'","info":"'.$entri->infotext.'"},';
	}
	$return = substr($return, 0, strlen($return)-1);
	$return .= ']}';
	
	/// send return
	//add_to_log(1, 1, $return, true); //debug mode
    echo $return;
	die;

// ************ END
?>