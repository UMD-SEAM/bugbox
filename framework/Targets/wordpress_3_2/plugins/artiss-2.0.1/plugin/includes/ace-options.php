<?php
/**
* Code Embed Options
*
* Allow the user to change the default options
*
* @package	Artiss-Code-Embed
* @since	1.4
*
* @uses	ace_get_embed_paras	Get the options
* @uses ace_help			Return help text
*/
?>
<div class="wrap">
<div class="icon32"><img src="<?php echo plugins_url(); ?>/simple-embed-code/images/screen_icon.png" alt="" title="" height="32px" width="32px"/><br /></div>
<h2><?php _e( 'Artiss Code Embed Options' ); ?></h2>
<?php
// If options have been updated on screen, update the database
if ( ( !empty( $_POST ) ) && ( check_admin_referer( 'code-embed-profile' , 'code_embed_profile_nonce' ) ) ) {

	// Update the options array from the form fields. Strip invalid tags.
	$options[ 'opening_ident' ] = strtoupper( trim( $_POST[ 'code_embed_opening' ], '[]<>' ) );
	$options[ 'keyword_ident' ] = strtoupper( trim( $_POST[ 'code_embed_keyword' ], '[]<>' ) );
	$options[ 'closing_ident' ] = strtoupper( trim( $_POST[ 'code_embed_closing' ], '[]<>' ) );

	// If any fields are blank assign default values
	if ( $options[ 'opening_ident' ] == '' ) { $options[ 'opening_ident' ] = '%'; }
	if ( $options[ 'keyword_ident' ] == '' ) { $options[ 'keyword_ident' ] = 'CODE'; }
	if ( $options[ 'closing_ident' ] == '' ) { $options[ 'closing_ident' ] = '%'; }

    update_option( 'artiss_code_embed', $options );
}

// Fetch options into an array
$options = ace_get_embed_paras();
?>

<form method="post" action="<?php echo get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=ace-options&amp;updated=true' ?>">

<?php echo '<h3>' . __( 'Identifier Format' ) . '</h3>' . __( 'Specify the format that will be used to define the way the code is embedded in your post. The formats are case insensitive and characters &lt; &gt [ ] are invalid.' ); ?>

<table class="form-table">

<tr>
<th scope="row"><?php _e( 'Keyword' ); ?></th>
<td><input type="text" size="12" maxlength="12" name="code_embed_keyword" value="<?php echo $options[ 'keyword_ident' ] ; ?>"/>&nbsp;<span class="description"><?php _e( 'The keyword that is used to name the custom field and then place in your post where the code should be embedded. A suffix on any type can then be placed on the end.' ); ?></span></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Opening Identifier' ); ?></th>
<td><input type="text" size="4" maxlength="4" name="code_embed_opening" value="<?php echo $options[ 'opening_ident' ]; ?>"/>&nbsp;<span class="description"><?php _e( 'The character(s) that must be placed in the post before the keyword to uniquely identify it.' ); ?></span></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Closing Identifier' ); ?></th>
<td><input type="text" size="4" maxlength="4" name="code_embed_closing" value="<?php echo $options[ 'closing_ident' ]; ?>"/>&nbsp;<span class="description"><?php _e( 'The character(s) that must be placed in the post after the keyword to uniquely identify it.' ); ?></span></td>
</tr>

</table>

<?php wp_nonce_field( 'code-embed-profile', 'code_embed_profile_nonce', true, true ); ?>

<br/><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Settings' ); ?>"/>

</form>

</div>