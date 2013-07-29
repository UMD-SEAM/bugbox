<?php
/**
* Administration Menu Options
*
* Add various adminstration menu options
*
* @package	Artiss-Code-Embed
*/

/**
* Add Settings link to plugin list
*
* Add a Settings link to the options listed against this plugin
*
* @since	1.6
*
* @param	string  $links	Current links
* @param	string  $file	File in use
* @return   string			Links, now with settings added
*/

function ace_add_settings_link( $links, $file ) {

	static $this_plugin;

	if ( !$this_plugin ) { $this_plugin = plugin_basename( __FILE__ ); }

	if ( strpos( $file, 'code-embed.php' ) !== false ) {
		$settings_link = '<a href="admin.php?page=ace-options">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}
add_filter( 'plugin_action_links', 'ace_add_settings_link', 10, 2 );

/**
* Add meta to plugin details
*
* Add options to plugin meta line
*
* @since	1.6
*
* @param	string  $links	Current links
* @param	string  $file	File in use
* @return   string			Links, now with settings added
*/

function ace_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'code-embed.php' ) !== false ) {

		$links = array_merge( $links, array( '<a href="http://www.artiss.co.uk/forum/specific-plugins-group2/artiss-code-embed-forum3">' . __( 'Support' ) . '</a>' ) );
		$links = array_merge( $links, array( '<a href="http://www.artiss.co.uk/donate">' . __( 'Donate' ) . '</a>' ) );
	}

	return $links;
}
add_filter( 'plugin_row_meta', 'ace_set_plugin_meta', 10, 2 );

/**
* Code Embed Menu
*
* Add a new option to the Admin menu and context menu
*
* @since	1.4
*
* @uses ace_help		Return help text
*/

function ace_menu() {

    // Depending on WordPress version and available functions decide which (if any) contextual help system to use

    $contextual_help = ace_contextual_help_type();

    // Add main admin option

	add_menu_page( 'Artiss Code Embed Settings', 'Code Embed', 'edit_posts', 'ace-search', 'ace_search', plugins_url() . '/simple-embed-code/images/menu_icon.png' );    
  
    // Add search sub-menu    

    if ( $contextual_help == 'new' ) { global $ace_search_hook; }

	$ace_search_hook = add_submenu_page( 'ace-search', 'Artiss Code Embed Search', 'Search', 'edit_posts', 'ace-search', 'ace_search' );

    if ( $contextual_help == 'new' ) { add_action('load-'.$ace_search_hook, 'ace_add_search_help'); }

    if ( $contextual_help == 'old' ) { add_contextual_help( $ace_search_hook, ace_search_help() ); }
    
    // Add options sub-menu

    if ( $contextual_help == 'new' ) { global $ace_options_hook; }

    $ace_options_hook = add_submenu_page( 'ace-search', 'Artiss Code Embed Settings', 'Options', 'manage_options', 'ace-options', 'ace_options' );

    if ( $contextual_help == 'new' ) { add_action('load-'.$ace_options_hook, 'ace_add_options_help'); }

    if ( $contextual_help == 'old' ) { add_contextual_help( $ace_options_hook, ace_options_help() ); }    
    
    // Add readme sub-menu

    if ( function_exists( 'wp_readme_parser' ) ) {
        add_submenu_page( 'ace-search', 'Artiss Code Embed README', 'README', 'edit_posts', 'ace-readme', 'ace_readme' );
    }
    
    // Add support sub-menu
    
    add_submenu_page( 'ace-search', 'Artiss Code Embed Support', 'Support', 'edit_posts', 'ace-support', 'ace_support' );

}
add_action( 'admin_menu','ace_menu' );

/**
* Get contextual help type
*
* Return whether this WP installtion requires the new or old contextual help type, or none at all
*
* @since	2.0
*
* @return   string			Contextual help type - 'new', 'old' or false
*/

function ace_contextual_help_type() {

    global $wp_version;

    $type = false;

    if ( ( float ) $wp_version >= 3.3 ) {
        $type = 'new';
    } else {
        if ( function_exists( 'add_contextual_help' ) ) {
            $type = 'old';
        }
    }

    return $type;

}

/**
* Add Options Help
*
* Add help tab to options screen
*
* @since	2.0
*
* @uses     ace_options_help    Return help text
*/

function ace_add_options_help() {

    global $ace_options_hook;
    $screen = get_current_screen();

    if ( $screen->id != $ace_options_hook ) { return; }

    $screen -> add_help_tab( array( 'id' => 'ace-options-help-tab', 'title'	=> __( 'Help' ), 'content' => ace_options_help() ) );
}

/**
* Add Search Help
*
* Add help tab to search screen
*
* @since	2.0
*
* @uses     ace_search_help    Return help text
*/

function ace_add_search_help() {

    global $ace_search_hook;
    $screen = get_current_screen();

    if ( $screen->id != $ace_search_hook ) { return; }

    $screen -> add_help_tab( array( 'id' => 'ace-search-help-tab', 'title' => __( 'Help' ), 'content' => ace_search_help() ) );
}

/**
* Code Embed Options
*
* Define an option screen
*
* @since	1.4
*/

function ace_options() {

	include_once( WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) ) . 'ace-options.php' );

}

/**
* Code Embed Search
*
* Define a the search screen
*
* @since	1.6
*/

function ace_search() {

	include_once( WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) ) . 'ace-search.php' );

}

/**
* Code Embed README
*
* Define the README screen
*
* @since	2.0
*/

function ace_readme() {

	include_once( WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) ) . 'ace-readme.php' );

}

/**
* Code Embed Support
*
* Define the support screen
*
* @since	2.0
*/

function ace_support() {

	include_once( WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) ) . 'ace-support.php' );

}

/**
* Code Embed Options Help
*
* Return help text for options screen
*
* @since	1.5
*
* @return	string	Help Text
*/

function ace_options_help() {

	$help_text = '<p>' . __( 'Use this screen to modify the identifiers and keyword used to specify your embedded code.' ) . '</p>';
	$help_text .= '<p>' . __( 'The keyword is the name used for your custom field. The custom field\'s value is the code that you wish to embed.' ) . '</p>';  
	$help_text .= '<p>' . __( 'The keyword, sandwiched with the identifier before and after, is what you then need to add to your post or page to activate the embed code.' ) . '</p>';
	$help_text .= '<h4>' . __( 'This plugin, and all support, is supplied for free, but <a title="Donate" href="http://artiss.co.uk/donate" target="_blank">donations</a> are always welcome.' ) . '</h4>';

	return $help_text;
}

/**
* Code Embed Search Help
*
* Return help text for search screen
*
* @since	1.6
*
* @return	string	Help Text
*/

function ace_search_help() {

	$help_text = '<p>' . __( 'This screen allows you to search for the post and pages that a particular code embed has been used in.' ) . '</p>';
	$help_text .= '<p>' . __( 'Simply enter the code suffix that you wish to search for and press the \'Search\' key to display a list of all the posts using it. In addition the code will be shown alongside it. Click on the post name to edit the post.' ) . '</p>';
	$help_text .= '<p>' . __( 'The search results are grouped together in matching code groups, so posts with the same code will be shown together with the same colour background.' ) . '</p>';
	$help_text .= '<h4>' . __( 'This plugin, and all support, is supplied for free, but <a title="Donate" href="http://artiss.co.uk/donate" target="_blank">donations</a> are always welcome.' ) . '</h4>';    

	return $help_text;
}

/**
* Detect plugin activation
*
* Upon detection of activation set an option
*
* @since	2.0
*/

function ace_plugin_activate() {

	update_option( 'artiss_code_embed_activated', true );

}
register_activation_hook( WP_PLUGIN_DIR . "/simple-embed-code/simple-code-embed.php", 'ace_plugin_activate' );

// If plugin activated, run activation commands and delete option

global $wp_version;

if ( get_option( 'artiss_code_embed_activated' ) ) {

    if ( ( float ) $wp_version >= 3.3 ) {

        add_action( 'admin_enqueue_scripts', 'ace_admin_enqueue_scripts' );

    }

    delete_option( 'artiss_code_embed_activated' );
}

/**
* Enqueue Feature Pointer files
*
* Add the required feature pointer files
*
* @since	2.0
*/

function ace_admin_enqueue_scripts() {

    wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script( 'wp-pointer' );

    add_action( 'admin_print_footer_scripts', 'ace_admin_print_footer_scripts' );
}

/**
* Show Feature Pointer
*
* Display feature pointer
*
* @since	2.0
*/

function ace_admin_print_footer_scripts() {

    $pointer_content = '<h3>' . __( 'Welcome to Artiss Code Embed' ) . '</h3>';
    $pointer_content .= '<p style="font-style:italic;">' . __( 'Thank you for installing this plugin.' ) . '</p>';
    $pointer_content .= '<p>' . __( 'A new menu has been added to the sidebar. This will allow you to change the keywords and identifiers used for embedding as well as providing a method for searching for code embeds.' );
?>
<script>
jQuery(function () {
	var body = jQuery(document.body),
	menu = jQuery('#toplevel_page_ace-options'),
	collapse = jQuery('#collapse-menu'),
	pluginmenu = menu.find("a[href='admin.php?page=ace-options']"),
	options = {
		content: '<?php echo $pointer_content; ?>',
		position: {
			edge: 'left',
			align: 'center',
			of: menu.is('.wp-menu-open') && !menu.is('.folded *') ? pluginmenu : menu
		},
		close: function() {
		}};

	if ( !pluginmenu.length )
		return;

	body.pointer(options).pointer('open');
});
</script>
<?php
}
?>