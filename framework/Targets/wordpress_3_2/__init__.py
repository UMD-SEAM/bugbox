
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "Wordpress 3.2"
    application_dir_mapping = [get_path("application"), "/var/www"]
    database_filename = get_path("database.sql")
    database_name = "wordpress_3_2"
    plugins = [["GigPress 2.1.10", 
                get_path("plugins/gigpress_2_1_10"),
                get_path("plugins/gigpress_2_1_10/database.sql"), 
                "/var/www/wordpress/wp-content/plugins/gigpress"],
               ["PhotoSmash 1.0.1", 
                get_path("plugins/photosmash-galleries_1_0_1"),
                get_path("plugins/photosmash-galleries_1_0_1/database.sql"), 
                "/var/www/wordpress/wp-content/plugins/photosmash-galleries"],
               ["Schreikasten 0.14.13", 
                get_path("plugins/schreikasten_0_14_13"),
                get_path("plugins/schreikasten_0_14_13/database.sql"), 
                "/var/www/wordpress/wp-content/plugins/schreikasten"],
               ["Pretty Link 1.5.2", 
                get_path("plugins/pretty-link_1_5_2"),
                get_path("plugins/pretty-link_1_5_2/database.sql"), 
                "/var/www/wordpress/wp-content/plugins/pretty-link"],
               ["Knews 1.1.0",
                get_path("plugins/knews_1_1_0"),
                None,
                "/var/www/wordpress/wp-content/plugins/knews"],
                ["Artiss 2.0.1",
                get_path("plugins/artiss-2.0.1"),                        
                None,                           
                "/var/www/wordpress/wp-content/plugins/artiss-2.0.1"],
               ["Newsletter Manager 1.0.2",
                get_path("plugins/newsletter_manager_1.0.2"),
                get_path("plugins/newsletter_manager_1.0.2/database.sql"),
                "/var/www/wordpress/wp-content/plugins/newsletter_manager"],
               ["CMS Tree Page View 0.8.8",
                get_path("plugins/cms_tree_page_view_0_8_8"),
                get_path("plugins/cms_tree_page_view_0_8_8/database.sql"),
                "/var/www/wordpress/wp-content/plugins/cms-tree-page-view"],
               ["yolink Search Plugin 1.1.4",
                get_path("plugins/yolink_1_1_4"), 
                get_path("plugins/yolink_1_1_4/database.sql"),
                "/var/www/wordpress/wp-content/plugins/yolink-search"],
               ["SH Slideshow Plugin 3.1.4",
                get_path("plugins/sh_slideshow_4_1"),
                get_path("plugins/sh_slideshow_4_1/database.sql"),
                "/var/www/wordpress/wp-content/plugins/sh-slideshow"]]


    chroot_environment = "Debian7"
