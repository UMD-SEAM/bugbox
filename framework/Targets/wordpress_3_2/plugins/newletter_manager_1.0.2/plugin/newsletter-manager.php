<?php 
/*
Plugin Name: Newsletter Manager
Plugin URI: http://xyzscripts.com/wordpress-plugins/newsletter-manager/
Description: Create and send html or plain text email newsletters to your subscribers. The plugin supports unlimited email campaigns, unlimited email addresses,  double opt-in anti-spam compliance, hourly email sending limit and much more. Opt-in form is available as HTML code, shortcode as well as standard Wordpress widget. The import/export tool allows to create and restore backup of your subscriber list.        
Version: 1.0.2
Author: xyzscripts.com
Author URI: http://xyzscripts.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

 if ( !function_exists( 'add_action' ) ) 
 {
 	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
 	exit;
 }

ob_start();

define(XYZ_EM_PLUGIN_FILE,__FILE__);

require( dirname( __FILE__ ) . '/xyz-functions.php' );

require( dirname( __FILE__ ) . '/admin/install.php' );

require( dirname( __FILE__ ) . '/admin/menu.php' );

require( dirname( __FILE__ ) . '/shortcode_handler.php' );

require( dirname( __FILE__ ) . '/admin/deactivate.php' );

require( dirname( __FILE__ ) . '/admin/uninstall.php' );

require( dirname( __FILE__ ) . '/widget.php' );

?>