<?php

if (! class_exists('SLPlus_Activate')) {
    class SLPlus_Activate {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        /*************************************
         * The Constructor
         */
        function __construct($params = null) {
            // Do the setting override or initial settings.
            //
            foreach ($params as $name => $value) {
                $this->$name = $value;
            }
        } 
        
        /***********************************
         ** function: slplus_dbupdater
         ** 
         ** Update the data structures on new db versions.
         **
         **/ 
        function dbupdater($sql,$table_name) {
            global $wpdb;
             
            // New installation
            //
            if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                return 'new';
                
            // Installation upgrade
            //
            } else {        
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                return 'updated';    
            }   
        }
        
        /*************************************
         * Update main table
         */
        function install_main_table() {
            global $wpdb;
            
            $charset_collate = '';
            if ( ! empty($wpdb->charset) )
                $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
            if ( ! empty($wpdb->collate) )
                $charset_collate .= " COLLATE $wpdb->collate";	
            $table_name = $wpdb->prefix . "store_locator";
            $sql = "CREATE TABLE $table_name (
                    sl_id mediumint(8) unsigned NOT NULL auto_increment,
                    sl_store varchar(255) NULL,
                    sl_address varchar(255) NULL,
                    sl_address2 varchar(255) NULL,
                    sl_city varchar(255) NULL,
                    sl_state varchar(255) NULL,
                    sl_zip varchar(255) NULL,
                    sl_country varchar(255) NULL,
                    sl_latitude varchar(255) NULL,
                    sl_longitude varchar(255) NULL,
                    sl_tags mediumtext NULL,
                    sl_description text NULL,
                    sl_email varchar(255) NULL,
                    sl_url varchar(255) NULL,
                    sl_hours varchar(255) NULL,
                    sl_phone varchar(255) NULL,
                    sl_image varchar(255) NULL,
                    sl_private varchar(1) NULL,
                    sl_neat_title varchar(255) NULL,
                    sl_linked_postid int NULL,
                    sl_pages_url varchar(255) NULL,
                    sl_lastupdated  timestamp NOT NULL default current_timestamp,			
                    PRIMARY KEY  (sl_id),
                    INDEX (sl_store),
                    INDEX (sl_longitude),
                    INDEX (sl_latitude)
                    ) 
                    $charset_collate
                    ";
                
            // If we updated an existing DB, do some mods to the data
            //
            if ($this->dbupdater($sql,$table_name) === 'updated') {
                
                // We are upgrading from something less than 2.0
                //
                if (floatval($sl_installed_ver) < 2.0) {
                    dbDelta("UPDATE $table_name SET sl_lastupdated=current_timestamp " . 
                        "WHERE sl_lastupdated < '2011-06-01'"
                        );
                }   
                if (floatval($sl_installed_ver) < 2.2) {
                    dbDelta("ALTER $table_name MODIFY sl_description text ");
                }
            }         
            
            //set up google maps v3
            $old_option = get_option('sl_map_type');
            $new_option = 'roadmap';
            switch ($old_option) {
                case 'G_NORMAL_MAP':
                    $new_option = 'roadmap';
                    break;
                case 'G_SATELLITE_MAP':
                    $new_option = 'satellite';
                    break;
                case 'G_HYBRID_MAP':
                    $new_option = 'hybrid';
                    break;
                case 'G_PHYSICAL_MAP':
                    $new_option = 'terrain';
                    break;
                default:
                    $new_option = 'roadmap';
                    break;
            }
            update_option('sl_map_type', $new_option);
        }
        
        /*************************************
         * Install reporting tables
         */
        function install_reporting_tables() {
            global $wpdb;

    
            $charset_collate = '';
            if ( ! empty($wpdb->charset) )
                $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
            if ( ! empty($wpdb->collate) )
                $charset_collate .= " COLLATE $wpdb->collate";

            //*****
            //***** CHANGE sl_db_version IN slplus_dbupdater() 
            //***** ANYTIME YOU CHANGE THIS STRUCTURE
            //*****		
            $table_name = $wpdb->prefix . "slp_rep_query";
            $sql = "CREATE TABLE $table_name (
                    slp_repq_id    bigint(20) unsigned NOT NULL auto_increment,
                    slp_repq_time  timestamp NOT NULL default current_timestamp,
                    slp_repq_query varchar(255) NOT NULL,
                    slp_repq_tags  varchar(255),
                    slp_repq_address varchar(255),
                    slp_repq_radius varchar(5),
                    PRIMARY KEY  (slp_repq_id),
                    INDEX (slp_repq_time)
                    )
                    $charset_collate						
                    ";
            $this->dbupdater($sql,$table_name);	
            

            //*****
            //***** CHANGE sl_db_version IN slplus_dbupdater() 
            //***** ANYTIME YOU CHANGE THIS STRUCTURE
            //*****		
            $table_name = $wpdb->prefix . "slp_rep_query_results";
            $sql = "CREATE TABLE $table_name (
                    slp_repqr_id    bigint(20) unsigned NOT NULL auto_increment,
                    slp_repq_id     bigint(20) unsigned NOT NULL,
                    sl_id           mediumint(8) unsigned NOT NULL,
                    PRIMARY KEY  (slp_repqr_id),
                    INDEX (slp_repq_id)
                    )
                    $charset_collate						
                    ";

            // Install or Update the slp_rep_query_results table
            //
            $slplusNewOrUpdated = $this->dbupdater($sql,$table_name);     
        }
        
        /*************************************
         * Add roles and caps
         */
        function add_splus_roles_and_caps() {
            $role = get_role('administrator');
            if (!$role->has_cap('manage_slp')) {
                $role->add_cap('manage_slp');
            }
        }
        
        /*************************************
         * Move upload directories
         */
        function move_upload_directories() {
            $sl_upload_path = ABSPATH.'wp-content/uploads/sl-uploads';
            $sl_path = ABSPATH.'wp-content/plugins/'.SLPLUS_PLUGINDIR;
            if (!is_dir(ABSPATH . "wp-content/uploads")) {
                mkdir(ABSPATH . "wp-content/uploads", 0755);
            }
            if (!is_dir($sl_upload_path)) {
                mkdir($sl_upload_path, 0755);
            }
            if (!is_dir($sl_upload_path . "/custom-icons")) {
                mkdir($sl_upload_path . "/custom-icons", 0755);
            }	
            if (is_dir($sl_path . "/languages") && !is_dir($sl_upload_path . "/languages")) {
                csl_copyr($sl_path . "/languages", $sl_upload_path . "/languages");
            }
            if (is_dir($sl_path . "/images") && !is_dir($sl_upload_path . "/images")) {
                csl_copyr($sl_path . "/images", $sl_upload_path . "/images");
            }
        }
        
        /*************************************
         * Updates the plugin
         */
        function update($slplus_plugin, $old_version) {
            $updater = new SLPlus_Activate(array(
                'plugin' => $slplus_plugin,
                'old_version' => $old_version,
            ));
            
            $updater->install_main_table();
            $updater->install_reporting_tables();
            $updater->add_splus_roles_and_caps();
            $updater->move_upload_directories();
        }
    }
}