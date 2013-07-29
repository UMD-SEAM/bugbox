
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "JBoss 4.2.0"
    application_dir_mapping = [os.path.realpath("application"), "/root"]
    chroot_environment = "Debian7"

    def get_start_service_script(self, target_system_dir):

        return

    def get_stop_service_script(self, target_system_dir):

        return


