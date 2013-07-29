
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


class Target():

    name = None
    application_dir_mapping = []
    database_filename = None
    database_name = None
    chroot_environment = None
    application_dir = None

    def __init__(self):
        self.application_dir = self.name.replace(' ', '_').replace('.','_').split('_')[0].lower() 
        return

    def get_path(self, efilename):
	raise NotImplementedError("get_path not implemented")

    def get_start_service_script(self, target_system_dir):
	raise NotImplementedError("get_path not implemented")

    def get_stop_service_script(self, target_system_dir):
	raise NotImplementedError("get_path not implemented")
