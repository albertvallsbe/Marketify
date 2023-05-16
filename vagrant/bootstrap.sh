#!/bin/sh

# Actualizar repositorios
sudo apt-get update

# Instalar Apache
sudo apt-get install -y apache2

# Agregar repositorio de PHP 8.2
sudo apt-get install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update

# Instalar PHP 8.2 y extensiones requeridas por Laravel
sudo sudo apt-get install -y apt-transport-https

sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg

sudo echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list

sudo apt-get update
sudo apt-get install -y php8.2 libapache2-mod-php8.2 php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip


# Instalar Composer
sudo apt-get install -y composer

# Crear un nuevo proyecto Laravel
sudo composer create-project --prefer-dist laravel/laravel /home/marketify/site

# Configurar Apache para Laravel
sudo tee /etc/apache2/sites-available/000-default.conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /home/marketify/site/public

    <Directory /home/marketify/site/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

sudo a2enmod rewrite
sudo service apache2 restart

# sudo a2enmod php8.2
# sudo service apache2 restart

# Cambiar el propietario de los archivos y directorios de Laravel al usuario de Apache
sudo chown -R www-data:www-data /home/marketify/site

# Establecer permisos adicionales en los directorios de Laravel
sudo chmod -R 775 /home/marketify/site/storage
sudo chmod -R 775 /home/marketify/site/bootstrap/cache
sudo chmod -R 775 /home/marketify/site/public

# Reiniciar Apache nuevamente
sudo service apache2 restart

# Instalar MariaDB
sudo apt-get install -y mariadb-server mariadb-client

# Crear la base de datos
sudo mysql << EOF
CREATE DATABASE marketifyBBDD charset='utf8mb4' collate='utf8mb4_unicode_ci';
EOF

# Configurar MariaDB
sudo cp /vagrant/50-server.cnf /etc/mysql/mariadb.conf.d/50-server.cnf

# Crear el usuario 'admin' con acceso remoto
sudo mysql << EOF
CREATE OR REPLACE USER 'admin'@'%' IDENTIFIED BY '1234';
GRANT ALL ON *.* TO 'admin'@'%';
FLUSH PRIVILEGES;
EOF

# Reiniciar MariaDB
sudo systemctl restart mariadb

cd /home/marketify/site
php artisan migrate:refresh --seed