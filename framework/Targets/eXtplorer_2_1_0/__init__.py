import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "eXtplorer 2.1"
    application_dir_mapping = [get_path("application"), "/var/www"]
    chroot_environment = "Debian5"
