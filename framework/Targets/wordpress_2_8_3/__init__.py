import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "Wordpress 2.8.3"
    application_dir_mapping = [get_path("application"), "/var/www"]
    database_filename = None
    database_name = None
    plugins = None
    chroot_environment = "Debian7"
