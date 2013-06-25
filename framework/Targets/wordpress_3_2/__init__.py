import os
from framework.Targets import ApacheTarget

class Target(ApacheTarget):

    def get_path(filename):
        return os.path.dirname(os.path.realpath(__file__)) + '/' + filename
    
    name = "Wordpress 3.2"
    application_dir_mapping = [get_path("application"), "/var/www"]
    database_filename = get_path("database.sql")
    database_name = "wordpress_3_2"
    plugins = [["GigPress 2.1.10", 
                get_path("plugins/gigpress_2_1_10"),
                get_path("plugins/gigpress_2_1_10/database.sql"), 
                "/var/www/wordpress/wp-content/plugins/gigpress"],
               ["PhotoSmash 1.0.1", 
                get_path("plugins/photosmash-galleries_1_0_1"),
                None,
                #get_path("plugins/photosmash-galleries/database.sql"), 
                "/var/www/wordpress/wp-content/plugins/photosmash-galleries"],
               ["Schreikasten 0.14.13", 
                get_path("plugins/schreikasten_0_14_13"),
                get_path("plugins/schreikasten_0_14_13/database.sql"), 
                "/var/www/wordpress/wp-content/plugins/schreikasten"],
               ["Pretty Link 1.5.2", 
                get_path("plugins/pretty-link_1_5_2"),
                get_path("plugins/pretty-link_1_5_2/database.sql"), 
                "/var/www/wordpress/wp-content/plugins/pretty-link"]]

    chroot_environment = "Debian7"
