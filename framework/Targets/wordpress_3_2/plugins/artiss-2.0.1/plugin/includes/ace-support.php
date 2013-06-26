<?php
/**
* Support Page
*
* Support for the plugin
*
* @package	Artiss-Code-Embed
* @since	2.0
*/
?>
<div class="wrap">
<div class="icon32"><img src="<?php echo plugins_url(); ?>/simple-embed-code/images/screen_icon.png" alt="" title="" height="32px" width="32px"/><br /></div>

<h2><?php _e( 'Artiss Code Embed Support'); ?></h2>

<p><?php echo sprintf( __( 'You are using Artiss Code Embed version %s. It was written by David Artiss.' ), artiss_code_embed_version ); ?></p>

<?php
$options = ace_get_embed_paras();

// How to embed

echo "<h3>" . __( 'How to Embed' ) . "</h3>\n";
echo '<p>' . sprintf ( __( 'Based upon your current settings to embed some code simply add a custom field named %s, where %s is any suffix you wish. The code to embed is then added as the field value.' ), '<strong>' . $options[ 'keyword_ident' ] . 'x</strong>', '<strong>x</strong>' ) . "</p>\n";
echo '<p>' . sprintf ( __( 'Then, to add the code into your post simple add %s where you wish it to appear. %s is the suffix you used for the custom field name.' ), '<strong>' . $options[ 'opening_ident' ] . $options[ 'keyword_ident' ] . "x" . $options[ 'closing_ident' ] . '</strong>', '<strong>x</strong>' ) . "</p>\n";
echo '<p>' . sprintf ( __( 'For example, I may add a custom field named %s, where the value is the code I wish to embed. I would then in my post add %s where I wish the code to then appear.' ), '<strong>' . $options[ 'keyword_ident' ].'1</strong>', '<strong>' . $options[ 'opening_ident' ] . $options[ 'keyword_ident' ] . "1" . $options[ 'closing_ident' ] . '</strong>' ) . "</p>\n";
echo '<p>' . sprintf ( __( 'To embed the same code but to make it responsive you would use %s. To set a maximum width you would use %s, where %s is the maximum width in pixels.' ), '<strong>' . $options[ 'opening_ident' ] . $options[ 'keyword_ident' ] . "x_RES" . $options[ 'closing_ident' ] . '</strong>', '<strong>' . $options[ 'opening_ident' ] . $options[ 'keyword_ident' ] . "x_RES_y" . $options[ 'closing_ident' ] . '</strong>', '<strong>y</strong>' ) . "</p>\n";
echo '<p>' . sprintf ( __( 'To embed an external URL you would type %s, where %s is the URL.' ), '<strong>' . $options[ 'opening_ident' ] . 'url' . $options[ 'closing_ident' ] . '</strong>', '<strong>url</strong>' ) . "</p>\n";

// Support information

echo '<h3>' . __( 'Support Information' ) . "</h3>\n";
echo '<p><a href="http://www.artiss.co.uk/code-embed">' . __( 'Artiss Code Embed Plugin Documentation' ) . "</a></p>\n";
echo '<p><a href="http://www.artiss.co.uk/forum/specific-plugins-group2/artiss-code-embed-forum3">' . __( 'Artiss Code Embed Support Forum' ) . "</a></p>\n";
echo '<h4>' . __( 'This plugin, and all support, is supplied for free, but <a title="Donate" href="http://artiss.co.uk/donate" target="_blank">donations</a> are always welcome.' ) . "</h4>\n";

// Acknowledgements

echo '<h3>' . __( 'Acknowledgements' ) . "</h3>\n";
echo '<p>' . sprintf( __( 'Images have been compressed with %s.' ), '<a href="http://www.smushit.com/ysmush.it/">Smush.it</a>' ) . "</p>\n";
echo '<p>' . sprintf( __( 'CSS has been compressed with %s.' ), '<a href="http://www.artiss.co.uk/css-compression">Artiss.co.uk CSS Compressor</a>' ) . "</p>\n";

// Stay in touch

echo '<h3>' . __( 'Stay in Touch' ) . "</h3>\n";
echo '<p>' . __( '<a href="http://www.artiss.co.uk/wp-plugins">See the full list</a> of Artiss plugins, including beta releases.' ) . "</p>\n";
echo '<p>' . __( '<a href="http://www.twitter.com/artiss_tech">Follow Artiss.co.uk</a> on Twitter.' ) . "</p>\n";
echo '<p>' . __( '<a href="http://www.artiss.co.uk/feed">Subscribe</a> to the Artiss.co.uk news feed.' ) . "</p>\n";

?>
</div>