
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "Drupal 7.14"
    application_dir_mapping = [get_path("application"), "/var/www"]
    database_filename = get_path("database.sql")
    database_name = "drupal_7_14"
    chroot_environment = "Debian7"
