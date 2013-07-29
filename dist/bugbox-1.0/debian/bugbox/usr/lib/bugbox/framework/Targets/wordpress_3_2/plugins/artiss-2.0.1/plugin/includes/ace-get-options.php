<?php
/**
* Get Code Embed Parameters
*
* Fetch options - if none exist set them. If the old options exist, move them over
*
* @package	Artiss-Code-Embed
* @since	1.5
*
* @return	string	Array of default options
*/

function ace_get_embed_paras() {

	$options = get_option( 'artiss_code_embed' );
	$changed = false;

	// If array doesn't exist, set defaults

	if ( !is_array( $options ) ) {
		$options = array( 'opening_ident' => '%', 'keyword_ident' => 'CODE', 'closing_ident' => '%' );
		$changed = true;
	}

	// If the old options exist, import and delete them

	if ( get_option( 'simple_code_embed' ) ) {
		$old_option = get_option( 'simple_code_embed' );
		$options[ 'keyword_ident' ] = $old_option[ 'prefix'];
		delete_option( 'simple_code_embed' );
		$changed = true;
	}

	// Update the options, if changed, and return the result

	if ( $changed ) { update_option( 'artiss_code_embed', $options );}

	return $options;
}
?>