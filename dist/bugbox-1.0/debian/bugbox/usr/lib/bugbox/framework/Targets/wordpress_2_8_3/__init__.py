
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "Wordpress 2.8.3"
    application_dir_mapping = [get_path("application"), "/var/www"]
    database_filename = get_path("database.sql")
    database_name = "wordpress_2_8_3"
    plugins = [["Relevanssi 2.7.2",
                get_path("plugins/relevanssi_2_7_2"),
                get_path("plugins/relevanssi_2_7_2/database.sql"),
                "var/www/wordpress/wp-content/plugins/relevanssi"],
               ["Proplayer 4.7.7",
                get_path("plugins/proplayer_4_7_7"),
                get_path("plugins/proplayer_4_7_7/database.sql"),
                "var/www/wordpress/wp-content/plugins/proplayer"]]
    chroot_environment = "Debian7"
