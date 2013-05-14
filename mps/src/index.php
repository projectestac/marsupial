<?php  //BASED ON MOODLE 1.9.9. ARQUITECTURE
    
    /// check if the application is already installed
    if (!file_exists('../config.php')) {
        header('Location: install.php');
        die;
    }

    /// load libraries
    require_once('../config.php');

    /// print header
    /// @param string  $title Appears at the top of the window
    /// @param string  $heading Appears at the top of the page
    print_header($CFG->sitename , $CFG->sitename);
	
	echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/application/viewer/viewer.jquery.inc.js"></script>';
	echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/lib/jquery/jquery.scrollTo.js"></script>';
	
	$filter = optional_param('filter', '', PARAM_HOST);
	echo '<script type="text/javascript">
	    var filter = "'.$filter.'";
		var lasttimetext = "'.get_string('lasttimetext').': ";
	</script>';
	
	/// print page content header
    print_container_start(true, 'content-header', 'content-header'); 
    corner_left_top();
    corner_left_bottom();
    corner_right_top();
    corner_right_bottom();
    echo '<form method="post">'.
	    get_string('filter').': <input type="text" name="filter" value="'.$filter.'" maxlength="15">
		<input type="hidden" id="lastid" value="0">
		<input type="submit" value="'.get_string('filterbutton').'"> '.get_string('filterexample','',getremoteaddr()).
	'</form>';
	echo '<div id="lasttimediv" style="float:right; margin-top:-20px;"></div>'; 
    print_container_end();
    
    /// print page content body
    print_container_start(true, 'content-body', 'content-body');
    corner_left_top();
    corner_left_bottom();
    corner_right_top();
    corner_right_bottom();
    echo '<div class="divtable"><table width="100%" height="100%">
	    <tr height="30">
            <th width="110">'.get_string('ip').'</th>
            <th width="150">'.get_string('time').'</th>
            <th width="150">'.get_string('category').'</th>
            <th class="norightborder">'.get_string('info').'</th>
        </tr>
		<tr class="firsttr" id="firsttr">
		    <td colspan="4" class="norightborder"></td>
		</tr>
    </table></div>';
    print_container_end();
    
    /// print footer 
    print_footer();     // Please do not modify this line
?>
