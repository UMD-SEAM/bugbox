<?php

/***********************************************************************
* Class: SLPlus_AdminUI
*
* The Store Locator Plus admin UI class.
*
* Provides various UI functions when someone is an admin on the WP site.
*
************************************************************************/

if (! class_exists('SLPlus_AdminUI')) {
    class SLPlus_AdminUI {
        
        /******************************
         * PUBLIC PROPERTIES & METHODS
         ******************************/
        
        /*************************************
         * The Constructor
         */
        function __construct($params) {
        } 
        
        /*************************************
         * method: slpRenderCreatePageButton()
         *
         * Render The Create Page Button
         */
        function slpRenderCreatePageButton($locationID=-1,$storePageID=-1) {
            if ($locationID < 0) { return; }            
            $slpPageClass = (($storePageID>0)?'haspage_icon' : 'createpage_icon');
            print "<a   class='action_icon $slpPageClass' 
                        alt='".__('create page',SLPLUS_PREFIX)."' 
                        title='".__('create page',SLPLUS_PREFIX)."' 
                        href='".
                            ereg_replace("&createpage=".(isset($_GET['createpage'])?$_GET['createpage']:''), "",$_SERVER['REQUEST_URI']).
                            "&act=createpage&sl_id=$locationID&slp_pageid=$storePageID#a$locationID'
                   ></a>";            
        }  
        
        /*****************************************************
         * method: slpCreatePage()
         *
         * Create a new store pages page.
         */
         function slpCreatePage($locationID=-1)  {
            global $slplus_plugin, $wpdb;
            
            // If not licensed or incorrect location ID get out of here
            //
            if (
                !$slplus_plugin->license->packages['Store Pages']->isenabled ||
                ($locationID < 0)
                ) {
                return -1;
            } 

            // Get The Store Data
            //
            if ($store=$wpdb->get_row('SELECT * FROM '.$wpdb->prefix."store_locator WHERE sl_id = $locationID", ARRAY_A)) {            
                
                $slpStorePage = get_post($store['sl_linked_postid']);
                if (empty($slpStorePage->ID)) {
                    $store['sl_linked_postid'] = -1;
                }
                
                // Create the page
                //
                $slpNewListing = array(
                    'ID'            => (($store['sl_linked_postid'] > 0)?$store['sl_linked_postid']:''),
                    'post_type'     => 'store_page',
                    'post_status'   => 'publish',
                    'post_title'    => $store['sl_store'],
                    'post_content'  => call_user_func(array('SLPlus_AdminUI','slpCreatePageContent'),$store),
                    );
                return wp_insert_post($slpNewListing);
             }                
         }
         
        /*****************************************************
         * method: slpCreatePageContent()
         *
         * Creates the content for the page.  If plus pack is installed
         * it uses the plus template file, otherwise we use the hard-coded 
         * layout.
         *
         */         
         function slpCreatePageContent($store) {
             $content = '';

             // Default Content
             //
             $content .= "<span class='storename'>".$store['sl_store']."</span>\n";
             if ($store['sl_image']         !='') { 
                 $content .= '<img class="alignright size-full" title="'.$store['sl_store'].'" src="'.$store['sl_image'].'"/>'."\n"; 
             }
             if ($store['sl_address']       !='') { $content .= $store['sl_address'] . "\n"; }
             if ($store['sl_address2']      !='') { $content .= $store['sl_address2'] . "\n"; }
             
             if ($store['sl_city']          !='') { 
                $content .= $store['sl_city']; 
                if ($store['sl_state'] !='') { $content .= ', '; }
             }
             if ($store['sl_state']         !='') { $content .= $store['sl_state']; }
             if ($store['sl_zip']           !='') { $content .= " ".$store['sl_zip']."\n"; }
             if ($store['sl_country']       !='') { $content .= " ".$store['sl_country']."\n"; }
             if ($store['sl_description']   !='') { $content .= "<h1>Description</h1>\n<p>".$store['sl_description']."</p>\n"; }
             
             $slpContactInfo = '';
             if ($store['sl_phone'] !='') { $slpContactInfo .= __('Phone: ',SLPLUS_PREFIX).$store['sl_phone'] . "\n"; }
             if ($store['sl_email'] !='') { $slpContactInfo .= '<a href="mailto:'.$store['sl_email'].'">'.$store['sl_email']."</a>\n"; }
             if ($store['sl_url']   !='') { $slpContactInfo .= '<a href="'.$store['sl_url'].'">'.$store['sl_url']."</a>\n"; }
             if ($slpContactInfo    != '') { 
                $content .= "<h1>Contact Info</h1>\n<p>".$slpContactInfo."</p>\n"; 
             }

             return $content;             
         }
    }
}        
     

