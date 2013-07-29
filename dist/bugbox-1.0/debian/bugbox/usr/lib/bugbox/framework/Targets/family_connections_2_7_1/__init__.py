
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "Family Connections 2.7.1"
    application_dir_mapping = [get_path("application"), "/var/www"]
    database_filename = get_path("database.sql")
    database_name = "family_connections_2_7_1"
    chroot_environment = "Debian5"
