
>> cp .env.example .env
>> composer install
>> php artisan key:gen

>> sudo apt-get update
>> sudo apt install php-mbstring php-xml php-bcmath php7.2-gd php7.2-intl php7.2-xsl
>> sudo apt install curl php-cli php-mbstring git unzip composer

## Files & Permissions
>> sudo chown -R $USER:www-data storage
>> sudo chown -R $USER:www-data bootstrap/cache
>> sudo chmod -R 775 storage
>> sudo chmod -R 775 bootstrap/cache
>
## Files & Permissions [MacApache]
>> sudo chown -R $USER:_www storage
>> sudo chown -R $USER:_www bootstrap/cache
>> sudo chmod -R 775 storage
>> sudo chmod -R 775 bootstrap/cache

 

##Database 
CREATE USER 'charden'@'localhost' IDENTIFIED BY 'Cappuccino@96';
CREATE USER 'charden'@'%' IDENTIFIED BY 'Cappuccino@96';
GRANT ALL PRIVILEGES ON *.* TO 'charden'@'%';
GRANT ALL PRIVILEGES ON *.* TO 'charden'@'localhost';

 
[Default Admin]
password: genesis102 
'name'=>"Site Admin",
'email'=>"",
'password'=>Hash::make("genesis102") ]);

 
