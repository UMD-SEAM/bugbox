<?php
/**
* Uninstaller
*
* Uninstall the plugin by removing any options from the database
*
* @package	Artiss-Code-Embed
* @since	1.6
*/

// If the uninstall was not called by WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Delete any options
delete_option( 'artiss_code_embed' );
delete_option( 'simple_code_embed' );
?>