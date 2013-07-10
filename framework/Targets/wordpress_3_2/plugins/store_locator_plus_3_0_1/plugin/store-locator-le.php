<?php
/*
Plugin Name: Google Maps via Store Locator Plus
Plugin URI: http://www.storelocatorplus.com/
Description: Manage multiple locations with ease. Map stores or other points of interest with ease via Gooogle Maps.  This is a highly customizable, easily expandable, enterprise-class location management system.
Version: 3.0.1
Author: Cyber Sprocket Labs
Author URI: http://www.cybersprocket.com
License: GPL3

Copyright 2012  Cyber Sprocket Labs (info@cybersprocket.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


// Globals
global $sl_upload_path,$slpath;
$sl_upload_path='';
$sl_path='';

// Drive Path Defines
//
if (defined('SLPLUS_PLUGINDIR') === false) {
    define('SLPLUS_PLUGINDIR', plugin_dir_path(__FILE__));
}
if (defined('SLPLUS_COREDIR') === false) {
    define('SLPLUS_COREDIR', SLPLUS_PLUGINDIR . 'core/');
}
if (defined('SLPLUS_ICONDIR') === false) {
    define('SLPLUS_ICONDIR', SLPLUS_COREDIR . 'images/icons/');
}

// URL Defines
//
if (defined('SLPLUS_PLUGINURL') === false) {
    define('SLPLUS_PLUGINURL', plugins_url('',__FILE__));
}
if (defined('SLPLUS_COREURL') === false) {
    define('SLPLUS_COREURL', SLPLUS_PLUGINURL . '/core/');
}
if (defined('SLPLUS_ICONURL') === false) {
    define('SLPLUS_ICONURL', SLPLUS_COREURL . 'images/icons/');
}
if (defined('SLPLUS_ADMINPAGE') === false) {
    define('SLPLUS_ADMINPAGE', admin_url() . 'admin.php?page=' . SLPLUS_COREDIR );
}
if (defined('SLPLUS_PLUSPAGE') === false) {
    define('SLPLUS_PLUSPAGE', admin_url() . 'admin.php?page=' . SLPLUS_PLUGINDIR );
}
// The relative path from the plugins directory
//
if (defined('SLPLUS_BASENAME') === false) {
    define('SLPLUS_BASENAME', plugin_basename(__FILE__));
}

// Our product prefix
//
if (defined('SLPLUS_PREFIX') === false) {
    define('SLPLUS_PREFIX', 'csl-slplus');
}

// Include our needed files
//
include_once(SLPLUS_PLUGINDIR . '/include/config.php'	);
include_once(SLPLUS_PLUGINDIR . 'plus.php'						);
include_once(SLPLUS_COREDIR   . 'csl_helpers.php'			);
include_once(SLPLUS_COREDIR   . 'functions.sl.php'			);
include_once(SLPLUS_COREDIR   . 'csl-ajax-search.php'	);
require_once(SLPLUS_PLUGINDIR . '/include/storelocatorplus-actions_class.php');
require_once(SLPLUS_PLUGINDIR . '/include/storelocatorplus-activation_class.php');

// Regular Actions
//
add_action('init'               ,array('SLPlus_Actions','init')                 );
add_action('wp_enqueue_scripts' ,array('SLPlus_Actions','wp_enqueue_scripts')   );
add_action('shutdown'           ,array('SLPlus_Actions','shutdown')             );

// Admin Actions
//
add_action('admin_menu'         , 'csl_slplus_add_options_page'                 );
add_action('admin_init'         ,array('SLPlus_Actions','admin_init'),10        );
add_action('admin_print_styles' , 'setup_ADMIN_stylesheet_for_slplus'           );
add_action('admin_head'         , 'slpreport_downloads'                         );

// Ajax search
//
add_action('wp_ajax_csl_ajax_search', 'csl_ajax_search');
add_action('wp_ajax_nopriv_csl_ajax_search', 'csl_ajax_search');

// Ajax Load
//
add_action('wp_ajax_csl_ajax_onload', 'csl_ajax_onload');
add_action('wp_ajax_nopriv_csl_ajax_onload', 'csl_ajax_onload');

// Short Codes
//
add_shortcode('STORE-LOCATOR','store_locator_shortcode');
add_shortcode('SLPLUS','store_locator_shortcode');
add_shortcode('slplus','store_locator_shortcode');

// Text Domains
//
load_plugin_textdomain(SLPLUS_PREFIX, false, SLPLUS_BASENAME . '/core/languages/');
