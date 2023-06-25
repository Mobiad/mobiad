
mysql> CREATE USER 'mobiad'@'localhost' IDENTIFIED BY 'mossy@45veckro'; 
mysql> CREATE USER 'mobiad'@'%' IDENTIFIED BY 'mossy@45veckro';
mysql> GRANT ALL PRIVILEGES ON *.* TO 'mobiad'@'%'; 
mysql> GRANT ALL PRIVILEGES ON *.* TO 'mobiad'@'localhost'; 

>> mysql -u mobiad -p

=========================
Database
=========================
http://159.89.8.177:9972
user: mobiad
pwd: mossy@45veckro
DB: mobiad


