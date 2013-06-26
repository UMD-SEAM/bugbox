<?php
/*
Plugin Name: Artiss Code Embed
Plugin URI: http://www.artiss.co.uk/code-embed
Description: Allows you to embed code into your posts & pages
Version: 2.0.1
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/

/**
* Artiss Code Embed
*
* Embed code into a post
*
* @package	Artiss-Code-Embed
* @since	1.6
*/

define( 'artiss_code_embed_version', '2.0.1' );

function ace_load_plugin_textdomain() {
    load_plugin_textdomain( 'simple-embed-code', false, 'simple-embed-code/languages' );
} 
add_action( 'init', 'ace_load_plugin_textdomain' );

$functions_dir = WP_PLUGIN_DIR . '/simple-embed-code/includes/';

// Include all the various functions

include_once( $functions_dir . 'ace-get-options.php' );		    			// Get the default options

if ( is_admin() ) {

	include_once( $functions_dir . 'ace-admin-config.php' );				// Various administration config. options

} else {

	include_once( $functions_dir . 'ace-add-scripts.php' );		       		// Add scripts to the main theme

	include_once( $functions_dir . 'ace-filter.php' );		        		// Filter to apply code embeds

}
?>