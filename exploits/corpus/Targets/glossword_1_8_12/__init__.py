import os

def get_path(filename):
    return os.path.dirname(os.path.realpath(__file__)) + '/' + filename

name = "Glossword 1.8.12"
application_dir_mapping = [get_path("application"), "/var/www"]
database_filename = get_path("database.sql")
database_name = "glossword_1_8_12"
chroot_environment = "Debian5"
