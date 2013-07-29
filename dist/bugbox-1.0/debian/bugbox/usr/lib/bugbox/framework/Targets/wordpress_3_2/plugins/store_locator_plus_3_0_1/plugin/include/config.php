<?php

/**
 * We need the generic WPCSL plugin class, since that is the
 * foundation of much of our plugin.  So here we make sure that it has
 * not already been loaded by another plugin that may also be
 * installed, and if not then we load it.
 */
if (defined('SLPLUS_PLUGINDIR')) {
    if (class_exists('wpCSL_plugin__slplus') === false) {
        require_once(SLPLUS_PLUGINDIR.'WPCSL-generic/classes/CSL-plugin.php');
    }
    
    if (class_exists('SLPlus_Activation') == false) {
        require_once(SLPLUS_PLUGINDIR.'include/storelocatorplus-activation_class.php');
    }
    
    /**
     * This section defines the settings for the admin menu.
     */ 
    global $slplus_plugin;
    $slplus_plugin = new wpCSL_plugin__slplus(
        array(
            // We don't want default wpCSL objects, let's set our own
            //
            'use_obj_defaults'      => false,
            'cache_obj_name'        => 'none',
            'helper_obj_name'       => 'default',
            'license_obj_name'      => 'default',
            'notifications_obj_name'=> 'default',
            'products_obj_name'     => 'none',
            'settings_obj_name'     => 'default',
            
            'themes_obj_name'       => 'default',
            'no_default_css'        => true,            
            
            'prefix'                => SLPLUS_PREFIX,
            'css_prefix'            => SLPLUS_PREFIX,
            'name'                  => 'Store Locator Plus',
            'sku'                   => 'SLPLUS',
            
            'on_update' => array(SLPlus_Activate, update),
            'version' => '3.0.1',
            
            'url'                   => 'http://www.storelocatorplus.com/',            
            'support_url'            => 'http://storelocatorplus.com/faq/',
            'purchase_url'           => 'http://storelocatorplus.com/download/',
            
            'basefile'              => SLPLUS_BASENAME,
            'plugin_path'           => SLPLUS_PLUGINDIR,
            'plugin_url'            => SLPLUS_PLUGINURL,
            'cache_path'            => SLPLUS_PLUGINDIR . 'cache',
            
            'uses_money'            => false,
            
            'driver_type'           => 'none',
            'driver_args'           => array(
                    'api_key'   => get_option(SLPLUS_PREFIX.'-api_key'),
                    'app_id'    => 'CyberSpr-',
                    'plus_pack_enabled' => get_option(SLPLUS_PREFIX.'-SLPLUS-isenabled'),
                    ),
            
            'has_packages'           => true,            
        )
    );   
    
    
    // Setup our optional packages
    //
    add_options_packages_for_slplus();       
}    

/**************************************
 ** function: list_options_packages_for_slplus
 **
 ** Setup the option package list.
 **/
function add_options_packages_for_slplus() {
    configure_slplus_propack();
    configure_slplus_storepages();
}

/**************************************
 ** function: configure_slplus_propack
 **
 ** Configure the Pro Pack.
 **/
function configure_slplus_propack() {
    global $slplus_plugin;
   
    // Setup metadata
    //
    $slplus_plugin->license->add_licensed_package(
            array(
                'name'              => 'Pro Pack',
                'help_text'         => 'A variety of enhancements are provided with this package.  ' .
                                       'See the <a href="'.$slplus_plugin->purchase_url.'" target="Cyber Sprocket">product page</a> for details.  If you purchased this add-on ' .
                                       'come back to this page to enter the license key to activate the new features.',
                'sku'               => 'SLPLUS',
                'paypal_button_id'  => '2D864VACHMK5A',
                'paypal_upgrade_button_id' => 'TZSPX7PD26XJU'
            )
        );
    
    // Enable Features Is Licensed
    //
    if ($slplus_plugin->license->packages['Pro Pack']->isenabled_after_forcing_recheck()) {
        
         //--------------------------------
         // Pro Pack v2.4+ Only
         //
         if ($slplus_plugin->license->packages['Pro Pack']->active_version >= 2004000) {
             
             //--------------------------------
             // Enable Themes
             //
             $slplus_plugin->themes_enabled = true;
             $slplus_plugin->themes->css_dir = SLPLUS_PLUGINDIR . 'css/';
         }             
         
         
    }        
}


/**************************************
 ** function: configure_slplus_storepages
 **
 ** Configure Store Pages.
 **/
function configure_slplus_storepages() {
    global $slplus_plugin;
   
    // Setup metadata
    //
    $slplus_plugin->license->add_licensed_package(
            array(
                'name'              => 'Store Pages',
                'help_text'         => 'Create inidividual WordPress pages from your locations data. Great for SEO.  ' .
                                       'See the <a href="'.$slplus_plugin->purchase_url.'" target="Cyber Sprocket">product page</a> for details.  If you purchased this add-on ' .
                                       'come back to this page to enter the license key to activate the new features.',
                'sku'               => 'SLP-PAGES',
                'paypal_button_id'  => '3V2SSFKB3R6XE',
                'paypal_upgrade_button_id' => '3V2SSFKB3R6XE'
            )
        );
    
    // Enable Features Is Licensed
    //
    if ($slplus_plugin->license->packages['Store Pages']->isenabled_after_forcing_recheck()) {
        
        // Future Feature Enabling Goes Here
    }        
}

