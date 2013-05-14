<?php  //BASED ON MOODLE 1.9.9. ARQUITECTURE

    /// check if the application is already installed
    if (!file_exists('../../../config.php')) {
        header('Location: ../../install.php');
        die;
    }

    /// load libraries
    require_once('../../../config.php');
    
    //initialize variables
    $errormsg = '';
    
    /// Check for timed out sessions
    if (!empty($SESSION->has_timed_out)) {
        $session_has_timed_out = true;
        $SESSION->has_timed_out = false;
    } else {
        $session_has_timed_out = false;
    }
    
    /// check if there are data to autheticate
    if ($frm = data_submitted()){
    	$frm->username = trim(moodle_strtolower($frm->username));
    	if (!$user = get_record('user', 'username', $frm->username, 'password', md5($frm->password))){
    		$errormsg = get_string("errorloggin");
    	}else {
    	    $USER = complete_user_login($user);
            if (isset($SESSION->wantsurl) and (strpos($SESSION->wantsurl, $CFG->wwwroot) === 0)) {
                $urltogo = $SESSION->wantsurl;    /// Because it's an address in this site
                unset($SESSION->wantsurl);
            } else {
                // no wantsurl stored or external - go to homepage
                $urltogo = $CFG->wwwroot.'/application/admin/';
                unset($SESSION->wantsurl);
            }
            redirect($urltogo);
            exit;
    	}
    }
    	
    /// First, let's remember where the user was trying to get to before they got here
    if (empty($SESSION->wantsurl)) {
        $SESSION->wantsurl = (array_key_exists('HTTP_REFERER',$_SERVER) &&
                              $_SERVER["HTTP_REFERER"] != $CFG->wwwroot &&
                              $_SERVER["HTTP_REFERER"] != $CFG->wwwroot.'/application/admin/' &&
                              $_SERVER["HTTP_REFERER"] != $CFG->httpswwwroot.'/application/admin/index.php' &&
                              $_SERVER["HTTP_REFERER"] != $CFG->httpswwwroot.'/application/admin/login.php')
            ? $_SERVER["HTTP_REFERER"] : NULL;
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
    echo "<br>";
    print_container_end();
        
    /// print page content
    print_container_start(true, 'content-body', 'content-body');
    corner_left_top();
    corner_left_bottom();
    corner_right_top();
    corner_right_bottom();
    echo '
          <div class="loginform">'.$errormsg.'<br><br><form action="login.php" method="post" id="login">
            <label for="username">'.get_string("username").'</label><br>
            <input type="text" name="username" id="username" size="15" value="'.$frm->username.'" /><br><br>
            <label for="password">'.get_string("password").'</label><br>
            <input type="password" name="password" id="password" size="15" value="" /><br><br>
            <input type="submit" class="boton_75" value="'.get_string("login").'" />
            <input type="hidden" name="testcookies" value="1" />
        </form>
          </div>';
    print_container_end();
	
    /// print footer
    print_footer();