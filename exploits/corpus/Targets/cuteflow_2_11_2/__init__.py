import os

def get_path(filename):
    return os.path.dirname(os.path.realpath(__file__)) + '/' + filename

name = "CuteFlow 2.11.2"
application_dir_mapping = [get_path("application"), "/var/www"]
database_filename = get_path("database.sql")
database_name = "cuteflow_2_11_2"
chroot_environment = "Debian7"
