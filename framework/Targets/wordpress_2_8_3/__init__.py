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
                "var/www/wordpress/wp-content/plugins/relevanssi_2_7_2"]]
    chroot_environment = "Debian7"
