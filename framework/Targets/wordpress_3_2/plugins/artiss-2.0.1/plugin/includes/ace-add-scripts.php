<?php
/**
* Add Scripts
*
* Add CSS to the main theme
*
* @package	Artiss-Code-Embed
*/

/**
* Add scripts to theme
*
* Add styles to the main theme
*
* @since		2.0
*/

function ace_main_scripts() {

    wp_register_style( 'ace_responsive', plugins_url( '/simple-embed-code/css/ace-video-container.css' ) );

    wp_enqueue_style( 'ace_responsive' );

}

add_action( 'wp_enqueue_scripts', 'ace_main_scripts' );
?>