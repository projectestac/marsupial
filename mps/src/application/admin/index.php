<?php  //BASED ON MOODLE 1.9.9. ARQUITECTURE
    
    /// check if the application is already installed
    if (!file_exists('../../../config.php')) {
        header('Location: ../../install.php');
        die;
    }

    /// load libraries
    require_once('../../../config.php');
    
    /// check is user is logged in
    if (!isloggedin()){
    	redirect('login.php', get_string('errornosession'), 4);
    };
    
    /// initialize variables
    $messagetext = '';
    
    ///if isset form data process it
    if ($frm = data_submitted()){
    	
    	foreach ($frm as $key=>$value){

    		//sanitize values
    		switch ($key){
    			case 'sitename':{
    				$value = clean_param($value, PARAM_TEXT);
    				break;
    			}
    			case 'limitviewentries':{
    				$value = clean_param($value, PARAM_INT);
    				break;
    			}
    		}
    		
    		$param = new stdClass();
    		$param->key = trim ($key);
    	    $param->value = trim($value);
    	    if ($param->key != "" && ($param->value != "" || $param->value != 0)){
    	    	if ($param->id = get_field('config', 'id', 'name', $param->key)){
    		        if (!update_record('config', $param)){
    		        	$messagetext = get_string('saveko1', '', $key);
    		        } else {
    	                $value = str_replace("\'", "'", $value);
    		        	$CFG->$key = $value;
    		        	$messagetext = get_string('saveok');
    		        }
    	    	} else {
    	    		$messagetext = get_string('saveko2', '', $key);
    	    	}
    	    }
    	    
    	}    	
    }

    /// print header
    /// @param string  $title Appears at the top of the window
    /// @param string  $heading Appears at the top of the page
    print_header($CFG->sitename , $CFG->sitename);
    
    /// print page content header
    print_container_start(true, 'content-header', 'content-header');
    corner_left_top();
    corner_left_bottom();
    corner_right_top();
    corner_right_bottom();
    echo "$messagetext";
    echo '<div class="contenttoright">'.get_string('loginas', '', $USER->firstname).' (<a href="logout.php" title="'.get_string('logout').'">'.get_string('logout').'</a>)</div>';
    print_container_end();
    
    /// print content
    print_container_start(true, 'content-body', 'content-body');
    corner_left_top();
    corner_left_bottom();
    corner_right_top();
    corner_right_bottom();
    echo "<br><form method=\"post\">";
    /// sitename
    echo "<label for=\"sitename\">".get_string("sitename").":</label><div class='formcontenttoright'><input type=\"text\" name=\"sitename\" size=\"55\" value=\"".$CFG->sitename."\" /><br>
            ".get_string('sitenameinfo')."</div><br><br>";
    ///lang
    echo "<label for=\"lang\">".get_string("lang").":</label><div class='formcontenttoright'><select name=\"lang\" /><option value=\"en_utf8\"";
            if ($CFG->lang == "en_utf8"){
            	echo " selected";
            }
            echo ">".get_string('en')."</option><option value=\"ca_utf8\"";
            if ($CFG->lang == "ca_utf8"){
            	echo " selected";
            }
            echo ">".get_string('ca')."</option></select><br>
            ".get_string('langinfo')."</div><br><br>";
    ///limitviewentries
    echo "<label for=\"limitviewentries\">".get_string("limitviewentries").":</label><div class='formcontenttoright'><input type=\"text\" name=\"limitviewentries\" size=\"15\" value=\"".$CFG->limitviewentries."\" /><br>
            ".get_string('limitviewentriesinfo')."</div><br><br>";
    
    ///debugmode
    echo "<label for=\"debugmode\">".get_string("debugmode").":</label><div class='formcontenttoright'><select name=\"debugmode\" /><option value=\"0\"";
            if ($CFG->debugmode == "0"){
            	echo " selected";
            }
            echo ">".get_string('no')."</option><option value=\"1\"";
            if ($CFG->debugmode == "1"){
            	echo " selected";
            }
            echo ">".get_string('yes')."</option></select><br>
            ".get_string('debugmodeinfo')."</div><br><br>";
            
    echo "<div class='formcontenttoright'><input type=\"submit\" class=\"boton_75\" value=\"".get_string("save")."\" /></div>
        </form>";
    print_container_end();
    
    /// print footer
    print_footer();