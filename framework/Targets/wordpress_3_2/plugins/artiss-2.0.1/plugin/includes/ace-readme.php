<?php
/**
* README Page
*
* Display the README instructions
*
* @package	Artiss-Code-Embed
* @since	2.0
*/
?>
<div class="wrap">
<div class="icon32" id="icon-edit-pages"></div>

<h2><?php _e( 'Artiss Code Embed README'); ?></h2>

<?php
if ( !function_exists( 'wp_readme_parser' ) ) {
    echo '<p>You shouldn\'t be able to see this but I guess that odd things can happen!<p>';
    echo '<p>To display the README you must install the <a href="http://wordpress.org/extend/plugins/wp-readme-parser/">README Parser plugin</a>.</p>';
} else {
    echo wp_readme_parser( array( 'exclude' => 'meta,upgrade notice,screenshots,support,changelog,links,installation,licence,reviews & mentions', 'ignore' => 'For help with this plugin,,for more information and advanced options ' ), 'Simple Embed Code' );
}
?>
</div>