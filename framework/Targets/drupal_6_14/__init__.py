import os

def get_path(filename):
    return os.path.dirname(os.path.realpath(__file__)) + '/' + filename

name = "Drupal 6.14"
application_dir_mapping = [get_path("application"), "/var/www"]
database_filename = get_path("database.sql")
database_name = "drupal_6_14"
chroot_environment = "Debian7"
