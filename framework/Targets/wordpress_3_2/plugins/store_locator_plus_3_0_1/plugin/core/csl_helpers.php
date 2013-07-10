<?php
/****************************************************************************
 ** file: csl_helpers.php
 **
 ** Generic helper functions.  May live in WPCSL-Generic soon.
 ***************************************************************************/

 
/**************************************
 ** function: get_string_from_phpexec()
 ** 
 ** Executes the included php (or html) file and returns the output as a string.
 **
 ** Parameters:
 **  $file (string, required) - name of the file 
 **/
function get_string_from_phpexec($file) {
    if (file_exists($file)) {
        ob_start();
        include($file);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    } else {
    	    print "No file: $file in ".getcwd()."<br/>";
    }
}
 
 
/**************************************
 ** function: execute_and_output_template()
 ** 
 ** Executes the included php (or html) file and prints out the results.
 ** Makes for easy include templates that depend on processing logic to be
 ** dumped mid-stream into a WordPress page.  A plugin in a plugin sorta.
 **
 ** Parameters:
 **  $file (string, required) - name of the file in the plugin/templates dir
 **/
function execute_and_output_template($file) {
    $file = SLPLUS_COREDIR.'/templates/'.$file;
    print get_string_from_phpexec($file);
}

/**************************************
 ** function: slp_createhelpdiv()
 ** 
 ** Generate the string that displays the help icon and the expandable div
 ** that mimics the WPCSL-Generic forms more info buttons.
 **
 ** Parameters:
 **  $divname (string, required) - the name of the div to toggle
 **  $msg (string, required) - the message to display
 **/
function slp_createhelpdiv($divname,$msg) {
    return "<a onclick=\"swapVisibility('".SLPLUS_PREFIX."-help$divname');\" href=\"javascript:;\">".
        "<img class='helpicon' border='0' title='More info' alt='More info' src='".SLPLUS_COREURL."images/help-icon-18x20.png'>".
        "</a>".
        "<div id='".SLPLUS_PREFIX."-help$divname' class='input_note' style='display: none;'>".
            $msg. 
        "</div>"
        ;
}


/**************************************
 ** function: setup_stylesheet_for_slplus
 **
 ** Setup the CSS for the product pages.
 **/
function setup_stylesheet_for_slplus() {
    global $slplus_plugin, $fnvars;
    
    // Pro Pack - Use Themes System
    //
    if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
        $slplus_plugin->themes->assign_user_stylesheet(isset($fnvars['theme'])?$fnvars['theme']:'');
    } else {
        wp_deregister_style(SLPLUS_PREFIX.'_user_header_css');             
        wp_dequeue_style(SLPLUS_PREFIX.'_user_header_css');                        
        if ( file_exists(SLPLUS_PLUGINDIR.'css/default.css')) {
            wp_enqueue_style(SLPLUS_PREFIX.'_user_header_css', SLPLUS_PLUGINURL .'/css/default.css');
        }                    
    }        
}

/**************************************
 ** function: setup_ADMIN_stylesheet_for_slplus
 **
 ** Setup the CSS for the admin page.
 **/
function setup_ADMIN_stylesheet_for_slplus() {
    if ( file_exists(SLPLUS_PLUGINDIR.'css/admin.css')) {
        wp_enqueue_style('csl_slplus_admin_css', SLPLUS_PLUGINURL .'/css/admin.css'); 
    }
}

