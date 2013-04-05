import os

def get_path(filename):
    return os.path.dirname(os.path.realpath(__file__)) + '/' + filename

name = "Family Connections 2.7.1"
application_dir_mapping = [get_path("application"), "/var/www"]
database_filename = get_path("database.sql")
database_name = "family_connections_2_7_1"
chroot_environment = "Debian5"
