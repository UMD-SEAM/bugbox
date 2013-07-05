
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import os
from framework.Targets import Target
from framework.Targets import TargetPluginNotFound


class ApacheTarget(Target):

    name = None
    application_dir_mapping = []
    database_filename = None
    database_name = None
    plugins = []
    plugin_src = None
    plugin_dest = None
    plugin_db = None
    chroot_environment = None
    application_dir = None

    def __init__(self):
        Target.__init__(self)
        return

    def get_path(self, filename):
	raise NotImplementedError("get_path not implemented")

    def get_plugin(self, plugin_name):
        
        try:
            for name, src, db, dest in self.plugins:
                if name == plugin_name:
                    return (src, db, dest)
        except AttributeError:
            pass
        
        raise TargetPluginNotFound(plugin_name)


    def set_plugin(self, plugin_name):
        self.plugin_src, self.plugin_db, self.plugin_dest = self.get_plugin(plugin_name)
        return

    def get_start_service_script(self, target_system_dir):

	if not (target_system_dir and 
		self.application_dir_mapping):
	    raise ValueError

	start_script = ["chown www-data %s%s/%s"              %(target_system_dir,
								self.application_dir_mapping[1],
								self.application_dir),
			"chgrp www-data %s%s/%s"              %(target_system_dir,
								self.application_dir_mapping[1],
								self.application_dir),
			"chroot %s /etc/init.d/apache2 start" %(target_system_dir,),
			"while [ \"`pgrep apache2`\" = \"\" ]; do sleep 0.5; done;"] # wait for apache
        
        if self.plugin_db:
            start_script += ["mysql -u root -pconnection452 < %s" %(self.plugin_db,)]
        elif self.database_name:
            start_script += ["mysql -u root -pconnection452 < %s" %(self.database_filename,)]

        if self.plugin_src: 
            start_script += ["mkdir %s/%s"            %(target_system_dir,
                                                        self.plugin_dest),
                             #"mount -o bind %s %s/%s" %(self.plugin_src,
                             "cp -pR %s/plugin/* %s/%s"      %(self.plugin_src,
                                                        target_system_dir,
                                                        self.plugin_dest)]

	return start_script

    def get_stop_service_script(self, target_system_dir):
	
	if not (target_system_dir):
	    raise ValueError

	stop_script = ["chroot %s /etc/init.d/apache2 stop" %(target_system_dir,),
		       "while pgrep \"apache2\">/dev/null; do sleep 0.5; done;"]

	if self.plugin_src: 
	    stop_script += ["rm -rf %s/%s"  %(target_system_dir,
					      self.plugin_dest)]
	return stop_script

