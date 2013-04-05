import os

def get_path(filename):
    return os.path.dirname(os.path.realpath(__file__)) + '/' + filename

name = "AjaXplorer 2.5.1"
application_dir_mapping = [get_path("application"), "/var/www"]
#database_filename = get_path("database.sql")
#database_name = "wordpress_3_3_1_A"
chroot_environment = "Debian7"
