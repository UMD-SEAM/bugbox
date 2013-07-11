<?php

/***********************************************************************
* Class: SLPlus_Actions
*
* The Store Locator Plus action hooks and helpers.
*
* The methods in here are normally called from an action hook that is
* called via the WordPress action stack.  
* 
* See http://codex.wordpress.org/Plugin_API/Action_Reference
*
************************************************************************/

if (! class_exists('SLPlus_Actions')) {
    class SLPlus_Actions {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        /*************************************
         * The Constructor
         */
        function __construct($params) {
        } 
        
        /**************************************
         ** method: admin_init()
         **
         ** Called when the WordPress admin_init action is processed.
         **
         ** Builds the interface elements used by WPCSL-generic for the admin interface.
         **
         **/
        function admin_init() {
            global $slplus_plugin;
            
            // Don't have what we need? Leave.
            if (!isset($slplus_plugin)) { return; }
        
            // Already been here?  Get out.
            if (isset($slplus_plugin->settings->sections['How to Use'])) { return; }

            // Add admin helpers
            //
            require_once(SLPLUS_PLUGINDIR . '/include/storelocatorplus-adminui_class.php');            
            
            //-------------------------
            // Navbar Section
            //-------------------------    
            $slplus_plugin->settings->add_section(
                array(
                    'name' => 'Navigation',
                    'div_id' => 'slplus_navbar',
                    'description' => get_string_from_phpexec(SLPLUS_COREDIR.'/templates/navbar.php'),
                    'is_topmenu' => true,
                    'auto' => false,
                    'headerbar'     => false        
                )
            );       
          
            //-------------------------
            // How to Use Section
            //-------------------------    
            $slplus_plugin->settings->add_section(
                array(
                    'name' => 'How to Use',
                    'description' => get_string_from_phpexec(SLPLUS_PLUGINDIR.'/how_to_use.txt'),
                    'start_collapsed' => true
                )
            );
        
            //-------------------------
            // Google Communiations
            //-------------------------    
            $slplus_plugin->settings->add_section(
                array(
                    'name'        => 'Google Communication',
                    'description' => 'These settings affect how the plugin communicates with Google to create your map.'.
                                        '<br/><br/>'
                )
            );
            
            $slplus_plugin->settings->add_item(
                'Google Communication', 
                'Google API Key', 
                'api_key', 
                'text', 
                false,
                'Your Google API Key.  You will need to ' .
                '<a href="http://code.google.com/apis/console/" target="newinfo">'.
                'go to Google</a> to get your Google Maps API Key.'
            );
        
        
            $slplus_plugin->settings->add_item(
                'Google Communication', 
                'Geocode Retries', 
                'goecode_retries', 
                'list', 
                false,
                'How many times should we try to set the latitude/longitude for a new address. ' .
                'Higher numbers mean slower bulk uploads ('.
                '<a href="http://www.cybersprocket.com/products/store-locator-plus/">plus version</a>'.
                '), lower numbers makes it more likely the location will not be set during bulk uploads.',
                array (
                      'None' => 0,
                      '1' => '1',
                      '2' => '2',
                      '3' => '3',
                      '4' => '4',
                      '5' => '5',
                      '6' => '6',
                      '7' => '7',
                      '8' => '8',
                      '9' => '9',
                      '10' => '10',
                    )
            );
            
            //--------------------------
            // Store Pages
            //
            $slp_rep_desc = __('These settings affect how the Store Pages add-on behaves. ', SLPLUS_PREFIX);
            if (!$slplus_plugin->license->packages['Store Pages']->isenabled) {
                $slp_rep_desc .= '<br/><br/>'.
                    __('This is a <a href="http://www.storelocatorplus.com/">Store Pages</a>'.
                    ' feature.  It provides a way to auto-create individual WordPress pages' .
                    ' for each of your locations. ', SLPLUS_PREFIX);
            }
            $slp_rep_desc .= '<br/><br/>';                 
            $slplus_plugin->settings->add_section(
                array(
                    'name'        => 'Store Pages',
                    'description' => $slp_rep_desc
                )
            );         
            if ($slplus_plugin->license->packages['Store Pages']->isenabled) {            
                slplus_add_pages_settings();
            }                
            
            //-------------------------
            // Pro Pack: Reporting
            // 
            $slp_rep_desc = __('These settings affect how the reporting system behaves. ', SLPLUS_PREFIX);
            if (!$slplus_plugin->license->packages['Pro Pack']->isenabled) {
                $slp_rep_desc .= '<br/><br/>'.
                    __('This is a <a href="http://www.storelocatorplus.com/">Pro Pack</a>'.
                    ' feature.  It provides a way to generate reports on what locations' .
                    ' people have searched for and what results they received back. ', SLPLUS_PREFIX);
            }
            $slp_rep_desc .= '<br/><br/>'; 
            $slplus_plugin->settings->add_section(
                array(
                    'name'        => 'Reporting',
                    'description' => $slp_rep_desc
                )
            );
            if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {
                slplus_add_report_settings();
            }                
        }
        
        /**************************************
         ** method: init()
         **
         ** Called when the WordPress init action is processed.
         **
         **/
        function init() {
            global $slplus_plugin;
            
            //--------------------------------
            // Store Pages Is Licensed
            //
            if ($slplus_plugin->license->packages['Store Pages']->isenabled) {

                // Register Store Pages Custom Type
                register_post_type( 'store_page',
                    array(
                        'labels' => array(
                            'name'              => __( 'Store Pages',SLPLUS_PREFIX ),
                            'singular_name'     => __( 'Store Page', SLPLUS_PREFIX ),
                            'add_new'           => __('Add New Store Page', SLPLUS_PREFIX),
                        ),
                    'public'            => true,
                    'has_archive'       => true,
                    'description'       => __('Store Locator Plus location pages.',SLPLUS_PREFIX),
                    'menu_postion'      => 20,   
                    'menu_icon'         => SLPLUS_COREURL . 'images/icon_from_jpg_16x16.png',
                    'capability_type'   => 'page',
                    )
                );                
                
                // Register Stores Taxonomy
                //                
                register_taxonomy(
                        'stores',
                        'store_page',
                        array (
                            'hierarchical'  => true,
                            'labels'        => 
                                array(
                                        'menu_name' => __('Stores',SLPLUS_PREFIX),
                                        'name'      => __('Store Attributes',SLPLUS_PREFIX),
                                     )
                            )
                    );                
            } 
        }
        
        /*************************************
         * method: wp_enqueue_scripts()
         * 
         * This is called whenever the WordPress wp_enqueue_scripts action is called.
         */
        static function wp_enqueue_scripts() {
            global $slplus_plugin;
            
            if (isset($slplus_plugin) && $slplus_plugin->ok_to_show()) {            
                $api_key=$slplus_plugin->driver_args['api_key'];
                $google_map_domain=(get_option('sl_google_map_domain')!="")? 
                        get_option('sl_google_map_domain') : 
                        "maps.google.com";                
                $sl_map_character_encoding='&oe='.get_option('sl_map_character_encoding','utf8');    
                
                //------------------------
                // Register our scripts for later enqueue when needed
                //
                //wp_register_script('slplus_functions',SLPLUS_PLUGINURL.'/core/js/functions.js');
				if (isset($api_key))
				{
					wp_register_script(
							'google_maps',
							"http://$google_map_domain/maps/api/js?v=3.9&amp;key=$api_key&amp;sensor=false" //todo:character encoding ???
							//"http://$google_map_domain/maps?file=api&amp;v=2&amp;key=$api_key&amp;sensor=false{$sl_map_character_encoding}"                        
							);
				}
				else {
					wp_register_script(
						'google_maps',
						"http://$google_map_domain/maps/api/js?v=3.9&amp;sensor=false"
					);
				}
                //wp_register_script(
                //        'slplus_map',
                //        SLPLUS_PLUGINURL.'/core/js/store-locator-map.js',
                //        array('google_maps','jquery')
                //        ); 
						
				wp_register_script('csl_script', SLPLUS_PLUGINURL.'/core/js/csl.js', array('jquery'));
                
                // Setup Email Form Script If Selected
                //                
                //if (get_option(SLPLUS_PREFIX.'_email_form')==1) {
                //    wp_register_script(
                //            'slplus_emailform',
                //            SLPLUS_PLUGINURL.'/core/js/store-locator-emailform.js',
                 //           array('google_maps','slplus_map')
                 //           );                       
                //}                            
            }                        
        }     
        
        
        /*************************************
         * method: shutdown()
         * 
         * This is called whenever the WordPress shutdown action is called.
         */
        function shutdown() {
            
            // If we rendered an SLPLUS shortcode...
            //
            if (defined('SLPLUS_SHORTCODE_RENDERED') && SLPLUS_SHORTCODE_RENDERED) {
                
                // Register Load JavaScript
                //
                //wp_enqueue_script('slplus_functions');
                wp_enqueue_script('google_maps');                
                //wp_enqueue_script('slplus_map');
				wp_enqueue_script('csl_script');
                
               // if (get_option(SLPLUS_PREFIX.'_email_form')==1) {
                //    wp_enqueue_script('slplus_emailform');
               // }
                
                // Enqueue the style sheet
                //
                setup_stylesheet_for_slplus();                
                           
                // Force our scripts to load for badly behaved themes
                //
                wp_print_footer_scripts();
				/*
?>                
                <script type='text/javascript'>
                    jQuery(window).load(function() {
                            allScripts=document.getElementsByTagName('script');
                            
                            // Check our scripts were enqueued
                            //
                            if (allScripts.length-1 < 4) {
                                alert('<?php echo __('SLPLUS: The theme or a plugin is preventing trailing JavaScript from loading.',SLPLUS_PREFIX); ?>');
                                
                            // Check the Google Maps was loaded
                            //
                            } else if (typeof GLatLng == 'undefined' ) {        
                                alert('<?php echo __('SLPLUS: Google Map Interface did not load.\n\nCheck your Google API key and make sure you have API V2 enabled.',SLPLUS_PREFIX); ?>');
                        
                            // Yup, set our sl_load to prepopulate map data
                            //
                            } else if (document.getElementById("map")){
                                setTimeout("sl_load()",1000);
                                
                            }
                        }
                    );                
                </script>
<?php                       */
            }             
		}            
	}
}        
     

