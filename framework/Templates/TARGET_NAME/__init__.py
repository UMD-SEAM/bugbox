# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.

import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    #TARGET_NAME is the name of the target as it will be refered to from 
    #bbmanage
    name = "[TARGET_NAME]"
    #INSTALLATION_PATH is the location in the live system the target will be 
    #installed. This is most commonly "/var/www" but can be different for some 
    #applications.
    application_dir_mapping = [get_path("application"), "[INSTALLATION_PATH]"]
    database_filename = get_path("database.sql")
    database_name = "[DATABASE_NAME]"
    plugins = [["[PLUGIN_NAME]",
                get_path("plugins/[PLUGIN_DIRECTORY]"),
                get_path("plugins/[PLUGIN_DIRECTORY]/database.sql"),
                #here [PLUGIN_DIR] means the name the target is expecting the 
                #plugin to use.  Some targets need this to be a specific name.
                "var/www/[TARGET_NAME]/.../[PLUGIN_DIRECTORY]/[PLUGIN_DIR]"],
               
               #all plugins should be uniquely named and be kept in their own 
               #directories
               ["[PLUGIN_NAME]",
                get_path("plugins/[PLUGIN_DIRECTORY]"),
                get_path("plugins/[PLUGIN_DIRECTORY]/database.sql"),
                #here [PLUGIN_DIR] means the name the target is expecting the 
                #plugin to use.  Some targets don't let you change this name.
                "var/www/[TARGET_NAME]/.../[PLUGIN_DIRECTORY]/[PLUGIN_DIR]"],
               ]
    #Different applications may require different chroot environments.
    #The options for this include "Debian5" and "Debian7"
    chroot_environment = "[CHROOT_ENVIRONMENT]"
