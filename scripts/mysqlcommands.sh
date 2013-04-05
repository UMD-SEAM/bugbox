mysqldump --opt -u [uname] -p[pass] [dbname] > [backupfile.sql]
mysql -u [uname] -p[pass] [db_to_restore] < [backupfile.sql]
mysqlimport -u [uname] -p[pass] [dbname] [backupfile.sql]
mysqldump -u root -pconnection452 wordpress_3_3_1_A > wordpress_3_3_1_A.sql
mysql -u root -pconnection452 wordpress_3_3_1_A < wordpress_3_3_1_A.sql
