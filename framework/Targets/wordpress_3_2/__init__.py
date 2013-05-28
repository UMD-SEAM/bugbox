import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "Wordpress 3.2"
    application_dir_mapping = [get_path("application"), "/var/www"]
    database_filename = get_path("database.sql")
    database_name = "wordpress_3_2"
    plugins = [["GigPress 2.1.10", get_path("plugins/gigpress-2.1.10"), "/var/www/wordpress/wp-content/plugins/gigpress"]]
    chroot_environment = "Debian7"
